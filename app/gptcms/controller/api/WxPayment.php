<?php
declare (strict_types = 1);

namespace app\gptcms\controller\api;
use app\BaseController;
use think\facade\Db;
use think\facade\Log;

class WxPayment extends BaseController
{
    static public function getConfig($wid)
    {
        $pay_config = Db::table('kt_gptcms_pay_config')->where(['wid'=>$wid])->find();
        $r = explode(',', $pay_config['config']);
        $config = [
            'appid' => $r[0] ?? '',
            'mch_id' => $r[1] ?? '',
            'key' => $r[2] ?? '',
            'appsecret' => $r[3] ?? '',
        ]; 
        $platform = platform(); //平台
        if($platform == 'mpapp' ){
            $miniprogram = Db::table('kt_gptcms_miniprogram')->where('wid',$wid)->find();
            $config['appid'] = $miniprogram['appid'] ?? '';
            $config['appsecret'] = $miniprogram['appsecret'] ?? '';
        }else{
            $gzh = Db::table('kt_gptcms_wxgzh')->where('wid',$wid)->find();
            $config['appid'] = $gzh['appid'] ?? '';
            $config['appsecret'] = $gzh['appsecret'] ?? '';
        }
        
        return $config;
    }

    /**
     * 通过跳转获取用户的openid，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://mp.yunzd.cn/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     * @return 用户的openid
     */
    static public function getOpenid($wid)
    {
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);
            $url = self::__CreateOauthUrlForCode($wid,$baseUrl);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $openid = self::getOpenidFromMp($wid,$code);
            return $openid;
        }
    }

    /** 
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code(可以直接在前端处理拿到code后，直接使用这个方法)
     * @return openid
     */
    static public function getOpenidFromMp($wid,$code)
    {
        $url = self::__CreateOauthUrlForOpenid($wid,$code);
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        //取出openid
        $data = json_decode($res,true);
        $openid = $data['openid'] ?? '';
        return $openid;
    }

    /**
     * 拼接签名字符串
     * @param array $urlObj
     * @return 返回已经拼接好的字符串
     */
    static public function toUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v)
        {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }
        
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     * @return 返回构造好的url
     */
    static public function __CreateOauthUrlForCode($wid,$redirectUrl)
    {
        $config = self::getConfig($wid);
        $urlObj["appid"] = $config['appid'];
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = self::toUrlParams($urlObj);
        return "https://mp.yunzd.cn/connect/oauth2/authorize?".$bizString;
    }

    /**
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     * @return 请求的url
     */
    static public function __CreateOauthUrlForOpenid($wid,$code)
    {
        $config = self::getConfig($wid);
        $urlObj["appid"] = $config['appid'];
        $urlObj["secret"] = $config['appsecret'];
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = self::toUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }

    /**
     * 微信支付
     * @param $wid:wid 
     * @param $openid:微信用户openid 
     * @param $money:支付金额（单位元） 
     * @param $notifyUrl:异步通知地址 
     * @param $orderNo:自定义订单号 
     * @param $tradetype:交易类型 
     * @param $remark:备注
     * @param $attach:附加数据，支付通知中原样返回(可设置为wid的值，用于支付通知使用)
     * @return array|bool
     */
    static public function pay($wid, $openid, $money, $notifyUrl, $orderNo, $tradetype = 'JSAPI', $remark = '', $attach = '')
    {
        Log::error(date('Y-m-d H:i:s').'微信下单开始');
        if (!$attach) {
            $attach = json_encode([]);
        }
        $config = self::getConfig($wid);//配置
        $time = time();
        $nonce_str = md5(time() . rand() . rand() . '微信支付');
        $body = $remark ?$remark:"产品下单" ;
        $params = [
            'appid'            => $config['appid'],//小程序ID
            'mch_id'           => $config['mch_id'],//商户号
            'nonce_str'        => $nonce_str,//随机字符串
            'body'             => $body,//商品描述(可以拼接字符串生成)
            'out_trade_no'     => $orderNo,//商品订单号
            'total_fee'        => $money * 100,//总金额
            'spbill_create_ip' => '192.168.1.1',//终端IP
            'notify_url'       => $notifyUrl,//通知地址
            'trade_type'       => $tradetype,//交易类型(小程序固定是JSAPI)
            'openid'           => $openid,//用户的openid
            'attach'           => $attach
        ];
        if($tradetype == 'MWEB'){
            $params['scene_info'] = '{"h5_info":{"type":"Wap","wap_url":"'.app('request')->host().'","wap_name":"支付"}}';
        }
        $params['sign'] = self::makeSign(array_filter($params), $config['key']);
        $xml_params = self::arrayToCdataXml($params);
        $resp = self::postXmlCurl('https://api.mch.weixin.qq.com/pay/unifiedorder', $xml_params);
        $resp_arr = self::xmlToArray($resp);
        Log::error($resp_arr);
        if ($resp_arr['return_code'] == 'SUCCESS') {//请求下单成功
            if ($resp_arr['result_code'] == 'SUCCESS') {//生成预支付订单成功
                //签名
                $paySign = self::makeSign([
                    'appId'     => $config['appid'],
                    'timeStamp' => "" . $time,
                    'nonceStr'  => $resp_arr['nonce_str'],
                    'package'   => 'prepay_id=' . $resp_arr['prepay_id'],
                    'signType'  => 'MD5',
                ], $config['key']);
                //要返回的数据(公众号端拉起支付必要参数)
                $respData = [
                    'appId'     => $config['appid'],
                    'timeStamp' => "" . $time,
                    'nonceStr'  => $resp_arr['nonce_str'],
                    'package'   => 'prepay_id=' . $resp_arr['prepay_id'],
                    'signType'  => 'MD5',
                    'paySign'   => $paySign,
                ];
                if($tradetype == 'MWEB'){
                    // $respData['mweb_url'] = $resp_arr['mweb_url'].'&redirect_url='.urlencode(app('request')->host().'/app/kt_ai/h5/#/pages/my/my');
                    $respData['mweb_url'] = $resp_arr['mweb_url'];
                }
                if($tradetype == 'NATIVE'){
                    $respData['code_url'] = self::makeCode($resp_arr['code_url'],$wid);
                }
                return $respData;
            }else{
                return ['status'=>'error','msg'=>$resp_arr['return_msg']?$resp_arr['return_msg']:$resp_arr['err_code_des']];
            }
        }else{
            return ['status'=>'error','msg'=>$resp_arr['return_msg']?$resp_arr['return_msg']:$resp_arr['err_code_des']];
        }
    }

    /**
     * 支付结果通知,通知数据举例如下：
     * <xml>
     * <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
     * <attach><![CDATA[支付测试]]></attach> （attach：自定义参数）
     * <bank_type><![CDATA[CFT]]></bank_type>
     * <fee_type><![CDATA[CNY]]></fee_type>
     * <is_subscribe><![CDATA[Y]]></is_subscribe>
     * <mch_id><![CDATA[10000100]]></mch_id>
     * <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
     * <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
     * <out_trade_no><![CDATA[1409811653]]></out_trade_no>  （out_trade_no：自定义订单号，可用于查询订单）
     * <result_code><![CDATA[SUCCESS]]></result_code>
     * <return_code><![CDATA[SUCCESS]]></return_code>
     * <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
     * <time_end><![CDATA[20140903131540]]></time_end>
     * <total_fee>1</total_fee>
     * <coupon_fee><![CDATA[10]]></coupon_fee>
     * <coupon_count><![CDATA[1]]></coupon_count>
     * <coupon_type><![CDATA[CASH]]></coupon_type>
     * <coupon_id><![CDATA[10000]]></coupon_id>
     * <trade_type><![CDATA[JSAPI]]></trade_type>
     * <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id> （transaction_id：微信支付订单号）
     * </xml>
     */
    static public function payNotify()
    {
        ini_set('date.timezone', 'Asia/Shanghai');
        error_reporting(0);
        $xml = file_get_contents("php://input");
        $pay_resp = self::xmlToArray($xml);
        $wid = $pay_resp['attach'];
        $config = self::getConfig($wid);//配置
        //验证签名
        if (!self::confirmSign($pay_resp, $config['key'])){
            $return = "<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>";
            return ['status'=>'error','msg'=>'签名失败','return'=>$return];
        }
        $return = "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
        return ['status'=>'success','pay_resp'=>$pay_resp,'return'=>$return];
    }

    static public function makeSign($params, $key)
    {
        ksort($params);
        $string = self::arrayToString($params);  //参数进行拼接key=value&k=v
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $key;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    static public function arrayToString($params)
    {
        $string = '';
        if (!empty($params)) {
            $array = [];
            foreach ($params as $key => $value) {
                $array[] = $key . '=' . $value;
            }
            $string = implode("&", $array);
        }
        return $string;
    }

    static public function arrayToCdataXml($params)
    {
        if (!is_array($params) || count($params) <= 0) {
            return false;
        }
        $xml = "<xml>";
        foreach ($params as $key => $val) {
            if (is_numeric($val)) {
                 $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    static public function xmlToArray($xml)
    {
        $res = simplexml_load_string($xml, 'SimpleXmlElement', LIBXML_NOCDATA);
        return json_decode(json_encode($res), true);
    }

    static public function postXmlCurl($url, $xml, $useCert = false, $second = 5)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($useCert == true) {
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
        }
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }

    static public function confirmSign($data,$key,$sign_key='sign')
    {
        $sign = self::arrGet($data, $sign_key,false);
        if (!$sign){
            return false;
        }
        $data2 = $data;
        unset($data2['sign']);
        $sign2 = self::makeSign($data2, $key);
        return $sign==$sign2;
    }

    static public function arrGet($arr, $key, $default = null)
    {
        if (key_exists($key, $arr)) {
            return $arr[$key];
        }
        return $default;
    }

    static public function makeCode($data,$prefix)
    {
        header("Content-type:image/png");
        require "../extend/Phpqrcode/phpqrcode.php";
        $codePath = $_SERVER['DOCUMENT_ROOT'];
        $qrCode  = new \QRcode();
        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/static/gptcms/paycode')) mkdir($_SERVER['DOCUMENT_ROOT'].'/static/gptcms/paycode',0777);
        $codeName = '/static/gptcms/paycode/'.$prefix.'_'.time().'.png';
        $res = $qrCode->png($data,$codePath.$codeName,'H',6);
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$codeName;
    }
    
}
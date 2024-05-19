<?php
declare (strict_types = 1);

namespace app\gptcms\controller\api;
use app\BaseController;
use think\facade\Db;
use think\facade\Log;

class XcxPay extends BaseController
{
    
    public function xcxlogin()
    {
    	$order_id = request()->param('order_id');
    	$code = request()->param('code');
    	if(!$order_id) return error('缺少必要参数order_id');
    	if(!$code) return error('缺少必要参数code');
    	$order = Db::table('kt_gptcms_pay_order')->find($order_id);
    	if(!$order) return error('订单不存在');
    	$wid = $order['wid'];
    	if(!$wid) return error('错误');
    	$config = Db::table('kt_gptcms_miniprogram')->where('wid',$wid)->find();
        if(!$config) return error("小程序暂不可用");
        
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $config['appid'] . '&secret=' . $config['appsecret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        $wxdata = json_decode(curlGet($url),true);
        if(empty($wxdata['openid'])) return error('登录失败',$wxdata);
        return success('授权登录成功',['openid'=>$wxdata['openid']]);
    }

    public function xcxpay()
    {
        $order_id = request()->param('order_id');
        $openid = request()->param('openid');
        if(!$order_id) return error('缺少必要参数order_id');
        if(!$openid) return error('缺少必要参数openid');
        $order = Db::table('kt_gptcms_pay_order')->find($order_id);
        if(!$order) return error('订单不存在');
        $wid = $order['wid'];
        if(!$wid) return error('错误');
        $config = Db::table('kt_gptcms_miniprogram')->where('wid',$wid)->find();
        if(!$config) return error("小程序暂不可用");

        $gptcms_pay_config = Db::table('kt_gptcms_pay_config')->where(['wid'=>$wid])->find();
        $r = explode(',', $gptcms_pay_config['config']);
        $config['mch_id'] = $r[1] ?? '';
        $config['key'] = $r[2] ?? '';

        $out_trade_no = time().rand(1000,9999);
        $notifyUrl =  request()->host().'/gptcms/api/paynotify/webchat';
        $payConfig = $this->prepay($wid,$openid,$order['amount'],$notifyUrl,$out_trade_no,$config);
        if($payConfig && isset($payConfig['status']) && $payConfig['status'] == 'error') return json($payConfig);
        
        $payid = Db::table('kt_gptcms_pay')->insertGetId([
            'wid' => $wid,
            'common_id' => $order['common_id'],
            'orderid' => $order_id,
            'out_trade_no' => $out_trade_no,
            'order_bh' => $order['order_bh'],
            'uip' => '',
            'amount' => $order['amount'],
            'ifok' => 0,
            'create_time' => date("Y-m-d H:i:s"),
            'update_time' => date("Y-m-d H:i:s"),
        ]);
        return success('获取成功',['payconfig'=>$payConfig]);
    }

    public function prepay($wid,$openid,$money,$notifyUrl,$orderNo,$config)
    {
        Log::error(date('Y-m-d H:i:s').'微信小程序下单开始');
        $attach = json_encode([]);
        $time = time();
        $nonce_str = md5(time() . rand() . rand() . '微信支付');
        $body = "产品下单";
        $params = [
            'appid'            => $config['appid'],//小程序ID
            'mch_id'           => $config['mch_id'],//商户号
            'nonce_str'        => $nonce_str,//随机字符串
            'body'             => $body,//商品描述(可以拼接字符串生成)
            'out_trade_no'     => $orderNo,//商品订单号
            'total_fee'        => $money * 100,//总金额
            'spbill_create_ip' => '192.168.1.1',//终端IP
            'notify_url'       => $notifyUrl,//通知地址
            'trade_type'       => 'JSAPI',//交易类型(小程序固定是JSAPI)
            'openid'           => $openid,//用户的openid
            'attach'           => $attach
        ];
        $params['sign'] = WxPayment::makeSign(array_filter($params), $config['key']);
        $xml_params = WxPayment::arrayToCdataXml($params);
        $resp = WxPayment::postXmlCurl('https://api.mch.weixin.qq.com/pay/unifiedorder', $xml_params);
        $resp_arr = WxPayment::xmlToArray($resp);
        Log::error($resp_arr);
        if ($resp_arr['return_code'] == 'SUCCESS') {//请求下单成功
            if ($resp_arr['result_code'] == 'SUCCESS') {//生成预支付订单成功
                //签名
                $paySign = WxPayment::makeSign([
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
                return $respData;
            }else{
                return ['status'=>'error','msg'=>$resp_arr['err_code_des']?$resp_arr['err_code_des']:$resp_arr['return_msg']];
            }
        }else{
            return ['status'=>'error','msg'=>$resp_arr['return_msg']?$resp_arr['return_msg']:$resp_arr['err_code_des']];
        }
    }
    
}
<?php 
namespace app\gptcms\model;
use think\facade\Db;
use think\facade\Session;
use app\gptcms\controller\api\WxPayment;
require_once(app_path().'/Alipay/AliPay.php');

class PayModel 
{
	/**
	 * 统一支付
	 * @param $wid:wid 
     * @param $openid:用户openid 
     * @param $money:支付金额（单位元） 
     * @param $notifyUrl:异步通知地址 
     * @param $orderNo:自定义订单号 
     * @param $remark:备注
     * @param $attach:附加数据，支付通知中原样返回(可设置为wid的值，用于支付通知使用)
     * @param array $request [paytype:支付方式 其他]
     * @return array|bool
	 */
 	static public function commonPrepay($wid, $openid, $money, $notifyUrl, $orderNo, $remark = '', $attach = '', $request = [])
 	{
 		$platform = platform(); //平台
 		$ismobile = ismobile(); //是否手机
 		$paytype = $request['paytype']??'wechat'; //支付方式

          if($paytype == 'wechat'){
               if($platform == 'h5' && !$ismobile){ //pc端
                    $payConfig = WxPayment::pay($wid,$openid,$money,$notifyUrl,$orderNo,'NATIVE');
               }elseif($platform == 'h5' && $ismobile){ //手机端浏览器(非微信环境)
                    $payConfig = WxPayment::pay($wid,$openid,$money,$notifyUrl,$orderNo,'MWEB');
               }elseif($platform == 'wxapp' && !$ismobile){ //PC端微信
                    $payConfig = WxPayment::pay($wid,$openid,$money,$notifyUrl,$orderNo,'NATIVE');
               }else{ //微信环境或小程序
                    $payConfig = WxPayment::pay($wid,$openid,$money,$notifyUrl,$orderNo,'JSAPI');
               }
               return $payConfig;
          }else if($paytype == 'alipay'){
               $request["notify_url"] = $notifyUrl;
               $res = new \AliPay($request);
               $payConfig = $res->pay($remark,$remark,$orderNo,$money);
               return $payConfig;
          }
	}

}

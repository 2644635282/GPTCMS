<?php
namespace paySDK;
// use Phpqrcode\Qrcode;
use paySDK\Wxpay\WebchatPay;
use paySDK\Alipay\AliPay;
use think\facade\Db;
class NewPay {
    
    public static $alipay;
    
    public static $webchat;
    
    public static function init($uid){
        $config = Db::table('kt_base_pay_config')->where(['uid'=>$uid,'type'=>'wx'])->find();
        if(!$config || !$config['config']) return '未配置微信支付';
        // $alipay = explode(',',$config['alipaywap']);
        $wxpay  = explode(',',$config['config']);
        // self::$alipay = [
        //     'app_id' => isset($alipay[0]) ? $alipay[0] : null,
        //     'merchant_private_key'=> isset($alipay[1]) ? $alipay[1] : null,
        //     'alipay_public_key'=> isset($alipay[2]) ? $alipay[2] : null,
        // ];
        self::$webchat =[
            'appid' => isset($wxpay[0]) ? $wxpay[0] : null,
            'mch_id' => isset($wxpay[1]) ? $wxpay[1] : null,
            'key' => isset($wxpay[2]) ? $wxpay[2] : null,
        ];
    }
    
    //获取pc端二维码
    public static function Alipay($body,$subject,$out_trade_no,$total_fee,$notify_url=false){
        if($notify_url) self::$alipay['notify_url'] = $notify_url;
        $alipay = new AliPay(self::$alipay);
        $result = $alipay->pay($body, $subject,$out_trade_no,$total_fee);
        return $result ? self::makeCode($result,'alipay') : null;
    }
    //获取pc端url
    public static function Alipcpay($body,$subject,$out_trade_no,$total_fee,$notify_url=false){
        if($notify_url) self::$alipay['notify_url'] = $notify_url;
        $alipay = new AliPay(self::$alipay);
        $result = $alipay->pcpay($body, $subject,$out_trade_no,$total_fee);
        return $result;
    }
    
    public static function Wxpay($total_fee,$out_trade_no,$body,$notify_url){
        if($notify_url) self::$webchat['notify_url'] = $notify_url;
        $wxpay = new WebchatPay(self::$webchat);
        $result = $wxpay ->unifiedorder($total_fee,$out_trade_no,$body);
        return $result ? self::makeCode($result,'wxpay') : null;
    }
    
    public static function makeCode($data,$prefix){
        header("Content-type:image/png");
        require "../extend/Phpqrcode/phpqrcode.php";
        $codePath = $_SERVER['DOCUMENT_ROOT'];
        $qrCode  = new \QRcode();
        if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/static/paycode')) mkdir($_SERVER['DOCUMENT_ROOT'].'/static/paycode',0777);
        $codeName = '/static/paycode/'.$prefix.'_'.time().'.png';
        $res = $qrCode->png($data,$codePath.$codeName,'H',6);
        return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$codeName;
    }

}
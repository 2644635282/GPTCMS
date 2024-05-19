<?php
namespace paySDK\Alipay;
use paySDK\Alipay\Aop\AopClient;
use paySDK\Alipay\Aop\AlipayTradePrecreateRequest;
use paySDK\Alipay\Aop\AlipayTradePagePayRequest;

class AliPay {
    public $gateway_url = "https://openapi.alipay.com/gateway.do";
    public $alipay_public_key;
    public $private_key;
    public $appid;
    public $charset = "UTF-8";
    public $format = "json";
    public $signtype = "RSA2";
    
    function __construct($alipay_config){
        $this->appid = $alipay_config['app_id'];
        $this->private_key = $alipay_config['merchant_private_key'];
        $this->alipay_public_key = $alipay_config['alipay_public_key'];
        $this->notify_url = $alipay_config['notify_url'];
    }
    
    public function pay($body, $subject,$out_trade_no,$total_amount){
        $aopObj = new AopClient ();
        $aopObj->gatewayUrl = $this->gateway_url;
        $aopObj->appId      = $this->appid;
        $aopObj->rsaPrivateKey = $this->private_key;
        $aopObj->alipayrsaPublicKey = $this->alipay_public_key;
        $aopObj->apiVersion = '1.0';
        $aopObj->postCharset = $this->charset;
        $aopObj->format = 'json';
        $aopObj->signType = $this->signtype;
        $request = new AlipayTradePrecreateRequest();
        $timeout_express = '5m';
        $bizContentarr = array(
            'body'            => $body ? $body : '', //商品描述,可以为空
            'subject'         => $subject,
            'out_trade_no'    => $out_trade_no,
            'total_amount'    => $total_amount/100,
            'timeout_express' => $timeout_express,  //过期时间
        );
        $bizContent = json_encode($bizContentarr,JSON_UNESCAPED_UNICODE);
        $request->setBizContent($bizContent);
        $request->setNotifyUrl($this->notify_url);
        $result = $aopObj->execute($request)->alipay_trade_precreate_response;
        return $result->code == 10000 ? $result->qr_code : false;
    } 

    public function pcpay($body, $subject,$out_trade_no,$total_amount){
        $aop = new AopClient ();
        $aop->gatewayUrl = $this->gateway_url;
        $aop->appId = $this->appid;
        $aop->rsaPrivateKey = $this->private_key;
        $aop->alipayrsaPublicKey=$this->alipay_public_key;
        $aop->apiVersion = '1.0';
        $aop->signType = $this->signtype;
        $aop->postCharset=$this->charset;
        $aop->format='json';
        $timeout_express = '5m';
        $object = new \stdClass();
        $object->out_trade_no = $out_trade_no;
        $object->total_amount = $total_amount/100;
        $object->subject = $subject;
        $object->product_code ='FAST_INSTANT_TRADE_PAY';
        $object->timeout_express = $timeout_express;
        $bizContent = json_encode($object);
        $request = new AlipayTradePagePayRequest();
        $request->setNotifyUrl($this->notify_url);
        $request->setReturnUrl('');
        $request->setBizContent($bizContent);
        $result = $aop->pageExecute($request,'GET'); 
        return $result;
    }
}
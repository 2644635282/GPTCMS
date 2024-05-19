<?php
namespace app\gptcms\controller\api;
use think\facade\Cache;

class Jssdk
{
    private $appId;
    private $appSecret;

    public function __construct($appId,$appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    /**
     * 获取jssdk签名参数
     * @param string $url
     * @return array
     */
    public function getSignPackage($url='')
    {
        $jsapiTicket = $this->getJsApiTicket();
        if(isset($jsapiTicket['status'])){
            return $jsapiTicket;
        }
        
        if(empty($url)){
            // 注意 URL 一定要动态获取，不能 hardcode.
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $url = "$protocol$_SERVER[HTTP_HOST]/$_SERVER[REQUEST_URI]";
        }else{
            $url = html_entity_decode($url);
        }
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }
    
    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 获取jsapi_ticket
     */
    public function getJsApiTicket()
    {
        $ticket_key = "gptcms_jsapi_ticket_".$this->appId;
        $jsapi_ticket = Cache::get('abcdef');
        if (!$jsapi_ticket){
            $accessToken = $this->getAccessToken();
            if(isset($accessToken['status'])){
                return $accessToken;
            }

            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode(curlGet($url));
            $jsapi_ticket = $res->ticket??'';
            if ($jsapi_ticket) {
                Cache::set($ticket_key,$jsapi_ticket,time() + 7000);
            }else{
                return ['status'=>'error','msg'=>$res->errmsg];
            }
        }
        return $jsapi_ticket;
    }

    /**
     * 获取access_token
     */
    public function getAccessToken()
    {
        $token_key = "gptcms_access_token_".$this->appId;
        if(!Cache::get($token_key)){
            // $url = "https://mp.yunzd.cn/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            // $res = json_decode(curlGet($url));
            // $access_token = $res->access_token??'';
            // if($access_token){
            //     Cache::set($token_key,$access_token,time() + 7000);
            // }else{
            //     return ['status'=>'error','msg'=>$res->errmsg];
            // }

            $url = "https://api.weixin.qq.com/cgi-bin/stable_token";
            $data = [
                "grant_type" => "client_credential",
                "appid" => $this->appId,
                "secret" => $this->appSecret
            ];
            $result = json_decode(curlPost($url,json_encode($data)),1);
            if(!$result['access_token']){
                return ['status'=>'error','msg'=>$result["errmsg"]];
            }
            Cache::set($token_key,$result['access_token'],$result["expires_in"]);
        }
        return Cache::get($token_key);
    }
}
 
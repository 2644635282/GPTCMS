<?php
namespace Ktadmin\Tyqw;

use think\facade\Cache;
use Ktadmin\Tyqw\Jwt;

/**
* 同义千问
*/
class Ktadmin
{
    public  $api_key = ''; //接口密钥

    /**
     * Tyqw. 同义千问
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }


  /**
     * 聊天
     */
    public function chat()
    {
        return new \Ktadmin\Tyqw\Src\Chat($this);  
    }


	 /**
     * 统一请求 GEt请求 
     * @param String $url 接口地址
     */
    public function curlRequest($url, $method = 'GET',$data=null,$header=array(),$call_back=null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($header){
        	 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if($method = 'POST'){
        	if($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if($call_back){
            curl_setopt($ch, CURLOPT_WRITEFUNCTION, $call_back);
        }
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return [
                'status' => 'error',
                'message' => 'curl 错误信息: ' . curl_error($ch)
            ];
        }
        curl_close($ch);
        return json_decode($result, true);
    }
}
<?php
namespace Ktadmin\Txhy;

use think\facade\Cache;
use Ktadmin\Txhy\Jwt;

/**
* 同义千问
*/
class Ktadmin
{
    public  $secret_id = ''; //接口密钥
    public  $secret_key = ''; //接口密钥
    public  $app_id = ''; //接口密钥

    /**
     * Txhy. 同义千问
     */
    public function __construct($app_id,$secret_id,$secret_key)
    {
        $this->secret_id = $secret_id;
        $this->secret_key = $secret_key;
        $this->app_id = $app_id;
    }


  /**
     * 聊天
     */
    public function chat()
    {
        return new \Ktadmin\Txhy\Src\Chat($this);  
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
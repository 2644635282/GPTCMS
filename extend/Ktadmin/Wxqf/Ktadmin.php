<?php
namespace Ktadmin\Wxqf;

use think\facade\Cache;

/**
* 文心千帆
*/
class Ktadmin
{
	private $channel = 3; //渠道
    private $api_key = ''; //接口密钥
    private $secret_key = ''; //接口密钥
    public $access_token = ''; //access_token
    private $get_access_token_url = 'https://aip.baidubce.com/oauth/2.0/token'; //获取access_token地址

    /**
     * Chatgpt constructor.
     */
    public function __construct($api_key,$secret_key)
    {
        $this->api_key = $api_key;
        $this->secret_key = $secret_key;
        $this->access_token = $this->getToken();
        
    }


    /**
     * 聊天
     */
    public function chat()
    {
        return new \Ktadmin\Wxqf\Src\Chat($this);
    }
    /**
	 *  获取 Access Token
	 */
	private function getToken()
	{
		if(!$this->api_key || !$this->secret_key) return '';
		$token = Cache::get('wxqf_'.$this->api_key);
		if(!$token){
			$header = ["Content-Type:application/x-www-form-urlencoded"];
			$data = [
				'grant_type' => 'client_credentials',
				'client_id' => $this->api_key,
				'client_secret'  => $this->secret_key
			];
			$url = $this->get_access_token_url.'?'.http_build_query($data);
			$res = $this->curlRequest($url,'POST',[],$header);
			if($res && isset($res['access_token'])){
				$token = $res['access_token'];
				Cache::set('wxqf_'.$this->api_key,$token,2592000);
			} 

		}
        return $token;
	}
	 /**
     * 统一请求 GEt请求 
     * @param String $url 接口地址
     */
    public function curlRequest($url, $method = 'GET',$data=null,$header=array())
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

    /**
     * 聊天请求
     */
    public function curlPostChat($apiUrl, $postData, $callback)
    {       
        $headers  = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, $callback);
        curl_exec($ch);
    }
}
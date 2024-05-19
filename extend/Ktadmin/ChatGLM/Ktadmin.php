<?php
namespace Ktadmin\ChatGLM;

use think\facade\Cache;
use Ktadmin\ChatGLM\Jwt;

/**
* ChtaGLM
*/
class Ktadmin
{
	  private $channel = 6; //渠道
    public  $api_key = ''; //接口密钥
    public  $key_id = ''; //
    public  $key_secret = ''; //
    private $public_key = ''; //接口密钥
    public  $access_token = ''; //access_token
    private $get_access_token_url = 'https://maas.aminer.cn/api/paas/passApiToken/createApiToken'; //获取access_token地址


    /**
     * ChatGLM.
     */
    public function __construct($config=array())
    {
        if($config){
            if(isset($config['api_key']) && $config['api_key']){
                $this->api_key = $config['api_key'];
                $apikeyArr = explode('.', $config['api_key']);
                $key_id = $apikeyArr[0] ?? '';
                $key_secret = $apikeyArr[1] ?? '';
            }
            $millisecond = $this->getMillisecond();
            $this->access_token = Jwt::getToken([
              "api_key"=>$key_id,
              "exp"=>$millisecond + 100000,
              "timestamp"=>$millisecond,
            ],$key_secret);
        }
    }


    /**
	 *  获取 Access Token
	 */
	private function getToken()
	{
		if(!$this->key || !$this->secret) return '';
		$token = Cache::get('chatglm_'.$this->key);
		if(!$token){
			$header = ["Content-Type:application/json"];
			$data = [
				'apiKey' => $this->api_key,
				'encrypted'  => $this->getCrypted(),
			];
			$url = $this->get_access_token_url;
			$res = $this->curlRequest($url,'POST',json_encode($data),$header);
			if($res && $res['code'] == 200){
				$token = $res['data'];
				Cache::set('chatglm_'.$this->api_key,$token,28800);
			} 

		}
        return $token;
	}

  /**
     * 聊天
     */
    public function chat()
    {
        return new \Ktadmin\ChatGLM\Src\Chat($this);  
    }
    /**
     * 获取 ras公钥加密字符串
     * @param string 
     */
    private function getCrypted() {
      $rs = "-----BEGIN PUBLIC KEY-----\n".
            wordwrap($this->public_key,64,"\n",true).
            // $public_key.
            "\n-----END PUBLIC KEY-----";
      openssl_public_encrypt($this->getMillisecond().'', $crypted, $rs);
      $eb64_cry = base64_encode($crypted);
      return $eb64_cry;
    }
    /**
     * 获取毫秒时间戳 
     * @param int 
     */
    private function getMillisecond() {
      list($s1, $s2) = explode(' ', microtime());
      return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
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
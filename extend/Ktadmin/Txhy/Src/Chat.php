<?php
namespace Ktadmin\Txhy\Src;

use Ktadmin\Txhy\Ktadmin;

/**
 * 聊天
 */
class Chat
{
	private $ktadmin;
    // private $top_p = 0.5; 
	private $temperature = 1.0; 
    private $stream = 1;
	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;

    }
    /**
     * 初始化配置
     */
    public function initConfig($config)
    {
        
        if(isset($config['temperature']) && $config['temperature']){
            $this->temperature = (float) $config['temperature'];
        }
        if(isset($config['stream'])){
            $this->stream = $config['stream'];
        } 


    }
     /**
	 * 执行获取 
	 * @param text string  文本内容
	 * @param history array  会话历史,只支持偶数，Q A Q A 的形式传进去
	 * @return  JSON
	 */
    public function sendText($messages = [],$callback, $config = [])
    {
    	$this->initConfig($config);
    	$param = [
            'app_id'=> intval($this->ktadmin->app_id),
            'expired' => time() + 3600,
            'messages' => $messages,
            'secret_id'=> $this->ktadmin->secret_id,
            'stream' => 1,
            'timestamp' => time(),
            // 'query_id' => "addh_sadajd",
            // 'temperature' => 0,
            // 'top_p'=>0.8
		];
		$url = 'https://hunyuan.cloud.tencent.com/hyllm/v1/chat/completions';
        $token = $this->getToken($param);
		$header = ["Authorization: ".$token,"Content-Type:application/json"];
        // var_dump($param);die;
		return $this->ktadmin->curlRequest($url,'POST',json_encode($param),$header,$callback);
    }
    public function getToken($param)
    {
        ksort($param);
        $str = "hunyuan.cloud.tencent.com/hyllm/v1/chat/completions?";
        $i = 0;
        foreach ($param as $k => $v) {
            if ($i == 0) {
                $str .= "$k" . "=" . "$v";
            } else {
                if(is_array($v)){
                    $v = json_encode($v,320);
                }
                $str .= "&" . "$k" . "=" . "$v";
            }
            $i++;
        }
        return $this->getSignature($str,$this->ktadmin->secret_key);
    }

    function getSignature($str, $key)
    {
       $signature = "";
       if (function_exists('hash_hmac')) {
           $signature = base64_encode(hash_hmac("sha1", $str, $key, true));
       } else {
           $blocksize = 64;
           $hashfunc = 'sha1';
           if (strlen($key) > $blocksize) {
               $key = pack('H*', $hashfunc($key));
           }
           $key = str_pad($key, $blocksize, chr(0x00));
           $ipad = str_repeat(chr(0x36), $blocksize);
           $opad = str_repeat(chr(0x5c), $blocksize);
           $hmac = pack(
               'H*', $hashfunc(
                   ($key ^ $opad) . pack(
                       'H*', $hashfunc(
                           ($key ^ $ipad) . $str
                       )
                   )
               )
           );
           $signature = base64_encode($hmac);
       }
       return $signature;
   }
}
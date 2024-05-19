<?php
namespace Ktadmin\Kw\Src;

use Ktadmin\Kw\Ktadmin;

/**
 * 聊天
 */
class Chat
{
	private $ktadmin;
    private $stream = true;
	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;

    }
     /**
	 * 执行获取 
	 * @param text string  文本内容
	 * @return  JSON
	 */
    public function sendText($message,$callback, $app_id)
    {
    	$param = [
            "message" => $message,
			"app_id" => $app_id,
		];
		$url = $this->ktadmin->host.'/kw/user/app/send';
		$header = ["UserToken: ".$this->ktadmin->token,"Content-Type:application/json"];
		return $this->ktadmin->curlRequest($url,'POST',json_encode($param),$header,$callback);
    }
}
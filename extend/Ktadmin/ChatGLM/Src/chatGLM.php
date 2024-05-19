<?php
namespace Ktadmin\Wxyy\Src;

use Ktadmin\Chatgpt\Ktadmin;

/**
* chatGLM
*/
class chatGLM
{
	private $ktadmin;

	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

    /**
	 * 执行获取 
	 * @param text string  文本内容
	 * @param history array  会话历史,只支持偶数，Q A Q A 的形式传进去
	 * @return  JSON
	 */
    public function run($text,$history=[])
    {
    	$param = [
			'prompt' => $text,
			'temperature' => $this->ktadmin->temperature,
			'top' => $this->ktadmin->top,
			'history' => $history,
		];
		$url = 'https://maas.aminer.cn/api/paas/model/v1/open/engines/chatGLM/chatGLM';
		$header = ["Authorization:".$this->ktadmin->access_token,"Content-Type:application/json"];
		return $this->ktadmin->curlRequest($url,'POST',json_encode($param),$header);
    }
    /**
	 * sse 执行获取 
	 * @param text string  文本内容
	 * @param history array  会话历史,只支持偶数，Q A Q A 的形式传进去
	 * @param call_back function  回调函数 示例 function($ch, $data){ob_flush();flush();return strlen($data);}
	 * @return  JSON
	 */
    public function sseRun($text,$history=[],$call_back)
    {
    	$param = [
			'prompt' => $text,
			'temperature' => $this->ktadmin->temperature,
			'top' => $this->ktadmin->top,
			'history' => $history,
		];
		$url = 'https://maas.aminer.cn/api/paas/model/v1/open/engines/sse/chatGLM/chatGLM';
		$header = ["Authorization:".$this->ktadmin->access_token,"Content-Type:application/json"];
		return $this->ktadmin->curlRequest($url,'POST',json_encode($param),$header,$call_back);
    }
}
<?php
namespace Ktadmin\Wxyy\Src;

use Ktadmin\Wxyy\Ktadmin;

/**
* 文心一言 ERNIE 3.0系列API
*/
class Ernie3
{
	private $ktadmin;

	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

   	/**
	 * 创建任务 
	 * @param text string  文本内容
	 * @return  JSON
	 */
	public function createTask($text)
	{
		$param = [
			'async' => 1,
			'typeId' => 1,
			'text' => $text,
			'seq_len' => $this->ktadmin->seq_len, 
			'topp' => $this->ktadmin->topp, 
			'penalty_score' => $this->ktadmin->penalty_score, 
			'min_dec_len' => $this->ktadmin->min_dec_len, 
			'min_dec_penalty_text' => $this->ktadmin->min_dec_penalty_text, 
			'is_unidirectional' => $this->ktadmin->is_unidirectional, 
			'task_prompt' => $this->ktadmin->task_prompt,
			'mask_type' => $this->ktadmin->mask_type,
		];
		$url = 'https://wenxin.baidu.com/moduleApi/portal/api/rest/1.0/ernie/3.0.'.$this->ktadmin->scene.'/zeus?access_token='.$this->ktadmin->access_token;
		$header = ["Content-Type:application/x-www-form-urlencoded"];
		return $this->ktadmin->curlRequest($url,'POST',urlencode($param),$header);
	}
	/**
	 * 获取任务结果 
	 * @param taskid string  任务id
	 * @return  JSON
	 */
	public function getTaskResult($taskId)
	{
		$param = [
			'taskId' => $taskId
		];
		$header = ["Content-Type:application/x-www-form-urlencoded"];
		$url = 'https://wenxin.baidu.com/moduleApi/portal/api/rest/1.0/ernie/v1/getResult?access_token='.$this->ktadmin->access_token;
		return $this->ktadmin->curlRequest($url,'POST',urlencode($param),$header);
	}
}
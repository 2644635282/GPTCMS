<?php
namespace Ktadmin\Chatgpt\Src;

use Ktadmin\Chatgpt\Ktadmin;

/**
* gpt 微调
*/
class Finetunes
{
	private $ktadmin;
	
	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }
	/**
	 * 创建微调
	 * @param param array 创建微调参数,详情参考文档
	 * @return  JSON
	 */
	public function create($param=array())
	{
		$url = "/v1/fine-tunes";
    	return $this->ktadmin->curlRequest($url,$param);
	}
	/**
	 * 列表微调
	 * @return  JSON
	 */
	public function list()
	{
		$url = "/v1/fine-tunes";
    	return $this->ktadmin->curlGetRequest($url);
	}
	/**
	 * 检索微调
	 * @param fine_tune_id 要为其获取事件的微调作业的 ID。
	 * @return  JSON
	 */
	public function detail($fine_tune_id)
	{
		$url = "/v1/fine-tunes/{fine_tune_id}";
    	return $this->ktadmin->curlGetRequest($url);
	}
	/**
	 * 取消微调
	 * @param fine_tune_id 要为其获取事件的微调作业的 ID。
	 * @return  JSON
	 */
	public function cancel($fine_tune_id)
	{
		$url = "/v1/fine-tunes/{fine_tune_id}/cancel";
    	return $this->ktadmin->curlRequest($url);
	}
	/**
	 * 列出微调事件
	 * @param fine_tune_id 要为其获取事件的微调作业的 ID。
	 * @return  JSON
	 */
	public function events($fine_tune_id)
	{
		$url = "/v1/fine-tunes/{fine_tune_id}/events";
    	return $this->ktadmin->curlGetRequest($url);
	}
	/**
	 * 删除模型
	 * @param model 模型
	 * @return  JSON
	 */
	public function delete($model)
	{
		$url = "/v1/models/{model}";
    	return $this->ktadmin->curlGetRequest($url,'DELETE');
	}
}	
<?php
namespace Ktadmin\Chatgpt\Src;

use Ktadmin\Chatgpt\Ktadmin;
/**
* gpt 文件
*/
class Files
{
	private $ktadmin;
	
	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

    /**
	 * 列出文件
	 * @return  JSON
	 */
	public function list()
	{
		$url = "/v1/files";
    	return $this->ktadmin->curlGetRequest($url);
	}
	/**
	 * 上传文件
	 * @param file string 要上传的JSON 行文件的名称, 绝对路径 
	 * @param purpose string  上传文件的预期目的。
	 * @return  JSON
	 */
	public function create($file,$purpose)
	{
		$url = "/v1/files";
		$param = [
			'file' => new \CURLFILE($file),
			'purpose' => 'purpose',
		];
    	return $this->ktadmin->curlRequest($url,$param);
	}
	/**
	 * 删除文件
	 * @param file_id string 用于此请求的文件的 ID。
	 * @return  JSON
	 */
	public function delete($file_id)
	{
		$url = "/v1/files/{file_id}";
    	return $this->ktadmin->curlGetRequest($url,'DELETE');
	}
	/**
	 * 检索文件
	 * @param file_id string 用于此请求的文件的 ID。
	 * @return  JSON
	 */
	public function detail($file_id)
	{
		$url = "/v1/files/{file_id}";
    	return $this->ktadmin->curlGetRequest($url);
	}
	/**
	 * 检索文件内容
	 * @param file_id string 用于此请求的文件的 ID。
	 * @return  JSON
	 */
	public function content($file_id)
	{
		$url = "/v1/files/{file_id}/content";
    	return $this->ktadmin->curlGetRequest($url);
	}
}
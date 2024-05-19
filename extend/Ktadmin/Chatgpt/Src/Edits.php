<?php
namespace Ktadmin\Chatgpt\Src;

use Ktadmin\Chatgpt\Ktadmin;

/**
* gpt 编辑
*/
class Edits
{
	private $ktadmin;
	
	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }
    /**
	 * 创建编辑 给定提示和指令，模型将返回提示的编辑版本
	 * @param model string OpenAI GPT-3 模型的 ID，可在控制台中找到
	 * @param input string  需要进行修改或者补充的文本内容
	 * @param instruction string  编辑指令，例如 "insert" 、"replace" 和 "remove" 等
	 * @return  JSON
	 */
	public function create($model,$input='',$instruction)
	{
		$url = "/v1/edits";
		$param = [
			'model' => $model,
			'input' => $input,
			'instruction' => $instruction,
		];
    	return $this->ktadmin->curlRequest($url,json_encode($param));
	}
}
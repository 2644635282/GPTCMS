<?php
namespace Ktadmin\Chatgpt\Src;

use Ktadmin\Chatgpt\Ktadmin;

/**
* gpt 嵌入
*/
class Embeddings
{
	private $ktadmin;
	
	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

    /**
	 * 创建嵌入 获取给定输入的矢量表示，机器学习模型和算法可以轻松使用该表示。
	 * @param model string OpenAI GPT-3 模型的 ID，可在控制台中找到
	 * @param input string  输入文本以获取嵌入，编码为字符串或标记数组。要在单个请求中获取多个输入的嵌入，请传递一个字符串数组或令牌数组数组。每个输入的长度不得超过 8192 个标记。
	 * @return  JSON
	 */
	public function create($model,$input)
	{
		$url = "/v1/embeddings";
		$param = [
			'model' => $model,
			'input' => $input,
		];
    	return $this->ktadmin->curlRequest($url,json_encode($param));
	}
}
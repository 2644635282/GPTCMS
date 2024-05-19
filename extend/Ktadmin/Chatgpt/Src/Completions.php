<?php
namespace Ktadmin\Chatgpt\Src;

use Ktadmin\Chatgpt\Ktadmin;

/**
 * 自动补全
 */
class Completions
{
	private $ktadmin;

    public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

    /**
	 * 创建完成  给定一个提示，该模型将返回一个或多个预测的完成，并且还可以返回每个位置的替代标记的概率。
	 * @param model strin 要使用的模型的 ID。您可以使用List models API 来查看所有可用模型，或查看我们的模型概述以了解它们的描述。
	 * @param prompt strin 生成完成的提示，编码为字符串、字符串数组、标记数组或标记数组数组。 请注意，<|endoftext|> 是模型在训练期间看到的文档分隔符，因此如果未指定提示，模型将生成新文档的开头。
	 * @param max_tokens strin 完成时生成的最大令牌数。 您的提示加上的令牌计数max_tokens不能超过模型的上下文长度。大多数模型的上下文长度为 2048 个标记
	 * @param temperature integer  使用什么采样温度，介于 0 和 2 之间。较高的值（如 0.8）将使输出更加随机，而较低的值（如 0.2）将使输出更加集中和确定。 我们通常建议改变这个或top_p但不是两者。
	 * @param top_p integer 一种替代温度采样的方法，称为核采样，其中模型考虑具有 top_p 概率质量的标记的结果。所以 0.1 意味着只考虑构成前 10% 概率质量的标记
	 * @param n integer  为每个提示生成多少完成。 **注意：**因为这个参数会产生很多完成，它会很快消耗你的令牌配额
	 * @param stream boolean  是否回流部分进度。如果设置，令牌将在可用时作为仅数据服务器发送事件发送
	 * @param logprobs null   包括最有可能标记的对数概率logprobs，以及所选标记。例如，如果logprobs是 5，API 将返回 5 个最有可能的标记的列表。API 将始终返回采样令牌的 ，因此响应中logprob最多可能有元素。logprobs+1 的最大值logprobs为 5
	 * @param stop string   API 将停止生成更多令牌的最多 4 个序列。返回的文本将不包含停止序列。
	 * @return  JSON
	 */
	public function create($model,$prompt='',$max_tokens=7,$temperature=0,$top_p=1,$n=1,$stream=false,$logprobs=null,$stop=null)
	{
		$url = "/v1/completions";
		$param = [
			'model' => $model,
			'prompt' => $prompt,
			'max_tokens' => $max_tokens,
			'temperature' => $temperature,
			'top_p' => $top_p,
			'n' => $n,
			'stream' => $stream,
			'logprobs' => $logprobs,
			'stop' => $stop
		];
    	return $this->ktadmin->curlRequest($url,json_encode($param));
	}

}
<?php
namespace Ktadmin\Chatgpt\Src;

use Ktadmin\Chatgpt\Ktadmin;

/**
* gpt 图像
*/
class Images
{
	private $ktadmin;
	
	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }
	/**
	 * 创建图像 给定提示和/或输入图像，模型将生成新图像。
	 * @param prompt string 所需图像的文本描述。最大长度为 1000 个字符。 模型dall-e-3 支持 4000个字符
	 * @param n int  要生成的图像数。必须介于 1 和 10 之间。
	 * @param size string 生成图像的大小。必须是256x256、512x512或 之一1024x1024 , 模型dall-e-3 支持 1024x1024, 1792x1024,  1024x1792
	 * @param style string 风格 vivid (生动)   natural(自然)
	 * @return  JSON
	 */
	public function create($prompt,$n=1,$size='1024x1024',$response_format="url",$model="dall-e-3",$style="vivid",$quality=null,$user=null)
	{
		$url = "/v1/images/generations";
		$param = [
			'prompt' => $prompt,
			'n' => $n,
			'size' => $size,
			'response_format' => $response_format,
			'model' => $model,
			'style' => $style,
			'user' => $user ?: '',
		];
		if($model =="dall-e-3") $param["n"] = 1;
		if($quality) $param["quality"] = $quality; // 仅dall-e-3支持 hd(高清) 默认为标准
    	return $this->ktadmin->curlRequest($url,json_encode($param),["Content-Type: application/json"]);
	}
	/**
	 * 创建图像编辑 在给定原始图像和提示的情况下创建编辑或扩展图像
	 * @param image string 要编辑的图像。必须是有效的 PNG 文件，小于 4MB，并且是方形的。如果未提供遮罩，图像必须具有透明度，将用作遮罩。
	 * @param mask string 所需图像的文本描述。最大长度为 1000 个字符。
	 * @param prompt string 所需图像的文本描述。最大长度为 1000 个字符。
	 * @param n int  要生成的图像数。必须介于 1 和 10 之间。
	 * @param size string 生成图像的大小。必须是256x256、512x512或 之一1024x1024
	 * @param response_format string 生成的图像返回的格式。必须是 或url之一b64_json
	 * @param user string 代表您的最终用户的唯一标识符，可以帮助 OpenAI 监控和检测滥用行为
	 * @return  JSON
	 */
	public function edits($image,$mask=null,$prompt,$n=2,$size='1024x1024',$response_format='url',$model="dall-e-2",$user=null)
	{
		$url = "/v1/images/edits";
		$param = [
			'image' => $image,
			'model'=> $model,
			'mask' => $mask,
			'prompt' => $prompt,
			'n' => $n,
			'size' => $size,
			'response_format' => $response_format,
			'user' => $user,
		];
    	return $this->ktadmin->curlRequest($url,http_build_query($param),["Content-Type: application/json"]);
	}
	/**
	 * 创建图像变化 创建给定图像的变体
 	 * @param image string 要编辑的图像。必须是有效的 PNG 文件，小于 4MB，并且是方形的。如果未提供遮罩，图像必须具有透明度，将用作遮罩。
	 * @param n int  要生成的图像数。必须介于 1 和 10 之间。 
	 * @param size string 生成图像的大小。必须是256x256、512x512或 之一1024x1024
	 * @param response_format string 生成的图像返回的格式。必须是 或url之一b64_json
	 * @param user string 代表您的最终用户的唯一标识符，可以帮助 OpenAI 监控和检测滥用行为
	 * @return  JSON
	 */
	public function variations($image,$n=2,$size='1024x1024',$response_format='url',$model="dall-e-2",$user=null)
	{
		$url = "/v1/images/variations";
		$param = [
			'image' => $image,
			'model'=> $model,
			'mask' => $mask,
			'prompt' => $prompt,
			'n' => $n,
			'size' => $size,
			'response_format' => $response_format,
			'user' => $user,
		];
    	return $this->ktadmin->curlRequest($url,http_build_query($param),["Content-Type: application/json"]);
	}


}
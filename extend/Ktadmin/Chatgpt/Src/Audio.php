<?php
namespace Ktadmin\Chatgpt\Src;

use Ktadmin\Chatgpt\Ktadmin;

/**
* gpt 音频 
*/
class Audio
{
	private $ktadmin;
	
	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

	/**
	 * 创建转录 将音频转录为输入语言。
	 * @param file string 要转录的音频文件，采用以下格式之一：mp3、mp4、mpeg、mpga、m4a、wav 或 webm。
	 * @param model string  要使用的模型的 ID。仅whisper-1当前可用
	 * @param prompt string  可选文本，用于指导模型的风格或继续之前的音频片段。提示应与音频语言相匹配。
	 * @param response_format string  成绩单输出的格式，采用以下选项之一：json、text、srt、verbose_json 或 vtt。
	 * @param temperature number  采样温度，介于 0 和 1 之间。较高的值（如 0.8）将使输出更加随机，而较低的值（如 0.2）将使输出更加集中和确定。如果设置为 0，模型将使用对数概率自动升高温度，直到达到特定阈值。
	 * @param language string  输入音频的语言。以ISO-639-1格式提供输入语言将提高准确性和延迟。
	 * @return  JSON
	 */
	public function transcriptions($file,$model="whisper-1",$prompt='',$response_format='',$temperature=0,$language='')
	{
		$url = "/v1/audio/transcriptions";
		$header = [
			'Content-Type: application/json',
			'Accept: application/json',
		];
		$param = [
			'file' => new \CURLFILE($file),
			'model' => $model,
			'prompt' => $prompt,
			'response_format' => $response_format,
			'temperature' => $temperature,
			'language' => $language,
		];
    	return $this->ktadmin->curlRequest($url,$param,$header);
	}

	/**
	 * 创建翻译 将音频翻译成英文。
	 * @param file string 要转录的音频文件，采用以下格式之一：mp3、mp4、mpeg、mpga、m4a、wav 或 webm。
	 * @param model string  要使用的模型的 ID。仅whisper-1当前可用
	 * @param prompt string  可选文本，用于指导模型的风格或继续之前的音频片段。提示应与音频语言相匹配。
	 * @param response_format string  成绩单输出的格式，采用以下选项之一：json、text、srt、verbose_json 或 vtt。
	 * @param temperature number  采样温度，介于 0 和 1 之间。较高的值（如 0.8）将使输出更加随机，而较低的值（如 0.2）将使输出更加集中和确定。如果设置为 0，模型将使用对数概率自动升高温度，直到达到特定阈值。
	 * @param language string  输入音频的语言。以ISO-639-1格式提供输入语言将提高准确性和延迟。8192 个标记。
	 * @return  JSON
	 */
	public function translations($file,$model="whisper-1",$prompt='',$response_format='',$temperature=0)
	{
		$url = "/v1/audio/translations";
		$header = [
			'Content-Type: application/json',
			'Accept: application/json',
		];
		$param = [
			'file' => new \CURLFILE($file),
			'model' => $model,
			'prompt' => $prompt,
			'response_format' => $response_format,
			'temperature' => $temperature,
		];
    	return $this->ktadmin->curlRequest($url,$param,$header);
	}
}
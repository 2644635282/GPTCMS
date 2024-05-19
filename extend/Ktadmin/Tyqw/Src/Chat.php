<?php
namespace Ktadmin\Tyqw\Src;

use Ktadmin\Tyqw\Ktadmin;

/**
 * 聊天
 */
class Chat
{
	private $ktadmin;
	private $top_p = 0.5; 
    private $model = "qwen-turbo";  //模型名 qwen-turbo    qwen-plus(z增强型qwen-turbo)  模型支持 7.5k tokens上下文，为了保障正常使用，API限定输入为6k Tokens。
    private $result_format = "message";  // "text"表示旧版本的text  "message"表示兼容openai的message
    private $stream = true;
	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;

    }
    /**
     * 初始化配置
     */
    public function initConfig($config)
    {
        
        if(isset($config['top_p']) && $config['top_p']){
            $this->top_p = (float) $config['top_p'];
        }
        if(isset($config['model']) && $config['model']){
            $this->model = $config['model'];
        } 
        if(isset($config['result_format']) && $config['result_format']){
            $this->result_format = $config['result_format'];
        } 
        if(isset($config['stream'])){
            $this->stream = $config['stream'];
        } 


    }
     /**
	 * 执行获取 
	 * @param text string  文本内容
	 * @param history array  会话历史,只支持偶数，Q A Q A 的形式传进去
	 * @return  JSON
	 */
    public function sendText($messages = [],$callback, $config = [])
    {
    	$this->initConfig($config);
        $parameters = [
            "top_p" => $this->top_p,
            "result_format" => $this->result_format,
        ];
    	$param = [
			'model' => $this->model,
			'input' =>[
                "parameters" => $parameters,
                "messages" => $messages
            ]
		];
		$url = 'https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation';
		$header = ["Authorization: Bearer ".$this->ktadmin->api_key,"Content-Type:application/json"];
        if($this->stream) $header[] = "X-DashScope-SSE: enable";
        // var_dump($param);die;
		return $this->ktadmin->curlRequest($url,'POST',json_encode($param),$header,$callback);
    }
}
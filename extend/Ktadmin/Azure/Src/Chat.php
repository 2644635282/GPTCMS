<?php
namespace Ktadmin\Azure\Src;

use Ktadmin\Azure\Ktadmin;

/**
 * 聊天
 */
class Chat
{
    private $ktadmin;
    private $temperature = 0.9; //介于 0 和 2 之间，越大回答问题越灵活，越像真人
    private $max_tokens = 1000; //每次回答的最大字符长度
    private $stream = true; //如果设置true则流式输出,false则一次性返回
    private $deployment;
    private $apiversion = "2023-05-15";

	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

    /**
     * 初始化配置
     */
    public function initConfig($config)
    {
        
        if(isset($config['temperature']) && $config['temperature']){
            $this->temperature = (float) $config['temperature'];
        }
        if(isset($config['max_tokens']) && $config['max_tokens']){
            $this->max_tokens = (int) $config['max_tokens'];
        }
        if(@($config['stream'] === 'false' || $config['stream'] === false)){
            $this->stream = false;
        }   
    }

    /**
     * 发送聊天
     * @param Array $messages 提问内容，格式[['role'=>'user','content'=>'hello!']]
     * @param Closure $callback 匿名函数
     * @param Array $config 配置信息。可选参数有 temperature：介于 0 和 2 之间，越大回答问题越灵活，越像真人；
     * max_tokens：每次回答的最大字符长度；model：要使用的模型的 ID；stream：如果设置true则流式输出,false则一次性返回
     */
    public function sendText($messages = [], $callback, $config = [],$deployment="gpt-35-turbo")
    {
        $this->initConfig($config);
        $postData = [
            'messages' => $messages,
            'temperature' => $this->temperature,
            'max_tokens' => $this->max_tokens,
            'frequency_penalty' => 0,
            'presence_penalty' => 0.6,
            'stream' => $this->stream
        ];
        $url = "/openai/deployments/".$deployment."/chat/completions?api-version=".$this->apiversion;
        $this->ktadmin->curlPostChat($url,$postData, $callback);
        return $this;
    }

    /**
     * 解析回复消息
     */
    public function parseData($data)
    {
        //一次性返回数据
        if(@json_decode($data)->choices[0]->message->content){
            return json_decode($data)->choices[0]->message->content;
        }


        //流式数据
        $data = str_replace('data: {', '{', $data);
        $data = rtrim($data, "\n\n");

        if(strpos($data, "}\n\n{") !== false) {
            $arr = explode("}\n\n{", $data);
            $data = '{' . $arr[1];
        }

        if (strpos($data, 'data: [DONE]') !== false) {
            return 'data: [DONE]';
        } else {
            $data = @json_decode($data, true);
            if (!is_array($data)) {
                return '';
            }
            if ($data['choices']['0']['finish_reason'] == 'stop') {
                return 'data: [DONE]';
            }
            elseif($data['choices']['0']['finish_reason'] == 'length') {
                return 'data: [CONTINUE]';
            }

            return $data['choices']['0']['delta']['content'] ?? '';
        }

    }
}
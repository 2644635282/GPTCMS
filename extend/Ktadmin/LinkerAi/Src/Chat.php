<?php
namespace Ktadmin\LinkerAi\Src;

use Ktadmin\LinkerAi\Ktadmin;

/**
 * 聊天
 */
class Chat
{
    private $ktadmin;
    private $network = false;
    private $temperature = 0.9; //温度采样参数，取值(	0,2]。大于1的值倾向于生成更加多样的回复，小于1倾向于生成更加稳定的回复
    private $max_tokens = 1000; //每次回答的最大字符长度
    private $model = 'gpt-3.5-turbo'; //要使用的模型的 ID
    private $stream = true; //如果设置true则流式输出,false则一次性返回
    private $painting = '';

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
        if(isset($config['model']) && $config['model']){
            $this->model = $config['model'];
        } 
        // if(isset($config['network']) && $config['network']){
        //     $this->network = $config['network'];
        // } 
        if(isset($config['painting']) && $config['painting']){
            $this->painting = $config['painting'];
        } 
    }

    /**
     * 发送聊天
     * @param Array $messages 提问内容，格式[['role'=>'user','content'=>'hello!']]
     * @param Closure $callback 匿名函数
     * @param Array $config 配置信息。可选参数有 temperature：介于 0 和 2 之间，越大回答问题越灵活，越像真人；
     * max_tokens：每次回答的最大字符长度；model：要使用的模型的 ID；stream：如果设置true则流式输出,false则一次性返回
     */
    public function sendText($messages = [], $callback, $config = [])
    {
        $this->initConfig($config);
        // if(count($messages) && $this->network){
        // 	if($messages[count($messages)-1]['role'] == 'user'){
        //         $startStr = "";
        //         if($this->network) $startStr = "联网";
        // 		$messages[count($messages)-1]['content'] =  $startStr.$messages[count($messages)-1]['content'];
        // 	}
        // }
        // array_unshift($messages, [
        //     'role' => 'system',
        //     'content' => 'You are ChatGPT, a large language model trained by OpenAI. Follow the user\'s instructions carefully. Respond using markdown.'
        // ]);

        $postData = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $this->temperature,
            // 'max_tokens' => $this->max_tokens,
            'stream' => $this->stream
        ];

        $this->ktadmin->curlPostChat($postData, $callback);
        return $this;
    }
    /**
     * 发送绘画要求
     * @param Array $messages 提问内容，格式[['role'=>'user','content'=>'hello!']]
     * @param Closure $callback 匿名函数
     * @param Array $config 配置信息。可选参数有 temperature：介于 0 和 2 之间，越大回答问题越灵活，越像真人；
     * max_tokens：每次回答的最大字符长度；model：要使用的模型的 ID；stream：如果设置true则流式输出,false则一次性返回
     */
    public function sendImage($messages = [], $callback, $config = [])
    {
    	$this->initConfig($config);
        if(count($messages)){
        	if($messages[count($messages)-1]['role'] == 'user'){
                $startStr = "";
                if($this->painting == "SD")   $startStr = "绘画";
                if($this->painting == "MJ")   $startStr = "MJ";
        		$messages[count($messages)-1]['content'] = $startStr.$messages[count($messages)-1]['content'];
        	}
        }
        $postData = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $this->temperature,
            'stream' => $this->stream
        ];

        $this->ktadmin->curlPostChat($postData, $callback);
        return $this;
    }
    /**
     * 发送绘画要求 绘画方式一
     * @param string $messages 绘画描述内容
     * @param Closure $callback 匿名函数
     */
    public function sendImageSd($messages)
    {
        $postData = [
            'prompt' => $messages,
        ];
        $url = "http://mj.80w.top:8605/image_aigc";
        return $this->ktadmin->curlRequestPaint($url,"POST",json_encode($postData));
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
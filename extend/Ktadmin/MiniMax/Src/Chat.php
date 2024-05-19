<?php
namespace Ktadmin\MiniMax\Src;

use Ktadmin\MiniMax\Ktadmin;

/**
 * 聊天
 */
class Chat
{
    private $ktadmin;
    private $temperature = 0.9; //介于 0 和 1 之间，越大回答问题越灵活，越像真人
    private $tokens_to_generate = 1024; //最大生成token数
    private $stream = true; //如果设置true则流式输出,false则一次性返回
    private $model = 'abab5.5-chat'; //调用的模型名称
    private $bot_setting = [['bot_name'=>'MM智能助理','content'=>'   ']]; //对每一个机器人的设定 list
    private $reply_constraints = ['sender_type'=>'BOT','sender_name'=>'MM智能助理']; //模型回复要求 dict

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
            $this->tokens_to_generate = (int) $config['max_tokens'];
        }
        if(isset($config['model']) && $config['model']){
            $this->model = $config['model'];
        }
        if(@($config['stream'] === 'false' || $config['stream'] === false)){
            $this->stream = false;
        }
        if(isset($config['bot_setting']) && $config['bot_setting']){
            $this->bot_setting = $config['bot_setting'];
        }
        if(isset($config['reply_constraints']) && $config['reply_constraints']){
            $this->reply_constraints = $config['reply_constraints'];
        }   
    }

    /**
     * 发送聊天
     * @param Array $messages 提问内容
     * @param Closure $callback 匿名函数
     * @param Array $config 配置信息
     */
    public function sendText($messages = [], $callback, $config = [])
    {
        $this->initConfig($config);
        foreach ($messages as $key=>$mes) {
            if(isset($mes['role'])){
                if($mes['role'] == 'system'){
                    $this->bot_setting[0]['content'] = $mes['content'];
                    unset($messages[$key]);
                }elseif($mes['role'] == 'user'){
                    $messages[$key] = ['sender_type'=>'USER','sender_name'=>'用户','text'=>$mes['content']];
                }elseif($mes['role'] == 'assistant'){
                    $messages[$key] = ['sender_type'=>'BOT','sender_name'=>'MM智能助理','text'=>$mes['content']];
                }
            }
        }

        $postData = [
            'bot_setting' => $this->bot_setting,
            'messages' => array_values($messages),
            'reply_constraints' => $this->reply_constraints,
            'temperature' => $this->temperature,
            'tokens_to_generate' => $this->tokens_to_generate,
            'model' => $this->model,
            'stream' => $this->stream
        ];
        $url = "/v1/text/chatcompletion_pro";
        // return $postData;
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
<?php
namespace Ktadmin\ChatGLM\Src;

use Ktadmin\ChatGLM\Ktadmin;

/**
 * 聊天
 */
class Chat
{
    private $ktadmin;
    private $temperature = 0.95; 
    private $top_p = 0.7; 
    private $incremental = true; 
    private $model = "charglm-3";  //v3  charglm-3              v4   glm-4   glm-4v   glm-3-turbo
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
        if(isset($config['top_p']) && $config['top_p']){
            $this->top_p = (float) $config['top_p'];
        }
        if(isset($config['model']) && $config['model']){
            $this->model = $config['model'];
        } 
        if(isset($config['incremental']) && $config['incremental']){
            $this->incremental = $config['incremental'];
        } 


    }
     /**
     * 执行获取 v3 版本  超拟人
     * @param text string  文本内容
     * @return  JSON
     */
    public function sendText($messages = [], $callback, $config = [])
    {
        $this->initConfig($config);
        $param = [
            'prompt' => $messages,
            'temperature' => $this->temperature,
            'top_p' => $this->top_p,
            // 'incremental' => $this->incremental,
        ];
        $url = 'https://open.bigmodel.cn/api/paas/v3/model-api/'.$this->model.'/sse-invoke';
        $header = ["Authorization:".$this->ktadmin->access_token,"Content-Type:application/json"];
        return $this->ktadmin->curlRequest($url,'POST',json_encode($param),$header,$callback);
    }

    /**
     * 执行获取 V4版本
     * @param messages
     * @return  JSON
     */
    public function sendTextV4($messages = [], $callback, $config = [])
    {
        $this->initConfig($config);
        $param = [
            'model' => $this->model,
            'messages' => $messages,
            'temperature' => $this->temperature,
            'top_p' => $this->top_p,
            'stream' => true,
            // 'incremental' => $this->incremental,
        ];
        $url = 'https://open.bigmodel.cn/api/paas/v4/chat/completions';
        $header = ["Authorization:".$this->ktadmin->access_token,"Content-Type:application/json"];
        return $this->ktadmin->curlRequest($url,'POST',json_encode($param),$header,$callback);
    }
}
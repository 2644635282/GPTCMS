<?php
namespace Ktadmin\Wxqf\Src;

use Ktadmin\Wxqf\Ktadmin;

/**
 * 聊天
 */
class Chat
{
	private $ktadmin;
	private $url;
    private $model = 'ErnieBot'; //要使用的模型
    private $stream = true; //如果设置true则流式输出,false则一次性返回
    private $temperature; //较高的数值会使输出更加随机，而较低的数值会使其更加集中和确定。 默认0.95，范围 (0, 1.0]，不能为0。 建议该参数和top_p只设置1个。 建议top_p和temperature不要同时更改。
    private $top_p; //默认0.8，取值范围 [0, 1.0]。 影响输出文本的多样性，取值越大，生成文本的多样性越强
    private $penalty_score; //通过对已生成的token增加惩罚，减少重复生成的现象。说明： 值越大表示惩罚越大。 默认1.0，取值范围：[1.0, 2.0]。

    public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

    /**
     * 初始化配置
     */
    public function initConfig($config)
    {
        
        if(isset($config['model']) && $config['model']){
            $this->model = $config['model'];
        }
        switch ($this->model) {
        	case 'ErnieBot':
        		$this->url = "https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/completions";
        		break;
        	case 'ErnieBot-turbo':
        		$this->url = "https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/eb-instant";
        		break;
            case 'ErnieBot-turbo-8k':
                $this->url = "https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/ernie_bot_8k";
                break;
            case 'ErnieBot-turbo-4.0':
                $this->url = "https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/completions_pro";
                break;
        	default:
        		$this->url = "https://aip.baidubce.com/rpc/2.0/ai_custom/v1/wenxinworkshop/chat/completions";
        		break;
        }
        if(@($config['stream'] === 'false' || $config['stream'] === false)){
            $this->stream = false;
        }
        if(isset($config['temperature']) && $config['temperature']){
            $this->temperature = $config['temperature'];
        }
        if(isset($config['top_p']) && $config['top_p']){
            $this->top_p = $config['top_p'];
        }
        if(isset($config['penalty_score']) && $config['penalty_score']){
            $this->penalty_score = $config['penalty_score'];
        }
    }
    /**
     * 发送聊天
     * @param Array $messages 提问内容，格式[['role'=>'user','content'=>'hello!']]
     * @param Closure $callback 匿名函数
     * @param Array $config 配置信息。 model：要使用的模型的 ID；stream：如果设置true则流式输出,false则一次性返回
     */
    public function sendText($messages = [], $callback, $config = [])
    {
        $this->initConfig($config);
        $postData = [
            'messages' => $messages,
            'stream' => $this->stream
        ];
        if($this->model == "ErnieBot"){
           if($this->temperature) $postData["temperature"] = $this->temperature;
           if($this->top_p) $postData["top_p"] = $this->top_p;
           if($this->penalty_score) $postData["penalty_score"] = $this->penalty_score;
        }
        $this->ktadmin->curlPostChat($this->url.'?access_token='.$this->ktadmin->access_token,$postData, $callback);
        return $this;
    }
     /**
     * 解析回复消息
     */
    public function parseData($data)
    {
        //一次性返回数据
        if(json_decode($data) && @json_decode($data)->result){
            return json_decode($data)->result;
        }


        //流式数据
        $data = str_replace('data: {', '{', $data);
        $data = @json_decode($data, true);
        if (!is_array($data)) {
            return '';
        }
        if ($data['is_end'] == true) {
            return $data['result']."\n".'data: [DONE]';
        }
        return $data['result'] ?? '';


    }


}
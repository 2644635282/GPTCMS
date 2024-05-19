<?php
namespace Ktadmin\Wxyy;

use think\facade\Cache

/**
* 文心一言
*/
class Ktadmin
{
	private $channel = 3; //渠道
    private $api_key = ''; //接口密钥
    private $secret_key = ''; //接口密钥
    private $access_token = ''; //access_token
    private $get_access_token_url = 'https://wenxin.baidu.com/moduleApi/portal/api/oauth/token'; //获取access_token地址

    public $scene = 25; //场景设置 20同义改写  21写作文  22写文案  23写摘要  24对对联  25自由问答 26写小说 27补全文本 28自定义 30问答对抽取

    public $seq_len = 512;  //  输出结果的最大长度，因模型生成END或者遇到用户指定的stop_token，实际返回结果可能会小于这个长度，与min_dec_len结合使用来控制生成文本的长度范围。
    public $topp = 0.5;     // 影响输出文本的多样性，取值越大，生成文本的多样性越强。
    public $penalty_score = 1.2; // 通过对已生成的token增加惩罚，减少重复生成的现象。值越大表示惩罚越大。设置过大会导致长文本生成效果变差。
    public $min_dec_len = 2;   //输出结果的最小长度，避免因模型生成END导致生成长度过短的情况，与seq_len结合使用来设置生成文本的长度范围。
    public $min_dec_penalty_text = "。?：！";  //  与最小生成长度搭配使用，可以在min_dec_len步前不让模型生成该字符串中的tokens。
    public $is_unidirectional = 0; //0表示模型为双向生成，1表示模型为单向生成。建议续写与few-shot等通用场景建议采用单向生成方式，而完型填空等任务相关场景建议采用双向生成方式。
    public $stop_token = '';   // 预测结果解析时使用的结束字符串，碰到对应字符串则直接截断并返回。可以通过设置该值，可以过滤掉few-shot等场景下模型重复的cases。
    public $task_prompt = "qa"; //指定预置的任务模板，效果更好。 PARAGRAPH：引导模型生成一段文章； SENT：引导模型生成一句话； ENTITY：引导模型生成词组； Summarization：摘要； MT：翻译； Text2Annotation：抽取； Correction：纠错； QA_MRC：阅读理解； Dialogue：对话； QA_Closed_book: 闭卷问答； QA_Multi_Choice：多选问答； QuestionGeneration：问题生成； Paraphrasing：复述； NLI：文本蕴含识别； SemanticMatching：匹配； Text2SQL：文本描述转SQL；TextClassification：文本分类； SentimentClassification：情感分析； zuowen：写作文； adtext：写文案； couplet：对对联； novel：写小说； cloze：文本补全； Misc：其它任务。
    public $mask_type = "work"; //设置该值可以控制模型生成粒度。
    public $logits_bias = 1; // 配合penalty_text使用，对给定的penalty_text中的token增加一个logits_bias，可以通过设置该值屏蔽某些token生成的概率。
    public $choice_text = ''; // 模型只能生成该字符串中的token的组合。通过设置该值，可以对某些抽取式任务进行定向调优



    /**
     * Chatgpt constructor.
     */
    public function __construct($config=array())
    {
        if($config){
            if(isset($config['api_key']) && $config['api_key']){
                $this->api_key = $config['api_key'];
            }
            if(isset($config['secret_key']) && $config['secret_key']){
                $this->secret_key = $config['secret_key'];
            }
            if(isset($config['scene']) && $config['scene']){
                $this->scene = $config['scene'];
            }
            if(isset($config['seq_len']) && $config['seq_len']){
                $this->seq_len = $config['seq_len'];
            }
            if(isset($config['penalty_score']) && $config['penalty_score']){
                $this->penalty_score = $config['penalty_score'];
            }
            if(isset($config['min_dec_len']) && $config['min_dec_len']){
                $this->min_dec_len = $config['min_dec_len'];
            }
            if(isset($config['min_dec_penalty_text']) && $config['min_dec_penalty_text']){
                $this->min_dec_penalty_text = $config['min_dec_penalty_text'];
            }
            if(isset($config['is_unidirectional']) && $config['is_unidirectional']){
                $this->is_unidirectional = $config['is_unidirectional'];
            }
            if(isset($config['stop_token']) && $config['stop_token']){
                $this->stop_token = $config['stop_token'];
            }
            if(isset($config['task_prompt']) && $config['task_prompt']){
                $this->task_prompt = $config['task_prompt'];
            }
            if(isset($config['mask_type']) && $config['mask_type']){
                $this->mask_type = $config['mask_type'];
            }
            if(isset($config['logits_bias']) && $config['logits_bias']){
                $this->logits_bias = $config['logits_bias'];
            }
            if(isset($config['choice_text']) && $config['choice_text']){
                $this->choice_text = $config['choice_text'];
            }

            $this->access_token = $this->getToken();
        }
    }



    /**
	 *  获取 Access Token
	 */
	private function getToken()
	{
		if(!$this->api_key || !$this->secret_key) return '';
		$token = Cache::get('wxyy_'.$this->api_key);
		if(!$token){
			$header = ["Content-Type:application/x-www-form-urlencoded"];
			$data = [
				'grant_type' => 'client_credentials',
				'client_id' => $this->api_key,
				'client_secret'  => $this->secret_key
			];
			$url = $this->get_access_token_url.'?'.http_build_query($data);
			$res = $this->curlRequest($url,'POST',[],$header);
			if($res && $res['code'] == 0){
				$token = $res['data'];
				Cache::set('wxyy_'.$this->api_key,$token,86400);
			} 

		}
        return $token;
	}

    /**
     *  设置场景url
     */
    public function setScene($scene=null)
    {
        if(!$scene){
            $scene = $this->scene;
        }else{
            $this->scene = $scene;
        }
        
    }
	 /**
     * 统一请求 GEt请求 
     * @param String $url 接口地址
     */
    public function curlRequest($url, $method = 'GET',$data=null,$header=array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($header){
        	 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        if($method = 'POST'){
        	if($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return [
                'status' => 'error',
                'message' => 'curl 错误信息: ' . curl_error($ch)
            ];
        }
        curl_close($ch);
        return json_decode($result, true);
    }

    /**
     * 聊天请求
     */
    public function curlPostChat($postData, $callback)
    {       
        $apiUrl = $this->diy_host.'/v1/chat/completions';
        $headers  = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, $callback);
        curl_exec($ch);
    }
}
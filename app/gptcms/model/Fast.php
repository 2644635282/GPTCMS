<?php 
namespace app\gptcms\model;
use think\facade\Db;
use think\facade\Session;


class Fast
{
    private $apikey;
    private $appId;
    private $channel = 11;

     /**
     * Ktadmin constructor.
     */
    public function __construct($apikey,$appId)
    {
        $this->channel = 11;
        $this->apikey = $apikey;
        $this->appId = $appId;
    }

	/*
	* V2 应用对话
	*chatId
		为 undefined 时（不传入），不使用 FastGpt 提供的上下文功能，完全通过传入的 messages 构建上下文。 
		为空字符 '' 时，表示新窗口第一次对话。响应值会有一个 newChatId
		为非空字符串时，意味着使用 chatId 进行对话，自动从 FastGpt 数据库取历史记录。
	*Stream 是否为Stream响应
	*messages 为提问或者回答的数据
	*/ 
 	public function completions($messages,$callback,$stream=true,$chatId=null){
 		$data["messages"] = $messages;
 		$data["stream"] = $stream;
 		$data["chatId"] = $chatId;
 		$res = self::curlPostChat($data,$callback);
 		return $res;
	}

    /**
     * 聊天请求
     */
    public function curlPostChat($postData, $callback)
    {       
        // $apiUrl = "https://fastgpt.run/api/openapi/v1/chat/completions"; //外网域名
        $apiUrl = "https://api.fastgpt.in/api/openapi/v1/chat/completions";  //国内域名
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '.$this->apikey.'-'.$this->appId
            // 'Authorization: Bearer fastgpt-g4i2fcgpou81mtij5cappu12-64a688d85be8eb98dca4d0d6'
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, $callback);
        $result = curl_exec($ch);
        return $result;
    }
	/**
     * 知识库请求
     * @param 
     */
	public function knowledgeRequest($data){
        // $url = "https://fastgpt.run/api/openapi/kb/pushData"; //外网域名
        $url = "https://ai.fastgpt.in/api/openapi/kb/pushData";  //国内域名
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($data)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'apikey: '.$this->apikey
        ]);
        $result = curl_exec($curl); // 执行操作

        return json_decode($result, true);
    }
}
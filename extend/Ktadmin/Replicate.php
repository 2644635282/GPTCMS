<?php 
namespace Ktadmin;

class Replicate
{
    private $apiHost = 'https://api.replicate.com';
    private $token = '';
    private $version = '';
    private $pageStartTime = 0;
    public function __construct($token="",$version="")
    {
        $this->token = $token;
        $this->version = $version;
        $this->pageStartTime = microtime(true);
    }

    public function draw($prompt,$webhook){
        $url = $this->apiHost . '/v1/predictions';
        $post = [
            'version' => $this->version,
            'input' => [
                'prompt' => 'mdjrny-v4 style design a ' . $prompt,
                'num_outputs' => 1
            ],
            "webhook"=>$webhook,
            "webhook_events_filter"=>["completed"]
        ];
        $result = $this->httpRequest($url, $post);
        return $result;
    }

    public function queryDrawResult($id){
        $url = $this->apiHost . '/v1/predictions/' . $id;
        $result = $this->httpRequest($url);
        if (!empty($result['detail']))return error("失败".$result['detail']);
        if (isset($result['status']) && $result['status'] == "succeeded")return $result;
        $runtime = $this->getRunTime();
        if ($runtime < 180) {
            usleep(5000000);
            return $this->queryDrawResult($id);
        }
    }

    public function getRunTime(){
        $etime = microtime(true);
        $total = $etime - $this->pageStartTime;
        return round($total, 4);
    }

    public function httpRequest($url,$data=[]){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        if($data){
	        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
	        curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($data)); // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: token ' . $this->token
        ]);
        $result = curl_exec($curl); // 执行操作

        return json_decode($result, true);
    }
}
<?php
namespace Ktadmin\LinkerAi;


/**
* 灵犀星火接口
*/
class Ktadmin
{
    private $channel = 7; //渠道 1.openai 2.api2d 7. 灵犀星火 linkerai
    private $api_key = ''; //接口密钥
    private $diy_host = 'http://chat.80w.top:8010'; //接口地址
    
    /**
     * Ktadmin constructor.
     */
    public function __construct($config=array())
    {
        
        if(isset($config['channel']) && $config['channel']){
            $this->channel = (int)$config['channel'];
        }
        if(isset($config['api_key']) && $config['api_key']){
            $this->api_key = $config['api_key'];
        }
    }

    /**
     * 聊天
     */
    public function chat()
    {
        return new \Ktadmin\LinkerAi\Src\Chat($this);
    }

    /**
     * 绘画
     */
    public function images()
    {
        return new \Ktadmin\LinkerAi\Src\Images($this);
    }

    /**
     * 查询额度
     */
    public function subscription()
    {
        $url = "/v1/dashboard/billing/subscription";
        return $this->curlRequest($url);
    }
    /**
     * 查询余额
     */
    public function used2()
    {
        $url = "/used2";
        return $this->curlRequest($url);
    }
    /**
     * 查询已消费金额
     */
    public function usage($start=null,$end=null)
    {
        $url = "/v1/dashboard/billing/usage";
        $url .= "?start_date=".$start ."&start=".$end;
        return $this->curlRequest($url);
    }

    /**
     * 统一请求
     * @param String $url 接口路径'/'开头
     */
    public function curlRequest($url, $method = 'GET', $data=null)
    {
        $apiUrl = $this->diy_host.$url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_key
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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
     * 绘画
     * @param String $url 接口路径'/'开头
     */
    public function curlRequestPaint($url, $method = 'POST', $data=null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->api_key
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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
            'Authorization: Bearer ' . $this->api_key
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
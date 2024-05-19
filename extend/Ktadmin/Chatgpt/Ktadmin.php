<?php
namespace Ktadmin\Chatgpt;

use Ktadmin\Chatgpt\Src\Chat;
use Ktadmin\Chatgpt\Src\Models;

/**
* gpt接口
*/
class Ktadmin
{
    private $channel = 1; //渠道 1.openai 2.api2d
    private $api_key = ''; //接口密钥
    private $diy_host = 'https://api.openai.com'; //接口地址
    
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
        if(isset($config['diy_host']) && $config['diy_host']){
            $this->diy_host = $config['diy_host'];
        }
        
    }

    /**
     * getChannel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * 聊天
     */
    public function chat()
    {
        return new \Ktadmin\Chatgpt\Src\Chat($this);
    }

    /**
     * 模型
     */
    public function Models()
    {
        return new \Ktadmin\Chatgpt\Src\Models($this);
    }

    /**
     * 审查
     */
    public function Moderations()
    {
        return new \Ktadmin\Chatgpt\Src\Moderations($this);
    }

    /**
     * 微调 
     */
    public function Finetunes()
    {
        return new \Ktadmin\Chatgpt\Src\Finetunes($this);
    }
    /**
     * 图像
     */
    public function Images()
    {
        return new \Ktadmin\Chatgpt\Src\Images($this);
    }
    /**
     * 文件
     */
    public function Files()
    {
        return new \Ktadmin\Chatgpt\Src\Files($this);
    }
    /**
     * 编辑
     */
    public function Edits()
    {
        return new \Ktadmin\Chatgpt\Src\Edits($this);
    }
    /**
     * 音频
     */
    public function Audio()
    {
        return new \Ktadmin\Chatgpt\Src\Audio($this);
    }
    /**
     * 自动补全
     */
    public function Completions()
    {
        return new \Ktadmin\Chatgpt\Src\Completions($this);
    }
    /**
     * 财务
     */
    public function Billing()
    {
        return new \Ktadmin\Chatgpt\Src\Billing($this);
    }

    /**
     * 统一请求 POSt
     * @param String $url 接口路径'/'开头
     */
    public function curlRequest($url, $data = '',$header=array())
    {
        switch ($this->channel) {
            case 1:
                $apiUrl = $this->diy_host . $url;
                break;
            case 2:
                $apiUrl = 'https://openai.api2d.net' . $url;
                break;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($header){
            $header[] = 'Authorization: Bearer ' . $this->api_key;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            
        }else{
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: multipart/form-data',
                'Authorization: Bearer ' . $this->api_key
            ]);
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
       
        curl_setopt($ch, CURLOPT_POST, true);
        if($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return [
                'status' => 'error',
                'message' => 'curl 错误信息: ' . curl_error($ch)
            ];
        }
        curl_close($ch);
        return json_decode($result, 1);
    }
    /**
     * 统一请求 GEt请求 delete
     * @param String $url 接口路径'/'开头
     */
    public function curlGetRequest($url, $method = 'GET')
    {
        switch ($this->channel) {
            case 1:
                $apiUrl = $this->diy_host . $url;
                break;
            case 2:
                $apiUrl = 'https://openai.api2d.net' . $url;
                break;
        }

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
        switch ($this->channel) {
            case 1:
                $apiUrl = $this->diy_host . '/v1/chat/completions';
                break;
            case 2:
                $apiUrl = 'https://openai.api2d.net/v1/chat/completions';
                break;
        }

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
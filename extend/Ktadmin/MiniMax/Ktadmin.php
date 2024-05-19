<?php
namespace Ktadmin\MiniMax;

use Ktadmin\MiniMax\Src\Chat;

/**
* MiniMax
*/
class Ktadmin
{
    private $api_key; //接口密钥
    private $groupid; //用户的groupid
    private $diy_host = 'https://api.minimax.chat'; //接口地址
    
    /**
     * Ktadmin constructor.
     */
    public function __construct($api_key,$groupid)
    {
        $this->api_key = $api_key;
        $this->groupid = $groupid;
    }

    /**
     * 聊天
     */
    public function chat()
    {
        return new \Ktadmin\MiniMax\Src\Chat($this);
    }
    
    /**
     * 统一请求 POSt
     * @param String $url 接口路径'/'开头
     */
    public function curlRequest($url, $data = '',$header=array())
    {
        $apiUrl = $this->diy_host . $url . '?GroupId=' . $this->groupid;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->api_key
        ]);
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
        $apiUrl = $this->diy_host . $url . '?GroupId=' . $this->groupid;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->api_key
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
    public function curlPostChat($url,$postData, $callback)
    {
        $apiUrl = $this->diy_host . $url . '?GroupId=' . $this->groupid;
        $headers  = [
            'Content-Type: application/json',
            'Authorization: Bearer '. $this->api_key
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
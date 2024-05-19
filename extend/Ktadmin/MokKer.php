<?php 
namespace Ktadmin;

/*
* api.mokker.ai 图片合成接口，速度很慢
*/
class MokKer
{
    //$res["images"][0]["image"]["data"] 为图片二进制字符串
    private $apiHost = 'https://api.mokker.ai/v1/';
    private $key = '';
    public function __construct($key="")
    {
        $this->key = $key;
    }

    /**
    * 单次请求-随机背景
    * $file 图片的绝对路径
    * $background_id 选用特定的背景模版
    */
    public function replace($file,$background_id=NULL,$size=0){
        $url = $this->apiHost."replace-background";
        $data["image"] = new \CURLFile($file);
        if($background_id)$data["background_id"] = $background_id;
        if($size > 0)$data["number_of_images"] = $size;
        $res = $this->httpRequest($url,$data);

        return $res;
    }

    /**
    *所有背景模版
    */
    public function backgrounds(){
        $url = $this->apiHost."backgrounds";
        $res = $this->httpRequest($url);

        return $res;
    }

    /**
    * 重复使用输入图像
    * upload_id 首次单次请求返回的upload_id
    */
    public function replaces($upload_id){
        $url = $this->apiHost."replace-background";
        $data["upload_id"] = $upload_id;
        $res = $this->httpRequest($url,$data);

        return $res;
    }

    /**
    * 重复使用输入图像
    * image_id 首次单次请求返回的image_id
    */
    public function upscale($image_id){
        $url = $this->apiHost."upscale";
        $data["image_id"] = $image_id;
        $res = $this->httpRequest($url,$data);

        return $res;
    }

    public function httpRequest($url,$data=[]){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        if($data){
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data); // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$this->key,
            'accept: application/json',
            'Content-Type: multipart/form-data'
        ]);
        $result = curl_exec($curl); // 执行操作

        return json_decode($result, true);
    }
}
<?php 
namespace Ktadmin;

class Painting
{
    // 环境：http://api.yjai.art:8080。
    const API_URL = 'http://api.yjai.art:8080/painting-open-api/site/';
    private $AK;
    private $SK;
    private $timestamp;
    public function __construct($AK,$SK,$timestamp) {
        $this->AK = $AK;
        $this->SK = $SK;
        $this->timestamp = $timestamp;
    }

    // 开始绘画
     public function put_task($prompt,$ratio,$style=NULL,$guidence_scale=NULL,$engine,$callback_url=NULL,$callback_type,$enable_face_enhance=NULL,$is_last_layer_skip=NULL,$init_image=NULL,$init_strength=NULL){
        $url = self::API_URL."put_task";
        $data["apikey"] = $this->AK;
        $data["timestamp"] = $this->timestamp;
        $data["prompt"] = $prompt;
        $data["ratio"] = $ratio;
        $data["engine"] = $engine;
        $data["callback_type"] = $callback_type;
        if($style)$data["style"] = $style;
        if($guidence_scale)$data["guidence_scale"] = $guidence_scale;
        if($callback_url)$data["callback_url"] = $callback_url;
        if($enable_face_enhance)$data["enable_face_enhance"] = $enable_face_enhance;
        if($is_last_layer_skip)$data["is_last_layer_skip"] = $is_last_layer_skip;
        if($init_image)$data["init_image"] = $init_image;
        if($init_strength)$data["init_strength"] = $init_strength;

        $n = $this->http_post($url,$data);
        return $n;
    }
     //画家风格
     public function get_draw_selector(){
        $url = self::API_URL."get_draw_selector";
        $data = array(
            'apikey'=>$this->AK,
            'timestamp'=>$this->timestamp,
        );

        $n = $this->http_post($url,$data);
        return $n;
    }
     // 获取任务详情
     public function show_task($uuid){
        $url = self::API_URL."show_task_detail";
        $data = array(
            'apikey'=>$this->AK,
            'timestamp'=>$this->timestamp,
            'uuid'=>$uuid,
        );

        $n = $this->http_post($url,$data);
        return $n;
    }
     // 获取完成的任务
     public function show_complete_tasks($uuid){
        $url = self::API_URL."show_complete_tasks";
        $data = array(
            'apikey'=>$this->AK,
            'timestamp'=>$this->timestamp
        );
        $n = $this->http_post($url,$data);
        return $n;
    }

    // 获取用户信息
    public function getUserInfo(){
        // return $this->getsign();
        $url = self::API_URL."getUserInfo";
        $data = array(
            'apikey'=>$this->AK,
            'timestamp'=>$this->timestamp,
        );
        $n = $this->http_post($url,$data);
        return $n;
    }
    private function getsign($data=array()) {
        $arr = array_merge(array('apisecret'=>$this->SK),$data);
        ksort($arr);
        foreach ($arr as $key => $val) {
          $param[] = $key . "=" .($val);
        }
        $sparam = join("&", $param);
        // return $sparam;
        $s = md5($sparam);
        return $s;
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function http_post($url, $param, $jsonpost = false, $post_file = false) {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        $httpHeaders = array("Content-Type:application/x-www-form-urlencoded","sign:".$this->getsign($param));
        if ($jsonpost) {
            $str = str_replace('\/', '/', json_encode($param));
        } elseif (is_string($param) || $post_file) {
            $str = $param;
        } else {
            $data = array();
            foreach ($param as $key => $val) {
                $data[] = $key . "=" .($val);
            }
            $str = join("&", $data);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $httpHeaders);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $str);
        $sContent = curl_exec($oCurl);
        $sContent = json_decode($sContent,true);
        $aStatus = curl_getinfo($oCurl);
        curl_close($oCurl);
        if ($sContent["status"] == 0)return $sContent;
        return "";
    }
}
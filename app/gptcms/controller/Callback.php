<?php 
namespace app\gptcms\controller;

use think\facade\Db;
use app\BaseController;
use think\facade\Log;
use app\gptcms\model\Wxopenapi;
include "../extend/Qywxcypto/WXBizMsgCrypt.php";

/**
* 公众号回调 登录操作
**/
class Callback extends BaseController{
    public function index(){
        if(isset($_GET['echostr'])){
            $echostr = $_GET['echostr'];
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];  
            $setting = Db::table("kt_gptcms_wxgzh")->select()->toArray();
            foreach ($setting as $value) {  
                $token = $value["token"];
                $tmpArr = [];
                $tmpArr = array($token, $timestamp, $nonce);
                sort($tmpArr);
                $tmpStr = implode( $tmpArr );
                $tmpStr = sha1( $tmpStr );
                if( $tmpStr == $signature ){
                    echo $echostr;
                    exit();
                }
            }
        }else{
            $xml = preg_replace("/[\n\s]/", '', file_get_contents('php://input'));
            $arr = $this->xml_to_array($xml);
            if(!isset($arr["CreateTime"])){
                $timestamp = $_GET['timestamp'];
                $nonce = $_GET['nonce'];
                $msg_signature = $_GET['msg_signature']; 
                $setting = Db::table("kt_gptcms_wxgzh")->select()->toArray();
                foreach ($setting as $value) {  
                    $wx = new \WXBizMsgCrypt($value["token"], $value["message_key"], $value["appid"]);
                    $msg = ''; 
                    $err_code = $wx->DecryptMsg($msg_signature, $timestamp, $nonce, $xml, $msg);
                    if($err_code == 0){
                        $arr = $this->xml_to_array($msg);
                        break;
                    }
                }
            }
            if($this->repeat_push($arr["CreateTime"])){
                echo "success";
                exit();
            }
            $EventKey = "";
            if(isset($arr["EventKey"])){
                if($arr["Event"] == "SCAN"){
                    $EventKey = $arr["EventKey"];
                }else if($arr["Event"] == "subscribe"){
                    if(!is_array($arr["EventKey"]))$EventKey = substr($arr["EventKey"], 8);
                }
            }
            if($EventKey){
                $random = Db::table("kt_gptcms_random")->where(["random"=>$EventKey])->whereTime("ctime",">=",date("Y-m-d"))->find();
                if($random["openid"]){
                    echo "success";
                    exit();
                }
                $wid = $random["wid"];
                $data["openid"] = $_GET["openid"];
                Db::table("kt_gptcms_random")->where(["id"=>$random["id"]])->save($data);
                $res = Wxopenapi::send($_GET["openid"],$wid,"清新版（chat.yunzd.cn）登录成功！请回到网页继续使用！");
            }else{
                $gzh = Db::table("kt_gptcms_wxgzh")->where(["original_id"=>$arr["ToUserName"]])->find();
                if(!$gzh){
                    echo "success";
                    exit();
                }
                $wid = $gzh["wid"];
                if(isset($arr["Event"])){
                    if($arr["Event"] == "subscribe"){
                         $interest = Db::table("kt_gptcms_gzh_interest")->where(["wid"=>$wid,"type"=>2,"status"=>1])->json(["content"])->find();
                        if(!$interest){
                            echo "success";
                            exit();
                        }
                        $content = $interest["content"];
                        $this->send($arr["FromUserName"],$content,$wid);
                    }else if($arr["Event"] == "CLICK"){
                        $arr["Content"] = $arr["EventKey"];
                        $keyword = Db::table("kt_gptcms_gzh_keyword")->where(["wid"=>$wid,"word"=>$arr["Content"],"type"=>1])->json(["content"])->find();
                        if(!$keyword)$keyword = Db::table("kt_gptcms_gzh_keyword")->where(["wid"=>$wid,"type"=>2])->whereLike("word","%".$arr["Content"]."%")->json(["content"])->find();
                        if($keyword){
                            $content = $keyword["content"];
                            if($keyword["reply_type"] == 1){
                                //随机数 随机发送的数据下标 减去1是为了下标比例对应
                                $rand = rand(1, count($content)) - 1;
                                $send_content[] = $content[$rand];
                                $this->send($arr["FromUserName"],$send_content,$wid);
                            }else{
                                $this->send($arr["FromUserName"],$content,$wid);
                            }
                        }
                    }else{
                        echo "success";
                        exit();
                    }
                }else{
                    if($arr["MsgType"] == "text"){
                        $keyword = Db::table("kt_gptcms_gzh_keyword")->where(["wid"=>$wid,"word"=>$arr["Content"],"type"=>1])->json(["content"])->find();
                        if(!$keyword)$keyword = Db::table("kt_gptcms_gzh_keyword")->where(["wid"=>$wid,"type"=>2])->whereLike("word","%".$arr["Content"]."%")->json(["content"])->find();
                        if($keyword){
                            $content = $keyword["content"];
                            if($keyword["reply_type"] == 1){
                                //随机数 随机发送的数据下标 减去1是为了下标比例对应
                                $rand = rand(1, count($content)) - 1;
                                $send_content[] = $content[$rand];
                                $this->send($arr["FromUserName"],$send_content,$wid);
                            }else{
                                $this->send($arr["FromUserName"],$content,$wid);
                            }
                        }else{
                            $interest = Db::table("kt_gptcms_gzh_interest")->where(["wid"=>$wid,"type"=>1,"status"=>1])->json(["content"])->find();
                            if(!$interest){
                                echo "success";
                                exit();
                            }
                            $content = $interest["content"];
                            $this->send($arr["FromUserName"],$content,$wid);
                        }
                    }else{
                        $interest = Db::table("kt_gptcms_gzh_interest")->where(["wid"=>$wid,"type"=>1,"status"=>1])->json(["content"])->find();
                        if(!$interest){
                            echo "success";
                            exit();
                        }
                        $content = $interest["content"];
                        $this->send($arr["FromUserName"],$content,$wid);
                    }
                }
            }
            
        }
        echo "success";
        die;
    }
    
    public function send($openid,$content,$wid){
        foreach ($content as $value){
            if($value["type"] == 1){
                $res = Wxopenapi::send($openid,$wid,$value["text"]);
            }else if($value["type"] == 2){
                $res = Wxopenapi::sendImage($openid,$wid,$value["image_url"],"image");
            }else if($value["type"] == 3){
                $res = Wxopenapi::sendImage($openid,$wid,$value["voice_url"],"voice");
            }else if($value["type"] == 4){
                $res = Wxopenapi::sendVideo($openid,$wid,$value);
            }
            Log::error("公众号回调日志".$value["type"]);
            Log::error($res);
        }

        return "ok";
    }


    public function xml_to_array($xml){
      $res = simplexml_load_string($xml, 'SimpleXmlElement', LIBXML_NOCDATA);
      return json_decode(json_encode($res), true);
    }
    
    public function repeat_push($msg_signature){
        $createTime = $msg_signature;
        $path = '../public/static/gptcms/callback';
        if(!is_dir($path))mkdir($path,0777,true);
        $filePath = $path.'/Callback.txt';
        $arr = [];
        if(is_file($filePath)){
            if(file_exists($filePath) && filesize($filePath) > 10485760) unlink($filePath);
            $arr =  json_decode(file_get_contents($filePath),1)?json_decode(file_get_contents($filePath),1):[];
            if(in_array($createTime,$arr)){
                return 1;//重复推送
            }else{
                array_push($arr,$createTime);//添加元素
                file_put_contents($filePath, json_encode($arr));
            }
        }else{
            array_push($arr,$createTime);//添加元素
            file_put_contents($filePath, json_encode($arr));
        }
        
        return 0;//未重复
    }
}
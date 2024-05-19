<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\user;
use think\facade\Db;
use app\BaseController;
use think\facade\Log;
use app\base\model\user\Wxopenapi;
include "../extend/Qywxcypto/WXBizMsgCrypt.php";

/**
* 公众号回调 登录操作
**/
class WxCallback extends BaseController
{
    public function index(){
        if(isset($_GET['echostr'])){
            $echostr = $_GET['echostr'];
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];  
            $setting = Db::table("kt_base_wxgzh")->select()->toArray();
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
                $setting = Db::table("kt_base_wxgzh")->select()->toArray();
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
                //重复推送
                echo "success";
                exit();
            }
            //Log::error('推送数据'.json_encode($arr,320));
            $EventKey = "";
            if(isset($arr["EventKey"])){
                if($arr["Event"] == "SCAN"){
                    //用户已关注时的事件推送
                    $EventKey = $arr["EventKey"];
                }else if($arr["Event"] == "subscribe"){
                    //用户未关注时，进行关注后的事件推送
                    if(!is_array($arr["EventKey"]))$EventKey = substr($arr["EventKey"], 8);
                }
            }
            if($EventKey){
                $random = Db::table("kt_base_wxgzh_random")->where(["random"=>$EventKey])->whereTime("ctime",">=",date("Y-m-d"))->find();
                if($random["openid"]){
                    echo "success";
                    exit();
                }
                $uid = $random["uid"];
                $data["openid"] = $_GET["openid"];
                //$data["appid"] = $setting['appid'];
                $data["un"] = 'wx'.date('YmdHis').rand(1,300);
                $pwd = rand(1, 99999999);
                $data["pwd"] = ktEncrypt($pwd);
                Db::table("kt_base_wxgzh_random")->where(["id"=>$random["id"]])->save($data);

                $message = "";
                $base_user = Db::table('kt_base_user')->where(['agid'=>$uid,'wxopenid'=>$data["openid"]])->find();
                if($random['type'] == 'login'){
                    //扫码登录时
                    if($base_user){
                        $message = "恭喜您，登录成功！请回到网页继续使用！";
                    }else{
                        $message = "恭喜您，注册成功！初始账号：{$data["un"]}   密码：{$pwd}

为了您的账户安全，请及时登录后台修改密码！";
                    } 
                }else{
                    //扫码绑定时
                    if($base_user){
                        $message = "该微信号已经绑定其他账号!";
                    }else{
                        $message = "恭喜您，绑定成功！请回到网页继续使用！";
                    }
                }
                $res = Wxopenapi::send($_GET["openid"],$uid,$message);
            }else{
                // $gzh = Db::table("kt_base_wxgzh")->where(["original_id"=>$arr["ToUserName"]])->find();
                // if(!$gzh){
                //     echo "success";
                //     exit();
                // }
                // $wid = $gzh["wid"];
                // if(isset($arr["Event"])){
                //     if($arr["Event"] == "subscribe"){
                //          $interest = Db::table("kt_gptcms_gzh_interest")->where(["wid"=>$wid,"type"=>2,"status"=>1])->json(["content"])->find();
                //         if(!$interest){
                //             echo "success";
                //             exit();
                //         }
                //         $content = $interest["content"];
                //         $this->send($arr["FromUserName"],$content,$wid);
                //     }else if($arr["Event"] == "CLICK"){
                //         $arr["Content"] = $arr["EventKey"];
                //         $keyword = Db::table("kt_gptcms_gzh_keyword")->where(["wid"=>$wid,"word"=>$arr["Content"],"type"=>1])->json(["content"])->find();
                //         if(!$keyword)$keyword = Db::table("kt_gptcms_gzh_keyword")->where(["wid"=>$wid,"type"=>2])->whereLike("word","%".$arr["Content"]."%")->json(["content"])->find();
                //         if($keyword){
                //             $content = $keyword["content"];
                //             if($keyword["reply_type"] == 1){
                //                 //随机数 随机发送的数据下标 减去1是为了下标比例对应
                //                 $rand = rand(1, count($content)) - 1;
                //                 $send_content[] = $content[$rand];
                //                 $this->send($arr["FromUserName"],$send_content,$wid);
                //             }else{
                //                 $this->send($arr["FromUserName"],$content,$wid);
                //             }
                //         }
                //     }else{
                //         echo "success";
                //         exit();
                //     }
                // }else{
                //     if($arr["MsgType"] == "text"){
                //         $keyword = Db::table("kt_gptcms_gzh_keyword")->where(["wid"=>$wid,"word"=>$arr["Content"],"type"=>1])->json(["content"])->find();
                //         if(!$keyword)$keyword = Db::table("kt_gptcms_gzh_keyword")->where(["wid"=>$wid,"type"=>2])->whereLike("word","%".$arr["Content"]."%")->json(["content"])->find();
                //         if($keyword){
                //             $content = $keyword["content"];
                //             if($keyword["reply_type"] == 1){
                //                 //随机数 随机发送的数据下标 减去1是为了下标比例对应
                //                 $rand = rand(1, count($content)) - 1;
                //                 $send_content[] = $content[$rand];
                //                 $this->send($arr["FromUserName"],$send_content,$wid);
                //             }else{
                //                 $this->send($arr["FromUserName"],$content,$wid);
                //             }
                //         }else{
                //             $interest = Db::table("kt_gptcms_gzh_interest")->where(["wid"=>$wid,"type"=>1,"status"=>1])->json(["content"])->find();
                //             if(!$interest){
                //                 echo "success";
                //                 exit();
                //             }
                //             $content = $interest["content"];
                //             $this->send($arr["FromUserName"],$content,$wid);
                //         }
                //     }else{
                //         $interest = Db::table("kt_gptcms_gzh_interest")->where(["wid"=>$wid,"type"=>1,"status"=>1])->json(["content"])->find();
                //         if(!$interest){
                //             echo "success";
                //             exit();
                //         }
                //         $content = $interest["content"];
                //         $this->send($arr["FromUserName"],$content,$wid);
                //     }
                // }
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
        $path = '../public/storage/base/callback';
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
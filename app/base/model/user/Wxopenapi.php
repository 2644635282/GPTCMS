<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\user;
use think\facade\Db;
use think\facade\Session;
use WebSocket\Client;
use think\facade\Cache;

class Wxopenapi
{
	static public function getToken($uid=NULL){
        if(!$uid) return error('缺少必要参数uid');
        $wxgzh = Db::table('kt_base_wxgzh')->where('uid', $uid)->find();
        if(!$wxgzh)return error("请配置公众号");
        $cacheName = "gptcms_access_token_".$wxgzh['appid'];
		if(!Cache::get($cacheName)){
			$url = "https://api.weixin.qq.com/cgi-bin/stable_token";
		    $data = [
    			"grant_type" => "client_credential",
    			"appid" => $wxgzh['appid'],
    			"secret" => $wxgzh['appsecret']
    		];
		    $result = json_decode(curlPost($url,json_encode($data)),1);
			if(!isset($result['access_token'])){
				return $result;
			}
			Cache::set($cacheName,$result['access_token'],$result["expires_in"]);
		}

		return Cache::get($cacheName);
	}

	//公众号发送文字
	static public function send($touser,$uid,$content){
		$token = self::getToken($uid);
		$resp = curlPost("https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" .$token,
	    	json_encode([
	      		"touser"  => $touser,
	      		"msgtype"	  => "text",
	      		"text"=>[
	      			"content"=>$content
	      		]	
	    	],JSON_UNESCAPED_UNICODE)
	  	);	
	  	$arr = json_decode($resp,1);
	  	return $arr;
	}

	//删除菜单
	static public function delete($wid){
		$token = self::getToken($wid);
		$res = curlGet('https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$token);
		$res = json_decode($res,1);

		return $res;
	}

	//创建菜单
	static public function creaet($wid,$menu){
		$token = self::getToken($wid);
		//一级菜单 二级菜单
		$resp = curlPost("https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" .$token,
	    	json_encode([
	    	    "button"=>$menu
	    	],JSON_UNESCAPED_UNICODE)
	  	);	
	  	$arr = json_decode($resp,1);
	  	return $arr;
	}

	//获取media_id 
	static public function upload($token,$image,$type){
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=" .$token.'&type='.$type;	
		if(class_exists('CURLFile')){
		    $data = ['media' => new \CURLFile($image)];
		}else{
		    $data = ['media' => $image];
		}
	  	$res = curlPost($url,$data);
	  	$arr = json_decode($res,1);
	  	return $arr;
	} 

	/*
	* 公众号发送图片
	* type 图片为image 语音为voice 
	**/
	static public function sendImage($touser,$wid,$url,$type="image"){
		$token = self::getToken($wid);
		$media = self::upload($token,$url,$type);
		$resp = curlPost(
	    	"https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" .$token,
	    	json_encode([
	      		"touser"  => $touser,
	      		"msgtype" => $type,
	      		$type=>[
	      			"media_id"=>$media["media_id"]
	      		]
	    	],JSON_UNESCAPED_UNICODE)
	  	);	
	  	$arr = json_decode($resp,1);
	  	return $arr;
	}

	/*
	* 发送视频 视频为 video
	*/
	static public function sendVideo($touser,$wid,$data){
		$token = self::getToken($wid);
		$media = self::upload($token,$data["video_url"],"video");
		$image_media = self::upload($token,$data["video_image_url"],"image");
		$resp = curlPost(
	    	"https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" .$token,
	    	json_encode([
	      		"touser"  => $touser,
	      		"msgtype" => "video",
	      		"video"=>[
	      			"media_id"=>$media["media_id"],
	      			"thumb_media_id"=>$image_media["media_id"],
	      			"title"=>$data["video_title"],
	      			"description"=>$data["video_desc"]
	      		]
	    	],JSON_UNESCAPED_UNICODE)
	  	);	
	  	$arr = json_decode($resp,1);
	  	return $arr;
	}
}
<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\admin\system;
use think\facade\Db;
use think\facade\Session;
use app\base\model\BaseModel;
use think\facade\Env;

/* 
* 基础功能model
*/
class BasicModel extends BaseModel
{
	/*
	* 获取基本配置信息
	* uid 登录时存储的uid 唯一
	*/
	static public function getSite(){
		$uid = Session::get("uid");
		$res = Db::table("kt_base_agent")->where(["id"=>$uid])->find();
		$res['isadmin'] = self::isAdmin($uid) ? true : false;
		$res['debug'] = Env::get('APP_DEBUG') ? 1 : 0;
		return $res;
	}
	/*
	* 修改基本配置信息
	* domain 	 	站点域名
	* webname 		站点名称
	* webtitle 		站点标题
	* copyright 	版权设置
  	* register_check	注册验证码（开启，关闭）
  	* registration_audit	注册审核（开启，关闭）----目前不确定代理商是否可以设置此项
  	* pc_official	PC官网（开启，关闭）
  	* register_setmeal	注册时免费开通套餐
  	* register_setmeal_specsid	注册时免费开通套餐规格id
	*/
	static public function setSite($domain,$webname,$webtitle,$copyright,$register_check,$registration_audit,$pc_official,$debug,$register_setmeal,$register_setmeal_specsid,$is_direct_app,$direct_app){
		$uid = Session::get("uid");
		$data["domain"] = $domain;
		$data["webname"] = $webname;
		$data["webtitle"] = $webtitle;
		$data["copyright"] = $copyright;
		$data["register_check"] = $register_check;
		$data["registration_audit"] = $registration_audit;
		$data["register_setmeal"] = $register_setmeal;
		$data["register_setmeal_specsid"] = $register_setmeal_specsid;
		$data["pc_official"] = (int)$pc_official?:2;
		$data["is_direct_app"] = $is_direct_app;
		$data["direct_app"] = $direct_app;
		$res = Db::table("kt_base_agent")->where(["id"=>$uid])->save($data);
		if(self::isAdmin($uid)){
			$debugt = $debug ? 'true' : 'false';
			self::setDebug($debugt);
		}
		return $res;
	}
	static public function setDebug($debugt){
		$env = file_get_contents(root_path().'.env');
		$envArr = explode("\n", $env);
		foreach ($envArr as &$v) {
			$vArr = explode('=', $v);
			if(count($vArr)>0){
				if(trim($vArr[0]) == "APP_DEBUG"){
					$v = "APP_DEBUG = ".$debugt;
					break;
				}
			}
		}
		$str = implode("\n", $envArr);
		file_put_contents(root_path().'.env',$str);
		return "ok";
	}
	/**
	* logo设置
	* user_logo	用户后台LOGO
  	* login_logo	登录页LOGO
  	* pc_logo	PC官网LOGO
	*/
	static public function setLogo($user_logo=NULL,$login_logo=NULL,$pc_logo=NULL){
		$uid = Session::get("uid");
		$data["user_logo"] = $user_logo;
		$data["login_logo"] = $login_logo;
		$data["pc_logo"] = $pc_logo;
		$res = Db::table("kt_base_agent")->where(["id"=>$uid])->save($data);

		return $res;
	}


	/**
	* login_background	背景图设置
	*/
	static public function setBackground($login_background=NULL,$login_background_status){
		$uid = Session::get("uid");
		$data["login_background_status"] = $login_background_status;
		$data["login_background"] = $login_background;
		$res = Db::table("kt_base_agent")->where(["id"=>$uid])->save($data);

		return $res;
	}

	/**
	* 设置其他设置
	* kf_code	客服二维码（上传图片）
  	* gzh_code	公众号二维码   （上传图片）
  	* company_name	公司名称
  	* company_address	公司地址
  	* telephone	联系电话
  	* qq	联系QQ
  	* record_number	备案号
  	* key_word	seo关键词
  	* describe	seo描述
	*/
	static public function setAdditional($kf_code=NULL,$gzh_code=NULL,$company_name=NULL,$company_address=NULL,$telephone=NULL,$qq=NULL,$record_number=NULL,$key_word=NULL,$describe=NULL){
		$uid = Session::get("uid");
		$data["kf_code"] = $kf_code;
		$data["gzh_code"] = $gzh_code;
		$data["company_name"] = $company_name;
		$data["company_address"] = $company_address;
		$data["telephone"] = $telephone;
		$data["qq"] = $qq;
		$data["record_number"] = $record_number;
		$data["key_word"] = $key_word;
		$data["describe"] = $describe;
		$res = Db::table("kt_base_agent")->where(["id"=>$uid])->save($data);

		return $res;
	}


}
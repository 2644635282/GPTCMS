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

class WxpayConfingModel extends BaseModel{
	static public function info(){
		$uid = Session::get("uid");
		$wxpay = [];
        $config = Db::table('kt_base_pay_config')->where(['uid'=>$uid,'type'=>'wx'])->find();
        if($config) $wxpay  = explode(',',$config['config']);
        return $wxpay;
	}

	static public function upd($data){
		$uid = Session::get("uid");
        $config = Db::table('kt_base_pay_config')->where(['uid'=>$uid,'type'=>'wx'])->find();
        if(!$config){
        	$data["type"] = "wx";
        	$data["uid"] = $uid;
   			$res = Db::table('kt_base_pay_config')->save($data);
        }else{
        	$res = Db::table('kt_base_pay_config')->where(['uid'=>$uid,'type'=>'wx'])->update($data);
        }
        return $res;
	}
}
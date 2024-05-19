<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\admin\user;
use think\facade\Db;
use think\facade\Session;
use app\base\model\BaseModel;

class AppPackageModel extends BaseModel
{
	/**
	 * 获取 套餐列表
	 * @return 
	 */
	public static function packageList()
	{
		$uid = Session::get('uid');
		$res = Db::table("kt_base_app_package")->where(['uid'=>$uid])->json(['specs',"apps"])->select()
					->toArray();
		return $res;			
	}

	/**
     * 套餐详情
     */
    public static function packageInfo($id)
    {
    	$uid = Session::get("uid");
    	$info = Db::table("kt_base_app_package")->where(["id"=>$id,'uid'=>$uid])->json(['specs',"apps"])->find();
    	if(isset($info['specs'])){
    		foreach ($info["specs"] as $key=>$value){
	    		if(is_int($info["specs"][$key]["duration_type"]))$info["specs"][$key]["duration_type"] = (string)$info["specs"][$key]["duration_type"];
	    	}
    	}
    	return $info;
    }

	/**
	* 新增套餐
	*/
	public static function addPackage($args)
	{
		$uid = Session::get("uid");
		$args['uid'] = $uid;
		$args['ctime'] = date('Y-m-d H:i:s');
		$info = Db::table("kt_base_app_package")->where(['name'=>$args['name']])->find();
		if($info) return '套餐名称已存在';
		$res = Db::table("kt_base_app_package")->json(['specs',"apps"])->insert($args);
		return 'ok';
	}

	/**
	* 编辑套餐
	*/
	public static function editPackage($id,$args)
	{
		$uid = Session::get("uid");
		$args['uid'] = $uid;
		$args['utime'] = date('Y-m-d H:i:s');
		$info = Db::table("kt_base_app_package")->find($id);
		if($info['name'] != $args['name']){
			$checkname = Db::table("kt_base_app_package")->where(['name'=>$args['name']])->find();
			if($checkname) return '套餐名称已存在';
		}
		$res = Db::table("kt_base_app_package")->where(['id'=>$id])->json(['specs',"apps"])->update($args);
		return 'ok';
	}


}
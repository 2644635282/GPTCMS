<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\admin\market;
use think\facade\Db;
use think\facade\Session;
use app\base\model\BaseModel;
use think\facade\Cache;

/*
* 应用管理
*/
class AppManageModel extends BaseModel
{
	public static function orderList($req)
	{
		$page = $req->post('page',1);
		$size = $req->post('size',10);
		$bh = $req->post('bh');
		$status = $req->post('status');
		$res = Db::table('kt_base_user_order')
				->alias("o")
				->field("o.*,u.un,a.code,a.name")
				->leftjoin("kt_base_user u","o.wid=u.id")
				->leftjoin("kt_base_market_app a","o.app_id=a.id");
		if($status) $res->where("o.status",$status);
		if($bh) $res->where("o.bh","like","%".$bh."%");
		$data["page"] = $page;
		$data["size"] = $size;
		$data["count"] = $res->count();
		$data["item"] = $res->page($page,$size)->order("create_time","desc")->select();
		return success("订单列表",$data);
	}
	/**
	 * 获取 应用列表
	 * @return 
	 */
	public static function list($req)
	{
		// $page = $req->post('page',1);
		// $size = $req->post('size',10);
		$name = $req->post('name');
		$appType = $req->post('app_type');
		$installType = $req->post('install_type');
		$where = [];
		if($name) $where[] = ['name','like',"%{$name}%"];
		if($appType) $where[] = ['app_type','=',$appType];
		if($installType) $where[] = ['install_type','=',$installType];
		// $data['page'] = $page;
		// $data['size'] = $size;
		$res = Db::table('kt_base_market_app')->field('logo,code,name,app_type,describe,install_type,author,version,c_time')->where('app_type','<',3);
		if($where) $res->where($where);
		// $data['count'] = $res->count();
		// $data['item'] = $res->page($page,$size)->select();
		return $res->select();
	}
	/**
	 * 删除 应用
	 * @return 
	 */
	public static function delete($req)
	{
		$id = $req->post('id');
		$app = Db::table('kt_base_market_app')->find($id);
		if(!$app) return error('应用不存在');
		$res = Db::table('kt_base_market_app')->delete($id);
		if($res) return success('删除成功');
		return error('删除失败');
	}
	
	/**
     * 应用信息
     */
    public static function appInfo($code)
    {
    	$uid = Session::get("uid");
    	$info = Db::table("kt_base_market_app")->where(["code"=>$code,'uid'=>$uid])->json(['specs',"label"])->find();
    	//初始化数据
    	if(!$info){
    		$app = self::getMainfestByName($code);
    		if(!$app) return null;
    		$data = [
    			'uid' => $uid,
    			'code' => $code,
    			'name' => $app['name'],
    			'logo' => $app['logo'],
    			'sort' => 0,
    			'recom' => 0,
    			'try_days' => 7,
    			'scene' => $app['description'] ?? '',
    			'version' => $app['version'] ?? '',
    			'user_link' => $app['userindex'] ?? '',
    			'admin_link' => $app['adminindex'] ?? '',
    			'describe' => $app['description'] ?? '',
    			'c_time' => date('Y-m-d H:i:s'),
    		];
    		$nameArr = explode('_', $code);
    		if($app['type'] == 2 && in_array('plugin', $nameArr)){
    			$key = array_keys($nameArr,'plugin');
    			$panmeArr = array_slice($nameArr, 0,$key);
    			$pname = implode('_', $panmeArr);
    			$data['pid'] = Db::table("kt_base_market_app")->where(["code"=>$pname,'uid'=>$uid])->value('id') ?: 0;
    		}
    		$id = Db::table("kt_base_market_app")->insertGetId($data);
    		$info = Db::table("kt_base_market_app")->find($id);
    	} 
    	if(isset($info['specs'])){
    		foreach ($info["specs"] as $key=>$value){
	    		if(is_int($info["specs"][$key]["duration_type"]))$info["specs"][$key]["duration_type"] = (string)$info["specs"][$key]["duration_type"];
	    	}
    	}

    	return $info;
    }

	/**
	* 设置应用信息
	*/
	public static function setApp($args)
	{
		$uid = Session::get("uid");
		$args['uid'] = $uid;
		$info = Db::table("kt_base_market_app")->where(["code"=>$args['code'],'uid'=>$uid])->find();
		if($info){
			$args['u_time'] = date('Y-m-d H:i:s');
			$res = Db::table("kt_base_market_app")->where(["code"=>$args['code'],'uid'=>$uid])->json(['specs',"label"])->update($args);
		}else{
			$args['c_time'] = date('Y-m-d H:i:s');
			$res = Db::table("kt_base_market_app")->json(['specs',"label"])->insert($args);
		}
		return $res;
	}

	/**
	* 分类列表
	*/
	public static function types($page=1,$size=10)
	{
		$uid = Session::get("uid");
		$res = Db::table('kt_base_market_type')->where(['uid'=>$uid]);
		$data['count'] = $res->count();
		$data['item'] = $res->page($page,$size)->select();
		$data['page'] = $page;
        $data['size'] = $size;
        return $data;
	}

	/**
	* 分类信息
	*/
	public static function typeInfo($id)
	{
		$uid = Session::get("uid");
    	$info = Db::table("kt_base_market_type")->where(['id'=>$id])->find();
    	return $info;
	}

	/**
	* 添加分类
	*/
	public static function addType($args)
	{
		$uid = Session::get("uid");
		$args['uid'] = $uid;
		$args['c_time'] = date('Y-m-d H:i:s');
		$info = Db::table("kt_base_market_type")->where(['name'=>$args['name'],'level'=>1])->find();
		if($info) return '分类名称已存在';
		$res = Db::table("kt_base_market_type")->insert($args);
		return 'ok';
    }

	/**
	* 修改分类
	*/
	public static function editType($id,$args)
	{
		$uid = Session::get("uid");
		$args['uid'] = $uid;
		$args['u_time'] = date('Y-m-d H:i:s');
		$info = Db::table("kt_base_market_type")->find($id);
		if($info['name'] != $args['name']){
			$checkname = Db::table("kt_base_market_type")->where(['name'=>$args['name'],'level'=>1])->find();
			if($checkname) return ['status'=>'error','msg'=>'分类名称已存在'];
		}
		$res = Db::table("kt_base_market_type")->where(['id'=>$id])->update($args);
		return $res;
    }

	/**
	* 删除分类
	*/
	public static function delType($id)
	{
		$app = Db::table("kt_base_market_app")->where(["type"=>$id])->find();
		if($app) return ['status'=>'error','msg'=>'删除失败'];
		$res = Db::table("kt_base_market_type")->where(['id'=>$id])->delete();
		return $res;
    }

}
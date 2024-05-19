<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\admin\app;
use think\facade\Db;
use think\facade\Session;
use app\base\model\BaseModel;
use think\facade\Cache;

/*
* 应用管理
*/
class AppManageModel extends BaseModel
{
	/**
	 * 获取 全部应用列表
	 * @return 
	 */
	public static function allapp($req)
	{
		$data = [];
		$page = $req->post('page',1);
		$size = $req->post('size',10);
		$name = $req->post('name');
		$installType = $req->post('install_type');
		$where = [];
		if($name) $where[] = ['name','like',"%{$name}%"];
		if($installType) $where[] = ['install_type','=',$installType];
		$res = Db::table('kt_base_market_app')->field('logo,code,name,app_type,describe,admin_link,install_type,author,target,version,c_time');
		if($where) $res->where($where);
	
		$data = [];
		$data["page"] = $page;
		$data["size"] = $size;
		$data["count"] = $res->count();
		$res = $res->page($page,$size)->select()->toArray();
		foreach ($res as $key=>$value) {
			$res[$key]['admin_link'] = $req->domain().$value['admin_link'];
		}
		$data["item"] = $res;
		return $data;
	}
	/**
	 * 获取 主应用列表
	 * @return 
	 */
	public static function mainapp($req)
	{
		$data = [];
		// $page = $req->post('page',1);
		// $size = $req->post('size',10);
		$name = $req->post('name');
		$installType = $req->post('install_type');
		$where = [];
		if($name) $where[] = ['name','like',"%{$name}%"];
		if($installType) $where[] = ['install_type','=',$installType];
		// $data['page'] = $page;
		// $data['size'] = $size;
		$res = Db::table('kt_base_market_app')->field('logo,code,name,app_type,describe,admin_link,install_type,author,target,version,c_time')->where('admin_link','<>','')->where('app_type',1);
		if($where) $res->where($where);
		// $data['count'] = $res->count();
		// $data['item'] = $res->select();
		$res = $res->select()->toArray();
		foreach ($res as $key=>$value) {
			$res[$key]['admin_link'] = $req->domain().$value['admin_link'];
		}
		return $res;
	}

	/**
	 * 获取 子应用（主应用插件）
	 * @return 
	 */
	public static function plugin($req)
	{
		$data = [];
		// $page = $req->post('page',1);
		// $size = $req->post('size',10);
		$name = $req->post('name');
		$installType = $req->post('install_type');
		$where = [];
		if($name) $where[] = ['name','like',"%{$name}%"];
		if($installType) $where[] = ['install_type','=',$installType];
		// $data['page'] = $page;
		// $data['size'] = $size;
		$res = Db::table('kt_base_market_app')->field('logo,code,name,app_type,describe,admin_link,install_type,author,target,version,c_time')->where('admin_link','<>','')->where('app_type',2);
		if($where) $res->where($where);
		// $data['count'] = $res->count();
		// $data['item'] = $res->select();
		$res = $res->select()->toArray();
		foreach ($res as $key=>$value) {
			$res[$key]['admin_link'] = $req->domain().$value['admin_link'];
		}
		return $res;
	}

	/**
	 * 获取 工具应用
	 * @return 
	 */
	public static function tools($req)
	{
		$data = [];
		// $page = $req->post('page',1);
		// $size = $req->post('size',10);
		$name = $req->post('name');
		$installType = $req->post('install_type');
		$where = [];
		if($name) $where[] = ['name','like',"%{$name}%"];
		if($installType) $where[] = ['install_type','=',$installType];
		// $data['page'] = $page;
		// $data['size'] = $size;
		$res = Db::table('kt_base_market_app')->field('logo,code,name,app_type,describe,admin_link,install_type,author,target,version,c_time')->where('admin_link','<>','')->where('app_type',3);
		if($where) $res->where($where);
		// $data['count'] = $res->count();
		// $data['item'] = $res->select();
		$res = $res->select()->toArray();
		foreach ($res as $key=>$value) {
			$res[$key]['admin_link'] = $req->domain().$value['admin_link'];
		}
		return $res;
	}

	/**
	 * 获取 模板应用
	 * @return 
	 */
	public static function template($req)
	{
		$uid = Session::get('uid');
		$data = [];
		// $page = $req->post('page',1);
		// $size = $req->post('size',10);
		$name = $req->post('name');
		$installType = $req->post('install_type');
		$where = [];
		if($name) $where[] = ['name','like',"%{$name}%"];
		if($installType) $where[] = ['install_type','=',$installType];
		// $data['page'] = $page;
		// $data['size'] = $size;
		$res = Db::table('kt_base_market_app')->field('logo,code,name,app_type,describe,admin_link,install_type,author,target,version,c_time')->where('admin_link','<>','')->where('app_type',4);
		if($where) $res->where($where);
		// $data['count'] = $res->count();
		// $data['item'] = $res->select();
		$res = $res->select()->toArray();
		foreach ($res as $key=>$value) {
			$res[$key]['admin_link'] = $req->domain().$value['admin_link'];
			$isuse = Db::table('kt_base_user_template')->where(['uid'=>$uid,'code'=>$value['code']])->field('id')->find();
			$res[$key]['isuse'] = $isuse?1:0;
		}
		return $res;
	}

	/**
	 * 使用模板
	 * @return 
	 */
	public static function useTemplate($code)
	{
		$uid = Session::get('uid');
		$template = Db::table('kt_base_user_template')->where(['uid'=>$uid])->find();
		$data = [];
		$data['uid'] = $uid;
		$data['code'] = $code;
		if(isset($template['id'])){
			$data['utime'] = date('Y-m-d H:i:s');
			$res = Db::table('kt_base_user_template')->where(['uid'=>$uid])->update($data);
		}else{
			$data['ctime'] = date('Y-m-d H:i:s');
			$res = Db::table('kt_base_user_template')->insert($data);
		}
		return $res;
	}
}
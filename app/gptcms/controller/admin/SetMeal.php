<?php 
namespace app\gptcms\controller\admin;
use app\gptcms\controller\BaseAdmin;
use app\gptcms\model\admin\SetMealModel;
use app\base\model\admin\user\UserModel;
use think\facade\Session;

class SetMeal extends BaseAdmin
{
	/*
	* 用户套餐列表
	*/
	public function index(){
		$info = SetMealModel::info();
		$agent = UserModel::getAgent();
		$data["isadmin"] = $agent["isadmin"];
		$data["list"] = $info;

		return success("获取成功",$data);
	}

	/*
	* 用户套餐详情
	*/
	public function info(){
		$id = $this->req->param("id");
		if(!$id) return error("缺少参数");
		$info = SetMealModel::infos($id);
		return success("获取成功",$info);
	}

	/*
	 * 用户级别
	 */
	public function getLevel(){
		$getLevel = SetMealModel::getLevel();
		return success("获取成功",$getLevel);
	}

	/*
	* 管理员 新增套餐
	*/
	public function addType(){
		$agent = UserModel::getAgent();
		if($agent["isadmin"] != 1) return error("您不可添加");
		$name = $this->req->param("name");
		$price = $this->req->param("price");
		if(!$name || !$price) return error("缺少参数");
		$is_exist = SetMealModel::isExist($name);
		if($is_exist) return error("套餐名称已存在");
		$data = SetMealModel::addType($name,$price);

		return success("创建成功",$data);
	}

	/*
	* 管理员 编辑套餐
	*/
	public function updType(){
		$name = $this->req->param("name");
		$price = $this->req->param("price");
		$id = $this->req->param("id");
		if(!$name || !$price || !$id) return error("缺少参数");
		$is_exist = SetMealModel::isExist($name,$id);
		if($is_exist) return error("套餐名称已存在");
		$data = SetMealModel::updType($name,$price,$id);

		return success("修改成功",$data);
	}

	/*
	* 删除
	*/
	public function delType(){
		$id = $this->req->param("id");
		$agent = UserModel::getAgent();
		if(!$id) return error("缺少参数");
		if($agent["isadmin"] != 1) return error("您不可删除");
		$is_set_type = SetMealModel::isSetType($id);
		if($is_set_type >= 1) return error("不可删除，有".$is_set_type."个用户正在使用该套餐");
		$res = SetMealModel::delType($id);

		return success("删除成功",$res);
	}

	/**
	* 代理修改 新增 套餐
	*/
	public function setMeal(){
		$data = $this->req->param("data");
		if(!$data) return error("参数缺少");
		$is_set = SetMealModel::isSetMeal();
		if(!$is_set)return error("上级管理员未设置，不可设置");
		$res = SetMealModel::setMeal($data);
		if(!is_int($res)) return error($res);

		return success("修改成功",$res);
	}

	/**
	* 代理权限 新增权限
	*/
	public function auth(){
		$level = $this->req->post('level');
		$auths = $this->req->post('auths');
		if(!$level || !$auths) return error('缺少参数');
		$res = SetMealModel::auth($level,$auths);
		return success('添加成功',$res);
	}

	/**
	* 套餐权限 获取已设置的权限
	*/
	public function getAuth(){
		$level = $this->req->post('level');
		if(!$level) return error('缺少参数');
		$res = SetMealModel::getAuth($level);
		return success('获取成功',$res);
	}


}
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
use think\facade\Cache;
use app\base\controller\BaseUser;
use think\facade\Session;
use app\base\model\BaseModel;
use app\base\model\user\AppModel;


class App extends BaseUser
{
	/**
    *获取 已购买应用
    **/
    public function openAppList()
    {
    	$page = $this->req->get('page',1);
        $size = $this->req->get('size',10);
        $title = $this->req->get('title','');
        $pid = $this->req->get('pid',0);
        $where = [];
    	$where[] = ['pid','=',$pid];
        if($title) $where[] = ['name','like',"%{$title}%"];
        $res = AppModel::openAppList($where,$page,$size);
        return success("已拥有引擎",$res);
    } 
    /**
    *获取 已购买应用
    **/
    public function updateOpenApp()
    {
        $data = [];
        $data['id'] = $this->req->post('id');
        $data['sequence'] = $this->req->post('sequence');
        $data['self_title'] = $this->req->post('self_title');
        if(!$data['id'] || !$data['sequence'] || !$data['self_title']) return error('参数错误');
        $res = AppModel::save($data);
        return success("更新成功");
    } 
    /**
    *获取 全部应用
    **/
    public function appList()
    {
    	$page = $this->req->get('page',1);
        $size = $this->req->get('size',10);
    	$type = $this->req->get('type',0);
        $title = $this->req->get('title','');
        
        $where = [];
        if($type) $where[] = ['type','=',$type];
        if($title) $where[] = ['name','like',"%{$title}%"];
        $res = AppModel::appList($where,$page,$size);
        return success("全部引擎",$res);
    } 
    /**
    *获取详情
    **/ 
    public function appDetail()
    {
        $id = $this->req->get('id');
        $res = AppModel::appDetail($id);
        return success("引擎详情",$res);
    } 
    /**
    *获取 应用分类
    **/
    public function appType()
    {
        $res = AppModel::appType();
        return success("引擎分类",$res);
    } 
    /**
    *试用
    **/
    public function tryout()
    {
    	$id = $this->req->get('id');
    	if(!$id) return error('参数错误');
        return AppModel::tryout($id);
    }
    /**
    *立即使用
    **/
    public function openappUse()
    {
        return AppModel::openappUse($this->req);
    } 
    /**
    *购买
    **/
    public function buy()
    {
    	// [{ "id":1,"duration": 1, "duration_type": 1, "old_price": 0.02, "price": 0.01 }]
    	$id = $this->req->post('id');
    	if(!$id) return error('请选择引擎');
    	$specsid = $this->req->post('specsid');
    	if(!$specsid) return error('请选择规格');
        return AppModel::buy($id,$specsid);
    }
    /**
    *购买结果
    **/
    public function getPayResult()
    {
        return AppModel::getPayResult($this->req);
    } 
    
}
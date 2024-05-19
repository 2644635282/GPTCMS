<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin\market;

use think\facade\Db;
use think\facade\Session;
use app\base\controller\BaseAdmin;
use app\base\model\admin\market\AppManageModel;

class AppManage extends BaseAdmin
{
    public function order()
    {
        return AppManageModel::orderList($this->req);
    }
    public function index()
    {
    	$uid = Session::get('uid');
    	// return error(base_path());
    	$res = AppManageModel::list($this->req);
        return success('应用列表',$res);
    }
    /**
     * 删除
     */
    public function delete()
    {
        $uid = Session::get('uid');
        // return error(base_path());
        return AppManageModel::delete($this->req);
    }

    /**
     * 应用信息
     */
    public function appInfo()
    {
        $code = $this->req->param("code");
        if(!$code) return error("应用编码不可为空");
        $info = AppManageModel::appInfo($code);
        return success("操作成功",$info);
    }

    /**
     * 设置应用信息
     */
    public function setApp()
    {
    	$data = [];
    	$data['code'] = $this->req->param("code");
    	$data['name'] = $this->req->param("name");
    	$data['logo'] = $this->req->param("logo");
    	$data['sort'] = $this->req->param("sort",0);
    	$data['try_days'] = $this->req->param("try_days",7);
    	$data['scene'] = $this->req->param("scene");
    	$data['type'] = $this->req->param("type");
        $data['describe'] = $this->req->param("describe");
    	$data['shelves'] = $this->req->param("shelves",0);
    	$data['label'] = $this->req->param("label",[]);
        $data['specs'] = $this->req->param("specs",[]);
    	if(!$data['code']) return error("应用编码不可为空");
		if(!$data['name']) return error("应用名称不可为空");
		if(!$data['type']) return error("类别不可为空");
        if(!$data['specs']) return error("规格不可为空");
        $label = $data['label'];
        if(count($label)>3) return error('最多三个标签');
        foreach ($label as $l) {
            if(mb_strlen($l) > 4) return error('标签不合法，最多4个字');
        }
		AppManageModel::setApp($data);
		return success("修改成功");
    }

    /**
     * 分类列表
     */
    public function types()
    {
        $page = $this->req->param('page',1);
        $size = $this->req->param('size',10);
        $types = AppManageModel::types($page,$size);
        return success('操作成功',$types); 
    }

    /**
     * 分类信息
     */
    public function typeInfo(){
        $id = $this->req->param("id");
        if(!$id) return error("分类id不可为空");
        $info = AppManageModel::typeInfo($id);
        return success("操作成功",$info);
    }

    /**
     * 添加分类
     */
    public function addType()
    {
        $data = [];
        $data['name'] = $this->req->param("name");
        $data['sort'] = $this->req->param("sort",0);
        $data['level'] = $this->req->param("level",1);
        $data['pid'] = $this->req->param("pid",0);
        if(!$data['name']) return error("分类名称不可为空");
        $res = AppManageModel::addType($data);
        if($res != 'ok') return error($res);
        return success('操作成功');
    }

    /**
     * 编辑分类
     */
    public function editType()
    {
        $data = [];
        $id = $this->req->param("id");
        $data['name'] = $this->req->param("name");
        $data['sort'] = $this->req->param("sort",0);
        $data['level'] = $this->req->param("level",1);
        $data['pid'] = $this->req->param("pid",0);
        if(!$id) return error("分类id不可为空");
        if(!$data['name']) return error("分类名称不可为空");
        AppManageModel::editType($id,$data);
        return success('操作成功');
    }

    /**
     * 删除分类
     */
    public function delType()
    {
        $id = $this->req->param("id");
        if(!$id) return error("分类id不可为空");
        $res = AppManageModel::delType($id);
        if($res['status'] == 'error') return error($res['msg']);
        return success('操作成功');
    }

}

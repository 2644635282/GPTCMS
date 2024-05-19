<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin\user;
use think\facade\Db;
use app\base\controller\BaseAdmin;
use app\base\model\admin\user\AppPackageModel;
use think\facade\Session;

class AppPackage extends BaseAdmin
{
    /**
     * 套餐列表
     */
    public function packageList()
    {
        $uid = Session::get('uid');
        $res = AppPackageModel::packageList();
        return success('套餐列表',$res);
    }

    /**
     * 套餐详情
     */
    public function packageInfo()
    {
        $id = $this->req->param("id");
        if(!$id) return error("套餐id不可为空");
        $info = AppPackageModel::packageInfo($id);
        return success("操作成功",$info);
    }

    /**
     * 新增套餐
     */
    public function addPackage()
    {
        $data = [];
        $data['name'] = $this->req->param("name");
        $data['specs'] = $this->req->param("specs",[]);
        $data['apps'] = $this->req->param("apps",[]);
        $data['sort'] = $this->req->param("sort",0);
        if(!$data['name']) return error("套餐名称不可为空");
        if(!$data['specs']) return error("套餐规格不可为空");
        if(!$data['apps']) return error("包含应用不可为空");
        $res = AppPackageModel::addPackage($data);
        if($res != 'ok') return error($res);
        return success("添加成功");
    }

    /**
     * 编辑套餐
     */
    public function editPackage()
    {
        $data = [];
        $id = $this->req->param("id");
        $data['name'] = $this->req->param("name");
        $data['specs'] = $this->req->param("specs",[]);
        $data['apps'] = $this->req->param("apps",[]);
        $data['sort'] = $this->req->param("sort",0);
        if(!$id) return error("套餐id不可为空");
        if(!$data['name']) return error("套餐名称不可为空");
        if(!$data['specs']) return error("套餐规格不可为空");
        if(!$data['apps']) return error("包含应用不可为空");
        $res = AppPackageModel::editPackage($id,$data);
        if($res != 'ok') return error($res);
        return success("修改成功");
    }
    
}
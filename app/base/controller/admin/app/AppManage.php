<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin\app;

use think\facade\Db;
use think\facade\Session;
use app\base\controller\BaseAdmin;
use app\base\model\admin\app\AppManageModel;

class AppManage extends BaseAdmin
{
    /**
     * 全部应用
     */
    public function allapp()
    {
        $uid = Session::get('uid');
        // return error(base_path());
        $res = AppManageModel::allapp($this->req);
        return success('应用列表',$res);
    }
	/**
     * 主应用
	 */
    public function mainapp()
    {
    	$uid = Session::get('uid');
    	// return error(base_path());
    	$res = AppManageModel::mainapp($this->req);
        return success('应用列表',$res);
    }

    /**
     * 子应用（主应用插件）
	 */
    public function plugin()
    {
    	$uid = Session::get('uid');
    	// return error(base_path());
    	$res = AppManageModel::plugin($this->req);
        return success('应用列表',$res);
    }

    /**
     * 工具应用
	 */
    public function tools()
    {
    	$uid = Session::get('uid');
    	// return error(base_path());
    	$res = AppManageModel::tools($this->req);
        return success('应用列表',$res);
    }

    /**
     * 模板应用
     */
    public function template()
    {
        $uid = Session::get('uid');
        // return error(base_path());
        $res = AppManageModel::template($this->req);
        return success('模板应用',$res);
    }

    /**
     * 使用模板
     */
    public function useTemplate()
    {
        $code = $this->req->param("code");
        if(!$code) return error("应用编码不可为空");
        $res = AppManageModel::useTemplate($code);
        return success('设置成功',$res);
    }
}

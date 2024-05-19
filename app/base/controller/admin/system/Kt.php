<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin\system;

use think\facade\Db;
use think\facade\Session;
use app\base\controller\BaseAdmin;
use app\base\model\admin\system\KtModel;

class Kt extends BaseAdmin
{
    /**
    * 狂团配置
    */
    public function ktconfig()
    {
        return KtModel::getKtconfig($this->req);
    }
    /**
    * 框架狂团绑定配置
    */
    public function ktAppConfig()
    {
        return KtModel::ktAppConfig($this->req);
    }

    /**
    * 应用列表
    */
    public function ktapp()
    {
        return KtModel::ktapp($this->req);
    }
    /**
    * 删除应用
    */
    public function delete()
    {
        return KtModel::delete($this->req);
    }
    /**
    * 应用列表
    */
    public function sync()
    {
        return KtModel::sync();
    }

    /**
    * 狂团配置
    */
    public function updateKtconfig()
    {
        return KtModel::updateKtconfig($this->req);
    }
    /**
    * 狂团配置
    */
    public function ktinfo()
    {
        return KtModel::ktinfo();
    }

    /**
     * 应用商店
     */
    public function appStore()
    {
        $page = $this->req->param('page',1);
        $size = $this->req->param('size',10);
        return KtModel::appStore($page,$size);
    }
}
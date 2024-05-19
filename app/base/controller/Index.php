<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

declare (strict_types = 1);

namespace app\base\controller;
use app\BaseController;

class Index extends BaseController
{
    public function index()
    {
    	$domain = request()->domain();
        return redirect($domain.'/app/base');
    }
}

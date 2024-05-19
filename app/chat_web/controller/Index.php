<?php
namespace app\chat_web\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;

class Index extends BaseController
{
    public function index()
    {
        $agent = site_info();
    	View::assign('agent',$agent);
        return View::fetch('chat_web@index/index');
    }
}
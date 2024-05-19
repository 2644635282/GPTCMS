<?php

namespace app\gptcms\controller;

use app\BaseController;
use think\facade\Db;
use think\Request;
use think\facade\Session;

class BaseAdmin extends BaseController
{
    protected $req;
    protected $admin;
    protected $uid;
    protected $token;
    protected $host;

    public function  __construct(Request $request){
        if(Session::has('uid')) Session::delete('uid');
        $this->req = $request;
        $this->host  = $request->host();
        $this->token = $this->req->header('AdminToken');
        // $this->token = Session::get('AdminToken');
        $this->admin = Db::table('kt_base_agent')->where([['agency_token', '=', $this->token], ['expire_time', '>',time()]])->find();
        if($this->admin){
            $this->uid = $this->admin['id'];
            Session::set('uid',$this->admin['id']);
        }
    }

    /**
     * 获取前端路由配置
     */
    public function getAdminRoutes(){
        $adminRoutes = include app_path()."adminroutes.php";
        $show = $this->req->param('show',0);
        $agent = $this->admin;
        return success('OK',$adminRoutes);
    }

    /**
     * 获取用户后台前端路由配置
     */
    public function getUsersRoutes(){
        $userroutes = include app_path()."userroutes.php";
        return success('OK',$userroutes);
    }   
}
<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller;

use app\BaseController;
use think\facade\Db;
use think\Request;
use app\base\model\BaseModel;
use think\facade\Session;

class BaseUser extends BaseController
{
    protected $req;
    protected $user;
    protected $wid;
    protected $host;
    protected $token;


    public function  __construct(Request $request){
        // $url   = strtolower($request->controller().'/'.$request->action());
        $this->req = $request;
        $this->host  = $request->host();
        $url  = $request->url();
        $this->token = $this->req->header('UserToken');
        $this->user = Db::table('kt_base_user')->where([['token', '=', $this->token], ['expire_time', '>',time()]])->find();
        // var_dump($this->user);
        if($this->user){
            $this->wid = $this->user['id'];
            Session::set('wid',$this->user['id']);
            Session::set('uid',$this->user['agid']);
        } 
    }

}
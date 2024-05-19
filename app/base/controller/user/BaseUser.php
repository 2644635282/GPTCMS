<?php
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 [ktadmin.cn] [kt8.cn]
// +----------------------------------------------------------------------
// | KtAdmin is NOT a free software, it under the license terms, visited http://www.kt8.cn/ for more details.
// +----------------------------------------------------------------------

namespace app\base\controller\user;

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
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
use app\base\controller\BaseUser;
use Ramsey\Uuid\Uuid;
use think\facade\Session;
use app\base\model\BaseModel;

class Register extends BaseUser
{
    public function index()
    {
        $username = $this->req->param("username");
        $password = $this->req->param("password");
        $phone = $this->req->param("phone");
        // if(!$username) return error('缺少参数username');
        if(!$password) return error('缺少参数password');
        if(!$phone) return error('缺少参数phone');
        if(!preg_match("/^1[23456789]\d{9}$/", $phone)) return error("手机号格式错误，请重新输入");
        $username = $username?:$phone;
        $where = [
            ['un', '=', $username],
            ['telephone', '=', $phone]
        ];
        $user = Db::table('kt_base_user')->whereOr($where)->find();
        if($user) return error("账户已存在，请重新注册");
        $res = BaseModel::getLoginInfo($this->host);
        $id = Db::table('kt_base_user')->insertGetId([
            'un' => $username ,
            'pwd' => ktEncrypt($password),
            'telephone' => $phone,
            'agid' => $res["id"],
            'mendtime' => date('Y-m-d', strtotime('-7 days')),
            'create_time' => date("Y-m-d H:i:s",time())
        ]);
        BaseModel::openRegisterSetmeal($id);
        return success("注册成功");
    }  
}
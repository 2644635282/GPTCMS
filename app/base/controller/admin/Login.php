<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin;
use think\facade\Db;
use app\base\controller\BaseAdmin;
use Ramsey\Uuid\Uuid;
use think\facade\Session;

class Login extends BaseAdmin
{
	/*
	* 登录
	*/
    public function index()
    {
		$username = $this->req->param('username');
		$password = $this->req->param('password');
		if(!$username) return error('缺少参数username');
		if(!$password) return error('缺少参数password');
		$where = [
            ['un', '=', $username],
            ['telephone', '=', $username]
        ];
    	$userAgency = Db::table('kt_base_agent')->whereOr($where)->find();
		if(!$userAgency) return error('账户不存在');
		if($userAgency['pwd'] != md5($password) && $userAgency['pwd'] != ktEncrypt($password)) return error('帐号或密码错误');
		if($userAgency['isstop'] != 1 ) return error('账户已经被停用或作废');
        $token = $userAgency['agency_token'] && $userAgency['expire_time'] > time() ? $userAgency['agency_token'] : Uuid::uuid1();
		Db::table('kt_base_agent')->where('id',$userAgency['id'])->update(['agency_token'=>"{$token}",'expire_time'=> time() + (7*24*3600),'lasttime'=>date("Y-m-d H:i:s") ]);
		Db::table('kt_base_loginlog')->insert([
			'admin' => 1,
			'wid' => $userAgency['id'],
			'uip' => $this->req->ip(),
            'create_time' => date("Y-m-d H:i:s")
		]);
        return success('登录成功',['token'=>$token]);
    }

    /*
	* 修改密码
	*/
    public function forgotPassword()
    {
    	$uid = Session::get('uid');
    	$agent = Db::table('kt_base_agent')->find($uid);
    	$password = $this->req->post('password');
    	if($agent['pwd'] != md5($password) && $agent['pwd'] != ktEncrypt($password)) return error('当前密码错误');
    	$new_password = $this->req->post('new_password');
    	$confirm_password = $this->req->post('confirm_password');
    	if(!$new_password || !$confirm_password) return error('请输入新密码');
    	if($new_password != $confirm_password) return error('两次输入的新密码不一致');
		if($agent['pwd'] == ktEncrypt($new_password)) return error('新旧密码一致');
		$res =  Db::table('kt_base_agent')->where('id',$uid)->update([
			"pwd" => ktEncrypt($new_password),
		]);
		if($res) return success('修改成功');
		return error('修改失败');
    }
}
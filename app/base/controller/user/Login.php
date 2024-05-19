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
use think\facade\Cache;
use Ramsey\Uuid\Uuid;
use think\facade\Session;
use app\base\model\BaseModel;
use app\base\controller\BaseUser;

class Login extends BaseUser
{
    public function index()
    {
        if(!$this->req->isPost()) return error('请使用POST请求');
		$username = $this->req->param('username');
		$password = $this->req->param('password');
		if(!$username) return error('缺少参数username');
		if(!$password) return error('缺少参数password');
		$where = [
            ['un', '=', $username],
            ['telephone', '=', $username],
            ['email','=',$username]
        ];
    	$user = Db::table('kt_base_user')->whereOr($where)->find();
		if(!$user) return error('用户不存在');
		if($user['pwd'] != md5($password) && $user['pwd'] != ktEncrypt($password)) return error('帐号或密码错误');
		if($user['isstop'] != 1 ) return error('账号审核中或已停用');
		// if(strtotime($user['mendtime']) <= time()) return error('账号已到期');
        $token = $user['token'] && $user['expire_time'] > time() ? $user['token'] : Uuid::uuid1();
	    Db::table('kt_base_user')->where('id',$user['id'])->inc('logtimes')->update(['token'=>"{$token}",'expire_time'=> time() + (7*24*3600),'lasttime'=>date("Y-m-d H:i:s")]);
		Db::table('kt_base_loginlog')->insert([
			'admin' => 2 ,
			'wid' => $user['id'],
			'uip' => $this->req->ip(),
            'create_time' => date("Y-m-d H:i:s")
		]);
        return success('登录成功',['token'=>$token]);
    }

    /**
    *获取登录页相关信息
    **/
    public function getLoginInfo(){
        $res = BaseModel::getLoginInfo($this->host);
        return success("登陆前相关信息",$res);
    }   

    /*
    * 修改密码
    */
    public function updatePwd()
    {
        $wid = Session::get('wid');
        $user = Db::table('kt_base_user')->find($wid);
        $password = $this->req->post('password');
        if($user['pwd'] != md5($password) && $user['pwd'] != ktEncrypt($password)) return error('当前密码错误');
        $new_password = $this->req->post('new_password');
        $confirm_password = $this->req->post('confirm_password');
        if(!$new_password || !$confirm_password) return error('请输入新密码');
        if($new_password != $confirm_password) return error('两次输入的新密码不一致');
        if($user['pwd'] == ktEncrypt($new_password)) return error('新旧密码一致');
        $res =  Db::table('kt_base_user')->where('id',$wid)->update([
            "pwd" => ktEncrypt($new_password),
        ]);
        if($res) return success('修改成功');
        return error('修改失败');
    }


    /**
    *忘记密码
    **/
    public function fogretpwd(){
        $phone = $this->req->post('phone');
        if(!preg_match("/^1[3456789]\d{9}$/", $phone)) return error('手机号格式不正确');
        $user = Db::table('kt_base_user')->where('telephone',$phone)->find();
        if(!$user) return error('账号不存在');
        $code = $this->req->post('code');
        $key = 'sms_'.$phone;
        $cacheCode = Cache::get($key);
        if(!$code || $code!=$cacheCode) return error('验证码不正确');
        $password = trim($this->req->post('password'));
        if(!$password) return error('请填写新密码');
        Db::table('kt_base_user')->where('id',$user['id'])->update(['pwd'=>ktEncrypt($password)]);
        Cache::delete($key);
        return success('修改成功');
    }  
}
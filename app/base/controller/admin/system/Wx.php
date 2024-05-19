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
use app\base\controller\BaseAdmin;
use app\base\model\admin\system\BasicModel;
use Ramsey\Uuid\Uuid;
use think\facade\Session;

/**
*  Wx
*/
class Wx extends BaseAdmin
{
	public function gzh()
	{
		$uid = Session::get('uid');
        $res = Db::table("kt_base_wxgzh")->where('uid',$uid)->find();
        $res['token'] = $res['token']??getRandStr(18);
        $res['message_key'] = $res['message_key']??getRandStr(43);
        $res['callback_url'] = $this->req->domain()."/base/user/wxcallback";
        return success('公众号配置',$res);
	}

	public function saveGzh()
	{
		$uid = Session::get('uid');
		$data = [];
		$data['uid'] = $uid;
		$data['appid'] = $this->req->param('appid');
		if(!$data['appid']) return error('请输入appid');
		$data['appsecret'] = $this->req->param('appsecret');
        if(!$data['appsecret']) return error('请输入appsecret');
        $data['token'] = $this->req->param('token');
        $data['message_key'] = $this->req->param('message_key');
        $data['switch'] = $this->req->param('switch',0);
        $data['message_mode'] = $this->req->param('message_mode',1);
        $data['original_id'] = $this->req->param('original_id');

        $has = Db::table('kt_base_wxgzh')->where('uid',$uid)->find();
        $data['update_time'] = date("Y-m-d H:i:s");
        if($has){
        	$data['id'] = $has['id'];
        }else{
        	$data['create_time'] = date("Y-m-d H:i:s");
        }
        Db::table('kt_base_wxgzh')->save($data);
		return success('保存成功');
	}

	public function getRandStr()
    {
    	$len = $this->req->param('len/d',18);
    	return success('随机字符串',getRandStr($len));
    }
}
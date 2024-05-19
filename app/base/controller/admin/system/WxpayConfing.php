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
use app\base\model\admin\system\WxpayConfingModel;
use Ramsey\Uuid\Uuid;
use think\facade\Session;

/**
* 微信配置
*/
class WxpayConfing extends BaseAdmin{
	/*
	* 配置拉取数据
	*/
	public function index(){
		$res = WxpayConfingModel::info();
		$data =[
            'appid' => isset($res[0]) ? $res[0] : null,
            'mch_id' => isset($res[1]) ? $res[1] : null,
            'key' => isset($res[2]) ? $res[2] : null,
            'appsecret' => isset($res[3]) ? $res[3] : null,
        ];
		return success("获取成功",$data);
	}

	/*
	* 添加修改配置
	*/
	public function upd(){
		$appid = $this->req->param("appid");
		$mch_id = $this->req->param("mch_id");
		$key = $this->req->param("key");
		$appsecret = $this->req->param("appsecret");
		if(!$appid || !$mch_id || !$key || !$appsecret)return error("缺少参数");
		$res = WxpayConfingModel::upd(["config"=>$appid.",".$mch_id.",".$key.",".$appsecret]);

		return success("修改成功",$res);
	}
}
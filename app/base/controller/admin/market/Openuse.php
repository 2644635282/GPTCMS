<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin\market;

use think\facade\Db;
use think\facade\Session;
use app\base\controller\BaseAdmin;
use app\base\model\admin\market\OpenuseModel;

class Openuse extends BaseAdmin
{
	/*
	* 已购应用列表
	*/
	public function allApp()
	{
		return OpenuseModel::allApp($this->req);
	}
	/*
	* 已购应用列表
	*/
	public function list()
	{
		return OpenuseModel::list($this->req);
	}
	/*
	* 已购应用列表
	*/
	public function updateStatus()
	{
		return OpenuseModel::updateStatus($this->req);
	}
	/*
	* 获取代理折扣
	*/
	public function discount()
	{
		return OpenuseModel::discount();
	}
	/*
	* 续费和购买
	*/
	public function buy()
	{
		return OpenuseModel::buy($this->req);
	}
	/*
	* 用户订单列表(用户在用户后台购买)
	*/
	public function userOrder()
	{
		return OpenuseModel::userOrderList($this->req);
	}
	/*
	* 套餐的开通与购买
	*/
	public function setmealBuy()
	{
		return OpenuseModel::setmealBuy($this->req);
	}
}
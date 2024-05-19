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
* 基础配置控制器 Site
*/
class Site extends BaseAdmin
{
	/*
	* 基本设置 拉取数据 初始化
	*/
	public function index(){
		$res = BasicModel::getSite();
		return success("获取成功",$res);
	}

	/*
	* 修改基础配置
	*/
	public function setSite(){
		$domain = $this->req->param("domain");
		if(!$domain) return error("站点域名不可为空");
		$webname = $this->req->param("webname");
		if(!$webname) return error("站点名称不可为空");
		$webtitle = $this->req->param("webtitle");
		$copyright = $this->req->param("copyright");
		$register_check = $this->req->param("registerCheck");
		$registration_audit = $this->req->param("registrationAudit");
		$pc_official = $this->req->param("pcOfficial");
		$debug = $this->req->param("debug",0);
		$register_setmeal = $this->req->param("register_setmeal");
		$register_setmeal_specsid = $this->req->param("register_setmeal_specsid");
		if($register_setmeal && !$register_setmeal_specsid) return error("请选择套餐规格");
		$is_direct_app = $this->req->param("is_direct_app");
		$direct_app = $this->req->param("direct_app");
		$addSite = BasicModel::setSite($domain,$webname,$webtitle,$copyright,$register_check,$registration_audit,$pc_official,$debug,$register_setmeal,$register_setmeal_specsid,$is_direct_app,$direct_app);
		return success("修改成功",$addSite);
	}

	/*
	* 修改logo信息
	*/
	public function setLogo(){
		$user_logo = $this->req->param("userLogo");
		$login_logo = $this->req->param("loginLogo");
		$pc_logo = $this->req->param("pcLogo");
		$setLogo = BasicModel::setLogo($user_logo,$login_logo,$pc_logo);
		return success("修改成功",$setLogo);
	}

	/*
	* 修改登录背景
	*/
	public function setBackground(){
		$login_background_status = $this->req->param("loginBackgroundStatus");
		$login_background = $this->req->param("loginBackground");
		$setBackground = BasicModel::setBackground($login_background,$login_background_status);
		return success("修改成功",$setBackground);
	}

	/*
	* 修改其他信息
	*/
	public function setAdditional(){
		$kf_code = $this->req->param("kfCode");
		$gzh_code = $this->req->param("gzhCode");
		$company_name = $this->req->param("companyName");
		$company_address = $this->req->param("companyAddress");
		$telephone = $this->req->param("telephone");
		$qq = $this->req->param("qq");
		$record_number = $this->req->param("recordNumber");
		$key_word = $this->req->param("keyWord");
		$describe = $this->req->param("describe");
		$setAdditional = BasicModel::setAdditional($kf_code,$gzh_code,$company_name,$company_address,$telephone,$qq,$record_number,$key_word,$describe);
		return success("修改成功",$setAdditional);
	}
}
<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\admin\market;
use think\facade\Db;
use think\facade\Session;
use app\base\model\BaseModel;
use app\kt_agent\model\admin\agent\ManageModel;
use think\facade\Cache;

/*
* 应用管理
*/
class OpenuseModel extends BaseModel
{
	/*
	* 获取所有应用列表
	*/
	static public function allApp($req)
	{	
		$keyword = $req->post('keyword');
		$res = Db::table("kt_base_market_app")->where(["app_type"=>[1,2]]);
		if($keyword) $res->where('name',"like","%{$keyword}%");
		$res = $res->json(['specs'])->select();
		return success('所用应用列表',$res);

	}
	/*
	* 已购应用列表
	*/
	static public function list($req)
	{
		$data = [];
		$uid = Session::get('uid');
		$ids = Db::table('kt_base_user')->where('agid',$uid)->column('id');
		$page = $req->post('page',1);
		$size = $req->post('size',10);
		$keyword = $req->post('keyword');
		$expired_type = $req->post('expired_type');

		$res = Db::table("kt_base_user_openapp")
			      ->field('id,wid,name,self_title,app_id,code,mend_time,sequence,logo,version,status,create_time')
			      ->where('wid','in',$ids);
		if($keyword) $res->where('name','like',"%{$keyword}%");
		if($expired_type) {
			switch ($expired_type) {
				case '1':
					$res->whereTime('mend_time','<=',date("Y-m-d H:i:s"));
					break;
				case '2':
					$res->whereBetweenTime('mend_time',date("Y-m-d H:i:s"),date("Y-m-d H:i:s",strtotime("+7 day")));
					break;
				case '3':
					$res->whereBetweenTime('mend_time',date("Y-m-d H:i:s"),date("Y-m-d H:i:s",strtotime("+30 day")));
					break;
				case '4':
					$res->whereBetweenTime('mend_time',date("Y-m-d H:i:s"),date("Y-m-d H:i:s",strtotime("+90 day")));
					break;
			}
		}
		$data['count'] = $res->count();
		$data['item'] = $res->order('create_time','desc')
			      ->page($page,$size)
			      ->filter(function($r){
			      	if(!$r['self_title']) $r['self_title']=$r['name'].$r['wid'];
			      	 $r['username'] = Db::table('kt_base_user')->where('id',$r['wid'])->value('un');
			      	 $specs = Db::table('kt_base_market_app')->where('id',$r['app_id'])->json(['specs'])->find();
			      	 $r['specs'] = $specs ? $specs ['specs']: [];
			      	 $market_app = Db::table("kt_base_market_app")->where('id',$r['app_id'])->find();
 			      	 $r['name'] = isset($market_app['name'])?$market_app['name']:$r['name'];
 			      	 $r['logo'] = isset($market_app['logo'])?$market_app['logo']:$r['logo'];
			      	return $r;
			      })
			      ->select();
		$data['page'] = $page; 
		$data['size'] = $size; 
 		return success('已购应用列表',$data);
	}

	/*
	* 开启停止
	*/
	static public function updateStatus($req)
	{
		$uid = Session::get('uid');
		$status = $req->post('status');
		$id = $req->post('id');
		if(!$id) return error('参数错误');
		$res = Db::table("kt_base_user_openapp")->where('id',$id)->update([
			'status' => $status,
			'update_time' => date("Y-m-d H:i:s")
		]);
		return success('修改成功');
	}
	/*
	* 获取代理折扣
	*/
	static public function discount()
	{
		$uid = Session::get('uid');
		$discount = 10;
		if(class_exists("app\kt_agent\model\admin\agent\ManageModel")){
			$discount = ManageModel::discount($uid) ?: 10;
		}
		return success('代理折扣',$discount);
	}
	/*
	* 续费和购买
	*/
	static public function buy($req)
	{
		$uid = Session::get('uid');
		$specsid = $req->post('specsid');
		if(!$specsid) return error('缺少参数specsid');
		$wid = $req->post('wid');
		if(!$wid) return error('缺少参数wid');
		$id = $req->post('app_id');//app_id
		$app = Db::table("kt_base_market_app")->json(['specs'])->find($id);
		if(!$app) return error('无此应用');
		$pid = 0;
		if($app['pid'] != 0){
 			$pApp = Db::table("kt_base_market_app")->find($app['pid']);
 			$pOpenapp = Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$pApp['id'])->find();
 			if(!$pOpenapp) return error('请先购买主应用');
 			$pid = $pOpenapp['id'];
 		}
		$specs = [];
		foreach ($app['specs'] as $v) {
			if($v['id'] == $specsid){
				$specs = $v;
				break;
			}
		}
		if(!$specs) return error('此应用无此规格');
		$agent = Db::table("kt_base_agent")->find($uid);
		$bh = date('YmdHis').rand(1000,9999).'|'.$wid;
		$discounty = 10;
		if(class_exists("app\kt_agent\model\admin\agent\ManageModel")){
			$discounty = ManageModel::discount($uid) ?: 10;
		}
		$discount = $discounty/10; 
		$discount_price = sprintf("%.2f",$specs['price']*$discount);
		if($agent['balance'] < $discount_price) return error('余额不足');
		$orderId = Db::table("kt_base_agent_apporder")->insertGetId([
			'uid' => $uid,
			'wid' => $wid,
			'app_id' => $id,
			'bh' => $bh,
			'specs_id' => $specs['id'],
			'specs_content' => json_encode($specs,320),
			// 'content' => $content,
			// 'openapp_id' => Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$id)->value('id'),
			'price' => $specs['price'],
			'discount_price' => $discount_price,
			'create_time'=>date("Y-m-d H:i:s"),
		]);
		if(!$orderId) return error('开通失败');
		Db::table("kt_base_agent")->where('id',$uid)->update([
			'balance' => Db::raw('balance-'.$discount_price),
			'update_time'=>date("Y-m-d H:i:s"),
		]);

		$data = [
			'wid' => $wid,
 			'name' => $app['name'],
 			'code' => $app['code'],
 			'logo' => $app['logo'],
 			'version' => $app['version'],
 			// 'mend_time' => date("Y-m-d H:i:s",strtotime("+".$app['try_days']." day")),
 			'update_time' => date("Y-m-d H:i:s"),
 			'app_id' => $id,
 			'pid' => $pid,
		];

 		$has = Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$id)->find();
 		if($has){
 			$data['id'] = $has['id'];
 			if(strtotime($has['mend_time'] < time())){
 				$date = time();
 			}else{
 				$date = strtotime($has['mend_time']);
 			} 
 		}else{
 			$date = time();
			$data['create_time'] = date("Y-m-d H:i:s");
 		}
 		$content = '';
 		switch ($specs['duration_type']) {
 			case 2:
 				$data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$specs['duration']." month",$date));
 				break;
 			case 3:
 				$data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$specs['duration']." year",$date));
 				break;
 		}
 		$res = Db::table("kt_base_user_openapp")->save($data);
 		if(!$res){
 			Db::table("kt_base_user")->where('id',$wid)->update([
 				'balance' => Db::raw('balance+'.$discount_price)
 			]);
 			return error('购买失败');
 		}
 		return success('购买成功');
	}

	/*
	* 用户订单列表(用户在用户后台购买)
	*/
	static public function userOrderList($req)
	{
		$uid = Session::get('uid');
		$ids = Db::table('kt_base_user')->where('agid',$uid)->column('id');
		$page = $req->post('page',1);
		$size = $req->post('size',10);
		$keyword = $req->post('keyword');
		$res = Db::table("kt_base_user_order")
				  ->alias('o')
			      ->field('o.id,o.wid,o.content,o.app_id,o.price,o.create_time,o.openapp_id,o.status,o.balance_pay,o.specs_id,o.bh,o.specs_content,a.name')
			      ->leftJoin("kt_base_market_app a","o.app_id=a.id")
			      ->json(['o.pecs_content'])
			      ->where('o.wid','in',$ids);
		if($keyword) $res->where("a.name",'like',"%{$keyword}%");
		$data['count'] = $res->count();
		$data['item'] = $res->order('o.create_time','desc')
			      ->page($page,$size)
			      ->select();
		$data['page'] = $page; 
		$data['size'] = $size; 
		return success('用户订单列表',$data);
	}

	/*
	* 开通套餐
	*/
	static public function setmealBuy($req)
	{
		$uid = Session::get('uid');
		$specsid = $req->post('specsid');
		$packageid = $req->post('packageid');
		if(!$packageid) return error('缺少参数packageid');
		if(!$specsid) return error('缺少参数specsid');
		$wid = $req->post('wid');
		if(!$wid) return error('缺少参数wid');
		$package = Db::table('kt_base_app_package')->json(['specs','apps'])->find($packageid);
		if(!$package) return error("参数错误");
		$specs = [];
		foreach ($package['specs'] as $v) {
			if($v['id'] == $specsid){
				$specs = $v;
				break;
			}
		}
		if(!$specs) return error('不存在规格');

		$agent = Db::table("kt_base_agent")->find($uid);
		$bh = date('YmdHis').rand(1000,9999).'|'.$wid;
		$discounty = 10;
		if(class_exists("app\kt_agent\model\admin\agent\ManageModel")){
			$discounty = ManageModel::discount($uid) ?: 10;
		}
		$discount = $discounty/10;
		$discount_price = sprintf("%.2f",$specs['price']*$discount);
		if($agent['balance'] < $discount_price) return error('余额不足');
		$orderData = [];

		foreach ($package['apps'] as  $code) {
			$app = Db::table("kt_base_market_app")->where('code',$code)->find();
			$orderData[] = [
				'uid' => $uid,
				'wid' => $wid,
				'app_id' => $app['id'],
				'bh' => $bh,
				'specs_id' => $specs['id'],
				'specs_content' => json_encode($specs,320),
				// 'content' => $content,
				// 'openapp_id' => Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$id)->value('id'),
				'price' => $specs['price'],
				'discount_price' => $discount_price,
				'create_time'=>date("Y-m-d H:i:s"),
				'setmeal_type' => 2,
			];
		}
		$orderRes = Db::table("kt_base_agent_apporder")->insertAll($orderData);
		if(!$orderRes) return error('开通失败');
		//套餐开通记录
		Db::table("kt_base_user_package_recode")->insert([
			"uid" => $uid,
			"wid" => $wid,
			"package_id" => $packageid,
			"specs_id" => $specsid,
			"app"=>json_encode($package['apps']),
			"specs_content"=>json_encode($specs,320),
			"create_time" => date("Y-m-d H:i:s"),
		]);
		//扣款
		Db::table("kt_base_agent")->where('id',$uid)->update([
			'balance' => Db::raw('balance-'.$discount_price),
			'update_time'=>date("Y-m-d H:i:s"),
		]);
		foreach ($package['apps'] as  $code) {
			$app = Db::table("kt_base_market_app")->where('code',$code)->find();
			$data = [
				'wid' => $wid,
	 			'name' => $app['name'],
	 			'code' => $app['code'],
	 			'logo' => $app['logo'],
	 			'version' => $app['version'],
	 			// 'mend_time' => date("Y-m-d H:i:s",strtotime("+".$app['try_days']." day")),
	 			'update_time' => date("Y-m-d H:i:s"),
	 			'app_id' => $app['id'],
			];

	 		$has = Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$app['id'])->find();
	 		if($has){
	 			$data['id'] = $has['id'];
	 			if(strtotime($has['mend_time'] < time())){
	 				$date = time();
	 			}else{
	 				$date = strtotime($has['mend_time']);
	 			} 
	 		}else{
	 			$date = time();
				$data['create_time'] = date("Y-m-d H:i:s");
	 		}
	 		$content = '';
	 		switch ($specs['duration_type']) {
	 			case 1:
	 				$data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$specs['duration']." day",$date));
	 				break;
	 			case 2:
	 				$data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$specs['duration']." month",$date));
	 				break;
	 			case 3:
	 				$data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$specs['duration']." year",$date));
	 				break;
	 		}
	 		$res = Db::table("kt_base_user_openapp")->save($data);
		}
		return success('开通成功');
	}

}
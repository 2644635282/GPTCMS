<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\user;
use think\facade\Db;
use think\facade\Session;
use think\facade\Request;
use app\base\model\BaseModel;
use paySDK\NewPay;


/**
 * 应用
 */
class AppModel extends BaseModel
{
	/**
 	* 获取已购买的应用列表
 	*/
 	static public function openAppList($where,$page=1,$size=10)
 	{
 		$wid = Session::get('wid');
 		$res = Db::table("kt_base_user_openapp")
 			      ->field('id,name,self_title,app_id,code,mend_time,sequence,logo,version,create_time')
 			      ->where("wid",$wid)
 			      ->where("status",1)
 			      ->where($where)
 			      ->order('sequence','desc')
 			      // ->page($page,$size)
 			      ->filter(function($r){
 			      	$remaining = 0;
 			      	if(strtotime($r['mend_time']) > time()) $remaining = ceil((strtotime($r['mend_time']) - time())/86400);
 			      	$r['remaining'] = $remaining;
 			      	$r['plug'] = Db::table("kt_base_user_openapp")->where('pid',$r['id'])->find() ? true : false;
 			      	$market_app = Db::table("kt_base_market_app")->where('id',$r['app_id'])->find();
 			      	$r['name'] = isset($market_app['name'])?$market_app['name']:$r['name'];
 			      	$r['logo'] = isset($market_app['logo'])?$market_app['logo']:$r['logo'];
 			      	$r['has_applets'] = isset($market_app['has_applets'])?$market_app['has_applets']:0;
 			      	$r['applets_url'] = Request::instance()->domain().'/open/user/auth';
 			      	return $r;
 			      })
 			      ->select();
 		return $res;
 	}
 	/**
 	*修改自定义标题
 	*/
 	static public function save($data)
 	{
 		$res = Db::table('kt_base_user_openapp')->save($data);
 		return 'ok';
 	}
 	/**
 	*获取 跳转用户后台链接
 	*/
	static public function  openappUse($req)
	{	
		$id = $req->get('id');
        if(!$id) return error('参数错误');
		$wid = Session::get('wid');
		$openApp = Db::table("kt_base_user_openapp")->where('wid',$wid)->where('status',1)->find($id);
		if(!$openApp) return error('未购买应用');
		if(strtotime($openApp['mend_time'])<time()) return error('已过期,请续费');
		$app = Db::table("kt_base_market_app")->find($openApp['app_id']);
		if(!$app) return error('无此应用');
		return success('用户后台链接',$req->domain().$app['user_link']);
	}
 	/**
 	* 获取全部应用列表
 	*/
 	static public function appList($where=array(),$page=1,$size=10)
 	{
 		$wid = Session::get('wid');
 		$res = Db::table("kt_base_market_app")
 			      ->field('id,name,code,type,try_days,logo,scene,label,specs,describe,c_time as create_time')
 			      ->json(['specs','label'])
 			      ->where("shelves",1);
 		if($where) $res = $res->where($where);
 		 $res =	  $res->order('sort','desc')
 			      ->page($page,$size)
 			      ->filter(function($r)use($wid){
 			      	$openApp = Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$r['id'])->find();
 			      	if($openApp){
 			      		$r['mend_time'] = $openApp['mend_time'];
 			      	} else{
 			      		$r['mend_time'] = '';
 			      	}
 			      	return $r;
 			      })
 			      ->select();
 		return $res;
 	}
 	/**
 	* 获取应用详情
 	*/
 	static public function appDetail($id)
 	{
 		$wid = Session::get('wid');
 		$res = Db::table('kt_base_market_app')->field('id,name,code,type,try_days,logo,scene,label,specs,c_time as create_time')->json(['specs'])->find($id);
 		return $res;
 	}
 	/**
 	* 获取应用类型
 	*/
 	static public function appType()
 	{
 		$wid = Session::get('wid');
 		$res = Db::table('kt_base_market_type')->field('id,name,level')->order('sort','asc')->select()->toArray();
 		array_unshift($res,['id'=>0,'name'=>'全部','level'=>1]);
 		return $res;
 	}
 	/**
 	* 试用
 	*/
 	static public function tryout($id)
 	{
 		$wid = Session::get('wid');
 		$has = Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$id)->find();
 		if($has) return success('已试用');
 		$app = Db::table("kt_base_market_app")->find($id);
 		if(!$app) return error('参数错误');
 		if($app['try_days'] == 0) return error('此应用没有试用');
 		$data = [
 			'wid' => $wid,
 			'name' => $app['name'],
 			'code' => $app['code'],
 			'logo' => $app['logo'],
 			'version' => $app['version'],
 			'mend_time' => date("Y-m-d H:i:s",strtotime("+".$app['try_days']." day")),
 			'create_time' => date("Y-m-d H:i:s"),
 			'update_time' => date("Y-m-d H:i:s"),
 			'app_id' => $id,
 		];
 		$pid = 0;
 		if($app['pid'] != 0){
 			$pApp = Db::table("kt_base_market_app")->find($app['pid']);
 			$pOpenapp = Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$pApp['id'])->find();
 			if(!$pOpenapp) return error('请先购买主应用');
 			$pid = $pOpenapp['id'];
 		}
 		$data['pid'] = $pid;
 		$openappid = Db::table("kt_base_user_openapp")->insertGetId($data);
 		Db::table("kt_base_user_order")->insert([
 				'wid' => $wid,
 				'app_id' => $id,
 				'content' => '用户试用',
 				'openapp_id' => $openappid,
 				'price' => 0,
 				'status' => 2,
 				'create_time'=>date("Y-m-d H:i:s"),
 			]);
 		return success('试用成功');
 	}
 	/**
 	* 购买
 	*/
 	static public function buy($id,$specsid)
 	{
 		$wid = Session::get('wid');
 		$uid = Session::get('uid');
 		$app = Db::table("kt_base_market_app")->json(['specs'])->find($id);
		if(!$app) return error('参数错误');
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
		$user = Db::table("kt_base_user")->find($wid);
				 		// var_dump($user);die;
		$bh = date('YmdHis').rand(1000,9999).'|'.$wid;
		$orderId = Db::table("kt_base_user_order")->insertGetId([
			'wid' => $wid,
			'app_id' => $id,
			'bh' => $bh,
			'specs_id' => $specs['id'],
			'specs_content' => json_encode($specs,320),
			// 'content' => $content,
			// 'openapp_id' => Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$id)->value('id'),
			'price' => $specs['price'],
			'status' => 1,
			'create_time'=>date("Y-m-d H:i:s"),
		]);
 		if(!$orderId) return error('购买失败');
		if($user['balance'] < $specs['price']){
 			$actualPay = ($specs['price'] - $user['balance'])*100;
 			$payData = [
		        'bh'=> $bh,
		        'order_bh'=> $bh,
		        'wid'=> $wid,
		        'create_time'=> date('Y-m-d H:i:s'),
		        'update_time'=> date('Y-m-d H:i:s'),
		        'uip'=> '',
		        'amount'=> sprintf("%.2f",$actualPay/100),
		        'status'=>'等待买家付款',
		        'bz'=> '微信',
		        'ifok'=>0,
		    ];
		    Db::table('kt_base_recharge')->insertGetId($payData);
		    $intRes = NewPay::init($uid);
		    if($intRes) return error($intRes);
            $notifyUrl = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME']."/base/user/callbackwxpay";
            $codeImg = NewPay::Wxpay($actualPay,$bh,$app['name'],$notifyUrl); 
            return success('微信链接',['url'=>$codeImg,'order_bh'=>$bh]);
 		}	

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
 		// [{ "id":1,"duration": 1, "duration_type": 1, "old_price": 0.02, "price": 0.01 }]
 		$content = '';
 		$day = 0;
 		switch ($specs['duration_type']) {
 			case 2:
 				$content = '用户购买'.$app['name'].'规格时长'.$specs['duration'].'月的套餐';
 				$data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$specs['duration']." month",$date));
 				break;
 			case 3:
 				$content = '用户购买'.$app['name'].'规格时长'.$specs['duration'].'年的套餐';
 				$data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$specs['duration']." year",$date));
 				break;
 		}
 		
 		$res = Db::table("kt_base_user_openapp")->save($data);

 		if($res){
 			Db::table("kt_base_user")->where('id',$wid)->update([
 				'balance' => Db::raw('balance-'.$specs['price']),
 				'update_time'=>date("Y-m-d H:i:s"),
 			]);
 			Db::table("kt_base_user_order")->where('id',$orderId)->update([
 				'status' => 2,
 				'content' => $content
 			]);
 			return success('购买成功');
 		}
 		return error('购买失败');
 	}

 	/**
 	* 下单
 	*/
 	static public function getPayResult($req)
 	{
 		$bh = $req->post('bh');
 		if(!$bh) return error('参数错误'); 
 		$order = Db::table('kt_base_recharge')->where('bh',$bh)->where('ifok',1)->find();
        if(!$order) return success('等待中...',false);
        return success('支付成功',true);
 	}

}
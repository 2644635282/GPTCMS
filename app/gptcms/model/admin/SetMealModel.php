<?php 
namespace app\gptcms\model\admin;
use think\facade\Db;
use think\facade\Session;
use app\base\model\BaseModel;

/* 
* 用户套餐类model
*/
class SetMealModel extends BaseModel
{
	/*
	*  用户套餐列表
	*/
	static public function info(){
		$uid = Session::get('uid');
		$res = Db::table("kt_gptcms_setmeal_type")
					->select()
					->toArray();		
		$setmeal = Db::table("kt_gptcms_setmeal_price")->where(['uid'=>$uid])->find();
		
		$data['price'] = isset($setmeal['price'])?json_decode($setmeal['price'],1):[];
		foreach($res as $key=>$value){
			$res[$key]['price'] = $data['price'][$value["level"]];
		}
		return $res;
	}

	/*
	*  用户套餐详情
	*/
	static public function infos($id){
		$uid = Session::get('uid');
		$res = Db::table("kt_gptcms_setmeal_type")->where(['id'=>$id])->find();
		$setmeal = Db::table("kt_gptcms_setmeal_price")->where(['uid'=>$uid])->find();
		$res['price'] = isset($setmeal['price'])?json_decode($setmeal['price'],1)[$res['level']]:0;
		return $res;
	}

	/*
	 *  套餐详情
	 */
	static public function getSetmealInfo($id){
		$res = Db::table('kt_gptcms_setmeal_type')->find($id);
		return $res;
	}

	static public function getLevel(){
		$res = Db::table('kt_gptcms_setmeal_type')->order('level')->select()->toArray();
		return $res;
	}

	/**
	* 单单总后台管理员的 设置套餐
	*/
	static public function addType($name,$price){
		$uid = Session::get('uid');
		$data["level"] = Db::table("kt_gptcms_setmeal_type")->max("level")+1;
		$data["name"] = $name;
		$res = Db::table("kt_gptcms_setmeal_type")->save($data);
		$user_setmeal = Db::table("kt_gptcms_setmeal_price")->where(["uid"=>$uid])->find();
		if($user_setmeal){
			$s['user_type_id'] = json_decode($user_setmeal["user_type_id"],1);
			$s['price'] = json_decode($user_setmeal['price'],1);
		}
		//设置admin的套餐价格跟
		$s['user_type_id'][] = $data["level"];
		$datas["user_type_id"] = json_encode($s['user_type_id'],320);
		$s['price'][$data["level"]] = $price;
		$datas['price'] = json_encode($s['price'],320);
		$datas["uid"] = $uid;
		if($user_setmeal) Db::table("kt_gptcms_setmeal_price")->where(['uid'=>$uid])->save($datas);
		if(!$user_setmeal) Db::table("kt_gptcms_setmeal_price")->save($datas);
		return $res;
	}

	/**
	* 单单总后台管理员的 修改套餐
	*/
	static public function updType($name,$price,$id){
		$uid = Session::get('uid');
		$data["name"] = $name;
		$user_type = Db::table("kt_gptcms_setmeal_type")->where(["id"=>$id])->find();
		if($user_type) Db::table("kt_gptcms_setmeal_type")->where(["id"=>$id])->save($data);
		$user_setmeal = Db::table("kt_gptcms_setmeal_price")->where(["uid"=>$uid])->find();
		if($user_setmeal){
			$s['user_type_id'] = json_decode($user_setmeal["user_type_id"],1);
			$s['price'] = json_decode($user_setmeal['price'],1);
		}
		$s['user_type_id'][] = $user_type["level"];
		$datas["user_type_id"] = json_encode($s['user_type_id'],320);
		$s['price'][$user_type["level"]] = $price;
		$datas['price'] = json_encode($s['price'],320);
		$datas["uid"] = $uid;
		$res = Db::table("kt_gptcms_setmeal_price")->where(["uid"=>$uid])->save($datas);
		return $res;
	}

	/**
	* 判断名称
	*/
	static public function isExist($name,$id=NULL){
		if($id) $res = Db::table("kt_gptcms_setmeal_type")->where(["name"=>$name])->where('id','<>',$id)->find();
		if(!$id) $res = Db::table("kt_gptcms_setmeal_type")->where(["name"=>$name])->find();
		return $res;
	}

	/*
	* 判断上级代理是否开启
	*/
	static public function isSetMeal(){
		$uid = Session::get('uid');
		$user = Db::table('kt_base_agent')->find($uid);
		if($user["isadmin"] == 1) return true;
		$res = Db::table("kt_gptcms_setmeal_price")->where(["uid"=>$user['pid']])->find();

		return $res;
	}

	/*
	* 修改套餐
	*/
	static public function setMeal($data){
		$uid = Session::get('uid');
		$user = Db::table('kt_base_agent')->find($uid);
		$agent_user_setmeal = Db::table("kt_gptcms_setmeal_price")->where(["uid"=>$user['pid']])->find();
		if($user["isadmin"] == 1) $agent_user_setmeal = Db::table("kt_gptcms_setmeal_price")->where(["uid"=>$user["id"]])->find();;
		$agent_price = json_decode($agent_user_setmeal["price"],1);
		$user_type_id = [];
		$prices = [];
		foreach ($data as $value){
			if($agent_price[$value["level"]] >= $value["price"])return "套餐级别".$value["level"]."的金额不能比上级金额高，上级金额为".$agent_price[$value["level"]];
			$user_type_id[] = $value["level"];
			$prices[$value["level"]] = $value["price"];
		}
		$setmeal = Db::table("kt_gptcms_setmeal_price")->where(["uid"=>$uid])->find();
		$where = [];
		if($setmeal) $where["uid"] = $uid;
		$datas["uid"] = $uid;
		$datas["price"] = json_encode($prices,320);
		$datas["user_type_id"] = json_encode($user_type_id,320);
		$res = Db::table("kt_gptcms_setmeal_price")->where($where)->save($datas);

		return 1;
	}

	/*
	* 判断是否有用户使用该套餐
	*/
	static public function isSetType($id){
		$user_type = Db::table("kt_gptcms_setmeal_type")->where(["id"=>$id])->find();
		$res = Db::table("kt_base_user")->where(["level_id"=>$user_type["level"]])->count();

		return $res;
	}

	/*
	* 判断是否有用户使用该套餐
	*/
	static public function delType($id){
		$res = Db::table("kt_gptcms_setmeal_type")->where(["id"=>$id])->delete();

		return $res;
	}

	/*
	* 套餐权限，只能管理员设置
	*/
	static public function auth($level,$auths=[]){
		$data["level"] = $level;
		$data["auths"] = json_encode($auths,320);
		$auth = Db::table('kt_gptcms_setmeal_auth')->where(["level"=>$level])->find();
		if(!$auth) $res = Db::table('kt_gptcms_setmeal_auth')->save($data);
		if($auth) $res = Db::table('kt_gptcms_setmeal_auth')->where(["level"=>$level])->save($data);
		return $res;
	}

	/**
	* 获取套餐权限
	*/
	static public function getAuth($level){
		$auth = Db::table('kt_gptcms_setmeal_auth')->where(["level"=>$level])->find();
		if($auth)$auth["auths"] = json_decode($auth["auths"],1);
		return $auth;
	}

	/*
 	 * 获取代理已配置的套餐 无限级向上
 	 */
 	static public function getSetmeal($uid){
		$res = Db::table("kt_gptcms_setmeal_price")->where(["uid"=>$uid])->find();
		if(!$res){
			$user = Db::table('kt_base_agent')->find($uid);
			if($user['isadmin'] == 1) return $res;
			$res = self::getSetmeal($user['pid']);
		}
		return $res;
 	}

 	/*
 	 * 套餐
 	 */
 	static public function setmealExplain($uid){
 		$setmeal = self::getSetmeal($uid);
 		$data['price'] = json_decode($setmeal["price"],1);
 		return $data;
 	}
}
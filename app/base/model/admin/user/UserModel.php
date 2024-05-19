<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\admin\user;
use think\facade\Db;
use think\facade\Session;
use app\base\model\BaseModel;

/* 
* 总后台用户类model
*/
class UserModel extends BaseModel
{
 	/**
 	* 直接客户
 	*/
 	static public function info($page=1,$un=NULL,$status=NULL,$size=10,$agname=NUll){
 		$wheres = [];
 		$uid = Session::get("uid");
		$admin = Db::table("kt_base_agent")->where(["id"=>$uid])->find();
 		if($admin["isadmin"] != 1) $wheres["u.agid"] = BaseModel::getAdentIds($uid);
 		if($status == 1) $wheres["u.isstop"] = 1;
 		if($status == 2) $wheres["u.isstop"] = 0;
 		if($status == 3) $wheres["u.isstop"] = 2;
 		$where = [
            ['u.un', 'like', '%'.$un.'%'],
            ['u.telephone', 'like', '%'.$un.'%'],
            ['u.id', 'like', '%'.$un.'%']
        ];
  		$res = Db::table("kt_base_user")
					->alias("u")
					->join('kt_base_agent a','u.agid=a.id')
					->field("u.un,u.id,u.telephone,u.isstop,u.create_time,u.agid,u.remark,u.level_id,u.balance,a.un as agname")
					->where($wheres);
  		if($un) $res->where(function ($query) use($where) { $query->whereOr($where); });
  		if($agname) $res->whereLike('a.un','%'.$agname.'%');
		$data['count'] = $res->count();
		$data['item'] = $res->page($page,$size)
						->filter(function($data){
							$logins = Db::table("kt_base_loginlog")->where(["wid"=>$data["id"],"admin"=>2])->find();
 							if($logins)$data["login_time"] = $logins["create_time"];
 							$agency = Db::table("kt_base_agent")->where(["id"=>$data["agid"]])->field("un")->find();
 							$data["agname"] = $agency["un"];
 							// $setmeal = Db::table("kt_svideo_type")->where(["level"=>$data["level_id"]])->find();
 							// if($setmeal)$data["setmeal"] = $setmeal["name"];
							if($data["remark"]) $data["remark_status"] = 1;
							return $data;
						})
 						// ->fetchSql(true)
 						->select()
 						->toArray();
		$data['page'] = $page;
		$data['size'] = $size;
		return $data;
 	}


	/**
 	* 开关
 	*/
 	static public function switch($id,$status){
 		$res = Db::table("kt_base_user")->where(["id"=>$id])->save(["isstop"=>$status]);

 		return $res;
 	}

 	/**
 	* 获取用户信息
 	*/
 	static public function user($id){
 		$res = Db::table("kt_base_user")->where(["id"=>$id])->find();

 		return $res;
 	}

 	/**
 	* 查看是否名称重复
 	*/
 	static public function setUser($username,$id){
 		$user = Db::table("kt_base_user");
		if($id)	$user = $user->where('id','<>',$id);
		$user = $user->where(["un"=>$username])->find();
 		return $user;
 	}

 	/**
 	* 修改用户信息
 	*/
  // `mendtime` datetime DEFAULT NULL COMMENT '到期时间',
 	static public function addUser($username,$password,$telephone,$contacts,$agid,$remark=NULL,$id=NULL){
 		if(!$agid) $agid = Session::get("uid");
 		$data["un"] = $username;
 		$data["pwd"] = $password;
 		$data["telephone"] = $telephone;
 		$data["contacts"] = $contacts;
 		$data["remark"] = $remark;
 		$data["agid"] = $agid;
 		$data["isadmin"] = 2;
 		$data["status"] = 1;
 		$data["isstop"] = 1;
 		$data["balance"] = "0.00";
 		if(!$id) $data["create_time"] = date("Y-m-d H:i:s",time());
 		if($id) $data["update_time"] = date("Y-m-d H:i:s",time());
 		$where = [];
 		if($id){
      $where["id"] = $id;
      $res = Db::table("kt_base_user")->where($where)->save($data);
    }else{
      $res = Db::table("kt_base_user")->insertGetId($data);
      BaseModel::openRegisterSetmeal($res);
    }
 		return $res;
 	}


 	//拉取代理列表
 	static public function getAgents(){
 		$uid = Session::get("uid");
 		$res = Db::table("kt_base_agent")->field("un,id")->select()->toArray();

 		return $res;
 	}


 	/**
 	* 获取用户数据
 	*/
 	static public function getUser($id){
 		$res = Db::table("kt_base_user")->where(["id"=>$id])->field("id,un,telephone,agid,balance,remark,contacts")->find();
 		
 		return $res;
 	}

 	/**
 	* 获取代理数据
 	*/
 	static public function getAgent(){
 		$uid = Session::get("uid");
 		$res = Db::table("kt_base_agent")->where(["id"=>$uid])->find();

 		return $res;
 	}

 	/**
 	* 修改代理数据
 	*/
 	static public function updAgent($balance){
 		$uid = Session::get("uid");
 		$res = Db::table("kt_base_agent")->where(["id"=>$uid])->save(["balance"=>$balance]);

 		return $res;
 	}

 	/**
 	* 修改用户数据
 	*/
 	static public function updUser($id,$balance){
 		$res = Db::table("kt_base_user")->where(["id"=>$id])->save(["balance"=>$balance]);

 		return $res;
 	}


 	/*
 	* 获取已开套餐的引擎
 	*/
 	public static function list(){
        $uid = Session::get('uid');
        $data = [];
        $fileList = getFileList('mainfest.xml');
        $fileList1 = getFileList('manifest.xml');
        $fileList = array_merge($fileList,$fileList1);
        $res = [];
        foreach ($fileList as $file) {
             $xml = simplexml_load_file($file,'SimpleXMLElement', LIBXML_NOCDATA);
             $xmlArr = json_decode(json_encode($xml), 1);
             if(isset($xmlArr['application']) && $xmlArr['application']["setmeal"] == 1) $res[] = $xmlArr['application'];
        }
        return $res;
    }

 	/**
 	* 修改用户 套餐
 	*/
 	static public function updUsers($id,$setmeal_id,$mend_time){
 		$data["level_id"] = $setmeal_id;
 		$data["mendtime"] = $mend_time;
 		$res = Db::table("kt_base_user")->where(["id"=>$id])->save($data);

 		return $res;
 	}

 	/*
 	* 充值或者扣除记录
 	*/
 	static public function addRecord($money,$out_trade_no,$type,$id,$remark){
 		$uid = Session::get("uid");
 		$data["user_id"] = $id;
 		$data["money"] = $money;
 		$data["out_trade_no"] = $out_trade_no;
 		$data["type"] = $type;
 		$data["agid"] = $uid;
 		$data["remark"] = $remark;
 		$data["recharge_time"] = date("Y-m-d H:i:s",time());
 		$res = Db::table("kt_base_user_recharge_record")->save($data);
 		return $res;
 	}

 	/*
 	* 升级续费 无限级向上
 	*/
 	static public function getSetmeal($uid,$name){
		$res = Db::table("kt_".$name."_setmeal")->where(["uid"=>$uid])->find();
		if(!$res){
			$user = Db::table('kt_base_agent')->find($uid);
			if($user['isadmin'] == 1) return $res;
			$res = self::getSetmeal($user['pid'],$name);
		}
		return $res;
 	}

 	/*
 	* 套餐已开应用
 	*/
 	static public function auth($id){
 		$res = Db::table("kt_base_user_appauth")
 					->where(["wid"=>$id])
 					->field("code,title,mend_time,name,set_meal")
 					->filter(function($data){
 						$setmeal = Db::table("kt_".$data["name"]."_type")->where(["level"=>$data["set_meal"]])->field("name")->find();
 						$data["setMealName"] = $setmeal["name"];
 						return $data;
 					})
 					->select()
 					->toArray();
 		return $res;
 	}

 	/*
 	* 查看现在的套餐
 	*/
 	static public function userType($id){
 		$res = Db::table("kt_base_user_type")->where(["level"=>$id])->field("name")->find();

 		return $res;
 	}

 	/*
 	* 获取代理商的折扣
 	*/
 	static public function getAgentLevel($id){
 		$res = Db::table("kt_base_agent_level")->where(["id"=>$id])->find();

 		return $res;
 	}

 	/*
 	* 套餐升级
 	*/
 	static public function addSetmealRecord($id,$setmeal_id,$price_difference,$prices,$money,$mend_time,$name,$year=0,$day=0){
 		$uid = Session::get("uid");
 		$data["wid"] = $id;
 		$data["level_id"] = $setmeal_id;
 		$data["difference"] = $price_difference;
 		$data["price"] = $prices;
 		$data["money"] = $money;
 		$data["months"] = $year;
 		$data["days"] = $day;
 		$data["agent_id"] = $uid;
 		$data["mendtime"] = $mend_time;
 		$data["ctime"] = date("Y-m-d H:i:s",time());
 		$res = Db::table("kt_".$name."_setmeal_record")->insertGetId($data);

 		return $res;
 	}

 	/*
 	* 获取到代理配置的套餐之后，获取套餐名称+价格
 	*/
 	static public function setMeal($data,$name){
 		foreach ($data as $key=>$value){
 			$type = Db::table("kt_".$name."_type")->where(["level"=>$key])->find();
 			$res["name"] = $type["name"];
 			$res["price"] = $value;
 			$res["id"] = $key;
 			$ress[] = $res;
 		}
 		return $ress;
 	}

 	/**
 	* 选中用户的已开通套餐
 	*/
 	static public function appauth($id,$name){
 		$res = Db::table("kt_base_user_appauth")->where(["wid"=>$id,"name"=>$name])->field("mend_time,set_meal")->find();

 		return $res;
 	}

 	/**
 	* 引擎套餐开同
 	*/
 	static public function updAppauth($id,$code,$title,$name,$mend_time,$setmeal_id){
 		$res = Db::table("kt_base_user_appauth")->where(["wid"=>$id,"name"=>$name])->find();
 		if($res) $data["id"] = $res["id"];
 		$data["wid"] = $id;
 		$data["code"] = $code;
 		$data["title"] = $title;
 		$data["name"] = $name;
 		$data["set_meal"] = $setmeal_id;
 		$data["mend_time"] = $mend_time;
 		$data["create_time"] = date("Y-m-d H:i:s",time());

 		$save = Db::table("kt_base_user_appauth")->save($data);
 		return $save;
 	}

 	/*
 	* 越权登录获取token
 	*/
 	static public function getToken($id){
  		$res = Db::table("kt_base_user")->where(["id"=>$id])->field("token,expire_time")->find();
  		if($res['expire_time'] < time())Db::table('kt_base_user')->where('id',$id)->update(['expire_time'=> time() + (7*24*3600) ]);
  		
  		return $res;
 	}

 	/*
    * 下級代理列表
    */
    static public function getAdents($ids){
        $uid = Session::get("uid");
        $res = Db::table('kt_base_agent')->where(["id"=>$ids])
                            ->field("un,id")
                            ->filter(function($data) use($uid){
                                if($data["id"] == $uid) $data["set"] = 1;
                                return $data;
                            })
                            ->select()
                            ->toArray();
        return $res;
    }
}
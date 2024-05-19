<?php 
namespace app\gptcms\model\admin;
use think\facade\Db;
use app\gptcms\model\TiktokModel;
use think\facade\Session;
use app\base\model\BaseModel;

/**
* 应用内用户管理
**/

class UserModel extends BaseModel
{
	/**
	 * 拥有应用权限的用户
	 * @param $wid 账户id
	 * @return 
	 */
 	static public function list($req){
 		$data = [];
 		$page = $req->param("page",1);
	 	$size = $req->param("size",10);
        $status = $req->param("status");
        if($status!=='') $where['uisstop'] = $status;
        $un = $req->param('un');
        $agname = $req->param("agname");

 		$uid = Session::get('uid');
 		$appInfo = self::getMainfest('gptcms');
 		$code = $appInfo['application']['code'];
		$res = Db::table('kt_base_user_openapp')
			   ->alias('a')
			   ->field('a.wid,a.mend_time,u.*,g.un as agname')
			   ->join('kt_base_user u','a.wid=u.id')
			   ->join('kt_base_agent g' ,'u.agid=g.id')
			   ->where('u.agid',$uid)
			   ->where('a.code',$code);
		if($un) $res->where(function ($query) use($un) { 
					$query->whereOr([
						['u.un', 'like', '%'.$un.'%'],
			            ['u.telephone', 'like', '%'.$un.'%'],
			            ['u.id', 'like', '%'.$un.'%']
					]);
				});
		if($agname) $res->where('g.un','like',$agname);
				$res->filter(function($datas){
					$user_setmeal = Db::table('kt_gptcms_user_setmeal')->where(["wid"=>$datas["wid"]])->find();
					$user_setmeal["level"] = isset($user_setmeal["level"])?$user_setmeal["level"]:'';
					$setmeal_type = Db::table('kt_gptcms_setmeal_type')->where(["level"=>$user_setmeal["level"]])->find();
					$datas["setmeal_mend_time"] = isset($user_setmeal["mend_time"])?$user_setmeal["mend_time"]:'';
					$datas["setmeal_name"] = isset($setmeal_type["name"])?$setmeal_type["name"]:'';
					return $datas;
				});
		$data['page'] = $page;
		$data['size'] = $size;

		$data['count'] = $res->count();
		$data['item'] = $res->page($page,$size)->select();
		return $data;
	}

	/**
	 * 设置应用权限给用户
	 * @param $wid 账户id
	 * @return 
	 */
	static public function add($id){
		$code = self::getMainfest('gptcms')['code'];
		$has = Db::table('kt_base_user_openapp')->where(['wid'=>$id,'code'=>$code])->find();
		if($has) return '已设置';
		$uid = Db::table('kt_base_user')->where('id',$id)->value('agid');
		Db::table('kt_base_user_openapp')->insert(['wid'=>$id,'code'=>$code,'uid'=>$uid,'create_time'=>date(['Y-m-d H:i:s'])]);
		return 'ok';
	}

	/**
	* 获取当前用户的套餐
	*/
	static public function getAppAuth($id){
		$res = Db::table('kt_gptcms_user_setmeal')->where('wid',$id)->find();
		$res["mend_time"] = isset($res["mend_time"])?strtotime($res["mend_time"]):time();
		return $res;
	}

	/**
     * 获取用户套餐级别
	 */
	static public function getUserSetmeal($id){
		$res = Db::table("kt_gptcms_user_setmeal")->where(["wid"=>$id])->find();
 		return $res;
	}

	/**
 	* 套餐开通
 	*/
 	static public function updUserSetmeal($id,$mend_time,$setmeal_level){
 		$res = Db::table("kt_gptcms_user_setmeal")->where(["wid"=>$id])->find();
 		if($res) $data["id"] = $res["id"];
 		$data["wid"] = $id;
 		$data["level"] = $setmeal_level;
 		$data["mend_time"] = $mend_time;
 		$data["create_time"] = date("Y-m-d H:i:s",time());

 		$save = Db::table("kt_gptcms_user_setmeal")->save($data);
 		return $save;
 	}
}

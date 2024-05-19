<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;
use app\gptcms\model\Wxopenapi;
use app\gptcms\model\Fast;

class ActionEvent extends BaseUser{
	public function index(){
		$wid = Session::get("wid");
		$list = Db::table("kt_gptcms_menu")
				->where(["wid"=>$wid,"status"=>1,"type"=>1])
				->order("order","asc")
				->filter(function($data){
					$data["menu"] = Db::table("kt_gptcms_menu")->where(["pid"=>$data["id"],"status"=>1])->order("order","DESC")->select()->toArray();

					return $data;
				})
				->select()
				->toArray();

		return success("获取成功",$list);
	}

	public function add(){
		$wid = Session::get("wid");
		$data["wid"] = $wid;
		$id = $this->req->param("id");
		$data["pid"] = $this->req->param("pid")?:0;
		$data["type"] = $this->req->param("type")?:1;
		$count = Db::table("kt_gptcms_menu")->where(["wid"=>$wid,"status"=>1,"type"=>$data["type"]])->count();
		if($data["type"] == 1 && $count >= 3 && !$id)return error("一级菜单数量超限");
		if($data["type"] == 1 && $count >= 5 && !$id)return error("二级菜单数量超限");
		$data["name"] = $this->req->param("name");
		$data["menu_type"] = $this->req->param("menu_type");
		if($data["menu_type"] == 1){
			$data["keys"] = $this->req->param("keys");
		}else if($data["menu_type"] == 2){
			$data["appid"] = $this->req->param("appid");
			$data["url"] = $this->req->param("url");
			$data["pagepath"] = $this->req->param("pagepath");
		}else if($data["menu_type"] == 3){
			$data["menu_url"] = $this->req->param("menu_url");
		}
		if($id){
			$info = Db::table("kt_gptcms_menu")->where(["id"=>$id])->find();
			$isset = Db::table("kt_gptcms_menu")->where(["name"=>$data["name"],"status"=>1])->where('id','<>',$id)->find();
			if($isset)return error("名称相同");
			Db::table("kt_gptcms_menu")->where(["id"=>$id])->update($data);
		}else{
			$info = [];
			$order = Db::table("kt_gptcms_menu")->where(["wid"=>$wid,"type"=>$data["type"]])->max("order");
			$data["order"] = $order+1;
			$isset = Db::table("kt_gptcms_menu")->where(["name"=>$data["name"],"status"=>1])->find();
			if($isset)return error("名称相同");
			$data["ctime"] = date("Y-m-d H:i:s");
			$id = Db::table("kt_gptcms_menu")->insertGetId($data);
		}
		$menu = $this->assembling($wid);
		$res = Wxopenapi::creaet($wid,$menu);
		if($res["errcode"] != 0){
			if($info)Db::table("kt_gptcms_menu")->where(["id"=>$id])->update($info);
			if(!$info)Db::table("kt_gptcms_menu")->where(["id"=>$id])->delete();
			$code = $this->errors($res["errcode"]);
			return error("操作失败".$code);
		}

		return success("操作成功",$res);
	}
	
	public function errors($code){
	    $data = [
	        "65316" => "该公众号的菜单设置了过多的域名外跳（最多跳转到 3 个域名的链接）",
	        "65309" => "个性化菜单开关处于关闭状态",
	        "65307" => "个性化菜单信息为空",
	        "65306" => "不支持个性化菜单的帐号",
	        "65305" => "个性化菜单数量受限",
	        "65303" => "没有默认菜单，不能创建个性化菜单",
	        "65301" => "不存在此 menuid 对应的个性化菜单",
	        "48005" => "api 禁止删除被自动回复和自定义菜单引用的素材",
	        "46003" => "不存在的菜单数据",
	        "46002" => "不存在的菜单版本",
	        "45064" => "创建菜单包含未关联的小程序",
	        "45010" => "创建菜单个数超过限制",
	        "41007" => "缺少子菜单数据",
	        "40028" => "不合法的自定义菜单使用用户",
	        "40027" => "不合法的子菜单按钮URL长度",
	        "40026" => "不合法的子菜单按钮KEY长度",
	        "40025" => "不合法的子菜单按钮名字长度",
	        "40024" => "不合法的子菜单按钮类型",
	        "40023" => "不合法的子菜单按钮个数",
	        "40022" => "不合法的子菜单级数",
	        "40021" => "不合法的菜单版本号",
	        "40015" => "不合法的菜单类型",
	        "40054" => "不合法的子菜单按钮url域名",
	        "40055" => "不合法的菜单按钮url域名",
	        "41007" => "缺少子菜单数据",
	        "40001" => " access_token 无效"
	    ];
	    
	    return $data[$code];
	}

	public function del(){
		$wid = Session::get("wid");
		$id = $this->req->param("id");
		$info = Db::table("kt_gptcms_menu")->where(["id"=>$id])->find();
		if($info["pid"] == 0){
			$isset = Db::table("kt_gptcms_menu")->where(["pid"=>$id,"status"=>1])->update(["status"=>2]);
		}
		$menu = $this->assembling($wid);
		$res = Wxopenapi::creaet($wid,$menu);
		if($res["errcode"] != 0)return error("操作失败",$res);
		Db::table("kt_gptcms_menu")->where(["id"=>$id])->update(["status"=>2]);

		return success("操作成功",$res);
	}

	public function assembling($wid){
		$all = Db::table("kt_gptcms_menu")->where(["wid"=>$wid,"status"=>1,"type"=>1])->order("order","asc")->select()->toArray();
		foreach ($all as $key => $info) {
			$data = [];
			$menu = Db::table("kt_gptcms_menu")->where(["wid"=>$wid,"status"=>1,"pid"=>$info["id"]])->order("order","DESC")->select()->toArray();
			$data["name"] = $info["name"];
			if(!$menu){
				if($info["menu_type"] == 1){
					$data["type"] = "click";
					$data["key"] = $info["keys"];
				}
				if($info["menu_type"] == 2){
					$data["type"] = "miniprogram";
					$data["appid"] = $info["appid"];
					$data["url"] = $info["url"];
					$data["pagepath"] = $info["pagepath"];
				}
				if($info["menu_type"] == 3){
					$data["type"] = "view";
					$data["url"] = $info["menu_url"];
				}
			}else{
				foreach ($menu as $value) {
					$menu_list = [];
					$menu_list["name"] = $value["name"];
					if($value["menu_type"] == 1){
						$menu_list["type"] = "click";
						$menu_list["key"] = $value["keys"];
					}
					if($value["menu_type"] == 2){
						$menu_list["type"] = "miniprogram";
						$menu_list["appid"] = $value["appid"];
						$menu_list["url"] = $value["url"];
						$menu_list["pagepath"] = $value["pagepath"];
					}
					if($value["menu_type"] == 3){
						$menu_list["type"] = "view";
						$menu_list["url"] = $value["menu_url"];
					}
					$data["sub_button"][] = $menu_list;
				}
			}
			$button[] = $data;
		}

		return $button;
	}

	/*
	* 关键词
	*/
	public function addKeyword(){
		$wid = Session::get("wid");
		$data = $this->req->param();
		$data["wid"] = $wid;
		if(!$data["name"])return success("请输入规则名称");
		if(!$data["content"])return error("请输入回复内容");
		if(!empty($data["id"])){
			$isset = Db::table("kt_gptcms_gzh_keyword")->where(["wid"=>$wid,"name"=>$data["name"]])->where("id","<>",$data["id"])->find();
			if($isset)return error("规则名称重复");
			$res = Db::table("kt_gptcms_gzh_keyword")->json(["content"])->save($data);
		}else{
			$data["ctime"] = date("Y-m-d H:i:s");
			$isset = Db::table("kt_gptcms_gzh_keyword")->where(["wid"=>$wid,"name"=>$data["name"]])->find();
			if($isset)return error("规则名称重复");
			$res = Db::table("kt_gptcms_gzh_keyword")->json(["content"])->insertGetId($data);
		}

		return success("操作成功",$res);
	}

	public function delKeyword(){
		$id = $this->req->param("id");
		$res = Db::table("kt_gptcms_gzh_keyword")->where(["id"=>$id])->delete();

		return success("操作成功",$res);
	}

	public function keywordList(){
		$page = $this->req->param("page")?:1;
		$size = $this->req->param("size")?:10;
		$name = $this->req->param("name");
		$wid = Session::get("wid");
		$where["wid"] = $wid;
		$res = Db::table("kt_gptcms_gzh_keyword")
				->where($where);
		if($name)$res = $res->whereLike("name","%".$name."%");
		$data["page"] = $page;
		$data["size"] = $size;
		$data["count"] = $res->count();
		$data["item"] = $res->page($page,$size)->json(["content"])->select()->toArray();

		return success("获取成功",$data);
	}

	/*
	* 被关注回复 interest
	* 收到消息回复 type = 2
	*/
	public function addInterest(){
		$wid = Session::get("wid");
		$data = $this->req->param();
		$data["wid"] = $wid;
		if(!$data["content"])return error("请输入回复内容");
		$info = Db::table("kt_gptcms_gzh_interest")->where(["wid"=>$wid,"type"=>$data["type"]])->find();
		if($info)$data["id"] = $info["id"];
		$res = Db::table("kt_gptcms_gzh_interest")->json(["content"])->save($data);

		return success("操作成功",$res);
	}

	public function interestInfo(){
		$wid = Session::get("wid");
		$type = $this->req->param("type")?:1;
		$where["type"] = $type;
		$where["wid"] = $wid;
		$res = Db::table("kt_gptcms_gzh_interest")
				->where($where)
				->json(["content"])
				->find();

		return success("获取成功",$res);
	}

	public function updStatus(){
		$type = $this->req->param("type")?:1;
		$id = $this->req->param("id");
		$status = $this->req->param("status")?:2;
		$res = Db::table("kt_gptcms_gzh_interest")->where(["wid"=>$wid,"type"=>$type])->update(["status"=>$status]);

		return success("获取成功",$res);
	}
}
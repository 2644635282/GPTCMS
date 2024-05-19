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
include "../extend/PHPExcel/php-excel.class.php";

class CardModel extends BaseModel
{
	static public function userecord($req)
    {
        $uid = Session::get("uid");
        $page = $req->param("page/d")?:1;
        $size = $req->param("size/d")?:10;
        $un = $req->param("un");
        $res = Db::table("kt_base_admin_carddetail")
               ->alias("d")
               ->field("d.*,c.uid,c.type,c.type,c.duration,c.duration_type,c.code,c.package_id,c.specs_id,c.remark,u.un")
               ->leftjoin("kt_base_admin_card c","d.pid = c.id")
               ->leftjoin("kt_base_user u","d.user = u.id ")
               ->where('d.status',1)
               ->where('c.uid',$uid);
        if($un) $res->where("u.un","like","%".$un."%");
        $data = [];
        $data["page"] = $page;
        $data["size"] = $size;
        $data["count"] = $res->count();
        $data["item"] = $res->page($page,$size)->order("time","desc")->filter(function($card){
            $card["package_name"] = "";
            $card["code_name"] = "";
            $codeArr = [];
            switch ($card["type"]) {
                case '1':
                    $package = Db::table("kt_base_app_package")->json(["specs","apps"])->find($card["package_id"]);
                    $specs = "";
                    foreach ($package["specs"] as $s) {
                        if($s["id"] == $card["specs_id"]){
                            $specs = $s;
                            break;
                        }
                    }
                    $card["package_name"] =  $package["name"];
                    $card["duration"] =  $specs["duration"];
                    $card["duration_type"] =  $specs["duration_type"];
                    $codeArr = $package["apps"];
                    break;
                case '2':
                    $codeArr = [$card["code"]];
                    break;      
            }
            $appsName =  Db::table("kt_base_market_app")->where("code","in",$codeArr)->column("name");
            $card["code_name"] = implode(",", $appsName);
            return $card;
        })->select();
        return success("卡密兑换记录",$data);
    }
    static public function list($req){
        $uid = Session::get("uid");
        $remark = $req->param("remark");
        $page = $req->param("page")?:1;
        $size = $req->param("size")?:10;
        $where["uid"] = $uid;
        $list = Db::table("kt_base_admin_card")
                ->where($where);
        if($remark)$list = $list->whereLike("remark",'%'.$remark.'%');
        $data["page"] = $page;
        $data["size"] = $size;
        $data["count"] = $list->count();
        $data["item"] = $list->page($page,$size)
                ->order("ctime","desc")
                ->filter(function($card){
                    $card["use_size"] = Db::table("kt_base_admin_carddetail")->where(["pid"=>$card["id"],"status"=>1])->count();
                    $card["package_name"] = "";
                    $card["code_name"] = "";
                    $codeArr = [];
                    switch ($card["type"]) {
                        case '1':
                            $package = Db::table("kt_base_app_package")->json(["specs","apps"])->find($card["package_id"]);
                            $specs = "";
                            foreach ($package["specs"] as $s) {
                                if($s["id"] == $card["specs_id"]){
                                    $specs = $s;
                                    break;
                                }
                            }
                            $card["package_name"] =  $package["name"];
                            $card["duration"] =  $specs["duration"];
                            $card["duration_type"] =  $specs["duration_type"];
                            $codeArr = $package["apps"];
                            break;
                        case '2':
                            $codeArr = [$card["code"]];
                            break;      
                    }
                    $appsName =  Db::table("kt_base_market_app")->where("code","in",$codeArr)->column("name");
                    $card["code_name"] = implode(",", $appsName);
                    return $card;
                })
                ->select()
                ->toArray();

        return success('获取成功',$data);
    }
    static public function del($req){
        $id = $req->param("id");
        Db::table("kt_base_admin_carddetail")->where(["pid"=>$id])->delete();
        $res = Db::table("kt_base_admin_card")->where(["id"=>$id])->delete();
        if($res)return success("操作成功",$res);
        return error("操作失败");
    }
    static public function detail($req){
        $id = $req->param("id");
        $page = $req->param("page")?:1;
        $size = $req->param("size")?:10;
        $card = Db::table("kt_base_admin_card")->where(["id"=>$id])->find();
        $card["package_name"] = "";
        switch ($card["type"]) {
            case '1':
                $package = Db::table("kt_base_app_package")->json(["specs","apps"])->find($card["package_id"]);
                $specs = "";
                foreach ($package["specs"] as $s) {
                    if($s["id"] == $card["specs_id"]){
                        $specs = $s;
                        break;
                    }
                }
                $card["package_name"] =  $package["name"];
                $card["duration"] =  $specs["duration"];
                $card["duration_type"] =  $specs["duration_type"];
                $codeArr = $package["apps"];
                break;
            case '2':
                $codeArr = [$card["code"]];
                break;      
        }
        $appsName =  Db::table("kt_base_market_app")->where("code","in",$codeArr)->column("name");
        $card["code_name"] = implode(",", $appsName);
        $where["pid"] = $id;
        $list = Db::table("kt_base_admin_carddetail")
                ->where($where);
        $data["page"] = $page;
        $data["size"] = $size;
        $data["count"] = $list->count();
        $data["item"] = $list->page(intval($page),intval($size))
                ->filter(function($detail) use($card){
                    $detail["type"] = $card["type"];
                    $detail["duration"] = $card["duration"];
                    $detail["package_id"] = $card["package_id"];
                    $detail["specs_id"] = $card["specs_id"];
                    $detail["package_name"] = $card["package_name"];
                    $detail["code_name"] = $card["code_name"];
                    $detail["un"] = "";
                    if($detail["status"] == 1){
                        $user = Db::table("kt_base_user")->where(["id"=>$detail["user"]])->find();
                        $detail["un"] = $user["un"];
                    }

                    return $detail;
                })
                ->select()
                ->toArray();

        return success('获取成功',$data);
    }
    static public function add($req){
        $uid = Session::get('uid');
        $data["uid"] = $uid;
        $data["type"] = $req->param('type') ?: 1;
        $data["package_id"] = $req->param('package_id');
        $data["specs_id"] = $req->param('specs_id');
        $data["duration"] = $req->param('duration');
        $data["duration_type"] = $req->param('duration_type') ?: 1;
        $data["code"] = $req->param('code');
        $data["remark"] = $req->param('remark');
        $data["amount"] = $req->param('amount');
        switch ($data["type"]) {
            case '1':
                if(!$data['package_id']) return error('请选择套餐');
                if(!$data['specs_id']) return error('请选择套餐规格');
                break;
            case '2':
                if(!$data['code']) return error('请选择应用');
                if(!$data['duration']) return error('请输入时长');
                break;
        }
        $data["ctime"] = date("Y-m-d H:i:s");
        $id = Db::table("kt_base_admin_card")->insertGetId($data);
        for($i=0;$i<$data["amount"];$i++){
            $detail = [];
            $detail["uid"] = $uid;
            $detail["pid"] = $id;
            $detail["code"] = self::randString();
            Db::table("kt_base_admin_carddetail")->insertGetId($detail);
        }

        return success('添加成功');
    }
    static public function downexcel($req){
        $id = $req->param("id");
        $status = $req->param("status/d");
        $card = Db::table("kt_base_admin_card")->where("id",$id)->find();
        $card["package_name"] = "";
        $card["code_name"] = "";
        $codeArr = [];
        switch ($card["type"]) {
            case '1':
                $package = Db::table("kt_base_app_package")->json(["specs","apps"])->find($card["package_id"]);
                $specs = "";
                foreach ($package["specs"] as $s) {
                    if($s["id"] == $card["specs_id"]){
                        $specs = $s;
                        break;
                    }
                }
                $card["package_name"] =  $package["name"];
                $card["duration"] =  $specs["duration"];
                $card["duration_type"] =  $specs["duration_type"];
                $codeArr = $package["apps"];
                break;
            case '2':
                $codeArr = [$card["code"]];
                break;      
        }
        $appsName =  Db::table("kt_base_market_app")->where("code","in",$codeArr)->column("name");
        $card["code_name"] = implode(",", $appsName);

        $where["pid"] = $id; 
        $list = Db::table("kt_base_admin_carddetail")->where($where);;
        if($status === 0)   $list->where("status",$status);
        $list = $list->select()->toArray();
        $arr = [
            0 => ['ID', '生成时间', '卡密 ',"类型","套餐名称","应用","时长","使用时间","使用者账号"]
        ];
        $typeName = "套餐";
        if($card["type"] == 2) $typeName = "自定义";
        $dsname = "";
        switch ($card["duration_type"]) {
            case '1':
                $dsname = $card["duration"]."日";
                break;
            case '2':
                $dsname = $card["duration"]."月";
                break;
            case '3':
                $dsname = $card["duration"]."年";
                break;
        }
        $i = 0;
        foreach ($list as $detail){
            $arr[$i+1]["ID"] = $card["id"];
            $arr[$i+1]["生成时间"] = $card["ctime"];
            $arr[$i+1]["卡密"] = $detail["code"];
            $arr[$i+1]["类型"] = $typeName;
            $arr[$i+1]["套餐名称"] = $card["package_name"];
            $arr[$i+1]["应用"] = $card["code_name"];
            $arr[$i+1]["时长"] = $dsname;
            $arr[$i+1]["使用时间"] = "";
            $arr[$i+1]["使用者账号"] = "";
            if($detail["status"] == 1){
                $arr[$i+1]["使用时间"] = $detail["time"];
                $arr[$i+1]["使用者账号"] = Db::table("kt_base_user")->where("id",$detail["user"])->value("un");
            }
            $i++;
        }
        $str = "时间统计";
        $xls = new \Excel_XML('utf-8', false, '数据导出');
        $xls->addArray($arr);
        $xls->generateXML($str.date('Y-m-d H:i:s'),time());
        exit();
    }
    static public function randString(){
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = $code[rand(0,25)].strtoupper(dechex(date('m'))).date('d').substr(microtime(),-5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        for(
            $a = md5( $rand, true ),
            $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
            $d = '',
            $f = 0;
            $f < 8;
            $g = ord( $a[ $f ] ),
            $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
            $f++
        );
        return  $d;
    }
}
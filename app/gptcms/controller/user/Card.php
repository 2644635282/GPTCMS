<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;
include "../extend/PHPExcel/php-excel.class.php";

class Card extends BaseUser{
    public function userecord()
    {
        $wid = Session::get("wid");
        $page = $this->req->param("page/d")?:1;
        $size = $this->req->param("size/d")?:10;
        $nickname = $this->req->param("nickname");
        $res = Db::table("kt_gptcms_card_detail")
               ->alias("d")
               ->field("d.*,c.wid,c.type,c.size,c.size_num,c.remark,u.mobile,u.nickname,u.headimgurl")
               ->leftjoin("kt_gptcms_card c","d.pid = c.id")
               ->leftjoin("kt_gptcms_common_user u","d.user = u.id ")
               ->where('d.status',1)
               ->where('c.wid',$wid);
        if($nickname) $res->where("u.nickname","like","%".$nickname."%");
        $data = [];
        $data["page"] = $page;
        $data["size"] = $size;
        $data["count"] = $res->count();
        $data["item"] = $res->page($page,$size)->order("time","desc")->select();
        return success("卡密兑换记录",$data);
    }
    public function list(){
    	$wid = Session::get("wid");
    	$remark = $this->req->param("remark");
    	$page = $this->req->param("page")?:1;
    	$size = $this->req->param("size")?:10;
    	$where["wid"] = $wid;
    	$list = Db::table("kt_gptcms_card")
    			->where($where);
    	if($remark)$list = $list->whereLike("remark",'%'.$remark.'%');
    	$data["page"] = $page;
    	$data["size"] = $size;
    	$data["count"] = $list->count();
    	$data["item"] = $list->page($page,$size)
                ->order("ctime","desc")
    			->filter(function($card){
    				$card["use_size"] = Db::table("kt_gptcms_card_detail")->where(["pid"=>$card["id"],"status"=>1])->count();

    				return $card;
    			})
    			->select()
    			->toArray();

    	return success('获取成功',$data);
    }

    public function del(){
    	$id = $this->req->param("id");
    	Db::table("kt_gptcms_card_detail")->where(["pid"=>$id])->delete();
    	$res = Db::table("kt_gptcms_card")->where(["id"=>$id])->delete();
    	if($res)return success("操作成功",$res);
    	return error("操作失败");
    }

    public function downexcel(){
    	$id = $this->req->param("id");
    	$status = $this->req->param("status/d");
    	$card = Db::table("kt_gptcms_card")->where(["id"=>$id])->find();
    	$where["pid"] = $id; 
        $list = Db::table("kt_gptcms_card_detail")->where($where);;
    	if($status === 0)	$list->where("status",$status);
    	$list = $list->select()->toArray();
    	if($card["type"] == 1){
    		$type = "对话次数";
    		$size = "条";
    	}
    	if($card["type"] == 2){
    		$type = "绘画次数";
    		$size = "张";
    	}
    	if($card["type"] == 3){
    		$type = "vip时长";
            switch ($card['size']) {
                case 1:
                    $size = "天";
                    break;
                case 3:
                    $size = "个月";
                    break;
                case 5:
                    $size = "年";
                    break;
                
                default:
                    $size = "个月";
                    break;
            }
    		
    	}
		$arr = [
            0 => ['ID', '生成时间', '卡密	','类型',"面额","使用时间","使用者ID"]
        ];
        $i = 0;
    	foreach ($list as $detail){
	        $arr[$i+1]["ID"] = $card["id"];
			$arr[$i+1]["生成时间"] = $card["ctime"];
			$arr[$i+1]["卡密"] = $detail["code"];
			$arr[$i+1]["类型"] = $type;
			$arr[$i+1]["面额"] = $card["size_num"].$size;
			$arr[$i+1]["使用时间"] = "";
			$arr[$i+1]["使用者ID"] = "";
			if($detail["status"] == 1){
				$arr[$i+1]["使用时间"] = $detail["time"];
				$arr[$i+1]["使用者ID"] = $detail["user"];
			}
            $i++;
    	}
    	$str = "时间统计";
        $xls = new \Excel_XML('utf-8', false, '数据导出');
        $xls->addArray($arr);
        $xls->generateXML($str.date('Y-m-d H:i:s'),time());
        exit();
    }

    public function detail(){
    	$id = $this->req->param("id");
    	$page = $this->req->param("page")?:1;
    	$size = $this->req->param("size")?:10;
    	$card = Db::table("kt_gptcms_card")->where(["id"=>$id])->find();
    	$where["pid"] = $id;
    	$list = Db::table("kt_gptcms_card_detail")
    			->where($where);
    	$data["page"] = $page;
    	$data["size"] = $size;
    	$data["count"] = $list->count();
    	$data["item"] = $list->page(intval($page),intval($size))
    			->filter(function($detail) use($card){
    				$detail["type"] = $card["type"];
    				$detail["size"] = $card["size"];
    				if($detail["status"] == 1){
    					$user = Db::table("kt_gptcms_common_user")->where(["id"=>$detail["user"]])->find();
    					$detail["nickname"] = $user["nickname"];
    					$detail["headimgurl"] = $user["headimgurl"];
    				}

    				return $detail;
    			})
    			->select()
    			->toArray();

    	return success('获取成功',$data);
    }

    public function add(){
    	$wid = Session::get('wid');
    	$data["wid"] = $wid;
    	$data["type"] = $this->req->param('type');
    	$data["size"] = $this->req->param('size');
    	$data["remark"] = $this->req->param('remark');
        $data["amount"] = $this->req->param('amount');
    	$data["size_num"] = $this->req->param('size_num/d');
    	if(!$data['size_num']) return error('请输入卡额度');
    	// if(!$data['remark']) return error('请输入卡备注');
        if(!$data['amount']) return error('请输入卡密数');
    	if($data["type"] == 3 && !$data['size']) return error('请输入单位类型');
    	$data["ctime"] = date("Y-m-d H:i:s");
    	$id = Db::table("kt_gptcms_card")->insertGetId($data);
    	for($i=0;$i<$data["amount"];$i++){
    		$detail = [];
    		$detail["wid"] = $wid;
    		$detail["pid"] = $id;
    		$detail["code"] = $this->randString();
    		Db::table("kt_gptcms_card_detail")->insertGetId($detail);
    	}

    	return success('保存成功');
    }

    public function randString(){
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
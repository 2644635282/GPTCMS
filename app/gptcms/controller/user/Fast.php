<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;
use app\gptcms\model\Wxopenapi;
use app\gptcms\model\SpeedTranscriptionController;
use app\gptcms\model\TtsModel;
use app\gptcms\model\Fast as Fasts;
include "../extend/PHPExcel/PHPExcel.php";
include "../extend/PHPExcel/PHPExcel/IOFactory.php";
include "../extend/PHPExcel/php-excel.class.php";
include "../extend/PHPExcel/PHPExcel/Reader/Excel5.php";

class Fast extends BaseUser{
	/*
	* 绑定 kbid
	*/
	public function set(){
		$wid = Session::get("wid");
		$data["wid"] = $wid;
		$data["kbid"] = $this->req->param("kbid");
		if(!$data["kbid"])return error("请配置知识库id");
		// $data["mode"] = "index";
		// $data["tag"] = $this->req->param("tag");
		// $data["tag"] = json_encode(explode(" ", $data["tag"]),320);
		// $data["name"] = $this->req->param("name");
		// $data["avatar"] = $this->req->param("avatar");
		$info = Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid])->find();
		if($info)$data["ctime"] = date("Y-m-d H:i:s");
		$res = Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid])->save($data);

		return success("操作成功",$res);
	}


	/*
	* 添加内容
	*/
	public function add(){
		$wid = Session::get("wid");
		$config = Db::table("kt_gptcms_gpt_config")->field('fastgpt')->json(['fastgpt'])->where('wid',$wid)->find();
		if(empty($config["fastgpt"]["apikey"]))return error("请先配置FastGpt");
		$info = Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid])->json(["data"])->find();
		if(empty($info["kbid"]))return error("请先配置知识库id");
		$q = $this->req->param("q");//匹配的知识点
		$a = $this->req->param("a");//补充知识
		if(!$q || !$a)return error("参数错误");
		$isset = Db::table("kt_gptcms_knowledge_list")->where(["wid"=>$wid,"q"=>$q,"a"=>$a])->find();
		if($isset)return error("已配置相同内容");
		$kbid = $info["kbid"];
		$data["kbId"] = $kbid;
   		$data["mode"] = "index";
   		$data["model"] = "text-embedding-ada-002";
   		$data["data"] = [
   			[
	   			"a"=>$a,
	   			"q"=>$q
   			]
   		];
   		$fast = new fasts($config["fastgpt"]["apikey"],"");
   		$res = $fast->knowledgeRequest($data);
   		if($res["code"] != 200)return error($res["message"]);
   		$save["wid"] = $wid;
   		$save["q"] = $q;
   		$save["a"] = $a;
   		$save["kbid"] = $kbid;
   		$save["ctime"] = date("Y-m-d H:i:s");
   		Db::table("kt_gptcms_knowledge_list")->insert($save);
		// if(!empty($info["data"])){
		// 	foreach ($info["data"] as $value) {
		// 		if(!array_diff_assoc($value,$message))return error("内容已存在");
		// 	}
		// }
		// $data["kbId"] = $kbid;
  //  		$data["mode"] = "index";
  //  		$data["data"] = [$message];
  //  		$fast = new fasts($config["fastgpt"]["apikey"],"");
  //  		$res = $fast->knowledgeRequest($data);
  //  		if($res["code"] != 200)return error($res["message"]);
  //  		$message["ctime"] = date("Y-m-d H:i:s");
  //  		$save["wid"] = $wid;
  //  		$save["kbid"] = $kbid;
  //  		$save["data"] = json_encode($message,320);
  //  		if($info)$save["id"] = $info["id"];
		// Db::table("kt_gptcms_knowledge")->save($save);

		return success("操作成功",$res);
	}

	/*
	* 详情
	*/
	public function info(){
		$wid = Session::get("wid");
		$info = Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid])->find();
		if(!$info){
			$data["wid"] = $wid;
			Db::table("kt_gptcms_knowledge")->insert($data);
			$info = Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid])->find();
		}
		// $kbid = $this->req->param("kbid");
		// $word = $this->req->param("word");
		// $info = Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid,"kbid"=>$kbid])->json(["data","tag"])->find();
		// if($word)$info = Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid,"kbid"=>$kbid])->where('data', 'like', '%'.$word.'%')->json(["data","tag"])->find();

		return success("获取成功",$info);
	}


	/*
	* 列表
	*/
	public function index(){
		$wid = Session::get("wid");
		$page = $this->req->param("page")?:1;
		$size = $this->req->param("size")?:10;
		$word = $this->req->param("word");
		$info = Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid])->json(["data"])->find();
		if(!$info){
			$data["count"] = 0;
			$data["item"] = [];
			$data["wid"] = $wid;
			$data["page"] = $page;
			$data["size"] = $size;
		}else{
			$res = Db::table("kt_gptcms_knowledge_list")
					->where(["wid"=>$wid,"kbid"=>$info["kbid"]]);
			if($word)$res = $res->where('a|q', 'like', '%'.$word.'%');
			$data["count"] = $res->count();
			$data["item"] = $res->page($page,$size)
					->field("kbid,a,q,ctime")
					->select()
					->toArray();
			$data["wid"] = $wid;
			$data["page"] = $page;
			$data["size"] = $size;
		}

		return success("获取成功",$data);
	}


	/*
	* 导入excl形式
	*/
	public function addAll(){
        $url = $this->req->param("url");
        $kbid = $this->req->param("kbid");
        $wid = Session::get("wid");
		$info = Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid,"kbid"=>$kbid])->json(["data"])->find();
		$config = Db::table("kt_gptcms_gpt_config")->field('fastgpt')->json(['fastgpt'])->where('wid',$wid)->find();
        $url = str_replace($this->req->domain(),".",$url);
        $objReader = new \PHPExcel_Reader_Excel2007();
	    $objPHPExcel = $objReader->load($url);
	    $sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow(); // 取得总行数
		for($j=6;$j<=$highestRow;$j++) {
			$q = $objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue();//获取A列的值
			$a = $objPHPExcel->getActiveSheet()->getCell("B".$j)->getValue();//获取B列的值;
			if($q){
				$arr["q"] = $q;
				$arr["a"] = $a;
				$arrs[] = $arr;
				$info["data"][] = $arr;
			}
		}
		if(!$arrs)return error("未识别到文件内容");
		$data["kbId"] = $kbid;
   		$data["mode"] = "index";
   		$data["model"] = "text-embedding-ada-002";
   		$data["data"] = $arrs;
   		$fast = new fasts($config["fastgpt"]["apikey"],"");
   		$res = $fast->knowledgeRequest($data);
   		if($res["code"] != 200)return error($res["message"]);
		Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid,"kbid"=>$kbid])->save(["data"=>json_encode($info["data"],320)]);

		return success("操作成功",$res);
	}

	/*
	* 导出excl形式
	*/
	public function downexcel(){
        $kbid = $this->req->param("kbid");
        $wid = $this->req->param("wid");
		$info = Db::table("kt_gptcms_knowledge")->where(["wid"=>$wid,"kbid"=>$kbid])->json(["data"])->find();
        $arr = [
            0 => ['匹配的知识点', '补充知识',"kbid","规则名称"]
        ];
		for ($i=0;$i<count($info["data"]);$i++){
			$arr[$i+1]["匹配的知识点"] = $info["data"][$i]["q"];
			$arr[$i+1]["补充知识"] = $info["data"][$i]["a"];
			$arr[$i+1]["kbid"] = $kbid;
			$arr[$i+1]["规则名称"] = $info["name"];
		}
		$str = "知识库-数据导出";
        $xls = new \Excel_XML('utf-8', false, '数据导出');
        $xls->addArray($arr);
        $xls->generateXML($str.date('Y-m-d H:i:s'),time());
        exit();
	}
}
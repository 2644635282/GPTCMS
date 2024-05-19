<?php 
namespace app\gptcms\model;
use think\facade\Db;
use think\facade\Session;


class CommonModel
{
	//获取消耗条数 
	// $type  chat(对话)  paint 绘画
	// $model 模型
 	static public function getExpend($type,$model)
	{
		$expend = 1;
		$wid = Session::get("wid");
		switch ($type) {
			case 'chat':
				$res =  Db::table('kt_gptcms_chatmodel_set')->where('wid',$wid)->json(["gpt35","gpt4","linkerai","api2d35","api2d4","wxyy","xfxh","fastgpt","chatglm","tyqw","azure","minimax","txhy","kw","wxyy4","glm4"])->find();
				if($res && isset($res[$model]) && $res[$model]) $expend = $res[$model]['expend'] ?? 1;
				break;
			case 'paint':
				$res = Db::table('kt_gptcms_paintmodel_set')->where('wid',$wid)->json(["sd","yjai","gpt35","api2d35","replicate","linkerai_mj","apishop"])->find();
				if($res && isset($res[$model]) && $res[$model]) $expend = $res[$model]['expend'] ?: 1;
				break;
		}
		
		return $expend ?: 1;
	}
}
<?php 
namespace app\gptcms\model;
use think\facade\Db;
use think\facade\Session;
use Ktadmin\Baidu\Ai as BaiduAi;


class SecurityModel
{
	//$text 检测内容   $type 1提问内容   2回复内容
	public static function check($text,$type)
	{
		switch ($type) {
			case 1:
				return self::questionCheck($text);
				break;
			case 2:
				return self::replyCheck($text);
				break;
		}

		return ["code"=>0];
	}

	public static function questionCheck($text)
	{
		$wid = Session::get('wid');
		$sys = Self::questionSistemCheck($text);
		if($sys["code"] == 1 || $sys["text"] != $text) return $sys;
		$setinfo = Db::table("kt_gptcms_content_security")->where('wid',$wid)->find();
		if(!$setinfo || !$setinfo["question_baiduai"]) return ["code"=>0,"text"=>$text];
		$config = self::getBaiduaiConfig($wid);
		
		if(!$config || !$config['apikey'] || !$config['secretkey']) return ["code"=>0,"text"=>$text];;
		$ai = new BaiduAi($config['apikey'],$config['secretkey']);
		$textCensor = json_decode($ai->textCensor([
			"text"=>$text
		]),true);
		
		if(isset($textCensor['conclusion']) && $textCensor['conclusion'] == "不合规"){
			return ["code"=>1,"text"=>"您的提问命中系统敏感词库，请重新提问。输入输出内容已接入AI自动审核，恶意提问或引导AI输出违规内容，将会被封号。"];
		} 

		return ["code"=>0,"text"=>$text];
	}	

	public static function questionSistemCheck($text)
	{
		$wid = Session::get('wid');
		$user = Db::table("kt_base_user")->find($wid);
		$set = Db::table("kt_base_content_security")->where('uid',$user['agid'])->find();
		if(!$set || !$set["question_system"]) return ["code"=>0,"text"=>$text];
		$textCheck = self::textCheck($text);
		if(!$textCheck) return ["code"=>0,"text"=>$text];
		switch ($set["question_deal"]) {
			case 1:
				return ["code"=>1,"text"=>$set["question_reply"]];
				break;
			case 2:
			    $wordreplace = str_replace($textCheck[0], "***", $text);
				return ["code"=>0,"text"=>$wordreplace];
				break;
		}
		return ["code"=>0,"text"=>$text];
	}

	static public function textCheck($text)
	{
		$path = root_path().'app/base/review.txt';
		$lexicon = array_filter(explode(',', file_get_contents($path)));
		if(!$lexicon) return '';
		$lexiconArr = array_chunk($lexicon, 1000);
		foreach ($lexiconArr as $l) {
			if(preg_match_all("/".implode("|", $l)."/", $text,$ma)) {
				return $ma;
			}
		}
		
		return '';
	}
	private static function getBaiduaiConfig($wid)
	{
		$config = Db::table('kt_gptcms_baiduai_config')->field('apikey,secretkey,appid')->where('wid',$wid)->find();
		if(!$config || !$config['apikey'] || !$config['secretkey']){
			$user = Db::table("kt_base_user")->find($wid);
			$config = Db::table('kt_base_baiduai_config')->field('apikey,secretkey,appid')->where('uid',$user['agid'])->find();
		}
		return $config;
	}
}
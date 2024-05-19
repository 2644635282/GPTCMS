<?php 
namespace app\gptcms\model;
use think\facade\Db;
use think\facade\Session;

class Security 
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

		return false;
	}

	public static function questionCheck($text)
	{
		$wid = Session::get('wid');
		$setinfo = Db::table("kt_gptcms_content_security")->where('wid',$wid)->find();
		if(!$setinfo || !$setinfo["question_baiduai"]) return '';
		
	}	
}
<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;

use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cache;

class Welcome extends BaseUser
{
	public function info()
    {
        $wid = Session::get('wid');
        $type = $this->req->param('type') ?: 1;
        $welcome = Db::table('kt_gptcms_welcome')->where('wid',$wid)->where("type",$type)->find();
        $content = $welcome["content"] ?? "";
        return success('欢迎语',$content);
    }
    public function save()
    {
        $wid = Session::get('wid');
        $content = $this->req->param('content');
        if(!$content)  return error("请输入内容");
        $type = $this->req->param('type/d');
        if(!$type)  return error("类型错误");
        $data = [
            'content' => $content,
            'u_time' => date("Y-m-d H:i:s")
        ];
        $id = Db::table('kt_gptcms_welcome')->where('wid',$wid)->where("type",$type)->value("id");
        if($id){
        	$data['id'] = $id;
        } else {
        	$data['c_time'] = date("Y-m-d H:i:s");
        	$data['wid'] = $wid;
        }
        Db::table('kt_gptcms_welcome')->save($data);
        return success("保存成功");
    }
}
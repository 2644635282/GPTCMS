<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use app\gptcms\model\CommonModel;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;

class Welcome extends BaseApi
{
	public function info()
    {
        $wid = Session::get('wid');
        $type = $this->req->param('type') ?: 1;
        $welcome = Db::table('kt_gptcms_welcome')->where('wid',$wid)->where("type",$type)->find();
        $content = $welcome["content"] ?? "";
        return success('欢迎语',$content);
    }
}
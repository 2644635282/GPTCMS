<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Session;

class Hot extends BaseApi
{
    public function paint()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_hotpaint')->order('sort','desc')->where('wid',$wid)->select();
        return success('热门',$res);
    }
	public function list()
    {
        $wid = Session::get('wid');
        $classify_id = $this->req->param('classify_id/d');
        $res = Db::table('kt_gptcms_hot')->order('sort','desc')->where('wid',$wid)->select();
        return success('热门',$res);
    }
    public function classify()
    {
        $wid = Session::get('wid');
        $classify_limit = $this->req->param("classify_limit/d");
        $son_limit = $this->req->param("son_limit/d");
        $res = Db::table('kt_gptcms_hot_classify')
               ->field("id,title,icon,sort")
               ->order('sort','desc')
               ->where('wid',$wid);
        if($classify_limit) $res->limit($classify_limit);
        $res =  $res->filter(function($r)use($son_limit){
                 $son = Db::table('kt_gptcms_hot')->field('id,content,sort,classify_id')->where('classify_id',$r['id'])->order('sort','desc');
                 if($son_limit) $son->limit($son_limit);
                 $r['son'] = $son->select();
                 return $r;
               })
               ->select();
        return success('热门分类',$res);
    }
}
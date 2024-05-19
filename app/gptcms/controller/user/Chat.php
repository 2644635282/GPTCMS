<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;

class Chat extends BaseUser
{
    public function list()
    {
    	$wid = Session::get('wid');
    	$page = $this->req->param('page/d')?:1;
        $size = $this->req->param("size/d")?:10;
        $nickname = $this->req->param('nickname');
        $res = Db::table("kt_gptcms_chat_msg")
               ->alias('c')
               ->field('c.id,c.common_id,c.message,c.response,c.total_tokens,c.c_time,u.nickname')
               ->leftjoin('kt_gptcms_common_user u','c.common_id=u.id')
        	   ->where('c.wid',$wid);

        if($nickname) $res->where('c.common_id|u.nickname','like','%'.$nickname.'%');

    	$data = [];
    	$data['page'] = $page;
    	$data['size'] = $size;
    	$data['count'] = $res->count();
		$data['item'] = $res->page($page,$size)->order('c.c_time','desc')->filter(function($r){
			$r['c_time'] = date("Y-m-d H:i:s",$r['c_time']);
			return $r;
		})->select();

    	return success('会话列表',$data);
    }

    public function delete()
    {
    	$wid = Session::get('wid');
    	$id = $this->req->param('id');
    	if(!$id) return error('请选择会话');
    	$res = Db::table("kt_gptcms_chat_msg")->where('wid',$wid)->where('id',$id)->delete();
    	if(!$res) return error('删除失败');
    	return success('删除成功');
    }

    public function jslist()
    {
        $wid = Session::get('wid');
        $page = $this->req->param('page/d')?:1;
        $size = $this->req->param("size/d")?:10;
        $nickname = $this->req->param('nickname');
        $res = Db::table("kt_gptcms_role_msg")
               ->alias('c')
               ->field('c.id,c.common_id,c.message,c.response,c.total_tokens,c.c_time,u.nickname')
               ->leftjoin('kt_gptcms_common_user u','c.common_id=u.id')
               ->where('c.wid',$wid);

        if($nickname) $res->where('c.common_id|u.nickname','like','%'.$nickname.'%');
        
        $data = [];
        $data['page'] = $page;
        $data['size'] = $size;
        $data['count'] = $res->count();
        $data['item'] = $res->page($page,$size)->order('c.c_time','desc')->filter(function($r){
            $r['c_time'] = date("Y-m-d H:i:s",$r['c_time']);
            return $r;
        })->select();

        return success('会话列表',$data);
    }

    public function jsdelete()
    {
        $wid = Session::get('wid');
        $id = $this->req->param('id');
        if(!$id) return error('请选择会话');
        $res = Db::table("kt_gptcms_role_msg")->where('wid',$wid)->where('id',$id)->delete();
        if(!$res) return error('删除失败');
        return success('删除成功');
    }
    public function czlist()
    {
        $wid = Session::get('wid');
        $page = $this->req->param('page/d')?:1;
        $size = $this->req->param("size/d")?:10;
        $nickname = $this->req->param('nickname');
        $res = Db::table("kt_gptcms_create_msg")
               ->alias('c')
               ->field('c.id,c.common_id,c.message,c.response,c.total_tokens,c.c_time,u.nickname')
               ->leftjoin('kt_gptcms_common_user u','c.common_id=u.id')
               ->where('c.wid',$wid);

        if($nickname) $res->where('c.common_id|u.nickname','like','%'.$nickname.'%');
        
        $data = [];
        $data['page'] = $page;
        $data['size'] = $size;
        $data['count'] = $res->count();
        $data['item'] = $res->page($page,$size)->order('c.c_time','desc')->filter(function($r){
            $r['c_time'] = date("Y-m-d H:i:s",$r['c_time']);
            return $r;
        })->select();

        return success('会话列表',$data);
    }

    public function czdelete()
    {
        $wid = Session::get('wid');
        $id = $this->req->param('id');
        if(!$id) return error('请选择会话');
        $res = Db::table("kt_gptcms_create_msg")->where('wid',$wid)->where('id',$id)->delete();
        if(!$res) return error('删除失败');
        return success('删除成功');
    }

    public function paintlist()
    {
        $wid = Session::get('wid');
        $page = $this->req->param('page/d')?:1;
        $size = $this->req->param("size/d")?:10;
        $nickname = $this->req->param('nickname');
        $res = Db::table("kt_gptcms_paint_msg")
               ->alias('c')
               ->field('c.id,c.common_id,c.message,c.response,c.total_tokens,c.c_time,u.nickname')
               ->leftjoin('kt_gptcms_common_user u','c.common_id=u.id')
               ->where('c.wid',$wid);

        if($nickname) $res->where('c.common_id|u.nickname','like','%'.$nickname.'%');
        
        $data = [];
        $data['page'] = $page;
        $data['size'] = $size;
        $data['count'] = $res->count();
        $data['item'] = $res->page($page,$size)->order('c.c_time','desc')->filter(function($r){
            $r['c_time'] = date("Y-m-d H:i:s",$r['c_time']);
            return $r;
        })->select();

        return success('会话列表',$data);
    }

    public function paintdelete()
    {
        $wid = Session::get('wid');
        $id = $this->req->param('id');
        if(!$id) return error('请选择会话');
        $res = Db::table("kt_gptcms_paint_msg")->where('wid',$wid)->where('id',$id)->delete();
        if(!$res) return error('删除失败');
        return success('删除成功');
    }

}

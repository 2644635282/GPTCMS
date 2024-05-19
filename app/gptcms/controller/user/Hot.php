<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;

use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cache;

class Hot extends BaseUser
{
    public function import()
    {
        $wid = Session::get('wid');
        $has =  Db::table('kt_gptcms_hot_classify')->where('wid',$wid)->find();
        if($has) return error("分类已存在");
        $list = config('hot');
        if($list){
            foreach ($list as  $classify) {
                $classify_id = Db::table('kt_gptcms_hot_classify')->insertGetId([
                  'wid' => $wid,
                  'title' => $classify['title'],
                  'icon' => $this->req->domain().$classify['icon'],
                  'sort' => $classify['sort'],
                  'c_time' => date("Y-m-d H:i:s"),
                  'u_time' => date("Y-m-d H:i:s"),
                ]);
                $hot = array_map(function($r)use($wid,$classify_id){
                        $r['wid'] = $wid;
                        $r['classify_id'] = $classify_id;
                        $r['c_time'] = date("Y-m-d H:i:s");
                        $r['u_time'] = date("Y-m-d H:i:s");
                        return $r;
                    },$classify['son']);
                if($hot) Db::table('kt_gptcms_hot')->insertAll($hot);
            }
        }
        return success('导入成功');
    }
	public function list()
    {
        $wid = Session::get('wid');
        $classify_id = $this->req->param('classify_id/d');
        $page = $this->req->param('page/d') ?: 1;
        $size = $this->req->param('size/d') ?: 10;
        $res = Db::table('kt_gptcms_hot')->where('wid',$wid);
        if($classify_id) $res->where('classify_id',$classify_id);
        $data['page'] = $page;
        $data['size'] = $size;
        $data['count'] = $res->count();
        $data['item'] = $res->page($page,$size)->order('sort','desc')->select();
        return success('热门',$data);
    }
    public function del()
    {
        $wid = Session::get('wid');
        $id = $this->req->param('id/d');
        if(!$id) return error('请选择热门');
        $res = Db::table('kt_gptcms_hot')->where('id',$id)->where('wid',$wid)->delete();
        if($res) return success('删除成功');
        return error("删除失败");
    }
    public function save()
    {
        $wid = Session::get('wid');
        $content = $this->req->param('content');
        if(!$content)  return error("请输入内容");
        $classify_id = $this->req->param('classify_id/d');
        if(!$classify_id)  return error("请选择分类");
        $sort = $this->req->param('sort') ?: 0;
        $data = [
            'sort' => $sort,
            'content' => $content,
            'classify_id' => $classify_id,
            'u_time' => date("Y-m-d H:i:s")
        ];
        $id = $this->req->param('id/d');
        if($id){
        	$data['id'] = $id;
        } else {
        	$data['c_time'] = date("Y-m-d H:i:s");
        	$data['wid'] = $wid;
        }
        Db::table('kt_gptcms_hot')->save($data);
        return success("保存成功");
    }
	public function classify()
    {
        $wid = Session::get('wid');
        $page = $this->req->param('page/d') ?: 1;
        $size = $this->req->param('size/d') ?: 10;
        $res =  Db::table('kt_gptcms_hot_classify')->where('wid',$wid);
        $data['page'] = $page;
        $data['size'] = $size;
        $data['count'] = $res->count();
        $data['item'] = $res->page($page,$size)->order('sort','desc')->select();
        return success('热门分类',$data);
    }
    public function classifyDel()
    {
        $wid = Session::get('wid');
        $id = $this->req->param('id/d');
        if(!$id) return error('请选择分类');
        $res = Db::table('kt_gptcms_hot_classify')->where('id',$id)->where('wid',$wid)->delete();
        if($res){
            Db::table('kt_gptcms_hot')->where('wid',$wid)->where("classify_id",$id)->delete();
            return success('删除成功');
        } 
        return error("删除失败");
    }
    public function classifySave()
    {
        $wid = Session::get('wid');
        $title = $this->req->param('title');
        if(!$title)  return error("请输入分类名称");
        $sort = $this->req->param('sort') ?: 0;
        $icon = $this->req->param('icon');
        if(!$icon)  return error("请输入分类图标");
        $data = [
            'sort' => $sort,
            'title' => $title,
            'icon' => $icon,
            'u_time' => date("Y-m-d H:i:s")
        ];
        $id = $this->req->param('id/d');
        if($id){
        	$data['id'] = $id;
        } else {
        	$data['c_time'] = date("Y-m-d H:i:s");
        	$data['wid'] = $wid;
        }
        Db::table('kt_gptcms_hot_classify')->save($data);
        return success("保存成功");
    }

    public function paint()
    {
        $wid = Session::get('wid');
        $page = $this->req->param('page/d') ?: 1;
        $size = $this->req->param('size/d') ?: 10;
        $res = Db::table('kt_gptcms_hotpaint')->where('wid',$wid);
        $data['page'] = $page;
        $data['size'] = $size;
        $data['count'] = $res->count();
        $data['item'] = $res->page($page,$size)->order('sort','desc')->select();
        return success('热门',$data);
    }
    public function paintDel()
    {
        $wid = Session::get('wid');
        $id = $this->req->param('id/d');
        if(!$id) return error('请选择热门');
        $res = Db::table('kt_gptcms_hotpaint')->where('id',$id)->where('wid',$wid)->delete();
        if($res) return success('删除成功');
        return error("删除失败");
    }
    public function paintSave()
    {
        $wid = Session::get('wid');
        $content = $this->req->param('content');
        if(!$content)  return error("请输入内容");
        $sort = $this->req->param('sort') ?: 0;
        $data = [
            'sort' => $sort,
            'content' => $content,
            'u_time' => date("Y-m-d H:i:s")
        ];
        $id = $this->req->param('id/d');
        if($id){
            $data['id'] = $id;
        } else {
            $data['c_time'] = date("Y-m-d H:i:s");
            $data['wid'] = $wid;
        }
        Db::table('kt_gptcms_hotpaint')->save($data);
        return success("保存成功");
    }
}
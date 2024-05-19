<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;

class Jmodel extends BaseUser
{
	public function list()
    {
    	$wid = Session::get('wid');
    	$page = $this->req->param('page')?:1;
        $size = $this->req->param("size")?:10;
        $classify_id = $this->req->param('classify_id');
        $res = Db::table("kt_gptcms_jmodel")->where('wid',$wid)->field('id,title,tp_url,desc,bz,xh,vip_status,status,c_time,classify_id,content,hint_content,defalut_question,defalut_reply,qzzl');

        if($classify_id) $res->where('classify_id',$classify_id);

    	$data = [];
    	$data['page'] = $page;
    	$data['size'] = $size;
    	$data['count'] = $res->count();
		$data['item'] = $res->page($page,$size)->order('xh','desc')->filter(function($r){
			$r['classify'] = Db::table("kt_gptcms_jmodel_classify")->value('title');
			return $r;
		})->select();

    	return success('模型列表',$data);
    }
    public function delete()
    {
    	$wid = Session::get('wid');
    	$id = $this->req->param('id');
    	if(!$id) return error('请选择模型');
    	$res = Db::table("kt_gptcms_jmodel")->where('wid',$wid)->where('id',$id)->delete();
    	if(!$res) return error('删除错误');
    	return success('删除成功');
    }
    public function importData(){
        $wid = Session::get('wid');
        $models = Db::table("kt_gptcms_jmodel")->where('wid',$wid)->select();
        if(!$models->isEmpty()) return error('已有模型,不可导入默认模型');
        $classify_id =  Db::table("kt_gptcms_jmodel_classify")->where('wid',$wid)->where('title','默认')->value('id');
        if(!$classify_id)  {
            $classify_id = Db::table("kt_gptcms_jmodel_classify")->insertGetId([
                "wid" => $wid,
                "title" => "默认",
                "c_time" => date("Y-m-d H:i:s"),
                "u_time" => date("Y-m-d H:i:s"),
            ]);
        }
        $czdata = config('copywriter.zl');
        $cz = array_map(function($r)use($wid,$classify_id){
            $r['wid'] = $wid;
            $r['tp_url'] = $this->req->domain().$r['tp_url'];
            $r['classify_id'] = $classify_id;
            $r['c_time'] = date("Y-m-d H:i:s");
            $r['u_time'] = date("Y-m-d H:i:s");
            return $r;
        }, $czdata);

        $res = Db::table("kt_gptcms_jmodel")->insertAll($cz);
        if($res) return success("导入成功");
        return error("导入失败");
    }
    public function save()
    {
    	$wid = Session::get('wid');
    	$id = $this->req->param('id');
    	$data = [];
    	$data['wid'] = $wid;
    	$data['title'] = $this->req->param('title');
    	$data['xh'] = $this->req->param('xh',0);;
    	if(!$data['title']) return error('请输入名称');
    	$data['tp_url'] = $this->req->param('tp_url');
    	$data['desc'] = $this->req->param('desc');
    	if(!$data['desc']) return error('请输入描述');
    	$data['classify_id'] = $this->req->param('classify_id');
    	if(!$data['classify_id']) return error('请选择类别');
    	$data['content'] = $this->req->param('content');
    	if(!$data['content']) return error('请输入内容');
    	$data['hint_content'] = $this->req->param('hint_content');
    	$data['defalut_question'] = $this->req->param('defalut_question');
        $data['defalut_reply'] = $this->req->param('defalut_reply');
    	$data['qzzl'] = $this->req->param('qzzl');
    	if($id){
    		$data['id'] = $id;

    	}else{
    		$data['c_time'] = date("Y-m-d H:i:s");
    	}	
    	$data['u_time'] = date("Y-m-d H:i:s");
    	$res = Db::table("kt_gptcms_jmodel")->save($data);
    	return success('保存成功');
    }
    public function updateStatus()
    {   
        $wid = Session::get('wid');
        $id = $this->req->param('id');
        $status = $this->req->param('status');
        Db::table('kt_gptcms_jmodel')->where('id',$id)->where('wid',$wid)->update([
            'status' => $status,
            'u_time' => date("Y-m-d H:i:s"),

        ]);
        return success('操作成功');
    }
    public function updateVipStatus()
    {
        $wid = Session::get('wid');
        $id = $this->req->param('id');
        $vip_status = $this->req->param('vip_status');
        Db::table('kt_gptcms_jmodel')->where('id',$id)->where('wid',$wid)->update([
            'vip_status' => $vip_status,
            'u_time' => date("Y-m-d H:i:s"),

        ]);
        return success('操作成功');
    }
    public function detail()
    {
    	$wid = Session::get('wid');
    	$id = $this->req->param('id');
    	if(!$id) return error('请选择模型');
    	$res = Db::table("kt_gptcms_jmodel")->field('id,title,tp_url,desc,bz,xh,vip_status,status,c_time,classify_id,content,hint_content,defalut_question,defalut_reply,qzzl')->where('wid',$wid)->find($id);

    	return success('模型详情',$res);
    }

    public function classify()
    {
    	$wid = Session::get('wid');
    	$res = Db::table("kt_gptcms_jmodel_classify")->field('id,title,sort')->where('wid',$wid)->order('sort','desc')->select();
    	return success('分类',$res);
    }
    public function classifyDelete()
    {
    	$wid = Session::get('wid');
    	$id = $this->req->param('id');
    	if(!$id) return error('请选择分类');
    	$res = Db::table("kt_gptcms_jmodel_classify")->where('wid',$wid)->where('id',$id)->delete();
    	if(!$res) return error('删除错误');
    	return success('删除成功');
    }
    public function classifySave()
    {
    	$wid = Session::get('wid');
    	$id = $this->req->param('id');
    	$data = [];
    	$data['title'] = $this->req->param('title');
    	if(!$data['title']) return error('请输入名称');
    	$data['sort'] = $this->req->param('sort',0);
    	$data['wid'] = $wid;
    	if($id){
    		$data['id'] = $id;
    	}else{
    		$data['c_time'] = date("Y-m-d H:i:s");
    	}
    	$data['u_time'] = date("Y-m-d H:i:s");
    	$res = Db::table("kt_gptcms_jmodel_classify")->save($data);
    	return success('保存成功');
    }

}
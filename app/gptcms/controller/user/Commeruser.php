<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;

class Commeruser extends BaseUser
{

    public function list()
    {
    	$wid = Session::get('wid');
    	$page = $this->req->param('page')?:1;
        $size = $this->req->param("size")?:10;
        $keyword = $this->req->param('keyword');
        $status = $this->req->param('status/d');

        $res = Db::table("kt_gptcms_common_user")->field('id,type,residue_degree,mobile,nickname,headimgurl,account,vip_expire,status,bz,c_time')->where('wid',$wid);
        if($keyword){
        	$res->where(function ($query) use($keyword) { $query->whereOr([
        		['nickname', 'like', '%'.$keyword.'%'],
	            ['mobile', 'like', '%'.$keyword.'%'],
	            ['id', 'like', '%'.$keyword.'%']
        	]); });
        }
        if(is_int($status)) $res->where('status',$status);
    	$data = [];
    	$data['page'] = $page;
    	$data['size'] = $size;
    	$data['count'] = $res->count();
		$data['item'] = $res->page($page,$size)->order('c_time','desc')->filter(function($r){
			
			return $r;
		})->select();

    	return success('会员列表',$data);
    }

    public function update()
    {   
        $wid = Session::get('wid');
        $id = $this->req->param('id');
        if(!$id) return error('参数错误');
        $account = $this->req->param('account');
        if(!$account) return error('请输入账号');
        $password = $this->req->param('password');
        $user = Db::table('kt_gptcms_common_user')->find($id);
        $residue_degree = $this->req->param('residue_degree',0);
        $bz = $this->req->param('bz');
        $data = [
            'account'=> $account,
            'residue_degree'=> $residue_degree,
            'bz'=> $bz,
            'u_time' => date("Y-m-d H:i:s"),

        ];
        if($password)  $data['password'] = ktEncrypt($password); 
        Db::table('kt_gptcms_common_user')->where('id',$id)->where('wid',$wid)->update($data);
        return success('操作成功');
    }
    public function updateStatus()
    {   
        $wid = Session::get('wid');
        $id = $this->req->param('id');
        $status = $this->req->param('status');
        Db::table('kt_gptcms_common_user')->where('id',$id)->where('wid',$wid)->update([
            'status' => $status,
            'u_time' => date("Y-m-d H:i:s"),

        ]);
        return success('操作成功');
    }
    public function updateVipExpire()
    {
    	$expire = $this->req->param('vip_expire');
    	if(!$expire) return error('请输入时间');
    	if(strtotime($expire) < time()) return error('到期时间不得小于当前时间');
    	$id = $this->req->param('id');
    	if(!$id) return error('请选择会员');
    	$wid = Session::get('wid');
    	$res = Db::table("kt_gptcms_common_user")->where('wid',$wid)->where('id',$id)->update([
    		'vip_expire' => $expire,
    		'u_time' => date("Y-m-d H:i:s")
    	]);
    	return success('操作成功');
    }
}
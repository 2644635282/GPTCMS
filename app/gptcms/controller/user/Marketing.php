<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;

class Marketing extends BaseUser
{
    public function list()
    {
    	$wid = Session::get('wid');
    	$res = Db::table("kt_gptcms_vip_setmeal")->where('wid',$wid)->order('sort','desc')->select();
    	return success('vip套餐',$res);
    }
    public function delete()
    {
    	$wid = Session::get('wid');
    	$id = $this->req->param('id');
    	if(!$id) return error('请选择套餐');
    	$res = Db::table("kt_gptcms_vip_setmeal")->where('wid',$wid)->where('id',$id)->delete();
    	if(!$res) return error('删除失败');
    	return success('删除成功');
    }
    public function save()
    {
    	$wid = Session::get('wid');
    	$add = $this->req->param('add',[]);
    	$update = $this->req->param('update',[]);
    	if(!$add && !$update) return error('添加,更新数据不能同时为空');
    	$add = array_filter($add);
    	if($add){
			$addData = array_map(function($a)use($wid){
				$a['wid']=$wid;
				$a['c_time']=date("Y-m-d H:i:s");
				$a['u_time']=date("Y-m-d H:i:s");
				return $a;
			}, $add);
			$addRes = Db::table('kt_gptcms_vip_setmeal')->field('wid,duration,title,sort,duration_type,price,old_price,give_num,c_time,u_time')->insertAll($addData);
		}
		if($update){
			foreach ($update as $u) {
				if(!$u) continue;
				Db::table('kt_gptcms_vip_setmeal')->where('id',$u['id'])->where('wid',$wid)->update([
                    'duration' => $u['duration'],
                    'title' => $u['title'],
					'sort' => $u['sort'],
					'duration_type' => $u['duration_type'],
					'price' => $u['price'],
					'old_price' => $u['old_price'],
                    'give_num' => $u['give_num'],
					'u_time' => date("Y-m-d H:i:s"),
				]);
			}
		}
    	return success('操作成功');
    }

    public function recharge()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_recharge_setmeal")->where('wid',$wid)->order('sequence','desc')->select();
        return success('充值套餐',$res);
    }
    public function rechargeDelete()
    {
        $wid = Session::get('wid');
        $id = $this->req->param('id');
        if(!$id) return error('请选择套餐');
        $res = Db::table("kt_gptcms_recharge_setmeal")->where('wid',$wid)->where('id',$id)->delete();
        if(!$res) return error('删除失败');
        return success('删除成功');
    }
    public function rechargeSave()
    {
        $wid = Session::get('wid');

        $id = $this->req->param('id');
        $data = [];
        $data['wid'] = $wid;
        $data['number'] = $this->req->param('number');
        $data['title'] = $this->req->param('title');
        $data['sequence'] = $this->req->param('sequence',0);;
        if(!$data['number']) return error('请输入条数');
        $data['price'] = $this->req->param('price');
        if(!$data['price']) return error('请输入价格');
        $data['old_price'] = $this->req->param('old_price',0);
        $data['bz'] = $this->req->param('bz');
        if($id){
            $data['id'] = $id;
        }else{
            $data['c_time'] = date("Y-m-d H:i:s");
        }   
        $data['u_time'] = date("Y-m-d H:i:s");
        $res = Db::table("kt_gptcms_recharge_setmeal")->save($data);
        return success('保存成功');
        
    }

    /**
    * 分享奖励  获取
    */
    public function share()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_share_award")->field("status,number,up_limit")->where('wid',$wid)->find();
        return success('',$res);
    }

    /**
    * 分享奖励  保存
    */
    public function shareSet()
    {
        $wid = Session::get('wid');
        $data = [];
        $data['status'] = $this->req->param('status',1);
        $data['number'] = $this->req->param('number',0);
        $data['up_limit'] = $this->req->param('up_limit',0);
   		
        $is = Db::table("kt_gptcms_share_award")->where('wid',$wid)->find();
        if($is){
            $data['id'] = $is['id'];
        }
        $data['wid'] = $wid;
        $res = Db::table("kt_gptcms_share_award")->save($data);
        return success("保存成功");
    }
     /**
    * 邀请奖励  获取
    */
    public function invite()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_invite_award")->field("status,number,up_limit")->where('wid',$wid)->find();
        return success('',$res);
    }

    /**
    * 邀请奖励  保存
    */
    public function inviteSet()
    {
        $wid = Session::get('wid');
        $data = [];
        $data['status'] = $this->req->param('status',1);
        $data['number'] = $this->req->param('number',0);
        $data['up_limit'] = $this->req->param('up_limit',0);
   		
        $is = Db::table("kt_gptcms_invite_award")->where('wid',$wid)->find();
        if($is){
            $data['id'] = $is['id'];
        }
        $data['wid'] = $wid;
        $res = Db::table("kt_gptcms_invite_award")->save($data);
        return success("保存成功");
    }
     /**
    * 看广告奖励  获取
    */
    public function vad()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_vad_award")->field("status,number,up_limit,ad_id")->where('wid',$wid)->find();
        return success('',$res);
    }

    /**
    * 看广告奖励  保存
    */
    public function vadSet()
    {
        $wid = Session::get('wid');
        $data = [];
        $data['status'] = $this->req->param('status',1);
        $data['number'] = $this->req->param('number',0);
        $data['up_limit'] = $this->req->param('up_limit',0);
        $data['ad_id'] = $this->req->param('ad_id');
   		if(!$data['ad_id']) return error('请输入广告位ID');
        $is = Db::table("kt_gptcms_vad_award")->where('wid',$wid)->find();
        if($is){
            $data['id'] = $is['id'];
        }
        $data['wid'] = $wid;
        $res = Db::table("kt_gptcms_vad_award")->save($data);
        return success("保存成功");
    }

    public function vipEquity()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_vip_equity")->where('wid',$wid)->find();
        return success('ok',$res);
    }

    public function saveVipEquity()
    {
        $wid = Session::get('wid');
        $explain = $this->req->param('explain');
        if(!$explain) return error('权益说明不能为空');
        $is = Db::table("kt_gptcms_vip_equity")->where('wid',$wid)->find();
        if($is){
            $data['id'] = $is['id'];
        }
        $data['wid'] = $wid;
        $data['explain'] = $explain;
        $data['utime'] = date('Y-m-d H:i:s');
        $res = Db::table("kt_gptcms_vip_equity")->save($data);
        return success("保存成功");
    }
}

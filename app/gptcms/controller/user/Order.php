<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;

class Order extends BaseUser
{
	public function list()
    {
    	$wid = Session::get('wid');
    	$page = $this->req->param('page/d')?:1;
        $size = $this->req->param("size/d")?:10;
        $nickname = $this->req->param('nickname');
        $status = $this->req->param('status/d');
        $res = Db::table("kt_gptcms_pay_order")
               ->alias('c')
               ->field('c.pay_time,c.common_id,c.pay_type,c.c_time,c.amount,c.order_bh,c.status,c.number,c.type,u.nickname,c.ddbh,c.transaction_id')
               ->leftjoin('kt_gptcms_common_user u','c.common_id=u.id')
        	   ->where('c.wid',$wid);

        if($nickname) $res->where('c.common_id|u.nickname','like','%'.$nickname.'%');
        if($status) $res->where('c.status','=',$status);
    	$data = [];
    	$data['page'] = $page;
    	$data['size'] = $size;
    	$data['count'] = $res->count();
		$data['item'] = $res->page($page,$size)->order('c.c_time','desc')->filter(function($r){
			return $r;
		})->select();

    	return success('订单列表',$data);
    }

}
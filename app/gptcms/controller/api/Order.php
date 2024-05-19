<?php
declare (strict_types = 1);

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Session;
use think\facade\Log;
use app\gptcms\model\PayModel;

class Order extends BaseApi
{
	public function list()
    {
    	$wid = Session::get('wid');
    	$page = $this->req->param('page/d')?:1;
        $size = $this->req->param("size/d")?:10;
        $nickname = $this->req->param('nickname');
        $res = Db::table("kt_gptcms_pay_order")
               ->alias('c')
               ->field('c.pay_time,c.pay_type,c.c_time,c.amount,c.order_bh,c.status,c.number,c.type,u.nickname,c.ddbh,c.transaction_id')
               ->leftjoin('kt_gptcms_common_user u','c.common_id=u.id')
        	   ->where('c.wid',$wid);

        if($nickname) $res->where('u.nickname','like','%'.$nickname.'%');

    	$data = [];
    	$data['page'] = $page;
    	$data['size'] = $size;
    	$data['count'] = $res->count();
		$data['item'] = $res->page($page,$size)->order('c.c_time','desc')->filter(function($r){
			return $r;
		})->select();

    	return success('订单列表',$data);
    }

    public function result()
    {
        $wid = Session::get('wid');
        $order_id = $this->req->param('order_id');
        if(!$order_id) return error('参数错误');
        $order = Db::table('kt_gptcms_pay_order')->find($order_id);
        if(!$order) return error('订单不存在');
        if($order['status'] == 1) return error('支付中...');
        if($order['status'] == 2) return success('支付成功');
        if($order['status'] == 3) return success('取消支付');
    }
    
    public function create()
    {
        $wid = Session::get('wid');
        $uid = Session::get('uid');
        $pay_type = $this->req->param("pay_type")?:'wx';
        $notpay = $this->req->param("notpay");
        $openid = '';
        $platform = platform(); //平台
        if($pay_type == "wx" && $notpay != 1){
            if($platform == 'wxapp'){ //微信环境浏览器
                $openid = Db::table('kt_gptcms_wx_user')->where('common_id',$uid)->value('openid');
                if(!$openid) return error('缺少必要参数openid');
            }elseif($platform == 'mpapp'){ //微信小程序
                $openid = Db::table('kt_gptcms_xcx_user')->where('common_id',$uid)->value('openid');
                if(!$openid) return error('缺少必要参数openid');
            }
        }
        
        $setmeal_type = $this->req->param('setmeal_type');
        if(!$setmeal_type) return error('请选择套餐类型');
        $setmeal_id = $this->req->param('setmeal_id');
        if(!$setmeal_id) return error('请选择套餐');
        $buy_number = $this->req->param('buy_number',1);
        $unitprice = 0;
        $setmeal = '';
        $number = 0;
        $type = 0;
        switch ($setmeal_type) {
            case 'vip':
                $setmeal = Db::table('kt_gptcms_vip_setmeal')->find($setmeal_id);
                if($setmeal){
                    $unitprice = $setmeal['price'];
                    $number = $buy_number * $setmeal['duration'];
                    $type =  $setmeal['duration_type'];
                } 
                break;
            case 'recharge':
                $setmeal = Db::table('kt_gptcms_recharge_setmeal')->find($setmeal_id);
                if($setmeal){
                    $unitprice = $setmeal['price'];
                    $number = $buy_number * $setmeal['number'];
                }
                $type = 9;
                break;
        }
        if(!$setmeal) return error('套餐不存在');
        
        $amount = $buy_number * $unitprice;
        $common_id = $this->user['id'];
        $order_bh = time() .'|'.$common_id . rand(10000,99999);
        $status = 1;
        $orderid =  Db::table('kt_gptcms_pay_order')->insertGetId([
            'wid' => $wid,
            'common_id' => $common_id,
            'amount' => $amount,
            'order_bh' => $order_bh,
            'status' => $status,
            'number' => $number,
            'type' => $type,
            'pay_type' => $pay_type,
            'setmeal_type' => $setmeal_type,
            'setmeal_id' => $setmeal_id,
            'buy_number' => $buy_number,
            'c_time' => date("Y-m-d H:i:s"),
            'u_time' => date("Y-m-d H:i:s"),
        ]);
        if(!$orderid) return error('订单创建失败');
        $out_trade_no = time().rand(1000,9999);
        $payConfig = [];
        if($pay_type == "wx" && $notpay != 1){
            $notifyUrl =  $this->host.'/gptcms/api/paynotify/webchat';
            $payConfig = PayModel::commonPrepay($wid,$openid,$amount,$notifyUrl,$out_trade_no);
        }else if($pay_type == "zfb" && $notpay != 1){
            $info = Db::table("kt_gptcms_alipay_config")->where(["wid"=>$wid])->find();
            $request["app_id"] = $info["app_id"];
            $request["merchant_private_key"] = $info["merchant_private_key"];
            $request["alipay_public_key"] = $info["alipay_public_key"];
            $request["paytype"] = "alipay";
            $notifyUrl =  $this->host.'/gptcms/user/alipay/callback';
            $payConfig = PayModel::commonPrepay($wid,$openid,$amount,$notifyUrl,$out_trade_no,"选购套餐","",$request);
        }
        Log::error($payConfig);
        if($payConfig && isset($payConfig['status']) && $payConfig['status'] == 'error') return json($payConfig);
        
        if($notpay != 1){
            $payid = Db::table('kt_gptcms_pay')->insertGetId([
                'wid' => $wid,
                'common_id' => $common_id,
                'orderid' => $orderid,
                'out_trade_no' => $out_trade_no,
                'order_bh' => $order_bh,
                'uip' => '',
                'amount' => $amount,
                'ifok' => 0,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ]);
        }

        return success('下单成功',['payconfig'=>$payConfig,'order_id'=>$orderid]);
    }

}
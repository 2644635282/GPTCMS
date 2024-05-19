<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Session;

class Marketing extends BaseApi
{
	/**
     * vip套餐
     */
    public function list()
    {
    	$wid = Session::get('wid');
    	$res = Db::table("kt_gptcms_vip_setmeal")->where('wid',$wid)->order("sort","desc")
              ->filter(function($r){
                $day_price = 0;
                switch ($r['duration_type']) {
                    case 1:
                        $day_price = number_format(($r['price']/$r['duration']),2) === "0.00" ? "0.01" : number_format(($r['price']/$r['duration']),2);
                        break;
                    case 3:
                        $day_price = number_format(($r['price']/($r['duration']*30)),2) === "0.00" ? "0.01" : number_format(($r['price']/($r['duration']*30)),2);
                        break;
                    case 5:
                        $day_price = number_format(($r['price']/($r['duration']*365)),2) === "0.00" ? "0.01" : number_format(($r['price']/($r['duration']*365)),2);
                        break;
                    
                }
                $r['day_price'] = $day_price;
                return $r;
              })->select();
    	return success('vip套餐',$res);
    }

    /**
     * 充值套餐
     */
    public function recharge()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_recharge_setmeal")->where('wid',$wid)->order('sequence','desc')->select();
        return success('充值套餐',$res);
    }

    /**
     * 广告
     */
    public function vad()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_vad_award")->field("status,number,up_limit,ad_id")->where('wid',$wid)->find();
        return success('',$res);
    }

    public function vipEquity()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_vip_equity")->where('wid',$wid)->find();
        return success('ok',$res);
    }
}

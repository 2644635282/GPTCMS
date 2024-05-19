<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Session;

class Set extends BaseApi
{
	/**
     * 获取公众号配置
     */
    public function getWxgzh()
    {
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_wxgzh')->where('wid', $wid)->find();
        return success('获取成功',$config);
    }

    /**
     * pc配置
     */
    public function getPc()
    {
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_pc')->where('wid',$wid)->find();
        return success('获取成功',$config);
    }

    /**
     * h5配置
     */
    public function getH5()
    {
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_h5_wx')->where('wid',$wid)->find();
        $config['title'] = Db::table('kt_gptcms_websit')->where('wid',$wid)->value("title");
        return success('获取成功',$config);
    }

    /**
     * 小程序配置
     */
    public function getXcx()
    {
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_xcx')->where('wid',$wid)->find();
        return success('获取成功',$config);
    }

    /**
     * 小程序应用
     */
    public function getMiniprogram()
    {
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_miniprogram')->where('wid',$wid)->find();
        return success('获取成功',$config);
    }

    /**
     * 提示语
     */
    public function getRemind()
    {
    	$wid = Session::get('wid');
      	$config = Db::table('kt_gptcms_system')->field('zdz_remind,welcome')->where('wid',$wid)->find();
      	return success('获取成功',$config);
    }
    /**
     * 自定义余额
     */
    public function getSelfBalance()
    {
        $wid = Session::get('wid');
        $system = Db::table('kt_gptcms_system')->where('wid',$wid)->value("self_balance") ?: "条";
        return success('获取成功',$system);
    }
    /**
     * 获取喇叭开关
     */
    public function getTextTVideoStatus()
    {
        $wid = Session::get('wid');
        $status = Db::table('kt_gptcms_aliai_config')->where('wid',$wid)->value("status") ?: 0;
        return success('获取成功',$status);
    }
    /**
     * 获取绘画开关
     */
    public function getPaintStatus()
    {
        $wid = Session::get('wid');
        $status = Db::table('kt_gptcms_gptpaint_config')->where('wid',$wid)->value("status") ?: 0;
        return success('获取成功',$status);
    }
    /**
     * 
     */
    public function getChatmodel()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_chatmodel_set')->field("gpt35,gpt4,linkerai,chatglm")->where('wid',$wid)->find();
        return success('对话模型',$res);
    }
    /**
     * 
     */
    public function getPaintmodel()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_paintmodel_set')->field("sd,mj")->where('wid',$wid)->find();
        return success('获取成功',$res);
    }

    /*
    * 系统 获取配置状态
    */
    public function systemStatus(){
        $wid = Session::get('wid');
        $system = Db::table('kt_gptcms_system')->where("wid",$wid)->find();
        $data = [];
        $data["alipay_status"] = Db::table('kt_gptcms_alipay_config')->where('wid',$wid)->value("status")?:0;
        $data["paint_draw_status"] = Db::table('kt_gptcms_gptpaint_config')->where('wid',$wid)->value("draw_status")?:0;
        $data["tencentai_status"] = Db::table('kt_gptcms_tencentai_config')->where('wid',$wid)->value("status")?:0;
        $data["logo"] = $system["logo"] ?? "";
        return success('获取成功',$data);
    }

}

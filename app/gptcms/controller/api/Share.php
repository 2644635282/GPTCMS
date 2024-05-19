<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Session;

class Share extends BaseApi
{
	/**
     * 分享奖励
     */
    public function reward()
    {
        $wid = Session::get('wid');
        $user = $this->user;

        $config = Db::table("kt_gptcms_share_award")->field("status,number,up_limit")->where('wid',$wid)->find();
        if($config['status'] != 1) return error('获取奖励失败，功能未开启');
        $res = Db::table('kt_gptcms_share_rewards')->where(['wid'=>$wid,'common_id'=>$user['id']])->whereDay('c_time');
        $count = $res->count();
        if($count >= $config['up_limit']) return error('您今日领取奖励次数已达上限');
        Db::table('kt_gptcms_share_rewards')->insert([
            'wid' => $wid, 
            'common_id' => $user['id'],
            'num' => $config['number'],
            'c_time' => date('Y-m-d H:i:s')
        ]);
        return success("分享成功奖励{$config['number']}条",$config['number']);
    }

    public function getJssdk()
    {
        $url = $this->req->param('url')?:$this->req->url();
        $wid = Session::get('wid');
        $wxgzh = Db::table('kt_gptcms_wxgzh')->where('wid', $wid)->find();
        if(!isset($wxgzh['appid']) || !isset($wxgzh['appsecret'])) return error('');
        $jssdk = new Jssdk($wxgzh['appid'],$wxgzh['appsecret']);
        $signPackage = $jssdk->getSignPackage($url);
        if(isset($signPackage['status'])) return error($signPackage['msg']);
        return success('ok',$signPackage);
    }
}

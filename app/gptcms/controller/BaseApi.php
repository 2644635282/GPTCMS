<?php

namespace app\gptcms\controller;

use app\BaseController;
use think\facade\Db;
use think\Request;
use think\facade\Session;
use app\gptcms\model\SmsModel;

class BaseApi extends BaseController
{
    protected $req;
    protected $user;
    protected $wid;
    protected $token;


    public function  __construct(Request $request){
        $this->req = $request;
        $this->host  = $request->host();
        $url  = $request->url();
        $this->token = $this->req->header('token');
        $this->wid = $this->req->header('wid');

        $platform = platform();
        if($platform == 'mpapp'){ //微信小程序
            $this->user = Db::table('kt_gptcms_common_user')->where([['wid', '=', $this->wid],['xcx_token', '=', $this->token]])->find();
        }else{
            $this->user = Db::table('kt_gptcms_common_user')->where([['wid', '=', $this->wid],['token', '=', $this->token], ['expire_time', '>',time()]])->find();
        }
        
        if($this->user){
            $this->uid = $this->user['id'];
            Session::set('uid',$this->user['id']);
        } 
        Session::set('wid',$this->wid);
    }

    /**
     * 发送短信验证码
     * @return \think\Response
     */
    public function sendCode()
    {
        $wid = Session::get('wid');
        $phone = $this->req->post('mobile');
        if(!preg_match("/^1[3456789]\d{9}$/", $phone)) return error('手机号格式不正确');
        $data = [
            'phone' => $phone,
            'bh' => '001', //验证码模板
            'param' => ['code'=>rand(100000,999999)]
        ];
        return SmsModel::sendSms($wid,$data);
    }
}
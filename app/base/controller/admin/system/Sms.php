<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin\system;
use think\facade\Db;
use Ramsey\Uuid\Uuid;
use think\facade\Session;
use app\base\model\admin\system\SmsModel;
use app\base\controller\BaseAdmin;

/**
* 短信
**/
class Sms extends BaseAdmin
{
    
    /**
     * 短信配置
     * @return \think\Response
     */
    public function index()
    {   
        $res = SmsModel::SmsInfo();
        return success('短信配置',$res);
    }

    /**
     * 短信配置保存
     * @return \think\Response
     */
    public function save()
    {   
        $data = [];
        $data = $this->req->post();
        $res = SmsModel::smsUpdate($data);
        if($res != 'ok') return error($res);
        return success('更新成功');
    }
    /**
     * 短信验证码模板配置
     * @return \think\Response
     */
    public function codeTemplateSave()
    {   
        $data = [];
        $data = $this->req->post();
        $data['bh'] = '001';
        $res = SmsModel::templateSave($data);
        if($res != 'ok') return error($res);
        return success('更新成功');
    }
    /**
     * 获取短信验证码模板配置
     * @return \think\Response
     */
    public function getCodeTemplate()
    {   
        $where = [];
        $where['bh'] = '001';
        $where['uid'] = Session::get('uid');
        $res = SmsModel::getTemplate($where);
        return success('短信模板配置',$res);
    }
	 /**
     * 发送短信验证码
     * @return \think\Response
     */
    public function sendCode()
    {
        $phone = $this->req->post('phone');
        if(!preg_match("/^1[3456789]\d{9}$/", $phone)) return error('手机号格式不正确');
        $data = [
            'phone' => $phone,
            'bh' => '001', //验证码模板
            'param' => ['code'=>SmsModel::getCode()]
        ];
        $res = SmsModel::sendSms($data);
        if($res != 'ok') return error('发送失败');
        return success('发送成功');
    }
}
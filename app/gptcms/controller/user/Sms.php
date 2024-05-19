<?php 

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use Ramsey\Uuid\Uuid;
use think\facade\Session;
use app\gptcms\model\SmsModel;

/**
* 短信
**/
class Sms extends BaseUser
{
    
    /**
     * 短信配置
     * @return \think\Response
     */
    public function info()
    {   
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_sms_config')->field('access_key_id,access_key_secret')->where('wid',$wid)->find();
        if(!$res){
            Db::table('kt_gptcms_sms_config')->insert(['wid'=>$wid]);
            $res = Db::table('kt_gptcms_sms_config')->field('access_key_id,access_key_secret')->where('wid',$wid)->find();
        }
        return success('阿里云短信配置',$res);
    }

    /**
     * 短信配置保存
     * @return \think\Response
     */
    public function save()
    {   

        $access_key_id = $this->req->post("access_key_id");
        $access_key_secret = $this->req->post("access_key_secret");
        if(!$access_key_id) return error("请输入 accessKeyId");
        if(!$access_key_secret) return error("请输入 accessKeySecret");
        $wid = Session::get('wid');
        $config =  Db::table('kt_gptcms_sms_config')->where('wid',$wid)->find();
        $param = [];
        $param['access_key_id'] = $access_key_id;
        $param['access_key_secret'] = $access_key_secret;
        $param['wid'] = $wid;
        if($config) $param['id'] = $config['id'];
        $res = Db::table('kt_gptcms_sms_config')->save($param);
        return success('保存成功');
    }
    /**
     * 短信验证码模板配置
     * @return \think\Response
     */
    public function codeTemplateSave()
    {   
        $wid = Session::get('wid');
        $data = [];
        $data['sign_name'] = $this->req->post('sign_name');
        $data['template_code'] = $this->req->post('template_code');
        $data['template_code'] = $this->req->post('template_code');
        $data['content'] = $this->req->post('content');
        $data['bh'] = '001';
        if(!$data['sign_name']) return '缺少模板签名';
        if(!$data['template_code']) return '缺少模板code';
        if(!$data['content']) return '缺少模板内容';
        $template = Db::table('kt_gptcms_sms_template')->where(['wid'=>$wid,'bh'=>$data['bh']])->find();
        if($template) $data['id'] = $template['id'];
        $res =  Db::table('kt_gptcms_sms_template')->save($data);
        return success('保存成功');
    }
    /**
     * 获取短信验证码模板配置
     * @return \think\Response
     */
    public function getCodeTemplate()
    {   
        $wid = Session::get('wid');
        $where = [];
        $where['wid'] = $wid;
        $where['bh'] = '001';
        $res = Db::table('kt_gptcms_sms_template')->field('sign_name,template_code,content')->where($where)->find();
        if(!$res){
            Db::table('kt_gptcms_sms_template')->insert(['wid'=>$wid,'bh'=>"001"]);
            $res = Db::table('kt_gptcms_sms_template')->field('sign_name,template_code,content')->where($where)->find();
        }
        return success('阿里云短信模板配置',$res);
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
            'param' => ['code'=>666666]
        ];
        return SmsModel::sendSms($data);
    }
}
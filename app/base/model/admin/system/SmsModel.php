<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\admin\system;
use think\facade\Db;
use think\facade\Session;
use app\base\model\BaseModel;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi; 
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use think\facade\Cache;

/*
* 基础功能model
*/
class SmsModel extends BaseModel
{
	private static $puid;

	 /**
     * 使用AK&SK初始化账号Client
     * @param string $accessKeyId
     * @param string $accessKeySecret
     * @return Dysmsapi Client
     */
    public static function createClient($accessKeyId, $accessKeySecret){
        $config = new Config([
            // 您的 AccessKey ID
            "accessKeyId" => $accessKeyId,
            // 您的 AccessKey Secret
            "accessKeySecret" => $accessKeySecret
        ]);
        // 访问的域名
        $config->endpoint = "dysmsapi.aliyuncs.com";
        return new Dysmsapi($config);
    }

    /**
     * @param data array ['bh'=>'001','phone'=>手机号,'templateParam'=>'参数']
     * @return void
     */
    public static function sendSms($data,$uid = ''){
    	$uid = $uid ?: Session::get('uid');
    	$apiData = [
    		'phoneNumbers'=>$data['phone'],
    	];
    	if(isset($data['param']) && $data['param']) $apiData['templateParam'] = json_encode($data['param']);
    	$smsConfig = self::getSmsInfo($uid);

    	if(!$smsConfig) return '未设置短信配置';
    	$puid = self::$puid;
    	$template = Db::table('kt_base_sms_template')->where('uid',$puid)->where('bh',$data['bh'])->find();
    	if(!$template) return '当前代理未设置短信模板';
    	$apiData['signName'] = $template['sign_name'];
    	$apiData['templateCode'] = $template['template_code'];
    	$client = self::createClient($smsConfig['access_key_id'], $smsConfig['access_key_secret']); // 阿里云key和accesecret
    	
        $sendSmsRequest = new SendSmsRequest($apiData);
        $resp = $client->sendSms($sendSmsRequest);
        if(!$resp || $resp->body->code != 'OK') return '发送失败';
        if(isset($data['param']['code'])){
        	Cache::set('sms_'.$data['phone'],$data['param']['code'],300);
        }
        return 'ok';
    }

	/**
	 * 获取 云存储配置, 当前代理未设置自动获取上级代理配置
	 * @param $uid 账户id
	 * @return 
	 */
 	static public function getSmsInfo($uid=''){
 		$uid = $uid ?: Session::get('uid');
		$res = Db::table('kt_base_sms_config')->where('uid',$uid)->find();
		if(!$res || !$res['access_key_id'] || !$res['access_key_secret']){
			$user = Db::table('kt_base_sms_config')->find($uid);
			if($user['isadmin'] == 1) return $res;
			$res = self::getSmsInfo($user['pid']);
		}
		self::$puid = $uid;
		return $res;
	}
	/**
	 * 获取 当前用户阿里云短信配置
	 * @param $uid 账户id
	 * @return 
	 */
 	static public function SmsInfo(){
 		$uid = Session::get('uid');
		$res = Db::table('kt_base_sms_config')->field('access_key_id,access_key_secret')->where('uid',$uid)->find();
		return $res;
	}
    /**
     * 短信模板配置
     * @param $data 数据
     * @return 
     */
    static public function templateSave ($data){
        $param['uid']  = Session::get('uid');
        $param['bh']  = $data['bh'];
        $template = Db::table('kt_base_sms_template')->where(['uid'=>$param['uid'],'bh'=>$param['bh']])->find();
        if($template) $param['id'] = $template['id'];

        if(!isset($data['sign_name']) || !$data['sign_name']) return '缺少模板签名';
        $param['sign_name'] =  $data['sign_name'];
        if(!isset($data['template_code']) || !$data['template_code']) return '缺少模板code';
        $param['template_code'] =  $data['template_code'];
        if(!isset($data['content']) || !$data['content']) return '缺少模板内容';
        $param['content'] =  $data['content'];
        $res =  Db::table('kt_base_sms_template')->save($param);

        return 'ok';
    }
    /**
     * 获取 当前用户阿里云短信模板
     * @param $uid 账户id
     * @return 
     */
    static public function getTemplate($where){
        $res = Db::table('kt_base_sms_template')->field('sign_name,template_code,content')->where($where)->find();
        return $res;
    }
	/**
	 * 保存 当前用户阿里云短信配置
	 * @param $data 更新数据 array
	 * @return 
	 */
 	static public function smsUpdate($data){
 		if(!$data['accessKeyId'] || !$data['accessKeySecret']) return '参数错误';
 		$uid = Session::get('uid');
 		$config =  Db::table('kt_base_sms_config')->where('uid',$uid)->find();
 		$param = [];
 		$param['access_key_id'] = $data['accessKeyId'];
 		$param['access_key_secret'] = $data['accessKeySecret'];
 		$param['uid'] = $uid;
 		if($config) $param['id'] = $config['id'];
		$res = Db::table('kt_base_sms_config')->save($param);
		return 'ok';
	}

}
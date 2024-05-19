<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\gptcms\model;
use think\facade\Db;
use think\facade\Session;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi; 
use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;
use think\facade\Cache;

/*
* 基础功能model
*/
class SmsModel
{
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
    public static function sendSms($wid,$data){
    	$apiData = [
    		'phoneNumbers'=>$data['phone'],
    	];
        $count = Db::table("kt_gptcms_sms_record")->where("phone",$data['phone'])->whereDay("c_time")->count();
        if($count >= 5) return error("发送次数已达上限");
    	if(isset($data['param']) && $data['param']) $apiData['templateParam'] = json_encode($data['param']);
    	$smsConfig = self::getSmsInfo($wid);

    	if(!$smsConfig || !$smsConfig['access_key_id'] || !$smsConfig['access_key_secret']) return error("配置错误");
    	$template = Db::table('kt_gptcms_sms_template')->where('wid',$wid)->where('bh',$data['bh'])->find();
    	if(!$template) return error('未设置短信模板');
    	$apiData['signName'] = $template['sign_name'];
    	$apiData['templateCode'] = $template['template_code'];
        try {
        	$client = self::createClient($smsConfig['access_key_id'], $smsConfig['access_key_secret']); // 阿里云key和accesecret
            $sendSmsRequest = new SendSmsRequest($apiData);
        
            $resp = $client->sendSms($sendSmsRequest);
            if(!$resp || $resp->body->code != 'OK') return error('发送成功');
            if(isset($data['param']['code'])){
                Cache::set('gptsms_'.$data['phone'],$data['param']['code'],300);
            }
            Db::table("kt_gptcms_sms_record")->insert([
                "wid" => $wid,
                "phone" => $data['phone'],
                "c_time" => date("Y-m-d H:i:s"),
            ]);
            return success("发送成功");
        } catch (Exception $e) {
            return error('发送成功');
        }
       
    }

	/**
	 * 获取 阿里云短信配置
	 * @param $wid 账户id
	 * @return 
	 */
 	static public function getSmsInfo($wid){
		$res = Db::table('kt_gptcms_sms_config')->where('wid',$wid)->find();
		return $res;   
	}

    /**
     * 获取 当前用户阿里云短信模板
     * @param $uid 账户id
     * @return 
     */
    static public function getTemplate($wid){
        $res = Db::table('kt_gptcms_sms_template')->field('sign_name,template_code,content')->where('wid',$wid)->find();
        return $res;
    }

}
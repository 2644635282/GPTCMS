<?php
namespace Ktadmin\Tencent;

use think\facade\Db;
use app\base\model\BaseModel;
use think\facade\Cache
use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Asr\V20190614\AsrClient;
use TencentCloud\Asr\V20190614\Models\CreateRecTaskRequest;
use TencentCloud\Asr\V20190614\Models\DescribeTaskStatusRequest;
use TencentCloud\Common\Profile\HttpProfile;

/**
* 腾讯 Ai百宝箱
**/
class Aichest
{
	protected $secret_id;
	protected $secret_key;
	protected $EngineModelType = '16k_0';
	protected $ChannelNum  = 1;
	protected $ResTextFormat = 0;
	protected $CallbackUrl = '';

    public function __construct($congif=array()){
		$this->initConfig($config);  //初始化配置
	}

	/**
	 * 创建录音文件识别请求
	 * @param string $url
	 * @return - http response body if succeeds, else false.
	 */
	public function CreateRecTask($url = '')
	{
		$TENCENTCLOUD_SECRET_ID = $this->secret_id;
		$TENCENTCLOUD_SECRET_KEY = $this->secret_key;
		$cred = new Credential($TENCENTCLOUD_SECRET_ID, $TENCENTCLOUD_SECRET_KEY);
		// 实例化一个http选项，可选的，没有特殊需求可以跳过
		$httpProfile = new HttpProfile();
		$httpProfile->setEndpoint($Endpoint);  // 指定接入地域域名(默认就近接入)

		// 实例化一个client选项，可选的，没有特殊需求可以跳过
		$clientProfile = new ClientProfile();
		$clientProfile->setSignMethod("TC3-HMAC-SHA256");  // 指定签名算法(默认为HmacSHA256)
		$clientProfile->setHttpProfile($httpProfile);
		$client = new AsrClient($cred, "ap-shanghai", $clientProfile);
		$req = new CreateRecTaskRequest();
		$params = '{"EngineModelType":"'.$this->EngineModelType.'","ChannelNum":'.$this->ChannelNum.',"ResTextFormat":'.$this->ResTextFormat.',"SourceType":0,"Url":"' . $url . '","CallbackUrl":""}';
		$req->fromJsonString($params);
		$resp = $client->CreateRecTask($req);
		return $resp;

	}

	/**
	 * 查看任务状态
	 * @param string $taskid
	 * @return - http response body if succeeds, else false.
	 */
	public function DescribeTaskStatus($taskid)
	{
		$TENCENTCLOUD_SECRET_ID = $this->secret_id;
		$TENCENTCLOUD_SECRET_KEY = $this->secret_key;
		$Endpoint = 'asr.tencentcloudapi.com';
		$cred = '';
		$cred = new Credential($TENCENTCLOUD_SECRET_ID, $TENCENTCLOUD_SECRET_KEY);
		// 实例化一个http选项，可选的，没有特殊需求可以跳过
		$httpProfile = new HttpProfile();
		$httpProfile->setEndpoint($Endpoint);  // 指定接入地域域名(默认就近接入)

		// 实例化一个client选项，可选的，没有特殊需求可以跳过
		$clientProfile = new ClientProfile();
		$clientProfile->setSignMethod("TC3-HMAC-SHA256");  // 指定签名算法(默认为HmacSHA256)
		$clientProfile->setHttpProfile($httpProfile);
		$client = new AsrClient($cred, "ap-shanghai", $clientProfile);
		$req = new DescribeTaskStatusRequest();
		$params = '{"TaskId":'.$taskid.'}';
		$req->fromJsonString($params);
		$resp = $client->DescribeTaskStatus($req);
		return $resp;
	}

	/**
	*  初始化腾讯云配置
	*/
	private function initConfig($config=array())
	{
		$uid = BaseModel::getUid();
		if(!$config) $config =  Db::table('kt_base_tencentai_config')->where($uid,$uid)->find();
		
		if($config){
			$this->secret_id = $config['secret_id'] ?? '';
			$this->secret_key = $config['secret_key'] ?? '';
		}
	}
}
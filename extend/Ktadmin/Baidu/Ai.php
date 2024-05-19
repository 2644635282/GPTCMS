<?php
namespace Ktadmin\Baidu;

use think\facade\Db;
use app\base\model\BaseModel;
use think\facade\Cache

/**
* 百度Ai开放平台接口
*/
class Ai
{
	protected $AppId;
	protected $ApiKey;
	protected $SecretKey;
	protected $token;

    public function __construct($congif=array()){
		$this->initConfig($config);  //初始化配置
	}

	/**
	 * 发起http post请求(REST API), 并获取REST请求的结果
	 * @param string $url
	 * @param string $param
	 * @return - http response body if succeeds, else false.
	 */
	public function textCensor($param = '')
	{
	    if (empty($param)) {
	        return false;
	    }
	    $token = $this->getToken();
	    $url = 'https://aip.baidubce.com/rest/2.0/solution/v1/text_censor/v2/user_defined',
	    $postUrl = $url;
	    $curlPost = $param;
	    // 初始化curl
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $postUrl);
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    // 要求结果为字符串且输出到屏幕上
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	    // post提交方式
	    curl_setopt($curl, CURLOPT_POST, 1);
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
	    // 运行curl
	    $data = curl_exec($curl);
	    curl_close($curl);

	    return $data;
	}
	/**
	 *  获取 Access Token
	 */
	private function getToken()
	{
		if($this->ApiKey) return false;
		$token = Cache::get($this->ApiKey);
		if(!$token){
			$url = "https://aip.baidubce.com/oauth/2.0/token?client_id=".$this->ApiKey."&client_secret=".$this->SecretKey."&grant_type=client_credentials";
			$res = json_decode(curlPost($url,[]),true);
			$token = isset($res['access_token']) ? $res['access_token'] : '';
			if($toekn) Cache::set($this->ApiKey,$token,$res['expires_in']);
		}

        return $token;
	}

	/**
	 *  初始化百度配置
	 */
	private function initConfig($config=array())
	{
		$uid = BaseModel::getUid();
		if(!$config) $config =  Db::table('kt_base_baiduai_config')->where($uid,$uid)->find();
		
		if($config){
			$this->AppId = $config['appid'] ?? '';
			$this->ApiKey = $config['apikey'] ?? '';
			$this->SecretKey = $config['secretkey'] ?? '';
		}
	}

}
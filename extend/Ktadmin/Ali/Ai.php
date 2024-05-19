<?php
namespace Ktadmin\Ali;

use think\facade\Db;
use app\base\model\BaseModel;
use think\facade\Cache

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

/**
* 阿里智能语音合成
*/
class Ai
{
	protected $AccessKeyID;
	protected $AccessKeySecret;
	protected $regionId;
	protected $token;
	protected $format = "pcm";
	protected $sampleRate = 16000;
	protected $audioSaveFile;

    public function __construct($congif=array()){
		$this->initConfig($config);  //初始化配置
	}

	function processGETRequest($text, $audioSaveFile="", $format="", $sampleRate="") {
		$token = $this->getToken();
		$audioSaveFile = $audioSaveFile ?: root_path().'public/storage/aliai/'.time().'_'.rand(1,99).'.'.$format;
		$format = $format ?: $this->$sampleRate;
		$sampleRate = $sampleRate ?: $this->$sampleRate;

		if(!$token) return error('配置错误');
	    $url = "https://nls-gateway-cn-shanghai.aliyuncs.com/stream/v1/tts";
	    $url = $url . "?appkey=" . $this->AccessKeyID;
	    $url = $url . "&token=" . $token;
	    $url = $url . "&text=" . $text;
	    $url = $url . "&format=" . $this->format;
	    $url = $url . "&sample_rate=" . strval($sampleRate);
	    // voice 发音人，可选，默认是xiaoyun。
	    // $url = $url . "&voice=" . "xiaoyun";
	    // volume 音量，范围是0~100，可选，默认50。
	    // $url = $url . "&volume=" . strval(50);
	    // speech_rate 语速，范围是-500~500，可选，默认是0。
	    // $url = $url . "&speech_rate=" . strval(0);
	    // pitch_rate 语调，范围是-500~500，可选，默认是0。
	    // $url = $url . "&pitch_rate=" . strval(0);
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	    /**
	     * 设置HTTPS GET URL。
	     */
	    curl_setopt($curl, CURLOPT_URL, $url);
	    /**
	     * 设置返回的响应包含HTTPS头部信息。
	     */
	    curl_setopt($curl, CURLOPT_HEADER, TRUE);
	    /**
	     * 发送HTTPS GET请求。
	     */
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
	    $response = curl_exec($curl);
	    if ($response == FALSE) {
	        return 'curl_exec failed!';
	        curl_close($curl);
	        return ;
	    }
	    /**
	     * 处理服务端返回的响应。
	     */
	    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	    $headers = substr($response, 0, $headerSize);
	    $bodyContent = substr($response, $headerSize);
	    curl_close($curl);
	    if (stripos($headers, "Content-Type: audio/mpeg") != FALSE || stripos($headers, "Content-Type:audio/mpeg") != FALSE) {
	        file_put_contents($audioSaveFile, $bodyContent);
	        return success('合成成功',$audioSaveFile);
	    }else {
	    	return error("The GET request failed: " . $bodyContent);
	    }
	}
	function processPOSTRequest($text, $audioSaveFile="", $format="", $sampleRate="") {
		$token = $this->getToken();
	    $url = "https://nls-gateway-cn-shanghai.aliyuncs.com/stream/v1/tts";
	    $audioSaveFile = $audioSaveFile ?: root_path().'public/storage/aliai/'.time().'_'.rand(1,99).'.'.$format;
		$format = $format ?: $this->$sampleRate;
		$sampleRate = $sampleRate ?: $this->$sampleRate;
	    /**
	     * 请求参数，以JSON格式字符串填入HTTPS POST请求的Body中。
	     */
	    $taskArr = array(
	        "appkey" => $this->AccessKeyID,
	        "token" => $token,
	        "text" => $text,
	        "format" => $format,
	        "sample_rate" => $sampleRate
	        // voice 发音人，可选，默认是xiaoyun。
	        // "voice" => "xiaoyun",
	        // volume 音量，范围是0~100，可选，默认50。
	        // "volume" => 50,
	        // speech_rate 语速，范围是-500~500，可选，默认是0。
	        // "speech_rate" => 0,
	        // pitch_rate 语调，范围是-500~500，可选，默认是0。
	        // "pitch_rate" => 0
	    );
	    $body = json_encode($taskArr);
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	    /**
	     * 设置HTTPS POST URL。
	     */
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_POST, TRUE);
	    /**
	     * 设置HTTPS POST请求头部。
	     * */
	    $httpHeaders = array(
	        "Content-Type: application/json"
	    );
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $httpHeaders);
	    /**
	     * 设置HTTPS POST请求体。
	     */
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
	    /**
	     * 设置返回的响应包含HTTPS头部信息。
	     */
	    curl_setopt($curl, CURLOPT_HEADER, TRUE);
	    /**
	     * 发送HTTPS POST请求。
	     */
	    $response = curl_exec($curl);
	    if ($response == FALSE) {
	        curl_close($curl);
	        return error('合成失败');
	    }
	    /**
	     * 处理服务端返回的响应。
	     */
	    $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
	    $headers = substr($response, 0, $headerSize);
	    $bodyContent = substr($response, $headerSize);
	    curl_close($curl);
	    if (stripos($headers, "Content-Type: audio/mpeg") != FALSE || stripos($headers, "Content-Type:audio/mpeg") != FALSE) {
	        file_put_contents($audioSaveFile, $bodyContent);
	        return success('合成成功',$audioSaveFile);
	    }else {
	    	return error("The GET request failed: " . $bodyContent);
	    }
	}
	/**
	 *  获取 Access Token
	 */
	private function getToken()
	{
		if($this->AccessKeyID) return false;
		$tokenId = Cache::get($this->AccessKeyID);
		if(!$token){
			$AccessKeyID = $this->AccessKeyID;
			$AccessKeySecret = $this->AccessKeySecret;
			$regionId = $this->regionId;
			/**
			 * 第一步：设置一个全局客户端
			 * 使用阿里云RAM账号的AccessKey ID和AccessKey Secret进行鉴权。
			 */
			AlibabaCloud::accessKeyClient($AccessKeyID,$AccessKeySecret)
			            ->regionId($regionId)
			            ->asDefaultClient();
			try {
			    $response = AlibabaCloud::nlsCloudMeta()
			                            ->v20180518()
			                            ->createToken()
			                            ->request();
			    $token = $response["Token"];
			    if ($token != NULL) {
			    	$tokenId = $token["Id"];
			    	$ExpireTime = $token["ExpireTime"] - time();
			    	Cache::set($this->AccessKeyID,$tokenId,$ExpireTime);
			    }
			    else {
			        return '';
			    }
			} catch (ClientException $exception) {
			    // 获取错误消息
			    // return error($exception->getErrorMessage());
			    return '';
			} catch (ServerException $exception) {
			    // 获取错误消息
			     // return error($exception->getErrorMessage());
				return '';
			}
		}

        return $tokenId;
	}

	/**
	 *  初始化百度配置
	 */
	private function initConfig($config=array())
	{
		$uid = BaseModel::getUid();
		if(!$config) $config =  Db::table('kt_base_baiduai_config')->where($uid,$uid)->find();
		
		if($config){
			$this->AccessKeyID = $config['accesskey_id'] ?? '';
			$this->AccessKeySecret = $config['accesskey_secret'] ?? '';
			$this->regionId = $config['region'] ?? "cn-shanghai";
		}
	}

}
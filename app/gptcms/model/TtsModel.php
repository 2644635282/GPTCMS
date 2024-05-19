<?php 
namespace app\gptcms\model;
use think\facade\Db;
use think\facade\Session;
// require dirname(__DIR__). '/vendor/autoload.php';
use WebSocket\Client;

class TtsModel
{
	static public function authentication($app_id,$api_key,$api_secret,$draft_content){
		$date = gmdate("D, d M Y H:i:s")." GMT";
		$url = self::createUrl($api_key, $api_secret);
		$client = new Client($url);
		$speed = 50;
		$volume = 50;
		$pitch = 50;
		//拼接要发送的信息
		$message = self::createMsg($app_id, $speed, $volume, $pitch, $draft_content);
	    $client->send(json_encode($message, true));
	    $response = $client->receive();
	    $response = json_decode($response, true);
	    $mp3 = $response["data"]["audio"];
	    $mp3Name = "qipa250_" . date("His", time()) . "_" . rand(1111, 9999) . '.mp3';
		$path = "./static/gptcms";
		if (!is_dir($path)) mkdir($path, 0777, true);
		$qipaMp3Src = fopen($path . "/" . $mp3Name, 'ab'); //mp3音频文件名字
		while(1){
			$audio = base64_decode($response['data']['audio']);
	        fwrite($qipaMp3Src, $audio);
	        if($response["data"]["status"] == 2)break;
	        //继续接收消息
	        $response = $client->receive();
	        $response = json_decode($response, true);
		}

		return $path . "/" . $mp3Name;
	}	

	static public function curl($url,$header=null, $data=null){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    $result = curl_exec($ch);
	    curl_close($ch);
	    return $result;
	}


	/**
	* 拼接签名
	* @param $api_key
	* @param $api_secret
	* @param $time
	* @return string
	*/
	static public function sign($api_key, $api_secret, $time){
	    $signature_origin = 'host: ws-api.xfyun.cn' . "\n";
	    $signature_origin .= 'date: ' . $time . "\n";
	    $signature_origin .= 'GET /v2/tts HTTP/1.1';
	    $signature_sha = hash_hmac('sha256', $signature_origin, $api_secret, true);
	    $signature_sha = base64_encode($signature_sha);
	    $authorization_origin = 'api_key="' . $api_key . '", algorithm="hmac-sha256", ';
	    $authorization_origin .= 'headers="host date request-line", signature="' . $signature_sha . '"';
	    $authorization = base64_encode($authorization_origin);
	    return $authorization;
	}

	/**
	* 生成Url
	* @param $api_key
	* @param $api_secret
	* @return string
	*/
	static public function createUrl($api_key, $api_secret){
	    $url = 'wss://tts-api.xfyun.cn/v2/tts';
	    $time = date('D, d M Y H:i:s', strtotime('-8 hour')) . ' GMT';
	    $authorization = self::sign($api_key, $api_secret, $time);
	    $url .= '?' . 'authorization=' . $authorization . '&date=' . urlencode($time) . '&host=ws-api.xfyun.cn';
	    return $url;
	}

	/**
	* 生成要发送的消息体
	* @param $app_id
	* @param $speed
	* @param $volume
	* @param $pitch
	* @param $draft_content
	* @return array
	*/
	static public function createMsg($app_id, $speed, $volume, $pitch, $draft_content){
	    return [
	        'common' => [
	            'app_id' => $app_id,
	        ],
	        'business' => [
	            'aue' => 'lame',
	            'sfl' => 1,
	            'auf' => 'audio/L16;rate=16000',
	            'vcn' => 'aisbabyxu',
	            'speed' => (int)$speed,
	            'volume' => (int)$volume,
	            'pitch' => (int)$pitch,
	            'tte' => 'utf8',
	        ],
	        'data' => [
	            'status' => 2,
	            'text' => base64_encode($draft_content),
	        ],
	    ];
	}

	static public function upload($appid,$api_secret,$time=NULL){
		$time = $time?:time();
		$signature_origin = md5($appid.$time);
		$mp3 = "D:\www\zq\public\static\gptcms\qipa250_123117_9120.mp3";
	    $signature_sha = hash_hmac('sha1', $signature_origin, $api_secret, true);
	    $signature_sha = base64_encode($signature_sha);
	    $url = "https://raasr.xfyun.cn/v2/api/upload?duration=100&signa=".$signature_sha."&fileName=/static/gptcms/qipa250_123117_9120.mp3&fileSize=".fileSize($mp3)."&sysDicts=uncivilizedLanguage&appId=".$appid."&ts=".$time;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/octet-stream'
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([]));
        $result = curl_exec($ch);
		var_dump($result);die;

	}
}
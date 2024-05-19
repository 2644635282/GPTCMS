<?php 
namespace app\gptcms\model;
use think\facade\Db;
use think\facade\Session;

use Ramsey\Uuid\Uuid;
use OSS\OssClient;
use Qcloud\Cos\Client;
use OSS\Core\OssException;
use think\facade\Filesystem;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class MediaModel 
{
	static public $wid = 0;
	/**
	 * 获取 云存储配置
	 * @param $wid 账户id
	 * @return 
	 */
 	static public function storageInfo($wid){
    $res = Db::table("kt_gptcms_storage_config")->where(["wid"=>$wid])->find();
    if(!$res || !$res["type"] || $res["type"] == 1){
      $user = Db::table("kt_base_user")->where(["id"=>$wid])->find();
      $res = Db::table('kt_base_storage_config')->where('uid',$user["agid"])->find();
    }
		return $res;
	}

	/**
	 * 获取 云存储配置
	 * @return 
	 */
 	static public function storageInfoByUid($uid){
		$res = Db::table('kt_base_storage_config')->where('uid',$uid)->find();
		return $res;
	}

	static public function uploadPaint($wid,$url,$chatmodel='paint')
	{

		$img = '';
		self::$wid = $wid;
        $storage = self::storageInfo($wid);
        $type = $storage["type"] ?? 1;
        $file_name = $chatmodel.'_'.time().rand(1,999999).".png";
        $path = root_path()."public/storage/gptcms/paintchat/".$file_name;
        if(!is_dir(root_path()."public/storage/gptcms/paintchat/")) mkdir(root_path()."public/storage/gptcms/paintchat/",0777,true);
        switch ($type) {
            case 1:
                $str = file_get_contents($url);
                $res = file_put_contents($path, $str);
                $http = "http";
                if( (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == "https")||(isset($_SERVER['HTTPS']) &&$_SERVER['HTTPS'] =="on")) $http = "https";
                if($res) $img = $http.'://'.$_SERVER['HTTP_HOST'].'/storage/gptcms/paintchat/'.$file_name;
                break;
            case 2:
                $img = self::uploadOss($storage,$url,$file_name);
                break;
            case 3:
                $img = self::uploadCos($storage,$url,$file_name);
                break;
            case 4:
                $img = self::uploadKodo($storage,$url,$file_name);
                break;
          }
        
        return ["img"=>$img,"file_name"=>$file_name];
    }

    /**
     * 上传到Oss
     * @return \think\Response
     */
     static public function uploadOss($storage,$url,$file_name){
          $wid = self::$wid;
          $accessKeyId      = $storage['oss_id'];
          $accessKeySecret  = $storage['oss_secret'];
          $endpoint  = $storage['oss_endpoint'];
          $bucket = "gptcms";
          $bucket = $storage['oss_bucket'];
          try {
               $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
               // 设置Socket层传输数据的超时时间
               $ossClient->setTimeout(3600);
               // 设置建立连接的超时时间，单位秒，默认10秒。
               $ossClient->setConnectTimeout(10);
      
               $filePath = 'gptcms/paintchat/'.$wid.'/'.$file_name;
               $content = file_get_contents($url);
               $uploadOssRes = $ossClient->putObject($bucket, $filePath, $content);
               $img = $uploadOssRes['info']['url'];
          return $img;
          } catch (OssException $e) {
               print $e->getDetails();  //调试时,打开,输出错误信息
               return '上传失败';
         }
    }

    /**
    * 上传到Cos
    * @return \think\Response
    */
    static public function uploadCos($storage,$url,$file_name){
          $wid = self::$wid;
          $secretId = $storage['cos_secretId']; 
          $secretKey = $storage['cos_secretKey']; 
          $endpoint = $storage['cos_endpoint'];
          $bucket = $storage['cos_bucket'];
          $regionArr =  explode('.', $endpoint);
          $region = $regionArr[2]; 
          $cosClient = new Client(
          array(
               'region' => $region,
               'schema' => 'https', //协议头部，默认为http
               'credentials'=> array(
               'secretId'  => $secretId ,
               'secretKey' => $secretKey)));
          $filePath = 'gptcms/paintchat/'.$wid.'/'.$file_name;
          try {
               $result = $cosClient->putObject(
                    array(
                         'Bucket' => $bucket,
                         'Key' => $filePath,
                         'Body' => file_get_contents($url),
                    )
               );
               $img = "https://".$result['Location']; 
               return $img;
          }catch (\Exception $e) {  
               return 'error';
          }
    }

    /**
    * 上传到kodo 七牛云
    * @return \think\Response
    */
    static public function uploadKodo($storage,$url,$file_name)
    {
        $wid = self::$wid;
        $accessKey = $storage['kodo_key'];
        $secretKey = $storage['kodo_secret'];
        $bucket = $storage['kodo_bucket'];
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        $content = file_get_contents($url);
 
        $filePath = 'gptcms/'.$wid.'/'.$file_name;
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->put($token, $filePath, $content);
        if ($err !== null) {
            return 'error';
        } else {
            $img = 'http://' . $storage['kodo_domain'] . '/' . $ret['key'];
            return $img;
        }
    }
}

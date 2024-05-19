<?php 
namespace app\gptcms\controller\job;

use app\BaseController;
use think\facade\Db;
use think\facade\Log;
use think\queue\Job;
use app\gptcms\model\MediaModel;
use OSS\OssClient;
use Qcloud\Cos\Client;
use OSS\Core\OssException;
use think\facade\Filesystem;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;


/**
* MokKer 任务类
*/
class MokKer extends BaseController{
     /**
     * fire方法是消息队列默认调用的方法
     * @param Job $job 当前的任务对象
     * @param array $data 发布任务时自定义的数据
     */
     public function fire(Job $job, array $data){
          $isJobDone = $this->doHelloJob($data);
          if ($isJobDone){
               $job->delete();
          	Log::info("执行完毕,删除任务" . $job->attempts() . '\n');
          }else{
               if ($job->attempts() > 3){
                    $job->delete();
                    Log::info("超时任务删除" . $job->attempts() . '\n');
               }
          }
     }

     private function doHelloJob(array $data){
          return true;
     }


     public function index(){
      	$info = Db::table("kt_gptcms_bj_paint")->where(["status"=>1])->find();
      	if(!$info)return "ok";
      	$wid = $info["wid"];
         	$config = Db::table("kt_gptcms_bj_config")->where(["wid"=>$wid])->find();
         	$key = $config["key"];
         	$backgrounId = $info["background_id"];
         	$size = $info["size"];
		$ker = new \Ktadmin\MokKer($key);
		$fileUrl = $this->dowload($info["image"]);
		$res = $ker->replace($fileUrl,$backgrounId,$size);
		$images = [];
		if(isset($res["detail"])){
    		    Db::table("kt_gptcms_bj_paint")->where(["id"=>$info["id"]])->update(["status"=>2]);
		   return "ok";
		}else{
			foreach ($res["images"] as $key=>$value){
                    $path = 'storage/gptcms_bj/'.time().getRandStr(5).'.jpg';
                    $path = public_path().$path;
                    // $binary = base64_decode($value["image"]["data"]);
                    // file_put_contents($path, $binary, 1);
                    $imageUrl = $this->uploadFile($path,$wid);
				$image["path"] = $imageUrl;
				$image["image_id"] = $value["image_id"];
				$image["message"] = $value["message"];
				$images[] = $image;
			}
		}
		$data["upload_id"] = $res["upload_id"];
		$data["status"] = 3;
		$data["images"] = json_encode($images,320);
		Db::table("kt_gptcms_bj_paint")->where(["id"=>$info["id"]])->update($data);
		unlink($fileUrl);

      return 'ok';
    }

     private function dowload($file){
		$fileArr = explode("/", $file);
		$path = public_path().'storage/gptcms_bj/'.$fileArr[count($fileArr)-1];
		if (!is_dir(public_path().'storage/gptcms_bj')){
               mkdir (public_path().'storage/gptcms_bj',0775,true);
          }
		$res = file_put_contents($path, file_get_contents($file));
		return $path;
	}

     public function uploadFile($path,$wid){
          $storage = MediaModel::storageInfo($wid);
          switch ($storage["type"]) {
               case 1:
                    $url = Request()->domain()."/".$path;
                    break;
               case 2:
                    $url = $this->uploadOss($storage,$path,"jpg",$wid);
                    if($url != "上传失败")unlink($path);
                    if($url == "上传失败")$url = Request()->domain()."/".$path;;
                    break;
               case 3:
                    $url = $this->uploadCos($storage,$path,"jpg",$wid);
                    if($url != "上传失败")unlink($path);
                    if($url == "上传失败")$url = Request()->domain()."/".$path;;
                    break;
               case 4:
                    $url = $this->uploadKodo($storage,$path,"jpg",$wid);
                    if($url != "上传失败")unlink($path);
                    if($url == "上传失败")$url = Request()->domain()."/".$path;;
                    break;
          }

          return $url;
     }
      /**
     * 上传到Oss
     * @return \think\Response
     */
     public function uploadOss($storage,$path,$ext,$wid){
          $accessKeyId      = $storage['oss_id'];
          $accessKeySecret  = $storage['oss_secret'];
          $endpoint  = $storage['oss_endpoint'];
          $bucket = "gptcms_bj";
          $bucket = $storage['oss_bucket'];
          try {
               $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
               // 设置Socket层传输数据的超时时间
               $ossClient->setTimeout(3600);
               // 设置建立连接的超时时间，单位秒，默认10秒。
               $ossClient->setConnectTimeout(10);
               $bucketExist = $ossClient->doesBucketExist($bucket);  //判断bucket是否存在
               $filePath = 'gptcms_bj/'.$wid.'/'.uniqid('gptcms_bj_').'.'.$ext;
               $uploadOssRes = $ossClient->uploadFile($bucket, $filePath, $path);
               $url = $uploadOssRes['info']['url'];
               return $url;
          } catch (OssException $e) {
               print $e->getDetails();  //调试时,打开,输出错误信息
               return '上传失败';
         }
    }

    /**
    * 上传到Cos
    * @return \think\Response
    */
    public function uploadCos($storage,$imgTruePath,$ext,$wid){
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
          $filePath = 'gptcms_bj/'.$wid.'/'.uniqid('gptcms_bj_').'.'.$ext;
          try {
               $result = $cosClient->putObject(
                    array(
                         'Bucket' => $bucket,
                         'Key' => $filePath,
                         'Body' => fopen($imgTruePath, 'rb')
                    )
               );
               $url = "https://".$result['Location']; 
               return $url;
          }catch (\Exception $e) {  
               return 'error';
          }
    }

    /**
    * 上传到kodo 七牛云
    * @return \think\Response
    */
    public function uploadKodo($storage,$imgTruePath,$ext,$wid)
    {
        $accessKey = $storage['kodo_key'];
        $secretKey = $storage['kodo_secret'];
        $bucket = $storage['kodo_bucket'];
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        $filePath = 'gptcms_bj/'.$wid.'/'.uniqid('gptcms_bj_').'.'.$ext;
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $filePath, $imgTruePath);
        if ($err !== null) {
            return 'error';
        } else {
            $url = 'http://' . $storage['kodo_domain'] . '/' . $ret['key'];
            return $url;
        }
    }
    
}
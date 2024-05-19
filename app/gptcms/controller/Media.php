<?php 
// +----------------------------------------------------------------------
// | CRMUU-企微SCRM是专业的企业微信第三方源码系统.
// +----------------------------------------------------------------------
// | [CRMUU] Copyright (c) 2022 http://crmuu.com All rights reserved.
// +----------------------------------------------------------------------

namespace app\gptcms\controller;
use think\facade\Db;
use app\gptcms\controller\BaseUser;
use Ramsey\Uuid\Uuid;
use think\facade\Session;
use app\gptcms\model\MediaModel;
use OSS\OssClient;
use Qcloud\Cos\Client;
use OSS\Core\OssException;
use think\facade\Filesystem;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;


/**
* 媒体类
**/
class Media extends BaseUser
{
    //上传临时文件 只限存储到本地
    public function uploadTempFile()
    {
        $files = request()->file('file');
        // $imgTruePath = $files->getPathname(); //获取临时地址
        // $fileName = $files->getOriginalName(); //获取上传名
        // $ext = $file->extension(); //获取后缀
        // $time = date('Y-m-d');
        $url = "/storage/".Filesystem::disk('public')->putFile( 'upload/gptcms/tem', $files, 'md5');
        
        return $url;
    }
    //上传图片
    public function uploadFile(){
        $wid = Session::get("wid");
        $files = request()->file('file');
        $data_type = request()->param("type")?:0;
        if(!$files) return error('未检测到上传资源');
        $storage = MediaModel::storageInfo($wid);
        $type = $storage["type"] ?? 1;
        $file_name = $files->getOriginalName();
        $ext = $files->extension(); //获取后缀
        if(!in_array($ext,["png","jpg","jpeg","gif"]) && $data_type == 1)return error("图片文件格式错误");
        if(!in_array($ext,["amr","mp3"]) && $data_type == 2)return error("语音文件格式错误");
        if(!in_array($ext,["mp4"]) && $data_type == 3)return error("视频文件格式错误");
        if(in_array($ext,["xlsx"])) $type = 1;
          switch ($type) {
            case 1:
                $imgTruePath = $files->getPathname(); //获取临时地址
                $minType = $files->getOriginalName(); //获取后缀
                $fileName = $files->getOriginalName(); //获取上传名
                $time = date('Y-m-d');
                $url = $this->req->domain()."/storage/".Filesystem::disk('public')->putFile( 'upload/gptcms/'.$time, $files, 'md5');
                break;
            case 2:
                $url = $this->uploadOss($storage,$files);
                break;
            case 3:
                $url = $this->uploadCos($storage,$files);
                break;
            case 4:
                $url = $this->uploadKodo($storage,$files);
                break;
          }
        
        return success('上传成功',["url"=>$url,"file_name"=>$file_name]);
    }

    /**
     * 上传到Oss
     * @return \think\Response
     */
     public function uploadOss($storage,$file){
          $wid = Session::get("wid");
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
               $bucketExist = $ossClient->doesBucketExist($bucket);  //判断bucket是否存在
               $imgTruePath = $file->getPathname(); //获取临时地址
               $ext = $file->extension(); //获取后缀
               $fileName = $file->getOriginalName();
               $filePath = 'gptcms/'.$wid.'/'.uniqid('gptcms_').'.'.$ext;
               $uploadOssRes = $ossClient->uploadFile($bucket, $filePath, $imgTruePath);
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
    public function uploadCos($storage,$file){
          $wid = Session::get("wid");
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
          $imgTruePath = $file->getPathname(); //获取临时地址
          $ext = $file->extension(); //获取后缀
          $minType = $file->getMime(); //获取文件类型
          $fileName = $file->getOriginalName();
          $filePath = 'gptcms/'.$wid.'/'.uniqid('gptcms_').'.'.$ext;
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
    public function uploadKodo($storage,$file)
    {
        $wid = Session::get("wid");
        $accessKey = $storage['kodo_key'];
        $secretKey = $storage['kodo_secret'];
        $bucket = $storage['kodo_bucket'];
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        $imgTruePath = $file->getPathname(); //获取临时地址
        $ext = $file->extension(); //获取后缀
        $minType = $file->getMime(); //获取文件类型
        $fileName = $file->getOriginalName();
        $filePath = 'gptcms/'.$wid.'/'.uniqid('gptcms_').'.'.$ext;
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
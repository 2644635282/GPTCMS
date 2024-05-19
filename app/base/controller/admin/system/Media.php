<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin\system;
use think\facade\Db;
use Ramsey\Uuid\Uuid;
use OSS\OssClient;
use OSS\Core\OssException;
use think\facade\Filesystem;
use think\facade\Session;
use app\base\model\BaseModel;
use app\base\controller\BaseAdmin;
use Qcloud\Cos\Client;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

/**
* 媒体类
**/
class Media extends BaseAdmin
{
    
  	/**
    * oss数据
    * @return \think\Response
    */
    public function info()
    {   
        $uid = Session::get('uid');
        $res = BaseModel::storageInfo();
        return success('云存储配置',$res);
    }

    /**
     * 云存储服务器配置保存
     * @return \think\Response
     */
    public function save()
    {   
        $uid = Session::get('uid');
        $data = [];
        $data = $this->req->post();
        if($data['type'] == 2 && (!$data['oss_id'] || !$data['oss_secret'] || !$data['oss_endpoint'] || !$data['oss_bucket'])) return error('参数错误');
        if($data['type'] == 3 && (!$data['cos_secretId'] || !$data['cos_secretKey'] || !$data['cos_bucket'] || !$data['cos_endpoint'])) return error('参数错误');
        if($data['type'] == 4 && (!$data['kodo_key'] || !$data['kodo_secret'] || !$data['kodo_domain'] || !$data['kodo_bucket'])) return error('参数错误');
        $res = BaseModel::storageUpdate($data);
        if($res != 'ok') return error($res);
        return success('更新成功');
    }

    /**
     * 上传文件
     * @return \think\Response
     */
    public function upload()
    {   
        $uid = Session::get("uid");
      	// 获取表单上传文件
        $file = request()->file('file');
      	if(!$file) return error('未检测到上传资源');
      	$res = [];
      	$storage = BaseModel::getStorageInfo();
      	$type = $storage['type'] ?? 1;
      	switch ($type) {
      		case 1: //本地
            $res = $this->uploadLocal($file);
    	 	    break;
      		case 2: //阿里云
          		$res = $this->uploadOss($storage,$file);
      			break;
      		case 3: //腾讯云
      			$res = $this->uploadCos($storage,$file);
      			break;
      		case 4:  //七牛
      			$res = $this->uploadKodo($storage,$file);
      			break;
      	}
        if($res == 'error') return error('上传失败');
        return success('上传成功',$res);
    }
    /**
    * 上传到Oss
    * @return \think\Response
    */
    public function uploadLocal($file)
    {
        $domain = $this->req->domain();
        // $imgTruePath = $file->getPathname(); //获取临时地址
        $ext = $file->extension(); //获取后缀
        $minType = $file->getMime(); //获取文件类型
        $fileName = $file->getOriginalName(); //获取上传名
        $time = date('Y-m-d');
        $res = Filesystem::putFile( 'upload/base/'.$time, $file, 'uniqid');
        return $domain.'/storage/'.$res;
    }

    /**
    * 上传到Oss
    * @return \think\Response
    */
    public function uploadOss($storage,$file)
    {
      	$accessKeyId      = $storage['oss_id'];
    		$accessKeySecret  = $storage['oss_secret'];
    		$endpoint  = $storage['oss_endpoint'];
    		$bucket = $storage['oss_bucket'];
    		try {
      	    $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
      	    // 设置Socket层传输数据的超时时间
      	    $ossClient->setTimeout(3600);
      	    // 设置建立连接的超时时间，单位秒，默认10秒。
      	    $ossClient->setConnectTimeout(10);

      	    // $bucketExist = $ossClient->doesBucketExist($bucket);  //判断bucket是否存在
      	    // if (!$bucketExist) {
      	    // 	$ossClient->createBucket($bucketName, \OSS\OssClient::OSS_ACL_TYPE_PUBLIC_READ);
      	    // }
            
      			$imgTruePath = $file->getPathname(); //获取临时地址
      			$ext = $file->extension(); //获取后缀
            $minType = $file->getMime(); //获取文件类型
      			$fileName = $file->getOriginalName();
      			$filePath = 'base/'.uniqid('base_').'.'.$ext;
      	    $uploadOssRes = $ossClient->uploadFile($bucket, $filePath, $imgTruePath);
      	    $url = $uploadOssRes['info']['url'];
      	    return $url;
    		} catch (OssException $e) {
      	    print $e->getDetails();  //调试时,打开,输出错误信息
      	    return 'error';
    		}
    }
    /**
    * 上传到Cos
    * @return \think\Response
    */
    public function uploadCos($storage,$file)
    {
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
        $filePath = 'base/'.uniqid('base_').'.'.$ext;
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
        $accessKey = $storage['kodo_key'];
        $secretKey = $storage['kodo_secret'];
        $bucket = $storage['kodo_bucket'];
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        $imgTruePath = $file->getPathname(); //获取临时地址
        $ext = $file->extension(); //获取后缀
        $minType = $file->getMime(); //获取文件类型
        $fileName = $file->getOriginalName();
        $filePath = 'base/'.uniqid('base_').'.'.$ext;
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $filePath, $imgTruePath);
        if ($err !== null) {
            return 'error';
        } else {
            return 'http://' . $storage['kodo_domain'] . '/' . $ret['key'];
        }
    }
}
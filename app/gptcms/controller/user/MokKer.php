<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;

class MokKer extends BaseUser{
	// public function index(){
 // 		// $filename = public_path().'storage/gptcms/ceshi.png'; //新图片名称
 //   //      $data = $res;
 //   //      $newFile = fopen($filename,"w"); //打开文件准备写入
 //   //      fwrite($newFile,$data); //写入二进制流到文件
 //   //      fclose($newFile); //关闭文件
	// 	// var_dump($res);die;
	// 	$wid = Session::get("wid");
	// 	$url = "https://api.mokker.ai/v1/backgrounds";
	// 	// $res = $this->httpRequest($url);
	// 	// $url = "https://api.mokker.ai/v1/uploadImage";
	// 	// $url = "https://api.mokker.ai/v1/replace-background";
	// 	// $image = 'https://qvmh-1251270280.cos.ap-guangzhou.myqcloud.com/fb172675831b50db23a61f9d37299833.jpg';
	// 	$file = "";
	// 	// $file = $this->dowload($image);
	// 	$header = [
	// 		'Authorization: Bearer 6a98dde5-c350-4934-9472-c5e42a6342bd',
	// 		'accept: application/json',
	// 		'Content-Type: multipart/form-data'
	// 	];
	// 	$res = $this->httpRequest($url,$file,$header);

	// 	var_dump($res);die;
	// }

	public function index(){
		$message = "一只狗狗";
		$token = "r8_XZZ1UujVZcuXG4E72nUtvXADRLnCu8f1BVQl4";
		// $token = "r8_2H7Ec5OggkQD1mIupw6uBW2uofYV3HW3t19dM";
		$version = "9936c2001faa2194a261c01381f90e65261879985476014a0a37a334593a05eb";
        $replicate = new \Ktadmin\Replicate($token,$version);
        $callback_url = $this->req->domain()."/gptcms/api/paintnotify/replicate";
        $res = $replicate->draw($message,$callback_url);
        var_dump($res);die;
		// $image = 'https://qvmh-1251270280.cos.ap-guangzhou.myqcloud.com/fb172675831b50db23a61f9d37299833.jpg';
		// $file = $this->dowload($image);
		// $key = "6a98dde5-c350-4934-9472-c5e42a6342bd";
		// $ker = new \Ktadmin\MokKer($key);
  //       // $res = $ker->replace($file);

  //       // $upload_id = "61e47a34-180a-46eb-b50d-40cd714ed1ec";
  //       // $res = $ker->replaces($upload_id);
  //       // $image_id = "d2f09bed-5dd3-430d-8b12-c8ddd20138a5";
  //       // $res = $ker->upscale($image_id);
  //       // $res = $ker->backgrounds();
  //       $background_id = "92b2b5d5-c3e1-4115-8b62-21ac2a715347";
  //       $res = $ker->replace($file,$background_id);
  // //       $path = root_path().'public/temp/module/';
		// // is_dir($path) or mkdir($path, 0777, true);

		// // $file_path = $path.'/'.$file_name;
		// // file_put_contents($file_path, base64_decode($resource));
		// var_dump($res);die;
  //       $filename = public_path().'storage/gptcms/ceshi.png'; //新图片名称
  //       $data = $res["images"][0]["image"]["data"];
  //       $newFile = fopen($filename,"w"); //打开文件准备写入
  //       fwrite($newFile,$data); //写入二进制流到文件
  //       fclose($newFile); //关闭文件
		// var_dump($filename);die;
	}
	
	public function dowload($file){
		$fileArr = explode("/", $file);
		$path = public_path().'storage/gptcms/'.$fileArr[count($fileArr)-1];
		if (!is_dir(public_path().'storage/gptcms')){
            mkdir (public_path().'storage/gptcms',0775,true);
        }
		$res = file_put_contents($path, file_get_contents($file));
		return $path;
	}

	public function httpRequest($url,$data=[],$header){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        if($data){
	        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
	        curl_setopt($curl, CURLOPT_POSTFIELDS,$data); // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($curl); // 执行操作

        return json_decode($result, true);
    }
}
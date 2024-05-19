<?php 
namespace app\gptcms\controller;

use think\facade\Db;
use think\facade\Session;
use app\BaseController;

/**
 * 统一定时任务类
 */
class Task extends BaseController
{
	/*
	*入口
	*/
	public function index(){
		$info = config("task");
		$arr =  json_decode(file_get_contents(app_path() . 'task.txt'),1) ?: [];
		$arr_api = [];
		$time = time();
		foreach ($info as $key => $val) {
			if(array_key_exists($key, $arr)){
				if($time > $arr[$key] + $val){
					$arr[$key] = $time;
					$arr_api[] = $this->request->domain()."/gptcms/job.".$key."/index";
				}
			}else{
				$arr[$key] = $time;
				$arr_api[] = $this->request->domain()."/gptcms/job.".$key."/index";
			}
		}
		$arr_json = json_encode($arr);
		file_put_contents(app_path() . 'task.txt', $arr_json);
		if(count($arr_api) > 0){
			$this->exApi($arr_api);
		}
		echo 'success';
		exit();
	}

	private function exApi($api){
		$mh = curl_multi_init();
		for ($i=0;$i<count($api);$i++) {
		     $conn[$i]=curl_init($api[$i]);
		      curl_setopt($conn[$i],CURLOPT_RETURNTRANSFER,1); //如果成功只将结果返回，不自动输出任何内容
		      curl_setopt($conn[$i],CURLOPT_TIMEOUT,0);
		      curl_setopt($conn[$i],CURLOPT_FOLLOWLOCATION,1);  //防止重定向
		      // curl_setopt($conn[$i], CURLOPT_POSTFIELDS, $data); //post 传参
		      curl_multi_add_handle ($mh,$conn[$i]);
		}
		$active = null;
		do { $n=curl_multi_exec($mh,$active); }
		while ($active > 0);

		$result = true;
		for ($i=0;$i<count($api);$i++) {
		      $res[$i]=json_decode(curl_multi_getcontent($conn[$i]),1);	
		      // echo microtime()."<br>";
			// $res = curl_multi_getcontent($conn[$i]);
		      curl_close($conn[$i]);
		      if($res[$i] != 'ok'){
				$result = false;
		      }
		}
		if(!$result){
			echo 'fail';
		}
		echo 'success';
		exit();
	}	

}
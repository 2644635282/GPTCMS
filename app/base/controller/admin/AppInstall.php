<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin;

use think\facade\Db;
use think\facade\Session;
use app\BaseController;
use app\base\model\admin\system\KtModel;
use PclZip;
use think\facade\Log;

/**
* 插件安装更新
*/
class AppInstall extends BaseController
{
	public function index()
	{
		$orderbh = request()->get('orderbh');
		$operation = request()->get('operation');
		$uid = KtModel::getAdminId();
		$config = Db::table('kt_base_ktconfig')->where('uid',$uid)->find();
		if(!$config){
			echo '配置错误';
			exit;
		}
		$params = [
			'key' => $config['key'],
			'secret' => $config['secret'],
			'orderbh' => $orderbh,
		];
		$detail = KtModel::getModuleDetailApi($params);
		if(!$detail || !isset($detail['status'])){
			echo '未知错误';
			exit;
		}
		if($detail['status'] != 'success'){
			echo $detail['msg'];
			exit;
		}
		
		$pulgin = $detail['data'];
		$module = Db::table('kt_base_market_app')->where('code',$pulgin['name'])->find();
		if($module && $operation == "uninstall"){
			$res = Db::table('kt_base_market_app')->where('code',$pulgin['name'])->delete();
			echo 'success';
			exit;
		}
		$data = [];
		if(!$module){
			$data['create_time'] = date("Y-m-d H:i:s");
			$operation = 'install';	
		}else{
			$data['id'] = $module['id'];
			if(!$module['version']){
				$operation = 'install';	
			}
			if($operation == 'upgrade' && $module['version'] == $pulgin['version']){
				echo '已更新到最新版本';
				exit;
			}
		}
		
		$data['version'] = $pulgin['version'];
		$data['orderbh'] = $orderbh;
		$data['has_applets'] = $pulgin['has_applets'];
		$data['expire_date'] = $pulgin['expire_date'];
		
		$params = [
			'key' => $config['key'],
			'secret' => $config['secret'],
			'orderbh' => $orderbh,
		];
		$downPlugin = KtModel::downModuleApi($params);
		if($downPlugin['status'] != 'success'){
			echo $downPlugin['msg'];
			exit;
		}
		//资源文件名
		$file_name = $downPlugin['data']['filename'];
		$resource = $downPlugin['data']['resource'];
		$path = root_path().'public/static/temp/module/';
		is_dir($path) or mkdir($path, 0777, true);
		$file_path = $path.'/'.$file_name;
		$res = file_put_contents($file_path, base64_decode($resource));
		$zipName = $file_name;
		if(!file_exists($file_path)) exit('写入失败');
		$zipArc = new PclZip($file_path);
		//插件目录
		// $nameIndex = $zipArc->getNameIndex(0);
		// $zipArc->close();
		// $nameIndexArr = explode('/', $nameIndex);
		// $name = $nameIndexArr[0];

		$nameIndex = $zipArc->listContent();
		$nameIndexArr = explode('/', $nameIndex[0]['filename']);
		$name = $nameIndexArr[0];

		try {
		    $re = $zipArc->extract(PCLZIP_OPT_PATH, root_path().'app', PCLZIP_OPT_REPLACE_NEWER);
		    if (!$re){echo '解压失败';exit;} 
		} catch (\Exception $e) {
		    echo '解压失败';exit;
		}


		//public目录移动
		$appPublicPath = root_path().'app'.'/'.$name.'/public';
		if(file_exists($appPublicPath)){
			if(file_exists($appPublicPath.'/static/'.$name)){
				if(file_exists(root_path().'public/static/'.$name)){
					clear_dir(root_path().'public/static/'.$name);
				}
				if(file_exists(root_path().'public/static/'.$name)){
					echo '请检查前端目录 public/static权限';exit;
				}
				rename($appPublicPath.'/static/'.$name,root_path().'public/static/'.$name);
			}
			if($name == 'gptcms'){
				if(file_exists($appPublicPath.'/app/kt_ai')){
					if(file_exists(root_path().'public/app/kt_ai')){
						clear_dir(root_path().'public/app/kt_ai');
					}
					if(file_exists(root_path().'public/app/kt_ai')){
						echo '请检查前端文件public/app权限';exit;
					}
					rename($appPublicPath.'/app/kt_ai',root_path().'public/app/kt_ai');
				}
			}else{
				if(file_exists($appPublicPath.'/app/'.$name)){
					if(file_exists(root_path().'public/app/'.$name)){
						clear_dir(root_path().'public/app/'.$name);
					}
					if(file_exists(root_path().'public/app/'.$name)){
						echo '请检查前端文件public/app权限';exit;
					}
					rename($appPublicPath.'/app/'.$name,root_path().'public/app/'.$name);
				}
			}
		}

		$xmlfilepath = root_path().'app/'.$name.'/mainfest.xml';
		if(!file_exists($xmlfilepath)) $xmlfilepath = root_path().'app/'.$name.'/manifest.xml';
 
		$xml = simplexml_load_file($xmlfilepath,'SimpleXMLElement', LIBXML_NOCDATA);

		$xml_arr = json_decode(json_encode($xml), 1);
		$data['user_link'] = $xml_arr['application']['userindex'] ?? '';
		$data['admin_link'] = $xml_arr['application']['adminindex'] ?? '';
		$data['target'] = $xml_arr['application']['target'] ?? 2;
		$data['describe'] = $xml_arr['application']['describe'] ?? '';
		$data['app_type'] = $xml_arr['application']['type'] ?? 1;
		$data['author'] = $xml_arr['application']['author'] ?? '';

		if($data['app_type'] == 2){
			$nameArr = explode('_', $xml_arr['application']['code']);
			if($data['app_type']=2  && in_array('plugin', $nameArr)){
				$key = array_keys($nameArr,'plugin');
    			$panmeArr = array_slice($nameArr, 0,$key);
    			$pname = implode('_', $panmeArr);
    			$data['pid'] = Db::table("kt_base_market_app")->where(["code"=>$pname])->value('id') ?: 0;
			}
		}
		$sqla = '';
		$upgradePath = root_path().'/app/'.$name.'/upgrade';
		
		if($operation == 'install'){
			$data['code'] = $xml_arr['application']['code'];
			$data['name'] =  $xml_arr['application']['name'] ."-".$pulgin["title"];
			$data['logo'] = $xml_arr['application']['logo'];
			$installsqlPath = root_path().'/app/'.$name.'/install.sql';
			if(file_exists($installsqlPath)){
				$sqla = file_get_contents($installsqlPath);
				if($sqla){
				    try{
						$resRunSql = $this->updateRunSql($sqla);
						if($resRunSql) {
							// echo "版本:".$versionDir." sql:".$resRunSql['msg'];
							// exit;
						}else{
							Log::error($resRunSql);
						}

					}catch(Exception $r){
						Log::error($r);
						// \Log::error('sql执行错误:' . $resRunSql['msg']);
							// echo "版本:".$versionDir." sql:".$resRunSql['msg'];
							// exit;
					}
				}
				$data['version'] = $xml_arr['application']['version'];
			}else if(is_dir($upgradePath)){
				$versionDirs = scandir($upgradePath);
				sort($versionDirs);
				if($versionDirs){
					foreach ($versionDirs as $versionDir) {
						if($versionDir == "." || $versionDir == ".." ) continue;
						if(!is_dir($upgradePath.'/'.$versionDir)) continue;
						$sqla = '';
						if(file_exists($upgradePath.'/'.$versionDir.'/up.sql')){
							$sqla = file_get_contents($upgradePath.'/'.$versionDir.'/up.sql');
							// $sqlArr = include($upgradePath.'/'.$versionDir.'/up.sql');
							// foreach ((array)$sqlArr as $val) {
							// 	$sqla .= $val;
							// }
						}
						if($sqla){
						    try{
								$resRunSql = $this->updateRunSql($sqla);
								if($resRunSql) {
									// echo "版本:".$versionDir." sql:".$resRunSql['msg'];
									// exit;
								}else{
									Log::error($resRunSql);
								}

							}catch(Exception $r){
								Log::error($r);
								// \Log::error('sql执行错误:' . $resRunSql['msg']);
									// echo "版本:".$versionDir." sql:".$resRunSql['msg'];
									// exit;
							}
						}
						
						$data['version'] = $versionDir;
					}
				}
			}
		}else if($operation == 'upgrade'){ 
			if(is_dir($upgradePath)){
				$versionDirs = scandir($upgradePath);
				foreach ($versionDirs as $key => $value) {
					if($value == "." || $value == ".." ) unset($versionDirs[$key]);
				}
				sort($versionDirs);
				if($versionDirs){
					// arsort($versionDirs);
					$versionDirKey = array_keys($versionDirs,$module['version'])[0];
					for ($i=$versionDirKey + 1; $i < count($versionDirs); $i++) { 
						if(!is_dir($upgradePath.'/'.$versionDirs[$i])) continue;
						$sqla = '';
						if(file_exists($upgradePath.'/'.$versionDirs[$i].'/up.sql')){
							$sqla = file_get_contents($upgradePath.'/'.$versionDirs[$i].'/up.sql');
							// $sqlArr = include($upgradePath.'/'.$versionDirs[$i].'/up.sql');
							// foreach ((array)$sqlArr as $k => $val) {
							// 	$sqla .= $val;
							// }
						}
						if($sqla){
							try{
								$resRunSql = $this->updateRunSql($sqla);
								if($resRunSql) {
									// echo "版本:".$versionDirs[$i]." sql:".$resRunSql['msg'];
									// exit;
								}else{
									Log::error($resRunSql);
								}
							}catch(Exception $r){
									Log::error($resRunSql);
									// echo "版本:".$versionDirs[$i]." sql:".$resRunSql['msg'];
									// exit;
							}
						}
						$data['version'] = $versionDirs[$i];
					}
				}
			}
		}
		$data['install_type'] =  2;
		$data['uid'] = KtModel::getAdminId();
		// $data['can_update'] = 0;
		Db::table('kt_base_market_app')->save($data);
		unlink($file_path);
		echo 'success';
		exit;
	}

	private function updateRunSql($content){
	    // error_reporting(0);
	    //遍历执行sql语句
	    //去除空行和注释
	    $content=removeBom($content);//自动去除bom头
	    // $content = preg_replace("/[\/\*][\s\S\r\n]*[\*\/]/", '', $content);
	    // $content = preg_replace("/[--]+(.+)(\r\n)+/", '', $content);
	    $content = preg_replace("/\/\*[\s\S\r\n]*\*\//", '', $content);
	    $content = preg_replace("/--+(.+)(\r\n)+/", '', $content);
	    $sqlArr = preg_split("/;[\r\n]*/", $content);
	    $error_message = '';
	    foreach ($sqlArr as $v) {
	        $v = str_replace( "\r\n",' ',$v);
	        if (empty($v)) continue;
	        try {
	            Db::execute($v);
	        } catch (\Exception $e) {
	            // $error_message .= $e->getMessage() . ' ';
	            // \Log::error('sql执行错误:' . $e->getMessage());
	            $error_message = $v;
	        }
	    }
	    // if ($error_message) {
	    //     return ['status'=>'error','msg'=> $error_message];
	    // } else {
	    //     return ['status'=>'success','msg'=>'执行成功'];
	    // }
	    return $error_message;
	}
}
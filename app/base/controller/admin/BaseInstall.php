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

/**
* 系统更新
*/
class BaseInstall extends BaseController
{
	private $param=[];
	public function index()
	{
		$u = $_SERVER['HTTP_HOST'];
		$a = 'download';
		$b = "all"; //每次请求安装全部版本,参数填 all, 每次安装一个版本 one

		//下载当前已安装的下一版本
		
		$param = [
			'a' => $a,
			'u' => $u,
		];

		//获取key
		$uid = KtModel::getAdminId();
		$config = Db::table('kt_base_ktconfig')->where('uid',$uid)->find();
		if(!$config || !$config['key'] || !$config['secret']) exit('config error'); 
		$param['webkey'] = $config['key'];
		$param['sign']  = KtModel::makeSignApi($param,$config['secret']);
		$this->param = $param;
		$url = 'https://www.kt8.cn/cloud/apiauthsys/index?'.http_build_query($param);
		if($b == 'one'){
			$res = $this->insta($url,$param);
			exit($res);
		}else if($b == 'all'){
			$msg = '';
			while (1) {
				$res = $this->insta($url);
				if($res!="success"){
					$msg = $res;
					break;
				}
			}
			if($msg=="newest"){
				exit('success');
			}else{
				exit($msg);
			}
			
		}
	}

	private function insta($url)
	{
		//如果存在sql文件,则删除
		$sqlPath = root_path() . 'update.sql';
		if (file_exists($sqlPath)){
			unlink($sqlPath);
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$output = curl_exec($ch);
		curl_close($ch);
		$res = json_decode($output,1);
		if($res && $res['status'] == 'success'){
			$resource = $res['data']['resource'];
			$file_name = $res['data']['filename'];

			$path = root_path().'public/temp/module/';
			is_dir($path) or mkdir($path, 0777, true);

			$file_path = $path.'/'.$file_name;
			file_put_contents($file_path, base64_decode($resource));
			KtModel::makelog($this->param,$file_name);
			// $zipArc = new \ZipArchive();
			$zipArc = new PclZip($file_path);
			// if (!$zipArc->open($file_path)) exit('更新失败');
			// if (!$zipArc->extractTo(root_path())) {
			// 	$zipArc->close();
			// 	exit('更新失败');
			// }
			try {
			    $re = $zipArc->extract(PCLZIP_OPT_PATH, root_path(), PCLZIP_OPT_REPLACE_NEWER);
			    // if (!$re) return $zipArc->error_string;
			    if (!$re) return '解压失败';

			} catch (\Exception $e) {
			    // \Log::error('插件解压失败，错误信息：' . $e->getMessage());
			    return '解压失败';
			}
			unlink($file_path);
			//去项目根目录找update.sql文件，执行sql
			
			if (file_exists($sqlPath)) {
	          $sqlContent = file_get_contents($sqlPath);
	          $sqlArr=preg_split("/;[\r\n]+/", $sqlContent);
	          foreach($sqlArr as $v){
	              Db::execute($v);
	          }
	          unlink($sqlPath);
	        }
			return 'success';
		}
		return $res['msg'];
	}
}
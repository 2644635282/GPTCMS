<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\admin\system;
use think\facade\Db;
use think\facade\Session;
use app\base\model\BaseModel;

/* 
* 狂团对接model
*/
class KtModel extends BaseModel
{
	/**
	* 获取狂团配置
	*/
	public static function getKtconfig($req)
	{
		//获取key和secret,不存在就自动创建
		$uid = Session::get('uid');
		$ktconfig = Db::table('kt_base_ktconfig')->field('key,secret')->where('uid',$uid)->find();
		if(!$ktconfig){
			Db::table('kt_base_ktconfig')->insert([
				'uid'=>$uid,
				'key'=>getRandStr(16),
				'secret'=>getRandStr(64)
			]);
			$ktconfig = Db::table('kt_base_ktconfig')->field('key,secret')->where('uid',$uid)->find();
		}
		//获取当前版本
		$nowver = require(root_path().'public/version.php');
		//查看是否绑定bind 0:未绑定 1:已绑定
		$ip = curlGet('http://www.kt8.cn/api/user/get_request_ip.html');
		$url = 'http://www.kt8.cn/api/user/kt_bind_status.html?domain='.$req->domain().'&key='.$ktconfig['key'].'&secret='.$ktconfig['secret'].'&ip='.$ip;
		$res = json_decode(curlGet($url),1);
		if($res['status'] == 'error'){
			$bindurl = 'http://www.kt8.cn/api/user/kt_bind.html?domain='.$req->domain().'&key='.$ktconfig['key'].'&secret='.$ktconfig['secret'].'&ip='.$ip.'&ver='.$nowver['ver'];
			$data = ['bind'=>0,'bindurl'=>$bindurl];
			return success('获取成功',$data);
		}
		//获取最新版本
		// $url = 'http://www.kt8.cn/cloud/apiauthsys/index';
		// $param['a'] = 'check'; 
		// $param['u'] = $req->domain();
		// $param['sign'] = md5($ktconfig['secret'] . '&' .$param['u']);
		// $url = $url .'?'. http_build_query($param);
		// $showver = json_decode(curlGet($url),1);

		$data = [
			'bind'=>1,
			'data'=>[
				'nowver'=>$res['data']['sys']['title'].'  '.$res['data']['level']['title'].'  '.$nowver['ver'],
				'uname'=>php_uname(),
				'phpver'=>PHP_VERSION,
				'software'=>strtolower($_SERVER['SERVER_SOFTWARE']),
				'mysqlver'=>Db::query("select version() as ver")[0]['ver'],
				'ktnc'=>$res['data']['user']['nc']
			]
		];
		return success('获取成功',$data);
	}
	/**
	* 获取狂团配置
	*/
	public static function ktAppConfig($req)
	{
		$uid = Session::get('uid');
		if(!BaseModel::isAdmin($uid)) return error("不是管理员账户");
		$key = $req->post("key");
		$secret = $req->post("secret");
		if(!$key || !$secret) return error("参数错误");
		$ktconfig = Db::table('kt_base_ktconfig')->field('key,secret')->where('uid',$uid)->find();
		if($ktconfig) return error("已绑定");
		Db::table('kt_base_ktconfig')->insert([
			'uid'=>$uid,
			'key'=>$key,
			'secret'=>$secret
		]);

		return success("绑定成功");
	}

	/**
	* 应用列表
	*/
	public static function ktapp($req)
	{
		$uid = Session::get('uid');
		// $page = $req->post('page',1);
		// $size = $req->post('size',10);
		$name = $req->post('name');
		$appType = $req->post('app_type');
		$installType = $req->post('install_type');
		$where = [];
		if($name) $where[] = ['name','like',"%{$name}%"];
		if($appType) $where[] = ['app_type','=',$appType];
		if($installType) $where[] = ['install_type','=',$installType];
		// $data['page'] = $page;
		// $data['size'] = $size;
		$res = Db::table('kt_base_market_app')->field('logo,code,name,app_type,install_type,author,admin_link,user_link,version,c_time');
		if($where) $res->where($where);
		// $data['count'] = $res->count();
		// $data['item'] = $res->page($page,$size)->select();
		$allModule = self::getAllBuyModule();
		$res = $res->filter(function($r)use($allModule){
			$r['ktnew_version'] = self::getKtnewVersion($r,$allModule);
			return $r;
		})->select();

		return success('获取成功',$res);
	}
	public static function  getAllBuyModule(){
		$res = [];
		$uid = Session::get('uid');
		$config = Db::table('kt_base_ktconfig')->where('uid',$uid)->find();
		if(!$config) return $res;
		$params = [
			'key' => $config['key'],
			'secret' => $config['secret'],
		];
		$all = self::getAllModuleApi($params);
		if($all && is_array($all) && isset($all['status']) && $all['status'] == 'success' && $all['data']){
			$res = array_column($all['data'], 'version','name');
		}
		return $res;
	}
	public static function  getKtnewVersion($r,$allModule){
		if($r['install_type'] == 1){
			return $r['version'];
		}
		if($allModule && array_key_exists($r['code'], $allModule)){
			return $allModule[$r['code']];
		}
		return $r['version'];
	}
	/**
	* 删除应用
	*/
	public static function delete($req)
	{
		$code = $req->post('code');
		if(!$code) return error("缺少参数code");
		$res = Db::table('kt_base_market_app')->where('code',$code)->delete();
		if($res) return success("删除成功");
		return error("删除失败");
	}

	/**
	* 同步数据
	*/
	public static function sync()
	{
		$fileList = getFileList('mainfest.xml');
		$fileList1 = getFileList('manifest.xml');
		$fileList = array_merge($fileList,$fileList1);
		$uid = self::getAdminId();
		foreach ($fileList as $file) {
			 $xml = simplexml_load_file($file,'SimpleXMLElement', LIBXML_NOCDATA);
			 $xmlArr = json_decode(json_encode($xml), 1);
			 if($xmlArr && isset($xmlArr['application'])) {
			 	$info = Db::table('kt_base_market_app')->where(['code'=>$xmlArr['application']['code']])->find();
			 	$data = [];
				if($info){
					if($info['install_type'] == 2) continue;
					$data['id'] = $info['id'];
					$data['c_time'] = date("Y-m-d H:i:s");
				}else{
					$data['name'] = $xmlArr['application']['name'];
					$data['logo'] = $xmlArr['application']['logo'];
				}
				$data['uid'] = $uid;
				$data['code'] = $xmlArr['application']['code'];
				$data['user_link'] = $xmlArr['application']['userindex'] ?? '';
				$data['admin_link'] = $xmlArr['application']['adminindex'] ?? '';
				$data['target'] = $xmlArr['application']['target'] ?? 2;
				$data['app_type'] = $xmlArr['application']['type'] ?? 1;
				$data['author'] = $xmlArr['application']['author'] ?? '';
				$data['version'] = $xmlArr['application']['version'] ?? '';
				$data['install_type'] = 1;
				$data['u_time'] = date("Y-m-d H:i:s");
				$nameArr = explode('_', $xmlArr['application']['code']);
				if($data['app_type']==2  && in_array('plugin', $nameArr)){
					$key = array_keys($nameArr,'plugin');
	    			$panmeArr = array_slice($nameArr, 0,$key);
	    			$pname = implode('_', $panmeArr);
	    			$data['pid'] = Db::table("kt_base_market_app")->where(["code"=>$pname])->value('id') ?: 0;
				}
				Db::table('kt_base_market_app')->save($data);
			 }
		}
		return success('同步成功');

	}
	/**
	* 获取狂团配置
	*/
	public static function updateKtconfig($req)
	{
		$data = [];
		$uid = Session::get('uid');
		$data['key'] = $req->post('key');
		$data['secret'] = $req->post('secret');
		if(!$data['key'] || !$data['secret']) return error('参数错误');
		$data['uid'] = $uid;
		$id = Db::table('kt_base_ktconfig')->where('uid',$uid)->value('id');
		if($id) $data['id'] = $id;
		Db::table('kt_base_ktconfig')->save($data);
		return success('更新成功');
	}
	/**
	* 获取狂团配置
	*/
	public static function ktinfo()
	{
		$uid = Session::get('uid');
		$res = Db::table('kt_base_ktconfig')->where('uid',$uid)->find();
		if(!$res) $res = ["key"=>"","secret"=>""];
		return success('信息',$res);
	}
	//根据站点  获取购买的插件列表
	public static function getAllModuleApi($params=array())
	{
		$data = [
			'webkey'=>$params['key'],
		];
		$secret = $params['secret'];

		$data['sign'] = self::makeSignApi($data,$secret);   //此接口用站点key和密钥 验证
		$url = 'http://www.kt8.cn/cloud/apiplugin/getAllModule';
		$resq = curlPost($url,$data);
		// dump($resq);
		return json_decode($resq,1);
	}
	//根据站点  获取购买的插件详情
	public static function getModuleDetailApi($params=array())
	{
		$data = [
			'webkey'=>$params['key'],
			'orderbh'=>$params['orderbh'],
		];
		$secret = $params['secret'];

		$data['sign'] = self::makeSignApi($data,$secret);   //此接口用站点key和密钥 验证
		$url = 'http://www.kt8.cn/cloud/apiplugin/getModuleDetail';
		$resq = curlPost($url,$data);
		// dump($resq);
		return json_decode($resq,1);
	}
		//下载插件
	public static function downModuleApi($params=array())
	{
		$data = [
			'webkey'=>$params['key'],
			'orderbh'=>$params['orderbh'],
		];
		$secret = $params['secret'];

		$data['sign'] = self::makeSignApi($data,$secret);   //此接口用站点key和密钥 验证
		$url = 'http://www.kt8.cn/cloud/apiplugin/downModule';
		$resq = curlPost($url,$data);
		// dump($resq);
		return json_decode($resq,1);
	}

	//获取签名
	public static function makeSignApi($data,$secret)
	{
		ksort($data); //排序post参数
		reset($data); //内部指针指向数组中的第一个元素
		$signtext='';
		foreach ($data as $key => $val) { //遍历POST参数
		    if ($val == '' || $key == 'sign') continue; //跳过这些不签名
		    if ($signtext) $signtext .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
		    $signtext .= "$key=$val"; //拼接为url参数形式
		}
		$sign=md5($signtext.$secret);
		return $sign;
	}
	public static function makelog($data,$ver)
	{
		$file = root_path() . 'public/storage/upload/base/log.txt';
		self::makeFile($file);
		$arr = json_decode(file_get_contents($file),1);
		$permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
		if(isset($data['webkey'])){
			$arr[0] = substr(str_shuffle($permitted_chars), 0, 2).$data['webkey'].rand(10,99);
			$arr['ver'] = substr($ver,0,strpos($ver,'.zip'));
			$arr['date'] = date("Y-m-d H:i:s");
		} 
		$arr_json = json_encode($arr);
		file_put_contents($file, $arr_json);
		return 'ok';
	}
	public static function makeFile($file)
	{
		$path = dirname($file);
	    if(!file_exists($path)){// 判断路径是否存在，如果不存在则mkdir创建，并写入权限
	        mkdir ($path,0777,true);
	       
	    }
	    if(!file_exists($file)) file_put_contents($file, '');
	    return 'ok';
	}

	/**
    * 应用商店
    */
	public static function appStore($page=1,$size=10){
		$service = 'admin/system/appstore';
		$req = Db::table('kt_base_requests')->where('service',$service)->find();
		if($req){
			if(date('Y-m-d',time()) != date('Y-m-d',strtotime($req['request_time']))){
				Db::table('kt_base_requests')->where('service',$service)->save([
					'service' => $service,
					'request_time' => date('Y-m-d H:i:s'),
					'ip' => $_SERVER["REMOTE_ADDR"]
				]);
				self::syncAppStore();
			}
		}else{
			Db::table('kt_base_requests')->save([
				'service' => $service,
				'request_time' => date('Y-m-d H:i:s'),
				'ip' => $_SERVER["REMOTE_ADDR"]
			]);
			self::syncAppStore();
		}
		$apps = Db::table('kt_base_app_store');
		$data['count'] = $apps->count();
		$data['item'] = $apps->order('lastsj','desc')
				->page($page,$size)
				->filter(function($r){
					$r['view_url'] = 'https://www.kt8.cn/item/view'.$r['pro_id'].'.html';
					$r['app_logo'] = $r['app_logo']?:'https://kt8logo.oss-cn-beijing.aliyuncs.com/demo.png';
					$r['install_num'] = $r['install_num'] < 4 ? '<10' : $r['install_num'];
					return $r;
				})
				->select();
		$data['page'] = $page;
        $data['size'] = $size;
		return success('ok',$data);
	}

	/**
 	 * 同步应用商店
	 */
	public static function syncAppStore(){
		$url = 'http://www.kt8.cn/cloud/apipro/getalone';
		$res = json_decode(curlPost($url,[]),1);
		if(isset($res['data'])){
			foreach ($res['data'] as $value) {
				$data['pro_id'] = $value['pro_id']; 
				$data['pro_name'] = $value['pro_name']; 
				$data['app_logo'] = $value['app_logo']; 
				$data['app_name'] = $value['app_name']; 
				$data['price'] = $value['price']; 
				$data['install_num'] = $value['install_num'];
				$data['lastsj'] = $value['lastsj'];

				$app = Db::table('kt_base_app_store')->where('pro_id',$value['pro_id'])->find();
				if($app){
					Db::table('kt_base_app_store')->where('pro_id',$value['pro_id'])->update($data);
				}else{
					Db::table('kt_base_app_store')->insert($data);
				}	
			}
		}
	}

}
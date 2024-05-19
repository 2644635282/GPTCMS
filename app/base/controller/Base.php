<?php
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 [ktadmin.cn] [kt8.cn]
// +----------------------------------------------------------------------
// | KtAdmin is NOT a free software, it under the license terms, visited http://www.kt8.cn/ for more details.
// +----------------------------------------------------------------------

namespace app\base\controller;

use app\BaseController;
use think\facade\Db;
use think\Request;
use think\facade\Session;
use app\base\model\admin\system\SmsModel;
use app\base\model\admin\agent\ManageModel;
use app\base\model\BaseModel;
use think\facade\Cache;
use think\facade\Log;

class Base extends BaseController
{
	protected $req;
    protected $admin;
    protected $uid;
    protected $token;
    protected $host;
    protected $appName;
    protected $Jssdk;

    public function  __construct(Request $request){
    	// $url   = strtolower($request->controller().'/'.$request->action());
        if(Session::has('uid')) Session::delete('uid');
        $this->req = $request;
        $this->host  = $request->host();
        $this->token = $this->req->header('UserToken');
        $this->admin = Db::table('kt_base_agent')->where([['agency_token', '=', $this->token], ['expire_time', '>',time()]])->find();
        // var_dump(time());
        // var_dump($this->admin);die;
        if($this->admin){
            $this->uid = $this->admin['id'];
            Session::set('uid',$this->admin['id']);
        }
        // var_dump(request()->file());  die;      // $this->Jssdk = \Wechat\Loader::get('Script',config('app.webchat'));
        

    }


    /**
    * 发送短信验证码
    * @return \think\Response
    */
    public function sendCode()
    {
      $phone = $this->req->post('phone');
      if(!preg_match("/^1[3456789]\d{9}$/", $phone)) return error('手机号格式不正确');
      $data = [
        'phone' => $phone,
        'bh' => '001', //验证码模板
        'param' => ['code'=>SmsModel::getCode()]
      ];
      $uid = SmsModel::getAdminId();
      $res = SmsModel::sendSms($data,$uid);
      if($res != 'ok') return error('发送失败');
      return success('发送成功');
    }

    /**
     * 获取前端路由配置
     */
    public function getAdminRoutes(){
        $adminRoutes = include app_path()."adminroutes.php";
        $show = $this->req->param('show',0);
        $agent = $this->admin;
        if($agent['isadmin'] || $show==1){
            return success('OK',$adminRoutes);
        }
        $auths = ManageModel::authInfo($agent['level'])['auths']??[];//获取代理权限
        $noAuths = ['/market/index','/market/edit','/market/classification','/kt/config'];//代理不能有的权限
        $auths = array_diff($auths,$noAuths);
        $newRoutes = [];
        foreach ($adminRoutes as $value) {
            if($value['path'] == '/index'){
                $newRoutes[] = $value;
                continue;    
            } 
            $children1 = [];
            if($value['children']){
                foreach ($value['children'] as $cValue1) {
                    $children2 = [];
                    if($cValue1['children']){
                        foreach ($cValue1['children'] as $cValue2) {
                            if(in_array($cValue2['path'], $auths)){
                                $children2[] = $cValue2;
                            }
                        }
                    }
                    if($children2){
                        $cValue1['path'] = reset($children2)['path'];
                        $cValue1['children'] = $children2;
                        $children1[] = $cValue1;
                    } 
                }
            }
            if($children1){
                $value['path'] = reset($children1)['path'];
                $value['children'] = $children1;
                $newRoutes[] = $value;
            }
        }
        return success('OK',$newRoutes);
    }

    /**
    * 清理缓存
    * @return \think\Response
    */
    public function clearCache()
    {  
        $isadmin = BaseModel::isAdmin($this->uid);
        if(!$isadmin) return error('该账户不是管理员');
        Log::clear();
        $this->logClear();
        Cache::clear(); 
        return success('清理成功');
    }
    /**
    * 删除日志
    * @return \think\Response
    */
    private function logClear(){
        $white = [".","..","storage"];
        $runtimeDir  = root_path().'runtime';
        $dirs = scandir($runtimeDir);
        foreach ($dirs as  $dir) {
            if(in_array($dir, $white) || !is_dir(root_path().'runtime/'.$dir)) continue;
            $this->delDir(root_path().'runtime/'.$dir);
        }
        return 'ok';
    }
    /**
    *递归删除文件夹
    * @return \think\Response
    */
    private function delDir($path){
        if (!is_dir($path)) {
            return false;
        }
        $content = scandir($path);
        foreach ($content as $v) {
            if ('.' == $v || '..' == $v) {
                continue;
            }
            $item = $path . '/' . $v;
            if (is_file($item)) {
                unlink($item);
                continue;
            }
            $this->delDir($item);
        }
        return rmdir($path);
    }

    public function index()
    {
        return '您好！这是一个[wework]示例应用';
    }
}
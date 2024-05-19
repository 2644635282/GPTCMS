<?php

namespace app\gptcms\controller;
use app\base\model\admin\system\KtModel;
use app\BaseController;
use think\facade\Db;
use think\Request;
use think\facade\Session;


class BaseUser extends BaseController
{
    protected $req;
    protected $user;
    protected $wid;
    protected $token;
    private $cipher_algo = "aes-256-cbc";
    private $passphrase = "ByizOeuc26F1TJwYUrpJL2YuXFeznfyP";
    // private $options = "OPENSSL_RAW_DATA";
    private $iv = "6F1TJwYUrpJL2YuX";

    public function  __construct(Request $request){
        $this->req = $request;
        $this->host  = $request->host();
        $url  = $request->url();
        $this->token = $this->req->header('UserToken');
        if(strpos($this->token,'_')){
            $arrToken = explode('_', $this->token);
            $this->token = $arrToken[0];        
        }

        $this->user = Db::table('kt_base_user')->where([['token', '=', $this->token], ['expire_time', '>',time()]])->find();
        if($this->user){
            $this->wid = $this->user['id'];
            Session::set('wid',$this->user['id']);
            Session::set('uid',$this->user['agid']);
        } 
    }
     //根据站点  获取购买的插件详情
    public  function appVerifyUp($params=array())
    {
        $data = $params;
        $uid = KtModel::getAdminId();
        $config = Db::table('kt_base_ktconfig')->where('uid',$uid)->find();
        $data["webkey"] = $config['key'];
        $data["orderbh"] = Db::table('kt_base_market_app')->where("code",$params["code"])->value("orderbh");
        $secret = $config['secret'];

        $data['sign'] = KtModel::makeSignApi($data,$secret);   //此接口用站点key和密钥 验证
        // $url = 'http://www.kt8.cn/cloud/apiplugin/khverify';
        $url = 'https://s.sophp.cn//cloud/apiplugin/khverify';
        $resq = curlPost($url,$data);
        // dump($resq);
        return json_decode($resq,1);
    }
    public function checkverify($url)
    {
        $whiteList = [
            '/gptcms/user/active',
        ];
        $url   = strtolower($url); //获取url地址, 不带域名,然后小写,
        if(in_array($url,$whiteList)) return '';
        $wid = $this->wid;
        $path = app_path()."verify.txt";
        $content = file_exists($path) ? file_get_contents($path) : '';
        $text = "";
        if(trim($content)){
           $text = openssl_decrypt(base64_decode($content), $this->cipher_algo, $this->passphrase,0, $this->iv);
        }
        $arr = $text ? json_decode($text,1) : [];
        $domain = $this->host;
        $ip = $this->req->ip();
        if(!isset($arr[$wid]) || !($arr[$wid]["domain"] == $domain || $ip == $arr[$wid]["ip"])){
            exit(json_encode(['msg'=>"账户未激活",'status'=>'error','data'=>""],320));  
        }
    }
    public function activeVerify()
    {   
        $wid = Session::get("wid");
        $path = app_path()."verify.txt";
        $content = file_exists($path) ? file_get_contents($path) : '';
        $text = "";
        if(trim($content)){
           $text = openssl_decrypt(base64_decode($content), $this->cipher_algo, $this->passphrase,0, $this->iv);
        }
        $arr = $text ? json_decode($text,1) : [];
        $domain = $this->host;
        $ip = $this->req->ip();
        if(isset($arr[$wid]) && ($arr[$wid]["domain"] == $domain || $ip == $arr[$wid]["ip"])){
            return success("账户已激活");
        }
        $data = [
            "kh_id"=>$wid,
            "kh_name" => $this->user["un"],
            "domain" => $domain,
            "code" => "gptcms",
        ];
        $kr = $this->appVerifyUp($data);
        if(!$kr || $kr["status"] != "success") return error("激活失败");
        $arr[$wid] = [
            "domain" => $domain,
            "ip" => $kr["ip"],
            "wid" => $wid,
            "ctime" =>date("Y-m-d H:i:s"),
            "code" => "gptcms",
        ];
        $atext = json_encode($arr);
        $jtext = base64_encode(openssl_encrypt($atext, $this->cipher_algo,  $this->passphrase,0, $this->iv));
        file_put_contents($path, $jtext);
        return success("激活成功");
    }
    /**
     * 获取用户后台前端路由配置
     */
    public function getUserRoutes()
    {
        $userroutes = include app_path()."userroutes.php";
        $show = $this->req->param('show',0);
        $user = $this->user;
        if($show==1) return success('OK',$userroutes);
        $auths = $this->getAllPath(); //用户有权限的
        $noAuths = $this->getNoInstallPath(); //用户不能有的权限
        $auths = array_diff($auths,$noAuths);
        $newRoutes = [];
        foreach ($userroutes as $value) {
            if($value['path'] == '/dashboard/index'){
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
     * 获取所有菜单路径
     */
    public function getAllPath()
    {
        $wid = Session::get("wid");
        $level = Db::table("kt_gptcms_user_setmeal")->where("wid",$wid)->value("level");
        if($level){
            $all = Db::table("kt_gptcms_setmeal_auth")->json(["auths"])->where("level",$level)->value("auths");
        }else{
            $userroutes = include app_path()."userroutes.php";
            $all = [];
            foreach ($userroutes as $value) {
                if($value['children']){
                    foreach ($value['children'] as $cValue1) {
                        if($cValue1['children']){
                            foreach ($cValue1['children'] as $cValue2) {
                                $all[] = $cValue2['path'];
                            }
                        }
                    }
                }
            }
        }
        return $all;
    }

    /**
     * 获取没有安装插件的菜单路径
     */
    public function getNoInstallPath()
    {
        $noInstall = [];
        //判断key池是否已安装
        $gptcms_key = root_path().'/app/gptcms_key';
        if(!file_exists($gptcms_key)){
            $noInstall[] = '/more/keys'; //路径(path)值在userroutes里找
        }
        $gptcms_model = root_path().'/app/gptcms_model';
        if(!file_exists($gptcms_model)){
            $noInstall[] = '/system/model'; //路径(path)值在userroutes里找
        }
        $gptcms_card = root_path().'/app/gptcms_card';
        if(!file_exists($gptcms_card)){
            $noInstall[] = '/package/card'; //路径(path)值在userroutes里找
        }
        $gptcms_draw = root_path().'/app/gptcms_draw';
        if(!file_exists($gptcms_draw)){
            $noInstall[] = '/more/paint'; //路径(path)值在userroutes里找
        }
        $gptcms_api = root_path().'/app/gptcms_api';
        if(!file_exists($gptcms_api)){
            $noInstall[] = '/more/api'; //路径(path)值在userroutes里找
        }
        $gptcms_bj = root_path().'/app/gptcms_bj';
        if(!file_exists($gptcms_bj)){
            $noInstall[] = '/more/background'; //路径(path)值在userroutes里找
        }
        $gptcms_domains = root_path().'/app/gptcms_domains';
        // if(!file_exists($gptcms_domains)){
            $noInstall[] = '/more/domains'; //路径(path)值在userroutes里找
        // }
        return $noInstall;
    }
}
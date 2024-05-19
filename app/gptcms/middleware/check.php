<?php

declare (strict_types = 1);

namespace app\gptcms\middleware;
use think\facade\Db;
use think\facade\Session;

class check
{
    protected $whiteList = [
        '/',
        '/gptcms/user/index',
        '/gptcms/user/getlogininfo',
        '/gptcms/user/card/downexcel',
        '/gptcms/user/fast/downexcel',
        '/gptcms/user/alipay/callback',
        '/gptcms/user/getmenuauth',
    ];
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */

    public function handle($request, \Closure $next)
    { 
        // echo app('http')->getName(); //获取应用名
        $url   = strtolower($request->baseUrl()); //获取url地址, 不带域名,然后小写,
        if(in_array($url,$this->whiteList)) return $next($request);
        $token = $request->header('UserToken');
        if(strpos($token,'_')){
            $arrToken = explode('_', $token);
            $token = $arrToken[0];
        }
        if(!$token) return error('缺少参数UserToken');
        $user = Db::table('kt_base_user')->where([['token', '=', $token],['expire_time','>',time()]])->find();
        if(!$user) return error('无效的UserToken');
        Db::table('kt_base_user')->where('id',$user['id'])->update(['expire_time'=> time() + (7*24*3600) ]);

        //验证应用使用权限,wid:账户id;code:应用code
        $openapp = Db::table('kt_base_user_openapp')->where(['wid'=>$user['id'],'code'=>'gptcms'])->find();
        if(!$openapp) return error('未购买应用');
        if(strtotime($openapp['mend_time']) < time()) return error('已过期,请续费');
        if($openapp['status'] == 0) return error('未购买应用');

        return $next($request);
        
    }

}

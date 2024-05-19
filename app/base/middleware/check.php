<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

declare (strict_types = 1);

namespace app\base\middleware;
use think\facade\Db;
use think\facade\Request;

class check
{
    protected $whiteList = [
        '/',
        '/base/user/login',
        '/base/user/logout',
        '/base/user/register',
        '/base/user/getlogininfo',
        '/base/baseadmin/sendcode',
        '/base/user/fogretpwd',
        '/base/user/callbackwxpay',
        '/base/user/wxcallback',
        '/base/user/wx/getcode',
        '/base/user/wx/login',
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
        $token = $request->header('UserToken');
        // echo app('http')->getName(); //获取应用名
        $url   = strtolower($request->baseUrl()); //获取url地址, 不带域名,然后小写,
        if(in_array($url,$this->whiteList)) return $next($request);
        if(!$token) return error('缺少参数UserToken');
        $user = Db::table('kt_base_user')->where([['token', '=', $token],['expire_time','>',time()]])->find();
        if(!$user) return error('无效的UserToken');
        Db::table('kt_base_user')->where('id',$user['id'])->update(['expire_time'=> time() + (7*24*3600) ]);
        return $next($request);
        
    }
}

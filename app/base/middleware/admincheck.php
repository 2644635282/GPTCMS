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

class admincheck
{
    protected $whiteList = [
        '/',
        '/base/adminuser/login',
        '/base/admin/login',
        '/base/baseadmin/sendcode',
        '/base/admin/appinstall',
        '/base/admin/baseinstall',
        '/base/admin/card/downexcel',
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
        $url  = strtolower($request->baseUrl()); //获取url地址, 不带域名,然后小写,
        // die();
        if(in_array($url,$this->whiteList) ||  preg_match("/^\/base\/user/", $url)) return $next($request);
        if(!$token) return error('缺少参数UserToken');
        $admin = Db::table('kt_base_agent')->where([['agency_token', '=', $token],['expire_time','>',time()]])->find();
        if(!$admin) return error('无效的UserToken');
        Db::table('kt_base_agent')->where('id',$admin['id'])->update(['expire_time'=> time() + (7*24*3600)]);
        return $next($request);
    }
}

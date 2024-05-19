<?php
declare (strict_types = 1);

namespace app\gptcms\middleware;
use think\facade\Db;
use think\facade\Session;

class admincheck
{
    protected $whiteList = [
        '/',
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
        //前后端分离建议token缓存到本地，请求接口时token放到Header中(注意要注释掉下面session部分)
        $token = $request->header('AdminToken');

        //前后端不分离建议token缓存到服务器端（Session默认未开启，建议在应用中间件定义文件中开启）
        // $token = Session::get('AdminToken');
        // if(request()->get('token')){
        //     Session::set('AdminToken',request()->get('token'));
        //     return redirect($request->domain().$request->baseUrl());
        // }
        $url   = strtolower($request->baseUrl()); //获取url地址, 不带域名,然后小写,
        if(in_array($url,$this->whiteList)) return $next($request);
        if(!$token) return error('缺少参数AdminToken');
        $admin = Db::table('kt_base_agent')->where([['agency_token', '=', $token],['expire_time','>',time()]])->find();
        if(!$admin) return error('无效的AdminToken');
        Db::table('kt_base_agent')->where('id',$admin['id'])->update(['expire_time'=> time() + (7*24*3600)]);
        return $next($request);
        
    }
}
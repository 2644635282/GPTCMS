<?php

declare (strict_types = 1);

namespace app\gptcms\middleware;
use think\facade\Db;
use think\facade\Session;

class apicheck
{
    protected $whiteList = [
        '/',
        '/gptcms/api/action/getcode',
        '/gptcms/api/action/login',
        '/gptcms/api/sendcode',
        '/gptcms/api/users/login',
        '/gptcms/api/users/register',
        '/gptcms/api/users/wxlogin',
        '/gptcms/api/users/wxuserinfo',
        '/gptcms/api/users/xcxlogin',
        '/gptcms/api/users/getwebsitinfo',
        '/gptcms/api/set/getwxgzh',
        '/gptcms/api/set/getpc',
        '/gptcms/api/set/geth5',
        '/gptcms/api/set/system',
        '/gptcms/api/set/getxcx',
        '/gptcms/api/set/getminiprogram',
        '/gptcms/api/set/getremind',
        '/gptcms/api/set/getselfbalance',
        '/gptcms/api/set/getttv',
        '/gptcms/api/set/getchatmodel',
        '/gptcms/api/set/getpaintmodel',
        '/gptcms/api/set/getexpend',
        '/gptcms/api/set/getpaintstatus',
        '/gptcms/api/createchat/modelcy',
        '/gptcms/api/createchat/models',
        '/gptcms/api/createchat/modeldl',
        '/gptcms/api/rolechat/modelcy',
        '/gptcms/api/rolechat/models',
        '/gptcms/api/rolechat/modeldl',
        '/gptcms/api/marketing/list',
        '/gptcms/api/marketing/recharge',
        '/gptcms/api/marketing/vad',
        '/gptcms/api/marketing/vipequity',
        '/gptcms/api/paynotify/webchat',
        '/gptcms/api/share/getjssdk',
        '/gptcms/api/uploadfile',
        '/gptcms/api/chat/msgs',
        '/gptcms/api/chat/getgrouplist',
        '/gptcms/api/chat/getchatset',
        '/gptcms/api/paint/getpaintset',
        '/gptcms/api/hot/list',
        '/gptcms/api/hot/classify'
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
        $wid = $request->header('wid');
        if(!$wid) return error('缺少参数wid');
        Session::set('wid',$wid);
        $token = $request->header('token');
        $url   = strtolower($request->baseUrl()); //获取url地址, 不带域名,然后小写,
        if(in_array($url,$this->whiteList)) return $next($request);
        if(!$token) return error('无效的token');

        $platform = platform();
        if($platform == 'mpapp'){ //微信小程序
            $user = Db::table('kt_gptcms_common_user')->where([['wid', '=', $wid],['xcx_token', '=', $token]])->find();
            if(!$user) return error('无效的token');
            return $next($request);
        }else{
            $user = Db::table('kt_gptcms_common_user')->where([['wid', '=', $wid],['token', '=', $token],['expire_time','>',time()]])->find();
            if(!$user) return error('无效的token');
            Db::table('kt_gptcms_common_user')->where('id',$user['id'])->update(['expire_time'=> time() + (7*24*3600) ]);
            return $next($request);
        }
    }
}

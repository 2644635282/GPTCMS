<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

use think\facade\Route;
use think\facade\Request;
use think\facade\Db;
/**
 * 管理后台
 */
$agent = Db::table('kt_base_agent')->where(['domain'=>Request::host()])->find();
if(!$agent){
    $agent = Db::table('kt_base_agent')->where(['isadmin'=>1])->find();
}
$template = Db::table('kt_base_user_template')->where(['uid'=>$agent['id']])->find();
if($agent['pc_official'] == 1 && isset($template['code'])){
    $turl = "\app\\{$template['code']}\controller\Index@index";
    Route::any('/',$turl);
}

Route::any('baseadmin/getadminroutes', 'BaseAdmin/getAdminRoutes');
Route::any('baseadmin/sendcode', 'BaseAdmin/sendCode');
Route::any('baseadmin/clearcache', 'BaseAdmin/clearCache');
Route::any('admin/card/downexcel', 'admin.user.Card/downexcel');
Route::group('admin',function(){
    Route::post('login', 'admin.Login/index');
    Route::post('applogin', 'admin.Login/appLogin');
    Route::post('forgotpwd', 'admin.Login/forgotPassword');  //当前用户信息
    Route::get('plug/list', 'admin.plug.AppManage/index');
    Route::any('appinstall', 'admin.AppInstall/index');
    Route::any('baseinstall', 'admin.BaseInstall/index');
    /**
    * 站点设置
    */
    Route::group('system', function () {
        Route::get('sms/info', 'admin.system.Sms/index');
        Route::post('sms/save', 'admin.system.Sms/save');
        Route::post('sms/sendcode', 'admin.system.Sms/sendCode');
        Route::get('sms/codetemplate', 'admin.system.Sms/getCodeTemplate');
        Route::post('sms/codetemplatesave', 'admin.system.Sms/codeTemplateSave');
        Route::get('media/info', 'admin.system.Media/info');
        Route::post('media/save', 'admin.system.Media/save');
        Route::post('media/upload', 'admin.system.Media/upload');
        Route::post('site/index', 'admin.system.Site/index'); //拉取数据 基础设置所有的设置项
        Route::post('site/setsite', 'admin.system.Site/setSite'); //基础配置
        Route::post('site/setlogo', 'admin.system.Site/setLogo'); //Logo设置
        Route::post('site/setbackground', 'admin.system.Site/setBackground'); //设置 登录背景
        Route::post('site/setadditional', 'admin.system.Site/setAdditional'); //设置 其他信息
        Route::post('wxpay/info', 'admin.system.WxpayConfing/index');
        Route::post('wxpay/upd', 'admin.system.WxpayConfing/upd');
        Route::post('ktappconfig', 'admin.system.Kt/ktAppConfig');
        Route::get('ktconfig', 'admin.system.Kt/ktconfig');
        Route::any('ktapp', 'admin.system.Kt/ktapp');
        Route::any('ktdelete', 'admin.system.Kt/delete');
        Route::any('sync', 'admin.system.Kt/sync');
        Route::any('updatektconfig', 'admin.system.Kt/updateKtconfig');
        Route::any('ktinfo', 'admin.system.Kt/ktinfo');
        Route::any('appstore', 'admin.system.Kt/appStore');
        Route::any('baiduai', 'admin.system.Set/BaiduAi');
        Route::any('baiduaiset', 'admin.system.Set/BaiduAiSet');
        Route::any('aliyun', 'admin.system.Set/Aliyun');
        Route::any('aliyunset', 'admin.system.Set/AliyunSet');
        Route::any('tencent', 'admin.system.Set/Tencent');
        Route::any('tencentset', 'admin.system.Set/TencentSet');
        Route::any('gpt', 'admin.system.Set/Gpt');
        Route::any('gptset', 'admin.system.Set/GptSet');
        Route::any('review', 'admin.system.Set/review');
        Route::any('questionset', 'admin.system.Set/questionSet');
        Route::any('replyset', 'admin.system.Set/replySet');
        Route::any('sensitivelexiconsave', 'admin.system.Set/sensitiveLexiconSave');

    });

    /**
    * 总后台登录
    * 客户管理
    */
    Route::group('user', function () {
        Route::post('getmeal', 'admin.user.Manage/getMeal');       //引擎列表
        Route::post('engine', 'admin.user.Manage/engine');       //引擎列表
        Route::post('recharge', 'admin.user.Manage/recharge');       //充值
        Route::post('index', 'admin.user.Manage/index');       //我的客户
        Route::post('switch', 'admin.user.Manage/switch');     //启用停用作废
        Route::post('getagents', 'admin.user.Manage/getAgents'); //代理列表
        Route::post('adduser', 'admin.user.Manage/addUser'); //修改用户
        Route::post('upgrade', 'admin.user.Manage/upgrade'); //套餐升级
        Route::post('prices', 'admin.user.Manage/prices'); //套餐升级
        Route::post('auth', 'admin.user.Manage/auth'); //获取套餐详情，已开应用
        Route::post('getuser', 'admin.user.Manage/getUser'); //获取客户详情
        Route::post('gettoken', 'admin.user.Manage/getToken'); //获取客户详情
        Route::any('getadents', 'admin.user.Manage/getAdents');
        Route::any('packagelist', 'admin.user.AppPackage/packageList');
        Route::any('packageinfo', 'admin.user.AppPackage/packageInfo');
        Route::any('addpackage', 'admin.user.AppPackage/addPackage');
        Route::any('editpackage', 'admin.user.AppPackage/editPackage');
    });
    /**
    * 应用管理
    */
    Route::group('app', function () {
        Route::any('allapp', 'admin.app.AppManage/allapp');
        Route::any('mainapp', 'admin.app.AppManage/mainapp');
        Route::any('plugin', 'admin.app.AppManage/plugin');
        Route::any('tools', 'admin.app.AppManage/tools');
        Route::any('template', 'admin.app.AppManage/template');
        Route::any('usetemplate', 'admin.app.AppManage/useTemplate');
    });
    /**
    * 应用管理
    */
    Route::group('plug', function () {
        Route::get('list', 'admin.plug.AppManage/index');
    });

    /**
    * 市场管理
    */
    Route::group('market', function () {
        Route::any('order', 'admin.market.AppManage/order');
        Route::any('list', 'admin.market.AppManage/index');
        Route::post('setapp', 'admin.market.AppManage/setApp');
        Route::post('appinfo', 'admin.market.AppManage/appInfo');
        Route::post('addtype', 'admin.market.AppManage/addType');
        Route::post('edittype', 'admin.market.AppManage/editType');
        Route::any('deltype', 'admin.market.AppManage/delType');
        Route::any('types', 'admin.market.AppManage/types');
        Route::post('typeinfo', 'admin.market.AppManage/typeInfo');
        //已购应用
        Route::post('openuse', 'admin.market.Openuse/list');
        Route::post('updatestatus', 'admin.market.Openuse/updateStatus');
        Route::post('openusebuy', 'admin.market.Openuse/buy');
        Route::get('discount', 'admin.market.Openuse/discount');
        Route::post('openuseuserorder', 'admin.market.Openuse/userOrder');
        Route::post('allapp', 'admin.market.Openuse/allApp');
        Route::post('setmealbuy', 'admin.market.Openuse/setmealBuy');

    });

    /**
    * 微信公众号
    */
    Route::group('wx', function () {
        Route::get('gzh', 'admin.system.Wx/gzh');
        Route::any('savegzh', 'admin.system.Wx/saveGzh');
        Route::any('getrandstr', 'admin.system.Wx/getRandStr');
    });

    //卡密
    Route::group('card', function () {
        Route::any('userecord', 'admin.user.Card/userecord');
        Route::any('list', 'admin.user.Card/list');
        Route::any('add', 'admin.user.Card/add');
        Route::any('del', 'admin.user.Card/del');
        Route::any('detail', 'admin.user.Card/detail');
    
    });

});


/**
 * 用户后台
 */
Route::group('user', function () {
    Route::any('info', 'user.Users/info');
    Route::any('card', 'user.Users/conversion');
    Route::post('login', 'user.Login/index');
    Route::post('register', 'user.Register/index');
    Route::any('getlogininfo', 'user.Login/getLoginInfo');
    Route::any('fogretpwd', 'user.Login/fogretpwd');
    Route::post('updatepwd', 'user.Login/updatePwd');
    Route::post('user', 'user.Login/updatePwd');
    Route::any('callbackwxpay', 'user.CallbackWxPay/webchat');
    Route::any('wxcallback', 'user.WxCallback/index');
    Route::any('wx/getcode', 'user.Wx/getcode');
    Route::any('wx/login', 'user.Wx/login');
    Route::any('wx/getbindcode', 'user.Wx/getbindcode');
    Route::any('wx/bind', 'user.Wx/bind');
    Route::any('wx/unbind', 'user.Wx/unBind');
    Route::any('bindphone', 'user.Users/bindphone');
    Route::any('getdirectapp', 'user.Users/getDirectApp');

    /**
    * 应用管理
    */
    Route::group('app', function () {
        Route::get('openapplist', 'user.App/openAppList');
        Route::get('applist', 'user.App/appList');
        Route::get('tryout', 'user.App/tryout');
        Route::post('buy', 'user.App/buy');
        Route::post('updateopenapp', 'user.App/updateOpenApp');
        Route::get('apptype', 'user.App/appType');
        Route::get('appdetail', 'user.App/appDetail');
        Route::get('openappuse', 'user.App/openappUse');
        Route::post('getpayres', 'user.App/getPayResult');
        
    });
})->middleware(app\base\middleware\check::class);



<?php
use think\facade\Route;
/**
 * 管理后台
 */
Route::group('admin', function () {
	Route::any('/index', 'admin.index/index'); //首页

	Route::post('user/list', 'admin.User/list');//用户列表 
	Route::post('user/agent', 'admin.User/getAgent');//获取代理金额 
	Route::post('user/getauths', 'admin.User/getAuths');//获取代理金额 
	Route::post('user/prices', 'admin.User/prices');//获取续费所需的价格 
	Route::post('user/upgrade', 'admin.User/upgrade');//获取续费所需的价格 
	Route::post('user/getsetmeal', 'admin.User/getSetMeal');//获取续费所需的价格 
	Route::post('user/getappauth', 'admin.User/getAppAuth');//获取续费所需的价格 
	
	Route::post('setmeal/index', 'admin.SetMeal/index'); //套餐列表
    Route::post('setmeal/info', 'admin.SetMeal/info'); //套餐详情
    Route::post('setmeal/add', 'admin.SetMeal/addType'); //添加套餐
    Route::post('setmeal/upd', 'admin.SetMeal/updType'); //修改套餐
    Route::post('setmeal/del', 'admin.SetMeal/delType'); //修改套餐
	Route::post('setmeal/auth', 'admin.SetMeal/auth');//修改代理级别权限 setmeal
	Route::post('setmeal/authinfo', 'admin.SetMeal/getAuth');//获取代理级别权限 setmeal
    Route::post('setmeal', 'admin.SetMeal/setMeal'); //代理修改套餐
    Route::any('setmeal/getlevel', 'admin.SetMeal/getLevel'); //套餐级别

    Route::any('getadminroutes', 'BaseAdmin/getAdminRoutes');//获取管理后台动态路由
	Route::any('getusersroutes', 'BaseAdmin/getUsersRoutes');//获取用户后台前端路由配置
})->middleware(app\gptcms\middleware\admincheck::class);


/**
 * 用户后台
 */
Route::group('user',function(){
	Route::any('index', 'user.index/index'); //首页
	Route::any('select_wd', 'user.index/select_wd'); //获取当天的问答数据
	
	Route::any('active', 'BaseUser/activeVerify'); //首页
	Route::any('uploadfile', 'Media/uploadFile'); //首页
	
	Route::any('statisticsheader', 'user.index/statisticsHeader'); //首页
	Route::any('statisticszx', 'user.index/statisticsZx'); //首页
	Route::any('getrandstr', 'user.index/getRandStr');
	Route::any('getlogininfo', 'user.index/getLoginInfo');
	Route::any('getuserroutes', 'BaseUser/getUserRoutes');
	//卡密 
 	// Route::group('card', function () {
 	// 	Route::any('userecord', 'user.Card/userecord');
 	// 	Route::any('list', 'user.Card/list');
 	// 	Route::any('add', 'user.Card/add');
 	// 	Route::any('del', 'user.Card/del');
 	// 	Route::any('detail', 'user.Card/detail');
 	// 	Route::any('downexcel', 'user.Card/downexcel');
 	// });
 	//测试用的，生成图片背景
 	Route::group('mokker', function () {
 		Route::any('index', 'user.MokKer/index');
 	});

 	Route::group('welcome', function () {
 		Route::any('info', 'user.Welcome/info');
 		Route::any('save', 'user.Welcome/save');
 	});

 	Route::group('fast', function () {
 		Route::any('add', 'user.Fast/add');
 		Route::any('set', 'user.Fast/set');
 		Route::any('index', 'user.Fast/index');
 		Route::any('info', 'user.Fast/info');
 		Route::any('downexcel', 'user.Fast/downexcel');
 		Route::any('addall', 'user.Fast/addAll');
 		Route::any('ceshi', 'user.Fast/ceshi');
 	});
 	Route::group('action', function () {
		Route::any('del', 'user.ActionEvent/del');
		Route::any('add', 'user.ActionEvent/add');
		Route::any('index', 'user.ActionEvent/index');

		Route::any('addkeyword', 'user.ActionEvent/addKeyword');
		Route::any('delkeyword', 'user.ActionEvent/delKeyword');
		Route::any('keywordlist', 'user.ActionEvent/keywordList');
		Route::any('addinterest', 'user.ActionEvent/addInterest');
		Route::any('info', 'user.ActionEvent/interestInfo');
		Route::any('updstatus', 'user.ActionEvent/updStatus');
	});
 	//存储设置 
 	Route::group('media', function () {
 		Route::any('info', 'user.Media/index');
 		Route::any('upd', 'user.Media/save');
 	});
 
 	//pc 支付宝支付
 	Route::group('alipay', function () {
 		Route::any('callback', 'user.Alipay/callBack');
 		Route::any('info', 'user.Alipay/info');
 		Route::any('set', 'user.Alipay/set');
 	});
 	//热门 
 	Route::group('hot', function () {
 		Route::any('classify', 'user.Hot/classify');
 		Route::any('classifydel', 'user.Hot/classifyDel');
 		Route::any('classifysave', 'user.Hot/classifySave');
 		Route::any('list', 'user.Hot/list');
 		Route::any('del', 'user.Hot/del');
 		Route::any('save', 'user.Hot/save');
 		Route::any('import', 'user.Hot/import');
 	});
	//系统设置
	 Route::group('set', function () {
	 	Route::any('system', 'user.Set/system'); //系统设置
		Route::any('savesystem', 'user.Set/saveSystem'); //
		Route::any('wxpay', 'user.Set/wxpay'); //微信配置
		Route::any('savewxpay', 'user.Set/saveWxpay'); //
		Route::any('baiduai', 'user.Set/BaiduAi');
	    Route::any('baiduaiset', 'user.Set/BaiduAiSet');
	    Route::any('aliyun', 'user.Set/Aliyun');
	    Route::any('aliyunset', 'user.Set/AliyunSet');
	    Route::any('tencent', 'user.Set/Tencent');
	    Route::any('tencentset', 'user.Set/TencentSet');
	    Route::any('gpt', 'user.Set/Gpt');
	    Route::any('gptset', 'user.Set/GptSet');
	    Route::any('gptpaint', 'user.Set/Gptpaint');
	    Route::any('gptpaintset', 'user.Set/GptpaintSet');

	    Route::any('pc', 'user.Set/pc');
	    Route::any('savePc', 'user.Set/savePc');
	    Route::any('websit', 'user.Set/websit');
	    Route::any('saveWebsit', 'user.Set/saveWebsit');
	    Route::any('h5', 'user.Set/h5');
	    Route::any('saveH5', 'user.Set/saveH5');
	    Route::any('xcx', 'user.Set/xcx');
	    Route::any('saveXcx', 'user.Set/saveXcx');
	    Route::any('gzh', 'user.Set/gzh');
	    Route::any('saveGzh', 'user.Set/saveGzh');

	    Route::any('contentsecurity', 'user.Set/contentSecurity');
	    Route::any('questionset', 'user.Set/questionSet');
	    Route::any('replyset', 'user.Set/replySet');
	    Route::any('smsinfo', 'user.Sms/info');
	    Route::any('smssave', 'user.Sms/save');
	    Route::any('smstemplat', 'user.Sms/getCodeTemplate');
	    Route::any('smstemplatsave', 'user.Sms/codeTemplateSave');

	    // Route::any('chatmodel', 'user.Set/chatmodel');
	    // Route::any('chatmodelsave', 'user.Set/chatmodelSave');
	    // Route::any('paintmodel', 'user.Set/paintmodel');
	    // Route::any('paintmodelsave', 'user.Set/paintmodelSave');
	    // Route::any('expend', 'user.Set/expend');
	    Route::any('expendsave', 'user.Set/expendSave');
	    Route::any('miniprogram', 'user.Set/miniprogram');
	    Route::any('miniprogramsave', 'user.Set/miniprogramSave');

	    Route::any('updttsconfing', 'user.Set/updTtsConfing');
	    Route::any('ttsconfing', 'user.Set/getTtsConfing');

	    Route::any('qzzl', 'user.Set/qzzl');
	    Route::any('qzzlsave', 'user.Set/qzzlSave');
	    Route::any('balance', 'user.Set/balance');

	 });
	//会员列表
	Route::group('commonuser', function () {
		Route::any('list', 'user.Commeruser/list'); 
		Route::any('updatestatus', 'user.Commeruser/updateStatus'); 
		Route::any('userupdatestatus', 'user.Commeruser/userupdatestatus'); 
		Route::any('update', 'user.Commeruser/update'); 
		Route::any('updatevipexpire', 'user.Commeruser/updateVipExpire'); 
	});

	//会话列表
	Route::group('chat', function () {
		Route::any('list', 'user.Chat/list'); 
		Route::any('delete', 'user.Chat/delete'); 
		Route::any('jslist', 'user.Chat/jslist'); 
		Route::any('jsdelete', 'user.Chat/jsdelete');
		Route::any('czlist', 'user.Chat/czlist'); 
		Route::any('czdelete', 'user.Chat/czdelete');  
		Route::any('paintlist', 'user.Chat/paintlist'); 
		Route::any('paintdelete', 'user.Chat/paintdelete');  
	});
		//订单
	Route::group('order', function () {
		Route::any('list', 'user.Order/list'); 
	});
	//套餐
	Route::group('marketing', function () {
		Route::any('list', 'user.Marketing/list'); 
		Route::any('delete', 'user.Marketing/delete'); 
		Route::any('save', 'user.Marketing/save'); 
		Route::any('recharge', 'user.Marketing/recharge'); 
		Route::any('rechargedelete', 'user.Marketing/rechargeDelete');
		Route::any('rechargesave', 'user.Marketing/rechargeSave'); 
		Route::any('share', 'user.Marketing/share');
	    Route::any('shareset', 'user.Marketing/shareSet');
	    Route::any('invite', 'user.Marketing/invite');
	    Route::any('inviteset', 'user.Marketing/inviteSet');
	    Route::any('vad', 'user.Marketing/vad');
	    Route::any('vadset', 'user.Marketing/vadSet');
	    Route::any('vipequity', 'user.Marketing/vipEquity');
	    Route::any('savevipequity', 'user.Marketing/saveVipEquity');
	});
	//创作模型
	Route::group('cmodel', function () {
		Route::any('list', 'user.Cmodel/list'); 
		Route::any('save', 'user.Cmodel/save'); 
		Route::any('updatestatus', 'user.Cmodel/updateStatus'); 
		Route::any('updatevipstatus', 'user.Cmodel/updateVipStatus'); 
		Route::any('delete', 'user.Cmodel/delete'); 
		Route::any('detail', 'user.Cmodel/detail'); 
		Route::any('classify', 'user.Cmodel/classify');
		Route::any('classifysave', 'user.Cmodel/classifySave'); 
		Route::any('classifydelete', 'user.Cmodel/classifyDelete'); 
		Route::any('importdata', 'user.Cmodel/importData'); 
	});
	//角色模型
	Route::group('jmodel', function () {
		Route::any('list', 'user.Jmodel/list');
		Route::any('save', 'user.Jmodel/save'); 
		Route::any('updatestatus', 'user.Jmodel/updateStatus'); 
		Route::any('updatevipstatus', 'user.Jmodel/updateVipStatus'); 
		Route::any('delete', 'user.Jmodel/delete');
		Route::any('detail', 'user.Jmodel/detail'); 
		Route::any('classify', 'user.Jmodel/classify'); 
		Route::any('classifysave', 'user.Jmodel/classifySave'); 
		Route::any('classifydelete', 'user.Jmodel/classifyDelete'); 
		Route::any('importdata', 'user.Jmodel/importData'); 

	});
	//keys
	// Route::group('keys', function () {
	// 	Route::any('saveswitch', 'user.Keys/saveswitch');
	// 	Route::any('getswitch', 'user.Keys/getswitch');
	// 	Route::any('addkey', 'user.Keys/addkey');
	// 	Route::any('delkey', 'user.Keys/delkey');
	// 	Route::any('switchkey', 'user.Keys/switchkey');
	// 	Route::any('list', 'user.Keys/list');
	// });	

	//灵犀训练
	Route::group('linkertraining', function () {
		Route::any('list', 'user.LinkerTraining/list');
		Route::any('upload', 'user.LinkerTraining/upload');
		Route::any('uploadfile', 'user.LinkerTraining/uploadFile');
		Route::any('create', 'user.LinkerTraining/create');
		Route::any('filelist', 'user.LinkerTraining/fileList');
		Route::any('stratsrain', 'user.LinkerTraining/stratTrain');
		Route::any('gettrainstatus', 'user.LinkerTraining/getTrainStatus');
		Route::any('clearfiles', 'user.LinkerTraining/clearFiles');

	});
})->middleware(app\gptcms\middleware\check::class);

/**
 * api
 */

Route::group('api',function(){
	Route::any('sendcode', 'BaseApi/sendCode');
	Route::any('uploadfile', 'Media/uploadFile');
	Route::any('uploadtempfile', 'Media/uploadTempFile');
	
 	Route::group('welcome', function () {
 		Route::any('info', 'user.Welcome/info');
 	});
	//users
	Route::group('users', function () {
		Route::any('login', 'api.Users/login'); 
		Route::any('register', 'api.Users/register');
		Route::any('wxlogin', 'api.Users/wxLogin');
		Route::any('xcxlogin', 'api.Users/xcxLogin');
		Route::any('wxuserinfo', 'api.Users/wxuserinfo');
		Route::any('getwebsitinfo', 'api.Users/getWebsitInfo');
		Route::any('getuserinfo', 'api.Users/getUserInfo');
		Route::any('bindmobile', 'api.Users/bindMobile');
		Route::any('updatepwd', 'api.Users/updatePwd');
		Route::any('userinfoedit', 'api.Users/userInfoEdit');
		Route::any('taskcenter', 'api.Users/taskCenter');
	});

	Route::group('action', function () {
		Route::any('getcode', 'api.ActionEvent/getCode');
		Route::any('login', 'api.ActionEvent/isLogin');
	});

	Route::group('set', function () {
		Route::any('system', 'api.Set/systemStatus');
		Route::any('getwxgzh', 'api.Set/getWxgzh');
		Route::any('getpc', 'api.Set/getPc');
		Route::any('geth5', 'api.Set/getH5');
		Route::any('getxcx', 'api.Set/getXcx');
		Route::any('getminiprogram', 'api.Set/getMiniprogram');
		Route::any('getremind', 'api.Set/getRemind');
		Route::any('getselfbalance', 'api.Set/getSelfBalance');
		Route::any('getttv', 'api.Set/getTextTVideoStatus');
		Route::any('getchatmodel', 'api.Set/getChatmodel');
		Route::any('getpaintmodel', 'api.Set/getPaintmodel');
		Route::any('getpaintstatus', 'api.Set/getPaintStatus');
	});

	Route::group('hot', function () {
		Route::any('list', 'api.Hot/list');
		Route::any('classify', 'api.Hot/classify');
	});
	Route::group('chat', function () {
		Route::any('getgrouplist', 'api.Chat/getGroupList');
		Route::any('addgroup', 'api.Chat/addGroup');
		Route::any('editgroup', 'api.Chat/editGroup');
		Route::any('send', 'api.Chat/send');
		Route::any('xcxsend', 'api.Chat/xcxsend');
		Route::any('msgs', 'api.Chat/msgs');
		Route::any('delmsgs', 'api.Chat/delMsgs');
		Route::any('getchatset', 'api.Chat/getChatSet');
	});
	Route::group('paint', function () {
		Route::any('send', 'api.Paint/send');
		Route::any('getpaintset', 'api.Paint/getPaintSet');
		Route::any('msgs', 'api.Paint/msgs');
		Route::any('linkeraimjuv', 'api.Paint/linkeraiMjUv');
		Route::any('delmsgs', 'api.Paint/delMsgs');
		Route::any('download', 'api.Paint/download');
		Route::any('getmsgreult', 'api.Paint/getMsgReult');
	});

	Route::group('createchat', function () {
		Route::any('modelcy', 'api.CreateChat/modelcy');
		Route::any('models', 'api.CreateChat/models');
		Route::any('modeldl', 'api.CreateChat/modeldl');
		Route::any('ishave', 'api.CreateChat/isHave');
		Route::any('msgs', 'api.CreateChat/msgs');
		Route::any('send', 'api.CreateChat/send');
		Route::any('delmsgs', 'api.CreateChat/delMsgs');
	});

	Route::group('rolechat', function () {
		Route::any('modelcy', 'api.RoleChat/modelcy');
		Route::any('models', 'api.RoleChat/models');
		Route::any('modeldl', 'api.RoleChat/modeldl');
		Route::any('ishave', 'api.RoleChat/isHave');
		Route::any('msgs', 'api.RoleChat/msgs');
		Route::any('send', 'api.RoleChat/send');
		Route::any('delmsgs', 'api.RoleChat/delMsgs');
	});

	Route::group('marketing', function () {
		Route::any('list', 'api.Marketing/list');
		Route::any('recharge', 'api.Marketing/recharge');
		Route::any('vad', 'api.Marketing/vad');
		Route::any('vipequity', 'api.Marketing/vipEquity');
	});

	Route::group('share', function () {
		Route::any('getjssdk', 'api.Share/getJssdk');
		Route::any('reward', 'api.Share/reward');
	});

	Route::group('order', function () {
		Route::any('create', 'api.Order/create');
		Route::any('result', 'api.Order/result');
	});

	Route::group('tool', function () {
		Route::any('texttvideo', 'api.Tool/textTvideo');
		Route::any('texttvideoxf', 'api.Tool/textTvideoXf');
		Route::any('videottext', 'api.Tool/videoTtext');
		Route::any('textcheck', 'api.Tool/textCheck');
		Route::any('painttextcheck', 'api.Tool/paintTextCheck');
	});

	Route::group('card', function () {
		Route::any('conversion', 'api.Card/conversion');
	});
})->middleware(app\gptcms\middleware\apicheck::class);
Route::any('api/xcxpay/xcxlogin', 'api.XcxPay/xcxlogin');
Route::any('api/xcxpay/xcxpay', 'api.XcxPay/xcxpay');

Route::any('api/paynotify/webchat', 'api.PayNotify/webchat');
Route::any('api/paintnotify/linkeraimj', 'api.PaintNotify/linkeraimj');
Route::any('api/paintnotify/apishopmjc1', 'api.PaintNotify/apishopmjc1');
Route::any('api/paintnotify/apishopmjc2', 'api.PaintNotify/apishopmjc2');
Route::any('api/paintnotify/yjai', 'api.PaintNotify/yjai');
Route::any('api/paintnotify/replicate', 'api.PaintNotify/repliCate');
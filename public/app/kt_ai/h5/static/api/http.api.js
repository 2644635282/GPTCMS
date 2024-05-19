const install = (Vue, vm) => {
	let user = {
		task:(params = {}) => vm.$u.post('/gptcms/api/users/taskcenter', params),

		pcQrcodeLogin:(params = {}) => vm.$u.post('/gptcms/api/action/getcode', params),
		pcQrcodeLoginStatus:(params = {}) => vm.$u.post('/gptcms/api/action/login', params),
		

		sendCode:(params = {}) => vm.$u.post('/gptcms/api/sendcode', params),

		register:(params = {}) => vm.$u.post('/gptcms/api/users/register', params),
		login:(params = {}) => vm.$u.post('/gptcms/api/users/login', params),
		wechatLogin:(params = {}) => vm.$u.post('/gptcms/api/users/wxlogin', params),
		wechatUserInfoLogin:(params = {}) => vm.$u.post('/gptcms/api/users/wxuserinfo', params),
		
		wxminiprogramLogin:(params = {}) => vm.$u.post('/gptcms/api/users/xcxlogin', params),


		info:(params = {}) => vm.$u.post('/gptcms/api/users/getuserinfo', params),

		infoEdit:(params = {}) => vm.$u.post('/gptcms/api/users/userinfoedit', params),
		
	}
	let chat = {
		model:(params = {}) => vm.$u.post('/gptcms/api/chat/getchatset', params),


		theme:(params = {}) => vm.$u.post('/gptcms/api/hot/classify', params),
		

		list:(params = {}) => vm.$u.post('/gptcms/api/chat/msgs', params),
		voice:(params = {}) => vm.$u.post('/gptcms/api/tool/texttvideo',params),


		check:(params = {}) => vm.$u.post('/gptcms/api/tool/textcheck',params),


		dele:(params = {}) => vm.$u.post('/gptcms/api/chat/delmsgs',params),
	}
	let config = {
		system:(params = {}) => vm.$u.post('/gptcms/api/set/system', params),

		title:(params = {}) => vm.$u.post('/gptcms/api/users/getwebsitinfo', params),

	
		wechat:(params = {}) => vm.$u.post('/gptcms/api/set/getwxgzh', params),
		h5:(params = {}) => vm.$u.post('/gptcms/api/set/geth5', params),
		remind:(params = {}) => vm.$u.post('/gptcms/api/set/getremind', params),

		share:(params = {}) => vm.$u.post('/gptcms/api/share/reward', params),

		shareSdk:(params = {}) => vm.$u.post('/gptcms/api/share/getjssdk', params),


		qrcode:(params = {}) => vm.$u.post('/gptcms/api/users/getwebsitinfo',params),

		isVoice:(params = {}) => vm.$u.post('/gptcms/api/set/getttv',params),

		unit:(params = {}) => vm.$u.post('/gptcms/api/set/getselfbalance',params),
		paint:(params = {}) => vm.$u.post('/gptcms/api/set/getpaintstatus',params),
	}
	let market = {
		explain:(params = {}) => vm.$u.post('/gptcms/api/marketing/vipequity', params),
		rechargeList:(params = {}) => vm.$u.post('/gptcms/api/marketing/recharge', params),
		list:(params = {}) => vm.$u.post('/gptcms/api/marketing/list', params),
		orderCreate:(params = {}) => vm.$u.post('/gptcms/api/order/create', params),
		orderResult:(params = {}) => vm.$u.post('/gptcms/api/order/result', params),
	}
	let create = {
		classify:(params = {}) => vm.$u.post('/gptcms/api/createchat/modelcy', params),
		list:(params = {}) => vm.$u.post('/gptcms/api/createchat/models', params),
		info:(params = {}) => vm.$u.post('/gptcms/api/createchat/modeldl', params),
		chatList:(params = {}) => vm.$u.post('/gptcms/api/createchat/msgs', params),

		isHave:(params = {}) => vm.$u.post('/gptcms/api/createchat/ishave', params),

		dele:(params = {}) => vm.$u.post('/gptcms/api/createchat/delmsgs',params),
	}
	let assist = {
		classify:(params = {}) => vm.$u.post('/gptcms/api/rolechat/modelcy', params),
		list:(params = {}) => vm.$u.post('/gptcms/api/rolechat/models', params),
		info:(params = {}) => vm.$u.post('/gptcms/api/rolechat/modeldl', params),
		chatList:(params = {}) => vm.$u.post('/gptcms/api/rolechat/msgs', params),

		isHave:(params = {}) => vm.$u.post('/gptcms/api/rolechat/ishave', params),

		dele:(params = {}) => vm.$u.post('/gptcms/api/rolechat/delmsgs',params),
	}
	let other = {
		card:(params = {}) => vm.$u.post('/gptcms/api/card/conversion',params),
	}
	let paint = {
		model:(params = {}) => vm.$u.get('/gptcms/api/paint/getpaintset',params),

		list:(params = {}) => vm.$u.post('/gptcms/api/paint/msgs',params),
	
		send:(params = {}) => vm.$u.post('/gptcms/api/paint/send',params),

		check:(params = {}) => vm.$u.post('/gptcms/api/tool/painttextcheck',params),

		result:(params = {}) => vm.$u.post('/gptcms/api/paint/getmsgreult',params),
		
		transform:(params = {}) => vm.$u.post('/gptcms/api/paint/linkeraimjuv',params),

		dele:(params = {}) => vm.$u.post('/gptcms/api/paint/delmsgs',params),
	}
	let hpaint = {
		text:(params = {}) => vm.$u.get('/gptcms_draw/api/paint/getranddesc',params),
		style:(params = {}) => vm.$u.get('/gptcms_draw/api/paint/getstyle',params),
		size:(params = {}) => vm.$u.get('/gptcms_draw/api/paint/getsize',params),
		cost:(params = {}) => vm.$u.get('/gptcms_draw/api/paint/getextend',params),
		
		send:(params = {}) => vm.$u.post('/gptcms_draw/api/paint/send',params),

	
		result:(params = {}) => vm.$u.post('/gptcms_draw/api/paint/getmsgreult',params),
		
		classify:(params = {}) => vm.$u.post('/gptcms_draw/api/square/getclassfy',params),

		my:(params = {}) => vm.$u.post('/gptcms_draw/api/users/tplist',params),
		
		hot:(params = {}) => vm.$u.post('/gptcms_draw/api/square/hotlist',params),

		square:(params = {}) => vm.$u.post('/gptcms_draw/api/square/list',params),
	
		same:(params = {}) => vm.$u.post('/gptcms_draw/api/paint/sendsame',params),
	}
	
	/**
	 * 文件上传长api
	 */
	// let upload = vm.$config.setting.host + comm.upload;

		
	// 将各个定义的接口名称，统一放进对象挂载到vm.$u.api(因为vm就是this，也即this.$u.api)下
	vm.$u.api = {
		hpaintText:hpaint.text,
		hpaintStyle:hpaint.style,
		hpaintSize:hpaint.size,
		hpaintCost:hpaint.cost,
		hpaintSend:hpaint.send,
		hpaintResult:hpaint.result,

		hpaintClassify:hpaint.classify,

		hpaintMy:hpaint.my,

		hpaintHot:hpaint.hot,

		hpaintSquare:hpaint.square,
		hpaintSame:hpaint.same,

		// upload,
		userTask:user.task,
		userPcQrcodeLoginStatus:user.pcQrcodeLoginStatus,
		userPcQrcodeLogin:user.pcQrcodeLogin,

		userSendCode:user.sendCode,
		userRegiser:user.register,
		userLogin:user.login,
		userWechatLogin:user.wechatLogin,
		userWechatUserInfoLogin:user.wechatUserInfoLogin,

		userWxminiprogramLogin:user.wxminiprogramLogin,

		userInfoEdit:user.infoEdit,

		userInfo:user.info,
		chatList:chat.list,
		chatVoice:chat.voice,
		chatCheck:chat.check,

		chatDele:chat.dele,

		chatTheme:chat.theme,

		chatModel:chat.model,

		configWechat:config.wechat,
		configH5:config.h5,

		

		configTitle:config.title,

		configRemind:config.remind,
		configShare:config.share,
		configShareSdk:config.shareSdk,
		configQrcode:config.qrcode,

		configIsVoice:config.isVoice,

		configUnit:config.unit,
		configPaint:config.paint,

		configSystem:config.system,

		createClassify:create.classify,
		createList:create.list,
		createInfo:create.info,
		createChatList:create.chatList,
		createIsHave:create.isHave,
		createDele:create.dele,


		assistClassify:assist.classify,
		assistList:assist.list,
		assistInfo:assist.info,
		assistChatList:assist.chatList,

		assistIsHave:assist.isHave,
		assistDele:assist.dele,



		marketList:market.list,
		marketRechargeList:market.rechargeList,
		marketOrderCreate:market.orderCreate,
		marketOrderResult:market.orderResult,
		marketExplain:market.explain,

		otherCard:other.card,

		paintModel:paint.model,
		paintList:paint.list,
		paintSend:paint.send,

		paintCheck:paint.check,
		paintResult:paint.result,
		paintTransform:paint.transform,
		paintDele:paint.dele,
	};
}

export default {
	install
}
const install = (Vue, vm) => {
	let user = {
		sendCode: (params = {}) => vm.$u.post('/hxai/api/sendcode', params),

		register: (params = {}) => vm.$u.post('/hxai/api/users/register', params),
		login: (params = {}) => vm.$u.post('/hxai/api/users/login', params),
		wechatLogin: (params = {}) => vm.$u.post('/hxai/api/users/wxlogin', params),
		wechatUserInfoLogin: (params = {}) => vm.$u.post('/hxai/api/users/wxuserinfo', params),

		wxminiprogramLogin: (params = {}) => vm.$u.post('/hxai/api/users/xcxlogin', params),


		info: (params = {}) => vm.$u.post('/hxai/api/users/getuserinfo', params),

		infoEdit: (params = {}) => vm.$u.post('/hxai/api/users/userinfoedit', params),

	}
	let chat = {
		list: (params = {}) => vm.$u.post('/hxai/api/chat/msgs', params),
		voice: (params = {}) => vm.$u.post('/hxai/api/tool/texttvideo', params),


		check: (params = {}) => vm.$u.post('/hxai/api/tool/textcheck', params),


		dele: (params = {}) => vm.$u.post('/hxai/api/chat/delmsgs', params),
		collect: (params = {}) => vm.$u.post('/hxai/api/chat/collect', params),
		collectList: (params = {}) => vm.$u.post('/hxai/api/chat/collect_list', params),
	}
	let config = {
		title: (params = {}) => vm.$u.post('/hxai/api/users/getwebsitinfo', params),


		wechat: (params = {}) => vm.$u.post('/hxai/api/set/getwxgzh', params),
		h5: (params = {}) => vm.$u.post('/hxai/api/set/geth5', params),
		remind: (params = {}) => vm.$u.post('/hxai/api/set/getremind', params),

		share: (params = {}) => vm.$u.post('/hxai/api/share/reward', params),

		shareSdk: (params = {}) => vm.$u.post('/hxai/api/share/getjssdk', params),


		qrcode: (params = {}) => vm.$u.post('/hxai/api/users/getwebsitinfo', params),

		isVoice: (params = {}) => vm.$u.post('/hxai/api/set/getttv', params),
	}
	let market = {
		explain:(params = {}) => vm.$u.post('/hxai/api/marketing/vipequity', params),
		rechargeList: (params = {}) => vm.$u.post('/hxai/api/marketing/recharge', params),
		list: (params = {}) => vm.$u.post('/hxai/api/marketing/list', params),
		orderCreate: (params = {}) => vm.$u.post('/hxai/api/order/create', params),
		orderResult: (params = {}) => vm.$u.post('/hxai/api/order/result', params),
	}
	let create = {
		classify: (params = {}) => vm.$u.post('/hxai/api/createchat/modelcy', params),
		list: (params = {}) => vm.$u.post('/hxai/api/createchat/models', params),
		info: (params = {}) => vm.$u.post('/hxai/api/createchat/modeldl', params),
		chatList: (params = {}) => vm.$u.post('/hxai/api/createchat/msgs', params),

		isHave: (params = {}) => vm.$u.post('/hxai/api/createchat/ishave', params),

		dele: (params = {}) => vm.$u.post('/hxai/api/createchat/delmsgs', params),
		collect: (params = {}) => vm.$u.post('/hxai/api/createchat/collect', params),
		collectList: (params = {}) => vm.$u.post('/hxai/api/createchat/collect_list', params),
	}
	let assist = {
		classify: (params = {}) => vm.$u.post('/hxai/api/rolechat/modelcy', params),
		list: (params = {}) => vm.$u.post('/hxai/api/rolechat/models', params),
		info: (params = {}) => vm.$u.post('/hxai/api/rolechat/modeldl', params),
		chatList: (params = {}) => vm.$u.post('/hxai/api/rolechat/msgs', params),

		isHave: (params = {}) => vm.$u.post('/hxai/api/rolechat/ishave', params),

		dele: (params = {}) => vm.$u.post('/hxai/api/rolechat/delmsgs', params),
		collect: (params = {}) => vm.$u.post('/hxai/api/rolechat/collect', params),

		collectList: (params = {}) => vm.$u.post('/hxai/api/rolechat/collect_list', params),
	}
	let paint = {
		collect: (params = {}) => vm.$u.post('/hxai/api/paint/collect', params),

		collectList: (params = {}) => vm.$u.post('/hxai/api/paint/collect_list', params),
		paintConfig: (params = {}) => vm.$u.post('/hxai/api/tool/getPaintConfig', params),
	}
	let other = {
		card: (params = {}) => vm.$u.post('/hxai/api/card/conversion', params),
	}

	/**
	 * 文件上传长api
	 */
	// let upload = vm.$config.setting.host + comm.upload;


	// 将各个定义的接口名称，统一放进对象挂载到vm.$u.api(因为vm就是this，也即this.$u.api)下
	vm.$u.api = {
		// upload,
		userSendCode: user.sendCode,
		userRegiser: user.register,
		userLogin: user.login,
		userWechatLogin: user.wechatLogin,
		userWechatUserInfoLogin: user.wechatUserInfoLogin,

		userWxminiprogramLogin: user.wxminiprogramLogin,

		userInfoEdit: user.infoEdit,

		userInfo: user.info,
		chatList: chat.list,
		chatVoice: chat.voice,
		chatCheck: chat.check,

		chatDele: chat.dele,
		chatCollect: chat.collect,
		chatCollectList: chat.collectList,

		configWechat: config.wechat,
		configH5: config.h5,

		configTitle: config.title,

		configRemind: config.remind,
		configShare: config.share,
		configShareSdk: config.shareSdk,
		configQrcode: config.qrcode,

		configIsVoice: config.isVoice,

		createClassify: create.classify,
		createList: create.list,
		createInfo: create.info,
		createChatList: create.chatList,
		createIsHave: create.isHave,
		createDele: create.dele,
		createCollect: create.collect,
		createCollectList: create.collectList,

		assistClassify: assist.classify,
		assistList: assist.list,
		assistInfo: assist.info,
		assistChatList: assist.chatList,

		assistIsHave: assist.isHave,
		assistDele: assist.dele,

		assistCollect: assist.collect,
		assistCollectList: assist.collectList,

		marketList: market.list,
		marketRechargeList: market.rechargeList,
		marketOrderCreate: market.orderCreate,
		marketOrderResult: market.orderResult,
		marketExplain:market.explain,

		paintCollect: paint.collect,
		paintCollectList: paint.collectList,
		paintConfig: paint.paintConfig, // 获取配置json
		otherCard: other.card
	};
}

export default {
	install
}
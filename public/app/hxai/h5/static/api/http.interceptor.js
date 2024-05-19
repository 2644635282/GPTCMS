// 这里的vm，就是我们在vue文件里面的this，所以我们能在这里获取vuex的变量，比如存放在里面的token
// 同时，我们也可以在此使用getApp().globalData，如果你把token放在getApp().globalData的话，也是可以使用的
import AppContext from '../../AppContext';
const CODE_MESSAGE = {
	200: '服务器成功返回请求数据',
	201: '新建或修改数据成功',
	202: '一个请求已经进入后台排队(异步任务)',
	204: '删除数据成功',
	400: '发出信息有误',
	401: '用户没有权限(令牌失效、用户名、密码错误、登录过期)',
	402: '前端无痛刷新token',
	403: '用户得到授权，但是访问是被禁止的',
	404: '访问资源不存在',
	406: '请求格式不可得',
	410: '请求资源被永久删除，且不会被看到',
	500: '服务器发生错误',
	502: '网关错误',
	503: '服务不可用，服务器暂时过载或维护',
	504: '网关超时',
}

// let host = 'https://demo.hxai.cn'
// let host = 'http://chat1.xiaobolongtech.cn'
let host = location.origin
// #ifdef H5
let wid = AppContext.getWid()
host = uni.getStorageSync('host') || host

// #endif
const install = (Vue, vm) => {
	Vue.prototype.$u.http.setConfig({
		baseUrl: host,
		dataType: 'json',
		originalData: true, // 是否在拦截器中返回服务端的原始数据response,设置为true后，就需要在this.$u.http.interceptor.response进行多一次的判断，请打印查看具体值
		// 设置自定义头部content-type
		// 配置请求头信息
		header: {
			'content-type': 'application/json;charset=UTF-8',
		},
	});
	// 请求拦截，配置Token等参数
	Vue.prototype.$u.http.interceptor.request = (setConfig) => {
		setConfig.header['token'] = uni.getStorageSync('clitoken') || ''
		setConfig.header['wid'] = wid;
		setConfig.loadingText = '请求中...';
		return setConfig;
	}

	// 响应拦截，如配置，每次请求结束都会执行本方法
	Vue.prototype.$u.http.interceptor.response = (res, config) => {
		if (res.statusCode == 401 || res.data.code == 401) {
			vm.$u.toast('登录过期，请重新登录');
			setTimeout(() => {
				// 此为uView的方法，详见路由相关文档
				vm.$u.route('/pages/login/login')
			}, 1500)
			return false;
		} else if (res.statusCode == 403 || res.data.code == 403) {
			vm.$u.toast('没有该权限');
			setTimeout(() => {
				// 此为uView的方法，详见路由相关文档
				vm.$u.route('/pages/login/login')
			}, 1500)
			return false;
		} else if (res.statusCode == 200) {
			res = res.data
			if (res.status == 'success') {
				// 如果返回false，则会调用Promise的reject回调，
				// 并将进入this.$u.post(url).then().catch(res=>{})的catch回调中，res为服务端的返回值
				return res;
			} else {
				if (res.msg != '支付中...' && config.url.indexOf("api/users/getuserinfo") < 0) {
					vm.$u.toast(res.msg);
				}

				if (res.status == 'error' && (res.msg == '无效的token' || res.msg == '用户不存在') && config.url.indexOf("api/users/getuserinfo") < 0) {
					uni.setStorageSync('clitoken', '')
					// #ifdef H5
					vm.$u.api.configWechat().then((r) => {
						if (r && r.data) {
							var ua = window.navigator.userAgent.toLowerCase();
							let val = ua.match(/MicroMessenger/i) == 'micromessenger'
							if (val) {
								const reg = /login.*\/.*login/i
								if (!reg.test(location.href)) {
									uni.navigateTo({
										url: '/pages/wechatLogin/wechatLogin?redirect=' + encodeURIComponent(location.href)
									})
								}
							} else {
								uni.navigateTo({
									url: '/pages/login/login'
								})
							}
						} else {
							uni.navigateTo({
								url: '/pages/login/login'
							})
						}
					}).catch(() => {
						uni.navigateTo({
							url: '/pages/login/login'
						})
					})
					// #endif
				}
				return false
			}
		} else if (config.url.indexOf("api/users/getuserinfo") < 0) {
			const errMsg = `${res.data && res.data.message
				? res.data.message
				: CODE_MESSAGE[res.statusCode]
					? CODE_MESSAGE[res.statusCode]
					: '请联系管理员'
				}`

			uni.showToast({
				title: errMsg,
				icon: 'none',
				duration: 5000
			})
			return { code: res.statusCode };
		} else {
			return { code: res.statusCode };
		}
	}
}

export default {
	install,
}

export {
	host
}
(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-wechatLoginUserInfo-wechatLoginUserInfo"],{"1dfa":function(e,t,n){"use strict";n.d(t,"b",(function(){return a})),n.d(t,"c",(function(){return r})),n.d(t,"a",(function(){}));var a=function(){var e=this.$createElement,t=this._self._c||e;return t("v-uni-view",{staticClass:"s-back"})},r=[]},ada1:function(e,t,n){"use strict";n.r(t);var a=n("e482"),r=n.n(a);for(var o in a)["default"].indexOf(o)<0&&function(e){n.d(t,e,(function(){return a[e]}))}(o);t["default"]=r.a},e362:function(e,t,n){"use strict";n.r(t);var a=n("1dfa"),r=n("ada1");for(var o in r)["default"].indexOf(o)<0&&function(e){n.d(t,e,(function(){return r[e]}))}(o);var c=n("f0c5"),i=Object(c["a"])(r["default"],a["b"],a["c"],!1,null,"5f39878e",null,!1,a["a"],void 0);t["default"]=i.exports},e482:function(e,t,n){"use strict";n("7a82");var a=n("4ea4").default;Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,n("c975"),n("ac1f"),n("466d");var r,o=a(n("c7eb")),c=a(n("1da1")),i={name:"subscribe",components:{},data:function(){return{}},onLoad:function(){var e=this;return(0,c.default)((0,o.default)().mark((function t(){var n,a,c;return(0,o.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(r=e,-1==window.location.href.indexOf("code")){t.next=10;break}return localStorage.setItem("isTokening",0),n=window.location.href.match(/code=(\S*)&/)[1],t.next=6,r.$u.api.userWechatUserInfoLogin({code:n});case 6:a=t.sent,a&&(null!==(c=a.data)&&void 0!==c&&c.token&&uni.setStorageSync("clitoken",a.data.token),uni.switchTab({url:"/pages/my/my"})),t.next=11;break;case 10:r.getCode();case 11:case"end":return t.stop()}}),t)})))()},methods:{getRemindData:function(){var e=this;return(0,c.default)((0,o.default)().mark((function t(){var n;return(0,o.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.$u.api.configRemind();case 2:n=t.sent,n&&uni.setStorageSync("welcome",n.data.welcome);case 4:case"end":return t.stop()}}),t)})))()},getCode:function(){return(0,c.default)((0,o.default)().mark((function e(){var t,n,a;return(0,o.default)().wrap((function(e){while(1)switch(e.prev=e.next){case 0:return t="",e.next=3,r.$u.api.configWechat();case 3:n=e.sent,n&&(t=n.data.appid),a=encodeURIComponent(window.location.href),"https://mp.yunzd.cn/connect/oauth2/authorize?appid=","STATE","snsapi_userinfo",window.location.href="https://mp.yunzd.cn/connect/oauth2/authorize?appid="+t+"&redirect_uri="+a+"&response_type=code&connect_redirect=1&scope=snsapi_userinfo&state=STATE#wechat_redirect";case 10:case"end":return e.stop()}}),e)})))()}}};t.default=i}}]);
(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-wechatLogin-wechatLogin"],{"77d1":function(e,t,n){"use strict";n.r(t);var a=n("88ac"),r=n("998b");for(var i in r)["default"].indexOf(i)<0&&function(e){n.d(t,e,(function(){return r[e]}))}(i);var c=n("f0c5"),o=Object(c["a"])(r["default"],a["b"],a["c"],!1,null,"0b112e78",null,!1,a["a"],void 0);t["default"]=o.exports},"88ac":function(e,t,n){"use strict";n.d(t,"b",(function(){return a})),n.d(t,"c",(function(){return r})),n.d(t,"a",(function(){}));var a=function(){var e=this.$createElement,t=this._self._c||e;return t("v-uni-view",{staticClass:"s-back"})},r=[]},"998b":function(e,t,n){"use strict";n.r(t);var a=n("d855"),r=n.n(a);for(var i in a)["default"].indexOf(i)<0&&function(e){n.d(t,e,(function(){return a[e]}))}(i);t["default"]=r.a},d855:function(e,t,n){"use strict";n("7a82");var a=n("4ea4").default;Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,n("c975"),n("ac1f"),n("466d"),n("caad"),n("2532"),n("5319");var r,i=a(n("c7eb")),c=a(n("1da1")),o={name:"subscribe",components:{},data:function(){return{}},onLoad:function(){var e=this;return(0,c.default)((0,i.default)().mark((function t(){var n,a,c;return(0,i.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:if(r=e,!uni.getStorageSync("clitoken")){t.next=4;break}return r.afterLogin(),t.abrupt("return");case 4:if(-1==window.location.href.indexOf("code")){t.next=20;break}return localStorage.setItem("isTokening",0),n=uni.getStorageSync("inviteId")||"",uni.setStorageSync("inviteId",""),a=window.location.href.match(/code=(\S*)&/)[1],t.next=11,r.$u.api.userWechatLogin({code:a,parent:n});case 11:if(c=t.sent,!c){t.next=18;break}return uni.setStorageSync("clitoken",c.data.token),t.next=16,r.getUserId();case 16:r.getRemindData(),r.afterLogin();case 18:t.next=21;break;case 20:r.getCode();case 21:case"end":return t.stop()}}),t)})))()},methods:{afterLogin:function(){var e=this.$mp.query.redirect;if(e){var t=decodeURIComponent(e);if(!t.includes("/pages/index/index"))return void location.replace(t)}uni.switchTab({url:"/pages/index/index"})},getUserId:function(){var e=this;return(0,c.default)((0,i.default)().mark((function t(){var n;return(0,i.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.$u.api.userInfo();case 2:n=t.sent,n&&localStorage.setItem("userId",n.data.id);case 4:case"end":return t.stop()}}),t)})))()},getRemindData:function(){var e=this;return(0,c.default)((0,i.default)().mark((function t(){var n;return(0,i.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.$u.api.configRemind();case 2:n=t.sent,n&&uni.setStorageSync("welcome",n.data.welcome);case 4:case"end":return t.stop()}}),t)})))()},getCode:function(){return(0,c.default)((0,i.default)().mark((function e(){var t,n,a;return(0,i.default)().wrap((function(e){while(1)switch(e.prev=e.next){case 0:return t="",e.next=3,r.$u.api.configWechat();case 3:n=e.sent,n&&(t=n.data.appid),a=encodeURIComponent(window.location.href),"https://mp.yunzd.cn/connect/oauth2/authorize?appid=","STATE","snsapi_base",window.location.href="https://mp.yunzd.cn/connect/oauth2/authorize?appid="+t+"&redirect_uri="+a+"&response_type=code&connect_redirect=1&scope=snsapi_base&state=STATE#wechat_redirect";case 10:case"end":return e.stop()}}),e)})))()}}};t.default=o}}]);
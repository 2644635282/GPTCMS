(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-login-login"],{1265:function(e,t,n){"use strict";var a=n("78e6"),i=n.n(a);i.a},"48bf":function(e,t,n){"use strict";n("7a82");var a=n("4ea4").default;Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var i=a(n("c7eb")),s=a(n("1da1")),r=a(n("0208")),u={name:"subscribe",components:{},data:function(){return{username:"",password:""}},onLoad:function(){},mounted:function(){var e;document.title=(null===(e=r.default.appConfig)||void 0===e?void 0:e.title)||"加载中···"},destroyed:function(){},computed:{},methods:{goBack:function(){uni.navigateBack({delta:1})},jumpWechat:function(){uni.navigateTo({url:"/pages/wechatLogin/wechatLogin"})},jumpRegister:function(){uni.navigateTo({url:"/pages/register/register"})},login:function(){var e=this;return(0,s.default)((0,i.default)().mark((function t(){var n,a;return(0,i.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return r.default.quitLogin(),n=uni.getStorageSync("inviteId")||"",t.next=4,e.$u.api.userLogin({mobile:e.username,password:e.password,parent:n});case 4:if(a=t.sent,!a){t.next=15;break}return e.$u.toast("登录成功"),e.getPageData(),e.getRemindData(),uni.setStorageSync("clitoken",a.data.token),uni.setStorageSync("inviteId",""),t.next=13,e.getUserId();case 13:uni.$emit("updateUserInfo"),setTimeout((function(){uni.switchTab({url:"/pages/index/index"})}),1e3);case 15:case"end":return t.stop()}}),t)})))()},getPageData:function(){var e=this;return(0,s.default)((0,i.default)().mark((function t(){var n;return(0,i.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.$u.api.configTitle();case 2:n=t.sent,n&&uni.setStorageSync("pageTitle",n.data.title);case 4:case"end":return t.stop()}}),t)})))()},getRemindData:function(){var e=this;return(0,s.default)((0,i.default)().mark((function t(){var n;return(0,i.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.$u.api.configRemind();case 2:n=t.sent,n&&uni.setStorageSync("welcome",n.data.welcome);case 4:case"end":return t.stop()}}),t)})))()},getUserId:function(){var e=this;return(0,s.default)((0,i.default)().mark((function t(){var n;return(0,i.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.$u.api.userInfo();case 2:n=t.sent,n&&localStorage.setItem("userId",n.data.id);case 4:case"end":return t.stop()}}),t)})))()}}};t.default=u},"497b":function(e,t,n){"use strict";n.d(t,"b",(function(){return a})),n.d(t,"c",(function(){return i})),n.d(t,"a",(function(){}));var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-uni-view",{staticClass:"s-back subscribe"},[n("v-uni-view",{staticClass:"page-header"},[n("v-uni-view",{staticClass:"back-icon gpticon-back",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.goBack.apply(void 0,arguments)}}}),n("v-uni-view",{staticClass:"page-title"})],1),n("v-uni-view",{staticClass:"inp1",staticStyle:{"margin-top":"60px"}},[n("v-uni-input",{ref:"inputRef",staticClass:"inpt",attrs:{placeholder:"用户名/手机号/邮箱","confirm-type":"send","cursor-spacing":"40","adjust-position":!1},model:{value:e.username,callback:function(t){e.username=t},expression:"username"}})],1),n("v-uni-view",{staticClass:"inp1"},[n("v-uni-input",{ref:"inputRef",staticClass:"inpt",attrs:{type:"password",placeholder:"请输入密码","confirm-type":"send","cursor-spacing":"40","adjust-position":!1},model:{value:e.password,callback:function(t){e.password=t},expression:"password"}})],1),n("v-uni-view",{staticClass:"btn",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.login.apply(void 0,arguments)}}},[e._v("登录")]),n("v-uni-view",{staticStyle:{display:"flex","justify-content":"space-between","align-items":"center"}},[n("v-uni-view",{staticClass:"left",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.jumpRegister.apply(void 0,arguments)}}},[e._v("注册")])],1)],1)},i=[]},"70b8":function(e,t,n){"use strict";n.r(t);var a=n("48bf"),i=n.n(a);for(var s in a)["default"].indexOf(s)<0&&function(e){n.d(t,e,(function(){return a[e]}))}(s);t["default"]=i.a},"78e6":function(e,t,n){var a=n("f3f1");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[e.i,a,""]]),a.locals&&(e.exports=a.locals);var i=n("4f06").default;i("19f4cce4",a,!0,{sourceMap:!1,shadowMode:!1})},eb0a:function(e,t,n){"use strict";n.r(t);var a=n("497b"),i=n("70b8");for(var s in i)["default"].indexOf(s)<0&&function(e){n.d(t,e,(function(){return i[e]}))}(s);n("1265");var r=n("f0c5"),u=Object(r["a"])(i["default"],a["b"],a["c"],!1,null,"6a7adb4a",null,!1,a["a"],void 0);t["default"]=u.exports},f3f1:function(e,t,n){var a=n("24fb");t=a(!1),t.push([e.i,'@charset "UTF-8";\r\n/**\r\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\r\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\r\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \r\n */.subscribe .right[data-v-6a7adb4a]{color:#828694;font-size:16px}.subscribe .inp1[data-v-6a7adb4a]{padding:10px 10px;display:flex;justify-content:flex-start;align-items:center;flex-direction:row;margin:20px;border-radius:30px;border:1px solid #78a4a1}.subscribe .inp1 .inpt[data-v-6a7adb4a]{margin-left:10px;flex:1}.subscribe .btn[data-v-6a7adb4a]{border-radius:20px;background-image:linear-gradient(95.9deg,#9fffbe -.66%,#0cffec 101.97%);padding:13px 20px;color:#000;font-size:15px;font-weight:500;text-align:center;margin:20px}.subscribe .left[data-v-6a7adb4a]{margin:20px 30px;color:#797979}@media screen and (min-width:700px){.btn[data-v-6a7adb4a]{width:660px}}',""]),e.exports=t}}]);
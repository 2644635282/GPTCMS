(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-distribution-cashout"],{"057b":function(t,i,e){t.exports=e.p+"static/img/icon-radio-checked.4b720285.svg"},"0bc0":function(t,i,e){"use strict";e.d(i,"b",(function(){return o})),e.d(i,"c",(function(){return n})),e.d(i,"a",(function(){return a}));var a={uUpload:e("bb22").default,uPopup:e("cfd9").default},o=function(){var t=this,i=t.$createElement,a=t._self._c||i;return a("v-uni-view",{staticClass:"page-distribution"},[a("v-uni-view",{staticClass:"page-header"},[a("v-uni-view",{staticClass:"back-icon gpticon-back",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.goBack.apply(void 0,arguments)}}}),a("v-uni-view",{staticClass:"page-title"})],1),a("v-uni-view",{staticClass:"page-main"},[a("v-uni-view",{staticClass:"distribution-container"},[a("v-uni-view",{staticClass:"distribution-title"},[t._v("提现金额"),a("v-uni-text",{staticClass:"available-cashout"},[t._v("可提现 ￥"+t._s(t.cashoutMax))])],1),a("v-uni-view",{staticClass:"distribution-content distribution-form"},[a("v-uni-view",{staticClass:"distribution-form-row"},[a("v-uni-input",{staticClass:"input",attrs:{placeholder:"请输入提现金额","confirm-type":"提现","adjust-position":!1,type:"number"},model:{value:t.applyData.withdraw_money,callback:function(i){t.$set(t.applyData,"withdraw_money",i)},expression:"applyData.withdraw_money"}})],1),a("v-uni-view",{staticClass:"distribution-form-tips"},[t._v("提现费率："+t._s(t.AppContext.distributionConfig.withdrawal_rate)+"%")]),a("v-uni-view",{staticClass:"distribution-form-tips"},[t._v(t._s(t.AppContext.distributionConfig.withdrawal_notice))])],1)],1),a("v-uni-view",{staticClass:"distribution-container"},[a("v-uni-view",{staticClass:"distribution-title"},[t._v("提现账户")]),a("v-uni-view",{staticClass:"distribution-content distribution-form"},[a("v-uni-view",{staticClass:"distribution-form-row"},[a("v-uni-text",{staticClass:"name"},[t._v("账户类型")]),a("v-uni-view",{staticClass:"cashout-account-list"},t._l(t.accounts,(function(i,e){return a("v-uni-view",{key:e,staticClass:"account-label",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.handleAccountChange(i)}}},[a("v-uni-view",{staticClass:"account-radio",class:{checked:i.value===t.selectAccount.value}}),a("v-uni-text",[t._v(t._s(i.text))])],1)})),1)],1),a("v-uni-view",{staticClass:"distribution-form-row"},[a("v-uni-text",{staticClass:"name"},[t._v("账户名称")]),a("v-uni-input",{staticClass:"input",staticStyle:{"font-size":"14px"},attrs:{placeholder:"必填，请输入账号姓名","adjust-position":!1},model:{value:t.applyData.name,callback:function(i){t.$set(t.applyData,"name",i)},expression:"applyData.name"}})],1),a("v-uni-view",{staticClass:"distribution-form-row"},[a("v-uni-text",{staticClass:"name"},[t._v("收款码")]),a("v-uni-view",{staticClass:"upload"},[a("v-uni-image",{staticClass:"upload-img",attrs:{src:t.applyData.img_url,mode:"aspectFit"}}),a("u-upload",{ref:"uUpload",attrs:{"show-upload-list":!1,"custom-btn":!0,header:t.header,action:t.action,"auto-upload":!0},on:{"on-success":function(i){arguments[0]=i=t.$handleEvent(i),t.handleUploadSuccess.apply(void 0,arguments)}}},[a("v-uni-view",{staticClass:"upload-text",attrs:{slot:"addBtn"},slot:"addBtn"},[t._v("点击上传")])],1)],1)],1)],1)],1)],1),a("v-uni-view",{staticClass:"page-bottom"},[a("v-uni-view",{staticClass:"btn-distribution-bottom primary",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.handleCashout.apply(void 0,arguments)}}},[t._v("申请提现")]),a("v-uni-view",{staticClass:"btn-distribution-bottom",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.handleCashoutList.apply(void 0,arguments)}}},[t._v("提现记录")])],1),a("u-popup",{attrs:{mode:"center","border-radius":"20"},model:{value:t.isShowPop,callback:function(i){t.isShowPop=i},expression:"isShowPop"}},[a("v-uni-view",{staticClass:"register-success"},[a("v-uni-image",{staticClass:"register-success-icon",attrs:{src:e("4bef"),mode:"widthFix"}}),a("v-uni-view",{staticClass:"register-success-text"},[t._v("申请成功")]),a("v-uni-view",{staticClass:"register-success-bottom"},[a("v-uni-view",{staticClass:"btn primary",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.handleSuccess()}}},[t._v("确定")])],1)],1)],1)],1)},n=[]},"1dff":function(t,i,e){var a=e("24fb"),o=e("1de5"),n=e("ea30"),r=e("4147"),c=e("057b");i=a(!1);var s=o(n),d=o(r),u=o(c);i.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\r\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\r\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \r\n */.page-distribution[data-v-4e8496cc]{display:flex;flex-direction:column;background-image:url('+s+');background-position:top;background-size:cover;height:100%;overflow-y:auto}.page-distribution.no-bg[data-v-4e8496cc]{background-image:none;background-color:#f5f5f5}.page-distribution .page-main[data-v-4e8496cc]{padding:0 14px 14px 14px;flex:1;overflow-y:auto}.page-distribution .page-bottom[data-v-4e8496cc]{padding:0 14px;padding-bottom:30px}.distribution-container[data-v-4e8496cc]{padding:0 14px;background-color:#fff;border-radius:10px;margin-top:10px}.distribution-container + .distribution-container[data-v-4e8496cc]{margin-top:20px}.distribution-container .distribution-title[data-v-4e8496cc]{display:flex;justify-content:space-between;position:relative;font-size:16px;line-height:24px;padding-top:10px;padding-bottom:10px;padding-left:12px;color:#3d3d60;font-weight:700;border-bottom:1px solid #f5f5f5}.distribution-container .distribution-title[data-v-4e8496cc]::before{position:absolute;content:"";left:0;top:0;bottom:0;width:6px;height:20px;border-radius:2px;margin:auto;background:linear-gradient(95.9deg,#9fffbe -.66%,#0cffec 101.97%)}.distribution-container .distribution-title .distribution-qrcode[data-v-4e8496cc]{display:flex;font-size:14px;color:#666;font-weight:400}.distribution-container .distribution-title .distribution-qrcode .icon-qrcode[data-v-4e8496cc]{width:24px}.distribution-container .distribution-title .available-cashout[data-v-4e8496cc]{font-size:14px;color:#42cbbb;font-weight:400}.distribution-container .distribution-content[data-v-4e8496cc]{padding:18px 0;font-size:14px;line-height:24px;word-break:break-all}.distribution-container .distribution-content + .distribution-content[data-v-4e8496cc]{border-top:1px solid #f5f5f5}.distribution-container .distribution-form .distribution-form-row[data-v-4e8496cc]{display:flex;align-items:center}.distribution-container .distribution-form .distribution-form-row + .distribution-form-row[data-v-4e8496cc]{margin-top:20px}.distribution-container .distribution-form .distribution-form-row .name[data-v-4e8496cc]{color:#3d3d60;min-width:80px}.distribution-container .distribution-form .distribution-form-row .input[data-v-4e8496cc]{flex:1;height:40px;padding:0 10px;border:1px solid #eee;border-radius:10px}.distribution-container .distribution-form .distribution-form-row .upload[data-v-4e8496cc]{flex:1;display:flex;align-items:center}.distribution-container .distribution-form .distribution-form-row .upload .upload-img[data-v-4e8496cc]{width:80px;height:80px;border-radius:10px;border:1px solid #f5f5f5}.distribution-container .distribution-form .distribution-form-row .upload .upload-text[data-v-4e8496cc]{color:#42cbbb;margin-left:14px}.distribution-container .distribution-form .distribution-form-tips[data-v-4e8496cc]{margin-top:16px;padding:0 10px;color:#999;line-height:1}.distribution-container .distribution-form .cashout-account-list[data-v-4e8496cc]{display:flex}.distribution-container .distribution-form .cashout-account-list .account-label[data-v-4e8496cc]{display:flex;align-items:center;color:#3d3d60}.distribution-container .distribution-form .cashout-account-list .account-label + .account-label[data-v-4e8496cc]{margin-left:24px}.distribution-container .distribution-form .cashout-account-list .account-label .account-radio[data-v-4e8496cc]{width:14px;height:14px;border-radius:100%;background:url('+d+") no-repeat;background-size:100%;margin-right:10px}.distribution-container .distribution-form .cashout-account-list .account-label .account-radio.checked[data-v-4e8496cc]{background:url("+u+") no-repeat;background-size:100%}.distribution-container .poster-list[data-v-4e8496cc]{display:flex}.distribution-container .distribution-poster[data-v-4e8496cc]{flex:1;display:flex;flex-direction:column;align-items:center}.distribution-container .distribution-poster + .distribution-poster[data-v-4e8496cc]{margin-left:20px}.distribution-container .distribution-poster .poster-container[data-v-4e8496cc]{width:100%;position:relative;background-color:initial;overflow:hidden;font-size:0;line-height:1}.distribution-container .distribution-poster .poster-container .poster-bg[data-v-4e8496cc]{width:100%}.distribution-container .distribution-poster .poster-container .poster-qrcode[data-v-4e8496cc]{width:50px;height:50px;position:absolute}.distribution-container .distribution-poster .btn-poster-save[data-v-4e8496cc]{display:flex;justify-content:center;align-items:center;height:40px;padding:0 14px;margin-top:14px;border-radius:40px;color:#444;font-size:14px;background:linear-gradient(95.9deg,#9fffbe -.66%,#0cffec 101.97%)}.btn-poster-touch[data-v-4e8496cc]{padding-bottom:30px;text-align:center}.btn-distribution-bottom[data-v-4e8496cc]{display:flex;justify-content:center;align-items:center;width:100%;height:48px;border-radius:48px;font-size:18px;color:#666;background-color:#fff}.btn-distribution-bottom + .btn-distribution-bottom[data-v-4e8496cc]{margin-top:10px}.btn-distribution-bottom.primary[data-v-4e8496cc]{color:#444;font-weight:700;background:linear-gradient(95.9deg,#9fffbe -.66%,#0cffec 101.97%)}.distribution-agree[data-v-4e8496cc]{margin-top:20px;text-align:center;color:#3d3d60;font-size:10px}.distribution-agree .distribution-protocol[data-v-4e8496cc]{color:#0af}.register-success[data-v-4e8496cc]{width:262px;padding:30px 20px;border-radius:20px;overflow:hidden;text-align:center}.register-success .register-success-icon[data-v-4e8496cc]{width:64px}.register-success .register-success-text[data-v-4e8496cc]{margin-top:10px;font-weight:700;font-size:16px;color:#3d3d60}.register-success .register-success-bottom[data-v-4e8496cc]{display:flex;margin-top:30px}.register-success .register-success-bottom .btn[data-v-4e8496cc]{display:flex;justify-content:center;align-items:center;height:38px;flex:1;color:#666;font-size:14px;border:1px solid #f5f5f5;border-radius:38px}.register-success .register-success-bottom .btn + .btn[data-v-4e8496cc]{margin-left:20px}.register-success .register-success-bottom .btn.primary[data-v-4e8496cc]{background:linear-gradient(95.9deg,#9fffbe -.66%,#0cffec 101.97%)}.distribution-tabs[data-v-4e8496cc]{padding:0 14px}.distribution-tabs .below-tab[data-v-4e8496cc]{display:flex;border-radius:15px;background-color:#fff;overflow:hidden}.distribution-tabs .below-tab .below-tab-item[data-v-4e8496cc]{flex:1;display:flex;justify-content:center;align-items:center;height:30px;font-size:14px;color:#999;background-color:#fff}.distribution-tabs .below-tab .below-tab-item.active[data-v-4e8496cc]{border-radius:15px;color:#3d3d60;background:linear-gradient(269.71deg,#18ffe8 .23%,#93ffc1 99.75%)}.list-noMore[data-v-4e8496cc]{padding:10px;text-align:center}.list-loading[data-v-4e8496cc]{padding:10px;text-align:center;font-size:24px}.below-list[data-v-4e8496cc]{flex:1;overflow-y:overlay}.below-list .below-item[data-v-4e8496cc]{display:flex;align-items:center;min-height:57px;background-color:#fff;border-radius:20px;margin-top:8px;padding:0 14px}.below-list .below-item .below-avatar[data-v-4e8496cc]{width:40px;height:40px;object-fit:cover;border-radius:40px}.below-list .below-item .below-info[data-v-4e8496cc]{margin-left:20px}.below-list .below-item .below-info .below-name[data-v-4e8496cc]{color:#6d8287}.below-list .below-item .below-info .below-date[data-v-4e8496cc]{margin-top:2px;font-size:10px;color:#999}.order-list[data-v-4e8496cc]{flex:1;overflow-y:overlay}.order-list .order-item[data-v-4e8496cc]{display:flex;flex-direction:column;background-color:#fff;border-radius:20px;margin-top:8px;padding:14px}.order-list .order-item .order-user[data-v-4e8496cc]{display:flex;align-items:center}.order-list .order-item .order-user .order-user-avatar[data-v-4e8496cc]{width:40px;height:40px;object-fit:cover;border-radius:40px}.order-list .order-item .order-user .order-user-name[data-v-4e8496cc]{color:#6d8287;margin-left:20px}.order-list .order-item .order-user .order-user-grade[data-v-4e8496cc]{margin-left:10px;display:flex;justify-content:center;align-items:center;height:16px;padding:2px 8px;border-radius:16px;color:#3d3d60;font-size:10px;background:linear-gradient(269.71deg,#18ffe8 .23%,#93ffc1 99.75%)}.order-list .order-item .order-type[data-v-4e8496cc]{margin-top:10px;display:flex;justify-content:space-between;color:#333}.order-list .order-item .order-income[data-v-4e8496cc]{color:#f23131}.cashout-list[data-v-4e8496cc]{flex:1;overflow-y:overlay}.cashout-list .cashout-item[data-v-4e8496cc]{display:flex;flex-direction:column;background-color:#fff;border-radius:20px;padding:14px}.cashout-list .cashout-item + .cashout-item[data-v-4e8496cc]{margin-top:10px}.cashout-list .cashout-item .cashout-cell[data-v-4e8496cc]{display:flex;align-items:center;font-size:14px}.cashout-list .cashout-item .cashout-cell .cashout-cell-title[data-v-4e8496cc]{width:70px;margin-right:10px}.cashout-list .cashout-item .cashout-cell .cashout-cell-content[data-v-4e8496cc]{flex:1;display:flex;justify-content:space-between;align-items:center}.cashout-list .cashout-item .cashout-cell .cashout-cell-content .cashout-state[data-v-4e8496cc]{display:flex;justify-content:center;align-items:center;width:58px;height:20px;font-size:12px;border-radius:4px}.cashout-list .cashout-item .cashout-cell .cashout-cell-content .cashout-state.primary[data-v-4e8496cc]{color:#2074ff;background-color:#dae8ff}.cashout-list .cashout-item .cashout-cell .cashout-cell-content .cashout-state.success[data-v-4e8496cc]{color:#00d7bf;background-color:#e3fffc}.cashout-list .cashout-item .cashout-cell .cashout-cell-content .cashout-state.danger[data-v-4e8496cc]{color:#f23131;background-color:#ffd8d8}.cashout-list .cashout-item .cashout-cell .cashout-cell-content .btn-look-qr[data-v-4e8496cc]{font-size:14px;color:#2074ff;margin-left:20px}.cashout-list .cashout-item .cashout-cell + .cashout-cell[data-v-4e8496cc]{margin-top:6px}.cashout-list .cashout-item .cashout-cell.sub[data-v-4e8496cc]{font-size:12px;color:#666}",""]),t.exports=i},"23e0":function(t,i,e){var a=e("1dff");a.__esModule&&(a=a.default),"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var o=e("4f06").default;o("129c0752",a,!0,{sourceMap:!1,shadowMode:!1})},3670:function(t,i,e){"use strict";e.r(i);var a=e("41e9"),o=e.n(a);for(var n in a)["default"].indexOf(n)<0&&function(t){e.d(i,t,(function(){return a[t]}))}(n);i["default"]=o.a},4062:function(t,i,e){"use strict";var a=e("23e0"),o=e.n(a);o.a},4147:function(t,i,e){t.exports=e.p+"static/img/icon-radio.32c8e27c.svg"},"41e9":function(t,i,e){"use strict";e("7a82");var a=e("4ea4").default;Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var o=a(e("c7eb")),n=a(e("1da1")),r=a(e("0208")),c=e("3cbe"),s=[{text:"微信",value:"1"},{text:"支付宝",value:"2"}],d={data:function(){return{AppContext:r.default,applyData:{user_id:uni.getStorageSync("userId"),withdraw_money:"",type:s[0].value,name:"",img_url:""},cashoutMax:0,accounts:s,selectAccount:s[0],isShowPop:!1,header:{wid:uni.getStorageSync("wid"),UserToken:uni.getStorageSync("clitoken")},action:"".concat(c.host,"/hxai/api/uploadfile")}},computed:{},onLoad:function(){this.getDistributionUser()},methods:{goBack:function(){uni.switchTab({url:"/pages/my/my"})},getDistributionUser:function(){var t=this;return(0,n.default)((0,o.default)().mark((function i(){var e,a,n;return(0,o.default)().wrap((function(i){while(1)switch(i.prev=i.next){case 0:return i.next=2,t.$u.post("/hxai/api/distribution/app/user/center",{user_id:uni.getStorageSync("userId")});case 2:e=i.sent,a=e.data,n=void 0===a?{}:a,t.cashoutMax=n.balance,n.last_withdraw_info&&(t.applyData.img_url=n.last_withdraw_info.img_url,t.applyData.name=n.last_withdraw_info.name,t.applyData.type=n.last_withdraw_info.type,t.selectAccount=s[+n.last_withdraw_info.type-1]);case 7:case"end":return i.stop()}}),i)})))()},handleAccountChange:function(t){this.selectAccount=t,this.applyData.type=t.value},handleCashout:function(){var t=this;return(0,n.default)((0,o.default)().mark((function i(){var e;return(0,o.default)().wrap((function(i){while(1)switch(i.prev=i.next){case 0:if(t.applyData.withdraw_money){i.next=3;break}return uni.showToast({icon:"none",title:"请输入提现金额"}),i.abrupt("return",!1);case 3:if(t.applyData.name){i.next=6;break}return uni.showToast({icon:"none",title:"请输入账号姓名"}),i.abrupt("return",!1);case 6:if(t.applyData.img_url){i.next=9;break}return uni.showToast({icon:"none",title:"请上传收款码"}),i.abrupt("return",!1);case 9:if(!(+t.applyData.withdraw_money>+t.cashoutMax)){i.next=12;break}return uni.showToast({icon:"none",title:"提现金额不能大于可提现金额"}),i.abrupt("return",!1);case 12:return i.next=14,t.$u.post("/hxai/api/distribution/app/withdraw/apply",t.applyData);case 14:e=i.sent,"success"===e.status?(t.isShowPop=!0,t.applyData.withdraw_money="",t.applyData.name="",t.applyData.img_url="",t.getDistributionUser()):uni.showToast({icon:"none",title:"申请失败"});case 16:case"end":return i.stop()}}),i)})))()},handleCashoutList:function(){uni.redirectTo({url:"/pages/distribution/cashoutList"})},handleUploadSuccess:function(t){this.applyData.img_url=t.data.url},handleSuccess:function(){this.isShowPop=!1}}};i.default=d},"4bef":function(t,i,e){t.exports=e.p+"static/img/icon-success.08345e34.svg"},"99dc":function(t,i,e){"use strict";e.r(i);var a=e("0bc0"),o=e("3670");for(var n in o)["default"].indexOf(n)<0&&function(t){e.d(i,t,(function(){return o[t]}))}(n);e("4062");var r=e("f0c5"),c=Object(r["a"])(o["default"],a["b"],a["c"],!1,null,"4e8496cc",null,!1,a["a"],void 0);i["default"]=c.exports}}]);
(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-recharge-recharge"],{"08cb":function(e,t,a){"use strict";a.d(t,"b",(function(){return r})),a.d(t,"c",(function(){return n})),a.d(t,"a",(function(){return i}));var i={uniIcons:a("6075").default,uPopup:a("7cd7").default,uImage:a("8083").default},r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("v-uni-view",{staticClass:"s-back recharge"},[a("v-uni-view",{staticClass:"flex",style:{paddingTop:e.statusbarHeight+12+"px"}},[a("v-uni-view",{staticClass:"text1"},[a("uni-icons",{attrs:{type:"back",size:"25",color:"#fff"},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.goBack.apply(void 0,arguments)}}})],1),a("v-uni-view",{staticClass:"flex1"},[e._v("充值对话次数")]),a("v-uni-view",{staticClass:"right",staticStyle:{visibility:"hidden"}},[e._v("恢复订阅")])],1),a("v-uni-view",{staticClass:"recharge-back"},[a("v-uni-text",{staticClass:"recharge-text1"},[e._v("对话余额"),a("v-uni-text",{staticClass:"recharge-text2"},[e._v(e._s(e.num))]),e._v(e._s(e.unit))],1)],1),a("v-uni-view",{staticClass:"block1"},[a("v-uni-view",{staticClass:"recharge-title"},[a("v-uni-text",{staticClass:"text-1"},[e._v("充值对话次数"),a("v-uni-text",{staticClass:"text-2"},[e._v("永不过期，文字类对话可用")])],1)],1),e._l(e.priceList,(function(t,i){return a("v-uni-view",{key:i,staticClass:"blo7",style:{marginBottom:"14px",marginLeft:i%2==0?"14px":"",marginRight:i%2==0?"14px":""},on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.choosePrice(i)}}},[a("v-uni-view",{class:e.activeIdx==i?"boxx1":"boxx1 boxx2"},[a("v-uni-view",{staticClass:"name1"},[e._v(e._s(t.title||"默认"))]),a("v-uni-view",{staticClass:"price1"},[e._v(e._s(t.price))]),a("v-uni-view",{staticClass:"orginal1"},[e._v(e._s(t.old_price))]),a("v-uni-view",{staticClass:"per1"},[e._v(e._s(t.number+e.unit))])],1)],1)}))],2),a("v-uni-view",{staticClass:"bgap"}),a("v-uni-view",{staticClass:"bottom"},[a("v-uni-view",{staticClass:"btn",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.choosePayMethod.apply(void 0,arguments)}}},[e._v("立即充值")])],1),a("u-popup",{attrs:{mode:"center","border-radius":"40"},model:{value:e.isShowPop,callback:function(t){e.isShowPop=t},expression:"isShowPop"}},[a("v-uni-view",{staticClass:"pop1"},[a("v-uni-view",{staticClass:"hu1"}),a("v-uni-view",{staticClass:"posi1"},[a("v-uni-view",{staticClass:"line7"},[e._v("是否要放弃充值")]),e.priceList.length>0?a("v-uni-view",{staticClass:"line8"},[e._v("优惠力度低至"+e._s(e.priceList[0].number+e.unit)+e._s(e.priceList[0].price)+"元")]):e._e()],1),a("v-uni-view",{staticClass:"mt50 line9"},[a("v-uni-view",{staticClass:"btn8",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.jumpBack1.apply(void 0,arguments)}}},[e._v("狠心放弃")]),a("v-uni-view",{staticClass:"btn9",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.pay.apply(void 0,arguments)}}},[e._v("继续支付")])],1)],1)],1),a("u-popup",{attrs:{mode:"center","border-radius":"0","mask-close-able":!1},model:{value:e.isShowPop1,callback:function(t){e.isShowPop1=t},expression:"isShowPop1"}},[a("u-image",{attrs:{src:e.src,width:"500",height:"500"}})],1),a("u-popup",{attrs:{mode:"bottom","border-radius":"60"},model:{value:e.isShowModel,callback:function(t){e.isShowModel=t},expression:"isShowModel"}},[a("v-uni-view",{staticClass:"edit11"},[a("v-uni-view",{staticClass:"center"},[e._v("选择支付方式")]),a("v-uni-radio-group",{staticStyle:{"margin-bottom":"90px"},on:{change:function(t){arguments[0]=t=e.$handleEvent(t),e.radioChange.apply(void 0,arguments)}}},e._l(e.payList,(function(t,i){return a("v-uni-label",{key:t.value,staticClass:"line1"},[a("v-uni-view",[a("v-uni-radio",{attrs:{color:"rgb(128, 229, 139)",value:t.val,checked:i==e.temp}})],1),a("v-uni-view",{staticStyle:{"margin-left":"10px",color:"#fff","font-size":"16px"}},[e._v(e._s(t.name))])],1)})),1),a("v-uni-view",{staticClass:"center"},[a("v-uni-view",{staticClass:"bottom"},[a("v-uni-view",{staticClass:"btn8",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.isShowModel=!1}}},[e._v("取消")]),a("v-uni-view",{staticClass:"btn9",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.save1.apply(void 0,arguments)}}},[e._v("支付")])],1)],1)],1)],1)],1)},n=[]},"29ab":function(e,t,a){"use strict";a.r(t);var i=a("f5b0"),r=a.n(i);for(var n in i)["default"].indexOf(n)<0&&function(e){a.d(t,e,(function(){return i[e]}))}(n);t["default"]=r.a},"3c45":function(e,t,a){var i=a("42f2");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);var r=a("4f06").default;r("1d2d309e",i,!0,{sourceMap:!1,shadowMode:!1})},4166:function(e,t,a){"use strict";var i=a("9dc6"),r=a.n(i);r.a},"42f2":function(e,t,a){var i=a("24fb");t=i(!1),t.push([e.i,'@charset "UTF-8";\n/**\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \n */.recharge .recharge-title[data-v-f5ac97fc]{padding:22px 14px;color:#fff}.recharge .recharge-title .text-1[data-v-f5ac97fc]{font-size:20px}.recharge .recharge-title .text-2[data-v-f5ac97fc]{margin-left:10px;font-size:14px;color:#bebebe}.recharge .recharge-text1[data-v-f5ac97fc]{color:#fff;font-size:18px}.recharge .recharge-text2[data-v-f5ac97fc]{color:#f77777;font-size:24px;margin:0 4px}.recharge .recharge-back[data-v-f5ac97fc]{display:flex;justify-content:center;padding-top:30px;width:100%;height:140px;background:#75ccd1}.recharge .flex[data-v-f5ac97fc]{padding:%?24?%;display:flex;color:#fff}.recharge .text1[data-v-f5ac97fc]{width:%?130?%}.recharge .flex1[data-v-f5ac97fc]{display:flex;justify-content:center;flex:1;font-size:%?36?%}.recharge .right[data-v-f5ac97fc]{color:#828694;font-size:%?32?%}.recharge .blo2[data-v-f5ac97fc]{display:flex;justify-content:center;padding:%?20?%;color:#828694}.recharge .blo3[data-v-f5ac97fc]{margin:0 %?20?%;font-size:%?24?%;color:#828694}.recharge .blo4[data-v-f5ac97fc]{display:inline-flex;align-items:center;background:#2a2c3a;padding:%?10?%;border-radius:%?60?%;margin-left:%?30?%}.recharge .block1[data-v-f5ac97fc]{white-space:wrap;overflow-x:auto;border-radius:20px;background:#272734;margin:-40px 14px 0}.recharge .blo5[data-v-f5ac97fc]{padding:%?40?%}.recharge .lin1[data-v-f5ac97fc]{color:#c4bba6;font-size:%?40?%;font-weight:800;margin:%?40?% 0 %?16?%}.recharge .lin2[data-v-f5ac97fc]{color:#c4bba6;font-size:%?30?%}.recharge .item1[data-v-f5ac97fc]{margin-bottom:%?30?%}.recharge .blo6[data-v-f5ac97fc]{background:#2a2c3a;margin:%?0?% %?24?% %?24?%;border-radius:%?20?%;padding:%?30?% %?24?% %?10?%}.recharge .text1[data-v-f5ac97fc]{font-size:%?36?%;color:#fff;margin-bottom:%?30?%;white-space:nowrap}.recharge .text2[data-v-f5ac97fc]{font-size:%?28?%;color:#828694}.recharge .blo7[data-v-f5ac97fc]{padding:%?0?% 0 %?20?%;position:relative;display:inline-flex;margin-right:%?30?%}.recharge .boxx1[data-v-f5ac97fc]{display:inline-flex;background-image:linear-gradient(#f2df90,#ecc291);width:calc((100vw - 72px)/2);border-radius:%?30?%;padding:%?30?% %?0?%;color:#000;flex-direction:column;justify-content:center;align-items:center}.recharge .boxx1 .name1[data-v-f5ac97fc]{font-size:%?36?%;font-weight:500}.recharge .boxx1 .price1[data-v-f5ac97fc]{margin:0 %?20?%;font-size:%?60?%;color:#d4372e}.recharge .boxx1 .orginal1[data-v-f5ac97fc]{text-decoration:line-through;color:#937353;font-size:%?30?%;margin:%?10?% 0}.recharge .boxx1 .per1[data-v-f5ac97fc]{color:#d4372e;font-size:%?28?%}.recharge .boxx2[data-v-f5ac97fc]{width:calc((100vw - 72px)/2);display:inline-flex;background:#1e2037!important;border:1px solid #292e49!important;padding:%?30?% %?0?%;border-radius:%?30?%;color:#fff;flex-direction:column;justify-content:center;align-items:center}.recharge .boxx2 .name1[data-v-f5ac97fc]{font-size:%?36?%;font-weight:500}.recharge .boxx2 .price1[data-v-f5ac97fc]{margin:0 %?20?%;font-size:%?60?%;color:#eac78f}.recharge .boxx2 .orginal1[data-v-f5ac97fc]{text-decoration:line-through;color:#9396a0;font-size:%?30?%;margin:%?10?% 0}.recharge .boxx2 .per1[data-v-f5ac97fc]{color:#9396a0;font-size:%?28?%}.recharge .top1[data-v-f5ac97fc]{position:absolute;background:#e74f3a;color:#fff;top:0;left:%?20?%;padding:%?4?% %?20?%;border-radius:%?20?%;font-size:%?28?%;display:flex;flex-direction:column;justify-content:center;align-items:center}.recharge .top1 .name1[data-v-f5ac97fc]{font-style:italic;font-weight:600}.recharge .top1 .time1[data-v-f5ac97fc]{font-size:%?34?%}.recharge .bgap[data-v-f5ac97fc]{width:100%;height:%?220?%}.recharge .bottom[data-v-f5ac97fc]{background:#151828;bottom:0;position:fixed;width:100%;border-top-left-radius:%?50?%;border-top-right-radius:%?50?%;padding:%?24?%;padding-bottom:%?50?%}.recharge .bottom .btn[data-v-f5ac97fc]{border-radius:%?50?%;background-image:linear-gradient(0deg,#7cd887,#75ccd1);padding:%?30?%;color:#000;font-size:%?40?%;font-weight:500;text-align:center}.recharge .block2[data-v-f5ac97fc]{padding:%?30?% %?24?%}.recharge .block2 .first[data-v-f5ac97fc]{display:flex;justify-content:flex-start;align-items:center;flex-direction:row}.recharge .block2 .bar[data-v-f5ac97fc]{width:%?8?%;height:%?50?%;background-image:linear-gradient(0deg,#83d2ea,#79cded);border-radius:%?4?%}.recharge .block2 .title[data-v-f5ac97fc]{font-size:%?40?%;color:#fff;margin-left:%?30?%}.recharge .block2 .item2[data-v-f5ac97fc]{padding:%?30?% %?30?% 0}.recharge .block2 .line1[data-v-f5ac97fc]{display:flex;align-items:center;justify-content:flex-start;padding:%?16?% 0;border-bottom:1px solid #272734;color:#fff}.recharge .block2 .line1 .name1[data-v-f5ac97fc]{font-size:%?34?%;margin:0 %?20?%}.recharge .block2 .line1 .tit1[data-v-f5ac97fc]{padding:%?8?% %?20?%;background:#0f0d0d;border-radius:%?40?%;display:flex;flex-direction:row;justify-content:flex-start;align-items:center}.recharge .block2 .line1 .tit1 .title1[data-v-f5ac97fc]{font-size:%?30?%;color:#f6d98f}.recharge .block2 .content1[data-v-f5ac97fc]{padding:%?20?% 0;color:#a6a7b9;font-size:%?30?%;line-height:%?50?%;white-space:pre-wrap}.recharge .pop1[data-v-f5ac97fc]{width:%?600?%;border:1px solid #39353c;overflow:hidden;position:relative;display:flex;align-items:center;flex-direction:column;justify-content:center;background-image:linear-gradient(90deg,#f4d4af,#f4e9d5)}.recharge .pop1 .hu1[data-v-f5ac97fc]{position:absolute;background:#2f313f;left:-20%;width:140%;height:%?600?%;top:%?-300?%;border-radius:50%}.recharge .pop1 .posi1[data-v-f5ac97fc]{top:0;position:absolute;display:flex;align-items:center;justify-content:center;flex-direction:column}.recharge .pop1 .posi1 .line7[data-v-f5ac97fc]{font-size:%?30?%;display:inline-flex;padding:%?14?% %?28?%;color:#cc997f;background:#18181f;border-bottom-left-radius:%?20?%;border-bottom-right-radius:%?20?%;margin-bottom:%?50?%}.recharge .pop1 .posi1 .line8[data-v-f5ac97fc]{font-size:%?40?%;color:#f9efaf;margin:%?4?% 0;text-align:center}.recharge .pop1 .mt50[data-v-f5ac97fc]{margin-top:%?260?%;height:%?300?%}.recharge .pop1 .line9[data-v-f5ac97fc]{display:flex;justify-content:center;align-items:center;flex-direction:row;padding:%?20?% 0;color:#fff;font-size:%?34?%}.recharge .pop1 .line9 .btn8[data-v-f5ac97fc]{background:#edc596;border-radius:%?80?%;border:1px solid #fff;padding:%?20?% %?40?%;margin-right:%?30?%}.recharge .pop1 .line9 .btn9[data-v-f5ac97fc]{background-image:linear-gradient(180deg,#e7b266,#ff8432);border-radius:%?80?%;border:1px solid #fff;padding:%?20?% %?40?%}@media screen and (min-width:700px){.btn[data-v-f5ac97fc]{width:660px}.boxx1[data-v-f5ac97fc]{width:200px!important}}.edit11[data-v-f5ac97fc]{background:rgba(21,21,32,.9);padding:20px}.edit11 .center[data-v-f5ac97fc]{display:flex;justify-content:center;align-items:center;color:#fff;font-size:20px}.edit11 .cell[data-v-f5ac97fc]{padding:10px 0;font-size:16px;display:flex;border-bottom:1px solid #1f1f2f}.edit11 .cell .left[data-v-f5ac97fc]{display:inline-block;width:70px;text-align:right;color:#a4a4a4}.edit11 .cell .right[data-v-f5ac97fc]{margin-left:4px;color:#fff}.edit11 .bottom[data-v-f5ac97fc]{margin-top:20px;display:flex;justify-content:space-between;width:300px}.edit11 .bottom .btn8[data-v-f5ac97fc]{width:130px;height:36px;border-radius:%?80?%;border:1px solid #7cd887;display:flex;justify-content:center;align-items:center;margin-right:%?30?%}.edit11 .bottom .btn9[data-v-f5ac97fc]{width:130px;height:36px;background-image:linear-gradient(0deg,#7cd887,#75ccd1);border-radius:%?80?%;display:flex;justify-content:center;align-items:center}.line1[data-v-f5ac97fc]{display:flex;justify-content:flex-start;align-items:center;margin:20px 0}',""]),e.exports=t},"5f06":function(e,t,a){"use strict";var i=a("3c45"),r=a.n(i);r.a},"73a4":function(e,t,a){"use strict";a("7a82");var i=a("4ea4").default;Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,a("d401"),a("d3b7"),a("25f0");var r=i(a("c7eb")),n=i(a("1da1")),o=a("be97"),c=a("70dd"),s=i(a("d099")),d={name:"subscribe",components:{},data:function(){return{isShowModel:!1,src:"",timer2:null,isShowPop:!1,activeIdx:0,priceList:[],payList:[{id:1,val:"wx",name:"微信支付"},{id:2,val:"zfb",name:"支付宝支付"}],orderId:"",num:0,statusbarHeight:0,unit:""}},onLoad:function(e){this.num=uni.getStorageSync("residue_degree"),this.statusbarHeight=uni.getStorageSync("statusBarHeight"),this.unit=uni.getStorageSync("unit")},mounted:function(){this.getData()},beforeDestroy:function(){clearInterval(this.timer2),this.timer2=null},destroyed:function(){},computed:{},methods:{radioChange:function(e){this.temp=e.detail.value},save1:function(){var e=this;return(0,n.default)((0,r.default)().mark((function t(){return(0,r.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.payVal=e.temp,e.isShowModel=!1,e.pay(e.payVal);case 3:case"end":return t.stop()}}),t)})))()},jumpBack1:function(){uni.navigateBack({delta:1})},jumpBack:function(){this.isShowPop=!0},choosePayMethod:function(){var e=this;return(0,n.default)((0,r.default)().mark((function t(){return(0,r.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:(0,c.isMobile)()||"1"==uni.getStorageSync("isMiniprogram")?e.pay("wx"):(0,o.getAliPayConfig)()?e.isShowModel=!0:e.pay("wx");case 1:case"end":return t.stop()}}),t)})))()},pay:function(e){var t=this;return(0,n.default)((0,r.default)().mark((function a(){var i;return(0,r.default)().wrap((function(a){while(1)switch(a.prev=a.next){case 0:return a.next=2,s.default.pay(t,{api:"marketOrderCreate",data:{setmeal_type:"recharge",setmeal_id:t.priceList[t.activeIdx].id,buy_number:1,pay_type:e}});case 2:i=a.sent,i.isTimer?(i.showPic&&(t.src=i.code_url,t.isShowPop1=!0),t.timer2=setInterval((function(){t.getResult()}),2e3)):t.getResult();case 4:case"end":return a.stop()}}),a)})))()},getResult:function(){var e=this;return(0,n.default)((0,r.default)().mark((function t(){var a;return(0,r.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.$u.api.marketOrderResult({order_id:e.orderId});case 2:a=t.sent,a?(e.$u.toast("支付成功"),uni.$emit("updateDataMy"),uni.switchTab({url:"/pages/my/my"})):e.$u.toast("支付失败");case 4:case"end":return t.stop()}}),t)})))()},showPop:function(){this.isShowPop=!0},getData:function(){var e=this;return(0,n.default)((0,r.default)().mark((function t(){var a;return(0,r.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,e.$u.api.marketRechargeList();case 2:a=t.sent,a&&(e.priceList=a.data);case 4:case"end":return t.stop()}}),t)})))()},choosePrice:function(e){this.activeIdx=e},countDown:function(){var e=this,t=36e3;this.timer1=setInterval((function(){if(t>=0){var a=Math.floor(t/60/60),i=Math.floor((t-60*a*60)/60),r=(t-60*a*60)%60;a.toString().length<2&&(a="0"+a),i.toString().length<2&&(i="0"+i),r.toString().length<2&&(r="0"+r),e.msg=a+":"+i+":"+r,--t}else clearInterval(e.timer1)}),1e3/60)},goBack:function(){console.log("ssss"),this.isShowPop=!0},scroll:function(){var e=document.getElementById("scroll").scrollWidth;this.timer=setInterval((function(){e>document.getElementById("scroll").scrollLeft+400?document.getElementById("scroll").scrollLeft+=1:document.getElementById("scroll").scrollLeft=0}),50)}}};t.default=d},"7c27":function(e,t,a){"use strict";a.r(t);var i=a("73a4"),r=a.n(i);for(var n in i)["default"].indexOf(n)<0&&function(e){a.d(t,e,(function(){return i[e]}))}(n);t["default"]=r.a},8083:function(e,t,a){"use strict";a.r(t);var i=a("d8c4"),r=a("29ab");for(var n in r)["default"].indexOf(n)<0&&function(e){a.d(t,e,(function(){return r[e]}))}(n);a("4166");var o=a("f0c5"),c=Object(o["a"])(r["default"],i["b"],i["c"],!1,null,"4c7366fc",null,!1,i["a"],void 0);t["default"]=c.exports},"9dc6":function(e,t,a){var i=a("df50");i.__esModule&&(i=i.default),"string"===typeof i&&(i=[[e.i,i,""]]),i.locals&&(e.exports=i.locals);var r=a("4f06").default;r("461f355a",i,!0,{sourceMap:!1,shadowMode:!1})},d8c4:function(e,t,a){"use strict";a.d(t,"b",(function(){return r})),a.d(t,"c",(function(){return n})),a.d(t,"a",(function(){return i}));var i={uIcon:a("4b49").default},r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("v-uni-view",{staticClass:"img",style:[e.wrapStyle,e.backgroundStyle],on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.onClick.apply(void 0,arguments)}}},[e.isError?e._e():a("v-uni-image",{staticClass:"img__image",style:{borderRadius:"circle"==e.shape?"50%":e.$u.addUnit(e.borderRadius)},attrs:{src:e.src,mode:e.mode,"lazy-load":e.lazyLoad,"show-menu-by-longpress":e.showMenuByLongpress},on:{error:function(t){arguments[0]=t=e.$handleEvent(t),e.onErrorHandler.apply(void 0,arguments)},load:function(t){arguments[0]=t=e.$handleEvent(t),e.onLoadHandler.apply(void 0,arguments)}}}),e.showLoading&&e.loading?a("v-uni-view",{staticClass:"img__loading",style:{borderRadius:"circle"==e.shape?"50%":e.$u.addUnit(e.borderRadius),backgroundColor:this.bgColor}},[e.$slots.loading?e._t("loading"):a("u-icon",{attrs:{name:e.loadingIcon,width:e.width,height:e.height}})],2):e._e(),e.showError&&e.isError&&!e.loading?a("v-uni-view",{staticClass:"img__error",style:{borderRadius:"circle"==e.shape?"50%":e.$u.addUnit(e.borderRadius)}},[e.$slots.error?e._t("error"):a("u-icon",{attrs:{name:e.errorIcon,width:e.width,height:e.height}})],2):e._e()],1)},n=[]},df50:function(e,t,a){var i=a("24fb");t=i(!1),t.push([e.i,'@charset "UTF-8";\n/**\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \n */.img[data-v-4c7366fc]{position:relative;transition:opacity .5s ease-in-out}.img__image[data-v-4c7366fc]{width:100%;height:100%}.img__loading[data-v-4c7366fc], .img__error[data-v-4c7366fc]{position:absolute;top:0;left:0;width:100%;height:100%;display:flex;flex-direction:row;align-items:center;justify-content:center;background-color:#f3f4f6;color:#909399;font-size:%?46?%}',""]),e.exports=t},f551:function(e,t,a){"use strict";a.r(t);var i=a("08cb"),r=a("7c27");for(var n in r)["default"].indexOf(n)<0&&function(e){a.d(t,e,(function(){return r[e]}))}(n);a("5f06");var o=a("f0c5"),c=Object(o["a"])(r["default"],i["b"],i["c"],!1,null,"f5ac97fc",null,!1,i["a"],void 0);t["default"]=c.exports},f5b0:function(e,t,a){"use strict";a("7a82"),Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,a("a9e3");var i={name:"img",props:{src:{type:String,default:""},mode:{type:String,default:"aspectFill"},width:{type:[String,Number],default:"100%"},height:{type:[String,Number],default:"auto"},shape:{type:String,default:"square"},borderRadius:{type:[String,Number],default:0},lazyLoad:{type:Boolean,default:!0},showMenuByLongpress:{type:Boolean,default:!0},loadingIcon:{type:String,default:"photo"},errorIcon:{type:String,default:"error-circle"},showLoading:{type:Boolean,default:!0},showError:{type:Boolean,default:!0},fade:{type:Boolean,default:!0},webp:{type:Boolean,default:!1},duration:{type:[String,Number],default:500},bgColor:{type:String,default:"#f3f4f6"}},data:function(){return{isError:!1,loading:!0,opacity:1,durationTime:this.duration,backgroundStyle:{}}},watch:{src:{immediate:!0,handler:function(e){e?this.isError=!1:(this.isError=!0,this.loading=!1)}}},computed:{wrapStyle:function(){var e={};return e.width=this.$u.addUnit(this.width),e.height=this.$u.addUnit(this.height),e.borderRadius="circle"==this.shape?"50%":this.$u.addUnit(this.borderRadius),e.overflow=this.borderRadius>0?"hidden":"visible",this.fade&&(e.opacity=this.opacity,e.transition="opacity ".concat(Number(this.durationTime)/1e3,"s ease-in-out")),e}},methods:{onClick:function(){this.$emit("click")},onErrorHandler:function(e){this.loading=!1,this.isError=!0,this.$emit("error",e)},onLoadHandler:function(){var e=this;if(this.loading=!1,this.isError=!1,this.$emit("load"),!this.fade)return this.removeBgColor();this.opacity=0,this.durationTime=0,setTimeout((function(){e.durationTime=e.duration,e.opacity=1,setTimeout((function(){e.removeBgColor()}),e.durationTime)}),50)},removeBgColor:function(){this.backgroundStyle={backgroundColor:"transparent"}}}};t.default=i}}]);
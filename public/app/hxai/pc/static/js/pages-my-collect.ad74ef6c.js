(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-my-collect"],{"0270":function(t,e,a){"use strict";a.r(e);var n=a("aac0"),i=a("064d");for(var o in i)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("53c6");var r=a("f0c5"),c=Object(r["a"])(i["default"],n["b"],n["c"],!1,null,"117acade",null,!1,n["a"],void 0);e["default"]=c.exports},"02ce":function(t,e,a){"use strict";a("7a82");var n=a("4ea4").default;Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,a("d3b7"),a("99af"),a("ac1f"),a("5319"),a("5b81"),a("c740"),a("a434");var i=n(a("c7eb")),o=n(a("1da1")),r=n(a("1572")),c=n(a("6d9e")),s=n(a("d2fb")),d=n(a("ef84")),l=n(a("29a2")),u={components:{viewer:s.default,history:c.default,loading:d.default},data:function(){return{tab:1,dataList:[],page:1,lastPage:-1,tabActive:1,collect_id:"",type:"",chatItem:null,currentView:null,loading:!1,showCollect:!1,showCollectId:0}},onLoad:function(t){var e=this;return(0,o.default)((0,i.default)().mark((function t(){return(0,i.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.refreshDataList(),document.title=r.default.appConfig.title;case 2:case"end":return t.stop()}}),t)})))()},computed:{},methods:{loadDataList:function(){var t=this;return(0,o.default)((0,i.default)().mark((function e(){var a,n;return(0,i.default)().wrap((function(e){while(1)switch(e.prev=e.next){case 0:return t.loading=!0,a=["assistCollectList","createCollectList","paintCollectList"],"chat"===t.type&&(a=["assistCollectList","chatCollectList"]),n="chat"===t.type?a[t.tabActive-1]:a[t.tab-1],e.next=6,t.$u.api[n]({page:t.page,limit:15}).then((function(e){t.dataList=t.dataList.concat(e.data.data),t.lastPage=e.data.last_page})).finally((function(){t.loading=!1}));case 6:case"end":return e.stop()}}),e)})))()},refreshDataList:function(){this.page=1,this.chatItem=null,this.dataList=[],this.loadDataList()},more:function(){this.page<this.lastPage&&(this.page++,this.loadDataList())},changeTab:function(t){this.tab!=t&&(this.tab=t,this.type="",this.tabActive=1,this.refreshDataList())},changeTabActive:function(t){this.tabActive!=t&&(this.type="chat",this.tabActive=t,this.refreshDataList())},mdContenxt:function(t){return l.default.render(t)},copy:function(t){var e=this,a=t.replaceAll("<br/>","\n");a=a.replaceAll("&nbsp;"," "),uni.setClipboardData({data:a,success:function(){e.$toast.center("复制成功")}})},onclickCollect:function(t){1==t.status&&(this.showCollectId=t.id||0,this.showCollect=!0)},collectSession:function(){var t=this;return(0,o.default)((0,i.default)().mark((function e(){var a;return(0,i.default)().wrap((function(e){while(1)switch(e.prev=e.next){case 0:return a=3==t.tab?"paintCollect":"assistCollect",e.next=3,t.$u.api[a]({collect_id:t.showCollectId||0}).then((function(e){t.$u.toast(e.msg),t.showCollect=!1;var a=t.dataList.findIndex((function(e){return e.id==t.showCollectId}));t.dataList.splice(a,1),t.chatItem=null})).catch((function(t){console.log(t)}));case 3:case"end":return e.stop()}}),e)})))()},download:function(t){var e=document.createElement("a");e.href=t,e.download=t.split("/").pop(),e.click()},copyLink:function(t){var e=this;return(0,o.default)((0,i.default)().mark((function a(){return(0,i.default)().wrap((function(a){while(1)switch(a.prev=a.next){case 0:return a.next=2,navigator.clipboard.writeText(t);case 2:e.$toast.center("链接已复制到剪切板");case 3:case"end":return a.stop()}}),a)})))()},viewImage:function(t){this.currentView={src:t.response}}}};e.default=u},"064d":function(t,e,a){"use strict";a.r(e);var n=a("1d1e"),i=a.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},"1d1e":function(t,e,a){"use strict";a("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={name:"u-line",props:{color:{type:String,default:"#e4e7ed"},length:{type:String,default:"100%"},direction:{type:String,default:"row"},hairLine:{type:Boolean,default:!0},margin:{type:String,default:"0"},borderStyle:{type:String,default:"solid"}},computed:{lineStyle:function(){var t={};return t.margin=this.margin,"row"==this.direction?(t.borderBottomWidth="1px",t.borderBottomStyle=this.borderStyle,t.width=this.$u.addUnit(this.length),this.hairLine&&(t.transform="scaleY(0.5)")):(t.borderLeftWidth="1px",t.borderLeftStyle=this.borderStyle,t.height=this.$u.addUnit(this.length),this.hairLine&&(t.transform="scaleX(0.5)")),t.borderColor=this.color,t}}};e.default=n},"34bf":function(t,e,a){var n=a("24fb"),i=a("1de5"),o=a("3903"),r=a("5034"),c=a("3bb4"),s=a("c876");e=n(!1);var d=i(o),l=i(r),u=i(c),f=i(s);e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\r\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\r\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \r\n */.deleteSession[data-v-89fc4eda]{padding:20px;display:flex;flex-direction:column;width:300px}.deleteSession .confirm-title[data-v-89fc4eda]{font-size:20px}.deleteSession .confirm-msg[data-v-89fc4eda]{margin:30px 0}.deleteSession .confirm-btn[data-v-89fc4eda]{display:flex;align-items:center;justify-content:space-between;-webkit-user-select:none;user-select:none}.deleteSession .confirm-btn .confirm-cancel[data-v-89fc4eda]{background:#e7fcf9}.deleteSession .confirm-btn .confirm-ok[data-v-89fc4eda]{color:#fff;background:#fe4f2f}.deleteSession .confirm-btn > *[data-v-89fc4eda]{padding:10px 30px;cursor:pointer;border-radius:10px}*[data-v-89fc4eda]{outline:none}.paint-canvas[data-v-89fc4eda]{overflow:hidden;position:relative;padding:20px;display:flex;flex-direction:column;justify-content:center;align-items:center}.paint-canvas .canvas-holder[data-v-89fc4eda]{display:flex;flex-direction:column;background:var(--main-bg);background-size:contain;background-position:50%;background-repeat:no-repeat;border-radius:var(--large-radius);-webkit-user-select:none;user-select:none;width:400px;height:400px;cursor:pointer}.paint-canvas .canvas-holder[data-v-89fc4eda]:not(.hasImg){padding:65px 100px;cursor:default}.paint-canvas .canvas-holder .startup-icon[data-v-89fc4eda]{width:160px;height:160px;background-image:url('+d+');background-position:50%;background-repeat:no-repeat;background-size:contain}.paint-canvas .canvas-holder .startup-desc[data-v-89fc4eda]{margin-top:30px;color:var(--color-secondary);font-size:13px;text-align:center}.paint-workplace[data-v-89fc4eda]{display:flex;align-items:stretch;height:100%;overflow:hidden;-webkit-user-select:none;user-select:none}.paint-workplace .paint-styles[data-v-89fc4eda]{display:grid;grid-template-columns:1fr 1fr 1fr 1fr;-webkit-column-gap:8px;column-gap:8px;row-gap:8px}.paint-workplace .paint-styles .style-item-wrapper[data-v-89fc4eda]{padding:3px;border-radius:6px;border:2px solid transparent}.paint-workplace .paint-styles .style-item-wrapper.active[data-v-89fc4eda]{border:2px solid var(--theme-border-color)}.paint-workplace .paint-styles .style-item[data-v-89fc4eda]{text-align:center;position:relative;border-radius:4px;overflow:hidden;-webkit-user-select:none;user-select:none;cursor:pointer}.paint-workplace .paint-styles .style-item .style-icon[data-v-89fc4eda]{padding:50%;background:red;background-size:contain;background-position:50%}.paint-workplace .paint-styles .style-item .style-desc[data-v-89fc4eda]{position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.2);font-size:12px;line-height:1.8em;color:#fff}.paint-workplace .panes[data-v-89fc4eda]{flex:1;position:relative;overflow:hidden;padding-top:10px}.paint-workplace .panes .pane-group[data-v-89fc4eda]{display:flex;align-items:center;border-radius:15px;background:#f2f7f8;overflow:hidden;cursor:pointer;font-size:14px}.paint-workplace .panes .pane-group .itempane[data-v-89fc4eda]{flex:1;text-align:center;height:30px;line-height:30px}.paint-workplace .panes .pane-group .active[data-v-89fc4eda]{background:#e7fcf9;border-radius:15px;color:#0bddc3}.paint-workplace .panes .chat-item[data-v-89fc4eda]{margin-bottom:20px;border-radius:10px;background:#f2f7f8;padding:12px 17px;cursor:pointer}.paint-workplace .panes .chat-item .response[data-v-89fc4eda]{margin:12px 0 3px 0;color:#666;font-size:14px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis}.paint-workplace .panes .chat-item .time[data-v-89fc4eda]{color:#999;font-size:10px}.paint-workplace .panes .chat-item-active[data-v-89fc4eda]{background:#e7fcf9;color:#0bddc3}.paint-workplace .panes .chat-item-active .response[data-v-89fc4eda]{color:#0bddc3}.paint-workplace .panes .chat-item-active .time[data-v-89fc4eda]{color:#0bddc3}.paint-workplace *[data-v-89fc4eda]{box-sizing:border-box}.paint-workplace .tool-pane[data-v-89fc4eda]{width:320px;background:var(--main-bg);border-left:var(--main-border);display:flex;flex-direction:column;overflow:hidden;padding:10px 20px}.paint-workplace .tool-pane .tabs[data-v-89fc4eda]{display:flex;-webkit-user-select:none;user-select:none;cursor:pointer}.paint-workplace .tool-pane .tabs .tab[data-v-89fc4eda]{flex:1;text-align:center;padding:10px 0 15px;font-size:13px;font-weight:800;position:0;position:relative}.paint-workplace .tool-pane .tabs .tab[data-v-89fc4eda]::before{display:inline-block;margin-right:6px;position:relative;top:2px}.paint-workplace .tool-pane .tabs .tab.active[data-v-89fc4eda]:before{color:var(--theme-bg)}.paint-workplace .tool-pane .tabs .tab.active[data-v-89fc4eda]::after{content:"";display:block;position:absolute;bottom:0;width:8em;left:0;right:0;margin:auto;background:var(--theme-bg);height:3px}.paint-workplace .tool-pane .tabs .tab[data-v-89fc4eda]:not(.active){color:var(--secondary-color)}.paint-workplace .paint-canvas[data-v-89fc4eda]{flex:1}\r\n/* 内容面板 */.content-pane[data-v-89fc4eda]{margin:16px;padding:16px;background:#fff;border-radius:20px;overflow:hidden;display:flex;flex-direction:column;flex:1}.content-pane .pane-content[data-v-89fc4eda]{overflow-y:overlay;flex:1}.content-wrapper[data-v-89fc4eda]{border-radius:20px;background:linear-gradient(95.9deg,rgba(159,255,190,.2) -.66%,rgba(12,255,236,.2) 101.97%)}.content-wrapper .content-box[data-v-89fc4eda]{padding:15px;display:flex;align-items:start}.content-wrapper .content[data-v-89fc4eda]{display:flex;align-items:start;flex:1;word-break:break-all;-webkit-user-select:text;user-select:text}.content-wrapper .protrait[data-v-89fc4eda]{margin-right:10px;border-radius:50%;width:30px;height:30px;min-width:30px;min-height:30px;background-repeat:no-repeat;background-position:50%;background-size:cover;background-image:url('+l+")}.content-wrapper .content-q[data-v-89fc4eda]{color:#6d8287}.content-wrapper .content-a[data-v-89fc4eda]{font-size:16px;color:#6d8287}.content-wrapper .a-wrapper[data-v-89fc4eda]{border-radius:20px;background:#fff;border:1px solid #9fffbe}.content-wrapper .a-wrapper .a-btns[data-v-89fc4eda]{display:flex;justify-content:end;padding:0 20px 20px}.content-wrapper .a-wrapper .a-btns > *[data-v-89fc4eda]{border-radius:30px;padding:6px 10px;font-size:12px;line-height:1.4em}.content-wrapper .a-wrapper .a-btns > *[data-v-89fc4eda]:before{margin-right:3px;position:relative;top:2px}.content-wrapper .a-wrapper .a-btns .a-rewrite[data-v-89fc4eda]{background:#dbfcef;color:#78bf8f;margin-left:15px;margin-left:15px}.content-wrapper .a-wrapper .a-btns .a-copy[data-v-89fc4eda]{margin-left:15px;color:#ccab93;background:#fff3ea}.content-wrapper .a-wrapper .a-btns .gpticon-star[data-v-89fc4eda]::before{background-color:#fff;background-position:50%;background-repeat:no-repeat;background-image:url("+u+");background-size:12px}.content-wrapper .a-wrapper .a-btns .gpticon-star[data-v-89fc4eda]{background-color:#f7f7f7;font-size:12px}.content-wrapper .a-wrapper .a-btns .gpticon-stared[data-v-89fc4eda]{background-color:#f7f7f7;font-size:12px;color:#fe4f2f}.content-wrapper .a-wrapper .a-btns .gpticon-stared[data-v-89fc4eda]::before{background-image:url("+f+");background-size:12px;background-color:#fff;background-position:50%;background-repeat:no-repeat}.content-paint[data-v-89fc4eda]{height:100%;padding:15px;display:flex;flex-direction:column;align-items:center;justify-content:center}.content-paint .content-img[data-v-89fc4eda]{width:600px;height:600px;object-fit:contain;border-radius:20px}.content-paint .btns[data-v-89fc4eda]{margin-top:40px;display:flex}.content-paint .btns .btns-item[data-v-89fc4eda]{display:flex;flex-direction:column;align-items:center;cursor:pointer;font-size:14px;margin:0 30px;color:#666}.content-paint .btns .btns-item .btns-item-icon[data-v-89fc4eda]{margin-bottom:10px}.content-paint .btns .btns-item .gpticon-cancel[data-v-89fc4eda]{background-color:#f7f7f7;width:12px;height:12px;color:#fe4f2f}.content-paint .btns .btns-item .gpticon-cancel[data-v-89fc4eda]::before{width:12px;height:12px;background-image:url("+f+");background-size:12px;background-color:#fff;background-position:50%;background-repeat:no-repeat}.waterfall[data-v-89fc4eda]{-webkit-column-count:2;column-count:2;-webkit-column-gap:10px;column-gap:10px;counter-reset:count;overflow-y:auto;width:100%;margin:0 auto}.waterfall .waterfall-item[data-v-89fc4eda]{position:relative;margin-bottom:10px;border-radius:4px;overflow:hidden}.waterfall .waterfall-item img[data-v-89fc4eda]{width:100%;height:auto;display:block;cursor:pointer}.waterfall .waterfall-item.waterfall-item-active[data-v-89fc4eda]{border:2px solid #0bddc3}",""]),t.exports=e},"35ea":function(t,e,a){"use strict";a.r(e);var n=a("90c4"),i=a.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},4883:function(t,e,a){var n=a("d6d9");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("7f3244bd",n,!0,{sourceMap:!1,shadowMode:!1})},"4b0d":function(t,e,a){var n=a("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\r\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\r\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \r\n */.u-line[data-v-117acade]{vertical-align:middle}',""]),t.exports=e},"53c6":function(t,e,a){"use strict";var n=a("e4e9"),i=a.n(n);i.a},"5a07":function(t,e,a){"use strict";a.r(e);var n=a("f2fa"),i=a("d4e9");for(var o in i)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("e6a8");var r=a("f0c5"),c=Object(r["a"])(i["default"],n["b"],n["c"],!1,null,"c905cd3c",null,!1,n["a"],void 0);e["default"]=c.exports},"7ba7":function(t,e,a){"use strict";a.d(e,"b",(function(){return n})),a.d(e,"c",(function(){return i})),a.d(e,"a",(function(){}));var n=function(){var t=this.$createElement,e=this._self._c||t;return this.show?e("v-uni-view",{staticClass:"u-loading",class:"circle"==this.mode?"u-loading-circle":"u-loading-flower",style:[this.cricleStyle]}):this._e()},i=[]},"90c4":function(t,e,a){"use strict";a("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,a("a9e3");var n={name:"u-loading",props:{mode:{type:String,default:"circle"},color:{type:String,default:"#c7c7c7"},size:{type:[String,Number],default:"34"},show:{type:Boolean,default:!0}},computed:{cricleStyle:function(){var t={};return t.width=this.size+"rpx",t.height=this.size+"rpx","circle"==this.mode&&(t.borderColor="#e4e4e4 #e4e4e4 #e4e4e4 ".concat(this.color?this.color:"#c7c7c7")),t}}};e.default=n},9124:function(t,e,a){"use strict";a.r(e);var n=a("c0bf"),i=a("9ed1");for(var o in i)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("cbe8");var r=a("f0c5"),c=Object(r["a"])(i["default"],n["b"],n["c"],!1,null,"807fb89c",null,!1,n["a"],void 0);e["default"]=c.exports},"91cf":function(t,e,a){var n=a("34bf");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("2bd96868",n,!0,{sourceMap:!1,shadowMode:!1})},"9ed1":function(t,e,a){"use strict";a.r(e);var n=a("d98b"),i=a.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},a79e:function(t,e,a){var n=a("d8b5");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("cdac1170",n,!0,{sourceMap:!1,shadowMode:!1})},aac0:function(t,e,a){"use strict";a.d(e,"b",(function(){return n})),a.d(e,"c",(function(){return i})),a.d(e,"a",(function(){}));var n=function(){var t=this.$createElement,e=this._self._c||t;return e("div")},i=[]},acbb:function(t,e,a){"use strict";a.r(e);var n=a("f21d"),i=a("e7d1");for(var o in i)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("c6e6");var r=a("f0c5"),c=Object(r["a"])(i["default"],n["b"],n["c"],!1,null,"89fc4eda",null,!1,n["a"],void 0);e["default"]=c.exports},b437:function(t,e,a){"use strict";a.r(e);var n=a("7ba7"),i=a("35ea");for(var o in i)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return i[t]}))}(o);a("d4f3");var r=a("f0c5"),c=Object(r["a"])(i["default"],n["b"],n["c"],!1,null,"966fd6d8",null,!1,n["a"],void 0);e["default"]=c.exports},bb46:function(t,e,a){var n=a("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\r\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\r\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \r\n */.u-empty[data-v-c905cd3c]{display:flex;flex-direction:row;flex-direction:column;justify-content:center;align-items:center;height:100%}.img[data-v-c905cd3c]{margin-bottom:%?20?%}.u-slot-wrap[data-v-c905cd3c]{display:flex;flex-direction:row;justify-content:center;align-items:center;margin-top:%?20?%}',""]),t.exports=e},c0bf:function(t,e,a){"use strict";a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}));var n={uLine:a("0270").default,uLoading:a("b437").default},i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"u-load-more-wrap",style:{backgroundColor:t.bgColor,marginBottom:t.marginBottom+"rpx",marginTop:t.marginTop+"rpx",height:t.$u.addUnit(t.height)}},[a("u-line",{attrs:{color:"#d4d4d4",length:"50"}}),a("v-uni-view",{staticClass:"u-load-more-inner",class:"loadmore"==t.status||"nomore"==t.status?"u-more":""},[a("v-uni-view",{staticClass:"u-loadmore-icon-wrap"},[a("u-loading",{staticClass:"u-loadmore-icon",attrs:{color:t.iconColor,mode:"circle"==t.iconType?"circle":"flower",show:"loading"==t.status&&t.icon}})],1),a("v-uni-view",{staticClass:"u-line-1",class:["nomore"==t.status&&1==t.isDot?"u-dot-text":"u-more-text"],style:[t.loadTextStyle],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.loadMore.apply(void 0,arguments)}}},[t._v(t._s(t.showText))])],1),a("u-line",{attrs:{color:"#d4d4d4",length:"50"}})],1)},o=[]},c6e6:function(t,e,a){"use strict";var n=a("91cf"),i=a.n(n);i.a},cbe8:function(t,e,a){"use strict";var n=a("4883"),i=a.n(n);i.a},cc73:function(t,e,a){"use strict";a("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,a("a9e3");var n={name:"u-empty",props:{src:{type:String,default:""},text:{type:String,default:""},color:{type:String,default:"#c0c4cc"},iconColor:{type:String,default:"#c0c4cc"},iconSize:{type:[String,Number],default:120},fontSize:{type:[String,Number],default:26},mode:{type:String,default:"data"},imgWidth:{type:[String,Number],default:120},imgHeight:{type:[String,Number],default:"auto"},show:{type:Boolean,default:!0},marginTop:{type:[String,Number],default:0},iconStyle:{type:Object,default:function(){return{}}}},data:function(){return{icons:{car:"购物车为空",page:"页面不存在",search:"没有搜索结果",address:"没有收货地址",wifi:"没有WiFi",order:"订单为空",coupon:"没有优惠券",favor:"暂无收藏",permission:"无权限",history:"无历史记录",news:"无新闻列表",message:"消息列表为空",list:"列表为空",data:"数据为空"}}}};e.default=n},d077:function(t,e,a){var n=a("bb46");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("0e1671bc",n,!0,{sourceMap:!1,shadowMode:!1})},d4e9:function(t,e,a){"use strict";a.r(e);var n=a("cc73"),i=a.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},d4f3:function(t,e,a){"use strict";var n=a("a79e"),i=a.n(n);i.a},d6d9:function(t,e,a){var n=a("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\r\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\r\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \r\n */.u-load-more-wrap[data-v-807fb89c]{display:flex;flex-direction:row;justify-content:center;align-items:center}.u-load-more-inner[data-v-807fb89c]{display:flex;flex-direction:row;justify-content:center;align-items:center;padding:0 %?12?%}.u-more[data-v-807fb89c]{position:relative;display:flex;flex-direction:row;justify-content:center}.u-dot-text[data-v-807fb89c]{font-size:%?28?%}.u-loadmore-icon-wrap[data-v-807fb89c]{margin-right:%?8?%}.u-loadmore-icon[data-v-807fb89c]{display:flex;flex-direction:row;align-items:center;justify-content:center}',""]),t.exports=e},d8b5:function(t,e,a){var n=a("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\r\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\r\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \r\n */.u-loading-circle[data-v-966fd6d8]{display:inline-flex;vertical-align:middle;width:%?28?%;height:%?28?%;background:0 0;border-radius:50%;border:2px solid;border-color:#e5e5e5 #e5e5e5 #e5e5e5 #8f8d8e;-webkit-animation:u-circle-data-v-966fd6d8 1s linear infinite;animation:u-circle-data-v-966fd6d8 1s linear infinite}.u-loading-flower[data-v-966fd6d8]{width:20px;height:20px;display:inline-block;vertical-align:middle;-webkit-animation:a 1s steps(12) infinite;animation:u-flower-data-v-966fd6d8 1s steps(12) infinite;background:transparent url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHBhdGggZmlsbD0ibm9uZSIgZD0iTTAgMGgxMDB2MTAwSDB6Ii8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjRTlFOUU5IiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTMwKSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iIzk4OTY5NyIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgzMCAxMDUuOTggNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjOUI5OTlBIiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKDYwIDc1Ljk4IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0EzQTFBMiIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSg5MCA2NSA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNBQkE5QUEiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoMTIwIDU4LjY2IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0IyQjJCMiIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgxNTAgNTQuMDIgNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjQkFCOEI5IiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKDE4MCA1MCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNDMkMwQzEiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTE1MCA0NS45OCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNDQkNCQ0IiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTEyMCA0MS4zNCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNEMkQyRDIiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTkwIDM1IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0RBREFEQSIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgtNjAgMjQuMDIgNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjRTJFMkUyIiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKC0zMCAtNS45OCA2NSkiLz48L3N2Zz4=) no-repeat;background-size:100%}@-webkit-keyframes u-flower-data-v-966fd6d8{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@keyframes u-flower-data-v-966fd6d8{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}@-webkit-keyframes u-circle-data-v-966fd6d8{0%{-webkit-transform:rotate(0);transform:rotate(0)}100%{-webkit-transform:rotate(1turn);transform:rotate(1turn)}}',""]),t.exports=e},d98b:function(t,e,a){"use strict";a("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,a("a9e3");var n={name:"u-loadmore",props:{bgColor:{type:String,default:"transparent"},icon:{type:Boolean,default:!0},fontSize:{type:String,default:"28"},color:{type:String,default:"#606266"},status:{type:String,default:"loadmore"},iconType:{type:String,default:"circle"},loadText:{type:Object,default:function(){return{loadmore:"加载更多",loading:"正在加载...",nomore:"没有更多了"}}},isDot:{type:Boolean,default:!1},iconColor:{type:String,default:"#b7b7b7"},marginTop:{type:[String,Number],default:0},marginBottom:{type:[String,Number],default:0},height:{type:[String,Number],default:"auto"}},data:function(){return{dotText:"●"}},computed:{loadTextStyle:function(){return{color:this.color,fontSize:this.fontSize+"rpx",position:"relative",zIndex:1,backgroundColor:this.bgColor}},cricleStyle:function(){return{borderColor:"#e5e5e5 #e5e5e5 #e5e5e5 ".concat(this.circleColor)}},flowerStyle:function(){return{}},showText:function(){var t="";return t="loadmore"==this.status?this.loadText.loadmore:"loading"==this.status?this.loadText.loading:"nomore"==this.status&&this.isDot?this.dotText:this.loadText.nomore,t}},methods:{loadMore:function(){"loadmore"==this.status&&this.$emit("loadmore")}}};e.default=n},e4e9:function(t,e,a){var n=a("4b0d");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("33b56518",n,!0,{sourceMap:!1,shadowMode:!1})},e6a8:function(t,e,a){"use strict";var n=a("d077"),i=a.n(n);i.a},e7d1:function(t,e,a){"use strict";a.r(e);var n=a("02ce"),i=a.n(n);for(var o in n)["default"].indexOf(o)<0&&function(t){a.d(e,t,(function(){return n[t]}))}(o);e["default"]=i.a},f21d:function(t,e,a){"use strict";a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}));var n={uLoadmore:a("9124").default,uEmpty:a("5a07").default,uIcon:a("c628").default,uPopup:a("4260").default},i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"paint-workplace"},[n("div",{staticClass:"tool-pane"},[n("div",{staticClass:"tabs"},[n("div",{staticClass:"tab",class:{active:1==t.tab},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeTab(1)}}},[t._v("对话")]),n("div",{staticClass:"tab",class:{active:2==t.tab},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeTab(2)}}},[t._v("创作")]),n("div",{staticClass:"tab",class:{active:3==t.tab},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeTab(3)}}},[t._v("绘画")])]),n("div",{staticClass:"panes"},[3==t.tab?n("div",{staticClass:"pane",staticStyle:{display:"flex","flex-direction":"column",height:"100%",overflow:"hidden"}},[n("v-uni-scroll-view",{staticStyle:{flex:"1","overflow-y":"overlay","padding-top":"20px"},attrs:{"scroll-y":"true"},on:{scrolltolower:function(e){arguments[0]=e=t.$handleEvent(e),t.more.apply(void 0,arguments)}}},[n("div",{staticClass:"waterfall"},t._l(t.dataList,(function(e,a){return n("div",{key:a,staticClass:"waterfall-item",class:{"waterfall-item-active":t.chatItem&&t.chatItem.id==e.id},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.chatItem=e}}},[e.response?n("img",{attrs:{src:e.response}}):t._e()])})),0)])],1):n("div",{staticClass:"pane",staticStyle:{display:"flex","flex-direction":"column",height:"100%",overflow:"hidden"}},[1==t.tab?n("div",{staticClass:"pane-group",staticStyle:{position:"sticky",top:"0","z-index":"10"}},[n("div",{staticClass:"itempane",class:{active:1==t.tabActive},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeTabActive(1)}}},[t._v("角色对话")]),n("div",{staticClass:"itempane",class:{active:2==t.tabActive},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeTabActive(2)}}},[t._v("默认对话")])]):t._e(),n("v-uni-scroll-view",{staticClass:"scroll-Y",staticStyle:{flex:"1","overflow-y":"overlay","padding-top":"20px"},attrs:{"scroll-y":"true"},on:{scrolltolower:function(e){arguments[0]=e=t.$handleEvent(e),t.more.apply(void 0,arguments)}}},[t._l(t.dataList,(function(e){return n("div",{key:e.id,staticClass:"chat-item",class:{"chat-item-active":t.chatItem&&t.chatItem.id==e.id},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.chatItem=e}}},[n("div",[t._v(t._s(e.message))]),n("div",{staticClass:"response"},[t._v(t._s(e.un_response))]),n("div",{staticClass:"time"},[t._v(t._s(e.create_time))])])})),t.dataList.length>6?n("u-loadmore",{attrs:{status:t.loading?"loading":"nomore"}}):t._e(),0===t.dataList.length&&t.lastPage>-1?n("u-empty",{attrs:{text:"数据为空",mode:"favor"}}):t._e()],2)],1)])]),n("v-uni-view",{staticClass:"content-pane"},[n("v-uni-view",{staticClass:"pane-content"},[t.chatItem&&3!=t.tab?n("v-uni-view",{staticClass:"content-wrapper"},[n("v-uni-view",{staticClass:"content-box content-q"},[n("v-uni-view",{staticClass:"content q-content"},[t._v(t._s(t.chatItem.message))])],1),n("v-uni-view",{staticClass:"a-wrapper"},[n("v-uni-view",{staticClass:"content-box content-a"},[n("v-uni-view",{staticClass:"mdcontent",domProps:{innerHTML:t._s(t.mdContenxt(t.chatItem.un_response))}})],1),n("v-uni-view",{staticClass:"a-btns"},[n("v-uni-view",{staticClass:"icon-star gpticon-stared",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onclickCollect(t.chatItem)}}},[t._v("取消收藏")]),n("v-uni-view",{staticClass:"a-copy gpticon-copy",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.copy(t.chatItem.response)}}},[t._v("复制文案")])],1)],1)],1):t._e(),t.chatItem&&3==t.tab?n("v-uni-view",{staticClass:"content-paint"},[n("img",{staticClass:"content-img",attrs:{src:t.chatItem.response,alt:""}}),n("v-uni-view",{staticClass:"btns"},[n("v-uni-view",{staticClass:"btns-item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.download(t.chatItem.response)}}},[n("span",{staticClass:"btns-item-icon gpticon-download"}),n("span",[t._v("下载")])]),n("v-uni-view",{staticClass:"btns-item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.copyLink(t.chatItem.response)}}},[n("span",{staticClass:"btns-item-icon gpticon-copylink"}),n("span",[t._v("链接")])]),n("v-uni-view",{staticClass:"btns-item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.viewImage(t.chatItem)}}},[n("u-icon",{staticClass:"btns-item-icon",attrs:{name:"search",color:"#666",size:"30"}}),n("span",[t._v("放大")])],1),n("v-uni-view",{staticClass:"btns-item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onclickCollect(t.chatItem)}}},[n("img",{staticStyle:{width:"15px",height:"16px","margin-bottom":"10px"},attrs:{src:a("c876"),alt:""}}),n("span",[t._v("取消收藏")])])],1)],1):t._e(),t.chatItem?t._e():n("u-empty",{attrs:{text:"暂无数据",mode:"favor"}})],1)],1),t.currentView?n("viewer",{staticClass:"paint-one",staticStyle:{"z-index":"1000",position:"fixed",top:"0",left:"0",right:"0",bottom:"0",background:"#00000070"},attrs:{hidePreview:!0,curImage:t.currentView},on:{close:function(e){arguments[0]=e=t.$handleEvent(e),t.currentView=null}}}):t._e(),n("u-popup",{attrs:{mode:"center","border-radius":"10"},model:{value:t.showCollect,callback:function(e){t.showCollect=e},expression:"showCollect"}},[n("v-uni-view",{staticClass:"deleteSession"},[n("v-uni-view",{staticClass:"confirm-title"},[t._v("您确定要取消收藏吗?")]),n("v-uni-view",{staticClass:"confirm-msg"},[t._v("取消收藏后，可能再也找不到了哦。")]),n("v-uni-view",{staticClass:"confirm-btn"},[n("v-uni-view",{staticClass:"confirm-cancel",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.showCollect=!1}}},[t._v("取消")]),n("v-uni-view",{staticClass:"confirm-ok",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.collectSession.apply(void 0,arguments)}}},[t._v("确定")])],1)],1)],1)],1)},o=[]},f2fa:function(t,e,a){"use strict";a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return o})),a.d(e,"a",(function(){return n}));var n={uIcon:a("c628").default},i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return t.show?a("v-uni-view",{staticClass:"u-empty",style:{marginTop:t.marginTop+"rpx"}},[a("u-icon",{attrs:{name:t.src?t.src:"empty-"+t.mode,"custom-style":t.iconStyle,label:t.text?t.text:t.icons[t.mode],"label-pos":"bottom","label-color":t.color,"label-size":t.fontSize,size:t.iconSize,color:t.iconColor,"margin-top":"14"}}),a("v-uni-view",{staticClass:"u-slot-wrap"},[t._t("bottom")],2)],1):t._e()},o=[]}}]);
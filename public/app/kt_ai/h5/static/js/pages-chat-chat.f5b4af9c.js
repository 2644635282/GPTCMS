(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-chat-chat"],{"08dd":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,'@charset "UTF-8";\n/**\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \n */.example1.out[data-v-3717d375]{width:100%;padding:2px;border-radius:%?40?%;background:linear-gradient(90deg,#b46a80,#739b83)}.example1 .in[data-v-3717d375]{width:calc(100vw - 70px);width:calc(100% - 20px);height:100%;background:#151520;border-radius:%?40?%}.inputClass[data-v-3717d375]{background-color:initial;align-content:center;padding:5px 10px 5px 10px;font-size:%?30?%;width:100%}.scrollView[data-v-3717d375]{position:fixed;right:0;left:0;top:0;\n  /* padding-left: 10px;\n\t\t\tpadding-right: 10px;\n\t\t\tpadding-top: 10px; */width:auto}.bottomClass1[data-v-3717d375]{padding:10px 12px;background:#151520;border-top-left-radius:20px;border-top-right-radius:20px;display:flex;justify-content:flex-start;align-items:center;flex-direction:row}.uni-row[data-v-3717d375]{display:flex;flex-direction:row;justify-content:flex-start;align-items:center}.uni-flex[data-v-3717d375]{display:flex;flex-direction:row;justify-content:flex-start;align-items:center}.s-back[data-v-3717d375]{background:#1a2134}.index-inp[data-v-3717d375]{width:100%}.index-inp-inner[data-v-3717d375]{width:calc(100% - 14px);height:36px;color:#fff;padding:0 10px}.index-ml10[data-v-3717d375]{margin-right:10px}.index-flex-right[data-v-3717d375]{display:flex;justify-content:flex-end}.index-flex-left[data-v-3717d375]{display:flex;justify-content:flex-start}.index-no[data-v-3717d375]{margin-left:12px;margin-top:12px;background:#aa4b4f;display:flex;justify-content:center;align-items:center;padding:0 %?26?%;color:#fff;height:%?60?%;border-radius:%?40?%}.index-mt12[data-v-3717d375]{margin-top:16px;margin-right:12px}.index-line1[data-v-3717d375]{padding:12px;display:flex;align-items:center}.index1[data-v-3717d375]{padding:0;display:flex;justify-content:center;align-items:center;border-radius:20px;background:#e6d08d;color:#5f3d22}.index1-text[data-v-3717d375]{margin-left:2px;margin-right:10px;font-size:16px}.index2[data-v-3717d375]{font-size:%?36?%;flex:1;display:flex;justify-content:center}.index3-mr4[data-v-3717d375]{margin-right:%?16?%}.index-line3[data-v-3717d375]{display:flex;align-items:center;margin:%?24?%;color:#fff;font-size:%?32?%}.index-line2[data-v-3717d375]{margin:%?30?% %?24?%;background:#323249;border-radius:10px;padding:%?28?%;color:#a3a3b7;font-size:%?32?%;display:flex;justify-content:space-between;align-items:center}.index-line-mb12[data-v-3717d375]{margin-bottom:%?60?%}.edit-chat[data-v-3717d375]{background:rgba(21,21,32,.9);padding:20px}.edit-chat .title[data-v-3717d375]{color:#fff;font-size:18px;margin-bottom:20px}.edit-chat .title2[data-v-3717d375]{color:#999;font-size:14px}.edit-chat .bottom[data-v-3717d375]{margin-top:20px;display:flex;justify-content:space-between;width:300px}.edit-chat .bottom .btn8[data-v-3717d375]{width:130px;height:36px;color:#fff;border-radius:%?80?%;border:1px solid #7cd887;display:flex;justify-content:center;align-items:center;margin-right:%?30?%}.edit-chat .bottom .btn9[data-v-3717d375]{color:#fff;width:130px;height:36px;background-image:linear-gradient(0deg,#7cd887,#75ccd1);border-radius:%?80?%;display:flex;justify-content:center;align-items:center}.bottom-bar[data-v-3717d375]{margin-top:10px;padding-bottom:16px;display:flex;justify-content:center}.bottom-bar .item[data-v-3717d375]{flex:1;display:flex;align-items:center;justify-content:center;flex-direction:column}.bottom-bar .text[data-v-3717d375]{margin-top:%?4?%;color:#686a7a;font-size:%?24?%}.new-bottom[data-v-3717d375]{display:flex;align-items:flex-end;margin-bottom:30px}',""]),t.exports=e},"21ac":function(t,e,i){"use strict";var n=i("f996"),a=i.n(n);a.a},6744:function(t,e,i){"use strict";i.r(e);var n=i("89ff"),a=i("a31d");for(var s in a)["default"].indexOf(s)<0&&function(t){i.d(e,t,(function(){return a[t]}))}(s);i("21ac");var o=i("f0c5"),c=Object(o["a"])(a["default"],n["b"],n["c"],!1,null,"3717d375",null,!1,n["a"],void 0);e["default"]=c.exports},"745f":function(t,e,i){"use strict";i("7a82");var n=i("4ea4").default;Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,i("14d9"),i("d401"),i("d3b7"),i("25f0"),i("159b"),i("a434"),i("ac1f"),i("e25e");var a,s,o=n(i("c7eb")),c=n(i("1da1")),r=n(i("ade3")),l=(n(i("596e")),i("70dd")),d=(i("36c7"),i("e985")),u=n(i("0cc8")),p=n(i("9058")),f=n(i("6075")),h=n(i("96e9")),g=null,v=null,m=(a={name:"easyChat",components:{chatItem:p.default},data:function(){var t;return t={tabbars:[{id:1,pic:"/static/tabbar/1.png",text:"首页"},{id:2,pic:"/static/tabbar/2.png",text:"创作者"},{id:3,pic:"/static/tabbar/3.png",text:"助理"},{id:5,pic:"/static/tabbar/4.png",text:"我的"}],websocket:null,deleIdx:0,isShowDele:!1,pageTitle:"",scrollTopHeight:0,textTemp:"",isLoadingText:!1,isLoading:!1,activeIndex:0,socketUrl:"",timeStr:"",clickMic:!1,scrollview:"",inputcontent:"",screenHeight:0,bottomHeight:10,micHeight:255,formatedRecordTime:"00:00:00",recordTime:0,isShowTips:!1,isRecording:!1,isCancel:!1,socketTask:null,form:{},chatList:[]},(0,r.default)(t,"screenHeight",0),(0,r.default)(t,"screenWidth",0),(0,r.default)(t,"statusbarHeight",0),(0,r.default)(t,"_audioContext",null),(0,r.default)(t,"options",{}),(0,r.default)(t,"isVoice",!1),(0,r.default)(t,"source",null),t},onLoad:function(t){var e=this;return(0,c.default)((0,o.default)().mark((function i(){return(0,o.default)().wrap((function(i){while(1)switch(i.prev=i.next){case 0:return i.next=2,e.$onLaunched;case 2:e.isVoice=uni.getStorageSync("isVoice"),s=e,s.options=t,e.screenWidth=uni.getStorageSync("screenWidth"),e.statusbarHeight=uni.getStorageSync("statusBarHeight"),e.screenHeight=uni.getStorageSync("screenHeight"),s.pageTitle=uni.getStorageSync("pageTitle")||"聊天",uni.setNavigationBarTitle({title:s.pageTitle}),s.getInfo();case 11:case"end":return i.stop()}}),i)})))()},mounted:function(){var t=this;uni.getSystemInfo({success:function(e){t.$nextTick((function(){t.screenHeight=e.windowHeight}))}}),this.$nextTick((function(){g=uni.getRecorderManager(),g.onStart((function(){v=setInterval((function(){t.recordTime+=1,t.formatedRecordTime=t.recordTime}),1e3)})),g.onStop((function(e){if(t.isCancel)t.formatedRecordTime="00:00:00",t.isCancel=!1,t.recordTime=0;else if(t.recordTime<1)t.formatedRecordTime="00:00:00",t.recordTime=0,uni.showToast({icon:"none",title:"发送时间太短",duration:1e3});else{t.recordTime=0;var i=new Date,n={speaker:"Self",contentType:"audio",audioSrc:e.tempFilePath,createTime:i.getTime(),isUndo:!1,isDelete:!1};t.chatList.push(n);var a="dynamic_"+(t.chatList.length-1>-1?t.chatList.length-1:0).toString();t.$nextTick((function(){t.scrollview=a,t.formatedRecordTime="00:00:00"})),t.$refs.socket.sendAudioMsg(n)}}))})),uni.onKeyboardHeightChange((function(e){t.scrollview="null";var i="dynamic_"+(t.chatList.length-1>-1?t.chatList.length-1:0).toString();e.height>0?(t.$nextTick((function(){t.scrollview=i})),t.bottomHeight=e.height):t.$nextTick((function(){t.bottomHeight=10})),console.log(t.bottomHeight,"cccccc")}))}},(0,r.default)(a,"components",{popup:u.default,chatItem:p.default,uniIcons:f.default,socketItem:h.default}),(0,r.default)(a,"computed",{getImageList:function(){var t=[];return this.chatList.forEach((function(e,i){"image"==e.contentType&&t.push(e.imageSrc)}),this),t},getScrollView:function(){return this.scrollview},setButtonBg:function(){return this.isRecording?"bg-blue":"bg-gray"}}),(0,r.default)(a,"methods",{jumpTabbar:function(t){var e="/pages/index/index";switch(t){case 1:e="/pages/index/index?isMiniprogram="+uni.getStorageSync("isMiniprogram");break;case 2:e="/pages/content/content";break;case 3:e="/pages/assist/assist";break;case 4:e="/pages/paint/paint";break;case 5:e="/pages/my/my";break}uni.switchTab({url:e})},save:function(){var t=this;return(0,c.default)((0,o.default)().mark((function e(){var i;return(0,o.default)().wrap((function(e){while(1)switch(e.prev=e.next){case 0:if("content"!=s.options.type){e.next=6;break}return e.next=3,t.$u.api.createDele({model_id:t.options.id});case 3:i=e.sent,e.next=9;break;case 6:return e.next=8,t.$u.api.assistDele({model_id:t.options.id});case 8:i=e.sent;case 9:i&&(t.isShowDele=!1,t.chatList=[],s.getDataList());case 10:case"end":return e.stop()}}),e)})))()},playVoice:function(t){var e=this;return(0,c.default)((0,o.default)().mark((function i(){return(0,o.default)().wrap((function(i){while(1)switch(i.prev=i.next){case 0:e._audioContext&&e._audioContext.pause(),e._audioContext=null,e.createAudio(t);case 3:case"end":return i.stop()}}),i)})))()},createAudio:function(t){var e=this._audioContext=uni.createInnerAudioContext();return e.autoplay=!1,e.src=t,this._audioContext.play(),e},getVoice:function(t){return(0,c.default)((0,o.default)().mark((function e(){var i;return(0,o.default)().wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,s.$u.api.chatVoice({content:(0,l.handleSymbolVoice)(s.chatList[t].text)});case 2:i=e.sent,i&&s.playVoice(i.data);case 4:case"end":return e.stop()}}),e)})))()},goBack:function(){uni.navigateBack({delta:1})},getInfo:function(){return(0,c.default)((0,o.default)().mark((function t(){var e;return(0,o.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:if("content"!=s.options.type){t.next=6;break}return t.next=3,s.$u.api.createInfo({id:s.options.id});case 3:e=t.sent,t.next=9;break;case 6:return t.next=8,s.$u.api.assistInfo({id:s.options.id});case 8:e=t.sent;case 9:e&&(s.form=e.data,s.getDataList());case 10:case"end":return t.stop()}}),t)})))()},getDataList:function(){var t=this;return(0,c.default)((0,o.default)().mark((function e(){var i,n,a,c;return(0,o.default)().wrap((function(e){while(1)switch(e.prev=e.next){case 0:if("content"!=s.options.type){e.next=6;break}return e.next=3,s.$u.api.createChatList({model_id:s.options.id});case 3:i=e.sent,e.next=9;break;case 6:return e.next=8,s.$u.api.assistChatList({model_id:s.options.id});case 8:i=e.sent;case 9:i&&(n=new Date,a={},i.data.forEach((function(e){a="我"==e.role?{speaker:"Self",contentType:"text",text:e.content,createTime:n.getTime(),isUndo:!1,isDelete:!1}:{speaker:"Others",contentType:"text",text:(0,l.handleSymbol)(e.content),createTime:n.getTime(),isUndo:!1,isDelete:!1},t.chatList.push(a)})),0==s.chatList.length&&(c=uni.getStorageSync("welcome"),s.chatList.push({speaker:"Others",contentType:"text",text:s.form.hint_content||c,createTime:n.getTime(),isUndo:!1,isDelete:!1})),setTimeout((function(){s.scrollToBottom()}),100));case 10:case"end":return e.stop()}}),e)})))()},getData:function(t){this.isLoadingText=!0;var e=uni.getStorageSync("host")||"",i=e+"/gptcms/api/createchat/send?model_id="+s.options.id+"&message="+encodeURIComponent(t);"content"!=s.options.type&&(i=e+"/gptcms/api/rolechat/send?model_id="+s.options.id+"&message="+t),s.source=new d.EventSourcePolyfill(i,{headers:{wid:uni.getStorageSync("wid")||"1",token:uni.getStorageSync("h5Token")||""}}),s.source.onopen=function(t){var e=new Date;s.isLoading=!0,s.isLoadingText=!0;var i={speaker:"Others",contentType:"text",text:"",createTime:e.getTime(),isUndo:!1,isDelete:!1};console.log(s.chatList,"ddddddd"),s.chatList.push(i);(s.chatList.length-1>-1?s.chatList.length-1:0).toString();s.$nextTick((function(){s.scrollToBottom()}))},s.source.onmessage=function(t){console.log(t.data);(s.chatList.length-1>-1?s.chatList.length-1:0).toString();s.$nextTick((function(){s.textTemp+=t.data,s.scrollToBottom()}))},s.source.onerror=function(t){var e=new Date;s.source.close(),s.isLoading=!1,s.isLoadingText=!1,s.chatList.splice(s.chatList.length-1,1);var i={speaker:"Others",contentType:"text",text:(0,l.handleSymbol)(s.textTemp),createTime:e.getTime(),isUndo:!1,isDelete:!1};s.chatList.push(i);(s.chatList.length-1>-1?s.chatList.length-1:0).toString();s.$nextTick((function(){s.textTemp="",s.isLoading=!1,setTimeout((function(){s.scrollToBottom()}),100)}))}},scrollToBottom:function(){var t=this;uni.createSelectorQuery().in(s).select("#scroll-view-content").boundingClientRect((function(e){var i=e.height-(s.screenHeight-s.bottomHeight-200);i>0&&(t.scrollTopHeight=i)})).exec()},subscribe:function(){uni.navigateTo({url:"/pages/subscribe/subscribe"})},readmethod:function(){},longpressmethod:function(t){var e=this,i=(t.target.id,0),n=0;uni.createSelectorQuery().in(this).select("#test1").boundingClientRect((function(t){var a=t.left,s=t.right;n=(a+s)/2,i=t.top,e.$refs.popup.show(n,i)})).exec()},computeDate:function(t,e){if(t){var i=new Date;if(e>0){var n=this.chatList[e-1].createTime;if(t-n>6e4){i.setTime(t);var a=i.toString(),s=a.split(" ")[4];return s}return""}return""}return""},setDatetimeStyle:function(t,e){if(t){if(e>0){var i=this.chatList[e-1].createTime;return t-i>6e4?"justify-content: center;align-content: center;margin-bottom: 25rpx;font-size: 25rpx;":""}return""}return""},sendMessage:function(t){(0,l.debounce)((function(){s.sendMessage1(t)}))},sendMessage1:function(t){var e=this;return(0,c.default)((0,o.default)().mark((function i(){var n,a,c,r,l;return(0,o.default)().wrap((function(i){while(1)switch(i.prev=i.next){case 0:if(n="",n="string"==typeof t?t:t.detail.value,a=new Date,!n||e.isLoading||e.isLoadingText){i.next=8;break}return i.next=6,e.$u.api.chatCheck({text:n,type:"content"==s.options.type?"createchat":"rolechat",model_id:s.options.id});case 6:c=i.sent,c&&(1!=c.data.code?(r={speaker:"Self",contentType:"text",text:c.data.text,createTime:a.getTime(),isUndo:!1,isDelete:!1},e.chatList.push(r),l="dynamic_"+(e.chatList.length-1>-1?e.chatList.length-1:0).toString(),e.$nextTick((function(){e.inputcontent="",e.scrollview=l})),e.getData(c.data.text)):(e.$nextTick((function(){e.inputcontent="",e.scrollview=id})),e.$u.toast(c.data.text)));case 8:case"end":return i.stop()}}),i)})))()},checkPermission:function(){return(0,c.default)((0,o.default)().mark((function t(){return(0,o.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.abrupt("return",1);case 1:case"end":return t.stop()}}),t)})))()},micClick:function(){return(0,c.default)((0,o.default)().mark((function t(){return(0,o.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:case"end":return t.stop()}}),t)})))()},imageClick:function(){var t=this,e=new Date,i=[];uni.chooseImage({sourceType:["album"],count:6,success:function(n){var a=n.tempFilePaths;a.forEach((function(n,a){var s={speaker:"Self",contentType:"image",imageSrc:n,createTime:e.getTime(),isUndo:!1,isDelete:!1};t.chatList.push(s),i.push(n)})),setTimeout((function(){var e="dynamic_"+(t.chatList.length-1>-1?t.chatList.length-1:0).toString();t.scrollview=e}),500),t.$refs.socket.sendImageMsg(i)},fail:function(t){}})},cameraClick:function(){var t=this,e=new Date;uni.chooseImage({sourceType:["camera"],success:function(i){var n=i.tempFilePaths;n.forEach((function(i,n){var a={speaker:"Self",contentType:"image",imageSrc:i,createTime:e.getTime(),isUndo:!1,isDelete:!1};t.chatList.push(a)})),setTimeout((function(){var e="dynamic_"+(t.chatList.length-1>-1?t.chatList.length-1:0).toString();t.scrollview=e}),500)},fail:function(t){}})},getKeyBoradHeight:function(t){},micHide:function(){this.bottomHeight=0,this.clickMic=!1},micTouchStart:function(t){this.isRecording=!0,g.start({format:"mp3"})},micTouchMove:function(t){var e=this.screenHeight-this.bottomHeight+t.currentTarget.offsetTop;t.touches[0].clientY<e&&!this.isShowTips&&(this.isCancel=!0,this.isShowTips=!0,this.$refs.tips.show()),t.touches[0].clientY>=e&&(this.isCancel=!1,this.isShowTips=!1,this.$refs.tips.hide())},micTouchEnd:function(t){this.isRecording=!1,clearInterval(v),g.stop(),this.$refs.tips.hide()},undo:function(t){var e=parseInt(t.index);this.chatList[e].isUndo=!0,console.log(this.scrollview)},deleted:function(t){this.deleIdx=parseInt(t),this.isShowDele=!0},onReciveMessages:function(t){},ontext:function(t){var e=this,i=t.data;this.chatList.push(i);var n="dynamic_"+(this.chatList.length-1>-1?this.chatList.length-1:0).toString();this.$nextTick((function(){e.scrollview=n}))},onimage:function(t){var e=this,i=t.data;this.chatList.push(i);var n="dynamic_"+(this.chatList.length-1>-1?this.chatList.length-1:0).toString();setTimeout((function(){e.scrollview=n}),500)},onaudio:function(t){var e=this,i=t.data;this.chatList.push(i);var n="dynamic_"+(this.chatList.length-1>-1?this.chatList.length-1:0).toString();setTimeout((function(){e.scrollview=n}),500)}}),(0,r.default)(a,"beforeDestroy",(function(){s.source&&(s.source.close(),s.source=null)})),a);e.default=m},"89ff":function(t,e,i){"use strict";i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return s})),i.d(e,"a",(function(){return n}));var n={uniIcons:i("6075").default,chatItem:i("9058").default,popup:i("0cc8").default,uPopup:i("7cd7").default},a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"s-back"},[i("v-uni-view",{staticClass:"s-back",style:{paddingTop:t.statusbarHeight+"px"}},[i("v-uni-view",{staticClass:"s-back",staticStyle:{position:"absolute"}},[i("v-uni-view",{staticClass:"s-back"},[i("v-uni-view",{staticClass:"index-line1",staticStyle:{color:"#fff"}},[i("v-uni-view",{staticStyle:{width:"80px"}},[i("uni-icons",{attrs:{type:"back",size:"25",color:"#fff"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.goBack.apply(void 0,arguments)}}})],1),i("v-uni-view",{staticClass:"index2"},[t._v(t._s(t.pageTitle))]),i("v-uni-view",{staticClass:"index3"},[i("v-uni-view",{staticStyle:{width:"90px",height:"10px"}})],1)],1),i("v-uni-scroll-view",{staticClass:"scrollView",style:{background:"#1A2134",paddingBottom:"20px",width:t.screenWidth+"px",height:t.screenHeight-t.bottomHeight-140-t.statusbarHeight+"px",position:"relative"},attrs:{"scroll-y":"true","scroll-x":"true","scroll-top":t.scrollTopHeight,"show-scrollbar":"true","scroll-into-view":t.getScrollView,"scroll-with-animation":"true"}},[i("v-uni-view",{attrs:{id:"scroll-view-content"}},t._l(t.chatList,(function(e,n){return 0==e.isDelete?i("v-uni-view",{key:n,class:"Self"==e.speaker?"index-flex-right":"index-flex-left"},["Self"==e.speaker&&e.isUndo?i("div",{staticClass:"index-no"},[t._v("!")]):t._e(),i("chat-item",{attrs:{dynamicId:n.toString(),speaker:e.speaker,text:e.text,textTemp:t.textTemp,isLoading:n==t.chatList.length-1&&t.isLoading,contentType:e.contentType,imageSrc:e.imageSrc,imageList:t.getImageList,audioSrc:e.audioSrc,createTime:e.createTime,isUndo:e.isUndo},on:{undo:function(e){arguments[0]=e=t.$handleEvent(e),t.undo.apply(void 0,arguments)},delete:function(e){arguments[0]=e=t.$handleEvent(e),t.deleted.apply(void 0,arguments)}}}),"Others"==e.speaker&&1==t.isVoice?i("div",{staticClass:"index-mt12"},[i("v-uni-image",{staticStyle:{width:"50px",height:"50px"},attrs:{src:"/static/img/voice.png"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.getVoice(n)}}})],1):t._e()],1):t._e()})),1),i("div",{staticStyle:{width:"100%",height:"120rpx"}})],1)],1),i("v-uni-view",{staticClass:"bottomClass1",staticStyle:{"background-color":"rgb(21, 21, 32)"},style:"position: absolute;bottom:"+(-76).toString()+"px;left:0;right:0;width:auto;display:block;padding-bottom:0px"},[i("v-uni-view",{staticClass:"new-bottom"},[i("v-uni-view",{staticClass:"index-inp uni-row uni-flex",staticStyle:{"align-content":"center","justify-content":"center"}},[i("div",{staticClass:"example1 out"},[i("div",{staticClass:"inputClass in"},[i("v-uni-textarea",{ref:"inputRef",staticClass:"index-inp-inner",staticStyle:{"font-size":"30rpx"},attrs:{maxlength:-1,"auto-height":!0,placeholder:"输入您想问的...","confirm-type":"send","cursor-spacing":"40"},on:{confirm:function(e){arguments[0]=e=t.$handleEvent(e),t.sendMessage.apply(void 0,arguments)},keyboardheightchange:function(e){arguments[0]=e=t.$handleEvent(e),t.getKeyBoradHeight.apply(void 0,arguments)}},model:{value:t.inputcontent,callback:function(e){t.inputcontent=e},expression:"inputcontent"}})],1)])]),i("uni-icons",{staticStyle:{margin:"0 10px"},attrs:{type:"paperplane",color:"#80E58B",size:"25"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.sendMessage(t.inputcontent)}}})],1),i("v-uni-view",{staticClass:"bottom-bar"},t._l(t.tabbars,(function(e,n){return i("v-uni-view",{staticClass:"item",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.jumpTabbar(e.id)}}},[i("v-uni-image",{staticStyle:{width:"60rpx",height:"60rpx"},attrs:{src:e.pic}}),i("v-uni-view",{staticClass:"text"},[t._v(t._s(e.text))])],1)})),1)],1)],1)],1),i("popup",{ref:"micpop",attrs:{maskColor:!1,bottom:!0,bodyBgColor:"bg-white",id:"micpop"},on:{hide:function(e){arguments[0]=e=t.$handleEvent(e),t.micHide.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"col-1",staticStyle:{height:"100%"}},[i("v-uni-view",{staticClass:"flex align-center justify-center text-xl",staticStyle:{height:"20%"}},[t._v(t._s(t.formatedRecordTime))]),i("v-uni-view",{staticClass:"flex align-center justify-center",staticStyle:{height:"50%"}},[i("v-uni-button",{staticClass:"cu-btn cuIcon  shadow",class:t.setButtonBg,staticStyle:{width:"200rpx",height:"200rpx"},attrs:{id:"micbutton"},on:{touchmove:function(e){arguments[0]=e=t.$handleEvent(e),t.micTouchMove.apply(void 0,arguments)},touchend:function(e){arguments[0]=e=t.$handleEvent(e),t.micTouchEnd.apply(void 0,arguments)},touchstart:function(e){arguments[0]=e=t.$handleEvent(e),t.micTouchStart.apply(void 0,arguments)}}},[i("uni-icons",{attrs:{type:"mic",size:"40"}})],1)],1),0==t.isRecording?i("v-uni-view",{staticClass:"flex align-center justify-center text-xl",staticStyle:{height:"20%"}},[t._v("点击录音")]):t._e(),1==t.isRecording?i("v-uni-view",{staticClass:"flex align-center justify-center text-xl",staticStyle:{height:"20%"}},[t._v("向上滑动取消")]):t._e()],1)],1),i("popup",{ref:"tips",attrs:{maskColor:!1,center:!0,bodyBgColor:"bg-white",mask:!1}},[i("v-uni-view",{staticClass:"col-1 radius",staticStyle:{width:"250rpx",height:"250rpx","background-color":"rgba(113,113,113,0.7)"}},[i("v-uni-view",{staticClass:"flex align-center justify-center ",staticStyle:{height:"50%","margin-top":"30rpx"}},[i("uni-icons",{attrs:{type:"refreshempty",size:"60"}})],1),i("v-uni-view",{staticClass:"flex justify-center align-center ",staticStyle:{color:"#ffffff"}},[t._v("松手取消发送")])],1)],1),i("u-popup",{attrs:{mode:"center","border-radius":"60"},model:{value:t.isShowDele,callback:function(e){t.isShowDele=e},expression:"isShowDele"}},[i("v-uni-view",{staticClass:"edit-chat"},[i("v-uni-view",{staticClass:"title"},[t._v("您确定要清除所有聊天记录?")]),i("v-uni-view",{staticClass:"title2"},[t._v("将清空所有对话中的聊天内容，此操作无法恢复。")]),i("v-uni-view",{staticClass:"center"},[i("v-uni-view",{staticClass:"bottom"},[i("v-uni-view",{staticClass:"btn8",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.isShowDele=!1}}},[t._v("取消")]),i("v-uni-view",{staticClass:"btn9",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.save.apply(void 0,arguments)}}},[t._v("确定")])],1)],1)],1)],1)],1)},s=[]},a31d:function(t,e,i){"use strict";i.r(e);var n=i("745f"),a=i.n(n);for(var s in n)["default"].indexOf(s)<0&&function(t){i.d(e,t,(function(){return n[t]}))}(s);e["default"]=a.a},f996:function(t,e,i){var n=i("08dd");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("b4518eb8",n,!0,{sourceMap:!1,shadowMode:!1})}}]);
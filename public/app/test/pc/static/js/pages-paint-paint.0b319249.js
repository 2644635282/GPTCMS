(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-paint-paint"],{"108f":function(t,e,a){"use strict";var n=a("5bd5"),i=a.n(n);i.a},1568:function(t,e,a){"use strict";a("7a82");var n=a("4ea4").default;Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,a("4de4"),a("d3b7"),a("7db0"),a("159b"),a("2ca0"),a("14d9"),a("d81d"),a("99af");var i=n(a("c7eb")),s=n(a("1da1")),o=n(a("1572")),d=n(a("6d9e")),r=n(a("d2fb")),c=n(a("ef84")),l=n(a("ac9a")),p={components:{viewer:r.default,history:d.default,loading:c.default,uniDataSelect:l.default,uniIcons:l.default},data:function(){return{tab:1,aspectArr:["1:1","2:1","1:2","4:3","3:4","9:16","16:9"],aspect:"1:1",paintDesc:"",brushStyle:"",models:[],imgs:null,currentModel:null,context:o.default,selectModel:!1,sendStatus:0,resultTimer:null,showResultDialog:!1,resultDialogTip:"",histories:[],currentView:null,loadingHistory:!1,chatList:[],chatInfo:{},showCollect:!1,showCollectId:0,showModels:!1,json:null,exampleIndex:0,curIndex:0}},onLoad:function(t){var e=this;return(0,s.default)((0,i.default)().mark((function t(){return(0,i.default)().wrap((function(t){while(1)switch(t.prev=t.next){case 0:e.getPaintConfig(),e.$u.post("/hxai/api/paint/getpaintset").then((function(t){e.models=t.data.paintmodel.filter((function(t){return t.status})),e.showModels=1==t.data.paintmodel_status;var a=uni.getStorageSync("paintmodel");a&&(e.currentModel=e.models.find((function(t){return t.type==a}))),e.currentModel||(e.currentModel=e.models[0])})).catch((function(t){})),document.title=o.default.appConfig.title;case 3:case"end":return t.stop()}}),t)})))()},computed:{currentImage:function(){return this.chatList.length?this.chatList[this.curIndex].content:""}},beforeMount:function(){this.loadHistory()},methods:{getPaintConfig:function(){var t=this;this.$u.api.paintConfig({}).then((function(e){var a=null;try{a=JSON.parse(e.data.config),a.entry&&a.entry.length&&a.entry.forEach((function(t){t.entryData.forEach((function(t){t.text=t.key}))}))}catch(n){}a.example.length&&(t.paintDesc=a.example[0].desc),t.json=a}))},randomExample:function(){this.exampleIndex<this.json.example.length-1?this.exampleIndex++:this.exampleIndex=0,this.paintDesc=this.json.example[this.exampleIndex].desc},loadHistory:function(){var t=this,e=[];this.loadingHistory=!0,this.$u.post("/hxai/api/paint/msgs").then((function(a){var n={};a.data.forEach((function(t){var a;t.id&&"助手"==t.role&&""!==t.content&&null!=t.content&&null!==(a=t.content)&&void 0!==a&&a.startsWith("http")&&(n={id:t.id||0,role:t.role||"",status:t.status||0,content:t.content||""},e.push(n))})),t.chatList=e,t.histories=a.data.filter((function(t){var e;return null===(e=t.content)||void 0===e?void 0:e.startsWith("http")})).map((function(t){return t.content})),t.chatList.length&&(t.chatInfo=t.chatList[0])})).finally((function(){t.loadingHistory=!1}))},selectM:function(){var t=this;this.selectModel=!0,this.$nextTick((function(){t.$refs.modelDrop.focus()}))},chooseModel:function(t){this.currentModel=t,uni.setStorageSync("paintmodel",t.type)},viewImage:function(t){this.curIndex=t.curIndex,this.chatInfo=this.chatList[t.curIndex]},download:function(t){var e=document.createElement("a");e.href=t,e.download=t.split("/").pop(),e.click()},copyLink:function(t){var e=this;return(0,s.default)((0,i.default)().mark((function a(){return(0,i.default)().wrap((function(a){while(1)switch(a.prev=a.next){case 0:return a.next=2,navigator.clipboard.writeText(t);case 2:e.$toast.center("链接已复制到剪切板");case 3:case"end":return a.stop()}}),a)})))()},viewVBigImage:function(){this.imgs=this.histories.map((function(t){return{src:t}})),this.currentView=this.imgs[this.curIndex]},send:function(){var t=this;if(!this.context.userInfo)return uni.$emit("requireLogin","");this.sendStatus=1;var e="";this.json&&this.json.direct&&(e=this.json.direct.map((function(t){return t.engine})).filter(Boolean).join(" "));var a=this.json&&this.json.entry.map((function(t){return t.entryText})).filter(Boolean).join(" "),n="".concat(this.paintDesc," --ar").concat(this.aspect?" "+this.aspect:"").concat(e?" "+e:"").concat(a?" "+a:"").concat(this.brushStyle?" "+this.brushStyle:"");this.showResultDialog=!0,this.$u.post("/hxai/api/tool/painttextcheck",{text:n,chatmodel:this.currentModel.type}).then((function(e){e&&(t.sendStatus=2,t.$u.post("/hxai/api/paint/send",{message:n,chatmodel:t.currentModel.type}).then((function(e){var a=e.data.msgid;clearInterval(t.resultTimer),t.resultTimer=setInterval((function(){t.getPaintResult(a)}),4e3)})).finally((function(){})).catch((function(e){t.sendStatus=0,t.resultDialogTip=e.data.msg})))})).catch((function(e){t.sendStatus=0,t.resultDialogTip=e.data.msg}))},getPaintResult:function(t){var e=this;this.$u.post("/hxai/api/paint/getmsgreult",{msgid:t}).then((function(t){"string"==typeof t.data&&(t.data.startsWith("http")||t.data.startsWith("https"))&&(clearInterval(e.resultTimer),e.showResultDialog=!1,e.sendStatus=0,e.curIndex=0,e.loadHistory())})).catch((function(t){e.resultDialogTip=t.data.msg}))},viewCanvas:function(){this.currentImage&&(this.imgs=[{src:this.currentImage}])},closeResult:function(){clearInterval(this.resultTimer),this.showResultDialog=!1,this.sendStatus=0},onclickCollect:function(t){var e=this;console.log(t),1==t.status?(this.showCollectId=t.id||0,this.showCollect=!0):this.$u.api.paintCollect({collect_id:t.id||0}).then((function(a){e.$u.toast(a.msg),e.showCollect=!1;var n=e.chatList;n.forEach((function(e,a){e.id==t.id&&(1==e.status?n[a].status=0:n[a].status=1)})),e.chatList=n,e.chatList.length&&(e.chatInfo=e.chatList[e.chatList.length-1])})).catch((function(t){console.log(t)}))},collectSession:function(){var t=this;return(0,s.default)((0,i.default)().mark((function e(){return(0,i.default)().wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,t.$u.api.paintCollect({collect_id:t.showCollectId||0}).then((function(e){t.$u.toast(e.msg),t.showCollect=!1;var a=t.chatList;a.forEach((function(e,n){e.id==t.showCollectId&&(1==e.status?a[n].status=0:a[n].status=1)})),t.chatList=a,t.chatList.length&&(t.chatInfo=t.chatList[t.chatList.length-1])})).catch((function(t){console.log(t)}));case 2:e.sent;case 3:case"end":return e.stop()}}),e)})))()}}};e.default=p},"31bc":function(t,e,a){"use strict";a.d(e,"b",(function(){return i})),a.d(e,"c",(function(){return s})),a.d(e,"a",(function(){return n}));var n={uniIcons:a("ac9a").default,uIcon:a("c628").default,uPopup:a("4260").default},i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"paint-workplace"},[a("div",{staticClass:"tool-pane"},[a("div",{staticClass:"tabs"},[a("div",{staticClass:"tab gpticon-create",class:{active:1==t.tab},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.tab=1}}},[t._v("文生图")]),a("div",{staticClass:"tab gpticon-history",class:{active:2==t.tab},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.tab=2}}},[t._v("历史记录")])]),a("div",{staticClass:"panes"},[1==t.tab?a("div",{staticClass:"pane",staticStyle:{display:"flex","flex-direction":"column",height:"100%",overflow:"hidden"}},[a("div",{staticClass:"pane-group",staticStyle:{position:"sticky",top:"0","z-index":"10",background:"var(--main-bg)"}},[a("div",{staticClass:"group-header"},[t._v("绘图描述")]),a("div",{staticClass:"group-content"},[a("div",{staticClass:"paint-chat-wrapper"},[a("v-uni-textarea",{staticClass:"textarea",staticStyle:{height:"180px"},attrs:{maxlength:"600"},model:{value:t.paintDesc,callback:function(e){t.paintDesc="string"===typeof e?e.trim():e},expression:"paintDesc"}}),a("div",{staticClass:"chat-toolbar"},[a("div",{staticClass:"clear gpticon-delete",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.paintDesc=""}}},[t._v("清空描述")]),t.json&&t.json.example&&t.json.example.length?a("div",{staticClass:"clear gpticon-reload",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.randomExample.apply(void 0,arguments)}}},[t._v("随机示例")]):t._e()])],1)])]),a("div",{staticStyle:{flex:"1","overflow-y":"overlay"}},[a("div",{staticClass:"pane-group"},[a("div",{staticClass:"group-header"},[t._v("图片比例")]),a("div",{staticClass:"group-content aspects"},t._l(t.aspectArr,(function(e,n){return a("div",{key:n,staticClass:"size-item",class:["gpticon-size-"+e.replace(":","-"),t.aspect===e?"active":""],on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.aspect=t.aspect===e?"":e}}},[t._v(t._s(e))])})),0)]),t.json?a("div",[t._l(t.json.direct,(function(e,n){return a("div",{key:n,staticClass:"pane-group"},[a("div",{staticClass:"group-header"},[t._v(t._s(e.label))]),a("div",{staticClass:"group-content engines"},t._l(e.directData,(function(n,i){return a("div",{key:i,staticClass:"engine-item aspect1",class:{active:e.engine==n.value},on:{click:function(a){arguments[0]=a=t.$handleEvent(a),e.engine=e.engine===n.value?"":n.value,t.$forceUpdate()}}},[t._v(t._s(n.key))])})),0)])})),t.json.entry&&t.json.entry.length?a("div",{staticClass:"pane-group"},[a("div",{staticClass:"group-header"},[t._v("辅助词条")]),a("div",{staticClass:"group-content engines"},t._l(t.json.entry,(function(e,n){return a("div",{key:n},[a("uni-icons",{attrs:{localdata:e.entryData,placeholder:e.label},model:{value:e.entryText,callback:function(a){t.$set(e,"entryText",a)},expression:"item.entryText"}})],1)})),0)]):t._e(),t.json.style&&t.json.style.length?a("div",{staticClass:"pane-group"},[a("div",{staticClass:"group-header"},[t._v("绘画风格")]),a("div",{staticClass:"group-content paint-styles"},t._l(t.json.style,(function(e,n){return a("div",{key:n,staticClass:"style-item-wrapper",class:{active:t.brushStyle==e.instruct}},[a("div",{staticClass:"style-item",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.brushStyle=t.brushStyle==e.instruct?"":e.instruct}}},[a("div",{staticClass:"style-icon",style:{backgroundImage:"url("+e.img+")"}}),a("div",{staticClass:"style-desc"},[t._v(t._s(e.name))])])])})),0)]):t._e()],2):t._e()]),t.currentModel?a("div",{staticClass:"submit"},[a("div",{staticClass:"model-wrapper"},[t.currentModel&&t.showModels?[a("div",{staticClass:"model-select"},[t.selectModel?a("div",{ref:"modelDrop",staticClass:"models",attrs:{tabindex:"1"},on:{blur:function(e){arguments[0]=e=t.$handleEvent(e),t.selectModel=!1}}},t._l(t.models,(function(e){return a("div",{staticClass:"model-item",attrs:{tabindex:"1"},on:{mousedown:function(a){arguments[0]=a=t.$handleEvent(a),t.chooseModel(e)}}},[a("div",{staticClass:"label"},[t._v(t._s(e.nickname))]),t.currentModel==e?a("div",{staticClass:"check gpticon-check"}):t._e()])})),0):t._e(),a("div",{staticClass:"model-value",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.selectM.apply(void 0,arguments)}}},[a("div",{staticClass:"value"},[t._v(t._s(t.currentModel.nickname))]),a("div",{staticClass:"select-icon gpticon-drop"})])]),a("div",[t._v(t._s(t.currentModel.expend)+t._s(t.context.unit)+"/次")])]:t._e()],2),a("v-uni-button",{attrs:{disabled:!t.paintDesc||0!=t.sendStatus||t.context.userInfo&&t.context.userInfo.residue_degree<t.currentModel.expend},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.send()}}},[t._v(t._s(t.context.userInfo&&t.context.userInfo.residue_degree<t.currentModel.expend?"余额不足":"开始绘画"))])],1):t._e()]):t._e(),2==t.tab?a("history",{staticClass:"pane",attrs:{histories:t.histories,index:t.curIndex},on:{viewimg:function(e){arguments[0]=e=t.$handleEvent(e),t.viewImage.apply(void 0,arguments)}}}):t._e()],1)]),a("div",{staticClass:"paint-canvas"},[a("div",{staticClass:"canvas-holder",class:{hasImg:t.currentImage},style:{backgroundImage:"url("+t.currentImage+")"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.viewCanvas.apply(void 0,arguments)}}},[t.currentImage||t.loadingHistory?t._e():[a("div",{staticClass:"startup-icon"}),a("div",{staticClass:"startup-desc"},[t._v("请输入绘画描述开始绘画")])],t.loadingHistory?a("loading",{staticStyle:{position:"relative",top:"50%",left:"50%",transform:"translate(-50%, -50%)"}}):t._e()],2),t.chatInfo.content?a("v-uni-view",{staticClass:"btns"},[a("v-uni-view",{staticClass:"btns-item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.download(t.chatInfo.content)}}},[a("span",{staticClass:"btns-item-icon gpticon-download"}),a("span",[t._v("下载")])]),a("v-uni-view",{staticClass:"btns-item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.copyLink(t.chatInfo.content)}}},[a("span",{staticClass:"btns-item-icon gpticon-copylink"}),a("span",[t._v("链接")])]),a("v-uni-view",{staticClass:"btns-item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.viewVBigImage()}}},[a("u-icon",{staticClass:"btns-item-icon",attrs:{name:"search",color:"#666",size:"30"}}),a("span",[t._v("放大")])],1),a("v-uni-view",{staticClass:"btns-item",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.onclickCollect(t.chatInfo)}}},[a("img",{staticStyle:{width:"15px",height:"16px","margin-bottom":"10px"},attrs:{src:1==t.chatInfo.status?"/app/hxai/pc/static/icons/my/Vector-2.svg":"/app/hxai/pc/static/icons/my/Vector.svg",alt:""}}),a("span",[t._v(t._s(1==t.chatInfo.status?"取消收藏":"收藏"))])])],1):t._e()],1),t.imgs?a("viewer",{staticClass:"paint-one",staticStyle:{"z-index":"1000",position:"fixed",top:"0",left:"0",right:"0",bottom:"0",background:"#00000070"},attrs:{imgs:t.imgs,curImage:t.currentView},on:{close:function(e){arguments[0]=e=t.$handleEvent(e),t.imgs=null,t.currentView=null}}}):t._e(),a("u-popup",{attrs:{mode:"center","border-radius":"20","mask-close-able":!1},model:{value:t.showResultDialog,callback:function(e){t.showResultDialog=e},expression:"showResultDialog"}},[a("div",{staticClass:"paint-result-dialog"},[a("div",{staticClass:"dialog-header"},[a("div",{staticClass:"dialog-title"},[t._v("正在生成您的作品")]),a("div",{staticClass:"dialog-close gpticon-close",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeResult.apply(void 0,arguments)}}})]),a("div",{staticClass:"paint-result-tip"},[a("loading"),t.resultDialogTip?a("div",[t._v(t._s(t.resultDialogTip))]):[a("div",[t._v("预计耗时 10~60秒")]),a("div",{staticStyle:{"margin-top":"6px"}},[t._v("您可以退出本页，稍后返回查看您的画作")])]],2)])]),a("u-popup",{attrs:{mode:"center","border-radius":"10"},model:{value:t.showCollect,callback:function(e){t.showCollect=e},expression:"showCollect"}},[a("v-uni-view",{staticClass:"deleteSession"},[a("v-uni-view",{staticClass:"confirm-title"},[t._v("您确定要取消收藏吗?")]),a("v-uni-view",{staticClass:"confirm-msg"},[t._v("取消收藏后，可能再也找不到了哦。")]),a("v-uni-view",{staticClass:"confirm-btn"},[a("v-uni-view",{staticClass:"confirm-cancel",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.showCollect=!1}}},[t._v("取消")]),a("v-uni-view",{staticClass:"confirm-ok",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.collectSession.apply(void 0,arguments)}}},[t._v("确定")])],1)],1)],1)],1)},s=[]},"3bb4":function(t,e,a){t.exports=a.p+"static/img/Vector.6dcf019f.svg"},"5bd5":function(t,e,a){var n=a("d568");n.__esModule&&(n=n.default),"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var i=a("4f06").default;i("32b6a996",n,!0,{sourceMap:!1,shadowMode:!1})},8321:function(t,e,a){"use strict";a.r(e);var n=a("31bc"),i=a("c381");for(var s in i)["default"].indexOf(s)<0&&function(t){a.d(e,t,(function(){return i[t]}))}(s);a("108f");var o=a("f0c5"),d=Object(o["a"])(i["default"],n["b"],n["c"],!1,null,"34dd0dd6",null,!1,n["a"],void 0);e["default"]=d.exports},c381:function(t,e,a){"use strict";a.r(e);var n=a("1568"),i=a.n(n);for(var s in n)["default"].indexOf(s)<0&&function(t){a.d(e,t,(function(){return n[t]}))}(s);e["default"]=i.a},d568:function(t,e,a){var n=a("24fb"),i=a("1de5"),s=a("3bb4"),o=a("3903"),d=a("c876");e=n(!1);var r=i(s),c=i(o),l=i(d);e.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 下方引入的为uView UI的集成样式文件，为scss预处理器，其中包含了一些"u-"开头的自定义变量\r\n * 使用的时候，请将下面的一行复制到您的uniapp项目根目录的uni.scss中即可\r\n * uView自定义的css类名和scss变量，均以"u-"开头，不会造成冲突，请放心使用 \r\n */.deleteSession[data-v-34dd0dd6]{padding:20px;display:flex;flex-direction:column;width:300px}.deleteSession .confirm-title[data-v-34dd0dd6]{font-size:20px}.deleteSession .confirm-msg[data-v-34dd0dd6]{margin:30px 0}.deleteSession .confirm-btn[data-v-34dd0dd6]{display:flex;align-items:center;justify-content:space-between;-webkit-user-select:none;user-select:none}.deleteSession .confirm-btn .confirm-cancel[data-v-34dd0dd6]{background:#e7fcf9}.deleteSession .confirm-btn .confirm-ok[data-v-34dd0dd6]{color:#fff;background:#fe4f2f}.deleteSession .confirm-btn > *[data-v-34dd0dd6]{padding:10px 30px;cursor:pointer;border-radius:10px}.a-btns[data-v-34dd0dd6]{display:flex;justify-content:end;padding:0 20px 20px;position:absolute;right:0;-webkit-user-select:none;user-select:none}.a-btns .icon-star[data-v-34dd0dd6]{cursor:pointer;background:#fff}.a-btns > *[data-v-34dd0dd6]{border-radius:30px;padding:6px 10px;font-size:12px;line-height:1.4em}.a-btns > *[data-v-34dd0dd6]:before{margin-right:3px;position:relative;top:2px}.a-btns .gpticon-star[data-v-34dd0dd6]::before{background-position:50%;background-repeat:no-repeat;background-image:url('+r+");background-size:12px}.a-btns .gpticon-star[data-v-34dd0dd6]{font-size:12px;margin-top:14px}.a-btns .gpticon-stared[data-v-34dd0dd6]{font-size:12px;color:#fe4f2f;margin-top:14px}.a-btns .gpticon-stared[data-v-34dd0dd6]::before{background-image:url("+r+");background-size:12px;background-position:50%;background-repeat:no-repeat}.paint-result-tip[data-v-34dd0dd6]{width:300px;padding:20px;text-align:center;font-size:13px}.paint-result-tip .paint-loading[data-v-34dd0dd6]{padding:40%;margin:10px 0;background-position:50%;background-size:contain;background-repeat:no-repeat}*[data-v-34dd0dd6]{outline:none}.paint-canvas[data-v-34dd0dd6]{overflow:hidden;position:relative;padding:20px;display:flex;flex-direction:column;justify-content:center;align-items:center}.paint-canvas .canvas-holder[data-v-34dd0dd6]{display:flex;flex-direction:column;background:var(--main-bg);background-size:contain;background-position:50%;background-repeat:no-repeat;border-radius:var(--large-radius);-webkit-user-select:none;user-select:none;width:600px;height:600px;cursor:pointer}.paint-canvas .canvas-holder[data-v-34dd0dd6]:not(.hasImg){padding:65px 100px;cursor:default;justify-content:center;align-items:center}.paint-canvas .canvas-holder .startup-icon[data-v-34dd0dd6]{width:160px;height:160px;background-image:url("+c+");background-position:50%;background-repeat:no-repeat;background-size:contain}.paint-canvas .canvas-holder .startup-desc[data-v-34dd0dd6]{margin-top:30px;color:var(--color-secondary);font-size:13px;text-align:center}.paint-canvas .btns[data-v-34dd0dd6]{margin-top:40px;display:flex}.paint-canvas .btns .btns-item[data-v-34dd0dd6]{display:flex;flex-direction:column;align-items:center;cursor:pointer;font-size:14px;margin:0 30px;color:#666}.paint-canvas .btns .btns-item .btns-item-icon[data-v-34dd0dd6]{margin-bottom:10px}.paint-canvas .btns .btns-item .gpticon-cancel[data-v-34dd0dd6]{background-color:#f7f7f7;width:12px;height:12px;color:#fe4f2f}.paint-canvas .btns .btns-item .gpticon-cancel[data-v-34dd0dd6]::before{width:12px;height:12px;background-image:url("+l+');background-size:12px;background-color:#fff;background-position:50%;background-repeat:no-repeat}.paint-workplace[data-v-34dd0dd6]{display:flex;align-items:stretch;height:100%;overflow:hidden\r\n  /* 输入框 */}.paint-workplace .paint-styles[data-v-34dd0dd6]{display:grid;grid-template-columns:1fr 1fr 1fr;-webkit-column-gap:8px;column-gap:8px;row-gap:8px}.paint-workplace .paint-styles .style-item-wrapper[data-v-34dd0dd6]{padding:3px;border-radius:6px;border:2px solid transparent}.paint-workplace .paint-styles .style-item-wrapper.active[data-v-34dd0dd6]{border:2px solid var(--theme-border-color)}.paint-workplace .paint-styles .style-item[data-v-34dd0dd6]{text-align:center;position:relative;border-radius:4px;overflow:hidden;-webkit-user-select:none;user-select:none;cursor:pointer}.paint-workplace .paint-styles .style-item .style-icon[data-v-34dd0dd6]{padding:50%;background-size:contain;background-position:50%}.paint-workplace .paint-styles .style-item .style-desc[data-v-34dd0dd6]{position:absolute;bottom:0;left:0;right:0;background:rgba(0,0,0,.2);font-size:12px;line-height:1.8em;color:#fff}.paint-workplace .submit[data-v-34dd0dd6]{padding:var(--large-gap);position:-webkit-sticky;position:sticky;bottom:0;z-index:10;background:var(--main-bg)}.paint-workplace .submit uni-button[data-v-34dd0dd6]{-webkit-user-select:none;user-select:none;border-radius:40px;font-size:16px;padding:16px 0;line-height:1;background:linear-gradient(95.9deg,#9fffbe -.66%,#0cffec 101.97%)}.paint-workplace .submit uni-button[data-v-34dd0dd6]::after, .paint-workplace .submit uni-button[data-v-34dd0dd6]::before{display:none}.paint-workplace .panes[data-v-34dd0dd6]{flex:1;position:relative;overflow:auto}.paint-workplace .engines[data-v-34dd0dd6]{display:grid;grid-template-columns:1fr 1fr;-webkit-column-gap:10px;column-gap:10px}.paint-workplace .engines .engine-item[data-v-34dd0dd6]{color:#999;background:#f6f7f9;padding:8px 10px;border-radius:5px;text-align:center;-webkit-user-select:none;user-select:none;cursor:pointer;font-size:14px;margin-bottom:10px}.paint-workplace .engines .engine-item.active[data-v-34dd0dd6]{background:#e7fcf9;color:#0bddc3}.paint-workplace .aspects[data-v-34dd0dd6]{display:grid;grid-template-columns:1fr 1fr 1fr 1fr 1fr 1fr 1fr;-webkit-column-gap:6px;column-gap:6px;text-align:center;font-size:12px}.paint-workplace .aspects .size-item[data-v-34dd0dd6]{display:flex;flex-direction:column;align-items:center;cursor:pointer;-webkit-user-select:none;user-select:none;padding:6px 3px;border-radius:3px;color:#999;background:#f6f7f9}.paint-workplace .aspects .size-item.active[data-v-34dd0dd6]{background:#e7fcf9;color:#0bddc3}.paint-workplace .aspects .size-item[data-v-34dd0dd6]:before{margin-bottom:3px;font-size:18px}.paint-workplace *[data-v-34dd0dd6]{box-sizing:border-box}.paint-workplace .tool-pane[data-v-34dd0dd6]{width:320px;background:var(--main-bg);border-left:var(--main-border);display:flex;flex-direction:column;overflow:hidden}.paint-workplace .tool-pane .tabs[data-v-34dd0dd6]{display:flex;-webkit-user-select:none;user-select:none;cursor:pointer}.paint-workplace .tool-pane .tabs .tab[data-v-34dd0dd6]{flex:1;text-align:center;padding:10px 0 15px;font-size:13px;font-weight:800;position:0;position:relative}.paint-workplace .tool-pane .tabs .tab[data-v-34dd0dd6]::before{display:inline-block;margin-right:6px;position:relative;top:2px}.paint-workplace .tool-pane .tabs .tab.active[data-v-34dd0dd6]:before{color:var(--theme-bg)}.paint-workplace .tool-pane .tabs .tab.active[data-v-34dd0dd6]::after{content:"";display:block;position:absolute;bottom:0;width:8em;left:0;right:0;margin:auto;background:var(--theme-bg);height:3px}.paint-workplace .tool-pane .tabs .tab[data-v-34dd0dd6]:not(.active){color:var(--secondary-color)}.paint-workplace .group-header[data-v-34dd0dd6]{padding:var(--large-gap) var(--large-gap) 14px;font-size:14px;font-weight:800}.paint-workplace .group-content[data-v-34dd0dd6]{padding:0 var(--large-gap)}.paint-workplace .paint-chat-wrapper[data-v-34dd0dd6]{border:1px solid var(--theme-border-color);border-radius:var(--large-radius);overflow:hidden;display:flex;flex-direction:column}.paint-workplace .paint-chat-wrapper .textarea[data-v-34dd0dd6]{padding:10px;width:100%;font-size:13px}.paint-workplace .paint-chat-wrapper .chat-toolbar[data-v-34dd0dd6]{display:flex;align-items:center;justify-content:space-between;color:var(--color-secondary);font-size:12px;padding:10px}.paint-workplace .paint-chat-wrapper .chat-toolbar div[data-v-34dd0dd6]{display:flex;align-items:center;-webkit-user-select:none;user-select:none;cursor:pointer}.paint-workplace .paint-chat-wrapper .chat-toolbar div[data-v-34dd0dd6]:before{display:inline-block;margin-right:5px;position:relative;top:2px}.paint-workplace .paint-canvas[data-v-34dd0dd6]{flex:1}.paint-workplace .model-wrapper[data-v-34dd0dd6]{display:flex;align-items:center;border-radius:20px;position:relative;z-index:1;font-size:12px;color:#888;flex:1;margin-right:20px;margin-bottom:8px}.paint-workplace .model-wrapper .model-select[data-v-34dd0dd6]{margin-right:10px;cursor:pointer;flex:1}.paint-workplace .model-wrapper .models[data-v-34dd0dd6]{padding:10px;position:absolute;background:#fff;bottom:100%;box-shadow:0 0 8px rgba(0,0,0,.1);margin-bottom:4px;border-radius:5px;outline:none;padding:6px}.paint-workplace .model-wrapper .models .model-item[data-v-34dd0dd6]{font-size:13px;display:flex;align-items:center;padding:5px 6px;border-radius:3px}.paint-workplace .model-wrapper .models .model-item[data-v-34dd0dd6]:hover{background:#e7fcf9}.paint-workplace .model-wrapper .models .model-item .label[data-v-34dd0dd6]{margin-right:20px;flex:1}.paint-workplace .model-wrapper .models .model-item[data-v-34dd0dd6]:not(:last-child){margin-bottom:3px}.paint-workplace .model-wrapper .models .model-item .check[data-v-34dd0dd6]{color:#42cbbb}.paint-workplace .model-wrapper .model-value[data-v-34dd0dd6]{display:inline-flex;align-items:center;padding:5px 14px;border-radius:90px;box-shadow:0 0 8px rgba(0,0,0,.1)}.paint-workplace .model-wrapper .model-value .value[data-v-34dd0dd6]{margin-right:5px}',""]),t.exports=e}}]);
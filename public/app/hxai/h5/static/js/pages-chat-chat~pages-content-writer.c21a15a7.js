(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-chat-chat~pages-content-writer"],{"06b7":function(t,e,n){t.exports=n.p+"static/img/Vector-1.f353a102.svg"},2680:function(t,e,n){"use strict";n("7a82"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,n("a9e3"),n("99af"),n("ac1f");var o={props:{maskColor:{type:Boolean,default:!1},mask:{type:Boolean,default:!0},center:{type:Boolean,default:!1},bottom:{type:Boolean,default:!1},bodyWidth:{type:Number,default:0},bodyHeight:{type:Number,default:0},bodyBgColor:{type:String,default:"bg-opacity"},transformOrigin:{type:String,default:"left top"},tabbarHeight:{type:Number,default:0}},data:function(){return{status:!1,x:-1,y:-1,maxX:0,maxY:0}},mounted:function(){try{var t=uni.getSystemInfoSync();this.maxX=t.windowWidth-uni.upx2px(this.bodyWidth),this.maxY=t.windowHeight-uni.upx2px(this.bodyHeight)-uni.upx2px(this.tabbarHeight)}catch(e){}},computed:{getMaskColor:function(){var t=this.maskColor?.5:0;return"background-color: rgba(0,0,0,".concat(t,");")},getBodyClass:function(){if(this.center)return"left-0 right-0 bottom-0 top-0 flex align-center justify-center";var t=this.bottom?"left-0 right-0 bottom-0":"";return"".concat(this.bodyBgColor," ").concat(t)},getBodyStyle:function(){var t=this.x>-1?"left:".concat(this.x,"px;"):"",e=this.y>-1?"top:".concat(this.y,"px;"):"";return t+e}},methods:{show:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:-1,e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:-1;this.status||(t>this.maxX&&this.$emit("outx"),this.x=t>this.maxX?this.maxX-uni.upx2px(.55*this.bodyWidth):t-uni.upx2px(this.bodyWidth/2),this.y=e>this.maxY?this.maxY:e+10,this.status=!0)},hide:function(){this.$emit("hide"),this.status=!1},showNodeInfo:function(t){uni.createSelectorQuery().in(this).select(t).boundingClientRect((function(t){console.log(t)})).exec()}}};e.default=o},3612:function(t,e,n){(function(t){var o,r,i,a=n("7037").default;n("d3b7"),n("d9e2"),n("d401"),n("c975"),n("ac1f"),n("5319"),n("fb6a"),n("14d9"),n("e25e"),n("00b4"),n("6c57"),
/** @license
 * eventsource.js
 * Available under MIT License (MIT)
 * https://github.com/Yaffle/EventSource/
 */
function(n){"use strict";var s=n.setTimeout,d=n.clearTimeout,c=n.XMLHttpRequest,u=n.XDomainRequest,l=n.ActiveXObject,f=n.EventSource,h=n.document,p=n.Promise,v=n.fetch,y=n.Response,g=n.TextDecoder,b=n.TextEncoder,m=n.AbortController;if("undefined"===typeof window||"undefined"===typeof h||"readyState"in h||null!=h.body||(h.readyState="loading",window.addEventListener("load",(function(t){h.readyState="complete"}),!1)),null==c&&null!=l&&(c=function(){return new l("Microsoft.XMLHTTP")}),void 0==Object.create&&(Object.create=function(t){function e(){}return e.prototype=t,new e}),Date.now||(Date.now=function(){return(new Date).getTime()}),void 0==m){var w=v;v=function(t,e){var n=e.signal;return w(t,{headers:e.headers,credentials:e.credentials,cache:e.cache}).then((function(t){var e=t.body.getReader();return n._reader=e,n._aborted&&n._reader.cancel(),{status:t.status,statusText:t.statusText,headers:t.headers,body:{getReader:function(){return e}}}}))},m=function(){this.signal={_reader:null,_aborted:!1},this.abort=function(){null!=this.signal._reader&&this.signal._reader.cancel(),this.signal._aborted=!0}}}function x(){this.bitsNeeded=0,this.codePoint=0}x.prototype.decode=function(t){function e(t,e,n){if(1===n)return t>=128>>e&&t<<e<=2047;if(2===n)return t>=2048>>e&&t<<e<=55295||t>=57344>>e&&t<<e<=65535;if(3===n)return t>=65536>>e&&t<<e<=1114111;throw new Error}function n(t,e){if(6===t)return e>>6>15?3:e>31?2:1;if(12===t)return e>15?3:2;if(18===t)return 3;throw new Error}for(var o="",r=this.bitsNeeded,i=this.codePoint,a=0;a<t.length;a+=1){var s=t[a];0!==r&&(s<128||s>191||!e(i<<6|63&s,r-6,n(r,i)))&&(r=0,i=65533,o+=String.fromCharCode(i)),0===r?(s>=0&&s<=127?(r=0,i=s):s>=192&&s<=223?(r=6,i=31&s):s>=224&&s<=239?(r=12,i=15&s):s>=240&&s<=247?(r=18,i=7&s):(r=0,i=65533),0===r||e(i,r,n(r,i))||(r=0,i=65533)):(r-=6,i=i<<6|63&s),0===r&&(i<=65535?o+=String.fromCharCode(i):(o+=String.fromCharCode(55296+(i-65535-1>>10)),o+=String.fromCharCode(56320+(i-65535-1&1023))))}return this.bitsNeeded=r,this.codePoint=i,o};void 0!=g&&void 0!=b&&function(){try{return"test"===(new g).decode((new b).encode("test"),{stream:!0})}catch(t){console.debug("TextDecoder does not support streaming option. Using polyfill instead: "+t)}return!1}()||(g=x);var E=function(){};function T(t){this.withCredentials=!1,this.readyState=0,this.status=0,this.statusText="",this.responseText="",this.onprogress=E,this.onload=E,this.onerror=E,this.onreadystatechange=E,this._contentType="",this._xhr=t,this._sendTimeout=0,this._abort=E}function _(t){return t.replace(/[A-Z]/g,(function(t){return String.fromCharCode(t.charCodeAt(0)+32)}))}function C(t){for(var e=Object.create(null),n=t.split("\r\n"),o=0;o<n.length;o+=1){var r=n[o],i=r.split(": "),a=i.shift(),s=i.join(": ");e[_(a)]=s}this._map=e}function S(){}function O(t){this._headers=t}function R(){}function H(){this._listeners=Object.create(null)}function D(t){s((function(){throw t}),0)}function N(t){this.type=t,this.target=void 0}function j(t,e){N.call(this,t),this.data=e.data,this.lastEventId=e.lastEventId}function A(t,e){N.call(this,t),this.status=e.status,this.statusText=e.statusText,this.headers=e.headers}function k(t,e){N.call(this,t),this.error=e.error}T.prototype.open=function(t,e){this._abort(!0);var n=this,o=this._xhr,r=1,i=0;this._abort=function(t){0!==n._sendTimeout&&(d(n._sendTimeout),n._sendTimeout=0),1!==r&&2!==r&&3!==r||(r=4,o.onload=E,o.onerror=E,o.onabort=E,o.onprogress=E,o.onreadystatechange=E,o.abort(),0!==i&&(d(i),i=0),t||(n.readyState=4,n.onabort(null),n.onreadystatechange())),r=0};var a=function(){if(1===r){var t=0,e="",i=void 0;if("contentType"in o)t=200,e="OK",i=o.contentType;else try{t=o.status,e=o.statusText,i=o.getResponseHeader("Content-Type")}catch(a){t=0,e="",i=void 0}0!==t&&(r=2,n.readyState=2,n.status=t,n.statusText=e,n._contentType=i,n.onreadystatechange())}},u=function(){if(a(),2===r||3===r){r=3;var t="";try{t=o.responseText}catch(e){}n.readyState=3,n.responseText=t,n.onprogress()}},l=function(t,e){if(null!=e&&null!=e.preventDefault||(e={preventDefault:E}),u(),1===r||2===r||3===r){if(r=4,0!==i&&(d(i),i=0),n.readyState=4,"load"===t)n.onload(e);else if("error"===t)n.onerror(e);else{if("abort"!==t)throw new TypeError;n.onabort(e)}n.onreadystatechange()}};"onload"in o&&(o.onload=function(t){l("load",t)}),"onerror"in o&&(o.onerror=function(t){l("error",t)}),"onabort"in o&&(o.onabort=function(t){l("abort",t)}),"onprogress"in o&&(o.onprogress=u),"onreadystatechange"in o&&(o.onreadystatechange=function(t){(function(t){void 0!=o&&(4===o.readyState?"onload"in o&&"onerror"in o&&"onabort"in o||l(""===o.responseText?"error":"load",t):3===o.readyState?"onprogress"in o||u():2===o.readyState&&a())})(t)}),!("contentType"in o)&&"ontimeout"in c.prototype||(e+=(-1===e.indexOf("?")?"?":"&")+"padding=true"),o.open(t,e,!0),"readyState"in o&&(i=s((function(){(function t(){i=s((function(){t()}),500),3===o.readyState&&u()})()}),0))},T.prototype.abort=function(){this._abort(!1)},T.prototype.getResponseHeader=function(t){return this._contentType},T.prototype.setRequestHeader=function(t,e){var n=this._xhr;"setRequestHeader"in n&&n.setRequestHeader(t,e)},T.prototype.getAllResponseHeaders=function(){return void 0!=this._xhr.getAllResponseHeaders&&this._xhr.getAllResponseHeaders()||""},T.prototype.send=function(){if("ontimeout"in c.prototype&&("sendAsBinary"in c.prototype||"mozAnon"in c.prototype)||void 0==h||void 0==h.readyState||"complete"===h.readyState){var t=this._xhr;"withCredentials"in t&&(t.withCredentials=this.withCredentials);try{t.send(void 0)}catch(n){throw n}}else{var e=this;e._sendTimeout=s((function(){e._sendTimeout=0,e.send()}),4)}},C.prototype.get=function(t){return this._map[_(t)]},null!=c&&null==c.HEADERS_RECEIVED&&(c.HEADERS_RECEIVED=2),S.prototype.open=function(t,e,n,o,r,i,a){t.open("GET",r);var s=0;for(var d in t.onprogress=function(){var e=t.responseText,o=e.slice(s);s+=o.length,n(o)},t.onerror=function(t){t.preventDefault(),o(new Error("NetworkError"))},t.onload=function(){o(null)},t.onabort=function(){o(null)},t.onreadystatechange=function(){if(t.readyState===c.HEADERS_RECEIVED){var n=t.status,o=t.statusText,r=t.getResponseHeader("Content-Type"),i=t.getAllResponseHeaders();e(n,o,r,new C(i))}},t.withCredentials=i,a)Object.prototype.hasOwnProperty.call(a,d)&&t.setRequestHeader(d,a[d]);return t.send(),t},O.prototype.get=function(t){return this._headers.get(t)},R.prototype.open=function(t,e,n,o,r,i,a){var s=null,d=new m,c=d.signal,u=new g;return v(r,{headers:a,credentials:i?"include":"same-origin",signal:c,cache:"no-store"}).then((function(t){return s=t.body.getReader(),e(t.status,t.statusText,t.headers.get("Content-Type"),new O(t.headers)),new p((function(t,e){(function o(){s.read().then((function(e){if(e.done)t(void 0);else{var r=u.decode(e.value,{stream:!0});n(r),o()}}))["catch"]((function(t){e(t)}))})()}))}))["catch"]((function(t){return"AbortError"===t.name?void 0:t})).then((function(t){o(t)})),{abort:function(){null!=s&&s.cancel(),d.abort()}}},H.prototype.dispatchEvent=function(t){t.target=this;var e=this._listeners[t.type];if(void 0!=e)for(var n=e.length,o=0;o<n;o+=1){var r=e[o];try{"function"===typeof r.handleEvent?r.handleEvent(t):r.call(this,t)}catch(i){D(i)}}},H.prototype.addEventListener=function(t,e){t=String(t);var n=this._listeners,o=n[t];void 0==o&&(o=[],n[t]=o);for(var r=!1,i=0;i<o.length;i+=1)o[i]===e&&(r=!0);r||o.push(e)},H.prototype.removeEventListener=function(t,e){t=String(t);var n=this._listeners,o=n[t];if(void 0!=o){for(var r=[],i=0;i<o.length;i+=1)o[i]!==e&&r.push(o[i]);0===r.length?delete n[t]:n[t]=r}},j.prototype=Object.create(N.prototype),A.prototype=Object.create(N.prototype),k.prototype=Object.create(N.prototype);var M=/^text\/event\-stream(;.*)?$/i,B=function(t){return Math.min(Math.max(t,1e3),18e6)},P=function(t,e,n){try{"function"===typeof e&&e.call(t,n)}catch(o){D(o)}};function I(t,e){H.call(this),e=e||{},this.onopen=void 0,this.onmessage=void 0,this.onerror=void 0,this.url=void 0,this.readyState=void 0,this.withCredentials=void 0,this.headers=void 0,this._close=void 0,function(t,e,n){e=String(e);var o=Boolean(n.withCredentials),r=(n.lastEventIdQueryParameterName,B(1e3)),i=function(t,e){var n=null==t?e:parseInt(t,10);return n!==n&&(n=e),B(n)}(n.heartbeatTimeout,45e10),a=r,l=!1,f=0,h=n.headers||{},p=n.Transport,v=X&&void 0==p?void 0:new T(void 0!=p?new p:function(){return void 0!=c&&"withCredentials"in c.prototype||void 0==u?new c:new u}()),y=null!=p&&"string"!==typeof p?new p:void 0==v?new R:new S,g=void 0,b=0,m=-1,w="",x=function(e,n,o,i){if(0===m)if(200===e&&void 0!=o&&M.test(o)){m=1,l=Date.now(),a=r,t.readyState=1;var s=new A("open",{status:e,statusText:n,headers:i});t.dispatchEvent(s),P(t,t.onopen,s)}else{var d="";200!==e?(n&&(n=n.replace(/\s+/g," ")),d="EventSource's response has a status "+e+" "+n+" that is not 200. Aborting the connection."):d="EventSource's response has a Content-Type specifying an unsupported type: "+(void 0==o?"-":o.replace(/\s+/g," "))+". Aborting the connection.",C();s=new A("error",{status:e,statusText:n,headers:i});t.dispatchEvent(s),P(t,t.onerror,s),console.error(d)}},E=function(e){var n=new j(w,{data:e});t.dispatchEvent(n),P(t,t.onmessage,n)},_=function(e){if(1===m||0===m){m=-1,0!==b&&(d(b),b=0),b=s((function(){O()}),a),a=B(Math.min(16*r,2*a)),t.readyState=0;var n=new k("error",{error:e});t.dispatchEvent(n),P(t,t.onerror,n),void 0!=e&&console.error(e)}},C=function(){m=2,void 0!=g&&(g.abort(),g=void 0),0!==b&&(d(b),b=0),t.readyState=2},O=function n(){if(b=0,-1===m){l=!1,f=0,b=s((function(){n()}),i),m=0,"",w="","","",0,0,0;var o=e;if("data:"!==e.slice(0,5)&&"blob:"!==e.slice(0,5));var r=t.withCredentials,a={Accept:"text/event-stream"},d=t.headers;if(void 0!=d)for(var c in d)Object.prototype.hasOwnProperty.call(d,c)&&(a[c]=d[c]);try{g=y.open(v,x,E,_,o,r,a)}catch(h){throw C(),h}}else if(l||void 0==g){var u=Math.max((l||Date.now())+i-Date.now(),1);l=!1,b=s((function(){n()}),u)}else _(new Error("No activity within "+i+" milliseconds. "+(0===m?"No response received.":f+" chars received.")+" Reconnecting.")),void 0!=g&&(g.abort(),g=void 0)};t.url=e,t.readyState=0,t.withCredentials=o,t.headers=h,t._close=C,O()}(this,t,e)}var X=void 0!=v&&void 0!=y&&"body"in y.prototype;I.prototype=Object.create(H.prototype),I.prototype.CONNECTING=0,I.prototype.OPEN=1,I.prototype.CLOSED=2,I.prototype.close=function(){this._close()},I.CONNECTING=0,I.OPEN=1,I.CLOSED=2,I.prototype.withCredentials=void 0;var z=f;void 0==c||void 0!=f&&"withCredentials"in f.prototype||(z=I),function(n){if("object"===a(t)&&"object"===a(t.exports)){var s=n(e);void 0!==s&&(t.exports=s)}else r=[e],o=n,i="function"===typeof o?o.apply(e,r):o,void 0===i||(t.exports=i)}((function(t){t.EventSourcePolyfill=I,t.NativeEventSource=f,t.EventSource=z}))}("undefined"===typeof globalThis?"undefined"!==typeof window?window:"undefined"!==typeof self?self:this:globalThis)}).call(this,n("62e4")(t))},"3f3a":function(t,e,n){"use strict";n.r(e);var o=n("b778"),r=n("e262");for(var i in r)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return r[t]}))}(i);n("d5b6");var a=n("f0c5"),s=Object(a["a"])(r["default"],o["b"],o["c"],!1,null,"558a8926",null,!1,o["a"],void 0);e["default"]=s.exports},"5de9":function(t,e,n){var o=n("7312");o.__esModule&&(o=o.default),"string"===typeof o&&(o=[[t.i,o,""]]),o.locals&&(t.exports=o.locals);var r=n("4f06").default;r("0941e101",o,!0,{sourceMap:!1,shadowMode:!1})},"6c57":function(t,e,n){var o=n("23e7"),r=n("da84");o({global:!0,forced:r.globalThis!==r},{globalThis:r})},7312:function(t,e,n){var o=n("24fb");e=o(!1),e.push([t.i,".flex[data-v-558a8926]{display:flex}.align-center[data-v-558a8926]{align-items:center}.justify-center[data-v-558a8926]{justify-content:center}.rounded[data-v-558a8926]{border-radius:%?8?%}.border[data-v-558a8926]{border-width:%?1?%;border-style:solid;border-color:#dee2e6}.top-0[data-v-558a8926]{top:0}.left-0[data-v-558a8926]{left:0}.right-0[data-v-558a8926]{right:0}.bottom-0[data-v-558a8926]{bottom:0}.bg-opacity[data-v-558a8926]{background-color:transparent}.bg-white[data-v-558a8926]{background-color:#fff}.position-fixed[data-v-558a8926]{position:fixed}.free-animated[data-v-558a8926]{\n}.bottom-animated[data-v-558a8926]{-webkit-animation:slide-bottom .1s ease 1 0s;animation:slide-bottom .1s ease 1 0s}.z-index[data-v-558a8926]{\nz-index:9999\n}.z-index2[data-v-558a8926]{z-index:10000}",""]),t.exports=e},b778:function(t,e,n){"use strict";n.d(e,"b",(function(){return o})),n.d(e,"c",(function(){return r})),n.d(e,"a",(function(){}));var o=function(){var t=this,e=t.$createElement,n=t._self._c||e;return t.status?n("div",{staticStyle:{"z-index":"9999",overflow:"hidden"}},[t.mask?n("v-uni-view",{staticClass:"position-fixed top-0 left-0 right-0 bottom-0 z-index",style:t.getMaskColor,on:{touchstart:function(e){arguments[0]=e=t.$handleEvent(e),t.hide.apply(void 0,arguments)}}}):t._e(),n("div",{ref:"popup",staticClass:"position-fixed free-animated z-index bottom-animated ",class:t.getBodyClass,style:t.getBodyStyle},[t._t("default")],2)],1):t._e()},r=[]},d5b6:function(t,e,n){"use strict";var o=n("5de9"),r=n.n(o);r.a},e262:function(t,e,n){"use strict";n.r(e);var o=n("2680"),r=n.n(o);for(var i in o)["default"].indexOf(i)<0&&function(t){n.d(e,t,(function(){return o[t]}))}(i);e["default"]=r.a}}]);
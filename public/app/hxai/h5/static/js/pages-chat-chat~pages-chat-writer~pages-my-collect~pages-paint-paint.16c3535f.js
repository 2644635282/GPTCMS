(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-chat-chat~pages-chat-writer~pages-my-collect~pages-paint-paint"],{"6c57":function(e,t,n){var o=n("23e7"),r=n("da84");o({global:!0,forced:r.globalThis!==r},{globalThis:r})},9133:function(e,t,n){(function(e){var o,r,i,a=n("7037").default;n("d3b7"),n("d9e2"),n("d401"),n("c975"),n("ac1f"),n("5319"),n("fb6a"),n("14d9"),n("e25e"),n("00b4"),n("6c57"),
/** @license
 * eventsource.js
 * Available under MIT License (MIT)
 * https://github.com/Yaffle/EventSource/
 */
function(n){"use strict";var s=n.setTimeout,c=n.clearTimeout,d=n.XMLHttpRequest,u=n.XDomainRequest,h=n.ActiveXObject,l=n.EventSource,p=n.document,f=n.Promise,v=n.fetch,y=n.Response,g=n.TextDecoder,w=n.TextEncoder,b=n.AbortController;if("undefined"===typeof window||"undefined"===typeof p||"readyState"in p||null!=p.body||(p.readyState="loading",window.addEventListener("load",(function(e){p.readyState="complete"}),!1)),null==d&&null!=h&&(d=function(){return new h("Microsoft.XMLHTTP")}),void 0==Object.create&&(Object.create=function(e){function t(){}return t.prototype=e,new t}),Date.now||(Date.now=function(){return(new Date).getTime()}),void 0==b){var T=v;v=function(e,t){var n=t.signal;return T(e,{headers:t.headers,credentials:t.credentials,cache:t.cache}).then((function(e){var t=e.body.getReader();return n._reader=t,n._aborted&&n._reader.cancel(),{status:e.status,statusText:e.statusText,headers:e.headers,body:{getReader:function(){return t}}}}))},b=function(){this.signal={_reader:null,_aborted:!1},this.abort=function(){null!=this.signal._reader&&this.signal._reader.cancel(),this.signal._aborted=!0}}}function E(){this.bitsNeeded=0,this.codePoint=0}E.prototype.decode=function(e){function t(e,t,n){if(1===n)return e>=128>>t&&e<<t<=2047;if(2===n)return e>=2048>>t&&e<<t<=55295||e>=57344>>t&&e<<t<=65535;if(3===n)return e>=65536>>t&&e<<t<=1114111;throw new Error}function n(e,t){if(6===e)return t>>6>15?3:t>31?2:1;if(12===e)return t>15?3:2;if(18===e)return 3;throw new Error}for(var o="",r=this.bitsNeeded,i=this.codePoint,a=0;a<e.length;a+=1){var s=e[a];0!==r&&(s<128||s>191||!t(i<<6|63&s,r-6,n(r,i)))&&(r=0,i=65533,o+=String.fromCharCode(i)),0===r?(s>=0&&s<=127?(r=0,i=s):s>=192&&s<=223?(r=6,i=31&s):s>=224&&s<=239?(r=12,i=15&s):s>=240&&s<=247?(r=18,i=7&s):(r=0,i=65533),0===r||t(i,r,n(r,i))||(r=0,i=65533)):(r-=6,i=i<<6|63&s),0===r&&(i<=65535?o+=String.fromCharCode(i):(o+=String.fromCharCode(55296+(i-65535-1>>10)),o+=String.fromCharCode(56320+(i-65535-1&1023))))}return this.bitsNeeded=r,this.codePoint=i,o};void 0!=g&&void 0!=w&&function(){try{return"test"===(new g).decode((new w).encode("test"),{stream:!0})}catch(e){console.debug("TextDecoder does not support streaming option. Using polyfill instead: "+e)}return!1}()||(g=E);var m=function(){};function _(e){this.withCredentials=!1,this.readyState=0,this.status=0,this.statusText="",this.responseText="",this.onprogress=m,this.onload=m,this.onerror=m,this.onreadystatechange=m,this._contentType="",this._xhr=e,this._sendTimeout=0,this._abort=m}function S(e){return e.replace(/[A-Z]/g,(function(e){return String.fromCharCode(e.charCodeAt(0)+32)}))}function C(e){for(var t=Object.create(null),n=e.split("\r\n"),o=0;o<n.length;o+=1){var r=n[o],i=r.split(": "),a=i.shift(),s=i.join(": ");t[S(a)]=s}this._map=t}function x(){}function R(e){this._headers=e}function O(){}function D(){this._listeners=Object.create(null)}function A(e){s((function(){throw e}),0)}function H(e){this.type=e,this.target=void 0}function N(e,t){H.call(this,e),this.data=t.data,this.lastEventId=t.lastEventId}function j(e,t){H.call(this,e),this.status=t.status,this.statusText=t.statusText,this.headers=t.headers}function I(e,t){H.call(this,e),this.error=t.error}_.prototype.open=function(e,t){this._abort(!0);var n=this,o=this._xhr,r=1,i=0;this._abort=function(e){0!==n._sendTimeout&&(c(n._sendTimeout),n._sendTimeout=0),1!==r&&2!==r&&3!==r||(r=4,o.onload=m,o.onerror=m,o.onabort=m,o.onprogress=m,o.onreadystatechange=m,o.abort(),0!==i&&(c(i),i=0),e||(n.readyState=4,n.onabort(null),n.onreadystatechange())),r=0};var a=function(){if(1===r){var e=0,t="",i=void 0;if("contentType"in o)e=200,t="OK",i=o.contentType;else try{e=o.status,t=o.statusText,i=o.getResponseHeader("Content-Type")}catch(a){e=0,t="",i=void 0}0!==e&&(r=2,n.readyState=2,n.status=e,n.statusText=t,n._contentType=i,n.onreadystatechange())}},u=function(){if(a(),2===r||3===r){r=3;var e="";try{e=o.responseText}catch(t){}n.readyState=3,n.responseText=e,n.onprogress()}},h=function(e,t){if(null!=t&&null!=t.preventDefault||(t={preventDefault:m}),u(),1===r||2===r||3===r){if(r=4,0!==i&&(c(i),i=0),n.readyState=4,"load"===e)n.onload(t);else if("error"===e)n.onerror(t);else{if("abort"!==e)throw new TypeError;n.onabort(t)}n.onreadystatechange()}};"onload"in o&&(o.onload=function(e){h("load",e)}),"onerror"in o&&(o.onerror=function(e){h("error",e)}),"onabort"in o&&(o.onabort=function(e){h("abort",e)}),"onprogress"in o&&(o.onprogress=u),"onreadystatechange"in o&&(o.onreadystatechange=function(e){(function(e){void 0!=o&&(4===o.readyState?"onload"in o&&"onerror"in o&&"onabort"in o||h(""===o.responseText?"error":"load",e):3===o.readyState?"onprogress"in o||u():2===o.readyState&&a())})(e)}),!("contentType"in o)&&"ontimeout"in d.prototype||(t+=(-1===t.indexOf("?")?"?":"&")+"padding=true"),o.open(e,t,!0),"readyState"in o&&(i=s((function(){(function e(){i=s((function(){e()}),500),3===o.readyState&&u()})()}),0))},_.prototype.abort=function(){this._abort(!1)},_.prototype.getResponseHeader=function(e){return this._contentType},_.prototype.setRequestHeader=function(e,t){var n=this._xhr;"setRequestHeader"in n&&n.setRequestHeader(e,t)},_.prototype.getAllResponseHeaders=function(){return void 0!=this._xhr.getAllResponseHeaders&&this._xhr.getAllResponseHeaders()||""},_.prototype.send=function(){if("ontimeout"in d.prototype&&("sendAsBinary"in d.prototype||"mozAnon"in d.prototype)||void 0==p||void 0==p.readyState||"complete"===p.readyState){var e=this._xhr;"withCredentials"in e&&(e.withCredentials=this.withCredentials);try{e.send(void 0)}catch(n){throw n}}else{var t=this;t._sendTimeout=s((function(){t._sendTimeout=0,t.send()}),4)}},C.prototype.get=function(e){return this._map[S(e)]},null!=d&&null==d.HEADERS_RECEIVED&&(d.HEADERS_RECEIVED=2),x.prototype.open=function(e,t,n,o,r,i,a){e.open("GET",r);var s=0;for(var c in e.onprogress=function(){var t=e.responseText,o=t.slice(s);s+=o.length,n(o)},e.onerror=function(e){e.preventDefault(),o(new Error("NetworkError"))},e.onload=function(){o(null)},e.onabort=function(){o(null)},e.onreadystatechange=function(){if(e.readyState===d.HEADERS_RECEIVED){var n=e.status,o=e.statusText,r=e.getResponseHeader("Content-Type"),i=e.getAllResponseHeaders();t(n,o,r,new C(i))}},e.withCredentials=i,a)Object.prototype.hasOwnProperty.call(a,c)&&e.setRequestHeader(c,a[c]);return e.send(),e},R.prototype.get=function(e){return this._headers.get(e)},O.prototype.open=function(e,t,n,o,r,i,a){var s=null,c=new b,d=c.signal,u=new g;return v(r,{headers:a,credentials:i?"include":"same-origin",signal:d,cache:"no-store"}).then((function(e){return s=e.body.getReader(),t(e.status,e.statusText,e.headers.get("Content-Type"),new R(e.headers)),new f((function(e,t){(function o(){s.read().then((function(t){if(t.done)e(void 0);else{var r=u.decode(t.value,{stream:!0});n(r),o()}}))["catch"]((function(e){t(e)}))})()}))}))["catch"]((function(e){return"AbortError"===e.name?void 0:e})).then((function(e){o(e)})),{abort:function(){null!=s&&s.cancel(),c.abort()}}},D.prototype.dispatchEvent=function(e){e.target=this;var t=this._listeners[e.type];if(void 0!=t)for(var n=t.length,o=0;o<n;o+=1){var r=t[o];try{"function"===typeof r.handleEvent?r.handleEvent(e):r.call(this,e)}catch(i){A(i)}}},D.prototype.addEventListener=function(e,t){e=String(e);var n=this._listeners,o=n[e];void 0==o&&(o=[],n[e]=o);for(var r=!1,i=0;i<o.length;i+=1)o[i]===t&&(r=!0);r||o.push(t)},D.prototype.removeEventListener=function(e,t){e=String(e);var n=this._listeners,o=n[e];if(void 0!=o){for(var r=[],i=0;i<o.length;i+=1)o[i]!==t&&r.push(o[i]);0===r.length?delete n[e]:n[e]=r}},N.prototype=Object.create(H.prototype),j.prototype=Object.create(H.prototype),I.prototype=Object.create(H.prototype);var P=/^text\/event\-stream(;.*)?$/i,L=function(e){return Math.min(Math.max(e,1e3),18e6)},M=function(e,t,n){try{"function"===typeof t&&t.call(e,n)}catch(o){A(o)}};function q(e,t){D.call(this),t=t||{},this.onopen=void 0,this.onmessage=void 0,this.onerror=void 0,this.url=void 0,this.readyState=void 0,this.withCredentials=void 0,this.headers=void 0,this._close=void 0,function(e,t,n){t=String(t);var o=Boolean(n.withCredentials),r=(n.lastEventIdQueryParameterName,L(1e3)),i=function(e,t){var n=null==e?t:parseInt(e,10);return n!==n&&(n=t),L(n)}(n.heartbeatTimeout,45e10),a=r,h=!1,l=0,p=n.headers||{},f=n.Transport,v=X&&void 0==f?void 0:new _(void 0!=f?new f:function(){return void 0!=d&&"withCredentials"in d.prototype||void 0==u?new d:new u}()),y=null!=f&&"string"!==typeof f?new f:void 0==v?new O:new x,g=void 0,w=0,b=-1,T="",E=function(t,n,o,i){if(0===b)if(200===t&&void 0!=o&&P.test(o)){b=1,h=Date.now(),a=r,e.readyState=1;var s=new j("open",{status:t,statusText:n,headers:i});e.dispatchEvent(s),M(e,e.onopen,s)}else{var c="";200!==t?(n&&(n=n.replace(/\s+/g," ")),c="EventSource's response has a status "+t+" "+n+" that is not 200. Aborting the connection."):c="EventSource's response has a Content-Type specifying an unsupported type: "+(void 0==o?"-":o.replace(/\s+/g," "))+". Aborting the connection.",C();s=new j("error",{status:t,statusText:n,headers:i});e.dispatchEvent(s),M(e,e.onerror,s),console.error(c)}},m=function(t){var n=new N(T,{data:t});e.dispatchEvent(n),M(e,e.onmessage,n)},S=function(t){if(1===b||0===b){b=-1,0!==w&&(c(w),w=0),w=s((function(){R()}),a),a=L(Math.min(16*r,2*a)),e.readyState=0;var n=new I("error",{error:t});e.dispatchEvent(n),M(e,e.onerror,n),void 0!=t&&console.error(t)}},C=function(){b=2,void 0!=g&&(g.abort(),g=void 0),0!==w&&(c(w),w=0),e.readyState=2},R=function n(){if(w=0,-1===b){h=!1,l=0,w=s((function(){n()}),i),b=0,"",T="","","",0,0,0;var o=t;if("data:"!==t.slice(0,5)&&"blob:"!==t.slice(0,5));var r=e.withCredentials,a={Accept:"text/event-stream"},c=e.headers;if(void 0!=c)for(var d in c)Object.prototype.hasOwnProperty.call(c,d)&&(a[d]=c[d]);try{g=y.open(v,E,m,S,o,r,a)}catch(p){throw C(),p}}else if(h||void 0==g){var u=Math.max((h||Date.now())+i-Date.now(),1);h=!1,w=s((function(){n()}),u)}else S(new Error("No activity within "+i+" milliseconds. "+(0===b?"No response received.":l+" chars received.")+" Reconnecting.")),void 0!=g&&(g.abort(),g=void 0)};e.url=t,e.readyState=0,e.withCredentials=o,e.headers=p,e._close=C,R()}(this,e,t)}var X=void 0!=v&&void 0!=y&&"body"in y.prototype;q.prototype=Object.create(D.prototype),q.prototype.CONNECTING=0,q.prototype.OPEN=1,q.prototype.CLOSED=2,q.prototype.close=function(){this._close()},q.CONNECTING=0,q.OPEN=1,q.CLOSED=2,q.prototype.withCredentials=void 0;var k=l;void 0==d||void 0!=l&&"withCredentials"in l.prototype||(k=q),function(n){if("object"===a(e)&&"object"===a(e.exports)){var s=n(t);void 0!==s&&(e.exports=s)}else r=[t],o=n,i="function"===typeof o?o.apply(t,r):o,void 0===i||(e.exports=i)}((function(e){e.EventSourcePolyfill=q,e.NativeEventSource=l,e.EventSource=k}))}("undefined"===typeof globalThis?"undefined"!==typeof window?window:"undefined"!==typeof self?self:this:globalThis)}).call(this,n("62e4")(e))},baa5:function(e,t,n){var o=n("23e7"),r=n("e58c");o({target:"Array",proto:!0,forced:r!==[].lastIndexOf},{lastIndexOf:r})}}]);
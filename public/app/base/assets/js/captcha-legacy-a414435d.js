System.register(["./index-legacy-6de2be09.js"],(function(t,e){"use strict";var o,l,a,r,n,s,h;return{setters:[t=>{o=t.r,l=t.V,a=t.aa,r=t.o,n=t.a7,s=t.k,h=t.l}],execute:function(){t("v",(function(t){return/^1[3-9]{1}\d{9}$/.test(t)})),t("_",{__name:"captcha",emits:["getCaptcha"],setup(t,{emit:e}){let c=[],f="",i=["red","yellow","blue","green","pink","black"];const u=o(null),g=l((()=>{let t="";for(let e=0;e<c.length;e++)t+=c[e];return console.log(t),t.toUpperCase()}));a(g,((t,o)=>{console.log("watch 已触发",t),e("getCaptcha",t)}));const d=()=>{c=[];const t="abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789".split("");for(let e=0;e<4;e++){const e=Math.floor(Math.random()*t.length);c.push(t[e])}let o="";for(let e=0;e<c.length;e++)o+=c[e];console.log(o),e("getCaptcha",o.toUpperCase());const l=u.value;f=l.getContext("2d"),f.fillStyle="#BFEFFF",f.fillRect(0,0,84,40),f.font="20px Verdana";let a=15;for(let e=0;e<4;e++)f.fillStyle=i[Math.floor(5*Math.random())],f.fillText(c[e],a,25),a+=15;for(let e=0;e<3;e++)f.strokeStyle=i[Math.floor(5*Math.random())],f.beginPath(),f.moveTo(Math.floor(84*Math.random()),Math.floor(30*Math.random())),f.lineTo(Math.floor(84*Math.random()),Math.floor(30*Math.random())),f.stroke()};return r((()=>d())),n((()=>{})),(t,e)=>(s(),h("canvas",{ref_key:"canvasRef",ref:u,onClick:d,width:"84",height:"40",style:{cursor:"pointer"}},null,512))}})}}}));
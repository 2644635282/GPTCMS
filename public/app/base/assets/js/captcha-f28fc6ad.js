import{r as k,X as v,ac as x,o as C,a9 as y,k as b,l as w}from"./index-8160da6b.js";function S(s){return/^1[3-9]{1}\d{9}$/.test(s)}const T={__name:"captcha",emits:["getCaptcha"],setup(s,{emit:n}){let r=[],e="",h=["red","yellow","blue","green","pink","black"],i=4;const f=k(null),p=v(()=>{let o="";for(let a=0;a<r.length;a++)o+=r[a];return console.log(o),o.toUpperCase()});x(p,(o,a)=>{console.log("watch \u5DF2\u89E6\u53D1",o),n("getCaptcha",o)});const g=()=>{},u=()=>{r=[];const a="abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789".split("");for(let t=0;t<i;t++){const _=Math.floor(Math.random()*a.length);r.push(a[_])}let l="";for(let t=0;t<r.length;t++)l+=r[t];console.log(l),n("getCaptcha",l.toUpperCase()),e=f.value.getContext("2d"),e.fillStyle="#BFEFFF",e.fillRect(0,0,84,40),e.font="20px Verdana";let c=15;for(let t=0;t<i;t++)e.fillStyle=h[Math.floor(Math.random()*5)],e.fillText(r[t],c,25),c=c+15;const M=3,d=84,m=30;for(let t=0;t<M;t++)e.strokeStyle=h[Math.floor(Math.random()*5)],e.beginPath(),e.moveTo(Math.floor(Math.random()*d),Math.floor(Math.random()*m)),e.lineTo(Math.floor(Math.random()*d),Math.floor(Math.random()*m)),e.stroke()};return C(()=>u()),y(()=>g()),(o,a)=>(b(),w("canvas",{ref_key:"canvasRef",ref:f,onClick:u,width:"84",height:"40",style:{cursor:"pointer"}},null,512))}};export{T as _,S as v};

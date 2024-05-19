import{r as f,o as M,_ as O,a as g,V as D,a7 as G,b as J,$ as L,E as P,d as Q,f as z,e as A,j as H,m as o,n,q as h,k as r,v,R as k,x as C,u as q,t as K,y as W,Q as X,B as m,a8 as Y,a9 as Z,a4 as ee}from"./index-2be3f537.js";import{_ as te}from"./auth-container-97a65656.js";import{_ as oe,v as ae}from"./captcha-45ae7d8b.js";import"./_plugin-vue_export-helper-09d269c4.js";const le={class:"flex-col login-p"},se={style:{"margin-bottom":"40px"},class:"flex-center"},ne=r("h3",{class:"flex-1 login-p__title"},"\u6CE8\u518C",-1),re={class:"app-flex-left"},ue={class:"app-flex-left"},ce={class:"flex-1 flex-col justify-end login-p__btns"},fe={__name:"register",setup(pe){const U=f(!0);M(()=>{document.title=`${O.title}-\u8D26\u53F7\u6CE8\u518C`});const y=W(),w=X(),B=f(JSON.parse(localStorage.register_check).data),V=f(null),e=g({account:"",pass:"",phone:"",captcha:"",code:""}),E=g({account:[{required:!0,message:"\u8BF7\u8F93\u5165\u7528\u6237\u540D",trigger:"blur"}],pass:[{required:!0,message:"\u8BF7\u8F93\u5165\u5BC6\u7801",trigger:"blur"}],phone:[{required:!0,message:"\u8BF7\u8F93\u5165\u624B\u673A\u53F7",trigger:"blur"}],captcha:[{required:!0,message:"\u8BF7\u8F93\u5165\u56FE\u7247\u9A8C\u8BC1\u7801",trigger:"blur"}]});let p="";const $=s=>{p=s.toUpperCase()};let i;const b=60,l=g({value:b,posting:!1,counting:!1}),N=D(()=>l.counting?`${l.value} s\u53EF\u91CD\u53D1`:"\u53D1\u9001\u9A8C\u8BC1\u7801"),_=()=>{!i||(clearInterval(i),i=null)},R=()=>{_(),l.value=b,l.counting=!0,i=setInterval(()=>{const s=l.value-1;s>0?l.value=s:(_(),l.counting=!1)},1e3)};G(()=>_());const S=async()=>{l.posting||l.counting||(e.captcha.toUpperCase()!=p?m({message:"\u8BF7\u5148\u8F93\u5165\u6B63\u786E\u7684\u56FE\u7247\u9A8C\u8BC1\u7801",type:"error"}):ae(e.phone)?await Y(e.phone)&&(l.posting=!0,R()):m({message:"\u8BF7\u5148\u8F93\u5165\u6B63\u786E\u7684\u624B\u673A\u53F7\u7801",type:"error"}))},I=async s=>{if(console.log(p,e.captcha),e.captcha.toUpperCase()!=p&&m({message:"\u8BF7\u5148\u8F93\u5165\u6B63\u786E\u7684\u56FE\u7247\u9A8C\u8BC1\u7801",type:"error"}),!(!s||!await s.validate())){m({message:"\u63D0\u4EA4\u4E2D...",type:"success"});try{const{data:d}=await Z(e);console.log(d,"kjlk;l;"),y.push({path:"/login",replace:!0})}catch(d){}}},T=s=>{y.push({name:s,query:w.query})};return(s,t)=>{const d=J,j=L,u=P,c=Q,x=z,F=A;return h(),H("div",null,[o(te,null,{default:n(()=>[r("div",le,[r("div",se,[ne,o(j,{underline:!1,onClick:t[0]||(t[0]=a=>T("login"))},{default:n(()=>[v(" \u767B\u5F55"),o(d,{class:"el-icon--right"},{default:n(()=>[o(k(ee))]),_:1})]),_:1})]),o(F,{ref_key:"formRef",ref:V,model:e,"status-icon":"",rules:E,"label-width":"84px","hide-required-asterisk":""},{default:n(()=>[o(c,{label:"\u7528\u6237\u540D",prop:"account"},{default:n(()=>[o(u,{modelValue:e.account,"onUpdate:modelValue":t[1]||(t[1]=a=>e.account=a),type:"text",placeholder:"\u8BF7\u8F93\u5165\u7528\u6237\u540D"},null,8,["modelValue"])]),_:1}),o(c,{label:"\u624B\u673A\u53F7\u7801",prop:"phone"},{default:n(()=>[o(u,{modelValue:e.phone,"onUpdate:modelValue":t[2]||(t[2]=a=>e.phone=a),type:"text",placeholder:"\u4EE5\u540E\u53EF\u4EE5\u7528\u624B\u673A\u53F7\u767B\u5F55"},null,8,["modelValue"])]),_:1}),o(c,{label:"\u56FE\u7247\u9A8C\u8BC1\u7801",prop:"captcha"},{default:n(()=>[r("div",re,[o(u,{modelValue:e.captcha,"onUpdate:modelValue":t[3]||(t[3]=a=>e.captcha=a),type:"text",placeholder:"\u8BF7\u8F93\u5165\u56FE\u7247\u9A8C\u8BC1\u7801"},null,8,["modelValue"]),U.value?(h(),C(oe,{key:0,onGetCaptcha:t[4]||(t[4]=a=>$(a))})):q("",!0)])]),_:1}),B.value==1?(h(),C(c,{key:0,label:"\u77ED\u4FE1\u9A8C\u8BC1\u7801",prop:"code"},{default:n(()=>[r("div",ue,[o(u,{modelValue:e.code,"onUpdate:modelValue":t[5]||(t[5]=a=>e.code=a),placeholder:"\u8BF7\u586B\u5199\u77ED\u4FE1\u9A8C\u8BC1\u7801"},null,8,["modelValue"]),o(x,{type:"primary",disabled:l.posting,onClick:S},{default:n(()=>[v(K(k(N)),1)]),_:1},8,["disabled"])])]),_:1})):q("",!0),o(c,{label:"\u8BBE\u7F6E\u5BC6\u7801",prop:"pass"},{default:n(()=>[o(u,{modelValue:e.pass,"onUpdate:modelValue":t[6]||(t[6]=a=>e.pass=a),"show-password":"",type:"password",placeholder:"8-16\u4F4D\u5FC5\u987B\u5305\u542B\u6570\u5B57\u548C\u5B57\u6BCD"},null,8,["modelValue"])]),_:1})]),_:1},8,["model","rules"]),r("div",ce,[o(x,{type:"primary",onClick:t[7]||(t[7]=a=>I(V.value))},{default:n(()=>[v("\u6CE8\u518C")]),_:1})])])]),_:1})])}}};export{fe as default};

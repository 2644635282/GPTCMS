import{_ as E,a as x,r as B,b as N,E as S,c as _,l as q,o as l,d as i,e as r,f as t,w as a,m as I,n as O,g as R,t as o,i as c}from"./index-b7084a6d.js";const w={class:"distribution-content"},A={class:"top-search"},L={key:0,style:{"margin-left":"20px"}},M={key:1,style:{"margin-left":"20px"}},P={style:{"margin-left":"10px"}},T={style:{"margin-left":"10px"}},U={key:0,style:{"margin-left":"20px"}},j={key:1,style:{"margin-left":"20px"}},F={key:2,style:{"margin-left":"20px",color:"#EA0000"}},G={key:0,style:{"margin-left":"20px"}},H={key:1,style:{"margin-left":"20px"}},J={key:2,style:{"margin-left":"20px",color:"#EA0000"}},K={class:"list-pagination"},Q={__name:"OrderRecords",setup(W){const b=x([]),n=B({page:1,pageSize:10,total:0,pageCount:0,search:""}),m=x(!1);f();async function f(){m.value=!0;const{data:s={}}=await N.post("/hxai/api/distribution/bg/order/list",{page:n.page,size:n.pageSize,search:n.search});if(s!=null&&s.data){const{list:d=[],page:u={}}=s.data;n.total=u.total_count,n.pageCount=u.total_page,b.value=d}else S.error(s==null?void 0:s.msg);m.value=!1}function v(){n.page=1,f()}function k(s){n.page=s,f()}return(s,d)=>{const u=_("el-input"),C=_("el-button"),p=_("el-table-column"),g=_("el-avatar"),h=_("el-col"),y=_("el-row"),V=_("el-table"),z=_("el-pagination"),D=q("loading");return l(),i("div",w,[r("div",A,[t(u,{class:"input-search",placeholder:"手机号/用户名/用户ID",modelValue:n.search,"onUpdate:modelValue":d[0]||(d[0]=e=>n.search=e)},null,8,["modelValue"]),t(C,{class:"btn-search",type:"primary",onClick:v},{default:a(()=>[R("查询")]),_:1})]),I((l(),O(V,{data:b.value,class:"table-list","row-class-name":"table-list-row","cell-class-name":"table-cell","header-cell-class-name":"table-header-cell"},{default:a(()=>[t(p,{prop:"order_id",label:"订单编号","min-width":"50"}),t(p,{label:"用户","min-width":"120"},{default:a(({row:e})=>[t(y,{type:"flex"},{default:a(()=>[t(h,{span:24},{default:a(()=>[t(g,{shape:"square",size:"small",src:e.headimgurl},null,8,["src"]),e.nickname?(l(),i("span",L,o(e.nickname),1)):c("",!0),e.telephone?(l(),i("span",M,o(e.telephone),1)):c("",!0)]),_:2},1024)]),_:2},1024)]),_:1}),t(p,{prop:"address",label:"订单信息","min-width":"200"},{default:a(({row:e})=>[r("span",null,o(e.order_info.goods_name),1),r("span",P,"￥"+o(e.order_info.money),1),r("span",T,o(e.order_info.create_time),1)]),_:1}),t(p,{label:"一级收益","min-width":"100"},{default:a(({row:e})=>[t(y,{type:"flex"},{default:a(()=>[t(h,{span:24},{default:a(()=>[t(g,{shape:"square",size:"small",src:e.one_p_info.headimgurl},null,8,["src"]),e.one_p_info.nickname?(l(),i("span",U,o(e.one_p_info.nickname),1)):c("",!0),e.one_p_info.telephone?(l(),i("span",j,o(e.one_p_info.telephone),1)):c("",!0),e.one_p_info.money?(l(),i("span",F,"￥"+o(e.one_p_info.money),1)):c("",!0)]),_:2},1024)]),_:2},1024)]),_:1}),t(p,{prop:"address",label:"二级收益","min-width":"100"},{default:a(({row:e})=>[t(y,{type:"flex"},{default:a(()=>[t(h,{span:24},{default:a(()=>[t(g,{shape:"square",size:"small",src:e.two_p_info.headimgurl},null,8,["src"]),e.two_p_info.nickname?(l(),i("span",G,o(e.two_p_info.nickname),1)):c("",!0),e.two_p_info.telephone?(l(),i("span",H,o(e.two_p_info.telephone),1)):c("",!0),e.two_p_info.money?(l(),i("span",J,"￥"+o(e.two_p_info.money),1)):c("",!0)]),_:2},1024)]),_:2},1024)]),_:1})]),_:1},8,["data"])),[[D,m.value]]),r("div",K,[t(z,{background:"",layout:"prev, pager, next",total:n.total,"page-count":n.pageCount,onCurrentChange:k},null,8,["total","page-count"])])])}}},Y=E(Q,[["__scopeId","data-v-9cddbeb1"]]);export{Y as default};
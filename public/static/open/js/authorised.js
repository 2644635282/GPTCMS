let okFn = null;
let PreviewList = [];
let checkList = [
  // {
  //   name: "服务类目",
  //   children: ["体育", "体育培训", "体育培训", "体育培训", "体育培训"],
  // },
  // {
  //   name: "服务类目",
  //   children: ["生活服务", "家政服务"],
  // },
  // {
  //   name: "服务类目",
  //   children: ["汽车服务", "维修保养"],
  // },
];
//打开弹窗
const openModel = (title, width) => {
  $("#model-title").text(title);
  $(".model-content").css("width", `${width}px`);
  $("#mask").css("display", "block");
  $(".model-content").addClass("model-show");
};

//关闭弹窗
const closeModel = () => {
  $(".model-content").removeClass("model-show");
  $(".model-content").addClass("model-hidden");
  setTimeout(() => {
    $("#mask").css("display", "none");
    $(".model-content").removeClass("model-hidden");
  }, 200);
};
//消息提示
const showMessage = (text, path, className) => {
  $(".message").addClass(className);
  $($(".message").children()[0]).attr("src", path);
  $($(".message").children()[1]).text(text);
  $(".message").css("display", "block");
  $(".message").animate({ top: "20px" }, { duration: 300 });
  setTimeout(() => {
    $(".message").removeClass(className);
    $(".message").css("display", "none");
    $(".message").css("top", "0px");
  }, 1500);
};
//渲染预览者列表
const showPreviewList = () => {
  PreviewList.forEach((item) => {
    $("#wechatList").append(`<div class="channel-info-itemTester">
    <span>${item}</span>
     <span class="klein-icon klein-icon-closeLine" style="margin-left: 10px; cursor: pointer;"><svg width="1em" height="1em" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path d="M0 0h16v16H0z"></path><path d="M12.313 2.98l.707.707L8.707 8l4.313 4.313-.707.707L8 8.707 3.687 13.02l-.707-.707L7.293 8 2.98 3.687l.707-.707L8 7.293l4.313-4.313z" fill="currentColor"></path></g></svg></span>`);
  });
};
//保存预览者
const savePreview = () => {
  const text = $("#wechatNumber")[0].value;
  if (text == "") {
    showMessage("请先输入微信号！", "../img/warning.png", "warning");
  } else {
    PreviewList.push(text);
    showMessage("添加成功", "../img/success.png", "success");
    $("#wechatNumber")[0].value = "";
    closeModel();
  }
};
//预览者弹窗
const showPreviewModel=()=>{
  $("#model-warp").empty();
  $("#model-warp").append(`<div class="input-box">
  <input id="wechatNumber" class="model-input" type="text" placeholder="请输入微信号" />
</div>
<div class="channel-info justify-start">
  <div class="channel-info-textGray">注意：</div>
  <div class="channel-info-text">
    <span>1.一次只能输入一个微信号，点击回车确认后，可再输入下一个微信号</span><br />
    <span>2.可删除预览者微信号</span>
  </div>
</div>
<div class="channel-info-testerList" id="wechatList">
  <div class="channel-info-itemTester">
    <span>dogcms</span>
    <span class="klein-icon klein-icon-closeLine" style="margin-left: 10px; cursor: pointer"><svg width="1em"
        height="1em" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
        <g fill="none" fill-rule="evenodd">
          <path d="M0 0h16v16H0z"></path>
          <path
            d="M12.313 2.98l.707.707L8.707 8l4.313 4.313-.707.707L8 8.707 3.687 13.02l-.707-.707L7.293 8 2.98 3.687l.707-.707L8 7.293l4.313-4.313z"
            fill="currentColor"></path>
        </g>
      </svg></span>
  </div>
</div> 
`);
  showPreviewList();
  $("#model-title").css("color", "#1e2226");
  $("#wechatList").on("click", "span", function () {
    PreviewList = PreviewList.filter((item) => {
      return item != $(this).prev().text();
    });
    $($(this).parent()[0]).remove();
  });
  $("#save-btn").text("保存");
  $("#btn-group").removeClass("btn-reverse");
  openModel("添加预览者", 600);
  okFn = savePreview;
}

$(document).ready(function () {
  $("#switch-btn").click(() => {
    const check = $("#switch-btn")[0].attributes[1].value;
    if (check === "false") {
      $("#switch-btn")[0].attributes[1].value = "true";
      $("#switch-control").animate({ left: "21px" });
      $("#switch-btn").addClass("switch-check");
    } else {
      $("#switch-btn")[0].attributes[1].value = "false";
      $("#switch-btn").removeClass("switch-check");
      $("#switch-control").animate({ left: "1px" });
    }
  });
  $(".text_9").click(() => {
    var appid = document.getElementById('appid').innerHTML;
    var aux = document.createElement("input");
    aux.setAttribute("value", appid);
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    showMessage("复制成功", "/static/open/img/success.png", "success");
  });

  //隐私设置
  // $('.text_4').click(() => {
  //   showPreviewModel();
  // });

  //  保存按钮
  $("#save-btn").click(() => {
    okFn();
  });
  //添加预览者
  $(".text_17").click(() => {
    //showPreviewModel();
  });
  $("#del-icon").click(() => {
    closeModel();
  });
  $("#cancel-btn").click(() => {
    closeModel();
  });
  $("#wechatNumber").focus(function () {
    $(this).parent().addClass("input-focus");
  });
  $("#wechatNumber").blur(function () {
    $(this).parent().removeClass("input-focus");
  });

  //审核失败弹框
  // $(".thumbnail_7").click(() => {
  //   $("#model-warp").empty();
  //   $("#model-warp").append(`<div class="channel-info-errmsgBox">
  //   1.
  //   您好，您的小程序检测类目空白，请到微信后台设置类目或将所有权限授权给微盟，感谢您的支持；
  // </div>
  // <div class="channel-info-tips">
    
  // </div>`);
  //   $("#save-btn").text("确定");
  //   $("#model-title").css("color", "#1e2226");
  //   $("#btn-group").removeClass("btn-reverse");
  //   openModel("失败原因", 600);
  //   okFn = closeModel;
  // });
  //提交审核按钮
  // $(".text-wrapper_5").click(() => {
  //   $("#model-title").css("color", "#ff5050");
  //   $("#save-btn").text("确定");
  //   $("#btn-group").addClass("btn-reverse");
  //   $("#model-warp").empty();
  //   $("#model-warp").append(`<div class="check-list"></div>`);
  //   checkList.forEach((item, index) => {
  //     $(".check-list").append(`<div class="check-item" id="checkOption${index}">
  //     <input type="radio" name="" id="checkInput${index}" class="check-input">
  //     <span class="check-title">${item.name}</span>
  //   </div>`);

  //     item.children.forEach((child) => {
  //       $(`#checkOption${index}`).append(`<div class="check-option">${child}</div>`);
  //     });
  //   });
  //   openModel("选择服务类目", 750);
  //   okFn = closeModel;
  // });
});

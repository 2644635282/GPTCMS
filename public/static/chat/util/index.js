$(function() {

    let answerIndex = 0;

    let isLoading = false;

    // 左侧question
    let activeIndex = 0
    let groupId = 0
    let questionDiv = $('#question')
    let questionLeftData = []
    let questionBottomData = []

    let answerData = []

    getQuestionsLeft('')

    // $('#isValid').bind('input propertychange', function()
    // {   
    //     let str = $("#isValid").val().trim()
    //     if(str==0){
    //         showDia()
    //     }else{

    //     }
    //    // 是否需要展示密码框

    // });
    let str = $("#isValid").val().trim()
    if(str==0){
       showDia() 
    }
    

    

    function showDia (){
        let model = $.confirm({
            title: '请输入密码',
            content: '<input id="password" class="s-pass bg-transparent border-0"  type="text" value="" placeholder="请输入">',
            buttons:{
                '确认':{
                    action:function async(r){
                       
                        let str = $("#password").val().trim()
                        let url = '/chat/index/verifypwd?pwd='+str
                        fetch(url)
                        .then(response => {
                          if (!response.ok) {
                            throw new Error('Network response was not ok');
                          }
                          return response.text();
                        })
                        .then(data => {
                         
                          let json = JSON.parse(data)
                         
                          if(json.status=='success'){
                           location.reload()
                              
                          }else{
                            $.confirm({
                                closeIcon: false,
                                title: '提示',
                                content: '密码错误',
                                buttons:{
                                    '重新输入密码':{
                                        action:function async(r){
                                            showDia()
                                        }}}
                                
                            })
                          }
                         
              
                        })
                        .catch(error => {
                            $.dialog({
                                closeIcon: false,
                                title: '提示',
                                content: '密码错误',
                                buttons:{}
                            })
                        });  

                       
                     
                        
                    }
                    
                },
               
            },
            
        });
        
    }

    
    

    $('#add-task').click(function(){
        let url = '/chat/index/addgroup?name=新的会话'
        fetch(url)
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.text();
          })
          .then(data => {
            console.log(data,'ssss')
            let json = 0
            try{
                json = JSON.parse(data)
                console.log(json,'cccc')
            }catch{}
            
            if(json.status=='success'){
                getQuestionsLeft('',false)
                
                console.log('ddddd')
                
            }
           

          })
          .catch(error => {
            console.error('Error:', error);
          });  

        
    })
    let input1 = document.getElementById('search-input');  
    input1.addEventListener('keydown', function(event) {
        if (event.keyCode === 13) {
            getQuestionsLeft(input1.value)
        }
    });

    $('#send-btn').click(function(){
        sendText()
    })
    let input2 = document.getElementById('input-question');  
    input2.addEventListener('keydown', function(event) {
        if (event.keyCode === 13) {
            sendText()
        }
    });

    function sendText(){
        let str = $("#input-question").val().trim()
        let notIncludeArr = ['/','']
        if(answerData.length==0){
            $('#intro').css('display','none')
            $('#dialogue').css('height','calc(100vh - 150px - '+ document.getElementById('footer').clientHeight+ 'px)')
        }
        if(!notIncludeArr.includes(str)&&!isLoading){
            dialogueDiv.append('<div class="row s-row-center"><div class="col-md-10 s-mb14 stretch-card grid-margin"><div class="smr-10"><img src="/static/chat/assets/images/user.png" class="s-avatar" alt="circle-image" /></div><div class="s-card1 card-img-holder text-white"><div class="s-dia-card s-padding0"><div class="mainText info"><p class="s-card-words">'+str+'</p></div></div></div></div></div>')

            $("#input-question").val('');
            isLoading = true;
            getAnswer(str,groupId)
        }
    }


    // 左侧的问题列表
    function getQuestionsLeft(val,isIncludeBottom){
        let url = '/chat/index/getgrouplist'
        if(val) url+='?name='+val
        fetch(url, {
            headers: {
              'Content-Type': 'application/json'
            }
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.text();
          })
          .then(data => {
            console.log(data,'ssss')
            let json = 0
            try{
                json = JSON.parse(data)
                console.log(json,'cccc')
            }catch{}
            
            if(json.data){
                questionLeftData = json.data.item
                // if(isIncludeBottom){
                    if(json.data.item.length>0){
                        groupId = json.data.item[0].id
                        getAnswerList()
                    // }
                    // questionBottomData = json.data.item
                    // getQuestionsBottom(json.data.item)
                }
                getQuestionLeftHtml(json.data.item)
            }
           

          })
          .catch(error => {
            console.error('Error:', error);
          });  
    }






    function getQuestionLeftHtml(data,isFirstEdit){
       
        questionDiv.empty()
        data.forEach((item,index)=>{
            let htmlStr = ''
            if(index==activeIndex){
                htmlStr='<li id=question-item-'+index+ ' class="nav-item nav-link s-line-2 active1">'
            }else{
                htmlStr='<li id=question-item-'+index+ ' class="nav-item nav-link s-line-2">'
            }
            if(isFirstEdit){
                htmlStr =htmlStr+'<div class="height"><div><input id="input-field-'+index+'" type="text" value="'+item.name+'" class="menu-title title s-ml-10 form-control bg-transparent border-0" placeholder="请输入分组"></div><div class="hover1"><i class="mdi mdi-border-color menu-icon s-icon" id="edit-'+index+'"></i></div></div></li>'
               
                event.stopPropagation();
            }else{
                htmlStr =htmlStr+'<div class="height"><div><input id="input-field-'+index+'" type="text" disabled value="'+item.name+'" class="menu-title title s-ml-10 form-control bg-transparent border-0" placeholder="请输入分组"></div><div class="hover1"><i class="mdi mdi-border-color menu-icon s-icon" id="edit-'+index+'"></i></div></div></li>'
          
            }
            
            $(questionDiv).append(htmlStr)

            if(isFirstEdit){
                let input = document.getElementById('input-field-'+index);
                input.addEventListener('keydown', function(event) {
                    if (event.keyCode === 13) {
                        saveQuestions(input.value,index)
                    }
                });
            }
    
            // 点击问题
            $('#question-item-'+index).click(function(event) {
                console.log(index,'点击问题')
                activeIndex = index
                groupId = data[index].id
                getAnswerList()
                
                $('#question-item-'+index).addClass('active1');
                data.forEach((item1,index1)=>{
                    if(index1!=activeIndex){
                        $('#question-item-'+index1).removeClass('active1');
                    }
                })
            })
            // 点击编辑
            $('#edit-'+index).click(function(event) {
                console.log(index,'ccccc')
                let input = document.getElementById('input-field-'+index);
                input.removeAttribute('disabled');
                
                console.log(index,'点击编辑')
              
                input.addEventListener('keydown', function(event) {
                    if (event.keyCode === 13) {
                        saveQuestions(input.value,index)
                    }
                });
                event.stopPropagation();
            });
    
             // 删除
            $('#delete-'+index).click(function(event) {
                console.log(index,'点击删除')
                event.stopPropagation();
            });
            
        })
    }

    function saveQuestions(val,index){
        console.log(val,questionLeftData,index,'ccccc')
        let url;
        if(questionLeftData[index].id){
            url = '/chat/index/editgroup?id='+questionLeftData[index].id+'&name='+val
        
        }else{
            url = '/chat/index/addgroup?name='+val
        }
       fetch(url)
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.text();
          })
          .then(data => {
            console.log(data,'ssss')
            let json = 0
            try{
                json = JSON.parse(data)
                console.log(json,'cccc')
            }catch{}
            
            if(json.status=='success'){
                if(index!=undefined){
                    let input = document.getElementById('input-field-'+index);
                    input.setAttribute('disabled', true);
                }else{
                    return json.data.id
                }
                
                console.log('ddddd')
                
            }
           

          })
          .catch(error => {
            console.error('Error:', error);
          });  
       

    }
    
    


    // 底部question
    let activeIndex1 = 0
    let questionDiv1 = $('#question1')

    questionBottomData =  [
        {
        'name':'帮我写一首情诗'
    },
    {
        'name':'写一个知乎问答'
    },
    {
        'name':'写一篇小红书分享文案'
    }]

    getQuestionsBottom(questionBottomData)

    // 底部question
    function getQuestionsBottom(data){
       data.forEach((item,index)=>{
            let htmlStr = ''
            if(index==activeIndex1){
                htmlStr='<span id=question1-item-'+index+ ' class="s-blo">'
            }else{
                htmlStr='<span id=question1-item-'+index+ ' class="s-blo">'
            }
            htmlStr =htmlStr+'<div class="height s-p-5"><div><span class="">'+item.name+'</span></div><div class="hover1"></div></div></span>'
          
            $(questionDiv1).append(htmlStr)
    
            // 点击问题添加
            $('#question1-item-'+index).click(function(event) {
                console.log(index,'点击问题')
                // groupId = data[index].id
                // getAnswerList()
                $("#input-question").val(data[index].name);
                // changeTopicShow()
            })
            
        })
    }




      // 输入框模版temp
      let activeIndex2 = 0
      let tempDiv = $('#temp')

      function getTemp(){
        let data2= [
            {
            'name':'测试test测试测试测试测试测试测试测试测试测试测试测试测试测试测试'
        },
        {
            'name':'test1'
        },
        {
            'name':'test2'
        },
       
    ]
        data2.forEach((item,index)=>{
            let htmlStr = ''
            if(index==activeIndex1){
                htmlStr='<div id=temp-item-'+index+ ' class="s-blo-temp">'
            }else{
                htmlStr='<div id=temp-item-'+index+ ' class="s-blo-temp">'
            }
            htmlStr =htmlStr+'<div class="s-flex-2 s-p-5"><div><i class="mdi mdi-border-color s-send-icon"></i><span class="s-temp-font">'+item.name+'</span></div><div class="s-sort">'+(index+1)+'</div></div></div>'
          
            $(tempDiv).append(htmlStr)
    
            // 点击问题添加
            $('#temp-item-'+index).click(function(event) {
                console.log(index,'点击模版')
                
                $("#input-question").val(data2[index].name);
                changeTopicShow()
            })
            
        })
      }
      getTemp()
     

    $("#input-question").bind('input propertychange', function()
    {
        changeTopicShow()
    });

    function changeTopicShow(){
        if($("#input-question").val()=='/'){
            $("#input-question").addClass('s-input-top')
            $('#topic').addClass('s-show-no')
            $('#temp').removeClass('s-show-no')
            
        }else{
            $("#input-question").removeClass('s-input-top')
            $('#topic').removeClass('s-show-no')
            $('#temp').addClass('s-show-no')
           
        }
    }











    // 逐字输入

    var dom_index = 0; // p标签计数index
    // 处理每一行
    function process_one(arrs, dom) {
        let nodeArr = []
        nodeArr = $("#info_one"+answerIndex+" p")
        if (nodeArr.eq(0).text().length == 0) return;

        let wordIndex = 0 // 每个文字的index
        let node = nodeArr.eq(dom_index) // 获取P标签
        let textItem = nodeArr.eq(dom_index).text() // P标签的文字
        let time = nodeArr.eq(0).text().length - 1 //每行完成时间，作为下一行开始时间间隔
        
        // 开始执行逐字加载
      
        add(node, textItem, wordIndex, dom)
        let timer2 = null;
        
        // 设置定时器来间隔开每行文字的触发
       
        
        timer2 = setTimeout(function() {
            if (dom_index < nodeArr.length-1) {
                process_one(arrs, dom)
            } else {
                clearTimeout(timer2)
                return;
            }
        }, time * 500)
    }

    // 添加文字
    function add(node, textItem, wordIndex, dom) {
        let newP = ''
        let timer = null
       
        console.log(answerIndex)
        console.log('xxxxxxxxx')
        
        timer = setInterval(function() {
            if (wordIndex != textItem.length) {
                newP += textItem[wordIndex]
                wordIndex++;
                newP = newP.replace('<div class="blink"></div>','')
                newP+='<div class="blink"></div>'
                node.html(newP)
                $("#info_show_one").append(node)
            } else {
                wordIndex = 0;
                newP = newP.replace('<div class="blink"></div>','')
                node.html(newP)
                $("#info_show_one"+answerIndex).append(node)
                clearInterval(timer)
            }
            if(wordIndex==1){
                $('#info_one'+answerIndex).css('opacity','1')
            }
        }, 300)

    }
    // 调用  把P标签和目标dom的id都穿进去，当然你也可以才用其他方法来获取P标签
    

    let dialogueDiv = $('#dialogue')

    function getAnswer(message,group_id){
        
        let url = '/chat/index/sendtext?message='+message
        if(group_id){
            url=url+'&group_id='+group_id
        }
        scrollBottom()
        let source = new EventSource(url,{
            type: 'text/html',
            headers:{
                accept:'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7'
            }
        })
        console.log(url,'llll')
        answerIndex++;
        dialogueDiv.append('<div class="row s-row-center"><div class="col-md-10 s-mb14 stretch-card grid-margin"><div class="smr-10"><img src="/static/chat/assets/images/robot.png" class="s-avatar" alt="circle-image" /></div><div class="s-card card-img-holder text-white"><div class="s-dia-card"><div style="color: #333;" id="info_one'+answerIndex+'" class="mainText info"><p class="s-card-words">'+'</p></div><div></div> </div></div></div></div>')
        dialogueDiv.append('<div class="row s-row-center"><div class="col-md-10 s-mb14 stretch-card grid-margin"><div class="smr-10"><div class="s-avatar" alt="circle-image" /></div><div style="background:transparent" class="s-card card-img-holder text-white"><div class="s-dia-card"><div class="mainText info"><p class="copy-btn" id="copynew'+answerIndex+'">复制</p></div><div></div> </div></div></div></div>')
        
        // <div  class="s-copy copy-btn">复制</div>')
        $('#copynew'+answerIndex).click(function(e){
            let id = e.target.id.replace('copynew','info_one')
            copy(document.getElementById(id).innerText)
        })
        source.onopen =function(e){

            // console.log("连接已经建立：",e);
            
            //document.getElementById("datadiv").innerText += "连接已经建立：" + this.readyState +"\n";
                 
            
            };
      
            
            source.onmessage =function(event){
                // console.log(event.data)
            var data = event.data;
            data = data.replace(/<br\s*\/?>/g, "\n")
            document.getElementById("info_one"+answerIndex).innerText+= data;
            scrollBottom()
            
            };
            
            source.onerror =function(err){
                console.log('连接错误')
                source.close();
                isLoading = false;
            };


          
       
    }
    function copy(str){
        console.log('ccccc')
        let clipboard = new ClipboardJS('.copy-btn', {
            text: function (el) {
       
                return str;
                }
            });

        //复制成功后的方法
        let isSuccess = false
        clipboard.on('success', function (e) {
            isSuccess = true
                    
        })
        setTimeout(()=>{
            if(isSuccess){
                new Tips("success",'复制成功',1000);
            }
        },50)
        //复制失败后的方法 
        clipboard.on('error', function (e) {
            // console.log(e);
            // $.alert('复制失败，请重试！', '复制失败',)
        });
      
    }

    function getAnswerList(){
         let url = '/chat/index/getgroupchatmsg?group_id='+groupId
       
        fetch(url, {
            headers: {
              'Content-Type': 'application/json'
            }
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.text();
          })
          .then(data => {
            console.log(data,'ssss')
            let json = 0
            try{
                json = JSON.parse(data)
                console.log(json,'cccc')
            }catch{}
            
            if(json.data){
                console.log(json.data,'vvvvv')
                answerData = json.data
                if(answerData.length>0){
                    console.log('ssssssvvvv',document.getElementById('footer').clientHeight)
                    $('#intro').css('display','none')
                    $('#dialogue').css('height','calc(100vh - 150px - '+ document.getElementById('footer').clientHeight+ 'px)')
                   
                }else{
                    $('#intro').css('display','flex')
                    $('#dialogue').css('height','0px')
                }
                dialogueDiv.empty()
                json.data.forEach((item,index)=>{
                    if(index%2==0){
                        dialogueDiv.append('<div class="row s-row-center"><div class="col-md-10 s-mb14 stretch-card grid-margin"><div class="smr-10"><img src="/static/chat/assets/images/user.png" class="s-avatar" alt="circle-image" /></div><div class="s-card1 card-img-holder text-white"><div class="s-dia-card s-padding0"><div class="mainText info"><p class="s-card-words">'+item.content+'</p></div></div></div></div></div>')
                        
                    
                    }else{
                        dialogueDiv.append('<div class="row s-row-center"><div class="col-md-10 s-mb14 stretch-card grid-margin"><div class="smr-10"><img src="/static/chat/assets/images/robot.png" class="s-avatar" alt="circle-image" /></div><div class="s-card card-img-holder text-white"><div class="s-dia-card"><div class="mainText info"><p id="copytext'+index+'" class="s-card-words">'+item.content+'</p></div></div></div></div></div>')
               
                        // dialogueDiv.append('<div id="copyorignal'+index+'" class="s-copy copy-btn"><div class="s-avatar-blo"></div><div class="s-dia-card"><p>复制</p></div></div>')
                        dialogueDiv.append('<div class="row s-row-center"><div class="col-md-10 s-mb14 stretch-card grid-margin"><div class="smr-10"><div class="s-avatar" alt="circle-image" /></div><div style="background:transparent;margin-top:-20px" class="s-card card-img-holder text-white"><div class="s-dia-card"><div class="mainText info"><p class="copy-btn" id="copyorignal'+index+'">复制</p></div><div></div> </div></div></div></div>')
        
                        
                        $('#copyorignal'+index).click(function(e){
                            let id = e.target.id.replace('copyorignal','copytext')
                            copy(document.getElementById(id).innerText)
                            
                        })
                    }
                })
           }
           

          })
          .catch(error => {
            console.error('Error:', error);
          });  
    }

    function scrollBottom(){
        document.getElementById('dialogue').scrollTop = document.getElementById('dialogue').scrollHeight
       
        // $("#dialogue").animate({ scrollTop: document.getElementById('dialogue').scrollHeight }, "slow");
        console.log('cccc')
    }


});
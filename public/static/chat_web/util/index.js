$(function() {

    let arr = [
        {
            img:1,
            title:'小说',
            desc:'为你续写精彩小说',
            person:1.5
        },
        {
            img:2,
            title:'语言翻译',
            desc:'翻译成想要的语言',
            person:0.9
        },
        {
            img:3,
            title:'诗词情诗',
            desc:'生成各种类型的精美诗词',
            person:3.8
        },
        {
            img:4,
            title:'个性签名',
            desc:'生成各种社交个性签名',
            person:2.1
        },
        {
            img:5,
            title:'合同模板',
            desc:'生成各种类型合同模板',
            person:6.3
        },
        {
            img:6,
            title:'梦境分析',
            desc:'为您的梦境进行解梦',
            person:5.5
        },
        {
            img:7,
            title:'作文',
            desc:'生成各阶段写作内容',
            person:6.8
        },
        {
            img:8,
            title:'短视频',
            desc:'生成短视频大纲',
            person:2.8
        },
        {
            img:9,
            title:'学习计划',
            desc:'学习计划制定高手',
            person:3.5
        },
        {
            img:10,
            title:'概括总结',
            desc:'快速搞定概况总结',
            person:2.6
        },
        {
            img:11,
            title:'周报',
            desc:'AI帮你几秒搞定周报',
            person:1.2
        },
        {
            img:12,
            title:'日程计划',
            desc:'	为您制定完美计划',
            person:0.8
        },
    ]
    let topicDiv = $('#topic1')
    arr.forEach(item=>{
        topicDiv.append('<div class="s-blo2 s-flexcc"><div class="s-radius"><img class="s-img3" src="/static/chat_web/assets/images/'+item.img+'.png"><div class="s-tip1">'+item.person+'w人使用</div></div><div class="s-t1">'+item.title+'</div><div class="s-t2">'+item.desc+'</div></div>')
    })



   
});
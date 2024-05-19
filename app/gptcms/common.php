<?php

// 这是系统自动生成的公共文件

//判断是什么平台
function platform()
{
    //ll($_SERVER['HTTP_USER_AGENT']);
    $platform='h5';
    $a_strtolower = strtolower($_SERVER['HTTP_USER_AGENT']??'');
    if(strpos($a_strtolower, "micromessenger"))//公众号MicroMessenger
    {
        $token = app('request')->header('token');
        if(strpos($token, "wxxcx_") !== false)//小程序
        {
            $platform = 'mpapp';
        }
        else
            $platform = 'wxapp';

    }
    elseif(strpos($a_strtolower, "uni-app") || strpos($a_strtolower, "Html5Plus"))//app
    {
        $platform = 'app';
    }
    return $platform;
}

function ismobile()
{
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : ''; 
    $mobile_browser = '0'; 
    if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
        $mobile_browser++; 
    if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false)) 
        $mobile_browser++; 
    if(isset($_SERVER['HTTP_X_WAP_PROFILE'])) 
        $mobile_browser++; 
    if(isset($_SERVER['HTTP_PROFILE'])) 
        $mobile_browser++; 
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4)); 
    $mobile_agents = array( 
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac', 
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno', 
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-', 
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-', 
        'newt','noki','oper','palm','pana','pant','phil','play','port','prox', 
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar', 
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-', 
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp', 
        'wapr','webc','winw','winw','xda','xda-'
    ); 
    if(in_array($mobile_ua, $mobile_agents)) 
        $mobile_browser++; 
    if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false) 
        $mobile_browser++; 
    // Pre-final check to reset everything if the user is on Windows 
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false) 
        $mobile_browser=0; 
    // But WP7 is also Windows, with a slightly different characteristic 
    if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false) 
        $mobile_browser++; 
    if($mobile_browser>0) 
        return true; 
    else
        return false;
}

function wxqfParseData($data)
{
    //一次性返回数据
    if(json_decode($data) && @json_decode($data)->result){
        return json_decode($data)->result;
    }


   //流式数据
    $data = str_replace('data: {', '{', $data);
    // echo $data;
    // $data = rtrim($data, "\n\n");
    $data = explode("\n",$data);
    // var_dump($data);
    $res = '';
    for ($i=0; $i < count($data); $i++) { 
        $data[$i] = @json_decode($data[$i], true);
        if(is_array($data[$i])){
            if($data[$i]['is_end'] === true){
                $res .= $data[$i]['result'] . " data: [DONE]";    
                break;
            }
            $res .= $data[$i]['result'];
        }
    }
    return $res;
}
function fastParseData($data)
{
    //一次性返回数据
    if(@json_decode($data)->choices[0]->message->content){
        return json_decode($data)->choices[0]->message->content;
    }
    //file_put_contents('./worker.txt', $data.'end', 8);
    //流式数据
    // $data = str_replace('data: {', '{', $data);
    // $data = rtrim($data, "\n\n");
    $data = explode("\n",$data);
    $res = '';
    for ($i=0; $i < count($data); $i++) { 
        if($data[$i] === "event: answer"){
            if($data[$i+1] == "data: [DONE]"){
                $res .=  "data: [DONE]";    
                break;
            }
            $str = str_replace('data: {', '{', $data[$i+1]);
            $strarr = json_decode($str,true);
            if(!isset($strarr['choices'][0]["delta"]["content"])) continue;
            $res .= $strarr['choices'][0]["delta"]["content"];
        }
    }
    return $res;
}
function parseData($data)
{
    //一次性返回数据
    if(@json_decode($data)->choices[0]->message->content){
        return json_decode($data)->choices[0]->message->content;
    }
    //file_put_contents('./worker.txt', $data.'end', 8);
    //流式数据
    $data = str_replace('data: {', '{', $data);
    $data = rtrim($data, "\n\n");
    $data = explode("\n\n",$data);
    $res = '';
    foreach ($data as $key => $d) {
        if (strpos($d, 'data: [DONE]') !== false) {
            $res .= 'data: [DONE]';
            break;
        }
        $d = @json_decode($d, true);
        if (!is_array($d)) {
            continue;
        }
        if (isset($d['choices']['0']['finish_reason']) && $d['choices']['0']['finish_reason'] == 'stop') {
            $res .= 'data: [DONE]';
            break;
        }
        if(isset($d['choices']['0']['finish_reason']) && $d['choices']['0']['finish_reason'] == 'length') {
            $res .= 'data: [DONE]';
            break;
        }
        $content = $d['choices']['0']['delta']['content'] ?? '';
        $res .= $content;
    }
    return $res;
}
function parseTyqwData($data)
{
    //一次性返回数据
    if(@json_decode($data)->output->text){
        return json_decode($data)->output->text;
    }
    //流式数据
    $data = str_replace('data:{', '{', $data);
    $data = rtrim($data, "\n");
    $data = explode("\n",$data);
    $res = '';
    foreach ($data as $key => $d) {
        $res = '';
        $d = @json_decode($d, true);
        if (!is_array($d)) {
            continue;
        }
        if(isset($d['output']['text'])){
            $res = $d['output']['text'];
        }
        if (isset($d['output']['finish_reason']) && $d['output']['finish_reason'] == "stop") {
            $res .= ' data: [DONE]';
            break;
        }
    }
    return $res;
}
function parseMiniMaxData($data)
{
    //流式数据
    $data = str_replace('data: {', '{', $data);
    $data = rtrim($data, "\n\n");
    $data = explode("\n\n",$data);
    $res = '';
    foreach ($data as $key => $d) {
        // if (strpos($d, 'data: [DONE]') !== false) {
        //     $res .= 'data: [DONE]';
        //     break;
        // }
        $d = @json_decode($d, true);
        if (!is_array($d)) {
            continue;
        }
        if (isset($d['choices']['0']['finish_reason'])) {
            $res .= 'data: [DONE]';
            break;
        }
        // if ($d['choices']['0']['finish_reason'] == 'stop') {
        //     $res .= 'data: [DONE]';
        //     break;
        // }
        // if($d['choices']['0']['finish_reason'] == 'length') {
        //     $res .= 'data: [DONE]';
        //     break;
        // }
        // if($d['choices']['0']['finish_reason'] == 'max_output') {
        //     $res .= 'data: [DONE]';
        //     break;
        // }
        $content = $d['choices']['0']['messages'][0]['text'] ?? '';
        $res .= $content;
    }
    return $res;
}

function getDomain(){
    if(isset($_SERVER['HTTP_X_CLIENT_SCHEME'])){
        $scheme = $_SERVER['HTTP_X_CLIENT_SCHEME'] . '://';
    }elseif(isset($_SERVER['REQUEST_SCHEME'])){
        $scheme = $_SERVER['REQUEST_SCHEME'] . '://';
    }else{
        $scheme = 'http://';
    }
    $domain = $scheme . $_SERVER['HTTP_HOST'];
    return $domain;
}


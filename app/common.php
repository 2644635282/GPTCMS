<?php
// 应用公共文件


function error($msg ='',$data = []){
    return json(['msg'=>$msg,'status'=>'error','data'=>$data]);
}
function success($msg ='',$data = []){
    return json(['msg'=>$msg,'status'=>'success','data'=>$data]);
}

/**
*  curl 获取指定长度（$len）字符串
 * @param int $len       长度
 * @param int $index     开始位置
 * @param string $baseStr    基础字符串
 * @return string        返回内容
**/
function getRandStr(int $len,int $index = 0,string $baseStr=""){
    $chars = array(
        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        '0','1','2','3','4','5','6','7','8','9'
        );
    $charsLen = count($chars) - 1;
    shuffle($chars);
    $output = $baseStr.base_convert($index, 10, 36);
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}

/**
*  curl get 请求
 * @param string $url       基于的baseUrl
 * @param int $flag         标志位, 是否验证ssl
 * @return string           返回的资源内容
**/
function curlGet($url, $flag = 0){
    $ch = curl_init();
    if(! $flag) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_URL, $url);
    $response = curl_exec($ch);
    curl_close($ch);
    //-------请求为空
    if(empty($response)){
        return null;
    }

    return $response;
}

/**
*  curl post 请求
 * @param string $url       基于的baseUrl
 * @param array $data       请求的参数列表
 * @param int $flag         标志位, 是否验证ssl
 * @return string           返回的资源内容
**/
function curlPost($url, $data, $flag = 0){
    $ch = curl_init();
    if(! $flag) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);

    curl_close($ch);
    return $ret;
}

 /**
 * 去表情符
 * @param $adminUserId int 代理id
 * @return \think\Response
 */
function filterEmoji($str){
  $str = preg_replace_callback( '/./u',
      function (array $match) {
        return strlen($match[0]) >= 4 ? '' : $match[0];
      },
      $str);
   return $str;
}

/**
* xml转数组
* 
* @return \think\Response
*/
function xmlToArray($xml)
{
  $res = simplexml_load_string($xml, 'SimpleXmlElement', LIBXML_NOCDATA);
  return json_decode(json_encode($res), true);
}

/**
 * 获取所有应用下指定文件列表
 * @param string $file_name
 * @return array
 */
function getFileList($fileName=''){
    $fileList=[];
    if(!$fileName)return $fileList;
    $addons_dir  = base_path();
    $dirs = scandir($addons_dir);
    foreach ($dirs as $dir) {
        $path=$addons_dir.$dir;
        if(!is_dir($path)) continue;
        if($dir!='.'&&$dir!='..'){
            if(is_file($path."/{$fileName}")){
                $fileList[]=$path."/{$fileName}";
            }
        }
    }
    return $fileList;
}

/**
 * 设置返回头
 * @param $key
 * @param $value
 * @return array
 */
function set_header($key=null,$value=null){
    static $header=[];
    if($key)$header[$key]=$value;
    return $header;
}

/**
 * import css js image out of pub director
 * @param $path
 * @param $code
 * @param bool $refresh
 * @return mixed|string
 */
function import($path,$code=''){
    $app_path = $code ? base_path().$code.'/' : app_path();
    $ext = substr(strrchr($path, '.'), 1);
    $source_path= $app_path.'view/'.ltrim($path,'/\\');

    if(($ext == 'html'||$ext == 'php')&&is_file($source_path)){
        include($source_path);
        return;
    }
    if(is_file($source_path)){
        $pub_son_path ="static/".basename($app_path)."/".ltrim($path,'/\\');
        $publish_path=root_path().'public/'.$pub_son_path;
        $param='';
        //文件如果被改变就重新发布
        if(!is_file($publish_path)|| filemtime($source_path) > filemtime($publish_path)){
            set_header('X-Accel-Buffering','no'); // 刷新缓存
            set_header("Expires","Mon, 26 Jul 1997 05:00:00 GMT");
            set_header("Cache-Control","no-cache, must-revalidate");
            set_header("Pragma: no-cache");
            is_dir(dirname($publish_path)) or mkdir(dirname($publish_path),0751,true);
            copy($source_path,$publish_path);

        }
        $param='?t='.time();
        $url=request()->domain().'/'.$pub_son_path;
        if($ext == 'js'){
            return "<script src='{$url}{$param}'></script>";
        }elseif ($ext == 'css'){
            return '<link rel="stylesheet" type="text/css" href="'.$url.'" media="all" />';
        }else{
            return $url;
        }
    }

    return '';

}

/**
 * 加密
 * @param string $str   要加密的字符串
 * @param string $key   盐
 * @return string       返回内容
 */
function ktEncrypt($str="",$key=""){
    $key = $key?:'kt';
    $str = md5($str).$key;
    $str = base64_encode($str);
    return $str;
}

/**
 * 站点信息
 * @return array
 */
function site_info(){
    $info = app('db')->table('kt_base_agent')->where(['domain'=>app('request')->host()])->field('id,user_logo,webname,webtitle,key_word,describe,kf_code,telephone,gzh_code,copyright,company_name,company_address,record_number,email,domain,qq,login_logo')->find();
    if(!$info){
        $info = app('db')->table('kt_base_agent')->where(['isadmin'=>1])->field('id,user_logo,webname,webtitle,key_word,describe,kf_code,telephone,gzh_code,copyright,company_name,company_address,record_number,email,domain,qq,login_logo')->find();
    }
    return $info;
}

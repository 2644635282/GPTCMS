<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

// 这是系统自动生成的公共文件
/**
 * 执行sql 保证sql执行完
 * @param $content
 * @return array
 * @throws \Exception
 */
function updateRunSql($content){
    // error_reporting(0);
    //遍历执行sql语句
    //去除空行和注释
    $content=removeBom($content);//自动去除bom头
    // $content = preg_replace("/[\/\*][\s\S\r\n]*[\*\/]/", '', $content);
    // $content = preg_replace("/[--]+(.+)(\r\n)+/", '', $content);
    $content = preg_replace("/\/\*[\s\S\r\n]*\*\//", '', $content);
    $content = preg_replace("/--+(.+)(\r\n)+/", '', $content);
    $sqlArr = preg_split("/;[\r\n]*/", $content);
    $error_message = '';
    foreach ($sqlArr as $v) {
        $v = str_replace( "\r\n",' ',$v);
        if (empty($v)) continue;
        try {
            think\facade\Db::execute($v);
        } catch (\Exception $e) {
            // $error_message .= $e->getMessage() . ' ';
            // \Log::error('sql执行错误:' . $e->getMessage());
            $error_message = $v;
        }
    }
    if ($error_message) {
        return ['status'=>'error','msg'=> $error_message];
    } else {
        return ['status'=>'success','msg'=>'执行成功'];
    }
}


/**
 * 自动找到内容bom头并去除
 * @param $content
 * @return bool|string
 */
function removeBom($content){
    $contents=$content;
    $charset[1]=substr($contents,0,1);
    $charset[2]=substr($contents,1,1);
    $charset[3]=substr($contents,2,1);
    if(ord($charset[1])==239 && ord($charset[2])==187 && ord($charset[3])==191){

        $content=substr($content,3);
    }
    return $content;
}

/**
 *
 * 清空文件夹
 * @param $dirName
 * @param $oldtime 小于的时间
 * @param $newtime 大于的时间
 */
function clear_dir($dirName){
    if(is_dir($dirName)){//如果传入的参数不是目录，则为文件，应将其删除
        //如果传入的参数是目录
        $handle = @opendir($dirName);
        while(($file = @readdir($handle)) !== false){
            if($file!='.'&&$file!='..'){
                $dir = $dirName . '/' . $file; //当前文件$dir为文件目录+文件
                remove_dir($dir);
            }
        }
        remove_dir($dirName);
    }
}

/**
 *
 * 清空并删除文件夹
 * @param $dirName
 * @param $oldtime 小于的时间
 * @param $newtime 大于的时间
 */
function remove_dir($dirName,$oldtime=null,$newtime=null){
    if(!is_dir($dirName)){//如果传入的参数不是目录，则为文件，应将其删除
        $mtime = filectime($dirName);
        if($oldtime===null&&$newtime===null){
            @unlink($dirName);
        }else{
            if(isset($oldtime)){
                if($mtime<$oldtime){
                    @unlink($dirName);
                }
            }
            if(isset($newtime)){
                if($mtime>$newtime){
                    @unlink($dirName);
                }
            }
        }
        return false;
    }
    //如果传入的参数是目录
    $handle = @opendir($dirName);
    while(($file = @readdir($handle)) !== false){
        if($file!='.'&&$file!='..'){
            $dir = $dirName . '/' . $file; //当前文件$dir为文件目录+文件
            remove_dir($dir,$oldtime,$newtime);
        }
    }
    closedir($handle);
    return @rmdir($dirName) ;
}
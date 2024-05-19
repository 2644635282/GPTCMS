<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use app\gptcms\model\CommonModel;
use app\gptcms\model\ApishopModel;
use think\facade\Db;
use think\facade\Session;

class Paint extends BaseApi
{
    private $expend = 0;
    private $vip;
    /**
     * 获取绘画渠道
     */
    public function getPaintSet()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_paintmodel_set')->where('wid',$wid)->json(["sd","yjai","gpt35","api2d35","replicate","linkerai_mj","apishop"])->find();
        $data = [];
        $data['paintmodel_status'] = $res['status'] ?? 0;
        $data['paintmodel'] = [];
        if($res && $data['paintmodel_status']){
                $res['sd']["type"] = "sd";
                $res['sd']["status"] = $res['sd']["status"]?:0;
                $res['sd']["nickname"] = $res['sd']["nickname"]?:"灵犀-SD";
                $res['sd']["expend"] = $res['sd']["expend"]?:1;
                $res['sd']["icon"] = $res['sd']["icon"]??"";
                $res['sd']["desc"] = $res['sd']["desc"]??"";
                $data['paintmodel'][] = $res["sd"];
            
            if($res['yjai']['status']){
                $res['yjai']["type"] = "yjai";
                $res['yjai']["nickname"] = $res['yjai']["nickname"]?:"意间AI绘画";
                $res['yjai']["expend"] = $res['yjai']["expend"]?:1;
                $res['yjai']["icon"] = $res['yjai']["icon"]??"";
                $res['yjai']["desc"] = $res['yjai']["desc"]??"";
                $data['paintmodel'][] = $res["yjai"];
            }
            if($res['replicate']['status']){
                $res['replicate']["type"] = "replicate";
                $res['replicate']["nickname"] = $res['replicate']["nickname"]?:"replicate-midjourney";
                $res['replicate']["expend"] = $res['replicate']["expend"]?:1;
                $res['replicate']["icon"] = $res['replicate']["icon"]??"";
                $res['replicate']["desc"] = $res['replicate']["desc"]??"";
                $data['paintmodel'][] = $res["replicate"];
            }
            if($res['gpt35']['status']){
                $res['gpt35']["type"] = "gpt35";
                $res['gpt35']["nickname"] = $res['gpt35']["nickname"]?:"gpt3.5绘画";
                $res['gpt35']["expend"] = $res['gpt35']["expend"]?:1;
                $res['gpt35']["icon"] = $res['gpt35']["icon"]??"";
                $res['gpt35']["desc"] = $res['gpt35']["desc"]??"";
                $data['paintmodel'][] = $res["gpt35"];
            }
            if($res['api2d35']['status']){
                $res['api2d35']["type"] = "api2d35";
                $res['api2d35']["nickname"] = $res['api2d35']["nickname"]?:"api2d-3.5绘画";
                $res['api2d35']["expend"] = $res['api2d35']["expend"]?:1;
                $res['api2d35']["icon"] = $res['api2d35']["icon"]??"";
                $res['api2d35']["desc"] = $res['api2d35']["desc"]??"";
                $data['paintmodel'][] = $res["api2d35"];
            }
            if($res['linkerai_mj'] && $res['linkerai_mj']['status']){
                $res['linkerai_mj']["type"] = "linkerai_mj";
                $res['linkerai_mj']["nickname"] = $res['linkerai_mj']["nickname"]?:"灵犀-MJ";
                $res['linkerai_mj']["expend"] = $res['linkerai_mj']["expend"]?:1;
                $res['linkerai_mj']["icon"] = $res['linkerai_mj']["icon"]??"";
                $res['linkerai_mj']["desc"] = $res['linkerai_mj']["desc"]??"";
                $data['paintmodel'][] = $res["linkerai_mj"];
            }
            if($res['apishop'] && $res['apishop']['status']){
                $res['apishop']["type"] = "apishop";
                $res['apishop']["nickname"] = $res['apishop']["nickname"]?:"灵犀-MJ";
                $res['apishop']["expend"] = $res['apishop']["expend"]?:1;
                $res['apishop']["icon"] = $res['apishop']["icon"]??"";
                $res['apishop']["desc"] = $res['apishop']["desc"]??"";
                $data['paintmodel'][] = $res["apishop"];
            }

        }else{
            $data['paintmodel'][] = [
                    "type" => "",
                    "nickname" => "",
                    "icon" => "",
                    "desc" => "",
                    "expend" => "1",
                    "status" => 1,
                ];
        }
       
        return success('绘画模型',$data);
    }
	/**
     * 历史记录
     */
    public function msgs()
    {
        $wid = Session::get('wid');
        $user = $this->user;
        $where = [];
        $where[] = ['wid','=',$wid];
        $where[] = ['common_id','=',$user['id']];

        $msgList = Db::table('kt_gptcms_paint_msg')->field('id,message,response,chatmodel,c_time')->where($where)->order('id asc')->select();
        $msgs = [];
        foreach ($msgList as $key => $msg) {
            $msgs[] = [
                'id' => $msg['id'],
                'role' => '我',
                'content' => $msg['message'],
                'is_mj' => 0, 
            ];
            $is_mj = 0;
            if( $msg["chatmodel"] == "linkerai_mj" && $msg["c_time"] + 5400 > time()){
                if(!in_array($msg["message"], ["U1","U2","U3","U4","V1","V2","V3","V4"])) $is_mj = 1;
            }
            if($msg["chatmodel"] == "apishop" && $msg["c_time"] + 5400 > time()){
                if(!in_array($msg["message"], ["U1","U2","U3","U4","V1","V2","V3","V4"])) $is_mj = 2;
            }

            $msgs[] = [
                'id' => $msg['id'],
                'role' => '助手',
                'content' => $msg['response'],
                'is_mj' => $is_mj, 
            ];
        }
        return success('获取成功',$msgs);
    }

    /**
     * 清除历史记录
     */
    public function delMsgs()
    {
        $wid = Session::get('wid');
        $user = $this->user;
        if(!$user) return error('用户不存在');

        Db::table('kt_gptcms_paint_msg')->where(['wid'=>$wid,'common_id'=>$user['id']])->delete();
        return success('操作成功,已删除');
    }
    /**
     * 下载
     */
    public function download()
    {
        $wid = Session::get('wid');
        $tp =  $this->req->param('tp');
        if(!$tp) return error("请输入图片地址");
        ob_start(); 
        $filename = basename($tp);
        $size=readfile($tp);
        header( "Content-type:  application/octet-stream "); 
        header( "Accept-Ranges:  bytes "); 
        header( "Content-Disposition:  attachment;  filename= {$tp}"); 
        header( "Accept-Length: " .$size);
        exit();
    }
    /**
     * getMsgReult
     */
    public function getMsgReult()
    {
        $wid = Session::get('wid');
        $msgid = $this->req->param('msgid');
        $res = Db::table('kt_gptcms_paint_msg')->find($msgid);
        if(!$res) return error('不存在');
        if($res['status'] == 0) return success("图片生成失败");
        if($res['status'] == 1) return success("图片生成中..");
        if($res['status'] == 2) return success("图片生成成功",$res['response']);
        return success("图片生成中...");
    }


    /**
     * linkeraiMjUv
     */
    public function linkeraiMjUv()
    {
        $wid = Session::get('wid');
        // $chatmodel = "linkerai_mj";
        $message = $this->req->param('message');
        $id = $this->req->param('id');
        if(!$id) return error("请选择消息id");
        $mjmsg = Db::table('kt_gptcms_paint_msg')->find($id);
        if(!$mjmsg || !$mjmsg['response']) return error("请等待图片生成后再执行放大或转换");
        if($mjmsg["c_time"] + 5400 < time()) return error("请在生成图片的90分钟内操作");
        if(!$message) return error("请选择类型");
        $chatmodel = $mjmsg["chatmodel"];
       
        $type = "";
        $index = 0;
        $position = "";
        switch ($message) {
             case 'U1':
                $type = "upscale";
                $index = "1";
                $position = "nw";
                break;
             case 'U2':
                $type = "upscale";
                $index = "2";
                $position = "ne";
                break;
             case 'U3':
                $type = "upscale";
                $index = "3";
                $position = "sw";
                break;
             case 'U4':
                $type = "upscale";
                $index = "4";
                $position = "se";
                break;
             case 'V1':
                $type = "variation";
                $index = "1";
                break;
             case 'V2':
                $type = "variation";
                $index = "2";
                break;
             case 'V3':
                $type = "variation";
                $index = "3";
                break;
             case 'V4':
                $type = "variation";
                $index = "4";
                break;
            default:
                return error("请输入合规的类型");
                break;
        }
        $user = $this->user;
        if($user['status'] != 1){
            $this->outError('账号因异常行为进入风控，请联系客服解除风控！error:005');
        }
        $vip = 0;
        if(strtotime($user['vip_expire']) > time()){ //会员未到期
            $vip = 1;
        }
        $lxmj_charging = Db::table('kt_gptcms_system')->where(['wid'=>$wid])->value('lxmj_charging')??0;
        if($lxmj_charging)  $vip = 0;//如果开启灵犀-MJ单独计费,不能使用vip
        $this->vip = $vip;
        if(!$vip){
            $this->expend = $expend = CommonModel::getExpend('paint',$chatmodel);//获取消耗条数
            if($user['residue_degree'] < $expend){ //余数不足
                $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
                return error($zdz_remind?:'剩余条数不足');
            }
        }
        $msgId = Db::table('kt_gptcms_paint_msg')->insertGetId([
                        'wid' => $wid,
                        'common_id' => $this->user['id'],
                        'un_message' => $message,
                        'message' => $message,
                        // 'un_response' => '',
                        // 'response' => '',
                        'total_tokens' => mb_strlen($message),
                        'chatmodel' => $chatmodel,
                        'sync_status' => 0,
                        'c_time' => time(),
                        'u_time' => time(),
                    ]);

        switch ($chatmodel) {
            case 'linkerai_mj':
                $taskid = Db::table('kt_gptcms_paintmsg_notify')->where("msgid",$id)->value("task_id");
                $config = Db::table('kt_gptcms_gpt_config')->json(['linkerai'])->where('wid',$wid)->find();
                $aiconfig = $config['linkerai'];
                $ktadmin = new \Ktadmin\LinkerAi\Ktadmin(['channel'=>7,'api_key'=>$aiconfig['api_key']]);
                $callback_url = $this->req->domain()."/gptcms/api/paintnotify/linkeraimj";
                $res = $ktadmin->images()->uv($taskid,$type,$index,$callback_url);
                if($res && is_array($res) && $res['code'] == 1){
                     Db::table('kt_gptcms_paintmsg_notify')->insert([
                                        'wid' => $wid,
                                        'task_id' => $res['result'],
                                        'chatmodel' => 'linkerai_mj',
                                        'msgid' => $msgId,
                                        'c_time' => date("Y-m-d H:i:s"),
                                    ]);
                    $this->updateExpend();
                    return success("图片生成中",['msgid'=>$msgId]);
                } 
                        break;
            case 'apishop':
                if(!in_array($message, ["U1","U2","U3","U4"])) return error("此渠道不支持放大以外的操作");
                $taskid = Db::table('kt_gptcms_paintapishopmsg_notify')->where("msgid",$id)->value("task_id");
                $config = Db::table('kt_gptcms_gptpaint_config')->json(['apishop'])->where('wid',$wid)->find();
                $apishopconfig = $config["apishop"];
                if(!$apishopconfig || !$apishopconfig["appkey"] || !$apishopconfig["appsecret"]) return '';
                if($apishopconfig["model"] == "mj_c2"){
                        $apiurl = '';
                        $data = [
                            "pcurl" => $mjmsg["un_response"]
                        ];
                        $data["position"] = $position;
                        $data["notifyhook"] = $this->req->domain()."/gptcms/api/paintnotify/apishopmjc2";
                        $apiurl = "/apishop/api/c2paint/uv";
                        $res = (new ApishopModel($apishopconfig["appkey"],$apishopconfig["appsecret"]))->create($apiurl,$data);
                        if($res && isset($res["status"]) && $res["status"] == "success"){
                            $task_id = $res["data"]["task_id"];
                            Db::table('kt_gptcms_paintapishopmsg_notify')->insert([
                                                'wid' => $wid,
                                                'task_id' => $task_id,
                                                'chatmodel' => 'apishop',
                                                'msgid' => $msgId,
                                                'c_time' => date("Y-m-d H:i:s"),
                                            ]);
                            $this->updateExpend();
                            return success("图片生成中",['msgid'=>$msgId]);
                        }
                }else{
                    $apiurl = '';
                        $data = [
                            "task_id" => $taskid,
                        ];

                        $data["prompt"] = $message;
                        $data["notifyhook"] = $this->req->domain()."/gptcms/api/paintnotify/apishopmjc1";
                        $apiurl = "/apishop/api/c1paint/uv";
                        $res = (new ApishopModel($apishopconfig["appkey"],$apishopconfig["appsecret"]))->create($apiurl,$data);
                        if($res && isset($res["status"]) && $res["status"] == "success"){
                            $task_id = $res["data"]["task_id"];
                            Db::table('kt_gptcms_paintapishopmsg_notify')->insert([
                                                'wid' => $wid,
                                                'task_id' => $task_id,
                                                'chatmodel' => 'apishop',
                                                'msgid' => $msgId,
                                                'c_time' => date("Y-m-d H:i:s"),
                                            ]);
                            $this->updateExpend();
                            return success("图片生成中",['msgid'=>$msgId]);
                        }
                }
                break;
        }

       
        
        Db::table('kt_gptcms_paint_msg')->where("wid",$wid)->where("common_id",$this->user['id'])->delete($msgId);
        return error("生成图片失败");
    }
    /**
     * send
     */
    public function send()
    {
    	$wid = Session::get('wid');
        $user = $this->user;
        if($user['status'] != 1){
            $this->outError('账号因异常行为进入风控，请联系客服解除风控！error:005.1');
        }
        $chatmodel = $this->req->param('chatmodel');
        if(!$chatmodel){
            //渠道 1意间AI   2 Replicate-MJ   3. gpt35  4. api2d35  5.灵犀星火 
            $config['channel'] = Db::table('kt_gptcms_gptpaint_config')->where('wid',$wid)->value('channel');
            switch ($config['channel']) {
                case 1:
                    $chatmodel = 'yjai';
                    break;

                case 2:
                    $chatmodel = 'replicate';
                    break;

                case 3:
                    $chatmodel = 'gpt35';
                    break;
                case 4:
                    $chatmodel = 'api2d35';
                    break;
                case 5:
                    $chatmodel = 'sd';
                    break;
                case 6:
                    $chatmodel = 'linkerai_mj';
                    break;
                case 7:
                    $chatmodel = 'apishop';
                    break;
                default:
                    $chatmodel = 'sd';
                    break;
            }
        }
        
        $vip = 0;
        // if(strtotime($user['vip_expire']) > time()){ //会员未到期
        //     $vip = 1;
        // }else{ //会员到期
        //     $this->expend = $expend = CommonModel::getExpend('paint',$chatmodel);//获取消耗条数

        //     if($user['residue_degree'] < $expend){ //余数不足
        //         $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
        //         return error($zdz_remind?:'剩余条数不足');
        //     }
        // }
        if(strtotime($user['vip_expire']) > time()){ //会员未到期
            $vip = 1;
        }
        if($chatmodel == 'linkerai_mj'){
            $lxmj_charging = Db::table('kt_gptcms_system')->where(['wid'=>$wid])->value('lxmj_charging')??0;
            if($lxmj_charging){ //如果开启灵犀-MJ单独计费,不能使用vip
                $vip = 0;
            }
        }
        if(!$vip){
            $this->expend = $expend = CommonModel::getExpend('paint',$chatmodel);//获取消耗条数
            if($user['residue_degree'] < $expend){ //余数不足
                $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
                return error($zdz_remind?:'剩余条数不足');
            }
        }
        $this->vip = $vip;
        $message = $this->req->param('message');
        if(!$message){
            return error('请输入您的描述');
        }
        $msgId = Db::table('kt_gptcms_paint_msg')->insertGetId([
                        'wid' => $wid,
                        'common_id' => $this->user['id'],
                        'un_message' => $message,
                        'message' => $message,
                        // 'un_response' => '',
                        // 'response' => '',
                        'total_tokens' => mb_strlen($message),
                        'chatmodel' => $chatmodel,
                        'sync_status' => 0,
                        'c_time' => time(),
                        'u_time' => time(),
                    ]);
        $this->updateExpend();

        switch ($chatmodel) {
            case 'sd':
                // return $this->linerAi('sd',$message);
                break;
            case 'yjai':
                $res = $this->paintingTask('yjai',$message,$msgId);
                if(!$res){
                    Db::table('kt_gptcms_paint_msg')->where('id',$msgId)->update([
                        'status' => 0,
                        'u_time' => time()
                    ]);
                    return error("图片生成失败");
                }
                break;
            case 'replicate':
                $res = $this->repliCateTask('replicate',$message,$msgId);
                if(!$res){
                    Db::table('kt_gptcms_paint_msg')->where('id',$msgId)->update([
                        'status' => 0,
                        'u_time' => time()
                    ]);
                    return error("图片生成失败");
                }

                break;
            case 'gpt35':
                // return $this->gpt35('gpt35',$message);
                break;
            case 'api2d35':
                // return $this->api2d35('api2d35',$message);
                break;
            case 'linkerai_mj':
                $res = $this->linkeraiMjTask('linkerai_mj',$message,$msgId);
                if(!$res){
                    Db::table('kt_gptcms_paint_msg')->where('id',$msgId)->update([
                        'status' => 0,
                        'u_time' => time()
                    ]);
                    return error("图片生成失败");
                }
                break;
            case 'apishop':
                $res = $this->apishopTask($message,$msgId);
                if(!$res){
                    Db::table('kt_gptcms_paint_msg')->where('id',$msgId)->update([
                        'status' => 0,
                        'u_time' => time()
                    ]);
                    return error("图片生成失败");
                }
                break;

        }

        return success("图片生成中",['msgid'=>$msgId]);
    }

    //apishop
    private function apishopTask($message,$msgId)
    {
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_gptpaint_config')->json(['apishop'])->where('wid',$wid)->find();
        $apishopconfig = $config["apishop"];
        if(!$apishopconfig || !$apishopconfig["appkey"] || !$apishopconfig["appsecret"]) return '';
        $apiurl = '';
        $data = [
            "prompt" => $message,
            "imageurl" => "",
        ];
        switch ($apishopconfig["model"]) {
            case 'mj_c1_fast':
                $data["mode"] = "fast";
                $data["notifyhook"] = $this->req->domain()."/gptcms/api/paintnotify/apishopmjc1";
                $apiurl = "/apishop/api/c1paint/send";
                break;
            case 'mj_c1_mix':
                $data["mode"] = "mix";
                $data["notifyhook"] = $this->req->domain()."/gptcms/api/paintnotify/apishopmjc1";
                $apiurl = "/apishop/api/c1paint/send";
                break;
            case 'mj_c1_relax':
                $data["mode"] = "relax";
                $data["notifyhook"] = $this->req->domain()."/gptcms/api/paintnotify/apishopmjc1";
                $apiurl = "/apishop/api/c1paint/send";
                break;
            case 'mj_c2':
                $data["mode"] = "v 5.2";
                $data["notifyhook"] = $this->req->domain()."/gptcms/api/paintnotify/apishopmjc2";
                $apiurl = "/apishop/api/c2paint/send";
                break; 
        }
        $res = (new ApishopModel($apishopconfig["appkey"],$apishopconfig["appsecret"]))->create($apiurl,$data);
        if($res && isset($res["status"]) && $res["status"] == "success"){
            $task_id = $res["data"]["task_id"];
            Db::table('kt_gptcms_paintapishopmsg_notify')->insert([
                                'wid' => $wid,
                                'task_id' => $task_id,
                                'chatmodel' => 'apishop',
                                'msgid' => $msgId,
                                'c_time' => date("Y-m-d H:i:s"),
                            ]);
            return $task_id;
        }
        return '';
    }
    private function linkeraiMjTask($type,$message,$msgId,$mode="v 5.1")
    {
        $wid = Session::get('wid');
        $message .= " --".$mode; 
        $config = Db::table('kt_gptcms_gpt_config')->json(['linkerai'])->where('wid',$wid)->find();
        if(!$config )  return '';
        $aiconfig = $config['linkerai'];
        $ktadmin = new \Ktadmin\LinkerAi\Ktadmin(['channel'=>7,'api_key'=>$aiconfig['api_key']]);
        $callback_url = $this->req->domain()."/gptcms/api/paintnotify/linkeraimj";
        $res = $ktadmin->images()->send($message,$callback_url);
        if($res && is_array($res) && $res['code'] == 1){
             Db::table('kt_gptcms_paintmsg_notify')->insert([
                                'wid' => $wid,
                                'task_id' => $res['result'],
                                'chatmodel' => 'linkerai_mj',
                                'msgid' => $msgId,
                                'c_time' => date("Y-m-d H:i:s"),
                            ]);
            return $res['result'];
        } 
        return '';
    }
    /*
    * repliCate
    */
    public function repliCateTask($type,$message,$msgId){
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_gptpaint_config')->json(['replicate'])->where('wid',$wid)->find();
        if(!$config )  return '';
        $token = $config["replicate"]["token"];
        $version = "9936c2001faa2194a261c01381f90e65261879985476014a0a37a334593a05eb";
        $replicate = new \Ktadmin\Replicate($token,$version);
        $callback_url = $this->req->domain()."/gptcms/api/paintnotify/replicate";
        $res = $replicate->draw($message,$callback_url);
        if (!empty($res['detail'])) return '';
        if (isset($res['error']) && $res['error'] == 1) return '';
        if (!isset($res['id'])) return '';
        Db::table('kt_gptcms_paintmsg_notify')->insert([
                                'wid' => $wid,
                                'task_id' => $res['id'],
                                'chatmodel' => 'replicate',
                                'msgid' => $msgId,
                                'c_time' => date("Y-m-d H:i:s"),
                            ]);
        return $res['id'];
        // $url = $replicate->queryDrawResult($res['id']);
        // $this->updateExpend();
        // Db::table('kt_gptcms_paint_msg')->insert([
        //                 'wid' => $wid,
        //                 'common_id' => $this->user['id'],
        //                 'un_message' => $message,
        //                 'message' => $message,
        //                 'un_response' => $url["output"][0],
        //                 'response' => $url["output"][0],
        //                 'total_tokens' => mb_strlen($message),
        //                 'c_time' => time()
        //             ]);
        // return success("获取成功",$url["output"][0]);
    }

    /*
    * 意见ai
    */
    private function paintingTask($type,$message,$msgId){
        $wid = Session::get('wid');
        $time = time();
        $config = Db::table('kt_gptcms_gptpaint_config')->json(['yjai'])->where('wid',$wid)->find();
        if(!$config )  return '';
        $key = $config["yjai"]["api_key"];
        $secret = $config["yjai"]["api_secret"];
        $painting = new \Ktadmin\Painting($key,$secret,$time);
        $ratio = 0;
        $style = "";
        $callback_url = $this->req->domain()."/gptcms/api/paintnotify/yjai";
        $guidence_scale = 10;
        $engine = "stable_diffusion";
        $callback_type = "progress";
        $enable_face_enhance = false;
        $is_last_layer_skip = false;
        $init_image = "";
        $init_strength = 50;
        $res = $painting->put_task($message,$ratio,$style,$guidence_scale,$engine,$callback_url,$callback_type,$enable_face_enhance,$is_last_layer_skip,$init_image,$init_strength);
        if(!isset($res["data"]["Uuid"])) return '';
        Db::table('kt_gptcms_paintmsg_notify')->insert([
                                'wid' => $wid,
                                'task_id' => $res["data"]["Uuid"],
                                'chatmodel' => 'yjai',
                                'msgid' => $msgId,
                                'c_time' => date("Y-m-d H:i:s"),
                            ]);
        return $res["data"]["Uuid"];

        // while(1){
        //     $show = $painting->show_task($res["data"]["Uuid"]);
        //     if(!empty($show["data"]["ImagePath"])) break;
        // }
        // $this->updateExpend();
        // Db::table('kt_gptcms_paint_msg')->insert([
        //                 'wid' => $wid,
        //                 'common_id' => $this->user['id'],
        //                 'un_message' => $message,
        //                 'message' => $message,
        //                 'un_response' => $show["data"]["ImagePath"],
        //                 'response' => $show["data"]["ImagePath"],
        //                 'total_tokens' => mb_strlen($message),
        //                 'c_time' => time()
        //             ]);
        // return success("获取成功",$show["data"]["ImagePath"]);
    }

    private function updateExpend()
    {
        if(!$this->vip && $this->expend){
            Db::table("kt_gptcms_common_user")->where("id",$this->user["id"])->update([
                "residue_degree" => $this->user['residue_degree'] - $this->expend
            ]);
        }   
        return "ok";
    }
    private function gpt35($type,$message)
    {
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_gpt_config')->json(['openai'])->where('wid',$wid)->find();
        if(!$config )  return error('未检查到配置信息');
        $agid = Db::table('kt_base_user')->where('id',$wid)->value('agid');
        $base_config = Db::table('kt_base_gpt_config')->json(['openai'])->where('uid',$agid)->find();
        $base_aiconfig = $base_config['openai'];
        $aiconfig = $config['openai'];
        $diy_host = $aiconfig['diy_host']?:$base_aiconfig['diy_host'];
        $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>1,'api_key'=>$aiconfig['api_key'],'diy_host'=>$diy_host]);
        $res = $ktadmin->Images()->create($message,1);
        if($res && isset($res['data']) && count($res['data'])){
            $this->updateExpend();
            Db::table('kt_gptcms_paint_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $this->user['id'],
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $res["data"][0]['url'],
                        'response' => $res["data"][0]['url'],
                        'total_tokens' => mb_strlen($message),
                        'c_time' => time()
                    ]);
            return success("获取成功",$res["data"][0]['url']);
        }
        return error("生成失败");
    }
    private function api2d35($type,$message)
    {
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_gpt_config')->json(['api2d'])->where('wid',$wid)->find();
        if(!$config)  return error('未检查到配置信息');
        $aiconfig = $config['api2d'];
        $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>2,'api_key'=>$aiconfig['forward_key'],'diy_host'=>'']);
        $res = $ktadmin->Images()->create($message,1);
        if($res && isset($res['data']) && count($res['data'])){
            $this->updateExpend();
            Db::table('kt_gptcms_paint_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $this->user['id'],
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $res["data"][0]['url'],
                        'response' => $res["data"][0]['url'],
                        'total_tokens' => mb_strlen($message),
                        'c_time' => time()
                    ]);
            return success("获取成功",$res["data"][0]['url']);
        }
        return error("生成失败");
    }
    /*
    * repliCate
    */
    public function repliCate($type,$message){
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_gptpaint_config')->json(['replicate'])->where('wid',$wid)->find();
        if(!$config )  return error('未检查到配置信息');
        $token = $config["replicate"]["token"];
        $version = "9936c2001faa2194a261c01381f90e65261879985476014a0a37a334593a05eb";

        $replicate = new \Ktadmin\Replicate($token,$version);
        $res = $replicate->draw($message);
        if (!empty($res['detail']))return error("失败".$res['detail']);
        if (isset($res['error']) && $res['error'] == 1)return error("失败".$res['message']);
        if (!isset($res['id']))return error("任务提交失败，请重试");
        $url = $replicate->queryDrawResult($res['id']);
        $this->updateExpend();
        Db::table('kt_gptcms_paint_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $this->user['id'],
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $url["output"][0],
                        'response' => $url["output"][0],
                        'total_tokens' => mb_strlen($message),
                        'c_time' => time()
                    ]);
        return success("获取成功",$url["output"][0]);
    }

    /*
    * 意见ai
    */
    private function painting($type,$message){
        $wid = Session::get('wid');
        $time = time();
        $config = Db::table('kt_gptcms_gptpaint_config')->json(['yjai'])->where('wid',$wid)->find();
        if(!$config )  return error('未检查到配置信息');
        $key = $config["yjai"]["api_key"];
        $secret = $config["yjai"]["api_secret"];
        $painting = new \Ktadmin\Painting($key,$secret,$time);
        $ratio = 0;
        $style = "";
        $callback_url = "";
        $guidence_scale = 10;
        $engine = "stable_diffusion";
        $callback_type = "progress";
        $enable_face_enhance = false;
        $is_last_layer_skip = false;
        $init_image = "";
        $init_strength = 50;
        $res = $painting->put_task($message,$ratio,$style,$guidence_scale,$engine,$callback_url,$callback_type,$enable_face_enhance,$is_last_layer_skip,$init_image,$init_strength);
        if(!isset($res["data"]["Uuid"])) return error("生成失败");
        while(1){
            $show = $painting->show_task($res["data"]["Uuid"]);
            if(!empty($show["data"]["ImagePath"])) break;
        }
        $this->updateExpend();
        Db::table('kt_gptcms_paint_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $this->user['id'],
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $show["data"]["ImagePath"],
                        'response' => $show["data"]["ImagePath"],
                        'total_tokens' => mb_strlen($message),
                        'c_time' => time()
                    ]);
        return success("获取成功",$show["data"]["ImagePath"]);
    }


    private function linerAi($type,$message)
    {
        $wid = Session::get('wid');
        $config = Db::table('kt_gptcms_gpt_config')->json(['linkerai'])->where('wid',$wid)->find();
        if(!$config )  return error('未检查到配置信息');
        $aiconfig = $config['linkerai'];
        $ktadmin = new \Ktadmin\LinkerAi\Ktadmin(['channel'=>7,'api_key'=>$aiconfig['api_key']]);
        $res = $ktadmin->chat()->sendImageSd($message);
        if($res && is_array($res) && isset($res['task_id'])){
            Db::table('kt_gptcms_paint_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $this->user['id'],
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $res['task_id'],
                        'response' => $res['task_id'],
                        'total_tokens' => mb_strlen($message),
                        'c_time' => time()
                    ]);
            $this->updateExpend();
            return success("获取成功",$res["task_id"]);
        } 
        return error("生成失败");
    }

}
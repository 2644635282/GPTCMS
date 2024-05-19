<?php 
namespace app\gptcms\controller\job;

use app\BaseController;
use think\facade\Db;
use think\facade\Log;
use think\queue\Job;
use app\gptcms\model\MediaModel;


/**
  * 任务类
  */
 class Syncpaint extends BaseController
 {
   public $wid;
   public $msgid;
    /**
     * fire方法是消息队列默认调用的方法
     * @param Job $job 当前的任务对象
     * @param array $data 发布任务时自定义的数据
     */
    public function fire(Job $job, array $data)
    {
      
        $isJobDone = $this->doHelloJob($data);
        if ($isJobDone){
            $job->delete();
          Log::info("执行完毕,删除任务" . $job->attempts() . '\n');
        }else{
            if ($job->attempts() > 3){
                $job->delete();
                Log::info("超时任务删除" . $job->attempts() . '\n');
            }
        }
    
    }

    private function doHelloJob(array $data)
    {
      return true;
    }
    public function index(){
      $msg = Db::table("kt_gptcms_paint_msg")->where('chatmodel','in',["sd","gpt35","api2d35"])->where('sync_status',0)->where('status',1)->whereNull('response')->find();
      if(!$msg) return 'ok';
      $this->wid = $msg['wid'];
      $this->msgid = $msg['id'];
      Db::table("kt_gptcms_paint_msg")->where('id',$msg['id'])->update([
        'sync_status' => 1,
        'u_time' => time()
      ]);
      switch ($msg['chatmodel']) {
            case 'sd':
                $res = $this->linerAi('sd',$msg['message']);
                break;
            // case 'yjai':
            //     return $this->painting('yjai',$msg['message']);
            //     break;
            // case 'replicate':
            //     return $this->repliCate('replicate',$msg['message']);
                // break;
            case 'gpt35':
                $res = $this->gpt35('gpt35',$msg['message']);
                break;
            case 'api2d35':
                $res = $this->api2d35('api2d35',$msg['message']);
                break;
        }

      return 'ok';
    }

    private function gpt35($type,$message)
    {
        $wid = $this->wid;
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
            $img = MediaModel::uploadPaint($wid,$res["data"][0]['url'],'gpt35');
            Db::table('kt_gptcms_paint_msg')->where('id',$this->msgid)->update([
                        'un_response' => $res["data"][0]['url'],
                        'response' => $img['img'],
                        'status' => 2,
                        'u_time' => time()
                    ]);
            return 'ok';
        }
        Db::table('kt_gptcms_paint_msg')->where('id',$this->msgid)->update([
                        'status' => 1,
                        'u_time' => time()
                    ]);
        return 'error';
    }
    private function api2d35($type,$message)
    {
        $wid = $this->wid;
        $config = Db::table('kt_gptcms_gpt_config')->json(['api2d'])->where('wid',$wid)->find();
        if(!$config)  return error('未检查到配置信息');
        $aiconfig = $config['api2d'];
        $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>2,'api_key'=>$aiconfig['forward_key'],'diy_host'=>'']);
        $res = $ktadmin->Images()->create($message,1);
        if($res && isset($res['data']) && count($res['data'])){
            $img = MediaModel::uploadPaint($wid,$res["data"][0]['url'],'api2d35');
            Db::table('kt_gptcms_paint_msg')->where('id',$this->msgid)->update([
                        'un_response' => $res["data"][0]['url'],
                        'response' => $img['img'],
                        'status' => 2,
                        'u_time' => time()
                    ]);
            return 'ok';
        }
        Db::table('kt_gptcms_paint_msg')->where('id',$this->msgid)->update([
                        'status' => 1,
                        'u_time' => time()
                    ]);
        return 'error';
    }

    private function linerAi($type,$message)
    {
        $wid = $this->wid;
        $config = Db::table('kt_gptcms_gpt_config')->json(['linkerai'])->where('wid',$wid)->find();
        if(!$config )  return error('未检查到配置信息');
        $aiconfig = $config['linkerai'];
        $ktadmin = new \Ktadmin\LinkerAi\Ktadmin(['channel'=>7,'api_key'=>$aiconfig['api_key']]);
        $res = $ktadmin->chat()->sendImageSd($message);
        if($res && is_array($res) && isset($res['task_id'])){
            // $img = MediaModel::uploadPaint($wid,$res["task_id"],'linerAi');
            Db::table('kt_gptcms_paint_msg')->where('id',$this->msgid)->update([
                        'un_response' => $res['task_id'],
                        // 'response' => $img['img'],
                        'response' => $res['task_id'],
                        'status' => 2,
                        'u_time' => time()
                    ]);
            return 'ok';
        } 
        Db::table('kt_gptcms_paint_msg')->where('id',$this->msgid)->update([
                        'status' => 1,
                        'u_time' => time()
                    ]);
        return 'error';
    }

 }  

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
 class Syncmjpaint extends BaseController
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

      $syncpaint = Db::table("kt_gptcms_syncpaint")->where('is_sync',0)->find();
      if(!$syncpaint) return 'ok';
      Db::table("kt_gptcms_syncpaint")->where('id',$syncpaint['id'])->update([
        'is_sync' => 1,
        'u_time' => date("Y-m-d H:i:s")
      ]);
      $syncmj = Db::table("kt_gptcms_gptpaint_config")->where('wid',$syncpaint['wid'])->value("syncmj") ?: 0;
      if(!$syncmj) return 'ok';
      $img = [];
      if($syncpaint['mj_url']) $img = MediaModel::uploadPaint($syncpaint['wid'],$syncpaint['mj_url'],'linerAi');
      $local_url = $img['img'] ?? '';
      Db::table("kt_gptcms_syncpaint")->where('id',$syncpaint['id'])->update([
        'local_url' => $local_url,
        'u_time' => date("Y-m-d H:i:s")
      ]);
      switch ($syncpaint['source_type']) {
            case 1:
                Db::table("kt_gptcms_paint_msg")->where('id',$syncpaint['msg_id'])->update([
                    'response' => $local_url,
                    'u_time' => time()
                  ]);
                break;
            case 2:
                  Db::table("kt_gptcms_draw_msgtp")->where('msg_id',$syncpaint['msg_id'])->update([
                    'image' => $local_url,
                    'u_time' => date("Y-m-d H:i:s")
                  ]);
                 Db::table("kt_gptcms_draw_msg")->where('id',$syncpaint['msg_id'])->update([
                    'response' => $local_url,
                    'u_time' => time()
                  ]);
                break;
        }

      return 'ok';
    }

 }  

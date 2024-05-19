<?php
declare (strict_types = 1);

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Session;
use think\facade\Log;
use app\gptcms\model\MediaModel;

class PaintNotify extends BaseApi
{
	public function apishopmjc1()
	{
		$task_id = $this->req->param('task_id');
		if(!$task_id) die("ERROR");
		$ImagePath = $this->req->param('imageUrl');
		$notify = Db::table('kt_gptcms_paintapishopmsg_notify')->where('task_id',$task_id)->where('status',0)->order("c_time","asc")->find();
		if(!$notify) die;
		Db::table('kt_gptcms_paintapishopmsg_notify')->where('id',$notify['id'])->update([
			'status' => 1,
			'u_time' => date("Y-m-d H:i:s"),
		]);
		$wid = $notify['wid'];	
		// $img = MediaModel::uploadPaint($wid,$ImagePath,'linkerai_mj');
		Db::table('kt_gptcms_paint_msg')->where('id',$notify['msgid'])->update([
                        'un_response' => $ImagePath,
                        // 'response' => $img['img'],
                        'response' => $ImagePath,
                        'status' => 2,
                        'u_time' => time()
                    ]);
		echo "SUCCESS";
		die;
	}
	public function apishopmjc2()
	{
		$task_id = $this->req->param('task_id');
		if(!$task_id) die("ERROR");
		$ImagePath = $this->req->param('imageUrl');
		$notify = Db::table('kt_gptcms_paintapishopmsg_notify')->where('task_id',$task_id)->where('status',0)->order("c_time","asc")->find();
		if(!$notify) die;
		Db::table('kt_gptcms_paintapishopmsg_notify')->where('id',$notify['id'])->update([
			'status' => 1,
			'u_time' => date("Y-m-d H:i:s"),
		]);
		$wid = $notify['wid'];	
		// $img = MediaModel::uploadPaint($wid,$ImagePath,'linkerai_mj');
		Db::table('kt_gptcms_paint_msg')->where('id',$notify['msgid'])->update([
                        'un_response' => $ImagePath,
                        // 'response' => $img['img'],
                        'response' => $ImagePath,
                        'status' => 2,
                        'u_time' => time()
                    ]);
		echo "SUCCESS";
		die;
	}
	public function linkeraimj()
	{
		$id = $this->req->param('id');
		$status = $this->req->param('status');
		if(!$id) die("ERROR");
		if($status != "SUCCESS") die("执行中");
		$ImagePath = $this->req->param('imageUrl');
		$notify = Db::table('kt_gptcms_paintmsg_notify')->where('task_id',$id)->where('status',0)->order("c_time","asc")->find();
		if(!$notify) die;
		Db::table('kt_gptcms_paintmsg_notify')->where('id',$notify['id'])->update([
			'status' => 1,
			'u_time' => date("Y-m-d H:i:s"),
		]);
		$wid = $notify['wid'];	
		// $img = MediaModel::uploadPaint($wid,$ImagePath,'linkerai_mj');
		Db::table('kt_gptcms_paint_msg')->where('id',$notify['msgid'])->update([
                        'un_response' => $ImagePath,
                        // 'response' => $img['img'],
                        'response' => $ImagePath,
                        'status' => 2,
                        'u_time' => time()
                    ]);
		Db::table('kt_gptcms_syncpaint')->insertGetId([
            "wid"=>$wid,
            "msg_id"=>$notify['msgid'],
            "source_type" => 1,
            "mj_url" => $ImagePath,
            "c_time" =>  date("Y-m-d H:i:s"),
            "u_time" =>  date("Y-m-d H:i:s"),
        ]);
		echo "SUCCESS";
		die;
	}
	public function yjai()
	{
		$task = json_decode($this->req->param('task'),true);
		if(!$task) die("SUCCESS");
		$Uuid = $task['Uuid'] ?? '';
		$ImagePath = $task['ImagePath'] ?? '';
		if(!$Uuid || !$ImagePath) die("SUCCESS");
		$notify = Db::table('kt_gptcms_paintmsg_notify')->where('task_id',$Uuid)->where('status',0)->find();
		if(!$notify) die("SUCCESS");
		Db::table('kt_gptcms_paintmsg_notify')->where('id',$notify['id'])->update([
			'status' => 1,
			'u_time' => date("Y-m-d H:i:s"),
		]);
		$wid = $notify['wid'];	
		// $img = MediaModel::uploadPaint($wid,$ImagePath,'yjai');
		Db::table('kt_gptcms_paint_msg')->where('id',$notify['msgid'])->update([
                        'un_response' => $ImagePath,
                        // 'response' => $img['img'],
                        'response' => $ImagePath,
                        'status' => 2,
                        'u_time' => time()
                    ]);
		echo "SUCCESS";
		die;
	}
	public function repliCate()
	{
		$id = $this->req->param('id');
		$output = $this->req->param('output');
		if(!$id || !$output) ;
		$ImagePath = $output[0];
		$notify = Db::table('kt_gptcms_paintmsg_notify')->where('task_id',$id)->where('status',0)->find();
		if(!$notify) die("ERROR");
		Db::table('kt_gptcms_paintmsg_notify')->where('id',$notify['id'])->update([
			'status' => 1,
			'u_time' => date("Y-m-d H:i:s"),
		]);
		$wid = $notify['wid'];	
		// $img = MediaModel::uploadPaint($wid,$ImagePath,'replicate');
		Db::table('kt_gptcms_paint_msg')->where('id',$notify['msgid'])->update([
                        'un_response' => $ImagePath,
                        // 'response' => $img['img'],
                        'response' => $ImagePath,
                        'status' => 2,
                        'u_time' => time()
                    ]);
		echo "SUCCESS";
		die;
	}
}



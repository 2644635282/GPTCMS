<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin;

use think\facade\Db;
use think\facade\Session;
use app\BaseController;
use think\facade\Queue;

/**
 * 统一定时任务类
 */
class Task extends BaseController
{
	/*
	*入口
	*/
	public function index()
	{
		$tasks = Db::table('kt_base_timed_task')->where('status',1)->select();
		if($tasks->isEmpty()) return 'ok';
		foreach ($tasks as  $task) {
			if($task['last_time'] + $task['interval_time'] > time()){
				$jobHandlerClassName = $task['class_ame'];
				$isPushed = Queue::push($jobHandlerClassName, array('time'=>time()), 'timed_task');
				Db::table('kt_base_timed_task')->where('id',$task['id']->update([
					'last_time' => time(),
				]);
			}
		}
		return success('执行成功');
	}	

}
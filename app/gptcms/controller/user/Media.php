<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;
use app\gptcms\model\MediaModel;

class Media extends BaseUser{
    public function index(){
    	$wid = Session::get('wid');
        $info = Db::table("kt_gptcms_storage_config")->where(["wid"=>$wid])->find();

        return success('云存储配置',$info);
    }

    /*
    * 修改/保存云存储配置
    */
    public function save(){
    	$wid = Session::get('wid');
    	$data = $this->req->param();
    	if($data['type'] == 2 && (!$data['oss_id'] || !$data['oss_secret'] || !$data['oss_endpoint'] || !$data['oss_bucket'])) return error('参数错误');
        if($data['type'] == 3 && (!$data['cos_secretId'] || !$data['cos_secretKey'] || !$data['cos_bucket'] || !$data['cos_endpoint'])) return error('参数错误');
        if($data['type'] == 4 && (!$data['kodo_key'] || !$data['kodo_secret'] || !$data['kodo_domain'] || !$data['kodo_bucket'])) return error('参数错误');
        $info = Db::table("kt_gptcms_storage_config")->where(["wid"=>$wid])->find();
 		$param = [];
 		$param['update_time'] = date("Y-m-d H:i:s");
 		if(!$info) $param['create_time'] = date("Y-m-d H:i:s");
 		if($info) $param['id'] = $info['id'];
 		$param['wid'] = $wid;
 		$param['type'] = $data['type'];
 		switch ($data['type']) {
 			case '2':
 				$param['oss_id'] = $data['oss_id'];
 				$param['oss_secret'] = $data['oss_secret'];
 				$param['oss_endpoint'] = $data['oss_endpoint'];
 				$param['oss_bucket'] = $data['oss_bucket'];
 				break;
 			case '3':
 				$param['cos_secretId'] = $data['cos_secretId'];
 				$param['cos_secretKey'] = $data['cos_secretKey'];
 				$param['cos_bucket'] = $data['cos_bucket'];
 				$param['cos_endpoint'] = $data['cos_endpoint'];
 				break;
 			case '4':
 				$param['kodo_key'] = $data['kodo_key'];
 				$param['kodo_secret'] = $data['kodo_secret'];
 				$param['kodo_domain'] = $data['kodo_domain'];
 				$param['kodo_bucket'] = $data['kodo_bucket'];
 				break;
 		}
		$res = Db::table('kt_gptcms_storage_config')->save($param);

		return success("操作成功");
    }
}
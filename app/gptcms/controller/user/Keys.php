<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;

class Keys extends BaseUser
{
	public function saveswitch()
    {
        $wid = Session::get('wid');
        $p['gpt35'] = $this->req->param("gpt35",0);
        $p['gpt4'] = $this->req->param("gpt4",0);
        $p['linkerai'] = $this->req->param("linkerai",0);
        $p['api2d35'] = $this->req->param("api2d35",0);
        $p['api2d4'] = $this->req->param("api2d4",0);

        $chatmodel = ['gpt35','gpt4','linkerai','api2d35','api2d4'];
        foreach ($chatmodel as $model) {
            $config = Db::table('kt_gptcms_keys_switch')->where(['wid'=>$wid,'chatmodel'=>$model])->find();
            $data = [];
            if($config){
                $data['id'] = $config['id'];
            }
            $data['wid'] = $wid;
            $data['chatmodel'] = $model;
            $data['switch'] = $p[$model];
            $data['utime'] = time();
            Db::table('kt_gptcms_keys_switch')->save($data);
        }
        return success('操作成功');
    }

    public function getswitch()
    {
        $wid = Session::get('wid');
        $chatmodel = $this->req->param("chatmodel")?:'';
        $data = [];
        if($chatmodel){
            $config = Db::table('kt_gptcms_keys_switch')->where(['wid'=>$wid,'chatmodel'=>$chatmodel])->find();
            if(!$config){
                $id = Db::table('kt_gptcms_keys_switch')->insertGetId([
                    'wid' => $wid,
                    'chatmodel' => $chatmodel,
                    'switch' => 0,
                    'utime' => time()
                ]);
                $data[$chatmodel] = 0;
            }else{
                $data[$chatmodel] = $config['switch'];
            }
            return success('获取成功',$data);
        }

        $chatmodel = ['gpt35','gpt4','linkerai','api2d35','api2d4'];
        foreach ($chatmodel as $model) {
            $config = Db::table('kt_gptcms_keys_switch')->where(['wid'=>$wid,'chatmodel'=>$model])->find();
            if(!$config){
                $id = Db::table('kt_gptcms_keys_switch')->insertGetId([
                    'wid' => $wid,
                    'chatmodel' => $model,
                    'switch' => 0,
                    'utime' => time()
                ]);
                $data[$model] = 0;
            }else{
                $data[$model] = $config['switch'];
            }
        }
        return success('获取成功',$data);
    }

    public function list()
    {
        $wid = Session::get('wid');
        $chatmodel = $this->req->param("chatmodel");
        $page = (int)$this->req->param('page')?:1;
        $size = (int)$this->req->param('size')?:10;
        // if(!$chatmodel) return error('缺少必要参数渠道');
        $where['wid'] = $wid;
        if($chatmodel) $where['chatmodel'] = $chatmodel;
        $res = Db::table('kt_gptcms_keys')->where($where);

        $data = [];
        $data['page'] = $page;
        $data['size'] = $size;
        $data['count'] = $res->count();
        $data['item'] = $res->page($page,$size)->order('id','desc')->filter(function($r){
            $r['ctime'] = date('Y-m-d H:i:s',$r['ctime']);
            $r['utime'] = date('Y-m-d H:i:s',$r['utime']);
            switch ($r['chatmodel']) {
                case 'gpt35':
                    $r['chatmodel_name'] = 'gpt-3.5';
                    break;
                case 'gpt4':
                    $r['chatmodel_name'] = 'gpt-4';
                    break;
                case 'linkerai':
                    $r['chatmodel_name'] = '灵犀星火';
                    break;
                case 'api2d35':
                    $r['chatmodel_name'] = 'api2d-3.5';
                    break;
                case 'api2d4':
                    $r['chatmodel_name'] = 'gapi2d-4';
                    break;
            }
            return $r;
        })->select();
        return success('key池列表',$data);
    }

    public function addkey()
    {
        $wid = Session::get('wid');
        $chatmodel = $this->req->param("chatmodel");
        $key = $this->req->param("key");
        if(!$chatmodel) return error('缺少必要参数渠道');
        if(!$key) return error('缺少必要参数key');
        $res = Db::table('kt_gptcms_keys')->where(['wid'=>$wid,'chatmodel'=>$chatmodel,'key'=>$key])->find();
        if($res) return error('key已存在');
        Db::table('kt_gptcms_keys')->save([
            'wid' => $wid,
            'chatmodel' => $chatmodel,
            'key' => $key,
            'state' => 1,
            'ctime' => time(),
            'utime' => time()
        ]);
        return success('操作成功');
    }

    public function delkey()
    {
        $wid = Session::get('wid');
        $id = $this->req->param("id");
        if(!$id) return error('缺少必要参数id');
        Db::table('kt_gptcms_keys')->delete($id);
        return success('操作成功');
    }

    public function switchkey()
    {
        $wid = Session::get('wid');
        $id = $this->req->param("id");
        $state = $this->req->param("state",0);
        if(!$id) return error('缺少必要参数id');
        Db::table('kt_gptcms_keys')->where('id',$id)->update([
            'state' => $state
        ]);
        return success('操作成功');
    }
}
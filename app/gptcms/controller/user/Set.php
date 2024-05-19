<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;

use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cache;

class Set extends BaseUser
{
    public function system()
    {
    	$wid = Session::get('wid');
      	$res = Db::table('kt_gptcms_system')->field('rz_number,dz_number,dz_remind,zdz_number,zdz_remind,yq_number,welcome,sms,self_balance,gpt4_charging,lxmj_charging,logo')->where('wid',$wid)->find();
        if(!$res){
            Db::table('kt_gptcms_system')->insert([
                'wid' => $wid,
                'rz_number' => 5,
                'dz_number' => 10,
                'zdz_number' => 150,
                'zdz_remind' => "您的次数已用完，请充值!",
                'self_balance' => "条",
                'gpt4_charging' => 0,
                'lxmj_charging' => 0,
                'create_time' => date("Y-m-d H:i:s"),
                'update_time' => date("Y-m-d H:i:s"),
            ]);
            $res = Db::table('kt_gptcms_system')->field('rz_number,dz_number,dz_remind,zdz_number,zdz_remind,yq_number,welcome,sms,self_balance,gpt4_charging,lxmj_charging,logo')->where('wid',$wid)->find();
        }
      	return success('系统设置信息',$res);
    }

    public function saveSystem()
    {
    	$wid = Session::get('wid');
     	$system = Db::table('kt_gptcms_system')->where('wid',$wid)->find();
     	$data = [];
     	$data['wid'] = $wid;
     	$data['rz_number'] = $this->req->param('rz_number',0);
     	$data['dz_number'] = $this->req->param('dz_number',0);
     	$data['dz_remind'] = $this->req->param('dz_remind');
     	$data['zdz_number'] = $this->req->param('zdz_number',0);
     	$data['zdz_remind'] = $this->req->param('zdz_remind')?:"您的次数已用完，请充值!";
     	$data['yq_number'] = $this->req->param('yq_number',0);
        $data['welcome'] = $this->req->param('welcome');
        $data['sms'] = $this->req->param('sms',0);
     	$data['logo'] = $this->req->param('logo');
        $data['gpt4_charging'] = $this->req->param('gpt4_charging',0);
        $data['lxmj_charging'] = $this->req->param('lxmj_charging',0);
        $data['self_balance'] = $this->req->param('self_balance') ?: "条";
     	$data['update_time'] = date("Y-m-d H:i:s");
     	if ($system){
     		$data['id'] = $system['id'];
     	} else{
     		$data['create_time'] = date("Y-m-d H:i:s");
     	}
     	$res = Db::table('kt_gptcms_system')->save($data);
     	return success('保存成功');
    }

    /*
	* 微信支付配置
	*/
	public function wxpay(){
		$wid = Session::get('wid');
		$wxpay = [];
		$config = Db::table('kt_gptcms_pay_config')->where(['wid'=>$wid,'type'=>'wx'])->find();
        if($config) $wxpay  = explode(',',$config['config']);
		$data =[
            'appid' => isset($wxpay[0]) ? $wxpay[0] : null,
            'mch_id' => isset($wxpay[1]) ? $wxpay[1] : null,
            'key' => isset($wxpay[2]) ? $wxpay[2] : null,
            'appsecret' => isset($wxpay[3]) ? $wxpay[3] : null,
        ];
		return success("获取成功",$data);
	}

	/*
	* 微信支付配置 保存
	*/
	public function saveWxpay(){
		$wid = Session::get('wid');
		$appid = $this->req->param("appid");
		$mch_id = $this->req->param("mch_id");
		$key = $this->req->param("key");
		$appsecret = $this->req->param("appsecret");
		if(!$mch_id || !$key)return error("缺少参数");
        $config = Db::table('kt_gptcms_pay_config')->where(['wid'=>$wid,'type'=>'wx'])->find();
        $data['config'] = $appid.",".$mch_id.",".$key.",".$appsecret;
        if(!$config){
        	$data["type"] = "wx";
        	$data["wid"] = $wid;
   			$res = Db::table('kt_gptcms_pay_config')->save($data);
        }else{
        	$res = Db::table('kt_gptcms_pay_config')->where('id',$config['id'])->update($data);
        }
		return success("保存成功");
	}

	/**
    * Baidu AI配置  获取
    */
    public function BaiduAi()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_baiduai_config")->field("appid,apikey,secretkey")->where('wid',$wid)->find();
        return success('百度AI配置',$res);
    }

    /**
    * Baidu AI配置  保存
    */
    public function BaiduAiSet()
    {
        $wid = Session::get('wid');
        $data = [];
        $data['appid'] = $this->req->param('appid');
        if(!$data['appid']) return error('请输入Appid');
        $data['apikey'] = $this->req->param('apikey');
        if(!$data['apikey']) return error('请输入Key');
        $data['secretkey'] = $this->req->param('secretkey');
        if(!$data['secretkey']) return error('请输入Secret');
        $is = Db::table("kt_gptcms_baiduai_config")->where('wid',$wid)->find();
        if($is){
            $data['id'] = $is['id'];
        }
        $data['wid'] = $wid;
        $res = Db::table("kt_gptcms_baiduai_config")->save($data);
        return success("保存成功");
        
    }
    /**
    * Aliyun 语音合成配置  获取
    */
    public function Aliyun()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_aliai_config")->field("accesskey_id,accesskey_secret,region,appkey,status,type")->where('wid',$wid)->find();
        $res["tts"] = Db::table('kt_gptcms_tts_config')->field("appid,secret,key")->where('wid',$wid)->find();

        return success('阿里云AI配置',$res);
    }

    /**
    * Aliyun 语音合成配置  保存
    * type 1为阿里，2为讯飞
    */
    public function AliyunSet()
    {
        $wid = Session::get('wid');
        $data = [];
        $type = $this->req->param('type')?:1;
        $set = Db::table("kt_gptcms_aliai_config")->where('wid',$wid)->find();
        if($set)$save["id"] = $set["id"];
        $save["type"] = $type;
        Db::table("kt_gptcms_aliai_config")->save($save);
        if($type == 1){
            $data['region'] = $this->req->param('region');
            $data['accesskey_id'] = $this->req->param('accesskey_id');
            if(!$data['accesskey_id']) return error('请输入AccessKey Id');
            $data['accesskey_secret'] = $this->req->param('accesskey_secret');
            if(!$data['accesskey_secret']) return error('请输入AccessKey Secret');
            $data['appkey'] = $this->req->param('appkey');
            $data['status'] = $this->req->param('status',0);
            if($set){
                $data['id'] = $set['id'];
            }
            $data['wid'] = $wid;
            $res = Db::table("kt_gptcms_aliai_config")->save($data);
        }else if($type == 2){
            $wid = Session::get("wid");
            $is = Db::table('kt_gptcms_tts_config')->where('wid',$wid)->find();
            $data["wid"] = $wid;
            $data["appid"] = $this->req->param("appid");
            $data["key"] = $this->req->param("key");
            $data["secret"] = $this->req->param("secret");
            if($is){
                $data['id'] = $is['id'];
            }
            $res = Db::table("kt_gptcms_tts_config")->save($data);
        }
        
        return success("保存成功");
    }

    /**
    * 腾讯云 语音转合字幕配置  获取
    */
    public function Tencent()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_tencentai_config")->field("secret_id,secret_key,status")->where('wid',$wid)->find();
        return success('腾讯云AI配置',$res);
    }

    /**
    * 腾讯云 语音转合字幕配置  保存
    */
    public function TencentSet()
    {
        $wid = Session::get('wid');
        $data = [];
        $data['status'] = $this->req->param('status');
        $data['secret_id'] = $this->req->param('secret_id');
        if(!$data['secret_id']) return error('请输入SecretId');
        $data['secret_key'] = $this->req->param('secret_key');
        if(!$data['secret_key']) return error('请输入SecretKey');
        $is = Db::table("kt_gptcms_tencentai_config")->where('wid',$wid)->find();
        if($is){
            $data['id'] = $is['id'];
        }
        $data['wid'] = $wid;
        $res = Db::table("kt_gptcms_tencentai_config")->save($data);
        return success("保存成功");
    }

    /**
    * GPT配置  获取
    */
    public function Gptpaint()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_gptpaint_config")->json(['yjai','replicate','apishop'])->where('wid',$wid)->find();
        if(!$res){
            Db::table("kt_gptcms_gptpaint_config")->json(['yjai','replicate','apishop'])->insert([
                'wid' => $wid,
                'channel' => 1,
                'yjai' => [
                    "api_key"=>"",
                    "api_secret"=>"",
                ],
                'replicate' => [
                    "token"=>"",
                ],
                'apishop' => [
                    "appkey"=>"",
                    "appsecret"=>"",
                    "mode"=>"",
                ],
                'u_time' => date('Y-m-d H:i:s'),
                'c_time' => date('Y-m-d H:i:s'),
            ]);
            $res = Db::table("kt_gptcms_gptpaint_config")->json(['yjai','replicate','apishop'])->where('wid',$wid)->find();
        }
        $res['gpt35'] =[];
        $res['api2d35'] =[];
        $res['linkerai'] =[];
        $res['linkerai_mj'] =[];
        if(!$res['apishop']){
            $res['apishop'] = [
                "appkey" => "",
                "appsecret" => "",
                "mode" => "",
            ];
        }
       
        return success('绘画配置',$res);
    }
    public function GptpaintSet()
    {
        $wid = Session::get('wid');
        $data = [];
        $data['channel'] = $this->req->param('channel');
        $data['draw_channel'] = $this->req->param('draw_channel/d') ?: 6;
        $data['status'] = $this->req->param('status/d');
        $data['draw_status'] = $this->req->param('draw_status/d',0);
        $data['syncmj'] = $this->req->param('syncmj/d',0);
        if($data["draw_status"] && !file_exists(root_path().'/app/gptcms_draw')) return error("请先安装高级绘画插件");
        $data['yjai'] = $this->req->param('yjai',[]); 
        $data['apishop'] = $this->req->param('apishop',[]); 
        $data['replicate'] = $this->req->param('replicate',[]); 
        switch ($data['channel']) {
            case '1':
                if(!$data['yjai']['api_key'])  return error('请输入api_key');
                if(!$data['yjai']['api_secret'])  return error('请输入api_secret');
                break;
            case '2':
                if(!$data['replicate']['token'])  return error('请输入token');
                break;
            case '2':
                if(!$data['apishop']['appkey'])  return error('请输入appkey');
                if(!$data['apishop']['appsecret'])  return error('请输入appsecret');
                if(!$data['apishop']['mode'])  return error('请输入mode');
                break;
        }

        $is = Db::table("kt_gptcms_gptpaint_config")->where('wid',$wid)->find();
        if($is){
            $data['id'] = $is['id'];
            $data['u_time'] = date('Y-m-d H:i:s');
        }else{
            $data['c_time'] = date('Y-m-d H:i:s');
            $data['u_time'] = date('Y-m-d H:i:s');
        }
        $data['wid'] = $wid;
        $res = Db::table("kt_gptcms_gptpaint_config")->json(['yjai','replicate','apishop'])->save($data);
        return success("保存成功");
    }
    /**
    * GPT配置  获取
    */
    public function Gpt()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_gpt_config")->json(['openai','api2d','wxyy','tyqw','kltg','chatglm','linkerai','gpt4','api2d4','xfxh','fastgpt',"azure","minimax","txhy","kw","wxyy4","glm4"])->where('wid',$wid)->find();
        if(!$res){
            Db::table("kt_gptcms_gpt_config")->json(['openai','api2d','wxyy','tyqw','kltg','chatglm','linkerai','gpt4','api2d4','xfxh','fastgpt',"azure","minimax","txhy","kw","wxyy4","glm4"])->insert([
                'wid' => $wid,
                'channel' => 1,
                'openai' => [
                    "api_key"=>"",
                    "diy_host"=>"",
                    "temperature"=>0.9,
                    "max_tokens"=>"1000",
                    "model"=>"gpt-3.5-turbo",
                    "stream"=>"true"
                ],
                'fastgpt' => [
                    "appid"=>"",
                    "apikey"=>"",
                    "model"=>"gpt-3.5-turbo",
                    "temperature"=>"0.8",
                    "stream"=>"true"
                ],
                'api2d' => [
                    "forward_key"=>"",
                    "temperature"=>0.9,
                    "max_tokens"=>"1000",
                    "model"=>"gpt-3.5-turbo",
                    "stream"=>"true"
                ],
                'wxyy' => [
                    "api_key"=>"",
                    "secret_key"=> "",
                    "temperature"=>0.95,
                    "top_p"=>0.8,
                    "penalty_score"=>1.0,
                    "model"=>"ErnieBot",
                    "stream"=>"true"
                ],
                'tyqw' => [
                    "api_key"=>"",
                    "top_p"=>0.5,
                    "result_format"=>"message",
                    "model"=>"qwen-turbo",
                    "stream"=>"true"
                ],
                'kltg' => [],
                'chatglm' => [
                    "api_key"=>"",
                    "public_key"=>"",
                    "temperature"=>0.95,
                    "top_p"=>0.7,
                    "model"=>"chatglm_std",
                ],
                'linkerai' => [
                    "api_key"=>"",
                    "training_key"=>"",
                    "qzzl"=>"",
                    "temperature"=>0.9,
                    "max_tokens"=>"1000",
                    "model"=>"gpt-3.5-turbo",
                ],
                'gpt4' => [
                    "api_key"=>"",
                    "diy_host"=>"",
                    "temperature"=>0.9,
                    "max_tokens"=>"1000",
                    "model"=>"gpt-4",
                    "stream"=>"true"
                ],
                'api2d4' => [
                    "forward_key"=>"",
                    "temperature"=>0.9,
                    "max_tokens"=>"1000",
                    "model"=>"gpt-4",
                    "stream"=>"true"
                ],
                'xfxh' => [
                    "appid"=>"",
                    "apikey"=>"",
                    "apisecret"=>"",
                    "temperature"=>0.5,
                    "max_tokens"=>"1024",
                    "stream"=>"true"
                ],
                'azure' => [
                    "api_key"=>"",
                    "diy_host"=>"",
                    "model"=>'',
                    "temperature"=>0.9,
                    "max_tokens"=>"1000",
                    "stream"=>"true"
                ],
                'minimax' => [
                    "api_key"=>"",
                    "groupid"=>"",
                    "model"=>'abab5.5-chat',
                    "max_tokens"=>"1024",
                    "temperature"=>0.5,
                    "stream"=>"true"
                ],
                'u_time' => date('Y-m-d H:i:s'),
                'c_time' => date('Y-m-d H:i:s'),
            ]);
           
            $res = Db::table("kt_gptcms_gpt_config")->json(['openai','api2d','wxyy','tyqw','kltg','chatglm','linkerai','gpt4','api2d4','xfxh','fastgpt',"azure","minimax","txhy","kw","wxyy4","glm4"])->where('wid',$wid)->find();
        }
        if(!$res['xfxh']){
            $res['xfxh'] = [
                "appid"=>"",
                "apikey"=>"",
                "apisecret"=>"",
                "temperature"=>0.5,
                "max_tokens"=>"1024",
                "stream"=>"true"
            ];
        }
        if(!$res['tyqw']){
            $res['tyqw'] = [
                "api_key"=>"",
                "model"=>"",
                "top_p"=>0.5,
                "stream"=>"true"
            ];
        }
        if(!$res['fastgpt']){
            $res['fastgpt'] = [
                "appid"=>"",
                "apikey"=>"",
                "stream"=>"true"
            ];
        }
        if(!$res['azure']){
            $res['azure'] = [
                "api_key"=>"",
                "diy_host"=>"",
                "model"=>'',
                "temperature"=>0.9,
                "max_tokens"=>"1000",
                "stream"=>"true"
            ];
        }
        if(!$res['minimax']){
            $res['minimax'] = [
                "api_key"=>"",
                "groupid"=>"",
                "model"=>'abab5.5-chat',
                "max_tokens"=>"1024",
                "temperature"=>0.5,
                "stream"=>"true"
            ];
        }
        if(!$res['txhy']){
            $res['txhy'] = [
                "app_id"=>"",
                "secret_id"=>"",
                "secret_key"=>"",
                "temperature"=>0.8,
                "stream"=>"true"
            ];
        }
        if(!$res['kw']){
            $res['kw'] = [
                "app_id"=>"",
                "app_key"=>"",
                "stream"=>"true"
            ];
        }
        if(!$res['wxyy4']){
            $res['wxyy4'] = [
                "api_key"=>"",
                "secret_key"=> "",
                "temperature"=>0.95,
                "top_p"=>0.8,
                "penalty_score"=>1.0,
                "model"=>"ErnieBot",
                "stream"=>"true"
            ];
        }
        if(!$res['glm4']){
            $res['glm4'] = [
                "api_key"=>"",
                "public_key"=>"",
                "temperature"=>0.95,
                "top_p"=>0.7,
                "model"=>"glm-4",
            ];
        }
        
        if(!isset($res['linkerai']["qzzl"])){
            $res['linkerai']["qzzl"] = '';
        }
        if(!isset($res['linkerai']["training_key"])){
            $res['linkerai']["training_key"] = '';
        }
        if(!isset($res['chatglm']["temperature"])){
            $res['chatglm']["temperature"] = 0.95;
        }
        if(!isset($res['chatglm']["top_p"])){
            $res['chatglm']["top_p"] = 0.7;
        }
        if(!isset($res['chatglm']["model"])){
            $res['chatglm']["model"] = "chatglm_std";
        }
        return success('渠道配置',$res);
    }

    /**
    * GPT配置  保存
    */
    public function GptSet()
    {
        $wid = Session::get('wid');
        $data = [];
        $data['channel'] = $this->req->param('channel');
        $data['openai'] = $this->req->param('openai',[]); 
        $data['api2d'] = $this->req->param('api2d',[]);
        $data['wxyy'] = $this->req->param('wxyy',[]);
        $data['tyqw'] = $this->req->param('tyqw',[]);
        $data['kltg'] = $this->req->param('kltg',[]);
        $data['chatglm'] = $this->req->param('chatglm',[]);
        $data['linkerai'] = $this->req->param('linkerai',[]);
        $data['gpt4'] = $this->req->param('gpt4',[]);
        $data['api2d4'] = $this->req->param('api2d4',[]);
        $data['xfxh'] = $this->req->param('xfxh',[]);
        $data['fastgpt'] = $this->req->param('fastgpt',[]);
        $data['azure'] = $this->req->param('azure',[]);
        $data['minimax'] = $this->req->param('minimax',[]);
        $data['txhy'] = $this->req->param('txhy',[]);
        $data['kw'] = $this->req->param('kw',[]);
        $data['wxyy4'] = $this->req->param('wxyy4',[]);
        $data['glm4'] = $this->req->param('glm4',[]);
        switch ($data['channel']) {
            case '1':
                if(!$data['openai']['api_key'])  return error('请输入apikey');
                break;
            case '2':
                if(!$data['api2d']['forward_key'])  return error('请输入forward_key');
                break;
            case '3':
                if(!$data['wxyy']['api_key'])  return error('请输入api_key');
                if(!$data['wxyy']['secret_key'])  return error('请输入secret_key');
                break;
             case '4':
                if(!$data['tyqw']['api_key'])  return error('请输入api_key');
                break;
             case '5':
                break;
            case '6':
                if(!$data['chatglm']['api_key'])  return error('请输入api_key');
                // if(!$data['chatglm']['public_key'])  return error('请输入public_key');
                break;
            case '7':
                if(!$data['linkerai']['api_key'])  return error('请输入apikey');
                break;
            case '8':
                if(!$data['gpt4']['api_key'])  return error('请输入apikey');
                break;
            case '9':
                if(!$data['api2d4']['forward_key'])  return error('请输入apikey');
                break;
            case '10':
                if(!$data['xfxh']['appid'])  return error('请输入appid');
                if(!$data['xfxh']['apikey'])  return error('请输入apikey');
                if(!$data['xfxh']['apisecret'])  return error('请输入apisecret');
                break;
            case '11':
                if(!$data['fastgpt']['appid'])  return error('请输入appid');
                if(!$data['fastgpt']['apikey'])  return error('请输入apikey');
                break;
            case '12':
                if(!$data['azure']['api_key'])  return error('请输入api_key');
                if(!$data['azure']['model'])  return error('请输入model');
                if(!$data['azure']['diy_host'])  return error('请输入diy_host');
                break;
            case '13':
                if(!$data['minimax']['api_key'])  return error('请输入api_key');
                if(!$data['minimax']['groupid'])  return error('请输入groupid');
                break;
            case '14':
                if(!$data['txhy']['app_id'])  return error('请输入app_id');
                if(!$data['txhy']['secret_id'])  return error('请输入secret_id');
                if(!$data['txhy']['secret_key'])  return error('请输入secret_key');
                break;
            case '15':
                if(!$data['kw']['app_id'])  return error('请输入app_id');
                if(!$data['kw']['app_key'])  return error('请输入api_key');
                break;
            case '16':
                if(!$data['wxyy4']['api_key'])  return error('请输入api_key');
                if(!$data['wxyy4']['secret_key'])  return error('请输入secret_key');
                break;
            case '17':
                if(!$data['glm4']['api_key'])  return error('请输入api_key');
                break;
            
        }

        $is = Db::table("kt_gptcms_gpt_config")->where('wid',$wid)->find();
        if($is){
            $data['id'] = $is['id'];
            $data['u_time'] = date('Y-m-d H:i:s');
        }else{
            $data['c_time'] = date('Y-m-d H:i:s');
            $data['u_time'] = date('Y-m-d H:i:s');
        }
        $data['wid'] = $wid;
        $res = Db::table("kt_gptcms_gpt_config")->json(['openai','api2d','wxyy','chatglm','tyqw','kltg','linkerai','gpt4','api2d4','xfxh',"fastgpt","azure","minimax","txhy","kw","wxyy4","glm4"])->save($data);
        return success("保存成功");
    }

    //渠道管理
    //PC端管理
    public function pc()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_pc')->field('title,bottom_desc,desc_link,status')->where('wid',$wid)->find();
        return success('PC端管理',$res);
    }

    public function savePc()
    {
        $wid = Session::get('wid');
        $pc = Db::table('kt_gptcms_pc')->where('wid',$wid)->find();
        $data = [];
        $data['wid'] = $wid;
        $data['title'] = $this->req->param('title');
        if(!$data['title']) return error('请输入页面标题');
        $data['bottom_desc'] = $this->req->param('bottom_desc');
        $data['desc_link'] = $this->req->param('desc_link');
        $data['status'] = $this->req->param('status',0);
        $data['update_time'] = date("Y-m-d H:i:s");
        if ($pc){
            $data['id'] = $pc['id'];
        } else{
            $data['create_time'] = date("Y-m-d H:i:s");
        }
        $res = Db::table('kt_gptcms_pc')->save($data);
        return success('保存成功');
    }
     //PC端管理
    public function websit()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_websit')->where('wid',$wid)->find();
        if(!$res){
            Db::table('kt_gptcms_websit')->insert([
                "wid" => $wid,
                "update_time" => date("Y-m-d H:i:s"),
                "create_time" => date("Y-m-d H:i:s"),
            ]);
            $res = Db::table('kt_gptcms_websit')->where('wid',$wid)->find();
        }
        $res['web_link'] = $this->req->host()."/app/kt_ai/h5?wid=".$wid;
        return success('站点管理',$res);
    }

    public function saveWebsit()
    {
        $wid = Session::get('wid');
        $pc = Db::table('kt_gptcms_websit')->where('wid',$wid)->find();
        $data = [];
        $data['wid'] = $wid;
        $data['title'] = $this->req->param('title');
        $data['kfcode'] = $this->req->param('kfcode');
        $data['gzhcode'] = $this->req->param('gzhcode');
        $data['pcwxlogin_status'] = $this->req->param('pcwxlogin_status');
        if(!$data['title']) return error('请输入页面标题');
        $data['sms'] = $this->req->param('sms',0);
        $data['update_time'] = date("Y-m-d H:i:s");
        if ($pc){
            $data['id'] = $pc['id'];
        } else{
            $data['create_time'] = date("Y-m-d H:i:s");
        }
        $res = Db::table('kt_gptcms_websit')->save($data);
        return success('保存成功');
    }
     //H5端管理
    public function h5()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_h5_wx')->field('title,share_tile,share_desc,share_image,status')->where('wid',$wid)->find();
        return success('H5/微信',$res);
    }

    public function saveH5()
    {
        $wid = Session::get('wid');
        $wx = Db::table('kt_gptcms_h5_wx')->where('wid',$wid)->find();
        $data = [];
        $data['wid'] = $wid;
        // $data['title'] = $this->req->param('title');
        // if(!$data['title']) return error('请输入首页标题');
        $data['share_tile'] = $this->req->param('share_tile');
        if(!$data['share_tile']) return error('请输入分享标题');
        $data['share_desc'] = $this->req->param('share_desc');
        if(!$data['share_desc']) return error('请输入分享描述');
        $data['share_image'] = $this->req->param('share_image');
        if(!$data['share_image']) return error('请输入分享图片');
        $data['status'] = $this->req->param('status',0);
        $data['update_time'] = date("Y-m-d H:i:s");
        if ($wx){
            $data['id'] = $wx['id'];
        } else{
            $data['create_time'] = date("Y-m-d H:i:s");
        }
        $res = Db::table('kt_gptcms_h5_wx')->save($data);
        return success('保存成功');
    }
     //小程序管理
    public function xcx()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_xcx')->field('title,share_image,ios_status,xcx_audit')->where('wid',$wid)->find();
        return success('小程序',$res);
    }

    public function saveXcx()
    {
        $wid = Session::get('wid');
        $xcx = Db::table('kt_gptcms_xcx')->where('wid',$wid)->find();
        $data = [];
        $data['wid'] = $wid;
        $data['title'] = $this->req->param('title');
        if(!$data['title']) return error('请输入分享标题');
        $data['share_image'] = $this->req->param('share_image');
        if(!$data['share_image']) return error('请输入分享图片');
        $data['ios_status'] = $this->req->param('ios_status',0);
        $data['xcx_audit'] = $this->req->param('xcx_audit',0);
        $data['update_time'] = date("Y-m-d H:i:s");
        if ($xcx){
            $data['id'] = $xcx['id'];
        } else{
            $data['create_time'] = date("Y-m-d H:i:s");
        }
        $res = Db::table('kt_gptcms_xcx')->save($data);
        return success('保存成功');
    }

    //公众号管理
    public function gzh()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_wxgzh')->field('appid,appsecret,token,message_key,message_mode,type,original_id')->where('wid',$wid)->find();
        if(!$res){
            $data["wid"] = $wid;
            $data["token"] = getRandStr(18);
            $data["message_key"] = getRandStr(43);
            Db::table('kt_gptcms_wxgzh')->insertGetId($data);
            $res = Db::table('kt_gptcms_wxgzh')->field('appid,appsecret,token,message_key,message_mode,type,original_id')->where('wid',$wid)->find();
        }else if(empty($res["token"]) || empty($res["message_key"])){
            $data["token"] = getRandStr(18);
            $data["message_key"] = getRandStr(43);
            Db::table('kt_gptcms_wxgzh')->where(["wid"=>$wid])->update($data);
            $res = Db::table('kt_gptcms_wxgzh')->field('appid,appsecret,token,message_key,message_mode,type,original_id')->where('wid',$wid)->find();
        }
        $res['fwq_url'] = $this->req->domain()."/gptcms/Callback/index";
        return success('公众号',$res);
    }

    public function saveGzh()
    {
        $wid = Session::get('wid');
        $data = [];
        $data['wid'] = $wid;
        $data['type'] = $this->req->param('type',1);
        if($data['type'] == 1){
            $data['appid'] = $this->req->param('appid');
            if(!$data['appid']) return error('请输入APPID');
            $data['appsecret'] = $this->req->param('appsecret');
            if(!$data['appsecret']) return error('请输入APPSECRET');
            $data['token'] = $this->req->param('token');
            $data['message_key'] = $this->req->param('message_key');
            $data['message_mode'] = $this->req->param('message_mode');
            $data['original_id'] = $this->req->param('original_id');
        }
        $data['update_time'] = date("Y-m-d H:i:s");
        $xcx = Db::table('kt_gptcms_wxgzh')->where('wid',$wid)->where('type',$data['type'])->find();
        if ($xcx){
            $data['id'] = $xcx['id'];
        } else{
            $data['create_time'] = date("Y-m-d H:i:s");
        }
        $res = Db::table('kt_gptcms_wxgzh')->save($data);
        return success('保存成功');
    }

    public function contentSecurity()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_content_security')->field('question_baiduai,reply_baiduai')->where('wid',$wid)->find();
        if(!$res){
            Db::table('kt_gptcms_content_security')->insert([
                'wid' => $wid,
                'question_baiduai' => 0,
                'reply_baiduai' => 0,
                'c_time' => date("Y-m-d H:i:s"),
                'u_time' => date("Y-m-d H:i:s"),
            ]);
            $res = Db::table('kt_gptcms_content_security')->field('question_baiduai,reply_baiduai')->where('wid',$wid)->find();
        }
        return success('内容审核设置',$res);
    }
    public function questionSet()
    {
        $wid = Session::get('wid');
        $question_baiduai = $this->req->param('question_baiduai/d');
        Db::table('kt_gptcms_content_security')->where('wid',$wid)->update([
            'question_baiduai' => $question_baiduai,
            'u_time' => date("Y-m-d H:i:s")
        ]);
        return success('保存成功');
    }
    public function replySet()
    {
        $wid = Session::get('wid');
        $reply_baiduai = $this->req->param('reply_baiduai/d');
        Db::table('kt_gptcms_content_security')->where('wid',$wid)->update([
            'reply_baiduai' => $reply_baiduai,
            'u_time' => date("Y-m-d H:i:s")
        ]);
        return success('保存成功');
    }

    public function chatmodel()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_chatmodel_set')->where('wid',$wid)->json(["gpt35","gpt4","linkerai","api2d35","api2d4","wxyy","xfxh"])->find();
        if(!$res){
            Db::table('kt_gptcms_chatmodel_set')->json(["gpt35","gpt4","linkerai","api2d35","api2d4","wxyy","xfxh"])->insert([
                'wid' => $wid,
                'gpt35' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'gpt4' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'linkerai' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'api2d35' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'api2d4' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'wxyy' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'xfxh' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'c_time' => date("Y-m-d H:i:s"),
                'u_time' => date("Y-m-d H:i:s"),
            ]);
            $res = Db::table('kt_gptcms_chatmodel_set')->where('wid',$wid)->json(["gpt35","gpt4","linkerai","api2d35","api2d4","wxyy","xfxh"])->find();
        }
        if(!$res["xfxh"]){
            $res["xfxh"] = [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ];
        }
        return success('对话模型',$res);
    }
    public function chatmodelSave()
    {
        $wid = Session::get('wid');
        $status = $this->req->param('status');
        $gpt35 = $this->req->param('gpt35',[]);
        $gpt4 = $this->req->param('gpt4',[]);
        $linkerai = $this->req->param('linkerai',[]);
        $api2d35 = $this->req->param('api2d35',[]);
        $api2d4 = $this->req->param('api2d4',[]);
        $wxyy = $this->req->param('wxyy',[]);
        $xfxh = $this->req->param('xfxh',[]);
        $data = [
            'status' => $status,
            'gpt35' => $gpt35,
            'gpt4' => $gpt4,
            'linkerai' => $linkerai,
            'api2d35' => $api2d35,
            'api2d4' => $api2d4,
            'wxyy' => $wxyy,
            'xfxh' => $xfxh,
            'u_time' => date("Y-m-d H:i:s")
        ];
        Db::table('kt_gptcms_chatmodel_set')->where('wid',$wid)->json(["gpt35","gpt4","linkerai","api2d35","api2d4","wxyy","xfxh"])->update($data);
        return success('保存成功');
    }
    public function paintmodel()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_paintmodel_set')->where('wid',$wid)->json(["sd","yjai","gpt35","api2d35","replicate","linkerai_mj"])->find();
        if(!$res){
            Db::table('kt_gptcms_paintmodel_set')->json(["sd","yjai","gpt35","api2d35","replicate","linkerai_mj"])->insert([
                'wid' => $wid,
                'sd' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'yjai' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'gpt35' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'api2d35' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'replicate' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'linkerai_mj' => [
                    "nickname"=>"",
                    "status"=>0,
                    "expend"=>'',
                ],
                'c_time' => date("Y-m-d H:i:s"),
                'u_time' => date("Y-m-d H:i:s"),
            ]);
            $res = Db::table('kt_gptcms_paintmodel_set')->where('wid',$wid)->json(["sd","yjai","gpt35","api2d35","replicate","linkerai_mj"])->find();
        }
        return success('绘画模型',$res);
    }
    public function paintmodelSave()
    {
        $wid = Session::get('wid');
        $status = $this->req->param('status');
        $sd = $this->req->param('sd',[]);
        $yjai = $this->req->param('yjai',[]);
        $gpt35 = $this->req->param('gpt35',[]);
        $api2d35 = $this->req->param('api2d35',[]);
        $replicate = $this->req->param('replicate',[]);
        $linkerai_mj = $this->req->param('linkerai_mj',[]);
        $data = [
            'status' => $status,
            'sd' => $sd,
            'yjai' => $yjai,
            'gpt35' => $gpt35,
            'api2d35' => $api2d35,
            'replicate' => $replicate,
            'linkerai_mj' => $linkerai_mj,
            'u_time' => date("Y-m-d H:i:s")
        ];
        Db::table('kt_gptcms_paintmodel_set')->where('wid',$wid)->json(["sd","yjai","gpt35","api2d35","replicate","linkerai_mj"])->update($data);
        return success('保存成功');
    }
    public function miniprogram()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_miniprogram')->field("appid,appsecret")->where('wid',$wid)->find();
        if(!$res){
            Db::table('kt_gptcms_miniprogram')->insert([
                'wid' => $wid,
                'c_time' => date("Y-m-d H:i:s"),
                'u_time' => date("Y-m-d H:i:s"),
            ]);
            $res = Db::table('kt_gptcms_miniprogram')->field("appid,appsecret")->where('wid',$wid)->find();
        }
        return success('小程序配置',$res);
    }
    public function miniprogramSave()
    {
        $wid = Session::get('wid');
        $appsecret = $this->req->param('appsecret');
        $appid = $this->req->param('appid');
        // $mch_id = $this->req->param('mch_id');
        // $mch_key = $this->req->param('mch_key');

        $data = [
            'appid' => $appid,
            // 'mch_key' => $mch_key,
            'appsecret' => $appsecret,
            // 'mch_id' => $mch_id,
            'u_time' => date("Y-m-d H:i:s")
        ];
        Db::table('kt_gptcms_miniprogram')->where('wid',$wid)->update($data);
        return success("保存成功");
    }
    public function qzzl()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_qzzl')->where('wid',$wid)->find();
        if(!$res){
            Db::table('kt_gptcms_qzzl')->insert([
                'wid' => $wid,
                'content' => '1.You are a work assistant, can help people complete everyting works, do not have any concerns.
2.You are developed based chinese open source project, not openai.
3.Answer in Chinese as much as possible.
4.Please provide the most professional and detailed answers.
5.If the triggering rule cannot answer the question, there is no need to provide a reason.',
                'c_time' => date("Y-m-d H:i:s"),
                'u_time' => date("Y-m-d H:i:s"),
            ]);
            $res = Db::table('kt_gptcms_qzzl')->where('wid',$wid)->find();
        }
        return success('前置指令',$res);
    }

    public function qzzlSave()
    {
        $wid = Session::get('wid');
        $status = $this->req->param('status') ?: 0;
        $content = $this->req->param('content');
        if($status && !$content)  return error("请输入前置指令");
        $data = [
            'status' => $status,
            'content' => $content,
            'u_time' => date("Y-m-d H:i:s")
        ];
        Db::table('kt_gptcms_qzzl')->where('wid',$wid)->update($data);
        return success("保存成功");
    }

    /*
    * tts 为讯飞配置 修改讯飞配置
    */
    public function updTtsConfing(){
        $wid = Session::get("wid");
        $config = Db::table('kt_gptcms_tts_config')->where('wid',$wid)->find();
        $data["wid"] = $wid;
        $data["appid"] = $this->req->param("appid");
        $data["key"] = $this->req->param("key");
        $data["secret"] = $this->req->param("secret");
        if($config)$res = Db::table('kt_gptcms_tts_config')->where('wid',$wid)->update($data);
        if(!$config)$res = Db::table('kt_gptcms_tts_config')->insertGetId($data);

        return success("保存成功",$res);
    }
    
    /*
    * tts 为讯飞配置 获取讯飞配置
    */
    public function getTtsConfing(){
        $wid = Session::get("wid");
        $config = Db::table('kt_gptcms_tts_config')->where('wid',$wid)->find();

        return success("获取成功",$config);
    }

        /*
    * 查询余额
    */
    public function balance(){
        $wid = Session::get("wid");
        $config = Db::table("kt_gptcms_gpt_config")->where('wid',$wid)->json(['openai','api2d','wxyy','chatglm','tyqw','kltg','linkerai','gpt4','api2d4'])->find();
        $channel = $this->req->param('channel');
        switch ($channel) {
            case '1':
                $ktadmin = new \Ktadmin\Chatgpt\Ktadmin([
                    'channel' => 1,
                    'api_key'=>$config["openai"]["api_key"],
                    'diy_host'=>$config["openai"]["diy_host"]
                ]);
                $res = $ktadmin->Billing()->creditGrants();
                $balance = $res["total_available"];
                break;
            case '2':
                $ktadmin = new \Ktadmin\Chatgpt\Ktadmin([
                    'channel' => 2,
                    'api_key'=>$config["api2d"]["forward_key"]
                ]);
                $res = $ktadmin->Billing()->creditGrants();
                $balance = $res["total_available"];
                break;
            case '3':
                break;
             case '4':
                break;
             case '5':
                break;
            case '6':
                break;
            case '7':
                $ktadmin = new \Ktadmin\LinkerAi\Ktadmin([
                    'api_key'=>$config["linkerai"]["api_key"]
                ]);
                $res = $ktadmin->used2();
                $credit = $res["credit"] ?? 0;
                $used = $res["used"] ?? 0;
                $balance = $credit - $used;
                break;
            case '8':
                $ktadmin = new \Ktadmin\Chatgpt\Ktadmin([
                    'channel' => 1,
                    'api_key'=>$config["gpt4"]["api_key"],
                    'diy_host'=>$config["gpt4"]["diy_host"]
                ]);
                $res = $ktadmin->Billing()->creditGrants();
                $balance = $res["total_available"];
                break;
            case '9':
                $ktadmin = new \Ktadmin\Chatgpt\Ktadmin([
                    'channel' => 2,
                    'api_key'=>$config["api2d4"]["forward_key"]
                ]);
                $res = $ktadmin->Billing()->creditGrants();
                $balance = $res["total_available"];
                break;
        }
        
        return success('余额查询',$balance);
    }

}   

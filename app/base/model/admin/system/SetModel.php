<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\model\admin\system;
use think\facade\Db;
use think\facade\Session;
use app\base\model\BaseModel;

/* 
* 接口配置
*/
class SetModel extends BaseModel
{
    /**
    * Baidu AI配置  获取
    */
    static public function BaiduAi()
    {
        $uid = Session::get('uid');
        $res = Db::table("kt_base_baiduai_config")->field("appid,apikey,secretkey")->where('uid',$uid)->find();
        return success('百度AI配置',$res);
    }

    /**
    * Baidu AI配置  保存
    */
    static public function BaiduAiSet($req)
    {
        $uid = Session::get('uid');
        $data = [];
        $data['appid'] = $req->param('appid');
        if(!$data['appid']) return error('请输入Appid');
        $data['apikey'] = $req->param('apikey');
        if(!$data['apikey']) return error('请输入Key');
        $data['secretkey'] = $req->param('secretkey');
        if(!$data['secretkey']) return error('请输入Secret');
        $is = Db::table("kt_base_baiduai_config")->where('uid',$uid)->find();
        if($is){
            $data['id'] = $is['id'];
        }
        $data['uid'] = $uid;
        $res = Db::table("kt_base_baiduai_config")->save($data);
        return success("保存成功");
        
    }
    /**
    * Aliyun 语音合成配置  获取
    */
    static public function Aliyun()
    {
        $uid = Session::get('uid');
        $res = Db::table("kt_base_aliai_config")->field("accesskey_id,accesskey_secret,region,appkey")->where('uid',$uid)->find();
        return success('阿里云AI配置',$res);
    }

    /**
    * Aliyun 语音合成配置  保存
    */
    static public function AliyunSet($req)
    {
        $uid = Session::get('uid');
        $data = [];
        $data['region'] = $req->param('region');
        $data['accesskey_id'] = $req->param('accesskey_id');
        if(!$data['accesskey_id']) return error('请输入AccessKey Id');
        $data['accesskey_secret'] = $req->param('accesskey_secret');
        if(!$data['accesskey_secret']) return error('请输入AccessKey Secret');
        $data['appkey'] = $req->param('appkey');

        $is = Db::table("kt_base_aliai_config")->where('uid',$uid)->find();
        if($is){
            $data['id'] = $is['id'];
        }
        $data['uid'] = $uid;
        $res = Db::table("kt_base_aliai_config")->save($data);
        return success("保存成功");
    }

    /**
    * 腾讯云 语音转合字幕配置  获取
    */
    static public function Tencent()
    {
        $uid = Session::get('uid');
        $res = Db::table("kt_base_tencentai_config")->field("secret_id,secret_key")->where('uid',$uid)->find();
        return success('腾讯云AI配置',$res);
    }

    /**
    * 腾讯云 语音转合字幕配置  保存
    */
    static public function TencentSet($req)
    {
        $uid = Session::get('uid');
        $data = [];
        $data['secret_id'] = $req->param('secret_id');
        if(!$data['secret_id']) return error('请输入SecretId');
        $data['secret_key'] = $req->param('secret_key');
        if(!$data['secret_key']) return error('请输入SecretKey');
        $is = Db::table("kt_base_tencentai_config")->where('uid',$uid)->find();
        if($is){
            $data['id'] = $is['id'];
        }
        $data['uid'] = $uid;
        $res = Db::table("kt_base_tencentai_config")->save($data);
        return success("保存成功");
    }

    /**
    * GPT配置  获取
    */
    static public function Gpt()
    {
        $uid = Session::get('uid');
        $res = Db::table("kt_base_gpt_config")->field('channel,openai,api2d,wxyy,tyqw,kltg,chatglm,linkerai')->json(['openai','api2d','wxyy','tyqw','kltg','chatglm','linkerai'])->where('uid',$uid)->find();
        if(!$res){
            Db::table("kt_base_gpt_config")->json(['openai','api2d','wxyy','tyqw','kltg','chatglm','linkerai'])->insert([
                'uid' => $uid,
                'channel' => 1,
                'openai' => [
                    "api_key"=>"",
                    "diy_host"=>"",
                    "temperature"=>0.9,
                    "max_tokens"=>"1000",
                    "model"=>"gpt-3.5-turbo",
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
                    "secret_key"=> ""
                ],
                'tyqw' => [],
                'kltg' => [],
                'chatglm' => [
                    "api_key"=>"",
                    "public_key"=>""
                ],
                'linkerai' => [
                    "api_key"=>"",
                    "temperature"=>0.9,
                    "max_tokens"=>"1000",
                    "model"=>"gpt-3.5-turbo",
                ],
                'u_time' => date('Y-m-d H:i:s'),
                'c_time' => date('Y-m-d H:i:s'),
            ]);
            $res = Db::table("kt_base_gpt_config")->field('channel,openai,api2d,wxyy,tyqw,kltg,chatglm,linkerai')->json(['openai','api2d','wxyy','tyqw','kltg','chatglm','linkerai'])->where('uid',$uid)->find();
        }
        return success('GPT配置',$res);
    }

    /**
    * GPT配置  保存
    */
    static public function GptSet($req)
    {
        $uid = Session::get('uid');
        $data = [];
        $data['channel'] = $req->param('channel');
        $data['openai'] = $req->param('openai',[]); 
        $data['api2d'] = $req->param('api2d',[]);
        $data['wxyy'] = $req->param('wxyy',[]);
        $data['tyqw'] = $req->param('tyqw',[]);
        $data['kltg'] = $req->param('kltg',[]);
        $data['chatglm'] = $req->param('chatglm',[]);
        $data['linkerai'] = $req->param('linkerai',[]);
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
                break;
             case '5':
                break;
            case '6':
                if(!$data['chatglm']['api_key'])  return error('请输入api_key');
                if(!$data['chatglm']['public_key'])  return error('请输入public_key');
                break;
            case '7':
                if(!$data['linkerai']['api_key'])  return error('请输入apikey');
                break;
        }

        $is = Db::table("kt_base_gpt_config")->where('uid',$uid)->find();
        if($is){
            $data['id'] = $is['id'];
            $data['u_time'] = date('Y-m-d H:i:s');
        }else{
            $data['c_time'] = date('Y-m-d H:i:s');
            $data['u_time'] = date('Y-m-d H:i:s');
        }
        $data['uid'] = $uid;
        $res = Db::table("kt_base_gpt_config")->json(['openai','api2d','wxyy','chatglm','tyqw','kltg','linkerai'])->save($data);
        return success("保存成功");
    }

    static public function review()
    {
        $uid = Session::get('uid');
        $res = Db::table('kt_base_content_security')->field('question_system,question_deal,question_reply,reply_system,reply_deal,reply_reply')->where('uid',$uid)->find();
        if(!$res){
            Db::table('kt_base_content_security')->insert([
                'uid' => $uid,
                'question_system' => 1,
                'question_deal' => 1,
                'question_reply' => '您的提问命中系统敏感词库，请重新提问。输入输出内容已接入AI自动审核，恶意提问或引导AI输出违规内容，将会被封号。',
                'reply_system' => 1,
                'reply_deal' => 1,
                'reply_reply' => '您的提问命中系统敏感词库，请重新提问。输入输出内容已接入AI自动审核，恶意提问或引导AI输出违规内容，将会被封号。',
                'c_time' => date("Y-m-d H:i:s"),
                'u_time' => date("Y-m-d H:i:s"),
            ]);
            $res = Db::table('kt_base_content_security')->field('question_system,question_deal,question_reply,reply_system,reply_deal,reply_reply')->where('uid',$uid)->find();
        }
        return success('内容审核设置',$res);
    }
    static public function questionSet($req)
    {
        $uid = Session::get('uid');
        $question_system = $req->param('question_system',1);
        $question_deal = $req->param('question_deal',1);
        $question_reply = $req->param('question_reply');
        Db::table('kt_base_content_security')->where('uid',$uid)->update([
            'question_system' => $question_system,
            'question_deal' => $question_deal,
            'question_reply' => $question_reply,
            'u_time' => date("Y-m-d H:i:s")
        ]);
        return success('保存成功');
    }
    static public function replySet($req)
    {
        $uid = Session::get('uid');
        $reply_system = $req->param('reply_system',1);
        $reply_deal = $req->param('reply_deal',1);
        $reply_reply = $req->param('reply_reply');
        Db::table('kt_base_content_security')->where('uid',$uid)->update([
            'reply_system' => $reply_system,
            'reply_deal' => $reply_deal,
            'reply_reply' => $reply_reply,
            'u_time' => date("Y-m-d H:i:s")
        ]);
        return success('保存成功');
    }

    static public function sensitiveLexicon()
    {
        $uid = Session::get('uid');
        $path = app_path().'review.txt';
        $lexicon = array_filter(explode(',', file_get_contents($path)));
        return success('敏感词库列表',$lexicon);
    }

    static public function sensitiveLexiconSave($req)
    {
        $uid = Session::get('uid');
        $word = $req->param('word');
        if(!$word) return error('请输入词语');
        $path = app_path().'review.txt';
        $lexicon = array_filter(explode(',', file_get_contents($path)));
        if(in_array($word, $lexicon)) return error('词语已存在');
        $lexicon[] = $word;
        file_put_contents($path,implode(',', $lexicon));
        return success('保存成功');
    }

}
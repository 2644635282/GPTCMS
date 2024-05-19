<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use app\gptcms\model\CommonModel;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;

class Chat extends BaseApi
{
    public $chatmodel;
    /**
     * 获取对话渠道
     */
    public function getChatSet()
    {
        $wid = Session::get('wid');
        $res = Db::table('kt_gptcms_chatmodel_set')->where('wid',$wid)->json(["gpt35","gpt4","linkerai","api2d35","api2d4","wxyy","xfxh","fastgpt","chatglm","tyqw","azure","minimax","txhy","kw","wxyy4","glm4"])->find();
        $data = [];
        $data['chatmodel_status'] = $res['status'] ?? 0;
        $data['chatmodel'] = [];
        if($res){
            
            $res['gpt35']["type"] = "gpt35";
            $res['gpt35']["nickname"] = $res['gpt35']["nickname"]?:"gpt3.5";
            $res['gpt35']["expend"] = $res['gpt35']["expend"]?:1;
            $res['gpt35']["icon"] = $res['gpt35']["icon"]??"";
            $res['gpt35']["desc"] = $res['gpt35']["desc"]??"";
            $data['chatmodel'][] = $res["gpt35"];
            
            if($res['gpt4']['status']){
                $res['gpt4']["type"] = "gpt4";
                $res['gpt4']["nickname"] = $res['gpt4']["nickname"]?:"gpt4";
                $res['gpt4']["expend"] = $res['gpt4']["expend"]?:1;
                $res['gpt4']["icon"] = $res['gpt4']["icon"]??"";
                $res['gpt4']["desc"] = $res['gpt4']["desc"]??"";
                $data['chatmodel'][] = $res["gpt4"];
            }
            if($res['linkerai']['status']){
                $res['linkerai']["type"] = "linkerai";
                $res['linkerai']["nickname"] = $res['linkerai']["nickname"]?:"灵犀星火";
                $res['linkerai']["expend"] = $res['linkerai']["expend"]?:1;
                $res['linkerai']["icon"] = $res['linkerai']["icon"]??"";
                $res['linkerai']["desc"] = $res['linkerai']["desc"]??"";
                $data['chatmodel'][] = $res["linkerai"];
            }
            if($res['api2d35']['status']){
                $res['api2d35']["type"] = "api2d35";
                $res['api2d35']["nickname"] = $res['api2d35']["nickname"]?:"api2d3.5";
                $res['api2d35']["expend"] = $res['api2d35']["expend"]?:1;
                $res['api2d35']["icon"] = $res['api2d35']["icon"]??"";
                $res['api2d35']["desc"] = $res['api2d35']["desc"]??"";
                $data['chatmodel'][] = $res["api2d35"];
            }
            if($res['api2d4']['status']){
                $res['api2d4']["type"] = "api2d4";
                $res['api2d4']["nickname"] = $res['api2d4']["nickname"]?:"api2d4";
                $res['api2d4']["expend"] = $res['api2d4']["expend"]?:1;
                $res['api2d4']["icon"] = $res['api2d4']["icon"]??"";
                $res['api2d4']["desc"] = $res['api2d4']["desc"]??"";
                $data['chatmodel'][] = $res["api2d4"];
            }
            if(isset($res['wxyy']) && $res['wxyy'] && $res['wxyy']['status']){
                $res['wxyy']["type"] = "wxyy";
                $res['wxyy']["nickname"] = $res['wxyy']["nickname"]?:"文心一言";
                $res['wxyy']["expend"] = $res['wxyy']["expend"]?:1;
                $res['wxyy']["icon"] = $res['wxyy']["icon"]??"";
                $res['wxyy']["desc"] = $res['wxyy']["desc"]??"";
                $data['chatmodel'][] = $res["wxyy"];
            }
            if(isset($res['xfxh']) && $res['xfxh'] && $res['xfxh']['status']){
                $res['xfxh']["type"] = "xfxh";
                $res['xfxh']["nickname"] = $res['xfxh']["nickname"]?:"讯飞星火";
                $res['xfxh']["expend"] = $res['xfxh']["expend"]?:1;
                $res['xfxh']["icon"] = $res['xfxh']["icon"]??"";
                $res['xfxh']["desc"] = $res['xfxh']["desc"]??"";
                $data['chatmodel'][] = $res["xfxh"];
            }
            if(isset($res['chatglm']) && $res['chatglm'] && $res['chatglm']['status']){
                $res['chatglm']["type"] = "chatglm";
                $res['chatglm']["nickname"] = $res['chatglm']["nickname"]?:"ChatGLM";
                $res['chatglm']["expend"] = $res['chatglm']["expend"]?:1;
                $res['chatglm']["icon"] = $res['chatglm']["icon"]??"";
                $res['chatglm']["desc"] = $res['chatglm']["desc"]??"";
                $data['chatmodel'][] = $res["chatglm"];
            }
            if(isset($res['tyqw']) && $res['tyqw'] && $res['tyqw']['status']){
                $res['tyqw']["type"] = "tyqw";
                $res['tyqw']["nickname"] = $res['tyqw']["nickname"]?:"tyqw";
                $res['tyqw']["expend"] = $res['tyqw']["expend"]?:1;
                $res['tyqw']["icon"] = $res['tyqw']["icon"]??"";
                $res['tyqw']["desc"] = $res['tyqw']["desc"]??"";
                $data['chatmodel'][] = $res["tyqw"];
            }
            if(isset($res['azure']) && $res['azure'] && $res['azure']['status']){
                $res['azure']["type"] = "azure";
                $res['azure']["nickname"] = $res['azure']["nickname"]?:"azure";
                $res['azure']["expend"] = $res['azure']["expend"]?:1;
                $res['txhy']["icon"] = $res['txhy']["icon"]??"";
                $res['txhy']["desc"] = $res['txhy']["desc"]??"";
                $data['chatmodel'][] = $res["azure"];
            }
            if(isset($res['minimax']) && $res['minimax'] && $res['minimax']['status']){
                $res['minimax']["type"] = "minimax";
                $res['minimax']["nickname"] = $res['minimax']["nickname"]?:"minimax";
                $res['minimax']["expend"] = $res['minimax']["expend"]?:1;
                $res['txhy']["icon"] = $res['txhy']["icon"]??"";
                $res['txhy']["desc"] = $res['txhy']["desc"]??"";
                $data['chatmodel'][] = $res["minimax"];
            }
            if(isset($res['txhy']) && $res['txhy'] && $res['txhy']['status']){
                $res['txhy']["type"] = "txhy";
                $res['txhy']["nickname"] = $res['txhy']["nickname"]?:"txhy";
                $res['txhy']["expend"] = $res['txhy']["expend"]?:1;
                $res['txhy']["icon"] = $res['txhy']["icon"]??"";
                $res['txhy']["desc"] = $res['txhy']["desc"]??"";
                $data['chatmodel'][] = $res["txhy"];
            }
            if(isset($res['kw']) && $res['kw'] && $res['kw']['status']){
                $res['kw']["type"] = "kw";
                $res['kw']["nickname"] = $res['kw']["nickname"]?:"kw";
                $res['kw']["expend"] = $res['kw']["expend"]?:1;
                $res['kw']["icon"] = $res['kw']["icon"]??"";
                $res['kw']["desc"] = $res['kw']["desc"]??"";
                $data['chatmodel'][] = $res["kw"];
            }
            if(isset($res['wxyy4']) && $res['wxyy4'] && $res['wxyy4']['status']){
                $res['wxyy4']["type"] = "wxyy4";
                $res['wxyy4']["nickname"] = $res['wxyy4']["nickname"]?:"wxyy4";
                $res['wxyy4']["expend"] = $res['wxyy4']["expend"]?:1;
                $res['wxyy4']["icon"] = $res['wxyy4']["icon"]??"";
                $res['wxyy4']["desc"] = $res['wxyy4']["desc"]??"";
                $data['chatmodel'][] = $res["wxyy4"];
            }
            if(isset($res['glm4']) && $res['glm4'] && $res['glm4']['status']){
                $res['glm4']["type"] = "glm4";
                $res['glm4']["nickname"] = $res['glm4']["nickname"]?:"glm4";
                $res['glm4']["expend"] = $res['glm4']["expend"]?:1;
                $res['glm4']["icon"] = $res['glm4']["icon"]??"";
                $res['glm4']["desc"] = $res['glm4']["desc"]??"";
                $data['chatmodel'][] = $res["glm4"];
            }

        }else{
            $res["type"] = "gpt35";
            $res["nickname"] = "gpt3.5";
            $res["expend"] = 1;
            $res["status"] = 0;
            $res["icon"] = "";
            $res["desc"] = "";
            $data['chatmodel'][] = $res;
        }

        return success('对话模型',$data);
    }

    /**
     * 分组列表
     */
    public function getGroupList()
    {
        $wid = Session::get('wid');
        $user = $this->user;
        if(!$user) return success('');

        $page = $this->req->param('page',1);
        $size = $this->req->param('size',10);
        $name = $this->req->param('name');
        $where = [];
        $where[] = ['wid','=',$wid];
        $where[] = ['common_id','=',$user['id']];
        if($name) $where[] = ['name','like',"%{$name}%"];

        $res = Db::table('kt_gptcms_chat_msg_group')->where($where);
        $data['count'] = $res->count()+1;
        $data['item'] = $res->field('id,name')->order('id desc')->page($page, $size)->select()->toArray();
        array_unshift($data['item'],['id'=>0,'name'=>'新的会话']);
        return success('分组数据',$data);
    }

    /**
     * 添加分组
     */
    public function addGroup()
    {
        $wid = Session::get('wid');
        $user = $this->user;
        if(!$user) return error('用户不存在');

        $name = $this->req->param('name');
        if(!$name) return error('会话标题不能为空');
        Db::table('kt_gptcms_chat_msg_group')->insert([
            'wid' => $wid,
            'common_id' => $user['id'],
            'name' => $name,
            'c_time' => time()
        ]);
        return success('添加成功');
    }

    /**
     * 修改分组
     */
    public function editGroup()
    {
        $id = $this->req->param('id',0);
        if($id == 0) return error('当前会话不支持修改');
        if(!$id) return error('缺少必要参数');
        $name = $this->req->param('name');
        if(!$name) return error('会话标题不能为空');
        Db::table('kt_gptcms_chat_msg_group')->where('id',$id)->update([
            'name' => $name
        ]);
        return success('更新成功');
    }

    /**
     * 历史记录
     */
    public function msgs()
    {
        $wid = Session::get('wid');
        $user = $this->user;
        if(!$user) return success('');
        $group_id = $this->req->param('group_id',0);
        $where = [];
        $where[] = ['wid','=',$wid];
        $where[] = ['common_id','=',$user['id']];
        $where[] = ['group_id','=',$group_id];

        $msgList = Db::table('kt_gptcms_chat_msg')->field('id,message,response')->where($where)->order('id asc')->select();
        $msgs = [];
        foreach ($msgList as $key => $msg) {
            $msgs[] = [
                'role' => '我',
                'content' => $msg['message']
            ];
            $msgs[] = [
                'role' => '助手',
                'content' => $msg['response']
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
        $group_id = $this->req->param('group_id',0);

        $where = [];
        $where[] = ['wid','=',$wid];
        $where[] = ['common_id','=',$user['id']];
        $where[] = ['group_id','=',$group_id];
        Db::table('kt_gptcms_chat_msg')->where($where)->delete();
        return success('操作成功，已删除');
    }

    /**
     * send
     */
    public function send()
    {
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        $wid = Session::get('wid');
        $user = $this->user;
        if($user['status'] != 1){
            $this->outError('账号因异常行为进入风控，请联系客服解除风控！error:001');
        }
        
        $chatmodel = $this->req->param('chatmodel');
        if(!$chatmodel){
            $config['channel'] = Db::table('kt_gptcms_gpt_config')->where('wid',$wid)->value('channel');
            switch ($config['channel']) {
                case 1:
                    $chatmodel = 'gpt35';
                    break;

                case 2:
                    $chatmodel = 'api2d35';
                    break;

                case 3:
                    $chatmodel = 'wxyy';
                    break;
                case 4:
                    $chatmodel = 'tyqw';
                    break;
                case 6:
                    $chatmodel = 'chatglm';
                    break;
                    
                case 7:
                    $chatmodel = 'linkerai';
                    break;

                case 8:
                    $chatmodel = 'gpt4';
                    break;

                case 9:
                    $chatmodel = 'api2d4';
                    break;
                case 10:
                    $chatmodel = 'xfxh';
                    break;
                case 11:
                    $chatmodel = 'fastgpt';
                    break;
                case 12:
                    $chatmodel = 'azure';
                    break;
                case 13:
                    $chatmodel = 'minimax';
                case 14:
                    $chatmodel = 'txhy';
                case 15:
                    $chatmodel = 'kw';
                case 16:
                    $chatmodel = 'wxyy4';
                case 17:
                    $chatmodel = 'glm4';
                default:
                    $chatmodel = 'gpt35';
                    break;
            }
        }
        $this->chatmodel = $chatmodel;
        $expend = CommonModel::getExpend('chat',$chatmodel);//获取消耗条数
        $vip = 0; //默认未开启vip
        // if(strtotime($user['vip_expire']) > time()){ //会员未到期
        //     $vip = 1;
        // }else{ //会员到期
        //     if($user['residue_degree'] < $expend){ //余数不足
        //         $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
        //         $this->outError($zdz_remind?:'剩余条数不足');
        //     }
        // }
        if(strtotime($user['vip_expire']) > time()){ //会员未到期
            $vip = 1;
        }
        if($chatmodel == 'gpt4'){
            $gpt4_charging = Db::table('kt_gptcms_system')->where(['wid'=>$wid])->value('gpt4_charging')??0;
            if($gpt4_charging){ //如果开启GPT4单独计费,不能使用vip
                $vip = 0;
            }
        }
        if(!$vip){
            if($user['residue_degree'] < $expend){ //余数不足
                $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
                $this->outError($zdz_remind?:'剩余条数不足');
            }
        }
        
        $group_id = $this->req->param('group_id',0);
        $message = $this->req->param('message');
        if(!$message){
            $this->outError('请输入您的问题');
        }
        $message = urldecode($message);
        
        
        //是否开启了key池
        $keysSwitch = Db::table('kt_gptcms_keys_switch')->where(['wid'=>$wid,'chatmodel'=>$chatmodel])->value('switch')??0;
        $apiKey = '';
        if($keysSwitch == 1){
            $apiKey = $this->getApiKey($wid,$chatmodel);
        }

        if($chatmodel == "wxyy" || $chatmodel == "wxyy4"){
            $messages = $this->getMessages($wid,$message,$user,$group_id,1); //文心一言不需要前置指令
            $callback = $this->getWxqfCallback($message,$wid,$user,$vip,$expend,$group_id);
        }else if($chatmodel == "xfxh"){
            $messages = $this->getMessages($wid,$message,$user,$group_id,1); //讯飞星火不需要前置指令
            $callback = $this->getXfxhCallback($message,$wid,$user,$vip,$expend,$group_id);
        }else if($chatmodel == "fastgpt"){
            $messages = $this->getMessages($wid,$message,$user,$group_id,1);
            $callback = $this->getFastCallback($message,$wid,$user,$vip,$expend,$group_id);
        }else if($chatmodel == "chatglm"){
            $messages = $this->getMessages($wid,$message,$user,$group_id,1);
            $callback = $this->getChatglmCallback($message,$wid,$user,$vip,$expend,$group_id);
        }else if($chatmodel == "minimax"){
            $messages = $this->getMessages($wid,$message,$user,$group_id,1);   
            $callback = $this->getMiniMaxCallback($message,$wid,$user,$vip,$expend,$group_id);
        }else if($chatmodel == "tyqw"){
            $messages = $this->getMessages($wid,$message,$user,$group_id);   
            $callback = $this->getTyqwCallback($message,$wid,$user,$vip,$expend,$group_id);
        }else if($chatmodel == "txhy"){
            $messages = $this->getMessages($wid,$message,$user,$group_id,1);   
            $callback = $this->getCallback($message,$wid,$user,$vip,$expend,$keysSwitch,$apiKey,$chatmodel,$group_id);
        }else if($chatmodel == "kw"){
            $messages = $message;
            $kwresp = '';
            $callback = $this->getKwCallback($message,$wid,$user,$vip,$expend,$group_id,$kwresp);
        }else{
            $messages = $this->getMessages($wid,$message,$user,$group_id);
            $callback = $this->getCallback($message,$wid,$user,$vip,$expend,$keysSwitch,$apiKey,$chatmodel,$group_id);
        }
        $agid = Db::table('kt_base_user')->where('id',$wid)->value('agid');
        if(!$agid){
            $agid = Db::table('kt_base_agent')->where('isadmin',1)->value('id');
        }
        // $base_config = Db::table('kt_base_gpt_config')->json(['openai','api2d'])->where('uid',$agid)->find();
        // $base_aiconfig = $base_config['openai'];
        
        $config = Db::table('kt_gptcms_gpt_config')->json(['openai','api2d','wxyy','tyqw','kltg','chatglm','linkerai','gpt4','api2d4','xfxh','fastgpt',"azure","minimax","txhy","kw","wxyy4","glm4"])->where('wid',$wid)->find();
        if($config){
            switch ($chatmodel) {
                case 'gpt35':
                    $aiconfig = $config['openai'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>1,'api_key'=>$keysSwitch == 1 ? $apiKey : $aiconfig['api_key'],'diy_host'=>$aiconfig['diy_host']]);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
                    // $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-3.5-turbo','stream'=>true]);
                    break;
                case 'gpt4':
                    $aiconfig = $config['gpt4'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>1,'api_key'=>$keysSwitch == 1 ? $apiKey : $aiconfig['api_key'],'diy_host'=>$aiconfig['diy_host']]);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
                    // $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-4','stream'=>true]);
                    break;
                
                case 'api2d35':
                    $aiconfig = $config['api2d'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>2,'api_key'=>$keysSwitch == 1 ? $apiKey : $aiconfig['forward_key'],'diy_host'=>'']);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
                    // $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-3.5-turbo','stream'=>true]);
                    break;

                case 'api2d4':
                    $aiconfig = $config['api2d4'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>2,'api_key'=>$keysSwitch == 1 ? $apiKey : $aiconfig['forward_key'],'diy_host'=>'']);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
                    // $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-4','stream'=>true]);
                    break;

                case 'linkerai':
                    $aiconfig = $config['linkerai'];
                    $ktadmin = new \Ktadmin\LinkerAi\Ktadmin(['channel'=>7,'api_key'=>$keysSwitch == 1 ? $apiKey : $aiconfig['api_key']]);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true,"network"=>false]);
                    break;
                case 'wxyy':
                    $aiconfig = $config['wxyy'];
                    $ktadmin = new \Ktadmin\Wxqf\Ktadmin($aiconfig['api_key'],$aiconfig['secret_key']);
                    $ktadmin->chat()->sendText($messages, $callback,['model'=>$aiconfig['model'] ??'','stream'=>true]);
                    break;
                case 'xfxh':  //讯飞星火认知大模型
                    $aiconfig = $config['xfxh'];
                    $ktadmin = new \Ktadmin\Xunfei\Ktadmin(['channel'=>10,'appid'=>$aiconfig['appid'],'apikey'=>$aiconfig['apikey'],'apisecret'=>$aiconfig['apisecret']]);
                    $ktadmin->chat()->sendText($messages, $callback,["version"=>$aiconfig["model"] ??'',"max_tokens"=>$aiconfig["max_tokens"]]);
                    break;
                 case 'fastgpt':  //Fastgpt
                    $aiconfig = $config['fastgpt'];
                    $ktadmin = new \app\gptcms\model\Fast($aiconfig["apikey"],$aiconfig["appid"]);
                    $ktadmin->completions($messages,$callback);
                    break;
                case 'chatglm':  //Fastgpt
                    $aiconfig = $config['chatglm'];
                    $ktadmin = new \Ktadmin\ChatGLM\Ktadmin(['channel'=>6,'api_key'=>$aiconfig['api_key']]);
                    $ktadmin->chat()->sendText($messages, $callback,["model"=>$aiconfig['model']??'']);
                    break;
                case 'azure':  //azure
                    $aiconfig = $config['azure'];
                    $ktadmin = new \Ktadmin\Azure\Ktadmin($aiconfig['api_key'],$aiconfig['diy_host']);
                    $ktadmin->chat()->sendText($messages, $callback,[],$aiconfig['model']);
                    break;
                case 'minimax':  //MiniMax
                    $aiconfig = $config['minimax'];
                    $api_key = $aiconfig['api_key'];
                    $groupid = $aiconfig['groupid'];
                    $ktadmin = new \Ktadmin\MiniMax\Ktadmin($api_key,$groupid);
                    $ktadmin->chat()->sendText($messages, $callback,[]);
                    break;
                case 'tyqw':  //同义千问
                    $aiconfig = $config['tyqw'];
                    $api_key = $aiconfig['api_key'];
                    $ktadmin = new \Ktadmin\Tyqw\Ktadmin($api_key);
                    $ktadmin->chat()->sendText($messages, $callback,["top_p"=>$aiconfig["top_p"] ?? '',"model"=>$aiconfig["model"] ?? '',"result_format"=>$aiconfig["result_format"] ?? 'message']);
                    break;
                case 'txhy':  //腾讯混元
                    $aiconfig = $config['txhy'];
                    $app_id =  $aiconfig['app_id'];
                    $secret_id = $aiconfig['secret_id'];
                    $secret_key = $aiconfig['secret_key'];
                    $ktadmin = new \Ktadmin\Txhy\Ktadmin($app_id,$secret_id,$secret_key);
                    $ktadmin->chat()->sendText($messages, $callback,["temperature"=>$aiconfig["temperature"] ?? '']);
                    break;
                case 'kw':  //腾讯混元
                    $aiconfig = $config['kw'];
                    $app_key =  $aiconfig['app_key'];
                    $ktadmin = new \Ktadmin\Kw\Ktadmin($app_key);
                    $ktadmin->chat()->sendText($messages, $callback,$aiconfig['app_id']);
                    Db::table('kt_gptcms_chat_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'group_id' => $group_id,
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $kwresp,
                        'response' => $kwresp,
                        'total_tokens' => mb_strlen($message) + mb_strlen($kwresp),
                        'c_time' => time()
                    ]);
                    //如果不是会员扣费
                    if($vip != 1){
                        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                    }
                    break;
                case 'wxyy4':
                    $aiconfig = $config['wxyy4'];
                    $ktadmin = new \Ktadmin\Wxqf\Ktadmin($aiconfig['api_key'],$aiconfig['secret_key']);
                    $ktadmin->chat()->sendText($messages, $callback,['model'=>$aiconfig['model'] ??'','stream'=>true]);
                    break;
                case 'glm4':  //Fastgpt
                    $aiconfig = $config['glm4'];
                    $ktadmin = new \Ktadmin\ChatGLM\Ktadmin(['channel'=>6,'api_key'=>$aiconfig['api_key']]);
                    $ktadmin->chat()->sendTextV4($messages, $callback,["model"=>$aiconfig['model']??'']);
                    break;
            }
        }else{
            $this->outError('未检查到配置信息');
        }
        exit();
    }

    //$isqzzl 有些渠道不需要前置指令 比如文心一言
    private function getMessages($wid,$message,$user,$group_id,$isqzzl=null)
    {
        $messages = [];
        $qzzltxt = '';
        if(!$isqzzl){
            $qzzl = DB::table("kt_gptcms_qzzl")->where("wid",$wid)->find();
            if(isset($qzzl['status']) && $qzzl['status']){
                    // $currentTime = date('Y-m-d H:i:s', time());
                    // $messages[] = [
                    //     'role' => 'system',
                    //     'content' => $qzzl['content']
                    // ];
                $qzzltxt .=  $qzzl['content'];
            }            
        }

        if($this->chatmodel == "linkerai"){
            $config = Db::table('kt_gptcms_gpt_config')->json(['linkerai'])->where('wid',$wid)->find();
            if($config && isset($config["linkerai"]["qzzl"]) && $config["linkerai"]["qzzl"]){
                // $messages = [];
                // $messages[] = [
                //         'role' => 'system',
                //         'content' => '['.$config["linkerai"]["qzzl"].']',
                //     ];
                $qzzltxt =  '['.$config["linkerai"]["qzzl"].']' . $qzzltxt;
            }
        }
        if($qzzltxt){
             $messages[] = [
                        'role' => 'system',
                        'content' => $qzzltxt,
                    ];
        }
        // 连续对话需要带着上一个问题请求接口
        $lastMsg = '';
        if($this->chatmodel != "linkerai"){
            if($message == '继续' || $message == 'go on'){
                $lastMsg = Db::table('kt_gptcms_chat_msg')->where([
                    ['wid', '=', $wid],
                    ['common_id', '=', $user['id']],
                    ['group_id', '=', $group_id],
                ])->order('id desc')->find();
            }else{
                $now = time();
                $lastMsg = Db::table('kt_gptcms_chat_msg')->where([
                    ['wid', '=', $wid],
                    ['common_id', '=', $user['id']],
                    ['group_id', '=', $group_id],
                    ['c_time', '>', ($now - 300)]
                ])->order('id desc')->find();
            }
        }
        
        // 如果超长，就不关联上下文了
        if ($lastMsg && (mb_strlen($lastMsg['message']) + mb_strlen($lastMsg['un_response']) + mb_strlen($message) < 3800)) {
            $messages[] = [
                'role' => 'user',
                'content' => $lastMsg['message']
            ];
            $messages[] = [
                'role' => 'assistant',
                'content' => $lastMsg['un_response']
            ];
        }
        $messages[] = [
            'role'=>'user',
            'content'=>$message
        ];
        return $messages;
    }
    private function getXfxhCallback($message,$wid,$user,$vip,$expend,$group_id)
    {
         //返回的文字
        $response = ''; 
        $un_response = '';
        $callback = function($data) use ($message,$wid,$user,$vip,$expend,$group_id) {
            global $response;
            global $un_response;
            $dataLength = strlen($data);
            $un_word = $data;
            $word = str_replace("\n", '<br/>', $un_word);
            // $word = str_replace(" ", '&nbsp;', $word);
            if($un_word=="data: [DONE]"){
                $un_word = str_replace("data: [DONE]","",$un_word);
                $word = str_replace("data: [DONE]","",$word);
                $un_response .= $un_word;
                $response .= $word;
                if (!empty($un_response)) {
                    Db::table('kt_gptcms_chat_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'group_id' => $group_id,
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $un_response,
                        'response' => $response,
                        'total_tokens' => mb_strlen($message) + mb_strlen($un_response),
                        'c_time' => time()
                    ]);
                    //如果不是会员扣费
                    if($vip != 1){
                        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                    }
                    $un_response = '';
                    $response = '';
                }
                echo "data:".$word."\n\n";ob_flush();flush();
            }else{
                $un_response .= $un_word;
                $response .= $word;
                echo "data:".$word."\n\n";ob_flush();flush();
            }
            
            return $dataLength;
        };
        return $callback;
    }
    private function getMiniMaxCallback($message,$wid,$user,$vip,$expend,$group_id)
    {
        //返回的文字
        $response = ''; 
        $un_response = '';
        //不完整的数据
        $imperfect = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$expend,$group_id) {
            global $response;
            global $un_response;
            global $imperfect;
            $dataLength = strlen($data);
            // echo $data;

            $complete = @json_decode($data,true);
            if(isset($complete['base_resp']) && $complete['base_resp']['status_code'] != 0){
                $this->outError($complete['base_resp']['status_msg']);
            }
            
            //如果存在不完整的数据
            if($imperfect){
                $data = $imperfect . $data;
                $imperfect = '';
            }else{
                if (substr($data, -1) !== "\n") {
                    $imperfect = $data;
                    return $dataLength;
                }
            }

            $un_word = parseMiniMaxData($data);
            $word = str_replace("\n", '<br/>', $un_word);
            $word = str_replace(" ", '&nbsp;', $word);
            if($complete){
                //一次性完整输出
            }else{
                //流式
                if(strpos($un_word, 'data: [DONE]') !== false){
                    $un_word = str_replace("data: [DONE]","",$un_word);
                    $word = str_replace("data:&nbsp;[DONE]","",$word);
                    $un_response .= $un_word;
                    $response .= $word;
                    Db::table('kt_gptcms_chat_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'group_id' => $group_id,
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $un_response,
                        'response' => $response,
                        'total_tokens' => mb_strlen($message) + mb_strlen($un_word),
                        'c_time' => time()
                    ]);
                    //如果不是会员扣费
                    if($vip != 1){
                        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                    }
                    echo "data:".$word."\n\n";ob_flush();flush();
                }else{
                    $un_response .= $un_word;
                    $response .= $word;
                    echo "data:".$word."\n\n";ob_flush();flush();
                }
            }
            return $dataLength;
        };
        return $callback;
    }
    private function getTyqwCallback($message,$wid,$user,$vip,$expend,$group_id)
    {
        //返回的文字
        $response = ''; 
        $un_response = '';

        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$expend,$group_id) {
            global $response;
            global $un_response;
            $dataLength = strlen($data);
            // echo $data;

            $complete = @json_decode($data,true);
            if(isset($complete['code'])){
                $this->outError($complete['message']);
            }
            
            $un_word = parseTyqwData($data);
            if($un_response){
                $word = substr($un_word,strlen($un_response));
            }else{
                $word = $un_word;
            }
            $word = str_replace("\n", '<br/>', $word);
            $word = str_replace(" ", '&nbsp;', $word);
            if($complete){
                //一次性完整输出
            }else{
                //流式
                if(strpos($un_word, 'data: [DONE]') !== false){
                    $un_word = str_replace("data: [DONE]","",$un_word);
                    $word = str_replace("data:&nbsp;[DONE]","",$word);
                    $un_response = $un_word;
                    $response .= $word;
                    Db::table('kt_gptcms_chat_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'group_id' => $group_id,
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $un_word,
                        'response' => $word,
                        'total_tokens' => mb_strlen($message) + mb_strlen($un_word),
                        'c_time' => time()
                    ]);
                    //如果不是会员扣费
                    if($vip != 1){
                        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                    }
                    echo "data:".$word."\n\n";ob_flush();flush();
                }else{
                    $un_response = $un_word;
                    $response .= $word;
                    echo "data:".$word."\n\n";ob_flush();flush();
                }
            }
            return $dataLength;
        };
        return $callback;
    }
    private function getWxqfCallback($message,$wid,$user,$vip,$expend,$group_id)
    {
         //返回的文字
        $response = ''; 
        $un_response = '';
        //不完整的数据
        $imperfect = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$expend,$group_id) {
            global $response;
            global $un_response;
            global $imperfect;
            $dataLength = strlen($data);
            $complete = @json_decode($data);
            if(isset($complete->error_code)){
                $this->outError($complete->error_msg);
            }
            
             //如果存在不完整的数据
            if($imperfect){
                $data = $imperfect . $data;
                $imperfect = '';
            }else{
                if (substr($data, -1) !== "\n") {
                    $imperfect = $data;
                    return $dataLength;
                }
            }
            // echo $data;
            $un_word = wxqfParseData($data);
            $word = str_replace("\n", '<br/>', $un_word);
            // $word = str_replace(" ", '&nbsp;', $word);
            if($complete){//一次性完整输出
                if (!empty($un_word)) {
                    Db::table('kt_gptcms_chat_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'group_id' => $group_id,
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $un_word,
                        'response' => $word,
                        'total_tokens' => mb_strlen($message) + mb_strlen($un_word),
                        'c_time' => time()
                    ]);
                    //如果不是会员扣费
                    if($vip != 1){
                        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                    }
                    echo "data:".$word."\n\n";
                }
                ob_flush();flush();
            }else{//流式
                if(strpos($un_word, 'data: [DONE]')){
                    $un_word = str_replace("data: [DONE]","",$un_word);
                    $word = str_replace("data: [DONE]","",$word);
                    $un_response .= $un_word;
                    $response .= $word;
                    if (!empty($un_response)) {
                        Db::table('kt_gptcms_chat_msg')->insert([
                            'wid' => $wid,
                            'common_id' => $user['id'],
                            'group_id' => $group_id,
                            'un_message' => $message,
                            'message' => $message,
                            'un_response' => $un_response,
                            'response' => $response,
                            'total_tokens' => mb_strlen($message) + mb_strlen($un_response),
                            'c_time' => time()
                        ]);
                        //如果不是会员扣费
                        if($vip != 1){
                            Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                        }
                        $un_response = '';
                        $response = '';
                    }
                    echo "data:".$word."\n\n";ob_flush();flush();
                }else{
                    $un_response .= $un_word;
                    $response .= $word;
                    echo "data:".$word."\n\n";ob_flush();flush();
                }
            }
            return $dataLength;
        };
        return $callback;
    }
    private function getChatglmCallback($message,$wid,$user,$vip,$expend,$group_id)
    {
         //返回的文字
        $response = ''; 
        $un_response = '';
        //不完整的数据
        $imperfect = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$expend,$group_id) {
            global $response;
            global $un_response;
            $dataLength = strlen($data);
            $str = '';
            $a = explode("\n",$data);
            foreach ($a as $k => $v) {
                $strtem = '';
                if($v === "event:finish"){
                    $str = $str . "  data: [DONE]";
                    break;
                }
                if(preg_match("/^data:/", $v)){
                    $strtem = substr($v,5);
                    if(!trim($strtem) && isset($a[$k+1]) && preg_match("/^data:/", $a[$k+1])) $strtem = "<br/>";
                    $str = $str . $strtem;
                }
            }
            // $un_word = $str;
            $word = $un_word = $str;
            // $word = str_replace("\n", '<br/>', $un_word);
            if(strpos($un_word, 'data: [DONE]')){
                $un_word = str_replace("data: [DONE]","",$un_word);
                $word = str_replace("data: [DONE]","",$word);
                $un_response .= $un_word;
                $response .= $word;
                if (!empty($un_response)) {
                    Db::table('kt_gptcms_chat_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'group_id' => $group_id,
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $un_response,
                        'response' => $response,
                        'total_tokens' => mb_strlen($message) + mb_strlen($un_response),
                        'c_time' => time()
                    ]);
                    //如果不是会员扣费
                    if($vip != 1){
                        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                    }
                    $un_response = '';
                    $response = '';
                }
                echo "data:".$word."\n\n";ob_flush();flush();
            }else{
                $un_response .= $un_word;
                $response .= $word;
                echo "data:".$word."\n\n";ob_flush();flush();
            }
            
            return $dataLength;
        };
        return $callback;
    }
    private function getFastCallback($message,$wid,$user,$vip,$expend,$group_id)
    {
         //返回的文字
        $response = ''; 
        $un_response = '';
        //不完整的数据
        $imperfect = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$expend,$group_id) {
            global $response;
            global $un_response;
            $dataLength = strlen($data);
            $complete = @json_decode($data);
            if(isset($complete->error_code)){
                $this->outError($complete->error_msg);
            }
            
            $un_word = parseData($data);
            $word = str_replace("\n", '<br/>', $un_word);
            // $word = str_replace(" ", '&nbsp;', $word);
            if($complete){//一次性完整输出
                if (!empty($un_word)) {
                    Db::table('kt_gptcms_chat_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'group_id' => $group_id,
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $un_word,
                        'response' => $word,
                        'total_tokens' => mb_strlen($message) + mb_strlen($un_word),
                        'c_time' => time()
                    ]);
                    //如果不是会员扣费
                    if($vip != 1){
                        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                    }
                    echo "data:".$word."\n\n";
                }
                ob_flush();flush();
            }else{//流式
                if($un_word=="data: [DONE]"){
                    $un_word = str_replace("data: [DONE]","",$un_word);
                    $word = str_replace("data: [DONE]","",$word);
                    $un_response .= $un_word;
                    $response .= $word;
                    if (!empty($un_response)) {
                        Db::table('kt_gptcms_chat_msg')->insert([
                            'wid' => $wid,
                            'common_id' => $user['id'],
                            'group_id' => $group_id,
                            'un_message' => $message,
                            'message' => $message,
                            'un_response' => $un_response,
                            'response' => $response,
                            'total_tokens' => mb_strlen($message) + mb_strlen($un_response),
                            'c_time' => time()
                        ]);
                        //如果不是会员扣费
                        if($vip != 1){
                            Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                        }
                        $un_response = '';
                        $response = '';
                    }
                    echo "data:".$word."\n\n";ob_flush();flush();
                }else{
                    $un_response .= $un_word;
                    $response .= $word;
                    echo "data:".$word."\n\n";ob_flush();flush();
                }
            }
            return $dataLength;
        };
        return $callback;
    }
    private function  getKwCallback($message,$wid,$user,$vip,$expend,$group_id,&$kwresp)
    {
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$expend,$group_id,&$kwresp) {
            $dataLength = strlen($data);   
            // echo $data;
            //根据key池是否开启处理报错
            $un_word = str_replace('data:', '', $data);
            $word = str_replace("\n", '', $un_word);
            $kwresp .= $word;
            echo "data:".$word."\n\n";ob_flush();flush();
            return $dataLength;
        };
        return $callback;
    }
    private function getCallback($message,$wid,$user,$vip,$expend,$keysSwitch,$apiKey,$chatmodel,$group_id)
    {
        //返回的文字
        $response = ''; 
        $un_response = '';
        //不完整的数据
        $imperfect = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$expend,$keysSwitch,$apiKey,$chatmodel,$group_id) {
            global $response;
            global $un_response;
            global $imperfect;
            $dataLength = strlen($data);
            // echo $data;
            //根据key池是否开启处理报错
            $complete = @json_decode($data);
            if($keysSwitch == 1){
                $this->handleError($wid, $chatmodel, $data, $apiKey);
            }else{
                if(isset($complete->error)){
                    $this->outError($complete->error->message?:$complete->error->code);
                }elseif(@$complete->object == 'error'){
                    $this->outError($complete->message);
                }
            }
            
            //如果存在不完整的数据
            if($imperfect){
                $data = $imperfect . $data;
                $imperfect = '';
            }else{
                if (substr($data, -1) !== "\n") {
                    $imperfect = $data;
                    return $dataLength;
                }
            }

            $un_word = parseData($data);
            $word = str_replace("\n", '<br/>', $un_word);
            $word = str_replace(" ", '&nbsp;', $word);
            if($complete){//一次性完整输出
                if (!empty($un_word)) {
                    Db::table('kt_gptcms_chat_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'group_id' => $group_id,
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $un_word,
                        'response' => $word,
                        'total_tokens' => mb_strlen($message) + mb_strlen($un_word),
                        'c_time' => time()
                    ]);
                    //如果不是会员扣费
                    if($vip != 1){
                        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                    }
                    echo "data:".$word."\n\n";
                }
                ob_flush();flush();
            }else{//流式
                if(strpos($un_word, 'data: [DONE]') !== false){
                    $un_word = str_replace("data: [DONE]","",$un_word);
                    $word = str_replace("data:&nbsp;[DONE]","",$word);
                    $un_response .= $un_word;
                    $response .= $word;
                    if (!empty($un_response)) {
                        Db::table('kt_gptcms_chat_msg')->insert([
                            'wid' => $wid,
                            'common_id' => $user['id'],
                            'group_id' => $group_id,
                            'un_message' => $message,
                            'message' => $message,
                            'un_response' => $un_response,
                            'response' => $response,
                            'total_tokens' => mb_strlen($message) + mb_strlen($un_response),
                            'c_time' => time()
                        ]);
                        //如果不是会员扣费
                        if($vip != 1){
                            Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                        }
                        $un_response = '';
                        $response = '';
                    }
                    echo "data:".$word."\n\n";ob_flush();flush();
                }else{
                    $un_response .= $un_word;
                    $response .= $word;
                    echo "data:".$word."\n\n";ob_flush();flush();
                }
            }
            return $dataLength;
        };
        return $callback;
    }

    private function getApiKey($wid,$chatmodel)
    {
        $res = Db::table('kt_gptcms_keys')->where(['wid'=>$wid,'chatmodel'=>$chatmodel,'state'=>1])->order('utime asc, id asc')->find();
        if(!$res){
            $this->outError('无可用的key');
        }
        Db::table('kt_gptcms_keys')->where('id',$res['id'])->update(['utime'=>time()]);
        return $res['key'];
    }

    private function handleError($wid, $chatmodel, $data, $apiKey)
    {
        $errorMsg = null;
        if($chatmodel == 'api2d35' || $chatmodel == 'api2d4'){
            $data = @json_decode($data);
            if (isset($data->object) && $data->object == 'error') {
                $errorMsg = $this->formatErrorMsg($chatmodel, $data);
            }
        }else{
            $data = @json_decode($data);
            if (!empty($data) && isset($data->error)) {
                $errorMsg = $this->formatErrorMsg($chatmodel, $data->error);
            }
        }
        if($errorMsg){
            //如果key有问题停用key，继续使用下一个key
            if ($errorMsg['level'] == 'error') {
                $this->setKeyStop($wid, $chatmodel, $apiKey, $errorMsg['message']);
                $this->send();
                exit;
            }
            $this->outError($errorMsg['message']);
        }
    }

    private function setKeyStop($wid, $chatmodel, $apiKey, $errorMsg)
    {
        if($errorMsg){
            Db::table('kt_gptcms_keys')->where(['wid'=>$wid,'chatmodel'=>$chatmodel,'key'=>$apiKey])->update(['state'=>0,'stop_reason'=>$errorMsg,'utime'=>time()]);
        }
    }

    private function formatErrorMsg($chatmodel, $error)
    {
        $level = 'warning';
        $errorMsg = $error->message;
        if($chatmodel == 'api2d35' || $chatmodel == 'api2d4'){
            if (strpos($errorMsg, 'Not enough point') !== false) {
                $level = 'error';
                $errorMsg = 'key余额不足。' . $errorMsg;
            } elseif (strpos($errorMsg, 'bad forward key') !== false) {
                $level = 'error';
                $errorMsg = 'key不正确。' . $errorMsg;
            }
        }else{
            if (isset($error->code) && $error->code == 'invalid_api_key') {
                $level = 'error';
                $errorMsg = 'key不正确';
            } else {
                if (strpos($errorMsg, 'Incorrect API key provided') !== false) {
                    $level = 'error';
                    $errorMsg = 'key不正确。' . $errorMsg;
                } elseif (strpos($errorMsg, 'deactivated account') !== false) {
                    $level = 'error';
                    $errorMsg = 'key账号被封。' . $errorMsg;
                } elseif (strpos($errorMsg, 'exceeded your current quota') !== false) {
                    $level = 'error';
                    $errorMsg = 'key余额不足。' . $errorMsg;
                }
            }
        }
        return [
            'level' => $level,
            'message' => $errorMsg
        ];
    }

    private function outError($msg)
    {
        echo 'data:[error]' . $msg . '\n\n';
        ob_flush();
        flush();
        exit;
    }

    /**
     * xcxsend
     */
    public function xcxsend()
    {
        // 设置响应头信息
        header('Access-Control-Allow-Credentials: true');
        // 设置响应头信息
        header('Transfer-Encoding: chunked');
        header('Cache-Control: no-cache');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        $wid = Session::get('wid');
        $user = $this->user;
        if($user['status'] != 1){
            $this->outError('账号因异常行为进入风控，请联系客服解除风控！error:001.1');
        }
        // $chatmodel = $this->req->param('chatmodel');
        // if(!$chatmodel){
        //     $this->outError('请先选择对话模型');
        // }
        
        $vip = 0;
        if(strtotime($user['vip_expire']) > time()){ //会员未到期
            $vip = 1;
        }else{ //会员到期
            if($user['residue_degree'] <= 0){ //余数不足
                $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
                $this->outError($zdz_remind?:'剩余条数不足');
            }
        }
        
        $message = $this->req->param('message');
        if(!$message){
            $this->outError('请输入您的问题');
        }
        $group_id = $this->req->param('group_id',0);
        $qzzl = DB::table("kt_gptcms_qzzl")->where("wid",$wid)->find();
        $messages = [];
        if(isset($qzzl['status']) && $qzzl['status']){
                // $currentTime = date('Y-m-d H:i:s', time());
                $messages[] = [
                    'role' => 'system',
                    'content' => $qzzl['content']
                ];
        }
        
        // 连续对话需要带着上一个问题请求接口
        if($message == '继续' || $message == 'go on'){
            $lastMsg = Db::table('kt_gptcms_chat_msg')->where([
                ['wid', '=', $wid],
                ['common_id', '=', $user['id']]
            ])->order('id desc')->find();
        }else{
            $now = time();
            $lastMsg = Db::table('kt_gptcms_chat_msg')->where([
                ['wid', '=', $wid],
                ['common_id', '=', $user['id']],
                ['c_time', '>', ($now - 300)]
            ])->order('id desc')->find();
        }
        // 如果超长，就不关联上下文了
        if ($lastMsg && (mb_strlen($lastMsg['message']) + mb_strlen($lastMsg['un_response']) + mb_strlen($message) < 3800)) {
            $messages[] = [
                'role' => 'user',
                'content' => $lastMsg['message']
            ];
            $messages[] = [
                'role' => 'assistant',
                'content' => $lastMsg['un_response']
            ];
        }
        $messages[] = [
            'role'=>'user',
            'content'=>$message
        ];

        //返回的文字
        $response = ''; 
        $un_response = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip) {
            //$ktadmin = new \Ktadmin\Chatgpt\Ktadmin();
            global $response;
            global $un_response;
            $complete = @json_decode($data);
            if(isset($complete->error)){
                $this->outError($complete->error->message?:$complete->error->code);
            }elseif(@$complete->object == 'error'){
                $this->outError($complete->message);
            }
            $un_word = parseData($data);
            $word = str_replace("\n", '<br/>', $un_word);
            $word = str_replace(" ", '&nbsp;', $word);
            if($complete){//一次性完整输出
                if (!empty($un_word)) {
                    Db::table('kt_gptcms_chat_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'un_message' => $message,
                        'message' => $message,
                        'un_response' => $un_word,
                        'response' => $word,
                        'total_tokens' => mb_strlen($message) + mb_strlen($un_word),
                        'c_time' => time()
                    ]);
                    //如果不是会员扣费
                    if($vip != 1){
                        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree')->update();
                    }
                    echo "data:".$word."\r\n";
                }
                ob_flush();flush();
            }else{//流式
                if(strpos($un_word, 'data: [DONE]') !== false){
                    $un_word = str_replace("data: [DONE]","",$un_word);
                    $word = str_replace("data:&nbsp;[DONE]","",$word);
                    $un_response .= $un_word;
                    $response .= $word;
                    if (!empty($un_response)) {
                        Db::table('kt_gptcms_chat_msg')->insert([
                            'wid' => $wid,
                            'common_id' => $user['id'],
                            'un_message' => $message,
                            'message' => $message,
                            'un_response' => $un_response,
                            'response' => $response,
                            'total_tokens' => mb_strlen($message) + mb_strlen($un_response),
                            'c_time' => time()
                        ]);
                        //如果不是会员扣费
                        if($vip != 1){
                            Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree')->update();
                        }
                        $un_response = '';
                        $response = '';
                    }
                    echo dechex(strlen($word))."\r\n".$word."\r\n";echo "0\r\n\r\n";ob_flush();flush();
                }else{
                    $un_response .= $un_word;
                    $response .= $word;
                    echo dechex(strlen($word))."\r\n".$word."\r\n";ob_flush();flush();
                }
            }
            return strlen($data);
        };

        $agid = Db::table('kt_base_user')->where('id',$wid)->value('agid');
        if(!$agid){
            $agid = Db::table('kt_base_agent')->where('isadmin',1)->value('id');
        }
        $base_config = Db::table('kt_base_gpt_config')->json(['openai','api2d'])->where('uid',$agid)->find();
        $base_aiconfig = $base_config['openai'];

        $config = Db::table('kt_gptcms_gpt_config')->json(['openai','api2d','linkerai'])->where('wid',$wid)->find();
        if($config){
            if($config['channel'] == 2){
                $aiconfig = $config['api2d'];
                $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>2,'api_key'=>$aiconfig['forward_key'],'diy_host'=>'']);
                $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
            }elseif($config['channel'] == 7){
                $aiconfig = $config['linkerai'];
                $ktadmin = new \Ktadmin\LinkerAi\Ktadmin(['channel'=>7,'api_key'=>$aiconfig['api_key']]);
                $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
            }else{
                $aiconfig = $config['openai'];
                $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>1,'api_key'=>$aiconfig['api_key'],'diy_host'=>$base_aiconfig['diy_host']]);
                $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
            }
            // switch ($chatmodel) {
            //     case 'gpt3.5':
            //         $aiconfig = $config['openai'];
            //         $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>1,'api_key'=>$aiconfig['api_key'],'diy_host'=>$base_aiconfig['diy_host']]);
            //         $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-3.5-turbo','stream'=>true]);
            //         break;
                
            //     case 'gpt4':
            //         $aiconfig = $config['openai'];
            //         $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>1,'api_key'=>$aiconfig['api_key'],'diy_host'=>$base_aiconfig['diy_host']]);
            //         $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-4','stream'=>true]);
            //         break;

            //     case 'linkerai':
            //         $aiconfig = $config['linkerai'];
            //         $ktadmin = new \Ktadmin\LinkerAi\Ktadmin(['channel'=>7,'api_key'=>$aiconfig['api_key']]);
            //         $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
            //         break;
            // }
        }else{
            $this->outError('未检查到配置信息');
        }
        exit();
    }
}

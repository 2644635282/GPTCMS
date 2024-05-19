<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use app\gptcms\model\CommonModel;
use think\facade\Db;
use think\facade\Session;

class RoleChat extends BaseApi
{
    /**
     * 分类
     */
    public function modelcy()
    {
        $wid = Session::get('wid');
        $res = Db::table("kt_gptcms_jmodel_classify")->field('id,title,sort')->where('wid',$wid)->order('sort','desc')->select();
        return success('分类',$res);
    }

    /**
     * 创作模型
     */
    public function models()
    {
        $wid = Session::get('wid');
        $page = $this->req->param('page')?:1;
        $size = $this->req->param("size")?:10;
        $classify_id = $this->req->param('classify_id');
        $title = $this->req->param('title','');
        $res = Db::table("kt_gptcms_jmodel")->where(['wid'=>$wid,'status'=>1])->field('id,title,tp_url,desc,bz,xh,vip_status,status,c_time,classify_id,content,hint_content,defalut_question,defalut_reply');

        if($classify_id) $res->where('classify_id',$classify_id);
        if($title) $res->where('title','like',"%{$title}%");

        $data = [];
        $data['page'] = $page;
        $data['size'] = $size;
        $data['count'] = $res->count();
        $data['item'] = $res->order('xh','desc')->filter(function($r){
            $r['classify'] = Db::table("kt_gptcms_jmodel_classify")->value('title');
            return $r;
        })->select();

        return success('模型列表',$data);
    }

    /**
     * 模型详情
     */
    public function modeldl()
    {
        $wid = Session::get('wid');
        $id = $this->req->param('id');
        if(!$id) return error('请选择模型');
        $res = Db::table("kt_gptcms_jmodel")->field('id,title,tp_url,desc,bz,xh,vip_status,status,c_time,classify_id,content,hint_content,defalut_question,defalut_reply')->where('wid',$wid)->find($id);

        return success('模型详情',$res);
    }

    /**
     * 模型是否可用
     */
    public function isHave()
    {
        $wid = Session::get('wid');
        $model_id = $this->req->param('model_id');
        $user = $this->user;
        if(!$model_id) return error('请选择模型',['have'=>0]);
        if(!$user) return error('用户不存在',['have'=>0]);

        $jmodel = Db::table('kt_gptcms_jmodel')->find($model_id);
        if(!$jmodel) return error('模型不存在',['have'=>0]);
        if($jmodel['status'] != 1) return error('模型不可用',['have'=>0]);

        $vip = 0;
        if(strtotime($user['vip_expire']) > time()){ //会员未到期
            $vip = 1;
        }
        //是VIP模型的，仅VIP能使用
        if($jmodel['vip_status'] == 1){
            if($vip == 0) return error('当前模型仅VIP可用',['have'=>0]);
        }
        return success('可以使用',['have'=>1]);
    }

    /**
     * 历史记录
     */
    public function msgs()
    {
        $wid = Session::get('wid');
        $user = $this->user;
        $model_id = $this->req->param('model_id');
        if(!$model_id) return error('请选择模型');
        $where = [];
        $where[] = ['wid','=',$wid];
        $where[] = ['common_id','=',$user['id']];
        $where[] = ['model_id','=',$model_id];

        $msgList = Db::table('kt_gptcms_role_msg')->field('id,message,response')->where($where)->order('id asc')->select();
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
        $model_id = $this->req->param('model_id');
        if(!$user) return error('用户不存在');
        if(!$model_id) return error('请选择模型');

        Db::table('kt_gptcms_role_msg')->where(['wid'=>$wid,'common_id'=>$user['id'],'model_id'=>$model_id])->delete();
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
            $this->outError('账号因异常行为进入风控，请联系客服解除风控！error:002');
        }
        
        $model_id = $this->req->param('model_id');
        $jmodel = Db::table('kt_gptcms_jmodel')->find($model_id);
        if(!$jmodel) $this->outError('模型不存在');
        if($jmodel['status'] != 1) $this->outError('模型不可用');

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
                // case 15:
                //     $chatmodel = 'kw';
                default:
                    $chatmodel = 'gpt35';
                    break;
            }
        }
        $expend = CommonModel::getExpend('chat',$chatmodel);//获取消耗条数
        $vip = 0;
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
        //是VIP模型的，仅VIP能使用
        if($jmodel['vip_status'] == 1){
            if($vip == 0) $this->outError('当前模型仅VIP可用');
        }
        
        $gpt4_charging = 0;
        if($chatmodel == 'gpt4'){
            $gpt4_charging = Db::table('kt_gptcms_system')->where(['wid'=>$wid])->value('gpt4_charging')??0;
            if($gpt4_charging){ //如果开启GPT4单独计费,不能使用vip
                $vip = 0;
            }
        }
        if(!$vip || $gpt4_charging){
            if($user['residue_degree'] < $expend){ //余数不足
                $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
                $this->outError($zdz_remind?:'剩余条数不足');
            }
        }
        
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

        if($chatmodel == "wxyy"){
            $messages = $this->getMessages($wid,$jmodel,$message,$user,$model_id,$chatmodel,1); //文心一言不需要前置指令
            $callback = $this->getWxqfCallback($message,$wid,$user,$vip,$jmodel,$expend);
        }else if($chatmodel == "xfxh"){
            $messages = $this->getMessages($wid,$jmodel,$message,$user,$model_id,$chatmodel,1);
            $callback = $this->getXfxhCallback($message,$wid,$user,$vip,$jmodel,$expend);
        }else if($chatmodel == "fastgpt"){
            $messages = $this->getMessages($wid,$jmodel,$message,$user,$model_id,$chatmodel,1);
            $callback = $this->getFastCallback($message,$wid,$user,$vip,$jmodel,$expend);
        }else if($chatmodel == "chatglm"){
            $messages = $this->getMessages($wid,$jmodel,$message,$user,$model_id,$chatmodel,1);
            $callback = $this->getChatglmCallback($message,$wid,$user,$vip,$jmodel,$expend);
        }else if($chatmodel == "txhy"){
            $messages = $this->getMessages($wid,$jmodel,$message,$user,$model_id,$chatmodel,1);   
            $callback = $this->getCallback($message,$wid,$user,$vip,$jmodel,$expend,$keysSwitch,$apiKey,$chatmodel);
        }else if($chatmodel == "minimax"){ 
            $messages = $this->getMessages($wid,$jmodel,$message,$user,$model_id,$chatmodel,1);
            $callback = $this->getMiniMaxCallback($message,$wid,$user,$vip,$jmodel,$expend);
        }else if($chatmodel == "tyqw"){
            $messages = $this->getMessages($wid,$jmodel,$message,$user,$model_id,$chatmodel,1);
            $callback = $this->getTyqwCallback($message,$wid,$user,$vip,$jmodel,$expend);
        }else{
            $messages = $this->getMessages($wid,$jmodel,$message,$user,$model_id,$chatmodel);
            $callback = $this->getCallback($message,$wid,$user,$vip,$jmodel,$expend,$keysSwitch,$apiKey,$chatmodel);
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
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>9,'api_key'=>$keysSwitch == 1 ? $apiKey : $aiconfig['forward_key'],'diy_host'=>'']);
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
            }
        }else{
            $this->outError('未检查到配置信息');
        }
        exit();
    }

    private function getMessages($wid,$jmodel,$message,$user,$model_id,$chatmodel,$isqzzl=null)
    {
        // if(!$isqzzl){
        //     $qzzl = DB::table("kt_gptcms_qzzl")->where("wid",$wid)->find();
        //     $messages = [];
        //     if(isset($qzzl['status']) && $qzzl['status']){
        //             // $currentTime = date('Y-m-d H:i:s', time());
        //             $messages[] = [
        //                 'role' => 'system',
        //                 'content' => $qzzl['content']
        //             ];
        //     }            
        // }
        $messages = [];
        $qzzltxt = '';
        //模型指令
        if($chatmodel == "linkerai" && $jmodel["qzzl"]){
            $qzzltxt =  '['.$jmodel["qzzl"].']';
            // $messages = [];
            // $messages[] = [
            //         'role' => 'system',
            //         'content' => '['.$jmodel["qzzl"].']',
            //     ];
        }
        $qzzltxt .= $jmodel['content'];
        if($chatmodel == "xfxh"){
            $messages[] = [
                'role' => 'user',
                'content' => $qzzltxt
            ];
        }else if($chatmodel == "chatglm" || $chatmodel == "txhy" || $chatmodel == "wxyy"){
            $messages[] = [
                'role'=>'user',
                'content'=>$qzzltxt
            ];
            $messages[] = [
                'role' => 'assistant',
                'content' => '好的'
            ];
        }else{

            $messages[] = [
                'role' => 'system',
                'content' => $qzzltxt
            ];
        }
        
        // 连续对话需要带着上一个问题请求接口
        if($message == '继续' || $message == 'go on'){
            $lastMsg = Db::table('kt_gptcms_role_msg')->where([
                ['wid', '=', $wid],
                ['common_id', '=', $user['id']],
                ['model_id', '=', $model_id]
            ])->order('id desc')->find();
        }else{
            $now = time();
            $lastMsg = Db::table('kt_gptcms_role_msg')->where([
                ['wid', '=', $wid],
                ['common_id', '=', $user['id']],
                ['model_id', '=', $model_id],
                ['c_time', '>', ($now - 300)]
            ])->order('id desc')->find();
        }
        // 如果超长，就不关联上下文了
        if ($lastMsg && (mb_strlen($jmodel['content']) + mb_strlen($lastMsg['message']) + mb_strlen($lastMsg['un_response']) + mb_strlen($message) < 3800)) {
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
    private function getMiniMaxCallback($message,$wid,$user,$vip,$jmodel,$expend)
    {
        //返回的文字
        $response = ''; 
        $un_response = '';
        //不完整的数据
        $imperfect = '';
        $callback = function($ch, $data) use ($param,$user,$getMessages) {
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
                    Db::table('kt_gptcms_role_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'model_id' => $jmodel['id'],
                        'tip_message' => $jmodel['content'],
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
    private function getTyqwCallback($message,$wid,$user,$vip,$jmodel,$expend)
    {
        //返回的文字
        $response = ''; 
        $un_response = '';

        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$jmodel,$expend) {
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
                    Db::table('kt_gptcms_role_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'model_id' => $jmodel['id'],
                        'tip_message' => $jmodel['content'],
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
    private function getXfxhCallback($message,$wid,$user,$vip,$jmodel,$expend)
    {
        //返回的文字
        $response = ''; 
        $un_response = '';
        $callback = function($data) use ($message,$wid,$user,$vip,$jmodel,$expend) {
            global $response;
            global $un_response;
            $dataLength = strlen($data);
            $un_word = $data;
            $word = str_replace("\n", '<br/>', $un_word);

            if($un_word=="data: [DONE]"){
                $un_word = str_replace("data: [DONE]","",$un_word);
                $word = str_replace("data: [DONE]","",$word);
                $un_response .= $un_word;
                $response .= $word;
                if (!empty($un_response)) {
                    Db::table('kt_gptcms_role_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'model_id' => $jmodel['id'],
                        'tip_message' => $jmodel['content'],
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
    private function getChatglmCallback($message,$wid,$user,$vip,$jmodel,$expend)
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
                    Db::table('kt_gptcms_role_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'model_id' => $jmodel['id'],
                        'tip_message' => $jmodel['content'],
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
    private function getWxqfCallback($message,$wid,$user,$vip,$jmodel,$expend)
    {
        //返回的文字
        $response = ''; 
        $un_response = '';
        //不完整的数据
        $imperfect = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$jmodel,$expend) {
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
            $un_word = wxqfParseData($data);
            $word = str_replace("\n", '<br/>', $un_word);
            // $word = str_replace(" ", '&nbsp;', $word);
            if($complete){//一次性完整输出
                if (!empty($un_word)) {
                    Db::table('kt_gptcms_role_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'model_id' => $jmodel['id'],
                        'tip_message' => $jmodel['content'],
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
                    $word = str_replace("data: [DONE]","",$word);
                    $un_response .= $un_word;
                    $response .= $word;
                    if (!empty($un_response)) {
                        Db::table('kt_gptcms_role_msg')->insert([
                            'wid' => $wid,
                            'common_id' => $user['id'],
                            'model_id' => $jmodel['id'],
                            'tip_message' => $jmodel['content'],
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
    private function getFastCallback($message,$wid,$user,$vip,$jmodel,$expend)
    {
        //返回的文字
        $response = ''; 
        $un_response = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$jmodel,$expend) {
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
                    Db::table('kt_gptcms_role_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'model_id' => $jmodel['id'],
                        'tip_message' => $jmodel['content'],
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
                        Db::table('kt_gptcms_role_msg')->insert([
                            'wid' => $wid,
                            'common_id' => $user['id'],
                            'model_id' => $jmodel['id'],
                            'tip_message' => $jmodel['content'],
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
    private function getCallback($message,$wid,$user,$vip,$jmodel,$expend,$keysSwitch,$apiKey,$chatmodel)
    {
        //返回的文字
        $response = ''; 
        $un_response = '';
        //不完整的数据
        $imperfect = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$jmodel,$expend,$keysSwitch,$apiKey,$chatmodel) {
            global $response;
            global $un_response;
            global $imperfect;
            $dataLength = strlen($data);

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
                    Db::table('kt_gptcms_role_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'model_id' => $jmodel['id'],
                        'tip_message' => $jmodel['content'],
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
                        Db::table('kt_gptcms_role_msg')->insert([
                            'wid' => $wid,
                            'common_id' => $user['id'],
                            'model_id' => $jmodel['id'],
                            'tip_message' => $jmodel['content'],
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
}

<?php
namespace app\gptcms\controller\api;

use think\facade\Db;
use think\worker\Server;
use think\facade\Session;
use app\gptcms\model\CommonModel;

class Worker extends Server
{
	protected $socket = 'websocket://0.0.0.0:2346';

    public function onConnect($connection)
    {
        echo "Connected，ID：" . $connection->id . "\n";
    }

    public function onMessage($connection, $data)
    {
        $data = @json_decode($data, true);
        if (!is_array($data)) {
            return '';
        }
        $wid = $data['wid'] ?? '';
        $token = $data['token'] ?? '';
        if(!$wid){
            $connection->send('[error]缺少必要参数wid');return '';
        }
        Session::set('wid',$wid);
        if(!$token){
            $connection->send('[error]缺少必要参数token');return '';
        }

        $user = Db::table('kt_gptcms_common_user')->where([['wid', '=', $wid],['xcx_token', '=', $token]])->find();
        if(!$user){
            $connection->send('[error]无效的token');return '';
        }
        if($user['status'] != 1){
            $connection->send('[error]账号因异常行为进入风控，请联系客服解除风控！');return '';
        }

        $chatmodel = $data['chatmodel'] ?? '';
        if(!$chatmodel){
            $config['channel'] = Db::table('kt_gptcms_gpt_config')->where('wid',$wid)->value('channel');
            switch ($config['channel']) {
                case 1:
                    $chatmodel = 'gpt35';
                    break;

                case 2:
                    $chatmodel = 'api2d35';
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
                
                default:
                    $chatmodel = 'gpt35';
                    break;
            }
            $data['chatmodel'] = $chatmodel;
        }
        $expend = CommonModel::getExpend('chat',$chatmodel);//获取消耗条数
        $data['expend'] = $expend;
        $vip = 0;
        if(@strtotime($user['vip_expire']) > time()){ //会员未到期
            $vip = 1;
        }else{ //会员到期
            if($user['residue_degree'] < $expend){ //余数不足
                $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
                $connection->send('[error]'.$zdz_remind?:'剩余条数不足');return '';
            }
        }
        $data['vip'] = $vip;
        $message = $data['message'] ?? '';
        if(!$message){
            $connection->send('[error]请输入您的问题');return '';
        }

        $type = $data['type'] ?? 'chat';
        if($type == 'chat'){
            $this->chat($connection, $data, $user);
        }elseif($type == 'create'){
            $this->createChat($connection, $data, $user);
        }elseif($type == 'role'){
            $this->roleChat($connection, $data, $user);
        }
    }

    // 普通聊天
    private function chat($connection, $args, $user)
    {
        $wid = $args['wid'];
        $vip = $args['vip'];
        $expend = $args['expend'];
        $message = $args['message'];
        $chatmodel = $args['chatmodel'];

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
                ['common_id', '=', $user['id']],
                //['group_id', '=', $group_id],
            ])->order('id desc')->find();
        }else{
            $now = time();
            $lastMsg = Db::table('kt_gptcms_chat_msg')->where([
                ['wid', '=', $wid],
                ['common_id', '=', $user['id']],
                //['group_id', '=', $group_id],
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
        //不完整的数据
        $imperfect = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$expend,$connection) {
            //$ktadmin = new \Ktadmin\Chatgpt\Ktadmin();
            global $response;
            global $un_response;
            global $imperfect;
            $dataLength = strlen($data);
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

            $complete = @json_decode($data);
            if(isset($complete->error)){
                $connection->send('[error]'.$complete->error->message?:$complete->error->code);
            }elseif(@$complete->object == 'error'){
                $connection->send('[error]'.$complete->message);
            }
            $un_word = $this->parseData($data);
            $word = str_replace("\n", '<br/>', $un_word);
            $word = str_replace(" ", '&nbsp;', $word);
            if($complete){//一次性完整输出
                if (!empty($un_word)) {
                    Db::table('kt_gptcms_chat_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        // 'group_id' => $group_id,
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
                    $connection->send($word);
                }
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
                            // 'group_id' => $group_id,
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
                    $connection->send($word);
                    $connection->send('data: [DONE]');
                }else{
                    $un_response .= $un_word;
                    $response .= $word;
                    $connection->send($word);
                }
            }
            return $dataLength;
        };

        $agid = Db::table('kt_base_user')->where('id',$wid)->value('agid');
        if(!$agid){
            $agid = Db::table('kt_base_agent')->where('isadmin',1)->value('id');
        }
        $base_config = Db::table('kt_base_gpt_config')->json(['openai','api2d'])->where('uid',$agid)->find();
        $base_aiconfig = $base_config['openai'];

        $config = Db::table('kt_gptcms_gpt_config')->json(['openai','api2d','linkerai','gpt4','api2d4'])->where('wid',$wid)->find();
        if($config){
            switch ($chatmodel) {
                case 'gpt35':
                    $aiconfig = $config['openai'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>1,'api_key'=>$aiconfig['api_key'],'diy_host'=>$aiconfig['diy_host']?:$base_aiconfig['diy_host']]);
                    @$ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-3.5-turbo','stream'=>true]);
                    break;

                case 'gpt4':
                    $aiconfig = $config['gpt4'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>8,'api_key'=>$aiconfig['api_key'],'diy_host'=>$aiconfig['diy_host']?:$base_aiconfig['diy_host']]);
                    @$ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-4','stream'=>true]);
                    break;
                
                case 'api2d35':
                    $aiconfig = $config['api2d'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>2,'api_key'=>$aiconfig['forward_key'],'diy_host'=>'']);
                    @$ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-3.5-turbo','stream'=>true]);
                    break;

                case 'api2d4':
                    $aiconfig = $config['api2d4'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>9,'api_key'=>$aiconfig['forward_key'],'diy_host'=>'']);
                    @$ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-4','stream'=>true]);
                    break;

                case 'linkerai':
                    $aiconfig = $config['linkerai'];
                    $ktadmin = new \Ktadmin\LinkerAi\Ktadmin(['channel'=>7,'api_key'=>$aiconfig['api_key']]);
                    @$ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
                    break;
            }
        }else{
            $connection->send('[error]未检查到配置信息');
        }
    }

    // 创作聊天
    private function createChat($connection, $args, $user)
    {
        $wid = $args['wid'];
        $vip = $args['vip'];
        $expend = $args['expend'];
        $message = $args['message'];
        $chatmodel = $args['chatmodel'];

        $model_id = $args['model_id'] ?? '';
        $cmodel = Db::table('kt_gptcms_cmodel')->find($model_id);
        if(!$cmodel){
            $connection->send('[error]模型不存在');return '';
        } 
        if($cmodel['status'] != 1){
            $connection->send('[error]模型不可用');return '';
        }
        //是VIP模型的，仅VIP能使用
        if($cmodel['vip_status'] == 1){
            if($vip == 0){
                $connection->send('[error]当前模型仅VIP可用');return '';
            }
        }
        
        $lang = $args['lang'] ?? '简体中文';

        // 连续对话需要带着上一个问题请求接口
        if($message == '继续' || $message == 'go on'){
            $lastMsg = Db::table('kt_gptcms_create_msg')->where([
                ['wid', '=', $wid],
                ['common_id', '=', $user['id']],
                ['model_id', '=', $model_id]
            ])->order('id desc')->find();
            // 如果超长，就不关联上下文了
            if ($lastMsg && (mb_strlen($lastMsg['make_message']) + mb_strlen($lastMsg['un_response']) + mb_strlen($message) < 3800)) {
                $messages[] = [
                    'role' => 'user',
                    'content' => $lastMsg['make_message']
                ];
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $lastMsg['un_response']
                ];
            }
            $make_message = $message;
        }else{
            $make_message = str_replace('[TARGETLANGGE]', $lang, $cmodel['content']);
            $make_message = str_replace('[PROMPT]', $message, $make_message);
        }
        $messages[] = [
            'role'=>'user',
            'content'=>$make_message
        ];

        //返回的文字
        $response = ''; 
        $un_response = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$cmodel,$make_message,$expend,$connection) {
            //$ktadmin = new \Ktadmin\Chatgpt\Ktadmin();
            global $response;
            global $un_response;
            $complete = @json_decode($data);
            if(isset($complete->error)){
                $connection->send('[error]'.$complete->error->message?:$complete->error->code);
            }elseif(@$complete->object == 'error'){
                $connection->send('[error]'.$complete->message);
            }
            $un_word = $this->parseData($data);
            $word = str_replace("\n", '<br/>', $un_word);
            $word = str_replace(" ", '&nbsp;', $word);
            if($complete){//一次性完整输出
                if (!empty($un_word)) {
                    Db::table('kt_gptcms_create_msg')->insert([
                        'wid' => $wid,
                        'common_id' => $user['id'],
                        'model_id' => $cmodel['id'],
                        'un_message' => $message,
                        'message' => $message,
                        'make_message' => $make_message,
                        'un_response' => $un_word,
                        'response' => $word,
                        'total_tokens' => mb_strlen($make_message) + mb_strlen($un_word),
                        'c_time' => time()
                    ]);
                    //如果不是会员扣费
                    if($vip != 1){
                        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                    }
                    $connection->send($word);
                }
            }else{//流式
                if(strpos($un_word, 'data: [DONE]') !== false){
                    $un_word = str_replace("data: [DONE]","",$un_word);
                    $word = str_replace("data:&nbsp;[DONE]","",$word);
                    $un_response .= $un_word;
                    $response .= $word;
                    if (!empty($un_response)) {
                        Db::table('kt_gptcms_create_msg')->insert([
                            'wid' => $wid,
                            'common_id' => $user['id'],
                            'model_id' => $cmodel['id'],
                            'un_message' => $message,
                            'message' => $message,
                            'make_message' => $make_message,
                            'un_response' => $un_response,
                            'response' => $response,
                            'total_tokens' => mb_strlen($make_message) + mb_strlen($un_response),
                            'c_time' => time()
                        ]);
                        //如果不是会员扣费
                        if($vip != 1){
                            Db::table('kt_gptcms_common_user')->where('id',$user['id'])->dec('residue_degree',$expend)->update();
                        }
                        $un_response = '';
                        $response = '';
                    }
                    $connection->send($word);
                    $connection->send('data: [DONE]');
                }else{
                    $un_response .= $un_word;
                    $response .= $word;
                    $connection->send($word);
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

        $config = Db::table('kt_gptcms_gpt_config')->json(['openai','api2d','linkerai','gpt4','api2d4'])->where('wid',$wid)->find();
        if($config){
            switch ($chatmodel) {
                case 'gpt35':
                    $aiconfig = $config['openai'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>1,'api_key'=>$aiconfig['api_key'],'diy_host'=>$aiconfig['diy_host']?:$base_aiconfig['diy_host']]);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-3.5-turbo','stream'=>true]);
                    break;

                case 'gpt4':
                    $aiconfig = $config['gpt4'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>8,'api_key'=>$aiconfig['api_key'],'diy_host'=>$aiconfig['diy_host']?:$base_aiconfig['diy_host']]);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-4','stream'=>true]);
                    break;
                
                case 'api2d35':
                    $aiconfig = $config['api2d'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>2,'api_key'=>$aiconfig['forward_key'],'diy_host'=>'']);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-3.5-turbo','stream'=>true]);
                    break;

                case 'api2d4':
                    $aiconfig = $config['api2d4'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>9,'api_key'=>$aiconfig['forward_key'],'diy_host'=>'']);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-4','stream'=>true]);
                    break;

                case 'linkerai':
                    $aiconfig = $config['linkerai'];
                    $ktadmin = new \Ktadmin\LinkerAi\Ktadmin(['channel'=>7,'api_key'=>$aiconfig['api_key']]);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
                    break;
            }
        }else{
            $connection->send('[error]未检查到配置信息');
        }
    }

    // 角色聊天
    private function roleChat($connection, $args, $user)
    {
        $wid = $args['wid'];
        $vip = $args['vip'];
        $expend = $args['expend'];
        $message = $args['message'];
        $chatmodel = $args['chatmodel'];

        $model_id = $args['model_id'] ?? '';
        $jmodel = Db::table('kt_gptcms_jmodel')->find($model_id);
        if(!$jmodel){
            $connection->send('[error]模型不存在');return '';
        }
        if($jmodel['status'] != 1){
            $connection->send('[error]模型不可用');return '';
        }
        //是VIP模型的，仅VIP能使用
        if($jmodel['vip_status'] == 1){
            if($vip == 0){
                $connection->send('[error]当前模型仅VIP可用');return '';
            }
        }

        $qzzl = DB::table("kt_gptcms_qzzl")->where("wid",$wid)->find();
        $messages = [];
        if(isset($qzzl['status']) && $qzzl['status']){
                // $currentTime = date('Y-m-d H:i:s', time());
                $messages[] = [
                    'role' => 'system',
                    'content' => $qzzl['content']
                ];
        }
        //模型指令
        $messages[] = [
            'role' => 'user',
            'content' => $jmodel['content']
        ];
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

        //返回的文字
        $response = ''; 
        $un_response = '';
        $callback = function($ch, $data) use ($message,$wid,$user,$vip,$jmodel,$expend,$connection) {
            //$ktadmin = new \Ktadmin\Chatgpt\Ktadmin();
            global $response;
            global $un_response;
            $complete = @json_decode($data);
            if(isset($complete->error)){
                $connection->send('[error]'.$complete->error->message?:$complete->error->code);
            }elseif(@$complete->object == 'error'){
                $connection->send('[error]'.$complete->message);
            }
            $un_word = $this->parseData($data);
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
                    $connection->send($word);
                }
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
                    $connection->send($word);
                    $connection->send('data: [DONE]');
                }else{
                    $un_response .= $un_word;
                    $response .= $word;
                    $connection->send($word);
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

        $config = Db::table('kt_gptcms_gpt_config')->json(['openai','api2d','linkerai','gpt4','api2d4'])->where('wid',$wid)->find();
        if($config){
            switch ($chatmodel) {
                case 'gpt35':
                    $aiconfig = $config['openai'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>1,'api_key'=>$aiconfig['api_key'],'diy_host'=>$aiconfig['diy_host']?:$base_aiconfig['diy_host']]);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-3.5-turbo','stream'=>true]);
                    break;

                case 'gpt4':
                    $aiconfig = $config['gpt4'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>8,'api_key'=>$aiconfig['api_key'],'diy_host'=>$aiconfig['diy_host']?:$base_aiconfig['diy_host']]);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-4','stream'=>true]);
                    break;
                
                case 'api2d35':
                    $aiconfig = $config['api2d'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>2,'api_key'=>$aiconfig['forward_key'],'diy_host'=>'']);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-3.5-turbo','stream'=>true]);
                    break;

                case 'api2d4':
                    $aiconfig = $config['api2d4'];
                    $ktadmin = new \Ktadmin\Chatgpt\Ktadmin(['channel'=>9,'api_key'=>$aiconfig['forward_key'],'diy_host'=>'']);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>'gpt-4','stream'=>true]);
                    break;

                case 'linkerai':
                    $aiconfig = $config['linkerai'];
                    $ktadmin = new \Ktadmin\LinkerAi\Ktadmin(['channel'=>7,'api_key'=>$aiconfig['api_key']]);
                    $ktadmin->chat()->sendText($messages, $callback,['temperature'=>$aiconfig['temperature'],'max_tokens'=>$aiconfig['max_tokens'],'model'=>$aiconfig['model'],'stream'=>true]);
                    break;
            }
        }else{
            $connection->send('[error]未检查到配置信息');
        }
    }

    private function parseData($data)
    {
        //一次性返回数据
        if(@json_decode($data)->choices[0]->message->content){
            return json_decode($data)->choices[0]->message->content;
        }
        //file_put_contents('./worker.txt', $data.'end', 8);
        //流式数据
        $data = str_replace('data: {', '{', $data);
        $data = rtrim($data, "\n\n");
        $data = explode("\n\n",$data);
        $res = '';
        foreach ($data as $key => $d) {
            if (strpos($d, 'data: [DONE]') !== false) {
                $res .= 'data: [DONE]';
                break;
            }
            $d = @json_decode($d, true);
            if (!is_array($d)) {
                continue;
            }
            if (@$d['choices']['0']['finish_reason'] == 'stop') {
                $res .= 'data: [DONE]';
                break;
            }
            if(@$d['choices']['0']['finish_reason'] == 'length') {
                $res .= 'data: [DONE]';
                break;
            }
            $content = $d['choices']['0']['delta']['content'] ?? '';
            $res .= $content;
        }
        return $res;
    }

    public function onClose($connection)
    {
        echo "closed，ID：" . $connection->id . "\n";
    }

    public function onError($connection, $code, $msg)
    {
        echo 'onError' . "\n";
        $connection->close();
    }

}

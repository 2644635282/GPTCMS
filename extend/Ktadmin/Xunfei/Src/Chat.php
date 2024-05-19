<?php
namespace Ktadmin\Xunfei\Src;

use Ktadmin\Xunfei\Ktadmin;
use WebSocket\Client;

/**
 * 聊天
 */
class Chat
{
    private $ktadmin;
    private $version = "v1";  // v1 v2 v3 v3.5
    private $temperature = 0.5; //温度采样参数，取值(    0,2]。大于1的值倾向于生成更加多样的回复，小于1倾向于生成更加稳定的回复
    private $max_tokens = 1024; //每次回答的最大字符长度

    public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;

    }
    /**
     * 初始化配置
     */
    public function initConfig($config)
    {
        
        if(isset($config['temperature']) && $config['temperature']){
            $this->temperature = (float) $config['temperature'];
        }
        if(isset($config['max_tokens']) && $config['max_tokens']){
            $this->max_tokens = (int) $config['max_tokens'];
        }
        if(isset($config['version']) && $config['version']){
            $this->version = $config['version'];
        }
    }
    /**
     * 发送聊天
     * @param Array $messages 提问内容，格式[['role'=>'user','content'=>'hello!']]
     * @param Closure $callback 匿名函数
     * @param Array $config 配置信息。可选参数有 temperature：介于 0 和 2 之间，越大回答问题越灵活，越像真人；
     * max_tokens：每次回答的最大字符长度；model：要使用的模型的 ID；stream：如果设置true则流式输出,false则一次性返回
     */
    public function sendText($messages = [], $callback,$config = [])
    {
        $this->initConfig($config);

        $app_id = $this->ktadmin->APPID;
        $api_key = $this->ktadmin->APIKEY;
        $api_secret = $this->ktadmin->APISecret;
        $url = $this->createUrl($api_key, $api_secret);
        $client = new Client($url);
        $message = $this->createMsg($app_id, $messages);
        $str = '';
        try {
            $client->send(json_encode($message));
            $response = $client->receive();

            $response_arr = json_decode($response, true);
            // 科达讯飞会分多次发送消息
            do {
                if ($response_arr['header']['code'] == '0') {
                    $callback($response_arr['payload']['choices']['text'][0]['content'] ??'');
                    // echo 'data: '.$response."\n\n"; //转成EventSource的输出格式
                }else{
                    $callback($response_arr['header']['message']);
                    // echo '[error]'.$response_arr['header']['message'];
                    break;
                }

                $content = $response_arr['payload']['choices']['text'][0]['content'];
                $str .= $content;
                if ($response_arr['header']['status'] == 2) {
                    // echo 'data: [DONE]'."\n\n";
                    $callback('data: [DONE]');
                    break;
                }
                //继续接收消息
                $response = $client->receive();
                $response_arr = json_decode($response, true);
            } while (true);
            $client->close();
        } catch (Exception $e) {

        } finally {
            $client->close();
        }
        return $str;
    }
    /**
    * 调用科大讯飞星火认知模型
    * @param $params
    * @return array
    */
    public function sendMsg($params)
    {
        $prompt = $params['prompt'];
        //获取科大讯飞参数
        $app_id = $params['APPID'];
        $api_key = $params['APIKEY'];
        $api_secret = $params['APISecret'];
        //拼接链接
        $url = $this->createUrl($api_key, $api_secret);
        $client = new Client($url);
        //拼接要发送的信息
        $message = $this->createMsg($app_id, $prompt);
        // try {
            $client->send(json_encode($message));
            $response = $client->receive();

            $response_arr = json_decode($response, true);
            // 科达讯飞会分多次发送消息
            do {
                if ($response_arr['header']['code'] == '0') {
                    echo 'data: '.$response."\n\n"; //转成EventSource的输出格式
                }else{
                    echo '[error]'.$response_arr['header']['message'];
                    break;
                }

                $content = $response_arr['payload']['choices']['text'][0]['content'];

                if ($response_arr['header']['status'] == 2) {
                    echo 'data: [DONE]'."\n\n";
                    break;
                }
                //继续接收消息
                $response = $client->receive();
                $response_arr = json_decode($response, true);
            } while (true);
            $client->close();
            /*return [
            'code' => 0,
            'msg' => '输出成功',
            ];*/
        // } catch (Exception $e) {
        /*return [
        'code' => -1,
        'msg' => $e->getMessage(),
        ];*/
        // } finally {
        //  $client->close();
        // }
    }
            /**
    * 拼接签名
    * @param $api_key
    * @param $api_secret
    * @param $time
    * @return string
    */
    private function sign($api_key, $api_secret, $time)
    {
        $signature_origin = 'host: spark-api.xf-yun.com' . "\n";
        $signature_origin .= 'date: ' . $time . "\n";
        if($this->version == "v3.5"){
            $signature_origin .= 'GET /'.$this->version.'/chat HTTP/1.1';
        }else{
            $signature_origin .= 'GET /'.$this->version.'.1/chat HTTP/1.1';
        }
        $signature_sha = hash_hmac('sha256', $signature_origin, $api_secret, true);
        $signature_sha = base64_encode($signature_sha);
        $authorization_origin = 'api_key="' . $api_key . '", algorithm="hmac-sha256", ';
        $authorization_origin .= 'headers="host date request-line", signature="' . $signature_sha . '"';
        $authorization = base64_encode($authorization_origin);
        return $authorization;
    }

    /**
    * 生成Url
    * @param $api_key
    * @param $api_secret
    * @return string
    */
    private function createUrl($api_key, $api_secret)
    {
        if($this->version == "v3.5"){
            $url = 'wss://spark-api.xf-yun.com/'.$this->version.'/chat';
        }else{
            $url = 'wss://spark-api.xf-yun.com/'.$this->version.'.1/chat';
        }
        $time = gmdate('D, d M Y H:i:s') . ' GMT';
        $authorization = $this->sign($api_key, $api_secret, $time);
        $url .= '?' . 'authorization=' . $authorization . '&date=' . urlencode($time) . '&host=spark-api.xf-yun.com';
        return $url;
    }

    /**
    * 生成要发送的消息体
    * @param $app_id
    * @param $speed
    * @param $volume
    * @param $pitch
    * @param $audio_content
    * @return array
    */
    private function createMsg($app_id, $messages)
    {
        $v = $this->version == "v1" ? "" : $this->version;
        return [
            'header' => [
                'app_id' => $app_id,
            ],
            'parameter' => [
                "chat"=> [
                    "domain"=> "general".$v,
                    "temperature"=> $this->temperature,
                    "max_tokens"=> $this->max_tokens, 
                ]
            ],
            // 'payload' => [
            //     "message"=> [
            //         "text"=> [
            //             ["role"=> "user", "content"=> $prompt]
            //         ]
            //     ]
            // ],
            'payload' => [
                "message"=> [
                    "text"=> $messages
                ]
            ],
        ];
    }
}


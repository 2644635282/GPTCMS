<?php
namespace Ktadmin\Xunfei;


/**
* 灵犀星火接口
*/
class Ktadmin
{
    private $channel = 10; //渠道 1.openai 2.api2d 7. 灵犀星火 linkerai
    public $APPID; //
    public $APIKEY; //
    public $APISecret; //
    
    /**
     * Ktadmin constructor.
     */
    public function __construct($config=array())
    {
        
        if(isset($config['channel']) && $config['channel']){
            $this->channel = (int)$config['channel'];
        }
        if(isset($config['appid']) && $config['appid']){
            $this->APPID = $config['appid'];
        }
        if(isset($config['apikey']) && $config['apikey']){
            $this->APIKEY = $config['apikey'];
        }
        if(isset($config['apisecret']) && $config['apisecret']){
            $this->APISecret = $config['apisecret'];
        }
    }

    /**
     * 聊天
     */
    public function chat()
    {
        return new \Ktadmin\Xunfei\Src\Chat($this);
    }


}
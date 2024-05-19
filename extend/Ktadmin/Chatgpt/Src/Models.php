<?php
namespace Ktadmin\Chatgpt\Src;

use Ktadmin\Chatgpt\Ktadmin;
/**
 * 模型
 */
class Models
{
    private $ktadmin;

    public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

    /**
     * 列出模型
     * @return  JSON
     */
    public function list(){
        $url = "/v1/models";
        return $this->ktadmin->curlGetRequest($url);
    }
    /**
     * 检索模型
     * @param modelid string 模型id
     * @return  JSON
     */
    public function detail($modelid){
        $url = "/v1/models/{$modelid}";
        return $this->ktadmin->curlGetRequest($url);
    }
}
<?php 
namespace app\gptcms\model;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cache;


class ApishopModel
{
    private $appkey;
    private $appsecret;
    private $domain = "http://api.sylong.net";
     /**
     * Ktadmin constructor.
     */
    public function __construct($appkey,$appsecret)
    {
        $this->appkey = $appkey;
        $this->appsecret = $appsecret;
    }

    private function getToken()
    {
        $token = '';
        $apiurl = $this->domain ."/apishop/api/token";
        if($token) return $token;
        $data = ["appkey"=>$this->appkey,"appsecret"=>$this->appsecret];
        $res = json_decode(curlPost($apiurl,$data),true);
        if($res && isset($res["status"]) && $res["status"] == "success") {
            $token = $res["data"]["token"];
            Cache::set("gptcmsapishop_".$this->appkey,$res["data"]["token"],$res["data"]["expiretime"]);
        }
        return $token;
    }

    public function create($apiurl,$data)
    {
        $token = $this->getToken();
        $header = ["appkey:".$this->appkey,"token:".$token,"Content-Type: application/json"];
    //  $curl = curl_init(); // 启动一个CURL会话
        // curl_setopt($curl, CURLOPT_URL, "https://qiye.3yu.com/apishop/api/photodid/create"); // 要访问的地址
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        // curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        // curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        // curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        // curl_setopt($curl, CURLOPT_POSTFIELDS,json_encode($data)); // Post提交的数据包
        // curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        // curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_POST, TRUE); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('appkey: '.$this->appkey,'token: '.$token));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($ch, CURLOPT_URL, $this->domain.$apiurl);
        $ret = curl_exec($ch);
        curl_close($ch);
        
        // $result = curl_exec($curl); // 执行操作
        $res = json_decode($ret,true);
        return $res;
    }
}
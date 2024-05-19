<?php
namespace paySDK\Wxpay;

class WebchatPay{
    public $payUrl = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    public $appid;
    public $mch_id;
    public $key;
    public function __construct($wxpay_config){
        $this->appid = $wxpay_config['appid'];
        $this->mch_id = $wxpay_config['mch_id'];
        $this->key = $wxpay_config['key'];
        $this->notify_url = $wxpay_config['notify_url'];
    }
    
    public function unifiedorder($total_fee,$out_trade_no,$body){
        $params['appid'] = $this->appid;
        $params['mch_id'] = $this->mch_id;
        $params['nonce_str'] = md5('app'.time());
        $params['trade_type'] = 'NATIVE';
        $params['notify_url'] = $this->notify_url;
        $params['spbill_create_ip'] = $_SERVER['REMOTE_ADDR'];
        $params['total_fee'] = $total_fee;
        $params['out_trade_no'] = $out_trade_no;
        $params['body'] = $body;
        $params['sign'] = $this->getSign($params);
        $xml = $this->arrayToXml($params);
        $result = $this->postXmlCurl($xml,$this->payUrl);
        $result = $this->xmlToArray($result);
        return $result['return_msg'] == 'OK' ? $result['code_url'] : false;
    }
    private function getSign($Parameters) {
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        $String = $String."&key=".$this->key;
        $String = md5($String);
        $result_ = strtoupper($String);
        return $result_;
    }
    private function formatBizQueryParaMap($paraMap, $urlencode) {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {$v = urlencode($v);}
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';
        if (strlen($buff) > 0) {$reqPar = substr($buff, 0, strlen($buff) - 1);}
        return $reqPar;
    }
    private function arrayToXml($arr) {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    private function xmlToArray($xml){
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);
        return $val;
    }

    private function postXmlCurl($xml, $url, $second = 30) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data) {return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }
}

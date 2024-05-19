<?php
namespace paySDK\Alipay\Aop;
class AopEncrypt{
    
    public static $hex_iv = '00000000000000000000000000000000';
    
    public static function encrypt($str,$screct_key){
        $data = openssl_encrypt($str,'AES-256-CBC',$screct_key,OPENSSL_RAW_DATA,self::hexToStr(self::$hex_iv));
        $data = base64_encode($data);
        return $data;
    }
    
    public static function decrypt($str,$screct_key){
        $str = base64_decode($str);
        $decrypted = openssl_decrypt($str,'AES-256-CBC',$screct_key,OPENSSL_RAW_DATA,self::hexToStr(self::$hex_iv));
        return $decrypted;
    }
    private function addPKCS7Padding($string, $blocksize = 16) {
        $len = strlen($string);
        $pad = $blocksize - ($len % $blocksize);
        $string .= str_repeat(chr($pad), $pad);
        return $string;
    }
 
    private function stripPKSC7Padding($string) {
        $slast = ord(substr($string, -1));
        $slastc = chr($slast);
        $pcheck = substr($string, -$slast);
        if (preg_match("/$slastc{" . $slast . "}/", $string)) {
            $string = substr($string, 0, strlen($string) - $slast);
            return $string;
        } else {
            return false;
        }
    }
    
    public static function hexToStr($hex){
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
}
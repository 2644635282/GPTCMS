<?php

include_once "errorCode.php";

/**
 * PKCS7Encoder class
 *
 * �ṩ����PKCS7�㷨�ļӽ��ܽӿ�.
 */
class PKCS7Encoder
{
    public static $block_size = 32;

    /**
     * ����Ҫ���ܵ����Ľ�����䲹λ
     * @param $text ��Ҫ������䲹λ����������
     * @return ���������ַ���
     */
    function encode($text)
    {
        $block_size = PKCS7Encoder::$block_size;
        $text_length = strlen($text);
        //������Ҫ����λ��
        $amount_to_pad = PKCS7Encoder::$block_size - ($text_length % PKCS7Encoder::$block_size);
        if ($amount_to_pad == 0) {
            $amount_to_pad = PKCS7Encoder::block_size;
        }
        //��ò�λ���õ��ַ�
        $pad_chr = chr($amount_to_pad);
        $tmp = "";
        for ($index = 0; $index < $amount_to_pad; $index++) {
            $tmp .= $pad_chr;
        }
        return $text . $tmp;
    }

    /**
     * �Խ��ܺ�����Ľ��в�λɾ��
     * @param decrypted ���ܺ������
     * @return ɾ����䲹λ�������
     */
    function decode($text)
    {

        $pad = ord(substr($text, -1));
        if ($pad < 1 || $pad > PKCS7Encoder::$block_size) {
            $pad = 0;
        }
        return substr($text, 0, (strlen($text) - $pad));
    }

}

/**
 * Prpcrypt class
 *
 * �ṩ���պ����͸�����ƽ̨��Ϣ�ļӽ��ܽӿ�.
 */
class Prpcrypt
{
    public $key = null;
    public $iv = null;

    /**
     * Prpcrypt constructor.
     * @param $k
     */
    public function __construct($k)
    {
        $this->key = base64_decode($k . '=');
        $this->iv = substr($this->key, 0, 16);

    }

    /**
     * ����
     *
     * @param $text
     * @param $receiveId
     * @return array
     */
    public function encrypt($text, $receiveId)
    {
        try {
            //ƴ��
            $text = $this->getRandomStr() . pack('N', strlen($text)) . $text . $receiveId;
            //���PKCS#7���
            $pkc_encoder = new PKCS7Encoder;
            $text = $pkc_encoder->encode($text);
            //����
            if (function_exists('openssl_encrypt')) {
                $encrypted = openssl_encrypt($text, 'AES-256-CBC', $this->key, OPENSSL_ZERO_PADDING, $this->iv);
            } else {
                $encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, base64_decode($text), MCRYPT_MODE_CBC, $this->iv);
            }
            return array(ErrorCode::$OK, $encrypted);
        } catch (Exception $e) {
            print $e;
            return array(MyErrorCode::$EncryptAESError, null);
        }
    }

    /**
     * ����
     *
     * @param $encrypted
     * @param $receiveId
     * @return array
     */
    public function decrypt($encrypted, $receiveId)
    {
        try {
            //����
            if (function_exists('openssl_decrypt')) {
                $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $this->key, OPENSSL_ZERO_PADDING, $this->iv);
            } else {
                $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, base64_decode($encrypted), MCRYPT_MODE_CBC, $this->iv);
            }
        } catch (Exception $e) {
            return array(ErrorCode::$DecryptAESError, null);
        }
        try {
            //ɾ��PKCS#7���
            $pkc_encoder = new PKCS7Encoder;
            $result = $pkc_encoder->decode($decrypted);
            if (strlen($result) < 16) {
                return array();
            }
            //���
            $content = substr($result, 16, strlen($result));
            $len_list = unpack('N', substr($content, 0, 4));
            $xml_len = $len_list[1];
            $xml_content = substr($content, 4, $xml_len);
            $from_receiveId = substr($content, $xml_len + 4);
        } catch (Exception $e) {
            print $e;
            return array(ErrorCode::$IllegalBuffer, null);
        }
        if ($from_receiveId != $receiveId) {
            return array(ErrorCode::$ValidateCorpidError, null);
        }
        return array(0, $xml_content);
    }

    /**
     * ��������ַ���
     *
     * @return string
     */
    private function getRandomStr()
    {
        $str = '';
        $str_pol = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyl';
        $max = strlen($str_pol) - 1;
        for ($i = 0; $i < 16; $i++) {
            $str .= $str_pol[mt_rand(0, $max)];
        }
        return $str;
    }
}
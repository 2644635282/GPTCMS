<?php

include_once "errorCode.php";

/**
 * SHA1 class
 *
 * ���㹫��ƽ̨����Ϣǩ���ӿ�.
 */
class SHA1
{
	/**
	 * ��SHA1�㷨���ɰ�ȫǩ��
	 * @param string $token Ʊ��
	 * @param string $timestamp ʱ���
	 * @param string $nonce ����ַ���
	 * @param string $encrypt ������Ϣ
	 */
	public function getSHA1($token, $timestamp, $nonce, $encrypt_msg)
	{
		//����
		try {
			$array = array($encrypt_msg, $token, $timestamp, $nonce);
			sort($array, SORT_STRING);
			$str = implode($array);
			return array(ErrorCode::$OK, sha1($str));
		} catch (Exception $e) {
			print $e . "\n";
			return array(ErrorCode::$ComputeSignatureError, null);
		}
	}

}


?>
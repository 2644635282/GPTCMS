<?php

/**
 * ��ҵ΢�Żص���Ϣ�ӽ���ʾ������.
 *
 * @copyright Copyright (c) 1998-2014 Tencent Inc.
 */


include_once "sha1.php";
include_once "xmlparse.php";
include_once "pkcs7Encoder.php";
include_once "errorCode.php";

class WXBizMsgCrypt
{
	private $m_sToken;
	private $m_sEncodingAesKey;
	private $m_sReceiveId;

	/**
	 * ���캯��
	 * @param $token string ���������õ�token
	 * @param $encodingAesKey string ���������õ�EncodingAESKey
	 * @param $receiveId string, ��ͬӦ�ó�������ͬ��id
	 */
	public function __construct($token, $encodingAesKey, $receiveId)
	{
		$this->m_sToken = $token;
		$this->m_sEncodingAesKey = $encodingAesKey;
		$this->m_sReceiveId = $receiveId;
	}
	
    /*
	*��֤URL
    *@param sMsgSignature: ǩ��������ӦURL������msg_signature
    *@param sTimeStamp: ʱ�������ӦURL������timestamp
    *@param sNonce: ���������ӦURL������nonce
    *@param sEchoStr: ���������ӦURL������echostr
    *@param sReplyEchoStr: ����֮���echostr����return����0ʱ��Ч
    *@return���ɹ�0��ʧ�ܷ��ض�Ӧ�Ĵ�����
	*/
	public function VerifyURL($sMsgSignature, $sTimeStamp, $sNonce, $sEchoStr, &$sReplyEchoStr)
	{
		if (strlen($this->m_sEncodingAesKey) != 43) {
			return ErrorCode::$IllegalAesKey;
		}

		$pc = new Prpcrypt($this->m_sEncodingAesKey);
		//verify msg_signature
		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $sEchoStr);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		$signature = $array[1];
		if ($signature != $sMsgSignature) {
			return ErrorCode::$ValidateSignatureError;
		}

		$result = $pc->decrypt($sEchoStr, $this->m_sReceiveId);
		if ($result[0] != 0) {
			return $result[0];
		}
		$sReplyEchoStr = $result[1];

		return ErrorCode::$OK;
	}
	/**
	 * ������ƽ̨�ظ��û�����Ϣ���ܴ��.
	 * <ol>
	 *    <li>��Ҫ���͵���Ϣ����AES-CBC����</li>
	 *    <li>���ɰ�ȫǩ��</li>
	 *    <li>����Ϣ���ĺͰ�ȫǩ�������xml��ʽ</li>
	 * </ol>
	 *
	 * @param $replyMsg string ����ƽ̨���ظ��û�����Ϣ��xml��ʽ���ַ���
	 * @param $timeStamp string ʱ����������Լ����ɣ�Ҳ������URL������timestamp
	 * @param $nonce string ������������Լ����ɣ�Ҳ������URL������nonce
	 * @param &$encryptMsg string ���ܺ�Ŀ���ֱ�ӻظ��û������ģ�����msg_signature, timestamp, nonce, encrypt��xml��ʽ���ַ���,
	 *                      ��return����0ʱ��Ч
	 *
	 * @return int �ɹ�0��ʧ�ܷ��ض�Ӧ�Ĵ�����
	 */
	public function EncryptMsg($sReplyMsg, $sTimeStamp, $sNonce, &$sEncryptMsg)
	{
		$pc = new Prpcrypt($this->m_sEncodingAesKey);

		//����
		$array = $pc->encrypt($sReplyMsg, $this->m_sReceiveId);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}

		if ($sTimeStamp == null) {
			$sTimeStamp = time();
		}
		$encrypt = $array[1];

		//���ɰ�ȫǩ��
		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $encrypt);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}
		$signature = $array[1];

		//���ɷ��͵�xml
		$xmlparse = new XMLParse;
		$sEncryptMsg = $xmlparse->generate($encrypt, $signature, $sTimeStamp, $sNonce);
		return ErrorCode::$OK;
	}


	/**
	 * ������Ϣ����ʵ�ԣ����һ�ȡ���ܺ������.
	 * <ol>
	 *    <li>�����յ����������ɰ�ȫǩ��������ǩ����֤</li>
	 *    <li>����֤ͨ��������ȡxml�еļ�����Ϣ</li>
	 *    <li>����Ϣ���н���</li>
	 * </ol>
	 *
	 * @param $msgSignature string ǩ��������ӦURL������msg_signature
	 * @param $timestamp string ʱ��� ��ӦURL������timestamp
	 * @param $nonce string ���������ӦURL������nonce
	 * @param $postData string ���ģ���ӦPOST���������
	 * @param &$msg string ���ܺ��ԭ�ģ���return����0ʱ��Ч
	 *
	 * @return int �ɹ�0��ʧ�ܷ��ض�Ӧ�Ĵ�����
	 */
	public function DecryptMsg($sMsgSignature, $sTimeStamp = null, $sNonce, $sPostData, &$sMsg)
	{
		if (strlen($this->m_sEncodingAesKey) != 43) {
			return ErrorCode::$IllegalAesKey;
		}

		$pc = new Prpcrypt($this->m_sEncodingAesKey);

		//��ȡ����
		$xmlparse = new XMLParse;
		$array = $xmlparse->extract($sPostData);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		if ($sTimeStamp == null) {
			$sTimeStamp = time();
		}

		$encrypt = $array[1];

		//��֤��ȫǩ��
		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->m_sToken, $sTimeStamp, $sNonce, $encrypt);
		$ret = $array[0];

		if ($ret != 0) {
			return $ret;
		}

		$signature = $array[1];
		if ($signature != $sMsgSignature) {
			return ErrorCode::$ValidateSignatureError;
		}

		$result = $pc->decrypt($encrypt, $this->m_sReceiveId);
		if ($result[0] != 0) {
			return $result[0];
		}
		$sMsg = $result[1];

		return ErrorCode::$OK;
	}

}
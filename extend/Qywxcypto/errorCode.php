<?php
/**
 * error code ˵��.
 * <ul>
 *    <li>-40001: ǩ����֤����</li>
 *    <li>-40002: xml����ʧ��</li>
 *    <li>-40003: sha��������ǩ��ʧ��</li>
 *    <li>-40004: encodingAesKey �Ƿ�</li>
 *    <li>-40005: corpid У�����</li>
 *    <li>-40006: aes ����ʧ��</li>
 *    <li>-40007: aes ����ʧ��</li>
 *    <li>-40008: ���ܺ�õ���buffer�Ƿ�</li>
 *    <li>-40009: base64����ʧ��</li>
 *    <li>-40010: base64����ʧ��</li>
 *    <li>-40011: ����xmlʧ��</li>
 * </ul>
 */
class ErrorCode
{
	public static $OK = 0;
	public static $ValidateSignatureError = -40001;
	public static $ParseXmlError = -40002;
	public static $ComputeSignatureError = -40003;
	public static $IllegalAesKey = -40004;
	public static $ValidateCorpidError = -40005;
	public static $EncryptAESError = -40006;
	public static $DecryptAESError = -40007;
	public static $IllegalBuffer = -40008;
	public static $EncodeBase64Error = -40009;
	public static $DecodeBase64Error = -40010;
	public static $GenReturnXmlError = -40011;
}

?>
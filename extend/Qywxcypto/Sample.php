<?php

include_once "WXBizMsgCrypt.php";

// ������ҵ���ڹ���ƽ̨�����õĲ�������
$encodingAesKey = "jWmYm7qr5nMoAUwZRjGtBxmz3KA1tkAj3ykkR6q2B2C";
$token = "QDG6eK";
$corpId = "wx5823bf96d3bd56c7";

/*
------------ʹ��ʾ��һ����֤�ص�URL---------------
*��ҵ�����ص�ģʽʱ����ҵ�Ż�����֤url����һ��get���� 
��������֤ʱ����ҵ�յ���������
* GET /cgi-bin/wxpush?msg_signature=5c45ff5e21c57e6ad56bac8758b79b1d9ac89fd3&timestamp=1409659589&nonce=263014780&echostr=P9nAzCzyDtyTWESHep1vC5X9xho%2FqYX3Zpb4yKa9SKld1DsH3Iyt3tP3zNdtp%2B4RPcs8TgAE7OaBO%2BFZXvnaqQ%3D%3D 
* HTTP/1.1 Host: qy.weixin.qq.com
���յ�������ʱ����ҵӦ
1.������Get����Ĳ�����������Ϣ��ǩ��(msg_signature)��ʱ���(timestamp)��������ִ�(nonce)�Լ�����ƽ̨���͹�������������ַ���(echostr),
��һ��ע����URL���롣
2.��֤��Ϣ��ǩ������ȷ�� 
3. ���ܳ�echostrԭ�ģ���ԭ�ĵ���Get�����response�����ظ�����ƽ̨
��2��3�������ù���ƽ̨�ṩ�Ŀ⺯��VerifyURL��ʵ�֡�
*/

// $sVerifyMsgSig = HttpUtils.ParseUrl("msg_signature");
$sVerifyMsgSig = "5c45ff5e21c57e6ad56bac8758b79b1d9ac89fd3";
// $sVerifyTimeStamp = HttpUtils.ParseUrl("timestamp");
$sVerifyTimeStamp = "1409659589";
// $sVerifyNonce = HttpUtils.ParseUrl("nonce");
$sVerifyNonce = "263014780";
// $sVerifyEchoStr = HttpUtils.ParseUrl("echostr");
$sVerifyEchoStr = "P9nAzCzyDtyTWESHep1vC5X9xho/qYX3Zpb4yKa9SKld1DsH3Iyt3tP3zNdtp+4RPcs8TgAE7OaBO+FZXvnaqQ==";

// ��Ҫ���ص�����
$sEchoStr = "";

$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
$errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
if ($errCode == 0) {
    var_dump($sEchoStr);
	//
	// ��֤URL�ɹ�����sEchoStr����
	// HttpUtils.SetResponce($sEchoStr);
} else {
	print("ERR: " . $errCode . "\n\n");
}

/*
------------ʹ��ʾ���������û��ظ�����Ϣ����---------------
�û��ظ���Ϣ���ߵ���¼���Ӧʱ����ҵ���յ��ص���Ϣ������Ϣ�Ǿ�������ƽ̨����֮���������post��ʽ���͸���ҵ�����ĸ�ʽ��ο��ٷ��ĵ�
������ҵ�յ�����ƽ̨�Ļص���Ϣ���£�
POST /cgi-bin/wxpush? msg_signature=477715d11cdb4164915debcba66cb864d751f3e6&timestamp=1409659813&nonce=1372623149 HTTP/1.1
Host: qy.weixin.qq.com
Content-Length: 613
<xml>
<ToUserName><![CDATA[wx5823bf96d3bd56c7]]></ToUserName><Encrypt><![CDATA[RypEvHKD8QQKFhvQ6QleEB4J58tiPdvo+rtK1I9qca6aM/wvqnLSV5zEPeusUiX5L5X/0lWfrf0QADHHhGd3QczcdCUpj911L3vg3W/sYYvuJTs3TUUkSUXxaccAS0qhxchrRYt66wiSpGLYL42aM6A8dTT+6k4aSknmPj48kzJs8qLjvd4Xgpue06DOdnLxAUHzM6+kDZ+HMZfJYuR+LtwGc2hgf5gsijff0ekUNXZiqATP7PF5mZxZ3Izoun1s4zG4LUMnvw2r+KqCKIw+3IQH03v+BCA9nMELNqbSf6tiWSrXJB3LAVGUcallcrw8V2t9EL4EhzJWrQUax5wLVMNS0+rUPA3k22Ncx4XXZS9o0MBH27Bo6BpNelZpS+/uh9KsNlY6bHCmJU9p8g7m3fVKn28H3KDYA5Pl/T8Z1ptDAVe0lXdQ2YoyyH2uyPIGHBZZIs2pDBS8R07+qN+E7Q==]]></Encrypt>
<AgentID><![CDATA[218]]></AgentID>
</xml>
��ҵ�յ�post����֮��Ӧ��
1.������url�ϵĲ�����������Ϣ��ǩ��(msg_signature)��ʱ���(timestamp)�Լ�������ִ�(nonce)
2.��֤��Ϣ��ǩ������ȷ�ԡ�
3.��post��������ݽ���xml����������<Encrypt>��ǩ�����ݽ��н��ܣ����ܳ��������ļ����û��ظ���Ϣ�����ģ����ĸ�ʽ��ο��ٷ��ĵ�
��2��3�������ù���ƽ̨�ṩ�Ŀ⺯��DecryptMsg��ʵ�֡�
*/

// $sReqMsgSig = HttpUtils.ParseUrl("msg_signature");
$sReqMsgSig = "477715d11cdb4164915debcba66cb864d751f3e6";
// $sReqTimeStamp = HttpUtils.ParseUrl("timestamp");
$sReqTimeStamp = "1409659813";
// $sReqNonce = HttpUtils.ParseUrl("nonce");
$sReqNonce = "1372623149";
// post�������������
// $sReqData = HttpUtils.PostData();
$sReqData = "<xml><ToUserName><![CDATA[wx5823bf96d3bd56c7]]></ToUserName><Encrypt><![CDATA[RypEvHKD8QQKFhvQ6QleEB4J58tiPdvo+rtK1I9qca6aM/wvqnLSV5zEPeusUiX5L5X/0lWfrf0QADHHhGd3QczcdCUpj911L3vg3W/sYYvuJTs3TUUkSUXxaccAS0qhxchrRYt66wiSpGLYL42aM6A8dTT+6k4aSknmPj48kzJs8qLjvd4Xgpue06DOdnLxAUHzM6+kDZ+HMZfJYuR+LtwGc2hgf5gsijff0ekUNXZiqATP7PF5mZxZ3Izoun1s4zG4LUMnvw2r+KqCKIw+3IQH03v+BCA9nMELNqbSf6tiWSrXJB3LAVGUcallcrw8V2t9EL4EhzJWrQUax5wLVMNS0+rUPA3k22Ncx4XXZS9o0MBH27Bo6BpNelZpS+/uh9KsNlY6bHCmJU9p8g7m3fVKn28H3KDYA5Pl/T8Z1ptDAVe0lXdQ2YoyyH2uyPIGHBZZIs2pDBS8R07+qN+E7Q==]]></Encrypt><AgentID><![CDATA[218]]></AgentID></xml>";
$sMsg = "";  // ����֮�������
$errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
if ($errCode == 0) {
	// ���ܳɹ���sMsg��Ϊxml��ʽ������
    var_dump($sMsg);
	// TODO: �����ĵĴ���
	/*
	"<xml><ToUserName><![CDATA[wx5823bf96d3bd56c7]]></ToUserName>
<FromUserName><![CDATA[mycreate]]></FromUserName>
<CreateTime>1409659813</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[hello]]></Content>
<MsgId>4561255354251345929</MsgId>
<AgentID>218</AgentID>
</xml>"
*/
} else {
	print("ERR: " . $errCode . "\n\n");
	//exit(-1);
}

/*
------------ʹ��ʾ��������ҵ�ظ��û���Ϣ�ļ���---------------
��ҵ�����ظ��û�����ϢҲ��Ҫ���м��ܣ�����ƴ�ӳ����ĸ�ʽ��xml����
������ҵ��Ҫ�ظ��û����������£�
<xml>
<ToUserName><![CDATA[mycreate]]></ToUserName>
<FromUserName><![CDATA[wx5823bf96d3bd56c7]]></FromUserName>
<CreateTime>1348831860</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[this is a test]]></Content>
<MsgId>1234567890123456</MsgId>
<AgentID>128</AgentID>
</xml>
Ϊ�˽��˶����Ļظ����û�����ҵӦ��
1.�Լ�����ʱ��ʱ���(timestamp),������ִ�(nonce)�Ա�������Ϣ��ǩ����Ҳ����ֱ���ôӹ���ƽ̨��post url�Ͻ������Ķ�Ӧֵ��
2.�����ļ��ܵõ����ġ�
3.�����ģ�����1���ɵ�timestamp,nonce����ҵ�ڹ���ƽ̨�趨��token������Ϣ��ǩ����
4.�����ģ���Ϣ��ǩ����ʱ�����������ִ�ƴ�ӳ�xml��ʽ���ַ��������͸���ҵ�š�
����2��3��4�������ù���ƽ̨�ṩ�Ŀ⺯��EncryptMsg��ʵ�֡�
*/

// ��Ҫ���͵�����
$sRespData = "<xml><ToUserName><![CDATA[mycreate]]></ToUserName><FromUserName><![CDATA[wx5823bf96d3bd56c7]]></FromUserName><CreateTime>1348831860</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[this is a test]]></Content><MsgId>1234567890123456</MsgId><AgentID>128</AgentID></xml>";
$sEncryptMsg = ""; //xml��ʽ������
$errCode = $wxcpt->EncryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
if ($errCode == 0) {
    var_dump($sEncryptMsg);
	print("done \n");
	// TODO:
	// ���ܳɹ�����ҵ��Ҫ������֮���sEncryptMsg����
	// HttpUtils.SetResponce($sEncryptMsg);  //�ظ�����֮�������
} else {
	print("ERR: " . $errCode . "\n\n");
	// exit(-1);
}
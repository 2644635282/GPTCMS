<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;
require_once(app_path().'/Alipay/AliPay.php');

class Alipay extends BaseUser{
	public function verify($content, $sign, $publicKeyPem)
    {
        $pubKey = $publicKeyPem;
        $res = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($pubKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');

        //调用openssl内置方法验签，返回bool值
        $result = FALSE;
        $result = (openssl_verify($content, base64_decode($sign), $res, OPENSSL_ALGO_SHA256) === 1);
        return $result;
    }

    public function verifyParams($parameters, $publicKey)
    {
        $sign = $parameters['sign'];
        $content = $this->getSignContent($parameters);
        return $this->verify($content, $sign, $publicKey);
    }

    public function getSignContent($params)
    {
        ksort($params);
        unset($params['sign']);
        unset($params['sign_type']);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if ("@" != substr($v, 0, 1)) {
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }

	public function set(){
		$wid = Session::get("wid");
        $data['status'] = $this->req->param("status")?:0;
		$data['app_id'] = $this->req->param("app_id");
		$data['merchant_private_key'] = $this->req->param("merchant_private_key");
		$data['alipay_public_key'] = $this->req->param("alipay_public_key");
		$info = Db::table("kt_gptcms_alipay_config")->where(["wid"=>$wid])->find();
		$data["wid"] = $wid;
		if($info)$data["id"] = $info["id"];
		Db::table("kt_gptcms_alipay_config")->save($data);

		return success("操作成功");
	}

	public function info(){
		$wid = Session::get("wid");
		$info = Db::table("kt_gptcms_alipay_config")->where(["wid"=>$wid])->find();

		return  success("获取成功",$info);
	}

    public function callBack(){
    	$data = $this->req->param();
    	if(!$data)return "success";
    	$order = Db::table('kt_gptcms_pay')->where('out_trade_no',$data["out_trade_no"])->where('ifok',0)->find();
		$info = Db::table("kt_gptcms_alipay_config")->where(["wid"=>$order["wid"]])->find();
		$sign = $this->verifyParams($data,$info["alipay_public_key"]);
		if($sign){
			Db::table('kt_gptcms_pay')->where('out_trade_no',$data["out_trade_no"])->update([
                'update_time'=> date('Y-m-d H:i:s'),
                'uip'=> request()->ip(),
                'status' => '交易成功',
                'ifok'=> 1,
                'jyh' => $data['buyer_id']
            ]);
            Db::table('kt_gptcms_pay_order')->where("id",$order['orderid'])->update([
                "status" => 2,
                "transaction_id" => $data['buyer_id'],
                "pay_time" => date("Y-m-d H:i:s"),
                "u_time" => date("Y-m-d H:i:s"),
            ]);
           	$this->buy($order['orderid']);
		}
        echo 'success';
        exit;
    }

    public function buy($id)
    {
    	$order = Db::table('kt_gptcms_pay_order')->find($id);
    	if(!$order) return '';
    	$user = Db::table('kt_gptcms_common_user')->find($order['common_id']);
    	$vip_expire = $user['vip_expire'] ? strtotime($user['vip_expire']) : 0;
    	if($vip_expire < time()) $vip_expire = time(); 
    	$data = [
    		'u_time' => date("Y-m-d H:i:s"),
    	];
    	switch ($order['type']) {
    		case 1:
    			$vip_expire = $vip_expire + ($order['number']*86400);
                $data['vip_expire'] = date("Y-m-d H:i:s",$vip_expire);
                if(!$user['vip_open']) $data['vip_open'] = date("Y-m-d H:i:s");
                $this->openVipGiveNum($order['setmeal_id'],$user);
    			break;
    		case 2:
    			$vip_expire = $vip_expire + ($order['number']*86400*7);
    			$data['vip_expire'] = date("Y-m-d H:i:s",$vip_expire);
                if(!$user['vip_open']) $data['vip_open'] = date("Y-m-d H:i:s");
                $this->openVipGiveNum($order['setmeal_id'],$user);
    			break;
    		case 3:
    			$vip_expire = $vip_expire + ($order['number']*86400*30);
    			$data['vip_expire'] = date("Y-m-d H:i:s",$vip_expire);
                if(!$user['vip_open']) $data['vip_open'] = date("Y-m-d H:i:s");
                $this->openVipGiveNum($order['setmeal_id'],$user);
    			break;
    		case 4:
    			$vip_expire = $vip_expire + ($order['number']*86400*30*3);
    			$data['vip_expire'] = date("Y-m-d H:i:s",$vip_expire);
                if(!$user['vip_open']) $data['vip_open'] = date("Y-m-d H:i:s");
                $this->openVipGiveNum($order['setmeal_id'],$user);
    			break;
    		case 5:
    			$vip_expire = $vip_expire + ($order['number']*86400*365);
    			$data['vip_expire'] = date("Y-m-d H:i:s",$vip_expire);
                if(!$user['vip_open']) $data['vip_open'] = date("Y-m-d H:i:s");
                $this->openVipGiveNum($order['setmeal_id'],$user);
    			break;
    		case 9:
    			$data['residue_degree'] = $user['residue_degree'] + $order['number'];
    			break;
    	}
    	$res = Db::table('kt_gptcms_common_user')->where('id',$order['common_id'])->update($data);
    	return 'ok';
    }

    /**
     * 开通vip赠送条数
     */
    public function openVipGiveNum($setmeal_id,$user)
    {
        $setmeal = Db::table('kt_gptcms_vip_setmeal')->find($setmeal_id);
        if(@$setmeal['give_num']){
            Db::table('kt_gptcms_common_user')->where('id',$user['id'])->update([
                'residue_degree' => $user['residue_degree'] + $setmeal['give_num']
            ]);
        }
    }
}
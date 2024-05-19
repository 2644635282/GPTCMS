<?php
declare (strict_types = 1);

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Session;
use think\facade\Log;

class PayNotify extends BaseApi
{
	public function webchat(){
        $post= file_get_contents("php://input");
        if (!$post) $post = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        if (!$post) exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>');
        libxml_disable_entity_loader(true);
        $xml = simplexml_load_string($post,'SimpleXMLElement',LIBXML_NOCDATA);
        $data = (array)$xml;
        $out_trade_no = isset($data['out_trade_no']) && !empty($data['out_trade_no']) ? $data['out_trade_no'] : 0;
        $order = Db::table('kt_gptcms_pay')->where('out_trade_no',$out_trade_no)->where('ifok',0)->find();
        if($order){ 
            $config = Db::table('kt_gptcms_pay_config')->where('wid',$order['wid'])->find();
            $wxKey = explode(',',$config['config'])[2] ?? null;
            
            // $oldSign = $data['sign'];
            // $signA = "appid=".$data['appid']."&bank_type=".$data['bank_type']."&cash_fee=".$data['cash_fee']."&fee_type=".$data
            // ['fee_type']."&is_subscribe=".$data['is_subscribe']."&mch_id=".$data['mch_id']."&nonce_str=".$data['nonce_str']."&openid=".  
            // $data['openid']."&out_trade_no=".$data['out_trade_no']."&result_code=".$data['result_code']."&return_code=".$data
            // ['return_code']."&time_end=".$data['time_end']."&total_fee=".$data['total_fee']."&trade_type=".$data
            // ['trade_type']."&transaction_id=".$data['transaction_id']."&key=".$wxKey;
            // $newSign = strtoupper(MD5($signA));
            if($this->confirm_sign($data,$wxKey)){
                Db::table('kt_gptcms_pay')->where('out_trade_no',$out_trade_no)->update([
                    'update_time'=> date('Y-m-d H:i:s'),
                    'uip'=> request()->ip(),
                    'status' => '交易成功',
                    'ifok'=> 1,
                    'jyh' => $data['transaction_id']
                ]);
                Db::table('kt_gptcms_pay_order')->where("id",$order['orderid'])->update([
                    "status" => 2,
                    "transaction_id" => $data['transaction_id'],
                    "pay_time" => date("Y-m-d H:i:s"),
                    "u_time" => date("Y-m-d H:i:s"),
                ]);
               	$this->buy($order['orderid']);
            }
        }
        echo 'SUCCESS';
        exit;
    }
    
    private function buy($id)
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

    public function confirm_sign($data,$key,$sign_key='sign'){
        if (key_exists($sign_key, $data)) {
            $sign = $data[$sign_key];
        }
        if (!$sign){
            return false;
        }
        $data2=$data;
        unset($data2['sign']);
        ksort($data2);
        $string = $this->array_to_string($data2);
        $string = $string . "&key=" . $key;
        $string = md5($string);
        $sign2 = strtoupper($string);
        return $sign==$sign2;
    }
    
    public function array_to_string($params){
        $string = '';
        if (!empty($params)) {
            $array = [];
            foreach ($params as $key => $value) {
                $array[] = $key . '=' . $value;
            }
            $string = implode("&", $array);
        }
        return $string;
    }
}
<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\user;

use think\facade\Db;
use think\facade\Log;
use app\BaseController;
use paySDK\NewPay;



/**
 * 外部联系人回调
 */
class CallbackWxPay extends BaseController
{
	public function webchat(){
        $post= file_get_contents("php://input");
        if (!$post) $post = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
        if (!$post) exit('<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>');
        libxml_disable_entity_loader(true);
        $xml = simplexml_load_string($post,'SimpleXMLElement',LIBXML_NOCDATA);
        $data = (array)$xml;
        $out_trade_no = isset($data['out_trade_no']) && !empty($data['out_trade_no']) ? $data['out_trade_no'] : 0;
        $order = Db::table('kt_base_recharge')->where('bh',$out_trade_no)->where('ifok',0)->find();
        if($order){ 
            $user = Db::table('kt_base_user')->where('id',$order['wid'])->find();
            $config = Db::table('kt_base_pay_config')->where(['uid'=>$user['agid'],'type'=>'wx'])->find();
            $wxKey = explode(',',$config['config'])[2] ?? null;
            
            // $oldSign = $data['sign'];
            // $signA = "appid=".$data['appid']."&bank_type=".$data['bank_type']."&cash_fee=".$data['cash_fee']."&fee_type=".$data
            // ['fee_type']."&is_subscribe=".$data['is_subscribe']."&mch_id=".$data['mch_id']."&nonce_str=".$data['nonce_str']."&openid=".  
            // $data['openid']."&out_trade_no=".$data['out_trade_no']."&result_code=".$data['result_code']."&return_code=".$data
            // ['return_code']."&time_end=".$data['time_end']."&total_fee=".$data['total_fee']."&trade_type=".$data
            // ['trade_type']."&transaction_id=".$data['transaction_id']."&key=".$wxKey;
            // $newSign = strtoupper(MD5($signA));
    
            if($this->confirm_sign($data,$wxKey)){
                Db::table('kt_base_user')->where('id',$user['id'])->update(['balance'=>$user['balance'] + $data['total_fee']/100 ]);
                Db::table('kt_base_recharge')->where('bh',$out_trade_no)->update([
                    'update_time'=> date('Y-m-d H:i:s'),
                    'uip'=> request()->ip(),
                    'status' => '交易成功',
                    'ifok'=> 1,
                    'jyh' => $data['transaction_id']
                ]);
               	$this->buy($out_trade_no);
            }
        }
        echo 'SUCCESS';
        exit;
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

    public  function buy($bh)
 	{
 		$order = Db::table('kt_base_user_order')->where('bh',$bh)->find();
 		$app = Db::table("kt_base_market_app")->json(['specs'])->find($order['app_id']);
		$specs = [];
		foreach ($app['specs'] as $v) {
			if($v['id'] == $order['specs_id']){
				$specs = $v;
				break;
			}
		}
		$wid = $order['wid'];
		$user = Db::table("kt_base_user")->find($wid);
		$data = [
			'wid' => $wid,
 			'name' => $app['name'],
 			'code' => $app['code'],
 			'logo' => $app['logo'],
 			'version' => $app['version'],
 			// 'mend_time' => date("Y-m-d H:i:s",strtotime("+".$app['try_days']." day")),
 			'update_time' => date("Y-m-d H:i:s"),
 			'app_id' => $order['app_id'],
		];

 		$has = Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$order['app_id'])->find();
 		if($has){
 			$data['id'] = $has['id'];
 			if(strtotime($has['mend_time'] < time())){
 				$date = date("Y-m-d H:i:s");
 			}else{
 				$date = $has['mend_time'];
 			} 
 		}else{
 			$date = date("Y-m-d H:i:s");
			$data['create_time'] = date("Y-m-d H:i:s");
 		}
 		// [{ "id":1,"duration": 1, "duration_type": 1, "old_price": 0.02, "price": 0.01 }]
 		$content = '';
 		$day = 0;
 		switch ($specs['duration_type']) {
 			case 2:
 				$content = '用户购买规格时长'.$specs['duration'].'月的套餐';
 				$data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$specs['duration']." month",strtotime($date)));
 				break;
 			case 3:
 				$content = '用户购买规格时长'.$specs['duration'].'年的套餐';
 				$data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$specs['duration']." year",strtotime($date)));
 				break;
 		}
 		$pid = 0;
 		if ($app['pid'] != 0) {
 			$pApp = Db::table("kt_base_market_app")->find($app['pid']);
 			$pOpenapp = Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$pApp['id'])->find();
 			$pid = $pOpenapp['id'];
 		}
 		$data['pid'] = $pid;
 		$res = Db::table("kt_base_user_openapp")->save($data);

 		if($res){
 			Db::table("kt_base_user")->where('id',$wid)->update([
 				'balance' => Db::raw('balance-'.$order['price']),
 				'update_time'=>date("Y-m-d H:i:s"),
 			]);
 			Db::table("kt_base_user_order")->where('id',$order['id'])->update([
 				'status' => 2,
 				'content' => $content
 			]);
 			return 'ok';
 		}
 		return 'error';
 	}

}
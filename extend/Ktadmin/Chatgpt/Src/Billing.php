<?php
namespace Ktadmin\Chatgpt\Src;

use Ktadmin\Chatgpt\Ktadmin;
/**
 * 财务
 */
class Billing
{
    private $ktadmin;

    public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

    /**
     * 查询余额
     */
    public function creditGrants()
    {
        $channel = $this->ktadmin->getChannel();
        $now = time();
        $startDate = date('Y-m-d', $now - 99*24*60*60);
        $endDate = date('Y-m-d', $now);
        if($channel == 1){
            $ueUrl = "/v1/dashboard/billing/usage?start_date={$startDate}&end_date={$endDate}";
            $usage = $this->ktadmin->curlGetRequest($ueUrl);
            if(isset($usage['error'])) return ['status'=>'error','message'=>$usage['error']['message']];
            $total_used = round($usage['total_usage'] / 100, 3);

            $snUlr = "/v1/dashboard/billing/subscription";
            $subscription = $this->ktadmin->curlGetRequest($snUlr);
            if(isset($subscription['error'])) return ['status'=>'error','message'=>$subscription['error']['message']];
            $total_granted = round($subscription['hard_limit_usd'], 3);
            $total_available = round(($total_granted * 1000 - $total_used * 1000) / 1000, 3);
            return [
                'total_granted' => $total_granted,
                'total_used' => $total_used,
                'total_available' => $total_available
            ];
        }elseif($channel == 2){
            $url = "/dashboard/billing/credit_grants";
            $res = $this->ktadmin->curlGetRequest($url);
            if($res['object'] == 'error') return ['status'=>'error','message'=>$res['message']];
            return $res;
        }
    }
}
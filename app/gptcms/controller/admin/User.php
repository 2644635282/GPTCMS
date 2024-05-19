<?php 
namespace app\gptcms\controller\admin;
use think\facade\Db;
use app\gptcms\controller\BaseAdmin;
use Ramsey\Uuid\Uuid;
use app\base\model\admin\user\UserModel as UserModels;
use think\facade\Session;
use app\base\model\BaseModel;
use app\gptcms\model\admin\UserModel;
use app\kt_agent\model\admin\agent\ManageModel;
use app\gptcms\model\admin\SetMealModel;



/**
* 应用内 用户管理
**/
class User extends BaseAdmin
{   

     /**
     * 获取 拥有应用权限的用户
     * @return \think\Response
     */
     public function list(){
        $res = UserModel::list($this->req);
        return success('用户',$res);
     }
     /**
     * 设置 应用权限给用户
     * @return \think\Response
     */
     public function add(){
        $id = $this->req->post('id');
        if(!$id) return error('参数错误');
        $res = UserModel::add($id);
        return success('添加成功');
     }


    
    /*
    * 获取当前gptcms的套餐列表
    */
    public function getSetMeal(){
        $id = $this->req->param("id"); //用户ID、
        if(!$id) return error("参数错误");
        $user = UserModels::getUser($id);
        $set_meal = SetMealModel::setmealExplain($user["agid"]);
        $price = $set_meal["price"];
        foreach ($price as $key=>$value){
            $data["level"] = $key;
            $data["price"] = $value;
            $type = Db::table("kt_gptcms_setmeal_type")->find(["level"=>$key]);
            $data["name"] = $type["name"];
            $res[] = $data;
        }
        return success('获取成功',$res);
    }


    /*
    * 获取当前用户的套餐
    */
    public function getAppAuth(){
        $id = $this->req->param("id"); //用户ID、
        if(!$id) return error("参数错误");
        $res = UserModel::getAppAuth($id);

        return success("获取成功",$res);
    }


    /**
    * 升级续费时的价格
    */
    public function prices(){
        $name = "gptcms";
        $prices = 0;
        $id = $this->req->param("id"); //用户ID、
        $mend_time = $this->req->param("mendTime");//到期时间
        $setmeal_id = $this->req->param("setmealId");//套餐id
        if(!$id || !$mend_time || !$setmeal_id) return error("参数错误");
        //用户信息
        $user = UserModels::getUser($id);
        //当前用户拥有的套餐
        $user_setmeal = UserModel::getUserSetmeal($id);
        //套餐级别
        $setmeal_level = SetMealModel::getSetmealInfo($setmeal_id)['level'];
        //获取代理已配置的套餐
        $set_meal = SetMealModel::setmealExplain($user["agid"]);
        //选择套餐的价格
        $price = $set_meal["price"][$setmeal_level];
        //当前套餐的价格
        $user_price = 0;
        $price_difference = 0;
        if($user_setmeal) $user_price = $set_meal["price"][$user_setmeal["level"]];
        if($user_setmeal && $price != $user_price && strtotime($user_setmeal["mend_time"]) >= time()){
            //当前套餐剩日价格
            $day_price = $user_price/365;//当前会员日价格
            //当前套餐剩余天数
            $day_time = floor((strtotime($user_setmeal["mend_time"])-time())/86400);//当前会员剩余天数（省略小数）
            //当前套餐剩余金额
            $return_price = floor($day_time * $day_price);//当前会员折扣金钱数  套餐剩余金额
            //新套餐日价格
            $day_price_new = $price/365;//新会员日价格
            //新套餐所需的金额
            $return_price_new = floor($day_price_new * $day_time);//新会员折扣金钱数
            //差价
            $price_difference = $return_price_new - $return_price >= 0 ? : 0; //需要的差价
        }
        //获取到指定的时间有多少年多少天
        $mend_times = self::getDateGap($user_setmeal["mend_time"]??date("Y-m-d",time()),$mend_time);
        //年价格 
        if($mend_times["year"]) $prices = $prices+$mend_times["year"]*$price;
        //日价格  套餐所需金额，到指定日期的金额 prices
        if($mend_times["day"]) $prices = $prices+ceil($mend_times["day"]*($price/365));

        return success('所需费用',$prices+$price_difference);
    }


    /*
    * 升级续费
    * "kt_".$name."_setmeal_type"  套餐名称表
    * "kt_".$name."_setmeal_price"  套餐设置表
    * "kt_".$name."_setmeal_record"  记录表
    */
    public function upgrade(){
        $name = "gptcms";
        $prices = 0;
        // $list = UserModels::list();
        // foreach ($list as $value){
        //  if($value["name"] == $name){
        //      $engine = $value;
        //      break;
        //  }
        // }
        $id = $this->req->param("id");
        $mend_time = $this->req->param("mendTime");
        $setmeal_id = $this->req->param("setmealId");
        if(!$id || !$mend_time || !$setmeal_id) return error("参数错误");
        //用户信息
        $user = UserModels::getUser($id);
        //当前用户拥有的套餐
        $user_setmeal = UserModel::getUserSetmeal($id);
        //套餐级别
        $setmeal_level = SetMealModel::getSetmealInfo($setmeal_id)['level'];
        //获取代理已配置的套餐
        $set_meal = SetMealModel::setmealExplain($user["agid"]);
        //选择套餐的价格
        $price = $set_meal["price"][$setmeal_level];
        //当前套餐的价格
        $user_price = 0;
        $price_difference = 0;
        if($user_setmeal) $user_price = $set_meal["price"][$user_setmeal["level"]];
        if($user_setmeal && $price != $user_price && strtotime($user_setmeal["mend_time"]) >= time()){
            //当前套餐剩日价格
            $day_price = $user_price/365;//当前会员日价格
            //当前套餐剩余天数
            $day_time = floor((strtotime($user_setmeal["mend_time"])-time())/86400);//当前会员剩余天数（省略小数）
            //当前套餐剩余金额
            $return_price = floor($day_time * $day_price);//当前会员折扣金钱数  套餐剩余金额
            //新套餐日价格
            $day_price_new = $price/365;//新会员日价格
            //新套餐所需的金额
            $return_price_new = floor($day_price_new * $day_time);//新会员折扣金钱数
            //差价
            $price_difference = $return_price_new - $return_price >= 0 ? : 0; //需要的差价
        }
        //获取到指定的时间有多少年多少天
        $mend_times = self::getDateGap($user_setmeal["mend_time"]??date("Y-m-d",time()),$mend_time);
        //年价格 
        if($mend_times["year"]) $prices = $prices+$mend_times["year"]*$price;
        //日价格  套餐所需金额，到指定日期的金额 prices
        if($mend_times["day"]) $prices = $prices+ceil($mend_times["day"]*($price/365));
        $money = $prices;
        //套餐差价如果大于0的情况下补齐差价，如果小于0的情况下舍弃不要
        if($prices && $price_difference > 0) $prices += $price_difference;
        $agent = UserModels::getAgent();
        if($agent["balance"] < $prices) return error("余额不足");
        if($agent["isadmin"] != 1){
            $discounty = 10;
            if(class_exists("app\kt_agent\model\admin\agent\ManageModel")){
                $discounty = ManageModel::discount() ?: 10;
            }
            $prices = floor($prices*($discount/10));
            // $agent_level = UserModels::getAgentLevel($agent["level"]);
            // if($agent_level) $prices = floor($prices*($agent_level["discount"]/10));
        }
        $add = UserModels::addSetmealRecord($id,$setmeal_id,$price_difference,$prices,$money,$mend_time,$mend_times["year"],$mend_times["day"],$name);
        
        UserModel::updUserSetmeal($id,$mend_time,$setmeal_level);
        if(!$add) return error("入库失败");
        $balance = $agent["balance"] - $prices;
        UserModels::updAgent($balance);
        //UserModels::updUsers($id,$setmeal_id,$mend_time);
        return success('续费成功,已扣除',$prices);
    }

    /*
    * 获取当前代理的数据
    */
    public function getAgent(){
        $user = UserModels::getAgent();
        return success("获取成功",$user["balance"]);
    }

    /*
    * 获取年份
    */
    public function getDateGap($mendtime,$end){
        $start = strtotime($mendtime);
        $end = strtotime($end);
        list($sy,$sm,$sd) = explode("-", date("Y-m-d", $start));
        list($ey,$em,$ed) = explode("-", date("Y-m-d", $end));
        $y = $ey - $sy;
        $m = $em - $sm;
        $w = date('W', $end) - date('W', $start);
        $d = $ed - $sd;
        if ($y > 0) { // 表示跨了年
            $m += 12 * $y;
            $startYear = date('Y', $start);
            $endYear = date('Y', $end);
            for ($i = $startYear; $i < $endYear; $i++) {
                for ($j = 31;$j > 24;$j--) {
                    $tmpWeek = date('W', strtotime(sprintf("%s-12-%s", $i, $j)));
                    if (intval($tmpWeek) != 1) { // 不是第一周，则是当年的最后一周
                        $w += $tmpWeek; // 加上当年的周数量
                        break;
                    }
                }
            }
        }
        if ($m > 0) { // 表示跨了月
            for($i = 1; $i <= $m; $i++) {
                $d += date('t', strtotime("+{$i} month", $start)); // 跨了月
            }
        }
        $years = floor($d/365);
        $tian = $years * 365;
        $days = $d-$tian;
        return ["year"=>$years,"day"=>$days];
    }

    /*
    * 获取当前登录代理的权限列表
    */
    public function getAuths(){
        $uid = Session::get("uid");
        $userAgency = Db::table('kt_base_agent')->find($uid);
        $auths = ["site","loginsetup","oss","sms","userlist","userAdd","agentlist","agentAdd","agentDiscount","releasechannel"];
        if($userAgency["isadmin"] == 1)return success('获取成功',["auths"=>$auths]);
        $auth = Db::table('kt_gptcms_agent_auth')->where(["level"=>$userAgency["level"]])->find();

        return success('获取成功',["auths"=>json_decode($auth["auths"],1)]);
    }

}
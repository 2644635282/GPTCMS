<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin\user;
use think\facade\Db;
use app\base\controller\BaseAdmin;
use app\base\model\admin\system\BasicModel;
use app\base\model\admin\user\UserModel;
use app\base\model\admin\plug\AppManageModel;
use app\base\model\BaseModel;
use Ramsey\Uuid\Uuid;
use think\facade\Session;

class Manage extends BaseAdmin
{
    /**
    * 我的客户管理
    */
    public function index()
    {
        $page = $this->req->param('page')?:1;
        $status = $this->req->param('status');
        $un = $this->req->param('un');
        $size = $this->req->param("size")?:10;
        $agname = $this->req->param("agname");
        $data = UserModel::info($page,$un,$status,$size,$agname);
        return success('获取成功',$data);
    }
    /**
    * 获取代理列表
    */
    public function getAgents()
    {
        $data = UserModel::getAgents();
        return success('获取成功',$data);
    }
    /*
    * 启用停用作废 1为启用，0为禁用，2为作废
    */
    public function switch()
    {
        $id = $this->req->param('id');
        if(!$id) return error("请选择账户");
        $status = $this->req->param('status');
        $data = UserModel::switch($id,$status);
        return success('更新成功',$data);
    }

    /**
    * username 账号
    * password 密码
    * telephone 手机号
    * contacts 联系人
    * remark 备注
    * agid 上级代理id
    * id 用户id
    */
    public function addUser()
    {
        $id = $this->req->param('id');
        $username = $this->req->param('username');
        if(!$username) return error("账号不可为空");
        $password = $this->req->param('password');
        if(!$password && !$id) return error("密码不可为空");
        $telephone = $this->req->param('telephone');
        // if(!$telephone) return error("手机号不可为空");
        $contacts = $this->req->param('contacts'); 
        // if(!$contacts) return error("联系人不可为空");
        $remark = $this->req->param('remark');
        $agid = $this->req->param('agid');
        $set = UserModel::setUser($username,$id);
        if($set) return error("用户名重复");
        if($id) $user = UserModel::user($id);
        if($password) $password = ktEncrypt($password);
        if(!$password && $id) $password = $user["pwd"]?:ktEncrypt("123456");
        $add = UserModel::addUser($username,$password,$telephone,$contacts,$agid,$remark,$id);

        return success('修改成功',$add);
    }
    /**
    * 编辑拉取数据
    */
    public function getUser()
    {
        $id = $this->req->param('id');
        if(!$id) return error("参数错误");
        $data = UserModel::getUser($id);

        return success('获取成功',$data);
    }
    /*
    *  充值
    *  先获取客户余额
    */
    public function recharge()
    {
        $id = $this->req->param("id");
        $type = $this->req->param("type");
        if(!$id) return error("参数错误");
        $money = $this->req->param("money");
        $remark = $this->req->param("remark");
        if($money <= 0) return error("充值金额不可小于1");
        $user = UserModel::getUser($id);
        $agent = UserModel::getAgent();
        if($type == 1){
            if($agent["balance"] < $money)return error("管理员余额不足，请重新选择");
            $agent_balance = $agent["balance"] - $money;
            $user_balance = $user["balance"] + $money;
        }
        if($type == 2){
            if($user["balance"] < $money)return error("客户余额不足，请重新选择");
            $agent_balance = $agent["balance"] + $money;
            $user_balance = $user["balance"] - $money;
        }
        UserModel::updAgent($agent_balance);
        UserModel::updUser($id,$user_balance);
        $out_trade_no = date('YmdHis', time()) . time() . rand(10000, 99999);
        $add = UserModel::addRecord($money,$out_trade_no,$type,$id,$remark);
        return success('充值成功',$add);
    }


    /*
    * 获取员工已开套餐及已购买应用
    */
    public function auth()
    {
        $id = $this->req->param("id");
        if(!$id) return error("参数错误，id不可为空");
        $auth = UserModel::auth($id);
        $data["appauth"] = $auth;
        return success('获取成功',$auth);
    }

    // 引擎列表
    public function engine()
    {
        $res = UserModel::list();
        $agent = UserModel::getAgent();
        $data["setmeal"] = $res;
        $data["balance"] = $agent["balance"];
        return success('获取成功',$data);
    }

    //选择引擎拿到引擎对应的套餐
    public function getMeal()
    {
        $name = $this->req->param("name");
        $id = $this->req->param("id");
        if(!$id) return error("参数错误");
        if(!$name) return error("参数错误");
        $user = UserModel::getUser($id);
        $set_meal = UserModel::getSetmeal($user["agid"],$name);
        $price = json_decode($set_meal["price"],1);
        $set_meal = UserModel::setMeal($price,$name);
        $appauth = UserModel::appauth($id,$name);
        $res["setMealList"] = $set_meal;
        $res["setMeal"]["set_meal"] = NULL;
        $res["setMeal"]["mend_time"] = time();
        if($appauth) $res["setMeal"] = $appauth;
        if($appauth) $res["setMeal"]["mend_time"] = strtotime($appauth["mend_time"]);
        return success('获取成功',$res);
    }


    /**
    * 升级续费时的价格
    */
    public function prices()
    {
        $prices = 0;
        $price_difference = 0;
        $name = $this->req->param("name");
        $id = $this->req->param("id");
        $mend_time = $this->req->param("mendTime");
        $title = $this->req->param("title");
        $code = $this->req->param("code");
        $setmeal_id = $this->req->param("setmealId");
        if(!$id || !$mend_time || !$setmeal_id || !$name) return error("参数错误");
        $user = UserModel::getUser($id);
        $appauth = UserModel::appauth($id,$name);
        //获取已配置套餐的代理
        $set_meal = UserModel::getSetmeal($user["agid"],$name);
        //选择套餐的价格
        $price = json_decode($set_meal["price"],1)[$setmeal_id];
        //当前套餐的价格
        $user_price = 0;
        if($appauth) $user_price = json_decode($set_meal["price"],1)[$appauth["set_meal"]];
        if($appauth && $price != $user_price && strtotime($appauth["mend_time"]) >= time()){
            //当前套餐剩日价格
            $day_price = $user_price/365;//当前会员日价格
            //当前套餐剩余天数
            $day_time = floor((strtotime($appauth["mend_time"])-time())/86400);//当前会员剩余天数（省略小数）
            //当前套餐剩余金额
            $return_price = floor($day_time * $day_price);//当前会员折扣金钱数  套餐剩余金额
            //新套餐日价格
            $day_price_new = $price/365;//新会员日价格
            //新套餐所需的金额
            $return_price_new = floor($day_price_new * $day_price);//新会员折扣金钱数
            //差价
            $price_difference = $return_price_new - $return_price; //需要的差价
        }
        //获取到指定的时间有多少年多少天
        $mend_times = self::getDateGap($appauth["mend_time"]??date("Y-m-d",time()),$mend_time);
        //年价格 
        if($mend_times["year"]) $prices = $prices+$mend_times["year"]*$price;
        //日价格  套餐所需金额，到指定日期的金额 prices
        if($mend_times["day"]) $prices = $prices+floor($mend_times["day"]*($price/365));
        return success('所需费用',$prices);
    }
    /*
    * 升级续费
    * "kt_".$name."_type"  套餐名称表
    * "kt_".$name."_setmeal"  套餐设置表
    * "kt_".$name."_setmeal_record"  记录表
    */
    public function upgrade()
    {
        $prices = 0;
        $price_difference = 0;
        $name = $this->req->param("name");
        $id = $this->req->param("id");
        $mend_time = $this->req->param("mendTime");
        $title = $this->req->param("title");
        $code = $this->req->param("code");
        $setmeal_id = $this->req->param("setmealId");
        if(!$id || !$mend_time || !$setmeal_id || !$name || !$title || !$code) return error("参数错误");
        $user = UserModel::getUser($id);
        // var_dump($id);die;
        $appauth = UserModel::appauth($id,$name);
        //获取已配置套餐的代理
        $set_meal = UserModel::getSetmeal($user["agid"],$name);
        //选择套餐的价格
        $price = json_decode($set_meal["price"],1)[$setmeal_id];
        //当前套餐的价格
        $user_price = 0;
        if($appauth) $user_price = json_decode($set_meal["price"],1)[$appauth["set_meal"]];
        if($appauth && $price != $user_price && strtotime($appauth["mend_time"]) >= time()){
            //当前套餐剩日价格
            $day_price = $user_price/365;//当前会员日价格
            //当前套餐剩余天数
            $day_time = floor((strtotime($appauth["mend_time"])-time())/86400);//当前会员剩余天数（省略小数）
            //当前套餐剩余金额
            $return_price = floor($day_time * $day_price);//当前会员折扣金钱数  套餐剩余金额
            //新套餐日价格
            $day_price_new = $price/365;//新会员日价格
            //新套餐所需的金额
            $return_price_new = floor($day_price_new * $day_price);//新会员折扣金钱数
            //差价
            $price_difference = $return_price_new - $return_price; //需要的差价
        }
        //获取到指定的时间有多少年多少天
        $mend_times = self::getDateGap($appauth["mend_time"]??date("Y-m-d",time()),$mend_time);
        //年价格 
        if($mend_times["year"]) $prices = $prices+$mend_times["year"]*$price;
        //日价格  套餐所需金额，到指定日期的金额 prices
        if($mend_times["day"]) $prices = $prices+floor($mend_times["day"]*($price/365));
        $money = $prices;
        //套餐差价如果大于0的情况下补齐差价，如果小于0的情况下舍弃不要
        if($prices && $price_difference > 0) $prices += $price_difference;
        $agent = UserModel::getAgent();
        if($agent["balance"] < $prices) return error("余额不足");
        if($agent["isadmin"] != 1){
            $agent_level = UserModel::getAgentLevel($agent["level"]);
            if($agent_level) $prices = floor($prices*($agent_level["discount"]/10));
        }
        $add = UserModel::addSetmealRecord($id,$setmeal_id,$price_difference,$prices,$money,$mend_time,$name,$mend_times["year"],$mend_times["day"]);
        UserModel::updAppauth($id,$code,$title,$name,$mend_time,$setmeal_id);
        if(!$add) return error("入库失败");
        $balance = $agent["balance"] - $prices;
        UserModel::updAgent($balance);
        UserModel::updUsers($id,$setmeal_id,$mend_time);
        return success('续费成功,已扣除',$prices);
    }
    
    public function getDateGap($mendtime,$end)
    {
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
    * 越权登录获取token
    */
    public function getToken(){
        $id = $this->req->param('id');
        if(!$id) return error("参数错误");
        $res = UserModel::getToken($id);
        return success('获取成功',["token"=>$res["token"]]);
    }

    /*
    * 拉取所属代理
    */
    public function getAdents(){
        $uid = Session::get("uid");
        $ids = BaseModel::getAdentIds($uid);
        $data = UserModel::getAdents($ids);

        return success("获取成功",$data);
    }
}
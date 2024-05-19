<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use app\base\model\BaseModel;
use think\facade\Session;

class Index extends BaseUser
{
    
    public function index()
    {
        $wid = 1; 
        // 设置时区为中国上海
        date_default_timezone_set('Asia/Shanghai');
        
        // 获取今天的开始时间戳（0点）
        $time_one = strtotime(date('Y-m-d 00:00:00'));
        
        // 获取当前时间戳
        $time_tow = time();

        $chat = Db::table("kt_gptcms_chat_msg")->where('wid',$wid)->whereBetweenTime('c_time',$time_one,$time_tow)->count();
        $create = Db::table("kt_gptcms_create_msg")->where('wid',$wid)->whereBetweenTime('c_time',$time_one,$time_tow)->count();
        $role = Db::table("kt_gptcms_role_msg")->where('wid',$wid)->whereBetweenTime('c_time',$time_one,$time_tow)->count();
                    
        $data["total"] = $chat + $create + $role;

        return $data['total'] ;
        
    }

    public function getRandStr()
    {
    	$len = $this->req->param('len/d',6);
    	return success('随机字符串',getRandStr($len));
    } 
    /**
    *获取登录页相关信息
    **/
    public function getLoginInfo(){
        $res = BaseModel::getLoginInfo($this->host);
        return success("登陆前相关信息",$res);
    }  

    public function statisticsHeader()
    {
        $wid = Session::get('wid');
        $data = [];
        $data['user'] = [
            'new_user' => Db::table("kt_gptcms_common_user")->where('wid',$wid)->whereDay('c_time')->count(),
            'vew_vip_user' => Db::table("kt_gptcms_common_user")->where('wid',$wid)->whereDay('vip_open')->count(),
            'total_user' => Db::table("kt_gptcms_common_user")->where('wid',$wid)->count(),
        ];
        $data['order'] = [
            'today_amount' => Db::table("kt_gptcms_pay_order")->where('wid',$wid)->where("status",2)->whereDay('pay_time')->sum("amount"),
            'total_amount' => Db::table("kt_gptcms_pay_order")->where('wid',$wid)->where("status",2)->sum("amount"),
            'total_order' => Db::table("kt_gptcms_pay_order")->where('wid',$wid)->where("status",2)->count()
        ];

        return success('首页头部统计数据',$data);
    }
    public function statisticsZx()
    {
        $wid = Session::get('wid');
        $data = [];
        $type = $this->req->param("type","new_user"); //new_user 新增用户  new_order新增订单 new_chat 新增聊天
        $start = $this->req->param("start");
        $end = $this->req->param("end");
        if(!($start && $end)){
            $start = date('Y-m-d',strtotime("-1 week"));
            $end = date('Y-m-d',strtotime("-1 day"));
        }
        $time = strtotime($end) - strtotime($start);
        $time_tow = $start;
        for ($i=0;$i<=$time/(3600*24);$i++){
            $time_one = date("Y-m-d",strtotime($time_tow));
            $time_tow = date("Y-m-d",strtotime($time_tow)+3600*24);
            $data["date"][$i]=$time_one;
            switch ($type) {
                case 'new_user':
                    $data["total"][$i] = Db::table("kt_gptcms_common_user")->where('wid',$wid)->whereBetweenTime('c_time',$time_one,$time_tow)->count();
                     break;
                case 'new_order':
                    $data["total"][$i] = Db::table("kt_gptcms_pay_order")->where('wid',$wid)->where("status",2)->whereBetweenTime('pay_time',$time_one,$time_tow)->count();
                    break;
                case 'new_chat':
                    $chat = Db::table("kt_gptcms_chat_msg")->where('wid',$wid)->whereBetweenTime('c_time',$time_one,$time_tow)->count();
                    $create = Db::table("kt_gptcms_create_msg")->where('wid',$wid)->whereBetweenTime('c_time',$time_one,$time_tow)->count();
                    $role = Db::table("kt_gptcms_role_msg")->where('wid',$wid)->whereBetweenTime('c_time',$time_one,$time_tow)->count();
                    $data["total"][$i] = $chat + $create + $role;
                    break;
            }
        }

        return success('首页折线图统计数据',$data);
    }

    public function getMenuAuth()
    {
        $gptcms_key = root_path().'/app/gptcms_key';
        $data['gptcms_key'] = false;
        if(file_exists($gptcms_key)){
           $data['gptcms_key'] = true;
        }
        return success('ok',$data);
    }

}

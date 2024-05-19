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
use app\base\controller\BaseUser;
use Ramsey\Uuid\Uuid;
use think\facade\Session;
use think\facade\Cache;
use app\base\model\BaseModel;

class Users extends BaseUser
{
    public function bindphone()
    {
        $wid = Session::get('wid');
        $user = Db::table('kt_base_user')->find($wid);
        if(!$user) return error('系统错误');
        $phone = $this->req->param("phone");
        $verfiy = $this->req->param('verfiy');
        if(!preg_match("/^1[0-9]{10}/",$phone)) return error('手机号码格式错误');
        $key = 'sms_'.$phone;
        if($verfiy != Cache::get($key)) return error('验证码错误');

        if($user['telephone'] == $phone){
            //解绑手机号
            Db::table('kt_base_user')->save([
                'id' => $user['id'],
                'telephone' => ''
            ]);
            return success('解绑成功');
        }
        $hasuser = Db::table('kt_base_user')->where('telephone',$phone)->find();
        if($hasuser) return error('手机号已被绑定');
        Db::table('kt_base_user')->save([
            'id' => $user['id'],
            'telephone' => $phone
        ]);
        return success('绑定成功');
    }

    public function info()
    {
        $wid = Session::get('wid');
        $user = Db::table('kt_base_user')->find($wid);
        if(!$user) return error('用户不存在');
        return success('用户详情',$user);
    } 

    public function conversion()
    {
        $wid = Session::get("wid");
        $uid = Session::get("uid");
        $code = $this->req->post("code");
        if(!$code) return error("请输入卡密");
        $detail = Db::table("kt_base_admin_carddetail")->where("uid",$uid)->where("code",$code)->find();
        if(!$detail) return error("卡密不存在");
        if($detail["status"]) return error("卡密已使用");
        $card =  Db::table("kt_base_admin_card")->find($detail["pid"]); 
        $uesr = $this->user;
        $codeArr = [];
        switch ($card["type"]) {
            case '1':
                $package = Db::table("kt_base_app_package")->json(["specs","apps"])->find($card["package_id"]);
                $specs = "";
                foreach ($package["specs"] as $s) {
                    if($s["id"] == $card["specs_id"]){
                        $specs = $s;
                        break;
                    }
                }
                $card["duration"] =  $specs["duration"];
                $card["duration_type"] =  $specs["duration_type"];
                $codeArr = $package["apps"];
                break;
            case '2':
                $codeArr = [$card["code"]];
                break;      
        }
        foreach ($codeArr as $appcode) {
            $app = Db::table("kt_base_market_app")->where('code',$appcode)->find();
            $data = [
                'wid' => $wid,
                'name' => $app['name'],
                'code' => $app['code'],
                'logo' => $app['logo'],
                'version' => $app['version'],
                // 'mend_time' => date("Y-m-d H:i:s",strtotime("+".$app['try_days']." day")),
                'update_time' => date("Y-m-d H:i:s"),
                'app_id' => $app['id'],
            ];

            $has = Db::table("kt_base_user_openapp")->where('wid',$wid)->where('app_id',$app['id'])->find();
            if($has){
                $data['id'] = $has['id'];
                if(strtotime($has['mend_time'] < time())){
                    $date = time();
                }else{
                    $date = strtotime($has['mend_time']);
                } 
            }else{
                $date = time();
                $data['create_time'] = date("Y-m-d H:i:s");
            }
            switch ($card['duration_type']) {
                case 1:
                    $data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$card['duration']." day",$date));
                    break;
                case 2:
                    $data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$card['duration']." month",$date));
                    break;
                case 3:
                    $data['mend_time'] = date("Y-m-d H:i:s",strtotime("+".$card['duration']." year",$date));
                    break;
            }
            $res = Db::table("kt_base_user_openapp")->save($data);
        }

        Db::table("kt_base_admin_carddetail")->where("id",$detail["id"])->update([
            "time" => date("Y-m-d H:i:s"),
            "user" => $wid,
            "status" => 1,
        ]);
        return success("兑换成功");
    }

    public function getDirectApp(){
        $wid = Session::get('wid');
        $user = Db::table('kt_base_user')->find($wid);
        if(!$user) return error('系统错误');
        $agent = Db::table('kt_base_agent')->where('domain',$this->host)->find();
        if(!$agent) return success('ok',['is_direct_app'=>0,'app_link'=>'','empty_desc'=>'']);
        if($agent['is_direct_app']){
            $appcode = $agent['direct_app'];
            if(!$appcode) return success('ok',['is_direct_app'=>1,'app_link'=>'','empty_desc'=>'未选择应用']);
            $appinfo = Db::table('kt_base_market_app')->where(['uid'=>$agent['id'],'code'=>$appcode])->find();
            if(!$appinfo) return success('ok',['is_direct_app'=>1,'app_link'=>'','empty_desc'=>'应用不存在']);
            $openapp = Db::table('kt_base_user_openapp')->where(['wid'=>$wid,'code'=>$appcode])->whereTime('mend_time','>',date('Y-m-d H:i:s'))->find();
            if(!$openapp) return success('ok',['is_direct_app'=>1,'app_link'=>'','empty_desc'=>'无权限访问应用']);
            return success('ok',['is_direct_app'=>1,'app_link'=>request()->domain().$appinfo['user_link'].'?token='.$user['token'],'empty_desc'=>'']);
        }else{
            return success('ok',['is_direct_app'=>0,'app_link'=>'','empty_desc'=>'']);
        }
    }

}
<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Session;
use Ramsey\Uuid\Uuid;
use think\facade\Log;
use think\facade\Cache;

class Users extends BaseApi
{
    /**
     * wx登录
     */
    public function wxLogin()
    {
        $wid = Session::get('wid');
        $code = $this->req->param('code')?:0;
        $parent = (int)$this->req->param("parent")?:0;
        if(!$wid) return error('缺少必要参数wid');
        if(!$code) return error('缺少参数Code');
        $wxgzh = Db::table('kt_gptcms_wxgzh')->where('wid', $wid)->find();
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$wxgzh['appid']}&secret={$wxgzh['appsecret']}&code={$code}&grant_type=authorization_code";
        $accessToken = json_decode(curlGet($url),true);
        if(!isset($accessToken['access_token'])) return error('登录失败');
        if(!isset($accessToken['openid'])) return error('登录失败');
        // $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$accessToken['access_token']}&openid={$accessToken['openid']}";
        // $userinfo = json_decode(curlGet($url),true);
        // if(!isset($userinfo['openid'])) return error('登录失败');
        $token = Uuid::uuid1();

        $hasRegsiter = Db::table('kt_gptcms_wx_user')->where(['wid'=>$wid,'openid'=>$accessToken['openid']])->find();
        if(!$hasRegsiter){
            $regtime = date("Y-m-d H:i:s",time());
            $common_id = Db::table('kt_gptcms_common_user')->insertGetId([
                'wid' => $wid,
                'type' => 'wx',
                'parent' => $parent,
                'nickname' => '微信用户',
                'headimgurl' => '',
                'account' => 'wx'.time().rand(1,300),
                'password' => ktEncrypt('123456'),
                'unionid' => '',
                'token' => "$token",
                'expire_time' => time() + (7*24*3600),
                'c_time' => $regtime,
                'u_time' => $regtime
            ]);
            Db::table('kt_gptcms_wx_user')->insert([
                'wid' => $wid,
                'common_id' => $common_id,
                'openid' => $accessToken['openid'],
                'nickname' => '微信用户',
                'headimgurl' => '',
                'sex' => 0,
                'city' => '',
                'province' => '',
                'country' => '',
                'unionid' => '',
                'c_time' => $regtime,
                'u_time' => $regtime
            ]);
            $this->registerReward($common_id);
            if($parent){
                $this->inviteReward($parent);
            }
            Log::error('wxLogin1'.json_encode($accessToken));
            return success('登录成功',['token'=>$token]);
        }

        $user = Db::table('kt_gptcms_common_user')->find($hasRegsiter['common_id']);
        if($user['status'] != 1 ) return error('账号因异常行为进入风控，请联系客服解除风控！error:006');
        $token = $user['token'] && $user['expire_time'] > time() ? $user['token'] : Uuid::uuid1();
        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->update([
            'token'=>"{$token}",
            'expire_time'=> time() + (7*24*3600)
        ]);
        Log::error('wxLogin2'.json_encode($accessToken));
        return success('登录成功',['token'=>$token]);
    }

    /**
     * wx用户信息
     */
    public function wxuserinfo()
    {
        $wid = Session::get('wid');
        $code = $this->req->param('code')?:0;
        if(!$wid) return error('缺少必要参数wid');
        if(!$code) return error('缺少参数Code');
        $wxgzh = Db::table('kt_gptcms_wxgzh')->where('wid', $wid)->find();
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$wxgzh['appid']}&secret={$wxgzh['appsecret']}&code={$code}&grant_type=authorization_code";
        $accessToken = json_decode(curlGet($url),true);
        if(!isset($accessToken['access_token'])) return error('登录失败');
        if(!isset($accessToken['openid'])) return error('登录失败');
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token={$accessToken['access_token']}&openid={$accessToken['openid']}";
        $userinfo = json_decode(curlGet($url),true);
        if(!isset($userinfo['openid'])) return error('登录失败');
        
        $wxuser = Db::table('kt_gptcms_wx_user')->where(['wid'=>$wid,'openid'=>$userinfo['openid']])->find();
        if(!$wxuser) return error('用户不存在');
        $user = Db::table('kt_gptcms_common_user')->find($wxuser['common_id']);
        if(!$user) return error('用户不存在');
        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->update([
            'nickname' => $userinfo['nickname']??'',
            'headimgurl' => $userinfo['headimgurl']??'',
            'unionid' => $userinfo['unionid']??''
        ]);
        Db::table('kt_gptcms_wx_user')->where(['id'=>$wxuser['id']])->update([
            'nickname' => $userinfo['nickname']??'',
            'headimgurl' => $userinfo['headimgurl']??'',
            'sex' => $userinfo['sex']??0,
            'city' => $userinfo['city']??'',
            'province' => $userinfo['province']??'',
            'country' => $userinfo['country']??'',
            'unionid' => $userinfo['unionid']??'',
            'u_time' => date("Y-m-d H:i:s",time())
        ]);
        Log::error('wxuserinfo'.json_encode($userinfo));
        return success('获取成功',$userinfo);
    }

    /**
     * 登录
     */
    public function login()
    {
        if(!$this->req->isPost()) return error('请使用POST请求');
        $wid = Session::get('wid');
        $mobile = $this->req->param('mobile');
        $password = $this->req->param('password');
        if(!$wid) return error('缺少必要参数wid');
        if(!$mobile) return error('请输入手机号');
        if(!$password) return error('请输入密码');
        $where = [
            ['account', '=', $mobile],
            ['mobile', '=', $mobile]
        ];
        $user = Db::table('kt_gptcms_common_user')
            ->where(['wid'=>$wid])
            ->where(function($query)use($where){
                $query->whereOr($where);
            })
            ->find();
        if(!$user) return error('用户不存在');
        if($user["lock_time"] > time()) return error("账户锁定中,请稍后再试");
        
        if($user['password'] != ktEncrypt($password)){
            Db::table("kt_gptcms_loginerror_record")->insert([
                "wid" => $wid,
                "account" => $mobile,
                "status" => 1,
                "c_time" => date("Y-m-d H:i:s"),
            ]);
            $lxpwderrornum = Db::table("kt_gptcms_loginerror_record")->where("wid",$wid)->whereTime("c_time","-10 minutes")->order("c_time","desc")->limit(5)->sum("status");
            if($lxpwderrornum >= 5){
                Db::table('kt_gptcms_common_user')->where('id',$user['id'])->update([
                    "lock_time" => time() + 3600
                ]);
            }
            return error('帐号或密码错误,如果密码连续输错5次,账户将会被锁定1小时');
        } 
        if($user['status'] != 1 ) return error('账号因异常行为进入风控，请联系客服解除风控！error:006.1');
        Db::table("kt_gptcms_loginerror_record")->insert([
                "wid" => $wid,
                "account" => $mobile,
                "status" => 0,
                "c_time" => date("Y-m-d H:i:s"),
            ]);
        $token = $user['token'] && $user['expire_time'] > time() ? $user['token'] : Uuid::uuid1();
        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->update(['token'=>"{$token}",'expire_time'=> time() + (7*24*3600)]);
        return success('登录成功',['token'=>$token]);
    }

    /**
     * 注册
     */
    public function register()
    {
    	$wid = Session::get('wid');
        
    	$parent = (int)$this->req->param("parent")?:0;
    	$mobile = $this->req->param("mobile");
        $password = $this->req->param("password");
        $code = $this->req->param("code");
        if(!$wid) return error('缺少必要参数wid');
        if(!$mobile) return error('缺少参数mobile');
        if(!$password) return error('缺少参数password');
        if(!preg_match("/^1[23456789]\d{9}$/", $mobile)) return error("手机号格式错误，请重新输入");
        $smsstatus = Db::table('kt_gptcms_websit')->where('wid',$wid)->value('sms') ?: 0;
        if($smsstatus && !$code) return error("请输入验证码");
        if($smsstatus && $code){
            $s_code = Cache::get("gptsms_".$mobile);
            if($s_code != $code) return error("验证码错误");
        }
        $account = $mobile;
        $where = [
        	['account', '=', $account],
        	['mobile', '=', $mobile]
        ];
        $user = Db::table('kt_gptcms_common_user')
            ->where('wid',$wid)
            ->where(function($query)use($where){
                $query->whereOr($where);
            })
            ->find();
        if($user) return error("账户已存在，请重新注册");
        $token = Uuid::uuid1();
        $common_id = Db::table('kt_gptcms_common_user')->insertGetId([
            'wid' => $wid,
            'type' => 'h5',
            'parent' => $parent,
            'mobile' => $mobile,
            'account' => $account,
            'password' => ktEncrypt($password),
            'token' => "$token",
            'expire_time' => time() + (7*24*3600),
            'c_time' => date("Y-m-d H:i:s",time()),
            'u_time' => date("Y-m-d H:i:s",time())
        ]);
        $this->registerReward($common_id);
        if($parent){
            $this->inviteReward($parent);
        }
        return success('注册成功',['token'=>$token]);
    }

    /**
     * 微信小程序登录
     */
    public function xcxLogin()
    {
        $wid = Session::get('wid');
        $code = $this->req->param('code')?:0;
        $parent = (int)$this->req->param("parent")?:0;
        if(!$wid) return error('缺少必要参数wid');
        if(!$code) return error('缺少参数Code');
        $nickname = $this->req->param('nickname')?:'';
        $headimgurl = $this->req->param('headimgurl')?:'';
        $sex = $this->req->param('sex')?:0;
        $city = $this->req->param('city')?:'';
        $province = $this->req->param('province')?:'';
        $country = $this->req->param('country')?:'';
        $config = Db::table('kt_gptcms_miniprogram')->where('wid',$wid)->find();
        if(!$config) return error("小程序暂不可用");
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $config['appid'] . '&secret=' . $config['appsecret'] . '&js_code=' . $code . '&grant_type=authorization_code';
        $wxdata = json_decode(curlGet($url),true);
        if(!isset($wxdata['openid'])) return error('登录失败');
        $openid = $wxdata['openid'];
        $unionid = $wxdata['unionid']??'';
        $token = 'wxxcx_'.Uuid::uuid1();

        $hasRegsiter = Db::table('kt_gptcms_xcx_user')->where(['wid'=>$wid,'openid'=>$openid])->find();
        if(!$hasRegsiter){
            $regtime = date("Y-m-d H:i:s",time());
            $common_id = Db::table('kt_gptcms_common_user')->insertGetId([
                'wid' => $wid,
                'type' => 'xcx',
                'parent' => $parent,
                'nickname' => $nickname,
                'headimgurl' => $headimgurl,
                'account' => 'xcx'.time().rand(1,300),
                'password' => ktEncrypt('123456'),
                'unionid' => $unionid,
                'xcx_token' => "$token",
                'c_time' => $regtime,
                'u_time' => $regtime
            ]);
            Db::table('kt_gptcms_xcx_user')->insert([
                'wid' => $wid,
                'common_id' => $common_id,
                'openid' => $openid,
                'nickname' => $nickname,
                'headimgurl' => $headimgurl,
                'sex' => $sex,
                'city' => $city,
                'province' => $province,
                'country' => $country,
                'unionid' => $unionid,
                'c_time' => $regtime,
                'u_time' => $regtime
            ]);
            $this->registerReward($common_id);
            if($parent){
                $this->inviteReward($parent);
            }
            return success('登录成功',['token'=>$token]);
        }

        $user = Db::table('kt_gptcms_common_user')->find($hasRegsiter['common_id']);
        if($user['status'] != 1 ) return error('账号因异常行为进入风控，请联系客服解除风控！error:006.2');
        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->update([
            'nickname' => $nickname,
            'headimgurl' => $headimgurl,
            'unionid' => $unionid
        ]);
        Db::table('kt_gptcms_xcx_user')->where(['wid'=>$wid,'openid'=>$openid])->update([
            'nickname' => $nickname,
            'headimgurl' => $headimgurl,
            'sex' => $sex,
            'city' => $city,
            'province' => $province,
            'country' => $country,
            'unionid' => $unionid,
            'u_time' => date("Y-m-d H:i:s",time())
        ]);
        return success('登录成功',['token'=>$user['xcx_token']]);
    }

    /**
     * 用户详情
     */
    public function getUserInfo()
    {
        $uid = Session::get('uid');
        $user = Db::table('kt_gptcms_common_user')->find($uid);
        $user['isvip'] = 0;
        if(strtotime($user['vip_expire']) > time()){
            $user['isvip'] = 1;
        }
        $this->loginReward($user['id']);
        return success('获取成功',$user);
    }
    /**
     * 用户编辑
     */
    public function userInfoEdit()
    {
        $wid = Session::get('wid');
        $uid = Session::get('uid');
        $user = Db::table('kt_gptcms_common_user')->find($uid);
        if(!$user) return error("用户不存在");
        $data = [];
        $data["nickname"] = $this->req->param("nickname");
        $data["headimgurl"] = $this->req->param("headimgurl");
        $data["mobile"] = $this->req->param("mobile");
        $code = $this->req->param("code");
        if($data['mobile']){
            $is = Db::table('kt_gptcms_common_user')->where('wid',$wid)->where("mobile",$data['mobile'])->where("id","<>",$uid)->find();
            if($is) return error('该手机号已被绑定，请更换其他手机号');
            if($data['mobile'] != $user["mobile"] && $user["mobile"] == $user["account"]){
                $data['account'] = $data["mobile"] ;
            }
        }
        $password = $this->req->param("password");
        
        if($password) $data['password'] = ktEncrypt($password);
        $data["u_time"] = date("Y-m-d H:i:s");
        Db::table('kt_gptcms_common_user')->where("id",$uid)->update($data);
        return success('操作成功');
    }
    /**
     * 获取二维码
     */
    public function getWebsitInfo()
    {
        $wid = Session::get('wid');
        $info = Db::table('kt_gptcms_websit')->where("wid",$wid)->find();
        return success('获取成功',$info);
    }

    /**
     * 绑定手机号
     */
    public function bindMobile()
    {
        $wid = Session::get('wid');
        $uid = Session::get('uid');
        if(!$uid) return error('账号不存在');
        $mobile = $this->req->param('mobile');
        if(!$mobile) return error('请填写手机号');
        $where = [
            ['mobile', '=', $mobile]
        ];
        $user = Db::table('kt_gptcms_common_user')->where('wid',$wid)->where($where)->find();
        if($user) return error('该手机号已被绑定，请更换其他手机号');
        Db::table('kt_gptcms_common_user')->where('id',$uid)->update(['mobile'=>$mobile,'u_time'=>date('Y-m-d H:i:s')]);
        return success('绑定成功');
    }

    /**
     * 修改密码
     */
    public function updatePwd()
    {
        $user = $this->user;
        if(!$user) return error('账号不存在');
        $password = $this->req->param('password');
        if($user['password'] != ktEncrypt($password)) return error('当前密码错误');
        $new_password = $this->req->param('new_password');
        $confirm_password = $this->req->param('confirm_password');
        if(!$new_password || !$confirm_password) return error('请输入新密码');
        if($new_password != $confirm_password) return error('两次输入的新密码不一致');
        if($user['password'] == ktEncrypt($new_password)) return error('新旧密码一致');
        $res =  Db::table('kt_gptcms_common_user')->where('id',$user['id'])->update([
            "password" => ktEncrypt($new_password),
        ]);
        if($res) return success('修改成功');
        return error('修改失败');
    }

    /**
     * @param $parent 邀请人id
     * 邀请奖励
     */
    public function inviteReward($parent)
    {
        $wid = Session::get('wid');
        $config = Db::table("kt_gptcms_invite_award")->field("status,number,up_limit")->where('wid',$wid)->find();
        if($config['status'] != 1) return '获取奖励失败，功能未开启';
        $res = Db::table('kt_gptcms_common_user')->where(['wid'=>$wid,'parent'=>$parent])->whereDay('c_time');
        $count = $res->count();
        if($count >= $config['up_limit']) return '今日领取奖励次数已达上限';
        Db::table('kt_gptcms_common_user')->where('id',$parent)->inc('residue_degree', $config['number'])->update();
        Db::table('kt_gptcms_reward_record')->insert([
            'wid' => $wid, 
            'common_id' => $parent,
            'num' => $config['number'],
            'type' => 3,
            'c_time' => date('Y-m-d H:i:s')
        ]);
        return "邀请成功奖励{$config['number']}条";
    }

    /**
     * @param $id 注册用户id
     * 注册奖励
     */
    public function registerReward($id)
    {
        $wid = Session::get('wid');
        $system = Db::table('kt_gptcms_system')->where('wid',$wid)->find();
        $rz_number = $system['rz_number']??0;
        if($rz_number){
            Db::table('kt_gptcms_common_user')->where('id',$id)->inc('residue_degree', $rz_number)->update();
            Db::table('kt_gptcms_reward_record')->insert([
                'wid' => $wid, 
                'common_id' => $id,
                'num' => $rz_number,
                'type' => 1,
                'c_time' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * @param $id 登录用户id
     * 登录奖励
     */
    public function loginReward($id)
    {
        $wid = Session::get('wid');
        $system = Db::table('kt_gptcms_system')->where('wid',$wid)->find();
        $dz_number = $system['dz_number']??0;
        $zdz_number = $system['zdz_number']??0;
        $res = Db::table('kt_gptcms_reward_record')->where(['wid'=>$wid,'common_id'=>$id,'type'=>2])->whereDay('c_time')->find();
        if($res) return '今日奖励已领取';
        $count = Db::table('kt_gptcms_reward_record')->where(['wid'=>$wid,'type'=>2])->whereDay('c_time')->count();
        if($count >= $zdz_number) return '总每日赠送次数已用完，不再赠送';
        if(!$dz_number) return '每日赠送次数为0';
        Db::table('kt_gptcms_common_user')->where('id',$id)->inc('residue_degree', $dz_number)->update();
        Db::table('kt_gptcms_reward_record')->insert([
            'wid' => $wid, 
            'common_id' => $id,
            'num' => $dz_number,
            'type' => 2,
            'c_time' => date('Y-m-d H:i:s')
        ]);
    }
    /**
     * 登录奖励
     */
    public function taskCenter()
    {
       $wid = Session::get('wid');
       $data = [];
       $data["residue_degree"] = $this->user["residue_degree"] ?: 0;
       $data["dz_number"] = Db::table('kt_gptcms_system')->where('wid',$wid)->value("dz_number");
       $invite = Db::table('kt_gptcms_invite_award')->where("wid",$wid)->find();
       $data["invite"] = [
         "status" => $invite["status"] ?? 0,
         "number" => $invite["number"] ?? 0,
         "up_limit" => $invite["up_limit"] ?? 0,
         "day_invite" => Db::table('kt_gptcms_common_user')->where("wid",$wid)->where("parent",$this->user["id"])->whereDay("c_time")->count(),
       ];
       $share = Db::table('kt_gptcms_share_award')->where("wid",$wid)->find();
       $data["share"] = [
            "status" => $share["status"] ?? 0,
            "number" => $share["number"] ?? 0,
            "up_limit" => $share["up_limit"] ?? 0,
            "day_share" => Db::table('kt_gptcms_share_rewards')->where("wid",$wid)->where("common_id",$this->user["id"])->whereDay("c_time")->count(),
       ];

       return success("任务中心",$data);
    }
}

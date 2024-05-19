<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;
use Ramsey\Uuid\Uuid;
use think\facade\Cache;
use app\gptcms\model\Wxopenapi;

class ActionEvent extends BaseApi
{
    public function getCode(){
        $wid = Session::get("wid");
        $authorizer_access_token = Wxopenapi::getToken($wid);
        if(is_array($authorizer_access_token))return error("token生成失败,状态码：".$authorizer_access_token["errcode"]);
        $random = $this->createNonceStr(8);
        $save["wid"] = $wid;
        $save["random"] = $random;
        $save["ctime"] = date("Y-m-d H:i:s");
        $id = Db::table("kt_gptcms_random")->insertGetId($save);
        $res = curlPost("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=" .$authorizer_access_token,
            json_encode([
                "action_name"     => "QR_LIMIT_STR_SCENE",
                "action_info"=>[
                    "scene"=>[
                        "scene_str"=>$random
                    ]
                ]
            ])
        );

        $res = json_decode($res,1);
        if(!isset($res["ticket"]) || !$res["ticket"]){
            Db::table("kt_gptcms_random")->delete($id);
            return error("获取失败，请检查配置");
        }
        $code = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($res["ticket"]);//ticket记得进行UrlEncode
        Db::table("kt_gptcms_random")->where(["id"=>$id])->update(["code"=>$code]);
        $data["code"] = $code;
        $data["random"] = $random;
        return success("获取成功",$data);
    }

    public function isLogin(){
        $wid = Session::get("wid");
        $random = $this->req->param("random");
        $parent = (int)$this->req->param("parent")?:0;
        $res = Db::table("kt_gptcms_random")->where(["random"=>$random,"wid"=>$wid])->whereTime("ctime",">=",date("Y-m-d"))->find();
        if(!$res["openid"])return error('暂未查询到用户');
        $token = Uuid::uuid1();
        $hasRegsiter = Db::table('kt_gptcms_wx_user')->where(['wid'=>$wid,'openid'=>$res['openid']])->find();
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
                'openid' => $res['openid'],
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
            Log::error('wxLogin1'.json_encode($res));
            return success('登录成功',['token'=>$token]);
        }

        $user = Db::table('kt_gptcms_common_user')->find($hasRegsiter['common_id']);
        if($user['status'] != 1 ) return error('账号因异常行为进入风控，请联系客服解除风控！error:007');
        $token = $user['token'] && $user['expire_time'] > time() ? $user['token'] : Uuid::uuid1();
        Db::table('kt_gptcms_common_user')->where('id',$user['id'])->update([
            'token'=>"{$token}",
            'expire_time'=> time() + (7*24*3600)
        ]);
        return success('登录成功',['token'=>$token]);
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


    public function createNonceStr($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
}
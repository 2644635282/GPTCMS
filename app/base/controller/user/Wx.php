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
use Ramsey\Uuid\Uuid;
use think\facade\Session;
use app\base\controller\BaseUser;
use app\base\model\BaseModel;
use app\base\model\user\Wxopenapi;

class Wx extends BaseUser
{

    public function getcode()
    {
        $agent = BaseModel::getLoginInfo($this->host);
        $uid = $agent['id'];
        $authorizer_access_token = Wxopenapi::getToken($uid);
        if(is_array($authorizer_access_token))return error("token生成失败,状态码：".$authorizer_access_token["errcode"]);
        $random = $this->getRandomStr();
        $save["uid"] = $uid;
        $save["type"] = 'login';
        $save["random"] = $random;
        $save["ctime"] = date("Y-m-d H:i:s");
        $id = Db::table("kt_base_wxgzh_random")->insertGetId($save);
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
            Db::table("kt_base_wxgzh_random")->delete($id);
            return error("获取失败，请检查配置");
        }
        $code = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($res["ticket"]);//ticket记得进行UrlEncode
        Db::table("kt_base_wxgzh_random")->where(["id"=>$id])->update(["code"=>$code]);
        $data["code"] = $code;
        $data["random"] = $random;
        return success("获取成功",$data);
        
    }

    public function login()
    {
        $agent = BaseModel::getLoginInfo($this->host);
        $uid = $agent['id'];
        $random = $this->req->param("random");
        $parent = (int)$this->req->param("parent")?:0;
        $res = Db::table("kt_base_wxgzh_random")->where(["random"=>$random,"uid"=>$uid])->whereTime("ctime",">=",date("Y-m-d"))->find();
        if(!$res["openid"])return error('暂未查询到用户');
        $user = Db::table('kt_base_user')->where(['agid'=>$uid,'wxopenid'=>$res['openid']])->find();
        if(!$user){
            $user_id = Db::table('kt_base_user')->insertGetId([
                'un' => $res['un'],
                'pwd' => $res['pwd'],
                'telephone' => '',
                'agid' => $uid,
                'mendtime' => date('Y-m-d', strtotime('-7 days')),
                'create_time' => date("Y-m-d H:i:s",time()),
                'wxopenid' => $res['openid']
            ]);
            BaseModel::openRegisterSetmeal($user_id);
            $user = Db::table('kt_base_user')->find($user_id);
        }
        if($user['isstop'] != 1 ) return error('账号审核中或已停用');
        $token = $user['token'] && $user['expire_time'] > time() ? $user['token'] : Uuid::uuid1();
        Db::table('kt_base_user')->where('id',$user['id'])->inc('logtimes')->update(['token'=>"{$token}",'expire_time'=> time() + (7*24*3600),'lasttime'=>date("Y-m-d H:i:s")]);
        Db::table('kt_base_loginlog')->insert([
            'admin' => 2 ,
            'wid' => $user['id'],
            'uip' => $this->req->ip(),
            'create_time' => date("Y-m-d H:i:s")
        ]);
        return success('登录成功',['token'=>$token]);
    }

    public function getbindcode()
    {
        $wid = Session::get('wid');
        $user = Db::table('kt_base_user')->find($wid);
        $uid = $user['agid'];
        $authorizer_access_token = Wxopenapi::getToken($uid);
        if(is_array($authorizer_access_token))return error("token生成失败,状态码：".$authorizer_access_token["errcode"]);
        $random = $this->getRandomStr();
        $save["uid"] = $uid;
        $save["type"] = 'bind';
        $save["random"] = $random;
        $save["ctime"] = date("Y-m-d H:i:s");
        $id = Db::table("kt_base_wxgzh_random")->insertGetId($save);
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
            Db::table("kt_base_wxgzh_random")->delete($id);
            return error("获取失败，请检查配置");
        }
        $code = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($res["ticket"]);//ticket记得进行UrlEncode
        Db::table("kt_base_wxgzh_random")->where(["id"=>$id])->update(["code"=>$code]);
        $data["code"] = $code;
        $data["random"] = $random;
        return success("获取成功",$data);
    }

    public function bind()
    {
        $wid = Session::get('wid');
        $user = Db::table('kt_base_user')->find($wid);
        if(!$user) return error('系统错误');
        $uid = $user['agid'];
        $random = $this->req->param("random");
        $res = Db::table("kt_base_wxgzh_random")->where(["random"=>$random,"uid"=>$uid])->whereTime("ctime",">=",date("Y-m-d"))->find();
        if(!$res["openid"])return error('暂未查询到用户');
        $hasuser = Db::table('kt_base_user')->where(['agid'=>$uid,'wxopenid'=>$res["openid"]])->find();
        if($hasuser)return error('绑定失败，该微信号已经绑定其他账号');
        Db::table('kt_base_user')->save([
            'id' => $user['id'],
            'wxopenid' => $res["openid"]
        ]);
        return success('绑定成功');
    }

    public function unBind()
    {
        $wid = Session::get('wid');
        $user = Db::table('kt_base_user')->find($wid);
        if(!$user) return error('用户不存在');
        //解绑微信号
        Db::table('kt_base_user')->save([
            'id' => $user['id'],
            'wxopenid' => ''
        ]);
        return success('解绑成功');
    }

    private function getRandomStr()
    {
        $str = getRandStr(16).time();
        $has = Db::table('kt_base_wxgzh_random')->where('random',$str)->find();
        if($has) $str = $this->getRandomStr();
        return $str;
    }

    public function index()
    {
        $username = $this->req->param("username");
        $password = $this->req->param("password");
        $phone = $this->req->param("phone");
        if(!$username) return error('缺少参数username');
        if(!$password) return error('缺少参数password');
        if(!$phone) return error('缺少参数phone');
        if(!preg_match("/^1[23456789]\d{9}$/", $phone)) return error("手机号格式错误，请重新输入");
        $where = [
            ['un', '=', $username],
            ['telephone', '=', $phone]
        ];
        $user = Db::table('kt_base_user')->whereOr($where)->find();
        if($user) return error("账户已存在，请重新注册");
        $res = BaseModel::getLoginInfo($this->host);
        Db::table('kt_base_user')->insert([
            'un' => $username ,
            'pwd' => ktEncrypt($password),
            'telephone' => $phone,
            'agid' => $res["id"],
            'mendtime' => date('Y-m-d', strtotime('-7 days')),
            'create_time' => date("Y-m-d H:i:s",time())
        ]);
        return success("注册成功");
    }  
}
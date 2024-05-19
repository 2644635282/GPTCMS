<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Session;

class Card extends BaseApi
{
	public function conversion(){
                $wid = Session::get('wid');
                $user = $this->user;
                $code = $this->req->param("code"); 
                $info = Db::table("kt_gptcms_card_detail")->where(["code"=>$code])->find();
                if(!$info)return error("操作失败，卡密错误或不存在");
                if($info["status"] == 1)return error("操作失败，卡密已被兑换");
                $detail["time"] = date("Y-m-d H:i:s");
                $detail["user"] = $user["id"];
                $detail["status"] = 1;
                $res = Db::table("kt_gptcms_card_detail")->where(["id"=>$info["id"]])->update($detail);
                if($res){
                        $card = Db::table("kt_gptcms_card")->where('wid',$wid)->find($info['pid']);
                        switch ($card['type']) {
                                case 1:
                                        Db::table("kt_gptcms_common_user")->where('id',$this->user['id'])->update([
                                                "residue_degree" => $this->user['residue_degree'] + $card['size_num'],
                                                "u_time" => date("Y-m-d H:i:s")
                                        ]);
                                        break;
                                case 3:
                                        $data = ["u_time" => date("Y-m-d H:i:s")];
                                        $vip_expire = $this->user['vip_expire'] ? strtotime($this->user['vip_expire']) : 0;
                                        if($vip_expire < time()) $vip_expire = time(); 
                                        if($card['size'] == 1){
                                                $vip_expire = $vip_expire + ($card['size_num'] *86400);
                                        }else if($card['size'] == 3){
                                                $vip_expire = $vip_expire + ($card['size_num'] *30*86400);
                                        }else if($card['size'] == 5){
                                                $vip_expire = $vip_expire + ($card['size_num'] *365*86400);
                                        }
                                        $data['vip_expire'] = date("Y-m-d H:i:s",$vip_expire);
                                        if(!$this->user['vip_open']) $data['vip_open'] = date("Y-m-d H:i:s");
                                        Db::table("kt_gptcms_common_user")->where('id',$this->user['id'])->update($data);
                                        break;

                        }
                        return success("操作成功");
                };
                return error("操作失败");
	}
}
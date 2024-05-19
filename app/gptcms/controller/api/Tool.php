<?php

namespace app\gptcms\controller\api;
use app\gptcms\controller\BaseApi;
use think\facade\Db;
use think\facade\Session;
use Ktadmin\Tencent\Aichest;
use Ktadmin\Ali\Ai as AliAi;
use app\gptcms\model\SecurityModel;
use app\gptcms\model\CommonModel;
use app\gptcms\model\TtsModel;
use think\facade\Filesystem;

class Tool extends BaseApi
{
	//语音转文字
	public function videoTtext()
	{
		try {
    		$wid = Session::get('wid');
			$file = $this->req->file('file');
			$urlpath = root_path()."public/storage/".Filesystem::disk('public')->putFile('upload/gptcms/tem', $file, 'md5');
			if(!file_exists($urlpath)) return error("语音转化失败");
			$config = $this->getTencentaiConfig($wid);
			// var_dump($config);die;
			if(!$config || !$config['secret_id'] || !$config['secret_key']) return error("转换失败, 请联系管理员");
			$data = base64_encode(file_get_contents($urlpath));
			@unlink($urlpath);
			$ai = new Aichest($config['secret_id'],$config['secret_key']);
			$ai->SourceType = 1;
			// $ai->VoiceFormat = "mp3";
			$task =  $ai->SentenceRecognition($data);
			if(isset($task->Error)) return error("转换失败, 请联系管理员");
			return success("转换成功",$task->Result);
		} catch (ValidateException $e) {
			return error("转换失败");
		    // 这是进行验证异常捕获
		    // return json($e->getError());
		} catch (\Exception $e) {
			
			return error("未识别到语音");
		    // 这是进行异常捕获
		    // return json($e->getMessage());
		}
		
		
	}

	private function getTencentaiConfig($wid)
	{
		$config = Db::table('kt_gptcms_tencentai_config')->field('secret_id,secret_key')->where('wid',$wid)->find();
		if(!$config || !$config['secret_id'] || !$config['secret_key']){
			$user = Db::table("kt_base_user")->find($wid);
			$config = Db::table('kt_base_tencentai_config')->field('secret_id,secret_key')->where('uid',$user['agid'])->find();
		}
		return $config;
	}
	//文字转语音 阿里云
	public function textTvideo()
	{
		$wid = Session::get('wid');
		$config = $this->getAliaiConfig($wid);
		if(!$config)return error("生成失败，请联系管理员");
		$content = $this->req->param("content");
		$content = str_replace(PHP_EOL,'',$content);
		if(!$content) return error("文本不可为空");
		$content = str_replace('<br/>', '', $content);
		$content = str_replace('，', '', $content);
		$content = str_replace('。', '', $content);
		if($config["type"] == 1){
			if(empty($config['accesskey_id']) || empty($config['accesskey_secret']) || empty($config['appkey'])) return error("生成失败, 请联系管理员");
			$path = root_path().'public/storage/aliai';
			if(!is_dir($path)) mkdir($path,0777,true);
			$filename = time().'_'.rand(1,99).'.mp3';
			$ai = new AliAi($config['accesskey_id'],$config['accesskey_secret'],$config['appkey']);
			$res = $ai->processGETRequest($content,$path."/".$filename,"mp3");
			if(!$res) return error("转换失败, 请联系管理员");
			$filename = basename($res);
			$file = $this->req->domain().'/storage/aliai/'.$filename;
		}else if($config["type"] == 2){
			$config = $this->getTtsConfing($wid);
			if(!$config || empty($config['appid']) || empty($config['secret']) || empty($config['key'])) return error("生成失败, 请联系管理员");
			if(strlen($content) > 8000) $content = substr($content, 0, 8000);
			$file = TtsModel::authentication($config['appid'],$config['key'],$config['secret'],$content);
			$file = $this->req->domain().ltrim($file,'.');
		}

		return success("转换成功",$file);
	}
	//文字转语音 讯飞
	public function textTvideoXf()
	{
		$url = $this->req->domain();
		$wid = Session::get('wid');
		$config = $this->getTtsConfing();
		if(!$config || !$config['appid'] || !$config['secret'] || !$config['key']) return error("生成失败, 请联系管理员");
		$content = $this->req->param("content");
		$content = str_replace(PHP_EOL,'',$content);
		if(!$content) return error("文本不可为空");
		$content = str_replace('<br/>', '', $content);
		$content = str_replace('，', '', $content);
		$content = str_replace('。', '', $content);
		if(strlen($content) > 8000) $content = substr($content, 0, 8000);
		$file = TtsModel::authentication($config['appid'],$config['key'],$config['secret'],$content);
		$file = ltrim($file,'.');

		return success("转换成功",$url.$file);
	}

	public function getTtsConfing($wid){
		$config = Db::table('kt_gptcms_tts_config')->where('wid',$wid)->find();

		return $config;
	}

	private function getAliaiConfig($wid)
	{
		$config = Db::table('kt_gptcms_aliai_config')->where('wid',$wid)->find();
		// if(!$config || !$config['accesskey_id'] || !$config['accesskey_secret']){
		// 	$user = Db::table("kt_base_user")->find($wid);
		// 	$config = Db::table('kt_base_aliai_config')->where('uid',$user['agid'])->find();
		// }
		return $config;
	}
	//绘画文字审核
	public function paintTextCheck()
	{
		$wid = Session::get('wid');
		$text = $this->req->param('text');
		$chatmodel = $this->req->param('chatmodel');
		$user = $this->user;
		if($user['status'] != 1){
            return error("账号因异常行为进入风控，请联系客服解除风控！error:003");
        }
        $vip = 0;
        if(strtotime($user['vip_expire']) > time()){ //会员未到期
            $vip = 1;
        }else{ //会员到期
        	if(!$chatmodel){
        		$config['channel'] = Db::table('kt_gptcms_gptpaint_config')->where('wid',$wid)->value('channel');
	            switch ($config['channel']) {
	                case 1:
	                    $chatmodel = 'yjai';
	                    break;

	                case 2:
	                    $chatmodel = 'replicate';
	                    break;

	                case 3:
	                    $chatmodel = 'gpt35';
	                    break;
	                case 4:
	                    $chatmodel = 'api2d35';
	                    break;
	                case 5:
	                    $chatmodel = 'sd';
	                    break;
	                default:
	                    $chatmodel = 'sd';
	                    break;
	            }
        	}
        	$expend = CommonModel::getExpend('paint',$chatmodel);//获取消耗条数
            if($user['residue_degree'] < $expend){ //余数不足
                $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
                return error($zdz_remind ?: "剩余条数不足");
            }
        }
		$cs = SecurityModel::check($text,1);
		return success("检查结果",$cs);
	}

	//对话文字审核
	public function textCheck()
	{
		$wid = Session::get('wid');
		$text = $this->req->param('text');
		$user = $this->user;
		if($user['status'] != 1){
            return error("账号因异常行为进入风控，请联系客服解除风控！error:003.1");
        }
        // $vip = 0;
        // if(strtotime($user['vip_expire']) > time()){ //会员未到期
        //     $vip = 1;
        // }else{ //会员到期
        // 	$chatmodel = $this->req->param('chatmodel');
        // 	if(!$chatmodel){
        // 		$config['channel'] = Db::table('kt_gptcms_gpt_config')->where('wid',$wid)->value('channel');
	       //      switch ($config['channel']) {
	       //          case 1:
	       //              $chatmodel = 'gpt35';
	       //              break;

	       //          case 2:
	       //              $chatmodel = 'api2d35';
	       //              break;

	       //          case 7:
	       //              $chatmodel = 'linkerai';
	       //              break;

	       //          case 8:
	       //              $chatmodel = 'gpt4';
	       //              break;

	       //          case 9:
	       //              $chatmodel = 'api2d4';
	       //              break;
	                
	       //          default:
	       //              $chatmodel = 'gpt35';
	       //              break;
	       //      }
        // 	}
        // 	$expend = CommonModel::getExpend('chat',$chatmodel);//获取消耗条数
        //     if($user['residue_degree'] < $expend){ //余数不足
        //         $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
        //         return error($zdz_remind ?: "剩余条数不足");
        //     }
        // }
        //------2023/6/13-------
        $chatmodel = $this->req->param('chatmodel');
        if(!$chatmodel){
        	$config['channel'] = Db::table('kt_gptcms_gpt_config')->where('wid',$wid)->value('channel');
	        switch ($config['channel']) {
	            case 1:
	                $chatmodel = 'gpt35';
	                break;

	            case 2:
	                $chatmodel = 'api2d35';
	                break;

	            case 7:
	                $chatmodel = 'linkerai';
	                break;

	            case 8:
	                $chatmodel = 'gpt4';
	               break;

	            case 9:
	                $chatmodel = 'api2d4';
	                break;
	                
	            default:
	                $chatmodel = 'gpt35';
	                break;
	        }
        }
        $expend = CommonModel::getExpend('chat',$chatmodel);//获取消耗条数
        $vip = 0;
        if(strtotime($user['vip_expire']) > time()){ //会员未到期
            $vip = 1;
        }
        $gpt4_charging = 0;
        if($chatmodel == 'gpt4'){
            $gpt4_charging = Db::table('kt_gptcms_system')->where(['wid'=>$wid])->value('gpt4_charging')??0;
            if($gpt4_charging){ //如果开启GPT4单独计费,不能使用vip
                // $vip = 0;
            }
        }
        if(!$vip || $gpt4_charging){
            if($user['residue_degree'] < $expend){ //余数不足
                $zdz_remind = Db::table('kt_gptcms_system')->where('wid',$wid)->value('zdz_remind');
                return error($zdz_remind?:'剩余条数不足');
            }
        }
        //------2023/6/13-------
        $model_id = $this->req->param('model_id');
        $type = $this->req->param('type',"chat");
        switch ($type) {
        	case 'chat':

        		break;
        	case 'createchat':
        		$cmodel = Db::table('kt_gptcms_cmodel')->find($model_id);
        		if(!$cmodel) return error('模型不存在');
        		if($cmodel['status'] != 1) return error('模型不可用');
        		if($cmodel['vip_status'] == 1 && $vip == 0) return error("当前模型仅VIP可用");
        		break;
        	case 'rolechat':
        		$jmodel = Db::table('kt_gptcms_jmodel')->find($model_id);
        		if(!$jmodel) return error('模型不存在');
        		if($jmodel['status'] != 1) return error('模型不可用');
        		if($jmodel['vip_status'] == 1 && $vip == 0) return error("当前模型仅VIP可用");
        		break;
        	
        }
        
		$cs = SecurityModel::check($text,1);
		return success("检查结果",$cs);
	}
	private function getBaiduaiConfig($wid)
	{
		$config = Db::table('kt_gptcms_baiduai_config')->field('apikey,secretkey,appid')->where('wid',$wid)->find();
		if(!$config || !$config['apikey'] || !$config['secretkey']){
			$user = Db::table("kt_base_user")->find($wid);
			$config = Db::table('kt_base_baiduai_config')->field('apikey,secretkey,appid')->where('uid',$user['agid'])->find();
		}
		return $config;
	}
}
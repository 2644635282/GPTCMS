<?php
declare (strict_types = 1);

namespace app\gptcms\controller\user;
use app\gptcms\controller\BaseUser;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;
use think\facade\Filesystem;

class LinkerTraining extends BaseUser
{
	public function list()
	{
		$key = $this->getTrainingKey();
		if(!$key) return error("请先配置灵犀星火专用训练Key");
		$params = [
			 "train_key"=>$key
		];
		$res = $this->curlRequest("https://train.80w.top/api/login","POST",json_encode($params));
		if(!$res || !isset($res['status']) || $res['status'] != "success") return error("配置错误");
		$data = [];
		$data['describe'] = $res["describe"];
		$data['key_info'] = $res["key_info"];
		$data['describe'] = $res["describe"];
		return success("模型列表",$data);
	}
	public function create()
	{
		$key = $this->getTrainingKey();
		if(!$key) return error("请先配置灵犀星火专用训练Key");
		$params = [
			 "train_key"=>$key
		];
		$desc = $this->req->param("desc") ?: '';
		$params["description"] = $desc;
		$res = $this->curlRequest("https://train.80w.top/api/create_model","POST",json_encode($params));
		if(!$res || !isset($res['status'])) return error("创建失败");
		if($res['status'] != "success") return error($res["describe"]);
		return success("创建成功",$res);
	}
	public function upload()
	{
		// var_dump($_FILES);die;
		$key = $this->getTrainingKey();
		if(!$key) return error("请先配置灵犀星火专用训练Key");
		$params = [
			 "train_key"=>$key
		];
		$model_id = $this->req->param("model_id");
		if(!$model_id) return error("请选择模型");
		$params["model_id"] = $model_id;
		$file = $this->req->file('file');
		$ext = $file->extension();
		if(!in_array($ext,["doc","docx","pdf","txt"])) return error("目前仅支持 doc, docx, pdf, txt, 均为文字版，扫描版无法识别");
		// $file_list = $this->req->param("file_list");
		// if(!$file_list) return errro("请上传地址");
		// $PSize = filesize($file);
		// $picturedata = fread(fopen($file, "r"), $PSize);
		// $filedata = [];
		// foreach ($file_list as $path) {
			// $filedata[] = curl_file_create(root_path()."public/storage/upload/gptcms/tem/".$path);
			// $params["file_list"] = new \CURLFILE(root_path()."public/storage/upload/gptcms/tem/".$file_list,"text/plain",$file_list);
			$params["file_list"] = new \CURLFILE($_FILES['file']['tmp_name'],$_FILES['file']['type'],$_FILES['file']['name']);
			// $filedata[] = "@".root_path()."public/storage/upload/gptcms/tem/".$path;
			// $params["file_list"] = "@C:\Users\peak\Desktop\kt_1.txt";
			// $params["file_list"] = "@".root_path()."public/storage/upload/gptcms/tem/".$file_list;
			// echo $params["file_list"];die;
		// }

		// $params["file_list"] = $filedata;
		$res = $this->curlRequestUpload("https://train.80w.top/api/upload",$params);
		// var_dump($res);die;	
		if(!$res || !isset($res['status']) || $res['status'] != "success") return error("配置错误");
		return success("上传成功");
	}
	public function uploadFile()
	{
		$file = $this->req->file('file');
		$ext = $file->extension();
		if(!in_array($ext,["doc","docx","pdf","txt"])) return error("目前仅支持 doc, docx, pdf, txt, 均为文字版，扫描版无法识别");
		// echo $file->getMime();die;
		// echo $file->getOriginalName();
		// echo $file->extension();
		// echo root_path()."public/storage/upload/gptcms/tem";die;

		$filename = $file->getOriginalName();
		$res = $file->move(root_path()."public/storage/upload/gptcms/tem",$filename);

		// $urlpath = root_path()."public/storage/".Filesystem::disk('public')->putFile('upload/gptcms/tem', $file);

		return success("文件地址",$filename);
	}

	public function fileList()
	{
		$key = $this->getTrainingKey();
		if(!$key) return error("请先配置灵犀星火专用训练Key");
		$params = [
			 "train_key"=>$key
		];
		$model_id = $this->req->param("model_id");
		if(!$model_id) return error("请选择模型");
		$params["model_id"] = $model_id;
		$res = $this->curlRequest("https://train.80w.top/api/show_upload_files","POST",json_encode($params));
		if(!$res || !isset($res['status']) || $res['status'] != "success") return error("配置错误");
		
		return success("当前模型上传的文件列表",$res);
	}
	public function stratTrain()
	{
		$key = $this->getTrainingKey();
		if(!$key) return error("请先配置灵犀星火专用训练Key");
		$params = [
			 "train_key"=>$key
		];
		$model_id = $this->req->param("model_id");
		if(!$model_id) return error("请选择模型");
		$params["model_id"] = $model_id;

		$res = $this->curlRequest("https://train.80w.top/api/start_train","POST",json_encode($params));
		if(!$res || !isset($res['status']) || $res['status'] != "success") return error("训练失败");
		
		return success("开始训练",$res);
	}
	public function getTrainStatus()
	{
		$key = $this->getTrainingKey();
		if(!$key) return error("请先配置灵犀星火专用训练Key");
		$params = [
			 "train_key"=>$key
		];
		$model_id = $this->req->param("model_id");
		if(!$model_id) return error("请选择模型");
		$params["model_id"] = $model_id;

		$res = $this->curlRequest("https://train.80w.top/api/get_train_status","POST",json_encode($params));
		if(!$res || !isset($res['status']) || $res['status'] != "success") return error("配置错误");
		
		return success("获取训练状态",$res);
	}
	public function clearFiles()
	{
		$key = $this->getTrainingKey();
		if(!$key) return error("请先配置灵犀星火专用训练Key");
		$params = [
			 "train_key"=>$key
		];
		$model_id = $this->req->param("model_id");
		if(!$model_id) return error("请选择模型");
		$params["model_id"] = $model_id;

		$res = $this->curlRequest("https://train.80w.top/api/clear_files","POST",json_encode($params));
		if(!$res || !isset($res['status']) || $res['status'] != "success") return error("配置错误");
		
		return success("清空已上传文件");
	}

	private function getTrainingKey()
	{
		$wid = Session::get("wid");
		$config = Db::table("kt_gptcms_gpt_config")->json(["linkerai"])->where("wid",$wid)->find();
		if(!$config || !isset($config['linkerai']['training_key']) || !$config['linkerai']['training_key']) return '';
		return $config['linkerai']['training_key'];
	}

    private function curlRequest($url, $method = 'GET', $data=null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        if($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            
        }
        curl_close($ch);
        return json_decode($result, true);
    }

    private function curlRequestUpload($url, $data)
    {
    	// var_dump($data);die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: multipart/form-data",
            "accept: application/json"
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            
        }
        curl_close($ch);
        // echo $result;die;
        // var_dump($result);die;
        return json_decode($result, true);
    }

}
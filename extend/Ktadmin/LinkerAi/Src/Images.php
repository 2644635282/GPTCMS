<?php
namespace Ktadmin\LinkerAi\Src;

use Ktadmin\LinkerAi\Ktadmin;

/**
 * MJ
 */
class Images
{
	private $ktadmin;

	public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

    /**
     * 发送绘画要求 
     * @param string 动作: 必传，IMAGINE（绘图）、UPSCALE（选中放大）、VARIATION（选中变换）
     * @param string $action 动作: 必传，IMAGINE（绘图）、UPSCALE（选中放大）、VARIATION（选中变换）
     */
    public function send($prompt,$notifyHook='',$imageurl="")
    {
        $postData = [
            'prompt' => $prompt,
            'imageurl' => $imageurl,
            'notifyHook' => $notifyHook,
            // 'taskId' => $taskId,
            // 'index' => $index,
            // 'state' => $state,
        ];
        $url = "http://mj.80w.top:8606/mj/trigger/submit";
        return $this->ktadmin->curlRequestPaint($url,"POST",json_encode($postData));
    }
    /**
     * 查询结果或者进度
     * @param string $taskId 任务ID
     */
    public function fetch($taskId)
    {
        $url = "http://mj.80w.top:8606/mj/task/{$taskId}/fetch";
        return $this->ktadmin->curlRequestPaint($url,"GET");
    }
   	/**
     * 单张图放大,转换
     * @param string $id     任务id
     * @param string $notifyHook    回调地址
     * @param string $type   upscale(放大) or variation(变换)
     * @param string $index  图片索引(取值：1-4) ，标识放大不同的图片或者变换不同的图片
     */
    public function uv($id,$type,$index,$notifyHook='')
    {
        $url = "http://mj.80w.top:8606/mj/trigger/submit-uv";
        $postData = [
            'id' => $id,
            'notifyHook' => $notifyHook,
            'type' => $type,
            'index' => $index,
        ];
        return $this->ktadmin->curlRequestPaint($url,"POST",json_encode($postData));
    }
    /**
     * 以图生图
     * @param string $prompt 任务描述: 如：选中ID为1320098173412546的第2张图片放大( 放大 U1～U4 ，变换 V1～V4)
     * @param string $base64str 图片的base64字符串
     */
    public function img2img($prompt,$base64str,$notifyHook='',$state='')
    {
        $url = "http://mj.80w.top:8606/mj/trigger/img2img";
        $postData = [
            'prompt' => $prompt,
            'base64' => "data:image/png;base64,".$base64str,
            'notifyHook' => $notifyHook,
            'state' => $state,
        ];
        return $this->ktadmin->curlRequestPaint($url,"POST",json_encode($postData));
    }

}
	
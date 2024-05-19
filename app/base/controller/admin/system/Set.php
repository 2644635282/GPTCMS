<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin\system;

use think\facade\Db;
use think\facade\Session;
use app\base\controller\BaseAdmin;
use app\base\model\admin\system\SetModel;

/**
 * 接口配置
 */
class Set extends BaseAdmin
{
	
	/**
    * Baidu AI配置  获取
    */
    public function BaiduAi()
    {
        return SetModel::BaiduAi();
    }

    /**
    * Baidu AI配置  保存
    */
    public function BaiduAiSet()
    {
        return SetModel::BaiduAiSet($this->req);
    }
    /**
    * Aliyun 语音合成配置  获取
    */
    public function Aliyun()
    {
        return SetModel::Aliyun();
    }

    /**
    * Aliyun 语音合成配置  保存
    */
    public function AliyunSet()
    {
        return SetModel::AliyunSet($this->req);
    }

	/**
    * 腾讯云 语音转合字幕配置  获取
    */
    public function Tencent()
    {
        return SetModel::Tencent();
    }

    /**
    * 腾讯云 语音转合字幕配置  保存
    */
    public function TencentSet()
    {
        return SetModel::TencentSet($this->req);
    }

    /**
    * GPT配置  获取
    */
    public function Gpt()
    {
        return SetModel::Gpt();
    }

    /**
    * GPT配置  保存
    */
    public function GptSet()
    {
        return SetModel::GptSet($this->req);
    }
    /**
    * 内容审核 获取
    */
    public function review()
    {
        return SetModel::review();
    }

    /**
    * 提问审核 设置
    */
    public function questionSet()
    {
        return SetModel::questionSet($this->req);
    }
    /**
    * 回复审核 设置
    */
    public function replySet()
    {
        return SetModel::replySet($this->req);
    }
    /**
    * 敏感词库获取
    */
    public function sensitiveLexicon()
    {
        return SetModel::sensitiveLexicon();
    }
    /**
    * 敏感词库保存
    */
    public function sensitiveLexiconSave()
    {
        return SetModel::sensitiveLexiconSave($this->req);
    }
}
<?php 
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\base\controller\admin\user;
use think\facade\Db;
use app\base\controller\BaseAdmin;
use app\base\model\admin\user\CardModel;
use think\facade\Session;

class Card extends BaseAdmin
{
    public function userecord()
    {
        return CardModel::userecord($this->req);
    }
    public function list()
    {
        return CardModel::list($this->req);
    }
    public function del()
    {
        return CardModel::del($this->req);
    }
    public function detail()
    {
        return CardModel::detail($this->req);
    }
    public function add()
    {
        return CardModel::add($this->req);
    }
    public function downexcel()
    {
        return CardModel::downexcel($this->req);
    }

}
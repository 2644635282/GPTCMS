<?php
namespace Ktadmin\Chatgpt\Src;

use Ktadmin\Chatgpt\Ktadmin;

/**
 * 审查
 */
class Moderations
{
	private $ktadmin;

    public function __construct(Ktadmin $ktadmin = null)
    {
        $this->ktadmin = $ktadmin;
    }

    /**
	 * 创建内容审核
	 * @param text strin 审核内容
	 * @return  JSON
	 */
	public function create($text='')
	{
		$url = "/v1/moderations";
		$param = array('input'=>$text);
    	return $this->ktadmin->curlRequest($url,json_encode($param,320));
	}

}
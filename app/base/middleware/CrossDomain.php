<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

declare (strict_types = 1);

namespace app\base\middleware;
use think\Response;

/**
 * 全局跨域请求处理
 * Class CrossDomain
 * @package app\middleware
 */
class CrossDomain
{
	public function handle($request, \Closure $next)
	{
		// header('Access-Control-Allow-Origin: *');
		header('Access-Control-Max-Age: 1800');
		header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE');
		header('Access-Control-Allow-Headers: usertoken, admintoken, Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-CSRF-TOKEN, X-Requested-With, Token, token, wid');
		if (strtoupper($request->method()) == "OPTIONS") {
			return Response::create()->send();
		}

		
		return $next($request);
	}
}
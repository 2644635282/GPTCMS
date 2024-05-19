<?php
// +----------------------------------------------------------------------
// | 狂团[kt8.cn]旗下KtAdmin是为独立版SAAS系统而生的快速开发框架.
// +----------------------------------------------------------------------
// | [KtAdmin] Copyright (c) 2022 http://ktadmin.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

/**
 * 这是管理后台的菜单文件
 */
return [
	//系统
	[
		"path" => "/system/site",
		"redirect" => "/system/site",
		"meta" => ['title'=>'系统设置', 'icon'=>'Setting'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/system/site",
				"name" => "system",
				"meta" => ['title'=>'系统设置', 'icon'=>'Setting', 'alwaysShow'=>true],
				"redirect" => "/system/site",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/system/site",
						"name" => "systemsite",
						"meta" => ['title'=>'站点设置'],
						"component" => "() => import('@/pages/admin/system/site.vue')",
					],
					[
						"path" => "/system/loginsetup",
						"name" => "systemloginsetup",
						"meta" => ['title'=>'登录设置'],
						"component" => "() => import('@/pages/admin/system/login.vue')",
					],
					[
						"path" => "/system/oss",
						"name" => "systemoss",
						"meta" => ['title'=>'存储设置'],
						"component" => "() => import('@/pages/admin/system/oss.vue')",
					],
					[
						"path" => "/system/sms",
						"name" => "systemsms",
						"meta" => ['title'=>'短信设置'],
						"component" => "() => import('@/pages/admin/system/sms.vue')",
					],
					[
						"path" => "/wxpay/config",
						"name" => "wxpayconfig",
						"meta" => ['title'=>'支付配置'],
						"component" => "() => import('@/pages/admin/wxpay/wxpayconfig.vue')",
					],
					[
						"path" => "/system/aiconfig",
						"name" => "systemaiconfig",
						"meta" => ['title'=>'AI接口设置'],
						"component" => "() => import('@/pages/admin/system/aiconfig.vue')",
					],
					[
						"path" => "/system/gptconfig",
						"name" => "systemgptconfig",
						"meta" => ['title'=>'GPT接口设置'],
						"component" => "() => import('@/pages/admin/system/gptconfig.vue')",
					],
					[
						"path" => "/system/cs",
						"name" => "systemcs",
						"meta" => ['title'=>'内容安全'],
						"component" => "() => import('@/pages/admin/system/contentsecurity.vue')",
					],
				]
			],
			[
				"path" => "/kt/config",
				"name" => "kt",
				"meta" => ['title'=>'狂团对接', 'icon'=>'HelpFilled', 'alwaysShow'=>true],
				"redirect" => "/kt/config",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/kt/config",
						"name" => "ktconfig",
						"meta" => ['title'=>'在线更新'],
						"component" => "() => import('@/pages/admin/kt/ktconfig.vue')",
					],
					[
						"path" => "/kt/app",
						"name" => "ktapp",
						"meta" => ['title'=>'应用列表'],
						"component" => "() => import('@/pages/admin/kt/ktapp.vue')",
					],
					[
						"path" => "/kt/appstore",
						"name" => "appstore",
						"meta" => ['title'=>'应用商店'],
						"component" => "() => import('@/pages/admin/kt/appstore.vue')",
					],
					[
						"path" => "/kt/dockset",
						"name" => "ktdockset",
						"meta" => ['title'=>'对接设置'],
						"component" => "() => import('@/pages/admin/kt/dockset.vue')",
					],
				]
			],
		]
	],
	//用户
	[
		"path" => "/user/list",
		"redirect" => "/user/list",
		"meta" => ['title'=>'用户管理', 'icon'=>'User'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/user/list",
				"name" => "user",
				"meta" => ['title'=>'用户管理', 'icon'=>'User', 'alwaysShow'=>true],
				"redirect" => "/user/list",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/user/list",
						"name" => "userlist",
						"meta" => ['title'=>'用户列表'],
						"component" => "() => import('@/pages/admin/user/list.vue')",
					],
					[
						"path" => "/user/add",
						"name" => "useradd",
						"meta" => ['title'=>'新增用户'],
						"component" => "() => import('@/pages/admin/user/add.vue')",
					],
					[
						"path" => "/user/edit",
						"name" => "useredit",
						"meta" => ['title'=>'编辑用户', 'hiddenInSideMenu'=>true],
						"component" => "() => import('@/pages/admin/user/edit.vue')",
					],
				]
			]
		]
	],
	//应用套餐
	[
		"path" => "/package/list",
		"redirect" => "/package/list",
		"meta" => ['title'=>'套餐管理', 'icon'=>'Coin'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/package/list",
				"name" => "package",
				"meta" => ['title'=>'应用套餐', 'icon'=>'Coin', 'alwaysShow'=>true],
				"redirect" => "/package/list",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/package/list",
						"name" => "packagelist",
						"meta" => ['title'=>'套餐列表'],
						"component" => "() => import('@/pages/admin/package/list.vue')",
					],
					[
						"path" => "/package/add",
						"name" => "packageadd",
						"meta" => ['title'=>'新增套餐'],
						"component" => "() => import('@/pages/admin/package/add.vue')",
					]
				]
			],
			[
				"path" => "/card/index",
				"name" => "card",
				"meta" => ['title'=>'卡密管理', 'icon'=>'Coin', 'alwaysShow'=>true],
				"redirect" => "/card/index",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/card/index",
						"name" => "cardindex",
						"meta" => ['title'=>'卡密列表'],
						"component" => "() => import('@/pages/admin/card/index.vue')",
					],
				]
			],
		]
	],
	//应用
	[
		"path" => "/app/mainapp",
		"redirect" => "/app/mainapp",
		"meta" => ['title'=>'应用设置', 'icon'=>'Menu'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/app/mainapp",
				"name" => "apps",
				"meta" => ['title'=>'应用管理', 'icon'=>'Menu', 'alwaysShow'=>true],
				"redirect" => "/app/mainapp",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/app/all",
						"name" => "appall",
						"meta" => ['title'=>'全部应用'],
						"component" => "() => import('@/pages/admin/app/all.vue')"
					],
					[
						"path" => "/app/mainapp",
						"name" => "appmainapp",
						"meta" => ['title'=>'应用设置'],
						"component" => "() => import('@/pages/admin/app/mainapp.vue')"
					],
					[
						"path" => "/app/plugin",
						"name" => "appplugin",
						"meta" => ['title'=>'插件设置'],
						"component" => "() => import('@/pages/admin/app/plugin.vue')"
					],
					[
						"path" => "/app/tools",
						"name" => "apptools",
						"meta" => ['title'=>'工具应用'],
						"component" => "() => import('@/pages/admin/app/tools.vue')"
					],
					[
						"path" => "/app/template",
						"name" => "apptemplate",
						"meta" => ['title'=>'模板应用'],
						"component" => "() => import('@/pages/admin/app/template.vue')"
					],
				]
			],
		]
	],
	//市场
	[
		"path" => "/market/index",
		"redirect" => "/market/index",
		"meta" => ['title'=>'自营市场', 'icon'=>'Goods'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/market/index",
				"name" => "market",
				"meta" => ['title'=>'应用市场', 'icon'=>'Goods', 'alwaysShow'=>true],
				"redirect" => "/market/index",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/market/index",
						"name" => "marketindex",
						"meta" => ['title'=>'应用管理'],
						"component" => "() => import('@/pages/admin/market/index.vue')"
					],
					[
						"path" => "/market/edit",
						"name" => "marketedit",
						"meta" => ['title'=>'市场编辑', 'hiddenInSideMenu'=>true],
						"component" => "() => import('@/pages/admin/market/edit.vue')"
					],
					[
						"path" => "/market/classification",
						"name" => "marketclassification",
						"meta" => ['title'=>'分类管理'],
						"component" => "() => import('@/pages/admin/market/classification.vue')"
					],
					[
						"path" => "/market/order",
						"name" => "marketorder",
						"meta" => ['title'=>'订单管理'],
						"component" => "() => import('@/pages/admin/market/order.vue')"
					],
				]
			],
			[
				"path" => "/market/openuse",
				"name" => "marketopenuse",
				"meta" => ['title'=>'已购应用', 'icon'=>'Present', 'alwaysShow'=>true],
				"redirect" => "/market/openuse",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/market/openuse",
						"name" => "marketopenuse",
						"meta" => ['title'=>'应用管理'],
						"component" => "() => import('@/pages/admin/market/openuse.vue')"
					],
				]
			],
			// [
			// 	"path" => "/market/order",
			// 	"name" => "marketorder",
			// 	"meta" => ['title'=>'市场订单', 'icon'=>'Tickets', 'alwaysShow'=>true],
			// 	"redirect" => "/market/order",
			// 	"component" => "RouterView",
			// 	"children" => [
			// 		[
			// 			"path" => "/market/order",
			// 			"name" => "marketorder",
			// 			"meta" => ['title'=>'订单列表'],
			// 			"component" => "() => import('@/pages/admin/market/order.vue')"
			// 		],
			// 	]
			// ],
		]
	],

];


<?php
/**
 * 这是管理后台的菜单文件
 */
return [
	//首页
	// [
	// 	"path" => "/index",
	// 	"meta" => ['title'=>'首页', 'icon'=>'EpHomeFilled'],
	// 	"component" => "MyLayout",
	// 	"children" => [[
	// 		"path" => "",
	// 		"name" => "dashboard",
	// 		"meta" => [ 'title'=>'Dashboard', 'hiddenInSideMenu'=>true, 'hidePageHeader'=>true],
	// 		"component" => "() => import('@/pages/admin/dashboard/index.vue')",
	// 	]]
	// ],
	[
		"path"=> '/',
		"redirect"=> '/basics/set',
		"meta"=>[ "hiddenInSideMenu"=> "true" ]
	],
	//系统
	[
		"path" => "/basics/set",
		"redirect" => "/user/list",
		"meta" => ['title'=>'系统', 'icon'=>'EpHomeFilled'],
		"component" => "MyLayout",
		"children" => [
			// [
			// 	"path" => "/cloudstorage",
			// 	"name" => "cloudstorage",
			// 	"meta" => ['title'=>'云存储', 'icon'=>'AntDesignBarsOutlined', 'alwaysShow'=>true],
			// 	"redirect" => "/cloudstorage/cloudStorage",
			// 	"component" => "RouterView",
			// 	"children" => [
			// 		[
			// 			"path" => "/cloudstorage/cloudStorage",
			// 			"name" => "appCloudStorage",
			// 			"meta" => ['title'=>'云存储'],
						// "component" => "() => import('@/pages/admin/cloudstorage/cloudStorage.vue')",
			// 		],
			// 	]
			// ],
			// [
			// 	"path" => "/basics/set",
			// 	"name" => "basics",
			// 	"meta" => ['title'=>'基础设置', 'icon'=>'Setting', 'alwaysShow'=>true],
			// 	"redirect" => "/basics/set",
			// 	"component" => "RouterView",
			// 	"children" => [
			// 		[
			// 			"path" => "/basics/set",
			// 			"name" => "basicsset",
			// 			"meta" => ['title'=>'基础设置'],
			// 			"component" => "() => import('@/pages/admin/basicsettings/basicSettings.vue')",
			// 		],
			// 		[
			// 			"path" => "/basics/logoset",
			// 			"name" => "basicslogoset",
			// 			"meta" => ['title'=>'logo设置'],
			// 			"component" => "() => import('@/pages/admin/basicsettings/logoSettings.vue')",
			// 		],
			// 		[
			// 			"path" => "/basics/pricesetting",
			// 			"name" => "pricesetting",
			// 			"meta" => ['title'=>'价格设置'],
			// 			"component" => "() => import('@/pages/admin/basicsettings/price.vue')",
			// 		]
			// 	]
			// ],
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
				]
			],
			[
				"path" => "/setmeal/list",
				"name" => "setmeal",
				"meta" => ['title'=>'套餐管理', 'icon'=>'Coin', 'alwaysShow'=>true],
				"redirect" => "/setmeal/list",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/setmeal/list",
						"name" => "setmeallist",
						"meta" => ['title'=>'套餐列表'],
						"component" => "() => import('@/pages/admin/packagemanagement/packagelist.vue')",
					],
					[
						"path" => "/setmeal/add",
						"name" => "setmealadd",
						"meta" => ['title'=>'新增套餐', 'hiddenInSideMenu'=>true],
						"component" => "() => import('@/pages/admin/packagemanagement/add.vue')",
					],
					[
						"path" => "/setmeal/auth",
						"name" => "setmealauth",
						"meta" => ['title'=>'套餐权限'],
						"component" => "() => import('@/pages/admin/packagemanagement/permission.vue')",
					],

				]
			],
			// [
			// 	"path" => "/agent/auth",
			// 	"name" => "agent",
			// 	"meta" => ['title'=>'代理管理', 'icon'=>'HelpFilled', 'alwaysShow'=>true],
			// 	"redirect" => "/agent/auth",
			// 	"component" => "RouterView",
			// 	"children" => [
			// 		// [
			// 		// 	"path" => "/agentmanagement/agentdiscount",
			// 		// 	"name" => "agentdiscount",
			// 		// 	"meta" => ['title'=>'代理商折扣'],
			// 		// 	"component" => "() => import('@/pages/admin/agentmanagement/agentdiscount.vue')",
			// 		// ],
			// 		[
			// 			"path" => "/agent/auth",
			// 			"name" => "agentauth",
			// 			"meta" => ['title'=>'代理商权限'],
			// 			"component" => "() => import('@/pages/admin/agentmanagement/agentauthority.vue')",
			// 		],

			// 	]
			// ],


		]
	],
	//引擎
];


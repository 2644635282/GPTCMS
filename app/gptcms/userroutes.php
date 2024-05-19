<?php
/**
 * 这是管理后台的菜单文件
 */
return [
	//首页
	[
		"path" => "/dashboard/index",
		"redirect" => "/dashboard/index",
		"meta" => ['title'=>'首页', 'icon'=>'Home'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/dashboard/index",
				"name" => "dashboard",
				"meta" => ['title'=>'首页', 'icon'=>'Home', 'alwaysShow'=>true],
				"redirect" => "/dashboard/index",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/dashboard/index",
						"name" => "dashboardIndex",
						"meta" => ['title'=>''],
						"component" => "() => import('@/pages/admin/dashboard/index.vue')",
					],
				]
			],
		]
	],
	//系统设置
	[
		"path" => "/system/index",
		"redirect" => "/system/index",
		"meta" => ['title'=>'系统设置', 'icon'=>'Setting'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/system/index",
				"name" => "system",
				"meta" => ['title'=>'系统设置', 'icon'=>'Setting', 'alwaysShow'=>true],
				"redirect" => "/system/index",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/system/index",
						"name" => "systemIndex",
						"meta" => ['title'=>'系统设置'],
						"component" => "() => import('@/pages/admin/system/index.vue')",
					],
					[
						"path" => "/system/wxpayconfig",
						"name" => "systemWxpayconfig",
						"meta" => ['title'=>'支付设置'],
						"component" => "() => import('@/pages/admin/system/wxpayconfig.vue')",
					],
					[
						"path" => "/system/aiconfig",
						"name" => "systemAiconfig",
						"meta" => ['title'=>'AI设置'],
						"component" => "() => import('@/pages/admin/system/aiconfig.vue')",
					],
					[
						"path" => "/system/gptconfig",
						"name" => "systemGptconfig",
						"meta" => ['title'=>'通道设置'],
						"component" => "() => import('@/pages/admin/system/gptconfig.vue')",
					],
					[
						"path" => "/system/oss",
						"name" => "systemOss",
						"meta" => ['title'=>'存储设置'],
						"component" => "() => import('@/pages/admin/system/oss.vue')",
					],
					[
						"path" => "/system/safe",
						"name" => "systemSafe",
						"meta" => ['title'=>'安全设置'],
						"component" => "() => import('@/pages/admin/system/safe.vue')",
					],
					[
						"path" => "/system/sms",
						"name" => "systemSms",
						"meta" => ['title'=>'短信设置'],
						"component" => "() => import('@/pages/admin/system/sms.vue')",
					],
					[
						"path" => "/system/hot",
						"name" => "systemHot",
						"meta" => ['title'=>'热门提问'],
						"component" => "() => import('@/pages/admin/system/hot.vue')",
					],
				]
			],
			[
				"path" => "/channel/pc",
				"name" => "channel",
				"meta" => ['title'=>'渠道管理', 'icon'=>'Channel', 'alwaysShow'=>true],
				"redirect" => "/channel/pc",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/channel/site",
						"name" => "channelSite",
						"meta" => ['title'=>'站点管理'],
						"component" => "() => import('@/pages/admin/channel/site.vue')",
					],
					[
						"path" => "/channel/office",
						"name" => "channelOffice",
						"meta" => ['title'=>'公众号设置'],
						"component" => "() => import('@/pages/admin/channel/office.vue')",
					],
					[
						"path" => "/channel/h5",
						"name" => "channelH5",
						"meta" => ['title'=>'分享设置'],
						"component" => "() => import('@/pages/admin/channel/h5.vue')",
					],
					[
						"path" => "/channel/miniprogram",
						"name" => "channelMiniprogram",
						"meta" => ['title'=>'小程序设置'],
						"component" => "() => import('@/pages/admin/channel/miniprogram.vue')",
					],
				]
			],
		]
	],
	//会员管理
	[
		"path" => "/user/list",
		"redirect" => "/user/list",
		"meta" => ['title'=>'会员管理', 'icon'=>'Member'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/user/list",
				"name" => "user",
				"meta" => ['title'=>'会员管理', 'icon'=>'Member', 'alwaysShow'=>true],
				"redirect" => "/user/list",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/user/list",
						"name" => "userlist",
						"meta" => ['title'=>'会员列表'],
						"component" => "() => import('@/pages/admin/user/list.vue')",
					],
					[
						"path" => "/user/edit",
						"name" => "userEdit",
						"isHide" => true,
						"meta" => ['title'=>'', 'hiddenInSideMenu'=>true],
						"component" => "() => import('@/pages/admin/user/edit.vue')",
					],
				]
			],
		]
	],
	//对话记录
	[
		"path" => "/dialogue/list",
		"redirect" => "/dialogue/list",
		"meta" => ['title'=>'对话记录', 'icon'=>'Chat'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/dialogue/list",
				"name" => "dialogue",
				"meta" => ['title'=>'对话记录', 'icon'=>'Chat', 'alwaysShow'=>true],
				"redirect" => "/dialogue/list",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/dialogue/list",
						"name" => "dialoguelist",
						"meta" => ['title'=>'首页聊天'],
						"component" => "() => import('@/pages/admin/dialogue/list.vue')",
					],
					[
						"path" => "/dialogue/create",
						"name" => "dialoguecreate",
						"meta" => ['title'=>'创作聊天'],
						"component" => "() => import('@/pages/admin/dialogue/create.vue')",
					],
					[
						"path" => "/dialogue/role",
						"name" => "dialoguerole",
						"meta" => ['title'=>'角色聊天'],
						"component" => "() => import('@/pages/admin/dialogue/role.vue')",
					],
					[
						"path" => "/dialogue/paint",
						"name" => "dialoguepaint",
						"meta" => ['title'=>'绘画记录'],
						"component" => "() => import('@/pages/admin/dialogue/paint.vue')",
					],
				]
			],
		]
	],
	//营销管理
	[
		"path" => "/package/list",
		"redirect" => "/package/list",
		"meta" => ['title'=>'营销管理', 'icon'=>'Market'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/package/list",
				"name" => "package",
				"meta" => ['title'=>'营销管理', 'icon'=>'Market', 'alwaysShow'=>true],
				"redirect" => "/package/list",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/package/listAdd",
						"name" => "packageList",
						"meta" => ['title'=>'VIP套餐'],
						"component" => "() => import('@/pages/admin/package/listAdd.vue')",
					],
					[
						"path" => "/package/list",
						"name" => "packagelist",
						"meta" => ['title'=>'充值套餐'],
						"component" => "() => import('@/pages/admin/package/list.vue')",
					],
					[
						"path" => "/package/add",
						"name" => "packageAdd",
						"isHide" => true,
						"meta" => ['title'=>'', 'hiddenInSideMenu'=>true],
						"component" => "() => import('@/pages/admin/package/add.vue')",
					],
					[
						"path" => "/package/share",
						"name" => "packageShare",
						"meta" => ['title'=>'分享奖励'],
						"component" => "() => import('@/pages/admin/package/share.vue')",
					],
					[
						"path" => "/package/invite",
						"name" => "packageInvite",
						"meta" => ['title'=>'邀请奖励'],
						"component" => "() => import('@/pages/admin/package/invite.vue')",
					],
				]
			],
		]
	],
	//订单管理
	[
		"path" => "/order/list",
		"redirect" => "/order/list",
		"meta" => ['title'=>'订单管理', 'icon'=>'Order'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/order/list",
				"name" => "order",
				"meta" => ['title'=>'订单列表', 'icon'=>'Order', 'alwaysShow'=>true],
				"redirect" => "/order/list",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/order/list",
						"name" => "orderlist",
						"meta" => ['title'=>'订单列表'],
						"component" => "() => import('@/pages/admin/order/list.vue')",
					],
				]
			],
		]
	],
	//模型管理
	[
		"path" => "/model/list",
		"redirect" => "/model/list",
		"meta" => ['title'=>'模型管理', 'icon'=>'Role'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/model/list",
				"name" => "model",
				"meta" => ['title'=>'创作模型', 'icon'=>'Role', 'alwaysShow'=>true],
				"redirect" => "/model/list",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/model/list",
						"name" => "modellist",
						"meta" => ['title'=>'模型管理'],
						"component" => "() => import('@/pages/admin/model/list.vue')",
					],
					[
						"path" => "/model/classify",
						"name" => "modelClassify",
						"meta" => ['title'=>'分类管理'],
						"component" => "() => import('@/pages/admin/model/classification.vue')",
					],
					[
						"path" => "/model/add",
						"name" => "modelAdd",
						"isHide" => true,
						"meta" => ['title'=>'', 'hiddenInSideMenu'=>true],
						"component" => "() => import('@/pages/admin/model/add.vue')",
					],
				]
			],
			[
				"path" => "/role/list",
				"name" => "role",
				"meta" => ['title'=>'角色模型', 'icon'=>'Role', 'alwaysShow'=>true],
				"redirect" => "/role/list",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/role/list",
						"name" => "rolelist",
						"meta" => ['title'=>'模型管理'],
						"component" => "() => import('@/pages/admin/role/list.vue')",
					],
					[
						"path" => "/role/classify",
						"name" => "roleClassify",
						"meta" => ['title'=>'分类管理'],
						"component" => "() => import('@/pages/admin/role/classification.vue')",
					],
					[
						"path" => "/role/add",
						"name" => "roleAdd",
						"isHide" => true,
						"meta" => ['title'=>'', 'hiddenInSideMenu'=>true],
						"component" => "() => import('@/pages/admin/role/add.vue')",
					],
				]
			],
		]
	],
	//增值功能
	[
		"path" => "/more/list",
		"redirect" => "/more/list",
		"meta" => ['title'=>'增值功能', 'icon'=>'Model'],
		"component" => "MyLayout",
		"children" => [
			[
				"path" => "/more/list",
				"name" => "more",
				"meta" => ['title'=>'增值功能', 'icon'=>'Model', 'alwaysShow'=>true],
				"redirect" => "/more/list",
				"component" => "RouterView",
				"children" => [
					[
						"path" => "/more/list",
						"name" => "systemInstruct",
						"meta" => ['title'=>'前置指令'],
						"component" => "() => import('@/pages/admin/system/instruct.vue')",
					],
					[
						"path" => "/system/model",
						"name" => "systemModel",
						"meta" => ['title'=>'模型切换'],
						"component" => "() => import('@/pages/admin/system/model.vue')",
					],
					[
						"path" => "/more/keys",
						"name" => "systemKeys",
						"meta" => ['title'=>'key池管理'],
						"component" => "() => import('@/pages/admin/system/keys.vue')",
					],
					[
						"path" => "/package/card",
						"name" => "packageCard",
						"meta" => ['title'=>'卡密兑换'],
						"component" => "() => import('@/pages/admin/package/card.vue')",
					],
					[
						"path" => "/more/paint",
						"name" => "morePaint",
						"meta" => ['title'=>'高级绘画'],
						"component" => "() => import('@/pages/admin/more/paint.vue')",
					],
					[
						"path" => "/more/api",
						"name" => "moreApi",
						"meta" => ['title'=>'API对接'],
						"component" => "() => import('@/pages/admin/more/api.vue')",
					],
					[
						"path" => "/more/background",
						"name" => "moreBackground",
						"meta" => ['title'=>'百变背景'],
						"component" => "() => import('@/pages/admin/more/background.vue')",
					],
					[
						"path" => "/more/knowledge",
						"name" => "moreKnowledge",
						"meta" => ['title'=>'知识库训练'],
						"component" => "() => import('@/pages/admin/more/knowledge.vue')",
					],
					[
						"path" => "/more/domains",
						"name" => "moreKnowledge",
						"meta" => ['title'=>'多域名管理'],
						"component" => "() => import('@/pages/admin/more/domains.vue')",
					],
				]
			],
		]
	],
];


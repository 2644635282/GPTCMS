
CREATE TABLE `kt_gptcms_aliai_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `accesskey_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `accesskey_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `appkey` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_baiduai_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `appid` int(11) NULL DEFAULT NULL,
  `apikey` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `secretkey` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_chat_msg`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '终端用户id',
  `un_message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '用户发出内容(未过滤)',
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '用户发出内容(已过滤)',
  `un_response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '助手回复内容(未过滤)',
  `response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '助手回复内容(已过滤)',
  `total_tokens` int(11) NULL DEFAULT NULL COMMENT '发出+回复字符串的总长度',
  `c_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 130 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_cmodel`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `tp_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图片url',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
  `bz` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `xh` int(11) NULL DEFAULT 0 COMMENT '序号 越大越靠前',
  `vip_status` tinyint(1) NULL DEFAULT 0 COMMENT 'vip是否可使用 1可以使用 0不能使用',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态 1正常 0暂停使用',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  `classify_id` int(5) NULL DEFAULT NULL COMMENT '分类id',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '模型内容',
  `hint_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '对话框提示文字',
  `defalut_question` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '默认问题',
  `defalut_reply` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '默认回复',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 36 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '创作模型' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_cmodel_classify`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_common_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '系统用户id',
  `type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'xcx 微信小程序 h5 H5 wx 微信',
  `parent` int(11) NULL DEFAULT 0 COMMENT '所属上级',
  `level` int(11) NULL DEFAULT 1 COMMENT '级别',
  `residue_degree` int(11) NULL DEFAULT 0 COMMENT '剩余条数',
  `vip_expire` datetime NULL DEFAULT NULL COMMENT 'vip到期时间',
  `vip_open` datetime NULL DEFAULT NULL COMMENT 'vip开通时间',
  `money` decimal(10, 2) NULL DEFAULT 0.00 COMMENT '余额',
  `mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `nickname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `headimgurl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '账号',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '密码',
  `unionid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '开放平台id',
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'token',
  `expire_time` int(11) NULL DEFAULT NULL COMMENT 'token过期时间',
  `xcx_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '微信小程序token',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态 1正常 0停用',
  `bz` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 62 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_content_security`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `question_baiduai` tinyint(1) NULL DEFAULT 1 COMMENT '提问审核 百度ai文本审核开关 1开启 0关闭',
  `reply_baiduai` tinyint(1) NULL DEFAULT 1 COMMENT '回复审核 百度ai文本审核开关  1开启 0关闭',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '内容安全设置' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_create_msg`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '终端用户id',
  `model_id` int(11) NULL DEFAULT NULL COMMENT '创作模型id',
  `un_message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '用户发出内容(未过滤)',
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '用户发出内容(已过滤)',
  `make_message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '最终组合提交接口的内容',
  `un_response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '助手回复内容(未过滤)',
  `response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '助手回复内容(已过滤)',
  `total_tokens` int(11) NULL DEFAULT NULL COMMENT '发出+回复字符串的总长度',
  `c_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 62 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_gpt_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `channel` tinyint(1) NULL DEFAULT 1 COMMENT '渠道1.openai 2.api2d 3.文心一言 4.通义千问 5.昆仑天工 6.ChatGLM',
  `openai` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'openai',
  `api2d` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'api2d',
  `wxyy` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '文心一言配置',
  `tyqw` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '通义千文',
  `kltg` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '昆仑天工',
  `chatglm` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'chatglm',
  `linkerai` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '灵犀星火',
  `u_time` datetime NULL DEFAULT NULL,
  `c_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_h5_wx`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '页面标题',
  `share_tile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享标题',
  `share_desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享描述',
  `share_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享图片链接',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '微信登陆 1开启 0关闭',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_invite_award`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT NULL COMMENT ' 1开启 0关闭',
  `number` int(11) NULL DEFAULT NULL COMMENT '邀请一次奖励数量',
  `up_limit` int(11) NULL DEFAULT NULL COMMENT '邀请人数上限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '邀请奖励' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_jmodel`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `tp_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图片url',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
  `bz` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
  `xh` int(11) NULL DEFAULT 0 COMMENT '序号 越大越靠前',
  `vip_status` tinyint(1) NULL DEFAULT 0 COMMENT 'vip是否可使用 1可以使用 0不能使用',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态 1正常 0暂停使用',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  `classify_id` int(5) NULL DEFAULT NULL COMMENT '分类id',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '模型内容',
  `hint_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '对话框提示文字',
  `defalut_question` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '默认问题',
  `defalut_reply` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '默认回复',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色模型' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_jmodel_classify`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '角色模型分类' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_pay`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `out_trade_no` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '微信自定义订单号',
  `orderid` int(11) NULL DEFAULT NULL COMMENT '订单id',
  `order_bh` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单编号',
  `wid` int(11) NULL DEFAULT NULL COMMENT '账户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `uip` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '请求ip',
  `amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '金额',
  `status` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '状态',
  `alipayzt` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `bz` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ifok` int(11) NULL DEFAULT NULL COMMENT '是否支付 1:已支付  0:未支付',
  `wxddbh` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '微信订单编号',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL,
  `jyh` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '交易单号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 57 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_pay_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `config` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '支付配置',
  `type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '类型: wx 微信  ali 支付宝',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '支付配置' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_pay_order`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `common_id` int(11) NULL DEFAULT NULL,
  `pay_time` datetime NULL DEFAULT NULL COMMENT '支付时间',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  `amount` decimal(10, 2) NULL DEFAULT NULL COMMENT '金额',
  `order_bh` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '订单编号',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '1待支付 2支付成功 3取消支付',
  `number` int(11) NULL DEFAULT NULL COMMENT '数量',
  `type` tinyint(1) NULL DEFAULT 9 COMMENT '1天 2周 3月 4季度  5年 9条',
  `pay_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'wx 微信   alipay支付宝',
  `ddbh` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '微信订单号',
  `setmeal_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'vip VIP套餐  recharge充值套餐',
  `setmeal_id` int(11) NULL DEFAULT NULL COMMENT '套餐表id',
  `buy_number` int(11) NULL DEFAULT NULL COMMENT '购买数量',
  `transaction_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '微信交易订单号',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 81 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '支付订单记录' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_pc`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '页面标题',
  `bottom_desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '底部声明',
  `desc_link` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '生命链接',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '微信登陆 1开启 0关闭',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_recharge_setmeal`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `sequence` int(11) NULL DEFAULT NULL COMMENT '序号  越大越靠前',
  `number` int(11) NULL DEFAULT NULL COMMENT '数量',
  `price` decimal(10, 2) NULL DEFAULT NULL COMMENT '价格',
  `old_price` decimal(10, 2) NULL DEFAULT NULL COMMENT '划线价',
  `bz` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_reward_record`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '终端用户id',
  `num` int(11) NULL DEFAULT NULL COMMENT '奖励条数',
  `type` tinyint(1) NULL DEFAULT 1 COMMENT '奖励类型 1注册 2登录 3邀请',
  `c_time` datetime NULL DEFAULT NULL COMMENT '奖励时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_role_msg`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '终端用户id',
  `model_id` int(11) NULL DEFAULT NULL COMMENT '角色模型id',
  `tip_message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '指令内容',
  `un_message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '用户发出内容(未过滤)',
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '用户发出内容(已过滤)',
  `un_response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '助手回复内容(未过滤)',
  `response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '助手回复内容(已过滤)',
  `total_tokens` int(11) NULL DEFAULT NULL COMMENT '发出+回复字符串的总长度',
  `c_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_share_award`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT NULL COMMENT ' 1开启 0关闭',
  `number` int(11) NULL DEFAULT NULL COMMENT '分享一次奖励数量',
  `up_limit` int(11) NULL DEFAULT NULL COMMENT '分享数量上限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '分享奖励' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_share_rewards`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '终端用户id',
  `num` int(11) NULL DEFAULT NULL COMMENT '奖励条数',
  `c_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_sms_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '账户id',
  `access_key_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '阿里云短信key',
  `access_key_secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '阿里云短信密钥',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '阿里云短信配置' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_sms_template`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `bh` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sign_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `template_code` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_system`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `rz_number` int(11) NULL DEFAULT 0 COMMENT '注册赠送次数',
  `dz_number` int(11) NULL DEFAULT 0 COMMENT '每日赠送次数',
  `dz_remind` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '超出每日赠送次数提醒',
  `zdz_number` int(11) NULL DEFAULT NULL COMMENT '总每日赠送次数限制',
  `zdz_remind` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '超出每日总次数提示语',
  `yq_number` int(11) NULL DEFAULT 0 COMMENT '邀请奖励次数',
  `welcome` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '欢迎语',
  `sms` tinyint(1) NULL DEFAULT 0 COMMENT '短信开关 1开启 0关闭',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_tencentai_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `secret_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `secret_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_vad_award`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT NULL COMMENT ' 1开启 0关闭',
  `number` int(11) NULL DEFAULT NULL COMMENT '邀请一次奖励数量',
  `up_limit` int(11) NULL DEFAULT NULL COMMENT '邀请人数上限',
  `ad_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '广告位id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '观看广告奖励' ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_vip_setmeal`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sort` int(11) NULL DEFAULT 0 COMMENT '序号 越大越靠前',
  `duration` int(11) NULL DEFAULT NULL COMMENT '时长',
  `duration_type` tinyint(1) NULL DEFAULT NULL COMMENT '1天 2周 3月 4季度  5年',
  `price` decimal(10, 2) NULL DEFAULT NULL COMMENT '价格',
  `old_price` decimal(10, 2) NULL DEFAULT NULL COMMENT '原价',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_websit`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '站点标题',
  `sms` tinyint(1) NULL DEFAULT 0,
  `kfcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '客服二维码',
  `gzhcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '公众号二维码',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_wx_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '系统用户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '关联基础用户id',
  `openid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'openid',
  `mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `nickname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `headimgurl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `sex` tinyint(1) NULL DEFAULT 0 COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '市',
  `province` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '省',
  `country` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '国家',
  `unionid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '开放平台id',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_wxgzh`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NOT NULL,
  `appid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `appsecret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '令牌',
  `message_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '消息加解密密钥',
  `message_mode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '明文加密',
  `type` tinyint(1) NULL DEFAULT 1 COMMENT '1手动配置 2扫码授权',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;



CREATE TABLE `kt_gptcms_xcx`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `share_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分享图片链接',
  `ios_status` tinyint(1) NULL DEFAULT 0 COMMENT 'ios支付 1开启 0关闭',
  `xcx_audit` tinyint(1) NULL DEFAULT NULL COMMENT '小程序审核模式 1开启 0关闭',
  `create_time` datetime NULL DEFAULT NULL,
  `update_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_xcx_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '系统用户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '关联基础用户id',
  `openid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'openid',
  `mobile` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `nickname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '昵称',
  `headimgurl` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '头像',
  `sex` tinyint(1) NULL DEFAULT 1 COMMENT '性别 0女 1男',
  `city` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '市',
  `province` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '省',
  `country` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '国家',
  `unionid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '开放平台id',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


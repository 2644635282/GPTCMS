
CREATE TABLE `kt_gptcms_chat_msg_group`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '客户端用户id',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '分组名称',
  `c_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = DYNAMIC;

CREATE TABLE `kt_gptcms_paintmodel_set`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `sd` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '灵犀星火 sd绘画',
  `yjai` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '意间Ai',
  `gpt35` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'openai 绘画',
  `api2d35` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'api2d绘画',
  `replicate` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'replicateMJ',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '1开启 0关闭',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '绘画模型设置' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_paintmsg_notify`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `task_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '回调任务di',
  `chatmodel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '渠道',
  `msgid` int(11) NULL DEFAULT NULL COMMENT '消息id',
  `c_time` datetime NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '0未回调  1已回调',
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_paint_msg`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '终端用户id',
  `un_message` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL COMMENT '用户发出内容(未过滤)',
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '用户发出内容(已过滤)',
  `un_response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '助手回复内容(未过滤)',
  `response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '助手回复内容(已过滤)',
  `total_tokens` int(11) NULL DEFAULT NULL COMMENT '发出 回复字符串的总长度',
  `c_time` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '0生成失败 1处理中 2处理成功',
  `u_time` int(11) NULL DEFAULT NULL,
  `sync_status` tinyint(1) NULL DEFAULT 0 COMMENT '1已同步 0未同步',
  `chatmodel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '渠道',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

ALTER TABLE `kt_gptcms_chat_msg` ADD COLUMN `group_id` int(11) NULL DEFAULT 0 COMMENT '' AFTER `common_id`;
ALTER TABLE `kt_gptcms_gptpaint_config` ADD COLUMN `replicate` text NULL DEFAULT NULL;

CREATE TABLE `kt_gptcms_api_employ`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '应用名称',
  `api_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'API_KEY',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态0关闭 1开启',
  `ctime` datetime NULL DEFAULT NULL,
  `utime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_draw_classify`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '作品分类' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_draw_desclexicon`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '词库内容',
  `xh` int(11) NULL DEFAULT NULL COMMENT '序号 越大越靠前',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '高级绘画 描述词库' ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_draw_msg`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `common_id` int(11) NULL DEFAULT NULL COMMENT '终端用户id',
  `un_message` text CHARACTER SET utf16 COLLATE utf16_general_ci NULL COMMENT '用户发出内容(未过滤)',
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '用户发出内容(已过滤)',
  `un_response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '助手回复内容(未过滤)',
  `response` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '助手回复内容(已过滤)',
  `total_tokens` int(11) NULL DEFAULT NULL COMMENT '发出+回复字符串的总长度',
  `c_time` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '0生成失败 1处理中 2处理成功',
  `u_time` int(11) NULL DEFAULT NULL,
  `sync_status` tinyint(1) NULL DEFAULT 0 COMMENT '1已同步 0未同步',
  `chatmodel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '渠道',
  `style` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '风格指令',
  `size` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '尺寸',
  `pid` int(11) NULL DEFAULT 0 COMMENT '放大上级id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_draw_msgtp`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `common_id` int(11) NULL DEFAULT NULL COMMENT '用户id',
  `msg_id` int(11) NULL DEFAULT NULL COMMENT '消息id',
  `message` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '提示词',
  `classfy_id` int(11) NULL DEFAULT NULL COMMENT '分类id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
  `image` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '图片地址',
  `del_status` tinyint(1) NULL DEFAULT 0 COMMENT '是否删除 1删除  0未删除 ',
  `open_status` tinyint(1) NULL DEFAULT 0 COMMENT '公开状态 1公开 0私密',
  `hot_status` tinyint(1) NULL DEFAULT 0 COMMENT '热门状态 1热门 0不是热门',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_draw_notify`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `task_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '回调任务di',
  `chatmodel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '渠道',
  `msgid` int(11) NULL DEFAULT NULL COMMENT '消息iid',
  `c_time` datetime NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '0未回调  1已回调',
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_draw_style`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `tp_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图片url',
  `desc` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '指令',
  `xh` int(11) NULL DEFAULT 0 COMMENT '序号 越大越靠前',
  `vip_status` tinyint(1) NULL DEFAULT 0 COMMENT 'vip是否可使用 1可以使用 0不能使用',
  `status` tinyint(1) NULL DEFAULT 1 COMMENT '状态 1正常 0暂停使用',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '风格' ROW_FORMAT = Dynamic;


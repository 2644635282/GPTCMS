
CREATE TABLE `kt_gptcms_card`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NULL DEFAULT NULL,
  `type` int(10) NULL DEFAULT NULL COMMENT '1为对话次，3为vip时长',
  `size` int(10) NULL DEFAULT NULL COMMENT '与类型为对应',
  `remark` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注信息',
  `amount` int(10) NULL DEFAULT NULL COMMENT '生成卡密数量',
  `ctime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_card_detail`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `pid` int(10) NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '卡密',
  `time` datetime NULL DEFAULT NULL COMMENT '使用时间',
  `user` int(10) NULL DEFAULT NULL COMMENT '使用者',
  `status` int(10) NULL DEFAULT 0 COMMENT '0未使用，1为已使用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_chatmodel_set`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '1开启  0关闭',
  `gpt35` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'GPT3.5',
  `gpt4` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'Gpt4',
  `linkerai` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '灵犀星火',
  `api2d35` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'api2d3.5',
  `api2d4` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'api2d4',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '对话模型设置' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_gptpaint_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `channel` tinyint(1) NULL DEFAULT 1 COMMENT '渠道1 意间',
  `yjai` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '意间AI',
  `u_time` datetime NULL DEFAULT NULL,
  `c_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_hot`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '内容',
  `sort` int(11) NULL DEFAULT 0 COMMENT '越大越靠前',
  `classify_id` int(11) NULL DEFAULT NULL COMMENT '分类id',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_hot_classify`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '图标',
  `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '热门分类' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_qzzl`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 0,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `u_time` datetime NULL DEFAULT NULL,
  `c_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

ALTER TABLE `kt_gptcms_gpt_config` ADD COLUMN `gpt4` text NULL AFTER `linkerai`, ADD COLUMN `api2d4` text NULL AFTER `gpt4`;
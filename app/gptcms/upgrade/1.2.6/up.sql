CREATE TABLE `kt_gptcms_bj_background`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `thumbnail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '样式图片',
  `background_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_bj_config`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NULL DEFAULT NULL,
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_bj_paint`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NULL DEFAULT NULL,
  `common_id` int(10) NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '上传文件的路径',
  `status` int(10) NULL DEFAULT NULL COMMENT '1为生成中，2为生成失败，3为生成成功',
  `c_time` datetime(0) NULL DEFAULT NULL,
  `images` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '生成图片的集合',
  `background_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '背景样式id',
  `size` int(10) NULL DEFAULT NULL COMMENT '生成数量',
  `upload_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '本次生成图片的id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_knowledge`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NULL DEFAULT NULL,
  `kbid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mode` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ctime` datetime(0) NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tag` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_knowledge_list`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NOT NULL,
  `kbid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `q` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `a` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `ctime` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_setmeal_auth`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) NULL DEFAULT NULL COMMENT '级别id',
  `auths` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '权限json',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户权限表' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_setmeal_price`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '代理id ',
  `user_type_id` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '代理已设置的套餐id列表',
  `price` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '代理已设置的套餐金额列表',
  `c_time` datetime(0) NULL DEFAULT NULL,
  `u_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '代理用户套餐设置' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_setmeal_record`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) UNSIGNED NOT NULL COMMENT '员工id',
  `level_id` tinyint(1) NOT NULL COMMENT '修改到的套餐id',
  `difference` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '差价，如果(负数)降低套餐则视为忽略',
  `price` int(10) NOT NULL COMMENT '价格，套餐实际价格',
  `money` int(10) NOT NULL COMMENT '实际支付金额',
  `months` int(11) NOT NULL COMMENT '年数',
  `days` int(11) NOT NULL COMMENT '天数',
  `ctime` datetime(0) NULL DEFAULT NULL COMMENT '购买时间',
  `mendtime` datetime(0) NULL DEFAULT NULL COMMENT '到期时间',
  `agent_id` int(10) NULL DEFAULT NULL COMMENT '当前代理',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM  CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户充值记录表' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_setmeal_type`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level` int(11) NULL DEFAULT NULL COMMENT '当前级别',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '级别名称',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM  CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户套餐设置' ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_tts_config`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NULL DEFAULT NULL,
  `appid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `secret` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_user_setmeal`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL COMMENT '用户',
  `level` int(11) NULL DEFAULT NULL COMMENT '当前用户的套餐级别',
  `mend_time` datetime(0) NULL DEFAULT NULL COMMENT '到期时间',
  `create_time` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;



ALTER TABLE `kt_gptcms_aliai_config` ADD COLUMN `type` int(10) NULL DEFAULT 1 AFTER `status`;
ALTER TABLE `kt_gptcms_tencentai_config` ADD COLUMN `status` int(10) NULL DEFAULT 0  AFTER `secret_key`;
ALTER TABLE `kt_gptcms_wxgzh` ADD COLUMN `original_id` varchar(255)  NULL DEFAULT NULL AFTER `update_time`;
ALTER TABLE `kt_gptcms_gpt_config` ADD COLUMN `xfxh` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '讯飞星火' AFTER `api2d4`;
ALTER TABLE `kt_gptcms_gpt_config` ADD COLUMN `fastgpt` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'FastGpt' AFTER `c_time`;
ALTER TABLE `kt_gptcms_chatmodel_set` ADD COLUMN `xfxh` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '讯飞星火' AFTER `wxyy`;
ALTER TABLE `kt_gptcms_chatmodel_set` ADD COLUMN `fastgpt` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'Fastgpt' AFTER `u_time`;
ALTER TABLE `kt_gptcms_chatmodel_set` ADD COLUMN `chatglm` text NULL COMMENT 'chatGlm' AFTER `fastgpt`;


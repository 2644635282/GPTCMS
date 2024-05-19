
CREATE TABLE `kt_gptcms_loginerror_record`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `account` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `c_time` datetime NULL DEFAULT NULL,
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '0密码正确 1密码错误',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_sms_record`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `phone` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '手机号',
  `c_time` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_syncpaint`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `is_sync` tinyint(1) NULL DEFAULT 0,
  `c_time` datetime NULL DEFAULT NULL,
  `u_time` datetime NULL DEFAULT NULL,
  `msg_id` int(11) NULL DEFAULT NULL,
  `source_type` tinyint(1) NULL DEFAULT 0 COMMENT '1通用  2高级',
  `mj_url` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '原mj图片地址',
  `local_url` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '下载的图片地址',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


ALTER TABLE `kt_gptcms_common_user` ADD COLUMN `lock_time` int(11) NULL DEFAULT 0 COMMENT '锁定结束时间';
ALTER TABLE `kt_gptcms_draw_msg` ADD COLUMN `imageurl` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '图生图所用image';
ALTER TABLE `kt_gptcms_gptpaint_config` ADD COLUMN `syncmj` tinyint(1) NULL DEFAULT 0 COMMENT '是否开启灵犀MJ图片下载' AFTER `draw_status`;

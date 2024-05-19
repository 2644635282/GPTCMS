CREATE TABLE `kt_gptcms_random`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '访问的客户openid',
  `random` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '生成所携带的随机数',
  `ctime` datetime NULL DEFAULT NULL,
  `wid` int(10) NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '二维码图片',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

ALTER TABLE `kt_gptcms_paintmodel_set` ADD COLUMN `linkerai_mj` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '灵犀星火 MJ绘画' AFTER `replicate`;
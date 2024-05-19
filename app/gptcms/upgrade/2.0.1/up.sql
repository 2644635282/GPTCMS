
CREATE TABLE `kt_gptcms_paintapishopmsg_notify`  (
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


ALTER TABLE `kt_gptcms_chatmodel_set` ADD COLUMN `tyqw` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '同义千问';

ALTER TABLE `kt_gptcms_chatmodel_set` ADD COLUMN `azure` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '微软';

ALTER TABLE `kt_gptcms_chatmodel_set` ADD COLUMN `minimax` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `kt_gptcms_chatmodel_set` ADD COLUMN `txhy` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '腾讯混元';

ALTER TABLE `kt_gptcms_chatmodel_set` ADD COLUMN `kw` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '狂问';

ALTER TABLE `kt_gptcms_gpt_config` ADD COLUMN `azure` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '微软';

ALTER TABLE `kt_gptcms_gpt_config` ADD COLUMN `minimax` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'minimax';

ALTER TABLE `kt_gptcms_gpt_config` ADD COLUMN `txhy` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '腾讯混元';

ALTER TABLE `kt_gptcms_gpt_config` ADD COLUMN `kw` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '狂问';

ALTER TABLE `kt_gptcms_gptpaint_config` ADD COLUMN `apishop` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '接口商店';

ALTER TABLE `kt_gptcms_gptpaint_config` ADD COLUMN `draw_channel` tinyint(1) NULL DEFAULT 6 COMMENT '高级绘画通道 6灵犀MJ  7apishop';

ALTER TABLE `kt_gptcms_paintmodel_set` ADD COLUMN `apishop` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '接口商店';
ALTER TABLE `kt_gptcms_system` ADD COLUMN `self_balance` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '自定义余额' AFTER `sms`;
ALTER TABLE `kt_gptcms_gptpaint_config` ADD COLUMN `status` tinyint(1) NULL DEFAULT 1 COMMENT '是否开启绘画 1开启  0关闭' AFTER `replicate`;
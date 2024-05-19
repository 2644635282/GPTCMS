
CREATE TABLE `kt_gptcms_gzh_interest`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NULL DEFAULT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '回复消息列表',
  `ctime` datetime NULL DEFAULT NULL,
  `status` int(10) NULL DEFAULT 1 COMMENT '1为启动，2为关闭，默认为关闭',
  `type` int(10) NULL DEFAULT NULL COMMENT '1为被关注回复，2为收到消息回复',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;


CREATE TABLE `kt_gptcms_gzh_keyword`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '规则名称',
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '1' COMMENT '规则类型，1为全匹配，2为半匹配，默认为2',
  `word` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '关键词',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '回复内容',
  `reply_type` int(10) NULL DEFAULT 1 COMMENT '回复方式，默认为随机回复一条，1为随机，2为全部回复',
  `ctime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_keys`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `chatmodel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '渠道',
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'key',
  `state` tinyint(1) NULL DEFAULT 1 COMMENT '状态0停用1正常',
  `stop_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '停用原因',
  `ctime` int(11) NULL DEFAULT NULL,
  `utime` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_keys_switch`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `chatmodel` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '渠道',
  `switch` tinyint(1) NULL DEFAULT 0 COMMENT '开关0关闭1开启',
  `utime` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_menu`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `wid` int(10) NOT NULL,
  `pid` int(10) NULL DEFAULT 0 COMMENT '本表中一级菜单id',
  `type` int(10) NULL DEFAULT NULL COMMENT '1为一级菜单，2为二级菜单',
  `menu_type` int(10) NULL DEFAULT NULL COMMENT '菜单类型，1为关键词，2为小程序，3为跳转url',
  `keys` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单类型为1，key值',
  `ctime` datetime NULL DEFAULT NULL,
  `appid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单类型为2，小程序appid',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单类型为2，小程序url',
  `pagepath` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单类型为2，小程序的页面路径',
  `menu_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '菜单类型为3，url',
  `order` int(10) NULL DEFAULT NULL COMMENT '排序，1-5,1-3',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '名称',
  `status` int(10) NULL DEFAULT 1 COMMENT '1为正常，2为删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_vip_equity`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wid` int(11) NULL DEFAULT NULL,
  `explain` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '权益说明',
  `utime` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB  CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

CREATE TABLE `kt_gptcms_alipay_config` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`wid`  int(10) NULL DEFAULT NULL ,
`app_id`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'appid' ,
`merchant_private_key`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '应用私钥' ,
`alipay_public_key`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '支付宝公钥' ,
`status`  int(10) NULL DEFAULT 0 COMMENT '1为开启，0为关闭' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
ROW_FORMAT=Dynamic
;

ALTER TABLE `kt_gptcms_chatmodel_set` ADD COLUMN `wxyy` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '文心一言' AFTER `api2d4`;
ALTER TABLE `kt_gptcms_gptpaint_config` ADD COLUMN `draw_status` tinyint(1) NULL DEFAULT 0 COMMENT '是否开启高级绘画 1开启 0关闭' AFTER `status`;
ALTER TABLE `kt_gptcms_system` ADD COLUMN `gpt4_charging` tinyint(1) NULL DEFAULT 0 COMMENT 'GPT4单独计费 1开启 0关闭 ' AFTER `self_balance`,ADD COLUMN `lxmj_charging` tinyint(1) NULL DEFAULT 0 COMMENT '灵犀-MJ单独计费 1开启 0关闭 ' AFTER `gpt4_charging`;
ALTER TABLE `kt_gptcms_vip_setmeal` ADD COLUMN `give_num` int(11) NULL DEFAULT 0 COMMENT '开通VIP会员一次性赠送条数' AFTER `old_price`;



ALTER TABLE `kt_gptcms_chat_msg` 
MODIFY COLUMN `un_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '用户发出内容(未过滤)' AFTER `group_id`,
MODIFY COLUMN `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '用户发出内容(已过滤)' AFTER `un_message`,
MODIFY COLUMN `un_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '助手回复内容(未过滤)' AFTER `message`,
MODIFY COLUMN `response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '助手回复内容(已过滤)' AFTER `un_response`;

ALTER TABLE `kt_gptcms_role_msg` 
MODIFY COLUMN `tip_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '指令内容' AFTER `model_id`,
MODIFY COLUMN `un_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '用户发出内容(未过滤)' AFTER `tip_message`,
MODIFY COLUMN `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '用户发出内容(已过滤)' AFTER `un_message`,
MODIFY COLUMN `un_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '助手回复内容(未过滤)' AFTER `message`,
MODIFY COLUMN `response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '助手回复内容(已过滤)' AFTER `un_response`;

ALTER TABLE `kt_gptcms_create_msg` 
MODIFY COLUMN `un_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '用户发出内容(未过滤)' AFTER `model_id`,
MODIFY COLUMN `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '用户发出内容(已过滤)' AFTER `un_message`,
MODIFY COLUMN `make_message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '最终组合提交接口的内容' AFTER `message`,
MODIFY COLUMN `un_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '助手回复内容(未过滤)' AFTER `make_message`,
MODIFY COLUMN `response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '助手回复内容(已过滤)' AFTER `un_response`;
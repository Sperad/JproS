
DROP TABLE IF EXISTS `dntk_chat_user`;
CREATE TABLE `dntk_chat_user` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
   	`nickname` varchar(32) NOT NULL COMMENT '昵称',
  	`password` varchar(64) NOT NULL COMMENT '密码',
  	`realname` varchar(32) COMMENT '真实姓名',
  	`online` tinyint(3) NOT NULL DEFAULT 0 COMMENT '用户是否在线',
  	`sex` tinyint(3) NOT NULL DEFAULT 0 COMMENT '性别',
  	`birthday` datetime NOT NULL COMMENT '生日',
  	PRIMARY KEY (`id`),
  	UNIQUE KEY `nickname` (`nickname`)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `dntk_chat_group`;
CREATE TABLE `dntk_chat_group`(
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '组ID',
	`group_name` varchar(32) NOT NULL COMMENT '组名称',
	`create_by` int(11) NOT NULL COMMENT '组的所属者',
  	PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `dntk_chat_group_user`;
CREATE TABLE `dntk_chat_group_user`(
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT ,
	`user_id` int(11) NOT NULL COMMENT '好友用户ID',
	`group_id` int(11) NOT NULL COMMENT '组ID',
	PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `dntk_chat_message`;
CREATE TABLE `dntk_chat_message`(
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '聊天信息ID',
	`from_user_id` int(11) NOT NULL COMMENT '聊天的发送者',
	`to_user_id` int(11) NOT NULL COMMENT '聊天的接收者',
	`content` text COMMENT '聊天发送的内容',
	'from_role' tinyint(3) NOT NULl DEFAULT 1 COMMENT '发送者角色: 6 好友, 7游客'
	`status` tinyint(3) NOT NULL DEFAULT 1 COMMENT '消息状态:1发送成功(对方最新),3对方接收()',
	`create_time` datetime COMMENT '聊天的发送时间',
	PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `dntk_chat_visitor`;
CREATE TABLE `dntk_chat_visitor`(
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '游客的ID',
	`nickname` varchar(32) NOT NULL COMMENT '昵称',
	`session_id` varchar(64) NOT NULL COMMENT '游客的Session_id',
	PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `dntk_chat_request_record`;
CREATE TABLE `dntk_chat_request_record`(
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`from_user_id` int(11) NOT NULL COMMENT '发送者的id',
	`to_user_id` int(11) NOT NULL COMMENT '待接收者的id',
	`status` tinyint(3) NOT NULL DEFAULT 1 COMMENT '此条记录的状态,1为发送者已发送(默认),3为接收者已接收(删除),5为接收者拒绝接收',
	PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;
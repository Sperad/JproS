
DROP TABLE IF EXISTS `dntk_chat_user`;
CREATE TABLE `dntk_chat_user` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
   	`nickname` varchar(32) NOT NULL COMMENT '昵称',
  	`password` varchar(64) NOT NULL COMMENT '密码',
  	`realname` varchar(32) COMMENT '真实姓名',
  	`sex` tinyint(1) NOT NULL DEFAULT 0 COMMENT '性别',
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
	`id` int(11) unsigned NOT NULL COMMENT '聊天信息ID',
	`from` int(11) NOT NULL COMMENT '聊天的发送者',
	`to` int(11) NOT null COMMENT '聊天的接收者',
	`content` text COMMENT '聊天发送的内容',
	`create_time` datetime COMMENT '聊天的发送时间',
	PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `dntk_chat_visitor`;
CREATE TABLE `dntk_chat_visitor`(
	`id` int(11) unsigned NOT null COMMENT '游客的ID',
	`nickname` varchar(32) NOT null COMMENT '游客的昵称',
	`session_id` varchar(64) NOT null COMMENT '游客的Session_id',
	PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `dntk_chat_request_record`;
CREATE TABLE `dntk_chat_request_record`(
	`id` int(11) unsigned NOT null AUTO_INCREMENT,
	`from_user_id` int(11) NOT null COMMENT '发送者的id',
	`to_user_id` int(11) NOT null COMMENT '待接收者的id',
	`status` tinyint(3) NOT null DEFAULT 1 COMMENT '此条记录的状态,1为发送者已发送(默认),3为接收者已接收(删除),5为接收者拒绝接收',
	PRIMARY KEY (`id`)
)DEFAULT CHARSET=utf8;
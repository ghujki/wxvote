#2016/4/30
alter table wsg_official_number add access_token varchar(128) null;
alter table wsg_official_number add expire_time int null;
alter table wsg_official_number add original_id varchar(32) NULL;

#2016/5/1
alter table wsg_keywords modify column media_id varchar(64) null;
alter table wsg_user modify column user_open_id varchar(32) not null;
alter table wsg_official_number add ticket varchar(64) null;
alter table wsg_official_number add ticket_expire int null;

#
drop table if EXISTS wsg_vote_properties ;
create table wsg_vote_properties(
  id int PRIMARY KEY AUTO_INCREMENT,
  property_name varchar(32) not null,
  property_code varchar(32) not null,
  value_type int comment '0:数字，1：文字，2:图片，3：datetime',
  default_value varchar(32) null,
  property_group varchar(32) null
);

insert into wsg_vote_properties(property_name, property_code, value_type, property_group)
values('分享标题','share_title',1,"活动分享设置");
insert into wsg_vote_properties(property_name, property_code, value_type, property_group)
values('分享描述','share_desc',1,"活动分享设置");
insert into wsg_vote_properties(property_name, property_code, value_type, property_group)
values('分享图标','share_picurl',2,"活动分享设置");
insert into wsg_vote_properties(property_name, property_code, value_type, property_group)
values('链接地址','url',1,"活动分享设置");

#2016/5/5
alter table wsg_keywords add event varchar(16)  null;
update wsg_keywords set event = 'text';

#2016/05/11
alter table wsg_candidate add status tinyint(1) default 0 comment '0:正常,1:冻结';


#2016/06/02
 DROP TABLE IF EXISTS `wsg_wx_match`;
 CREATE TABLE `wsg_wx_match` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user1_id` int(11) DEFAULT NULL,
`user2_id` int(11) DEFAULT NULL,
`wait_time` int(11) default null,
`match_time` int(11) DEFAULT NULL,
`end_time` int(11) DEFAULT NULL,
`ended_by` int(11) DEFAULT NULL,
`status` tinyint(1) DEFAULT NULL COMMENT '0:wait for match,1:matched ,2:ended',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP TABLE IF EXISTS `wsg_wx_match_message`;
CREATE TABLE `wsg_wx_match_message` (
`record_id` int(11) NOT NULL AUTO_INCREMENT,
`match_id` int(11) DEFAULT NULL,
`from` int(11) DEFAULT NULL,
`to` int(11) DEFAULT NULL,
`content` varchar(1024) DEFAULT NULL,
`time` int(11) DEFAULT NULL,
PRIMARY KEY (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

#2016/06/03  '对于某些用户特殊处理'
alter table wsg_keywords add `username` varchar(128) null ;

#2016/06/07 '推送图文处理'
alter table wsg_material add `synchronized` tinyint(1) default 0 comment '0:未同步,1:已同步';
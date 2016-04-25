/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : wxvote

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-04-25 17:58:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for wsg_account
-- ----------------------------
DROP TABLE IF EXISTS `wsg_account`;
CREATE TABLE `wsg_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lastlogin` int(11) DEFAULT NULL,
  `lastIp` varchar(56) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_account_login_rec
-- ----------------------------
DROP TABLE IF EXISTS `wsg_account_login_rec`;
CREATE TABLE `wsg_account_login_rec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `login_time` int(11) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `account_id` (`account_id`),
  CONSTRAINT `wsg_account_login_rec_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `wsg_account` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_candidate
-- ----------------------------
DROP TABLE IF EXISTS `wsg_candidate`;
CREATE TABLE `wsg_candidate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `QQ` varchar(255) DEFAULT NULL,
  `desc` varchar(526) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `vote_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `candidate_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_candi_gallery
-- ----------------------------
DROP TABLE IF EXISTS `wsg_candi_gallery`;
CREATE TABLE `wsg_candi_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `candi_id` int(11) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `wsg_ci_sessions`;
CREATE TABLE `wsg_ci_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_material
-- ----------------------------
DROP TABLE IF EXISTS `wsg_material`;
CREATE TABLE `wsg_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `media_id` varchar(128) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `thumb_media_id` varchar(128) DEFAULT NULL,
  `show_cover_pic` tinyint(1) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `digest` varchar(255) DEFAULT NULL,
  `content` text,
  `url` varchar(512) DEFAULT NULL,
  `content_source_url` varchar(512) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `picurl` varchar(512) DEFAULT NULL,
  `desc` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_official_number
-- ----------------------------
DROP TABLE IF EXISTS `wsg_official_number`;
CREATE TABLE `wsg_official_number` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` varchar(32) DEFAULT NULL,
  `secretkey` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `app_name` varchar(255) DEFAULT NULL,
  `app_type` tinyint(2) DEFAULT NULL,
  `authorized` tinyint(1) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `qrcode` varchar(1025) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_user
-- ----------------------------
DROP TABLE IF EXISTS `wsg_user`;
CREATE TABLE `wsg_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_open_id` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `sex` enum('2','1','0') DEFAULT NULL COMMENT '0：未知；1：男；2：女',
  `headimgurl` varchar(255) DEFAULT NULL,
  `subscribe_time` int(255) DEFAULT NULL,
  `union_id` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `app_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  CONSTRAINT `wsg_user_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `wsg_official_number` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_user_captcha
-- ----------------------------
DROP TABLE IF EXISTS `wsg_user_captcha`;
CREATE TABLE `wsg_user_captcha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `captcha` varchar(12) DEFAULT NULL,
  `build_time` int(11) DEFAULT NULL,
  `expire_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_user_op_record
-- ----------------------------
DROP TABLE IF EXISTS `wsg_user_op_record`;
CREATE TABLE `wsg_user_op_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `open_id` varchar(128) DEFAULT NULL,
  `token` varchar(128) DEFAULT NULL,
  `op_time` int(11) DEFAULT NULL,
  `url` varchar(512) DEFAULT NULL,
  `active_id` int(11) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`open_id`)
) ENGINE=InnoDB AUTO_INCREMENT=346 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_vote
-- ----------------------------
DROP TABLE IF EXISTS `wsg_vote`;
CREATE TABLE `wsg_vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_name` varchar(255) DEFAULT NULL,
  `signup_start_time` int(11) DEFAULT NULL,
  `signup_end_time` int(11) DEFAULT NULL,
  `vote_start_time` int(11) DEFAULT NULL,
  `vote_end_time` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '1 : 关闭 0：开启',
  `app_id` int(11) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `app_id` (`app_id`),
  CONSTRAINT `wsg_vote_ibfk_1` FOREIGN KEY (`app_id`) REFERENCES `wsg_official_number` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_vote_config
-- ----------------------------
DROP TABLE IF EXISTS `wsg_vote_config`;
CREATE TABLE `wsg_vote_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_id` int(11) DEFAULT NULL,
  `item_code` varchar(128) DEFAULT NULL,
  `item_value` blob,
  `item_name` varchar(255) DEFAULT NULL,
  `rank` smallint(5) DEFAULT NULL,
  `page` tinyint(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vote_id` (`vote_id`),
  CONSTRAINT `wsg_vote_config_ibfk_1` FOREIGN KEY (`vote_id`) REFERENCES `wsg_vote` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_vote_token
-- ----------------------------
DROP TABLE IF EXISTS `wsg_vote_token`;
CREATE TABLE `wsg_vote_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `candi_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `build_time` int(11) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for wsg_voting_record
-- ----------------------------
DROP TABLE IF EXISTS `wsg_voting_record`;
CREATE TABLE `wsg_voting_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `vote_time` int(11) DEFAULT NULL,
  `vote_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `candidate_id` (`candidate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

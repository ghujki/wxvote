/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : wxvote

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2016-04-28 17:44:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `wsg_account`
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
-- Records of wsg_account
-- ----------------------------
INSERT INTO `wsg_account` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1461804607', '127.0.0.1');

-- ----------------------------
-- Table structure for `wsg_account_login_rec`
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of wsg_account_login_rec
-- ----------------------------
INSERT INTO `wsg_account_login_rec` VALUES ('1', '1', '1460617901', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('2', '1', '1460618804', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('3', '1', '1460619020', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('4', '1', '1460619763', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('5', '1', '1460620027', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('6', '1', '1460684113', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('7', '1', '1460942677', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('8', '1', '1460942817', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('9', '1', '1460942862', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('10', '1', '1461308109', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('11', '1', '1461309587', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('12', '1', '1461310296', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('13', '1', '1461310420', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('14', '1', '1461748751', '127.0.0.1');
INSERT INTO `wsg_account_login_rec` VALUES ('15', '1', '1461804607', '127.0.0.1');

-- ----------------------------
-- Table structure for `wsg_candidate`
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
  `enroll_time` int(11) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0' COMMENT '0:正常；1：冻结',
  PRIMARY KEY (`id`),
  KEY `candidate_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of wsg_candidate
-- ----------------------------
INSERT INTO `wsg_candidate` VALUES ('1', '1', '李静', '18974557773', '', null, 'abc', null, '4', '1461638145', '11', '0');
INSERT INTO `wsg_candidate` VALUES ('4', '10', '郭伟', '18974557773', '', null, '标题党去死吧', null, '4', '1461744000', '11', '0');
INSERT INTO `wsg_candidate` VALUES ('8', '11', '大熊', '18974557773', '', null, '给老板投票吧', '/upload/4/1461736940.jpg', '4', '1461722400', '102', '0');

-- ----------------------------
-- Table structure for `wsg_candi_gallery`
-- ----------------------------
DROP TABLE IF EXISTS `wsg_candi_gallery`;
CREATE TABLE `wsg_candi_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `candi_id` int(11) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of wsg_candi_gallery
-- ----------------------------
INSERT INTO `wsg_candi_gallery` VALUES ('10', '4', '/upload/4/1461657377.jpg', null);
INSERT INTO `wsg_candi_gallery` VALUES ('13', '8', '/upload/4/1461731317.jpg', null);
INSERT INTO `wsg_candi_gallery` VALUES ('14', '8', '/upload/4/1461736940.jpg', null);

-- ----------------------------
-- Table structure for `wsg_ci_sessions`
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
-- Records of wsg_ci_sessions
-- ----------------------------
INSERT INTO `wsg_ci_sessions` VALUES ('0005c708eb0f65eb1b4c5c79aac17a5edf2aa87c', '127.0.0.1', '1461045753', '__ci_last_regenerate|i:1461045566;vote|a:10:{s:2:\"id\";s:1:\"4\";s:9:\"vote_name\";s:22:\"第一届投票活动1\";s:17:\"signup_start_time\";s:10:\"1459947600\";s:15:\"signup_end_time\";s:10:\"1460473200\";s:15:\"vote_start_time\";s:10:\"1460995200\";s:13:\"vote_end_time\";s:10:\"1461600000\";s:6:\"status\";s:1:\"0\";s:6:\"app_id\";s:1:\"2\";s:7:\"content\";s:7730:\"<section class=\"rules\"><p>奖品设置</p><p><span style=\"color:#CC33E5;\">参与奖（报名就有现金奖励）</span><br/>1、报名成功后，请添加客服微信&nbsp;<span style=\"background-color:#FFE500;\">mbao86&nbsp;</span>即可领取现金奖励<br/>2、<span style=\"background-color:#FFE500;\">mbao86&nbsp;</span><span id=\"__kindeditor_bookmark_end_14__\"></span>是本次活动客服微信，帮大家处理萌宝大赛的相关问题。。&nbsp;<br/><span style=\"color:#CC33E5;\"><strong>超级一等奖(得票数第一）</strong></span><br/>1、上海范提供的<span style=\"color:#E53333;\">3000元现金</span><br/>2、上海范提供的价值2800元的婴儿面膜10盒<br/>3、上海范提供的价值800元的宝马M8儿童电动车一台<br/>4、上海范提供的价值800元儿童GPS手表一块<br/>5、上海范提供的价值500元超大大白公仔玩具一件<br/>6、上海范提供的语音点读早教机一台<br/>7、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>8、所有奖品上海市内全部包邮&nbsp;<br/><br/><span style=\"color:#CC33E5;\">超级二等奖(得票数第二名)</span><br/>1、上海范提供的<span style=\"color:#E53333;\">2000元现金</span><br/>2、上海范提供的价值1680元的婴儿面膜6盒<br/>3、上海范提供的价值800元宝马M8儿童电动车一台<br/>4、上海范提供的价值500元超大大白公仔玩具一件<br/>5、上海范提供的语音点读早教机一台<br/>6、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级三等奖(得票数第三名)</span><br/>1、上海范提供的<span style=\"color:#E53333;\">1000元现金</span><br/>2、上海范提供的价值840元的婴儿面膜3盒<br/>3、上海范提供的价值800元的宝马M8儿童电动车一台<br/>4、上海范提供的价值300元的超大大白公仔玩具一件<br/>5、上海范提供的语音点读早教机一台<br/>6、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级四等奖（得票数4-10名）</span><br/>1、上海范提供的<span style=\"color:#E53333;\">200元现金</span><br/>2、上海范提供的价值 388元的14寸超酷儿童自行车<br/>3、上海范提供的价值 280元的婴儿面膜1盒<br/>4、上海范提供的兰博基尼儿童遥控赛车一台<br/>5、上海范提供的儿童智能语音点读早教机一台<br/>6、上海范提供的儿童多功能防水手表<br/>7、&nbsp;<span style=\"color:#E53333;\">荣誉证书</span><br/>8、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级五等奖（得票数11-50名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的兰博基尼儿童遥控赛车一台<br/>3、上海范提供的儿童多功能防水手表一只<br/>4、上海范提供的儿童学习文具9件套<br/>5、上海范提供的儿童益智玩具大礼包<br/>6、<span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">明星萌宝（51-200名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的儿童学习文具9件套<br/>3、上海范提供的儿童益智玩具大礼包<br/>4、上海范提供的时尚小黄鸭<br/>5、<span style=\"color:#E53333;\">荣誉证书</span><br/>6、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">阳光宝宝奖（得票数第201-500名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的儿童学习文具9件套<br/>3、上海范提供的儿童益智玩具大礼包<br/>4、上海范提供的时尚小黄鸭<br/>5、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级萌宝（501名-1000名）</span><br/>1、上海范提供的儿童学习文具9件套<br/>2、上海范提供的儿童益智玩具礼包<br/>3、上海范提供的时尚小黄鸭<br/>4、所有奖品上海市内全部包邮&nbsp;<br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongche1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongbiao1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongdabai1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongxiaoche1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongzxc1.jpg\" alt=\"\" class=\"img-responsive\"/></span></p><p>活动规则</p><p>\r\n		【禁止刷票，我们将严格审查刷票选手，一经发现取消活动资格！】 &nbsp;<br/>1、每人每天可投3票，每人每天对同一个萌宝仅限投1票&nbsp;<br/>2、投票时间:2016年4月5至2016年5月4日<br/>3、仅限0-12岁的宝宝参加，报名需提交真实照片，虚假的照片和报名信息视为无效；同一个人不可重复报名；&nbsp;<br/>4、为了防止刷票和机器人参加，仅限生活或居住在上海或在外地从业的上海宝宝参加（原则上仅上海地区的IP用户进行投票），外省的IP超过20%系统将自动剔除外省票数，如果系参赛者主动刷票第一次警告清票，第二次直接取消参赛资格。（拒绝一切刷票行为，您的参与表示认可主办方以上活动规则）<br/>5、活动结束后，5月5日将在“上海范”微信公布名次，得票数前1000名的票数将进行深度核查，票数有异常的主办方有权剔除异常票数&nbsp;<br/>6、本次活动不设置评委，由在“上海范”粉丝投票决定最终名次；本次活动最终解释权归“上海范”平台所有！&nbsp;<br/>客服微信：mbao86大家在投票活动遇到任何问题可以添加我们的客服MM微信反映哦。<br/></p><p>\r\n		大赛郑重申明！！！</p><p>\r\n		针对本次活动，严厉杜绝任何利用网络作弊投票行为，活动过程中如果发现票数异常，我们将不通过参赛者允许进行删除，但是我们会保留相关证据，也希望大家积极举报有关恶意刷票行为，我们将会严肃处理。<br/>&nbsp;<br/>1、参赛者需提供自己的真实照片，若盗用或冒用他人名义照片的参赛行为，本平台将立即予以报警处理。&nbsp;<br/>2、一旦参赛，本活动将认定参赛者提供了自己照片的合法使用授权，不再另行协议。&nbsp;<br/>3、对蓄意诽谤、破坏、干扰活动的恶意言论及行为，本公司将诉诸法律手段维护本次活动的合法权益。&nbsp;<br/>4、我们采用了高科技手段监控投票数据，一切形式的作弊投票，一经发现，情节严重并查证核实后，本平台将有权在不告知当事人的情况下对其参赛资格及投票票数数据进行处理，作弊投票行为之截图、监控数据库将作为证据保留。&nbsp;<br/>5、本次投票系统主要监控投票IP及投票异常等情况，一旦发现无效投票超过20%者，系统将会做出减票及锁定投票，甚至取消参赛资格。<br/>&nbsp;<br/>6、本活动公平、公正、公开，欢迎监督，但对诋毁造谣之言论将追究法律责任！&nbsp;</p></section>\";s:6:\"config\";a:0:{}}official_number|a:8:{s:2:\"id\";s:1:\"2\";s:6:\"app_id\";s:3:\"aaa\";s:9:\"secretkey\";s:4:\"dfdd\";s:5:\"token\";s:32:\"6DCZfpDc6YJhZ3JkGzdfQpw46cYrNtPx\";s:8:\"app_name\";s:15:\"测试公众号\";s:8:\"app_type\";s:1:\"0\";s:10:\"authorized\";s:1:\"1\";s:11:\"create_time\";N;}');
INSERT INTO `wsg_ci_sessions` VALUES ('3ccf775c3e58c8a22d484a98f350951c04e76d45', '127.0.0.1', '1460964401', '__ci_last_regenerate|i:1460964401;');
INSERT INTO `wsg_ci_sessions` VALUES ('5405f614644cd2b329e4c27a020af38a3cd3bc23', '127.0.0.1', '1461045437', '__ci_last_regenerate|i:1461045194;vote|a:10:{s:2:\"id\";s:1:\"4\";s:9:\"vote_name\";s:22:\"第一届投票活动1\";s:17:\"signup_start_time\";s:10:\"1459947600\";s:15:\"signup_end_time\";s:10:\"1460473200\";s:15:\"vote_start_time\";s:10:\"1460995200\";s:13:\"vote_end_time\";s:10:\"1461600000\";s:6:\"status\";s:1:\"0\";s:6:\"app_id\";s:1:\"2\";s:7:\"content\";s:7730:\"<section class=\"rules\"><p>奖品设置</p><p><span style=\"color:#CC33E5;\">参与奖（报名就有现金奖励）</span><br/>1、报名成功后，请添加客服微信&nbsp;<span style=\"background-color:#FFE500;\">mbao86&nbsp;</span>即可领取现金奖励<br/>2、<span style=\"background-color:#FFE500;\">mbao86&nbsp;</span><span id=\"__kindeditor_bookmark_end_14__\"></span>是本次活动客服微信，帮大家处理萌宝大赛的相关问题。。&nbsp;<br/><span style=\"color:#CC33E5;\"><strong>超级一等奖(得票数第一）</strong></span><br/>1、上海范提供的<span style=\"color:#E53333;\">3000元现金</span><br/>2、上海范提供的价值2800元的婴儿面膜10盒<br/>3、上海范提供的价值800元的宝马M8儿童电动车一台<br/>4、上海范提供的价值800元儿童GPS手表一块<br/>5、上海范提供的价值500元超大大白公仔玩具一件<br/>6、上海范提供的语音点读早教机一台<br/>7、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>8、所有奖品上海市内全部包邮&nbsp;<br/><br/><span style=\"color:#CC33E5;\">超级二等奖(得票数第二名)</span><br/>1、上海范提供的<span style=\"color:#E53333;\">2000元现金</span><br/>2、上海范提供的价值1680元的婴儿面膜6盒<br/>3、上海范提供的价值800元宝马M8儿童电动车一台<br/>4、上海范提供的价值500元超大大白公仔玩具一件<br/>5、上海范提供的语音点读早教机一台<br/>6、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级三等奖(得票数第三名)</span><br/>1、上海范提供的<span style=\"color:#E53333;\">1000元现金</span><br/>2、上海范提供的价值840元的婴儿面膜3盒<br/>3、上海范提供的价值800元的宝马M8儿童电动车一台<br/>4、上海范提供的价值300元的超大大白公仔玩具一件<br/>5、上海范提供的语音点读早教机一台<br/>6、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级四等奖（得票数4-10名）</span><br/>1、上海范提供的<span style=\"color:#E53333;\">200元现金</span><br/>2、上海范提供的价值 388元的14寸超酷儿童自行车<br/>3、上海范提供的价值 280元的婴儿面膜1盒<br/>4、上海范提供的兰博基尼儿童遥控赛车一台<br/>5、上海范提供的儿童智能语音点读早教机一台<br/>6、上海范提供的儿童多功能防水手表<br/>7、&nbsp;<span style=\"color:#E53333;\">荣誉证书</span><br/>8、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级五等奖（得票数11-50名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的兰博基尼儿童遥控赛车一台<br/>3、上海范提供的儿童多功能防水手表一只<br/>4、上海范提供的儿童学习文具9件套<br/>5、上海范提供的儿童益智玩具大礼包<br/>6、<span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">明星萌宝（51-200名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的儿童学习文具9件套<br/>3、上海范提供的儿童益智玩具大礼包<br/>4、上海范提供的时尚小黄鸭<br/>5、<span style=\"color:#E53333;\">荣誉证书</span><br/>6、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">阳光宝宝奖（得票数第201-500名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的儿童学习文具9件套<br/>3、上海范提供的儿童益智玩具大礼包<br/>4、上海范提供的时尚小黄鸭<br/>5、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级萌宝（501名-1000名）</span><br/>1、上海范提供的儿童学习文具9件套<br/>2、上海范提供的儿童益智玩具礼包<br/>3、上海范提供的时尚小黄鸭<br/>4、所有奖品上海市内全部包邮&nbsp;<br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongche1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongbiao1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongdabai1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongxiaoche1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongzxc1.jpg\" alt=\"\" class=\"img-responsive\"/></span></p><p>活动规则</p><p>\r\n		【禁止刷票，我们将严格审查刷票选手，一经发现取消活动资格！】 &nbsp;<br/>1、每人每天可投3票，每人每天对同一个萌宝仅限投1票&nbsp;<br/>2、投票时间:2016年4月5至2016年5月4日<br/>3、仅限0-12岁的宝宝参加，报名需提交真实照片，虚假的照片和报名信息视为无效；同一个人不可重复报名；&nbsp;<br/>4、为了防止刷票和机器人参加，仅限生活或居住在上海或在外地从业的上海宝宝参加（原则上仅上海地区的IP用户进行投票），外省的IP超过20%系统将自动剔除外省票数，如果系参赛者主动刷票第一次警告清票，第二次直接取消参赛资格。（拒绝一切刷票行为，您的参与表示认可主办方以上活动规则）<br/>5、活动结束后，5月5日将在“上海范”微信公布名次，得票数前1000名的票数将进行深度核查，票数有异常的主办方有权剔除异常票数&nbsp;<br/>6、本次活动不设置评委，由在“上海范”粉丝投票决定最终名次；本次活动最终解释权归“上海范”平台所有！&nbsp;<br/>客服微信：mbao86大家在投票活动遇到任何问题可以添加我们的客服MM微信反映哦。<br/></p><p>\r\n		大赛郑重申明！！！</p><p>\r\n		针对本次活动，严厉杜绝任何利用网络作弊投票行为，活动过程中如果发现票数异常，我们将不通过参赛者允许进行删除，但是我们会保留相关证据，也希望大家积极举报有关恶意刷票行为，我们将会严肃处理。<br/>&nbsp;<br/>1、参赛者需提供自己的真实照片，若盗用或冒用他人名义照片的参赛行为，本平台将立即予以报警处理。&nbsp;<br/>2、一旦参赛，本活动将认定参赛者提供了自己照片的合法使用授权，不再另行协议。&nbsp;<br/>3、对蓄意诽谤、破坏、干扰活动的恶意言论及行为，本公司将诉诸法律手段维护本次活动的合法权益。&nbsp;<br/>4、我们采用了高科技手段监控投票数据，一切形式的作弊投票，一经发现，情节严重并查证核实后，本平台将有权在不告知当事人的情况下对其参赛资格及投票票数数据进行处理，作弊投票行为之截图、监控数据库将作为证据保留。&nbsp;<br/>5、本次投票系统主要监控投票IP及投票异常等情况，一旦发现无效投票超过20%者，系统将会做出减票及锁定投票，甚至取消参赛资格。<br/>&nbsp;<br/>6、本活动公平、公正、公开，欢迎监督，但对诋毁造谣之言论将追究法律责任！&nbsp;</p></section>\";s:6:\"config\";a:0:{}}official_number|a:8:{s:2:\"id\";s:1:\"2\";s:6:\"app_id\";s:3:\"aaa\";s:9:\"secretkey\";s:4:\"dfdd\";s:5:\"token\";s:32:\"6DCZfpDc6YJhZ3JkGzdfQpw46cYrNtPx\";s:8:\"app_name\";s:15:\"测试公众号\";s:8:\"app_type\";s:1:\"0\";s:10:\"authorized\";s:1:\"1\";s:11:\"create_time\";N;}');
INSERT INTO `wsg_ci_sessions` VALUES ('7fc788a4bed89fada244c772eb6c66439bac3c7a', '127.0.0.1', '1461043746', '__ci_last_regenerate|i:1461043492;vote|a:10:{s:2:\"id\";s:1:\"4\";s:9:\"vote_name\";s:22:\"第一届投票活动1\";s:17:\"signup_start_time\";s:10:\"1459947600\";s:15:\"signup_end_time\";s:10:\"1460473200\";s:15:\"vote_start_time\";s:10:\"1460995200\";s:13:\"vote_end_time\";s:10:\"1461600000\";s:6:\"status\";s:1:\"0\";s:6:\"app_id\";s:1:\"2\";s:7:\"content\";s:7730:\"<section class=\"rules\"><p>奖品设置</p><p><span style=\"color:#CC33E5;\">参与奖（报名就有现金奖励）</span><br/>1、报名成功后，请添加客服微信&nbsp;<span style=\"background-color:#FFE500;\">mbao86&nbsp;</span>即可领取现金奖励<br/>2、<span style=\"background-color:#FFE500;\">mbao86&nbsp;</span><span id=\"__kindeditor_bookmark_end_14__\"></span>是本次活动客服微信，帮大家处理萌宝大赛的相关问题。。&nbsp;<br/><span style=\"color:#CC33E5;\"><strong>超级一等奖(得票数第一）</strong></span><br/>1、上海范提供的<span style=\"color:#E53333;\">3000元现金</span><br/>2、上海范提供的价值2800元的婴儿面膜10盒<br/>3、上海范提供的价值800元的宝马M8儿童电动车一台<br/>4、上海范提供的价值800元儿童GPS手表一块<br/>5、上海范提供的价值500元超大大白公仔玩具一件<br/>6、上海范提供的语音点读早教机一台<br/>7、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>8、所有奖品上海市内全部包邮&nbsp;<br/><br/><span style=\"color:#CC33E5;\">超级二等奖(得票数第二名)</span><br/>1、上海范提供的<span style=\"color:#E53333;\">2000元现金</span><br/>2、上海范提供的价值1680元的婴儿面膜6盒<br/>3、上海范提供的价值800元宝马M8儿童电动车一台<br/>4、上海范提供的价值500元超大大白公仔玩具一件<br/>5、上海范提供的语音点读早教机一台<br/>6、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级三等奖(得票数第三名)</span><br/>1、上海范提供的<span style=\"color:#E53333;\">1000元现金</span><br/>2、上海范提供的价值840元的婴儿面膜3盒<br/>3、上海范提供的价值800元的宝马M8儿童电动车一台<br/>4、上海范提供的价值300元的超大大白公仔玩具一件<br/>5、上海范提供的语音点读早教机一台<br/>6、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级四等奖（得票数4-10名）</span><br/>1、上海范提供的<span style=\"color:#E53333;\">200元现金</span><br/>2、上海范提供的价值 388元的14寸超酷儿童自行车<br/>3、上海范提供的价值 280元的婴儿面膜1盒<br/>4、上海范提供的兰博基尼儿童遥控赛车一台<br/>5、上海范提供的儿童智能语音点读早教机一台<br/>6、上海范提供的儿童多功能防水手表<br/>7、&nbsp;<span style=\"color:#E53333;\">荣誉证书</span><br/>8、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级五等奖（得票数11-50名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的兰博基尼儿童遥控赛车一台<br/>3、上海范提供的儿童多功能防水手表一只<br/>4、上海范提供的儿童学习文具9件套<br/>5、上海范提供的儿童益智玩具大礼包<br/>6、<span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">明星萌宝（51-200名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的儿童学习文具9件套<br/>3、上海范提供的儿童益智玩具大礼包<br/>4、上海范提供的时尚小黄鸭<br/>5、<span style=\"color:#E53333;\">荣誉证书</span><br/>6、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">阳光宝宝奖（得票数第201-500名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的儿童学习文具9件套<br/>3、上海范提供的儿童益智玩具大礼包<br/>4、上海范提供的时尚小黄鸭<br/>5、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级萌宝（501名-1000名）</span><br/>1、上海范提供的儿童学习文具9件套<br/>2、上海范提供的儿童益智玩具礼包<br/>3、上海范提供的时尚小黄鸭<br/>4、所有奖品上海市内全部包邮&nbsp;<br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongche1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongbiao1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongdabai1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongxiaoche1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongzxc1.jpg\" alt=\"\" class=\"img-responsive\"/></span></p><p>活动规则</p><p>\r\n		【禁止刷票，我们将严格审查刷票选手，一经发现取消活动资格！】 &nbsp;<br/>1、每人每天可投3票，每人每天对同一个萌宝仅限投1票&nbsp;<br/>2、投票时间:2016年4月5至2016年5月4日<br/>3、仅限0-12岁的宝宝参加，报名需提交真实照片，虚假的照片和报名信息视为无效；同一个人不可重复报名；&nbsp;<br/>4、为了防止刷票和机器人参加，仅限生活或居住在上海或在外地从业的上海宝宝参加（原则上仅上海地区的IP用户进行投票），外省的IP超过20%系统将自动剔除外省票数，如果系参赛者主动刷票第一次警告清票，第二次直接取消参赛资格。（拒绝一切刷票行为，您的参与表示认可主办方以上活动规则）<br/>5、活动结束后，5月5日将在“上海范”微信公布名次，得票数前1000名的票数将进行深度核查，票数有异常的主办方有权剔除异常票数&nbsp;<br/>6、本次活动不设置评委，由在“上海范”粉丝投票决定最终名次；本次活动最终解释权归“上海范”平台所有！&nbsp;<br/>客服微信：mbao86大家在投票活动遇到任何问题可以添加我们的客服MM微信反映哦。<br/></p><p>\r\n		大赛郑重申明！！！</p><p>\r\n		针对本次活动，严厉杜绝任何利用网络作弊投票行为，活动过程中如果发现票数异常，我们将不通过参赛者允许进行删除，但是我们会保留相关证据，也希望大家积极举报有关恶意刷票行为，我们将会严肃处理。<br/>&nbsp;<br/>1、参赛者需提供自己的真实照片，若盗用或冒用他人名义照片的参赛行为，本平台将立即予以报警处理。&nbsp;<br/>2、一旦参赛，本活动将认定参赛者提供了自己照片的合法使用授权，不再另行协议。&nbsp;<br/>3、对蓄意诽谤、破坏、干扰活动的恶意言论及行为，本公司将诉诸法律手段维护本次活动的合法权益。&nbsp;<br/>4、我们采用了高科技手段监控投票数据，一切形式的作弊投票，一经发现，情节严重并查证核实后，本平台将有权在不告知当事人的情况下对其参赛资格及投票票数数据进行处理，作弊投票行为之截图、监控数据库将作为证据保留。&nbsp;<br/>5、本次投票系统主要监控投票IP及投票异常等情况，一旦发现无效投票超过20%者，系统将会做出减票及锁定投票，甚至取消参赛资格。<br/>&nbsp;<br/>6、本活动公平、公正、公开，欢迎监督，但对诋毁造谣之言论将追究法律责任！&nbsp;</p></section>\";s:6:\"config\";a:0:{}}official_number|a:8:{s:2:\"id\";s:1:\"2\";s:6:\"app_id\";s:3:\"aaa\";s:9:\"secretkey\";s:4:\"dfdd\";s:5:\"token\";s:32:\"6DCZfpDc6YJhZ3JkGzdfQpw46cYrNtPx\";s:8:\"app_name\";s:15:\"测试公众号\";s:8:\"app_type\";s:1:\"0\";s:10:\"authorized\";s:1:\"1\";s:11:\"create_time\";N;}');
INSERT INTO `wsg_ci_sessions` VALUES ('b85a891bf39279c217db582b25e6164f9af99a56', '127.0.0.1', '1461044320', '__ci_last_regenerate|i:1461044317;vote|a:10:{s:2:\"id\";s:1:\"4\";s:9:\"vote_name\";s:22:\"第一届投票活动1\";s:17:\"signup_start_time\";s:10:\"1459947600\";s:15:\"signup_end_time\";s:10:\"1460473200\";s:15:\"vote_start_time\";s:10:\"1460995200\";s:13:\"vote_end_time\";s:10:\"1461600000\";s:6:\"status\";s:1:\"0\";s:6:\"app_id\";s:1:\"2\";s:7:\"content\";s:7730:\"<section class=\"rules\"><p>奖品设置</p><p><span style=\"color:#CC33E5;\">参与奖（报名就有现金奖励）</span><br/>1、报名成功后，请添加客服微信&nbsp;<span style=\"background-color:#FFE500;\">mbao86&nbsp;</span>即可领取现金奖励<br/>2、<span style=\"background-color:#FFE500;\">mbao86&nbsp;</span><span id=\"__kindeditor_bookmark_end_14__\"></span>是本次活动客服微信，帮大家处理萌宝大赛的相关问题。。&nbsp;<br/><span style=\"color:#CC33E5;\"><strong>超级一等奖(得票数第一）</strong></span><br/>1、上海范提供的<span style=\"color:#E53333;\">3000元现金</span><br/>2、上海范提供的价值2800元的婴儿面膜10盒<br/>3、上海范提供的价值800元的宝马M8儿童电动车一台<br/>4、上海范提供的价值800元儿童GPS手表一块<br/>5、上海范提供的价值500元超大大白公仔玩具一件<br/>6、上海范提供的语音点读早教机一台<br/>7、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>8、所有奖品上海市内全部包邮&nbsp;<br/><br/><span style=\"color:#CC33E5;\">超级二等奖(得票数第二名)</span><br/>1、上海范提供的<span style=\"color:#E53333;\">2000元现金</span><br/>2、上海范提供的价值1680元的婴儿面膜6盒<br/>3、上海范提供的价值800元宝马M8儿童电动车一台<br/>4、上海范提供的价值500元超大大白公仔玩具一件<br/>5、上海范提供的语音点读早教机一台<br/>6、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级三等奖(得票数第三名)</span><br/>1、上海范提供的<span style=\"color:#E53333;\">1000元现金</span><br/>2、上海范提供的价值840元的婴儿面膜3盒<br/>3、上海范提供的价值800元的宝马M8儿童电动车一台<br/>4、上海范提供的价值300元的超大大白公仔玩具一件<br/>5、上海范提供的语音点读早教机一台<br/>6、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级四等奖（得票数4-10名）</span><br/>1、上海范提供的<span style=\"color:#E53333;\">200元现金</span><br/>2、上海范提供的价值 388元的14寸超酷儿童自行车<br/>3、上海范提供的价值 280元的婴儿面膜1盒<br/>4、上海范提供的兰博基尼儿童遥控赛车一台<br/>5、上海范提供的儿童智能语音点读早教机一台<br/>6、上海范提供的儿童多功能防水手表<br/>7、&nbsp;<span style=\"color:#E53333;\">荣誉证书</span><br/>8、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级五等奖（得票数11-50名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的兰博基尼儿童遥控赛车一台<br/>3、上海范提供的儿童多功能防水手表一只<br/>4、上海范提供的儿童学习文具9件套<br/>5、上海范提供的儿童益智玩具大礼包<br/>6、<span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">明星萌宝（51-200名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的儿童学习文具9件套<br/>3、上海范提供的儿童益智玩具大礼包<br/>4、上海范提供的时尚小黄鸭<br/>5、<span style=\"color:#E53333;\">荣誉证书</span><br/>6、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">阳光宝宝奖（得票数第201-500名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的儿童学习文具9件套<br/>3、上海范提供的儿童益智玩具大礼包<br/>4、上海范提供的时尚小黄鸭<br/>5、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级萌宝（501名-1000名）</span><br/>1、上海范提供的儿童学习文具9件套<br/>2、上海范提供的儿童益智玩具礼包<br/>3、上海范提供的时尚小黄鸭<br/>4、所有奖品上海市内全部包邮&nbsp;<br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongche1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongbiao1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongdabai1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongxiaoche1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongzxc1.jpg\" alt=\"\" class=\"img-responsive\"/></span></p><p>活动规则</p><p>\r\n		【禁止刷票，我们将严格审查刷票选手，一经发现取消活动资格！】 &nbsp;<br/>1、每人每天可投3票，每人每天对同一个萌宝仅限投1票&nbsp;<br/>2、投票时间:2016年4月5至2016年5月4日<br/>3、仅限0-12岁的宝宝参加，报名需提交真实照片，虚假的照片和报名信息视为无效；同一个人不可重复报名；&nbsp;<br/>4、为了防止刷票和机器人参加，仅限生活或居住在上海或在外地从业的上海宝宝参加（原则上仅上海地区的IP用户进行投票），外省的IP超过20%系统将自动剔除外省票数，如果系参赛者主动刷票第一次警告清票，第二次直接取消参赛资格。（拒绝一切刷票行为，您的参与表示认可主办方以上活动规则）<br/>5、活动结束后，5月5日将在“上海范”微信公布名次，得票数前1000名的票数将进行深度核查，票数有异常的主办方有权剔除异常票数&nbsp;<br/>6、本次活动不设置评委，由在“上海范”粉丝投票决定最终名次；本次活动最终解释权归“上海范”平台所有！&nbsp;<br/>客服微信：mbao86大家在投票活动遇到任何问题可以添加我们的客服MM微信反映哦。<br/></p><p>\r\n		大赛郑重申明！！！</p><p>\r\n		针对本次活动，严厉杜绝任何利用网络作弊投票行为，活动过程中如果发现票数异常，我们将不通过参赛者允许进行删除，但是我们会保留相关证据，也希望大家积极举报有关恶意刷票行为，我们将会严肃处理。<br/>&nbsp;<br/>1、参赛者需提供自己的真实照片，若盗用或冒用他人名义照片的参赛行为，本平台将立即予以报警处理。&nbsp;<br/>2、一旦参赛，本活动将认定参赛者提供了自己照片的合法使用授权，不再另行协议。&nbsp;<br/>3、对蓄意诽谤、破坏、干扰活动的恶意言论及行为，本公司将诉诸法律手段维护本次活动的合法权益。&nbsp;<br/>4、我们采用了高科技手段监控投票数据，一切形式的作弊投票，一经发现，情节严重并查证核实后，本平台将有权在不告知当事人的情况下对其参赛资格及投票票数数据进行处理，作弊投票行为之截图、监控数据库将作为证据保留。&nbsp;<br/>5、本次投票系统主要监控投票IP及投票异常等情况，一旦发现无效投票超过20%者，系统将会做出减票及锁定投票，甚至取消参赛资格。<br/>&nbsp;<br/>6、本活动公平、公正、公开，欢迎监督，但对诋毁造谣之言论将追究法律责任！&nbsp;</p></section>\";s:6:\"config\";a:0:{}}official_number|a:8:{s:2:\"id\";s:1:\"2\";s:6:\"app_id\";s:3:\"aaa\";s:9:\"secretkey\";s:4:\"dfdd\";s:5:\"token\";s:32:\"6DCZfpDc6YJhZ3JkGzdfQpw46cYrNtPx\";s:8:\"app_name\";s:15:\"测试公众号\";s:8:\"app_type\";s:1:\"0\";s:10:\"authorized\";s:1:\"1\";s:11:\"create_time\";N;}');
INSERT INTO `wsg_ci_sessions` VALUES ('f03c0296c2904ee24f7aca826698d1098d6770e6', '127.0.0.1', '1460962848', '__ci_last_regenerate|i:1460962848;');

-- ----------------------------
-- Table structure for `wsg_keywords`
-- ----------------------------
DROP TABLE IF EXISTS `wsg_keywords`;
CREATE TABLE `wsg_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) DEFAULT NULL,
  `keywords` varchar(256) DEFAULT NULL,
  `content` text,
  `type` tinyint(1) DEFAULT '0' COMMENT '0:文本；1：图文',
  `media_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of wsg_keywords
-- ----------------------------
INSERT INTO `wsg_keywords` VALUES ('14', '2', '科比,', '长沙最美妈妈', '1', '1461816847', null);
INSERT INTO `wsg_keywords` VALUES ('15', '2', '宝宝,萌宝,', 'toupiaoba', '0', null, null);
INSERT INTO `wsg_keywords` VALUES ('16', '2', 'TP%', 'resp/VoteForHandle', '2', null, null);

-- ----------------------------
-- Table structure for `wsg_material`
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of wsg_material
-- ----------------------------
INSERT INTO `wsg_material` VALUES ('16', '1', '1461634355', '长沙最美妈妈', null, null, null, null, null, 'http://www.xinggan.net/meinvtupian/201603/17/11753_3.html', null, null, null, 'news', '/upload/wx/1/1461634355.jpg', '标题党去死吧');
INSERT INTO `wsg_material` VALUES ('17', '1', '1461634355', '投票活动', null, null, null, null, null, 'http://www.xinggan.net/meinvtupian/201509/07/2403_3.html', null, null, null, 'news', '/upload/wx/1/1461634371.jpg', 'abc');
INSERT INTO `wsg_material` VALUES ('18', '1', '1461634355', '长沙最美妈妈投票活动开始啦', null, null, null, null, null, 'http://www.xinggan.net/meinvtupian/201603/17/11753_10.html', null, null, null, 'news', '/upload/wx/1/1461634387.jpg', 'aaa');
INSERT INTO `wsg_material` VALUES ('19', '1', '1461815617', 'abc', null, null, null, null, null, 'http://baidu.com', null, null, null, 'news', '/upload/wx/1/1461815617.jpg', 'aaa');
INSERT INTO `wsg_material` VALUES ('20', '1', '1461815617', 'abc', null, null, null, null, null, 'http://www.xinggan.net/meinvtupian/201603/17/11753_10.html', null, null, null, 'news', '/upload/wx/1/1461815636.jpg', 'aaaa');
INSERT INTO `wsg_material` VALUES ('21', '2', '1461816847', '长沙最美妈妈', null, null, null, null, null, 'http://baidu.com', null, null, null, 'news', '/upload/wx/2/1461816847.jpg', '标题党去死吧');
INSERT INTO `wsg_material` VALUES ('22', '2', '1461816847', '投票活动', null, null, null, null, null, 'http://www.xinggan.net/meinvtupian/201603/17/11753_3.html', null, null, null, 'news', '/upload/wx/2/1461816857.jpg', 'aa');

-- ----------------------------
-- Table structure for `wsg_official_number`
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
-- Records of wsg_official_number
-- ----------------------------
INSERT INTO `wsg_official_number` VALUES ('1', 'aaa', 'dfdd', 'tAzGkS78cCe4XkMxGNScksjXkMABirYa', 'adfd', null, '0', null, null);
INSERT INTO `wsg_official_number` VALUES ('2', 'wx5636efafd4348246', 'b868572875f1695f60648cdeed546794', 'Eioa5C5oj3S32qhH', '测试公众号', '0', '1', null, 'http://tp.shangbanba.cn/img/static/qrcode.jpg');

-- ----------------------------
-- Table structure for `wsg_user`
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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of wsg_user
-- ----------------------------
INSERT INTO `wsg_user` VALUES ('10', 'oVvX1s7qWYpHkizTWGelr1rW59NU', 'Peter Kwok', '中国', '湖南', null, '长沙', null, '2', 'http://wx.qlogo.cn/mmopen/LklD7ibyXRJrgFcfmpPcoW8rEOickmy8Ph9ds4pdhqmZ5nicc5BAmDib2xxSzD24QywMsdfD9xL7CibSKSGoeGtGz5g/0', '1439454898', null, 'zh_CN', '2');
INSERT INTO `wsg_user` VALUES ('11', 'oVvX1s-dXk_TYOoAKHk-_6s1_Ye8', '大熊（熊哥）', '中国', '湖南', null, '长沙', null, '2', 'http://wx.qlogo.cn/mmopen/ajNVdqHZLLDFTtc2qkZR8MqZCg70r04FkRS1IS4OJJZLSBBYxibWaNZVia8ribTVAOZdEzUyDaU5GM06X9Y8bBglYF0ZhGWd3MP5fJuSicBRJl8/0', '1460447900', null, 'zh_CN', '2');

-- ----------------------------
-- Table structure for `wsg_user_captcha`
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
-- Records of wsg_user_captcha
-- ----------------------------
INSERT INTO `wsg_user_captcha` VALUES ('1', '1', 'D43E', '1461219630', '1462219630');

-- ----------------------------
-- Table structure for `wsg_user_op_record`
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
) ENGINE=InnoDB AUTO_INCREMENT=533 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of wsg_user_op_record
-- ----------------------------
INSERT INTO `wsg_user_op_record` VALUES ('1', null, null, '1461043617', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('2', null, null, '1461043712', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('3', null, null, '1461043746', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('4', null, null, '1461044320', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('5', null, null, '1461045197', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('6', null, null, '1461045227', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('7', null, null, '1461045276', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('8', null, null, '1461045437', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('9', null, null, '1461045566', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('10', null, null, '1461045588', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('11', null, null, '1461045602', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('12', null, null, '1461045733', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('13', null, null, '1461045740', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('14', null, null, '1461046014', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('15', null, null, '1461046129', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('16', null, null, '1461046315', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('17', null, null, '1461046332', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('18', null, null, '1461046402', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('19', null, null, '1461046509', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('20', null, null, '1461046526', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('21', null, null, '1461048091', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('22', null, null, '1461053292', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('23', null, null, '1461053318', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('24', null, null, '1461053335', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('25', null, null, '1461053369', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('26', null, null, '1461053431', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('27', null, null, '1461053465', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('28', null, null, '1461053545', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('29', null, null, '1461053626', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('30', null, null, '1461053629', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('31', null, null, '1461053666', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('32', null, null, '1461053789', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('33', null, null, '1461053907', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('34', null, null, '1461053910', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=17655', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('35', null, null, '1461053931', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('36', null, null, '1461054062', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('37', null, null, '1461054153', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('38', null, null, '1461054161', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('39', null, null, '1461054234', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('40', null, null, '1461054236', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('41', null, null, '1461054249', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('42', null, null, '1461054251', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('43', null, null, '1461054263', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('44', null, null, '1461054264', '/index.php/voteController/vote?id=2&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('45', null, null, '1461054268', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('46', null, null, '1461054420', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('47', null, null, '1461054422', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('48', null, null, '1461054657', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('49', null, null, '1461054659', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('50', null, null, '1461054704', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('51', null, null, '1461054706', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('52', null, null, '1461054733', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('53', null, null, '1461054735', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('54', null, null, '1461054738', '/index.php/voteController/vote?id=2&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('55', null, null, '1461054748', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('56', null, null, '1461054754', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('57', null, null, '1461055009', '/index.php/voteController/vote?id=2&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('58', null, null, '1461055015', '/index.php/voteController/vote?id=2&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('59', null, null, '1461055099', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('60', null, null, '1461055101', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('61', null, null, '1461055103', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('62', null, null, '1461055154', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('63', null, null, '1461055409', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('64', null, null, '1461055413', '/index.php/voteController/vote?id=2&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('65', null, null, '1461055428', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('66', null, null, '1461056294', '/index.php/VoteController/index?vote_id=4&XDEBUT_SESSION_START=11825', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('67', null, null, '1461114169', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('68', null, null, '1461114292', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('69', null, null, '1461114755', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('70', null, null, '1461114755', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('71', null, null, '1461114815', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('72', null, null, '1461114855', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('73', null, null, '1461114886', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('74', null, null, '1461115075', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('75', null, null, '1461115428', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('76', null, null, '1461115430', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('77', null, null, '1461115454', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('78', null, null, '1461115456', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('79', null, null, '1461116333', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('80', null, null, '1461116658', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('81', null, null, '1461116950', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('82', null, null, '1461117120', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('83', null, null, '1461117219', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('84', null, null, '1461118220', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('85', null, null, '1461118235', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('86', null, null, '1461119556', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('87', null, null, '1461119571', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('88', null, null, '1461119604', '/index.php/VoteController/view?vote_id=4&candi_id=1&XDEBUT_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('89', null, null, '1461119625', '/index.php/VoteController/view?vote_id=4&candi_id=1&XDEBUT_START_SESSION=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('90', null, null, '1461119638', '/index.php/VoteController/view?vote_id=4&candi_id=1&XDEBUT_START_SESSION=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('91', null, null, '1461119647', '/index.php/VoteController/view?vote_id=4&candi_id=1&XDEBUT_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('92', null, null, '1461119668', '/index.php/VoteController/view?vote_id=4&candi_id=1&XDEBUG_SESSION_START=14330', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('93', null, null, '1461119916', '/index.php/VoteController/view?vote_id=4&candi_id=1&XDEBUG_SESSION_START=14330', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('94', null, null, '1461119981', '/index.php/VoteController/view?vote_id=4&candi_id=1&XDEBUG_SESSION_START=14330', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('95', null, null, '1461120016', '/index.php/VoteController/view?vote_id=4&candi_id=1&XDEBUG_SESSION_START=14330', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('96', null, null, '1461120092', '/index.php/VoteController/view?vote_id=4&candi_id=1&XDEBUG_SESSION_START=14330', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('97', null, null, '1461134659', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('98', null, null, '1461134661', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('99', null, null, '1461134765', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('100', null, null, '1461136654', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('101', null, null, '1461136753', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('102', null, null, '1461136754', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('103', null, null, '1461136780', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('104', null, null, '1461136827', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('105', null, null, '1461136893', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('106', null, null, '1461136937', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('107', null, null, '1461136963', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('108', null, null, '1461137024', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('109', null, null, '1461137520', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('110', null, null, '1461139931', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('111', null, null, '1461139933', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('112', null, null, '1461139934', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('113', null, null, '1461142757', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('114', null, null, '1461142830', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('115', null, null, '1461142903', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('116', null, null, '1461142992', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('117', null, null, '1461143455', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('118', null, null, '1461143616', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('119', null, null, '1461143791', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('120', null, null, '1461143850', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('121', null, null, '1461143857', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('122', null, null, '1461143860', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('123', null, null, '1461143871', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('124', null, null, '1461144007', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('125', null, null, '1461144046', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('126', null, null, '1461144174', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('127', null, null, '1461144190', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('128', null, null, '1461144449', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('129', null, null, '1461144524', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('130', null, null, '1461144542', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('131', null, null, '1461144566', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('132', null, null, '1461145798', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('133', null, null, '1461145800', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('134', null, null, '1461145882', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('135', null, null, '1461200960', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('136', null, null, '1461200963', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('137', null, null, '1461202394', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('138', null, null, '1461202417', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('139', null, null, '1461202517', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('140', null, null, '1461202652', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('141', null, null, '1461202849', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('142', null, null, '1461202923', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('143', null, null, '1461202974', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('144', null, null, '1461203288', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('145', null, null, '1461203522', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('146', null, null, '1461203766', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('147', null, null, '1461203872', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('148', null, null, '1461203885', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('149', null, null, '1461203936', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('150', null, null, '1461204010', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('151', null, null, '1461204070', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('152', null, null, '1461204203', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('153', null, null, '1461204325', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('154', null, null, '1461204371', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('155', null, null, '1461204376', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('156', null, null, '1461204408', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('157', null, null, '1461204414', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('158', null, null, '1461204532', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('159', null, null, '1461204536', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('160', null, null, '1461204576', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('161', null, null, '1461204580', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('162', null, null, '1461204701', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('163', null, null, '1461204704', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('164', null, null, '1461204769', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('165', null, null, '1461204772', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('166', null, null, '1461204841', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('167', null, null, '1461204844', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('168', null, null, '1461204916', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('169', null, null, '1461204922', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('170', null, null, '1461204969', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('171', null, null, '1461204972', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('172', null, null, '1461204992', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('173', null, null, '1461204996', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('174', null, null, '1461205172', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('175', null, null, '1461205177', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('176', null, null, '1461205247', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('177', null, null, '1461205255', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('178', null, null, '1461205303', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('179', null, null, '1461205306', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('180', null, null, '1461205335', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('181', null, null, '1461205338', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('182', null, null, '1461205379', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('183', null, null, '1461205382', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('184', null, null, '1461205412', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('185', null, null, '1461205416', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('186', null, null, '1461205505', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('187', null, null, '1461205508', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('188', null, null, '1461206004', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('189', null, null, '1461206008', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('190', null, null, '1461206067', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('191', null, null, '1461206071', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('192', null, null, '1461206173', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('193', null, null, '1461206176', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('194', null, null, '1461206207', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('195', null, null, '1461206216', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('196', null, null, '1461206316', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('197', null, null, '1461206323', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('198', null, null, '1461206362', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('199', null, null, '1461206384', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('200', null, null, '1461206418', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('201', null, null, '1461206424', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('202', null, null, '1461206663', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('203', null, null, '1461206669', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('204', null, null, '1461206740', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('205', null, null, '1461206746', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('206', null, null, '1461206787', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('207', null, null, '1461206792', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('208', null, null, '1461207331', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('209', null, null, '1461207335', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('210', null, null, '1461207344', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('211', null, null, '1461207428', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('212', null, null, '1461207509', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('213', null, null, '1461207647', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('214', null, null, '1461207652', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('215', null, null, '1461207655', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('216', null, null, '1461207658', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('217', null, null, '1461207660', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('218', null, null, '1461207712', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('219', null, null, '1461207785', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('220', null, null, '1461207798', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('221', null, null, '1461207809', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('222', null, null, '1461207813', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('223', null, null, '1461207816', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('224', null, null, '1461207886', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=12016', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('225', null, null, '1461207889', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('226', null, null, '1461207892', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('227', null, null, '1461207895', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('228', null, null, '1461207897', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('229', null, null, '1461207925', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('230', null, null, '1461210106', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('231', null, null, '1461210110', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('232', null, null, '1461210123', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('233', null, null, '1461210126', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('234', null, null, '1461210129', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('235', null, null, '1461210145', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('236', null, null, '1461210235', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('237', null, null, '1461210303', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('238', null, null, '1461210331', '/index.php/VoteController/view?vote_id=4&candi_id=1', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('239', null, null, '1461210338', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('240', null, null, '1461210615', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('241', null, null, '1461210744', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('242', null, null, '1461210869', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('243', null, null, '1461212037', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('244', null, null, '1461213053', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('245', null, null, '1461213532', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('246', null, null, '1461213541', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('247', null, null, '1461213545', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('248', null, null, '1461213549', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('249', null, null, '1461216643', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('250', null, null, '1461216883', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('251', null, null, '1461216979', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('252', null, null, '1461217015', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('253', null, null, '1461217842', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('254', null, null, '1461218097', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('255', null, null, '1461218818', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('256', null, null, '1461218821', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=ddd', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('257', null, null, '1461218861', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('258', null, null, '1461218863', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=ddd', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('259', null, null, '1461218883', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=ddd', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('260', null, null, '1461218911', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('261', null, null, '1461218914', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=ddd', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('262', null, null, '1461218917', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=ddd', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('263', null, null, '1461219141', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=ddd', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('264', null, null, '1461219143', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=ddd', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('265', null, null, '1461219157', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=ddd', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('266', null, null, '1461219531', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('267', null, null, '1461219542', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=ddd', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('268', null, null, '1461219547', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('269', null, null, '1461219655', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('270', null, null, '1461219662', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('271', null, null, '1461219832', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=11456', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('272', null, null, '1461219846', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('273', null, null, '1461219880', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('274', null, null, '1461219901', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('275', null, null, '1461219992', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('276', null, null, '1461220011', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('277', null, null, '1461220106', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=11456', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('278', null, null, '1461220118', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('279', null, null, '1461220131', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('280', null, null, '1461220272', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=11456', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('281', null, null, '1461220273', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=11456', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('282', null, null, '1461220282', '/index.php/VoteController/enroll?vote_id=4&XDEBUG_SESSION_START=11456', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('283', null, null, '1461220295', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D34E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('284', null, null, '1461220309', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('285', null, null, '1461220317', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('286', null, null, '1461222206', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('287', null, null, '1461222210', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('288', null, null, '1461222229', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('289', null, null, '1461222237', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('290', null, null, '1461224472', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('291', null, null, '1461224686', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('292', null, null, '1461224722', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('293', null, null, '1461224722', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('294', null, null, '1461224724', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('295', null, null, '1461224735', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('296', null, null, '1461224737', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('297', null, null, '1461224784', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('298', null, null, '1461224785', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('299', null, null, '1461224794', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('300', null, null, '1461224796', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('301', null, null, '1461224802', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('302', null, null, '1461224816', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('303', null, null, '1461224829', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('304', null, null, '1461224854', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('305', null, null, '1461224856', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('306', null, null, '1461224883', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('307', null, null, '1461224886', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('308', null, null, '1461224916', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('309', null, null, '1461224928', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('310', null, null, '1461224931', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('311', null, null, '1461224935', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('312', null, null, '1461224988', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('313', null, null, '1461224998', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('314', null, null, '1461225000', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('315', null, null, '1461225005', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('316', null, null, '1461225007', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('317', null, null, '1461225009', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('318', null, null, '1461225209', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('319', null, null, '1461225210', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('320', null, null, '1461225216', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=D43E', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('321', null, null, '1461225218', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('322', null, null, '1461225224', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('323', null, null, '1461225227', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('324', null, null, '1461225332', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('325', null, null, '1461225356', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('326', null, null, '1461225416', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('327', null, null, '1461225428', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('328', null, null, '1461304624', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('329', null, null, '1461304657', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('330', null, null, '1461304752', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('331', null, null, '1461304845', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('332', null, null, '1461305010', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('333', null, null, '1461305128', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('334', null, null, '1461305189', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('335', null, null, '1461305212', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('336', null, null, '1461305259', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('337', null, null, '1461305426', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('338', null, null, '1461305449', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('339', null, null, '1461305457', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('340', null, null, '1461306440', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('341', null, null, '1461306663', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('342', null, null, '1461306853', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('343', null, null, '1461306898', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('344', null, null, '1461306922', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('345', null, null, '1461306941', '/index.php/VoteController/join?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('346', null, null, '1461635870', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('347', null, null, '1461636105', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('348', null, null, '1461641894', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('349', null, null, '1461642016', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('350', null, null, '1461653396', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('351', null, null, '1461653464', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('352', null, null, '1461653649', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('353', null, null, '1461653666', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('354', null, null, '1461653728', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('355', null, null, '1461654098', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('356', null, null, '1461654118', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('357', null, null, '1461654253', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('358', null, null, '1461654265', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('359', null, null, '1461654268', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('360', null, null, '1461655910', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('361', null, null, '1461655913', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('362', null, null, '1461655980', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('363', null, null, '1461655983', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('364', null, null, '1461655987', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('365', null, null, '1461656421', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('366', null, null, '1461656425', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('367', null, null, '1461656522', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('368', null, null, '1461656525', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('369', null, null, '1461656938', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('370', null, null, '1461656941', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('371', null, null, '1461657374', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('372', null, null, '1461657377', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('373', null, null, '1461725546', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('374', null, null, '1461725614', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('375', null, null, '1461731311', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('376', null, null, '1461731317', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('377', null, null, '1461731576', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('378', null, null, '1461733001', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('379', null, null, '1461733044', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('380', null, null, '1461733444', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('381', null, null, '1461733498', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('382', null, null, '1461733965', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('383', null, null, '1461736772', '/index.php/voteController/vote?id=8&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('384', null, null, '1461736785', '/index.php/voteController/vote?id=8&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('385', null, null, '1461736791', '/index.php/VoteController/view?vote_id=4&candi_id=8', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('386', null, null, '1461736940', '/index.php/VoteController/upload?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('387', null, null, '1461736951', '/index.php/VoteController/view?vote_id=4&candi_id=8', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('388', null, null, '1461737242', '/index.php/VoteController/view?vote_id=4&candi_id=8', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('389', null, null, '1461737259', '/index.php/VoteController/view?vote_id=4&candi_id=8', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('390', null, null, '1461738063', '/index.php/VoteController/view?vote_id=4&candi_id=8', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('391', null, null, '1461738088', '/index.php/VoteController/view?vote_id=4&candi_id=8', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('392', null, null, '1461738121', '/index.php/VoteController/view?vote_id=4&candi_id=8&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('393', null, null, '1461738155', '/index.php/VoteController/view?vote_id=4&candi_id=8&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('394', null, null, '1461738169', '/index.php/VoteController/view?vote_id=4&candi_id=8&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('395', null, null, '1461738227', '/index.php/VoteController/view?vote_id=4&candi_id=8&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('396', null, null, '1461738363', '/index.php/VoteController/index?vote_id=4&XDEBUG_SESSION_START=14824', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('397', null, null, '1461738372', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('398', null, null, '1461738389', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('399', null, null, '1461738713', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('400', null, null, '1461738922', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('401', null, null, '1461738951', '/index.php/VoteController/index/1?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('402', null, null, '1461738978', '/index.php/VoteController/index/1?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('403', null, null, '1461738980', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('404', null, null, '1461738982', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('405', null, null, '1461739025', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('406', null, null, '1461739027', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('407', null, null, '1461739045', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('408', null, null, '1461739053', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('409', null, null, '1461739057', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('410', null, null, '1461739108', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('411', null, null, '1461739109', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('412', null, null, '1461739128', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('413', null, null, '1461739177', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('414', null, null, '1461739306', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('415', null, null, '1461739400', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('416', null, null, '1461739451', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('417', null, null, '1461739496', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('418', null, null, '1461739664', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('419', null, null, '1461739676', '/index.php/VoteController/index/1?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('420', null, null, '1461739687', '/index.php/VoteController/index/2?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('421', null, null, '1461739736', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('422', null, null, '1461739762', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('423', null, null, '1461739951', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('424', null, null, '1461740177', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('425', null, null, '1461740220', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('426', null, null, '1461740221', '/index.php/VoteController/index/?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('427', null, null, '1461740223', '/index.php/VoteController/index?vote_id=4&orderby=enroll_time&sort=desc', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('428', null, null, '1461740235', '/index.php/VoteController/index?vote_id=4&orderby=count&sort=desc', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('429', null, null, '1461740813', '/index.php/VoteController/index?vote_id=4&orderby=count&sort=desc', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('430', null, null, '1461740822', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('431', null, null, '1461741133', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('432', null, null, '1461741180', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('433', null, null, '1461741183', '/index.php/VoteController/view?vote_id=4&candi_id=8', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('434', null, null, '1461741187', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('435', null, null, '1461741189', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('436', null, null, '1461741192', '/index.php/VoteController/index?vote_id=4&orderby=top', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('437', null, null, '1461741326', '/index.php/VoteController/index?vote_id=4&orderby=top', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('438', null, null, '1461741615', '/index.php/VoteController/index?vote_id=4&orderby=enroll_time', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('439', null, null, '1461741638', '/index.php/VoteController/index?vote_id=4%20or%201=1%20&orderby=enroll_time', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('440', null, null, '1461741691', '/index.php/VoteController/index?vote_id=4%20or%201=1%20&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('441', null, null, '1461741774', '/index.php/VoteController/index?vote_id=4%20or%201=1%20&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('442', null, null, '1461741843', '/index.php/VoteController/index?vote_id=4%20or%201=1%20&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('443', null, null, '1461741846', '/index.php/VoteController/index?vote_id=4%20or%201=1%20&orderby=top', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('444', null, null, '1461741982', '/index.php/VoteController/index?vote_id=4%20or%201=1%20&orderby=top', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('445', null, null, '1461741984', '/index.php/VoteController/index?vote_id=4%20or%201=1%20&orderby=enroll_time', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('446', null, null, '1461742515', '/index.php/VoteController/index?vote_id=4%20or%201=1%20&orderby=enroll_time', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('447', null, null, '1461742521', '/index.php/VoteController/search?keywords=%E6%9D%8E&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('448', null, null, '1461742538', '/index.php/VoteController/search?keywords=%E5%A4%A7&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('449', null, null, '1461742554', '/index.php/VoteController/search?keywords=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('450', null, null, '1461742618', '/index.php/VoteController/search?keywords=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('451', null, null, '1461742694', '/index.php/VoteController/search?keywords=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('452', null, null, '1461742722', '/index.php/VoteController/search?keywords=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('453', null, null, '1461742723', '/index.php/VoteController/search?keywords=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('454', null, null, '1461742725', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('455', null, null, '1461742748', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('456', null, null, '1461742750', '/index.php/VoteController/search?keywords=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('457', null, null, '1461742751', '/index.php/VoteController/search?keywords=%E5%A4%A7&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('458', null, null, '1461742753', '/index.php/VoteController/index?vote_id=4&orderby=enroll_time', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('459', null, null, '1461742769', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('460', null, null, '1461742794', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('461', null, null, '1461742805', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('462', null, null, '1461742808', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('463', null, null, '1461742811', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('464', null, null, '1461742837', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('465', null, null, '1461742839', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('466', null, null, '1461742844', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('467', null, null, '1461744313', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('468', null, null, '1461744316', '/index.php/VoteController/my?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('469', null, null, '1461745145', '/index.php/VoteController/my?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('470', null, null, '1461745199', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('471', null, null, '1461745355', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('472', null, null, '1461745400', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('473', null, null, '1461745434', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('474', null, null, '1461745445', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('475', null, null, '1461745479', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('476', null, null, '1461745558', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('477', null, null, '1461745614', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('478', null, null, '1461745615', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('479', null, null, '1461745649', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('480', null, null, '1461745743', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('481', null, null, '1461745836', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('482', null, null, '1461745851', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('483', null, null, '1461746042', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('484', null, null, '1461746071', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('485', null, null, '1461746150', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('486', null, null, '1461746161', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('487', null, null, '1461746179', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('488', null, null, '1461746202', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('489', null, null, '1461747238', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('490', null, null, '1461747300', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('491', null, null, '1461747562', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('492', null, null, '1461747596', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('493', null, null, '1461747675', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('494', null, null, '1461747692', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('495', null, null, '1461747699', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('496', null, null, '1461747824', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('497', null, null, '1461747825', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('498', null, null, '1461747856', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('499', null, null, '1461747965', '/index.php/VoteController/index?vote_id=4&orderby=enroll_time', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('500', null, null, '1461748117', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('501', null, null, '1461748121', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('502', null, null, '1461748123', '/index.php/VoteController/my?vote_id=4&XDEBUG_SESSION_START=16719', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('503', null, null, '1461748126', '/index.php/VoteController/index?vote_id=4&orderby=enroll_time', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('504', null, null, '1461748128', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('505', null, null, '1461748132', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('506', null, null, '1461748135', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('507', null, null, '1461748137', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('508', null, null, '1461748139', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('509', null, null, '1461748141', '/index.php/VoteController/checkCaptcha?vote_id=4&captcha=', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('510', null, null, '1461748180', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('511', null, null, '1461748186', '/index.php/VoteController/my?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('512', null, null, '1461748227', '/index.php/VoteController/my?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('513', null, null, '1461748234', '/index.php/VoteController/index?vote_id=4&orderby=enroll_time', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('514', null, null, '1461748239', '/index.php/VoteController/index?vote_id=4&orderby=top', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('515', null, null, '1461822514', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('516', null, null, '1461822520', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('517', null, null, '1461822535', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('518', null, null, '1461823814', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('519', null, null, '1461823816', '/index.php/VoteController/enroll?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('520', null, null, '1461823823', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('521', null, null, '1461823825', '/index.php/voteController/vote?id=4&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('522', null, null, '1461824084', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('523', null, null, '1461824124', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('524', null, null, '1461824153', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('525', null, null, '1461824179', '/index.php/voteController/vote?id=8&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('526', null, null, '1461824566', '/index.php/VoteController/index?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('527', null, null, '1461824567', '/index.php/voteController/vote?id=4&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('528', null, null, '1461824576', '/index.php/voteController/vote?id=1&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('529', null, null, '1461824578', '/index.php/voteController/vote?id=8&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('530', null, null, '1461824580', '/index.php/voteController/vote?id=4&vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('531', null, null, '1461824584', '/index.php/VoteController/my?vote_id=4', '4', 'vote');
INSERT INTO `wsg_user_op_record` VALUES ('532', null, null, '1461824588', '/index.php/VoteController/index?vote_id=4&orderby=count', '4', 'vote');

-- ----------------------------
-- Table structure for `wsg_vote`
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
-- Records of wsg_vote
-- ----------------------------
INSERT INTO `wsg_vote` VALUES ('4', '第一届投票活动1', '1459947600', '1460473200', '1460995200', '1461600000', '0', '2', '<section class=\"rules\"><p>奖品设置</p><p><span style=\"color:#CC33E5;\">参与奖（报名就有现金奖励）</span><br/>1、报名成功后，请添加客服微信&nbsp;<span style=\"background-color:#FFE500;\">mbao86&nbsp;</span>即可领取现金奖励<br/>2、<span style=\"background-color:#FFE500;\">mbao86&nbsp;</span><span id=\"__kindeditor_bookmark_end_14__\"></span>是本次活动客服微信，帮大家处理萌宝大赛的相关问题。。&nbsp;<br/><span style=\"color:#CC33E5;\"><strong>超级一等奖(得票数第一）</strong></span><br/>1、上海范提供的<span style=\"color:#E53333;\">3000元现金</span><br/>2、上海范提供的价值2800元的婴儿面膜10盒<br/>3、上海范提供的价值800元的宝马M8儿童电动车一台<br/>4、上海范提供的价值800元儿童GPS手表一块<br/>5、上海范提供的价值500元超大大白公仔玩具一件<br/>6、上海范提供的语音点读早教机一台<br/>7、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>8、所有奖品上海市内全部包邮&nbsp;<br/><br/><span style=\"color:#CC33E5;\">超级二等奖(得票数第二名)</span><br/>1、上海范提供的<span style=\"color:#E53333;\">2000元现金</span><br/>2、上海范提供的价值1680元的婴儿面膜6盒<br/>3、上海范提供的价值800元宝马M8儿童电动车一台<br/>4、上海范提供的价值500元超大大白公仔玩具一件<br/>5、上海范提供的语音点读早教机一台<br/>6、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级三等奖(得票数第三名)</span><br/>1、上海范提供的<span style=\"color:#E53333;\">1000元现金</span><br/>2、上海范提供的价值840元的婴儿面膜3盒<br/>3、上海范提供的价值800元的宝马M8儿童电动车一台<br/>4、上海范提供的价值300元的超大大白公仔玩具一件<br/>5、上海范提供的语音点读早教机一台<br/>6、上海范提供的<span style=\"color:#E53333;\">水晶奖杯+</span><span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级四等奖（得票数4-10名）</span><br/>1、上海范提供的<span style=\"color:#E53333;\">200元现金</span><br/>2、上海范提供的价值 388元的14寸超酷儿童自行车<br/>3、上海范提供的价值 280元的婴儿面膜1盒<br/>4、上海范提供的兰博基尼儿童遥控赛车一台<br/>5、上海范提供的儿童智能语音点读早教机一台<br/>6、上海范提供的儿童多功能防水手表<br/>7、&nbsp;<span style=\"color:#E53333;\">荣誉证书</span><br/>8、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级五等奖（得票数11-50名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的兰博基尼儿童遥控赛车一台<br/>3、上海范提供的儿童多功能防水手表一只<br/>4、上海范提供的儿童学习文具9件套<br/>5、上海范提供的儿童益智玩具大礼包<br/>6、<span style=\"color:#E53333;\">荣誉证书</span><br/>7、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">明星萌宝（51-200名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的儿童学习文具9件套<br/>3、上海范提供的儿童益智玩具大礼包<br/>4、上海范提供的时尚小黄鸭<br/>5、<span style=\"color:#E53333;\">荣誉证书</span><br/>6、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">阳光宝宝奖（得票数第201-500名）</span><br/>1、上海范提供的儿童智能语音点读早教机一台<br/>2、上海范提供的儿童学习文具9件套<br/>3、上海范提供的儿童益智玩具大礼包<br/>4、上海范提供的时尚小黄鸭<br/>5、所有奖品上海市内全部包邮<br/><span style=\"color:#CC33E5;\">超级萌宝（501名-1000名）</span><br/>1、上海范提供的儿童学习文具9件套<br/>2、上海范提供的儿童益智玩具礼包<br/>3、上海范提供的时尚小黄鸭<br/>4、所有奖品上海市内全部包邮&nbsp;<br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongche1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongbiao1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongdabai1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongxiaoche1.jpg\" alt=\"\" class=\"img-responsive\"/></span><br/><span style=\"background-color:#FFE500;\"><img src=\"http://imgmd.51mvnote.com/huodongtu/huodongzxc1.jpg\" alt=\"\" class=\"img-responsive\"/></span></p><p>活动规则</p><p>\r\n		【禁止刷票，我们将严格审查刷票选手，一经发现取消活动资格！】 &nbsp;<br/>1、每人每天可投3票，每人每天对同一个萌宝仅限投1票&nbsp;<br/>2、投票时间:2016年4月5至2016年5月4日<br/>3、仅限0-12岁的宝宝参加，报名需提交真实照片，虚假的照片和报名信息视为无效；同一个人不可重复报名；&nbsp;<br/>4、为了防止刷票和机器人参加，仅限生活或居住在上海或在外地从业的上海宝宝参加（原则上仅上海地区的IP用户进行投票），外省的IP超过20%系统将自动剔除外省票数，如果系参赛者主动刷票第一次警告清票，第二次直接取消参赛资格。（拒绝一切刷票行为，您的参与表示认可主办方以上活动规则）<br/>5、活动结束后，5月5日将在“上海范”微信公布名次，得票数前1000名的票数将进行深度核查，票数有异常的主办方有权剔除异常票数&nbsp;<br/>6、本次活动不设置评委，由在“上海范”粉丝投票决定最终名次；本次活动最终解释权归“上海范”平台所有！&nbsp;<br/>客服微信：mbao86大家在投票活动遇到任何问题可以添加我们的客服MM微信反映哦。<br/></p><p>\r\n		大赛郑重申明！！！</p><p>\r\n		针对本次活动，严厉杜绝任何利用网络作弊投票行为，活动过程中如果发现票数异常，我们将不通过参赛者允许进行删除，但是我们会保留相关证据，也希望大家积极举报有关恶意刷票行为，我们将会严肃处理。<br/>&nbsp;<br/>1、参赛者需提供自己的真实照片，若盗用或冒用他人名义照片的参赛行为，本平台将立即予以报警处理。&nbsp;<br/>2、一旦参赛，本活动将认定参赛者提供了自己照片的合法使用授权，不再另行协议。&nbsp;<br/>3、对蓄意诽谤、破坏、干扰活动的恶意言论及行为，本公司将诉诸法律手段维护本次活动的合法权益。&nbsp;<br/>4、我们采用了高科技手段监控投票数据，一切形式的作弊投票，一经发现，情节严重并查证核实后，本平台将有权在不告知当事人的情况下对其参赛资格及投票票数数据进行处理，作弊投票行为之截图、监控数据库将作为证据保留。&nbsp;<br/>5、本次投票系统主要监控投票IP及投票异常等情况，一旦发现无效投票超过20%者，系统将会做出减票及锁定投票，甚至取消参赛资格。<br/>&nbsp;<br/>6、本活动公平、公正、公开，欢迎监督，但对诋毁造谣之言论将追究法律责任！&nbsp;</p></section>');

-- ----------------------------
-- Table structure for `wsg_vote_config`
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
-- Records of wsg_vote_config
-- ----------------------------

-- ----------------------------
-- Table structure for `wsg_vote_token`
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
-- Records of wsg_vote_token
-- ----------------------------

-- ----------------------------
-- Table structure for `wsg_voting_record`
-- ----------------------------
DROP TABLE IF EXISTS `wsg_voting_record`;
CREATE TABLE `wsg_voting_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `vote_time` int(11) DEFAULT NULL,
  `vote_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `ip` varchar(128) DEFAULT NULL,
  `source` tinyint(1) DEFAULT NULL COMMENT '0:网页;1:分享;2:公众号',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `candidate_id` (`candidate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of wsg_voting_record
-- ----------------------------
INSERT INTO `wsg_voting_record` VALUES ('1', '2', '1', null, '4', null, null, null);
INSERT INTO `wsg_voting_record` VALUES ('2', '3', '1', null, '4', null, null, null);

/*
Navicat MySQL Data Transfer

Source Server         : 测试218
Source Server Version : 50560
Source Host           : 192.168.8.218:3306
Source Database       : c1_hxxiaoketang

Target Server Type    : MYSQL
Target Server Version : 50560
File Encoding         : 65001

Date: 2020-07-09 15:52:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for action
-- ----------------------------
DROP TABLE IF EXISTS `action`;
CREATE TABLE `action` (
  `action_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '动作编号',
  `action_name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `action` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '动作',
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='动作表';

-- ----------------------------
-- Records of action
-- ----------------------------
INSERT INTO `action` VALUES ('1', '查看', 'index');
INSERT INTO `action` VALUES ('2', '编辑', 'edit');
INSERT INTO `action` VALUES ('3', '删除', 'delete');
INSERT INTO `action` VALUES ('4', '导入', 'import');
INSERT INTO `action` VALUES ('5', '导出', 'export');
INSERT INTO `action` VALUES ('6', '添加', 'add');
INSERT INTO `action` VALUES ('7', '审核', 'check');

-- ----------------------------
-- Table structure for adverts
-- ----------------------------
DROP TABLE IF EXISTS `adverts`;
CREATE TABLE `adverts` (
  `ad_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告编号',
  `ad_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '广告地址',
  `ad_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '广告标题',
  `ad_image` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '广告图片',
  `ad_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '广告描述',
  `ad_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '广告类型1pc2m3app',
  `ad_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '广告状态：1正常2弃用',
  `ad_position` varchar(255) NOT NULL DEFAULT '' COMMENT '广告位置h1顶部h2轮播h3分类侧边h4中部推荐l1顶部l2侧边',
  `add_time` datetime NOT NULL COMMENT '添加时间',
  `add_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加人',
  `start_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ad_bgcolor` varchar(20) NOT NULL DEFAULT '' COMMENT '颜色',
  PRIMARY KEY (`ad_id`),
  KEY `link_url` (`ad_url`),
  KEY `ad_type` (`ad_type`),
  KEY `add_user_id` (`add_user_id`),
  KEY `ad_position` (`ad_position`),
  KEY `ad_title` (`ad_title`),
  KEY `ad_status` (`ad_status`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8 COMMENT='广告';

-- ----------------------------
-- Records of adverts
-- ----------------------------
INSERT INTO `adverts` VALUES ('11', 'https:www.baidu.com', 'gh', '/sponline/static/images/banner.png', '', '1', '2', 'h2', '2018-09-27 16:57:11', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('12', 'https://m.hxsheji.cn/uipeixun', 'gh_test123', '/sponline/static/images/banner.png', '', '1', '2', 'h4', '2018-09-27 17:00:35', '1', '2018-09-27 00:00:00', '2018-09-30 00:00:00', '');
INSERT INTO `adverts` VALUES ('13', 'https://m.hxsheji.cn/uipeixun', 'gh2', '/sponline/static/images/banner.png', '', '1', '2', 'h4', '2018-09-27 17:36:28', '1', '2018-09-26 00:00:00', '2018-09-30 00:00:00', '');
INSERT INTO `adverts` VALUES ('17', 'www.baidu.com', 'dsgsd', '/sponline/static/images/banner.png', '', '1', '1', 'h2', '2019-04-29 14:58:42', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('18', '南京', 'php课程', '/sponline/static/images/banner.png', 'very good', '1', '1', 'h2', '2019-05-06 09:13:40', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '#D22E0D');
INSERT INTO `adverts` VALUES ('19', 'https://www.ishouping.com/shangpin/6.html', '这是一个测试', '/sponline/static/images/banner.png', '', '1', '2', 'h3', '2019-07-26 10:13:18', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('20', 'https://www.ishouping.com/shangpin/6.html', '新的感受到', '/sponline/static/images/banner.png', '', '1', '1', 'h4', '2019-07-26 10:22:15', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('21', 'https://www.ishouping.com/shangpin/7.html', '啥时发股份', '/sponline/static/images/banner.png', '', '1', '1', 'h3', '2019-07-26 10:24:49', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('22', 'https://www.ishouping.com/shangpin/7.html', '和集体研究', '/sponline/static/images/banner.png', '', '1', '1', 'l2', '2019-07-26 10:26:11', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('23', 'https://www.ishouping.com/shangpin/7.html', '第三个是无数人废弃物', 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1556191930341&di=762a140adead8d4ccc20d38b87761a87&imgtype=0&src=http%3A%2F%2Fimg.fznews.com.cn%2Fcms%2F7e3b72334%2F20181207%2F15441429017327.png', '', '1', '1', 'l1', '2019-07-26 10:33:40', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('24', 'https://www.ishouping.com/shangpin/11.html', 'sfsw违法未', 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1556191930341&di=762a140adead8d4ccc20d38b87761a87&imgtype=0&src=http%3A%2F%2Fimg.fznews.com.cn%2Fcms%2F7e3b72334%2F20181207%2F15441429017327.png', '', '1', '1', 'h2', '2019-07-26 16:38:04', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('25', 'https://www.ishouping.com/shangpin/6.html', 'sfsd是否', 'http://pic1.win4000.com/wallpaper/2018-06-26/5b31dab532a5f.jpg', '是大哥大哥', '1', '1', 'h2', '2019-08-02 11:01:54', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '#E01212');
INSERT INTO `adverts` VALUES ('27', 'https://www.csdn.net/gather_22/OtTacg3sMjQ3LWJsb2cO0O0O.html', '阿萨德', 'https://image-online.houxue.com\\uploads\\image\\20200108\\7e5ace8bd3284d70dceb17f2545a7567.jpg', '', '1', '1', 'h2', '2020-01-08 14:17:07', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('28', 'https://www.csdn.net/gather_22/OtTacg3sMjQ3LWJsb2cO0O0O.html', '安抚 ', 'https://image-online.houxue.com\\uploads\\image\\20200108\\6baecb87d83bc772459a6072e898647a.jpg', '', '1', '1', 'h2', '2020-01-08 14:22:27', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('29', 'https://www.csdn.net/gather_22/OtTacg3sMjQ3LWJsb2cO0O0O.html', '是大法官', 'https://image-online.houxue.com\\uploads\\image\\20200108\\c00a8b2b5ffc1b5b890339e40de36870.jpg', '', '1', '1', 'h1', '2020-01-08 14:26:26', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('30', '十多个', '十多个', 'https://image-online.houxue.com\\uploads\\image\\20200108\\9fbb9973e8bff540ba3c331114058d71.jpg', '', '1', '1', 'h1', '2020-01-08 14:40:59', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('31', '111111111', '阿达杀手的', '阿达是大师大师大师', '', '1', '1', 'h1', '2020-01-08 14:47:45', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('99', '是大法官', '十多个', '', '', '1', '2', 'h3', '2020-01-09 09:47:38', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('100', '阿斯蒂芬', '十多个', 'https://image-online.houxue.com\\uploads\\image\\20200110\\cbf4b23a8143e7300e080e816950cb32.png', '', '1', '2', 'h2', '2020-01-10 11:30:20', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('101', 'https://blog.csdn.net/xusongsong520/article/details/7829419', 'sdg h', 'https://blog.csdn.net/xusongsong520/article/details/7829419', '', '1', '1', 'h1', '2020-01-13 10:47:45', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('102', 'https://blog.csdn.net/xusongsong520/article/details/7829419', 'fgh ', 'https://image-online.houxue.com\\uploads\\image\\20200113\\5902f99de686b0fa406d15a67a7409eb.png', '', '1', '1', 'h2', '2020-01-13 10:52:54', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('103', 'https://blog.csdn.net/xusongsong520/article/details/7829419', 'sg', 'asdf', '', '1', '1', 'h1', '2020-01-13 10:55:26', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');
INSERT INTO `adverts` VALUES ('104', 'https://blog.csdn.net/viewyu12345/article/details/80850029', 'asf', 'https://image-online.houxue.com\\uploads\\image\\20200113\\e7354d436010f5c613ca15f7ce1a0996.png', '', '1', '1', 'h1', '2020-01-13 14:55:23', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');

-- ----------------------------
-- Table structure for app
-- ----------------------------
DROP TABLE IF EXISTS `app`;
CREATE TABLE `app` (
  `app_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '应用编号',
  `app_name` varchar(200) NOT NULL DEFAULT '' COMMENT '应用名称',
  `test_version` varchar(200) NOT NULL DEFAULT '' COMMENT '应用版本',
  `test_url` varchar(400) NOT NULL DEFAULT '' COMMENT '应用路径',
  `sign_secret` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '签名秘钥',
  `app_version` varchar(200) NOT NULL DEFAULT '' COMMENT '正式版本',
  `app_url` varchar(400) NOT NULL DEFAULT '' COMMENT '正式地址',
  `mini_up_version` varchar(200) NOT NULL DEFAULT '' COMMENT '最低可升级版本号',
  `device_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '设备类型1android2ios',
  PRIMARY KEY (`app_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='app';

-- ----------------------------
-- Records of app
-- ----------------------------
INSERT INTO `app` VALUES ('1', '安卓', '1.0.6', 'https://image-online.houxue.com\\uploads\\apk\\20200113\\9ad55e8f2d320718bcb9760c070db26d.apk', 'a9QBcsGiaKKkw7hxg8YhQU3ne2fbUCLx', '1.0.6', 'https://image-online.houxue.com\\uploads\\apk\\20200113\\9ad55e8f2d320718bcb9760c070db26d.apk', '1.0.0', '1');
INSERT INTO `app` VALUES ('2', 'ios', '1.0.1', '', 'ceshi', '1.0.1', 'https://itunes.apple.com/cn/app/%E9%A6%96%E5%B1%8F%E5%B0%8F%E7%A7%98%E4%B9%A6/id1233585812?mt=8', '1.0.0', '2');

-- ----------------------------
-- Table structure for app_version
-- ----------------------------
DROP TABLE IF EXISTS `app_version`;
CREATE TABLE `app_version` (
  `v_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app_id` int(11) unsigned NOT NULL DEFAULT '0',
  `version_url` varchar(400) NOT NULL DEFAULT '' COMMENT '下载地址',
  `version` varchar(200) NOT NULL COMMENT '版本号',
  `md5` varchar(200) NOT NULL DEFAULT '',
  `add_user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '添加人',
  `add_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `upload_ip` varchar(30) NOT NULL DEFAULT '' COMMENT 'ip',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态：1新增2审核通话3审核失败',
  `check_ip` varchar(30) NOT NULL DEFAULT '' COMMENT 'ip',
  `check_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `check_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `memo` varchar(200) NOT NULL DEFAULT '' COMMENT '升级备注',
  PRIMARY KEY (`v_id`),
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='app版本操作';

-- ----------------------------
-- Records of app_version
-- ----------------------------

-- ----------------------------
-- Table structure for department
-- ----------------------------
DROP TABLE IF EXISTS `department`;
CREATE TABLE `department` (
  `dp_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '部门编号',
  `dp_name` varchar(255) NOT NULL DEFAULT '' COMMENT '部门名称',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父类编号',
  PRIMARY KEY (`dp_id`),
  UNIQUE KEY `dp_name` (`dp_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='部门表';

-- ----------------------------
-- Records of department
-- ----------------------------
INSERT INTO `department` VALUES ('1', '首屏', '0');
INSERT INTO `department` VALUES ('2', '研发', '1');
INSERT INTO `department` VALUES ('3', '客服', '1');

-- ----------------------------
-- Table structure for file
-- ----------------------------
DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
  `file_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件编号',
  `file_url` varchar(1000) NOT NULL DEFAULT '' COMMENT '文件地址',
  `upload_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上传时间',
  `file_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '文件类型：1图片2音频3视频',
  `media_id` varchar(255) NOT NULL DEFAULT '' COMMENT '媒体编号',
  `remote_url` varchar(1000) NOT NULL DEFAULT '' COMMENT '百度地址',
  `remote_cover` varchar(1000) NOT NULL DEFAULT '' COMMENT '封面地址',
  PRIMARY KEY (`file_id`),
  KEY `media_id` (`media_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='上传文件表';

-- ----------------------------
-- Records of file
-- ----------------------------

-- ----------------------------
-- Table structure for global_set
-- ----------------------------
DROP TABLE IF EXISTS `global_set`;
CREATE TABLE `global_set` (
  `gs_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `gs_option` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '全局配置项',
  `gs_value` text NOT NULL COMMENT '内容',
  `gs_label` varchar(255) NOT NULL DEFAULT '' COMMENT '显示标签',
  PRIMARY KEY (`gs_id`),
  UNIQUE KEY `gs_option` (`gs_option`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='全局配置';

-- ----------------------------
-- Records of global_set
-- ----------------------------
INSERT INTO `global_set` VALUES ('1', 'site_name', '广电直播课', '默认平台名称');
INSERT INTO `global_set` VALUES ('2', 'site_ico', '', '默认平台图标');
INSERT INTO `global_set` VALUES ('3', 'threshold', '22', '访问阈值');
INSERT INTO `global_set` VALUES ('4', 'copy_right', 'Copyright © 2019- All Rights Reserved. 江苏厚学网信息技术股份有限公司.', 'Copyright');
INSERT INTO `global_set` VALUES ('5', 'ipc1', '32010402000155', 'IPC1');
INSERT INTO `global_set` VALUES ('6', 'ipc2', '苏B2-20150251', 'IPC2');
INSERT INTO `global_set` VALUES ('7', 'ipc3', '苏ICP备14012599号-10', 'IPC3');
INSERT INTO `global_set` VALUES ('8', 'per_fee', '0.03', '单价(元/人/分钟)');
INSERT INTO `global_set` VALUES ('9', '2', '2', '2');
INSERT INTO `global_set` VALUES ('10', '3', '3', '3');
INSERT INTO `global_set` VALUES ('22', '13', '1', '1');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `menu_id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` mediumint(9) unsigned NOT NULL,
  `name` varchar(20) NOT NULL COMMENT '菜单名称',
  `url` varchar(200) NOT NULL COMMENT 'URL',
  `icon` varchar(200) NOT NULL DEFAULT '' COMMENT '图标',
  `icon_color` varchar(20) NOT NULL DEFAULT '' COMMENT '图标颜色',
  `description` varchar(200) NOT NULL DEFAULT '' COMMENT '描述',
  `display_order` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '显示顺序',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0=可用，1=禁用',
  `permission_id` int(11) NOT NULL DEFAULT '0' COMMENT '权限id',
  `tab_id` varchar(50) NOT NULL DEFAULT '' COMMENT '路由标签(以_分割)',
  PRIMARY KEY (`menu_id`),
  KEY `fk_sys_menu_sys_permission1_idx` (`permission_id`) USING BTREE,
  KEY `idx_menu_disabled` (`disabled`) USING BTREE,
  KEY `IDX_PA_DI_ME` (`parent_id`,`display_order`,`menu_id`) USING BTREE,
  KEY `IDX_PARENT_ID` (`parent_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='系统菜单';

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES ('1', '0', '后台管理', 'admin', 'el-icon-setting', '#aeb9c2', '后台管理', '100', '0', '1', 'admin');
INSERT INTO `menu` VALUES ('2', '1', '人员列表', 'admin/user/Index', '', '', '员工管理', '0', '0', '2', 'admin_user_Index');
INSERT INTO `menu` VALUES ('3', '1', '职位列表', 'admin/position/Index', '', '', '职位管理', '0', '0', '3', 'admin_position_Index');
INSERT INTO `menu` VALUES ('4', '1', '部门列表', 'admin/department/Index', '', '', '部门管理', '0', '0', '4', 'admin_department_Index');
INSERT INTO `menu` VALUES ('5', '1', '权限管理', 'admin/permission/Index', '', '', '权限管理', '0', '0', '5', 'admin_permission_Index');
INSERT INTO `menu` VALUES ('6', '0', '会员管理', 'member', 'el-icon-school', '#aeb9c2', '学校管理', '1', '0', '6', 'member');
INSERT INTO `menu` VALUES ('7', '6', '试用会员', 'member/freeteacher/Index', '', '', '', '0', '0', '7', 'member_freeteacher_Index');
INSERT INTO `menu` VALUES ('8', '6', '会员列表', 'member/teacher/Index', '', '', '老师列表', '0', '0', '8', 'member_teacher_Index');
INSERT INTO `menu` VALUES ('9', '6', '学生列表', 'member/student/Index', '', '', '学员列表', '0', '0', '9', 'member_student_Index');
INSERT INTO `menu` VALUES ('10', '0', '直播管理', 'course', 'el-icon-video-camera', '#aeb9c2', '课程管理', '0', '0', '10', 'course');
INSERT INTO `menu` VALUES ('11', '10', '直播课列表', 'course/course/Index', '', '', '课程列表', '0', '0', '11', 'course_course_Index');
INSERT INTO `menu` VALUES ('12', '6', '课件列表', 'member/courseware/Index', '', '', '课件列表', '0', '0', '12', 'member_courseware_Index');
INSERT INTO `menu` VALUES ('13', '10', '回放列表', 'course/replay/Index', '', '', '', '0', '0', '13', 'course_replay_Index');
INSERT INTO `menu` VALUES ('14', '19', '广告管理', 'website/adverts/Index', 'el-icon-website', '', '广告管理', '0', '0', '14', 'website_adverts_Index');
INSERT INTO `menu` VALUES ('15', '0', '订单管理', 'order', 'el-icon-s-data', '#aeb9c2', '订单管理', '4', '0', '15', 'order');
INSERT INTO `menu` VALUES ('16', '6', '班级列表', 'member/classes/Index', '', '', '', '0', '0', '16', 'member_class_Index');
INSERT INTO `menu` VALUES ('17', '15', '订单列表', 'order/order/Index', '', '', '订单列表', '0', '0', '17', 'order_order_Index');
INSERT INTO `menu` VALUES ('18', '6', '回访记录', 'member/visit/Index', '', '', '', '0', '0', '18', 'member_visit_Index');
INSERT INTO `menu` VALUES ('19', '0', '网站管理', 'website', 'el-icon-monitor', '#aeb9c2', '网站管理', '8', '0', '19', 'website');
INSERT INTO `menu` VALUES ('20', '19', 'APP管理', 'website/app/Index', '', '', 'M端首页设置', '0', '0', '20', 'website_app_Index');
INSERT INTO `menu` VALUES ('21', '19', '版本记录', 'website/history/Index', '', '', 'M端首页设置', '0', '0', '21', 'website_history_Index');
INSERT INTO `menu` VALUES ('22', '6', '反馈列表', 'member/feeback/Index', '', '', '', '0', '0', '0', 'member_feeback_Index');
INSERT INTO `menu` VALUES ('27', '15', '消费记录', 'order/expense/Index', '', '', '消费记录', '0', '0', '0', 'order_expense_Index');
INSERT INTO `menu` VALUES ('28', '1', '全局配置', 'admin/global/Index', '', '', '全局配置', '0', '0', '0', 'admin_global_Index');

-- ----------------------------
-- Table structure for permission
-- ----------------------------
DROP TABLE IF EXISTS `permission`;
CREATE TABLE `permission` (
  `permission_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限编号',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父类编号',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '权限名称',
  `controller` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '控制器',
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `controller` (`controller`) USING BTREE,
  KEY `parent_id` (`parent_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='权限表';

-- ----------------------------
-- Records of permission
-- ----------------------------
INSERT INTO `permission` VALUES ('1', '0', '后台管理', 'admin');
INSERT INTO `permission` VALUES ('2', '1', '人员列表', 'admin/user');
INSERT INTO `permission` VALUES ('3', '1', '职位列表', 'admin/position');
INSERT INTO `permission` VALUES ('4', '1', '部门列表', 'admin/department');
INSERT INTO `permission` VALUES ('5', '1', '权限管理', 'admin/permission');
INSERT INTO `permission` VALUES ('6', '0', '用户管理', 'member');
INSERT INTO `permission` VALUES ('7', '6', '试用会员', 'member/free');
INSERT INTO `permission` VALUES ('8', '6', '会员列表', 'member/teacher');
INSERT INTO `permission` VALUES ('9', '6', '学生列表', 'member/student');
INSERT INTO `permission` VALUES ('10', '0', '直播管理', 'course');
INSERT INTO `permission` VALUES ('11', '10', '直播课列表', 'course/course');
INSERT INTO `permission` VALUES ('12', '6', '课件列表', 'course/courseware');
INSERT INTO `permission` VALUES ('13', '10', '回放列表', 'course/replay');
INSERT INTO `permission` VALUES ('14', '19', '广告管理', 'website/adverts');
INSERT INTO `permission` VALUES ('15', '0', '订单管理', 'order');
INSERT INTO `permission` VALUES ('16', '6', '班级列表', 'member/classes');
INSERT INTO `permission` VALUES ('17', '15', '订单列表', 'order/order');
INSERT INTO `permission` VALUES ('18', '6', '回访列表', 'member/visit');
INSERT INTO `permission` VALUES ('19', '0', '网站管理', 'website');
INSERT INTO `permission` VALUES ('20', '19', 'APP管理', 'website/app');
INSERT INTO `permission` VALUES ('21', '19', '版本记录', 'website/history');
INSERT INTO `permission` VALUES ('22', '6', '反馈列表', 'member/feeback');
INSERT INTO `permission` VALUES ('27', '15', '消费记录', 'order/expense');
INSERT INTO `permission` VALUES ('28', '1', '全局配置', 'admin/global');

-- ----------------------------
-- Table structure for permission_position
-- ----------------------------
DROP TABLE IF EXISTS `permission_position`;
CREATE TABLE `permission_position` (
  `pp_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `permission_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限编号',
  `ps_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '职位编号',
  `range_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '范围类型1全部2部分3个人',
  PRIMARY KEY (`pp_id`),
  KEY `permission_id` (`permission_id`,`ps_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='权限职位表';

-- ----------------------------
-- Records of permission_position
-- ----------------------------
INSERT INTO `permission_position` VALUES ('3', '2', '6', '1');

-- ----------------------------
-- Table structure for permission_position_action
-- ----------------------------
DROP TABLE IF EXISTS `permission_position_action`;
CREATE TABLE `permission_position_action` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `pp_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限编号',
  `action_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '动作编号',
  PRIMARY KEY (`id`),
  KEY `permission_id` (`pp_id`,`action_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='权限职位表';

-- ----------------------------
-- Records of permission_position_action
-- ----------------------------
INSERT INTO `permission_position_action` VALUES ('9', '3', '1');

-- ----------------------------
-- Table structure for permission_position_range
-- ----------------------------
DROP TABLE IF EXISTS `permission_position_range`;
CREATE TABLE `permission_position_range` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `pp_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限编号',
  `dp_id` int(11) NOT NULL DEFAULT '0' COMMENT '职位编号',
  PRIMARY KEY (`id`),
  KEY `permission_id` (`pp_id`,`dp_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='权限职位表';

-- ----------------------------
-- Records of permission_position_range
-- ----------------------------
INSERT INTO `permission_position_range` VALUES ('2', '3', '0');

-- ----------------------------
-- Table structure for position
-- ----------------------------
DROP TABLE IF EXISTS `position`;
CREATE TABLE `position` (
  `ps_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '职位编号',
  `ps_name` varchar(255) NOT NULL DEFAULT '' COMMENT '职位名称',
  PRIMARY KEY (`ps_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='职位表';

-- ----------------------------
-- Records of position
-- ----------------------------
INSERT INTO `position` VALUES ('1', '超级管理员');
INSERT INTO `position` VALUES ('5', '销售');
INSERT INTO `position` VALUES ('6', '客服');
INSERT INTO `position` VALUES ('7', '运维');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '员工编号',
  `user_name` varchar(30) NOT NULL DEFAULT '' COMMENT '人员名称',
  `ps_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '职位编号',
  `login_name` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '账号名称',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '密码',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '人员状态:1正常，0停用',
  `email` varchar(40) NOT NULL DEFAULT '' COMMENT '邮箱',
  `add_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `update_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最近登录时间',
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后登录时间',
  `phone` varchar(255) NOT NULL DEFAULT '' COMMENT '电话号码',
  `token` varchar(32) NOT NULL,
  `token_timeout` datetime NOT NULL COMMENT 'token到期时间',
  `wechat` varchar(255) NOT NULL DEFAULT '' COMMENT '微信号',
  `gender` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别:1男2女',
  `dp_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '部门编号',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `login_name` (`login_name`) USING BTREE,
  KEY `ps_id` (`ps_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8 COMMENT='老师编号';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '超级管理员', '1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '1', '', '2017-12-08 11:12:25', '2019-01-14 15:54:56', '2020-05-09 16:20:03', '18655073830', '8blxY7OQaayObWfpSUP4gCiinGIYYlQ5', '2019-01-27 11:20:11', '', '1', '0');
INSERT INTO `user` VALUES ('195', 'test', '6', 'test', 'e10adc3949ba59abbe56e057f20f883e', '1', '', '2020-01-13 11:13:17', '2020-03-13 11:16:41', '0000-00-00 00:00:00', '11', '', '0000-00-00 00:00:00', '', '1', '3');
INSERT INTO `user` VALUES ('196', '测试1', '7', 'test1', 'e10adc3949ba59abbe56e057f20f883e', '1', '', '2020-03-13 11:07:58', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', '', '1', '2');

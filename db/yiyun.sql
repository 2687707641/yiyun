/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : yiyun

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2019-09-03 11:25:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tb_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `tb_auth_group`;
CREATE TABLE `tb_auth_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(255) NOT NULL COMMENT '角色名称',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态:0正常,1禁用,-1删除',
  `rules` varchar(255) NOT NULL COMMENT '权限规则,以id标识,逗号隔开',
  `remarks` varchar(255) DEFAULT NULL COMMENT '描述',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色权限表\r\n';

-- ----------------------------
-- Records of tb_auth_group
-- ----------------------------
INSERT INTO `tb_auth_group` VALUES ('1', '管理员', '0', ',1,2,3,4,5,6,7,', '除系统管理外的全部权限', '2019-08-28 15:08:16');

-- ----------------------------
-- Table structure for tb_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `tb_auth_rule`;
CREATE TABLE `tb_auth_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `pid` int(11) DEFAULT NULL COMMENT '所属栏目标识(0为顶级栏目)',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否有效(1有效,0无效)',
  `moudule` varchar(20) DEFAULT NULL COMMENT '规则所属moudule',
  `name` varchar(80) DEFAULT NULL COMMENT '英文标识',
  `remarks` varchar(20) DEFAULT NULL COMMENT '中文描述',
  `desc` varchar(255) DEFAULT NULL COMMENT '描述',
  `icon` varchar(255) DEFAULT NULL COMMENT '图标',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='权限节点表(菜单栏权限表)';

-- ----------------------------
-- Records of tb_auth_rule
-- ----------------------------
INSERT INTO `tb_auth_rule` VALUES ('1', '0', '1', 'admin', '', '用户管理', null, '&#xe6b8;');
INSERT INTO `tb_auth_rule` VALUES ('2', '1', '1', 'admin', 'user/lists', '用户列表', '', null);
INSERT INTO `tb_auth_rule` VALUES ('3', '0', '1', 'admin', null, '商品管理', null, '&#xe6f4;');
INSERT INTO `tb_auth_rule` VALUES ('4', '3', '1', 'admin', 'book/cate', '商品分类', null, null);
INSERT INTO `tb_auth_rule` VALUES ('5', '3', '1', 'admin', 'book/lists', '商品列表', '', null);
INSERT INTO `tb_auth_rule` VALUES ('6', '0', '1', 'admin', '', '订单管理', null, '&#xe6fc;');
INSERT INTO `tb_auth_rule` VALUES ('7', '6', '1', 'admin', 'order/lists', '订单列表', null, null);
INSERT INTO `tb_auth_rule` VALUES ('8', '0', '1', 'admin', null, '系统管理', null, '&#xe6ae;');
INSERT INTO `tb_auth_rule` VALUES ('9', '8', '1', 'admin', 'manager/lists', '管理员列表', null, '');
INSERT INTO `tb_auth_rule` VALUES ('10', '8', '1', 'admin', 'role/lists', '角色管理', null, null);
INSERT INTO `tb_auth_rule` VALUES ('11', '8', '1', 'admin', 'menu/lists', '菜单管理', null, null);

-- ----------------------------
-- Table structure for tb_manager
-- ----------------------------
DROP TABLE IF EXISTS `tb_manager`;
CREATE TABLE `tb_manager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL COMMENT '登录用户名',
  `password` varchar(32) NOT NULL COMMENT '登录密码',
  `nickname` varchar(45) DEFAULT NULL,
  `last_login_time` datetime DEFAULT NULL COMMENT '上次登录时间',
  `last_ip` varchar(50) DEFAULT NULL COMMENT '上次登录ip',
  `status` int(11) DEFAULT '1' COMMENT '0:启用,1:禁用',
  `login` int(11) DEFAULT '0' COMMENT '登录次数',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `role` int(255) NOT NULL DEFAULT '0' COMMENT '所属角色组',
  `deleted` tinyint(4) DEFAULT '0' COMMENT '是否被删除 0:违背删除,1:被删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='管理员主表';

-- ----------------------------
-- Records of tb_manager
-- ----------------------------
INSERT INTO `tb_manager` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', '超级管理员', '2019-08-28 18:02:02', '127.0.0.1', '0', '16', '2019-08-28 15:06:25', '2019-08-28 18:02:02', '0', '0');

-- ----------------------------
-- Table structure for tb_user
-- ----------------------------
DROP TABLE IF EXISTS `tb_user`;
CREATE TABLE `tb_user` (
  `id` int(50) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `phone` varchar(255) NOT NULL COMMENT '用户手机号',
  `nickname` varchar(255) DEFAULT NULL COMMENT '用户昵称',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `password` varchar(255) DEFAULT NULL COMMENT '用户密码',
  `level` tinyint(2) DEFAULT '0' COMMENT '用户VIP等级(0:普通,1:会员,2:铂金会员)',
  `status` tinyint(2) DEFAULT '0' COMMENT '账户状态 0:正常',
  `deleted` tinyint(2) DEFAULT '0' COMMENT '是否删除(0:正常,1:被删除)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of tb_user
-- ----------------------------

-- ----------------------------
-- Table structure for tb_cate
-- ----------------------------
DROP TABLE IF EXISTS `tb_cate`;
CREATE TABLE `tb_cate` (
  `id` int(30) NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `name` varchar(255) DEFAULT NULL COMMENT '栏目名称',
  `pid` int(30) DEFAULT '0' COMMENT '所属栏目id(0:顶级栏目,非0:id为此值的次级栏目)',
  `remarks` varchar(255) DEFAULT NULL COMMENT '描述',
  `sort` int(30) DEFAULT '1' COMMENT '排序',
  `status` int(11) DEFAULT '0' COMMENT '状态(0:启用,1:禁用)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='商品栏目表';

-- ----------------------------
-- Records of tb_cate
-- ----------------------------

-- ----------------------------
-- Table structure for tb_book
-- ----------------------------
DROP TABLE IF EXISTS `tb_book`;
CREATE TABLE `tb_book` (
  `id` int(30) NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `name` varchar(255) DEFAULT NULL COMMENT '商品名称',
  `price` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `num` int(50) DEFAULT NULL COMMENT '商品数量',
  `remarks` varchar(255) DEFAULT NULL COMMENT '描述',
  `author` varchar(255) DEFAULT NULL COMMENT '作者',
  `pid` int(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Records of tb_book
-- ----------------------------
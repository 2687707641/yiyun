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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='权限节点表(菜单栏权限表)';

-- ----------------------------
-- Records of tb_auth_rule
-- ----------------------------
INSERT INTO `tb_auth_rule` VALUES ('1', '0', '1', 'admin', '', '用户管理', null);
INSERT INTO `tb_auth_rule` VALUES ('2', '1', '1', 'admin', 'user/lists', '用户列表', '');
INSERT INTO `tb_auth_rule` VALUES ('3', '0', '1', 'admin', null, '商品管理', null);
INSERT INTO `tb_auth_rule` VALUES ('4', '3', '1', 'admin', 'book/cate', '商品分类', null);
INSERT INTO `tb_auth_rule` VALUES ('5', '3', '1', 'admin', 'book/lists', '商品列表', '');
INSERT INTO `tb_auth_rule` VALUES ('6', '0', '1', 'admin', '', '订单管理', null);
INSERT INTO `tb_auth_rule` VALUES ('7', '6', '1', 'admin', 'order/lists', '订单列表', null);
INSERT INTO `tb_auth_rule` VALUES ('8', '0', '1', 'admin', null, '系统管理', null);
INSERT INTO `tb_auth_rule` VALUES ('9', '8', '1', 'admin', 'manager/lists', '管理员列表', null);
INSERT INTO `tb_auth_rule` VALUES ('10', '8', '1', 'admin', 'role/lists', '角色管理', null);
INSERT INTO `tb_auth_rule` VALUES ('11', '8', '1', 'admin', 'menu/lists', '菜单管理', null);

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
  `phone` varchar(255) DEFAULT NULL COMMENT '用户电话',
  `password` varchar(255) DEFAULT NULL COMMENT '用户密码',
  `nickname` varchar(255) DEFAULT NULL COMMENT '用户昵称',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户信息表';

-- ----------------------------
-- Records of tb_user
-- ----------------------------
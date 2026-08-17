/*
 Navicat Premium Data Transfer

 Source Server         : LOCAL
 Source Server Type    : MySQL
 Source Server Version : 50717
 Source Host           : localhost:3306
 Source Schema         : tmc_builder

 Target Server Type    : MySQL
 Target Server Version : 50717
 File Encoding         : 65001

 Date: 04/08/2019 22:17:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for aauth_group_to_group
-- ----------------------------
DROP TABLE IF EXISTS `aauth_group_to_group`;
CREATE TABLE `aauth_group_to_group`  (
  `group_id` int(11) UNSIGNED NOT NULL,
  `subgroup_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`group_id`, `subgroup_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for aauth_groups
-- ----------------------------
DROP TABLE IF EXISTS `aauth_groups`;
CREATE TABLE `aauth_groups`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `definition` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of aauth_groups
-- ----------------------------
INSERT INTO `aauth_groups` VALUES (1, 'Admin', 'Superadmin Group');
INSERT INTO `aauth_groups` VALUES (4, 'User', 'User Access Group');

-- ----------------------------
-- Table structure for aauth_login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `aauth_login_attempts`;
CREATE TABLE `aauth_login_attempts`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(39) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `timestamp` datetime(0) NULL DEFAULT NULL,
  `login_attempts` tinyint(2) UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 79 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for aauth_perm_to_group
-- ----------------------------
DROP TABLE IF EXISTS `aauth_perm_to_group`;
CREATE TABLE `aauth_perm_to_group`  (
  `perm_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of aauth_perm_to_group
-- ----------------------------
INSERT INTO `aauth_perm_to_group` VALUES (99, 1);
INSERT INTO `aauth_perm_to_group` VALUES (120, 1);
INSERT INTO `aauth_perm_to_group` VALUES (120, 1);
INSERT INTO `aauth_perm_to_group` VALUES (121, 1);
INSERT INTO `aauth_perm_to_group` VALUES (120, 1);
INSERT INTO `aauth_perm_to_group` VALUES (120, 1);
INSERT INTO `aauth_perm_to_group` VALUES (120, 1);
INSERT INTO `aauth_perm_to_group` VALUES (120, 1);
INSERT INTO `aauth_perm_to_group` VALUES (1, 1);
INSERT INTO `aauth_perm_to_group` VALUES (122, 1);
INSERT INTO `aauth_perm_to_group` VALUES (1, 4);
INSERT INTO `aauth_perm_to_group` VALUES (120, 4);
INSERT INTO `aauth_perm_to_group` VALUES (121, 4);
INSERT INTO `aauth_perm_to_group` VALUES (122, 4);
INSERT INTO `aauth_perm_to_group` VALUES (22, 4);
INSERT INTO `aauth_perm_to_group` VALUES (119, 4);
INSERT INTO `aauth_perm_to_group` VALUES (135, 1);
INSERT INTO `aauth_perm_to_group` VALUES (121, 1);
INSERT INTO `aauth_perm_to_group` VALUES (121, 1);

-- ----------------------------
-- Table structure for aauth_perm_to_user
-- ----------------------------
DROP TABLE IF EXISTS `aauth_perm_to_user`;
CREATE TABLE `aauth_perm_to_user`  (
  `perm_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `perm_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for aauth_perms
-- ----------------------------
DROP TABLE IF EXISTS `aauth_perms`;
CREATE TABLE `aauth_perms`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `definition` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 226 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of aauth_perms
-- ----------------------------
INSERT INTO `aauth_perms` VALUES (1, 'menu_dashboard', NULL);
INSERT INTO `aauth_perms` VALUES (2, 'menu_crud_builder', NULL);
INSERT INTO `aauth_perms` VALUES (3, 'menu_api_builder', NULL);
INSERT INTO `aauth_perms` VALUES (4, 'menu_page_builder', NULL);
INSERT INTO `aauth_perms` VALUES (5, 'menu_form_builder', NULL);
INSERT INTO `aauth_perms` VALUES (6, 'menu_menu', NULL);
INSERT INTO `aauth_perms` VALUES (7, 'menu_auth', NULL);
INSERT INTO `aauth_perms` VALUES (8, 'menu_user', NULL);
INSERT INTO `aauth_perms` VALUES (9, 'menu_group', NULL);
INSERT INTO `aauth_perms` VALUES (10, 'menu_access', NULL);
INSERT INTO `aauth_perms` VALUES (11, 'menu_permission', NULL);
INSERT INTO `aauth_perms` VALUES (12, 'menu_api_documentation', NULL);
INSERT INTO `aauth_perms` VALUES (13, 'menu_web_documentation', NULL);
INSERT INTO `aauth_perms` VALUES (14, 'menu_settings', NULL);
INSERT INTO `aauth_perms` VALUES (15, 'user_list', NULL);
INSERT INTO `aauth_perms` VALUES (16, 'user_update_status', NULL);
INSERT INTO `aauth_perms` VALUES (17, 'user_export', NULL);
INSERT INTO `aauth_perms` VALUES (18, 'user_add', NULL);
INSERT INTO `aauth_perms` VALUES (19, 'user_update', NULL);
INSERT INTO `aauth_perms` VALUES (20, 'user_update_profile', NULL);
INSERT INTO `aauth_perms` VALUES (21, 'user_update_password', NULL);
INSERT INTO `aauth_perms` VALUES (22, 'user_profile', NULL);
INSERT INTO `aauth_perms` VALUES (23, 'user_view', NULL);
INSERT INTO `aauth_perms` VALUES (24, 'user_delete', NULL);
INSERT INTO `aauth_perms` VALUES (25, 'blog_list', NULL);
INSERT INTO `aauth_perms` VALUES (26, 'blog_export', NULL);
INSERT INTO `aauth_perms` VALUES (27, 'blog_add', NULL);
INSERT INTO `aauth_perms` VALUES (28, 'blog_update', NULL);
INSERT INTO `aauth_perms` VALUES (29, 'blog_view', NULL);
INSERT INTO `aauth_perms` VALUES (30, 'blog_delete', NULL);
INSERT INTO `aauth_perms` VALUES (31, 'form_list', NULL);
INSERT INTO `aauth_perms` VALUES (32, 'form_export', NULL);
INSERT INTO `aauth_perms` VALUES (33, 'form_add', NULL);
INSERT INTO `aauth_perms` VALUES (34, 'form_update', NULL);
INSERT INTO `aauth_perms` VALUES (35, 'form_view', NULL);
INSERT INTO `aauth_perms` VALUES (36, 'form_manage', NULL);
INSERT INTO `aauth_perms` VALUES (37, 'form_delete', NULL);
INSERT INTO `aauth_perms` VALUES (38, 'crud_list', NULL);
INSERT INTO `aauth_perms` VALUES (39, 'crud_export', NULL);
INSERT INTO `aauth_perms` VALUES (40, 'crud_add', NULL);
INSERT INTO `aauth_perms` VALUES (41, 'crud_update', NULL);
INSERT INTO `aauth_perms` VALUES (42, 'crud_view', NULL);
INSERT INTO `aauth_perms` VALUES (43, 'crud_delete', NULL);
INSERT INTO `aauth_perms` VALUES (44, 'rest_list', NULL);
INSERT INTO `aauth_perms` VALUES (45, 'rest_export', NULL);
INSERT INTO `aauth_perms` VALUES (46, 'rest_add', NULL);
INSERT INTO `aauth_perms` VALUES (47, 'rest_update', NULL);
INSERT INTO `aauth_perms` VALUES (48, 'rest_view', NULL);
INSERT INTO `aauth_perms` VALUES (49, 'rest_delete', NULL);
INSERT INTO `aauth_perms` VALUES (50, 'group_list', NULL);
INSERT INTO `aauth_perms` VALUES (51, 'group_export', NULL);
INSERT INTO `aauth_perms` VALUES (52, 'group_add', NULL);
INSERT INTO `aauth_perms` VALUES (53, 'group_update', NULL);
INSERT INTO `aauth_perms` VALUES (54, 'group_view', NULL);
INSERT INTO `aauth_perms` VALUES (55, 'group_delete', NULL);
INSERT INTO `aauth_perms` VALUES (56, 'permission_list', NULL);
INSERT INTO `aauth_perms` VALUES (57, 'permission_export', NULL);
INSERT INTO `aauth_perms` VALUES (58, 'permission_add', NULL);
INSERT INTO `aauth_perms` VALUES (59, 'permission_update', NULL);
INSERT INTO `aauth_perms` VALUES (60, 'permission_view', NULL);
INSERT INTO `aauth_perms` VALUES (61, 'permission_delete', NULL);
INSERT INTO `aauth_perms` VALUES (62, 'access_list', NULL);
INSERT INTO `aauth_perms` VALUES (63, 'access_add', NULL);
INSERT INTO `aauth_perms` VALUES (64, 'access_update', NULL);
INSERT INTO `aauth_perms` VALUES (65, 'menu_list', NULL);
INSERT INTO `aauth_perms` VALUES (66, 'menu_add', NULL);
INSERT INTO `aauth_perms` VALUES (67, 'menu_update', NULL);
INSERT INTO `aauth_perms` VALUES (68, 'menu_delete', NULL);
INSERT INTO `aauth_perms` VALUES (69, 'menu_save_ordering', NULL);
INSERT INTO `aauth_perms` VALUES (70, 'menu_type_add', NULL);
INSERT INTO `aauth_perms` VALUES (71, 'page_list', NULL);
INSERT INTO `aauth_perms` VALUES (72, 'page_export', NULL);
INSERT INTO `aauth_perms` VALUES (73, 'page_add', NULL);
INSERT INTO `aauth_perms` VALUES (74, 'page_update', NULL);
INSERT INTO `aauth_perms` VALUES (75, 'page_view', NULL);
INSERT INTO `aauth_perms` VALUES (76, 'page_delete', NULL);
INSERT INTO `aauth_perms` VALUES (77, 'blog_list', NULL);
INSERT INTO `aauth_perms` VALUES (78, 'blog_export', NULL);
INSERT INTO `aauth_perms` VALUES (79, 'blog_add', NULL);
INSERT INTO `aauth_perms` VALUES (80, 'blog_update', NULL);
INSERT INTO `aauth_perms` VALUES (81, 'blog_view', NULL);
INSERT INTO `aauth_perms` VALUES (82, 'blog_delete', NULL);
INSERT INTO `aauth_perms` VALUES (83, 'setting', NULL);
INSERT INTO `aauth_perms` VALUES (84, 'setting_update', NULL);
INSERT INTO `aauth_perms` VALUES (85, 'dashboard', NULL);
INSERT INTO `aauth_perms` VALUES (86, 'extension_list', NULL);
INSERT INTO `aauth_perms` VALUES (87, 'extension_activate', NULL);
INSERT INTO `aauth_perms` VALUES (88, 'extension_deactivate', NULL);
INSERT INTO `aauth_perms` VALUES (99, 'menu_administrator', '');
INSERT INTO `aauth_perms` VALUES (120, 'menu_master_data', '');
INSERT INTO `aauth_perms` VALUES (121, 'menu_skpd', '');
INSERT INTO `aauth_perms` VALUES (122, 'menu_profil', '');
INSERT INTO `aauth_perms` VALUES (128, 'menu_tmc_crud', '');
INSERT INTO `aauth_perms` VALUES (129, 'menu_tmc_api_create', '');
INSERT INTO `aauth_perms` VALUES (135, 'menu_program', '');

-- ----------------------------
-- Table structure for aauth_pms
-- ----------------------------
DROP TABLE IF EXISTS `aauth_pms`;
CREATE TABLE `aauth_pms`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `receiver_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(225) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `date_sent` datetime(0) NULL DEFAULT NULL,
  `date_read` datetime(0) NULL DEFAULT NULL,
  `pm_deleted_sender` int(1) NULL DEFAULT NULL,
  `pm_deleted_receiver` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for aauth_user
-- ----------------------------
DROP TABLE IF EXISTS `aauth_user`;
CREATE TABLE `aauth_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `definition` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for aauth_user_to_group
-- ----------------------------
DROP TABLE IF EXISTS `aauth_user_to_group`;
CREATE TABLE `aauth_user_to_group`  (
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`user_id`, `group_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of aauth_user_to_group
-- ----------------------------
INSERT INTO `aauth_user_to_group` VALUES (1, 1);
INSERT INTO `aauth_user_to_group` VALUES (2, 4);
INSERT INTO `aauth_user_to_group` VALUES (3, 4);

-- ----------------------------
-- Table structure for aauth_user_variables
-- ----------------------------
DROP TABLE IF EXISTS `aauth_user_variables`;
CREATE TABLE `aauth_user_variables`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `data_key` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for aauth_users
-- ----------------------------
DROP TABLE IF EXISTS `aauth_users`;
CREATE TABLE `aauth_users`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `oauth_uid` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `oauth_provider` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pass` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `full_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `avatar` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `banned` tinyint(1) NULL DEFAULT 0,
  `last_login` datetime(0) NULL DEFAULT NULL,
  `last_activity` datetime(0) NULL DEFAULT NULL,
  `date_created` datetime(0) NULL DEFAULT NULL,
  `forgot_exp` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `remember_time` datetime(0) NULL DEFAULT NULL,
  `remember_exp` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `verification_code` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `top_secret` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ip_address` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of aauth_users
-- ----------------------------
INSERT INTO `aauth_users` VALUES (1, 'tmc@gmail.com', NULL, NULL, 'a8f7eb8270c328a5a9258df1e15e381b36b74c9c084260c52e8a1e7240b127d1', 'tmc', 'Trios Media', '20190803222616-IMG_0851.JPG', 0, '2019-08-04 22:15:18', '2019-08-04 22:15:18', '2019-08-03 01:11:23', NULL, NULL, NULL, NULL, NULL, '::1');
INSERT INTO `aauth_users` VALUES (2, 'farid@gmail.com', NULL, NULL, 'd553d86a94c733bc39f057dab91af8fdaf09129d263df0f9af541d8d4ae681d9', 'farid', 'Farid Zahwan', '20190803222738-Birds_with_Human_Hands_7.jpg', 0, '2019-08-04 22:14:31', '2019-08-04 22:14:31', '2019-08-03 18:58:24', NULL, NULL, NULL, NULL, NULL, '::1');

-- ----------------------------
-- Table structure for captcha
-- ----------------------------
DROP TABLE IF EXISTS `captcha`;
CREATE TABLE `captcha`  (
  `captcha_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `captcha_time` int(10) NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `word` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`captcha_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of captcha
-- ----------------------------
INSERT INTO `captcha` VALUES (1, 1564900765, '::1', 'U99Z');
INSERT INTO `captcha` VALUES (2, 1564900790, '::1', 'IN2Q');

-- ----------------------------
-- Table structure for cc_options
-- ----------------------------
DROP TABLE IF EXISTS `cc_options`;
CREATE TABLE `cc_options`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `option_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `option_value` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cc_options
-- ----------------------------
INSERT INTO `cc_options` VALUES (1, 'active_theme', 'cicool');
INSERT INTO `cc_options` VALUES (2, 'favicon', 'default.png');
INSERT INTO `cc_options` VALUES (3, 'site_name', 'TMC APP');
INSERT INTO `cc_options` VALUES (4, 'enable_disqus', NULL);
INSERT INTO `cc_options` VALUES (5, 'disqus_id', '');
INSERT INTO `cc_options` VALUES (6, 'email', 'tmc@gmail.com');
INSERT INTO `cc_options` VALUES (7, 'author', '');
INSERT INTO `cc_options` VALUES (8, 'site_description', 'TMC APP');
INSERT INTO `cc_options` VALUES (9, 'keywords', '');
INSERT INTO `cc_options` VALUES (10, 'landing_page_id', 'default');
INSERT INTO `cc_options` VALUES (11, 'timezone', 'Asia/Jakarta');
INSERT INTO `cc_options` VALUES (12, 'google_id', '');
INSERT INTO `cc_options` VALUES (13, 'google_secret', '');

-- ----------------------------
-- Table structure for cc_session
-- ----------------------------
DROP TABLE IF EXISTS `cc_session`;
CREATE TABLE `cc_session`  (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `timestamp` int(10) NOT NULL,
  `data` blob NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for crud
-- ----------------------------
DROP TABLE IF EXISTS `crud`;
CREATE TABLE `crud`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `subject` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `table_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `primary_key` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `page_read` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `page_create` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `page_update` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for crud_custom_option
-- ----------------------------
DROP TABLE IF EXISTS `crud_custom_option`;
CREATE TABLE `crud_custom_option`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `crud_field_id` int(11) NOT NULL,
  `crud_id` int(11) NOT NULL,
  `option_value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `option_label` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for crud_field
-- ----------------------------
DROP TABLE IF EXISTS `crud_field`;
CREATE TABLE `crud_field`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `crud_id` int(11) NOT NULL,
  `field_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `field_label` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `input_type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `show_column` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `show_add_form` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `show_update_form` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `show_detail_page` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `relation_table` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `relation_value` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `relation_label` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 407 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of crud_field
-- ----------------------------
INSERT INTO `crud_field` VALUES (396, 24, 'id_skpd', 'id_skpd', 'number', '', '', '', 'yes', 1, '', '', '');
INSERT INTO `crud_field` VALUES (397, 24, 'kode', 'kode', 'input', 'yes', 'yes', 'yes', 'yes', 2, '', '', '');
INSERT INTO `crud_field` VALUES (398, 24, 'nama', 'nama', 'input', 'yes', 'yes', 'yes', 'yes', 3, '', '', '');
INSERT INTO `crud_field` VALUES (399, 24, 'singkatan', 'singkatan', 'input', 'yes', 'yes', 'yes', 'yes', 4, '', '', '');
INSERT INTO `crud_field` VALUES (400, 24, 'nip_ka_satker', 'nip_ka_satker', 'input', 'yes', 'yes', 'yes', 'yes', 5, '', '', '');
INSERT INTO `crud_field` VALUES (401, 24, 'nm_ka_satker', 'nm_ka_satker', 'input', 'yes', 'yes', 'yes', 'yes', 6, '', '', '');
INSERT INTO `crud_field` VALUES (402, 24, 'jab_ka_satker', 'jab_ka_satker', 'input', 'yes', 'yes', 'yes', 'yes', 7, '', '', '');
INSERT INTO `crud_field` VALUES (403, 24, 'created_by', 'created_by', 'input', 'yes', 'yes', 'yes', 'yes', 8, '', '', '');
INSERT INTO `crud_field` VALUES (404, 24, 'creation_date', 'creation_date', 'datetime', 'yes', 'yes', 'yes', 'yes', 9, '', '', '');
INSERT INTO `crud_field` VALUES (405, 24, 'last_updated_by', 'last_updated_by', 'input', 'yes', 'yes', 'yes', 'yes', 10, '', '', '');
INSERT INTO `crud_field` VALUES (406, 24, 'last_updated_date', 'last_updated_date', 'datetime', 'yes', 'yes', 'yes', 'yes', 11, '', '', '');

-- ----------------------------
-- Table structure for crud_field_validation
-- ----------------------------
DROP TABLE IF EXISTS `crud_field_validation`;
CREATE TABLE `crud_field_validation`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `crud_field_id` int(11) NOT NULL,
  `crud_id` int(11) NOT NULL,
  `validation_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `validation_value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 625 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of crud_field_validation
-- ----------------------------
INSERT INTO `crud_field_validation` VALUES (1, 2, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (2, 2, 1, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (3, 3, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (4, 3, 1, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (5, 4, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (6, 4, 1, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (7, 5, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (8, 5, 1, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (9, 6, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (10, 6, 1, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (11, 7, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (12, 7, 1, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (13, 8, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (14, 8, 1, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (15, 9, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (16, 9, 1, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (17, 10, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (18, 10, 1, 'max_length', '1');
INSERT INTO `crud_field_validation` VALUES (19, 11, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (20, 12, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (21, 13, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (22, 13, 1, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (23, 14, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (24, 15, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (25, 15, 1, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (26, 16, 1, 'required', '');
INSERT INTO `crud_field_validation` VALUES (27, 18, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (28, 18, 2, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (29, 19, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (30, 19, 2, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (31, 20, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (32, 20, 2, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (33, 21, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (34, 21, 2, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (35, 22, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (36, 22, 2, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (37, 23, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (38, 23, 2, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (39, 24, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (40, 24, 2, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (41, 25, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (42, 25, 2, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (43, 26, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (44, 26, 2, 'max_length', '1');
INSERT INTO `crud_field_validation` VALUES (45, 27, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (46, 28, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (47, 29, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (48, 29, 2, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (49, 30, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (50, 31, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (51, 31, 2, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (52, 32, 2, 'required', '');
INSERT INTO `crud_field_validation` VALUES (53, 34, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (54, 34, 3, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (55, 35, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (56, 35, 3, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (57, 36, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (58, 36, 3, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (59, 37, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (60, 37, 3, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (61, 38, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (62, 38, 3, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (63, 39, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (64, 39, 3, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (65, 40, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (66, 40, 3, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (67, 41, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (68, 41, 3, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (69, 42, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (70, 42, 3, 'max_length', '1');
INSERT INTO `crud_field_validation` VALUES (71, 43, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (72, 44, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (73, 45, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (74, 45, 3, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (75, 46, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (76, 47, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (77, 47, 3, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (78, 48, 3, 'required', '');
INSERT INTO `crud_field_validation` VALUES (79, 50, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (80, 50, 4, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (81, 51, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (82, 51, 4, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (83, 52, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (84, 52, 4, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (85, 53, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (86, 53, 4, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (87, 54, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (88, 54, 4, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (89, 55, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (90, 55, 4, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (91, 56, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (92, 56, 4, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (93, 57, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (94, 57, 4, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (95, 58, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (96, 58, 4, 'max_length', '1');
INSERT INTO `crud_field_validation` VALUES (97, 59, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (98, 60, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (99, 61, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (100, 61, 4, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (101, 62, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (102, 63, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (103, 63, 4, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (104, 64, 4, 'required', '');
INSERT INTO `crud_field_validation` VALUES (131, 82, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (132, 82, 5, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (133, 83, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (134, 83, 5, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (135, 84, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (136, 84, 5, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (137, 85, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (138, 85, 5, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (139, 86, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (140, 86, 5, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (141, 87, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (142, 87, 5, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (143, 88, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (144, 88, 5, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (145, 89, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (146, 89, 5, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (147, 90, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (148, 90, 5, 'max_length', '1');
INSERT INTO `crud_field_validation` VALUES (149, 91, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (150, 92, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (151, 93, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (152, 93, 5, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (153, 94, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (154, 95, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (155, 95, 5, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (156, 96, 5, 'required', '');
INSERT INTO `crud_field_validation` VALUES (267, 186, 6, 'required', '');
INSERT INTO `crud_field_validation` VALUES (268, 186, 6, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (269, 187, 6, 'required', '');
INSERT INTO `crud_field_validation` VALUES (270, 187, 6, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (271, 188, 6, 'required', '');
INSERT INTO `crud_field_validation` VALUES (272, 188, 6, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (273, 189, 6, 'required', '');
INSERT INTO `crud_field_validation` VALUES (274, 189, 6, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (275, 190, 6, 'required', '');
INSERT INTO `crud_field_validation` VALUES (276, 190, 6, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (277, 191, 6, 'required', '');
INSERT INTO `crud_field_validation` VALUES (278, 191, 6, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (279, 197, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (280, 197, 7, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (281, 198, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (282, 198, 7, 'max_length', '5');
INSERT INTO `crud_field_validation` VALUES (283, 199, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (284, 199, 7, 'max_length', '2');
INSERT INTO `crud_field_validation` VALUES (285, 200, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (286, 200, 7, 'max_length', '255');
INSERT INTO `crud_field_validation` VALUES (287, 201, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (288, 201, 7, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (289, 202, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (290, 202, 7, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (291, 203, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (292, 203, 7, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (293, 204, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (294, 204, 7, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (295, 205, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (296, 206, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (297, 206, 7, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (298, 207, 7, 'required', '');
INSERT INTO `crud_field_validation` VALUES (299, 209, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (300, 209, 8, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (301, 210, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (302, 210, 8, 'max_length', '5');
INSERT INTO `crud_field_validation` VALUES (303, 211, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (304, 211, 8, 'max_length', '2');
INSERT INTO `crud_field_validation` VALUES (305, 212, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (306, 212, 8, 'max_length', '255');
INSERT INTO `crud_field_validation` VALUES (307, 213, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (308, 213, 8, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (309, 214, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (310, 214, 8, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (311, 215, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (312, 215, 8, 'max_length', '30');
INSERT INTO `crud_field_validation` VALUES (313, 216, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (314, 216, 8, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (315, 217, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (316, 218, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (317, 218, 8, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (318, 219, 8, 'required', '');
INSERT INTO `crud_field_validation` VALUES (319, 221, 9, 'required', '');
INSERT INTO `crud_field_validation` VALUES (320, 221, 9, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (321, 222, 9, 'required', '');
INSERT INTO `crud_field_validation` VALUES (322, 222, 9, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (323, 223, 9, 'required', '');
INSERT INTO `crud_field_validation` VALUES (324, 223, 9, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (325, 224, 9, 'required', '');
INSERT INTO `crud_field_validation` VALUES (326, 224, 9, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (327, 225, 9, 'required', '');
INSERT INTO `crud_field_validation` VALUES (328, 225, 9, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (329, 226, 9, 'required', '');
INSERT INTO `crud_field_validation` VALUES (330, 226, 9, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (331, 227, 9, 'required', '');
INSERT INTO `crud_field_validation` VALUES (332, 227, 9, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (333, 228, 9, 'required', '');
INSERT INTO `crud_field_validation` VALUES (334, 229, 9, 'required', '');
INSERT INTO `crud_field_validation` VALUES (335, 229, 9, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (336, 230, 9, 'required', '');
INSERT INTO `crud_field_validation` VALUES (337, 232, 10, 'required', '');
INSERT INTO `crud_field_validation` VALUES (338, 232, 10, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (339, 233, 10, 'required', '');
INSERT INTO `crud_field_validation` VALUES (340, 233, 10, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (341, 234, 10, 'required', '');
INSERT INTO `crud_field_validation` VALUES (342, 234, 10, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (343, 235, 10, 'required', '');
INSERT INTO `crud_field_validation` VALUES (344, 235, 10, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (345, 236, 10, 'required', '');
INSERT INTO `crud_field_validation` VALUES (346, 236, 10, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (347, 237, 10, 'required', '');
INSERT INTO `crud_field_validation` VALUES (348, 237, 10, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (349, 238, 10, 'required', '');
INSERT INTO `crud_field_validation` VALUES (350, 238, 10, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (351, 239, 10, 'required', '');
INSERT INTO `crud_field_validation` VALUES (352, 240, 10, 'required', '');
INSERT INTO `crud_field_validation` VALUES (353, 240, 10, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (354, 241, 10, 'required', '');
INSERT INTO `crud_field_validation` VALUES (355, 243, 11, 'required', '');
INSERT INTO `crud_field_validation` VALUES (356, 243, 11, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (357, 244, 11, 'required', '');
INSERT INTO `crud_field_validation` VALUES (358, 244, 11, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (359, 245, 11, 'required', '');
INSERT INTO `crud_field_validation` VALUES (360, 245, 11, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (361, 246, 11, 'required', '');
INSERT INTO `crud_field_validation` VALUES (362, 246, 11, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (363, 247, 11, 'required', '');
INSERT INTO `crud_field_validation` VALUES (364, 247, 11, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (365, 248, 11, 'required', '');
INSERT INTO `crud_field_validation` VALUES (366, 248, 11, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (367, 249, 11, 'required', '');
INSERT INTO `crud_field_validation` VALUES (368, 249, 11, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (369, 250, 11, 'required', '');
INSERT INTO `crud_field_validation` VALUES (370, 251, 11, 'required', '');
INSERT INTO `crud_field_validation` VALUES (371, 251, 11, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (372, 252, 11, 'required', '');
INSERT INTO `crud_field_validation` VALUES (373, 254, 12, 'required', '');
INSERT INTO `crud_field_validation` VALUES (374, 254, 12, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (375, 255, 12, 'required', '');
INSERT INTO `crud_field_validation` VALUES (376, 255, 12, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (377, 256, 12, 'required', '');
INSERT INTO `crud_field_validation` VALUES (378, 256, 12, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (379, 257, 12, 'required', '');
INSERT INTO `crud_field_validation` VALUES (380, 257, 12, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (381, 258, 12, 'required', '');
INSERT INTO `crud_field_validation` VALUES (382, 258, 12, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (383, 259, 12, 'required', '');
INSERT INTO `crud_field_validation` VALUES (384, 259, 12, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (385, 260, 12, 'required', '');
INSERT INTO `crud_field_validation` VALUES (386, 260, 12, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (387, 261, 12, 'required', '');
INSERT INTO `crud_field_validation` VALUES (388, 262, 12, 'required', '');
INSERT INTO `crud_field_validation` VALUES (389, 262, 12, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (390, 263, 12, 'required', '');
INSERT INTO `crud_field_validation` VALUES (391, 265, 13, 'required', '');
INSERT INTO `crud_field_validation` VALUES (392, 265, 13, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (393, 266, 13, 'required', '');
INSERT INTO `crud_field_validation` VALUES (394, 266, 13, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (395, 267, 13, 'required', '');
INSERT INTO `crud_field_validation` VALUES (396, 267, 13, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (397, 268, 13, 'required', '');
INSERT INTO `crud_field_validation` VALUES (398, 268, 13, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (399, 269, 13, 'required', '');
INSERT INTO `crud_field_validation` VALUES (400, 269, 13, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (401, 270, 13, 'required', '');
INSERT INTO `crud_field_validation` VALUES (402, 270, 13, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (403, 271, 13, 'required', '');
INSERT INTO `crud_field_validation` VALUES (404, 271, 13, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (405, 272, 13, 'required', '');
INSERT INTO `crud_field_validation` VALUES (406, 273, 13, 'required', '');
INSERT INTO `crud_field_validation` VALUES (407, 273, 13, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (408, 274, 13, 'required', '');
INSERT INTO `crud_field_validation` VALUES (409, 276, 14, 'required', '');
INSERT INTO `crud_field_validation` VALUES (410, 276, 14, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (411, 277, 14, 'required', '');
INSERT INTO `crud_field_validation` VALUES (412, 277, 14, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (413, 278, 14, 'required', '');
INSERT INTO `crud_field_validation` VALUES (414, 278, 14, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (415, 279, 14, 'required', '');
INSERT INTO `crud_field_validation` VALUES (416, 279, 14, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (417, 280, 14, 'required', '');
INSERT INTO `crud_field_validation` VALUES (418, 280, 14, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (419, 281, 14, 'required', '');
INSERT INTO `crud_field_validation` VALUES (420, 281, 14, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (421, 282, 14, 'required', '');
INSERT INTO `crud_field_validation` VALUES (422, 282, 14, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (423, 283, 14, 'required', '');
INSERT INTO `crud_field_validation` VALUES (424, 284, 14, 'required', '');
INSERT INTO `crud_field_validation` VALUES (425, 284, 14, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (426, 285, 14, 'required', '');
INSERT INTO `crud_field_validation` VALUES (427, 287, 15, 'required', '');
INSERT INTO `crud_field_validation` VALUES (428, 287, 15, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (429, 288, 15, 'required', '');
INSERT INTO `crud_field_validation` VALUES (430, 288, 15, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (431, 289, 15, 'required', '');
INSERT INTO `crud_field_validation` VALUES (432, 289, 15, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (433, 290, 15, 'required', '');
INSERT INTO `crud_field_validation` VALUES (434, 290, 15, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (435, 291, 15, 'required', '');
INSERT INTO `crud_field_validation` VALUES (436, 291, 15, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (437, 292, 15, 'required', '');
INSERT INTO `crud_field_validation` VALUES (438, 292, 15, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (439, 293, 15, 'required', '');
INSERT INTO `crud_field_validation` VALUES (440, 293, 15, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (441, 294, 15, 'required', '');
INSERT INTO `crud_field_validation` VALUES (442, 295, 15, 'required', '');
INSERT INTO `crud_field_validation` VALUES (443, 295, 15, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (444, 296, 15, 'required', '');
INSERT INTO `crud_field_validation` VALUES (445, 298, 16, 'required', '');
INSERT INTO `crud_field_validation` VALUES (446, 298, 16, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (447, 299, 16, 'required', '');
INSERT INTO `crud_field_validation` VALUES (448, 299, 16, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (449, 300, 16, 'required', '');
INSERT INTO `crud_field_validation` VALUES (450, 300, 16, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (451, 301, 16, 'required', '');
INSERT INTO `crud_field_validation` VALUES (452, 301, 16, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (453, 302, 16, 'required', '');
INSERT INTO `crud_field_validation` VALUES (454, 302, 16, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (455, 303, 16, 'required', '');
INSERT INTO `crud_field_validation` VALUES (456, 303, 16, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (457, 304, 16, 'required', '');
INSERT INTO `crud_field_validation` VALUES (458, 304, 16, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (459, 305, 16, 'required', '');
INSERT INTO `crud_field_validation` VALUES (460, 306, 16, 'required', '');
INSERT INTO `crud_field_validation` VALUES (461, 306, 16, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (462, 307, 16, 'required', '');
INSERT INTO `crud_field_validation` VALUES (463, 309, 17, 'required', '');
INSERT INTO `crud_field_validation` VALUES (464, 309, 17, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (465, 310, 17, 'required', '');
INSERT INTO `crud_field_validation` VALUES (466, 310, 17, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (467, 311, 17, 'required', '');
INSERT INTO `crud_field_validation` VALUES (468, 311, 17, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (469, 312, 17, 'required', '');
INSERT INTO `crud_field_validation` VALUES (470, 312, 17, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (471, 313, 17, 'required', '');
INSERT INTO `crud_field_validation` VALUES (472, 313, 17, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (473, 314, 17, 'required', '');
INSERT INTO `crud_field_validation` VALUES (474, 314, 17, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (475, 315, 17, 'required', '');
INSERT INTO `crud_field_validation` VALUES (476, 315, 17, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (477, 316, 17, 'required', '');
INSERT INTO `crud_field_validation` VALUES (478, 317, 17, 'required', '');
INSERT INTO `crud_field_validation` VALUES (479, 317, 17, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (480, 318, 17, 'required', '');
INSERT INTO `crud_field_validation` VALUES (481, 320, 18, 'required', '');
INSERT INTO `crud_field_validation` VALUES (482, 320, 18, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (483, 321, 18, 'required', '');
INSERT INTO `crud_field_validation` VALUES (484, 321, 18, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (485, 322, 18, 'required', '');
INSERT INTO `crud_field_validation` VALUES (486, 322, 18, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (487, 323, 18, 'required', '');
INSERT INTO `crud_field_validation` VALUES (488, 323, 18, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (489, 324, 18, 'required', '');
INSERT INTO `crud_field_validation` VALUES (490, 324, 18, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (491, 325, 18, 'required', '');
INSERT INTO `crud_field_validation` VALUES (492, 325, 18, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (493, 326, 18, 'required', '');
INSERT INTO `crud_field_validation` VALUES (494, 326, 18, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (495, 327, 18, 'required', '');
INSERT INTO `crud_field_validation` VALUES (496, 328, 18, 'required', '');
INSERT INTO `crud_field_validation` VALUES (497, 328, 18, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (498, 329, 18, 'required', '');
INSERT INTO `crud_field_validation` VALUES (499, 331, 19, 'required', '');
INSERT INTO `crud_field_validation` VALUES (500, 331, 19, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (501, 332, 19, 'required', '');
INSERT INTO `crud_field_validation` VALUES (502, 332, 19, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (503, 333, 19, 'required', '');
INSERT INTO `crud_field_validation` VALUES (504, 333, 19, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (505, 334, 19, 'required', '');
INSERT INTO `crud_field_validation` VALUES (506, 334, 19, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (507, 335, 19, 'required', '');
INSERT INTO `crud_field_validation` VALUES (508, 335, 19, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (509, 336, 19, 'required', '');
INSERT INTO `crud_field_validation` VALUES (510, 336, 19, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (511, 337, 19, 'required', '');
INSERT INTO `crud_field_validation` VALUES (512, 337, 19, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (513, 338, 19, 'required', '');
INSERT INTO `crud_field_validation` VALUES (514, 339, 19, 'required', '');
INSERT INTO `crud_field_validation` VALUES (515, 339, 19, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (516, 340, 19, 'required', '');
INSERT INTO `crud_field_validation` VALUES (517, 342, 20, 'required', '');
INSERT INTO `crud_field_validation` VALUES (518, 342, 20, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (519, 343, 20, 'required', '');
INSERT INTO `crud_field_validation` VALUES (520, 343, 20, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (521, 344, 20, 'required', '');
INSERT INTO `crud_field_validation` VALUES (522, 344, 20, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (523, 345, 20, 'required', '');
INSERT INTO `crud_field_validation` VALUES (524, 345, 20, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (525, 346, 20, 'required', '');
INSERT INTO `crud_field_validation` VALUES (526, 346, 20, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (527, 347, 20, 'required', '');
INSERT INTO `crud_field_validation` VALUES (528, 347, 20, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (529, 348, 20, 'required', '');
INSERT INTO `crud_field_validation` VALUES (530, 348, 20, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (531, 349, 20, 'required', '');
INSERT INTO `crud_field_validation` VALUES (532, 350, 20, 'required', '');
INSERT INTO `crud_field_validation` VALUES (533, 350, 20, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (534, 351, 20, 'required', '');
INSERT INTO `crud_field_validation` VALUES (535, 353, 21, 'required', '');
INSERT INTO `crud_field_validation` VALUES (536, 353, 21, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (537, 354, 21, 'required', '');
INSERT INTO `crud_field_validation` VALUES (538, 354, 21, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (539, 355, 21, 'required', '');
INSERT INTO `crud_field_validation` VALUES (540, 355, 21, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (541, 356, 21, 'required', '');
INSERT INTO `crud_field_validation` VALUES (542, 356, 21, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (543, 357, 21, 'required', '');
INSERT INTO `crud_field_validation` VALUES (544, 357, 21, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (545, 358, 21, 'required', '');
INSERT INTO `crud_field_validation` VALUES (546, 358, 21, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (547, 359, 21, 'required', '');
INSERT INTO `crud_field_validation` VALUES (548, 359, 21, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (549, 360, 21, 'required', '');
INSERT INTO `crud_field_validation` VALUES (550, 361, 21, 'required', '');
INSERT INTO `crud_field_validation` VALUES (551, 361, 21, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (552, 362, 21, 'required', '');
INSERT INTO `crud_field_validation` VALUES (553, 364, 22, 'required', '');
INSERT INTO `crud_field_validation` VALUES (554, 364, 22, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (555, 365, 22, 'required', '');
INSERT INTO `crud_field_validation` VALUES (556, 365, 22, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (557, 366, 22, 'required', '');
INSERT INTO `crud_field_validation` VALUES (558, 366, 22, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (559, 367, 22, 'required', '');
INSERT INTO `crud_field_validation` VALUES (560, 367, 22, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (561, 368, 22, 'required', '');
INSERT INTO `crud_field_validation` VALUES (562, 368, 22, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (563, 369, 22, 'required', '');
INSERT INTO `crud_field_validation` VALUES (564, 369, 22, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (565, 370, 22, 'required', '');
INSERT INTO `crud_field_validation` VALUES (566, 370, 22, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (567, 371, 22, 'required', '');
INSERT INTO `crud_field_validation` VALUES (568, 372, 22, 'required', '');
INSERT INTO `crud_field_validation` VALUES (569, 372, 22, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (570, 373, 22, 'required', '');
INSERT INTO `crud_field_validation` VALUES (589, 386, 23, 'required', '');
INSERT INTO `crud_field_validation` VALUES (590, 386, 23, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (591, 387, 23, 'required', '');
INSERT INTO `crud_field_validation` VALUES (592, 387, 23, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (593, 388, 23, 'required', '');
INSERT INTO `crud_field_validation` VALUES (594, 388, 23, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (595, 389, 23, 'required', '');
INSERT INTO `crud_field_validation` VALUES (596, 389, 23, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (597, 390, 23, 'required', '');
INSERT INTO `crud_field_validation` VALUES (598, 390, 23, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (599, 391, 23, 'required', '');
INSERT INTO `crud_field_validation` VALUES (600, 391, 23, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (601, 392, 23, 'required', '');
INSERT INTO `crud_field_validation` VALUES (602, 392, 23, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (603, 393, 23, 'required', '');
INSERT INTO `crud_field_validation` VALUES (604, 394, 23, 'required', '');
INSERT INTO `crud_field_validation` VALUES (605, 394, 23, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (606, 395, 23, 'required', '');
INSERT INTO `crud_field_validation` VALUES (607, 397, 24, 'required', '');
INSERT INTO `crud_field_validation` VALUES (608, 397, 24, 'max_length', '10');
INSERT INTO `crud_field_validation` VALUES (609, 398, 24, 'required', '');
INSERT INTO `crud_field_validation` VALUES (610, 398, 24, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (611, 399, 24, 'required', '');
INSERT INTO `crud_field_validation` VALUES (612, 399, 24, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (613, 400, 24, 'required', '');
INSERT INTO `crud_field_validation` VALUES (614, 400, 24, 'max_length', '19');
INSERT INTO `crud_field_validation` VALUES (615, 401, 24, 'required', '');
INSERT INTO `crud_field_validation` VALUES (616, 401, 24, 'max_length', '100');
INSERT INTO `crud_field_validation` VALUES (617, 402, 24, 'required', '');
INSERT INTO `crud_field_validation` VALUES (618, 402, 24, 'max_length', '200');
INSERT INTO `crud_field_validation` VALUES (619, 403, 24, 'required', '');
INSERT INTO `crud_field_validation` VALUES (620, 403, 24, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (621, 404, 24, 'required', '');
INSERT INTO `crud_field_validation` VALUES (622, 405, 24, 'required', '');
INSERT INTO `crud_field_validation` VALUES (623, 405, 24, 'max_length', '20');
INSERT INTO `crud_field_validation` VALUES (624, 406, 24, 'required', '');

-- ----------------------------
-- Table structure for crud_input_type
-- ----------------------------
DROP TABLE IF EXISTS `crud_input_type`;
CREATE TABLE `crud_input_type`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `relation` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `custom_value` int(11) NOT NULL,
  `validation_group` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of crud_input_type
-- ----------------------------
INSERT INTO `crud_input_type` VALUES (1, 'input', '0', 0, 'input');
INSERT INTO `crud_input_type` VALUES (2, 'textarea', '0', 0, 'text');
INSERT INTO `crud_input_type` VALUES (3, 'select', '1', 0, 'select');
INSERT INTO `crud_input_type` VALUES (4, 'editor_wysiwyg', '0', 0, 'editor');
INSERT INTO `crud_input_type` VALUES (5, 'password', '0', 0, 'password');
INSERT INTO `crud_input_type` VALUES (6, 'email', '0', 0, 'email');
INSERT INTO `crud_input_type` VALUES (7, 'address_map', '0', 0, 'address_map');
INSERT INTO `crud_input_type` VALUES (8, 'file', '0', 0, 'file');
INSERT INTO `crud_input_type` VALUES (9, 'file_multiple', '0', 0, 'file_multiple');
INSERT INTO `crud_input_type` VALUES (10, 'datetime', '0', 0, 'datetime');
INSERT INTO `crud_input_type` VALUES (11, 'date', '0', 0, 'date');
INSERT INTO `crud_input_type` VALUES (12, 'timestamp', '0', 0, 'timestamp');
INSERT INTO `crud_input_type` VALUES (13, 'number', '0', 0, 'number');
INSERT INTO `crud_input_type` VALUES (14, 'yes_no', '0', 0, 'yes_no');
INSERT INTO `crud_input_type` VALUES (15, 'time', '0', 0, 'time');
INSERT INTO `crud_input_type` VALUES (16, 'year', '0', 0, 'year');
INSERT INTO `crud_input_type` VALUES (17, 'select_multiple', '1', 0, 'select_multiple');
INSERT INTO `crud_input_type` VALUES (18, 'checkboxes', '1', 0, 'checkboxes');
INSERT INTO `crud_input_type` VALUES (19, 'options', '1', 0, 'options');
INSERT INTO `crud_input_type` VALUES (20, 'true_false', '0', 0, 'true_false');
INSERT INTO `crud_input_type` VALUES (21, 'current_user_username', '0', 0, 'user_username');
INSERT INTO `crud_input_type` VALUES (22, 'current_user_id', '0', 0, 'current_user_id');
INSERT INTO `crud_input_type` VALUES (23, 'custom_option', '0', 1, 'custom_option');
INSERT INTO `crud_input_type` VALUES (24, 'custom_checkbox', '0', 1, 'custom_checkbox');
INSERT INTO `crud_input_type` VALUES (25, 'custom_select_multiple', '0', 1, 'custom_select_multiple');
INSERT INTO `crud_input_type` VALUES (26, 'custom_select', '0', 1, 'custom_select');

-- ----------------------------
-- Table structure for crud_input_validation
-- ----------------------------
DROP TABLE IF EXISTS `crud_input_validation`;
CREATE TABLE `crud_input_validation`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `validation` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `input_able` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `group_input` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `input_placeholder` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `call_back` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `input_validation` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 38 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of crud_input_validation
-- ----------------------------
INSERT INTO `crud_input_validation` VALUES (1, 'required', 'no', 'input, file, number, text, datetime, select, password, email, editor, date, yes_no, time, year, select_multiple, options, checkboxes, true_false, address_map, custom_option, custom_checkbox, custom_select_multiple, custom_select, file_multiple', '', '', '');
INSERT INTO `crud_input_validation` VALUES (2, 'max_length', 'yes', 'input, number, text, select, password, email, editor, yes_no, time, year, select_multiple, options, checkboxes, address_map', '', '', 'numeric');
INSERT INTO `crud_input_validation` VALUES (3, 'min_length', 'yes', 'input, number, text, select, password, email, editor, time, year, select_multiple, address_map', '', '', 'numeric');
INSERT INTO `crud_input_validation` VALUES (4, 'valid_email', 'no', 'input, email', '', '', '');
INSERT INTO `crud_input_validation` VALUES (5, 'valid_emails', 'no', 'input, email', '', '', '');
INSERT INTO `crud_input_validation` VALUES (6, 'regex', 'yes', 'input, number, text, datetime, select, password, email, editor, date, yes_no, time, year, select_multiple, options, checkboxes', '', 'yes', 'callback_valid_regex');
INSERT INTO `crud_input_validation` VALUES (7, 'decimal', 'no', 'input, number, text, select', '', '', '');
INSERT INTO `crud_input_validation` VALUES (8, 'allowed_extension', 'yes', 'file, file_multiple', 'ex : jpg,png,..', '', 'callback_valid_extension_list');
INSERT INTO `crud_input_validation` VALUES (9, 'max_width', 'yes', 'file, file_multiple', '', '', 'numeric');
INSERT INTO `crud_input_validation` VALUES (10, 'max_height', 'yes', 'file, file_multiple', '', '', 'numeric');
INSERT INTO `crud_input_validation` VALUES (11, 'max_size', 'yes', 'file, file_multiple', '... kb', '', 'numeric');
INSERT INTO `crud_input_validation` VALUES (12, 'max_item', 'yes', 'file_multiple', '', '', 'numeric');
INSERT INTO `crud_input_validation` VALUES (13, 'valid_url', 'no', 'input, text', '', '', '');
INSERT INTO `crud_input_validation` VALUES (14, 'alpha', 'no', 'input, text, select, password, editor, yes_no', '', '', '');
INSERT INTO `crud_input_validation` VALUES (15, 'alpha_numeric', 'no', 'input, number, text, select, password, editor', '', '', '');
INSERT INTO `crud_input_validation` VALUES (16, 'alpha_numeric_spaces', 'no', 'input, number, text,select, password, editor', '', '', '');
INSERT INTO `crud_input_validation` VALUES (17, 'valid_number', 'no', 'input, number, text, password, editor, true_false', '', 'yes', '');
INSERT INTO `crud_input_validation` VALUES (18, 'valid_datetime', 'no', 'input, datetime, text', '', 'yes', '');
INSERT INTO `crud_input_validation` VALUES (19, 'valid_date', 'no', 'input, datetime, date, text', '', 'yes', '');
INSERT INTO `crud_input_validation` VALUES (20, 'valid_max_selected_option', 'yes', 'select_multiple, custom_select_multiple, custom_checkbox, checkboxes', '', 'yes', 'numeric');
INSERT INTO `crud_input_validation` VALUES (21, 'valid_min_selected_option', 'yes', 'select_multiple, custom_select_multiple, custom_checkbox, checkboxes', '', 'yes', 'numeric');
INSERT INTO `crud_input_validation` VALUES (22, 'valid_alpha_numeric_spaces_underscores', 'no', 'input, text,select, password, editor', '', 'yes', '');
INSERT INTO `crud_input_validation` VALUES (23, 'matches', 'yes', 'input, number, text, password, email', 'any field', 'no', 'callback_valid_alpha_numeric_spaces_underscores');
INSERT INTO `crud_input_validation` VALUES (24, 'valid_json', 'no', 'input, text, editor', '', 'yes', ' ');
INSERT INTO `crud_input_validation` VALUES (25, 'valid_url', 'no', 'input, text, editor', '', 'no', ' ');
INSERT INTO `crud_input_validation` VALUES (26, 'exact_length', 'yes', 'input, text, number', '0 - 99999*', 'no', 'numeric');
INSERT INTO `crud_input_validation` VALUES (27, 'alpha_dash', 'no', 'input, text', '', 'no', '');
INSERT INTO `crud_input_validation` VALUES (28, 'integer', 'no', 'input, text, number', '', 'no', '');
INSERT INTO `crud_input_validation` VALUES (29, 'differs', 'yes', 'input, text, number, email, password, editor, options, select', 'any field', 'no', 'callback_valid_alpha_numeric_spaces_underscores');
INSERT INTO `crud_input_validation` VALUES (30, 'is_natural', 'no', 'input, text, number', '', 'no', '');
INSERT INTO `crud_input_validation` VALUES (31, 'is_natural_no_zero', 'no', 'input, text, number', '', 'no', '');
INSERT INTO `crud_input_validation` VALUES (32, 'less_than', 'yes', 'input, text, number', '', 'no', 'numeric');
INSERT INTO `crud_input_validation` VALUES (33, 'less_than_equal_to', 'yes', 'input, text, number', '', 'no', 'numeric');
INSERT INTO `crud_input_validation` VALUES (34, 'greater_than', 'yes', 'input, text, number', '', 'no', 'numeric');
INSERT INTO `crud_input_validation` VALUES (35, 'greater_than_equal_to', 'yes', 'input, text, number', '', 'no', 'numeric');
INSERT INTO `crud_input_validation` VALUES (36, 'in_list', 'yes', 'input, text, number, select, options', '', 'no', 'callback_valid_multiple_value');
INSERT INTO `crud_input_validation` VALUES (37, 'valid_ip', 'no', 'input, text', '', 'no', '');

-- ----------------------------
-- Table structure for keys
-- ----------------------------
DROP TABLE IF EXISTS `keys`;
CREATE TABLE `keys`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL,
  `is_private_key` tinyint(1) NOT NULL,
  `ip_addresses` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `date_created` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of keys
-- ----------------------------
INSERT INTO `keys` VALUES (1, 0, 'E611F398D9D925F00053EF4D39FD94DE', 0, 0, 0, NULL, '2019-08-03 01:11:22');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `label` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `icon_color` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `link` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `menu_type_id` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1, 'MAIN NAVIGATION', 'label', '', 'administrator/dashboard', 1, 0, '', 1, 1);
INSERT INTO `menu` VALUES (2, 'Dashboard', 'menu', 'default', 'administrator/dashboard', 3, 23, 'fa-dashboard', 1, 0);
INSERT INTO `menu` VALUES (3, 'TMC CRUD', 'menu', 'default', 'administrator/crud', 4, 23, 'fa-table', 1, 1);
INSERT INTO `menu` VALUES (4, 'TMC API Create', 'menu', 'default', 'administrator/rest', 5, 23, 'fa-code', 1, 1);
INSERT INTO `menu` VALUES (8, 'Menu', 'menu', '', 'administrator/menu', 6, 23, 'fa-bars', 1, 1);
INSERT INTO `menu` VALUES (9, 'Auth', 'menu', '', '', 7, 23, 'fa-shield', 1, 1);
INSERT INTO `menu` VALUES (10, 'User', 'menu', '', 'administrator/user', 8, 9, '', 1, 1);
INSERT INTO `menu` VALUES (11, 'Groups', 'menu', '', 'administrator/group', 9, 9, '', 1, 1);
INSERT INTO `menu` VALUES (12, 'Access', 'menu', '', 'administrator/access', 10, 9, '', 1, 1);
INSERT INTO `menu` VALUES (13, 'Permission', 'menu', '', 'administrator/permission', 11, 9, '', 1, 1);
INSERT INTO `menu` VALUES (14, 'API Keys', 'menu', '', 'administrator/keys', 12, 9, '', 1, 1);
INSERT INTO `menu` VALUES (15, 'Extension', 'menu', '', 'administrator/extension', 13, 23, 'fa-puzzle-piece', 1, 1);
INSERT INTO `menu` VALUES (17, 'Settings', 'menu', 'text-red', 'administrator/setting', 14, 23, 'fa-circle-o', 1, 1);
INSERT INTO `menu` VALUES (20, 'Home', 'menu', '', '/', 1, 0, '', 2, 1);
INSERT INTO `menu` VALUES (21, 'Blog', 'menu', '', 'blog', 4, 0, '', 2, 1);
INSERT INTO `menu` VALUES (22, 'Dashboard', 'menu', '', 'administrator/dashboard', 5, 0, '', 2, 1);
INSERT INTO `menu` VALUES (23, 'ADMINISTRATOR', 'menu', 'default', '#', 2, 0, 'fa-amazon', 1, 1);
INSERT INTO `menu` VALUES (27, 'MASTER DATA', 'menu', 'text-red', '#', 15, 0, 'fa-get-pocket', 1, 1);
INSERT INTO `menu` VALUES (28, 'Profil', 'menu', 'default', 'profile', 16, 27, '', 1, 1);

-- ----------------------------
-- Table structure for menu_type
-- ----------------------------
DROP TABLE IF EXISTS `menu_type`;
CREATE TABLE `menu_type`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `definition` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu_type
-- ----------------------------
INSERT INTO `menu_type` VALUES (1, 'side menu', NULL);
INSERT INTO `menu_type` VALUES (2, 'top menu', NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `version` bigint(20) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1);

-- ----------------------------
-- Table structure for page
-- ----------------------------
DROP TABLE IF EXISTS `page`;
CREATE TABLE `page`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `fresh_content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `keyword` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `link` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `template` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for page_block_element
-- ----------------------------
DROP TABLE IF EXISTS `page_block_element`;
CREATE TABLE `page_block_element`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `image_preview` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `block_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content_type` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rest
-- ----------------------------
DROP TABLE IF EXISTS `rest`;
CREATE TABLE `rest`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `table_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `primary_key` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `x_api_key` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `x_token` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rest_field
-- ----------------------------
DROP TABLE IF EXISTS `rest_field`;
CREATE TABLE `rest_field`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rest_id` int(11) NOT NULL,
  `field_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `field_label` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `input_type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `show_column` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `show_add_api` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `show_update_api` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `show_detail_api` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rest_field_validation
-- ----------------------------
DROP TABLE IF EXISTS `rest_field_validation`;
CREATE TABLE `rest_field_validation`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rest_field_id` int(11) NOT NULL,
  `rest_id` int(11) NOT NULL,
  `validation_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `validation_value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for rest_input_type
-- ----------------------------
DROP TABLE IF EXISTS `rest_input_type`;
CREATE TABLE `rest_input_type`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `relation` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `validation_group` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rest_input_type
-- ----------------------------
INSERT INTO `rest_input_type` VALUES (1, 'input', '0', 'input');
INSERT INTO `rest_input_type` VALUES (2, 'timestamp', '0', 'timestamp');
INSERT INTO `rest_input_type` VALUES (3, 'file', '0', 'file');

SET FOREIGN_KEY_CHECKS = 1;

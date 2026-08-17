-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 31 Okt 2022 pada 09.22
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `silaris`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_groups`
--

CREATE TABLE `aauth_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `definition` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `aauth_groups`
--

INSERT INTO `aauth_groups` (`id`, `name`, `definition`) VALUES
(1, 'Admin', 'Superadmin Group'),
(3, 'User', 'User Access Group'),
(7, 'PIMTI', 'Pimpinan Tinggi'),
(8, 'Kakanwil', 'Kepala Kantor Wilayah');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_group_to_group`
--

CREATE TABLE `aauth_group_to_group` (
  `group_id` int(11) UNSIGNED NOT NULL,
  `subgroup_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_login_attempts`
--

CREATE TABLE `aauth_login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(39) DEFAULT NULL,
  `timestamp` datetime DEFAULT NULL,
  `login_attempts` tinyint(2) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `aauth_login_attempts`
--

INSERT INTO `aauth_login_attempts` (`id`, `ip_address`, `timestamp`, `login_attempts`) VALUES
(127, '::1', '2019-08-11 22:36:54', 3),
(129, '::1', '2019-10-08 19:56:50', 1),
(138, '::1', '2019-11-01 00:18:54', 1),
(140, '::1', '2019-11-01 00:40:46', 3),
(151, '182.1.209.86', '2019-11-05 22:26:24', 2),
(152, '173.3.2.184', '2019-11-05 22:44:05', 3),
(156, '182.1.195.67', '2019-11-09 11:34:33', 1),
(157, '182.1.209.43', '2019-11-09 11:35:58', 1),
(158, '182.1.195.85', '2019-11-09 11:37:50', 1),
(159, '182.1.192.91', '2019-11-09 11:37:59', 1),
(160, '182.1.209.43', '2019-11-09 11:41:59', 1),
(171, '173.3.1.49', '2019-11-14 07:17:58', 1),
(172, '180.244.34.48', '2019-11-14 07:29:30', 2),
(173, '114.125.185.137', '2019-11-14 07:29:52', 1),
(174, '202.67.36.219', '2019-11-14 07:37:58', 4),
(177, '114.125.169.240', '2019-11-14 08:04:15', 2),
(178, '114.125.169.200', '2019-11-14 08:02:05', 1),
(180, '114.125.169.152', '2019-11-14 08:03:13', 1),
(182, '180.249.172.111', '2019-11-14 09:05:48', 13),
(183, '110.136.241.182', '2019-11-14 10:51:49', 1),
(185, '114.124.213.191', '2019-11-14 22:04:27', 1),
(186, '114.124.244.47', '2019-11-14 22:09:47', 3),
(191, '114.125.167.63', '2019-11-15 03:42:01', 4),
(192, '172.2.30.150', '2019-11-15 03:52:52', 3),
(193, '114.125.184.211', '2019-11-15 03:54:11', 1),
(194, '114.125.184.12', '2019-11-15 03:54:29', 2),
(200, '182.1.199.255', '2019-11-15 23:15:11', 1),
(212, '114.125.207.59', '2019-11-16 12:35:39', 5),
(220, '36.75.142.94', '2019-11-21 10:19:29', 1),
(226, '36.79.135.88', '2019-11-27 03:13:16', 3),
(231, '174.3.2.202', '2019-11-28 22:30:04', 4),
(235, '172.2.22.200', '2019-11-29 01:04:29', 7),
(242, '173.3.0.33', '2019-12-10 02:14:15', 4),
(243, '173.3.0.33', '2019-12-10 02:41:48', 1),
(244, '36.75.140.223', '2019-12-12 10:08:54', 5),
(245, '168.235.205.140', '2019-12-12 18:18:48', 1),
(248, '36.83.103.116', '2019-12-13 06:40:06', 2),
(255, '180.245.174.171', '2019-12-13 08:02:35', 2),
(258, '110.136.243.179', '2019-12-13 08:42:10', 1),
(267, '182.1.177.113', '2019-12-13 09:46:34', 1),
(268, '182.1.178.224', '2019-12-13 09:46:53', 1),
(276, '182.1.193.4', '2019-12-13 18:00:11', 1),
(282, '103.3.221.96', '2019-12-16 06:04:27', 2),
(283, '114.79.38.74', '2019-12-16 15:10:30', 18),
(292, '180.249.0.230', '2019-12-21 02:05:48', 1),
(293, '180.249.0.230', '2019-12-21 02:19:29', 11),
(294, '182.1.162.118', '2019-12-21 22:20:19', 1),
(296, '36.80.13.240', '2019-12-24 00:01:58', 1),
(297, '173.3.0.136', '2019-12-24 00:19:59', 17),
(298, '120.188.79.4', '2019-12-24 00:20:39', 4),
(301, '173.3.0.136', '2019-12-24 06:01:25', 1),
(302, '114.125.199.64', '2019-12-25 06:30:21', 3),
(304, '180.254.189.234', '2019-12-27 00:29:40', 1),
(305, '180.254.189.234', '2019-12-27 00:40:09', 3),
(307, '120.188.82.251', '2019-12-27 02:57:13', 2),
(321, '114.125.186.181', '2019-12-27 07:42:06', 1),
(322, '114.125.165.170', '2019-12-27 07:44:06', 2),
(323, '114.125.187.180', '2019-12-27 07:46:14', 3),
(330, '114.4.220.230', '2019-12-27 08:08:31', 30),
(333, '36.79.132.26', '2019-12-27 08:01:32', 1),
(334, '180.249.1.165', '2019-12-27 08:01:40', 1),
(343, '182.1.211.44', '2019-12-27 08:06:38', 2),
(346, '182.1.213.214', '2019-12-27 08:17:39', 2),
(350, '182.1.198.144', '2019-12-27 08:18:16', 1),
(351, '173.3.0.94', '2019-12-27 08:21:37', 3),
(356, '114.79.38.11', '2019-12-27 08:26:08', 3),
(357, '114.125.205.40', '2019-12-27 08:24:00', 1),
(359, '114.125.220.109', '2019-12-27 08:25:02', 1),
(361, '180.249.1.165', '2019-12-27 08:30:22', 3),
(368, '114.125.164.4', '2019-12-27 08:37:07', 1),
(369, '114.125.223.18', '2019-12-27 08:38:52', 5),
(373, '173.3.2.155', '2019-12-27 08:47:49', 8),
(377, '114.125.223.231', '2019-12-27 09:10:55', 1),
(379, '173.3.2.124', '2019-12-27 09:21:41', 1),
(388, '182.1.198.117', '2019-12-27 10:05:14', 4),
(389, '114.125.168.253', '2019-12-27 10:16:50', 1),
(399, '182.1.179.32', '2019-12-27 16:18:56', 1),
(408, '173.3.2.108', '2020-01-01 06:57:17', 3),
(409, '114.125.204.161', '2020-01-02 13:36:06', 1),
(424, '36.83.102.102', '2020-01-03 05:20:29', 2),
(425, '36.83.102.102', '2020-01-03 05:28:16', 1),
(426, '114.5.243.110', '2020-01-03 05:42:43', 7),
(427, '174.3.2.202', '2020-01-03 05:44:35', 2),
(438, '114.125.206.53', '2020-01-03 07:30:35', 1),
(440, '182.1.161.70', '2020-01-03 09:05:50', 4),
(442, '172.2.15.3', '2020-01-14 22:13:31', 1),
(457, '114.125.221.217', '2020-01-15 01:39:31', 3),
(459, '114.125.223.97', '2020-01-15 01:40:10', 1),
(463, '182.1.209.80', '2020-01-15 02:04:48', 1),
(464, '182.1.195.209', '2020-01-15 02:05:40', 1),
(466, '174.3.2.202', '2020-01-15 02:44:02', 1),
(470, '182.1.208.244', '2020-01-15 04:33:23', 1),
(472, '182.1.192.101', '2020-01-15 04:40:24', 1),
(473, '182.1.208.79', '2020-01-15 04:41:45', 1),
(474, '114.125.199.37', '2020-01-15 04:41:50', 1),
(477, '182.1.176.179', '2020-01-15 04:55:47', 1),
(480, '182.1.163.248', '2020-01-15 04:58:29', 1),
(487, '182.1.178.100', '2020-01-15 05:45:26', 1),
(488, '182.1.163.95', '2020-01-15 05:48:12', 4),
(498, '182.1.209.222', '2020-01-15 06:36:59', 2),
(505, '180.254.186.45', '2020-01-15 07:08:28', 1),
(515, '202.67.36.10', '2020-01-15 07:45:53', 2),
(524, '182.1.192.89', '2020-01-15 08:09:14', 1),
(532, '114.125.184.120', '2020-01-14 16:39:38', 1),
(534, '36.83.108.25', '2020-01-15 09:11:29', 2),
(541, '36.75.143.234', '2020-01-15 10:40:00', 1),
(553, '180.254.191.186', '2020-01-15 12:37:04', 1),
(574, '173.3.1.114', '2020-01-15 22:57:21', 5),
(595, '114.125.164.26', '2020-01-16 01:40:18', 1),
(596, '114.125.164.34', '2020-01-16 01:40:43', 1),
(597, '114.125.164.21', '2020-01-16 01:40:53', 1),
(598, '114.125.164.32', '2020-01-16 01:41:06', 1),
(599, '114.125.164.25', '2020-01-16 01:41:40', 1),
(710, '173.3.1.114', '2020-01-17 00:23:53', 5),
(742, '182.1.211.129', '2020-01-17 03:30:44', 1),
(753, '114.5.102.225', '2020-01-17 04:59:02', 5),
(755, '114.5.102.225', '2020-01-17 05:05:12', 2),
(768, '114.125.215.90', '2020-01-17 06:38:11', 1),
(769, '114.125.199.147', '2020-01-17 06:38:39', 1),
(773, '114.79.38.137', '2020-01-17 07:51:51', 1),
(775, '::1', '2020-01-20 13:03:20', 1),
(784, '36.75.254.23', '2020-07-02 11:23:16', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_perms`
--

CREATE TABLE `aauth_perms` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `definition` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `aauth_perms`
--

INSERT INTO `aauth_perms` (`id`, `name`, `definition`) VALUES
(1, 'menu_dashboard', NULL),
(2, 'menu_crud_builder', NULL),
(3, 'menu_api_builder', NULL),
(4, 'menu_page_builder', NULL),
(5, 'menu_form_builder', NULL),
(6, 'menu_menu', NULL),
(7, 'menu_auth', NULL),
(8, 'menu_user', NULL),
(9, 'menu_group', NULL),
(10, 'menu_access', NULL),
(11, 'menu_permission', NULL),
(12, 'menu_api_documentation', NULL),
(13, 'menu_web_documentation', NULL),
(14, 'menu_settings', NULL),
(15, 'user_list', NULL),
(16, 'user_update_status', NULL),
(17, 'user_export', NULL),
(18, 'user_add', NULL),
(19, 'user_update', NULL),
(20, 'user_update_profile', NULL),
(21, 'user_update_password', NULL),
(22, 'user_profile', NULL),
(23, 'user_view', NULL),
(24, 'user_delete', NULL),
(25, 'blog_list', NULL),
(26, 'blog_export', NULL),
(27, 'blog_add', NULL),
(28, 'blog_update', NULL),
(29, 'blog_view', NULL),
(30, 'blog_delete', NULL),
(31, 'form_list', NULL),
(32, 'form_export', NULL),
(33, 'form_add', NULL),
(34, 'form_update', NULL),
(35, 'form_view', NULL),
(36, 'form_manage', NULL),
(37, 'form_delete', NULL),
(38, 'crud_list', NULL),
(39, 'crud_export', NULL),
(40, 'crud_add', NULL),
(41, 'crud_update', NULL),
(42, 'crud_view', NULL),
(43, 'crud_delete', NULL),
(44, 'rest_list', NULL),
(45, 'rest_export', NULL),
(46, 'rest_add', NULL),
(47, 'rest_update', NULL),
(48, 'rest_view', NULL),
(49, 'rest_delete', NULL),
(50, 'group_list', NULL),
(51, 'group_export', NULL),
(52, 'group_add', NULL),
(53, 'group_update', NULL),
(54, 'group_view', NULL),
(55, 'group_delete', NULL),
(56, 'permission_list', NULL),
(57, 'permission_export', NULL),
(58, 'permission_add', NULL),
(59, 'permission_update', NULL),
(60, 'permission_view', NULL),
(61, 'permission_delete', NULL),
(62, 'access_list', NULL),
(63, 'access_add', NULL),
(64, 'access_update', NULL),
(65, 'menu_list', NULL),
(66, 'menu_add', NULL),
(67, 'menu_update', NULL),
(68, 'menu_delete', NULL),
(69, 'menu_save_ordering', NULL),
(70, 'menu_type_add', NULL),
(71, 'page_list', NULL),
(72, 'page_export', NULL),
(73, 'page_add', NULL),
(74, 'page_update', NULL),
(75, 'page_view', NULL),
(76, 'page_delete', NULL),
(77, 'blog_list', NULL),
(78, 'blog_export', NULL),
(79, 'blog_add', NULL),
(80, 'blog_update', NULL),
(81, 'blog_view', NULL),
(82, 'blog_delete', NULL),
(83, 'setting', NULL),
(84, 'setting_update', NULL),
(85, 'dashboard', NULL),
(86, 'extension_list', NULL),
(87, 'extension_activate', NULL),
(88, 'extension_deactivate', NULL),
(99, 'menu_administrator', ''),
(120, 'menu_master_data', ''),
(122, 'menu_profil', ''),
(128, 'menu_tmc_crud', ''),
(129, 'menu_tmc_api_create', ''),
(756, 'wilayah_add', ''),
(757, 'wilayah_update', ''),
(758, 'wilayah_view', ''),
(759, 'wilayah_delete', ''),
(760, 'wilayah_list', ''),
(1005, 'reportorium_add', ''),
(1006, 'reportorium_update', ''),
(1007, 'reportorium_view', ''),
(1008, 'reportorium_delete', ''),
(1009, 'reportorium_list', ''),
(1010, 'legalisasi_add', ''),
(1011, 'legalisasi_update', ''),
(1012, 'legalisasi_view', ''),
(1013, 'legalisasi_delete', ''),
(1014, 'legalisasi_list', ''),
(1015, 'laporan_bulanan_add', ''),
(1016, 'laporan_bulanan_update', ''),
(1017, 'laporan_bulanan_view', ''),
(1018, 'laporan_bulanan_delete', ''),
(1019, 'laporan_bulanan_list', ''),
(1020, 'data_notaris_add', ''),
(1021, 'data_notaris_update', ''),
(1022, 'data_notaris_view', ''),
(1023, 'data_notaris_delete', ''),
(1024, 'data_notaris_list', ''),
(1025, 'daftar_proses_add', ''),
(1026, 'daftar_proses_update', ''),
(1027, 'daftar_proses_view', ''),
(1028, 'daftar_proses_delete', ''),
(1029, 'daftar_proses_list', ''),
(1030, 'waarmerking_add', ''),
(1031, 'waarmerking_update', ''),
(1032, 'waarmerking_view', ''),
(1033, 'waarmerking_delete', ''),
(1034, 'waarmerking_list', ''),
(1035, 'wil_add', ''),
(1036, 'wil_update', ''),
(1037, 'wil_view', ''),
(1038, 'wil_delete', ''),
(1039, 'wil_list', ''),
(1040, 'menu_setup_wilayah', ''),
(1041, 'menu_data_notaris', ''),
(1042, 'menu_laporan', ''),
(1043, 'menu_laporan_bulanan', ''),
(1044, 'menu_reportorium', ''),
(1045, 'menu_daftar_protes', ''),
(1046, 'menu_legalisasi', ''),
(1047, 'menu_waarmerking', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_perm_to_group`
--

CREATE TABLE `aauth_perm_to_group` (
  `perm_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `aauth_perm_to_group`
--

INSERT INTO `aauth_perm_to_group` (`perm_id`, `group_id`) VALUES
(239, 0),
(99, 1),
(120, 1),
(121, 1),
(122, 1),
(553, 1),
(561, 1),
(567, 1),
(598, 1),
(604, 1),
(615, 1),
(616, 1),
(622, 1),
(633, 1),
(644, 1),
(650, 1),
(656, 1),
(657, 1),
(658, 1),
(659, 1),
(660, 1),
(85, 1),
(1, 1),
(661, 1),
(713, 1),
(719, 1),
(743, 1),
(749, 1),
(753, 1),
(743, 1),
(754, 1),
(776, 1),
(782, 1),
(804, 1),
(805, 1),
(816, 1),
(824, 1),
(840, 1),
(120, 4),
(122, 4),
(781, 4),
(782, 4),
(783, 4),
(784, 4),
(795, 4),
(804, 4),
(805, 4),
(816, 4),
(822, 4),
(823, 4),
(824, 4),
(825, 4),
(840, 4),
(848, 4),
(20, 4),
(21, 4),
(22, 4),
(808, 4),
(810, 4),
(813, 4),
(815, 4),
(819, 4),
(821, 4),
(826, 4),
(827, 4),
(828, 4),
(829, 4),
(830, 4),
(831, 4),
(832, 4),
(833, 4),
(834, 4),
(120, 2),
(122, 2),
(781, 2),
(782, 2),
(783, 2),
(784, 2),
(785, 2),
(787, 2),
(788, 2),
(789, 2),
(795, 2),
(804, 2),
(805, 2),
(816, 2),
(824, 2),
(825, 2),
(840, 2),
(846, 2),
(847, 2),
(848, 2),
(22, 2),
(756, 2),
(757, 2),
(758, 2),
(759, 2),
(760, 2),
(766, 2),
(767, 2),
(768, 2),
(769, 2),
(770, 2),
(771, 2),
(772, 2),
(773, 2),
(774, 2),
(775, 2),
(790, 2),
(791, 2),
(792, 2),
(793, 2),
(794, 2),
(761, 2),
(762, 2),
(763, 2),
(764, 2),
(765, 2),
(776, 2),
(777, 2),
(778, 2),
(779, 2),
(780, 2),
(796, 2),
(797, 2),
(798, 2),
(799, 2),
(800, 2),
(808, 2),
(810, 2),
(813, 2),
(815, 2),
(819, 2),
(821, 2),
(826, 2),
(829, 2),
(830, 2),
(832, 2),
(834, 2),
(837, 2),
(838, 2),
(839, 2),
(841, 2),
(842, 2),
(843, 2),
(844, 2),
(845, 2),
(785, 2),
(867, 0),
(868, 0),
(849, 1),
(849, 2),
(849, 4),
(801, 1),
(801, 2),
(802, 1),
(802, 2),
(952, 1),
(953, 0),
(953, 1),
(959, 1),
(971, 1),
(972, 0),
(972, 1),
(981, 0),
(1, 5),
(2, 5),
(3, 5),
(4, 5),
(5, 5),
(6, 5),
(7, 5),
(9, 5),
(10, 5),
(11, 5),
(12, 5),
(13, 5),
(14, 5),
(65, 5),
(66, 5),
(67, 5),
(68, 5),
(69, 5),
(70, 5),
(120, 5),
(122, 5),
(128, 5),
(129, 5),
(781, 5),
(782, 5),
(783, 5),
(784, 5),
(785, 5),
(786, 5),
(787, 5),
(788, 5),
(789, 5),
(795, 5),
(801, 5),
(802, 5),
(803, 5),
(804, 5),
(805, 5),
(816, 5),
(822, 5),
(823, 5),
(824, 5),
(825, 5),
(840, 5),
(846, 5),
(847, 5),
(848, 5),
(849, 5),
(850, 5),
(851, 5),
(861, 5),
(862, 5),
(863, 5),
(864, 5),
(865, 5),
(866, 5),
(867, 5),
(868, 5),
(869, 5),
(870, 5),
(896, 5),
(919, 5),
(920, 5),
(921, 5),
(936, 5),
(940, 5),
(952, 5),
(953, 5),
(959, 5),
(960, 5),
(971, 5),
(972, 5),
(978, 5),
(979, 5),
(980, 5),
(981, 5),
(993, 5),
(999, 5),
(15, 5),
(16, 5),
(17, 5),
(18, 5),
(19, 5),
(20, 5),
(21, 5),
(22, 5),
(23, 5),
(24, 5),
(44, 5),
(45, 5),
(46, 5),
(47, 5),
(48, 5),
(49, 5),
(50, 5),
(51, 5),
(52, 5),
(53, 5),
(54, 5),
(55, 5),
(56, 5),
(57, 5),
(58, 5),
(59, 5),
(60, 5),
(61, 5),
(62, 5),
(63, 5),
(64, 5),
(71, 5),
(72, 5),
(73, 5),
(74, 5),
(75, 5),
(76, 5),
(85, 5),
(86, 5),
(87, 5),
(88, 5),
(796, 5),
(797, 5),
(798, 5),
(799, 5),
(800, 5),
(806, 5),
(807, 5),
(808, 5),
(809, 5),
(810, 5),
(811, 5),
(812, 5),
(813, 5),
(814, 5),
(815, 5),
(817, 5),
(818, 5),
(819, 5),
(820, 5),
(821, 5),
(826, 5),
(827, 5),
(828, 5),
(829, 5),
(830, 5),
(831, 5),
(832, 5),
(833, 5),
(834, 5),
(835, 5),
(836, 5),
(837, 5),
(838, 5),
(839, 5),
(841, 5),
(842, 5),
(843, 5),
(844, 5),
(845, 5),
(852, 5),
(853, 5),
(854, 5),
(855, 5),
(856, 5),
(871, 5),
(872, 5),
(873, 5),
(874, 5),
(875, 5),
(876, 5),
(877, 5),
(878, 5),
(879, 5),
(880, 5),
(881, 5),
(882, 5),
(883, 5),
(884, 5),
(885, 5),
(886, 5),
(887, 5),
(888, 5),
(889, 5),
(890, 5),
(891, 5),
(892, 5),
(893, 5),
(894, 5),
(895, 5),
(897, 5),
(898, 5),
(899, 5),
(900, 5),
(901, 5),
(902, 5),
(903, 5),
(904, 5),
(905, 5),
(906, 5),
(926, 5),
(927, 5),
(928, 5),
(929, 5),
(930, 5),
(933, 5),
(934, 5),
(935, 5),
(937, 5),
(938, 5),
(939, 5),
(982, 5),
(983, 5),
(984, 5),
(985, 5),
(986, 5),
(1, 6),
(2, 6),
(3, 6),
(4, 6),
(5, 6),
(6, 6),
(7, 6),
(9, 6),
(10, 6),
(11, 6),
(12, 6),
(13, 6),
(65, 6),
(66, 6),
(67, 6),
(68, 6),
(69, 6),
(70, 6),
(120, 6),
(122, 6),
(128, 6),
(129, 6),
(781, 6),
(782, 6),
(783, 6),
(784, 6),
(785, 6),
(786, 6),
(787, 6),
(788, 6),
(789, 6),
(795, 6),
(801, 6),
(802, 6),
(803, 6),
(804, 6),
(805, 6),
(816, 6),
(822, 6),
(823, 6),
(824, 6),
(825, 6),
(840, 6),
(846, 6),
(847, 6),
(848, 6),
(849, 6),
(850, 6),
(851, 6),
(861, 6),
(862, 6),
(863, 6),
(864, 6),
(865, 6),
(866, 6),
(867, 6),
(868, 6),
(869, 6),
(870, 6),
(896, 6),
(919, 6),
(920, 6),
(921, 6),
(936, 6),
(940, 6),
(952, 6),
(953, 6),
(959, 6),
(960, 6),
(971, 6),
(972, 6),
(978, 6),
(980, 6),
(981, 6),
(992, 6),
(993, 6),
(999, 6),
(15, 6),
(16, 6),
(17, 6),
(18, 6),
(19, 6),
(20, 6),
(21, 6),
(22, 6),
(23, 6),
(24, 6),
(796, 6),
(797, 6),
(798, 6),
(799, 6),
(800, 6),
(806, 6),
(807, 6),
(808, 6),
(809, 6),
(810, 6),
(811, 6),
(812, 6),
(813, 6),
(814, 6),
(815, 6),
(817, 6),
(818, 6),
(819, 6),
(820, 6),
(821, 6),
(826, 6),
(827, 6),
(828, 6),
(829, 6),
(830, 6),
(831, 6),
(832, 6),
(833, 6),
(834, 6),
(835, 6),
(836, 6),
(837, 6),
(838, 6),
(839, 6),
(841, 6),
(842, 6),
(843, 6),
(844, 6),
(845, 6),
(852, 6),
(853, 6),
(854, 6),
(855, 6),
(856, 6),
(871, 6),
(872, 6),
(873, 6),
(874, 6),
(875, 6),
(876, 6),
(877, 6),
(878, 6),
(879, 6),
(880, 6),
(881, 6),
(882, 6),
(883, 6),
(884, 6),
(885, 6),
(886, 6),
(887, 6),
(888, 6),
(889, 6),
(890, 6),
(891, 6),
(892, 6),
(893, 6),
(894, 6),
(895, 6),
(897, 6),
(898, 6),
(899, 6),
(900, 6),
(901, 6),
(902, 6),
(903, 6),
(904, 6),
(905, 6),
(906, 6),
(926, 6),
(927, 6),
(928, 6),
(929, 6),
(930, 6),
(933, 6),
(934, 6),
(935, 6),
(937, 6),
(938, 6),
(939, 6),
(1000, 6),
(1001, 6),
(1002, 6),
(1003, 6),
(1004, 6),
(951, 1),
(1046, 0),
(1047, 0),
(1, 8),
(120, 8),
(122, 8),
(1042, 8),
(1043, 8),
(1044, 8),
(1045, 8),
(1046, 8),
(1047, 8),
(31, 8),
(32, 8),
(33, 8),
(34, 8),
(35, 8),
(36, 8),
(37, 8),
(44, 8),
(45, 8),
(46, 8),
(47, 8),
(48, 8),
(49, 8),
(62, 8),
(63, 8),
(64, 8),
(71, 8),
(72, 8),
(73, 8),
(74, 8),
(75, 8),
(76, 8),
(85, 8),
(86, 8),
(87, 8),
(88, 8),
(1005, 8),
(1006, 8),
(1007, 8),
(1008, 8),
(1009, 8),
(1010, 8),
(1011, 8),
(1012, 8),
(1013, 8),
(1014, 8),
(1015, 8),
(1016, 8),
(1017, 8),
(1018, 8),
(1019, 8),
(1025, 8),
(1026, 8),
(1027, 8),
(1028, 8),
(1029, 8),
(1030, 8),
(1031, 8),
(1032, 8),
(1033, 8),
(1034, 8),
(1, 3),
(120, 3),
(122, 3),
(1042, 3),
(1043, 3),
(1044, 3),
(1045, 3),
(1046, 3),
(1047, 3),
(20, 3),
(21, 3),
(22, 3),
(31, 3),
(32, 3),
(33, 3),
(34, 3),
(35, 3),
(36, 3),
(37, 3),
(44, 3),
(45, 3),
(46, 3),
(47, 3),
(48, 3),
(49, 3),
(71, 3),
(72, 3),
(73, 3),
(74, 3),
(75, 3),
(76, 3),
(85, 3),
(756, 3),
(757, 3),
(758, 3),
(759, 3),
(760, 3),
(1005, 3),
(1006, 3),
(1007, 3),
(1008, 3),
(1009, 3),
(1010, 3),
(1011, 3),
(1012, 3),
(1013, 3),
(1014, 3),
(1015, 3),
(1016, 3),
(1017, 3),
(1018, 3),
(1019, 3),
(1025, 3),
(1026, 3),
(1027, 3),
(1028, 3),
(1029, 3),
(1030, 3),
(1031, 3),
(1032, 3),
(1033, 3),
(1034, 3),
(1043, 3),
(1043, 1),
(1043, 3),
(1043, 1),
(1043, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_perm_to_user`
--

CREATE TABLE `aauth_perm_to_user` (
  `perm_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_pms`
--

CREATE TABLE `aauth_pms` (
  `id` int(11) UNSIGNED NOT NULL,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `receiver_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(225) NOT NULL,
  `message` text,
  `date_sent` datetime DEFAULT NULL,
  `date_read` datetime DEFAULT NULL,
  `pm_deleted_sender` int(1) DEFAULT NULL,
  `pm_deleted_receiver` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_user`
--

CREATE TABLE `aauth_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `definition` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_users`
--

CREATE TABLE `aauth_users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `oauth_uid` text,
  `oauth_provider` varchar(100) DEFAULT NULL,
  `pass` varchar(64) NOT NULL,
  `username` varchar(100) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `avatar` text,
  `banned` tinyint(1) DEFAULT '0',
  `kd_wilayah` varchar(30) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `forgot_exp` text,
  `remember_time` datetime DEFAULT NULL,
  `remember_exp` text,
  `verification_code` text,
  `top_secret` varchar(16) DEFAULT NULL,
  `ip_address` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `aauth_users`
--

INSERT INTO `aauth_users` (`id`, `email`, `oauth_uid`, `oauth_provider`, `pass`, `username`, `full_name`, `avatar`, `banned`, `kd_wilayah`, `last_login`, `last_activity`, `date_created`, `forgot_exp`, `remember_time`, `remember_exp`, `verification_code`, `top_secret`, `ip_address`) VALUES
(1, 'dev@gmail.com', NULL, NULL, '3783a5063e48003fd64eb62d2f06125430b4d63e62aeda455564932654079c80', 'admin', 'dev', '', 0, '73', '2022-09-29 07:09:03', '2022-09-29 07:09:03', '2019-08-03 01:11:23', NULL, '2020-02-07 00:00:00', 'k5YICT7oXOQ39WeS', NULL, NULL, '::1'),
(4, 'asbarimran@my.id', NULL, NULL, '26099512199546597858cebb4cc8d90e21f48e9be747c98d45d8a57a0f0a6e44', 'asbarimransh', 'Asbar Imran, SH', '20220710010651-asbarimran.png', 0, '7471', '2022-07-12 08:27:25', '2022-07-12 08:27:25', '2022-07-10 01:06:51', NULL, NULL, NULL, NULL, NULL, '::1'),
(5, 'notaris@coba.com', NULL, NULL, '28a9d1ac311fc87b88b094cd50b05abf517134b03d636bbc7ee94401f9952a21', 'notaris', 'notaris coba', '20220712083416-asbarimran.png', 0, '7404', '2022-07-12 08:35:45', '2022-07-12 08:35:45', '2022-07-12 08:34:16', NULL, NULL, NULL, NULL, NULL, '::1'),
(6, 'coba@coba.com', NULL, NULL, '3913228818759cd846b475d3106a4ecc9bf9bd91746cab4e88a8750c11d15914', 'notaris1', 'notaris1', '20220929071347-2.jpeg', 0, '7408', '2022-09-29 07:14:05', '2022-09-29 07:14:05', '2022-09-29 07:13:47', NULL, NULL, NULL, NULL, NULL, '::1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_user_to_group`
--

CREATE TABLE `aauth_user_to_group` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `aauth_user_to_group`
--

INSERT INTO `aauth_user_to_group` (`user_id`, `group_id`) VALUES
(1, 1),
(2, 2),
(2, 5),
(3, 4),
(3, 6),
(4, 3),
(5, 3),
(6, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `aauth_user_variables`
--

CREATE TABLE `aauth_user_variables` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `data_key` varchar(100) NOT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog`
--

CREATE TABLE `blog` (
  `id` int(11) UNSIGNED NOT NULL,
  `kd_wilayah` varchar(10) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `image` text NOT NULL,
  `tags` text NOT NULL,
  `category` varchar(200) NOT NULL,
  `status` varchar(10) NOT NULL,
  `author` varchar(100) NOT NULL,
  `viewers` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `blog`
--

INSERT INTO `blog` (`id`, `kd_wilayah`, `title`, `slug`, `content`, `image`, `tags`, `category`, `status`, `author`, `viewers`, `created_at`, `updated_at`) VALUES
(4, '7303011006', 'polisi bekuk 2 pelaku pencurian di bonto atu bantaeng', 'polisi-bekuk-2-pelaku-pencurian-di-bonto-atu-bantaeng', '<p>Kepolisian Sektor atau Polsek Bissappu&nbsp;<a href=\"https://makassar.tribunnews.com/tag/bantaeng\" title=\"Bantaeng\">Bantaeng</a>&nbsp;berhasil meringkus&nbsp; pelaku pencurian atas nama Rahmat dan Erin.</p>\r\n\r\n<p>Sementara Pa terduga pelaku lainnya kini menjadi Daftar Pencarian Orang (DPO).</p>\r\n\r\n<p>Kedua pelaku melakukan aksinya di rumah korban berlokasi di Jl Hasanuddin, Kelurahan Bonto Atu, Kecamatan Bissappu, Kabupaten Bantaeng pada Juli 2019.</p>\r\n\r\n<p>an di rumah kosong yang pemiliknya sedang merantau di Kalimantan Utara,&quot; kata&nbsp;Kapolsek Bissappu, Iptu Baharuddin,&nbsp;Kamis (8/8/2019).</p>\r\n\r\n<p>Pasalnya,&nbsp; Rahmat masuk ke dalam rumah korban dengan cara mencungkil jendela rumah tersebut. Alhasil, pelaku pun menggasak sejumlah barang-barang berharga yang ditinggal.</p>\r\n\r\n<p>&quot;Adapun barang yang berhasil dibawa kabur seperti satu unit TV Led merek samsung 40 inch, satu unit kipas angin merek tornado, satu buah aki sepeda motor, satu set kunci kunci dan sebuah dinamo penghisap air,&quot; kata Baharuddin.</p>\r\n\r\n<p>Dia menjelaskan peran pelaku. Erin dan Pa, bertugas untuk menjual barang tersebut di berbagai tempat.</p>\r\n\r\n<p>&quot;Uang hasil penjualan barang tersebut dibagi oleh tersangka,&quot; bebernya.</p>\r\n\r\n<p>Berdasarkan pengakuan tersangka, diketahuilah beberapa tempat penjualan barang bukti.</p>\r\n\r\n<p>&quot;Selanjutnya dilakukanlah penyitaan terhadap barang bukti TV Led di kampung Lamalaka. Sementara terhadap barang bukti yang belum disita akan dilakukan pencarian,&quot; ujarnya.</p>\r\n\r\n<p>Penulis: Nurwahidah<br />\r\nEditor: Suryana Anas</p>', '20200608152214-2020-06-08blog152159.jpg', '', '2', 'publish', 'admin', 0, '2020-06-03 14:14:59', '2020-06-08 15:22:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `blog_category`
--

CREATE TABLE `blog_category` (
  `category_id` int(11) UNSIGNED NOT NULL,
  `category_name` varchar(200) NOT NULL,
  `category_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `blog_category`
--

INSERT INTO `blog_category` (`category_id`, `category_name`, `category_desc`) VALUES
(2, 'Pemerintah', 'pemerintah'),
(3, 'KEGIATAN WARGA', 'Berisi Kegiatan-kegiatan Warga');

-- --------------------------------------------------------

--
-- Struktur dari tabel `captcha`
--

CREATE TABLE `captcha` (
  `captcha_id` int(11) UNSIGNED NOT NULL,
  `captcha_time` int(10) DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL,
  `word` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `captcha`
--

INSERT INTO `captcha` (`captcha_id`, `captcha_time`, `ip_address`, `word`) VALUES
(4, 1570428877, '::1', 'YJE8');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cc_options`
--

CREATE TABLE `cc_options` (
  `id` int(11) UNSIGNED NOT NULL,
  `option_name` varchar(200) NOT NULL,
  `option_value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `cc_options`
--

INSERT INTO `cc_options` (`id`, `option_name`, `option_value`) VALUES
(1, 'active_theme', 'cicool'),
(2, 'favicon', 'default.png'),
(3, 'site_name', 'Silaris'),
(4, 'enable_disqus', NULL),
(5, 'disqus_id', ''),
(6, 'email', 'tmc@gmail.com'),
(7, 'author', ''),
(8, 'site_description', 'Sistem Laporan Notaris'),
(9, 'keywords', ''),
(10, 'landing_page_id', 'default'),
(11, 'timezone', 'Asia/Jakarta'),
(12, 'google_id', ''),
(13, 'google_secret', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cc_session`
--

CREATE TABLE `cc_session` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) NOT NULL,
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struktur dari tabel `crud`
--

CREATE TABLE `crud` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `table_name` varchar(200) NOT NULL,
  `primary_key` varchar(200) NOT NULL,
  `page_read` varchar(20) DEFAULT NULL,
  `page_create` varchar(20) DEFAULT NULL,
  `page_update` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `crud`
--

INSERT INTO `crud` (`id`, `title`, `subject`, `table_name`, `primary_key`, `page_read`, `page_create`, `page_update`) VALUES
(1, 'Reportorium', 'Reportorium', 'reportorium', 'id_reportorium', 'yes', 'yes', 'yes'),
(2, 'Legalisasi', 'Legalisasi', 'legalisasi', 'id_legalisasi', 'yes', 'yes', 'yes'),
(3, 'Laporan Bulanan', 'Laporan Bulanan', 'laporan_bulanan', 'id_laporan_bulanan', 'yes', 'yes', 'yes'),
(4, 'Data Notaris', 'Data Notaris', 'data_notaris', 'id_notaris', 'yes', 'yes', 'yes'),
(5, 'Daftar Protes', 'Daftar Protes', 'daftar_proses', 'id_daftar_proses', 'yes', 'yes', 'yes'),
(6, 'Waarmerking', 'Waarmerking', 'waarmerking', 'id_waarmerking', 'yes', 'yes', 'yes'),
(7, 'Setup Wilayah', 'Wil', 'wil', 'id', 'yes', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Struktur dari tabel `crud_custom_option`
--

CREATE TABLE `crud_custom_option` (
  `id` int(11) UNSIGNED NOT NULL,
  `crud_field_id` int(11) NOT NULL,
  `crud_id` int(11) NOT NULL,
  `option_value` text NOT NULL,
  `option_label` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `crud_custom_option`
--

INSERT INTO `crud_custom_option` (`id`, `crud_field_id`, `crud_id`, `option_value`, `option_label`) VALUES
(7, 72, 4, 'Laki-Laki', 'Laki-laki'),
(8, 72, 4, 'Perempuan', 'Perempuan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `crud_field`
--

CREATE TABLE `crud_field` (
  `id` int(11) UNSIGNED NOT NULL,
  `crud_id` int(11) NOT NULL,
  `field_name` varchar(200) NOT NULL,
  `field_label` varchar(200) DEFAULT NULL,
  `input_type` varchar(200) NOT NULL,
  `show_column` varchar(10) DEFAULT NULL,
  `show_add_form` varchar(10) DEFAULT NULL,
  `show_update_form` varchar(10) DEFAULT NULL,
  `show_detail_page` varchar(10) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `relation_table` varchar(200) DEFAULT NULL,
  `relation_value` varchar(200) DEFAULT NULL,
  `relation_label` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `crud_field`
--

INSERT INTO `crud_field` (`id`, `crud_id`, `field_name`, `field_label`, `input_type`, `show_column`, `show_add_form`, `show_update_form`, `show_detail_page`, `sort`, `relation_table`, `relation_value`, `relation_label`) VALUES
(1, 1, 'id_reportorium', 'id_reportorium', 'number', '', '', '', 'yes', 1, '', '', ''),
(2, 1, 'nomor_urut', 'Nomor Urut', 'number', 'yes', 'yes', 'yes', 'yes', 2, '', '', ''),
(3, 1, 'nomor_akta', 'Nomor Akta', 'number', 'yes', 'yes', 'yes', 'yes', 3, '', '', ''),
(4, 1, 'tanggal_akta', 'Tanggal Akta', 'date', 'yes', 'yes', 'yes', 'yes', 4, '', '', ''),
(5, 1, 'sifat_akta', 'Sifat Akta', 'input', 'yes', 'yes', 'yes', 'yes', 5, '', '', ''),
(6, 1, 'penghadap', 'Penghadap', 'input', 'yes', 'yes', 'yes', 'yes', 6, '', '', ''),
(7, 2, 'id_legalisasi', 'id_legalisasi', 'number', '', '', '', 'yes', 1, '', '', ''),
(8, 2, 'nomor_urut', 'Nomor Urut', 'number', 'yes', 'yes', 'yes', 'yes', 2, '', '', ''),
(9, 2, 'nomor_akta', 'Nomor Akta', 'number', 'yes', 'yes', 'yes', 'yes', 3, '', '', ''),
(10, 2, 'tanggal_akta', 'Tanggal Akta', 'date', 'yes', 'yes', 'yes', 'yes', 4, '', '', ''),
(11, 2, 'sifat_akta', 'Sifat Akta', 'input', 'yes', 'yes', 'yes', 'yes', 5, '', '', ''),
(12, 2, 'penghadap', 'Penghadap', 'input', 'yes', 'yes', 'yes', 'yes', 6, '', '', ''),
(13, 3, 'id_laporan_bulanan', 'id_laporan_bulanan', 'number', '', '', '', 'yes', 1, '', '', ''),
(14, 3, 'nama_notaris', 'Nama Notaris', 'current_user_username', 'yes', 'yes', 'yes', 'yes', 2, '', '', ''),
(15, 3, 'tanggal_laporan', 'Tanggal Laporan', 'timestamp', 'yes', 'yes', 'yes', 'yes', 3, '', '', ''),
(16, 3, 'file_laporan', 'File Laporan', 'file', 'yes', 'yes', 'yes', 'yes', 4, '', '', ''),
(35, 6, 'id_waarmerking', 'id_waarmerking', 'number', '', '', '', 'yes', 1, '', '', ''),
(36, 6, 'nomor_urut', 'Nomor Urut', 'number', 'yes', 'yes', 'yes', 'yes', 2, '', '', ''),
(37, 6, 'nomor_akta', 'Nomor Akta', 'input', 'yes', 'yes', 'yes', 'yes', 3, '', '', ''),
(38, 6, 'tanggal_akta', 'Tanggal Akta', 'date', 'yes', 'yes', 'yes', 'yes', 4, '', '', ''),
(39, 6, 'sifat_akta', 'Sifat Akta', 'input', 'yes', 'yes', 'yes', 'yes', 5, '', '', ''),
(40, 6, 'penghadap', 'Penghadap', 'input', 'yes', 'yes', 'yes', 'yes', 6, '', '', ''),
(41, 7, 'id', 'id', 'number', '', '', '', 'yes', 1, '', '', ''),
(42, 7, 'kd_wilayah', 'Kode Wilayah', 'input', 'yes', 'yes', 'yes', 'yes', 2, '', '', ''),
(43, 7, 'nama_wilayah', 'Nama Wilayah', 'input', 'yes', 'yes', 'yes', 'yes', 3, '', '', ''),
(68, 4, 'id_notaris', 'id_notaris', 'number', '', '', '', 'yes', 1, '', '', ''),
(69, 4, 'nama_notaris', 'Nama Notaris', 'input', 'yes', 'yes', 'yes', 'yes', 2, '', '', ''),
(70, 4, 'tempat_lahir', 'Tempat Lahir', 'input', 'yes', 'yes', 'yes', 'yes', 3, '', '', ''),
(71, 4, 'tanggal_lahir', 'Tanggal Lahir', 'date', 'yes', 'yes', 'yes', 'yes', 4, '', '', ''),
(72, 4, 'jenis_kelamin', 'Jenis kelamin', 'custom_select', 'yes', 'yes', 'yes', 'yes', 5, '', '', ''),
(73, 4, 'email', 'Email', 'email', '', 'yes', 'yes', 'yes', 6, '', '', ''),
(74, 4, 'wilayah', 'Wilayah', 'options', 'yes', 'yes', 'yes', 'yes', 7, 'wil', 'nama_wilayah', 'nama_wilayah'),
(75, 4, 'surat_pindah', 'Surat Pindah', 'input', '', 'yes', 'yes', 'yes', 8, '', '', ''),
(76, 4, 'surat_keputusan', 'Surat Keputusan', 'input', '', 'yes', 'yes', 'yes', 9, '', '', ''),
(77, 4, 'alamat_rumah', 'Alamat Rumah', 'input', '', 'yes', 'yes', 'yes', 10, '', '', ''),
(78, 4, 'alamat_kantor', 'Alamat Kantor', 'input', 'yes', 'yes', 'yes', 'yes', 11, '', '', ''),
(79, 4, 'password', 'password', 'password', '', 'yes', 'yes', 'yes', 12, '', '', ''),
(80, 5, 'id_daftar_proses', 'id_daftar_proses', 'number', '', '', '', 'yes', 1, '', '', ''),
(81, 5, 'nomor_urut', 'Nomor Urut', 'number', 'yes', 'yes', 'yes', 'yes', 2, '', '', ''),
(82, 5, 'nomor_akta', 'Nomor Akta', 'number', 'yes', 'yes', 'yes', 'yes', 3, '', '', ''),
(83, 5, 'tanggal_akta', 'Tanggal Akta', 'date', 'yes', 'yes', 'yes', 'yes', 4, '', '', ''),
(84, 5, 'sifat_akta', 'Sifat Akta', 'input', 'yes', 'yes', 'yes', 'yes', 5, '', '', ''),
(85, 5, 'penghadap', 'Penghadap', 'input', 'yes', 'yes', 'yes', 'yes', 6, '', '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `crud_field_validation`
--

CREATE TABLE `crud_field_validation` (
  `id` int(11) UNSIGNED NOT NULL,
  `crud_field_id` int(11) NOT NULL,
  `crud_id` int(11) NOT NULL,
  `validation_name` varchar(200) NOT NULL,
  `validation_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `crud_field_validation`
--

INSERT INTO `crud_field_validation` (`id`, `crud_field_id`, `crud_id`, `validation_name`, `validation_value`) VALUES
(1, 2, 1, 'max_length', '10'),
(2, 3, 1, 'required', ''),
(3, 3, 1, 'max_length', '10'),
(4, 4, 1, 'required', ''),
(5, 5, 1, 'max_length', '100'),
(6, 6, 1, 'required', ''),
(7, 6, 1, 'max_length', '100'),
(8, 8, 2, 'max_length', '10'),
(9, 9, 2, 'required', ''),
(10, 9, 2, 'max_length', '10'),
(11, 10, 2, 'required', ''),
(12, 11, 2, 'required', ''),
(13, 11, 2, 'max_length', '100'),
(14, 12, 2, 'required', ''),
(15, 12, 2, 'max_length', '100'),
(16, 14, 3, 'required', ''),
(17, 14, 3, 'max_length', '100'),
(18, 15, 3, 'required', ''),
(19, 16, 3, 'required', ''),
(20, 16, 3, 'max_length', '1000'),
(52, 36, 6, 'required', ''),
(53, 36, 6, 'max_length', '10'),
(54, 37, 6, 'required', ''),
(55, 37, 6, 'max_length', '10'),
(56, 38, 6, 'required', ''),
(57, 39, 6, 'required', ''),
(58, 39, 6, 'max_length', '100'),
(59, 40, 6, 'required', ''),
(60, 40, 6, 'max_length', '100'),
(61, 42, 7, 'required', ''),
(62, 42, 7, 'max_length', '30'),
(63, 43, 7, 'required', ''),
(64, 43, 7, 'max_length', '100'),
(105, 69, 4, 'required', ''),
(106, 69, 4, 'max_length', '100'),
(107, 70, 4, 'max_length', '100'),
(108, 72, 4, 'required', ''),
(109, 73, 4, 'max_length', '100'),
(110, 74, 4, 'required', ''),
(111, 74, 4, 'max_length', '100'),
(112, 75, 4, 'max_length', '100'),
(113, 76, 4, 'max_length', '100'),
(114, 77, 4, 'max_length', '100'),
(115, 78, 4, 'max_length', '100'),
(116, 79, 4, 'max_length', '100'),
(117, 81, 5, 'required', ''),
(118, 81, 5, 'max_length', '10'),
(119, 82, 5, 'required', ''),
(120, 82, 5, 'max_length', '10'),
(121, 83, 5, 'required', ''),
(122, 84, 5, 'required', ''),
(123, 84, 5, 'max_length', '100'),
(124, 85, 5, 'required', ''),
(125, 85, 5, 'max_length', '100');

-- --------------------------------------------------------

--
-- Struktur dari tabel `crud_input_type`
--

CREATE TABLE `crud_input_type` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` varchar(200) NOT NULL,
  `relation` varchar(20) NOT NULL,
  `custom_value` int(11) NOT NULL,
  `validation_group` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `crud_input_type`
--

INSERT INTO `crud_input_type` (`id`, `type`, `relation`, `custom_value`, `validation_group`) VALUES
(1, 'input', '0', 0, 'input'),
(2, 'textarea', '0', 0, 'text'),
(3, 'select', '1', 0, 'select'),
(4, 'editor_wysiwyg', '0', 0, 'editor'),
(5, 'password', '0', 0, 'password'),
(6, 'email', '0', 0, 'email'),
(7, 'address_map', '0', 0, 'address_map'),
(8, 'file', '0', 0, 'file'),
(9, 'file_multiple', '0', 0, 'file_multiple'),
(10, 'datetime', '0', 0, 'datetime'),
(11, 'date', '0', 0, 'date'),
(12, 'timestamp', '0', 0, 'timestamp'),
(13, 'number', '0', 0, 'number'),
(14, 'yes_no', '0', 0, 'yes_no'),
(15, 'time', '0', 0, 'time'),
(16, 'year', '0', 0, 'year'),
(17, 'select_multiple', '1', 0, 'select_multiple'),
(18, 'checkboxes', '1', 0, 'checkboxes'),
(19, 'options', '1', 0, 'options'),
(20, 'true_false', '0', 0, 'true_false'),
(21, 'current_user_username', '0', 0, 'user_username'),
(22, 'current_user_id', '0', 0, 'current_user_id'),
(23, 'custom_option', '0', 1, 'custom_option'),
(24, 'custom_checkbox', '0', 1, 'custom_checkbox'),
(25, 'custom_select_multiple', '0', 1, 'custom_select_multiple'),
(26, 'custom_select', '0', 1, 'custom_select');

-- --------------------------------------------------------

--
-- Struktur dari tabel `crud_input_validation`
--

CREATE TABLE `crud_input_validation` (
  `id` int(11) UNSIGNED NOT NULL,
  `validation` varchar(200) NOT NULL,
  `input_able` varchar(20) NOT NULL,
  `group_input` text NOT NULL,
  `input_placeholder` text NOT NULL,
  `call_back` varchar(10) NOT NULL,
  `input_validation` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `crud_input_validation`
--

INSERT INTO `crud_input_validation` (`id`, `validation`, `input_able`, `group_input`, `input_placeholder`, `call_back`, `input_validation`) VALUES
(1, 'required', 'no', 'input, file, number, text, datetime, select, password, email, editor, date, yes_no, time, year, select_multiple, options, checkboxes, true_false, address_map, custom_option, custom_checkbox, custom_select_multiple, custom_select, file_multiple', '', '', ''),
(2, 'max_length', 'yes', 'input, number, text, select, password, email, editor, yes_no, time, year, select_multiple, options, checkboxes, address_map', '', '', 'numeric'),
(3, 'min_length', 'yes', 'input, number, text, select, password, email, editor, time, year, select_multiple, address_map', '', '', 'numeric'),
(4, 'valid_email', 'no', 'input, email', '', '', ''),
(5, 'valid_emails', 'no', 'input, email', '', '', ''),
(6, 'regex', 'yes', 'input, number, text, datetime, select, password, email, editor, date, yes_no, time, year, select_multiple, options, checkboxes', '', 'yes', 'callback_valid_regex'),
(7, 'decimal', 'no', 'input, number, text, select', '', '', ''),
(8, 'allowed_extension', 'yes', 'file, file_multiple', 'ex : jpg,png,..', '', 'callback_valid_extension_list'),
(9, 'max_width', 'yes', 'file, file_multiple', '', '', 'numeric'),
(10, 'max_height', 'yes', 'file, file_multiple', '', '', 'numeric'),
(11, 'max_size', 'yes', 'file, file_multiple', '... kb', '', 'numeric'),
(12, 'max_item', 'yes', 'file_multiple', '', '', 'numeric'),
(13, 'valid_url', 'no', 'input, text', '', '', ''),
(14, 'alpha', 'no', 'input, text, select, password, editor, yes_no', '', '', ''),
(15, 'alpha_numeric', 'no', 'input, number, text, select, password, editor', '', '', ''),
(16, 'alpha_numeric_spaces', 'no', 'input, number, text,select, password, editor', '', '', ''),
(17, 'valid_number', 'no', 'input, number, text, password, editor, true_false', '', 'yes', ''),
(18, 'valid_datetime', 'no', 'input, datetime, text', '', 'yes', ''),
(19, 'valid_date', 'no', 'input, datetime, date, text', '', 'yes', ''),
(20, 'valid_max_selected_option', 'yes', 'select_multiple, custom_select_multiple, custom_checkbox, checkboxes', '', 'yes', 'numeric'),
(21, 'valid_min_selected_option', 'yes', 'select_multiple, custom_select_multiple, custom_checkbox, checkboxes', '', 'yes', 'numeric'),
(22, 'valid_alpha_numeric_spaces_underscores', 'no', 'input, text,select, password, editor', '', 'yes', ''),
(23, 'matches', 'yes', 'input, number, text, password, email', 'any field', 'no', 'callback_valid_alpha_numeric_spaces_underscores'),
(24, 'valid_json', 'no', 'input, text, editor', '', 'yes', ' '),
(25, 'valid_url', 'no', 'input, text, editor', '', 'no', ' '),
(26, 'exact_length', 'yes', 'input, text, number', '0 - 99999*', 'no', 'numeric'),
(27, 'alpha_dash', 'no', 'input, text', '', 'no', ''),
(28, 'integer', 'no', 'input, text, number', '', 'no', ''),
(29, 'differs', 'yes', 'input, text, number, email, password, editor, options, select', 'any field', 'no', 'callback_valid_alpha_numeric_spaces_underscores'),
(30, 'is_natural', 'no', 'input, text, number', '', 'no', ''),
(31, 'is_natural_no_zero', 'no', 'input, text, number', '', 'no', ''),
(32, 'less_than', 'yes', 'input, text, number', '', 'no', 'numeric'),
(33, 'less_than_equal_to', 'yes', 'input, text, number', '', 'no', 'numeric'),
(34, 'greater_than', 'yes', 'input, text, number', '', 'no', 'numeric'),
(35, 'greater_than_equal_to', 'yes', 'input, text, number', '', 'no', 'numeric'),
(36, 'in_list', 'yes', 'input, text, number, select, options', '', 'no', 'callback_valid_multiple_value'),
(37, 'valid_ip', 'no', 'input, text', '', 'no', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_proses`
--

CREATE TABLE `daftar_proses` (
  `id_daftar_proses` int(11) NOT NULL,
  `nomor_urut` int(10) NOT NULL,
  `nomor_akta` int(10) NOT NULL,
  `tanggal_akta` date NOT NULL,
  `sifat_akta` varchar(100) NOT NULL,
  `penghadap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_notaris`
--

CREATE TABLE `data_notaris` (
  `id_notaris` int(11) NOT NULL,
  `nama_notaris` varchar(100) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` varchar(100) DEFAULT NULL,
  `jenis_kelamin` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `wilayah` varchar(100) DEFAULT NULL,
  `surat_pindah` varchar(100) DEFAULT NULL,
  `surat_keputusan` varchar(100) DEFAULT NULL,
  `alamat_rumah` varchar(100) DEFAULT NULL,
  `alamat_kantor` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `data_notaris`
--

INSERT INTO `data_notaris` (`id_notaris`, `nama_notaris`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `email`, `wilayah`, `surat_pindah`, `surat_keputusan`, `alamat_rumah`, `alamat_kantor`, `password`) VALUES
(112, 'Mohammad Nurung, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Bombana', NULL, NULL, NULL, NULL, NULL),
(113, 'Puput Purbowati, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Bombana', NULL, NULL, NULL, NULL, NULL),
(114, 'MUHAMMAD RIDWAN, S.H.,M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Bombana', NULL, NULL, NULL, NULL, NULL),
(115, 'SUDIRMAN', NULL, NULL, NULL, NULL, 'Kabupaten Bombana', NULL, NULL, NULL, NULL, NULL),
(116, 'SUDIRMAN', NULL, NULL, NULL, NULL, 'Kabupaten Bombana', NULL, NULL, NULL, NULL, NULL),
(117, 'FARMA, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Bombana', NULL, NULL, NULL, NULL, NULL),
(118, 'DISA HAIFA IZDIHAR, S.H., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Buton Selatan', NULL, NULL, NULL, NULL, NULL),
(119, 'Nasrin, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Buton Tengah', NULL, NULL, NULL, NULL, NULL),
(120, 'ARISMAN SUAR BHAKTI IBRAHIM', NULL, NULL, NULL, NULL, 'Kabupaten Buton Tengah', NULL, NULL, NULL, NULL, NULL),
(121, 'HANDRI JUFRI, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Buton Utara', NULL, NULL, NULL, NULL, NULL),
(122, 'LA ODE Kabupaten MunaWIR', NULL, NULL, NULL, NULL, 'Kabupaten Buton Utara', NULL, NULL, NULL, NULL, NULL),
(123, 'Ahmad, SH.', NULL, NULL, NULL, NULL, 'Kabupaten Kabupaten Konawe', NULL, NULL, NULL, NULL, NULL),
(124, 'Sitti Nurfarhah Tane, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Konawe', NULL, NULL, NULL, NULL, NULL),
(125, 'Sabrial Iksan, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Konawe', NULL, NULL, NULL, NULL, NULL),
(126, 'Rian Resvitasari, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Konawe', NULL, NULL, NULL, NULL, NULL),
(127, 'Yadi Adrianus Leroux', NULL, NULL, NULL, NULL, 'Kabupaten Konawe', NULL, NULL, NULL, NULL, NULL),
(128, 'fredi omastik SH.,M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Konawe', NULL, NULL, NULL, NULL, NULL),
(129, 'Jorinda Bittikaka, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Selatan', NULL, NULL, NULL, NULL, NULL),
(130, 'Istianah, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Selatan', NULL, NULL, NULL, NULL, NULL),
(131, 'Silvester Sampe, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Selatan', NULL, NULL, NULL, NULL, NULL),
(132, 'Maya Dewi Makmun, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Selatan', NULL, NULL, NULL, NULL, NULL),
(133, 'Rahmawati Lallo, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Selatan', NULL, NULL, NULL, NULL, NULL),
(134, 'Nurmiati Laga', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Selatan', NULL, NULL, NULL, NULL, NULL),
(135, 'ANITA SAPRIANA, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Selatan', NULL, NULL, NULL, NULL, NULL),
(136, 'ANITA SAPRIANA, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Selatan', NULL, NULL, NULL, NULL, NULL),
(137, 'Hasriatin, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Utara', NULL, NULL, NULL, NULL, NULL),
(138, 'ANANIA SURYAWATI, S.H.,M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Utara', NULL, NULL, NULL, NULL, NULL),
(139, 'Andi Fauziah Nurul Utami, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Konawe Utara', NULL, NULL, NULL, NULL, NULL),
(140, 'Emy Astuti, SH.', NULL, NULL, NULL, NULL, 'Kabupaten Muna', NULL, NULL, NULL, NULL, NULL),
(141, 'Achmad Yani Kalimuddin, SH.', NULL, NULL, NULL, NULL, 'Kabupaten Muna', NULL, NULL, NULL, NULL, NULL),
(142, 'Ary Guntoro, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Muna', NULL, NULL, NULL, NULL, NULL),
(143, 'M. Asman Amanullah, SH.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(144, 'Zainuddin Tahir, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(145, 'Santi Bunga, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(146, 'Vanda Madethen, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(147, 'Andi Helmy Rahman, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(148, 'Salma, S.H., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(149, 'Dedi Indrawan Darsan, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(150, 'Herdianti, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(151, 'Silvana Resky Muliawan, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(152, 'PAHERI', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(153, 'DR. WANDHI PRATAMA PUTRA SISMAN, S.H., M. KN', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(154, 'HARDIYANTI TRI WULAN SARI, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(155, 'FAUZAN HASBI, S.H.,M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(156, 'hardiyanti tri wulan sari', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(157, 'MUSRIANSYAH, S.H.,M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka', NULL, NULL, NULL, NULL, NULL),
(158, 'Irsan Haerudin Akif, SH., M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka Timur', NULL, NULL, NULL, NULL, NULL),
(159, 'Andi Bau Padiawanti, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka Utara', NULL, NULL, NULL, NULL, NULL),
(160, 'Akbar Zulhaq, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka Utara', NULL, NULL, NULL, NULL, NULL),
(161, 'ENAH ROHAENAH KASIM, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kabupaten Kolaka Utara', NULL, NULL, NULL, NULL, NULL),
(162, 'AM. Kasim Siruhu, SH.', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(163, 'Laode Muhammad Taufik, SH', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(164, 'Nita Mirawati, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(165, 'Puspita Mustikasari, SH., M.Kn', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(166, 'HAMID PRIOEGI,S.H.', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(167, 'MUSNAWIR', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(168, 'NUR SYAMSI MUSTAFA,S.H.,M.Kn', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(169, 'MUHAMAD RAMADHAN MAKMUR MA\'RUD, SH.,M.Kn', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(170, 'La Ode Arsanudin', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(171, 'Aril Alfian Lanae, S.H., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(172, 'Valdy Tunggeleng, SH.,M.Kn', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(173, 'RAHMAT GANDI ASRUDDIN', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(174, 'Muhammad Achdar Khaliq Danial, S.H., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(175, 'Baharillah Mouna, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kota Baubau', NULL, NULL, NULL, NULL, NULL),
(176, 'Al Fajri, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(177, 'Albert Widya Arung Raya SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(178, 'Andi Aulia Jusman, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(179, 'Armansyah, SH.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(180, 'Armayulita, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(181, 'Arsari Rahma Ramly, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(182, 'Asbar Imran, SH.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(183, 'Deschika Gaby Justicia Tolla, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(184, 'Doli Manika, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(185, 'Etyka Agriyani, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(186, 'Fahruddin Zaki Halim, S.H., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(187, 'Gresia Puterahmat, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(188, 'H. Eko Saputra, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(189, 'Hana Prisca, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(190, 'Hardianti Fahli, S.H., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(191, 'Hidayat, SH.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(192, 'Hot Asih Hadi Wijaya Sianturi, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(193, 'Karlina, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(194, 'Maulana Saputra Sauala, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(195, 'Miftah Husabri Asbar, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(196, 'Muhammad Emil Gazali, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(197, 'Muhammad Farid Azhari Tahrir, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(198, 'Muhammad Hasyim, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(199, 'Muhammad Tun Samudra, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(200, 'Rayan Riadi, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(201, 'Riovino Moscani, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(202, 'Savara, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(203, 'Siprianus Trisno, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(204, 'Sudirman, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(205, 'Wa Ode Fadilah Yusuf, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(206, 'RIMA ANGGRIYANI', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(207, 'ERFANDI, SH., M.KN', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(208, 'YENEKE FERONIKA KAHIMPONG,SH.,M.Kn', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(209, 'MUHAMMAD ISHAK, S.H., M.Kn., M.M.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(210, 'Irwan Addy Sanusi, SH', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(211, 'H. SUJIANTO, SH., M.H., M.KN.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(212, 'ANDI HIKMAWATI', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(213, 'Agus Jaya, S.H.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(214, 'La Ode Saharudin, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(215, 'ANANDA HAZTI KARMAN, S.H.,M.Kn', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(216, 'SITI KHOIRIYAH, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(217, 'ASKAR AMIR LAEPE, S.H., M.Kn.', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(218, 'Shaenur Astuti Sangkala', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(219, 'FIDWAL INDRAJAB, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(220, 'SITI KHOIRIYAH, S.H.,M.Kn', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(221, 'MUHAMMAD TIANTANIK CITRA MIDO, S.H., M.Kn', NULL, NULL, NULL, NULL, 'Kota Kendari', NULL, NULL, NULL, NULL, NULL),
(222, 'Inalis Veranica Ritonga, SH., M.Kn.', NULL, NULL, NULL, NULL, 'Kabupaten Wakatobi', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `keys`
--

CREATE TABLE `keys` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL,
  `is_private_key` tinyint(1) NOT NULL,
  `ip_addresses` text,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `keys`
--

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1, 0, 'E611F398D9D925F00053EF4D39FD94DE', 0, 0, 0, NULL, '2019-08-02 17:11:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_bulanan`
--

CREATE TABLE `laporan_bulanan` (
  `id_laporan_bulanan` int(11) NOT NULL,
  `nama_notaris` varchar(100) NOT NULL,
  `tanggal_laporan` date NOT NULL,
  `file_laporan` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `laporan_bulanan`
--

INSERT INTO `laporan_bulanan` (`id_laporan_bulanan`, `nama_notaris`, `tanggal_laporan`, `file_laporan`) VALUES
(1, 'admin', '2022-07-12', '20220712082623-2022-07-12laporan_bulanan081053.pdf'),
(2, 'asbarimransh', '2022-07-12', '20220712082745-2022-07-12laporan_bulanan082744.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `legalisasi`
--

CREATE TABLE `legalisasi` (
  `id_legalisasi` int(11) NOT NULL,
  `nomor_urut` int(10) NOT NULL,
  `nomor_akta` int(10) NOT NULL,
  `tanggal_akta` date NOT NULL,
  `sifat_akta` varchar(100) NOT NULL,
  `penghadap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` int(11) UNSIGNED NOT NULL,
  `label` varchar(200) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `icon_color` varchar(200) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `sort` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `menu_type_id` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `label`, `type`, `icon_color`, `link`, `sort`, `parent`, `icon`, `menu_type_id`, `active`) VALUES
(1, 'MAIN NAVIGATION', 'label', '', 'administrator/dashboard', 1, 0, '', 1, 1),
(2, 'Dashboard', 'menu', 'default', 'administrator/dashboard', 3, 23, 'fa-dashboard', 1, 0),
(3, 'TMC CRUD', 'menu', 'default', 'administrator/crud', 4, 23, 'fa-table', 1, 1),
(4, 'TMC API Create', 'menu', 'default', 'administrator/rest', 5, 23, 'fa-code', 1, 1),
(8, 'Menu', 'menu', '', 'administrator/menu', 12, 23, 'fa-bars', 1, 1),
(9, 'User Management', 'menu', 'default', '#', 6, 23, 'fa-shield', 1, 1),
(10, 'User', 'menu', '', 'administrator/user', 7, 9, '', 1, 1),
(11, 'Groups', 'menu', '', 'administrator/group', 8, 9, '', 1, 1),
(12, 'Access', 'menu', '', 'administrator/access', 9, 9, '', 1, 1),
(13, 'Permission', 'menu', '', 'administrator/permission', 10, 9, '', 1, 1),
(14, 'API Keys', 'menu', '', 'administrator/keys', 11, 9, '', 1, 1),
(15, 'Extension', 'menu', '', 'administrator/extension', 13, 23, 'fa-puzzle-piece', 1, 1),
(17, 'Settings', 'menu', 'text-red', 'administrator/setting', 14, 23, 'fa-circle-o', 1, 1),
(20, 'Home', 'menu', '', '/', 1, 0, '', 2, 1),
(21, 'Blog', 'menu', '', 'blog', 4, 0, '', 2, 1),
(22, 'Dashboard', 'menu', '', 'administrator/dashboard', 5, 0, '', 2, 1),
(23, 'ADMINISTRATOR', 'menu', 'default', '#', 2, 0, 'fa-amazon', 1, 1),
(27, 'MASTER DATA', 'menu', 'text-red', '#', 15, 0, 'fa-get-pocket', 1, 1),
(28, 'Profil', 'menu', 'default', 'profile', 16, 27, '', 1, 1),
(29, 'SETUP', 'menu', 'text-yellow', '#', 17, 0, 'fa-cog', 1, 1),
(30, 'Setup Wilayah', 'menu', 'text-yellow', 'wil', 18, 29, 'fa-map-marker', 1, 1),
(31, 'Data Notaris', 'menu', 'text-yellow', 'data_notaris', 21, 29, '', 1, 1),
(32, 'LAPORAN', 'menu', 'text-green', '#', 22, 0, 'fa-file-archive-o', 1, 1),
(33, 'Laporan Bulanan', 'menu', 'text-green', 'laporan_bulanan', 23, 32, 'fa-amazon', 1, 1),
(34, 'Reportorium', 'menu', 'text-green', 'reportorium', 24, 32, '', 1, 1),
(35, 'Daftar Protes', 'menu', 'text-green', 'daftar_proses', 25, 32, '', 1, 1),
(36, 'Legalisasi', '', 'text-green', 'legalisasi', 26, 32, '', 1, 1),
(37, 'Waarmerking', '', 'text-green', 'waarmerking', 27, 32, '', 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu_type`
--

CREATE TABLE `menu_type` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(200) NOT NULL,
  `definition` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `menu_type`
--

INSERT INTO `menu_type` (`id`, `name`, `definition`) VALUES
(1, 'side menu', NULL),
(2, 'top menu', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`version`) VALUES
(1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `page`
--

CREATE TABLE `page` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `fresh_content` text NOT NULL,
  `keyword` text,
  `description` text,
  `link` varchar(200) DEFAULT NULL,
  `template` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struktur dari tabel `page_block_element`
--

CREATE TABLE `page_block_element` (
  `id` int(11) UNSIGNED NOT NULL,
  `group_name` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `image_preview` varchar(200) NOT NULL,
  `block_name` varchar(200) NOT NULL,
  `content_type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Struktur dari tabel `reportorium`
--

CREATE TABLE `reportorium` (
  `id_reportorium` int(10) NOT NULL,
  `nomor_urut` int(10) NOT NULL,
  `nomor_akta` int(10) NOT NULL,
  `tanggal_akta` date NOT NULL,
  `sifat_akta` varchar(100) NOT NULL,
  `penghadap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rest`
--

CREATE TABLE `rest` (
  `id` int(11) UNSIGNED NOT NULL,
  `subject` varchar(200) NOT NULL,
  `table_name` varchar(200) NOT NULL,
  `primary_key` varchar(200) NOT NULL,
  `x_api_key` varchar(20) DEFAULT NULL,
  `x_token` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `rest`
--

INSERT INTO `rest` (`id`, `subject`, `table_name`, `primary_key`, `x_api_key`, `x_token`) VALUES
(1, 'Rkpd Skpd Kgtn', 'rkpd_skpd_kgtn', 'id_rkpd_skpd_kgtn', 'no', 'yes');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rest_field`
--

CREATE TABLE `rest_field` (
  `id` int(11) UNSIGNED NOT NULL,
  `rest_id` int(11) NOT NULL,
  `field_name` varchar(200) NOT NULL,
  `field_label` varchar(200) DEFAULT NULL,
  `input_type` varchar(200) NOT NULL,
  `show_column` varchar(10) DEFAULT NULL,
  `show_add_api` varchar(10) DEFAULT NULL,
  `show_update_api` varchar(10) DEFAULT NULL,
  `show_detail_api` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `rest_field`
--

INSERT INTO `rest_field` (`id`, `rest_id`, `field_name`, `field_label`, `input_type`, `show_column`, `show_add_api`, `show_update_api`, `show_detail_api`) VALUES
(1, 1, 'id_rkpd_skpd_kgtn', NULL, 'input', 'yes', '', '', 'yes'),
(2, 1, 'kd_kgtn', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(3, 1, 'nm_kgtn', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(4, 1, 'nm_subkgtn', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(5, 1, 'prioritas', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(6, 1, 'sifat_kgtn', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(7, 1, 'sasaran', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(8, 1, 'output', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(9, 1, 'outcome', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(10, 1, 'latar_belakang', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(11, 1, 'keterangan', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(12, 1, 'rkpd_rkpd_id', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(13, 1, 'sikd_skpd_id', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(14, 1, 'sikd_bidang_id', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(15, 1, 'sikd_kgtn_id', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(16, 1, 'musren_kgtn_kab_id', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(17, 1, 'sikd_program_daerah_id', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(18, 1, 'sikd_sasaran_program_daerah_id', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(19, 1, 'kemiskinan', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(20, 1, 'created_by', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(21, 1, 'creation_date', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(22, 1, 'last_updated_by', NULL, 'input', 'yes', 'yes', 'yes', 'yes'),
(23, 1, 'last_updated_date', NULL, 'input', 'yes', 'yes', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rest_field_validation`
--

CREATE TABLE `rest_field_validation` (
  `id` int(11) UNSIGNED NOT NULL,
  `rest_field_id` int(11) NOT NULL,
  `rest_id` int(11) NOT NULL,
  `validation_name` varchar(200) NOT NULL,
  `validation_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `rest_field_validation`
--

INSERT INTO `rest_field_validation` (`id`, `rest_field_id`, `rest_id`, `validation_name`, `validation_value`) VALUES
(1, 2, 1, 'required', ''),
(2, 2, 1, 'max_length', '15'),
(3, 3, 1, 'required', ''),
(4, 3, 1, 'max_length', '200'),
(5, 4, 1, 'required', ''),
(6, 4, 1, 'max_length', '200'),
(7, 5, 1, 'required', ''),
(8, 5, 1, 'max_length', '5'),
(9, 6, 1, 'required', ''),
(10, 6, 1, 'max_length', '1'),
(11, 7, 1, 'required', ''),
(12, 7, 1, 'max_length', '250'),
(13, 8, 1, 'required', ''),
(14, 8, 1, 'max_length', '200'),
(15, 9, 1, 'required', ''),
(16, 9, 1, 'max_length', '200'),
(17, 10, 1, 'required', ''),
(18, 10, 1, 'max_length', '200'),
(19, 11, 1, 'required', ''),
(20, 11, 1, 'max_length', '255'),
(21, 12, 1, 'required', ''),
(22, 12, 1, 'max_length', '30'),
(23, 13, 1, 'required', ''),
(24, 13, 1, 'max_length', '30'),
(25, 14, 1, 'required', ''),
(26, 14, 1, 'max_length', '30'),
(27, 15, 1, 'required', ''),
(28, 15, 1, 'max_length', '30'),
(29, 16, 1, 'required', ''),
(30, 16, 1, 'max_length', '30'),
(31, 17, 1, 'required', ''),
(32, 17, 1, 'max_length', '30'),
(33, 18, 1, 'required', ''),
(34, 18, 1, 'max_length', '30'),
(35, 19, 1, 'required', ''),
(36, 19, 1, 'max_length', '255'),
(37, 20, 1, 'required', ''),
(38, 20, 1, 'max_length', '20'),
(39, 21, 1, 'required', ''),
(40, 22, 1, 'required', ''),
(41, 22, 1, 'max_length', '20'),
(42, 23, 1, 'required', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rest_input_type`
--

CREATE TABLE `rest_input_type` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` varchar(200) NOT NULL,
  `relation` varchar(20) NOT NULL,
  `validation_group` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Dumping data untuk tabel `rest_input_type`
--

INSERT INTO `rest_input_type` (`id`, `type`, `relation`, `validation_group`) VALUES
(1, 'input', '0', 'input'),
(2, 'timestamp', '0', 'timestamp'),
(3, 'file', '0', 'file');

-- --------------------------------------------------------

--
-- Struktur dari tabel `waarmerking`
--

CREATE TABLE `waarmerking` (
  `id_waarmerking` int(11) NOT NULL,
  `nomor_urut` int(10) NOT NULL,
  `nomor_akta` int(10) NOT NULL,
  `tanggal_akta` date NOT NULL,
  `sifat_akta` varchar(100) NOT NULL,
  `penghadap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `wil`
--

CREATE TABLE `wil` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(30) NOT NULL,
  `nama_wilayah` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `wil`
--

INSERT INTO `wil` (`id`, `kd_wilayah`, `nama_wilayah`) VALUES
(1, '7401', 'Kabupaten Kolaka'),
(2, '7402', 'Kabupaten Konawe'),
(3, '7403', 'Kabupaten Muna'),
(4, '7404', 'Kabupaten Buton'),
(5, '7405', 'Kabupaten Konawe Selatan'),
(6, '7406', 'Kabupaten Bombana'),
(7, '7407', 'Kabupaten Wakatobi'),
(8, '7408', 'Kabupaten Kolaka Utara'),
(9, '7409', 'Kabupaten Konawe Utara'),
(10, '7410', 'Kabupaten Buton Utara'),
(11, '7411', 'Kabupaten Kolaka Timur'),
(12, '7412', 'Kabupaten Konawe Kepulauan'),
(13, '7413', 'Kabupaten Muna Barat'),
(14, '7414', 'Kabupaten Buton Tengah'),
(15, '7415', 'Kabupaten Buton Selatan'),
(16, '7471', 'Kota Kendari'),
(17, '7472', 'Kota Baubau'),
(9205, '', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wilayah`
--

CREATE TABLE `wilayah` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `nama` varchar(30) DEFAULT NULL,
  `singkatan` varchar(20) DEFAULT NULL,
  `klasifikasi` varchar(30) DEFAULT NULL COMMENT 'prov/kab/kec/kel/desa',
  `kd_induk` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `wilayah`
--

INSERT INTO `wilayah` (`id`, `kd_wilayah`, `nama`, `singkatan`, `klasifikasi`, `kd_induk`) VALUES
(1, '7401', 'Kabupaten Kolaka', NULL, NULL, NULL),
(2, '7402', 'Kabupaten Konawe', NULL, NULL, NULL),
(3, '7403', 'Kabupaten Muna', NULL, NULL, NULL),
(4, '7404', 'Kabupaten Buton', NULL, NULL, NULL),
(5, '7405', 'Kabupaten Konawe Selatan', NULL, NULL, NULL),
(6, '7406', 'Kabupaten Bombana', NULL, NULL, NULL),
(7, '7407', 'Kabupaten Wakatobi', NULL, NULL, NULL),
(8, '7408', 'Kabupaten Kolaka Utara', NULL, NULL, NULL),
(9, '7409', 'Kabupaten Konawe Utara', NULL, NULL, NULL),
(10, '7410', 'Kabupaten Buton Utara', NULL, NULL, NULL),
(11, '7411', 'Kabupaten Kolaka Timur', NULL, NULL, NULL),
(12, '7412', 'Kabupaten Konawe Kepulauan', NULL, NULL, NULL),
(13, '7413', 'Kabupaten Muna Barat', NULL, NULL, NULL),
(14, '7414', 'Kabupaten Buton Tengah', NULL, NULL, NULL),
(15, '7415', 'Kabupaten Buton Selatan', NULL, NULL, NULL),
(16, '7471', 'Kota Kendari', NULL, NULL, NULL),
(17, '7472', 'Kota Baubau', NULL, NULL, NULL),
(9205, '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `wilayah_kepala`
--

CREATE TABLE `wilayah_kepala` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(30) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `jenis_kelamin` varchar(20) DEFAULT NULL,
  `usia` varchar(20) DEFAULT NULL,
  `agama` varchar(30) DEFAULT NULL,
  `pend_terakhir` varchar(50) DEFAULT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `periode_mulai` date DEFAULT NULL,
  `periode_selesai` date DEFAULT NULL,
  `avatar` text,
  `banned` tinyint(1) DEFAULT '1',
  `created_by` varchar(30) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `last_updated_by` varchar(30) DEFAULT NULL,
  `last_updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `wilayah_kepala`
--

INSERT INTO `wilayah_kepala` (`id`, `kd_wilayah`, `nama`, `nip`, `jabatan`, `jenis_kelamin`, `usia`, `agama`, `pend_terakhir`, `pekerjaan`, `no_telp`, `periode_mulai`, `periode_selesai`, `avatar`, `banned`, `created_by`, `creation_date`, `last_updated_by`, `last_updated_date`) VALUES
(2, '7303011006', 'bacc', '1', 'Lurah', 'Laki - Laki', '32', 'Islam', 'D4/S1', 'wrwerwr', '324324', '2020-05-14', '2020-05-22', NULL, 0, 'admin', '2020-05-13 20:44:44', NULL, NULL),
(3, '730301', 'cama', '2', 'Lurah', 'Laki - Laki', '5446', 'Islam', 'D4/S1', 'erwrwrwe', '3242', '2020-04-30', '2020-05-29', NULL, 0, 'admin', '2020-05-13 20:48:47', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `wilayah_perangkat`
--

CREATE TABLE `wilayah_perangkat` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(30) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `unsur_pem` varchar(30) DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `jabatan` varchar(30) DEFAULT NULL,
  `jenis_kelamin` varchar(15) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `pend_terakhir` varchar(30) DEFAULT NULL,
  `pekerjaan` varchar(50) DEFAULT NULL,
  `no_hp` varchar(30) DEFAULT NULL,
  `periode_mulai` date DEFAULT NULL,
  `periode_selesai` date DEFAULT NULL,
  `no_seq` int(2) DEFAULT NULL,
  `avatar` text,
  `created_by` varchar(30) DEFAULT NULL,
  `creation_date` datetime DEFAULT NULL,
  `last_updated_by` varchar(30) DEFAULT NULL,
  `last_updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `wilayah_perangkat`
--

INSERT INTO `wilayah_perangkat` (`id`, `kd_wilayah`, `nama`, `unsur_pem`, `nip`, `jabatan`, `jenis_kelamin`, `tgl_lahir`, `agama`, `pend_terakhir`, `pekerjaan`, `no_hp`, `periode_mulai`, `periode_selesai`, `no_seq`, `avatar`, `created_by`, `creation_date`, `last_updated_by`, `last_updated_date`) VALUES
(1, '7303011006', 'add', 'Unsur Staf', '234242', 'Staf', 'Laki - Laki', '2011-08-15', 'Islam', 'SMP/Sederajat', 'ewrw', '32424', '2020-05-12', '2021-06-15', 6, '20200515150853-2020-05-15wilayah_perangkat150719.png', 'admin', '2020-05-15 15:08:53', NULL, NULL),
(2, '7303011006', 'xxx', 'Unsur Staf', '11111111111111111', 'Sekretaris', 'Laki - Laki', '2022-02-07', 'Islam', 'SD/Sederajat', 'xxxx', '082155555047', '2020-06-08', '2020-06-08', 1, NULL, 'admin', '2020-06-07 22:44:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `wilayah_profil`
--

CREATE TABLE `wilayah_profil` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(30) NOT NULL,
  `alamat_kantor` varchar(100) DEFAULT NULL,
  `lokasi` text NOT NULL,
  `luas` double(12,2) DEFAULT NULL,
  `utara` varchar(50) DEFAULT NULL,
  `timur` varchar(50) DEFAULT NULL,
  `selatan` varchar(50) DEFAULT NULL,
  `barat` varchar(50) DEFAULT NULL,
  `sejarah` text,
  `visi_misi` text NOT NULL,
  `tahun_pembentukan` year(4) DEFAULT NULL,
  `dasar_hukum` longtext,
  `kd_pos` varchar(15) DEFAULT NULL,
  `tipologi` varchar(50) DEFAULT NULL,
  `foto` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `wilayah_profil`
--

INSERT INTO `wilayah_profil` (`id`, `kd_wilayah`, `alamat_kantor`, `lokasi`, `luas`, `utara`, `timur`, `selatan`, `barat`, `sejarah`, `visi_misi`, `tahun_pembentukan`, `dasar_hukum`, `kd_pos`, `tipologi`, `foto`) VALUES
(2, '7303011006', 'JL.Langkasa No.6', '342.0000000', 324.00, 'dsfs', 'fsf', 'dsf', 'fds', 'dsfsdfsdfsfsfsfsf', '<center>Visi</center>\n”TERWUJUDNYA KEHIDUPAN MASYARAKAT DESA KODASARI YANG  RELIGIUS, AMAN,  HARMONIS, MAJU, ADIL, DAN TERTIB (RAHMAT) “.\n<p>\n<center>Misi</center>\n1.Meningkatkan kualitas kehidupan beragama dalam mewujudkan masyarakat <p>2. Majalengka beriman dan bertaqwa.\n<p>3. Meningkatkan kualitas pendidikan dan kesehatan yang merata dan terjangkau.\n<p>4. Meningkatkan ekonomi kerakyatan yang berbasis agribisnis.\n', 2005, 'dfsfdsfsfsf', '43535', 'Dataran Rendah|Pertanian', 'bg2.jpeg'),
(3, '7303011007', 'JL.Langkasa No.7', '342.0000000', 324.00, 'dsfs', 'fsf', 'dsf', 'fds', 'dsfsdfsdfsfsfsfsf', '<center>Visi</center>\r\n”TERWUJUDNYA KEHIDUPAN MASYARAKAT DESA KODASARI YANG  RELIGIUS, AMAN,  HARMONIS, MAJU, ADIL, DAN TERTIB (RAHMAT) “.\r\n<p>\r\n<center>Misi</center>\r\n1.Meningkatkan kualitas kehidupan beragama dalam mewujudkan masyarakat <p>2. Majalengka beriman dan bertaqwa.\r\n<p>3. Meningkatkan kualitas pendidikan dan kesehatan yang merata dan terjangkau.\r\n<p>4. Meningkatkan ekonomi kerakyatan yang berbasis agribisnis.\r\n', 2005, 'dfsfdsfsfsf', '43535', 'Dataran Rendah|Pertanian', 'bg2.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `aauth_groups`
--
ALTER TABLE `aauth_groups`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `aauth_group_to_group`
--
ALTER TABLE `aauth_group_to_group`
  ADD PRIMARY KEY (`group_id`,`subgroup_id`) USING BTREE;

--
-- Indeks untuk tabel `aauth_login_attempts`
--
ALTER TABLE `aauth_login_attempts`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `aauth_perms`
--
ALTER TABLE `aauth_perms`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `aauth_perm_to_user`
--
ALTER TABLE `aauth_perm_to_user`
  ADD PRIMARY KEY (`user_id`,`perm_id`) USING BTREE;

--
-- Indeks untuk tabel `aauth_pms`
--
ALTER TABLE `aauth_pms`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `aauth_user`
--
ALTER TABLE `aauth_user`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `aauth_users`
--
ALTER TABLE `aauth_users`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `aauth_user_to_group`
--
ALTER TABLE `aauth_user_to_group`
  ADD PRIMARY KEY (`user_id`,`group_id`) USING BTREE;

--
-- Indeks untuk tabel `aauth_user_variables`
--
ALTER TABLE `aauth_user_variables`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `blog_category`
--
ALTER TABLE `blog_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indeks untuk tabel `captcha`
--
ALTER TABLE `captcha`
  ADD PRIMARY KEY (`captcha_id`) USING BTREE;

--
-- Indeks untuk tabel `cc_options`
--
ALTER TABLE `cc_options`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `crud`
--
ALTER TABLE `crud`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `crud_custom_option`
--
ALTER TABLE `crud_custom_option`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `crud_field`
--
ALTER TABLE `crud_field`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `crud_field_validation`
--
ALTER TABLE `crud_field_validation`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `crud_input_type`
--
ALTER TABLE `crud_input_type`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `crud_input_validation`
--
ALTER TABLE `crud_input_validation`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `daftar_proses`
--
ALTER TABLE `daftar_proses`
  ADD PRIMARY KEY (`id_daftar_proses`) USING BTREE,
  ADD UNIQUE KEY `nomor_urut` (`nomor_urut`);

--
-- Indeks untuk tabel `data_notaris`
--
ALTER TABLE `data_notaris`
  ADD PRIMARY KEY (`id_notaris`);

--
-- Indeks untuk tabel `keys`
--
ALTER TABLE `keys`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  ADD PRIMARY KEY (`id_laporan_bulanan`) USING BTREE,
  ADD UNIQUE KEY `nomor_urut` (`nama_notaris`);

--
-- Indeks untuk tabel `legalisasi`
--
ALTER TABLE `legalisasi`
  ADD PRIMARY KEY (`id_legalisasi`) USING BTREE,
  ADD UNIQUE KEY `nomor_urut` (`nomor_urut`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `menu_type`
--
ALTER TABLE `menu_type`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `page_block_element`
--
ALTER TABLE `page_block_element`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `reportorium`
--
ALTER TABLE `reportorium`
  ADD PRIMARY KEY (`id_reportorium`),
  ADD UNIQUE KEY `nomor_urut` (`nomor_urut`);

--
-- Indeks untuk tabel `rest`
--
ALTER TABLE `rest`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `rest_field`
--
ALTER TABLE `rest_field`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `rest_field_validation`
--
ALTER TABLE `rest_field_validation`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `rest_input_type`
--
ALTER TABLE `rest_input_type`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indeks untuk tabel `waarmerking`
--
ALTER TABLE `waarmerking`
  ADD PRIMARY KEY (`id_waarmerking`) USING BTREE,
  ADD UNIQUE KEY `nomor_urut` (`nomor_urut`);

--
-- Indeks untuk tabel `wil`
--
ALTER TABLE `wil`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `wilayah`
--
ALTER TABLE `wilayah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `wilayah_kepala`
--
ALTER TABLE `wilayah_kepala`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `wilayah_perangkat`
--
ALTER TABLE `wilayah_perangkat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `wilayah_profil`
--
ALTER TABLE `wilayah_profil`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aauth_groups`
--
ALTER TABLE `aauth_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `aauth_login_attempts`
--
ALTER TABLE `aauth_login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=785;

--
-- AUTO_INCREMENT untuk tabel `aauth_perms`
--
ALTER TABLE `aauth_perms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1048;

--
-- AUTO_INCREMENT untuk tabel `aauth_pms`
--
ALTER TABLE `aauth_pms`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `aauth_user`
--
ALTER TABLE `aauth_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `aauth_users`
--
ALTER TABLE `aauth_users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `aauth_user_variables`
--
ALTER TABLE `aauth_user_variables`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `blog`
--
ALTER TABLE `blog`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `blog_category`
--
ALTER TABLE `blog_category`
  MODIFY `category_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `captcha`
--
ALTER TABLE `captcha`
  MODIFY `captcha_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `cc_options`
--
ALTER TABLE `cc_options`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `crud`
--
ALTER TABLE `crud`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `crud_custom_option`
--
ALTER TABLE `crud_custom_option`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `crud_field`
--
ALTER TABLE `crud_field`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT untuk tabel `crud_field_validation`
--
ALTER TABLE `crud_field_validation`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT untuk tabel `crud_input_type`
--
ALTER TABLE `crud_input_type`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `crud_input_validation`
--
ALTER TABLE `crud_input_validation`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `daftar_proses`
--
ALTER TABLE `daftar_proses`
  MODIFY `id_daftar_proses` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `data_notaris`
--
ALTER TABLE `data_notaris`
  MODIFY `id_notaris` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT untuk tabel `keys`
--
ALTER TABLE `keys`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `laporan_bulanan`
--
ALTER TABLE `laporan_bulanan`
  MODIFY `id_laporan_bulanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `legalisasi`
--
ALTER TABLE `legalisasi`
  MODIFY `id_legalisasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `menu_type`
--
ALTER TABLE `menu_type`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `page_block_element`
--
ALTER TABLE `page_block_element`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `reportorium`
--
ALTER TABLE `reportorium`
  MODIFY `id_reportorium` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rest`
--
ALTER TABLE `rest`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `rest_field`
--
ALTER TABLE `rest_field`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `rest_field_validation`
--
ALTER TABLE `rest_field_validation`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT untuk tabel `rest_input_type`
--
ALTER TABLE `rest_input_type`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `waarmerking`
--
ALTER TABLE `waarmerking`
  MODIFY `id_waarmerking` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `wil`
--
ALTER TABLE `wil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9206;

--
-- AUTO_INCREMENT untuk tabel `wilayah`
--
ALTER TABLE `wilayah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9206;

--
-- AUTO_INCREMENT untuk tabel `wilayah_kepala`
--
ALTER TABLE `wilayah_kepala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `wilayah_perangkat`
--
ALTER TABLE `wilayah_perangkat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `wilayah_profil`
--
ALTER TABLE `wilayah_profil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

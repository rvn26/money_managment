/*
 Navicat Premium Dump SQL

 Source Server         : 187.77.119.63
 Source Server Type    : MySQL
 Source Server Version : 80409 (8.4.9)
 Source Host           : 187.77.119.63:2002
 Source Schema         : kepitink_db

 Target Server Type    : MySQL
 Target Server Version : 80409 (8.4.9)
 File Encoding         : 65001

 Date: 19/06/2026 23:25:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for batas_harians
-- ----------------------------
DROP TABLE IF EXISTS `batas_harians`;
CREATE TABLE `batas_harians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `batas` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batas_harians_id_user_foreign` (`id_user`),
  CONSTRAINT `batas_harians_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of batas_harians
-- ----------------------------
BEGIN;
INSERT INTO `batas_harians` (`id`, `id_user`, `batas`, `created_at`, `updated_at`) VALUES (1, 6, 100000.00, '2026-06-19 21:11:10', '2026-06-19 21:11:10');
INSERT INTO `batas_harians` (`id`, `id_user`, `batas`, `created_at`, `updated_at`) VALUES (2, 7, 100000.00, '2026-06-19 22:22:38', '2026-06-19 22:22:38');
INSERT INTO `batas_harians` (`id`, `id_user`, `batas`, `created_at`, `updated_at`) VALUES (3, 1, 150000.00, '2026-06-19 22:46:25', '2026-06-19 23:03:49');
COMMIT;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache
-- ----------------------------
BEGIN;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897', 'i:1;', 1781883082);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897:timer', 'i:1781883082;', 1781883082);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-0rtmlrzaFENcXVhN', 's:7:\"forever\";', 2097242743);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1781885257);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1781885257;', 1781885257);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-77de68daecd823babbb58edb1c8e14d7106e83bb', 'i:2;', 1781881191);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-77de68daecd823babbb58edb1c8e14d7106e83bb:timer', 'i:1781881191;', 1781881191);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4', 'i:2;', 1781693356);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4:timer', 'i:1781693356;', 1781693356);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-b1d5781111d84f7b3fe45a0852e59758cd7a87e5', 'i:1;', 1781881249);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-b1d5781111d84f7b3fe45a0852e59758cd7a87e5:timer', 'i:1781881249;', 1781881249);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f', 'i:1;', 1781876538);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-fe5dbbcea5ce7e2988b8c69bcfdfde8904aabc1f:timer', 'i:1781876538;', 1781876538);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-FU2XLvF3NwC3DAAI', 's:7:\"forever\";', 2097244834);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-JU76TsHxY4gqC7CO', 's:7:\"forever\";', 2097237028);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-M5QILbTz2SMgG8ZS', 's:7:\"forever\";', 2097245704);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-pyYBoMF5mocNK3Bo', 's:7:\"forever\";', 2097236726);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES ('kepitink-cache-Rt7AWrklBNgaU4zm', 's:7:\"forever\";', 2097244751);
COMMIT;

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for fcm_tokens
-- ----------------------------
DROP TABLE IF EXISTS `fcm_tokens`;
CREATE TABLE `fcm_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fcm_tokens_id_user_token_unique` (`id_user`,`token`),
  CONSTRAINT `fcm_tokens_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of fcm_tokens
-- ----------------------------
BEGIN;
INSERT INTO `fcm_tokens` (`id`, `id_user`, `token`, `device_name`, `created_at`, `updated_at`) VALUES (2, 8, 'eL36Kfn-RNijstv5PBP4TN:APA91bFoYj4nm1epMXf87c6FZa3TKj0x4crUGff21L06OmJQpoyISgU3NQ245VdjyqG7EFuhJxhrHVw7vbWynwzwzZRVUozA7raQpLDeqj4ItjDae2yvWj0', 'Android', '2026-06-19 22:26:22', '2026-06-19 22:26:22');
COMMIT;

-- ----------------------------
-- Table structure for hutangs
-- ----------------------------
DROP TABLE IF EXISTS `hutangs`;
CREATE TABLE `hutangs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `id_teman` bigint unsigned DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal_pinjaman` date NOT NULL,
  `status` enum('belum_lunas','lunas','terlambat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_lunas',
  `metode_pembayaran` enum('Qris','Bank','Dana','Gopay','Cash') COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hutangs_id_user_foreign` (`id_user`),
  KEY `hutangs_id_teman_foreign` (`id_teman`),
  CONSTRAINT `hutangs_id_teman_foreign` FOREIGN KEY (`id_teman`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `hutangs_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of hutangs
-- ----------------------------
BEGIN;
INSERT INTO `hutangs` (`id`, `id_user`, `id_teman`, `nama`, `jumlah`, `tanggal_pinjaman`, `status`, `metode_pembayaran`, `catatan`, `created_at`, `updated_at`) VALUES (1, 7, NULL, 'Tisu Bius', 12000.00, '2026-06-19', 'belum_lunas', 'Cash', NULL, '2026-06-19 21:17:40', '2026-06-19 21:17:40');
INSERT INTO `hutangs` (`id`, `id_user`, `id_teman`, `nama`, `jumlah`, `tanggal_pinjaman`, `status`, `metode_pembayaran`, `catatan`, `created_at`, `updated_at`) VALUES (3, 8, NULL, 'Panji', 1.00, '2026-06-19', 'lunas', 'Bank', NULL, '2026-06-19 22:19:45', '2026-06-19 22:19:54');
INSERT INTO `hutangs` (`id`, `id_user`, `id_teman`, `nama`, `jumlah`, `tanggal_pinjaman`, `status`, `metode_pembayaran`, `catatan`, `created_at`, `updated_at`) VALUES (8, 10, 8, 'TUP_Ervan  Hapiz', 11999999.00, '2026-06-03', 'belum_lunas', 'Qris', 'WOKW', '2026-06-19 23:08:51', '2026-06-19 23:08:51');
INSERT INTO `hutangs` (`id`, `id_user`, `id_teman`, `nama`, `jumlah`, `tanggal_pinjaman`, `status`, `metode_pembayaran`, `catatan`, `created_at`, `updated_at`) VALUES (9, 1, 8, 'TUP_Ervan  Hapiz', 11000000.00, '2026-11-11', 'belum_lunas', 'Cash', 'xzXZ', '2026-06-19 23:09:09', '2026-06-19 23:09:09');
COMMIT;

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of job_batches
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of jobs
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for kategori_tagihans
-- ----------------------------
DROP TABLE IF EXISTS `kategori_tagihans`;
CREATE TABLE `kategori_tagihans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emoji` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warna` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategori_tagihans_id_user_foreign` (`id_user`),
  CONSTRAINT `kategori_tagihans_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of kategori_tagihans
-- ----------------------------
BEGIN;
INSERT INTO `kategori_tagihans` (`id`, `id_user`, `nama`, `emoji`, `warna`, `deskripsi`, `created_at`, `updated_at`) VALUES (1, 8, 'air', '💧', '#06b6d4', 'nanab', '2026-06-19 21:47:20', '2026-06-19 21:47:20');
INSERT INTO `kategori_tagihans` (`id`, `id_user`, `nama`, `emoji`, `warna`, `deskripsi`, `created_at`, `updated_at`) VALUES (2, 1, 'Tagihan bulanan', '🍔', '#ef4444', 'sjabsja', '2026-06-19 22:50:51', '2026-06-19 22:50:51');
INSERT INTO `kategori_tagihans` (`id`, `id_user`, `nama`, `emoji`, `warna`, `deskripsi`, `created_at`, `updated_at`) VALUES (3, 1, 'Listrik', '🍕', '#14b8a6', 'listrik', '2026-06-19 23:14:39', '2026-06-19 23:14:39');
COMMIT;

-- ----------------------------
-- Table structure for kategoris
-- ----------------------------
DROP TABLE IF EXISTS `kategoris`;
CREATE TABLE `kategoris` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emoji` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warna` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_user` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kategoris_id_user_foreign` (`id_user`),
  CONSTRAINT `kategoris_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of kategoris
-- ----------------------------
BEGIN;
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (1, 'makanan', '🍔', '#ef4444', 'asasas', 2, '2026-06-16 00:48:40', '2026-06-16 00:48:40');
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (2, 'makan dan minum', '🍔', '#f97316', 'Kategori pengeluaran untuk makanan dan minuman', 1, '2026-06-19 04:09:42', '2026-06-19 04:09:42');
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (3, 'makanan', '🍉', '#ef4444', 'makanan lejat', 8, '2026-06-19 21:45:54', '2026-06-19 21:45:54');
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (4, 'smakan', '🍔', '#ef4444', 'hjkjh', 3, '2026-06-19 21:58:14', '2026-06-19 21:58:14');
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (5, 'makanan-1', '🍕', '#f97316', 'dfgsg', 10, '2026-06-19 21:58:23', '2026-06-19 21:58:23');
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (6, 'makan', '🍔', '#ef4444', 'ndajnda', 1, '2026-06-19 21:59:51', '2026-06-19 21:59:51');
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (7, 'minuman', '🥃', '#84cc16', 'test', 8, '2026-06-19 22:03:41', '2026-06-19 22:03:41');
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (8, 'lain lain', '🍈', '#f97316', 'mNabsbs', 8, '2026-06-19 22:15:59', '2026-06-19 22:15:59');
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (9, 'Tagihan', NULL, NULL, 'Pengeluaran dari tagihan', 1, '2026-06-19 22:51:29', '2026-06-19 22:51:29');
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (10, 'Tagihan', NULL, NULL, 'Pengeluaran dari tagihan', 8, '2026-06-19 22:52:12', '2026-06-19 22:52:12');
INSERT INTO `kategoris` (`id`, `nama`, `emoji`, `warna`, `deskripsi`, `id_user`, `created_at`, `updated_at`) VALUES (11, 'Air', '💧', '#14b8a6', 'adad', 1, '2026-06-19 23:14:06', '2026-06-19 23:14:06');
COMMIT;

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
BEGIN;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4, '2025_09_02_075243_add_two_factor_columns_to_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5, '2026_01_08_205637_create_kategoris_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6, '2026_01_08_210640_create_pengeluarans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7, '2026_01_12_224759_create_pemasukans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8, '2026_01_13_173304_create_kategori_tagihans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9, '2026_01_13_173912_create_tagihans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10, '2026_01_19_013510_create_batas_harians_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11, '2026_03_13_153652_create_hutangs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12, '2026_05_22_133701_add_emoji_to_kategoris_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13, '2026_05_23_150222_add_warna_to_kategoris_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14, '2026_05_23_151454_add_emoji_and_warna_to_kategori_tagihans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15, '2026_05_31_120000_create_pertemanans_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16, '2026_05_31_120001_add_id_teman_to_hutangs_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17, '2026_06_09_204849_add_google_id_to_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18, '2026_06_12_170723_create_fcm_tokens_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19, '2026_06_12_170744_create_notifikasis_table', 1);
COMMIT;

-- ----------------------------
-- Table structure for notifikasis
-- ----------------------------
DROP TABLE IF EXISTS `notifikasis`;
CREATE TABLE `notifikasis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `dibaca_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifikasis_id_user_dibaca_at_index` (`id_user`,`dibaca_at`),
  CONSTRAINT `notifikasis_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of notifikasis
-- ----------------------------
BEGIN;
INSERT INTO `notifikasis` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `data`, `dibaca_at`, `created_at`, `updated_at`) VALUES (1, 10, 'Pertemanan Diterima', 'TUP_Ervan  Hapiz menerima permintaan pertemanan kamu.', 'pertemanan', '{\"aksi\": \"permintaan_diterima\", \"pertemanan_id\": \"3\"}', NULL, '2026-06-19 22:23:35', '2026-06-19 22:23:35');
INSERT INTO `notifikasis` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `data`, `dibaca_at`, `created_at`, `updated_at`) VALUES (2, 8, 'Hutang Baru', 'WILDAN DAFFA\' HAKIM PUTRA ANTARA mencatat hutang kamu sebesar Rp220.000.000.', 'hutang', '{\"aksi\": \"hutang_baru\", \"hutang_id\": \"4\"}', '2026-06-19 22:26:26', '2026-06-19 22:24:13', '2026-06-19 22:26:26');
INSERT INTO `notifikasis` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `data`, `dibaca_at`, `created_at`, `updated_at`) VALUES (3, 8, 'Hutang Baru', 'WILDAN DAFFA\' HAKIM PUTRA ANTARA mencatat hutang kamu sebesar Rp999.999.', 'hutang', '{\"aksi\": \"hutang_baru\", \"hutang_id\": \"5\"}', '2026-06-19 22:30:01', '2026-06-19 22:26:53', '2026-06-19 22:30:01');
INSERT INTO `notifikasis` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `data`, `dibaca_at`, `created_at`, `updated_at`) VALUES (4, 10, 'Pertemanan Diterima', 'TUP_Ervan  Hapiz menerima permintaan pertemanan kamu.', 'pertemanan', '{\"aksi\": \"permintaan_diterima\", \"pertemanan_id\": \"4\"}', NULL, '2026-06-19 22:30:39', '2026-06-19 22:30:39');
INSERT INTO `notifikasis` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `data`, `dibaca_at`, `created_at`, `updated_at`) VALUES (5, 1, 'Pertemanan Diterima', 'TUP_Ervan  Hapiz menerima permintaan pertemanan kamu.', 'pertemanan', '{\"aksi\": \"permintaan_diterima\", \"pertemanan_id\": \"5\"}', NULL, '2026-06-19 22:55:01', '2026-06-19 22:55:01');
INSERT INTO `notifikasis` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `data`, `dibaca_at`, `created_at`, `updated_at`) VALUES (6, 10, 'Pertemanan Diterima', 'TUP_Ervan  Hapiz menerima permintaan pertemanan kamu.', 'pertemanan', '{\"aksi\": \"permintaan_diterima\", \"pertemanan_id\": \"6\"}', NULL, '2026-06-19 22:55:02', '2026-06-19 22:55:02');
INSERT INTO `notifikasis` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `data`, `dibaca_at`, `created_at`, `updated_at`) VALUES (7, 8, 'Hutang Baru', 'WILDAN DAFFA\' HAKIM PUTRA ANTARA mencatat hutang kamu sebesar Rp119.900.', 'hutang', '{\"aksi\": \"hutang_baru\", \"hutang_id\": \"7\"}', NULL, '2026-06-19 22:55:25', '2026-06-19 22:55:25');
INSERT INTO `notifikasis` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `data`, `dibaca_at`, `created_at`, `updated_at`) VALUES (8, 10, 'Pertemanan Diterima', 'TUP_Ervan  Hapiz menerima permintaan pertemanan kamu.', 'pertemanan', '{\"aksi\": \"permintaan_diterima\", \"pertemanan_id\": \"7\"}', NULL, '2026-06-19 23:08:09', '2026-06-19 23:08:09');
INSERT INTO `notifikasis` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `data`, `dibaca_at`, `created_at`, `updated_at`) VALUES (9, 8, 'Hutang Baru', 'WILDAN DAFFA\' HAKIM PUTRA ANTARA mencatat hutang kamu sebesar Rp11.999.999.', 'hutang', '{\"aksi\": \"hutang_baru\", \"hutang_id\": \"8\"}', NULL, '2026-06-19 23:08:51', '2026-06-19 23:08:51');
INSERT INTO `notifikasis` (`id`, `id_user`, `judul`, `pesan`, `tipe`, `data`, `dibaca_at`, `created_at`, `updated_at`) VALUES (10, 8, 'Hutang Baru', 'ervan hapiz mencatat hutang kamu sebesar Rp11.000.000.', 'hutang', '{\"aksi\": \"hutang_baru\", \"hutang_id\": \"9\"}', NULL, '2026-06-19 23:09:09', '2026-06-19 23:09:09');
COMMIT;

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for pemasukans
-- ----------------------------
DROP TABLE IF EXISTS `pemasukans`;
CREATE TABLE `pemasukans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('gaji','bonus','penjualan','investasi','lain-lain') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gaji',
  `total` decimal(15,2) NOT NULL,
  `metode_pembayaran` enum('Qris','Bank','Dana','Gopay','Cash') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash',
  `status` enum('pending','lunas') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pemasukans_id_user_foreign` (`id_user`),
  CONSTRAINT `pemasukans_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of pemasukans
-- ----------------------------
BEGIN;
INSERT INTO `pemasukans` (`id`, `id_user`, `tanggal`, `jenis`, `total`, `metode_pembayaran`, `status`, `deskripsi`, `created_at`, `updated_at`) VALUES (1, 1, '2026-06-19', 'gaji', 1000000.00, 'Qris', 'lunas', 'sdkdsnadnsa', '2026-06-19 04:29:07', '2026-06-19 04:29:07');
INSERT INTO `pemasukans` (`id`, `id_user`, `tanggal`, `jenis`, `total`, `metode_pembayaran`, `status`, `deskripsi`, `created_at`, `updated_at`) VALUES (2, 1, '2026-06-19', 'gaji', 1000000.00, 'Qris', 'lunas', 'dsihasoihd', '2026-06-19 21:10:02', '2026-06-19 21:10:02');
INSERT INTO `pemasukans` (`id`, `id_user`, `tanggal`, `jenis`, `total`, `metode_pembayaran`, `status`, `deskripsi`, `created_at`, `updated_at`) VALUES (3, 6, '2026-06-12', 'bonus', 6000000.00, 'Qris', 'lunas', 'ttt', '2026-06-19 21:10:10', '2026-06-19 21:10:10');
INSERT INTO `pemasukans` (`id`, `id_user`, `tanggal`, `jenis`, `total`, `metode_pembayaran`, `status`, `deskripsi`, `created_at`, `updated_at`) VALUES (4, 3, '2026-06-03', 'investasi', 7.00, 'Bank', 'pending', 'sas', '2026-06-19 21:10:50', '2026-06-19 21:10:50');
INSERT INTO `pemasukans` (`id`, `id_user`, `tanggal`, `jenis`, `total`, `metode_pembayaran`, `status`, `deskripsi`, `created_at`, `updated_at`) VALUES (5, 1, '2026-06-19', 'gaji', 1000000.00, 'Qris', 'lunas', 'dsadsa', '2026-06-19 22:49:15', '2026-06-19 22:49:15');
COMMIT;

-- ----------------------------
-- Table structure for pengeluarans
-- ----------------------------
DROP TABLE IF EXISTS `pengeluarans`;
CREATE TABLE `pengeluarans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `id_kategori` bigint unsigned NOT NULL,
  `tanggal_pengeluaran` date NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tujuan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metode_pembayaran` enum('Qris','Bank','Dana','Gopay','Cash') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash',
  `status` enum('draft','approved','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengeluarans_id_user_foreign` (`id_user`),
  KEY `pengeluarans_id_kategori_foreign` (`id_kategori`),
  CONSTRAINT `pengeluarans_id_kategori_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategoris` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pengeluarans_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of pengeluarans
-- ----------------------------
BEGIN;
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (1, 1, 2, '2026-06-18', 15000.00, 'bayar ayam', 'beli ayam', 'Bank', 'paid', '2026-06-19 04:18:28', '2026-06-19 04:18:28');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (2, 1, 2, '2026-06-19', 16000.00, 'dassada', 'beli ayam', 'Qris', 'paid', '2026-06-19 04:19:03', '2026-06-19 04:19:03');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (3, 8, 3, '2026-06-19', 10000.00, 'alahsh', 'ayam', 'Qris', 'paid', '2026-06-19 21:54:29', '2026-06-19 21:54:29');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (4, 8, 7, '2026-06-19', 5000.00, 'Scan struk: AIR MINERAL BOTOL x1', 'AIR MINERAL BOTOL', 'Cash', 'paid', '2026-06-19 22:14:27', '2026-06-19 22:14:27');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (5, 8, 7, '2026-06-19', 8637.00, 'Scan struk: GREEN THAI TEA x1', 'GREEN THAI TEA', 'Cash', 'paid', '2026-06-19 22:14:28', '2026-06-19 22:14:28');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (6, 8, 3, '2026-06-19', 10910.00, 'Scan struk: MIE GACOAN LV 1 x1', 'MIE GACOAN LV 1', 'Cash', 'paid', '2026-06-19 22:14:28', '2026-06-19 22:14:28');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (7, 8, 3, '2026-06-19', 10910.00, 'Scan struk: MIE HOMPIMPA LV 1 x1', 'MIE HOMPIMPA LV 1', 'Cash', 'paid', '2026-06-19 22:14:28', '2026-06-19 22:14:28');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (8, 8, 3, '2026-06-19', 30000.00, 'Scan struk: UDANG KEJU x3', 'UDANG KEJU', 'Cash', 'paid', '2026-06-19 22:14:29', '2026-06-19 22:14:29');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (9, 8, 7, '2026-06-19', 8637.00, 'Scan struk: VANILLA LATTE ICE x1', 'VANILLA LATTE ICE', 'Cash', 'paid', '2026-06-19 22:14:29', '2026-06-19 22:14:29');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (11, 1, 2, '2026-06-19', 15000.00, 'ahudahd', 'beli ayam', 'Qris', 'paid', '2026-06-19 22:47:53', '2026-06-19 22:47:53');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (12, 8, 3, '2026-06-19', 15000.00, 'ggg', 'ata', 'Qris', 'paid', '2026-06-19 22:48:41', '2026-06-19 22:48:41');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (13, 1, 9, '2026-06-19', 750000.00, 'asas', 'bayar kos', 'Qris', 'paid', '2026-06-19 22:51:29', '2026-06-19 22:51:29');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (14, 8, 10, '2026-06-19', 100000.00, 'hshay', 'kao', 'Qris', 'paid', '2026-06-19 22:52:12', '2026-06-19 22:52:12');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (16, 1, 2, '2026-06-19', 28000.00, 'SIU MAI', 'SIU MAI', 'Cash', 'approved', '2026-06-19 22:56:27', '2026-06-19 22:56:27');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (17, 1, 2, '2026-06-19', 29000.00, 'WONTON IN CHILI OIL', 'WONTON IN CHILI OIL', 'Cash', 'approved', '2026-06-19 22:56:27', '2026-06-19 22:56:27');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (18, 1, 2, '2026-06-19', 42000.00, 'Tim ayam kampung', 'Tim ayam kampung', 'Cash', 'approved', '2026-06-19 22:56:27', '2026-06-19 22:56:27');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (19, 1, 2, '2026-06-19', 12000.00, 'AIR MINERAL', 'AIR MINERAL', 'Cash', 'approved', '2026-06-19 22:56:27', '2026-06-19 22:56:27');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (20, 8, 7, '2026-06-19', 5000.00, 'Scan struk: AIR MINERAL BOTOL x1', 'AIR MINERAL BOTOL', 'Cash', 'paid', '2026-06-19 22:56:35', '2026-06-19 22:56:35');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (21, 8, 7, '2026-06-19', 8637.00, 'Scan struk: GREEN THAI TEA x1', 'GREEN THAI TEA', 'Cash', 'paid', '2026-06-19 22:56:36', '2026-06-19 22:56:36');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (22, 8, 3, '2026-06-19', 10910.00, 'Scan struk: MIE GACAOAN LV 1 x1', 'MIE GACAOAN LV 1', 'Cash', 'paid', '2026-06-19 22:56:37', '2026-06-19 22:56:37');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (23, 8, 3, '2026-06-19', 10910.00, 'Scan struk: MIE HOMPIMPA LV 1 x1', 'MIE HOMPIMPA LV 1', 'Cash', 'paid', '2026-06-19 22:56:37', '2026-06-19 22:56:37');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (24, 8, 3, '2026-06-19', 30000.00, 'Scan struk: UDANG KEJU x3', 'UDANG KEJU', 'Cash', 'paid', '2026-06-19 22:56:38', '2026-06-19 22:56:38');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (25, 8, 7, '2026-06-19', 8637.00, 'Scan struk: VANILLA LATTE ICE x1', 'VANILLA LATTE ICE', 'Cash', 'paid', '2026-06-19 22:56:39', '2026-06-19 22:56:39');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (26, 1, 2, '2026-06-19', 41000.00, 'BAKMEE AYAM KOMPLIT', 'BAKMEE AYAM KOMPLIT', 'Cash', 'approved', '2026-06-19 23:06:54', '2026-06-19 23:06:54');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (27, 1, 2, '2026-06-19', 28000.00, 'SIU MAI', 'SIU MAI', 'Cash', 'approved', '2026-06-19 23:06:54', '2026-06-19 23:06:54');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (28, 1, 2, '2026-06-19', 29000.00, 'WONTON IN CHILI OIL', 'WONTON IN CHILI OIL', 'Cash', 'approved', '2026-06-19 23:06:54', '2026-06-19 23:06:54');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (29, 1, 2, '2026-06-19', 42000.00, 'Tim ayam kampung', 'Tim ayam kampung', 'Cash', 'approved', '2026-06-19 23:06:54', '2026-06-19 23:06:54');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (30, 1, 2, '2026-06-19', 12000.00, 'AIR MINERAL', 'AIR MINERAL', 'Cash', 'approved', '2026-06-19 23:06:54', '2026-06-19 23:06:54');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (31, 8, 7, '2026-06-19', 5000.00, 'Scan struk: AIR MINERAL BOTOL x1', 'AIR MINERAL BOTOL', 'Cash', 'paid', '2026-06-19 23:06:56', '2026-06-19 23:06:56');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (32, 8, 7, '2026-06-19', 8637.00, 'Scan struk: GREEN THAI TEA x1', 'GREEN THAI TEA', 'Cash', 'paid', '2026-06-19 23:06:58', '2026-06-19 23:06:58');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (33, 8, 3, '2026-06-19', 10910.00, 'Scan struk: MIE GACAOAN LV 1 x1', 'MIE GACAOAN LV 1', 'Cash', 'paid', '2026-06-19 23:06:58', '2026-06-19 23:06:58');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (34, 8, 3, '2026-06-19', 10910.00, 'Scan struk: MIE HOMPIMPA LV 1 x1', 'MIE HOMPIMPA LV 1', 'Cash', 'paid', '2026-06-19 23:06:59', '2026-06-19 23:06:59');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (35, 8, 3, '2026-06-19', 30000.00, 'Scan struk: UDANG KEJU x3', 'UDANG KEJU', 'Cash', 'paid', '2026-06-19 23:06:59', '2026-06-19 23:06:59');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (36, 8, 7, '2026-06-19', 8637.00, 'Scan struk: VANILLA LATTE ICE x1', 'VANILLA LATTE ICE', 'Cash', 'paid', '2026-06-19 23:07:00', '2026-06-19 23:07:00');
INSERT INTO `pengeluarans` (`id`, `id_user`, `id_kategori`, `tanggal_pengeluaran`, `total`, `description`, `tujuan`, `metode_pembayaran`, `status`, `created_at`, `updated_at`) VALUES (37, 1, 9, '2026-06-19', 7999999.00, 'daada', 'bayar kos', 'Qris', 'paid', '2026-06-19 23:11:59', '2026-06-19 23:11:59');
COMMIT;

-- ----------------------------
-- Table structure for pertemanans
-- ----------------------------
DROP TABLE IF EXISTS `pertemanans`;
CREATE TABLE `pertemanans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `id_teman` bigint unsigned NOT NULL,
  `status` enum('pending','accepted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pertemanans_id_user_id_teman_unique` (`id_user`,`id_teman`),
  KEY `pertemanans_id_teman_foreign` (`id_teman`),
  CONSTRAINT `pertemanans_id_teman_foreign` FOREIGN KEY (`id_teman`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pertemanans_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of pertemanans
-- ----------------------------
BEGIN;
INSERT INTO `pertemanans` (`id`, `id_user`, `id_teman`, `status`, `created_at`, `updated_at`) VALUES (5, 1, 8, 'accepted', '2026-06-19 22:54:46', '2026-06-19 22:55:01');
INSERT INTO `pertemanans` (`id`, `id_user`, `id_teman`, `status`, `created_at`, `updated_at`) VALUES (7, 10, 8, 'accepted', '2026-06-19 23:07:50', '2026-06-19 23:08:09');
COMMIT;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of sessions
-- ----------------------------
BEGIN;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('07ErH827IcMmE0XDFuZRA0WEph8opsWUMjIqNwZk', 4, '10.0.1.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 OPR/131.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTkpJMFNvQzlIR1hxT3RsVmkwc2pIUkxjME93b1QwM0ZRWkZlUTJiYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkL3RlbWFuIjtzOjU6InJvdXRlIjtzOjEwOiJwZXJ0ZW1hbmFuIjt9czo1OiJzdGF0ZSI7czo0MDoiMXJtM05lbzhJR2VwWHFWeUxrYVFiREw3TEpacFk0SU4ycDFabWVrUSI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1781885653);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('7TkHEIzgPllb0qSJV04OBCFVnVO2EPCI92Mk6s9E', 6, '10.0.1.12', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibmI4RGl3c0c5aHMzMFRZZ3JmdzhYSHlWUFlScWc1RHd6d2R6NE56bCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjU6InN0YXRlIjtzOjQwOiIzS05SWnFqMnVPZ2dEeWJ1SzZwRmd3bFRpcUdrVExLaHZMalRhMUtDIjtzOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=', 1781883471);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('bRmL6D93G4F4V7AgytHtyXz4ZQba58o7f7kHJP6k', NULL, '10.0.1.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiekxJb2s0OGRHeDRTckUxN0ZUT2lGV0xUc0dxMGx5dllmRzBEdU5aOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781880469);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('bt8Iv9HN7IjrooHtJCTM0vKaWPvGR9DWx42YPUT0', NULL, '10.0.1.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZDZ5OVZ2dkRnc3FMMnlZUmh4b2JhMUpIVzI5RVR5aXJ3aGhHS2lITyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkL3JlZ2lzdGVyIjtzOjU6InJvdXRlIjtzOjg6InJlZ2lzdGVyIjt9fQ==', 1781885715);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('C0AhGvzwItT45OzLnsqbuE8BtcAfExYutqpXjjQP', NULL, '10.0.1.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRTBOZWdiMFU1ZUwwU2xmdjRobzNWVnZFODViQXZIN3ZaSGVudXh4SiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781880468);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('d7I1VHc1s7bkfuhrEDiMxwc9pAlyoLgcFj0RjiZK', NULL, '10.0.1.12', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1NHNjFQb1B5Ujl5OVVFM3FUWlNEQ0xxWFo2VzVSVXRGMVFoNzBuUCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO319', 1781885383);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('eanUJ8EiubdNeN2r1LsB7HuZp0JdDUNzvOYTnmmf', 7, '10.0.1.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYmxkTklFWmRUZTc5MFhLSVNnWFA3cnJ4enBLYWsybWR0cjBUdDBQUiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2tlcGl0aW5rLndlYi5pZC9rYXRlZ29yaSI7czo1OiJyb3V0ZSI7czo4OiJrYXRlZ29yaSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NToic3RhdGUiO3M6NDA6ImR2ajBvM3g4alpybk50VHQwRVJxMGRxRjlvOFU5dHBqNE5Yak96NHYiO3M6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjc7fQ==', 1781885512);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('oBvP2m6XXeeA3EqQ001bxUeNpeaGLg9ntRCX08qJ', 9, '10.0.1.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiQkJnOWw1MHBIMHVhamlQMXBMWHlQb0tLcm5Oa0xOR3dackxRRzdCNSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkL2h1dGFuZyI7czo1OiJyb3V0ZSI7czo2OiJodXRhbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo5O3M6MzoidXJsIjthOjA6e319', 1781884067);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('pq9x4uuDyglE3US8BU1u1KMOAZPMjt5m5VYjPzde', NULL, '10.0.1.12', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1ZPTkpiczVsaVlHTTZhdlJmajFyWDRnWWtwWWJ5SGRjMGlDYkNjRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkL2ZvcmdvdC1wYXNzd29yZCI7czo1OiJyb3V0ZSI7czoxNjoicGFzc3dvcmQucmVxdWVzdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781886052);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('tmA4UTr9XTvEXlFYghCVIvkWyFV5C8USeEw95SE6', NULL, '10.0.1.12', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTHdzVjgyODNCZjJ0ZVpOejVwbXRHT3ROallSWDNDb1ZEaVE2aVdtRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkL3JlZ2lzdGVyIjtzOjU6InJvdXRlIjtzOjg6InJlZ2lzdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1781879002);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('TxmB5RUex8Gck81pHa69ibCBoeaoa6Q1gmKBQtkW', NULL, '10.0.1.12', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic3B6UEZoa0hrUlRxWGkyZWhZVDBVNUo1WFBhcnZmaFVVVWhsVVZOcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781880395);
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('y5AtPPYzx9SJ9Agopy3sFn3GUiWgcB9pZA0gCxXj', NULL, '10.0.1.12', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYzZWRmNJVVNnRUlIMG9IQjlwZ2tqWXN1ako4UDZEQWFnazNWUm9jYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8va2VwaXRpbmsud2ViLmlkIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781879398);
COMMIT;

-- ----------------------------
-- Table structure for tagihans
-- ----------------------------
DROP TABLE IF EXISTS `tagihans`;
CREATE TABLE `tagihans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint unsigned NOT NULL,
  `kategori` bigint unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` decimal(15,2) NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `status` enum('belum_dibayar','lunas','terlambat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dibayar',
  `metode_pembayaran` enum('Qris','Bank','Dana','Gopay','Cash') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash',
  `pengulangan` enum('sekali_bayar','bulanan','tahunan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sekali_bayar',
  `catatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tagihans_id_user_foreign` (`id_user`),
  KEY `tagihans_kategori_foreign` (`kategori`),
  CONSTRAINT `tagihans_id_user_foreign` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tagihans_kategori_foreign` FOREIGN KEY (`kategori`) REFERENCES `kategori_tagihans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of tagihans
-- ----------------------------
BEGIN;
INSERT INTO `tagihans` (`id`, `id_user`, `kategori`, `nama`, `nominal`, `jatuh_tempo`, `status`, `metode_pembayaran`, `pengulangan`, `catatan`, `created_at`, `updated_at`) VALUES (1, 1, 2, 'bayar kosa', 750000.00, '2026-06-19', 'lunas', 'Qris', 'bulanan', 'asas', '2026-06-19 22:51:29', '2026-06-19 22:51:45');
INSERT INTO `tagihans` (`id`, `id_user`, `kategori`, `nama`, `nominal`, `jatuh_tempo`, `status`, `metode_pembayaran`, `pengulangan`, `catatan`, `created_at`, `updated_at`) VALUES (2, 8, 1, 'kas', 100000.00, '2026-06-19', 'lunas', 'Qris', 'bulanan', 'hshay', '2026-06-19 22:52:12', '2026-06-19 23:12:27');
COMMIT;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
BEGIN;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES (1, 'ervan hapiz', 'ervannn26@gmail.com', '2026-06-15 23:00:39', '$2y$12$AMWwiz2QJuSedMi6c7L1X.XXlZy13aonZWmBjM5CJ5leUWHbcsfiy', NULL, NULL, NULL, NULL, '2026-06-15 23:00:38', '2026-06-15 23:00:39', '106468177018213995460');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES (2, 'TUP_Ervan  Hapiz', '2311102206@ittelkom-pwt.ac.id', '2026-06-16 00:48:00', '$2y$12$DTSao8YpS1I27uEeWHQ0s.yOMKL8Is8lGVYkt0DLlEF97jZ7CiJOi', NULL, NULL, NULL, NULL, '2026-06-15 23:59:32', '2026-06-15 23:59:32', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES (3, 'Rouge Rose', 'adithanadharma66@gmail.com', '2026-06-16 21:37:35', '$2y$12$nQsA/MnG9zyvUNcaiY6xouVwvtUsZCz27EIC21L0eJfkaJW8pMMlq', NULL, NULL, NULL, NULL, '2026-06-16 21:37:35', '2026-06-16 21:37:35', '105312401670804506896');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES (4, 'ADITHANA DHARMA PUTRA', '2311102207@ittelkom-pwt.ac.id', '2026-06-16 21:39:00', '$2y$12$pdvTMP5/QI8JRXWrA2QKIeyi6/gLXTnVYKPOgxnrYXVw1dZsYm7iS', NULL, NULL, NULL, NULL, '2026-06-16 21:39:00', '2026-06-16 21:39:00', '106606631103230534891');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES (5, 'Ervan Hapiz', 'makjuicipicip@gmail.com', '2026-06-17 17:48:58', '$2y$12$WhyCU6XdEVErm1NEWvnxyeiWiwycojgMos/bDfPbCUqMW2wRhrGpy', NULL, NULL, NULL, NULL, '2026-06-17 17:33:56', '2026-06-17 17:48:58', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES (6, 'Muhammad Azka Hermawan', 'azkah2494@gmail.com', '2026-06-19 17:23:03', '$2y$12$LjIbrXr0z9QDfU7q18C14.jr.RgUPjhUnhOaGAHHyBilnjLF/lxXS', NULL, NULL, NULL, NULL, '2026-06-19 17:23:03', '2026-06-19 17:23:03', '103686610602485138682');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES (7, 'Hendwi Saputra', 'hendwisaputra28@gmail.com', '2026-06-19 17:23:54', '$2y$12$niQdSrSXZL1WFH50ru1bAu4royAYAQvQEt2ml0.qrIS/T.qwypOou', NULL, NULL, NULL, NULL, '2026-06-19 17:23:54', '2026-06-19 17:23:54', '101657808716263510720');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES (8, 'TUP_Ervan  Hapiz', 'bigboos2604@gmail.com', '2026-06-19 20:41:18', '$2y$12$.rvOoYDrMV4jInapgqoi5.Jt8Qi8N6y9S9NdZdv4Rn7IIRuyAwP1u', NULL, NULL, NULL, NULL, '2026-06-19 20:40:44', '2026-06-19 20:41:18', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES (9, 'Panjaya', 'panjiutomo2324@gmail.com', '2026-06-19 22:30:22', '$2y$12$pe0KriDkGwYLXbD2cKiLie8trABY1jXb0zUiBAvf4Y.17wYJwB81.', NULL, NULL, NULL, NULL, '2026-06-19 21:09:39', '2026-06-19 22:30:22', NULL);
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `google_id`) VALUES (10, 'WILDAN DAFFA\' HAKIM PUTRA ANTARA', '2311102055@ittelkom-pwt.ac.id', '2026-06-19 21:53:15', '$2y$12$CVZCPe7PY1pdMtb8wX/JuuK.eo/YLQtCAfdzeKMcK7ev.H2AIH6AO', NULL, NULL, NULL, NULL, '2026-06-19 21:53:15', '2026-06-19 21:53:15', '112605231038918495039');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

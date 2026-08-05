-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 05, 2026 at 05:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `presensi-magang`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `latitude_in` varchar(255) DEFAULT NULL,
  `longitude_in` varchar(255) DEFAULT NULL,
  `latitude_out` varchar(255) DEFAULT NULL,
  `longitude_out` varchar(255) DEFAULT NULL,
  `early_leave_reason` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Hadir',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `user_id`, `date`, `time_in`, `time_out`, `latitude_in`, `longitude_in`, `latitude_out`, `longitude_out`, `early_leave_reason`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, '2026-07-31', '10:59:15', NULL, NULL, NULL, NULL, NULL, NULL, 'Hadir', '2026-07-30 18:59:42', '2026-07-30 18:59:42'),
(2, 3, '2026-08-03', '08:12:32', NULL, NULL, NULL, NULL, NULL, NULL, 'Hadir', '2026-08-02 16:12:32', '2026-08-02 16:12:32'),
(3, 3, '2026-08-04', '08:10:00', NULL, NULL, NULL, NULL, NULL, NULL, 'Hadir', '2026-08-03 16:10:00', '2026-08-03 16:10:00'),
(4, 3, '2026-08-08', '10:08:54', '10:09:09', NULL, NULL, '-8.592368239128307', '116.0968307692122', 'bebas', 'Hadir', '2026-08-07 18:08:54', '2026-08-07 18:09:09');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `izin_magangs`
--

CREATE TABLE `izin_magangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `magang_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_izin` date NOT NULL,
  `alasan` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `magangs`
--

CREATE TABLE `magangs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `asal_instansi` varchar(255) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `durasi_kerja` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_28_004905_create_magangs_table', 2),
(5, '2026_07_28_005009_create_izin_magangs_table', 2),
(6, '2026_07_28_010048_add_intern_columns_to_users_table', 3),
(7, '2026_07_28_010418_create_attendances_table', 3),
(8, '2026_07_28_010426_create_permissions_table', 3),
(9, '2026_07_28_011536_add_early_leave_reason_to_attendances_table', 3),
(10, '2026_07_29_022826_add_admin_fields_to_users_table', 3),
(11, '2026_07_29_023445_add_role_to_users_table', 3),
(12, '2026_07_29_070206_add_major_to_users_table', 4),
(13, '2026_07_30_085700_add_end_date_to_users_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `reason_option` varchar(255) NOT NULL,
  `custom_reason` text DEFAULT NULL,
  `proof_file` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `user_id`, `date`, `reason_option`, `custom_reason`, `proof_file`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, '2026-07-29', 'Urusan Kampus / Sekolah', NULL, NULL, 'Approved', '2026-07-28 22:07:26', '2026-07-28 22:08:44'),
(2, 5, '2026-07-30', 'Keperluan Keluarga / Acara Penting', NULL, NULL, 'Approved', '2026-07-29 16:00:12', '2026-07-29 16:01:11'),
(3, 3, '2026-07-31', 'Terlambat / Di luar Radius Kantor', NULL, NULL, 'Approved', '2026-07-30 18:59:15', '2026-07-30 18:59:42'),
(4, 3, '2026-08-01', 'Keperluan Keluarga / Acara Penting', NULL, NULL, 'Approved', '2026-07-30 23:37:12', '2026-07-30 23:37:12'),
(5, 3, '2026-08-02', 'Urusan Kampus / Sekolah', NULL, 'permissions/1785485954_3.pdf', 'Approved', '2026-07-31 00:19:14', '2026-07-31 00:19:14'),
(6, 5, '2026-07-31', 'Keperluan Keluarga / Acara Penting', NULL, 'permissions/1785486269_5.jpeg', 'Approved', '2026-07-31 00:24:29', '2026-07-31 00:24:29'),
(7, 3, '2026-08-03', 'Terlambat / Di luar Radius Kantor', NULL, NULL, 'Approved', '2026-08-02 16:12:32', '2026-08-02 16:12:32'),
(8, 3, '2026-08-05', 'Sakit', NULL, 'permissions/1785889240_3.jpeg', 'Approved', '2026-08-04 16:20:40', '2026-08-04 16:20:40'),
(9, 3, '2026-08-06', 'Keperluan Keluarga / Acara Penting', NULL, NULL, 'Approved', '2026-08-04 16:21:29', '2026-08-04 16:21:29'),
(10, 3, '2026-08-28', 'Keperluan Keluarga / Acara Penting', NULL, NULL, 'Approved', '2026-08-04 16:23:13', '2026-08-04 16:23:13'),
(11, 3, '2026-08-04', 'Terlambat / Di luar Radius Kantor', NULL, NULL, 'Approved', '2026-08-03 16:10:00', '2026-08-03 16:10:00'),
(12, 3, '2026-08-08', 'Terlambat / Di luar Radius Kantor', NULL, NULL, 'Approved', '2026-08-07 18:08:54', '2026-08-07 18:08:54');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('73UWuwlkF4HlcQ6l6pYbKTDWwlpByXN18iHWqSOG', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiclNycDE5ZmFoWDdTR01SakFWOGJsb2RWQlY4M3pVN0VRWGVoYm5mOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlc2Vuc2ktbWFnYW5nL3B1YmxpYy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1785897980),
('7rtH0u9eT2ZNSCcTuOZLGS5eBB38YiAnPmJLire0', NULL, '10.10.1.8', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWxkTDdKbHVQTkhveDBDYUpDR1Rnd3h1RmJMNzcwaWJ6aXcxTkxyMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly8xMC4xMC4xLjYyL3ByZXNlbnNpLW1hZ2FuZy9wdWJsaWMvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1785898698),
('8x3p10DQtTLorYgER9JRyRvgQDRgEnZglS2FQKJu', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZHU3OXp3a2x2OHBlSHE3bTVkdkVXNU5BVm5ZUVJseGhOYzJKQXRuVyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlc2Vuc2ktbWFnYW5nL3B1YmxpYy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1785893227),
('aLsNFQ8SbxVIUi0dWaPwb0KXCA9mBfxovRPry1yy', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSENuaXVaTnh6c3BYVUZCeEVQU0xvZUk4cGtIeTRWYkx3SjU2VXFqSiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlc2Vuc2ktbWFnYW5nL3B1YmxpYy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1785897932),
('CZEnIvW6J91CTmtAgBNYss50A07jrL5foYYhRwrH', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOGNuaWV6MEdQQjE3TFlXdGNyRmdmNHB0Q2lodWcyMWpCQXUzNk41dyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlc2Vuc2ktbWFnYW5nL3B1YmxpYy9pbnRlcm4vZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjE2OiJpbnRlcm4uZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjIyOiJwZXJtaXNzaW9uX3RvYXN0X3Nob3duIjtiOjE7fQ==', 1785898489),
('dzmglp2mtwbRGftL66dmDwc2yH6CDf3E6V1ABVCx', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZTcyMUppYXR2UzY5WWZoQkdtalFvb3QyNFV1c0s2azBxclRhbzNqWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlc2Vuc2ktbWFnYW5nL3B1YmxpYy9pbmRleC5waHAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1785899191),
('IkXTDRVBexh2a3vaaSTFy3KUbQcmMVi5EZ0VPYxs', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiakJEQjZsT1h6VFFyY3dYajlQUmNoZEtYNUtCVFBlb21BcWZmUGFCVSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlc2Vuc2ktbWFnYW5nL3B1YmxpYyI7czo1OiJyb3V0ZSI7Tjt9fQ==', 1785901784),
('Jn0nOJouqWjhlmhyAKL8FYFXtpV4Sq3ecmHMmVpF', NULL, '::1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.8875', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNThsS1JTd1E5VDBaN29YbUhZZFdUQ2M1VTIxSkdkeTBwdUcwU3R4ViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlc2Vuc2ktbWFnYW5nL3B1YmxpYy9pbmRleC5waHAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1785899190),
('jr9rveal0Rhjkuimdcd1hiLEfZoQHrWMm9ADiGxW', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUG5lbzV5VVByQkl3azZuc0M5UURiZTBoa3hjR08wVFFHYmdBMVBnYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlc2Vuc2ktbWFnYW5nL3B1YmxpYy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1785893121),
('lO5phWhc0W171ZFcDGSIboM9MAkieK2nyNdEQUkt', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3BDWno1VGZkR2ZKa3NVQUF2TzJBc2lucTBEaVNmWUU3WndnVzZobiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1785900069),
('ypqroB8XmfBg3edvsgudC6mjGrPBDsR61fRrUeCd', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZWFNMVlqZXkwbWxxMXdMQ3lKd04yV09KWVJqNG5Edm5aSVIyTE50byI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlc2Vuc2ktbWFnYW5nL3B1YmxpYy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1785898164),
('ZrtUKNdnObLIRx51jP8XyqavZrggcCRAUPn0einU', NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWhFZUJNMTVFTml6U2IzaGtHQWJkb01HQlJ0dTczUVJYbEo3eWwzcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly9sb2NhbGhvc3QvcHJlc2Vuc2ktbWFnYW5nL3B1YmxpYy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1786154704);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `major` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'intern',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `institution`, `major`, `start_date`, `end_date`, `duration`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@admin.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$kgpOXAYxxeDhnIH4vAtTPOw55hQT/fASjhk9CVaOTYAVprnkxtSfq', 'admin', NULL, '2026-07-28 21:37:02', '2026-07-28 21:37:02'),
(2, 'Salsabila Nailafahdi', 'intern@bapenda.ntb.go.id', 'Universitas Mataram', NULL, NULL, NULL, NULL, NULL, '$2y$12$yIxhs.g1pXn6O6FGjGYfauZBWoDuS48IMMIomSeL6f4VteUejpOhm', 'intern', NULL, '2026-07-28 21:37:02', '2026-07-28 21:37:02'),
(3, 'Sagos', 'Sagos@gmail.com', 'Universitas Mataram', 'Teknik Informatika', '2026-07-29', '2026-09-05', '1 bulan', NULL, '$2y$12$pYwlbNw5NuUEmAYbDaFIHeojCzIZh70fNlor4K9mbrzqbncLpRv..', 'intern', NULL, '2026-07-28 22:06:26', '2026-08-03 22:46:08'),
(4, 'Budi', 'budi@gmail.com', 'Universitas Mataram', NULL, '2026-07-29', NULL, '2 bulan', NULL, '$2y$12$qWgv9CZApbZ2ZaJTSYcnbOrQkiZ5kegD2/GLaH1oO1sjfMMn2MP.W', 'intern', NULL, '2026-07-28 22:13:35', '2026-07-28 22:13:35'),
(5, 'user', 'user@gmail.com', 'Universitas Mataram', 'Teknik Mesin', '2026-07-29', '2026-08-26', '1 bulan', NULL, '$2y$12$d/k5Y8dX9bVJQh0.oDW0/ucox4qas5HQyj7SZOr26eEjfXLwCkIBC', 'intern', NULL, '2026-07-28 23:13:09', '2026-08-03 16:09:22'),
(7, 'Gaza Bulbul', 'gazabul@gmail.com', 'Universitas Mataram', 'Teknik Informatika', '2026-07-30', NULL, '1 Bulan', NULL, '$2y$12$tQ3qGBaIQuv8GQuRQ1rDbOPKTjQwB7.Z3sFfSparevFUdFEtJ5nGq', 'intern', NULL, '2026-07-29 16:41:46', '2026-07-31 00:46:11'),
(8, 'tes', 'tes@gmail.com', 'Unram', 'Kimia', '2026-08-03', '2026-08-31', NULL, NULL, '$2y$12$dqABggyklUUhnbPFs8vwG.HsDBmRXTOfrHDVa5/exhX0DeWtWdSy.', 'intern', NULL, '2026-08-02 15:57:50', '2026-08-04 16:17:24'),
(9, 'Meja', 'meja@gmail.com', 'unram', 'infor', '2026-08-04', '2026-08-05', NULL, NULL, '$2y$12$ewUsgYcLdGL9lo9vjsIwhuh.ohaA/JL117F53pDnA/5ASEHvq.0xm', 'intern', NULL, '2026-08-03 18:03:56', '2026-08-03 18:03:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `izin_magangs`
--
ALTER TABLE `izin_magangs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `izin_magangs_magang_id_foreign` (`magang_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `magangs`
--
ALTER TABLE `magangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permissions_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `izin_magangs`
--
ALTER TABLE `izin_magangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `magangs`
--
ALTER TABLE `magangs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `izin_magangs`
--
ALTER TABLE `izin_magangs`
  ADD CONSTRAINT `izin_magangs_magang_id_foreign` FOREIGN KEY (`magang_id`) REFERENCES `magangs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

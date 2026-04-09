-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2026 at 05:59 AM
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
-- Database: `inventory_gudang`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('inventory-gudang-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:17:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:16:\"view master-data\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:18:\"manage master-data\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:19:\"view purchase-order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:21:\"create purchase-order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:22:\"approve purchase-order\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:13:\"receive goods\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:17:\"view distribution\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:19:\"create distribution\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:13:\"view transfer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:15:\"create transfer\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:11:\"view return\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:13:\"create return\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:10:\"view stock\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:12:\"adjust stock\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:11:\"view report\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:13:\"export report\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:12:\"manage users\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:3:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"superadmin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"admin_gudang\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:6:\"viewer\";s:1:\"c\";s:3:\"web\";}}}', 1775734700);

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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Elektronik', 'elektronik', NULL, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(2, 'Alat Tulis', 'alat-tulis', NULL, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(3, 'Peralatan Kebersihan', 'peralatan-kebersihan', NULL, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(4, 'Spare Part', 'spare-part', NULL, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(5, 'Bahan Baku', 'bahan-baku', NULL, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(6, 'Kemasan', 'kemasan', NULL, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(7, 'Alat Kesehatan2', 'alat-kesehatan2', 'Alat Kesehatan2', '2026-04-08 04:56:28', '2026-04-08 04:56:55', '2026-04-08 04:56:55');

-- --------------------------------------------------------

--
-- Table structure for table `distributions`
--

CREATE TABLE `distributions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dist_number` varchar(50) NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `issued_by` bigint(20) UNSIGNED NOT NULL,
  `destination` varchar(255) NOT NULL,
  `recipient` varchar(255) DEFAULT NULL,
  `dist_date` date NOT NULL,
  `status` enum('draft','issued','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `distributions`
--

INSERT INTO `distributions` (`id`, `dist_number`, `warehouse_id`, `issued_by`, `destination`, `recipient`, `dist_date`, `status`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DO/2026/04/0001', 1, 1, 'Finance', 'A', '2026-04-08', 'cancelled', NULL, '2026-04-08 05:45:53', '2026-04-08 06:14:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `distribution_items`
--

CREATE TABLE `distribution_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `distribution_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `distribution_items`
--

INSERT INTO `distribution_items` (`id`, `distribution_id`, `item_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-04-08 05:45:53', '2026-04-08 05:45:53');

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
-- Table structure for table `goods_receipts`
--

CREATE TABLE `goods_receipts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt_number` varchar(50) NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `receipt_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_receipts`
--

INSERT INTO `goods_receipts` (`id`, `receipt_number`, `purchase_order_id`, `warehouse_id`, `received_by`, `receipt_date`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'GR/2026/04/0001', 1, 1, 1, '2026-04-08', NULL, '2026-04-08 05:16:38', '2026-04-08 05:16:38', NULL),
(2, 'GR/2026/04/0002', 1, 1, 1, '2026-04-08', NULL, '2026-04-08 05:21:54', '2026-04-08 05:21:54', NULL),
(3, 'GR/2026/04/0003', 1, 1, 1, '2026-04-08', NULL, '2026-04-08 05:25:28', '2026-04-08 05:25:28', NULL),
(4, 'GR/2026/04/0004', 3, 2, 1, '2026-04-08', NULL, '2026-04-08 05:44:48', '2026-04-08 05:44:48', NULL),
(5, 'GR/2026/04/0005', 3, 2, 4, '2026-04-08', NULL, '2026-04-08 05:57:00', '2026-04-08 05:57:00', NULL),
(6, 'GR/2026/04/0006', 2, 2, 4, '2026-04-08', NULL, '2026-04-08 05:57:09', '2026-04-08 05:57:09', NULL),
(7, 'GR/2026/04/0007', 2, 2, 4, '2026-04-08', NULL, '2026-04-08 05:57:31', '2026-04-08 05:57:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `goods_receipt_items`
--

CREATE TABLE `goods_receipt_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_receipt_id` bigint(20) UNSIGNED NOT NULL,
  `po_item_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `qty_received` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_receipt_items`
--

INSERT INTO `goods_receipt_items` (`id`, `goods_receipt_id`, `po_item_id`, `item_id`, `qty_received`, `notes`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 100, NULL, '2026-04-08 05:25:28', '2026-04-08 05:25:28');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `min_stock` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `code`, `barcode`, `name`, `category_id`, `unit_id`, `purchase_price`, `min_stock`, `description`, `image`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'BRG-0001', 'BRG-0001', 'Buku Tulis KiKy', 2, 1, 50000.00, 5, NULL, 'items/XpF2F9egbWH6lURNUcDDk0BMqVPeUtafBgqSzLnu.jpg', 1, '2026-04-08 05:05:34', '2026-04-08 05:05:34', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_stocks`
--

CREATE TABLE `item_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_stocks`
--

INSERT INTO `item_stocks` (`id`, `item_id`, `warehouse_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100, '2026-04-08 05:25:28', '2026-04-08 06:14:39');

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
(4, '2026_04_08_035615_create_permission_tables', 1),
(5, '2026_04_08_035814_create_warehouses_table', 1),
(6, '2026_04_08_035842_create_categories_table', 1),
(7, '2026_04_08_035930_create_units_table', 1),
(8, '2026_04_08_040010_create_suppliers_table', 1),
(9, '2026_04_08_040037_create_items_table', 1),
(10, '2026_04_08_040117_create_item_stocks_table', 1),
(11, '2026_04_08_040159_create_purchase_orders_table', 1),
(12, '2026_04_08_040224_create_po_items_table', 1),
(13, '2026_04_08_040255_create_goods_receipts_table', 1),
(14, '2026_04_08_040321_create_goods_receipt_items_table', 1),
(15, '2026_04_08_040355_create_stock_movements_table', 1),
(16, '2026_04_08_040417_create_distributions_table', 1),
(17, '2026_04_08_040446_create_distribution_items_table', 1),
(18, '2026_04_08_040509_create_stock_transfers_table', 1),
(19, '2026_04_08_040528_create_stock_transfer_items_table', 1),
(20, '2026_04_08_040547_create_returns_table', 1),
(21, '2026_04_08_040603_create_return_items_table', 1),
(22, '2026_04_08_113705_add_is_active_to_users_table', 2),
(23, '2026_04_08_121934_add_deleted_at_to_goods_receipts_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(1, 'App\\Models\\User', 4),
(2, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3);

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
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view master-data', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(2, 'manage master-data', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(3, 'view purchase-order', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(4, 'create purchase-order', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(5, 'approve purchase-order', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(6, 'receive goods', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(7, 'view distribution', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(8, 'create distribution', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(9, 'view transfer', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(10, 'create transfer', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(11, 'view return', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(12, 'create return', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(13, 'view stock', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(14, 'adjust stock', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(15, 'view report', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(16, 'export report', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(17, 'manage users', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00');

-- --------------------------------------------------------

--
-- Table structure for table `po_items`
--

CREATE TABLE `po_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `qty_ordered` int(11) NOT NULL,
  `qty_received` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `po_items`
--

INSERT INTO `po_items` (`id`, `purchase_order_id`, `item_id`, `qty_ordered`, `qty_received`, `unit_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 100, 100, 50000.00, 5000000.00, '2026-04-08 05:13:39', '2026-04-08 05:25:28'),
(2, 2, 1, 10, 0, 50000.00, 500000.00, '2026-04-08 05:32:42', '2026-04-08 05:32:42'),
(3, 3, 1, 10, 0, 50000.00, 500000.00, '2026-04-08 05:44:34', '2026-04-08 05:44:34');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_number` varchar(50) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('draft','waiting_approval','approved','partial','completed','cancelled') NOT NULL DEFAULT 'draft',
  `order_date` date NOT NULL,
  `expected_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `po_number`, `supplier_id`, `warehouse_id`, `created_by`, `approved_by`, `status`, `order_date`, `expected_date`, `notes`, `total_amount`, `approved_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PO/2026/04/0001', 1, 1, 1, 1, 'completed', '2026-04-08', '2026-04-10', NULL, 5000000.00, '2026-04-08 05:13:49', '2026-04-08 05:13:39', '2026-04-08 05:25:28', NULL),
(2, 'PO/2026/04/0002', 1, 2, 1, 4, 'partial', '2026-04-08', '2026-04-10', NULL, 500000.00, '2026-04-08 05:56:47', '2026-04-08 05:32:42', '2026-04-08 05:57:09', NULL),
(3, 'PO/2026/04/0003', 1, 2, 1, 1, 'partial', '2026-04-08', '2026-04-09', NULL, 500000.00, '2026-04-08 05:44:44', '2026-04-08 05:44:34', '2026-04-08 05:44:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `return_number` varchar(50) NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `return_date` date NOT NULL,
  `status` enum('draft','sent','confirmed','cancelled') NOT NULL DEFAULT 'draft',
  `reason` text NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return_items`
--

CREATE TABLE `return_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `return_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(2, 'admin_gudang', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00'),
(3, 'viewer', 'web', '2026-04-07 21:36:00', '2026-04-07 21:36:00');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(3, 1),
(3, 2),
(3, 3),
(4, 1),
(4, 2),
(5, 1),
(6, 1),
(6, 2),
(7, 1),
(7, 2),
(7, 3),
(8, 1),
(8, 2),
(9, 1),
(9, 2),
(9, 3),
(10, 1),
(10, 2),
(11, 1),
(11, 2),
(11, 3),
(12, 1),
(12, 2),
(13, 1),
(13, 2),
(13, 3),
(14, 1),
(14, 2),
(15, 1),
(15, 2),
(15, 3),
(16, 1),
(16, 2),
(16, 3),
(17, 1);

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
('3pFatIq4fifp70PQTwYobejitXqJVXItos63mkbM', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTUtzbURpQ044RkhJd25rMVB2MDNTdEhnWVZsamptU0NVY2Y3UHF5TyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czozOiJ1cmwiO2E6MDp7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1775654807);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out','transfer_in','transfer_out','retur','adjustment') NOT NULL,
  `quantity` int(11) NOT NULL,
  `stock_before` int(11) NOT NULL,
  `stock_after` int(11) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `moved_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `item_id`, `warehouse_id`, `user_id`, `type`, `quantity`, `stock_before`, `stock_after`, `reference_type`, `reference_id`, `notes`, `moved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'in', 100, 0, 100, 'App\\Models\\GoodsReceipt', 3, 'Penerimaan barang dari PO PO/2026/04/0001', '2026-04-08 05:25:28', '2026-04-08 05:25:28', '2026-04-08 05:25:28'),
(2, 1, 1, 1, 'out', 1, 100, 99, 'App\\Models\\Distribution', 1, 'Distribusi ke Finance — DO/2026/04/0001', '2026-04-08 05:46:00', '2026-04-08 05:46:00', '2026-04-08 05:46:00'),
(3, 1, 1, 4, 'in', 1, 99, 100, 'App\\Models\\Distribution', 1, 'Pembatalan distribusi DO/2026/04/0001', '2026-04-08 06:14:39', '2026-04-08 06:14:39', '2026-04-08 06:14:39');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_number` varchar(50) NOT NULL,
  `from_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `to_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `transferred_by` bigint(20) UNSIGNED NOT NULL,
  `transfer_date` date NOT NULL,
  `status` enum('draft','completed','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_items`
--

CREATE TABLE `stock_transfer_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_transfer_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `code`, `name`, `contact_person`, `phone`, `email`, `address`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'SUP-001', 'PT. Sumber Makmur', 'Budi Santoso', '021-5551234', 'budi@sumbermakmur.com', 'Jl. Industri No. 12, Jakarta', 1, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(2, 'SUP-002', 'CV. Cahaya Logistik', 'Siti Rahayu', '021-5559876', 'siti@cahayalogistik.com', 'Jl. Raya Bekasi No. 45, Bekasi', 1, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(3, 'SUP-004', 'HollyWings', 'Wahyu', '089911223344', 'tes@tes.com', 'HW BSD', 1, '2026-04-08 04:59:59', '2026-04-08 05:00:46', '2026-04-08 05:00:46');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `abbreviation` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `abbreviation`, `created_at`, `updated_at`) VALUES
(1, 'Pieces', 'pcs', '2026-04-07 21:36:01', '2026-04-07 21:36:01'),
(2, 'Kilogram', 'kg', '2026-04-07 21:36:01', '2026-04-07 21:36:01'),
(3, 'Liter', 'L', '2026-04-07 21:36:01', '2026-04-07 21:36:01'),
(4, 'Box', 'box', '2026-04-07 21:36:01', '2026-04-07 21:36:01'),
(5, 'Karton', 'ktn', '2026-04-07 21:36:01', '2026-04-07 21:36:01'),
(6, 'Meter', 'm', '2026-04-07 21:36:01', '2026-04-07 21:36:01'),
(7, 'Roll', 'roll', '2026-04-07 21:36:01', '2026-04-07 21:36:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'superadmin@inventory.test', 1, NULL, '$2y$12$KsBJ3yEMihEyXtRtOYn29uXHN.Jnh65RwrfL30WZNN/VVtgrsMTKi', 'za1PJEyPjlhL3LsDBThiXDdcuAIZpEvJworgA73E4rG10My9QPD7rwmq2pkB', '2026-04-07 21:36:01', '2026-04-07 21:36:01'),
(2, 'Admin Gudang', 'admin@inventory.test', 1, NULL, '$2y$12$EUfTZqsOJ5mgf/AuZX2W3.yRds52c5yz.KBoAsPJS23Yhknju3PGS', NULL, '2026-04-07 21:36:01', '2026-04-07 21:36:01'),
(3, 'Viewer', 'viewer@inventory.test', 1, NULL, '$2y$12$ldfluaRTqifT8Xby9PhjD.y08tJxr7bxZ0XdiqHxj7aEAjZvf5d9q', NULL, '2026-04-07 21:36:01', '2026-04-07 21:36:01'),
(4, 'hasta', 'hasta@hasta.com', 1, NULL, '$2y$12$Yv3ydw/IJVKmVp8iivbriOGT1OwWbJLtGMvYcIGzVjftdpGOqs2nq', 'PTKOwNjuehumgT90w4fs7jJ1T3ew6FaoNZLCdRatZOJfsHo6eoqD192Ujek9', '2026-04-08 05:49:59', '2026-04-08 05:50:10');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `code`, `name`, `location`, `description`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'GDG-01', 'Gudang Utama', 'Lantai 1, Gedung A', NULL, 1, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(2, 'GDG-02', 'Gudang Cadangan', 'Lantai 2, Gedung A', NULL, 1, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(3, 'GDG-03', 'Gudang Transit', 'Gedung B', NULL, 1, '2026-04-07 21:36:01', '2026-04-07 21:36:01', NULL),
(4, 'GDG-4', 'Gudang Utama 1', 'Lantai 1, Gedung B', NULL, 1, '2026-04-08 04:53:53', '2026-04-08 04:54:44', '2026-04-08 04:54:44');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `distributions`
--
ALTER TABLE `distributions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `distributions_dist_number_unique` (`dist_number`),
  ADD KEY `distributions_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `distributions_issued_by_foreign` (`issued_by`);

--
-- Indexes for table `distribution_items`
--
ALTER TABLE `distribution_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `distribution_items_distribution_id_foreign` (`distribution_id`),
  ADD KEY `distribution_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goods_receipts_receipt_number_unique` (`receipt_number`),
  ADD KEY `goods_receipts_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `goods_receipts_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `goods_receipts_received_by_foreign` (`received_by`);

--
-- Indexes for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_receipt_items_goods_receipt_id_foreign` (`goods_receipt_id`),
  ADD KEY `goods_receipt_items_po_item_id_foreign` (`po_item_id`),
  ADD KEY `goods_receipt_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `items_code_unique` (`code`),
  ADD UNIQUE KEY `items_barcode_unique` (`barcode`),
  ADD KEY `items_category_id_foreign` (`category_id`),
  ADD KEY `items_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `item_stocks`
--
ALTER TABLE `item_stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_stocks_item_id_warehouse_id_unique` (`item_id`,`warehouse_id`),
  ADD KEY `item_stocks_warehouse_id_foreign` (`warehouse_id`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

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
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `po_items`
--
ALTER TABLE `po_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `po_items_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `po_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  ADD KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_orders_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_orders_created_by_foreign` (`created_by`),
  ADD KEY `purchase_orders_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `returns_return_number_unique` (`return_number`),
  ADD KEY `returns_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `returns_supplier_id_foreign` (`supplier_id`),
  ADD KEY `returns_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `returns_created_by_foreign` (`created_by`);

--
-- Indexes for table `return_items`
--
ALTER TABLE `return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `return_items_return_id_foreign` (`return_id`),
  ADD KEY `return_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `stock_movements_user_id_foreign` (`user_id`),
  ADD KEY `stock_movements_item_id_warehouse_id_index` (`item_id`,`warehouse_id`),
  ADD KEY `stock_movements_reference_type_reference_id_index` (`reference_type`,`reference_id`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_transfers_transfer_number_unique` (`transfer_number`),
  ADD KEY `stock_transfers_from_warehouse_id_foreign` (`from_warehouse_id`),
  ADD KEY `stock_transfers_to_warehouse_id_foreign` (`to_warehouse_id`),
  ADD KEY `stock_transfers_transferred_by_foreign` (`transferred_by`);

--
-- Indexes for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_transfer_items_stock_transfer_id_foreign` (`stock_transfer_id`),
  ADD KEY `stock_transfer_items_item_id_foreign` (`item_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_code_unique` (`code`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouses_code_unique` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `distributions`
--
ALTER TABLE `distributions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `distribution_items`
--
ALTER TABLE `distribution_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `item_stocks`
--
ALTER TABLE `item_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `po_items`
--
ALTER TABLE `po_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `return_items`
--
ALTER TABLE `return_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `distributions`
--
ALTER TABLE `distributions`
  ADD CONSTRAINT `distributions_issued_by_foreign` FOREIGN KEY (`issued_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `distributions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `distribution_items`
--
ALTER TABLE `distribution_items`
  ADD CONSTRAINT `distribution_items_distribution_id_foreign` FOREIGN KEY (`distribution_id`) REFERENCES `distributions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `distribution_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `goods_receipts`
--
ALTER TABLE `goods_receipts`
  ADD CONSTRAINT `goods_receipts_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`),
  ADD CONSTRAINT `goods_receipts_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `goods_receipts_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `goods_receipt_items`
--
ALTER TABLE `goods_receipt_items`
  ADD CONSTRAINT `goods_receipt_items_goods_receipt_id_foreign` FOREIGN KEY (`goods_receipt_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_receipt_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `goods_receipt_items_po_item_id_foreign` FOREIGN KEY (`po_item_id`) REFERENCES `po_items` (`id`);

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints for table `item_stocks`
--
ALTER TABLE `item_stocks`
  ADD CONSTRAINT `item_stocks_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `item_stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `po_items`
--
ALTER TABLE `po_items`
  ADD CONSTRAINT `po_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `po_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `purchase_orders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `returns_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `returns_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `returns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `return_items`
--
ALTER TABLE `return_items`
  ADD CONSTRAINT `return_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `return_items_return_id_foreign` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `stock_movements_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `stock_transfers_from_warehouse_id_foreign` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `stock_transfers_to_warehouse_id_foreign` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`),
  ADD CONSTRAINT `stock_transfers_transferred_by_foreign` FOREIGN KEY (`transferred_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  ADD CONSTRAINT `stock_transfer_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  ADD CONSTRAINT `stock_transfer_items_stock_transfer_id_foreign` FOREIGN KEY (`stock_transfer_id`) REFERENCES `stock_transfers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

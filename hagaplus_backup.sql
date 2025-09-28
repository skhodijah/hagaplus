-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 28, 2025 at 02:33 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `hagaplus`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instansi_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `branch_id` bigint UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in_time` timestamp NULL DEFAULT NULL,
  `check_out_time` timestamp NULL DEFAULT NULL,
  `check_in_method` enum('qr','gps','face_id','manual') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_method` enum('qr','gps','face_id','manual') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_in_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `check_out_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_duration` int NOT NULL DEFAULT '0',
  `break_duration` int NOT NULL DEFAULT '0',
  `overtime_duration` int NOT NULL DEFAULT '0',
  `late_minutes` int NOT NULL DEFAULT '0',
  `early_checkout_minutes` int NOT NULL DEFAULT '0',
  `status` enum('present','late','absent','partial','leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'absent',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_policies`
--

CREATE TABLE `attendance_policies` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `work_days` json NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `break_duration` int NOT NULL DEFAULT '60',
  `late_tolerance` int NOT NULL DEFAULT '15',
  `early_checkout_tolerance` int NOT NULL DEFAULT '15',
  `overtime_after_minutes` int NOT NULL DEFAULT '0',
  `attendance_methods` json NOT NULL,
  `auto_checkout` tinyint(1) NOT NULL DEFAULT '0',
  `auto_checkout_time` time DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `backup_logs`
--

CREATE TABLE `backup_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED DEFAULT NULL,
  `backup_type` enum('full','incremental','instansi_specific','table_specific') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('started','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'started',
  `backup_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `backup_size` bigint DEFAULT NULL,
  `tables_included` json DEFAULT NULL,
  `records_count` int DEFAULT NULL,
  `started_at` datetime NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `error_details` text COLLATE utf8mb4_unicode_ci,
  `initiated_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'system',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `radius` int NOT NULL DEFAULT '100',
  `timezone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_notification_logs`
--

CREATE TABLE `bulk_notification_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_ids` json DEFAULT NULL,
  `total_sent` int NOT NULL,
  `sent_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'direct',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `chat_id` bigint UNSIGNED NOT NULL,
  `sender_id` bigint UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_participants`
--

CREATE TABLE `chat_participants` (
  `id` bigint UNSIGNED NOT NULL,
  `chat_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `last_read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_themes`
--

CREATE TABLE `company_themes` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `primary_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3b82f6',
  `secondary_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#64748b',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_css` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `compliance_logs`
--

CREATE TABLE `compliance_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `compliance_type` enum('data_retention','privacy_policy','gdpr','audit','security') COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `affected_data` json DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_deletion_requests`
--

CREATE TABLE `data_deletion_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `requested_by` bigint UNSIGNED NOT NULL,
  `request_type` enum('employee_data','attendance_data','payroll_data','full_instansi','specific_period') COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_specification` json NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `scheduled_deletion_date` date NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_exports`
--

CREATE TABLE `data_exports` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `requested_by` bigint UNSIGNED NOT NULL,
  `export_type` enum('attendance','payroll','employees','full_backup','custom') COLLATE utf8mb4_unicode_ci NOT NULL,
  `format` enum('csv','excel','pdf','json') COLLATE utf8mb4_unicode_ci NOT NULL,
  `filters` json DEFAULT NULL,
  `status` enum('pending','processing','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percentage','fixed_amount') COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(15,2) NOT NULL,
  `max_discount` decimal(15,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `usage_limit_per_instansi` int NOT NULL DEFAULT '1',
  `used_count` int NOT NULL DEFAULT '0',
  `valid_from` date NOT NULL,
  `valid_until` date NOT NULL,
  `applicable_packages` json DEFAULT NULL,
  `target` enum('new_customers','existing_customers','all') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_usage`
--

CREATE TABLE `discount_usage` (
  `id` bigint UNSIGNED NOT NULL,
  `discount_id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED DEFAULT NULL,
  `original_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL,
  `final_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `branch_id` bigint UNSIGNED DEFAULT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `hire_date` date NOT NULL,
  `status` enum('active','inactive','terminated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_schedules`
--

CREATE TABLE `employee_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `policy_id` bigint UNSIGNED NOT NULL,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `features`
--

CREATE TABLE `features` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` enum('attendance','payroll','reporting','integration','customization') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `config` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instansis`
--

CREATE TABLE `instansis` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subdomain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_langganan` enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL,
  `retention_policy` json DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL,
  `archived_by` bigint UNSIGNED DEFAULT NULL,
  `package_id` bigint UNSIGNED DEFAULT NULL,
  `subscription_start` datetime DEFAULT NULL,
  `subscription_end` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `max_employees` int NOT NULL DEFAULT '10',
  `max_branches` int NOT NULL DEFAULT '1',
  `settings` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instansi_feature_overrides`
--

CREATE TABLE `instansi_feature_overrides` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `feature_id` bigint UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `custom_limits` json DEFAULT NULL,
  `custom_config` json DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `effective_from` date NOT NULL,
  `effective_until` date DEFAULT NULL,
  `applied_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instansi_subscription_addons`
--

CREATE TABLE `instansi_subscription_addons` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `subscription_addon_id` bigint UNSIGNED NOT NULL,
  `price_override` decimal(15,2) DEFAULT NULL,
  `active_from` date NOT NULL,
  `active_until` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `leave_type` enum('annual','sick','maternity','emergency','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_count` int NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) NOT NULL,
  `duration_days` int NOT NULL DEFAULT '30',
  `max_employees` int NOT NULL DEFAULT '10',
  `max_branches` int NOT NULL DEFAULT '1',
  `features` json NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_change_requests`
--

CREATE TABLE `package_change_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `current_package_id` bigint UNSIGNED NOT NULL,
  `requested_package_id` bigint UNSIGNED NOT NULL,
  `type` enum('upgrade','downgrade') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `requested_effective_date` date NOT NULL,
  `prorate_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `requested_by` bigint UNSIGNED NOT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_features`
--

CREATE TABLE `package_features` (
  `id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `feature_id` bigint UNSIGNED NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `limits` json DEFAULT NULL,
  `config_override` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `period_year` int NOT NULL,
  `period_month` int NOT NULL,
  `basic_salary` decimal(15,2) NOT NULL,
  `allowances` json DEFAULT NULL,
  `deductions` json DEFAULT NULL,
  `overtime_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_gross` decimal(15,2) NOT NULL,
  `total_deductions` decimal(15,2) NOT NULL DEFAULT '0.00',
  `net_salary` decimal(15,2) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_status` enum('draft','processed','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `branch_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `status` enum('active','inactive','expired','suspended','canceled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `effective_date` date DEFAULT NULL,
  `trial_ends_at` date DEFAULT NULL,
  `is_trial` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `discount_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_addons`
--

CREATE TABLE `subscription_addons` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) NOT NULL,
  `billing_cycle` enum('monthly','quarterly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `features` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_history`
--

CREATE TABLE `subscription_history` (
  `id` bigint UNSIGNED NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `package_id` bigint UNSIGNED NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('pending','paid','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_transitions`
--

CREATE TABLE `subscription_transitions` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `from_package_id` bigint UNSIGNED NOT NULL,
  `to_package_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `transition_type` enum('upgrade','downgrade','renewal','new') COLLATE utf8mb4_unicode_ci NOT NULL,
  `effective_from` datetime NOT NULL,
  `effective_until` datetime DEFAULT NULL,
  `transition_amount` decimal(15,2) NOT NULL,
  `prorate_credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `feature_changes` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `processed_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_requests`
--

CREATE TABLE `support_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `requested_by` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('open','in_progress','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `priority` enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `theme_settings`
--

CREATE TABLE `theme_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `background_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3B82F6',
  `secondary_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#1E40AF',
  `accent_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#F59E0B',
  `success_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#10B981',
  `warning_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#F59E0B',
  `error_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#EF4444',
  `info_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3B82F6',
  `background_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#F9FAFB',
  `surface_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#FFFFFF',
  `card_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#FFFFFF',
  `text_primary_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#111827',
  `text_secondary_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#6B7280',
  `text_muted_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#9CA3AF',
  `border_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#E5E7EB',
  `border_light_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#F3F4F6',
  `font_family` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Inter',
  `border_radius` int NOT NULL DEFAULT '8',
  `dark_mode_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `sidebar_collapsed` tinyint(1) NOT NULL DEFAULT '0',
  `custom_css` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('superadmin','admin','employee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  `instansi_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attendances_user_id_attendance_date_unique` (`user_id`,`attendance_date`),
  ADD KEY `attendances_approved_by_foreign` (`approved_by`),
  ADD KEY `attendances_user_id_attendance_date_index` (`user_id`,`attendance_date`),
  ADD KEY `attendances_branch_id_index` (`branch_id`),
  ADD KEY `attendances_attendance_date_index` (`attendance_date`),
  ADD KEY `attendances_user_id_status_attendance_date_index` (`user_id`,`status`,`attendance_date`),
  ADD KEY `attendances_branch_id_attendance_date_index` (`branch_id`,`attendance_date`),
  ADD KEY `attendances_status_attendance_date_index` (`status`,`attendance_date`);

--
-- Indexes for table `attendance_policies`
--
ALTER TABLE `attendance_policies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendance_policies_company_id_foreign` (`company_id`);

--
-- Indexes for table `backup_logs`
--
ALTER TABLE `backup_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `backup_logs_instansi_id_backup_type_status_index` (`instansi_id`,`backup_type`,`status`),
  ADD KEY `backup_logs_started_at_index` (`started_at`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branches_company_id_foreign` (`company_id`);

--
-- Indexes for table `bulk_notification_logs`
--
ALTER TABLE `bulk_notification_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bulk_notification_logs_sent_by_foreign` (`sent_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_chat_id_foreign` (`chat_id`),
  ADD KEY `chat_messages_sender_id_foreign` (`sender_id`);

--
-- Indexes for table `chat_participants`
--
ALTER TABLE `chat_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_participants_chat_id_user_id_unique` (`chat_id`,`user_id`),
  ADD KEY `chat_participants_user_id_foreign` (`user_id`);

--
-- Indexes for table `company_themes`
--
ALTER TABLE `company_themes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_themes_company_id_foreign` (`company_id`);

--
-- Indexes for table `compliance_logs`
--
ALTER TABLE `compliance_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compliance_logs_user_id_foreign` (`user_id`),
  ADD KEY `compliance_logs_instansi_id_compliance_type_index` (`instansi_id`,`compliance_type`),
  ADD KEY `compliance_logs_event_created_at_index` (`event`,`created_at`);

--
-- Indexes for table `data_deletion_requests`
--
ALTER TABLE `data_deletion_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data_deletion_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `data_deletion_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `data_deletion_requests_instansi_id_status_index` (`instansi_id`,`status`),
  ADD KEY `data_deletion_requests_scheduled_deletion_date_index` (`scheduled_deletion_date`);

--
-- Indexes for table `data_exports`
--
ALTER TABLE `data_exports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `data_exports_instansi_id_status_index` (`instansi_id`,`status`),
  ADD KEY `data_exports_requested_by_created_at_index` (`requested_by`,`created_at`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discounts_code_unique` (`code`),
  ADD KEY `discounts_code_is_active_index` (`code`,`is_active`),
  ADD KEY `discounts_valid_from_valid_until_index` (`valid_from`,`valid_until`);

--
-- Indexes for table `discount_usage`
--
ALTER TABLE `discount_usage`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discount_usage_instansi_id_foreign` (`instansi_id`),
  ADD KEY `discount_usage_subscription_id_foreign` (`subscription_id`),
  ADD KEY `discount_usage_discount_id_instansi_id_index` (`discount_id`,`instansi_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_id_unique` (`employee_id`),
  ADD KEY `employees_user_id_foreign` (`user_id`),
  ADD KEY `employees_instansi_id_foreign` (`instansi_id`),
  ADD KEY `employees_branch_id_foreign` (`branch_id`),
  ADD KEY `employees_deleted_by_foreign` (`deleted_by`);

--
-- Indexes for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_schedules_user_id_foreign` (`user_id`),
  ADD KEY `employee_schedules_policy_id_foreign` (`policy_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `features`
--
ALTER TABLE `features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `features_slug_unique` (`slug`),
  ADD KEY `features_category_is_active_index` (`category`,`is_active`);

--
-- Indexes for table `instansis`
--
ALTER TABLE `instansis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instansis_subdomain_unique` (`subdomain`),
  ADD KEY `instansis_archived_by_foreign` (`archived_by`),
  ADD KEY `instansis_deleted_by_foreign` (`deleted_by`),
  ADD KEY `instansis_package_id_foreign` (`package_id`);

--
-- Indexes for table `instansi_feature_overrides`
--
ALTER TABLE `instansi_feature_overrides`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instansi_feature_override_unique` (`instansi_id`,`feature_id`,`effective_from`),
  ADD KEY `instansi_feature_overrides_feature_id_foreign` (`feature_id`),
  ADD KEY `instansi_feature_overrides_applied_by_foreign` (`applied_by`),
  ADD KEY `instansi_feature_overrides_instansi_id_is_enabled_index` (`instansi_id`,`is_enabled`);

--
-- Indexes for table `instansi_subscription_addons`
--
ALTER TABLE `instansi_subscription_addons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instansi_addon_unique` (`instansi_id`,`subscription_addon_id`),
  ADD KEY `instansi_subscription_addons_subscription_addon_id_foreign` (`subscription_addon_id`);

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
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leaves_approved_by_foreign` (`approved_by`),
  ADD KEY `leaves_user_id_index` (`user_id`),
  ADD KEY `leaves_start_date_end_date_index` (`start_date`,`end_date`),
  ADD KEY `leaves_status_index` (`status`),
  ADD KEY `leaves_user_id_status_start_date_index` (`user_id`,`status`,`start_date`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `package_change_requests`
--
ALTER TABLE `package_change_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_change_requests_current_package_id_foreign` (`current_package_id`),
  ADD KEY `package_change_requests_requested_package_id_foreign` (`requested_package_id`),
  ADD KEY `package_change_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `package_change_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `package_change_requests_instansi_id_status_index` (`instansi_id`,`status`),
  ADD KEY `package_change_requests_requested_effective_date_index` (`requested_effective_date`);

--
-- Indexes for table `package_features`
--
ALTER TABLE `package_features`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `package_feature_unique` (`package_id`,`feature_id`),
  ADD KEY `package_features_feature_id_foreign` (`feature_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payrolls_user_id_period_year_period_month_unique` (`user_id`,`period_year`,`period_month`),
  ADD KEY `payrolls_created_by_foreign` (`created_by`),
  ADD KEY `payrolls_period_year_period_month_index` (`period_year`,`period_month`),
  ADD KEY `payrolls_user_id_index` (`user_id`),
  ADD KEY `payrolls_payment_status_period_year_period_month_index` (`payment_status`,`period_year`,`period_month`);

--
-- Indexes for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `qr_codes_code_unique` (`code`),
  ADD KEY `qr_codes_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_instansi_id_key_unique` (`instansi_id`,`key`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_instansi_id_foreign` (`instansi_id`),
  ADD KEY `subscriptions_package_id_foreign` (`package_id`);

--
-- Indexes for table `subscription_addons`
--
ALTER TABLE `subscription_addons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_addons_slug_unique` (`slug`);

--
-- Indexes for table `subscription_history`
--
ALTER TABLE `subscription_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_history_package_id_foreign` (`package_id`),
  ADD KEY `subscription_history_created_by_foreign` (`created_by`),
  ADD KEY `subscription_history_company_id_foreign` (`company_id`);

--
-- Indexes for table `subscription_transitions`
--
ALTER TABLE `subscription_transitions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_transitions_from_package_id_foreign` (`from_package_id`),
  ADD KEY `subscription_transitions_to_package_id_foreign` (`to_package_id`),
  ADD KEY `subscription_transitions_subscription_id_foreign` (`subscription_id`),
  ADD KEY `subscription_transitions_processed_by_foreign` (`processed_by`),
  ADD KEY `subscription_transitions_instansi_id_effective_from_index` (`instansi_id`,`effective_from`),
  ADD KEY `subscription_transitions_transition_type_index` (`transition_type`);

--
-- Indexes for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_requests_requested_by_foreign` (`requested_by`),
  ADD KEY `support_requests_instansi_id_status_index` (`instansi_id`,`status`);

--
-- Indexes for table `theme_settings`
--
ALTER TABLE `theme_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `theme_settings_instansi_id_unique` (`instansi_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_instansi_id_foreign` (`instansi_id`),
  ADD KEY `users_deleted_by_foreign` (`deleted_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance_policies`
--
ALTER TABLE `attendance_policies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `backup_logs`
--
ALTER TABLE `backup_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulk_notification_logs`
--
ALTER TABLE `bulk_notification_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_participants`
--
ALTER TABLE `chat_participants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company_themes`
--
ALTER TABLE `company_themes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `compliance_logs`
--
ALTER TABLE `compliance_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_deletion_requests`
--
ALTER TABLE `data_deletion_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `data_exports`
--
ALTER TABLE `data_exports`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_usage`
--
ALTER TABLE `discount_usage`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `features`
--
ALTER TABLE `features`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instansis`
--
ALTER TABLE `instansis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instansi_feature_overrides`
--
ALTER TABLE `instansi_feature_overrides`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instansi_subscription_addons`
--
ALTER TABLE `instansi_subscription_addons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_change_requests`
--
ALTER TABLE `package_change_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `package_features`
--
ALTER TABLE `package_features`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_addons`
--
ALTER TABLE `subscription_addons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_history`
--
ALTER TABLE `subscription_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_transitions`
--
ALTER TABLE `subscription_transitions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `theme_settings`
--
ALTER TABLE `theme_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `attendances_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendance_policies`
--
ALTER TABLE `attendance_policies`
  ADD CONSTRAINT `attendance_policies_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `backup_logs`
--
ALTER TABLE `backup_logs`
  ADD CONSTRAINT `backup_logs_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bulk_notification_logs`
--
ALTER TABLE `bulk_notification_logs`
  ADD CONSTRAINT `bulk_notification_logs_sent_by_foreign` FOREIGN KEY (`sent_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_participants`
--
ALTER TABLE `chat_participants`
  ADD CONSTRAINT `chat_participants_chat_id_foreign` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `company_themes`
--
ALTER TABLE `company_themes`
  ADD CONSTRAINT `company_themes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `compliance_logs`
--
ALTER TABLE `compliance_logs`
  ADD CONSTRAINT `compliance_logs_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `compliance_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `data_deletion_requests`
--
ALTER TABLE `data_deletion_requests`
  ADD CONSTRAINT `data_deletion_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `data_deletion_requests_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `data_deletion_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `data_exports`
--
ALTER TABLE `data_exports`
  ADD CONSTRAINT `data_exports_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `data_exports_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `discount_usage`
--
ALTER TABLE `discount_usage`
  ADD CONSTRAINT `discount_usage_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discount_usage_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discount_usage_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_schedules`
--
ALTER TABLE `employee_schedules`
  ADD CONSTRAINT `employee_schedules_policy_id_foreign` FOREIGN KEY (`policy_id`) REFERENCES `attendance_policies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `instansis`
--
ALTER TABLE `instansis`
  ADD CONSTRAINT `instansis_archived_by_foreign` FOREIGN KEY (`archived_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `instansis_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `instansis_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `instansi_feature_overrides`
--
ALTER TABLE `instansi_feature_overrides`
  ADD CONSTRAINT `instansi_feature_overrides_applied_by_foreign` FOREIGN KEY (`applied_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instansi_feature_overrides_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instansi_feature_overrides_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `instansi_subscription_addons`
--
ALTER TABLE `instansi_subscription_addons`
  ADD CONSTRAINT `instansi_subscription_addons_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instansi_subscription_addons_subscription_addon_id_foreign` FOREIGN KEY (`subscription_addon_id`) REFERENCES `subscription_addons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_change_requests`
--
ALTER TABLE `package_change_requests`
  ADD CONSTRAINT `package_change_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `package_change_requests_current_package_id_foreign` FOREIGN KEY (`current_package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_change_requests_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_change_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_change_requests_requested_package_id_foreign` FOREIGN KEY (`requested_package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `package_features`
--
ALTER TABLE `package_features`
  ADD CONSTRAINT `package_features_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_features_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD CONSTRAINT `payrolls_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payrolls_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD CONSTRAINT `qr_codes_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_history`
--
ALTER TABLE `subscription_history`
  ADD CONSTRAINT `subscription_history_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_history_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscription_history_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_transitions`
--
ALTER TABLE `subscription_transitions`
  ADD CONSTRAINT `subscription_transitions_from_package_id_foreign` FOREIGN KEY (`from_package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_transitions_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_transitions_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_transitions_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_transitions_to_package_id_foreign` FOREIGN KEY (`to_package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD CONSTRAINT `support_requests_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `support_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `theme_settings`
--
ALTER TABLE `theme_settings`
  ADD CONSTRAINT `theme_settings_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE SET NULL;
COMMIT;

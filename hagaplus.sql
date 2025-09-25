SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE activity_logs (
  id bigint UNSIGNED NOT NULL,
  log_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  description text COLLATE utf8mb4_unicode_ci NOT NULL,
  subject_type varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  subject_id bigint UNSIGNED DEFAULT NULL,
  causer_type varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  causer_id bigint UNSIGNED DEFAULT NULL,
  properties json DEFAULT NULL,
  event varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  batch_uuid varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  instansi_id bigint UNSIGNED DEFAULT NULL,
  ip_address varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_agent text COLLATE utf8mb4_unicode_ci,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO activity_logs (id, log_name, description, subject_type, subject_id, causer_type, causer_id, properties, event, batch_uuid, instansi_id, ip_address, user_agent, created_at, updated_at) VALUES
(1, 'attendance', 'User checked in via QR Code', 'App\\Models\\Attendance', 1, 'App\\Models\\User', 6, '{\"method\": \"qr\", \"location\": \"-6.208763,106.845599\"}', 'check_in', NULL, 1, '192.168.1.100', 'HagaPlus Mobile App v1.0', '2025-09-16 08:55:00', '2025-09-16 08:55:00'),
(2, 'attendance', 'User checked out via QR Code', 'App\\Models\\Attendance', 1, 'App\\Models\\User', 6, '{\"method\": \"qr\", \"work_duration\": 555}', 'check_out', NULL, 1, '192.168.1.100', 'HagaPlus Mobile App v1.0', '2025-09-16 18:10:00', '2025-09-16 18:10:00'),
(3, 'leave', 'Leave request submitted', 'App\\Models\\Leave', 1, 'App\\Models\\User', 6, '{\"days\": 3, \"type\": \"annual\", \"reason\": \"Liburan keluarga ke Bali\"}', 'created', NULL, 1, '192.168.1.105', 'Mozilla/5.0 Chrome/91.0', '2025-09-15 11:02:53', '2025-09-15 11:02:53'),
(4, 'leave', 'Leave request approved', 'App\\Models\\Leave', 1, 'App\\Models\\User', 2, '{\"status\": \"approved\", \"approved_by\": \"Budi Santoso\"}', 'approved', NULL, 1, '192.168.1.50', 'Mozilla/5.0 Chrome/91.0', '2025-09-20 11:02:53', '2025-09-20 11:02:53'),
(5, 'user', 'New employee registered', 'App\\Models\\User', 6, 'App\\Models\\User', 2, '{\"name\": \"Andi Pratama\", \"position\": \"Software Engineer\"}', 'created', NULL, 1, '192.168.1.50', 'Mozilla/5.0 Chrome/91.0', '2025-01-15 11:02:53', '2025-01-15 11:02:53'),
(6, 'attendance', 'User checked in via GPS', 'App\\Models\\Attendance', 2, 'App\\Models\\User', 6, '{\"method\": \"gps\", \"location\": \"-6.208763,106.845599\"}', 'check_in', NULL, 1, '192.168.1.100', 'HagaPlus Mobile App v1.0', '2025-09-17 09:20:00', '2025-09-17 09:20:00'),
(7, 'payroll', 'Payroll processed for September 2024', 'App\\Models\\Payroll', 1, 'App\\Models\\User', 2, '{\"period\": \"2024-09\", \"net_salary\": 9920000}', 'processed', NULL, 1, '192.168.1.50', 'Mozilla/5.0 Chrome/91.0', '2024-10-05 10:00:00', '2024-10-05 10:00:00');

CREATE TABLE attendances (
  id bigint UNSIGNED NOT NULL,
  user_id bigint UNSIGNED NOT NULL,
  branch_id bigint UNSIGNED NOT NULL,
  attendance_date date NOT NULL,
  check_in_time timestamp NULL DEFAULT NULL,
  check_out_time timestamp NULL DEFAULT NULL,
  check_in_method enum('qr','gps','face_id','manual') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  check_out_method enum('qr','gps','face_id','manual') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  check_in_location varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  check_out_location varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  check_in_photo varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  check_out_photo varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  work_duration int NOT NULL DEFAULT '0',
  break_duration int NOT NULL DEFAULT '0',
  overtime_duration int NOT NULL DEFAULT '0',
  late_minutes int NOT NULL DEFAULT '0',
  early_checkout_minutes int NOT NULL DEFAULT '0',
  status enum('present','late','absent','partial','leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'absent',
  notes text COLLATE utf8mb4_unicode_ci,
  approved_by bigint UNSIGNED DEFAULT NULL,
  approved_at timestamp NULL DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO attendances (id, user_id, branch_id, attendance_date, check_in_time, check_out_time, check_in_method, check_out_method, check_in_location, check_out_location, check_in_photo, check_out_photo, work_duration, break_duration, overtime_duration, late_minutes, early_checkout_minutes, status, notes, approved_by, approved_at, created_at, updated_at) VALUES
(1, 6, 1, '2025-09-16', '2025-09-16 08:55:00', '2025-09-16 18:10:00', 'qr', 'qr', '-6.208763,106.845599', NULL, NULL, NULL, 555, 60, 75, 0, 0, 'present', 'Hadir tepat waktu', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 6, 1, '2025-09-17', '2025-09-17 09:20:00', '2025-09-17 18:05:00', 'gps', 'gps', '-6.208763,106.845599', NULL, NULL, NULL, 525, 60, 45, 20, 0, 'late', 'Terlambat karena macet', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 6, 1, '2025-09-18', '2025-09-18 08:45:00', '2025-09-18 18:15:00', 'face_id', 'face_id', '-6.208763,106.845599', NULL, NULL, NULL, 570, 60, 90, 0, 0, 'present', 'Kerja lembur', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 6, 1, '2025-09-19', '2025-09-19 09:00:00', '2025-09-19 18:00:00', 'qr', 'qr', '-6.208763,106.845599', NULL, NULL, NULL, 540, 60, 60, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 6, 1, '2025-09-20', '2025-09-20 08:50:00', '2025-09-20 17:55:00', 'gps', 'gps', '-6.208763,106.845599', NULL, NULL, NULL, 545, 60, 65, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(6, 6, 1, '2025-09-21', '2025-09-21 09:10:00', '2025-09-21 18:20:00', 'face_id', 'face_id', '-6.208763,106.845599', NULL, NULL, NULL, 550, 60, 70, 10, 0, 'late', 'Sedikit terlambat', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(7, 7, 1, '2025-09-16', '2025-09-16 08:58:00', '2025-09-16 18:05:00', 'qr', 'qr', '-6.208763,106.845599', NULL, NULL, NULL, 547, 60, 67, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(8, 7, 1, '2025-09-17', '2025-09-17 09:05:00', '2025-09-17 18:10:00', 'gps', 'gps', '-6.208763,106.845599', NULL, NULL, NULL, 545, 60, 65, 5, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(9, 7, 1, '2025-09-18', '2025-09-18 08:55:00', '2025-09-18 18:00:00', 'face_id', 'face_id', '-6.208763,106.845599', NULL, NULL, NULL, 545, 60, 65, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(10, 7, 1, '2025-09-19', '2025-09-19 09:15:00', '2025-09-19 18:15:00', 'qr', 'qr', '-6.208763,106.845599', NULL, NULL, NULL, 540, 60, 60, 15, 0, 'late', 'Meeting pagi', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(11, 7, 1, '2025-09-20', '2025-09-20 08:50:00', '2025-09-20 17:50:00', 'gps', 'gps', '-6.208763,106.845599', NULL, NULL, NULL, 540, 60, 60, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(12, 14, 4, '2025-09-16', '2025-09-16 10:05:00', '2025-09-16 19:10:00', 'qr', 'qr', '-6.921831,107.607147', NULL, NULL, NULL, 545, 90, 65, 5, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(13, 14, 4, '2025-09-17', '2025-09-17 09:55:00', '2025-09-17 19:05:00', 'gps', 'gps', '-6.921831,107.607147', NULL, NULL, NULL, 550, 90, 70, 0, 0, 'present', 'Datang lebih awal', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(14, 14, 4, '2025-09-18', '2025-09-18 10:20:00', '2025-09-18 19:25:00', 'face_id', 'face_id', '-6.921831,107.607147', NULL, NULL, NULL, 545, 90, 65, 20, 0, 'late', 'Meeting client pagi', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(15, 14, 4, '2025-09-19', '2025-09-19 10:00:00', '2025-09-19 19:00:00', 'qr', 'qr', '-6.921831,107.607147', NULL, NULL, NULL, 540, 90, 60, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(16, 19, 6, '2025-09-16', '2025-09-16 07:58:00', '2025-09-16 17:02:00', 'qr', 'qr', '-7.257472,112.752088', NULL, NULL, NULL, 544, 60, 64, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(17, 19, 6, '2025-09-17', '2025-09-17 08:05:00', '2025-09-17 17:05:00', 'gps', 'gps', '-7.257472,112.752088', NULL, NULL, NULL, 540, 60, 60, 5, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(18, 19, 6, '2025-09-18', '2025-09-18 08:12:00', '2025-09-18 17:12:00', 'qr', 'qr', '-7.257472,112.752088', NULL, NULL, NULL, 540, 60, 60, 12, 0, 'late', 'Kendaraan mogok', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(19, 23, 7, '2025-09-16', '2025-09-16 05:58:00', '2025-09-16 14:02:00', 'qr', 'qr', '-6.296406,107.154808', NULL, NULL, NULL, 484, 30, 4, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(20, 23, 7, '2025-09-17', '2025-09-17 06:03:00', '2025-09-17 14:05:00', 'gps', 'gps', '-6.296406,107.154808', NULL, NULL, NULL, 482, 30, 2, 3, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(21, 23, 7, '2025-09-18', '2025-09-18 06:08:00', '2025-09-18 14:10:00', 'qr', 'qr', '-6.296406,107.154808', NULL, NULL, NULL, 482, 30, 2, 8, 0, 'late', 'Bangun kesiangan', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(22, 23, 7, '2025-09-19', '2025-09-19 05:55:00', '2025-09-19 14:15:00', 'gps', 'gps', '-6.296406,107.154808', NULL, NULL, NULL, 500, 30, 20, 0, 0, 'present', 'Lembur produksi', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(23, 28, 7, '2025-09-16', '2025-09-16 13:58:00', '2025-09-16 22:02:00', 'qr', 'qr', '-6.296406,107.154808', NULL, NULL, NULL, 484, 30, 4, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(24, 28, 7, '2025-09-17', '2025-09-17 14:05:00', '2025-09-17 22:05:00', 'gps', 'gps', '-6.296406,107.154808', NULL, NULL, NULL, 480, 30, 0, 5, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(25, 28, 7, '2025-09-18', '2025-09-18 14:02:00', '2025-09-18 22:10:00', 'qr', 'qr', '-6.296406,107.154808', NULL, NULL, NULL, 488, 30, 8, 2, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(26, 8, 2, '2025-09-16', '2025-09-16 08:45:00', '2025-09-16 18:30:00', 'qr', 'qr', '-6.238270,107.001567', NULL, NULL, NULL, 585, 60, 105, 0, 0, 'present', 'Project deadline', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(27, 9, 2, '2025-09-16', '2025-09-16 08:55:00', '2025-09-16 17:45:00', 'gps', 'gps', '-6.238270,107.001567', NULL, NULL, NULL, 530, 60, 50, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(28, 10, 3, '2025-09-16', '2025-09-16 09:10:00', '2025-09-16 18:05:00', 'face_id', 'face_id', '-6.297524,106.718124', NULL, NULL, NULL, 535, 60, 55, 10, 0, 'late', 'Traffic jam', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(29, 11, 3, '2025-09-16', '2025-09-16 08:50:00', '2025-09-16 17:50:00', 'qr', 'qr', '-6.297524,106.718124', NULL, NULL, NULL, 540, 60, 60, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(30, 12, 1, '2025-09-16', '2025-09-16 09:05:00', '2025-09-16 18:15:00', 'qr', 'qr', '-6.208763,106.845599', NULL, NULL, NULL, 550, 60, 70, 5, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(31, 15, 4, '2025-09-16', '2025-09-16 09:50:00', '2025-09-16 19:00:00', 'gps', 'gps', '-6.921831,107.607147', NULL, NULL, NULL, 550, 90, 70, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(32, 16, 5, '2025-09-16', '2025-09-16 10:15:00', '2025-09-16 19:10:00', 'face_id', 'face_id', '-6.895562,107.613144', NULL, NULL, NULL, 535, 90, 55, 15, 0, 'late', 'Client meeting', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(33, 17, 5, '2025-09-16', '2025-09-16 10:00:00', '2025-09-16 19:05:00', 'qr', 'qr', '-6.895562,107.613144', NULL, NULL, NULL, 545, 90, 65, 0, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(34, 18, 4, '2025-09-16', '2025-09-16 09:55:00', '2025-09-16 19:15:00', 'gps', 'gps', '-6.921831,107.607147', NULL, NULL, NULL, 560, 90, 80, 0, 0, 'present', 'Bug fixing', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(35, 20, 6, '2025-09-16', '2025-09-16 08:02:00', '2025-09-16 17:03:00', 'qr', 'qr', '-7.257472,112.752088', NULL, NULL, NULL, 541, 60, 61, 2, 0, 'present', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(36, 21, 6, '2025-09-16', '2025-09-16 07:55:00', '2025-09-16 17:10:00', 'gps', 'gps', '-7.257472,112.752088', NULL, NULL, NULL, 555, 60, 75, 0, 0, 'present', 'Store opening preparation', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(37, 22, 6, '2025-09-16', '2025-09-16 08:10:00', '2025-09-16 17:15:00', 'qr', 'qr', '-7.257472,112.752088', NULL, NULL, NULL, 545, 60, 65, 10, 0, 'late', 'Inventory check', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(38, 19, 6, '2025-09-21', '2025-09-21 08:00:00', '2025-09-21 16:00:00', 'qr', 'qr', '-7.257472,112.752088', NULL, NULL, NULL, 480, 60, 0, 0, 0, 'present', 'Weekend shift', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(39, 20, 6, '2025-09-21', '2025-09-21 08:05:00', '2025-09-21 16:05:00', 'gps', 'gps', '-7.257472,112.752088', NULL, NULL, NULL, 480, 60, 0, 5, 0, 'present', 'Weekend shift', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(40, 29, 8, '2025-09-16', '2025-09-16 22:00:00', '2025-09-17 06:05:00', 'qr', 'qr', '-6.301206,107.307809', NULL, NULL, NULL, 485, 30, 5, 0, 0, 'present', 'Night shift production', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(41, 30, 8, '2025-09-16', '2025-09-16 22:02:00', '2025-09-17 06:00:00', 'gps', 'gps', '-6.301206,107.307809', NULL, NULL, NULL, 478, 30, 0, 2, 0, 'present', 'Night shift admin', NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(42, 6, 1, '2025-09-25', '2025-09-25 08:50:00', NULL, 'face_id', NULL, '-6.208763,106.845599', NULL, NULL, NULL, 0, 0, 0, 0, 0, 'present', 'Currently working', NULL, NULL, '2025-09-25 08:50:00', '2025-09-25 08:50:00'),
(43, 7, 1, '2025-09-25', '2025-09-25 09:15:00', NULL, 'qr', NULL, '-6.208763,106.845599', NULL, NULL, NULL, 0, 0, 0, 15, 0, 'late', 'Traffic jam, currently working', NULL, NULL, '2025-09-25 09:15:00', '2025-09-25 09:15:00'),
(44, 14, 4, '2025-09-25', '2025-09-25 10:00:00', NULL, 'gps', NULL, '-6.921831,107.607147', NULL, NULL, NULL, 0, 0, 0, 0, 0, 'present', 'Creative team working', NULL, NULL, '2025-09-25 10:00:00', '2025-09-25 10:00:00');

CREATE TABLE attendance_policies (
  id bigint UNSIGNED NOT NULL,
  company_id bigint UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  work_days json NOT NULL,
  start_time time NOT NULL,
  end_time time NOT NULL,
  break_duration int NOT NULL DEFAULT '60',
  late_tolerance int NOT NULL DEFAULT '15',
  early_checkout_tolerance int NOT NULL DEFAULT '15',
  overtime_after_minutes int NOT NULL DEFAULT '0',
  attendance_methods json NOT NULL,
  auto_checkout tinyint(1) NOT NULL DEFAULT '0',
  auto_checkout_time time DEFAULT NULL,
  is_default tinyint(1) NOT NULL DEFAULT '0',
  is_active tinyint(1) NOT NULL DEFAULT '1',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO attendance_policies (id, company_id, name, work_days, start_time, end_time, break_duration, late_tolerance, early_checkout_tolerance, overtime_after_minutes, attendance_methods, auto_checkout, auto_checkout_time, is_default, is_active, created_at, updated_at) VALUES
(1, 1, 'Kebijakan Standar Office', '[1, 2, 3, 4, 5]', '09:00:00', '18:00:00', 60, 15, 15, 480, '[\"qr\", \"gps\", \"face_id\"]', 0, NULL, 1, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 2, 'Creative Team Schedule', '[1, 2, 3, 4, 5]', '10:00:00', '19:00:00', 90, 20, 20, 540, '[\"qr\", \"gps\", \"face_id\"]', 0, NULL, 1, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 3, 'Retail Store Hours', '[1, 2, 3, 4, 5, 6]', '08:00:00', '17:00:00', 60, 10, 10, 540, '[\"qr\", \"gps\"]', 1, '17:30:00', 1, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 4, 'Manufacturing Shift A', '[1, 2, 3, 4, 5, 6]', '06:00:00', '14:00:00', 30, 5, 5, 480, '[\"qr\", \"gps\"]', 0, NULL, 1, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 4, 'Manufacturing Shift B', '[1, 2, 3, 4, 5, 6]', '14:00:00', '22:00:00', 30, 5, 5, 480, '[\"qr\", \"gps\"]', 0, NULL, 0, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(6, 4, 'Manufacturing Shift C', '[1, 2, 3, 4, 5, 6]', '22:00:00', '06:00:00', 30, 5, 5, 480, '[\"qr\", \"gps\"]', 0, NULL, 0, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE branches (
  id bigint UNSIGNED NOT NULL,
  company_id bigint UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  address text COLLATE utf8mb4_unicode_ci,
  latitude decimal(10,8) DEFAULT NULL,
  longitude decimal(11,8) DEFAULT NULL,
  radius int NOT NULL DEFAULT '100',
  timezone varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  is_active tinyint(1) NOT NULL DEFAULT '1',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO branches (id, company_id, name, address, latitude, longitude, radius, timezone, is_active, created_at, updated_at) VALUES
(1, 1, 'Kantor Pusat Jakarta', 'Jl. Sudirman No. 123, Jakarta Pusat', -6.20876300, 106.84559900, 100, 'Asia/Jakarta', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 1, 'Cabang Bekasi', 'Jl. Ahmad Yani No. 67, Bekasi Timur', -6.23827000, 107.00156700, 150, 'Asia/Jakarta', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 1, 'Cabang Tangerang', 'Jl. Imam Bonjol No. 34, Tangerang Selatan', -6.29752400, 106.71812400, 120, 'Asia/Jakarta', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 2, 'Kantor Pusat Bandung', 'Jl. Asia Afrika No. 45, Bandung', -6.92183100, 107.60714700, 80, 'Asia/Jakarta', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 2, 'Co-working Space Dago', 'Jl. Ir. H. Juanda No. 123, Bandung', -6.89556200, 107.61314400, 50, 'Asia/Jakarta', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(6, 3, 'Toko Utama', 'Jl. Diponegoro No. 88, Surabaya', -7.25747200, 112.75208800, 75, 'Asia/Jakarta', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(7, 4, 'Pabrik Utama Cikarang', 'Kawasan Industri Jababeka Blok A-1, Cikarang', -6.29640600, 107.15480800, 300, 'Asia/Jakarta', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(8, 4, 'Gudang Distribusi Karawang', 'Jl. Raya Karawang-Jakarta KM 45, Karawang', -6.30120600, 107.30780900, 200, 'Asia/Jakarta', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  value mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  expiration int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO cache (`key`, value, expiration) VALUES
('attendance_stats_company_1', 's:165:\"{\"total_employees\":8,\"present_today\":6,\"late_today\":1,\"absent_today\":1,\"avg_work_hours\":8.5,\"overtime_hours\":12,\"attendance_rate\":87.5,\"punctuality_rate\":85.7}\";', 1758695400),
('branch_qr_code_1', 's:32:\"QR_BRANCH_1_ABC123DEF456\";', 1758695400),
('company_settings_1', 's:215:\"{\"working_hours_start\":\"09:00\",\"working_hours_end\":\"18:00\",\"late_tolerance\":15,\"overtime_enabled\":true,\"face_recognition_enabled\":true,\"auto_checkout\":false,\"break_duration\":60,\"timezone\":\"Asia/Jakarta\"}\";', 1758699000),
('user_permissions_6', 's:85:\"[\"attendance.view\",\"attendance.create\",\"leave.create\",\"leave.view\",\"profile.update\"]\";', 1758699000);

CREATE TABLE cache_locks (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  owner varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  expiration int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE companies (
  id bigint UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  email varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  phone varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  address text COLLATE utf8mb4_unicode_ci,
  logo varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  package_id bigint UNSIGNED DEFAULT NULL,
  subscription_start datetime DEFAULT NULL,
  subscription_end datetime DEFAULT NULL,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  max_employees int NOT NULL DEFAULT '10',
  max_branches int NOT NULL DEFAULT '1',
  settings json DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO companies (id, name, email, phone, address, logo, package_id, subscription_start, subscription_end, is_active, max_employees, max_branches, settings, created_at, updated_at) VALUES
(1, 'PT Teknologi Maju Bersama', 'admin@teknologimaju.com', '021-5555-0001', 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta', NULL, 3, '2024-08-01 00:00:00', '2024-10-31 23:59:59', 1, 200, 20, '{\"currency\": \"IDR\", \"timezone\": \"Asia/Jakarta\", \"date_format\": \"d/m/Y\"}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 'CV Kreatif Digital Solutions', 'hr@kreatifdigital.com', '022-3333-0002', 'Jl. Asia Afrika No. 45, Bandung, Jawa Barat', NULL, 2, '2024-08-15 00:00:00', '2024-11-14 23:59:59', 1, 50, 5, '{\"timezone\": \"Asia/Jakarta\", \"working_hours\": \"10:00-19:00\"}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 'Toko Berkah Jaya', 'owner@berkahjaya.com', '031-7777-0003', 'Jl. Diponegoro No. 88, Surabaya, Jawa Timur', NULL, 1, '2024-09-01 00:00:00', '2024-10-01 23:59:59', 1, 10, 1, '{\"timezone\": \"Asia/Jakarta\", \"working_days\": [1, 2, 3, 4, 5, 6]}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 'PT Industri Manufaktur Nusantara', 'hrd@manufakturnusantara.co.id', '024-9999-0004', 'Kawasan Industri Jababeka, Cikarang, Jawa Barat', NULL, 4, '2024-07-01 00:00:00', '2024-10-31 23:59:59', 1, 1000, 50, '{\"timezone\": \"Asia/Jakarta\", \"shift_based\": true, \"overtime_enabled\": true}', '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE company_themes (
  id bigint UNSIGNED NOT NULL,
  company_id bigint UNSIGNED NOT NULL,
  primary_color varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3b82f6',
  secondary_color varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#64748b',
  logo varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  favicon varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  custom_css text COLLATE utf8mb4_unicode_ci,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO company_themes (id, company_id, primary_color, secondary_color, logo, favicon, custom_css, is_active, created_at, updated_at) VALUES
(1, 1, '#3b82f6', '#64748b', 'themes/company1/logo.png', 'themes/company1/favicon.ico', '.navbar { background: linear-gradient(90deg, #3b82f6, #1e40af); }', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 2, '#8b5cf6', '#a855f7', 'themes/company2/logo.png', 'themes/company2/favicon.ico', '.header { border-bottom: 3px solid #8b5cf6; }', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 3, '#f59e0b', '#d97706', 'themes/company3/logo.png', 'themes/company3/favicon.ico', '.btn-primary { background-color: #f59e0b; border-color: #f59e0b; }', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 4, '#ef4444', '#dc2626', 'themes/company4/logo.png', 'themes/company4/favicon.ico', '.sidebar { background: linear-gradient(180deg, #ef4444, #dc2626); }', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE employees (
  id bigint UNSIGNED NOT NULL,
  user_id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  branch_id bigint UNSIGNED DEFAULT NULL,
  employee_id varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  position varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  department varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  salary decimal(10,2) NOT NULL,
  hire_date date NOT NULL,
  status enum('active','inactive','terminated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO employees (id, user_id, instansi_id, branch_id, employee_id, position, department, salary, hire_date, status, created_at, updated_at) VALUES
(1, 6, 1, 1, 'TMB006', 'Software Engineer', 'Engineering', 8500000.00, '2023-01-15', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 7, 1, 1, 'TMB007', 'UI/UX Designer', 'Design', 7500000.00, '2023-02-20', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 8, 1, 2, 'TMB008', 'Project Manager', 'Project Management', 12000000.00, '2022-11-10', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 9, 1, 2, 'TMB009', 'Quality Assurance', 'Quality Control', 6500000.00, '2023-03-05', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 10, 1, 3, 'TMB010', 'DevOps Engineer', 'Infrastructure', 9500000.00, '2023-01-28', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(6, 11, 1, 3, 'TMB011', 'Business Analyst', 'Business', 8000000.00, '2023-04-12', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(7, 12, 1, 1, 'TMB012', 'Frontend Developer', 'Engineering', 7800000.00, '2023-05-17', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(8, 13, 1, 1, 'TMB013', 'Marketing Specialist', 'Marketing', 6800000.00, '2023-06-22', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(9, 14, 2, 4, 'KDS014', 'Web Developer', 'Development', 7000000.00, '2023-02-10', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(10, 15, 2, 4, 'KDS015', 'Graphic Designer', 'Creative', 6200000.00, '2023-03-15', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(11, 16, 2, 5, 'KDS016', 'Content Creator', 'Marketing', 5800000.00, '2023-04-20', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(12, 17, 2, 5, 'KDS017', 'Digital Marketer', 'Marketing', 6500000.00, '2023-01-08', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(13, 18, 2, 4, 'KDS018', 'Full Stack Developer', 'Development', 8200000.00, '2023-05-03', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(14, 19, 3, 6, 'BJ019', 'Sales Associate', 'Sales', 4200000.00, '2022-08-15', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(15, 20, 3, 6, 'BJ020', 'Cashier', 'Operations', 3800000.00, '2023-01-20', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(16, 21, 3, 6, 'BJ021', 'Store Manager', 'Management', 6500000.00, '2022-05-10', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(17, 22, 3, 6, 'BJ022', 'Inventory Staff', 'Operations', 4000000.00, '2023-03-12', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(18, 23, 4, 7, 'IMN023', 'Production Operator', 'Production', 5200000.00, '2022-09-01', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(19, 24, 4, 7, 'IMN024', 'Quality Control Specialist', 'Quality Control', 6800000.00, '2022-10-15', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(20, 25, 4, 8, 'IMN025', 'Maintenance Technician', 'Maintenance', 6200000.00, '2023-01-03', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(21, 26, 4, 8, 'IMN026', 'Production Supervisor', 'Production', 8500000.00, '2022-07-20', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(22, 27, 4, 7, 'IMN027', 'Safety Officer', 'Safety', 7200000.00, '2023-02-14', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(23, 28, 4, 7, 'IMN028', 'Warehouse Staff', 'Logistics', 4800000.00, '2023-04-05', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(24, 29, 4, 8, 'IMN029', 'Machine Operator', 'Production', 5500000.00, '2023-03-18', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(25, 30, 4, 8, 'IMN030', 'Admin Staff', 'Administration', 4500000.00, '2023-06-01', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE employee_schedules (
  id bigint UNSIGNED NOT NULL,
  user_id bigint UNSIGNED NOT NULL,
  policy_id bigint UNSIGNED NOT NULL,
  effective_date date NOT NULL,
  end_date date DEFAULT NULL,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO employee_schedules (id, user_id, policy_id, effective_date, end_date, is_active, created_at, updated_at) VALUES
(1, 6, 1, '2023-01-15', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 7, 1, '2023-02-20', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 8, 1, '2022-11-10', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 9, 1, '2023-03-05', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 10, 1, '2023-01-28', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(6, 11, 1, '2023-04-12', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(7, 12, 1, '2023-05-17', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(8, 13, 1, '2023-06-22', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(9, 14, 2, '2023-02-10', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(10, 15, 2, '2023-03-15', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(11, 16, 2, '2023-04-20', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(12, 17, 2, '2023-01-08', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(13, 18, 2, '2023-05-03', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(14, 19, 3, '2022-08-15', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(15, 20, 3, '2023-01-20', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(16, 21, 3, '2022-05-10', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(17, 22, 3, '2023-03-12', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(18, 23, 4, '2022-09-01', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(19, 24, 4, '2022-10-15', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(20, 25, 4, '2023-01-03', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(21, 26, 4, '2022-07-20', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(22, 27, 4, '2023-02-14', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(23, 28, 5, '2023-04-05', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(24, 29, 5, '2023-03-18', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(25, 30, 4, '2023-06-01', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE failed_jobs (
  id bigint UNSIGNED NOT NULL,
  uuid varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  connection text COLLATE utf8mb4_unicode_ci NOT NULL,
  queue text COLLATE utf8mb4_unicode_ci NOT NULL,
  payload longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  exception longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  failed_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) VALUES
(1, 'f47ac10b-58cc-4372-a567-0e02b2c3d479', 'database', 'default', '{\"uuid\":\"f47ac10b-58cc-4372-a567-0e02b2c3d479\",\"displayName\":\"App\\\\Jobs\\\\ProcessPayroll\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProcessPayroll\",\"command\":\"O:25:\\\"App\\\\Jobs\\\\ProcessPayroll\\\":1:{s:6:\\\"userId\\\";i:6;}\"}}', 'Illuminate\\Database\\QueryException: SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry', '2025-09-22 10:30:00'),
(2, 'a1b2c3d4-e5f6-7890-abcd-ef1234567890', 'database', 'default', '{\"uuid\":\"a1b2c3d4-e5f6-7890-abcd-ef1234567890\",\"displayName\":\"App\\\\Jobs\\\\SendNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendNotification\",\"command\":\"O:26:\\\"App\\\\Jobs\\\\SendNotification\\\":2:{s:6:\\\"userId\\\";i:15;s:7:\\\"message\\\";s:50:\\\"Reminder: Please complete your attendance today\\\";}\"}}', 'GuzzleHttp\\Exception\\ConnectException: cURL error 28: Connection timed out', '2025-09-21 14:15:00');

CREATE TABLE features (
  id bigint UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  slug varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  description text COLLATE utf8mb4_unicode_ci,
  category enum('attendance','payroll','reporting','integration','customization') COLLATE utf8mb4_unicode_ci NOT NULL,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  sort_order int NOT NULL DEFAULT '0',
  config json DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO features (id, name, slug, description, category, is_active, sort_order, config, created_at, updated_at) VALUES
(1, 'QR Code Attendance', 'qr_attendance', 'Absensi menggunakan QR Code', 'attendance', 1, 1, '{\"max_distance\": 10, \"refresh_interval\": 300}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 'GPS Attendance', 'gps_attendance', 'Absensi menggunakan lokasi GPS', 'attendance', 1, 2, '{\"radius_tolerance\": 100, \"accuracy_required\": 50}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 'Face Recognition', 'face_recognition', 'Absensi menggunakan pengenalan wajah', 'attendance', 1, 3, '{\"max_attempts\": 3, \"confidence_threshold\": 0.85}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 'Shift Management', 'shift_management', 'Manajemen jadwal shift karyawan', 'attendance', 1, 4, '{\"overlap_allowed\": false, \"max_shifts_per_day\": 3}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 'Leave Management', 'leave_management', 'Manajemen cuti karyawan', 'attendance', 1, 5, '{\"max_leave_days\": 12, \"approval_required\": true}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(6, 'Payroll Processing', 'payroll_processing', 'Pemrosesan gaji karyawan', 'payroll', 1, 6, '{\"tax_enabled\": true, \"auto_calculate\": true}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(7, 'Overtime Calculation', 'overtime_calculation', 'Perhitungan lembur otomatis', 'payroll', 1, 7, '{\"minimum_minutes\": 30, \"rate_multiplier\": 1.5}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(8, 'Basic Reports', 'basic_reports', 'Laporan dasar absensi dan kehadiran', 'reporting', 1, 8, '{\"export_formats\": [\"pdf\", \"excel\"], \"schedule_enabled\": false}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(9, 'Advanced Reports', 'advanced_reports', 'Laporan lanjutan dengan analitik', 'reporting', 1, 9, '{\"dashboard\": true, \"charts_enabled\": true, \"custom_filters\": true}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(10, 'API Access', 'api_access', 'Akses API untuk integrasi', 'integration', 1, 10, '{\"rate_limit\": 1000, \"authentication\": \"oauth2\"}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(11, 'Custom Branding', 'custom_branding', 'Kustomisasi tema dan branding', 'customization', 1, 11, '{\"logo_upload\": true, \"css_override\": true, \"color_schemes\": true}', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(12, 'Multi Branch', 'multi_branch', 'Dukungan multiple cabang', 'attendance', 1, 12, '{\"max_branches\": 50, \"central_management\": true}', '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE instansis (
  id bigint UNSIGNED NOT NULL,
  nama_instansi varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  subdomain varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  status_langganan enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  deleted_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO instansis (id, nama_instansi, subdomain, status_langganan, created_at, updated_at, deleted_at) VALUES
(1, 'PT Teknologi Maju Bersama', 'teknologimaju', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53', NULL),
(2, 'CV Kreatif Digital Solutions', 'kreatifdigital', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53', NULL),
(3, 'Toko Berkah Jaya', 'berkahjaya', 'active', '2025-09-22 11:02:53', '2025-09-22 11:02:53', NULL),
(4, 'PT Industri Manufaktur Nusantara', 'manufakturnusantara', 'active', '2025-09-22 11:02:53', '2025-09-22 09:38:31', NULL),
(5, 'Klinik Sehat Sentosa', 'kliniksehat', 'inactive', '2025-09-22 11:02:53', '2025-09-22 11:02:53', NULL);

CREATE TABLE instansi_feature_overrides (
  id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  feature_id bigint UNSIGNED NOT NULL,
  is_enabled tinyint(1) NOT NULL,
  custom_limits json DEFAULT NULL,
  custom_config json DEFAULT NULL,
  reason text COLLATE utf8mb4_unicode_ci,
  effective_from date NOT NULL,
  effective_until date DEFAULT NULL,
  applied_by bigint UNSIGNED NOT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO instansi_feature_overrides (id, instansi_id, feature_id, is_enabled, custom_limits, custom_config, reason, effective_from, effective_until, applied_by, created_at, updated_at) VALUES
(1, 1, 3, 1, '{\"max_attempts\": 5}', '{\"confidence_threshold\": 0.8}', 'Special configuration for testing environment', '2024-08-01', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 4, 7, 1, '{\"rate_multiplier\": 2.0}', NULL, 'Higher overtime rate for manufacturing', '2024-07-01', NULL, 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE jobs (
  id bigint UNSIGNED NOT NULL,
  queue varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  payload longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  attempts tinyint UNSIGNED NOT NULL,
  reserved_at int UNSIGNED DEFAULT NULL,
  available_at int UNSIGNED NOT NULL,
  created_at int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE job_batches (
  id varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  total_jobs int NOT NULL,
  pending_jobs int NOT NULL,
  failed_jobs int NOT NULL,
  failed_job_ids longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  options mediumtext COLLATE utf8mb4_unicode_ci,
  cancelled_at int DEFAULT NULL,
  created_at int NOT NULL,
  finished_at int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) VALUES
('01HZ5K3M9G7QWERTYU12345ABC', 'Monthly Payroll Processing - September 2024', 25, 0, 1, '[1]', '{\"allowFailures\": false}', NULL, 1727616000, 1727619600),
('01HZ5K4N8H6POIUYTREW09876XYZ', 'Send Monthly Report Notifications', 30, 0, 2, '[2]', '{\"allowFailures\": true}', NULL, 1727702400, 1727706000);

CREATE TABLE `leaves` (
  id bigint UNSIGNED NOT NULL,
  user_id bigint UNSIGNED NOT NULL,
  leave_type enum('annual','sick','maternity','emergency','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  start_date date NOT NULL,
  end_date date NOT NULL,
  days_count int NOT NULL,
  reason text COLLATE utf8mb4_unicode_ci NOT NULL,
  attachment varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  status enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  approved_by bigint UNSIGNED DEFAULT NULL,
  approved_at timestamp NULL DEFAULT NULL,
  rejection_reason text COLLATE utf8mb4_unicode_ci,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO leaves (id, user_id, leave_type, start_date, end_date, days_count, reason, attachment, status, approved_by, approved_at, rejection_reason, created_at, updated_at) VALUES
(1, 6, 'annual', '2024-10-15', '2024-10-17', 3, 'Liburan keluarga ke Bali', NULL, 'approved', 2, '2025-09-20 11:02:53', NULL, '2025-09-15 11:02:53', '2025-09-22 11:02:53'),
(2, 7, 'sick', '2024-09-20', '2024-09-21', 2, 'Demam dan flu', NULL, 'approved', 2, '2025-09-07 11:02:53', NULL, '2025-09-02 11:02:53', '2025-09-22 11:02:53'),
(3, 14, 'annual', '2024-11-01', '2024-11-03', 3, 'Acara pernikahan saudara', NULL, 'pending', NULL, NULL, NULL, '2025-09-19 11:02:53', '2025-09-22 11:02:53'),
(4, 19, 'emergency', '2024-09-25', '2024-09-25', 1, 'Anak sakit mendadak', NULL, 'approved', 4, '2025-09-12 11:02:53', NULL, '2025-09-10 11:02:53', '2025-09-22 11:02:53'),
(5, 23, 'annual', '2024-10-20', '2024-10-22', 3, 'Mudik lebaran haji', NULL, 'rejected', 5, '2025-09-17 11:02:53', NULL, '2025-09-14 11:02:53', '2025-09-22 11:02:53'),
(6, 24, 'sick', '2024-10-01', '2024-10-02', 2, 'Sakit punggung', NULL, 'approved', 5, '2025-09-04 11:02:53', NULL, '2025-09-02 11:02:53', '2025-09-22 11:02:53'),
(7, 8, 'annual', '2024-11-10', '2024-11-12', 3, 'Family vacation', NULL, 'pending', NULL, NULL, NULL, '2025-09-20 11:02:53', '2025-09-22 11:02:53'),
(8, 9, 'sick', '2024-09-28', '2024-09-28', 1, 'Migraine headache', NULL, 'approved', 2, '2025-09-25 11:02:53', NULL, '2025-09-25 11:02:53', '2025-09-22 11:02:53'),
(9, 15, 'annual', '2024-12-23', '2024-12-25', 3, 'Christmas holiday', NULL, 'approved', 3, '2025-09-18 11:02:53', NULL, '2025-09-16 11:02:53', '2025-09-22 11:02:53'),
(10, 16, 'maternity', '2024-11-15', '2024-12-15', 30, 'Maternity leave', NULL, 'approved', 3, '2025-09-10 11:02:53', NULL, '2025-09-08 11:02:53', '2025-09-22 11:02:53'),
(11, 20, 'sick', '2024-10-05', '2024-10-06', 2, 'Food poisoning', NULL, 'approved', 4, '2025-09-22 11:02:53', NULL, '2025-09-20 11:02:53', '2025-09-22 11:02:53'),
(12, 25, 'emergency', '2024-09-30', '2024-09-30', 1, 'Family emergency', NULL, 'approved', 5, '2025-09-28 11:02:53', NULL, '2025-09-28 11:02:53', '2025-09-22 11:02:53'),
(13, 12, 'annual', '2024-11-25', '2024-11-27', 3, 'Extended weekend vacation', NULL, 'pending', NULL, NULL, NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(14, 17, 'sick', '2024-10-10', '2024-10-11', 2, 'Chronic back pain', 'medical_certificate.pdf', 'approved', 3, '2025-09-22 08:00:53', NULL, '2025-09-22 07:00:53', '2025-09-22 11:02:53'),
(15, 26, 'emergency', '2024-10-15', '2024-10-15', 1, 'Child hospitalized', NULL, 'approved', 5, '2025-09-22 06:00:53', NULL, '2025-09-22 05:30:53', '2025-09-22 11:02:53'),
(16, 11, 'annual', '2024-12-20', '2024-12-24', 5, 'Year-end holiday', NULL, 'pending', NULL, NULL, NULL, '2025-09-23 11:02:53', '2025-09-23 11:02:53');

CREATE TABLE migrations (
  id int UNSIGNED NOT NULL,
  migration varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  batch int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO migrations (id, migration, batch) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_21_090716_create_packages_table', 1),
(5, '2025_09_21_090717_create_companies_table', 1),
(6, '2025_09_21_090718_create_branches_table', 1),
(7, '2025_09_21_090719_create_attendance_policies_table', 1),
(8, '2025_09_21_090719_create_employee_schedules_table', 1),
(9, '2025_09_21_090720_create_attendances_table', 1),
(10, '2025_09_21_090721_create_leaves_table', 1),
(11, '2025_09_21_090722_create_payrolls_table', 1),
(12, '2025_09_21_090722_create_subscription_history_table', 1),
(13, '2025_09_21_090723_create_qr_codes_table', 1),
(14, '2025_09_21_090724_create_company_themes_table', 1),
(15, '2025_09_21_090724_create_notifications_table', 1),
(16, '2025_09_21_101018_add_additional_columns_to_users_table', 1),
(17, '2025_09_21_143227_create_instansis_table', 1),
(18, '2025_09_21_143235_create_subscriptions_table', 1),
(19, '2025_09_21_143246_create_employees_table', 1),
(20, '2025_09_21_143254_create_settings_table', 1),
(21, '2025_09_21_144455_add_role_to_users_table', 1),
(22, '2025_09_21_154818_add_deleted_at_to_instansi_table', 1),
(23, '2025_09_25_000001_create_package_change_requests_table', 2),
(24, '2025_09_25_000002_create_subscription_transitions_table', 2),
(25, '2025_09_25_000004_create_features_table', 3),
(26, '2025_09_25_000005_create_package_features_table', 3);

CREATE TABLE notifications (
  id bigint UNSIGNED NOT NULL,
  user_id bigint UNSIGNED NOT NULL,
  title varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  message text COLLATE utf8mb4_unicode_ci NOT NULL,
  type varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  is_read tinyint(1) NOT NULL DEFAULT '0',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO notifications (id, user_id, title, message, type, is_read, created_at, updated_at) VALUES
(1, 6, 'Selamat Datang!', 'Selamat datang di sistem HagaPlus. Jangan lupa untuk selalu absen tepat waktu.', 'info', 1, '2025-08-23 11:02:53', '2025-09-22 11:02:53'),
(2, 6, 'Pengajuan Cuti Disetujui', 'Pengajuan cuti Anda untuk tanggal 15-17 Oktober 2024 telah disetujui.', 'success', 1, '2025-09-20 11:02:53', '2025-09-22 11:02:53'),
(3, 7, 'Reminder: Absen Hari Ini', 'Jangan lupa untuk melakukan absen masuk hari ini.', 'warning', 0, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 14, 'Pengajuan Cuti Menunggu Persetujuan', 'Pengajuan cuti Anda sedang dalam proses review oleh atasan.', 'info', 0, '2025-09-19 11:02:53', '2025-09-22 11:02:53'),
(5, 19, 'Gaji Bulan September Sudah Dibayar', 'Gaji bulan September 2024 sebesar Rp 4,357,000 sudah ditransfer ke rekening Anda.', 'success', 1, '2025-09-17 11:02:53', '2025-09-22 11:02:53'),
(6, 23, 'Pengajuan Cuti Ditolak', 'Pengajuan cuti Anda untuk tanggal 20-22 Oktober 2024 ditolak karena sedang masa peak production.', 'error', 0, '2025-09-17 11:02:53', '2025-09-22 11:02:53'),
(7, 2, 'Karyawan Baru Terdaftar', 'Ada 3 karyawan baru yang telah terdaftar minggu ini.', 'info', 1, '2025-09-15 11:02:53', '2025-09-22 11:02:53'),
(8, 2, 'Laporan Absensi Bulanan Siap', 'Laporan absensi bulan September 2024 sudah siap untuk didownload.', 'success', 0, '2025-09-21 11:02:53', '2025-09-22 11:02:53'),
(9, 3, 'Subscription Akan Berakhir', 'Paket Business Anda akan berakhir pada tanggal 14 November 2024.', 'warning', 0, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(10, 4, 'Ada Pengajuan Cuti Pending', 'Terdapat 1 pengajuan cuti yang menunggu persetujuan Anda.', 'warning', 0, '2025-09-21 23:02:53', '2025-09-22 11:02:53'),
(11, 5, 'Pembayaran Gaji Berhasil', 'Pembayaran gaji bulan September untuk 8 karyawan telah berhasil diproses.', 'success', 1, '2025-09-17 11:02:53', '2025-09-22 11:02:53'),
(12, 1, 'Instansi Baru Terdaftar', 'Ada 2 instansi baru yang mendaftar minggu ini.', 'info', 1, '2025-09-15 11:02:53', '2025-09-22 11:02:53'),
(13, 1, 'Subscription Berakhir', 'Subscription untuk Klinik Sehat Sentosa telah berakhir.', 'warning', 0, '2025-08-23 11:02:53', '2025-09-22 11:02:53'),
(14, 8, 'Selamat Datang!', 'Selamat bergabung dengan PT Teknologi Maju Bersama. Silakan lengkapi profil Anda.', 'info', 1, '2022-11-10 11:02:53', '2025-09-22 11:02:53'),
(15, 14, 'Sistem Update', 'Sistem akan mengalami maintenance pada hari Minggu, 29 September 2024 pukul 02:00-04:00 WIB.', 'warning', 0, '2025-09-26 11:02:53', '2025-09-22 11:02:53'),
(16, 15, 'Pelatihan Face Recognition', 'Anda diundang untuk mengikuti pelatihan penggunaan sistem Face Recognition pada 5 Oktober 2024.', 'info', 0, '2025-09-25 11:02:53', '2025-09-22 11:02:53'),
(17, 16, 'Cuti Hamil Disetujui', 'Pengajuan cuti hamil Anda mulai 15 November 2024 telah disetujui.', 'success', 1, '2025-09-10 11:02:53', '2025-09-22 11:02:53'),
(18, 20, 'Reminder Absen', 'Anda belum melakukan absen keluar hari ini. Jangan lupa check out sebelum pulang.', 'warning', 0, '2025-09-22 16:30:53', '2025-09-22 11:02:53'),
(19, 22, 'Laporan Inventory', 'Laporan inventory mingguan sudah tersedia untuk didownload.', 'info', 1, '2025-09-20 11:02:53', '2025-09-22 11:02:53'),
(20, 25, 'Safety Training', 'Anda terjadwal mengikuti safety training pada 1 Oktober 2024 di ruang training.', 'info', 0, '2025-09-23 11:02:53', '2025-09-22 11:02:53'),
(21, 27, 'Inspeksi K3', 'Inspeksi K3 bulanan akan dilakukan besok. Pastikan semua area sudah sesuai standar.', 'warning', 0, '2025-09-24 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE packages (
  id bigint UNSIGNED NOT NULL,
  name varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  description text COLLATE utf8mb4_unicode_ci,
  price decimal(15,2) NOT NULL,
  duration_days int NOT NULL DEFAULT '30',
  max_employees int NOT NULL DEFAULT '10',
  max_branches int NOT NULL DEFAULT '1',
  features json NOT NULL,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO packages (id, name, description, price, duration_days, max_employees, max_branches, features, is_active, created_at, updated_at) VALUES
(1, 'Starter', 'Paket dasar untuk usaha kecil dengan fitur absen QR Code dan GPS', 49000.00, 30, 10, 1, '[\"qr\", \"gps\", \"basic_reports\"]', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 'Business', 'Paket lengkap untuk usaha menengah dengan multi cabang dan Face ID', 149000.00, 30, 50, 5, '[\"qr\", \"gps\", \"face_id\", \"shift_management\", \"leave_management\", \"payroll\"]', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 'Enterprise', 'Paket premium untuk perusahaan besar dengan fitur lengkap', 299000.00, 30, 200, 20, '[\"qr\", \"gps\", \"face_id\", \"shift_management\", \"leave_management\", \"payroll\", \"advanced_reports\", \"api_access\"]', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 'Corporate', 'Solusi enterprise dengan unlimited features', 599000.00, 30, 1000, 50, '[\"all_features\", \"unlimited_employees\", \"custom_integration\", \"dedicated_support\"]', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE package_change_requests (
  id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  current_package_id bigint UNSIGNED NOT NULL,
  requested_package_id bigint UNSIGNED NOT NULL,
  type enum('upgrade','downgrade') COLLATE utf8mb4_unicode_ci NOT NULL,
  status enum('pending','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  requested_effective_date date NOT NULL,
  prorate_amount decimal(15,2) NOT NULL DEFAULT '0.00',
  reason text COLLATE utf8mb4_unicode_ci,
  admin_notes text COLLATE utf8mb4_unicode_ci,
  requested_by bigint UNSIGNED NOT NULL,
  approved_by bigint UNSIGNED DEFAULT NULL,
  approved_at timestamp NULL DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO package_change_requests (id, instansi_id, current_package_id, requested_package_id, type, status, requested_effective_date, prorate_amount, reason, admin_notes, requested_by, approved_by, approved_at, created_at, updated_at) VALUES
(1, 2, 2, 3, 'upgrade', 'approved', '2024-10-01', 150000.00, 'Need more employees and advanced reporting features', 'Approved due to business growth', 3, 1, '2025-09-20 11:02:53', '2025-09-18 11:02:53', '2025-09-22 11:02:53'),
(2, 3, 1, 2, 'upgrade', 'pending', '2024-11-01', 100000.00, 'Expanding to multiple locations', NULL, 4, NULL, NULL, '2025-09-21 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE package_features (
  id bigint UNSIGNED NOT NULL,
  package_id bigint UNSIGNED NOT NULL,
  feature_id bigint UNSIGNED NOT NULL,
  is_enabled tinyint(1) NOT NULL DEFAULT '1',
  limits json DEFAULT NULL,
  config_override json DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO package_features (id, package_id, feature_id, is_enabled, limits, config_override, created_at, updated_at) VALUES
(1, 1, 1, 1, '{\"max_employees\": 10}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 1, 2, 1, '{\"max_employees\": 10}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 1, 8, 1, '{\"exports_per_month\": 10}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 2, 1, 1, '{\"max_employees\": 50}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 2, 2, 1, '{\"max_employees\": 50}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(6, 2, 3, 1, '{\"max_employees\": 50}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(7, 2, 4, 1, '{\"max_shifts\": 5}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(8, 2, 5, 1, '{\"approval_levels\": 2}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(9, 2, 6, 1, '{\"auto_processing\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(10, 2, 8, 1, '{\"exports_per_month\": 50}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(11, 2, 12, 1, '{\"max_branches\": 5}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(12, 3, 1, 1, '{\"max_employees\": 200}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(13, 3, 2, 1, '{\"max_employees\": 200}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(14, 3, 3, 1, '{\"max_employees\": 200}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(15, 3, 4, 1, '{\"max_shifts\": 10}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(16, 3, 5, 1, '{\"approval_levels\": 3}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(17, 3, 6, 1, '{\"auto_processing\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(18, 3, 7, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(19, 3, 8, 1, '{\"exports_per_month\": 200}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(20, 3, 9, 1, '{\"custom_dashboards\": 5}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(21, 3, 10, 1, '{\"requests_per_hour\": 5000}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(22, 3, 12, 1, '{\"max_branches\": 20}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(23, 4, 1, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(24, 4, 2, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(25, 4, 3, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(26, 4, 4, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(27, 4, 5, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(28, 4, 6, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(29, 4, 7, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(30, 4, 8, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(31, 4, 9, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(32, 4, 10, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(33, 4, 11, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(34, 4, 12, 1, '{\"unlimited\": true}', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE password_reset_tokens (
  email varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  token varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE payrolls (
  id bigint UNSIGNED NOT NULL,
  user_id bigint UNSIGNED NOT NULL,
  period_year int NOT NULL,
  period_month int NOT NULL,
  basic_salary decimal(15,2) NOT NULL,
  allowances json DEFAULT NULL,
  deductions json DEFAULT NULL,
  overtime_amount decimal(15,2) NOT NULL DEFAULT '0.00',
  total_gross decimal(15,2) NOT NULL,
  total_deductions decimal(15,2) NOT NULL DEFAULT '0.00',
  net_salary decimal(15,2) NOT NULL,
  payment_date date DEFAULT NULL,
  payment_status enum('draft','processed','paid') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  notes text COLLATE utf8mb4_unicode_ci,
  created_by bigint UNSIGNED DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO payrolls (id, user_id, period_year, period_month, basic_salary, allowances, deductions, overtime_amount, total_gross, total_deductions, net_salary, payment_date, payment_status, notes, created_by, created_at, updated_at) VALUES
(1, 6, 2024, 9, 8500000.00, '{\"meal\": 300000, \"transport\": 500000, \"performance\": 850000}', '{\"tax\": 425000, \"bpjs_kesehatan\": 85000, \"bpjs_ketenagakerjaan\": 170000}', 450000.00, 10600000.00, 680000.00, 9920000.00, '2024-10-05', 'paid', NULL, 2, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 7, 2024, 9, 7500000.00, '{\"meal\": 300000, \"transport\": 500000, \"performance\": 750000}', '{\"tax\": 375000, \"bpjs_kesehatan\": 75000, \"bpjs_ketenagakerjaan\": 150000}', 380000.00, 9430000.00, 600000.00, 8830000.00, '2024-10-05', 'paid', NULL, 2, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 8, 2024, 9, 12000000.00, '{\"meal\": 300000, \"transport\": 500000, \"management\": 500000, \"performance\": 1200000}', '{\"tax\": 600000, \"bpjs_kesehatan\": 120000, \"bpjs_ketenagakerjaan\": 240000}', 520000.00, 15020000.00, 960000.00, 14060000.00, '2024-10-05', 'paid', NULL, 2, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 14, 2024, 9, 7000000.00, '{\"internet\": 150000, \"creativity\": 400000, \"performance\": 700000}', '{\"tax\": 350000, \"bpjs_kesehatan\": 70000, \"bpjs_ketenagakerjaan\": 140000}', 320000.00, 8570000.00, 560000.00, 8010000.00, '2024-10-05', 'paid', NULL, 3, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 15, 2024, 9, 6200000.00, '{\"internet\": 150000, \"creativity\": 400000, \"performance\": 620000}', '{\"tax\": 310000, \"bpjs_kesehatan\": 62000, \"bpjs_ketenagakerjaan\": 124000}', 280000.00, 7650000.00, 496000.00, 7154000.00, '2024-10-05', 'paid', NULL, 3, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(6, 19, 2024, 9, 4200000.00, '{\"attendance\": 200000, \"sales_incentive\": 84000}', '{\"tax\": 210000, \"employee_fund\": 25000, \"bpjs_kesehatan\": 42000}', 150000.00, 4634000.00, 277000.00, 4357000.00, '2024-10-05', 'paid', NULL, 4, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(7, 20, 2024, 9, 3800000.00, '{\"attendance\": 200000}', '{\"tax\": 190000, \"employee_fund\": 25000, \"bpjs_kesehatan\": 38000}', 120000.00, 4120000.00, 253000.00, 3867000.00, '2024-10-05', 'paid', NULL, 4, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(8, 23, 2024, 9, 5200000.00, '{\"shift\": 300000, \"safety\": 250000}', '{\"tax\": 260000, \"cooperative\": 100000, \"bpjs_kesehatan\": 52000, \"bpjs_ketenagakerjaan\": 104000}', 200000.00, 5950000.00, 516000.00, 5434000.00, '2024-10-05', 'paid', NULL, 5, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(9, 24, 2024, 9, 6800000.00, '{\"shift\": 300000, \"safety\": 250000, \"quality_bonus\": 340000}', '{\"tax\": 340000, \"cooperative\": 100000, \"bpjs_kesehatan\": 68000, \"bpjs_ketenagakerjaan\": 136000}', 280000.00, 8970000.00, 644000.00, 8326000.00, '2024-10-05', 'paid', NULL, 5, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(10, 28, 2024, 9, 4800000.00, '{\"shift\": 300000, \"safety\": 250000}', '{\"tax\": 240000, \"cooperative\": 100000, \"bpjs_kesehatan\": 48000, \"bpjs_ketenagakerjaan\": 96000}', 180000.00, 5530000.00, 484000.00, 5046000.00, '2024-10-05', 'paid', NULL, 5, '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE qr_codes (
  id bigint UNSIGNED NOT NULL,
  branch_id bigint UNSIGNED NOT NULL,
  code varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  expires_at timestamp NOT NULL,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO qr_codes (id, branch_id, code, expires_at, is_active, created_at, updated_at) VALUES
(1, 1, 'QR_BRANCH_1_ABC123DEF456', '2025-09-23 11:02:53', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 2, 'QR_BRANCH_2_GHI789JKL012', '2025-09-23 11:02:53', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 3, 'QR_BRANCH_3_MNO345PQR678', '2025-09-23 11:02:53', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 4, 'QR_BRANCH_4_STU901VWX234', '2025-09-23 11:02:53', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 5, 'QR_BRANCH_5_YZA567BCD890', '2025-09-23 11:02:53', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(6, 6, 'QR_BRANCH_6_EFG123HIJ456', '2025-09-23 11:02:53', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(7, 7, 'QR_BRANCH_7_KLM789NOP012', '2025-09-23 11:02:53', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(8, 8, 'QR_BRANCH_8_QRS345TUV678', '2025-09-23 11:02:53', 1, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(9, 1, 'QR_BRANCH_1_XYZ789ABC123', '2025-09-24 11:02:53', 0, '2025-09-21 11:02:53', '2025-09-23 11:02:53'),
(10, 2, 'QR_BRANCH_2_DEF456GHI789', '2025-09-24 11:02:53', 0, '2025-09-21 11:02:53', '2025-09-23 11:02:53'),
(11, 4, 'QR_BRANCH_4_JKL012MNO345', '2025-09-24 11:02:53', 1, '2025-09-23 11:02:53', '2025-09-23 11:02:53'),
(12, 6, 'QR_BRANCH_6_PQR678STU901', '2025-09-24 11:02:53', 1, '2025-09-23 11:02:53', '2025-09-23 11:02:53');

CREATE TABLE sessions (
  id varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  user_id bigint UNSIGNED DEFAULT NULL,
  ip_address varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_agent text COLLATE utf8mb4_unicode_ci,
  payload longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  last_activity int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO sessions (id, user_id, ip_address, user_agent, payload, last_activity) VALUES
('7Skk4fgi3WPdX9cHHJrdtJwfN77UO6dvPeN9VTZs', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVzEwekY3d2ZiWjFtVVN0YmZvVTlXWVZhNDBKbDR0OGVlOE5zbkx5TCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdXBlcmFkbWluL2luc3RhbnNpIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1758695368),
('d5oxNca79KbWfa6O9rVhJh9WFNw9g8FsUvF1k0d1', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTjlaVEhKdTlYeHV6bTZLU1lTSDh0cFQwNXI3eW5tOGVZVWdOV3ZKMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdXBlcmFkbWluL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1758689066),
('fnERGfgBw48PYX1VOCXTuMb58PilwHHEvR7VqXP6', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; id-ID) WindowsPowerShell/5.1.19041.3031', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUTFnMG5hNFBvcDNyVUNqYXJ0OGVqY0IxYnQzU1doOXppUlc4b2psayI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3N1cGVyYWRtaW4vZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758691128),
('LFIf4rXc8zmjDrC6RZ6vVtTlhHHfFsbtPlhqteI8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; id-ID) WindowsPowerShell/5.1.19041.3031', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUdoY2ZpRXc3NkdUUDExQzZqc2ppc0c0ZzBlOEoxTnlnVUYxWXBlYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758691392),
('lYflzRE8CGKYH7D4pzedfKygxvMRuftG2oePNx7s', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; id-ID) WindowsPowerShell/5.1.19041.3031', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNzM1aXo1b1pKeGg2elpmazhkMTdGNjY3RGZVOUppOWFPMlhQSnFjWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758691121),
('sOYU3Polv3KJsJ6iWJj5AJIMPxoUyGFqRagObnJz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; id-ID) WindowsPowerShell/5.1.19041.3031', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidHpIeXJBS2NuaWpRa1A5ZVZqeUEyaWxBNFlhZGVrVmt0WTJqRWNESyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758690886),
('TPAAFen2OtOk1izKJpxuElKUbQyvRLpNoyACQoHA', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM3FNbUdTcWhjMVY0N1M3SWtvR090NXRmM1dId3c3QzA0T0VOWFo4ZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly9sb2NhbGhvc3QvaGFnYXBsdXMvcHVibGljL3N1cGVyYWRtaW4vaW5zdGFuc2kiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1758682493),
('xl2eAkHPnmxVzAiVwJRQG3c8U8quwXIFQ5mTsTGC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; id-ID) WindowsPowerShell/5.1.19041.3031', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSFc4SDFYNG9SbXJHWHFiWFF2b0lRT1NQYWRVTnVIblVhS0NtTHNRbiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3N1cGVyYWRtaW4vZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1758691361);

CREATE TABLE settings (
  id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  value text COLLATE utf8mb4_unicode_ci NOT NULL,
  type varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings (id, instansi_id, `key`, value, type, created_at, updated_at) VALUES
(1, 1, 'company_name', 'PT Teknologi Maju Bersama', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 1, 'working_hours_start', '09:00', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 1, 'working_hours_end', '18:00', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 1, 'late_tolerance', '15', 'integer', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 1, 'overtime_enabled', 'true', 'boolean', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(6, 1, 'face_recognition_enabled', 'true', 'boolean', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(7, 2, 'company_name', 'CV Kreatif Digital Solutions', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(8, 2, 'working_hours_start', '10:00', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(9, 2, 'working_hours_end', '19:00', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(10, 2, 'late_tolerance', '20', 'integer', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(11, 2, 'flexible_working', 'true', 'boolean', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(12, 3, 'company_name', 'Toko Berkah Jaya', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(13, 3, 'working_hours_start', '08:00', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(14, 3, 'working_hours_end', '17:00', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(15, 3, 'late_tolerance', '10', 'integer', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(16, 3, 'auto_checkout', 'true', 'boolean', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(17, 4, 'company_name', 'PT Industri Manufaktur Nusantara', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(18, 4, 'shift_system', 'true', 'boolean', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(19, 4, 'safety_protocol_enabled', 'true', 'boolean', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(20, 4, 'late_tolerance', '5', 'integer', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(21, 1, 'notification_email', 'admin@teknologimaju.com', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(22, 1, 'logo_url', '/storage/logos/teknologi_maju_logo.png', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(23, 1, 'primary_color', '#3b82f6', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(24, 1, 'payroll_cutoff_date', '25', 'integer', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(25, 2, 'notification_email', 'hr@kreatifdigital.com', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(26, 2, 'logo_url', '/storage/logos/kreatif_digital_logo.png', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(27, 2, 'primary_color', '#8b5cf6', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(28, 3, 'notification_email', 'owner@berkahjaya.com', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(29, 3, 'weekend_work', 'true', 'boolean', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(30, 4, 'notification_email', 'hrd@manufakturnusantara.co.id', 'string', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(31, 4, 'multiple_shifts', 'true', 'boolean', '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(32, 4, 'overtime_multiplier', '1.5', 'decimal', '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE subscriptions (
  id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  package_id bigint UNSIGNED NOT NULL,
  status enum('active','inactive','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  start_date date NOT NULL,
  end_date date NOT NULL,
  price decimal(10,2) NOT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO subscriptions (id, instansi_id, package_id, status, start_date, end_date, price, created_at, updated_at) VALUES
(1, 1, 3, 'active', '2024-08-01', '2024-10-31', 299000.00, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(2, 2, 2, 'active', '2024-08-15', '2024-11-14', 149000.00, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(3, 3, 1, 'active', '2024-09-01', '2024-10-01', 49000.00, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(4, 4, 4, 'active', '2024-07-01', '2024-10-31', 599000.00, '2025-09-22 11:02:53', '2025-09-22 11:02:53'),
(5, 5, 1, 'expired', '2024-06-01', '2024-07-01', 49000.00, '2025-09-22 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE subscription_history (
  id bigint UNSIGNED NOT NULL,
  company_id bigint UNSIGNED NOT NULL,
  package_id bigint UNSIGNED NOT NULL,
  start_date datetime NOT NULL,
  end_date datetime NOT NULL,
  amount decimal(15,2) NOT NULL,
  payment_method varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  payment_status enum('pending','paid','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  transaction_id varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  notes text COLLATE utf8mb4_unicode_ci,
  created_by bigint UNSIGNED DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO subscription_history (id, company_id, package_id, start_date, end_date, amount, payment_method, payment_status, transaction_id, notes, created_by, created_at, updated_at) VALUES
(1, 1, 3, '2024-08-01 00:00:00', '2024-10-31 23:59:59', 299000.00, 'Bank Transfer', 'paid', 'TXN-TMB-240801-001', 'Pembayaran paket Enterprise untuk 3 bulan', 1, '2024-08-01 10:00:00', '2025-09-22 11:02:53'),
(2, 2, 2, '2024-08-15 00:00:00', '2024-11-14 23:59:59', 149000.00, 'Bank Transfer', 'paid', 'TXN-KDS-240815-001', 'Pembayaran paket Business untuk 3 bulan', 1, '2024-08-15 14:30:00', '2025-09-22 11:02:53'),
(3, 3, 1, '2024-09-01 00:00:00', '2024-10-01 23:59:59', 49000.00, 'Cash', 'paid', 'TXN-BJ-240901-001', 'Pembayaran paket Starter untuk 1 bulan', 1, '2024-09-01 09:15:00', '2025-09-22 11:02:53'),
(4, 4, 4, '2024-07-01 00:00:00', '2024-10-31 23:59:59', 599000.00, 'Bank Transfer', 'paid', 'TXN-IMN-240701-001', 'Pembayaran paket Corporate untuk 4 bulan', 1, '2024-07-01 11:45:00', '2025-09-22 11:02:53');

CREATE TABLE subscription_transitions (
  id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  from_package_id bigint UNSIGNED NOT NULL,
  to_package_id bigint UNSIGNED NOT NULL,
  subscription_id bigint UNSIGNED NOT NULL,
  transition_type enum('upgrade','downgrade','renewal','new') COLLATE utf8mb4_unicode_ci NOT NULL,
  effective_from datetime NOT NULL,
  effective_until datetime DEFAULT NULL,
  transition_amount decimal(15,2) NOT NULL,
  prorate_credit decimal(15,2) NOT NULL DEFAULT '0.00',
  feature_changes json DEFAULT NULL,
  notes text COLLATE utf8mb4_unicode_ci,
  processed_by bigint UNSIGNED NOT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO subscription_transitions (id, instansi_id, from_package_id, to_package_id, subscription_id, transition_type, effective_from, effective_until, transition_amount, prorate_credit, feature_changes, notes, processed_by, created_at, updated_at) VALUES
(1, 2, 2, 3, 2, 'upgrade', '2024-10-01 00:00:00', '2024-11-14 23:59:59', 150000.00, 0.00, '{\"added_features\": [\"advanced_reports\", \"api_access\"], \"increased_limits\": {\"max_branches\": 20, \"max_employees\": 200}}', 'Upgrade from Business to Enterprise', 1, '2025-09-20 11:02:53', '2025-09-22 11:02:53');

CREATE TABLE users (
  id bigint UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  email varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  email_verified_at timestamp NULL DEFAULT NULL,
  password varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  remember_token varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  role enum('superadmin','admin','employee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  instansi_id bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role, instansi_id) VALUES
(1, 'Super Administrator', 'superadmin@hagaplus.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'superadmin', NULL),
(2, 'Budi Santoso', 'admin@teknologimaju.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'admin', 1),
(3, 'Sari Dewi Lestari', 'hr@kreatifdigital.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'admin', 2),
(4, 'Ahmad Wijaya', 'owner@berkahjaya.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'admin', 3),
(5, 'Diana Permata Sari', 'hrd@manufakturnusantara.co.id', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'admin', 4),
(6, 'Andi Pratama', 'andi.pratama@teknologimaju.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 1),
(7, 'Siti Nurhaliza', 'siti.nurhaliza@teknologimaju.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 1),
(8, 'Dedi Kurniawan', 'dedi.kurniawan@teknologimaju.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 1),
(9, 'Maya Sari', 'maya.sari@teknologimaju.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 1),
(10, 'Rizky Fauzan', 'rizky.fauzan@teknologimaju.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 1),
(11, 'Indah Permatasari', 'indah.permatasari@teknologimaju.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 1),
(12, 'Bayu Setiawan', 'bayu.setiawan@teknologimaju.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 1),
(13, 'Putri Maharani', 'putri.maharani@teknologimaju.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 1),
(14, 'Yoga Pratama', 'yoga.pratama@kreatifdigital.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 2),
(15, 'Lina Marlina', 'lina.marlina@kreatifdigital.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 2),
(16, 'Farhan Maulana', 'farhan.maulana@kreatifdigital.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 2),
(17, 'Rika Amelia', 'rika.amelia@kreatifdigital.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 2),
(18, 'Galih Prasetyo', 'galih.prasetyo@kreatifdigital.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 2),
(19, 'Wati Suryani', 'wati.suryani@berkahjaya.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 3),
(20, 'Joko Susanto', 'joko.susanto@berkahjaya.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 3),
(21, 'Ani Rahayu', 'ani.rahayu@berkahjaya.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 3),
(22, 'Bambang Sutrisno', 'bambang.sutrisno@berkahjaya.com', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 3),
(23, 'Hendra Gunawan', 'hendra.gunawan@manufakturnusantara.co.id', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 4),
(24, 'Sri Mulyani', 'sri.mulyani@manufakturnusantara.co.id', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 4),
(25, 'Agus Salim', 'agus.salim@manufakturnusantara.co.id', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 4),
(26, 'Dewi Sartika', 'dewi.sartika@manufakturnusantara.co.id', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 4),
(27, 'Rudi Hartono', 'rudi.hartono@manufakturnusantara.co.id', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 4),
(28, 'Sari Wulandari', 'sari.wulandari@manufakturnusantara.co.id', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 4),
(29, 'Eko Prasetyo', 'eko.prasetyo@manufakturnusantara.co.id', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 4),
(30, 'Fitri Handayani', 'fitri.handayani@manufakturnusantara.co.id', NULL, '$2y$12$APaCMILfwsR2nY/vbgfSluT83pkG7NhSlscD3ofReCxr36QhFOMPq', NULL, '2025-09-22 11:02:53', '2025-09-22 11:02:53', 'employee', 4);


ALTER TABLE activity_logs
  ADD PRIMARY KEY (id),
  ADD KEY activity_logs_subject_type_subject_id_index (subject_type,subject_id),
  ADD KEY activity_logs_causer_type_causer_id_index (causer_type,causer_id),
  ADD KEY activity_logs_instansi_id_foreign (instansi_id);

ALTER TABLE attendances
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY attendances_user_id_attendance_date_unique (user_id,attendance_date),
  ADD KEY attendances_approved_by_foreign (approved_by),
  ADD KEY attendances_user_id_attendance_date_index (user_id,attendance_date),
  ADD KEY attendances_branch_id_index (branch_id),
  ADD KEY attendances_attendance_date_index (attendance_date);

ALTER TABLE attendance_policies
  ADD PRIMARY KEY (id),
  ADD KEY attendance_policies_company_id_foreign (company_id);

ALTER TABLE branches
  ADD PRIMARY KEY (id),
  ADD KEY branches_company_id_foreign (company_id);

ALTER TABLE cache
  ADD PRIMARY KEY (`key`);

ALTER TABLE cache_locks
  ADD PRIMARY KEY (`key`);

ALTER TABLE companies
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY companies_email_unique (email),
  ADD KEY companies_package_id_foreign (package_id);

ALTER TABLE company_themes
  ADD PRIMARY KEY (id),
  ADD KEY company_themes_company_id_foreign (company_id);

ALTER TABLE employees
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY employees_employee_id_unique (employee_id),
  ADD KEY employees_user_id_foreign (user_id),
  ADD KEY employees_instansi_id_foreign (instansi_id),
  ADD KEY employees_branch_id_foreign (branch_id);

ALTER TABLE employee_schedules
  ADD PRIMARY KEY (id),
  ADD KEY employee_schedules_user_id_foreign (user_id),
  ADD KEY employee_schedules_policy_id_foreign (policy_id);

ALTER TABLE failed_jobs
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY failed_jobs_uuid_unique (uuid);

ALTER TABLE features
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY features_slug_unique (slug),
  ADD KEY features_category_is_active_index (category,is_active);

ALTER TABLE instansis
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY instansis_subdomain_unique (subdomain);

ALTER TABLE instansi_feature_overrides
  ADD PRIMARY KEY (id),
  ADD KEY instansi_feature_overrides_instansi_id_foreign (instansi_id),
  ADD KEY instansi_feature_overrides_feature_id_foreign (feature_id),
  ADD KEY instansi_feature_overrides_applied_by_foreign (applied_by);

ALTER TABLE jobs
  ADD PRIMARY KEY (id),
  ADD KEY jobs_queue_index (queue);

ALTER TABLE job_batches
  ADD PRIMARY KEY (id);

ALTER TABLE leaves
  ADD PRIMARY KEY (id),
  ADD KEY leaves_approved_by_foreign (approved_by),
  ADD KEY leaves_user_id_index (user_id),
  ADD KEY leaves_start_date_end_date_index (start_date,end_date),
  ADD KEY leaves_status_index (status);

ALTER TABLE migrations
  ADD PRIMARY KEY (id);

ALTER TABLE notifications
  ADD PRIMARY KEY (id),
  ADD KEY notifications_user_id_foreign (user_id);

ALTER TABLE packages
  ADD PRIMARY KEY (id);

ALTER TABLE package_change_requests
  ADD PRIMARY KEY (id),
  ADD KEY package_change_requests_current_package_id_foreign (current_package_id),
  ADD KEY package_change_requests_requested_package_id_foreign (requested_package_id),
  ADD KEY package_change_requests_requested_by_foreign (requested_by),
  ADD KEY package_change_requests_approved_by_foreign (approved_by),
  ADD KEY package_change_requests_instansi_id_status_index (instansi_id,status),
  ADD KEY package_change_requests_requested_effective_date_index (requested_effective_date);

ALTER TABLE package_features
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY package_features_package_id_feature_id_unique (package_id,feature_id),
  ADD KEY package_features_feature_id_foreign (feature_id);

ALTER TABLE password_reset_tokens
  ADD PRIMARY KEY (email);

ALTER TABLE payrolls
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY payrolls_user_id_period_year_period_month_unique (user_id,period_year,period_month),
  ADD KEY payrolls_created_by_foreign (created_by),
  ADD KEY payrolls_period_year_period_month_index (period_year,period_month),
  ADD KEY payrolls_user_id_index (user_id);

ALTER TABLE qr_codes
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY qr_codes_code_unique (code),
  ADD KEY qr_codes_branch_id_foreign (branch_id);

ALTER TABLE sessions
  ADD PRIMARY KEY (id),
  ADD KEY sessions_user_id_index (user_id),
  ADD KEY sessions_last_activity_index (last_activity);

ALTER TABLE settings
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY settings_instansi_id_key_unique (instansi_id,`key`);

ALTER TABLE subscriptions
  ADD PRIMARY KEY (id),
  ADD KEY subscriptions_instansi_id_foreign (instansi_id),
  ADD KEY subscriptions_package_id_foreign (package_id);

ALTER TABLE subscription_history
  ADD PRIMARY KEY (id),
  ADD KEY subscription_history_company_id_foreign (company_id),
  ADD KEY subscription_history_package_id_foreign (package_id),
  ADD KEY subscription_history_created_by_foreign (created_by);

ALTER TABLE subscription_transitions
  ADD PRIMARY KEY (id),
  ADD KEY subscription_transitions_from_package_id_foreign (from_package_id),
  ADD KEY subscription_transitions_to_package_id_foreign (to_package_id),
  ADD KEY subscription_transitions_subscription_id_foreign (subscription_id),
  ADD KEY subscription_transitions_processed_by_foreign (processed_by),
  ADD KEY subscription_transitions_instansi_id_effective_from_index (instansi_id,effective_from),
  ADD KEY subscription_transitions_transition_type_index (transition_type);

ALTER TABLE users
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY users_email_unique (email),
  ADD KEY users_instansi_id_foreign (instansi_id);


ALTER TABLE activity_logs
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE attendances
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

ALTER TABLE attendance_policies
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE branches
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

ALTER TABLE companies
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE company_themes
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE employees
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

ALTER TABLE employee_schedules
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

ALTER TABLE failed_jobs
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE features
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE instansis
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE instansi_feature_overrides
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE jobs
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE leaves
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

ALTER TABLE migrations
  MODIFY id int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

ALTER TABLE notifications
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

ALTER TABLE packages
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE package_change_requests
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE package_features
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

ALTER TABLE payrolls
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

ALTER TABLE qr_codes
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE settings
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

ALTER TABLE subscriptions
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE subscription_history
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE subscription_transitions
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE users
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;


ALTER TABLE activity_logs
  ADD CONSTRAINT activity_logs_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE;

ALTER TABLE attendances
  ADD CONSTRAINT attendances_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT attendances_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES branches (id) ON DELETE CASCADE,
  ADD CONSTRAINT attendances_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE attendance_policies
  ADD CONSTRAINT attendance_policies_company_id_foreign FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE;

ALTER TABLE branches
  ADD CONSTRAINT branches_company_id_foreign FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE;

ALTER TABLE companies
  ADD CONSTRAINT companies_package_id_foreign FOREIGN KEY (package_id) REFERENCES packages (id) ON DELETE SET NULL;

ALTER TABLE company_themes
  ADD CONSTRAINT company_themes_company_id_foreign FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE;

ALTER TABLE employees
  ADD CONSTRAINT employees_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES branches (id) ON DELETE SET NULL,
  ADD CONSTRAINT employees_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE,
  ADD CONSTRAINT employees_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE employee_schedules
  ADD CONSTRAINT employee_schedules_policy_id_foreign FOREIGN KEY (policy_id) REFERENCES attendance_policies (id) ON DELETE CASCADE,
  ADD CONSTRAINT employee_schedules_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE instansi_feature_overrides
  ADD CONSTRAINT instansi_feature_overrides_applied_by_foreign FOREIGN KEY (applied_by) REFERENCES users (id) ON DELETE CASCADE,
  ADD CONSTRAINT instansi_feature_overrides_feature_id_foreign FOREIGN KEY (feature_id) REFERENCES features (id) ON DELETE CASCADE,
  ADD CONSTRAINT instansi_feature_overrides_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE;

ALTER TABLE leaves
  ADD CONSTRAINT leaves_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT leaves_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE notifications
  ADD CONSTRAINT notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE package_change_requests
  ADD CONSTRAINT package_change_requests_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT package_change_requests_current_package_id_foreign FOREIGN KEY (current_package_id) REFERENCES packages (id) ON DELETE CASCADE,
  ADD CONSTRAINT package_change_requests_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE,
  ADD CONSTRAINT package_change_requests_requested_by_foreign FOREIGN KEY (requested_by) REFERENCES users (id) ON DELETE CASCADE,
  ADD CONSTRAINT package_change_requests_requested_package_id_foreign FOREIGN KEY (requested_package_id) REFERENCES packages (id) ON DELETE CASCADE;

ALTER TABLE package_features
  ADD CONSTRAINT package_features_feature_id_foreign FOREIGN KEY (feature_id) REFERENCES features (id) ON DELETE CASCADE,
  ADD CONSTRAINT package_features_package_id_foreign FOREIGN KEY (package_id) REFERENCES packages (id) ON DELETE CASCADE;

ALTER TABLE payrolls
  ADD CONSTRAINT payrolls_created_by_foreign FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT payrolls_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE qr_codes
  ADD CONSTRAINT qr_codes_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES branches (id) ON DELETE CASCADE;

ALTER TABLE settings
  ADD CONSTRAINT settings_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE;

ALTER TABLE subscriptions
  ADD CONSTRAINT subscriptions_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE,
  ADD CONSTRAINT subscriptions_package_id_foreign FOREIGN KEY (package_id) REFERENCES packages (id) ON DELETE CASCADE;

ALTER TABLE subscription_history
  ADD CONSTRAINT subscription_history_company_id_foreign FOREIGN KEY (company_id) REFERENCES companies (id) ON DELETE CASCADE,
  ADD CONSTRAINT subscription_history_created_by_foreign FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT subscription_history_package_id_foreign FOREIGN KEY (package_id) REFERENCES packages (id) ON DELETE CASCADE;

ALTER TABLE subscription_transitions
  ADD CONSTRAINT subscription_transitions_from_package_id_foreign FOREIGN KEY (from_package_id) REFERENCES packages (id) ON DELETE CASCADE,
  ADD CONSTRAINT subscription_transitions_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE,
  ADD CONSTRAINT subscription_transitions_processed_by_foreign FOREIGN KEY (processed_by) REFERENCES users (id) ON DELETE CASCADE,
  ADD CONSTRAINT subscription_transitions_subscription_id_foreign FOREIGN KEY (subscription_id) REFERENCES subscriptions (id) ON DELETE CASCADE,
  ADD CONSTRAINT subscription_transitions_to_package_id_foreign FOREIGN KEY (to_package_id) REFERENCES packages (id) ON DELETE CASCADE;

ALTER TABLE users
  ADD CONSTRAINT users_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

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

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  value mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  expiration int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE failed_jobs (
  id bigint UNSIGNED NOT NULL,
  uuid varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  connection text COLLATE utf8mb4_unicode_ci NOT NULL,
  queue text COLLATE utf8mb4_unicode_ci NOT NULL,
  payload longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  exception longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  failed_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE instansis (
  id bigint UNSIGNED NOT NULL,
  nama_instansi varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  subdomain varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  status_langganan enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE migrations (
  id int UNSIGNED NOT NULL,
  migration varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  batch int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE qr_codes (
  id bigint UNSIGNED NOT NULL,
  branch_id bigint UNSIGNED NOT NULL,
  code varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  expires_at timestamp NOT NULL,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE sessions (
  id varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  user_id bigint UNSIGNED DEFAULT NULL,
  ip_address varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_agent text COLLATE utf8mb4_unicode_ci,
  payload longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  last_activity int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE settings (
  id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  value text COLLATE utf8mb4_unicode_ci NOT NULL,
  type varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE users (
  id bigint UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  email varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  email_verified_at timestamp NULL DEFAULT NULL,
  password varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  remember_token varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  company_id bigint UNSIGNED DEFAULT NULL,
  branch_id bigint UNSIGNED DEFAULT NULL,
  employee_id varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  phone varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  role varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  position varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  department varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  hire_date date DEFAULT NULL,
  salary decimal(10,2) DEFAULT NULL,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  instansi_id bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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

ALTER TABLE instansis
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY instansis_subdomain_unique (subdomain);

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

ALTER TABLE users
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY users_email_unique (email),
  ADD KEY users_instansi_id_foreign (instansi_id);


ALTER TABLE attendances
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE attendance_policies
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE branches
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE companies
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE company_themes
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE employees
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE employee_schedules
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE failed_jobs
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE instansis
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE jobs
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE leaves
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE migrations
  MODIFY id int UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE notifications
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE packages
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE payrolls
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE qr_codes
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE settings
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE subscriptions
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE subscription_history
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE users
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;


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

ALTER TABLE leaves
  ADD CONSTRAINT leaves_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT leaves_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE notifications
  ADD CONSTRAINT notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

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

ALTER TABLE users
  ADD CONSTRAINT users_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE SET NULL;

-- ===================================================================
-- DATA DUMMY UNTUK HAGAPLUS DATABASE YANG SUDAH ADA
-- ===================================================================

-- 1. INSERT PACKAGES
INSERT INTO packages (id, name, description, price, duration_days, max_employees, max_branches, features, is_active, created_at, updated_at) VALUES
(1, 'Starter', 'Paket dasar untuk usaha kecil dengan fitur absen QR Code dan GPS', 49000.00, 30, 10, 1, '["qr", "gps", "basic_reports"]', 1, NOW(), NOW()),
(2, 'Business', 'Paket lengkap untuk usaha menengah dengan multi cabang dan Face ID', 149000.00, 30, 50, 5, '["qr", "gps", "face_id", "shift_management", "leave_management", "payroll"]', 1, NOW(), NOW()),
(3, 'Enterprise', 'Paket premium untuk perusahaan besar dengan fitur lengkap', 299000.00, 30, 200, 20, '["qr", "gps", "face_id", "shift_management", "leave_management", "payroll", "advanced_reports", "api_access"]', 1, NOW(), NOW()),
(4, 'Corporate', 'Solusi enterprise dengan unlimited features', 599000.00, 30, 1000, 50, '["all_features", "unlimited_employees", "custom_integration", "dedicated_support"]', 1, NOW(), NOW());

-- 2. INSERT INSTANSIS
INSERT INTO instansis (id, nama_instansi, subdomain, status_langganan, created_at, updated_at) VALUES
(1, 'PT Teknologi Maju Bersama', 'teknologimaju', 'active', NOW(), NOW()),
(2, 'CV Kreatif Digital Solutions', 'kreatifdigital', 'active', NOW(), NOW()),
(3, 'Toko Berkah Jaya', 'berkahjaya', 'active', NOW(), NOW()),
(4, 'PT Industri Manufaktur Nusantara', 'manufakturnusantara', 'active', NOW(), NOW()),
(5, 'Klinik Sehat Sentosa', 'kliniksehat', 'inactive', NOW(), NOW());

-- 3. INSERT COMPANIES
INSERT INTO companies (id, name, email, phone, address, package_id, subscription_start, subscription_end, max_employees, max_branches, settings, created_at, updated_at) VALUES
(1, 'PT Teknologi Maju Bersama', 'admin@teknologimaju.com', '021-5555-0001', 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta', 3, '2024-08-01 00:00:00', '2024-10-31 23:59:59', 200, 20, '{"timezone": "Asia/Jakarta", "date_format": "d/m/Y", "currency": "IDR"}', NOW(), NOW()),
(2, 'CV Kreatif Digital Solutions', 'hr@kreatifdigital.com', '022-3333-0002', 'Jl. Asia Afrika No. 45, Bandung, Jawa Barat', 2, '2024-08-15 00:00:00', '2024-11-14 23:59:59', 50, 5, '{"timezone": "Asia/Jakarta", "working_hours": "10:00-19:00"}', NOW(), NOW()),
(3, 'Toko Berkah Jaya', 'owner@berkahjaya.com', '031-7777-0003', 'Jl. Diponegoro No. 88, Surabaya, Jawa Timur', 1, '2024-09-01 00:00:00', '2024-10-01 23:59:59', 10, 1, '{"timezone": "Asia/Jakarta", "working_days": [1,2,3,4,5,6]}', NOW(), NOW()),
(4, 'PT Industri Manufaktur Nusantara', 'hrd@manufakturnusantara.co.id', '024-9999-0004', 'Kawasan Industri Jababeka, Cikarang, Jawa Barat', 4, '2024-07-01 00:00:00', '2024-10-31 23:59:59', 1000, 50, '{"timezone": "Asia/Jakarta", "shift_based": true, "overtime_enabled": true}', NOW(), NOW());

-- 4. INSERT BRANCHES
INSERT INTO branches (id, company_id, name, address, latitude, longitude, radius, timezone, created_at, updated_at) VALUES
-- PT Teknologi Maju Bersama
(1, 1, 'Kantor Pusat Jakarta', 'Jl. Sudirman No. 123, Jakarta Pusat', -6.208763, 106.845599, 100, 'Asia/Jakarta', NOW(), NOW()),
(2, 1, 'Cabang Bekasi', 'Jl. Ahmad Yani No. 67, Bekasi Timur', -6.238270, 107.001567, 150, 'Asia/Jakarta', NOW(), NOW()),
(3, 1, 'Cabang Tangerang', 'Jl. Imam Bonjol No. 34, Tangerang Selatan', -6.297524, 106.718124, 120, 'Asia/Jakarta', NOW(), NOW()),

-- CV Kreatif Digital Solutions
(4, 2, 'Kantor Pusat Bandung', 'Jl. Asia Afrika No. 45, Bandung', -6.921831, 107.607147, 80, 'Asia/Jakarta', NOW(), NOW()),
(5, 2, 'Co-working Space Dago', 'Jl. Ir. H. Juanda No. 123, Bandung', -6.895562, 107.613144, 50, 'Asia/Jakarta', NOW(), NOW()),

-- Toko Berkah Jaya
(6, 3, 'Toko Utama', 'Jl. Diponegoro No. 88, Surabaya', -7.257472, 112.752088, 75, 'Asia/Jakarta', NOW(), NOW()),

-- PT Industri Manufaktur
(7, 4, 'Pabrik Utama Cikarang', 'Kawasan Industri Jababeka Blok A-1, Cikarang', -6.296406, 107.154808, 300, 'Asia/Jakarta', NOW(), NOW()),
(8, 4, 'Gudang Distribusi Karawang', 'Jl. Raya Karawang-Jakarta KM 45, Karawang', -6.301206, 107.307809, 200, 'Asia/Jakarta', NOW(), NOW());

-- 5. INSERT USERS (SUPERADMIN, ADMIN, EMPLOYEES)

-- SUPERADMIN
INSERT INTO users (id, name, email, password, role, instansi_id, created_at, updated_at) VALUES
(1, 'Super Administrator', 'superadmin@hagaplus.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'superadmin', NULL, NOW(), NOW());

-- ADMINS untuk setiap instansi
INSERT INTO users (id, name, email, password, role, instansi_id, created_at, updated_at) VALUES
(2, 'Budi Santoso', 'admin@teknologimaju.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW(), NOW()),
(3, 'Sari Dewi Lestari', 'hr@kreatifdigital.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 2, NOW(), NOW()),
(4, 'Ahmad Wijaya', 'owner@berkahjaya.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 3, NOW(), NOW()),
(5, 'Diana Permata Sari', 'hrd@manufakturnusantara.co.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 4, NOW(), NOW());

-- EMPLOYEES untuk PT Teknologi Maju Bersama (ID Instansi: 1)
INSERT INTO users (id, name, email, password, role, instansi_id, created_at, updated_at) VALUES
(6, 'Andi Pratama', 'andi.pratama@teknologimaju.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 1, NOW(), NOW()),
(7, 'Siti Nurhaliza', 'siti.nurhaliza@teknologimaju.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 1, NOW(), NOW()),
(8, 'Dedi Kurniawan', 'dedi.kurniawan@teknologimaju.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 1, NOW(), NOW()),
(9, 'Maya Sari', 'maya.sari@teknologimaju.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 1, NOW(), NOW()),
(10, 'Rizky Fauzan', 'rizky.fauzan@teknologimaju.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 1, NOW(), NOW()),
(11, 'Indah Permatasari', 'indah.permatasari@teknologimaju.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 1, NOW(), NOW()),
(12, 'Bayu Setiawan', 'bayu.setiawan@teknologimaju.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 1, NOW(), NOW()),
(13, 'Putri Maharani', 'putri.maharani@teknologimaju.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 1, NOW(), NOW()),

-- EMPLOYEES untuk CV Kreatif Digital Solutions (ID Instansi: 2)
(14, 'Yoga Pratama', 'yoga.pratama@kreatifdigital.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 2, NOW(), NOW()),
(15, 'Lina Marlina', 'lina.marlina@kreatifdigital.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 2, NOW(), NOW()),
(16, 'Farhan Maulana', 'farhan.maulana@kreatifdigital.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 2, NOW(), NOW()),
(17, 'Rika Amelia', 'rika.amelia@kreatifdigital.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 2, NOW(), NOW()),
(18, 'Galih Prasetyo', 'galih.prasetyo@kreatifdigital.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 2, NOW(), NOW()),

-- EMPLOYEES untuk Toko Berkah Jaya (ID Instansi: 3)
(19, 'Wati Suryani', 'wati.suryani@berkahjaya.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 3, NOW(), NOW()),
(20, 'Joko Susanto', 'joko.susanto@berkahjaya.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 3, NOW(), NOW()),
(21, 'Ani Rahayu', 'ani.rahayu@berkahjaya.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 3, NOW(), NOW()),
(22, 'Bambang Sutrisno', 'bambang.sutrisno@berkahjaya.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 3, NOW(), NOW()),

-- EMPLOYEES untuk PT Industri Manufaktur (ID Instansi: 4)
(23, 'Hendra Gunawan', 'hendra.gunawan@manufakturnusantara.co.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 4, NOW(), NOW()),
(24, 'Sri Mulyani', 'sri.mulyani@manufakturnusantara.co.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 4, NOW(), NOW()),
(25, 'Agus Salim', 'agus.salim@manufakturnusantara.co.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 4, NOW(), NOW()),
(26, 'Dewi Sartika', 'dewi.sartika@manufakturnusantara.co.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 4, NOW(), NOW()),
(27, 'Rudi Hartono', 'rudi.hartono@manufakturnusantara.co.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 4, NOW(), NOW()),
(28, 'Sari Wulandari', 'sari.wulandari@manufakturnusantara.co.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 4, NOW(), NOW()),
(29, 'Eko Prasetyo', 'eko.prasetyo@manufakturnusantara.co.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 4, NOW(), NOW()),
(30, 'Fitri Handayani', 'fitri.handayani@manufakturnusantara.co.id', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employee', 4, NOW(), NOW());

-- 6. INSERT EMPLOYEES DETAIL
INSERT INTO employees (id, user_id, instansi_id, branch_id, employee_id, position, department, salary, hire_date, status, created_at, updated_at) VALUES
-- PT Teknologi Maju Bersama Employees
(1, 6, 1, 1, 'TMB006', 'Software Engineer', 'Engineering', 8500000.00, '2023-01-15', 'active', NOW(), NOW()),
(2, 7, 1, 1, 'TMB007', 'UI/UX Designer', 'Design', 7500000.00, '2023-02-20', 'active', NOW(), NOW()),
(3, 8, 1, 2, 'TMB008', 'Project Manager', 'Project Management', 12000000.00, '2022-11-10', 'active', NOW(), NOW()),
(4, 9, 1, 2, 'TMB009', 'Quality Assurance', 'Quality Control', 6500000.00, '2023-03-05', 'active', NOW(), NOW()),
(5, 10, 1, 3, 'TMB010', 'DevOps Engineer', 'Infrastructure', 9500000.00, '2023-01-28', 'active', NOW(), NOW()),
(6, 11, 1, 3, 'TMB011', 'Business Analyst', 'Business', 8000000.00, '2023-04-12', 'active', NOW(), NOW()),
(7, 12, 1, 1, 'TMB012', 'Frontend Developer', 'Engineering', 7800000.00, '2023-05-17', 'active', NOW(), NOW()),
(8, 13, 1, 1, 'TMB013', 'Marketing Specialist', 'Marketing', 6800000.00, '2023-06-22', 'active', NOW(), NOW()),

-- CV Kreatif Digital Employees
(9, 14, 2, 4, 'KDS014', 'Web Developer', 'Development', 7000000.00, '2023-02-10', 'active', NOW(), NOW()),
(10, 15, 2, 4, 'KDS015', 'Graphic Designer', 'Creative', 6200000.00, '2023-03-15', 'active', NOW(), NOW()),
(11, 16, 2, 5, 'KDS016', 'Content Creator', 'Marketing', 5800000.00, '2023-04-20', 'active', NOW(), NOW()),
(12, 17, 2, 5, 'KDS017', 'Digital Marketer', 'Marketing', 6500000.00, '2023-01-08', 'active', NOW(), NOW()),
(13, 18, 2, 4, 'KDS018', 'Full Stack Developer', 'Development', 8200000.00, '2023-05-03', 'active', NOW(), NOW()),

-- Toko Berkah Jaya Employees
(14, 19, 3, 6, 'BJ019', 'Sales Associate', 'Sales', 4200000.00, '2022-08-15', 'active', NOW(), NOW()),
(15, 20, 3, 6, 'BJ020', 'Cashier', 'Operations', 3800000.00, '2023-01-20', 'active', NOW(), NOW()),
(16, 21, 3, 6, 'BJ021', 'Store Manager', 'Management', 6500000.00, '2022-05-10', 'active', NOW(), NOW()),
(17, 22, 3, 6, 'BJ022', 'Inventory Staff', 'Operations', 4000000.00, '2023-03-12', 'active', NOW(), NOW()),

-- PT Industri Manufaktur Employees
(18, 23, 4, 7, 'IMN023', 'Production Operator', 'Production', 5200000.00, '2022-09-01', 'active', NOW(), NOW()),
(19, 24, 4, 7, 'IMN024', 'Quality Control Specialist', 'Quality Control', 6800000.00, '2022-10-15', 'active', NOW(), NOW()),
(20, 25, 4, 8, 'IMN025', 'Maintenance Technician', 'Maintenance', 6200000.00, '2023-01-03', 'active', NOW(), NOW()),
(21, 26, 4, 8, 'IMN026', 'Production Supervisor', 'Production', 8500000.00, '2022-07-20', 'active', NOW(), NOW()),
(22, 27, 4, 7, 'IMN027', 'Safety Officer', 'Safety', 7200000.00, '2023-02-14', 'active', NOW(), NOW()),
(23, 28, 4, 7, 'IMN028', 'Warehouse Staff', 'Logistics', 4800000.00, '2023-04-05', 'active', NOW(), NOW()),
(24, 29, 4, 8, 'IMN029', 'Machine Operator', 'Production', 5500000.00, '2023-03-18', 'active', NOW(), NOW()),
(25, 30, 4, 8, 'IMN030', 'Admin Staff', 'Administration', 4500000.00, '2023-06-01', 'active', NOW(), NOW());

-- 7. INSERT ATTENDANCE POLICIES
INSERT INTO attendance_policies (id, company_id, name, work_days, start_time, end_time, break_duration, late_tolerance, early_checkout_tolerance, overtime_after_minutes, attendance_methods, auto_checkout, auto_checkout_time, is_default, is_active, created_at, updated_at) VALUES
(1, 1, 'Kebijakan Standar Office', '[1,2,3,4,5]', '09:00:00', '18:00:00', 60, 15, 15, 480, '["qr", "gps", "face_id"]', 0, NULL, 1, 1, NOW(), NOW()),
(2, 2, 'Creative Team Schedule', '[1,2,3,4,5]', '10:00:00', '19:00:00', 90, 20, 20, 540, '["qr", "gps", "face_id"]', 0, NULL, 1, 1, NOW(), NOW()),
(3, 3, 'Retail Store Hours', '[1,2,3,4,5,6]', '08:00:00', '17:00:00', 60, 10, 10, 540, '["qr", "gps"]', 1, '17:30:00', 1, 1, NOW(), NOW()),
(4, 4, 'Manufacturing Shift A', '[1,2,3,4,5,6]', '06:00:00', '14:00:00', 30, 5, 5, 480, '["qr", "gps"]', 0, NULL, 1, 1, NOW(), NOW()),
(5, 4, 'Manufacturing Shift B', '[1,2,3,4,5,6]', '14:00:00', '22:00:00', 30, 5, 5, 480, '["qr", "gps"]', 0, NULL, 0, 1, NOW(), NOW()),
(6, 4, 'Manufacturing Shift C', '[1,2,3,4,5,6]', '22:00:00', '06:00:00', 30, 5, 5, 480, '["qr", "gps"]', 0, NULL, 0, 1, NOW(), NOW());

-- 8. INSERT EMPLOYEE SCHEDULES
INSERT INTO employee_schedules (user_id, policy_id, effective_date, end_date, is_active, created_at, updated_at) VALUES
-- PT Teknologi Maju (Policy ID: 1)
(6, 1, '2023-01-15', NULL, 1, NOW(), NOW()),
(7, 1, '2023-02-20', NULL, 1, NOW(), NOW()),
(8, 1, '2022-11-10', NULL, 1, NOW(), NOW()),
(9, 1, '2023-03-05', NULL, 1, NOW(), NOW()),
(10, 1, '2023-01-28', NULL, 1, NOW(), NOW()),
(11, 1, '2023-04-12', NULL, 1, NOW(), NOW()),
(12, 1, '2023-05-17', NULL, 1, NOW(), NOW()),
(13, 1, '2023-06-22', NULL, 1, NOW(), NOW()),

-- CV Kreatif Digital (Policy ID: 2)
(14, 2, '2023-02-10', NULL, 1, NOW(), NOW()),
(15, 2, '2023-03-15', NULL, 1, NOW(), NOW()),
(16, 2, '2023-04-20', NULL, 1, NOW(), NOW()),
(17, 2, '2023-01-08', NULL, 1, NOW(), NOW()),
(18, 2, '2023-05-03', NULL, 1, NOW(), NOW()),

-- Toko Berkah Jaya (Policy ID: 3)
(19, 3, '2022-08-15', NULL, 1, NOW(), NOW()),
(20, 3, '2023-01-20', NULL, 1, NOW(), NOW()),
(21, 3, '2022-05-10', NULL, 1, NOW(), NOW()),
(22, 3, '2023-03-12', NULL, 1, NOW(), NOW()),

-- PT Industri Manufaktur (Policy ID: 4 - Shift A)
(23, 4, '2022-09-01', NULL, 1, NOW(), NOW()),
(24, 4, '2022-10-15', NULL, 1, NOW(), NOW()),
(25, 4, '2023-01-03', NULL, 1, NOW(), NOW()),
(26, 4, '2022-07-20', NULL, 1, NOW(), NOW()),
(27, 4, '2023-02-14', NULL, 1, NOW(), NOW()),
(28, 5, '2023-04-05', NULL, 1, NOW(), NOW()), -- Shift B
(29, 5, '2023-03-18', NULL, 1, NOW(), NOW()), -- Shift B
(30, 4, '2023-06-01', NULL, 1, NOW(), NOW());

-- 9. INSERT QR CODES untuk setiap branch
INSERT INTO qr_codes (branch_id, code, expires_at, is_active, created_at, updated_at) VALUES
(1, 'QR_BRANCH_1_ABC123DEF456', DATE_ADD(NOW(), INTERVAL 24 HOUR), 1, NOW(), NOW()),
(2, 'QR_BRANCH_2_GHI789JKL012', DATE_ADD(NOW(), INTERVAL 24 HOUR), 1, NOW(), NOW()),
(3, 'QR_BRANCH_3_MNO345PQR678', DATE_ADD(NOW(), INTERVAL 24 HOUR), 1, NOW(), NOW()),
(4, 'QR_BRANCH_4_STU901VWX234', DATE_ADD(NOW(), INTERVAL 24 HOUR), 1, NOW(), NOW()),
(5, 'QR_BRANCH_5_YZA567BCD890', DATE_ADD(NOW(), INTERVAL 24 HOUR), 1, NOW(), NOW()),
(6, 'QR_BRANCH_6_EFG123HIJ456', DATE_ADD(NOW(), INTERVAL 24 HOUR), 1, NOW(), NOW()),
(7, 'QR_BRANCH_7_KLM789NOP012', DATE_ADD(NOW(), INTERVAL 24 HOUR), 1, NOW(), NOW()),
(8, 'QR_BRANCH_8_QRS345TUV678', DATE_ADD(NOW(), INTERVAL 24 HOUR), 1, NOW(), NOW());

-- 10. INSERT SAMPLE ATTENDANCES (Last 7 days)
INSERT INTO attendances (user_id, branch_id, attendance_date, check_in_time, check_out_time, check_in_method, check_out_method, check_in_location, work_duration, break_duration, overtime_duration, late_minutes, status, notes, created_at, updated_at) VALUES
-- Employee ID 6 (Andi Pratama) - Last 7 days
(6, 1, CURDATE() - INTERVAL 6 DAY, CONCAT(CURDATE() - INTERVAL 6 DAY, ' 08:55:00'), CONCAT(CURDATE() - INTERVAL 6 DAY, ' 18:10:00'), 'qr', 'qr', '-6.208763,106.845599', 555, 60, 75, 0, 'present', 'Hadir tepat waktu', NOW(), NOW()),
(6, 1, CURDATE() - INTERVAL 5 DAY, CONCAT(CURDATE() - INTERVAL 5 DAY, ' 09:20:00'), CONCAT(CURDATE() - INTERVAL 5 DAY, ' 18:05:00'), 'gps', 'gps', '-6.208763,106.845599', 525, 60, 45, 20, 'late', 'Terlambat karena macet', NOW(), NOW()),
(6, 1, CURDATE() - INTERVAL 4 DAY, CONCAT(CURDATE() - INTERVAL 4 DAY, ' 08:45:00'), CONCAT(CURDATE() - INTERVAL 4 DAY, ' 18:15:00'), 'face_id', 'face_id', '-6.208763,106.845599', 570, 60, 90, 0, 'present', 'Kerja lembur', NOW(), NOW()),
(6, 1, CURDATE() - INTERVAL 3 DAY, CONCAT(CURDATE() - INTERVAL 3 DAY, ' 09:00:00'), CONCAT(CURDATE() - INTERVAL 3 DAY, ' 18:00:00'), 'qr', 'qr', '-6.208763,106.845599', 540, 60, 60, 0, 'present', NULL, NOW(), NOW()),
(6, 1, CURDATE() - INTERVAL 2 DAY, CONCAT(CURDATE() - INTERVAL 2 DAY, ' 08:50:00'), CONCAT(CURDATE() - INTERVAL 2 DAY, ' 17:55:00'), 'gps', 'gps', '-6.208763,106.845599', 545, 60, 65, 0, 'present', NULL, NOW(), NOW()),
(6, 1, CURDATE() - INTERVAL 1 DAY, CONCAT(CURDATE() - INTERVAL 1 DAY, ' 09:10:00'), CONCAT(CURDATE() - INTERVAL 1 DAY, ' 18:20:00'), 'face_id', 'face_id', '-6.208763,106.845599', 550, 60, 70, 10, 'late', 'Sedikit terlambat', NOW(), NOW()),

-- Employee ID 7 (Siti Nurhaliza) - Last 7 days
(7, 1, CURDATE() - INTERVAL 6 DAY, CONCAT(CURDATE() - INTERVAL 6 DAY, ' 08:58:00'), CONCAT(CURDATE() - INTERVAL 6 DAY, ' 18:05:00'), 'qr', 'qr', '-6.208763,106.845599', 547, 60, 67, 0, 'present', NULL, NOW(), NOW()),
(7, 1, CURDATE() - INTERVAL 5 DAY, CONCAT(CURDATE() - INTERVAL 5 DAY, ' 09:05:00'), CONCAT(CURDATE() - INTERVAL 5 DAY, ' 18:10:00'), 'gps', 'gps', '-6.208763,106.845599', 545, 60, 65, 5, 'present', NULL, NOW(), NOW()),
(7, 1, CURDATE() - INTERVAL 4 DAY, CONCAT(CURDATE() - INTERVAL 4 DAY, ' 08:55:00'), CONCAT(CURDATE() - INTERVAL 4 DAY, ' 18:00:00'), 'face_id', 'face_id', '-6.208763,106.845599', 545, 60, 65, 0, 'present', NULL, NOW(), NOW()),
(7, 1, CURDATE() - INTERVAL 3 DAY, CONCAT(CURDATE() - INTERVAL 3 DAY, ' 09:15:00'), CONCAT(CURDATE() - INTERVAL 3 DAY, ' 18:15:00'), 'qr', 'qr', '-6.208763,106.845599', 540, 60, 60, 15, 'late', 'Meeting pagi', NOW(), NOW()),
(7, 1, CURDATE() - INTERVAL 2 DAY, CONCAT(CURDATE() - INTERVAL 2 DAY, ' 08:50:00'), CONCAT(CURDATE() - INTERVAL 2 DAY, ' 17:50:00'), 'gps', 'gps', '-6.208763,106.845599', 540, 60, 60, 0, 'present', NULL, NOW(), NOW()),

-- Employee ID 14 (Yoga Pratama) - Creative Team
(14, 4, CURDATE() - INTERVAL 6 DAY, CONCAT(CURDATE() - INTERVAL 6 DAY, ' 10:05:00'), CONCAT(CURDATE() - INTERVAL 6 DAY, ' 19:10:00'), 'qr', 'qr', '-6.921831,107.607147', 545, 90, 65, 5, 'present', NULL, NOW(), NOW()),
(14, 4, CURDATE() - INTERVAL 5 DAY, CONCAT(CURDATE() - INTERVAL 5 DAY, ' 09:55:00'), CONCAT(CURDATE() - INTERVAL 5 DAY, ' 19:05:00'), 'gps', 'gps', '-6.921831,107.607147', 550, 90, 70, 0, 'present', 'Datang lebih awal', NOW(), NOW()),
(14, 4, CURDATE() - INTERVAL 4 DAY, CONCAT(CURDATE() - INTERVAL 4 DAY, ' 10:20:00'), CONCAT(CURDATE() - INTERVAL 4 DAY, ' 19:25:00'), 'face_id', 'face_id', '-6.921831,107.607147', 545, 90, 65, 20, 'late', 'Meeting client pagi', NOW(), NOW()),
(14, 4, CURDATE() - INTERVAL 3 DAY, CONCAT(CURDATE() - INTERVAL 3 DAY, ' 10:00:00'), CONCAT(CURDATE() - INTERVAL 3 DAY, ' 19:00:00'), 'qr', 'qr', '-6.921831,107.607147', 540, 90, 60, 0, 'present', NULL, NOW(), NOW()),

-- Employee ID 19 (Wati Suryani) - Retail
(19, 6, CURDATE() - INTERVAL 6 DAY, CONCAT(CURDATE() - INTERVAL 6 DAY, ' 07:58:00'), CONCAT(CURDATE() - INTERVAL 6 DAY, ' 17:02:00'), 'qr', 'qr', '-7.257472,112.752088', 544, 60, 64, 0, 'present', NULL, NOW(), NOW()),
(19, 6, CURDATE() - INTERVAL 5 DAY, CONCAT(CURDATE() - INTERVAL 5 DAY, ' 08:05:00'), CONCAT(CURDATE() - INTERVAL 5 DAY, ' 17:05:00'), 'gps', 'gps', '-7.257472,112.752088', 540, 60, 60, 5, 'present', NULL, NOW(), NOW()),
(19, 6, CURDATE() - INTERVAL 4 DAY, CONCAT(CURDATE() - INTERVAL 4 DAY, ' 08:12:00'), CONCAT(CURDATE() - INTERVAL 4 DAY, ' 17:12:00'), 'qr', 'qr', '-7.257472,112.752088', 540, 60, 60, 12, 'late', 'Kendaraan mogok', NOW(), NOW()),

-- Employee ID 23 (Hendra Gunawan) - Manufacturing Shift A
(23, 7, CURDATE() - INTERVAL 6 DAY, CONCAT(CURDATE() - INTERVAL 6 DAY, ' 05:58:00'), CONCAT(CURDATE() - INTERVAL 6 DAY, ' 14:02:00'), 'qr', 'qr', '-6.296406,107.154808', 484, 30, 4, 0, 'present', NULL, NOW(), NOW()),
(23, 7, CURDATE() - INTERVAL 5 DAY, CONCAT(CURDATE() - INTERVAL 5 DAY, ' 06:03:00'), CONCAT(CURDATE() - INTERVAL 5 DAY, ' 14:05:00'), 'gps', 'gps', '-6.296406,107.154808', 482, 30, 2, 3, 'present', NULL, NOW(), NOW()),
(23, 7, CURDATE() - INTERVAL 4 DAY, CONCAT(CURDATE() - INTERVAL 4 DAY, ' 06:08:00'), CONCAT(CURDATE() - INTERVAL 4 DAY, ' 14:10:00'), 'qr', 'qr', '-6.296406,107.154808', 482, 30, 2, 8, 'late', 'Bangun kesiangan', NOW(), NOW()),
(23, 7, CURDATE() - INTERVAL 3 DAY, CONCAT(CURDATE() - INTERVAL 3 DAY, ' 05:55:00'), CONCAT(CURDATE() - INTERVAL 3 DAY, ' 14:15:00'), 'gps', 'gps', '-6.296406,107.154808', 500, 30, 20, 0, 'present', 'Lembur produksi', NOW(), NOW()),

-- Employee ID 28 (Sari Wulandari) - Manufacturing Shift B
(28, 7, CURDATE() - INTERVAL 6 DAY, CONCAT(CURDATE() - INTERVAL 6 DAY, ' 13:58:00'), CONCAT(CURDATE() - INTERVAL 6 DAY, ' 22:02:00'), 'qr', 'qr', '-6.296406,107.154808', 484, 30, 4, 0, 'present', NULL, NOW(), NOW()),
(28, 7, CURDATE() - INTERVAL 5 DAY, CONCAT(CURDATE() - INTERVAL 5 DAY, ' 14:05:00'), CONCAT(CURDATE() - INTERVAL 5 DAY, ' 22:05:00'), 'gps', 'gps', '-6.296406,107.154808', 480, 30, 0, 5, 'present', NULL, NOW(), NOW()),
(28, 7, CURDATE() - INTERVAL 4 DAY, CONCAT(CURDATE() - INTERVAL 4 DAY, ' 14:02:00'), CONCAT(CURDATE() - INTERVAL 4 DAY, ' 22:10:00'), 'qr', 'qr', '-6.296406,107.154808', 488, 30, 8, 2, 'present', NULL, NOW(), NOW());

-- 11. INSERT SAMPLE LEAVES
INSERT INTO `leaves` (user_id, leave_type, start_date, end_date, days_count, reason, status, approved_by, approved_at, created_at, updated_at) VALUES
(6, 'annual', '2024-10-15', '2024-10-17', 3, 'Liburan keluarga ke Bali', 'approved', 2, NOW() - INTERVAL 2 DAY, NOW() - INTERVAL 7 DAY, NOW()),
(7, 'sick', '2024-09-20', '2024-09-21', 2, 'Demam dan flu', 'approved', 2, NOW() - INTERVAL 15 DAY, NOW() - INTERVAL 20 DAY, NOW()),
(14, 'annual', '2024-11-01', '2024-11-03', 3, 'Acara pernikahan saudara', 'pending', NULL, NULL, NOW() - INTERVAL 3 DAY, NOW()),
(19, 'emergency', '2024-09-25', '2024-09-25', 1, 'Anak sakit mendadak', 'approved', 4, NOW() - INTERVAL 10 DAY, NOW() - INTERVAL 12 DAY, NOW()),
(23, 'annual', '2024-10-20', '2024-10-22', 3, 'Mudik lebaran haji', 'rejected', 5, NOW() - INTERVAL 5 DAY, NOW() - INTERVAL 8 DAY, NOW()),
(24, 'sick', '2024-10-01', '2024-10-02', 2, 'Sakit punggung', 'approved', 5, NOW() - INTERVAL 18 DAY, NOW() - INTERVAL 20 DAY, NOW());

-- 12. INSERT SAMPLE PAYROLLS (September 2024)
INSERT INTO payrolls (user_id, period_year, period_month, basic_salary, allowances, deductions, overtime_amount, total_gross, total_deductions, net_salary, payment_date, payment_status, created_by, created_at, updated_at) VALUES
-- PT Teknologi Maju Employees
(6, 2024, 9, 8500000.00, '{"transport": 500000, "meal": 300000, "performance": 850000}', '{"tax": 425000, "bpjs_kesehatan": 85000, "bpjs_ketenagakerjaan": 170000}', 450000.00, 10600000.00, 680000.00, 9920000.00, '2024-10-05', 'paid', 2, NOW(), NOW()),
(7, 2024, 9, 7500000.00, '{"transport": 500000, "meal": 300000, "performance": 750000}', '{"tax": 375000, "bpjs_kesehatan": 75000, "bpjs_ketenagakerjaan": 150000}', 380000.00, 9430000.00, 600000.00, 8830000.00, '2024-10-05', 'paid', 2, NOW(), NOW()),
(8, 2024, 9, 12000000.00, '{"transport": 500000, "meal": 300000, "performance": 1200000, "management": 500000}', '{"tax": 600000, "bpjs_kesehatan": 120000, "bpjs_ketenagakerjaan": 240000}', 520000.00, 15020000.00, 960000.00, 14060000.00, '2024-10-05', 'paid', 2, NOW(), NOW()),

-- CV Kreatif Digital Employees
(14, 2024, 9, 7000000.00, '{"creativity": 400000, "internet": 150000, "performance": 700000}', '{"tax": 350000, "bpjs_kesehatan": 70000, "bpjs_ketenagakerjaan": 140000}', 320000.00, 8570000.00, 560000.00, 8010000.00, '2024-10-05', 'paid', 3, NOW(), NOW()),
(15, 2024, 9, 6200000.00, '{"creativity": 400000, "internet": 150000, "performance": 620000}', '{"tax": 310000, "bpjs_kesehatan": 62000, "bpjs_ketenagakerjaan": 124000}', 280000.00, 7650000.00, 496000.00, 7154000.00, '2024-10-05', 'paid', 3, NOW(), NOW()),

-- Toko Berkah Jaya Employees
(19, 2024, 9, 4200000.00, '{"sales_incentive": 84000, "attendance": 200000}', '{"tax": 210000, "bpjs_kesehatan": 42000, "employee_fund": 25000}', 150000.00, 4634000.00, 277000.00, 4357000.00, '2024-10-05', 'paid', 4, NOW(), NOW()),
(20, 2024, 9, 3800000.00, '{"attendance": 200000}', '{"tax": 190000, "bpjs_kesehatan": 38000, "employee_fund": 25000}', 120000.00, 4120000.00, 253000.00, 3867000.00, '2024-10-05', 'paid', 4, NOW(), NOW()),

-- PT Industri Manufaktur Employees
(23, 2024, 9, 5200000.00, '{"shift": 300000, "safety": 250000}', '{"tax": 260000, "bpjs_kesehatan": 52000, "bpjs_ketenagakerjaan": 104000, "cooperative": 100000}', 200000.00, 5950000.00, 516000.00, 5434000.00, '2024-10-05', 'paid', 5, NOW(), NOW()),
(24, 2024, 9, 6800000.00, '{"shift": 300000, "safety": 250000, "quality_bonus": 340000}', '{"tax": 340000, "bpjs_kesehatan": 68000, "bpjs_ketenagakerjaan": 136000, "cooperative": 100000}', 280000.00, 8970000.00, 644000.00, 8326000.00, '2024-10-05', 'paid', 5, NOW(), NOW()),
(28, 2024, 9, 4800000.00, '{"shift": 300000, "safety": 250000}', '{"tax": 240000, "bpjs_kesehatan": 48000, "bpjs_ketenagakerjaan": 96000, "cooperative": 100000}', 180000.00, 5530000.00, 484000.00, 5046000.00, '2024-10-05', 'paid', 5, NOW(), NOW());

-- 13. INSERT SUBSCRIPTIONS
INSERT INTO subscriptions (instansi_id, package_id, status, start_date, end_date, price, created_at, updated_at) VALUES
(1, 3, 'active', '2024-08-01', '2024-10-31', 299000.00, NOW(), NOW()),
(2, 2, 'active', '2024-08-15', '2024-11-14', 149000.00, NOW(), NOW()),
(3, 1, 'active', '2024-09-01', '2024-10-01', 49000.00, NOW(), NOW()),
(4, 4, 'active', '2024-07-01', '2024-10-31', 599000.00, NOW(), NOW()),
(5, 1, 'expired', '2024-06-01', '2024-07-01', 49000.00, NOW(), NOW());

-- 14. INSERT SUBSCRIPTION HISTORY
INSERT INTO subscription_history (company_id, package_id, start_date, end_date, amount, payment_method, payment_status, transaction_id, notes, created_by, created_at, updated_at) VALUES
(1, 3, '2024-08-01 00:00:00', '2024-10-31 23:59:59', 299000.00, 'Bank Transfer', 'paid', 'TXN-TMB-240801-001', 'Pembayaran paket Enterprise untuk 3 bulan', 1, '2024-08-01 10:00:00', NOW()),
(2, 2, '2024-08-15 00:00:00', '2024-11-14 23:59:59', 149000.00, 'Bank Transfer', 'paid', 'TXN-KDS-240815-001', 'Pembayaran paket Business untuk 3 bulan', 1, '2024-08-15 14:30:00', NOW()),
(3, 1, '2024-09-01 00:00:00', '2024-10-01 23:59:59', 49000.00, 'Cash', 'paid', 'TXN-BJ-240901-001', 'Pembayaran paket Starter untuk 1 bulan', 1, '2024-09-01 09:15:00', NOW()),
(4, 4, '2024-07-01 00:00:00', '2024-10-31 23:59:59', 599000.00, 'Bank Transfer', 'paid', 'TXN-IMN-240701-001', 'Pembayaran paket Corporate untuk 4 bulan', 1, '2024-07-01 11:45:00', NOW());

-- 15. INSERT COMPANY THEMES
INSERT INTO company_themes (company_id, primary_color, secondary_color, logo, favicon, custom_css, created_at, updated_at) VALUES
(1, '#3b82f6', '#64748b', 'themes/company1/logo.png', 'themes/company1/favicon.ico', '.navbar { background: linear-gradient(90deg, #3b82f6, #1e40af); }', NOW(), NOW()),
(2, '#8b5cf6', '#a855f7', 'themes/company2/logo.png', 'themes/company2/favicon.ico', '.header { border-bottom: 3px solid #8b5cf6; }', NOW(), NOW()),
(3, '#f59e0b', '#d97706', 'themes/company3/logo.png', 'themes/company3/favicon.ico', '.btn-primary { background-color: #f59e0b; border-color: #f59e0b; }', NOW(), NOW()),
(4, '#ef4444', '#dc2626', 'themes/company4/logo.png', 'themes/company4/favicon.ico', '.sidebar { background: linear-gradient(180deg, #ef4444, #dc2626); }', NOW(), NOW());

-- 16. INSERT SETTINGS untuk setiap instansi
INSERT INTO settings (instansi_id, `key`, `value`, `type`, created_at, updated_at) VALUES
-- PT Teknologi Maju Bersama
(1, 'company_name', 'PT Teknologi Maju Bersama', 'string', NOW(), NOW()),
(1, 'working_hours_start', '09:00', 'string', NOW(), NOW()),
(1, 'working_hours_end', '18:00', 'string', NOW(), NOW()),
(1, 'late_tolerance', '15', 'integer', NOW(), NOW()),
(1, 'overtime_enabled', 'true', 'boolean', NOW(), NOW()),
(1, 'face_recognition_enabled', 'true', 'boolean', NOW(), NOW()),

-- CV Kreatif Digital Solutions
(2, 'company_name', 'CV Kreatif Digital Solutions', 'string', NOW(), NOW()),
(2, 'working_hours_start', '10:00', 'string', NOW(), NOW()),
(2, 'working_hours_end', '19:00', 'string', NOW(), NOW()),
(2, 'late_tolerance', '20', 'integer', NOW(), NOW()),
(2, 'flexible_working', 'true', 'boolean', NOW(), NOW()),

-- Toko Berkah Jaya
(3, 'company_name', 'Toko Berkah Jaya', 'string', NOW(), NOW()),
(3, 'working_hours_start', '08:00', 'string', NOW(), NOW()),
(3, 'working_hours_end', '17:00', 'string', NOW(), NOW()),
(3, 'late_tolerance', '10', 'integer', NOW(), NOW()),
(3, 'auto_checkout', 'true', 'boolean', NOW(), NOW()),

-- PT Industri Manufaktur Nusantara
(4, 'company_name', 'PT Industri Manufaktur Nusantara', 'string', NOW(), NOW()),
(4, 'shift_system', 'true', 'boolean', NOW(), NOW()),
(4, 'safety_protocol_enabled', 'true', 'boolean', NOW(), NOW()),
(4, 'late_tolerance', '5', 'integer', NOW(), NOW());

-- 17. INSERT NOTIFICATIONS
INSERT INTO notifications (user_id, title, message, type, is_read, created_at, updated_at) VALUES
-- Notifications for employees
(6, 'Selamat Datang!', 'Selamat datang di sistem HagaPlus. Jangan lupa untuk selalu absen tepat waktu.', 'info', 1, NOW() - INTERVAL 30 DAY, NOW()),
(6, 'Pengajuan Cuti Disetujui', 'Pengajuan cuti Anda untuk tanggal 15-17 Oktober 2024 telah disetujui.', 'success', 1, NOW() - INTERVAL 2 DAY, NOW()),
(7, 'Reminder: Absen Hari Ini', 'Jangan lupa untuk melakukan absen masuk hari ini.', 'warning', 0, NOW(), NOW()),
(14, 'Pengajuan Cuti Menunggu Persetujuan', 'Pengajuan cuti Anda sedang dalam proses review oleh atasan.', 'info', 0, NOW() - INTERVAL 3 DAY, NOW()),
(19, 'Gaji Bulan September Sudah Dibayar', 'Gaji bulan September 2024 sebesar Rp 4,357,000 sudah ditransfer ke rekening Anda.', 'success', 1, NOW() - INTERVAL 5 DAY, NOW()),
(23, 'Pengajuan Cuti Ditolak', 'Pengajuan cuti Anda untuk tanggal 20-22 Oktober 2024 ditolak karena sedang masa peak production.', 'error', 0, NOW() - INTERVAL 5 DAY, NOW()),

-- Notifications for admins
(2, 'Karyawan Baru Terdaftar', 'Ada 3 karyawan baru yang telah terdaftar minggu ini.', 'info', 1, NOW() - INTERVAL 7 DAY, NOW()),
(2, 'Laporan Absensi Bulanan Siap', 'Laporan absensi bulan September 2024 sudah siap untuk didownload.', 'success', 0, NOW() - INTERVAL 1 DAY, NOW()),
(3, 'Subscription Akan Berakhir', 'Paket Business Anda akan berakhir pada tanggal 14 November 2024.', 'warning', 0, NOW(), NOW()),
(4, 'Ada Pengajuan Cuti Pending', 'Terdapat 1 pengajuan cuti yang menunggu persetujuan Anda.', 'warning', 0, NOW() - INTERVAL 12 HOUR, NOW()),
(5, 'Pembayaran Gaji Berhasil', 'Pembayaran gaji bulan September untuk 8 karyawan telah berhasil diproses.', 'success', 1, NOW() - INTERVAL 5 DAY, NOW()),

-- Notification for superadmin
(1, 'Instansi Baru Terdaftar', 'Ada 2 instansi baru yang mendaftar minggu ini.', 'info', 1, NOW() - INTERVAL 7 DAY, NOW()),
(1, 'Subscription Berakhir', 'Subscription untuk Klinik Sehat Sentosa telah berakhir.', 'warning', 0, NOW() - INTERVAL 30 DAY, NOW());

-- ===================================================================
-- SUMMARY DATA YANG TELAH DIBUAT
-- ===================================================================
/*
DATA SUMMARY:
============

1. PACKAGES: 4 paket (Starter, Business, Enterprise, Corporate)
2. INSTANSIS: 5 instansi (4 active, 1 inactive)
3. COMPANIES: 4 company yang terhubung dengan instansi
4. BRANCHES: 8 cabang tersebar di berbagai kota
5. USERS: 30 users total
   - 1 Super Admin
   - 4 Company Admins
   - 25 Employees
6. EMPLOYEES: 25 detail karyawan dengan posisi dan gaji
7. ATTENDANCE POLICIES: 6 kebijakan absen berbeda
8. EMPLOYEE SCHEDULES: Semua karyawan sudah terjadwal
9. QR CODES: 8 QR code aktif untuk setiap cabang
10. ATTENDANCES: 25+ record absensi 7 hari terakhir
11. LEAVES: 6 pengajuan cuti dengan berbagai status
12. PAYROLLS: 10 slip gaji September 2024
13. SUBSCRIPTIONS: 5 subscription record
14. SUBSCRIPTION HISTORY: 4 history pembayaran
15. COMPANY THEMES: 4 tema kustomisasi perusahaan
16. SETTINGS: 20+ pengaturan untuk setiap instansi
17. NOTIFICATIONS: 15+ notifikasi untuk berbagai user
18. QR CODES: 8 QR code dengan expiry 24 jam

KREDENSIAL LOGIN:
=================
Super Admin:
- Email: superadmin@hagaplus.com
- Password: password (hash: $2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi)

Company Admin:
- admin@teknologimaju.com / password
- hr@kreatifdigital.com / password  
- owner@berkahjaya.com / password
- hrd@manufakturnusantara.co.id / password

Sample Employees:
- andi.pratama@teknologimaju.com / password
- siti.nurhaliza@teknologimaju.com / password
- yoga.pratama@kreatifdigital.com / password
- wati.suryani@berkahjaya.com / password
- hendra.gunawan@manufakturnusantara.co.id / password

FITUR YANG SUDAH ADA DATA:
==========================
✅ Multi-tenant system dengan instansi terpisah
✅ Berbagai paket subscription dengan fitur berbeda
✅ Multiple branches per company
✅ Role-based users (superadmin, admin, employee)
✅ Attendance tracking dengan berbagai method
✅ Leave management system
✅ Payroll system dengan allowances & deductions  
✅ QR Code system untuk absen
✅ Company branding/themes
✅ Notification system
✅ Settings per instansi
✅ Realistic attendance patterns
✅ Various work shifts (office, retail, manufacturing)

Jalankan query SQL ini untuk populate database dengan data dummy yang lengkap dan realistis!
*/
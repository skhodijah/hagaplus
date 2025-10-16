SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE activity_logs (
  id bigint UNSIGNED NOT NULL,
  log_name varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  description text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  subject_type varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  subject_id bigint UNSIGNED DEFAULT NULL,
  causer_type varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  causer_id bigint UNSIGNED DEFAULT NULL,
  properties json DEFAULT NULL,
  event varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  batch_uuid varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  instansi_id bigint UNSIGNED DEFAULT NULL,
  ip_address varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  user_agent text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  updated_at timestamp NULL DEFAULT NULL,
  deleted_at timestamp NULL DEFAULT NULL,
  deleted_by bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE employee_policies (
  id bigint UNSIGNED NOT NULL,
  employee_id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  description text COLLATE utf8mb4_unicode_ci,
  work_days json DEFAULT NULL,
  work_start_time time DEFAULT NULL,
  work_end_time time DEFAULT NULL,
  work_hours_per_day int NOT NULL DEFAULT '8',
  break_times json DEFAULT NULL,
  grace_period_minutes int NOT NULL DEFAULT '15',
  max_late_minutes int NOT NULL DEFAULT '120',
  early_leave_grace_minutes int NOT NULL DEFAULT '15',
  allow_overtime tinyint(1) NOT NULL DEFAULT '0',
  max_overtime_hours_per_day int NOT NULL DEFAULT '2',
  max_overtime_hours_per_week int NOT NULL DEFAULT '10',
  annual_leave_days int NOT NULL DEFAULT '12',
  sick_leave_days int NOT NULL DEFAULT '14',
  personal_leave_days int NOT NULL DEFAULT '3',
  allow_negative_leave_balance tinyint(1) NOT NULL DEFAULT '0',
  can_work_from_home tinyint(1) NOT NULL DEFAULT '0',
  flexible_hours tinyint(1) NOT NULL DEFAULT '0',
  skip_weekends tinyint(1) NOT NULL DEFAULT '0',
  skip_holidays tinyint(1) NOT NULL DEFAULT '1',
  require_location_check tinyint(1) NOT NULL DEFAULT '1',
  allowed_radius_meters decimal(10,2) NOT NULL DEFAULT '100.00',
  allowed_locations json DEFAULT NULL,
  has_shifts tinyint(1) NOT NULL DEFAULT '0',
  shift_schedule json DEFAULT NULL,
  custom_rules json DEFAULT NULL,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  effective_from timestamp NULL DEFAULT NULL,
  effective_until timestamp NULL DEFAULT NULL,
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
  email varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  phone varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  address text COLLATE utf8mb4_unicode_ci,
  logo varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  subdomain varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  status_langganan enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  deleted_at timestamp NULL DEFAULT NULL,
  deleted_by bigint UNSIGNED DEFAULT NULL,
  retention_policy json DEFAULT NULL,
  archived_at timestamp NULL DEFAULT NULL,
  archived_by bigint UNSIGNED DEFAULT NULL,
  package_id bigint UNSIGNED DEFAULT NULL,
  subscription_start datetime DEFAULT NULL,
  subscription_end datetime DEFAULT NULL,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  max_employees int NOT NULL DEFAULT '10',
  max_branches int NOT NULL DEFAULT '1',
  settings json DEFAULT NULL
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
  user_id bigint UNSIGNED DEFAULT NULL,
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

CREATE TABLE payment_methods (
  id bigint UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  type enum('bank_transfer','qris','ewallet') COLLATE utf8mb4_unicode_ci NOT NULL,
  account_number varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  account_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  bank_name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  qris_image varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  description text COLLATE utf8mb4_unicode_ci,
  is_active tinyint(1) NOT NULL DEFAULT '1',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
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
  instansi_id bigint UNSIGNED DEFAULT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  value text COLLATE utf8mb4_unicode_ci NOT NULL,
  type varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  category varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  description text COLLATE utf8mb4_unicode_ci,
  is_public tinyint(1) NOT NULL DEFAULT '0',
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE subscriptions (
  id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  package_id bigint UNSIGNED NOT NULL,
  status enum('pending_verification','active','inactive','expired','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending_verification',
  effective_date date DEFAULT NULL,
  trial_ends_at date DEFAULT NULL,
  is_trial tinyint(1) NOT NULL DEFAULT '0',
  start_date date NOT NULL,
  end_date date NOT NULL,
  price decimal(10,2) NOT NULL,
  override_features text COLLATE utf8mb4_unicode_ci,
  payment_method varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer',
  notes text COLLATE utf8mb4_unicode_ci,
  cancelled_at datetime DEFAULT NULL,
  cancel_reason text COLLATE utf8mb4_unicode_ci,
  discount_amount decimal(10,2) DEFAULT '0.00',
  discount_id int DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE subscription_requests (
  id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  package_id bigint UNSIGNED NOT NULL,
  subscription_id bigint UNSIGNED DEFAULT NULL,
  amount decimal(15,2) NOT NULL,
  extension_months int DEFAULT NULL,
  payment_method varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  payment_status enum('pending','paid','expired','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  processed_at timestamp NULL DEFAULT NULL,
  transaction_id varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  notes text COLLATE utf8mb4_unicode_ci,
  created_by bigint UNSIGNED DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  payment_method_id bigint UNSIGNED DEFAULT NULL,
  payment_proof varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  payment_proof_uploaded_at timestamp NULL DEFAULT NULL,
  target_package_id bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE support_requests (
  id bigint UNSIGNED NOT NULL,
  instansi_id bigint UNSIGNED NOT NULL,
  requested_by bigint UNSIGNED NOT NULL,
  subject varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  message text COLLATE utf8mb4_unicode_ci NOT NULL,
  status enum('open','in_progress','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  priority enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  admin_notes text COLLATE utf8mb4_unicode_ci,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE users (
  id bigint UNSIGNED NOT NULL,
  name varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  email varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  phone varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  avatar varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  email_verified_at timestamp NULL DEFAULT NULL,
  password varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  remember_token varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  created_at timestamp NULL DEFAULT NULL,
  updated_at timestamp NULL DEFAULT NULL,
  role enum('superadmin','admin','employee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  instansi_id bigint UNSIGNED DEFAULT NULL,
  deleted_at timestamp NULL DEFAULT NULL,
  deleted_by bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE attendances
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY attendances_user_id_attendance_date_unique (user_id,attendance_date),
  ADD KEY attendances_approved_by_foreign (approved_by),
  ADD KEY attendances_user_id_attendance_date_index (user_id,attendance_date),
  ADD KEY attendances_branch_id_index (branch_id),
  ADD KEY attendances_attendance_date_index (attendance_date),
  ADD KEY attendances_user_id_status_attendance_date_index (user_id,status,attendance_date),
  ADD KEY attendances_branch_id_attendance_date_index (branch_id,attendance_date),
  ADD KEY attendances_status_attendance_date_index (status,attendance_date);

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

ALTER TABLE employees
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY employees_employee_id_unique (employee_id),
  ADD KEY employees_user_id_foreign (user_id),
  ADD KEY employees_instansi_id_foreign (instansi_id),
  ADD KEY employees_branch_id_foreign (branch_id),
  ADD KEY employees_deleted_by_foreign (deleted_by);

ALTER TABLE employee_policies
  ADD PRIMARY KEY (id),
  ADD KEY employee_policies_employee_id_is_active_index (employee_id,is_active),
  ADD KEY employee_policies_instansi_id_index (instansi_id),
  ADD KEY employee_policies_effective_from_effective_until_index (effective_from,effective_until);

ALTER TABLE employee_schedules
  ADD PRIMARY KEY (id),
  ADD KEY employee_schedules_user_id_foreign (user_id),
  ADD KEY employee_schedules_policy_id_foreign (policy_id);

ALTER TABLE failed_jobs
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY failed_jobs_uuid_unique (uuid);

ALTER TABLE instansis
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY instansis_subdomain_unique (subdomain),
  ADD KEY instansis_archived_by_foreign (archived_by),
  ADD KEY instansis_deleted_by_foreign (deleted_by),
  ADD KEY instansis_package_id_foreign (package_id);

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
  ADD KEY leaves_status_index (status),
  ADD KEY leaves_user_id_status_start_date_index (user_id,status,start_date);

ALTER TABLE migrations
  ADD PRIMARY KEY (id);

ALTER TABLE notifications
  ADD PRIMARY KEY (id),
  ADD KEY notifications_user_id_foreign (user_id);

ALTER TABLE packages
  ADD PRIMARY KEY (id);

ALTER TABLE password_reset_tokens
  ADD PRIMARY KEY (email);

ALTER TABLE payment_methods
  ADD PRIMARY KEY (id);

ALTER TABLE payrolls
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY payrolls_user_id_period_year_period_month_unique (user_id,period_year,period_month),
  ADD KEY payrolls_created_by_foreign (created_by),
  ADD KEY payrolls_period_year_period_month_index (period_year,period_month),
  ADD KEY payrolls_user_id_index (user_id),
  ADD KEY payrolls_payment_status_period_year_period_month_index (payment_status,period_year,period_month);

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
  ADD UNIQUE KEY settings_key_unique (`key`);

ALTER TABLE subscriptions
  ADD PRIMARY KEY (id),
  ADD KEY subscriptions_instansi_id_foreign (instansi_id),
  ADD KEY subscriptions_package_id_foreign (package_id);

ALTER TABLE subscription_requests
  ADD PRIMARY KEY (id),
  ADD KEY subscription_history_package_id_foreign (package_id),
  ADD KEY subscription_history_created_by_foreign (created_by),
  ADD KEY subscription_history_company_id_foreign (instansi_id),
  ADD KEY payment_history_subscription_id_foreign (subscription_id),
  ADD KEY subscription_requests_payment_method_id_foreign (payment_method_id),
  ADD KEY subscription_requests_target_package_id_foreign (target_package_id);

ALTER TABLE support_requests
  ADD PRIMARY KEY (id),
  ADD KEY support_requests_requested_by_foreign (requested_by),
  ADD KEY support_requests_instansi_id_status_index (instansi_id,status);

ALTER TABLE users
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY users_email_unique (email),
  ADD KEY users_instansi_id_foreign (instansi_id),
  ADD KEY users_deleted_by_foreign (deleted_by);


ALTER TABLE attendances
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE attendance_policies
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE branches
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE employees
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE employee_policies
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

ALTER TABLE payment_methods
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE payrolls
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE qr_codes
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE settings
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE subscriptions
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE subscription_requests
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE support_requests
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE users
  MODIFY id bigint UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE attendances
  ADD CONSTRAINT attendances_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT attendances_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES branches (id) ON DELETE CASCADE,
  ADD CONSTRAINT attendances_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE attendance_policies
  ADD CONSTRAINT attendance_policies_company_id_foreign FOREIGN KEY (company_id) REFERENCES instansis (id) ON DELETE CASCADE;

ALTER TABLE branches
  ADD CONSTRAINT branches_company_id_foreign FOREIGN KEY (company_id) REFERENCES instansis (id) ON DELETE CASCADE;

ALTER TABLE employees
  ADD CONSTRAINT employees_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES branches (id) ON DELETE SET NULL,
  ADD CONSTRAINT employees_deleted_by_foreign FOREIGN KEY (deleted_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT employees_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE,
  ADD CONSTRAINT employees_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE employee_policies
  ADD CONSTRAINT employee_policies_employee_id_foreign FOREIGN KEY (employee_id) REFERENCES users (id) ON DELETE CASCADE,
  ADD CONSTRAINT employee_policies_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE;

ALTER TABLE employee_schedules
  ADD CONSTRAINT employee_schedules_policy_id_foreign FOREIGN KEY (policy_id) REFERENCES attendance_policies (id) ON DELETE CASCADE,
  ADD CONSTRAINT employee_schedules_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE instansis
  ADD CONSTRAINT instansis_archived_by_foreign FOREIGN KEY (archived_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT instansis_deleted_by_foreign FOREIGN KEY (deleted_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT instansis_package_id_foreign FOREIGN KEY (package_id) REFERENCES packages (id) ON DELETE SET NULL;

ALTER TABLE leaves
  ADD CONSTRAINT leaves_approved_by_foreign FOREIGN KEY (approved_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT leaves_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE notifications
  ADD CONSTRAINT notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL;

ALTER TABLE payrolls
  ADD CONSTRAINT payrolls_created_by_foreign FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT payrolls_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE qr_codes
  ADD CONSTRAINT qr_codes_branch_id_foreign FOREIGN KEY (branch_id) REFERENCES branches (id) ON DELETE CASCADE;

ALTER TABLE subscriptions
  ADD CONSTRAINT subscriptions_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE,
  ADD CONSTRAINT subscriptions_package_id_foreign FOREIGN KEY (package_id) REFERENCES packages (id) ON DELETE CASCADE;

ALTER TABLE subscription_requests
  ADD CONSTRAINT payment_history_subscription_id_foreign FOREIGN KEY (subscription_id) REFERENCES subscriptions (id) ON DELETE CASCADE,
  ADD CONSTRAINT subscription_history_company_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE,
  ADD CONSTRAINT subscription_history_created_by_foreign FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT subscription_history_package_id_foreign FOREIGN KEY (package_id) REFERENCES packages (id) ON DELETE CASCADE,
  ADD CONSTRAINT subscription_requests_payment_method_id_foreign FOREIGN KEY (payment_method_id) REFERENCES payment_methods (id) ON DELETE SET NULL,
  ADD CONSTRAINT subscription_requests_target_package_id_foreign FOREIGN KEY (target_package_id) REFERENCES packages (id) ON DELETE SET NULL;

ALTER TABLE support_requests
  ADD CONSTRAINT support_requests_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE CASCADE,
  ADD CONSTRAINT support_requests_requested_by_foreign FOREIGN KEY (requested_by) REFERENCES users (id) ON DELETE CASCADE;

ALTER TABLE users
  ADD CONSTRAINT users_deleted_by_foreign FOREIGN KEY (deleted_by) REFERENCES users (id) ON DELETE SET NULL,
  ADD CONSTRAINT users_instansi_id_foreign FOREIGN KEY (instansi_id) REFERENCES instansis (id) ON DELETE SET NULL;

-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: hagaplus
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL,
  `log_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instansi_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,'attendance','User checked in via QR Code','App\\Models\\Attendance',1,'App\\Models\\User',6,'{\"method\": \"qr\", \"location\": \"-6.208763,106.845599\"}','check_in',NULL,1,'192.168.1.100','HagaPlus Mobile App v1.0','2025-09-16 01:55:00','2025-09-16 01:55:00'),(2,'attendance','User checked out via QR Code','App\\Models\\Attendance',1,'App\\Models\\User',6,'{\"method\": \"qr\", \"work_duration\": 555}','check_out',NULL,1,'192.168.1.100','HagaPlus Mobile App v1.0','2025-09-16 11:10:00','2025-09-16 11:10:00'),(3,'leave','Leave request submitted','App\\Models\\Leave',1,'App\\Models\\User',6,'{\"days\": 3, \"type\": \"annual\", \"reason\": \"Liburan keluarga ke Bali\"}','created',NULL,1,'192.168.1.105','Mozilla/5.0 Chrome/91.0','2025-09-15 04:02:53','2025-09-15 04:02:53'),(4,'leave','Leave request approved','App\\Models\\Leave',1,'App\\Models\\User',2,'{\"status\": \"approved\", \"approved_by\": \"Budi Santoso\"}','approved',NULL,1,'192.168.1.50','Mozilla/5.0 Chrome/91.0','2025-09-20 04:02:53','2025-09-20 04:02:53'),(5,'user','New employee registered','App\\Models\\User',6,'App\\Models\\User',2,'{\"name\": \"Andi Pratama\", \"position\": \"Software Engineer\"}','created',NULL,1,'192.168.1.50','Mozilla/5.0 Chrome/91.0','2025-01-15 04:02:53','2025-01-15 04:02:53'),(6,'attendance','User checked in via GPS','App\\Models\\Attendance',2,'App\\Models\\User',6,'{\"method\": \"gps\", \"location\": \"-6.208763,106.845599\"}','check_in',NULL,1,'192.168.1.100','HagaPlus Mobile App v1.0','2025-09-17 02:20:00','2025-09-17 02:20:00'),(7,'payroll','Payroll processed for September 2024','App\\Models\\Payroll',1,'App\\Models\\User',2,'{\"period\": \"2024-09\", \"net_salary\": 9920000}','processed',NULL,1,'192.168.1.50','Mozilla/5.0 Chrome/91.0','2024-10-05 03:00:00','2024-10-05 03:00:00'),(1,'attendance','User checked in via QR Code','App\\Models\\Attendance',1,'App\\Models\\User',6,'{\"method\": \"qr\", \"location\": \"-6.208763,106.845599\"}','check_in',NULL,1,'192.168.1.100','HagaPlus Mobile App v1.0','2025-09-15 18:55:00','2025-09-15 18:55:00'),(2,'attendance','User checked out via QR Code','App\\Models\\Attendance',1,'App\\Models\\User',6,'{\"method\": \"qr\", \"work_duration\": 555}','check_out',NULL,1,'192.168.1.100','HagaPlus Mobile App v1.0','2025-09-16 04:10:00','2025-09-16 04:10:00'),(3,'leave','Leave request submitted','App\\Models\\Leave',1,'App\\Models\\User',6,'{\"days\": 3, \"type\": \"annual\", \"reason\": \"Liburan keluarga ke Bali\"}','created',NULL,1,'192.168.1.105','Mozilla/5.0 Chrome/91.0','2025-09-14 21:02:53','2025-09-14 21:02:53'),(4,'leave','Leave request approved','App\\Models\\Leave',1,'App\\Models\\User',2,'{\"status\": \"approved\", \"approved_by\": \"Budi Santoso\"}','approved',NULL,1,'192.168.1.50','Mozilla/5.0 Chrome/91.0','2025-09-19 21:02:53','2025-09-19 21:02:53'),(5,'user','New employee registered','App\\Models\\User',6,'App\\Models\\User',2,'{\"name\": \"Andi Pratama\", \"position\": \"Software Engineer\"}','created',NULL,1,'192.168.1.50','Mozilla/5.0 Chrome/91.0','2025-01-14 21:02:53','2025-01-14 21:02:53'),(6,'attendance','User checked in via GPS','App\\Models\\Attendance',2,'App\\Models\\User',6,'{\"method\": \"gps\", \"location\": \"-6.208763,106.845599\"}','check_in',NULL,1,'192.168.1.100','HagaPlus Mobile App v1.0','2025-09-16 19:20:00','2025-09-16 19:20:00'),(7,'payroll','Payroll processed for September 2024','App\\Models\\Payroll',1,'App\\Models\\User',2,'{\"period\": \"2024-09\", \"net_salary\": 9920000}','processed',NULL,1,'192.168.1.50','Mozilla/5.0 Chrome/91.0','2024-10-04 20:00:00','2024-10-04 20:00:00');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance_policies`
--

DROP TABLE IF EXISTS `attendance_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance_policies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendance_policies_company_id_foreign` (`company_id`),
  CONSTRAINT `attendance_policies_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance_policies`
--

LOCK TABLES `attendance_policies` WRITE;
/*!40000 ALTER TABLE `attendance_policies` DISABLE KEYS */;
INSERT INTO `attendance_policies` VALUES (1,1,'Kebijakan Standar Office','[1, 2, 3, 4, 5]','09:00:00','18:00:00',60,15,15,480,'[\"qr\", \"gps\", \"face_id\"]',0,NULL,1,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(2,2,'Creative Team Schedule','[1, 2, 3, 4, 5]','10:00:00','19:00:00',90,20,20,540,'[\"qr\", \"gps\", \"face_id\"]',0,NULL,1,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(3,3,'Retail Store Hours','[1, 2, 3, 4, 5, 6]','08:00:00','17:00:00',60,10,10,540,'[\"qr\", \"gps\"]',1,'17:30:00',1,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(4,4,'Manufacturing Shift A','[1, 2, 3, 4, 5, 6]','06:00:00','14:00:00',30,5,5,480,'[\"qr\", \"gps\"]',0,NULL,1,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(5,4,'Manufacturing Shift B','[1, 2, 3, 4, 5, 6]','14:00:00','22:00:00',30,5,5,480,'[\"qr\", \"gps\"]',0,NULL,0,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(6,4,'Manufacturing Shift C','[1, 2, 3, 4, 5, 6]','22:00:00','06:00:00',30,5,5,480,'[\"qr\", \"gps\"]',0,NULL,0,1,'2025-09-24 21:43:22','2025-09-24 21:43:22');
/*!40000 ALTER TABLE `attendance_policies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
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
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_user_id_attendance_date_unique` (`user_id`,`attendance_date`),
  KEY `attendances_approved_by_foreign` (`approved_by`),
  KEY `attendances_user_id_attendance_date_index` (`user_id`,`attendance_date`),
  KEY `attendances_branch_id_index` (`branch_id`),
  KEY `attendances_attendance_date_index` (`attendance_date`),
  KEY `attendances_user_id_status_attendance_date_index` (`user_id`,`status`,`attendance_date`),
  KEY `attendances_branch_id_attendance_date_index` (`branch_id`,`attendance_date`),
  KEY `attendances_status_attendance_date_index` (`status`,`attendance_date`),
  CONSTRAINT `attendances_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attendances_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
INSERT INTO `attendances` VALUES (1,6,1,'2025-09-19','2025-09-18 18:55:00','2025-09-19 04:10:00','qr','qr','-6.208763,106.845599',NULL,NULL,NULL,555,60,75,0,0,'present','Hadir tepat waktu',NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(2,6,1,'2025-09-20','2025-09-19 19:20:00','2025-09-20 04:05:00','gps','gps','-6.208763,106.845599',NULL,NULL,NULL,525,60,45,20,0,'late','Terlambat karena macet',NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(3,6,1,'2025-09-21','2025-09-20 18:45:00','2025-09-21 04:15:00','face_id','face_id','-6.208763,106.845599',NULL,NULL,NULL,570,60,90,0,0,'present','Kerja lembur',NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(4,6,1,'2025-09-22','2025-09-21 19:00:00','2025-09-22 04:00:00','qr','qr','-6.208763,106.845599',NULL,NULL,NULL,540,60,60,0,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(5,6,1,'2025-09-23','2025-09-22 18:50:00','2025-09-23 03:55:00','gps','gps','-6.208763,106.845599',NULL,NULL,NULL,545,60,65,0,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(6,6,1,'2025-09-24','2025-09-23 19:10:00','2025-09-24 04:20:00','face_id','face_id','-6.208763,106.845599',NULL,NULL,NULL,550,60,70,10,0,'late','Sedikit terlambat',NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(7,7,1,'2025-09-19','2025-09-18 18:58:00','2025-09-19 04:05:00','qr','qr','-6.208763,106.845599',NULL,NULL,NULL,547,60,67,0,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(8,7,1,'2025-09-20','2025-09-19 19:05:00','2025-09-20 04:10:00','gps','gps','-6.208763,106.845599',NULL,NULL,NULL,545,60,65,5,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(9,7,1,'2025-09-21','2025-09-20 18:55:00','2025-09-21 04:00:00','face_id','face_id','-6.208763,106.845599',NULL,NULL,NULL,545,60,65,0,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(10,7,1,'2025-09-22','2025-09-21 19:15:00','2025-09-22 04:15:00','qr','qr','-6.208763,106.845599',NULL,NULL,NULL,540,60,60,15,0,'late','Meeting pagi',NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(11,7,1,'2025-09-23','2025-09-22 18:50:00','2025-09-23 03:50:00','gps','gps','-6.208763,106.845599',NULL,NULL,NULL,540,60,60,0,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(12,14,4,'2025-09-19','2025-09-18 20:05:00','2025-09-19 05:10:00','qr','qr','-6.921831,107.607147',NULL,NULL,NULL,545,90,65,5,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(13,14,4,'2025-09-20','2025-09-19 19:55:00','2025-09-20 05:05:00','gps','gps','-6.921831,107.607147',NULL,NULL,NULL,550,90,70,0,0,'present','Datang lebih awal',NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(14,14,4,'2025-09-21','2025-09-20 20:20:00','2025-09-21 05:25:00','face_id','face_id','-6.921831,107.607147',NULL,NULL,NULL,545,90,65,20,0,'late','Meeting client pagi',NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(15,14,4,'2025-09-22','2025-09-21 20:00:00','2025-09-22 05:00:00','qr','qr','-6.921831,107.607147',NULL,NULL,NULL,540,90,60,0,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(16,19,6,'2025-09-19','2025-09-18 17:58:00','2025-09-19 03:02:00','qr','qr','-7.257472,112.752088',NULL,NULL,NULL,544,60,64,0,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(17,19,6,'2025-09-20','2025-09-19 18:05:00','2025-09-20 03:05:00','gps','gps','-7.257472,112.752088',NULL,NULL,NULL,540,60,60,5,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(18,19,6,'2025-09-21','2025-09-20 18:12:00','2025-09-21 03:12:00','qr','qr','-7.257472,112.752088',NULL,NULL,NULL,540,60,60,12,0,'late','Kendaraan mogok',NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(19,23,7,'2025-09-19','2025-09-18 15:58:00','2025-09-19 00:02:00','qr','qr','-6.296406,107.154808',NULL,NULL,NULL,484,30,4,0,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(20,23,7,'2025-09-20','2025-09-19 16:03:00','2025-09-20 00:05:00','gps','gps','-6.296406,107.154808',NULL,NULL,NULL,482,30,2,3,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(21,23,7,'2025-09-21','2025-09-20 16:08:00','2025-09-21 00:10:00','qr','qr','-6.296406,107.154808',NULL,NULL,NULL,482,30,2,8,0,'late','Bangun kesiangan',NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(22,23,7,'2025-09-22','2025-09-21 15:55:00','2025-09-22 00:15:00','gps','gps','-6.296406,107.154808',NULL,NULL,NULL,500,30,20,0,0,'present','Lembur produksi',NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(23,28,7,'2025-09-19','2025-09-18 23:58:00','2025-09-19 08:02:00','qr','qr','-6.296406,107.154808',NULL,NULL,NULL,484,30,4,0,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(24,28,7,'2025-09-20','2025-09-20 00:05:00','2025-09-20 08:05:00','gps','gps','-6.296406,107.154808',NULL,NULL,NULL,480,30,0,5,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(25,28,7,'2025-09-21','2025-09-21 00:02:00','2025-09-21 08:10:00','qr','qr','-6.296406,107.154808',NULL,NULL,NULL,488,30,8,2,0,'present',NULL,NULL,NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(26,8,2,'2025-09-16','2025-09-15 18:45:00','2025-09-16 04:30:00','qr','qr','-6.238270,107.001567',NULL,NULL,NULL,585,60,105,0,0,'present','Project deadline',NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(27,9,2,'2025-09-16','2025-09-15 18:55:00','2025-09-16 03:45:00','gps','gps','-6.238270,107.001567',NULL,NULL,NULL,530,60,50,0,0,'present',NULL,NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(28,10,3,'2025-09-16','2025-09-15 19:10:00','2025-09-16 04:05:00','face_id','face_id','-6.297524,106.718124',NULL,NULL,NULL,535,60,55,10,0,'late','Traffic jam',NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(29,11,3,'2025-09-16','2025-09-15 18:50:00','2025-09-16 03:50:00','qr','qr','-6.297524,106.718124',NULL,NULL,NULL,540,60,60,0,0,'present',NULL,NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(30,12,1,'2025-09-16','2025-09-15 19:05:00','2025-09-16 04:15:00','qr','qr','-6.208763,106.845599',NULL,NULL,NULL,550,60,70,5,0,'present',NULL,NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(31,15,4,'2025-09-16','2025-09-15 19:50:00','2025-09-16 05:00:00','gps','gps','-6.921831,107.607147',NULL,NULL,NULL,550,90,70,0,0,'present',NULL,NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(32,16,5,'2025-09-16','2025-09-15 20:15:00','2025-09-16 05:10:00','face_id','face_id','-6.895562,107.613144',NULL,NULL,NULL,535,90,55,15,0,'late','Client meeting',NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(33,17,5,'2025-09-16','2025-09-15 20:00:00','2025-09-16 05:05:00','qr','qr','-6.895562,107.613144',NULL,NULL,NULL,545,90,65,0,0,'present',NULL,NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(34,18,4,'2025-09-16','2025-09-15 19:55:00','2025-09-16 05:15:00','gps','gps','-6.921831,107.607147',NULL,NULL,NULL,560,90,80,0,0,'present','Bug fixing',NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(35,20,6,'2025-09-16','2025-09-15 18:02:00','2025-09-16 03:03:00','qr','qr','-7.257472,112.752088',NULL,NULL,NULL,541,60,61,2,0,'present',NULL,NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(36,21,6,'2025-09-16','2025-09-15 17:55:00','2025-09-16 03:10:00','gps','gps','-7.257472,112.752088',NULL,NULL,NULL,555,60,75,0,0,'present','Store opening preparation',NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(37,22,6,'2025-09-16','2025-09-15 18:10:00','2025-09-16 03:15:00','qr','qr','-7.257472,112.752088',NULL,NULL,NULL,545,60,65,10,0,'late','Inventory check',NULL,NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53');
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `backup_logs`
--

DROP TABLE IF EXISTS `backup_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `backup_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `backup_logs_instansi_id_backup_type_status_index` (`instansi_id`,`backup_type`,`status`),
  KEY `backup_logs_started_at_index` (`started_at`),
  CONSTRAINT `backup_logs_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `backup_logs`
--

LOCK TABLES `backup_logs` WRITE;
/*!40000 ALTER TABLE `backup_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `backup_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `radius` int NOT NULL DEFAULT '100',
  `timezone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Asia/Jakarta',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `branches_company_id_foreign` (`company_id`),
  CONSTRAINT `branches_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (1,1,'Kantor Pusat Jakarta','Jl. Sudirman No. 123, Jakarta Pusat',-6.20876300,106.84559900,100,'Asia/Jakarta',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(2,1,'Cabang Bekasi','Jl. Ahmad Yani No. 67, Bekasi Timur',-6.23827000,107.00156700,150,'Asia/Jakarta',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(3,1,'Cabang Tangerang','Jl. Imam Bonjol No. 34, Tangerang Selatan',-6.29752400,106.71812400,120,'Asia/Jakarta',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(4,2,'Kantor Pusat Bandung','Jl. Asia Afrika No. 45, Bandung',-6.92183100,107.60714700,80,'Asia/Jakarta',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(5,2,'Co-working Space Dago','Jl. Ir. H. Juanda No. 123, Bandung',-6.89556200,107.61314400,50,'Asia/Jakarta',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(6,3,'Toko Utama','Jl. Diponegoro No. 88, Surabaya',-7.25747200,112.75208800,75,'Asia/Jakarta',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(7,4,'Pabrik Utama Cikarang','Kawasan Industri Jababeka Blok A-1, Cikarang',-6.29640600,107.15480800,300,'Asia/Jakarta',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(8,4,'Gudang Distribusi Karawang','Jl. Raya Karawang-Jakarta KM 45, Karawang',-6.30120600,107.30780900,200,'Asia/Jakarta',1,'2025-09-24 21:43:22','2025-09-24 21:43:22');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `company_themes`
--

DROP TABLE IF EXISTS `company_themes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `company_themes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `primary_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#3b82f6',
  `secondary_color` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#64748b',
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `custom_css` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `company_themes_company_id_foreign` (`company_id`),
  CONSTRAINT `company_themes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `company_themes`
--

LOCK TABLES `company_themes` WRITE;
/*!40000 ALTER TABLE `company_themes` DISABLE KEYS */;
INSERT INTO `company_themes` VALUES (1,1,'#3b82f6','#64748b','themes/company1/logo.png','themes/company1/favicon.ico','.navbar { background: linear-gradient(90deg, #3b82f6, #1e40af); }',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(2,2,'#8b5cf6','#a855f7','themes/company2/logo.png','themes/company2/favicon.ico','.header { border-bottom: 3px solid #8b5cf6; }',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(3,3,'#f59e0b','#d97706','themes/company3/logo.png','themes/company3/favicon.ico','.btn-primary { background-color: #f59e0b; border-color: #f59e0b; }',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(4,4,'#ef4444','#dc2626','themes/company4/logo.png','themes/company4/favicon.ico','.sidebar { background: linear-gradient(180deg, #ef4444, #dc2626); }',1,'2025-09-24 21:43:22','2025-09-24 21:43:22');
/*!40000 ALTER TABLE `company_themes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compliance_logs`
--

DROP TABLE IF EXISTS `compliance_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compliance_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned NOT NULL,
  `compliance_type` enum('data_retention','privacy_policy','gdpr','audit','security') COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `affected_data` json DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `compliance_logs_user_id_foreign` (`user_id`),
  KEY `compliance_logs_instansi_id_compliance_type_index` (`instansi_id`,`compliance_type`),
  KEY `compliance_logs_event_created_at_index` (`event`,`created_at`),
  CONSTRAINT `compliance_logs_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `compliance_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compliance_logs`
--

LOCK TABLES `compliance_logs` WRITE;
/*!40000 ALTER TABLE `compliance_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `compliance_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_deletion_requests`
--

DROP TABLE IF EXISTS `data_deletion_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_deletion_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned NOT NULL,
  `requested_by` bigint unsigned NOT NULL,
  `request_type` enum('employee_data','attendance_data','payroll_data','full_instansi','specific_period') COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_specification` json NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `scheduled_deletion_date` date NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `data_deletion_requests_requested_by_foreign` (`requested_by`),
  KEY `data_deletion_requests_approved_by_foreign` (`approved_by`),
  KEY `data_deletion_requests_instansi_id_status_index` (`instansi_id`,`status`),
  KEY `data_deletion_requests_scheduled_deletion_date_index` (`scheduled_deletion_date`),
  CONSTRAINT `data_deletion_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `data_deletion_requests_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `data_deletion_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_deletion_requests`
--

LOCK TABLES `data_deletion_requests` WRITE;
/*!40000 ALTER TABLE `data_deletion_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_deletion_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_exports`
--

DROP TABLE IF EXISTS `data_exports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `data_exports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned NOT NULL,
  `requested_by` bigint unsigned NOT NULL,
  `export_type` enum('attendance','payroll','employees','full_backup','custom') COLLATE utf8mb4_unicode_ci NOT NULL,
  `format` enum('csv','excel','pdf','json') COLLATE utf8mb4_unicode_ci NOT NULL,
  `filters` json DEFAULT NULL,
  `status` enum('pending','processing','completed','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` int DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  `error_message` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `data_exports_instansi_id_status_index` (`instansi_id`,`status`),
  KEY `data_exports_requested_by_created_at_index` (`requested_by`,`created_at`),
  CONSTRAINT `data_exports_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `data_exports_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_exports`
--

LOCK TABLES `data_exports` WRITE;
/*!40000 ALTER TABLE `data_exports` DISABLE KEYS */;
/*!40000 ALTER TABLE `data_exports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discount_usage`
--

DROP TABLE IF EXISTS `discount_usage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discount_usage` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `discount_id` bigint unsigned NOT NULL,
  `instansi_id` bigint unsigned NOT NULL,
  `subscription_id` bigint unsigned DEFAULT NULL,
  `original_amount` decimal(15,2) NOT NULL,
  `discount_amount` decimal(15,2) NOT NULL,
  `final_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discount_usage_instansi_id_foreign` (`instansi_id`),
  KEY `discount_usage_subscription_id_foreign` (`subscription_id`),
  KEY `discount_usage_discount_id_instansi_id_index` (`discount_id`,`instansi_id`),
  CONSTRAINT `discount_usage_discount_id_foreign` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `discount_usage_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `discount_usage_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discount_usage`
--

LOCK TABLES `discount_usage` WRITE;
/*!40000 ALTER TABLE `discount_usage` DISABLE KEYS */;
/*!40000 ALTER TABLE `discount_usage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `discounts_code_unique` (`code`),
  KEY `discounts_code_is_active_index` (`code`,`is_active`),
  KEY `discounts_valid_from_valid_until_index` (`valid_from`,`valid_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discounts`
--

LOCK TABLES `discounts` WRITE;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_schedules`
--

DROP TABLE IF EXISTS `employee_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `policy_id` bigint unsigned NOT NULL,
  `effective_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_schedules_user_id_foreign` (`user_id`),
  KEY `employee_schedules_policy_id_foreign` (`policy_id`),
  CONSTRAINT `employee_schedules_policy_id_foreign` FOREIGN KEY (`policy_id`) REFERENCES `attendance_policies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employee_schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_schedules`
--

LOCK TABLES `employee_schedules` WRITE;
/*!40000 ALTER TABLE `employee_schedules` DISABLE KEYS */;
INSERT INTO `employee_schedules` VALUES (1,6,1,'2023-01-15',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(2,7,1,'2023-02-20',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(3,8,1,'2022-11-10',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(4,9,1,'2023-03-05',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(5,10,1,'2023-01-28',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(6,11,1,'2023-04-12',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(7,12,1,'2023-05-17',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(8,13,1,'2023-06-22',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(9,14,2,'2023-02-10',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(10,15,2,'2023-03-15',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(11,16,2,'2023-04-20',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(12,17,2,'2023-01-08',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(13,18,2,'2023-05-03',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(14,19,3,'2022-08-15',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(15,20,3,'2023-01-20',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(16,21,3,'2022-05-10',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(17,22,3,'2023-03-12',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(18,23,4,'2022-09-01',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(19,24,4,'2022-10-15',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(20,25,4,'2023-01-03',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(21,26,4,'2022-07-20',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(22,27,4,'2023-02-14',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(23,28,5,'2023-04-05',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(24,29,5,'2023-03-18',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(25,30,4,'2023-06-01',NULL,1,'2025-09-24 21:43:22','2025-09-24 21:43:22');
/*!40000 ALTER TABLE `employee_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `instansi_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `employee_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `hire_date` date NOT NULL,
  `status` enum('active','inactive','terminated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_employee_id_unique` (`employee_id`),
  KEY `employees_user_id_foreign` (`user_id`),
  KEY `employees_instansi_id_foreign` (`instansi_id`),
  KEY `employees_branch_id_foreign` (`branch_id`),
  KEY `employees_deleted_by_foreign` (`deleted_by`),
  CONSTRAINT `employees_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (1,6,1,1,'TMB006','Software Engineer','Engineering',8500000.00,'2023-01-15','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(2,7,1,1,'TMB007','UI/UX Designer','Design',7500000.00,'2023-02-20','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(3,8,1,2,'TMB008','Project Manager','Project Management',12000000.00,'2022-11-10','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(4,9,1,2,'TMB009','Quality Assurance','Quality Control',6500000.00,'2023-03-05','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(5,10,1,3,'TMB010','DevOps Engineer','Infrastructure',9500000.00,'2023-01-28','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(6,11,1,3,'TMB011','Business Analyst','Business',8000000.00,'2023-04-12','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(7,12,1,1,'TMB012','Frontend Developer','Engineering',7800000.00,'2023-05-17','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(8,13,1,1,'TMB013','Marketing Specialist','Marketing',6800000.00,'2023-06-22','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(9,14,2,4,'KDS014','Web Developer','Development',7000000.00,'2023-02-10','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(10,15,2,4,'KDS015','Graphic Designer','Creative',6200000.00,'2023-03-15','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(11,16,2,5,'KDS016','Content Creator','Marketing',5800000.00,'2023-04-20','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(12,17,2,5,'KDS017','Digital Marketer','Marketing',6500000.00,'2023-01-08','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(13,18,2,4,'KDS018','Full Stack Developer','Development',8200000.00,'2023-05-03','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(14,19,3,6,'BJ019','Sales Associate','Sales',4200000.00,'2022-08-15','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(15,20,3,6,'BJ020','Cashier','Operations',3800000.00,'2023-01-20','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(16,21,3,6,'BJ021','Store Manager','Management',6500000.00,'2022-05-10','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(17,22,3,6,'BJ022','Inventory Staff','Operations',4000000.00,'2023-03-12','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(18,23,4,7,'IMN023','Production Operator','Production',5200000.00,'2022-09-01','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(19,24,4,7,'IMN024','Quality Control Specialist','Quality Control',6800000.00,'2022-10-15','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(20,25,4,8,'IMN025','Maintenance Technician','Maintenance',6200000.00,'2023-01-03','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(21,26,4,8,'IMN026','Production Supervisor','Production',8500000.00,'2022-07-20','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(22,27,4,7,'IMN027','Safety Officer','Safety',7200000.00,'2023-02-14','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(23,28,4,7,'IMN028','Warehouse Staff','Logistics',4800000.00,'2023-04-05','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(24,29,4,8,'IMN029','Machine Operator','Production',5500000.00,'2023-03-18','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL),(25,30,4,8,'IMN030','Admin Staff','Administration',4500000.00,'2023-06-01','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL);
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category` enum('attendance','payroll','reporting','integration','customization') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `config` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `features_slug_unique` (`slug`),
  KEY `features_category_is_active_index` (`category`,`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `features`
--

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
INSERT INTO `features` VALUES (1,'QR Code Attendance','qr_attendance','Absensi menggunakan QR Code','attendance',1,1,'{\"max_distance\": 10, \"refresh_interval\": 300}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(2,'GPS Attendance','gps_attendance','Absensi menggunakan lokasi GPS','attendance',1,2,'{\"radius_tolerance\": 100, \"accuracy_required\": 50}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(3,'Face Recognition','face_recognition','Absensi menggunakan pengenalan wajah','attendance',1,3,'{\"max_attempts\": 3, \"confidence_threshold\": 0.85}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(4,'Shift Management','shift_management','Manajemen jadwal shift karyawan','attendance',1,4,'{\"overlap_allowed\": false, \"max_shifts_per_day\": 3}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(5,'Leave Management','leave_management','Manajemen cuti karyawan','attendance',1,5,'{\"max_leave_days\": 12, \"approval_required\": true}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(6,'Payroll Processing','payroll_processing','Pemrosesan gaji karyawan','payroll',1,6,'{\"tax_enabled\": true, \"auto_calculate\": true}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(7,'Overtime Calculation','overtime_calculation','Perhitungan lembur otomatis','payroll',1,7,'{\"minimum_minutes\": 30, \"rate_multiplier\": 1.5}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(8,'Basic Reports','basic_reports','Laporan dasar absensi dan kehadiran','reporting',1,8,'{\"export_formats\": [\"pdf\", \"excel\"], \"schedule_enabled\": false}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(9,'Advanced Reports','advanced_reports','Laporan lanjutan dengan analitik','reporting',1,9,'{\"dashboard\": true, \"charts_enabled\": true, \"custom_filters\": true}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(10,'API Access','api_access','Akses API untuk integrasi','integration',1,10,'{\"rate_limit\": 1000, \"authentication\": \"oauth2\"}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(11,'Custom Branding','custom_branding','Kustomisasi tema dan branding','customization',1,11,'{\"logo_upload\": true, \"css_override\": true, \"color_schemes\": true}','2025-09-21 21:02:53','2025-09-21 21:02:53'),(12,'Multi Branch','multi_branch','Dukungan multiple cabang','attendance',1,12,'{\"max_branches\": 50, \"central_management\": true}','2025-09-21 21:02:53','2025-09-21 21:02:53');
/*!40000 ALTER TABLE `features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instansi_feature_overrides`
--

DROP TABLE IF EXISTS `instansi_feature_overrides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instansi_feature_overrides` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned NOT NULL,
  `feature_id` bigint unsigned NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  `custom_limits` json DEFAULT NULL,
  `custom_config` json DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `effective_from` date NOT NULL,
  `effective_until` date DEFAULT NULL,
  `applied_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `instansi_feature_override_unique` (`instansi_id`,`feature_id`,`effective_from`),
  KEY `instansi_feature_overrides_feature_id_foreign` (`feature_id`),
  KEY `instansi_feature_overrides_applied_by_foreign` (`applied_by`),
  KEY `instansi_feature_overrides_instansi_id_is_enabled_index` (`instansi_id`,`is_enabled`),
  CONSTRAINT `instansi_feature_overrides_applied_by_foreign` FOREIGN KEY (`applied_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `instansi_feature_overrides_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE,
  CONSTRAINT `instansi_feature_overrides_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instansi_feature_overrides`
--

LOCK TABLES `instansi_feature_overrides` WRITE;
/*!40000 ALTER TABLE `instansi_feature_overrides` DISABLE KEYS */;
/*!40000 ALTER TABLE `instansi_feature_overrides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instansi_subscription_addons`
--

DROP TABLE IF EXISTS `instansi_subscription_addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instansi_subscription_addons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned NOT NULL,
  `subscription_addon_id` bigint unsigned NOT NULL,
  `price_override` decimal(15,2) DEFAULT NULL,
  `active_from` date NOT NULL,
  `active_until` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `instansi_addon_unique` (`instansi_id`,`subscription_addon_id`),
  KEY `instansi_subscription_addons_subscription_addon_id_foreign` (`subscription_addon_id`),
  CONSTRAINT `instansi_subscription_addons_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `instansi_subscription_addons_subscription_addon_id_foreign` FOREIGN KEY (`subscription_addon_id`) REFERENCES `subscription_addons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instansi_subscription_addons`
--

LOCK TABLES `instansi_subscription_addons` WRITE;
/*!40000 ALTER TABLE `instansi_subscription_addons` DISABLE KEYS */;
/*!40000 ALTER TABLE `instansi_subscription_addons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `instansis`
--

DROP TABLE IF EXISTS `instansis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `instansis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
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
  `deleted_by` bigint unsigned DEFAULT NULL,
  `retention_policy` json DEFAULT NULL,
  `archived_at` timestamp NULL DEFAULT NULL,
  `archived_by` bigint unsigned DEFAULT NULL,
  `package_id` bigint unsigned DEFAULT NULL,
  `subscription_start` datetime DEFAULT NULL,
  `subscription_end` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `max_employees` int NOT NULL DEFAULT '10',
  `max_branches` int NOT NULL DEFAULT '1',
  `settings` json DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `instansis_subdomain_unique` (`subdomain`),
  KEY `instansis_archived_by_foreign` (`archived_by`),
  KEY `instansis_deleted_by_foreign` (`deleted_by`),
  KEY `instansis_package_id_foreign` (`package_id`),
  CONSTRAINT `instansis_archived_by_foreign` FOREIGN KEY (`archived_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `instansis_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `instansis_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `instansis`
--

LOCK TABLES `instansis` WRITE;
/*!40000 ALTER TABLE `instansis` DISABLE KEYS */;
INSERT INTO `instansis` VALUES (1,'PT Teknologi Maju Bersama','admin@teknologimaju.com','021-5555-0001','Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta',NULL,'teknologimaju','inactive','2025-09-24 21:43:22','2025-09-24 18:29:47',NULL,NULL,NULL,NULL,NULL,3,'2025-09-25 00:00:00','2025-10-25 00:00:00',1,200,20,'{\"currency\": \"IDR\", \"timezone\": \"Asia/Jakarta\", \"date_format\": \"d/m/Y\"}'),(2,'CV Kreatif Digital Solutions','hr@kreatifdigital.com','022-3333-0002','Jl. Asia Afrika No. 45, Bandung, Jawa Barat',NULL,'kreatifdigital','active','2025-09-24 21:43:22','2025-09-24 18:30:43',NULL,NULL,NULL,NULL,NULL,2,'2025-09-25 00:00:00','2025-10-25 00:00:00',1,50,5,'{\"timezone\": \"Asia/Jakarta\", \"working_hours\": \"10:00-19:00\"}'),(3,'Toko Berkah Jaya','owner@berkahjaya.com','031-7777-0003','Jl. Diponegoro No. 88, Surabaya, Jawa Timur',NULL,'berkahjaya','active','2025-09-24 21:43:22','2025-09-24 21:43:22',NULL,NULL,NULL,NULL,NULL,1,'2024-09-01 00:00:00','2024-10-01 23:59:59',1,10,1,'{\"timezone\": \"Asia/Jakarta\", \"working_days\": [1, 2, 3, 4, 5, 6]}'),(4,'PT Industri Manufaktur Nusantara','hrd@manufakturnusantara.co.id','024-9999-0004','Kawasan Industri Jababeka, Cikarang, Jawa Barat',NULL,'manufakturnusantara','active','2025-09-24 21:43:22','2025-09-24 15:42:36',NULL,NULL,NULL,NULL,NULL,4,'2024-07-01 00:00:00','2025-01-01 23:59:59',1,1000,50,'{\"timezone\": \"Asia/Jakarta\", \"shift_based\": true, \"overtime_enabled\": true}'),(5,'Klinik Sehat Sentosa',NULL,NULL,NULL,NULL,'kliniksehat','active','2025-09-24 21:43:22','2025-09-24 17:32:03',NULL,NULL,NULL,NULL,NULL,3,'2025-09-25 00:00:00','2025-10-25 00:00:00',0,200,20,NULL),(6,'PT Mora Cipta Solusi','mcs@gmail.com','0844346464',NULL,NULL,'mcs','active','2025-09-24 17:57:38','2025-09-24 17:57:38',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,10,1,NULL),(7,'CV Tiba Tiba','tiba@gmail.com','089529204605','unpam',NULL,'tibatiba','inactive','2025-09-24 18:43:18','2025-09-24 18:44:24',NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,1,10,1,NULL),(8,'Chopin','chopin@gmail.com','089529204605`','Tangerang Selatan',NULL,'chopin','active','2025-09-24 19:00:59','2025-09-24 19:00:59',NULL,NULL,NULL,NULL,NULL,5,NULL,NULL,1,5,1,NULL);
/*!40000 ALTER TABLE `instansis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leaves`
--

DROP TABLE IF EXISTS `leaves`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leaves` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `leave_type` enum('annual','sick','maternity','emergency','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_count` int NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leaves_approved_by_foreign` (`approved_by`),
  KEY `leaves_user_id_index` (`user_id`),
  KEY `leaves_start_date_end_date_index` (`start_date`,`end_date`),
  KEY `leaves_status_index` (`status`),
  KEY `leaves_user_id_status_start_date_index` (`user_id`,`status`,`start_date`),
  CONSTRAINT `leaves_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leaves_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaves`
--

LOCK TABLES `leaves` WRITE;
/*!40000 ALTER TABLE `leaves` DISABLE KEYS */;
INSERT INTO `leaves` VALUES (1,6,'annual','2024-10-15','2024-10-17',3,'Liburan keluarga ke Bali',NULL,'approved',2,'2025-09-22 21:43:22',NULL,'2025-09-17 21:43:22','2025-09-24 21:43:22'),(2,7,'sick','2024-09-20','2024-09-21',2,'Demam dan flu',NULL,'approved',2,'2025-09-09 21:43:22',NULL,'2025-09-04 21:43:22','2025-09-24 21:43:22'),(3,14,'annual','2024-11-01','2024-11-03',3,'Acara pernikahan saudara',NULL,'pending',NULL,NULL,NULL,'2025-09-21 21:43:22','2025-09-24 21:43:22'),(4,19,'emergency','2024-09-25','2024-09-25',1,'Anak sakit mendadak',NULL,'approved',4,'2025-09-14 21:43:22',NULL,'2025-09-12 21:43:22','2025-09-24 21:43:22'),(5,23,'annual','2024-10-20','2024-10-22',3,'Mudik lebaran haji',NULL,'rejected',5,'2025-09-19 21:43:22',NULL,'2025-09-16 21:43:22','2025-09-24 21:43:22'),(6,24,'sick','2024-10-01','2024-10-02',2,'Sakit punggung',NULL,'approved',5,'2025-09-06 21:43:22',NULL,'2025-09-04 21:43:22','2025-09-24 21:43:22'),(7,8,'annual','2024-11-10','2024-11-12',3,'Family vacation',NULL,'pending',NULL,NULL,NULL,'2025-09-19 21:02:53','2025-09-21 21:02:53'),(8,9,'sick','2024-09-28','2024-09-28',1,'Migraine headache',NULL,'approved',2,'2025-09-24 21:02:53',NULL,'2025-09-24 21:02:53','2025-09-21 21:02:53'),(9,15,'annual','2024-12-23','2024-12-25',3,'Christmas holiday',NULL,'approved',3,'2025-09-17 21:02:53',NULL,'2025-09-15 21:02:53','2025-09-21 21:02:53'),(10,16,'maternity','2024-11-15','2024-12-15',30,'Maternity leave',NULL,'approved',3,'2025-09-09 21:02:53',NULL,'2025-09-07 21:02:53','2025-09-21 21:02:53'),(11,20,'sick','2024-10-05','2024-10-06',2,'Food poisoning',NULL,'approved',4,'2025-09-21 21:02:53',NULL,'2025-09-19 21:02:53','2025-09-21 21:02:53'),(12,25,'emergency','2024-09-30','2024-09-30',1,'Family emergency',NULL,'approved',5,'2025-09-27 21:02:53',NULL,'2025-09-27 21:02:53','2025-09-21 21:02:53');
/*!40000 ALTER TABLE `leaves` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_09_21_090716_create_packages_table',1),(5,'2025_09_21_090717_create_companies_table',1),(6,'2025_09_21_090718_create_branches_table',1),(7,'2025_09_21_090719_create_attendance_policies_table',1),(8,'2025_09_21_090719_create_employee_schedules_table',1),(9,'2025_09_21_090720_create_attendances_table',1),(10,'2025_09_21_090721_create_leaves_table',1),(11,'2025_09_21_090722_create_payrolls_table',1),(12,'2025_09_21_090722_create_subscription_history_table',1),(13,'2025_09_21_090723_create_qr_codes_table',1),(14,'2025_09_21_090724_create_company_themes_table',1),(15,'2025_09_21_090724_create_notifications_table',1),(16,'2025_09_21_101018_add_additional_columns_to_users_table',1),(17,'2025_09_21_143227_create_instansis_table',1),(18,'2025_09_21_143235_create_subscriptions_table',1),(19,'2025_09_21_143246_create_employees_table',1),(20,'2025_09_21_143254_create_settings_table',1),(21,'2025_09_21_144455_add_role_to_users_table',1),(22,'2025_09_21_154818_add_deleted_at_to_instansi_table',1),(23,'2025_09_25_000001_create_package_change_requests_table',1),(24,'2025_09_25_000002_create_subscription_transitions_table',1),(25,'2025_09_25_000004_create_features_table',1),(26,'2025_09_25_000005_create_package_features_table',1),(27,'2025_09_25_000006_create_instansi_feature_overrides_table',1),(28,'2025_09_25_000007_create_data_exports_table',1),(29,'2025_09_25_000008_create_backup_logs_table',1),(30,'2025_09_25_000009_create_compliance_logs_table',1),(31,'2025_09_25_000010_create_data_deletion_requests_table',1),(32,'2025_09_25_000011_create_subscription_addons_table',1),(33,'2025_09_25_000012_create_instansi_subscription_addons_table',1),(34,'2025_09_25_000013_create_discounts_table',1),(35,'2025_09_25_000014_create_discount_usage_table',1),(36,'2025_09_25_000015_update_existing_tables',2),(37,'2025_09_25_000016_merge_companies_instansi_tables',2),(38,'2025_09_25_000016_update_subscription_statuses',2),(39,'2025_09_25_100000_create_support_requests_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,6,'Selamat Datang!','Selamat datang di sistem HagaPlus. Jangan lupa untuk selalu absen tepat waktu.','info',1,'2025-08-25 21:43:22','2025-09-24 21:43:22'),(2,6,'Pengajuan Cuti Disetujui','Pengajuan cuti Anda untuk tanggal 15-17 Oktober 2024 telah disetujui.','success',1,'2025-09-22 21:43:22','2025-09-24 21:43:22'),(3,7,'Reminder: Absen Hari Ini','Jangan lupa untuk melakukan absen masuk hari ini.','warning',0,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(4,14,'Pengajuan Cuti Menunggu Persetujuan','Pengajuan cuti Anda sedang dalam proses review oleh atasan.','info',0,'2025-09-21 21:43:22','2025-09-24 21:43:22'),(5,19,'Gaji Bulan September Sudah Dibayar','Gaji bulan September 2024 sebesar Rp 4,357,000 sudah ditransfer ke rekening Anda.','success',1,'2025-09-19 21:43:22','2025-09-24 21:43:22'),(6,23,'Pengajuan Cuti Ditolak','Pengajuan cuti Anda untuk tanggal 20-22 Oktober 2024 ditolak karena sedang masa peak production.','error',0,'2025-09-19 21:43:22','2025-09-24 21:43:22'),(7,2,'Karyawan Baru Terdaftar','Ada 3 karyawan baru yang telah terdaftar minggu ini.','info',1,'2025-09-17 21:43:22','2025-09-24 21:43:22'),(8,2,'Laporan Absensi Bulanan Siap','Laporan absensi bulan September 2024 sudah siap untuk didownload.','success',0,'2025-09-23 21:43:22','2025-09-24 21:43:22'),(9,3,'Subscription Akan Berakhir','Paket Business Anda akan berakhir pada tanggal 14 November 2024.','warning',0,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(10,4,'Ada Pengajuan Cuti Pending','Terdapat 1 pengajuan cuti yang menunggu persetujuan Anda.','warning',0,'2025-09-24 09:43:22','2025-09-24 21:43:22'),(11,5,'Pembayaran Gaji Berhasil','Pembayaran gaji bulan September untuk 8 karyawan telah berhasil diproses.','success',1,'2025-09-19 21:43:22','2025-09-24 21:43:22'),(12,1,'Instansi Baru Terdaftar','Ada 2 instansi baru yang mendaftar minggu ini.','info',1,'2025-09-17 21:43:22','2025-09-24 21:43:22'),(13,1,'Subscription Berakhir','Subscription untuk Klinik Sehat Sentosa telah berakhir.','warning',0,'2025-08-25 21:43:22','2025-09-24 21:43:22'),(14,1,'Permintaan Pendaftaran Instansi','Instansi baru \"CV Tiba Tiba\" mengajukan pendaftaran dan memilih paket ID 1.','info',0,'2025-09-24 18:43:18','2025-09-24 18:43:18'),(15,1,'Instansi Baru (Free Trial) Terdaftar','Instansi \"Chopin\" dibuat otomatis dengan paket Free dan admin lukman@biz.id.','info',0,'2025-09-24 19:00:59','2025-09-24 19:00:59');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package_change_requests`
--

DROP TABLE IF EXISTS `package_change_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `package_change_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned NOT NULL,
  `current_package_id` bigint unsigned NOT NULL,
  `requested_package_id` bigint unsigned NOT NULL,
  `type` enum('upgrade','downgrade') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `requested_effective_date` date NOT NULL,
  `prorate_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `requested_by` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_change_requests_current_package_id_foreign` (`current_package_id`),
  KEY `package_change_requests_requested_package_id_foreign` (`requested_package_id`),
  KEY `package_change_requests_requested_by_foreign` (`requested_by`),
  KEY `package_change_requests_approved_by_foreign` (`approved_by`),
  KEY `package_change_requests_instansi_id_status_index` (`instansi_id`,`status`),
  KEY `package_change_requests_requested_effective_date_index` (`requested_effective_date`),
  CONSTRAINT `package_change_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `package_change_requests_current_package_id_foreign` FOREIGN KEY (`current_package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `package_change_requests_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `package_change_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `package_change_requests_requested_package_id_foreign` FOREIGN KEY (`requested_package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package_change_requests`
--

LOCK TABLES `package_change_requests` WRITE;
/*!40000 ALTER TABLE `package_change_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `package_change_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `package_features`
--

DROP TABLE IF EXISTS `package_features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `package_features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `package_id` bigint unsigned NOT NULL,
  `feature_id` bigint unsigned NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `limits` json DEFAULT NULL,
  `config_override` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `package_feature_unique` (`package_id`,`feature_id`),
  KEY `package_features_feature_id_foreign` (`feature_id`),
  CONSTRAINT `package_features_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE,
  CONSTRAINT `package_features_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `package_features`
--

LOCK TABLES `package_features` WRITE;
/*!40000 ALTER TABLE `package_features` DISABLE KEYS */;
INSERT INTO `package_features` VALUES (1,1,1,1,'{\"max_employees\": 10}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(2,1,2,1,'{\"max_employees\": 10}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(3,1,8,1,'{\"exports_per_month\": 10}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(4,2,1,1,'{\"max_employees\": 50}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(5,2,2,1,'{\"max_employees\": 50}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(6,2,3,1,'{\"max_employees\": 50}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(7,2,4,1,'{\"max_shifts\": 5}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(8,2,5,1,'{\"approval_levels\": 2}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(9,2,6,1,'{\"auto_processing\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(10,2,8,1,'{\"exports_per_month\": 50}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(11,2,12,1,'{\"max_branches\": 5}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(12,3,1,1,'{\"max_employees\": 200}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(13,3,2,1,'{\"max_employees\": 200}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(14,3,3,1,'{\"max_employees\": 200}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(15,3,4,1,'{\"max_shifts\": 10}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(16,3,5,1,'{\"approval_levels\": 3}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(17,3,6,1,'{\"auto_processing\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(18,3,7,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(19,3,8,1,'{\"exports_per_month\": 200}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(20,3,9,1,'{\"custom_dashboards\": 5}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(21,3,10,1,'{\"requests_per_hour\": 5000}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(22,3,12,1,'{\"max_branches\": 20}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(23,4,1,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(24,4,2,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(25,4,3,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(26,4,4,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(27,4,5,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(28,4,6,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(29,4,7,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(30,4,8,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(31,4,9,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(32,4,10,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(33,4,11,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53'),(34,4,12,1,'{\"unlimited\": true}',NULL,'2025-09-21 21:02:53','2025-09-21 21:02:53');
/*!40000 ALTER TABLE `package_features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) NOT NULL,
  `duration_days` int NOT NULL DEFAULT '30',
  `max_employees` int NOT NULL DEFAULT '10',
  `max_branches` int NOT NULL DEFAULT '1',
  `features` json NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packages`
--

LOCK TABLES `packages` WRITE;
/*!40000 ALTER TABLE `packages` DISABLE KEYS */;
INSERT INTO `packages` VALUES (1,'Starter','Paket dasar untuk usaha kecil dengan fitur absen QR Code dan GPS',49000.00,30,10,1,'[\"qr\", \"gps\", \"basic_reports\"]',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(2,'Business','Paket lengkap untuk usaha menengah dengan multi cabang dan Face ID',149000.00,30,50,5,'[\"qr\", \"gps\", \"face_id\", \"shift_management\", \"leave_management\", \"payroll\"]',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(3,'Enterprise','Paket premium untuk perusahaan besar dengan fitur lengkap',299000.00,30,200,20,'[\"qr\", \"gps\", \"face_id\", \"shift_management\", \"leave_management\", \"payroll\", \"advanced_reports\", \"api_access\"]',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(4,'Corporate','Solusi enterprise dengan unlimited features',599000.00,30,1000,50,'[\"all_features\", \"unlimited_employees\", \"custom_integration\", \"dedicated_support\"]',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(5,'Free','Paket gratis percobaan dengan fitur terbatas',0.00,30,5,1,'[\"qr\", \"basic_reports\"]',1,'2025-09-24 19:00:59','2025-09-24 19:00:59');
/*!40000 ALTER TABLE `packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payrolls`
--

DROP TABLE IF EXISTS `payrolls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payrolls` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
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
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payrolls_user_id_period_year_period_month_unique` (`user_id`,`period_year`,`period_month`),
  KEY `payrolls_created_by_foreign` (`created_by`),
  KEY `payrolls_period_year_period_month_index` (`period_year`,`period_month`),
  KEY `payrolls_user_id_index` (`user_id`),
  KEY `payrolls_payment_status_period_year_period_month_index` (`payment_status`,`period_year`,`period_month`),
  CONSTRAINT `payrolls_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `payrolls_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payrolls`
--

LOCK TABLES `payrolls` WRITE;
/*!40000 ALTER TABLE `payrolls` DISABLE KEYS */;
INSERT INTO `payrolls` VALUES (1,6,2024,9,8500000.00,'{\"meal\": 300000, \"transport\": 500000, \"performance\": 850000}','{\"tax\": 425000, \"bpjs_kesehatan\": 85000, \"bpjs_ketenagakerjaan\": 170000}',450000.00,10600000.00,680000.00,9920000.00,'2024-10-05','paid',NULL,2,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(2,7,2024,9,7500000.00,'{\"meal\": 300000, \"transport\": 500000, \"performance\": 750000}','{\"tax\": 375000, \"bpjs_kesehatan\": 75000, \"bpjs_ketenagakerjaan\": 150000}',380000.00,9430000.00,600000.00,8830000.00,'2024-10-05','paid',NULL,2,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(3,8,2024,9,12000000.00,'{\"meal\": 300000, \"transport\": 500000, \"management\": 500000, \"performance\": 1200000}','{\"tax\": 600000, \"bpjs_kesehatan\": 120000, \"bpjs_ketenagakerjaan\": 240000}',520000.00,15020000.00,960000.00,14060000.00,'2024-10-05','paid',NULL,2,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(4,14,2024,9,7000000.00,'{\"internet\": 150000, \"creativity\": 400000, \"performance\": 700000}','{\"tax\": 350000, \"bpjs_kesehatan\": 70000, \"bpjs_ketenagakerjaan\": 140000}',320000.00,8570000.00,560000.00,8010000.00,'2024-10-05','paid',NULL,3,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(5,15,2024,9,6200000.00,'{\"internet\": 150000, \"creativity\": 400000, \"performance\": 620000}','{\"tax\": 310000, \"bpjs_kesehatan\": 62000, \"bpjs_ketenagakerjaan\": 124000}',280000.00,7650000.00,496000.00,7154000.00,'2024-10-05','paid',NULL,3,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(6,19,2024,9,4200000.00,'{\"attendance\": 200000, \"sales_incentive\": 84000}','{\"tax\": 210000, \"employee_fund\": 25000, \"bpjs_kesehatan\": 42000}',150000.00,4634000.00,277000.00,4357000.00,'2024-10-05','paid',NULL,4,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(7,20,2024,9,3800000.00,'{\"attendance\": 200000}','{\"tax\": 190000, \"employee_fund\": 25000, \"bpjs_kesehatan\": 38000}',120000.00,4120000.00,253000.00,3867000.00,'2024-10-05','paid',NULL,4,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(8,23,2024,9,5200000.00,'{\"shift\": 300000, \"safety\": 250000}','{\"tax\": 260000, \"cooperative\": 100000, \"bpjs_kesehatan\": 52000, \"bpjs_ketenagakerjaan\": 104000}',200000.00,5950000.00,516000.00,5434000.00,'2024-10-05','paid',NULL,5,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(9,24,2024,9,6800000.00,'{\"shift\": 300000, \"safety\": 250000, \"quality_bonus\": 340000}','{\"tax\": 340000, \"cooperative\": 100000, \"bpjs_kesehatan\": 68000, \"bpjs_ketenagakerjaan\": 136000}',280000.00,8970000.00,644000.00,8326000.00,'2024-10-05','paid',NULL,5,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(10,28,2024,9,4800000.00,'{\"shift\": 300000, \"safety\": 250000}','{\"tax\": 240000, \"cooperative\": 100000, \"bpjs_kesehatan\": 48000, \"bpjs_ketenagakerjaan\": 96000}',180000.00,5530000.00,484000.00,5046000.00,'2024-10-05','paid',NULL,5,'2025-09-24 21:43:22','2025-09-24 21:43:22');
/*!40000 ALTER TABLE `payrolls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qr_codes`
--

DROP TABLE IF EXISTS `qr_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qr_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_codes_code_unique` (`code`),
  KEY `qr_codes_branch_id_foreign` (`branch_id`),
  CONSTRAINT `qr_codes_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qr_codes`
--

LOCK TABLES `qr_codes` WRITE;
/*!40000 ALTER TABLE `qr_codes` DISABLE KEYS */;
INSERT INTO `qr_codes` VALUES (1,1,'QR_BRANCH_1_ABC123DEF456','2025-09-25 21:43:22',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(2,2,'QR_BRANCH_2_GHI789JKL012','2025-09-25 21:43:22',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(3,3,'QR_BRANCH_3_MNO345PQR678','2025-09-25 21:43:22',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(4,4,'QR_BRANCH_4_STU901VWX234','2025-09-25 21:43:22',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(5,5,'QR_BRANCH_5_YZA567BCD890','2025-09-25 21:43:22',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(6,6,'QR_BRANCH_6_EFG123HIJ456','2025-09-25 21:43:22',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(7,7,'QR_BRANCH_7_KLM789NOP012','2025-09-25 21:43:22',1,'2025-09-24 21:43:22','2025-09-24 21:43:22'),(8,8,'QR_BRANCH_8_QRS345TUV678','2025-09-25 21:43:22',1,'2025-09-24 21:43:22','2025-09-24 21:43:22');
/*!40000 ALTER TABLE `qr_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_instansi_id_key_unique` (`instansi_id`,`key`),
  CONSTRAINT `settings_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,1,'company_name','PT Teknologi Maju Bersama','string','2025-09-24 21:43:22','2025-09-24 21:43:22'),(2,1,'working_hours_start','09:00','string','2025-09-24 21:43:22','2025-09-24 21:43:22'),(3,1,'working_hours_end','18:00','string','2025-09-24 21:43:22','2025-09-24 21:43:22'),(4,1,'late_tolerance','15','integer','2025-09-24 21:43:22','2025-09-24 21:43:22'),(5,1,'overtime_enabled','true','boolean','2025-09-24 21:43:22','2025-09-24 21:43:22'),(6,1,'face_recognition_enabled','true','boolean','2025-09-24 21:43:22','2025-09-24 21:43:22'),(7,2,'company_name','CV Kreatif Digital Solutions','string','2025-09-24 21:43:22','2025-09-24 21:43:22'),(8,2,'working_hours_start','10:00','string','2025-09-24 21:43:22','2025-09-24 21:43:22'),(9,2,'working_hours_end','19:00','string','2025-09-24 21:43:22','2025-09-24 21:43:22'),(10,2,'late_tolerance','20','integer','2025-09-24 21:43:22','2025-09-24 21:43:22'),(11,2,'flexible_working','true','boolean','2025-09-24 21:43:22','2025-09-24 21:43:22'),(12,3,'company_name','Toko Berkah Jaya','string','2025-09-24 21:43:22','2025-09-24 21:43:22'),(13,3,'working_hours_start','08:00','string','2025-09-24 21:43:22','2025-09-24 21:43:22'),(14,3,'working_hours_end','17:00','string','2025-09-24 21:43:22','2025-09-24 21:43:22'),(15,3,'late_tolerance','10','integer','2025-09-24 21:43:22','2025-09-24 21:43:22'),(16,3,'auto_checkout','true','boolean','2025-09-24 21:43:22','2025-09-24 21:43:22'),(17,4,'company_name','PT Industri Manufaktur Nusantara','string','2025-09-24 21:43:22','2025-09-24 21:43:22'),(18,4,'shift_system','true','boolean','2025-09-24 21:43:22','2025-09-24 21:43:22'),(19,4,'safety_protocol_enabled','true','boolean','2025-09-24 21:43:22','2025-09-24 21:43:22'),(20,4,'late_tolerance','5','integer','2025-09-24 21:43:22','2025-09-24 21:43:22');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_addons`
--

DROP TABLE IF EXISTS `subscription_addons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_addons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(15,2) NOT NULL,
  `billing_cycle` enum('monthly','quarterly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL,
  `features` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_addons_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_addons`
--

LOCK TABLES `subscription_addons` WRITE;
/*!40000 ALTER TABLE `subscription_addons` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscription_addons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_history`
--

DROP TABLE IF EXISTS `subscription_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_history` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `company_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` enum('pending','paid','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscription_history_package_id_foreign` (`package_id`),
  KEY `subscription_history_created_by_foreign` (`created_by`),
  KEY `subscription_history_company_id_foreign` (`company_id`),
  CONSTRAINT `subscription_history_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_history_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subscription_history_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_history`
--

LOCK TABLES `subscription_history` WRITE;
/*!40000 ALTER TABLE `subscription_history` DISABLE KEYS */;
INSERT INTO `subscription_history` VALUES (1,1,3,'2024-08-01 00:00:00','2024-10-31 23:59:59',299000.00,'Bank Transfer','paid','TXN-TMB-240801-001','Pembayaran paket Enterprise untuk 3 bulan',1,'2024-07-31 20:00:00','2025-09-24 21:43:22'),(2,2,2,'2024-08-15 00:00:00','2024-11-14 23:59:59',149000.00,'Bank Transfer','paid','TXN-KDS-240815-001','Pembayaran paket Business untuk 3 bulan',1,'2024-08-15 00:30:00','2025-09-24 21:43:22'),(3,3,1,'2024-09-01 00:00:00','2024-10-01 23:59:59',49000.00,'Cash','paid','TXN-BJ-240901-001','Pembayaran paket Starter untuk 1 bulan',1,'2024-08-31 19:15:00','2025-09-24 21:43:22'),(4,4,4,'2024-07-01 00:00:00','2024-10-31 23:59:59',599000.00,'Bank Transfer','paid','TXN-IMN-240701-001','Pembayaran paket Corporate untuk 4 bulan',1,'2024-06-30 21:45:00','2025-09-24 21:43:22');
/*!40000 ALTER TABLE `subscription_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_transitions`
--

DROP TABLE IF EXISTS `subscription_transitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscription_transitions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned NOT NULL,
  `from_package_id` bigint unsigned NOT NULL,
  `to_package_id` bigint unsigned NOT NULL,
  `subscription_id` bigint unsigned NOT NULL,
  `transition_type` enum('upgrade','downgrade','renewal','new') COLLATE utf8mb4_unicode_ci NOT NULL,
  `effective_from` datetime NOT NULL,
  `effective_until` datetime DEFAULT NULL,
  `transition_amount` decimal(15,2) NOT NULL,
  `prorate_credit` decimal(15,2) NOT NULL DEFAULT '0.00',
  `feature_changes` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `processed_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscription_transitions_from_package_id_foreign` (`from_package_id`),
  KEY `subscription_transitions_to_package_id_foreign` (`to_package_id`),
  KEY `subscription_transitions_subscription_id_foreign` (`subscription_id`),
  KEY `subscription_transitions_processed_by_foreign` (`processed_by`),
  KEY `subscription_transitions_instansi_id_effective_from_index` (`instansi_id`,`effective_from`),
  KEY `subscription_transitions_transition_type_index` (`transition_type`),
  CONSTRAINT `subscription_transitions_from_package_id_foreign` FOREIGN KEY (`from_package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_transitions_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_transitions_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_transitions_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscription_transitions_to_package_id_foreign` FOREIGN KEY (`to_package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_transitions`
--

LOCK TABLES `subscription_transitions` WRITE;
/*!40000 ALTER TABLE `subscription_transitions` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscription_transitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subscriptions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned NOT NULL,
  `package_id` bigint unsigned NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_instansi_id_foreign` (`instansi_id`),
  KEY `subscriptions_package_id_foreign` (`package_id`),
  CONSTRAINT `subscriptions_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subscriptions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES (1,1,3,'inactive',NULL,NULL,0,'2025-09-25','2025-10-25',299000.00,0.00,NULL,'2025-09-24 21:43:22','2025-09-24 17:32:34'),(2,2,2,'active',NULL,NULL,0,'2025-08-25','2025-10-21',149000.00,0.00,NULL,'2025-09-24 21:43:22','2025-09-24 17:46:23'),(3,3,3,'active',NULL,NULL,0,'2024-09-01','2026-06-01',49000.00,0.00,NULL,'2025-09-24 21:43:22','2025-09-24 17:55:56'),(4,4,4,'active',NULL,NULL,0,'2024-07-01','2025-01-01',599000.00,0.00,NULL,'2025-09-24 21:43:22','2025-09-24 15:42:36'),(5,5,3,'active',NULL,NULL,0,'2025-09-25','2025-10-25',299000.00,0.00,NULL,'2025-09-24 21:43:22','2025-09-24 16:24:51'),(6,6,2,'active',NULL,NULL,0,'2025-09-25','2025-10-25',149000.00,0.00,NULL,'2025-09-24 18:11:57','2025-09-24 18:44:09'),(7,8,5,'active',NULL,'2025-10-25',1,'2025-09-25','2025-10-25',0.00,0.00,NULL,'2025-09-24 19:00:59','2025-09-24 19:00:59');
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_requests`
--

DROP TABLE IF EXISTS `support_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instansi_id` bigint unsigned NOT NULL,
  `requested_by` bigint unsigned NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('open','in_progress','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `priority` enum('low','normal','high','urgent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `support_requests_requested_by_foreign` (`requested_by`),
  KEY `support_requests_instansi_id_status_index` (`instansi_id`,`status`),
  CONSTRAINT `support_requests_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `support_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_requests`
--

LOCK TABLES `support_requests` WRITE;
/*!40000 ALTER TABLE `support_requests` DISABLE KEYS */;
INSERT INTO `support_requests` VALUES (1,1,2,'Masalah Face Recognition Tidak Akurat','Tim kami mengalami kesulitan dengan sistem face recognition. Tingkat akurasi rendah dan sering gagal mengenali karyawan. Mohon bantuan untuk setting yang lebih optimal.','in_progress','high','Tim teknis sedang melakukan adjustment pada confidence threshold. Target selesai 2 hari kerja.','2025-09-19 20:30:00','2025-09-22 00:20:00'),(2,1,6,'Tidak Bisa Checkout Via GPS','Saya tidak bisa melakukan checkout menggunakan GPS hari ini. Aplikasi menampilkan error \"Location not found\". Padahal GPS sudah aktif dan lokasi terdeteksi.','resolved','normal','Issue disebabkan oleh update Android. Sudah diperbaiki dengan patch v1.2.3. User diminta update aplikasi.','2025-09-21 02:45:00','2025-09-21 19:15:00'),(3,2,3,'Request Peningkatan Limit Karyawan','Perusahaan kami berkembang pesat dan sudah mencapai limit maksimal 50 karyawan pada paket Business. Mohon informasi untuk upgrade ke Enterprise atau penambahan slot karyawan.','resolved','normal','Customer sudah dibantu untuk upgrade ke paket Enterprise. Proses completed dan limit sudah bertambah menjadi 200 karyawan.','2025-09-17 21:20:00','2025-09-20 02:30:00'),(4,2,14,'QR Code Tidak Ter-refresh Otomatis','QR Code di kantor cabang Bandung tidak ter-refresh secara otomatis setiap 5 menit. Karyawan harus refresh manual browser untuk mendapat QR code baru.','open','normal',NULL,'2025-09-21 18:30:00','2025-09-21 18:30:00'),(5,3,4,'Laporan Payroll Tidak Sesuai','Hasil perhitungan payroll bulan September tidak sesuai dengan ekspektasi. Sepertinya ada masalah dengan perhitungan lembur dan potongan BPJS. Mohon dicek ulang.','in_progress','high','Tim finance sedang review perhitungan. Ditemukan bug pada formula overtime weekend. Sedang dalam proses fixing.','2025-09-18 23:45:00','2025-09-20 20:20:00'),(6,3,19,'Absen Masuk Terkadang Tidak Tersimpan','Beberapa kali absen masuk saya tidak tersimpan di sistem padahal sudah berhasil scan QR Code dan muncul notifikasi \"Check-in berhasil\". Mohon solusinya.','open','normal',NULL,'2025-09-22 17:45:00','2025-09-22 17:45:00'),(7,4,5,'Integrasi API untuk Sistem ERP','Kami ingin mengintegrasikan sistem HagaPlus dengan ERP internal perusahaan. Mohon informasi dokumentasi API dan bantuan teknis untuk proses integrasi.','in_progress','normal','Dokumentasi API sudah dikirim via email. Tim teknis akan schedule meeting dengan IT team client minggu depan.','2025-09-17 00:20:00','2025-09-18 21:40:00'),(8,4,23,'Shift Malam Tidak Bisa Checkout Otomatis','Untuk shift malam (22:00-06:00), sistem tidak bisa melakukan auto-checkout. Karyawan harus manual checkout keesokan paginya. Mohon diperbaiki untuk shift lintas hari.','open','high',NULL,'2025-09-21 08:30:00','2025-09-21 08:30:00'),(9,1,8,'Request Fitur Custom Report','Tim management membutuhkan report custom untuk analisa produktivitas per departemen. Apakah bisa ditambahkan fitur custom report builder di dashboard admin?','open','low',NULL,'2025-09-22 01:20:00','2025-09-22 01:20:00'),(10,2,15,'Notifikasi Push Tidak Masuk','Saya tidak menerima notifikasi push untuk reminder absen dan pengumuman dari HRD. Sudah cek setting notifikasi di HP dan semuanya aktif. Mohon bantuannya.','resolved','normal','Issue disebabkan oleh blocked notification di browser mobile. User sudah dibantu untuk enable notification permission.','2025-09-19 19:10:00','2025-09-21 00:45:00'),(11,4,28,'Data Absensi Hilang Seminggu','Data absensi saya dari tanggal 16-22 September tiba-tiba hilang dari sistem. Padahal sudah absen setiap hari. Mohon bantuan untuk recovery data.','in_progress','urgent','Tim database sedang melakukan recovery dari backup. Data ditemukan dan sedang dalam proses restore. Estimasi selesai 1x24 jam.','2025-09-22 20:00:00','2025-09-22 22:30:00'),(12,3,21,'Pengajuan Cuti Tidak Bisa Disubmit','Form pengajuan cuti tidak bisa disubmit. Setelah klik tombol \"Ajukan Cuti\" muncul error 500. Sudah coba beberapa kali dan dari browser berbeda tetap sama.','resolved','high','Bug pada validasi form cuti sudah diperbaiki. Issue disebabkan oleh special character pada field reason. Sudah di-deploy hotfix.','2025-09-18 21:30:00','2025-09-19 18:45:00'),(13,1,12,'Request Training Sistem','Tim baru kami membutuhkan training penggunaan sistem HagaPlus. Apakah ada program training atau panduan lengkap yang bisa kami akses?','closed','low','ada','2025-09-23 20:15:00','2025-09-24 19:49:36'),(14,4,26,'Masalah Sinkronisasi Multi Branch','Data absensi antara cabang pusat dan cabang gudang tidak tersinkronisasi dengan baik. Ada delay yang cukup lama, kadang sampai 2-3 jam baru muncul di dashboard pusat.','open','high',NULL,'2025-09-22 02:45:00','2025-09-22 02:45:00'),(15,2,17,'Ganti Password Tidak Berhasil','Saya tidak bisa mengganti password akun. Setelah masukkan password lama dan password baru, sistem bilang \"Password lama tidak sesuai\" padahal saya yakin password yang dimasukkan benar.','open','normal',NULL,'2025-09-23 00:20:00','2025-09-23 00:20:00');
/*!40000 ALTER TABLE `support_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('superadmin','admin','employee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'employee',
  `instansi_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_instansi_id_foreign` (`instansi_id`),
  KEY `users_deleted_by_foreign` (`deleted_by`),
  CONSTRAINT `users_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansis` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Administrator','superadmin@hagaplus.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','superadmin',NULL,NULL,NULL),(2,'Budi Santoso','admin@teknologimaju.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','admin',1,NULL,NULL),(3,'Sari Dewi Lestari','hr@kreatifdigital.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','admin',2,NULL,NULL),(4,'Ahmad Wijaya','owner@berkahjaya.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','admin',3,NULL,NULL),(5,'Diana Permata Sari','hrd@manufakturnusantara.co.id',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','admin',4,NULL,NULL),(6,'Andi Pratama','andi.pratama@teknologimaju.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',1,NULL,NULL),(7,'Siti Nurhaliza','siti.nurhaliza@teknologimaju.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',1,NULL,NULL),(8,'Dedi Kurniawan','dedi.kurniawan@teknologimaju.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',1,NULL,NULL),(9,'Maya Sari','maya.sari@teknologimaju.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',1,NULL,NULL),(10,'Rizky Fauzan','rizky.fauzan@teknologimaju.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',1,NULL,NULL),(11,'Indah Permatasari','indah.permatasari@teknologimaju.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',1,NULL,NULL),(12,'Bayu Setiawan','bayu.setiawan@teknologimaju.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',1,NULL,NULL),(13,'Putri Maharani','putri.maharani@teknologimaju.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',1,NULL,NULL),(14,'Yoga Pratama','yoga.pratama@kreatifdigital.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',2,NULL,NULL),(15,'Lina Marlina','lina.marlina@kreatifdigital.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',2,NULL,NULL),(16,'Farhan Maulana','farhan.maulana@kreatifdigital.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',2,NULL,NULL),(17,'Rika Amelia','rika.amelia@kreatifdigital.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',2,NULL,NULL),(18,'Galih Prasetyo','galih.prasetyo@kreatifdigital.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',2,NULL,NULL),(19,'Wati Suryani','wati.suryani@berkahjaya.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',3,NULL,NULL),(20,'Joko Susanto','joko.susanto@berkahjaya.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',3,NULL,NULL),(21,'Ani Rahayu','ani.rahayu@berkahjaya.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',3,NULL,NULL),(22,'Bambang Sutrisno','bambang.sutrisno@berkahjaya.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',3,NULL,NULL),(23,'Hendra Gunawan','hendra.gunawan@manufakturnusantara.co.id',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',4,NULL,NULL),(24,'Sri Mulyani','sri.mulyani@manufakturnusantara.co.id',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',4,NULL,NULL),(25,'Agus Salim','agus.salim@manufakturnusantara.co.id',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',4,NULL,NULL),(26,'Dewi Sartika','dewi.sartika@manufakturnusantara.co.id',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',4,NULL,NULL),(27,'Rudi Hartono','rudi.hartono@manufakturnusantara.co.id',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',4,NULL,NULL),(28,'Sari Wulandari','sari.wulandari@manufakturnusantara.co.id',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',4,NULL,NULL),(29,'Eko Prasetyo','eko.prasetyo@manufakturnusantara.co.id',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',4,NULL,NULL),(30,'Fitri Handayani','fitri.handayani@manufakturnusantara.co.id',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 21:43:22','2025-09-24 21:43:22','employee',4,NULL,NULL),(31,'lukman','lukmanmauludin831@gmail.com',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 15:07:48','2025-09-24 15:07:48','employee',NULL,NULL,NULL),(32,'Lukman Muludin','lukman@biz.id',NULL,'$2y$12$XniQQMhKe0aaL.qGLlwYqub1QVSMJTg8dX0XZErqOt4n4EQ05h/t6',NULL,'2025-09-24 19:00:59','2025-09-24 19:00:59','admin',8,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-25 22:38:16

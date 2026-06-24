-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: job_application_system
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `applications`
--

DROP TABLE IF EXISTS `applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `seeker_id` int(11) NOT NULL,
  `cover_letter` text NOT NULL,
  `resume_path` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending',
  `tracking_stage` enum('Applied','Screen','Interview','Offer') DEFAULT 'Applied',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`),
  KEY `seeker_id` (`seeker_id`),
  CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`seeker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `applications`
--

LOCK TABLES `applications` WRITE;
/*!40000 ALTER TABLE `applications` DISABLE KEYS */;
/*!40000 ALTER TABLE `applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employer_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `employer_id` (`employer_id`),
  CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (3,2,'TechNova','A leading company in the tech sector.','2026-06-24 04:27:40'),(4,2,'Quantum Solutions','A leading company in the tech sector.','2026-06-24 04:27:40'),(5,2,'Apex Interactive','A leading company in the tech sector.','2026-06-24 04:27:40'),(6,2,'BlueSky Data','A leading company in the tech sector.','2026-06-24 04:27:40'),(7,2,'Zenith Systems','A leading company in the tech sector.','2026-06-24 04:27:40');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employer_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(100) NOT NULL,
  `salary` varchar(50) DEFAULT NULL,
  `type` enum('full-time','contract','remote') DEFAULT 'full-time',
  `experience` enum('entry','mid','exec') DEFAULT 'mid',
  `status` enum('Open','Closed') DEFAULT 'Open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `employer_id` (`employer_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (3,2,5,'Backend Engineer','We are seeking a talented Backend Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Singapore','$150k - $190k','full-time','exec','Open','2026-06-24 04:27:40'),(4,2,5,'DevOps Engineer','We are seeking a talented DevOps Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Toronto, ON','$130k - $160k','contract','exec','Open','2026-06-24 04:27:40'),(5,2,5,'Security Architect','We are seeking a talented Security Architect to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','New York, NY','$150k - $190k','remote','exec','Open','2026-06-24 04:27:40'),(6,2,5,'DevOps Engineer','We are seeking a talented DevOps Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Seattle, WA','$100k - $130k','contract','entry','Open','2026-06-24 04:27:40'),(7,2,3,'Cloud Consultant','We are seeking a talented Cloud Consultant to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Remote','$100k - $130k','remote','mid','Open','2026-06-24 04:27:40'),(8,2,3,'QA Specialist','We are seeking a talented QA Specialist to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Sydney, AUS','$80k - $100k','full-time','exec','Open','2026-06-24 04:27:40'),(9,2,4,'UX/UI Designer','We are seeking a talented UX/UI Designer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Seattle, WA','$90k - $120k','remote','entry','Open','2026-06-24 04:27:40'),(10,2,3,'DevOps Engineer','We are seeking a talented DevOps Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Remote','$130k - $160k','contract','exec','Open','2026-06-24 04:27:40'),(11,2,7,'Backend Engineer','We are seeking a talented Backend Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','London, UK','$150k - $190k','remote','mid','Open','2026-06-24 04:27:40'),(12,2,6,'Data Analyst','We are seeking a talented Data Analyst to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','San Francisco, CA','$100k - $130k','contract','mid','Open','2026-06-24 04:27:40'),(13,2,7,'Frontend Developer','We are seeking a talented Frontend Developer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Seattle, WA','$100k - $130k','remote','entry','Open','2026-06-24 04:27:40'),(14,2,5,'Data Analyst','We are seeking a talented Data Analyst to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Singapore','$100k - $130k','full-time','mid','Open','2026-06-24 04:27:40'),(15,2,4,'Data Analyst','We are seeking a talented Data Analyst to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Singapore','$130k - $160k','contract','entry','Open','2026-06-24 04:27:40'),(16,2,4,'DevOps Engineer','We are seeking a talented DevOps Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Sydney, AUS','$130k - $160k','contract','exec','Open','2026-06-24 04:27:40'),(17,2,3,'Cloud Consultant','We are seeking a talented Cloud Consultant to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Austin, TX','$80k - $100k','contract','exec','Open','2026-06-24 04:27:40'),(18,2,5,'Product Manager','We are seeking a talented Product Manager to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','London, UK','$130k - $160k','full-time','entry','Open','2026-06-24 04:27:40'),(19,2,6,'Product Manager','We are seeking a talented Product Manager to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','London, UK','$130k - $160k','full-time','entry','Open','2026-06-24 04:27:40'),(20,2,3,'QA Specialist','We are seeking a talented QA Specialist to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','San Francisco, CA','$90k - $120k','remote','mid','Open','2026-06-24 04:27:40'),(21,2,4,'Backend Engineer','We are seeking a talented Backend Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Singapore','$130k - $160k','remote','mid','Open','2026-06-24 04:27:40'),(22,2,7,'Frontend Developer','We are seeking a talented Frontend Developer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Remote','$150k - $190k','remote','exec','Open','2026-06-24 04:27:40'),(23,2,5,'Backend Engineer','We are seeking a talented Backend Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','New York, NY','$100k - $130k','remote','exec','Open','2026-06-24 04:27:40'),(24,2,5,'QA Specialist','We are seeking a talented QA Specialist to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Austin, TX','$130k - $160k','contract','mid','Open','2026-06-24 04:27:40'),(25,2,4,'Full Stack Developer','We are seeking a talented Full Stack Developer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','New York, NY','$80k - $100k','remote','mid','Open','2026-06-24 04:27:40'),(26,2,3,'Product Manager','We are seeking a talented Product Manager to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','New York, NY','$90k - $120k','remote','entry','Open','2026-06-24 04:27:40'),(27,2,3,'UX/UI Designer','We are seeking a talented UX/UI Designer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Austin, TX','$80k - $100k','full-time','exec','Open','2026-06-24 04:27:40'),(28,2,5,'Full Stack Developer','We are seeking a talented Full Stack Developer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','New York, NY','$100k - $130k','remote','exec','Open','2026-06-24 04:27:40'),(29,2,6,'DevOps Engineer','We are seeking a talented DevOps Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Sydney, AUS','$90k - $120k','contract','entry','Open','2026-06-24 04:27:40'),(30,2,4,'Product Manager','We are seeking a talented Product Manager to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','London, UK','$90k - $120k','contract','mid','Open','2026-06-24 04:27:40'),(31,2,4,'Security Architect','We are seeking a talented Security Architect to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','San Francisco, CA','$90k - $120k','full-time','entry','Open','2026-06-24 04:27:40'),(32,2,5,'DevOps Engineer','We are seeking a talented DevOps Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Remote','$80k - $100k','contract','exec','Open','2026-06-24 04:27:40'),(33,2,4,'Product Manager','We are seeking a talented Product Manager to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Seattle, WA','$90k - $120k','full-time','entry','Open','2026-06-24 04:27:40'),(34,2,5,'Security Architect','We are seeking a talented Security Architect to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Singapore','$80k - $100k','remote','entry','Open','2026-06-24 04:27:40'),(35,2,6,'DevOps Engineer','We are seeking a talented DevOps Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Seattle, WA','$150k - $190k','full-time','exec','Open','2026-06-24 04:27:40'),(36,2,6,'QA Specialist','We are seeking a talented QA Specialist to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Remote','$100k - $130k','remote','mid','Open','2026-06-24 04:27:40'),(37,2,5,'Backend Engineer','We are seeking a talented Backend Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Sydney, AUS','$130k - $160k','contract','mid','Open','2026-06-24 04:27:40'),(38,2,3,'QA Specialist','We are seeking a talented QA Specialist to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Sydney, AUS','$130k - $160k','full-time','entry','Open','2026-06-24 04:27:40'),(39,2,7,'Product Manager','We are seeking a talented Product Manager to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','New York, NY','$100k - $130k','contract','exec','Open','2026-06-24 04:27:40'),(40,2,4,'Security Architect','We are seeking a talented Security Architect to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Sydney, AUS','$100k - $130k','full-time','exec','Open','2026-06-24 04:27:40'),(41,2,5,'DevOps Engineer','We are seeking a talented DevOps Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Sydney, AUS','$130k - $160k','contract','entry','Open','2026-06-24 04:27:40'),(42,2,7,'Cloud Consultant','We are seeking a talented Cloud Consultant to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Remote','$130k - $160k','full-time','mid','Open','2026-06-24 04:27:40'),(43,2,6,'Frontend Developer','We are seeking a talented Frontend Developer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Austin, TX','$100k - $130k','full-time','entry','Open','2026-06-24 04:27:40'),(44,2,5,'Cloud Consultant','We are seeking a talented Cloud Consultant to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Sydney, AUS','$130k - $160k','remote','entry','Open','2026-06-24 04:27:40'),(45,2,4,'UX/UI Designer','We are seeking a talented UX/UI Designer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Berlin, Germany','$90k - $120k','full-time','mid','Open','2026-06-24 04:27:40'),(46,2,5,'Backend Engineer','We are seeking a talented Backend Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','New York, NY','$80k - $100k','contract','entry','Open','2026-06-24 04:27:40'),(47,2,3,'Full Stack Developer','We are seeking a talented Full Stack Developer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','New York, NY','$130k - $160k','full-time','mid','Open','2026-06-24 04:27:40'),(48,2,5,'Full Stack Developer','We are seeking a talented Full Stack Developer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Austin, TX','$100k - $130k','contract','entry','Open','2026-06-24 04:27:40'),(49,2,6,'Backend Engineer','We are seeking a talented Backend Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','San Francisco, CA','$130k - $160k','remote','entry','Open','2026-06-24 04:27:40'),(50,2,3,'Data Analyst','We are seeking a talented Data Analyst to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Berlin, Germany','$130k - $160k','full-time','mid','Open','2026-06-24 04:27:40'),(51,2,7,'Product Manager','We are seeking a talented Product Manager to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','London, UK','$90k - $120k','remote','entry','Open','2026-06-24 04:27:40'),(52,2,7,'DevOps Engineer','We are seeking a talented DevOps Engineer to join our team at a fast-growing company. You will be responsible for building cutting-edge solutions and collaborating with cross-functional teams to deliver value to our users.','Seattle, WA','$130k - $160k','contract','mid','Open','2026-06-24 04:27:40');
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('seeker','employer','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'System Admin','admin@admin.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin','2026-06-24 04:19:53'),(2,'Tech Employer','employer@company.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','employer','2026-06-24 04:19:53'),(3,'Job Seeker','seeker@seeker.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','seeker','2026-06-24 04:19:53'),(4,'Test','test@test.com','$2y$10$Gv6mhS.71Mvca9xNlkzAneGrvxBRO3iS4iPJ6xuV4tOI1OenZ35ju','seeker','2026-06-24 04:20:43'),(5,'TR','TR@gmail.com','$2y$10$nKGvVh3at6RUdxO0nWIhfeb0eCgEnbf837XAfbaOA7leSdYIqhrrO','seeker','2026-06-24 04:21:45'),(6,'Gmail Test','myaccount@gmail.com','$2y$10$yl8Mqpc3u4a4Sh4/5S6uuuAHekg8szDU1X2nh9v0WNyjhUfcyW8Ke','seeker','2026-06-24 04:27:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `saved_jobs`
--

DROP TABLE IF EXISTS `saved_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saved_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_save` (`user_id`,`job_id`),
  KEY `job_id` (`job_id`),
  CONSTRAINT `saved_jobs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `saved_jobs_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-24 12:30:16

use cs744;
-- MySQL dump 10.13  Distrib 5.7.17, for macos10.12 (x86_64)
--
-- Host: 127.0.0.1    Database: cs744
-- ------------------------------------------------------
-- Server version	5.7.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','123456');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isBlocked` tinyint(1) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'Wei Du','123456',0);
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `connections`
--

DROP TABLE IF EXISTS `connections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `connections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `connections`
--

LOCK TABLES `connections` WRITE;
/*!40000 ALTER TABLE `connections` DISABLE KEYS */;
INSERT INTO `connections` VALUES (1,2,1,3),(2,3,1,2),(3,4,2,1),(4,5,2,3),(5,6,3,2),(6,7,2,4),(7,8,7,2),(8,13,7,3),(9,16,3,2),(10,17,3,3),(11,18,7,3),(12,19,18,2),(13,20,7,4);
/*!40000 ALTER TABLE `connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `creditaccounts`
--

DROP TABLE IF EXISTS `creditaccounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `creditaccounts` (
  `accountid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `holdername` varchar(255) NOT NULL,
  `phonenumber` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `spendlinglimit` double NOT NULL,
  `balance` double NOT NULL,
  PRIMARY KEY (`accountid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creditaccounts`
--

LOCK TABLES `creditaccounts` WRITE;
/*!40000 ALTER TABLE `creditaccounts` DISABLE KEYS */;
INSERT INTO `creditaccounts` VALUES (1,'Wei Du','6464319033','1824 La Crosse St',2000,49),(2,'Jason Peter','6084319023','1824 La Crossse St',200,800);
/*!40000 ALTER TABLE `creditaccounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `creditcards`
--

DROP TABLE IF EXISTS `creditcards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `creditcards` (
  `cardId` varchar(255) NOT NULL,
  `csc` int(11) NOT NULL,
  `expireDate` date NOT NULL,
  `accountid` varchar(255) NOT NULL,
  PRIMARY KEY (`cardId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creditcards`
--

LOCK TABLES `creditcards` WRITE;
/*!40000 ALTER TABLE `creditcards` DISABLE KEYS */;
INSERT INTO `creditcards` VALUES ('4340847829821680',762,'2019-03-01','2'),('4716459360888577',201,'2019-12-01','1');
/*!40000 ALTER TABLE `creditcards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(6,'2018_02_04_044557_create_admin_table',2),(9,'2018  02  04  093520  questions',3),(10,'2018_02_18_045942_creat_creaditaccount_table',4),(12,'2018_02_18_050035_create_creditcard_table',5),(13,'2018_03_04_135808_create_station_table',6),(14,'2018_03_04_140140_create_connections_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `qid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (1,'Which course do you take for this project?','CS744',1),(2,'Who is your instructor for this project?','Kasi',1),(3,'Who is your partner?','Yi',1);
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queues`
--

DROP TABLE IF EXISTS `queues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queues` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message` varchar(255) DEFAULT NULL,
  `from` int(11) NOT NULL,
  `path` json NOT NULL,
  `card` varchar(255) NOT NULL,
  `cvv` varchar(255) NOT NULL,
  `holder_name` varchar(255) NOT NULL,
  `amount` double NOT NULL,
  `result` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '1 - processing\n2 - returning\n3 - finish\n4 - stopped',
  `current` int(11) NOT NULL,
  `f_status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queues`
--

LOCK TABLES `queues` WRITE;
/*!40000 ALTER TABLE `queues` DISABLE KEYS */;
INSERT INTO `queues` VALUES (1,'The card don\'t exist',1,'[{\"id\": 5, \"type\": \"1\"}, {\"edge\": {\"id\": 4, \"to\": 2, \"from\": 5, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 2, \"type\": \"1\"}, {\"edge\": {\"id\": 1, \"to\": 1, \"from\": 2, \"weight\": 3}, \"type\": \"2\"}]','000000000000000','000','null',0,'Declined',3,0,NULL),(2,'The card don\'t exist',1,'[{\"id\": 6, \"type\": \"1\"}, {\"edge\": {\"id\": 5, \"to\": 3, \"from\": 6, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 3, \"type\": \"1\"}, {\"edge\": {\"id\": 2, \"to\": 1, \"from\": 3, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','000000000000000','000','null',0,'Declined',3,0,NULL),(3,'The card don\'t exist',1,'[{\"id\": 6, \"type\": \"1\"}, {\"edge\": {\"id\": 5, \"to\": 3, \"from\": 6, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 3, \"type\": \"1\"}, {\"edge\": {\"id\": 2, \"to\": 1, \"from\": 3, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','000000000000000','000','null',0,'Declined',3,0,NULL),(4,'The card don\'t exist',1,'[{\"id\": 8, \"type\": \"1\"}, {\"edge\": {\"id\": 7, \"to\": 7, \"from\": 8, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 7, \"type\": \"1\"}, {\"edge\": {\"id\": 6, \"to\": 2, \"from\": 7, \"weight\": 4}, \"type\": \"2\"}, {\"id\": 2, \"type\": \"1\"}, {\"edge\": {\"id\": 1, \"to\": 1, \"from\": 2, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','000000000000000','000','null',0,'Declined',3,0,NULL),(5,'The information of the card doesn\'t match with our records',1,'[{\"id\": 5, \"type\": \"1\"}, {\"edge\": {\"id\": 4, \"to\": 2, \"from\": 5, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 2, \"type\": \"1\"}, {\"edge\": {\"id\": 1, \"to\": 1, \"from\": 2, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','484','Wei',500,'Declined',3,0,NULL),(6,'The information of the card doesn\'t match with our records',17,'[{\"id\": 17, \"type\": \"1\"}, {\"edge\": {\"id\": 10, \"to\": 3, \"from\": 17, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 3, \"type\": \"1\"}, {\"edge\": {\"id\": 2, \"to\": 1, \"from\": 3, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','200','Wei Du',100,'Declined',3,0,NULL),(7,'The information of the card doesn\'t match with our records',6,'[{\"id\": 6, \"type\": \"1\"}, {\"edge\": {\"id\": 5, \"to\": 3, \"from\": 6, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 3, \"type\": \"1\"}, {\"edge\": {\"id\": 2, \"to\": 1, \"from\": 3, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','200','Wei Du',100,'Declined',3,0,NULL),(8,'The transaction is finished',13,'[{\"id\": 13, \"type\": \"1\"}, {\"edge\": {\"id\": 8, \"to\": 7, \"from\": 13, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 7, \"type\": \"1\"}, {\"edge\": {\"id\": 6, \"to\": 2, \"from\": 7, \"weight\": 4}, \"type\": \"2\"}, {\"id\": 2, \"type\": \"1\"}, {\"edge\": {\"id\": 1, \"to\": 1, \"from\": 2, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','201','Wei Du',120,'Approved',3,0,NULL),(9,'The nearest relay is inactivated, please resend a new transaction later',19,'[{\"id\": 19, \"type\": \"1\"}, {\"edge\": {\"id\": 12, \"to\": 18, \"from\": 19, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 18, \"type\": \"1\"}, {\"edge\": {\"id\": 11, \"to\": 7, \"from\": 18, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 7, \"type\": \"1\"}, {\"edge\": {\"id\": 6, \"to\": 2, \"from\": 7, \"weight\": 4}, \"type\": \"2\"}, {\"id\": 2, \"type\": \"1\"}, {\"edge\": {\"id\": 1, \"to\": 1, \"from\": 2, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','201','Wei Du',120,NULL,3,1,NULL),(10,'The transaction is finished',19,'[{\"id\": 19, \"type\": \"1\"}, {\"edge\": {\"id\": 12, \"to\": 18, \"from\": 19, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 18, \"type\": \"1\"}, {\"edge\": {\"id\": 11, \"to\": 7, \"from\": 18, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 7, \"type\": \"1\"}, {\"edge\": {\"id\": 6, \"to\": 2, \"from\": 7, \"weight\": 4}, \"type\": \"2\"}, {\"id\": 2, \"type\": \"1\"}, {\"edge\": {\"id\": 1, \"to\": 1, \"from\": 2, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','201','Wei Du',120,'Approved',3,0,NULL),(11,'The transaction is finished',8,'[{\"id\": 8, \"type\": \"1\"}, {\"edge\": {\"id\": 7, \"to\": 7, \"from\": 8, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 7, \"type\": \"1\"}, {\"edge\": {\"id\": 6, \"to\": 2, \"from\": 7, \"weight\": 4}, \"type\": \"2\"}, {\"id\": 2, \"type\": \"1\"}, {\"edge\": {\"id\": 1, \"to\": 1, \"from\": 2, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','201','Wei Du',100,'Approved',3,0,NULL),(12,'The transaction is finished',6,'[{\"id\": 6, \"type\": \"1\"}, {\"edge\": {\"id\": 5, \"to\": 3, \"from\": 6, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 3, \"type\": \"1\"}, {\"edge\": {\"id\": 2, \"to\": 1, \"from\": 3, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','201','Wei Du',100,'Approved',3,0,NULL),(13,'The nearest relay is inactivated, please resend a new transaction later',19,'[{\"id\": 19, \"type\": \"1\"}, {\"edge\": {\"id\": 12, \"to\": 18, \"from\": 19, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 18, \"type\": \"1\"}, {\"edge\": {\"id\": 11, \"to\": 7, \"from\": 18, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 7, \"type\": \"1\"}, {\"edge\": {\"id\": 6, \"to\": 2, \"from\": 7, \"weight\": 4}, \"type\": \"2\"}, {\"id\": 2, \"type\": \"1\"}, {\"edge\": {\"id\": 1, \"to\": 1, \"from\": 2, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','201','Wei Du',201,NULL,3,1,NULL),(14,'The transaction is finished',16,'[{\"id\": 16, \"type\": \"1\"}, {\"edge\": {\"id\": 9, \"to\": 3, \"from\": 16, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 3, \"type\": \"1\"}, {\"edge\": {\"id\": 2, \"to\": 1, \"from\": 3, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','201','Wei Du',201,'Approved',3,0,NULL),(15,'The transaction is finished',20,'[{\"id\": 20, \"type\": \"1\"}, {\"edge\": {\"id\": 13, \"to\": 7, \"from\": 20, \"weight\": 4}, \"type\": \"2\"}, {\"id\": 7, \"type\": \"1\"}, {\"edge\": {\"id\": 6, \"to\": 2, \"from\": 7, \"weight\": 4}, \"type\": \"2\"}, {\"id\": 2, \"type\": \"1\"}, {\"edge\": {\"id\": 1, \"to\": 1, \"from\": 2, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','201','Wei Du',100,'Approved',3,0,NULL),(16,'The transaction is finished',19,'[{\"id\": 19, \"type\": \"1\"}, {\"edge\": {\"id\": 12, \"to\": 18, \"from\": 19, \"weight\": 2}, \"type\": \"2\"}, {\"id\": 18, \"type\": \"1\"}, {\"edge\": {\"id\": 11, \"to\": 7, \"from\": 18, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 7, \"type\": \"1\"}, {\"edge\": {\"id\": 6, \"to\": 2, \"from\": 7, \"weight\": 4}, \"type\": \"2\"}, {\"id\": 2, \"type\": \"1\"}, {\"edge\": {\"id\": 1, \"to\": 1, \"from\": 2, \"weight\": 3}, \"type\": \"2\"}, {\"id\": 1, \"type\": \"1\"}]','4716459360888577','201','Wei Du',100,'Approved',3,0,NULL);
/*!40000 ALTER TABLE `queues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `station`
--

DROP TABLE IF EXISTS `station`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `station` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `queues` json DEFAULT NULL,
  `limit` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `station_ip_unique` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `station`
--

LOCK TABLES `station` WRITE;
/*!40000 ALTER TABLE `station` DISABLE KEYS */;
INSERT INTO `station` VALUES (1,0,1,'32.181.121.11','[]',NULL),(2,1,1,'151.250.93.7','[]',NULL),(3,1,1,'84.46.44.129','[]',NULL),(4,2,1,'140.76.60.104',NULL,NULL),(5,2,1,'73.173.4.239',NULL,NULL),(6,2,1,'56.136.162.39',NULL,NULL),(7,1,1,'41.123.143.237','[]',NULL),(8,2,1,'4.9.150.118',NULL,NULL),(13,2,1,'64.233.161.147',NULL,NULL),(16,2,1,'104.110.178.164',NULL,NULL),(17,2,1,'19.218.44.71',NULL,NULL),(18,1,1,'61.182.123.146','[]',NULL),(19,2,1,'64.233.161.148',NULL,NULL),(20,2,1,'85.56.18.242',NULL,NULL);
/*!40000 ALTER TABLE `station` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tests`
--

DROP TABLE IF EXISTS `tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tests`
--

LOCK TABLES `tests` WRITE;
/*!40000 ALTER TABLE `tests` DISABLE KEYS */;
INSERT INTO `tests` VALUES (1,'test1');
/*!40000 ALTER TABLE `tests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
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

-- Dump completed on 2018-03-27 14:27:40

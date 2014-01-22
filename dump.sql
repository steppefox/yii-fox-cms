-- MySQL dump 10.14  Distrib 5.5.34-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: fox
-- ------------------------------------------------------
-- Server version	5.5.34-MariaDB-1~precise-log

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
-- Table structure for table `AuthAssignment`
--

DROP TABLE IF EXISTS `AuthAssignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthAssignment` (
  `itemname` varchar(64) NOT NULL,
  `userid` varchar(64) NOT NULL,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`itemname`,`userid`),
  CONSTRAINT `AuthAssignment_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AuthAssignment`
--

LOCK TABLES `AuthAssignment` WRITE;
/*!40000 ALTER TABLE `AuthAssignment` DISABLE KEYS */;
INSERT INTO `AuthAssignment` VALUES ('Admin','1',NULL,NULL);
/*!40000 ALTER TABLE `AuthAssignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AuthItem`
--

DROP TABLE IF EXISTS `AuthItem`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthItem` (
  `name` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `description` text,
  `bizrule` text,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AuthItem`
--

LOCK TABLES `AuthItem` WRITE;
/*!40000 ALTER TABLE `AuthItem` DISABLE KEYS */;
INSERT INTO `AuthItem` VALUES ('Admin',2,'Администратор',NULL,NULL);
/*!40000 ALTER TABLE `AuthItem` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AuthItemChild`
--

DROP TABLE IF EXISTS `AuthItemChild`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AuthItemChild` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `AuthItemChild_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `AuthItemChild_ibfk_2` FOREIGN KEY (`child`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AuthItemChild`
--

LOCK TABLES `AuthItemChild` WRITE;
/*!40000 ALTER TABLE `AuthItemChild` DISABLE KEYS */;
/*!40000 ALTER TABLE `AuthItemChild` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Rights`
--

DROP TABLE IF EXISTS `Rights`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Rights` (
  `itemname` varchar(64) NOT NULL,
  `type` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`itemname`),
  CONSTRAINT `Rights_ibfk_1` FOREIGN KEY (`itemname`) REFERENCES `AuthItem` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Rights`
--

LOCK TABLES `Rights` WRITE;
/*!40000 ALTER TABLE `Rights` DISABLE KEYS */;
/*!40000 ALTER TABLE `Rights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_Catalog`
--

DROP TABLE IF EXISTS `data_Catalog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_Catalog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_CatalogCategory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title_ru` varchar(255) NOT NULL,
  `description_ru` varchar(255) NOT NULL,
  `text_ru` text NOT NULL,
  `image` text NOT NULL,
  `price` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(10) unsigned DEFAULT '0',
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `is_static` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_CatalogCategory_id` (`parent_CatalogCategory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_Catalog`
--

LOCK TABLES `data_Catalog` WRITE;
/*!40000 ALTER TABLE `data_Catalog` DISABLE KEYS */;
INSERT INTO `data_Catalog` VALUES (6,0,'123123123','','<p>asdfasdfasdf</p><p><img src=\"/./upload/aa41f9cdc08ff782ce15585fd0ccbf1b.jpg\" style=\"width: 153px;\"></p>','{\"ba8945d24a0be405a98a1e02d5133600\":\"Catalog-image-6-ba8945d24a0be405a98a1e02d5133600.jpg\"}',0,1389976423,1389983971,1,0);
/*!40000 ALTER TABLE `data_Catalog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_CatalogCategory`
--

DROP TABLE IF EXISTS `data_CatalogCategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_CatalogCategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title_ru` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `created_at` int(10) unsigned DEFAULT '0',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_CatalogCategory`
--

LOCK TABLES `data_CatalogCategory` WRITE;
/*!40000 ALTER TABLE `data_CatalogCategory` DISABLE KEYS */;
INSERT INTO `data_CatalogCategory` VALUES (1,0,'Прожекторы','',1375381724,1375381724,1),(2,0,'Вспышки','',1375381763,1375381845,1),(3,0,'Светолучи','',1375381857,1375381857,1),(4,0,'Фары','',1375381866,1375381866,1),(5,0,'Фонарики','',1375381871,1375381871,1);
/*!40000 ALTER TABLE `data_CatalogCategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_Navigation`
--

DROP TABLE IF EXISTS `data_Navigation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_Navigation` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` smallint(5) NOT NULL DEFAULT '0',
  `ancestors` varchar(255) NOT NULL DEFAULT '[]',
  `type` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT 'Тип меню (верхнее, нижнее и т.д. )',
  `url` varchar(150) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `weight` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`status`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Таблица навигации';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_Navigation`
--

LOCK TABLES `data_Navigation` WRITE;
/*!40000 ALTER TABLE `data_Navigation` DISABLE KEYS */;
INSERT INTO `data_Navigation` VALUES (1,0,'[]',1,'/','Главная',1,1),(2,0,'[]',1,'/catalog','Каталог',2,1),(3,0,'[]',1,'/site/about','О нас',3,1);
/*!40000 ALTER TABLE `data_Navigation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_News`
--

DROP TABLE IF EXISTS `data_News`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_News` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_NewsCategory_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title_ru` varchar(255) NOT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_NewsCategory_id` (`parent_NewsCategory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_News`
--

LOCK TABLES `data_News` WRITE;
/*!40000 ALTER TABLE `data_News` DISABLE KEYS */;
INSERT INTO `data_News` VALUES (1,0,'asdasd',1,0,0);
/*!40000 ALTER TABLE `data_News` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_Page`
--

DROP TABLE IF EXISTS `data_Page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_Page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `description_ru` varchar(255) NOT NULL,
  `text_ru` text NOT NULL,
  `image` text NOT NULL,
  `created_at` int(10) unsigned NOT NULL DEFAULT '0',
  `updated_at` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_Page`
--

LOCK TABLES `data_Page` WRITE;
/*!40000 ALTER TABLE `data_Page` DISABLE KEYS */;
INSERT INTO `data_Page` VALUES (1,'','2','','','',1375379782,1375379787,1);
/*!40000 ALTER TABLE `data_Page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `data_User`
--

DROP TABLE IF EXISTS `data_User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `data_User` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `nicename` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `data_User`
--

LOCK TABLES `data_User` WRITE;
/*!40000 ALTER TABLE `data_User` DISABLE KEYS */;
INSERT INTO `data_User` VALUES (1,'root','Admin','root@steppefox.kz','$2a$13$mfC2ArEm6rChtP5IlpQ8Z.SNBid8.bh6kXSPkhCWf.Gg8hZSbAf2G',1),(2,'user','Mortal','user@steppefox.kz','$2a$13$2v/OdTK3sDdo3ipG7KJD8eIm7U7njYccCgwkpDj1WVvvXUV3ObOYK',1);
/*!40000 ALTER TABLE `data_User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_Message`
--

DROP TABLE IF EXISTS `sys_Message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_Message` (
  `id` int(11) NOT NULL DEFAULT '0',
  `language` varchar(16) NOT NULL DEFAULT '',
  `translation` text,
  PRIMARY KEY (`id`,`language`),
  CONSTRAINT `FK_Message_SourceMessage` FOREIGN KEY (`id`) REFERENCES `sys_SourceMessage` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_Message`
--

LOCK TABLES `sys_Message` WRITE;
/*!40000 ALTER TABLE `sys_Message` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_Message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sys_SourceMessage`
--

DROP TABLE IF EXISTS `sys_SourceMessage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sys_SourceMessage` (
  `id` int(11) NOT NULL,
  `category` varchar(32) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sys_SourceMessage`
--

LOCK TABLES `sys_SourceMessage` WRITE;
/*!40000 ALTER TABLE `sys_SourceMessage` DISABLE KEYS */;
/*!40000 ALTER TABLE `sys_SourceMessage` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-22 15:39:23

--   Filename: tabfill.sql
--   Secure Coding project
--   Oct 2014


-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: my_bank
-- ------------------------------------------------------
-- Server version	5.5.35-1

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
-- Table structure for table `BALANCE`
--

DROP TABLE IF EXISTS `BALANCE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BALANCE` (
  `account_number` int(25) NOT NULL AUTO_INCREMENT,
  `email` varchar(64) NOT NULL,
  `balance` float(10,4) NOT NULL,
  PRIMARY KEY (`account_number`),
  KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

ALTER TABLE BALANCE AUTO_INCREMENT = 1;
--
-- Dumping data for table `BALANCE`
--

LOCK TABLES `BALANCE` WRITE;
/*!40000 ALTER TABLE `BALANCE` DISABLE KEYS */;
INSERT INTO `BALANCE` VALUES (1,'client1@mybank.de',108700.7656),(2,'client2@mybank.de',54300.7617);
/*!40000 ALTER TABLE `BALANCE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TRANSACTIONS`
--

DROP TABLE IF EXISTS `TRANSACTIONS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TRANSACTIONS` (
  `trans_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account_num_src` int(25) NOT NULL,
  `account_num_dest` int(25) NOT NULL,
  `amount` mediumint(8) unsigned NOT NULL,
  `description` varchar(120) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_id`),
  KEY `account_num_src` (`account_num_src`),
  KEY `account_num_dest` (`account_num_dest`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TRANSACTIONS`
--

LOCK TABLES `TRANSACTIONS` WRITE;
/*!40000 ALTER TABLE `TRANSACTIONS` DISABLE KEYS */;
INSERT INTO `TRANSACTIONS` VALUES (1,1,2,300,'Bride. You know what for!','2014-11-28 00:37:37',1),(2,1,2,11000,'Ok, here is the real bride!','2014-11-28 00:39:46',1),(3,1,2,30000,'Ok, ok. Here is your money!','2014-11-28 00:44:01',1),(4,1,2,30000,'Ok, ok. Here is your money!','2014-11-28 00:44:59',0),(5,1,2,30000,'Ok, ok. Here is your money!','2014-11-28 00:45:41',0);
/*!40000 ALTER TABLE `TRANSACTIONS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TRANSACTION_CODES`
--

DROP TABLE IF EXISTS `TRANSACTION_CODES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TRANSACTION_CODES` (
  `account_number` int(25) NOT NULL,
  `tancode_id` smallint(2) unsigned NOT NULL AUTO_INCREMENT,
  `tancode` varchar(15) NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_number`,`tancode_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TRANSACTION_CODES`
--

LOCK TABLES `TRANSACTION_CODES` WRITE;
/*!40000 ALTER TABLE `TRANSACTION_CODES` DISABLE KEYS */;
INSERT INTO `TRANSACTION_CODES` VALUES (1,1,'tz5477ba96aee48',0),(1,2,'av5477ba96af566',0),(1,3,'gk5477ba96af888',0),(1,4,'ns5477ba96afa66',0),(1,5,'sa5477ba96afc3d',0),(1,6,'mw5477ba96afdbc',0),(1,7,'ew5477ba96aff5b',0),(1,8,'iv5477ba96b00dc',1),(1,9,'ra5477ba96b03c0',0),(1,10,'jw5477ba96b0635',0),(1,11,'rq5477ba96b07d9',0),(1,12,'io5477ba96b097c',0),(1,13,'fu5477ba96b0b30',0),(1,14,'yo5477ba96b0cd8',0),(1,15,'fl5477ba96b0eb8',0),(1,16,'to5477ba96b1078',0),(1,17,'so5477ba96b1273',0),(1,18,'fi5477ba96b1472',0),(1,19,'xg5477ba96b1668',0),(1,20,'fj5477ba96b182f',0),(1,21,'xr5477ba96b1a0d',0),(1,22,'zp5477ba96b1bf2',0),(1,23,'pa5477ba96b45ba',0),(1,24,'ts5477ba96b4901',0),(1,25,'ge5477ba96b4ac3',0),(1,26,'dw5477ba96b4cc1',0),(1,27,'kk5477ba96b4e9c',0),(1,28,'hj5477ba96b5200',0),(1,29,'eu5477ba96b543f',0),(1,30,'zv5477ba96b5658',1),(1,31,'xi5477ba96b5842',1),(1,32,'iy5477ba96b5a3a',0),(1,33,'fe5477ba96b5c37',0),(1,34,'hj5477ba96b5e9a',0),(1,35,'na5477ba96b96ae',0),(1,36,'ku5477ba96b9869',0),(1,37,'jh5477ba96b9a40',0),(1,38,'pr5477ba96b9c84',0),(1,39,'ms5477ba96b9e23',0),(1,40,'zn5477ba96ba16f',0),(1,41,'em5477ba96ba3b8',0),(1,42,'sb5477ba96ba574',0),(1,43,'yf5477ba96ba745',0),(1,44,'qs5477ba96ba8ef',0),(1,45,'xb5477ba96baae1',0),(1,46,'rj5477ba96baca6',0),(1,47,'ty5477ba96bae45',0),(1,48,'wf5477ba96bb000',0),(1,49,'yo5477ba96bb1be',0),(1,50,'ye5477ba96bb368',0),(1,51,'cz5477ba96bb51f',0),(1,52,'ok5477ba96bb6c9',0),(1,53,'zi5477ba96bb8a1',0),(1,54,'kj5477ba96bba69',0),(1,55,'po5477ba96bbc28',0),(1,56,'hg5477ba96bbdcf',0),(1,57,'fb5477ba96bbf8d',0),(1,58,'lv5477ba96bc147',0),(1,59,'ih5477ba96bc2ed',0),(1,60,'id5477ba96bc4ae',0),(1,61,'ta5477ba96bc68c',1),(1,62,'vt5477ba96bc86a',1),(1,63,'ah5477ba96bca6c',1),(1,64,'uy5477ba96bcc48',0),(1,65,'sc5477ba96bce25',0),(1,66,'ls5477ba96bd0a3',0),(1,67,'nm5477ba96bd2a7',0),(1,68,'tp5477ba96bd49f',0),(1,69,'gp5477ba96bd681',0),(1,70,'nr5477ba96bd83c',0),(1,71,'vv5477ba96bda26',0),(1,72,'vs5477ba96bdbe1',0),(1,73,'so5477ba96bdd9b',0),(1,74,'ca5477ba96bdf58',0),(1,75,'tb5477ba96be101',0),(1,76,'ab5477ba96be2bc',0),(1,77,'gb5477ba96be47a',0),(1,78,'sb5477ba96be621',0),(1,79,'vp5477ba96be7dc',0),(1,80,'vp5477ba96be9a4',0),(1,81,'cb5477ba96beb66',0),(1,82,'fb5477ba96bee3e',0),(1,83,'fx5477ba96bf047',0),(1,84,'kc5477ba96bf21d',0),(1,85,'sl5477ba96bf3c8',0),(1,86,'ag5477ba96bf582',0),(1,87,'ql5477ba96bf74b',0),(1,88,'lb5477ba96bf962',0),(1,89,'fv5477ba96bfb28',0),(1,90,'ib5477ba96bfce7',0),(1,91,'dg5477ba96bfe8d',0),(1,92,'wj5477ba96c1216',0),(1,93,'jj5477ba96c1608',0),(1,94,'os5477ba96c18ef',0),(1,95,'fm5477ba96c1ae8',0),(1,96,'jb5477ba96c1ca4',0),(1,97,'ce5477ba96c1e62',0),(1,98,'iz5477ba96c200e',0),(1,99,'fb5477ba96c21cc',0),(1,100,'by5477ba96c238a',0);
/*!40000 ALTER TABLE `TRANSACTION_CODES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USERS`
--

DROP TABLE IF EXISTS `USERS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USERS` (
  `email` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_employee` tinyint(1) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `pdf` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERS`
--

LOCK TABLES `USERS` WRITE;
/*!40000 ALTER TABLE `USERS` DISABLE KEYS */;
INSERT INTO `USERS` VALUES ('employee1@mybank.de','$2y$10$fXXbdeKt1K7ZZ7p0eDLciuaAQLz3VUawPotYVCUpLzJzTZR4ELwEu',1,1,2),('client1@mybank.de','$2y$10$5mFtI0qcO6swHa/hpFc6pOvGhfi5yn9MuIza0UhVJ1DyzhLzuMMjO',0,1,1),('client2@mybank.de','$2y$10$i6FLPhWKUV7zkpbcUQCnmO.J2DlHvvFA65DZkvJA5kc.29Z/uB/7G',0,1,2);
/*!40000 ALTER TABLE `USERS` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-28  1:57:50

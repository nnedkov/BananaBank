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
  `email` varchar(64) NOT NULL,
  `balance` float(10,4) NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BALANCE`
--

LOCK TABLES `BALANCE` WRITE;
/*!40000 ALTER TABLE `BALANCE` DISABLE KEYS */;
INSERT INTO `BALANCE` VALUES ('client1@mybank.de',53000.0000),('client2@mybank.de',4000.0000),('client3@mybank.de',13000.0000);
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
  `email_src` varchar(64) NOT NULL,
  `email_dest` varchar(64) NOT NULL,
  `amount` mediumint(8) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TRANSACTIONS`
--

LOCK TABLES `TRANSACTIONS` WRITE;
/*!40000 ALTER TABLE `TRANSACTIONS` DISABLE KEYS */;
INSERT INTO `TRANSACTIONS` VALUES (1,'client1@mybank.de','client2@mybank.de',200,'2014-10-27 18:22:33',1),(2,'client1@mybank.de','client3@mybank.de',400,'2014-10-27 18:22:33',1),(3,'client1@mybank.de','client2@mybank.de',800,'2014-10-27 18:22:33',1),(4,'client1@mybank.de','client3@mybank.de',300,'2014-10-27 18:22:33',1),(5,'client1@mybank.de','client3@mybank.de',11000,'2014-10-27 18:22:33',0);
/*!40000 ALTER TABLE `TRANSACTIONS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TRANSACTION_CODES`
--

DROP TABLE IF EXISTS `TRANSACTION_CODES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TRANSACTION_CODES` (
  `email` varchar(64) NOT NULL,
  `tancode_id` smallint(2) unsigned NOT NULL AUTO_INCREMENT,
  `tancode` varchar(15) NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`email`,`tancode_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TRANSACTION_CODES`
--

LOCK TABLES `TRANSACTION_CODES` WRITE;
/*!40000 ALTER TABLE `TRANSACTION_CODES` DISABLE KEYS */;
INSERT INTO `TRANSACTION_CODES` VALUES ('client1@mybank.de',1,'wv544e8e4986b6f',0),('client1@mybank.de',2,'mw544e8e4986e47',0),('client1@mybank.de',3,'da544e8e4987023',0),('client1@mybank.de',4,'cm544e8e4987299',0),('client1@mybank.de',5,'dw544e8e49874e7',0),('client1@mybank.de',6,'sw544e8e498770b',0),('client1@mybank.de',7,'ij544e8e4987910',0),('client1@mybank.de',8,'gh544e8e4987b28',0),('client1@mybank.de',9,'wz544e8e4987d5b',0),('client1@mybank.de',10,'vr544e8e4987fa7',0),('client1@mybank.de',11,'xs544e8e49881ef',0),('client1@mybank.de',12,'lh544e8e4988427',0),('client1@mybank.de',13,'yh544e8e4988643',0),('client1@mybank.de',14,'ds544e8e4988878',0),('client1@mybank.de',15,'ed544e8e4988af2',0),('client1@mybank.de',16,'pc544e8e4988d11',0),('client1@mybank.de',17,'iz544e8e4989047',0),('client1@mybank.de',18,'rq544e8e49892a5',0),('client1@mybank.de',19,'ma544e8e498b365',0),('client1@mybank.de',20,'bn544e8e498b608',0),('client1@mybank.de',21,'xl544e8e498b83d',0),('client1@mybank.de',22,'cp544e8e498ba8f',0),('client1@mybank.de',23,'gr544e8e498bcaa',0),('client1@mybank.de',24,'ev544e8e498beed',0),('client1@mybank.de',25,'xu544e8e498c18d',0),('client1@mybank.de',26,'hx544e8e498c4b2',0),('client1@mybank.de',27,'mq544e8e498c732',0),('client1@mybank.de',28,'zl544e8e498c986',0),('client1@mybank.de',29,'mm544e8e498cbcd',0),('client1@mybank.de',30,'vd544e8e498ce23',0),('client1@mybank.de',31,'sr544e8e498d0bf',0),('client1@mybank.de',32,'lk544e8e498d2f3',0),('client1@mybank.de',33,'rm544e8e498d5b3',0),('client1@mybank.de',34,'oc544e8e498d813',0),('client1@mybank.de',35,'ir544e8e498da7e',0),('client1@mybank.de',36,'ip544e8e498dce5',0),('client1@mybank.de',37,'ai544e8e498df5f',0),('client1@mybank.de',38,'hk544e8e498e1cb',0),('client1@mybank.de',39,'bd544e8e498e44f',0),('client1@mybank.de',40,'jr544e8e498e6cd',0),('client1@mybank.de',41,'cc544e8e498e96d',0),('client1@mybank.de',42,'ti544e8e498ebf3',0),('client1@mybank.de',43,'oh544e8e498ee85',0),('client1@mybank.de',44,'bu544e8e498f107',0),('client1@mybank.de',45,'bo544e8e498f392',0),('client1@mybank.de',46,'yb544e8e498f699',0),('client1@mybank.de',47,'ti544e8e498f91e',0),('client1@mybank.de',48,'mk544e8e498fbab',0),('client1@mybank.de',49,'wb544e8e498fe72',0),('client1@mybank.de',50,'wc544e8e499011d',0),('client1@mybank.de',51,'hc544e8e499037c',0),('client1@mybank.de',52,'xk544e8e4990678',0),('client1@mybank.de',53,'yx544e8e4990929',0),('client1@mybank.de',54,'hj544e8e4990bc3',0),('client1@mybank.de',55,'ip544e8e4990e48',0),('client1@mybank.de',56,'sj544e8e49910ca',0),('client1@mybank.de',57,'fi544e8e4991341',0),('client1@mybank.de',58,'gu544e8e49915b5',0),('client1@mybank.de',59,'uj544e8e499184b',0),('client1@mybank.de',60,'vg544e8e4991b39',0),('client1@mybank.de',61,'dp544e8e4991dfe',0),('client1@mybank.de',62,'uu544e8e49920c4',0),('client1@mybank.de',63,'st544e8e4992351',0),('client1@mybank.de',64,'ae544e8e4992606',0),('client1@mybank.de',65,'zs544e8e49928a8',0),('client1@mybank.de',66,'pw544e8e4992ba0',0),('client1@mybank.de',67,'nq544e8e4992e78',0),('client1@mybank.de',68,'md544e8e49930e9',0),('client1@mybank.de',69,'zd544e8e4993362',0),('client1@mybank.de',70,'xh544e8e49935f2',0),('client1@mybank.de',71,'xx544e8e499397f',0),('client1@mybank.de',72,'hj544e8e4993c22',0),('client1@mybank.de',73,'km544e8e4993ebc',0),('client1@mybank.de',74,'nd544e8e4994137',0),('client1@mybank.de',75,'ev544e8e49943cb',0),('client1@mybank.de',76,'ho544e8e4994681',0),('client1@mybank.de',77,'gs544e8e4994a3d',0),('client1@mybank.de',78,'mx544e8e4994d1b',0),('client1@mybank.de',79,'ii544e8e4994fad',0),('client1@mybank.de',80,'dl544e8e499522b',0),('client1@mybank.de',81,'od544e8e49954c0',0),('client1@mybank.de',82,'ot544e8e499576c',0),('client1@mybank.de',83,'cc544e8e4995a31',0),('client1@mybank.de',84,'am544e8e4995d47',0),('client1@mybank.de',85,'rf544e8e4996004',0),('client1@mybank.de',86,'fm544e8e49962c7',0),('client1@mybank.de',87,'at544e8e4996554',0),('client1@mybank.de',88,'bf544e8e4996810',0),('client1@mybank.de',89,'wh544e8e4996ae6',0),('client1@mybank.de',90,'ta544e8e4996da8',0),('client1@mybank.de',91,'vq544e8e499706b',0),('client1@mybank.de',92,'xm544e8e4997326',0),('client1@mybank.de',93,'jx544e8e49975e2',0),('client1@mybank.de',94,'du544e8e4997876',0),('client1@mybank.de',95,'rm544e8e4997af4',0),('client1@mybank.de',96,'ac544e8e4997dd5',0),('client1@mybank.de',97,'yl544e8e4998088',0),('client1@mybank.de',98,'eu544e8e4998333',0),('client1@mybank.de',99,'un544e8e49985fe',0),('client1@mybank.de',100,'lf544e8e49988be',0),('client2@mybank.de',1,'cv544e8e71d7e76',0),('client2@mybank.de',2,'tv544e8e71d812f',0),('client2@mybank.de',3,'xw544e8e71d83be',0),('client2@mybank.de',4,'on544e8e71d8638',0),('client2@mybank.de',5,'yy544e8e71d8871',0),('client2@mybank.de',6,'gb544e8e71d8acf',0),('client2@mybank.de',7,'sj544e8e71d8d0b',0),('client2@mybank.de',8,'sy544e8e71d8f5e',0),('client2@mybank.de',9,'am544e8e71d91a6',0),('client2@mybank.de',10,'rc544e8e71d9405',0),('client2@mybank.de',11,'vs544e8e71d962b',0),('client2@mybank.de',12,'st544e8e71d9891',0),('client2@mybank.de',13,'mc544e8e71d9aba',0),('client2@mybank.de',14,'ce544e8e71d9d66',0),('client2@mybank.de',15,'il544e8e71d9fc6',0),('client2@mybank.de',16,'my544e8e71da241',0),('client2@mybank.de',17,'am544e8e71da49c',0),('client2@mybank.de',18,'sy544e8e71da6da',0),('client2@mybank.de',19,'oy544e8e71da929',0),('client2@mybank.de',20,'oa544e8e71dac2e',0),('client2@mybank.de',21,'bi544e8e71dae6d',0),('client2@mybank.de',22,'la544e8e71db0bb',0),('client2@mybank.de',23,'qm544e8e71db33c',0),('client2@mybank.de',24,'xh544e8e71db596',0),('client2@mybank.de',25,'sm544e8e71db819',0),('client2@mybank.de',26,'gj544e8e71db9ef',0),('client2@mybank.de',27,'cx544e8e71dbbcb',0),('client2@mybank.de',28,'za544e8e71dbee5',0),('client2@mybank.de',29,'nv544e8e71dc10e',0),('client2@mybank.de',30,'bx544e8e71dc333',0),('client2@mybank.de',31,'kv544e8e71dc54f',0),('client2@mybank.de',32,'uv544e8e71dc73c',0),('client2@mybank.de',33,'xq544e8e71dc93d',0),('client2@mybank.de',34,'js544e8e71dcb35',0),('client2@mybank.de',35,'pe544e8e71dcd3c',0),('client2@mybank.de',36,'ek544e8e71dcf24',0),('client2@mybank.de',37,'jq544e8e71dd159',0),('client2@mybank.de',38,'bh544e8e71dd37c',0),('client2@mybank.de',39,'kx544e8e71dd582',0),('client2@mybank.de',40,'sy544e8e71dd781',0),('client2@mybank.de',41,'wx544e8e71dd93e',0),('client2@mybank.de',42,'kb544e8e71ddafd',0),('client2@mybank.de',43,'ru544e8e71ddcd6',0),('client2@mybank.de',44,'oh544e8e71ddf15',0),('client2@mybank.de',45,'cn544e8e71de134',0),('client2@mybank.de',46,'so544e8e71de33e',0),('client2@mybank.de',47,'zg544e8e71de55d',0),('client2@mybank.de',48,'zj544e8e71de735',0),('client2@mybank.de',49,'tf544e8e71de975',0),('client2@mybank.de',50,'om544e8e71debe4',0),('client2@mybank.de',51,'ja544e8e71dedef',0),('client2@mybank.de',52,'rs544e8e71df06e',0),('client2@mybank.de',53,'zc544e8e71df35c',0),('client2@mybank.de',54,'rc544e8e71df658',0),('client2@mybank.de',55,'ni544e8e71df9ba',0),('client2@mybank.de',56,'zt544e8e71dfc7b',0),('client2@mybank.de',57,'ou544e8e71dfedc',0),('client2@mybank.de',58,'yt544e8e71e017a',0),('client2@mybank.de',59,'ba544e8e71e03fa',0),('client2@mybank.de',60,'jz544e8e71e064e',0),('client2@mybank.de',61,'io544e8e71e08d4',0),('client2@mybank.de',62,'ii544e8e71e0b13',0),('client2@mybank.de',63,'vv544e8e71e0d87',0),('client2@mybank.de',64,'ku544e8e71e10b1',0),('client2@mybank.de',65,'ib544e8e71e1366',0),('client2@mybank.de',66,'rf544e8e71e1600',0),('client2@mybank.de',67,'nm544e8e71e1897',0),('client2@mybank.de',68,'vk544e8e71e1b11',0),('client2@mybank.de',69,'qk544e8e71e1d88',0),('client2@mybank.de',70,'xa544e8e71e202e',0),('client2@mybank.de',71,'fo544e8e71e2363',0),('client2@mybank.de',72,'im544e8e71e25a5',0),('client2@mybank.de',73,'pk544e8e71e2823',0),('client2@mybank.de',74,'pq544e8e71e2a70',0),('client2@mybank.de',75,'nl544e8e71e2cde',0),('client2@mybank.de',76,'qv544e8e71e2f61',0),('client2@mybank.de',77,'pk544e8e71e3216',0),('client2@mybank.de',78,'vs544e8e71e34b5',0),('client2@mybank.de',79,'xy544e8e71e373b',0),('client2@mybank.de',80,'in544e8e71e39a8',0),('client2@mybank.de',81,'os544e8e71e3c32',0),('client2@mybank.de',82,'cz544e8e71e3ef2',0),('client2@mybank.de',83,'oz544e8e71e4275',0),('client2@mybank.de',84,'ag544e8e71e4540',0),('client2@mybank.de',85,'fd544e8e71e47d0',0),('client2@mybank.de',86,'vg544e8e71e4a51',0),('client2@mybank.de',87,'pf544e8e71e4ce7',0),('client2@mybank.de',88,'cw544e8e71e4fbc',0),('client2@mybank.de',89,'ee544e8e71e5307',0),('client2@mybank.de',90,'sw544e8e71e55cb',0),('client2@mybank.de',91,'jq544e8e71e585c',0),('client2@mybank.de',92,'qb544e8e71e5ada',0),('client2@mybank.de',93,'ep544e8e71e5d61',0),('client2@mybank.de',94,'hu544e8e71e5ff1',0),('client2@mybank.de',95,'ci544e8e71e638a',0),('client2@mybank.de',96,'qg544e8e71e6645',0),('client2@mybank.de',97,'xw544e8e71e68ea',0),('client2@mybank.de',98,'ci544e8e71e6b88',0),('client2@mybank.de',99,'xl544e8e71e6e7e',0),('client2@mybank.de',100,'is544e8e71e7110',0),('client3@mybank.de',1,'is544e8f18c28c2',0),('client3@mybank.de',2,'gd544e8f18c2b82',0),('client3@mybank.de',3,'as544e8f18c2d80',0),('client3@mybank.de',4,'cl544e8f18c2f83',0),('client3@mybank.de',5,'je544e8f18c31bc',0),('client3@mybank.de',6,'ri544e8f18c33f5',0),('client3@mybank.de',7,'vb544e8f18c3617',0),('client3@mybank.de',8,'cj544e8f18c3810',0),('client3@mybank.de',9,'mh544e8f18c3a49',0),('client3@mybank.de',10,'hn544e8f18c3ca4',0),('client3@mybank.de',11,'zw544e8f18c3efa',0),('client3@mybank.de',12,'xw544e8f18c412f',0),('client3@mybank.de',13,'uv544e8f18c4356',0),('client3@mybank.de',14,'qb544e8f18c45ac',0),('client3@mybank.de',15,'ks544e8f18c47d6',0),('client3@mybank.de',16,'fw544e8f18c4a0f',0),('client3@mybank.de',17,'mt544e8f18c4c9e',0),('client3@mybank.de',18,'be544e8f18c4f16',0),('client3@mybank.de',19,'zi544e8f18c5194',0),('client3@mybank.de',20,'zc544e8f18c541c',0),('client3@mybank.de',21,'ea544e8f18c568a',0),('client3@mybank.de',22,'in544e8f18c5a6a',0),('client3@mybank.de',23,'xp544e8f18c5d00',0),('client3@mybank.de',24,'mj544e8f18c5f6d',0),('client3@mybank.de',25,'rx544e8f18c6200',0),('client3@mybank.de',26,'cd544e8f18c648f',0),('client3@mybank.de',27,'xq544e8f18c66f3',0),('client3@mybank.de',28,'pl544e8f18c6b0f',0),('client3@mybank.de',29,'zu544e8f18c6da6',0),('client3@mybank.de',30,'zb544e8f18c7025',0),('client3@mybank.de',31,'vb544e8f18c72d8',0),('client3@mybank.de',32,'dn544e8f18c7581',0),('client3@mybank.de',33,'fz544e8f18c780b',0),('client3@mybank.de',34,'me544e8f18c7bab',0),('client3@mybank.de',35,'qs544e8f18c7e43',0),('client3@mybank.de',36,'xi544e8f18c80f4',0),('client3@mybank.de',37,'ox544e8f18c838f',0),('client3@mybank.de',38,'ks544e8f18c8674',0),('client3@mybank.de',39,'jd544e8f18c89b3',0),('client3@mybank.de',40,'ou544e8f18c8dbf',0),('client3@mybank.de',41,'pl544e8f18c9118',0),('client3@mybank.de',42,'ax544e8f18c9472',0),('client3@mybank.de',43,'od544e8f18c9778',0),('client3@mybank.de',44,'gt544e8f18c9aa1',0),('client3@mybank.de',45,'ic544e8f18c9d77',0),('client3@mybank.de',46,'rt544e8f18ca058',0),('client3@mybank.de',47,'fi544e8f18ca2ff',0),('client3@mybank.de',48,'kh544e8f18ca5ee',0),('client3@mybank.de',49,'zl544e8f18ca7f2',0),('client3@mybank.de',50,'zs544e8f18caa1e',0),('client3@mybank.de',51,'rk544e8f18cad02',0),('client3@mybank.de',52,'ch544e8f18caf3b',0),('client3@mybank.de',53,'kz544e8f18cb15f',0),('client3@mybank.de',54,'ef544e8f18cb36a',0),('client3@mybank.de',55,'bq544e8f18cb58c',0),('client3@mybank.de',56,'qv544e8f18cb7f2',0),('client3@mybank.de',57,'yk544e8f18cb9ad',0),('client3@mybank.de',58,'zn544e8f18cbbc6',0),('client3@mybank.de',59,'kp544e8f18cbdca',0),('client3@mybank.de',60,'sc544e8f18cc001',0),('client3@mybank.de',61,'sg544e8f18cc21b',0),('client3@mybank.de',62,'ly544e8f18cc413',0),('client3@mybank.de',63,'ma544e8f18cc5f7',0),('client3@mybank.de',64,'fn544e8f18cc814',0),('client3@mybank.de',65,'px544e8f18cc9d0',0),('client3@mybank.de',66,'jv544e8f18ccbb6',0),('client3@mybank.de',67,'wk544e8f18cce7e',0),('client3@mybank.de',68,'mz544e8f18cd096',0),('client3@mybank.de',69,'ov544e8f18cd2cc',0),('client3@mybank.de',70,'ls544e8f18cd4de',0),('client3@mybank.de',71,'qq544e8f18cd707',0),('client3@mybank.de',72,'by544e8f18cd908',0),('client3@mybank.de',73,'nh544e8f18cdb09',0),('client3@mybank.de',74,'hv544e8f18cdcc9',0),('client3@mybank.de',75,'yl544e8f18cdecc',0),('client3@mybank.de',76,'vz544e8f18ce10c',0),('client3@mybank.de',77,'om544e8f18ce31d',0),('client3@mybank.de',78,'bo544e8f18ce54a',0),('client3@mybank.de',79,'yt544e8f18ce821',0),('client3@mybank.de',80,'uj544e8f18cea91',0),('client3@mybank.de',81,'eg544e8f18ced08',0),('client3@mybank.de',82,'fu544e8f18cf052',0),('client3@mybank.de',83,'mc544e8f18cf2cb',0),('client3@mybank.de',84,'uj544e8f18cf539',0),('client3@mybank.de',85,'vl544e8f18cf7bc',0),('client3@mybank.de',86,'ne544e8f18cf9fc',0),('client3@mybank.de',87,'dc544e8f18cfc43',0),('client3@mybank.de',88,'bi544e8f18cfefd',0),('client3@mybank.de',89,'js544e8f18d0186',0),('client3@mybank.de',90,'rn544e8f18d03e1',0),('client3@mybank.de',91,'ea544e8f18d0629',0),('client3@mybank.de',92,'ky544e8f18d08a8',0),('client3@mybank.de',93,'nl544e8f18d0b06',0),('client3@mybank.de',94,'jv544e8f18d0d5f',0),('client3@mybank.de',95,'ce544e8f18d10a4',0),('client3@mybank.de',96,'zk544e8f18d3938',0),('client3@mybank.de',97,'bv544e8f18d3eb3',0),('client3@mybank.de',98,'gm544e8f18d4116',0),('client3@mybank.de',99,'sg544e8f18d4377',0),('client3@mybank.de',100,'wf544e8f18d462f',0);
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
  `password` varchar(64) NOT NULL,
  `is_employee` tinyint(1) NOT NULL DEFAULT '0',
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USERS`
--

LOCK TABLES `USERS` WRITE;
/*!40000 ALTER TABLE `USERS` DISABLE KEYS */;
INSERT INTO `USERS` VALUES ('employee1@mybank.de','12345',1,1),('employee2@mybank.de','12345',1,0),('client1@mybank.de','12345',0,1),('client2@mybank.de','12345',0,1),('client3@mybank.de','12345',0,1),('client4@mybank.de','12345',0,0);
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

-- Dump completed on 2014-10-27 19:35:35

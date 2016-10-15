-- MySQL dump 10.13  Distrib 5.7.9, for osx10.9 (x86_64)
--
-- Host: localhost    Database: cng_db
-- ------------------------------------------------------
-- Server version	5.7.11

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
-- Table structure for table `career`
--

DROP TABLE IF EXISTS `career`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `career` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `organization` varchar(200) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `job_description` text,
  `job_achievement` text,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_career_idx` (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `career`
--

LOCK TABLES `career` WRITE;
/*!40000 ALTER TABLE `career` DISABLE KEYS */;
INSERT INTO `career` VALUES (1,1,'kkk','ooo','oo','kkkk','2016-01-01','2016-01-01',90909),(2,1,NULL,'jhjhjh','jhjhjh','jhjhjh','2016-08-01','2016-08-02',1470194568),(3,1,'editada3','yuyuyuy','uyuyuy','uyuyuyuy','2016-08-02','2016-08-02',1470194793),(4,1,'otro','otro','otro','otro','2010-08-05','2016-08-01',1470536650),(5,1,'mas','mas','mas','mas','2016-08-06','2016-08-06',1470536767),(6,1,'otro mas','otro mas','otro mas','otro mas','2016-08-06','2016-08-06',1470537494),(7,1,'mas mas ','mas mas','mas mas','mas mas','2016-08-06','2016-08-06',1470542308);
/*!40000 ALTER TABLE `career` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_name_unique` (`name`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'Zend Framework','zend-framework'),(2,'PHP','php'),(3,'MySQL','mysql');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `course`
--

DROP TABLE IF EXISTS `course`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `content` longtext,
  `created` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `course_category_id_index` (`category_id`),
  KEY `course_ibfk_3` (`author_id`),
  CONSTRAINT `course_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `course_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`),
  CONSTRAINT `course_ibfk_3` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `course`
--

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;
INSERT INTO `course` VALUES (3,'Mi primer curso','mi-primer-curso','este es mi primer curso de zend framework',1468084617,1,1),(4,'tercer curso','tercer-curso-ok','mi tercer curso',1468093494,3,1),(5,'cuarto curso','cuarto-curso','mi cuarto curso',1468093609,2,1),(8,'Curso creado por alma','curso-alma','Este curso fue creado por Alma',1468210681,1,7);
/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `education`
--

DROP TABLE IF EXISTS `education`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `education` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `degree_id` int(11) NOT NULL,
  `academic_specialty` varchar(100) DEFAULT NULL,
  `academic_achievement` text,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `organization` varchar(100) DEFAULT NULL,
  `created` int(11) NOT NULL,
  `career` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_education_1_idx` (`author_id`),
  KEY `fk_education_2_idx` (`degree_id`),
  CONSTRAINT `fk_education_1` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`),
  CONSTRAINT `fk_education_2` FOREIGN KEY (`degree_id`) REFERENCES `education_degree` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `education`
--

LOCK TABLES `education` WRITE;
/*!40000 ALTER TABLE `education` DISABLE KEYS */;
INSERT INTO `education` VALUES (1,9,NULL,'ok','2016-08-04','2016-08-07',1,'itcm',1470551792,'sistemas computacionales');
/*!40000 ALTER TABLE `education` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `education_degree`
--

DROP TABLE IF EXISTS `education_degree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `education_degree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `degree` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `degree_UNIQUE` (`degree`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `education_degree`
--

LOCK TABLES `education_degree` WRITE;
/*!40000 ALTER TABLE `education_degree` DISABLE KEYS */;
INSERT INTO `education_degree` VALUES (5,'Bachillerato'),(10,'Doctorado'),(1,'Educación inicial'),(7,'Ingeniería'),(6,'Licenciatura'),(9,'Maestría'),(4,'Preparatoria'),(2,'Primaria'),(8,'Profesional técnico'),(3,'Secuendaria');
/*!40000 ALTER TABLE `education_degree` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `content` longtext NOT NULL,
  `created` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_slug_category_id_unique` (`slug`,`category_id`),
  KEY `post_category_id_index` (`category_id`),
  KEY `post_author_id_index` (`author_id`),
  KEY `course_author_id_index` (`author_id`),
  CONSTRAINT `post_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `post_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post`
--

LOCK TABLES `post` WRITE;
/*!40000 ALTER TABLE `post` DISABLE KEYS */;
INSERT INTO `post` VALUES (4,'hola mundo','holamundo','hola mundo',1468083857,1,1);
/*!40000 ALTER TABLE `post` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project`
--

DROP TABLE IF EXISTS `project`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_name` varchar(140) NOT NULL,
  `abstract` varchar(140) DEFAULT NULL,
  `webpage` varchar(255) DEFAULT NULL,
  `project_type` varchar(100) DEFAULT NULL,
  `start_date` int(11) DEFAULT NULL,
  `end_date` int(11) DEFAULT NULL,
  `disabled` int(11) DEFAULT NULL,
  `created` int(11) NOT NULL,
  `updated` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_projects_1_idx` (`user_id`),
  CONSTRAINT `fk_projects_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project`
--

LOCK TABLES `project` WRITE;
/*!40000 ALTER TABLE `project` DISABLE KEYS */;
/*!40000 ALTER TABLE `project` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topic`
--

DROP TABLE IF EXISTS `topic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `content` longtext,
  `created` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topic_author_id_index` (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topic`
--

LOCK TABLES `topic` WRITE;
/*!40000 ALTER TABLE `topic` DISABLE KEYS */;
INSERT INTO `topic` VALUES (1,'mi primer tema','mi-primer-tema','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec rutrum libero vitae risus egestas congue. Nam at libero orci. Mauris lobortis justo sed dapibus tincidunt. Cras placerat porta magna. Aenean dictum porta diam, eget hendrerit arcu pretium a. Praesent semper justo nisl, at fermentum tellus suscipit in. Pellentesque lacinia tristique urna ac pretium.',1468098669,1,6),(2,'mi segundo tema','mi-segundo-tema','Interdum et malesuada fames ac ante ipsum primis in faucibus. Vestibulum in consectetur mi, vitae fringilla nisl. Morbi iaculis sed purus id placerat. Aenean vitae luctus odio. Phasellus elementum diam eget tristique euismod. Suspendisse vehicula euismod orci sit amet congue. Aliquam erat volutpat. Duis tortor sapien, blandit sed eleifend consequat, fringilla feugiat libero. Vestibulum convallis velit felis, sit amet eleifend magna posuere a. Fusce vestibulum nec turpis nec iaculis. Nulla rhoncus consectetur felis, interdum viverra purus vestibulum varius. Phasellus convallis lacinia rutrum. Duis at posuere leo. Suspendisse potenti. Nullam in diam aliquam, iaculis diam ut, molestie orci.',1468106489,1,6),(3,'mi tercer tema','mi-tercer-tema','hola mundo cruel',1468119438,1,6),(4,'mi cuarto tema','mi-cuarto-tema','Aenean feugiat in leo placerat lobortis. Suspendisse ac nisi a purus commodo sodales mollis ut nisl. Nam volutpat dui at velit tristique, ac lacinia ex imperdiet. Sed ut vulputate purus. Morbi a pulvinar purus, vel viverra enim. Cras mattis ut mi ac rutrum. In hac habitasse platea dictumst. Vestibulum mollis convallis aliquet. Nulla facilisi. Aenean congue posuere laoreet. Aenean non bibendum lectus, sed bibendum lacus.',1468119489,1,6),(5,'mi quinto tema','mi-quinto-tema','Aenean feugiat in leo placerat lobortis. Suspendisse ac nisi a purus commodo sodales mollis ut nisl. Nam volutpat dui at velit tristique, ac lacinia ex imperdiet. Sed ut vulputate purus. Morbi a pulvinar purus, vel viverra enim. Cras mattis ut mi ac rutrum. In hac habitasse platea dictumst. Vestibulum mollis convallis aliquet. Nulla facilisi. Aenean congue posuere laoreet. Aenean non bibendum lectus, sed bibendum lacus.',1468119536,1,6),(6,'mi ultimo tema','mi-ultimo-tema','Aenean feugiat in leo placerat lobortis. Suspendisse ac nisi a purus commodo sodales mollis ut nisl. Nam volutpat dui at velit tristique, ac lacinia ex imperdiet. Sed ut vulputate purus. Morbi a pulvinar purus, vel viverra enim. Cras mattis ut mi ac rutrum. In hac habitasse platea dictumst. Vestibulum mollis convallis aliquet. Nulla facilisi. Aenean congue posuere laoreet. Aenean non bibendum lectus, sed bibendum lacus.',1468119572,1,6),(7,'mi topic','mi-topic','my topic of mysql',1468164954,1,7),(8,'Mi tema','mi-tema','Este tema fue creado por alma',1468210738,7,8),(9,'Tema con video','tema-con-video','<p><a href=\"https://youtu.be/3CjFXRLyzE8\">https://youtu.be/3CjFXRLyzE8</a></p>',1468212028,1,7),(10,'este es el ultimo tema','ultimo-tema-ok','<p>ultimo tema</p>',1468604582,1,6),(13,'hola hola','hola-hola','<p>hola</p>',1468606490,1,4),(14,'topic NO','topic-no','<p>topic</p>',1468612780,1,5);
/*!40000 ALTER TABLE `topic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uploads`
--

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` char(60) NOT NULL,
  `created` int(11) NOT NULL,
  `user_group` enum('1','2','3') NOT NULL DEFAULT '1',
  `nickname` varchar(20) NOT NULL,
  `gender` enum('1','0') NOT NULL DEFAULT '1',
  `age` int(3) DEFAULT NULL,
  `bio` varchar(140) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_unique` (`email`),
  UNIQUE KEY `nickname_UNIQUE` (`nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Nubia','Mor','moreira.nubia@gmail.com','$2y$12$pX7tHyFmHdD.tv/8X7126emLc95HvK2CMRXnEk2EqdiSY9Xfnypq2',1467601359,'1','nmoreira','1',30,'Estudiante y desarrollador web'),(2,'ana','lopez','ana@abc.com','$2y$12$/.eKAxqS.6wDlAgsznHTAej/j9gtg6rH6qPeNcTOWwYzSqUkzaCse',1467603632,'1','analop','1',NULL,NULL),(3,'luis javier','iraheta','luisj.19@gmail.com','$2y$12$i1U2YoS1Ez/67g9nBQxzZOTh/frEdVnB2pM8PlAntLGzG6x0VouKC',1467604650,'1','luisj','1',NULL,NULL),(4,'lola','lopez','lola@a.com','$2y$12$g2.8UA78tvcPrL0sV5zJgOsJwt7E1JjdkhepSzRpk1ztYRawM2GW.',1467691124,'1','lolopez','1',NULL,NULL),(5,'Javier','Iraheta','l_j_222@gmail.com','$2y$12$7mlKHSlXEztayKGJ5wuT.eSAgHbHe89Xh0xbyc8XdXMlLBQiyDG0i',1468084396,'1','javierirh','1',NULL,NULL),(6,'ana maria','salazar','asalazar@outlook.mx','$2y$12$6ALs1mVuzVzX9gapYCh7Xe6/hFlY7AqpwRXaXQgOSiPKkEFoMLYlW',1468183802,'1','anasal','1',NULL,NULL),(7,'Alma','Morales','amorales@gmail.com','$2y$12$gNawY1ZbQCLcwDWtfvyez.411vEXk8x7W27s6elMd8HX3/X1MvAL2',1468209565,'1','alamor','1',NULL,NULL),(8,'Santa','Vallejo','svallejo@itspr.edu','$2y$12$ckb7HqLKkQ.bMpKWedHmuePZNluVBCcYW4fnOfT.wpQRoLx3YzUG6',1468599158,'1','svallejo','1',NULL,NULL),(9,'César','Moreira','cesar@gmail.com','$2y$12$WopjRZilD98BUXo6236rTedFPiZ47b09iJ/LrYNXF0qiAWF4ZDKri',1469581208,'1','cesarito','0',NULL,NULL),(10,'lorena','vazquez','lvazquez@gmail.com','$2y$12$IpZ5PF3FroAFSBWA6D/SwOGtrCQh5SmDOssd17y8yFm2jdx9ewOWy',1469581777,'1','lvazquez','1',NULL,NULL),(11,'mario','martinez','mario@gmail.com','$2y$12$WmiTWBHwZ3oJSBHj.2GlKOf446IcdzBG7GdGSdq2Z/ii/Zcp4lQaO',1469581846,'1','mariom','0',NULL,NULL),(12,'jgjgjgjgjgj','urururururruru','moreira.nubia1@gmail.com','$2y$12$7MYAYv605BPgYrfBpE2/kODcuQ3faLeFYuK5mP8K41rAA3bypQoAG',1469591892,'1','ixixixix','1',NULL,NULL),(13,'lolo','mendoza','lolo@gmail.com','$2y$12$/G2cMSVRufOYqfnsmzk3z.Z7/H1vWRJgRu6dsIBDwS.vS45WjDjCi',1469592686,'1','lolo020','0',NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(254) DEFAULT NULL,
  `password` binary(60) DEFAULT NULL,
  `avatar_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(25) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `bio` tinytext,
  `location` tinytext,
  `gender` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_username` (`username`),
  KEY `idx_email` (`email`(191))
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

-- Dump completed on 2016-08-07  2:46:01

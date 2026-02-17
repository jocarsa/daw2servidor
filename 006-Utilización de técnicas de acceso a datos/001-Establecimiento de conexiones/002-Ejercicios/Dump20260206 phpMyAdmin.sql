CREATE DATABASE IF NOT EXISTS videojuegos_asir
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE videojuegos_asir;


-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: videojuegos_asir
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categoria_juego`
--

DROP TABLE IF EXISTS `categoria_juego`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria_juego` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `num_juegos` int DEFAULT '1',
  `tiene_juegos` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_juego`
--

LOCK TABLES `categoria_juego` WRITE;
/*!40000 ALTER TABLE `categoria_juego` DISABLE KEYS */;
INSERT INTO `categoria_juego` VALUES (1,'Acción','Juegos centrados en combate y reflejos',5,1),(2,'Rol','Juegos con progresión de personaje',1,0),(3,'Aventura','Juegos basados en exploración y narrativa',3,1),(4,'RPG',NULL,3,1),(5,'Roguelike',NULL,2,1),(6,'Sandbox',NULL,1,1),(7,'Indie',NULL,2,1),(11,'Larian Studios','Estudio con 1 videojuegos',1,0),(12,'FromSoftware','Estudio con 1 videojuegos',1,0),(13,'CD PROJEKT RED','Estudio con 1 videojuegos',1,0),(14,'Supergiant Games','Estudio con 2 videojuegos',1,0),(15,'Mojang Studios','Estudio con 1 videojuegos',1,0),(16,'MercurySteam','Estudio con 1 videojuegos',1,0),(18,'Competitivo','Videojuegos orientados a la competición entre jugadores',3,1),(29,'Estrategia','Juegos de planificación y táctica',1,0);
/*!40000 ALTER TABLE `categoria_juego` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `desarrollador`
--

DROP TABLE IF EXISTS `desarrollador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `desarrollador` (
  `id_desarrollador` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(120) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `ciudad` varchar(80) DEFAULT NULL,
  `pais` varchar(80) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_alta` date NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `id_estudio` int DEFAULT NULL,
  PRIMARY KEY (`id_desarrollador`),
  KEY `fk_dev_estudio` (`id_estudio`),
  CONSTRAINT `fk_dev_estudio` FOREIGN KEY (`id_estudio`) REFERENCES `estudio` (`id_estudio`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `desarrollador`
--

LOCK TABLES `desarrollador` WRITE;
/*!40000 ALTER TABLE `desarrollador` DISABLE KEYS */;
INSERT INTO `desarrollador` VALUES (1,'Ana','Serrano','ana.serrano@ejemplo.com','Ghent','Bélgica','1991-02-11','2023-01-10',1,1),(2,'Luis','Pérez','luis.perez@ejemplo.com','Tokyo','Japón','1989-07-19','2022-05-03',1,2),(3,'Marta','Kowalska','marta.kowalska@ejemplo.com','Warsaw','Polonia','1993-12-01','2021-09-01',1,3),(4,'Noah','Reed',NULL,'San Francisco','USA','1996-04-09','2020-06-20',1,4),(5,'Erik','Lind','erik.lind@ejemplo.com','Stockholm','Suecia','1990-10-22','2019-03-14',0,5),(6,'Clara','Ruiz','clara.ruiz@ejemplo.com',NULL,'España','2000-08-05','2024-09-10',1,NULL);
/*!40000 ALTER TABLE `desarrollador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `disponibilidad`
--

DROP TABLE IF EXISTS `disponibilidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `disponibilidad` (
  `id_tienda` int NOT NULL,
  `id_videojuego` int NOT NULL,
  `precio` decimal(8,2) NOT NULL,
  `stock` int DEFAULT NULL,
  `url_producto` varchar(250) DEFAULT NULL,
  `fecha_alta` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_tienda`,`id_videojuego`),
  KEY `fk_disp_juego` (`id_videojuego`),
  CONSTRAINT `fk_disp_juego` FOREIGN KEY (`id_videojuego`) REFERENCES `videojuego` (`id_videojuego`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_disp_tienda` FOREIGN KEY (`id_tienda`) REFERENCES `tienda` (`id_tienda`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `disponibilidad`
--

LOCK TABLES `disponibilidad` WRITE;
/*!40000 ALTER TABLE `disponibilidad` DISABLE KEYS */;
INSERT INTO `disponibilidad` VALUES (1,1,59.99,9999,'https://store.steampowered.com','2023-08-03 09:00:00'),(1,2,59.99,9999,'https://store.steampowered.com','2022-02-25 09:00:00'),(1,3,59.99,9999,'https://store.steampowered.com','2020-12-10 09:00:00'),(1,4,24.99,9999,'https://store.steampowered.com','2020-09-17 09:00:00'),(1,5,26.95,9999,'https://store.steampowered.com','2011-11-18 09:00:00'),(2,3,59.99,NULL,'https://www.gog.com','2020-12-10 10:00:00'),(2,5,26.95,NULL,'https://www.gog.com','2011-11-18 10:00:00'),(3,1,69.99,NULL,'https://store.playstation.com','2023-09-06 10:00:00'),(3,2,59.99,NULL,'https://store.playstation.com','2022-02-25 10:00:00'),(3,3,59.99,NULL,'https://store.playstation.com','2020-12-10 10:00:00'),(4,4,24.99,NULL,'https://www.nintendo.com','2020-09-17 11:00:00'),(4,5,26.95,NULL,'https://www.nintendo.com','2017-05-12 11:00:00'),(4,8,59.99,NULL,'https://www.nintendo.com','2021-10-08 11:00:00'),(5,4,29.99,0,NULL,'2021-01-01 12:00:00'),(5,5,29.99,15,NULL,'2022-01-01 12:00:00'),(5,8,59.99,10,NULL,'2021-10-08 12:00:00');
/*!40000 ALTER TABLE `disponibilidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dlc`
--

DROP TABLE IF EXISTS `dlc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dlc` (
  `id_dlc` int NOT NULL AUTO_INCREMENT,
  `id_videojuego` int NOT NULL,
  `titulo` varchar(160) NOT NULL,
  `fecha_lanzamiento` date DEFAULT NULL,
  `precio` decimal(8,2) DEFAULT NULL,
  PRIMARY KEY (`id_dlc`),
  KEY `fk_dlc_juego` (`id_videojuego`),
  CONSTRAINT `fk_dlc_juego` FOREIGN KEY (`id_videojuego`) REFERENCES `videojuego` (`id_videojuego`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dlc`
--

LOCK TABLES `dlc` WRITE;
/*!40000 ALTER TABLE `dlc` DISABLE KEYS */;
INSERT INTO `dlc` VALUES (1,3,'Cyberpunk 2077: Phantom Liberty','2023-09-26',29.99),(2,2,'Elden Ring: Shadow of the Erdtree','2024-06-21',39.99),(3,5,'Minecraft: Skin Pack (ejemplo)',NULL,2.99),(4,1,'Baldur\'s Gate 3: Extra Pack (ejemplo)',NULL,4.99);
/*!40000 ALTER TABLE `dlc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudio`
--

DROP TABLE IF EXISTS `estudio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudio` (
  `id_estudio` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `pais` varchar(80) DEFAULT NULL,
  `ciudad` varchar(80) DEFAULT NULL,
  `fundado_en` year DEFAULT NULL,
  `web` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_estudio`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudio`
--

LOCK TABLES `estudio` WRITE;
/*!40000 ALTER TABLE `estudio` DISABLE KEYS */;
INSERT INTO `estudio` VALUES (1,'Larian Studios','Bélgica','Ghent',1996,'https://larian.com'),(2,'FromSoftware','Japón','Tokyo',1986,'https://www.fromsoftware.jp'),(3,'CD PROJEKT RED','Polonia','Warsaw',2002,'https://www.cdprojektred.com'),(4,'Supergiant Games','USA','San Francisco',2009,'https://www.supergiantgames.com'),(5,'Mojang Studios','Suecia','Stockholm',NULL,'https://www.minecraft.net'),(6,'MercurySteam','España','Madrid',2002,'https://www.mercurysteam.com');
/*!40000 ALTER TABLE `estudio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genero`
--

DROP TABLE IF EXISTS `genero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genero` (
  `id_genero` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(60) NOT NULL,
  PRIMARY KEY (`id_genero`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (1,'RPG'),(2,'Acción'),(3,'Aventura'),(4,'Roguelike'),(5,'Sandbox'),(6,'Indie');
/*!40000 ALTER TABLE `genero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participacion`
--

DROP TABLE IF EXISTS `participacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participacion` (
  `id_desarrollador` int NOT NULL,
  `id_videojuego` int NOT NULL,
  `id_rol` int NOT NULL,
  `horas` int DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  PRIMARY KEY (`id_desarrollador`,`id_videojuego`,`id_rol`),
  KEY `fk_part_juego` (`id_videojuego`),
  KEY `fk_part_rol` (`id_rol`),
  CONSTRAINT `fk_part_dev` FOREIGN KEY (`id_desarrollador`) REFERENCES `desarrollador` (`id_desarrollador`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_part_juego` FOREIGN KEY (`id_videojuego`) REFERENCES `videojuego` (`id_videojuego`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_part_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participacion`
--

LOCK TABLES `participacion` WRITE;
/*!40000 ALTER TABLE `participacion` DISABLE KEYS */;
INSERT INTO `participacion` VALUES (1,1,2,600,'2021-01-01','2023-07-15'),(1,1,4,NULL,'2023-01-10','2023-08-01'),(2,2,1,900,'2020-03-01','2022-02-01'),(3,3,1,850,'2018-09-01',NULL),(3,3,4,200,'2020-01-01','2020-12-01'),(4,4,2,500,'2019-06-01','2020-08-01'),(4,6,2,300,'2023-01-01',NULL),(5,5,1,1200,'2010-01-01','2015-01-01');
/*!40000 ALTER TABLE `participacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plataforma`
--

DROP TABLE IF EXISTS `plataforma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `plataforma` (
  `id_plataforma` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  `fabricante` varchar(80) DEFAULT NULL,
  `tipo` enum('Consola','PC','Móvil','Nube') NOT NULL,
  `lanzamiento` year DEFAULT NULL,
  PRIMARY KEY (`id_plataforma`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plataforma`
--

LOCK TABLES `plataforma` WRITE;
/*!40000 ALTER TABLE `plataforma` DISABLE KEYS */;
INSERT INTO `plataforma` VALUES (1,'PC',NULL,'PC',NULL),(2,'PlayStation 5','Sony','Consola',2020),(3,'Xbox Series X|S','Microsoft','Consola',2020),(4,'Nintendo Switch','Nintendo','Consola',2017);
/*!40000 ALTER TABLE `plataforma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Programador/a'),(2,'Diseñador/a'),(3,'Artista'),(4,'QA');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tienda`
--

DROP TABLE IF EXISTS `tienda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tienda` (
  `id_tienda` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(120) NOT NULL,
  `tipo` enum('Digital','Física','Mixta') NOT NULL,
  `pais` varchar(80) DEFAULT NULL,
  `ciudad` varchar(80) DEFAULT NULL,
  `web` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_tienda`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tienda`
--

LOCK TABLES `tienda` WRITE;
/*!40000 ALTER TABLE `tienda` DISABLE KEYS */;
INSERT INTO `tienda` VALUES (1,'Steam','Digital','USA',NULL,'https://store.steampowered.com'),(2,'GOG','Digital','Polonia',NULL,'https://www.gog.com'),(3,'PlayStation Store','Digital','USA',NULL,'https://store.playstation.com'),(4,'Nintendo eShop','Digital','Japón',NULL,'https://www.nintendo.com'),(5,'Game (España)','Física','España','Madrid','https://www.game.es');
/*!40000 ALTER TABLE `tienda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videojuego`
--

DROP TABLE IF EXISTS `videojuego`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `videojuego` (
  `id_videojuego` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(160) NOT NULL,
  `fecha_lanzamiento` date DEFAULT NULL,
  `pegi` tinyint DEFAULT NULL,
  `precio_base` decimal(8,2) DEFAULT NULL,
  `motor` varchar(80) DEFAULT NULL,
  `es_multijugador` tinyint(1) NOT NULL DEFAULT '0',
  `id_estudio` int DEFAULT NULL,
  `id_juego_padre` int DEFAULT NULL,
  `descripcion` text,
  PRIMARY KEY (`id_videojuego`),
  KEY `fk_juego_estudio` (`id_estudio`),
  KEY `fk_juego_padre` (`id_juego_padre`),
  CONSTRAINT `fk_juego_estudio` FOREIGN KEY (`id_estudio`) REFERENCES `estudio` (`id_estudio`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_juego_padre` FOREIGN KEY (`id_juego_padre`) REFERENCES `videojuego` (`id_videojuego`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videojuego`
--

LOCK TABLES `videojuego` WRITE;
/*!40000 ALTER TABLE `videojuego` DISABLE KEYS */;
INSERT INTO `videojuego` VALUES (1,'Baldur\'s Gate 3','2023-08-03',18,59.99,'Divinity Engine',1,1,NULL,'RPG basado en D&D.'),(2,'Elden Ring','2022-02-25',16,59.99,'FromEngine',1,2,NULL,'Acción RPG en mundo abierto.'),(3,'Cyberpunk 2077','2020-12-10',18,59.99,'REDengine 4',0,3,NULL,'RPG de ciencia ficción.'),(4,'Hades','2020-09-17',12,24.99,'Unreal Engine',0,4,NULL,'Roguelike de acción.'),(5,'Minecraft','2011-11-18',7,26.95,'Java',1,5,NULL,'Sandbox de construcción.'),(6,'Hades II',NULL,12,NULL,NULL,0,4,4,'Secuela (para self-join, sin fecha).'),(7,'Proyecto Misterioso',NULL,NULL,NULL,NULL,0,NULL,NULL,'Juego sin estudio ni fecha.'),(8,'Metroid Dread','2021-10-08',12,59.99,'Mercury Engine',0,6,NULL,'Aventura de acción con exploración y progresión.');
/*!40000 ALTER TABLE `videojuego` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videojuego_genero`
--

DROP TABLE IF EXISTS `videojuego_genero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `videojuego_genero` (
  `id_videojuego` int NOT NULL,
  `id_genero` int NOT NULL,
  PRIMARY KEY (`id_videojuego`,`id_genero`),
  KEY `fk_vg_genero` (`id_genero`),
  CONSTRAINT `fk_vg_genero` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_vg_juego` FOREIGN KEY (`id_videojuego`) REFERENCES `videojuego` (`id_videojuego`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videojuego_genero`
--

LOCK TABLES `videojuego_genero` WRITE;
/*!40000 ALTER TABLE `videojuego_genero` DISABLE KEYS */;
INSERT INTO `videojuego_genero` VALUES (1,1),(2,1),(3,1),(2,2),(4,2),(6,2),(8,2),(1,3),(3,3),(8,3),(4,4),(6,4),(5,5),(4,6),(7,6);
/*!40000 ALTER TABLE `videojuego_genero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videojuego_plataforma`
--

DROP TABLE IF EXISTS `videojuego_plataforma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `videojuego_plataforma` (
  `id_videojuego` int NOT NULL,
  `id_plataforma` int NOT NULL,
  `fecha_lanzamiento` date DEFAULT NULL,
  `resolucion_objetivo` varchar(40) DEFAULT NULL,
  `fps_objetivo` int DEFAULT NULL,
  PRIMARY KEY (`id_videojuego`,`id_plataforma`),
  KEY `fk_vp_plataforma` (`id_plataforma`),
  CONSTRAINT `fk_vp_juego` FOREIGN KEY (`id_videojuego`) REFERENCES `videojuego` (`id_videojuego`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_vp_plataforma` FOREIGN KEY (`id_plataforma`) REFERENCES `plataforma` (`id_plataforma`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videojuego_plataforma`
--

LOCK TABLES `videojuego_plataforma` WRITE;
/*!40000 ALTER TABLE `videojuego_plataforma` DISABLE KEYS */;
INSERT INTO `videojuego_plataforma` VALUES (1,1,'2023-08-03','1440p',60),(1,2,'2023-09-06','4K',60),(2,1,'2022-02-25','4K',60),(2,2,'2022-02-25','4K',60),(2,3,'2022-02-25','4K',60),(3,1,'2020-12-10','4K',60),(3,2,'2020-12-10','4K',60),(3,3,'2020-12-10','4K',60),(4,1,'2020-09-17','1080p',60),(4,4,'2020-09-17','720p',60),(5,1,'2011-11-18',NULL,NULL),(5,4,'2017-05-12',NULL,NULL),(6,1,NULL,NULL,NULL),(7,1,NULL,NULL,NULL),(8,4,'2021-10-08','720p',60);
/*!40000 ALTER TABLE `videojuego_plataforma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_videojuegos_estudio`
--

DROP TABLE IF EXISTS `vw_videojuegos_estudio`;
/*!50001 DROP VIEW IF EXISTS `vw_videojuegos_estudio`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `vw_videojuegos_estudio` AS SELECT 
 1 AS `videojuego`,
 1 AS `estudio`,
 1 AS `pais_estudio`,
 1 AS `pegi`,
 1 AS `precio_base`,
 1 AS `precio_con_iva`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vw_videojuegos_estudio`
--

/*!50001 DROP VIEW IF EXISTS `vw_videojuegos_estudio`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_videojuegos_estudio` AS select `v`.`titulo` AS `videojuego`,`e`.`nombre` AS `estudio`,`e`.`pais` AS `pais_estudio`,`v`.`pegi` AS `pegi`,`v`.`precio_base` AS `precio_base`,(`v`.`precio_base` * 1.21) AS `precio_con_iva` from (`videojuego` `v` join `estudio` `e` on((`v`.`id_estudio` = `e`.`id_estudio`))) where (`v`.`pegi` is not null) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-06 10:18:11

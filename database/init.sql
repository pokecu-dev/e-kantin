-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: kantin
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `detail_menu_kantin`
--

DROP TABLE IF EXISTS `detail_menu_kantin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_menu_kantin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_kantin` int NOT NULL,
  `menu` varchar(100) NOT NULL,
  `harga` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_id_kantin` (`id_kantin`),
  CONSTRAINT `fk_id_kantin` FOREIGN KEY (`id_kantin`) REFERENCES `list_kantin` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_menu_kantin`
--

LOCK TABLES `detail_menu_kantin` WRITE;
/*!40000 ALTER TABLE `detail_menu_kantin` DISABLE KEYS */;
INSERT INTO `detail_menu_kantin` VALUES (1,1,'nasi goreng mawut',6000),(2,1,'bakso',8000),(3,2,'sate kambing',7000),(4,3,'ayam geprek',5000);
/*!40000 ALTER TABLE `detail_menu_kantin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `list_kantin`
--

DROP TABLE IF EXISTS `list_kantin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `list_kantin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_kantin` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_kantin`
--

LOCK TABLES `list_kantin` WRITE;
/*!40000 ALTER TABLE `list_kantin` DISABLE KEYS */;
INSERT INTO `list_kantin` VALUES (1,'kantin pak trisno'),(2,'kantin bu rully'),(3,'kantin pak fajar');
/*!40000 ALTER TABLE `list_kantin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `murid`
--

DROP TABLE IF EXISTS `murid`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `murid` (
  `ID_MURID` int NOT NULL AUTO_INCREMENT,
  `ID_USER` int NOT NULL,
  `NISN` varchar(10) NOT NULL,
  PRIMARY KEY (`ID_MURID`),
  KEY `FK_ID_USER` (`ID_USER`),
  CONSTRAINT `FK_ID_USER` FOREIGN KEY (`ID_USER`) REFERENCES `USER` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `murid`
--

LOCK TABLES `murid` WRITE;
/*!40000 ALTER TABLE `murid` DISABLE KEYS */;
/*!40000 ALTER TABLE `murid` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_kantin` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `tgl` date DEFAULT (curdate()),
  `waktu` time DEFAULT (curtime()),
  PRIMARY KEY (`id`),
  KEY `id_kantin_fk` (`id_kantin`),
  KEY `id_user_fk` (`id_user`),
  CONSTRAINT `id_kantin_fk` FOREIGN KEY (`id_kantin`) REFERENCES `list_kantin` (`id`),
  CONSTRAINT `id_user_fk` FOREIGN KEY (`id_user`) REFERENCES `USER` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USER`
--

DROP TABLE IF EXISTS `USER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `USER` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(100) DEFAULT NULL,
  `PASS` varchar(100) DEFAULT NULL,
  `NAMA_LENGKAP` varchar(150) DEFAULT NULL,
  `NO_TLP` varchar(18) DEFAULT NULL,
  `ROLE` enum('MURID','GURU','PENJUAL','ADMIN') NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `USERNAME` (`USERNAME`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USER`
--

LOCK TABLES `USER` WRITE;
/*!40000 ALTER TABLE `USER` DISABLE KEYS */;
INSERT INTO `USER` VALUES (1,'adin','SIGMA_COY','MUHAMMAD SAIFUDDIN','+62 81235807937','MURID'),(2,'penjual1','ADMIN!@#','penjual santoso eak','+62 123654789','PENJUAL'),(3,'mulyono','mobil esemka','bapak mulyono','+62 097384324736','MURID'),(4,'atemin_nyata','ini atemin ygy','pokok admin','+62 097384434736','ADMIN'),(5,'EBADRUS','GURU PPLG','PAK BADRUS','+62 097344434736','GURU');
/*!40000 ALTER TABLE `USER` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-04  4:42:32

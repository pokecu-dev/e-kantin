-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: kantin
-- ------------------------------------------------------
-- Server version	8.0.46

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
-- Table structure for table `detail_transaksi`
--

DROP TABLE IF EXISTS `detail_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detail_transaksi` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ID_TRANSAKSI` int NOT NULL,
  `ID_MENU` int NOT NULL,
  `NAMA_MENU` varchar(150) NOT NULL COMMENT 'Snapshot nama menu saat checkout',
  `HARGA` int NOT NULL COMMENT 'Snapshot harga saat checkout',
  `QTY` int NOT NULL DEFAULT '1',
  `SUBTOTAL` int NOT NULL COMMENT 'harga * qty',
  PRIMARY KEY (`ID`),
  KEY `idx_dt_transaksi` (`ID_TRANSAKSI`),
  KEY `fk_dt_menu` (`ID_MENU`),
  CONSTRAINT `fk_dt_menu` FOREIGN KEY (`ID_MENU`) REFERENCES `tb_menu` (`ID_MENU`),
  CONSTRAINT `fk_dt_transaksi` FOREIGN KEY (`ID_TRANSAKSI`) REFERENCES `transaksi` (`ID_TRANSAKSI`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi`
--

LOCK TABLES `detail_transaksi` WRITE;
/*!40000 ALTER TABLE `detail_transaksi` DISABLE KEYS */;
INSERT INTO `detail_transaksi` VALUES (1,1,3,'Risol Mayo',3000,5,15000),(2,2,1,'Nasi Ayam Bakar',8000,22,176000),(3,2,2,'Chocolatos Drink',5000,10,50000),(4,3,3,'Risol Mayo',3000,2,6000),(5,4,4,'Nasi Goreng',8000,2,16000);
/*!40000 ALTER TABLE `detail_transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keranjang`
--

DROP TABLE IF EXISTS `keranjang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keranjang` (
  `id_keranjang` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_menu` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_keranjang`),
  UNIQUE KEY `id_user` (`id_user`,`id_menu`),
  KEY `id_menu` (`id_menu`),
  CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`ID`),
  CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `tb_menu` (`ID_MENU`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keranjang`
--

LOCK TABLES `keranjang` WRITE;
/*!40000 ALTER TABLE `keranjang` DISABLE KEYS */;
INSERT INTO `keranjang` VALUES (35,11,8,1),(36,11,1,4);
/*!40000 ALTER TABLE `keranjang` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `list_kantin`
--

DROP TABLE IF EXISTS `list_kantin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `list_kantin` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAMA_KANTIN` varchar(20) NOT NULL,
  `FOTO_KANTIN` varchar(255) NOT NULL,
  `id_penjual` int DEFAULT NULL,
  `STATUS` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `fk_penjual` (`id_penjual`),
  CONSTRAINT `fk_penjual` FOREIGN KEY (`id_penjual`) REFERENCES `users` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_kantin`
--

LOCK TABLES `list_kantin` WRITE;
/*!40000 ALTER TABLE `list_kantin` DISABLE KEYS */;
INSERT INTO `list_kantin` VALUES (1,'kantin ceria','kantin1.jpeg',24,'0'),(2,'kantin bu rully','kantin2.jpg',15,'1'),(3,'kantin pak fajar','kantin3.jpg',NULL,'1');
/*!40000 ALTER TABLE `list_kantin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rating`
--

DROP TABLE IF EXISTS `rating`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rating` (
  `ID_RATING` int NOT NULL AUTO_INCREMENT,
  `ID_KANTIN` int DEFAULT NULL,
  `ID_MENU` int DEFAULT NULL,
  `ID_USER` int DEFAULT NULL,
  `RATING` decimal(2,1) NOT NULL,
  `DESK` text,
  PRIMARY KEY (`ID_RATING`),
  KEY `FK_ID_MENU` (`ID_MENU`),
  KEY `FK_ID_USER` (`ID_USER`),
  CONSTRAINT `FK_ID_MENU` FOREIGN KEY (`ID_MENU`) REFERENCES `tb_menu` (`ID_MENU`),
  CONSTRAINT `FK_ID_USER` FOREIGN KEY (`ID_USER`) REFERENCES `users` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rating`
--

LOCK TABLES `rating` WRITE;
/*!40000 ALTER TABLE `rating` DISABLE KEYS */;
INSERT INTO `rating` VALUES (1,1,1,1,4.0,'ga tau,mungkin enak:D'),(2,1,1,2,3.0,'asin:v'),(3,1,2,12,5.0,'ENAK GANN,cuman radak pait:v,TAPI OVERAL ENAK MANIS PAIT DIKIT\r\n');
/*!40000 ALTER TABLE `rating` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `tr_ins_rating` AFTER INSERT ON `rating` FOR EACH ROW BEGIN 
    UPDATE tb_menu 
    SET RATING = (SELECT IFNULL(AVG(RATING), 0) FROM rating WHERE ID_MENU = NEW.ID_MENU) 
    WHERE ID_MENU = NEW.ID_MENU; 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `tr_upd_rating` AFTER UPDATE ON `rating` FOR EACH ROW BEGIN 
    UPDATE tb_menu 
    SET RATING = (SELECT IFNULL(AVG(RATING), 0) FROM rating WHERE ID_MENU = NEW.ID_MENU) 
    WHERE ID_MENU = NEW.ID_MENU; 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_unicode_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `tr_del_rating` AFTER DELETE ON `rating` FOR EACH ROW BEGIN 
    UPDATE tb_menu 
    SET RATING = (SELECT IFNULL(AVG(RATING), 0) FROM rating WHERE ID_MENU = OLD.ID_MENU) 
    WHERE ID_MENU = OLD.ID_MENU; 
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `tb_menu`
--

DROP TABLE IF EXISTS `tb_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tb_menu` (
  `ID_MENU` int NOT NULL AUTO_INCREMENT,
  `ID_KANTIN` int DEFAULT NULL,
  `NAMA_MENU` varchar(100) NOT NULL,
  `HARGA` varchar(15) NOT NULL,
  `KATEGORI` enum('makanan','minuman','snack') NOT NULL,
  `STOK` int DEFAULT '0',
  `STATUS` enum('tersedia','habis','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'tersedia',
  `FOTO_MENU` varchar(255) DEFAULT NULL,
  `DESK` text,
  `RATING` decimal(2,1) DEFAULT NULL,
  PRIMARY KEY (`ID_MENU`),
  KEY `FK_ID_KANTIN` (`ID_KANTIN`),
  CONSTRAINT `FK_ID_KANTIN` FOREIGN KEY (`ID_KANTIN`) REFERENCES `list_kantin` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_menu`
--

LOCK TABLES `tb_menu` WRITE;
/*!40000 ALTER TABLE `tb_menu` DISABLE KEYS */;
INSERT INTO `tb_menu` VALUES (1,1,'Nasi Ayam Bakar','8000','makanan',0,'habis','ayambakar.jpg','Ayam bakar adalah hidangan khas Indonesia berupa daging ayam yang dimarinasi dengan rempah-rempah (seperti kunyit, bawang putih, kemiri), kemudian dipanggang di atas bara arang atau teflon hingga bumbu meresap ke dalam daging. Karakteristik utamanya adalah aroma asap (smokey) yang kuat, kulit yang kecoklatan, dan tekstur daging yang lembut.',3.5),(2,1,'Chocolatos Drink','5000','minuman',0,'habis','chocolatos.jpg','Chocolatos Drink adalah minuman cokelat bubuk instan yang diproduksi oleh Garudafood, yang menonjolkan cita rasa cokelat Italia yang intens, creamy, dan premium. Minuman ini populer karena rasanya yang gurih-manis, tekstur kental, dan kemudahan penyajian (bisa panas maupun dingin). ',5.0),(3,1,'Risol Mayo','3000','snack',13,'tersedia','risolmayo.jpg','Risol mayo adalah camilan gurih kekinian berupa kulit dadar tipis renyah berlapis tepung panir, diisi daging asap (smoked beef), telur rebus, keju, dan mayones yang melimpah. Teksturnya renyah di luar, namun lembut dan lumer (creamy) di dalam. Umumnya dijual sebagai snack, risol mayo sering dideskripsikan sebagai \"lumer\", \"gurih\", dan \"krispi\".',NULL),(4,2,'Nasi Goreng','8000','makanan',18,'tersedia','nasigoreng.jpg','Nasi goreng adalah hidangan khas Indonesia berupa nasi yang digoreng dalam minyak/margarin, dicampur bumbu kaya rempah (bawang, kecap manis, terasi). Makanan ini fleksibel dengan topping telur, ayam, atau seafood. Populer karena rasa gurih-manis yang khas, nikmat, dan sering disajikan hangat dengan kerupuk. \r\n',NULL),(5,2,'Drink Beng Beng','5000','minuman',10,'tersedia','dringbeng.jpg','Drink Beng Beng adalah minuman cokelat instan yang mengadaptasi rasa ikonik dari snack bar Beng Beng, memadukan cokelat, susu, krimer, dan ekstrak malt yang menghasilkan rasa creamy, manis, dan gurih. Minuman ini populer disajikan dalam keadaan dingin (es) maupun panas, serta sering kali diperkaya dengan tambahan topping seperti susu evaporasi untuk meningkatkan tekstur dan rasa. ',NULL),(6,2,'Risol Solo','2000','snack',20,'tersedia','risolo.jpg','Risol Solo (Sosis Solo) adalah jajanan tradisional khas Surakarta yang berupa dadar gulung tipis berbahan telur dan tepung, berisi suwiran daging ayam atau sapi berbumbu gurih. Berbeda dengan risoles biasa yang menggunakan tepung panir, Sosis Solo umumnya dicelupkan ke dalam kocokan telur sebelum digoreng hingga kuning keemasan, menghasilkan kulit yang lembut di dalam namun sedikit renyah di luar. ',NULL),(7,3,'Nasi Sate Ayam','8000','makanan',20,'tersedia','sateayam.jpg','Sate ayam adalah hidangan khas Indonesia berupa potongan daging ayam yang ditusuk, dipanggang di atas bara arang hingga matang sempurna, dan disajikan dengan bumbu kacang atau kecap yang gurih-manis. Deskripsi ini menekankan pada aroma smoky (asap), kelembutan daging, dan kekayaan rempah, sering dipasangkan dengan lontong/nasi.',NULL),(8,3,'Martabak Manis','5000','snack',20,'tersedia','martabakmanis.jpg','Martabak manis (dikenal juga sebagai terang bulan) adalah kue dadar tebal khas Indonesia yang dipanggang dengan adonan tepung terigu, telur, dan gula. Karakteristik utamanya adalah permukaan bergelembung (bersarang/ fluffy) dengan tekstur lembut, tebal, dan bagian bawah yang renyah setelah diolesi mentega.',NULL),(9,3,'Es Buah','5000','minuman',10,'tersedia','esbuah.jpg','Es buah adalah hidangan penutup atau minuman segar khas Indonesia yang terdiri dari campuran berbagai potongan buah-buahan segar, disajikan dengan kuah manis (sirup/susu), dan es serut atau es batu',NULL),(10,1,'Nasi Ayam Bakar','1000','makanan',10,'nonaktif','Screenshot 2026-01-28 205349.png','yam',NULL),(11,1,'Nasi Ayam Bakar','1000','makanan',10,'tersedia','Screenshot 2026-01-28 205349.png','yam',NULL),(12,1,'esteh','1000','makanan',10,'tersedia','Screenshot 2026-01-28 205349.png','yamm',NULL);
/*!40000 ALTER TABLE `tb_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi` (
  `ID_TRANSAKSI` int NOT NULL AUTO_INCREMENT,
  `KODE_PESANAN` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ID_KANTIN` int DEFAULT NULL,
  `ID_USER` int DEFAULT NULL,
  `TGL` date DEFAULT (curdate()),
  `WAKTU` time DEFAULT (curtime()),
  `TOTAL` int NOT NULL DEFAULT '0',
  `STATUS` enum('pending','dikonfirmasi','diproses','selesai','dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
  `CATATAN` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`ID_TRANSAKSI`),
  UNIQUE KEY `idx_kode_pesanan` (`KODE_PESANAN`),
  KEY `id_kantin_fk` (`ID_KANTIN`),
  KEY `id_user_fk` (`ID_USER`),
  CONSTRAINT `id_kantin_fk` FOREIGN KEY (`ID_KANTIN`) REFERENCES `list_kantin` (`ID`),
  CONSTRAINT `id_user_fk` FOREIGN KEY (`ID_USER`) REFERENCES `users` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES (1,'ORD-20260520-6953F',1,12,'2026-05-20','03:40:55',15000,'diproses',''),(2,'ORD-20260520-7602C',1,12,'2026-05-20','04:46:37',226000,'dikonfirmasi',''),(3,'ORD-20260520-9F36A',1,12,'2026-05-20','05:54:00',6000,'diproses','tanpa mayo'),(4,'ORD-20260520-5C39D',2,12,'2026-05-20','07:50:25',16000,'pending','');
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(100) DEFAULT NULL,
  `PASS` varchar(100) DEFAULT NULL,
  `NAMA_LENGKAP` varchar(150) DEFAULT NULL,
  `NO_TLP` varchar(18) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `ROLE` enum('PEMBELI','PENJUAL','ADMIN') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `FOTO_USERS` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'default.jpg',
  `STATUS` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `USERNAME` (`USERNAME`),
  UNIQUE KEY `NO_TLP` (`NO_TLP`),
  UNIQUE KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'adin','$2y$10$Sr8PkbInbzNv6zb27qWPmuHUPWkHDa.7mdNxiPeDLdDfVwV2MhPlu','MUHAMMAD SAIFUDDIN','+62 81235807937','adin@dnproject.my.id','PEMBELI','57717d6c54430bdbf77a884dcc109e93.png','1'),(2,'penjual1','ADMIN!@#','penjual santoso eak','+62 123654789','PENJUAL@gmail.com','PENJUAL',NULL,'1'),(3,'mulyono','mobil esemka','bapak mulyono','+62 097384324736','ratapansolo@solo.com','PEMBELI',NULL,'1'),(4,'atemin_nyata','ini atemin ygy','pokok admin','+62 097384434736','adminUntukNyata@gmail.com','ADMIN',NULL,'1'),(5,'EBADRUS','GURU PPLG','PAK BADRUS','+62 097344434736','guru@ebadrus.com','PEMBELI',NULL,'1'),(7,'tuwes','tuwes123','buwat tuwes','+60 1234567890','tuwes@tuwes.com','PEMBELI',NULL,'1'),(8,'bagus','bagus14','prasetiyo','+62 87521098','vinas@gmail.com','PEMBELI',NULL,'1'),(9,'murid','murid1','murid aseli','+62 765432189','murid@gmail.com','PEMBELI',NULL,'1'),(11,'murid1','$2y$10$6Z/AiyeIOEsVwEzERnX/SepRZ0XCD8TPQTy51pTCjHNLWgJeq/9UG','murid aselioi','+62 7654321892','murid1@gmail.com','PEMBELI',NULL,'1'),(12,'siswa','$2y$10$J6p0a3AulK3YR/GavIjrSOY3r0C6391uLO13wfsR9WMS4H6TIl18K','siswa','+62 7687678678','siswa@gmail.com','PEMBELI',NULL,'1'),(13,'admin','$2y$10$Jr4drTCswbJ6U3QtLGUY5.YbuH9.be2FEvWon.kQ307/8gx8rPlNu','admin nyata banget coy versi password hash','+62 56789765','atemin@gmail.com','ADMIN',NULL,'1'),(15,'penjual','$2y$10$WBeInwwZtc7Vv5WCUQPtauWHIl/knlhdD.AlbvA5ua/.TY4rF3k1W','penjual nyata banget tipe pass hash','+62 456789876','wpenjual@gmail.com','PENJUAL',NULL,'1'),(24,'penjual3','$2y$10$Ub2yzG9hWc8TvF3oqxWRsuqDdcKkZuMH5ClKjKf50gRnXriU1yyUi','jual minuma','+62 81235807939','penju1al@gmail.com','PENJUAL','user_24_1779414158.png','1'),(36,'admin baru','$2y$10$NgsjbotUK3aD6RHRhdGCgunZ3pe4ynIrjBncS2UQWQEnwzG3e3B3G','atemin','+62 111111111','adminaslkdfj@gmail.com','ADMIN',NULL,'1'),(37,'penjual5','$2y$10$LI9EfH.8URFpQhtUVMkbv.ptjM2n3cTfs17vBbU3UO8mmKQo0MD26','penjual baru','+62 2233342','penjuaddl@gmail.com','PENJUAL',NULL,'1'),(39,'mentri kehutanan','$2y$10$uHysCSMibUDaSJLTzF3eTOO3gYARqizA58h.xbF0Qbc2Dt9JJhyAm','hutan asri banget','+62 81235807931','hutanindonesia@gmail.com','PEMBELI',NULL,'1'),(40,'monyet','$2y$10$aICiso2vUAWNYNjt.kDmAeVjGeEatiN4gARzzIxQZoPoKAX5xyRna','monyet asli indonesia','+62 81235807933','monyet23@gmail.com','PEMBELI',NULL,'1'),(41,'lala','$2y$10$rYqnHUACmg9QuwRJBck0cegipaUru2mP5R7HKT7uMB.drNEKK0w3.','lalalalili','+62 81235807945','lala@gmail.com','PEMBELI','default.jpg','1');
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

-- Dump completed on 2026-05-25  1:53:47

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
  CONSTRAINT `fk_id_kantin` FOREIGN KEY (`id_kantin`) REFERENCES `list_kantin` (`ID`)
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
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `KELAS` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES (1,'X PPLG 1'),(2,'X PPLG 2'),(3,'XI PPLG 1'),(4,'XI PPLG 2'),(5,'X DKV 1'),(6,'X DKV 2'),(7,'XI DKV 1'),(8,'XI DKV 2'),(9,'X AKL 1'),(10,'X AKL 2'),(11,'X AKL 3'),(12,'X AKL 4'),(13,'XI AKL 1'),(14,'XI AKL 2'),(15,'XI AKL 3'),(16,'XI AKL 4'),(17,'X MPLB 1'),(18,'X MPLB 2'),(19,'X MPLB 3'),(20,'X MPLB 4'),(21,'XI MPLB 1'),(22,'XI MPLB 2'),(23,'XI MPLB 3'),(24,'XI MPLB 4'),(25,'X BD 1'),(26,'X BD 2'),(27,'X BD 3'),(28,'XI BD 1'),(29,'XI BD 2'),(30,'XI BD 3'),(31,'X TJKT 1'),(32,'X TJKT 2'),(33,'X TJKT 3'),(34,'XI TJKT 1'),(35,'XI TJKT 2'),(36,'XI TJKT 3'),(37,'X ANIMASI 1'),(38,'X ANIMASI 2'),(39,'XI ANIMASI 1'),(40,'XI ANIMASI 2'),(41,'X TKI 1'),(42,'X TKI 2'),(43,'XI TKI 1'),(44,'XI TKI 2'),(45,'X PSPT 1'),(46,'X PSPT 2'),(47,'XI PSPT 1'),(48,'XI PSPT 2'),(49,'X ULW 1'),(50,'X ULW 2'),(51,'XI ULW 1'),(52,'XI ULW 2'),(53,'XII PPLG 1'),(54,'XII PPLG 2'),(55,'XII DKV 1'),(56,'XII DKV 2'),(57,'XII AKL 1'),(58,'XII AKL 2'),(59,'XII AKL 3'),(60,'XII AKL 4'),(61,'XII MPLB 1'),(62,'XII MPLB 2'),(63,'XII MPLB 3'),(64,'XII MPLB 4'),(65,'XII BD 1'),(66,'XII BD 2'),(67,'XII BD 3'),(68,'XII TJKT 1'),(69,'XII TJKT 2'),(70,'XII TJKT 3'),(71,'XII ANIMASI 1'),(72,'XII ANIMASI 2'),(73,'XII TKI 1'),(74,'XII TKI 2'),(75,'XII PSPT 1'),(76,'XII PSPT 2'),(77,'XII ULW 1'),(78,'XII ULW 2');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keranjang`
--

LOCK TABLES `keranjang` WRITE;
/*!40000 ALTER TABLE `keranjang` DISABLE KEYS */;
INSERT INTO `keranjang` VALUES (35,11,8,1),(36,11,1,4),(42,12,1,6),(43,12,2,2);
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
INSERT INTO `list_kantin` VALUES (1,'kantin pak trisno','kantin1.jpg',24,'1'),(2,'kantin bu rully','kantin2.jpg',NULL,'1'),(3,'kantin pak fajar','kantin3.jpg',NULL,'1');
/*!40000 ALTER TABLE `list_kantin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MURID`
--

DROP TABLE IF EXISTS `MURID`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `MURID` (
  `ID_MURID` int NOT NULL AUTO_INCREMENT,
  `ID_USER` int NOT NULL,
  `NISN` varchar(10) NOT NULL,
  `ID_KELAS` int DEFAULT NULL,
  `TEMPAT_LAHIR` varchar(50) DEFAULT NULL,
  `TANGGAL_LAHIR` date DEFAULT NULL,
  `ALAMAT_RUMAH` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ID_MURID`),
  KEY `FK_ID_USER` (`ID_USER`),
  KEY `FK_ID_KELAS` (`ID_KELAS`),
  CONSTRAINT `FK_ID_KELAS` FOREIGN KEY (`ID_KELAS`) REFERENCES `kelas` (`ID`),
  CONSTRAINT `FK_ID_USER` FOREIGN KEY (`ID_USER`) REFERENCES `users` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MURID`
--

LOCK TABLES `MURID` WRITE;
/*!40000 ALTER TABLE `MURID` DISABLE KEYS */;
INSERT INTO `MURID` VALUES (2,1,'1234567890',2,'TULUNGAGUNG','2009-12-10','DESA PUCANGLABAN'),(3,7,'67853999',2,'the gunung','2002-03-12','the gunung'),(4,8,'12345678',1,'tulungagung','2026-04-09','bago'),(5,9,'12345678',1,'the gunung','2007-04-23','bumi'),(6,11,'12345623',2,'the gunung','2007-04-23','bumi'),(7,12,'4567876543',2,'iyh','2026-04-15','iyh');
/*!40000 ALTER TABLE `MURID` ENABLE KEYS */;
UNLOCK TABLES;

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
  `STATUS` enum('tersedia','habis') DEFAULT 'tersedia',
  `FOTO_MENU` varchar(255) DEFAULT NULL,
  `DESK` text,
  PRIMARY KEY (`ID_MENU`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_menu`
--

LOCK TABLES `tb_menu` WRITE;
/*!40000 ALTER TABLE `tb_menu` DISABLE KEYS */;
INSERT INTO `tb_menu` VALUES (1,1,'Nasi Ayam Bakar','8000','makanan',22,'tersedia','ayambakar.jpg','Ayam bakar adalah hidangan khas Indonesia berupa daging ayam yang dimarinasi dengan rempah-rempah (seperti kunyit, bawang putih, kemiri), kemudian dipanggang di atas bara arang atau teflon hingga bumbu meresap ke dalam daging. Karakteristik utamanya adalah aroma asap (smokey) yang kuat, kulit yang kecoklatan, dan tekstur daging yang lembut.'),(2,1,'Chocolatos Drink','5000','minuman',10,'tersedia','chocolatos.jpg','Chocolatos Drink adalah minuman cokelat bubuk instan yang diproduksi oleh Garudafood, yang menonjolkan cita rasa cokelat Italia yang intens, creamy, dan premium. Minuman ini populer karena rasanya yang gurih-manis, tekstur kental, dan kemudahan penyajian (bisa panas maupun dingin). '),(3,1,'Risol Mayo','3000','snack',20,'tersedia','risolmayo.jpg','Risol mayo adalah camilan gurih kekinian berupa kulit dadar tipis renyah berlapis tepung panir, diisi daging asap (smoked beef), telur rebus, keju, dan mayones yang melimpah. Teksturnya renyah di luar, namun lembut dan lumer (creamy) di dalam. Umumnya dijual sebagai snack, risol mayo sering dideskripsikan sebagai \"lumer\", \"gurih\", dan \"krispi\".'),(4,2,'Nasi Goreng','8000','makanan',20,'tersedia','nasigoreng.jpg','Nasi goreng adalah hidangan khas Indonesia berupa nasi yang digoreng dalam minyak/margarin, dicampur bumbu kaya rempah (bawang, kecap manis, terasi). Makanan ini fleksibel dengan topping telur, ayam, atau seafood. Populer karena rasa gurih-manis yang khas, nikmat, dan sering disajikan hangat dengan kerupuk. \r\n'),(5,2,'Drink Beng Beng','5000','minuman',10,'tersedia','dringbeng.jpg','Drink Beng Beng adalah minuman cokelat instan yang mengadaptasi rasa ikonik dari snack bar Beng Beng, memadukan cokelat, susu, krimer, dan ekstrak malt yang menghasilkan rasa creamy, manis, dan gurih. Minuman ini populer disajikan dalam keadaan dingin (es) maupun panas, serta sering kali diperkaya dengan tambahan topping seperti susu evaporasi untuk meningkatkan tekstur dan rasa. '),(6,2,'Risol Solo','2000','snack',20,'tersedia','risolo.jpg','Risol Solo (Sosis Solo) adalah jajanan tradisional khas Surakarta yang berupa dadar gulung tipis berbahan telur dan tepung, berisi suwiran daging ayam atau sapi berbumbu gurih. Berbeda dengan risoles biasa yang menggunakan tepung panir, Sosis Solo umumnya dicelupkan ke dalam kocokan telur sebelum digoreng hingga kuning keemasan, menghasilkan kulit yang lembut di dalam namun sedikit renyah di luar. '),(7,3,'Nasi Sate Ayam','8000','makanan',20,'tersedia','sateayam.jpg','Sate ayam adalah hidangan khas Indonesia berupa potongan daging ayam yang ditusuk, dipanggang di atas bara arang hingga matang sempurna, dan disajikan dengan bumbu kacang atau kecap yang gurih-manis. Deskripsi ini menekankan pada aroma smoky (asap), kelembutan daging, dan kekayaan rempah, sering dipasangkan dengan lontong/nasi.'),(8,3,'Martabak Manis','5000','snack',20,'tersedia','martabakmanis.jpg','Martabak manis (dikenal juga sebagai terang bulan) adalah kue dadar tebal khas Indonesia yang dipanggang dengan adonan tepung terigu, telur, dan gula. Karakteristik utamanya adalah permukaan bergelembung (bersarang/ fluffy) dengan tekstur lembut, tebal, dan bagian bawah yang renyah setelah diolesi mentega.'),(9,3,'Es Buah','5000','minuman',10,'tersedia','esbuah.jpg','Es buah adalah hidangan penutup atau minuman segar khas Indonesia yang terdiri dari campuran berbagai potongan buah-buahan segar, disajikan dengan kuah manis (sirup/susu), dan es serut atau es batu');
/*!40000 ALTER TABLE `tb_menu` ENABLE KEYS */;
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
  CONSTRAINT `id_kantin_fk` FOREIGN KEY (`id_kantin`) REFERENCES `list_kantin` (`ID`),
  CONSTRAINT `id_user_fk` FOREIGN KEY (`id_user`) REFERENCES `users` (`ID`)
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
  `FOTO_USERS` varchar(100) DEFAULT NULL,
  `STATUS` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `USERNAME` (`USERNAME`),
  UNIQUE KEY `NO_TLP` (`NO_TLP`),
  UNIQUE KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'adin','$2y$10$Sr8PkbInbzNv6zb27qWPmuHUPWkHDa.7mdNxiPeDLdDfVwV2MhPlu','MUHAMMAD SAIFUDDIN','+62 81235807937','adin@dnproject.my.id','PEMBELI','6bee6143c5f90bf6e241e21b79cc2feb.jpeg','1'),(2,'penjual1','ADMIN!@#','penjual santoso eak','+62 123654789','PENJUAL@gmail.com','PENJUAL',NULL,'1'),(3,'mulyono','mobil esemka','bapak mulyono','+62 097384324736','ratapansolo@solo.com','PEMBELI',NULL,'1'),(4,'atemin_nyata','ini atemin ygy','pokok admin','+62 097384434736','adminUntukNyata@gmail.com','ADMIN',NULL,'1'),(5,'EBADRUS','GURU PPLG','PAK BADRUS','+62 097344434736','guru@ebadrus.com','PEMBELI',NULL,'1'),(7,'tuwes','tuwes123','buwat tuwes','+60 1234567890','tuwes@tuwes.com','PEMBELI',NULL,'1'),(8,'bagus','bagus14','prasetiyo','+62 87521098','vinas@gmail.com','PEMBELI',NULL,'1'),(9,'murid','murid1','murid aseli','+62 765432189','murid@gmail.com','PEMBELI',NULL,'1'),(11,'murid1','$2y$10$6Z/AiyeIOEsVwEzERnX/SepRZ0XCD8TPQTy51pTCjHNLWgJeq/9UG','murid aselioi','+62 7654321892','murid1@gmail.com','PEMBELI',NULL,'1'),(12,'siswa','$2y$10$J6p0a3AulK3YR/GavIjrSOY3r0C6391uLO13wfsR9WMS4H6TIl18K','siswa','+62 7687678678','siswa@gmail.com','PEMBELI',NULL,'1'),(13,'admin','$2y$10$Jr4drTCswbJ6U3QtLGUY5.YbuH9.be2FEvWon.kQ307/8gx8rPlNu','admin nyata banget coy versi password hash','+62 56789765','atemin@gmail.com','ADMIN',NULL,'1'),(15,'penjual','$2y$10$WBeInwwZtc7Vv5WCUQPtauWHIl/knlhdD.AlbvA5ua/.TY4rF3k1W','penjual nyata banget tipe pass hash','+62 456789876','wpenjual@gmail.com','PENJUAL',NULL,'1'),(24,'penjual3','$2y$10$Ub2yzG9hWc8TvF3oqxWRsuqDdcKkZuMH5ClKjKf50gRnXriU1yyUi','jual makan','+62 81235807939','penju1al@gmail.com','PENJUAL',NULL,'1');
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

-- Dump completed on 2026-05-19  4:17:27

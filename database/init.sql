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
-- Table structure for table `list_kantin`
--

DROP TABLE IF EXISTS `list_kantin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `list_kantin` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NAMA_KANTIN` varchar(20) NOT NULL,
  `FOTO_KANTIN` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_kantin`
--

LOCK TABLES `list_kantin` WRITE;
/*!40000 ALTER TABLE `list_kantin` DISABLE KEYS */;
INSERT INTO `list_kantin` VALUES (1,'kantin pak trisno','kantin1.jpg'),(2,'kantin bu rully','kantin2.jpg'),(3,'kantin pak fajar','kantin3.jpg');
/*!40000 ALTER TABLE `list_kantin` ENABLE KEYS */;
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
INSERT INTO `tb_menu` VALUES (1,1,'Nasi Ayam Bakar','8000','makanan',20,'tersedia','ayambakar.jpg','Ayam bakar adalah hidangan khas Indonesia berupa daging ayam yang dimarinasi dengan rempah-rempah (seperti kunyit, bawang putih, kemiri), kemudian dipanggang di atas bara arang atau teflon hingga bumbu meresap ke dalam daging. Karakteristik utamanya adalah aroma asap (smokey) yang kuat, kulit yang kecoklatan, dan tekstur daging yang lembut.'),(2,1,'Chocolatos Drink','5000','minuman',10,'tersedia','chocolatos.jpg','Chocolatos Drink adalah minuman cokelat bubuk instan yang diproduksi oleh Garudafood, yang menonjolkan cita rasa cokelat Italia yang intens, creamy, dan premium. Minuman ini populer karena rasanya yang gurih-manis, tekstur kental, dan kemudahan penyajian (bisa panas maupun dingin). '),(3,1,'Risol Mayo','3000','snack',20,'tersedia','risolmayo.jpg','Risol mayo adalah camilan gurih kekinian berupa kulit dadar tipis renyah berlapis tepung panir, diisi daging asap (smoked beef), telur rebus, keju, dan mayones yang melimpah. Teksturnya renyah di luar, namun lembut dan lumer (creamy) di dalam. Umumnya dijual sebagai snack, risol mayo sering dideskripsikan sebagai \"lumer\", \"gurih\", dan \"krispi\".'),(4,2,'Nasi Goreng','8000','makanan',20,'tersedia','nasigoreng.jpg','Nasi goreng adalah hidangan khas Indonesia berupa nasi yang digoreng dalam minyak/margarin, dicampur bumbu kaya rempah (bawang, kecap manis, terasi). Makanan ini fleksibel dengan topping telur, ayam, atau seafood. Populer karena rasa gurih-manis yang khas, nikmat, dan sering disajikan hangat dengan kerupuk. \r\n'),(5,2,'Drink Beng Beng','5000','minuman',10,'tersedia','dringbeng.jpg','Drink Beng Beng adalah minuman cokelat instan yang mengadaptasi rasa ikonik dari snack bar Beng Beng, memadukan cokelat, susu, krimer, dan ekstrak malt yang menghasilkan rasa creamy, manis, dan gurih. Minuman ini populer disajikan dalam keadaan dingin (es) maupun panas, serta sering kali diperkaya dengan tambahan topping seperti susu evaporasi untuk meningkatkan tekstur dan rasa. '),(6,2,'Risol Solo','2000','snack',20,'tersedia','risolo.jpg','Risol Solo (Sosis Solo) adalah jajanan tradisional khas Surakarta yang berupa dadar gulung tipis berbahan telur dan tepung, berisi suwiran daging ayam atau sapi berbumbu gurih. Berbeda dengan risoles biasa yang menggunakan tepung panir, Sosis Solo umumnya dicelupkan ke dalam kocokan telur sebelum digoreng hingga kuning keemasan, menghasilkan kulit yang lembut di dalam namun sedikit renyah di luar. '),(7,3,'Nasi Sate Ayam','8000','makanan',20,'tersedia','sateayam.jpg','Sate ayam adalah hidangan khas Indonesia berupa potongan daging ayam yang ditusuk, dipanggang di atas bara arang hingga matang sempurna, dan disajikan dengan bumbu kacang atau kecap yang gurih-manis. Deskripsi ini menekankan pada aroma smoky (asap), kelembutan daging, dan kekayaan rempah, sering dipasangkan dengan lontong/nasi.'),(8,3,'Martabak Manis','5000','snack',20,'tersedia','martabakmanis.jpg','Martabak manis (dikenal juga sebagai terang bulan) adalah kue dadar tebal khas Indonesia yang dipanggang dengan adonan tepung terigu, telur, dan gula. Karakteristik utamanya adalah permukaan bergelembung (bersarang/ fluffy) dengan tekstur lembut, tebal, dan bagian bawah yang renyah setelah diolesi mentega.'),(9,3,'Es Buah','5000','minuman',10,'tersedia','esbuah.jpg','Es buah adalah hidangan penutup atau minuman segar khas Indonesia yang terdiri dari campuran berbagai potongan buah-buahan segar, disajikan dengan kuah manis (sirup/susu), dan es serut atau es batu');
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
  PRIMARY KEY (`ID`),
  UNIQUE KEY `USERNAME` (`USERNAME`),
  UNIQUE KEY `NO_TLP` (`NO_TLP`),
  UNIQUE KEY `EMAIL` (`EMAIL`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'adin','SIGMA_COY','MUHAMMAD SAIFUDDIN','+62 81235807937','adin@dnproject.my.id','PEMBELI','6bee6143c5f90bf6e241e21b79cc2feb.jpeg'),(2,'penjual1','ADMIN!@#','penjual santoso eak','+62 123654789','PENJUAL@gmail.com','PENJUAL',NULL),(3,'mulyono','mobil esemka','bapak mulyono','+62 097384324736','ratapansolo@solo.com','PEMBELI',NULL),(4,'atemin_nyata','ini atemin ygy','pokok admin','+62 097384434736','adminUntukNyata@gmail.com','ADMIN',NULL),(5,'EBADRUS','GURU PPLG','PAK BADRUS','+62 097344434736','guru@ebadrus.com','PEMBELI',NULL),(6,'tes',NULL,NULL,NULL,NULL,'PEMBELI',NULL),(7,'tuwes','tuwes123','buwat tuwes','+60 1234567890','tuwes@tuwes.com','PEMBELI',NULL),(8,'bagus','bagus14','prasetiyo','+62 87521098','vinas@gmail.com','PEMBELI',NULL),(9,'murid','murid1','murid aseli','+62 765432189','murid@gmail.com','PEMBELI',NULL),(11,'murid1','$2y$10$6Z/AiyeIOEsVwEzERnX/SepRZ0XCD8TPQTy51pTCjHNLWgJeq/9UG','murid aselioi','+62 7654321892','murid1@gmail.com','PEMBELI',NULL),(12,'siswa','$2y$10$J6p0a3AulK3YR/GavIjrSOY3r0C6391uLO13wfsR9WMS4H6TIl18K','siswa','+62 7687678678','siswa@gmail.com','PEMBELI',NULL),(13,'admin','$2y$10$Jr4drTCswbJ6U3QtLGUY5.YbuH9.be2FEvWon.kQ307/8gx8rPlNu','admin nyata banget coy versi password hash','+62 56789765','atemin@gmail.com','ADMIN',NULL),(15,'penjual','$2y$10$o6bPhZvsmtDh6Jo.QcrTOOLBKEoqWvKCHDLd77.XxWtvVvo2RJYEC','penjual nyata banget tipe pass hash','+62 456789876','wpenjual@gmail.com','PENJUAL',NULL);
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

-- Dump completed on 2026-05-12 12:48:14

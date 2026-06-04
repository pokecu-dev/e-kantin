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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_transaksi`
--

LOCK TABLES `detail_transaksi` WRITE;
/*!40000 ALTER TABLE `detail_transaksi` DISABLE KEYS */;
INSERT INTO `detail_transaksi` VALUES (1,1,3,'Risol Mayo',3000,5,15000),(2,2,1,'Nasi Ayam Bakar',8000,22,176000),(3,2,2,'Chocolatos Drink',5000,10,50000),(4,3,3,'Risol Mayo',3000,2,6000),(5,4,4,'Nasi Goreng',8000,2,16000),(6,5,4,'Nasi Ayam Goreng',8000,1,8000),(7,6,1,'Tahu Kres',5000,1,5000),(8,6,2,'Jamur Crispy',5000,1,5000),(9,7,1,'Tahu Kres',5000,1,5000),(10,8,4,'Nasi Ayam Goreng',8000,1,8000),(11,9,4,'Nasi Ayam Goreng',8000,1,8000),(12,10,5,'Nasi Lele Goreng',8000,1,8000),(13,11,8,'Nasi Ayam Geprek',8000,1,8000),(14,12,4,'Nasi Ayam Goreng',8000,2,16000),(15,13,1,'Tahu Kres',5000,4,20000),(16,14,4,'Nasi Ayam Goreng',8000,9,72000),(17,14,5,'Nasi Lele Goreng',8000,1,8000),(18,14,6,'Indomie Goreng',3000,6,18000),(19,15,9,'Es Jeruk',3000,1,3000),(20,16,5,'Nasi Lele Goreng',8000,1,8000),(21,17,6,'Indomie Goreng',3000,2,6000),(22,18,1,'Tahu Kres',5000,4,20000);
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
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keranjang`
--

LOCK TABLES `keranjang` WRITE;
/*!40000 ALTER TABLE `keranjang` DISABLE KEYS */;
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
  `QRIS` varchar(250) DEFAULT NULL,
  `id_penjual` int DEFAULT NULL,
  `STATUS` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `fk_penjual` (`id_penjual`),
  CONSTRAINT `fk_penjual` FOREIGN KEY (`id_penjual`) REFERENCES `users` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `list_kantin`
--

LOCK TABLES `list_kantin` WRITE;
/*!40000 ALTER TABLE `list_kantin` DISABLE KEYS */;
INSERT INTO `list_kantin` VALUES (1,'Kantin Bu Dian','kantin1.jpeg',NULL,24,'1'),(2,'Kantin Pak Sahudi','kantin2.png','9d6b818f3bea269bd251fe9a1732e5e7.jpeg',15,'1'),(3,'Kantin Bu Kom','kantin3.png',NULL,37,'1'),(10,'Kantin Pak Agus','kantin4.png',NULL,42,'1'),(12,'Kantin Bu Tika','kantin5.png',NULL,43,'1'),(14,'Kantin Pak Sukamto','kaantin7.png',NULL,45,'1'),(15,'Kantin Mardika','KANTIN8.png',NULL,46,'1'),(16,'Kantin Pak Basuni','kantin9.png',NULL,47,'1'),(17,'Kantin Pak Fajar','kantin10.png',NULL,48,'1'),(18,'Kantin Pak Angga','kantin6.png',NULL,44,'1');
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rating`
--

LOCK TABLES `rating` WRITE;
/*!40000 ALTER TABLE `rating` DISABLE KEYS */;
INSERT INTO `rating` VALUES (1,1,1,1,4.0,'ga tau,mungkin enak:D'),(3,1,2,12,5.0,'ENAK GANN,cuman radak pait:v,TAPI OVERAL ENAK MANIS PAIT DIKIT\r\n'),(4,2,5,11,5.0,'MANTAPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPPP'),(5,2,4,11,1.0,'minta g pedes, malah di kasi cabe 1000. humph!'),(6,2,4,12,5.0,'enakk\r\n'),(7,2,6,12,5.0,'enak nyemek mie nya\r\n');
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
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tb_menu`
--

LOCK TABLES `tb_menu` WRITE;
/*!40000 ALTER TABLE `tb_menu` DISABLE KEYS */;
INSERT INTO `tb_menu` VALUES (1,1,'Tahu Kres','5000','snack',40,'tersedia','tahukres.jpg','Tahu kres (atau tahu krispi) adalah camilan populer yang terbuat dari potongan tahu (biasanya tahu putih atau tahu Sumedang) yang dibalur adonan tepung berbumbu, lalu digoreng hingga menghasilkan tekstur ekstra renyah di luar dan lembut di dalam. ',4.0),(2,1,'Jamur Crispy','5000','snack',49,'tersedia','jamur.jpg','Jamur crispy adalah camilan renyah berbahan dasar jamur tiram yang disuwir, dibalur adonan tepung berbumbu, dan digoreng garing. Camilan gurih ini populer karena teksturnya yang krispi, kaya protein, dan biasanya disajikan dengan berbagai pilihan bumbu tabur atau saus sambal',5.0),(3,1,'Usus Crunchy','5000','snack',13,'tersedia','usus.jpg','Usus crunchy (atau usus crispy) adalah olahan usus ayam yang dibersihkan, dibumbui, dibalur tepung, dan digoreng kering hingga teksturnya sangat renyah. Camilan sekaligus lauk favorit ini bercita rasa gurih, asin, dan sering diberi taburan bumbu seperti daun jeruk atau bubuk cabai. \r\n',NULL),(4,2,'Nasi Ayam Goreng','8000','makanan',9,'tersedia','ayamgor.jpg','Nasi ayam goreng adalah hidangan lengkap berupa nasi yang dimasak bersama bumbu halus (bawang, cabai, kecap), disajikan dengan lauk potongan ayam goreng gurih.',3.0),(5,2,'Nasi Lele Goreng','8000','makanan',7,'tersedia','lele.jpg','Nasi lele goreng adalah hidangan praktis berisi nasi putih hangat yang dipadukan dengan ikan lele bertekstur garing di luar dan lembut di dalam. Hidangan ini biasanya disajikan bersama tahu, tempe, lalapan segar (mentimun, kemangi), dan sambal khas. ',5.0),(6,2,'Indomie Goreng','3000','makanan',92,'tersedia','indoreng.jpg','Indomie Goreng adalah produk mi instan kering terkemuka dari Indomie, yang diproduksi oleh Indofood. Terkenal dengan perpaduan rasa gurih dan manis, produk ini mendunia karena tekstur mienya yang kenyal dan racikan bumbu khasnya',5.0),(7,3,'Nasi Soto Ayam','7000','makanan',20,'tersedia','sotoayam.jpg','Soto ayam adalah makanan tradisional khas Indonesia berupa sup ayam berkuah kaldu gurih dengan suwiran daging ayam. Kuahnya yang berwarna kekuningan berasal dari racikan bumbu rempah seperti kunyit, serai, dan daun jeruk. Hidangan ini biasa disajikan dengan pelengkap seperti soun, tauge, kol, dan telur rebus.',NULL),(8,3,'Nasi Ayam Geprek','8000','makanan',19,'tersedia','Ayam geprek.jpg','Ayam geprek adalah hidangan ayam goreng tepung krispi khas Indonesia yang disajikan dengan cara dilumatkan atau diulek bersama sambal bawang yang pedas. Berasal dari Yogyakarta, hidangan ini biasanya disajikan panas langsung dengan nasi putih dan lalapan. ',NULL),(9,3,'Es Jeruk','3000','minuman',9,'tersedia','esjeruk.jpg','Es jeruk adalah minuman segar khas Indonesia yang terbuat dari perasan buah jeruk asli yang dicampur dengan air, gula cair, dan es batu. Minuman ini sangat populer sebagai pelepas dahaga, terutama saat cuaca panas.',NULL),(13,10,'Nasi Pecel','6000','makanan',40,'tersedia','pecel.jpg','Nasi pecel adalah hidangan tradisional khas Jawa yang terdiri dari nasi putih hangat, disiram dengan sambal kacang, dan dilengkapi dengan aneka sayuran rebus. Makanan yang sangat populer ini sering disajikan di atas pincuk (daun pisang yang dilipat) dan menjadi menu sarapan favorit masyarakat.',NULL),(14,10,'Nasi Ayam Geprek','8000','makanan',30,'tersedia','ayprek.jpg','Nasi ayam geprek adalah hidangan populer Indonesia yang terdiri dari nasi putih hangat, ayam goreng berbalut tepung krispi (fried chicken), dan sambal pedas yang diulek langsung bersama ayamnya hingga dagingnya sedikit hancur. ',NULL),(17,10,'Nasi Soto','7000','makanan',80,'tersedia','nasto.jpg','Nasi soto ayam adalah hidangan sup tradisional Indonesia yang menyajikan nasi putih hangat dengan siraman kuah kaldu ayam kaya rempah yang khas. Kuah ini berwarna kekuningan berkat penggunaan kunyit, dan dipadukan dengan suwiran daging ayam, soun (bihun), tauge, serta taburan koya, bawang goreng, dan seledri.',NULL),(18,10,'Es Teh','3000','minuman',70,'tersedia','iceti.jpg','Es teh adalah minuman penyegar yang terbuat dari seduhan daun teh (Camellia sinensis) yang disajikan dingin bersama es batu. Umumnya, minuman ini dikreasikan dengan tambahan gula (es teh manis) atau dibiarkan murni tanpa pemanis (es teh tawar).',NULL),(22,14,'Nasi Ayam Geprek','8000','makanan',505,'tersedia','yamprek.jpg','Nasi ayam geprek adalah hidangan populer Indonesia yang terdiri dari nasi putih hangat, ayam goreng tepung krispi, dan sambal. Keunikan makanan ini terletak pada proses \"geprek\" (dipukul atau ditekan menggunakan ulekan), sehingga daging ayam sedikit hancur dan menyatu dengan lumuran sambal pedas. ',NULL),(23,14,'Kopi Susu','4000','minuman',101,'tersedia','sukop.jpg','Kopi susu adalah minuman perpaduan antara seduhan kopi dan susu yang menghasilkan cita rasa lebih lembut dan creamy. Minuman ini hadir dalam berbagai variasi global dan lokal???mulai dari hidangan tradisional kopi tubruk dengan susu kental manis, sajian berbasis espresso ala Italia (seperti caff?? latte dan cappuccino), hingga es kopi susu kekinian dengan gula aren. ',NULL),(24,14,'Mie Sedaap Laksa','5000','makanan',2090,'tersedia','selaksa.jpg','Mie Sedaap Laksa, yang lebih dikenal dengan varian Singapore Spicy Laksa, adalah produk mi instan kuah dari Wings Food yang menghadirkan sensasi cita rasa hidangan laksa khas Singapura.',NULL),(25,14,'Teh Panas','3000','minuman',108,'tersedia','MUST TRY_ This Golden Tea Glow Will Change Your Morning Routine!.jpg','Teh panas adalah minuman sederhana namun kaya makna, yang menyajikan kombinasi menenangkan antara aroma, kehangatan, dan rasa.',NULL),(26,12,'Ceker Lava Tanpa Tulang','8000','snack',100,'tersedia','dakbal.jpg','Ceker Lava Tanpa Tulang adalah inovasi kuliner modern yang menggabungkan sensasi rasa super pedas dengan kemudahan menyantap makanan. Hidangan ini sangat populer di kalangan pencinta kuliner pedas karena menyajikan tekstur yang unik tanpa repot memisahkan tulang.',NULL),(27,12,'Mie Kuah Telur','6000','makanan',510,'tersedia','307370743342071079.jpg','Mie kuah telur adalah salah satu hidangan comfort food paling populer yang menyajikan perpaduan sempurna antara kesederhanaan, kehangatan, dan rasa yang memanjakan lidah. Baik menggunakan mie instan maupun mie basah racikan sendiri, hidangan ini selalu berhasil menjadi penyelamat di kala lapar, terutama saat cuaca dingin atau hujan.',NULL),(28,12,'Mie Goreng Telur','6000','makanan',9090,'tersedia','341147740543922031.jpg','Mie goreng telur adalah hidangan klasik yang merakyat, praktis, dan selalu berhasil memanjakan lidah. Kombinasi antara mie yang kenyal, gurih-manisnya bumbu tumis, dan gurihnya telur membuat menu ini menjadi pilihan favorit untuk sarapan, makan malam, ataupun camilan larut malam.',NULL),(29,10,'Sate Jeroan','2000','makanan',10,'tersedia','Sate jeroan ayam.jpg','Sate jeroan adalah hidangan khas Indonesia yang menawarkan petualangan rasa dan tekstur yang unik bagi para pencinta kuliner tradisional.',NULL),(30,10,'Sate Ayam','2000','makanan',90,'tersedia','sateayam.jpg','Sate ayam adalah salah satu mahakarya kuliner Indonesia yang paling ikonik dan dicintai oleh semua kalangan. Hidangan ini menyajikan potongan daging ayam pilihan yang ditusuk rapi, dibakar di atas bara api, lalu disajikan dengan siraman saus yang kaya rasa.',NULL),(31,1,'Dimsum Mentai','2500','snack',100,'tersedia','Dimsum Mentai Isi 4 Pcs dari La-Riez Dimsum.jpg','Dimsum adalah hidangan tradisional Tiongkok berupa camilan berukuran kecil yang disajikan dalam keranjang kukus bambu atau digoreng.',NULL),(32,1,'Tahu Bakso','2000','snack',100,'tersedia','7 Resep Tahu Bakso yang Gurih dan Kenyal.jpg','Tahu bakso adalah kudapan tradisional khas Jawa Tengah (khususnya Ungaran/Semarang) yang memadukan tahu goreng atau tahu pong dengan isian adonan daging (sapi atau ayam) yang kenyal.',NULL),(33,1,'Doanat Gula Pasir ','2000','snack',90,'tersedia','38069559344107062.jpg','Donat gula pasir adalah penganan manis berbentuk cincin (atau bola) yang digoreng, terbuat dari adonan tepung terigu, telur, ragi, dan mentega. Bagian luarnya yang renyah dilapisi atau dilumuri dengan taburan gula pasir (atau gula halus) yang memberikan sensasi manis legit saat digigit',NULL),(34,1,'Donat Meses ','2000','snack',90,'tersedia','a693e6009a62b6484f1a81f81d2701e4.jpg','Donat meses coklat adalah camilan klasik berbentuk cincang dengan lubang di tengah. Donat ini memiliki tekstur yang empuk di dalam dan permukaannya dilapisi olesan glaze atau mentega manis, lalu ditaburi butiran cokelat manis (meses). Perpaduannya memberikan sensasi rasa yang manis, gurih, dan renyah.',NULL),(35,1,'Doanat Gula Pasir ','2000','snack',90,'nonaktif','38069559344107062.jpg','Donat gula pasir adalah penganan manis berbentuk cincin (atau bola) yang digoreng, terbuat dari adonan tepung terigu, telur, ragi, dan mentega. Bagian luarnya yang renyah dilapisi atau dilumuri dengan taburan gula pasir (atau gula halus) yang memberikan sensasi manis legit saat digigit',NULL),(36,2,'Pop Mie Lapeer Time Rasa Ayam Bawang ','5000','makanan',100,'tersedia','popyambawang.jpg','Pop Mie Lapeer Time Rasa Ayam Bawang adalah varian mi instan cup kuah dari Pop Mie dengan kemasan paper cup yang lebih praktis untuk mengganjal perut lapar. Menawarkan perpaduan mie yang lebih kenyal, chunky ball yang lebih jumbo, dan kuah kaldu rasa ayam bawang yang gurih serta beraroma khas.',NULL),(37,2,'Pop Mie Lapeer Time Rasa Soto Ayam','5000','makanan',10,'tersedia','popsoyam.jpg','Pop Mie Lapeer Time Rasa Soto Ayam adalah mi instan cup kuah dengan tekstur mi yang lebih kenyal dan isian lebih jumbo. Memiliki cita rasa kaldu soto gurih, segar, dan kaya rempah khas Indonesia, lengkap dengan bubuk koya dan sayuran kering.',NULL),(38,2,'Pop Mie Pedes Dower','7000','makanan',100,'tersedia','popdeswer.jpg','Pop Mie Pedes Dower adalah varian mi instan cup dengan sensasi kuah rasa ayam pedas yang kuat dan menantang. Dikenal dengan kuahnya yang merah, mi ini memiliki tekstur kenyal dan sangat cocok bagi pecinta kuliner pedas. Penyajiannya sangat praktis, cukup diseduh selama 3 menit.',NULL),(39,3,'Drink Beng Beng','4000','minuman',170,'tersedia','dringbeng.jpg','Drink Beng-Beng adalah minuman cokelat serbuk instan dari Mayora yang memadukan rasa cokelat, malt, dan susu. Dikemas praktis dalam bentuk sachet (biasanya 30 gram), minuman ini memiliki tekstur creamy dan legit, serta sangat fleksibel untuk disajikan hangat maupun dingin dengan es batu.',NULL),(40,3,'Cireng Isi Ayam Suir Pedas','2000','snack',50,'tersedia','CIRENG JUMBO 14 CM TERLARIS CIRENG ISI AYAM SUWIR PEDAS _ CIRENG KEJU _ CIRENG FROZEN _ CIRENG ENAK _ CIRENG WAROENGBEM _ CIRENG MEGALODON.jpg','Cireng ayam suwir pedas adalah camilan khas Sunda yang memadukan adonan aci (tepung tapioka) bertekstur renyah di luar dan kenyal di dalam, dengan isian tumisan daging ayam suwir berbumbu pedas gurih. Perpaduan rasa ini menciptakan sensasi kriuk, kenyal, dan pedas yang sangat menggugah selera.',NULL),(41,3,'Wedang Jahe','3000','minuman',10,'tersedia','Ginger Tea_???????? Feeling queasy_ Ginger tea is one???.jpg','Wedang jahe adalah minuman tradisional khas Jawa yang terbuat dari rebusan rimpang jahe dan gula merah atau gula batu. Minuman ini sering disajikan panas atau hangat untuk meredakan masuk angin, menghangatkan tubuh, dan meningkatkan sistem kekebalan tubuh.',NULL),(42,3,'Mie Sedaap Goreng','5000','makanan',98,'tersedia','segor.jpg','Mie Sedaap Goreng adalah mi instan tanpa kuah dari Wings Food yang terkenal dengan tekstur mi yang kenyal dan perpaduan bumbu gurih-manis yang kaya. Ciri khas utamanya adalah tambahan bawang goreng renyah dan taburan keremesan kriuk yang memberikan sensasi makan lebih nikmat.',NULL),(43,3,'Mie Sedaap Rawit Bingit','5000','makanan',100,'tersedia','serawit.jpg','Mie Sedaap Rawit Bingit adalah inovasi mi instan kuah dari Wings Food yang menggunakan cabai rawit asli untuk memberikan sensasi pedas yang nendang. Hadir dalam kemasan praktis, varian ini menyajikan perpaduan kaldu gurih yang nikmat dengan pedas segar khas cabe rawit',NULL),(44,2,'Bakso','6000','makanan',100,'tersedia','480970435223143089.jpg','Bakso adalah hidangan khas Indonesia berupa bola daging yang dicampur tepung tapioka dan bumbu rempah. Biasanya disajikan dengan kuah kaldu gurih, mi, bihun, tahu, dan sayuran, kuliner ini terkenal dengan teksturnya yang kenyal dan lezat.',NULL),(45,10,'Chocolatos','4000','minuman',19,'tersedia','chocolatos.jpg','Chocolatos Drink adalah minuman serbuk instan rasa cokelat khas Italia dari PT Garudafood. Minuman ini populer karena rasa cokelatnya yang pekat, manis yang pas, dan tidak membuat enek. Tersedia dalam bentuk sachet (renteng) maupun botol siap minum.',NULL),(46,12,'Nasi Pecel','7000','makanan',10,'tersedia','Video cara membuat Nasi Pecel.jpg','Nasi pecel adalah hidangan tradisional khas Jawa berupa nasi putih yang disajikan dengan aneka sayuran rebus, disiram sambal kacang, dan dilengkapi dengan lauk pendamping. Perpaduan sayuran segar dan bumbu kacang yang gurih, manis, dan sedikit pedas menjadikannya menu sarapan yang sangat populer.??',NULL),(47,12,'Es CLBK','6000','minuman',80,'tersedia','matcha bubble tea_.jpg','Matcha Susu bercampur boba boba yang sangat cocok nemenin kamu biar tambah happy',NULL),(48,12,'Es TTM','6000','minuman',10,'tersedia','How to Make Delicious Strawberry Milk Soda.jpg','Perpaduan Strawberry Milky Soda yang bikin hari jadi warna warni',NULL),(49,18,'Nasi Chicken Katsu ','6000','makanan',10,'tersedia','katsu.jpg','Nasi chicken katsu adalah hidangan Jepang berupa potongan dada ayam fillet yang dibalur tepung roti (panko) dan digoreng hingga renyah. Kata \"katsu\" sendiri berasal dari singkatan bahasa Jepang katsuretsu (adaptasi dari bahasa Inggris cutlet). Menu ini disajikan bersama nasi putih hangat.??',NULL),(50,18,'Mie Ngaco','7000','makanan',98,'tersedia','miegacoan.jpg','Mie Ngaco adalah jajanan kuliner kekinian berupa mie pedas manis atau asin yang dilengkapi dengan pangsit dan berbagai macam topping seperti siomai, lumpia, atau bola keju. Kuliner ini populer dengan sebutan tingkat kepedasan yang ekstrem.',NULL),(51,18,'Mie Ayam','7000','makanan',100,'tersedia','mieayam.jpg','Mie ayam adalah hidangan khas Indonesia berupa mi gandum kuning yang direbus, diberi bumbu gurih atau manis, dan dilengkapi dengan potongan daging ayam, sayuran (sawi), serta kuah kaldu. Kuliner ini merupakan hasil perpaduan budaya Tionghoa dan Nusantara yang sangat populer.??',NULL),(52,18,'Mie Ayam Ceker','10000','makanan',100,'tersedia','Mie Ayam Ceker.jpg','Mie ayam ceker adalah varian hidangan mi ayam yang disajikan dengan tambahan topping ceker (kaki) ayam yang dimasak empuk dengan bumbu kecap bercita rasa manis dan gurih. Perpaduan kuahnya yang kental atau nyemek dengan tekstur ceker yang lembut membuat hidangan ini sangat populer.',NULL),(53,18,'Es Degan','5000','minuman',100,'tersedia','Es Degan.jpg','Es degan adalah sebutan lain untuk es kelapa muda. Minuman segar khas Indonesia ini terbuat dari perpaduan air dan kerokan daging kelapa muda, yang disajikan dengan tambahan es batu serta pemanis seperti gula cair, sirup, atau susu kental manis.',NULL),(54,18,'Es Teh','3000','minuman',100,'tersedia','10 ????????????????????????????????????????????? ????????????????????????????????????????????????????????????????????????????????????.jpg','Es degan adalah sebutan lain untuk es kelapa muda. Minuman segar khas Indonesia ini terbuat dari perpaduan air dan kerokan daging kelapa muda, yang disajikan dengan tambahan es batu serta pemanis seperti gula cair, sirup, atau susu kental manis.',NULL),(55,14,'Sate Tahu','5000','snack',100,'tersedia','satetahu.jpg','Sate tahu adalah hidangan sate alternatif yang menggunakan tahu sebagai bahan utama pengganti daging. Makanan ini populer sebagai camilan lezat yang ekonomis atau hidangan bagi vegetarian, disajikan dengan siraman bumbu kacang yang gurih dan manis.',NULL),(56,14,'Kopi Hitam','3000','minuman',100,'tersedia','Lets Meet for Coffee.jpg','Kopi Hitam adalah minuman kopi murni yang disajikan tanpa tambahan susu, krimer, atau gula. Minuman ini dibuat dengan menyeduh bubuk kopi menggunakan air panas, menghasilkan cita rasa kopi yang pekat dan autentik dengan tingkat kalori yang sangat rendah.',NULL),(57,15,'Nasi Telur Tahu/Tempe','6000','makanan',100,'tersedia','nastulpe.jpg','Nasi Telur Tahu/Tempe adalah hidangan praktis khas rumahan yang memadukan nasi putih hangat dengan lauk pelengkap sederhana berupa telur (biasanya ceplok atau dadar), tahu, dan tempe. Menu ini sangat populer di Indonesia karena rasanya yang nikmat, ramah di kantong, dan tinggi protein.',NULL),(58,15,'Mie Sedaap Cup Kari Spesial','7000','makanan',100,'tersedia','Mie Instan Sedaap Cup 81g - Rasa Kari Spesial, Tekstur Kenyal, Bawang Goreng Kriuk.jpg','Menghadirkan mie kuah kental dengan aroma dan rasa rempah kari khas Indonesia yang gurih, hangat, dan autentik.',NULL),(59,15,'Mie Sedaap Cup Korean Spicy','7000','makanan',100,'tersedia','Mi Sedaap Cup Korean Spicy Chicken Flavor - Pack of 2.jpg','Menawarkan mi bertekstur tebal dan kenyal ala ramyun dengan perpaduan rasa manis, gurih, serta tingkat kepedasan yang bisa diatur sendiri.',NULL),(60,15,'Mie Sedaap Cup Ayam Jerit','7000','makanan',100,'tersedia','SEDAAP MIE CUP RASA AYAM JERIT 75 GR.jpg','Menyajikan kombinasi gurihnya kaldu ayam tradisional nusantara yang menyatu dengan sengatan rasa pedas cabai lokal yang tajam.',NULL),(61,15,'Nasi Pecel','6000','makanan',10,'tersedia','download.jpg','Menyajikan kombinasi gurihnya kaldu ayam tradisional nusantara yang menyatu dengan sengatan rasa pedas cabai lokal yang tajam.',NULL),(62,15,'Pop Ice Durian','3000','minuman',10,'tersedia','duren.jpg','Pop Ice Durian adalah varian minuman milkshake bubuk instan dari merek legendaris Pop Ice yang menawarkan cita rasa khas buah durian yang manis dan beraroma kuat',NULL),(63,16,'Nasi Pecel','6000','makanan',10,'tersedia','cel.jpg','Nasi pecel adalah potret kesederhanaan kuliner Jawa yang kaya akan gizi, memadukan segarnya sayuran hijau dengan gurihnya saus kacang tradisional. Hidangan ini tidak hanya sekadar pengisi perut, melainkan simbol kehangatan sarapan pagi masyarakat Indonesia yang merakyat dan tak lekang oleh zaman.',NULL),(64,16,'Pop Ice Coklat','3000','minuman',10,'tersedia','lat.jpg','Pop Ice Coklat adalah salah satu varian rasa paling legendaris, paling laris, dan menjadi pilar utama dari seluruh rangkaian produk minuman milkshake serbuk instan merek Pop Ice.',NULL),(65,16,'Dimsum Goreng Keju','2500','snack',98,'tersedia','DIMSUM GORENG KEJU LUMER FROZEN.jpg','Dimsum goreng keju adalah inovasi kuliner modern yang menggabungkan kelezatan adonan dimsum gurih (biasanya berbahan dasar ayam dan udang) dengan sensasi lumeran keju di dalamnya.',NULL),(66,16,'Makaroni Pedas','2000','snack',100,'tersedia','makronipds.jpg','Makaroni pedas adalah jajanan jalanan (street food) dan camilan kering khas Indonesia yang sangat populer karena perpaduan rasa pedas, gurih, asin, dan teksturnya yang adiktif.',NULL),(67,16,'Es Teh','3000','minuman',100,'tersedia','iceteh.jpg','Es teh adalah minuman penyegar paling universal, merakyat, dan tak tergantikan di Indonesia yang dibuat dari seduhan daun teh (Camellia sinensis) lalu disajikan dingin bersama bongkahan es batu',NULL),(68,16,'Soto Ayam','7000','makanan',49,'tersedia','toyam.jpg','Soto ayam adalah kuliner sup tradisional khas Indonesia yang terdiri dari suwiran daging ayam, soun, kol, tauge, dan telur rebus, lalu disiram dengan kaldu ayam yang kaya akan rempah-rempah',NULL),(69,17,'Pentol','1000','snack',100,'tersedia','1bb093a3ae943c8b0b26670833679e2b.jpg','Pentol adalah sebutan khas masyarakat Jawa Timur dan Jawa Tengah untuk jajanan tradisional berbentuk bulatan mirip bakso, namun memiliki kadar campuran daging yang lebih sedikit dan dominan tepung tapioka (kanji)',NULL),(70,17,'Tahu Walik','1000','snack',100,'tersedia','tahuwalik.jpg','Tahu walik adalah camilan gorengan tradisional khas Banyuwangi, Jawa Timur, yang dinamai berdasarkan proses pembuatannya, yaitu tahu goreng yang dibelah dan dibalik (diwalik) hingga bagian dalamnya berada di luar. ',NULL),(71,17,'Indomie Goreng Bangladesh','5000','makanan',99,'tersedia','indoladesh.jpg','Indomie Goreng Bangladesh (atau Mi Bangladesh) adalah kreasi olahan mie instan legendaris asal Kota Medan, Sumatera Utara, yang dimasak setengah basah (nyemek) dengan tambahan bumbu pasta rempah khas Aceh yang sangat pekat',NULL),(72,17,'Indomie Soto','5000','makanan',10,'tersedia','indosoto.jpg','Indomie Soto adalah salah satu varian mie instan kuah paling legendaris dan paling digemari dari lini produk Indomie. Varian ini menawarkan kelezatan kuah soto khas Indonesia yang gurih, segar, dan beraroma rempah tradisional. Karakter kuahnya yang ringan namun kaya rasa menjadikannya sebagai makanan penyelamat (comfort food) favorit masyarakat, terutama saat cuaca dingin atau hujan.',NULL),(73,17,'Soto Ayam','7000','makanan',100,'tersedia','If you have been to Jakarta, Bali, or any part of???.jpg','Soto ayam adalah sebuah karya seni kuliner sup Nusantara yang mengutamakan kedalaman rasa kaldu, di mana kesegaran sari pati ayam berpadu harmonis dengan kehangatan rempah-rempah rimpang asli Indonesia.',NULL),(74,17,'Sompil','5000','makanan',100,'tersedia','sompel.jpg','Sompil adalah kuliner lodeh lontong tradisional khas Mataraman, terutama sangat populer di Kabupaten Tulungagung dan sekitarnya (seperti Trenggalek dan Blitar). Hidangan merakyat ini menyajikan irisan lontong yang bertekstur sangat lembut, lalu diguyur dengan sayur lodeh (jangan) pedas serta taburan bubuk kedelai gurih di atasnya.',NULL);
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
  `METODE` enum('cash','qris') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES (1,'ORD-20260520-6953F',1,12,'cash','2026-05-20','03:40:55',15000,'diproses',''),(2,'ORD-20260520-7602C',1,12,'cash','2026-05-20','04:46:37',226000,'dikonfirmasi',''),(3,'ORD-20260520-9F36A',1,12,'cash','2026-05-20','05:54:00',6000,'diproses','tanpa mayo'),(4,'ORD-20260520-5C39D',2,12,'cash','2026-05-20','07:50:25',16000,'pending',''),(5,'ORD-20260527-7270F',2,12,'cash','2026-05-27','13:56:56',8000,'dikonfirmasi','ga pedas'),(6,'ORD-20260527-55B0F',1,1,'cash','2026-05-27','14:33:58',10000,'selesai','tahu kres tanpa tahu,jamur krispi tanpa tahu'),(7,'ORD-20260528-71543',1,12,NULL,'2026-05-28','03:35:20',5000,'pending',''),(8,'ORD-20260528-96F42',2,12,'cash','2026-05-28','03:42:08',8000,'pending',''),(9,'ORD-20260528-72A33',2,12,'qris','2026-05-28','04:01:22',8000,'selesai',''),(10,'ORD-20260529-4B1F1',2,11,'qris','2026-05-29','00:53:11',8000,'selesai','gak pake lele indo'),(11,'ORD-20260529-BC447',3,11,'cash','2026-05-29','02:25:05',8000,'pending','jangan pakai ayam indo'),(12,'ORD-20260529-644E2',2,11,'cash','2026-05-29','02:30:22',16000,'selesai','jangan pakai sambal yg pedes ya pak.'),(13,'ORD-20260529-A59F6',1,11,'cash','2026-05-29','08:44:05',20000,'pending',''),(14,'ORD-20260529-18FE5',2,11,'cash','2026-05-29','08:44:59',98000,'selesai',''),(15,'ORD-20260529-78FA9',3,11,'cash','2026-05-29','08:58:40',3000,'pending',''),(16,'ORD-20260529-6BD1F',2,11,'qris','2026-05-29','09:09:20',8000,'selesai',''),(17,'ORD-20260529-A3FBC',2,12,'cash','2026-05-29','09:15:48',6000,'selesai','jangan kasi'),(18,'ORD-20260603-7B389',1,12,'cash','2026-06-03','19:20:38',20000,'pending','');
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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'adin','$2y$10$hwbGXKyRhBP/CFqrkPQCHOBE/ee7An7yIrwYdPlselw2.wcACryKG','MUHAMMAD SAIFUDDIN','+62 81235807937','adin@dnproject.my.id','PEMBELI','57717d6c54430bdbf77a884dcc109e93.png','1'),(3,'mulyono','mobil esemka','bapak mulyono','+62 097384324736','ratapansolo@solo.com','PEMBELI',NULL,'1'),(4,'atemin_nyata','ini atemin ygy','pokok admin','+62 097384434736','adminUntukNyata@gmail.com','ADMIN',NULL,'1'),(5,'EBADRUS','GURU PPLG','PAK BADRUS','+62 097344434736','guru@ebadrus.com','PEMBELI',NULL,'1'),(7,'tuwes','tuwes123','buwat tuwes','+60 1234567890','tuwes@tuwes.com','PEMBELI',NULL,'1'),(8,'bagus','bagus14','prasetiyo','+62 87521098','vinas@gmail.com','PEMBELI',NULL,'1'),(9,'murid','murid1','murid aseli','+62 765432189','murid@gmail.com','PEMBELI',NULL,'1'),(11,'murid1','$2y$10$6Z/AiyeIOEsVwEzERnX/SepRZ0XCD8TPQTy51pTCjHNLWgJeq/9UG','murid aselioi','+62 7654321892','murid1@gmail.com','PEMBELI',NULL,'1'),(12,'siswa','$2y$10$J6p0a3AulK3YR/GavIjrSOY3r0C6391uLO13wfsR9WMS4H6TIl18K','Higuruma','+62 7687678678','siswa@gmail.com','PEMBELI','user_12_1780032655.jpg','1'),(13,'admin','$2y$10$Jr4drTCswbJ6U3QtLGUY5.YbuH9.be2FEvWon.kQ307/8gx8rPlNu','admin nyata banget coy versi password hash','+62 56789765','atemin@gmail.com','ADMIN',NULL,'1'),(15,'penjual2','$2y$10$wSWn263gFcoxMFdaDv2JIe8SdXOtd49/yApXlRTYAXntJXQ2jtxLy','Pak Sahudi','+62 456789876','wpenjual@gmail.com','PENJUAL',NULL,'1'),(24,'penjual1','$2y$10$6.LPzla5U57eX/sDU9LdJuCPmyKZFGK3V2xRtijLFwARRgey6f1py','Bu Dian','+62 81235807939','penju1al@gmail.com','PENJUAL','user_24_1779414158.png','1'),(36,'admin baru','$2y$10$NgsjbotUK3aD6RHRhdGCgunZ3pe4ynIrjBncS2UQWQEnwzG3e3B3G','atemin','+62 111111111','adminaslkdfj@gmail.com','ADMIN',NULL,'1'),(37,'penjual3','$2y$10$.SuJKPsETmG3VsDkjrMtC.jdJlr3U5yB5NoErOPBdc3YCCLi0fD3m','Bu Kom','+62 2233342','penjuaddl@gmail.com','PENJUAL',NULL,'1'),(39,'mentri kehutanan','$2y$10$uHysCSMibUDaSJLTzF3eTOO3gYARqizA58h.xbF0Qbc2Dt9JJhyAm','hutan asri banget','+62 81235807931','hutanindonesia@gmail.com','PEMBELI',NULL,'1'),(40,'monyet','$2y$10$aICiso2vUAWNYNjt.kDmAeVjGeEatiN4gARzzIxQZoPoKAX5xyRna','monyet asli indonesia','+62 81235807933','monyet23@gmail.com','PEMBELI',NULL,'1'),(41,'lala','$2y$10$rYqnHUACmg9QuwRJBck0cegipaUru2mP5R7HKT7uMB.drNEKK0w3.','lalalalili','+62 81235807945','lala@gmail.com','PEMBELI','default.jpg','1'),(42,'penjual4','$2y$10$gDbbS/ATzZQ5czY4ldPUI.Rv8DcOBz4YYCWLOEr1CH64Say2FyVDq','Pak Agus','+62 090897876564','penjual5@gmail.com','PENJUAL','default.jpg','1'),(43,'penjual5','$2y$10$UhVW2x68aeMIDgYM5ykotueKWKNszmZ.oj0ThFZRs04V55W3kGf42','Bu Tika','+62 3989786543','Pwenjuwal6@gmail.com','PENJUAL','default.jpg','1'),(44,'penjual6','$2y$10$GD2TAsvgzTyhu7sHHUdfuOW.jyzeLYgTo.U6G.FVsNjODP7Uy3kN2','Pak Angga','+62 2345678901','PakAngga@gmail.com','PENJUAL','default.jpg','1'),(45,'penjual7','$2y$10$deflBrW33.0Jov7Syh4vqOD28FDTmg/meh11qWU5hQdzfApBkBFta','Pak Sukamto','+62 8912675432','pen7ual@gmail.com','PENJUAL','default.jpg','1'),(46,'penjual8','$2y$10$7GrpWCdtWEkNerUTyxleWe/madWIQXeVxK8mD.HleBxRXma4nubYu','Mardika','+99775533221','penjual8@gmail.com','PENJUAL','default.jpg','1'),(47,'penjual9','$2y$10$ElVMgx1e8SQDVGF96SmQNeaq/o3.GizLU.DVilEZc/JsdeZeemLpy','Pak Basuni','+62908765234','penjua9n@gmail.com','PENJUAL','default.jpg','1'),(48,'penjual10','$2y$10$HYV0LphwTgreGajuJDAl2ezsYc6TG3arZ5JGyeLfgiExnI/DUmYUK','Pak Fajar','+62 8902356977','penjua10n@gmail.com','PENJUAL','default.jpg','1');
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

-- Dump completed on 2026-06-04  0:12:12

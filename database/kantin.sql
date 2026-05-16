-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: my-database
-- Generation Time: May 16, 2026 at 08:52 AM
-- Server version: 8.0.46
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kantin`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_menu_kantin`
--

CREATE TABLE `detail_menu_kantin` (
  `id` int NOT NULL,
  `id_kantin` int NOT NULL,
  `menu` varchar(100) NOT NULL,
  `harga` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `detail_menu_kantin`
--

INSERT INTO `detail_menu_kantin` (`id`, `id_kantin`, `menu`, `harga`) VALUES
(1, 1, 'nasi goreng mawut', 6000),
(2, 1, 'bakso', 8000),
(3, 2, 'sate kambing', 7000),
(4, 3, 'ayam geprek', 5000);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `ID` int NOT NULL,
  `KELAS` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`ID`, `KELAS`) VALUES
(1, 'X PPLG 1'),
(2, 'X PPLG 2'),
(3, 'XI PPLG 1'),
(4, 'XI PPLG 2'),
(5, 'X DKV 1'),
(6, 'X DKV 2'),
(7, 'XI DKV 1'),
(8, 'XI DKV 2'),
(9, 'X AKL 1'),
(10, 'X AKL 2'),
(11, 'X AKL 3'),
(12, 'X AKL 4'),
(13, 'XI AKL 1'),
(14, 'XI AKL 2'),
(15, 'XI AKL 3'),
(16, 'XI AKL 4'),
(17, 'X MPLB 1'),
(18, 'X MPLB 2'),
(19, 'X MPLB 3'),
(20, 'X MPLB 4'),
(21, 'XI MPLB 1'),
(22, 'XI MPLB 2'),
(23, 'XI MPLB 3'),
(24, 'XI MPLB 4'),
(25, 'X BD 1'),
(26, 'X BD 2'),
(27, 'X BD 3'),
(28, 'XI BD 1'),
(29, 'XI BD 2'),
(30, 'XI BD 3'),
(31, 'X TJKT 1'),
(32, 'X TJKT 2'),
(33, 'X TJKT 3'),
(34, 'XI TJKT 1'),
(35, 'XI TJKT 2'),
(36, 'XI TJKT 3'),
(37, 'X ANIMASI 1'),
(38, 'X ANIMASI 2'),
(39, 'XI ANIMASI 1'),
(40, 'XI ANIMASI 2'),
(41, 'X TKI 1'),
(42, 'X TKI 2'),
(43, 'XI TKI 1'),
(44, 'XI TKI 2'),
(45, 'X PSPT 1'),
(46, 'X PSPT 2'),
(47, 'XI PSPT 1'),
(48, 'XI PSPT 2'),
(49, 'X ULW 1'),
(50, 'X ULW 2'),
(51, 'XI ULW 1'),
(52, 'XI ULW 2'),
(53, 'XII PPLG 1'),
(54, 'XII PPLG 2'),
(55, 'XII DKV 1'),
(56, 'XII DKV 2'),
(57, 'XII AKL 1'),
(58, 'XII AKL 2'),
(59, 'XII AKL 3'),
(60, 'XII AKL 4'),
(61, 'XII MPLB 1'),
(62, 'XII MPLB 2'),
(63, 'XII MPLB 3'),
(64, 'XII MPLB 4'),
(65, 'XII BD 1'),
(66, 'XII BD 2'),
(67, 'XII BD 3'),
(68, 'XII TJKT 1'),
(69, 'XII TJKT 2'),
(70, 'XII TJKT 3'),
(71, 'XII ANIMASI 1'),
(72, 'XII ANIMASI 2'),
(73, 'XII TKI 1'),
(74, 'XII TKI 2'),
(75, 'XII PSPT 1'),
(76, 'XII PSPT 2'),
(77, 'XII ULW 1'),
(78, 'XII ULW 2');

-- --------------------------------------------------------

--
-- Table structure for table `keranjang`
--

CREATE TABLE `keranjang` (
  `id_keranjang` int NOT NULL,
  `id_user` int NOT NULL,
  `id_menu` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `keranjang`
--

INSERT INTO `keranjang` (`id_keranjang`, `id_user`, `id_menu`, `qty`) VALUES
(35, 11, 8, 1),
(36, 11, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `list_kantin`
--

CREATE TABLE `list_kantin` (
  `ID` int NOT NULL,
  `NAMA_KANTIN` varchar(20) NOT NULL,
  `FOTO_KANTIN` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `list_kantin`
--

INSERT INTO `list_kantin` (`ID`, `NAMA_KANTIN`, `FOTO_KANTIN`) VALUES
(1, 'kantin pak trisno', 'kantin1.jpg'),
(2, 'kantin bu rully', 'kantin2.jpg'),
(3, 'kantin pak fajar', 'kantin3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `MURID`
--

CREATE TABLE `MURID` (
  `ID_MURID` int NOT NULL,
  `ID_USER` int NOT NULL,
  `NISN` varchar(10) NOT NULL,
  `ID_KELAS` int DEFAULT NULL,
  `TEMPAT_LAHIR` varchar(50) DEFAULT NULL,
  `TANGGAL_LAHIR` date DEFAULT NULL,
  `ALAMAT_RUMAH` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `MURID`
--

INSERT INTO `MURID` (`ID_MURID`, `ID_USER`, `NISN`, `ID_KELAS`, `TEMPAT_LAHIR`, `TANGGAL_LAHIR`, `ALAMAT_RUMAH`) VALUES
(2, 1, '1234567890', 2, 'TULUNGAGUNG', '2009-12-10', 'DESA PUCANGLABAN'),
(3, 7, '67853999', 2, 'the gunung', '2002-03-12', 'the gunung'),
(4, 8, '12345678', 1, 'tulungagung', '2026-04-09', 'bago');

-- --------------------------------------------------------

--
-- Table structure for table `tb_menu`
--

CREATE TABLE `tb_menu` (
  `ID_MENU` int NOT NULL,
  `ID_KANTIN` int DEFAULT NULL,
  `NAMA_MENU` varchar(100) NOT NULL,
  `HARGA` varchar(15) NOT NULL,
  `KATEGORI` enum('makanan','minuman','snack') NOT NULL,
  `STOK` int DEFAULT '0',
  `STATUS` enum('tersedia','habis') DEFAULT 'tersedia',
  `FOTO_MENU` varchar(255) DEFAULT NULL,
  `DESK` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_menu`
--

INSERT INTO `tb_menu` (`ID_MENU`, `ID_KANTIN`, `NAMA_MENU`, `HARGA`, `KATEGORI`, `STOK`, `STATUS`, `FOTO_MENU`, `DESK`) VALUES
(1, 1, 'Nasi Ayam Bakar', '8000', 'makanan', 22, 'tersedia', 'ayambakar.jpg', 'Ayam bakar adalah hidangan khas Indonesia berupa daging ayam yang dimarinasi dengan rempah-rempah (seperti kunyit, bawang putih, kemiri), kemudian dipanggang di atas bara arang atau teflon hingga bumbu meresap ke dalam daging. Karakteristik utamanya adalah aroma asap (smokey) yang kuat, kulit yang kecoklatan, dan tekstur daging yang lembut.'),
(2, 1, 'Chocolatos Drink', '5000', 'minuman', 10, 'tersedia', 'chocolatos.jpg', 'Chocolatos Drink adalah minuman cokelat bubuk instan yang diproduksi oleh Garudafood, yang menonjolkan cita rasa cokelat Italia yang intens, creamy, dan premium. Minuman ini populer karena rasanya yang gurih-manis, tekstur kental, dan kemudahan penyajian (bisa panas maupun dingin). '),
(3, 1, 'Risol Mayo', '3000', 'snack', 20, 'tersedia', 'risolmayo.jpg', 'Risol mayo adalah camilan gurih kekinian berupa kulit dadar tipis renyah berlapis tepung panir, diisi daging asap (smoked beef), telur rebus, keju, dan mayones yang melimpah. Teksturnya renyah di luar, namun lembut dan lumer (creamy) di dalam. Umumnya dijual sebagai snack, risol mayo sering dideskripsikan sebagai \"lumer\", \"gurih\", dan \"krispi\".'),
(4, 2, 'Nasi Goreng', '8000', 'makanan', 20, 'tersedia', 'nasigoreng.jpg', 'Nasi goreng adalah hidangan khas Indonesia berupa nasi yang digoreng dalam minyak/margarin, dicampur bumbu kaya rempah (bawang, kecap manis, terasi). Makanan ini fleksibel dengan topping telur, ayam, atau seafood. Populer karena rasa gurih-manis yang khas, nikmat, dan sering disajikan hangat dengan kerupuk. \r\n'),
(5, 2, 'Drink Beng Beng', '5000', 'minuman', 10, 'tersedia', 'dringbeng.jpg', 'Drink Beng Beng adalah minuman cokelat instan yang mengadaptasi rasa ikonik dari snack bar Beng Beng, memadukan cokelat, susu, krimer, dan ekstrak malt yang menghasilkan rasa creamy, manis, dan gurih. Minuman ini populer disajikan dalam keadaan dingin (es) maupun panas, serta sering kali diperkaya dengan tambahan topping seperti susu evaporasi untuk meningkatkan tekstur dan rasa. '),
(6, 2, 'Risol Solo', '2000', 'snack', 20, 'tersedia', 'risolo.jpg', 'Risol Solo (Sosis Solo) adalah jajanan tradisional khas Surakarta yang berupa dadar gulung tipis berbahan telur dan tepung, berisi suwiran daging ayam atau sapi berbumbu gurih. Berbeda dengan risoles biasa yang menggunakan tepung panir, Sosis Solo umumnya dicelupkan ke dalam kocokan telur sebelum digoreng hingga kuning keemasan, menghasilkan kulit yang lembut di dalam namun sedikit renyah di luar. '),
(7, 3, 'Nasi Sate Ayam', '8000', 'makanan', 20, 'tersedia', 'sateayam.jpg', 'Sate ayam adalah hidangan khas Indonesia berupa potongan daging ayam yang ditusuk, dipanggang di atas bara arang hingga matang sempurna, dan disajikan dengan bumbu kacang atau kecap yang gurih-manis. Deskripsi ini menekankan pada aroma smoky (asap), kelembutan daging, dan kekayaan rempah, sering dipasangkan dengan lontong/nasi.'),
(8, 3, 'Martabak Manis', '5000', 'snack', 20, 'tersedia', 'martabakmanis.jpg', 'Martabak manis (dikenal juga sebagai terang bulan) adalah kue dadar tebal khas Indonesia yang dipanggang dengan adonan tepung terigu, telur, dan gula. Karakteristik utamanya adalah permukaan bergelembung (bersarang/ fluffy) dengan tekstur lembut, tebal, dan bagian bawah yang renyah setelah diolesi mentega.'),
(9, 3, 'Es Buah', '5000', 'minuman', 10, 'tersedia', 'esbuah.jpg', 'Es buah adalah hidangan penutup atau minuman segar khas Indonesia yang terdiri dari campuran berbagai potongan buah-buahan segar, disajikan dengan kuah manis (sirup/susu), dan es serut atau es batu');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int NOT NULL,
  `id_kantin` int DEFAULT NULL,
  `id_user` int DEFAULT NULL,
  `tgl` date DEFAULT (curdate()),
  `waktu` time DEFAULT (curtime())
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int NOT NULL,
  `USERNAME` varchar(100) DEFAULT NULL,
  `PASS` varchar(100) DEFAULT NULL,
  `NAMA_LENGKAP` varchar(150) DEFAULT NULL,
  `NO_TLP` varchar(18) DEFAULT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `ROLE` enum('PEMBELI','PENJUAL','ADMIN') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `FOTO_USERS` varchar(100) DEFAULT NULL,
  `STATUS` enum('1','0') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `USERNAME`, `PASS`, `NAMA_LENGKAP`, `NO_TLP`, `EMAIL`, `ROLE`, `FOTO_USERS`, `STATUS`) VALUES
(1, 'adin', '$2y$10$Sr8PkbInbzNv6zb27qWPmuHUPWkHDa.7mdNxiPeDLdDfVwV2MhPlu', 'MUHAMMAD SAIFUDDIN', '+62 81235807937', 'adin@dnproject.my.id', 'PEMBELI', '6bee6143c5f90bf6e241e21b79cc2feb.jpeg', '1'),
(2, 'penjual1', 'ADMIN!@#', 'penjual santoso eak', '+62 123654789', 'PENJUAL@gmail.com', 'PENJUAL', NULL, '1'),
(3, 'mulyono', 'mobil esemka', 'bapak mulyono', '+62 097384324736', 'ratapansolo@solo.com', 'PEMBELI', NULL, '1'),
(4, 'atemin_nyata', 'ini atemin ygy', 'pokok admin', '+62 097384434736', 'adminUntukNyata@gmail.com', 'ADMIN', NULL, '1'),
(5, 'EBADRUS', 'GURU PPLG', 'PAK BADRUS', '+62 097344434736', 'guru@ebadrus.com', 'PEMBELI', NULL, '1'),
(6, 'tes', NULL, NULL, NULL, NULL, 'PEMBELI', NULL, '1'),
(7, 'tuwes', 'tuwes123', 'buwat tuwes', '+60 1234567890', 'tuwes@tuwes.com', 'PEMBELI', NULL, '1'),
(8, 'bagus', 'bagus14', 'prasetiyo', '+62 87521098', 'vinas@gmail.com', 'PEMBELI', NULL, '1'),
(9, 'murid', 'murid1', 'murid aseli', '+62 765432189', 'murid@gmail.com', 'PEMBELI', NULL, '1'),
(11, 'murid1', '$2y$10$6Z/AiyeIOEsVwEzERnX/SepRZ0XCD8TPQTy51pTCjHNLWgJeq/9UG', 'murid aselioi', '+62 7654321892', 'murid1@gmail.com', 'PEMBELI', NULL, '1'),
(12, 'siswa', '$2y$10$J6p0a3AulK3YR/GavIjrSOY3r0C6391uLO13wfsR9WMS4H6TIl18K', 'siswa', '+62 7687678678', 'siswa@gmail.com', 'PEMBELI', NULL, '1'),
(13, 'admin', '$2y$10$Jr4drTCswbJ6U3QtLGUY5.YbuH9.be2FEvWon.kQ307/8gx8rPlNu', 'admin nyata banget coy versi password hash', '+62 56789765', 'atemin@gmail.com', 'ADMIN', NULL, '1'),
(15, 'penjual', '$2y$10$WBeInwwZtc7Vv5WCUQPtauWHIl/knlhdD.AlbvA5ua/.TY4rF3k1W', 'penjual nyata banget tipe pass hash', '+62 456789876', 'wpenjual@gmail.com', 'PENJUAL', NULL, '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_menu_kantin`
--
ALTER TABLE `detail_menu_kantin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_kantin` (`id_kantin`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD UNIQUE KEY `id_user` (`id_user`,`id_menu`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indexes for table `list_kantin`
--
ALTER TABLE `list_kantin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `MURID`
--
ALTER TABLE `MURID`
  ADD PRIMARY KEY (`ID_MURID`),
  ADD KEY `FK_ID_USER` (`ID_USER`),
  ADD KEY `FK_ID_KELAS` (`ID_KELAS`);

--
-- Indexes for table `tb_menu`
--
ALTER TABLE `tb_menu`
  ADD PRIMARY KEY (`ID_MENU`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kantin_fk` (`id_kantin`),
  ADD KEY `id_user_fk` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`),
  ADD UNIQUE KEY `NO_TLP` (`NO_TLP`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_menu_kantin`
--
ALTER TABLE `detail_menu_kantin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `keranjang`
--
ALTER TABLE `keranjang`
  MODIFY `id_keranjang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `list_kantin`
--
ALTER TABLE `list_kantin`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `MURID`
--
ALTER TABLE `MURID`
  MODIFY `ID_MURID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_menu`
--
ALTER TABLE `tb_menu`
  MODIFY `ID_MENU` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_menu_kantin`
--
ALTER TABLE `detail_menu_kantin`
  ADD CONSTRAINT `fk_id_kantin` FOREIGN KEY (`id_kantin`) REFERENCES `list_kantin` (`ID`);

--
-- Constraints for table `keranjang`
--
ALTER TABLE `keranjang`
  ADD CONSTRAINT `keranjang_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `keranjang_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `tb_menu` (`ID_MENU`);

--
-- Constraints for table `MURID`
--
ALTER TABLE `MURID`
  ADD CONSTRAINT `FK_ID_KELAS` FOREIGN KEY (`ID_KELAS`) REFERENCES `kelas` (`ID`),
  ADD CONSTRAINT `FK_ID_USER` FOREIGN KEY (`ID_USER`) REFERENCES `users` (`ID`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `id_kantin_fk` FOREIGN KEY (`id_kantin`) REFERENCES `list_kantin` (`ID`),
  ADD CONSTRAINT `id_user_fk` FOREIGN KEY (`id_user`) REFERENCES `users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

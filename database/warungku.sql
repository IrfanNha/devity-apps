-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 02, 2023 at 04:42 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warungku`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) DEFAULT NULL
);

--
-- Triggers `items`
--
DELIMITER $$
CREATE TRIGGER `after_insert_item` AFTER INSERT ON `items` FOR EACH ROW BEGIN
    INSERT INTO items_stock (user_id, item_id, items_qty)
    VALUES (NEW.user_id, NEW.id, 0);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_item_insert` AFTER INSERT ON `items` FOR EACH ROW BEGIN
    INSERT INTO items_stock (item_id, user_id)
    VALUES (NEW.id, NEW.user_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `items_stock`
--

CREATE TABLE `items_stock` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `items_qty` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
);

-- --------------------------------------------------------

--
-- Table structure for table `laporan_kasir`
--

CREATE TABLE `laporan_kasir` (
  `laporan_kasir_id` int(11) NOT NULL,
  `laporan_keuangan_id` int(11) NOT NULL,
  `item_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`item_details`)),
  `harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
);

-- --------------------------------------------------------

--
-- Table structure for table `laporan_keuangan`
--

CREATE TABLE `laporan_keuangan` (
  `laporan_keuangan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(255) NOT NULL,
  `success` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
);

--
-- Dumping data for table `login_attempts`
--

INSERT INTO `login_attempts` (`id`, `user_id`, `ip_address`, `success`, `created_at`) VALUES
(1, 1, '114.10.22.143', 1, '2023-09-05 06:55:41'),
(2, 1, '114.10.22.143', 1, '2023-09-05 06:56:16'),
(3, 1, '114.10.22.143', 1, '2023-09-05 10:48:26'),
(4, 1, '114.10.22.143', 1, '2023-09-05 10:53:21'),
(5, 1, '114.10.22.143', 1, '2023-09-05 11:38:57'),
(6, 1, '114.10.22.143', 1, '2023-09-05 11:40:45'),
(7, NULL, '::1', 0, '2023-09-05 11:49:51'),
(8, NULL, '::1', 0, '2023-09-05 11:50:48'),
(9, NULL, '114.10.22.143', 0, '2023-09-05 11:52:54'),
(10, NULL, '114.10.22.143', 0, '2023-09-05 12:20:08'),
(11, 1, '114.10.22.143', 1, '2023-09-05 12:20:18'),
(12, 1, '114.10.22.143', 1, '2023-09-05 12:52:11'),
(13, 1, '114.10.22.143', 1, '2023-09-06 00:30:32'),
(14, NULL, '114.10.22.143', 0, '2023-09-06 00:52:44'),
(15, 1, '114.10.22.143', 1, '2023-09-06 00:53:06'),
(16, 1, '114.10.124.18', 1, '2023-09-07 05:12:44'),
(17, 1, '114.10.120.138', 1, '2023-09-09 10:13:15'),
(18, NULL, '114.10.120.138', 0, '2023-09-09 12:03:33'),
(19, NULL, '114.10.120.138', 0, '2023-09-09 12:03:46'),
(20, 6, '114.10.120.138', 1, '2023-09-09 12:09:03'),
(21, 1, '114.10.120.138', 1, '2023-09-09 13:03:52'),
(22, 1, '114.10.20.167', 1, '2023-09-09 13:54:14'),
(23, 1, '114.10.20.167', 1, '2023-09-09 14:12:50'),
(24, 1, '114.10.20.167', 1, '2023-09-09 15:45:54'),
(25, 1, '114.10.20.167', 1, '2023-09-09 15:55:48'),
(26, 1, '114.10.4.8', 1, '2023-09-10 01:36:18'),
(27, 1, '114.10.4.8', 1, '2023-09-10 01:54:07'),
(28, 1, '114.10.4.8', 1, '2023-09-10 01:56:46'),
(29, 1, '114.10.4.8', 1, '2023-09-10 02:08:04'),
(30, 1, '114.10.7.48', 1, '2023-09-10 03:43:40'),
(31, 1, '114.10.7.48', 1, '2023-09-10 04:51:53'),
(32, 1, '114.10.7.48', 1, '2023-09-10 05:52:21'),
(33, NULL, '114.10.7.48', 0, '2023-09-10 07:09:38'),
(34, 1, '114.10.7.48', 1, '2023-09-10 07:11:17'),
(35, 1, '114.10.7.48', 1, '2023-09-10 08:51:46'),
(36, 1, '114.10.7.48', 1, '2023-09-10 08:56:42'),
(37, 1, '114.10.20.44', 1, '2023-09-10 12:24:53'),
(38, 1, '114.10.20.44', 1, '2023-09-10 12:31:23'),
(39, 1, '114.10.20.138', 1, '2023-09-11 01:48:57'),
(40, 1, '114.10.20.138', 1, '2023-09-11 01:55:07'),
(41, 1, '114.10.17.214', 1, '2023-09-11 03:48:43'),
(42, 1, '114.10.17.214', 1, '2023-09-11 04:04:00'),
(43, 1, '103.3.222.20', 1, '2023-09-29 13:20:10'),
(44, 9, '103.3.222.20', 1, '2023-09-29 13:25:23'),
(45, 1, '103.3.222.20', 1, '2023-09-29 13:29:06'),
(46, 1, '103.3.222.20', 1, '2023-09-29 13:29:29'),
(47, 1, '103.3.222.20', 1, '2023-09-29 13:29:49'),
(48, 1, '103.3.222.20', 1, '2023-09-29 13:31:54'),
(49, 1, '103.3.222.20', 1, '2023-09-30 11:35:02'),
(50, 1, '::1', 1, '2023-10-02 02:36:23'),
(51, 1, '182.2.52.206', 1, '2023-10-02 02:59:55');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `user_id` int(11) DEFAULT NULL
);

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama`, `harga`, `user_id`) VALUES
(20, 'sego borok', '100000.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_order`
--

CREATE TABLE `riwayat_order` (
  `id` int(11) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
);

--
-- Dumping data for table `riwayat_order`
--

INSERT INTO `riwayat_order` (`id`, `data`, `created_at`, `user_id`) VALUES
(1, '{\"keranjang\":[{\"nama\":\"Menu1\",\"harga\":10,\"quantity\":2,\"subtotal\":20},{\"nama\":\"Menu2\",\"harga\":15,\"quantity\":1,\"subtotal\":15}],\"totalHarga\":35}', '2023-10-02 03:04:56', 1),
(2, '{\"keranjang\":[{\"nama\":\"Menu3\",\"harga\":8,\"quantity\":3,\"subtotal\":24},{\"nama\":\"Menu1\",\"harga\":10,\"quantity\":1,\"subtotal\":10}],\"totalHarga\":34}', '2023-10-02 03:04:56', 2),
(3, '{\"keranjang\":[{\"nama\":\"Menu2\",\"harga\":15,\"quantity\":2,\"subtotal\":30},{\"nama\":\"Menu3\",\"harga\":8,\"quantity\":2,\"subtotal\":16}],\"totalHarga\":46}', '2023-10-02 03:04:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_rank` int(11) NOT NULL DEFAULT 1,
  `token` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `payment_key` varchar(255) DEFAULT NULL,
  `subs_expiry` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `is_paid` tinyint(1) DEFAULT 0
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `user_rank`, `token`, `is_active`, `payment_key`, `subs_expiry`, `created_at`, `is_paid`) VALUES
(1, 'admin', 'mazirfan0099@gmail.com', '$2y$10$pJ3KTMKAUjVzzH9bk9Btf.YOukBS.HcpVxGmmYyY3rPh8O7WSx1Nu', 3, '232cdf91f965d69860bfb94754ebee20e5466b03a24c278185b08bdf90d50028', 1, NULL, NULL, '2023-09-05 02:02:00', 0),
(3, 'ayis', 'akuntugas010102@gmail.com', '$2y$10$Q.2PgVTjQMqCcXL6dkWEneHZqiuDiHM.FNubpa06KF67xglHw7xRO', 2, '9a3ca592f4b8191491adcc610c53a70080925fddc81b4e43eb428b065453df27', 1, NULL, NULL, '2023-09-05 03:55:24', 0),
(6, 'users', 'finnch77@gmail.com', '$2y$10$CevbG07NZq8SBmqUKfiki.QLgXX/9tCkD6UamRVDljuZ2w2RY4Cyq', 3, '688f7323034ba8c60d1276803daba55c58521fdce8299860fa90edf40ce07b57', 1, 'M2IOBU61Q2NT', '2023-09-11', '2023-09-09 12:08:03', 0),
(8, 'ini rasa', 'ini.r4sa@gmail.com', '$2y$10$yepQdf1LCAdVdKuW9W97nOOBbVCUcGnRw6cEehEKk1YJhi2PdHEA6', 1, '2a9861bce30393ae186de05df634d9d16a2754f45bf11d63d0281eea030798c2', 1, 'VZRVJQTIKSR6', '2023-11-11', '2023-09-11 03:47:10', 1),
(9, 'irfan', 'brokirgaa2@gmail.com', '$2y$10$Aguma0DP3JuO/08dK83EqOiECgQp8ztRiQSZp275UBWR3evziS5S6', 1, '16c32b11f49910cc3496c7f9c70f2819680e081ca5655cce752aaecf7e5568b1', 1, NULL, NULL, '2023-09-29 13:23:26', 0);

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `delete_user_data` BEFORE DELETE ON `users` FOR EACH ROW BEGIN
    -- Hapus data yang terkait dengan pengguna (users) yang akan dihapus
    DELETE FROM users_preferences WHERE user_id = OLD.id;
    DELETE FROM items WHERE user_id = OLD.id;
    DELETE FROM items_stock WHERE user_id = OLD.id;
    -- Tambahkan perintah DELETE untuk setiap tabel yang perlu dihapus

    -- Selain itu, jika Anda memiliki tabel lain yang terkait dengan pengguna,
    -- tambahkan perintah DELETE untuk tabel tersebut di sini.

    -- Contoh:
    -- DELETE FROM related_table WHERE user_id = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users_preferences`
--

CREATE TABLE `users_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `store_name` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
);

--
-- Dumping data for table `users_preferences`
--

INSERT INTO `users_preferences` (`id`, `user_id`, `image`, `store_name`, `alamat`, `phone`) VALUES
(1, 1, 'images/logo.jpg', 'bagong store', 'jl solok cilangkap', '121313131313131'),
(3, 6, 'images/logo.jpg', 'andi store', 'jl solok cilangkap', '121313131313131');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_id` (`item_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `items_stock`
--
ALTER TABLE `items_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `laporan_kasir`
--
ALTER TABLE `laporan_kasir`
  ADD PRIMARY KEY (`laporan_kasir_id`),
  ADD KEY `laporan_keuangan_id` (`laporan_keuangan_id`);

--
-- Indexes for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD PRIMARY KEY (`laporan_keuangan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_menu` (`user_id`);

--
-- Indexes for table `riwayat_order`
--
ALTER TABLE `riwayat_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_preferences`
--
ALTER TABLE `users_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `items_stock`
--
ALTER TABLE `items_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `laporan_kasir`
--
ALTER TABLE `laporan_kasir`
  MODIFY `laporan_kasir_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  MODIFY `laporan_keuangan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `riwayat_order`
--
ALTER TABLE `riwayat_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users_preferences`
--
ALTER TABLE `users_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `items_stock`
--
ALTER TABLE `items_stock`
  ADD CONSTRAINT `items_stock_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `items_stock_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Constraints for table `laporan_kasir`
--
ALTER TABLE `laporan_kasir`
  ADD CONSTRAINT `laporan_kasir_ibfk_1` FOREIGN KEY (`laporan_keuangan_id`) REFERENCES `laporan_keuangan` (`laporan_keuangan_id`);

--
-- Constraints for table `laporan_keuangan`
--
ALTER TABLE `laporan_keuangan`
  ADD CONSTRAINT `laporan_keuangan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `fk_user_menu` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users_preferences`
--
ALTER TABLE `users_preferences`
  ADD CONSTRAINT `users_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

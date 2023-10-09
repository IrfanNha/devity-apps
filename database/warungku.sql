-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2023 at 04:23 PM
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
  `item_code` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_name`, `item_code`, `user_id`, `created_at`, `price`) VALUES
(33, 'susu kental manis', 'SUS1180097', 11, '2023-10-09 02:43:04', '6000.00');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `items_stock`
--

INSERT INTO `items_stock` (`id`, `user_id`, `item_id`, `items_qty`, `created_at`) VALUES
(102, 11, 33, 0, '2023-10-09 02:43:04'),
(103, 11, 33, 0, '2023-10-09 02:43:04');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_kasir`
--

CREATE TABLE `laporan_kasir` (
  `laporan_kasir_id` int(11) NOT NULL,
  `laporan_keuangan_id` int(11) NOT NULL,
  `item_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_keuangan`
--

CREATE TABLE `laporan_keuangan` (
  `laporan_keuangan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(51, 1, '182.2.52.206', 1, '2023-10-02 02:59:55'),
(52, 1, '114.10.18.179', 1, '2023-10-03 11:48:20'),
(53, 1, '103.3.221.72', 1, '2023-10-04 12:56:30'),
(54, 1, '103.3.221.72', 1, '2023-10-05 13:28:14'),
(55, 1, '::1', 1, '2023-10-09 01:54:25'),
(56, 1, '182.2.44.11', 1, '2023-10-09 01:55:21'),
(57, 1, '182.2.44.11', 1, '2023-10-09 02:25:32'),
(58, NULL, '114.10.11.190', 0, '2023-10-09 02:29:13'),
(59, 11, '114.10.11.190', 1, '2023-10-09 02:29:39'),
(60, 1, '114.10.11.190', 1, '2023-10-09 02:53:18'),
(61, 1, '202.80.217.120', 1, '2023-10-09 11:55:50');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama`, `harga`, `user_id`) VALUES
(22, 'kari ayam india', '12000.00', 11),
(24, 'Nasi Goreng', '15000.00', 1),
(25, 'Mie Goreng', '8000.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `created_at`) VALUES
(1, 1, '45000.00', '2023-10-09 13:29:46'),
(2, 1, '31000.00', '2023-10-09 13:32:41'),
(3, 1, '76000.00', '2023-10-09 13:39:26'),
(4, 1, '63000.00', '2023-10-09 13:49:18'),
(5, 1, '68000.00', '2023-10-09 13:53:40'),
(6, 1, '24000.00', '2023-10-09 14:04:12'),
(7, 1, '54000.00', '2023-10-09 14:12:35'),
(8, 1, '54000.00', '2023-10-09 14:12:36'),
(9, 1, '30000.00', '2023-10-09 14:13:39'),
(10, 1, '53000.00', '2023-10-09 14:21:17'),
(11, 1, '24000.00', '2023-10-09 14:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_name`, `item_price`, `quantity`, `created_at`) VALUES
(1, 1, 'Nasi Goreng', '15000.00', 3, '2023-10-09 13:29:46'),
(2, 2, 'Nasi Goreng', '15000.00', 1, '2023-10-09 13:32:41'),
(3, 2, 'Mie Goreng', '8000.00', 2, '2023-10-09 13:32:41'),
(4, 3, 'Nasi Goreng', '15000.00', 4, '2023-10-09 13:39:26'),
(5, 3, 'Mie Goreng', '8000.00', 2, '2023-10-09 13:39:26'),
(6, 4, 'Mie Goreng', '8000.00', 6, '2023-10-09 13:49:18'),
(7, 4, 'Nasi Goreng', '15000.00', 1, '2023-10-09 13:49:18'),
(8, 5, 'Nasi Goreng', '15000.00', 4, '2023-10-09 13:53:40'),
(9, 5, 'Mie Goreng', '8000.00', 1, '2023-10-09 13:53:40'),
(10, 6, 'Mie Goreng', '8000.00', 3, '2023-10-09 14:04:12'),
(11, 7, 'Mie Goreng', '8000.00', 3, '2023-10-09 14:12:35'),
(12, 7, 'Nasi Goreng', '15000.00', 2, '2023-10-09 14:12:35'),
(13, 8, 'Mie Goreng', '8000.00', 3, '2023-10-09 14:12:36'),
(14, 8, 'Nasi Goreng', '15000.00', 2, '2023-10-09 14:12:36'),
(15, 9, 'Nasi Goreng', '15000.00', 2, '2023-10-09 14:13:39'),
(16, 10, 'Nasi Goreng', '15000.00', 3, '2023-10-09 14:21:17'),
(17, 10, 'Mie Goreng', '8000.00', 1, '2023-10-09 14:21:17'),
(18, 11, 'Mie Goreng', '8000.00', 3, '2023-10-09 14:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_order`
--

CREATE TABLE `riwayat_order` (
  `id` int(11) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `riwayat_order`
--

INSERT INTO `riwayat_order` (`id`, `data`, `created_at`, `user_id`) VALUES
(1, '{\"keranjang\":[{\"nama\":\"Menu1\",\"harga\":10,\"quantity\":2,\"subtotal\":20},{\"nama\":\"Menu2\",\"harga\":15,\"quantity\":1,\"subtotal\":15}],\"totalHarga\":35}', '2023-10-02 03:04:56', 1),
(2, '{\"keranjang\":[{\"nama\":\"Menu3\",\"harga\":8,\"quantity\":3,\"subtotal\":24},{\"nama\":\"Menu1\",\"harga\":10,\"quantity\":1,\"subtotal\":10}],\"totalHarga\":34}', '2023-10-02 03:04:56', 2),
(3, '{\"keranjang\":[{\"nama\":\"Menu2\",\"harga\":15,\"quantity\":2,\"subtotal\":30},{\"nama\":\"Menu3\",\"harga\":8,\"quantity\":2,\"subtotal\":16}],\"totalHarga\":46}', '2023-10-02 03:04:56', 1);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_pembelian`
--

CREATE TABLE `riwayat_pembelian` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `tgl_pembayaran` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `riwayat_pembelian`
--

INSERT INTO `riwayat_pembelian` (`id`, `user_id`, `item_name`, `quantity`, `total_price`, `order_id`, `tgl_pembayaran`) VALUES
(16, 1, 'Nasi Goreng', 3, '45000.00', 10, '2023-10-09 14:21:17'),
(17, 1, 'Mie Goreng', 1, '8000.00', 10, '2023-10-09 14:21:17'),
(18, 1, 'Mie Goreng', 3, '24000.00', 11, '2023-10-09 14:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_penjualan`
--

CREATE TABLE `riwayat_penjualan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `riwayat_penjualan`
--

INSERT INTO `riwayat_penjualan` (`id`, `user_id`, `item_name`, `quantity`, `total`, `created_at`) VALUES
(1, 1, 'susu kental manis', 1, '10000.00', '2023-10-04 13:38:26'),
(2, 1, 'susu kental manis', 10, '100000.00', '2023-10-05 14:32:22'),
(3, 11, 'gula ireng', 3, '36000.00', '2023-10-09 02:34:00');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `user_rank`, `token`, `is_active`, `payment_key`, `subs_expiry`, `created_at`, `is_paid`) VALUES
(1, 'admin', 'a@admin', '$2y$10$pJ3KTMKAUjVzzH9bk9Btf.YOukBS.HcpVxGmmYyY3rPh8O7WSx1Nu', 3, '232cdf91f965d69860bfb94754ebee20e5466b03a24c278185b08bdf90d50028', 1, NULL, '2023-10-09', '2023-09-05 02:02:00', 0),
(3, 'kuncoroXgandung', 'akuntugas010102@gmail.com', '$2y$10$Q.2PgVTjQMqCcXL6dkWEneHZqiuDiHM.FNubpa06KF67xglHw7xRO', 3, '9a3ca592f4b8191491adcc610c53a70080925fddc81b4e43eb428b065453df27', 1, NULL, '2023-10-09', '2023-09-05 03:55:24', 0),
(6, 'users', 'finnch77@gmail.com', '$2y$10$CevbG07NZq8SBmqUKfiki.QLgXX/9tCkD6UamRVDljuZ2w2RY4Cyq', 3, '688f7323034ba8c60d1276803daba55c58521fdce8299860fa90edf40ce07b57', 1, 'M2IOBU61Q2NT', '2023-09-11', '2023-09-09 12:08:03', 0),
(8, 'ini rasa', 'ini.r4sa@gmail.com', '$2y$10$yepQdf1LCAdVdKuW9W97nOOBbVCUcGnRw6cEehEKk1YJhi2PdHEA6', 1, '2a9861bce30393ae186de05df634d9d16a2754f45bf11d63d0281eea030798c2', 1, 'VZRVJQTIKSR6', '2023-11-11', '2023-09-11 03:47:10', 1),
(11, 'johanymoushengker', 'brokirgaa2@gmail.com', '$2y$10$XxityTN05nmDiauNWp7Fc.DNRiyKEug0f2zEYGlanfTnoQhlNEiQy', 1, '160fbddfa1bef61ebfdae9a8750e630dac447a2cf45b8cc47cd5ff0965ceca0d', 1, NULL, NULL, '2023-10-09 02:26:30', 0);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_preferences`
--

INSERT INTO `users_preferences` (`id`, `user_id`, `image`, `store_name`, `alamat`, `phone`) VALUES
(1, 1, 'images/logo.jpg', 'bagong store', 'jl solok cilangkap', '121313131313131'),
(3, 6, 'images/logo.jpg', 'andi store', 'jl solok cilangkap', '121313131313131'),
(4, 11, 'images/JOHAN (2).jpg', 'pani puri setya cale cale', 'Jl. Peterongan Sari IV', '123 456 789');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_id` (`item_code`),
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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `riwayat_order`
--
ALTER TABLE `riwayat_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `riwayat_pembelian`
--
ALTER TABLE `riwayat_pembelian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `riwayat_penjualan`
--
ALTER TABLE `riwayat_penjualan`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `items_stock`
--
ALTER TABLE `items_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `riwayat_order`
--
ALTER TABLE `riwayat_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `riwayat_pembelian`
--
ALTER TABLE `riwayat_pembelian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `riwayat_penjualan`
--
ALTER TABLE `riwayat_penjualan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users_preferences`
--
ALTER TABLE `users_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `riwayat_pembelian`
--
ALTER TABLE `riwayat_pembelian`
  ADD CONSTRAINT `riwayat_pembelian_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `riwayat_pembelian_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `users_preferences`
--
ALTER TABLE `users_preferences`
  ADD CONSTRAINT `users_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2023 at 04:19 AM
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
-- Table structure for table `activation_keys`
--

CREATE TABLE `activation_keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `activation_key` varchar(25) NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activation_keys`
--

INSERT INTO `activation_keys` (`id`, `user_id`, `activation_key`, `is_used`) VALUES
(1, 1, 'NS4Y9F62G3IG3ZM14JT4R2ZMV', 1),
(2, 9, 'E0LJMJIDTSW6QGGFOXG2K50VX', 1),
(3, 8, 'NZ5GLSQFTGK2EIU78M130C6ZS', 1),
(4, 10, 'FNAGIF7DXJRQA81BNRPR7EUKG', 1),
(5, 11, 'YANRKQ5R23IOJKOVVHY41O4P2', 1),
(7, NULL, 'SDMZU2GL6IZE01273RZNKK5JU', 0),
(8, NULL, '70R7VDKD7E2K4TX90NYPULWPO', 0),
(9, NULL, 'D25U478SXCIRGBI9YTKKHM7XP', 0),
(10, NULL, 'QRLU3K3DKCKF2H4EH3EVR8P61', 0),
(11, NULL, '96F5LNHMPKSGSA6UGRXC8SDNK', 0),
(12, NULL, '1K7UA69ET4ONEORBBPTES1A71', 0),
(13, NULL, 'FOQRQM9LCGQUI0S6IBR0RPAG4', 0),
(14, NULL, 'ZHKQD9JY8Q3JF2S38J5OBN5KC', 0),
(15, NULL, 'TGDFV45I40Y08DDCA9CZE0PNJ', 0),
(16, NULL, 'AB7663RVIXNXLZOW1YP8MDW4R', 0),
(17, NULL, 'WSLGE8UH6IMTENBV5S7J60CJD', 0),
(18, NULL, 'ODSIFDHMXLFBRT86BYNV7XCDD', 0),
(19, NULL, 'NO0UMC0B0Q11YOHPH7681MVSP', 0),
(20, NULL, 'WT9U861EJ4TDSIFQENPIWXH14', 0),
(21, NULL, '1GEV5WBCHC6U9CQFIN5P4JYBW', 0),
(22, NULL, '74OPUKP8EVNY6VPR9S7ZD0KP7', 0),
(23, NULL, 'JRX9WYB1P4JG3PJMA7S44LDV3', 0),
(24, NULL, 'U8YLGX4KLC7NF6W9VNSXD2H36', 0),
(25, NULL, 'KH7DW89XUWKSXVORDTAM3EXAU', 0),
(26, NULL, 'Q5DVOS7NHLHY634U4HUZTKVYL', 0),
(27, NULL, 'VBMBPG3YNGZF8C9PZ1W8Z4EDU', 0),
(28, NULL, 'QZY2Y8KV1FWB259NTBDN1234G', 0),
(29, NULL, '1NEV82LUW00LUTHF10CWHEEUJ', 0),
(30, NULL, '3QTJ6M1WPDRRXHMWXXCZZ2EWA', 0),
(31, NULL, '2HT81Q30J5BC8MKC4O1FKV12K', 0),
(32, NULL, 'C12MJ5B7WEE6R6OYTDIBEVSTG', 0),
(33, NULL, '99QQ6X7SNNFQAIUU99L297RGM', 0),
(34, NULL, '4TIF4FUSYPEQOH1M6AANKD3JA', 0),
(35, NULL, '7QWL69O0EMP6BHL7T2ZCPKJL6', 0),
(36, NULL, 'O43V0EZKFLT4G37YMXTB5MCVI', 0),
(37, NULL, 'JMEZIO9UFXR6TP8SK4AQQFUPT', 0),
(38, NULL, 'S2VADYEISZQL3LSDXH53VQK0H', 0),
(39, NULL, 'L3ORX515I9HM4KZCWO5Z1M7TK', 0),
(40, NULL, 'LCZ5EQGDKHL5F8S4WTU6R3IGP', 0);

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
(36, 'Kecap', 'KEC9163415', 1, '2023-10-17 05:01:58', '12000.00');

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
(112, 1, 36, 0, '2023-10-17 05:01:58'),
(113, 1, 36, 0, '2023-10-17 05:01:58'),
(114, 1, 36, 5, '2023-10-17 05:02:34'),
(115, 1, 36, -3, '2023-10-17 05:02:57');

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
(54, 1, '114.10.18.162', 1, '2023-10-09 15:11:31'),
(55, 1, '114.10.18.162', 1, '2023-10-09 15:18:03'),
(56, 1, '114.10.16.1', 1, '2023-10-09 23:44:32'),
(57, 1, '114.10.16.1', 1, '2023-10-09 23:48:22'),
(58, 1, '114.10.16.1', 1, '2023-10-10 00:15:25'),
(59, 1, '182.2.68.183', 1, '2023-10-10 02:41:42'),
(60, 1, '182.2.68.183', 1, '2023-10-10 02:42:13'),
(61, 1, '114.10.11.185', 1, '2023-10-10 05:10:47'),
(62, NULL, '114.10.11.185', 0, '2023-10-10 05:11:28'),
(63, NULL, '114.10.11.185', 0, '2023-10-10 05:11:35'),
(64, 1, '114.10.11.185', 1, '2023-10-10 05:11:49'),
(65, 1, '114.10.11.185', 1, '2023-10-10 05:20:16'),
(66, 1, '114.10.11.185', 1, '2023-10-10 05:21:06'),
(67, 1, '202.80.217.120', 1, '2023-10-10 12:58:42'),
(68, 1, '103.3.220.22', 1, '2023-10-11 14:19:32'),
(69, 1, '::1', 1, '2023-10-17 04:05:47'),
(70, 10, '::1', 1, '2023-10-17 04:07:58'),
(71, 1, '::1', 1, '2023-10-17 04:11:43'),
(72, 1, '114.10.11.11', 1, '2023-10-17 04:58:51'),
(73, 1, '114.10.11.11', 1, '2023-10-17 04:59:59'),
(74, 1, '114.10.11.11', 1, '2023-10-17 05:09:04'),
(75, 1, '114.10.11.11', 1, '2023-10-17 05:11:14'),
(76, 10, '114.10.11.11', 1, '2023-10-17 05:12:36');

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
(28, 'Mie Goreng', '12000.00', 1);

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
(22, 1, '30000.00', '2023-10-11 14:17:03'),
(23, 1, '70000.00', '2023-10-17 05:04:28'),
(24, 1, '48000.00', '2023-10-17 05:07:36'),
(25, 1, '36000.00', '2023-10-17 05:07:51');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `user_id`, `order_id`, `item_name`, `item_price`, `quantity`, `created_at`) VALUES
(36, NULL, 22, 'Nasi Goreng', '10000.00', 3, '2023-10-11 14:17:03'),
(37, NULL, 23, 'Bakso', '10000.00', 7, '2023-10-17 05:04:28'),
(38, NULL, 24, 'Mie Goreng', '12000.00', 4, '2023-10-17 05:07:36'),
(39, NULL, 25, 'Mie Goreng', '12000.00', 3, '2023-10-17 05:07:51');

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
(2, '{\"keranjang\":[{\"nama\":\"Menu3\",\"harga\":8,\"quantity\":3,\"subtotal\":24},{\"nama\":\"Menu1\",\"harga\":10,\"quantity\":1,\"subtotal\":10}],\"totalHarga\":34}', '2023-10-02 03:04:56', 2);

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
(35, 1, 'Mie Goreng', 4, '48000.00', 24, '2023-10-17 05:07:36'),
(36, 1, 'Mie Goreng', 3, '36000.00', 25, '2023-10-17 05:07:51');

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
(1, 1, 'susu kental manis', 1, '10000.00', '2023-10-04 13:38:26');

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
(1, 'admin', 'admin@admin.com', '$2y$10$pJ3KTMKAUjVzzH9bk9Btf.YOukBS.HcpVxGmmYyY3rPh8O7WSx1Nu', 3, '232cdf91f965d69860bfb94754ebee20e5466b03a24c278185b08bdf90d50028', 1, NULL, NULL, '2023-09-05 02:02:00', 0),
(3, 'borok', 'akuntugas010102@gmail.com', '$2y$10$Q.2PgVTjQMqCcXL6dkWEneHZqiuDiHM.FNubpa06KF67xglHw7xRO', 2, '9a3ca592f4b8191491adcc610c53a70080925fddc81b4e43eb428b065453df27', 1, NULL, NULL, '2023-09-05 03:55:24', 0),
(8, 'ini rasa', 'ini.r4sa@gmail.com', '$2y$10$yepQdf1LCAdVdKuW9W97nOOBbVCUcGnRw6cEehEKk1YJhi2PdHEA6', 1, '2a9861bce30393ae186de05df634d9d16a2754f45bf11d63d0281eea030798c2', 1, NULL, NULL, '2023-09-11 03:47:10', 0),
(9, 'irfan', 'brokirgaa2@gmail.com', '$2y$10$Aguma0DP3JuO/08dK83EqOiECgQp8ztRiQSZp275UBWR3evziS5S6', 1, '16c32b11f49910cc3496c7f9c70f2819680e081ca5655cce752aaecf7e5568b1', 1, NULL, NULL, '2023-09-29 13:23:26', 0),
(10, 'users', 'finnch77@gmail.com', '$2y$10$zx4YsUch3OPg.ALATe.y9u4IB2VyxSxP2FteNXeK6Tr3rgvvyi0ey', 1, '6c71a224b1119932762da6377a79a086ff078da46d7bdf6c758ab22ce5df8c67', 0, NULL, NULL, '2023-10-09 23:48:02', 0),
(11, 'arifin', 'arifin.wahyuapril01@gmail.com', '$2y$10$ZYtVNm1LAgmxvPX316mIMOYnyXccBB3gJrLTrCsCujb6qhEnBNUPS', 1, 'd3c499683d32b25231623cae876660fb2c559dc1309acd4aa8bf7438cd1e6d44', 1, NULL, NULL, '2023-10-10 00:16:20', 0);

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
(1, 1, 'images/WhatsApp Image 2023-05-03 at 10.44.13.jpg', 'E Warung', 'Jl. Ngawi timur', '081233676908'),
(4, 10, 'images/JOHAN (2).jpg', 'E Warung', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activation_keys`
--
ALTER TABLE `activation_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `activation_key` (`activation_key`);

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
  ADD KEY `order_id` (`order_id`),
  ADD KEY `order_items_ibfk_2` (`user_id`);

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
-- AUTO_INCREMENT for table `activation_keys`
--
ALTER TABLE `activation_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `items_stock`
--
ALTER TABLE `items_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `riwayat_order`
--
ALTER TABLE `riwayat_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `riwayat_pembelian`
--
ALTER TABLE `riwayat_pembelian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `riwayat_penjualan`
--
ALTER TABLE `riwayat_penjualan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users_preferences` (`user_id`);

--
-- Constraints for table `users_preferences`
--
ALTER TABLE `users_preferences`
  ADD CONSTRAINT `users_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

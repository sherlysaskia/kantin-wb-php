-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2026 at 12:59 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kantinn_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','habis') DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `nama`, `harga`, `gambar`, `status`) VALUES
(1, 'Bakso kecil (bakso kecil 8)', 13000, 'menu_1767844762_Donna Jos Sia.jpg', 'tersedia'),
(2, 'Indomie Bakso (Bakso besar 1, kecil 4)', 18000, 'menu_1767844693_Mie Bakso🍜.jpg', 'tersedia'),
(3, 'indomie (rebus/goreng)', 6000, 'menu_1767844633_Some packs Indomie🇮🇩.jpg', 'tersedia'),
(4, 'Bakso Urat (bakso besar 1, kecil 4)', 13000, 'menu_1765465019_baso.jpeg', 'tersedia'),
(5, 'Mie Yamin', 11000, 'menu_1765465007_yamin.jpeg', 'tersedia'),
(6, 'Mie Ayam', 11000, 'menu_1765464986_miayam.jpeg', 'tersedia'),
(7, 'Mie Tek-Tek', 13000, 'menu_1765464967_tektek.jpeg', 'tersedia'),
(8, 'Air Mineral', 5000, 'menu_1765464950_air.jpeg', 'tersedia'),
(9, 'Jus Alpukat', 10000, 'menu_1765464939_alpukat.jpeg', 'tersedia'),
(10, 'Jus Mangga', 8000, 'menu_1765464928_mangga.jpeg', 'tersedia'),
(11, 'Jeruk Peras', 5000, 'menu_1765464913_jeruk.jpeg', 'tersedia'),
(17, 'Mie ayam Bakso urat (bakso besar 1)', 17000, 'menu_1767845078_Mie Ayam Bakso Super.jpg', 'tersedia'),
(18, 'Mie ayam Bakso (bakso kecil 2)', 14000, 'menu_1767845113_Mie Ayam+Bakso😍.jpg', 'tersedia'),
(19, 'Bakso besar (bakso besar 2)', 13000, 'menu_1767845362_Bakso Urat mas mono.jpg', 'tersedia'),
(20, 'Bakso keju (bakso keju 2, kecil 3)', 15000, 'menu_1767845432_Bakso Keju Isi Lezat Mudah Praktis - Resep _ ResepKoki.jpg', 'tersedia'),
(21, 'Jus Strawberry', 7000, 'menu_1767845730_Jus Strawberry.jpg', 'tersedia'),
(22, 'Jus Sirsak', 7000, 'menu_1767845750_JUS SIRSAK LITERAN 13000 KHUSUS INSTAN BANDUNG.jpg', 'tersedia'),
(23, 'Jus Buah Naga', 7000, 'menu_1767845772_Jus Naga.jpg', 'tersedia'),
(24, 'Jus Tomat', 7000, 'menu_1767845795_Tomato Juice ,33_8 fl oz.jpg', 'tersedia'),
(25, 'Jus Jambu', 7000, 'menu_1767845812_Jamaican Style Guava Juice_ Nutritious And Delicious.jpg', 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `catatan` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `total` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `tipe_pesanan` enum('Take Away','Dine In') NOT NULL,
  `nomor_meja` int(11) DEFAULT NULL,
  `metode_pesanan` enum('Take Away','Dine In') NOT NULL DEFAULT 'Take Away',
  `no_meja` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `nama_menu` varchar(200) NOT NULL,
  `harga` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL,
  `nama_pembeli` varchar(100) DEFAULT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `status` enum('Pending','Selesai') DEFAULT 'Pending',
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`) VALUES
(1, 'admin', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(150) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `nomor_wa` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `created_at`, `nomor_wa`) VALUES
(12, 'sabrina', '$2y$10$4GQ4xkR/JoLLWUKUfpqYN.uU6Ay7ObSEyLdNJG2zAW/Bxg2zabfvi', 'sabrina septiananda', '2026-01-08 21:16:10', '6281383830509');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

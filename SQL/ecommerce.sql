-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 22, 2026 at 10:33 AM
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
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(30) NOT NULL DEFAULT 'Pending',
  `order_status` varchar(30) NOT NULL DEFAULT 'Pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `card_last4` varchar(4) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `user_id`, `full_name`, `email`, `phone`, `address`, `city`, `postal_code`, `total_amount`, `payment_status`, `order_status`, `payment_method`, `card_last4`, `created_at`) VALUES
(2, '', 1, NULL, NULL, NULL, NULL, NULL, NULL, 55.00, 'Paid', 'Confirmed', 'Card', NULL, '2026-08-19 15:40:54'),
(4, 'ORD-TEST-001', 1, NULL, NULL, NULL, NULL, NULL, NULL, 5000.00, 'Paid', 'Confirmed', 'Card', NULL, '2026-08-19 16:55:49'),
(6, 'ORD-20260819185928-2AF92157', 1, NULL, NULL, NULL, NULL, NULL, NULL, 110.00, 'Paid', 'Delivered', 'Card', NULL, '2026-08-19 16:59:28'),
(11, 'ORD-20260819-210024-EFAA47', 1, NULL, NULL, NULL, NULL, NULL, NULL, 142.00, 'Pending', 'Pending', 'Cash on Delivery', NULL, '2026-08-19 19:00:24'),
(12, 'ORD-20260819-211653-81B08F', 1, NULL, NULL, NULL, NULL, NULL, NULL, 174.00, 'Pending', 'Pending', 'Cash on Delivery', NULL, '2026-08-19 19:16:53'),
(13, 'ORD-20260819-212757-DD3D91', 1, NULL, NULL, NULL, NULL, NULL, NULL, 55.00, 'Pending', 'Pending', 'Cash on Delivery', NULL, '2026-08-19 19:27:57'),
(14, 'ORD-20260819-213229-3EC51F', 1, 'Sadat Ali', 'ali@gmail.com', '03030824425', 'KAMALIA\r\nRavi town kamalia', 'Toba Tek Singh', '36350', 87.00, 'Pending', 'Pending', 'Cash on Delivery', '', '2026-08-19 19:32:29'),
(15, 'ORD-20260819-213325-902E57', 1, 'Sadat Ali', 'ali@gmail.com', '03030824425', 'KAMALIA\r\nRavi town kamalia', 'Toba Tek Singh', '36350', 110.00, 'Pending', 'Pending', 'Cash on Delivery', '', '2026-08-19 19:33:25'),
(16, 'ORD-20260819-213537-F5A567', 1, 'Sadat Ali', 'ali@gmail.com', '03030824425', 'KAMALIA\r\nRavi town kamalia', 'Toba Tek Singh', '36350', 87.00, 'Pending', 'Pending', 'Cash on Delivery', '', '2026-08-19 19:35:37');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`, `subtotal`, `created_at`) VALUES
(1, 11, 1, 'mdl', 55.00, 1, 55.00, '2026-08-19 19:00:24'),
(2, 11, 2, 'csc', 87.00, 1, 87.00, '2026-08-19 19:00:24'),
(3, 12, 2, 'csc', 87.00, 2, 174.00, '2026-08-19 19:16:53'),
(4, 13, 1, 'mdl', 55.00, 1, 55.00, '2026-08-19 19:27:57'),
(5, 14, 2, 'csc', 87.00, 1, 87.00, '2026-08-19 19:32:29'),
(6, 15, 1, 'mdl', 55.00, 2, 110.00, '2026-08-19 19:33:25'),
(7, 16, 2, 'csc', 87.00, 1, 87.00, '2026-08-19 19:35:37');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(2, 'csc', 'xczc', 87.00, 'product_1787163249_6a85f27155612.jpg', '2026-08-19 18:14:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `verification_code` varchar(6) DEFAULT NULL,
  `verification_expires` datetime DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `contact`, `city`, `address`, `verification_code`, `verification_expires`, `is_verified`) VALUES
(1, 'Ali Chaudhary', 'ali@gmail.com', '12345678', '03030824425', 'KAMALIA', 'MOHALLAH RAVI TOWN KAMALIA', NULL, NULL, 0),
(2, 'ali', 'ali89@gmail.com', '12345678', '03482095314', 'Toba Tek Singh', 'CHAK NO 517 GB DISTRICT TOBA TEK SINGH', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_items`
--

CREATE TABLE `users_items` (
  `id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `status` enum('Added to cart','Confirmed') NOT NULL DEFAULT 'Added to cart'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_items`
--

INSERT INTO `users_items` (`id`, `order_id`, `user_id`, `item_id`, `status`) VALUES
(24, NULL, 1, 2, ''),
(27, NULL, 1, 2, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_order_id` (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_items`
--
ALTER TABLE `users_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users_items`
--
ALTER TABLE `users_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_items`
--
ALTER TABLE `users_items`
  ADD CONSTRAINT `users_items_product_fk` FOREIGN KEY (`item_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_items_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

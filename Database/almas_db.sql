-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 07:48 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `almas_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin'),
(2, 'Testuser12', '123');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(2, 'Men Clothing'),
(3, 'Women Clothing'),
(4, 'Kids Wear'),
(5, 'Casual Wear'),
(6, 'Formal Wear'),
(7, 'Sportswear'),
(8, 'Ethnic Wear'),
(9, 'Winter Collection'),
(10, 'Summer Collection'),
(11, 'Accessories');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TotalPrice` decimal(10,2) NOT NULL,
  `DeliveryAddress` varchar(255) NOT NULL,
  `OrderStatus` enum('Pending','Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `TotalPrice`, `DeliveryAddress`, `OrderStatus`) VALUES
(1, 2, 8200.00, 'Multan Line two', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `OrderItemID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`OrderItemID`, `OrderID`, `ProductID`, `Quantity`) VALUES
(1, 1, 1, 1),
(2, 1, 20, 10);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Description` text NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `StockQuantity` int(11) NOT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `SizeGuide` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `Description`, `Price`, `StockQuantity`, `CategoryID`, `ImageURL`, `Timestamp`, `SizeGuide`) VALUES
(1, 'Men Casual Shirt', 'Comfortable casual shirt for men', 1200.00, 49, 2, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'S,M,L,XL'),
(2, 'Women Summer Dress', 'Light summer dress for women', 1500.00, 30, 3, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'S,M,L'),
(3, 'Kids T-Shirt', 'Cotton T-Shirt for kids', 800.00, 40, 4, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'XS,S,M'),
(4, 'Men Formal Shirt', 'Elegant formal shirt for office', 1800.00, 25, 5, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'M,L,XL'),
(5, 'Women Ethnic Kurti', 'Traditional ethnic wear', 2000.00, 20, 6, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'S,M,L,XL'),
(6, 'Men Hoodie', 'Warm and cozy hoodie', 2200.00, 15, 7, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'M,L,XL'),
(7, 'Women Jeans', 'Stylish denim jeans', 2500.00, 35, 8, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'S,M,L'),
(8, 'Kids Jacket', 'Winter jacket for kids', 1800.00, 20, 9, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'XS,S,M'),
(9, 'Men T-Shirt', 'Casual T-shirt for daily wear', 1000.00, 50, 2, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'M,L,XL'),
(10, 'Women Blouse', 'Elegant blouse for women', 1300.00, 25, 3, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'S,M,L'),
(11, 'Men Joggers', 'Comfortable sportswear joggers', 2000.00, 30, 7, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'M,L,XL'),
(12, 'Women Skirt', 'Stylish skirt for casual outings', 1600.00, 20, 8, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'S,M,L'),
(13, 'Kids Shorts', 'Summer shorts for kids', 900.00, 40, 4, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'XS,S,M'),
(14, 'Men Sweater', 'Warm sweater for winter', 2100.00, 15, 9, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'M,L,XL'),
(15, 'Women Jacket', 'Winter jacket for women', 3000.00, 10, 10, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'S,M,L'),
(16, 'Men Polo Shirt', 'Casual polo shirt for men', 1500.00, 40, 2, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'M,L,XL'),
(17, 'Women T-Shirt', 'Trendy T-shirt for women', 1200.00, 35, 3, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'S,M,L'),
(18, 'Kids Dress', 'Cute dress for kids', 1100.00, 25, 4, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'XS,S,M'),
(19, 'Men Coat', 'Elegant winter coat', 4000.00, 10, 9, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'M,L,XL'),
(20, 'Women Scarf', 'Fashionable scarf accessory', 700.00, 40, 11, '692fd7200df4b.jpg', '2025-12-03 06:23:57', 'One Size');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `ReviewID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Comment` text NOT NULL,
  `Rating` int(11) NOT NULL,
  `Image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`ReviewID`, `OrderID`, `UserID`, `Comment`, `Rating`, `Image`) VALUES
(1, 1, 2, 'Good', 3, '692fdb8762da44.60089731.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`) VALUES
(2, 'Nasir12', '$2y$10$DKcKcks0KmqwcWsD0B6QruWDxffYzQhVzqcPtxdyUNyjGa/93hq4S', 'nasiryt.827@gmail.com', '03176526827');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`OrderItemID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`ReviewID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `OrderItemID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `ReviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

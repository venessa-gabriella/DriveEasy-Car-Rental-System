-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2026 at 09:22 PM
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
-- Database: `car_rental_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `booking_status` enum('Booked','Completed','Cancelled') DEFAULT 'Booked',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `vehicle_id`, `start_date`, `end_date`, `total_amount`, `booking_status`, `created_at`) VALUES
(1, 2, 2, '2026-11-11', '2026-12-02', 88000.00, 'Booked', '2026-11-09 20:08:19'),
(2, 4, 1, '2026-11-10', '2026-11-20', 27500.00, 'Booked', '2026-11-09 20:09:50'),
(3, 1, 3, '2026-11-09', '2026-12-13', 105000.00, 'Booked', '2026-11-09 20:14:10'),
(4, 3, 4, '2026-11-09', '2026-12-09', 310000.00, 'Booked', '2026-11-09 20:16:38'),
(5, 5, 6, '2026-11-09', '2026-12-12', 221000.00, 'Booked', '2026-11-09 20:21:37');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `full_name`, `phone`, `email`, `created_at`) VALUES
(1, 'Purity Nyambura', '0712098356', 'purity@gmail.com', '2026-11-09 20:03:47'),
(2, 'Sonia daniel', '0792673098', 'soniad@gmail.com', '2026-11-09 20:04:37'),
(3, 'Sonia daniel', '0792673098', 'soniad@gmail.com', '2026-11-09 20:04:46'),
(4, 'Lucianna villa', '0793678012', 'lucy@gmail.com', '2026-11-09 20:05:46'),
(5, 'Britiny litiny', '0785217094', 'lityny@gmail.com', '2026-11-09 20:20:34');

-- --------------------------------------------------------

--
-- Table structure for table `rental_payments`
--

CREATE TABLE `rental_payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('Cash','M-Pesa','Card','Bank Transfer') NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rental_payments`
--

INSERT INTO `rental_payments` (`id`, `booking_id`, `amount`, `payment_method`, `payment_date`) VALUES
(1, 3, 100000.00, 'Card', '2026-11-09 20:14:43'),
(2, 1, 67000.00, 'Bank Transfer', '2026-11-09 20:14:58'),
(3, 2, 43.00, 'M-Pesa', '2026-11-09 20:15:22');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `registration_no` varchar(30) NOT NULL,
  `make_model` varchar(100) NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `status` enum('Available','Unavailable') DEFAULT 'Available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `registration_no`, `make_model`, `daily_rate`, `status`) VALUES
(1, 'KDA 123A', 'Toyota Vitz', 2500.00, 'Available'),
(2, 'KCB 456B', 'Toyota Axio', 4000.00, 'Available'),
(3, 'KDC 789C', 'Nissan Note', 3000.00, 'Available'),
(4, 'KDR A00B', 'Mazda demio', 10000.00, 'Available'),
(5, 'KED 674S', 'CX-6', 20000.00, 'Available'),
(6, 'KDR 438J', 'AXELA', 6500.00, 'Available'),
(7, 'Harrier', 'Toyota 678n', 25000.00, 'Available');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rental_payments`
--
ALTER TABLE `rental_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_no` (`registration_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rental_payments`
--
ALTER TABLE `rental_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`);

--
-- Constraints for table `rental_payments`
--
ALTER TABLE `rental_payments`
  ADD CONSTRAINT `rental_payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

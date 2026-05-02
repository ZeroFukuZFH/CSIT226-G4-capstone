-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 02, 2026 at 06:03 AM
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
-- Database: `tranquilitybase_schema`
--

-- --------------------------------------------------------

--
-- Table structure for table `Amusement`
--

CREATE TABLE `Amusement` (
  `AmusementID` int(11) NOT NULL,
  `bookingItemId` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `AmusementType` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Automotives`
--

CREATE TABLE `Automotives` (
  `VehicleID` int(11) NOT NULL,
  `bookingItemId` int(11) NOT NULL,
  `VehicleType` varchar(100) DEFAULT NULL,
  `Model` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Booking`
--

CREATE TABLE `Booking` (
  `bookingId` int(11) NOT NULL,
  `guestId` int(11) NOT NULL,
  `bookingType` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `BookingItem`
--

CREATE TABLE `BookingItem` (
  `bookingItemId` int(11) NOT NULL,
  `bookingId` int(11) NOT NULL,
  `ItemType` varchar(100) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `isAvailable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Consumables`
--

CREATE TABLE `Consumables` (
  `ConsumableID` int(11) NOT NULL,
  `bookingItemId` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Category` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Guest`
--

CREATE TABLE `Guest` (
  `guestId` int(11) NOT NULL,
  `accessLevel` varchar(50) DEFAULT NULL,
  `password` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Room`
--

CREATE TABLE `Room` (
  `RoomID` int(11) NOT NULL,
  `bookingItemId` int(11) NOT NULL,
  `RoomType` varchar(100) DEFAULT NULL,
  `Floor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Amusement`
--
ALTER TABLE `Amusement`
  ADD PRIMARY KEY (`AmusementID`),
  ADD UNIQUE KEY `bookingItemId` (`bookingItemId`);

--
-- Indexes for table `Automotives`
--
ALTER TABLE `Automotives`
  ADD PRIMARY KEY (`VehicleID`),
  ADD UNIQUE KEY `bookingItemId` (`bookingItemId`);

--
-- Indexes for table `Booking`
--
ALTER TABLE `Booking`
  ADD PRIMARY KEY (`bookingId`),
  ADD KEY `guestId` (`guestId`);

--
-- Indexes for table `BookingItem`
--
ALTER TABLE `BookingItem`
  ADD PRIMARY KEY (`bookingItemId`),
  ADD KEY `bookingId` (`bookingId`);

--
-- Indexes for table `Consumables`
--
ALTER TABLE `Consumables`
  ADD PRIMARY KEY (`ConsumableID`),
  ADD UNIQUE KEY `bookingItemId` (`bookingItemId`);

--
-- Indexes for table `Guest`
--
ALTER TABLE `Guest`
  ADD PRIMARY KEY (`guestId`);

--
-- Indexes for table `Room`
--
ALTER TABLE `Room`
  ADD PRIMARY KEY (`RoomID`),
  ADD UNIQUE KEY `bookingItemId` (`bookingItemId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Amusement`
--
ALTER TABLE `Amusement`
  MODIFY `AmusementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Automotives`
--
ALTER TABLE `Automotives`
  MODIFY `VehicleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Booking`
--
ALTER TABLE `Booking`
  MODIFY `bookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `BookingItem`
--
ALTER TABLE `BookingItem`
  MODIFY `bookingItemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `Consumables`
--
ALTER TABLE `Consumables`
  MODIFY `ConsumableID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Guest`
--
ALTER TABLE `Guest`
  MODIFY `guestId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Room`
--
ALTER TABLE `Room`
  MODIFY `RoomID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Amusement`
--
ALTER TABLE `Amusement`
  ADD CONSTRAINT `amusement_ibfk_1` FOREIGN KEY (`bookingItemId`) REFERENCES `BookingItem` (`bookingItemId`) ON DELETE CASCADE;

--
-- Constraints for table `Automotives`
--
ALTER TABLE `Automotives`
  ADD CONSTRAINT `automotives_ibfk_1` FOREIGN KEY (`bookingItemId`) REFERENCES `BookingItem` (`bookingItemId`) ON DELETE CASCADE;

--
-- Constraints for table `Booking`
--
ALTER TABLE `Booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`guestId`) REFERENCES `Guest` (`guestId`) ON DELETE CASCADE;

--
-- Constraints for table `BookingItem`
--
ALTER TABLE `BookingItem`
  ADD CONSTRAINT `bookingitem_ibfk_1` FOREIGN KEY (`bookingId`) REFERENCES `Booking` (`bookingId`) ON DELETE CASCADE;

--
-- Constraints for table `Consumables`
--
ALTER TABLE `Consumables`
  ADD CONSTRAINT `consumables_ibfk_1` FOREIGN KEY (`bookingItemId`) REFERENCES `BookingItem` (`bookingItemId`) ON DELETE CASCADE;

--
-- Constraints for table `Room`
--
ALTER TABLE `Room`
  ADD CONSTRAINT `room_ibfk_1` FOREIGN KEY (`bookingItemId`) REFERENCES `BookingItem` (`bookingItemId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

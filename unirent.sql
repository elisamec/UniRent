-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 26, 2024 at 02:19 PM
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
-- Database: `unirent`
--

-- --------------------------------------------------------

--
-- Table structure for table `accommodation`
--

CREATE TABLE `accommodation` (
  `id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `address` int(11) NOT NULL,
  `price` double NOT NULL,
  `start` date NOT NULL,
  `description` varchar(500) NOT NULL,
  `deposit` double DEFAULT NULL,
  `visitAvaiability` int(11) NOT NULL,
  `visitDuration` time NOT NULL,
  `man` tinyint(1) NOT NULL,
  `woman` tinyint(1) NOT NULL,
  `pets` tinyint(1) NOT NULL,
  `smokers` tinyint(1) NOT NULL,
  `owner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accommodationreview`
--

CREATE TABLE `accommodationreview` (
  `idReview` int(11) NOT NULL,
  `idAccommodation` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `streetName` varchar(50) NOT NULL,
  `houseNumber` int(11) NOT NULL,
  `appartmentNumber` int(11) DEFAULT NULL,
  `postalCode` int(11) NOT NULL,
  `city` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE `contract` (
  `reservationId` int(11) NOT NULL,
  `status` enum('onGoing','future','finshed','') NOT NULL,
  `paymentDate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cardNumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `creditcard`
--

CREATE TABLE `creditcard` (
  `number` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `expiry` varchar(5) NOT NULL,
  `cvv` int(3) NOT NULL,
  `idStudent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `day`
--

CREATE TABLE `day` (
  `id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `picture` int(11) DEFAULT NULL,
  `email` varchar(40) NOT NULL,
  `phoneNumber` int(15) NOT NULL,
  `iban` varchar(27) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ownerreview`
--

CREATE TABLE `ownerreview` (
  `idOwner` int(11) NOT NULL,
  `idReview` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `photo` blob NOT NULL,
  `relativeTo` enum('review','accommodation','other') NOT NULL,
  `idAccommodation` int(11) DEFAULT NULL,
  `idReview` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `fromDate` date NOT NULL,
  `toDate` date NOT NULL,
  `made` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `statusAccept` tinyint(1) NOT NULL,
  `accommodationId` int(11) NOT NULL,
  `idStudent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `title` varchar(40) NOT NULL,
  `valutation` int(1) NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `name` varchar(20) NOT NULL,
  `surname` varchar(20) NOT NULL,
  `picture` int(11) DEFAULT NULL,
  `universityMail` varchar(40) NOT NULL,
  `courseDuration` int(1) NOT NULL,
  `immatricolationYear` int(4) NOT NULL,
  `birthDate` date NOT NULL,
  `sex` varchar(1) NOT NULL,
  `smoker` tinyint(1) NOT NULL,
  `animals` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studentreview`
--

CREATE TABLE `studentreview` (
  `idStudent` int(11) NOT NULL,
  `idReview` int(11) NOT NULL,
  `authorType` enum('student','owner') NOT NULL,
  `authorStudent` int(11) DEFAULT NULL,
  `authorOwner` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supportrequest`
--

CREATE TABLE `supportrequest` (
  `id` int(11) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `topic` enum('registration','appUse','bug','') NOT NULL,
  `idStudent` int(11) DEFAULT NULL,
  `ownerId` int(11) DEFAULT NULL,
  `authorType` enum('student','owner','','') NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `time`
--

CREATE TABLE `time` (
  `id` int(11) NOT NULL,
  `hour` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visit`
--

CREATE TABLE `visit` (
  `id` int(11) NOT NULL,
  `day` datetime NOT NULL,
  `studentId` int(11) NOT NULL,
  `accommodationId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accommodation`
--
ALTER TABLE `accommodation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `address` (`address`),
  ADD KEY `visitAvaiability` (`visitAvaiability`),
  ADD KEY `accommodation_ibfk_4` (`owner`);

--
-- Indexes for table `accommodationreview`
--
ALTER TABLE `accommodationreview`
  ADD KEY `idAccommodation` (`idAccommodation`),
  ADD KEY `idAuthor` (`idAuthor`),
  ADD KEY `idReview` (`idReview`);

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Indexes for table `contract`
--
ALTER TABLE `contract`
  ADD KEY `reservationId` (`reservationId`),
  ADD KEY `cardNumber` (`cardNumber`);

--
-- Indexes for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD PRIMARY KEY (`number`),
  ADD KEY `studentId` (`idStudent`);

--
-- Indexes for table `day`
--
ALTER TABLE `day`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `day` (`day`),
  ADD KEY `time` (`time`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phoneNumber` (`phoneNumber`),
  ADD UNIQUE KEY `iban` (`iban`),
  ADD KEY `picture` (`picture`);

--
-- Indexes for table `ownerreview`
--
ALTER TABLE `ownerreview`
  ADD KEY `idAuthor` (`idAuthor`),
  ADD KEY `idOwner` (`idOwner`),
  ADD KEY `idReview` (`idReview`);

--
-- Indexes for table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idAccommodation` (`idAccommodation`),
  ADD KEY `idReview` (`idReview`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accommodationId` (`accommodationId`),
  ADD KEY `studentId` (`idStudent`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `universityMail` (`universityMail`),
  ADD KEY `picture` (`picture`);

--
-- Indexes for table `studentreview`
--
ALTER TABLE `studentreview`
  ADD KEY `idStudent` (`idStudent`),
  ADD KEY `idReview` (`idReview`),
  ADD KEY `authorStudent` (`authorStudent`),
  ADD KEY `authorOwner` (`authorOwner`);

--
-- Indexes for table `supportrequest`
--
ALTER TABLE `supportrequest`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ownerId` (`ownerId`),
  ADD KEY `studentId` (`idStudent`);

--
-- Indexes for table `time`
--
ALTER TABLE `time`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `hour` (`hour`);

--
-- Indexes for table `visit`
--
ALTER TABLE `visit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `studentId` (`studentId`),
  ADD KEY `accommodationId` (`accommodationId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accommodation`
--
ALTER TABLE `accommodation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `administrator`
--
ALTER TABLE `administrator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `day`
--
ALTER TABLE `day`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `owner`
--
ALTER TABLE `owner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supportrequest`
--
ALTER TABLE `supportrequest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `time`
--
ALTER TABLE `time`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visit`
--
ALTER TABLE `visit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accommodation`
--
ALTER TABLE `accommodation`
  ADD CONSTRAINT `accommodation_ibfk_2` FOREIGN KEY (`address`) REFERENCES `address` (`id`),
  ADD CONSTRAINT `accommodation_ibfk_3` FOREIGN KEY (`visitAvaiability`) REFERENCES `day` (`id`),
  ADD CONSTRAINT `accommodation_ibfk_4` FOREIGN KEY (`owner`) REFERENCES `owner` (`id`);

--
-- Constraints for table `accommodationreview`
--
ALTER TABLE `accommodationreview`
  ADD CONSTRAINT `accommodationreview_ibfk_1` FOREIGN KEY (`idAccommodation`) REFERENCES `accommodation` (`id`),
  ADD CONSTRAINT `accommodationreview_ibfk_2` FOREIGN KEY (`idAuthor`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `accommodationreview_ibfk_3` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`);

--
-- Constraints for table `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `contract_ibfk_1` FOREIGN KEY (`reservationId`) REFERENCES `reservation` (`id`),
  ADD CONSTRAINT `contract_ibfk_2` FOREIGN KEY (`cardNumber`) REFERENCES `creditcard` (`number`);

--
-- Constraints for table `creditcard`
--
ALTER TABLE `creditcard`
  ADD CONSTRAINT `creditcard_ibfk_1` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`);

--
-- Constraints for table `day`
--
ALTER TABLE `day`
  ADD CONSTRAINT `day_ibfk_1` FOREIGN KEY (`time`) REFERENCES `time` (`id`);

--
-- Constraints for table `owner`
--
ALTER TABLE `owner`
  ADD CONSTRAINT `owner_ibfk_1` FOREIGN KEY (`picture`) REFERENCES `photo` (`id`);

--
-- Constraints for table `ownerreview`
--
ALTER TABLE `ownerreview`
  ADD CONSTRAINT `ownerreview_ibfk_1` FOREIGN KEY (`idAuthor`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `ownerreview_ibfk_2` FOREIGN KEY (`idOwner`) REFERENCES `owner` (`id`),
  ADD CONSTRAINT `ownerreview_ibfk_3` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`);

--
-- Constraints for table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`idAccommodation`) REFERENCES `accommodation` (`id`),
  ADD CONSTRAINT `photo_ibfk_2` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`);

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`accommodationId`) REFERENCES `accommodation` (`id`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`picture`) REFERENCES `photo` (`id`);

--
-- Constraints for table `studentreview`
--
ALTER TABLE `studentreview`
  ADD CONSTRAINT `studentreview_ibfk_1` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `studentreview_ibfk_2` FOREIGN KEY (`idReview`) REFERENCES `review` (`id`),
  ADD CONSTRAINT `studentreview_ibfk_3` FOREIGN KEY (`authorStudent`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `studentreview_ibfk_4` FOREIGN KEY (`authorOwner`) REFERENCES `owner` (`id`);

--
-- Constraints for table `supportrequest`
--
ALTER TABLE `supportrequest`
  ADD CONSTRAINT `supportrequest_ibfk_1` FOREIGN KEY (`ownerId`) REFERENCES `owner` (`id`),
  ADD CONSTRAINT `supportrequest_ibfk_2` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`);

--
-- Constraints for table `visit`
--
ALTER TABLE `visit`
  ADD CONSTRAINT `visit_ibfk_1` FOREIGN KEY (`studentId`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `visit_ibfk_2` FOREIGN KEY (`accommodationId`) REFERENCES `accommodation` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

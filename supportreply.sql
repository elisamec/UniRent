-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Lug 31, 2024 alle 19:51
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

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
-- Struttura della tabella `supportreply`
--

CREATE TABLE `supportreply` (
  `id` int(11) NOT NULL,
  `description` varchar(500) NOT NULL,
  `idSupportRequest` int(11) NOT NULL,
  `idStudent` int(11) DEFAULT NULL,
  `idOwner` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `supportreply`
--
ALTER TABLE `supportreply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supportRequest` (`idSupportRequest`),
  ADD KEY `idStudent` (`idStudent`),
  ADD KEY `idOwner` (`idOwner`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `supportreply`
--
ALTER TABLE `supportreply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `supportreply`
--
ALTER TABLE `supportreply`
  ADD CONSTRAINT `supportRequest` FOREIGN KEY (`idSupportRequest`) REFERENCES `supportrequest` (`id`),
  ADD CONSTRAINT `supportreply_ibfk_1` FOREIGN KEY (`idStudent`) REFERENCES `student` (`id`),
  ADD CONSTRAINT `supportreply_ibfk_2` FOREIGN KEY (`idOwner`) REFERENCES `owner` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

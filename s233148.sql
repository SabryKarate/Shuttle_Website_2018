-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Creato il: Giu 14, 2018 alle 12:10
-- Versione del server: 10.1.13-MariaDB
-- Versione PHP: 5.5.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s233148`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `address_table`
--

DROP TABLE IF EXISTS `address_table`;
CREATE TABLE `address_table` (
  `id` int(11) NOT NULL,
  `address` varchar(50) DEFAULT NULL,
  `total` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `address_table`
--

INSERT INTO `address_table` (`id`, `address`, `total`) VALUES
(1, 'AL', 1),
(2, 'BB', 2),
(3, 'DD', 2),
(4, 'EE', 0),
(5, 'FF', 4),
(6, 'KK', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `departure_arrival`
--

DROP TABLE IF EXISTS `departure_arrival`;
CREATE TABLE `departure_arrival` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `num_people` int(20) DEFAULT NULL,
  `departure` varchar(50) DEFAULT NULL,
  `arrival` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `departure_arrival`
--

INSERT INTO `departure_arrival` (`id`, `email`, `num_people`, `departure`, `arrival`) VALUES
(1, 'u1@p.it', 4, 'FF', 'KK'),
(2, 'u2@p.it', 1, 'BB', 'EE'),
(3, 'u3@p.it', 1, 'DD', 'EE'),
(4, 'u4@p.it', 1, 'AL', 'DD');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'u1@p.it', 'b78f576611ec06f96af3ca654c22172a5d746c40'),
(2, 'u2@p.it', 'c5fd961c9f737a955a308050062e7a2c34ee67c3'),
(3, 'u3@p.it', 'e4fbe62d887b8cdee986e6be781203d8d938bbd5'),
(4, 'u4@p.it', '1b9645e71bb4d1ce9c48520b78a3d94f3f9a6a62');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `address_table`
--
ALTER TABLE `address_table`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `departure_arrival`
--
ALTER TABLE `departure_arrival`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `address_table`
--
ALTER TABLE `address_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT per la tabella `departure_arrival`
--
ALTER TABLE `departure_arrival`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mar 18, 2025 alle 13:44
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
-- Database: `ticket_manager`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `cat_tickets`
--

CREATE TABLE `cat_tickets` (
  `id` bigint(20) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `cat_tickets`
--

INSERT INTO `cat_tickets` (`id`, `tipo`, `descrizione`) VALUES
(1, 'PROB. MECCANICO', 'Segnala una problematica con la parte meccanica della macchina.'),
(2, 'PROB. ELETTRICO/ELETTRONICO', 'Segnala una problematica con la parte elettrica della macchina.'),
(3, 'PROB. APP/INTERFACCIA', "Segnala una problematica nell'app o nella interfaccia web."),
(4, 'PROB. ACCOUNT/LOGIN', 'Segnala un problema col tuo account o di login.'),
(5, 'PROB. AMMINISTRATIVO', 'Segnala un problema di carattere amministrativo e/o fiscale.'),
(6, 'ALTRO', 'Segnala un problema di altro tipo o di cui non conosci la natura.');

-- --------------------------------------------------------

--
-- Struttura della tabella `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `ticket_id` bigint(20) NOT NULL,
  `mess_text` text NOT NULL,
  `data_ora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `cat_id` bigint(20) NOT NULL,
  `data_ora` timestamp NOT NULL DEFAULT current_timestamp(),
  `descrizione` text NOT NULL,
  `stato` enum('open','pending','closed') NOT NULL DEFAULT 'open',
  `admin_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tickets`
--

INSERT INTO `tickets` (`id`, `user_id`, `cat_id`, `data_ora`, `descrizione`, `stato`, `admin_id`) VALUES
(18, 1, 4, '2025-03-17 10:03:03', 'Prova.', 'pending', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `ruolo` enum('superadmin','admin','cliente') NOT NULL DEFAULT 'cliente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `nome`, `cognome`, `email`, `password_hash`, `ruolo`) VALUES
(1, 'Mario', 'Rossi', 'superadmin@example.com', '$2y$10$mn1jd9c1D3R.5CoIO9Mc7uUmkXwbQQz/T51tkd8Q9Eyz7PiYS3DX.', 'superadmin'),
(6, 'Paolino', 'Paperino', 'paperino@gmail.com', '$2y$10$zw/fYaIFcGh5ukGtl0TV4eFjnA.ZO/4lSp4KG1ZRBHYs4C9yJQiB2', 'cliente');

-- --------------------------------------------------------

--
-- Struttura della tabella `user_ticketcategory`
--

CREATE TABLE `user_ticketcategory` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `ticketcategory_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `user_ticketcategory`
--

INSERT INTO `user_ticketcategory` (`id`, `user_id`, `ticketcategory_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `cat_tickets`
--
ALTER TABLE `cat_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indici per le tabelle `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cat_id` (`cat_id`),
  ADD KEY `admin_id_fk` (`admin_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indici per le tabelle `user_ticketcategory`
--
ALTER TABLE `user_ticketcategory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ticketcategory_id` (`ticketcategory_id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `cat_tickets`
--
ALTER TABLE `cat_tickets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT per la tabella `user_ticketcategory`
--
ALTER TABLE `user_ticketcategory`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `admin_id_fk` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`cat_id`) REFERENCES `cat_tickets` (`id`) ON DELETE CASCADE;

--
-- Limiti per la tabella `user_ticketcategory`
--
ALTER TABLE `user_ticketcategory`
  ADD CONSTRAINT `user_ticketcategory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_ticketcategory_ibfk_2` FOREIGN KEY (`ticketcategory_id`) REFERENCES `cat_tickets` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

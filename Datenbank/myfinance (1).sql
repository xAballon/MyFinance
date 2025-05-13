-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 13. Mai 2025 um 03:43
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `myfinance`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `einkommensregeln`
--

CREATE TABLE `einkommensregeln` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `bezeichnung` varchar(128) NOT NULL,
  `betrag` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `einkommensregeln`
--

INSERT INTO `einkommensregeln` (`id`, `uid`, `bezeichnung`, `betrag`) VALUES
(8, 5, 'regel 2', 2250.00);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `einkommensverteilung`
--

CREATE TABLE `einkommensverteilung` (
  `id` int(11) NOT NULL,
  `regel_id` int(11) NOT NULL,
  `konto_id` int(11) NOT NULL,
  `typ` enum('betrag','prozent','','') NOT NULL,
  `wert` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `einkommensverteilung`
--

INSERT INTO `einkommensverteilung` (`id`, `regel_id`, `konto_id`, `typ`, `wert`) VALUES
(31, 8, 3, '', 1000.00),
(32, 8, 4, '', 250.00),
(33, 8, 5, '', 350.00),
(34, 8, 6, '', 87.50),
(35, 8, 7, 'prozent', 25.00);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `konto`
--

CREATE TABLE `konto` (
  `kid` int(11) NOT NULL,
  `knr` int(3) UNSIGNED ZEROFILL NOT NULL,
  `bezeichnung` varchar(150) NOT NULL,
  `kontostand` decimal(10,2) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `konto`
--

INSERT INTO `konto` (`kid`, `knr`, `bezeichnung`, `kontostand`, `uid`) VALUES
(1, 000, 'Einnahmen', 45650.50, 5),
(2, 999, 'Ausgaben', 213.00, 5),
(3, 100, 'Wohnen', 33007.00, 5),
(4, 101, 'Lebensmittel', 8154.00, 5),
(5, 102, 'Freizeit', 202888.00, 5),
(6, 103, 'Gesundheit', 1062.50, 5),
(7, 104, 'Sparen', 3025.00, 5),
(8, 000, 'Einnahmen', 0.00, 6),
(9, 999, 'Ausgaben', 0.00, 6),
(10, 100, 'Wohnen', 0.00, 6),
(11, 101, 'Lebensmittel', 0.00, 6),
(12, 102, 'Freizeit', 0.00, 6),
(13, 103, 'Gesundheit', 0.00, 6),
(14, 104, 'Sparen', 0.00, 6),
(15, 000, 'Einnahmen', 0.00, 1),
(16, 999, 'Ausgaben', 0.00, 1),
(17, 100, 'Wohnen', 0.00, 1),
(18, 101, 'Lebensmittel', 0.00, 1),
(19, 102, 'Freizeit', 0.00, 1),
(20, 103, 'Gesundheit', 0.00, 1),
(21, 104, 'Sparen', 0.00, 1),
(22, 000, 'Einnahmen', 2000.00, 7),
(23, 999, 'Ausgaben', 0.00, 7),
(24, 100, 'Wohnen', 1000.00, 7),
(25, 101, 'Lebensmittel', 200.00, 7),
(26, 102, 'Freizeit', 200.00, 7),
(27, 103, 'Gesundheit', 200.00, 7),
(28, 104, 'Sparen', 400.00, 7);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `transaktionen`
--

CREATE TABLE `transaktionen` (
  `tid` int(11) NOT NULL,
  `betrag` decimal(10,2) NOT NULL,
  `tnr` int(12) NOT NULL COMMENT 'Transaktionsnummer',
  `kommentar` varchar(255) DEFAULT NULL,
  `quelle` int(11) NOT NULL,
  `ziel` int(11) NOT NULL,
  `zeit` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `transaktionen`
--

INSERT INTO `transaktionen` (`tid`, `betrag`, `tnr`, `kommentar`, `quelle`, `ziel`, `zeit`, `uid`) VALUES
(6, 0.00, 1, 'Lebensmittel Mai 25', 2, 3, '2025-04-22 00:35:41', 5),
(7, 500.00, 2, 'Geburtstagsgeld - Sparen auf neuen PC', 2, 5, '2025-04-22 00:36:42', 5),
(8, 900.00, 1, 'Geschenk', 16, 21, '2025-04-22 00:39:09', 1),
(9, 100.00, 2, 'Kühlschrank-Budget-Zuschuss April 2025', 16, 18, '2025-04-22 00:39:59', 1),
(10, 200.00, 3, 'Neuer Monitor', 16, 16, '2025-04-22 00:45:29', 1),
(11, 900.00, 4, 'Hätte auf rot setzen sollen :(', 16, 16, '2025-04-22 00:46:22', 1),
(12, 2000.00, 3, 'Geburtstagsgeld', 1, 7, '2025-05-11 11:29:36', 5),
(13, 250.00, 4, 'Ausflug', 7, 5, '2025-05-11 11:30:10', 5),
(14, 213.00, 5, 'Ausflug', 5, 2, '2025-05-11 11:30:37', 5),
(16, 1400.00, 6, 'Einkommen 05_25', 1, 3, '2025-05-12 19:48:57', 5),
(17, 300.00, 6, 'Einkommen 05_25', 1, 4, '2025-05-12 19:48:57', 5),
(18, 300.00, 6, 'Einkommen 05_25', 1, 6, '2025-05-12 19:48:57', 5),
(19, 250.00, 6, 'Einkommen 05_25', 1, 7, '2025-05-12 19:48:57', 5),
(20, 1400.00, 7, 'Einkommen 05_25', 1, 3, '2025-05-12 19:51:46', 5),
(21, 300.00, 8, 'Einkommen 05_25', 1, 4, '2025-05-12 19:51:46', 5),
(22, 300.00, 9, 'Einkommen 05_25', 1, 6, '2025-05-12 19:51:46', 5),
(23, 250.00, 10, 'Einkommen 05_25', 1, 7, '2025-05-12 19:51:46', 5),
(24, 800.00, 11, 'Einkommen 06_25', 1, 3, '2025-05-12 19:52:32', 5),
(25, 200.00, 12, 'Einkommen 06_25', 1, 5, '2025-05-12 19:52:32', 5),
(36, 1000.00, 1, 'Einkommen 05_25', 22, 24, '2025-05-12 20:39:40', 7),
(37, 200.00, 2, 'Einkommen 05_25', 22, 25, '2025-05-12 20:39:40', 7),
(38, 200.00, 3, 'Einkommen 05_25', 22, 26, '2025-05-12 20:39:40', 7),
(39, 200.00, 4, 'Einkommen 05_25', 22, 27, '2025-05-12 20:39:40', 7),
(40, 400.00, 5, 'Einkommen 05_25', 22, 28, '2025-05-12 20:39:40', 7),
(41, 1.00, 13, '', 1, 3, '2025-05-12 23:04:48', 5),
(42, 1.00, 14, '', 1, 3, '2025-05-12 23:13:56', 5),
(43, 1.00, 15, '', 1, 3, '2025-05-12 23:14:54', 5),
(44, 1.00, 16, '', 1, 3, '2025-05-12 23:18:30', 5),
(45, 1.00, 17, '', 1, 3, '2025-05-12 23:19:40', 5),
(46, 1.00, 18, '', 1, 3, '2025-05-12 23:33:21', 5),
(47, 1.00, 19, '', 1, 3, '2025-05-12 23:40:39', 5),
(48, 1000.00, 20, '', 1, 3, '2025-05-12 23:58:23', 5),
(49, 250.00, 21, '', 1, 4, '2025-05-12 23:58:23', 5),
(50, 350.00, 22, '', 1, 5, '2025-05-12 23:58:23', 5),
(51, 87.50, 23, '', 1, 6, '2025-05-12 23:58:23', 5),
(52, 1000.00, 24, '', 1, 3, '2025-05-13 00:21:23', 5),
(53, 250.00, 25, '', 1, 4, '2025-05-13 00:21:23', 5),
(54, 350.00, 26, '', 1, 5, '2025-05-13 00:21:23', 5),
(55, 87.50, 27, '', 1, 6, '2025-05-13 00:21:23', 5),
(56, 562.50, 28, '', 1, 7, '2025-05-13 00:21:23', 5),
(57, 1000.00, 29, '', 1, 3, '2025-05-13 00:26:29', 5),
(58, 250.00, 30, '', 1, 4, '2025-05-13 00:26:29', 5),
(59, 350.00, 31, '', 1, 5, '2025-05-13 00:26:29', 5),
(60, 87.50, 32, '', 1, 6, '2025-05-13 00:26:29', 5),
(61, 562.50, 33, '', 1, 7, '2025-05-13 00:26:29', 5),
(62, 50.00, 34, '', 1, 7, '2025-05-13 00:27:30', 5),
(63, 200.00, 35, '', 1, 5, '2025-05-13 00:27:59', 5),
(64, 200.00, 36, '', 1, 6, '2025-05-13 00:27:59', 5),
(65, 200.00, 37, '', 1, 5, '2025-05-13 00:29:36', 5),
(66, 200.00, 38, '', 1, 6, '2025-05-13 00:29:36', 5),
(67, 200.00, 39, '', 1, 5, '2025-05-13 00:30:13', 5),
(68, 200.00, 40, '', 1, 6, '2025-05-13 00:30:13', 5),
(69, 200.00, 41, '', 1, 5, '2025-05-13 00:30:14', 5),
(70, 200.00, 42, '', 1, 6, '2025-05-13 00:30:14', 5),
(71, 200.00, 43, '', 1, 3, '2025-05-13 00:32:14', 5),
(72, 1000.00, 44, '', 1, 4, '2025-05-13 00:32:14', 5),
(73, 200.00, 45, '', 1, 3, '2025-05-13 00:32:18', 5),
(74, 1000.00, 46, '', 1, 4, '2025-05-13 00:32:18', 5),
(75, 1000.00, 47, '', 1, 3, '2025-05-13 00:32:28', 5),
(76, 300.00, 48, '', 1, 4, '2025-05-13 00:32:28', 5),
(77, 1000.00, 49, '', 1, 3, '2025-05-13 00:32:53', 5),
(78, 300.00, 50, '', 1, 4, '2025-05-13 00:32:53', 5),
(79, 1000.00, 51, '', 1, 3, '2025-05-13 00:32:54', 5),
(80, 300.00, 52, '', 1, 4, '2025-05-13 00:32:54', 5),
(81, 900.00, 53, '', 1, 3, '2025-05-13 00:33:10', 5),
(82, 900.00, 54, '', 1, 4, '2025-05-13 00:33:10', 5),
(83, 900.00, 55, '', 1, 3, '2025-05-13 00:34:59', 5),
(84, 600.00, 56, '', 1, 4, '2025-05-13 00:34:59', 5),
(85, 900.00, 57, '', 1, 3, '2025-05-13 00:39:22', 5),
(86, 600.00, 58, '', 1, 4, '2025-05-13 00:39:22', 5),
(87, 500.00, 59, '', 1, 3, '2025-05-13 00:39:30', 5),
(88, 600.00, 60, '', 1, 3, '2025-05-13 00:39:42', 5),
(89, 600.00, 61, '', 1, 4, '2025-05-13 00:39:42', 5),
(90, 600.00, 62, '', 1, 3, '2025-05-13 00:42:45', 5),
(91, 600.00, 63, '', 1, 4, '2025-05-13 00:42:45', 5),
(92, 600.00, 64, '', 1, 3, '2025-05-13 00:43:28', 5),
(93, 600.00, 65, '', 1, 4, '2025-05-13 00:43:28', 5),
(94, 600.00, 66, '', 1, 3, '2025-05-13 00:43:37', 5),
(95, 600.00, 67, '', 1, 4, '2025-05-13 00:43:37', 5),
(96, 1000.00, 68, 'gg', 1, 3, '2025-05-13 00:43:58', 5),
(97, 1000.00, 69, 'gg', 1, 5, '2025-05-13 00:43:58', 5),
(98, 1.00, 70, '', 1, 5, '2025-05-13 00:44:37', 5),
(99, 2.00, 71, '', 1, 4, '2025-05-13 00:44:54', 5),
(100, 2.00, 72, '', 1, 4, '2025-05-13 00:46:57', 5),
(101, 100.00, 73, '', 1, 7, '2025-05-13 00:46:57', 5),
(102, 20000.00, 74, '', 1, 3, '2025-05-13 00:47:20', 5),
(103, 50000.00, 75, '', 1, 5, '2025-05-13 00:49:05', 5),
(104, 50000.00, 76, '', 1, 5, '2025-05-13 00:49:51', 5),
(105, 50000.00, 77, '', 1, 5, '2025-05-13 00:50:17', 5),
(106, 50000.00, 78, '', 1, 5, '2025-05-13 00:50:21', 5);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `vorname` varchar(50) NOT NULL,
  `nachname` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `passwort` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`uid`, `vorname`, `nachname`, `email`, `passwort`) VALUES
(1, 'fabs', 'Default', 'default@mail.com', '$2y$10$Ce1wAAdXHD9bQj6RYQCDO..z.ySk/l6hMYahPzWGgn6RXsiBrTefO'),
(2, 'Paul', 'Default', 'default@mail.com', 'Passwort'),
(3, 'testest', 'Default', 'default2@mail.com', 'Passwort'),
(4, 'User1', 'Test', '1@mail.at', '$2y$10$TPSGW81zZ/guU8fhWPL.veDYyjANNkDTOFwfMplljlrxh9un6tcHe'),
(5, 'mail', 'mail', 'mail@mail.at', '$2y$10$bTXMi.VRk7VS1aLoP2RKVOgbYniVIRzCYARSPe04oIGf2aq9.43zW'),
(6, 'Fabian', 'Ebner', 'ebner.fabian@hakspittal.at', '$2y$10$h4Uw94fK4okpdXMn7noRAeTXUWb9auwsr/1qR1kslLd1crSSS5hku'),
(7, 'Pete', 'Lichtner', 'pete@mail.com', '$2y$10$MSKqZx2Vs6WadXc.qTLdXuZPKRGwDElNrwBdHaxXwleoMSSUOk0xm');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `einkommensregeln`
--
ALTER TABLE `einkommensregeln`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_regel` (`uid`);

--
-- Indizes für die Tabelle `einkommensverteilung`
--
ALTER TABLE `einkommensverteilung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `regel` (`regel_id`),
  ADD KEY `konto_verteilung` (`konto_id`);

--
-- Indizes für die Tabelle `konto`
--
ALTER TABLE `konto`
  ADD PRIMARY KEY (`kid`),
  ADD KEY `uid` (`uid`);

--
-- Indizes für die Tabelle `transaktionen`
--
ALTER TABLE `transaktionen`
  ADD PRIMARY KEY (`tid`),
  ADD KEY `quelle` (`quelle`),
  ADD KEY `ziel` (`ziel`),
  ADD KEY `user` (`uid`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `einkommensregeln`
--
ALTER TABLE `einkommensregeln`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `einkommensverteilung`
--
ALTER TABLE `einkommensverteilung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT für Tabelle `konto`
--
ALTER TABLE `konto`
  MODIFY `kid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT für Tabelle `transaktionen`
--
ALTER TABLE `transaktionen`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `einkommensregeln`
--
ALTER TABLE `einkommensregeln`
  ADD CONSTRAINT `user_regel` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Constraints der Tabelle `einkommensverteilung`
--
ALTER TABLE `einkommensverteilung`
  ADD CONSTRAINT `konto_verteilung` FOREIGN KEY (`konto_id`) REFERENCES `konto` (`kid`),
  ADD CONSTRAINT `regel` FOREIGN KEY (`regel_id`) REFERENCES `einkommensregeln` (`id`);

--
-- Constraints der Tabelle `konto`
--
ALTER TABLE `konto`
  ADD CONSTRAINT `uid` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Constraints der Tabelle `transaktionen`
--
ALTER TABLE `transaktionen`
  ADD CONSTRAINT `quelle` FOREIGN KEY (`quelle`) REFERENCES `konto` (`kid`),
  ADD CONSTRAINT `user` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`),
  ADD CONSTRAINT `ziel` FOREIGN KEY (`ziel`) REFERENCES `konto` (`kid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

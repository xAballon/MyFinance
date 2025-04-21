-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 21. Apr 2025 um 23:23
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

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
(1, 000, 'Einnahmen', 0.00, 5),
(2, 999, 'Ausgaben', 0.00, 5),
(3, 100, 'Wohnen', 0.00, 5),
(4, 101, 'Lebensmittel', 0.00, 5),
(5, 102, 'Freizeit', 0.00, 5),
(6, 103, 'Gesundheit', 0.00, 5),
(7, 104, 'Sparen', 0.00, 5),
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
(21, 104, 'Sparen', 0.00, 1);

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
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(6, 'Fabian', 'Ebner', 'ebner.fabian@hakspittal.at', '$2y$10$h4Uw94fK4okpdXMn7noRAeTXUWb9auwsr/1qR1kslLd1crSSS5hku');

--
-- Indizes der exportierten Tabellen
--

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
-- AUTO_INCREMENT für Tabelle `konto`
--
ALTER TABLE `konto`
  MODIFY `kid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT für Tabelle `transaktionen`
--
ALTER TABLE `transaktionen`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints der exportierten Tabellen
--

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

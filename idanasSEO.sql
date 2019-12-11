-- Host: localhost
-- Erstellungszeit: 11. Dez 2019 um 13:09
-- Server-Version: 10.3.17-MariaDB-0+deb10u1
-- PHP-Version: 7.3.11-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `idanasSEO`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `keywords`
--

CREATE TABLE `keywords` (
  `id` int(7) NOT NULL,
  `contrat_nr` varchar(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `domain` varchar(128) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `language` char(3) COLLATE latin1_general_ci NOT NULL DEFAULT 'all',
  `playdaymonth` int(2) NOT NULL DEFAULT 1,
  `pages` int(2) NOT NULL DEFAULT 2,
  `keywords` text COLLATE latin1_general_ci NOT NULL,
  `contracter` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  `emails` text COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `statistics`
--

CREATE TABLE `statistics` (
  `id` int(7) NOT NULL,
  `user` varchar(64) COLLATE latin1_general_ci DEFAULT NULL,
  `language` char(3) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `pages` int(2) NOT NULL DEFAULT 0,
  `domain` varchar(128) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `keywords` text COLLATE latin1_general_ci DEFAULT NULL,
  `prozent` int(3) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `name` varchar(32) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `surname1` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  `surname2` varchar(32) COLLATE latin1_general_ci DEFAULT NULL,
  `pass` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `email` varchar(64) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ip` varchar(15) COLLATE latin1_general_ci DEFAULT NULL,
  `sitespy` tinyint(1) NOT NULL DEFAULT 0,
  `posspy` tinyint(1) NOT NULL DEFAULT 0,
  `start` tinyint(4) NOT NULL DEFAULT 0,
  `idanasdb` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Table for take control of the richt for the users with acces';

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contrat_nr` (`contrat_nr`);

--
-- Indizes für die Tabelle `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domain` (`domain`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `keywords`
--
ALTER TABLE `keywords`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

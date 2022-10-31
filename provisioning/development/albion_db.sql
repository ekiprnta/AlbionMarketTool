-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: db_local
-- Erstellungszeit: 27. Okt 2022 um 17:49
-- Server-Version: 10.7.6-MariaDB-1:10.7.6+maria~ubu2004
-- PHP-Version: 8.0.19

SET
SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET
time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `albion_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `items`
--

CREATE TABLE `items`
(
    `tier`                    varchar(64) NOT NULL,
    `name`                    varchar(64) NOT NULL,
    `weaponGroup`             varchar(64) NOT NULL,
    `realName`                varchar(64) DEFAULT NULL,
    `class`                   varchar(64) DEFAULT NULL,
    `city`                    varchar(64) DEFAULT NULL,
    `quality`                 int(1) DEFAULT NULL,
    `sellOrderPrice`          int(11) DEFAULT NULL,
    `sellOrderPriceDate`      datetime    DEFAULT NULL,
    `buyOrderPrice`           int(11) DEFAULT NULL,
    `buyOrderPriceDate`       datetime    DEFAULT NULL,
    `primaryResource`         varchar(64) DEFAULT NULL,
    `primaryResourceAmount`   int(2) DEFAULT NULL,
    `secondaryResource`       varchar(64) DEFAULT NULL,
    `secondaryResourceAmount` int(2) DEFAULT NULL,
    `bonusCity`               varchar(64) DEFAULT NULL,
    `fameFactor`              float       DEFAULT NULL,
    `amountInStorage`         int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `journals`
--

CREATE TABLE `journals`
(
    `tier`               varchar(64) NOT NULL,
    `name`               varchar(64) NOT NULL,
    `city`               varchar(64) NOT NULL,
    `fameToFill`         int(10) DEFAULT NULL,
    `sellOrderPrice`     int(11) DEFAULT NULL,
    `sellOrderPriceDate` datetime DEFAULT NULL,
    `buyOrderPrice`      int(11) DEFAULT NULL,
    `buyOrderPriceDate`  datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `resource`
--

CREATE TABLE `resource`
(
    `tier`               varchar(64) NOT NULL,
    `name`               varchar(64) NOT NULL,
    `city`               varchar(64) NOT NULL,
    `realName`           varchar(64) DEFAULT NULL,
    `sellOrderPrice`     int(11) DEFAULT NULL,
    `sellOrderPriceDate` datetime    DEFAULT NULL,
    `buyOrderPrice`      int(11) DEFAULT NULL,
    `buyOrderPriceDate`  datetime    DEFAULT NULL,
    `bonusCity`          varchar(64) DEFAULT NULL,
    `amountInStorage`    int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `items`
--
ALTER TABLE `items`
    ADD PRIMARY KEY (`tier`, `name`, `weaponGroup`);

--
-- Indizes für die Tabelle `journals`
--
ALTER TABLE `journals`
    ADD PRIMARY KEY (`tier`, `name`, `city`);

--
-- Indizes für die Tabelle `resource`
--
ALTER TABLE `resource`
    ADD PRIMARY KEY (`tier`, `name`, `city`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

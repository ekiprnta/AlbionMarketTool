-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db_local:3306
-- Erstellungszeit: 22. Mrz 2023 um 14:07
-- Server-Version: 10.9.2-MariaDB-1:10.9.2+maria~ubu2204
-- PHP-Version: 8.1.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `albion_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bmCrafting`
--

CREATE TABLE `bmCrafting`
(
    `id`                      int(11)    NOT NULL,
    `item`                    int(11)                                 DEFAULT NULL,
    `fameAmount`              double                                  DEFAULT NULL,
    `journalAmountPerItem`    double                                  DEFAULT NULL,
    `itemValue`               int(11)                                 DEFAULT NULL,
    `primResourceTotalAmount` int(11)                                 DEFAULT NULL,
    `secResourceTotalAmount`  int(11)                                 DEFAULT NULL,
    `journalTotalAmount`      double                                  DEFAULT NULL,
    `craftingFee`             double                                  DEFAULT NULL,
    `profitJournals`          double                                  DEFAULT NULL,
    `bonusResource`           tinyint(1) NOT NULL,
    `tierColor`               int(11)                                 DEFAULT NULL,
    `amount`                  int(11)                                 DEFAULT NULL,
    `city`                    varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostBuy`         double                                  DEFAULT NULL,
    `profitBuy`               double                                  DEFAULT NULL,
    `profitPercentageBuy`     double                                  DEFAULT NULL,
    `profitGradeBuy`          varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostSell`        double                                  DEFAULT NULL,
    `profitSell`              double                                  DEFAULT NULL,
    `profitPercentageSell`    double                                  DEFAULT NULL,
    `profitGradeSell`         varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `complete`                tinyint(1) NOT NULL,
    `primResource`            int(11)                                 DEFAULT NULL,
    `secResource`             int(11)                                 DEFAULT NULL,
    `startResource`           int(11)                                 DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3
  COLLATE = utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bmTransport`
--

CREATE TABLE `bmTransport`
(
    `id`                   int(11)    NOT NULL,
    `tierString`           varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `tierColor`            int(11)                                 DEFAULT NULL,
    `amount`               int(11)                                 DEFAULT NULL,
    `city`                 varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostBuy`      double                                  DEFAULT NULL,
    `profitBuy`            double                                  DEFAULT NULL,
    `profitPercentageBuy`  double                                  DEFAULT NULL,
    `profitGradeBuy`       varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostSell`     double                                  DEFAULT NULL,
    `profitSell`           double                                  DEFAULT NULL,
    `profitPercentageSell` double                                  DEFAULT NULL,
    `profitGradeSell`      varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `complete`             tinyint(1) NOT NULL,
    `cityItemId`           int(11)                                 DEFAULT NULL,
    `bmItemId`             int(11)                                 DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3
  COLLATE = utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `enchanting`
--

CREATE TABLE `enchanting`
(
    `id`                    int(11)    NOT NULL,
    `baseEnchantment`       int(11)                                 DEFAULT NULL,
    `materialAmount`        int(11)                                 DEFAULT NULL,
    `tierColor`             int(11)                                 DEFAULT NULL,
    `amount`                int(11)                                 DEFAULT NULL,
    `city`                  varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostBuy`       double                                  DEFAULT NULL,
    `profitBuy`             double                                  DEFAULT NULL,
    `profitPercentageBuy`   double                                  DEFAULT NULL,
    `profitGradeBuy`        varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostSell`      double                                  DEFAULT NULL,
    `profitSell`            double                                  DEFAULT NULL,
    `profitPercentageSell`  double                                  DEFAULT NULL,
    `profitGradeSell`       varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `complete`              tinyint(1) NOT NULL,
    `higherEnchantmentItem` int(11)                                 DEFAULT NULL,
    `enchantmentMaterial`   int(11)                                 DEFAULT NULL,
    `baseItem`              int(11)                                 DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3
  COLLATE = utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `items`
--

CREATE TABLE `items`
(
    `tier`                    int(11)      DEFAULT NULL,
    `name`                    varchar(255) DEFAULT NULL,
    `weaponGroup`             varchar(255) DEFAULT NULL,
    `realName`                varchar(255) DEFAULT NULL,
    `class`                   varchar(255) DEFAULT NULL,
    `city`                    varchar(255) DEFAULT NULL,
    `quality`                 int(1)       DEFAULT NULL,
    `sellOrderPrice`          int(11)      DEFAULT NULL,
    `buyOrderPrice`           int(11)      DEFAULT NULL,
    `primaryResource`         varchar(255) DEFAULT NULL,
    `primaryResourceAmount`   int(2)       DEFAULT NULL,
    `secondaryResource`       varchar(255) DEFAULT NULL,
    `secondaryResourceAmount` int(2)       DEFAULT NULL,
    `bonusCity`               varchar(255) DEFAULT NULL,
    `fame`                    double       DEFAULT NULL,
    `itemValue`               int(11)      DEFAULT NULL,
    `id`                      int(11)    NOT NULL,
    `totalResourceAmount`     int(11)    NOT NULL,
    `artifact`                varchar(255) DEFAULT NULL,
    `blackMarketSellable`     tinyint(1) NOT NULL,
    `sellOrderDate`           datetime     DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    `buyOrderDate`            datetime     DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `journals`
--

CREATE TABLE `journals`
(
    `tier`           int(11)      DEFAULT NULL,
    `name`           varchar(255) DEFAULT NULL,
    `city`           varchar(255) DEFAULT NULL,
    `fameToFill`     int(10)      DEFAULT NULL,
    `sellOrderPrice` int(11)      DEFAULT NULL,
    `buyOrderPrice`  int(11)      DEFAULT NULL,
    `fillStatus`     varchar(255) DEFAULT NULL,
    `class`          varchar(255) DEFAULT NULL,
    `id`             int(11) NOT NULL,
    `sellOrderDate`  datetime     DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    `buyOrderDate`   datetime     DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    `realName`       varchar(255) DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `listData`
--

CREATE TABLE `listData`
(
    `id`                               int(11) NOT NULL,
    `cheapestObjectCitySellOrder`      varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `mostExpensiveObjectCitySellOrder` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `cheapestObjectCityBuyOrder`       varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `mostExpensiveObjectCityBuyOrder`  varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `type`                             varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `tierColor`                        int(11)                                 DEFAULT NULL,
    `fortsterlingObject`               int(11)                                 DEFAULT NULL,
    `martlockObject`                   int(11)                                 DEFAULT NULL,
    `bridgewatchObject`                int(11)                                 DEFAULT NULL,
    `lymhurstObject`                   int(11)                                 DEFAULT NULL,
    `thetfordObject`                   int(11)                                 DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3
  COLLATE = utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `materials`
--

CREATE TABLE `materials`
(
    `id`             int(11) NOT NULL,
    `type`           varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `tier`           int(11)                                 DEFAULT NULL,
    `name`           varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `city`           varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `sellOrderPrice` int(11)                                 DEFAULT NULL,
    `sellOrderDate`  datetime                                DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    `buyOrderPrice`  int(11)                                 DEFAULT NULL,
    `buyOrderDate`   datetime                                DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    `realName`       varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `class`          varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3
  COLLATE = utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `noSpec`
--

CREATE TABLE `noSpec`
(
    `id`                   int(11)    NOT NULL,
    `artifact`             int(11)                                 DEFAULT NULL,
    `tierColor`            int(11)                                 DEFAULT NULL,
    `amount`               int(11)                                 DEFAULT NULL,
    `city`                 varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostBuy`      double                                  DEFAULT NULL,
    `profitBuy`            double                                  DEFAULT NULL,
    `profitPercentageBuy`  double                                  DEFAULT NULL,
    `profitGradeBuy`       varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostSell`     double                                  DEFAULT NULL,
    `profitSell`           double                                  DEFAULT NULL,
    `profitPercentageSell` double                                  DEFAULT NULL,
    `profitGradeSell`      varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `complete`             tinyint(1) NOT NULL,
    `defaultItem`          int(11)                                 DEFAULT NULL,
    `specialItem`          int(11)                                 DEFAULT NULL,
    `secondResource`       int(11)                                 DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3
  COLLATE = utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `refining`
--

CREATE TABLE `refining`
(
    `id`                   int(11)    NOT NULL,
    `amountRawResource`    int(11)                                 DEFAULT NULL,
    `tierColor`            int(11)                                 DEFAULT NULL,
    `amount`               int(11)                                 DEFAULT NULL,
    `city`                 varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostBuy`      double                                  DEFAULT NULL,
    `profitBuy`            double                                  DEFAULT NULL,
    `profitPercentageBuy`  double                                  DEFAULT NULL,
    `profitGradeBuy`       varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostSell`     double                                  DEFAULT NULL,
    `profitSell`           double                                  DEFAULT NULL,
    `profitPercentageSell` double                                  DEFAULT NULL,
    `profitGradeSell`      varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `complete`             tinyint(1) NOT NULL,
    `rawResource`          int(11)                                 DEFAULT NULL,
    `lowerResource`        int(11)                                 DEFAULT NULL,
    `refinedResource`      int(11)                                 DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3
  COLLATE = utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `resources`
--

CREATE TABLE `resources`
(
    `id`             int(11)    NOT NULL,
    `bonusCity`      varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `raw`            tinyint(1) NOT NULL,
    `tier`           int(11)                                 DEFAULT NULL,
    `name`           varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `city`           varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `sellOrderPrice` int(11)                                 DEFAULT NULL,
    `sellOrderDate`  datetime                                DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    `buyOrderPrice`  int(11)                                 DEFAULT NULL,
    `buyOrderDate`   datetime                                DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
    `realName`       varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `class`          varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3
  COLLATE = utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `transmutation`
--

CREATE TABLE `transmutation`
(
    `id`                   int(11)    NOT NULL,
    `pathName`             varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `transmutationPath`    longtext COLLATE utf8mb3_unicode_ci     DEFAULT NULL COMMENT '(DC2Type:simple_array)',
    `resourceType`         varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `endTierColor`         int(11)                                 DEFAULT NULL,
    `transmutationPrice`   double                                  DEFAULT NULL,
    `tierColor`            int(11)                                 DEFAULT NULL,
    `amount`               int(11)                                 DEFAULT NULL,
    `city`                 varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostBuy`      double                                  DEFAULT NULL,
    `profitBuy`            double                                  DEFAULT NULL,
    `profitPercentageBuy`  double                                  DEFAULT NULL,
    `profitGradeBuy`       varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `materialCostSell`     double                                  DEFAULT NULL,
    `profitSell`           double                                  DEFAULT NULL,
    `profitPercentageSell` double                                  DEFAULT NULL,
    `profitGradeSell`      varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `complete`             tinyint(1) NOT NULL,
    `startResource`        int(11)                                 DEFAULT NULL,
    `endResource`          int(11)                                 DEFAULT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb3
  COLLATE = utf8mb3_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bmCrafting`
--
ALTER TABLE `bmCrafting`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_1978F56059CAACC9` (`primResource`),
    ADD KEY `IDX_1978F5607EA2BE62` (`secResource`),
    ADD KEY `IDX_1978F5601F1B251E` (`item`),
    ADD KEY `IDX_1978F560C16A2BEE` (`startResource`);

--
-- Indizes für die Tabelle `bmTransport`
--
ALTER TABLE `bmTransport`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `UNIQ_4575A6F67BE6F98C` (`cityItemId`),
    ADD KEY `IDX_4575A6F61E1B466E` (`bmItemId`);

--
-- Indizes für die Tabelle `enchanting`
--
ALTER TABLE `enchanting`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_C88138CB3C051404` (`higherEnchantmentItem`),
    ADD KEY `IDX_C88138CBC8FE71EE` (`enchantmentMaterial`),
    ADD KEY `IDX_C88138CB9824ED90` (`baseItem`);

--
-- Indizes für die Tabelle `items`
--
ALTER TABLE `items`
    ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `journals`
--
ALTER TABLE `journals`
    ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `listData`
--
ALTER TABLE `listData`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_5129D98ABB799ACA` (`fortsterlingObject`),
    ADD KEY `IDX_5129D98A3E64A1C1` (`martlockObject`),
    ADD KEY `IDX_5129D98AE83125EA` (`bridgewatchObject`),
    ADD KEY `IDX_5129D98AFC16C389` (`lymhurstObject`),
    ADD KEY `IDX_5129D98A58EEBDE3` (`thetfordObject`);

--
-- Indizes für die Tabelle `materials`
--
ALTER TABLE `materials`
    ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `noSpec`
--
ALTER TABLE `noSpec`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_D9264809DC25E0F7` (`defaultItem`),
    ADD KEY `IDX_D9264809ADB7798C` (`specialItem`),
    ADD KEY `IDX_D926480926EDF750` (`secondResource`),
    ADD KEY `IDX_D926480948E5602C` (`artifact`);

--
-- Indizes für die Tabelle `refining`
--
ALTER TABLE `refining`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_AC0111F047EBD2A5` (`rawResource`),
    ADD KEY `IDX_AC0111F0DFBCFC60` (`lowerResource`),
    ADD KEY `IDX_AC0111F07EBC310F` (`refinedResource`);

--
-- Indizes für die Tabelle `resources`
--
ALTER TABLE `resources`
    ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `transmutation`
--
ALTER TABLE `transmutation`
    ADD PRIMARY KEY (`id`),
    ADD KEY `IDX_796A4FC16A2BEE` (`startResource`),
    ADD KEY `IDX_796A4FA46026D4` (`endResource`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bmCrafting`
--
ALTER TABLE `bmCrafting`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `bmTransport`
--
ALTER TABLE `bmTransport`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `enchanting`
--
ALTER TABLE `enchanting`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `items`
--
ALTER TABLE `items`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `journals`
--
ALTER TABLE `journals`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `listData`
--
ALTER TABLE `listData`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `materials`
--
ALTER TABLE `materials`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `noSpec`
--
ALTER TABLE `noSpec`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `refining`
--
ALTER TABLE `refining`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `resources`
--
ALTER TABLE `resources`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `transmutation`
--
ALTER TABLE `transmutation`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bmCrafting`
--
ALTER TABLE `bmCrafting`
    ADD CONSTRAINT `FK_1978F5601F1B251E` FOREIGN KEY (`item`) REFERENCES `items` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_1978F56059CAACC9` FOREIGN KEY (`primResource`) REFERENCES `resources` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_1978F5607EA2BE62` FOREIGN KEY (`secResource`) REFERENCES `resources` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_1978F560C16A2BEE` FOREIGN KEY (`startResource`) REFERENCES `journals` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `bmTransport`
--
ALTER TABLE `bmTransport`
    ADD CONSTRAINT `FK_4575A6F61E1B466E` FOREIGN KEY (`bmItemId`) REFERENCES `items` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_4575A6F67BE6F98C` FOREIGN KEY (`cityItemId`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `enchanting`
--
ALTER TABLE `enchanting`
    ADD CONSTRAINT `FK_C88138CB3C051404` FOREIGN KEY (`higherEnchantmentItem`) REFERENCES `items` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_C88138CB9824ED90` FOREIGN KEY (`baseItem`) REFERENCES `items` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_C88138CBC8FE71EE` FOREIGN KEY (`enchantmentMaterial`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `listData`
--
ALTER TABLE `listData`
    ADD CONSTRAINT `FK_5129D98A3E64A1C1` FOREIGN KEY (`martlockObject`) REFERENCES `resources` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_5129D98A58EEBDE3` FOREIGN KEY (`thetfordObject`) REFERENCES `resources` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_5129D98ABB799ACA` FOREIGN KEY (`fortsterlingObject`) REFERENCES `resources` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_5129D98AE83125EA` FOREIGN KEY (`bridgewatchObject`) REFERENCES `resources` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_5129D98AFC16C389` FOREIGN KEY (`lymhurstObject`) REFERENCES `resources` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `noSpec`
--
ALTER TABLE `noSpec`
    ADD CONSTRAINT `FK_D926480926EDF750` FOREIGN KEY (`secondResource`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_D926480948E5602C` FOREIGN KEY (`artifact`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_D9264809ADB7798C` FOREIGN KEY (`specialItem`) REFERENCES `items` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_D9264809DC25E0F7` FOREIGN KEY (`defaultItem`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `refining`
--
ALTER TABLE `refining`
    ADD CONSTRAINT `FK_AC0111F047EBD2A5` FOREIGN KEY (`rawResource`) REFERENCES `resources` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_AC0111F07EBC310F` FOREIGN KEY (`refinedResource`) REFERENCES `resources` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_AC0111F0DFBCFC60` FOREIGN KEY (`lowerResource`) REFERENCES `resources` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `transmutation`
--
ALTER TABLE `transmutation`
    ADD CONSTRAINT `FK_796A4FA46026D4` FOREIGN KEY (`endResource`) REFERENCES `resources` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `FK_796A4FC16A2BEE` FOREIGN KEY (`startResource`) REFERENCES `resources` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

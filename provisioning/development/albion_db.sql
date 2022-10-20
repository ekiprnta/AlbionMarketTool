-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Erstellungszeit: 29. Jun 2022 um 09:00
-- Server-Version: 10.7.4-MariaDB-1:10.7.4+maria~focal
-- PHP-Version: 8.0.19

SET
SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET
time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `Warrior`
(
    `id`               varchar(64) NOT NULL,
    `city`             varchar(64) DEFAULT NULL,
    `quality`          int(1) DEFAULT NULL,
    `sellPriceMin`     int(11) DEFAULT NULL,
    `sellPriceMinDate` varchar(64) DEFAULT NULL,
    `buyPriceMin`      int (11) DEFAULT NULL,
    `buyPriceMinDate`  varchar(64) DEFAULT NULL
);

CREATE TABLE `Mage`
(
    `id`               varchar(64)  NOT NULL,
    `city`             varchar(64) DEFAULT NULL,
    `quality`          int(1) DEFAULT NULL,
    `sellPriceMin`     int(11) DEFAULT NULL,
    `sellPriceMinDate` varchar(64) DEFAULT NULL,
    `buyPriceMin`      int (11) DEFAULT NULL,
    `buyPriceMinDate`  varchar(64) DEFAULT NULL
);

CREATE TABLE `Hunter`
(
    `id`               varchar(64)  NOT NULL,
    `city`             varchar(64) DEFAULT NULL,
    `quality`          int(1) DEFAULT NULL,
    `sellPriceMin`     int(11) DEFAULT NULL,
    `sellPriceMinDate` varchar(64) DEFAULT NULL,
    `buyPriceMin`      int (11) DEFAULT NULL,
    `buyPriceMinDate`  varchar(64) DEFAULT NULL
);

ALTER TABLE `Warrior`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `id_UNIQUE` (`id`);

ALTER TABLE `Mage`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `id_UNIQUE` (`id`);

ALTER TABLE `Hunter`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `id_UNIQUE` (`id`);

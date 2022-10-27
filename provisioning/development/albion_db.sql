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

CREATE TABLE `resource`
(
    `tier`               varchar(64) NOT NULL,
    `name`               varchar(64) NOT NULL,
    `city`               varchar(64) NOT NULL,
    `realName`           varchar(64) DEFAULT NULL,
    `sellOrderPrice`     int(11) DEFAULT NULL,
    `sellOrderPriceDate` datetime    DEFAULT NULL,
    `buyOrderPrice`      int (11) DEFAULT NULL,
    `buyOrderPriceDate`  datetime    DEFAULT NULL,
    `bonusCity`          varchar(64) DEFAULT NULL,
    `amountInStorage`    int(10) DEFAULT NULL
        primary key (`tier`, `id`, `city`)
);

CREATE TABLE `items`
(
    `tier`                    varchar(64) NOT NULL,
    `name`                    varchar(64) NOT NULL,
    `weaponGroup`             varchar(64) NOT NULL,
    `realName`                varchar(64) DEFAULT NULL
        `class` varchar (64) DEFAULT NULL,
    `city`                    varchar(64) DEFAULT NULL,
    `quality`                 int(1) DEFAULT NULL,
    `sellOrderPrice`          int(11) DEFAULT NULL,
    `sellOrderPriceDate`      datetime    DEFAULT NULL,
    `buyOrderPrice`           int (11) DEFAULT NULL,
    `buyOrderPriceDate`       datetime    DEFAULT NULL,
    `primaryResource`         varchar(64) DEFAULT NULL,
    `primaryResourceAmount`   int(2) DEFAULT NULL,
    `secondaryResource`       varchar(64) DEFAULT NULL,
    `secondaryResourceAmount` int(2) DEFAULT NULL,
    `bonusCity`               varchar(64) DEFAULT NULL,
    `fameFactor`              float       DEFAULT NULL,
    `amountInStorage`         int(10) DEFAULT NULL,
    primary key (`tier`, `id`, `weaponGroup`)
);

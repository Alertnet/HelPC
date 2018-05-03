<?php
require_once './cfg.php';
// Подключаемся к серверу,
// на котором предварительно создали базу данных

mysql_connect($HOST, $USER, $PASS) or die ("Не могу создать соединение"); //устанавливаем соединение с хостом, если не получилось завершаем скрипт с ошибкой
mysql_select_db($DB) or die (mysql_error());                              //Выбор базы данных или завершение скрипта


$query = 'CREATE TABLE clients (clients_telephone int(10) NOT NULL PRIMARY KEY,
                                clients_name varchar(50),
                                clients_address varchar(200)
                                )';

mysql_query($query) or die (mysql_error());
echo 'success';
?>


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


CREATE DATABASE IF NOT EXISTS `u359871647_helpc` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `u359871647_helpc`;


CREATE TABLE `clients` (
  `clients_telephone` bigint(20) UNSIGNED NOT NULL,
  `clients_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clients_address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clients_description` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `masters` (
  `masters_log` int(10) NOT NULL,
  `masters_pass` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `masters_balance` int(10) DEFAULT NULL,
  `masters_img` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `masters_rating` int(10) DEFAULT NULL,
  `masters_quantity` int(5) DEFAULT NULL,
  `masters_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `masters_surename` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



CREATE TABLE `requests` (
  `requests_id` int(10) NOT NULL,
  `requests_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `requests_description` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `requests_category` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `requests_mastersID` int(10) NOT NULL,
  `requests_clientsID` bigint(20) NOT NULL,
  `requests_cost` int(5) DEFAULT NULL,
  `requests_time` int(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `clients`
  ADD PRIMARY KEY (`clients_telephone`);

ALTER TABLE `masters`
  ADD PRIMARY KEY (`masters_log`);

ALTER TABLE `requests`
  ADD PRIMARY KEY (`requests_id`);
COMMIT;

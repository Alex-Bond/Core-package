-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 29 2011 г., 23:56
-- Версия сервера: 5.5.17
-- Версия PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `digitallib_v2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `image` varchar(50) NOT NULL,
  `name` varchar(500) NOT NULL DEFAULT '1',
  `autor` varchar(255) NOT NULL DEFAULT '1',
  `file` varchar(255) NOT NULL DEFAULT '1',
  `data` datetime NOT NULL,
  `com` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `vid` int(1) NOT NULL,
  `cheked` int(1) NOT NULL,
  `show` int(1) NOT NULL DEFAULT '1',
  `onflash` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `onflash`
--

CREATE TABLE IF NOT EXISTS `onflash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book` int(6) NOT NULL,
  `user` int(5) NOT NULL,
  `sesid` int(6) NOT NULL,
  `unicid` int(6) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `userid` int(5) NOT NULL,
  `exp` int(15) NOT NULL,
  `pc` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(5) NOT NULL,
  `event` varchar(256) NOT NULL,
  `result` varchar(256) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `event` (`event`(255)),
  KEY `event_2` (`event`(255),`result`(255))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

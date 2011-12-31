-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 29 2011 г., 23:17
-- Версия сервера: 5.5.17
-- Версия PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `kkzcore`
--

-- --------------------------------------------------------

--
-- Структура таблицы `commissions`
--

CREATE TABLE IF NOT EXISTS `commissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `courses`
--

CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `com` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `lostpass`
--

CREATE TABLE IF NOT EXISTS `lostpass` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` int(6) NOT NULL,
  `user` int(6) NOT NULL,
  `exp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `oauth`
--

CREATE TABLE IF NOT EXISTS `oauth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ses` varchar(40) NOT NULL,
  `user` int(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ses` varchar(40) NOT NULL,
  `user` int(6) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `stats`
--

CREATE TABLE IF NOT EXISTS `stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(6) NOT NULL,
  `do` varchar(256) NOT NULL,
  `result` varchar(256) NOT NULL,
  `site` int(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `result` (`result`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `sys_groups`
--

CREATE TABLE IF NOT EXISTS `sys_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sys_g_name` varchar(256) NOT NULL,
  `sys_g_level` int(2) NOT NULL,
  `sys_g_studid` int(1) NOT NULL,
  `sys_g_admin` int(2) NOT NULL,
  `sys_g_groups` int(1) NOT NULL,
  `sys_g_coms` int(1) NOT NULL,
  `sys_g_courses` int(1) NOT NULL,
  `sys_g_options` int(1) NOT NULL,
  `sys_g_old` int(2) NOT NULL,
  `sys_g_register` int(1) NOT NULL,
  `sys_g_onpass` int(1) NOT NULL,
  `sys_g_pass` varchar(256) NOT NULL,
  `sys_g_off` int(1) NOT NULL,
  `sys_g_woff` varchar(1024) DEFAULT NULL,
  `sys_g_delete` int(1) NOT NULL DEFAULT '0',
  `sys_g_allcoms` int(1) NOT NULL DEFAULT '0',
  `sys_g_other` longtext NOT NULL,
  `sys_g_internet` int(1) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `sys_groups` (`id`, `sys_g_name`, `sys_g_level`, `sys_g_studid`, `sys_g_admin`, `sys_g_groups`, `sys_g_coms`, `sys_g_courses`, `sys_g_options`, `sys_g_old`, `sys_g_register`, `sys_g_onpass`, `sys_g_pass`, `sys_g_off`, `sys_g_woff`, `sys_g_delete`, `sys_g_allcoms`, `sys_g_other`, `sys_g_internet`) VALUES
(1, 'Системний адміністратор', 99, 2, 1, 1, 1, 1, 1, 99, 0, 0, '', 0, '', 1, 1, '', 3),
(0, 'Денне відділення', 1, 0, 0, 0, 0, 0, 0, 1, 1, 0, '', 0, '', 0, 0, '', 1),

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(256) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `permission` int(2) NOT NULL,
  `studid` varchar(20) DEFAULT NULL,
  `name` varchar(256) NOT NULL,
  `lastname` varchar(256) NOT NULL,
  `fathername` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `question` int(2) NOT NULL,
  `answer` varchar(32) NOT NULL,
  `flush` int(1) DEFAULT NULL,
  `group` int(5) DEFAULT NULL,
  `com` int(5) DEFAULT NULL,
  `lastvisit` datetime NOT NULL,
  `cheked` int(1) DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `comments` text NOT NULL,
  `register` datetime NOT NULL,
  `sys_group` int(2) NOT NULL,
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `internet` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `studid` (`studid`),
  KEY `login` (`login`),
  KEY `pass` (`pass`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `users` (`login`, `pass`, `permission`, `studid`, `name`, `lastname`, `fathername`, `email`, `question`, `answer`, `flush`, `group`, `com`, `lastvisit`, `cheked`, `active`, `comments`, `register`, `sys_group`, `archived`, `internet`) VALUES
('root', '23b4222d2613a2765d4d432d2d65e88e', 99, '', 'Admin', 'Admin', 'Admin', 'i@localhost', 1, '84489f2cfd152997127c94160d62532c', 0, 0, 0, '2011-12-27 00:00:00', 1, 1, '', '2010-08-31 00:00:00', 1, 0, 0),

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

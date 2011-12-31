-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 29 2011 г., 23:48
-- Версия сервера: 5.5.17
-- Версия PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `kkz_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `kkztanswers`
--

CREATE TABLE IF NOT EXISTS `kkztanswers` (
  `answerid` int(10) unsigned NOT NULL DEFAULT '0',
  `questionid` int(10) unsigned NOT NULL DEFAULT '0',
  `answer_text` longtext NOT NULL,
  `answer_feedback` text NOT NULL,
  `answer_correct` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `answer_percents` float NOT NULL DEFAULT '0',
  `isregexp` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `iscasesensitive` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`questionid`,`answerid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztconfig`
--

CREATE TABLE IF NOT EXISTS `kkztconfig` (
  `configid` int(10) unsigned NOT NULL DEFAULT '0',
  `config_name` varchar(256) NOT NULL DEFAULT '',
  `config_value` text NOT NULL,
  PRIMARY KEY (`configid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Дамп данных таблицы `kkztconfig`
--

INSERT INTO `kkztconfig` (`configid`, `config_name`, `config_value`) VALUES
(1, 'igttimestamp', '1122210319'),
(2, 'igtversion', '1.4.5'),
(3, 'can_register', '0'),
(4, 'reg_intro', ''),
(5, 'reg_username', '4'),
(6, 'reg_password', '4'),
(7, 'reg_email', '2'),
(8, 'reg_firstname', '4'),
(9, 'reg_lastname', '4'),
(10, 'reg_middlename', '4'),
(11, 'reg_address', '0'),
(12, 'reg_city', '0'),
(13, 'reg_state', '0'),
(14, 'reg_zip', '0'),
(15, 'reg_country', '0'),
(16, 'reg_phone', '0'),
(17, 'reg_fax', '0'),
(18, 'reg_mobile', '0'),
(19, 'reg_pager', '0'),
(20, 'reg_ipphone', '0'),
(21, 'reg_webpage', '0'),
(22, 'reg_icq', '0'),
(23, 'reg_msn', '0'),
(24, 'reg_gender', '0'),
(25, 'reg_birthday', '0'),
(26, 'reg_photo', '0'),
(27, 'reg_company', '0'),
(28, 'reg_jobtitle', '0'),
(29, 'reg_department', '0'),
(30, 'reg_office', '0'),
(31, 'reg_caddress', '0'),
(32, 'reg_ccity', '0'),
(33, 'reg_cstate', '0'),
(34, 'reg_czip', '0'),
(35, 'reg_ccountry', '0'),
(36, 'reg_cphone', '0'),
(37, 'reg_cfax', '0'),
(38, 'reg_cmobile', '0'),
(39, 'reg_cpager', '0'),
(40, 'reg_cipphone', '0'),
(41, 'reg_cwebpage', '0'),
(42, 'reg_trainer', '0'),
(43, 'reg_userfield1', '0'),
(44, 'reg_caption_userfield1', ''),
(45, 'reg_userfield2', '0'),
(46, 'reg_caption_userfield2', ''),
(47, 'reg_userfield3', '0'),
(48, 'reg_caption_userfield3', ''),
(49, 'reg_userfield4', '0'),
(50, 'reg_caption_userfield4', ''),
(51, 'list_length', '50'),
(52, 'store_logs', '1'),
(53, 'editor_type', '4'),
(54, 'upon_registration', '0'),
(55, 'reg_title', '0'),
(56, 'reg_aol', '0'),
(57, 'reg_husbandwife', '0'),
(58, 'reg_children', '0'),
(59, 'reg_cphoto', '0'),
(60, 'reg_userfield5', '0'),
(61, 'reg_caption_userfield5', ''),
(62, 'reg_userfield6', '0'),
(63, 'reg_caption_userfield6', ''),
(64, 'reg_userfield7', '0'),
(65, 'reg_caption_userfield7', ''),
(66, 'reg_userfield8', '0'),
(67, 'reg_caption_userfield8', ''),
(68, 'reg_userfield9', '0'),
(69, 'reg_caption_userfield9', ''),
(70, 'reg_userfield10', '0'),
(71, 'reg_caption_userfield10', ''),
(72, 'reg_type_userfield1', ''),
(73, 'reg_values_userfield1', ''),
(74, 'reg_type_userfield2', ''),
(75, 'reg_values_userfield2', ''),
(76, 'reg_type_userfield3', ''),
(77, 'reg_values_userfield3', '0'),
(78, 'reg_type_userfield4', ''),
(79, 'reg_values_userfield4', ''),
(80, 'reg_type_userfield5', ''),
(81, 'reg_values_userfield5', ''),
(82, 'reg_type_userfield6', ''),
(83, 'reg_values_userfield6', ''),
(84, 'reg_type_userfield7', ''),
(85, 'reg_values_userfield7', ''),
(86, 'reg_type_userfield8', ''),
(87, 'reg_values_userfield8', ''),
(88, 'reg_type_userfield9', ''),
(89, 'reg_values_userfield9', ''),
(90, 'reg_type_userfield10', ''),
(91, 'reg_values_userfield10', '');


--
-- Структура таблицы `kkztetemplates`
--

CREATE TABLE IF NOT EXISTS `kkztetemplates` (
  `etemplateid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `etemplate_name` varbinary(255) NOT NULL DEFAULT '',
  `etemplate_description` varbinary(255) NOT NULL DEFAULT '',
  `etemplate_from` varbinary(255) NOT NULL DEFAULT '',
  `etemplate_subject` varbinary(255) NOT NULL DEFAULT '',
  `etemplate_body` blob NOT NULL,
  PRIMARY KEY (`etemplateid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztgroups`
--

CREATE TABLE IF NOT EXISTS `kkztgroups` (
  `groupid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(512) NOT NULL DEFAULT '',
  `group_description` varchar(1024) NOT NULL DEFAULT '',
  `access_tests` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `access_testmanager` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `access_gradingsystems` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `access_emailtemplates` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `access_reporttemplates` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `access_reportsmanager` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `access_questionbank` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `access_subjects` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `access_groups` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `access_users` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `access_visitors` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `access_config` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztgroups_tests`
--

CREATE TABLE IF NOT EXISTS `kkztgroups_tests` (
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `testid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupid`,`testid`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztgroups_users`
--

CREATE TABLE IF NOT EXISTS `kkztgroups_users` (
  `groupid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztgscales`
--

CREATE TABLE IF NOT EXISTS `kkztgscales` (
  `gscaleid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gscale_name` varchar(1024) NOT NULL DEFAULT '',
  `gscale_description` varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY (`gscaleid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztgscales_grades`
--

CREATE TABLE IF NOT EXISTS `kkztgscales_grades` (
  `gscaleid` int(10) unsigned NOT NULL DEFAULT '0',
  `gscale_gradeid` int(10) unsigned NOT NULL DEFAULT '0',
  `grade_name` varchar(1024) NOT NULL DEFAULT '',
  `grade_description` varchar(1024) NOT NULL DEFAULT '',
  `grade_from` float NOT NULL DEFAULT '0',
  `grade_to` float NOT NULL DEFAULT '0',
  `isabsolute` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`gscaleid`,`gscale_gradeid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztquestions`
--

CREATE TABLE IF NOT EXISTS `kkztquestions` (
  `questionid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subjectid` int(10) unsigned NOT NULL DEFAULT '1',
  `question_time` int(10) unsigned NOT NULL DEFAULT '0',
  `question_pre` text NOT NULL,
  `question_post` text NOT NULL,
  `question_text` text NOT NULL,
  `question_points` tinyint(4) NOT NULL DEFAULT '1',
  `question_solution` text NOT NULL,
  `question_type` int(10) unsigned NOT NULL DEFAULT '0',
  `question_upper` int(1) NOT NULL DEFAULT '1',
  `question_spaces` int(1) NOT NULL DEFAULT '1',
  `question_2spaces` int(1) NOT NULL DEFAULT '1',
  `question_comas` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`questionid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztresults`
--

CREATE TABLE IF NOT EXISTS `kkztresults` (
  `resultid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `testid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `result_datestart` int(10) unsigned NOT NULL DEFAULT '0',
  `result_lastact` int(10) NOT NULL DEFAULT '0',
  `result_timespent` int(10) unsigned NOT NULL DEFAULT '0',
  `result_timeexceeded` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `result_points` int(11) NOT NULL DEFAULT '0',
  `result_pointsmax` int(11) NOT NULL DEFAULT '0',
  `gscaleid` int(10) unsigned NOT NULL DEFAULT '1',
  `gscale_gradeid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`resultid`),
  KEY `test` (`testid`),
  KEY `user` (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztresults_answers`
--

CREATE TABLE IF NOT EXISTS `kkztresults_answers` (
  `result_answerid` int(10) unsigned NOT NULL DEFAULT '0',
  `resultid` int(10) unsigned NOT NULL DEFAULT '0',
  `questionid` int(10) unsigned NOT NULL DEFAULT '0',
  `test_questionid` int(10) unsigned NOT NULL DEFAULT '0',
  `result_answer_text` text NOT NULL,
  `result_answer_points` tinyint(4) NOT NULL DEFAULT '0',
  `result_answer_iscorrect` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `result_answer_feedback` text NOT NULL,
  `result_answer_timespent` int(10) unsigned NOT NULL DEFAULT '0',
  `result_answer_timeexceeded` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`resultid`,`result_answerid`),
  KEY `questionid` (`questionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztrtemplates`
--

CREATE TABLE IF NOT EXISTS `kkztrtemplates` (
  `rtemplateid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rtemplate_name` varchar(512) NOT NULL,
  `rtemplate_description` varchar(512) NOT NULL,
  `rtemplate_body` longtext NOT NULL,
  PRIMARY KEY (`rtemplateid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztsubjects`
--

CREATE TABLE IF NOT EXISTS `kkztsubjects` (
  `subjectid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(1024) NOT NULL DEFAULT '',
  `subject_description` varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY (`subjectid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkzttests`
--

CREATE TABLE IF NOT EXISTS `kkzttests` (
  `testid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subjectid` int(10) unsigned NOT NULL DEFAULT '1',
  `rtemplateid` int(10) unsigned NOT NULL DEFAULT '1',
  `result_etemplateid` int(10) unsigned NOT NULL DEFAULT '0',
  `gscaleid` int(10) unsigned NOT NULL DEFAULT '1',
  `test_name` varchar(1024) NOT NULL DEFAULT '',
  `test_description` varchar(2048) NOT NULL DEFAULT '',
  `test_instructions` text NOT NULL,
  `test_time` int(10) unsigned NOT NULL DEFAULT '0',
  `test_timeforceout` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_timingq` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_attempts` int(10) unsigned NOT NULL DEFAULT '0',
  `test_shuffleq` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_shufflea` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_sectionstype` int(10) unsigned NOT NULL DEFAULT '0',
  `test_qsperpage` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `test_showqfeedback` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_result_showanswers` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_result_showpoints` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `test_result_showgrade` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_result_showpdf` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_reportgradecondition` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_result_email` varbinary(255) NOT NULL DEFAULT '',
  `test_result_emailtouser` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_datestart` int(10) unsigned NOT NULL DEFAULT '0',
  `test_dateend` int(10) unsigned NOT NULL DEFAULT '0',
  `test_notes` text NOT NULL,
  `test_forall` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `test_createdate` int(10) unsigned NOT NULL DEFAULT '0',
  `test_enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `test_internet` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`testid`),
  KEY `subjectid` (`subjectid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkzttests_attempts`
--

CREATE TABLE IF NOT EXISTS `kkzttests_attempts` (
  `testid` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `test_attempt_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`testid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkzttests_questions`
--

CREATE TABLE IF NOT EXISTS `kkzttests_questions` (
  `test_questionid` int(10) unsigned NOT NULL DEFAULT '0',
  `testid` int(10) unsigned NOT NULL DEFAULT '0',
  `questionid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`testid`,`test_questionid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztusers`
--

CREATE TABLE IF NOT EXISTS `kkztusers` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(512) NOT NULL DEFAULT '',
  `user_passhash` varbinary(32) NOT NULL DEFAULT '',
  `user_email` varchar(512) NOT NULL DEFAULT '',
  `user_title` varbinary(32) NOT NULL DEFAULT '',
  `user_firstname` varchar(512) NOT NULL DEFAULT '',
  `user_lastname` varchar(512) NOT NULL DEFAULT '',
  `user_middlename` varchar(512) NOT NULL DEFAULT '',
  `user_address` blob NOT NULL,
  `user_city` varbinary(255) NOT NULL DEFAULT '',
  `user_state` varbinary(255) NOT NULL DEFAULT '',
  `user_zip` varbinary(255) NOT NULL DEFAULT '',
  `user_country` varbinary(255) NOT NULL DEFAULT '',
  `user_phone` varbinary(255) NOT NULL DEFAULT '',
  `user_fax` varbinary(255) NOT NULL DEFAULT '',
  `user_mobile` varbinary(255) NOT NULL DEFAULT '',
  `user_pager` varbinary(255) NOT NULL DEFAULT '',
  `user_ipphone` varbinary(255) NOT NULL DEFAULT '',
  `user_webpage` varbinary(255) NOT NULL DEFAULT '',
  `user_icq` varbinary(255) NOT NULL DEFAULT '',
  `user_msn` varbinary(255) NOT NULL DEFAULT '',
  `user_aol` varbinary(255) NOT NULL DEFAULT '',
  `user_gender` tinyint(4) NOT NULL DEFAULT '0',
  `user_birthday` datetime DEFAULT NULL,
  `user_husbandwife` varbinary(255) NOT NULL DEFAULT '',
  `user_children` varbinary(255) NOT NULL DEFAULT '',
  `user_trainer` varbinary(255) NOT NULL DEFAULT '',
  `user_photo` varbinary(255) NOT NULL DEFAULT '',
  `user_company` varbinary(255) NOT NULL DEFAULT '',
  `user_cposition` varbinary(255) NOT NULL DEFAULT '',
  `user_department` varbinary(255) NOT NULL DEFAULT '',
  `user_coffice` varbinary(255) NOT NULL DEFAULT '',
  `user_caddress` blob NOT NULL,
  `user_ccity` varbinary(255) NOT NULL DEFAULT '',
  `user_cstate` varbinary(255) NOT NULL DEFAULT '',
  `user_czip` varbinary(255) NOT NULL DEFAULT '',
  `user_ccountry` varbinary(255) NOT NULL DEFAULT '',
  `user_cphone` varbinary(255) NOT NULL DEFAULT '',
  `user_cfax` varbinary(255) NOT NULL DEFAULT '',
  `user_cmobile` varbinary(255) NOT NULL DEFAULT '',
  `user_cpager` varbinary(255) NOT NULL DEFAULT '',
  `user_cipphone` varbinary(255) NOT NULL DEFAULT '',
  `user_cwebpage` varbinary(255) NOT NULL DEFAULT '',
  `user_cphoto` varbinary(255) NOT NULL DEFAULT '',
  `user_ufield1` blob NOT NULL,
  `user_ufield2` blob NOT NULL,
  `user_ufield3` blob NOT NULL,
  `user_ufield4` blob NOT NULL,
  `user_ufield5` blob NOT NULL,
  `user_ufield6` blob NOT NULL,
  `user_ufield7` blob NOT NULL,
  `user_ufield8` blob NOT NULL,
  `user_ufield9` blob NOT NULL,
  `user_ufield10` blob NOT NULL,
  `user_notes` blob NOT NULL,
  `user_joindate` int(10) unsigned NOT NULL DEFAULT '0',
  `user_logindate` int(10) unsigned NOT NULL DEFAULT '0',
  `user_expiredate` int(10) unsigned NOT NULL DEFAULT '0',
  `user_enabled` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztvisitors`
--

CREATE TABLE IF NOT EXISTS `kkztvisitors` (
  `visitorid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `startdate` int(10) unsigned NOT NULL DEFAULT '0',
  `enddate` int(10) unsigned NOT NULL DEFAULT '0',
  `userid` int(10) unsigned NOT NULL DEFAULT '0',
  `ip1` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip2` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip3` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `ip4` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `host` varbinary(100) NOT NULL DEFAULT '',
  `referer` varbinary(255) NOT NULL DEFAULT '',
  `useragent` varbinary(255) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `inurl` varbinary(255) NOT NULL DEFAULT '',
  `outurl` varbinary(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`visitorid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

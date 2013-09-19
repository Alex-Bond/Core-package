-- phpMyAdmin SQL Dump
-- version 3.4.1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Дек 29 2011 г., 23:48
-- Версия сервера: 5.5.17
-- Версия PHP: 5.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `kkz_test`
--

-- --------------------------------------------------------

--
-- Структура таблицы `kkztanswers`
--

CREATE TABLE IF NOT EXISTS `kkztanswers` (
  `answerid`        INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `questionid`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `answer_text`     LONGTEXT            NOT NULL,
  `answer_feedback` TEXT                NOT NULL,
  `answer_correct`  TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `answer_percents` FLOAT               NOT NULL DEFAULT '0',
  `isregexp`        TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `iscasesensitive` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`questionid`, `answerid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztconfig`
--

CREATE TABLE IF NOT EXISTS `kkztconfig` (
  `configid`     INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `config_name`  VARCHAR(256)     NOT NULL DEFAULT '',
  `config_value` TEXT             NOT NULL,
  PRIMARY KEY (`configid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

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
  `etemplateid`           INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `etemplate_name`        VARBINARY(255)   NOT NULL DEFAULT '',
  `etemplate_description` VARBINARY(255)   NOT NULL DEFAULT '',
  `etemplate_from`        VARBINARY(255)   NOT NULL DEFAULT '',
  `etemplate_subject`     VARBINARY(255)   NOT NULL DEFAULT '',
  `etemplate_body`        BLOB             NOT NULL,
  PRIMARY KEY (`etemplateid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztgroups`
--

CREATE TABLE IF NOT EXISTS `kkztgroups` (
  `groupid`                INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `group_name`             VARCHAR(512)        NOT NULL DEFAULT '',
  `group_description`      VARCHAR(1024)       NOT NULL DEFAULT '',
  `access_tests`           TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  `access_testmanager`     TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_gradingsystems`  TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_emailtemplates`  TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_reporttemplates` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_reportsmanager`  TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_questionbank`    TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_subjects`        TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_groups`          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_users`           TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_visitors`        TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `access_config`          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztgroups_tests`
--

CREATE TABLE IF NOT EXISTS `kkztgroups_tests` (
  `groupid` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `testid`  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupid`, `testid`),
  KEY `groupid` (`groupid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztgroups_users`
--

CREATE TABLE IF NOT EXISTS `kkztgroups_users` (
  `groupid` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `userid`  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`groupid`, `userid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztgscales`
--

CREATE TABLE IF NOT EXISTS `kkztgscales` (
  `gscaleid`           INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gscale_name`        VARCHAR(1024)    NOT NULL DEFAULT '',
  `gscale_description` VARCHAR(1024)    NOT NULL DEFAULT '',
  PRIMARY KEY (`gscaleid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztgscales_grades`
--

CREATE TABLE IF NOT EXISTS `kkztgscales_grades` (
  `gscaleid`          INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `gscale_gradeid`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `grade_name`        VARCHAR(1024)       NOT NULL DEFAULT '',
  `grade_description` VARCHAR(1024)       NOT NULL DEFAULT '',
  `grade_from`        FLOAT               NOT NULL DEFAULT '0',
  `grade_to`          FLOAT               NOT NULL DEFAULT '0',
  `isabsolute`        TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`gscaleid`, `gscale_gradeid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztquestions`
--

CREATE TABLE IF NOT EXISTS `kkztquestions` (
  `questionid`        INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subjectid`         INT(10) UNSIGNED NOT NULL DEFAULT '1',
  `question_time`     INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `question_pre`      TEXT             NOT NULL,
  `question_post`     TEXT             NOT NULL,
  `question_text`     TEXT             NOT NULL,
  `question_points`   TINYINT(4)       NOT NULL DEFAULT '1',
  `question_solution` TEXT             NOT NULL,
  `question_type`     INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `question_upper`    INT(1)           NOT NULL DEFAULT '1',
  `question_spaces`   INT(1)           NOT NULL DEFAULT '1',
  `question_2spaces`  INT(1)           NOT NULL DEFAULT '1',
  `question_comas`    INT(1)           NOT NULL DEFAULT '1',
  PRIMARY KEY (`questionid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztresults`
--

CREATE TABLE IF NOT EXISTS `kkztresults` (
  `resultid`            INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `testid`              INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `userid`              INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `result_datestart`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `result_lastact`      INT(10)             NOT NULL DEFAULT '0',
  `result_timespent`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `result_timeexceeded` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `result_points`       INT(11)             NOT NULL DEFAULT '0',
  `result_pointsmax`    INT(11)             NOT NULL DEFAULT '0',
  `gscaleid`            INT(10) UNSIGNED    NOT NULL DEFAULT '1',
  `gscale_gradeid`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`resultid`),
  KEY `test` (`testid`),
  KEY `user` (`userid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztresults_answers`
--

CREATE TABLE IF NOT EXISTS `kkztresults_answers` (
  `result_answerid`            INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `resultid`                   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `questionid`                 INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `test_questionid`            INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `result_answer_text`         TEXT                NOT NULL,
  `result_answer_points`       TINYINT(4)          NOT NULL DEFAULT '0',
  `result_answer_iscorrect`    TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `result_answer_feedback`     TEXT                NOT NULL,
  `result_answer_timespent`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `result_answer_timeexceeded` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`resultid`, `result_answerid`),
  KEY `questionid` (`questionid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztrtemplates`
--

CREATE TABLE IF NOT EXISTS `kkztrtemplates` (
  `rtemplateid`           INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rtemplate_name`        VARCHAR(512)     NOT NULL,
  `rtemplate_description` VARCHAR(512)     NOT NULL,
  `rtemplate_body`        LONGTEXT         NOT NULL,
  PRIMARY KEY (`rtemplateid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztsubjects`
--

CREATE TABLE IF NOT EXISTS `kkztsubjects` (
  `subjectid`           INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_name`        VARCHAR(1024)    NOT NULL DEFAULT '',
  `subject_description` VARCHAR(1024)    NOT NULL DEFAULT '',
  PRIMARY KEY (`subjectid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkzttests`
--

CREATE TABLE IF NOT EXISTS `kkzttests` (
  `testid`                    INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `subjectid`                 INT(10) UNSIGNED    NOT NULL DEFAULT '1',
  `rtemplateid`               INT(10) UNSIGNED    NOT NULL DEFAULT '1',
  `result_etemplateid`        INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `gscaleid`                  INT(10) UNSIGNED    NOT NULL DEFAULT '1',
  `test_name`                 VARCHAR(1024)       NOT NULL DEFAULT '',
  `test_description`          VARCHAR(2048)       NOT NULL DEFAULT '',
  `test_instructions`         TEXT                NOT NULL,
  `test_time`                 INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `test_timeforceout`         TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_timingq`              TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_attempts`             INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `test_shuffleq`             TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_shufflea`             TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_sectionstype`         INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `test_qsperpage`            TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  `test_showqfeedback`        TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_result_showanswers`   TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_result_showpoints`    TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  `test_result_showgrade`     TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_result_showpdf`       TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_reportgradecondition` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_result_email`         VARBINARY(255)      NOT NULL DEFAULT '',
  `test_result_emailtouser`   TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_datestart`            INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `test_dateend`              INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `test_notes`                TEXT                NOT NULL,
  `test_forall`               TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `test_createdate`           INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `test_enabled`              TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  `test_internet`             TINYINT(1)          NOT NULL DEFAULT '0',
  PRIMARY KEY (`testid`),
  KEY `subjectid` (`subjectid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkzttests_attempts`
--

CREATE TABLE IF NOT EXISTS `kkzttests_attempts` (
  `testid`             INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `userid`             INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `test_attempt_count` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`testid`, `userid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkzttests_questions`
--

CREATE TABLE IF NOT EXISTS `kkzttests_questions` (
  `test_questionid` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `testid`          INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `questionid`      INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`testid`, `test_questionid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztusers`
--

CREATE TABLE IF NOT EXISTS `kkztusers` (
  `userid`           INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `user_name`        VARCHAR(512)        NOT NULL DEFAULT '',
  `user_passhash`    VARBINARY(32)       NOT NULL DEFAULT '',
  `user_email`       VARCHAR(512)        NOT NULL DEFAULT '',
  `user_title`       VARBINARY(32)       NOT NULL DEFAULT '',
  `user_firstname`   VARCHAR(512)        NOT NULL DEFAULT '',
  `user_lastname`    VARCHAR(512)        NOT NULL DEFAULT '',
  `user_middlename`  VARCHAR(512)        NOT NULL DEFAULT '',
  `user_address`     BLOB                NOT NULL,
  `user_city`        VARBINARY(255)      NOT NULL DEFAULT '',
  `user_state`       VARBINARY(255)      NOT NULL DEFAULT '',
  `user_zip`         VARBINARY(255)      NOT NULL DEFAULT '',
  `user_country`     VARBINARY(255)      NOT NULL DEFAULT '',
  `user_phone`       VARBINARY(255)      NOT NULL DEFAULT '',
  `user_fax`         VARBINARY(255)      NOT NULL DEFAULT '',
  `user_mobile`      VARBINARY(255)      NOT NULL DEFAULT '',
  `user_pager`       VARBINARY(255)      NOT NULL DEFAULT '',
  `user_ipphone`     VARBINARY(255)      NOT NULL DEFAULT '',
  `user_webpage`     VARBINARY(255)      NOT NULL DEFAULT '',
  `user_icq`         VARBINARY(255)      NOT NULL DEFAULT '',
  `user_msn`         VARBINARY(255)      NOT NULL DEFAULT '',
  `user_aol`         VARBINARY(255)      NOT NULL DEFAULT '',
  `user_gender`      TINYINT(4)          NOT NULL DEFAULT '0',
  `user_birthday`    DATETIME DEFAULT NULL,
  `user_husbandwife` VARBINARY(255)      NOT NULL DEFAULT '',
  `user_children`    VARBINARY(255)      NOT NULL DEFAULT '',
  `user_trainer`     VARBINARY(255)      NOT NULL DEFAULT '',
  `user_photo`       VARBINARY(255)      NOT NULL DEFAULT '',
  `user_company`     VARBINARY(255)      NOT NULL DEFAULT '',
  `user_cposition`   VARBINARY(255)      NOT NULL DEFAULT '',
  `user_department`  VARBINARY(255)      NOT NULL DEFAULT '',
  `user_coffice`     VARBINARY(255)      NOT NULL DEFAULT '',
  `user_caddress`    BLOB                NOT NULL,
  `user_ccity`       VARBINARY(255)      NOT NULL DEFAULT '',
  `user_cstate`      VARBINARY(255)      NOT NULL DEFAULT '',
  `user_czip`        VARBINARY(255)      NOT NULL DEFAULT '',
  `user_ccountry`    VARBINARY(255)      NOT NULL DEFAULT '',
  `user_cphone`      VARBINARY(255)      NOT NULL DEFAULT '',
  `user_cfax`        VARBINARY(255)      NOT NULL DEFAULT '',
  `user_cmobile`     VARBINARY(255)      NOT NULL DEFAULT '',
  `user_cpager`      VARBINARY(255)      NOT NULL DEFAULT '',
  `user_cipphone`    VARBINARY(255)      NOT NULL DEFAULT '',
  `user_cwebpage`    VARBINARY(255)      NOT NULL DEFAULT '',
  `user_cphoto`      VARBINARY(255)      NOT NULL DEFAULT '',
  `user_ufield1`     BLOB                NOT NULL,
  `user_ufield2`     BLOB                NOT NULL,
  `user_ufield3`     BLOB                NOT NULL,
  `user_ufield4`     BLOB                NOT NULL,
  `user_ufield5`     BLOB                NOT NULL,
  `user_ufield6`     BLOB                NOT NULL,
  `user_ufield7`     BLOB                NOT NULL,
  `user_ufield8`     BLOB                NOT NULL,
  `user_ufield9`     BLOB                NOT NULL,
  `user_ufield10`    BLOB                NOT NULL,
  `user_notes`       BLOB                NOT NULL,
  `user_joindate`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `user_logindate`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `user_expiredate`  INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `user_enabled`     TINYINT(3) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`userid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `kkztvisitors`
--

CREATE TABLE IF NOT EXISTS `kkztvisitors` (
  `visitorid` INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `startdate` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `enddate`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `userid`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `ip1`       TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `ip2`       TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `ip3`       TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `ip4`       TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `host`      VARBINARY(100)      NOT NULL DEFAULT '',
  `referer`   VARBINARY(255)      NOT NULL DEFAULT '',
  `useragent` VARBINARY(255)      NOT NULL DEFAULT '',
  `hits`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `inurl`     VARBINARY(255)      NOT NULL DEFAULT '',
  `outurl`    VARBINARY(255)      NOT NULL DEFAULT '',
  PRIMARY KEY (`visitorid`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

--
-- Структура таблицы `kkztconfig`
--

CREATE TABLE IF NOT EXISTS `kkztusersessions` (
  `id`     INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `testid` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
)
  ENGINE =MyISAM
  DEFAULT CHARSET =utf8;

/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;

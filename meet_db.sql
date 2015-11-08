-- phpMyAdmin SQL Dump
-- version 4.0.10.10
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 08 2015 г., 22:52
-- Версия сервера: 5.5.45
-- Версия PHP: 5.4.44

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `meet_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `mt_events`
--

CREATE TABLE IF NOT EXISTS `mt_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator` int(11) NOT NULL,
  `date_create` date NOT NULL,
  `date_event` date NOT NULL,
  `event_description` varchar(250) NOT NULL,
  `event_data` text NOT NULL,
  `event_place` varchar(250) NOT NULL,
  `event_title` varchar(250) NOT NULL,
  `event_participants` int(11) NOT NULL DEFAULT '0',
  `event_cover` varchar(250) NOT NULL,
  `event_full_cover` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `creater` (`creator`,`date_create`,`date_event`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `mt_events`
--

INSERT INTO `mt_events` (`id`, `creator`, `date_create`, `date_event`, `event_description`, `event_data`, `event_place`, `event_title`, `event_participants`, `event_cover`, `event_full_cover`) VALUES
(1, 3, '2015-11-08', '2015-11-10', 'встречаемся в полях!', 'встречаемся в полях, берем трактор и пашем, пашем', 'гдето в полях', 'Тестовая встреча', 2, 'storage/events/event1/thumbs_cover.jpg', 'storage/events/event1/cover.jpg'),
(2, 1, '2015-11-08', '2015-11-27', 'какой то анонс', 'Описанеи тут', 'на мосту', 'Тестовая встреча 2', 2, 'storage/events/event2/thumbs_cover.jpg', 'storage/events/event2/cover.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `mt_participants`
--

CREATE TABLE IF NOT EXISTS `mt_participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`,`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `mt_participants`
--

INSERT INTO `mt_participants` (`id`, `event_id`, `user_id`, `user`) VALUES
(1, 1, 3, 'user'),
(2, 2, 1, 'warriorcat'),
(3, 1, 1, 'warriorcat'),
(4, 2, 3, 'user');

-- --------------------------------------------------------

--
-- Структура таблицы `mt_users`
--

CREATE TABLE IF NOT EXISTS `mt_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `mt_users`
--

INSERT INTO `mt_users` (`id`, `login`, `password`, `email`, `role`) VALUES
(1, 'warriorcat', 'dc78f80619bbf8b9d326da439fcb4337', 'wldct@inbox.ru', 'admin'),
(2, 'admin', '692658e1532af4e77338e8eec88b3e8b', 'admin@event.loc', 'admin'),
(3, 'user', 'bce1c71adcaa6722ea8b67fc12c78ddf', 'user@event.loc', 'user');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

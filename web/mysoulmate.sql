-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 11, 2018 at 11:51 PM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mysoulmate`
--

-- --------------------------------------------------------

--
-- Table structure for table `accepted_choice`
--

DROP TABLE IF EXISTS `accepted_choice`;
CREATE TABLE IF NOT EXISTS `accepted_choice` (
  `choice_id` int(11) NOT NULL,
  `answer_id` int(11) NOT NULL,
  PRIMARY KEY (`answer_id`,`choice_id`),
  KEY `IDX_3943E542998666D1` (`choice_id`),
  KEY `IDX_3943E542AA334807` (`answer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `accepted_choice`
--

INSERT INTO `accepted_choice` (`choice_id`, `answer_id`) VALUES
(17, 148),
(17, 165),
(17, 183),
(18, 148),
(18, 165),
(18, 183),
(24, 138),
(24, 163),
(24, 175),
(25, 163),
(25, 175),
(26, 138),
(34, 149),
(34, 180),
(37, 147),
(38, 147),
(38, 161),
(38, 170),
(39, 145),
(39, 161),
(39, 170),
(46, 156),
(46, 164),
(46, 172),
(46, 182),
(47, 164),
(47, 172),
(47, 182),
(64, 146),
(64, 185),
(65, 146),
(65, 154),
(65, 185),
(66, 154),
(67, 153),
(67, 160),
(68, 160),
(69, 153),
(72, 166),
(72, 174),
(73, 144),
(73, 174),
(86, 167),
(86, 179),
(87, 140),
(87, 157),
(87, 167),
(89, 152),
(89, 171),
(89, 186),
(94, 141),
(94, 150),
(94, 162),
(94, 169),
(94, 181),
(95, 141),
(95, 150),
(95, 162),
(98, 143),
(98, 159),
(99, 168),
(99, 177),
(104, 139),
(104, 151),
(104, 178),
(105, 151),
(105, 158),
(105, 173),
(116, 142),
(116, 184),
(117, 142),
(117, 176),
(117, 184),
(120, 137),
(120, 155);

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `longitude` double DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `longitude`, `latitude`, `country`, `city`) VALUES
(1, 54.35, 21.24, 'Tunisia', 'Chebba'),
(2, NULL, NULL, 'Tunisia', 'Sousse'),
(3, NULL, NULL, 'Tunisia', 'Sousse'),
(4, 10.1852837, 36.8998328, 'Tunisia', 'Unnamed Road, Cebalat Ben Ammar'),
(5, NULL, NULL, NULL, NULL),
(6, 10.182, 36.806, 'Tunisia', 'Tunis'),
(7, 10.634584, 35.8245029, 'Tunisia', 'Sousse'),
(8, 10.310501, 36.8964803, 'Tunisia', 'La Marsa'),
(9, 10.1815316, 36.8064948, 'Tunisia', 'Tunis'),
(10, 10.1647233, 36.8665367, 'Tunisia', 'Ariana'),
(11, 10.8112885, 35.7642515, 'Tunisia', 'Monastir'),
(12, 10.0863269, 36.8093284, 'Tunisia', 'Manouba'),
(13, 11.1107854, 35.23369, 'Tunisia', 'Chebba'),
(14, 9.9926232, 34.2150397, 'Tunisia', 'Bou Said'),
(15, 10.7600196, 34.739822, 'Tunisia', 'Sfax'),
(16, NULL, NULL, NULL, NULL),
(17, 10.165, 36.867, 'Tunisia', 'Ariana'),
(18, 10.311, 36.896, 'Tunisia', 'La Marsa');

-- --------------------------------------------------------

--
-- Table structure for table `advert`
--

DROP TABLE IF EXISTS `advert`;
CREATE TABLE IF NOT EXISTS `advert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `business_id` int(11) DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `photo_url` text COLLATE utf8_unicode_ci,
  `video_url` text COLLATE utf8_unicode_ci,
  `reason` text COLLATE utf8_unicode_ci,
  `state` int(11) DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `clicks` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `payed` int(11) DEFAULT NULL,
  `position` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `business_id` (`business_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `advert`
--

INSERT INTO `advert` (`id`, `business_id`, `content`, `photo_url`, `video_url`, `reason`, `state`, `end_date`, `clicks`, `price`, `payed`, `position`) VALUES
(3, 11, 'Get going!', 'ae3ce4fe30b3d40b6f56f5b78bcfc2e5.jpeg', '22', 'cool', 1, '2018-04-13 00:00:00', 0, 14, 1, 2),
(4, 11, 'Free Drinks', '302872bfbf8c8796ac3ba764f19c125a.jpeg', '11', 'nice', 1, '2018-04-13 00:00:00', 0, 8, 1, 3),
(5, 11, 'Eat!', '2e8cef0b6f6f1feb2a2e9e5809072431.jpeg', 'd', 'nice', 1, '2018-04-17 00:00:00', 0, 42, 1, 2),
(6, 11, 'Pizza', '6f5591f60c974325a88094cd9cd71a10.jpeg', 'd', 'nice', 1, '2018-04-16 00:00:00', 0, 50, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `answer`
--

DROP TABLE IF EXISTS `answer`;
CREATE TABLE IF NOT EXISTS `answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `selected_choice_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `importance` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `user_id` (`user_id`),
  KEY `choice_id` (`selected_choice_id`),
  KEY `fk_answer_choice_id` (`selected_choice_id`,`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `answer`
--

INSERT INTO `answer` (`id`, `selected_choice_id`, `question_id`, `user_id`, `importance`, `date`) VALUES
(137, 120, 47, 6, 1, '2018-04-11 21:13:03'),
(138, 24, 11, 6, 1, '2018-04-11 21:13:21'),
(139, 105, 41, 6, 2, '2018-04-11 21:13:31'),
(140, 86, 33, 6, 1, '2018-04-11 21:13:39'),
(141, 95, 37, 6, 1, '2018-04-11 21:13:48'),
(142, 118, 46, 6, 2, '2018-04-11 21:14:04'),
(143, 98, 39, 6, 2, '2018-04-11 21:14:13'),
(144, 73, 27, 6, 1, '2018-04-11 21:14:21'),
(145, 38, 16, 6, 0, '2018-04-11 21:14:29'),
(146, 66, 24, 6, 1, '2018-04-11 21:14:37'),
(147, 38, 16, 7, 1, '2018-04-11 21:21:46'),
(148, 17, 8, 7, 1, '2018-04-11 21:21:54'),
(149, 33, 14, 7, 2, '2018-04-11 21:22:02'),
(150, 95, 37, 7, 2, '2018-04-11 21:22:10'),
(151, 104, 41, 7, 1, '2018-04-11 21:22:18'),
(152, 88, 34, 7, 2, '2018-04-11 21:22:26'),
(153, 69, 25, 7, 1, '2018-04-11 21:23:56'),
(154, 65, 24, 7, 1, '2018-04-11 21:24:05'),
(155, 120, 47, 7, 1, '2018-04-11 21:24:13'),
(156, 48, 19, 7, 1, '2018-04-11 21:24:22'),
(157, 87, 33, 8, 1, '2018-04-11 21:34:47'),
(158, 105, 41, 8, 1, '2018-04-11 21:34:55'),
(159, 99, 39, 8, 2, '2018-04-11 21:35:04'),
(160, 68, 25, 8, 2, '2018-04-11 21:35:23'),
(161, 38, 16, 8, 1, '2018-04-11 21:35:46'),
(162, 94, 37, 8, 2, '2018-04-11 21:35:56'),
(163, 26, 11, 8, 1, '2018-04-11 21:36:13'),
(164, 48, 19, 8, 1, '2018-04-11 21:36:30'),
(165, 18, 8, 8, 1, '2018-04-11 21:36:49'),
(166, 73, 27, 8, 2, '2018-04-11 21:37:09'),
(167, 87, 33, 9, 2, '2018-04-11 21:46:10'),
(168, 99, 39, 9, 1, '2018-04-11 21:46:18'),
(169, 94, 37, 9, 1, '2018-04-11 21:46:37'),
(170, 38, 16, 9, 1, '2018-04-11 21:47:00'),
(171, 89, 34, 9, 1, '2018-04-11 21:47:27'),
(172, 46, 19, 9, 1, '2018-04-11 21:47:55'),
(173, 105, 41, 9, 2, '2018-04-11 21:48:28'),
(174, 73, 27, 9, 1, '2018-04-11 21:49:02'),
(175, 26, 11, 9, 2, '2018-04-11 21:49:37'),
(176, 116, 46, 9, 2, '2018-04-11 21:50:17'),
(177, 99, 39, 10, 1, '2018-04-11 21:57:55'),
(178, 105, 41, 10, 2, '2018-04-11 21:58:07'),
(179, 86, 33, 10, 2, '2018-04-11 21:58:17'),
(180, 33, 14, 10, 1, '2018-04-11 21:58:29'),
(181, 94, 37, 10, 2, '2018-04-11 21:58:58'),
(182, 47, 19, 10, 1, '2018-04-11 21:59:33'),
(183, 17, 8, 10, 2, '2018-04-11 22:00:21'),
(184, 116, 46, 10, 2, '2018-04-11 22:01:18'),
(185, 65, 24, 10, 1, '2018-04-11 22:02:16'),
(186, 89, 34, 10, 1, '2018-04-11 22:03:13');

-- --------------------------------------------------------

--
-- Table structure for table `choice`
--

DROP TABLE IF EXISTS `choice`;
CREATE TABLE IF NOT EXISTS `choice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL,
  `choice` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_choice` (`question_id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `choice`
--

INSERT INTO `choice` (`id`, `question_id`, `choice`) VALUES
(16, 8, 'Often'),
(17, 8, 'Sometimes'),
(18, 8, 'Rarely'),
(19, 8, 'Never'),
(20, 9, 'Yes'),
(21, 9, 'No'),
(22, 10, 'Kissing in Paris'),
(23, 10, 'Kissing in a tent, in the woods'),
(24, 11, 'Yes, I have a fulltime job'),
(25, 11, 'No'),
(26, 11, 'I have a part-time job'),
(27, 11, 'I\'m a student'),
(28, 12, 'Yes'),
(29, 12, 'No'),
(30, 13, 'es, it\'s not a problem for me'),
(31, 13, 'Yes, if I thought the debt were justified'),
(32, 13, 'No'),
(33, 14, 'Yes'),
(34, 14, 'No'),
(37, 16, 'Yes'),
(38, 16, 'No'),
(39, 16, 'Rarely'),
(40, 17, 'Liberal / Left-wing'),
(41, 17, 'Centrist'),
(42, 17, 'Conservative / Right-wing'),
(43, 17, 'Other'),
(44, 18, 'Yes'),
(45, 18, 'No'),
(46, 19, 'Extremely important'),
(47, 19, 'Somewhat important'),
(48, 19, 'Not very important'),
(49, 19, 'Not important at all'),
(50, 20, 'Carefree'),
(51, 20, 'Intense'),
(52, 21, 'One night'),
(53, 21, 'A few months to a year'),
(54, 21, 'Several years'),
(55, 21, 'The rest of my life'),
(56, 22, 'Yes, usually'),
(57, 22, 'About half the time'),
(58, 22, 'No, usually'),
(59, 22, 'I don\'t know'),
(60, 23, 'Very, very high'),
(61, 23, 'Higher than average'),
(62, 23, 'Average'),
(63, 23, 'Below average'),
(64, 24, 'Yes, absolutely !'),
(65, 24, 'Not really'),
(66, 24, 'No way !'),
(67, 25, 'Very important'),
(68, 25, 'Somewhat important'),
(69, 25, 'Not important at all'),
(70, 26, 'Passion'),
(71, 26, 'Dedication'),
(72, 27, 'Yes'),
(73, 27, 'No'),
(74, 28, 'Yes'),
(75, 28, 'No'),
(76, 29, 'Yes'),
(77, 29, 'No'),
(78, 29, 'Yes, to some extent'),
(79, 30, 'Yes'),
(80, 30, 'No'),
(81, 31, 'Yes'),
(82, 31, 'No'),
(83, 32, 'Logical'),
(84, 32, 'Social'),
(85, 32, 'Artistic'),
(86, 33, 'Yes'),
(87, 33, 'No'),
(88, 34, 'Yes'),
(89, 34, 'No'),
(90, 35, 'Yes'),
(91, 35, 'No'),
(92, 36, 'Yes'),
(93, 36, 'No'),
(94, 37, 'Yes'),
(95, 37, 'No'),
(96, 38, 'Good'),
(97, 38, 'Bad'),
(98, 39, 'Yes'),
(99, 39, 'No'),
(100, 40, 'Always'),
(101, 40, 'Usually'),
(102, 40, 'Rarely'),
(103, 40, 'Never'),
(104, 41, 'Yes'),
(105, 41, 'No'),
(110, 44, 'Always'),
(111, 44, 'Sometimes, but I do my best not to be'),
(112, 44, 'Never'),
(113, 45, 'Yes'),
(114, 45, 'No'),
(115, 46, 'Love'),
(116, 46, 'Wealth'),
(117, 46, 'Expression'),
(118, 46, 'Knowledge'),
(119, 47, 'Yes'),
(120, 47, 'No'),
(121, 48, 'Split the bill'),
(122, 48, 'Pay the whole bill'),
(123, 48, 'Have them pay the whole bill'),
(124, 48, 'It doesn\'t matter to me'),
(129, 50, 'Universal'),
(130, 50, 'Relative'),
(131, 51, 'Yes'),
(132, 51, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiver_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `post_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `post_id` (`post_id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `receiver_id`, `sender_id`, `post_id`, `photo_id`, `content`, `date`) VALUES
(31, 6, 6, 48, 0, 'haha', '2018-04-11 21:18:30'),
(32, 8, 8, 48, 0, 'yes it is :D', '2018-04-11 21:41:55'),
(33, 9, 9, 48, 0, 'Let\'s go out !', '2018-04-11 21:54:03'),
(34, 9, 9, 0, 6, 'looking handsome', '2018-04-11 21:54:26'),
(35, 10, 10, 0, 6, ':D', '2018-04-11 22:06:30'),
(36, 10, 10, 52, 0, 'Camping', '2018-04-11 22:07:07'),
(37, 6, 6, 51, 0, 'sup', '2018-04-11 22:10:19'),
(38, 6, 6, 50, 0, 'thats a weird question', '2018-04-11 22:10:40'),
(39, 6, 6, 48, 0, 'yea i\'m up for it ', '2018-04-11 22:11:15'),
(40, 6, 6, 0, 6, 'thx ladies', '2018-04-11 22:11:24'),
(41, 7, 7, 52, 0, 'fishing haha', '2018-04-11 22:12:54'),
(42, 7, 7, 0, 10, 'so beautiful', '2018-04-11 22:13:06'),
(43, 7, 7, 50, 0, 'lol', '2018-04-11 22:13:15'),
(44, 7, 7, 0, 8, 'wow', '2018-04-11 22:13:39');

-- --------------------------------------------------------

--
-- Table structure for table `conversation`
--

DROP TABLE IF EXISTS `conversation`;
CREATE TABLE IF NOT EXISTS `conversation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person1_id` int(11) DEFAULT NULL,
  `person2_id` int(11) DEFAULT NULL,
  `label` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `seen` tinyint(1) DEFAULT NULL,
  `seen_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `person1_id` (`person1_id`),
  KEY `person2_id` (`person2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address_id` int(11) DEFAULT NULL,
  `business_id` int(11) DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8_unicode_ci,
  `state` int(11) DEFAULT NULL,
  `photo_url` text COLLATE utf8_unicode_ci,
  `max_places` int(11) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `business_id` (`business_id`),
  KEY `address_id` (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `address_id`, `business_id`, `content`, `title`, `reason`, `state`, `photo_url`, `max_places`, `start_date`, `end_date`) VALUES
(2, 17, 11, 'All you can eat', 'Free for All !', 'cool', 1, '0368c4dddb349d7fd34a3235fe1f9acc.jpeg', 10, '2018-04-11 22:25:00', '2018-04-15 00:00:00'),
(3, 18, 11, 'Traditional sweets', 'Candy Time', 'nice', 1, '70e47c33e602c148a4bedc0e22e74050.jpeg', 20, '2018-04-11 22:26:00', '2018-05-17 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

DROP TABLE IF EXISTS `experience`;
CREATE TABLE IF NOT EXISTS `experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `place_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `date` datetime DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `address_id` (`address_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`id`, `address_id`, `user_id`, `place_name`, `content`, `date`, `image`) VALUES
(2, 8, 6, 'Omy Mna Pastryshop', 'Very delicious pastry, and very clean. And staff was very kind :)', '2018-04-11 22:15:00', '5ace7b4bbbfe4.jpg'),
(3, 10, 7, 'Cascada Restaurant', 'Good stuff, very clean and delicious food. Especially roasted chickens. Service is fast.', '2018-04-11 22:25:00', '5ace7dbbcf0df.jpeg'),
(4, 12, 8, 'Papa Johns', 'Average pizza and very slow service...', '2018-04-11 22:38:00', '5ace80b31e56b.jpg'),
(5, 14, 9, 'Sidi Azizi Cafe', 'A warm place, suitable for families and friends, I had a very good experience going there, tea, cafe and jus was good, and the Chiche was Ok.', '2018-04-11 22:51:00', '5ace83a8a09a5.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `state` tinyint(1) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `sender_id`, `content`, `state`, `date`) VALUES
(1, 6, 'Bonjour ! J\'ai un problème au niveau de mon fil d’actualité, il ne s\'affiche pas correctement, fallait trouver une solution ! merci en avance.', 0, '2018-04-11 23:47:22'),
(2, 8, 'J\'ai un problème au niveau de la messagerie, je ne peux ni envoyer ni recevoir des messages de la part de mes amis, il faut trouver une solution..', 0, '2018-04-11 23:48:42'),
(3, 7, 'J\'ai un problème dans mon compte, je ne peux ni faire un \'Like\' ni recevoir des \'Likes\' de la part d\'autres personnes..', 0, '2018-04-11 23:49:23');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiver_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `seen` tinyint(1) DEFAULT NULL,
  `seen_date` datetime DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `msg`
--

DROP TABLE IF EXISTS `msg`;
CREATE TABLE IF NOT EXISTS `msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_688A5FAFE2904019` (`thread_id`),
  KEY `IDX_688A5FAFF624B39D` (`sender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `msg`
--

INSERT INTO `msg` (`id`, `thread_id`, `sender_id`, `body`, `created_at`) VALUES
(149, 3, 8, 'Hello', '2018-04-11 21:41:18'),
(150, 3, 8, 'Let\'s talk', '2018-04-11 21:41:26'),
(151, 4, 9, 'Yo, you up for some chatting ?', '2018-04-11 21:54:46'),
(152, 5, 10, 'Hey', '2018-04-11 22:05:56'),
(153, 5, 6, 'hi', '2018-04-11 22:08:10'),
(154, 4, 6, 'yes i am ! ', '2018-04-11 22:08:23'),
(155, 4, 6, 'what\'s up ?', '2018-04-11 22:08:29'),
(156, 3, 6, 'Hey', '2018-04-11 22:08:40'),
(157, 3, 6, 'How are you doing today ?', '2018-04-11 22:08:48'),
(158, 6, 7, 'nice day isn\'t it ', '2018-04-11 22:14:20'),
(159, 7, 7, 'you have such beautiful skin', '2018-04-11 22:14:40');

-- --------------------------------------------------------

--
-- Table structure for table `msg_metadata`
--

DROP TABLE IF EXISTS `msg_metadata`;
CREATE TABLE IF NOT EXISTS `msg_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) DEFAULT NULL,
  `participant_id` int(11) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1E0EE5A8537A1329` (`message_id`),
  KEY `IDX_1E0EE5A89D1C3019` (`participant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `msg_metadata`
--

INSERT INTO `msg_metadata` (`id`, `message_id`, `participant_id`, `is_read`) VALUES
(1, 149, 6, 1),
(2, 149, 8, 1),
(3, 150, 6, 1),
(4, 150, 8, 1),
(5, 151, 6, 1),
(6, 151, 9, 1),
(7, 152, 6, 1),
(8, 152, 10, 1),
(9, 153, 6, 1),
(10, 153, 10, 0),
(11, 154, 6, 1),
(12, 154, 9, 0),
(13, 155, 6, 1),
(14, 155, 9, 0),
(15, 156, 6, 1),
(16, 156, 8, 0),
(17, 157, 6, 1),
(18, 157, 8, 0),
(19, 158, 8, 0),
(20, 158, 7, 1),
(21, 159, 10, 0),
(22, 159, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiver_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `date` datetime DEFAULT NULL,
  `icon` text COLLATE utf8_unicode_ci,
  `photo_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `seen` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  KEY `photo_id` (`photo_id`,`post_id`),
  KEY `answer_notification` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participant`
--

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`event_id`),
  KEY `IDX_D79F6B1171F7E88B` (`event_id`),
  KEY `IDX_D79F6B11A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `type` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` (`id`, `user_id`, `date`, `type`, `image`) VALUES
(6, 6, '2018-04-11 22:15:29', 1, '5ace7af167996.png'),
(7, 7, '2018-04-11 22:24:47', 1, '5ace7d1f5084b.png'),
(8, 8, '2018-04-11 22:37:37', 1, '5ace8021db29b.png'),
(9, 9, '2018-04-11 22:51:10', 1, '5ace834e9471a.png'),
(10, 10, '2018-04-11 23:05:03', 1, '5ace868fea817.png');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `user_id`, `date`, `content`) VALUES
(48, 6, '2018-04-11 21:17:48', 'It\'s a beautiful day today :D'),
(49, 7, '2018-04-11 21:28:26', 'Anyone up for a chat ?'),
(50, 8, '2018-04-11 21:42:22', 'What do you think of Christmas ?'),
(51, 9, '2018-04-11 21:53:45', 'Yahoo !'),
(52, 10, '2018-04-11 22:06:57', 'What hobbies do you have ?');

-- --------------------------------------------------------

--
-- Table structure for table `post_reaction`
--

DROP TABLE IF EXISTS `post_reaction`;
CREATE TABLE IF NOT EXISTS `post_reaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `photo_id` int(11) DEFAULT NULL,
  `experience_id` int(11) DEFAULT NULL,
  `reaction` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_reaction` (`post_id`),
  KEY `fk_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `post_reaction`
--

INSERT INTO `post_reaction` (`id`, `user_id`, `post_id`, `photo_id`, `experience_id`, `reaction`) VALUES
(28, 8, 48, 0, 0, 3),
(29, 8, 0, 6, 0, 2),
(30, 9, 48, 0, 0, 1),
(31, 9, 0, 6, 0, 3),
(32, 10, 48, 0, 0, 2),
(38, 7, 50, 0, 0, 1),
(39, 7, 0, 8, 0, 3),
(45, 6, 51, 0, 0, 3),
(46, 6, 50, 0, 0, 3),
(47, 6, 0, 9, 0, 3),
(48, 6, 0, 8, 0, 1),
(49, 7, 52, 0, 0, 1),
(50, 7, 0, 10, 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `prefered_relation`
--

DROP TABLE IF EXISTS `prefered_relation`;
CREATE TABLE IF NOT EXISTS `prefered_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `relation` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `prefered_status`
--

DROP TABLE IF EXISTS `prefered_status`;
CREATE TABLE IF NOT EXISTS `prefered_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `promotion`
--

DROP TABLE IF EXISTS `promotion`;
CREATE TABLE IF NOT EXISTS `promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `endDate` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `promotion`
--

INSERT INTO `promotion` (`id`, `name`, `code`, `endDate`) VALUES
(1, 'p1', 'FucVY6V', '2018-04-14'),
(2, 'p2', 'Ag38qZc', '2018-04-15');

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

DROP TABLE IF EXISTS `question`;
CREATE TABLE IF NOT EXISTS `question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `topic` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `question`, `topic`) VALUES
(8, 'How frequently do you drink alcohol?', 5),
(9, 'Could you date someone who was really messy?', 1),
(10, 'Choose the better romantic activity', 1),
(11, 'Are you currently employed?', 5),
(12, 'Is jealousy healthy in a relationship?', 1),
(13, 'Would you date someone who was in considerable debt?', 1),
(14, 'Would you strongly prefer to date someone of your own skin color / racial background?', 5),
(16, 'Do you smoke?', 5),
(17, 'Which best describes your political beliefs?', 4),
(18, 'Do you enjoy discussing politics?', 4),
(19, 'How important is religion/God in your life?', 5),
(20, 'Which word describes you better?', 4),
(21, 'About how long do you want your next relationship to last?', 1),
(22, 'Can you tell from someone’s online dating profile whether or not you’ll get along in real life?', 1),
(23, 'Rate your self-confidence:', 4),
(24, 'Are pets a neccessity in your life ?', 5),
(25, 'How important is money/wealth for you in a match?', 5),
(26, 'Which makes for a better relationship?', 1),
(27, 'Do you enjoy intense intellectual conversations?', 5),
(28, 'Do you follow movies/shows ?', 4),
(29, 'Would you consider yourself a feminist?', 4),
(30, 'Do spelling mistakes annoy you?', 4),
(31, 'Do you often find yourself worrying about things that you have no control over?', 4),
(32, 'Which of the following types of intelligence do you value most?', 1),
(33, 'Do you take drugs ?', 5),
(34, 'Are you either vegetarian or vegan?', 5),
(35, 'Should religion be taught is schools ?', 2),
(36, 'Do you believe in the woman\'s right for abortion ?', 2),
(37, 'Do you ok with sex before marriage ?', 5),
(38, 'If you don\'t do anything at all for an entire day, how does that make you feel?', 4),
(39, 'Are you looking for marriage ?', 5),
(40, 'How often are you open with your feelings?', 4),
(41, 'Could you date someone who was really quiet?', 5),
(44, 'Are you usually late for meetings ?', 4),
(45, 'Are you a morning person ?', 4),
(46, 'If you had to name your greatest motivation in life thus far, what would it be?', 5),
(47, 'Do you think homosexuality is a sin?', 5),
(48, 'It’s your first date. Do you ...', 1),
(50, 'Do you believe morality is universal, or relative?', 2),
(51, 'Do you read books ?', 4);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

DROP TABLE IF EXISTS `rating`;
CREATE TABLE IF NOT EXISTS `rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rating` int(11) DEFAULT NULL,
  `idE` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thread`
--

DROP TABLE IF EXISTS `thread`;
CREATE TABLE IF NOT EXISTS `thread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_by_id` int(11) DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `is_spam` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_31204C83B03A8386` (`created_by_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `thread`
--

INSERT INTO `thread` (`id`, `created_by_id`, `subject`, `created_at`, `is_spam`) VALUES
(3, 8, 'hi', '2018-04-11 21:41:18', 0),
(4, 9, 'hi', '2018-04-11 21:54:46', 0),
(5, 10, 'hi', '2018-04-11 22:05:56', 0),
(6, 7, 'hi', '2018-04-11 22:14:20', 0),
(7, 7, 'hi', '2018-04-11 22:14:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `thread_metadata`
--

DROP TABLE IF EXISTS `thread_metadata`;
CREATE TABLE IF NOT EXISTS `thread_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) DEFAULT NULL,
  `participant_id` int(11) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `last_participant_message_date` datetime DEFAULT NULL,
  `last_message_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_40A577C8E2904019` (`thread_id`),
  KEY `IDX_40A577C89D1C3019` (`participant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `thread_metadata`
--

INSERT INTO `thread_metadata` (`id`, `thread_id`, `participant_id`, `is_deleted`, `last_participant_message_date`, `last_message_date`) VALUES
(5, 3, 6, 0, '2018-04-11 22:08:48', '2018-04-11 21:41:26'),
(6, 3, 8, 0, '2018-04-11 21:41:26', '2018-04-11 22:08:48'),
(7, 4, 6, 0, '2018-04-11 22:08:29', '2018-04-11 21:54:46'),
(8, 4, 9, 0, '2018-04-11 21:54:46', '2018-04-11 22:08:29'),
(9, 5, 6, 0, '2018-04-11 22:08:10', '2018-04-11 22:05:56'),
(10, 5, 10, 0, '2018-04-11 22:05:56', '2018-04-11 22:08:10'),
(11, 6, 8, 0, NULL, '2018-04-11 22:14:20'),
(12, 6, 7, 0, '2018-04-11 22:14:20', NULL),
(13, 7, 10, 0, NULL, '2018-04-11 22:14:40'),
(14, 7, 7, 0, '2018-04-11 22:14:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `address_id` int(11) DEFAULT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `firstname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` tinyint(1) DEFAULT NULL,
  `height` double DEFAULT NULL,
  `body_type` int(11) DEFAULT NULL,
  `children_number` int(11) DEFAULT NULL,
  `relegion` int(11) DEFAULT NULL,
  `relegion_importance` int(11) DEFAULT NULL,
  `smoker` tinyint(1) DEFAULT NULL,
  `drinker` tinyint(1) DEFAULT NULL,
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `locked` smallint(6) DEFAULT NULL,
  `ip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `role` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `about` text COLLATE utf8_unicode_ci,
  `civil_status` int(11) DEFAULT NULL,
  `connected` tinyint(1) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `price_range` int(11) DEFAULT NULL,
  `link` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`),
  UNIQUE KEY `UNIQ_8D93D649C05FB297` (`confirmation_token`),
  KEY `address_id` (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `address_id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `firstname`, `lastname`, `birth_date`, `gender`, `height`, `body_type`, `children_number`, `relegion`, `relegion_importance`, `smoker`, `drinker`, `min_age`, `max_age`, `phone`, `locked`, `ip`, `port`, `role`, `created_at`, `updated_at`, `about`, `civil_status`, `connected`, `category`, `price_range`, `link`) VALUES
(4, NULL, 'admin', 'admin', 'admin@soulmate.com', 'admin@soulmate.com', 1, NULL, '$2y$13$Wagl3YT2AlVlggodiTDg/OC4mXDQ7RdheA.aat/HJ7bUkoUUEA82a', '2018-04-11 22:25:28', NULL, NULL, 'a:1:{i:0;s:10:\"ROLE_ADMIN\";}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 5, 'bus', 'bus', 'b@b.gmail', 'b@b.gmail', 1, NULL, '$2y$13$GB.o5wSyPtAv0GhNYQDj5uF2KsrUfm.sKNLqJgs1ql0P.XOVVxBNi', '2018-04-11 13:11:04', NULL, NULL, 'a:1:{i:0;s:13:\"ROLE_BUSINESS\";}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 12234556, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 'www.facebook.com/nessmatv.tv'),
(6, 7, 'john', 'john', 'john@gmail.com', 'john@gmail.com', 1, NULL, '$2y$13$mPMMir7FVOqrUP95fnDkWeH80R9MP97Pcd3GrwWTAHswd3ufSHHKy', '2018-04-11 22:33:04', NULL, NULL, 'a:1:{i:0;s:0:\"\";}', 'John', 'Evans', '1993-05-09', 1, 1.75, 4, 0, 0, 2, 1, 0, 18, 30, 12456578, NULL, NULL, NULL, NULL, NULL, NULL, 'I love the outdoors.', 2, NULL, NULL, NULL, NULL),
(7, 9, 'chris', 'chris', 'chris@gmail.com', 'chris@gmail.com', 1, NULL, '$2y$13$OL0lQwroEgPiZH.JTYkWRuAbf2252KY1ry8T91i6AaWxlq8ppBQfS', '2018-04-11 23:49:04', NULL, NULL, 'a:1:{i:0;s:0:\"\";}', 'Chris', 'King', '1995-09-12', 1, 1.8, 1, 2, 2, 1, 0, 1, 18, 25, 12452356, NULL, NULL, NULL, NULL, NULL, NULL, 'I love my children. I want to find someone who appreciates them like I do.', 1, NULL, NULL, NULL, NULL),
(8, 11, 'jamila', 'jamila', 'jamila@gmail.com', 'jamila@gmail.com', 1, NULL, '$2y$13$Aa53hac8Tk6pTdnwHkooEeHrvRvuhWNdIRAaoshy///8VAXW/uR2G', '2018-04-11 23:48:11', NULL, NULL, 'a:1:{i:0;s:0:\"\";}', 'Jamila', 'El Behia', '1999-05-22', 0, 1.7, 1, 0, 3, 2, 0, 0, 18, 30, 23568956, NULL, NULL, NULL, NULL, NULL, NULL, 'I love camping', 0, NULL, NULL, NULL, NULL),
(9, 13, 'lina', 'lina', 'lina@gmail.com', 'lina@gmail.com', 1, NULL, '$2y$13$LkD.8KDv1eyM1Bl2ldG9t.OC7VhkkNTD4VNEoYnYqHSS87o1ey5vq', '2018-04-11 21:45:38', NULL, NULL, 'a:1:{i:0;s:0:\"\";}', 'Lina', 'Marzouk', '1990-06-08', 0, 1.65, 3, 0, 4, 1, 0, 1, 18, 26, 23568956, NULL, NULL, NULL, NULL, NULL, NULL, 'Let\'s have fun :D', 1, NULL, NULL, NULL, NULL),
(10, 15, 'sirine', 'sirine', 'sirine@gmail.com', 'sirine@gmail.com', 1, NULL, '$2y$13$id3Cxj86CWnAUS6xNqEXq.G/1gtRx/oUaIk1BFyz7rqEiNSsTnKe.', '2018-04-11 21:57:27', NULL, NULL, 'a:1:{i:0;s:0:\"\";}', 'Sirine', 'Mbarek', '1988-02-04', 0, 1.88, 2, 0, 1, 0, 1, 0, 18, 23, 23451289, NULL, NULL, NULL, NULL, NULL, NULL, 'Let\'s hang out !', 0, NULL, NULL, NULL, NULL),
(11, 16, 'papajones', 'papajones', 'papa@gmail.com', 'papa@gmail.com', 1, NULL, '$2y$13$VFLKTIdQDyxJjHKu30kYzuu19hi772Rm9wMEUXjnMsWVXo6LFnX5O', '2018-04-11 22:18:22', NULL, NULL, 'a:1:{i:0;s:13:\"ROLE_BUSINESS\";}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 12455698, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 'https://www.facebook.com/papajohns/');

-- --------------------------------------------------------

--
-- Table structure for table `user_block`
--

DROP TABLE IF EXISTS `user_block`;
CREATE TABLE IF NOT EXISTS `user_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_receiver_id` int(11) DEFAULT NULL,
  `block_sender_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_block_receiver` (`block_receiver_id`),
  KEY `fk_user_sender_id` (`block_sender_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_like`
--

DROP TABLE IF EXISTS `user_like`;
CREATE TABLE IF NOT EXISTS `user_like` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `like_receiver_id` int(11) DEFAULT NULL,
  `like_sender_id` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_like_receiver` (`like_receiver_id`),
  KEY `fk_like_sender_id` (`like_sender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_like`
--

INSERT INTO `user_like` (`id`, `like_receiver_id`, `like_sender_id`, `date`) VALUES
(11, 6, 8, '2018-04-11 21:40:57'),
(12, 6, 9, '2018-04-11 21:53:26'),
(13, 6, 10, '2018-04-11 22:05:38'),
(14, 8, 6, '2018-04-11 22:09:59'),
(15, 9, 6, '2018-04-11 22:10:06'),
(16, 10, 7, '2018-04-11 22:12:28'),
(17, 8, 7, '2018-04-11 22:12:31');

-- --------------------------------------------------------

--
-- Table structure for table `user_signal`
--

DROP TABLE IF EXISTS `user_signal`;
CREATE TABLE IF NOT EXISTS `user_signal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `receiver_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `reason` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `state` tinyint(1) DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_signal`
--

INSERT INTO `user_signal` (`id`, `receiver_id`, `sender_id`, `reason`, `date`, `state`, `content`) VALUES
(1, 8, 6, 0, '2018-04-11 23:46:18', 0, 'Il faut trouver une solution avec cette personne qui publie des contenus inappropriés toujours contenant des expressions de racisme et de régionalité'),
(2, 9, 6, 5, '2018-04-11 23:46:49', 0, 'Le propriétaire de ce profil me gène toujours, Pourtant je ne le connais pas il m\'envoie toujours des messages et même des insultes et des expressions d\'impolitesse'),
(3, 10, 6, 1, '2018-04-11 23:47:03', 0, 'Cette personne m\'insulte avec des expressions de racisme, il faut le faire un ban pour lui empêcher d’accéder à son compte une autre fois  .');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accepted_choice`
--
ALTER TABLE `accepted_choice`
  ADD CONSTRAINT `FK_3943E542998666D1` FOREIGN KEY (`choice_id`) REFERENCES `choice` (`id`),
  ADD CONSTRAINT `FK_3943E542AA334807` FOREIGN KEY (`answer_id`) REFERENCES `answer` (`id`);

--
-- Constraints for table `advert`
--
ALTER TABLE `advert`
  ADD CONSTRAINT `FK_54F1F40BA89DB457` FOREIGN KEY (`business_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `FK_DADD4A251E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`),
  ADD CONSTRAINT `FK_DADD4A25A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_DADD4A25C1F9753A` FOREIGN KEY (`selected_choice_id`) REFERENCES `choice` (`id`);

--
-- Constraints for table `choice`
--
ALTER TABLE `choice`
  ADD CONSTRAINT `FK_C1AB5A921E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526CCD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9474526CF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `FK_8A8E26E92C402DF5` FOREIGN KEY (`person2_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8A8E26E93EF5821B` FOREIGN KEY (`person1_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `FK_3BAE0AA7A89DB457` FOREIGN KEY (`business_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_3BAE0AA7F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);

--
-- Constraints for table `experience`
--
ALTER TABLE `experience`
  ADD CONSTRAINT `FK_590C103A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_590C103F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `FK_D2294458F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307FCD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_B6BD307FF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `msg`
--
ALTER TABLE `msg`
  ADD CONSTRAINT `FK_688A5FAFE2904019` FOREIGN KEY (`thread_id`) REFERENCES `thread` (`id`),
  ADD CONSTRAINT `FK_688A5FAFF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `msg_metadata`
--
ALTER TABLE `msg_metadata`
  ADD CONSTRAINT `FK_1E0EE5A8537A1329` FOREIGN KEY (`message_id`) REFERENCES `msg` (`id`),
  ADD CONSTRAINT `FK_1E0EE5A89D1C3019` FOREIGN KEY (`participant_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `FK_BF5476CACD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_BF5476CAF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `participant`
--
ALTER TABLE `participant`
  ADD CONSTRAINT `FK_D79F6B1171F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  ADD CONSTRAINT `FK_D79F6B11A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `FK_14B78418A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_5A8A6C8DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `post_reaction`
--
ALTER TABLE `post_reaction`
  ADD CONSTRAINT `FK_1B3A8E56A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `prefered_relation`
--
ALTER TABLE `prefered_relation`
  ADD CONSTRAINT `FK_78596892A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `prefered_status`
--
ALTER TABLE `prefered_status`
  ADD CONSTRAINT `FK_80085692A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `thread`
--
ALTER TABLE `thread`
  ADD CONSTRAINT `FK_31204C83B03A8386` FOREIGN KEY (`created_by_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `thread_metadata`
--
ALTER TABLE `thread_metadata`
  ADD CONSTRAINT `FK_40A577C89D1C3019` FOREIGN KEY (`participant_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_40A577C8E2904019` FOREIGN KEY (`thread_id`) REFERENCES `thread` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);

--
-- Constraints for table `user_block`
--
ALTER TABLE `user_block`
  ADD CONSTRAINT `FK_61D96C7A175EB86D` FOREIGN KEY (`block_receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_61D96C7AC6CB3388` FOREIGN KEY (`block_sender_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_like`
--
ALTER TABLE `user_like`
  ADD CONSTRAINT `FK_D6E20C7A7E960338` FOREIGN KEY (`like_receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_D6E20C7ADE843FFF` FOREIGN KEY (`like_sender_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_signal`
--
ALTER TABLE `user_signal`
  ADD CONSTRAINT `FK_115E8EC8CD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_115E8EC8F624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

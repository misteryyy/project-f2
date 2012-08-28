-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 28, 2012 at 08:53 PM
-- Server version: 5.5.22
-- PHP Version: 5.3.10-1ubuntu3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db-flo-test`
--

-- --------------------------------------------------------

--
-- Table structure for table `launch_newsletter`
--

CREATE TABLE IF NOT EXISTS `launch_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `launch_user`
--

CREATE TABLE IF NOT EXISTS `launch_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` tinyint(1) DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3854644EE7927C74` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pitch_sentence` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `disableRoleWidget` tinyint(1) NOT NULL,
  `dir` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `issue` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lesson` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plan` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `view_count` int(11) NOT NULL,
  `picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ban` tinyint(1) NOT NULL,
  `featured` int(11) NOT NULL,
  `priority` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2FB3D0EE12469DE2` (`category_id`),
  KEY `IDX_2FB3D0EEA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_application`
--

CREATE TABLE IF NOT EXISTS `project_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `project_role_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `role_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `result` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `state` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6C942A3A166D1F9C` (`project_id`),
  KEY `IDX_6C942A3A401D2EC9` (`project_role_id`),
  KEY `IDX_6C942A3AA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_board_comment`
--

CREATE TABLE IF NOT EXISTS `project_board_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8FBED65A76ED395` (`user_id`),
  KEY `IDX_8FBED65166D1F9C` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_board_file`
--

CREATE TABLE IF NOT EXISTS `project_board_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_board_comment_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4EAF1401E9E26AC6` (`project_board_comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_category`
--

CREATE TABLE IF NOT EXISTS `project_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `project_category`
--

INSERT INTO `project_category` (`id`, `name`) VALUES
(1, 'Design'),
(2, 'Performing arts'),
(3, 'Technology'),
(4, 'Writing'),
(5, 'Social change'),
(6, 'Business');

-- --------------------------------------------------------

--
-- Table structure for table `project_comment`
--

CREATE TABLE IF NOT EXISTS `project_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `priority` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_26A5E09166D1F9C` (`project_id`),
  KEY `IDX_26A5E09727ACA70` (`parent_id`),
  KEY `IDX_26A5E09A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_has_project_tag`
--

CREATE TABLE IF NOT EXISTS `project_has_project_tag` (
  `project_id` int(11) NOT NULL,
  `project_tag_id` int(11) NOT NULL,
  PRIMARY KEY (`project_id`,`project_tag_id`),
  KEY `IDX_C0239897166D1F9C` (`project_id`),
  KEY `IDX_C0239897AD76885B` (`project_tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `project_log`
--

CREATE TABLE IF NOT EXISTS `project_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rate` int(11) NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1D44B226166D1F9C` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_poll`
--

CREATE TABLE IF NOT EXISTS `project_poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_BD2D325D166D1F9C` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_poll_answer`
--

CREATE TABLE IF NOT EXISTS `project_poll_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `poll_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `answer` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_FE17FE85A76ED395` (`user_id`),
  KEY `IDX_FE17FE853C947C0F` (`poll_id`),
  KEY `IDX_FE17FE851E27F6BF` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_poll_question`
--

CREATE TABLE IF NOT EXISTS `project_poll_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) DEFAULT NULL,
  `role_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D4FCA6F83C947C0F` (`poll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_role`
--

CREATE TABLE IF NOT EXISTS `project_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `level` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6EF84272166D1F9C` (`project_id`),
  KEY `IDX_6EF84272A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_role_widget_question`
--

CREATE TABLE IF NOT EXISTS `project_role_widget_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `question` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B3AABB6A166D1F9C` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_survey_answer`
--

CREATE TABLE IF NOT EXISTS `project_survey_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `answer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2642154F1E27F6BF` (`question_id`),
  KEY `IDX_2642154F166D1F9C` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_survey_question`
--

CREATE TABLE IF NOT EXISTS `project_survey_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `project_survey_question`
--

INSERT INTO `project_survey_question` (`id`, `name`) VALUES
(1, 'What problem does your idea solve?'),
(2, 'Did the idea come about because it was your own personal problem?'),
(3, 'Is this idea new--or are you just trying to be better than the competition?'),
(4, 'On a scale of 1-10, how passionate are you about this idea?'),
(5, 'Have you sought outside feedback and validation? Who?'),
(6, 'How enthusiastic are they?'),
(7, 'Are people willing to pay for your product? If so, do you know how much?'),
(8, 'How and when will you make your first dollar?'),
(9, 'Do you have domain expertise? if not, do you have the skills and time to gain that knowledge?'),
(10, 'How big is the market?'),
(11, 'How crowded is the market?'),
(12, 'How simple will it be to complete a minimum viable product (MVP)'),
(13, 'What is the most basic version you can get out in 60 days?'),
(14, 'In 90 days?'),
(15, 'What message are you looking to convey with your product?'),
(16, 'Have you focussed in on your value proposition? If so, what is it?');

-- --------------------------------------------------------

--
-- Table structure for table `project_tag`
--

CREATE TABLE IF NOT EXISTS `project_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `search_project_tag_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_task`
--

CREATE TABLE IF NOT EXISTS `project_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `level` int(11) NOT NULL,
  `task` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `finished` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6BEF133D166D1F9C` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `project_update`
--

CREATE TABLE IF NOT EXISTS `project_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8F81DE32166D1F9C` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `setting_slideshow`
--

CREATE TABLE IF NOT EXISTS `setting_slideshow` (
  `id` int(11) NOT NULL,
  `project_1_id` int(11) DEFAULT NULL,
  `project_2_id` int(11) DEFAULT NULL,
  `project_3_id` int(11) DEFAULT NULL,
  `project_4_id` int(11) DEFAULT NULL,
  `project_5_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3A76762AC94294E2` (`project_1_id`),
  KEY `IDX_3A76762ADBF73B0C` (`project_2_id`),
  KEY `IDX_3A76762A634B5C69` (`project_3_id`),
  KEY `IDX_3A76762AFE9C64D0` (`project_4_id`),
  KEY `IDX_3A76762A462003B5` (`project_5_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `setting_slideshow`
--

INSERT INTO `setting_slideshow` (`id`, `project_1_id`, `project_2_id`, `project_3_id`, `project_4_id`, `project_5_id`) VALUES
(1, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_info_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `profile_picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_visibility` tinyint(1) DEFAULT NULL,
  `email_newsletter` tinyint(1) NOT NULL,
  `email_notification` tinyint(1) NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` char(2) COLLATE utf8_unicode_ci DEFAULT NULL,
  `confirmed` smallint(6) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `date_of_birth_visibility` tinyint(1) DEFAULT NULL,
  `ban` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D649C5659115` (`profile_picture`),
  UNIQUE KEY `UNIQ_8D93D649586DFF2` (`user_info_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_info_id`, `name`, `email`, `profile_picture`, `email_visibility`, `email_newsletter`, `email_notification`, `password`, `country`, `confirmed`, `description`, `date_of_birth`, `created`, `last_login`, `date_of_birth_visibility`, `ban`) VALUES
(1, 1, 'Josef Kortan', 'j.kortan@gmail.com', NULL, 0, 1, 1, '4c44f835dd72479c9a932f4b2333471d9a2ffdfd', NULL, 1, NULL, '2012-08-28', '2012-08-28 20:52:43', '2012-08-28 20:52:43', 0, 0),
(2, 2, 'Jake Zahradnik', 'jake.zahradnik@gmail.com', NULL, 0, 1, 1, 'da92276eabb83e3a660e5019d7ad114866b3d1a8', NULL, 1, NULL, '2012-08-28', '2012-08-28 20:52:43', '2012-08-28 20:52:43', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_field_of_interest_tag`
--

CREATE TABLE IF NOT EXISTS `user_field_of_interest_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E02396115E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_flo_box`
--

CREATE TABLE IF NOT EXISTS `user_flo_box` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type_detail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2DD519CAA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_flo_box_comment`
--

CREATE TABLE IF NOT EXISTS `user_flo_box_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flo_box_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_82BE57DA30A79F24` (`flo_box_id`),
  KEY `IDX_82BE57DAA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_favourite_project`
--

CREATE TABLE IF NOT EXISTS `user_has_favourite_project` (
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`project_id`),
  KEY `IDX_1ADA335DA76ED395` (`user_id`),
  KEY `IDX_1ADA335D166D1F9C` (`project_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_friend`
--

CREATE TABLE IF NOT EXISTS `user_has_friend` (
  `user_id` int(11) NOT NULL,
  `friend_user_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`friend_user_id`),
  KEY `IDX_DEAB7D16A76ED395` (`user_id`),
  KEY `IDX_DEAB7D1693D1119E` (`friend_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_user_field_of_interest_tag`
--

CREATE TABLE IF NOT EXISTS `user_has_user_field_of_interest_tag` (
  `user_id` int(11) NOT NULL,
  `user_field_of_interest_tag_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_field_of_interest_tag_id`),
  KEY `IDX_8C6D2AE5A76ED395` (`user_id`),
  KEY `IDX_8C6D2AE5FED95079` (`user_field_of_interest_tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_user_role`
--

CREATE TABLE IF NOT EXISTS `user_has_user_role` (
  `user_id` int(11) NOT NULL,
  `user_role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`user_role_id`),
  KEY `IDX_E2C3F9BCA76ED395` (`user_id`),
  KEY `IDX_E2C3F9BC8E0E3CA6` (`user_role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user_has_user_role`
--

INSERT INTO `user_has_user_role` (`user_id`, `user_role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE IF NOT EXISTS `user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skype` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `im` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `skype`, `im`, `phone`, `website`) VALUES
(1, '', '', '', ''),
(2, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `user_log`
--

CREATE TABLE IF NOT EXISTS `user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `rate` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6429094EA76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DE8C6A35E237E06` (`name`),
  KEY `search_idx` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `type`, `name`) VALUES
(1, 'system_role', 'visitor'),
(2, 'system_role', 'member'),
(3, 'system_role', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_specific_role`
--

CREATE TABLE IF NOT EXISTS `user_specific_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_name` (`user_id`,`name`),
  KEY `IDX_63B91808A76ED395` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_specific_role_has_user_specific_tag`
--

CREATE TABLE IF NOT EXISTS `user_specific_role_has_user_specific_tag` (
  `specific_role_id` int(11) NOT NULL,
  `user_specific_tag_id` int(11) NOT NULL,
  PRIMARY KEY (`specific_role_id`,`user_specific_tag_id`),
  KEY `IDX_A0AE6A0325E14547` (`specific_role_id`),
  KEY `IDX_A0AE6A03F318AC8C` (`user_specific_tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_specific_role_tag`
--

CREATE TABLE IF NOT EXISTS `user_specific_role_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_78DF514D5E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `FK_2FB3D0EEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_2FB3D0EE12469DE2` FOREIGN KEY (`category_id`) REFERENCES `project_category` (`id`);

--
-- Constraints for table `project_application`
--
ALTER TABLE `project_application`
  ADD CONSTRAINT `FK_6C942A3AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6C942A3A166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_6C942A3A401D2EC9` FOREIGN KEY (`project_role_id`) REFERENCES `project_role` (`id`);

--
-- Constraints for table `project_board_comment`
--
ALTER TABLE `project_board_comment`
  ADD CONSTRAINT `FK_8FBED65166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_8FBED65A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_board_file`
--
ALTER TABLE `project_board_file`
  ADD CONSTRAINT `FK_4EAF1401E9E26AC6` FOREIGN KEY (`project_board_comment_id`) REFERENCES `project_board_comment` (`id`);

--
-- Constraints for table `project_comment`
--
ALTER TABLE `project_comment`
  ADD CONSTRAINT `FK_26A5E09A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_26A5E09166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_26A5E09727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `project_comment` (`id`);

--
-- Constraints for table `project_has_project_tag`
--
ALTER TABLE `project_has_project_tag`
  ADD CONSTRAINT `FK_C0239897AD76885B` FOREIGN KEY (`project_tag_id`) REFERENCES `project_tag` (`id`),
  ADD CONSTRAINT `FK_C0239897166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `project_log`
--
ALTER TABLE `project_log`
  ADD CONSTRAINT `FK_1D44B226166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `project_poll`
--
ALTER TABLE `project_poll`
  ADD CONSTRAINT `FK_BD2D325D166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `project_poll_answer`
--
ALTER TABLE `project_poll_answer`
  ADD CONSTRAINT `FK_FE17FE851E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `project_poll_question` (`id`),
  ADD CONSTRAINT `FK_FE17FE853C947C0F` FOREIGN KEY (`poll_id`) REFERENCES `project_poll` (`id`),
  ADD CONSTRAINT `FK_FE17FE85A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `project_poll_question`
--
ALTER TABLE `project_poll_question`
  ADD CONSTRAINT `FK_D4FCA6F83C947C0F` FOREIGN KEY (`poll_id`) REFERENCES `project_poll` (`id`);

--
-- Constraints for table `project_role`
--
ALTER TABLE `project_role`
  ADD CONSTRAINT `FK_6EF84272A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_6EF84272166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `project_role_widget_question`
--
ALTER TABLE `project_role_widget_question`
  ADD CONSTRAINT `FK_B3AABB6A166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `project_survey_answer`
--
ALTER TABLE `project_survey_answer`
  ADD CONSTRAINT `FK_2642154F166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_2642154F1E27F6BF` FOREIGN KEY (`question_id`) REFERENCES `project_survey_question` (`id`);

--
-- Constraints for table `project_task`
--
ALTER TABLE `project_task`
  ADD CONSTRAINT `FK_6BEF133D166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `project_update`
--
ALTER TABLE `project_update`
  ADD CONSTRAINT `FK_8F81DE32166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `setting_slideshow`
--
ALTER TABLE `setting_slideshow`
  ADD CONSTRAINT `FK_3A76762A462003B5` FOREIGN KEY (`project_5_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_3A76762A634B5C69` FOREIGN KEY (`project_3_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_3A76762AC94294E2` FOREIGN KEY (`project_1_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_3A76762ADBF73B0C` FOREIGN KEY (`project_2_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_3A76762AFE9C64D0` FOREIGN KEY (`project_4_id`) REFERENCES `project` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D649586DFF2` FOREIGN KEY (`user_info_id`) REFERENCES `user_info` (`id`);

--
-- Constraints for table `user_flo_box`
--
ALTER TABLE `user_flo_box`
  ADD CONSTRAINT `FK_2DD519CAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_flo_box_comment`
--
ALTER TABLE `user_flo_box_comment`
  ADD CONSTRAINT `FK_82BE57DAA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_82BE57DA30A79F24` FOREIGN KEY (`flo_box_id`) REFERENCES `user_flo_box` (`id`);

--
-- Constraints for table `user_has_favourite_project`
--
ALTER TABLE `user_has_favourite_project`
  ADD CONSTRAINT `FK_1ADA335D166D1F9C` FOREIGN KEY (`project_id`) REFERENCES `project` (`id`),
  ADD CONSTRAINT `FK_1ADA335DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_has_friend`
--
ALTER TABLE `user_has_friend`
  ADD CONSTRAINT `FK_DEAB7D1693D1119E` FOREIGN KEY (`friend_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_DEAB7D16A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_has_user_field_of_interest_tag`
--
ALTER TABLE `user_has_user_field_of_interest_tag`
  ADD CONSTRAINT `FK_8C6D2AE5FED95079` FOREIGN KEY (`user_field_of_interest_tag_id`) REFERENCES `user_field_of_interest_tag` (`id`),
  ADD CONSTRAINT `FK_8C6D2AE5A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_has_user_role`
--
ALTER TABLE `user_has_user_role`
  ADD CONSTRAINT `FK_E2C3F9BC8E0E3CA6` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`id`),
  ADD CONSTRAINT `FK_E2C3F9BCA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_log`
--
ALTER TABLE `user_log`
  ADD CONSTRAINT `FK_6429094EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_specific_role`
--
ALTER TABLE `user_specific_role`
  ADD CONSTRAINT `FK_63B91808A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `user_specific_role_has_user_specific_tag`
--
ALTER TABLE `user_specific_role_has_user_specific_tag`
  ADD CONSTRAINT `FK_A0AE6A03F318AC8C` FOREIGN KEY (`user_specific_tag_id`) REFERENCES `user_specific_role_tag` (`id`),
  ADD CONSTRAINT `FK_A0AE6A0325E14547` FOREIGN KEY (`specific_role_id`) REFERENCES `user_specific_role` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2015 at 06:55 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dms_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `dms_documents`
--

CREATE TABLE IF NOT EXISTS `dms_documents` (
  `document_id` int(9) NOT NULL AUTO_INCREMENT COMMENT 'file id',
  `owner_id` int(11) NOT NULL COMMENT 'user id of file owner',
  `folder_id` int(11) NOT NULL,
  `file_title` varchar(220) NOT NULL COMMENT 'name given at the time of uploading',
  `description` longtext COMMENT 'Description for file',
  `keywords` varchar(220) NOT NULL,
  `shareable` int(1) NOT NULL COMMENT 'can share with anyone with link',
  `default_access` tinyint(4) NOT NULL,
  `inherited_access` tinyint(1) NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `created_by` mediumint(9) NOT NULL DEFAULT '0' COMMENT 'File creator',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dms_document_files`
--

CREATE TABLE IF NOT EXISTS `dms_document_files` (
  `document_file_id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `file_name` varchar(220) NOT NULL,
  `file_path` varchar(220) NOT NULL COMMENT 'Relative path of folder of file',
  `file_path_id` int(11) NOT NULL COMMENT 'File path by ids',
  `file_size` double NOT NULL COMMENT 'File size in KB',
  `file_mimetype` varchar(220) NOT NULL,
  `file_extension` varchar(220) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Version updator',
  `file_version` varchar(220) NOT NULL,
  `file_comment` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`document_file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dms_folders`
--

CREATE TABLE IF NOT EXISTS `dms_folders` (
  `folder_id` int(11) NOT NULL,
  `parent_folder_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'owner of folder',
  `folder_name` int(11) NOT NULL,
  `comment` text NOT NULL,
  `inherited_access` tinyint(1) NOT NULL,
  `default_access` tinyint(4) NOT NULL,
  `real_path` varchar(220) NOT NULL COMMENT 'real folder path',
  `folder_id_path` int(11) NOT NULL COMMENT 'Path to folder by folders ids',
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dms_users`
--

CREATE TABLE IF NOT EXISTS `dms_users` (
  `users_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(220) NOT NULL,
  `password` varchar(220) NOT NULL,
  `email` varchar(80) NOT NULL,
  `comment` text NOT NULL,
  `role` tinyint(4) NOT NULL,
  `disabled` tinyint(4) NOT NULL,
  `quota` double NOT NULL COMMENT 'Allowed disk space in KB',
  `home_folder` int(11) NOT NULL COMMENT 'folder id',
  `parent_user` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`users_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `document_category`
--

CREATE TABLE IF NOT EXISTS `document_category` (
  `document_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`document_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dsm_access_log`
--

CREATE TABLE IF NOT EXISTS `dsm_access_log` (
  `document_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dsm_category`
--

CREATE TABLE IF NOT EXISTS `dsm_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'Owner of category',
  `category_title` varchar(220) NOT NULL,
  `note` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dsm_documentfolder_category`
--

CREATE TABLE IF NOT EXISTS `dsm_documentfolder_category` (
  `document_id` int(11) DEFAULT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dsm_favorite_document`
--

CREATE TABLE IF NOT EXISTS `dsm_favorite_document` (
  `document_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dsm_folderdocument_access`
--

CREATE TABLE IF NOT EXISTS `dsm_folderdocument_access` (
  `document_id` int(11) DEFAULT NULL,
  `folder_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `access_code` enum('0','1','2','3','4') NOT NULL COMMENT '0-No access,1-Read,2-read write,3-read write share,4-all permission'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='access management for folder and document other than inherited access ';

-- --------------------------------------------------------

--
-- Table structure for table `dsm_group`
--

CREATE TABLE IF NOT EXISTS `dsm_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'owner of group',
  `group_name` varchar(220) NOT NULL,
  `note` text NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`group_id`),
  UNIQUE KEY `user_id` (`user_id`,`group_name`),
  UNIQUE KEY `user_id_2` (`user_id`,`group_name`),
  UNIQUE KEY `user_id_3` (`user_id`,`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dsm_group_members`
--

CREATE TABLE IF NOT EXISTS `dsm_group_members` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`group_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dsm_keywords`
--

CREATE TABLE IF NOT EXISTS `dsm_keywords` (
  `keyword_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'Owner of keyword',
  `keyword` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`keyword_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dsm_shared_documents`
--

CREATE TABLE IF NOT EXISTS `dsm_shared_documents` (
  `shared_document_id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `token` int(11) NOT NULL,
  `expire_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `shared_by` int(11) NOT NULL,
  PRIMARY KEY (`shared_document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dsm_user_permission`
--

CREATE TABLE IF NOT EXISTS `dsm_user_permission` (
  `user_id` int(11) NOT NULL,
  `user_permissionlist_id` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dsm_user_permissionlist`
--

CREATE TABLE IF NOT EXISTS `dsm_user_permissionlist` (
  `user_permissionlist_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(220) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`user_permissionlist_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `dsm_user_permissionlist`
--

INSERT INTO `dsm_user_permissionlist` (`user_permissionlist_id`, `title`, `description`) VALUES
(1, 'User Creation', 'can user create user'),
(2, 'User creation Permission', 'Can user assign create user permission to its child users'),
(3, 'Document Category', 'can user create document category?');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

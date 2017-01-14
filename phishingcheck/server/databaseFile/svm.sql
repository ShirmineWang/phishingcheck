-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2014 年 05 月 31 日 14:36
-- 服务器版本: 5.5.27
-- PHP 版本: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `app_phishingcheck`
--

-- --------------------------------------------------------

--
-- 表的结构 `svm`
--

CREATE TABLE IF NOT EXISTS `svm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lable` varchar(10) NOT NULL,
  `empty_href` int(11) NOT NULL,
  `exter_link` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `svm`
--

INSERT INTO `svm` (`id`, `lable`, `empty_href`, `exter_link`, `total`) VALUES
(1, 'phishing', 37, 2575, 51),
(2, 'trusted', 711, 46266, 62),
(3, 'total', 0, 0, 0);

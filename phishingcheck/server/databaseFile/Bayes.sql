-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- 主机: w.rdc.sae.sina.com.cn:3307
-- 生成日期: 2014 年 05 月 31 日 14:37
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
-- 表的结构 `Bayes`
--

CREATE TABLE IF NOT EXISTS `Bayes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lable` varchar(32) NOT NULL,
  `ip_contained` double NOT NULL DEFAULT '0',
  `abnormal_symbol` double NOT NULL DEFAULT '0',
  `domain_num` double NOT NULL DEFAULT '0',
  `port_contained` double NOT NULL DEFAULT '0',
  `long_length` double NOT NULL DEFAULT '0',
  `total` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `Bayes`
--

INSERT INTO `Bayes` (`id`, `lable`, `ip_contained`, `abnormal_symbol`, `domain_num`, `port_contained`, `long_length`, `total`) VALUES
(1, 'phishing', 0, 24, 13, 2, 18, 147),
(2, 'trusted', 0, 0, 0, 0, 0, 62),
(3, 'total', 0, 24, 13, 2, 18, 209);

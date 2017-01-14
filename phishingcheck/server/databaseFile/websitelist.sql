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
-- 表的结构 `websitelist`
--

CREATE TABLE IF NOT EXISTS `websitelist` (
  `trustedwebsite` varchar(256) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `websitelist`
--

INSERT INTO `websitelist` (`trustedwebsite`) VALUES
('http://www.baidu.com/'),
('http://www.google.com/'),
('http://www.sina.com/'),
('http://www.sohu.com/'),
('http://www.yahoo.com/'),
('http://www.qq.com/'),
('http://www.taobao.com/'),
('http://www.tianmao.com/'),
('http://www.renren.com/'),
('http://www.ctrip.com/'),
('http://swjx.scu.edu.cn/'),
('http://www.baidu.com/#wd=%E5%BE%AE%E5%8D%9A&rsv_spt=1&issp=1&rsv_bp=0&ie=utf-8&tn=baiduhome_pg&rsv_sug3=4&rsv_sug4=371&rsv_sug1=3&rsv_sug2=0&inputT=896'),
('http://www.amazon.cn/'),
('http://www.163.com/'),
('http://www.ifeng.com/'),
('http://www.youku.com/'),
('http://www.qunar.com/'),
('http://www.jiayuan.com/'),
('http://www.autohome.com.cn/'),
('http://www.ganji.com/'),
('http://www.jd.com/'),
('http://www.meilishuo.com/'),
('http://cd.58.com/'),
('http://www.baihe.com/'),
('http://www.vip.com/'),
('http://www.lefeng.com/'),
('http://www.7k7k.com/'),
('http://www.4399.com/'),
('http://www.nuomi.com/'),
('http://www.dangdang.com/'),
('http://www.yhd.com/'),
('http://www.51job.com/'),
('http://my.zhaopin.com/'),
('http://cp.duba.com/KaiJiang/SSQ/index.html'),
('http://www.meituan.com/'),
('http://cd.soufun.com/'),
('http://www.tudou.com/'),
('http://www.iqiyi.com/'),
('http://www.letv.com/'),
('http://chengdu.anjuke.com/'),
('http://www.12306.cn/'),
('http://jing.fm/'),
('http://t.dianping.com/'),
('http://www.ccb.com/cn/home/index.html'),
('http://www.icbc.com.cn/icbc/'),
('http://www.abchina.com/cn/'),
('http://www.boc.cn/'),
('http://www.bankcomm.com/BankCommSite/cn/'),
('http://www.cmbchina.com/'),
('https://www.alipay.com/'),
('http://china.nba.com/'),
('http://www.stockstar.com/'),
('http://www.cnfol.com/'),
('http://www.chinaamc.com/'),
('http://www.tianya.cn/'),
('http://www.douban.com/'),
('http://www.mop.com/'),
('http://www.zol.com.cn/'),
('http://www.pconline.com.cn/'),
('http://www.zhcw.com/'),
('http://www.dajie.com/'),
('http://www.phishtank.com/');

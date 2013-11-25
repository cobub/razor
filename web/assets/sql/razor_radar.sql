-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost
-- 生成日期: 2013 年 10 月 10 日 11:44
-- 服务器版本: 5.0.51
-- PHP 版本: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 数据库: `cobub04_db`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `razor_radar`
-- 

CREATE TABLE `razor_radar` (
  `id` tinyint(4) NOT NULL auto_increment,
  `app_id` varchar(25) NOT NULL,
  `user_id` int(25) NOT NULL,
  `product_id` int(15) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost
-- 生成日期: 2013 年 10 月 09 日 15:48
-- 服务器版本: 6.0.4
-- PHP 版本: 6.0.0-dev

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 数据库: `cobub06_db`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `razor_ios_product`
-- 

CREATE TABLE `razor_ios_product` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `product_id` varchar(25) NOT NULL,
  `register_id` varchar(64) NOT NULL,
  `is_active` int(4) NOT NULL,
  `user_id` int(15) NOT NULL,
  `bundle_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



SET NAMES 'utf8';

--
-- Table Structure of  `alert`
--

CREATE TABLE IF NOT EXISTS `umsinstall_alert` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `userid` int(50) NOT NULL,
  `productid` int(50) NOT NULL,
  `condition` float NOT NULL,
  `label` varchar(50) NOT NULL,
  `active` int(10) NOT NULL DEFAULT '1',
  `emails` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `alertdetail`
--

CREATE TABLE IF NOT EXISTS `umsinstall_alertdetail` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `alertlabel` int(50) NOT NULL,
  `factdata` int(50) NOT NULL,
  `forecastdata` int(50) NOT NULL,
  `time` datetime NOT NULL,
  `states` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `cell_towers`
--

CREATE TABLE IF NOT EXISTS `umsinstall_cell_towers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `clientdataid` int(50) NOT NULL,
  `cellid` varchar(50) NOT NULL,
  `lac` varchar(50) NOT NULL,
  `mcc` varchar(50) NOT NULL,
  `mnc` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `signalstrength` varchar(50) NOT NULL,
  `timingadvance` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `channel`
--

CREATE TABLE IF NOT EXISTS `umsinstall_channel` (
  `channel_id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_name` varchar(255) NOT NULL DEFAULT '',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL DEFAULT '1',
  `type` enum('system','user') NOT NULL DEFAULT 'user',
  `platform` int(10) NOT NULL,
  `active` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`channel_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `channel_product`
--

CREATE TABLE IF NOT EXISTS `umsinstall_channel_product` (
  `cp_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(5000) DEFAULT NULL,
  `updateurl` varchar(2000) NOT NULL DEFAULT '',
  `entrypoint` varchar(500) NOT NULL DEFAULT '',
  `location` varchar(500) NOT NULL DEFAULT '',
  `version` varchar(50) NOT NULL DEFAULT '',
  `date` datetime NOT NULL,
  `productkey` varchar(50) NOT NULL,
  `man` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '1',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `channel_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table Structure of  `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `umsinstall_ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table Structure of  `clientdata`
--

CREATE TABLE IF NOT EXISTS `umsinstall_clientdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serviceversion` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `platform` varchar(50) DEFAULT NULL,
  `osversion` varchar(50) DEFAULT NULL,
  `osaddtional` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `resolution` varchar(50) DEFAULT NULL,
  `ismobiledevice` varchar(50) DEFAULT NULL,
  `devicename` varchar(50) DEFAULT NULL,
  `deviceid` varchar(200) DEFAULT NULL,
  `defaultbrowser` varchar(50) DEFAULT NULL,
  `javasupport` varchar(50) DEFAULT NULL,
  `flashversion` varchar(50) DEFAULT NULL,
  `modulename` varchar(50) DEFAULT NULL,
  `imei` varchar(50) DEFAULT NULL,
  `imsi` varchar(50) DEFAULT NULL,
  `havegps` varchar(50) DEFAULT NULL,
  `havebt` varchar(50) DEFAULT NULL,
  `havewifi` varchar(50) DEFAULT NULL,
  `havegravity` varchar(50) DEFAULT NULL,
  `wifimac` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL,
  `clientip` varchar(50) NOT NULL,
  `productkey` varchar(50) NOT NULL,
  `service_supplier` varchar(64) DEFAULT NULL,
  `country` varchar(50) DEFAULT 'unknown',
  `region` varchar(50) DEFAULT 'unknown',
  `city` varchar(50) DEFAULT 'unknown',
  `street` varchar(500) DEFAULT NULL,
  `streetno` varchar(50) DEFAULT NULL,
  `postcode` varchar(50) DEFAULT NULL,
  `network` varchar(128) NOT NULL DEFAULT '1',
  `isjailbroken` int(10) NOT NULL DEFAULT '0',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `useridentifier` varchar(256) default NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `clientusinglog`
--

CREATE TABLE IF NOT EXISTS `umsinstall_clientusinglog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(500) NOT NULL,
  `start_millis` datetime NOT NULL,
  `end_millis` datetime NOT NULL,
  `duration` int(50) NOT NULL,
  `activities` varchar(500) NOT NULL,
  `appkey` varchar(500) NOT NULL,
  `version` varchar(50) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `config`
--

CREATE TABLE IF NOT EXISTS `umsinstall_config` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `autogetlocation` tinyint(1) NOT NULL DEFAULT '1',
  `updateonlywifi` tinyint(1) NOT NULL DEFAULT '1',
  `product_id` int(50) NOT NULL,
  `sessionmillis` int(50) NOT NULL DEFAULT '30',
  `reportpolicy` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `errorlog`
--

CREATE TABLE IF NOT EXISTS `umsinstall_errorlog` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `appkey` varchar(50) NOT NULL,
  `device` varchar(50) NOT NULL,
  `os_version` varchar(50) NOT NULL,
  `activity` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `title` text NOT NULL,
  `stacktrace` text NOT NULL,
  `version` varchar(50) NOT NULL,
  `isfix` int(11) DEFAULT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table Structure of  `eventdata`
--

CREATE TABLE IF NOT EXISTS `umsinstall_eventdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deviceid` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `event` varchar(50) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  `attachment` varchar(50) DEFAULT NULL,
  `clientdate` datetime NOT NULL,
  `productkey` varchar(64) NOT NULL DEFAULT 'no_key',
  `num` int(50) NOT NULL DEFAULT '1',
  `event_id` int(50) NOT NULL,
  `version` varchar(50) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table Structure of  `event_defination`
--

CREATE TABLE IF NOT EXISTS `umsinstall_event_defination` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_identifier` varchar(50) NOT NULL,
  `productkey` char(50) NOT NULL,
  `event_name` char(50) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `channel_id` (`channel_id`,`product_id`,`user_id`,`event_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table Structure of  `login_attempts`
--

CREATE TABLE IF NOT EXISTS `umsinstall_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table Structure of  `markevent`
--

CREATE TABLE IF NOT EXISTS `umsinstall_markevent` (
  `id` int(50) NOT NULL auto_increment,
  `userid` int(50) NOT NULL,
  `productid` int(50) NOT NULL default '-1',
  `title` varchar(45) NOT NULL,
  `description` varchar(128) NOT NULL,
  `private` tinyint(1) NOT NULL default '0',
  `marktime` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1;


-- --------------------------------------------------------

--
-- Table Structure of  `networktype`
--

CREATE TABLE IF NOT EXISTS `umsinstall_networktype` (
  `id` int(8) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table Structure of  `platform`
--

CREATE TABLE IF NOT EXISTS `umsinstall_platform` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `product`
--

CREATE TABLE IF NOT EXISTS `umsinstall_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `date` datetime NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '1',
  `channel_count` int(11) NOT NULL DEFAULT '0',
  `product_key` varchar(50) NOT NULL,
  `product_platform` int(50) NOT NULL DEFAULT '1',
  `category` int(50) NOT NULL DEFAULT '1',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `productfiles`
--

CREATE TABLE IF NOT EXISTS `umsinstall_productfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `version` double NOT NULL,
  `type` varchar(50) NOT NULL,
  `updatedate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `product_category`
--

CREATE TABLE IF NOT EXISTS `umsinstall_product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `level` int(50) NOT NULL DEFAULT '1',
  `parentid` int(11) NOT NULL DEFAULT '0',
  `active` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `product_version`
--

CREATE TABLE IF NOT EXISTS `umsinstall_product_version` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `version` varchar(50) NOT NULL,
  `product_channel_id` int(50) NOT NULL,
  `updateurl` varchar(2000) NOT NULL,
  `updatetime` datetime NOT NULL,
  `description` varchar(5000) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `reportlayout`
--

CREATE TABLE IF NOT EXISTS `umsinstall_reportlayout` (
  `id` int(50) NOT NULL auto_increment,
  `userid` int(50) NOT NULL,
  `productid` int(50) NOT NULL,
  `reportname` varchar(128) NOT NULL,
  `controller` varchar(128) NOT NULL,
  `method` varchar(45) default NULL,
  `height` int(50) NOT NULL,
  `src` varchar(512) NOT NULL,
  `location` int(50) NOT NULL,
  `type` int(10) NOT NULL,
  `createtime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `user2role`
--

CREATE TABLE IF NOT EXISTS `umsinstall_user2role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `users`
--

CREATE TABLE IF NOT EXISTS `umsinstall_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sessionkey` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `user_autologin`
--

CREATE TABLE IF NOT EXISTS `umsinstall_user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table Structure of  `user_permissions`
--

CREATE TABLE IF NOT EXISTS `umsinstall_user_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` int(11) DEFAULT NULL,
  `resource` int(11) DEFAULT NULL,
  `read` tinyint(1) DEFAULT '0',
  `write` tinyint(1) DEFAULT '0',
  `modify` tinyint(1) DEFAULT '0',
  `delete` tinyint(1) DEFAULT '0',
  `publish` tinyint(1) DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table Structure of  `user_profiles`
--

CREATE TABLE IF NOT EXISTS `umsinstall_user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `companyname` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `contact` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `telephone` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `QQ` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `MSN` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `Gtalk` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `user_resources`
--

CREATE TABLE IF NOT EXISTS `umsinstall_user_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `user_roles`
--

CREATE TABLE IF NOT EXISTS `umsinstall_user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table Structure of  `wifi_towers`
--

CREATE TABLE IF NOT EXISTS `umsinstall_wifi_towers` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `clientdataid` int(50) NOT NULL,
  `mac_address` varchar(50) NOT NULL,
  `signal_strength` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- 
-- Table Structure of  `tag_group`
-- 

CREATE TABLE IF NOT EXISTS `umsinstall_tag_group` (
  `id` int(4) NOT NULL auto_increment,
  `product_id` int(4) NOT NULL,
  `name` varchar(200) NOT NULL,
  `tags` varchar(5000) NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
-- Table Structure of  `target`
--

CREATE TABLE IF NOT EXISTS `umsinstall_target` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `targetname` varchar(128) NOT NULL,
  `targettype` int(11) DEFAULT NULL,
  `unitprice` decimal(12,2) NOT NULL,
  `targetstatusc` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table Structure of  `targetevent`
--

CREATE TABLE IF NOT EXISTS `umsinstall_targetevent` (
  `teid` int(11) NOT NULL AUTO_INCREMENT,
  `targetid` int(11) NOT NULL,
  `eventid` int(11) NOT NULL,
  `eventalias` varchar(128) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`teid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



-- --------------------------------------------------------

--
-- Table Structure of  `user2product`
--
CREATE TABLE IF NOT EXISTS `umsinstall_user2product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
INSERT INTO `umsinstall_channel` (`channel_id`, `channel_name`, `create_date`, `user_id`, `type`, `platform`, `active`) VALUES
(1, "安卓市场", "2011-11-22 13:54:39", 1, "system", 1, 1),
(2, "机锋市场", "2011-11-22 13:54:47", 1, "system", 1, 1),
(3, "安智市场", "2011-11-22 13:54:57", 1, "system", 1, 1),
(4, "XDA市场", "2011-11-22 13:55:03", 1, "system", 1, 1),
(5, "AppStore", "2011-12-03 13:49:25", 1, "system", 2, 1),
(6, "Windows Phone Store", "2011-12-03 13:49:25", 1, "system", 3, 1);
-- --------------------------------------------------------



-- --------------------------------------------------------


--
-- Default value for table `networktype`
--

INSERT INTO `umsinstall_networktype` (`id`, `type`) VALUES
(1, "WIFI"),
(2, "2G/3G"),
(3, "1xRTT"),
(4, "CDMA"),
(5, "EDGE"),
(6, "EVDO_0"),
(7, "EVDO_A"),
(8, "GPRS"),
(9, "HSDPA"),
(10, "HSPA"),
(11, "HSUPA"),
(12, "UMTS"),
(13, "EHRPD"),
(14, "EVDO_B"),
(15, "HSPAP"),
(16, "IDEN"),
(17, "LTE"),
(18, "UNKNOWN");

-- --------------------------------------------------------

INSERT INTO `umsinstall_platform` (`id`, `name`) VALUES
(1, "Android"),
(2, "iOS"),
(3, "Windows Phone");

-- --------------------------------------------------------


INSERT INTO `umsinstall_product_category` (`id`, `name`, `level`, `parentid`) VALUES
(1, "UMSINSTALL_NEWSPAPER", 1, 0),
(2, "UMSINSTALL_SOCIAL", 1, 0),
(3, "UMSINSTALL_BUSINESS", 1, 0),
(4, "UMSINSTALL_FINANCIALBUSINESS", 1, 0),
(5, "UMSINSTALL_REFERENCE", 1, 0),
(6, "UMSINSTALL_NAVIGATION", 1, 0),
(7, "UMSINSTALL_INSTRUMENT", 1, 0),
(8, "UMSINSTALL_HEALTHFITNESS", 1, 0),
(9, "UMSINSTALL_EDUCATION", 1, 0),
(10, "UMSINSTALL_TRAVEL", 1, 0),
(11, "UMSINSTALL_PHOTOVIDEO", 1, 0),
(12, "UMSINSTALL_LIFE", 1, 0),
(13, "UMSINSTALL_SPORTS", 1, 0),
(14, "UMSINSTALL_WEATHER", 1, 0),
(15, "UMSINSTALL_BOOKS", 1, 0),
(16, "UMSINSTALL_EFFICIENCY", 1, 0),
(17, "UMSINSTALL_NEWS", 1, 0),
(18, "UMSINSTALL_MUSIC", 1, 0),
(19, "UMSINSTALL_MEDICAL", 1, 0),
(32, "UMSINSTALL_ENTERTAINMENT", 1, 0),
(33, "UMSINSTALL_GAME", 1, 0);


-- --------------------------------------------------------

--
-- Table Structure of  `user_permissions`
--


--
-- Default value for table `user_permissions`
--

--$$

INSERT INTO `umsinstall_user_permissions` VALUES 
(1,1,1,1,1,0,0,0,NULL),(2,2,1,0,0,0,0,0,NULL),(3,3,1,1,1,1,1,1,NULL),
(4,1,2,0,0,0,0,0,NULL),(5,2,2,0,0,0,0,0,NULL),(6,3,2,1,1,1,1,1,NULL),
(7,1,3,0,0,0,0,0,NULL),(8,2,3,0,0,0,0,0,NULL),(9,3,3,1,0,0,0,0,NULL),
(10,1,4,0,0,0,0,0,NULL),(11,2,4,0,0,0,0,0,NULL),(12,3,4,1,0,0,0,0,NULL),
(13,1,5,0,0,0,0,0,NULL),(14,2,5,0,0,0,0,0,NULL),(15,3,5,1,0,0,0,0,NULL),
(16,1,6,0,0,0,0,0,NULL),(17,2,6,0,0,0,0,0,NULL),(18,3,6,1,0,0,0,0,NULL),
(19,1,7,0,0,0,0,0,NULL),(20,2,7,0,0,0,0,0,NULL),(21,3,7,1,0,0,0,0,NULL),
(22,1,8,0,0,0,0,0,NULL),(23,2,8,0,0,0,0,0,NULL),(24,3,8,1,0,0,0,0,NULL),
(25,1,9,0,0,0,0,0,NULL),(26,2,9,0,0,0,0,0,NULL),(27,3,9,1,0,0,0,0,NULL),
(28,1,10,0,0,0,0,0,NULL),(29,2,10,0,0,0,0,0,NULL),(30,3,10,1,0,0,0,0,NULL),
(31,1,11,0,0,0,0,0,NULL),(32,2,11,0,0,0,0,0,NULL),(33,3,11,1,0,0,0,0,NULL),
(34,1,12,0,0,0,0,0,NULL),(35,2,12,0,0,0,0,0,NULL),(36,3,12,1,0,0,0,0,NULL),
(37,1,13,0,0,0,0,0,NULL),(38,2,13,0,0,0,0,0,NULL),(39,3,13,1,0,0,0,0,NULL),
(40,1,14,0,0,0,0,0,NULL),(41,2,14,0,0,0,0,0,NULL),(42,3,14,1,0,0,0,0,NULL),
(43,1,15,0,0,0,0,0,NULL),(44,2,15,0,0,0,0,0,NULL),(45,3,15,1,0,0,0,0,NULL),
(46,1,16,0,0,0,0,0,NULL),(47,2,16,0,0,0,0,0,NULL),(48,3,16,1,0,0,0,0,NULL),
(49,1,17,0,0,0,0,0,NULL),(50,2,17,0,0,0,0,0,NULL),(51,3,17,1,0,0,0,0,NULL),
(52,1,18,0,0,0,0,0,NULL),(53,2,18,0,0,0,0,0,NULL),(54,3,18,1,0,0,0,0,NULL),
(55,1,19,0,0,0,0,0,NULL),(56,2,19,0,0,0,0,0,NULL),(57,3,19,1,0,0,0,0,NULL),
(58,1,20,0,0,0,0,0,NULL),(59,2,20,0,0,0,0,0,NULL),(60,3,20,1,0,0,0,0,NULL),
(61,1,21,0,0,0,0,0,NULL),(62,2,21,0,0,0,0,0,NULL),(63,3,21,1,0,0,0,0,NULL),
(64,1,22,0,0,0,0,0,NULL),(65,2,22,0,0,0,0,0,NULL),(66,3,22,1,0,0,0,0,NULL),
(67,1,23,0,0,0,0,0,NULL),(68,2,23,0,0,0,0,0,NULL),(69,3,23,1,0,0,0,0,NULL),
(70,1,24,0,0,0,0,0,NULL),(71,2,24,0,0,0,0,0,NULL),(72,3,24,1,0,0,0,0,NULL),
(73,1,25,0,0,0,0,0,NULL),(74,2,25,0,0,0,0,0,NULL),(75,3,25,1,0,0,0,0,NULL),
(76,1,26,0,0,0,0,0,NULL),(77,2,26,0,0,0,0,0,NULL),(78,3,26,1,0,0,0,0,NULL),
(79,1,27,0,0,0,0,0,NULL),(80,2,27,0,0,0,0,0,NULL),(81,3,27,1,0,0,0,0,NULL),
(82,1,28,0,0,0,0,0,NULL),(83,2,28,0,0,0,0,0,NULL),(84,3,28,1,0,0,0,0,NULL);

-- --------------------------------------------------------

--
-- Default value for table `user_resources`
--

INSERT INTO `umsinstall_user_resources` (`id`, `name`, `description`, `parentId`) VALUES
(1, "test", "Acl Test Controller", NULL),
(2, "User", "UMSINSTALLC_SYSMANAGER", NULL),
(3, "Product", "UMSINSTALLC_MYAPPS", NULL),
(4, "errorlogondevice", "UMSINSTALLC_ERRORDEVICE", NULL),
(5, "productbasic", "UMSINSTALLC_DASHBOARD", NULL),
(6, "Auth", "UMSINSTALLC_USERS", NULL),
(7, "Autoupdate", "UMSINSTALLC_AUTOUPDATE", NULL),
(8, "Channel", "UMSINSTALLC_CHANNEL", NULL),
(9, "Device", "UMSINSTALLC_DEVICE", NULL),
(10, "Event", "UMSINSTALLC_EVENTMANAGEMENT", NULL),
(11, "Onlineconfig", "UMSINSTALLC_SENDPOLICY", NULL),
(12, "Operator", "UMSINSTALLC_OPERATORSTATISTICS", NULL),
(13, "Os", "UMSINSTALLC_OSSTATISTICS", NULL),
(14, "Profile", "UMSINSTALLC_PROFILE", NULL),
(15, "Resolution", "UMSINSTALLC_RESOLUTIONSTATISTICS", NULL),
(16, "Usefrequency", "UMSINSTALLC_REEQUENCYSTATISTICS", NULL),
(17, "Usetime", "UMSINSTALLC_USAGEDURATION", NULL),
(18, "errorlog", "UMSINSTALLC_ERRORLOG", NULL),
(19, "Eventlist", "UMSINSTALLC_EVENTLIST", NULL),
(20, "market", "UMSINSTALLC_CHANNELSTATISTICS", NULL),
(21, "region", "UMSINSTALLC_GEOGRAPHYSTATICS", NULL),
(22, "errorlogonos", "UMSINSTALLC_ERRORONOS", NULL),
(23, "version", "UMSINSTALLC_VERSIONSTATISTICS", NULL),
(24, "console", "UMSINSTALLC_APPS", NULL),
(25, "Userremain", "UMSINSTALLC_RETENTION", NULL),
(26, "Pagevisit", "UMSINSTALLC_PAGEVIEWSANALY", NULL),
(27, "Network", "UMSINSTALLC_NETWORKINGSTATISTIC", NULL),
(28, "funnels", "UMSINSTALLC_FUNNELMODEL", NULL);
-- --------------------------------------------------------

INSERT INTO `umsinstall_user_roles` (`id`, `name`, `description`, `parentId`) VALUES
(1, 'user', 'normal user', NULL),
(2, 'guest', 'not log in', NULL),
(3, 'admin', 'system admin', NULL);

-- --------------------------------------------------------
--
-- Default value for table `userkeys`
--
CREATE TABLE `umsinstall_userkeys` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `user_key` varchar(50) NOT NULL,
  `user_secret` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------
--
-- Default value for table `plugins`
--
CREATE TABLE `umsinstall_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(50) NOT NULL,
  `user_id` int(50) NOT NULL,
  `status` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------
--
-- Default value for table `getui_product`
--
CREATE TABLE IF NOT EXISTS `umsinstall_getui_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  `app_id` varchar(25) DEFAULT NULL,
  `user_id` int(8) DEFAULT NULL,
  `app_key` varchar(25) NOT NULL,
  `app_secret` varchar(25) NOT NULL,
  `app_mastersecret` varchar(25) NOT NULL,
  `app_identifier` varchar(25) NOT NULL,
  `activate_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `umsinstall_device_tag` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `deviceid` varchar(256) NOT NULL,
    `tags` varchar(1024) default NULL,
    `productkey` varchar(64) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

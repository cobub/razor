SET NAMES 'utf8';
--
-- 表的结构 `alert`
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
-- 表的结构 `alertdetail`
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
-- 表的结构 `cell_towers`
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
-- 表的结构 `channel`
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
-- 表的结构 `channel_product`
--

CREATE TABLE IF NOT EXISTS `umsinstall_channel_product` (
  `cp_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(5000) NOT NULL,
  `updateurl` varchar(2000) NOT NULL,
  `entrypoint` varchar(500) NOT NULL,
  `location` varchar(500) NOT NULL,
  `version` varchar(50) NOT NULL,
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
-- 表的结构 `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `umsinstall_ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- 表的结构 `clientdata`
--

CREATE TABLE IF NOT EXISTS `umsinstall_clientdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serviceversion` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `version` varchar(50) NOT NULL,
  `platform` varchar(50) NOT NULL,
  `osversion` varchar(50) NOT NULL,
  `osaddtional` varchar(50) NOT NULL,
  `language` varchar(50) NOT NULL,
  `resolution` varchar(50) NOT NULL,
  `ismobiledevice` varchar(50) NOT NULL,
  `devicename` varchar(50) NOT NULL,
  `deviceid` varchar(200) NOT NULL,
  `defaultbrowser` varchar(50) NOT NULL,
  `javasupport` varchar(50) NOT NULL,
  `flashversion` varchar(50) NOT NULL,
  `modulename` varchar(50) NOT NULL,
  `imei` varchar(50) NOT NULL,
  `imsi` varchar(50) NOT NULL,
  `havegps` varchar(50) NOT NULL,
  `havebt` varchar(50) NOT NULL,
  `havewifi` varchar(50) NOT NULL,
  `havegravity` varchar(50) NOT NULL,
  `wifimac` varchar(50) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `date` datetime NOT NULL,
  `clientip` varchar(50) NOT NULL,
  `productkey` varchar(50) NOT NULL,
  `service_supplier` varchar(64) NOT NULL DEFAULT 'China Mobile',
  `country` varchar(50) NOT NULL,
  `region` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `street` varchar(500) NOT NULL,
  `streetno` varchar(50) NOT NULL,
  `postcode` varchar(50) NOT NULL,
  `network` varchar(128) NOT NULL DEFAULT '1',
  `isjailbroken` int(10) NOT NULL DEFAULT '0',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
-- --------------------------------------------------------

--
-- 表的结构 `clientusinglog`
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
-- 表的结构 `config`
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
-- 表的结构 `errorlog`
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
  `isfix` int(11) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- 表的结构 `eventdata`
--

CREATE TABLE IF NOT EXISTS `umsinstall_eventdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deviceid` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `event` varchar(50) NOT NULL,
  `label` varchar(50) NOT NULL,
  `attachment` varchar(50) NOT NULL,
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
-- 表的结构 `event_defination`
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
-- 表的结构 `login_attempts`
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
-- 表的结构 `markevent`
--

CREATE TABLE IF NOT EXISTS `umsinstall_markevent` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `userid` int(50) NOT NULL,
  `productid` int(50) NOT NULL,
  `title` varchar(45) NOT NULL,
  `description` varchar(128) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `marktime` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- 表的结构 `mccmnc`
--

CREATE TABLE IF NOT EXISTS `umsinstall_mccmnc` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `value` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- 表的结构 `networktype`
--

CREATE TABLE IF NOT EXISTS `umsinstall_networktype` (
  `id` int(8) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `platform`
--

CREATE TABLE IF NOT EXISTS `umsinstall_platform` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `product`
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
-- 表的结构 `productfiles`
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
-- 表的结构 `product_category`
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
-- 表的结构 `product_version`
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
-- 表的结构 `reportlayout`
--

CREATE TABLE IF NOT EXISTS `umsinstall_reportlayout` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `userid` int(50) NOT NULL,
  `productid` int(50) NOT NULL,
  `reportname` varchar(128) NOT NULL,
  `controller` varchar(128) NOT NULL,
  `method` varchar(45) NOT NULL,
  `height` int(50) NOT NULL,
  `src` varchar(512) NOT NULL,
  `location` int(50) NOT NULL,
  `type` int(10) NOT NULL,
  `createtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user2role`
--

CREATE TABLE IF NOT EXISTS `umsinstall_user2role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `users`
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_autologin`
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
-- 表的结构 `user_permissions`
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
-- 表的结构 `user_profiles`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user_resources`
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
-- 表的结构 `user_roles`
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
-- 表的结构 `wifi_towers`
--

CREATE TABLE IF NOT EXISTS `umsinstall_wifi_towers` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `clientdataid` int(50) NOT NULL,
  `mac_address` varchar(50) NOT NULL,
  `signal_strength` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `razor_target`
--

CREATE TABLE IF NOT EXISTS `umsinstall_target` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `targetname` varchar(128) NOT NULL,
  `targettype` int(11) NOT NULL,
  `unitprice` decimal(12,2) NOT NULL,
  `targetstatusc` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `razor_targetevent`
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
INSERT INTO `umsinstall_channel` (`channel_id`, `channel_name`, `create_date`, `user_id`, `type`, `platform`, `active`) VALUES
(1, "Android Market", "2011-11-22 13:54:39", 1, "system", 1, 1),
(2, "JiFeng Market", "2011-11-22 13:54:47", 1, "system", 1, 1),
(3, "AnZhi Market", "2011-11-22 13:54:57", 1, "system", 1, 1),
(4, "XDA Market", "2011-11-22 13:55:03", 1, "system", 1, 1),
(5, "AppStore", "2011-12-03 13:49:25", 1, "system", 2, 1),
(6, "Windows Phone Store", "2011-12-03 13:49:25", 1, "system", 3, 1);
-- --------------------------------------------------------


--
-- 转存表中的数据 `mccmnc`
--

INSERT INTO `umsinstall_mccmnc` (`id`, `value`, `name`) VALUES
(1, "46000", "China Mobile"),
(2, "46001", "China Unicom"),
(3, "46002", "China Mobile"),
(4, "46003", "China Telecom"),
(5, "310SP", "China Telecom"),
(8, "45406", "SMC"),
(6, "46601", "Far EasTone"),
(7, "46692", "Chunghwa"),
(11, "46697", "TWN GSM"),
(12, "45403", "Hutchison 3G"),
(13, "52505", "STARHUB-SGP"),
(14, "50216", "DiGi"),
(15, "45400", "CSL"),
(16, "23415", "Vodafone"),
(17, "46689", "KGT"),
(9, "45419", "Sunday3G"),
(10, "45412", "PEOPLES"),
(18, "20801", "Orange"),
(19, "20802", "Orange"),
(20, "20810", "SFR"),
(21, "20811", "SFR"),
(22, "20813", "SFR"),
(23, "20815", "Free Mobile"),
(24, "20816", "Free Mobile"),
(25, "20820", "Bouygues Telecom"),
(26, "20821", "Bouygues Telecom"),
(27, "20823", "Virgin Mobile"),
(28, "20825", "Lycamobile"),
(29, "20826", "NRJ Mobile"),
(30, "20827", "Afone Mobile"),
(31, "20830", "Symacom"),
(32, "20888", "Bouygues Telecom");

-- --------------------------------------------------------


--
-- 转存表中的数据 `networktype`
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
(1, "Newspapers and magazines", 1, 0),
(2, "Social", 1, 0),
(3, "Business", 1, 0),
(4, "Financial Business", 1, 0),
(5, "Reference", 1, 0),
(6, "Navigation", 1, 0),
(7, "instrument", 1, 0),
(8, "Health and fitness", 1, 0),
(9, "Education", 1, 0),
(10, "Travel", 1, 0),
(11, "Photography and Video", 1, 0),
(12, "Life", 1, 0),
(13, "Sports", 1, 0),
(14, "Weather", 1, 0),
(15, "Books", 1, 0),
(16, "Efficiency", 1, 0),
(17, "News", 1, 0),
(18, "Music", 1, 0),
(19, "Medical", 1, 0),
(32, "Entertainment", 1, 0),
(33, "Game", 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `user_permissions`
--


--
-- 转存表中的数据 `user_permissions`
--

INSERT INTO `umsinstall_user_permissions` (`id`, `role`, `resource`, `read`, `write`, `modify`, `delete`, `publish`, `description`) VALUES
(1, 1, 1, 1, 1, 0, 0, 0, NULL),
(2, 2, 1, 0, 0, 0, 0, 0, NULL),
(3, 3, 1, 1, 1, 1, 1, 1, NULL),
(4, 1, 2, 0, 0, 0, 0, 0, NULL),
(5, 2, 2, 0, 0, 0, 0, 0, NULL),
(6, 3, 2, 1, 1, 1, 1, 1, NULL);

-- --------------------------------------------------------

--
-- 转存表中的数据 `user_resources`
--

INSERT INTO `umsinstall_user_resources` (`id`, `name`, `description`, `parentId`) VALUES
(1, "test", "Acl Test Controller", NULL),
(2, "User", "System Management", NULL),
(3, "Product", "My Apps", NULL),
(4, "errorlogondevice", "Error on device", NULL),
(5, "productbasic", "Dashboard", NULL),
(6, "Auth", "Users", NULL),
(7, "Autoupdate", "Automatic update", NULL),
(8, "Channel", "Channel", NULL),
(9, "Device", "Device", NULL),
(10, "Event", "Event Management", NULL),
(11, "Onlineconfig", "Send policy", NULL),
(12, "Operator", "Operator statistics", NULL),
(13, "Os", "OS statistics", NULL),
(14, "Profile", "Profile", NULL),
(15, "Resolution", "Resolution statistics", NULL),
(16, "Usefrequency", "Frequency statistics", NULL),
(17, "Usetime", "Usage Duration statistics", NULL),
(18, "errorlog", "Error log", NULL),
(19, "Eventlist", "Event list", NULL),
(20, "market", "Channel statistics", NULL),
(21, "region", "Geography statistics", NULL),
(22, "errorlogonos", "Error on OS", NULL),
(23, "version", "Version statistics", NULL),
(24, "console", "Apps", NULL),
(25, "Userremain", "Retention", NULL),
(26, "Pagevisit", "Page views analysis", NULL),
(27, "Network", "Networking statistics", NULL),
(28, "funnels", "Funnel model", NULL);

-- --------------------------------------------------------

INSERT INTO `umsinstall_user_roles` (`id`, `name`, `description`, `parentId`) VALUES
(1, 'user', 'normal user', NULL),
(2, 'guest', 'not log in', NULL),
(3, 'admin', 'system admin', NULL);

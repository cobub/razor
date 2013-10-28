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
-- Table Structure of  `mccmnc`
--

CREATE TABLE IF NOT EXISTS `umsinstall_mccmnc` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `value` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `countrycode` varchar(50) DEFAULT NULL,
  `countryname` varchar(50) DEFAULT NULL,
  PRIMARY KEY `id` (`id`),
  UNIQUE KEY `value` (`value`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

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
-- 表的结构 `tag_group`
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


--
-- Default value for table `mccmnc`
--

INSERT INTO `umsinstall_mccmnc` (`value`, `name`,`countrycode`,'countryname`) VALUES
('20201', 'Cosmote Greece', '202', 'GR'),
('20205', 'Vodafone - Panafon Greece', '202', 'GR'),
('20209', 'Info Quest S.A. Greece', '202', 'GR'),
('20210', 'Telestet Greece', '202', 'GR'),
('20402', 'Tele2 (Netherlands) B.V.', '204', 'NL'),
('20404', 'Vodafone Libertel N.V. Netherlands', '204', 'NL'),
('20408', 'KPN Telecom B.V. Netherlands', '204', 'NL'),
('20412', 'BT Ignite Nederland B.V.', '204', 'NL'),
('20416', 'BEN Nederland B.V.', '204', 'NL'),
('20420', 'Dutchtone N.V. Netherlands', '204', 'NL'),
('20421', 'NS Railinfrabeheer B.V. Netherlands', '204', 'NL'),
('20601', 'Proximus Belgium', '206', 'BE'),
('20610', 'Mobistar Belgium', '206', 'BE'),
('20620', 'Base Belgium', '206', 'BE'),
('20801', 'Orange', '208', 'FR'),
('20802', 'Orange', '208', 'FR'),
('20805', 'Globalstar Europe France', '208', 'FR'),
('20806', 'Globalstar Europe France', '208', 'FR'),
('20807', 'Globalstar Europe France', '208', 'FR'),
('20810', 'SFR', '208', 'FR'),
('20811', 'SFR', '208', 'FR'),
('20813', 'SFR', '208', 'FR'),
('20815', 'Free Mobile', '208', 'FR'),
('20816', 'Free Mobile', '208', 'FR'),
('20820', 'Bouygues Telecom', '208', 'FR'),
('20821', 'Bouygues Telecom', '208', 'FR'),
('20823', 'Virgin Mobile', '208', 'FR'),
('20825', 'Lycamobile', '208', 'FR'),
('20826', 'NRJ Mobile', '208', 'FR'),
('20827', 'Afone Mobile', '208', 'FR'),
('20830', 'Symacom', '208', 'FR'),
('20888', 'Bouygues Telecom', '208', 'FR'),
('21303', 'MobilandAndorra', '213', 'AD'),
('21401', 'Vodafone Spain', '214', 'ES'),
('21403', 'Amena Spain', '214', 'ES'),
('21404', 'Xfera Spain', '214', 'ES'),
('21407', 'Movistar Spain', '214', 'ES'),
('21601', 'Pannon GSM Hungary', '216', 'HU'),
('21630', 'T-Mobile Hungary', '216', 'HU'),
('21670', 'Vodafone Hungary', '216', 'HU'),
('21803', 'Eronet Mobile Communications Ltd. Bosnia and Herzegovina', '218', 'BA'),
('21805', 'MOBIS (Mobilina Srpske) Bosnia and Herzegovina', '218', 'BA'),
('21890', 'GSMBIH Bosnia and Herzegovina', '218', 'BA'),
('21901', 'Cronet Croatia', '219', 'HR'),
('21910', 'VIPnet Croatia', '219', 'HR'),
('22001', 'Mobtel Serbia', '220', 'YU'),
('22002', 'Promonte GSM Serbia', '220', 'YU'),
('22003', 'Telekom Srbija', '220', 'YU'),
('22004', 'Monet Serbia', '220', 'YU'),
('22201', 'Telecom Italia Mobile (TIM)', '222', 'IT'),
('22202', 'Elsacom Italy', '222', 'IT'),
('22210', 'Omnitel Pronto Italia (OPI)', '222', 'IT'),
('22277', 'IPSE 2000 Italy', '222', 'IT'),
('22288', 'Wind Italy', '222', 'IT'),
('22298', 'Blu Italy', '222', 'IT'),
('22299', 'H3G Italy', '222', 'IT'),
('22601', 'Vodafone Romania SA', '226', 'RO'),
('22603', 'Cosmorom Romania', '226', 'RO'),
('22610', 'Orange Romania', '226', 'RO'),
('22801', 'Swisscom GSM', '228', 'CH'),
('22802', 'Sunrise GSM Switzerland', '228', 'CH'),
('22803', 'Orange Switzerland', '228', 'CH'),
('22805', 'Togewanet AG Switzerland', '228', 'CH'),
('22806', 'SBB AG Switzerland', '228', 'CH'),
('22807', 'IN&Phone SA Switzerland', '228', 'CH'),
('22808', 'Tele2 Telecommunications AG Switzerland', '228', 'CH'),
('22812', 'Sunrise UMTS Switzerland', '228', 'CH'),
('22850', '3G Mabile AG Switzerland', '228', 'CH'),
('22851', 'Global Networks Schweiz AG', '228', 'CH'),
('23001', 'RadioMobil a.s., T-Mobile Czech Rep.', '230', 'CZ'),
('23002', 'Eurotel Praha, spol. Sro., Eurotel Czech Rep.', '230', 'CZ'),
('23003', 'Cesky Mobil a.s., Oskar', '230', 'CZ'),
('23099', 'Cesky Mobil a.s., R&D Centre', '230', 'CZ'),
('23101', 'Orange, GSM Slovakia', '231', 'SK'),
('23102', 'Eurotel, GSM & NMT Slovakia', '231', 'SK'),
('23104', 'Eurotel, UMTS Slovakia', '231', 'SK'),
('23105', 'Orange, UMTS Slovakia', '231', 'SK'),
('23201', 'A1 Austria', '232', 'AT'),
('23203', 'T-Mobile Austria', '232', 'AT'),
('23205', 'One Austria', '232', 'AT'),
('23207', 'tele.ring Austria', '232', 'AT'),
('23208', 'Telefonica Austria', '232', 'AT'),
('23209', 'One Austria', '232', 'AT'),
('23210', 'Hutchison 3G Austria', '232', 'AT'),
('23402', 'O2 UK Ltd.', '234', 'GB'),
('23410', 'O2 UK Ltd.', '234', 'GB'),
('23411', 'O2 UK Ltd.', '234', 'GB'),
('23412', 'Railtrack Plc UK', '234', 'GB'),
('23415', 'Vodafone', '234', 'GB'),
('23420', 'Hutchison 3G UK Ltd.', '234', 'GB'),
('23430', 'T-Mobile UK', '234', 'GB'),
('23431', 'T-Mobile UK', '234', 'GB'),
('23432', 'T-Mobile UK', '234', 'GB'),
('23433', 'Orange UK', '234', 'GB'),
('23434', 'Orange UK', '234', 'GB'),
('23450', 'Jersey Telecom UK', '234', 'GB'),
('23455', 'Guensey Telecom UK', '234', 'GB'),
('23458', 'Manx Telecom UK', '234', 'GB'),
('23475', 'Inquam Telecom (Holdings) Ltd. UK', '234', 'GB'),
('23801', 'TDC Mobil Denmark', '238', 'DK'),
('23802', 'Sonofon Denmark', '238', 'DK'),
('23803', 'MIGway A/S Denmark', '238', 'DK'),
('23806', 'Hi3G Denmark', '238', 'DK'),
('23807', 'Barablu Mobile Ltd. Denmark', '238', 'DK'),
('23810', 'TDC Mobil Denmark', '238', 'DK'),
('23820', 'Telia Denmark', '238', 'DK'),
('23830', 'Telia Mobile Denmark', '238', 'DK'),
('23877', 'Tele2 Denmark', '238', 'DK'),
('24001', 'Telia Sonera AB Sweden', '240', 'SE'),
('24002', 'H3G Access AB Sweden', '240', 'SE'),
('24003', 'Nordisk Mobiltelefon AS Sweden', '240', 'SE'),
('24004', '3G Infrastructure Services AB Sweden', '240', 'SE'),
('24005', 'Svenska UMTS-Nat AB', '240', 'SE'),
('24006', 'Telenor Sverige AB', '240', 'SE'),
('24007', 'Tele2 Sverige AB', '240', 'SE'),
('24008', 'Telenor Sverige AB', '240', 'SE'),
('24009', 'Telenor Mobile Sverige', '240', 'SE'),
('24010', 'Swefour AB Sweden', '240', 'SE'),
('24011', 'Linholmen Science Park AB Sweden', '240', 'SE'),
('24020', 'Wireless Maingate Message Services AB Sweden', '240', 'SE'),
('24021', 'Banverket Sweden', '240', 'SE'),
('24201', 'Telenor Mobil AS Norway', '242', 'NO'),
('24202', 'Netcom GSM AS Norway', '242', 'NO'),
('24203', 'Teletopia Mobile Communications AS Norway', '242', 'NO'),
('24204', 'Tele2 Norge AS', '242', 'NO'),
('24404', 'Finnet Networks Ltd.', '244', 'FI'),
('24405', 'Elisa Matkapuhelinpalvelut Ltd. Finland', '244', 'FI'),
('24409', 'Finnet Group', '244', 'FI'),
('24412', 'Finnet Networks Ltd.', '244', 'FI'),
('24414', 'Alands Mobiltelefon AB Finland', '244', 'FI'),
('24416', 'Oy Finland Tele2 AB', '244', 'FI'),
('24421', 'Saunalahti Group Ltd. Finland', '244', 'FI'),
('24491', 'Sonera Carrier Networks Oy Finland', '244', 'FI'),
('24601', 'Omnitel Lithuania', '246', 'LT'),
('24602', 'Bit GSM Lithuania', '246', 'LT'),
('24603', 'Tele2 Lithuania', '246', 'LT'),
('24701', 'Latvian Mobile Phone', '247', 'LV'),
('24702', 'Tele2 Latvia', '247', 'LV'),
('24703', 'Telekom Baltija Latvia', '247', 'LV'),
('24704', 'Beta Telecom Latvia', '247', 'LV'),
('24801', 'EMT GSM Estonia', '248', 'EE'),
('24802', 'RLE Estonia', '248', 'EE'),
('24803', 'Tele2 Estonia', '248', 'EE'),
('24804', 'OY Top Connect Estonia', '248', 'EE'),
('24805', 'AS Bravocom Mobiil Estonia', '248', 'EE'),
('24806', 'OY ViaTel Estonia', '248', 'EE'),
('25001', 'Mobile Telesystems Russia', '250', 'RU'),
('25002', 'Megafon Russia', '250', 'RU'),
('25003', 'Nizhegorodskaya Cellular Communications Russia', '250', 'RU'),
('25004', 'Sibchallenge Russia', '250', 'RU'),
('25005', 'Mobile Comms System Russia', '250', 'RU'),
('25007', 'BM Telecom Russia', '250', 'RU'),
('25010', 'Don Telecom Russia', '250', 'RU'),
('25011', 'Orensot Russia', '250', 'RU'),
('25012', 'Baykal Westcom Russia', '250', 'RU'),
('25013', 'Kuban GSM Russia', '250', 'RU'),
('25016', 'New Telephone Company Russia', '250', 'RU'),
('25017', 'Ermak RMS Russia', '250', 'RU'),
('25019', 'Volgograd Mobile Russia', '250', 'RU'),
('25020', 'ECC Russia', '250', 'RU'),
('25028', 'Extel Russia', '250', 'RU'),
('25039', 'Uralsvyazinform Russia', '250', 'RU'),
('25044', 'Stuvtelesot Russia', '250', 'RU'),
('25092', 'Printelefone Russia', '250', 'RU'),
('25093', 'Telecom XXI Russia', '250', 'RU'),
('25099', 'Bec Line GSM Russia', '250', 'RU'),
('25501', 'Ukrainian Mobile Communication, UMC', '255', 'UA'),
('25502', 'Ukranian Radio Systems, URS', '255', 'UA'),
('25503', 'Kyivstar Ukraine', '255', 'UA'),
('25504', 'Golden Telecom, GT Ukraine', '255', 'UA'),
('25506', 'Astelit Ukraine', '255', 'UA'),
('25507', 'Ukrtelecom Ukraine', '255', 'UA'),
('25701', 'MDC Velcom Belarus', '257', 'BY'),
('25702', 'MTS Belarus', '257', 'BY'),
('25901', 'Voxtel Moldova', '259', 'MD'),
('25902', 'Moldcell Moldova', '259', 'MD'),
('26001', 'Plus GSM (Polkomtel S.A.) Poland', '260', 'PL'),
('26002', 'ERA GSM (Polska Telefonia Cyfrowa Sp. Z.o.o.)', '260', 'PL'),
('26003', 'Idea (Polska Telefonia Komorkowa Centertel Sp. Z.o.o)', '260', 'PL'),
('26004', 'Tele2 Polska (Tele2 Polska Sp. Z.o.o.)', '260', 'PL'),
('26005', 'IDEA (UMTS)/PTK Centertel sp. Z.o.o. Poland', '260', 'PL'),
('26006', 'Netia Mobile Poland', '260', 'PL'),
('26007', 'Premium internet Poland', '260', 'PL'),
('26008', 'E-Telko Poland', '260', 'PL'),
('26009', 'Telekomunikacja Kolejowa (GSM-R) Poland', '260', 'PL'),
('26010', 'Telefony Opalenickie Poland', '260', 'PL'),
('26201', 'T-Mobile Deutschland GmbH', '262', 'DE'),
('26202', 'Vodafone D2 GmbH Germany', '262', 'DE'),
('26203', 'E-Plus Mobilfunk GmbH & Co. KG Germany', '262', 'DE'),
('26204', 'Vodafone D2 GmbH Germany', '262', 'DE'),
('26205', 'E-Plus Mobilfunk GmbH & Co. KG Germany', '262', 'DE'),
('26206', 'T-Mobile Deutschland GmbH', '262', 'DE'),
('26207', 'O2 (Germany) GmbH & Co. OHG', '262', 'DE'),
('26208', 'O2 (Germany) GmbH & Co. OHG', '262', 'DE'),
('26209', 'Vodafone D2 GmbH Germany', '262', 'DE'),
('26210', 'Arcor AG & Co. Germany', '262', 'DE'),
('26211', 'O2 (Germany) GmbH & Co. OHG', '262', 'DE'),
('26212', 'Dolphin Telecom (Deutschland) GmbH', '262', 'DE'),
('26213', 'Mobilcom Multimedia GmbH Germany', '262', 'DE'),
('26214', 'Group 3G UMTS GmbH (Quam) Germany', '262', 'DE'),
('26215', 'Airdata AG Germany', '262', 'DE'),
('26276', 'Siemens AG, ICMNPGUSTA Germany', '262', 'DE'),
('26277', 'E-Plus Mobilfunk GmbH & Co. KG Germany', '262', 'DE'),
('26601', 'Gibtel GSM Gibraltar', '266', 'GI'),
('26801', 'Vodafone Telecel - Comunicacoes Pessoais, S.A. Portugal', '268', 'PT'),
('26803', 'Optimus - Telecomunicacoes, S.A. Portugal', '268', 'PT'),
('26805', 'Oniway - Inforcomunicacoes, S.A. Portugal', '268', 'PT'),
('26806', 'TMN - Telecomunicacoes Moveis Nacionais, S.A. Portugal', '268', 'PT'),
('27001', 'P&T Luxembourg', '270', 'LU'),
('27077', 'Tango Luxembourg', '270', 'LU'),
('27099', 'Voxmobile S.A. Luxembourg', '270', 'LU'),
('27201', 'Vodafone Ireland Plc', '272', 'IE'),
('27202', 'Digifone mm02 Ltd. Ireland', '272', 'IE'),
('27203', 'Meteor Mobile Communications Ltd. Ireland', '272', 'IE'),
('27207', 'Eircom Ireland', '272', 'IE'),
('27209', 'Clever Communications Ltd. Ireland', '272', 'IE'),
('27401', 'Iceland Telecom Ltd.', '274', 'IS'),
('27402', 'Tal hf Iceland', '274', 'IS'),
('27403', 'Islandssimi GSM ehf Iceland', '274', 'IS'),
('27404', 'IMC Islande ehf', '274', 'IS'),
('27601', 'AMC Albania', '276', 'AL'),
('27602', 'Vodafone Albania', '276', 'AL'),
('27801', 'Vodafone Malta', '278', 'MT'),
('27821', 'go mobile Malta', '278', 'MT'),
('28001', 'CYTA Cyprus', '280', 'CY'),
('28010', 'Scancom (Cyprus) Ltd.', '280', 'CY'),
('28201', 'Geocell Ltd. Georgia', '282', 'GE'),
('28202', 'Magti GSM Ltd. Georgia', '282', 'GE'),
('28203', 'Iberiatel Ltd. Georgia', '282', 'GE'),
('28204', 'Mobitel Ltd. Georgia', '282', 'GE'),
('28301', 'ARMGSM', '283', 'AM'),
('28401', 'M-Tel GSM BG Bulgaria', '284', 'BG'),
('28405', 'Globul Bulgaria', '284', 'BG'),
('28601', 'Turkcell Turkey', '286', 'TR'),
('28602', 'Telsim GSM Turkey', '286', 'TR'),
('28603', 'Aria Turkey', '286', 'TR'),
('28604', 'Aycell Turkey', '286', 'TR'),
('28801', 'Faroese Telecom - GSM', '288', 'FO'),
('28802', 'Kall GSM Faroe Islands', '288', 'FO'),
('29001', 'Tele Greenland', '290', 'GR'),
('29201', 'SMT - San Marino Telecom', '292', 'SM'),
('29340', 'SI Mobil Slovenia', '293', 'SI'),
('29341', 'Mobitel Slovenia', '293', 'SI'),
('29369', 'Akton d.o.o. Slovenia', '293', 'SI'),
('29370', 'Tusmobil d.o.o. Slovenia', '293', 'SI'),
('29401', 'Mobimak Macedonia', '294', 'MK'),
('29402', 'MTS Macedonia', '294', 'MK'),
('29501', 'Telecom FL AG Liechtenstein', '295', 'LI'),
('29502', 'Viag Europlatform AG Liechtenstein', '295', 'LI'),
('29505', 'Mobilkom (Liechstein) AG', '295', 'LI'),
('29577', 'Tele2 AG Liechtenstein', '295', 'LI'),
('30236', 'Clearnet Canada', '302', 'CA'),
('30237', 'Microcell Canada', '302', 'CA'),
('30262', 'Ice Wireless Canada', '302', 'CA'),
('30263', 'Aliant Mobility Canada', '302', 'CA'),
('30264', 'Bell Mobility Canada', '302', 'CA'),
('302656', 'Tbay Mobility Canada', '302', 'CA'),
('30266', 'MTS Mobility Canada', '302', 'CA'),
('30267', 'CityTel Mobility Canada', '302', 'CA'),
('30268', 'Sask Tel Mobility Canada', '302', 'CA'),
('30271', 'Globalstar Canada', '302', 'CA'),
('30272', 'Rogers Wireless Canada', '302', 'CA'),
('30286', 'Telus Mobility Canada', '302', 'CA'),
('30801', 'St. Pierre-et-Miquelon Telecom', '308', 'CA'),
('310010', 'MCI USA', '310', 'US'),
('310012', 'Verizon Wireless USA', '310', 'US'),
('310013', 'Mobile Tel Inc. USA', '310', 'US'),
('310014', 'Testing USA', '310', 'US'),
('310016', 'Cricket Communications USA', '310', 'US'),
('310017', 'North Sight Communications Inc. USA', '310', 'US'),
('310020', 'Union Telephone Company USA', '310', 'US'),
('310030', 'Centennial Communications USA', '310', 'US'),
('310034', 'Nevada Wireless LLC USA', '310', 'US'),
('310040', 'Concho Cellular Telephone Co., Inc. USA', '310', 'US'),
('310050', 'ACS Wireless Inc. USA', '310', 'US'),
('310060', 'Consolidated Telcom USA', '310', 'US'),
('310070', 'Highland Cellular, Inc. USA', '310', 'US'),
('310080', 'Corr Wireless Communications LLC USA', '310', 'US'),
('310090', 'Edge Wireless LLC USA', '310', 'US'),
('310100', 'New Mexico RSA 4 East Ltd. Partnership USA', '310', 'US'),
('310120', 'Sprint USA', '310', 'US'),
('310130', 'Carolina West Wireless USA', '310', 'US'),
('310140', 'GTA Wireless LLC USA', '310', 'US'),
('310150', 'Cingular Wireless USA', '310', 'US'),
('310160', 'T-Mobile USA', '310', 'US'),
('310170', 'Cingular Wireless USA', '310', 'US'),
('310180', 'West Central Wireless USA', '310', 'US'),
('310190', 'Alaska Wireless Communications LLC USA', '310', 'US'),
('310200', 'T-Mobile USA', '310', 'US'),
('310210', 'T-Mobile USA', '310', 'US'),
('310220', 'T-Mobile USA', '310', 'US'),
('310230', 'T-Mobile USA', '310', 'US'),
('310240', 'T-Mobile USA', '310', 'US'),
('310250', 'T-Mobile USA', '310', 'US'),
('310260', 'T-Mobile USA', '310', 'US'),
('310270', 'T-Mobile USA', '310', 'US'),
('310280', 'Contennial Puerto Rio License Corp. USA', '310', 'US'),
('310290', 'Nep Cellcorp Inc. USA', '310', 'US'),
('310300', 'Get Mobile Inc. USA', '310', 'US'),
('310310', 'T-Mobile USA', '310', 'US'),
('310320', 'Bug Tussel Wireless LLC USA', '310', 'US'),
('310330', 'AN Subsidiary LLC USA', '310', 'US'),
('310340', 'High Plains Midwest LLC, dba Wetlink Communications USA', '310', 'US'),
('310350', 'Mohave Cellular L.P. USA', '310', 'US'),
('310360', 'Cellular Network Partnership dba Pioneer Cellular USA', '310', 'US'),
('310370', 'Guamcell Cellular and Paging USA', '310', 'US'),
('310380', 'AT&T Wireless Services Inc. USA', '310', 'US'),
('310390', 'TX-11 Acquistion LLC USA', '310', 'US'),
('310400', 'Wave Runner LLC USA', '310', 'US'),
('310410', 'Cingular Wireless USA', '310', 'US'),
('310420', 'Cincinnati Bell Wireless LLC USA', '310', 'US'),
('310430', 'Alaska Digitel LLC USA', '310', 'US'),
('310440', 'Numerex Corp. USA', '310', 'US'),
('310450', 'North East Cellular Inc. USA', '310', 'US'),
('310460', 'TMP Corporation USA', '310', 'US'),
('310470', 'Guam Wireless Telephone Company USA', '310', 'US'),
('310480', 'Choice Phone LLC USA', '310', 'US'),
('310490', 'Triton PCS USA', '310', 'US'),
('310500', 'Public Service Cellular, Inc. USA', '310', 'US'),
('310510', 'Airtel Wireless LLC USA', '310', 'US'),
('310520', 'VeriSign USA', '310', 'US'),
('310530', 'West Virginia Wireless USA', '310', 'US'),
('310540', 'Oklahoma Western Telephone Company USA', '310', 'US'),
('310560', 'American Cellular Corporation USA', '310', 'US'),
('310570', 'MTPCS LLC USA', '310', 'US'),
('310580', 'PCS ONE USA', '310', 'US'),
('310590', 'Western Wireless Corporation USA', '310', 'US'),
('310600', 'New Cell Inc. dba Cellcom USA', '310', 'US'),
('310610', 'Elkhart Telephone Co. Inc. dba Epic Touch Co. USA', '310', 'US'),
('310620', 'Coleman County Telecommunications Inc. (Trans Texas PCS) USA', '310', 'US'),
('310630', 'Comtel PCS Mainstreet LP USA', '310', 'US'),
('310640', 'Airadigm Communications USA', '310', 'US'),
('310650', 'Jasper Wireless Inc. USA', '310', 'US'),
('310660', 'T-Mobile USA', '310', 'US'),
('310670', 'Northstar USA', '310', 'US'),
('310680', 'Noverr Publishing, Inc. dba NPI Wireless USA', '310', 'US'),
('310690', 'Conestoga Wireless Company USA', '310', 'US'),
('310700', 'Cross Valiant Cellular Partnership USA', '310', 'US'),
('310710', 'Arctic Slopo Telephone Association Cooperative USA', '310', 'US'),
('310720', 'Wireless Solutions International Inc. USA', '310', 'US'),
('310730', 'Sea Mobile USA', '310', 'US'),
('310740', 'Telemetrix Technologies USA', '310', 'US'),
('310750', 'East Kentucky Network LLC dba Appalachian Wireless USA', '310', 'US'),
('310760', 'Panhandle Telecommunications Systems Inc. USA', '310', 'US'),
('310770', 'Iowa Wireless Services LP USA', '310', 'US'),
('310790', 'PinPoint Communications Inc. USA', '310', 'US'),
('310800', 'T-Mobile USA', '310', 'US'),
('310810', 'Brazos Cellular Communications Ltd. USA', '310', 'US'),
('310820', 'Triton PCS License Company LLC USA', '310', 'US'),
('310830', 'Caprock Cellular Ltd. Partnership USA', '310', 'US'),
('310840', 'Edge Mobile LLC USA', '310', 'US'),
('310850', 'Aeris Communications, Inc. USA', '310', 'US'),
('310870', 'Kaplan Telephone Company Inc. USA', '310', 'US'),
('310880', 'Advantage Cellular Systems, Inc. USA', '310', 'US'),
('310890', 'Rural Cellular Corporation USA', '310', 'US'),
('310900', 'Taylor Telecommunications Ltd. USA', '310', 'US'),
('310910', 'Southern IL RSA Partnership dba First Cellular of Southern USA', '310', 'US'),
('310940', 'Poka Lambro Telecommunications Ltd. USA', '310', 'US'),
('310950', 'Texas RSA 1 dba XIT Cellular USA', '310', 'US'),
('310970', 'Globalstar USA', '310', 'US'),
('310980', 'AT&T Wireless Services Inc. USA', '310', 'US'),
('310990', 'Alaska Digitel USA', '310', 'US'),
('311000', 'Mid-Tex Cellular Ltd. USA', '311', 'US'),
('311010', 'Chariton Valley Communications Corp., Inc. USA', '311', 'US'),
('311020', 'Missouri RSA No. 5 Partnership USA', '311', 'US'),
('311030', 'Indigo Wireless, Inc. USA', '311', 'US'),
('311040', 'Commet Wireless, LLC USA', '311', 'US'),
('311070', 'Easterbrooke Cellular Corporation USA', '311', 'US'),
('311080', 'Pine Telephone Company dba Pine Cellular USA', '311', 'US'),
('311090', 'Siouxland PCS USA', '311', 'US'),
('311100', 'High Plains Wireless L.P. USA', '311', 'US'),
('311110', 'High Plains Wireless L.P. USA', '311', 'US'),
('311120', 'Choice Phone LLC USA', '311', 'US'),
('311130', 'Amarillo License L.P. USA', '311', 'US'),
('311140', 'MBO Wireless Inc./Cross Telephone Company USA', '311', 'US'),
('311150', 'Wilkes Cellular Inc. USA', '311', 'US'),
('311160', 'Endless Mountains Wireless, LLC USA', '311', 'US'),
('311180', 'Cingular Wireless, Licensee Pacific Telesis Mobile Services, LLC USA', '311', 'US'),
('311190', 'Cellular Properties Inc. USA', '311', 'US'),
('311200', 'ARINC USA', '311', 'US'),
('311210', 'Farmers Cellular Telephone USA', '311', 'US'),
('311230', 'Cellular South Inc. USA', '311', 'US'),
('311250', 'Wave Runner LLC USA', '311', 'US'),
('311260', 'SLO Cellular Inc. dba CellularOne of San Luis Obispo USA', '311', 'US'),
('311270', 'Alltel Communications Inc. USA', '311', 'US'),
('311271', 'Alltel Communications Inc. USA', '311', 'US'),
('311272', 'Alltel Communications Inc. USA', '311', 'US'),
('311273', 'Alltel Communications Inc. USA', '311', 'US'),
('311274', 'Alltel Communications Inc. USA', '311', 'US'),
('311275', 'Alltel Communications Inc. USA', '311', 'US'),
('311276', 'Alltel Communications Inc. USA', '311', 'US'),
('311277', 'Alltel Communications Inc. USA', '311', 'US'),
('311278', 'Alltel Communications Inc. USA', '311', 'US'),
('311279', 'Alltel Communications Inc. USA', '311', 'US'),
('311280', 'Verizon Wireless USA', '311', 'US'),
('311281', 'Verizon Wireless USA', '311', 'US'),
('311282', 'Verizon Wireless USA', '311', 'US'),
('311283', 'Verizon Wireless USA', '311', 'US'),
('311284', 'Verizon Wireless USA', '311', 'US'),
('311285', 'Verizon Wireless USA', '311', 'US'),
('311286', 'Verizon Wireless USA', '311', 'US'),
('311287', 'Verizon Wireless USA', '311', 'US'),
('311288', 'Verizon Wireless USA', '311', 'US'),
('311289', 'Verizon Wireless USA', '311', 'US'),
('311290', 'Pinpoint Wireless Inc. USA', '311', 'US'),
('311320', 'Commnet Wireless LLC USA', '311', 'US'),
('311340', 'Illinois Valley Cellular USA', '311', 'US'),
('311380', 'New Dimension Wireless Ltd. USA', '311', 'US'),
('311390', 'Midwest Wireless Holdings LLC USA', '311', 'US'),
('311400', 'Salmon PCS LLC USA', '311', 'US'),
('311410', 'Iowa RSA No.2 Ltd Partnership USA', '311', 'US'),
('311420', 'Northwest Missouri Cellular Limited Partnership USA', '311', 'US'),
('311430', 'RSA 1 Limited Partnership dba Cellular 29 Plus USA', '311', 'US'),
('311440', 'Bluegrass Cellular LLC USA', '311', 'US'),
('311450', 'Panhandle Telecommunication Systems Inc. USA', '311', 'US'),
('316010', 'Nextel Communications Inc. USA', '316', 'US'),
('316011', 'Southern Communications Services Inc. USA', '316', 'US'),
('334020', 'Telcel Mexico', '334', 'MX'),
('338020', 'Cable & Wireless Jamaica Ltd.', '338', 'JM'),
('338050', 'Mossel (Jamaica) Ltd.', '338', 'JM'),
('34001', 'Orange Carabe Mobiles Guadeloupe', '340', 'FW'),
('34002', 'Outremer Telecom Guadeloupe', '340', 'FW'),
('34003', 'Saint Martin et Saint Barthelemy Telcell Sarl Guadeloupe', '340', 'FW'),
('34020', 'Bouygues Telecom Caraibe Guadeloupe', '340', 'FW'),
('342600', 'Cable & Wireless (Barbados) Ltd.', '342', 'BB '),
('342820', 'Sunbeach Communications Barbados', '342', 'BB '),
('344030', 'APUA PCS Antigua ', '344', 'AG'),
('344920', 'Cable & Wireless (Antigua)', '344', 'AG'),
('344930', 'AT&T Wireless (Antigua)', '344', 'AG'),
('346140', 'Cable & Wireless (Cayman)', '346', 'KY'),
('348570', 'Caribbean Cellular Telephone, Boatphone Ltd.', '348', 'BVI'),
('35001', 'Telecom', '350', 'BM'),
('36251', 'TELCELL GSM Netherlands Antilles', '362', 'AN'),
('36269', 'CT GSM Netherlands Antilles', '362', 'AN'),
('36291', 'SETEL GSM Netherlands Antilles', '362', 'AN'),
('36301', 'Setar GSM Aruba', '363', 'AW'),
('365010', 'Weblinks Limited Anguilla', '365', 'AI'),
('36801', 'ETECSA Cuba', '368', 'CU'),
('37001', 'Orange Dominicana, S.A.', '370', 'DO'),
('37002', 'Verizon Dominicana S.A.', '370', 'DO'),
('37003', 'Tricom S.A. Dominican Rep.', '370', 'DO'),
('37004', 'CentennialDominicana', '370', 'DO'),
('37201', 'Comcel Haiti', '372', 'HT'),
('37202', 'Digicel Haiti', '372', 'HT'),
('37203', 'Rectel Haiti', '372', 'HT'),
('37412', 'TSTT Mobile Trinidad and Tobago', '374', 'TT'),
('374130', 'Digicel Trinidad and Tobago Ltd.', '374', 'TT'),
('374140', 'LaqTel Ltd. Trinidad and Tobago', '374', 'TT'),
('40001', 'Azercell Limited Liability Joint Venture', '400', 'AZ'),
('40002', 'Bakcell Limited Liability Company Azerbaijan', '400', 'AZ'),
('40003', 'Catel JV Azerbaijan', '400', 'AZ'),
('40004', 'Azerphone LLC', '400', 'AZ'),
('40101', 'Kar-Tel llc Kazakhstan', '401', 'KZ'),
('40102', 'TSC Kazak Telecom Kazakhstan', '401', 'KZ'),
('40211', 'Bhutan Telecom Ltd', '402', 'BT '),
('40217', 'B-Mobile of Bhutan Telecom', '402', 'BT '),
('40401', 'Aircell Digilink India Ltd.,', '404', 'IN'),
('40402', 'Bharti Mobile Ltd. India', '404', 'IN'),
('40403', 'Bharti Telenet Ltd. India', '404', 'IN'),
('40404', 'Idea Cellular Ltd. India', '404', 'IN'),
('40405', 'Fascel Ltd. India', '404', 'IN'),
('40406', 'Bharti Mobile Ltd. India', '404', 'IN'),
('40407', 'Idea Cellular Ltd. India', '404', 'IN'),
('40409', 'Reliance Telecom Private Ltd. India', '404', 'IN'),
('40410', 'Bharti Cellular Ltd. India', '404', 'IN'),
('40411', 'Sterling Cellular Ltd. India', '404', 'IN'),
('40412', 'Escotel Mobile Communications Pvt Ltd. India', '404', 'IN'),
('40413', 'Hutchinson Essar South Ltd. India', '404', 'IN'),
('40414', 'Spice Communications Ltd. India', '404', 'IN'),
('40415', 'Aircell Digilink India Ltd.', '404', 'IN'),
('40416', 'Hexcom India', '404', 'IN'),
('40418', 'Reliance Telecom Private Ltd. India', '404', 'IN'),
('40419', 'Escotel Mobile Communications Pvt Ltd. India', '404', 'IN'),
('40420', 'Hutchinson Max Telecom India', '404', 'IN'),
('40421', 'BPL Mobile Communications Ltd. India', '404', 'IN'),
('40422', 'Idea Cellular Ltd. India', '404', 'IN'),
('40424', 'Idea Cellular Ltd. India', '404', 'IN'),
('40427', 'BPL Cellular Ltd. India', '404', 'IN'),
('40430', 'Usha Martin Telecom Ltd. India', '404', 'IN'),
('40431', 'Bharti Mobinet Ltd. India', '404', 'IN'),
('40434', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40436', 'Reliance Telecom Private Ltd. India', '404', 'IN'),
('40438', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40440', 'Bharti Mobinet Ltd. India', '404', 'IN'),
('40441', 'RPG Cellular India', '404', 'IN'),
('40442', 'Aircel Ltd. India', '404', 'IN'),
('40443', 'BPL Mobile Cellular Ltd. India', '404', 'IN'),
('40444', 'Spice Communications Ltd. India', '404', 'IN'),
('40446', 'BPL Cellular Ltd. India', '404', 'IN'),
('40449', 'Bharti Mobile Ltd. India', '404', 'IN'),
('40450', 'Reliance Telecom Private Ltd. India', '404', 'IN'),
('40451', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40452', 'Reliance Telecom Private Ltd. India', '404', 'IN'),
('40453', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40454', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40455', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40456', 'Escotel Mobile Communications Pvt Ltd. India', '404', 'IN'),
('40457', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40458', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40459', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40460', 'Aircell Digilink India Ltd.', '404', 'IN'),
('40462', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40464', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40466', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40467', 'Reliance Telecom Private Ltd. India', '404', 'IN'),
('40468', 'Mahanagar Telephone Nigam Ltd. India', '404', 'IN'),
('40469', 'Mahanagar Telephone Nigam Ltd. India', '404', 'IN'),
('40470', 'Hexicom India', '404', 'IN'),
('40471', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40472', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40473', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40474', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40475', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40476', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40477', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40478', 'BTA Cellcom Ltd. India', '404', 'IN'),
('40480', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40481', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN'),
('40482', 'Escorts Telecom Ltd. India', '404', 'IN'),
('40483', 'Reliable Internet Services Ltd. India', '404', 'IN'),
('40484', 'Hutchinson Essar South Ltd. India', '404', 'IN'),
('40485', 'Reliance Telecom Private Ltd. India', '404', 'IN'),
('40486', 'Hutchinson Essar South Ltd. India', '404', 'IN'),
('40487', 'Escorts Telecom Ltd. India', '404', 'IN'),
('40488', 'Escorts Telecom Ltd. India', '404', 'IN'),
('40489', 'Escorts Telecom Ltd. India', '404', 'IN'),
('40490', 'Bharti Cellular Ltd. India', '404', 'IN'),
('40492', 'Bharti Cellular Ltd. India', '404', 'IN'),
('40493', 'Bharti Cellular Ltd. India', '404', 'IN'),
('40494', 'Bharti Cellular Ltd. India', '404', 'IN'),
('40495', 'Bharti Cellular Ltd. India', '404', 'IN'),
('40496', 'Bharti Cellular Ltd. India', '404', 'IN'),
('40497', 'Bharti Cellular Ltd. India', '404', 'IN'),
('40498', 'Bharti Cellular Ltd. India', '404', 'IN'),
('41001', 'Mobilink Pakistan', '410', 'PK'),
('41003', 'PAK Telecom Mobile Ltd. (UFONE) Pakistan', '410', 'PK'),
('41201', 'AWCC Afghanistan', '412', 'AF'),
('41220', 'Roshan Afghanistan', '412', 'AF'),
('41230', 'New1 Afghanistan', '412', 'AF'),
('41240', 'Areeba Afghanistan', '412', 'AF'),
('41288', 'Afghan Telecom', '412', 'AF'),
('41302', 'MTN Network Ltd. Sri Lanka', '413', 'LK'),
('41303', 'Celtel Lanka Ltd. Sri Lanka', '413', 'LK'),
('41401', 'Myanmar Post and Telecommunication', '414', 'MM'),
('41532', 'Cellis Lebanon', '415', 'LB'),
('41533', 'Cellis Lebanon', '415', 'LB'),
('41534', 'Cellis Lebanon', '415', 'LB'),
('41535', 'Cellis Lebanon', '415', 'LB'),
('41536', 'Libancell', '415', 'LB'),
('41537', 'Libancell', '415', 'LB'),
('41538', 'Libancell', '415', 'LB'),
('41539', 'Libancell', '415', 'LB'),
('41601', 'Fastlink Jordan', '416', 'JO'),
('41602', 'Xpress Jordan', '416', 'JO'),
('41603', 'Umniah Jordan', '416', 'JO'),
('41677', 'Mobilecom Jordan', '416', 'JO'),
('41701', 'Syriatel', '417', 'SY'),
('41702', 'Spacetel Syria', '417', 'SY'),
('41709', 'Syrian Telecom', '417', 'SY'),
('41902', 'Mobile Telecommunications Company Kuwait', '419', 'KW'),
('41903', 'Wataniya Telecom Kuwait', '419', 'KW'),
('42001', 'Saudi Telecom', '420', 'SA'),
('42101', 'Yemen Mobile Phone Company', '421', 'YE'),
('42102', 'Spacetel Yemen', '421', 'YE'),
('42202', 'Oman Mobile Telecommunications Company (Oman Mobile)', '422', 'OM'),
('42203', 'Oman Qatari Telecommunications Company (Nawras)', '422', 'OM'),
('42204', 'Oman Telecommunications Company (Omantel)', '422', 'OM'),
('42402', 'Etisalat United Arab Emirates', '424', 'AE'),
('42501', 'Partner Communications Co. Ltd. Israel', '425', 'IL'),
('42502', 'Cellcom Israel Ltd.', '425', 'IL'),
('42503', 'Pelephone Communications Ltd. Israel', '425', 'IL'),
('42601', 'BHR Mobile Plus Bahrain', '426', 'BH'),
('42701', 'QATARNET', '427', 'QA'),
('42899', 'Mobicom Mongolia', '428', 'MN'),
('42901', 'Nepal Telecommunications', '429', 'NP'),
('43211', 'Telecommunication Company of Iran (TCI)', '432', 'IR'),
('43214', 'Telecommunication Kish Co. (KIFZO) Iran', '432', 'IR'),
('43219', 'Telecommunication Company of Iran (TCI) Isfahan Celcom', '432', 'IR'),
('43401', 'Buztel Uzbekistan', '434', 'UZ'),
('43402', 'Uzmacom Uzbekistan', '434', 'UZ'),
('43404', 'Daewoo Unitel Uzbekistan', '434', 'UZ'),
('43405', 'Coscom Uzbekistan', '434', 'UZ'),
('43407', 'Uzdunrobita Uzbekistan', '434', 'UZ'),
('43601', 'JC Somoncom Tajikistan', '436', 'TJ'),
('43602', 'CJSC Indigo Tajikistan', '436', 'TJ'),
('43603', 'TT mobile Tajikistan', '436', 'TJ'),
('43604', 'Josa Babilon-T Tajikistan', '436', 'TJ'),
('43605', 'CTJTHSC Tajik-tel', '436', 'TJ'),
('43701', 'Bitel GSM Kyrgyzstan', '437', 'KG'),
('43801', 'Barash Communication Technologies (BCTI) Turkmenistan', '438', 'TM'),
('43802', 'TM-Cell Turkmenistan', '438', 'TM'),
('44001', 'NTT DoCoMo, Inc. Japan', '440', 'JP'),
('44002', 'NTT DoCoMo Kansai, Inc.  Japan', '440', 'JP'),
('44003', 'NTT DoCoMo Hokuriku, Inc. Japan', '440', 'JP'),
('44004', 'Vodafone Japan', '440', 'JP'),
('44006', 'Vodafone Japan', '440', 'JP'),
('44007', 'KDDI Corporation Japan', '440', 'JP'),
('44008', 'KDDI Corporation Japan', '440', 'JP'),
('44009', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP'),
('44010', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP'),
('44011', 'NTT DoCoMo Tokai Inc. Japan', '440', 'JP'),
('44012', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44013', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44014', 'NTT DoCoMo Tohoku Inc. Japan', '440', 'JP'),
('44015', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44016', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44017', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44018', 'NTT DoCoMo Tokai Inc. Japan', '440', 'JP'),
('44019', 'NTT DoCoMo Hokkaido Japan', '440', 'JP'),
('44020', 'NTT DoCoMo Hokuriku Inc. Japan', '440', 'JP'),
('44021', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44022', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP'),
('44023', 'NTT DoCoMo Tokai Inc. Japan', '440', 'JP'),
('44024', 'NTT DoCoMo Chugoku Inc. Japan', '440', 'JP'),
('44025', 'NTT DoCoMo Hokkaido Inc. Japan', '440', 'JP'),
('44026', 'NTT DoCoMo Kyushu Inc. Japan', '440', 'JP'),
('44027', 'NTT DoCoMo Tohoku Inc. Japan', '440', 'JP'),
('44028', 'NTT DoCoMo Shikoku Inc. Japan', '440', 'JP'),
('44029', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44030', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44031', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP'),
('44032', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44033', 'NTT DoCoMo Tokai Inc. Japan', '440', 'JP'),
('44034', 'NTT DoCoMo Kyushu Inc. Japan', '440', 'JP'),
('44035', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP'),
('44036', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44037', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44038', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44039', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44040', 'Vodafone Japan', '440', 'JP'),
('44041', 'Vodafone Japan', '440', 'JP'),
('44042', 'Vodafone Japan', '440', 'JP'),
('44043', 'Vodafone Japan', '440', 'JP'),
('44044', 'Vodafone Japan', '440', 'JP'),
('44045', 'Vodafone Japan', '440', 'JP'),
('44046', 'Vodafone Japan', '440', 'JP'),
('44047', 'Vodafone Japan', '440', 'JP'),
('44048', 'Vodafone Japan', '440', 'JP'),
('44049', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44050', 'KDDI Corporation Japan', '440', 'JP'),
('44051', 'KDDI Corporation Japan', '440', 'JP'),
('44052', 'KDDI Corporation Japan', '440', 'JP'),
('44053', 'KDDI Corporation Japan', '440', 'JP'),
('44054', 'KDDI Corporation Japan', '440', 'JP'),
('44055', 'KDDI Corporation Japan', '440', 'JP'),
('44056', 'KDDI Corporation Japan', '440', 'JP'),
('44058', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP'),
('44060', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP'),
('44061', 'NTT DoCoMo Chugoku Inc. Japan', '440', 'JP'),
('44062', 'NTT DoCoMo Kyushu Inc. Japan', '440', 'JP'),
('44063', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44064', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44065', 'NTT DoCoMo Shikoku Inc. Japan', '440', 'JP'),
('44066', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44067', 'NTT DoCoMo Tohoku Inc. Japan', '440', 'JP'),
('44068', 'NTT DoCoMo Kyushu Inc. Japan', '440', 'JP'),
('44069', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44070', 'KDDI Corporation Japan', '440', 'JP'),
('44071', 'KDDI Corporation Japan', '440', 'JP'),
('44072', 'KDDI Corporation Japan', '440', 'JP'),
('44073', 'KDDI Corporation Japan', '440', 'JP'),
('44074', 'KDDI Corporation Japan', '440', 'JP'),
('44075', 'KDDI Corporation Japan', '440', 'JP'),
('44076', 'KDDI Corporation Japan', '440', 'JP'),
('44077', 'KDDI Corporation Japan', '440', 'JP'),
('44078', 'Okinawa Cellular Telephone Japan', '440', 'JP'),
('44079', 'KDDI Corporation Japan', '440', 'JP'),
('44080', 'TU-KA Cellular Tokyo Inc. Japan', '440', 'JP'),
('44081', 'TU-KA Cellular Tokyo Inc. Japan', '440', 'JP'),
('44082', 'TU-KA Phone Kansai Inc. Japan', '440', 'JP'),
('44083', 'TU-KA Cellular Tokai Inc. Japan', '440', 'JP'),
('44084', 'TU-KA Phone Kansai Inc. Japan', '440', 'JP'),
('44085', 'TU-KA Cellular Tokai Inc. Japan', '440', 'JP'),
('44086', 'TU-KA Cellular Tokyo Inc. Japan', '440', 'JP'),
('44087', 'NTT DoCoMo Chugoku Inc. Japan', '440', 'JP'),
('44088', 'KDDI Corporation Japan', '440', 'JP'),
('44089', 'KDDI Corporation Japan', '440', 'JP'),
('44090', 'Vodafone Japan', '440', 'JP'),
('44092', 'Vodafone Japan', '440', 'JP'),
('44093', 'Vodafone Japan', '440', 'JP'),
('44094', 'Vodafone Japan', '440', 'JP'),
('44095', 'Vodafone Japan', '440', 'JP'),
('44096', 'Vodafone Japan', '440', 'JP'),
('44097', 'Vodafone Japan', '440', 'JP'),
('44098', 'Vodafone Japan', '440', 'JP'),
('44099', 'NTT DoCoMo Inc. Japan', '440', 'JP'),
('44140', 'NTT DoCoMo Inc. Japan', '441', 'JP'),
('44141', 'NTT DoCoMo Inc. Japan', '441', 'JP'),
('44142', 'NTT DoCoMo Inc. Japan', '441', 'JP'),
('44143', 'NTT DoCoMo Kansai Inc. Japan', '441', 'JP'),
('44144', 'NTT DoCoMo Chugoku Inc. Japan', '441', 'JP'),
('44145', 'NTT DoCoMo Shikoku Inc. Japan', '441', 'JP'),
('44150', 'TU-KA Cellular Tokyo Inc. Japan', '441', 'JP'),
('44151', 'TU-KA Phone Kansai Inc. Japan', '441', 'JP'),
('44161', 'Vodafone Japan', '441', 'JP'),
('44162', 'Vodafone Japan', '441', 'JP'),
('44163', 'Vodafone Japan', '441', 'JP'),
('44164', 'Vodafone Japan', '441', 'JP'),
('44165', 'Vodafone Japan', '441', 'JP'),
('44170', 'KDDI Corporation Japan', '441', 'JP'),
('44190', 'NTT DoCoMo Inc. Japan', '441', 'JP'),
('44191', 'NTT DoCoMo Inc. Japan', '441', 'JP'),
('44192', 'NTT DoCoMo Inc. Japan', '441', 'JP'),
('44193', 'NTT DoCoMo Hokkaido Inc. Japan', '441', 'JP'),
('44194', 'NTT DoCoMo Tohoku Inc. Japan', '441', 'JP'),
('44198', 'NTT DoCoMo Kyushu Inc. Japan', '441', 'JP'),
('44199', 'NTT DoCoMo Kyushu Inc. Japan', '441', 'JP'),
('45201', 'Mobifone Vietnam', '452', 'VN'),
('45202', 'Vinaphone Vietnam', '452', 'VN'),
('45400', 'CSL', '454', 'HK'),
('45401', 'MVNO/CITIC Hong Kong', '454', 'HK'),
('45402', '3G Radio System/HKCSL3G Hong Kong', '454', 'HK'),
('45403', 'Hutchison 3G', '454', 'HK'),
('45404', 'GSM900/GSM1800/Hutchison Hong Kong', '454', 'HK'),
('45405', 'CDMA/Hutchison Hong Kong', '454', 'HK'),
('45406', 'SMC', '454', 'HK'),
('45407', 'MVNO/China Unicom International Ltd. Hong Kong', '454', 'HK'),
('45408', 'MVNO/Trident Hong Kong', '454', 'HK'),
('45409', 'MVNO/China Motion Telecom (HK) Ltd. Hong Kong', '454', 'HK'),
('45410', 'GSM1800New World PCS Ltd. Hong Kong', '454', 'HK'),
('45411', 'MVNO/CHKTL Hong Kong', '454', 'HK'),
('45412', 'PEOPLES', '454', 'HK'),
('45415', '3G Radio System/SMT3G Hong Kong', '454', 'HK'),
('45416', 'GSM1800/Mandarin Communications Ltd. Hong Kong', '454', 'HK'),
('45418', 'GSM7800/Hong Kong CSL Ltd.', '454', 'HK'),
('45419', 'Sunday3G', '454', 'HK'),
('45500', 'Smartone Mobile Communications (Macao) Ltd.', '455', 'MO'),
('45501', 'CTM GSM Macao', '455', 'MO'),
('45503', 'Hutchison Telecom Macao', '455', 'MO'),
('45601', 'Mobitel (Cam GSM) Cambodia', '456', 'KH'),
('45602', 'Samart (Casacom) Cambodia', '456', 'KH'),
('45603', 'S Telecom (CDMA) (reserved) Cambodia', '456', 'KH'),
('45618', 'Camshin (Shinawatra) Cambodia', '456', 'KH'),
('45701', 'Lao Telecommunications', '457', 'LA'),
('45702', 'ETL Mobile Lao', '457', 'LA'),
('45708', 'Millicom Lao', '457', 'LA'),
('46000', 'China Mobile', '460', 'CN'),
('46001', 'China Unicom', '460', 'CN'),
('46002', 'China Mobile', '460', 'CN'),
('46003', 'China Telecom', '460', 'CN'),
('46004', 'China Satellite Global Star Network', '460', 'CN'),
('46601', 'Far EasTone', '466', 'TW'),
('46606', 'TUNTEX', '466', 'TW'),
('46668', 'ACeS', '466', 'TW'),
('46688', 'KGT', '466', 'TW'),
('46689', 'KGT', '466', 'TW'),
('46692', 'Chunghwa', '466', 'TW'),
('46693', 'MobiTai', '466', 'TW'),
('46697', 'TWN GSM', '466', 'TW'),
('46699', 'TransAsia', '466', 'TW'),
('47001', 'GramenPhone Bangladesh', '470', 'BD'),
('47002', 'Aktel Bangladesh', '470', 'BD'),
('47003', 'Mobile 2000 Bangladesh', '470', 'BD'),
('47201', 'DhiMobile Maldives', '472', 'MV'),
('50200', 'Art900 Malaysia', '502', 'MY'),
('50212', 'Maxis Malaysia', '502', 'MY'),
('50213', 'TM Touch Malaysia', '502', 'MY'),
('50216', 'DiGi', '502', 'MY'),
('50217', 'TimeCel Malaysia', '502', 'MY'),
('50219', 'CelCom Malaysia', '502', 'MY'),
('50501', 'Telstra Corporation Ltd. Australia', '505', 'AU'),
('50502', 'Optus Mobile Pty. Ltd. Australia', '505', 'AU'),
('50503', 'Vodafone Network Pty. Ltd. Australia', '505', 'AU'),
('50504', 'Department of Defence Australia', '505', 'AU'),
('50505', 'The Ozitel Network Pty. Ltd. Australia', '505', 'AU'),
('50506', 'Hutchison 3G Australia Pty. Ltd.', '505', 'AU'),
('50507', 'Vodafone Network Pty. Ltd. Australia', '505', 'AU'),
('50508', 'One.Tel GSM 1800 Pty. Ltd. Australia', '505', 'AU'),
('50509', 'Airnet Commercial Australia Ltd.', '505', 'AU'),
('50511', 'Telstra Corporation Ltd. Australia', '505', 'AU'),
('50512', 'Hutchison Telecommunications (Australia) Pty. Ltd.', '505', 'AU'),
('50514', 'AAPT Ltd. Australia', '505', 'AU'),
('50515', '3GIS Pty Ltd. (Telstra & Hutchison 3G) Australia', '505', 'AU'),
('50524', 'Advanced Communications Technologies Pty. Ltd. Australia', '505', 'AU'),
('50571', 'Telstra Corporation Ltd. Australia', '505', 'AU'),
('50572', 'Telstra Corporation Ltd. Australia', '505', 'AU'),
('50588', 'Localstar Holding Pty. Ltd. Australia', '505', 'AU'),
('50590', 'Optus Ltd. Australia', '505', 'AU'),
('50599', 'One.Tel GSM 1800 Pty. Ltd. Australia', '505', 'AU'),
('51000', 'PSN Indonesia', '510', 'ID'),
('51001', 'Satelindo Indonesia', '510', 'ID'),
('51008', 'Natrindo (Lippo Telecom) Indonesia', '510', 'ID'),
('51010', 'Telkomsel Indonesia', '510', 'ID'),
('51011', 'Excelcomindo Indonesia', '510', 'ID'),
('51021', 'Indosat - M3 Indonesia', '510', 'ID'),
('51028', 'Komselindo Indonesia', '510', 'ID'),
('51501', 'Islacom Philippines', '515', 'PH'),
('51502', 'Globe Telecom Philippines', '515', 'PH'),
('51503', 'Smart Communications Philippines', '515', 'PH'),
('51505', 'Digitel Philippines', '515', 'PH'),
('52000', 'CAT CDMA Thailand', '520', 'TH'),
('52001', 'AIS GSM Thailand', '520', 'TH'),
('52015', 'ACT Mobile Thailand', '520', 'TH'),
('52501', 'SingTel ST GSM900 Singapore', '525', 'SG'),
('52502', 'SingTel ST GSM1800 Singapore', '525', 'SG'),
('52503', 'MobileOne Singapore', '525', 'SG'),
('52505', 'STARHUB-SGP', '525', 'SG'),
('52512', 'Digital Trunked Radio Network Singapore', '525', 'SG'),
('52811', 'DST Com Brunei ', '528', 'BN'),
('53000', 'Reserved for AMPS MIN based IMSIs New Zealand', '530', 'NZ'),
('53001', 'Vodafone New Zealand GSM Mobile Network', '530', 'NZ'),
('53002', 'Teleom New Zealand CDMA Mobile Network', '530', 'NZ'),
('53003', 'Walker Wireless Ltd. New Zealand', '530', 'NZ'),
('53028', 'Econet Wireless New Zealand GSM Mobile Network', '530', 'NZ'),
('53701', 'Pacific Mobile Communications Papua New Guinea', '537', 'PG'),
('53702', 'Dawamiba PNG Ltd Papua New Guinea', '537', 'PG'),
('53703', 'Digicel Ltd Papua New Guinea', '537', 'PG'),
('53901', 'Tonga Communications Corporation', '539', 'TO'),
('53943', 'Shoreline Communication Tonga', '539', 'TO'),
('54101', 'SMILE Vanuatu', '541', 'VU'),
('54201', 'Vodafone Fiji', '542', 'FJ'),
('54411', 'Blue Sky', '544', 'AS'),
('54601', 'OPT Mobilis New Caledonia', '546', 'NC'),
('54720', 'Tikiphone French Polynesia', '547', 'PF'),
('54801', 'Telecom Cook', '548', 'CK'),
('54901', 'Telecom Samoa Cellular Ltd.', '549', 'WS'),
('54927', 'GoMobile SamoaTel Ltd', '549', 'WS'),
('55001', 'FSM Telecom Micronesia', '550', 'FM'),
('55201', 'Palau National Communications Corp. (a.k.a. PNCC)', '552', 'PW'),
('60201', 'EMS - Mobinil Egypt', '602', 'EG'),
('60202', 'Vodafone Egypt', '602', 'EG'),
('60301', 'Algrie Telecom', '603', 'DZ'),
('60302', 'Orascom Telecom Algrie', '603', 'DZ'),
('60400', 'Meditelecom (GSM) Morocco', '604', 'MA'),
('60401', 'Ittissalat Al Maghrid Morocco', '604', 'MA'),
('60502', 'Tunisie Telecom', '605', 'TN'),
('60503', 'Orascom Telecom Tunisia', '605', 'TN'),
('60701', 'Gamcel Gambia', '607', 'GM'),
('60702', 'Africell Gambia', '607', 'GM'),
('60703', 'Comium Services Ltd Gambia', '607', 'GM'),
('60801', 'Sonatel Senegal', '608', 'SN'),
('60802', 'Sentel GSM Senegal', '608', 'SN'),
('60901', 'Mattel S.A.', '609', 'MR'),
('60902', 'Chinguitel S.A. ', '609', 'MR'),
('60910', 'Mauritel Mobiles  ', '609', 'MR'),
('61001', 'Malitel', '610', 'ML'),
('61101', 'Spacetel Guinea', '611', 'GN'),
('61102', 'Sotelgui Guinea', '611', 'GN'),
('61202', 'Atlantique Cellulaire Cote d Ivoire', '612', 'CI'),
('61203', 'Orange Cote dIvoire', '612', 'CI'),
('61204', 'Comium Cote d Ivoire', '612', 'CI'),
('61205', 'Loteny Telecom Cote d Ivoire', '612', 'CI'),
('61206', 'Oricel Cote d Ivoire', '612', 'CI'),
('61207', 'Aircomm Cote d Ivoire', '612', 'CI'),
('61302', 'Celtel Burkina Faso', '613', 'BF'),
('61303', 'Telecel Burkina Faso', '613', 'BF'),
('61401', 'Sahel.Com Niger', '614', 'NE'),
('61402', 'Celtel Niger', '614', 'NE'),
('61403', 'Telecel Niger', '614', 'NE'),
('61501', 'Togo Telecom', '615', 'TG'),
('61601', 'Libercom Benin', '616', 'BJ'),
('61602', 'Telecel Benin', '616', 'BJ'),
('61603', 'Spacetel Benin', '616', 'BJ'),
('61701', 'Cellplus Mauritius', '617', 'MU'),
('61702', 'Mahanagar Telephone (Mauritius) Ltd.', '617', 'MU'),
('61710', 'Emtel Mauritius', '617', 'MU'),
('61804', 'Comium Liberia', '618', 'LR'),
('61901', 'Celtel Sierra Leone', '619', 'SL'),
('61902', 'Millicom Sierra Leone', '619', 'SL'),
('61903', 'Africell Sierra Leone', '619', 'SL'),
('61904', 'Comium (Sierra Leone) Ltd.', '619', 'SL'),
('61905', 'Lintel (Sierra Leone) Ltd.', '619', 'SL'),
('61925', 'Mobitel Sierra Leone', '619', 'SL'),
('61940', 'Datatel (SL) Ltd GSM Sierra Leone', '619', 'SL'),
('61950', 'Dtatel (SL) Ltd CDMA Sierra Leone', '619', 'SL'),
('62001', 'Spacefon Ghana', '620', 'GH'),
('62002', 'Ghana Telecom Mobile', '620', 'GH'),
('62003', 'Mobitel Ghana', '620', 'GH'),
('62004', 'Kasapa Telecom Ltd. Ghana', '620', 'GH'),
('62120', 'Econet Wireless Nigeria Ltd.', '621', 'NG'),
('62130', 'MTN Nigeria Communications', '621', 'NG'),
('62140', 'Nigeria Telecommunications Ltd.', '621', 'NG'),
('62201', 'Celtel Chad', '622', 'TD'),
('62202', 'Tchad Mobile', '622', 'TD'),
('62301', 'Centrafrique Telecom Plus (CTP)', '623', 'CF'),
('62302', 'Telecel Centrafrique (TC)', '623', 'CF'),
('62303', 'Celca (Socatel) Central African Rep.', '623', 'CF'),
('62401', 'Mobile Telephone Networks Cameroon', '624', 'CM'),
('62402', 'Orange Cameroun', '624', 'CM'),
('62501', 'Cabo Verde Telecom', '625', 'CV'),
('62601', 'Companhia Santomese de Telecomunicacoes', '626', 'ST'),
('62701', 'Guinea Ecuatorial de Telecomunicaciones Sociedad Anonima', '627', 'GQ'),
('62801', 'Libertis S.A. Gabon', '628', 'GA'),
('62802', 'Telecel Gabon S.A.', '628', 'GA'),
('62803', 'Celtel Gabon S.A.', '628', 'GA'),
('62901', 'Celtel Congo', '629', 'CG'),
('62910', 'Libertis Telecom Congo', '629', 'CG'),
('63001', 'Vodacom Congo RDC sprl', '630', 'CD'),
('63005', 'Supercell Sprl Congo', '630', 'CD'),
('63086', 'Congo-Chine Telecom s.a.r.l.', '630', 'CD'),
('63102', 'Unitel Angola', '631', 'AO'),
('63201', 'Guinetel S.A. Guinea-Bissau', '632', 'GW'),
('63202', 'Spacetel Guine-Bissau S.A.', '632', 'GW'),
('63301', 'Cable & Wireless (Seychelles) Ltd.', '633', 'SC'),
('63302', 'Mediatech International Ltd. Seychelles', '633', 'SC'),
('63310', 'Telecom (Seychelles) Ltd.', '633', 'SC'),
('63401', 'SD Mobitel Sudan', '634', 'MZ'),
('63402', 'Areeba-Sudan', '634', 'MZ'),
('63510', 'MTN Rwandacell', '635', 'RW'),
('63601', 'ETH MTN Ethiopia', '636', 'ET'),
('63730', 'Golis Telecommunications Company Somalia', '637', 'SO'),
('63801', 'Evatis Djibouti', '638', 'DJ'),
('63902', 'Safaricom Ltd. Kenya', '639', 'KE'),
('63903', 'Kencell Communications Ltd. Kenya', '639', 'KE'),
('64002', 'MIC (T) Ltd. Tanzania', '640', 'TZ'),
('64003', 'Zantel Tanzania', '640', 'TZ'),
('64004', 'Vodacom (T) Ltd. Tanzania', '640', 'TZ'),
('64005', 'Celtel (T) Ltd. Tanzania', '640', 'TZ'),
('64101', 'Celtel Uganda', '641', 'UG'),
('64110', 'MTN Uganda Ltd.', '641', 'UG'),
('64111', 'Uganda Telecom Ltd.', '641', 'UG'),
('64201', 'Spacetel Burundi', '642', 'BI'),
('64202', 'Safaris Burundi', '642', 'BI'),
('64203', 'Telecel Burundi Company', '642', 'BI'),
('64301', 'T.D.M. GSM Mozambique', '643', 'MZ'),
('64304', 'VM Sarl Mozambique', '643', 'MZ'),
('64501', 'Celtel Zambia Ltd.', '645', 'ZM'),
('64502', 'Telecel Zambia Ltd.', '645', 'ZM'),
('64503', 'Zamtel Zambia', '645', 'ZM'),
('64601', 'MADACOM Madagascar', '646', 'MG'),
('64602', 'Orange Madagascar', '646', 'MG'),
('64604', 'Telecom Malagasy Mobile Madagascar', '646', 'MG'),
('64700', 'Orange La Reunion', '647', 'RE'),
('64702', 'Outremer Telecom', '647', 'RE'),
('64710', 'Societe Reunionnaise du Radiotelephone', '647', 'RE'),
('64801', 'Net One Zimbabwe', '648', 'ZW'),
('64803', 'Telecel Zimbabwe', '648', 'ZW'),
('64804', 'Econet Zimbabwe', '648', 'ZW'),
('64901', 'Mobile Telecommunications Ltd. Namibia', '649', 'NA'),
('64903', 'Powercom Pty Ltd Namibia', '649', 'NA'),
('65001', 'Telekom Network Ltd. Malawi', '650', 'MW'),
('65010', 'Celtel ltd. Malawi', '650', 'MW'),
('65101', 'Vodacom Lesotho (pty) Ltd.', '651', 'LS'),
('65102', 'Econet Ezin-cel Lesotho', '651', 'LS'),
('65201', 'Mascom Wireless (Pty) Ltd. Botswana', '652', 'BW'),
('65202', 'Orange Botswana (Pty) Ltd.', '652', 'BW'),
('65310', 'Swazi MTN', '653', 'SZ'),
('65401', 'HURI - SNPT Comoros', '654', 'KM'),
('65501', 'Vodacom (Pty) Ltd. South Africa', '655', 'ZA'),
('65506', 'Sentech (Pty) Ltd. South Africa', '655', 'ZA'),
('65507', 'Cell C (Pty) Ltd. South Africa', '655', 'ZA'),
('65510', 'Mobile Telephone Networks South Africa', '655', 'ZA'),
('65511', 'SAPS Gauteng South Africa', '655', 'ZA'),
('65521', 'Cape Town Metropolitan Council South Africa', '655', 'ZA'),
('65530', 'Bokamoso Consortium South Africa', '655', 'ZA'),
('65531', 'Karabo Telecoms (Pty) Ltd. South Africa', '655', 'ZA'),
('65532', 'Ilizwi Telecommunications South Africa', '655', 'ZA'),
('65533', 'Thinta Thinta Telecommunications South Africa', '655', 'ZA'),
('65534', 'Bokone Telecoms South Africa', '655', 'ZA'),
('65535', 'Kingdom Communications South Africa', '655', 'ZA'),
('65536', 'Amatole Telecommunication Services South Africa', '655', 'ZA'),
('70267', 'Belize Telecommunications Ltd., GSM 1900', '702', 'BZ'),
('70268', 'International Telecommunications Ltd. (INTELCO) Belize', '702', 'BZ'),
('70401', 'Servicios de Comunicaciones Personales Inalambricas, S.A. Guatemala', '704', 'GT'),
('70402', 'Comunicaciones Celulares S.A. Guatemala', '704', 'GT'),
('70403', 'Telefonica Centroamerica Guatemala S.A.', '704', 'GT'),
('70601', 'CTE Telecom Personal, S.A. de C.V. El Salvador', '706', 'SV'),
('70602', 'Digicel, S.A. de C.V. El Salvador', '706', 'SV'),
('70603', 'Telemovil El Salvador, S.A.', '706', 'SV'),
('708001', 'Megatel Honduras', '708', 'HN'),
('708002', 'Celtel Honduras', '708', 'HN'),
('708040', 'Digicel Honduras', '708', 'HN'),
('71021', 'Empresa Nicaraguense de Telecomunicaciones, S.A. (ENITEL)', '710', 'NI'),
('71073', 'Servicios de Comunicaciones, S.A. (SERCOM) Nicaragua', '710', 'NI'),
('71201', 'Instituto Costarricense de Electricidad - ICE', '712', 'CR'),
('71401', 'Cable & Wireless Panama S.A.', '714', 'PA'),
('71402', 'BSC de Panama S.A.', '714', 'PA'),
('71610', 'TIM Peru', '716', 'PE'),
('722010', 'Compaia de Radiocomunicaciones Moviles S.A. Argentina', '722', 'AR'),
('722020', 'Nextel Argentina srl', '722', 'AR'),
('722070', 'Telefonica Comunicaciones Personales S.A. Argentina', '722', 'AR'),
('722310', 'CTI PCS S.A. Argentina', '722', 'AR'),
('722320', 'Compaia de Telefonos del Interior Norte S.A. Argentina', '722', 'AR'),
('722330', 'Compaia de Telefonos del Interior S.A. Argentina', '722', 'AR'),
('722341', 'Telecom Personal S.A. Argentina', '722', 'AR'),
('72400', 'Telet Brazil', '724', 'BR'),
('72401', 'CRT Cellular Brazil', '724', 'BR'),
('72402', 'Global Telecom Brazil', '724', 'BR'),
('72403', 'CTMR Cel Brazil', '724', 'BR'),
('72404', 'BCP Brazil', '724', 'BR'),
('72405', 'Telesc Cel Brazil', '724', 'BR'),
('72406', 'Tess Brazil', '724', 'BR'),
('72407', 'Sercontel Cel Brazil', '724', 'BR'),
('72408', 'Maxitel MG Brazil', '724', 'BR'),
('72409', 'Telepar Cel Brazil', '724', 'BR'),
('72410', 'ATL Algar Brazil', '724', 'BR'),
('72411', 'Telems Cel Brazil', '724', 'BR'),
('72412', 'Americel Brazil', '724', 'BR'),
('72413', 'Telesp Cel Brazil', '724', 'BR'),
('72414', 'Maxitel BA Brazil', '724', 'BR'),
('72415', 'CTBC Cel Brazil', '724', 'BR'),
('72416', 'BSE Brazil', '724', 'BR'),
('72417', 'Ceterp Cel Brazil', '724', 'BR');

INSERT INTO `umsinstall_db_mccmnc` (`value`, `name`, `countrycode`, `countryname`) VALUES
('72418', 'Norte Brasil Tel', '724', 'BR'),
('72419', 'Telemig Cel Brazil', '724', 'BR'),
('72421', 'Telerj Cel Brazil', '724', 'BR'),
('72423', 'Telest Cel Brazil', '724', 'BR'),
('72425', 'Telebrasilia Cel', '724', 'BR'),
('72427', 'Telegoias Cel Brazil', '724', 'BR'),
('72429', 'Telemat Cel Brazil', '724', 'BR'),
('72431', 'Teleacre Cel Brazil', '724', 'BR'),
('72433', 'Teleron Cel Brazil', '724', 'BR'),
('72435', 'Telebahia Cel Brazil', '724', 'BR'),
('72437', 'Telergipe Cel Brazil', '724', 'BR'),
('72439', 'Telasa Cel Brazil', '724', 'BR'),
('72441', 'Telpe Cel Brazil', '724', 'BR'),
('72443', 'Telepisa Cel Brazil', '724', 'BR'),
('72445', 'Telpa Cel Brazil', '724', 'BR'),
('72447', 'Telern Cel Brazil', '724', 'BR'),
('72448', 'Teleceara Cel Brazil', '724', 'BR'),
('72451', 'Telma Cel Brazil', '724', 'BR'),
('72453', 'Telepara Cel Brazil', '724', 'BR'),
('72455', 'Teleamazon Cel Brazil', '724', 'BR'),
('72457', 'Teleamapa Cel Brazil', '724', 'BR'),
('72459', 'Telaima Cel Brazil', '724', 'BR'),
('73001', 'Entel Telefonica Movil Chile', '730', 'CL'),
('73002', 'Telefonica Movil Chile', '730', 'CL'),
('73003', 'Smartcom Chile', '730', 'CL'),
('73004', 'Centennial Cayman Corp. Chile S.A.', '730', 'CL'),
('73005', 'Multikom S.A. Chile', '730', 'CL'),
('73010', 'Entel Chile', '730', 'CL'),
('732001', 'Colombia Telecomunicaciones S.A. - Telecom', '732', 'CO'),
('732002', 'Edatel S.A. Colombia', '732', 'CO'),
('732101', 'Comcel S.A. Occel S.A./Celcaribe Colombia', '732', 'CO'),
('732102', 'Bellsouth Colombia S.A.', '732', 'CO'),
('732103', 'Colombia Movil S.A.', '732', 'CO'),
('732111', 'Colombia Movil S.A.', '732', 'CO'),
('732123', 'Telfonica Moviles Colombia S.A.', '732', 'CO'),
('73401', 'Infonet Venezuela', '734', 'VE'),
('73402', 'Corporacion Digitel Venezuela', '734', 'VE'),
('73403', 'Digicel Venezuela', '734', 'VE'),
('73404', 'Telcel, C.A. Venezuela', '734', 'VE'),
('73601', 'Nuevatel S.A. Bolivia', '736', 'BO'),
('73602', 'ENTEL S.A. Bolivia', '736', 'BO'),
('73603', 'Telecel S.A. Bolivia', '736', 'BO'),
('73801', 'Cel*Star (Guyana) Inc.', '738', 'GY'),
('74000', 'Otecel S.A. - Bellsouth Ecuador', '740', 'EC'),
('74001', 'Porta GSM Ecuador', '740', 'EC'),
('74002', 'Telecsa S.A. Ecuador', '740', 'EC'),
('74401', 'Hola Paraguay S.A.', '744', 'PY'),
('74402', 'Hutchison Telecom S.A. Paraguay', '744', 'PY'),
('74403', 'Compania Privada de Comunicaciones S.A. Paraguay', '744', 'PY'),
('74602', 'Telesur Suriname', '746', 'SR'),
('74800', 'Ancel TDMA Uruguay', '748', 'UY'),
('74801', 'Ancel GSM Uruguay', '748', 'UY'),
('74803', 'Ancel Uruguay', '748', 'UY'),
('74807', 'Movistar Uruguay', '748', 'UY'),
('74810', 'CTI Movil Uruguay', '748', 'UY'),
('90101', 'ICO Global Communications', '901', 'International Mobile, shared code'),
('90102', 'Sense Communications International AS', '901', 'International Mobile, shared code'),
('90103', 'Iridium Satellite, LLC (GMSS)', '901', 'International Mobile, shared code'),
('90104', 'Globalstar International Mobile', '901', 'International Mobile, shared code'),
('90105', 'Thuraya RMSS Network', '901', 'International Mobile, shared code'),
('90106', 'Thuraya Satellite Telecommunications Company', '901', 'International Mobile, shared code'),
('90107', 'Ellipso International Mobile', '901', 'International Mobile, shared code'),
('90108', 'GSM International Mobile', '901', 'International Mobile, shared code'),
('90109', 'Tele1 Europe', '901', 'International Mobile, shared code'),
('90110', 'Asia Cellular Satellite (AceS)', '901', 'International Mobile, shared code'),
('90111', 'Inmarsat Ltd.', '901', 'International Mobile, shared code'),
('90112', 'Maritime Communications Partner AS (MCP network)', '901', 'International Mobile, shared code'),
('90113', 'Global Networks, Inc.', '901', 'International Mobile, shared code'),
('90114', 'Telenor GSM - services in aircraft', '901', 'International Mobile, shared code'),
('90115', 'SITA GSM services in aircraft', '901', 'International Mobile, shared code'),
('90116', 'Jasper Systems, Inc.', '901', 'International Mobile, shared code'),
('90117', 'Jersey Telecom', '901', 'International Mobile, shared code'),
('90118', 'Cingular Wireless', '901', 'International Mobile, shared code'),
('90119', 'Vodaphone Malta', '901', 'International Mobile, shared code');
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
(1, "报刊杂志", 1, 0),
(2, "社交", 1, 0),
(3, "商业", 1, 0),
(4, "财务", 1, 0),
(5, "参考", 1, 0),
(6, "导航", 1, 0),
(7, "工具", 1, 0),
(8, "健康健美", 1, 0),
(9, "教育", 1, 0),
(10, "旅行", 1, 0),
(11, "摄影与录像", 1, 0),
(12, "生活", 1, 0),
(13, "体育", 1, 0),
(14, "天气", 1, 0),
(15, "图书", 1, 0),
(16, "效率", 1, 0),
(17, "新闻", 1, 0),
(18, "音乐", 1, 0),
(19, "医疗", 1, 0),
(32, "娱乐", 1, 0),
(33, "游戏", 1, 0);

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
(2, "User", "用户管理", NULL),
(3, "Product", "我的应用", NULL),
(4, "errorlogondevice", "错误设备统计", NULL),
(5, "productbasic", "基本统计", NULL),
(6, "Auth", "用户", NULL),
(7, "Autoupdate", "自动更新", NULL),
(8, "Channel", "渠道", NULL),
(9, "Device", "设备", NULL),
(10, "Event", "事件管理", NULL),
(11, "Onlineconfig", "发送策略", NULL),
(12, "Operator", "运营商", NULL),
(13, "Os", "操作系统统计", NULL),
(14, "Profile", "个人资料", NULL),
(15, "Resolution", "分辨率统计", NULL),
(16, "Usefrequency", "使用频率统计", NULL),
(17, "Usetime", "使用时长统计", NULL),
(18, "errorlog", "错误日志", NULL),
(19, "Eventlist", "事件", NULL),
(20, "market", "渠道STATISTICS", NULL),
(21, "region", "地域统计", NULL),
(22, "errorlogonos", "错误操作系统统计", NULL),
(23, "version", "版本统计", NULL),
(24, "console", "应用", NULL),
(25, "Userremain", "用户留存", NULL),
(26, "Pagevisit", "页面访问统计", NULL),
(27, "Network", "联网方式统计", NULL),
(28, "funnels", "漏斗模型", NULL);
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


-- 
-- Default value for table `gcmappkeys`
-- 

CREATE TABLE IF NOT EXISTS `umsinstall_gcmappkeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `appkey` varchar(128) NOT NULL,
  `status` smallint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `umsinstall_device_tag` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `deviceid` varchar(256) NOT NULL,
    `tags` varchar(1024) default NULL,
    `productkey` varchar(64) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

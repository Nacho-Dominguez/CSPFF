CREATE TABLE `independent_donation` (
  `id` char(22) NOT NULL,
  `amount` decimal(6,2) NOT NULL,
  `pay_type_id` tinyint(1) NOT NULL,
  `benefactor` varchar(70) default NULL,
  `defendant` varchar(70) default NULL,
  `court_id` smallint(4) unsigned default NULL,
  `cc_trans_id` varchar(255) default NULL,
  `reason` tinyint(1) NOT NULL default '1',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
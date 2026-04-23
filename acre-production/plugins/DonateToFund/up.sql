CREATE TABLE `fund_donation` (
  `id` char(22) NOT NULL,
  `amount` decimal(6,2) NOT NULL,
  `pay_type_id` tinyint(1) NOT NULL,
  `benefactor` varchar(70) default NULL,
  `cc_trans_id` varchar(255) default NULL,
  `fund_id` int(11) NOT NULL default '1',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `fund` (
  `fund_id` smallint unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `is_active` boolean default 0,
  PRIMARY KEY (`fund_id`));

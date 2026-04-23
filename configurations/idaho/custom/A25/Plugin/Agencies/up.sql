CREATE TABLE `agency` (
  `agency_id` smallint NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY (`agency_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `jos_users` ADD COLUMN `agency_id` smallint DEFAULT NULL;
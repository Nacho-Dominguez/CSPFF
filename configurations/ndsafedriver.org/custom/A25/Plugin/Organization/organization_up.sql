CREATE TABLE `jos_organization` (
    `organization_id` int(11) NOT NULL auto_increment,
    `name` varchar(60) NOT NULL default '',
    `password` varchar(100) default '',
    PRIMARY KEY  (`organization_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `jos_course` ADD COLUMN `organization_id` int(11) default NULL;
ALTER TABLE `jos_location` ADD COLUMN `organization_id` int(11) default NULL;

CREATE TABLE `checkbox` (
`id` int(11) unsigned NOT NULL auto_increment,
`student_id` int(11) unsigned NOT NULL,
`text` varchar(988) NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `checkbox_key` (`student_id`, `text`));
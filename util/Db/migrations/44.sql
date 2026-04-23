CREATE TABLE `jos_course_comments` (
  `course_id` int(11) unsigned NOT NULL,
  `comments` varchar(8191) default NULL,
  PRIMARY KEY `course_id` (`course_id`));
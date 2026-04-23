ALTER TABLE `jos_location` MODIFY COLUMN `number_of_seats` int unsigned default NULL;
ALTER TABLE `jos_course` MODIFY COLUMN `course_capacity` int unsigned default '0';
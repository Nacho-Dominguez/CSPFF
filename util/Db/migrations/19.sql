ALTER TABLE `jos_course` ADD `duration` TIME AFTER `course_end_date`;

UPDATE `jos_course` SET `duration`=TIMEDIFF(`course_end_date`, `course_start_date`);

ALTER TABLE `jos_course` DROP `course_end_date`;

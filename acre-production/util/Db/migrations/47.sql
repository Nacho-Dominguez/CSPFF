ALTER TABLE `jos_student` ADD COLUMN `password` char(31) default NULL,
    ADD COLUMN `salt_prefix` char(9) default NULL;
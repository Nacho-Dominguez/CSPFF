ALTER TABLE `jos_student` MODIFY COLUMN `password` char(31) NOT NULL,
    MODIFY COLUMN `salt_prefix` char(9) NOT NULL;
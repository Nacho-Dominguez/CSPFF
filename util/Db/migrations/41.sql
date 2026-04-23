ALTER TABLE `jos_users` MODIFY COLUMN `nsc` int;
ALTER TABLE `jos_users` MODIFY COLUMN `control` int;
UPDATE `jos_users` SET `nsc` = NULL where `nsc` = 0;
UPDATE `jos_users` SET `control` = NULL where `control` = 0;
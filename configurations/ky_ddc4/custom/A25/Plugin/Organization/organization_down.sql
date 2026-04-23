DROP TABLE IF EXISTS `jos_organization`;

ALTER TABLE `jos_course` DROP COLUMN `organization_id`;
ALTER TABLE `jos_location` DROP COLUMN `organization_id`;

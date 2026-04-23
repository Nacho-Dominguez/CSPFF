ALTER TABLE `jos_student` MODIFY `userid` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_bin;
ALTER TABLE `jos_student` ADD UNIQUE(`userid`);
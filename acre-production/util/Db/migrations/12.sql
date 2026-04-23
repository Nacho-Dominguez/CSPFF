ALTER IGNORE TABLE `jos_location_user_xref` ADD UNIQUE KEY `unique_user_location_gid` (`user_id`,`location_id`,`gid`);

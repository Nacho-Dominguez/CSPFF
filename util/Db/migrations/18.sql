DROP TABLE IF EXISTS `hide_broadcast`;
CREATE TABLE `hide_broadcast` (
  `broadcast_id` tinyint(3) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  CONSTRAINT pk_broadcast_user PRIMARY KEY (broadcast_id,user_id)
);

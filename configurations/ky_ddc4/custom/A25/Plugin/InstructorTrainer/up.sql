CREATE TABLE instructor_trainer (
	`trainee_user_id` int(11) NOT NULL,
    `trainer_user_id` int(11) NOT NULL,
	PRIMARY KEY (`trainee_user_id`,`trainer_user_id`)
);

ALTER TABLE `jos_users` ADD COLUMN is_a_trainer BOOL NOT NULL DEFAULT 0;
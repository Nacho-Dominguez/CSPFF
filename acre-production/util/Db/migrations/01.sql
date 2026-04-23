ALTER TABLE jos_order_item ADD date_paid DATE;

ALTER TABLE jos_location MODIFY `fee` decimal(10,2) unsigned default NULL;
ALTER TABLE jos_location MODIFY `late_fee` decimal(10,2) unsigned default NULL;
ALTER TABLE jos_location MODIFY `late_fee_deadline` int(11) default NULL;
ALTER TABLE jos_location MODIFY `enrollment_deadline` int(11) default NULL;
ALTER TABLE jos_location MODIFY `cancellation_deadline` int(11) unsigned default NULL;
ALTER TABLE jos_location MODIFY `enrollment_email_subject` varchar(200) default NULL;
ALTER TABLE jos_location MODIFY `enrollment_email_body` text default NULL;
ALTER TABLE jos_location MODIFY `course_completed_email_subject` varchar(200) default NULL;
ALTER TABLE jos_location MODIFY `course_completed_email_body` text default NULL;
ALTER TABLE jos_location MODIFY `payment_reminder_email_subject` varchar(200) default NULL;
ALTER TABLE jos_location MODIFY `payment_reminder_email_body` text default NULL;

ALTER TABLE jos_student MODIFY `special_needs` varchar(255) default NULL;

ALTER TABLE jos_users MODIFY `activation` varchar(100) default NULL;
ALTER TABLE jos_users MODIFY `params` text default NULL;

ALTER TABLE jos_course_message MODIFY `message_title` varchar(230) default NULL;
ALTER TABLE jos_course_message MODIFY `message_body` text default NULL;

ALTER TABLE jos_student_messages MODIFY `subject` varchar(230) default NULL;
ALTER TABLE jos_student_messages MODIFY `message` text default NULL;

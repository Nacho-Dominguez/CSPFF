ALTER TABLE jos_course
    ADD COLUMN fee decimal(10,2) unsigned default NULL,
    ADD COLUMN late_fee decimal(10,2) unsigned default NULL,
    ADD COLUMN late_fee_deadline int(11) default NULL,
    ADD COLUMN enrollment_deadline int(11) default NULL,
    ADD COLUMN cancellation_deadline int(11) default NULL,
    ADD COLUMN payment_deadline int(11) default NULL,
    ADD COLUMN register_cc_days int(11) default NULL,
    ADD COLUMN enrollment_email_body text default NULL,
    ADD COLUMN course_completed_email_body text default NULL,
    ADD COLUMN payment_reminder_email_body text default NULL

ALTER TABLE jos_course ADD COLUMN `classroom_set` boolean NOT NULL default 0,
ADD COLUMN `instructor_confirmed` boolean NOT NULL default 0,
ADD COLUMN `materials_sent` boolean NOT NULL default 0,
ADD COLUMN `materials_received` boolean NOT NULL default 0,
ADD COLUMN `instructor_paid` boolean NOT NULL default 0,
ADD COLUMN `certificates_sent` boolean NOT NULL default 0
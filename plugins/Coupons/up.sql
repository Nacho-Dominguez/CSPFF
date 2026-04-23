ALTER TABLE `jos_credit_type` ADD COLUMN `coupon_code` varchar(30) default NULL UNIQUE,
    ADD COLUMN `discount` decimal(6,2) default '0.00';
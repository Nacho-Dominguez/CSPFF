DROP TABLE IF EXISTS `instructor_trainer`;

drop procedure if exists schema_change;

delimiter ';;'
create procedure schema_change() begin
	if exists (select * from information_schema.columns where table_name='jos_users' and column_name='is_a_trainer') then
		ALTER TABLE `jos_users` DROP COLUMN `is_a_trainer`;
	end if;
end;;

delimiter ';'
call schema_change();

drop procedure if exists schema_change;
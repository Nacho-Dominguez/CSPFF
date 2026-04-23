alter table jos_court add collect_docket_number bool not null default false;
alter table jos_student_course_xref add court_docket_number varchar(40);

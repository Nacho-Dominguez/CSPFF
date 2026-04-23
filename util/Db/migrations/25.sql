update jos_order_item_type set type_name='Tuition' where type_id=1;
update jos_order_item_type set type_name='Nonrefundable Tuition (no-show)' where type_id=5;
insert into jos_order_item_type (type_id,type_name) values(8,'Money Order Discount');

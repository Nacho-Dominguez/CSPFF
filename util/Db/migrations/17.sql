DROP PROCEDURE IF EXISTS fillInNulls;

DELIMITER //

CREATE PROCEDURE fillInNulls()
BEGIN
	DECLARE c1 INT;
	SELECT count(*) from jos_order_item where created is null INTO c1;
	WHILE c1 > 0 DO
		update jos_order_item as p inner join jos_order_item as p1 on p1.item_id=(p.item_id+1)
			set p.created=p1.created
			where p.created is null;
		update jos_order_item as p inner join jos_order_item as p1 on p1.item_id=(p.item_id-1)
			set p.created=p1.created
			where p.created is null;
		SELECT count(*) from jos_order_item where created is null INTO c1;
	END WHILE;
END//

DELIMITER ;

CALL fillInNulls();

DROP PROCEDURE fillInNulls;

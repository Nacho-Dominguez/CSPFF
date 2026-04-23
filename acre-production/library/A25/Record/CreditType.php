<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CreditTypeRecord
 *
 * @author remote
 */
class A25_Record_CreditType extends JosCreditType
{

	/**
	 * @param integer $id
	 * @return A25_Record_CreditType
	 *
	 * As part of goal 'php5_3', all declarations of this will be replaced with
	 * a single definition in A25_Record.
	 */
	public static function retrieve($id)
	{
		return Doctrine::getTable('A25_Record_CreditType')->find($id);
	}

	/**
	 * Get list of credit types that are available for use (they are active
	 * and not full) in an objectlist using the ACL for locations.
	 * @author Garey Hoffman
	 * @version December 10, 2006
	 *
	 * @return boolean
	 */
	function getCreditTypeListACL() {
    	$where = array();
        /* Only show location parents */
    	//$where[] = "l.`is_location`=0";
    	$where[] = "ct.`is_active` = 1";

		$sql = "SELECT ct.*, SUM( c.`credit_value` ) AS `sum_credit_value`, CONCAT(ct.`credit_type_name`) AS `credit_full_name`"
		. "\n FROM #__credit_type ct"
		. "\n LEFT JOIN #__credits c ON  (c.`credit_type_id` = ct.`credit_type_id`)"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n GROUP BY ct.`credit_type_id`"
		. "\n HAVING (SUM( c.`credit_value` ) < ct.`total_value`) "
		  . "\n OR ((SUM( c.`credit_value` ) IS NULL) AND (ct.`total_value` > 0))"
		;


		A25_DI::DB()->setQuery($sql);
    	//echo str_replace('#_','jos',$sql);
		//var_dump(A25_DI::DB()->loadObjectList()); exit;

		return A25_DI::DB()->loadObjectList();
	}

	public function getBalance()
	{
		$sql = "SELECT sum(credit_value) FROM jos_credits WHERE credit_type_id =".$this->credit_type_id;
		A25_DI::DB()->setQuery($sql);
		return $this->total_value - A25_DI::DB()->loadResult();
	}
}
?>

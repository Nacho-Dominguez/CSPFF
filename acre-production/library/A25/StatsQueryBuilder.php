<?php

/**
 * This class provides static functions that build common queries for statistics
 * generation.  To get a good idea about what each function does, look at the
 * unit tests.
 */
class A25_StatsQueryBuilder {
	/**
     * @param array $where
     * @param bool $includeRevenueInfo (optional)
     * @return string (of SQL)
     */
	public function reasonEnrolled($where, $includeRevenueInfo = false)
	{
		$fields = array('e.`reason_id`','r.`reason_name`');

		if ($includeRevenueInfo)
			$fields[] = '(SUM(p.`amount`)) AS `gross_revenue`';

		$join = "";
		if ($includeRevenueInfo)
			$join .= "\n LEFT JOIN #__pay p ON (e.`xref_id` = p.`xref_id`)";

		$join .= "\n LEFT JOIN #__reason_type r ON (e.`reason_id` = r.`reason_id`)";
		
		$sql = self::groupByStatement('e.`reason_id`', $fields, $join, $where);

		return $sql;
	}
	public function gender($where)
	{
		$sql = self::groupByStatement('s.`gender`',array('s.`gender`'),
				self::joinWithStudent(), $where);
		return $sql;
    }
	public function age($where)
	{
		$age = "("
			. "DATE_FORMAT(c.`course_start_date`,'%Y') - "
			. "DATE_FORMAT(s.`date_of_birth`,'%Y') - "
			. "("
				. "DATE_FORMAT(c.`course_start_date`,'00-%m-%d') < "
				. "DATE_FORMAT(s.`date_of_birth`,'00-%m-%d')"
			. ")"
		. ") AS age";

		$sql = self::groupByStatement('age', array($age),
				self::joinWithStudent(), $where);
		return $sql;
    }
	private static function groupByStatement($fieldName,$fieldDefs,$join,$where)
	{
		$sql = self::selectStatement($fieldDefs)
		. $join
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n GROUP BY $fieldName"
		. "\n ORDER BY $fieldName";
		return $sql;
    }
	private static function joinWithStudent()
	{
		return "\n LEFT JOIN #__student s USING (`student_id`)";
    }
	private function selectStatement($fields)
	{
		$sql = "SELECT ";
		foreach ($fields as $field) {
			$sql .= $field . ', ';
        }
		// Remove last comma:
		$sql = trim($sql,',');
		$sql .= "COUNT(*) AS `count`"
		. "\n FROM #__course c"
		. "\n LEFT JOIN #__student_course_xref e USING (`course_id`)";
    $sql = self::fireJoinTable($sql);
		return $sql;
    }
  public static function addWhereEnrollmentIsActive($where)
  {
    $inactive = implode(',', A25_Record_Enroll::inactiveStatusList());
    $where[] = 'e.`status_id` NOT IN (' . $inactive . ')';
    return $where;
  }
  
  private static function fireJoinTable($query)
  {
    foreach (A25_ListenerManager::all() as $listener) {
      if ($listener instanceof A25_ListenerI_LocationStats) {
        $query = $listener->joinTable($query);
      }
    }
    return $query;
  }
}

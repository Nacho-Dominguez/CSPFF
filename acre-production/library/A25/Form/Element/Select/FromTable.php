<?php

class A25_Form_Element_Select_FromTable extends A25_Form_Element_Select
{
	public function __construct($name, $table, $valueColumn, $textColumn, $where = null)
	{
		parent::__construct($name);
		$this->addMultiOptions($this->getSelections($table, $valueColumn, $textColumn, $where));
	}
	private function getSelections($table, $valueColumn, $textColumn, $where)
	{
		$sql = "SELECT `$valueColumn` AS value, `$textColumn` AS text FROM $table ";
    if ($where) {
      $sql .= "WHERE $where ";
    }
    $sql .= "ORDER BY `$textColumn`;";
		$db = A25_DI::DB();
		$db->setQuery($sql);
		$selects = $db->loadObjectList();

		$selections = array();
		$selections[null] = '--none--';
		foreach ($selects as $select) {
			$selections[$select->value] = $select->text;
		}
		return $selections;
	}
}
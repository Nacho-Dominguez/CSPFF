<?php

class A25_FormLoader {
	public static function run($class, $returnUrl,
			$isReadOnly = false)
	{
		$recordClass = self::parseRecordClassName($class);
		if ($_GET['id'] > 0) {
			$record = new $recordClass();
			$record->load($_GET['id']);
		} else {
			$record = new $recordClass();
		}
		$formClassName = 'A25_Form_Record_' . $class;
		$form = new $formClassName($record, $returnUrl, $isReadOnly);
		$form->run($_POST);
	}
	public static function parseRecordClassName($class)
	{
		preg_match('/^([^_]+)_?/',$class, $matches);
		$recordClass = 'A25_Record_' . $matches[1];
		return $recordClass;
	}
}
?>

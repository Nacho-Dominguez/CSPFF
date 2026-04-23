<?php
// This is where we should put functions that make new tools compatible with old
//ones. For example, making Doctrine compatible with old forms.
class A25_Compatibility
{
  public static function appendDoctrineRecordsToSelectionList($table, $list)
  {
    $records = Doctrine_Query::create()->select()->from($table)->execute();
    $doctrineList = A25_Form_Record::createSelectionList($records);
    foreach($doctrineList as $id => $name)
    {
      $item = new stdClass();
      $item->value = $id;
      $item->text = $name;
      $list[] = $item;
    }
    return $list;
  }
}

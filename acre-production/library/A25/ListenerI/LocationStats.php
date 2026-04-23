<?php
interface A25_ListenerI_LocationStats
{
  public function addLocationFilter($filter, &$lists);
  public function setLocationFilter($filter, $mainframe, $option);
  public function joinTable($query);
  public function addWhereClause($filter, $where);
}

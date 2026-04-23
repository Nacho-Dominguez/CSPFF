<?php
interface A25_ListenerI_ShowUsers
{
  public function addFilter($mainframe, &$where, &$lists);
  public function addColumn($record);
  public function addColumnHeader();
}
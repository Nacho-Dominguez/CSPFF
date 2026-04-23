<?php

class A25_Plugin_Agencies implements A25_ListenerI_Doctrine,
    A25_ListenerI_AddUserFields, A25_ListenerI_AddIcons,
    A25_ListenerI_ShowUsers, A25_ListenerI_LocationStats,
    A25_ListenerI_HomepageBroadcasts
{
  public function homepageBroadcasts()
  {
    if (A25_DI::User()->isAdminOrHigher()) {
      $broadcast = new A25_Broadcast(9, 'Agencies',
          'Instructors can now be associated with an Agency for reporting purposes',
           A25_Link::to('administrator/documentation/agency'));
      echo $broadcast->render();
    }
  }
  public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
  {
    if ($doctrineRecord instanceof A25_Record_User) {
      $doctrineRecord->hasColumn('agency_id', 'integer', 2, array(
             'type' => 'integer',
             'length' => 2,
             'fixed' => false,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
    }
  }
  public function afterGroup($row)
  { 
    $agency_id = new A25_Form_Element_Select_FromTable('agency_id', 'agency',
        'agency_id', 'name');
    $agency_id->setValue($row->agency_id);
    $agency_id->removeDecorator('label');
    $agency_id->removeDecorator('HtmlTag');
    
    ?>
    <tr>
      <td>
      Agency:
      </td>
      <td>
      <?php
      echo $agency_id->render(new Zend_View());
      echo mosToolTip(
          'New agencies can be added by clicking on the Agencies button on the homepage.<br /><br />'
          . 'Changing an instructor from one agency to another may affect Agency Reports.  If an instructor switches agencies, we recommend creating a new User Account for the instructor to use under their new agency, so that data from their previous courses remains under the old agency.'
          ,'Agencies',null,'tooltip.png');
      ?>
      </td>
    </tr>
    <?php
  }
  public function saveUser($row)
  {
  }
  
  public function afterAdminButtons()
  {
    if (!A25_DI::User()->isAdminOrHigher())
      return;
    quickiconButton('ViewAgencies', 'frontpage.png', 'Agencies');
  }
  
  public function addFilter($mainframe, &$where, &$lists)
  {
    $filter_agency	= intval( $mainframe->getUserStateFromRequest( "filter_agency{$option}", 'filter_agency', 0 ) );
    if ($filter_agency) {
      $where[] = "a.agency_id = $filter_agency";
    }
    // get list of Agencies for dropdown filter
    $agencies[] = mosHTML::makeOption( '0', '- All Agencies -' );
    $agencies = A25_Compatibility::appendDoctrineRecordsToSelectionList(
        'A25_Record_Agency', $agencies);
    $lists['agency'] = mosHTML::selectList($agencies, 'filter_agency', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', "$filter_agency");
  }
  
  public function addColumn($row)
  {
    $agency = A25_Record_Agency::retrieve($row->agency_id);
    echo '<td>' . $agency->name . '</td>';
  }
  
  public function addColumnHeader()
  {
    echo '<th width="15%" class="title">Agency</th>';
  }
  
  public function addLocationFilter($filter, &$lists)
  {
    // get list of Agencies for dropdown filter
    $agencies[] = mosHTML::makeOption( '0', '- All Agencies -' );
    $agencies = A25_Compatibility::appendDoctrineRecordsToSelectionList(
        'A25_Record_Agency', $agencies);
    $lists['agency'] = mosHTML::selectList($agencies, 'filter_agency', 'class="inputbox" size="1"', 'value', 'text', "$filter");
  }
  
  public function setLocationFilter($filter, $mainframe, $option)
  {
    $filter->agencyId = intval( $mainframe->getUserStateFromRequest( "filter_agency{$option}", 'filter_agency', 0 ) );
    return $filter;
  }
  
  public function joinTable($query)
  {
    $query .= "\n LEFT JOIN #__users i1 ON (c.instructor_id=i1.id)"
    . "\n LEFT JOIN #__users i2 ON (c.instructor_2_id=i2.id)";
    return $query;
  }
  
  public function addWhereClause($filter, $where)
  {
    if ($filter->agencyId) {
      $where[] = '(i1.`agency_id`=' . $filter->agencyId . ' OR i2.`agency_id`=' . $filter->agencyId . ')';
    }
    return $where;
  }
}

set_include_path(
	ServerConfig::webRoot . '/plugins/Agencies' . PATH_SEPARATOR
	. get_include_path()
);
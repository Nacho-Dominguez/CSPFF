<?php

class Controller_Administrator_ViewAgencies extends Controller
{ 
  public function executeTask()
  {
		$user_id = A25_DI::UserId();
		if ($user_id < 1)
			exit("You need to be logged in to view this.");
    if (!A25_DI::User()->isAdminOrHigher())
      exit('Sorry, your account is not allowed to access this page.');
    
		$this->displayAgencies();
  }

	private function displayAgencies()
	{
    $head = A25_DI::HtmlHead();
    $head->append('
      <style type="text/css" media="all">
        .main {
          max-width: 500px;
        }
      </style>');
    $q = Doctrine_Query::create()->from('A25_Record_Agency')->orderBy('name');
		$agencies = $q->execute();
		
    echo '<div style="float: left;"><h2>Agencies</h2></div>';
    echo '<div style="float: right;">';
    echo A25_Buttons::toolbarWithUnassumingUrl('New', 'EditAgency', 'new_f2.png');
    echo '</div><div style="clear:both; padding: 12px 0px; font-style: italic; color: #666">'
        . 'Users can be assigned to agencies via the <a href="'
        . A25_Link::withoutSef('/administrator/index2.php?option=com_users')
        . '">User Manager</a></div>';

    if ($agencies[0]->agency_id) {
      echo '<table class="adminform" style="max-width: 500px;">';
      foreach	($agencies as $agency) {
        $view = A25_Link::to('/administrator/index2.php?option=com_users&filter_agency=' . $agency->agency_id);
        $edit = A25_Link::to('/administrator/EditAgency?id=' . $agency->agency_id);
        echo '<tr><td>' . $agency->name . '</td><td><a href="' . $view
            .'">View Users</a> - <a href="' . $edit . '">Rename</a></td></tr>';
      }
      echo '</table>';
    }
    else {
      echo '<span style="font-size: larger;">No Agencies have been added yet.'
          . ' To add one, click "New" above.</span>';
    }
	}
}
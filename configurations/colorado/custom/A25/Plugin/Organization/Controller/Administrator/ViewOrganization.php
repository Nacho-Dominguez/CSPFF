<?php

class Controller_Administrator_ViewOrganization extends Controller
{
	public function executeTask()
	{
		$user_id = A25_DI::UserId();
		if ($user_id < 1)
			exit("You need to be logged in to view this.");
    
		$id = $this->request['id'];

		if ($id > 0) {
			$this->displayOrganization($id);
		} else {
			$this->displayOrganizations();
		}
	}

	private function displayOrganization($id)
	{
		$organization = Doctrine::getTable('JosOrganization')->find($id);

		echo '<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="menudottedline" width="40%">';
		echo '<h2 style="padding-left: 1em;">View Organization</h2>';
	echo '</td>
	<td class="menudottedline" align="right">';
		echo '<table cellpadding="0" cellspacing="4" border="0" id="toolbar">

		<tr valign="middle" align="center">
		<td>
				<a class="toolbar" href="' . A25_Link::to("/administrator/EditOrganization?id=$organization->organization_id") . '">
					<img src="' . A25_Link::to('/administrator/images/edit_f2.png') . '"  alt="Edit" name="list" title="Edit" align="middle" border="0" />					<br />Edit</a>
			</td>
					<td>
				<a class="toolbar" href="' . A25_Link::to("/administrator/ViewOrganization") . '">
					<img src="' . A25_Link::to('/administrator/images/restore_f2.png') . '"  alt="Return" name="list" title="Return to List" align="middle" border="0" />					<br />Return</a>
			</td>
					</tr>
		</table>';
		echo '</td>
</tr>

</table>';

		$form = new A25_Plugin_Organization_Form($organization, 'task=ViewOrganization&id=' . $id, true);
		$form->run($_POST);
	}

	private function displayOrganizations()
	{
		$organizations = Doctrine::getTable('JosOrganization')->findAll();
		
		echo '<div style="float: left;">';
		echo '<h2>Organizations</h2>';
		echo '</div><div style="float: right;">';
		echo A25_Buttons::toolbarWithUnassumingUrl('New', 'EditOrganization', 'new_f2.png');
		echo '</div><div style="clear:both; height: 12px;"></div>';
    
    echo '<div style="clear:both; padding: 12px 0px; font-style: italic; color: #666">'
        . 'Add an Organization & give it a Registration Code to restrict their '
        . 'classes from the general public.  See the '
        . '<a href="' . A25_Link::withoutSef(
          '/administrator/documentation/organization-registration-code')
        . '">Registration Code for Organizations</a> documentation for details.</div>';

		echo '<table class="adminform"><tr><th>Id</th><th>Name</th></tr>';
		foreach	($organizations as $organization) {
			$link = A25_Link::to('/administrator/ViewOrganization?id=' . $organization->organization_id);
			echo '<tr>';
			echo '<td>' . $organization->organization_id . '</td>';
			echo '<td><a href="' . $link .'">' . $organization->name .'</a></td>';
			echo '</tr>';
		}
		echo '</table>';
	}
}
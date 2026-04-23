<?php

// Append as a valid 'Overridable Location Setting':
A25_Record_Course::$overridableSettings[] = 'organization_id';

class A25_Plugin_Organization implements A25_ListenerI_AdminUi,
		A25_ListenerI_Doctrine, A25_ListenerI_BrowseCourses, A25_ListenerI_AddIcons
{
	public function afterAdminButtons()
	{
		if (!A25_DI::User()->isSuperAdmin())
			return;
		
		$link = 'ViewOrganization';
		quickiconButton( $link, 'frontpage.png', 'Organizations' );
	}

	public function afterLocationEditForm(A25_Record_LocationAbstract $location)
	{
		if (!A25_DI::User()->isAdminOrHigher())
			return false;

		$parentOrganizationName = '';
		$parentOrgantizationId = $location->Parent->getSetting('organization_id');
		$parentOrgantization = Doctrine::getTable('JosOrganization')->find($parentOrgantizationId);
		if ($parentOrgantization) {
			$parentOrganizationName = $parentOrgantization->name;
		}
		?>
		<td>
		Organization:
		</td>
		<td><?php echo $parentOrganizationName;?></td>
		<td>
			<?php echo self::generateOrganizationSelectList('organization_id', 'class="inputbox" size="1"', $location->organization_id); ?>
		</td>
		<?php
	}

	public function duringCourseEditFormAddOverridableSetting(A25_Form_Record_Course $courseForm,
			A25_Record_Course $course, $isReadOnly)
	{
		if (!A25_DI::User()->isAdminOrHigher())
			return false;
		
		$courseForm->overridableSettings[] = 'organization_id';

		$organization_id = new A25_Form_Element_Select('organization_id');
		$organization_id->setLabel('Organization');
		$organization_id->addMultiOption('', '(Inherit from Location)')
				->addMultiOptions($this->getOrganizationsNameArray());
		$courseForm->addElement($organization_id);

		$courseForm->formatInheritedSetting($organization_id, '');
		
		if (!$isReadOnly) {
			$location = $course->settingParent();
			if (!$location) {
				$location = new A25_Record_Location();
			}
			$organization = Doctrine::getTable('JosOrganization')->find(
					$location->getSetting('organization_id'));
			if ($organization)
				$value = $organization->name;
			else
				$value = 'none';
			
			$organization_id->setDescription('(Location\'s value: '
					. $value . ')');
		}
	}

	private function generateOrganizationSelectList($fieldName, $fieldAttributes, $selectedOrganization)
	{
		$organizationOptions = array();
		$organizationOptions[] = mosHTML::makeOption('','- Select One -');
		$organizations = Doctrine::getTable('JosOrganization')->findAll();
		foreach ($organizations as $organization) {
			$organizationOptions[] = mosHTML::makeOption($organization->organization_id, $organization->name);
		}
		return mosHTML::selectList( $organizationOptions, $fieldName, $fieldAttributes, 'value', 'text', $selectedOrganization);
	}

	public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
	{
		if ($doctrineRecord instanceof A25_Record_LocationAbstract
			|| $doctrineRecord instanceof A25_Record_Course)
		{
			$doctrineRecord->hasColumn('organization_id', 'integer', 4, array(
				 'type' => 'integer',
				 'length' => 4,
				 'unsigned' => 1,
				 'primary' => false,
				 'default' => null,
				 'notnull' => false,
				 'autoincrement' => false,
				 ));
		}
	}

	public function enrollLink(A25_Record_Course $course)
	{
		$org_id = $course->getSetting('organization_id');
		if ($org_id) {
			$org = Doctrine::getTable('JosOrganization')->find($org_id);
			if (!empty($org->password))
				return A25_Link::removeDoubleSlashes(ServerConfig::currentUrl()
						. '/private-course?id=' . $course->course_id);
		}
		return false;
	}

	/**
	 * Private methods
	 */

	private function getOrganizationsNameArray()
	{
		$organizationArray = array();
		$organizations = Doctrine::getTable('JosOrganization')->findAll();

		foreach ($organizations as $organization) {
			$organizationArray[$organization->organization_id] = $organization->name;
		}

		return $organizationArray;
	}
}

set_include_path(
	ServerConfig::webRoot . '/plugins/Organization' . PATH_SEPARATOR
	. get_include_path()
);

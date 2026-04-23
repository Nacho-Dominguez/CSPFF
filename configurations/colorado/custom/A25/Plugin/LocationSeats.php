<?php

class A25_Plugin_LocationSeats implements A25_ListenerI_AdminUi,
    A25_ListenerI_Doctrine
{
	public function afterLocationEditForm(A25_Record_LocationAbstract $location)
	{
		if (!A25_DI::User()->isAdminOrHigher())
			return false;

		?>
    <tr>
      <td colspan="3">
      Enforce <input type="text" name="alert_seats" size="3" maxlength="3" class="inputbox" value="<?php echo $location->alert_seats ?>" />
      seats within 
      <input type="text" name="alert_days" size="3" maxlength="3" class="inputbox" value="<?php echo $location->alert_days ?>" />
      days <?php echo mosToolTip('When there are fewer than this many total seats across courses within this many days an alert will be triggered.','What is this?'); ?>
      </td>
    </tr>
		<?php
	}

	public function duringCourseEditFormAddOverridableSetting(A25_Form_Record_Course $courseForm,
			A25_Record_Course $course, $isReadOnly)
	{
    return false;
	}
  
	public function afterDoctrineSetup(A25_DoctrineRecord $doctrineRecord)
	{
		if ($doctrineRecord instanceof A25_Record_LocationAbstract)
		{
			$doctrineRecord->hasColumn('alert_seats', 'integer', 3, array(
				 'type' => 'integer',
				 'length' => 3,
				 'unsigned' => 1,
				 'primary' => false,
				 'default' => null,
				 'notnull' => false,
				 'autoincrement' => false,
				 ));
			$doctrineRecord->hasColumn('alert_days', 'integer', 3, array(
				 'type' => 'integer',
				 'length' => 3,
				 'unsigned' => 1,
				 'primary' => false,
				 'default' => null,
				 'notnull' => false,
				 'autoincrement' => false,
				 ));
		}
	}
}
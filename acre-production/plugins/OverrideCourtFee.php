<?php

class A25_Plugin_OverrideCourtFee implements A25_ListenerI_AdminUi,
    A25_ListenerI_Doctrine, A25_ListenerI_CourtFee
{
	public function afterLocationEditForm(A25_Record_LocationAbstract $location)
	{
		if (!A25_DI::User()->isAdminOrHigher())
			return false;

		?>
<tr>
    <td>
    Override Tuition for Court-Ordered:
    </td>
    <td>$<?php echo PlatformConfig::defaultCourtFee ?></td>
    <td>
    $ <input type="text" name="court_fee" size="10" maxlength="10" class="inputbox" value="<?php echo $location->court_fee ?>" />  <?php echo mosToolTip('This is the tuition fee that all court-ordered students pay','What is this?'); ?>
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
			$doctrineRecord->hasColumn('court_fee', 'decimal', 10, array(
             'type' => 'decimal',
             'length' => 10,
             'fixed' => false,
             'unsigned' => true,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'scale' => '2',
             ));
		}
	}
    
    public function duringAddCourtTuition(A25_Record_Enroll $enroll) {
        $location = $enroll->Location;
        if ($location->court_fee) {
            return $location->court_fee;
        }
    }
}
<?php

abstract class A25_View_Student_ReasonForEnrollment
{
	/**
	 * Confirm enollment in a given course
	 * @author Christiaan van Woudenberg
	 * @version June 28, 2006
	 *
	 * @param object $course
	 * @return void
	 */
	public function reasonForEnrollment($course, $lists, $student) {
		global $mosConfig_live_site, $Itemid;
//		echo '<style type="text/css">';
//		echo '.formlabel, .formlabeltop {
//				width: 170px;
//			}</style>';
		echo '<div class="shell">';
		echo '<div class="colHeader">';
        if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            echo 'Inscribirse en el curso';
        }
        else {
            echo 'Enroll in Course';
        }
		echo '</div>';
		echo '<div id="colContent" class="row" style="margin: 0px;">';
		mosCommonHTML::loadOverlib();
		?>
		<?php
			$types = new Config_CourseTypes();
			echo $types->restrictedEnrollmentWarning($course);
      self::fireBeforeCourseInfo($student, $course);
		?>

<script language="javascript" type="text/javascript">
		<!--
		<?php A25_View_StudentConfirmation::javascript(); ?>
		//-->
		</script>

<div class="col-sm-8" style="margin-bottom: 15px;">

		<?php echo $this->showCourseInfo($course); ?>

  <form method="GET" name="enroll" id="enroll"
    action="commit-payment-option" tmt:validate="true">
    <input type="hidden" name="location_id"
    value="<?php echo $course->Location->location_id; ?>" />
    <input type="hidden" name="course_id" value="<?php echo $course->course_id; ?>" />
    <input
    type="hidden" name="status_id" id="status_id" value="1" />
    <div class="row" style="margin-top: 12px;">
      <div class="col-sm-4">
        <label for="hear_about_id">
        <?php if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            echo '&iquest;C&oacute;mo se enter&oacute; de nosotros';
        }
        else {
            echo 'How did you hear about us';
        } ?>?:
        </label>
      </div>
      <div class="col-sm-8">
        <?php echo $lists['heard_id']; ?>
      </div>
    </div>
    <div class="row" style="margin-top: 8px;">
      <div class="col-sm-4">
        <label for="reason_id">
        <?php if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            echo 'Raz&oacute;n para la inscripci&oacute;n:';
        }
        else {
            echo 'Reason For Enrollment:';
        }
        if (PlatformConfig::allowCourtReferrals) { ?>
          <img src="<?php echo $mosConfig_live_site; ?>/includes/js/tmt_validator/images/required.gif"
          border="0" width="10" height="8" align="absmiddle" />
          <?php } ?>
        </label>
      </div>
      <div class="col-sm-8">
        <?php echo $lists['reason_id']; ?>
      </div>
    </div>

  <?php A25_View_StudentConfirmation::courtSpecificFeatures($lists['court_id'], $course);

    self::fireAfterReasonForEnrollment($student, $course); ?>
      <input type="submit" value="<?php if ($course->course_type_id == A25_Record_Course::typeId_Spanish) {
            echo 'Inscribirse';
        }
        else {
            echo 'Enroll';
        } ?>" style="margin-top: 10px;"/>
      <?php echo $this->differentCourse($course) ?>
  </form>
</div>
<?php if (PlatformConfig::allowCourtReferrals && !PlatformConfig::allowCourtReferralsOnly) { ?>
<div class="col-sm-4" style="padding: 1em; border: solid red 1px; background-color: #eeeeee;">
  <img src="<?php echo $mosConfig_live_site; ?>/includes/js/tmt_validator/images/required.gif"
	border="0" align="absmiddle" />
<?php echo A25_DI::PlatformConfig()->reasonForEnrollmentCourtOrderText(); ?>
</div>
<?php } ?>

		<?php
		echo '</div>';
		echo '</div>';

	}

  abstract protected function showCourseInfo($course);
  abstract protected function differentCourse($course);

  private static function fireBeforeCourseInfo($student, $course)
  {
    foreach (A25_ListenerManager::all() as $listener) {
      if ($listener instanceof A25_ListenerI_StudentConfirmationWarning) {
        $listener->beforeCourseInfo($student, $course);
      }
    }
  }
  private static function fireAfterReasonForEnrollment($student, $course)
  {
    foreach (A25_ListenerManager::all() as $listener) {
      if ($listener instanceof A25_ListenerI_StudentConfirmationFields) {
        $listener->afterReasonForEnrollment($student, $course);
      }
    }
  }
}

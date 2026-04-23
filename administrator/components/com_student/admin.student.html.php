<?php
/**
 * $URL$
 * 
 * @package AliveAt25
 * @subpackage com_student
 * @author Christiaan van Woudenberg
 * @author Thomas Albright
 * @version $LastChangedRevision$, $Date$
 * @since Revision 1, 2007-11-22
 */

class HTML_student {

  function listStudent($rows, $pageNav, $option, $lists, $onlyShowForm) {
    global $mosConfig_offset, $my, $database;
    mosCommonHTML::loadOverlib();
		?>
		<script type="text/javascript">
		function tog(elem) {
		  if ($(elem).style.display == 'none') {
		    new Effect.BlindDown(elem, {duration: 0.2});
		    $('showsearch').innerHTML = '(Hide Search Form)';
		  } else {
		    new Effect.BlindUp(elem, {duration: 0.2});
		    $('showsearch').innerHTML = '(Show Search Form)';
		  }
		}
		</script>
		<form action="index2.php" method="get" name="adminForm">
		<table class="adminheading">
		<tr>
			<th rowspan="2">List Students</th>
		</tr>
		</table>
		<?php if (!$onlyShowForm) { ?>
			<div style="width: 100%; text-align: center; margin-bottom: 1em;">
			<span style="font-size: larger; font-weight: bold;">Search Results</span>
			<a href="javascript:void(0)" onClick="tog('advsearch')"><span id="showsearch">(Change your search)</span></a>
			</div>
		<?php } ?>
		<?php
		$searchDisplay = 'block';
		if (!$onlyShowForm) {
			$searchDisplay = 'none';
		}
		?>
		<div id="advsearch" style="margin-bottom: 20px; display:
				<?php echo $searchDisplay ?>;">
			<table cellspacing="0" cellpadding="0">
			<tr><td valign="top">
			<table cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td style="text-align: right">Student ID:</td>
					<td><input type="text" name="filter_student_id" id="filter_student_id" value="<?php echo $lists['filter_student_id']; ?>" size="20" maxlength="11" class="inputbox" /></td>
				</tr>
				<tr>
					<td style="text-align: right">First Name:</td>
					<td><input type="text" name="filter_first_name" id="filter_first_name" value="<?php echo $lists['filter_first_name']; ?>" size="20" maxlength="25" class="inputbox" /></td>
				</tr>
				<tr>
					<td style="text-align: right">Last Name:</td>
					<td><input type="text" name="filter_last_name" id="filter_last_name" value="<?php echo $lists['filter_last_name']; ?>" size="20" maxlength="25" class="inputbox" /></td>
				</tr>
				<tr>
					<td style="text-align: right">Email Address:</td>
					<td><input type="text" name="filter_email" id="filter_email" value="<?php echo $lists['filter_email']; ?>" size="20" maxlength="60" class="inputbox" /></td>
				</tr>
				<tr>
					<td style="text-align: right">City:</td>
					<td><input type="text" name="filter_city" id="filter_city" value="<?php echo $lists['filter_city']; ?>" size="20" maxlength="60" class="inputbox" /></td>
				</tr>
				<?php self::fireAfterLicenseState($lists['filter_license_no']); ?>
			</table>
			</td><td valign="top">
			<table cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td style="text-align: right">Status:</td>
					<td><?php echo $lists['filter_status']; ?></td>
				</tr>
				<tr>
					<td style="text-align: right">Referring Court:</td>
					<td><?php echo $lists['filter_court']; ?></td>
				</tr>
				</table>
			</td>
			</tr>
			<tr>
				<td colspan="2" style="padding-top: 1em; text-align: center">
					<input type="submit" id="applybutton" value="Search" />&nbsp;
					or <a href="<?php echo A25_Link::to('/administrator/index2.php?option=com_student&task=list')?>">
						Show all students</a>
				</td>
			</tr>
			</table>
		</div>

	<?php if (!$onlyShowForm) { ?>

		<table class="adminlist">
		<tr>
			<th width="20">#</th>
			<th width="20" class="title"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($rows); ?>);" /></th>
			<th class="title">Name</th>
			<th class="title">Status</th>
			<th class="title">Referring Court</th>
			<th class="title">Date of Birth</th>
			<th class="title">ID</th>
			<th class="title">Address</th>
			<th class="title">City</th>
			<th class="title">State</th>
			<th class="title">Zip</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($rows); $i < $n; $i++) {
		  $row = $rows[$i];
		  $row->id = $row->student_id;

		  $link 	= 'index2.php?option=com_student&task=viewA&id='. $row->id;

		  $checked 	= mosCommonHTML::CheckedOutProcessing( $row, $i );
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $pageNav->rowNumber( $i ); ?>
				</td>
				<td>
				<?php echo $checked; ?>
				</td>
				<td>
				<?php
				$name = $row->last_name.  ', ' . $row->first_name;
				$name .= $row->middle_initial ? ' ' . $row->middle_initial . '.' : '';

				if ( $row->checked_out && ( $row->checked_out != $my->id ) ) {
				  echo $name;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="Edit Student">
					<?php echo $name; ?>
					</a>
					<?php
				}
				?>
				</td>
				<td>
				<?php 	// HACK: Get the correct status_id from the student's latest xref entry
                	    $sql = "SELECT `status_id`"
                	    .  "\n FROM #__student_course_xref"
                	    .  "\n WHERE `student_id` = " . $row->student_id
                	    .  "\n ORDER BY `date_registered` DESC";
                	    $database->setQuery( $sql );
                	    $row->status_id = $database->loadResult();
	            ?>
				<?php echo $row->status_id ? $lists['status_id'][$row->status_id]->status_name : '-'; ?>
				</td>
				<td>
				<?php echo $row->court_name ? $row->court_name : '-'; ?>
				</td>
				<td align="center">
				<?php echo date('m/d/Y', strtotime($row->date_of_birth)); ?>
				</td>
				<td align="center">
				<?php echo $row->student_id; ?>
				</td>
				<td>
				<?php echo $row->address_1; ?>
				</td>
				<td>
				<?php echo $row->city; ?>
				</td>
				<td align="center">
				<?php echo $row->state; ?>
				</td>
				<td align="center">
				<?php echo $row->zip; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter();
		} ?>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="list" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="hidemainmenu" value="0">
		</form>
		<?php
  }

  private static function fireAfterLicenseState($filter_license_no)
  {
	  foreach (A25_ListenerManager::all() as $listener)
	  {
		  if ($listener instanceof A25_ListenerI_LicenseNo)
		  {
			  $listener->afterLicenseStateFilterAdminListStudentHtml($filter_license_no);
		  }
	  }
  }

  function noteForm( $row, $lists, $option ) {
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
		  var form = document.adminForm;
		  if (pressbutton == 'cancel') {
		    submitform( pressbutton );
		    return;
		  }

		  // do field validation
		  if ($F('note') == "") {
		    alert( "You must provide a note." );
		  } else {
		    submitform( pressbutton );
		  }
		}
		//-->
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Add Student Note
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th colspan="2">Note Details</th>
		</tr>
		<tr>
			<td width="150">
			</td>
			<td>
			<span class="required">&#149; Required Field</span>
			</td>
		</tr>
		<tr>
			<td>
			Student:
			</td>
			<td>
			<?php echo $row->first_name . ' ' . $row->last_name; ?>
			</td>
		</tr>
		<tr>
			<td valign="top">
			Note: <span class="required">&#149;</span>
			</td>
			<td>
			<textarea name="note" id="note" rows="10" style="width:400px;"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="student_id" value="<?php echo $row->student_id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
  }


  function msgForm( $row, $lists, $option ) {
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
		  var form = document.adminForm;
		  if (pressbutton == 'cancel') {
		    submitform( pressbutton );
		    return;
		  }

		  // do field validation
		  if ($F('message') == "") {
		    alert( "You must provide a message." );
		  } else {
		    submitform( pressbutton );
		  }
		}
		//-->
		</script>
		<form enctype="multipart/form-data" action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Send a Student Message
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th colspan="2">Message Details</th>
		</tr>
		<tr>
			<td width="150">
			</td>
			<td>
			<span class="required">&#149; Required Field</span>
			</td>
		</tr>
		<tr>
			<td>
			Student:
			</td>
			<td>
			<?php echo $row->first_name . ' ' . $row->last_name; ?>
			</td>
		</tr>
		<tr>
			<td>
				Subject: <span class="required">&#149;</span>
			</td>
			<td>
				<input type="text" name="subject" size="50" maxlength="100" class="inputbox" style="width:400px" value="" />
			</td>
		</tr>
		<tr>
			<td valign="top">
				Message: <span class="required">&#149;</span>
			</td>
			<td width="100%">
				<textarea name="message" id="message" style="width:400px" rows="20" class="inputbox"></textarea>
			</td>
		</tr>
		<tr>
			<td valign="top">
				Attachment:
			</td>
			<td width="100%">
				<input type="file" name="attachment" id="attachment" class="inputbox"></textarea>
			</td>
		</tr>
		</table>


		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="student_id" value="<?php echo $row->student_id; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
  }


  function enrollForm( $row, $lists, $option ) {
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th>
			Process Student Enrollment
			</th>
		</tr>
		</table>

		<table class="adminform">
		<tr>
			<th colspan="2">Enrollment Details</th>
		</tr>
		<tr>
			<td width="150">
			</td>
			<td>
			<span class="required">&#149; Required Field</span>
			</td>
		</tr>
		<tr>
			<td>
			Student: <span class="required">&#149;</span>
			</td>
			<td>
			<?php echo $row->first_name . ' ' . $row->last_name; ?>
			</td>
		</tr>
		<tr>
			<td>
			Location: <span class="required">&#149;</span>
			</td>
			<td>
			<?php echo $lists['location_id']; ?>
			</td>
		</tr>
		<?php if (strpos($lists['location_id'],'select') === false) { ?>
		<tr>
			<td>
			Course: <span class="required">&#149;</span>
			</td>
			<td>
			<?php echo $lists['course_id']; ?>
			</td>
		</tr>
		<tr>
			<td>
			How did you hear about us?: <span class="required">&#149;</span>
			</td>
			<td>
			<?php echo $lists['heard_id']; ?>
			</td>
		</tr>
		<tr>
			<td>
			Reason For Enrollment: <span class="required">&#149;</span>
			</td>
			<td>
			<?php echo $lists['reason_id']; ?>
			</td>
		</tr>
		<tr>
			<td>
			Referring Court:
			</td>
			<td>
			<?php echo $lists['court_id']; ?>
			</td>
		</tr>
		<tr>
			<td>
			Late Enrollment? <span class="required">&#149;</span>
			</td>
			<td>
			<?php echo $lists['is_late']; ?>
			</td>
		</tr>
    <?php self::fireAfterIsLate(); ?>
		<tr>
			<td></td>
			<td><input type="submit" value="Enroll Student" /></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		</table>

		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="id" value="<?php echo $row->student_id; ?>" />
		<input type="hidden" name="student_id" value="<?php echo $row->student_id; ?>" />
		<input type="hidden" name="task" value="<?php echo (strpos($lists['location_id'],'select') === false) ? 'enroll' : 'enrollform'; ?>" />
		</form>
		<?php
  }


  function viewEnrollment( A25_Record_Enroll $enroll ) {
    global $my;
		?>
		<table class="adminheading">
		<tr>
			<th>View Enrollment</th>
		</tr>
		</table>

  <?php // HACK: There are some hacks in here to give court admins access to view students. ?>
		<table width="100%">
		<tr>
			<td valign="top" width="60%">
				<table class="adminform">
				<tr>
					<th align="left" colspan="2">Course Details</th>
				</tr>
				<tr>
					<td align="left">Course Location</td>
					<td align="left"><?php echo $enroll->Course->getLocationName(); ?></td>
				</tr>
				<tr>
					<td align="left">Course Date:</td>
					<td align="left"><?php echo date("m/d/Y", strtotime($enroll->Course->course_start_date)); ?>
				</tr>
				<tr>
					<td align="left">Instructor:</td>
					<td align="left"><?php echo $enroll->Course->instructorName(); ?>
				</tr>
                <?php if ($enroll->Course->instructor_2_id > 0) {
                echo '
				<tr>
					<td align="left">Instructor 2:</td>
					<td align="left">' . $enroll->Course->instructor2Name() . '
				</tr>';
                }?>
				<tr>
					<th align="left" colspan="2">Student Details</th>
				</tr>
				<tr>
					<td align="left">Student ID:</td>
					<td align="left"><?php echo $enroll->Student->student_id; ?></td>
				</tr>
				<tr>
					<td align="left">Student Name:</td>
					<td align="left"><?php echo $enroll->Student->firstLastName(); ?></td>
				</tr>
				<tr>
					<td align="left">DOB:</td>
					<td align="left"><?php echo date("m/d/Y", strtotime($enroll->Student->date_of_birth)); ?></td>
				</tr>
				<tr>
				    <td align="left">How they heard about <?php echo PlatformConfig::courseTitleHtml() ?>:</td>
				    <td align="left"><?php echo $enroll->hearAboutName(); ?></td>
				</tr>
				<tr>
					<td align="left">Reason for attendance:</td>
					<td align="left"><?php echo $enroll->reasonName(); ?></td>
				</tr>
				<?php if ($enroll->isLegalMatter()) { ?>
				<tr>
					<td align="left">Referring Court:</td>
					<td align="left"><?php
					   $other_court_link = "<a href='" . A25_Link::to("/administrator/index2.php?option=com_court&task=new&hidemainmenu=1&xref_id=" . $enroll->xref_id) . "'>" . $enroll->court_other . "</a>";
					   echo ($enroll->court_id) ? $enroll->courtName() : "Other: " . $other_court_link;
					?></td>
				</tr>
				<?php } ?>
				<tr>
				    <td align="left">Date of Enrollment:</td>
				    <td align="left"><?php echo date("m/d/Y", strtotime($enroll->date_registered)); ?></td>
				</tr>
        <?php self::fireAfterEnrollmentDate($enroll); ?>
				</table>
			</td>
			<td width="10">&nbsp;</td>
      <?php // HACK to remove the control panel if user is a court admin
      if(!$my->isCourtAdministrator()) {
      ?>
			<td valign="top" width="40%">
				<table class="adminform">
				<tr>
					<th colspan="2">Student Enrollment Actions</th>
				</tr>
				<tr>
					<td colspan="2">
					<div id="cpanel">
						<div style="float:left;">
							<div class="icon">
								<a href="#" onclick="javascript:window.open('<?php echo A25_Link::to("/administrator/components/com_student/enrollmentEmail.php?xref_id=$enroll->xref_id") ?>','enroll','menubar=1,scrollbars=1,resizable=1,width=800,height=600');"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/printer.png"  alt="Print Enrollment Email" align="middle" border="0" /><span>View/Print Enrollment Email</span></a>
							</div>
						</div>
						<div style="float:left;">
							<div class="icon">
								<a href="<?php echo A25_Link::to('/administrator/index2.php?option=com_student&task=enrolledit&xref_id=' . $enroll->xref_id);?>"><img src="<?php echo $mosConfig_live_site; ?>/administrator/images/addedit.png"  alt="Edit Enrollment" align="middle" border="0" /><span>Edit Enrollment</span></a>
							</div>
						</div>
				<?php
				  if(trim($enroll->Student->email) != '') {
					$link = 'index2.php?option=com_student&task=sendEnrollmentEmail&id=' . $enroll->xref_id;
					HTML_student::quickiconButton( $link, 'note_f2.png', 'Re-Send Enrollment Email' );
				  }
                  
				  if($enroll->status_id == A25_Record_Enroll::statusId_completed) {
					$link = 'index2.php?option=com_student&task=sendCompletionEmail&id=' . $enroll->xref_id;
					HTML_student::quickiconButton( $link, 'note_f2.png', 'Re-Send Completion Email' );
				  }
                  
				  if(A25_DI::PlatformConfig()->allowResendReminders) {
					$link = 'index2.php?option=com_student&task=sendReminderEmail&id=' . $enroll->xref_id;
					HTML_student::quickiconButton( $link, 'note_f2.png', 'Re-Send Reminder Email' );
				  }?>

					</div>
					</td>
				</tr>
				</table>
			</td>
    <?php } ?>
		</tr>
		</table>

		<?php
  }

  private static function fireAfterEnrollmentDate($enroll)
  {
	  foreach (A25_ListenerManager::all() as $listener)
	  {
		  if ($listener instanceof A25_ListenerI_AdminEnroll)
		  {
			  $listener->afterEnrollmentDate($enroll);
		  }
	  }
  }

  private static function fireAfterIsLate()
  {
	  foreach (A25_ListenerManager::all() as $listener)
	  {
		  if ($listener instanceof A25_ListenerI_AdminEnroll)
		  {
			  $listener->afterIsLateNew();
		  }
	  }
  }

  /**
	 * Copied from mod_quickicon.php
	 *
	 * @param string $link
	 * @param string $image
	 * @param string $text
	 * @return void
	 */
  function quickiconButton( $link, $image, $text ) {
    global $my, $acl;
		?>
		<div style="float:left;">
			<div class="icon">
				<a href="<?php echo $link; ?>">
					<?php echo mosAdminMenus::imageCheckAdmin( $image, '/administrator/images/', NULL, NULL, $text ); ?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
  }
}
?>

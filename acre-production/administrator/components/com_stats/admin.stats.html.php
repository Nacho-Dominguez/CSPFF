<?php

class HTML_stats {

	/**
	 * Show course revenue report for the given time interval
	 * @author Christiaan van Woudenberg
	 *
	 * @param  array $stats
	 * @param  array $lists
	 * @return void
	 */
	function courseStats( &$stats, &$lists, &$pageNav, $limit, $offset) {
		A25_Javascript::loadOverlib();
		mosCommonHTML::loadCalendar();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th rowspan="3">Course Revenue Report</th>
			<td>From:</td>
			<td>To:</td>
		</tr>
		<tr>
			<td nowrap="nowrap" style="padding-right:30px;">
			<input class="text_area" type="text" name="f_from" id="f_from" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->from); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_from', 'm/d/Y');" value="..." />
			</td>
			<td nowrap="nowrap">
			<input class="text_area" type="text" name="f_to" id="f_to" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->to); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_to', 'm/d/Y');" value="..." />
			</td>
		</tr>
		<tr>
			<td colspan="4" align="right" nowrap="nowrap">
				<input type="submit" onClick="this.form.action='<?php echo A25_Link::to('/administrator/admin.stats.xls.php');?>'" value="Save for Excel" />
				<input type="submit" onClick="this.form.action='index2.php'" value="Update Statistics" />
			</td>
		</tr>
		</table>
		<?php if (!$stats->statistics->numCourses) { echo '<div class="message">No statistics available for the given filter set.</div>'; } else { ?>

		<h3>Summary Statistics</h3>
		<table class="adminlist">
		<tr>
		<td width="50%" align="center" valign="top">

		<table class="striped" width="300">
		<thead>
		<tr><th colspan="2">Counts</th></tr>
		</thead>
		<tbody>
		<tr><td class="numeric"><?php echo $stats->statistics->numCourses; ?></td><td>courses</td></tr>
		<tr><td class="numeric"><?php echo $stats->statistics->numLosingCourses; ?></td><td>losing courses (<a href="index2.php?option=com_stats&task=losing<?php echo '&f_from=' . date("m/d/Y",$stats->filter->from) . '&f_to' . date("m/d/Y",$stats->filter->to); ?>">view details</a>)</td></tr>
		<tr><td class="numeric"><?php echo round(100*$stats->statistics->numLosingCourses/$stats->statistics->numCourses,2); ?>%</td><td>losing course rate</td></tr>
		</tbody>
		</table>

		</td>
		<td width="50%" align="center" valign="top">

		<table class="striped" width="300">
		<thead>
		<tr><th colspan="2">Finances</th></tr>
		</thead>
		<tbody>
		<tr><td class="numeric">$<?php echo number_format($stats->statistics->totalRevenue,2); ?>
		</td><td>gross revenue</td></tr>
		<tr><td class="numeric">$<?php echo number_format($stats->statistics->totalGrossProfit,2); ?>
		</td><td>gross profit</td></tr>
		<tr><td class="numeric">$<?php echo number_format($stats->statistics->totalInstructorPayroll,2); ?>
		</td><td>instructor payroll</td></tr>
		<tr><td class="numeric">$<?php echo number_format($stats->statistics->totalLosingCost,2); ?>
		</td><td>lost profit from losing courses</td></tr>
		</tbody>
		</table>

		</td>
		</tr>
		</table>

		<h3>Details</h3>

		<?php
			self::coursesTable($stats, $limit, $offset);
			echo $pageNav->getListFooter();
		} ?>
		<input type="hidden" name="option" value="com_stats" />
		<input type="hidden" name="task" value="course" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}

	private function coursesTable($stats, $limit, $offset)
	{
		?>
		<table class="adminlist">
		<tr>
			<th class="title">Course ID</th>
			<th class="title">Location</th>
			<th class="title">Date/Time</th>
			<th><?php echo mosToolTip('Registered','',80,null,'R'); ?></th>
			<th><?php echo mosToolTip('Student','',80,null,'S'); ?></th>
			<th><?php echo mosToolTip('Complete','',80,null,'C'); ?></th>
			<th><?php echo mosToolTip('Cancelled','',80,null,'X'); ?></th>
			<th><?php echo mosToolTip('No Show','',80,null,'N'); ?></th>
			<th><?php echo mosToolTip('Unavailable','',80,null,'U'); ?></th>
			<th><?php echo mosToolTip('Pending','',80,null,'P'); ?></th>
			<th><?php echo mosToolTip('Court Ordered Enrollment','',80,null,'CO'); ?></th>
			<th><?php echo mosToolTip('Voluntary Enrollment','',80,null,'V'); ?></th>
			<th><?php echo mosToolTip('Number of Paid Enrollments','',80,null,'# Paid'); ?></th>
			<th><?php echo mosToolTip('Number of Instructors','',80,null,'# Inst'); ?></th>
			<th align="right">Profit</th>
		</tr>
		<?php
		foreach ($stats->data as $item) {
			$course_ids[] = $item->course_id;
		}
		// This query attempts to fetch all related records to the courses that
		// will be displayed, so that no more database queries are needed.
		$q = Doctrine_Query::create()
			->from('A25_Record_Course c')
			->leftJoin('c.Enrollments e')
			->leftJoin('e.Order o')
			->leftJoin('o.Payments p')
			->leftJoin('o.OrderItems i')
			->whereIn('c.course_id', $course_ids)
			->orderBy('c.course_start_date')
			->limit($limit);
		// The offset was already taken care of by $stats->load()
//		if ($offset)
//			$q->offset($offset);
		$courses = $q->execute();

		$k = 0;
		foreach ($courses as $course) {
			$link 	= 'index2.php?option=com_course&task=viewA&id='. $course->course_id;
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $course->course_id; ?>
				</td>
				<td>
					<?php
					try {
						echo $course->getLocationName();
					} catch (A25_Exception_DataConstraint $e) {
						// Do nothing.  This means a location has not been assigned.
						// We don't want that to crash the report.
					}
					?>
				</td>
				<td>
					<a href="<?php echo $link; ?>"><?php echo $course->getFormattedDateTime() ?></a>
				</td>
				<td style="text-align:center">
				<?php echo $course->getEnrollmentStatusCount(
						A25_Record_Enroll::statusId_registered); ?>
				</td>
				<td style="text-align:center">
				<?php echo $course->getEnrollmentStatusCount(
						A25_Record_Enroll::statusId_student); ?>
				</td>
				<td style="text-align:center">
				<?php echo $course->getEnrollmentStatusCount(
						A25_Record_Enroll::statusId_completed); ?>
				</td>
				<td style="text-align:center">
				<?php echo $course->getEnrollmentStatusCount(
						A25_Record_Enroll::statusId_canceled); ?>
				</td>
				<td style="text-align:center">
				<?php echo $course->getEnrollmentStatusCount(
						A25_Record_Enroll::statusId_noShow); ?>
				</td>
				<td style="text-align:center">
				<?php echo $course->getEnrollmentStatusCount(
						A25_Record_Enroll::statusId_unavailable); ?>
				</td>
				<td style="text-align:center">
				<?php echo $course->getEnrollmentStatusCount(
						A25_Record_Enroll::statusId_pending); ?>
				</td>
				<td style="text-align:center">
					<?php echo $course->getCourtOrderedCount(); ?>
				</td>
				<td style="text-align:center">
				<?php echo $course->getNotCourtOrderedCount(); ?>
				</td>
				<td style="text-align:center">
				<?php echo $course->getPaidEnrollmentCount(); ?>
				</td>
				<td style="text-align:center">
				<?php echo $course->getNumberOfInstructors(); ?>
				</td>
				<td style="text-align:right">
					<?php echo $course->getProfit() ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php
	}

	/**
	 * Choose instructors for instructor statistics
	 * @author Christiaan van Woudenberg
	 * @version September 18, 2006
	 *
	 * @param  array $stats
	 * @param  array $lists
	 * @return void
	 */
	function chooseInsts( $stats, $lists ) {
		global $mosConfig_live_site;
		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cpanel') {
				submitform( pressbutton );
				return;
			}
		}
		//-->
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th rowspan="3">Instructor Statistics</th>
			<td>From:</td>
			<td>To:</td>
			<td>Show As:</td>
		</tr>
		<tr>
			<td nowrap="nowrap" style="padding-right:30px;">
			<input class="text_area" type="text" name="f_from" id="f_from" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->from); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_from', 'm/d/Y');" value="..." />
			</td>
			<td nowrap="nowrap" style="padding-right:30px;">
			<input class="text_area" type="text" name="f_to" id="f_to" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->to); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_to', 'm/d/Y');" value="..." />
			</td>
			<td>
			<?php echo $lists['f_per']; ?>
			</td>
		</tr>
		</table>

		<table class="adminform">
			<thead>
			<tr><th colspan="3">Choose Instructor(s)</th></tr>
			</thead>
			<tbody>
			<tr>
			<td>
			<p>To view instructor statistics, please choose instructor(s) from the following list:</p>
			<p><?php echo $lists['insts']; ?></p>
			<input type="submit" value="View Instructor Statistics" />
			</td>
			</tr>
			</tbody>
		</table>
		<input type="hidden" name="option" value="com_stats" />
		<input type="hidden" name="task" value="instructor" />
		</form>
		<?php
	}


	/**
	 * Show losing courses report for the given time interval
	 * @author Christiaan van Woudenberg
	 * @version September 10, 2006
	 *
	 * @param  array $stats
	 * @param  array $lists
	 * @return void
	 */
	function losingStats( &$stats, &$lists, &$pageNav, $limit, $offset ) {
		global $my, $mosConfig_live_site;
		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar();
		?>
		<form action="index2.php" method="get" name="adminForm">
		<table class="adminheading">
		<tr>
			<th rowspan="3">Losing Courses Report</th>
			<td>From:</td>
			<td>To:</td>
			<td>Location:</td>
		</tr>
		<tr>
			<td nowrap="nowrap" style="padding-right:30px;">
			<input class="text_area" type="text" name="f_from" id="f_from" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->from); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_from', 'm/d/Y');" value="..." />
			</td>
			<td nowrap="nowrap">
			<input class="text_area" type="text" name="f_to" id="f_to" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->to); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_to', 'm/d/Y');" value="..." />
			</td>
			<td nowrap="nowrap">
			<?php echo $lists['f_lid']; ?>
			</td>
		</tr>
		<tr>
			<td colspan="4" align="right" nowrap="nowrap">
				<input type="submit" onClick="this.form.action='<?php echo $mosConfig_live_site; ?>/administrator/admin.stats.xls.php'" value="Save for Excel" />
				<input type="submit" onClick="this.form.action='index2.php'" value="Update Statistics" />
			</td>
		</tr>
		</table>
		<?php
		if (!count($stats->data)) {
			echo '<div class="message">No statistics available for the given filter set.</div>';
		} else {
			self::coursesTable($stats, $limit, $offset);
			echo $pageNav->getListFooter();
		} ?>

		<input type="hidden" name="option" value="com_stats" />
		<input type="hidden" name="task" value="losing" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}


	/**
	 * Show marketing statistics for the given time interval
	 * @author Christiaan van Woudenberg
	 * @version September 19, 2006
	 *
	 * @param  array $stats
	 * @param  array $lists
	 * @return void
	 */
	function marketingStats( $stats, $lists ) {
		global $mosConfig_live_site;
		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar();
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			var form = document.adminForm;
			if (pressbutton == 'cpanel') {
				submitform( pressbutton );
				return;
			}
		}
		//-->
		</script>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th rowspan="3">Marketing Statistics</th>
			<td>From:</td>
			<td>To:</td>
			<td>Show As:</td>
		</tr>
		<tr>
			<td nowrap="nowrap" style="padding-right:30px;">
			<input class="text_area" type="text" name="f_from" id="f_from" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->from); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_from', 'm/d/Y');" value="..." />
			</td>
			<td nowrap="nowrap" style="padding-right:30px;">
			<input class="text_area" type="text" name="f_to" id="f_to" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->to); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_to', 'm/d/Y');" value="..." />
			</td>
			<td>
			<?php echo $lists['f_per']; ?>
			</td>
		</tr>
		<tr>
			<td colspan="3" align="right" nowrap="nowrap">
				<input type="submit" onClick="this.form.action='index2.php'" value="Update Statistics" />
			</td>
		</tr>
		</table>
		<?php if (!$stats->statistics->age->total) { echo '<div class="message">No statistics available for the given filter set.</div>'; } else { ?>

		<table cellspacing="0" cellpadding="0" border="0" width="100%">
			<tr>
				<td valign="top" width="25%" style="padding-right:25px;">
					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="3">Students By Sex</th></tr>
						</thead>
						<thead>
						<tr><th>Sex</th><th class="r">Value</th><th class="r">Percent</th></tr>
						</thead>
						<tbody id="genderStats">
						<tr><td>Male</td><td class="numeric"><?php echo $stats->statistics->gender->data['M']->count; ?></td><td class="numeric"><?php echo round(100*$stats->statistics->gender->data['M']->count/($stats->statistics->gender->data['M']->count + $stats->statistics->gender->data['F']->count),1); ?>%</td></tr>
						<tr><td>Female</td><td class="numeric"><?php echo $stats->statistics->gender->data['F']->count; ?></td><td class="numeric"><?php echo round(100*$stats->statistics->gender->data['F']->count/($stats->statistics->gender->data['M']->count + $stats->statistics->gender->data['F']->count),1); ?>%</td></tr>
						<tr><td>TOTAL</td><td class="numeric"><?php echo ($stats->statistics->gender->data['M']->count+$stats->statistics->gender->data['F']->count); ?></td><td class="numeric">100%</td></tr>
						</tbody>
					</table>

					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="4">Students By Age</th></tr>
						<tr><th class="r">Age</th><th class="r">Value</th><th class="r">Percent</th><th width="100">&nbsp;</th></tr>
						</thead>
						<tbody id="ageStats">
						<?php
						$ct = count($stats->statistics->age->data);
						foreach ($stats->statistics->age->data as $age => $a) {
							echo '<tr><td class="numeric">' . ($age ? $age : '(n/a)') . '</td><td class="numeric">' . $a->count . '</td><td class="numeric">' . round(100*$a->count/$stats->statistics->age->total,1) . '%</td><td><div class="gmarker" style="width:' . ceil(120*$a->count/$stats->statistics->age->total) . 'px;"></td></tr>' . "\n";
						}
						?>
						<tr><td class="numeric">TOTAL</td><td class="numeric"><?php echo $stats->statistics->age->total; ?></td><td class="numeric">100%</td><td>&nbsp;</td></tr>
						</tbody>
					</table>
				</td>
				<td valign="top" width="45%" style="padding-right:25px;">
					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="4">Referrals By Court</th></tr>
						<tr><th>Court</th><th class="r">Value</th><th class="r">Percent</th><th width="100">&nbsp;</th></tr>
						</thead>
						<tbody id="referralsByCourt">
						<?php
						foreach ($stats->statistics->court->data as $c) {
							echo '<tr><td>' . ($c->court_name ? $c->court_name : '(n/a)') . '</td><td class="numeric">' . $c->count . '</td><td class="numeric">' . round(100*$c->count/$stats->statistics->court->total,1) . '%</td><td><div class="gmarker" style="width:' . ceil(120*$c->count/$stats->statistics->court->total) . 'px;"></td></tr>' . "\n";
						}
						?>
						<tr><td>TOTAL</td><td class="numeric"><?php echo $stats->statistics->court->total; ?></td><td class="numeric">100%</td><td>&nbsp;</td></tr>
						</tbody>
					</table>
				</td>
				<td valign="top" width="30%">
					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="4">Student Counts By Reason For Attendance</th></tr>
						<tr><th>Reason</th><th class="r">Value</th><th class="r">Percent</th><th width="100">&nbsp;</th></tr>
						</thead>
						<tbody id="studentCountsAttendenceReasonStats">
						<?php
						foreach ($stats->statistics->reason->data as $r) {
							echo '<tr><td>' . ($r->reason_name ? $r->reason_name : '(n/a)') . '</td><td class="numeric">' . $r->count . '</td><td class="numeric">' . round(100*$r->count/$stats->statistics->reason->total,1) . '%</td><td><div class="gmarker" style="width:' . ceil(120*$r->count/$stats->statistics->reason->total) . 'px;"></td></tr>' . "\n";
						}
						?>
						<tr><td>TOTAL</td><td class="numeric"><?php echo $stats->statistics->reason->total; ?></td><td class="numeric">100%</td><td>&nbsp;</td></tr>
						</tbody>
					</table>

					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="4">Student Revenue By Reason For Attendance</th></tr>
						<tr><th>Reason</th><th class="r">Value</th><th class="r">Percent</th><th width="100">&nbsp;</th></tr>
						</thead>
						<tbody id="studentRevenueAttendenceReasonStats">
						<?php
						foreach ($stats->statistics->reason->data as $r) {
							echo '<tr><td>' . ($r->reason_name ? $r->reason_name : '(n/a)') . '</td><td class="numeric">$' . number_format($r->gross_revenue,2) . '</td><td class="numeric">' . round(100*$r->gross_revenue/$stats->statistics->reason->total_revenue,1) . '%</td><td><div class="gmarker" style="width:' . ceil(120*$r->gross_revenue/$stats->statistics->reason->total_revenue) . 'px;"></td></tr>' . "\n";
						}
						?>
						<tr><td>TOTAL</td><td class="numeric">$<?php echo number_format($stats->statistics->reason->total_revenue,2); ?></td><td class="numeric">100%</td><td>&nbsp;</td></tr>
						</tbody>
					</table>

					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="4">Students By Referral Method</th></tr>
						<tr><th>Referred By</th><th class="r">Value</th><th class="r">Percent</th><th width="100">&nbsp;</th></tr>
						</thead>
						<tbody id="studentReferralsMethodStats">
						<?php
						foreach ($stats->statistics->hear_about->data as $h) {
							echo '<tr><td>' . ($h->hear_about_name ? $h->hear_about_name : '(n/a)') . '</td><td class="numeric">' . $h->count . '</td><td class="numeric">' . round(100*$h->count/$stats->statistics->hear_about->total,1) . '%</td><td><div class="gmarker" style="width:' . ceil(120*$h->count/$stats->statistics->hear_about->total) . 'px;"></td></tr>' . "\n";
						}
						?>
						<tr><td>TOTAL</td><td class="numeric"><?php echo $stats->statistics->hear_about->total; ?></td><td class="numeric">100%</td><td>&nbsp;</td></tr>
						</tbody>
					</table>

					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="4">Student Revenue By Referral Method</th></tr>
						<tr><th>Reason</th><th class="r">Value</th><th class="r">Percent</th><th width="100">&nbsp;</th></tr>
						</thead>
						<tbody id="studentRevenueReferralMethodStats">
						<?php
						foreach ($stats->statistics->hear_about->data as $h) {
							echo '<tr><td>' . ($h->hear_about_name ? $h->hear_about_name : '(n/a)') . '</td><td class="numeric">$' . number_format($h->gross_revenue,2) . '</td><td class="numeric">' . round(100*$h->gross_revenue/$stats->statistics->hear_about->total_revenue,1) . '%</td><td><div class="gmarker" style="width:' . ceil(120*$h->gross_revenue/$stats->statistics->hear_about->total_revenue) . 'px;"></td></tr>' . "\n";
						}
						?>
						<tr><td>TOTAL</td><td class="numeric">$<?php echo number_format($stats->statistics->hear_about->total_revenue,2); ?></td><td class="numeric">100%</td><td>&nbsp;</td></tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<?php } ?>

		<input type="hidden" name="option" value="com_stats" />
		<input type="hidden" name="task" value="marketing" />
		</form>
		<?php
	}


	/**
	 * Show payment report for the given time interval
	 * @author Christiaan van Woudenberg
	 * @version September 19, 2006
	 *
	 * @param  array $stats
	 * @param  array $lists
	 * @return void
	 */
	function creditTypeStats( &$stats, &$lists, &$pageNav ) {
		global $my, $mosConfig_live_site;
		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th rowspan="3">Credit Type Usage Report</th>
			<td>Credit type:</td>
			<td>From:</td>
			<td>To:</td>
		</tr>
		<tr>
			<td><?php echo $lists['filter_credit_type']; ?></td>
			<td nowrap="nowrap" style="padding-right:30px;">
			<input class="text_area" type="text" name="f_from" id="f_from" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->from); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_from', 'm/d/Y');" value="..." />
			</td>
			<td nowrap="nowrap">
			<input class="text_area" type="text" name="f_to" id="f_to" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->to); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_to', 'm/d/Y');" value="..." />
			</td>
		</tr>
		<tr>
			<td colspan="4" align="right" nowrap="nowrap">
				<input type="submit" onClick="this.form.action='<?php echo $mosConfig_live_site; ?>/administrator/admin.stats.xls.php'" value="Save for Excel" />
				<input type="submit" onClick="this.form.action='index2.php'" value="Update Statistics" />
			</td>
		</tr>
		</table>
		<?php if (!count($stats->data)) { echo '<div class="message">No statistics available for the given filter set.</div>'; } else { ?>

		<table class="adminlist">
		<tr>
			<th class="title">Payment Date</th>
			<th class="title">Course Start Date</th>
			<th class="title">Payment ID</th>
			<th class="title">Student ID</th>
			<th class="title">Last Name</th>
			<th class="title">First Name</th>
			<th class="title">Course ID</th>
			<th class="title">Credit Type</th>
			<th class="title">
				Amount (Total = <?php echo $stats->summation; ?>)
			</th>
			<th class="title">Course Location</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($stats->data); $i < $n; $i++) {
			$row = $stats->data[$i];
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $row->payment_date; ?>
				</td>
				<td>
				<?php echo $row->course_start_date; ?>
				</td>
				<td>
				<?php echo $row->pay_id; ?>
				</td>
				<td>
				<?php echo $row->student_id; ?>
				</td>
				<td>
				<?php echo $row->last_name; ?>
				</td>
				<td>
				<?php echo $row->first_name; ?>
				</td>
				<td>
				<?php echo $row->course_id; ?>
				</td>
				<td>
				<?php echo $row->credit_type_name; ?>
				</td>
				<td>
				<?php echo $row->amount; ?>
				</td>
				<td>
				<?php echo $row->location_name; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		<?php } ?>

		<input type="hidden" name="option" value="com_stats" />
		<input type="hidden" id="adminform_task" name="task" value="creditTypeStats" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}

	/**
	 * Show payment report for the given time interval
	 * @author Christiaan van Woudenberg
	 * @version September 19, 2006
	 *
	 * @param  array $stats
	 * @param  array $lists
	 * @return void
	 */
	function paymentStats( &$stats, &$lists, &$pageNav ) {
		global $my, $mosConfig_live_site;
		mosCommonHTML::loadOverlib();
		mosCommonHTML::loadCalendar();
		?>
		<form action="index2.php" method="post" name="adminForm">
		<table class="adminheading">
		<tr>
			<th rowspan="3">Payments Received Report</th>
			<td>From:</td>
			<td>To:</td>
		</tr>
		<tr>
			<td nowrap="nowrap" style="padding-right:30px;">
			<input class="text_area" type="text" name="f_from" id="f_from" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->from); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_from', 'm/d/Y');" value="..." />
			</td>
			<td nowrap="nowrap">
			<input class="text_area" type="text" name="f_to" id="f_to" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->to); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_to', 'm/d/Y');" value="..." />
			</td>
		</tr>
		<tr>
			<td colspan="4" align="right" nowrap="nowrap">
				<input type="submit" onClick="this.form.action='<?php echo $mosConfig_live_site; ?>/administrator/admin.stats.xls.php'" value="Save for Excel" />
				<input type="submit" onClick="this.form.action='index2.php'" value="Update Statistics" />
			</td>
		</tr>
		</table>
		<?php if (!count($stats->data)) { echo '<div class="message">No statistics available for the given filter set.</div>'; } else { ?>

			<h2>Total Amount: <?php echo $stats->total_amount; ?></h2>
		<table class="adminlist">
		<tr>
			<th class="title">Payment Date</th>
			<th class="title">Payment ID</th>
			<th class="title">Student ID</th>
			<th class="title">Last Name</th>
			<th class="title">First Name</th>
			<th class="title">Course ID</th>
			<th class="title">Course Date</th>
			<th class="title">Payment Method</th>
			<th class="title">Amount</th>
			<th class="title">Paid By</th>
			<th class="title">CC Transaction ID</th>
			<th class="title">Check Number</th>
			<th class="title">Notes</th>
			<th class="title">Email</th>
		</tr>
		<?php
		$k = 0;
		for ($i=0, $n=count($stats->data); $i < $n; $i++) {
			$row = $stats->data[$i];
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td>
				<?php echo $row->payment_date; ?>
				</td>
				<td>
				<?php echo $row->pay_id; ?>
				</td>
				<td>
				<?php echo $row->student_id; ?>
				</td>
				<td>
				<?php echo $row->last_name; ?>
				</td>
				<td>
				<?php echo $row->first_name; ?>
				</td>
				<td>
				<?php echo $row->course_id; ?>
				</td>
				<td>
				<?php echo $row->course_date; ?>
				</td>
				<td>
				<?php echo $row->pay_type_name; ?>
				</td>
				<td>
				<?php echo $row->amount; ?>
				</td>
				<td>
				<?php echo $row->paid_by_name; ?>
				</td>
				<td>
				<?php echo $row->cc_trans_id; ?>
				</td>
				<td>
				<?php echo $row->check_number; ?>
				</td>
				<td>
				<?php echo $row->notes; ?>
				</td>
				<td>
				<?php echo $row->email; ?>
				</td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<?php echo $pageNav->getListFooter(); ?>
		<?php } ?>

		<input type="hidden" name="option" value="com_stats" />
		<input type="hidden" name="task" value="payment" />
		<input type="hidden" name="boxchecked" value="0" />
		</form>
		<?php
	}
}
?>

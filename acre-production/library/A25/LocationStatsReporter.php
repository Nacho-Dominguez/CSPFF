<?php

require_once(dirname(__FILE__) .
		'/../../administrator/components/com_stats/stats.class.php');

/**
 * This class has the functions that generate the Location Statistics Report.
 */
class A25_LocationStatsReporter
{
	/**
	 * Shows location statistics given various filters.
	 *
	 * @param stdClass $filter -- filter has these public properties:
	 *	- from -- date of first results, in time format.  This can be done
	 *	          using strtotime('12/31/2007')
	 *	- to -- date of last results, in time format.
	 *	- lid -- location ID
	 *	- per -- true if results should be displayed as percentages, false if
	 *	         results should be displayed as absolute value
	 *
	 * @param database $database -- a Joomla database object.
	 *
	 * @param array $locs -- an array of selected location id's.  The first
	 * element should be 'all' if all locations should be searched.
	 *
	 * @param array $insts -- an array of selected instructo id's.  The first
	 * element should be 'all' if all locations should be searched.
	 *
	 * @return void
	 */
	public static function locationStats($filter, $database, $locs)
	{
		$stats = new locationStats( $filter, $locs );
		$stats->summary($database, $insts);

		$lists = array();

		$p = array();
		$p[] = mosHTML::makeOption(0,'Absolute Values');
		$p[] = mosHTML::makeOption(1,'Percentages');
		$lists['f_per'] = mosHTML::selectList( $p, 'f_per', 'class="inputbox"', 'value', 'text', $filter->per);

		$where = array();

		if ($locs[0] != 'all') {
			$where[] = "l.location_id IN (" . implode(",",$locs) . ")";
		}

		// build list of locs
		$locs = array();
		$locs[] = mosHTML::makeOption('','- All Locations -');
		$sql = "SELECT `location_id` AS value, `location_name` AS text FROM #__location"
		. ( count( $where ) ? "\n WHERE " . implode( ' AND ', $where ) : "" )
		. "\n ORDER BY `location_name`;";
		$database->setQuery($sql);
		$locs = array_merge($locs,$database->loadObjectList());
		$lists['f_lid'] = mosHTML::selectList( $locs, 'f_lid', '', 'value', 'text', $filter->lid);

		// build list of Instructors
		$locs = array();
		$locs[] = mosHTML::makeOption('','- All Instructors -');
		$sql = "SELECT u.`id` AS value, u.`name` AS text FROM #__users u ORDER BY `name`;";
		$database->setQuery($sql);
		$locs = array_merge($locs,$database->loadObjectList());
		$lists['f_instructorId'] = mosHTML::selectList( $locs, 'f_instructorId', '', 'value', 'text', $filter->instructorId);
    
    self::fireAddLocationFilter($filter->agencyId, $lists);

		self::locationStatsHtml( $stats, $lists );
	}
  
  private static function fireAddLocationFilter($filter, &$lists)
  {
    foreach (A25_ListenerManager::all() as $listener) {
      if ($listener instanceof A25_ListenerI_LocationStats) {
        $listener->addLocationFilter($filter, $lists);
      }
    }
  }

	/**
	 * @param  array $stats
	 * @param  array $lists
	 * @return void
	 */
	private static function locationStatsHtml( $stats, $lists )
	{
		// I don't think this is even used by the GUI in this page
		//mosCommonHTML::loadOverlib();
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
			<th rowspan="3">Location Statistics Report</th>
			<td rowspan="2">
      <?php self::renderFilters($lists) ?></td>
      <td>From:</td>
      <td>To:</td>
		</tr>
		<tr>
			<td nowrap="nowrap" style="padding-right:30px; vertical-align:top;">
			<input class="text_area" type="text" name="f_from" id="f_from" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->from); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_from', 'm/d/Y');" value="..." />
			</td>
			<td nowrap="nowrap" style="padding-right:30px; vertical-align:top;">
			<input class="text_area" type="text" name="f_to" id="f_to" size="10" maxlength="10" value="<?php echo date('m/d/Y',$stats->filter->to); ?>" />
			<input name="reset" type="reset" class="button" onclick="return showCalendar('f_to', 'm/d/Y');" value="..." />
			</td>
		</tr>
		<tr>
			<td colspan="5" align="right" nowrap="nowrap">
				<!-- <input type="submit" onClick="this.form.action='<?php echo ServerConfig::currentUrl(); ?>/administrator/admin.stats.xls.php'" value="Save for Excel" /> -->
				<input type="submit" onClick="this.form.action='index2.php'" value="Update Statistics" />
			</td>
		</tr>
		</table>
		<?php if (!$stats->statistics->course->total) { echo '<div class="message">No statistics available for the given filter set.</div>'; } else { ?>

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
						<tbody>
              <?php
              $male_count = $stats->statistics->gender->data['M']->count;
              if($male_count == null)
                $male_count = 0;
              
              $female_count = $stats->statistics->gender->data['F']->count;
              if ($female_count == null)
                $female_count = 0;
              
              $total_count = $male_count + $female_count;
              if ($total_count > 0) {
                $male_percent = round(100 * $male_count / $total_count, 1);
                $female_percent = round(100 * $female_count / $total_count, 1);
              } else {
                $male_percent = 0;
                $female_percent = 0;
              }
              ?>
						<tr><td>Male</td><td class="numeric"><?php echo $male_count ?></td><td class="numeric"><?php echo $male_percent ?>%</td></tr>
						<tr><td>Female</td><td class="numeric"><?php echo $female_count ?></td><td class="numeric"><?php echo $female_percent ?>%</td></tr>
						<tr><td>TOTAL</td><td class="numeric"><?php echo $total_count ?></td><td class="numeric">100%</td></tr>
						</tbody>
					</table>

					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="4">Students By Age</th></tr>
						<tr><th class="r">Age</th><th class="r">Value</th><th class="r">Percent</th><th width="100">&nbsp;</th></tr>
						</thead>
						<tbody>
						<?php
						$ct = count($stats->statistics->age->data);
						foreach ($stats->statistics->age->data as $age => $a) {
							echo '<tr><td class="numeric">' . ($age ? $age : '(n/a)') . '</td><td class="numeric">' . $a->count . '</td><td class="numeric">' . round(100*$a->count/$stats->statistics->age->total,1) . '%</td><td><div class="gmarker" style="width:' . ceil(120*$a->count/$stats->statistics->age->total) . 'px;"></td></tr>' . "\n";
						}
						?>
						<tr><td class="numeric">TOTAL</td><td class="numeric"><?php echo $stats->statistics->age->total; ?></td><td class="numeric">100%</td><td>&nbsp;</td></tr>
						</tbody>
					</table>

					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="4">Student Enrollments By Status</th></tr>
						<tr><th>Type</th><th class="r">Value</th><th class="r">Percent</th><th width="100">&nbsp;</th></tr>
						</thead>
						<tbody>
						<?php
						foreach ($stats->statistics->enroll->data as $r) {
							echo '<tr><td>' . ($r->status_name ? $r->status_name : '(n/a)') . '</td><td class="numeric">' . $r->count . '</td><td class="numeric">' . round(100*$r->count/$stats->statistics->enroll->total,1) . '%</td><td><div class="gmarker" style="width:' . ceil(120*$r->count/$stats->statistics->enroll->total) . 'px;"></td></tr>' . "\n";
						}
						?>
						<tr><td>TOTAL</td><td class="numeric"><?php echo $stats->statistics->enroll->total; ?></td><td class="numeric">100%</td><td>&nbsp;</td></tr>
						</tbody>
					</table>
				</td>
				<td valign="top" width="45%" style="padding-right:25px;">
					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="4">Courses By Status</th></tr>
						<tr><th>Type</th><th class="r">Value</th><th class="r">Percent</th><th width="100">&nbsp;</th></tr>
						</thead>
						<tbody>
						<?php
						foreach ($stats->statistics->course->data as $c) {
							echo '<tr><td>' . ($c->status_name ? $c->status_name : '(n/a)') . '</td><td class="numeric">' . $c->count . '</td><td class="numeric">' . round(100*$c->count/$stats->statistics->course->total,1) . '%</td><td><div class="gmarker" style="width:' . ceil(120*$c->count/$stats->statistics->course->total) . 'px;"></td></tr>' . "\n";
						}
						?>
						<tr><td>TOTAL</td><td class="numeric"><?php echo $stats->statistics->course->total; ?></td><td class="numeric">100%</td><td>&nbsp;</td></tr>
						</tbody>
					</table>

					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="9">Courses By Gross Revenue</th></tr>
						<tr>
							<th class="r"><?php echo mosToolTip('Course ID','',80,null,'CID'); ?></th>
							<th class="r"><?php echo mosToolTip('Location ID','',80,null,'LID'); ?></th>
							<th>Date/Time</th>
							<th class="r" nowrap="nowrap"><?php echo mosToolTip('Number of Students / Classroom Capacity','',80,null,'# Stu'); ?></th>
							<th>Status</th>
							<th class="r">Gross Revenue</th>
							<th colspan="3" width="100">&nbsp;</th>
						</thead>
						<tbody>
						<?php
						foreach ($stats->statistics->rev->data as $r) {
							$gross_revenue = ( $r->gross_revenue < 0) ? '-$' . number_format(-$r->gross_revenue,2) : '$' . number_format($r->gross_revenue,2);
							echo '<tr><td class="numeric">' . $r->course_id . '</td><td class="numeric">' . $r->location_id . '</td><td nowrap="nowrap"><a href="index2.php?option=com_course&task=viewA&id=' . $r->course_id . '">' . $r->course_date . ' ' . $r->course_time . '</a></td><td class="numeric">' . $r->num_students . '/' . $r->course_capacity . '</td><td>' . $r->course_status . '&nbsp;</td><td class="numeric">' . ($gross_revenue != '$' ? $gross_revenue : '') . '</td>';
							if ($r->gross_revenue < 0) {
							#prabhakar 11102006 for  dividing by zero Error id 768
								if($stats->statistics->rev->max_abs_profit <=0)
								{
								echo '<td class="inputbox" style="padding-right:0px;"><div class="rmarker"> Zero Value</div></td>';
								}
								else
								{
								echo '<td class="numeric" style="padding-right:0px;"><div class="rmarker" style="width:' . ceil(abs(60*$r->gross_revenue/$stats->statistics->rev->max_abs_profit)) . 'px;"></div></td>';
								}
							} else {
								echo '<td>&nbsp;</td>';
							}
							echo '<td class="divider"><img src="images/blank.png" border="0" width="1" height="1" /></td>';
							if ($r->gross_revenue > 0) {
								echo '<td style="padding-left:0px;"><div class="gmarker" style="width:' . ceil(60*$r->gross_revenue/$stats->statistics->rev->max_abs_profit) . 'px;"></div></td>';
							} else {
								echo '<td>&nbsp;</td>';
							}
							echo '</tr>' . "\n";
						}
						?>
						<tr><td colspan="3" class="numeric">TOTAL</td><td class="numeric"><?php echo $stats->statistics->rev->total_students . '/' . $stats->statistics->rev->total_capacity; ?></td><td colspan="2" class="numeric">$<?php echo number_format($stats->statistics->rev->total_profit,2); ?></td><td colspan="3">&nbsp;</td></tr>
						</tbody>
					</table>
				</td>
				<td valign="top" width="30%">
					<table cellspacing="0" cellpadding="0" border="0" class="striped" style="margin-bottom:25px;" width="100%">
						<thead>
						<tr><th colspan="4">Students By Reason For Attendance</th></tr>
						<tr><th>Reason</th><th class="r">Value</th><th class="r">Percent</th><th width="100">&nbsp;</th></tr>
						</thead>
						<tbody>
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
						<tr><th colspan="5">Instructors By Primary/Secondary</th></tr>
						<tr><th>Name</th><th class="r"># Pri</th><th class="r"># Sec</th><th class="r">Total</th><th width="<?php echo floor(min(100,400/count($stats->statistics->inst->data))); ?>">&nbsp;</th></tr>
						</thead>
						<tbody>
						<?php
						foreach ($stats->statistics->inst->data as $i) {
							echo '<tr><td>' . ($i->name ? $i->name : '(n/a)') . '</td><td class="numeric">' . $i->count_pri . '</td><td class="numeric">' . $i->count_sec . '</td><td class="numeric">' . ($i->count_pri+$i->count_sec) . '</td><td><div class="gmarker" style="width:' . ceil(120*($i->count_pri+$i->count_sec)/$stats->statistics->inst->total) . 'px;"></td></tr>' . "\n";
						}
						?>
						<tr><td>TOTAL</td><td class="numeric"><?php echo $stats->statistics->inst->total_pri; ?></td><td class="numeric"><?php echo $stats->statistics->inst->total_sec; ?></td><td class="numeric"><?php echo ($stats->statistics->inst->total_pri+$stats->statistics->inst->total_sec); ?></td><td>&nbsp;</td></tr>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
		<?php } ?>

		<input type="hidden" name="option" value="com_stats" />
		<input type="hidden" name="task" value="location" />
		</form>
		<?php
	}
	
	private static function renderFilters($lists)
	{
    foreach ($lists as $list)
    {
      echo $list;
    }
	}
}

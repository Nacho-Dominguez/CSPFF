<?php

class A25_OldCom_Admin_NewMessageHtml
{
	public static function newMessage( $row, $lists, $option ) {
		?>
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
			if (pressbutton == 'cancel') {
				submitform( pressbutton );
				return;
			}

			// do field validation
			if ($F('subject') == '') {
				alert( "Please enter a subject for your message." );
			} else if ($F('message') == '') {
				alert( "Please enter a body for your message." );
			} else {
				submitform( pressbutton );
			}
		}
		//-->
		</script>
		<table class="adminheading">
		<tr>
			<th class="inbox">New Message</th>
		</tr>
		</table>

		<table width="100%">
		<tr>
			<td valign="top" width="60%">
				<form enctype="multipart/form-data" action="index2.php" method="post" name="adminForm" id="adminForm">
				<table class="adminform">
				<tr>
					<th colspan="2">
					Message Details
					</th>
				</tr>
				<tr>
					<td width="150">
					</td>
					<td>
					<span class="required">&#149; Required Field</span>
					</td>
				</tr>
				<tr>
					<td class="formlabel">
					Subject: <span class="required">&#149;</span>
					</td>
					<td>
					<input type="text" name="subject" id="subject" size="30" maxlength="80" class="inputbox" style="width:400px;" value="" />
					</td>
				</tr>
				<tr>
					<td class="formlabeltop">
					Message: <span class="required">&#149;</span>
					</td>
					<td>
					<textarea name="message" id="message" rows="15" style="width:400px;"></textarea>
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
				<input type="hidden" name="course_id" value="<?php echo $row->course_id; ?>" />
				<input type="hidden" name="task" value="" />
				</form>
			</td>
			<td width="10">&nbsp;</td>
			<td valign="top" width="40%">
				<table class="adminform">
				<tr>
					<th colspan="2">Course Information</th>
				</tr>
				<tr>
					<td colspan="2">
					<?php echo $row->showCourseInfo('full'); ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<?php
	}
}

?>

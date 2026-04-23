<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
abstract class A25_Filter_DateRange extends A25_Filter
{
  protected function smartField($from_name, $to_name)
  {
    $htmlHead = A25_DI::HtmlHead();
    $htmlHead->includeJquery();
		// Setup for calendar javascript
    $htmlHead->stylesheet(
        '/includes/third-party/jquery-ui-1.8.16.custom/css/jquery-ui-1.8.16.custom.css');
    $htmlHead->javascriptFile('/includes/third-party/jquery-ui-1.8.16.custom/jquery-ui-1.8.16.custom.min.js');
    $htmlHead->append('
    <script type="text/javascript">
    jQuery(function() {
      $("#' . $from_name . '").datepicker();
      $("#' . $to_name . '").datepicker();
    });
    </script>');
    
		return '
		<div class="date_range">From:<br/>
			<input type="text" name="' . $from_name . '" id="' . $from_name . '" size="10" maxlength="10"
				value="' . $this->$from_name . '" />
		</div>
    <div class="date_range">To:<br/>
			<input type="text" name="' . $to_name . '" id="' . $to_name . '" size="10" maxlength="10"
				value="' . $this->$to_name . '" />
		</div>';
  }
}
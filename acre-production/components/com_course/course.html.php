<?php
/**
 * $URL$
 *
 * @package AliveAt25
 * @subpackage course
 * @version $LastChangedRevision$, $Date$
 */

defined('_VALID_MOS') or die('Direct Access to this location is not allowed.');

/**
 * PlatformConfig class, needed for coloring available states in map.
 */
require_once($mosConfig_absolute_path . '/PlatformConfig.php');

/**
 * Contains the HTML-generating functions for com_course.
 *
 * @package AliveAt25
 * @subpackage course
 * @author Christiaan van Woudenberg
 */
class HTML_course {
	/**
	 * Show receipt for course enrollment
	 * @author Christiaan van Woudenberg
	 * @version July 6, 2006
	 *
	 * @param object $enroll
	 * @param object $course
	 * @param object $student
	 * @param object $pay
	 * @param object $suppay
	 * @return void
	 */
	function receipt($enroll) {
		?>
		<div class="shell">
		<div id="colHeader"></div>
		<div id="colContent">
		<?php
    echo $enroll->getEnrollmentEmailBody();
    self::fireAppendReceipt($enroll);
    ?>
		</div>
		</div>
		<?php
	}
  
  private function fireAppendReceipt($enroll)
  {
    foreach (A25_ListenerManager::all() as $listener)
    {
      if ($listener instanceof A25_ListenerI_AppendReceipt)
        $listener->appendReceipt ($enroll);
    }
  }
}
<?php
/**
* Alive At 25
* @version $Id: install.location.php 210 2006-04-02 21:48:58Z beat $
* @package Alive At 25
* @subpackage install.location.php
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

function com_install() {
	global $database;
	?>
	<h3>Installation Process:</h3>
	<?php
	echo "Updating administration icons ...<br />";
	$iconresult = array();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/content.png' WHERE admin_menu_link='option=com_location&task=list'");
	$iconresult[] = $database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/edit.png' WHERE admin_menu_link='option=com_location&task=new'");
	$iconresult[] = $database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/edit.png' WHERE admin_menu_link='option=com_location&task=newparent'");
	$iconresult[] = $database->query();

	foreach ($iconresult as $i=>$icresult) {
		if ($icresult) {
			echo '<div><span style="color:green;">FINISHED:</span> Image of menu entry ' . $i . ' has been corrected.</div>';
		} else {
			echo '<div><span style="color:red;">Error:</span> Image of menu entry ' . $i . ' could not be corrected.</div>';
		}
	}

	echo '<div><span style="color:green;">Installation finished.</span>.</div>';
	}
?>

<?php

function com_install() {
	global $database;
	?>
	<h3>Installation Process:</h3>
	<?php
	echo "Updating administration icons ...<br />";
	$iconresult = array();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/content.png' WHERE admin_menu_link='option=com_instructor&task=list'");
	$iconresult[] = $database->query();
	$database->setQuery("UPDATE #__components SET admin_menu_img='js/ThemeOffice/content.png' WHERE admin_menu_link='option=com_instructor&task=new'");
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

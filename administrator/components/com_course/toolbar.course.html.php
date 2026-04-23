<?php

class TOOLBAR_course
{
	function _MESSAGE() {
		mosMenuBar::startTable();
		mosMenuBar::save('sendmsg','Send');
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}

	function _CANCEL() {
		mosMenuBar::startTable();
		mosMenuBar::save('cancelcourse','Send');
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}

	function _VIEW() {
		mosMenuBar::startTable();
		echo A25_Buttons::toolbarWithUnassumingUrl('Return to List',
				$_SESSION['last_search'], 'restore_f2.png');
		mosMenuBar::endTable();
	}

	function _DEFAULT() {
		echo A25_Buttons::toolbarWithUnassumingUrl('New',
				A25_Link::to('/administrator/edit-course'), 'new_f2.png');
	}
}
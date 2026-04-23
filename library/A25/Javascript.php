<?php
/**
 * @todo-soon - Convert this into a A25_Include static class, complete
 * with checking whether or not it has already been loaded, similar to
 * A25_Include_Broadcast. 
 */
class A25_Javascript
{
	public static function loadOverLib()
	{
		?>
			<script language="javascript" type="text/javascript" src="/includes/js/overlib_mini.js"></script>
			<script language="javascript" type="text/javascript" src="/includes/js/overlib_hideform_mini.js"></script>
			<div id="overDiv" style="position:absolute; visibility:hidden; z-index:10000;"></div>
		<?php
	}
}
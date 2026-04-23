<?php

namespace Acre\AdminTemplate;

class Header
{
    public function run()
    {
        ob_start();
?>
<link rel="stylesheet"
  href="<?php echo \A25_Link::to('/administrator/templates/aliveat25_admin/css/template_css.css') ?>"
  type="text/css" />
<div id="wrapper">
	<div id="header">
			<div id="logo"><?php echo \PlatformConfig::courseTitleHtml() ?> Site Administration</div>
	</div>
</div>
<table width="100%" class="menubar" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="menubackgr" style="padding-left:5px;">
		<?php \mosLoadAdminModule('fullmenu');?>
	</td>
	<td class="menubackgr" align="right">
		<div id="wrapper1">
			<?php \mosLoadAdminModules('header', 2);?>
		</div>
	</td>
	<td class="menubackgr" align="right" style="padding-right:5px;">
        <a href="<?php echo \A25_Link::to('/administrator/index2.php?option=logout') ?>"
            style="color: #333333; font-weight: bold">Logout</a>
		<strong><?php echo \A25_DI::User()->username;?></strong>
	</td>
</tr>
</table>
<?php
        return ob_get_clean();
    }
}

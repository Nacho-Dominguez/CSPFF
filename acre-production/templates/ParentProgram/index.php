<?php defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' ); ?>
<html>
<head>
<?php mosShowHead();?>
<link rel="stylesheet" href="templates/<?php echo $cur_template;?>/template.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $cur_template;?>/com_aliveat25.css" type="text/css" />
<!-- TODO: These should not be included by template, as they are needed
regardless of the template -->
<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/includes/js/scriptaculous/prototype.js"></script>
<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/includes/js/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/includes/js/velocera.javascript.js"></script>
<script type="text/javascript" src="<?php echo $mosConfig_live_site; ?>/includes/js/tmt_validator/script_tmt_validator.js"></script>
</head>
<?php echo A25_DI::HtmlHead()->render(); ?>
<body>
<div class="wrapper">
<div class="head">
<div class="title">
<div class="logo">
<span class="logo_alive">Alive</span>
<span class="logo_at">At</span>
<span class="logo_25">25</span>
<span class="logo_tm">&reg;</span>
</div>
<div class="pp_title">
<div class="pp_title_pp">Parent Program</div>
<div class="pp_title_tag">Saving Lives Through Education</div>
</div>
</div>
<div class="user5">
<ul id="mainlevel-header">
	<li><a href="<?php echo PlatformConfig::contactUrl()?>"
		   class="mainlevel-header">Contact Us</a></li>
	<li><span class="mainlevel-header" > |</span></li>
	<li><a href="<?php echo PlatformConfig::accountUrl()?>"
		   class="mainlevel-header">My Account</a></li>
</ul></div>
</div>
<div class="user3">
<?php mosLoadModules('user3',-1)?>
</div>
<div class="content">
<?php mosMainBody();?>
<div class="clr"></div>
</div>
</div>
<div class="footer">
<div class="user9">
<ul id="mainlevel-bottom">
	<li><a href="<?php echo A25_Link::to('/component/option,com_location/task,privacypolicy/');?>"
		class="mainlevel-bottom" >Privacy Policy</a></li>
	<li><a href="<?php echo PlatformConfig::contactUrl()?>"
		class="mainlevel-bottom" >Contact Us</a></li>
	<li><a href="http://aliveat25.us/content/view/14/14/"
		class="mainlevel-bottom" >Become an Instructor</a></li>
</ul>
&copy; 2008 The Colorado State Patrol Family Foundation
</div>
<div class="user6">
<?php mosLoadModules('user6',-1)?>
</div>
</div>
</body>
</html>

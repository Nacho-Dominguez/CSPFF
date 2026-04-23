<?php

class Controller_Administrator_Updates_ResponsiveDesign extends Controller
{
	public function executeTask()
	{
    A25_DI::HtmlHead()->append('<link href="'
        . A25_Link::to('/templates/aliveat25/css/bootstrap.css')
        . '" rel="stylesheet" media="screen" />
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">');
    A25_DI::HtmlHead()->includeJquery();
		$user_id = A25_DI::UserId();
		if ($user_id < 1)
			exit("You need to be logged in to view this."); ?>
<div style="text-align: left; font-size: 14px; width: 640px; margin-left: auto; margin-right: auto; margin-bottom: 48px;">
<h1>Mobile Responsive Design<small style="font-size: 14px; margin-left: 12px;">December 18, 2013</small></h1>

<p>Students can now use the site easier with the cell phone, thanks to a new
  mobile-friendly design.</p>
<p>On a regular computer screen, everything still looks largely the same. But if
  you shrink the window size so that it gets very skinny, you will see the site
  "respond" by scaling everything down so that it still fits nicely.</p>
<p>Of course, rather than trying it with a skinny browser window on your
  computer, you can also try it directly with a smartphone.</p>
<p><i>(This only applies to the student-facing side of the site.  The /administrator
    section will not do this.)</i></p>
<p>Here are some screenshots of the site on an iPhone:</p>
<div id="mycarousel" class="carousel slide" data-ride="carousel"
     style="max-width: 320px; margin-left: auto; margin-right: auto;
     margin-top: 36px; margin-bottom: 36px;">
  <div class="carousel-inner">
    <div class="item active">
      <img src="/administrator/images/homepage.png" alt="homepage">
    </div>
    <div class="item">
      <img src="/administrator/images/find-a-course.png" alt="homepage">
    </div>
    <div class="item">
      <img src="/administrator/images/course-info.png" alt="course-info">
    </div>
    <div class="item">
      <img src="/administrator/images/enrollment-confirmation.png"
           alt="enrollment-confirmation">
    </div>
    <div class="item">
      <img src="/administrator/images/payment-form.png" alt="homepage">
    </div>
    <div class="item">
      <img src="/administrator/images/donation-ad.png" alt="homepage">
    </div>
    <div class="item">
      <img src="/administrator/images/donation-form.png" alt="homepage">
    </div>
  </div>
  <a class="left carousel-control" href="#mycarousel" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left"></span>
  </a>
  <a class="right carousel-control" href="#mycarousel" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right"></span>
  </a>
</div>
Our stats show that more than 1/3 of students are now registering via their
smartphones, rather than a traditional computer, so this should be very useful.
</div>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
<?php
	}
}

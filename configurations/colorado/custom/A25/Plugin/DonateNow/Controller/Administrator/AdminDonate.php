<?php

class Controller_Administrator_AdminDonate extends Controller
{
  public function executeTask()
  {
    if (!A25_DI::User()->isAdminOrHigher())
      throw new Exception('Permission denied.');

    $head = A25_DI::HtmlHead();
    ob_start();
    ?>
    <style type="text/css">
      a.donate-button {
     display: block;
     padding: 12px;
     margin: 12px;
     /*background: -webkit-linear-gradient(top, rgba(250,250,250,1) 0%,rgba(220,220,220,1) 100%);*/
     background: rgb(255,255,255); /* Old browsers */
/* IE9 SVG, needs conditional override of 'filter' to 'none' */
background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmZmZmZiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNlNWU1ZTUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
background: -moz-linear-gradient(top, rgba(255,255,255,1) 0%, rgba(229,229,229,1) 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,255,255,1)), color-stop(100%,rgba(229,229,229,1))); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, rgba(255,255,255,1) 0%,rgba(229,229,229,1) 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, rgba(255,255,255,1) 0%,rgba(229,229,229,1) 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, rgba(255,255,255,1) 0%,rgba(229,229,229,1) 100%); /* IE10+ */
background: linear-gradient(to bottom, rgba(255,255,255,1) 0%,rgba(229,229,229,1) 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#e5e5e5',GradientType=0 ); /* IE6-8 */
     border-radius: 10px;
     box-shadow: 1px 1px 5px #888;
     font-size: 14px;
     vertical-align: top;
     text-align: center;
      }
      a.donate-button:hover {
        background: rgb(246,246,246);
      }
      span.donate-image {
        display: block; height: 110px; padding-bottom: 12px;
      }
      .donation_type {
        display: inline-block;
        font-size: 12px;
        width: 160px;
      }
    </style>
    <!--[if lt IE 8]>
    <style type="text/css">
        .donation_type { display: inline; }
    </style>
    <![endif]-->
    <?php
    $head->append(ob_get_clean());
    ?>
    <div style="margin: 24px; background-color: #f7f7d0; padding: 32px;
        box-shadow: 0px 0px 10px #666; font-size: 14px; max-width: 600px;
        border-radius: 5px; color: #444; text-align: center; display: inline-block;">
      <div style="font-size: 20px; font-weight: bold; color: #333;
           margin-top: 18px;">New Donation</div>
      <div style="margin-top: 12px;color: #555;">Select type & method of donation</div>
      <p style="clear: both; vertical-align: top;">
    <div class="donation_type" style="margin: 0px 24px;">
      <span class="donate-image">
      <img style="margin-top: 24px; margin-bottom: 12px;" src="<?php echo A25_Link::to('/images/sivvus_scales.png')?>" alt="Court-ordered donation" width="80px" /></span>
      For court order
    <a href="<?php echo A25_Link::to('/court-ordered-donation')?>" class="donate-button">
      Credit/Debit Card
    </a>
    <a href="<?php echo A25_Link::to('/administrator/court-donation-without-processing')?>" class="donate-button">
      Check or Money Order
    </a>
    </div>
    <div class="donation_type">
      <span class="donate-image">
      <img style="margin-top: 48px; margin-bottom: 4px;" src="<?php echo A25_Link::to('/images/heart.png')?>" alt="General donation" width="80px" /></span>
      <p>For other reason</p>

    <a href="<?php echo A25_Link::to('/general-donation')?>" class="donate-button">
      Credit/Debit Card
    </a>
    <a href="<?php echo A25_Link::to('/administrator/general-donation-without-processing')?>" class="donate-button">
      Check or Money Order
    </a>
    </div>
        </p>
		</div>
    <?php
  }
}

<?php

class A25_Plugin_DonateToFund implements
    A25_ListenerI_Reports,
    A25_ListenerI_AddIcons,
    A25_ListenerI_BannerAd
{
    public function appendToIndividualRecordReports()
    {
        echo '<dt><a href="list-fund-donations">Donations to Funds</a></dt>';
    }

    public function afterAdminButtons()
    {
        if (!A25_DI::User()->isAdminOrHigher()) {
            return;
        }

        quickiconButton('view-funds', 'heart_small.png', 'View Donation Funds');
        quickiconButton('admin-fund-donate', 'heart_small.png', 'Donation to Fund');
    }

    public function afterState()
    {
?>
<div style="clear: both"></div>
<style type="text/css">
a#license_ad {
background-color: #446611;
}
a#license_ad:hover {
background-color: #557722;
text-decoration: none;
}
#header {
height: auto;
}
#top-bottom {
height: auto;
}
</style>
<style type="text/css">
a.donate-button, a.donate-button:visited {
padding-left: 20px; padding-right: 20px; margin: 8px 16px; font-size: 12px;
display: inline-block;
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
font-size: 12px;
width: 190px;
height: 85px;
vertical-align: top;
text-align: center;
}
a.donate-button img {
border: none;
}
a.donate-button:hover {
background: rgb(246,246,246);
}
span.donate-image {
display: block; height: 48px; padding-bottom: 0px;
}
</style>
<div style="margin: 0px 15px; background-color: #f7f7d0; padding: 0px;
font-size: 14px;
border-radius: 5px; color: #444; text-align: center;">
<div style="font-size: 20px; font-weight: bold; color: #333;
margin-top: 18px;">Help us save lives. Donate Now.</div>
<p style="clear: both; vertical-align: top;">
<a href="<?php echo A25_Link::to('fund-donation')?>" class="donate-button">
<span class="donate-image">
<img style="margin-top: 8px; margin-bottom: 4px;" src="<?php echo A25_Link::to('/images/heart_small.png')?>" alt="Fund donation" width="38px" /></span>
Donate Now
</a>
</p>
</div>
<?php
    }
}

set_include_path(
    ServerConfig::webRoot . '/plugins/DonateToFund' . PATH_SEPARATOR
    . get_include_path()
);

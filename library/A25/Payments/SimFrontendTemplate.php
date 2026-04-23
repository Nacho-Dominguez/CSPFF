<?php

namespace Acre\A25\Payments;

class SimFrontendTemplate
{
    public function paymentFormHeader()
    {
        $header = \A25_DI::PlatformConfig()->siteTemplateHeader();
        return \A25_Functions::includeCss(true) . $this->cssForAuthorizeNetForm()
            . $header->run();
    }

    public function paymentFormFooter()
    {
        $footer = new \Acre\A25\Template\StandardFooter(
            new \Acre\A25\Template\NoTracker()
        );
        return $footer->run();
    }

    private function cssForAuthorizeNetForm()
    {
        return '<meta name="viewport" content="width=device-width; initial-scale=1.0;">
<style type="text/css">
.HeaderFooter1 { padding: 0px; }
#formPayment { background-color: #f7f7d0;
box-shadow: 0px 0px 10px #666;
border-radius: 5px;
color: #444;
width: 634px;
max-width: 100%;
margin: 24px auto;
display: block;
padding: 32px;
overflow-x: auto; }
.Page { border: 1px solid #769e3b;
background-color: #daeea6;
width: 780px;
max-width: 100%;
padding: 0px;
overflow-x: auto; }
.PageOuter { background: #01104b
url(http://aliveat25.us/templates/aliveat25/images/bodyBG.jpg)
repeat-x;
margin: 0px; }
.Footer2 { width: 780px;
max-width: 100%;
margin-left: auto;
margin-right: auto; }
@media (max-width: 680px) {
#formPayment { padding: 4px; }
}
</style>';
    }
}

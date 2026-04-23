<?php

namespace Acre\A25\Payments;

class SimAdminTemplate
{
    public function paymentFormHeader()
    {
        $header = new \Acre\AdminTemplate\Header();
        $header->run();
        return $this->cssForAuthorizeNetForm() . $header->run();
    }

    public function paymentFormFooter()
    {
        return '';
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
#divPageOuter, #divPage {
    border:none;
    width: 100%;
    padding: 0px;
    margin: 0px;
}
#logo {
    text-align: left;
}
@media (max-width: 680px) {
#formPayment { padding: 4px; }
}
</style>';
    }
}

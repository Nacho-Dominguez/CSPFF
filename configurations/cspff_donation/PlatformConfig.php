<?php

require_once(dirname(__FILE__) . '/../../PlatformConfigState.php');

class PlatformConfig extends PlatformConfigState
{
    const AUTHORIZE_NET_LOGIN = '4qc9Vb9Ug';
    const AUTHORIZE_NET_TRAN_KEY = '4cg8ZGq9x79Qy28h';

    const messageSenderId = 494;

    const phoneNumber = '(720) 269-4046';

    const businessHours = 'Monday-Thursday 8am-4pm<br />Friday 8am-1pm Mountain Time';

    public function siteTemplateHeader()
    {
        return new Acre\A25\Template\NoAccountHeader(
            new Acre\A25\Template\StandardTopMenuContainer(
                new Acre\A25\Template\StandardTopMenu()
            )
        );
    }
    public $automatedReportRecipients = array('jonathan@appdevl.net', 'barry.bratt@state.co.us');
    public function automatedReportFields($donation)
    {
        $fund = $donation->Fund;
        $return .= $donation->created . ',' . $donation->benefactor . ','
                . $donation->amount . ',' . $donation->cc_trans_id . ','
                . $fund->name . ',' . '
';
        return $return;
    }
    public function automatedReportQuery()
    {
        $query = Doctrine_Query::create()
                ->select('*')
                ->from('A25_Record_FundDonation d')
                ->where('d.fund_id = 22');
        return $query;
    }
    public $automatedReportTitle = '8 States conference registration report';
}

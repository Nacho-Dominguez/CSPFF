<?php

class A25_Report_FundDonations extends A25_Report
{
    protected $isLegacy = false;
    private $fundFilter;
    private $donationDateFilter;
    public function __construct($limit, $offset)
    {
        parent::__construct(null, $limit, $offset);

        $this->fundFilter = new A25_Filter_Fund;
        $this->donationDateFilter = new A25_Filter_DonationDate;
        $this->filters = array($this->fundFilter, $this->donationDateFilter);
    }
    protected function formatRow(A25_DoctrineRecord $donation)
    {
        return array(
        'Date' => $donation->created,
        'Benefactor' => $donation->benefactor,
        'Fund' => $donation->Fund->name,
        'Amount' => $donation->amount,
        'Pay Type' => $this->payTypeName($donation->pay_type_id),
        'Credit Card Transaction #' => $donation->cc_trans_id,
        'View Receipt' => $this->viewReceipt($donation)
        );
    }

    private function viewReceipt($donation)
    {
        return '<a href="' . A25_Link::to(
            '/administrator/fund-donation-receipt?id=' . $donation->id
        )
            . '">View Receipt</a>';
    }

    private function payTypeName($type_id)
    {
        switch ($type_id) {
            case A25_Record_Pay::typeId_Cash:
                return 'Cash';
            break;
            case A25_Record_Pay::typeId_Check:
                return 'Check';
            break;
            case A25_Record_Pay::typeId_CreditCard:
                return 'Credit Card';
            break;
            case A25_Record_Pay::typeId_MoneyOrder:
                return 'Money Order';
            break;

            default:
                return 'None';
            break;
        }
    }

    protected function query()
    {
        $q = Doctrine_Query::create()
            ->from('A25_Record_FundDonation d');

        return $q;
    }

    protected function name()
    {
        return 'Donations to Funds';
    }

    protected function heading()
    {
    ?>
		<form action="list-fund-donations" method="get" name="adminForm" id="adminForm">
		<h1 style="background: url(images/generic.png) no-repeat left;
			text-align: left;
			padding: 12px;
			width: 99%;
			padding-left: 50px;
			border-bottom: 5px solid #fff;
			color: #C64934;
			font-size: 18px;">
			<?php echo $this->name() ?> Report
		</h1>
		<?php
        $this->filters();
    }

    protected function filters()
    {
        foreach ($this->filters as $filter) {
            echo $filter->htmlFormElement();
        }
    ?>
		<div style="float:left; clear: left; margin: 12px 0px;">
			<input type="submit" onClick="this.form.limitstart.value=0"
				   value="Update Statistics" />
      <input type="submit" onClick="this.form.action='<?php
        echo A25_Link::to('/administrator/export-fund-donations')?>'"
        value="Export to Excel" />
    </div>
    <?php
    }

    protected function footer($showPageNav = true)
    {
        A25_Listing::footer();
    }
}

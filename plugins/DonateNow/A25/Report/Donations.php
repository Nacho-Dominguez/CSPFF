<?php

class A25_Report_Donations extends A25_Report
{
  protected $isLegacy = false;
  private $donationTypeFilter;
  private $donationDateFilter;
  public function __construct($limit, $offset)
  {
    parent::__construct(null, $limit, $offset);
    
    $this->donationTypeFilter = new A25_Filter_DonationType;
    $this->donationDateFilter = new A25_Filter_DonationDate;
    $this->filters = array($this->donationTypeFilter, $this->donationDateFilter);
  }
  protected function formatRow(A25_DoctrineRecord $donation)
  {
		return array(
        'Date' => $donation->created,
        'Benefactor' => $donation->benefactor,
        'Donation Type' => $this->donationTypeName($donation->reason),
        'Amount' => $donation->amount,
        'Pay Type' => $this->payTypeName($donation->pay_type_id),
        'Defendant' => $donation->defendant,
        'Court Name' => $donation->courtName(),
        'Credit Card Transaction #' => $donation->cc_trans_id,
        'View Receipt' => $this->viewReceipt($donation)
		);
  }
  
  private function viewReceipt($donation)
  {
    return '<a href="' . A25_Link::to(
					'/administrator/donation-receipt?id='
					. $donation->id)
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
      case A25_Record_Pay::typeId_ScholarshipCredit:
        return 'Scholarship Credit';
        break;

      default:
        return 'None';
        break;
    }
  }
  
  private function donationTypeName($reason)
  {
    switch ($reason) {
      case A25_Record_IndependentDonation::reason_None:
        return 'General';
        break;
      case A25_Record_IndependentDonation::reason_LicensePlate:
        return 'License Plate';
        break;
      case A25_Record_IndependentDonation::reason_CourtOrder:
        return 'Court Order';
        break;
    }
  }
  
  protected function query()
  {
    $q = Doctrine_Query::create()
      ->from('A25_Record_IndependentDonation d');
    
    return $q;
  }

	protected function name()
	{
		return 'Independent (non-student) Donations';
	}
  
  // Duplication with A25_Report->heading()
  protected function heading()
  {
    ?>
		<form action="list-donations" method="get" name="adminForm" id="adminForm">
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
  
  // Some duplication with A25_Report->filters().  The main difference is in the
  // way it is exported to Excel.
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
        echo A25_Link::to('/administrator/export-donations')?>'"
        value="Export to Excel" />
    </div>
    <?php
  }

	protected function footer($showPageNav = true)
	{
		A25_Listing::footer();
	}
}

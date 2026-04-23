<?php

class A25_Report_IncomeSummary
{
	protected $isExportable = false;
	private $runningTotal;
  private $accrualDateFilter;

  public function __construct()
  {
    $this->accrualDateFilter = new A25_Filter_AccrualDate();
  }
  
	protected function heading()
	{
		A25_DI::HtmlHead()->stylesheet('/templates/aliveat25/css/a25_filters_forReports.css');
    echo '<form action="income-summary" method="get" name="adminForm" style="max-width: 651px;">';
		echo "<h1 style='text-align:left; color: #333;'>Income Summary</h1>";
    echo $this->accrualDateFilter->htmlFormElement();
    echo '<p style="float: left; clear: both;"><input type="submit" /></p>';
    echo '<p style="font-style: italic; color: 666; clear: left;">This report counts fees as accrued income whether or not the fees have actually been paid.</p>';
    echo '<p style="font-style: italic; color: 666;">Click on a Total to drill down to specifics.</p>';
	}
	
	public function run()
	{
		$this->heading();
		?>
		<table style="clear: both; border: 1px solid black; padding: 1em;
			background-color: #F6F6F6;">
			<thead style="font-weight: bold;">
				<tr>
					<td style="width: 180px;">TYPE</td>
					<td style="text-align: right;">Subtotals</td>
					<td style="padding-left: 1em;">Accrual</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Tuition</td>
					<td style="text-align: right;"><?php echo $this->tuition() ?></td>
					<td style="padding-left: 1em;">Accrues on the class date.</td>
				</tr>
				<tr>
					<td>Late Payment Fees</td>
					<td style="text-align: right;"><?php echo $this->latePaymentFees(); ?></td>
					<td style="padding-left: 1em;">Accrues on the date the fee is created.</td>
				</tr>
				<tr>
					<td>Replacement Certificate Fees</td>
					<td style="text-align: right;"><?php echo $this->replacementCertificateFees(); ?></td>
					<td style="padding-left: 1em;">Accrues on the date the fee is created.</td>
				</tr>
				<tr>
					<td>Returned Check (NSF) Fees</td>
					<td style="text-align: right;"><?php echo $this->returnedCheckFees() ?></td>
					<td style="padding-left: 1em;">Accrues on the date the fee is created.</td>
				</tr>
				<tr>
					<td>Credit Card Fees</td>
					<td style="text-align: right;"><?php echo $this->creditCardFees() ?></td>
					<td style="padding-left: 1em;">Accrues on the date the fee is created.</td>
				</tr>
				<tr>
					<td>Forfeited Tuition Due to No-Shows</td>
					<td style="text-align: right;"><?php echo $this->noShowFees() ?></td>
					<td style="padding-left: 1em;">Accrues on the date the fee is created.</td>
				</tr>
				<tr>
					<td>Expired Payments</td>
					<td style="text-align: right;"><?php echo $this->expiredPayments() ?></td>
					<td style="padding-left: 1em;">Accrues 1 year after an unused payment was made.</td>
				</tr>
				<tr>
					<td>Money Order Discounts</td>
					<td style="text-align: right;"><?php echo $this->moneyOrderDiscounts() ?></td>
					<td style="padding-left: 1em;">Accrues (actually, deducts from income)
            on the date the discount is created.</td>
				</tr>
			</tbody>
			<tfoot style="font-weight: bold;">
				<tr><td>Total</td><td style="text-align: right;">
            <a href="<?php echo A25_Link::to('/administrator/index2.php?option=com_stats&task=income' . $this->putAccrualDateIntoLink('accrual_date_from') . $this->putAccrualDateIntoLink('accrual_date_to'));?>">$<?php echo sprintf("%01.2f", $this->runningTotal) ?></a></td>
        </tr>
			</tfoot>
		</table>
  </form>
		<?php
	}

	/**
	 * @return collection of A25_Record_OrderItem
	 */
	protected function query($type_id)
	{
		return $this->accrualDateFilter->modifyQuery(Doctrine_Query::create()
			->from('A25_Record_OrderItem i')
      ->where('i.calc_accrual_date IS NOT NULL')
			->andWhere('i.type_id = ' . $type_id));
	}
	/**
	 * @return decimal
	 */
	private function moneyOrderDiscounts()
	{
		return $this->displayFee(A25_Record_OrderItemType::typeId_MoneyOrderDiscount);
	}
	/**
	 * @return decimal
	 */
	private function expiredPayments()
	{
		return $this->displayFee(A25_Record_OrderItemType::typeId_ExpiredPayment);
	}
	/**
	 * @return decimal
	 */
	private function noShowFees()
	{
		return $this->displayFee(A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows);
	}
	/**
	 * @return decimal
	 */
	private function creditCardFees()
	{
		return $this->displayFee(A25_Record_OrderItemType::typeId_CreditCardFee);
	}
	/**
	 * @return decimal
	 */
	private function returnedCheckFees()
	{
		return $this->displayFee(A25_Record_OrderItemType::typeId_ReturnCheckFee);
	}
	/**
	 * @return decimal
	 */
	private function replacementCertificateFees()
	{
		return $this->displayFee(A25_Record_OrderItemType::typeId_ReplaceCertFee);
	}
	/**
	 * @return decimal
	 */
	private function latePaymentFees()
	{
		return $this->displayFee(A25_Record_OrderItemType::typeId_LateFee);
	}
	/**
	 * @return decimal
	 */
	private function tuition()
	{
		return $this->displayFee(A25_Record_OrderItemType::typeId_CourseFee);
	}
  
  private function displayFee($type_id)
  {
		return $this->displaySubtotal($this->revenueTotal($type_id), $type_id);
  }

	private function displaySubtotal($total, $type_id)
	{
    $url = '/administrator/index2.php?option=com_stats&task=income&item_type_ids[]='
					. $type_id;
    
    $url .= $this->putAccrualDateIntoLink('accrual_date_to');
    
    $url .= $this->putAccrualDateIntoLink('accrual_date_from');
    
    return '<a href="' . A25_Link::to($url) . '">$' . $total . '</a>';
	}
  
  private function putAccrualDateIntoLink($field)
  {
    if (!empty($this->accrualDateFilter->$field))
      return "&$field=" . urlencode(date('m/d/Y',strtotime($this->accrualDateFilter->$field)));
  }
  
	/**
	 * @return decimal
	 */
	protected function revenueTotal($type_id)
	{
		$q = $this->query($type_id)
			->select('SUM(i.unit_price) as total');
		$payments = $q->fetchOne();
		$this->runningTotal += $payments->total;
		if ($payments->total)
			return $payments->total;
		return 0;
	}
}

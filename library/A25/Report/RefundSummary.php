<?php
class A25_Report_RefundSummary extends A25_Report
{
	protected $isExportable = false;
	private $runningTotal;

	protected function name()
	{
		return "Refund-By-Type";
	}

	protected function query() {}
	
	protected function formatRow(A25_DoctrineRecord $orderItem) {}
	
	/**
	 * @return collection of A25_Record_Pay
	 */
	protected function queryRefundByTypeAndItemCreationDate($type_id)
	{
		return Doctrine_Query::create()
			->from('A25_Record_Pay p')
			->innerJoin('p.Order o')
			->innerJoin('o.Enrollment e')
			->where('p.refund_type_id = ' . $type_id)
			->andWhere('p.created > ?', date('Y-m-d h:i:s', $this->filter->from))
			->andWhere('p.created < ?', date('Y-m-d h:i:s', $this->filter->to));
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
					<td style="padding-left: 1em;">Accounting Method</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Refunded Tuition</td>
					<td style="text-align: right;"><?php echo $this->createLinkByTypeId(A25_Record_OrderItemType::typeId_CourseFee) ?></td>
					<td style="padding-left: 1em;">By date the refund was created</td>
				</tr>
				<tr>
					<td>Refunded Late Payment Fees</td>
					<td style="text-align: right;"><?php echo $this->createLinkByTypeId(A25_Record_OrderItemType::typeId_LateFee); ?></td>
					<td style="padding-left: 1em;">By date the refund was created.</td>
				</tr>
				<tr>
					<td>Refunded Replacement Certificate Fees</td>
					<td style="text-align: right;"><?php echo $this->createLinkByTypeId(A25_Record_OrderItemType::typeId_ReplaceCertFee); ?></td>
					<td style="padding-left: 1em;">By date the refund was created.</td>
				</tr>
				<tr>
					<td>Refunded Returned Check (NSF) Fees</td>
					<td style="text-align: right;"><?php echo $this->createLinkByTypeId(A25_Record_OrderItemType::typeId_ReturnCheckFee) ?></td>
					<td style="padding-left: 1em;">By date the refund was created.</td>
				</tr>
				<tr>
					<td>Refunded Credit Card Fees</td>
					<td style="text-align: right;"><?php echo $this->createLinkByTypeId(A25_Record_OrderItemType::typeId_CreditCardFee) ?></td>
					<td style="padding-left: 1em;">By date the refund was created.</td>
				</tr>
				<tr>
					<td>Refunded Forfeited Tuition Due to No-Show</td>
					<td style="text-align: right;"><?php echo $this->createLinkByTypeId(A25_Record_OrderItemType::typeId_NonrefundableBecauseOfNoShows) ?></td>
					<td style="padding-left: 1em;">By date the refund was created.</td>
				</tr>
				<?php if (PlatformConfig::defaultCourtSurcharge > 0) { ?>
				<tr>
					<td>Refunded Court Surcharges</td>
					<td style="text-align: right;"><?php echo $this->createLinkByTypeId(A25_Record_OrderItemType::typeId_CourtSurcharge) ?></td>
					<td style="padding-left: 1em;">By date the refund was created.</td>
				</tr>
				<?php } ?>
				<tr>
					<td>Uncategorized Refunds</td>
					<td style="text-align: right;"><?php echo $this->createUnCategorizedLink() ?></td>
					<td style="padding-left: 1em;">By date the refund was created.</td>
				</tr>
			</tbody>
			<tfoot style="font-weight: bold;">
				<tr><td>Total Refunds</td><td style="text-align: right;">$<?php echo $this->runningTotal ?>.00</td></tr>
			</tfoot>
		</table>
		<?php
		$this->footer(false);
	}

	/**
	 * @return decimal
	 */
	private function createLinkByTypeId($type_id)
	{
		$total = $this->refundTotalByItemCreationDate($type_id);

		return $this->displaySubtotal($total, $type_id);
	}

	private function displaySubtotal($total, $type_id)
	{
		return '<a href="'
			. A25_Link::to('/administrator/index2.php?option=com_stats&task=refund&refund_type_ids[]='
					. $type_id . '&limitstart=0')
			. '">$' . $total . '</a>';
	}
	/**
	 * @return decimal
	 */
	protected function refundTotalByItemCreationDate($type_id)
	{
		$q = $this->queryRefundByTypeAndItemCreationDate($type_id)
			->select('SUM(p.amount) as total');
		$payments = $q->fetchOne();
		$this->runningTotal -= $payments->total;
		if ($payments->total)
			return -$payments->total;
		return 0;
	}

	private function createUnCategorizedLink()
	{
		$report = new A25_Report_Refund_Uncategorized($this->filter, $this->limit, 0);
		$total = $report->getTotal();
		$this->runningTotal += $total;

		return '<a href="'
			. A25_Link::to('/administrator/index2.php?option=com_stats&task=uncategorizedRefund&limitstart=0')
			. '">$' . $total . '</a>';
	}
}
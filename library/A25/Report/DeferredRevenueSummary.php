<?php

class A25_Report_DeferredRevenueSummary
{
	protected $isExportable = false;
	
	public function run()
	{
    $printer = new DeferredRevenueSummaryPrinter($this->deferredFeesTotal(),
        $this->accountBalancesTotal());
    $printer->output();
	}

	private function deferredFeesTotal()
	{
    return Doctrine_Query::create()
      ->select('SUM(i.unit_price) as total')
			->from('A25_Record_OrderItem i')
      ->andFeeIsDeferredRevenueForUpcomingCourse()
      ->fetchOne()->total;
	}

	private function accountBalancesTotal()
	{
		return Doctrine_Query::create()
      ->select('SUM(-s.calc_balance) as total')
			->from('A25_Record_Student s')
      ->where('s.calc_balance < 0')
      ->fetchOne()->total;
	}
}

class DeferredRevenueSummaryPrinter
{
  private $deferred_fee_total;
  private $student_balance_total;
  private $total;
  
  public function __construct($deferred_fee_total, $student_balance_total)
  {
    $this->deferred_fee_total = $deferred_fee_total;
    $this->student_balance_total = $student_balance_total;
    $this->total = $deferred_fee_total + $student_balance_total;
  }
  
  public function output()
  {
		$this->heading();
		?>
		<table style="clear: both; border: 1px solid black; padding: 1em;
			background-color: #F6F6F6;">
			<thead style="font-weight: bold;">
				<tr>
					<td style="width: 180px;">TYPE</td>
					<td style="text-align: right;">Subtotals</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Fees paid for upcoming courses</td>
					<td style="text-align: right;"><?php echo $this->deferredFeesLink() ?></td>
				</tr>
				<tr>
					<td>Unclaimed balances (expire after 1 year)</td>
					<td style="text-align: right;"><?php echo $this->accountBalancesLink() ?></td>
				</tr>
			</tbody>
			<tfoot style="font-weight: bold;">
				<tr><td>Total</td><td style="text-align: right;">$<?php echo sprintf("%01.2f", $this->total) ?></td></tr>
			</tfoot>
		</table>
  </form>
		<?php
  }
  
	protected function heading()
	{
		A25_DI::HtmlHead()->stylesheet('/templates/aliveat25/css/a25_filters_forReports.css');
		echo '<form action="deferred-revenue-summary" method="get" name="adminForm">';
		echo "<h1 style='text-align:left; color: #333;'>Deferred Revenue Summary</h1>";
    echo '<p style="font-style: italic; color: 666;">Click on a Total to drill down to specifics.</p>';
	}
  
  private function deferredFeesLink()
  {
    return '<a href="'
      . A25_Link::to('/administrator/index2.php?option=com_stats&task=upcoming_course_revenue')
      . '">$' . $this->deferred_fee_total . '</a>';
    
  }
  
  private function accountBalancesLink()
  {
    return '<a href="'
      . A25_Link::to('/administrator/index2.php?option=com_stats&task=student_balances')
      . '">$' . $this->student_balance_total . '</a>';
  }
}
<?php

class A25_Filter_AccountsReceivable extends A25_Filter
{
	protected $accounts_receivable;
  protected $as_of;

	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->accounts_receivable)
      if (empty($this->as_of))
			  $q->andWhere('i.date_paid IS NULL');
      else
        $q->andWhere('(i.date_paid IS NULL OR i.date_paid > ?)', date('Y-m-d',
					strtotime($this->as_of)));
    
    return $q;
	}
	
	protected function title()
	{
		return 'Accounts Receivable';
	}
	
	protected function field()
	{
    $html = '<input type="checkbox" name="accounts_receivable" value=1 ';
    
    if ($this->accounts_receivable)
      $html .= 'checked=checked ';
    
    $html .= '/><span style="font-weight: normal">only show unpaid fees</span>';
    
    $html .= $this->asOfDate();
    
    return $html;
	}
  
  /**
   * @todo-soon - remove duplication with A25_Filter_DateRange->smartField()
   */
  private function asOfDate()
  {
    $to_name = 'as_of';
    
    $htmlHead = A25_DI::HtmlHead();
    $htmlHead->includeJquery();
		// Setup for calendar javascript
    $htmlHead->stylesheet(
        '/includes/third-party/jquery-ui-1.8.16.custom/css/jquery-ui-1.8.16.custom.css');
    $htmlHead->javascriptFile('/includes/third-party/jquery-ui-1.8.16.custom/jquery-ui-1.8.16.custom.min.js');
    $htmlHead->append('
    <script type="text/javascript">
    jQuery(function() {
      $("#' . $to_name . '").datepicker();
    });
    </script>');
    
		return '
    <div class="date_range">As of:<br/>
			<input type="text" name="' . $to_name . '" id="' . $to_name . '" size="10" maxlength="10"
				value="' . $this->$to_name . '" />
		</div>';
  }
}
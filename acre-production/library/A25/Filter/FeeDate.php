<?php

/**
 * This class follows the "Test Strategy" for all A25_Filter classses, which is
 * defined in its docblock.
 */
class A25_Filter_FeeDate extends A25_Filter
{
	protected $fee_date_from;
	protected $fee_date_to;
	
	public function modifyQuery(Doctrine_Query $q)
	{
		if ($this->fee_date_from) {
			$q->andWhere('i.created >= ?', date('Y-m-d',
					strtotime($this->fee_date_from)));
		}
		if ($this->fee_date_to) {
			$q->andWhere('i.created < ?', A25_Functions::addADay($this->fee_date_to));
		}
		return $q;
	}
	
	protected function title()
	{
		return 'Fee Created';
	}
	
	/**
	 * @todo - refactor away duplication with A25_Filter_CourseDate::field(),
	 * and any other filters which have date fields like this.
	 */
	protected function field()
	{
    return '<div style="float: left; font-weight: bold; text-align: left; margin-left: 1em;">From:<br/>
			<input type="text" name="fee_date_from" id="fee_date_from" size="10" maxlength="10"
				value="' . $this->fee_date_from . '" />
			<input name="reset" type="reset" class="button" onclick=
				"return showCalendar(\'fee_date_from\', \'m/d/Y\');" value="..." />
    </div>
    <div style="float: left; font-weight: bold; text-align: left; margin-left: 1em;">To:<br/>
      <input type="text" name="fee_date_to" id="fee_date_to" size="10" maxlength="10"
        value="' . $this->fee_date_to . '" />
      <input name="reset" type="reset" class="button" onclick=
        "return showCalendar(\'fee_date_to\', \'m/d/Y\');" value="..." /><br/>
		</div>';
	}
}
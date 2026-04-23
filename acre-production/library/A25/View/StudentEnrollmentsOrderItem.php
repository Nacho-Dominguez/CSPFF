<?php
class A25_View_StudentEnrollmentsOrderItem extends A25_StrictObject
{
	private $item;

	public function __construct(A25_Record_OrderItem $item)
	{
		$this->item = $item;
	}

	public function run()
	{
		return '<tr>'
			. '<td style="vertical-align: top; text-align: right;" colspan="5">'
			. '<span style="font-weight: ; font-style: italic;">'
				. $this->item->getTypeName()
			. '</span></td>'
			. '<td style="vertical-align: top; text-align: center;">'
				. $this->value()
			. '</td>'
			. $this->actionButtons()
			. '</tr>';
	}

	protected function value()
	{
		$return = '($' . number_format($this->item->faceValue(), 2) . ')';
		if ($this->item->waived())
			$return = '<span style="text-decoration: line-through">' . $return
					. '</span>';
		return $return;
	}

	protected function actionButtons()
	{	
		return '<td></td><td></td>';
	}
}
?>

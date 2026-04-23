<?php
class A25_View_StudentEnrollmentsOrderItem_ForAdmins extends
		A25_View_StudentEnrollmentsOrderItem
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
		if ($this->item->waivedButUnconfirmed()) {
			return '<td style="vertical-align: top; text-align: center;">'
				. $this->descriptiveWaiveLinkWith('confirm',
						'Confirm student waiving of', 'confirm') . '</td>'
				. '<td>' . $this->waiveLinkWith('Unwaive', 'unwaive') . '</td>'
				. '<td></td>';
		} else {
			return '<td style="vertical-align: top; text-align: center;">'
					. $this->waiveLink() . '</td>'
					. '<td></td>';
		}
	}

	protected function editLink()
	{
		if (A25_DI::User()->isSuperAdmin())
			return '<a href="index2.php?option=com_student&task=editItemAmount&item_id='
					. $this->item->item_id
					. '"><span style="color: blue; font-weight: bolder;">edit</span></a>';
		else
			return '';
	}

	protected function waiveLink()
	{
		if($this->item->type_id == A25_Record_OrderItemType::typeId_CourseFee)
			$waiveLink = $this->editLink();
		else if (!$this->item->waived())
			$waiveLink = $this->waiveLinkWith('Waive', '&#8212;');
		else if ($this->item->waived())
			$waiveLink = $this->waiveLinkWith('Unwaive', '+');
		else
			$waiveLink = '';

		return $waiveLink;
	}

	protected function waiveLinkWith($waiveUnwaiveOrConfirm, $icon)
	{
		return $this->descriptiveWaiveLinkWith($waiveUnwaiveOrConfirm,
				$waiveUnwaiveOrConfirm, $icon);
	}

	protected function descriptiveWaiveLinkWith($action, $description, $icon)
	{
		$link = A25_Link::withJavascriptConfirmation(
			'index2.php?option=com_student&task=' . strtolower($action)
					. 'OrderItem' . '&item_id=' . $this->item->item_id,
			$description . ' ' . $this->item->getTypeName() . '?');

		return '<a href="' . $link . '">'
				. '<span style="color: blue; font-weight: bolder;">' . $icon
				. '</span></a>';
	}
}
?>

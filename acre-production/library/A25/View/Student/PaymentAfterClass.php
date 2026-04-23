<?php

class A25_View_Student_PaymentAfterClass
{
  public function render($student, $readOnly = true)
  {
		?>
		<div class="locationContent">
		<div id="colContent">
		<?php
    echo $this->contextMessage();

		$form = new A25_Form_Record_StudentAddressVerification($student,
			'after-late-payment',
			$readOnly);
		$form->run($_POST);
		?>
		If any of this information is incorrect, <a href="
		<?php echo A25_Link::withoutSef(
			'/after-late-payment?edit=1')
		?>
		">click here to edit</a>.
		</div></div>
		<?php
  }

  protected function contextMessage()
  {
    return '<p>Your Certificate of Completion will be mailed to you in 5-7 days.
      It will be mailed to this address:</p>';
  }
}

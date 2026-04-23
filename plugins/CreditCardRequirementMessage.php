<?php

class A25_Plugin_CreditCardRequirementMessage
		implements A25_ListenerI_AppendPaymentForm, \Acre\Listeners\PrePaymentPostInterface,
        A25_ListenerI_BottomOfPaymentViewScript
{

  public function appendPaymentForm()
  {
    return new A25_ElementMaker_EndOfCreditCardForm();
  }

  public function bottomOfPaymentViewScript($form)
  {
  ?>
		<tr>
			<td style="text-align: right;"></td>
			<td>
				<table style="border: 1px solid #BBBBBB; background-color: #efefef;
            font-size: 11px; color: #555; margin-bottom: 12px;">
				<tr valign="top">
					<td>
            <?php echo $form->element->requirement_message->render($form); ?>
					</td>
					<td>
            <?php echo PlatformConfig::creditCardRequirementMessage(); ?>
					</td>
				</tr>
				</table>
			</td>
		</tr>
  <?php
  }

  public function beforePaymentPosts($enroll)
  {
    if ($_POST['requirement_message'])
    {
      $enroll->Student->createCheckboxIfNecessary(PlatformConfig::creditCardRequirementMessage());
    }
  }
}

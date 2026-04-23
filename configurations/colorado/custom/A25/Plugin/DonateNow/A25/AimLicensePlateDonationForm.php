<?php

class A25_AimLicensePlateDonationForm extends A25_AimDonationForm
{
    protected function preFormContent()
    {
        echo '<div class="row">
      <div class="col-sm-5" style="padding: 32px;">
      <p>Show your support for driving education with the Alive at 25 Colorado
      Group Special Plates.</p>
      <img src="' . \A25_Link::to('/images/license_plate_large.png') . '" width="200px"
        alt="Alive at 25 License Plate" style="margin: 12px 0px;" />
      <h4>How to obtain Alive at 25 License Plates</h4>
      <p>Simply make a
      donation of at least $30 using the form on the right, print out the
      donation receipt, and bring it to the Colorado DMV, where they will issue
      the license plates.  (The DMV may issue fees that are standard for
      obtaining license plates.)</p>
      <p>For more information, visit the
      <a href="http://www.colorado.gov/cs/Satellite/Revenue-MV/RMV/1201542141645#alive">
      Colorado Department of Motor Vehicles.</a></p>
      </div>';
        echo '<div class="col-sm-7">';
    }

    protected function postFormContent()
    {
        echo '</div></div>';
    }

    protected function getElements()
    {
        $plate = new \A25_ElementMaker_LicensePlate();
        $credit = new \A25_ElementMaker_CreditCard();
        $submit = new \A25_ElementMaker_Submit();

        return array_merge(
            $plate->elements(),
            $credit->elements(),
            $submit->elements()
        );
    }
}

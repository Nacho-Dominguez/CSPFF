<?php

interface A25_ListenerI_LicenseInfo
{
	public function afterLicenseIssuingStateRegister();
	public function afterLicenseIssuingState(A25_Form_Record_Register $form);
	public function validateLicenseInfo(A25_Record_Student $student);
}

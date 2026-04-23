<?php

interface A25_ListenerI_PhoneNumbers
{
	public function registrationFormAfterEachPhoneNumber($name);
  
  public function studentFormAfterHomePhone(A25_Form_Record_Student $form);
  
  public function studentFormAfterWorkPhone(A25_Form_Record_Student $form);
  
  public function registrationFormAfterEachPhone(A25_Form_Record_Register $form, $name);
}

<?php

interface A25_ListenerI_AdminEnroll
{
	public function afterEnrollmentDate(A25_Record_Enroll $enroll);
  public function afterIsLateEdit(A25_Form_Record_Enroll $form);
  public function afterIsLateNew();
}

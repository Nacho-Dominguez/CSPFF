<?php

class A25_Factory_Online extends A25_Factory
{
  public function Account($student)
  {
    return new A25_View_Student_Account_Online($student);
  }
  
  public function BusinessRules()
  {
    return new A25_BusinessRules_Online();
  }
  
  public function ReasonForEnrollment()
  {
    return new A25_View_Student_ReasonForEnrollment_Online();
  }
}

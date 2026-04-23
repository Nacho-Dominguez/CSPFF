<?php

class A25_Factory_PhysicalLocation extends A25_Factory
{
  public function Account($student)
  {
    return new A25_View_Student_Account_PhysicalLocation($student);
  }
  
  public function BusinessRules()
  {
    return new A25_BusinessRules_PhysicalLocation();
  }
  
  public function ReasonForEnrollment()
  {
    return new A25_View_Student_ReasonForEnrollment_PhysicalLocation();
  }
}
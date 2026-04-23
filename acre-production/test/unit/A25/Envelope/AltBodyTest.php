<?php

class test_unit_A25_Envelope_AltBodyTest extends
		test_Framework_UnitTestCase
{
  /**
	 * @test
	 */
	public function fillsInBody()
	{
    $content = new EnvelopeWithAltBodyExposed(new EmailContentForTest());
    
    $this->assertEquals($this->expectedOutput(), $content->alt_body());
	}
  
  private function expectedOutput()
  {
    $date = date('Y-m-d');
    $output = <<<END
Subject goes here 
www.aliveat25.us
(720) 269-4046

Inner HTML goes here


END;
    return $output;
  }
}

class EnvelopeWithAltBodyExposed extends A25_Envelope
{
  public function alt_body() {
    return parent::alt_body();
  }
}
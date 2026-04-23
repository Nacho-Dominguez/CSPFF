<?php

class test_unit_A25_PageNav_CalculateStartPageTest extends
		test_Framework_UnitTestCase
{
  private $this_page;
  private $total_pages;
  
  /**
   * @test 
   */
  public function whenOnPageOne_returnsOne() {
    $this->this_page = 1;
    $this->total_pages = 99;
    
    $this->expect(1);
  }
  private function expect($expected)
  {
    $pageNav = new unit_CalculateStartPage_PageNav();
    $this->assertEquals($expected, $pageNav->calculateStartPage($this->this_page,
       $this->total_pages));
  }
  /**
   * @test 
   */
  public function whenOnPage6_returnsOne() {
    $this->this_page = 6;
    $this->total_pages = 99;
    
    $this->expect(1);
  }
  /**
   * @test 
   */
  public function whenOnPage7But11Total_returnsTwo() {
    $this->this_page = 7;
    $this->total_pages = 11;
    
    $this->expect(2);
  }
  /**
   * @test 
   */
  public function whenOnPage7ButOnly10Total_returnsOne() {
    $this->this_page = 7;
    $this->total_pages = 10;
    
    $this->expect(1);
  }
  /**
   * @test 
   */
  public function whenOnPage18ButOnly20Total_returns11() {
    $this->this_page = 18;
    $this->total_pages = 20;
    
    $this->expect(11);
  }
}

class unit_CalculateStartPage_PageNav extends A25_PageNav
{
  public function __construct()
  {
  }
	public function calculateStartPage($this_page, $total_pages) {
    return parent::calculateStartPage($this_page, $total_pages);
  }
}
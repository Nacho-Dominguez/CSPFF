<?php

class A25_StandardElementMaker
{
  private static $already_included_css = false;
  
  protected $elements;
  
  public function elements()
  {
    $this->addSharedElements();
    $this->decorateAllElements();
    $this->includeCssIfNecessary();
    
    return $this->elements;
  }
  
  protected function includeCssIfNecessary()
  {
    if (self::$already_included_css)
      return false;
    
    self::$already_included_css = true;
    
    $head = A25_DI::HtmlHead();
    $head->append('
      <style type="text/css">
          label {
            display: block;
          }
          .form_element {
            position:relative; /*this is the key*/
            margin-bottom: 12px;
          }
          .errors {
            background-color: #ffdddd;
            color: #aa3333;
            padding: 6px;
            margin-top: 1px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px #333;
          }
          .errors li {
            list-style: none;
            margin: 0; padding: 0;
          }
          .tooltip {
            display: inline-block;
            width: 344px;
          }
          .description {
            font-size: 12px;
          }
        </style>');
  }
  
  /**
   * This function should only be used for decorating with HTML, not for
   * styling the elements.
   */
  protected function decorateAllElements()
  {
    foreach ($this->elements as $element) {
      $element->setDecorators($this->customElementDecorators());
    }
  }
  
  private function customElementDecorators()
  {
    return array(
      'ViewHelper',
      'Label',
      'Errors',
      array(
        'Description',
        array('tag' => 'span','class' => 'description', 'placement' => 'append',
             'escape' => false)
      ),
      array(array('content' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form_element')),
    );
  }
}

<?php

class A25_Form_SendSmsMessage extends A25_BareForm
{
  public $successMessage = 'Text message sent successfully';
  private $course_id;
  
  public function __construct($course_id)
  {
    $this->course_id = $course_id;
    
    $message = new A25_Form_Element_Textarea('message');
    $message->setRequired(true);
    $message->addValidator('StringLength', false, array(1, 160, 'messages'
        => array('stringLengthTooLong' => 'The text message must be 160 characters or less.' )));
    $message->setAttrib('cols', 40);
    $message->setAttrib('rows', 4);
		$this->addElement($message);
    
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Send');
    $submit->setDescription('160 characters left');
		$this->addElement($submit);
    
    $this->styleElements();
    
    parent::__construct();
  }
  
  private function styleElements()
  {
    $this->setDecorators(array('FormElements','Form'));
    
    foreach($this->getElements() as $element)
      $element->setDecorators($this->customElementDecorators($element->getName()));
    
    $htmlHead = A25_DI::HtmlHead();
		
		$htmlHead->append('
		<style type="text/css" media="all">
      #message {
        font-size: 14px;
      }
      #submit-element {
        text-align: right;
      }
      #submit {
        font-size: 18px;
      }
      .description {
        font-size: 14px;
        color: green;
        margin-right: 6px; 
      }
    </style>');
    
    $htmlHead->includeJquery();
    
    $htmlHead->append('
    <script type="text/javascript">
    function updateCountdown() {
      // 160 is the max message length
      var remaining = 160 - jQuery("#message").val().length;
      if (remaining < 0)
        jQuery("#submit-description").css("color", "red");
      else
        jQuery("#submit-description").css("color", "green");
      
      jQuery("#submit-description").text(remaining + " characters left");
    }
    jQuery(document).ready(function($) {
      updateCountdown();
      $("#message").change(updateCountdown);
      $("#message").keyup(updateCountdown);
    });
    </script>
    ');
  }
  
  private function customElementDecorators($element_id)
  {
    return array(
      'ViewHelper',
      'Errors',
      array(
        'Description',
        array('tag' => 'span','class' => 'description', 'placement' => 'prepend',
             'id'  => $element_id . '-description')
      ),
      array(array('elementDiv' => 'HtmlTag'), array('tag' => 'div')),
      array(
        array('data' => 'HtmlTag'),
        array('tag' => 'div', 'id'  => $element_id . '-element')
      ),
    );
  }
  
  protected function redirect() {
    A25_DI::Redirector()->redirectBasedOnSiteRoot(
        '/administrator/index2.php?option=com_course&task=viewA&id=' . $this->course_id, 
        $this->successMessage);
  }
  
	protected function save()
	{
    $sms_to_course = new A25_Remind_SmsToCourse($this->course_id,
        $this->getElement('message')->getValue());
    
    $sms_to_course->send();
    
    return $this->successMessage;
	}
}

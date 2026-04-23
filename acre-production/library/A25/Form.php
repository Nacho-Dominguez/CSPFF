<?php

abstract class A25_Form extends A25_BareForm
{
	private $_redirectToQuerystring;

	public function __construct($redirectToQuerystring)
	{
    parent::__construct();
    
    /**
     * This CSS dealing with Zend_Form follows the 'Left Marginal' pattern from
     * _Pro CSS and HTML Design Patterns_
     */
    $head = A25_DI::HtmlHead();
    $head->append('
    <style>
      .zend_form {
          text-align: left;
      }

      .zend_form dt {
          font-size: smaller;
          margin-top: 5px;
          font-weight: bold;
      }

      .zend_form dd {
          margin-left: 0px;
      }

      .zend_form .description {
          color: gray;
          font-size: smaller;
          font-weight: bold;
          font-style: italic;
          margin-top: 0px;
      }

      .required {
        color:#c00;
      }
    </style>'
    );

		$this->_redirectToQuerystring = $redirectToQuerystring;
		
    $this->setName('ActiveRecordForm');
		$this->generateSaveButton();
	}
	
	protected function generateSaveButton()
	{
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Save');
		$this->addElement($submit);
	}
  
  /**
   * This is a generic function which could be used on any page to disable every
   * 'submit' button on the page when its form is submitted.  This prevents
   * problems such as students creating multiple enrollments when they get
   * impatient when the page is slow to load after clicking the first time.
   */
  public static function disableSubmitAfterSubmission()
  {
    A25_DI::HtmlHead()->includeJquery();
    A25_DI::HtmlHead()->append('
    <script type="text/javascript">
      jQuery(function() {
        $("form").submit(function() {
            $("input[type=submit]", this).attr("disabled", "disabled");
        });
      });
    </script>
    ');
  }

	protected function redirect()
	{
		A25_DI::Redirector()->changeQueryString($this->_redirectToQuerystring,
		    $this->successMessage);
	}
}
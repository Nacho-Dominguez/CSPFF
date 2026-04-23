<?php

class A25_HtmlHead extends A25_StrictObject
{
	private $text;
	private $jquery_already_included = false;
	private $jqueryUI_already_included = false;
	private $jqueryCookie_already_included = false;
  private $ckEditor_already_included = false;
	
	public function append($value)
	{
		$this->text .= "$value\n";
	}
	
	public function render()
	{
		return $this->text;
	}
	
	public function stylesheet($local_path)
	{
		$this->append('<link type="text/css" href="'
				. ServerConfig::currentUrl() . $local_path
				. '" rel="stylesheet" media="screen" />');
	}
	
	public function javascriptFile($local_path)
	{
		$this->append('<script type="text/javascript" src="'
				. ServerConfig::currentUrl() . $local_path . '"></script>');
	}
	
  /**
   * @todo-soon - move this to be like A25_Include_Broadcast
   */
	public function includeJquery()
	{
		if ($this->jquery_already_included)
			return;
		
		$this->text .= '<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>';
		
		$this->jquery_already_included = true;
	}
  
  /**
   * @todo-soon - move this to be like A25_Include_Broadcast
   */
	public function includeJqueryUI()
	{
		if ($this->jqueryUI_already_included)
			return;
		
    $this->includeJquery();
    
		$this->text .= '<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.0/jquery-ui.min.js"></script>';
		
		$this->jqueryUI_already_included = true;
	}
  
  public function includeJqueryCookie()
  {
		if ($this->jqueryCookie_already_included)
			return;
		
    $this->includeJquery();
		
		$this->text .= '<script type="text/javascript" src="' . A25_Link::to('includes/third-party/jquery-cookie/jquery.cookie.js') . '"></script>';
		
		$this->jqueryCookie_already_included = true;
	}
  
  public function includeCKEditor()
  {
		if ($this->ckEditor_already_included)
			return;
		
		$this->text .= '<script type="text/javascript" src="' . ServerConfig::currentUrl() . '/includes/third-party/ckeditor/ckeditor.js"></script>';
		
		$this->ckEditor_already_included = true;
  }
}
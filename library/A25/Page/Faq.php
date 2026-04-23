<?php

class A25_Page_Faq
{
  private $questions = '';
  private $answers = '';
  private $count = 1;
  
  private static function toTop()
  {
    return '[<a href="' . self::currentURI() . '#top">back to top</a>]<br />';
  }
  
  private static function currentURI()
  {
    return preg_replace('%#[^/]+$%','',$_SERVER['REQUEST_URI']);
  }
  
  private function newSection($section)
  {
    $this->questions .= '<h2>' . $section . '</h2>';
  }
  
  private function questionAnswer($question, $answer)
  {
    $this->questions .= '<a href="' . self::currentURI() . '#' . $this->count . '">' . $question . '</a><br />';
    $this->answers .= '<h3>' . $question . '<a name="' . $this->count . '" title="' . $this->count . '"></a></h3>
      <p>' . $answer . '</p>
      <p>' . self::toTop() . '</p>';
      $this->count++;
  }
  
  private function allQuestions()
  {
    include PlatformConfig::platformTemplateFilePath('faq.phtml');
  }
  
  public function display()
	{
		echo '
							<div class="colHeader">
				Frequently Asked Questions				</div>

										 	<div align="center">
		 			 	<!-- <img src="/images/headers/25.jpg" border="0" width="100" height="22" alt="25.jpg" /><br /> -->
		 	<div class="colHeaderPic"><img src="/images/headers/25.jpg" border="0" alt="25.jpg" style="width: 100%;"/><br /></div>
		 		 	</div>
	  									<div id="colContent">
				<a name="content"></a>
										<table border="0" cellspacing="0" cellpadding="0" class="contentpaneopen">
			<tr>
								<td class="contentheading" width="100%">

					Frequently Asked Questions<a name="top" title="top"></a>									</td>
							</tr>
			</table>

		<table border="0" cellspacing="0" cellpadding="0" class="contentpaneopen">
				<tr>
			<td valign="top" colspan="2">


The following frequently asked questions apply to all Alive at 25 programs. If you are looking for state-specific information regarding tuition rates or payment and registration policies, please <a href="' . PlatformConfig::programInfoUrl() . '">visit our program information section</a> and choose the state you live in.&nbsp;';
    $this->allQuestions();
    echo $this->questions;
    echo '
<p>
&nbsp;
</p>';
    echo $this->answers;
    echo '
			</td>
		</tr>
				</table>

		<span class="article_seperator">&nbsp;</span>

									</div>

';
	}
}
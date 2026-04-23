<?php
class A25_HtmlGenerationFunctions
{
	public static function adminForm($innerHtml)
	{
		return '<form action="index2.php" method="post" name="adminForm">'
			   . $innerHtml . '</form>';
    }
	public static function singleColumnRow($innerHtml, $attr=null)
	{
		$html = self::rowCell($innerHtml, $attr);
		return self::row($html);
    }
	public static function singleColumnHeader($innerHtml,$attr=null)
	{
		$html = self::headerCell($innerHtml, $attr);
		return self::row($html);
    }
	public static function row($innerhtml)
	{
		$html = '<tr>' . $innerhtml . '</tr>';
		return $html;
    }
	public static function arrayToRow($columns,$attrs=array())
	{
		for($i=0; $i<count($columns); $i+=1)
		{
			$html .= self::rowCell($columns[$i],$attrs[$i]);
        }
		return self::row($html);
    }
	public static function rowCell($innerHtml,$attr=null)
	{
		$html  = '<td';
		if($attr)
			$html .= ' ';
		$html .= $attr . '>' . $innerHtml . '</td>';
		return $html;
    }
	public static function headerCell($innerHtml,$attr=null)
	{
		$html  = '<th';
		if($attr)
			$html .= ' ';
		$html .= $attr . '>' . $innerHtml . '</th>';
		return $html;
    }
	public static function table($innerHtml,$attr=array())
	{
		$html = '<table';
		if($attr)
			$html .= ' ';
		$html .= $attr . '>' . $innerHtml . '</table>';
		return $html;
    }
	public static function tableWithOnlyHeading($heading,$tableAttr=array())
	{
		$html = self::headerCell($heading);
		$html = self::row($html);
		return self::table($html,$tableAttr);
    }
    public static function adminFormHeader($width)
    {
        $html = '<td width="'.$width.'%">
				<table class="adminform">';
        return $html;
    }
     public static function adminFormFooter()
     {
         $html = '</table>
			</td>';
         return $html;
     }
}
?>

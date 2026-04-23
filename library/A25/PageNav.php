<?php
class A25_PageNav
{
	private $total;
	private $limit = 20;
	private $start;
  const DISPLAYED_PAGES = 10;

	public function __construct($total, $limitstart)
	{
		$this->total = intval($total);
		$this->start = max($limitstart, 0);
		
		if ($this->limit > $this->total) {
			$this->start = 0;
		}
		if (($this->limit-1)*$this->start > $this->total) {
			$this->start -= $this->start % $this->limit;
		}
		
		A25_DI::HtmlHead()->stylesheet('/templates/aliveat25/css/a25_pagenav.css');
	}

	public function getPagesLinks()
	{
		$html 				= '';
		$total_pages 		= ceil( $this->total / $this->limit );

		if ($total_pages < 2)
			return;

		$this_page 			= ceil( ($this->start+1) / $this->limit );

		$start_loop 		= $this->calculateStartPage($this_page, $total_pages);
    $stop_loop = $this->calculateEndPage($start_loop, $total_pages);

		if ($this_page > 1) {
			$page = ($this_page - 2) * $this->limit;
			$html .= self::link($page, 'Previous');
		}

		for ($i=$start_loop; $i <= $stop_loop; $i++) {
			$page = ($i - 1) * $this->limit;
			if ($i == $this_page) {
				$html .= "\n<span class='pagenav'>$i</span>";
			} else {
				$html .= self::link($page, "$i");
			}
		}

		if ($this_page < $total_pages) {
			$page = $this_page * $this->limit;
			$end_page = ($total_pages-1) * $this->limit;
			$html .= self::link($page, 'Next');
		}
		return $html;
	}
  protected function calculateStartPage($this_page, $total_pages)
  {
    if ($total_pages - $this_page < 4)
      return max(array($total_pages - 9, 1));
    
    if ($this_page > 6)
      return $this_page - 5;
    
    return (floor(($this_page-1)/self::DISPLAYED_PAGES))*self::DISPLAYED_PAGES+1;
  }
  protected function calculateEndPage($start_loop, $total_pages)
  {
		if ($start_loop + self::DISPLAYED_PAGES - 1 < $total_pages)
			return $start_loop + self::DISPLAYED_PAGES - 1;
		else
			return $total_pages;
  }
	private static function link ($page, $text)
	{
		return "\n<a href='" . self::linkStartingAt($page)
						. "' class='pagenav'>$text</a>";
	}
	protected static function linkStartingAt($start)
	{
		$link = $_SERVER['REQUEST_URI'];
		
		if (preg_match('/(\?|&)start=/', $link))
			$link = preg_replace('/(?<=(?:\?|&)start=)\d*/', $start, $link);
		else if (strpos($link, '?'))
			$link .= "&start=$start";
		else
			$link .= "?start=$start";

		return A25_Link::encodeAmpersands($link);
	}
}
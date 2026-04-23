<?php
abstract class A25_Listing
{
	protected $total;
	protected $limit;
	protected $offset;
  protected $item_name = 'results';
	
	/**
	 * @var array A25_Filter
	 */
	protected $filters;

	public function __construct($limit = 20, $offset = 0)
	{
		if ($limit == 0 || $limit == null)
			$limit = 20;
		if ($offset == null)
			$offset = 0;

		$this->limit = $limit;
		$this->offset = $offset;
		
		A25_DI::HtmlHead()->stylesheet('/templates/aliveat25/css/a25_listing.css');
	}

	abstract protected function formatRow(A25_DoctrineRecord $record);

	/**
	 * @return Doctrine_Query
	 */
	abstract protected function query();
	
	protected function queryWithFilters()
	{
		
		$q = $this->query();
		
		if ($this->filters) {
			foreach ($this->filters as $filter) {
				$q = $filter->modifyQuery($q);
			}
		}
		
		return $q;
	}
	
	public function run()
	{
		$records = $this->queryWithLimits()->execute();

		$rows = $this->formatRecords($records);

		$this->total = $this->queryWithFilters()->count();

		$grid = $this->grid($rows);

		$this->heading();
		echo $grid->generate();
		$this->footer();
	}

	/**
	 * @param array $rows
	 * @return A25_Grid
	 */
	protected function grid($rows)
	{
		return new A25_Grid($rows);
	}

	protected function formatRecords($records)
	{
		$rows = array();
		foreach ($records as $record) {
			$rows[] = $this->formatRow($record);
		}
		return $rows;
	}

	protected function queryWithLimits()
	{
		return $this->queryWithFilters()
			->limit($this->limit)
			->offset($this->offset);
	}
	
	protected function heading()
	{
		echo '<form action="find-a-course" method="get" name="adminForm">';
	}
  
  protected function displayNavStatus()
  {
		if ($this->offset < 20)
			echo "<p class='result_stats'>Found $this->total $this->item_name</p>";
		else {
			$this_page = ceil( ($this->offset+1) / $this->limit );
			echo "<p class='result_stats'>Page $this_page of $this->total $this->item_name</p>";
		}
  }

	protected function footer($showPageNav = true)
	{
		$pageNav = new A25_PageNav($this->total, $this->offset);
		echo '<div class="pagenav_list">';
		echo $pageNav->getPagesLinks();
		echo '</div>';
		echo '</form>';
	}
}
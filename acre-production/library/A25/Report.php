<?php
abstract class A25_Report extends A25_Listing
{
	/**
	 * @todo
	 * Once all reports have been re-written and are using A25_Filter_CourseDate
	 * instead of $this->filter, remove $filter and $isLegacy.
	 * 
	 * @var A25_ReportFilter
	 */
	protected $filter;
	
	/**
	 * @var A25_Filter array
	 */
	protected $filters = array();
	
	/**
	 * @var bool
	 */
	protected $isExportable = true;
	
	/**
	 * @todo
	 * Once all reports have been re-written and are using A25_Filter_CourseDate
	 * instead of $this->filter, remove $isLegacy from here and all subclasses.
	 * Also, remove $this->filter.
	 * 
	 * @var bool
	 */
	protected $isLegacy = true;

	/**
	 * This is the legacy version of the constructor.  Once no more reports are
	 * using A25_ReportFilter, remove this version, leaving only the parent
	 * constructor.
	 * 
	 * @param A25_ReportFilter $filter
	 * @param type $limit
	 * @param type $offset 
	 */
	public function __construct($filter, $limit, $offset)
	{
		$this->filter = $filter;
		parent::__construct($limit, $offset);
    
		A25_DI::HtmlHead()->stylesheet('/templates/aliveat25/css/a25_filters_forReports.css');
	}
  
  abstract protected function name();

	public function exportToExcel()
	{
		$export_file = "/tmp/" . date("mdY") . "_report.csv";
		$fp = fopen($export_file, "wb");
		if (!is_resource($fp))
		{
			die("Cannot open $export_file");
		}

    $count = $this->queryWithFilters()->count();
    $this->limit = 1000;
    $this->offset = 0;
    while ($this->offset < $count) {
      $records = $this->queryWithLimits()->execute();
      
      $rows = $this->removeLinksFromData($this->formatRecords($records));
        foreach ($rows as $row) {
          if(!$flag) {
            $this->fputcsv_eol($fp, array_keys($row));
            $flag = true;
          }
          $this->fputcsv_eol($fp, array_values($row));
        }

      $this->offset += $this->limit;
      
      $records = null;
      unset($records);
      Doctrine_Manager::getInstance()->reset();
      bootstrapDoctrine();
    }
    
		fclose($fp);

		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: maxage=1"); //In seconds
		header ("Pragma: public");
		// The old code had these 2 lines instead.  IE has trouble with these lines
		// over SSL.
		//	header ("Cache-Control: no-cache, must-revalidate");
		//	header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=\"" . basename($export_file) . "\"" );
		header ("Content-Description: PHP/INTERBASE Generated Data" );

		readfile($export_file);
		exit;
	}
  
  private function fputcsv_eol($fp, $array, $eol = "\r\n") {
    fputcsv($fp, $array);
    if("\n" != $eol && 0 === fseek($fp, -1, SEEK_CUR)) {
      fwrite($fp, $eol);
    }
  }

	private function removeLinksFromData($rows)
	{
		$i = 0;
		foreach ($rows as $row) {
			foreach ($row as $key => $value)
			{
				$row[$key] = preg_replace('/<[^>]+>/','',$value);
			}
			$rows[$i] = $row;
			$i++;
		}
		return $rows;
	}

	/**
	 * This function is empty by default, but subclasses can override it in
	 * order to add extra fields to filter with.
	 * 
	 * @deprecated - We are moving towards using sublcasses of A25_Filter
	 * instead.  Once all reports have been updated to use A25_Filter, we should
	 * delete this function and its uses.
	 */
	protected function extraFilters()
	{}

	protected function heading()
	{
		?>
		<form action="index2.php" method="get" name="adminForm">
		<h1 style="background: url(images/generic.png) no-repeat left;
			text-align: left;
			padding: 12px;
			width: 99%;
			padding-left: 50px;
			border-bottom: 5px solid #fff;
			color: #C64934;
			font-size: 18px;">
			<?php echo $this->name() ?> Report
		</h1>
		<?php
    $this->filters();
	}

	protected function footer($showPageNav = true)
	{
		if ($showPageNav) {
			require_once(ServerConfig::webRoot .
				'/administrator/includes/pageNavigation.php');
			$pageNav = new mosPageNav($this->total, $this->offset, $this->limit);
			echo $pageNav->getListFooter();
		}
		echo '</form>';
	}

	protected function filters()
	{
		// @todo - instead of using the deprecated joomla calendar, use jQuery
		// UI DatePicker instead.  An example is in the Course Edit form.
		mosCommonHTML::loadCalendar();
		
    if ($this->isLegacy) {
		?>
      <div style="float: left; font-weight: bold; text-align: left; margin-left: 1em;">From:<br/>
				<input type="text" name="f_from" id="f_from" size="10" maxlength="10"
					value="<?php echo date('m/d/Y',$this->filter->from); ?>" />
				<input name="reset" type="reset" class="button" onclick=
					"return showCalendar('f_from', 'm/d/Y');" value="..." />
			</div>
      <div style="float: left; font-weight: bold; text-align: left; margin-left: 1em;">To:<br/>
        <input type="text" name="f_to" id="f_to" size="10" maxlength="10"
          value="<?php echo date('m/d/Y',$this->filter->to); ?>" />
        <input name="reset" type="reset" class="button" onclick=
          "return showCalendar('f_to', 'm/d/Y');" value="..." /><br/>
      </div>
		<?php
		}

		$this->extraFilters();
		
		foreach ($this->filters as $filter) {
			echo $filter->htmlFormElement();
		}

		?>
    <div style="float: left; clear: left; margin: 12px 0px;">
			<input type="submit" onClick="this.form.limitstart.value=0"
				   value="Update Statistics" />
		<?php if ($this->isExportable) { ?>
				<input type="submit" onClick="this.form.action='<?php
					echo A25_Link::to('/administrator/admin.stats.xls.php')?>'"
					value="Export to Excel" />
		<?php } ?>
    </div>
		<input type="hidden" name="option" value="com_stats" />
		<input type="hidden" name="task" value="<?php echo $_REQUEST['task'] ?>" />
		<?php
	}
}

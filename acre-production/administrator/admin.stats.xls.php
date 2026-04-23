<?php
/**
* @version $Id$
* @author Christiaan van Woudenberg
* @copyright (C) Velocera Engineering, LLC, www.velocera.com
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// Set flag that this is a parent file
define( '_VALID_MOS', 1 );

global $mosConfig_absolute_path, $mosConfig_live_site;

stream_wrapper_register("xlsfile", "xlsStream") or die("Failed to register protocol: xlsfile");

include_once( '../globals.php' );
require_once( '../configuration.php' );
require_once( $mosConfig_absolute_path . '/includes/joomla.php' );

// must start the session before we create the mainframe object
session_name( md5( $mosConfig_live_site ) );
session_start();

$mainframe = new mosMainFrame( $database, null, '..', true );
$mainframe->initSession();

/* Init $my session variable with automatic lockout/redirection */
$my = $mainframe->initSessionAdmin( '', '' );

require_once( $mosConfig_absolute_path . '/administrator/components/com_stats/stats.class.php' );
require_once( $mosConfig_absolute_path . '/administrator/components/com_stats/admin.stats.html.php' );
require_once( $mosConfig_absolute_path . '/administrator/components/com_stats/stats.config.php' );

// Get list of administerable locations for the current user.
$locs = A25_Record_Location::getLocs();

if (empty($locs)) {
	mosRedirect( 'index2.php', 'You are not authorized to download statistics.' );
}

$task = trim( mosGetParam( $_REQUEST, 'task', null ) );

$filter = new A25_ReportFilter();
$filter->from = $mainframe->getUserStateFromRequest( "f_from{$option}", 'f_from', null );
$filter->to = $mainframe->getUserStateFromRequest( "f_to{$option}", 'f_to', null );
$filter->lid = (int) $mainframe->getUserStateFromRequest( "f_lid{$option}", 'f_lid', null );
$filter->per = (boolean) $mainframe->getUserStateFromRequest( "f_per{$option}", 'f_per', 1 );

if (!($filter->from && $filter->to)) {
	$filter->from = mktime(0, 0, 0, date("m"), 1, date("Y"));
	$filter->to = mktime(23, 59, 59, date("m"), date("d"), date("Y"));
} else {
	$filter->from = strtotime($filter->from);
	//add in almost a full day
	$filter->to = strtotime($filter->to) + 86399;
}

switch ($task) {

	case "completedbycourt":
		$report = new A25_Report_CompletedByCourt($filter, $limit, $limitstart);
		$report->exportToExcel();
		break;

	case "course":
		courseStats( $filter );
		break;

	case "courses":
		$report = new A25_Report_Course($limit, $limitstart);
		$report->exportToExcel();
		break;

	case "court":
		$report = new A25_Report_Court($filter, $limit, $limitstart);
		$report->exportToExcel();
		break;

	case "courtSurchargeCollected":
		$report = new A25_Report_CollectedCourtSurcharges($filter, $limit, $limitstart);
		$report->exportToExcel();
		break;

	case "dmv":
		$report = new A25_Report_Dmv($filter, $limit, $limitstart);
		$report->exportToExcel();
		break;

	case "enrollment":
		$report = new A25_Report_Enrollment($limit, $limitstart);
		$report->exportToExcel();
		break;

	case 'instructor':
		instructorStats( $filter );
		break;

	case "location":
		locationStats( $filter );
		break;

	case "losing":
		losingStats( $filter );
		break;

	case "payment":
		paymentStats( $filter );
		break;

	case "creditTypeStats":
		$filter->credit_type = $mainframe->getUserStateFromRequest( "filter_credit_type{$option}", 'filter_credit_type', null );
		creditTypeStats ( $filter );
		break;

	case "fees":
		$report = new A25_Report_Fee($filter, $limit, $limitstart);
		$report->exportToExcel();
		break;

	case "refund":
		$report = new A25_Report_Refund($filter, $limit, $limitstart);
		$report->exportToExcel();
		break;

	case "uncategorizedRefund":
        $report = new A25_Report_Refund_Uncategorized($filter, $limit, $limitstart);
		$report->exportToExcel();
		break;

  case "income":
    if (!A25_DI::User()->isAdminOrHigher()) {
      echo 'Sorry, your account is not allowed to access this page.';
      exit();
    }

		$report = new A25_Report_Income($limit, $limitstart);
		$report->exportToExcel();
    break;
    
  case "upcoming_course_revenue":
    if (!A25_DI::User()->isAdminOrHigher()) {
      echo 'Sorry, your account is not allowed to access this page.';
      exit();
    }

		$report = new A25_Report_UpcomingCourseRevenue($limit, $limitstart);
		$report->exportToExcel();
    break;
    
  case "student_balances":
    if (!A25_DI::User()->isAdminOrHigher()) {
      echo 'Sorry, your account is not allowed to access this page.';
      exit();
    }

		$report = new A25_Report_StudentBalances($limit, $limitstart);
		$report->exportToExcel();
    break;

	case "cpanel":
	default:
		$redirector = new A25_Redirector();
		$redirector->redirect('reports', '', 301);
		break;
}


/**
 * Saves course revenue statistics as an XLS file.
 * @author Christiaan van Woudenberg
 * @version September 11, 2006
 *
 * @return void
 */
function courseStats( $filter ) {
	global $my, $locs;

	$stats = new courseStats( $filter, $locs );
	$stats->load(A25_DI::DB(),'export');

	//print_r($stats->data); die();

	$export_file = "xlsfile://tmp/" . date("mdY") . "_{$my->id}_coursestats.xls";
	$fp = fopen($export_file, "wb");
	if (!is_resource($fp))
	{
		die("Cannot open $export_file");
	}

	fwrite($fp, serialize($stats->data));
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

/**
 * Saves payment report as an XLS file.
 * @author Garey Hoffman
 * @version September 10, 2006
 *
 * @return void
 */
function paymentStats( $filter ) {
	global $my, $locs;

	$stats = new paymentStats( $filter, $locs );
	$stats->load('export');

	//print_r($stats->data); die();

	$export_file = "xlsfile://tmp/" . date("mdY") . "_{$my->id}_paymentreport.xls";
	$fp = fopen($export_file, "wb");
	if (!is_resource($fp))
	{
		die("Cannot open $export_file");
	}

	fwrite($fp, serialize($stats->data));
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

/**
 * Saves 'Credit Type Usage Report' as an XLS file.
 *
 * @author Thomas Albright
 * @version LastChangedRevision 32, 2007-12-27
 */
function creditTypeStats ( $filter ) {
	global $my, $locs;


	$stats = new creditTypeStats ( $filter, $locs );
	$stats->load('export');

	//print_r($stats->data); die();

	$export_file = "xlsfile://tmp/" . date("mdY") . "_{$my->id}_CreditTypeUsageReport.xls";
	$fp = fopen($export_file, "wb");
	if (!is_resource($fp))
	{
		die("Cannot open $export_file");
	}

	fwrite($fp, serialize($stats->data));
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

/**
 * Saves losing coursesreport as an XLS file.
 * @author Christiaan van Woudenberg
 * @version September 10, 2006
 *
 * @return void
 */
function losingStats( $filter ) {
	global $my, $locs;

	$stats = new losingStats( $filter, $locs );
	$stats->load('export');

	//print_r($stats->data); die();

	$export_file = "xlsfile://tmp/" . date("mdY") . "_{$my->id}_losingreport.xls";
	$fp = fopen($export_file, "wb");
	if (!is_resource($fp))
	{
		die("Cannot open $export_file");
	}

	fwrite($fp, serialize($stats->data));
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


/**************************************************************************/

/**
 * MS-Excel stream handler
 * This class read/writes a data stream directly
 * from/to a Microsoft Excel spreadsheet
 * opened with the xlsfile:// protocol
 * This is used to export associative array data directly to MS-Excel
 * @requires	PHP 4 >= 4.3.2
 * @author	  Ignatius Teo			<ignatius@act28.com>
 * @copyright   (C)2004 act28.com	   <http://act28.com>
 * @version	 0.3
 * @date		20 Jan 2005
 * $Id: excel.php,v 1.3 2005/01/20 09:58:58 Owner Exp $
 */
class xlsStream
{
	/* private */
	var $position = 0;		  // stream pointer
	var $mode = "rb";		   // default stream open mode
	var $xlsfilename = null;	// stream name
	var $fp = null;			 // internal stream pointer to physical file
	var $buffer = null;		 // internal write buffer
	var $endian = "unknown";	// little | unknown | big endian mode
	var $bin = array(
		"big" => "v",
		"little" => "s",
		"unknown" => "s",
	);

	/**
	 * detect server endian mode
	 * thanks to Charles Turner for picking this one up
	 * @access	private
	 * @params	void
	 * @returns	void
	 * @see		http://www.phpdig.net/ref/rn45re877.html
	 */
	function _detect()
	{
		// A hex number that may represent 'abyz'
		$abyz = 0x6162797A;

		// Convert $abyz to a binary string containing 32 bits
		// Do the conversion the way that the system architecture wants to
		switch (pack ('L', $abyz))
		{
			// Compare the value to the same value converted in a Little-Endian fashion
			case pack ('V', $abyz):
				$this->endian = "little";
				break;

			// Compare the value to the same value converted in a Big-Endian fashion
			case pack ('N', $abyz):
				$this->endian = "big";
				break;

			default:
				$this->endian = "unknown";
				break;
		}
	}

	/**
	 * called by fopen() to the stream
	 * @param   (string)	$path		   file path
	 * @param   (string)	$mode		   stream open mode
	 * @param   (int)	   $options		stream options (STREAM_USE_PATH |
	 *									  STREAM_REPORT_ERRORS)
	 * @param   (string)	$opened_path	stream opened path
	 */
	function stream_open($path, $mode, $options, &$opened_path)
	{
		$url = parse_url($path);
		$this->xlsfilename = '/' . $url['host'] . $url['path'];
		$this->position = 0;
		$this->mode = $mode;

		$this->_detect();	// detect endian mode

		// open underlying resource
		$this->fp = @fopen($this->xlsfilename, $this->mode);
		if (is_resource($this->fp))
		{
			// empty the buffer
			$this->buffer = "";

			if (preg_match("/^w|x/", $this->mode))
			{
				// write an Excel stream header
				$str = pack(str_repeat($this->bin[$this->endian], 6), 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
				fwrite($this->fp, $str);
				$opened_path = $this->xlsfilename;
				$this->position = strlen($str);
			}
		}
		return is_resource($this->fp);
	}

	/**
	 * read the underlying stream resource (automatically called by fread/fgets)
	 * @param   (int)	   $byte_count	 number of bytes to read (in 8192 byte blocks)
	 */
	function stream_read($byte_count)
	{
		if (is_resource($this->fp) && !feof($this->fp))
		{
			$data .= fread($this->fp, $byte_count);
			$this->position = strlen($data);
		}
		return $data;
	}

	/**
	 * called automatically by an fwrite() to the stream
	 * @param   (string)	$data		   serialized array data string
	 *									  representing a tabular worksheet
	 */
	function stream_write($data)
	{
		// buffer the data
		$this->buffer .= $data;
		$bufsize = strlen($data);
		return $bufsize;
	}

	/**
	 * pseudo write function to manipulate the data
	 * stream before writing it
	 * modify this to suit your data array
	 * @access  private
	 * @param   (array)	 $data		   associative array representing
	 *									  a tabular worksheet
	 */
	function _xls_stream_write($data)
	{
		if (is_array($data) && !empty($data))
		{
			$row = 0;
			foreach (array_values($data) as $_data)
			{
				if (is_array($_data) && !empty($_data))
				{
					if ($row == 0)
					{
						// write the column headers
						foreach (array_keys($_data) as $col => $val)
						{
							// next line intentionally commented out
							// since we don't want a warning about the
							// extra bytes written
							// $size += $this->write($row, $col, $val);
							if (strpos($col,'_ignore') === false)
							{
								$this->_xlsWriteCell($row, $col, $val);
							}
						}
						$row++;
					}

					foreach (array_values($_data) as $col => $val)
					{
						if (strpos($col,'_ignore') === false)
						{
							$size += $this->_xlsWriteCell($row, $col, $val);
						}
					}
					$row++;
				}
			}
		}
		return $size;
	}

	/**
	 * Excel worksheet cell insertion
	 * (single-worksheet supported only)
	 * @access  private
	 * @param   (int)	   $row			worksheet row number (0...65536)
	 * @param   (int)	   $col			worksheet column number (0..255)
	 * @param   (mixed)	 $val			worksheet row number
	 */
	function _xlsWriteCell($row, $col, $val)
	{
		if (is_float($val) || is_int($val) || (is_numeric($val) && strpos($val,'$') === false))
		{
			// doubles, floats, integers
			$str  = pack(str_repeat($this->bin[$this->endian], 5), 0x203, 14, $row, $col, 0x0);
			$str .= pack("d", $val);
		}
		else
		{
			// everything else is treated as a string
			$l	= strlen($val);
			$str  = pack(str_repeat($this->bin[$this->endian], 6), 0x204, 8 + $l, $row, $col, 0x0, $l);
			$str .= $val;
		}
		fwrite($this->fp, $str);
		$this->position += strlen($str);
		return strlen($str);
	}

	/**
	 * called by an fclose() on the stream
	 */
	function stream_close()
	{
		if (preg_match("/^w|x/", $this->mode))
		{
			// flush the buffer
			$bufsize = $this->_xls_stream_write(unserialize($this->buffer));

			// ...and empty it
			$this->buffer = null;

			// write the xls EOF
			$str = pack(str_repeat($this->bin[$this->endian], 2), 0x0A, 0x00);
			$this->position += strlen($str);
			fwrite($this->fp, $str);
		}

		// ...and close the internal stream
		return fclose($this->fp);
	}

	function stream_eof()
	{
		$eof = true;
		if (is_resource($this->fp))
		{
			$eof = feof($this->fp);
		}
		return $eof;
	}
}
?>

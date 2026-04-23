<?php

class Controller_Administrator_Resources extends Controller
{
	public function executeTask()
	{
    A25_Allow::everyoneExceptCourtAdmin();
    
    // This variable is used in Resources.phtml
    $resources = A25_Query::create()
            ->select()->from('A25_Record_Resource')->execute();
    
		require dirname(__FILE__) . '/Resources.phtml';
	}
  
  /**
   * Although not used in this file, it is used by the included Resources.phtml
   * In the 'link' case, $filename should be the link path
   */
  private function htmlForDocument($title, $filename, $filetype)
  {
    $path = $this->linkToPdf(rawurlencode($filename));
    switch ($filetype) {
      case 'pdf':
        $icon = '/administrator/images/pdficon.png';
        $pdfview = '<a href="' . $this->linkToViewPdf($path) . '">View in browser</a> | ';
        break;
      case 'ppt':
        $icon = '/administrator/images/powerpoint.png';
        break;
      case 'link':
        $icon = '/administrator/images/copy_f2.png';
        $path = $filename;
        break;

      default:
        $icon = '/administrator/images/copy_f2.png';
        break;
    }
    ?>
    <tr>
      <td style="padding-top: 12px;">
        <a href="<?php echo $path?>">
          <img src="<?php echo A25_Link::to($icon)?>" style="vertical-align: middle" />
        </a>
      </td>
      <td style="padding-left: 6px; padding-top: 12px;">
        <div style="font-size: 16px;">
        <?php echo $title ?>
        </div>
        <div>
          <?php echo $pdfview ?>
          <a href="<?php echo $path?>">Download</a>
        </div>
      </td>
    </tr>
    <?php
  }
  
  private function linkToPdf($filename)
  {
    return A25_Link::to(
        '/images/resources/' . $filename);
  }

  private function linkToViewPdf($path)
  {
    return A25_Link::to('/administrator/view-pdf?uri=' . urlencode($path));
  }
}

set_include_path(
	ServerConfig::webRoot . '/plugins/Resources' . PATH_SEPARATOR
	. get_include_path()
);


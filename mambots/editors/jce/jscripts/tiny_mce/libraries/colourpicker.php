<?php 
// ================================================
// PHP image manager - iManager 
// ================================================
// iManager dialog -color picker
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: GPL - see readme.txt
// (c)2005 All rights reserved.
// ================================================
// Revision: 1.0                   Date: 2005/03/25
// ================================================


    global $mosConfig_absolute_path, $mosConfig_live_site;
    $tiny_url = $mosConfig_live_site."/mambots/editors/jce/jscripts/tiny_mce";
    $tiny_path = $mosConfig_absolute_path."/mambots/editors/jce/jscripts/tiny_mce";
    $lib_path = $tiny_path."/libraries";
    $lib_url = $tiny_url."/libraries";

    $func = mosGetParam( $_REQUEST, 'val', '' );
    $block = mosGetParam( $_REQUEST, 'block', '' );
    $cur_colour = mosGetParam( $_REQUEST, 'colour', '' );

    //-------------------------------------------------------------------------
	// color picker variables
		$rows = 7;
		$cols = 7;
		$cellsize = 10;
		$cellspacing = 1;
		$cellpadding = 0;
		$maxwidth = 250;
		$totalsegments = 6; // one for grayscale, the rest for colors
	// end color picker variables	
	//-------------------------------------------------------------------------
	
	function RGB2hex( $r, $g, $b ) {
		$RGB2hex  = str_pad(dechex(round($r)), 2, '0', STR_PAD_LEFT);
		$RGB2hex .= str_pad(dechex(round($g)), 2, '0', STR_PAD_LEFT);
		$RGB2hex .= str_pad(dechex(round($b)), 2, '0', STR_PAD_LEFT);
		return $RGB2hex;
	}	
	function CheckMaxWidthBR( &$currentwidth, $maxwidth, $cols, $cellsize, $cellpadding, $cellspacing ) {
		$currentwidth += $cols * ( $cellsize + ( $cellpadding + $cellspacing * 2 ));
		if ( $currentwidth >= $maxwidth ) {
			echo '<br clear="all">';
			$currentwidth = 0;
		}
		return true;
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Colour Picker</title>
<meta http-equiv="Pragma" content="no-cache">
    <link href="<?php echo $lib_url;?>/css/picker.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $tiny_url;?>/themes/advanced/css/editor_popup.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
        var cur_color = "<?php echo $cur_colour;?>";
        var func = "<?php echo $func;?>";
        var block = "<?php echo $block;?>";
        
        function init() {
            if ( cur_color != '' ) {
                document.getElementById( 'color' ).value = cur_color;
                document.getElementById( 'ncol' ).bgColor = cur_color;
                document.getElementById( 'ccol' ).bgColor = cur_color;
            }
        }
        function okClick() {
            window.opener.document.getElementById(func).value = document.getElementById( 'color' ).value;
            window.opener.document.getElementById(block).style.background = '#' + document.getElementById( 'color' ).value;
            window.close();
        }
        function cancelClick() {
            window.close();
        }
        function imgOn(imgid) {
            document.getElementById( 'ccol' ).bgColor = '#' + imgid.id.substring(3);
        }
        function selColor(colorcode) {
            document.getElementById( 'ncol' ).bgColor = '#' + colorcode;
            document.getElementById( 'ccol' ).bgColor = '#' + colorcode;
            document.getElementById( 'color' ).value  = colorcode;
        }
        function setColor() {
            document.getElementById( 'ncol' ).bgColor = document.getElementById( 'color' ).value;
        }
    </script>
</head>
<body onload="init()">
<div id="dialog">
  <div>Colour Picker</div>
  <div>
    <?php
		$currentwidth = 0;		
		// grayscale
		$graystep = 0xFF / (( $rows * $cols ) - 1);
		$gray = 0;
		echo '<table border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" align="left">'."\n";
			for ( $y = 0; $y < $rows; $y++ ) {
				echo '<tr>'."\n";
				for ( $x = 0; $x < $cols; $x++ ) {
					$hexcolor = RGB2hex($gray, $gray, $gray);
					echo '<td bgcolor="#' . $hexcolor . '"><img id="img' . $hexcolor . '" src="'.$lib_url.'/images/spacer.gif" alt="#' . $hexcolor . '" width="' . $cellsize . '" height="' . $cellsize . '" onMouseOver="imgOn(this)" onClick="selColor(\'' . $hexcolor . '\')" onDblClick="returnColor(\'' . $hexcolor . '\')" style="cursor: pointer;"></td>'."\n";
					$gray += $graystep;
				}
				echo '</tr>'."\n";
			}
		echo '</table>';
		
		CheckMaxWidthBR( $currentwidth, $maxwidth, $cols, $cellsize, $cellpadding, $cellspacing );
		
		// NOTE: "round($r) <= 0xFF" is needed instead of just "$r <= 0xFF" because of floating-point rounding errors
		for ($r = 0x00; round($r) <= 0xFF; $r += ( 0xFF / (( $totalsegments - 1 ) - 1 ))) {
			echo '<table border="0" cellspacing="' . $cellspacing . '" cellpadding="' . $cellpadding . '" align="left">'."\n";
			for ($g = 0x00; round($g) <= 0xFF; $g += ( 0xFF / ($rows - 1 ))) {
				echo '  <tr>'."\n";
				for ( $b = 0x00; round($b ) <= 0xFF; $b += ( 0xFF / ($cols - 1 ))) {
					$hexcolor = RGB2hex($r, $g, $b);
					echo '<td bgcolor="#'.$hexcolor.'"><img id="img'. $hexcolor .'" src="'.$lib_url.'/images/spacer.gif" alt="#' . $hexcolor . '" width="' . $cellsize . '" height="'.$cellsize.'" onMouseOver="imgOn(this)" onClick="selColor(\''.$hexcolor.'\')" onDblClick="returnColor(\''.$hexcolor.'\')" style="cursor: pointer;"></td>'."\n";
				}
				echo '  </tr>'."\n";
			}
			echo '</table>';		
			CheckMaxWidthBR( $currentwidth, $maxwidth, $cols, $cellsize, $cellpadding, $cellspacing );
		}
		echo '<br clear="all">';
	?>
    <table width="<?php echo $maxwidth; ?>" border="0" cellpadding="0" cellspacing="3">
      <form name="colorpicker" onsubmit="okClick(); return false;" action="">
        <tr>
          <td id="ccol" align="left" width="20" style="border:1px solid #bababa;"><img src="<?php echo $lib_url;?>/images/spacer.gif" alt="" width="20" height="20" hspace="0" vspace="0" align="left"></td>

          <td id="ncol" align="left" width="20" style="border:1px solid #bababa;"><img src="<?php echo $lib_url;?>/images/spacer.gif" alt="" width="20" height="20" hspace="0" vspace="0" align="left"></td>

          <td align="left" valign="bottom" nowrap>#<input type="text" id="color" name="color" size="7" maxlength="8" class="fldsm" onkeyup="setColor()"></td>
        </tr>
        <tr>
            <td colspan="3"><input type="button" value="Ok" style="cursor:pointer;" onclick="okClick();" class="button">
            <input type="button" value="Cancel" style="cursor:pointer;" onclick="cancelClick();" class="button"></td>
        </tr>
      </form>
    </table>
  </div>
</div>
</body>
</html>

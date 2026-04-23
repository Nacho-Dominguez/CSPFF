<?php
/**
* @version $Id: jce.php,v 1.0 2006/03/20 13:53:08 happy_noodle_boy Exp $
* @package Joomla 1.0
* @Based on tinymce.php by stingrey
* @copyright (C) 2006 Ryan Demmer
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_VALID_MOS' ) or die( 'Direct Access to this location is not allowed.' );

$_MAMBOTS->registerFunction( 'onInitEditor', 'botEditorInit' );
$_MAMBOTS->registerFunction( 'onGetEditorContents', 'botEditorGetContents' );
$_MAMBOTS->registerFunction( 'onEditorArea', 'botEditorArea' );

Global $mosConfig_live_site, $jspath;
// --- Start custom code DHS Informatisering ---
// ---     opensource@dhs.nl, www.dhs.nl     ---

// This code makes it possible to use jce in a
// website that combines normal and SSL-connections

//if(isset($_SERVER['HTTPS'])) {
if (isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] == "on") { //Modified by Ryan Demmer 01/06/2005 ryandemmer@gmail.com
	$jspath = "https:" . stristr ( $mosConfig_live_site, "//" );
} else {
	$jspath = "http:" . stristr ( $mosConfig_live_site, "//" );
}
/**
* TinyMCE WYSIWYG Editor - javascript initialisation
*/
function botEditorInit() {

        global $mosConfig_live_site, $my, $database, $mosConfig_absolute_path, $jspath;

        require_once( dirname( __FILE__ )."/jce/jscripts/tiny_mce/auth_jce.php" );
        require_once( $mosConfig_absolute_path."/administrator/components/com_jce/plugins/plugins.class.php" );
        
        $jceAuth = new authJCE();
        $isSuperAdmin = $jceAuth->isAdmin();
        $userid = $jceAuth->id;
        
        // load JCE info
    	$query = "SELECT id"
    	. "\n FROM #__mambots"
    	. "\n WHERE element = 'jce'"
    	. "\n AND folder = 'editors'"
    	;
    	$database->setQuery( $query );
    	$id = $database->loadResult();
    	$mambot = new mosMambot( $database );
    	$mambot->load( $id );
    	$params = new mosParameters( $mambot->params );
    	
    	$toolbar 			= $params->get( 'toolbar', 'top' );
    	$html_height		= $params->get( 'html_height', '550' );
    	$html_width			= $params->get( 'html_width', '750' );
    	$text_direction		= $params->get( 'text_direction', 'ltr' );
    	$content_css		= $params->get( 'content_css', 1 );
    	$content_css_custom	= $params->get( 'content_css_custom', '' );
    	$invalid_elms	    = $params->get( 'invalid_elements', 'script,applet,iframe' );
    	$extended_elms	    = $params->get( 'extended_elements', '' );
    	$event_elms	        = $params->get( 'event_elements', '' );
    	$newlines			= $params->get( 'newlines', 0 );
    	$compressed			= $params->get( 'compressed', 0 );
    	$preview_height		= $params->get( 'preview_height', '550' );
	    $preview_width		= $params->get( 'preview_width', '750' );
	    $convert_urls       = $params->get( 'convert_urls', 1 );
	    $entity_encoding    = $params->get( 'entity_encoding', 'named' );
	    $editor_width       = $params->get( 'editor_width', '500' );
        $editor_height      = $params->get( 'editor_height', '600' );
        $convert_fonts      = $params->get( 'convert_fonts', 1 );
        $table_inline       = $params->get( 'table_inline', 0 );
        $font_size_type     = $params->get( 'font_size_type', 'length' );
        
        $convert_urls = ( $convert_urls ) ? 'true' : 'false';
        $convert_fonts = ( $convert_fonts ) ? 'true' : 'false';
        $table_inline = ( $table_inline ) ? 'true' : 'false';

        // load Paste parameters
        $query = "SELECT id"
        . "\n FROM #__jce_plugins"
        . "\n WHERE plugin = 'paste' LIMIT 1"
        ;
        $database->setQuery( $query );
        $id = $database->loadResult();
        $plugin = new jcePlugins( $database );
        $plugin->load( $id );
        $paste_params = new mosParameters( $plugin->params );
        
        $paste_create_paragraphs = $paste_params->get( 'paste_create_paragraphs', 'false' );
        $paste_create_linebreaks = $paste_params->get( 'paste_create_linebreaks', 'false' );
        $paste_use_dialog = $paste_params->get( 'paste_use_dialog', 'false' );
        $paste_auto_cleanup_on_paste = $paste_params->get( 'paste_auto_cleanup_on_paste', 'false' );
        $paste_strip_class_attributes = $paste_params->get( 'paste_strip_class_attributes', 'all' );
        $paste_remove_spans = $paste_params->get( 'paste_remove_spans', 'true' );
        $paste_remove_styles = $paste_params->get( 'paste_remove_styles', 'true' );

        // Get the default stylesheet
        $query = "SELECT template FROM #__templates_menu WHERE client_id='0' AND menuid='0'";
        $database->setQuery( $query );
        $cur_template = $database->loadResult();
        // Assigned template
        if ( isset( $Itemid ) && $Itemid != "" && $Itemid != 0 ) {
            $query = "SELECT template FROM #__templates_menu WHERE client_id='0' AND menuid='$Itemid' LIMIT 1";
            $database->setQuery( $query );
            $cur_template = $database->loadResult() ? $database->loadResult() : $cur_template;
        }
        // load JCE info
        $elements = array();
    	$query = "SELECT elements"
    	. "\n FROM #__jce_plugins"
    	. "\n WHERE elements != ''"
    	. "\n AND published = 1"
    	. "\n AND access <= '".$userid."'"
    	;
    	$database->setQuery( $query );
    	$plugin_elms = $database->loadResultArray();
    	$plugin_elms = implode( ',', $plugin_elms );

        //Plugin settings and Authorisation
        $invalid_elements[] = $invalid_elms;

        $template_path = $mosConfig_live_site."/templates/".$cur_template."/css";
        $css_template = ( $content_css ) ? $template_path."/template_css.css" : $template_path."/".$content_css_custom;

        $query = "SELECT plugin"
        . "\n FROM #__jce_plugins"
        . "\n WHERE access <= '".$jceAuth->id."' AND published = 1 AND type = 'plugin'"
        ;
        $database->setQuery( $query );
        $plugin_array = $database->loadObjectList();
        
        $query = "SELECT icon"
        . "\n FROM #__jce_plugins"
        . "\n WHERE access <= '".$userid."'"
        . "\n AND published = 1"
        . "\n AND row = 1"
        . "\n AND icon != ''"
        . "\n ORDER BY ordering ASC"
        ;
        $database->setQuery( $query );
        $row1 = $database->loadObjectlist();
        
        $query = "SELECT icon"
        . "\n FROM #__jce_plugins"
        . "\n WHERE access <= '".$userid."'"
        . "\n AND published = 1"
        . "\n AND row = 2"
        . "\n AND icon != ''"
        . "\n ORDER BY ordering ASC"
        ;
        $database->setQuery( $query );
        $row2 = $database->loadObjectlist();
        
        $query = "SELECT icon"
        . "\n FROM #__jce_plugins"
        . "\n WHERE access <= '".$userid."'"
        . "\n AND published = 1"
        . "\n AND row = 3"
        . "\n AND icon != ''"
        . "\n ORDER BY ordering ASC"
        ;
        $database->setQuery( $query );
        $row3 = $database->loadObjectlist();
        
        $query = "SELECT icon"
        . "\n FROM #__jce_plugins"
        . "\n WHERE access <= '".$userid."'"
        . "\n AND published = 1"
        . "\n AND row = 4"
        . "\n AND icon != ''"
        . "\n ORDER BY ordering ASC"
        ;
        $database->setQuery( $query );
        $row4 = $database->loadObjectlist();
        
        $query = "SELECT plugin"
        . "\n FROM #__jce_plugins"
        . "\n WHERE type = 'command'"
        . "\n AND access > '".$userid."'"
        . "\n OR published = 0"
        ;
        $database->setQuery( $query );
        $remove_buttons = $database->loadResultArray();

        $row1_btns = array();
        $row2_btns = array();
        $row3_btns = array();
        $row4_btns = array();
        $plugins = array();
        
        foreach( $plugin_array as $plugin ){
            $plugins[] = $plugin->plugin;
        }
        foreach( $row1 as $row ){
            $row1_btns[] = $row->icon;
        }
        foreach( $row2 as $row ){
            $row2_btns[] = $row->icon;
        }
        foreach( $row3 as $row ){
            $row3_btns[] = $row->icon;
        }
        foreach( $row4 as $row ){
            $row4_btns[] = $row->icon;
        }
        $remove_buttons = ( !empty( $remove_buttons ) ) ? implode( ',', $remove_buttons ) : "";

        $row1_btns = ( $row1_btns ) ? $row1_btns = implode( ',', $row1_btns ) : '';
        $row2_btns = ( $row2_btns ) ? implode( ',', $row2_btns ) : '';
        $row3_btns = ( $row3_btns ) ? implode( ',', $row3_btns ) : '';
        $row4_btns = ( $row4_btns ) ? implode( ',', $row4_btns ) : '';
        
        $plugins = ( $plugins ) ? implode( ',', $plugins ) : '';

        if( $extended_elms ) $elements[] = $extended_elms;
        if( $plugin_elms ) $elements[] = $plugin_elms;

        $elements = implode( ',', $elements );
        $invalid_elements = implode( ',', $invalid_elements );
        
        //Get the installed languages
        $query = "SELECT lang"
        . "\n FROM #__jce_langs"
        ;
        $database->setQuery( $query );
        $langs = $database->loadResultArray();
        $jce_langs = implode( ',', $langs );
        
        //Get the published language
        $query = "SELECT lang"
        . "\n FROM #__jce_langs"
        . "\n WHERE published = 1"
        ;
        $database->setQuery( $query );
        $jce_curr_lang = $database->loadResult();
        
    	// JCE Compressed mode
	    if ( $compressed ) {
            $tinyjs = 'tiny_mce_gzip.php';
	    } else {
		    $tinyjs = 'tiny_mce.js';
	    }
	    if ( $newlines ) {
		    $br_newlines	= 'true';
		    $p_newlines     = 'false';
    	} else {
    		$br_newlines	= 'false';
    		$p_newlines     = 'true';
    	}
    	$font_size_type = ( $font_size_type == 'length' ) ? '8pt,10pt,12pt,14pt,18pt,24pt,36pt' : 'xx-small,x-small,small,medium,large,x-large,xx-large';
        return <<<EOD
<!--//TinyMCE/MosCE-->
<script type="text/javascript" src="$jspath/mambots/editors/jce/jscripts/tiny_mce/$tinyjs"></script>
<script type="text/javascript" src="$jspath/mambots/editors/jce/jscripts/tiny_mce/jce_functions.js"></script>
<script type="text/javascript">
        var url = document.location.href;
        var site_url = "$mosConfig_live_site";
        url = url.replace(site_url, '', 'g');
        if( url.match( 'administrator', 'g' ) ) site_url = site_url+'/administrator';
        if( url.match( '#', 'g' ) ) site_url = '';
        var convert_urls = $convert_urls;
        tinyMCE.init({
                site : site_url,
                jbase : site_url,
                document_base_url: "$mosConfig_live_site/",
                theme : "advanced",
                language : "$jce_curr_lang",
                lang_list : "$jce_langs",
                width : "$editor_width",
                height : "$editor_height",
                mode : "specific_textareas",
                browsers : "msie,safari,gecko,opera",
                event_elements : "$event_elms",
                relative_urls : true,
                remove_script_host : false,
                auto_reset_designmode : true,
                apply_source_formatting : true,
                save_callback : "JCESave",
                entity_encoding : "$entity_encoding",
                content_css : "$css_template",
      		    theme_advanced_source_editor_height : "$html_height",
		        theme_advanced_source_editor_width : "$html_width",
                plugin_preview_width : "$preview_width",
                plugin_preview_height : "$preview_height",
                //Paste Options
                paste_create_paragraphs : $paste_create_paragraphs,
                paste_create_linebreaks : $paste_create_linebreaks,
                paste_use_dialog : $paste_use_dialog,
                paste_auto_cleanup_on_paste : $paste_auto_cleanup_on_paste,
                paste_strip_class_attributes : "$paste_strip_class_attributes",
                paste_remove_spans : $paste_remove_spans,
                paste_remove_styles : $paste_remove_styles,
                //
                convert_fonts_to_spans : $convert_fonts,
                font_size_style_values : "$font_size_type",
                table_inline_editing : $table_inline,
                invalid_elements: "$invalid_elements",
		        force_br_newlines : $br_newlines,
		        force_p_newlines : $p_newlines,
                directionality : "$text_direction",
                theme_advanced_layout_manager : "SimpleLayout",
                theme_advanced_toolbar_location : "top",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_disable : "$remove_buttons",
                theme_advanced_buttons1 : "$row1_btns",
                theme_advanced_buttons2 : "$row2_btns",
                theme_advanced_buttons3 : "$row3_btns",
                theme_advanced_buttons4 : "$row4_btns",
                plugins : "$plugins",
                extended_valid_elements : "$elements"
        });
</script>
EOD;
}
/**
* TinyMCE WYSIWYG Editor - copy editor contents to form field
* @param string The name of the editor area
* @param string The name of the form field
*/
function botEditorGetContents( $editorArea, $hiddenField ) {
        global $jspath;

        return <<<EOD
        tinyMCE.triggerSave();
EOD;
}
/**
* mosce WYSIWYG Editor - display the editor
* @param string The name of the editor area
* @param string The content of the field
* @param string The name of the form field
* @param string The width of the editor area
* @param string The height of the editor area
* @param int The number of columns for the editor area
* @param int The number of rows for the editor area
*/
function botEditorArea( $name, $content, $hiddenField, $width, $height, $col, $row ) {
        global $jspath, $_MAMBOTS, $mosConfig_live_site;
        
        $results = $_MAMBOTS->trigger( 'onCustomEditorButton' );
        $buttons = array();
        foreach ($results as $result) {
               $buttons[] = '<img src="'.$jspath.'/mambots/editors-xtd/'.$result[0].'" onclick="tinyMCE.execCommand(\'mceInsertContent\',false,\''.$result[1].'\')" />';
        }
            $buttons = implode( "", $buttons );
            
        return <<<EOD
<textarea id="$hiddenField" name="$hiddenField" cols="$col" rows="$row" style="width:{$width}px; height:{$height}px;" mce_editable="true" class="mceEditor">$content</textarea>
<br />$buttons
EOD;
}
?>

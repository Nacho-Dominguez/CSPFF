/**
 * $RCSfile: editor_plugin_src.js,v $
 * $Revision: 1.22 $
 * $Date: 2006/02/10 16:29:39 $
 *
 * @author Moxiecode
 * @copyright Copyright © 2004-2006, Moxiecode Systems AB, All rights reserved.
 */

/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('preview', tinyMCE.getParam("lang_list"));

var TinyMCE_PreviewPlugin = {
	getInfo : function() {
		return {
			longname : 'Preview',
			author : 'Moxiecode Systems',
			authorurl : 'http://tinymce.moxiecode.com',
			infourl : 'http://tinymce.moxiecode.com/tinymce/docs/plugin_preview.html',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	/**
	 * Returns the HTML contents of the preview control.
	 */
	getControlHTML : function(cn) {
		switch (cn) {
			case "preview":
				return tinyMCE.getButtonHTML(cn, 'lang_preview_desc', '{$pluginurl}/images/preview.gif', 'mcePreview');
		}

		return "";
	},

	/**
	 * Executes the mcePreview command.
	 */
	execCommand : function(editor_id, element, command, user_interface, value) {
		// Handle commands
		switch (command) {
			case "mcePreview":
				var previewPage = tinyMCE.getParam("plugin_preview_pageurl", null);
				var previewWidth = tinyMCE.getParam("plugin_preview_width", "550");
				var previewHeight = tinyMCE.getParam("plugin_preview_height", "600");

				// Use a custom preview page
				if (previewPage) {
					var template = new Array();

					template['file'] = previewPage;
					template['width'] = previewWidth;
					template['height'] = previewHeight;

					tinyMCE.openWindow(template, {editor_id : editor_id, resizable : "yes", scrollbars : "yes", inline : "yes", content : tinyMCE.getContent(), content_css : tinyMCE.getParam("content_css")});
				} else {
					var win = window.open("", "mcePreview", "menubar=no,toolbar=no,scrollbars=yes,resizable=yes,left=20,top=20,width=" + previewWidth + ",height="  + previewHeight);
					var html = "";
					var c = tinyMCE.getContent();
					var pos = c.indexOf('<body'), pos2;

					if (pos != -1) {
						pos = c.indexOf('>', pos);
						pos2 = c.lastIndexOf('</body>');
						c = c.substring(pos + 1, pos2);
					}

                    var form = win.opener.document.adminForm
                    var title = form.title.value;

                    var temp = new Array();
            		for (var i=0, n=form.imagelist.options.length; i < n; i++) {
            			value = form.imagelist.options[i].value;
            			parts = value.split( '|' );

            			temp[i] = '<img src="'+ tinyMCE.getParam('jbase') +'/images/stories/' + parts[0] + '" align="' + parts[1] + '" border="' + parts[3] + '" alt="' + parts[2] + '" hspace="6" />';
            		}

            		var temp2 = c.split( '{mosimage}' );

            		var c = temp2[0];

            		for (var i=0, n=temp2.length-1; i < n; i++) {
            			c += temp[i] + temp2[i+1];
            		}
                    var link_elm = win.document.getElementsByTagName('a');
                    for (var i=0; i<link_elm.length; i++) {
                       var onclick = tinyMCE.getAttrib(link_elm[i], 'onclick');
                       if(onclick.charAt(0) == 'index.php?option='){
                            onclick += tinyMCE.getParam('jbase') + '/' + onclick;
                       }
                    }
                   
                    html += tinyMCE.getParam('doctype');
					html += '<html xmlns="http://www.w3.org/1999/xhtml">';
					html += '<head>';
					html += '<title>' + tinyMCE.getLang('lang_preview_desc') + '</title>';
					html += '<base href="' + tinyMCE.settings['base_href'] + '" />';
					html += '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					html += '<link href="' + tinyMCE.getParam("content_css") + '" rel="stylesheet" type="text/css" />';
					html += '</head>';
					html += '<body>';
                    html += '<table align="center" width="90%" cellspacing="2" cellpadding="2" border="0">';
	                html += '<tr>';
                    html += '<td class="contentheading" colspan="2">' +title+ '</td>';
	                html += '</tr>';
	                html += '<tr>';
		            html += '<td valign="top" height="90%" colspan="2">' + c + '</td>';
                    html += '</tr>';
	                html += '<tr>';
                    html += '<td align="right"><a href="javascript:void(0)" onClick="window.close()">Close</a></td>';
		            html += '<td align="left"><a href="javascript:void(0)" onClick="window.print(); return false">Print</a></td>';
	                html += '</tr>';
                    html += '</table>';
					html += '</body>';
					html += '</html>';

					win.document.write(html);
					win.document.close();
				}

				return true;
		}

		return false;
	}
};

tinyMCE.addPlugin("preview", TinyMCE_PreviewPlugin);

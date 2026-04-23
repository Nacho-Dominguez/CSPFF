//Common scripts for JCE
function openWin(url, width, height, resizable, scrollbars)
{
    x = parseInt(screen.width / 2.0) - (width / 2.0);
	y = parseInt(screen.height / 2.0) - (height / 2.0);
    if ((tinyMCE.isMSIE && !tinyMCE.isOpera) && resizable != 'yes' && tinyMCE.settings["dialog_type"] == "modal") {
        height += 10;

    var features = "resizable:" + resizable
                + ";scroll:"
                + scrollbars + ";status:yes;center:yes;help:no;dialogWidth:"
                + width + "px;dialogHeight:" + height + "px;";

    window.showModalDialog(url, window, features);
    } else {
        var modal = (resizable == "yes") ? "no" : "yes";

        if (tinyMCE.isGecko && tinyMCE.isMac)
            modal = "no";

        var win = window.open(url, "Popup", "top=" + y + ",left=" + x + ",scrollbars=" + scrollbars + ",dialog=" + modal + ",minimizable=" + resizable + ",modal=" + modal + ",width=" + width + ",height=" + height + ",resizable=" + resizable);
        if (win == null) {
            alert(tinyMCELang['lang_popup_blocked']);
            return;
        }
        eval('try { win.resizeTo(width, height); } catch(e) { }');
        // Make it bigger if statusbar is forced
        if (tinyMCE.isGecko) {
            if (win.document.defaultView.statusbar.visible)
                win.resizeBy(0, tinyMCE.isMac ? 10 : 24);
			}
			win.focus();
		 }
}
function checkUpload(val, other)
{
    var first = document.getElementById(val);
    var second = document.getElementById(other);

    if( first.checked ){
        first.value = true;
        second.checked = false;
        second.value = false;
    }
    if( first.checked == false )
        first.value = false;
    if(second.checked == false)
        second.value = false;
}
function showMessage(doc, msg, img, className)
{
    var message = doc.getElementById('msgContainer');
    message.className = className;
    setImg(doc, img);
    createInfo(doc, 'msgContainer', msg);
}
function setImg(doc, img)
{
	var image = doc.getElementById('imgMsgContainer');
	img = lib_url+'/images/'+img+'.gif';
	image.src = img;	
}
function removeInfo(obj)
{
    if(obj.firstChild)
        obj.removeChild(obj.firstChild);
}
function createInfo(doc, obj, txt)
{
    removeInfo(doc.getElementById(obj));
        doc.getElementById(obj).appendChild(doc.createTextNode(txt));
}
function showDlg(doc, dlg)
{
    switch( doc.getElementById(dlg).className ){
        case 'show':
            hide(doc, dlg);
        break;
        case 'hide':
            show(doc, dlg);
        break;
    }
}
function hide(doc, obj)
{
    doc.getElementById(obj).className = 'hide';
}
function show(doc, obj)
{
    doc.getElementById(obj).className = 'show';
}
function urlencode(file)
{
    file = file.replace('/', '%2F', 'gi');
    return file;
}
function urldecode(file)
{
    file = file.replace('%2F', '/', 'gi');
    return file;
}
function in_array(obj, arr)
{
    for(var i=0; i<arr.length; i++)
    {
        if(obj == arr[i])
        {
            return true;
        }else{
            return false;
        }
    }
}
function array_key(obj, arr)
{
    for(var i=0; i<arr.length; i++)
    {
        if(obj == arr[i])
        {
            return i;
        }
    }
}
function remove_from_array(obj, arr)
{
    for(var i=0; i<arr.length; i++)
    {
        if(obj == arr[i])
        {
            arr.splice(obj, 1);
        }
    }
}
function colourPicker(val, block, colour)
{
    var template = new Array();
    var url = hostUrl+'/index2.php?option=com_jce&no_html=1&task=lib&file=colourpicker.php&val='+val+'&block='+block+'&colour='+colour;
    w = 260;
    h = 280;
    openWin(url, w, h, 'no', 'no');
}
function getExtension(name) {
    var regexp = /\/|\\/;
    var parts = name.split(regexp);
    var filename = parts[parts.length-1].split(".");
    if (filename.length <= 1) {
        return(-1);
    }
    var ext = filename[filename.length-1].toLowerCase();
    return ext;
}
function getSelectValue(form_obj, field_name) {
	var elm = form_obj.elements[field_name];

	if (elm == null || elm.options == null)
		return "";

	return elm.options[elm.selectedIndex].value;
}

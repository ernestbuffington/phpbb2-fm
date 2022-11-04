<script language="JavaScript" type="text/javascript">
<!--
// bbCode control by
// subBlue design
// www.subBlue.com

// Startup variables
var imageTag = false;
var theSelection = false;

// Check for Browser & Platform for PC & IE specific bits
// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
var is_moz = 0;

var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac")!=-1);

// Define the bbCode tags
bbcode = new Array();
bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]','[border]','[/border]','[blur]','[/blur]','[code]','[/code]','[ebay]','[/ebay]','[edit]','[/edit]','[fade]','[/fade]','[flash width=100 height=100]','[/flash]','[flipv]','[/flipv]','[fliph]','[/fliph]','[footnote]','[/footnote]','[google]','[/google]','[googlevid]','[/googlevid]','[hr]','','[offtopic]','[/offtopic]','[pre]','[/pre]','[search]','[/search]','[scroll]','[/scroll]','[spoiler]','[/spoiler]','[stream]','[/stream]','[strike]','[/strike]','[tab]','[tab]','[table]','[/table]','[updown]','[/updown]','[username]','','[video width=100 height=100]','[/video]','[wave]','[/wave]','[yahoo]','[/yahoo]','[youtube]','[/youtube]');
imageTag = false;

function select_switch(status)
{
	for (i = 0; i < document.msglist.length; i++)
	{
		document.msglist.elements[i].checked = status;
	}
}


// Replacement for arrayname.length property
function getarraysize(thearray) {
	for (i = 0; i < thearray.length; i++) {
		if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null))
			return i;
	}
	return thearray.length;
}

// Replacement for arrayname.push(value) not implemented in IE until version 5.5
// Appends element to the array
function arraypush(thearray,value) {
	thearray[ getarraysize(thearray) ] = value;
}

// Replacement for arrayname.pop() not implemented in IE until version 5.5
// Removes and returns the last element of an array
function arraypop(thearray) {
	thearraysize = getarraysize(thearray);
	retval = thearray[thearraysize - 1];
	delete thearray[thearraysize - 1];
	return retval;
}

function checkForm() {

     formErrors = false;

	if (document.post.message.value.length < 2) {
       formErrors = "{L_EMPTY_MESSAGE}";
	}

	if (formErrors) {
		alert(formErrors);
		return false;
	} else {
		bbstyle(-1);
		//formObj.preview.disabled = true;
		//formObj.submit.disabled = true;
		return true;
	}
}

function bbplace(text) {
    var txtarea = document.post.message;
    if (txtarea.createTextRange && txtarea.caretPos) {
        var caretPos = txtarea.caretPos;
        caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
        txtarea.focus();
    } else if (txtarea.selectionStart || txtarea.selectionStart == '0') {
        var startPos = txtarea.selectionStart;
        var endPos = txtarea.selectionEnd;
        txtarea.value = txtarea.value.substring(0, startPos)
                      + text
                      + txtarea.value.substring(endPos, txtarea.value.length);
        txtarea.focus();
        txtarea.selectionStart = startPos + text.length;
        txtarea.selectionEnd = startPos + text.length;
    } else {
        txtarea.value  += text;
        txtarea.focus();
    }
}

function emoticon(text) {
	var txtarea = document.post.message;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos) {
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		txtarea.focus();
	} else {
		bbplace(text);
		txtarea.focus();
	}
}

function bbfontstyle(bbopen, bbclose) {
	var txtarea = document.post.message;

	if ((clientVer >= 4) && is_ie && is_win) {
		theSelection = document.selection.createRange().text;
		if (!theSelection) {
			txtarea.value += bbopen + bbclose;
			txtarea.focus();
			return;
		}
		document.selection.createRange().text = bbopen + theSelection + bbclose;
		txtarea.focus();
		return;
	}
	else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
	{
		mozWrap(txtarea, bbopen, bbclose);
		return;
	}
	else
	{
		txtarea.value += bbopen + bbclose;
		txtarea.focus();
	}
	storeCaret(txtarea);
}


function bbstyle(bbnumber) {
	var txtarea = document.post.message;

	txtarea.focus();
	donotinsert = false;
	theSelection = false;
	bblast = 0;

	if (bbnumber == -1) { // Close all open tags & default button names
		while (bbcode[0]) {
			butnumber = arraypop(bbcode) - 1;
			bbplace(bbtags[butnumber + 1]);
			buttext = eval('document.post.addbbcode' + butnumber + '.value');
			eval('document.post.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
		}
		imageTag = false; // All tags are closed including image tags :D
		txtarea.focus();
		return;
	}

	if ((clientVer >= 4) && is_ie && is_win)
	{
		theSelection = document.selection.createRange().text; // Get text selection
		if (theSelection) {
			// Add tags around selection
			document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];
			txtarea.focus();
			theSelection = '';
			return;
		}
	}
	else if (txtarea.selectionEnd && (txtarea.selectionEnd - txtarea.selectionStart > 0))
	{
		mozWrap(txtarea, bbtags[bbnumber], bbtags[bbnumber+1]);
		return;
	}

	// Find last occurance of an open tag the same as the one just clicked
	for (i = 0; i < bbcode.length; i++) {
		if (bbcode[i] == bbnumber+1) {
			bblast = i;
			donotinsert = true;
		}
	}

	if (donotinsert) {		// Close all open tags up to the one just clicked & default button names
		while (bbcode[bblast]) {
				butnumber = arraypop(bbcode) - 1;
           			bbplace(bbtags[butnumber + 1]);
				buttext = eval('document.post.addbbcode' + butnumber + '.value');
				eval('document.post.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
				imageTag = false;
			}
			txtarea.focus();
			return;
	} else { // Open tags

		if (imageTag && (bbnumber != 12)) {		// Close image tag before adding another
			bbplace(bbtags[13]);
			lastValue = arraypop(bbcode) - 1;	// Remove the close image tag from the list
			document.post.addbbcode12.value = "Img";	// Return button back to normal state
			imageTag = false;
		}

		// Open tag
		bbplace(bbtags[bbnumber]);
		if ((bbnumber == 12) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag
		arraypush(bbcode,bbnumber+1);
		eval('document.post.addbbcode'+bbnumber+'.value += "*"');
		txtarea.focus();
		return;
	}
	storeCaret(txtarea);
}

// From http://www.massless.org/mozedit/
function mozWrap(txtarea, open, close)
{
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	if (selEnd == 1 || selEnd == 2)
		selEnd = selLength;

	var s1 = (txtarea.value).substring(0,selStart);
	var s2 = (txtarea.value).substring(selStart, selEnd)
	var s3 = (txtarea.value).substring(selEnd, selLength);
	txtarea.value = s1 + open + s2 + close + s3;
	return;
}

// Insert at Claret position. Code from
// http://www.faqts.com/knowledge_base/view.phtml/aid/1052/fid/130
function storeCaret(textEl) {
	if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
}

function open_window(name, url, left, top, width, height, toolbar, menubar, statusbar, scrollbar, resizable)
{
  	toolbar_str = toolbar ? 'yes' : 'no';
  	menubar_str = menubar ? 'yes' : 'no';
  	statusbar_str = statusbar ? 'yes' : 'no';
  	scrollbar_str = scrollbar ? 'yes' : 'no';
  	resizable_str = resizable ? 'yes' : 'no';
  	window.open(url, name, 'left='+left+',top='+top+',width='+width+',height='+height+',toolbar='+toolbar_str+',menubar='+menubar_str+',status='+statusbar_str+',scrollbars='+scrollbar_str+',resizable='+resizable_str);
}
//-->
</script>

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
        <td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
	<!-- BEGIN switch_user_staff -->
        <td align="right" class="nav">{LINK_VIEW_VISIBLE} :: {LINK_VIEW_ALL} :: {LINK_VIEW_HIDDEN}</td>
        <!-- END switch_user_staff -->
</tr>
</table>

{ERROR_BOX}

<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form action="{S_GUESTBOOK_ACTION}" method="post" name="post" onsubmit="return checkForm(this)">

<tr>
         <th width="22%" height="26">{L_MESSAGE}</th>
         <th width="78%" height="26">{L_GUESTBOOK}</th>
</tr>
<tr>
	<td class="row1" valign="top"><table width="100%" cellpadding="4" cellspacing="2">
	<tr>
        	<td colspan="2"><span class="gensmall">{L_CAVEAT}</span></td>
        </tr>
        <tr>
        	<td colspan="2" class="gensmall">
                <b>{L_USERNAME}:</b><br />
                <input type="text" value="{FIELD_NICK}" size="30" maxlength="{LIMIT_USERNAME_MAX_LENGTH}" class="post" name="nick" accesskey="n" tabindex="1" title="{L_USERNAME}" /><br />
                <b>{L_EMAIL}:</b><br />
                <input type="text" value="{FIELD_EMAIL}" size="30" maxlength="100" class="post" name="email" accesskey="e" tabindex="2" title="{L_EMAIL}" /><br />
                <b>{L_SITE}:</b><br />
                <input type="text" value="{FIELD_SITO}" size="30" maxlength="100" class="post" name="sito" accesskey="s" tabindex="3" title="{L_SITE}" />
                </td>
	</tr>
        <tr>
        	<td colspan="2" align="center">
                <input type="button" class="button" accesskey="b" name="addbbcode0" value=" B " style="font-weight:bold; width: 30px" onClick="bbstyle(0)" />&nbsp;
                <input type="button" class="button" accesskey="i" name="addbbcode2" value=" i " style="font-style:italic; width: 30px" onClick="bbstyle(2)" />&nbsp;
                <input type="button" class="button" accesskey="u" name="addbbcode4" value=" u " style="text-decoration: underline; width: 30px" onClick="bbstyle(4)" />&nbsp;
                <input type="button" class="button" accesskey="w" name="addbbcode14" value="URL" style="text-decoration: underline; width: 40px" onClick="bbstyle(14)" />
                </td>
	</tr>
        <tr>
        	<td colspan="2" align="center">
                <input type="button" class="button" accesskey="q" name="addbbcode6" value="Quote" style="width: 50px" onClick="bbstyle(6)" />&nbsp;
                <input type="button" class="button" accesskey="c" name="addbbcode8" value="Code" style="width: 50px" onClick="bbstyle(8)" />&nbsp;
                <input type="button" class="button" accesskey="p" name="addbbcode12" value="Img" style="width: 50px" onClick="bbstyle(12)" />
                </td>
	</tr>
        <tr>
        	<td colspan="2" align="center"><select name="addbbcodefontcolor" onChange="bbfontstyle('[color=' + this.form.addbbcodefontcolor.options[this.form.addbbcodefontcolor.selectedIndex].value + ']', ' [/color]');this.selectedIndex=0;">
                        <option style="background-color: {T_TD_COLOR1}" value="{T_FONTCOLOR1}" class="genmed">{L_FONT_COLOR}</option>
 			<option style="color:coral; background-color: {T_TD_COLOR1}" value="coral" class="genmed">{L_COLOR_CORAL}</option>
 			<option style="color:crimson; background-color: {T_TD_COLOR1}" value="crimson" class="genmed">{L_COLOR_CRIMSON}</option>
			<option style="color:tomato; background-color: {T_TD_COLOR1}" value="tomato" class="genmed">{L_COLOR_TOMATO}</option>
			<option style="color:seagreen; background-color: {T_TD_COLOR1}" value="seagreen" class="genmed">{L_COLOR_SEA_GREEN}</option>
			<option style="color:darkorchid; background-color: {T_TD_COLOR1}" value="darkorchid" class="genmed">{L_COLOR_DARK_ORCHID}</option>
                        <option style="color:darkred; background-color: {T_TD_COLOR1}" value="darkred" class="genmed">{L_COLOR_DARK_RED}</option>
                        <option style="color:red; background-color: {T_TD_COLOR1}" value="red" class="genmed">{L_COLOR_RED}</option>
                        <option style="color:orange; background-color: {T_TD_COLOR1}" value="orange" class="genmed">{L_COLOR_ORANGE}</option>
			<option style="color:gold; background-color: {T_TD_COLOR1}" value="gold" class="genmed">{L_COLOR_GOLD}</option>
			<option style="color:chocolate; background-color: {T_TD_COLOR1}"value="chocolate" class="genmed">{L_COLOR_CHOCOLATE}</option>
                        <option style="color:brown; background-color: {T_TD_COLOR1}" value="brown" class="genmed">{L_COLOR_BROWN}</option>
                        <option style="color:yellow; background-color: {T_TD_COLOR1}" value="yellow" class="genmed">{L_COLOR_YELLOW}</option>
                        <option style="color:green; background-color: {T_TD_COLOR1}" value="green" class="genmed">{L_COLOR_GREEN}</option>
			<option style="color:darkgreen; background-color: {T_TD_COLOR1}" value="darkgreen" class="genmed">{L_COLOR_DARKGREEN}</option>
                        <option style="color:olive; background-color: {T_TD_COLOR1}" value="olive" class="genmed">{L_COLOR_OLIVE}</option>
                        <option style="color:cyan; background-color: {T_TD_COLOR1}" value="cyan" class="genmed">{L_COLOR_CYAN}</option>
			<option style="color:deepskyblue; background-color: {T_TD_COLOR1}" value="deepskyblue" class="genmed">{L_COLOR_DEEPSKYBLUE}</option>
			<option style="color:cadetblue; background-color: {T_TD_COLOR1}" value="cadetblue" class="genmed">{L_COLOR_CADET_BLUE}</option>
                        <option style="color:blue; background-color: {T_TD_COLOR1}" value="blue" class="genmed">{L_COLOR_BLUE}</option>
                        <option style="color:darkblue; background-color: {T_TD_COLOR1}" value="darkblue" class="genmed">{L_COLOR_DARK_BLUE}</option>
			<option style="color:midnightblue; background-color: {T_TD_COLOR1}" value="midnightblue" class="genmed">{L_COLOR_MIDNIGHTBLUE}</option>
                        <option style="color:indigo; background-color: {T_TD_COLOR1}" value="indigo" class="genmed">{L_COLOR_INDIGO}</option>
                        <option style="color:violet; background-color: {T_TD_COLOR1}" value="violet" class="genmed">{L_COLOR_VIOLET}</option>
                        <option style="color:white; background-color: {T_TD_COLOR1}" value="white" class="genmed">{L_COLOR_WHITE}</option>
                        <option style="color:black; background-color: {T_TD_COLOR1}" value="black" class="genmed">{L_COLOR_BLACK}</option>
		</select> &nbsp; <select name="addbbcodefontsize" onChange="bbfontstyle('[size=' + this.form.addbbcodefontsize.options[this.form.addbbcodefontsize.selectedIndex].value + ']', ' [/size]')">
 			<option style="background-color: {T_TD_COLOR1}" selected="selected">{L_FONT_SIZE}</option>
                        <option style="background-color: {T_TD_COLOR1}" value="7" class="genmed">{L_FONT_TINY}</option>
                        <option style="background-color: {T_TD_COLOR1}" value="9" class="genmed">{L_FONT_SMALL}</option>
                        <option style="background-color: {T_TD_COLOR1}" value="12" class="genmed">{L_FONT_NORMAL}</option>
                        <option style="background-color: {T_TD_COLOR1}" value="18" class="genmed">{L_FONT_LARGE}</option>
                        <option style="background-color: {T_TD_COLOR1}" value="24" class="genmed">{L_FONT_HUGE}</option>
		</select></td>
	</tr>
        <tr>
          	<td nowrap="nowrap" align="center">{BBCODE_STATUS}</td>
      		<td nowrap="nowrap" align="center"><a href="javascript:bbstyle(-1)" class="gensmall">{L_BBCODE_CLOSE_TAGS}</a></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><textarea cols="30" rows="15" wrap="off" maxlength="{MAXLENGTH}" class="post" name="message" accesskey="x" tabindex="4" onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);">{FIELD_COMENTO}</textarea></td>
	</tr>
	<tr>
		<td colspan="2"><table width="100" cellspacing="0" cellpadding="5" align="center">
		<tr>
        		<td align="center" colspan="{S_SMILIES_COLSPAN}" class="gensmall"><b>{L_EMOTICONS}</b></td>
        	</tr>
        	<!-- BEGIN smilies_row -->
        	<tr align="center" valign="middle">
        		<!-- BEGIN smilies_col -->
        	    	<td><a href="javascript:emoticon('{smilies_row.smilies_col.SMILEY_CODE}')"><img src="{smilies_row.smilies_col.SMILEY_IMG}" alt="{smilies_row.smilies_col.SMILEY_DESC}" title="{smilies_row.smilies_col.SMILEY_DESC}" /></a></td>
        	    	<!-- END smilies_col -->
		</tr>
        	<!-- END smilies_row -->
        	</table></td>
	</tr>
       	<tr>
        	<td colspan="2" class="catBottom" align="center">{S_HIDDEN_FIELDS}<input type="submit" value="{L_SUBMIT}" class="mainoption" name="submit" accesskey="v" tabindex="5" onclick="this.onclick = new Function('return false');" />&nbsp;&nbsp;<input class="liteoption" type="reset" value="{L_RESET}" tabindex="6" /></td>
        </tr>
        </form></table></td>
         <td class="row1" valign="top">
<form method="post" name="msglist" action="{U_GUESTBOOK}">
            <table width="100%" cellspacing="0" cellpadding="0">
     <!-- BEGIN postrow -->
            <tr>
                <td align="left" class="bodyline">
        	     <table width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                   <td height="15" class="{postrow.ROW_CLASS}">&nbsp;<img src="templates/{T_NAV_STYLE}/icon_minipost.gif" alt="" />
                    <span class="postdetails">
                    {L_FROM}&nbsp;<b>{postrow.NICK}</b>&nbsp;{postrow.DATA} {postrow.SITO} {postrow.EMAIL}&nbsp;</span></td>
                    <td align="center" class="{postrow.ROW_CLASS}" width="25%"><span class="postdetails"><b>{postrow.HIDEN_NICK}</b><br />{postrow.GUEST_IP}</span>&nbsp;</td>
                    <td align="center" width="2%" class="{postrow.ROW_CLASS}">&nbsp;{postrow.CHECK_ROW}&nbsp;</td>
        	      </tr>
                  <tr>
                    <td colspan="3" class="{postrow.ROW_CLASS}" valign="top">
                     <hr width="95%" height="3"/><span class="postbody">&nbsp;
                     {postrow.MESSAGE}<br /></span><hr width="95%" height="3"/>{postrow.QUOTE}&nbsp;{postrow.EDIT} </td>
                 </tr>
                 <tr>
                  <td class="{postrow.ROW_CLASS}" align="right" height="10" colspan="3"><span class="copyright">{postrow.AGENT}&nbsp;</span></td>
                 </tr>
                </table>
        	   </td>
            </tr>
            <tr>
        	   <td class="spaceRow" width="100%" height="5"></td>
            </tr>
            <tr>
                <td height="10" width="100%">&nbsp;</td>
            </tr>
          <!-- END postrow -->

          <!-- BEGIN guest_empty -->
            <tr>
             <td><span class="gen"><br /><b>{guest_empty.GUEST_EMPTY}</b></span></td>
            </tr>
          <!-- END guest_empty -->
          </table>
         </td>
</tr>
<tr>
	<td colspan="2" align="center" class="row3"><table width="100%" cellspacing="1">
	<tr>
		<td class="nav" nowrap="nowrap">{L_COUNTER}: <b>{COUNTER}</b></td>
	  	<td align="right" class="nav" width="100%" nowrap="nowrap">{PAGINATION}</td>
	</tr>
	</table></td>
</tr>
<tr>
         <td class="catBottom" colspan="2" align="right">{FIELD_VIEW}&nbsp;&nbsp;{FIELD_HIDE}&nbsp;&nbsp;{FIELD_UNHIDE}&nbsp;&nbsp;{FIELD_DELETE}</td>
</tr>
</form></table>
<table width="100%" cellpadding="2" cellspacing="2">
<tr>
         <td align="right"><b class="gensmall">{MARKED}</b></td>
</tr>
<tr>
         <td align="right"><br />{JUMPBOX}</span></td>
</tr>
</table>
<br />
<div align="center" class="copyright">Cricca Guestbook {VERSION} &copy 2004, {COPYRIGHT_YEAR} -Nessuno-</div>
{MEETING_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

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
bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[code]','[/code]','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]');
imageTag = false;

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


function emoticon(text) {
	var txtarea = document.post.message;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos) {
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
		txtarea.focus();
	} else {
		txtarea.value  += text;
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
			txtarea.value += bbtags[butnumber + 1];
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
				txtarea.value += bbtags[butnumber + 1];
				buttext = eval('document.post.addbbcode' + butnumber + '.value');
				eval('document.post.addbbcode' + butnumber + '.value ="' + buttext.substr(0,(buttext.length - 1)) + '"');
				imageTag = false;
			}
			txtarea.focus();
			return;
	} else { // Open tags

		if (imageTag && (bbnumber != 14)) {		// Close image tag before adding another
			txtarea.value += bbtags[15];
			lastValue = arraypop(bbcode) - 1;	// Remove the close image tag from the list
			document.post.addbbcode14.value = "Img";	// Return button back to normal state
			imageTag = false;
		}

		// Open tag
		txtarea.value += bbtags[bbnumber];
		if ((bbnumber == 14) && (imageTag == false)) imageTag = 1; // Check to stop additional tags after an unclosed image tag
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

//-->
</script>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="{S_ACTION}" method="post" name="post">
<tr>
	<th class="thHead" colspan="2">{L_MEETING}</th>
</tr>
<tr>
	<td class="row1" width="38%"><b>{L_MEETING_SUBJECT}:</b><br /><span class="gensmall">{MEETING_BY_USER} {MEETING_EDIT_BY_USER}</span></td>
	<td class="row2"><input type="text" class="post" name="meeting_subject" size="35" maxlength="255" value="{MEETING_SUBJECT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_LOCATION}:</b></td>
	<td class="row2"><input type="text" class="post" name="meeting_location" size="35" maxlength="255" value="{MEETING_LOCATION}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_LINK}:</b></td>
	<td class="row2"><input type="text" class="post" name="meeting_link" size="35" maxlength="255" value="{MEETING_LINK}" /></td>
</tr>
<tr>
	<td class="row1" valign="top"><b>{L_MEETING_DESC}:</><br /><br />
	<center><a href="{U_SMILIES}" onclick="window.open('{U_SMILIES}', '_phpbbsmilies', 'HEIGHT=300,resizable=yes,scrollbars=yes,WIDTH=250');return false;" target="_phpbbsmilies" class="nav">{L_SMILIES}</a></center></td>
	<td class="row2"><table cellpadding="2" cellspacing="2" width="100%">
	<tr>
		<td>
			<input type="button" class="button" accesskey="b" name="addbbcode0" value=" B " style="font-weight:bold; width: 40px" onClick="bbstyle(0)" />
			<input type="button" class="button" accesskey="i" name="addbbcode2" value=" i " style="font-style:italic; width: 40px" onClick="bbstyle(2)" />
			<input type="button" class="button" accesskey="u" name="addbbcode4" value=" u " style="text-decoration: underline; width: 40px" onClick="bbstyle(4)" />
			<input type="button" class="button" accesskey="q" name="addbbcode6" value="Quote" style="width: 50px" onClick="bbstyle(6)" />
			<input type="button" class="button" accesskey="c" name="addbbcode8" value="Code" style="width: 50px" onClick="bbstyle(8)" />
		</td>
	</tr>
	<tr>
		<td>
			<input type="button" class="button" accesskey="l" name="addbbcode10" value="List" style="width: 50px" onClick="bbstyle(10)" />
			<input type="button" class="button" accesskey="o" name="addbbcode12" value="List=" style="width: 50px" onClick="bbstyle(12)" />
			<input type="button" class="button" accesskey="p" name="addbbcode14" value="Img" style="width: 50px"  onClick="bbstyle(14)" />
			<input type="button" class="button" accesskey="w" name="addbbcode16" value="URL" style="text-decoration: underline; width: 50px" onClick="bbstyle(16)" />
			<a href="javascript:bbstyle(-1)" class="gensmall">{L_BBCODE_CLOSE_TAGS}</a></td>
	</tr>
	<tr>
		<td class="genmed"> &nbsp;{L_FONT_COLOR}:
			<select name="addbbcode18" onChange="bbfontstyle('[color=' + this.form.addbbcode18.options[this.form.addbbcode18.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;" onMouseOver="helpline('s')">
			  <option style="color:black; background-color: {T_TD_COLOR1}" value="{T_FONTCOLOR1}" class="genmed">{L_COLOR_DEFAULT}</option>
			  <option style="color:darkred; background-color: {T_TD_COLOR1}" value="darkred" class="genmed">{L_COLOR_DARK_RED}</option>
			  <option style="color:red; background-color: {T_TD_COLOR1}" value="red" class="genmed">{L_COLOR_RED}</option>
			  <option style="color:orange; background-color: {T_TD_COLOR1}" value="orange" class="genmed">{L_COLOR_ORANGE}</option>
			  <option style="color:brown; background-color: {T_TD_COLOR1}" value="brown" class="genmed">{L_COLOR_BROWN}</option>
			  <option style="color:yellow; background-color: {T_TD_COLOR1}" value="yellow" class="genmed">{L_COLOR_YELLOW}</option>
			  <option style="color:green; background-color: {T_TD_COLOR1}" value="green" class="genmed">{L_COLOR_GREEN}</option>
			  <option style="color:olive; background-color: {T_TD_COLOR1}" value="olive" class="genmed">{L_COLOR_OLIVE}</option>
			  <option style="color:cyan; background-color: {T_TD_COLOR1}" value="cyan" class="genmed">{L_COLOR_CYAN}</option>
			  <option style="color:blue; background-color: {T_TD_COLOR1}" value="blue" class="genmed">{L_COLOR_BLUE}</option>
			  <option style="color:darkblue; background-color: {T_TD_COLOR1}" value="darkblue" class="genmed">{L_COLOR_DARK_BLUE}</option>
			  <option style="color:indigo; background-color: {T_TD_COLOR1}" value="indigo" class="genmed">{L_COLOR_INDIGO}</option>
			  <option style="color:violet; background-color: {T_TD_COLOR1}" value="violet" class="genmed">{L_COLOR_VIOLET}</option>
			  <option style="color:white; background-color: {T_TD_COLOR1}" value="white" class="genmed">{L_COLOR_WHITE}</option>
			  <option style="color:black; background-color: {T_TD_COLOR1}" value="black" class="genmed">{L_COLOR_BLACK}</option>
			</select> &nbsp;{L_FONT_SIZE}: <select name="addbbcode20" onChange="bbfontstyle('[size=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/size]')" onMouseOver="helpline('f')">
			  <option value="7" class="genmed">{L_FONT_TINY}</option>
			  <option value="9" class="genmed">{L_FONT_SMALL}</option>
			  <option value="12" selected class="genmed">{L_FONT_NORMAL}</option>
			  <option value="18" class="genmed">{L_FONT_LARGE}</option>
			  <option  value="24" class="genmed">{L_FONT_HUGE}</option>
		</select></td>
	</tr>
	<tr>
		<td><textarea class="post" name="message" rows="10" cols="35" wrap="virtual" style="width:350px">{MEETING_DESC}</textarea></td>
	</tr>
	</table></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_TIME}:</b></td>
	<td class="row2">{MEETING_DAY} / {MEETING_MONTH} / <input class="post" type="text" size="4" maxlength="4" name="m_year" value="{MEETING_YEAR}" /> - {MEETING_HOUR} : {MEETING_MINUTE}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_UNTIL}:</b></td>
	<td class="row2">{MEETING_DAY_UNTIL} / {MEETING_MONTH_UNTIL} / <input class="post" type="text" size="4" maxlength="4" name="u_year" value="{MEETING_YEAR_UNTIL}" /> - {MEETING_HOUR_UNTIL} : {MEETING_MINUTE_UNTIL}</td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_START_VALUE} / {L_MEETING_RECURE_VALUE}:</b></td>
	<td class="row2"><input class="post" type="text" size="2" maxlength="2" name="meeting_start_value" value="{MEETING_START_VALUE}" /> / <input class="post" type="text" size="2" maxlength="2" name="meeting_recure_value" value="{MEETING_RECURE_VALUE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_NOTIFY}:</b></td>
	<td class="row2"><span class="genmed"><input type="radio" name="meeting_notify" value="1" {MEETING_NOTIFY_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="meeting_notify" value="0" {MEETING_NOTIFY_NO} /> {L_NO}</span></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_PLACES}:</b></td>
	<td class="row2"><input class="post" type="text" size="8" maxlength="8" name="meeting_places" value="{MEETING_PLACES}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_GUEST_OVERALL}:</b><br /><span class="gensmall">{L_MEETING_NO_GUEST_LIMIT}</span></td>
	<td class="row2"><input class="post" type="text" size="8" maxlength="8" name="meeting_guest_overall" value="{MEETING_GUEST_OVERALL}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_GUEST_SINGLE}:</b><br /><span class="gensmall">{L_MEETING_NO_GUEST_LIMIT}</span></td>
	<td class="row2"><input class="post" type="text" size="8" maxlength="8"  name="meeting_guest_single" value="{MEETING_GUEST_SINGLE}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_GUEST_NAMES}:</b><br /><span class="gensmall">{L_MEETING_GUEST_NAMES_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="meeting_guest_names" value="1" {MEETING_GUEST_NAMES_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="meeting_guest_names" value="0" {MEETING_GUEST_NAMES_NO} /> {L_NO}</td>
</tr>

<tr>
	<th class="thHead" colspan="2">{L_MEETING_USERGROUP}</th>
</tr>
<tr>
	<td class="row1"><b>{L_MEETING_USERGROUP}:</b></td>
	<td class="row2">{MEETING_USERGROUP}</td>
</tr>
<tr>
	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" />&nbsp;&nbsp;<input type="submit" name="cancel" class="liteoption" value="{L_CANCEL}" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Meeting 1.3.18 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.oxpus.de/" class="copyright" target="_blank">OXPUS</a></div>

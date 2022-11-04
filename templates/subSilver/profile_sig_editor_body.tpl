<script language="JavaScript" src="templates/js/color.js"></script> 
<!-- BEGIN switch_spellcheck -->
<script language="JavaScript" src="mods/spelling/spellmessage.js"></script>
<!-- END switch_spellcheck -->
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

// Helpline messages
b_help = "{L_BBCODE_B_HELP}";
i_help = "{L_BBCODE_I_HELP}";
u_help = "{L_BBCODE_U_HELP}";
q_help = "{L_BBCODE_Q_HELP}";
c_help = "{L_BBCODE_C_HELP}";
l_help = "{L_BBCODE_L_HELP}";
o_help = "{L_BBCODE_O_HELP}";
p_help = "{L_BBCODE_P_HELP}";
w_help = "{L_BBCODE_W_HELP}";
a_help = "{L_BBCODE_A_HELP}";
a1_help = "{L_BBCODE_A1_HELP}";
s_help = "{L_BBCODE_S_HELP}";
f_help = "{L_BBCODE_F_HELP}";
f1_help = "{L_BBCODE_F1_HELP}";
g1_help = "{L_BBCODE_G1_HELP}";
h1_help = "{L_BBCODE_H1_HELP}";
s1_help = "{L_BBCODE_S1_HELP}";
sc_help = "{L_BBCODE_SC_HELP}";
<!-- BEGIN XBBcode -->
<!-- BEGIN BB -->
{XBBcode.BB.VALUE}_help = "{XBBcode.BB.HELP}";
<!-- END BB -->
<!-- END XBBcode --> 
<!-- BEGIN category_help -->
{category_help.NAME}_help = "{category_help.HELP}";
<!-- END category_help -->

// Define the bbCode tags
bbcode = new Array();
bbtags = new Array('[b]','[/b]','[i]','[/i]','[u]','[/u]','[quote]','[/quote]','[list]','[/list]','[list=]','[/list]','[img]','[/img]','[url]','[/url]','[border]','[/border]','[blur]','[/blur]','[code]','[/code]','[ebay]','[/ebay]','[edit]','[/edit]','[fade]','[/fade]','[flash width=100 height=100]','[/flash]','[flipv]','[/flipv]','[fliph]','[/fliph]','[footnote]','[/footnote]','[google]','[/google]','[googlevid]','[/googlevid]','[hr]','','[mp3]','[/mp3]','[offtopic]','[/offtopic]','[pre]','[/pre]','[search]','[/search]','[scroll]','[/scroll]','[spoiler]','[/spoiler]','[stream]','[/stream]','[strike]','[/strike]','[tab]','[tab]','[table]','[/table]','[updown]','[/updown]','[username]','','[video width=100 height=100]','[/video]','[wave]','[/wave]','[yahoo]','[/yahoo]','[youtube]','[/youtube]','[mod="{EDITOR_NAME}"]','[/mod]');
imageTag = false;

// Shows the help messages in the helpline window
function helpline(help) {
	document.post.helpbox.value = eval(help + "_help");
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

// Message Length
var maxLength = {MAX_SYMBOLS}

function count(str) 
{
	var count = 0;
	for (i = 0; i < str.length; i++) 
	{
		val = str.charCodeAt(i);
		if (val >31) count++; 
	}
	return count;
}

function find(str, num) 
{
	var count = 0;
	var i=0;
	while (count < num) 
	{
		val = str.charCodeAt(i);
		if (val >31) count++;
		i++;
	}
	return i;
}

function changeValues(text, textlgth) 
{
	cutoff = find(text,maxLength)
	document.post.message.value = text.substring(0, cutoff);
	text = document.post.message.value;
	textlgth = text.length;
	document.post.theLength.value = maxLength-count(text);
}

function doChange() 
{
	if (maxLength != 0) 
	{
		text = document.post.message.value;
    		textlgth = count(text);
    		document.post.theLength.value = maxLength - textlgth;
    		if (textlgth > maxLength) 
		{
    			changeValues(text, textlgth);
    		}
	} 
	else 
	{
    		document.post.theLength.value = 'n/a';
	}
  	return true;
}

function highlightmetasearch() 
{ 
	document.post.message.select(); document.post.message.focus(); 
} 

function copymetasearch() 
{ 
	highlightmetasearch(); 
	textRange = document.post.message.createTextRange(); 
	textRange.execCommand("RemoveFormat"); 
	textRange.execCommand("Copy"); 
	alert("{L_COPY_TO_CLIPBOARD_EXPLAIN}"); 
} 

function toggleBlock(strObjName)
{
	var current;
	if (document.getElementById)
	{
		current = (document.getElementById(strObjName).style.display == 'block') ? 'none' : 'block';
		document.getElementById(strObjName).style.display = current;
	}
	else if (document.all)
	{
		current = (document.all[strObjName].style.display == 'block') ? 'none' : 'block'
		document.all[strObjName].style.display = current;
	}
	else alert ('This link does not work in your browser.');
}
// --> 
</script> 

<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>


{CPL_MENU_OUTPUT}

<!-- BEGIN preview -->
<table cellpadding="4" cellspacing="1" width="100%" class="forumline">
  <tr> 
	<th class="thHead">{L_PREVIEW} {L_SIGNATURE}</th>
  </tr>
  <tr> 
	<td class="row1"><b class="genmed">{L_SAMPLE_POST_1}</b><br /><span class="gensmall">{PRE_SIGNATURE}</span></td>
  </tr>
  <tr> 
	<td class="row2"><b class="genmed">{L_SAMPLE_POST_2}</b><br /><span class="gensmall">{PRE_SIGNATURE}</span></td>
  </tr>
</table>
<br />
<!-- END preview -->

<table cellpadding="4" cellspacing="1" width="100%" class="forumline"><form action="{S_POST_ACTION}" method="post" name="post">
<tr> 
	<th class="thHead" colspan="2">{L_SIGNATURE_PANEL}</th>
</tr>
<tr>
	<td colspan="2" class="row2"><span class="gensmall">{L_SIGNATURE_EXPLAIN}</span></td>
</tr>
<tr> 
	<td class="row1" valign="top"><table width="100%" cellspacing="0" cellpadding="1">
	<tr> 
		<td><b class="gen">{L_SIGNATURE}:</b></td>
	</tr>
	<tr> 
		<td valign="middle" align="center"><br /> <table width="100" cellspacing="0" cellpadding="5">
		<tr align="center"> 
			<td colspan="{S_SMILIES_COLSPAN}"><b class="genmed">{L_EMOTICONS}</b></td>
		</tr>
		<!-- BEGIN smilies_row -->
		<tr align="center" valign="middle"> 
			<!-- BEGIN smilies_col -->
			<td><img src="{smilies_row.smilies_col.SMILEY_IMG}" onmouseover="this.style.cursor='hand';" onclick="emoticon('{smilies_row.smilies_col.SMILEY_CODE}');" alt="{smilies_row.smilies_col.SMILEY_DESC}" title="{smilies_row.smilies_col.SMILEY_DESC}" /></td> 
			<!-- END smilies_col -->
		</tr>
		<!-- END smilies_row -->
		<!-- BEGIN switch_smilies_extra -->
		<tr align="center"> 
			<td colspan="{S_SMILIES_COLSPAN}"><span class="nav"><a href="{U_MORE_SMILIES}" onclick="window.open('{U_MORE_SMILIES}', '_phpbbsmilies', 'HEIGHT=300,resizable=yes,scrollbars=yes,WIDTH=250');return false;" target="_phpbbsmilies" class="nav">{L_MORE_SMILIES}</a></span></td>
		</tr>
		<!-- END switch_smilies_extra -->
		</table></td>
	</tr>
	<!-- BEGIN smiley_category -->
	<tr>
		<td align="center"><br /><b class="genmed">{L_SMILEY_CATEGORIES}</b></td>
	</tr>
	<!-- BEGIN buttons -->
	<tr>
		<td align="center"><input {smiley_category.buttons.TYPE} name="_phpbbsmilies" {smiley_category.buttons.VALUE} onClick="window.open('{smiley_category.buttons.CAT_MORE_SMILIES}', '_phpbbsmilies', 'HEIGHT=300,resizable=yes,scrollbars=yes,WIDTH=410'); return false;" onMouseOver="helpline('{smiley_category.buttons.NAME}')" /></td>
	</tr>
	<!-- END buttons -->
	<!-- BEGIN dropdown -->
	<tr>
		<td align="center">{smiley_category.dropdown.OPTIONS}</td>
	</tr>
	<!-- END dropdown -->
	<!-- END smiley_category -->
		<tr>
			<td align="center"><input type="button" class="button" value="{L_SMILIE_CREATOR}" style="width: 125px" onclick="window.open('smilie_creator.php?mode=text2schild', '_phpbbcreatesmilies', 'HEIGHT=300,resizable=yes,scrollbars=yes,WIDTH=450');return false;" target="_phpbbcreatesmilies" onMouseOver="helpline('sc')" /></td>
		</tr>
	</table>
	<br /><br /><br />
<b class="gen">{L_OPTIONS}:</b><br /><span class="gensmall">{HTML_STATUS}<br />{BBCODE_STATUS}<br />{SMILIES_STATUS}</span></td>
	<td class="row2" valign="top"><table width="510" cellspacing="0" cellpadding="2">
	<tr align="center" valign="middle"> 
		<td><table width="100%" cellspacing="2" cellpadding="2">
		<tr align="center" valign="middle"> 
			<td><span class="genmed"> 
			<input type="button" class="button" accesskey="b" name="addbbcode0" value=" B " style="font-weight:bold; width: 40px" onClick="bbstyle(0)" onMouseOver="helpline('b')" />
			</span></td>
			<td><span class="genmed"> 
			<input type="button" class="button" accesskey="i" name="addbbcode2" value=" i " style="font-style:italic; width: 40px" onClick="bbstyle(2)" onMouseOver="helpline('i')" />
			</span></td>
			<td><span class="genmed"> 
			<input type="button" class="button" accesskey="u" name="addbbcode4" value=" u " style="text-decoration: underline; width: 40px" onClick="bbstyle(4)" onMouseOver="helpline('u')" />
			</span></td>
			<td><span class="genmed"> 
			<input type="button" class="button" accesskey="q" name="addbbcode6" value="Quote" style="width: 50px" onClick="bbstyle(6)" onMouseOver="helpline('q')" />
			</span></td>
			<td><span class="genmed"> 
			<input type="button" class="button" accesskey="l" name="addbbcode8" value="List" style="width: 50px" onClick="bbstyle(8)" onMouseOver="helpline('l')" />
			</span></td>
			<td><span class="genmed"> 
			<input type="button" class="button" accesskey="o" name="addbbcode10" value="List=" style="width: 50px" onClick="bbstyle(10)" onMouseOver="helpline('o')" />
			</span></td>
			<td><span class="genmed"> 
			<input type="button" class="button" accesskey="p" name="addbbcode12" value="Img" style="text-decoration: none; width: 50px" onClick="bbstyle(12)" onMouseOver="helpline('p')" />
			</span></td>
			<td><span class="genmed"> 
			<input type="button" class="button" accesskey="w" name="addbbcode14" value="URL" style="text-decoration: underline; width: 50px" onClick="bbstyle(14)" onMouseOver="helpline('w')" />
			</span></td>
		  </tr>
		  <tr> 
			<td colspan="6" align="center"><select name="addbbcodefontcolor" onChange="bbfontstyle('[color=' + this.form.addbbcodefontcolor.options[this.form.addbbcodefontcolor.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;" onMouseOver="helpline('s')">
				<option style="background-color: {T_TD_COLOR1}" value="{T_FONTCOLOR1}">{L_FONT_COLOR}</option>
 				<option style="color:coral; background-color: {T_TD_COLOR1}" value="coral">{L_COLOR_CORAL}</option>
 				<option style="color:crimson; background-color: {T_TD_COLOR1}" value="crimson">{L_COLOR_CRIMSON}</option>
				<option style="color:tomato; background-color: {T_TD_COLOR1}" value="tomato">{L_COLOR_TOMATO}</option>
				<option style="color:seagreen; background-color: {T_TD_COLOR1}" value="seagreen">{L_COLOR_SEA_GREEN}</option>
				<option style="color:darkorchid; background-color: {T_TD_COLOR1}" value="darkorchid">{L_COLOR_DARK_ORCHID}</option>
				<option style="color:darkred; background-color: {T_TD_COLOR1}" value="darkred">{L_COLOR_DARK_RED}</option>
				<option style="color:red; background-color: {T_TD_COLOR1}" value="red">{L_COLOR_RED}</option>
				<option style="color:orange; background-color: {T_TD_COLOR1}" value="orange">{L_COLOR_ORANGE}</option>
				<option style="color:gold; background-color: {T_TD_COLOR1}" value="gold">{L_COLOR_GOLD}</option>
				<option style="color:chocolate; background-color: {T_TD_COLOR1}"value="chocolate">{L_COLOR_CHOCOLATE}</option>
				<option style="color:brown; background-color: {T_TD_COLOR1}" value="brown">{L_COLOR_BROWN}</option>
				<option style="color:yellow; background-color: {T_TD_COLOR1}" value="yellow">{L_COLOR_YELLOW}</option>
				<option style="color:green; background-color: {T_TD_COLOR1}" value="green">{L_COLOR_GREEN}</option>
				<option style="color:darkgreen; background-color: {T_TD_COLOR1}" value="darkgreen">{L_COLOR_DARKGREEN}</option>
				<option style="color:olive; background-color: {T_TD_COLOR1}" value="olive">{L_COLOR_OLIVE}</option>
				<option style="color:cyan; background-color: {T_TD_COLOR1}" value="cyan">{L_COLOR_CYAN}</option>
				<option style="color:deepskyblue; background-color: {T_TD_COLOR1}" value="deepskyblue">{L_COLOR_DEEPSKYBLUE}</option>
				<option style="color:cadetblue; background-color: {T_TD_COLOR1}" value="cadetblue">{L_COLOR_CADET_BLUE}</option>
				<option style="color:blue; background-color: {T_TD_COLOR1}" value="blue">{L_COLOR_BLUE}</option>
				<option style="color:darkblue; background-color: {T_TD_COLOR1}" value="darkblue">{L_COLOR_DARK_BLUE}</option>
				<option style="color:midnightblue; background-color: {T_TD_COLOR1}" value="midnightblue">{L_COLOR_MIDNIGHTBLUE}</option>
				<option style="color:indigo; background-color: {T_TD_COLOR1}" value="indigo">{L_COLOR_INDIGO}</option>
				<option style="color:violet; background-color: {T_TD_COLOR1}" value="violet">{L_COLOR_VIOLET}</option>
				<option style="color:white; background-color: {T_TD_COLOR1}" value="white">{L_COLOR_WHITE}</option>
				<option style="color:black; background-color: {T_TD_COLOR1}" value="black">{L_COLOR_BLACK}</option>
			</select> &nbsp; <select name="addbbcodefontsize" onChange="bbfontstyle('[size=' + this.form.addbbcodefontsize.options[this.form.addbbcodefontsize.selectedIndex].value + ']', '[/size]');this.selectedIndex=0;" onMouseOver="helpline('f')">
				<option style="background-color: {T_TD_COLOR1}" selected="selected">{L_FONT_SIZE}</option>
				<option style="background-color: {T_TD_COLOR1}" value="7" title="{L_FONT_TINY}">{L_FONT_TINY}</option>
				<option style="background-color: {T_TD_COLOR1}" value="9" title="{L_FONT_SMALL}">{L_FONT_SMALL}</option>
				<option style="background-color: {T_TD_COLOR1}" value="12" title="{L_FONT_NORMAL}">{L_FONT_NORMAL}</option>
				<option style="background-color: {T_TD_COLOR1}" value="18" title="{L_FONT_LARGE}">{L_FONT_LARGE}</option>
				<option style="background-color: {T_TD_COLOR1}" value="24" title="{L_FONT_HUGE}">{L_FONT_HUGE}</option>
			</select> &nbsp;<select name="addbbcodefontface" onchange="bbfontstyle('[font=' + this.form.addbbcodefontface.options[this.form.addbbcodefontface.selectedIndex].value + ']', '[/font]');this.selectedIndex=0;" onmouseover="helpline('f1')">
				<option style="background-color: {T_TD_COLOR1}" selected="selected">{L_FONT_FACE}</option>
			<!-- BEGIN font_styles -->
				<option style="background-color: {T_TD_COLOR1}; font-family:{font_styles.L_FONTNAME}" title="{font_styles.L_FONTNAME}" value="{font_styles.L_FONTNAME}">{font_styles.L_FONTNAME}</option>
			<!-- END font_styles -->
			</select></td>
			<td nowrap="nowrap" align="center"><a href="javascript:toggleBlock('bbcode');" onMouseOver="helpline('a1')" class="gensmall">{L_EXPAND_BBCODE}</a></td>
			<td nowrap="nowrap" align="center"><a href="javascript:bbstyle(-1)" class="gensmall" onMouseOver="helpline('a')">{L_BBCODE_CLOSE_TAGS}</a></td>
		</tr>
		</table>

		<div id="bbcode" name="bbcode" style="display: none;"> 
		<table width="100%" cellspacing="2" cellpadding="2">
		<tr> 
			<td colspan="8" align="center"><select name="addbbcodetextalign" onChange="bbfontstyle('[align=' + this.form.addbbcodetextalign.options[this.form.addbbcodetextalign.selectedIndex].value + ']', '[/align]')" onMouseOver="helpline('c')">
				<option selected="selected">{L_ALIGN_TEXT}</option>
				<option value="left">{L_LEFT}</option>
				<option value="center">{L_CENTER}</option>
				<option value="right">{L_RIGHT}</option>
				<option value="justify">{L_JUSTIFY}</option>
			</select> &nbsp; <select name="addbbcodehighlight" onChange="bbfontstyle('[highlight=' + this.form.addbbcodehighlight.options[this.form.addbbcodehighlight.selectedIndex].value + ']', '[/highlight]');this.selectedIndex=0;" onMouseOver="helpline('h1')">
				<option style="background-color: {T_TD_COLOR1}" value="{T_FONTCOLOR1}">{L_HIGHLIGHT_COLOR}</option>
 				<option style="color:coral; background-color: {T_TD_COLOR1}" value="coral">{L_COLOR_CORAL}</option>
 				<option style="color:crimson; background-color: {T_TD_COLOR1}" value="crimson">{L_COLOR_CRIMSON}</option>
				<option style="color:tomato; background-color: {T_TD_COLOR1}" value="tomato">{L_COLOR_TOMATO}</option>
				<option style="color:seagreen; background-color: {T_TD_COLOR1}" value="seagreen">{L_COLOR_SEA_GREEN}</option>
				<option style="color:darkorchid; background-color: {T_TD_COLOR1}" value="darkorchid">{L_COLOR_DARK_ORCHID}</option>
				<option style="color:darkred; background-color: {T_TD_COLOR1}" value="darkred">{L_COLOR_DARK_RED}</option>
				<option style="color:red; background-color: {T_TD_COLOR1}" value="red">{L_COLOR_RED}</option>
				<option style="color:orange; background-color: {T_TD_COLOR1}" value="orange">{L_COLOR_ORANGE}</option>
				<option style="color:gold; background-color: {T_TD_COLOR1}" value="gold">{L_COLOR_GOLD}</option>
				<option style="color:chocolate; background-color: {T_TD_COLOR1}"value="chocolate">{L_COLOR_CHOCOLATE}</option>
				<option style="color:brown; background-color: {T_TD_COLOR1}" value="brown">{L_COLOR_BROWN}</option>
				<option style="color:yellow; background-color: {T_TD_COLOR1}" value="yellow">{L_COLOR_YELLOW}</option>
				<option style="color:green; background-color: {T_TD_COLOR1}" value="green">{L_COLOR_GREEN}</option>
				<option style="color:darkgreen; background-color: {T_TD_COLOR1}" value="darkgreen">{L_COLOR_DARKGREEN}</option>
				<option style="color:olive; background-color: {T_TD_COLOR1}" value="olive">{L_COLOR_OLIVE}</option>
				<option style="color:cyan; background-color: {T_TD_COLOR1}" value="cyan">{L_COLOR_CYAN}</option>
				<option style="color:deepskyblue; background-color: {T_TD_COLOR1}" value="deepskyblue">{L_COLOR_DEEPSKYBLUE}</option>
				<option style="color:cadetblue; background-color: {T_TD_COLOR1}" value="cadetblue">{L_COLOR_CADET_BLUE}</option>
				<option style="color:blue; background-color: {T_TD_COLOR1}" value="blue">{L_COLOR_BLUE}</option>
				<option style="color:darkblue; background-color: {T_TD_COLOR1}" value="darkblue">{L_COLOR_DARK_BLUE}</option>
				<option style="color:midnightblue; background-color: {T_TD_COLOR1}" value="midnightblue">{L_COLOR_MIDNIGHTBLUE}</option>
				<option style="color:indigo; background-color: {T_TD_COLOR1}" value="indigo">{L_COLOR_INDIGO}</option>
				<option style="color:violet; background-color: {T_TD_COLOR1}" value="violet">{L_COLOR_VIOLET}</option>
				<option style="color:white; background-color: {T_TD_COLOR1}" value="white">{L_COLOR_WHITE}</option>
				<option style="color:black; background-color: {T_TD_COLOR1}" value="black">{L_COLOR_BLACK}</option>
			</select> &nbsp; <select name="addbbcodeshadowcolor" onChange="bbfontstyle('[shadow=' + this.form.addbbcodeshadowcolor.options[this.form.addbbcodeshadowcolor.selectedIndex].value + ']', '[/shadow]')" onMouseOver="helpline('s1')">
			<option style="background-color: {T_TD_COLOR1}" selected>{L_SHADOW_COLOR}</option>
 				<option style="color:coral; background-color: {T_TD_COLOR1}" value="coral">{L_COLOR_CORAL}</option>
 				<option style="color:crimson; background-color: {T_TD_COLOR1}" value="crimson">{L_COLOR_CRIMSON}</option>
				<option style="color:tomato; background-color: {T_TD_COLOR1}" value="tomato">{L_COLOR_TOMATO}</option>
				<option style="color:seagreen; background-color: {T_TD_COLOR1}" value="seagreen">{L_COLOR_SEA_GREEN}</option>
				<option style="color:darkorchid; background-color: {T_TD_COLOR1}" value="darkorchid">{L_COLOR_DARK_ORCHID}</option>
				<option style="color:darkred; background-color: {T_TD_COLOR1}" value="darkred">{L_COLOR_DARK_RED}</option>
				<option style="color:red; background-color: {T_TD_COLOR1}" value="red">{L_COLOR_RED}</option>
				<option style="color:orange; background-color: {T_TD_COLOR1}" value="orange">{L_COLOR_ORANGE}</option>
				<option style="color:gold; background-color: {T_TD_COLOR1}" value="gold">{L_COLOR_GOLD}</option>
				<option style="color:chocolate; background-color: {T_TD_COLOR1}"value="chocolate">{L_COLOR_CHOCOLATE}</option>
				<option style="color:brown; background-color: {T_TD_COLOR1}" value="brown">{L_COLOR_BROWN}</option>
				<option style="color:yellow; background-color: {T_TD_COLOR1}" value="yellow">{L_COLOR_YELLOW}</option>
				<option style="color:green; background-color: {T_TD_COLOR1}" value="green">{L_COLOR_GREEN}</option>
				<option style="color:darkgreen; background-color: {T_TD_COLOR1}" value="darkgreen">{L_COLOR_DARKGREEN}</option>
				<option style="color:olive; background-color: {T_TD_COLOR1}" value="olive">{L_COLOR_OLIVE}</option>
				<option style="color:cyan; background-color: {T_TD_COLOR1}" value="cyan">{L_COLOR_CYAN}</option>
				<option style="color:deepskyblue; background-color: {T_TD_COLOR1}" value="deepskyblue">{L_COLOR_DEEPSKYBLUE}</option>
				<option style="color:cadetblue; background-color: {T_TD_COLOR1}" value="cadetblue">{L_COLOR_CADET_BLUE}</option>
				<option style="color:blue; background-color: {T_TD_COLOR1}" value="blue">{L_COLOR_BLUE}</option>
				<option style="color:darkblue; background-color: {T_TD_COLOR1}" value="darkblue">{L_COLOR_DARK_BLUE}</option>
				<option style="color:midnightblue; background-color: {T_TD_COLOR1}" value="midnightblue">{L_COLOR_MIDNIGHTBLUE}</option>
				<option style="color:indigo; background-color: {T_TD_COLOR1}" value="indigo">{L_COLOR_INDIGO}</option>
				<option style="color:violet; background-color: {T_TD_COLOR1}" value="violet">{L_COLOR_VIOLET}</option>
				<option style="color:white; background-color: {T_TD_COLOR1}" value="white">{L_COLOR_WHITE}</option>
				<option style="color:black; background-color: {T_TD_COLOR1}" value="black">{L_COLOR_BLACK}</option>
			</select> &nbsp; <select name="addbbcodeglowtext" onChange="bbfontstyle('[glow=' + this.form.addbbcodeglowtext.options[this.form.addbbcodeglowtext.selectedIndex].value + ']', '[/glow]')" onMouseOver="helpline('g1')">
			<option style="background-color: {T_TD_COLOR1}" selected>{L_GLOW_COLOR}</option>
 				<option style="color:coral; background-color: {T_TD_COLOR1}" value="coral">{L_COLOR_CORAL}</option>
 				<option style="color:crimson; background-color: {T_TD_COLOR1}" value="crimson">{L_COLOR_CRIMSON}</option>
				<option style="color:tomato; background-color: {T_TD_COLOR1}" value="tomato">{L_COLOR_TOMATO}</option>
				<option style="color:seagreen; background-color: {T_TD_COLOR1}" value="seagreen">{L_COLOR_SEA_GREEN}</option>
				<option style="color:darkorchid; background-color: {T_TD_COLOR1}" value="darkorchid">{L_COLOR_DARK_ORCHID}</option>
				<option style="color:darkred; background-color: {T_TD_COLOR1}" value="darkred">{L_COLOR_DARK_RED}</option>
				<option style="color:red; background-color: {T_TD_COLOR1}" value="red">{L_COLOR_RED}</option>
				<option style="color:orange; background-color: {T_TD_COLOR1}" value="orange">{L_COLOR_ORANGE}</option>
				<option style="color:gold; background-color: {T_TD_COLOR1}" value="gold">{L_COLOR_GOLD}</option>
				<option style="color:chocolate; background-color: {T_TD_COLOR1}"value="chocolate">{L_COLOR_CHOCOLATE}</option>
				<option style="color:brown; background-color: {T_TD_COLOR1}" value="brown">{L_COLOR_BROWN}</option>
				<option style="color:yellow; background-color: {T_TD_COLOR1}" value="yellow">{L_COLOR_YELLOW}</option>
				<option style="color:green; background-color: {T_TD_COLOR1}" value="green">{L_COLOR_GREEN}</option>
				<option style="color:darkgreen; background-color: {T_TD_COLOR1}" value="darkgreen">{L_COLOR_DARKGREEN}</option>
				<option style="color:olive; background-color: {T_TD_COLOR1}" value="olive">{L_COLOR_OLIVE}</option>
				<option style="color:cyan; background-color: {T_TD_COLOR1}" value="cyan">{L_COLOR_CYAN}</option>
				<option style="color:deepskyblue; background-color: {T_TD_COLOR1}" value="deepskyblue">{L_COLOR_DEEPSKYBLUE}</option>
				<option style="color:cadetblue; background-color: {T_TD_COLOR1}" value="cadetblue">{L_COLOR_CADET_BLUE}</option>
				<option style="color:blue; background-color: {T_TD_COLOR1}" value="blue">{L_COLOR_BLUE}</option>
				<option style="color:darkblue; background-color: {T_TD_COLOR1}" value="darkblue">{L_COLOR_DARK_BLUE}</option>
				<option style="color:midnightblue; background-color: {T_TD_COLOR1}" value="midnightblue">{L_COLOR_MIDNIGHTBLUE}</option>
				<option style="color:indigo; background-color: {T_TD_COLOR1}" value="indigo">{L_COLOR_INDIGO}</option>
				<option style="color:violet; background-color: {T_TD_COLOR1}" value="violet">{L_COLOR_VIOLET}</option>
				<option style="color:white; background-color: {T_TD_COLOR1}" value="white">{L_COLOR_WHITE}</option>
				<option style="color:black; background-color: {T_TD_COLOR1}" value="black">{L_COLOR_BLACK}</option>
			</select></td>
		</tr>
		<!-- BEGIN XBBcode -->
		<tr align="center" valign="middle"> 
			<!-- BEGIN BB -->
			<td><span class="genmed">
			<input type="button" class="button" accesskey="{XBBcode.BB.KEY}" name="{XBBcode.BB.NAME}" value="{XBBcode.BB.VALUE}" style="width: {XBBcode.BB.WIDTH}px" onClick="{XBBcode.BB.STYLE}" onMouseOver="helpline('{XBBcode.BB.VALUE}')" />
			</span></td>
			<!-- END BB -->
		</tr>
		<!-- END XBBcode -->
		</table>
		</div>

		</td>
	</tr>
	<tr> 
		<td class="gensmall"><input type="text" name="helpbox" size="50" class="helpline" style="width:510px; font-size:10px" value="{L_STYLES_TIP}" /></td>
	</tr>
	<tr> 
		<td align="left"><table width="100%">
		<tr> 
			<td valign="top"><textarea name="message" rows="16" cols="35" wrap="virtual" style="width:510px" tabindex="3" class="post" onselect="storeCaret(this);doChange();" onclick="storeCaret(this);doChange();" onkeyup="storeCaret(this);doChange();">{SIGNATURE}</textarea></td>
			<td align="right"><table width="100%" id="ColorPanel" cellspacing="0" cellpadding="0"> 
			<tbody>
			<script language="JavaScript" type="text/javascript">
			<!-- 
				rgb(30,4,1) 
			// --> 
			</script>
			</tbody>
			</table></td>
		</tr>
		</table></td>
	</tr>
	<!-- BEGIN switch_msg_length -->
	<tr>
		<td class="gensmall">{L_SYMBOLS_LEFT}: <input style="background:0;border:0;" name="theLength" type="text" size="5" readonly /></td>
	</tr>
	 <!-- END switch_msg_length -->
	</table></td>
</tr>
<tr> 
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FORM_FIELDS}<input type="submit" tabindex="5" name="preview" class="liteoption" value="{L_PREVIEW}" />&nbsp;&nbsp;<input type="submit" accesskey="s" tabindex="6" name="submit" class="mainoption" value="{L_SUBMIT}" onclick="this.onclick = new Function('return false');" />&nbsp;
	<script language="JavaScript" type="text/javascript"> 
	<!-- 
		if ((navigator.appName=="Microsoft Internet Explorer")&&(parseInt(navigator.appVersion)>=4)) 
 		{ 
			document.write('<input type="button" class="liteoption" value="{L_COPY_TO_CLIPBOARD}" onClick="copymetasearch();">'); 
	  	} 
	  	else 
	  	{ 
	  		document.write('<input type="button" class="liteoption" value="{L_HIGHLIGHT_TEXT}" onClick="highlightmetasearch();">'); 
	  	} 
	  // --> 
	  </script> 
	  <!-- BEGIN switch_spellcheck -->
	  &nbsp;<input type="button" class="liteoption" value="{L_SPELLCHECK}" name="button" onclick="openspell();">
	  <!-- END switch_spellcheck -->
</td>
</tr>
</form></table>

	</td>
</tr>
</table>
<br />
<table width="100%" cellspacing="2" align="center">
  <tr> 
	<td align="right">{JUMPBOX}</td>
  </tr> 
</table>
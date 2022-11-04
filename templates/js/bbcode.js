var pas = 30; // define the number of color in the color bar 

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

function checkForm() {

	formErrors = false;    

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
    if (document.post.message.createTextRange && txtarea.caretPos) {
        var caretPos = document.post.message.caretPos;
        caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
        document.post.message.focus();
    } else if (document.post.message.selectionStart || document.post.message.selectionStart == '0') {
        var startPos = document.post.message.selectionStart;
        var endPos = document.post.message.selectionEnd;
        document.post.message.value = document.post.message.value.substring(0, startPos)
                      + text
                      + document.post.message.value.substring(endPos, document.post.message.value.length);
        document.post.message.focus();
        document.post.message.selectionStart = startPos + text.length;
        document.post.message.selectionEnd = startPos + text.length;
    } else {
        document.post.message.value  += text;
        document.post.message.focus();
    }
}

function emoticon(text) {
	text = ' ' + text + ' ';
	if (document.post.message.createTextRange && document.post.message.caretPos) {
		var caretPos = document.post.message.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		document.post.message.focus();
	} else {
            	bbplace(text);
	document.post.message.focus();
	}
}

function bbfontstyle(bbopen, bbclose) {
	if ((clientVer >= 4) && is_ie && is_win) {
		theSelection = document.selection.createRange().text;
		if (!theSelection) {
			document.post.message.value += bbopen + bbclose;
			document.post.message.focus();
			return;
		}
		document.selection.createRange().text = bbopen + theSelection + bbclose;
		document.post.message.focus();
		return;
	}
	else if (document.post.message.selectionEnd && (document.post.message.selectionEnd - document.post.message.selectionStart > 0))
	{
		mozWrap(document.post.message, bbopen, bbclose);
		return;
	}
	else
	{
		document.post.message.value += bbopen + bbclose;
		document.post.message.focus();
	}
	storeCaret(document.post.message);
}


function bbstyle(bbnumber) {
	document.post.message.focus();
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
		document.post.message.focus();
		return;
	}

	if ((clientVer >= 4) && is_ie && is_win)
	{
		theSelection = document.selection.createRange().text; // Get text selection
		if (theSelection) {
			// Add tags around selection
			document.selection.createRange().text = bbtags[bbnumber] + theSelection + bbtags[bbnumber+1];
		document.post.message.focus();
			theSelection = '';
			return;
		}
	}
	else if (document.post.message.selectionEnd && (document.post.message.selectionEnd - document.post.message.selectionStart > 0))
	{
		mozWrap(document.post.message, bbtags[bbnumber], bbtags[bbnumber+1]);
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
			document.post.message.focus();
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
		document.post.message.focus();
		return;
	}
	storeCaret(document.post.message);
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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="imagetoolbar" content="no"> 
<title>{SITENAME} &bull; {PAGE_TITLE}</title>
{META}
<style type="text/css">
<!--
/*  The original subSilver Theme for phpBB version 2+
    phpBB 3.0 Admin Style Sheet    
    ------------------------------------------------------------------------
	Original author:	subBlue ( http://www.subBlue.com/ )
	Copyright 2006 phpBB Group ( http://www.phpbb.com/ )
    ------------------------------------------------------------------------
*/

* {
	/* Reset browsers default margin, padding and font sizes */
	margin-top: 0;
}


/* General page style. The scroll bar colours only visible in IE5.5+ */
body { 
	font-family: "Lucida Grande", Verdana, Helvetica, Arial, sans-serif;
	color: {T_BODY_TEXT};
	background: #E4EDF0 url("../templates/{T_THEME}/images/bg_header.gif") 0 0 repeat-x;
	font-size: 62.5%; /* This sets the default font size to be equivalent to 10px */
	scrollbar-face-color: {T_TR_COLOR2};
	scrollbar-highlight-color: {T_TD_COLOR2};
	scrollbar-shadow-color: {T_TR_COLOR2};
	scrollbar-3dlight-color: {T_TR_COLOR3};
	scrollbar-arrow-color:  {T_BODY_LINK};
	scrollbar-track-color: {T_TR_COLOR1};
	scrollbar-darkshadow-color: {T_TH_COLOR1};
	margin-top: 0;
}

/* General font families for common tags */
font,th,td { font-size: {T_FONTSIZE2}px; }
p { margin-bottom: 1.0em; line-height: 1.5em; }
a:link,a:active,a:visited { color: {T_BODY_LINK} ;text-decoration: none; }
a:hover	{ text-decoration: underline; color: {T_BODY_HLINK}; }
hr { border: 0 none; border-top: 1px solid {T_TR_COLOR3}; margin-bottom: 5px; padding-bottom: 5px; height: 1px; }

/* This is the border line & background colour round the entire page */
.bodyline { background-color: {T_BODY_BGCOLOR}; border: 1px {T_TH_COLOR1} solid; }

/* This is the outline round the main forum tables 
.forumline { background-color: {T_TD_COLOR2}; border: 1px {T_TH_COLOR1} solid; } */

/* Main table cell colours and backgrounds */
td.row1, td.bg1	{ background-color: {T_TR_COLOR1}; }
td.row2, td.bg2	{ background-color: {T_TR_COLOR2}; }
td.row3	{ background-color: {T_TR_COLOR3}; }

/*
  This is for the table cell above the Topics, Post & Last posts on the index.php page
  By default this is the fading out gradiated silver background.
  However, you could replace this with a bitmap specific for each forum
*/
td.rowpic {
	background-color: {T_TD_COLOR2}; border: {T_TD_COLOR2}; border-style: solid; height: 28px;
	background-image: url(../templates/{T_THEME}/images/{T_TH_CLASS3});
	background-repeat: repeat-y;
}

/* Header cells - the blue and silver gradient backgrounds */
th {
	color: {T_FONTCOLOR3}; font-size: {T_FONTSIZE2}px; font-weight: bold; 
	background-color: {T_BODY_LINK};
	background-image: url(../templates/{T_THEME}/images/{T_TH_CLASS2});
}
.thsort,a.thsort:link,a.thsort:active,a.thsort:visited { color: {T_FONTCOLOR3}; font-size: {T_FONTSIZE2}px; font-weight: bold; }

td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
	font-size: {T_FONTSIZE3}px; background-image: url(../templates/{T_THEME}/images/{T_TH_CLASS1});
	background-color:{T_TR_COLOR3}; border: {T_TD_COLOR2}; border-style: solid;
}

/*
  Setting additional nice inner borders for the main table cells.
  The names indicate which sides the border will be on.
  Don't worry if you don't understand this, just ignore it :-)
*/
td.cat,td.catHead,td.catSides,td.catBottom,td.rowpic {
	height: 28px;
	border-width: 0px 0px 0px 0px;
}
th.thHead,th.thSides,th.thTop,th.thLeft,th.thRight,th.thBottom,th.thCornerL,th.thCornerR {
	font-weight: bold; border: {T_TD_COLOR2}; border-style: solid; height: 25px;
}
td.row3Right,td.spaceRow {
	background-color: {T_TR_COLOR3}; border: {T_TH_COLOR3}; border-style: solid;
}

th.thHead,td.catHead,th.thLeft,th.thRight,td.catRight,td.row3Right { font-size: {T_FONTSIZE3}px; border-width: 0px 0px 0px 0px; }
td.catLeft { font-size: {T_FONTSIZE3}px; height: 28px; border-width: 0px 0px 0px 0px; }
th.thBottom,td.catBottom,th.thSides,td.spaceRow,th.thTop,th.thCornerL,th.thCornerR { border-width: 0px 0px 0px 0px; }

/* The largest text used in the index page title, toptic title & Admin CP etc. */
h1,h2 {
	margin: 0;
	font: bold 1.8em "Lucida Grande", 'Trebuchet MS', Verdana, sans-serif;
	text-decoration: none; 
	color: {T_BODY_TEXT};
}

/* General text */
.gen { font-size: {T_FONTSIZE3}px; }
.genmed { font-size: {T_FONTSIZE2}px; }
.gensmall,.postdetails { font-size: {T_FONTSIZE1}px; }
a.gen,a.genmed,a.gensmall { color: {T_BODY_LINK}; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover { color: {T_BODY_HLINK}; text-decoration: underline; }

/* Forum category titles */
.cattitle { font-weight: bold; font-size: {T_FONTSIZE3}px ; letter-spacing: 1px; color: {T_BODY_LINK}}
a.cattitle { text-decoration: none; color: {T_BODY_LINK}; }
a.cattitle:hover{ text-decoration: underline; }

/* Forum title: Text and link to the forums used in: index.php */
.forumlink { font-weight: bold; font-size: {T_FONTSIZE3}px; color: {T_BODY_LINK}; }
a.forumlink { text-decoration: none; color : {T_BODY_LINK}; }
a.forumlink:hover { text-decoration: underline; color: {T_BODY_HLINK}; }

/* Used for the navigation text, (Page 1,2,3 etc) and the navigation bar when in a forum */
.nav { font-weight: bold; font-size: {T_FONTSIZE2}px; color: {T_BODY_TEXT};}
a.nav { text-decoration: none; color: {T_BODY_LINK}; }
a.nav:hover { text-decoration: underline; }

/* titles for the topics: could specify viewed link colour too */
.topictitle { font-weight: bold; font-size: {T_FONTSIZE3}px; color: {T_BODY_TEXT}; }
a.topictitle:link { text-decoration: none; color: {T_BODY_LINK}; }
a.topictitle:visited { text-decoration: none; color: {T_BODY_VLINK}; }
a.topictitle:hover { text-decoration: underline; color: {T_BODY_HLINK}; }

/* The content of the posts (body of text) */
.postbody { font-size: {T_FONTSIZE3}px; line-height: 18px; }
a.postlink:link	{ text-decoration: none; color: {T_BODY_LINK}; }
a.postlink:visited { text-decoration: none; color: {T_BODY_VLINK}; }
a.postlink:hover { text-decoration: underline; color: {T_BODY_HLINK}; }

/* Quote, Code & Mod blocks */
.codetitle { margin: 0px; padding: 4px; border-width: 1px 1px 0px 1px; border-style: solid; border-color: {T_TR_COLOR3}; color: {T_BODY_TEXT}; background-color: {T_TH_COLOR1}; {T_FONTSIZE1}px; }
.codecontent { margin: 0px; padding: 5px; border-color: {T_TR_COLOR3}; border-width: 0px 1px 1px 1px; border-style: solid; color: {T_FONTCOLOR2}; font-weight: normal; font-size: {T_FONTSIZE2}px; font-family: 'Courier New', monospace; background-color: {T_TD_COLOR1}; }

.quotetitle { margin: 0px; padding: 4px; border-width: 1px 1px 0px 1px; border-style: solid; border-color: {T_TR_COLOR3}; color: {T_BODY_TEXT}; background-color: {T_TH_COLOR1}; {T_FONTSIZE1}px; }
.quotecontent { margin: 0px; padding: 5px; border-color: {T_TR_COLOR3}; border-width: 0px 1px 1px 1px; border-style: solid; color: {T_BODY_TEXT}; font-weight: normal; font-size: {T_FONTSIZE2}px; font-family: {T_FONTFACE1}; background-color: {T_TD_COLOR1}; }

.mod { font-family: {T_FONTFACE1}; font-size: {T_FONTSIZE2}px; color: {T_BODY_TEXT}; line-height: 125%; }  
.exclamation { font-weight: bold; font-family: Times New Roman, Verdana; font-size : 25px; color: {T_BODY_BGCOLOR}; }  
td.modtable { background-color: {T_ADMINFONTCOLOR}; }

/* Copyright and bottom info */
.copyright { font-size: {T_FONTSIZE1}px; color: {T_FONTCOLOR1}; letter-spacing: -1px; }
a.copyright { color: {T_FONTCOLOR1}; text-decoration: none; }
a.copyright:hover { color: {T_BODY_TEXT}; text-decoration: underline; }

/* Form elements */
input,textarea, select {
	color: {T_BODY_TEXT};
	font: normal {T_FONTSIZE2}px {T_FONTFACE1};
	border-color: {T_BODY_TEXT};
	border-top-width : 1px; 
	border-right-width : 1px; 
	border-bottom-width : 1px; 
	border-left-width : 1px;  
}

/* The text input fields background colour */
input.post, textarea.post, select {
	background-color: {T_TD_COLOR2};
}

input { text-indent : 2px; }

/* The buttons used for bbCode styling in message post */
input.button {
	background-color: {T_TR_COLOR1};
	color: {T_BODY_TEXT};
	font-size: {T_FONTSIZE2}px; font-family: {T_FONTFACE1};
	border-top-width : 1px; 
	border-right-width : 1px; 
	border-bottom-width : 1px; 
	border-left-width : 1px;  
}

/* The main submit button option */
input.mainoption {
	background-color: {T_TD_COLOR1};
	font-weight: bold;
}

/* None-bold submit button */
input.liteoption {
	background-color: {T_TD_COLOR1};
	font-weight: normal;
}

/* This is the line in the posting page which shows the rollover
  help line. This is actually a text box, but if set to be the same
  colour as the background no one will know ;)
*/
.helpline { background-color: {T_TR_COLOR2}; border-style: none; }

/* Admin Hierarchie Forum Tables */
td.row1h0	{ background-color: {T_HR_COLOR1}; }
td.row2h0	{ background-color: {T_HR_COLOR2}; }
td.spaceRowh0 	{ background-color: {T_HR_COLOR2}; border: {T_TH_COLOR3}; }
td.row3h0	{ background-color: {T_HR_COLOR3}; }
td.cath0 	{ background-color: {T_HR_COLOR3}; border: {T_TD_COLOR2}; height: 28px; }

td.row1h1	{ background-color: {T_HR_COLOR4}; }
td.row2h1	{ background-color: {T_HR_COLOR5}; }
td.spaceRowh1 	{ background-color: {T_HR_COLOR5}; border: {T_TD_COLOR2}; }
td.row3h1	{ background-color: {T_HR_COLOR6}; }
td.cath1 	{ background-color: {T_HR_COLOR6}; border: {T_TD_COLOR2}; height: 28px; }

td.row1h2	{ background-color: {T_HR_COLOR7}; }
td.row2h2	{ background-color: {T_HR_COLOR8}; }
td.spaceRowh2 	{ background-color: {T_HR_COLOR8}; border: {T_TD_COLOR2}; }
td.row3h2	{ background-color: {T_HR_COLOR9}; }
td.cath2 	{ background-color: {T_HR_COLOR9}; border: {T_TD_COLOR2}; height: 28px; }

/* Remove border from hyperlinked image by default */
img, .forumline img { border: 0; }

/* Error for Admin Index */
.error { color: {T_ADMINFONTCOLOR}; }
.errorpage { padding: 10px; margin: 20px 0; text-align: center; background-color: #ECD7DA; color: #990000; }
.errorpageh1 { color: #990000; font-weight: bold; font-size: 1.5em; }
.successpage { padding: 20px; margin: 20px 0; text-align: center; background-color: #B9DBB3; color: {T_FONTCOLOR2}; }
.successpage h1 { color: {T_FONTCOLOR2}; font-weight: bold; font-size: 1.4em; margin-bottom: 0.5em; }

/* Main blocks
---------------------------------------- */
#wrap {
	padding: 0 20px 0px 20px;
	min-width: 615px;
}

#page-header {
	text-align: right;
	background: url("../templates/{T_THEME}/images/logo_acp_phpBB.gif") 0 0 no-repeat;
	height: 84px;
}

#page-header h1 {
	font-family: "Lucida Grande", Verdana, Arial, Helvetica, sans-serif;
	color: {T_BODY_TEXT};
	font-size: 1.5em;
	font-weight: normal;
}

#page-header p {
	font-size: 1.1em;
}


/* Sub-navigation Menu */
#menu {
	float: left;
	width: 100%;
	font-size: 100%;
	padding: 0;
}

.rtl #menu {
	float: right;
}

#menu p {
	font-size: 1em;
}

/* Default list state */
#menu li {
	list-style: none;
	margin: 0px 0px 0px 0px;
	text-decoration: none;
	font-size: 1em;
	font-weight: bold;
	display: inline;
}

/* Link styles for the sub-section links */
#menu li span {
	display: block;
	padding: 3px 2px 3px 5px;
	text-decoration: none;
	font-weight: bold;
	font-size: 0.9em;
	color: {T_BODY_LINK};
	background-color: {T_TR_COLOR1};
	border-top: 1px solid {T_BODY_BGCOLOR};
}

#menu li a:hover span, #menu li#activemenu span, #menu li a:hover {
	text-decoration: none;
	background-color: {T_ADMINFONTCOLOR};
	color: {T_BODY_BGCOLOR};
}

#menu li span.completed {
	text-decoration: none;
	background-color: {T_TR_COLOR1};
	color: {T_BODY_LINK};
}

#menu li.header {
	display: block;
	padding: 5px;
	font-size: 0.8em;
	font-family: "Lucida Grande", Verdana;
	color: {T_FONTCOLOR3}; 
	font-weight: bold;
	background: {T_BODY_LINK} url("../templates/{T_THEME}/images/{T_TH_CLASS2}") 0 0 repeat-x;
	margin-top: 5px;
	text-transform: uppercase;
}

#menu li#activemenu a span {
	text-decoration: none;
	font-weight: bold;
	color: {T_BODY_TEXT};
	background-color: {T_TR_COLOR2};
}

#menu li#activemenu a:hover span {
	text-decoration: none;
	color: {T_BODY_TEXT};
}

-->
</style>
<script language="JavaScript" type="text/javascript">
<!-- 
function seturl(url) 
{
	opener.document.form.dlurl.value = url;
}

function setframe(url)
{
	parent.frames.main.location = url;
}

function change_page(targ,selObj,restore)
{
     eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
     if (restore) selObj.selectedIndex=0;
} 

fadeClasses = new Array(""); // Determines which classes get which colors
startColors = new Array("{T_BODY_LINK}"); // MouseOut link colors 
endColors = new Array("{T_BODY_HLINK}"); // MouseOver link color
stepIn = 20; // delay when fading in
stepOut = 20; // delay when fading out
autoFade = true;
sloppyClass = true;

{AJAX_JS}
//-->
</script>
<script language="javascript1.2" src="../includes/AJAXed_config.php"></script>
<script language="javascript1.2" src="../includes/AJAXed_func.js"></script>
<script language="JavaScript" type="text/javascript" src="../templates/js/fade.js"></script>
</head>
<body topmargin="0">
<div id="loading" style="position:absolute;font-family:arial,sans-serif;background:#0070EA;color:white;font-size:75%;top:0;right:0;"></div>
<div id="misc" style="position:absolute;left:0px;top:0px;z-index:100;"></div>
<a name="top"></a>

<div id="wrap">
	<div id="page-header"><br />
		<h1>{PAGE_TITLE}</h1>
		<p><a href="{U_ADMIN_INDEX}" class="genmed">{L_ADMIN_INDEX}</a> &bull; <a href="{U_INDEX}" class="genmed">{L_FORUM_INDEX}</a> &bull; 
		<!-- BEGIN switch_lite_on -->
		<a href="{switch_lite_on.U_LITE_INDEX}" class="genmed">{switch_lite_on.L_LITE_INDEX}</a> &bull; 
		<!-- END switch_lite_on -->
		<a href="{U_PORTAL_INDEX}" class="genmed">{L_PORTAL_INDEX}</a></p>
		<p><span class="gensmall">{S_CURRENT_TIME}</span></p>
	</div>
</div>
<table align="center" width="98%" cellpadding="5" cellspacing="0">
<tr> 
	<td class="bodyline"><table width="100%" cellpadding="5" cellspacing="0">
  	<tr> 
		<td height="1" class="gensmall">{L_LOGGED_IN_AS}:<br /><b>{S_ADMIN_USERNAME}</b> [ <a href="{U_LOGOUT}">{L_LOGOUT}</a> ]
		<!-- BEGIN switch_admin_login -->
		[ <a href="{switch_admin_login.U_ADMIN_LOGOUT}">{switch_admin_login.L_ADMIN_LOGOUT}</a> ]
		<!-- END switch_admin_login -->
		</td>
		<td height="1" align="right" class="gensmall">{ACP_CONFIG_MENU}</td>
	</tr>
	<tr>
		<td width="20%" valign="top" align="left">
		<div id="menu">
		{QUICK_MENU}

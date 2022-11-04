<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}"  />
<meta http-equiv="Content-Style-Type" content="text/css" />
{META_HTTP_EQUIV_TAG}
<meta name="resource-type" content="document" />
<meta http-equiv="imagetoolbar" content="no" /> 
<title>{SITENAME} &bull; {PAGE_TITLE}</title>
{META_TAG}
{META}
{IM_META}
{PREFS_TABS}
<link rel="shortcut icon" href="{HOMEPAGE}images/favicon.ico" type="image/x-icon" />
<style type="text/css">
<!--
/*
  The original subSilver Theme for phpBB version 2+
  Created by subBlue design
  http://www.subBlue.com
*/

/* General page style. The scroll bar colours only visible in IE5.5+ */
body { 
	background-color: {T_BODY_BGCOLOR};
}

/* General font families for common tags */
font,th,td,p,ul,li { font-size: {T_FONTSIZE2}px; color: {T_BODY_TEXT}; font-family: {T_FONTFACE1} }
a:link,a:active,a:visited { color : {T_BODY_LINK}; }
a:hover	{ text-decoration: underline; color : {T_BODY_HLINK}; }
hr { height: 0px; border: solid {T_TR_COLOR3} 0px; border-top-width: 1px;}

/* This is the border line & background colour round the entire page */
.bodyline { background-color: {T_BODY_BGCOLOR}; border: 1px {T_TH_COLOR1} solid; }

/* This is the outline round the main forum tables */
.forumline { background-color: {T_TD_COLOR2}; border: 1px {T_TH_COLOR1} solid; }

/* Main table cell colours and backgrounds */
td.row1	{ background-color: {T_TR_COLOR1}; }
td.row2	{ background-color: {T_TR_COLOR2}; }
td.row3	{ background-color: {T_TR_COLOR3}; }

/* Main calendar cell colours and backgrounds */
td.daterow2 { background-color: #E0E4E8; padding-left: 1px; padding-right: 1px; border: 2px solid #E0E4E8; }
td.daterow3 { background-color: #D0D0D8; padding-left: 1px; padding-right: 1px; border: 2px solid #E0E4E8; }
td.daterowtoday { background-color: {T_BODY_LINK}; padding-left: 1px; padding-right: 1px; border: 2px solid #E0E4E8; }

.block_start { text-align: left; border-left: 1px solid #F0F0F0; }
.block_single { text-align: left; border-left: 1px solid #F0F0F0; border-right: 1px solid #F0F0F0; }
.block_end { text-align: left; border-right: 1px solid #F0F0F0; }

.transparent { font-size: {T_FONTSIZE1}px; color: #F0F0F0; text-align: left; }

/*
  This is for the table cell above the Topics, Post & Last posts on the index.php page
  By default this is the fading out gradiated silver background.
  However, you could replace this with a bitmap specific for each forum
*/
td.rowpic {
	background-color: {T_TD_COLOR2}; border: {T_TD_COLOR2}; border-style: solid; height: 28px;
	background-image: url({HOMEPAGE}templates/{T_THEME}/images/{T_TH_CLASS3});
	background-repeat: repeat-y;
}

/* Header cells - the blue and silver gradient backgrounds */
th {
	color: {T_FONTCOLOR3}; font-size: {T_FONTSIZE2}px; font-weight: bold; 
	background-color: {T_BODY_LINK};
	background-image: url({HOMEPAGE}templates/{T_THEME}/images/{T_TH_CLASS2});
}
.thsort,a.thsort:link,a.thsort:active,a.thsort:visited { color: {T_FONTCOLOR3}; font-size: {T_FONTSIZE3}px; font-weight: bold; }

td.cat,td.catHead,td.catSides,td.catLeft,td.catRight,td.catBottom {
	font-size: {T_FONTSIZE3}px; background-image: url({HOMEPAGE}templates/{T_THEME}/images/{T_TH_CLASS1});
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

/* The largest text used in the header title, toptic title & Admin CP etc. */
.maintitle,h1,h2 {
	font-weight: bold; font-size: 22px; font-family: "{T_FONTFACE2}",{T_FONTFACE1};
	text-decoration: none; line-height: 120%; color: {T_BODY_TEXT};
}

/* New Messages */ 
.pm { font-size: {T_FONTSIZE2}px; text-decoration: none; font-weight: bold; color: {T_FONTCOLOR1}; }

/* General text */
.gen { font-size: {T_FONTSIZE3}px; }
.genmed { font-size: {T_FONTSIZE2}px; }
.gensmall { font-size: {T_FONTSIZE1}px; }
.gen,.genmed,.gensmall { color: {T_BODY_TEXT}; }
a.gen,a.genmed,a.gensmall { color: {T_BODY_LINK}; text-decoration: none; }
a.gen:hover,a.genmed:hover,a.gensmall:hover { color: {T_BODY_HLINK}; text-decoration: underline; }

/* The register, login, search etc links at the top of the page */
.mainmenu { font-size : {T_FONTSIZE2}px; color: {T_BODY_TEXT} }
a.mainmenu { text-decoration: none; color: {T_BODY_LINK};  }
a.mainmenu:hover{ text-decoration: underline; color: {T_BODY_HLINK}; }

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

/* Name of poster in viewmsg.php and viewtopic.php and other places */
.name { font-size: {T_FONTSIZE2}px; color: {T_BODY_TEXT};}

/* Location, number of posts, post date etc */
.postdetails { font-size: {T_FONTSIZE1}px; color: {T_BODY_TEXT}; }

/* The content of the posts (body of text) */
.postbody { font-size: {T_FONTSIZE3}px; line-height: 18px; }
a.postlink:link	{ text-decoration: none; color: {T_BODY_LINK}; }
a.postlink:visited { text-decoration: none; color: {T_BODY_VLINK}; }
a.postlink:hover { text-decoration: underline; color: {T_BODY_HLINK}; }

/* Quote, Code, Pre & Mod blocks */
.codetitle { margin: 0px; padding: 4px; border-width: 1px 1px 0px 1px; border-style: solid; border-color: {T_TR_COLOR3}; color: {T_BODY_TEXT}; background-color: {T_TH_COLOR1}; font-size: {T_FONTSIZE1}px; }
.codecontent { margin: 0px; padding: 5px; border-color: {T_TR_COLOR3}; border-width: 0px 1px 1px 1px; border-style: solid; color: {T_FONTCOLOR2}; font-weight: normal; font-size: {T_FONTSIZE2}px; font-family: 'Courier New', monospace; background-color: {T_TD_COLOR1}; }

.quotetitle { margin: 0px; padding: 4px; border-width: 1px 1px 0px 1px; border-style: solid; border-color: {T_TR_COLOR3}; color: {T_BODY_TEXT}; background-color: {T_TH_COLOR1}; font-size: {T_FONTSIZE1}px; }
.quotecontent { margin: 0px; padding: 5px; border-color: {T_TR_COLOR3}; border-width: 0px 1px 1px 1px; border-style: solid; color: {T_BODY_TEXT}; font-weight: normal; font-size: {T_FONTSIZE2}px; font-family: {T_FONTFACE1}; background-color: {T_TD_COLOR1}; }

pre { font-family: {T_FONTFACE3}; font-size: {T_FONTSIZE3}px; }

.mod { font-family: {T_FONTFACE1}; font-size: {T_FONTSIZE2}px; color: {T_BODY_TEXT}; line-height: 125%; }  
.exclamation { font-weight: bold; font-family: Times New Roman, Verdana; font-size : 25px; color: {T_BODY_BGCOLOR}; }  
td.ModTable { background-color: {T_ADMINFONTCOLOR}; }

/* Copyright and bottom info */
.copyright { font-size: {T_FONTSIZE1}px; font-family: {T_FONTFACE1}; color: {T_FONTCOLOR1}; letter-spacing: -1px; }
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

/* Hierarchie colors for jumpbox */
option.h0	{ background-color: {T_TD_COLOR2}; color: {T_JB_COLOR1}; }
option.h0c	{ background-color: {T_TD_COLOR2}; color: {T_JB_COLOR1}; }
option.h0sf	{ background-color: {T_TD_COLOR2}; color: {T_JB_COLOR1}; }

option.h1	{ background-color: {T_TD_COLOR2}; color: {T_JB_COLOR2}; }
option.h1c	{ background-color: {T_TD_COLOR2}; color: {T_JB_COLOR2}; }
option.h1sf	{ background-color: {T_TD_COLOR2}; color: {T_JB_COLOR2}; }

option.h2	{ background-color: {T_TD_COLOR2}; color: {T_JB_COLOR3}; }
option.h2c	{ background-color: {T_TD_COLOR2}; color: {T_JB_COLOR3}; }
option.h2sf	{ background-color: {T_TD_COLOR2}; color: {T_JB_COLOR3}; }

select.jumpbox 	{ background-color: {T_TD_COLOR2}; }

/* Slideshow image border */
.imageborder { color: {T_FONTCOLOR1}; border-color: {T_FONTCOLOR1}; }

/* Remove border from hyperlinked image by default */
img, .forumline img { border: 0; }
-->
</style>
<link rel="stylesheet" href="{HOMEPAGE}templates/{T_THEME}/prillian/layout.css" type="text/css">

<!--[if gte IE 5]>
<style type="text/css">
body { 
	scrollbar-face-color: {T_TR_COLOR2};
	scrollbar-highlight-color: {T_TD_COLOR2};
	scrollbar-shadow-color: {T_TR_COLOR2};
	scrollbar-3dlight-color: {T_TR_COLOR3};
	scrollbar-arrow-color:  {T_BODY_LINK};
	scrollbar-track-color: {T_TR_COLOR1};
	scrollbar-darkshadow-color: {T_TH_COLOR1};
}

pre { 
	white-space: pre-wrap; 
	white-space: -moz-pre-wrap; 
	white-space: -pre-wrap; 
	white-space: -o-pre-wrap; 
	white-space: normal; 
	word-wrap: break-word; 
	width="100%"; 
}
</style>
<![endif]-->

<script language="JavaScript" type="text/javascript">
<!--
fadeClasses = new Array(""); // Determines which classes get which colors
startColors = new Array("{T_BODY_LINK}"); // MouseOut link colors 
endColors = new Array("{T_BODY_HLINK}"); // MouseOver link color
stepIn = 20; // delay when fading in
stepOut = 20; // delay when fading out
autoFade = true;
sloppyClass = true;

var win = null;
function Gk_PopTart(mypage,myname,w,h,scroll)
{
  	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
  	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
  	settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable';
  	win = window.open(mypage,myname,settings);
}
function Trophy_Popup(mypage,myname,w,h,scroll)
{
  	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
  	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
  	settings = 'height='+h+',width='+w+',top='+TopPosition+',left='+LeftPosition+',scrollbars='+scroll+',resizable';
  	win = window.open(mypage,myname,settings);
}

//-->
</script>
<!-- BEGIN buddy_alert -->
<script language="Javascript" type="text/javascript">
<!--
if ( {buddy_alert.BUDDY_ALERT} )
{
	window.open('{buddy_alert.U_BUDDY_ALERT}', '_buddyalert', 'height=225, resizable=yes, width=400');
}
//-->
</script>
<!-- END buddy_alert -->
<script language="JavaScript" type="text/javascript" src="{HOMEPAGE}templates/js/fade.js"></script>
</head>
<body bgcolor="{T_BODY_BGCOLOR}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}" {IM_RELOAD}>
<a name="top"></a>
<div align="center">{CUSTOM_SIMPLE_HEADER}</div>

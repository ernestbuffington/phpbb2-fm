<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{S_CONTENT_DIRECTION}" XMLNS:MYWEBSITE>
<head>
{PAGE_TRANSITION}
<meta http-equiv="Content-Type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="Content-Style-Type" content="text/css" />
{META_HTTP_EQUIV_TAG}
<meta name="resource-type" content="document" />
<meta http-equiv="imagetoolbar" content="no" />
<title>{SITENAME} &bull; {PAGE_TITLE} [ {TOP_USERNAME} ]</title>
{META_TAG}
{META}
{NAV_LINKS}
{PROFILE_VIEW}
{GREETING_POPUP}
{L_CUSTOM_HEADER}
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
	{T_BODY_BACKGROUND}
}

/* General font families for common tags */
font,th,td,p,ul,li { font-size: {T_FONTSIZE2}px; color: {T_BODY_TEXT}; font-family: {T_FONTFACE1}; }
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
.thsort,a.thsort:link,a.thsort:active,a.thsort:visited { color: {T_FONTCOLOR3}; font-size: {T_FONTSIZE3}px; font-weight: bold; }
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
a.postdetails:link { text-decoration: none; color: {T_BODY_LINK}; }
a.postdetails:visited { text-decoration: none; color: {T_BODY_VLINK}; }
a.postdetails:hover { text-decoration: underline; color: {T_BODY_HLINK}; }

/* The content of the posts (body of text) */
.postbody { font-size: {T_FONTSIZE3}px; line-height: 18px; }
a.postlink:link	{ text-decoration: none; }
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
td.modtable { background-color: {T_ADMINFONTCOLOR}; }

/* Copyright and bottom info */
.copyright { font-size: {T_FONTSIZE1}px; font-family: {T_FONTFACE1}; color: {T_FONTCOLOR1}; letter-spacing: -1px; }
a.copyright { color: {T_FONTCOLOR1}; text-decoration: none; }
a.copyright:hover { color: {T_BODY_TEXT}; text-decoration: underline; }

/* Lexicon navigation */
.letter { font: bold 10pt Verdana; text-decoration: none; }
.letter:hover { text-decoration: underline }
.letter2 { font: bold 11pt Verdana; text-decoration: none; }
.letter3 { font: bold 10pt Verdana; color:#C0C0C0; }

/* Lexicon category */
.categorie { font: 7pt Verdana; color: #B0B0F0; text-decoration: none; }

/* Lexicon crosslinks */
.crosslink { color: {T_BODY_TEXT}; }
a.crosslink:link { text-decoration: none; color: #777755; }
a.crosslink:visited { text-decoration: none; color: #777755; } 
a.crosslink:hover { text-decoration: none; color: #773366; }


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

<script language="JavaScript" type="text/javascript"> 
<!--
function MakeHomepage()
{
	oHomePage.setHomePage("{HOMEPAGE}");
}

function openRadioPopup()
{ 
	day = new Date(); 
	id = day.getTime();
	eval('page' + id + ' = window.open("mods/radio/index.php", "' + id + '", "toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=500,height=300");');
} 

var AMOUNT = 2; // distance to scroll for each time
var TIME = 5; // milliseconds  
var timer = null;  
function scrollIt(v)  
{  
	var direction=v?1:-1;  
	var distance=AMOUNT*direction;  
	window.scrollBy(0,distance);  
}  
function down(v)  
{ 
	if(timer) 
	{ 
		clearInterval(timer); timer=null; 
	}  
  	if(v)timer=setInterval("scrollIt(true)",TIME);  
}  

function notes() 
{ 
	window.open("profile_notes.php", "_notes", "width=800, height=600, scrollbars, resizable=yes");
} 

function tour() 
{ 
 	window.open("tour.php", "_tour", "width=800, height=600, scrollbars, resizable=yes");
}

fadeClasses = new Array(""); // Determines which classes get which colors
startColors = new Array("{T_BODY_LINK}"); // MouseOut link colors 
endColors = new Array("{T_BODY_HLINK}"); // MouseOver link color
stepIn = 20; // delay when fading in
stepOut = 20; // delay when fading out
autoFade = true;
sloppyClass = true;
window.status="{SITENAME} :: {PAGE_TITLE} [ {TOP_USERNAME} ]"

var win = null;
function Gk_PopTart(mypage,myname,w,h,scroll)
{
  	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
  	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
  	settings = 'height='+h+',width='+w+', top='+TopPosition+', left='+LeftPosition+', scrollbars='+scroll+', resizable';
  	win = window.open(mypage, myname, settings);
}
function Trophy_Popup(mypage,myname,w,h,scroll)
{
  	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0;
  	TopPosition = (screen.height) ? (screen.height-h)/2 : 0;
  	settings = 'height='+h+',width='+w+', top='+TopPosition+', left='+LeftPosition+', scrollbars='+scroll+', resizable';
  	win = window.open(mypage, myname, settings);
}

function getWallpaper()
{
	url = "http://www.gamewallpapers.com/wallpaperoftheday/getpreview.php";
	ipwindow = window.open(url, "_blank", "fullscreen=no, resizable=no, scrollbars=no, toolbar=no, height=316, width=400");
}
//-->
</script>

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

<style>
@media all{MYWEBSITE\:HOMEPAGE {behavior:url(#default#homepage)}}
</style>
<![endif]-->

<!-- BEGIN switch_enable_pm_popup --> 
<script language="Javascript" type="text/javascript"> 
<!-- 
var win = null; 
function phpBBPOPUP(mypage, myname, w, h, scroll)
{ 
	LeftPosition = (screen.width) ? (screen.width-w)/2 : 0; 
	TopPosition = (screen.height) ? (screen.height-h)/2 : 0; 
	settings = 'height='+h+', width='+w+', top='+TopPosition+', left='+LeftPosition+', scrollbars='+scroll+', resizable'; 
	win = window.open(mypage, myname, settings);
} 
if ( {PRIVATE_MESSAGE_NEW_FLAG} ) 
{ 
	phpBBPOPUP('{U_PRIVATEMSGS_POPUP}', '_phpbbprivmsg', '400', '225', 'no');
}
//--> 
</script> 
<!-- END switch_enable_pm_popup -->
<!-- BEGIN switch_prillian_on -->
<script language="JavaScript" type="text/javascript">
<!--
function prill_launch(url, w, h)
{
	window.name = '_prillmain';
	prillian = window.open(url, 'prillian', 'height='+h+', width='+w+', innerWidth='+w+', innerHeight='+h+', resizable, scrollbars');
}

if ( {IM_AUTO_POPUP} ) 
{ 
	prill_launch('{U_IM_LAUNCH}', '{IM_WIDTH}', '{IM_HEIGHT}');
} 
//-->
</script>
<!-- END switch_prillian_on -->
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
<!-- BEGIN switch_ajax -->
<script language="Javascript" type="text/javascript">
<!--
{AJAX_JS}
-->
</script>
<script language="javascript1.2" src="{HOMEPAGE}includes/AJAXed_config.php"></script>
<script language="javascript1.2" src="{HOMEPAGE}includes/AJAXed_func.js"></script>
<!-- END switch_ajax -->
<script language="JavaScript" type="text/javascript" src="{HOMEPAGE}templates/js/fade.js"></script>
</head>
<MYWEBSITE:HOMEPAGE ID=oHomePage />
<body topmargin="0" leftmargin="20" rightmargin="20" bgcolor="{T_BODY_BGCOLOR}" text="{T_BODY_TEXT}" link="{T_BODY_LINK}" vlink="{T_BODY_VLINK}"{L_CUSTOM_BODY}>
<div id="loading" style="position:absolute;font-family:arial,sans-serif;background:#0070EA;color:white;font-size:75%;top:0;right:0;"></div>
<div id="misc" style="position:absolute;left:0px;top:0px;z-index:100;"></div>
<a name="top"></a>
{L_CUSTOM_BODY_HEADER}
<table width="100%" cellspacing="0" cellpadding="0" align="center">
<tr> 
	<td><table width="100%" align="center" cellspacing="0" cellpadding="0">
		<tr> 
			<td height="70" valign="top"><a href="{LOGO_URL}"><img src="{T_LOGO}" alt="{SITENAME}" title="{SITENAME}" /></a></td>
			<form method="post" action="{S_LOGIN_ACTION}">						
			<td width="100%" valign="top" align="right">
			<!-- BEGIN switch_user_logged_out -->
			<span class="gensmall">{L_USERNAME}:&nbsp;<input class="post" type="text" name="username" size="10" maxlength="{LIMIT_USERNAME_MAX_LENGTH}" />&nbsp;&nbsp;{L_PASSWORD}:&nbsp;<input class="post" type="password" name="password" size="10" maxlength="32" /><br />
			<!-- BEGIN switch_allow_autologin -->
			{L_AUTO_LOGIN}</span>&nbsp;<input class="text" type="checkbox" name="autologin"{AUTOLOGIN_CHECKED} />&nbsp;
			<!-- END switch_allow_autologin -->
			<input type="submit" class="mainoption" name="login" value="{L_LOGIN}" />
			<!-- END switch_user_logged_out -->
			<!-- BEGIN switch_user_logged_in -->
			<br /><br />
			<!-- END switch_user_logged_in -->
			<div align="center"><span class="maintitle">{SITENAME}</span><br /><span class="gen"><i>{SITE_DESCRIPTION}</i></span><br />&nbsp;
			<!-- BEGIN switch_user_logged_in --> 
			<br />&nbsp;
			<!-- END switch_user_logged_in -->
			</div></td>
			</form>
		</tr>
		<tr>
			<td height="30" valign="top" nowrap="nowrap" class="mainmenu"><a href="javascript:MakeHomepage()" class="mainmenu" title="{L_MAKEHOMEPAGE}">{L_MAKEHOMEPAGE}</a> &bull;
			<script language="Javascript" type="text/javascript"> 
			<!--
			if (navigator.appName == 'Microsoft Internet Explorer' && parseInt(navigator.appVersion) >= 4)
			{
				document.write('<a href=\"#\" onclick=\"javascript:window.external.AddFavorite(location.href,document.title)\" title=\"{L_ADDTOFAVORITES}\" class=\"mainmenu\">');
				document.write('{L_ADDTOFAVORITES}</a>');
			}
			else
			{
				var msg = "{L_ADDTOFAVORITES}";
				if(navigator.appName == "Netscape") msg += "  (CTRL+D)";
				document.write(msg);
			}
			// -->
			</script>
			</td>
			<td align="right" valign="top" nowrap="nowrap">
			<!-- BEGIN switch_admin_logged_in --> 
			&nbsp;<a href="{switch_admin_logged_in.U_ADMINCP}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_admin.gif" alt="{switch_admin_logged_in.L_ADMINCP}" title="{switch_admin_logged_in.L_ADMINCP}" hspace="3" />{switch_admin_logged_in.L_ADMINCP}</a>&nbsp;
			<!-- END switch_admin_logged_in --> 
			<!-- BEGIN switch_user_logged_in -->
			&nbsp;<a href="{U_USERCP}" title="{L_USERCP}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_profile.gif" alt="{L_USERCP}" title="{L_USERCP}" hspace="3" />{L_USERCP}</a>&nbsp;&nbsp;<a href="{U_PRIVATEMSGS}" title="{L_PRIVATEMSGS}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_message.gif" alt="{L_PRIVATEMSGS}" title="{L_PRIVATEMSGS}" hspace="3" />{PRIVATE_MESSAGE_INFO}</a>&nbsp; &nbsp;<a href="{U_LOGIN_LOGOUT}" title="{L_LOGIN_LOGOUT}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_login.gif" alt="{L_LOGIN_LOGOUT}" title="{L_LOGIN_LOGOUT}" hspace="3" />{L_LOGIN_LOGOUT}</a>&nbsp;
			<!-- END switch_user_logged_in -->
			<!-- BEGIN switch_user_logged_out -->
			&nbsp;<a href="{U_REGISTER}" class="mainmenu" title="{L_REGISTER}"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_register.gif" alt="{L_REGISTER}" title="{L_REGISTER}" hspace="3" />{L_REGISTER}</a>&nbsp;
			<!-- END switch_user_logged_out -->
			</td>
		</tr>
		</table>
		<table width="99%" align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td nowrap valign="top" class="gensmall">
			<!-- BEGIN switch_user_logged_in -->
			{LAST_VISIT_DATE}<br />
			<!-- END switch_user_logged_in -->
			{POINTS}</td>
			<td class="gensmall" align="right" valign="top">{CURRENT_TIME}<br />{S_TIMEZONE}</td>
		</tr>
		<tr>
			<td colspan="2"><img src="images/spacer.gif" height="5" alt="" title="" /></td>
		</tr>
		<tr>
			<td height="30" colspan="2" align="center" class="mainmenu">
            		<!-- BEGIN switch_lite_on -->
            		&nbsp;<a href="{switch_lite_on.U_LITE}" title="{switch_lite_on.L_LITE}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_forums.gif" alt="{switch_lite_on.L_LITE}" title="{switch_lite_on.L_LITE}" hspace="3" />{switch_lite_on.L_LITE}</a>&nbsp;
            		<!-- END switch_lite_on -->
			<!-- BEGIN switch_menu -->
			&nbsp;<a href="{switch_menu.U_URL}"{switch_menu.EXTRA} title="{switch_menu.L_LINK}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/{switch_menu.IMAGE}" alt="{switch_menu.L_LINK}" title="{switch_menu.L_LINK}" hspace="3" />{switch_menu.L_LINK}</a>&nbsp;
			<!-- END switch_menu -->
			<!-- BEGIN switch_search_on -->
			&nbsp;<a href="{switch_search_on.U_SEARCH}" title="{switch_search_on.L_SEARCH}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_search.gif" alt="{switch_search_on.L_SEARCH}" title="{switch_search_on.L_SEARCH}" hspace="3" />{switch_search_on.L_SEARCH}</a>&nbsp;
			<!-- END switch_search_on -->
			<!-- BEGIN switch_jobs_on -->
			&nbsp;<a href="{switch_jobs_on.U_JOBS}" title="{switch_jobs_on.L_JOBS}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_jobs.gif" alt="{switch_jobs_on.L_JOBS}" title="{switch_jobs_on.L_JOBS}" hspace="3" />{switch_jobs_on.L_JOBS}</a>&nbsp;
			<!-- END switch_jobs_on -->
			<!-- BEGIN switch_bank_on -->
			&nbsp;<a href="{switch_bank_on.U_BANK}" title="{switch_bank_on.L_BANK}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_bank.gif" alt="{switch_bank_on.L_BANK}" title="{switch_bank_on.L_BANK}" hspace="3" />{switch_bank_on.L_BANK}</a>&nbsp;
			<!-- END switch_bank_on -->
			<!-- BEGIN switch_lottery_on -->
			&nbsp;<a href="{switch_lottery_on.U_LOTTERY}" title="{switch_lottery_on.L_LOTTERY}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_lottery.gif" alt="{switch_lottery_on.L_LOTTERY}" title="{switch_lottery_on.L_LOTTERY}" hspace="3" />{switch_lottery_on.L_LOTTERY}</a>&nbsp;
			<!-- END switch_lottery_on -->
            		<!-- BEGIN switch_meeting_on -->
			&nbsp;<a href="{switch_meeting_on.U_MEETING_LINK}" title="{switch_meeting_on.L_MEETING_LINK}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_profile.gif" alt="{switch_meeting_on.L_MEETING_LINK}" title="{switch_meeting_on.L_MEETING_LINK}" hspace="3" />{switch_meeting_on.L_MEETING_LINK}</a>
            		<!-- END switch_meeting_on -->
            		<!-- BEGIN switch_chatroom_on -->
			&nbsp;<a href="{switch_chatroom_on.U_CHATLINK}" title="{switch_bank_on.L_CHATLINK}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_chatroom.gif" alt="{switch_lottery_on.L_CHATLINK}" title="{switch_lottery_on.L_CHATLINK}" hspace="3" />{switch_chatroom_on.L_CHATLINK}</a>
            		<!-- END switch_chatroom_on -->
			<!-- BEGIN switch_auctions_on -->
			&nbsp;<a href="{switch_auctions_on.U_AUCTIONS}" title="{switch_auctions_on.L_AUCTIONS}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_favorites.gif" alt="{switch_auctions_on.L_AUCTIONS}" title="{switch_auctions_on.L_AUCTIONS}" hspace="3" />{switch_auctions_on.L_AUCTIONS}</a>&nbsp;
			<!-- END switch_auctions_on -->
			<!-- BEGIN switch_avatartoplist_on -->
			&nbsp;<a href="{switch_avatartoplist_on.U_AVATAR_TOPLIST}" title="{switch_avatartoplist_on.L_AVATAR_TOPLIST}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_reports.gif" alt="{switch_avatartoplist_on.L_AVATAR_TOPLIST}" title="{switch_avatartoplist_on.L_AVATAR_TOPLIST}" hspace="3" />{switch_avatartoplist_on.L_AVATAR_TOPLIST}</a>&nbsp;
			<!-- END switch_avatartoplist_on -->
			<!-- BEGIN switch_prillian_on -->
			&nbsp;<a href="{U_IM_LAUNCH}" target="prillian" onClick="javascript:prill_launch('{U_IM_LAUNCH}', '{IM_MAIN_WIDTH}', '{IM_MAIN_HEIGHT}'); return false" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="templates/{T_NAV_STYLE}/menu/icon_mini_register.gif" alt="{L_IM_LAUNCH}" hspace="3" />{L_IM_LAUNCH}</a>&nbsp;
			<!-- END switch_prillian_on -->
            		<!-- BEGIN switch_invisible_on -->
            		&nbsp;<a href="{switch_invisible_on.U_SWITCH_ONLINE_STATUS}" title="{switch_invisible_on.L_SWITCH_VIEWONLINE}" class="mainmenu"><img style="vertical-align:middle; margin:2px" src="{switch_invisible_on.ONLINE_SWITCH_IMG}" alt="{switch_invisible_on.L_SWITCH_VIEWONLINE}" hspace="3" />{switch_invisible_on.L_SWITCH_VIEWONLINE}</a>&nbsp;
            		<!-- END switch_invisible_on -->
			</td>
		</tr>
		</table>
		<table width="99%" align="center" cellspacing="0" cellpadding="0">
		<tr>
			<td align="center">{BANNER_0_IMG}</td>
		</tr>
		<tr>
			<td align="center">{BANNER_1_IMG}{BANNER_2_IMG}{BANNER_3_IMG}{BANNER_4_IMG}{BANNER_5_IMG}{BANNER_6_IMG}</td>
		</tr>
		<tr>
			<td align="center">{CUSTOM_OVERALL_HEADER}</td>
		</tr>
		</table>
		<!-- BEGIN switch_lw_user_logged_in -->
		<div align="center" class="mainmenu">{L_LW_EXPIRE_REMINDER}</div>
		<!-- END switch_lw_user_logged_in -->
		<!-- BEGIN board_disable -->
		<div class="forumline" style="padding: 10px; margin: 5px 2px; text-align: center"><span class="gen">{board_disable.MSG}</span></div>
		<!-- END board_disable -->

<?php
/***************************************************************************
 *                                virgo.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: virgo.php,v 1.99.2.3 2004/07/11 16:46:15 acydburn Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true);
$phpbb_root_path = '../../';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="' . $lang['DIRECTION'] . '">
<meta http-equiv="Content-Type" content="text/html; charset=' . $lang['ENCODING'] . '" />
<meta http-equiv="Content-Style-Type" content="text/css">
<style type="text/css">
<!--
/* General page style. The scroll bar colours only visible in IE5.5+ */
body { 
	background-color: #' . $theme['body_bgcolor'] . ';
	scrollbar-face-color: #' . $theme['tr_color2'] . ';
	scrollbar-highlight-color: #' . $theme['td_color2'] . ';
	scrollbar-shadow-color: #' . $theme['tr_color2'] . ';
	scrollbar-3dlight-color: #' . $theme['tr_color3'] . ';
	scrollbar-arrow-color: #' . $theme['body_link'] . ';
	scrollbar-track-color: #' . $theme['tr_color1'] . ';
	scrollbar-darkshadow-color: #' . $theme['th_color1'] . ';
}

/* General font families for common tags */
font,th,td,p { font-size: ' . $theme['fontsize2'] . 'px; color: #' . $theme['body_text'] . '; font-family: ' . $theme['fontface1'] . ' }
a:link,a:active,a:visited { color : #' . $theme['body_link'] . '; }
a:hover	{ text-decoration: underline; color : #' . $theme['body_hlink'] . '; }

/* This is the outline round the main forum tables */
.forumline { background-color: #' . $theme['td_color2'] . '; border: 1px #' . $theme['th_color1'] . ' solid; }

/* Main table cell colours and backgrounds */
td.row1	{ background-color: #' . $theme['tr_color1'] . '; }

/* Header cells - the blue gradient background */
th {
	color: #' . $theme['fontcolor3'] . '; font-size: ' . $theme['fontsize2'] . 'px; font-weight: bold; 
	background-color: #' . $theme['body_link'] . ';
	background-image: url(../../templates/' . $theme['template_name'] . '/images/' . $theme['th_class2'] . ');
}
th.thHead {
	font-weight: bold; border: #' . $theme['td_color2'] . '; border-style: solid; height: 25px;
	font-size: ' . $theme['fontsize3'] . 'px; border-width: 0px 0px 0px 0px; }

/* General text */
.gen { font-size: ' . $theme['fontsize3'] . 'px; }
.genmed { font-size: ' . $theme['fontsize2'] . 'px; }
.gen,.genmed { color: #' . $theme['body_text'] . '; }
a.genmed { color: #' . $theme['body_link'] . '; text-decoration: none; }
a.genmed:hover { color: #' . $theme['body_hlink'] . '; text-decoration: underline; }

-->
</style>
<title>' . $board_config['sitename'] . ' :: ' . $lang['Zodiac'] . ' - ' . $lang['Virgo'] . '</title>
<body>	
<table class="forumline" width="100%" cellspacing="1" cellpadding="4">
<tr>
	<th class="thHead">' . $lang['Virgo'] . '</th>
</tr>
<tr> 
	<td class="row1"><table width="100%" cellspacing="0" cellpadding="1">
	<tr> 
		<td>&nbsp;</td>
	</tr>
	<tr> 
		<td align="center" class="gen">';

require('phpHoroscope.class.php');  // full path to phpHoroscope (required)

$horoscope = new phpHoroscope;      // start phpHoroscope (required)
$horoscope->setSign('virgo');       // set valid sign (required)
$horoscope->setDate('today');       // set valid date (required) (options: today|yesterday|tomorrow)
$horoscope->setExtended();          // toggles to extended results (optional)
$horoscope->displayHoroscope();     // display horoscope results


echo '<br /><br /><a href="javascript:window.close()" class="genmed">' . $lang['Close_window'] . '</a></td>
	</tr>
	<tr> 
		<td>&nbsp;</td>
	</tr>
	</table></td>
</tr>
</table>
<br />
</body>
</html>';

?>
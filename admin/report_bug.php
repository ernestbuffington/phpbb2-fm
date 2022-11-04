<?php
/** 
*
* @package admin
* @version $Id: report_bug.php,v 1.00 2007/12/18 MJ Exp $
* @copyright (c) 2007 MJ, Fully Modded phpBB
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<style type="text/css">
<!--
body { 
	background-color: #'.$theme['body_bgcolor'].';
	scrollbar-face-color: #'.$theme['tr_color2'].';
	scrollbar-highlight-color: #'.$theme['td_color2'].';
	scrollbar-shadow-color: #'.$theme['tr_color2'].';
	scrollbar-3dlight-color: #'.$theme['tr_color3'].';
	scrollbar-arrow-color: #'.$theme['body_link'].';
	scrollbar-track-color: #'.$theme['tr_color1'].';
	scrollbar-darkshadow-color: #'.$theme['th_color1'].';
}

font,th,td,p { font-size: '.$theme['fontsize2'].'px; color: #'.$theme['body_text'].'; font-family: '.$theme['fontface1'].'; }

td.row1	{ background-color: #'.$theme['tr_color1'].'; }

th {
	color: #'.$theme['fontcolor3'].'; font-size: #'.$theme['fontsize3'].'px; font-weight: bold; 
	background-color: #'.$theme['body_link'].';
	background-image: url('.$phpbb_root_path . 'templates/'.$theme['template_name'].'/images/'.$theme['th_class2'].');
	border: #'.$theme['td_color2'].'; border-style: solid; height: 25px; border-width: 0px 0px 0px 0px; 
}

td.catBottom {
	font-size: '.$theme['fontsize3'].'px; 
	background-color: #'.$theme['tr_color3'].'; 
	background-image: url('.$phpbb_root_path . 'templates/'.$theme['template_name'].'/images/'.$theme['th_class1'].');
	border: #'.$theme['td_color2'].'; border-style: solid; height: 28px; border-width: 0px 0px 0px 0px;
}

input,textarea, select {
	color: #'.$theme['body_text'].';
	font: normal '.$theme['fontsize2'].'px '.$theme['fontface1'].';
	border-color: #'.$theme['body_text'].';
	border-top-width : 1px; 
	border-right-width : 1px; 
	border-bottom-width : 1px; 
	border-left-width : 1px;  
}

input.post, textarea.post, select {
	background-color: #'.$theme['td_color2'].';
}

input { text-indent : 2px; }

input.mainoption {
	background-color: #'.$theme['td_color1'].';
	font-weight: bold;
}
-->
</style>
</head>
<body>';

//
// Grab form data and send report...
//
$subject = (isset($HTTP_GET_VARS['title'])) ? htmlspecialchars($HTTP_GET_VARS['title']) : htmlspecialchars($HTTP_POST_VARS['title']);
$message = (isset($HTTP_GET_VARS['desc'])) ? htmlspecialchars($HTTP_GET_VARS['desc']) : htmlspecialchars($HTTP_POST_VARS['desc']);

$subject = trim($subject);
$message = trim($message);

if (!empty($subject) && !empty($message))
{
	$message = $message . "\n~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\nURL: http://" . $board_config['server_name'] . $board_config['script_path'] . "\nBuild: " . $board_config['fm_version'] . "\nPHP: " . @phpversion() . "\nMySQL: " . mysql_get_client_info();
    $headers = 'From: ' . $board_config['sitename'] . ' <' . $board_config['board_email'] . ">\r\n";
	@mail('support@phpbb-fm.com', 'Bug Report: ' . $subject, $message, $headers); 
	
	echo '<p align="center">Report sent.<br />
	Thanks for your help!<br />
	<br />
	<a href="javascript:window.close();" class="genmed">' . $lang['Close_window'] . '</a></p>';
	exit;
}

//
// Display report form...
//
echo '<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><form action="' . append_sid('report_bug.'.$phpEx) . '" method="post" name="ReportBug">
<tr>
	<th class="thHead">' . $lang['Report_bug'] . '</th>
</tr>
<tr>
	<td class="row1">
	When you report a software bug, please make sure that the following conditions are met:<br />
	<br />
	<b>- The bug must be reproducible</b><br />
	You should have tested it at least two times (preferably on two different servers).<br />
	<br />
	<b>- Describe the issue as detailed as possible</b><br />
	Include a step-by-step reproduction guide which we can use to recreate the problem. This will help us to easier maintain and fix the issue.<br />
	<br />
	<b>- Write reports in English only</b><br />
	We have no possibility to handle reports written in other languages than English.<br />
	<br />
	<b>NOTE:</b><br />
	If we cannot reproduce the issue in the report we may have to close the report.<br />
	<br />
	Short Description:<br />
	<input name="title" type="text" class="post" size="58" maxlength="60" /><br />
	Description:<br />
	<textarea name="desc" class="post" rows="12" cols="55"></textarea><br />
	Please make sure you have supplied enough reproduction information before posting a bug report.<br />
	<br />
	Thanks for your help!</td>
</tr>
<tr>
	<td class="catBottom" align="center"><input type="submit" class="mainoption" value="' . $lang['Submit'] . '" /></td>
</tr>
</form></table>	

</body>
</html>';
	
?>
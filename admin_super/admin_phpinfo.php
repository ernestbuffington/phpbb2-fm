<?php
/** 
* 
* @package admin_mod
* @version $Id: admin_phpinfo.php,v 1.5 2003/09/07 16:52:50 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
* 
*/

if(!defined('IN_PHPBB')) 
{ 
	define('IN_PHPBB', 1); 
	$phpbb_root_path = "./../"; 
	require($phpbb_root_path . 'extension.inc'); 
	require('./pagestart.' . $phpEx); 
} 
else if ( !empty($setmodules) ) 
{ 
	$filename = basename(__FILE__); 
	$module['Utilities_']['PHP_Info'] = $filename; 
	return; 
} 

if( !empty($setmodules) ) 
{ 
	return; 
} 

ob_start(); 
phpinfo(INFO_GENERAL | INFO_CONFIGURATION | INFO_MODULES | INFO_VARIABLES); 
$phpinfo = ob_get_clean(); 

$phpinfo = trim($phpinfo);

// Here we play around a little with the PHP Info HTML to try and stylise 
// it along phpBB's lines ... hopefully without breaking anything. The idea 
// for this was nabbed from the PHP annotated manual 
preg_match_all('#<body[^>]*>(.*)</body>#siU', $phpinfo, $output); 

if (empty($phpinfo) || empty($output))
{ 
	message_die(GENERAL_MESSAGE, $lang['No_PHP_Info']);
}

$output = $output[1][0];
$output = preg_replace('#<tr class="v"><td>(.*?<a[^>]*><img[^>]*></a>)(.*?)</td></tr>#s', '<tr><td><table><tr><td>\2</td><td>\1</td></tr></table></td></tr>', $output);
$output = preg_replace('#<table[^>]+>#i', '<table cellpadding="4" cellspacing="1" class="forumline">', $output);
$output = str_replace(array('class="e"', 'class="v"', 'class="h"', '<hr />', '<font', '</font>'), array('class="row1"', 'class="row2"', '', '', '<span', '</span>'), $output);
	
		preg_match_all('#<div class="center">(.*)</div>#siU', $output, $output); 
		$output = $output[1][0]; 

echo $mod_menu . '
		</ul>
	</div></td>
	<td valign="top" width="78%">

<h1>' . $lang['PHP_Info'] . '</h1>
	
<p>' . $lang['PHP_Info_Explain'] . '</p>

' . $output; 

include('../admin/page_footer_admin.' . $phpEx); 

?>
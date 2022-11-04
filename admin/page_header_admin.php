<?php
/** 
*
* @package admin
* @version $Id: page_header_admin.php,v 1.12.2.5 2003/06/10 20:48:18 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

define('HEADER_INC', true);

//
// gzip_compression
//
$do_gzip_compress = FALSE;
if ( $board_config['gzip_compress'] )
{
	$phpver = phpversion();

	$useragent = (isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) ? $HTTP_SERVER_VARS['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');

	if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
	{
		if ( extension_loaded('zlib') )
		{
			if (headers_sent() != TRUE) 
			{ 
				//
				// Here we updated the gzip function.
				// With this method we can get the server up
				// to 10% faster
				//
				$gz_possible = isset($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING']) && eregi('gzip, deflate',$HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING']); 
				if ($gz_possible) 
				{
					ob_start('ob_gzhandler'); 
				}
			}
		}
	}
	else if ( $phpver > '4.0' )
	{
		if ( strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip') )
		{
			if ( extension_loaded('zlib') )
			{
				$do_gzip_compress = TRUE;
				ob_start();
				ob_implicit_flush(0);

				header('Content-Encoding: gzip');
			}
		}
	}
}

$page_title = $lang['ADMIN_CP'];
$template->set_filenames(array(
	'header' => 'admin/page_header.tpl')
);

// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
$l_timezone = explode('.', $board_config['board_timezone']);
$l_timezone = (count($l_timezone) > 1 && $l_timezone[count($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $board_config['board_timezone'])] : $lang[number_format($board_config['board_timezone'])];

//
// The following assigns all _common_ variables that may be used at any point
// in a template. Note that all URL's should be wrapped in append_sid, as
// should all S_x_ACTIONS for forms.
//
$template->assign_vars(array(
	'SITENAME' => $board_config['sitename'],
	'PAGE_TITLE' => $page_title,

	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_ENABLED' => $lang['Enabled'], 
	'L_DISABLED' => $lang['Disabled'], 
	'L_ACTION' => $lang['Action'],
	'L_ADD' => $lang['Add'],
	'L_EDIT' => $lang['Edit'],
	'L_DELETE' => $lang['Delete'], 
	'L_MARK_ALL' => $lang['Mark_all'],
	'L_UNMARK_ALL' => $lang['Unmark_all'],
	'L_GO' => $lang['Go'],
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
				
	'L_ADMIN' => $lang['Admin'], 
	'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
	'L_FAQ' => $lang['FAQ'],
	'L_LOGGED_IN_AS' => $lang['Logged_in_as'],
	'L_LOGOUT' => $lang['Logout'],

	'L_PORTAL_INDEX' => $lang['Portal_index'],
	'L_FORUM_INDEX' => $lang['Main_index'],
	'L_ADMIN_INDEX' => $lang['Admin_Index'],
	'U_PORTAL_INDEX' => append_sid($phpbb_root_path . 'portal.'.$phpEx),
	'U_INDEX' => append_sid($phpbb_root_path . 'index.'.$phpEx),
	'U_ADMIN_INDEX' => append_sid('index.'.$phpEx),
	'U_LOGOUT' => append_sid($phpbb_root_path . 'login.'.$phpEx.'?logout=true'),

	'S_ADMIN_USERNAME' => username_level_color($userdata['username'], $userdata['user_level'], $userdata['user_id']),
	'S_CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])), 
	'S_CONTENT_DIRECTION' => $lang['DIRECTION'], 
	'S_CONTENT_ENCODING' => $lang['ENCODING'], 
	'S_CONTENT_DIR_LEFT' => $lang['LEFT'], 
	'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'], 

 	'T_THEME' => $theme['template_name'],
	'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
	'T_BODY_BACKGROUND' => $theme['body_background'],
	'T_BODY_BGCOLOR' => '#'.$theme['body_bgcolor'],
	'T_BODY_TEXT' => '#'.$theme['body_text'],
	'T_BODY_LINK' => '#'.$theme['body_link'],
	'T_BODY_VLINK' => '#'.$theme['body_vlink'],
	'T_BODY_ALINK' => '#'.$theme['body_alink'],
	'T_BODY_HLINK' => '#'.$theme['body_hlink'],
	'T_TR_COLOR1' => '#'.$theme['tr_color1'],
	'T_TR_COLOR2' => '#'.$theme['tr_color2'],
	'T_TR_COLOR3' => '#'.$theme['tr_color3'],
	'T_TR_CLASS1' => $theme['tr_class1'],
	'T_TR_CLASS2' => $theme['tr_class2'],
	'T_TR_CLASS3' => $theme['tr_class3'],
	'T_TH_COLOR1' => '#'.$theme['th_color1'],
	'T_TH_COLOR2' => '#'.$theme['th_color2'],
	'T_TH_COLOR3' => '#'.$theme['th_color3'],
	'T_TH_CLASS1' => $theme['th_class1'],
	'T_TH_CLASS2' => $theme['th_class2'],
	'T_TH_CLASS3' => $theme['th_class3'],
	'T_TD_COLOR1' => '#'.$theme['td_color1'],
	'T_TD_COLOR2' => '#'.$theme['td_color2'],
	'T_TD_COLOR3' => '#'.$theme['td_color3'],
	'T_TD_CLASS1' => $theme['td_class1'],
	'T_TD_CLASS2' => $theme['td_class2'],
	'T_TD_CLASS3' => $theme['td_class3'],
	'T_FONTFACE1' => $theme['fontface1'],
	'T_FONTFACE2' => $theme['fontface2'],
	'T_FONTFACE3' => $theme['fontface3'],
	'T_FONTSIZE1' => $theme['fontsize1'],
	'T_FONTSIZE2' => $theme['fontsize2'],
	'T_FONTSIZE3' => $theme['fontsize3'],
	'T_FONTCOLOR1' => '#'.$theme['fontcolor1'],
	'T_FONTCOLOR2' => '#'.$theme['fontcolor2'],
	'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
	'T_HR_COLOR1' => '#'.$theme['hr_color1'],
	'T_HR_COLOR2' => '#'.$theme['hr_color2'],
	'T_HR_COLOR3' => '#'.$theme['hr_color3'],
	'T_HR_COLOR4' => '#'.$theme['hr_color4'],
	'T_HR_COLOR5' => '#'.$theme['hr_color5'],
	'T_HR_COLOR6' => '#'.$theme['hr_color6'],
	'T_HR_COLOR7' => '#'.$theme['hr_color7'],
	'T_HR_COLOR8' => '#'.$theme['hr_color8'],
	'T_HR_COLOR9' => '#'.$theme['hr_color9'],
	'T_ADMINFONTCOLOR' => '#'.$theme['adminfontcolor'], 
	'T_SUPERMODFONTCOLOR' => '#'.$theme['supermodfontcolor'], 
	'T_MODFONTCOLOR' => '#'.$theme['modfontcolor'], 
	'T_SPAN_CLASS1' => $theme['span_class1'],
	'T_SPAN_CLASS2' => $theme['span_class2'],
	'T_SPAN_CLASS3' => $theme['span_class3'],
		
	'COPYRIGHT_YEAR' => date('Y'))
);

if ( file_exists($phpbb_root_path . 'lite') )
{
	$template->assign_block_vars('switch_lite_on', array(
		'L_LITE_INDEX' => $lang['Lite_Index'],
		'U_LITE_INDEX' => append_sid($phpbb_root_path . 'lite/index.'.$phpEx))
	);
}

if ($board_config['admin_login'])
{
	$template->assign_block_vars('switch_admin_login', array(
		'L_ADMIN_LOGOUT' => $lang['Admin_session_logout'],
		'U_ADMIN_LOGOUT' => append_sid($phpbb_root_path . 'login.'.$phpEx.'?logout=true&amp;admin_session_logout=true'))
	);
}

// Work around for "current" Apache 2 + PHP module which seems to not
// cope with private cache control setting
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: 0');
header ('Pragma: no-cache');

$template->pparse('header');

?>
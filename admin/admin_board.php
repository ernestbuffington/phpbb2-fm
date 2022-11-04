<?php
/** 
*
* @package admin
* @version $Id: admin_board.php,v 1.51.2.15 2006/02/10 22:19:01 grahamje Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['Configuration'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);
include($phpbb_root_path . 'includes/functions_news.'.$phpEx); 
include($phpbb_root_path . 'includes/constants_usage_stats.' . $phpEx);
include($phpbb_root_path . 'includes/functions_usage_stats.' . $phpEx); 
include($phpbb_root_path . 'includes/prune_users.' . $phpEx); 
include($phpbb_root_path . 'mods/toplist/toplist_common.'.$phpEx);


//
// Check to see what mode we should operate in.
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = 'board';
}


//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_kb.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_kb.' . $phpEx);
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.' . $phpEx);
if ( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_toplist.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_toplist.' . $phpEx);
if ( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_user_prune.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_user_prune.' . $phpEx);

	
//
// Pull all config data
//
$sql = "SELECT user_zipcode
	FROM " . USERS_TABLE . "
	WHERE user_id = " . ANONYMOUS;
if (!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, 'Could not query default board weather code', '', __LINE__, __FILE__, $sql);
}
$ziprow = $db->sql_fetchrow($result);

$sql = "SELECT *
	FROM " . CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not query config information in admin_board", "", __LINE__, __FILE__, $sql);
}
else
{
	// Configurable Username Length error
	if( isset($HTTP_POST_VARS['submit']) )
	{
		if ($HTTP_POST_VARS['limit_username_max_length'] < $HTTP_POST_VARS['limit_username_min_length'])
		{
			message_die(GENERAL_MESSAGE, 'username_max_min_error');
		}
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

		// Attempt to prevent a common mistake with these values
		if ($config_name == 'avatar_path')
		{
			$new['avatar_path'] = trim($new['avatar_path']);
			if (strstr($new['avatar_path'], "\0") || !is_dir($phpbb_root_path . $new['avatar_path']) || !is_writable($phpbb_root_path . $new['avatar_path']))
			{
				$new['avatar_path'] = $default_config['avatar_path'];
			}
		}

		if ($config_name == 'cookie_name')
		{
			$new['cookie_name'] = str_replace('.', '_', $new['cookie_name']);
		}

		if ($config_name == 'gzip_level')
		{
			$new['gzip_level'] = preg_replace("#[^0-9]#", '9', $new['gzip_level']);
		}
		
		// http:// is the protocol and not part of the server name
		if ($config_name == 'server_name')
		{
			$new['server_name'] = str_replace('http://', '', $new['server_name']);
		}
			
		// Configurable Username Length error
		if ($config_name == 'limit_username_min_length' && $config_value < 2)
		{
			$new['limit_username_min_length'] = 2;
		}
		else if ($config_name == 'limit_username_max_length' && $config_value < 2)
		{
			$new['limit_username_max_length'] = 25;
		}

		if( isset($HTTP_POST_VARS['submit']) )
		{
			// Board disable
			if ($mode == 'disable')
			{
				if ($config_name == 'board_disable_mode')
				{
					$new[$config_name] = implode(',', $new[$config_name]);
				}
			}
			
			// Toplist image dimensions
			$explode_dimensions = (!empty($board_config['toplist_dimensions'])) ? explode('#', $board_config['toplist_dimensions']) : '' ;
		
			$new['toplist_dimensions'] = '';
			for ($i = 0; $i < sizeof($explode_dimensions); $i++)
			{
				$dont = FALSE;
	
				for ($j = 0; $j < sizeof($HTTP_POST_VARS['toplist_dimensions']); $j++)
				{
					if ( $explode_dimensions[$i] == $HTTP_POST_VARS['toplist_dimensions'][$j])
					{
						$dont = TRUE;
					}
				}
					
				if (!$dont)
				{
					$new['toplist_dimensions'] .= ( ( $new['toplist_dimensions'] != '' ) ? '#' : '' ) . $explode_dimensions[$i];
				}
			}
				
			if ( trim($HTTP_POST_VARS['dimension_width']) != '' && trim($HTTP_POST_VARS['dimension_heigth']) != '' )
			{
				$new['toplist_dimensions'] .= ( ( $new['toplist_dimensions'] != '' ) ? '#' : '' ) . $HTTP_POST_VARS['dimension_width'] . 'x' . $HTTP_POST_VARS['dimension_heigth'];
			}
			
			// Update config values			
			$sql = "UPDATE " . CONFIG_TABLE . " SET
				config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
                WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}

			// Board
			if ($config_name == 'default_clock')
			{
				$sql = "UPDATE " . USERS_TABLE . " 
					SET user_clockformat = '" . $config_value . "'
					WHERE user_id = " . ANONYMOUS;
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Failed to update anonymous user clock format", "", __LINE__, __FILE__, $sql);
				}
			}
			
			if ($config_name == 'default_style')
			{
				$sql = "UPDATE " . USERS_TABLE . " 
					SET user_style = '" . $config_value . "'
					WHERE user_id = " . ANONYMOUS;
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Failed to update anonymous user board style", "", __LINE__, __FILE__, $sql);
				}
			}
			
			// Points System
			$reset_points = $HTTP_POST_VARS['reset_points'];
			if ( $reset_points != '' )
			{
				$sql = "SELECT user_id FROM " . USERS_TABLE . " 
					WHERE user_id != " . ANONYMOUS;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain userdata.', '', __LINE__, __FILE__, $sql);
				}
				$users = $db->sql_fetchrowset($result);
				$total_users = sizeof($users);
	
				for($i = 0; $i < $total_users; $i++)
				{
					$user_id = $users[$i]['user_id'];
					$points = intval($reset_points);

					$sql = "UPDATE " . USERS_TABLE . " 
						SET user_points = $points
						WHERE user_id = " . $user_id;
					if( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not update users points data.', '', __LINE__, __FILE__, $sql);
					}
				}
			}	
			
			// Default zipcode
			if (!empty($HTTP_POST_VARS['zipcode']))
			{
				$zipcode = ( !empty($HTTP_POST_VARS['zipcode']) ) ? trim($HTTP_POST_VARS['zipcode']) : '';
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_zipcode = '$zipcode'
					WHERE user_id = " . ANONYMOUS;
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, "Failed to update default board weather code", '', __LINE__, __FILE__, $sql);
				}
			}
		}
	}
	$db->sql_freeresult($result);

	if( isset($HTTP_POST_VARS['submit']) )
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
		
		$message = $lang['Board_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_board.'.$phpEx.'?mode=' . $mode) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}

//
// Check to see what section we should load
//
if ( $mode == 'toplist' )
{
	$f = ( !empty($HTTP_POST_VARS['f']) ) ? $HTTP_POST_VARS['f'] : (($HTTP_GET_VARS['f']) ? $HTTP_GET_VARS['f'] : 'toplisteditsitelogin');
	
	include($phpbb_root_path . 'toplist.'.$phpEx);
}
else
{

$temp_tpl = 'board';
switch($mode)
{
	case 'ajaxed':
		$config_page = $lang['AJAXed_Config'];
		$template->assign_block_vars('switch_ajax', array());
		break;
	case 'amazon':
		$config_page = $lang['Amazon'];
		$template->assign_block_vars('switch_amazon', array());
		break;
	case 'autoprune':
		$config_page = $lang['User_Auto_Delete'];
		$template->assign_block_vars('switch_autoprune', array());
		break;
	case 'avatars':
		$config_page = $lang['Avatar'];
		$template->assign_block_vars('switch_avatars', array());
		$temp_tpl = 'avatar';
		break;
	case 'avatar_upload':
		$config_page = $lang['Uploading'];
		$template->assign_block_vars('switch_avatar_upload', array());
		$temp_tpl = 'avatar';
		break;
	case 'avatar_gallery':
		$config_page = $lang['Avatar_gallery'];
		$template->assign_block_vars('switch_avatar_gallery', array());
		$temp_tpl = 'avatar';
		break;
	case 'avatar_generator':
		$config_page = $lang['Avatar_Generator'];
		$template->assign_block_vars('switch_avatar_generator', array());
		$temp_tpl = 'avatar';
		break;
	case 'bancard':
		$config_page = $lang['Ban_card_config'];
		$temp_tpl = 'ban';
		break;
	case 'birthday':
		$config_page = $lang['Birthday'];
		$template->assign_block_vars('switch_bday', array());
		break;
	case 'bots':
		$config_page = $lang['Bots_Spiders'];
		$template->assign_block_vars('switch_bots', array());
		$temp_tpl = 'bots';
		break;
	case 'disable':
		$config_page = $lang['Board_disable'];
		$template->assign_block_vars('switch_disable', array());
		break;
	case 'load':
		$config_page = $lang['Board_load'];
		$template->assign_block_vars('switch_load', array());
		break;
	case 'usage':
		$config_page = $lang['BBUS_Settings_Caption'];
		$template->assign_block_vars('switch_usage', array());
		break;
	case 'calendar':
		$config_page = $lang['Calendar'];
		$template->assign_block_vars('switch_calendar', array());
		break;
	case 'cookie':
		$config_page = $lang['Cookie_settings'];
		$template->assign_block_vars('switch_cookie', array());
		break;
	case 'ebay_auction':
		$config_page = $lang['Auctions_config'];
		$template->assign_block_vars('switch_ebay', array());
		break;
	case 'email':
		$config_page = $lang['Email'];
		$temp_tpl = 'email';
		break;
	case 'forum':
		$config_page = $lang['Forum'];
		$temp_tpl = 'forum';
		break;
	case 'forum_modules':
		$config_page = $lang['Forum_module_title'];
		$temp_tpl = 'forum_module';
		break;
	case 'gender':
		$config_page = $lang['Gender'];
		$template->assign_block_vars('switch_gender', array());
		break;
	case 'inline_ads':
		$config_page = $lang['Inline_ad_config'];
		$temp_tpl = 'inline_ad';
		break;
	case 'karma':
		$config_page = $lang['Karma'];
		$template->assign_block_vars('switch_karma', array());
		break;
	case 'kb':
		$config_page = $lang['Kb'];
		$template->assign_block_vars('switch_config', array());
		$temp_tpl = 'kb';
		break;
	case 'kb_info':
		$config_page = $lang['Kb'] . ' ' . $lang['Pre_text_name'];
		$template->assign_block_vars('switch_article', array());
		$temp_tpl = 'kb';
		break;
	case 'lexicon':
		$config_page = $lang['Lexicon'];
		$temp_tpl = 'lexicon';
		break;
	case 'login':
		$config_page = $lang['Login'];
		$template->assign_block_vars('switch_login', array());
		break;
	case 'medals':
		$config_page = $lang['Medals'];
		$temp_tpl = 'medals';
		break;
	case 'meta_tags':
		$config_page = $lang['Meta_settings'];
		$template->assign_block_vars('switch_meta_tags', array());
		break;
	case 'modcp':
		$config_page = $lang['Mod_CP'];
		$template->assign_block_vars('switch_modcp', array());
		break;
	case 'myinfo':
		$config_page = $lang['myInfo_title'];
		$template->assign_block_vars('switch_myinfo', array());
		break;
	case 'newsbar':
		$config_page = $lang['News_bar'];
		$template->assign_block_vars('switch_newsbar', array());
		break;
	case 'notes':
		$config_page = $lang['Notes'];
		$template->assign_block_vars('switch_notes', array());
		break;
	case 'passgen':
		$config_page = $lang['Pass_gen'];
		$template->assign_block_vars('switch_passgen', array());
		break;
	case 'points':
		$config_page = $lang['Points_sys_settings'];
		$template->assign_block_vars('switch_points', array());
		break;
	case 'post':
		$config_page = $lang['Post'];
		$temp_tpl = 'topic';
		break;
	case 'pm':
		$config_page = $lang['Private_Messaging'];
		$temp_tpl = 'priv_msgs';
		break;
	case 'profile_photo':
		$config_page = $lang['Profile_photo'];
		$template->assign_block_vars('switch_profile_photo', array());
		break;
	case 'referral':
		$config_page = $lang['Referral_System'];
		$template->assign_block_vars('switch_referral', array());
		break;
	case 'register':
		$config_page = $lang['Registration_settings'];
		$template->assign_block_vars('switch_register', array());
		break;
	case 'search':
		$config_page = $lang['Search'];
		$template->assign_block_vars('switch_search', array());
		break;
	case 'server':
		$config_page = $lang['Web_server'];
		$template->assign_block_vars('switch_server', array());
		break;
	case 'shoutbox':
		$config_page = $lang['Shoutbox'];
		$template->assign_block_vars('switch_shoutbox', array());
		break;
	case 'shoutcast':
		$config_page = $lang['Shoutcast'];
		$template->assign_block_vars('switch_shoutcast', array());
		break;
	case 'signature':
		$config_page = $lang['Signature'];
		$template->assign_block_vars('switch_signature', array());
		break;
	case 'smilies':
		$config_page = $lang['Smiley'];
		$temp_tpl = 'smile';
		break;
	case 'teamspeak':
		$config_page = $lang['Teamspeak'];
		$template->assign_block_vars('switch_teamspeak', array());
		break;
	case 'toplist_config':
		$config_page = $lang['Toplist'];
		$temp_tpl = 'toplist';
		$template->assign_block_vars('switch_config', array());
		break;
	case 'toplist_button':
		$config_page = $lang['Toplist'] . ' ' . $lang['Admin_Button_Config'];
		$temp_tpl = 'toplist';
		$template->assign_block_vars('switch_button', array());
		break;
	case 'toplist_hits':
		$config_page = $lang['Toplist'] . ' ' . $lang['Admin_Disable_hits'];
		$temp_tpl = 'toplist';
		$template->assign_block_vars('switch_hits', array());
		break;
	case 'toplist_image':
		$config_page = $lang['Toplist'] . ' ' . $lang['Admin_Dimensions_expl'];
		$temp_tpl = 'toplist';
		$template->assign_block_vars('switch_image', array());
		break;
	case 'toplist_interval':
		$config_page = $lang['Toplist'] . ' ' . $lang['Admin_Intervals'];
		$temp_tpl = 'toplist';
		$template->assign_block_vars('switch_interval', array());
		break;
	case 'toplist_rank':
		$config_page = $lang['Toplist'] . ' ' . $lang['Admin_Count_hits_title'];
		$temp_tpl = 'toplist';
		$template->assign_block_vars('switch_rank', array());
		break;		
	case 'whosonline':
		$config_page = $lang['Who_is_Online'];
		$template->assign_block_vars('switch_whosonline', array());
		break;
	case 'board':
	default:
		$config_page = $lang['Board'];
		$template->assign_block_vars('switch_config', array());
		break;
}
$hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';


//
// Send to template
//
$viewlevel = $new[BBUS_CONFIGPROP_VIEWLEVEL_NAME];
$viewoptions = $board_config[BBUS_CONFIGPROP_VIEWOPTIONS_NAME];

$auction_tz_select = '<select name="auction_timezone_offset">';
for($i = -24; $i <= 24; $i = $i + 1)
{
	$selected = ( $new['auction_timezone_offset'] == $i ) ? ' selected="selected"' : '';
	$auction_tz_select .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
}
$auction_tz_select .= '</select>';

$avatars_per_page_select = '<select name="avatars_per_page">';
for($i = 5; $i <= 50; $i = $i + 5)
{
	$selected = ( $new['avatars_per_page'] == $i ) ? ' selected="selected"' : '';

	$avatars_per_page_select .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
}
$avatars_per_page_select .= '</select>';

$explode_dimensions = explode('#', $new['toplist_dimensions']);
for ($i = 0; $i < sizeof($explode_dimensions); $i++)	
{
	$dimensions .= '<option value="' . $explode_dimensions[$i] . '">' . $explode_dimensions[$i] . '</option>';
}

$sql = "SELECT group_id, group_name, group_type
	FROM " . GROUPS_TABLE . "
	WHERE group_single_user <> " . TRUE . "
	ORDER BY group_name";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
}

$s_group_list_opt ='<option value="0" title="' . $row['Disabled'] . '">' . $lang['Disabled'] . '</option>';
while( $row = $db->sql_fetchrow($result) )
{
	if ( $row['group_type'] != GROUP_HIDDEN && $row['group_type'] != GROUP_PAYMENT )
	{
   	 	$s_selected = ( $row['group_id'] == $new['auto_group_id'] ) ? ' selected="selected" ' : '';
		$s_group_list_opt .='<option value="' . $row['group_id'] . '" title="' . $row['group_name'] . '"' . $s_selected . '>' . $row['group_name'] . '</option>';
	}
}
$db->sql_freeresult($result);
$simple_auto_group = '<select name="auto_group_id">' . $s_group_list_opt . '</select>';

if ($mode == 'server')
{
	include($phpbb_root_path . 'mods/attachments/includes/functions_admin.'.$phpEx);

	// Check for gd version
	if (function_exists('gd_info')) 
	{
		$info = gd_info();
		$keys = array_keys($info);
	
		for ( $i=0; $i < 1; $i++ )
		{
			$gd_title = $keys[$i];
			$gd_version = $info[$keys[$i]];
		}
	} 
	else 
	{
		$gd_title = $lang['gd_version'];
		$gd_version = $lang['Not_available']; 
	}

	// Check for server version
	define('SAPI_NAME', php_sapi_name());
	if (preg_match('#(Apache)/([0-9\.]+)\s#siU', $_SERVER['SERVER_SOFTWARE'], $wsregs))
	{
		$server = "$wsregs[1] v$wsregs[2]";
		if (SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi')
		{
			$addsapi = true;
		}
	}
	else if (preg_match('#Microsoft-IIS/([0-9\.]+)#siU', $SERVER['SERVER_SOFTWARE'], $wsregs))
	{
		$server = "IIS v$wsregs[1]";
		$addsapi = true;
	}
	else if (preg_match('#Zeus/([0-9\.]+)#siU', $SERVER['SERVER_SOFTWARE'], $wsregs))
	{
		$server = "Zeus v$wsregs[1]";
		$addsapi = true;
	}
	else if (strtoupper($_SERVER['SERVER_SOFTWARE']) == 'APACHE')	
	{
		$server = 'Apache';
		if (SAPI_NAME == 'cgi' OR SAPI_NAME == 'cgi-fcgi')
		{
			$addsapi = true;
		}
	}
	else
	{
		$server = SAPI_NAME;
	}	
	
	if ($addsapi)
	{
		$server .= ' (' . SAPI_NAME . ')';
	}

	$template->assign_vars(array(
		'L_STATISTIC' => $lang['Statistic'],
		'L_VALUE' => $lang['Value'],
		'L_SERVER_TYPE' => $lang['Server_type'],
		'L_SERVER' => $lang['Web_server'],
		'L_DATABASE_TYPE' => $lang['MySQL'],
		'L_PHP_VERSION' => sprintf($lang['php_version'], '<a href="' . append_sid('admin_phpinfo.'.$phpEx) . '" class="genmed">', '</a>'),
		'L_PHP_FILESIZE' => $lang['php_filesize'],
		'L_MEMORY_LIMIT' => $lang['memory_limit'],
		'L_GD_SUPPORT' => $gd_title,
		'L_GZIP_COMPRESSION' => $lang['Gzip_compression'],
		'L_SAFE_MODE' => $lang['safe_mode'],
		'L_PKT_SIZE' => $lang['pkt_size'],
		'L_REGISTER_GLOBALS' => $lang['register_globals'],
		'L_UPLOAD_MAX_FILESIZE' => $lang['upload_max_filesize'],

		'GZIP_COMPRESSION' => ( $board_config['gzip_compress'] ) ? $lang['ON'] . ' (' . $board_config['gzip_level'] . ')' : '<span class="error">' . $lang['OFF'] . '</span>',
		'DATABASE' => (function_exists('mysql_get_server_info')) ? (( mysql_get_server_info() < '3.23.23' ) ? '<span class="error">' . mysql_get_server_info() . '</span>' : mysql_get_server_info()) : $lang['Not_available'], 
 		'MAX_FILESIZE' => @ini_get('upload_max_filesize'),
 		'PKT_SIZE' => get_formatted_dirsize('max_allowed_packet'),
		'PHP_VERSION' => ((PHP_VERSION < PHP_REQ) ? '<span class="error">' . PHP_VERSION . '</span>' : PHP_VERSION) . ' ' . (function_exists('zend_version') ? '(' . $lang['Zend'] . ': ' . zend_version() . ')' : ''),
		'PHP_FILESIZE' => (@ini_get('post_max_size')) ? @ini_get('post_max_size') : $lang['Not_available'],
		'MEMORY_LIMIT' => ( (@ini_get('memory_limit')) && (@ini_get('memory_limit') != -1) ) ? ini_get('memory_limit') : $lang['None'],
		'GD_SUPPORT' => $gd_version,
		'SAFE_MODE' => (@ini_get('safe_mode') == 1 || strtolower(@ini_get('safe_mode')) == 'on') ? '<span class="error">' . $lang['ON'] . '</span>' : $lang['OFF'],
		'SAFE_MODE_XTRA' => (@ini_get('file_uploads') == 0 || strtolower(@ini_get('file_uploads')) == 'off') ? '<br />' . $lang['File_uploads_disabled'] : '',
		'REGISTER_GLOBALS' => (@ini_get('register_globals') == 1 || strtolower(@ini_get('register_globals')) == 'on') ? '<span class="error"">' . $lang['ON'] . '</span>' : $lang['OFF'],
		'SERVER_TYPE' => PHP_OS,
		'SERVER' => $server,
			
 		'U_TABLECOUNT' => append_sid('admin_db_utilities_phpbbmyadmin.'.$phpEx))
	);
}

$template->set_filenames(array(
	'body' => 'admin/' . $temp_tpl . '_config_body.tpl')
);

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_board.'.$phpEx),
	
	'L_PAGE_TITLE' => $config_page . ' ' . $lang['Setting'],
	'L_PAGE_EXPLAIN' => sprintf($lang['Config_explain'], $config_page),

	// AJAXed
	'L_AJAXED_EXPLAIN' => $lang['AJAXed_explain'],
	'L_AJAXED_STATUS' => $lang['AJAXed_status'],
	'L_AJAXED_STATUS_EXPLAIN' => $lang['AJAXed_status_explain'],
	'L_AJAXED_INLINE_POST_EDITING' => $lang['AJAXed_inline_post_editing'],
	'L_AJAXED_INLINE_POST_EDITING_EXPLAIN' => $lang['AJAXed_inline_post_editing_explain'],
	'L_AJAXED_POST_TITLE' => $lang['AJAXed_post_title'],
	'L_AJAXED_POST_TITLE_EXPLAIN' => $lang['AJAXed_post_title_explain'],
	'L_AJAXED_POST_IP' => $lang['AJAXed_post_ip'],
	'L_AJAXED_POST_IP_EXPLAIN' => $lang['AJAXed_post_ip_explain'],
	'L_AJAXED_POST_MENU' => $lang['AJAXed_post_menu'],
	'L_AJAXED_POST_MENU_EXPLAIN' => $lang['AJAXed_post_menu_explain'],
	'L_AJAXED_POLL_MENU' => $lang['AJAXed_poll_menu'],
	'L_AJAXED_POLL_MENU_EXPLAIN' => $lang['AJAXed_poll_menu_explain'],
	'L_AJAXED_POLL_TITLE' => $lang['AJAXed_poll_title'],
	'L_AJAXED_POLL_TITLE_EXPLAIN' => $lang['AJAXed_poll_title_explain'],
	'L_AJAXED_POLL_OPTIONS' => $lang['AJAXed_poll_options'],
	'L_AJAXED_POLL_OPTIONS_EXPLAIN' => $lang['AJAXed_poll_options_explain'],
	'L_AJAXED_POST_PREVIEW' => $lang['AJAXed_post_preview'],
	'L_AJAXED_POST_PREVIEW_EXPLAIN' => $lang['AJAXed_post_preview_explain'],
	'L_AJAXED_PM_PREVIEW' => $lang['AJAXed_pm_preview'],
	'L_AJAXED_PM_PREVIEW_EXPLAIN' => $lang['AJAXed_pm_preview_explain'],
	'L_AJAXED_USER_LIST' => $lang['AJAXed_user_list'],
	'L_AJAXED_USER_LIST_EXPLAIN' => $lang['AJAXed_user_list_explain'],
	'L_AJAXED_USER_LIST_NUMBER' => $lang['AJAXed_user_list_number'],
	'L_AJAXED_USER_LIST_NUMBER_EXPLAIN' => $lang['AJAXed_user_list_number_explain'],
	'L_AJAXED_FORUM_DELETE' => $lang['AJAXed_forum_delete'],
	'L_AJAXED_FORUM_DELETE_EXPLAIN' => $lang['AJAXed_forum_delete_explain'],
	'L_AJAXED_DISPLAY' => $lang['AJAXed_display'],
	'L_AJAXED_DISPLAY_DELETE' => $lang['AJAXed_display_delete'],
	'L_AJAXED_FORUM_MOVE' => $lang['AJAXed_forum_move'],
	'L_AJAXED_FORUM_MOVE_EXPLAIN' => $lang['AJAXed_forum_move_explain'],
	'L_AJAXED_DISPLAY_MOVE' => $lang['AJAXed_display_move'],
	'L_AJAXED_FORUM_LOCK' => $lang['AJAXed_forum_lock'],
	'L_AJAXED_FORUM_LOCK_EXPLAIN' => $lang['AJAXed_forum_lock_explain'],
	'L_AJAXED_POST_DELETE' => $lang['AJAXed_post_delete'],
	'L_AJAXED_POST_DELETE_EXPLAIN' => $lang['AJAXed_post_delete_explain'],
	'L_AJAXED_TOPIC_DELETE' => $lang['AJAXed_topic_delete'],
	'L_AJAXED_TOPIC_DELETE_EXPLAIN' => $lang['AJAXed_topic_delete_explain'],
	'L_AJAXED_TOPIC_MOVE' => $lang['AJAXed_topic_move'],
	'L_AJAXED_TOPIC_MOVE_EXPLAIN' => $lang['AJAXed_topic_move_explain'],
	'L_AJAXED_TOPIC_LOCK' => $lang['AJAXed_topic_lock'],
	'L_AJAXED_TOPIC_LOCK_EXPLAIN' => $lang['AJAXed_topic_lock_explain'],
	'L_AJAXED_TOPIC_WATCH' => $lang['AJAXed_topic_watch'],
	'L_AJAXED_TOPIC_WATCH_EXPLAIN' => $lang['AJAXed_topic_watch_explain'],
	'L_AJAXED_USERNAME_CHECK' => $lang['AJAXed_username_check'],
	'L_AJAXED_USERNAME_CHECK_EXPLAIN' => $lang['AJAXed_username_check_explain'],
	'L_AJAXED_PASSWORD_CHECK' => $lang['AJAXed_password_check'],
	'L_AJAXED_PASSWORD_CHECK_EXPLAIN' => $lang['AJAXed_password_check_explain'],

	'STATUS_ENABLE' => ( $new['AJAXed_status'] ) ? 'checked="checked"' : '', 
	'STATUS_DISABLE' => ( !$new['AJAXed_status'] ) ? 'checked="checked"' : '', 
	'INLINE_POST_EDITING_ENABLE' => ( $new['AJAXed_inline_post_editing'] ) ? 'checked="checked"' : '', 
	'INLINE_POST_EDITING_DISABLE' => ( !$new['AJAXed_inline_post_editing'] ) ? 'checked="checked"' : '', 
	'POST_TITLE_ENABLE' => ( $new['AJAXed_post_title'] ) ? 'checked="checked"' : '', 
	'POST_TITLE_DISABLE' => ( !$new['AJAXed_post_title'] ) ? 'checked="checked"' : '', 
	'POST_IP_ENABLE' => ( $new['AJAXed_post_ip'] ) ? 'checked="checked"' : '', 
	'POST_IP_DISABLE' => ( !$new['AJAXed_post_ip'] ) ? 'checked="checked"' : '', 
	'POST_MENU_ENABLE' => ( $new['AJAXed_post_menu'] ) ? 'checked="checked"' : '', 
	'POST_MENU_DISABLE' => ( !$new['AJAXed_post_menu'] ) ? 'checked="checked"' : '', 
	'POLL_MENU_ENABLE' => ( $new['AJAXed_poll_menu'] ) ? 'checked="checked"' : '', 
	'POLL_MENU_DISABLE' => ( !$new['AJAXed_poll_menu'] ) ? 'checked="checked"' : '', 
	'POLL_TITLE_ENABLE' => ( $new['AJAXed_poll_title'] ) ? 'checked="checked"' : '', 
	'POLL_TITLE_DISABLE' => ( !$new['AJAXed_poll_title'] ) ? 'checked="checked"' : '', 
	'POLL_OPTIONS_ENABLE' => ( $new['AJAXed_poll_options'] ) ? 'checked="checked"' : '', 
	'POLL_OPTIONS_DISABLE' => ( !$new['AJAXed_poll_options'] ) ? 'checked="checked"' : '', 
	'POST_PREVIEW_ENABLE' => ( $new['AJAXed_post_preview'] ) ? 'checked="checked"' : '', 
	'POST_PREVIEW_DISABLE' => ( !$new['AJAXed_post_preview'] ) ? 'checked="checked"' : '', 
	'PM_PREVIEW_ENABLE' => ( $new['AJAXed_pm_preview'] ) ? 'checked="checked"' : '', 
	'PM_PREVIEW_DISABLE' => ( !$new['AJAXed_pm_preview'] ) ? 'checked="checked"' : '', 
	'USER_LIST_ENABLE' => ( $new['AJAXed_user_list'] ) ? 'checked="checked"' : '', 
	'USER_LIST_DISABLE' => ( !$new['AJAXed_user_list'] ) ? 'checked="checked"' : '', 
	'USER_LIST_NUMBER' => $new['AJAXed_user_list_number'], 
	'FORUM_DELETE_ENABLE' => ( $new['AJAXed_forum_delete'] ) ? 'checked="checked"' : '', 
	'FORUM_DELETE_DISABLE' => ( !$new['AJAXed_forum_delete'] ) ? 'checked="checked"' : '', 
	'DISPLAY_DELETE_ENABLE' => ( $new['AJAXed_display_delete'] ) ? 'checked="checked"' : '', 
	'DISPLAY_DELETE_DISABLE' => ( !$new['AJAXed_display_delete'] ) ? 'checked="checked"' : '', 
	'FORUM_MOVE_ENABLE' => ( $new['AJAXed_forum_move'] ) ? 'checked="checked"' : '', 
	'FORUM_MOVE_DISABLE' => ( !$new['AJAXed_forum_move'] ) ? 'checked="checked"' : '', 
	'DISPLAY_MOVE_ENABLE' => ( $new['AJAXed_display_move'] ) ? 'checked="checked"' : '', 
	'DISPLAY_MOVE_DISABLE' => ( !$new['AJAXed_display_move'] ) ? 'checked="checked"' : '', 
	'FORUM_LOCK_ENABLE' => ( $new['AJAXed_forum_lock'] ) ? 'checked="checked"' : '', 
	'FORUM_LOCK_DISABLE' => ( !$new['AJAXed_forum_lock'] ) ? 'checked="checked"' : '', 
	'POST_DELETE_ENABLE' => ( $new['AJAXed_post_delete'] ) ? 'checked="checked"' : '', 
	'POST_DELETE_DISABLE' => ( !$new['AJAXed_post_delete'] ) ? 'checked="checked"' : '', 
	'TOPIC_DELETE_ENABLE' => ( $new['AJAXed_topic_delete'] ) ? 'checked="checked"' : '', 
	'TOPIC_DELETE_DISABLE' => ( !$new['AJAXed_topic_delete'] ) ? 'checked="checked"' : '', 
	'TOPIC_MOVE_ENABLE' => ( $new['AJAXed_topic_move'] ) ? 'checked="checked"' : '', 
	'TOPIC_MOVE_DISABLE' => ( !$new['AJAXed_topic_move'] ) ? 'checked="checked"' : '', 
	'TOPIC_LOCK_ENABLE' => ( $new['AJAXed_topic_lock'] ) ? 'checked="checked"' : '', 
	'TOPIC_LOCK_DISABLE' => ( !$new['AJAXed_topic_lock'] ) ? 'checked="checked"' : '', 
	'TOPIC_WATCH_ENABLE' => ( $new['AJAXed_topic_watch'] ) ? 'checked="checked"' : '', 
	'TOPIC_WATCH_DISABLE' => ( !$new['AJAXed_topic_watch'] ) ? 'checked="checked"' : '', 
	'USERNAME_CHECK_ENABLE' => ( $new['AJAXed_username_check'] ) ? 'checked="checked"' : '', 
	'USERNAME_CHECK_DISABLE' => ( !$new['AJAXed_username_check'] ) ? 'checked="checked"' : '', 
	'PASSWORD_CHECK_ENABLE' => ( $new['AJAXed_password_check'] ) ? 'checked="checked"' : '', 
	'PASSWORD_CHECK_DISABLE' => ( !$new['AJAXed_password_check'] ) ? 'checked="checked"' : '',

	// Amazon Links
	'L_COUNTRY' => $lang['Amazon_Country'],
    'L_POSTS' => $lang['Amazon_Posts'],
	'L_NORMAL' => $lang['Post_Normal'],
	'L_STICKY' => $lang['Post_Sticky'],
	'L_ANNOUNCE' => $lang['Post_Announcement'],
	'L_GLOBAL_ANNOUNCE' => $lang['Post_global_announcement'],
	'L_USERNAME' => $lang['Amazon_Username'],
	'L_ENABLE' => $lang['Amazon_Enable'],
	'L_CHOOSE' => $lang['Amazon_Choose'],
	'L_UK' => $lang['Amazon_UK'],
	'L_US' => $lang['Amazon_US'],
	'L_CANADA' => $lang['Amazon_Canada'],
	'L_FRANCE' => $lang['Amazon_France'],
	'L_GERMANY' => $lang['Amazon_Germany'],
	'L_INFO_TEXT' => sprintf($lang['Amazon_Info_Text'], '<a href="' . append_sid('admin_forums.'.$phpEx) . '">', '</a>'),
	'L_WINDOW' => $lang['Amazon_New_Window'],
			
	'AFFILIATE' => $new['amazon_username'],
	'S_ENABLE_AMAZON_YES' => ( $new['amazon_enable'] ) ? 'checked="checked"' : '',
	'S_ENABLE_AMAZON_NO' => ( !$new['amazon_enable'] ) ? 'checked="checked"' : '',
	'S_NEW_WINDOW_YES' => ( $new['amazon_window'] ) ? 'checked="checked"' : '',
	'S_NEW_WINDOW_NO' => ( !$new['amazon_window'] ) ? 'checked="checked"' : '',
	'S_ENABLED_NORMAL' => ( $new['amazon_normal'] ) ? 'checked="checked"' : '',
	'S_ENABLED_STICKY' => ( $new['amazon_sticky'] ) ? 'checked="checked"' : '',
	'S_ENABLED_ANNOUNCE' => ( $new['amazon_announce'] ) ? 'checked="checked"' : '',
	'S_ENABLED_GLOBAL_ANNOUNCE' => ( $new['amazon_global_announce'] ) ? 'checked="checked"' : '',
	'S_UK_SELECTED' => ( !$new['amazon_country'] ) ? 'selected="selected"' : '',
	'S_US_SELECTED' => ( $new['amazon_country'] ) ? 'selected="selected"' : '',
	'S_CANADA_SELECTED' => ( $new['amazon_country'] == 2 ) ? 'selected="selected"' : '',
	'S_GERMANY_SELECTED' => ( $new['amazon_country'] == 3 ) ? 'selected="selected"' : '',
	'S_FRANCE_SELECTED' => ( $new['amazon_country'] == 4 ) ? 'selected="selected"' : '',

	// Avatars
	'L_AVATAR_SETTINGS_EXPLAIN' => ($mode == 'avatars') ? $lang['Avatar_settings_explain'] : '',
	'L_UPLOAD' => $lang['Uploading'] . ' ' . $lang['Setting'],
	'L_LOCAL' => $lang['Avatar_gallery'] . ' ' . $lang['Setting'],
	'L_GENERATOR' => $lang['Avatar_Generator'] . ' ' . $lang['Setting'],
	'L_ALLOW_LOCAL' => $lang['Allow_local'],
	'L_ALLOW_REMOTE' => $lang['Allow_remote'],
	'L_ALLOW_REMOTE_EXPLAIN' => $lang['Allow_remote_explain'],
	'L_ALLOW_UPLOAD' => $lang['Allow_upload'],
	'L_MAX_FILESIZE' => $lang['Max_filesize'],
	'L_MAX_FILESIZE_EXPLAIN' => $lang['Max_filesize_explain'],
	'L_MAX_AVATAR_SIZE' => $lang['Max_avatar_size'],
	'L_MAX_AVATAR_SIZE_EXPLAIN' => $lang['Max_avatar_size_explain'],
	'L_AVATAR_STORAGE_PATH' => $lang['Avatar_storage_path'],
	'L_AVATAR_STORAGE_PATH_EXPLAIN' => $lang['Avatar_storage_path_explain'],
	'L_AVATAR_GALLERY_PATH' => $lang['Avatar_gallery_path'],
	'L_AVATAR_GALLERY_PATH_EXPLAIN' => $lang['Avatar_gallery_path_explain'],
	'L_ALLOW_STICKY' => $lang['Avatars_Sticky'],
	'L_ALLOW_STICKY_EXPLAIN' => $lang['Avatars_Sticky_Explain'],
	'L_AVATARS_PER_PAGE' => $lang['Avatars_per_page'], 
	'L_DISABLE_AVATAR_APPROVE' => $lang['Disable_avatar_approve'], 
	'L_DISABLE_AVATAR_APPROVE_EXPLAIN' => $lang['Disable_avatar_approve_explain'], 
	'L_AVATAR_POSTS' => $lang['Avatar_posts'], 
	'L_AVATAR_POSTS_EXPLAIN' => $lang['Avatar_posts_explain'],
	'L_DEFAULT_AVATAR' => $lang['Default_avatar'],
	'L_DEFAULT_AVATAR_EXPLAIN' => $lang['Default_avatar_explain'],
	'L_DEFAULT_AVATAR_GUESTS' => $lang['Default_avatar_guests'],
	'L_DEFAULT_AVATAR_USERS' => $lang['Default_avatar_users'],
	'L_DEFAULT_AVATAR_BOTH' => $lang['Default_avatar_both'],
	'L_DEFAULT_AVATAR_NONE' => $lang['None'],
	'L_ALLOW_GENERATOR' => $lang['Allow_generator'],
	'L_AVATAR_GENERATOR_TEMPLATE_PATH' => $lang['Avatar_generator_template_path'],
	'L_AVATAR_GENERATOR_TEMPLATE_PATH_EXPLAIN' => $lang['Avatar_generator_template_path_explain'],
	'L_ALLOW_TOPLIST' => $lang['Allow_toplist'],
	'L_ALLOW_TOPLIST_EXPLAIN' => $lang['Allow_toplist_explain'],
	'L_ALLOW_VOTING_VIEWTOPIC' => $lang['Allow_viewtopic_voting'],
	'L_ALLOW_VOTING_VIEWTOPIC_EXPLAIN' => $lang['Allow_viewtopic_voting_explain'],
	'L_AVATAR_REGISTER' => $lang['Avatar_register'],
	'L_AVATAR_REGISTER_EXPLAIN' => $lang['Avatar_register_explain'],
		
	'AVATAR_REGISTER_YES' => ( $new['enable_avatar_register'] ) ? 'checked="checked"' : '',
	'AVATAR_REGISTER_NO' => ( !$new['enable_avatar_register'] ) ? 'checked="checked"' : '',
	'AVATARS_TOPLIST_YES' => ( $new['avatar_toplist'] ) ? 'checked="checked"' : '',
	'AVATARS_TOPLIST_NO' => ( !$new['avatar_toplist'] ) ? 'checked="checked"' : '',
	'AVATARS_VOTING_VIEWTOPIC_YES' => ( $new['avatar_voting_viewtopic'] ) ? 'checked="checked"' : '',
	'AVATARS_VOTING_VIEWTOPIC_NO' => ( !$new['avatar_voting_viewtopic'] ) ? 'checked="checked"' : '',
	'AVATARS_LOCAL_YES' => ( $new['allow_avatar_local'] ) ? 'checked="checked"' : '',
	'AVATARS_LOCAL_NO' => ( !$new['allow_avatar_local'] ) ? 'checked="checked"' : '',
	'AVATARS_REMOTE_YES' => ( $new['allow_avatar_remote'] ) ? 'checked="checked"' : '',
	'AVATARS_REMOTE_NO' => ( !$new['allow_avatar_remote'] ) ? 'checked="checked"' : '',
	'AVATARS_UPLOAD_YES' => ( $new['allow_avatar_upload'] ) ? 'checked="checked"' : '',
	'AVATARS_UPLOAD_NO' => ( !$new['allow_avatar_upload'] ) ? 'checked="checked"' : '',
	'AVATAR_FILESIZE' => $new['avatar_filesize'],
	'AVATAR_MAX_HEIGHT' => $new['avatar_max_height'],
	'AVATAR_MAX_WIDTH' => $new['avatar_max_width'],
	'AVATAR_PATH' => $new['avatar_path'], 
	'AVATAR_GALLERY_PATH' => $new['avatar_gallery_path'], 
	'AVATARS_STICKY_YES' => ( $new['allow_avatar_sticky'] ) ? 'checked="checked"' : '',
	'AVATARS_STICKY_NO' => ( !$new['allow_avatar_sticky'] ) ? 'checked="checked"' : '',
	'AVATARS_PER_PAGE_SELECT' => $avatars_per_page_select, 
	'DISABLE_AVATAR_APPROVE_YES' => ( $new['disable_avatar_approve'] ) ? 'checked="checked"' : '', 
	'DISABLE_AVATAR_APPROVE_NO' => ( !$new['disable_avatar_approve'] ) ? 'checked="checked"' : '', 
	'AVATAR_POSTS' => $new['avatar_posts'],
	'DEFAULT_AVATAR_GUESTS_URL' => $new['default_avatar_guests_url'],
	'DEFAULT_AVATAR_USERS_URL' => $new['default_avatar_users_url'],
	'DEFAULT_AVATAR_GUESTS' => ( !$new['default_avatar_set'] ) ? 'checked="checked"' : '',
	'DEFAULT_AVATAR_USERS' => ( $new['default_avatar_set'] ) ? 'checked="checked"' : '',
	'DEFAULT_AVATAR_BOTH' => ( $new['default_avatar_set'] == 2) ? 'checked="checked"' : '',
	'DEFAULT_AVATAR_NONE' => ( $new['default_avatar_set'] == 3) ? 'checked="checked"' : '',
	'AVATAR_GENERATOR_YES' => ( $new['allow_avatar_generator'] ) ? 'checked="checked"' : '',
	'AVATAR_GENERATOR_NO' => ( !$new['allow_avatar_generator'] ) ? 'checked="checked"' : '',
	'AVATAR_GENERATOR_TEMPLATE_PATH' => $new['avatar_generator_template_path'],
	
	// Bancard
	'L_BLUECARD_LIMIT' => $lang['Bluecard_limit'], 
	'L_BLUECARD_LIMIT_EXPLAIN' => $lang['Bluecard_limit_explain'], 
	'L_BLUECARD_LIMIT_2' => $lang['Bluecard_limit_2'], 
	'L_BLUECARD_LIMIT_2_EXPLAIN' => $lang['Bluecard_limit_2_explain'], 
	'L_MAX_USER_BANCARD' => $lang['Max_user_bancard'], 
	'L_MAX_USER_BANCARD_EXPLAIN' => $lang['Max_user_bancard_explain'], 
	'L_MAX_USER_VOTEBANCARD' => $lang['Max_user_votebancard'], 
	'L_MAX_USER_VOTEBANCARD_EXPLAIN' => $lang['Max_user_votebancard_explain'], 
	'L_REPORT_FORUM' => $lang['Report_forum'],
	'L_REPORT_FORUM_EXPLAIN' => $lang['Report_forum_explain'],
	'L_ENABLE_BANCARDS' => $lang['Enable_bancards'], 

	'ENABLE_BANCARDS_YES' => ( $new['enable_bancards'] ) ? 'checked="checked"' : '', 
	'ENABLE_BANCARDS_NO' => ( !$new['enable_bancards'] ) ? 'checked="checked"' : '',
	'BLUECARD_LIMIT' => $new['bluecard_limit'], 
	'BLUECARD_LIMIT_2' => $new['bluecard_limit_2'], 
	'MAX_USER_BANCARD' => $new['max_user_bancard'], 
	'MAX_USER_VOTEBANCARD' => $new['max_user_votebancard'], 
	'REPORT_FORUM_SELECT' => forum_select($new['report_forum'], 'report_forum'),
	
	// Birthday
	'L_ENABLE_BIRTHDAY_GREETING' => $lang['Enable_birthday_greeting'], 
	'L_BIRTHDAY_GREETING_EXPLAIN' => $lang['Birthday_greeting_expain'], 
	'L_BIRTHDAY_REQUIRED' => $lang['Birthday_required'], 
	'L_MIN_USER_AGE' => $lang['Min_user_age'], 
	'L_MIN_USER_AGE_EXPLAIN' => $lang['Min_user_age_explain'], 
	'L_MIN' => $lang['Limit_username_min'],
	'L_MAX' => $lang['Limit_username_max'],
	'L_BIRTHDAY_LOOKFORWARD' => $lang['Birthday_lookforward'], 
	'L_BIRTHDAY_LOOKFORWARD_EXPLAIN' => $lang['Birthday_lookforward_explain'], 
		
	'BIRTHDAY_VIEWTOPIC_YES' => ( $new['birthday_viewtopic'] ) ? 'checked="checked"' : '', 
	'BIRTHDAY_VIEWTOPIC_NO' => ( !$new['birthday_viewtopic'] ) ? 'checked="checked"' : '',
	'BIRTHDAY_GREETING_YES' => ( $new['birthday_greeting'] ) ? 'checked="checked"' : '', 
	'BIRTHDAY_GREETING_NO' => ( !$new['birthday_greeting'] ) ? 'checked="checked"' : '',
	'BIRTHDAY_REQUIRED_YES' => ( $new['birthday_required'] ) ? 'checked="checked"' : '', 
	'BIRTHDAY_REQUIRED_NO' => ( !$new['birthday_required'] ) ? 'checked="checked"' : '', 
	'MAX_USER_AGE' => $new['max_user_age'], 
	'MIN_USER_AGE' => $new['min_user_age'],
	'BIRTHDAY_LOOKFORWARD' => $new['birthday_check_day'],

	// Board		
	'L_SITE_NAME' => $lang['Site_name'],
	'L_SITE_DESCRIPTION' => $lang['Site_desc'],
	'L_SITE_DESCRIPTION_EXPLAIN' => $lang['Site_desc_explain'] . ' ' . $lang['group_description_explain'],
	'L_SITE_LOGO' => $lang['Site_logo'],
	'L_SITE_LOGO_EXPLAIN' => $lang['Site_logo_explain'],
	'L_DEBUG_VALUE' => $lang['Debug_value'], 
	'L_DEBUG_EMAIL' => $lang['Debug_email'], 
	'L_DEBUG_EMAIL_EXPLAIN' => $lang['Debug_email_explain'], 
	'L_DEFAULT_STYLE' => $lang['Default_style'],
	'L_OVERRIDE_STYLE' => $lang['Override_style'],
	'L_OVERRIDE_STYLE_EXPLAIN' => $lang['Override_style_explain'],
	'L_DEFAULT_LANGUAGE' => $lang['Default_language'],
	'L_DATE_FORMAT' => $lang['Admin_Date_format'],
	'L_CLOCK_FORMAT' => $lang['Admin_Clock_format'],
	'L_CLOCK_FORMAT_EXPLAIN' => sprintf($lang['Admin_Clock_format_explain'], '<a href="javascript:void(0);" onclick="clocks();">', '</a>'),
	'L_SYSTEM_TIMEZONE' => $lang['System_timezone'],
	'L_PAGE_TRANSITION' => $lang['Page_transition'], 
	'L_DISABLE_CALLHOME' => $lang['Disable_Callhome'],
 	'L_ENABLE_IP_LOGGER' => $lang['Enable_ip_logger'],
	'L_SITEMAP' => $lang['Enable_sitemap'],
	'L_SITEMAP_EXPLAIN' => $lang['Enable_sitemap_explain'],

	'SITENAME' => $new['sitename'],
	'SITE_DESCRIPTION' => $new['site_desc'], 
	'LOGO_URL' => $new['logo_url'],
	'STYLE_SELECT' => style_select($new['default_style'], 'default_style', '../templates'),
	'OVERRIDE_STYLE_YES' => ( $new['override_user_style'] ) ? 'checked="checked"' : '',
	'OVERRIDE_STYLE_NO' => ( !$new['override_user_style'] ) ? 'checked="checked"' : '',
	'LANG_SELECT' => language_select($new['default_lang'], 'default_lang', 'language'),
	'DEFAULT_DATEFORMAT' => admin_date_format_select($new['default_dateformat'], $timezone_select),
	'TIMEZONE_SELECT' => admin_tz_select($new['board_timezone'], 'board_timezone'),
	'CLOCK_SELECT' => clock_format_select($new['default_clock'], 'default_clock'),
	'S_SITEMAP_ENABLED' => ( $new['board_sitemap'] ) ? 'checked="checked"' : '',
	'S_SITEMAP_DISABLED' => ( !$new['board_sitemap'] ) ? 'checked="checked"' : '',
	'S_DISABLE_CALLHOME_YES' => ( $new['callhome_disable'] ) ? 'checked="checked"' : '',
	'S_DISABLE_CALLHOME_NO' => ( !$new['callhome_disable'] ) ? 'checked="checked"' : '',
	'PAGE_TRANSITION' => page_transition_select($new['page_transition']),
	'S_DEBUG_VALUE_ENABLED' => ( $new['debug_value'] ) ? 'checked="checked"' : '', 
	'S_DEBUG_VALUE_DISABLED' => ( !$new['debug_value'] ) ? 'checked="checked"' : '', 
	'S_DEBUG_EMAIL_ENABLED' => ( $new['debug_email'] ) ? 'checked="checked"' : '', 
	'S_DEBUG_EMAIL_DISABLED' => ( !$new['debug_email'] ) ? 'checked="checked"' : '', 
	'IP_LOGGER_YES' => ( $new['enable_ip_logger'] ) ? 'checked="checked"' : '',
	'IP_LOGGER_NO' => ( !$new['enable_ip_logger'] ) ? 'checked="checked"' : '',

	// Board Disable
	'L_DISABLE_BOARD' => $lang['Board_disable_board'], 
	'L_DISABLE_BOARD_EXPLAIN' => $lang['Board_disable_text_explain'] . ' ' . $lang['group_description_explain'], 
	'L_BOARD_DISABLE_MODE' => $lang['Board_disable_mode'],
	'L_BOARD_DISABLE_MODE_EXPLAIN' => $lang['Board_disable_mode_explain'],

	'S_DISABLE_BOARD_YES' => ( $new['board_disable'] ) ? 'checked="checked"' : '',
	'S_DISABLE_BOARD_NO' => ( !$new['board_disable'] ) ? 'checked="checked"' : '',
	'BOARD_DISABLE_TEXT' => $new['board_disable_text'],
	'BOARD_DISABLE_MODE' => disable_mode_select($new['board_disable_mode']),

	// Board Load
	'L_LOAD_SETTINGS_EXPLAIN' => $lang['Load_settings_explain'], 
	'L_DISPLAY_LAST_EDITED' => $lang['Display_last_edited'],
	'L_USERTIME_VIEWTOPIC' => $lang['Usertime_viewtopic'], 
	'L_VIEWTOPIC_EXTRASTATS' => $lang['Viewtopic_extrastats'], 
	'L_VIEWTOPIC_MEMNUM' => $lang['Viewtopic_memnum'], 
	'L_YEAR_STARS' => $lang['Year_stars'],
	'L_YEAR_STARS_EXPLAIN' => $lang['Year_stars_explain'],
	'L_VIEWTOPIC_FLAG' => $lang['Viewtopic_flag'], 
	'L_VIEWTOPIC_USERGROUPS' => $lang['Viewtopic_usergroups'], 
	'L_VIEWTOPIC_BUDDYIMG' => $lang['Viewtopic_buddyimg'], 
	'L_VIEWTOPIC_STYLE' => $lang['Viewtopic_style'], 
	'L_VIEWTOPIC_VIEWPOST' => $lang['Viewtopic_viewpost'], 
	'L_VIEWTOPIC_DOWNPOST' => $lang['Viewtopic_downpost'], 
	'L_VIEWTOPIC_STATUS' => $lang['Viewtopic_status'], 
	'L_VIEWTOPIC_EDITDATE' => $lang['Viewtopic_editdate'], 
	'L_ENABLE_WRAP' => $lang['wrap_enable'],
	'L_WRAP_MIN' => $lang['wrap_min'],
	'L_WRAP_MAX' => $lang['wrap_max'],
	'L_WRAP_DEF' => $lang['wrap_def'],
	'L_WRAP_UNITS' => $lang['Word_Wrap_Extra'],
	'L_REDUCE_IMGS' => $lang['Reduce_imgs'], 
	'L_REDUCE_IMGS_EXPLAIN' => $lang['Reduce_imgs_explain'], 
	'L_TOTAL_VISITORS' => $lang['Total_visitors'], 
	'L_TOTAL_VISITORS_EXPLAIN' => $lang['Total_visitors_explain'], 
	'L_JUMP_TOPIC' => $lang['Jump_to_topic'], 
	'L_INDEX_CHAT' => $lang['Index_chat'], 
	'L_INDEX_CHAT_EXPLAIN' => $lang['Index_chat_explain'], 
	'L_BOARD_HITS' => $lang['Board_hits'], 
	'L_BOARD_HITS_EXPLAIN' => $lang['Board_hits_explain'], 
	'L_BOARD_SERVERLOAD' => $lang['Board_serverload'], 
	'L_INDEX_NEW_USERS' => $lang['Index_new_users'], 
	'L_INDEX_NEW_USERS_EXPLAIN' => $lang['Index_new_users_explain'], 
	'L_INDEX_ACTIVE_FORUMS' => $lang['Index_active_forums'], 
	'L_INDEX_ACTIVE_FORUMS_EXPLAIN' => $lang['Index_actve_forums_explain'], 
	'L_ENABLE_QUICK_TITLES' => $lang['Enable_quick_titles'],
	'L_STAT_INDEX' => $lang['Stat_index'],
	'L_STAT_INDEX_EXPLAIN' => $lang['Stat_index_explain'],
	'L_INDEX_GROUPS' => $lang['Index_groups'], 

	'INDEX_GROUPS_YES' => ( $new['index_groups'] ) ? 'checked="checked"' : '',
	'INDEX_GROUPS_NO' => ( !$new['index_groups'] ) ? 'checked="checked"' : '',
	'STAT_INDEX' => $new['stat_index'],
	'ENABLE_QUICK_TITLES_YES' => ( $new['enable_quick_titles'] ) ? 'checked="checked"' : '',
	'ENABLE_QUICK_TITLES_NO' => ( !$new['enable_quick_titles'] ) ? 'checked="checked"' : '', 
	'INDEX_ACTIVE_FORUMS_YES' => ( $new['index_active_in_forum'] ) ? 'checked="checked"' : '',
	'INDEX_ACTIVE_FORUMS_NO' => ( !$new['index_active_in_forum'] ) ? 'checked="checked"' : '',
	'INDEX_NEW_USERS_YES' => ( $new['index_new_reg_users'] ) ? 'checked="checked"' : '',
	'INDEX_NEW_USERS_NO' => ( !$new['index_new_reg_users'] ) ? 'checked="checked"' : '',
	'BOARD_HITS_YES' => ( $new['board_hits'] ) ? 'checked="checked"' : '',
	'BOARD_HITS_NO' => ( !$new['board_hits'] ) ? 'checked="checked"' : '',
	'JUMP_TOPIC_YES' => ( $new['jump_to_topic'] ) ? 'checked="checked"' : '',
	'JUMP_TOPIC_NO' => ( !$new['jump_to_topic'] ) ? 'checked="checked"' : '',
	'VISIT_COUNTER_YES' => ( $new['visit_counter_index'] ) ? 'checked="checked"' : '', 
	'VISIT_COUNTER_NO' => ( !$new['visit_counter_index'] ) ? 'checked="checked"' : '', 
	'TOTAL_VISITORS' => $new['visit_counter'], 
	'INDEX_CHAT_YES' => ( $new['chat_index'] ) ? 'checked="checked"' : '',
	'INDEX_CHAT_NO' => ( !$new['chat_index'] ) ? 'checked="checked"' : '',
	'REDUCE_IMGS_YES' => ( $new['reduce_bbcode_imgs'] ) ? 'checked="checked"' : '', 
	'REDUCE_IMGS_NO' => ( !$new['reduce_bbcode_imgs'] ) ? 'checked="checked"' : '',
	'WRAP_ENABLE' => ( $new['wrap_enable'] ) ? 'checked="checked"' : '',
	'WRAP_DISABLE' => ( !$new['wrap_enable'] ) ? 'checked="checked"' : '',
	'WRAP_MIN' => $new['wrap_min'],
	'WRAP_DEF' => $new['wrap_def'],
	'WRAP_MAX' => $new['wrap_max'],
	'VIEWTOPIC_EDITDATE_YES' => ( $new['viewtopic_editdate'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_EDITDATE_NO' => ( !$new['viewtopic_editdate'] ) ? 'checked="checked"' : '',
	'VIEWTOPIC_STATUS_YES' => ( $new['viewtopic_status'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_STATUS_NO' => ( !$new['viewtopic_status'] ) ? 'checked="checked"' : '',
	'VIEWTOPIC_VIEWPOST_YES' => ( $new['viewtopic_viewpost'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_VIEWPOST_NO' => ( !$new['viewtopic_viewpost'] ) ? 'checked="checked"' : '',
	'VIEWTOPIC_DOWNPOST_YES' => ( $new['viewtopic_downpost'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_DOWNPOST_NO' => ( !$new['viewtopic_downpost'] ) ? 'checked="checked"' : '',
	'VIEWTOPIC_STYLE_YES' => ( $new['viewtopic_style'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_STYLE_NO' => ( !$new['viewtopic_style'] ) ? 'checked="checked"' : '',
	'VIEWTOPIC_BUDDYIMG_YES' => ( $new['viewtopic_buddyimg'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_BUDDYIMG_NO' => ( !$new['viewtopic_buddyimg'] ) ? 'checked="checked"' : '',
	'VIEWTOPIC_USERGROUPS_YES' => ( $new['viewtopic_usergroups'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_USERGROUPS_NO' => ( !$new['viewtopic_usergroups'] ) ? 'checked="checked"' : '',
	'VIEWTOPIC_FLAG_YES' => ( $new['viewtopic_flag'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_FLAG_NO' => ( !$new['viewtopic_flag'] ) ? 'checked="checked"' : '',
	'VIEWTOPIC_YEARSTARS_YES' => ( $new['viewtopic_yearstars'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_YEARSTARS_NO' => ( !$new['viewtopic_yearstars'] ) ? 'checked="checked"' : '',
	'YEAR_STARS' => $new['year_stars'],
	'VIEWTOPIC_MEMNUM_YES' => ( $new['viewtopic_memnum'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_MEMNUM_NO' => ( !$new['viewtopic_memnum'] ) ? 'checked="checked"' : '',
	'VIEWTOPIC_EXTRASTATS_YES' => ( $new['viewtopic_extrastats'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_EXTRASTATS_NO' => ( !$new['viewtopic_extrastats'] ) ? 'checked="checked"' : '',
	'USERTIME_VIEWTOPIC_YES' => ( $new['usertime_viewtopic'] ) ? 'checked="checked"' : '', 
	'USERTIME_VIEWTOPIC_NO' => ( !$new['usertime_viewtopic'] ) ? 'checked="checked"' : '',
	'DISPLAY_LAST_EDITED_YES' => ( $new['display_last_edited'] ) ? 'checked="checked"' : '', 
	'DISPLAY_LAST_EDITED_NO' => ( !$new['display_last_edited'] ) ? 'checked="checked"' : '',

	// Board Usage Statistics
    'L_BBUS_SETTINGS_EXPLAIN' => $lang['BBUS_Settings_Explain'],
    'L_BBUS_ENABLE' => $lang['BBUS_Enable'],
    'L_BBUS_SETTING_VIEWLEVEL' => $lang['BBUS_Setting_ViewLevel'],
    'L_BBUS_SETTING_VIEWLEVEL_CAPTION' => $lang['BBUS_Setting_ViewLevel_Caption'],
    'L_BBUS_VIEWLEVEL_ANONYMOUS_CAPTION' => $lang['BBUS_ViewLevel_Anonymous_Caption'],
    'L_BBUS_VIEWLEVEL_ANONYMOUS_EXPLAIN' => $lang['BBUS_ViewLevel_Anonymous_Explain'],
    'L_BBUS_VIEWLEVEL_SELF_CAPTION' => $lang['BBUS_ViewLevel_Self_Caption'],
    'L_BBUS_VIEWLEVEL_SELF_EXPLAIN' => $lang['BBUS_ViewLevel_Self_Explain'],
    'L_BBUS_VIEWLEVEL_USERS_CAPTION' => $lang['BBUS_ViewLevel_Users_Caption'],
    'L_BBUS_VIEWLEVEL_USERS_EXPLAIN' => $lang['BBUS_ViewLevel_Users_Explain'],
    'L_BBUS_VIEWLEVEL_MODERATORS_CAPTION' => $lang['BBUS_ViewLevel_Moderators_Caption'],
    'L_BBUS_VIEWLEVEL_MODERATORS_EXPLAIN' => $lang['BBUS_ViewLevel_Moderators_Explain'],
    'L_BBUS_VIEWLEVEL_ADMINS_CAPTION' => $lang['BBUS_ViewLevel_Admins_Caption'],
    'L_BBUS_VIEWLEVEL_ADMINS_EXPLAIN' => $lang['BBUS_ViewLevel_Admins_Explain'],
    'L_BBUS_VIEWLEVEL_SPECIALGRP_CAPTION' => $lang['BBUS_ViewLevel_SpecialGrp_Caption'],
    'L_BBUS_VIEWLEVEL_SPECIALGRP_EXPLAIN' => $lang['BBUS_ViewLevel_SpecialGrp_Explain'],
    'L_BBUS_SETTING_VIEWOPTIONS_CAPTION' => $lang['BBUS_Setting_ViewOptions_Caption'],
    'L_BBUS_SETTING_VIEWOPTIONS_EXPLAIN' => $lang['BBUS_Setting_ViewOptions_Explain'],
    'L_BBUS_VIEWOPTION_SHOW_ALL_FORUMS_CAPTION' => $lang['BBUS_ViewOption_Show_All_Forums_Caption'],
    'L_BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_CAPTION' => $lang['BBUS_ViewOption_PCTUTUP_Column_Visible_Caption'],
    'L_BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_CAPTION' => $lang['BBUS_ViewOption_Misc_Section_Visible_Caption'],
    'L_BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_CAPTION' => $lang['BBUS_ViewOption_Misc_TotPrunedPosts_Visible_Caption'],
    'L_BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_CAPTION' => $lang['BBUS_ViewOption_Viewer_Scalable_PR_Caption'],
    'L_BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_CAPTION' => $lang['BBUS_ViewOption_Viewer_Scalable_TR_Caption'],
    'L_BBUS_DEFAULT_POST_RATE_SCALING_CAPTION' => $lang['BBUS_Settings_Default_Post_Rate_Scaling_Caption'],
    'L_BBUS_DEFAULT_POST_RATE_SCALING_EXPLAIN' => $lang['BBUS_Settings_Default_Post_Rate_Scaling_Explain'],
    'L_BBUS_DEFAULT_TOPIC_RATE_SCALING_CAPTION' => $lang['BBUS_Settings_Default_Topic_Rate_Scaling_Caption'],
    'L_BBUS_DEFAULT_TOPIC_RATE_SCALING_EXPLAIN' => $lang['BBUS_Settings_Default_Topic_Rate_Scaling_Explain'],

 	'BBUS_ENABLE_YES' => ( $new['bb_usage_stats_enable'] ) ? 'checked="checked"' : '', 
 	'BBUS_ENABLE_NO' => ( !$new['bb_usage_stats_enable'] ) ? 'checked="checked"' : '', 
    'BBUS_SETTING_SPECIALGRP_SELECT' => specialgrp_select(BBUS_CONFIGPROP_SPECIALGRP_NAME, $new[BBUS_CONFIGPROP_SPECIALGRP_NAME]),
    'BBUS_SETTING_VIEWOPTIONS_VALUE' => $new[BBUS_CONFIGPROP_VIEWOPTIONS_NAME],
    'BBUS_SETTING_VIEWLEVEL_VALUE' => $new[BBUS_CONFIGPROP_VIEWLEVEL_NAME],
    'BBUS_VIEWLEVEL_ANONYMOUS_CHKED' => ( ($viewlevel & BBUS_VIEWLEVEL_ANONYMOUS) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWLEVEL_SELF_CHKED' => ( ($viewlevel & BBUS_VIEWLEVEL_SELF) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWLEVEL_USERS_CHKED' => ( ($viewlevel & BBUS_VIEWLEVEL_USERS) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWLEVEL_MODERATORS_CHKED' => ( ($viewlevel & BBUS_VIEWLEVEL_MODERATORS) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWLEVEL_ADMINS_CHKED' => ( ($viewlevel & BBUS_VIEWLEVEL_ADMINS) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWLEVEL_SPECIALGRP_CHKED' => ( ($viewlevel & BBUS_VIEWLEVEL_SPECIALGRP) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWLEVEL_ANONYMOUS_FLAGVALUE' => BBUS_VIEWLEVEL_ANONYMOUS,
    'BBUS_VIEWLEVEL_SELF_FLAGVALUE' => BBUS_VIEWLEVEL_SELF,
    'BBUS_VIEWLEVEL_USERS_FLAGVALUE' => BBUS_VIEWLEVEL_USERS,
    'BBUS_VIEWLEVEL_MODERATORS_FLAGVALUE' => BBUS_VIEWLEVEL_MODERATORS,
    'BBUS_VIEWLEVEL_ADMINS_FLAGVALUE' => BBUS_VIEWLEVEL_ADMINS,
    'BBUS_VIEWLEVEL_SPECIALGRP_FLAGVALUE' => BBUS_VIEWLEVEL_SPECIALGRP,
    'BBUS_VIEWOPTION_SHOW_ALL_FORUMS_CHKED' => ( ($viewoptions & BBUS_VIEWOPTION_SHOW_ALL_FORUMS) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_CHKED' => ( ($viewoptions & BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_CHKED' => ( ($viewoptions & BBUS_VIEWOPTION_MISC_SECTION_VISIBLE) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_CHKED' => ( ($viewoptions & BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_VISIBLE) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_CHKED' => ( ($viewoptions & BBUS_VIEWOPTION_VIEWER_SCALABLE_PR) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_CHKED' => ( ($viewoptions & BBUS_VIEWOPTION_VIEWER_SCALABLE_TR) != 0 ) ? 'checked="checked"' : '',
    'BBUS_VIEWOPTION_SHOW_ALL_FORUMS_FLAGVALUE' => BBUS_VIEWOPTION_SHOW_ALL_FORUMS,
    'BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE_FLAGVALUE' => BBUS_VIEWOPTION_PCTUTUP_COLUMN_VISIBLE,
    'BBUS_VIEWOPTION_MISC_SECTION_VISIBLE_FLAGVALUE' => BBUS_VIEWOPTION_MISC_SECTION_VISIBLE,
    'BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_FLAGVALUE' => BBUS_VIEWOPTION_MISC_TOTPRUNEDPOSTS_VISIBLE,
    'BBUS_VIEWOPTION_VIEWER_SCALABLE_PR_FLAGVALUE' => BBUS_VIEWOPTION_VIEWER_SCALABLE_PR,
    'BBUS_VIEWOPTION_VIEWER_SCALABLE_TR_FLAGVALUE' => BBUS_VIEWOPTION_VIEWER_SCALABLE_TR,
    'BBUS_DEFAULT_POST_RATE_SCALING_SELECT' => scaleby_select('', BBUS_CONFIGPROP_PRSCALE_NAME, BBUS_SCALING_MIN, BBUS_SCALING_MAX, $new[BBUS_CONFIGPROP_PRSCALE_NAME]),
    'BBUS_DEFAULT_TOPIC_RATE_SCALING_SELECT' => scaleby_select('', BBUS_CONFIGPROP_TRSCALE_NAME, BBUS_SCALING_MIN, BBUS_SCALING_MAX, $new[BBUS_CONFIGPROP_TRSCALE_NAME]),
 
 	// Bots
    'L_BOT_WHOSONLINE' => $lang['Bots_whosonline'],
    'L_BOT_WHOSONLINE_EXPLAIN' => $lang['Bots_whosonline_explain'],
    'L_BOT_TRACKING' => $lang['Bots_tracking'],
    'L_BOT_EMAIL' => $lang['Bots_email'],
    'L_BOT_EMAIL_EXPLAIN' => $lang['Bots_email_explain'],
 	
 	'BOT_WHOSONLINE_YES' => ( $new['enable_bots_whosonline'] ) ? 'checked="checked"' : '', 
 	'BOT_WHOSONLINE_NO' => ( !$new['enable_bots_whosonline'] ) ? 'checked="checked"' : '', 
 	'BOT_TRACKING_YES' => ( $new['enable_bot_tracking'] ) ? 'checked="checked"' : '', 
 	'BOT_TRACKING_NO' => ( !$new['enable_bot_tracking'] ) ? 'checked="checked"' : '', 
 	'BOT_EMAIL_YES' => ( $new['enable_bot_email'] ) ? 'checked="checked"' : '', 
 	'BOT_EMAIL_NO' => ( !$new['enable_bot_email'] ) ? 'checked="checked"' : '', 
		
 	// Calendar
  	'L_CAL_EVENT_LMT' => $lang['Mini_Cal_events'], 
  	'L_CAL_EVENT_LMT_EXPLAIN' => $lang['Minical_event_lmt_explain'], 
	'L_CAL_UPCOMING' => $lang['Minical_upcoming'], 
	'L_CAL_UPCOMING_EXPLAIN' => $lang['Minical_upcoming_explain'], 
	'L_CAL_SEARCH' => $lang['Search_options'],
 	'L_CAL_SEARCH_EXPLAIN' => $lang['Minical_search_explain'],
 	'L_POSTS' => $lang['Posts'],
 	'L_EVENTS' => $lang['Events'],
 		
 	'CAL_EVENT_LMT' => $new['minical_event_lmt'], 
	'CAL_UPCOMING' => $new['minical_upcoming'], 
	'CAL_SEARCH_POSTS' => ( $new['minical_search'] == 'POSTS') ? 'checked="checked"' : '', 
	'CAL_SEARCH_EVENTS' => ( $new['minical_search'] == 'EVENTS') ? 'checked="checked"' : '', 
	 	
 	// Cookie
	'L_COOKIE_SETTINGS_EXPLAIN' => $lang['Cookie_settings_explain'], 
	'L_COOKIE_DOMAIN' => $lang['Cookie_domain'],
	'L_COOKIE_NAME' => $lang['Cookie_name'], 
	'L_COOKIE_PATH' => $lang['Cookie_path'], 
	'L_COOKIE_SECURE' => $lang['Cookie_secure'], 
	'L_COOKIE_SECURE_EXPLAIN' => $lang['Cookie_secure_explain'], 

	'COOKIE_DOMAIN' => $new['cookie_domain'], 
	'COOKIE_NAME' => $new['cookie_name'], 
	'COOKIE_PATH' => $new['cookie_path'], 
	'S_COOKIE_SECURE_ENABLED' => ( $new['cookie_secure'] ) ? 'checked="checked"' : '', 
	'S_COOKIE_SECURE_DISABLED' => ( !$new['cookie_secure'] ) ? 'checked="checked"' : '',
   	
	// eBay Auction
	'L_AUCTIONS_ENABLE' => $lang['Auctions_enable'],
	'L_AUCTIONS_USERID' => $lang['Auctions_ebay_userid'],
	'L_AUCTIONS_THUMBS' => $lang['Auctions_enable_thumbs'],
	'L_AUCTIONS_TZ' => $lang['Auctions_tz_offset'],
	'L_AUCTIONS_TZ_EXPLAIN' => $lang['Auctions_tz_offset_explain'],
	'L_AUCTIONS_ENDED' => $lang['Auctions_enable_ended'],
	'L_AUCTIONS_ENDED_EXPLAIN' => $lang['Auctions_enable_ended_explain'],
	'L_AUCTIONS_ORDER' => $lang['Auctions_sort_order'],
	'L_AUCTIONS_ORDER0' => ( $new['auction_sort_order'] == 1 ) ? $lang['Auctions_sort_1'] : ( ( $new['auction_sort_order'] == 2 ) ? $lang['Auctions_sort_2'] : ( ( $new['auction_sort_order'] == 3 ) ? $lang['Auctions_sort_3'] : ( ( $new['auction_sort_order'] == 4 ) ? $lang['Auctions_sort_4'] : $lang['Auctions_sort_5']))),
	'L_AUCTIONS_ORDER1' => $lang['Auctions_sort_1'],
	'L_AUCTIONS_ORDER2' => $lang['Auctions_sort_2'],
	'L_AUCTIONS_ORDER3' => $lang['Auctions_sort_3'],
	'L_AUCTIONS_ORDER4' => $lang['Auctions_sort_4'],
	'L_AUCTIONS_ORDER5' => $lang['Auctions_sort_5'],
	'L_AUCTIONS_AMT' => $lang['Auctions_amt'],
	'L_AUCTIONS_AMT_EXPLAIN' => $lang['Auctions_amt_explain'],
	'L_AUCTIONS_EBAYURL' => $lang['Auctions_ebay_url'],
	'L_AUCTIONS_EBAYURL_EXPLAIN' => $lang['Auctions_ebay_url_explain'],

	'S_AUCTIONS_ENABLE_YES' => ( $new['auction_enable'] ) ? 'checked="checked"' : '',
	'S_AUCTIONS_ENABLE_NO' => ( !$new['auction_enable'] ) ? 'checked="checked"' : '',
	'S_AUCTIONS_ENABLE_THUMBS_YES' => ( $new['auction_enable_thumbs'] ) ? 'checked="checked"' : '',
	'S_AUCTIONS_ENABLE_THUMBS_NO' => ( !$new['auction_enable_thumbs'] ) ? 'checked="checked"' : '',
	'S_AUCTIONS_USERID' => $new['auction_ebay_user_id'],
	'S_AUCTIONS_EBAYURL' => $new['auction_ebay_url'],
	'S_AUCTIONS_TZ' => $auction_tz_select,
	'S_AUCTIONS_AMT' => $new['auction_amt'],
	'S_AUCTIONS_ENDED' => $new['auction_enable_ended'],
	'S_AUCTIONS_ORDER' => $new['auction_sort_order'],

	// Email
	'L_EMAIL_SETTINGS_EXPLAIN' => $lang['Email_settings_explain'],
	'L_BOARD_EMAIL_FORM' => $lang['Board_email_form'], 
	'L_BOARD_EMAIL_FORM_EXPLAIN' => $lang['Board_email_form_explain'], 
	'L_ADMIN_EMAIL' => $lang['Admin_email'],
	'L_ADMIN_EMAIL_EXPLAIN' => $lang['Admin_email_explain'],
	'L_EMAIL_SIG' => $lang['Email_sig'],
	'L_EMAIL_SIG_EXPLAIN' => $lang['Email_sig_explain'],
	'L_SMTP_SETTINGS' => $lang['SMTP'] . ' ' . $lang['Setting'],
	'L_USE_SMTP' => $lang['Use_SMTP'],
	'L_USE_SMTP_EXPLAIN' => $lang['Use_SMTP_explain'],
	'L_SMTP_SERVER' => $lang['SMTP_server'], 
	'L_SMTP_USERNAME' => $lang['SMTP_username'], 
	'L_SMTP_USERNAME_EXPLAIN' => $lang['SMTP_username_explain'], 
	'L_SMTP_PASSWORD' => $lang['SMTP_password'], 
	'L_SMTP_PASSWORD_EXPLAIN' => $lang['SMTP_password_explain'], 
	'L_PRUNE_EMAIL' => $lang['Prune_email'],
	'L_PRUNE_EMAIL_EXPLAIN' => $lang['Prune_email_explain'],

	'PRUNE_EMAIL_YES' => ( $new['user_prune_notify'] ) ? 'checked="checked"' : '', 
	'PRUNE_EMAIL_NO' => ( !$new['user_prune_notify'] ) ? 'checked="checked"' : '', 
	'BOARD_EMAIL_FORM_ENABLE' => ( $new['board_email_form'] ) ? 'checked="checked"' : '', 
	'BOARD_EMAIL_FORM_DISABLE' => ( !$new['board_email_form'] ) ? 'checked="checked"' : '', 
	'EMAIL_FROM' => $new['board_email'],
	'EMAIL_SIG' => $new['board_email_sig'],
	'SMTP_YES' => ( $new['smtp_delivery'] ) ? 'checked="checked"' : '',
	'SMTP_NO' => ( !$new['smtp_delivery'] ) ? 'checked="checked"' : '',
	'SMTP_HOST' => $new['smtp_host'],
	'SMTP_USERNAME' => $new['smtp_username'],
	'SMTP_PASSWORD' => $new['smtp_password'],
		
	// Forum
	'L_TOPICS_PER_PAGE' => $lang['Topics_per_page'],
	'L_POSTS_PER_PAGE' => $lang['Posts_per_page'],
	'L_HOT_THRESHOLD' => $lang['Hot_threshold'],
	'L_ENABLE_PRUNE' => $lang['Enable_prune'],
	'L_ALLOW_HTML' => $lang['Allow_HTML'],
	'L_ALLOW_BBCODE' => $lang['Allow_BBCode'],
	'L_ALLOWED_TAGS' => $lang['Allowed_tags'],
	'L_ALLOWED_TAGS_EXPLAIN' => $lang['Allowed_tags_explain'],
	'L_SPLIT_GLOBAL_ANNOUNCE' => $lang['split_global_announce'],
	'L_SPLIT_ANNOUNCE' => $lang['split_announce'],
	'L_SPLIT_STICKY' => $lang['split_sticky'],
	'L_LOCKED_LAST' => $lang['Locked_last'],
	'L_LOCKED_LAST_EXPLAIN' => $lang['Locked_last_explain'],
	'L_BIN_FORUM' => $lang['Bin_forum'],
	'L_BIN_FORUM_EXPLAIN' => $lang['Bin_forum_explain'],
	'L_JOURNAL_FORUM' => $lang['Journal_Forum'],
	'L_JOURNAL_FORUM_EXPLAIN' => $lang['Journal_Forum_Explain'],
	'L_NO_POST_COUNT' => $lang['No_post_count'],
	'L_NO_POST_COUNT_EXPLAIN' => $lang['No_post_count_explain'],
	'L_SIMILAR_TOPICS' => $lang['Enable_similar_topics'],
	'L_ENABLE_NULL_VOTE' => $lang['Enable_null_vote'],
	'L_ENABLE_NULL_VOTE_EXPLAIN' => $lang['Enable_null_vote_explain'],
    'L_CAPITALIZATION' => $lang['Capitalization'],
    'L_CAPITALIZATIONEXPLAIN' => $lang['Capitalizationexplain'],
    'L_CAPITALIZATION_NONE' => $lang['Capitalization_none'],
    'L_CAPITALIZATION_UPPERCASE' => $lang['Capitalization_uppercase'],
    'L_CAPITALIZATION_LOWERCASE' => $lang['Capitalization_lowercase'],
    'L_CAPITALIZATION_FIRSTCHAR' => $lang['Capitalization_firstchar'],
    'L_CAPITALIZATION_FIRSTCHARPERWORD' => $lang['Capitalization_firstcharperword'],
	'L_ENABLE_QUICK_REPLY' => $lang['Enable_quick_reply'],
	'L_ENABLE_QUICK_REPLY_EXPLAIN' => $lang['Enable_quick_reply_explain'],
	'L_ALLOW_SWEARYWORDS' => $lang['Allow_Swearywords'],
	'L_ALLOW_SWEARYWORDS_EXPLAIN' => $lang['Allow_Swearywords_explain'],
	'L_ENABLE_TOPIC_VIEW_USERS' => $lang['Enable_topic_view_users'],
	'L_ENABLE_TOPIC_VIEW_USERS_EXPLAIN' => $lang['Enable_topic_view_users_explain'],
	'L_ENABLE_TELLAFRIEND' => $lang['Enable_tellafriend'],
	'L_DISABLE_TOPIC_WATCHING' => $lang['Disable_topic_watching'],
	'L_DISABLE_TOPIC_WATCHING_EXPLAIN' => $lang['Disable_topic_watching_explain'],

	'DISABLE_TOPIC_WATCHING_YES' => ( !$new['disable_topic_watching'] ) ? 'checked="checked"' : '',
	'DISABLE_TOPIC_WATCHING_NO' => ( $new['disable_topic_watching'] ) ? 'checked="checked"' : '',
	'TELLAFRIEND_YES' => ( $new['enable_tellafriend'] ) ? 'checked="checked"' : '',
	'TELLAFRIEND_NO' => ( !$new['enable_tellafriend'] ) ? 'checked="checked"' : '',
	'TOPIC_VIEW_USERS_YES' => ( $new['enable_topic_view_users'] ) ? 'checked="checked"' : '',
	'TOPIC_VIEW_USERS_NO' => ( !$new['enable_topic_view_users'] ) ? 'checked="checked"' : '',
	'SWEARYWORDS_YES' => ( $new['allow_swearywords'] ) ? 'checked="checked"' : '',
	'SWEARYWORDS_NO' => ( !$new['allow_swearywords'] ) ? 'checked="checked"' : '',
	'QUICK_REPLY_ENABLE' => ( $new['enable_quick_reply'] ) ? 'checked="checked"' : '',
	'QUICK_REPLY_DISABLE' => ( !$new['enable_quick_reply'] ) ? 'checked="checked"' : '',
    'CAPITALIZATION_NONE_CHECKED' => ( $new['capitalization'] == USER_AVATAR_NONE ) ? 'checked="checked"' : '',
    'CAPITALIZATION_UPPERCASE_CHECKED' => ( $new['capitalization'] == USER_AVATAR_UPLOAD ) ? 'checked="checked"' : '',
    'CAPITALIZATION_LOWERCASE_CHECKED' => ( $new['capitalization'] == USER_AVATAR_REMOTE ) ? 'checked="checked"' : '',
    'CAPITALIZATION_FIRSTCHAR_CHECKED' => ( $new['capitalization'] == USER_AVATAR_GALLERY ) ? 'checked="checked"' : '',
    'CAPITALIZATION_FIRSTCHARPERWORD_CHECKED' => ( $new['capitalization'] == USER_AVATAR_GENERATOR ) ? 'checked="checked"' : '',
	'SPLIT_GLOBAL_ANNOUNCE_YES' => ( $new['split_global_announce'] ) ? 'checked="checked"' : '',
	'SPLIT_GLOBAL_ANNOUNCE_NO' => ( !$new['split_global_announce'] ) ? 'checked="checked"' : '',
	'SPLIT_ANNOUNCE_YES' => ( $new['split_announce'] ) ? 'checked="checked"' : '',
	'SPLIT_ANNOUNCE_NO' => ( !$new['split_announce'] ) ? 'checked="checked"' : '',
	'SPLIT_STICKY_YES' => ( $new['split_sticky'] )   ? 'checked="checked"' : '',
	'SPLIT_STICKY_NO' => ( !$new['split_sticky'] )   ? 'checked="checked"' : '',
	'PRUNE_YES' => ( $new['prune_enable'] ) ? 'checked="checked"' : '',
	'PRUNE_NO' => ( !$new['prune_enable'] ) ? 'checked="checked"' : '', 
	'TOPICS_PER_PAGE' => $new['topics_per_page'],
	'POSTS_PER_PAGE' => $new['posts_per_page'],
	'HOT_TOPIC' => $new['hot_threshold'],
	'HTML_TAGS' => $new['allow_html_tags'], 
	'HTML_YES' => ( $new['allow_html'] ) ? 'checked="checked"' : '',
	'HTML_NO' => ( !$new['allow_html'] ) ? 'checked="checked"' : '',
	'BBCODE_YES' => ( $new['allow_bbcode'] ) ? 'checked="checked"' : '',
	'BBCODE_NO' => ( !$new['allow_bbcode'] ) ? 'checked="checked"' : '',
	'LOCKED_LAST_YES' => ( $new['locked_last'] ) ? 'checked="checked"' : '',
	'LOCKED_LAST_NO' => ( !$new['locked_last'] ) ? 'checked="checked"' : '',
	'JOURNAL_SELECT' => forum_select($new['journal_forum_id'], 'journal_forum_id'),	
	'NO_POST_COUNT_FORUM_ID' => $new['no_post_count_forum_id'],
	'BIN_FORUM_SELECT' => forum_select($new['bin_forum'], 'bin_forum'),
	'S_SIMILAR_TOPICS_YES' => ( $new['enable_similar_topics'] ) ? 'checked="checked"' : '',
	'S_SIMILAR_TOPICS_NO' => ( !$new['enable_similar_topics'] ) ? 'checked="checked"' : '',
	
	// Forum Modules	
	'L_FORUM_MODULE_DISABLE' => $lang['Forum_module_disable'],
	'L_FORUM_MODULE_DISABLE_EXPLAIN' => $lang['Forum_module_disable_explain'],
	'L_FORUM_MODULE_WIDTH' => $lang['Forum_module_width'],
	'L_FORUM_MODULE_WIDTH_EXPLAIN' => $lang['Forum_module_width_explain'],
	'L_ENABLE_FORUM_MODULE_LINKS' => $lang['Forum_module_links'],
	'L_ENABLE_FORUM_MODULE_LINKS_EXPLAIN' => $lang['Forum_module_links_explain'],
	'L_ENABLE_FORUM_MODULE_WEATHER' => $lang['Forum_module_weather'],
	'L_ENABLE_FORUM_MODULE_WEATHER_EXPLAIN' => $lang['Forum_module_weather_explain'],
	'L_WEATHER_CODE' => $lang['Zip_code'],
	'L_AS_DEFAULT' => sprintf($lang['As_default'], '<a href="javascript:void(0);" onclick="spawn();">', '</a>'),
	'L_ENABLE_FORUM_MODULE_GLANCE' => $lang['Forum_module_glance'],
	'L_ENABLE_FORUM_MODULE_EXPLAIN' => $lang['Forum_module_explain'],
	'L_GLANCE_BLOCK_TITLE' => $lang['Glance_forum_block_title'],
	'L_GLANCE_FORUM_ID' => $lang['Glance_forum_id'],
	'L_GLANCE_FORUM_ID_EXPLAIN' => $lang['Glance_forum_id_explain'],
	'L_GLANCE_NUM' => $lang['Glance_num'],
	'L_GLANCE_SCROLL' => $lang['Glance_scroll'],
	'L_GLANCE_NUM_EXPLAIN' => $lang['Glance_num_explain'],
	'L_GLANCE_BLOCK_TITLE_BTM' => $lang['Glance_forum_block_title_btm'],
	'L_ENABLE_FORUM_MODULE_CALENDAR' => $lang['Forum_module_calendar'],
	'L_ENABLE_FORUM_MODULE_CALENDAR_EXPLAIN' => $lang['Forum_module_calendar_explain'],
	'L_ENABLE_FORUM_MODULE_CLOCK'=> $lang['Forum_module_clock'],
	'L_ENABLE_FORUM_MODULE_PHOTO' => $lang['Forum_module_photo'],
	'L_ENABLE_FORUM_MODULE_QUOTE' => $lang['Forum_module_quote'],
	'L_ENABLE_FORUM_MODULE_KARMA' => $lang['Forum_module_karma'],
	'L_ENABLE_FORUM_MODULE_KARMA_EXPLAIN' => $lang['Forum_module_explain'] . ' ' . sprintf($lang['Forum_module_karma_explain'], '<a href="' . append_sid('admin_board.'.$phpEx.'?mode=karma') . '">', '</a>'),
	'L_ENABLE_FORUM_MODULE_NEWUSERS' => $lang['Forum_module_newusers'],
	'L_ENABLE_FORUM_MODULE_TOPPOSTERS' => $lang['Forum_module_topposters'],
	'L_ENABLE_FORUM_MODULE_TOPPOSTERS_EXPLAIN' => $lang['Forum_module_topposters_explain'],
	'L_ENABLE_FORUM_MODULE_POINTS' => sprintf($lang['Forum_module_points'], $board_config['points_name']),
	'L_ENABLE_FORUM_MODULE_DLOADS' => $lang['Forum_module_dloads'],
	'L_ENABLE_FORUM_MODULE_RANDOMUSER' => $lang['Forum_module_randomuser'],
	'L_ENABLE_FORUM_MODULE_GAME' => $lang['Forum_module_game'],
	'L_ENABLE_FORUM_MODULE_WALLPAPER' => $lang['Forum_module_wallpaper'],
	'L_ENABLE_FORUM_MODULE_DONORS' => $lang['Forum_module_donors'],
	'L_ENABLE_FORUM_MODULE_SHOUTBOX' => $lang['Forum_module_shoutbox'],
	'L_ENABLE_FORUM_MODULE_SHOUTBOX_EXPLAIN' => $lang['Forum_module_explain'] . ' ' . sprintf($lang['Forum_module_shoutbox_explain'], '<a href="' . append_sid('admin_board.'.$phpEx.'?mode=shoutbox') . '">', '</a>'),
	'L_ENABLE_FORUM_MODULE_SHOUTCAST' => $lang['Forum_module_shoutcast'],

	'S_FORUM_MODULE_SHOUTCAST_YES' => ( $new['forum_module_shoutcast'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_SHOUTCAST_NO' => ( !$new['forum_module_shoutcast'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_DONORS_YES' => ( $new['forum_module_donors'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_DONORS_NO' => ( !$new['forum_module_donors'] ) ? 'checked="checked"' : '',
	'S_GLANCE_NEWS_SCROLL_YES' => ( $new['glance_news_scroll'] ) ? 'checked="checked"' : '',
	'S_GLANCE_NEWS_SCROLL_NO' => ( !$new['glance_news_scroll'] ) ? 'checked="checked"' : '',
	'S_GLANCE_RECENT_SCROLL_YES' => ( $new['glance_recent_scroll'] ) ? 'checked="checked"' : '',
	'S_GLANCE_RECENT_SCROLL_NO' => ( !$new['glance_recent_scroll'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_DISABLE_YES' => ( $new['forum_module_disable'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_DISABLE_NO' => ( !$new['forum_module_disable'] ) ? 'checked="checked"' : '',
	'FORUM_MODULE_WIDTH' => $new['forum_module_width'],
	'S_FORUM_MODULE_LINKS_YES' => ( $new['forum_module_links'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_LINKS_NO' => ( !$new['forum_module_links'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_WEATHER_YES' => ( $new['forum_module_weather'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_WEATHER_NO' => ( !$new['forum_module_weather'] ) ? 'checked="checked"' : '',
	'ZIPCODE' => $ziprow['user_zipcode'],
	'S_FORUM_MODULE_GLANCE_YES' => ( $new['forum_module_glance'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_GLANCE_NO' => ( !$new['forum_module_glance'] ) ? 'checked="checked"' : '',
	'GLANCE_SELECT' => forum_select($new['glance_forum_id'], 'glance_forum_id'),
	'GLANCE_NEWS_NUM' => $new['glance_news_num'],
	'GLANCE_RECENT_NUM' => $new['glance_recent_num'],
	'GLANCE_FORUM_NEWS_TITLE' => $new['glance_forum_news_title'],
	'GLANCE_FORUM_DISCUSS_TITLE' => $new['glance_forum_discuss_title'],
	'S_FORUM_MODULE_CLOCK_YES' => ( $new['forum_module_clock'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_CLOCK_NO' => ( !$new['forum_module_clock'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_CALENDAR_YES' => ( $new['forum_module_calendar'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_CALENDAR_NO' => ( !$new['forum_module_calendar'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_PHOTO_YES' => ( $new['forum_module_photo'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_PHOTO_NO' => ( !$new['forum_module_photo'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_QUOTE_YES' => ( $new['forum_module_quote'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_QUOTE_NO' => ( !$new['forum_module_quote'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_KARMA_YES' => ( $new['forum_module_karma'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_KARMA_NO' => ( !$new['forum_module_karma'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_NEWUSERS_YES' => ( $new['forum_module_newusers'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_NEWUSERS_NO' => ( !$new['forum_module_newusers'] ) ? 'checked="checked"' : '',	
	'S_FORUM_MODULE_TOPPOSTERS_YES' => ( $new['forum_module_topposters'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_TOPPOSTERS_NO' => ( !$new['forum_module_topposters'] ) ? 'checked="checked"' : '',	
	'S_FORUM_MODULE_POINTS_YES' => ( $new['forum_module_points'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_POINTS_NO' => ( !$new['forum_module_points'] ) ? 'checked="checked"' : '',	
	'S_FORUM_MODULE_DLOADS_YES' => ( $new['forum_module_dloads'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_DLOADS_NO' => ( !$new['forum_module_dloads'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_RANDOMUSER_YES' => ( $new['forum_module_randomuser'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_RANDOMUSER_NO' => ( !$new['forum_module_randomuser'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_GAME_YES' => ( $new['forum_module_game'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_GAME_NO' => ( !$new['forum_module_game'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_WALLPAPER_YES' => ( $new['forum_module_wallpaper'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_WALLPAPER_NO' => ( !$new['forum_module_wallpaper'] ) ? 'checked="checked"' : '',
	
    // Gender
	'L_GENDER_REQUIRED' => $lang['Gender_required'], 
	'L_GENDER_INDEX' => $lang['Gender_index'], 
	'L_BIRTHDAY_VIEWTOPIC' => $lang['Birthday_viewtopic'], 
	'L_GENDER_VIEWTOPIC' => $lang['Gender_viewtopic'], 

	'GENDER_VIEWTOPIC_YES' => ( $new['gender_viewtopic'] ) ? 'checked="checked"' : '', 
	'GENDER_VIEWTOPIC_NO' => ( !$new['gender_viewtopic'] ) ? 'checked="checked"' : '',
	'GENDER_REQUIRED_YES' => ( $new['gender_required'] ) ? 'checked="checked"' : '', 
	'GENDER_REQUIRED_NO' => ( !$new['gender_required'] ) ? 'checked="checked"' : '',
	'GENDER_INDEX_YES' => ( $new['gender_index'] ) ? 'checked="checked"' : '', 
	'GENDER_INDEX_NO' => ( !$new['gender_index'] ) ? 'checked="checked"' : '',

	// Inline Ads
	'L_AD_AFTER_POST' => $lang['ad_after_post'],
	'L_AD_EVERY_POST' => $lang['ad_every_post'],
	'L_AD_DISPLAY' => $lang['ad_display'],
	'L_AD_ALL' => $lang['ad_all'],
	'L_AD_REG' => $lang['ad_reg'],
	'L_AD_GUEST' => $lang['ad_guest'],
	'L_AD_EXCLUDE' => $lang['ad_exclude'],
	'L_AD_EXCLUDE_EXPLAIN' => $lang['ad_exclude_explain'],
	'L_AD_FORUMS' => $lang['ad_forums'],
	'L_AD_FORUMS_EXPLAIN' => $lang['ad_forums_explain'],
	'L_AD_STYLE' => $lang['ad_style'],
	'L_AD_NEW_STYLE' => $lang['ad_new_style'],
	'L_AD_OLD_STYLE' => $lang['ad_old_style'],
	'L_AD_POST_THRESHOLD' => $lang['ad_post_threshold'], 
	'L_AD_POST_THRESHOLD_EXPLAIN' => $lang['ad_post_threshold_explain'], 
			
	'AD_AFTER_POST' => $new['ad_after_post'],
	'AD_EVERY_POST' => $new['ad_every_post'],
	'AD_FORUMS' => $new['ad_no_forums'],
	'AD_NO_GROUPS' => $new['ad_no_groups'],
	'AD_POST_THRESHOLD' => $new['ad_post_threshold'],
	'AD_ALL' => ( $new['ad_who'] == ADMIN ) ? 'checked="checked"' : '',
	'AD_REG' => ( $new['ad_who'] == USER ) ? 'checked="checked"' : '',
	'AD_GUEST' => ( $new['ad_who'] == ANONYMOUS ) ? 'checked="checked"' : '',
	'AD_OLD_STYLE' => ( $new['ad_old_style'] ) ? 'checked="checked"' : '',
	'AD_NEW_STYLE' => ( !$new['ad_old_style'] ) ? 'checked="checked"' : '',
	
	// Karma
	'L_KARMA_SETTINGS' => $lang['Karma_settings'],
	'L_KARMA_ALLOW' => $lang['Allow_Karma'], 
	'L_KARMA_ALLOW_ADMINS' => $lang['Karma_admins'], 
	'L_KARMA_ALLOW_ADMINS_EXPLAIN' => $lang['Karma_admins_explain'], 
	'L_KARMA_FLOOD_INTERVAL' => $lang['Karma_flood_interval'], 
	'L_KARMA_FLOOD_INTERVAL_EXPLAIN' => $lang['Karma_flood_interval_explain'], 

	'KARMA_YES' => ( $new['allow_karma'] ) ? 'checked="checked"' : '',
	'KARMA_NO' => ( !$new['allow_karma'] ) ? 'checked="checked"' : '',
	'KARMA_FLOOD_INTERVAL' => $new['karma_flood_interval'],
	'KARMA_ADMINS_YES' => ( $new['karma_admins'] ) ? 'checked="checked"' : '',
	'KARMA_ADMINS_NO' => ( !$new['karma_admins'] ) ? 'checked="checked"' : '',

	// Knowledge Base
	'L_NEW_NAME' => $lang['New_title'],
	'L_NEW_EXPLAIN' => $lang['New_explain'],
	'L_APPROVE_NEW_NAME' => $lang['Approve_new_name'],
	'L_APPROVE_NEW_EXPLAIN' => $lang['Approve_new_explain'],
	'L_EDIT_NAME' => $lang['Edit_name'],
	'L_EDIT_EXPLAIN' => $lang['Edit_explain'],
	'L_PRE_TEXT_HEADER' => $lang['Pre_text_header'],
	'L_PRE_TEXT_BODY' => $lang['Message'],
	'L_PRE_TEXT_EXPLAIN' => $lang['Pre_text_explain'],
	'L_APPROVE_EDIT_NAME' => $lang['Approve_edit_name'],
	'L_APPROVE_EDIT_EXPLAIN' => $lang['Approve_edit_explain'],
	'L_NOTIFY_NAME' => $lang['Notify_name'],
	'L_NOTIFY_EXPLAIN' => $lang['Notify_explain'],
	'L_EMAIL' => $lang['Email'],
	'L_PM' => $lang['PM'],
	'L_ADMIN_ID_NAME' => $lang['Admin_id_name'],
	'L_ADMIN_ID_EXPLAIN' => $lang['Admin_id_explain'],
	'L_COMMENTS' => $lang['Allow_comments'],
	'L_COMMENTS_EXPLAIN' => $lang['Allow_comments_explain'],
	'L_FORUM_ID' => $lang['Forum'],
	'L_FORUM_ID_EXPLAIN' => $lang['Forum_id_explain'],
	'L_ANON_NAME' => $lang['Allow_anon_name'], 
    'L_ANON_EXPLAIN' => $lang['Allow_anon_explain'], 
	'L_DEL_TOPIC' => $lang['Del_topic'],
	'L_DEL_TOPIC_EXPLAIN' => $lang['Del_topic_explain'],

	'S_NEW_YES' => ( $new['kb_allow_new'] ) ? 'checked="checked"' : '',
	'S_NEW_NO' => ( !$new['kb_allow_new'] ) ? 'checked="checked"' : '',
	'S_APPROVE_NEW_YES' => ( $new['kb_approve_new'] ) ? 'checked="checked"' : '',
	'S_APPROVE_NEW_NO' => ( !$new['kb_approve_new'] ) ? 'checked="checked"' : '',
	'S_EDIT_YES' => ( $new['kb_allow_edit'] ) ? 'checked="checked"' : '',
	'S_EDIT_NO' => ( !$new['kb_allow_edit'] ) ? 'checked="checked"' : '',
	'S_SHOW_PRETEXT' => ( $new['kb_show_pt'] ) ? 'checked="checked"' : '',
	'S_HIDE_PRETEXT' => ( !$new['kb_show_pt'] ) ? 'checked="checked"' : '',
	'L_PT_BODY' => $new['kb_pt_body'],			
	'S_APPROVE_EDIT_YES' => ( $new['kb_approve_edit'] ) ? 'checked="checked"' : '',
	'S_APPROVE_EDIT_NO' => ( !$new['kb_approve_edit'] ) ? 'checked="checked"' : '',
	'S_NOTIFY_NONE' => ( !$new['kb_notify'] ) ? 'checked="checked"' : '',
	'S_NOTIFY_EMAIL' => ( $new['kb_notify'] == 2 ) ? 'checked="checked"' : '',
	'S_NOTIFY_PM' => ( $new['kb_notify'] ) ? 'checked="checked"' : '',		
	'ADMIN_ID' => $new['kb_admin_id'],		
	'S_COMMENTS_YES' => ( $new['kb_comments'] ) ? 'checked="checked"' : '',
	'S_COMMENTS_NO' => ( !$new['kb_comments'] ) ? 'checked="checked"' : '',		
	'FORUMS' => forum_select($new['kb_forum_id'], 'kb_forum_id'),		
    'S_ANON_YES' => ( $new['kb_allow_anon'] ) ? 'checked="checked"' : '', 
    'S_ANON_NO' => ( !$new['kb_allow_anon'] ) ? 'checked="checked"' : '',		
	'S_DEL_TOPIC_YES' => ( $new['kb_del_topic'] ) ? 'checked="checked"' : '',
	'S_DEL_TOPIC_NO' => ( !$new['kb_del_topic'] ) ? 'checked="checked"' : '',
	
	// Lexicon
	'L_LEXICON_TITLE' => $lang['Lexicon_titel'],
	'L_LEXICON_TITLE_EXPLAIN' => $lang['Lexicon_titel_explain'],
	'L_LEXICON_DESCRIPTION' => $lang['Lexicon_description'],
	'L_LEXICON_DESCRIPTION_EXPLAIN' => $lang['Lexicon_description_explain'],

	'LEXICON_TITLE' => $new['lexicon_title'],
	'LEXICON_DESCRIPTION' => $new['lexicon_description'],
		
	// Login
	'L_ALLOW_AUTOLOGIN' => $lang['Allow_autologin'],
	'L_ALLOW_AUTOLOGIN_EXPLAIN' => $lang['Allow_autologin_explain'],
	'L_AUTOLOGIN_CHECK' => $lang['Autologin_check'],
	'L_AUTOLOGIN_CHECK_EXPLAIN' => $lang['Autologin_check_explain'],
	'L_AUTOLOGIN_TIME' => $lang['Autologin_time'],
	'L_AUTOLOGIN_TIME_EXPLAIN' => $lang['Autologin_time_explain'],
	'L_MAX_LOGIN_ATTEMPTS' => $lang['Max_login_attempts'],
   	'L_MAX_LOGIN_ATTEMPTS_EXPLAIN' => $lang['Max_login_attempts_explain'],
   	'L_LOGIN_RESET_TIME' => $lang['Login_reset_time'],
   	'L_LOGIN_RESET_TIME_EXPLAIN' => $lang['Login_reset_time_explain'],
	'L_ADMIN_LOGIN' => $lang['admin_login'],
	'L_ADMIN_LOGIN_EXPLAIN' => $lang['admin_login_explain'],
	'L_HIDDE_LAST_LOGON' => $lang['Hidde_last_logon'], 
	'L_HIDDE_LAST_LOGON_EXPLAIN' => $lang['Hidde_last_logon_expain'],

	'HIDDE_LAST_LOGON_YES' => ( $new['hidde_last_logon'] ) ? 'checked="checked"' : '', 
	'HIDDE_LAST_LOGON_NO' => ( !$new['hidde_last_logon'] ) ? 'checked="checked"' : '', 
	'ALLOW_AUTOLOGIN_YES' => ( $new['allow_autologin'] ) ? 'checked="checked"' : '',
	'ALLOW_AUTOLOGIN_NO' => ( !$new['allow_autologin'] ) ? 'checked="checked"' : '',
	'AUTOLOGIN_CHECK_YES' => ( $new['autologin_check'] ) ? 'checked="checked"' : '',
	'AUTOLOGIN_CHECK_NO' => ( !$new['autologin_check'] ) ? 'checked="checked"' : '',
	'AUTOLOGIN_TIME' => (int) $new['max_autologin_time'],
   	'MAX_LOGIN_ATTEMPTS' => $new['max_login_attempts'],
   	'LOGIN_RESET_TIME' => $new['login_reset_time'],
	'ADMIN_LOGIN_YES' => ( $new['admin_login'] ) ? 'checked="checked"' : '',
	'ADMIN_LOGIN_NO' => ( !$new['admin_login'] ) ? 'checked="checked"' : '',

	// Medals
	'L_MEDAL_SETTINGS' => $lang['Medal_setting'],
	'L_ALLOW_MEDAL' => $lang['Allow_medal'],
	'L_ALLOW_MEDAL2' => $lang['Allow_medal2'],
	'L_MEDAL_RAND' => $lang['Medal_rand'],
	'L_MEDAL_RAND_EXPLAIN' => $lang['Medal_rand_explain'],
	'L_MEDAL_DISPLAY' => $lang['Medal_display'],
	'L_MEDAL_DISPLAY_EXPLAIN' => $lang['Medal_display_explain'],
	'L_MEDAL_SIZE' => $lang['Medal_size'],
	'L_MEDAL_SIZE_EXPLAIN' => $lang['Medal_size_explain'],

	'RAND_YES' => ( $new['medal_display_order'] ) ? 'checked="checked"' : '',
	'RAND_NO' => ( !$new['medal_display_order'] ) ? 'checked="checked"' : '',
	'MEDAL_YES' => ( $new['allow_medal_display_viewtopic'] ) ? 'checked="checked"' : '',
	'MEDAL_NO' => ( !$new['allow_medal_display_viewtopic'] ) ? 'checked="checked"' : '',
	'MEDAL2_YES' => ( $new['allow_medal_display_viewprofile'] ) ? 'checked="checked"' : '',
	'MEDAL2_NO' => ( !$new['allow_medal_display_viewprofile'] ) ? 'checked="checked"' : '',
	'MEDAL_DISPALY_ROW' => $new['medal_display_row'],
	'MEDAL_DISPALY_COL' => $new['medal_display_col'],
	'MEDAL_DISPALY_W' => $new['medal_display_width'],
	'MEDAL_DISPALY_H' => $new['medal_display_height'],
	
	// Meta Tags
	'L_META_SETTINGS_EXPLAIN' => $lang['Meta_settings_explain'],
	'L_META_KEYWORDS' => $lang['Meta_keywords'],
	'L_META_KEYWORDS_EXPLAIN' => $lang['Meta_keywords_explain'],
	'L_META_DESCRIPTION' => $lang['Site_desc'],
	'L_META_DESCRIPTION_EXPLAIN' => $lang['Meta_description_explain'],
	'L_META_REVISIT' => $lang['Meta_revisit'],
	'L_META_REVISIT_EXPLAIN' => $lang['Meta_revisit_explain'],
	'L_META_AUTHOR' => $lang['Meta_author'],
	'L_META_AUTHOR_EXPLAIN' => $lang['Meta_author_explain'],
	'L_META_OWNER' => $lang['Meta_owner'],
	'L_META_OWNER_EXPLAIN' => $lang['Meta_owner_explain'],
	'L_META_DISTRIBUTION' => $lang['Meta_distribution'],
	'L_META_DISTRIBUTION_EXPLAIN' => $lang['Meta_distribution_explain'],
	'L_META_ROBOTS' => $lang['Bots_Spiders'],
	'L_META_ROBOTS_EXPLAIN' => $lang['Meta_robots_explain'],
	'L_META_ABSTRACT' => $lang['Meta_abstract'],
	'L_META_ABSTRACT_EXPLAIN' => $lang['Meta_abstract_explain'],
	'L_META_LANGUAGE' => $lang['Default_language'],
	'L_META_LANGUAGE_EXPLAIN' => $lang['Meta_language_explain'],
	'L_META_RATING' => $lang['Rating'],
	'L_META_RATING_EXPLAIN' => $lang['Meta_rating_explain'],
	'L_META_IDENTIFIER' => $lang['Meta_indentifier'],
	'L_META_IDENTIFIER_EXPLAIN' => $lang['Meta_indentifier_explain'],
	'L_META_REPLYTO' => $lang['Meta_replyto'],
	'L_META_REPLYTO_EXPLAIN' => $lang['Meta_replyto_explain'],
	'L_META_CATEGORY' => $lang['Category'],
	'L_META_CATEGORY_EXPLAIN' => $lang['Meta_category_explain'],
	'L_META_COPYRIGHT' => $lang['Meta_copyright'],
	'L_META_COPYRIGHT_EXPLAIN' => $lang['Meta_copyright_explain'],
	'L_META_GENERATOR' => $lang['Meta_generator'],
	'L_META_GENERATOR_EXPLAIN' => $lang['Meta_generator_explain'],
	'L_META_CREATION_DATE' => $lang['Meta_creation_date'],
	'L_META_CREATION_DATE_EXPLAIN' => $lang['Meta_creation_date_explain'],
	'L_META_REVISION_DATE' => $lang['Meta_revision_date'],
	'L_META_REVISION_DATE_EXPLAIN' => $lang['Meta_revision_date_explain'],
	'L_META_REDIRECT_TIME' => $lang['Meta_redirect_time'],
	'L_META_REDIRECT_TIME_EXPLAIN' => $lang['Meta_redirect_time_explain'],
	'L_META_REDIRECT_URL' => $lang['Meta_redirect_url'],
	'L_META_REDIRECT_URL_EXPLAIN' => $lang['Meta_redirect_url_explain'],
	'L_META_REFRESH' => $lang['Meta_refresh'],
	'L_META_REFRESH_EXPLAIN' => $lang['Meta_refresh_explain'],
	'L_META_PRAGMA' => $lang['Meta_pragma'],
	'L_META_PRAGMA_EXPLAIN' => $lang['Meta_pragma_explain'],
	'L_META_HTTP_SETTINGS' => $lang['Meta_http_settings'] . ' ' . $lang['Configuration'],
	'L_META_HTTP_SETTINGS_EXPLAIN' => $lang['Meta_http_settings_explain'],

	'META_IDENTIFIER' => $new['meta_identifier_url'],
	'META_REPLYTO' => $new['meta_reply_to'],
	'META_CATEGORY' => $new['meta_category'],
	'META_COPYRIGHT' => $new['meta_copyright'],
	'META_GENERATOR' => $new['meta_generator'],
	'META_CREATION_DAY' => $new['meta_date_creation_day'],
	'META_CREATION_MONTH' => $new['meta_date_creation_month'],
	'META_CREATION_YEAR' => $new['meta_date_creation_year'],
	'META_REVISION_DAY' => $new['meta_date_revision_day'],
	'META_REVISION_MONTH' => $new['meta_date_revision_month'],
	'META_REVISION_YEAR' => $new['meta_date_revision_year'],
	'META_REDIRECT_TIME' => $new['meta_redirect_url_time'],
	'META_REDIRECT_URL' => $new['meta_redirect_url_adress'],
	'META_REFRESH' => $new['meta_refresh'],
	'META_PRAGMA' => $new['meta_pragma'],
	'META_LANGUAGE' => $new['meta_language'],
	'META_RATING' => $new['meta_rating'],
	'META_KEYWORDS' => $new['meta_keywords'],
	'META_DESCRIPTION' => $new['meta_description'],
	'META_REVISIT' => $new['meta_revisit'],
	'META_AUTHOR' => $new['meta_author'],
	'META_OWNER' => $new['meta_owner'],
	'META_DISTRIBUTION' => $new['meta_distribution'],
	'META_ROBOTS' => $new['meta_robots'],
	'META_ABSTRACT' => $new['meta_abstract'],

	// Moderator CP
	'L_MODS_VIEWIPS' => $lang['Mods_viewips'],
	'L_MODS_VIEWIPS_EXPLAIN' => $lang['Mods_viewips_explain'],
	'L_MODULE_AVDELETE' => $lang['Module_avdelete'],
	'L_MODULE_AVDELETE_EXPLAIN' => $lang['Module_avdelete_explain'],
	'L_MODULE_BACKUP' => $lang['Module_backup'],
	'L_MODULE_BACKUP_EXPLAIN' => $lang['Module_backup_explain'],
	'L_MODULE_DISALLOW' => $lang['Module_disallow'],
	'L_MODULE_DISALLOW_EXPLAIN' => $lang['Module_disallow_explain'],
	'L_MODULE_MASS_EMAIL' => $lang['Module_mass_email'],
	'L_MODULE_MASS_EMAIL_EXPLAIN' => $lang['Module_mass_email_explain'],
	'L_MODULE_RANKS' => $lang['Module_ranks'],
	'L_MODULE_RANKS_EXPLAIN' => $lang['Module_ranks_explain'],
	'L_MODULE_SMILIES' => $lang['Module_smilies'],
	'L_MODULE_SMILIES_EXPLAIN' => $lang['Module_smilies_explain'],
	'L_MODULE_USER_BAN' => $lang['Module_user_ban'],
	'L_MODULE_USER_BAN_EXPLAIN' => $lang['Module_user_ban_explain'],
	'L_MODULE_USERS' => $lang['Module_users'],
	'L_MODULE_USERS_EXPLAIN' => $lang['Module_users_explain'],
	'L_MODULE_WORDS' => $lang['Module_words'],
	'L_MODULE_WORDS_EXPLAIN' => $lang['Module_words_explain'],

	'MODS_VIEWIPS_YES' => ( $new['mods_viewips'] ) ? ' checked="checked"' : '',
	'MODS_VIEWIPS_NO' => ( !$new['mods_viewips'] ) ? ' checked="checked"' : '',
	'MODULE_AVDELETE_ENABLE' => ( $new['enable_module_avdelete'] ) ? ' checked="checked"' : '',
	'MODULE_AVDELETE_DISABLE' => ( !$new['enable_module_avdelete'] ) ? ' checked="checked"' : '',
	'MODULE_BACKUP_ENABLE' => ( $new['enable_module_backup'] ) ? ' checked="checked"' : '',
	'MODULE_BACKUP_DISABLE' => ( !$new['enable_module_backup'] ) ? ' checked="checked"' : '',
	'MODULE_DISALLOW_ENABLE' => ( $new['enable_module_disallow'] ) ? ' checked="checked"' : '',
	'MODULE_DISALLOW_DISABLE' => ( !$new['enable_module_disallow'] ) ? ' checked="checked"' : '',
	'MODULE_MASS_EMAIL_ENABLE' => ( $new['enable_module_mass_email'] ) ? ' checked="checked"' : '',
	'MODULE_MASS_EMAIL_DISABLE' => ( !$new['enable_module_mass_email'] ) ? ' checked="checked"' : '',
	'MODULE_RANKS_ENABLE' => ( $new['enable_module_ranks'] ) ? ' checked="checked"' : '',
	'MODULE_RANKS_DISABLE' => ( !$new['enable_module_ranks'] ) ? ' checked="checked"' : '',
	'MODULE_SMILIES_ENABLE' => ( $new['enable_module_smilies'] ) ? ' checked="checked"' : '',
	'MODULE_SMILIES_DISABLE' => ( !$new['enable_module_smilies'] ) ? ' checked="checked"' : '',
	'MODULE_USER_BAN_ENABLE' => ( $new['enable_module_user_ban'] ) ? ' checked="checked"' : '',
	'MODULE_USER_BAN_DISABLE' => ( !$new['enable_module_user_ban'] ) ? ' checked="checked"' : '',
	'MODULE_USERS_ENABLE' => ( $new['enable_module_users'] ) ? ' checked="checked"' : '',
	'MODULE_USERS_DISABLE' => ( !$new['enable_module_users'] ) ? ' checked="checked"' : '',
	'MODULE_WORDS_ENABLE' => ( $new['enable_module_words'] ) ? ' checked="checked"' : '',
	'MODULE_WORDS_DISABLE' => ( !$new['enable_module_words'] ) ? ' checked="checked"' : '',

	// MyInfo
	'L_ENABLE_MYINFO' => $lang['Enable_myInfo'],
	'L_MYINFO_TITLE' => $lang['myInfo_title'],
	'L_MYINFO_NAME' => $lang['myInfo_name'],
	'L_MYINFO_NAME_EXPLAIN' => $lang['myInfo_name_explain'],
	'L_MYINFO_INSTRUCTIONS' => $lang['myInfo_instructions'],
	'L_MYINFO_INSTRUCTIONS_EXPLAIN' => $lang['myInfo_instructions_explain'],

	'MYINFO_YES' => ( $new['myInfo_enable'] ) ? 'checked="checked"' : '',
	'MYINFO_NO' => ( !$new['myInfo_enable'] ) ? 'checked="checked"' : '', 
	'MYINFO_NAME' => $new['myInfo_name'],
	'MYINFO_INSTRUCTIONS' => $new['myInfo_instructions'],

	// Newsbar
	'L_ENABLE_NEWSBAR' => $lang['Enable_News_bar'],
	'L_ENABLE_NEWSBAR_EXPLAIN' => $lang['Enable_News_bar_explain'],
	'L_NEWS_TITLE' => $lang['Title'],
	'L_NEWS_STYLE' => $lang['Style'],
	'L_NEWS_BOLD' => $lang['News_bold'],
	'L_NEWS_ITAL' => $lang['News_ital'],
	'L_NEWS_UNDER' => $lang['News_under'],
	'L_NEWS_SIZE' => $lang['Font_size'],
	'L_NEWS_BLOCK' => $lang['Message'],
	'L_NEWS_BLOCK_EXPLAIN' => $lang['group_description_explain'],
	'L_SCROLL_SPEED' => $lang['scroll_speed'],
	'L_SCROLL_ACTION' => $lang['scroll_action'],
	'L_SCROLL_BEHAVIOR' =>$lang['scroll_behavior'],
	'L_SCROLL_SIZE' => $lang['scroll_size'],

	'S_NEWSBAR_YES' => ( $new['forum_module_newsbar'] ) ? 'checked="checked"' : '',
	'S_NEWSBAR_NO' => ( !$new['forum_module_newsbar'] ) ? 'checked="checked"' : '',
	'NEWS_TITLE' => $new['news_title'],
	'NEWS_BLOCK' => $new['news_block'],
	'NEWS_STYLE_SELECT' => nss_select($new['news_style'], 'news_style'),
	'NEWS_BOLD_SELECT' => nbs_select($new['news_bold'], 'news_bold'),
	'NEWS_ITAL_SELECT' => nis_select($new['news_ital'], 'news_ital'),
	'NEWS_UNDER_SELECT' => nus_select($new['news_under'], 'news_under'),
	'NEWS_SIZE_SELECT' => ns_select($new['news_size'], 'news_size'),
	'SCROLL_SPEED_SELECT' => ssp_select($new['scroll_speed'], 'scroll_speed'),
	'SCROLL_ACTION_SELECT' => sa_select($new['scroll_action'], 'scroll_action'),
	'SCROLL_BEHAVIOR_SELECT' => sb_select($new['scroll_behavior'], 'scroll_behavior'),
	'SCROLL_SIZE_SELECT' => ss_select($new['scroll_size'], 'scroll_size'),

	// Notes
	'L_ENABLE_NOTES' => $lang['Notes_enable'],
	'L_ENABLE_NOTES_EXPLAIN' => $lang['Notes_enable_explain'],
	'L_NOTES' => $lang['Notes_admin'],

	'ENABLE_NOTES_YES' => ( $new['enable_user_notes'] ) ? 'checked="checked"' : '', 
	'ENABLE_NOTES_NO' => ( !$new['enable_user_notes'] ) ? 'checked="checked"' : '', 
	'NOTES' => $new['notes'],
		
	// Password Generator
	'L_PASS_GEN_ENABLE' => $lang['Pass_gen_enable'],
	'L_PASS_GEN_LENGTH' => $lang['Pass_gen_length'],
	'L_PASS_GEN_LENGTH_EXPLAIN' => $lang['Pass_gen_length_explain'],
	'L_PASS_GEN_ALPHA' => $lang['Pass_gen_alpha'],
	'L_PASS_GEN_SPECIAL' => $lang['Pass_gen_special'],
	'L_PASS_GEN_UPPERCASE' => $lang['Pass_gen_upper'],
	'L_PASS_GEN_LOWERCASE' => $lang['Pass_gen_lower'],
	'L_PASS_GEN_NUMBERS' => $lang['Pass_gen_nums'],

	'PASS_GEN_ENABLE_YES' => ( $new['pass_gen_enable'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_ENABLE_NO' => ( !$new['pass_gen_enable'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_LENGTH' => $new['pass_gen_length'], 
	'PASS_GEN_ALPHA_YES' => ( $new['pass_gen_alphanumerical'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_ALPHA_NO' => ( !$new['pass_gen_alphanumerical'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_SPECIAL_YES' => ( $new['pass_gen_specialchars'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_SPECIAL_NO' => ( !$new['pass_gen_specialchars'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_UPPERCASE_YES' => ( $new['pass_gen_uppercase'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_UPPERCASE_NO' => ( !$new['pass_gen_uppercase'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_LOWERCASE_YES' => ( $new['pass_gen_lowercase'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_LOWERCASE_NO' => ( !$new['pass_gen_lowercase'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_NUMBERS_YES' => ( $new['pass_gen_numbers'] ) ? 'checked="checked"' : '', 
	'PASS_GEN_NUMBERS_NO' => ( !$new['pass_gen_numbers'] ) ? 'checked="checked"' : '',

	// Points System
	'L_POINTS' => $board_config['points_name'],
	'L_POINTS_NAME_EXPLAIN' => sprintf($lang['Points_name_explain'], $board_config['points_name']),
	'L_ENABLE_DONATION' => $lang['Points_enable_donation'],
	'L_ENABLE_DONATION_EXPLAIN' => sprintf($lang['Points_enable_donation_explain'], $board_config['points_name']),
	'L_ENABLE_POST' => sprintf($lang['Points_enable_post'], $board_config['points_name']),
	'L_ENABLE_POST_EXPLAIN' => sprintf($lang['Points_enable_post_explain'], $board_config['points_name']),
	'L_ENABLE_BROWSE' => sprintf($lang['Points_enable_browse'], $board_config['points_name']),	
	'L_ENABLE_BROWSE_EXPLAIN' => sprintf($lang['Points_enable_browse_explain'], $board_config['points_name']),
	'L_POINTS_NAME' => sprintf($lang['Points_name'], $board_config['points_name']),
	'L_PER_DEFAULT' => sprintf($lang['Points_per_default'], $board_config['points_name']),
	'L_PER_DEFAULT_EXPLAIN' => sprintf($lang['Points_per_default_explain'], $board_config['points_name']),
	'L_PER_REPLY' => sprintf($lang['Points_per_reply'], $board_config['points_name']),
	'L_PER_REPLY_EXPLAIN' => sprintf($lang['Points_per_reply_explain'], $board_config['points_name']),
	'L_PER_TOPIC' => sprintf($lang['Points_per_topic'], $board_config['points_name']),
	'L_PER_TOPIC_EXPLAIN' => sprintf($lang['Points_per_topic_explain'], $board_config['points_name']),
	'L_PER_PAGE' => sprintf($lang['Points_per_page'], $board_config['points_name']),
	'L_PER_PAGE_EXPLAIN' => sprintf($lang['Points_per_page_explain'], $board_config['points_name']),
	'L_PER_BANNER' => sprintf($lang['Points_per_banner'], $board_config['points_name']),
	'L_PER_BANNER_EXPLAIN' => sprintf($lang['Points_per_banner_explain'], $board_config['points_name']),
	'L_REFERRAL_REWARD' => sprintf($lang['Referral_Reward'], $board_config['points_name']),
	'L_REFERRAL_REWARD_EXPLAIN' => sprintf($lang['Referral_Reward_Explain'], $board_config['points_name']),
	'L_USER_GROUP_AUTH' => $lang['Points_user_group_auth'],
	'L_USER_GROUP_AUTH_EXPLAIN' => sprintf($lang['Points_user_group_auth_explain'], $board_config['points_name']),
	'L_POINTS_RESET' => sprintf($lang['Points_reset'], $board_config['points_name']),
	'L_POINTS_RESET_EXPLAIN' => sprintf($lang['Points_reset_explain'], $board_config['points_name']),
	'L_POINTS_VIEWTOPIC' => sprintf($lang['Viewtopic_points'], $board_config['points_name']),
		
	'S_POINTS_DEFAULT' => $new['points_default'],
	'S_POINTS_POST_YES' => ( $new['points_post'] ) ? 'checked="checked"' : '',
	'S_POINTS_POST_NO' => ( !$new['points_post'] ) ? 'checked="checked"' : '',
	'S_POINTS_DONATE_YES' => ( $new['points_donate'] ) ? 'checked="checked"' : '',
	'S_POINTS_DONATE_NO' => ( !$new['points_donate'] ) ? 'checked="checked"' : '',	
	'S_POINTS_REPLY' => $new['points_reply'],
	'S_POINTS_TOPIC' => $new['points_topic'],
	'S_POINTS_NAME' => $new['points_name'],
	'S_POINTS_VOTE' => $new['points_vote'],
	'S_POINTS_PAGE' => $new['points_page'],
	'S_POINTS_BANNER' => $new['points_banner'],
	'S_POINTS_REFERRAL' => $new['referral_reward'],
	'S_POINTS_BROWSE_YES' => ( $new['points_browse'] ) ? 'checked="checked"' : '',
	'S_POINTS_BROWSE_NO' => ( !$new['points_browse'] ) ? 'checked="checked"' : '',
	'S_POINTS_VIEWTOPIC_YES' => ( $new['points_viewtopic'] ) ? 'checked="checked"' : '',
	'S_POINTS_VIEWTOPIC_NO' => ( !$new['points_viewtopic'] ) ? 'checked="checked"' : '',
	'S_USER_GROUP_AUTH' => $new['points_user_group_auth_ids'],
	
	 // Posting
	'L_TOPIC_REDIRECT' => $lang['Topic_redirect'],
	'L_TOPIC_REDIRECT_EXPLAIN' => $lang['Topic_redirect_explain'],
	'L_FLOOD_INTERVAL' => $lang['Flood_Interval'],
	'L_FLOOD_INTERVAL_EXPLAIN' => $lang['Flood_Interval_explain'], 
	'L_MESSAGE_LENGTH' => $lang['Message_length'],
	'L_MESSAGE_LENGTH_EXPLAIN' => $lang['Message_length_explain'],
	'L_MIN' => $lang['Limit_username_min'],
	'L_MAX' => $lang['Limit_username_max'],
	'L_ENABLE_NULL_VOTE' => $lang['Enable_null_vote'],
	'L_ENABLE_NULL_VOTE_EXPLAIN' => $lang['Enable_null_vote_explain'],
   	'L_DISABLE_POST_EDITING' => $lang['Disable_post_editing'],
   	'L_DISABLE_POST_EDITING_EXPLAIN' => $lang['Disable_post_editing_explain'],
	'L_VISUAL_CONFIRM_POSTING' => $lang['Visual_confirm_posting'], 
	'L_VISUAL_CONFIRM_POSTING_EXPLAIN' => $lang['Visual_confirm_posting_explain'], 
	'L_STOP_BUMPING' => $lang['Stop_bumping'],
	'L_STOP_BUMPING_EXPLAIN' => $lang['Stop_bumping_explain'],
	'L_HOURS' => $lang['BM_Hours'],
	'L_FS' => $lang['Stop_bumping_fs_option'],
	'L_ALLOW_CUSTOM_POST_COLOR' => $lang['Allow_custom_post_color'],
	'L_ALLOW_CUSTOM_POST_COLOR_EXPLAIN' => $lang['Allow_custom_post_color_explain'],
	'L_VIEWTOPIC_USERINFO' => $lang['Viewtopic_userinfo'], 
	'L_DAILY_POST_LIMIT' => $lang['Daily_post_limit'], 
	'L_DAILY_POST_LIMIT_EXPLAIN' => $lang['Daily_post_limit_explain'], 
	'L_TOPIC' => $lang['Topic'],
	'L_FORUM' => $lang['Forum'],
	'L_MAX_POLL_OPTIONS' => $lang['Max_poll_options'],
	'L_MAX_IMAGES_LIMIT' => $lang['Max_images_limit'],
	'L_MAX_IMAGES_SIZE' => $lang['Max_images_size'],
	'L_HL_ENABLE' => $lang['Hide_links_enable'],
	'L_HL_NECESSARY_POST_NUMBER' => $lang['Necessary_Post_Number'],
	'L_HL_MODS_PRIORITY' => $lang['Mods_Priority'],
	'L_HL_MODS_PRIORITY_EXPLAIN' => $lang['Mods_Priority_Explain'],
	'L_VOTE_MIN_POSTS' => $lang['Vote_min_posts'],
	'L_MISSING_IMGS' => $lang['Missing_imgs'], 
	'L_MISSING_IMGS_EXPLAIN' => $lang['Missing_imgs_explain'], 

 	'MISSING_IMGS_YES' => ( $new['missing_bbcode_imgs'] ) ? 'checked="checked"' : '', 
	'MISSING_IMGS_NO' => ( !$new['missing_bbcode_imgs'] ) ? 'checked="checked"' : '',
	'VOTE_MIN_POSTS' => $new['vote_min_posts'],
	'HL_ENABLE_YES' => ( $new['hl_enable'] ) ? 'checked="checked"' : '',
	'HL_ENABLE_NO' => ( !$new['hl_enable'] ) ? 'checked="checked"' : '',
	'HL_NECESSARY_POST_NUMBER' => $new['hl_necessary_post_number'],
	'HL_MODS_PRIORITY_YES' => ( $new['hl_mods_priority'] ) ? 'checked="checked"' : '',
	'HL_MODS_PRIORITY_NO' => ( !$new['hl_mods_priority'] ) ? 'checked="checked"' : '',
	"STOP_BUMPING_YES" => ( $new['stop_bumping'] == 1 ) ? 'checked="checked"' : '',
	"STOP_BUMPING_NO" => ( !$new['stop_bumping'] ) ? 'checked="checked"' : '',
	"STOP_BUMPING_FS" => ( $new['stop_bumping'] == 2 ) ? 'checked="checked"' : '',
	'MAX_POLL_OPTIONS' => $new['max_poll_options'], 
	'DAILY_POST_LIMIT_YES' => ( $new['daily_post_limit'] ) ? 'checked="checked"' : '', 
	'DAILY_POST_LIMIT_NO' => ( !$new['daily_post_limit'] ) ? 'checked="checked"' : '',
	'VIEWTOPIC_USERINFO_YES' => ( $new['collapse_userinfo'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_USERINFO_NO' => ( !$new['collapse_userinfo'] ) ? 'checked="checked"' : '',
	'CUSTOM_POST_COLOR' => $new['allow_custom_post_color'],
	'CONFIRM_POSTING_ENABLE' => ( $new['enable_confirm_posting'] ) ? 'checked="checked"' : '',
	'CONFIRM_POSTING_DISABLE' => ( !$new['enable_confirm_posting'] ) ? 'checked="checked"' : '',
   	'EDITING_TIME' => $new['post_edit_time_limit'],
	'FLOOD_INTERVAL' => $new['flood_interval'],
	'MESSAGE_MAXLENGTH' => $new['message_maxlength'],
	'MESSAGE_MINLENGTH' => $new['message_minlength'],
	'TOPIC_REDIRECT_YES' => ( $new['topic_redirect'] ) ? 'checked="checked"' : '', 
	'TOPIC_REDIRECT_NO' => ( !$new['topic_redirect'] ) ? 'checked="checked"' : '',
	'NULL_VOTE_YES' => ( $new['null_vote'] ) ? 'checked="checked"' : '',
	'NULL_VOTE_NO' => ( !$new['null_vote'] ) ? 'checked="checked"' : '',
	'IMAGES_MAX_LIMIT' => $new['post_images_max_limit'],
	'IMAGES_MAX_HEIGHT' => $new['post_images_max_height'],
	'IMAGES_MAX_WIDTH' => $new['post_images_max_width'],

	// Private Messaging
	'L_DISABLE_PRIVATE_MESSAGING' => $lang['Private_Messaging'], 
	'L_DISABLE_PRIVATE_MESSAGING_EXPLAIN' => $lang['Disable_privmsg_explain'], 
	'L_DISABLE_NEWUSER_PRIVATE_MESSAGING' => $lang['Disable_newuser_privmsg'], 
	'L_DISABLE_NEWUSER_PRIVATE_MESSAGING_EXPLAIN' => $lang['Disable_newuser_privmsg_explain'], 
	'L_DISABLE_WPM' => $lang['Disable_wpm'], 
	'L_DISABLE_WPM_EXPLAIN' => $lang['Disable_wpm_explain'], 
	'L_INBOX_LIMIT' => $lang['Inbox_limits'], 
	'L_INBOX_LIMIT_EXPLAIN' => $lang['Inbox_limits_explain'], 
	'L_SENTBOX_LIMIT' => $lang['Sentbox_limits'], 
	'L_SENTBOX_LIMIT_EXPLAIN' => $lang['Sentbox_limits_explain'], 
	'L_SAVEBOX_LIMIT' => $lang['Savebox_limits'], 
	'L_SAVEBOX_LIMIT_EXPLAIN' => $lang['Savebox_limits_explain'],
	'L_PRIVMSG_SELF' => $lang['Privmsg_self'], 

	'S_PRIVMSG_ENABLED' => ( !$new['privmsg_disable'] ) ? 'checked="checked"' : '', 
	'S_PRIVMSG_DISABLED' => ( $new['privmsg_disable'] ) ? 'checked="checked"' : '', 
	'S_PRIVMSG_NEWUSER_ENABLED' => ( $new['privmsg_newuser_disable'] ) ? 'checked="checked"' : '', 
	'S_PRIVMSG_NEWUSER_DISABLED' => ( !$new['privmsg_newuser_disable'] ) ? 'checked="checked"' : '', 
	'S_WPM_ENABLED' => ( !$new['wpm_disable'] ) ? 'checked="checked"' : '', 
	'S_WPM_DISABLED' => ( $new['wpm_disable'] ) ? 'checked="checked"' : '', 
	'INBOX_LIMIT' => $new['max_inbox_privmsgs'], 
	'SENTBOX_LIMIT' => $new['max_sentbox_privmsgs'],
	'SAVEBOX_LIMIT' => $new['max_savebox_privmsgs'],
	'INBOX_PRIVMSGS' => $new['max_inbox_privmsgs'], 
	'SENTBOX_PRIVMSGS' => $new['max_sentbox_privmsgs'], 
	'SAVEBOX_PRIVMSGS' => $new['max_savebox_privmsgs'],
	'S_PRIVMSG_SELF_YES' => ( $new['privmsg_self'] ) ? 'checked="checked"' : '', 
	'S_PRIVMSG_SELF_NO' => ( !$new['privmsg_self'] ) ? 'checked="checked"' : '', 
	
	// Profile Photo
	'L_ALLOW_PHOTO_REMOTE' => $lang['Allow_photo_remote'],
	'L_ALLOW_PHOTO_REMOTE_EXPLAIN' => $lang['Allow_photo_remote_explain'],
	'L_ALLOW_PHOTO_UPLOAD' => $lang['Allow_photo_upload'],
	'L_PHOTO_MAX_FILESIZE' => $lang['Photo_max_filesize'],
	'L_PHOTO_MAX_FILESIZE_EXPLAIN' => $lang['Photo_max_filesize_explain'],
	'L_MAX_PHOTO_SIZE' => $lang['Max_photo_size'],
	'L_MAX_AVATAR_SIZE_EXPLAIN' => $lang['Max_avatar_size_explain'],
	'L_PHOTO_STORAGE_PATH' => $lang['Photo_storage_path'],
	'L_PHOTO_STORAGE_PATH_EXPLAIN' => $lang['Photo_storage_path_explain'],
	'L_VIEWTOPIC_PROFILEPHOTO' => $lang['Viewtopic_profilephoto'], 
	'L_VIEWPROFILE_PROFILEPHOTO' => $lang['Viewprofile_profilephoto'], 
		
  	'PHOTO_REMOTE_YES' => ( $new['allow_photo_remote'] ) ? 'checked="checked"' : '',
	'PHOTO_REMOTE_NO' => ( !$new['allow_photo_remote'] ) ? 'checked="checked"' : '',
	'PHOTO_UPLOAD_YES' => ( $new['allow_photo_upload'] ) ? 'checked="checked"' : '',
	'PHOTO_UPLOAD_NO' => ( !$new['allow_photo_upload'] ) ? 'checked="checked"' : '',
	'PHOTO_FILESIZE' => $new['photo_filesize'],
	'PHOTO_MAX_HEIGHT' => $new['photo_max_height'],
	'PHOTO_MAX_WIDTH' => $new['photo_max_width'],
	'PHOTO_PATH' => $new['photo_path'],
 	'VIEWTOPIC_PROFILEPHOTO_YES' => ( $new['viewtopic_profilephoto'] ) ? 'checked="checked"' : '', 
	'VIEWTOPIC_PROFILEPHOTO_NO' => ( !$new['viewtopic_profilephoto'] ) ? 'checked="checked"' : '',
 	'VIEWPROFILE_PROFILEPHOTO_YES' => ( $new['viewprofile_profilephoto'] ) ? 'checked="checked"' : '', 
	'VIEWPROFILE_PROFILEPHOTO_NO' => ( !$new['viewprofile_profilephoto'] ) ? 'checked="checked"' : '',

	// Referrals
	'L_REFERRAL_TITLE' => $lang['Referral_Settings'],
	'L_REFERRAL_ENABLE' => $lang['Referral_Enable'],
	'L_REFERRAL_DEFAULT_USER_ID' => $lang['Referral_Default_user_id'],
	'L_REFERRAL_DEFAULT_USER_ID_EXPLAIN' => $lang['Referral_Default_user_id_Explain'],
	'L_TOP_REFERRALS' => $lang['Top_referrals'],
	'L_TOP_REFERRALS_EXPLAIN' => $lang['Referral_Top_Explain'],
	'L_REFERRAL_VIEWTOPIC' => $lang['Referral_viewtopic'],
		
	'REFERRAL_VIEWTOPIC_YES' => ( $new['referral_viewtopic'] ) ? 'checked="checked"' : '',
	'REFERRAL_VIEWTOPIC_NO' => ( !$new['referral_viewtopic'] ) ? 'checked="checked"' : '',
	'S_DISABLE_REFERRAL_YES' => ( $new['referral_enable'] ) ? 'checked="checked"' : '',
	'S_DISABLE_REFERRAL_NO' => ( !$new['referral_enable'] ) ? 'checked="checked"' : '',
	'REFERRAL_DEFAULT_USER_ID' => $new['referral_id'],
	'TOP_REFERRALS_VALUE' => $new['referral_top_limit'],
	
	// Registration
	'L_USER_ACCOUNT_LIMIT' => $lang['Admin_user_accounts_limit'],
	'L_USER_ACCOUNT_LIMIT_EXPLAIN' => $lang['Admin_user_accounts_limit_explain'],
	'L_MIN' => $lang['Limit_username_min'],
	'L_MAX' => $lang['Limit_username_max'],
	'L_ACCT_ACTIVATION' => $lang['Acct_activation'], 
	'L_ACCT_ACTIVATION_EXPLAIN' => $lang['Acct_activation_explain'] . ' ' . $lang['group_description_explain'], 
	'L_NONE' => $lang['Acc_None'], 
	'L_USER' => $lang['Acc_User'], 
	'L_ADMIN' => $lang['Acc_Admin'],
 	'L_REGISTRATION_NOTIFY' => $lang['Registration_notify'],
 	'L_REGISTRATION_NOTIFY_EXPLAIN' => $lang['Registration_notify_explain'],
	'L_MOD' => $lang['Mod'],
	'L_SUPERMOD' => $lang['Super_Mod'],
	'L_VISUAL_CONFIRM' => $lang['Visual_confirm'], 
	'L_VISUAL_CONFIRM_EXPLAIN' => $lang['Visual_confirm_explain'], 
	'L_LIMIT_USERNAME_LENGTH' => $lang['Limit_username_length'],
	'L_LIMIT_USERNAME_LENGTH_EXPLAIN' => $lang['Limit_username_length_explain'],
	'L_ALLOW_NAME_CHANGE' => $lang['Allow_name_change'],
	'L_VIP_ENABLE' => $lang['Vip_code_enable'], 
	'L_VIP_ENABLE_EXPLAIN' => $lang['Vip_code_enable_explain'], 
	'L_COPPA_ENABLE' => $lang['Coppa_enable'], 
	'L_COPPA_ENABLE_EXPLAIN' => $lang['Coppa_enable_explain'], 
	'L_PASSWORD_CHANGE' => $lang['Password_change'], 
	'L_PASSWORD_CHANGE_EXPLAIN' => $lang['Password_change_explain'], 
	'L_AUTO_GROUP' => $lang['Auto_group'],
	'L_AUTO_GROUP_EXPLAIN' => $lang['Auto_group_explain'],

	'AUTO_GROUP_ID' => $simple_auto_group,
	'PASSWORD_DAYS' => $new['password_update_days'],
	'COPPA_YES' => ( $new['enable_coppa'] ) ? 'checked="checked"' : '',
	'COPPA_NO' => ( !$new['enable_coppa'] ) ? 'checked="checked"' : '',
	'NAMECHANGE_YES' => ( $new['allow_namechange'] ) ? 'checked="checked"' : '',
	'NAMECHANGE_NO' => ( !$new['allow_namechange'] ) ? 'checked="checked"' : '',
	'DISABLE_REG_MSG' => $new['disable_reg_msg'],
	'USER_ACCOUNT_LIMIT' => $new['user_accounts_limit'],
	'ACTIVATION_NONE' => USER_ACTIVATION_NONE, 
	'ACTIVATION_NONE_CHECKED' => ( $new['require_activation'] == USER_ACTIVATION_NONE ) ? 'checked="checked"' : '',
	'ACTIVATION_USER' => USER_ACTIVATION_SELF, 
	'ACTIVATION_USER_CHECKED' => ( $new['require_activation'] == USER_ACTIVATION_SELF ) ? 'checked="checked"' : '',
	'ACTIVATION_ADMIN' => USER_ACTIVATION_ADMIN, 
	'ACTIVATION_ADMIN_CHECKED' => ( $new['require_activation'] == USER_ACTIVATION_ADMIN ) ? 'checked="checked"' : '', 
	'ACTIVATION_DISABLE' => USER_ACTIVATION_DISABLE,
	'ACTIVATION_DISABLE_CHECKED' => ( $new['require_activation'] == USER_ACTIVATION_DISABLE ) ? 'checked="checked"' : '',
	'REGISTRATION_NOTIFY_NONE' => USER_REGISTRATION_NOTIFY_NONE,
	'REGISTRATION_NOTIFY_NONE_CHECKED' => ( $new['registration_notify'] == USER_REGISTRATION_NOTIFY_NONE ) ? 'checked="checked"' : '',
	'REGISTRATION_NOTIFY_MOD' => USER_REGISTRATION_NOTIFY_MOD,
	'REGISTRATION_NOTIFY_MOD_CHECKED' => ( $new['registration_notify'] == USER_REGISTRATION_NOTIFY_MOD ) ? 'checked="checked"' : '',
	'REGISTRATION_NOTIFY_ADMIN' => USER_REGISTRATION_NOTIFY_ADMIN,
	'REGISTRATION_NOTIFY_ADMIN_CHECKED' => ( $new['registration_notify'] == USER_REGISTRATION_NOTIFY_ADMIN ) ? 'checked="checked"' : '',
	'CONFIRM_ENABLE' => ( $new['enable_confirm'] ) ? 'checked="checked"' : '',
	'CONFIRM_DISABLE' => ( !$new['enable_confirm'] ) ? 'checked="checked"' : '',
	'VIP_ENABLE' => ( $new['vip_enable'] ) ? 'checked="checked"' : '',
	'VIP_DISABLE' => ( !$new['vip_enable'] ) ? 'checked="checked"' : '',
	'VIP_CODE' => $new['vip_code'],
	'LIMIT_USERNAME_MAX_LENGTH' => $new['limit_username_max_length'],
	'LIMIT_USERNAME_MIN_LENGTH' => $new['limit_username_min_length'],
	
	// Search
 	'L_SEARCH_ENABLE' => $lang['Search_Enable'],
 	'L_SEARCH_FLOOD_INTERVAL' => $lang['Search_Flood_Interval'],
	'L_SEARCH_FLOOD_INTERVAL_EXPLAIN' => $lang['Search_Flood_Interval_explain'], 
 	'L_SEARCH_FOOTER' => $lang['Search_Footer'],
 	'L_SEARCH_FORUM' => $lang['Search_Forum'],

	'SEARCH_ENABLE_YES' => ( $new['search_enable'] ) ? 'checked="checked"' : '',
	'SEARCH_ENABLE_NO' => ( !$new['search_enable'] ) ? 'checked="checked"' : '',
	'SEARCH_FLOOD_INTERVAL' => $new['search_flood_interval'],
	'SEARCH_FOOTER_YES' => ( $new['search_footer'] ) ? 'checked="checked"' : '',
	'SEARCH_FOOTER_NO' => ( !$new['search_footer'] ) ? 'checked="checked"' : '',
	'SEARCH_FORUM_YES' => ( $new['search_forum'] ) ? 'checked="checked"' : '',
	'SEARCH_FORUM_NO' => ( !$new['search_forum'] ) ? 'checked="checked"' : '',

	'L_FUNCTION' => $lang['Function'],
	'L_FUNCTION_DESC' => $lang['Description'],
	'FUNCTION_NAME' => $lang['Search_function_name'],
	'FUNCTION_DESC' => $lang['Search_function_desc'],
	'FUNCTION_NAME1' => $lang['Search_function_name1'],
	'FUNCTION_DESC1' => $lang['Search_function_desc1'],
	'FUNCTION_NAME2' => $lang['Search_function_name2'],
	'FUNCTION_DESC2' => $lang['Search_function_desc2'],
	
	'U_FUNCTION_URL' => append_sid('admin_db_maintenance.'.$phpEx.'?mode=start&amp;function=check_search_wordmatch'),
	'U_FUNCTION_URL1' => append_sid('admin_db_maintenance.'.$phpEx.'?mode=start&amp;function=check_search_wordlist'),
	'U_FUNCTION_URL2' => append_sid('admin_db_maintenance.'.$phpEx.'?mode=start&amp;function=rebuild_search_index'),

	// Server
	'L_SERVER_NAME' => $lang['Server_name'], 
	'L_SERVER_NAME_EXPLAIN' => $lang['Server_name_explain'], 
	'L_SERVER_PORT' => $lang['Server_port'], 
	'L_SERVER_PORT_EXPLAIN' => $lang['Server_port_explain'], 
	'L_SCRIPT_PATH' => $lang['Script_path'], 
	'L_SCRIPT_PATH_EXPLAIN' => $lang['Script_path_explain'], 
	'L_ENABLE_GZIP' => $lang['Enable_gzip'],
	'L_ENABLE_GZIP_EXPLAIN' => $lang['Enable_gzip_explain'],
	'L_GZIP_LEVEL' => $lang['Gzip_level'],
	'L_GZIP_LEVEL_EXPLAIN' => $lang['Gzip_level_explain'],
	'L_PATH_SETTINGS' => $lang['Path_settings'],
	"L_SMILIES_PATH" => $lang['Smilies_path'],
	"L_SMILIES_PATH_EXPLAIN" => $lang['Smilies_path_explain'],
	'L_XS_CACHE_DIR' => $lang['xs_cache_dir'],
	'L_XS_CACHE_DIR_EXPLAIN' => $lang['xs_cache_dir_explain'],
	'L_XS_DIR_ABSOLUTE' => $lang['xs_dir_absolute'],
	'L_XS_DIR_ABSOLUTE_EXPLAIN'	=> $lang['xs_dir_absolute_explain'],
	'L_XS_DIR_RELATIVE' => $lang['xs_dir_relative'],
	'L_XS_DIR_RELATIVE_EXPLAIN'	=> $lang['xs_dir_relative_explain'],
	'L_BOARD_SERVERLOAD_EXPLAIN' => $lang['Board_serverload_explain'], 

	'BOARD_SERVERLOAD_YES' => ( $new['board_serverload'] ) ? 'checked="checked"' : '',
	'BOARD_SERVERLOAD_NO' => ( !$new['board_serverload'] ) ? 'checked="checked"' : '',
	'SERVER_NAME' => $new['server_name'], 
	'SCRIPT_PATH' => $new['script_path'], 
	'SERVER_PORT' => $new['server_port'], 
	'GZIP_YES' => ( $new['gzip_compress'] ) ? 'checked="checked"' : '',
	'GZIP_NO' => ( !$new['gzip_compress'] ) ? 'checked="checked"' : '',
	'GZIP_LEVEL' => $new['gzip_level'],
	'SMILIES_PATH' => $new['smilies_path'],
	'AVATAR_PATH' => $new['avatar_path'], 
	'AVATAR_GALLERY_PATH' => $new['avatar_gallery_path'], 
	'AVATAR_GENERATOR_TEMPLATE_PATH' => $new['avatar_generator_template_path'],
	'XS_CACHE_DIR' => $new['xs_cache_dir'],
	'XS_CACHE_DIR_ABSOLUTE_YES' => ( $new['xs_cache_dir_absolute'] ) ? 'checked="checked"' : '',
	'XS_CACHE_DIR_ABSOLUTE_NO' => ( !$new['xs_cache_dir_absolute'] ) ? 'checked="checked"' : '',
			
	// Shoutbox
	'L_SHOUTBOX_TITLE' => $lang['Shoutbox_title'],
	'L_ENABLE_SHOUTBOX' => $lang['Enable_shoutbox'],
	'L_SHOUT_POSITION' => $lang['Shoutbox_position'],
	'L_SHOUT_POSITION_EXPLAIN' => sprintf($lang['Shoutbox_position_explain'], '<a href="' . append_sid('admin_board.'.$phpEx.'?mode=forum_modules') . '">', '</a>'),
	'L_SHOUT_REFRESH_RATE' => $lang['Meta_refresh'],
	'L_SHOUT_REFRESH_RATE_EXPLAIN' => $lang['Shoutbox_refresh_rate_explain'],
	'L_PRUNE_SHOUTS' => $lang['Prune_shouts'],
	'L_PRUNE_SHOUTS_EXPLAIN' => $lang['Prune_shouts_explain'],
	'L_SHOUT_TOP' => $lang['Shoutbox_top'],
	'L_SHOUT_BOTTOM' => $lang['Shoutbox_btm'],
	'L_SHOUT_MODULE' => $lang['Shoutbox_module'],
	'L_SHOUT_HEIGHT' => $lang['iFrame_height'],
	
	'SHOUTBOX_POS_MODULE' => ( $new['shoutbox_position'] == 1) ? 'checked="checked"' : '',
	'SHOUTBOX_POS_TOP' => ( !$new['shoutbox_position'] ) ? 'checked="checked"' : '',
	'SHOUTBOX_POS_BOTTOM' => ( $new['shoutbox_position'] == 2 ) ? 'checked="checked"' : '',
	'SHOUTBOX_YES' => ( $new['shoutbox_enable'] ) ? 'checked="checked"' : '',
	'SHOUTBOX_NO' => ( !$new['shoutbox_enable'] ) ? 'checked="checked"' : '',
	'SHOUTBOX_REFRESH_RATE' => $new['shoutbox_refresh_rate'],
	'SHOUTBOX_HEIGHT' => $new['shoutbox_height'],
	'PRUNE_SHOUTS' => $new['prune_shouts'],

	// Shoutcast
	'SHOUTCAST_HEIGHT' => $new['forum_module_shoutcast_height'],
	'SHOUTCAST_SERVER' => $new['shoutcast_server'],
	'SHOUTCAST_PORT' => $new['shoutcast_port'],
	'SHOUTCAST_PASS' => $new['shoutcast_pass'],
	
	// Signature
	'L_ALLOW_SIG' => $lang['Allow_sig'],
	'L_MAX_SIG_LENGTH' => $lang['Max_sig_length'],
	'L_MAX_SIG_LENGTH_EXPLAIN' => $lang['Max_sig_length_explain'],
	'L_MAX_SIG_LINES' => $lang['Max_sig_lines'], 
	'L_MAX_SIG_LINES_EXPLAIN' => $lang['Max_sig_lines_explain'], 
	'L_PROFILE_SIG' => $lang['Profile_sig'],
	'L_MAX_SIG_IMAGES_LIMIT' => $lang['Max_sig_images_limit'],
	'L_MAX_SIG_IMAGES_SIZE' => $lang['Max_sig_images_size'],
	'L_IGNORE_SIGAV' => $lang['Ignore_sigav'],
	'L_IGNORE_SIGAV_EXPLAIN' => $lang['Ignore_sigav_explain'],

	'IGNORE_SIGAV_YES' => ( $new['enable_ignore_sigav'] ) ? 'checked="checked"' : '',
	'IGNORE_SIGAV_NO' => ( !$new['enable_ignore_sigav'] ) ? 'checked="checked"' : '',
	'SIG_IMAGES_MAX_LIMIT' => $new['sig_images_max_limit'],
	'SIG_IMAGES_MAX_HEIGHT' => $new['sig_images_max_height'],
	'SIG_IMAGES_MAX_WIDTH' => $new['sig_images_max_width'],
	'PROFILE_SIG_YES' => ( $new['profile_show_sig'] ) ? 'checked="checked"' : '',
	'PROFILE_SIG_NO' => ( !$new['profile_show_sig'] ) ? 'checked="checked"' : '',
	'SIG_YES' => ( $new['allow_sig'] ) ? 'checked="checked"' : '',
	'SIG_NO' => ( !$new['allow_sig'] ) ? 'checked="checked"' : '',
	'SIG_SIZE' => $new['max_sig_chars'], 
	'SIG_LINES' => $new['max_sig_lines'],
	
	// Smilies
	'L_ALLOW_SMILIES' => $lang['Allow_smilies'],
	'L_SMILIES_REMOVAL1' => $lang['Smiley_removal1'],
	'L_SMILIES_REMOVAL2' => $lang['Smiley_removal2'],
	'L_SMILIES_RANDOM' => $lang['Smiley_random'],
	'L_SMILIES_PATH' => $lang['Smilies_path'],
	'L_SMILIES_PATH_EXPLAIN' => $lang['Smilies_path_explain'],
	'L_SMILEY_TABLE_COLUMNS' => $lang['Smiley_table_columns'],
	'L_SMILEY_TABLE_ROWS' => $lang['Smiley_table_rows'],
	'L_SMILEY_POSTING' => $lang['Smiley_posting'],
	'L_SMILEY_POPUP' => $lang['Smiley_popup'],
	'L_SMILEY_NOTHING' => $lang['Smiley_nothing'],
	'L_SMILEY_BUTTON' => $lang['Smiley_button'],
	'L_SMILEY_DROPDOWN' => $lang['Smiley_dropdown'],
	'L_SMILEY_BUTTONS' => $lang['Smiley_buttons'],
	'L_BUTTONS_IMAGE' => $lang['Smiley_buttons_image'],
	'L_BUTTONS_NAME' => $lang['Smiley_buttons_name'],
	'L_BUTTONS_NUMBER' => $lang['Smiley_buttons_number'],
	'L_SMILIES_IMAGE_PATH' => $lang['Smilies_image_path'],
	'L_SMILIES_IMAGE_PATH_EXPLAIN' => $lang['Smilies_path_explain'],
	'L_ALLOW_USERGROUPS' => $lang['Allow_usergroups'],
	'L_ALLOW_USERGROUPS_EXPLAIN' => $lang['Allow_usergroups_explain'],
	'L_SMILIES_FILESIZE' => $lang['Smilies_filesize'],
	'L_SMILIES_FILESIZE_EXPLAIN' => $lang['Smilies_filesize_explain'],

	'SMILE_YES' => ( $new['allow_smilies'] ) ? ' checked="checked"' : '',
	'SMILE_NO' => ( !$new['allow_smilies'] ) ? ' checked="checked"' : '',
	'REMOVAL1_YES' => ( $new['smilie_removal1'] ) ? ' checked="checked"' : '',
	'REMOVAL1_NO' => ( !$new['smilie_removal1'] ) ? ' checked="checked"' : '',
	'REMOVAL2_YES' => ( $new['smilie_removal2'] ) ? ' checked="checked"' : '',
	'REMOVAL2_NO' => ( !$new['smilie_removal2'] ) ? ' checked="checked"' : '',
	'RANDOM_YES' => ( $new['smilie_random'] ) ? ' checked="checked"' : '',
	'RANDOM_NO' => ( !$new['smilie_random'] ) ? ' checked="checked"' : '',
	'SMILEY_COLUMNS' => $new['smilie_columns'],
	'SMILEY_ROWS' => $new['smilie_rows'],
	'SMILEY_NOTHING1' => ( !$new['smilie_posting'] ) ? ' selected="selected"' : '',
	'SMILEY_BUTTONS1' => ( $new['smilie_posting'] == 1 ) ? ' selected="selected"' : '',
	'SMILEY_DROPDOWN1' => ( $new['smilie_posting'] == 2 ) ? ' selected="selected"' : '',
	'SMILEY_NOTHING2' => ( !$new['smilie_popup'] ) ? ' selected="selected"' : '',
	'SMILEY_BUTTONS2' => ( $new['smilie_popup'] == 1 ) ? ' selected="selected"' : '',
	'SMILEY_DROPDOWN2' => ( $new['smilie_popup'] == 2 ) ? ' selected="selected"' : '',
	'SMILEY_BUTTONS_IMAGE' => ( $new['smilie_buttons'] == 2 ) ? ' checked="checked"' : '',
	'SMILEY_BUTTONS_NAME' => ( $new['smilie_buttons'] == 1 ) ? ' checked="checked"' : '',
	'SMILEY_BUTTONS_NUMBER' => ( !$new['smilie_buttons'] ) ? ' checked="checked"' : '',
	'SMILIES_PATH' => $new['smilies_path'],
	'SMILIES_IMAGE_PATH' => $new['smilie_icon_path'],
	'USERGROUPS_YES' => ( $new['smilie_usergroups'] ) ? ' checked="checked"' : '',
	'USERGROUPS_NO' => ( !$new['smilie_usergroups'] ) ? ' checked="checked"' : '',
	'SMILIE_FILESIZE' => $new['smilie_max_filesize'],
		
	// Teamspeak
	'L_TS_SITE_TITLE' => $lang['Teamspeak'] . ' ' . $lang['Title'],
	'L_TS_SITE_TITLE_EXPLAIN' => $lang['Ts_sitetitle_explain'],
	'L_TS_SERVER_ADDRESS' => $lang['Ts_serveraddress'],
	'L_TS_SERVER_ADDRESS_EXPLAIN' => $lang['Ts_serveraddress_explain'],
	'L_TS_SERVER_QUERY_PORT' => $lang['Ts_serverqueryport'],
	'L_TS_SERVER_QUERY_PORT_EXPLAIN' => $lang['Ts_serverqueryport_explain'],
	'L_TS_SERVER_UDP_PORT' => $lang['Ts_serverudpport'],
	'L_TS_SERVER_UDP_PORT_EXPLAIN' => $lang['Ts_serverudpport_explain'],
	'L_TS_REFRESH_TIME' => $lang['Meta_refresh'],
	'L_TS_REFRESH_TIME_EXPLAIN' => $lang['Ts_refreshtime_explain'],
	'L_TS_SERVER_PASSWORD' => $lang['Ts_serverpasswort'],
	'L_TS_SERVER_PASSWORD_EXPLAIN' => $lang['Ts_serverpasswort_explain'],
	'L_ENABLE_FORUM_MODULE_TEAMSPEAK' => $lang['Forum_module_teamspeak'],
	'L_ENABLE_FORUM_MODULE_TEAMSPEAK_EXPLAIN' => $lang['Forum_module_teamspeak_explain'],
	'L_TS_WIN_HEIGHT' => $lang['Ts_win_height'],

	'TS_SITE_TITLE' => $new['ts_sitetitle'],
	'TS_SERVER_ADDRESS' => $new['ts_serveraddress'],
	'TS_SERVER_QUERY_PORT' => $new['ts_serverqueryport'],
	'TS_SERVER_UDP_PORT' => $new['ts_serverudpport'],
	'TS_REFRESH_TIME' => $new['ts_refreshtime'],
	'TS_WIN_HEIGHT' => $new['ts_winheight'],
	'TS_SERVER_PASSWORD' => $new['ts_serverpasswort'],
	'S_FORUM_MODULE_TEAMSPEAK_YES' => ( $new['forum_module_teamspeak'] ) ? 'checked="checked"' : '',
	'S_FORUM_MODULE_TEAMSPEAK_NO' => ( !$new['forum_module_teamspeak'] ) ? 'checked="checked"' : '',

	// Toplist
	'L_BUTTON_1' => $lang['Admin_Button_1'],
	'L_BUTTON_2' => $lang['Admin_Button_2'],
	'L_BUTTON_1_L' => $lang['Admin_Button_1_l'],
	'L_BUTTON_2_L' => $lang['Admin_Button_2_l'],
	'L_HITS_OUT' => $lang['Admin_Hits_out'],
	'L_HITS_IMG' => $lang['Admin_Hits_img'],
	'L_HITS_IN' => $lang['Admin_Hits_in'],
	'L_BUTTON_EXPLAIN' => $lang['Admin_Button_Explain'],
	'L_TOPLIST_IMGE_DIS' => $lang['Admin_Toplist_Imge_Dis'],
	'L_TOPLIST_IMGE_DIS_EXPLAIN' => $lang['Admin_Toplist_Imge_Dis_explain'],
	'L_DIMENSIONS_EXPL_MULTIPLE' => $lang['Admin_Dimensions_expl_multiple'],
	'L_DIMENSIONS_EXPL_HEIGHT' => $lang['Admin_Dimensions_expl_heigth'],
	'L_DIMENSIONS_EXPL_WIDTH' => $lang['Admin_Dimensions_expl_width'],
	'L_PRUNE_INTERVAL_IMG' => $lang['Admin_Reset_interval_img'],
	'L_PRUNE_INTERVAL_OUT' => $lang['Admin_Reset_interval_out'],
	'L_PRUNE_INTERVAL_HIN' => $lang['Admin_Reset_interval_hin'],
	'L_PRUNE_INTERVAL_HIN' => $lang['Admin_Specify_in_prune_interval'],
	'L_PRUNE_INTERVAL_OUT' => $lang['Admin_Specify_out_prune_interval'],
	'L_PRUNE_INTERVAL_IMG' => $lang['Admin_Specify_img_prune_interval'],
	'L_ANTI_FLOOD_INTERVAL_HIN' => $lang['Admin_Specify_in_anti_flood_interval'],
	'L_ANTI_FLOOD_INTERVAL_OUT' => $lang['Admin_Specify_out_anti_flood_interval'],
	'L_ANTI_FLOOD_INTERVAL_IMG' => $lang['Admin_Specify_img_anti_flood_interval'],
	'L_TOPLIST_TOPLIST_TOP10' => $lang['Admin_Toplist_toplist_top10'],
	'L_TOPLIST_TOPLIST_TOP10_EXPLAIN' => $lang['Admin_Toplist_toplist_top10_explain'],
	'L_HIN_ACTIVATION' => $lang['Admin_Hin_activation'],
	'L_HIN_ACTIVATION_EXPLAIN' => $lang['Admin_Hin_activation_explain'],
	'L_COUNT_HITS_IN' => $lang['Admin_Count_hits_in'],
	'L_COUNT_HITS_IMG' => $lang['Admin_Count_hits_img'],
	'L_COUNT_HITS_OUT' => $lang['Admin_Count_hits_out'],
	'L_OOPS' => $lang['Admin_Count_ioi_oops'],
	'L_COUNT_HITS_EX' => $lang['Admin_Count_ex_hits'],
	'L_NEVER' => $lang['Never_last_logon'],
	'L_HOURS' => $lang['Hours'],
	'L_1_DAY' => $lang['1_Day'],
	'L_DAYS' => $lang['Days'],
	'L_1_WK' => $lang['7_Days'],
	'L_2_WKS' => $lang['2_Weeks'],
	'L_1_MTH' => $lang['1_Month'],
	'L_6_MTHS' => $lang['6_Months'],
	'L_MONTHS' => $lang['Months'],
	'L_1_YR' => $lang['1_Year'],
	'L_YEARS' => $lang['Years'],
		
	'BUTTON_1' => $new['toplist_button_1'],
	'BUTTON_2' => $new['toplist_button_2'],
	'BUTTON_1_L' => $new['toplist_button_1_l'],
	'BUTTON_2_L' => $new['toplist_button_2_l'],
	'TOPLIST_IMGE_DIS' => $new['toplist_imge_dis'],
	'INHITSEN' => ($new['toplist_view_hin_hits']) ? 'checked="checked"' : '',
	'INHITSDI' => (!$new['toplist_view_hin_hits']) ? 'checked="checked"' : '',
	'OUTHITSEN' => ($new['toplist_view_out_hits']) ? 'checked="checked"' : '',
	'OUTHITSDI' => (!$new['toplist_view_out_hits']) ? 'checked="checked"' : '',
	'IMGHITSEN' => ($new['toplist_view_img_hits']) ? 'checked="checked"' : '',
	'IMGHITSDI' => (!$new['toplist_view_img_hits']) ? 'checked="checked"' : '',
	'COUNT_INHITS' => ($new['toplist_count_hin_hits']) ? 'checked="checked"' : '',
	'COUNT_IMGHITS' => ($new['toplist_count_img_hits']) ? 'checked="checked"' : '',
	'COUNT_OUTHITS' => ($new['toplist_count_out_hits']) ? 'checked="checked"' : '',
	'DIMENSIONS' => $dimensions,
	'HINACTEN' => ($new['toplist_hin_activation']) ? 'checked="checked"' : '',
	'HINACTDI' => (!$new['toplist_hin_activation']) ? 'checked="checked"' : '',
	'TOP10EN' => ($new['toplist_toplist_top10']) ? 'checked="checked"' : '',
	'TOP10DI' => (!$new['toplist_toplist_top10']) ? 'checked="checked"' : '',
	'PRUNE_IMG_' . $new['toplist_prune_img_hits_interval'] . '_SELECT' => 'selected="selected"',
	'PRUNE_OUT_' . $new['toplist_prune_out_hits_interval'] . '_SELECT' => 'selected="selected"',
	'PRUNE_HIN_' . $new['toplist_prune_hin_hits_interval'] . '_SELECT' => 'selected="selected"',
	'ANTI_FLOOD_IMG_' . $new['toplist_anti_flood_img_hits_interval'] . '_SELECT' => 'selected="selected"',
	'ANTI_FLOOD_OUT_' . $new['toplist_anti_flood_out_hits_interval'] . '_SELECT' => 'selected="selected"',
	'ANTI_FLOOD_HIN_' . $new['toplist_anti_flood_hin_hits_interval'] . '_SELECT' => 'selected="selected"',			
	
	// Who is Online
	'L_ENABLE_INVISIBLE' => $lang['Enable_inivisble'],
	'L_ENABLE_INVISIBLE_EXPLAIN' => $lang['Enable_inivisble_explain'],
	'L_WHOSONLINE_TIME' => $lang['Whosonline_time'],
	'L_WHOSONLINE_TIME_EXPLAIN'	=> $lang['Whosonline_time_explain'],
	'L_UNIQUEHITS_TIME' => $lang['Unique_hits_time'],
	'L_UNIQUEHITS_TIME_EXPLAIN' => $lang['Unique_hits_time_explain'],
	'L_RECORD_ONLINE_USERS' => $lang['Record_Online_Users'], 
	'L_RECORD_ONLINE_USERS_ONEDAY' => $lang['Record_Online_Users_Oneday'], 
	'L_SESSION_LENGTH' => $lang['Session_length'], 
	'L_SESSION_LENGTH_EXPLAIN' => $lang['Session_length_explain'], 

	'RECORD_ONLINE_USERS' => $new['record_online_users'], 
	'RECORD_DAY_USERS' => $new['record_day_users'], 
	'UNIQUEHITS_TIME' => $new['uniquehits_time'],
	'WHOSONLINE_TIME' => $new['whosonline_time'],
	'SESSION_LENGTH' => $new['session_length'], 
 	'ENABLE_INVISIBLE_YES' => ( $new['allow_invisible_link'] ) ? 'checked="checked"' : '',
	'ENABLE_INVISIBLE_NO' => ( !$new['allow_invisible_link'] ) ? 'checked="checked"' : '',

	// Automatic User Pruning
	'L_INACTIVE' => $lang['admin_auto_delete_inactive'],
	'L_INACTIVE_DESC' => $lang['DESC_admin_auto_delete_inactive'],
	'L_NON_VISIT' => $lang['admin_auto_delete_non_visit'],
	'L_NON_VISIT_DESC' => $lang['DESC_admin_auto_delete_non_visit'],
	'L_NO_POST' => $lang['admin_auto_delete_no_post'],
	'L_NO_POST_DESC' => $lang['DESC_admin_auto_delete_no_post'],
	'L_AUTO_TOTAL' => $lang['admin_auto_delete_total'],
	'L_AUTO_TOTAL_DESC' => $lang['DESC_admin_auto_delete_total'],
	'L_AUTO_MINS' => $lang['admin_auto_delete_minutes'],
	'L_AUTO_MINS_DESC' => $lang['DESC_admin_auto_delete_minutes'],
	'S_AUTO_MINS' => $board_config['admin_auto_delete_minutes'],
	'L_AUTO_DAYS' => $lang['Auto_Days'],
		
	'FAKE_DELETE_TEXT' => (FAKE_DELETE) ? '<tr><td colspan="2" class="row2"><span class="gensmall">' . $lang['Fake_Delete'] . '</td></tr>' : '',
	'S_AUTO_TOTAL' => (!empty($board_config['admin_auto_delete_total'])) ? $board_config['admin_auto_delete_total'] : 0,
	'S_AUTO_DAYS' => $new['admin_auto_delete_days'],
	'S_AUTO_DAYS_NO_POST' => $new['admin_auto_delete_days_no_post'],
	'S_AUTO_DAYS_INACTIVE' => $new['admin_auto_delete_days_inactive'],
	'S_INACTIVE_Y' => ( $new['admin_auto_delete_inactive'] ) ? 'checked="checked"' : '',
	'S_INACTIVE_N' => ( !$new['admin_auto_delete_inactive'] ) ? 'checked="checked"' : '',
	'S_NON_VISIT_Y' => ( $new['admin_auto_delete_non_visit'] ) ? 'checked="checked"' : '',
	'S_NON_VISIT_N' => ( !$new['admin_auto_delete_non_visit'] ) ? 'checked="checked"' : '',
	'S_NO_POST_Y' => ( $new['admin_auto_delete_no_post'] ) ? 'checked="checked"' : '',
	'S_NO_POST_N' => ( !$new['admin_auto_delete_no_post'] ) ? 'checked="checked"' : '',
	
	// All pages
	'CONFIG_SELECT' => config_optgroup_select($mode, array(
		array(MODE => 'ajaxed', LANG => $lang['AJAXed_Config'], OPT => ''),
		array(MODE => 'amazon', LANG => $lang['Amazon'], OPT => ''),
		array(MODE => '', LANG => $lang['Avatars'], OPT => 1),
		array(MODE => 'avatars', LANG => $lang['Configuration'], OPT => ''),
		array(MODE => 'avatar_gallery', LANG => $lang['Avatar_gallery'], OPT => ''),
		array(MODE => 'avatar_generator', LANG => $lang['Avatar_Generator'], OPT => ''),
		array(MODE => 'avatar_upload', LANG => $lang['Uploading'], OPT => ''),
		array(MODE => '', LANG => '', OPT => -1),
		array(MODE => 'bancard', LANG => $lang['Ban_card_config'], OPT => ''),
		array(MODE => '', LANG => $lang['Board'], OPT => 1),
		array(MODE => 'board', LANG => $lang['Configuration'], OPT => ''),
		array(MODE => 'disable', LANG => $lang['Board_disable'], OPT => ''),
		array(MODE => 'load', LANG => $lang['Board_load'], OPT => ''),
		array(MODE => '', LANG => '', OPT => -1),
		array(MODE => 'calendar', LANG => $lang['Calendar'], OPT => ''),
		array(MODE => 'cookie', LANG => $lang['Cookie_settings'], OPT => ''),
		array(MODE => 'ebay_auction', LANG => $lang['Auctions_config'], OPT => ''),
		array(MODE => 'email', LANG => $lang['Email'], OPT => ''),
		array(MODE => '', LANG => $lang['Forum'], OPT => 1),
		array(MODE => 'forum', LANG => $lang['Configuration'], OPT => ''),
		array(MODE => 'forum_modules', LANG => $lang['Forum_module_title'], OPT => ''),
		array(MODE => '', LANG => '', OPT => -1),
		array(MODE => 'inline_ads', LANG => $lang['Inline_ad_config'], OPT => ''),
		array(MODE => 'karma', LANG => $lang['Karma'], OPT => ''),
		array(MODE => '', LANG => $lang['Kb'], OPT => 1),
		array(MODE => 'kb', LANG => $lang['Configuration'], OPT => ''),
		array(MODE => 'kb_info', LANG => $lang['Pre_text_name'], OPT => ''),
		array(MODE => '', LANG => '', OPT => -1),
		array(MODE => 'lexicon', LANG => $lang['Lexicon'], OPT => ''),
		array(MODE => 'login', LANG => $lang['Login'], OPT => ''),
		array(MODE => 'meta_tags', LANG => $lang['Meta_settings'], OPT => ''),
		array(MODE => 'medals', LANG => $lang['Medals'], OPT => ''),
		array(MODE => 'modcp', LANG => $lang['Mod_CP'], OPT => ''),
		array(MODE => 'newsbar', LANG => $lang['News_bar'], OPT => ''),
		array(MODE => 'passgen', LANG => $lang['Pass_gen'], OPT => ''),
		array(MODE => 'points', LANG => $lang['Points_sys_settings'], OPT => ''),
		array(MODE => 'post', LANG => $lang['Post'], OPT => ''),
		array(MODE => 'pm', LANG => $lang['Private_Messaging'], OPT => ''),
		array(MODE => 'referral', LANG => $lang['Referral_System'], OPT => ''),
		array(MODE => 'register', LANG => $lang['Registration_settings'], OPT => ''),
		array(MODE => 'search', LANG => $lang['Search'], OPT => ''),
		array(MODE => 'server', LANG => $lang['Web_server'], OPT => ''),
		array(MODE => 'shoutbox', LANG => $lang['Shoutbox'], OPT => ''),
		array(MODE => 'shoutcast', LANG => $lang['Shoutcast'], OPT => ''),
		array(MODE => 'bots', LANG => $lang['Bots_Spiders'], OPT => ''),
		array(MODE => 'smilies', LANG => $lang['Smilies'], OPT => ''),
		array(MODE => 'teamspeak', LANG => $lang['Teamspeak'], OPT => ''),
		array(MODE => '', LANG => $lang['Toplist'], OPT => 1),	
		array(MODE => 'toplist_config', LANG => $lang['Configuration'], OPT => ''),
		array(MODE => 'toplist_button', LANG => $lang['Admin_Button_Config'], OPT => ''),
		array(MODE => 'toplist_hits', LANG => $lang['Admin_Disable_hits'], OPT => ''),
		array(MODE => 'toplist_image', LANG => $lang['Admin_Dimensions_expl'], OPT => ''),
		array(MODE => 'toplist_interval', LANG => $lang['Admin_Intervals'], OPT => ''),
		array(MODE => 'toplist_rank', LANG => $lang['Admin_Count_hits_title'], OPT => ''),
		array(MODE => '', LANG => '', OPT => -1),
		array(MODE => '', LANG => $lang['User'], OPT => 1),	
		array(MODE => 'autoprune', LANG => $lang['User_Auto_Delete'], OPT => ''),
		array(MODE => 'birthday', LANG => $lang['Birthday'], OPT => ''),
		array(MODE => 'usage', LANG => $lang['BBUS_Settings_Caption'], OPT => ''),
		array(MODE => 'gender', LANG => $lang['Gender'], OPT => ''),
		array(MODE => 'myinfo', LANG => $lang['myInfo_title'], OPT => ''),
		array(MODE => 'notes', LANG => $lang['Notes'], OPT => ''),
		array(MODE => 'profile_photo', LANG => $lang['Profile_photo'], OPT => ''),
		array(MODE => 'signature', LANG => $lang['Signature'], OPT => ''),
		array(MODE => '', LANG => '', OPT => -1),
		array(MODE => 'whosonline', LANG => $lang['Who_is_Online'], OPT => ''))
	),
		
	'S_HIDDEN_FIELDS' => $hidden_fields)
);

}

include_once($phpbb_root_path . 'mods/subjectchk/admin.'.$phpEx);
$subchk->print_config_fields();

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

// ------------------
// Begin function block
//
// Creates the select list for the Board Usage Stats Special Access Group
function specialgrp_select($select_name, $selected_group) 
{
    global $db;

    $selected_attribute = ' selected="selected"';

    /* First, add the "---------------" (value=-1) option to the list */
    $group_select = '<select name="' . $select_name . '">';
    if ( $selected_group == -1 )
    {
        $selected = $selected_attribute;
    }

    $group_select .= '<option value="-1"' . $selected . '>---------------</option>';
    $selected = ''; // Reset to empty string

    /* Next, retrieve users groups from GROUPS_TABLE */
    $sql = 'SELECT group_id, group_name 
    	FROM ' . GROUPS_TABLE . ' 
    	WHERE group_single_user <> ' . TRUE . ' 
    	ORDER BY group_name';
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Function specialgrp_select(): Unable to query the ' . GROUPS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
    }

    /* Loop through query results adding each group to the pull-down list */
    while ( $row = $db->sql_fetchrow($result) )
    {
        if ( $selected_group != -1 )
        {
            $selected = ( $row['group_id'] == $selected_group ) ? $selected_attribute : '';
        }
        $group_select .= '<option value="' . $row['group_id'] . '"' . $selected . '>' . $row['group_name'] . '</option>';
    }
    $db->sql_freeresult($result);
    unset($sql);

    $group_select .= '</select>';

    return $group_select;
}
//
// End function block
// ------------------

?>
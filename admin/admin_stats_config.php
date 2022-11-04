<?php
/** 
*
* @package admin
* @version $Id: admin_stats_config.php,v 4.2.8 2003/02/12 16:41:34 acydburn Exp $
* @copyright (c) 2003 Meik Sievertsen
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
//	$module['Statistics']['Configuration'] = $filename . '?mode=config';
	return;
}

require('./pagestart.' . $phpEx);

$submit = (isset($HTTP_POST_VARS['submit'])) ? TRUE : FALSE;

if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_statistics.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_statistics.' . $phpEx);
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_statistics.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_statistics.' . $phpEx);

include($phpbb_root_path . 'mods/stats/includes/constants.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/lang_functions.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/stat_functions.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/admin_functions.'.$phpEx);

if ($submit)
{
	$message = '';
	$config_update = FALSE;

	// Go through all configuration settings
	if ( (intval($board_config['stat_return_limit']) != intval($HTTP_POST_VARS['return_limit'])) )
	{
		$sql = "UPDATE " . CONFIG_TABLE . " 
			SET config_value = '" . trim(intval($HTTP_POST_VARS['return_limit'])) . "' 
			WHERE config_name = 'stat_return_limit'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Unable to update statistics return_limit', '', __LINE__, __FILE__, $sql);
		}
		
		$config_update = TRUE;
	}

	if ( (intval($board_config['stat_all_or_one']) != intval($HTTP_POST_VARS['stat_all_or_one'])) )
	{
		$sql = "UPDATE " . CONFIG_TABLE . " 
			SET config_value = '" . trim(htmlspecialchars($HTTP_POST_VARS['stat_all_or_one'])) . "' 
			WHERE config_name = 'stat_all_or_one'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Unable to update statistics stat_all_or_one', '', __LINE__, __FILE__, $sql);
		}
		
		$config_update = TRUE;
	}

	if ( (intval($board_config['stat_index']) != intval($HTTP_POST_VARS['stat_index'])) )
	{
		$sql = "UPDATE " . CONFIG_TABLE . " 
			SET config_value = '" . trim(intval($HTTP_POST_VARS['stat_index'])) . "' 
			WHERE config_name = 'stat_index'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Unable to update statistics stat_index', '', __LINE__, __FILE__, $sql);
		}
		
		$config_update = TRUE;
	}

	if ($config_update)
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

		$message = ($message == '') ? $message . $lang['Msg_config_updated'] : $message . '<br />' . $lang['Msg_config_updated'];
	}

	// Reset Settings
	if (isset($HTTP_POST_VARS['reset_view_count']))
	{
		$sql = "UPDATE " . CONFIG_TABLE . " SET 
			config_value = 0 
			WHERE config_name = 'stat_page_views'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Unable to update statistics config table', '', __LINE__, __FILE__, $sql);
		}

		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

		$message = ($message == '') ? $message . $lang['Msg_reset_view_count'] : $message . '<br />' . $lang['Msg_reset_view_count'];
	}

	// Reset Settings
	if (isset($HTTP_POST_VARS['reset_install_date']))
	{
		$sql = "UPDATE " . CONFIG_TABLE . " SET 	
			config_value = " . time() . " 
			WHERE config_name = 'stat_install_date'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Unable to update statistics config table', '', __LINE__, __FILE__, $sql);
		}
		
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

		$message = ($message == '') ? $message . $lang['Msg_reset_install_date'] : $message . '<br />' . $lang['Msg_reset_install_date'];
	}

	// Reset Cache
	if (isset($HTTP_POST_VARS['reset_cache']))
	{
		// Clear Module Cache
		$sql = "UPDATE " . CACHE_TABLE . " 	
			SET module_cache_time = 0, db_cache = '', priority = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Unable to update cache table', '', __LINE__, __FILE__, $sql);
		}

		// Clear the Smilies Cache
		$sql = "DELETE FROM " . SMILIE_INDEX_TABLE;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Unable to update smiley index table', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . SMILIE_INFO_TABLE . " 
			SET last_post_id = 0, last_update_time = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Unable to update smiley info table', '', __LINE__, __FILE__, $sql);
		}

		// Clear Cache Directory
		clear_directory('modules/cache');

		$message = ($message == '') ? $message . $lang['Msg_reset_cache'] : $message . '<br />' . $lang['Msg_reset_cache'];
	}

	// Delete Module Directory
	if (isset($HTTP_POST_VARS['purge_module_directory']))
	{
		clear_directory('modules');

		$message = ($message == '') ? $message . $lang['Msg_purge_modules'] : $message . '<br />' . $lang['Msg_purge_modules'];
	}
}

if ($mode == 'config')
{
	$template->set_filenames(array(
		'body' => 'admin/stat_config_body.tpl')
	);

	$template->assign_vars(array(
		'L_CONFIG_TITLE' => $lang['Statistics'] . ' ' . $lang['Setting'],
		'L_CONFIG_EXPLAIN' => sprintf($lang['Config_explain'], $lang['Statistics']),
		
		'L_RETURN_LIMIT' => $lang['Return_limit'],
		'L_PURGE_MODULE_DIRECTORY' => $lang['Purge_module_dir'],
		'L_PURGE_MODULE_DIRECTORY_EXPLAIN' => $lang['Purge_module_dir_explain'],
		'L_STAT_INDEX' => $lang['Stat_index'],
		'L_STAT_INDEX_EXPLAIN' => $lang['Stat_index_explain'],
		'L_STAT_ALL_OR_ONE' => $lang['Stat_all_or_one'],
		'L_STAT_ALL_OR_ONE_EXPLAIN' => $lang['Stat_all_or_one_explain'],
		'L_RETURN_LIMIT_EXPLAIN' => $lang['Return_limit_explain'],
		'L_RESET_SETTINGS_TITLE' => $lang['Reset_settings_title'],
		'L_RESET_VIEW_COUNT' => $lang['Reset_view_count'],
		'L_RESET_VIEW_COUNT_EXPLAIN' => $lang['Reset_view_count_explain'],
		'L_RESET_INSTALL_DATE' => $lang['Reset_install_date'],
		'L_RESET_INSTALL_DATE_EXPLAIN' => $lang['Reset_install_date_explain'],
		'L_RESET_CACHE' => $lang['Reset_cache'],
		'L_RESET_CACHE_EXPLAIN' => $lang['Reset_cache_explain'],
	
		'STAT_INDEX' => $board_config['stat_index'],
		'STAT_ALL_OR_ONE' => $board_config['stat_all_or_one'],
		'RETURN_LIMIT' => $board_config['stat_return_limit'],
		'S_ACTION' => append_sid('admin_stats_config.'.$phpEx.'?mode=' . $mode),
		'MESSAGE' => (!empty($message)) ? '<p class="successpage">' . $message . '</p>' : '')
	);
}

$template->assign_vars(array(
	'VIEWED_INFO' => sprintf($lang['Viewed_info'], $board_config['stat_page_views']),
	'INSTALL_INFO' => sprintf($lang['Install_info'], create_date($board_config['default_dateformat'], $board_config['stat_install_date'], $board_config['board_timezone'])))
);

$template->pparse('body');

//
// Page Footer
//
include('./page_footer_admin.'.$phpEx);

?>
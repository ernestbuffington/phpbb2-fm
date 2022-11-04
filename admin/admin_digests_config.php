<?php
/** 
*
* @package admin
* @version $Id: admin_digests_config.php,v 1.51.2.9 2004/07/17 17:49:33 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
//	$module['Email_Digests']['Configuration'] = $filename;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);


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
	$mode = 'config';
}


//
// Pull all config data
//
$sql = "SELECT *
	FROM " . DIGEST_CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query digest configuration information", "", __LINE__, __FILE__, $sql);
}
else
{
	while($row = $db->sql_fetchrow($result))
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;

		if ($row['config_name'] == 'direct_password')
		{
			$orig_pass = $row['config_value'];
		}

		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? stripslashes($HTTP_POST_VARS[$config_name]) : $default_config[$config_name]; 

		if(isset($HTTP_POST_VARS['submit']))
		{
			$new['direct_password'] = ($new['direct_password'] != '') ? md5($new['direct_password']) : $orig_pass;

			$sql = "UPDATE " . DIGEST_CONFIG_TABLE . " SET
				config_value = '" . addslashes($new[$config_name]) . "' 
				WHERE config_name = '$config_name'";
			if(!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Failed to update digest configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}
	$db->sql_freeresult($result);

	if(isset($HTTP_POST_VARS['submit']))
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_digest.'.$phpEx);

		$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_digests_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}


//
// Check to see what section we should load
//
switch($mode)
{
	case 'freq':
		$template->assign_block_vars('switch_freq', array());
		break;
	case 'defaults':
		$template->assign_block_vars('switch_defaults', array());
		break;
	case 'config':
	default:
		$template->assign_block_vars('switch_config', array());
		break;
}
$hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';


$template->set_filenames(array(
	'body' => 'admin/digests_config_body.tpl')
);

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid("admin_digests_config.$phpEx"),

	'L_DIGEST_CONFIG_TITLE' => $lang['Digests'] . ' ' . $lang['Setting'],
	'L_DIGEST_CONFIG_EXPLAIN' => sprintf($lang['Config_explain'], $lang['Digests']),
		
	'L_DIGEST_SETTINGS' => $lang['Digest_settings'],
	'L_DIGEST_DISABLE_USER' => $lang['Digest_disable_user'],
	'L_DIGEST_DISABLE_GROUP' => $lang['Digest_disable_group'],
	'L_DIGEST_TEST_MODE' => $lang['Test_mode'],
	'L_DIGEST_TEST_MODE_EXPLAIN' => $lang['Test_mode_explain'],
	'L_DIGEST_AUTO_SUBSCRIBE' => $lang['Digest_auto_subscribe'],
	'L_DIGEST_AUTO_EXPLAIN' => $lang['Digest_auto_explain'],
	'L_DIGEST_AUTO_GROUP' => $lang['Digest_auto_group'],
	'L_DIGEST_NEW_SIGN_UP' => $lang['Digest_new_sign_up'],
	'L_DIGEST_NEW_SIGN_UP_EXPLAIN' => $lang['Digest_sign_up_explain'],
	'L_DIGEST_PM_NOTIFY' => $lang['Pm_notify'],
	'L_DIGEST_PM_DISPLAY' => $lang['Pm_display'],
	'L_DIGEST_SHOW_STATS' => $lang['Digest_show_stats'],
	'L_DIGEST_SHOW_STATS_EXPLAIN' => $lang['Digest_show_stats_explain'],
	'L_DIGEST_SHOW_IP' => $lang['Show_ip'],
	'L_DIGEST_TIME_OPTION' => $lang['Use_system_time'],
	'L_DIGEST_TIME_EXPLAIN' => $lang['System_time_explain'],
	'L_DIGEST_TIME' => $lang['Digest_time'],
	'L_DIGEST_HOUR' => $lang['Hours'],
	'L_DIGEST_WEEKLY_DAY' => $lang['Digest_weekly_day'],
	'L_DIGEST_HOURLY' => $lang['Allow_hourly'],
	'L_DIGEST_2HOURLY' => $lang['Allow_2hourly'],
	'L_DIGEST_4HOURLY' => $lang['Allow_4hourly'],
	'L_DIGEST_6HOURLY' => $lang['Allow_6hourly'],
	'L_DIGEST_8HOURLY' => $lang['Allow_8hourly'],
	'L_DIGEST_12HOURLY' => $lang['Allow_12hourly'],
	'L_DIGEST_DAILY' => $lang['Allow_daily'],
	'L_DIGEST_WEEKLY' => $lang['Allow_weekly'],
	'L_DIGEST_MONTHLY' => $lang['Allow_monthly'],
	'L_DIGEST_SUBJECT' => $lang['Digest_subject'],
	'L_DIGEST_SUBJECT_EXPLAIN' => $lang['Digest_subject_explain'],
	'L_DIGEST_HOME' => $lang['Digest_home'],
	'L_DIGEST_HOME_EXPLAIN' => $lang['Digest_home_explain'],
	'L_DIGEST_MONTHLY_DAY' => $lang['Digest_monthly_day'],
	'L_DIGEST_THEME_TYPE' => $lang['Digest_theme_type'],
	'L_CSS' => $lang['Css'],
	'L_TABLE' => $lang['Table'],
	'L_HEADER' => $lang['Header'],
	'L_DIGEST_OVERRIDE' => $lang['Override_theme'],
	'L_DIGEST_THEME' => $lang['Digest_theme'],
	'L_DIGEST_DATE_FORMAT' => $lang['Digest_date_format'],
	'L_DIGEST_ACTIVITY' => $lang['Check_user_activity'],
	'L_DIGEST_ACTIVITY_EXPLAIN' => $lang['User_activity_explain'],
	'L_DIGEST_THRESHOLD' => $lang['Activity_threshold'],
	'L_DIGEST_LOGGING' => $lang['Digest_logging'],
	'L_DIGEST_LOG_DAYS' => $lang['Digest_log_days'],
	'L_DIGEST_LOG_DAYS_EXPLAIN' => $lang['Digest_log_days_explain'],
	'L_DIGEST_ALLOW_DIRECT' => $lang['Allow_direct'],
	'L_DIGEST_ALLOW_DIRECT_EXPLAIN' => $lang['Allow_direct_explain'],
	'L_DIGEST_DIRECT_PASS' => $lang['Direct_pass'],
	'L_SUPRESS_CRON' => $lang['Supress_cron'],
	'L_DIGEST_FREQUENCIES' => $lang['Digest_frequencies'],
	'L_DIGEST_DEFAULTS' => $lang['Digest_defaults'],	
	'L_DIGEST_DEFAULT_FREQUENCY' => $lang['Digest_default_frequency'],
	'L_DIGEST_DEFAULT_FORMAT' => $lang['Digest_default_format'],
	'L_HTML' => $lang['Digest_html'],
	'L_TEXT' => $lang['Digest_text'],
	'L_DIGEST_DEFAULT_SHOW_TEXT' => $lang['Digest_default_show_text'],
	'L_DIGEST_DEFAULT_SHOW_MINE' => $lang['Digest_default_show_mine'],
	'L_DIGEST_DEFAULT_NEW_ONLY' => $lang['Digest_default_new_only'],
	'L_DIGEST_DEFAULT_SEND_ON_NO_MESSAGES' => $lang['Digest_default_send_on_no_messages'],
	'L_DIGEST_SHORT_TEXT_LENGTH' => $lang['Digest_short_text_length'],
	'L_TEXT_LENGTH_EXPLAIN' => $lang['Text_length_explain'],
	'L_DIGEST_ALLOW_EXCLUDE' => $lang['Digest_allow_exclude'],
	'L_DIGEST_ALLOW_EXCLUDE_EXPLAIN' => $lang['Digest_allow_exclude_explain'],
	'L_DIGEST_SHOW_DESC' => $lang['Digest_show_desc'],
	'L_DIGEST_SHOW_DESC_EXPLAIN' => $lang['Digest_show_desc_expalin'],
	'L_ALLOW_URGENT' => $lang['Allow_urgent'],
	'L_ALLOW_URGENT_EXPLAIN' => $lang['Allow_urgent_explain'],
	'L_RUN_URGENT_ONLY' => $lang['Run_urgent'],
	'L_RUN_URGENT_ONLY_EXPLAIN' => $lang['Run_urgent_explain'],
	'L_CHARACTERS' => $lang['Characters'],
	'L_TEXT_LENGTH_OPTION' => $lang['Text_length_option'],
	'L_FULL' => $lang['Full'],
	'L_SHORT' => $lang['Short'],
	'L_NO_TEXT' => $lang['No_text'],
	
	'DIGEST_DISABLE_USER_YES' => ($new['digest_disable_user']) ? 'checked="checked"' : '',
	'DIGEST_DISABLE_USER_NO' => (!$new['digest_disable_user']) ? 'checked="checked"' : '',
	'DIGEST_DISABLE_GROUP_YES' => ($new['digest_disable_group']) ? 'checked="checked"' : '',
	'DIGEST_DISABLE_GROUP_NO' => (!$new['digest_disable_group']) ? 'checked="checked"' : '',
	'DIGEST_TEST_MODE_YES' => ($new['test_mode']) ? 'checked="checked"' : '',
	'DIGEST_TEST_MODE_NO' => (!$new['test_mode']) ? 'checked="checked"' : '',
	'DIGEST_USE_SYSTEM_TIME_YES' => ($new['use_system_time']) ? 'checked="checked"' : '',
	'DIGEST_USE_SYSTEM_TIME_NO' => (!$new['use_system_time']) ? 'checked="checked"' : '',
	'DIGEST_TIME' => $new['run_time'],
	'DAYS_SELECT' => ds_select($new['weekly_day'], 'weekly_day'),
	'DIGEST_ALLOW_EXCLUDE_YES' => ($new['allow_exclude']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_EXCLUDE_NO' => (!$new['allow_exclude']) ? 'checked="checked"' : '',
	'DIGEST_SHOW_DESC_YES' => ($new['show_forum_description']) ? 'checked="checked"' : '',
	'DIGEST_SHOW_DESC_NO' => (!$new['show_forum_description']) ? 'checked="checked"' : '',
	'ALLOW_URGENT_YES' => ($new['allow_urgent']) ? 'checked="checked"' : '',
	'ALLOW_URGENT_NO' => (!$new['allow_urgent']) ? 'checked="checked"' : '',
	'RUN_URGENT_YES' => ($new['run_urgent_only']) ? 'checked="checked"' : '',
	'RUN_URGENT_NO' => (!$new['run_urgent_only']) ? 'checked="checked"' : '',
	'DIGEST_DIRECT_YES' => ($new['allow_direct_run']) ? 'checked="checked"' : '',
	'DIGEST_DIRECT_NO' => (!$new['allow_direct_run']) ? 'checked="checked"' : '',
	'DIRECT_PASSWORD' => $new['direct_password'],
	'DIGEST_AUTO_SUBSCRIBE_YES' => ($new['auto_subscribe']) ? 'checked="checked"' : '',
	'DIGEST_AUTO_SUBSCRIBE_NO' => (!$new['auto_subscribe']) ? 'checked="checked"' : '',
	'DIGEST_NEW_SIGN_UP_YES' => ($new['new_sign_up']) ? 'checked="checked"' : '',
	'DIGEST_NEW_SIGN_UP_NO' => (!$new['new_sign_up']) ? 'checked="checked"' : '',
	'DIGEST_PM_NOTIFY_YES' => ($new['pm_notify']) ? 'checked="checked"' : '',
	'DIGEST_PM_NOTIFY_NO' => (!$new['pm_notify']) ? 'checked="checked"' : '',
	'DIGEST_PM_DISPLAY_YES' => ($new['pm_display']) ? 'checked="checked"' : '',
	'DIGEST_PM_DISPLAY_NO' => (!$new['pm_display']) ? 'checked="checked"' : '',
	'DIGEST_SHOW_STATS_YES' => ($new['show_stats']) ? 'checked="checked"' : '',
	'DIGEST_SHOW_STATS_NO' => (!$new['show_stats']) ? 'checked="checked"' : '',
	'DIGEST_SHOW_IP_YES' => ($new['show_ip']) ? 'checked="checked"' : '',
	'DIGEST_SHOW_IP_NO' => (!$new['show_ip']) ? 'checked="checked"' : '',
	'SUPRESS_CRON_YES' => ($new['supress_cron_output']) ? 'checked="checked"' : '',
	'SUPRESS_CRON_NO' => (!$new['supress_cron_output']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_HOURLY_YES' => ($new['allow_hours1']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_HOURLY_NO' => (!$new['allow_hours1']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_2HOURLY_YES' => ($new['allow_hours2']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_2HOURLY_NO' => (!$new['allow_hours2']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_4HOURLY_YES' => ($new['allow_hours4']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_4HOURLY_NO' => (!$new['allow_hours4']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_6HOURLY_YES' => ($new['allow_hours6']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_6HOURLY_NO' => (!$new['allow_hours6']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_8HOURLY_YES' => ($new['allow_hours8']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_8HOURLY_NO' => (!$new['allow_hours8']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_12HOURLY_YES' => ($new['allow_hours12']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_12HOURLY_NO' => (!$new['allow_hours12']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_DAILY_YES' => ($new['allow_daily']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_DAILY_NO' => (!$new['allow_daily']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_WEEKLY_YES' => ($new['allow_weekly']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_WEEKLY_NO' => (!$new['allow_weekly']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_MONTHLY_YES' => ($new['allow_monthly']) ? 'checked="checked"' : '',
	'DIGEST_ALLOW_MONTHLY_NO' => (!$new['allow_monthly']) ? 'checked="checked"' : '',
	'DIGEST_SUBJECT' => $new['digest_subject'],
	'DIGEST_HOME' => $new['home_page'],
	'DIGEST_MONTHLY_DAY' => $new['monthly_day'],
	'DIGEST_THEME_TYPE_CSS' => ($new['theme_type'] == 0) ? 'checked="checked"' : '',
	'DIGEST_THEME_TYPE_TABLE' => ($new['theme_type'] == 1) ? 'checked="checked"' : '',
	'DIGEST_THEME_TYPE_HEADER' => ($new['theme_type'] ==2) ? 'checked="checked"' : '',
	'DIGEST_OVERRIDE_THEME_YES' => ($new['override_theme']) ? 'checked="checked"' : '',
	'DIGEST_OVERRIDE_THEME_NO' => (!$new['override_theme']) ? 'checked="checked"' : '',
	'DIGEST_STYLE_SELECT' => style_select($new['digest_theme'], 'digest_theme', "../templates"),
	'USER_ACTIVITY_YES' => ($new['check_user_activity']) ? 'checked="checked"' : '',
	'USER_ACTIVITY_NO' => (!$new['check_user_activity']) ? 'checked="checked"' : '',
	'ACTIVITY_THRESHOLD' => $new['activity_threshold'],
	'DIGEST_LOGGING_YES' => ($new['digest_logging']) ? 'checked="checked"' : '',
	'DIGEST_LOGGING_NO' => (!$new['digest_logging']) ? 'checked="checked"' : '',
	'DIGEST_LOG_DAYS' => $new['log_days'],
	'DIGEST_DEFAULT_FREQUENCY_SELECT' => df_select($new['default_frequency'], '', 'default_frequency'),
	'DIGEST_DEFAULT_FORMAT_HTML' => ($new['default_format']) ? 'checked="checked"' : '',
	'DIGEST_DEFAULT_FORMAT_TEXT' => (!$new['default_format']) ? 'checked="checked"' : '',
	'DIGEST_DEFAULT_TEXT_LENGTH_FULL' => ($new['default_text_length_type'] == FULL_TEXT) ? 'checked="checked"' : '',
	'DIGEST_DEFAULT_TEXT_LENGTH_SHORT' => ($new['default_text_length_type'] == SHORT_TEXT) ? 'checked="checked"' : '',
	'DIGEST_DEFAULT_TEXT_LENGTH_NONE' => ($new['default_text_length_type'] == NO_TEXT) ? 'checked="checked"' : '',
	'DIGEST_DEFAULT_SHOW_MINE_TRUE' => ($new['default_show_mine']) ? 'checked="checked"' : '',
	'DIGEST_DEFAULT_SHOW_MINE_FALSE' => (!$new['default_show_mine']) ? 'checked="checked"' : '',
	'DIGEST_DEFAULT_NEW_ONLY_TRUE' => ($new['default_new_only']) ? 'checked="checked"' : '',
	'DIGEST_DEFAULT_NEW_ONLY_FALSE' => (!$new['default_new_only']) ? 'checked="checked"' : '',
	'DIGEST_DEFAULT_SEND_ON_NO_MESSAGES_TRUE' => ($new['default_send_on_no_messages']) ? 'checked="checked"' : '',
	'DIGEST_DEFAULT_SEND_ON_NO_MESSAGES_FALSE' => (!$new['default_send_on_no_messages']) ? 'checked="checked"' : '',	
	'DIGEST_SHORT_TEXT_LENGTH_SELECT' => tl_select($new['short_text_length'], 'short_text_length'),
	'S_GROUP_SELECT' => ug_select($new['auto_subscribe_group'], 'auto_subscribe_group'),
	'S_DATE_FORMAT_SELECT' => digest_date_format_select($new['digest_date_format'], $timezone, $select_name = 'digest_date_format'),
		
	// All pages
	'CONFIG_SELECT' => config_select($mode, array(
		'config' => $lang['Configuration'],
		'freq' => $lang['Digest_frequencies'],
		'defaults' => $lang['Digest_defaults'])
	),
	
	'S_HIDDEN_FIELDS' => $hidden_fields)		
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>

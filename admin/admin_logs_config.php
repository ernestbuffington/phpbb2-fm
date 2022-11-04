<?php
/** 
*
* @package admin
* @version $Id: admin_logs_config.php,v 1.85.2.9 2003/01/24 18:31:54 Moprheus Exp $
* @copyright (c) 2003 Morpheus
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Moderators']['Log_configuration'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);


//
// Get main Admin (USER_ID 2)
//
$sql = "SELECT config_value AS access_admin
	FROM " . CONFIG_TABLE . "
	WHERE config_name = 'all_admin'";
if(!$result = $db->sql_query($sql)) 
{ 
   message_die(GENERAL_ERROR, "Could not query log config informations", "", __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);
$all_admin_authorized = $row['access_admin'];

if ( $all_admin_authorized == 0 && $userdata['user_id'] <> 2 && $userdata['user_view_log'] <> 1 )
{
	message_die(GENERAL_MESSAGE, $lang['Admin_not_authorized']);
}


//
// Pull all config data
//
$sql = "SELECT *
	FROM " . CONFIG_TABLE ;
if(!$result = $db->sql_query($sql)) 
{ 
   message_die(GENERAL_ERROR, "Could not query config information in admin_logs_config", "", __LINE__, __FILE__, $sql); 
}
else
{
	while ( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

		if ( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . CONFIG_TABLE . " SET
				config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
					WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}	
	$db->sql_freeresult($result);

	if( isset($HTTP_POST_VARS['submit']) )
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

		$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_logs_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$add_admin_username = ( isset($HTTP_POST_VARS['add_admin']) ) ? $HTTP_POST_VARS['add_admin'] : '';
$delete_admin_username = ( isset($HTTP_POST_VARS['delete_admin']) ) ? $HTTP_POST_VARS['delete_admin'] : array();

//
// Admins which can be allowed
//
$sql = "SELECT user_id, username
	FROM " . USERS_TABLE . "
	WHERE user_level = 1
		AND user_id <> 2
		AND user_view_log = 0";
$result = $db->sql_query($sql);
if( !$result )
{
	message_die(GENERAL_ERROR, "Couldn't selected informations about user.", "",__LINE__, __FILE__, $sql);
}

$choose = $db->sql_fetchrowset($result);
$add_admin_select = '<select name="add_admin_select">';

if( trim($choose) == '' )
{
	$add_admin_select .= '<option value="">' . $lang['No_other_admins'] . '</option>';
}
else 
{
	$user = array();
	for( $i = 0; $i < sizeof($choose); $i++ )
	{
		$add_admin_select .= '<option value="' . $choose[$i]['user_id'] . '">' . $choose[$i]['username'] . '</option>';
	}
}
$add_admin_select .= '</select>';

$choose_username_add = ( isset($HTTP_POST_VARS['add_admin_select']) ) ? $HTTP_POST_VARS['add_admin_select'] : '';

if ( $add_admin_username )
{
	if ( $choose_username_add != '' )
	{
		//
		// Allow a admin to see the logs
		//
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_view_log = 1
			WHERE user_id = '$choose_username_add' ";
			$result = $db->sql_query($sql);
		if( !$result )
		{
			message_die(GENERAL_ERROR, "Couldn't allow this admin to see the logs.", '',__LINE__, __FILE__, $sql);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Admins_add_success'] . "<br /><br />" . sprintf($lang['Click_return_admin_log_config'], "<a href=\"" . append_sid("admin_logs_config.$phpEx") . "\">", "</a>"));
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_admins_allow'] . "<br /><br />" . sprintf($lang['Click_return_admin_log_config'], "<a href=\"" . append_sid("admin_logs_config.$phpEx") . "\">", "</a>"));
	}
}

//
// Admins which can be disallowed
//
$sql = "SELECT user_id, username
	FROM " . USERS_TABLE . "
	WHERE user_level = 1
		AND user_id <> 2
		AND user_view_log = 1";
$result = $db->sql_query($sql);
if( !$result )
{
	message_die(GENERAL_ERROR, "Couldn't selected informations about user.", '',__LINE__, __FILE__, $sql);
}
$choose_delete = $db->sql_fetchrowset($result);
$delete_admin_select = '<select name="delete_admin_select[]" multiple="multiple" size="4">';

if( trim($choose_delete) == '' )
{
	$delete_admin_select .= '<option value=""> ' . $lang['No_admins_authorized'] . '</option>';
}
else 
{
	$user = array();
	for( $i = 0; $i < sizeof($choose_delete); $i++ )
	{
		$delete_admin_select .= '<option value="' . $choose_delete[$i]['user_id'] . '">' . $choose_delete[$i]['username'] . '</option>';
	}
}
$delete_admin_select .= '</select>';

$choose_username_del = ( isset($HTTP_POST_VARS['delete_admin_select']) ) ? $HTTP_POST_VARS['delete_admin_select'] : array();
$choose_username_del_sql = implode(', ', $choose_username_del);

if ( $delete_admin_username )
{
	if ( $choose_username_del_sql != '' )
	{
		//
		// Disllow a admin to see the logs
		//
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_view_log = 0
			WHERE user_id ";
		if ( count($choose_username_del) > 1 )
		{
			$sql .= "IN ($choose_username_del_sql)";
		}
		else
		{
			$sql .= " = $choose_username_del_sql ";
		}
		$result = $db->sql_query($sql);
		
		if( !$result )
		{
			message_die(GENERAL_ERROR, "Couldn't disallow this admin to see the logs.", '',__LINE__, __FILE__, $sql);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Admins_del_success'] . "<br /><br />" . sprintf($lang['Click_return_admin_log_config'], "<a href=\"" . append_sid("admin_logs_config.$phpEx") . "\">", "</a>"));
		}
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['No_admins_disallow'] . "<br /><br />" . sprintf($lang['Click_return_admin_log_config'], "<a href=\"" . append_sid("admin_logs_config.$phpEx") . "\">", "</a>"));
	}
}

$template->set_filenames(array(
	'body' => 'admin/logs_config_body.tpl')
);

$template->assign_vars(array(
	"S_CONFIG_ACTION" => append_sid("admin_logs_config.$phpEx"),

	"L_LOG_CONFIG_TITLE" => $lang['Logger'] . ' ' . $lang['Setting'],
	"L_LOG_CONFIG_TITLE_EXPLAIN" => sprintf($lang['Config_explain'], $lang['Logger']),
	"L_ALLOW_OTHER_ADMIN" => $lang['Allow_all_admin'],
	"L_ALLOW_OTHER_ADMIN_EXPLAIN" => $lang['Allow_all_admin_explain'],
	"L_ADD_ADMIN_USERNAME" => $lang['Add_Admin_Username'],
	"L_DELETE_ADMIN_USERNAME" => $lang['Delete_Admin_Username'],
	"L_USERNAME_ADD_ADMIN_EXPLAIN" => $lang['Add_username_admin_explain'],
	"L_USERNAME_DELETE_ADMIN_EXPLAIN" => $lang['Delete_username_admin_explain'],
	'L_ENABLE_LOGGER' => $lang['Enable_mod_logger'],
 	'L_ENABLE_IP_LOGGER' => $lang['Enable_ip_logger'],
	'L_ENABLE_REFERERS' => $lang['Enable_referers'],
    'L_BOT_TRACKING' => $lang['Bots_tracking'],
		
 	'BOT_TRACKING_YES' => ( $new['enable_bot_tracking'] ) ? 'checked="checked"' : '', 
 	'BOT_TRACKING_NO' => ( !$new['enable_bot_tracking'] ) ? 'checked="checked"' : '', 
	'ENABLE_REFERERS_YES' => ( $new['enable_http_referrers'] ) ? 'checked="checked"' : '',
	'ENABLE_REFERERS_NO' => ( !$new['enable_http_referrers'] ) ? 'checked="checked"' : '', 
	'IP_LOGGER_YES' => ( $new['enable_ip_logger'] ) ? 'checked="checked"' : '',
	'IP_LOGGER_NO' => ( !$new['enable_ip_logger'] ) ? 'checked="checked"' : '',
	'LOGGER_YES' => ( $new['enable_mod_logger'] ) ? 'checked="checked"' : '',
	'LOGGER_NO' => ( !$new['enable_mod_logger'] ) ? 'checked="checked"' : '',
	'S_ALLOW_ALL_ADMIN' => ( $new['all_admin'] ) ? 'checked="checked"' : '',
	'S_DISALLOW_ALL_ADMIN' => ( !$new['all_admin'] ) ? 'checked="checked"' : '',
	'S_ADD_ADMIN' => $add_admin_select,
	'S_DELETE_ADMIN' => $delete_admin_select)
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
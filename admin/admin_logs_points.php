<?php
/** 
*
* @package admin
* @version $Id: admin_logs_points.php,v 1.0.0 austin Exp $
* @copyright (c) aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);
	
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Moderators']['Points_sys_logger'] = $filename;
	
	return;
}

// Let's set the root dir for phpBB
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = "";
}

$sql = "SELECT config_value AS all_admin
	FROM " . CONFIG_TABLE . "
	WHERE config_name = 'all_admin'";
if(!$result = $db->sql_query($sql)) 
{ 
   message_die(CRITICAL_ERROR, "Could not query log config informations", "", __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);
$all_admin_authorized = $row['all_admin'];

if ( $all_admin_authorized == 0 && $userdata['user_id'] <> 2 && $userdata['user_view_log'] <> 1 )
{
	message_die(GENERAL_MESSAGE, $lang['Admin_not_authorized']);
}
	
//
// Select main mode
//
if( isset($HTTP_POST_VARS['delete']) || isset($HTTP_GET_VARS['delete']) )
{
	//
	// Delete all points logs 
	//
	$sql = "DELETE FROM " . POINTS_LOGGER_TABLE;
	if (!$query = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not delete HTTP Referers.', '', __LINE__, __FILE__, $sql);
	}
	
	$message = $lang['Points_log_delete'] . "<br /><br />" . sprintf($lang['Click_return_points_log'], "<a href=\"" . append_sid("admin_logs_points.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);		
}			
else
{	
	//
	// This is the main display of the page before the admin has selected
	// any options.
	//
	$start = (isset($HTTP_GET_VARS['start'])) ? intval($HTTP_GET_VARS['start']) : 0;
	
	if( isset($HTTP_POST_VARS['sort']) )
	{
		$sort_method = $HTTP_POST_VARS['sort'];
	}
	else if( isset($HTTP_GET_VARS['sort']) )
	{
		$sort_method = $HTTP_GET_VARS['sort'];
	}
	else
	{
		$sort_method = 'id';
	}

	if( isset($HTTP_POST_VARS['order']) )
	{
		$sort_order = $HTTP_POST_VARS['order'];
	}
	else if( isset($HTTP_GET_VARS['order']) )
	{
		$sort_order = $HTTP_GET_VARS['order'];
	}
	else
	{
		$sort_order = '';
	}

	$template->set_filenames(array(
		'body' => 'admin/logs_points_body.tpl')
	);

	$template->assign_vars(array(
		'L_TITLE' => $lang['Points_sys_logger'],
		'L_TITLE_EXPLAIN' => $lang['Log_action_explain'],
		'L_ADMIN' => $lang['Acc_Admin'],
		'L_USER' => $lang['User'],
		'L_ACTION' => $lang['Action'],
		'L_AMOUNT' => $lang['Points_amount'],
		'L_DATE' => $lang['Date'],
		'L_DELETE_ALL' => $lang['Delete_all'],
		
		'S_MODE_DELETE' => append_sid('admin_logs_points.'.$phpEx))
	);
	
	// Count logs
	$sql = "SELECT * 
		FROM " . POINTS_LOGGER_TABLE;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain log count', '', __LINE__, __FILE__, $sql);
	}
	$total_logs = $db->sql_numrows($result);

	// Query user info...
	$sql = "SELECT *
		FROM " . POINTS_LOGGER_TABLE . " 
		ORDER BY " . $sort_method . " " . $sort_order . " 
		LIMIT " . $start . "," . $board_config['topics_per_page'];
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain user logger data.', '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$logrow[] = $row;
	}

	for ($i = 0; $i < $board_config['topics_per_page']; $i++)
	{
		if (empty($logrow[$i]))
		{
			break;
		}

		$logtime = create_date($board_config['default_dateformat'], $logrow[$i]['time'], $board_config['board_timezone']);
					
		$sql = "SELECT user_id, user_level
			FROM " . USERS_TABLE . " 
			WHERE username = '" . $logrow[$i]['admin'] . "'";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get admin user_id', '', __LINE__, __FILE__, $sql);
		}
		$adminrow = $db->sql_fetchrow($result);
		
		$admin_id = $adminrow['user_id'];
			
		$sql = "SELECT user_id, user_level
			FROM " .  USERS_TABLE . "
			WHERE username = '" . $logrow[$i]['person'] . "'";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get user user_id', '', __LINE__, __FILE__, $sql);
		}
		$userrow = $db->sql_fetchrow($result);
		
		$user_id = $userrow['user_id'];
			
		if( $logrow[$i]['add_sub'] == 'add_points' )
		{
			$action = $lang['Add'];
		}
		else if( $logrow[$i]['add_sub'] == 'subtract_points' )
		{
			$action = $lang['Subtract'];	
		}
		else
		{
			$action = $lang['An_error_occured'];		
		}
		
		$logrow[$i]['admin'] = username_level_color($logrow[$i]['admin'], $adminrow[$i]['user_level'], $adminrow[$i]['user_id']);
		$logrow[$i]['person'] = username_level_color($logrow[$i]['person'], $userrow[$i]['user_level'], $logrow[$i]['user_id']);

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		$template->assign_block_vars('log', array(
			'ROW_COLOR' => $row_color,
			'ADMIN' => '<a href="' . append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $admin_id) . '">' . $logrow[$i]['admin'] . ' </a>',
			'USER' => '<a href="' . append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '">' . $logrow[$i]['person'] . ' </a>',
			'ACTION' => $action,
			'AMOUNT' => $logrow[$i]['total'],
			'DATE' => $logtime)
		);
	}

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination(append_sid("admin_logs_points.$phpEx?sort=$sort_method&amp;order=$sort_order"), $total_logs, $board_config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_logs / $board_config['topics_per_page'] )))
	);

	$template->pparse('body');

}

include('./page_footer_admin.'.$phpEx);

?>
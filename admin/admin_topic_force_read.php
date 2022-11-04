<?php
/** 
*
* @package admin
* @version $Id: admin_topic_force_read.php,v 1.0.3 2006 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Forums']['FTR_config'] = $file;
	$module['Forums']['FTR_users'] = $file . '?mode=users';
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);


//
// Mode setting
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}


if ($mode == 'resetusers')
{
	//
	// Reset All Viewers
	//
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_ftr = '', user_ftr_time = ''
		WHERE user_id > " . ANONYMOUS;
	if( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Failed to reset users", "", __LINE__, __FILE__, $sql);
	}

	$message = $lang['admin_ftr_config_do_reset'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_topic_force_read.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}
else if ($mode == 'changepost' || $mode == 'changeforum')
{
	//
	// Change Selected Topic
	//
	$template->set_filenames(array(
		'body' => 'admin/topic_move_select_body.tpl')
	);

	if ($mode == 'changeforum')
	{
		$ftr_forum_id = ( isset($HTTP_POST_VARS['ftr_forum_id']) ) ? intval($HTTP_POST_VARS['ftr_forum_id']) : 0;
		
		$sql = "SELECT topic_id, topic_title
			FROM " . TOPICS_TABLE . "
			WHERE forum_id = " . $ftr_forum_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Failed to select topics from selected forum", "", __LINE__, __FILE__, $sql);
		}
		
		$topic_select = '<select name="topic">';
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$topic_select .= '<option value="' . $row['topic_id'] . '">' . $row['topic_title'] . '</option>';
		}
		$db->sql_freeresult($result);
		
		$topic_select .= '</select>';

		$template->assign_vars(array(
			'L_FORUM_MOVE' => $lang['admin_ftr_config_title'],
			'L_FORUM_MOVE_EXPLAIN' => '',	
			'S_FORUMMOVE_ACTION' => append_sid("admin_topic_force_read.$phpEx?mode=updatepost"),
			'L_SELECT_FORUM' => $lang['admin_ftr_config_select_t'],
			'S_FORUMS_SELECT' => $topic_select,
			'L_LOOK_UP' => $lang['admin_ftr_config_select_t2'])
		);
	}
	else
	{
		$forum_select = forum_select('', 'ftr_forum_id');
		
		$template->assign_vars(array(
			'L_FORUM_MOVE' => $lang['admin_ftr_config_title'],
			'L_FORUM_MOVE_EXPLAIN' => '',	
			'S_FORUMMOVE_ACTION' => append_sid("admin_topic_force_read.$phpEx?mode=changeforum"),
			'L_SELECT_FORUM' => $lang['Select_a_Forum'],
			'S_FORUMS_SELECT' => $forum_select,
			'L_LOOK_UP' => $lang['Look_up_Forum'])
		);
	}
}
else if ($mode == 'updatepost')
{
	$topic_id = ( isset($HTTP_POST_VARS['topic']) ) ? intval($HTTP_POST_VARS['topic']) : 0;

	$sql = "UPDATE ". CONFIG_TABLE ."
		SET config_value = '". intval($topic_id) ."'
		WHERE config_name = 'ftr_topic'";
	if( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Failed to update general configuration for ftr_topic", "", __LINE__, __FILE__, $sql);
	}

	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

	$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_topic_force_read.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}
else if ($mode == 'users')
{
	//
	// User reads
	//
	if ($HTTP_GET_VARS['remove'])
	{
		$user_id = intval($HTTP_GET_VARS['remove']);
			
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_ftr = '', user_ftr_time = ''
			WHERE user_id = " . $user_id;
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Failed to update user force topic data", "", __LINE__, __FILE__, $sql);
		}
	}

	$template->set_filenames(array(
		'body' => 'admin/topic_force_read_users_body.tpl')
	);

	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;
	$user = ( isset($HTTP_POST_VARS['user']) ) ? intval($HTTP_POST_VARS['user']) : 0;

	$order 	= ($HTTP_GET_VARS['order']) ? $HTTP_GET_VARS['order'] : 'user_ftr_time';
	$dir = ($HTTP_GET_VARS['dir']) ? $HTTP_GET_VARS['dir'] : 'asc';
		
	$order_by = ($order == 'username') ? 'ORDER BY username ' : 'ORDER BY user_ftr_time ';
	$order_by .= ($dir == 'asc') ? 'ASC ' : 'DESC ';
	$order_by .= ($start > 0) ? 'LIMIT '. $start .', ' . $board_config['topics_per_page'] : 'LIMIT 0, ' . $board_config['topics_per_page'];
		
	$sql = "SELECT user_id, username, user_level, user_ftr, user_ftr_time
		FROM " . USERS_TABLE . "
		WHERE user_id <> " . ANONYMOUS . "
			AND user_ftr <> ''
		$order_by";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain users.', '', __LINE__, __FILE__, $sql);
	}
	
	$i = 0;	
	while ($row = $db->sql_fetchrow($result))
	{	
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('userrow', array(
			'ROW_CLASS' => $row_class,
			'ROW_NUM' => $i + 1 + $start,
			'USERNAME' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
			'DATE' => create_date($board_config['default_dateformat'], $row['user_ftr_time'], $board_config['board_timezone']),

			'DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
			
			'U_EDITUSER' => append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']),
			'U_DELETE' => append_sid("admin_topic_force_read.$phpEx?mode=users&remove=" . $row['user_id']))
		);
		$i++;
	}
	$db->sql_freeresult($result);

	if (empty($i))
	{
		$template->assign_block_vars('none', array(
			'L_NONE' => $lang['None'])
		);	
	}
	
	$sql = "SELECT COUNT(*) AS total
		FROM ". USERS_TABLE ."
		WHERE user_id <> " . ANONYMOUS . "
			AND user_ftr <> ''";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Error Grabbing FTR User Info For Pagination.', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $total = $db->sql_fetchrow($result) )
	{
		$total_users = $total['total'];

		$pagination = generate_pagination("admin_topic_force_read.$phpEx?mode=users&amp;order=$order&amp;dir=$dir", $total_users, $board_config['topics_per_page'], $start). '&nbsp;';
	}
	
	$template->assign_vars(array(
		'S_CONFIG_ACTION' => append_sid("admin_topic_force_read.$phpEx"),	
	
		'L_FTR_SETTINGS' => $lang['admin_ftr_config_title'],
		'L_FTR_SETTINGS_EXPLAIN' => $lang['admin_ftr_users_exp'],
		'L_USERNAME' => $lang['Username'],
		'USERNAME_ORDER' => sprintf($lang['admin_ftr_users_asc_desc'], '<a href="'. append_sid("admin_topic_force_read.$phpEx?mode=users&order=username&dir=asc") .'" class="thsort">', '</a>', '<a href="'. append_sid("admin_topic_force_read.$phpEx?mode=users&order=username&dir=desc") .'" class="thsort">', '</a>'),
		'L_DATE' => $lang['Date'],
		'DATE_ORDER' => sprintf($lang['admin_ftr_users_asc_desc'], '<a href="'. append_sid("admin_topic_force_read.$phpEx?mode=users&order=time&dir=asc") .'" class="thsort">', '</a>', '<a href="'. append_sid("admin_topic_force_read.$phpEx?mode=users&order=time&dir=desc") .'" class="thsort">', '</a>'),
			
		'TOTAL_USERS' => $total_users,
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' =>  sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_users / $board_config['topics_per_page'] ) ))
	);
}
else
{
	//
	// Pull all config data
	//
	$sql = "SELECT *
		FROM " . CONFIG_TABLE  . "
		WHERE config_name 
		LIKE '%ftr_%'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query config information in admin_ftr_config", "", __LINE__, __FILE__, $sql);
	}
	else
	{
		while( $row = $db->sql_fetchrow($result) )
		{
			$config_name = $row['config_name'];
			$config_value = $row['config_value'];
			$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;
			
			$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];
	
			if( isset($HTTP_POST_VARS['submit']) )
			{
				$sql = "UPDATE " . CONFIG_TABLE . " SET
					config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
					WHERE config_name = '$config_name'";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
				}
			}
		}
		$db->sql_freeresult($result);

		if( isset($HTTP_POST_VARS['submit']) )
		{
			// Remove cache file
			@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

			$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_topic_force_read.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
	}

	if ($board_config['ftr_topic'])
	{
		$sql = "SELECT t.topic_title, f.forum_name
			FROM " . TOPICS_TABLE . " t, "  . FORUMS_TABLE . " f
			WHERE t.forum_id = f.forum_id 
				AND t.topic_id = " . intval($board_config['ftr_topic']);
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Failed to select forced topic information", "", __LINE__, __FILE__, $sql);
		}
		$topic_row = $db->sql_fetchrow($result);
	}
	
	$board_ftr_yes = ( $new['ftr_active'] ) ? "checked=\"checked\"" : "";
	$board_ftr_no = ( !$new['ftr_active'] ) ? "checked=\"checked\"" : "";	

	$who_yes = ( $new['ftr_who'] == 2 ) ? "checked=\"checked\"" : "";
	$who_no = ( $new['ftr_who'] ) ? "checked=\"checked\"" : "";
		
	$template->set_filenames(array(
		'body' => 'admin/topic_force_read_body.tpl')
	);
	
	$template->assign_vars(array(
		"S_CONFIG_ACTION" => append_sid("admin_topic_force_read.$phpEx"),	
	
		"L_FTR_SETTINGS" => $lang['FTR_config'] . ' ' . $lang['Setting'],
		"L_FTR_SETTINGS_EXPLAIN" => sprintf($lang['Config_explain'], $lang['FTR_config']),
			
		"L_BOARD_FTR" => $lang['admin_ftr_config_enable'], 
		"L_WHO" => $lang['admin_ftr_config_status'],
		"L_WHO_NEW" => $lang['admin_ftr_config_status2_y'],
		"L_WHO_BOTH" => $lang['admin_ftr_config_status2_n'],	
		"L_FTR_RESET" => $lang['admin_ftr_config_reset'],
		"L_FTR_RESET_EXPLAIN" => $lang['admin_ftr_config_reset_exp'],
		"L_FTR_POST_SETTINGS" => $lang['admin_ftr_config_status3'],
		"L_FTR_FORUM" => '<b>' . $lang['Forum'] . ':</b> '  . ((!$board_config['ftr_topic']) ? $lang['None'] : $topic_row['forum_name']),
		"L_FTR_TOPIC" => '<b>' . $lang['Topic'] . ':</b> ' . ((!$board_config['ftr_topic']) ? $lang['None'] : $topic_row['topic_title']),
		"L_CHANGE" => $lang['admin_ftr_config_change'],
		"L_FTR_MSG" => $lang['Message'],
		"L_FTR_MSG_EXPLAIN" => $lang['admin_ftr_config_msg_exp'],
			
		"BOARD_FTR_ENABLE" => $board_ftr_yes, 
		"BOARD_FTR_DISABLE" => $board_ftr_no, 
		"FTR_MSG" => $new['ftr_msg'],
		"WHO_YES" => $who_yes,
		"WHO_NO" => $who_no,
			
		"U_FTR_RESET" => append_sid("admin_topic_force_read.$phpEx?mode=resetusers"),
		"U_CHANGE_POST" => append_sid("admin_topic_force_read.$phpEx?mode=changepost"))
	);
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
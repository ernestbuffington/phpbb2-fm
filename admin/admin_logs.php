<?php
/***************************************************************************
 *				  			admin_logs.php
 *                          -------------------
 *     begin                : Jan 24 2003
 *     copyright            : Morpheus
 *     email                : morpheus@2037.biz
 *
 *     $Id: admin_logs.php,v 1.85.2.9 2003/01/24 18:31:54 Moprheus Exp $
 *
 ****************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Moderators']['Action_Logger'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$template->set_filenames(array(
	'body' => 'admin/logs_body.tpl')
);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_POST_VARS['order']) )
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if ( isset($HTTP_GET_VARS['order']) )
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'ASC';
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
// Logs sorting
//
$mode_types_text = array($lang['Time'], $lang['Username'], $lang['Action'], $lang['Id_log']);
$mode_types = array('time', 'username', 'mode', 'id');
	
$select_sort_mode = '<select name="mode">';
for($i = 0; $i < sizeof($mode_types_text); $i++)
{
	$selected = ( $mode == $mode_types[$i] ) ? ' selected="selected"' : '';
	$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
}
$select_sort_mode .= '</select>';
	
$select_sort_order = '<select name="order">';
if($sort_order == 'ASC')
{
	$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$select_sort_order .= '</select>';
	
$template->assign_vars(array(
	'L_LOG_ACTIONS_TITLE' => $lang['Action_Logger'],
	'L_LOG_ACTION_EXPLAIN' => $lang['Log_action_explain'] . ' ' . ((!$board_config['enable_mod_logger']) ? sprintf($lang['IP_logger_disabled'], '<a href="' . append_sid('admin_logs_config.'.$phpEx) . '">', '</a>') : ''),
	'L_CHOOSE_SORT' => $lang['Select_sort_method'],
	'L_ORDER' => $lang['Order'],
	'L_DELETE_LOG' => $lang['Mark'],
	'L_ID_LOG' => $lang['Id_log'],
	'L_TOPIC' => $lang['Topic'],
	'L_DONE_BY' => $lang['Username'],
	'L_USER_IP' => $lang['IP_Address'],
	'L_DATE' => $lang['Date'],
	'L_SUBMIT' => $lang['Sort'],

	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid('admin_logs.'.$phpEx))
);

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];

	switch( $mode )
	{
		case 'mode':
			$order_by = "mode $sort_order LIMIT $start, " . $board_config['topics_per_page'];
			break;
		case 'username':
			$order_by = "username $sort_order LIMIT $start, " . $board_config['topics_per_page'];
			break;
		case 'time':
			$order_by = "time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
			break;
		case 'id':
			$order_by = "id_log $sort_order LIMIT $start, " . $board_config['topics_per_page'];
			break;
		default:
			$order_by = "time DESC LIMIT $start, " . $board_config['topics_per_page'];
			break;
	}
}
else
{
	$order_by = "time DESC LIMIT $start, " . $board_config['topics_per_page'];
}

$sql = "SELECT l.*, u.user_level, t.topic_title
	FROM " . LOGS_TABLE . " l 
		LEFT JOIN " . USERS_TABLE . " u ON l.user_id = u.user_id
		LEFT JOIN " . TOPICS_TABLE . " t ON l.topic_id = t.topic_id
	ORDER BY $order_by "; 
if(!$result = $db->sql_query($sql)) 
{ 
   message_die(CRITICAL_ERROR, "Could not query log informations", '', __LINE__, __FILE__, $sql); 
} 

$rows = $db->sql_fetchrowset($result); 
$numrows = $db->sql_numrows($result); 

for ($i = 0; $i < $numrows; $i++) 
{
	$action = $rows[$i]['mode']; 
	$topic_title = ( strlen($rows[$i]['topic_title']) > 40 ) ? substr(trim($rows[$i]['topic_title']), 0, 37) . '...' : $rows[$i]['topic_title'];
	 
	if ( $topic_title == '' )
	{
		$topic_title = $lang['Topic_post_not_exist'];
	}
	else
	{
    	$topic_title = '<a href="' . append_sid($phpbb_root_path .'viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $rows[$i]['topic_id']) . '" target="_blank">' . $topic_title . '</a>'; 
	}
	
	$user_id = $rows[$i]['user_id']; 
	$user_ip = decode_ip($rows[$i]['user_ip']);
    $date = $rows[$i]['time']; 

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('record_row', array( 
		'ROW_CLASS' => $row_class,
		'ID_LOG' => $rows[$i]['id_log'],
		'ACTION' => $phpbb_root_path . $images['topic_mod_' . $action],
		'ACTION_ALT' => ucwords($action),
        'TOPIC' => $topic_title, 
        'USER_ID' => $user_id, 
        'USERNAME' => '<a href="' .append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $user_id) . '" class="genmed">' . username_level_color($rows[$i]['username'], $rows[$i]['user_level'], $rows[$i]['user_id']) . '</a>', 
		'USER_IP' => $user_ip,
        'DATE' => create_date($board_config['default_dateformat'], $date, $board_config['board_timezone']),
		
		'U_WHOIS_IP' => "http://network-tools.com/default.asp?prog=trace&amp;host=$user_ip", 
        'U_ACTION' => append_sid('log_rules.'.$phpEx.'?mode=' . $action))
	);
}
$db->sql_freeresult($result);

$log_list = ( isset($HTTP_POST_VARS['log_list']) ) ?  $HTTP_POST_VARS['log_list'] : array();
$delete = ( isset($HTTP_POST_VARS['delete']) ) ?  TRUE : FALSE ;

$log_list_sql = implode(', ', $log_list);

if ( $log_list_sql != '' )
{
	if ( $delete )
	{
		$sql = "DELETE 
			FROM " . LOGS_TABLE . " 
			WHERE id_log IN (" . $log_list_sql . ")";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not delete Logs', '', __LINE__, __FILE__, $sql);
		}
		else
		{
			$message = $lang['Log_delete'] . '<br /><br />' . sprintf($lang['Click_return_admin_log'], '<a href=' . append_sid("admin_logs.$phpEx") . '>', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

if ( $board_config['topics_per_page'] > 10 )
{
	$sql = "SELECT COUNT(*) AS total
		FROM " . LOGS_TABLE;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error getting total informations for logs', '', __LINE__, __FILE__, $sql); 
	}

	if ( $total = $db->sql_fetchrow($result) ) 
	{ 
		$total_records = $total['total']; 
	
		$pagination = generate_pagination("admin_logs.$phpEx?mode=$mode&amp;order=$sort_order", $total_records, $board_config['topics_per_page'], $start). '&nbsp;'; 
	} 
} 
else
{
	$pagination = '&nbsp;';
	$total_records = 10;
}
	
$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_records / $board_config['topics_per_page'] )), 	
	'TOTAL_LOGS' => $total_records . ' ' . $lang['Logger'] . 's',

	'L_GOTO_PAGE' => $lang['Goto_page'])
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
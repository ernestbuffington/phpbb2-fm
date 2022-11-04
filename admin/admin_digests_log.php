<?php
/***************************************************************************
                         admin_digests_log.php
                         ----------------------
    begin                : Monday, Oct 18, 2004
    copyright            : (C) 2000 The phpBB Group
    email                : support@phpBB.com

	$Id: $

 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['Email_Digests']['Logging'] = $filename;
	return;
}

// Load default header
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$params = array('sort_mode' => 'sort_mode', 'start' => 'start', 'user_select' => 'user_select', 'status_select' => 'status_select');

while(list($var, $param) = @each($params))
{
	if (!empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]))
	{
		$$var = (!empty($HTTP_POST_VARS[$param])) ? htmlspecialchars($HTTP_POST_VARS[$param]) : htmlspecialchars($HTTP_GET_VARS[$param]);
	}
	else
	{
		$$var = 0;
	}
}

if(isset($HTTP_POST_VARS['order']))
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if(isset($HTTP_GET_VARS['order']))
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'DESC';
}

$template->set_filenames(array(
	'body' => 'admin/digests_log.tpl')
);

// Sorting
//
$mode_types_text = array($lang['Digest_log_time'], $lang['Digest_run_type'], $lang['Username'], $lang['Digest_type'], $lang['Digest_frequency'], $lang['Digest_log_status']);
$mode_types = array('log_time', 'run_type', 'username', 'type', 'frequency', 'status');

$select_sort_mode = '<select name="sort_mode">';
for ($i = 0; $i < count($mode_types_text); $i++)
{
	$selected = ($mode == $mode_types[$i]) ? ' selected="selected"' : '';
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

switch($sort_mode)
{
	case 'log_time':
		$order_by = 'l.log_time ' . $sort_order;
		break;
	case 'run_type':
		$order_by = 'l.run_type ' . $sort_order;
		break;
	case 'username':
		$order_by = 'u.username ' . $sort_order;
		break;
	case 'type':
		$order_by = 'l.digest_type ' . $sort_order;
		break;
	case 'frequency':
		$order_by = 'l.digest_frequency ' . $sort_order;
		break;
	case 'status':
		$order_by = 'l.log_status '. $sort_order;
		break;
	default:
		$order_by = 'l.log_time DESC, l.digest_type ASC, u.username ASC';
		break;
}

$order_by .= ' LIMIT '. $start . ', ' . $board_config['topics_per_page'];

// Set up filters
$filter_by_user = ($user_select == 0) ? '' : "AND u.user_id = " . $user_select;
$filter_by_status = ($status_select == 0) ? '' : "AND l.log_status = " . $status_select;

// Perform SQL query to get the user/log information
$sql = "SELECT l.*, u.user_id, u.username, u.user_timezone, g.group_id, g.group_name
	FROM " . DIGEST_LOG_TABLE . " l, " . USERS_TABLE . " u, " . GROUPS_TABLE . " g
	WHERE l.user_id = u.user_id
	AND l.group_id = g.group_id
	$filter_by_user
	$filter_by_status
	ORDER BY $order_by";

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not obtain user/logging information', '', __LINE__, __FILE__, $sql);
}

while($row = $db->sql_fetchrow($result))
{	
	$user_name = ($row['user_id'] == -1) ? $lang['Digest_empty'] : $row['username'];

	switch ($row['digest_type'])
	{
		case 0:
			$digest_type = $lang['Digest_user'];
			break;
		case 1:
			$digest_type = $lang['Digest_group'] . ' (' . $row['group_name'] . ')';
			break;
		case 9:
			$digest_type = $lang['Digest_empty'];
			break;
	}

	$user_timezone = $row['user_timezone'];

	$post_count = ($row['log_posts'] == 0 ) ? '' : $row['log_posts'];
	
	$row_class = ($row_class == 'row1') ? 'row2' : 'row1';

	$template->assign_block_vars('log_row', array(
		'LOG_TIME' => date($digest_config['digest_date_format'], $row['log_time']),
		'RUN_TYPE' => $lang['rt'][$row['run_type']],
		'USERNAME' => $user_name,
		'DIGEST_TYPE' => $digest_type,
		'DIGEST_FREQUENCY' => get_frequency_name($row['digest_frequency']),
		'LOG_STATUS' => $lang['lm'][$row['log_status']],
		'POSTS' => $post_count,

		'ROW_CLASS' => $row_class,

		'U_DIGEST_TIMES' => append_sid("digest_times.$phpEx?user_timezone=$user_timezone"),
		)
	);
}

// Pagination
//
$sql = "SELECT l.*, u.user_id, u.username, u.user_timezone, g.group_id, g.group_name
	FROM " . DIGEST_LOG_TABLE . " l, " . USERS_TABLE . " u, " . GROUPS_TABLE . " g
	WHERE l.user_id = u.user_id
	AND l.group_id = g.group_id
	$filter_by_user
	$filter_by_status";

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not obtain user/logging information', '', __LINE__, __FILE__, $sql);
}

$log_count = $db->sql_numrows($result);

// Send vars to template
//
$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_PAGE_TITLE' => $lang['Digest_log_title'],
	'L_PAGE_DESCRIPTION' => $lang['Digest_log_description'],
	'L_LOG_TIME' => $lang['Digest_log_time'],
	'L_POPUP_MESSAGE' => $lang['Digest_popup_message'],
	'L_RUN_TYPE' => $lang['Digest_run_type'],
	'L_USERNAME' => $lang['Digest_username'],
	'L_USERTYPE' => $lang['Digest_type'],
	'L_TYPE' => $lang['Digest_frequency'],
	'L_LOG_STATUS' => $lang['Digest_log_status'],
	'L_USER_FILTER' => $lang['Filter_users'],
	'L_STATUS_FILTER' => $lang['Filter_status'],

	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_SUBMIT' => $lang['Sort'],

	'S_USER_FILTER' => log_user_select($user_select),
	'S_STATUS_FILTER' => log_status_filter($status_select),
	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid($_SERVER['PHP_SELF']),

	'PAGINATION' => generate_pagination("admin_digests_log.$phpEx?sort_mode=$sort_mode&order=$sort_order&user_select=$user_select&status_select=$status_select", $log_count, $board_config['topics_per_page'], $start) . '&nbsp;',
	)
);

// Create the page.
//
$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
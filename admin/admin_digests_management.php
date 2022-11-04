<?php 
/***************************************************************************
                           admin_digests_management.php
                           ----------------------------
    begin                : Friday, Feb 25, 2005
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

define('IN_PHPBB', true); 

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['Email_Digests']['User Management'] = $filename;
	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$params = array('user_id' => 'user_id', 'digest_id' => 'digest_id', 'group_id' => 'group_id', 'freq' => 'freq', 'start' => 'start');

while(list($var, $param) = @each($params))
{
	if (!empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]))
	{
		$$var = (!empty($HTTP_POST_VARS[$param])) ? htmlspecialchars($HTTP_POST_VARS[$param]) : htmlspecialchars($HTTP_GET_VARS[$param]);
	}
	else
	{
		$$var = '0';
	}
}

$digest_count = $start;

$params = array('mode' => 'mode', 'order' => 'order', 'alpha' => 'alpha', 'sort_mode' => 'sort_mode');

while(list($var, $param) = @each($params))
{
	if (!empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]))
	{
		$$var = (!empty($HTTP_POST_VARS[$param])) ? htmlspecialchars($HTTP_POST_VARS[$param]) : htmlspecialchars($HTTP_GET_VARS[$param]);
	}
	else
	{
		$$var = '';
	}
}

if (($order == '') or ($order == 'ASC'))
{
	$sort_order = 'ASC';
}
else
{
	$sort_order = 'DESC';
}

$user = ($group_id != 0) ? $group_id : $user_id;

if ($mode == 'activity')
{
	$sql = "SELECT *
		FROM " . DIGEST_TABLE . "
		WHERE user_id = $user
		AND digest_id = $digest_id";
	$result = $db->sql_query($sql);

	$row = $db->sql_fetchrow($result);
	
	$activity = $row['digest_activity'];
	$activity =($activity == 0) ? 1 : 0;

	$sql = "UPDATE " . DIGEST_TABLE . "
		SET digest_activity = $activity
		WHERE user_id = $user
			AND digest_id = $digest_id";
	$result = $db->sql_query($sql);
}

if ($mode == 'reset')
{
	$reset_time = (time() - (($freq * 3600) + 120));

	$sql = "UPDATE " . DIGEST_TABLE . "
		SET last_digest = $reset_time
		WHERE user_id = $user
			AND digest_id = $digest_id";
	$result = $db->sql_query($sql);
}

if ($mode == 'unsub')
{
	$sql = "DELETE
		FROM " . DIGEST_FORUMS_TABLE . "
		WHERE user_id = $user_id
			AND digest_id = $digest_id";
	$result = $db->sql_query($sql);

	$sql = "DELETE
		FROM " . DIGEST_TABLE . "
		WHERE user_id = $user_id
			AND digest_id = $digest_id";
	$result = $db->sql_query($sql);
}

if ($mode == 'gr_unsub')
{
	$sql = "DELETE
		FROM " . USER_GROUP_TABLE . "
		WHERE group_id = $group_id
			AND user_id = $user_id";
	$result = $db->sql_query($sql);
} 

// Sorting
//
$mode_types_text = array($lang['Username'], $lang['Last_digest_time'], $lang['Digest_frequency']);
$mode_types = array('username', 'last', 'frequency');

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
	case 'frequency':
		$order_by = 'd.digest_frequency ';
		$g_order_by = 'd.digest_frequency ';
		break;
	case 'last':
		$order_by = 'd.last_digest ';
		$g_order_by = 'd.last_digest ';
		break;
	default:
		$order_by = 'u.username ';
		$g_order_by = 'g.group_name ';
		break;
}

$order_by .= $sort_order . ' LIMIT ' . $start . ', ' . ($board_config['topics_per_page']);
$g_order_by .= $sort_order;

//
// alphanumeric stuff
//
$alpha_where = '';
$alpha_highlight = $alpha;

if ($alpha == 'all')
{
	$alpha_highlight = $alpha;
	$alpha = '';
}
else
{
	$alpha = str_replace("\'", "''", $alpha);
	$alpha_where = ( $alpha == 'num' ) ? "AND username NOT RLIKE '^[A-Z]'" : "AND username LIKE '$alpha%'";
	$alpha_highlight = $alpha;
}

// Pagination
//
$sql = "SELECT count(*) AS total
		FROM " .  DIGEST_TABLE;

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Error getting total users', '', __LINE__, __FILE__, $sql);
}

$total = $db->sql_fetchrow($result);

$template->set_filenames(array(
	'body' => 'admin/digests_management.tpl')
); 

// Send vars to template
$template->assign_vars(array( 
	'L_PAGE_TITLE' => $lang['Digest_title'],
	'L_PAGE_DESCRIPTION' => $lang['Admin_panel_description'],
	'L_USER_CONTROL_PANEL' => $lang['Admin_user_control_panel'],
	'L_GROUP_CONTROL_PANEL' => $lang['Admin_group_control_panel'],
	'L_USERNAME' => $lang['Username'],
	'L_LAST_DIGEST' => $lang['Last_digest_time'],
	'L_FREQUENCY' => $lang['Digest_default_frequency'],
	'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
	'L_ACTION' => $lang['Action'],
	'L_UNSUBSCRIBE' => $lang['Unsubscribe'],
	'L_STATUS' => $lang['Active'],
	'L_RESET' => $lang['Reset'],
	'L_GROUP_NAME' => $lang['Group_name'],
	'L_GROUP_MEMBERS' => $lang['Group_members'],
	'L_CREATE_NEW' => $lang['Create_new'],
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_DIGEST_DATA' => $lang['Digest_data'],
	'L_NAME' => $lang['Digest_name'],
	'L_FORMAT' => $lang['Digest_format'],
	'L_TEXT' => $lang['Digest_show_text'],
	'L_MINE' => $lang['Digest_mine'],
	'L_NEW' => $lang['Digest_new'],
	'L_NO_MESSAGE' => $lang['Digest_no_message'],
	'L_FORUMS' => $lang['Digest_forums'],
	'L_CONFIRM_STATUS' => $lang['Digest_confirm_status'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_SUBMIT' => $lang['Sort'],
	'L_LAST_VISIT' => $lang['Digest_last_visit'],
	'L_NEXT_DIGEST' => $lang['Next_digest'],
	'L_FORUMS_INCLUDED' => $lang['Forums_included'],

	'S_ALPHA_VALUE' => $alpha,
	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_ACTION' => append_sid($_SERVER['PHP_SELF']),

	'PAGINATION' => generate_pagination("admin_digests_management.$phpEx?sort_mode=$sort_mode&order=$sort_order", $total['total'], $board_config['topics_per_page'], $start). '&nbsp;') 
);

$digest_day_format = str_replace('D ', '', substr_replace($digest_config['digest_date_format'], '', strpos($digest_config['digest_date_format'], '\a\t')));

// Perform SQL query to get the user/digest information
//
$sql = "SELECT d.*, u.username, u.user_level, u.user_timezone, u.user_lastvisit, u.user_session_time
	FROM " . DIGEST_TABLE . " d, " . USERS_TABLE . " u
	WHERE d.digest_type = 0
	AND d.user_id = u.user_id
	$alpha_where
	ORDER BY $order_by";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not obtain user/digest information', '', __LINE__, __FILE__, $sql);
}

while($row = $db->sql_fetchrow($result))
{
	$user_id = $row['user_id'];
	$digest_id = $row['digest_id'];
	$user_timezone = $row['user_timezone'];
	$frequency = $row['digest_frequency'];
	$active = yes_no($row['digest_activity']);
	$last_visit = ($row['user_session_time'] > $row['user_lastvisit']) ? $row['user_session_time'] : $row['user_lastvisit'];

	switch ($row['digest_show_text'])
	{
		case -1:
			$digest_show_text = $lang['Full'];
			break;
		case 0:
			$digest_show_text = $lang['No_text'];
			break;
		case 1:
			$digest_show_text = $lang['Short'];
			break;
	}

	// Perform SQL query to get the user's forums
	//
	$sql = "SELECT f.*
		FROM " . DIGEST_FORUMS_TABLE . " df, " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
		WHERE f.forum_id = df.forum_id
			AND df.user_id = $user_id
			AND df.digest_id = $digest_id
			AND f.cat_id = c.cat_id
		ORDER BY c.cat_order, f.cat_id, f.forum_order";

	$result2 = $db->sql_query($sql);
	if (!($result2 = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain user\'s forum information', '', __LINE__, __FILE__, $sql);
	}

	while($row2 = $db->sql_fetchrow($result2))
	{
		$forum_name .= '&nbsp;&nbsp;' . $row2['forum_name'];
		if ($row2['forum_digest'] == 0)
		{
			$forum_name .= '&nbsp;<i>(' . $lang['Forum_not_active'] . ')</i>';
		}
		$forum_name .= '<br />';
	}
	$db->sql_freeresult($result2);

	$forum_name = (is_null($forum_name)) ? '&nbsp;&nbsp;' . $lang['All_Forums'] : $forum_name;
	
	// Get confirm data
	//
	$sql = "SELECT ug.digest_confirm_date
		FROM " . USER_GROUP_TABLE . " ug, " . DIGEST_TABLE . " d
		WHERE ug.user_id = d.user_id
		AND d.user_id = $user_id
		AND d.digest_id = $digest_id";

	if (!($result3 = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain user\'s confirm information', '', __LINE__, __FILE__, $sql);
	}

	$row3 = $db->sql_fetchrow($result3);

	$row_class = ($row_class == $theme['td_class1']) ? $theme['td_class2'] : $theme['td_class1'];
	$digest_count ++;

	$template->assign_block_vars('digest_row', array(
		'COUNT' => $digest_count,
		'USERNAME' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
		'DIGEST_ID' => $digest_id,
		'DIGEST_NAME' => $row['digest_name'],
		'LAST_DATE' => date($digest_day_format, $row['last_digest']),
		'LAST_TIME' => date('\a\t H:i:s', $row['last_digest']),
		'FREQUENCY' => get_frequency_name($row['digest_frequency']),	
		'ACTIVITY' => $active,
		'ALT_ACTIVITY' => ($row['digest_activity'] == 1) ? $lang['Deactivate'] : $lang['Activate'],
		'DIGEST_FORMAT' => ($row['digest_format'] == 1) ? 'HTML' : 'Text',
		'DIGEST_SHOW_TEXT' =>  $digest_show_text,
		'DIGEST_SHOW_MINE' => yes_no($row['digest_show_mine']),
		'DIGEST_NEW_ONLY' => yes_no($row['digest_new_only']),
		'DIGEST_NO_MESSAGE' => yes_no($row['digest_send_on_no_messages']),
		'INCLUDE_FORUM' => ($row['digest_include_forum'] == 1) ? $lang['Include'] : $lang['Exclude'],
		'FORUM_NAME' => $forum_name,
		'CONFIRM_STATUS' => ($row3['digest_confirm_date'] == 0) ? '' : date($digest_day_format, $row3['digest_confirm_date']),	
		'EDIT_URL' => append_sid($phpbb_root_path . "digests.$phpEx?user_id=$user_id&digest_id=$digest_id&mode=admin&digest_type=$digest_type"),
		'ACTIVITY_URL' => append_sid($phpbb_root_path . "admin/admin_digests_management.$phpEx?user_id=$user_id&digest_id=$digest_id&mode=activity"),
		'UNSUBSCRIBE_URL' => append_sid($phpbb_root_path . "admin/admin_digests_management.$phpEx?user_id=$user_id&digest_id=$digest_id&mode=unsub"),
		'RESET_URL' => append_sid($phpbb_root_path . "admin/admin_digests_management.$phpEx?user_id=$user_id&digest_id=$digest_id&mode=reset&freq=$frequency"),
		'USER_URL' => append_sid($phpbb_root_path . "digests.$phpEx?user_id=$user_id&digest_id=$digest_id&mode=admin&digest_type=$digest_type"),		
	
		'ROW_CLASS' => $row_class)
	);		
	$forum_name = NULL;
}
$db->sql_freeresult($result);

// Perform SQL query to get the group digest data
//
$sql = "SELECT DISTINCT  d.*, g.*
	FROM  " . DIGEST_TABLE . " d, " . GROUPS_TABLE . " g
	WHERE  d.user_id = g.group_id
	AND g.group_single_user = 0
	AND d.digest_type = 1
	ORDER BY $g_order_by";
$result = $db->sql_query($sql);
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not obtain group digest information', '', __LINE__, __FILE__, $sql);
}

while($row = $db->sql_fetchrow($result))
{
	$digest_id = $row['digest_id'];
	$frequency = $row['digest_frequency'];
	$group_id = $row['group_id'];
	$alt_activity =($row['digest_activity'] == 1) ? $lang['Deactivate'] : $lang['Activate'];
	
	$row_class = ($row_class == $theme['td_class1']) ? $theme['td_class2'] : $theme['td_class1'];

	switch ($row['digest_show_text'])
	{
		case -1:
			$digest_show_text = $lang['Full'];
			break;
		case 0:
			$digest_show_text = $lang['No_text'];
			break;
		case 1:
			$digest_show_text = $lang['Short'];
			break;
	}

	// Perform SQL query to get the group's forums
	//
	$sql = "SELECT f.forum_name
		FROM " . DIGEST_FORUMS_TABLE . " df, " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
		WHERE f.forum_id = df.forum_id
		AND df.digest_id = $digest_id
		AND f.cat_id = c.cat_id
		ORDER BY c.cat_order, f.cat_id, f.forum_order";

	$result2 = $db->sql_query($sql);
	if (!($result2 = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain group\'s forum information', '', __LINE__, __FILE__, $sql);
	}

	while($row2 = $db->sql_fetchrow($result2))
	{
		$forum_name .= '&nbsp;&nbsp;' . $row2['forum_name'] . '<br />';
	}
	$db->sql_freeresult($result2);

	$forum_name = (is_null($forum_name)) ? '&nbsp;&nbsp;' . $lang['All_Forums'] : $forum_name;

	// Get group members
	//
	$sql3 = "SELECT u.user_id, u.username, ug.*, g.group_moderator
		FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
		WHERE ug.user_id = u.user_id
		AND ug.group_id = $group_id
		AND ug.group_id = g.group_id
		ORDER BY u.username";
	if (!($result3 = $db->sql_query($sql3)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain user/group information', '', __LINE__, __FILE__, $sql3);
	}

	while($row3 = $db->sql_fetchrow($result3) )
	{
		$moderator = ($row3['user_id'] == $row3['group_moderator']) ? ' <b>(M)</b>' : '';
		$username_list .= '&nbsp;&nbsp;' . $row3['username'] . $moderator . '<br />';
		$confirm_list .= ($row3['digest_confirm_date'] == 0) ? '<br />' : date($digest_day_format, $row3['digest_confirm_date']) . '<br />';
	}
	$db->sql_freeresult($result3);

	$template->assign_block_vars('digest_group_row', array(
		'GROUP_NAME' => $row['group_name'],	
		'DIGEST_NAME' => $row['digest_name'],
		'DIGEST_ID' => $digest_id,
		'DIGEST_NAME_URL' => $phpbb_root_path . "digests_group_members.$phpEx?group_id=$group_id",
		'LAST_DATE' => date($digest_day_format, $row['last_digest']),
		'LAST_TIME' => date('\a\t H:i:s', $row['last_digest']),
		'FREQUENCY' =>get_frequency_name($row['digest_frequency']),	
		'ROW_CLASS' => $row_class,
		'UNSUBSCRIBE' => $unsubscribe,
		'UNSUBSCRIBE_URL' => $unsubscribe_url,		
		'ACTIVITY' =>  yes_no($row['digest_activity']),
		'ALT_ACTIVITY' => '[' . $alt_activity .']',
		'DIGEST_FORMAT' => ($row['digest_format'] == 1) ? 'HTML' : 'Text',
		'DIGEST_SHOW_TEXT' =>  $digest_show_text,
		'DIGEST_SHOW_MINE' => yes_no($row['digest_show_mine']),
		'DIGEST_NEW_ONLY' => yes_no($row['digest_new_only']),
		'DIGEST_NO_MESSAGE' => yes_no($row['digest_send_on_no_messages']),
		'INCLUDE_FORUM' => ($row['digest_include_forum'] == 1) ? $lang['Include'] : $lang['Exclude'],
		'FORUM_NAME' => $forum_name,
		'USER_NAME_LIST' => $username_list,
		'CONFIRM_LIST' => $confirm_list,
		'ROW_CLASS' => $row_class,

		'EDIT_URL' =>  append_sid($phpbb_root_path . "digests.$phpEx?user_id=$group_id&digest_id=$digest_id&mode=group"),
		'ACTIVITY_URL' =>  append_sid($phpbb_root_path . "admin/admin_digests_management.$phpEx?group_id=$group_id&digest_id=$digest_id&mode=activity"),
		'RESET_URL' => append_sid($phpbb_root_path . "admin/admin_digests_management.$phpEx?group_id=$group_id&digest_id=$digest_id&mode=reset&freq=$frequency"),
		)
	);
		
	$forum_name = $username_list = $confirm_list = NULL;
}
$db->sql_freeresult($result);

// alpha search bit
//
$start = array($lang['All'], '#');
$alpha_range = array_merge($start, range('A','Z'));

for ($i = 0; $i < sizeof($alpha_range); $i++)
{	
	$alpha = ($alpha_range[$i] != '#') ? strtolower($alpha_range[$i]) : 'num';
	$alpha_range[$i] = ($alpha == $alpha_highlight) ? '<b>' . $alpha_range[$i] . '</b>' : $alpha_range[$i];

	$template->assign_block_vars('alpha_search', array(
		'SEARCH_SIZE' => floor(95 / sizeof($alpha_range)) . '%',
		'SEARCH_TERM' => $alpha_range[$i],
		'SEARCH_LINK' => append_sid($phpbb_root_path . "admin/admin_digests_management.$phpEx?sort=$sort&order=$sort_order&show=$show&alpha=$alpha"))
	);
}

$template->pparse('body'); 

include('./page_footer_admin.'.$phpEx);

?> 
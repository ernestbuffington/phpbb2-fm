<?php
/** 
*
* @package admin
* @version $Id: admin_topic_kicker.php,v 1.0.0 2004/10/17 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
//	$module['Forums']['Topic_Kicker'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_thread_kicker.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_thread_kicker.' . $phpEx);

if (isset($HTTP_POST_VARS['config']))
{
	$config_value = ( isset($HTTP_POST_VARS['enable_kicker']) ) ? intval($HTTP_POST_VARS['enable_kicker']) : 0;

	$sql = "UPDATE " . CONFIG_TABLE . "
		SET config_value = $config_value
		WHERE config_name = 'enable_kicker'";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, "Failed to update general configuration for enable_kicker", "", __LINE__, __FILE__, $sql);
	}

	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	
	$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_topic_kicker.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}

//
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => 'admin/topic_kicker_body.tpl')
);

// pagination
$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0; 

$pagination = ''; 
$total_pag_items = 1;

// are we kicking all
$submit_all = $HTTP_POST_VARS['unkick_all'];
if ( $submit_all )
{
	$sql = "DELETE FROM " . THREAD_KICKER_TABLE;
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Error in unkicking all users', '', __LINE__, __FILE__, $sql_z); 
	}
}

// Build Kicker Table
$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = 'date';
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

$mode_types_text = array( $lang['tk_date'], $lang['Topic'], $lang['tk_kicked'], $lang['tk_kicked_by']);
$mode_types = array('date', 'thread', 'kicked', 'kicked_by');

$select_sort_mode = '<select name="mode">';
for($i = 0; $i < count($mode_types_text); $i++)
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

switch( $mode )
{	case 'date':
		$order_by = "kick_time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'kicked':
		$order_by = "kicked_username, kick_time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'thread':
		$order_by = "topic_title, kick_time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'kicked_by':
		$order_by = "kicker_username, kick_time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	default:
		$order_by = "kick_time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
}

$sql = "SELECT * 
	FROM " . THREAD_KICKER_TABLE . "
	ORDER BY $order_by";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building list of kicked users', '', __LINE__, __FILE__, $sql); 
}

$i = 0;
while ( $row = $db->sql_fetchrow($result) )
{
	$tk_kick_id = $row['kick_id'];
	// Has this user just been kicked
	$submit = $HTTP_POST_VARS['unkick_marked'];
	if ( $submit )
	{
		$check = $HTTP_POST_VARS[$tk_kick_id];
		if ( $check == 1 )
		{
			$sql_z = "DELETE FROM " . THREAD_KICKER_TABLE . "
				WHERE kick_id = " . $tk_kick_id;
			if ( !($result_z = $db->sql_query($sql_z)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error in unkicking user', '', __LINE__, __FILE__, $sql_z); 
			}
		}
	}
	if ( $check != 1 )
	{
		$tk_user_id = $row['user_id'];
		$tk_topic_id = $row['topic_id'];
		$tk_kicker = $row['kicker'];
		$tk_post_id = $row['post_id'];
		$tk_kick_time = $row['kick_time'];
		$tk_kicker_status = $row['kicker_status'];
		
		$sql_d = "SELECT topic_title 
			FROM " . TOPICS_TABLE . "
			WHERE topic_id = " . $tk_topic_id;
		if ( !($result_d = $db->sql_query($sql_d)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in retrieving username', '', __LINE__, __FILE__, $sql_d); 
		}
		$row_d = $db->sql_fetchrow($result_d);
		$topic_title = $row_d['topic_title'];
		
		$thread = '<a href="' . $phpbb_root_path . append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $tk_topic_id) . '" target="_blank">' . $topic_title . '</a>';
		
		// Convert date to viewable format
		$kick_time = create_date($board_config['default_dateformat'], $tk_kick_time, $board_config['board_timezone']);
		
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		// Get usernames from userids
		$sql_a = "SELECT username, user_level 
			FROM " . USERS_TABLE . " 
			WHERE user_id = " . $tk_user_id;
		if ( !($result_a = $db->sql_query($sql_a)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in retrieving username', '', __LINE__, __FILE__, $sql_a); 
		}
		$row_a = $db->sql_fetchrow($result_a);
		$kicked_username = username_level_color($row_a['username'], $row_a['user_level'], $tk_user_id);
		
		$sql_b = "SELECT username, user_level 
			FROM " . USERS_TABLE . " 
			WHERE user_id = " . $tk_kicker;
		if ( !($result_b = $db->sql_query($sql_b)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Error in retrieving username', '', __LINE__, __FILE__, $sql_b); 
		}
		$row_b = $db->sql_fetchrow($result_b);
		$kicker_username = username_level_color($row_b['username'], $row_b['user_level'], $tk_kicker);
		
		$tk_mark = '<input type="checkbox" name="' . $tk_kick_id . '" value="1" />';
		
		$template->assign_block_vars('kicker', array(
			'ROW_CLASS' => $row_class,
			'KICK_ID' => $tk_kick_id,
			'KICKED' => '<a href="' . $phpbb_root_path . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $tk_user_id) . '" target="_blank" class="genmed">' . $kicked_username . '</a>',
			'KICKED_BY' => '<a href="' . $phpbb_root_path . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $tk_kicker) . '" target="_blank" class="genmed">' . $kicker_username . '</a>',
			'THREAD' => $thread,
			'DATE' => $kick_time,
			'CHECKBOX' => $tk_mark)
		); 
		
		$i++;
	}
}
$db->sql_freeresult($result);

// Pagination output
$sql = "SELECT count(*) AS total 
	FROM " . THREAD_KICKER_TABLE . " 
	ORDER BY kick_time DESC"; 
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error getting total for pagination', '', __LINE__, __FILE__, $sql); 
} 

if ( $total = $db->sql_fetchrow($result) ) 
{ 
   	$total_pag_items = $total['total'];
   	if ( !empty($total_pag_items) )
   	{
   		$pagination = generate_pagination("admin_topic_kicker.php?mode=$mode&amp;order=$sort_order", $total_pag_items, $board_config['topics_per_page'], $start). '';
   		$page_number = sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_pag_items / $board_config['topics_per_page'] )); 
	}
}
// end pagination output
	
// Set template Vars
$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_topic_kicker.'.$phpEx),

	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'S_MODE_SELECT' => $select_sort_mode,
	'L_SORT' => $lang['Sort'],
	'L_ORDER' => $lang['Order'],
	'S_ORDER_SELECT' => $select_sort_order,

	'L_CONFIGURATION' => $lang['Topic_kicker'] . ' ' . $lang['Setting'],
	'L_ENABLE_KICKER' => $lang['tk_enable_kicker'],

	'ENABLE_KICKER_YES' => ($board_config['enable_kicker']) ? ' checked="checked"' : '',
	'ENABLE_KICKER_NO' =>  (!$board_config['enable_kicker']) ? ' checked="checked"' : '',
		
	'KICKER_HEADER' => $lang['tk_kicker_heading'],
	'KICKER_EXPLAIN' => $lang['tk_kicker_explain'],
	'KICKER_TABLE' => $lang['tk_kicker_table1'],
	'UNKICK' => $lang['Mark'],
	'KICKED' => $lang['tk_kicked'],
	'THREAD' => $lang['Topic'],
	'DATE' => $lang['tk_date'],
	'KICKED_BY' => $lang['tk_kicked_by'],
	'KICKER_SET_HEAD' => $lang['tk_kicker_set_head'],
	'KICKER_SET_EXPLAIN' => $lang['tk_kicker_set_explain'],
	'KICK_MARKED' => $lang['tk_kick_marked'],
	'UNKICK_ALL' => $lang['unkick_all'],
	'KICKER_SET_CURRENT' => $current_setting,
	'KICKER_SET_CHANGE_BUTTON' => $lang['tk_kicker_set_change'],
	'PAGINATION' => $pagination, 
	'PAGE_NUMBER' => $page_number,
	'VIEW_SET_HEAD' => $lang['tk_view_set_head'],
	'VIEW_SET_EXPLAIN' => $lang['tk_view_set_explain'],
	'VIEW_SET_CHANGE_BUTTON' => $lang['tk_view_set_change_button'],

	'VIEW_SET_CURRENT' => $current_view_setting)
);

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
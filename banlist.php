<?php
/** 
*
* @package phpBB2
* @version $Id: banlist.php,v 1.36.2.2 2002/07/29 05:04:03 dougk_ff7 Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_BANLIST);
init_userprefs($userdata);
//
// End session management
//

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = 'username';
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
	$sort_order = 'ASC';
}

//
// Banlist sorting
//
$mode_types_text = array($lang['Sort_Username'], $lang['Ban_By'], $lang['Reason'], $lang['Date']);
$mode_types = array('username', 'banedby', 'reason', 'date');

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

//
// Generate page
//
$page_title = $lang['Banlist'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'banlist_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Submit'],

	'L_BAN_REASON' => $lang['Reason'],
	'L_BAN_BY' => $lang['Ban_By'],
	'L_BANNED' => $lang['When_Banned'],
	'L_DATE' => $lang['Date'],

	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid('banlist.'.$phpEx))
);

switch( $mode )
{
	case 'username':
		$order_by = "b.user_name $sort_order LIMIT $start, " . $board_config['topics_per_page'];
	break;
	
	case 'banedby':
		$order_by = "b.baned_by $sort_order LIMIT $start, " . $board_config['topics_per_page'];
	break;
	
	case 'reason':
		$order_by = "b.reason $sort_order LIMIT $start, " . $board_config['topics_per_page'];
	break;
	
	case 'date':
		$order_by = "b.ban_time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
	break;
	
	default:
		$order_by = "b.user_name $sort_order LIMIT $start, " . $board_config['topics_per_page'];
	break;
}

$sql = "SELECT b.*, u.user_level AS user1_level, u2.user_level AS user2_level
	FROM " . BANLIST_TABLE . " AS b
		LEFT JOIN " . USERS_TABLE  . " AS u ON b.ban_userid = u.user_id
		LEFT JOIN " . USERS_TABLE . " AS u2 ON b.ban_by_userid = u2.user_id
	WHERE b.ban_userid <> 0
	ORDER BY $order_by";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query banlist', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$i = 0;
	do
	{
	  	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

  	  	$template->assign_block_vars('banlistrow', array(
			'ROW_CLASS' => $row_class,
			
			'USERNAME' => username_level_color($row['user_name'], $row['user1_level'], $row['ban_userid']),
			'BY' => username_level_color($row['baned_by'], $row['user2_level'], $row['ban_by_userid']),
			'REASON' => ( $row['ban_pub_reason_mode'] == 0 ) ? $lang['You_been_banned'] : (( $row['reason'] ) ? $row['reason'] : $lang['No_reason']),
			'BANNED' => create_date($board_config['default_dateformat'], $row['ban_time'], $board_config['board_timezone']),
			
			'U_VIEWPROFILE2' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['ban_by_userid']),
			'U_VIEWPROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['ban_userid']))
		);

		$i++;
	}
	while ( $row = $db->sql_fetchrow($result) );
	$db->sql_freeresult($result);
}
else
{
	message_die(GENERAL_MESSAGE, $lang['No_bans'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>'));

}

if ( $mode != 'topten' || $board_config['topics_per_page'] < 10 )
{
	$sql = "SELECT COUNT(*) AS total
		FROM " . BANLIST_TABLE . "
		WHERE ban_userid <> 0";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting total users', '', __LINE__, __FILE__, $sql);
	}

	if ( $total = $db->sql_fetchrow($result) )
	{
		$total_members = $total['total'];

		$pagination = generate_pagination("banlist.$phpEx?mode=$mode&amp;order=$sort_order", $total_members, $board_config['topics_per_page'], $start). '&nbsp;';
	}
}
else
{
	$pagination = '&nbsp;';
	$total_members = 10;
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_members / $board_config['topics_per_page'] )),

	'L_GOTO_PAGE' => $lang['Goto_page'])
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
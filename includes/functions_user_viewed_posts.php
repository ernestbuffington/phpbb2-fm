<?php
/** 
*
* @package includes
* @version $Id: functions_user_viewed_posts.php,v 1.3 2003/05/24 04:54:34 nivisec Exp $
* @copyright (c) 2003 Nivisec.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
function uvp_get_topic_title($topic_id)
{
	global $db, $lang;
	
	$sql = "SELECT topic_title 
		FROM " . TOPICS_TABLE . "
	   WHERE topic_id = " . $topic_id;
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Topic_Viewdata_Error'], '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	
	if (!isset($row['topic_title']))
	{
		return $lang['Unknown_Title'];
	}
	else
	{
		return $row['topic_title'];
	}
}

function update_user_viewed($user_id, $topic_id)
{
	global $db, $lang;
	
	// See if our topic already exists 
	$sql = "SELECT viewed_id, num_views 
		FROM " . TOPICS_VIEWDATA_TABLE . "
	   	WHERE user_id = " . $user_id . "
	   		AND topic_id = " . $topic_id;
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Topic_Viewdata_Error'], '', __LINE__, __FILE__, $sql);
	}
		
	$row = $db->sql_fetchrow($result);

	// If it exists, we update the info 
	if ( isset( $row['viewed_id'] ) )
	{
		$views = $row['num_views'] + 1;
		
		$sql = "UPDATE " . TOPICS_VIEWDATA_TABLE . "
		   SET num_views = $views, last_viewed = " . time() . "
		   WHERE viewed_id = " . $row['viewed_id'];
	}
	// Else, we just insert the new default values 
	else
	{
		$sql = "INSERT INTO " . TOPICS_VIEWDATA_TABLE . " (user_id, topic_id, num_views, last_viewed)
			VALUES ($user_id, $topic_id, 1, " . time() . ")";	
	}

	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Topic_Viewdata_Error'], '', __LINE__, __FILE__, $sql);
	}
}		

	
function uvp_do_pagination($user_id)
{
	global $db, $lang, $template, $phpEx, $board_config, $start;
	
	$sql = "SELECT COUNT(*) AS total 
		FROM " . TOPICS_VIEWDATA_TABLE . "
		WHERE user_id = " . $user_id;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Topic_Viewdata_Error'], '', __LINE__, __FILE__, $sql);
	}
	else
	{
		$total = $db->sql_fetchrow($result);
		$total_views = ($total['total'] > 0) ? $total['total'] : 1;
		
		$pagination = generate_pagination('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id, $total_views, $board_config['topics_per_page'], $start) . '&nbsp;';
	}
	
	$template->assign_vars(array(
		"PAGINATION" => $pagination,
		"PAGE_NUMBER" => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_views / $board_config['topics_per_page'] )),
	
		"L_GOTO_PAGE" => $lang['Goto_page'])
	);
}

function uvp_make_sort_drop_box($sort)
{
	global $lang;
	
	$sort_types = array('last_viewed', 'num_views');
	
	$rval = '<select name="sort">';
	foreach($sort_types as $val)
	{
		$selected = ($sort == $val) ? 'selected="selected"' : '';
		$rval .= '<option value="' . $val . '" $selected>' . $lang[$val] . '</option>';
	}
	$rval .= '</select>';
	
	return $rval;
}

function uvp_make_order_drop_box($order)
{
	global $lang;
	
	$order_types = array('DESC', 'ASC');
	
	$rval = '<select name="order">';
	foreach($order_types as $val)
	{
		$selected = ($order == $val) ? ' selected="selected"' : '';
		$rval .= '<option value="' . $val . '"' . $selected . '>' . $lang[$val] . '</option>';
	}
	$rval .= '</select>';
	
	return $rval;
}

function display_user_viewed_data($user_id)
{
	global $template, $db, $board_config, $phpEx, $lang;
	
	//
	// Get parameters.  'var_name' => 'default'
	//
	$params = array('start' => 0, 'order' => 'DESC', 'sort' => 'last_viewed');
	
	foreach($params as $var => $default)
	{
		$$var = $default;
		if( isset($_POST[$var]) || isset($_GET[$var]) )
		{
			$$var = ( isset($_POST[$var]) ) ? $_POST[$var] : $_GET[$var];
		}
	}
	
	$sql = "SELECT tv.*, t.*, f.forum_name 
		FROM " . TOPICS_VIEWDATA_TABLE . " tv, " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
	   	WHERE tv.user_id = $user_id
	   		AND t.topic_id = tv.topic_id
	   		AND t.forum_id = f.forum_id
	   	ORDER BY $sort $order
	   	LIMIT $start, " . $board_config['topics_per_page'];
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['Topic_Viewdata_Error'], '', __LINE__, __FILE__, $sql);
	}
	
	while($row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('viewedrow', array(
			'TOPIC_TITLE' => $row['topic_title'],
			'FORUM_NAME' => $row['forum_name'],
			'FORUM_ID' => $row['forum_id'],
			'TOPIC_ID' => $row['topic_id'],
			'LAST_VIEWED' => create_date($board_config['default_dateformat'], $row['last_viewed'], $board_config['board_timezone']),
			'USER_ID' => $row['user_id'],
			'NUM_VIEWS' => $row['num_views'])
		);
	}
	$db->sql_freeresult($result);
	
	$template->assign_vars(array(
		'PHPEX' => $phpEx,
		'USER_ID' => $user_id,
		'S_MODE_SELECT' => uvp_make_sort_drop_box($psort),
		'S_ORDER_SELECT' => uvp_make_order_drop_box($porder),
		'FORUM_URL_CODE' => POST_FORUM_URL,
		'TOPIC_URL_CODE' => POST_TOPIC_URL,
		'L_VIEWS' => $lang['Views'],
		'L_TOPICS' => $lang['Topics'],
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_ORDER' => $lang['Order'],
		'L_FORUM' => $lang['Forum'],
		'L_USER_VIEWED' => $lang['User_viewed'],
		'L_LAST_VIEWED' => $lang['Last_Viewed'])
	);
	
	uvp_do_pagination($user_id);
	
	$template->set_filenames(array(
		'viewed_data_body' => 'profile_posts_viewed.tpl')
	);
	
	$template->assign_var_from_handle('USER_POSTS_VIEW_DATA', 'viewed_data_body');
}

?>
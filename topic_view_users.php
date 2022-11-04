<?php
/** 
*
* @package phpBB
* @version $Id: topic_view_users.php,v 1.36.2.2 2002/07/29 05:04:03 dougk_ff7 Exp $
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
$userdata = session_pagestart($user_ip, PAGE_TOPIC_VIEW);
init_userprefs($userdata);
//
// End session management
//

if ( !$board_config['enable_topic_view_users'] )
{ 
	message_die(GENERAL_MESSAGE, $lang['Topic_view_users_disabled']); 
}

if ( isset($HTTP_GET_VARS[POST_TOPIC_URL]) )
{
	$topic_id = intval($HTTP_GET_VARS[POST_TOPIC_URL]);
}
else if ( isset($HTTP_POST_VARS[POST_TOPIC_URL]) )
{
	$topic_id = intval($HTTP_POST_VARS[POST_TOPIC_URL]);
}

if (empty($topic_id))
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

if ( !$userdata['session_logged_in'] ) 
{ 
	$header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: "; 
	header($header_location . append_sid("login.$phpEx?redirect=topic_view_users.$phpEx&" . POST_TOPIC_URL . "=$topic_id", true));
	exit;
}

// find the forum, in which the topic are located
$sql = "SELECT f.forum_id, t.topic_title 
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f  
	WHERE f.forum_id = t.forum_id 
		AND t.topic_id = " . $topic_id;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain topic information", '', __LINE__, __FILE__, $sql);
}

if ( !($forum_topic_data = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

$forum_id = $forum_topic_data['forum_id'];
$topic_title = $forum_topic_data['topic_title'];

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

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

//
// Memberlist sorting
//
$mode_types_text = array($lang['Last_viewed'], $lang['Sort_Joined'], $lang['Sort_Username'], $lang['Views'], $lang['Sort_Email'],  $lang['Sort_Website']);
$mode_types = array('topic_time', 'joindate', 'username', 'topic_count', 'email', 'website');

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
$select_sort_order .= '<input type="hidden" name="'.POST_TOPIC_URL.'" value="'.$topic_id.'"/>';
//
// Generate page
//
$page_title = $lang['Topic_views'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'topic_viewusers_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_EMAIL' => $lang['Email'],
	'L_WEBSITE' => $lang['Website'],
	'L_VIEWS' => $lang['Views'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_SUBMIT' => $lang['Sort'],
	'L_PM' => $lang['PM'], 
	'L_JOINED' => $lang['Joined'], 
	'L_LAST_VIEWED' => $lang['Last_viewed'], 
	'L_VIEWTOPIC' => $topic_title,
	
	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'U_VIEWTOPIC' => append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id),
	'S_MODE_ACTION' => append_sid('topic_view_users.'.$phpEx))
);

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];

	switch( $mode )
	{
		case 'joindate':
			$order_by = "u.user_regdate $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;

		case 'username':
			$order_by = "u.username $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;

		case 'topic_count':
			$order_by = "t.num_views $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;

		case 'topic_time':
			$order_by = "t.last_viewed $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;

		case 'email':
			$order_by = "u.user_email $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;

		case 'website':
			$order_by = "u.user_website $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;

		default:
			$order_by = "t.last_viewed $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	}
}
else
{
	$order_by = "t.last_viewed $sort_order LIMIT $start, " . $board_config['topics_per_page'];
}


$sql = "SELECT t.*, u.username, u.user_level, u.user_from, u.user_regdate, u.user_email, u.user_website
	FROM " . TOPICS_VIEWDATA_TABLE . " t, " . USERS_TABLE . " u 
	WHERE t.user_id = u.user_id 
		AND t.topic_id = " . $topic_id . "
	GROUP BY t.user_id
	ORDER BY $order_by";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query topic users.', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$i = 0;
	do
	{
		$user_id = $row['user_id'];
		$username = username_level_color($row['username'], $row['user_level'], $user_id);
		$username = ( $row['user_id'] == ANONYMOUS ) ? $lang['Guest'] : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $row['user_id']) . '" class="genmed">' . $username . '</a>';

		$from = ( !empty($row['user_from']) ) ? $row['user_from'] : '&nbsp;';
		$joined = create_date($lang['DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']);
		$topic_time = ( $row['last_viewed'] ) ? create_date($board_config['default_dateformat'], $row['last_viewed'], $board_config['board_timezone']) : $lang['Never_last_logon'];
		$view_count = ( $row['num_views'] ) ? $row['num_views'] : '';

		if ( !empty($row['user_viewemail']) || $userdata['user_level'] == ADMIN )
		{
			$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $user_id) : 'mailto:' . $row['user_email'];

			$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
			$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
		}
		else
		{
			$email_img = $email = '&nbsp;';
		}

		$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id");
		$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>';
		$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

		$temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$user_id");
		$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
		$pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';

		$www_img = ( $row['user_website'] ) ? '<a href="' . $row['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '';
		$www = ( $row['user_website'] ) ? '<a href="' . $row['user_website'] . '" target="_userwww">' . $lang['Visit_website'] . '</a>' : '';

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('memberrow', array(
			'ROW_NUMBER' => $i + ( $HTTP_GET_VARS['start'] + 1 ),
			'ROW_CLASS' => $row_class,
			'USERNAME' => $username,
			'FROM' => $view_count,
			'JOINED' => $joined,
			'POSTS' => $topic_time,
			'PROFILE_IMG' => $profile_img, 
			'PROFILE' => $profile, 
			'PM_IMG' => $pm_img,
			'PM' => $pm,
			'EMAIL_IMG' => $email_img,
			'EMAIL' => $email,
			'WWW_IMG' => $www_img,
			'WWW' => $www)
		);

		$i++;
	}
	while ( $row = $db->sql_fetchrow($result) );
}

if ( $mode != 'topten' || $board_config['topics_per_page'] < 10 )
{
	$sql = "SELECT count(*) AS total
		FROM " . TOPICS_VIEWDATA_TABLE . "
		WHERE topic_id = " . $topic_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting total topic users.', '', __LINE__, __FILE__, $sql);
	}

	if ( $total = $db->sql_fetchrow($result) )
	{
		$total_members = $total['total'];
		$pagination = generate_pagination("topic_view_users.$phpEx?".POST_TOPIC_URL."=$topic_id&amp;mode=$mode&amp;order=$sort_order", $total_members, $board_config['topics_per_page'], $start). '&nbsp;';
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

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
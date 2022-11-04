<?php
/** 
 *
* @package phpbb
* @version $Id: viewhier.php,v 1.139.2.10 2003/03/04 21:02:44 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
	exit;
}

// Set to true to disable special topic types...
$no_topic_type = true;

$forum_id = ( !empty($HTTP_GET_VARS['pf']) ) ? $HTTP_GET_VARS['pf'] : 0;
$forum_id = intval($forum_id);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_GET_VARS['mark']) || isset($HTTP_POST_VARS['mark']) )
{
	$mark_read = (isset($HTTP_POST_VARS['mark'])) ? $HTTP_POST_VARS['mark'] : $HTTP_GET_VARS['mark'];
}
else
{
	$mark_read = '';
}
//
// End initial var setup
//

//
// Check if the user has actually sent a forum ID with his/her request
// If not give them a nice error page.
//
if ( !empty($forum_id) )
{
	$sql = "SELECT *
		FROM " . FORUMS_TABLE . "
		WHERE forum_id = $forum_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
	}
}
else
{
	message_die(GENERAL_MESSAGE, 'Forum_not_exist');
}

//
// If the query doesn't return any rows this isn't a valid forum. Inform
// the user.
//
if ( !($forum_row = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Forum_not_exist');
}


//
// Start auth check
//
$is_auth = array();
$is_auth = auth(AUTH_ALL, $forum_id, $userdata, $forum_row);

if ( !$is_auth['auth_read'] || !$is_auth['auth_view'] )
{
	if ( !$userdata['session_logged_in'] )
	{
		$redirect = POST_HIERARCHIE_URL . "=" . ($hierarchie_level) . "&" . POST_PARENTFORUM_URL . "=$forum_id" . ( ( isset($start) ) ? "&start=$start" : '' );
		redirect(append_sid("login.$phpEx?redirect=index.$phpEx&$redirect", true));
	}
	//
	// The user is not authed to read this forum ...
	//
	$message = ( !$is_auth['auth_view'] ) ? $lang['Forum_not_exist'] : sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);
	$message .= '<br /><br />' . sprintf($lang['Click_return_subscribe_lw'], '<a href="' . append_sid('lwtopup.'.$phpEx) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);
}
//
// End of auth check
//


//
// Password check
//
if( !$is_auth['auth_mod'] && $userdata['user_level'] < 1 )
{
	$redirect = str_replace("&amp;", "&", preg_replace('#.*?([a-z]+?\.' . $phpEx . '.*?)$#i', '\1', htmlspecialchars($HTTP_SERVER_VARS['REQUEST_URI'])));

	if( $HTTP_POST_VARS['cancel'] )
	{
		redirect(append_sid("index.$phpEx"));
	}
	else if( $HTTP_POST_VARS['pass_login'] )
	{
		if( $forum_row['forum_password'] != '' )
		{
			password_check('forum', $forum_id, $HTTP_POST_VARS['password'], $redirect);
		}
	}

	$passdata = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_fpass']) ) ? unserialize(stripslashes($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_fpass'])) : '';
	if( $forum_row['forum_password'] != '' && ($passdata[$forum_id] != md5($forum_row['forum_password'])) )
	{
		password_box('forum', $redirect);
	}
}


//
// Forum enter limit 
//
if (($userdata['user_posts'] < $forum_row['forum_enter_limit']) && $userdata['user_level'] < 1 )
{
	message_die(GENERAL_MESSAGE, sprintf($lang['Forum_enter_limit_error'], $forum_row['forum_name'], $forum_row['forum_enter_limit']));
}

//
// Is user watching this forum?
//
if( $userdata['session_logged_in'] )
{
	$can_watch_forum = TRUE;
	$sql = "SELECT user_id
		FROM " . FORUMS_WATCH_TABLE . "
		WHERE forum_id = $forum_id
			AND user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain forum watch information", '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		if ( isset($HTTP_GET_VARS['unwatch']) )
		{
			if ( $HTTP_GET_VARS['unwatch'] == 'forum' )
			{
				$is_watching_forum = 0;

				$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
				$sql = "DELETE $sql_priority FROM " . FORUMS_WATCH_TABLE . "
					WHERE forum_id = $forum_id
						AND user_id = " . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not delete forum watch information", '', __LINE__, __FILE__, $sql);
				}
			}

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;start=$start") . '">')
			);

			$message = $lang['No_longer_watching_forum'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;start=$start") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_forum = TRUE;

			if ( $row['notify_status'] )
			{
				$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
				$sql = "UPDATE $sql_priority " . FORUMS_WATCH_TABLE . "
					SET notify_status = 0
					WHERE forum_id = $forum_id
						AND user_id = " . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not update forum watch information", '', __LINE__, __FILE__, $sql);
				}
			}
		}
	}
	else
	{
		if ( isset($HTTP_GET_VARS['watch']) )
		{
			if ( $HTTP_GET_VARS['watch'] == 'forum' )
			{
				$is_watching_forum = TRUE;

				$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
				$sql = "INSERT $sql_priority INTO " . FORUMS_WATCH_TABLE . " (user_id, forum_id, notify_status)
					VALUES (" . $userdata['user_id'] . ", $forum_id, 0)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not insert forum watch information", '', __LINE__, __FILE__, $sql);
				}
			}

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;start=$start") . '">')
			);

			$message = $lang['You_are_watching_forum'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;start=$start") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_forum = 0;
		}
	}
	$row = $db->sql_fetchrow($result);
}
else
{
	if ( isset($HTTP_GET_VARS['unwatch']) )
	{
		if ( $HTTP_GET_VARS['unwatch'] == 'forum' )
		{
			redirect(append_sid("login.$phpEx?redirect=viewforum.$phpEx&" . POST_FORUM_URL . "=$forum_id&unwatch=forum", true));
		}
	}
	else
	{
		$can_watch_forum = $is_watching_forum = 0;
	}
}

//
// Forum watch information
//
$s_watching_forum = '';
if ( $can_watch_forum )
{
	if ( $is_watching_forum )
	{
		$s_watching_forum = "<a href=\"viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;unwatch=forum&amp;start=$start&amp;sid=" . $userdata['session_id'] . '" class="gensmall" title="' . $lang['Stop_watching_forum'] .'">' . $lang['Stop_watching_forum'] . '</a>';
		$s_watching_forum_img = ( isset($images['forum_un_watch']) ) ? "<a href=\"viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;unwatch=forum&amp;start=$start&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['forum_un_watch'] . '" alt="' . $lang['Stop_watching_forum'] . '" title="' . $lang['Stop_watching_forum'] . '" /></a>' : '';
	}
	else
	{
		$s_watching_forum = "<a href=\"viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;watch=forum&amp;start=$start&amp;sid=" . $userdata['session_id'] . '" class="gensmall" title="' . $lang['Start_watching_forum'] .'">' . $lang['Start_watching_forum'] . '</a>';
		$s_watching_forum_img = ( isset($images['Forum_watch']) ) ? "<a href=\"viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;watch=forum&amp;start=$start&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['Forum_watch'] . '" alt="' . $lang['Start_watching_forum'] . '" title="' . $lang['Start_watching_forum'] . '" /></a>' : '';
	}
}

//
// Handle marking posts
//
if ( $mark_read == 'topics' )
{
	if ( $userdata['session_logged_in'] )
	{
		$sql = "SELECT MAX(post_time) AS last_post 
			FROM " . POSTS_TABLE . " 
			WHERE forum_id = $forum_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain forums information', '', __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) : array();
			$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : array();

			if ( ( count($tracking_forums) + count($tracking_topics) ) >= 150 && empty($tracking_forums[$forum_id]) )
			{
				asort($tracking_forums);
				unset($tracking_forums[key($tracking_forums)]);
			}

			if ( $row['last_post'] > $userdata['user_lastvisit'] )
			{
				$tracking_forums[$forum_id] = time();

				setcookie($board_config['cookie_name'] . '_f', serialize($tracking_forums), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
			}
		}
		$row = $db->sql_fetchrow($result);

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("index.$phpEx?" . POST_HIERARCHIE_URL . "=" . ($hierarchie_level) . "&" . POST_PARENTFORUM_URL . "=$forum_id") . '">')
		);
	}

	$message = $lang['Topics_marked_read'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("index.$phpEx?" . POST_HIERARCHIE_URL . "=" . ($hierarchie_level) . "&amp;" . POST_PARENTFORUM_URL . "=$forum_id") . '">', '</a> ');
	message_die(GENERAL_MESSAGE, $message);
}
//
// End handle marking posts
//

$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : '';
$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) : '';

//
// Do the forum Prune
//
if ( $is_auth['auth_mod'] && $board_config['prune_enable'] )
{
	if ( $forum_row['prune_next'] < time() && $forum_row['prune_enable'] )
	{
		include($phpbb_root_path . 'includes/prune.'.$phpEx);
		require($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
		auto_prune($forum_id);
	}
}
//
// End of forum prune
//

//
// Do the forum move
//
if ( $is_auth['auth_mod'] && $board_config['prune_enable'] )
{
	if ( $forum_row['move_next'] < time() && $forum_row['move_enable'] )
	{
		include($phpbb_root_path . 'includes/functions_move.'.$phpEx);
		require($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
		auto_move($forum_id);
	}
}
//
// End of forum move
//

//
// Obtain list of moderators of each forum
// First users, then groups ... broken into two queries
//
$sql = "SELECT u.user_id, u.username, u.user_level 
	FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
	WHERE aa.forum_id = $forum_id 
		AND aa.auth_mod = " . TRUE . " 
		AND g.group_single_user = 1
		AND ug.group_id = aa.group_id 
		AND g.group_id = aa.group_id 
		AND u.user_id = ug.user_id 
	GROUP BY u.user_id, u.username  
	ORDER BY u.user_id";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
}

$moderators = array();
while( $row = $db->sql_fetchrow($result) )
{
	$moderators[] = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" class="gensmall">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>';
}
$row = $db->sql_fetchrow($result);

$sql = "SELECT g.group_id, g.group_name 
	FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g 
	WHERE aa.forum_id = $forum_id
		AND aa.auth_mod = " . TRUE . " 
		AND g.group_single_user = 0
		AND g.group_type <> ". GROUP_HIDDEN ."
		AND ug.group_id = aa.group_id 
		AND g.group_id = aa.group_id 
	GROUP BY g.group_id, g.group_name  
	ORDER BY g.group_id";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
}

while( $row = $db->sql_fetchrow($result) )
{
	$moderators[] = '<a href="' . append_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=" . $row['group_id']) . '">' . $row['group_name'] . '</a>';
}
$row = $db->sql_fetchrow($result);
	
$l_moderators = ( sizeof($moderators) == 1 ) ? $lang['Moderator'] . ':' : $lang['Moderators'] . ':';
$forum_moderators = ( sizeof($moderators) ) ? implode(', ', $moderators) : $lang['None'];
unset($moderators);

//
// Generate a 'Show topics in previous x days' select box. If the topicsdays var is sent
// then get it's value, find the number of topics with dates newer than it (to properly
// handle pagination) and alter the main query
//
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['All_Topics'], $lang['1_Day'], $lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year']);

if ( !empty($HTTP_POST_VARS['topicdays']) || !empty($HTTP_GET_VARS['topicdays']) )
{
	$topic_days = ( !empty($HTTP_POST_VARS['topicdays']) ) ? intval($HTTP_POST_VARS['topicdays']) : intval($HTTP_GET_VARS['topicdays']);
	$min_topic_time = time() - ($topic_days * 86400);

	$sql = "SELECT COUNT(t.topic_id) AS forum_topics 
		FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p 
		WHERE t.forum_id = $forum_id 
			AND p.post_id = t.topic_last_post_id
			AND p.post_time >= $min_topic_time"; 
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain limited topics count information', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	$topics_count = ( $row['forum_topics'] ) ? $row['forum_topics'] : 1;
	$limit_topics_time = "AND p.post_time >= $min_topic_time";

	if ( !empty($HTTP_POST_VARS['topicdays']) )
	{
		$start = 0;
	}
}
else
{
	$topics_count = ( $forum_row['forum_topics'] ) ? $forum_row['forum_topics'] : 1;

	$limit_topics_time = '';
	$topic_days = 0;
}

$select_topic_days = '<select name="topicdays">';
for($i = 0; $i < sizeof($previous_days); $i++)
{
	$selected = ($topic_days == $previous_days[$i]) ? ' selected="selected"' : '';
	$select_topic_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}
$select_topic_days .= '</select>';


// 
// All GLOBAL announcement data, this keeps GLOBAL announcements 
// on each viewforum page ... 
// 
if ( $forum_row['forum_sort'] == 'SORT_ALPHA' ) 
{
	$topic_order = $topic_order2 = 't.topic_title, ';
}
else
{
	$topic_order = 'p.post_time DESC, ';
	$topic_order2 = 'p2.post_time DESC, ';
}

$sql = "SELECT t.*, u.username, u.user_id, u.user_level, u2.user_level AS user_level2, u2.username as user2, u2.user_id as id2, p.post_time, p.post_username, pt.post_text, pt.bbcode_uid
   FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2, " . POSTS_TEXT_TABLE . " pt
   WHERE t.topic_poster = u.user_id 
      AND p.post_id = t.topic_last_post_id 
 	  AND p.post_id = pt.post_id
      AND p.poster_id = u2.user_id 
      AND t.topic_type = " . POST_GLOBAL_ANNOUNCE . " 
   	ORDER BY t.topic_priority DESC, " . $topic_order . "t.topic_last_post_id DESC"; 
if( !$result = $db->sql_query($sql) ) 
{ 
   message_die(GENERAL_ERROR, "Couldn't obtain global announcement information", "", __LINE__, __FILE__, $sql); 
} 

$topic_rowset = array(); 
$total_announcements = 0; 
while( $row = $db->sql_fetchrow($result) ) 
{ 
   $topic_rowset[] = $row; 
   $total_announcements++; 
} 
$db->sql_freeresult($result); 

//
// All announcement data, this keeps announcements
// on each viewforum page ...
//
$sql = "SELECT t.*, u.username, u.user_id, u.user_level, u2.user_level AS user_level2, u2.username as user2, u2.user_id as id2, p.post_time, p.post_username, pt.post_text, pt.bbcode_uid
	FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2, " . POSTS_TEXT_TABLE . " pt
	WHERE t.forum_id = $forum_id 
		AND t.topic_poster = u.user_id
		AND p.post_id = t.topic_last_post_id
		AND p.post_id = pt.post_id
		AND p.poster_id = u2.user_id
		AND t.topic_type = " . POST_ANNOUNCE . " 
	ORDER BY t.topic_priority DESC, " . $topic_order . "t.topic_last_post_id DESC";
if ( !($result = $db->sql_query($sql)) )
{
   message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
}

while( $row = $db->sql_fetchrow($result) )
{
	$topic_rowset[] = $row;
	$total_announcements++;
}
$db->sql_freeresult($result);


//
// Grab all the basic data (all topics except announcements)
// for this forum
//
if ( $board_config['locked_last'] ) 
{
	$topic_sink_order = 't.topic_status ASC, ';
}

$sql = "SELECT t.*, u.username, u.user_id, u.user_level, u2.user_level AS user_level2, u2.username as user2, u2.user_id as id2, p.post_username, p2.post_username AS post_username2, p2.post_time, pt.post_text, pt.bbcode_uid 
	FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u2, " . POSTS_TEXT_TABLE . " pt
	WHERE t.forum_id = $forum_id
		AND t.topic_poster = u.user_id
		AND p.post_id = t.topic_first_post_id
		AND p2.post_id = t.topic_last_post_id
		AND p2.post_id = pt.post_id
		AND u2.user_id = p2.poster_id 
		AND t.topic_type <> " . POST_ANNOUNCE . " 
		AND t.topic_type <> " . POST_GLOBAL_ANNOUNCE . " 
		$limit_topics_time
	ORDER BY t.topic_type DESC, " . $topic_sink_order . "t.topic_priority DESC, " . $topic_order2 . "t.topic_last_post_id DESC
	LIMIT $start, " . $board_config['topics_per_page'];
if ( !($result = $db->sql_query($sql)) )
{
   message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
}

$total_topics = 0;
while( $row = $db->sql_fetchrow($result) )
{
	$topic_rowset[] = $row;
	$total_topics++;
}
$db->sql_freeresult($result);

//
// Total topics ...
//
$total_topics += $total_announcements;

//
// Define censored word matches
//
if ( !$board_config['allow_swearywords'] )
{
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
}
else if ( !$userdata['user_allowswearywords'] )
{
    $orig_word = $replacement_word = array();
    obtain_word_list($orig_word, $replacement_word);
}

//
// Post URL generation for templating vars
//
$template->assign_vars(array(
	'L_DISPLAY_TOPICS' => $lang['Display_topics'],

	'U_POST_NEW_TOPIC' => append_sid("posting.$phpEx?mode=newtopic&amp;" . POST_FORUM_URL . "=$forum_id&" . POST_HIERARCHIE_URL . "=" . ($hierarchie_level)),

	'S_SELECT_TOPIC_DAYS' => $select_topic_days,
	'S_POST_DAYS_ACTION' => append_sid("index.$phpEx?" . POST_HIERARCHIE_URL . "=" . ($hierarchie_level) . "&amp;" . POST_PARENTFORUM_URL . "=$forum_id&amp;start=$start"))
);

//
// User authorisation levels output
//
$s_auth_can = ( ( $is_auth['auth_post'] ) ? $lang['Rules_post_can'] : $lang['Rules_post_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_reply'] ) ? $lang['Rules_reply_can'] : $lang['Rules_reply_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_edit'] ) ? $lang['Rules_edit_can'] : $lang['Rules_edit_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_delete'] ) ? $lang['Rules_delete_can'] : $lang['Rules_delete_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_vote'] ) ? $lang['Rules_vote_can'] : $lang['Rules_vote_cannot'] ) . '<br />';
$s_auth_can .= ( $is_auth['auth_ban'] && $board_config['enable_bancards']  ) ? $lang['Rules_ban_can'] . '<br />' : ''; 
$s_auth_can .= ( $is_auth['auth_voteban'] && $board_config['enable_bancards']  ) ? $lang['Rules_voteban_can'] . '<br />' : ''; 
$s_auth_can .= ( $is_auth['auth_greencard'] && $board_config['enable_bancards']  ) ? $lang['Rules_greencard_can'] . '<br />' : ''; 
$s_auth_can .= ( $is_auth['auth_bluecard'] && $board_config['enable_bancards']  ) ? $lang['Rules_bluecard_can'] . '<br />' : ''; 
attach_build_auth_levels($is_auth, $s_auth_can);

if ( $is_auth['auth_mod'] )
{
	if ($board_config['AJAXed_status'])
    {
		include($phpbb_root_path . 'includes/sajax.'.$phpEx);
		$sajax->init('ajax.'.$phpEx.'?' . POST_FORUM_URL . '=' . $forum_id . '&sid=' . $userdata['session_id']);
		$sajax->export('lock_unlock_topic', 'delete_topic', 'move_topic', 'move_build');
		$sajax->handle_client_request();
		$sajax->show_javascript();
		$template->assign_block_vars('switch_ajax', array());
		$template->assign_vars(array('AJAXED_NO_TOPICS' => $lang['No_topics_post_one']));
	}

	$s_auth_can .= sprintf($lang['Rules_moderate'], "<a href=\"modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;start=" . $start . "&amp;sid=" . $userdata['session_id'] . '">', '</a><br />');
}

//
// Mozilla navigation bar
//
$nav_links['up'] = array(
	'url' => append_sid('index.'.$phpEx),
	'title' => $lang['Forum_Index']
);

//
// Set columns for message & answered icons/images
//
$colspan = 2;
if ( $forum_row['icon_enable'] )
{
	$colspan = 3;
}

if ( $forum_row['answered_enable'] )
{
	$template->assign_block_vars('switch_answered_images', array());
}

//
// Forum rules
//
if ( $forum_row['forum_rules'] )
{
	$template->assign_block_vars('switch_forum_rules', array() );
}


//
// Dump out the page header and load viewhier template
//
$page_title = $lang['View_forum'] . ' - ' . $forum_row['forum_name'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'hier' => 'viewhier_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);


$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
	'FORUM_NAME' => $forum_row['forum_name'],
	'FORUM_TOPICS' => $topics_count,
	'FORUM_DESC' => $forum_row['forum_desc'],	
	'FORUM_RULES' => $forum_row['forum_rules'],	
	'MODERATORS' => $forum_moderators,
	'POST_IMG' => ( $forum_row['forum_status'] == FORUM_LOCKED ) ? $images['post_locked'] : $images['post_new'],
	'COLSPAN' => $colspan,

	'FOLDER_IMG' => $images['folder'],
	'FOLDER_NEW_IMG' => $images['folder_new'],
	'FOLDER_HOT_IMG' => $images['folder_hot'],
	'FOLDER_HOT_NEW_IMG' => $images['folder_hot_new'],
	'FOLDER_LOCKED_IMG' => $images['folder_locked'],
	'FOLDER_LOCKED_NEW_IMG' => $images['folder_locked_new'],
	'FOLDER_STICKY_IMG' => $images['folder_sticky'],
	'FOLDER_STICKY_NEW_IMG' => $images['folder_sticky_new'],
	'FOLDER_ANNOUNCE_IMG' => $images['folder_announce'],
	'FOLDER_ANNOUNCE_NEW_IMG' => $images['folder_announce_new'],
	'FOLDER_GLOBAL_ANNOUNCE_IMG' => $images['folder_global_announce'],
	'FOLDER_GLOBAL_ANNOUNCE_NEW_IMG' => $images['folder_global_announce_new'],
	'FOLDER_POLL_IMG' => $images['folder_poll'],
	'FOLDER_POLL_NEW_IMG' => $images['folder_poll_new'],
	'ANSWERED_TOPIC_IMG' => $images['folder_answered'],
	'UNANSWERED_TOPIC_IMG' => $images['folder_unanswered'],
	'FOLDER_LINK_IMG' => $images['folder_linked'],
	'FOLDER_MOVED_IMG' => $images['folder_moved'],

	'L_TOPICS' => $lang['Topics'],
	'L_REPLIES' => $lang['Replies'],
	'L_VIEWS' => $lang['Views'],
	'L_POSTS' => $lang['Posts'],
	'L_LASTPOST' => $lang['Last_Post'], 
	'L_MODERATOR' => $l_moderators, 
	'L_MARK_TOPICS_READ' => $lang['Mark_all_topics'], 
	'L_POST_NEW_TOPIC' => ( $forum_row['forum_status'] == FORUM_LOCKED ) ? $lang['Forum_locked'] : $lang['Post_new_topic'], 
	'L_NO_NEW_POSTS' => $lang['No_new_posts'],
	'L_NEW_POSTS' => $lang['New_posts'],
	'L_NO_NEW_POSTS_LOCKED' => $lang['No_new_posts_locked'], 
	'L_NEW_POSTS_LOCKED' => $lang['New_posts_locked'], 
	'L_NO_NEW_POSTS_HOT' => $lang['No_new_posts_hot'],
	'L_NEW_POSTS_HOT' => $lang['New_posts_hot'],
	'L_ANNOUNCEMENT' => $lang['Post_Announcement'], 
	'L_GLOBAL_ANNOUNCEMENT' => $lang['Post_global_announcement'], 
	'L_STICKY' => $lang['Post_Sticky'], 
	'L_POLL' => $lang['Post_Poll'],
	'L_ANSWERED_TOPIC' => $lang['Answered'],
	'L_UNANSWERED_TOPIC' => $lang['Unanswered'],
	'L_LINKED' => $lang['Post_linked'], 
	'L_MOVED' => $lang['Moved'],
	'L_POSTED' => $lang['Posted'],
	'L_AUTHOR' => $lang['Author'],
	'L_SEARCH_FOR' => $lang['Search_for'],
	'L_REFRESH_PAGE' => $lang['Refresh_page'],
	'L_FORUM_RULES' => $lang['Forum_rules'],

	'S_WATCH_FORUM' => $s_watching_forum,
	'S_AUTH_LIST' => $s_auth_can, 

	'U_VIEW_FORUM' => append_sid('index.'.$phpEx.'?' . POST_HIERARCHIE_URL . '=' . ($hierarchie_level) . '&amp;' . POST_PARENTFORUM_URL . '=' . $forum_id),

	'U_MARK_T_READ' => append_sid('index.'.$phpEx.'?' . POST_HIERARCHIE_URL . '=' . ($hierarchie_level) . '&amp;' . POST_PARENTFORUM_URL . '=' . $forum_id . '&amp;mark=topics'))
);
//
// End header
//

//
// Update the forum view counter
//
$sql = "UPDATE " . FORUMS_TABLE . "
	SET forum_views = forum_views + 1
	WHERE forum_id = $forum_id";
if ( !$db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not update forum views.", '', __LINE__, __FILE__, $sql);
}

//
// Okay, lets dump out the page ...
//
if( $total_topics )
{
	$prec_topic_real_type = -1;

	// Ratings
	include($phpbb_root_path . 'includes/functions_rating.'.$phpEx);
	$rating_config = get_rating_config('1,19');
	if ( $rating_config[1] == 1 )
	{
		get_rating_ranks();

		// Show dropdown box for ratings screen if appropriate 
		if ( $rating_config[19] == 1 && $forum_topic_data['auth_view'] < 2 && $forum_topic_data['auth_read'] < 2 )
		{
			$u_ratings = append_sid($phpbb_root_path.'ratings.'.$phpEx);
			$template->assign_block_vars('ratingsbox', array(
				'U_RATINGS' => $u_ratings,
				'L_LATEST_RATINGS' => $lang['Latest_ratings'],
				'L_HIGHEST_RANKED_POSTS' => $lang['Highest_ranked_posts'],
				'L_HIGHEST_RANKED_TOPICS' => $lang['Highest_ranked_topics'],
				'L_HIGHEST_RANKED_POSTERS' => $lang['Highest_ranked_posters'])
			);
		}
	}	
	
	//
	// Begin loop of topics ...
	//	
	for($i = 0; $i < $total_topics; $i++)
	{
		$topic_rating = ( sizeof($topic_rank_set) > 0 && $topic_rowset[$i]['rating_rank_id'] > 0 ) ? $topic_rank_set[$topic_rowset[$i]['rating_rank_id']] : '';
	
		$topic_id = $topic_rowset[$i]['topic_id'];
		$topic_title = $topic_rowset[$i]['topic_title'];

		if ( !empty($orig_word) )
		{
			$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
		}

		if ($board_config['amazon_enable'] && $forum_row['amazon_display'])
		{
			include($phpbb_root_path . 'includes/functions_amazon.'.$phpEx);
		}
	
   		$topic_title = capitalization($topic_title);

		if ($board_config['enable_quick_titles'])
		{
			if ( $topic_rowset[$i]['title_pos'] )
			{
				$topic_title = (empty($topic_rowset[$i]['title_compl_infos'])) ? $topic_title : $topic_title . ' <span style="color: #' . $topic_rowset[$i]['title_compl_color'] . '">' . $topic_rowset[$i]['title_compl_infos'] . '</span>';
			}
			else
			{
				$topic_title = (empty($topic_rowset[$i]['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $topic_rowset[$i]['title_compl_color'] . '">' . $topic_rowset[$i]['title_compl_infos'] . '</span> ' . $topic_title;
			}
		}
		
		// Mouse hover topic preview
		$topic_content = $topic_rowset[$i]['post_text'];
		$bbcode_uid = $topic_rowset[$i]['bbcode_uid'];
		$topic_content = bbencode_strip($topic_content, $bbcode_uid);
		$topic_content = (strlen($topic_content) > 200) ? substr($topic_content, 0, 197) . '...' : $topic_content; 

		$replies = $topic_rowset[$i]['topic_replies'];

		$topic_type = $topic_rowset[$i]['topic_type'];

		if ( !$topic_rowset[$i]['topic_icon'] && $forum_row['icon_enable'] )
		{ 
			$icon = '<td class="row1">&nbsp;</td>'; 
		} 
		else
		{ 
			$icon = '<td class="row1" align="center" valign="middle" width="5%" nowrap="nowrap"><img src="' . $images['msg_icons'] . $topic_rowset[$i]['topic_icon'] . '.gif" alt="' . $lang['Topic_icon'] . '" title="' . $lang['Topic_icon'] . '" /></td>'; 
		} 
		
		if ( !$forum_row['icon_enable'] )
		{
			$icon = '';
		}

		if ( $topic_rowset[$i]['answer_status'] && $forum_row['answered_enable'] )
		{ 
			$answered_icon = '<td class="row1" align="center" valign="middle" width="5%"><img src="' . $images['folder_answered'] . '" alt="' . $lang['Answered'] . '" title="' . $lang['Answered'] . '" /></td>'; 
		} 
		else
		{ 
			$answered_icon = '<td class="row1" align="center" valign="middle" width="5%"><img src="' . $images['folder_unanswered'] . '" alt="' . $lang['Unanswered'] . '" title="' . $lang['Unanswered'] . '" /></td>'; 
		} 
		
		if ( !$forum_row['answered_enable'] )
		{
			$answered_icon = '';
		}

		if( $topic_type == POST_GLOBAL_ANNOUNCE ) 
		{ 
		   $topic_type = $lang['Topic_global_announcement'] . ' '; 
		} 
		else if( $topic_type == POST_ANNOUNCE )
		{
			$topic_type = $lang['Topic_Announcement'] . ' ';
		}
		else if( $topic_type == POST_STICKY )
		{
			$topic_type = $lang['Topic_Sticky'] . ' ';
		}
		else
		{
			$topic_type = '';		
		}

		if( $topic_rowset[$i]['topic_vote'] )
		{
			$topic_type .= $lang['Topic_Poll'] . ' ';
		}
		
		if( $topic_rowset[$i]['topic_status'] == TOPIC_MOVED )
		{
			$topic_type = $lang['Topic_Moved'] . ' ';
			$topic_id = $topic_rowset[$i]['topic_moved_id'];

			$folder_image =  $images['folder_moved'];
			$folder_alt = $lang['Topics_Moved'];
			$newest_post_img = '';
		}
		else if( $topic_rowset[$i]['topic_status'] == TOPIC_LINKED )
		{
			$topic_type = $lang['Topic_Linked'] . ' ';
			$topic_id = $topic_rowset[$i]['topic_moved_id'];

			$folder_image =  $images['folder_linked'];
			$folder_alt = $lang['Topics_Linked'];
			$newest_post_img = '';
		}
		else
		{
			if( $topic_rowset[$i]['topic_type'] == POST_GLOBAL_ANNOUNCE ) 
			{ 
				$folder = $images['folder_global_announce']; 
				$folder_new = $images['folder_global_announce_new']; 
			} 
			else if( $topic_rowset[$i]['topic_type'] == POST_ANNOUNCE )
			{
				$folder = $images['folder_announce'];
				$folder_new = $images['folder_announce_new'];
			}
			else if( $topic_rowset[$i]['topic_type'] == POST_STICKY )
			{
				$folder = $images['folder_sticky'];
				$folder_new = $images['folder_sticky_new'];
			}
			else if( $topic_rowset[$i]['topic_vote'] )
			{
				$folder = $images['folder_poll'];
				$folder_new = $images['folder_poll_new'];
			}
			else if( $topic_rowset[$i]['topic_status'] == TOPIC_LOCKED )
			{
				$folder = $images['folder_locked'];
				$folder_new = $images['folder_locked_new'];
			}
			else
			{
				if($replies >= $board_config['hot_threshold'])
				{
					$folder = $images['folder_hot'];
					$folder_new = $images['folder_hot_new'];
				}
				else
				{
					$folder = $images['folder'];
					$folder_new = $images['folder_new'];
				}
			}

			$newest_post_img = '';
			if( $userdata['session_logged_in'] )
			{
				if( $topic_rowset[$i]['post_time'] > $userdata['user_lastvisit'] ) 
				{
					if( !empty($tracking_topics) || !empty($tracking_forums) || isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
					{
						$unread_topics = true;

						if( !empty($tracking_topics[$topic_id]) )
						{
							if( $tracking_topics[$topic_id] >= $topic_rowset[$i]['post_time'] )
							{
								$unread_topics = false;
							}
						}

						if( !empty($tracking_forums[$forum_id]) )
						{
							if( $tracking_forums[$forum_id] >= $topic_rowset[$i]['post_time'] )
							{
								$unread_topics = false;
							}
						}

						if( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
						{
							if( $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all'] >= $topic_rowset[$i]['post_time'] )
							{
								$unread_topics = false;
							}
						}

						if( $unread_topics )
						{
							$folder_image = $folder_new;
							$folder_alt = $lang['New_posts'];

							$newest_post_img = '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;view=newest") . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ';
						}
						else
						{
							$folder_image = $folder;
							$folder_alt = ( $topic_rowset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];

							$newest_post_img = '';
						}
					}
					else
					{
						$folder_image = $folder_new;
						$folder_alt = ( $topic_rowset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['New_posts'];

						$newest_post_img = '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;view=newest") . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ';
					}
				}
				else 
				{
					$folder_image = $folder;
					$folder_alt = ( $topic_rowset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];

					$newest_post_img = '';
				}
			}
			else
			{
				$folder_image = $folder;
				$folder_alt = ( $topic_rowset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];

				$newest_post_img = '';
			}
		}

		if( ( $replies + 1 ) > $board_config['posts_per_page'] )
		{
			$total_pages = ceil( ( $replies + 1 ) / $board_config['posts_per_page'] );
			$goto_page = ' [ <img src="' . $images['icon_gotopost'] . '" alt="' . $lang['Goto_page'] . '" title="' . $lang['Goto_page'] . '" />' . $lang['Goto_page'] . ': ';

			$times = 1;
			for($j = 0; $j < $replies + 1; $j += $board_config['posts_per_page'])
			{
				$goto_page .= '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=" . $topic_id . "&amp;start=$j") . '" class="gensmall">' . $times . '</a>';
				if( $times == 1 && $total_pages > 4 )
				{
					$goto_page .= ' ... ';
					$times = $total_pages - 3;
					$j += ( $total_pages - 4 ) * $board_config['posts_per_page'];
				}
				else if ( $times < $total_pages )
				{
					$goto_page .= ', ';
				}
				$times++;
			}
			$goto_page .= ' ] ';
		}
		else
		{
			$goto_page = '';
		}
		
		$view_topic_url = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id");

		$topic_author = ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $topic_rowset[$i]['user_id']) . '" class="genmed">' : '';
		$topic_rowset[$i]['username'] = username_level_color($topic_rowset[$i]['username'], $topic_rowset[$i]['user_level'], $topic_rowset[$i]['user_id']);
	    $topic_author .= ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? $topic_rowset[$i]['username'] : ( ( $topic_rowset[$i]['post_username'] != '' ) ? $topic_rowset[$i]['post_username'] : $lang['Guest'] ); 
		$topic_author .= ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';

		$first_post_time = create_date($board_config['default_dateformat'], $topic_rowset[$i]['topic_time'], $board_config['board_timezone']);

		$last_post_time = create_date($board_config['default_dateformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone']);
		if ( $board_config['time_today'] < $topic_rowset[$i]['post_time'])
		{ 
			$last_post_time = sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone'])); 
		}
		else if ( $board_config['time_yesterday'] < $topic_rowset[$i]['post_time'])
		{ 
			$last_post_time = sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone'])); 
		}

		$topic_rowset[$i]['user2'] = username_level_color($topic_rowset[$i]['user2'], $topic_rowset[$i]['user_level2'], $topic_rowset[$i]['id2']);
		$last_post_author = ( $topic_rowset[$i]['id2'] == ANONYMOUS ) ? ( ($topic_rowset[$i]['post_username2'] != '' ) ? $topic_rowset[$i]['post_username2'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $topic_rowset[$i]['id2']) . '" class="gensmall">' . $topic_rowset[$i]['user2'] . '</a>'; 

		$last_post_url = '<a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $topic_rowset[$i]['topic_last_post_id']) . '&amp;no=1#' . $topic_rowset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

		$views = ( $board_config['enable_topic_view_users'] && $userdata['session_logged_in'] ) ? '<a href="javascript:who_viewed(' . $topic_id . ')">' . $topic_rowset[$i]['topic_views'] . '</a>' : $topic_rowset[$i]['topic_views'];
	
		$replies = '<a href="javascript:who(' . $topic_id . ')">' . $replies . '</a>';
		
		$ajaxed_mod = '';
		if ( $is_auth['auth_mod'] && !($topic_rowset[$i]['topic_status'] == TOPIC_MOVED) && $board_config['AJAXed_status'])
		{
			$ajaxed_delete_href 	= "modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=delete&amp;sid=" . $userdata['session_id'];
			$ajaxed_delete_onclick 	= ( $board_config['AJAXed_forum_delete'] ) ? ' onClick="dt(' . $topic_id . '); return false;"' : '';
			$ajaxed_move_href 		= "modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=move&amp;sid=" . $userdata['session_id'];
			$ajaxed_move_onclick = ( $board_config['AJAXed_forum_move'] ) ? ' onClick="mb(' . $topic_id . '); return false;"' : '';
			$ajaxed_mod .= ( $board_config['AJAXed_display_delete'] ) ? '<a' . $ajaxed_delete_onclick . ' href="' . $ajaxed_delete_href . '"><img src="' . $images['topic_mod_delete'] . '" alt="' . $lang['Delete_topic'] . '" title="' . $lang['Delete_topic'] . '" /></a>' : '';
			$ajaxed_mod .= ( $board_config['AJAXed_display_move'] ) ? '<a' . $ajaxed_move_onclick . ' href="' . $ajaxed_move_href . '"><img src="' . $images['topic_mod_move'] . '" alt="' . $lang['Move_topic'] . '" title="' . $lang['Move_topic'] . '" /></a>' : '';
		}
		
		//
		// Display picture alert
		//
		$picture_alert = '';
		if ($forum_row['display_pic_alert'])
		{
			$text_sql = "SELECT pt.post_text, pt.bbcode_uid
				FROM " . POSTS_TEXT_TABLE . " pt, " . POSTS_TABLE . " p
				WHERE pt.post_id = p.post_id
				AND p.topic_id = $topic_id";
			if( !$text_result = $db->sql_query($text_sql) )
			{
				message_die(GENERAL_ERROR, 'Could not retrieve posts information', '', __LINE__, __FILE__, $text_sql);
			}

			while( $text_row = $db->sql_fetchrow($text_result) )
			{
				$text_uid = $text_row['bbcode_uid'];
				preg_match_all("#\[img:$text_uid\](.*?)\[/img:$text_uid\]#si", $text_row['post_text'], $matches);
				$total_matches = sizeof($matches[0]);
	
				$picture_alert = ( $total_matches == 1 ) ? $lang['Picture_alert'] : (( $total_matches > 1 ) ? sprintf($lang['Pictures_alert'], $total_matches) : '' );
			}
			$db->sql_freeresult($text_result);
		}

		$template->assign_block_vars('topicrow', array(
			'ICON' => $icon, 
			'ANSWERED' => $answered_icon, 
			'FORUM_ID' => $forum_id,
			'TOPIC_ID' => $topic_id,
			'TOPIC_FOLDER_IMG' => $folder_image, 
			'TOPIC_AUTHOR' => $topic_author, 
			'GOTO_PAGE' => $goto_page,
			'REPLIES' => $replies,
			'NEWEST_POST_IMG' => $newest_post_img, 
			'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($topic_rowset[$i]['topic_attachment']),
			'TOPIC_TITLE' => $topic_title . $picture_alert,
			'TOPIC_CONTENT' => $topic_content,
			'TOPIC_TYPE' => $topic_type,
			'VIEWS' => $views,
			'FIRST_POST_TIME' => $first_post_time, 
			'LAST_POST_TIME' => $last_post_time, 
			'LAST_POST_AUTHOR' => $last_post_author, 
			'LAST_POST_IMG' => $last_post_url, 
			'RATING' => $topic_rating,
			'ANSWER_STATUS' => $answer_image,
			'ANSWER_EXPLAIN' => $answer_explain,
			'AMAZON_LINK' => $amazon_text,

			'LOCK_UNLOCK_JS' => ( $board_config['AJAXed_forum_lock'] && $is_auth['auth_mod'] && $board_config['AJAXed_status'] ) ? ' onClick="lt(' . $topic_id . ',0);"' : '',
			'AJAXED_MOD' => $ajaxed_mod,

			'L_TOPIC_FOLDER_ALT' => $folder_alt, 

			'U_VIEW_TOPIC' => $view_topic_url)
		);

		// Split topic types
		$topic_real_type = $topic_rowset[$i]['topic_type'];

		// if no split between global and standard announcement, group them with standard announcement
		if ( (!$board_config['split_global_announce']) && ($topic_real_type == POST_GLOBAL_ANNOUNCE) ) 
		{
			$topic_real_type = POST_ANNOUNCE; 
		}
		// if no split between announce and sticky, group them with sticky
		if ( (!$board_config['split_announce']) && ($topic_real_type == POST_ANNOUNCE) ) 
		{
			$topic_real_type = POST_STICKY; 
		}
		
		// if no split between sticky and normal, group them with normal
		if ( (!$board_config['split_sticky']) && ($topic_real_type == POST_STICKY) ) 
		{
			$topic_real_type = POST_NORMAL;
		}
		
		// check prec type
		$is_rupt = false;
		if ($prec_topic_real_type != $topic_real_type)
		{
			if ($prec_topic_real_type == -1) 
			{
				$is_rupt = ( $topic_real_type != POST_NORMAL );
			}
			if ($prec_topic_real_type != -1) 
			{
				$is_rupt = true;
			}
		}
		
		// send to screen
		if ( $is_rupt )
		{
			$title = '';
			switch ($topic_real_type)
			{
				case POST_GLOBAL_ANNOUNCE:
					$title = $lang['Post_global_announcement'];
					break;
				case POST_ANNOUNCE:
					$title = $lang['Post_Announcement'];
					break;
				case POST_STICKY:
					$title = $lang['Post_Sticky'];
					break;
				case POST_NORMAL:
					$title = $lang['Topics'];
					break;
				default:
					$title = '';
					break;
			}

			$template->assign_block_vars('topicrow.topictype', array(
				'TITLE' => $title)
			);
		}
		$prec_topic_real_type = $topic_real_type;
	}

	$topics_count = ( $topics_count == 0 ) ? 1 : $topics_count;

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("index.$phpEx?" . POST_HIERARCHIE_URL . "=" . ($hierarchie_level) . "&amp;" . POST_PARENTFORUM_URL . "=$forum_id&amp;topicdays=$topic_days", $topics_count, $board_config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $topics_count / $board_config['topics_per_page'] )), 

		'L_GOTO_PAGE' => $lang['Goto_page'])
	);
}
else
{
	//
	// No topics
	//
	$no_topics_msg = ( $forum_row['forum_status'] == FORUM_LOCKED ) ? $lang['Forum_locked'] : $lang['No_topics_post_one'];
	$template->assign_vars(array(
		'L_NO_TOPICS' => $no_topics_msg)
	);

	$template->assign_block_vars('switch_no_topics', array() );

}

// Search Box
if ($board_config['search_forum'])
{	
	$template->assign_block_vars('switch_search_forum', array());
}

?>
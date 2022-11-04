<?php
/** 
*
* @package includes
* @version $Id: functions_move.php,v 1.133.2.34 2005/02/21 18:37:33 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
   die("Hacking attempt");
}

require($phpbb_root_path . 'includes/functions_search.'.$phpEx);

function move($forum_id, $move_date, $destination_id)
{
	global $db, $lang;

	$sql_topics = '';
	$new_forum_id = $destination_id;
	$old_forum_id = $forum_id;

	//
	// Those without polls ...
	//
	$sql = "SELECT t.topic_id 
		FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t
		WHERE t.forum_id = $forum_id
			AND t.topic_vote = 0 
			AND t.topic_type <> " . POST_GLOBAL_ANNOUNCE . "
			AND t.topic_type <> " . POST_ANNOUNCE . " 
			AND t.topic_type <> " . POST_STICKY . " 
			AND ( p.post_id = t.topic_last_post_id 
				OR t.topic_last_post_id = 0 )";
	if ( $move_date != '' )
	{
		$sql .= " AND p.post_time < $move_date";
	}

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain lists of topics to move', '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql_topics .= ( ( $sql_topics != '' ) ? ', ' : '' ) . $row['topic_id'];
	}
		
	if( $sql_topics != '' )
	{
		$sql = "SELECT post_id
			FROM " . POSTS_TABLE . " 
			WHERE forum_id = $forum_id 
				AND topic_id IN ($sql_topics)";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain list of posts to move', '', __LINE__, __FILE__, $sql);
		}

		$sql_post = '';
		while ( $row = $db->sql_fetchrow($result) )
		{
			$sql_post .= ( ( $sql_post != '' ) ? ', ' : '' ) . $row['post_id'];
		}

		if ( $sql_post != '' )
		{
			$sql = "UPDATE " . TOPICS_TABLE . " 
				SET forum_id = $new_forum_id 
				WHERE topic_id IN ($sql_topics)";
			if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
			{
				message_die(GENERAL_ERROR, 'Could not move topics during move', '', __LINE__, __FILE__, $sql);
			}

			$moved_topics = $db->sql_affectedrows();

			$sql = "UPDATE " . POSTS_TABLE . " 
				SET forum_id = $new_forum_id 
				WHERE post_id IN ($sql_post)";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not move post_text during move', '', __LINE__, __FILE__, $sql);
			}

			$moved_posts = $db->sql_affectedrows();

			return array('topics' => $moved_topics, 'posts' => $moved_posts);
		}
	}

	return array('topics' => 0, 'posts' => 0);
}

function auto_move($forum_id = 0)
{
	global $db, $lang;

	$sql = "SELECT *
		FROM " . MOVE_TABLE . "
		WHERE forum_id = $forum_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not read auto_move table', '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		if ( $row['move_freq'] && $row['move_days'] && $row['forum_dest'] )
		{
			$move_date = time() - ( $row['move_days'] * 86400 );
			$next_move = time() + ( $row['move_freq'] * 86400 );
			$forum_dest = $row['forum_dest'];

			move($forum_id, $move_date, $forum_dest);
			sync('forum', $forum_id);
			sync('forum', $forum_dest);

			$sql = "UPDATE " . FORUMS_TABLE . " 
				SET move_next = $next_move 
				WHERE forum_id = $forum_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update forum table', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	return;
}

function send_moved_topic_pm($user_from_id, $user_to_id, $pm_subject, $pm_message, $send_email)
{
	global $board_config, $lang, $db, $phpbb_root_path, $phpEx;

	$sql = "SELECT *
		FROM " . USERS_TABLE . " 
		WHERE user_id = " . $user_to_id . "
		AND user_id <> " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, $lang['non_existing_user'], '', __LINE__, __FILE__, $sql);
	}
	$usertodata = $db->sql_fetchrow($result);

	// prepare pm message
	$bbcode_uid = make_bbcode_uid();

	if(empty($pm_message))
	{
		$pm_message = $lang['No_entry_pm'];
	}
	$pm_message = prepare_message(trim($pm_message), 0, 1, 1, $bbcode_uid);

	$msg_time = time();

	//
	// See if recipient is at their inbox limit
	//
	$sql = "SELECT COUNT(privmsgs_id) AS inbox_items, MIN(privmsgs_date) AS oldest_post_time 
		FROM " . PRIVMSGS_TABLE . " 
		WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
			OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "  
			OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " 
			OR privmsgs_type = " . PRIVMSGS_REPLY_MAIL . ") 
			AND privmsgs_to_userid = " . $usertodata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_such_user']);
	}

	$sql_priority = ( SQL_LAYER == 'mysql' ) ? 'LOW_PRIORITY' : '';

	if ( $inbox_info = $db->sql_fetchrow($result) )
	{
		if ( $inbox_info['inbox_items'] >= $board_config['max_inbox_privmsgs'] )
		{
			$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . " 
				WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
					OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " 
					OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " 
					OR privmsgs_type = " . PRIVMSGS_REPLY_MAIL . ") 
					AND privmsgs_date = " . $inbox_info['oldest_post_time'] . " 
					AND privmsgs_to_userid = " . $to_userdata['user_id'];
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not find oldest privmsgs (inbox)', '', __LINE__, __FILE__, $sql);
			}
			$old_privmsgs_id = $db->sql_fetchrow($result);
			$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];
			
			$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TABLE . " 
				WHERE privmsgs_id = $old_privmsgs_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs (inbox)'.$sql, '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TEXT_TABLE . " 
				WHERE privmsgs_text_id = $old_privmsgs_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs text (inbox)', '', __LINE__, __FILE__, $sql);
			}		
		}
	}

	$sql_info = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig)
		VALUES (" . PRIVMSGS_NEW_MAIL . ", '" . str_replace("\'", "''", $pm_subject) . "', " . $user_from_id . ", " . $usertodata['user_id'] . ", $msg_time, '$user_ip', 0, 1, 1, 1)";
	if ( !($result = $db->sql_query($sql_info, BEGIN_TRANSACTION)) )
	{
		message_die(GENERAL_ERROR, $lang['no_sent_pm_insert'], "", __LINE__, __FILE__, $sql_info);
	}

	$privmsg_sent_id = $db->sql_nextid();

	$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text)
		VALUES ($privmsg_sent_id, '" . $bbcode_uid . "', '" . str_replace("\'", "''", $pm_message) . "')";
	if ( !$db->sql_query($sql, END_TRANSACTION) )
	{
		message_die(GENERAL_ERROR, $lang['no_sent_pm_insert'], "", __LINE__, __FILE__, $sql_info);
	}

	// Add to the users new pm counter
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . "
		WHERE user_id = " . $usertodata['user_id']; 
	if ( !$status = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, $lang['no_sent_pm_insert'], '', __LINE__, __FILE__, $sql);
	}

	if ( $send_email && $usertodata['user_notify_pm'] && !empty($usertodata['user_email']) && $usertodata['user_active'] )
	{
		$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
		$script_name = ( $script_name != '' ) ? $script_name . '/privmsg.'.$phpEx : 'privmsg.'.$phpEx;
		$server_name = trim($board_config['server_name']);
		$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
		$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

		include_once($phpbb_root_path . 'includes/emailer.'.$phpEx);

		$emailer = new emailer($board_config['smtp_delivery']);

		$emailer->from($board_config['board_email']);
		$emailer->replyto($board_config['board_email']);
		
		$emailer->use_template('privmsg_notify', $usertodata['user_lang']);
		$emailer->email_address($usertodata['user_email']);
		$emailer->set_subject($lang['Notification_subject']);
			
		$emailer->assign_vars(array(
			'PM_USERNAME' => $usertodata['username'], 
			'SITENAME' => $board_config['sitename'],
			'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '', 

			'U_INBOX' => $server_protocol . $server_name . $server_port . $script_name . '?folder=inbox')
		);

		$emailer->send();
		$emailer->reset();
	}

	return;
}

?>
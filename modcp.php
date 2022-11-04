<?php
/** 
*
* @package phpBB2
* @version $Id: modcp.php,v 1.71.2.21 2005/11/27 11:41:35 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* Moderator Control Panel
*
* From this 'Control Panel' the moderator of a forum will be able to do
* mass topic operations (locking/unlocking/moving/deleteing), and it will
* provide an interface to do quick locking/unlocking/moving/deleting of
* topics via the moderator operations buttons on all of the viewtopic pages.
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
include($phpbb_root_path . 'mods/calendar/mycalendar_functions.'.$phpEx);

//
// Obtain initial var settings
//
if ( isset($HTTP_GET_VARS[POST_FORUM_URL]) || isset($HTTP_POST_VARS[POST_FORUM_URL]) )
{
	$forum_id = (isset($HTTP_POST_VARS[POST_FORUM_URL])) ? intval($HTTP_POST_VARS[POST_FORUM_URL]) : intval($HTTP_GET_VARS[POST_FORUM_URL]);
}
else
{
	$forum_id = '';
}

if ( isset($HTTP_GET_VARS[POST_POST_URL]) || isset($HTTP_POST_VARS[POST_POST_URL]) )
{
	$post_id = (isset($HTTP_POST_VARS[POST_POST_URL])) ? intval($HTTP_POST_VARS[POST_POST_URL]) : intval($HTTP_GET_VARS[POST_POST_URL]);
}
else
{
	$post_id = '';
}

if ( isset($HTTP_GET_VARS[POST_TOPIC_URL]) || isset($HTTP_POST_VARS[POST_TOPIC_URL]) )
{
	$topic_id = (isset($HTTP_POST_VARS[POST_TOPIC_URL])) ? intval($HTTP_POST_VARS[POST_TOPIC_URL]) : intval($HTTP_GET_VARS[POST_TOPIC_URL]);
}
else
{
	$topic_id = '';
}

$confirm = ( $HTTP_POST_VARS['confirm'] ) ? TRUE : 0;
$confirm_recycle = TRUE;

//
// Continue var definitions
//
$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$delete = ( isset($HTTP_POST_VARS['delete']) ) ? TRUE : FALSE;
$move = ( isset($HTTP_POST_VARS['move']) ) ? TRUE : FALSE;
$lock = ( isset($HTTP_POST_VARS['lock']) ) ? TRUE : FALSE;
$unlock = ( isset($HTTP_POST_VARS['unlock']) ) ? TRUE : FALSE;
$recycle = ( isset($HTTP_POST_VARS['recycle']) ) ? TRUE : FALSE;
$quick_title_edit = ( isset($HTTP_POST_VARS['quick_title_edit']) ) ? TRUE : FALSE;
$qtnum = ( isset($HTTP_POST_VARS['qtnum']) ) ? intval($HTTP_POST_VARS['qtnum']) : 0;
$cement = ( isset($HTTP_POST_VARS['cement']) ) ? TRUE : FALSE;
$mergetopic = ( isset($HTTP_POST_VARS['mergetopic']) ) ? TRUE : FALSE;
$mergepost = ( isset($HTTP_POST_VARS['mergepost']) ) ? TRUE : FALSE;

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	if ( $delete )
	{
		$mode = 'delete';
	}
	else if ( $move )
	{
		$mode = 'move';
	}
	else if ( $lock )
	{
		$mode = 'lock';
	}
	else if ( $unlock )
	{
		$mode = 'unlock';
	}
	else if ( $recycle )
	{
		$mode = 'recycle';
	}
	else if ( $quick_title_edit )
	{
		$mode = 'quick_title_edit';
	}
	else if ( isset($HTTP_POST_VARS['link']) )
	{
		$mode = 'link';
	}
	else if ( $cement )
	{
		$mode = 'cement';
	}
	else if ( $mergetopic )
	{
		$mode = 'mergetopic';
	}
	else if ( $mergepost )
	{
		$mode = 'mergepost';
	}
	else if ( $ip)
	{
		$mode = 'ip';
	}
	else
	{
		$mode = '';
	}
}

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}

//
// Obtain relevant data
//
if ( !empty($topic_id) )
{
	$sql = "SELECT f.forum_id, f.forum_name, f.forum_topics, f.index_posts, t.topic_poster
		FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
		WHERE t.topic_id = " . $topic_id . "
			AND f.forum_id = t.forum_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
	}
	$topic_row = $db->sql_fetchrow($result);

	if (!$topic_row)
	{
		message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
	}

	$forum_topics = ( $topic_row['forum_topics'] == 0 ) ? 1 : $topic_row['forum_topics'];
	$forum_id = $topic_row['forum_id'];
	$forum_name = $topic_row['forum_name'];
	$index_posts = $topic_row['index_posts'];
}
else if ( !empty($forum_id) )
{
	$sql = "SELECT forum_name, forum_topics, index_posts
		FROM " . FORUMS_TABLE . "
		WHERE forum_id = " . $forum_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_MESSAGE, 'Forum_not_exist');
	}
	$topic_row = $db->sql_fetchrow($result);

	if (!$topic_row)
	{
		message_die(GENERAL_MESSAGE, 'Forum_not_exist');
	}

	$forum_topics = ( $topic_row['forum_topics'] == 0 ) ? 1 : $topic_row['forum_topics'];
	$forum_name = $topic_row['forum_name'];
	$index_posts = $topic_row['index_posts'];
}
else
{
	message_die(GENERAL_MESSAGE, 'Forum_not_exist');
}

//
// Start session management
//
setup_forum_style($forum_id);
$userdata = session_pagestart($user_ip, $forum_id);
init_userprefs($userdata);
//
// End session management
//

// session id check
if ($sid == '' || $sid != $userdata['session_id'])
{
	message_die(GENERAL_ERROR, 'Invalid_session');
}

//
// Check if user did or did not confirm
// If they did not, forward them to the last page they were on
//
if ( isset($HTTP_POST_VARS['cancel']) )
{
	if ( $topic_id )
	{
		$redirect = "viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id";
	}
	else if ( $forum_id )
	{
		$redirect = "viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id";
	}
	else
	{
		$redirect = "index.$phpEx";
	}

	redirect(append_sid($redirect, true));
}

//
// Start auth check
//
if ( !($mode == 'quick_title_edit') ) 
{
	$is_auth = auth(AUTH_ALL, $forum_id, $userdata);
	
	if ( !$is_auth['auth_mod'] )
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Moderator'], $lang['Not_Authorised']);
	}
}
else
{
	if ( $qtnum > -1 )
	{
		$sql_qt = "SELECT * 
			FROM " . TITLE_INFOS_TABLE . " 
			WHERE id = $qtnum";
		if ( !($result_qt = $db->sql_query($sql_qt)) )
		{
			message_die(GENERAL_MESSAGE, 'Quick Topic Title does not exist');
		}
		$qt_row = $db->sql_fetchrow($result_qt);
		
		if (($userdata['user_level'] == 1 & $qt_row['admin_auth'] == 0) || ($userdata['user_level'] == 2 & $qt_row['mod_auth'] == 0) || ($userdata['user_level'] == 3 & $qt_row['mod_auth'] == 0) || ($userdata['user_level'] == 0 & $qt_row['poster_auth'] == 0) || ($userdata['user_level'] == 0 & $qt_row['poster_auth'] == 1 & $userdata['user_id'] != $topic_row['topic_poster'] ))
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
		}
	}
	else
	{
		if ($userdata['user_level'] == 0 & $userdata['user_id'] != $topic_row['topic_poster'] )
		{	
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
		}
		$qt_row = array('title_info' => '');
	}
}
//
// End Auth Check
//

if (($mode == 'ip') && ($userdata['user_level'] != ADMIN) && (!$board_config['mods_viewips'])) 
{ 
	$mode = '';    
} 

//
// Do major work ...
//
switch( $mode )
{
	case 'delete':
		if (!$is_auth['auth_delete'])
		{
			message_die(GENERAL_MESSAGE, sprintf($lang['Sorry_auth_delete'], $is_auth['auth_delete_type']));
		}

        // Stop ADMIN posts from being deleted
        if ( $userdata['user_level'] != ADMIN )
      	{
        	$topics_sql = ( isset($HTTP_POST_VARS['topic_id_list']) ) ? implode(',', $HTTP_POST_VARS['topic_id_list']) : $topic_id;
        	$sql = "SELECT t.topic_id
            	FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u
               	WHERE u.user_id = t.topic_poster
                	AND u.user_level = " . ADMIN . "
                	AND t.topic_id IN ($topics_sql)";
			if ( !($result = $db->sql_query($sql)) )
         	{
            	message_die(GENERAL_ERROR, 'Could not retrieve topics list', '', __LINE__, __FILE__, $sql);
         	}

        	if( $db->sql_numrows($result) > 0 )
        	{
            	message_die(GENERAL_MESSAGE, $lang['Not_edit_delete_admin']);
         	}
      	} 

		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
	
		if ( $confirm )
		{
  			if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}
			
			include($phpbb_root_path . 'includes/functions_search.'.$phpEx);

			$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

			$topic_id_sql = '';
			for($i = 0; $i < sizeof($topics); $i++)
			{
				$topic_id_sql .= ( ( $topic_id_sql != '' ) ? ', ' : '' ) . intval($topics[$i]);
			}

			$sql = "SELECT topic_id 
				FROM " . TOPICS_TABLE . "
				WHERE topic_id IN ($topic_id_sql)
					AND forum_id = $forum_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get topic id information', '', __LINE__, __FILE__, $sql);
			}
			
			$topic_id_sql = '';
			while ($row = $db->sql_fetchrow($result))
			{
				$topic_id_sql .= (($topic_id_sql != '') ? ', ' : '') . intval($row['topic_id']);
			}
			$db->sql_freeresult($result);

			if ( $topic_id_sql == '')
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}
			
			//
			// Check for password-protected topics
			//
			if( $userdata['user_level'] != ADMIN )
			{
				$sql = "SELECT topic_id 
					FROM " . TOPICS_TABLE . " 
					WHERE topic_id IN ($topic_id_sql) 
						AND topic_password = ''";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain topic list', '', __LINE__, __FILE__, $sql);
				}

				$topic_id_sql = '';
				while( $row = $db->sql_fetchrow($result) )
				{
					$topic_id_sql .= ( ( $topic_id_sql != '' ) ? ', ' : '' ) . $row['topic_id'];
				}
			}

			if( $topic_id_sql == '' )
			{
				message_die(GENERAL_MESSAGE, $lang['Not_delete_password_topics']);
			}
			else
			{
				$sql = "SELECT poster_id, COUNT(post_id) AS posts 
					FROM " . POSTS_TABLE . " 
					WHERE topic_id IN ($topic_id_sql) 
					GROUP BY poster_id";
			}
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get poster id information', '', __LINE__, __FILE__, $sql);
			}

			$count_sql = array();
			while ( $row = $db->sql_fetchrow($result) )
			{
				$count_sql[] = "UPDATE " . USERS_TABLE . " 
					SET user_posts = user_posts - " . $row['posts'] . " 
					WHERE user_id = " . $row['poster_id'];
			}
			$db->sql_freeresult($result);

			if ( sizeof($count_sql) )
			{
				for($i = 0; $i < sizeof($count_sql); $i++)
				{
					if ( !$db->sql_query($count_sql[$i]) )
					{
						message_die(GENERAL_ERROR, 'Could not update user post count information', '', __LINE__, __FILE__, $sql);
					}
				}
			}
			
			$sql = "SELECT post_id 
				FROM " . POSTS_TABLE . " 
				WHERE topic_id IN ($topic_id_sql)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get post id information', '', __LINE__, __FILE__, $sql);
			}

			$post_id_sql = '';
			while ( $row = $db->sql_fetchrow($result) )
			{
				$post_id_sql .= ( ( $post_id_sql != '' ) ? ', ' : '' ) . intval($row['post_id']);
			}
			$db->sql_freeresult($result);

			$sql = "SELECT vote_id 
				FROM " . VOTE_DESC_TABLE . " 
				WHERE topic_id IN ($topic_id_sql)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get vote id information', '', __LINE__, __FILE__, $sql);
			}

			$vote_id_sql = '';
			while ( $row = $db->sql_fetchrow($result) )
			{
				$vote_id_sql .= ( ( $vote_id_sql != '' ) ? ', ' : '' ) . $row['vote_id'];
			}
			$db->sql_freeresult($result);

			//
			// Got all required info so go ahead and start deleting everything
			//
	        mycal_delete_event($topic_id_sql, null, false);

			$sql = "DELETE 
				FROM " . TOPICS_TABLE . " 
				WHERE topic_id IN ($topic_id_sql) 
					OR topic_moved_id IN ($topic_id_sql)";
			if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
			{
				message_die(GENERAL_ERROR, 'Could not delete topics', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE 
				FROM " . THANKS_TABLE . "
				WHERE topic_id IN ($topic_id_sql)";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete topic thanks', '', __LINE__, __FILE__, $sql);
			}
			
			if ( $post_id_sql != '' )
			{
				$sql = "DELETE 
					FROM " . POSTS_TABLE . " 
					WHERE post_id IN ($post_id_sql)";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete posts', '', __LINE__, __FILE__, $sql);
				}
				
				if ($board_config['enable_mod_logger'])
				{
					for($i = 0; $i < sizeof($topics); $i++)
					{
						log_action('delete', intval($topics[$i]), $userdata['user_id'], $userdata['username']);
					}
				}
				
				$sql = "DELETE 
					FROM " . POSTS_TEXT_TABLE . " 
					WHERE post_id IN ($post_id_sql)";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete posts text', '', __LINE__, __FILE__, $sql);
				}

				if ($index_posts)
				{
					remove_search_post($post_id_sql);
				}
				delete_attachment(explode(', ', $post_id_sql));
			}

			if ( $vote_id_sql != '' )
			{
				$sql = "DELETE 
					FROM " . VOTE_DESC_TABLE . " 
					WHERE vote_id IN ($vote_id_sql)";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete vote descriptions', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE 
					FROM " . VOTE_RESULTS_TABLE . " 
					WHERE vote_id IN ($vote_id_sql)";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete vote results', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE 
					FROM " . VOTE_USERS_TABLE . " 
					WHERE vote_id IN ($vote_id_sql)";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete vote users', '', __LINE__, __FILE__, $sql);
				}
			}

			$sql = "DELETE 
				FROM " . TOPICS_WATCH_TABLE . " 
				WHERE topic_id IN ($topic_id_sql)";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete watched post list', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE 
				FROM " . TOPICS_VIEWDATA_TABLE . " 
				WHERE topic_id IN ($topic_id_sql)"; 
			if ( !$db->sql_query($sql) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not delete topic views list', '', __LINE__, __FILE__, $sql); 
			} 

			$sql = "DELETE
				FROM " . THREAD_KICKER_TABLE . "
				WHERE topic_id IN ($topic_id_sql)";
			if ( !$db->sql_query($sql) ) 
			{
				message_die(GENERAL_ERROR, 'Could not delete topic kicker data', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE
				FROM " . SUBSCRIPTIONS_LIST_TABLE  . "
				WHERE forum = 0
					AND thread IN ($topic_id_sql)";
			if ( !$db->sql_query($sql, END_TRANSACTION) )
			{
				message_die(GENERAL_ERROR, 'Could not delete usercp topic subscriptions', '', __LINE__, __FILE__, $sql);
			}

			sync('forum', $forum_id);

			if ( !empty($topic_id) )
			{
				$redirect_page = "viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'];
				$l_redirect = sprintf($lang['Click_return_forum'], '<a href="' . $redirect_page . '">', '</a>');
			}
			else
			{
				$redirect_page = "modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'];
				$l_redirect = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
			}

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
			);

			message_die(GENERAL_MESSAGE, $lang['Topics_Removed'] . '<br /><br />' . $l_redirect);
		}
		else
		{
			// Not confirmed, show confirmation message
			if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';

			if ( isset($HTTP_POST_VARS['topic_id_list']) )
			{
				$topics = $HTTP_POST_VARS['topic_id_list'];
				for($i = 0; $i < sizeof($topics); $i++)
				{
					$hidden_fields .= '<input type="hidden" name="topic_id_list[]" value="' . intval($topics[$i]) . '" />';
				}
			}
			else
			{
				$hidden_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
			}

			//
			// Set template files
			//
			$template->set_filenames(array(
				'confirm' => 'confirm_body.tpl')
			);

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_delete_topic'],

				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_CONFIRM_ACTION' => append_sid("modcp.$phpEx"),
				'S_HIDDEN_FIELDS' => $hidden_fields)
			);

			$template->pparse('confirm');

			include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		}
		break;

	case 'move':
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		if ( $confirm )
		{
			if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			include($phpbb_root_path . 'includes/functions_search.'.$phpEx);
			$post_id_sql = '';

			$new_forum_id = intval($HTTP_POST_VARS['new_forum']);
			$old_forum_id = $forum_id;

			$sql = 'SELECT forum_id, index_posts
				FROM ' . FORUMS_TABLE . '
				WHERE forum_id = ' . $new_forum_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select from forums table', '', __LINE__, __FILE__, $sql);
			}
			$forum_row = $db->sql_fetchrow($result);
		
			if (!$forum_row)
			{
				message_die(GENERAL_MESSAGE, 'New forum does not exist');
			}

			$index_posts = $forum_row['index_posts'];

			$db->sql_freeresult($result);

			$sql = 'SELECT index_posts 
				FROM ' . FORUMS_TABLE . '
				WHERE forum_id = ' . $old_forum_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select from forums table', '', __LINE__, __FILE__, $sql);
			}

			$forum_row = $db->sql_fetchrow($result);
			$index_post = $forum_row['index_posts'];

			$db->sql_freeresult($result);

			if ( $new_forum_id != $old_forum_id )
			{
				$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

				$topic_list = '';
				for($i = 0; $i < sizeof($topics); $i++)
				{
					$topic_list .= ( ( $topic_list != '' ) ? ', ' : '' ) . intval($topics[$i]);
				}

				$sql = "SELECT * 
					FROM " . TOPICS_TABLE . " 
					WHERE topic_id IN ($topic_list) 
						AND forum_id = $old_forum_id
						AND topic_status NOT IN (" . TOPIC_MOVED . ", " . TOPIC_LINKED . ")";
				if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
				{
					message_die(GENERAL_ERROR, 'Could not select from topic table', '', __LINE__, __FILE__, $sql);
				}

				$row = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);

				mycal_move_event($new_forum_id, $topic_list, isset($HTTP_POST_VARS['move_leave_shadow']));

				for($i = 0; $i < sizeof($row); $i++)
				{
					$topic_id = intval($row[$i]['topic_id']);
					
					if ( isset($HTTP_POST_VARS['move_leave_shadow']) )
					{
						// Insert topic in the old forum that indicates that the forum has moved.
						$sql = "INSERT INTO " . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_status, topic_type, topic_vote, topic_views, topic_replies, topic_first_post_id, topic_last_post_id, topic_moved_id)
							VALUES ($old_forum_id, '" . addslashes(str_replace("\'", "''", $row[$i]['topic_title'])) . "', '" . str_replace("\'", "''", $row[$i]['topic_poster']) . "', " . $row[$i]['topic_time'] . ", " . TOPIC_MOVED . ", " . POST_NORMAL . ", " . $row[$i]['topic_vote'] . ", " . $row[$i]['topic_views'] . ", " . $row[$i]['topic_replies'] . ", " . $row[$i]['topic_first_post_id'] . ", " . $row[$i]['topic_last_post_id'] . ", $topic_id)";
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'Could not insert shadow topic', '', __LINE__, __FILE__, $sql);
						}
					}

					if ($board_config['enable_mod_logger'])
					{
						log_action('move', $topic_id, $userdata['user_id'], $userdata['username']);
					}
					
					while ( $row2 = $db->sql_fetchrow($result) )
					{
						$post_id_sql .= ( ( $post_id_sql != '' ) ? ', ' : '' ) . intval($row2['post_id']);
						if ((!$index_post) && (index_posts))
						{
							$sql = "SELECT post_id, post_subject, post_text  
								FROM " . POSTS_TEXT_TABLE . "
								WHERE post_id = " . $row2['post_id'];
							if ( !($result2 = $db->sql_query($sql)) )
							{
								message_die(GENERAL_ERROR, 'Could not get post information', '', __LINE__, __FILE__, $sql);
							}
							
							while ( $row3 = $db->sql_fetchrow($result2) )
							{
								 add_search_words('single', $row3['post_id'], $row3['post_text'], $row3['post_subject']);
							}
							$db->sql_freeresult($result2);
						}
					}
					$db->sql_freeresult($result);

					$sql = "UPDATE " . TOPICS_TABLE . " 
						SET forum_id = $new_forum_id  
						WHERE topic_id = $topic_id";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not update old topic', '', __LINE__, __FILE__, $sql);
					}

					$sql = "UPDATE " . POSTS_TABLE . " 
						SET forum_id = $new_forum_id 
						WHERE topic_id = $topic_id";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not update post topic ids', '', __LINE__, __FILE__, $sql);
					}
					
					if ( $lock )
					{
						$sql = "UPDATE " . TOPICS_TABLE . "
							SET topic_status = " . TOPIC_LOCKED . "
							WHERE topic_id = $topic_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'Could not update topic status', '', __LINE__, __FILE__, $sql);
						}
					}
					else
					{
						$sql = "UPDATE " . TOPICS_TABLE . "
							SET topic_status = " . TOPIC_UNLOCKED . "
							WHERE topic_id = $topic_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'Could not update topic status', '', __LINE__, __FILE__, $sql);
						}
					}
				}

				if (!$index_posts)
				{
					remove_search_post($post_id_sql);
				}

				// Topic Moved Mailer
				if (($userdata['user_topic_moved_mail'] == 1) || ($userdata['user_topic_moved_pm'] == 1))
				{
					include($phpbb_root_path . 'includes/functions_move.'.$phpEx);
					
					// old forumname
					$sql = "SELECT f.forum_name, c.cat_title
						FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c 
						WHERE f.forum_id = $old_forum_id
						AND c.cat_id = f.cat_id";
					if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
					{
						message_die(GENERAL_ERROR, 'Could not select from forums or catagory table', '', __LINE__, __FILE__, $sql);
					}
					if ($oldnamerow = $db->sql_fetchrow($result))
					{
						$oldcatname = $oldnamerow['cat_title'];
						$oldforumname = $oldnamerow['forum_name'];
					}
					$db->sql_freeresult($result);		

					// new forumname
					$sql = "SELECT f.forum_name, c.cat_title
						FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c 
						WHERE f.forum_id = $new_forum_id
						AND c.cat_id = f.cat_id";
					if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
					{
						message_die(GENERAL_ERROR, 'Could not select from forums or catagory table', '', __LINE__, __FILE__, $sql);
					}
					if ($newnamerow = $db->sql_fetchrow($result))
					{
						$newcatname = $newnamerow['cat_title'];
						$newforumname = $newnamerow['forum_name'];
					}
					$db->sql_freeresult($result);

					// topictitle, user_id, username, useremail
					$sql = "SELECT t.topic_id, t.topic_title, u.user_id, u.username, u.user_email, u.user_lang, u.user_topic_moved_pm_notify 
						FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u 
						WHERE t.topic_id IN ($topic_list)
							AND t.topic_poster = u.user_id";
					if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
					{
						message_die(GENERAL_ERROR, 'Could not select from topic or users table', '', __LINE__, __FILE__, $sql);
					}
					$mailrow = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);

					$script_path = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
					$server_name = trim($board_config['server_name']);
					$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
					$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';
					
					if ($userdata['user_topic_moved_mail'] == 1)
					{
						for($i = 0; $i < sizeof($mailrow); $i++)
						{
							//emailer
							include_once($phpbb_root_path . 'includes/emailer.'.$phpEx);
							$emailer = new emailer($board_config['smtp_delivery']);
							$emailer->from($board_config['board_email']);
							$emailer->replyto($board_config['board_email']);
							$emailer->use_template('topic_moved', $mailrow[$i]['user_lang']);
							$emailer->email_address($mailrow[$i]['user_email']);
							$emailer->set_subject($lang['topic_moved']);
							
							$emailer->assign_vars(array(
								'SUBJECT' => $lang['topic_moved'],
								'SITENAME' => $board_config['sitename'], 
								'BOARD_URL' => $server_protocol . $server_name . $server_port . $script_path,
								'TOPICPOSTER' => $mailrow[$i]['username'], 
								'TOPICTITLE' => $mailrow[$i]['topic_title'],
								'OLD_CATAGORIE_NAME' => $oldcatname, 
								'OLD_FORUM_NAME' => $oldforumname,
								'NEW_CATAGORIE_NAME' => $newcatname, 
								'NEW_FORUM_NAME' => $newforumname,
								
								'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',
								
								'MOVED_URL' => $server_protocol . $server_name . $server_port . $script_path . 'viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $mailrow[$i]['topic_id'],
								'PROFILE_LINK' => $server_protocol . $server_name . $server_port . $script_path . 'profile.'.$phpEx.'?mode=editprofile')
							);
							
							$emailer->send();
							$emailer->reset();
						}
					}
					if ($userdata['user_topic_moved_pm'] == 1)
					{
						include($phpbb_root_path . 'includes/functions_post.'.$phpEx);
						for($i = 0; $i < sizeof($mailrow); $i++)
						{
							//pm-er
							$moved_url = $server_protocol . $server_name . $server_port . $script_path . 'viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $mailrow[$i]['topic_id'];
							$profile_link = $server_protocol . $server_name . $server_port . $script_path . 'profile.'.$phpEx.'?mode=editprofile';
							
							$pm_subject = $lang['topic_moved'];
							$pm_message = $lang['hello'] . $mailrow[$i]['username'] . '\n\n' . $lang['pmtext1'] . '\"' . $mailrow[$i]['topic_title'] . '\"' . $lang['pmtext2'] . '\n' . $lang['pmtext3'] . '\"' . $oldforumname . '"' . $lang['pmtext4'] . '\"' . $oldcatname . '\"\n' . $lang['pmtext5'] . '\"' . $newcatname . '"' . $lang['pmtext6'] . '\"' . $newforumname . '\".\n\n\n' . $lang['pmtext7'] . '\n' . $moved_url . '\n\n\n' . $lang['profiletext'] . '\n' . $profile_link;
							
							send_moved_topic_pm($userdata['user_id'], $mailrow[$i]['user_id'], $pm_subject, $pm_message, $mailrow[$i]['user_topic_moved_pm_notify']);
						}
					}
					if (($userdata['user_topic_moved_mail'] == 1) && ($userdata['user_topic_moved_pm'] != 1))
					{
						$mailmess = $lang['mail_send'] . '<br /><br />';
					}
					if (($userdata['user_topic_moved_mail'] != 1) && ($userdata['user_topic_moved_pm'] == 1))
					{
						$mailmess = $lang['pm_send'] . '<br /><br />';
					}
					if (($userdata['user_topic_moved_mail'] == 1) && ($userdata['user_topic_moved_pm'] == 1))
					{
						$mailmess = $lang['mail_pm_send'] . '<br /><br />';
					}
				}

				// Sync the forum indexes
				sync('forum', $new_forum_id);
				sync('forum', $old_forum_id);

				$message = $lang['Topics_Moved'] . (( $lock ) ? '<br /><br />' . $mailmess . $lang['Topics_Locked'] : '') . '<br /><br />';

			}
			else
			{
				$message = $lang['No_Topics_Moved'] . '<br /><br />';
			}

			if ( !empty($topic_id) )
			{
				$redirect_page = "viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;sid=" . $userdata['session_id'];
				$message .= sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
			}
			else
			{
				$redirect_page = "modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'];
				$message .= sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
			}

			$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . "viewforum.$phpEx?" . POST_FORUM_URL . "=$old_forum_id&amp;sid=" . $userdata['session_id'] . '">', '</a>');

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
			);

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';

			if ( isset($HTTP_POST_VARS['topic_id_list']) )
			{
				$topics = $HTTP_POST_VARS['topic_id_list'];

				for($i = 0; $i < sizeof($topics); $i++)
				{
					$hidden_fields .= '<input type="hidden" name="topic_id_list[]" value="' . intval($topics[$i]) . '" />';
				}
			}
			else
			{
				$hidden_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
			}

			//
			// Set template files
			//
			$template->set_filenames(array(
				'movetopic' => 'modcp_move.tpl')
			);

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_move_topic'],

				'L_MOVE_TO_FORUM' => $lang['Move_to_forum'], 
				'L_LEAVESHADOW' => $lang['Leave_shadow_topic'], 
				'L_LOCK' => $lang['Lock_topic'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_FORUM_SELECT' => make_forum_select('new_forum', $forum_id), 
				'S_MODCP_ACTION' => append_sid("modcp.$phpEx"),
				'S_HIDDEN_FIELDS' => $hidden_fields)
			);

			$template->pparse('movetopic');

			include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		}
		break;

	case 'lock':
		if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < sizeof($topics); $i++)
		{
			$topic_id_sql .= ( ( $topic_id_sql != '' ) ? ', ' : '' ) . intval($topics[$i]);
		}

		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET topic_status = " . TOPIC_LOCKED . " 
			WHERE topic_id IN ($topic_id_sql) 
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		if ($board_config['enable_mod_logger'])
		{
			for($i = 0; $i < sizeof($topics); $i++)
			{
				log_action('lock', intval($topics[$i]), $userdata['user_id'], $userdata['username']);
			}
		}
		
		if ( !empty($topic_id) )
		{
			$redirect_page = "viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;sid=" . $userdata['session_id'];
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = "modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . "viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'] . '">', '</a>');

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
		);

		message_die(GENERAL_MESSAGE, $lang['Topics_Locked'] . '<br /><br />' . $message);

		break;

	case 'unlock':
		if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < sizeof($topics); $i++)
		{
			$topic_id_sql .= ( ( $topic_id_sql != "") ? ', ' : '' ) . intval($topics[$i]);
		}

		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET topic_status = " . TOPIC_UNLOCKED . " 
			WHERE topic_id IN ($topic_id_sql) 
				AND forum_id = $forum_id
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		if ($board_config['enable_mod_logger'])
		{
			for($i = 0; $i < sizeof($topics); $i++)
			{
				log_action('unlock', intval($topics[$i]), $userdata['user_id'], $userdata['username']);
			}
		}
		
		if ( !empty($topic_id) )
		{
			$redirect_page = "viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;sid=" . $userdata['session_id'];
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = "modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . "viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'] . '">', '</a>');

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
		);

		message_die(GENERAL_MESSAGE, $lang['Topics_Unlocked'] . '<br /><br />' . $message);

		break;

	case 'sticky':
		if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < sizeof($topics); $i++)
		{
			$topic_id_sql .= ( ( $topic_id_sql != "") ? ', ' : '' ) . intval($topics[$i]);
		}

		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET topic_type = 1 
			WHERE topic_id IN ($topic_id_sql) 
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}
		
		if ($board_config['enable_mod_logger'])
		{
			for($i = 0; $i < sizeof($topics); $i++)
			{
				log_action('sticky', intval($topics[$i]), $userdata['user_id'], $userdata['username']);
			}
		}
		
		if ( !empty($topic_id) )
		{
			$redirect_page = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id");
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = append_sid("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id");
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
		);

		message_die(GENERAL_MESSAGE, $lang['Topics_Stickied'] . '<br /><br />' . $message);

		break;

	case 'unsticky':
		if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < sizeof($topics); $i++)
		{
			$topic_id_sql .= ( ( $topic_id_sql != "") ? ', ' : '' ) . intval($topics[$i]);
		}

		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET topic_type = 0 
			WHERE topic_id IN ($topic_id_sql) 
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		if ($board_config['enable_mod_logger'])
		{
			for($i = 0; $i < sizeof($topics); $i++)
			{
				log_action('unsticky', intval($topics[$i]), $userdata['user_id'], $userdata['username']);
			}
		}
		
		if ( !empty($topic_id) )
		{
			$redirect_page = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id");
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = append_sid("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id");
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
		);

		message_die(GENERAL_MESSAGE, $lang['Topics_Unstickied'] . '<br /><br />' . $message);

		break;

	case 'announce':
		if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < sizeof($topics); $i++)
		{
			$topic_id_sql .= ( ( $topic_id_sql != "") ? ', ' : '' ) . intval($topics[$i]);
		}

		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET topic_type = 2 
			WHERE topic_id IN ($topic_id_sql) 
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		if ($board_config['enable_mod_logger'])
		{
			for($i = 0; $i < sizeof($topics); $i++)
			{
				log_action('announce', intval($topics[$i]), $userdata['user_id'], $userdata['username']);
			}
		}
		
		if ( !empty($topic_id) )
		{
			$redirect_page = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id");
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = append_sid("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id");
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
		);

		message_die(GENERAL_MESSAGE, $lang['Topics_Announced'] . '<br /><br />' . $message);

		break;

	case 'unannounce':
		if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < sizeof($topics); $i++)
		{
			$topic_id_sql .= ( ( $topic_id_sql != "") ? ', ' : '' ) . intval($topics[$i]);
		}

		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET topic_type = 0 
			WHERE topic_id IN ($topic_id_sql) 
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		if ($board_config['enable_mod_logger'])
		{
			for($i = 0; $i < sizeof($topics); $i++)
			{
				log_action('unannounce', intval($topics[$i]), $userdata['user_id'], $userdata['username']);
			}
		}
		
		if ( !empty($topic_id) )
		{
			$redirect_page = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id");
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = append_sid("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id");
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
		);

		message_die(GENERAL_MESSAGE, $lang['Topics_Unannounced'] . '<br /><br />' . $message);

		break;

	case 'globalannounce':
		if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < sizeof($topics); $i++)
		{
			$topic_id_sql .= ( ( $topic_id_sql != "") ? ', ' : '' ) . intval($topics[$i]);
		}

		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET topic_type = 3 
			WHERE topic_id IN ($topic_id_sql) 
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}
		
		if ($board_config['enable_mod_logger'])
		{
			for($i = 0; $i < sizeof($topics); $i++)
			{
				log_action('globalannounce', intval($topics[$i]), $userdata['user_id'], $userdata['username']);
			}
		}
		
		if ( !empty($topic_id) )
		{
			$redirect_page = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id");
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = append_sid("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id");
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
		);

		message_die(GENERAL_MESSAGE, $lang['Topics_Globalannounced'] . '<br /><br />' . $message);

		break;

	case 'unglobalannounce':
		if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < sizeof($topics); $i++)
		{
			$topic_id_sql .= ( ( $topic_id_sql != "") ? ', ' : '' ) . intval($topics[$i]);
		}

		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET topic_type = 0 
			WHERE topic_id IN ($topic_id_sql) 
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		if ($board_config['enable_mod_logger'])
		{
			for($i = 0; $i < sizeof($topics); $i++)
			{
				log_action('unglobalannounce', intval($topics[$i]), $userdata['user_id'], $userdata['username']);
			}
		}
		
		if ( !empty($topic_id) )
		{
			$redirect_page = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id");
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = append_sid("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id");
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
		);

		message_die(GENERAL_MESSAGE, $lang['Topics_Unglobalannounced'] . '<br /><br />' . $message);

		break;

	case 'split':
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$post_id_sql = '';

		if (isset($HTTP_POST_VARS['split_type_all']) || isset($HTTP_POST_VARS['split_type_beyond']))
		{
			$posts = $HTTP_POST_VARS['post_id_list'];

			for ($i = 0; $i < sizeof($posts); $i++)
			{
				$post_id_sql .= (($post_id_sql != '') ? ', ' : '') . intval($posts[$i]);
			}
		}

		if ($post_id_sql != '')
		{
			$sql = "SELECT post_id 
				FROM " . POSTS_TABLE . "
				WHERE post_id IN ($post_id_sql)
					AND forum_id = $forum_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get post id information', '', __LINE__, __FILE__, $sql);
			}
			
			$post_id_sql = '';
			while ($row = $db->sql_fetchrow($result))
			{
				$post_id_sql .= (($post_id_sql != '') ? ', ' : '') . intval($row['post_id']);
			}
			$db->sql_freeresult($result);

			if ($post_id_sql == '')
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$sql = "SELECT post_id, poster_id, topic_id, post_time
				FROM " . POSTS_TABLE . "
				WHERE post_id IN ($post_id_sql) 
				ORDER BY post_time ASC";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not get post information', '', __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				$first_poster = $row['poster_id'];
				$topic_id = $row['topic_id'];
				$post_time = $row['post_time'];

				$user_id_sql = '';
				$post_id_sql = '';
				do
				{
					$user_id_sql .= (($user_id_sql != '') ? ', ' : '') . intval($row['poster_id']);
					$post_id_sql .= (($post_id_sql != '') ? ', ' : '') . intval($row['post_id']);;
				}
				while ($row = $db->sql_fetchrow($result));

				$post_subject = trim(htmlspecialchars($HTTP_POST_VARS['subject']));
				if (empty($post_subject))
				{
					message_die(GENERAL_MESSAGE, $lang['Empty_subject']);
				}

				$new_forum_id = intval($HTTP_POST_VARS['new_forum_id']);
				$topic_time = time();

				$sql = 'SELECT forum_id 
					FROM ' . FORUMS_TABLE . '
					WHERE forum_id = ' . $new_forum_id;
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not select from forums table', '', __LINE__, __FILE__, $sql);
				}
			
				if (!$db->sql_fetchrow($result))
				{
					message_die(GENERAL_MESSAGE, 'New forum does not exist');
				}

				$db->sql_freeresult($result);

				$sql  = "INSERT INTO " . TOPICS_TABLE . " (topic_title, topic_poster, topic_time, forum_id, topic_status, topic_type)
					VALUES ('" . str_replace("\'", "''", $post_subject) . "', $first_poster, " . $topic_time . ", $new_forum_id, " . TOPIC_UNLOCKED . ", " . POST_NORMAL . ")";
				if (!($db->sql_query($sql, BEGIN_TRANSACTION)))
				{
					message_die(GENERAL_ERROR, 'Could not insert new topic', '', __LINE__, __FILE__, $sql);
				}

				$new_topic_id = $db->sql_nextid();

				if ($board_config['enable_mod_logger'])
				{
					log_action('split', $topic_id, $userdata['user_id'], $userdata['username']);
				}
				
				// Update topic watch table, switch users whose posts
				// have moved, over to watching the new topic
				$sql = "UPDATE " . TOPICS_WATCH_TABLE . " 
					SET topic_id = $new_topic_id 
					WHERE topic_id = $topic_id 
						AND user_id IN ($user_id_sql)";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not update topics watch table', '', __LINE__, __FILE__, $sql);
				}

				$sql_where = (!empty($HTTP_POST_VARS['split_type_beyond'])) ? " post_time >= $post_time AND topic_id = $topic_id" : "post_id IN ($post_id_sql)";

				$sql = 	"UPDATE " . POSTS_TABLE . "
					SET topic_id = $new_topic_id, forum_id = $new_forum_id 
					WHERE $sql_where";
				if (!$db->sql_query($sql, END_TRANSACTION))
				{
					message_die(GENERAL_ERROR, 'Could not update posts table', '', __LINE__, __FILE__, $sql);
				}

				sync('topic', $new_topic_id);
				sync('topic', $topic_id);
				sync('forum', $new_forum_id);
				sync('forum', $forum_id);

				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . "viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;sid=" . $userdata['session_id'] . '">')
				);

				$message = $lang['Topic_split'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . "viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;sid=" . $userdata['session_id'] . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
		}
		else
		{
			//
			// Set template files
			//
			$template->set_filenames(array(
				'split_body' => 'modcp_split.tpl')
			);

			$sql = "SELECT u.username, p.*, pt.post_text, pt.bbcode_uid, pt.post_subject, p.post_username
				FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
				WHERE p.topic_id = $topic_id
					AND p.poster_id = u.user_id
					AND p.post_id = pt.post_id
				ORDER BY p.post_time ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get topic/post information', '', __LINE__, __FILE__, $sql);
			}

			$s_hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" /><input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" /><input type="hidden" name="mode" value="split" />';

			if( ( $total_posts = $db->sql_numrows($result) ) > 0 )
			{
				$postrow = $db->sql_fetchrowset($result);

				$template->assign_vars(array(
					'L_SPLIT_TOPIC' => $lang['Split_Topic'],
					'L_SPLIT_TOPIC_EXPLAIN' => $lang['Split_Topic_explain'],
					'L_AUTHOR' => $lang['Author'],
					'L_MESSAGE' => $lang['Message'],
					'L_SELECT' => $lang['Select'],
					'L_SPLIT_SUBJECT' => $lang['Split_title'],
					'L_SPLIT_FORUM' => $lang['Split_forum'],
					'L_POSTED' => $lang['Posted'],
					'L_SPLIT_POSTS' => $lang['Split_posts'],
					'L_SUBMIT' => $lang['Submit'],
					'L_SPLIT_AFTER' => $lang['Split_after'], 
					'L_POST_SUBJECT' => $lang['Post_subject'], 
					'L_MARK_ALL' => $lang['Mark_all'], 
					'L_UNMARK_ALL' => $lang['Unmark_all'], 
					'L_POST' => $lang['Post'], 

					'FORUM_NAME' => $forum_name, 

					'U_VIEW_FORUM' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id"), 

					'S_SPLIT_ACTION' => append_sid("modcp.$phpEx"),
					'S_HIDDEN_FIELDS' => $s_hidden_fields,
					'S_FORUM_SELECT' => make_forum_select("new_forum_id", false, $forum_id))
				);

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

				for($i = 0; $i < $total_posts; $i++)
				{
					$post_id = $postrow[$i]['post_id'];
					$poster_id = $postrow[$i]['poster_id'];
					$poster = $postrow[$i]['username'];

					$post_date = create_date($board_config['default_dateformat'], $postrow[$i]['post_time'], $board_config['board_timezone']);

					$bbcode_uid = $postrow[$i]['bbcode_uid'];
					$message = $postrow[$i]['post_text'];
					$post_subject = ( $postrow[$i]['post_subject'] != '' ) ? $postrow[$i]['post_subject'] : $topic_title;

					//
					// If the board has HTML off but the post has HTML
					// on then we process it, else leave it alone
					//
					if ( !$board_config['allow_html'] )
					{
						if ( $postrow[$i]['enable_html'] )
						{
							$message = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\\2&gt;', $message);
						}
					}

					if ( $bbcode_uid != '' )
					{
						$message = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
					}

					if ( sizeof($orig_word) )
					{
						$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);
						$message = preg_replace($orig_word, $replacement_word, $message);
					}

					$message = make_clickable($message);

					if ( $board_config['allow_smilies'] && $postrow[$i]['enable_smilies'] )
					{
						$message = smilies_pass($message);
					}
					if( !$board_config['allow_smilies'] && $board_config['smilie_removal1'] )
					{
						$message = smilies_code_removal($message);
					}

					$message = str_replace("\n", '<br />', $message);
					$message = word_wrap_pass($message);
					
					$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

					$checkbox = ( $i > 0 ) ? '<input type="checkbox" name="post_id_list[]" value="' . $post_id . '" />' : '&nbsp;';
					
					$template->assign_block_vars('postrow', array(
						'ROW_CLASS' => $row_class,
						'POSTER_NAME' => $poster,
						'POST_DATE' => $post_date,
						'POST_SUBJECT' => $post_subject,
						'MESSAGE' => $message,
						'POST_ID' => $post_id,
						
						'S_SPLIT_CHECKBOX' => $checkbox)
					);
				}

				$template->pparse('split_body');
			}
		}
		break;

	case 'bump': 
		//
		// !! Need to fix for subforums and update all forums above the sub
		// with the last_post_id update !!
		//
		$sql = "SELECT p.post_id AS last_post, p.forum_id
			FROM  " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		   	WHERE t.topic_id  = " . $topic_id . "
				AND t.topic_last_post_id = p.post_id";
		if ( !($result = $db->sql_query($sql)) ) 
		{
			message_die(GENERAL_ERROR, 'Could not select last post.', '', __LINE__, __FILE__, $sql);
		}

		if ($board_config['enable_mod_logger'])
		{
			log_action('bump', $topic_id, $userdata['user_id'], $userdata['username']);
		}
				
		$postrow = $db->sql_fetchrow($result);
		
		$sql = "UPDATE " . POSTS_TABLE . " 
			SET post_time = " . time() . "
			WHERE post_id = " . $postrow['last_post']; 
		if ( !($result = $db->sql_query($sql)) ) 
		{
			message_die(GENERAL_ERROR, 'Could not bump topic (posts).', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET topic_last_post_id = " . $postrow['last_post'] . "
			WHERE topic_id = " . $topic_id; 
		if ( !($result = $db->sql_query($sql)) ) 
		{
			message_die(GENERAL_ERROR, 'Could not bump topic (topics).', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . FORUMS_TABLE . " 
			SET forum_last_post_id = " . $postrow['last_post'] . "
			WHERE forum_id = " . $postrow['forum_id']; 
		if ( !($result = $db->sql_query($sql)) ) 
		{
			message_die(GENERAL_ERROR, 'Could not bump topic (forums).', '', __LINE__, __FILE__, $sql);
		}
		
		message_die(GENERAL_MESSAGE, $lang['Topics_Bumped'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid('viewforum.'.$phpEx.'?' . POST_FORUM_URL . '=' . $forum_id) . '">', '</a>')); 
		
		break; 

	case 'ip':
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$rdns_ip_num = ( isset($HTTP_GET_VARS['rdns']) ) ? $HTTP_GET_VARS['rdns'] : '';

		if ( !$post_id )
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_post']);
		}

		//
		// Set template files
		//
		$template->set_filenames(array(
			'viewip' => 'modcp_viewip.tpl')
		);

		// Look up relevent data for this post
		$sql = "SELECT poster_ip, poster_id 
			FROM " . POSTS_TABLE . " 
			WHERE post_id = $post_id
				AND forum_id = $forum_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not get poster IP information', '', __LINE__, __FILE__, $sql);
		}
		
		if ( !($post_row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_post']);
		}

		$ip_this_post = decode_ip($post_row['poster_ip']);
		$ip_this_post = ( $rdns_ip_num == $ip_this_post ) ? htmlspecialchars(gethostbyaddr($ip_this_post)) : $ip_this_post;

		$poster_id = $post_row['poster_id'];

		$template->assign_vars(array(
			'L_IP_INFO' => $lang['IP_info'],
			'L_THIS_POST_IP' => $lang['This_posts_IP'],
			'L_OTHER_IPS' => $lang['Other_IP_this_user'],
			'L_OTHER_USERS' => $lang['Users_this_IP'],
			'L_LOOKUP_IP' => $lang['Lookup_IP'], 
			'L_SEARCH' => $lang['Search'],

			'SEARCH_IMG' => $images['icon_search'], 

			'IP' => $ip_this_post, 
				
			'U_LOOKUP_IP' => "modcp.$phpEx?mode=ip&amp;" . POST_POST_URL . "=$post_id&amp;" . POST_TOPIC_URL . "=$topic_id&amp;rdns=$ip_this_post&amp;sid=" . $userdata['session_id'])
		);

		//
		// Get other IP's this user has posted under
		//
		$sql = "SELECT poster_ip, COUNT(*) AS postings 
			FROM " . POSTS_TABLE . " 
			WHERE poster_id = $poster_id 
			GROUP BY poster_ip 
			ORDER BY " . (( SQL_LAYER == 'msaccess' ) ? 'COUNT(*)' : 'postings' ) . " DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not get IP information for this user', '', __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			$i = 0;
			do
			{
				if ( $row['poster_ip'] == $post_row['poster_ip'] )
				{
					$template->assign_vars(array(
						'POSTS' => $row['postings'] . ' ' . ( ( $row['postings'] == 1 ) ? $lang['Post'] : $lang['Posts'] ))
					);
					continue;
				}

				$ip = decode_ip($row['poster_ip']);
				$ip = ( $rdns_ip_num == $row['poster_ip'] || $rdns_ip_num == 'all') ? htmlspecialchars(gethostbyaddr($ip)) : $ip;

				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('iprow', array(
					'ROW_CLASS' => $row_class, 
					'IP' => $ip,
					'POSTS' => $row['postings'] . ' ' . ( ( $row['postings'] == 1 ) ? $lang['Post'] : $lang['Posts'] ),

					'U_LOOKUP_IP' => "modcp.$phpEx?mode=ip&amp;" . POST_POST_URL . "=$post_id&amp;" . POST_TOPIC_URL . "=$topic_id&amp;rdns=" . $row['poster_ip'] . "&amp;sid=" . $userdata['session_id'])
				);

				$i++; 
			}
			while ( $row = $db->sql_fetchrow($result) );
		}

		//
		// Get other users who've posted under this IP
		//
		$sql = "SELECT u.user_id, u.username, COUNT(*) as postings 
			FROM " . USERS_TABLE ." u, " . POSTS_TABLE . " p 
			WHERE p.poster_id = u.user_id 
				AND p.poster_ip = '" . $post_row['poster_ip'] . "'
			GROUP BY u.user_id, u.username
			ORDER BY " . (( SQL_LAYER == 'msaccess' ) ? 'COUNT(*)' : 'postings' ) . " DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not get posters information based on IP', '', __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			$i = 0;
			do
			{
				$id = $row['user_id'];
				$username = ( $id == ANONYMOUS ) ? $lang['Guest'] : $row['username'];

				$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('userrow', array(
					'ROW_CLASS' => $row_class, 
					'USERNAME' => $username,
					'POSTS' => $row['postings'] . ' ' . ( ( $row['postings'] == 1 ) ? $lang['Post'] : $lang['Posts'] ),
					'L_SEARCH_POSTS' => sprintf($lang['Search_user_posts'], $username), 

					'U_PROFILE' => ($id == ANONYMOUS) ? "modcp.$phpEx?mode=ip&amp;" . POST_POST_URL . "=" . $post_id . "&amp;" . POST_TOPIC_URL . "=" . $topic_id . "&amp;sid=" . $userdata['session_id'] : append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$id"),
					'U_SEARCHPOSTS' => append_sid("search.$phpEx?search_author=" . (($id == ANONYMOUS) ? 'Anonymous' : urlencode($username)) . "&amp;showresults=topics"))
				);

				$i++; 
			}
			while ( $row = $db->sql_fetchrow($result) );
		}

		$template->pparse('viewip');

		break;

	case 'recycle':
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		if ( $confirm_recycle )
		{
			if ( ( $board_config['bin_forum'] == 0 ) || ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) ) )
			{
				$redirect_page = "modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'];
				$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
				$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . "viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'] . '">', '</a>');

				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
				);

				message_die(GENERAL_MESSAGE, $lang['None_selected'] . '<br /><br />' . $message);
			}
			else if ( isset($HTTP_POST_VARS['topic_id_list']) )
			{
				// Define bin forum
				$new_forum_id = intval($board_config['bin_forum']);
				$old_forum_id = $forum_id;

				if ( $new_forum_id != $old_forum_id )
				{
					$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

					$topic_list = '';
					for($i = 0; $i < sizeof($topics); $i++)
					{
						$topic_list .= ( ( $topic_list != '' ) ? ', ' : '' ) . intval($topics[$i]);
					}

					$sql = "SELECT * 
						FROM " . TOPICS_TABLE . " 
						WHERE topic_id IN ($topic_list)
							AND forum_id = $old_forum_id
							AND topic_status <> " . TOPIC_MOVED;
					if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
					{
						message_die(GENERAL_ERROR, 'Could not select from topic table', '', __LINE__, __FILE__, $sql);
					}

					$row = $db->sql_fetchrowset($result);
					$db->sql_freeresult($result);

					for($i = 0; $i < sizeof($row); $i++)
					{
						$topic_id = $row[$i]['topic_id'];

						if ( isset($HTTP_POST_VARS['move_leave_shadow']) )
						{
							// Insert topic in the old forum that indicates that the forum has moved.
							$sql = "INSERT INTO " . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_status, topic_type, topic_vote, topic_views, topic_replies, topic_first_post_id, topic_last_post_id, topic_moved_id)
								VALUES ($old_forum_id, '" . addslashes(str_replace("\'", "''", $row[$i]['topic_title'])) . "', '" . str_replace("\'", "''", $row[$i]['topic_poster']) . "', " . $row[$i]['topic_time'] . ", " . TOPIC_MOVED . ", " . POST_NORMAL . ", " . $row[$i]['topic_vote'] . ", " . $row[$i]['topic_views'] . ", " . $row[$i]['topic_replies'] . ", " . $row[$i]['topic_first_post_id'] . ", " . $row[$i]['topic_last_post_id'] . ", $topic_id)";
							if ( !$db->sql_query($sql) )
							{
								message_die(GENERAL_ERROR, 'Could not insert shadow topic', '', __LINE__, __FILE__, $sql);
							}
						}

						if ($board_config['enable_mod_logger'])
						{
							log_action('bin', $topic_id, $userdata['user_id'], $userdata['username']);
						}
						
						$sql = "UPDATE " . TOPICS_TABLE . " 
							SET forum_id = $new_forum_id  
							WHERE topic_id = $topic_id";
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'Could not update old topic', '', __LINE__, __FILE__, $sql);
						}

						$sql = "UPDATE " . POSTS_TABLE . " 
							SET forum_id = $new_forum_id 
							WHERE topic_id = $topic_id";
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'Could not update post topic ids', '', __LINE__, __FILE__, $sql);
						}
					}

					// Sync the forum indexes
					sync('forum', $new_forum_id);
					sync('forum', $old_forum_id);

					$message = $lang['Topics_Moved_bin'];
				}
				else
				{
					$message = $lang['No_Topics_Moved'];
				}

				$redirect_page = "modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'];
				$message .= '<br /><br />' . sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');

				$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . "viewforum.$phpEx?" . POST_FORUM_URL . "=$old_forum_id&amp;sid=" . $userdata['session_id'] . '">', '</a>');

				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
				);

				message_die(GENERAL_MESSAGE, $message);
			}
		}
		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		break;

	case 'link':
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		
		$confirm = ( isset($HTTP_POST_VARS['merge2']) ) ? TRUE : FALSE;

		if ( $confirm )
		{
			if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$new_forum_id = $HTTP_POST_VARS['new_forum'];
			$old_forum_id = $forum_id;

			$message = $lang['No_Topics_Linked'] . '<br /><br />';
			if ( $new_forum_id != $old_forum_id )
			{
				$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

				$topic_list = '';
				for($i = 0; $i < sizeof($topics); $i++)
				{
					$topic_list .= ( ( $topic_list != '' ) ? ', ' : '' ) . intval($topics[$i]);
				}

				$sql = "SELECT *
					FROM " . TOPICS_TABLE . "
					WHERE topic_id IN ($topic_list)
						AND topic_status NOT IN (" . TOPIC_MOVED . ", " . TOPIC_LINKED . ")";
				if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
				{
					message_die(GENERAL_ERROR, 'Could not select from topic table', '', __LINE__, __FILE__, $sql);
				}

				$row = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);

				for($i = 0; $i < sizeof($row); $i++)
				{
					$topic_id = $row[$i]['topic_id'];

					$values_sql .= "($new_forum_id, '" . addslashes(str_replace("\'", "''", $row[$i]['topic_title'])) . "', '" . str_replace("\'", "''", $row[$i]['topic_poster']) . "', " . $row[$i]['topic_time'] . ", " . TOPIC_LINKED . ", " . POST_NORMAL . ", " . $row[$i]['topic_vote'] . ", " . $row[$i]['topic_views'] . ", " . $row[$i]['topic_replies'] . ", " . $row[$i]['topic_first_post_id'] . ", " . $row[$i]['topic_last_post_id'] . ", $topic_id)";
					if ( !$db->sql_query($sql) )
					{
							message_die(GENERAL_ERROR, 'Could not insert shadow topic', '', __LINE__, __FILE__, $sql);
					}
				}
				if ( !empty($values_sql) )
				{
					$sql = "INSERT INTO " . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_status, topic_type, topic_vote, topic_views, topic_replies, topic_first_post_id, topic_last_post_id, topic_moved_id)
						VALUES $values_sql";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not insert link topic', '', __LINE__, __FILE__, $sql);
					}
					
					if ($board_config['enable_mod_logger'])
					{
						log_action('link', $topic_id, $userdata['user_id'], $userdata['username']);
					}
					
					sync('forum', $new_forum_id);
					$message = $lang['Topics_Linked'] . '<br /><br />';
				}

			}

			if ( !empty($topic_id) )
			{
				$redirect_page = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id");
				$message .= sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
			}
			else
			{
				$redirect_page = append_sid("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id");
				$message .= sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
			}

			$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$old_forum_id") . '">', '</a>');

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
			);

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';

			if ( isset($HTTP_POST_VARS['topic_id_list']) )
			{
				$topics = $HTTP_POST_VARS['topic_id_list'];

				for($i = 0; $i < sizeof($topics); $i++)
				{
					$hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="topic_id_list[]" value="' . intval($topics[$i]) . '" />';
				}
			}
			else
			{
				$hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
			}

			//
			// Set template files
			//
			$template->set_filenames(array(
				'body' => 'modcp_merge_topic.tpl')
			);

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_link_topic'],

				'L_MERGE_TO_FORUM' => $lang['Link_in_forum'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_FORUM_SELECT' => make_forum_select('new_forum', $forum_id),
				'S_MODCP_ACTION' => append_sid("modcp.$phpEx"),
				'S_HIDDEN_FIELDS' => $hidden_fields)
			);

			$template->pparse('body');

			include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		}
		break;

	case 'quick_title_edit':
		if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$addon = str_replace('%mod%', addslashes($userdata['username']), $qt_row['title_info']);
		$dateqt = ( $qt_row['date_format'] == '' ) ? create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']) : create_date($qt_row['date_format'], time(), $board_config['board_timezone']);
		$addon = str_replace('%date%', $dateqt, $addon);
		$title_pos = $qt_row['title_pos'];
		
		$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

		$topic_id_sql = '';
		for($i = 0; $i < sizeof($topics); $i++)
		{
			$topic_id_sql .= ( ( $topic_id_sql != "") ? ', ' : '' ) . $topics[$i];
		}

		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET title_compl_infos = '" . addslashes($addon) . "'   
			WHERE topic_id IN ($topic_id_sql) 
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET title_compl_color = '" . $qt_row['info_color'] . "'
			WHERE topic_id IN ($topic_id_sql)
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . TOPICS_TABLE . "
			SET title_pos = '" . $qt_row['title_pos'] . "'
			WHERE topic_id IN ($topic_id_sql)
				AND topic_moved_id = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
		}

		if ( !empty($topic_id) )
		{
			$redirect_page = "viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;sid=" . $userdata['session_id'];
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = "modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . "viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'] . '">', '</a>');

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
		);

		message_die(GENERAL_MESSAGE, $lang['Topics_Title_Edited'] . '<br /><br />' . $message);
		break;

	case 'cement':
		if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
		{
			message_die(GENERAL_MESSAGE, $lang['None_selected']);
		}

		$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

		for($i = 0; $i < sizeof($topics); $i++)
		{
			$priority_box_id = "topic_cement:" . intval($topics[$i]);
			$topic_priority = (isset($HTTP_POST_VARS[$priority_box_id])) ? intval($HTTP_POST_VARS[$priority_box_id]) : 0;
			
			$sql = "UPDATE " . TOPICS_TABLE . " 
 				SET topic_priority = $topic_priority
 				WHERE topic_id = " . $topics[$i];
 			if ( !($result = $db->sql_query($sql)) )
 			{
 				message_die(GENERAL_ERROR, 'Could not update topics table', '', __LINE__, __FILE__, $sql);
 			}
		}

		if ( !empty($topic_id) )
		{
			$redirect_page = "viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;sid=" . $userdata['session_id'];
			$message = sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
		}
		else
		{
			$redirect_page = "modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'];
			$message = sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
		}

		$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . "viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'] . '">', '</a>');

		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
		);

		message_die(GENERAL_MESSAGE, $lang['Topics_Prioritized'] . '<br /><br />' . $message);

		break;

	case 'mergetopic':
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		$merge2 = ( isset($HTTP_POST_VARS['merge2']) ) ? TRUE : FALSE;
		
		// this starts after you selected the forum to which the topic will be moved/merged
	    if ( $merge2 )
	    {
			$new_forum_id = $HTTP_POST_VARS['new_forum'];
			
			if ( isset($HTTP_POST_VARS['topic_id_list']) )
			{
				$topics = $HTTP_POST_VARS['topic_id_list'];
				$topic_list = '';

				for($i = 0; $i < sizeof($topics); $i++)
				{
					$topic_list .= ( ( $topic_list != '' ) ? ', ' : '' ) . $topics[$i];
					$hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="topic_id_list[]" value="' . intval($topics[$i]) . '" />';
				}
			}
			else
			{
				$topic_list = $topic_id;
				$hidden_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
			}
			$hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
			$hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
			$hidden_fields .= '<input type="hidden" name="new_forum" value="' . $new_forum_id . '" />';

			$template->assign_block_vars('switch_shadow_topic', array());

			$template->assign_vars(array(
				'FORUM_NAME' => $forum_name,
	
				'L_MOD_CP' => $lang['Mod_CP'],
				'L_MOD_CP_EXPLAIN' => $lang['Mod_CP_merge_explain'],
				'L_SELECT' => $lang['Select'],
				'L_MERGE' => $lang['Merge'],
				'L_AUTHOR' => $lang['Author'], 
				'L_TOPICS' => $lang['Topics'], 
				'L_REPLIES' => $lang['Replies'], 
				'L_LASTPOST' => $lang['Last_Post'], 
				'L_SELECT' => $lang['Select'], 
				'L_LEAVESHADOW' => $lang['Leave_shadow_topic'], 
	
				'U_VIEW_FORUM' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id"), 
				'S_HIDDEN_FIELDS' => $hidden_fields,
				'S_MODCP_ACTION' => append_sid("modcp.$phpEx"))
			);
	
			$template->set_filenames(array(
				'body' => 'modcp_merge_topicpost.tpl')
			);
	
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
	
			$sql = "SELECT t.*, u.username, u.user_id, u.user_level, p.post_time
				FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p
				WHERE t.forum_id = $new_forum_id
					AND t.topic_poster = u.user_id
					AND p.post_id = t.topic_last_post_id
					AND t.topic_moved_id = 0
					AND t.topic_id NOT IN ($topic_list)
				ORDER BY t.topic_type DESC, p.post_time DESC
				LIMIT $start, " . $board_config['topics_per_page'];
			if ( !($result = $db->sql_query($sql)) )
			{
		   		message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
			}
	
			while ( $row = $db->sql_fetchrow($result) )
			{
				$topic_title = '';
	
				if ( $row['topic_status'] == TOPIC_LOCKED )
				{
					$folder_img = $images['folder_locked'];
					$folder_alt = $lang['Topic_locked'];
				}
				else
				{
					if ( $topic_type == POST_GLOBAL_ANNOUNCE )
					{
						$folder = $images['folder_global_announce']; 
						$folder_new = $images['folder_global_announce_new']; 
					}
					else if ( $row['topic_type'] == POST_ANNOUNCE )
					{
						$folder_img = $images['folder_announce'];
						$folder_alt = $lang['Announcement'];
					}
					else if ( $row['topic_type'] == POST_STICKY )
					{
						$folder_img = $images['folder_sticky'];
						$folder_alt = $lang['Sticky'];
					}
					else 
					{
						$folder_img = $images['folder'];
						$folder_alt = $lang['No_new_posts'];
					}
				}
	
				$topic_id = $row['topic_id'];
				$topic_type = $row['topic_type'];
				$topic_status = $row['topic_status'];
				$topic_title = $row['topic_title'];
				
				if ( $topic_type == POST_GLOBAL_ANNOUNCE )
				{
					$topic_type = $lang['Topic_global_Announcement'] . ' ';
				}
				else if ( $topic_type == POST_ANNOUNCE )
				{
					$topic_type = $lang['Topic_Announcement'] . ' ';
				}
				else if ( $topic_type == POST_STICKY )
				{
					$topic_type = $lang['Topic_Sticky'] . ' ';
				}
				else if ( $topic_status == TOPIC_MOVED )
				{
					$topic_type = $lang['Topic_Moved'] . ' ';
				}
				else
				{
					$topic_type = '';		
				}
		
				if ( $row['topic_vote'] )
				{
					$topic_type .= $lang['Topic_Poll'] . ' ';
				}
		
				if ( sizeof($orig_word) )
				{
					$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
				}
	
		        $topic_title = capitalization($topic_title);

				if ($board_config['enable_quick_titles'])
				{
					if ($row['title_pos'] )
					{
						$topic_title = (empty($row['title_compl_infos'])) ? $topic_title : $topic_title . ' <span style="color: #' . $row['title_compl_color'] . '">' . $row['title_compl_infos'] . '</span>';
					}
					else
					{
						$topic_title = (empty($row['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $row['title_compl_color'] . '">' . $row['title_compl_infos'] . '</span> ' . $topic_title;
					}
				}

				$u_view_topic = '';
				$topic_replies = $row['topic_replies'];
	
				$last_post_time = create_date($board_config['default_dateformat'], $row['post_time'], $board_config['board_timezone']);
				$first_post_time = create_date($board_config['default_dateformat'], $row['topic_time'], $board_config['board_timezone']);
				$username = username_level_color($row['username'], $row['user_level'], $row['user_id']);
	
				$template->assign_block_vars('topicrow', array(
					'U_VIEW_TOPIC' => $u_view_topic,
	
					'TOPIC_FOLDER_IMG' => $folder_img, 
					'TOPIC_TYPE' => $topic_type, 
					'TOPIC_TITLE' => $topic_title,
					'REPLIES' => $topic_replies,
					'LAST_POST_TIME' => $last_post_time,
					'FIRST_POST_TIME' => $first_post_time,
					'TOPIC_ID' => $topic_id,
					'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($row['topic_attachment']),
					'USERNAME' => $username,
					
					'L_TOPIC_STARTED' => $lang['Topic_started'],
					'L_TOPIC_FOLDER_ALT' => $folder_alt)
				);
			}
	
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'], $forum_topics, $board_config['topics_per_page'], $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $forum_topics / $board_config['topics_per_page'] )), 
				'L_GOTO_PAGE' => $lang['Goto_page'])
			);
	
			$template->pparse('body');
	    }
	    // After you selected the topic to merge with
		else if ( $confirm )
		{
			// $leave_shadow = ( $HTTP_POST_VARS['merge_leave_shadow'] ) ? TRUE : 0;
			if ( empty($HTTP_POST_VARS['topic_id_to']) )
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}
			
			$topic_id_to = $HTTP_POST_VARS['topic_id_to'];
			$new_forum_id = $HTTP_POST_VARS['new_forum'];
			$old_forum_id = $forum_id;
			
			if ( $topic_id != $topic_id_to )
			{
				$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ?  $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

				$topic_list = '';
				for($i = 0; $i < sizeof($topics); $i++)
				{
					$topic_list .= ( ( $topic_list != '' ) ? ', ' : '' ) . $topics[$i];
				}

				$sql = "SELECT * 
					FROM " . TOPICS_TABLE . " 
					WHERE topic_id IN ($topic_list) 
						AND topic_status <> " . TOPIC_MOVED;
				if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
				{
					message_die(GENERAL_ERROR, 'Could not select from topic table', '', __LINE__, __FILE__, $sql);
				}

				$row = $db->sql_fetchrowset($result);
				$db->sql_freeresult($result);

				for($i = 0; $i < sizeof($row); $i++)
				{
					$topic_id = $row[$i]['topic_id'];
					
					if ( isset($HTTP_POST_VARS['merge_leave_shadow']) )
					{
						// Insert topic in the old forum that indicates that the forum has moved.
						$sql = "INSERT INTO " . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_status, topic_type, topic_vote, topic_views, topic_replies, topic_first_post_id, topic_last_post_id, topic_moved_id)
							VALUES ($old_forum_id, '" . addslashes(str_replace("\'", "''", $row[$i]['topic_title'])) . "', '" . str_replace("\'", "''", $row[$i]['topic_poster']) . "', " . $row[$i]['topic_time'] . ", " . TOPIC_MOVED . ", " . POST_NORMAL . ", " . $row[$i]['topic_vote'] . ", " . $row[$i]['topic_views'] . ", " . $row[$i]['topic_replies'] . ", " . $row[$i]['topic_first_post_id'] . ", " . $row[$i]['topic_last_post_id'] . ", $topic_id_to)";
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'Could not insert shadow topic', '', __LINE__, __FILE__, $sql);
						}
					}

					if ($board_config['enable_mod_logger'])
					{
						log_action('merge', $topic_id_to, $userdata['user_id'], $userdata['username']);
					}
					
					$sql = "DELETE 
						FROM " . TOPICS_TABLE . " 
						WHERE topic_id = $topic_id";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete old topic', '', __LINE__, __FILE__, $sql);
					}

					$sql = "UPDATE " . POSTS_TABLE . " 
						SET forum_id = $new_forum_id, topic_id = $topic_id_to
						WHERE topic_id = $topic_id";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not update post topic ids', '', __LINE__, __FILE__, $sql);
					}
				}

				// Sync the forum indexes
				sync('forum', $new_forum_id);
				sync('forum', $old_forum_id);
				sync('topic', $topic_id_to);
				$message = $lang['Topics_Merged'] . '<br /><br />';

			}
			else
			{
				$message = $lang['No_Topics_Merged'] . '<br /><br />';
			}

			if ( !empty($topic_id) )
			{
				$redirect_page = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id_to&amp;sid=" . $userdata['session_id']);
				$message .= sprintf($lang['Click_return_topic'], '<a href="' . $redirect_page . '">', '</a>');
			}
			else
			{
				$redirect_page = append_sid("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id']);
				$message .= sprintf($lang['Click_return_modcp'], '<a href="' . $redirect_page . '">', '</a>');
			}

			$message = $message . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$old_forum_id") . '">', '</a>');

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . $redirect_page . '">')
			);

			message_die(GENERAL_MESSAGE, $message);
		}
		// Here you select the forum is has to merge to
		else
		{
			if ( empty($HTTP_POST_VARS['topic_id_list']) && empty($topic_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}

			$hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';

			if ( isset($HTTP_POST_VARS['topic_id_list']) )
			{
				$topics = $HTTP_POST_VARS['topic_id_list'];

				for($i = 0; $i < sizeof($topics); $i++)
				{
					$hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="topic_id_list[]" value="' . intval($topics[$i]) . '" />';
				}
			}
			else
			{
				$hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
			}

			//
			// Set template files
			//
			$template->set_filenames(array(
				'movetopic' => 'modcp_merge_topic.tpl')
			);

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Confirm_merge_topic'],

				'L_MERGE_TO_FORUM' => $lang['Merge_to_forum'], 
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],

				'S_FORUM_SELECT' => make_forum_select('new_forum'), 
				'S_MODCP_ACTION' => append_sid("modcp.$phpEx"),
				'S_HIDDEN_FIELDS' => $hidden_fields)
			);

			$template->pparse('movetopic');

			include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		}
		break;
		
	case 'mergepost':
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		if ( $confirm )
		{
			if ( empty($HTTP_POST_VARS['topic_id_to']) )
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected']);
			}
			
			$topic_id_to = $HTTP_POST_VARS['topic_id_to'];
			$new_forum_id = $HTTP_POST_VARS['new_forum_id'];
			$old_forum_id = $forum_id;
			
			$posts = $HTTP_POST_VARS['post_id_list'];

			if ( empty($HTTP_POST_VARS['post_id_list']) && empty($post_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['None_selected_posts']);
			}
		
			$sql = "SELECT poster_id, topic_id, post_time
				FROM " . POSTS_TABLE . "
				WHERE post_id = " . $posts[0];
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get post information', '', __LINE__, __FILE__, $sql);
			}

			$post_rowset = $db->sql_fetchrow($result);
			$post_time = $post_rowset['post_time'];
			
			if( !empty($HTTP_POST_VARS['merge_type_all']) )
			{
				$post_id_sql = '';
				for($i = 0; $i < sizeof($posts); $i++)
				{
					$post_id_sql .= ( ( $post_id_sql != '' ) ? ', ' : '' ) . $posts[$i];
				}

				$sql = "UPDATE " . POSTS_TABLE . "
					SET topic_id = $topic_id_to, forum_id = $new_forum_id 
					WHERE post_id IN ($post_id_sql)";
			}
			else if( !empty($HTTP_POST_VARS['merge_type_beyond']) )
			{
				$sql = "UPDATE " . POSTS_TABLE . "
					SET topic_id = $topic_id_to, forum_id = $new_forum_id
					WHERE post_time >= $post_time
						AND topic_id = $topic_id";
			}

			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update posts table', '', __LINE__, __FILE__, $sql);
			}

			if ($board_config['enable_mod_logger'])
			{
				log_action('merge', $topic_id, $userdata['user_id'], $userdata['username']);
			}
			
			sync('topic', $topic_id_to);
			sync('topic', $topic_id);
			sync('forum', $new_forum_id);
			sync('forum', $old_forum_id);

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">')
			);

			$message = $lang['Posts_Merged'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		// Step 1 - Pic topic to merge to
		else if ( isset($HTTP_POST_VARS['merge_type_all']) || isset($HTTP_POST_VARS['merge_type_beyond'])   )
    	{
			$posts = $HTTP_POST_VARS['post_id_list'];
			$new_forum_id = $HTTP_POST_VARS['new_forum_id'];
			
			if ( isset($HTTP_POST_VARS['post_id_list']) )
			{
				$posts = $HTTP_POST_VARS['post_id_list'];
				$posts_list = '';

				for($i = 0; $i < sizeof($posts); $i++)
				{
					$posts_list .= ( ( $posts_list != '' ) ? ', ' : '' ) . $posts[$i];
					$hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="post_id_list[]" value="' . intval($posts[$i]) . '" />';
				}
			}
			else
			{
				$posts_list = $post_id;
				$hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_POST_URL . '" value="' . $post_id . '" />';
			}
			$hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';
			$hidden_fields .= '<input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
			$hidden_fields .= '<input type="hidden" name="mode" value="' . $mode . '" />';
			$hidden_fields .= '<input type="hidden" name="new_forum_id" value="' . $new_forum_id . '" />';
			$hidden_fields .= '<input type="hidden" name="merge_type_all" value="' . $HTTP_POST_VARS['merge_type_all'] . '" />';
			$hidden_fields .= '<input type="hidden" name="merge_type_beyond" value="' . $HTTP_POST_VARS['merge_type_beyond'] . '" />';
			
			$template->assign_vars(array(
				'FORUM_NAME' => $forum_name,
	
				'L_MOD_CP' => $lang['Mod_CP'],
				'L_MOD_CP_EXPLAIN' => $lang['Mod_CP_explain'],
				'L_SELECT' => $lang['Select'],
				'L_MERGE' => $lang['Merge'],
				'L_AUTHOR' => $lang['Author'], 
				'L_TOPICS' => $lang['Topics'], 
				'L_REPLIES' => $lang['Replies'], 
				'L_LASTPOST' => $lang['Last_Post'], 
				'L_SELECT' => $lang['Select'], 
	
				'U_VIEW_FORUM' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id"), 
				'S_HIDDEN_FIELDS' => $hidden_fields,
				'S_MODCP_ACTION' => append_sid("modcp.$phpEx"))
			);
	
			$template->set_filenames(array(
				'body' => 'modcp_merge_topicpost.tpl')
			);
	
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
			
			//AND t.topic_id NOT IN ($topic_list)
			$sql = "SELECT t.*, u.username, u.user_id, u.user_level, p.post_time
				FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p
				WHERE t.forum_id = $new_forum_id
					AND t.topic_poster = u.user_id
					AND p.post_id = t.topic_last_post_id
					AND t.topic_moved_id = 0
					AND t.topic_id != $topic_id
				ORDER BY t.topic_type DESC, p.post_time DESC
				LIMIT $start, " . $board_config['topics_per_page'];
			if ( !($result = $db->sql_query($sql)) )
			{
		   		message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
			}
	
			while ( $row = $db->sql_fetchrow($result) )
			{
				$topic_title = '';
	
				if ( $row['topic_status'] == TOPIC_LOCKED )
				{
					$folder_img = $images['folder_locked'];
					$folder_alt = $lang['Topic_locked'];
				}
				else
				{
					if ( $topic_type == POST_GLOBAL_ANNOUNCE )
					{
						$folder = $images['folder_global_announce']; 
						$folder_new = $images['folder_global_announce_new']; 
					}
					else if ( $row['topic_type'] == POST_ANNOUNCE )
					{
						$folder_img = $images['folder_announce'];
						$folder_alt = $lang['Announcement'];
					}
					else if ( $row['topic_type'] == POST_STICKY )
					{
						$folder_img = $images['folder_sticky'];
						$folder_alt = $lang['Sticky'];
					}
					else 
					{
						$folder_img = $images['folder'];
						$folder_alt = $lang['No_new_posts'];
					}
				}
	
				$topic_id = $row['topic_id'];
				$topic_type = $row['topic_type'];
				$topic_status = $row['topic_status'];
				
				if ( $topic_type == POST_GLOBAL_ANNOUNCE )
				{
					$topic_type = $lang['Topic_global_Announcement'] . ' ';
				}
				else if ( $topic_type == POST_ANNOUNCE )
				{
					$topic_type = $lang['Topic_Announcement'] . ' ';
				}
				else if ( $topic_type == POST_STICKY )
				{
					$topic_type = $lang['Topic_Sticky'] . ' ';
				}
				else if ( $topic_status == TOPIC_MOVED )
				{
					$topic_type = $lang['Topic_Moved'] . ' ';
				}
				else
				{
					$topic_type = '';		
				}
		
				if ( $row['topic_vote'] )
				{
					$topic_type .= $lang['Topic_Poll'] . ' ';
				}
		
				$topic_title = $row['topic_title'];
				if ( sizeof($orig_word) )
				{
					$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
				}
	
				$u_view_topic = '';
				$topic_replies = $row['topic_replies'];
	
				$last_post_time = create_date($board_config['default_dateformat'], $row['post_time'], $board_config['board_timezone']);
				$username = username_level_color($row['username'], $row['user_level'], $row['user_id']);

				$template->assign_block_vars('topicrow', array(
					'U_VIEW_TOPIC' => $u_view_topic,
	
					'TOPIC_FOLDER_IMG' => $folder_img, 
					'TOPIC_TYPE' => $topic_type, 
					'TOPIC_TITLE' => $topic_title,
					'USERNAME' => $username,
					'REPLIES' => $topic_replies,
					'LAST_POST_TIME' => $last_post_time,
					'TOPIC_ID' => $topic_id,
						
					'L_TOPIC_FOLDER_ALT' => $folder_alt)
				);
			}
			$db->sql_freeresult($result);
	
			$template->assign_vars(array(
				'PAGINATION' => generate_pagination("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'], $forum_topics, $board_config['topics_per_page'], $start),
				'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $forum_topics / $board_config['topics_per_page'] )), 
				'L_GOTO_PAGE' => $lang['Goto_page'])
			);
	
			$template->pparse('body');
	   	}
		else
		{	
			// Step 0 - select the post you want to merge 
			//
			// Set template files
			//
			$template->set_filenames(array(
				'merge_post_body' => 'modcp_merge_post.tpl')
			);

			$sql = "SELECT u.username, p.*, pt.post_text, pt.bbcode_uid, pt.post_subject, p.post_username
				FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
				WHERE p.topic_id = $topic_id
					AND p.poster_id = u.user_id
					AND p.post_id = pt.post_id
				ORDER BY p.post_time ASC";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get topic/post information', '', __LINE__, __FILE__, $sql);
			}

			$s_hidden_fields = '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" /><input type="hidden" name="mode" value="mergepost" />';
			$s_hidden_fields .= '<input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" />';

			if( ( $total_posts = $db->sql_numrows($result) ) > 0 )
			{
				$postrow = $db->sql_fetchrowset($result);

				$template->assign_vars(array(
					'L_MERGE_TOPIC' => $lang['Merge_Topic'],
					'L_MERGE_TOPIC_EXPLAIN' => $lang['Merge_Topic_explain'],
					'L_AUTHOR' => $lang['Author'],
					'L_MESSAGE' => $lang['Message'],
					'L_SELECT' => $lang['Select'],
					'L_MERGE_TO_FORUM' => $lang['Merge_to_forum'],
					'L_MERGE_POST_TOPIC' => $lang['Merge_post_topic'],
					'L_POSTED' => $lang['Posted'],
					'L_MERGE_POSTS' => $lang['Merge_posts'],
					'L_SUBMIT' => $lang['Submit'],
					'L_MERGE_AFTER' => $lang['Merge_after'], 
					'L_MARK_ALL' => $lang['Mark_all'], 
					'L_UNMARK_ALL' => $lang['Unmark_all'], 
					'L_POST' => $lang['Post'], 
					'L_POST_SUBJECT' => $lang['Post_subject'], 

					'FORUM_NAME' => $forum_name, 

					'U_VIEW_FORUM' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id"), 

					'S_MERGE_ACTION' => append_sid("modcp.$phpEx"),
					'S_HIDDEN_FIELDS' => $s_hidden_fields,
					'S_FORUM_SELECT' => make_forum_select("new_forum_id"))
				);

				for($i = 0; $i < $total_posts; $i++)
				{
					$post_id = $postrow[$i]['post_id'];
					$poster_id = $postrow[$i]['user_id'];
					$poster = $postrow[$i]['username'];

					$post_date = create_date($board_config['default_dateformat'], $postrow[$i]['post_time'], $board_config['board_timezone']);

					$bbcode_uid = $postrow[$i]['bbcode_uid'];
					$message = $postrow[$i]['post_text'];
					$post_subject = ( $postrow[$i]['post_subject'] != '' ) ? $postrow[$i]['post_subject'] : $topic_title;

					//
					// If the board has HTML off but the post has HTML
					// on then we process it, else leave it alone
					//
					if ( !$board_config['allow_html'] )
					{
						if ( $postrow[$i]['enable_html'] )
						{
							$message = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\\2&gt;', $message);
						}
					}

					if ( $bbcode_uid != '' )
					{
						$message = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
					}

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

					if ( sizeof($orig_word) )
					{
						$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);
						$message = preg_replace($orig_word, $replacement_word, $message);
					}

					$message = make_clickable($message);

					if ( $board_config['allow_smilies'] && $postrow[$i]['enable_smilies'] )
					{
						$message = smilies_pass($message);
					}
					if( !$board_config['allow_smilies'] && $board_config['smilie_removal1'] )
					{
						$message = smilies_code_removal($message);
					}

					$message = str_replace("\n", '<br />', $message);
					
					$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

					$checkbox = ( $i > 0 ) ? '<input type="checkbox" name="post_id_list[]" value="' . $post_id . '" />' : '&nbsp;';
					
					$template->assign_block_vars('postrow', array(
						'ROW_CLASS' => $row_class,
						'POSTER_NAME' => $poster,
						'POST_DATE' => $post_date,
						'POST_SUBJECT' => $post_subject,
						'MESSAGE' => $message,
						'POST_ID' => $post_id,
						
						'S_MERGE_CHECKBOX' => $checkbox)
					);
				}

				$template->pparse('merge_post_body');
			}
		}
		break;
	
	default:	
		$page_title = $lang['Mod_CP'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		if ($board_config['enable_quick_titles'])
		{
			$sql = "SELECT * 
				FROM " . TITLE_INFOS_TABLE . " 
				ORDER BY title_info";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_MESSAGE, 'Unable to query Quick Title Addon informations.');
			}
			
			$select_title = '<select name="qtnum"><option value="-1">---</option>';
			while ( $row = $db->sql_fetchrow($result) )
			{
				$addon = str_replace('%mod%', addslashes($userdata['username']), $row['title_info']);
				$dateqt = ( $row['date_format'] == '' ) ? create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']) : create_date($row['date_format'], time(), $board_config['board_timezone']);
				$addon = str_replace('%date%', $dateqt, $addon);
				$select_title .= '<option value="' . $row['id'] . '">' . $addon . '</option>';
			}
			$db->sql_freeresult($result);
			$select_title .= '</select>';
		}

		$template->assign_vars(array(
			'FORUM_NAME' => $forum_name,
			'SELECT_TITLE' => $select_title,

			'L_MOD_CP' => $lang['Mod_CP'],
			'L_MOD_CP_EXPLAIN' => $lang['Mod_CP_explain'],
			'L_SELECT' => $lang['Select'],
			'L_DELETE' => $lang['Delete'],
			'L_MOVE' => $lang['Move'],
			'L_LOCK' => $lang['Lock'],
			'L_UNLOCK' => $lang['Unlock'],
			'L_LINK' => $lang['Link'],
			'L_AUTHOR' => $lang['Author'],
			'L_TOPICS' => $lang['Topics'], 
			'L_REPLIES' => $lang['Replies'], 
			'L_LASTPOST' => $lang['Last_Post'], 
			'L_SELECT' => $lang['Select'], 
			'L_PRIORITY' =>   $lang['Priority'], 
			'L_PRIORITIZE' => $lang['Prioritize'], 
			'L_MERGE' => $lang['Merge'],

			'U_VIEW_FORUM' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id"), 
			'S_HIDDEN_FIELDS' => '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />',
			'S_MODCP_ACTION' => append_sid("modcp.$phpEx"))
		);

		$template->set_filenames(array(
			'body' => 'modcp_body.tpl')
		);
		make_jumpbox('modcp.'.$phpEx);

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

		$sql = "SELECT t.*, u.username, u.user_id, u.user_level, p.post_time
			FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p
			WHERE t.forum_id = $forum_id
				AND t.topic_poster = u.user_id
				AND p.post_id = t.topic_last_post_id ";
			if ( $board_config['locked_last'] ) 
			{
				$sql .= "ORDER BY t.topic_type DESC, t.topic_priority DESC, t.topic_status ASC, p.post_time DESC ";
			}
			else
			{
				$sql .= "ORDER BY t.topic_type DESC, t.topic_priority DESC, p.post_time DESC ";
			}
			$sql .= "LIMIT $start, " . $board_config['topics_per_page'];

		if ( !($result = $db->sql_query($sql)) )
		{
	   		message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$topic_title = '';

			if ( $row['topic_status'] == TOPIC_LOCKED )
			{
				$folder_img = $images['folder_locked'];
				$folder_alt = $lang['Topic_locked'];
			}
			else
			{
				if ( $row['topic_type'] == POST_GLOBAL_ANNOUNCE ) 
				{ 
					$folder_img = $images['folder_global_announce']; 
					$folder_alt = $lang['Global_announcement']; 
				}
				else if ( $row['topic_type'] == POST_ANNOUNCE )
				{
					$folder_img = $images['folder_announce'];
					$folder_alt = $lang['Topic_Announcement'];
				}
				else if ( $row['topic_type'] == POST_STICKY )
				{
					$folder_img = $images['folder_sticky'];
					$folder_alt = $lang['Topic_Sticky'];
				}
				else 
				{
					$folder_img = $images['folder'];
					$folder_alt = $lang['No_new_posts'];
				}
			}

			$topic_id = $row['topic_id'];
			$topic_type = $row['topic_type'];
			$topic_status = $row['topic_status'];
			$topic_priority = $row['topic_priority'];
			$topic_title = $row['topic_title'];
		
			if ( $topic_type == POST_GLOBAL_ANNOUNCE ) 
			{ 
				$topic_type = $lang['Topic_global_announcement'] . ' '; 
			}
			else if ( $topic_type == POST_ANNOUNCE )
			{
				$topic_type = $lang['Topic_Announcement'] . ' ';
			}
			else if ( $topic_type == POST_STICKY )
			{
				$topic_type = $lang['Topic_Sticky'] . ' ';
			}
			else if ( $topic_status == TOPIC_MOVED )
			{
				$topic_type = $lang['Topic_Moved'] . ' ';
			}
			else if ( $topic_status == TOPIC_LINKED )
			{
				$topic_type = $lang['Topic_Linked'] . ' ';
			}
			else
			{
				$topic_type = '';		
			}
	
			if ( $row['topic_vote'] )
			{
				$topic_type .= $lang['Topic_Poll'] . ' ';
			}

			if ( !empty($orig_word) )
			{
				$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
			}
	
	        $topic_title = capitalization($topic_title);

			if ($board_config['enable_quick_titles'])
			{
				if ($row['title_pos'] )
				{
					$topic_title = (empty($row['title_compl_infos'])) ? $topic_title : $topic_title . ' <span style="color: #' . $row['title_compl_color'] . '">' . $row['title_compl_infos'] . '</span>';
				}
				else
				{
					$topic_title = (empty($row['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $row['title_compl_color'] . '">' . $row['title_compl_infos'] . '</span> ' . $topic_title;
				}
			}
			
			$u_view_topic = "modcp.$phpEx?mode=split&amp;" . POST_TOPIC_URL . "=$topic_id&amp;sid=" . $userdata['session_id'];
			$topic_replies = $row['topic_replies'];

			$last_post_time = create_date($board_config['default_dateformat'], $row['post_time'], $board_config['board_timezone']);
			$first_post_time = create_date($board_config['default_dateformat'], $row['topic_time'], $board_config['board_timezone']);
			$username = username_level_color($row['username'], $row['user_level'], $row['user_id']);

			$template->assign_block_vars('topicrow', array(
				'U_VIEW_TOPIC' => $u_view_topic,

				'TOPIC_FOLDER_IMG' => $folder_img, 
				'TOPIC_TYPE' => $topic_type, 
				'TOPIC_TITLE' => $topic_title,
				'REPLIES' => $topic_replies,
				'LAST_POST_TIME' => $last_post_time,
				'FIRST_POST_TIME' => $first_post_time,
				'TOPIC_ID' => $topic_id,
				'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($row['topic_attachment']),
				'TOPIC_PRIORITY' => $topic_priority,
				'USERNAME' => $username,

				'L_TOPIC_STARTED' => $lang['Topic_started'],
				'L_TOPIC_FOLDER_ALT' => $folder_alt)
			);
		}
		$db->sql_freeresult($result);

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination("modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'], $forum_topics, $board_config['topics_per_page'], $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $forum_topics / $board_config['topics_per_page'] )), 
			'L_MARK_ALL' => $lang['Mark_all'], 
			'L_UNMARK_ALL' => $lang['Unmark_all'],
			'L_GOTO_PAGE' => $lang['Goto_page'])
		);

		if ( $board_config['enable_quick_titles'] )
		{
			$template->assign_block_vars('switch_quick_title', array(
				'L_EDIT_TITLE' => $lang['Edit_title'])
			);
		}


		if ( $board_config['bin_forum'] )
		{
			$template->assign_block_vars('switch_bin', array(
				'L_RECYCLE' => $lang['Bin_recycle'])
			);
		}

		$template->pparse('body');

		break;
}

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
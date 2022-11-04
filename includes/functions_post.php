<?php
/** 
*
* @package includes
* @version $Id: functions_post.php,v 1.9.2.37 2004/11/18 17:49:44 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

$html_entities_match = array('#&(?!(\#[0-9]+;))#', '#<#', '#>#', '#"#');
$html_entities_replace = array('&amp;', '&lt;', '&gt;', '&quot;');

$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
$unhtml_specialchars_replace = array('>', '<', '"', '&');

//
// This function will prepare a posted message for
// entry into the database.
//
function prepare_message($message, $html_on, $bbcode_on, $smile_on, $bbcode_uid = 0)
{
	global $board_config, $html_entities_match, $html_entities_replace;

	//
	// Clean up the message
	//
	$message = trim($message);

	if ($html_on)
	{
		// If HTML is on, we try to make it safe
		// This approach is quite agressive and anything that does not look like a valid tag
		// is going to get converted to HTML entities
		$message = stripslashes($message);
		$html_match = '#<[^\w<]*(\w+)((?:"[^"]*"|\'[^\']*\'|[^<>\'"])+)?>#';
		$matches = array();

		$message_split = preg_split($html_match, $message);
		preg_match_all($html_match, $message, $matches);

		$message = '';

		foreach ($message_split as $part)
		{
			$tag = array(array_shift($matches[0]), array_shift($matches[1]), array_shift($matches[2]));
			$message .= preg_replace($html_entities_match, $html_entities_replace, $part) . clean_html($tag);
		}

		$message = addslashes($message);
		$message = str_replace('&quot;', '\&quot;', $message); 
	}
	else
	{
		$message = preg_replace($html_entities_match, $html_entities_replace, $message);
	}

	if($bbcode_on && $bbcode_uid != '')
	{
		$message = bbencode_first_pass($message, $bbcode_uid);
	}

	return $message;
}

function unprepare_message($message)
{
	global $unhtml_specialchars_match, $unhtml_specialchars_replace;

	return preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, $message);
}

//
// Prepare a message for posting
// 
function prepare_post(&$mode, &$post_data, &$bbcode_on, &$html_on, &$smilies_on, &$error_msg, &$username, &$bbcode_uid, &$subject, &$message, &$poll_title, &$poll_options, &$poll_length, &$topic_password)
{
	global $board_config, $userdata, $lang, $phpEx, $phpbb_root_path, $is_auth, $subject_check;

	// Check username
	if (!empty($username))
	{
		$username = phpbb_clean_username($username);

		if (!$userdata['session_logged_in'] || ($userdata['session_logged_in'] && $username != $userdata['username']))
		{
			include($phpbb_root_path . 'includes/functions_validate.'.$phpEx);

			$result = validate_username($username);
			if ($result['error'])
			{
				$error_msg .= (!empty($error_msg)) ? '<br />' . $result['error_msg'] : $result['error_msg'];
			}
		}
		else
		{
			$username = '';
		}
	}

	// Check subject
	if (!empty($subject))
	{
		$subject = htmlspecialchars(trim($subject));
	}
	else if ($mode == 'newtopic' || ($mode == 'editpost' && $post_data['first_post']))
	{
		$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Empty_subject'] : $lang['Empty_subject'];
	}

	if ($board_config['subchk_enable'])
	{
		$subject_check->look_for_posts();
	}
	
	// Check message
	if (!empty($message) || $mode == 'editpost')
	{
		// Check character length against board limits...
  		$message = trim($message);
  		if ( (strlen ($message) > $board_config['message_maxlength']) && ($board_config['message_maxlength'] > 0) )
        {
            $message_too_long = sprintf($lang['Message_Maxlength_error'], $board_config['message_maxlength']);
            $error_msg .= (!empty($error_msg)) ? '<br />' . $message_too_long : $message_too_long;
        }
  		if ( (strlen ($message) < $board_config['message_minlength']) && ($board_config['message_minlength'] > 0) )
        {
            $message_too_short = sprintf($lang['Message_Minlength_error'], $board_config['message_minlength']);
            $error_msg .= (!empty($error_msg)) ? '<br />' . $message_too_short : $message_too_short;
        }

		// Check [img] BBCode tags against board limits... 
		if( preg_match_all("#\[img\]((ht|f)tp://)([^\r\n\t<\"]*?)\[/img\]#sie", $message, $matches) )
		{
			if( count($matches[0]) > $board_config['post_images_max_limit'] )
			{
				$l_too_many_images = ( $board_config['post_images_max_limit'] == 1 ) ? sprintf($lang['Too_many_image'], $board_config['post_images_max_limit']) : sprintf($lang['Too_many_images'], $board_config['post_images_max_limit']);
				$error_msg .= (!empty($error_msg)) ? '<br />' . $l_too_many_images : $l_too_many_images;
			}
			else
			{
				for( $i = 0; $i < count($matches[0]); $i++ )
				{
					$image = preg_replace("#\[img\](.*)\[/img\]#si", "\\1", $matches[0][$i]);
					list($width, $height) = @getimagesize($image);
					if( $width > $board_config['post_images_max_width'] || $height > $board_config['post_images_max_height'] )
					{
						$l_image_too_large = sprintf($lang['Image_too_large'], $board_config['post_images_max_width'], $board_config['post_images_max_height']);
						$error_msg .= (!empty($error_msg)) ? '<br />' . $l_image_too_large : $l_image_too_large;
						break;
					}
				}
			}
		}
	
		$bbcode_uid = ($bbcode_on && preg_match('#\[.+\].*?\[/.+\]#s', $message)) ? make_bbcode_uid() : ''; 
		if (empty($bbcode_uid)) 
		{ 
			$bbcode_uid = ($bbcode_on && preg_match('#\[.+\]#s', $message)) ? make_bbcode_uid() : ''; 
		} 
		
		$message = prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on, $bbcode_uid);

		// Tags [mod] [/mod] are prohibited for everyone except moderators and administrators (post)
		if ( check_mod_tags($is_auth['auth_mod'], $message) )
		{ 
			message_die(GENERAL_MESSAGE, $lang['Mod_reserved'], $lang['Mod_restrictions']);
		} 
	}
	else if ($mode != 'delete' && $mode != 'poll_delete') 
	{
		$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Empty_message'] : $lang['Empty_message'];
	}

	// Check topic password
	if( !empty($topic_password) && !preg_match("#^[A-Za-z0-9]{3,20}$#si", $topic_password) )
	{
		$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Only_alpha_num_chars'] : $lang['Only_alpha_num_chars'];
	}

	//
	// Handle poll stuff
	//
	if ($mode == 'newtopic' || ($mode == 'editpost' && $post_data['first_post']))
	{
		$poll_length = (isset($poll_length)) ? max(0, intval($poll_length)) : 0;

		if (!empty($poll_title))
		{
			$poll_title = htmlspecialchars(trim($poll_title));
		}

		if(!empty($poll_options))
		{
			$temp_option_text = array();
			while(list($option_id, $option_text) = @each($poll_options))
			{
				$option_text = trim($option_text);
				if (!empty($option_text))
				{
					$temp_option_text[intval($option_id)] = htmlspecialchars($option_text);
				}
			}
			$option_text = $temp_option_text;

			if (count($poll_options) < 2)
			{
				$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['To_few_poll_options'] : $lang['To_few_poll_options'];
			}
			else if (count($poll_options) > $board_config['max_poll_options']) 
			{
				$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['To_many_poll_options'] : $lang['To_many_poll_options'];
			}
			else if ($poll_title == '')
			{
				$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Empty_poll_title'] : $lang['Empty_poll_title'];
			}
		}
	}

	return;
}

//
// Daily Post Limit
//
function generate_limit_period($period_id) 
{
	global $db;
			
	$sql = "UPDATE " . USERS_TABLE . " 
		SET daily_post_period = " . time() . ", daily_post_count = 0 
		WHERE user_id = " . $period_id;
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not update daily flood post period', '', __LINE__, __FILE__, $sql);
	}
	
	return 0;
}		

//
// Post a new topic/reply/poll or edit existing post/poll
//
function submit_post($mode, &$post_data, &$post_index, &$message, &$meta, &$forum_id, &$topic_id, &$post_id, &$poll_id, &$topic_type, &$bbcode_on, &$html_on, &$smilies_on, &$attach_sig, &$urgent_post, &$bbcode_uid, $post_username, $post_subject, $post_message, $poll_title, &$poll_options, &$poll_length, &$topic_password, &$msg_icon, &$locktopic)
{
	global $board_config, $lang, $db, $phpbb_root_path, $phpEx;
	global $userdata, $user_ip, $post_info;

	$forum_bumping = 0;
	if ($board_config['stop_bumping'] == 2 && $mode != 'newtopic')
	{
		$sql = "SELECT f.stop_bumping FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t
			WHERE f.forum_id = t.forum_id
				AND t.topic_id = $topic_id";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not fetch bumping status for this forum', '', __LINE__, __FILE__, $sql);
		}
		while ($row = $db->sql_fetchrow($result))
		{
			$forum_bumping = $row['stop_bumping'];
		}
		$db->sql_freeresult($result);
	}

	if (($board_config['stop_bumping'] == 1 || $forum_bumping == 1) && $userdata['user_level'] == USER && ($mode == 'reply' || $mode == 'quote')) 
	{
		$sql = "SELECT p.poster_id FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t
			WHERE t.topic_id = " . $topic_id . "
				AND t.topic_last_post_id = p.post_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not check last poster id', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$checked_user = $row['poster_id'];

		if ($checked_user == $userdata['user_id'])
		{
			message_die(GENERAL_MESSAGE, $lang['Bump_Error']);
		}

		$db->sql_freeresult($result);
	}

	include_once($phpbb_root_path . 'includes/functions_search.'.$phpEx);

	// code to get rid of some quick reply problems 
	if (empty($msg_icon)) 
	{
		$msg_icon = 0; 
	}
	
	$current_time = time();

	//
	// Check for all caps subject. Make first word caps only
	// 
	if ( ereg('^[^[:lower:]]+$', $post_subject) )
	{
		$post_subject = ucwords(strtolower($post_subject));
	}

	if ($mode == 'newtopic' || $mode == 'reply' || $mode == 'editpost') 
	{
		//
		// Flood control
		// No flood control for Admins, Super Mods, or Mods
		//
		if ($userdata['user_level'] < 1)
		{
			$where_sql = ($userdata['user_id'] == ANONYMOUS) ? "poster_ip = '$user_ip'" : 'poster_id = ' . $userdata['user_id'];
			$sql = "SELECT MAX(post_time) AS last_post_time
				FROM " . POSTS_TABLE . "
				WHERE $where_sql";
			if ($result = $db->sql_query($sql))
			{
				if ($row = $db->sql_fetchrow($result))
				{
					if (intval($row['last_post_time']) > 0 && ($current_time - intval($row['last_post_time'])) < intval($board_config['flood_interval']))
					{
						message_die(GENERAL_MESSAGE, $lang['Flood_Error']);
					}
				}
			}
		}
		
		//
		// Daily Post Limit
		//
		if ($board_config['daily_post_limit'])
		{
			// stale period
			if ($userdata['daily_post_period'] != 0 && ((time() - $userdata['daily_post_period']) > 86400 )) 
			{ 
				$userdata['daily_post_limit'] = generate_limit_period($userdata['user_id']);
			}
			// new limit
			if ($userdata['daily_post_period'] == 0 && $userdata['daily_post_limit'] > 0) 
			{ 
				$userdata['daily_post_limit'] = generate_limit_period($userdata['user_id']);
			}
			// if they have reached the limit
			if (($userdata['daily_post_limit'] - $userdata['daily_post_count']) <= 0 && $userdata['daily_post_limit'] != 0) 
			{
				message_die(GENERAL_MESSAGE, $lang['daily_flood_limit'], 'Daily Flood Limit');
			} 
			else 
			{ 
				// if they havent reached the limit
				$sql = "UPDATE " . USERS_TABLE . " 
					SET daily_post_count = daily_post_count + 1 
					WHERE user_id = " . $userdata['user_id'];
				if(!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not update daily flood post count', '', __LINE__, __FILE__, $sql);
				}
			}
		}
	}
	
	if ($mode == 'editpost')
	{
		remove_search_post($post_id);
	}

	if ($mode == 'newtopic' || ($mode == 'editpost' && $post_data['first_post']))
	{
		$topic_vote = (!empty($poll_title) && count($poll_options) >= 2) ? 1 : 0;

		$sql  = ($mode != "editpost") ? "INSERT INTO " . TOPICS_TABLE . " (topic_title, topic_poster, topic_time, forum_id, topic_status, topic_type, topic_vote, topic_password, topic_icon) VALUES ('$post_subject', " . $userdata['user_id'] . ", $current_time, $forum_id, " . (($locktopic) ? TOPIC_LOCKED : TOPIC_UNLOCKED) . ", $topic_type, $topic_vote, '$topic_password', $msg_icon)" : "UPDATE " . TOPICS_TABLE . " SET topic_title = '$post_subject'" . (($locktopic) ? ", topic_status =" . (($locktopic == 2) ? TOPIC_UNLOCKED : TOPIC_LOCKED) : "") . ", topic_type = $topic_type " . (($post_data['edit_vote'] || !empty($poll_title)) ? ", topic_vote = " . $topic_vote : "") . ", topic_password = '$topic_password', topic_icon = $msg_icon WHERE topic_id = $topic_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}

		if ($mode == 'newtopic')
		{
			$topic_id = $db->sql_nextid();
		}
	}

	$avatar_sql = ( ($mode == 'newtopic') || ($mode == 'reply') && ($post_data['poster_id'] == $userdata['user_id']) ) ? ", user_avatar = '" . $userdata['user_avatar'] . "', user_avatar_type = " . $userdata['user_avatar_type'] : '';
	$sql = ($mode != "editpost") ? "INSERT INTO " . POSTS_TABLE . " (topic_id, forum_id, poster_id, post_username, post_time, poster_ip, enable_bbcode, enable_html, enable_smilies, enable_sig, post_icon, user_avatar, user_avatar_type, urgent_post) VALUES ($topic_id, $forum_id, " . $userdata['user_id'] . ", '$post_username', $current_time, '$user_ip', $bbcode_on, $html_on, $smilies_on, $attach_sig, $msg_icon, '" . $userdata['user_avatar'] . "', " . $userdata['user_avatar_type'] . ", $urgent_post)" : "UPDATE " . POSTS_TABLE . " SET post_username = '$post_username', enable_bbcode = $bbcode_on, enable_html = $html_on, enable_smilies = $smilies_on, enable_sig = $attach_sig" . $avatar_sql . ", post_icon = $msg_icon WHERE post_id = $post_id";
	if (!$db->sql_query($sql, BEGIN_TRANSACTION))
	{
		message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
	}

	if ($mode != 'editpost')
	{
		$post_id = $db->sql_nextid();
	}

	$sql = ($mode != 'editpost') ? "INSERT INTO " . POSTS_TEXT_TABLE . " (post_id, post_subject, bbcode_uid, post_text) VALUES ($post_id, '$post_subject', '$bbcode_uid', '$post_message')" : "UPDATE " . POSTS_TEXT_TABLE . " SET post_text = '$post_message',  bbcode_uid = '$bbcode_uid', post_subject = '$post_subject' WHERE post_id = $post_id";
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
	}

	if( $mode == 'editpost' )
	{
		$sql = "SELECT * FROM " . POSTS_EDIT_TABLE . "
			WHERE post_id = $post_id
				AND user_id = " . $userdata['user_id'];
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not retrieve post edit count information', '', __LINE__, __FILE__, $sql);
		}

		$sql = ( $row = $db->sql_fetchrow($result) ) ? "UPDATE " . POSTS_EDIT_TABLE . " SET post_edit_count = post_edit_count + 1, post_edit_time = $current_time WHERE post_id = $post_id AND user_id = " . $userdata['user_id'] : "INSERT INTO " . POSTS_EDIT_TABLE . " (post_id, user_id, post_edit_count, post_edit_time) VALUES ($post_id, " . $userdata['user_id'] . ", 1, $current_time)";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update post edit count information', '', __LINE__, __FILE__, $sql);
		}
	}

	if ($post_index)
	{
		add_search_words('single', $post_id, stripslashes($post_message), stripslashes($post_subject));
	}


	//
	// Add poll
	// 
	if (($mode == 'newtopic' || ($mode == 'editpost' && $post_data['edit_poll'])) && !empty($poll_title) && count($poll_options) >= 2)
	{
		$sql = (!$post_data['has_poll']) ? "INSERT INTO " . VOTE_DESC_TABLE . " (topic_id, vote_text, vote_start, vote_length) VALUES ($topic_id, '$poll_title', $current_time, " . ($poll_length * 86400) . ")" : "UPDATE " . VOTE_DESC_TABLE . " SET vote_text = '$poll_title', vote_length = " . ($poll_length * 86400) . " WHERE topic_id = $topic_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}

		$delete_option_sql = '';
		$old_poll_result = array();
		if ($mode == 'editpost' && $post_data['has_poll'])
		{
			$sql = "SELECT vote_option_id, vote_result  
				FROM " . VOTE_RESULTS_TABLE . " 
				WHERE vote_id = $poll_id 
				ORDER BY vote_option_id ASC";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain vote data results for this topic', '', __LINE__, __FILE__, $sql);
			}

			while ($row = $db->sql_fetchrow($result))
			{
				$old_poll_result[$row['vote_option_id']] = $row['vote_result'];

				if (!isset($poll_options[$row['vote_option_id']]))
				{
					$delete_option_sql .= ($delete_option_sql != '') ? ', ' . $row['vote_option_id'] : $row['vote_option_id'];
				}
			}
			$db->sql_freeresult($result);
		}
		else
		{
			$poll_id = $db->sql_nextid();
		}

		@reset($poll_options);

		$poll_option_id = 1;
		while (list($option_id, $option_text) = each($poll_options))
		{
			if (!empty($option_text))
			{
				$option_text = str_replace("\'", "''", htmlspecialchars($option_text));
				$poll_result = ($mode == "editpost" && isset($old_poll_result[$option_id])) ? $old_poll_result[$option_id] : 0;

				$sql = ($mode != "editpost" || !isset($old_poll_result[$option_id])) ? "INSERT INTO " . VOTE_RESULTS_TABLE . " (vote_id, vote_option_id, vote_option_text, vote_result) VALUES ($poll_id, $poll_option_id, '$option_text', $poll_result)" : "UPDATE " . VOTE_RESULTS_TABLE . " SET vote_option_text = '$option_text', vote_result = $poll_result WHERE vote_option_id = $option_id AND vote_id = $poll_id";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
				}
				$poll_option_id++;
			}
		}

		if ($delete_option_sql != '')
		{
			$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " 
				WHERE vote_option_id IN ($delete_option_sql) 
					AND vote_id = $poll_id";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Error deleting pruned poll options', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	if ( $board_config['topic_redirect'] )
	{
		$meta = '<meta http-equiv="refresh" content="3;url=' . append_sid('viewtopic.'.$phpEx.'?' . POST_POST_URL . '=' . $post_id) . '">';
	}
	else
	{
		$meta = '<meta http-equiv="refresh" content="3;url=' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . '=' . $forum_id) . '">';
	}

	if ($urgent_post) 
    { 
		$sql = "UPDATE " . DIGEST_CONFIG_TABLE . "
			SET config_value = 1
			WHERE config_name = 'urgent_run_required'";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error updating digest config table', '', __LINE__, __FILE__, $sql);
		}
	
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_digest.'.$phpEx);
	  
        $message = $lang['Urgent_stored'] . '<br /><br />' . sprintf($lang['Click_view_message'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_POST_URL . "=" . $post_id) . '#' . $post_id . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>'); 
	}    
   	else 
   	{ 
		$message = $lang['Stored'] . '<br /><br />' . sprintf($lang['Click_view_message'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_POST_URL . "=" . $post_id) . '#' . $post_id . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');
	}
	
	if ($board_config['points_post'] && !$post_info['points_disabled'])
	{
		if ($mode != 'editpost') 
		{
			$points = abs(($mode == 'newtopic') ? $board_config['points_topic'] : $board_config['points_reply']);
			if ($userdata['user_id'] != ANONYMOUS)
			{
				add_points($userdata['user_id'], $points);
			}
		}
	}

	return false;
}

//
// Update post stats and details
//
function update_post_stats(&$mode, &$post_data, &$forum_id, &$topic_id, &$post_id, &$user_id)
{
	global $db, $board_config;

	$sign = ($mode == 'delete') ? '- 1' : '+ 1';
	$forum_update_sql = "forum_posts = forum_posts $sign";
	$topic_update_sql = '';

	if ($mode == 'delete')
	{
		if ($post_data['last_post'])
		{
			if ($post_data['first_post'])
			{
				$forum_update_sql .= ', forum_topics = forum_topics - 1';
			}
			else
			{

				$topic_update_sql .= 'topic_replies = topic_replies - 1';

				$sql = "SELECT MAX(post_id) AS last_post_id
					FROM " . POSTS_TABLE . " 
					WHERE topic_id = $topic_id";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
				}

				if ($row = $db->sql_fetchrow($result))
				{
					$topic_update_sql .= ', topic_last_post_id = ' . $row['last_post_id'];
				}
			}

			if ($post_data['last_topic'])
			{
				$sql = "SELECT MAX(post_id) AS last_post_id
					FROM " . POSTS_TABLE . " 
					WHERE forum_id = $forum_id"; 
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
				}

				if ($row = $db->sql_fetchrow($result))
				{
					$forum_update_sql .= ($row['last_post_id']) ? ', forum_last_post_id = ' . $row['last_post_id'] : ', forum_last_post_id = 0';
				}
			}
		}
		else if ($post_data['first_post']) 
		{
			$sql = "SELECT MIN(post_id) AS first_post_id
				FROM " . POSTS_TABLE . " 
				WHERE topic_id = $topic_id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				$topic_update_sql .= 'topic_replies = topic_replies - 1, topic_first_post_id = ' . $row['first_post_id'];
			}
		}
		else
		{
			$topic_update_sql .= 'topic_replies = topic_replies - 1';
		}
	}
	else if ($mode != 'poll_delete')
	{
		$forum_update_sql .= ", forum_last_post_id = $post_id" . (($mode == 'newtopic') ? ", forum_topics = forum_topics $sign" : ""); 
		$topic_update_sql = "topic_last_post_id = $post_id" . (($mode == 'reply') ? ", topic_replies = topic_replies $sign" : ", topic_first_post_id = $post_id");
	}
	else 
	{
		$topic_update_sql .= 'topic_vote = 0';
	}

	//
	// Get the parent_forum_id list to update the complete hierarchie as needed
	//		
	$sql = "SELECT f.forum_id
		FROM " . FORUMS_TABLE . " f, " . FORUMS_TABLE . " f2, " . CAT_REL_FORUM_PARENTS_TABLE . " cfp
		WHERE cfp.parent_forum_id = f.forum_id
		AND cfp.cat_id = f2.cat_id
		AND f2.forum_id = " . $forum_id;
	
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query parent_forum_id list to update', '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) ) 
	{
		$forum_parent_ids_list[] = $row['forum_id'];
		$forum_parent_ids .= ", " . $row['forum_id'];
	}
	
	if ($mode != 'poll_delete')
	{
		$sql = "UPDATE " . FORUMS_TABLE . " SET 
			$forum_update_sql 
			WHERE forum_id IN ($forum_id$forum_parent_ids)";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}
	}
	
	// Set correct last_post_id of parent forums
	if ( $mode == 'delete' && $post_data['last_post'] && $post_data['last_topic'])
	{
		for ( $i = 0; $i < count($forum_parent_ids_list); $i++)
		{
			// first get cat_id of forum
			$sql = "SELECT cat_id
				FROM " . FORUMS_TABLE . "
				WHERE forum_id = $forum_parent_ids_list[$i]";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not get cat_id of forum', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			
			// now get all cats junior to this
			$sql = "SELECT cat_id
				FROM " . CAT_REL_CAT_PARENTS_TABLE . "
				WHERE parent_cat_id = " . $row['cat_id'];
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not get junior cats', '', __LINE__, __FILE__, $sql);
			}
			while ( $row = $db->sql_fetchrow($result) )
			{
				$child_cat_ids .= $row['cat_id'] . ',';
			}
			$child_cat_ids = substr($child_cat_ids, 0, -1);

			// now get all forums junior to the just found cats
			$sql = "SELECT forum_id, forum_issub
				FROM " . FORUMS_TABLE . "
				WHERE cat_id IN ($child_cat_ids)";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not get junior forums', '', __LINE__, __FILE__, $sql);
			}
			while ( $row = $db->sql_fetchrow($result) )
			{
				if( $row['forum_issub'] == FORUM_ISNOSUB )
				{
					$forum_ids .= $row['forum_id'] . ',';
				}
			}
			$forum_ids = substr($forum_ids, 0, -1);

			// now do the main work
			$sql = "SELECT MAX(post_id) AS post_id
				FROM " . POSTS_TABLE . "
				WHERE forum_id IN ($forum_ids)"; 
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
			}

			if ( $row = $db->sql_fetchrow($result) )
			{
				$forum_update_sql = ( $row['post_id'] ) ? 'forum_last_post_id = ' . $row['post_id'] : 'forum_last_post_id = 0';
				$sql = "UPDATE " . FORUMS_TABLE . " SET
					$forum_update_sql
					WHERE forum_id = " . $forum_parent_ids_list[$i];
				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
				}
			}
		}
	}

	if ($topic_update_sql != '')
	{
		$sql = "UPDATE " . TOPICS_TABLE . " SET 
			$topic_update_sql 
			WHERE topic_id = $topic_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}
	}

	if( strstr($board_config['no_post_count_forum_id'], ',') )
	{
		$fids = explode(',', $board_config['no_post_count_forum_id']);
	
		while( list($foo, $id) = each($fids) )
		{
			$fid[] = intval( trim($id) );
		}
	}
	else
	{
		$fid[] = intval( trim($board_config['no_post_count_forum_id']) );
	}
	reset($fid);

	if ( $mode != 'poll_delete' && in_array($forum_id, $fid) == false )
	{
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_posts = user_posts $sign 
			WHERE user_id = $user_id";
		if (!$db->sql_query($sql, END_TRANSACTION))
		{
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}
	}

	return;
}

//
// Delete a post/poll
//
function delete_post($mode, &$post_data, &$message, &$meta, &$forum_id, &$topic_id, &$post_id, &$poll_id)
{
	global $board_config, $lang, $db, $phpbb_root_path, $phpEx;
	global $userdata, $user_ip;

	if ($mode != 'poll_delete')
	{
		include($phpbb_root_path . 'includes/functions_search.'.$phpEx);

		$sql = "DELETE FROM " . POSTS_TABLE . " 
			WHERE post_id = $post_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . POSTS_TEXT_TABLE . " 
			WHERE post_id = $post_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
		}

		if ($post_data['last_post'])
		{
			if ($post_data['first_post'])
			{
				$forum_update_sql .= ', forum_topics = forum_topics - 1';
				$sql = "DELETE FROM " . TOPICS_TABLE . " 
					WHERE topic_id = $topic_id 
						OR topic_moved_id = $topic_id";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . THANKS_TABLE . "
					WHERE topic_id = $topic_id";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Error in deleting thanks', '', __LINE__, __FILE__, $sql);
				}

				$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
					WHERE topic_id = $topic_id";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Error in deleting topic watches', '', __LINE__, __FILE__, $sql);
				}
			}
		}

		remove_search_post($post_id);
	}

	if ($mode == 'poll_delete' || ($mode == 'delete' && $post_data['first_post'] && $post_data['last_post']) && $post_data['has_poll'] && $post_data['edit_poll'])
	{
		$sql = "DELETE FROM " . VOTE_DESC_TABLE . " 
			WHERE topic_id = $topic_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in deleting poll', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " 
			WHERE vote_id = $poll_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in deleting poll', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . VOTE_USERS_TABLE . " 
			WHERE vote_id = $poll_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in deleting poll', '', __LINE__, __FILE__, $sql);
		}
	}

	if ($board_config['points_post'] && !$post_info['points_disabled'] && ($mode == 'delete' || $mode == 'poll_delete') )
	{
		if (($userdata['user_id'] == $post_data['first_post']) && (($userdata['user_id'] != ANONYMOUS) && ($userdata['admin_allow_points'])))
		{
			subtract_points($userdata['user_id'], $board_config['points_topic']);
		}
		else if (($userdata['user_id'] != ANONYMOUS) && ($userdata['admin_allow_points']))
		{
			subtract_points($userdata['user_id'], $board_config['points_reply']);
		}
	}
	
	if ($mode == 'delete' && $post_data['first_post'] && $post_data['last_post'])
	{
		$meta = '<meta http-equiv="refresh" content="3;url=' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . '=' . $forum_id) . '">';
		$message = $lang['Deleted'];
	}
	else
	{
		$meta = '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . '=' . $topic_id) . '">';
		$message = (($mode == 'poll_delete') ? $lang['Poll_delete'] : $lang['Deleted']) . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">', '</a>');
	}

	$message .=  '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');

	return;
}

//
// Handle user notification on new post
//
function user_notification($mode, &$post_data, &$topic_title, &$forum_id, &$topic_id, &$post_id, &$notify_user)
{
	global $board_config, $lang, $db, $phpbb_root_path, $phpEx;
	global $userdata, $user_ip;

	$current_time = time();

	if ($mode != 'delete')
	{
		if ( $mode == 'reply' || $mode == 'newtopic')
		{
			$sql = "SELECT ban_userid 
				FROM " . BANLIST_TABLE;
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain banlist', '', __LINE__, __FILE__, $sql);
			}

			$user_id_sql = '';
			while ($row = $db->sql_fetchrow($result))
			{
				if (isset($row['ban_userid']) && !empty($row['ban_userid']))
				{
					$user_id_sql .= ', ' . $row['ban_userid'];
				}
			}

			$sql = "SELECT u.user_id, u.user_email, u.user_lang 
				FROM " . TOPICS_WATCH_TABLE . " tw, " . USERS_TABLE . " u 
				WHERE tw.topic_id = $topic_id 
					AND tw.user_id NOT IN (" . $userdata['user_id'] . ", " . ANONYMOUS . $user_id_sql . ") 
					AND tw.notify_status = " . TOPIC_WATCH_UN_NOTIFIED . " 
					AND u.user_id = tw.user_id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain list of topic watchers', '', __LINE__, __FILE__, $sql);
			}

			$update_watched_sql = '';
			$bcc_list_ary = array();

			if ($row = $db->sql_fetchrow($result))
			{
				// Sixty second limit
				@set_time_limit(60);

				do
				{
					if ($row['user_email'] != '')
					{
						$bcc_list_ary[$row['user_lang']][] = $row['user_email'];
					}
					$update_watched_sql .= ($update_watched_sql != '') ? ', ' . $row['user_id'] : $row['user_id'];
				}
				while ($row = $db->sql_fetchrow($result));

				//
				// Let's do some checking to make sure that mass mail functions
				// are working in win32 versions of php.
				//
				if (preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
				{
					$ini_val = (@phpversion() >= '4.0.0') ? 'ini_get' : 'get_cfg_var';

					// We are running on windows, force delivery to use our smtp functions
					// since php's are broken by default
					$board_config['smtp_delivery'] = 1;
					$board_config['smtp_host'] = @$ini_val('SMTP');
				}

				if (sizeof($bcc_list_ary))
				{
					include($phpbb_root_path . 'includes/emailer.'.$phpEx);
					$emailer = new emailer($board_config['smtp_delivery']);

					$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
					$script_name = ($script_name != '') ? $script_name . '/viewtopic.'.$phpEx : 'viewtopic.'.$phpEx;
					$server_name = trim($board_config['server_name']);
					$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
					$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';

					$orig_word = $replacement_word = array();
					obtain_word_list($orig_word, $replacement_word);

					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);

					$topic_title = (count($orig_word)) ? preg_replace($orig_word, $replacement_word, unprepare_message($topic_title)) : unprepare_message($topic_title);

					@reset($bcc_list_ary);
					while (list($user_lang, $bcc_list) = each($bcc_list_ary))
					{
						$emailer->use_template('topic_notify', $user_lang);

						for ($i = 0; $i < count($bcc_list); $i++)
						{
							$emailer->bcc($bcc_list[$i]);
						}

						// The Topic_reply_notification lang string below will be used
						// if for some reason the mail template subject cannot be read 
						// ... note it will not necessarily be in the posters own language!
						$emailer->set_subject($lang['Topic_reply_notification']); 

						// This is a nasty kludge to remove the username var ... till (if?)
						// translators update their templates
						$emailer->msg = preg_replace('#[ ]?{USERNAME}#', '', $emailer->msg);

						$emailer->assign_vars(array(
							'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "---------- \n" . $board_config['board_email_sig']) : '',
							'SITENAME' => $board_config['sitename'],
							'TOPIC_TITLE' => $topic_title, 
							'POSTER_USERNAME' => $userdata['username'],

							'U_TOPIC' => $server_protocol . $server_name . $server_port . $script_name . '?' . POST_POST_URL . "=$post_id#$post_id",
							'U_STOP_WATCHING_TOPIC' => $server_protocol . $server_name . $server_port . $script_name . '?' . POST_TOPIC_URL . "=$topic_id&unwatch=topic")
						);

						$emailer->send();
						$emailer->reset();
					}
				}
			}
			$db->sql_freeresult($result);

			if ($update_watched_sql != '')
			{
				$sql = "UPDATE " . TOPICS_WATCH_TABLE . "
					SET notify_status = " . TOPIC_WATCH_NOTIFIED . "
					WHERE topic_id = $topic_id
						AND user_id IN ($update_watched_sql)";
				$db->sql_query($sql);
			}
		}

		$sql = "SELECT topic_id 
			FROM " . TOPICS_WATCH_TABLE . "
			WHERE topic_id = $topic_id
				AND user_id = " . $userdata['user_id'];
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain topic watch information', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		if (!$notify_user && !empty($row['topic_id']))
		{
			$sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
				WHERE topic_id = $topic_id
					AND user_id = " . $userdata['user_id'];
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete topic watch information', '', __LINE__, __FILE__, $sql);
			}
		}
		else if ($notify_user && empty($row['topic_id']))
		{
			$sql = "INSERT INTO " . TOPICS_WATCH_TABLE . " (user_id, topic_id, notify_status)
				VALUES (" . $userdata['user_id'] . ", $topic_id, 0)";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert topic watch information', '', __LINE__, __FILE__, $sql);
			}
		}
	}
}

//
// Fill smiley templates (or just the variables) with smileys
// Either in a window or inline
//
function generate_smilies($mode, $page_id, $forum_id = FALSE)
{
	global $db, $board_config, $template, $lang, $images, $theme, $phpEx, $phpbb_root_path;
	global $user_ip, $session_length, $starttime, $userdata, $HTTP_GET_VARS;

	$inline_columns = $board_config['smilie_columns'];
	$inline_rows = $board_config['smilie_rows'];

	$cat_posting = $board_config['smilie_posting'];	// 2 = dropdown, 1 = buttons, 0 = nothing.
	$cat_popup = $board_config['smilie_popup'];	// 2 = dropdown, 1 = buttons, 0 = nothing.
	$cat_buttons = $board_config['smilie_buttons'];	// 2 = icon, 1 = name, 0 = number.
	$randomise = $board_config['smilie_random'];	// 1 = yes, 0 = no.
	$cat_id = ( isset($HTTP_GET_VARS['scid']) ) ? intval($HTTP_GET_VARS['scid']) : FALSE;
	$forum_id = ( isset($HTTP_GET_VARS['fid']) ) ? intval($HTTP_GET_VARS['fid']) : $forum_id;
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;	// For pagination.

	if ($mode == 'window')
	{
		$userdata = session_pagestart($user_ip, $page_id);
		init_userprefs($userdata);

		$gen_simple_header = TRUE;
		$page_title = $lang['Emoticons'];
		if ( defined('IN_ADMIN') )
		{
			include('./page_header_admin.'.$phpEx);
		}
		else
		{
			include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		}

		$template->set_filenames(array(
			'smiliesbody' => 'posting_smilies.tpl')
		);
		
		$sql_select = 'cat_name, cat_order, smilies_popup';
	}
	else
	{
		$sql_select = 'cat_name, description, cat_order, cat_icon_url';
	}

	if( !($userdata['session_logged_in']) && ($forum_id == '999') ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Login_check_pm']); 
	}

	$permissions = ( $userdata['session_logged_in'] ) ? (( $userdata['user_level'] == ADMIN ) ? 'cat_perms <= 40' : (( $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD ) ? 'cat_perms <= 30' : (( $userdata['user_level'] == USER ) ? 'cat_perms <= 20' : 'cat_perms = 10'))) : 'cat_perms = 10';
	$which_forum = ( $forum_id == '999' ) ? "cat_forum LIKE '%999%'" : ( $forum_id && $forum_id != '999' ) ? "cat_forum LIKE '%" . $forum_id . "%'" : "cat_open = 1";

	if( $board_config['smilie_usergroups'] )
	{
		$sql = "SELECT g.group_id
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE ug.user_id = " . $userdata['user_id'] . "  
				AND ug.group_id = g.group_id
				AND g.group_single_user <> " . TRUE;
		if( $result = $db->sql_query($sql) )
		{
			$group_num = 0;
			$array_groups = array();
			while( $row = $db->sql_fetchrow($result) )
			{
				$array_groups[] = $row;
				$group_num++;
			}

			for( $i=0; $i<$group_num; $i++ )
			{
				$which_forum .= " OR cat_group LIKE '%" . $array_groups[$i]['group_id'] . "%'";
			}
		}
	}

	$which_forum .= ( $forum_id && $forum_id != '999' ) ? (( $userdata['session_logged_in'] ) ? (( $userdata['user_level'] == ADMIN ) ? '' : (( $userdata['user_level'] == MOD ) ? ' AND (cat_special = ' . $userdata['user_level'] . ' OR cat_special = -2)' : (( $userdata['user_level'] == USER ) ? ' AND (cat_special = ' . $userdata['user_level'] . ' OR cat_special = -2)' : ' AND cat_special = -2'))) : ' AND cat_special = -2') : '';

	$sql = "SELECT $sql_select
		FROM " . SMILIES_CAT_TABLE . "
		WHERE $permissions
			AND $which_forum
		ORDER BY cat_order";
	if ($result = $db->sql_query($sql))
	{
		if( $total_cats = $db->sql_numrows($result) )
		{
			$cat_count = 0;
			$rowset = $array_order = array();
			while ($row1 = $db->sql_fetchrow($result))
			{
				$array_order[$row1['cat_order']] = $cat_count;
				$rowset[$cat_count]['cat_name'] = htmlspecialchars(str_replace("'", "\\'", str_replace('\\', '\\\\', $row1['cat_name'])));
				$rowset[$cat_count]['cat_order'] = $row1['cat_order'];
				
				if( $mode == 'window' )
				{
					$rowset[$cat_count]['smilies_popup'] = $row1['smilies_popup'];
				}
				if( $mode == 'inline' )
				{
					if( $cat_buttons == 2 )
					{
						$rowset[$cat_count]['cat_icon_url'] = $row1['cat_icon_url'];
					}
					if( $cat_posting )
					{
						$rowset[$cat_count]['description'] = htmlspecialchars(str_replace("'", "\\'", str_replace('\\', '\\\\', $row1['description'])));
					}
				}
				$cat_count++;
			}

			// If $cat_id exists, check the user has permission else use 1st default category.
			if( $cat_id )
			{	
				for($i = 0; $i < $cat_count; $i++ )
				{
					if( $rowset[$i]['cat_order'] == $cat_id )
					{
						$cat = $cat_id;
						break;
					}
				}
				if( !$cat )
				{
					$cat = $rowset[0]['cat_order'];
				}
			}
			else
			{
				$cat = $rowset[0]['cat_order'];
			}
			
			$sql2 = "SELECT code, smile_url, emoticon
				FROM " . SMILIES_TABLE . "
				WHERE cat_id = $cat
				ORDER BY smilies_order";
			if( $result2 = $db->sql_query($sql2) )
			{
				$num_smilies = 0;
				$rowset2 = $rowset3 = array();
				while( $row2 = $db->sql_fetchrow($result2) )
				{
					if( empty($rowset3[$row2['smile_url']]) )
					{
						$rowset3[$row2['smile_url']] = $row2['smile_url'];

						$rowset2[$num_smilies]['smile_url'] = $row2['smile_url'];
						$rowset2[$num_smilies]['code'] = str_replace("'", "\\'", str_replace('\\', '\\\\', $row2['code']));
						$rowset2[$num_smilies]['emoticon'] = $row2['emoticon'];
						
						$num_smilies++;
					} 
				}
				unset($rowset3);
				
				list($width, $height, $group_columns, $list_columns, $smiley_group, $smilies_per_page) = explode("|", $rowset[$array_order[$cat]]['smilies_popup']);

				if( $num_smilies )
				{
					// Calculations for pagination.
					if ( ($mode == 'inline') || ($smilies_per_page == 0) )
					{
						$per_page = $num_smilies;
						$smiley_start = 0;
						$smiley_stop = $num_smilies;
					}
					else
					{
						$per_page = ( $smilies_per_page > $num_smilies ) ? $num_smilies : $smilies_per_page;
						$page_num = ( $start <= 0 ) ? 1 : ($start / $per_page) + 1;
						$smiley_start = ($per_page * $page_num) - $per_page;
						$smiley_stop = ( ($per_page * $page_num) > $num_smilies ) ? $num_smilies : $smiley_start + $per_page;
					}
					if( $mode == 'inline' )
					{
						if( $randomise )
						{
							shuffle($rowset2);
						}
						$smilies_split_row = $inline_columns - 1;
						$inline = TRUE;
					}
					else
					{
						if( $smiley_group && $list_columns != 0 )
						{
							$template->assign_block_vars('smiley_list', array());
							$group = 'smiley_list.';
							$smilies_split_row = $list_columns - 1;
						}
						else
						{
							$template->assign_block_vars('smiley_group', array());
							$group = 'smiley_group.';
							$smilies_split_row = $group_columns - 1;
						}
						$inline = FALSE;
					}

					$s_colspan = $row = $col = 0;

					// Start outputting the smilies.
					for($i = $smiley_start; $i < $smiley_stop; $i++)
					{	
						if( !$col )
						{
							$template->assign_block_vars($group . 'smilies_row', array());
						}

						$template->assign_block_vars($group . 'smilies_row.smilies_col', array(
							'SMILEY_CODE' => $rowset2[$i]['code'],
							'SMILEY_CODE2' => str_replace("\\", "", $rowset2[$i]['code']),
							'SMILEY_IMG' => $board_config['smilies_path'] . '/' . $rowset2[$i]['smile_url'],
							'SMILEY_DESC' => $rowset2[$i]['emoticon'])
						);

						$s_colspan = max($s_colspan, $col + 1);

						if( $col == $smilies_split_row )
						{
							if( ($inline && $row == $inline_rows - 1) || (!$inline && $row == $per_page) )
							{
								break;
							}
							$col = 0;
							$row++;
						}
						else
						{
							$col++;
						}

					}
					if( !$inline && $smiley_group && ($list_columns != 0) && ($col != '0') && ($col < $per_page) && ($row != '0') )
					{
						$template->assign_block_vars('smiley_list.smilies_row.smilies_odd', array(
							'S_SMILIES_ODD_COLSPAN' => ($list_columns - $col) * 2)
						);
					}						
				}

				$template->assign_vars(array(
					'L_EMOTICONS' => $lang['Emoticons'], 
					'S_SMILIES_COLSPAN' =>  ( $num_smilies ) ? $s_colspan : 1)
				);
			} 
			// End - $result2 = $db->sql_query($sql2)

			// Display the categories.
			if( ($cat_posting && ($mode == 'inline') && ($cat_count != 1)) || ($cat_popup && ($mode == 'window') && ($cat_count != 1)) )
			{	
				$template->assign_block_vars('smiley_category', array());

				$template->assign_vars(array(
					'L_SMILEY_CATEGORIES' => $lang['smiley_categories'])
				);

				// Do buttons.
				if( (($cat_posting == 1) && ($mode == 'inline')) || (($cat_popup == 1) && ($mode == 'window')) )
				{	
					for( $i=0; $i < $cat_count; $i++ )
					{
						$j = $i+1;
						if( $mode == 'inline' )
						{
							$template->assign_block_vars('category_help', array(
								'NAME' => 'cat' . $j,
								'HELP' => $rowset[$i]['description'])
							);
						}
						
						// What to put on the buttons, a number or a name or an image?
						$button_class = ($theme['template_name'] == 'prosilver') ? 2 : '';
						
						$value = ( $cat_buttons == 0 ) ? 'value=" ' . $j . ' "' : (( $cat_buttons == 1 ) ? 'value="' . $rowset[$i]['cat_name'] . '"' : ( $cat_buttons == 2 ) ? (( $rowset[$i]['cat_icon_url'] ) ? 'src="' . $phpbb_root_path . $board_config['smilie_icon_path'] . '/' . $rowset[$i]['cat_icon_url'] . '"' : $value = 'value="' . $rowset[$i]['cat_name'] . '"') : 'value=" ' . $j . ' "');
						$type = ( ($cat_buttons == 0) || ($cat_buttons == 1) || ($cat_buttons == 2 && !$rowset[$i]['cat_icon_url']) ) ? 'type="button" class="button' . $button_class . '" title="' . $rowset[$i]['description'] . '"' : 'type="image" title="' . $rowset[$i]['description'] . '"';

						$template->assign_block_vars('smiley_category.buttons', array(
							'VALUE' => $value,
							'TYPE' => $type,
							'NAME' => 'cat' . $j,
							'CAT_MORE_SMILIES' => ( $forum_id ) ? append_sid("posting.$phpEx?mode=smilies&amp;scid=" . $rowset[$i]['cat_order'] . "&amp;fid=" . $forum_id) : append_sid("posting.$phpEx?mode=smilies&amp;scid=" . $rowset[$i]['cat_order']))
						);
					}
				}
				else if( (($cat_posting == 2) && ($mode == 'inline')) || (($cat_popup == 2) && ($mode == 'window')) )
				{	// Do dropdown menu.
					if( $mode == 'inline' )
					{
						$template->assign_block_vars('category_help', array(
							'NAME' => 'smile_cats',
							'HELP' => $lang['smiley_help'])
						);
					}

					$select_menu = ( $mode == 'inline' ) ? '<select name="cat" onChange="window.open(this.options[this.selectedIndex].value, \'_phpbbsmilies\', \'HEIGHT=300,resizable=yes,scrollbars=yes,WIDTH=410\'); return false;" onMouseOver="helpline(\'smile_cats\')">' : '<select name="cat" onChange="location.href=this.options[this.selectedIndex].value;">';
					$select_menu .= ( $forum_id ) ? '<option value="' . append_sid("posting.$phpEx?mode=smilies&amp;scid=" . $cat . "&amp;fid=" . $forum_id) . '">' . $lang['Select'] . '</option>' : '<option value="' . append_sid("posting.$phpEx?mode=smilies&amp;scid=" . $cat) . '">' . $lang['Select'] . '</option>';

					for( $i=0; $i<$cat_count; $i++ )
					{
						$selected = ( $rowset[$i]['cat_order'] == $cat_id ) ? ' selected="selected"' : '';
						$select_menu .= ( $forum_id ) ? '<option value="' . append_sid("posting.$phpEx?mode=smilies&amp;scid=" . $rowset[$i]['cat_order'] . "&amp;fid=" . $forum_id) . '"' . $selected . '>' . $rowset[$i]['cat_name'] . '</option>' : '<option value="' . append_sid("posting.$phpEx?mode=smilies&amp;scid=" . $rowset[$i]['cat_order']) . '"' . $selected . '>' . $rowset[$i]['cat_name'] . '</option>';
					}

					$select_menu .= '</select>';

					$template->assign_block_vars('smiley_category.dropdown', array(
						'OPTIONS' => $select_menu)
					);
				}
			}
			else
			{	// Don't display any categories.
				if( $mode == 'inline')
				{	// If categories for posting are 'off' then display 'more emoticons' text link,
					// but only if categories for the popup window are 'on' or total smilies is greater
					// than what is displayed in the inline block.
					if( $cat_popup || ($num_smilies > $inline_rows * $inline_columns)  )
					{
						$template->assign_block_vars('switch_smilies_extra', array());

						$template->assign_vars(array(
							'L_MORE_SMILIES' => $lang['More_emoticons'], 
							'U_MORE_SMILIES' => ( $forum_id ) ? append_sid("posting.$phpEx?mode=smilies&amp;scid=" . $cat . "&amp;fid=" . $forum_id) : append_sid("posting.$phpEx?mode=smilies&amp;scid=" . $cat))
						);
					}
				}
			} 
			// End - displaying categories.

			if( $mode == 'window' || $mode == 'window_announcement_text' )
			{
				$pagination = ( $num_smilies ) ? (( $forum_id ) ? generate_pagination("posting.$phpEx?mode=smilies&amp;scid=$cat&amp;fid=$forum_id", $num_smilies, $per_page, $start, FALSE) : generate_pagination("posting.$phpEx?mode=smilies&amp;scid=$cat", $num_smilies, $per_page, $start, FALSE)) : '';

				$template->assign_vars(array(
					'L_CLOSE_WINDOW' => $lang['Close_window'], 
					'S_WIDTH' =>  $width,
					'S_HEIGHT' =>  $height,
					'PAGINATION' => $pagination)
				);

				$template->pparse('smiliesbody');
				
				include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
			}
		} // End - if( $total_cats )
		else
		{
			$template->assign_vars(array(
				'L_EMOTICONS' => $lang['Emoticons'], 
				'S_SMILIES_COLSPAN' => 1)
			);
		}
	} 
	// End - $result = $db->sql_query($sql)
}

/**
* Called from within prepare_message to clean included HTML tags if HTML is
* turned on for that post
* @param array $tag Matching text from the message to parse
*/
function clean_html($tag)
{
	global $board_config;

	if (empty($tag[0]))
	{
		return '';
	}

	$allowed_html_tags = preg_split('/, */', strtolower($board_config['allow_html_tags']));
	$disallowed_attributes = '/^(?:style|on)/i';

	// Check if this is an end tag
	preg_match('/<[^\w\/]*\/[\W]*(\w+)/', $tag[0], $matches);
	if (sizeof($matches))
	{
		if (in_array(strtolower($matches[1]), $allowed_html_tags))
		{
			return  '</' . $matches[1] . '>';
		}
		else
		{
			return  htmlspecialchars('</' . $matches[1] . '>');
		}
	}

	// Check if this is an allowed tag
	if (in_array(strtolower($tag[1]), $allowed_html_tags))
	{
		$attributes = '';
		if (!empty($tag[2]))
		{
			preg_match_all('/[\W]*?(\w+)[\W]*?=[\W]*?(["\'])((?:(?!\2).)*)\2/', $tag[2], $test);
			for ($i = 0; $i < sizeof($test[0]); $i++)
			{
				if (preg_match($disallowed_attributes, $test[1][$i]))
				{
					continue;
				}
				$attributes .= ' ' . $test[1][$i] . '=' . $test[2][$i] . str_replace(array('[', ']'), array('&#91;', '&#93;'), htmlspecialchars($test[3][$i])) . $test[2][$i];
			}
		}
		if (in_array(strtolower($tag[1]), $allowed_html_tags))
		{
			return '<' . $tag[1] . $attributes . '>';
		}
		else
		{
			return htmlspecialchars('<' . $tag[1] . $attributes . '>');
		}
	}
	// Finally, this is not an allowed tag so strip all the attibutes and escape it
	else
	{
		return htmlspecialchars('<' .   $tag[1] . '>');
	}
}

?>
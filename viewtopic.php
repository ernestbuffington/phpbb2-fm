<?php
/** 
*
* @package phpBB
* @version $Id: viewtopic.php,v 1.186.2.43 2005/07/19 20:01:21 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'lgf-reflog.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include($phpbb_root_path . 'includes/functions_rating.'.$phpEx);
include($phpbb_root_path . 'mods/calendar/mycalendar_functions.'.$phpEx);
if ( $board_config['birthday_viewtopic'] ) 
{ 
	include($phpbb_root_path . 'includes/chinese.'.$phpEx);
}

//
// Include additional language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_thread_kicker.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_thread_kicker.' . $phpEx);
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_myinfo.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_myinfo.' . $phpEx);


//
// Activity Hall of Fame
//	
include_once($phpbb_root_path .'includes/functions_amod_plus.'.$phpEx);
$q = "SELECT * 
	FROM " . INA_HOF; 
$r = $db->sql_query($q); 
$hof_data = $db->sql_fetchrowset($r); 

	
//
// Start initial var setup
//
//$topic_id = $post_id = 0; 
unset($topic_id, $post_id);

if ( isset($HTTP_GET_VARS[POST_TOPIC_URL]) )
{
	$topic_id = intval($HTTP_GET_VARS[POST_TOPIC_URL]);
}
else if ( isset($HTTP_GET_VARS['topic']) )
{
	$topic_id = intval($HTTP_GET_VARS['topic']);
}
else if ( $HTTP_GET_VARS['view'] == 'random' ) 
{ 
    $sql = 'SELECT topic_id 
        FROM ' . TOPICS_TABLE . ' 
        ORDER BY RAND() 
        LIMIT 0, 1'; 
    if ( !($result = $db->sql_query($sql)) ) 
    { 
        message_die(GENERAL_ERROR, 'Could not obtain a random topic.', '', __LINE__, __FILE__, $sql); 
    } 
    if ( !($row = $db->sql_fetchrow($result)) ) 
    { 
        message_die(GENERAL_MESSAGE, 'Topic_post_not_exist'); 
    } 
    else 
    { 
        $topic_id = $row['topic_id']; 
    } 
} 

if ( isset($HTTP_GET_VARS[POST_POST_URL]))
{
	$post_id = intval($HTTP_GET_VARS[POST_POST_URL]);
}

if ($board_config['enable_ignore_sigav'])
{
	if ( isset($HTTP_GET_VARS['hide']) ) 
	{ 
	   $hide = htmlspecialchars($HTTP_GET_VARS['hide']); 
	}
	
	if ( isset($HTTP_GET_VARS['hidee_id']) )
	{
		$hidee_id = intval($HTTP_GET_VARS['hidee_id']);
	}
}

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;
$download = ( isset($HTTP_GET_VARS['download']) ) ? intval($HTTP_GET_VARS['download']) : '';

if ( !$topic_id && !$post_id && $HTTP_GET_VARS['view'] != 'random')
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}


//
// Find topic id if user requested a newer
// or older topic
//
if ( isset($HTTP_GET_VARS['view']) && empty($HTTP_GET_VARS[POST_POST_URL]) )
{
	if ( $HTTP_GET_VARS['view'] == 'newest' )
	{
		if ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) || isset($HTTP_GET_VARS['sid']) )
		{
			$session_id = isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) ? $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid'] : $HTTP_GET_VARS['sid'];

			if (!preg_match('/^[A-Za-z0-9]*$/', $session_id)) 
			{
				$session_id = '';
			}

			if ( $session_id )
			{
				$sql = "SELECT p.post_id
					FROM " . POSTS_TABLE . " p, " . SESSIONS_TABLE . " s,  " . USERS_TABLE . " u
					WHERE s.session_id = '$session_id'
						AND u.user_id = s.session_user_id
						AND p.topic_id = $topic_id
						AND p.post_time >= u.user_lastvisit
					ORDER BY p.post_time ASC
					LIMIT 1";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain newer/older topic information', '', __LINE__, __FILE__, $sql);
				}

				if ( !($row = $db->sql_fetchrow($result)) )
				{
					message_die(GENERAL_MESSAGE, 'No_new_posts_last_visit');
				}

				$post_id = $row['post_id'];

				if (isset($HTTP_GET_VARS['sid']))
				{
					redirect("viewtopic.$phpEx?sid=$session_id&" . POST_POST_URL . "=$post_id#$post_id");
				}
				else
				{
					redirect("viewtopic.$phpEx?" . POST_POST_URL . "=$post_id#$post_id");
				}
			}
		}

		redirect(append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id", true));
	}
	else if ( $HTTP_GET_VARS['view'] == 'next' || $HTTP_GET_VARS['view'] == 'previous' )
	{
		$sql_condition = ( $HTTP_GET_VARS['view'] == 'next' ) ? '>' : '<';
		$sql_ordering = ( $HTTP_GET_VARS['view'] == 'next' ) ? 'ASC' : 'DESC';

		$sql = "SELECT t.topic_id
			FROM " . TOPICS_TABLE . " t, " . TOPICS_TABLE . " t2
			WHERE t2.topic_id = $topic_id
				AND t.forum_id = t2.forum_id
				AND t.topic_moved_id = 0
				AND t.topic_last_post_id $sql_condition t2.topic_last_post_id
			ORDER BY t.topic_last_post_id $sql_ordering
			LIMIT 1";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain newer/older topic information", '', __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			$topic_id = intval($row['topic_id']);
		}
		else
		{
			$message = ( $HTTP_GET_VARS['view'] == 'next' ) ? 'No_newer_topics' : 'No_older_topics';

			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

//
// This rather complex gaggle of code handles querying for topics but
// also allows for direct linking to a post (and the calculation of which
// page the post is on and the correct display of viewtopic)
//
$join_sql_table = ( !$post_id ) ? '' : ", " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2 ";
$join_sql = ( !$post_id ) ? "t.topic_id = $topic_id" : "p.post_id = $post_id AND t.topic_id = p.topic_id AND p2.topic_id = p.topic_id AND p2.post_id <= $post_id";
$count_sql = ( !$post_id ) ? '' : ", COUNT(p2.post_id) AS prev_posts";

$order_sql = (!$post_id) ? '' : "GROUP BY p.post_id, t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.image_ever_thumb, f.auth_ban, f.auth_voteban, f.auth_greencard, f.auth_bluecard, f.forum_password, f.stop_bumping ORDER BY p.post_id ASC";

$sql = "SELECT t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, t.answer_status, t.title_compl_infos, t.title_compl_color, t.title_pos, t.topic_password, f.forum_name, f.forum_status, f.forum_id, f.cat_id, f.forum_regdate_limit, f.forum_enter_limit, f.forum_rules, f.answered_enable, f.forum_thank, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.image_ever_thumb, f.auth_ban, f.auth_voteban, f.auth_greencard, f.auth_bluecard, f.forum_password, f.stop_bumping" . $count_sql . "
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f" . $join_sql_table . "
	WHERE $join_sql
		AND f.forum_id = t.forum_id
		$order_sql";
attach_setup_viewtopic_auth($order_sql, $sql);
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain topic information", '', __LINE__, __FILE__, $sql);
}

if ( !($forum_topic_data = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

$forum_id = intval($forum_topic_data['forum_id']);
$topic_id = intval($forum_topic_data['topic_id']);

//
// Start session management
//
setup_forum_style($forum_id);
$userdata = session_pagestart($user_ip, $forum_id, $topic_id);
init_userprefs($userdata);
//
// End session management
//

//
// Start auth check
//
$is_auth = array();
$is_auth = auth(AUTH_ALL, $forum_id, $userdata, $forum_topic_data);

if( !$is_auth['auth_view'] || !$is_auth['auth_read'] )
{
	if ( !$userdata['session_logged_in'] )
	{
		$redirect = ($post_id) ? POST_POST_URL . "=$post_id" : POST_TOPIC_URL . "=$topic_id";
		$redirect .= ($start) ? "&start=$start" : '';
		redirect(append_sid("login.$phpEx?redirect=viewtopic.$phpEx&$redirect", true));
	}

	$message = ( !$is_auth['auth_view'] ) ? $lang['Topic_post_not_exist'] : sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);
	$message .= '<br /><br />' . sprintf($lang['Click_return_subscribe_lw'], '<a href="' . append_sid('lwtopup.'.$phpEx) . '">', '</a>');
	
	message_die(GENERAL_MESSAGE, $message);
}
//
// End auth check
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
		if( $forum_topic_data['topic_password'] != '' )
		{
			password_check('topic', $topic_id, $HTTP_POST_VARS['password'], $redirect);
		}
		else if( $forum_topic_data['forum_password'] != '' )
		{
			password_check('forum', $forum_id, $HTTP_POST_VARS['password'], $redirect);
		}
	}

	if( $forum_topic_data['topic_password'] != '' )
	{
		$passdata = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_tpass']) ) ? unserialize(stripslashes($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_tpass'])) : '';
		if( $passdata[$topic_id] != md5($forum_topic_data['topic_password']) )
		{
			password_box('topic', $redirect);
		}
	}
	else if( $forum_topic_data['forum_password'] != '' )
	{
		$passdata = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_fpass']) ) ? unserialize(stripslashes($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_fpass'])) : '';
		if( $passdata[$forum_id] != md5($forum_topic_data['forum_password']) )
		{
			password_box('forum', $redirect);
		}
	}
}


// 
// Topic kicker
//
if ( $board_config['kicker_view_setting'] == 2 )
{
	if ( $userdata['user_level'] < 1 )
	{
		$sql = "SELECT user_id, kicker 
			FROM " . THREAD_KICKER_TABLE . "
			WHERE topic_id = $topic_id
				AND user_id = " . $userdata['user_id'];
		if ( !($result = $db->sql_query($sql)) )
        {
        	message_die(GENERAL_ERROR, 'Error in getting thread kicker data', '', __LINE__, __FILE__, $sql);
        }
		$row = $db->sql_fetchrow($result);
		
		$tk_kicker_id = $row['kicker'];
		$tk_postid = $row['post_id'];
		
		if ( $row['user_id'] == $userdata['user_id'] )
		{
			$sql_a = "SELECT username 
				FROM " . USERS_TABLE . "
				WHERE user_id = '$tk_kicker_id'";
			if ( !($result_a = $db->sql_query($sql_a)) )
            {
            	message_die(GENERAL_ERROR, 'Error in getting kicker username', '', __LINE__, __FILE__, $sql_a);
            }
			$row_a = $db->sql_fetchrow($result_a);
			
			$kicker_username = $row_a['username'];
			
			$index_redirect = '<meta http-equiv="refresh" content="3;url=' . append_sid("index.$phpEx") . '">';
			
			$message = $lang['tk_userkicked_topic_noview'] . '<a href="' . append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$tk_kicker_id") . '">' . $kicker_username . '</a>' . $lang['tk_userkicked_contact'] . $index_redirect;
			
			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

	
//
// Forum enter limits 
//
// Registration Date
if (($userdata['user_regdate'] > (time() - ($forum_topic_data['forum_regdate_limit'] * 86400))) && $userdata['user_level'] == USER )
{
	message_die(GENERAL_MESSAGE, sprintf($lang['Forum_regdate_limit_error'], $forum_topic_data['forum_name'], $forum_topic_data['forum_regdate_limit']) . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>'));
}
// No. Posts
if ( ( $userdata['user_posts'] < $forum_topic_data['forum_enter_limit'] ) && $userdata['user_level'] == USER )
{
	message_die(GENERAL_MESSAGE, sprintf($lang['Forum_enter_limit_error'], $forum_topic_data['forum_name'], $forum_topic_data['forum_enter_limit']) . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>'));
}


//
// Stop Bumping
//
$stop_bumping = FALSE;
if (($board_config['stop_bumping'] == 1 || ($board_config['stop_bumping'] == 2 && $forum_topic_data['stop_bumping'] == 1)) && $userdata['user_level'] == USER) 
{
	$sql = "SELECT p.poster_id FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t
		WHERE t.topic_id = " . intval($forum_topic_data['topic_id']) . "
			AND t.topic_last_post_id = p.post_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not check last poster id', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$checked_user = $row['poster_id'];

	if ($checked_user == $userdata['user_id'])
	{
		$stop_bumping = TRUE;
	}

	$db->sql_freeresult($result);
}


//
// Download topic
//
if ( $download )
{
	$sql_download = ( $download != -1 ) ? " AND p.post_id = $download " : '';

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

	$sql = "SELECT u.*, p.*, pt.post_text, pt.post_subject, pt.bbcode_uid
		FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
		WHERE p.topic_id = $topic_id
			$sql_download
			AND pt.post_id = p.post_id
			AND u.user_id = p.poster_id
			ORDER BY p.post_time ASC, p.post_id ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not create download stream for post.", '', __LINE__, __FILE__, $sql);
	}

	$download_file = '';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$poster_id = $row['user_id'];
		$poster = ($poster_id == ANONYMOUS) ? $lang['Guest'] : $row['username'];

		$post_date = create_date($board_config['default_dateformat'], $row['post_time'], $board_config['board_timezone']);

		$post_subject = ($row['post_subject'] != '') ? $row['post_subject'] : '';

		$bbcode_uid = $row['bbcode_uid'];
		$message = $row['post_text'];
		$message = strip_tags($message);
		$message = preg_replace("/\[.*?:$bbcode_uid:?.*?\]/si", '', $message);
		$message = preg_replace('/\[url\]|\[\/url\]/si', '', $message);
		$message = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);

		$message = unprepare_message($message);
		$message = preg_replace('/&#40;/', '(', $message);
		$message = preg_replace('/&#41;/', ')', $message);
		$message = preg_replace('/&#58;/', ':', $message);
		$message = preg_replace('/&#91;/', '[', $message);
		$message = preg_replace('/&#93;/', ']', $message);
		$message = preg_replace('/&#123;/', '{', $message);
		$message = preg_replace('/&#125;/', '}', $message);

		if( !empty($orig_word) )
		{
			$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);

			// This has been back-ported from 3.0 CVS
			$message = preg_replace('#(?!<.*)(?<!\w)(' . $highlight_match . ')(?!\w|[^<>]*>)#i', '<b style="color:#'.$theme['fontcolor4'].'">\1</b>', $message);
		}

		$break = "\n\r";
		$line = '-----------------------------------';
		$download_file .= $break . $line . $break . $poster . $break . $post_date . $break . $break . $post_subject . $break . $line . $break . $message . $break;
	}
	$db->sql_freeresult($result);

	$disp_folder = ( $download == -1 ) ? $lang['Topic'] . '_' . $topic_id : $lang['Post'] . '_' . $download;
	$filename = str_replace(' ', '_', $board_config['sitename']) . '_' . $disp_folder . '_' . date('Y-m-d', time()) . '.txt';
	header('Content-Type: text/x-delimtext; name="' . $filename . '"');
	header('Content-Disposition: attachment;filename=' . $filename);
	header('Content-Transfer-Encoding: plain/text');
	header('Content-Length: ' . strlen($download_file));
	print $download_file;
	exit;
}
	

$forum_name = $forum_topic_data['forum_name'];
$forum_rules = $forum_topic_data['forum_rules'];
$topic_title = $forum_topic_data['topic_title'];
$topic_id = intval($forum_topic_data['topic_id']);
$topic_time = $forum_topic_data['topic_time'];
$show_thanks = $forum_topic_data['forum_thank'];

//
// Similar Topics
//
if ($board_config['enable_similar_topics'])
{
	$sql = "SELECT topic_id, forum_id
		FROM " . TOPICS_TABLE . "
		WHERE topic_id != $topic_id
			AND MATCH (topic_title) AGAINST ('" . addslashes($topic_title) . "')
		ORDER BY topic_time DESC 
		LIMIT 0, 5";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get main information for similar topics.', '', __LINE__, __FILE__, $sql);
	}
	
	$similar_topics = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$sis_auth = auth(AUTH_READ, $row['forum_id'], $userdata); 
	 	if ( $sis_auth['auth_read'] ) 
		{ 
		 	$similar_topics[] = $row;
		}
	}
	$db->sql_freeresult($result);
	
	if ( sizeof($similar_topics) > 0 )
	{
		$template->assign_block_vars('similar', array(
	    	'L_SIMILAR' => $lang['Similar'],
	        'L_TOPIC' => $lang['Topic'],
	        'L_AUTHOR' => $lang['Author'],
	        'L_FORUM' =>  $lang['Forum'],
	        'L_REPLIES' => $lang['Replies'],
	        'L_LAST_POST' => $lang['Posted'])
	  	);
	  
		for ($i = 0; $i < sizeof($similar_topics); $i++)
		{
	  		$sql = "SELECT t.*, u.user_id, u.username, u.user_level, f.forum_id, f.forum_name, p.post_time, p.post_username
	  			FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p
	  			WHERE t.topic_id = '" . $similar_topics[$i]['topic_id'] . "'
	  				AND t.forum_id = f.forum_id
	  				AND t.topic_id = p.topic_id 
	  				AND t.topic_poster = u.user_id
  				GROUP BY t.topic_id";
 			if ( !($result = $db->sql_query($sql)) )
 			{
 			 	message_die(GENERAL_ERROR, 'Could not get similar topics', '', __LINE__, __FILE__, $sql);
 			}
 	 
  			while ( $row = $db->sql_fetchrow($result) )
  			{
	  	  		$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_t']) : array();
	 			$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_f']) : array();
  			
  				$similar = $row;
	   	 		$topic_type = ( $similar['topic_type'] == POST_GLOBAL_ANNOUNCE ) ? $lang['Topic_global_announcement'] . ' ': '';
   				$topic_type .= ( $similar['topic_type'] == POST_ANNOUNCE ) ? $lang['Topic_Announcement'] . ' ': '';
				$topic_type .= ( $similar['topic_type'] == POST_STICKY ) ? $lang['Topic_Sticky'] . ' ': '';
				$topic_type .= ( $similar['topic_vote'] ) ? $lang['Topic_Poll'] . ' ': '';
	   			$replies = $similar['topic_replies'];
	   
	   			if( $similar['topic_status'] == TOPIC_LOCKED )
				{
					$folder = $images['folder_locked'];
					$folder_new = $images['folder_locked_new'];
				}
				else if( $similar['topic_type'] == POST_GLOBAL_ANNOUNCE )
				{
					$folder = $images['folder_global_announce']; 
					$folder_new = $images['folder_global_announce_new']; 
				}
				else if( $similar['topic_type'] == POST_ANNOUNCE )
				{
					$folder = $images['folder_announce'];
					$folder_new = $images['folder_announce_new'];
				}
				else if( $similar['topic_type'] == POST_STICKY )
				{
					$folder = $images['folder_sticky'];
					$folder_new = $images['folder_sticky_new'];
				}
				else
				{
					if( $replies >= $board_config['hot_threshold'] )
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
			
		  		if( $userdata['session_logged_in'] )
				{
					if( $similar['post_time'] > $userdata['user_lastvisit'] ) 
					{
						if( !empty($tracking_topics) || !empty($tracking_forums) || isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_f_all']) )
						{
							$unread_topics = true;
							if( !empty($tracking_topics[$topic_id]) )
							{
								if( $tracking_topics[$topic_id] >= $similar['post_time'] )
								{
									$unread_topics = false;
								}
							}
							
							if( !empty($tracking_forums[$forum_id]) )
							{
								if( $tracking_forums[$forum_id] >= $similar['post_time'] )
								{
									$unread_topics = false;
								}
							}
							
							if( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_f_all']) )
							{
								if( $HTTP_COOKIE_VARS[$board_config['cookie_name'] .'_f_all'] >= $similar['post_time'] )
								{
									$unread_topics = false;
								}
							}
		
							if( $unread_topics )
							{
								$folder_image = $folder_new;
								$folder_alt = $lang['New_posts'];
								$newest_img = '<a href="' . append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;view=newest') . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '"  /></a> ';
							}
							else
							{
								$folder_image = $folder;
								$folder_alt = ( $similar['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];
								$newest_img = '';
							}
						}
						else
						{
							$folder_image = $folder_new;
							$folder_alt = ( $similar['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['New_posts'];
							$newest_img = '<a href="' . append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;view=newest') . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ';
						}
					}
					else 
					{
						$folder_image = $folder;
						$folder_alt = ( $similar['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];
						$newest_img = '';
					}
				}	
				else
				{
					$folder_image = $folder;
					$folder_alt = ( $similar['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];
					$newest_img = '';
				}		

				$replies = '<a href="javascript:who(' . $similar['topic_id'] . ')">' . $replies . '</a>';
		
		   		$title = (strlen($similar['topic_title']) > 40) ? (substr($similar['topic_title'], 0, 37) . '...') : $similar['topic_title']; 
		   		$topic_url = '<a href="'. append_sid('viewtopic.'.$phpEx.'?'. POST_TOPIC_URL . '=' . $similar['topic_id']) . '" class="topictitle">' . $title . '</a>';	
	
		   		$author = ( $similar['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $similar['user_id']) . '" class="genmed">' . username_level_color($similar['username'], $similar['user_level'], $similar['user_id']) . '</a>' : ( ($similar['post_username'] != '' ) ? $similar['post_username'] : $lang['Guest'] );
		   		$forum = '<a href="' . append_sid('viewforum.'.$phpEx.'?' . POST_FORUM_URL . '=' . $similar['forum_id']) .'" class="forumlink">' . $similar['forum_name'] . '</a>';
		   		$post_time = create_date($board_config['default_dateformat'], $similar['topic_time'], $board_config['board_timezone']);
		   		$post_url = '<a href="' . append_sid('viewtopic.'.$phpEx.'?' . POST_POST_URL . '=' . $similar['topic_last_post_id']) . '#' . $similar['topic_last_post_id'] . '"><img src="'. $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
		 
		    	$template->assign_block_vars('similar.topics', array(
		        	'FOLDER' => $folder_image,
		        	'ALT' => $folder_alt,
		        	'TYPE' => $topic_type,
		        	'TOPICS' => $topic_url,
		        	'AUTHOR' => $author,
		        	'FORUM' => $forum,
		        	'REPLIES' => $replies,
		        	'NEWEST' => $newest_img,
		        	'POST_TIME' => $post_time,
		        	'POST_URL' => $post_url)
   		 		);
  			} 
  			$db->sql_freeresult($result);
 		} 
	}	 
}

if ($post_id)
{
	$start = floor(($forum_topic_data['prev_posts'] - 1) / intval($board_config['posts_per_page'])) * intval($board_config['posts_per_page']);
}

//
// Sig and Av ignore Toggling
//
if ($board_config['enable_ignore_sigav'])
{
	if ($hide == 'hide')
	{
		$sql = "INSERT INTO " . HIDE_TABLE . " (user_id, hid_id) 
			VALUES (" . $userdata['user_id'] . ", " . $hidee_id . ")";
		if ( !($db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not ignore signature/avatar.', '', __LINE__, __FILE__, $sql); 
		}
	}
	else if ($hide == 'unhide')
	{
		$sql = "DELETE FROM " . HIDE_TABLE . " 
			WHERE user_id = " . $userdata['user_id'] . " 
				AND hid_id = " . $hidee_id;
		if ( !($db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not remove ignore signature/avatar.', '', __LINE__, __FILE__, $sql); 
		}	
	}
}

//
// Is user watching this thread?
//
$can_watch_topic = FALSE;
if ( $board_config['disable_topic_watching'] )
{
	if( $userdata['session_logged_in'] )
	{
		$can_watch_topic = TRUE;
	
		$sql = "SELECT notify_status
			FROM " . TOPICS_WATCH_TABLE . "
			WHERE topic_id = $topic_id
				AND user_id = " . $userdata['user_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain topic watch information", '', __LINE__, __FILE__, $sql);
		}
	
		if ( $row = $db->sql_fetchrow($result) )
		{
			if ( isset($HTTP_GET_VARS['unwatch']) )
			{
				if ( $HTTP_GET_VARS['unwatch'] == 'topic' )
				{
					$is_watching_topic = 0;
	
					$sql = "DELETE LOW_PRIORITY FROM " . TOPICS_WATCH_TABLE . "
						WHERE topic_id = $topic_id
							AND user_id = " . $userdata['user_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, "Could not delete topic watch information", '', __LINE__, __FILE__, $sql);
					}
				}
	
				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start") . '">')
				);
	
				$message = $lang['No_longer_watching'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				$is_watching_topic = TRUE;
	
				if ( $row['notify_status'] )
				{
					$sql = "UPDATE LOW_PRIORITY " . TOPICS_WATCH_TABLE . "
						SET notify_status = 0
						WHERE topic_id = $topic_id
							AND user_id = " . $userdata['user_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, "Could not update topic watch information", '', __LINE__, __FILE__, $sql);
					}
				}
			}
		}
		else
		{
			if ( isset($HTTP_GET_VARS['watch']) )
			{
				if ( $HTTP_GET_VARS['watch'] == 'topic' )
				{
					$is_watching_topic = TRUE;
	
					$sql = "INSERT LOW_PRIORITY INTO " . TOPICS_WATCH_TABLE . " (user_id, topic_id, notify_status)
						VALUES (" . $userdata['user_id'] . ", $topic_id, 0)";
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, "Could not insert topic watch information", '', __LINE__, __FILE__, $sql);
					}
				}
	
				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start") . '">')
				);
	
				$message = $lang['You_are_watching'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				$is_watching_topic = 0;
			}
		}
	}
	else
	{
		if ( isset($HTTP_GET_VARS['unwatch']) )
		{
			if ( $HTTP_GET_VARS['unwatch'] == 'topic' )
			{
				redirect(append_sid("login.$phpEx?redirect=viewtopic.$phpEx&" . POST_TOPIC_URL . "=$topic_id&unwatch=topic", true));
			}
		}
		else
		{
			$can_watch_topic = $is_watching_topic = 0;
		}
	}	
}


//
// Get the hierarchie
//
$cat_id = $forum_topic_data['cat_id'];

$sql = "SELECT concat(c.cat_title, ', ', f.forum_name) AS hierarchie_title, f.forum_id, f.forum_hier_level + 1 AS hierarchie_level
	FROM " . CATEGORIES_TABLE . " c, " . CAT_REL_CAT_PARENTS_TABLE . " ccp, " . FORUMS_TABLE . " f, " . CAT_REL_FORUM_PARENTS_TABLE . " cfp
	WHERE ccp.parent_cat_id = c.cat_id
		AND ccp.cat_id = $cat_id
		AND cfp.parent_forum_id = f.forum_id
		AND cfp.cat_id = $cat_id
		AND f.cat_id = c.cat_id
	ORDER BY c.cat_hier_level, f.forum_hier_level";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query hierarchie title', '', __LINE__, __FILE__, $sql);
}
while( $row = $db->sql_fetchrow($result) )
{
	$template->assign_block_vars('navrow', array(
		'U_SUBINDEX' => append_sid("index.$phpEx?" . POST_HIERARCHIE_URL . "=" . $row['hierarchie_level'] . "&" . POST_PARENTFORUM_URL . "=" . $row['forum_id']),
		'L_SUBINDEX' => $row['hierarchie_title'])
	);
}
$db->sql_freeresult($result);

//
// Get the category title
//
$sql = "SELECT cat_title
	FROM " . CATEGORIES_TABLE . "
	WHERE cat_id = $cat_id";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query category title', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);

$cf_title = $row['cat_title'] . ", " . $forum_topic_data['forum_name'];


//
// Is the topic answered or unanswered?
//
$answer_link = '';
if ( $forum_topic_data['answered_enable'] )
{
	$sql = "SELECT t.topic_id, t.topic_first_post_id, t.answer_status, p.post_id, p.poster_id, f.answered_enable
		FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . FORUMS_TABLE . " f
		WHERE t.topic_id = " . $topic_id . "
			AND t.topic_first_post_id = p.post_id
			AND f.forum_id = t.forum_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	
	if( ($row['poster_id'] == $userdata['user_id'] && $userdata['user_id'] != '-1') || ($userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $is_auth['auth_mod']) ) 
	{
		if( $userdata['session_logged_in'] )
		{
			if ($HTTP_GET_VARS['mode'] == 'answered')
			{
				$sql = "UPDATE " . TOPICS_TABLE . " 
					SET answer_status = 1 
					WHERE topic_id = $topic_id";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update topic status', '', __LINE__, __FILE__, $sql);
				}
				
				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">')
				);
		
				$message = $lang['Marked_answered'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
	
			if ($HTTP_GET_VARS['mode'] == 'unanswered')
			{
				$sql = "UPDATE " . TOPICS_TABLE . " 
					SET answer_status = 0 
					WHERE topic_id = $topic_id";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update topic status', '', __LINE__, __FILE__, $sql);
				}
				
				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">')
				);
		
				$message = $lang['Marked_unanswered'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
	
			if ( $row['answer_status'] == 1 && $row['answered_enable'] == 1 )
			{
				$answer_link = '<a href="' . append_sid("viewtopic.$phpEx?t=$topic_id&mode=unanswered"). '"><img src="' . $images['topic_answered'] . '" alt="' . $lang['Marked_answered'] . '" title="' . $lang['Marked_answered'] . '" /></a>';
			}
			else if ( $row['answer_status'] == 0 && $row['answered_enable'] == 1 )
			{
				$answer_link = '<a href="' . append_sid("viewtopic.$phpEx?t=$topic_id&mode=answered"). '"><img src="' . $images['topic_unanswered'] . '" alt="' . $lang['Marked_unanswered'] . '" title="' . $lang['Marked_unanswered'] . '" /></a>';
			}
			else
			{
				$answer_link = '';
			}
		}
	}
}


//
// Generate a 'Show posts in previous x days' select box. If the postdays var is POSTed
// then get it's value, find the number of topics with dates newer than it (to properly
// handle pagination) and alter the main query
//
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['All_Posts'], $lang['1_Day'], $lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year']);

if( !empty($HTTP_POST_VARS['postdays']) || !empty($HTTP_GET_VARS['postdays']) )
{
	$post_days = ( !empty($HTTP_POST_VARS['postdays']) ) ? intval($HTTP_POST_VARS['postdays']) : intval($HTTP_GET_VARS['postdays']); 
	$min_post_time = time() - (intval($post_days) * 86400);

	$sql = "SELECT COUNT(p.post_id) AS num_posts
		FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		WHERE t.topic_id = $topic_id
			AND p.topic_id = t.topic_id
			AND p.post_time >= $min_post_time";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain limited topics count information", '', __LINE__, __FILE__, $sql);
	}

	$total_replies = ( $row = $db->sql_fetchrow($result) ) ? intval($row['num_posts']) : 0;

	$limit_posts_time = "AND p.post_time >= $min_post_time ";

	if ( !empty($HTTP_POST_VARS['postdays']))
	{
		$start = 0;
	}
}
else
{
	$total_replies = intval($forum_topic_data['topic_replies']) + 1;

	$limit_posts_time = '';
	$post_days = 0;
}

$select_post_days = '<select name="postdays">';
for($i = 0; $i < sizeof($previous_days); $i++)
{
	$selected = ($post_days == $previous_days[$i]) ? ' selected="selected"' : '';
	$select_post_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}
$select_post_days .= '</select>';

//
// Decide how to order the post display
//
if ( !empty($HTTP_POST_VARS['postorder']) || !empty($HTTP_GET_VARS['postorder']) )
{
	$post_order = (!empty($HTTP_POST_VARS['postorder'])) ? htmlspecialchars($HTTP_POST_VARS['postorder']) : htmlspecialchars($HTTP_GET_VARS['postorder']); 
	$post_time_order = ($post_order == "asc") ? "ASC" : "DESC";
}
else
{
	$post_order = 'asc';
	$post_time_order = 'ASC';
}

$select_post_order = '<select name="postorder">';
if ( $post_time_order == 'ASC' )
{
	$select_post_order .= '<option value="asc" selected="selected">' . $lang['Oldest_First'] . '</option><option value="desc">' . $lang['Newest_First'] . '</option>';
}
else
{
	$select_post_order .= '<option value="asc">' . $lang['Oldest_First'] . '</option><option value="desc" selected="selected">' . $lang['Newest_First'] . '</option>';
}
$select_post_order .= '</select>';


//
// Get theme count (if more than one theme available)
//
if (!$board_config['override_user_style'] && $board_config['viewtopic_style']) 
{
	$sql = "SELECT user_style, COUNT(*) AS total 
        FROM " . USERS_TABLE . " 
        GROUP BY user_style";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user style counts.', '', __LINE__, __FILE__, $sql);
	}
	while( $style_row = $db->sql_fetchrow($result) )
	{
		$style_counts[$style_row['user_style']] = $style_row['total']; 
	}
	$db->sql_freeresult($result);
}

	
//
// Go ahead and pull all data for this topic
//
$sql = "SELECT u.*, u.user_avatar AS current_user_avatar, u.user_avatar_type AS current_user_avatar_type, p.*, pt.post_text, pt.post_subject, pt.bbcode_uid, th.style_name, t.topic_poster
	FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt, " . THEMES_TABLE . " th, " . TOPICS_TABLE . " t
	WHERE p.topic_id = $topic_id
		AND t.topic_id = p.topic_id
		$limit_posts_time
		AND pt.post_id = p.post_id
		AND u.user_id = p.poster_id
		AND th.themes_id = u.user_style
	ORDER BY p.post_time $post_time_order
	LIMIT $start, " . $board_config['posts_per_page'];
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain post/user information.", '', __LINE__, __FILE__, $sql);
}

$postrow = $user_sig_cache = array();
$poster_id_sql = '';

if ($row = $db->sql_fetchrow($result))
{
	do
	{
		$postrow[] = $row;
		$poster_id_sql .= ($row['user_rank']) ? '' : ',' . $row['user_id'];
	}
	while ($row = $db->sql_fetchrow($result));
	$db->sql_freeresult($result);

	$total_posts = sizeof($postrow);
}
else 
{ 
   include($phpbb_root_path . 'includes/functions_admin.' . $phpEx); 
   sync('topic', $topic_id); 

   message_die(GENERAL_MESSAGE, $lang['No_posts_topic']); 
} 

$resync = FALSE; 
if ($forum_topic_data['topic_replies'] + 1 < $start + sizeof($postrow)) 
{ 
   $resync = TRUE; 
} 
elseif ($start + $board_config['posts_per_page'] > $forum_topic_data['topic_replies']) 
{ 
   $row_id = intval($forum_topic_data['topic_replies']) % intval($board_config['posts_per_page']); 
   if ($postrow[$row_id]['post_id'] != $forum_topic_data['topic_last_post_id'] || $start + sizeof($postrow) < $forum_topic_data['topic_replies']) 
   { 
      $resync = TRUE; 
   } 
} 
elseif (sizeof($postrow) < $board_config['posts_per_page']) 
{ 
   $resync = TRUE; 
} 

if ($resync) 
{ 
   include($phpbb_root_path . 'includes/functions_admin.' . $phpEx); 
   sync('topic', $topic_id); 

   $result = $db->sql_query('SELECT COUNT(post_id) AS total FROM ' . POSTS_TABLE . ' WHERE topic_id = ' . $topic_id); 
   $row = $db->sql_fetchrow($result); 
   $total_replies = $row['total']; 
}

//
// Ranks
//
$sql = "SELECT *
	FROM " . RANKS_TABLE . "
	ORDER BY rank_special, rank_min DESC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql);
}

$ranksrow = $poster_group = array();
$rank_group_id_sql = '';
while ( $row = $db->sql_fetchrow($result) )
{
	if ( $row['rank_special'] )
	{
		$ranksrow[-1][$row['rank_id']] = $row;
	}
	else
	{
		$ranksrow[$row['rank_group']][] = $row;
		$rank_group_id_sql .= ($row['rank_group'] > 0) ? ',' . $row['rank_group'] : '';
		$ranksrow[$row['rank_group']]['count']++;
	}
}
$db->sql_freeresult($result);

if ( !empty($poster_id_sql) && !empty($rank_group_id_sql) )
{
	$rank_group_id_sql = substr($rank_group_id_sql, 1);
	$poster_id_sql = substr($poster_id_sql, 1);
	$sql = "SELECT ug.user_id, ug.group_id
		FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
		WHERE ug.user_id IN ( $poster_id_sql )
			AND ug.group_id IN ( $rank_group_id_sql )
			AND g.group_id = ug.group_id
			AND g.group_single_user = 0
		ORDER BY g.group_rank_order DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain poster group information.', '', __LINE__, __FILE__, $sql);
	}

	while ( $row = $db->sql_fetchrow($result) )
	{
		$poster_group[$row['user_id']] = $row['group_id'];
	}
	$db->sql_freeresult($result);
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

//
// Topic title ... Censor, Add Quick title, Capatalization
//
$topic_title = capitalization($topic_title);

if ( !empty($orig_word) )
{
	$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
}


$topic_title_full = $topic_title;
if ($board_config['enable_quick_titles'])
{
	if ( $forum_topic_data['title_pos'] )
	{
		$topic_title_full = (empty($forum_topic_data['title_compl_infos'])) ? $topic_title : $topic_title . ' <span style="color: #' . $forum_topic_data['title_compl_color'] . '">' . $forum_topic_data['title_compl_infos'] . '</span>';
	}
	else
	{
		$topic_title_full = (empty($forum_topic_data['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $forum_topic_data['title_compl_color'] . '">' . $forum_topic_data['title_compl_infos'] . '</span> ' . $topic_title;
	}
}
	
//
// Was a highlight request part of the URI?
//
$highlight_match = $highlight = '';
if (isset($HTTP_GET_VARS['highlight']))
{
	// Split words and phrases
	$words = explode(' ', trim(htmlspecialchars($HTTP_GET_VARS['highlight'])));
	
	for($i = 0; $i < sizeof($words); $i++)
	{
		if (trim($words[$i]) != '')
		{
			$highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', preg_quote($words[$i], '#'));
		}
	}
	unset($words);

   	$highlight = urlencode($HTTP_GET_VARS['highlight']);
 	$highlight_match = phpbb_rtrim($highlight_match, "\\");
}

//
// Post, reply and other URL generation for
// templating vars
//
$new_topic_url = append_sid("posting.$phpEx?mode=newtopic&amp;" . POST_FORUM_URL . "=$forum_id");
$reply_topic_url = append_sid("posting.$phpEx?mode=reply&amp;" . POST_TOPIC_URL . "=$topic_id");
$thank_topic_url = append_sid("posting.$phpEx?mode=thank&amp;" . POST_TOPIC_URL . "=$topic_id");
$view_forum_url = append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id");
$view_prev_topic_url = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;view=previous");
$view_next_topic_url = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;view=next");

//
// Mozilla navigation bar
//
$nav_links['prev'] = array(
	'url' => $view_prev_topic_url,
	'title' => $lang['View_previous_topic']
);
$nav_links['next'] = array(
	'url' => $view_next_topic_url,
	'title' => $lang['View_next_topic']
);
$nav_links['up'] = array(
	'url' => $view_forum_url,
	'title' => $forum_name
);

if ( $forum_topic_data['forum_status'] == FORUM_LOCKED || $forum_topic_data['topic_status'] == TOPIC_LOCKED ) 
{ 
	$reply_img = ( !$is_auth['auth_mod'] ) ? $images['reply_locked'] : $images['reply_new'];
	$reply_img_mini = ( !$is_auth['auth_mod'] ) ? $images['reply_locked_mini'] : $images['reply_new_mini'];
	$reply_alt = ( !$is_auth['auth_mod'] ) ? $lang['Topic_locked'] : $lang['Reply_to_topic'];
	$post_img = ( !$is_auth['auth_mod'] ) ? $images['post_locked'] : $images['post_new'];
	$post_alt = ( !$is_auth['auth_mod'] ) ? $lang['Forum_locked'] : $lang['Post_new_topic'];
	$reply_topic_mini_url = '<a href="' . $reply_topic_url . '"><img src="' . $reply_img_mini . '" alt="' . $reply_alt . '" title="' . $reply_alt . '" /></a>';
	$reply_topic_url = '&nbsp;&nbsp;&nbsp;<a href="' . $reply_topic_url . '"><img src="' . $reply_img . '" alt="' . $reply_alt . '" title="' . $reply_alt . '" align="middle" /></a>';
} 
else 
{ 
	$reply_img = $images['reply_new']; 
	$reply_img_mini = $images['reply_new_mini']; 
	$reply_alt = $lang['Reply_to_topic']; 
	$post_img = $images['post_new']; 
	$post_alt = $lang['Post_new_topic']; 
	$reply_topic_mini_url = '<a href="' . $reply_topic_url . '"><img src="' . $reply_img_mini . '" alt="' . $reply_alt . '" title="' . $reply_alt . '" /></a>';
	$reply_topic_url = '&nbsp;&nbsp;&nbsp;<a href="' . $reply_topic_url . '"><img src="' . $reply_img . '" alt="' . $reply_alt . '" title="' . $reply_alt . '" align="middle" /></a>';
} 

$print_topic_img = $images['topic_print'];
$tell_friend_img = $images['topic_tell_friend'];
$next_topic_img = $images['topic_next'];
$previous_topic_img = $images['topic_previous'];
$refresh_topic_img = $images['topic_refresh'];
$bookmark_topic_img = $images['topic_bookmark'];
$search_img = ( $userdata['session_logged_in'] ) ? '<a href="' . append_sid("search.$phpEx?search_id=newposts") . '"><img src="' . $images['topic_search'] . '" alt="' . $lang['Search_new'] . '" title="' . $lang['Search_new'] . '" /></a>' : '';
$topic_view_img = ( $board_config['enable_topic_view_users'] && $userdata['session_logged_in'] ) ? '<a href="' . append_sid("topic_view_users.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '"><img src="' . $images['topic_view'] . '" alt="' . $lang['Topic_view_users'] . '" title="' . $lang['Topic_view_users'] . '" /></a>' : '';
$download_topic_img = $images['topic_download'];
$thank_img = ( $show_thanks == FORUM_THANKABLE ) ? '&nbsp;&nbsp;&nbsp;<a href="' . $thank_topic_url . '"><img src="' . $images['thanks'] . '"  alt="' . $lang['thanks_alt'] . '" title="' . $lang['thanks_alt'] . '" align="middle" />' : '';

//
// Set a cookie for this topic
//
if ( $userdata['session_logged_in'] )
{
	$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : array();
	$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) : array();

	if ( !empty($tracking_topics[$topic_id]) && !empty($tracking_forums[$forum_id]) )
	{
		$topic_last_read = ( $tracking_topics[$topic_id] > $tracking_forums[$forum_id] ) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
	}
	else if ( !empty($tracking_topics[$topic_id]) || !empty($tracking_forums[$forum_id]) )
	{
		$topic_last_read = ( !empty($tracking_topics[$topic_id]) ) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
	}
	else
	{
		$topic_last_read = $userdata['user_lastvisit'];
	}

	if ( sizeof($tracking_topics) >= 150 && empty($tracking_topics[$topic_id]) )
	{
		asort($tracking_topics);
		unset($tracking_topics[key($tracking_topics)]);
	}

	$tracking_topics[$topic_id] = time();

	setcookie($board_config['cookie_name'] . '_t', serialize($tracking_topics), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
}

//
// Load templates
//
$template->set_filenames(array(
	'body' => 'viewtopic_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx, $forum_id);

if ( $userdata['session_logged_in'] && $board_config['AJAXed_status'] )
{
	include($phpbb_root_path . 'includes/sajax.'.$phpEx);
	$sajax->request_type = 'POST';
	$sajax->init('ajax.'.$phpEx.'?' . POST_FORUM_URL . '=' . $forum_id . '&' . POST_TOPIC_URL . '=' . $topic_id . '&sid=' . $userdata['session_id']);
	if ( $is_auth['auth_edit'] )
	{
		$sajax->export('edit_post_msg', 'edit_post_subject', 'post_delete', 'delete_topic', 'move_topic', 'move', 'move_build', 'lock_unlock_topic', 'lock_topic', 'get_editor', 'edit_post_msg_update', 'post_ip');
	}
	if ( !empty($forum_topic_data['topic_vote']) )
	{
		$sajax->export('poll_menu');
		if ( $is_auth['auth_mod'] )
		{
			$sajax->export('poll_title', 'poll_options', 'poll_option_update');
		}
		$sajax->export('poll_view', 'poll_vote');
	}
	$sajax->export('watch_topic', 'post_menu', 'previewPost');
	$sajax->handle_client_request();
	$sajax->show_javascript();
	$template->assign_block_vars('switch_ajax', array());
}

//
// Output page header
//
define('SHOW_ONLINE', true); 
$page_title = $lang['View_topic'] .' - ' . $topic_title;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

//
// User authorisation levels output
//
$topic_mod = $s_watching_topic = $topic_mod_ajaxed = '';

$s_auth_can = ( ( $is_auth['auth_post'] ) ? $lang['Rules_post_can'] : $lang['Rules_post_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_reply'] ) ? $lang['Rules_reply_can'] : $lang['Rules_reply_cannot'] ) . '<br />';
$s_auth_can .= ( ( $stop_bumping == TRUE ) ? $lang['Rules_bump_cannot'] . '<br />' : '' );
$s_auth_can .= ( ( $is_auth['auth_edit'] ) ? $lang['Rules_edit_can'] : $lang['Rules_edit_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_delete'] ) ? $lang['Rules_delete_can'] : $lang['Rules_delete_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_vote'] ) ? $lang['Rules_vote_can'] : $lang['Rules_vote_cannot'] ) . '<br />';
$s_auth_can .= ( $is_auth['auth_ban'] && $board_config['enable_bancards'] ) ? $lang['Rules_ban_can'] . '<br />' : ''; 
$s_auth_can .= ( $is_auth['auth_greencard'] && $board_config['enable_bancards'] ) ? $lang['Rules_greencard_can'] . '<br />' : ''; 
$s_auth_can .= ( $is_auth['auth_voteban'] && $board_config['enable_bancards'] ) ? $lang['Rules_voteban_can'] . '<br />' : ''; 
$s_auth_can .= ( $is_auth['auth_bluecard'] && $board_config['enable_bancards'] ) ? $lang['Rules_bluecard_can'] . '<br />' : ''; 
attach_build_auth_levels($is_auth, $s_auth_can);

if ( $is_auth['auth_mod'] )
{
	if ( $board_config['AJAXed_status'] )
	{
		$ajaxed_delete_onclick = ( $board_config['AJAXed_topic_delete'] ) ? "onClick=\"dt(" . $topic_id . ",1); return false;\" " : '';
		$ajaxed_move_onclick = ( $board_config['AJAXed_topic_move'] ) ? "onClick=\"mb(" . $topic_id . ",1); return false;\" " : '';
		$ajaxed_lock_onclick = ( $board_config['AJAXed_topic_lock'] ) ? "onClick=\"lt(" . $topic_id . ",1); return false;\" " : '';
	}
	
	$s_auth_can .= sprintf($lang['Rules_moderate'], "<a href=\"modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'] . '">', '</a>');

	$topic_mod .= "<a " . $ajaxed_delete_onclick . "href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=delete&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_delete'] . '" alt="' . $lang['Delete_topic'] . '" title="' . $lang['Delete_topic'] . '" hspace="2" /></a>';

	$topic_mod .= "<a " . $ajaxed_move_onclick . "href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=move&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_move'] . '" alt="' . $lang['Move_topic'] . '" title="' . $lang['Move_topic'] . '" hspace="2" /></a>';

	$topic_mod .= ( $forum_topic_data['topic_status'] == TOPIC_UNLOCKED ) ? "<a " . $ajaxed_lock_onclick . "href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=lock&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_lock'] . '" id="topic_lock" alt="' . $lang['Lock_topic'] . '" title="' . $lang['Lock_topic'] . '" hspace="2" /></a>' : "<a " . $ajaxed_lock_onclick . "href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=unlock&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_unlock'] . '" id="topic_lock" alt="' . $lang['Unlock_topic'] . '" title="' . $lang['Unlock_topic'] . '" hspace="2" /></a>';

	$topic_mod .= "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=split&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_split'] . '" alt="' . $lang['Split_topic'] . '" title="' . $lang['Split_topic'] . '" hspace="2" /></a>';

	$topic_mod .= "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=mergepost&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_merge'] . '" alt="' . $lang['Merge_post'] . '" title="' . $lang['Merge_post'] . '" hspace="2" /></a>';

	$topic_mod .= ( $board_config['bin_forum'] ) ? "<a href=\"modcp_recycle.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_bin'] . '" alt="' . $lang['Move_bin'] . '" title="' . $lang['Move_bin'] . '" hspace="2" ></a>' : '';
	
	$topic_mod .= "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=link&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_link'] . '" alt="' . $lang['Link_topic'] . '" title="' . $lang['Link_topic'] . '" hspace="2" /></a>';

	$topic_mod .= "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=bump&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_bump'] . '" alt="' . $lang['Bump_topic'] . '" title="' . $lang['Bump_topic'] . '" hspace="2" /></a>';

	$topic_mod .= ( $forum_topic_data['topic_type'] != 1 ) ? "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=sticky&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_sticky'] . '" alt="' . $lang['Sticky_topic'] . '" title="' . $lang['Sticky_topic'] . '" hspace="2" /></a>' : "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=unsticky&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_unsticky'] . '" alt="' . $lang['Unsticky_topic'] . '" title="' . $lang['Unsticky_topic'] . '" hspace="2" /></a>';

	$topic_mod .= ( $forum_topic_data['topic_type'] != 2 ) ? "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=announce&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_announce'] . '" alt="' . $lang['Announce_topic'] . '" title="' . $lang['Announce_topic'] . '" hspace="2" /></a>' : "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=unannounce&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_unannounce'] . '" alt="' . $lang['Unannounce_topic'] . '" title="' . $lang['Unannounce_topic'] . '" hspace="2" /></a>';

	$topic_mod .= ( $forum_topic_data['topic_type'] != 3 ) ? "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=globalannounce&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_globalannounce'] . '" alt="' . $lang['Globalannounce_topic'] . '" title="' . $lang['Globalannounce_topic'] . '" hspace="2" /></a>' : "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=unglobalannounce&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_unglobalannounce'] . '" alt="' . $lang['Unglobalannounce_topic'] . '" title="' . $lang['Unglobalannounce_topic'] . '" hspace="2" /></a>';
}


//
// Quick topic titles
//
if ($board_config['enable_quick_titles'])
{
	if (!($userdata['user_level'] == 0 && $userdata['user_id'] != $postrow[$row_id]['topic_poster']))
	{
		$sql = "SELECT * 
			FROM " . TITLE_INFOS_TABLE . " 
			ORDER BY title_info";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain quick topic titles.', '', __LINE__, __FILE__, $sql);
		}
	
		$select_title = '<form action="modcp.'.$phpEx.'?sid=' . $userdata['session_id'] . '" method="post"><select name="qtnum"><option value="-1">---</option>';
			
		while ( $row = $db->sql_fetchrow($result) )
		{
			if (($row['poster_auth'] == 1 & $userdata['user_level'] == 0) || ($row['mod_auth'] == 1 & $userdata['user_level'] == 3) || ($row['mod_auth'] == 1 & $userdata['user_level'] == 2) || ($row['admin_auth'] == 1 & $userdata['user_level'] == 1))
			{
				$addon = str_replace('%mod%', addslashes($userdata['username']), $row['title_info']);
				$dateqt = ( $row['date_format'] == '' ) ? create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']) : create_date($row['date_format'], time(), $board_config['board_timezone']);
				$addon = str_replace('%date%', $dateqt, $addon);
				$select_title .= '<option value="' . $row['id'] . '">' . $addon . '</option>';
			}
		}
		$db->sql_freeresult($result);
	
		$select_title .= '</select> &nbsp;<input type="submit" name="quick_title_edit" class="liteoption" value="' . $lang['Edit_title'] . '" /><input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" /></form>';
	
		$topic_mod .= $select_title;
	}
}


//
// Topic watch information
//
if ( $userdata['email_validation'] == 0 )
{
	if ( $can_watch_topic )
	{
		$ajaxed_watch_topic = ( $board_config['AJAXed_topic_watch'] && $board_config['AJAXed_status'] ) ? ' onClick="uh(); return false;"' : '';
	
		if ( $is_watching_topic )
		{
			$s_watching_topic = "<a" . $ajaxed_watch_topic . " href=\"viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;unwatch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '">' . $lang['Stop_watching_topic'] . '</a>';
			$s_watching_topic_img = ( isset($images['topic_un_watch']) ) ? "<a href=\"viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;unwatch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_un_watch'] . '" alt="' . $lang['Stop_watching_topic'] . '" title="' . $lang['Stop_watching_topic'] . '" /></a>' : '';
		}
		else
		{
			$s_watching_topic = "<a" . $ajaxed_watch_topic . " href=\"viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;watch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '">' . $lang['Start_watching_topic'] . '</a>';
			$s_watching_topic_img = ( isset($images['topic_watch']) ) ? "<a href=\"viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;watch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_watch'] . '" alt="' . $lang['Start_watching_topic'] . '" title="' . $lang['Start_watching_topic'] . '" /></a>' : '';
		}
	}
}


//
// Forum rules
//
if ( $forum_rules )
{
	$template->assign_block_vars('switch_forum_rules', array() );
}

//
// If we've got a hightlight set pass it on to pagination,
// I get annoyed when I lose my highlight after the first page.
//
$pagination = ( $highlight != '' ) ? generate_pagination("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;highlight=$highlight", $total_replies, $board_config['posts_per_page'], $start) : generate_pagination("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order", $total_replies, $board_config['posts_per_page'], $start);
$current_page = get_page($total_replies, $board_config['posts_per_page'], $start);

$subscribe_members = '<br /><a href="' . append_sid("topic_subscribe.$phpEx?subscribe_to=$topic_id") . '" class="gensmall">' . $lang['Subscribe_members'] . '</a><br /><a href="' . append_sid("topic_subscribe.$phpEx?unsubscribe_from=$topic_id") . '" class="gensmall">' . $lang['Unsubscribe_members'] . '</a>';

//
// Send vars to template
//
$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
    'FORUM_NAME' => $forum_name,
	'FORUM_NAVNAME' => $cf_title,
    'FORUM_RULES' => $forum_rules,
	'TOPIC_ID' => $topic_id,
	'TOPIC_POSTS' => $total_replies,
	'TOPIC_TITLE' => $topic_title_full,
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / intval($board_config['posts_per_page']) ) + 1 ), ceil( $total_replies / intval($board_config['posts_per_page']) )),
    'PREVIOUS_TOPIC_EXTRAS' => $previous_topic_extras,
    'NEXT_TOPIC_EXTRAS' => $next_topic_extras,

	'POST_IMG' => $post_img,
	'PRINT_TOPIC_IMG' => $print_topic_img,
	'NEXT_TOPIC_IMG' => $next_topic_img,
	'PREVIOUS_TOPIC_IMG' => $previous_topic_img,
	'REFRESH_TOPIC_IMG' => $refresh_topic_img,
	'BOOKMARK_TOPIC_IMG' => $bookmark_topic_img,
	'SEARCH_IMG' => $search_img,
	'TOPIC_VIEW_IMG' => $topic_view_img,
	'ANSWER_STATUS' => $answer_link,
	'THANK_IMG' => $thank_img,

	'L_AUTHOR' => $lang['Author'],
	'L_MESSAGE' => $lang['Message'],
	'L_POSTED' => $lang['Posted'],
	'L_POST_SUBJECT' => $lang['Post_subject'],
	'L_MEMBER_NUMBER' => $lang['Member_number'],
	'L_VIEW_NEXT_TOPIC' => $lang['View_next_topic'],
	'L_VIEW_PREVIOUS_TOPIC' => $lang['View_previous_topic'],
	'L_POST_NEW_TOPIC' => $post_alt,
	'L_POST_REPLY_TOPIC' => $reply_alt,
	'L_BACK_TO_TOP' => $lang['Back_to_top'],
	'L_BACK_AT_BOTTOM' => $lang['Back_at_bottom'],
	'L_DISPLAY_POSTS' => $lang['Display_posts'],
	'L_LOCK_TOPIC' => $lang['Lock_topic'],
	'L_UNLOCK_TOPIC' => $lang['Unlock_topic'],
	'L_MOVE_TOPIC' => $lang['Move_topic'],
	'L_SPLIT_TOPIC' => $lang['Split_topic'],
	'L_DELETE_TOPIC' => $lang['Delete_topic'],
	'L_GOTO_PAGE' => $lang['Goto_page'],
	'L_PRINT' => $lang['Print_View'], 
	'L_TOPIC_BOOKMARK' => $lang['Topic_bookmark'], 
	'L_REFRESH_PAGE' => $lang['Refresh_page'],	
	'L_POSTS' => $lang['Posts'],
	'L_DOWNLOAD_TOPIC' => $lang['Download_topic'],
	'L_FORUM_RULES' => $lang['Forum_rules'],
	'L_TOPIC' => $lang['Topic'],
	
	'S_TOPIC_LINK' => POST_TOPIC_URL,
	'S_SELECT_POST_DAYS' => $select_post_days,
	'S_SELECT_POST_ORDER' => $select_post_order,
	'S_POST_DAYS_ACTION' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . '=' . $topic_id . "&amp;start=$start"),
	'S_AUTH_LIST' => $s_auth_can,
	'S_TOPIC_ADMIN' => $topic_mod,
	'S_WATCH_TOPIC' => $s_watching_topic,
	'S_WATCH_TOPIC_IMG' => $s_watching_topic_img,
	'DOWNLOAD_TOPIC_IMG' => $download_topic_img,
		
	'SUBSCRIBE_MEMBERS' => ($userdata['user_level'] == ADMIN) ? $subscribe_members : '',
	'U_DOWNLOAD_TOPIC' => append_sid('viewtopic.'.$phpEx.'?download=-1&amp;' . POST_TOPIC_URL . '=' . $topic_id),
	'U_PRINT' => append_sid("viewtopic_print.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start"), 
	'TELLAFRIEND' => ($board_config['enable_tellafriend']) ? '<a href="' . append_sid('viewtopic_tellafriend.'.$phpEx.'?topic=' . str_replace("'", "\'", $forum_topic_data['topic_title']) .'&amp;link=' . append_sid(real_path('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id))) . '"><img src="' . $tell_friend_img . '" alt="' . $lang['Tell_Friend'] . '" title="' . $lang['Tell_Friend'] . '" /></a>' : '', 
	'U_VIEW_TOPIC' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start&amp;postdays=$post_days&amp;postorder=$post_order&amp;highlight=$highlight"),
	'U_VIEW_FORUM' => $view_forum_url,
	'U_VIEW_OLDER_TOPIC' => $view_prev_topic_url,
	'U_VIEW_NEWER_TOPIC' => $view_next_topic_url,
	'U_POST_NEW_TOPIC' => $new_topic_url,
	'U_POST_REPLY_MINI_TOPIC' => ($stop_bumping == TRUE) ? '' : $reply_topic_mini_url,
	'U_POST_REPLY_TOPIC' => ($stop_bumping == TRUE) ? '' : $reply_topic_url)
);

//
// Quick reply
//
if ($is_auth['auth_reply'] == true && $stop_bumping == FALSE && $board_config['enable_quick_reply'])
{
	$quick_reply_img = ( $forum_topic_data['forum_status'] == FORUM_LOCKED || $forum_topic_data['topic_status'] == TOPIC_LOCKED ) ? $images['reply_locked'] : $images['quick_reply']; 
	$quick_reply_alt = ( $forum_topic_data['forum_status'] == FORUM_LOCKED || $forum_topic_data['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['Quick_Reply_to_topic']; 
	
	if ( !$userdata['session_logged_in'] )
	{
		$template->assign_block_vars('switch_username_field', array());

		//
		// Visual confirmation for guest postings
		//
		$confirm_image = '';
		if( !empty($board_config['enable_confirm_posting']) )
		{
			$sql = 'SELECT session_id 
				FROM ' . SESSIONS_TABLE; 
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not select session data', '', __LINE__, __FILE__, $sql);
			}
			
			if ($row = $db->sql_fetchrow($result))
			{
				$confirm_sql = '';
				do
				{
					$confirm_sql .= (($confirm_sql != '') ? ', ' : '') . "'" . $row['session_id'] . "'";
				}
				while ($row = $db->sql_fetchrow($result));
			
				$sql = 'DELETE FROM ' .  CONFIRM_TABLE . " 
					WHERE session_id NOT IN ($confirm_sql)";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not delete stale confirm data', '', __LINE__, __FILE__, $sql);
				}
			}
			$db->sql_freeresult($result);
			
			$confirm_chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',  'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',  'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9');
			
			list($usec, $sec) = explode(' ', microtime()); 
			mt_srand($sec * $usec); 
			
			$max_chars = count($confirm_chars) - 1;
			$code = '';
			for ($i = 0; $i < 6; $i++)
			{
				$code .= $confirm_chars[mt_rand(0, $max_chars)];
			}
			
			$confirm_id = md5(uniqid($user_ip));
			
			$sql = 'INSERT INTO ' . CONFIRM_TABLE . " (confirm_id, session_id, code) 
				VALUES ('$confirm_id', '". $userdata['session_id'] . "', '$code')";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert new confirm code information', '', __LINE__, __FILE__, $sql);
			}
			
			unset($code);
			
			$confirm_image = (@extension_loaded('zlib')) ? '<img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id") . '" alt="" title="" />' : '<img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=1") . '" alt="" title="" /><img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=2") . '" alt="" title="" /><img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=3") . '" alt="" title="" /><img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=4") . '" alt="" title="" /><img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=5") . '" alt="" title="" /><img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=6") . '" alt="" title="" />';
			$hidden_confirm_field = '<input type="hidden" name="confirm_id" value="' . $confirm_id . '" />';
			
			$template->assign_block_vars('switch_confirm', array(
				'L_CONFIRM_CODE' => $lang['Confirm_code'],
				'CONFIRM_IMG' => $confirm_image)
			);
		}		
	}

	$template->assign_block_vars('quick_reply', array(
		'QUICK_REPLY_IMG' => $quick_reply_img, 

		'L_QUICK_REPLY_TOPIC' => $quick_reply_alt,
		'L_EMPTY_MESSAGE' => $lang['Empty_message'], 		
	
		'U_QUICK_REPLY' => append_sid('posting.' . $phpEx),
		'U_HIDDEN_FORM_FIELDS' => ( ( $userdata['user_attachsig'] ? '<input type="hidden" name="attach_sig" value="1" />' : '' ) . ( $userdata['user_notify'] || $is_watching_topic ? '<input type="hidden" name="notify" value="1" />' : '') . '<input type="hidden" name="mode" value="reply" /><input type="hidden" name="post" value="1"><input type="hidden" name="' . POST_TOPIC_URL . '" value="' . $topic_id . '" /><input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />' . $hidden_confirm_field ))
	);
}

//
// Does this topic contain a poll?
//
if ( !empty($forum_topic_data['topic_vote']) )
{
	$s_hidden_fields = '';

	$sql = "SELECT vd.vote_id, vd.vote_text, vd.vote_start, vd.vote_length, vr.vote_option_id, vr.vote_option_text, vr.vote_result
		FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
		WHERE vd.topic_id = $topic_id
			AND vr.vote_id = vd.vote_id
		ORDER BY vr.vote_option_id ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain vote data for this topic", '', __LINE__, __FILE__, $sql);
	}

	if ( $vote_info = $db->sql_fetchrowset($result) )
	{
		$db->sql_freeresult($result);
		$vote_options = sizeof($vote_info);

		$vote_id = $vote_info[0]['vote_id'];
		$vote_title = $vote_info[0]['vote_text'];

		$sql = "SELECT vote_id
			FROM " . VOTE_USERS_TABLE . "
			WHERE vote_id = $vote_id
				AND vote_user_id = " . intval($userdata['user_id']);
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain user vote data for this topic", '', __LINE__, __FILE__, $sql);
		}

		$user_voted = ( $row = $db->sql_fetchrow($result) ) ? TRUE : 0;
		$db->sql_freeresult($result);

		if ( isset($HTTP_GET_VARS['vote']) || isset($HTTP_POST_VARS['vote']) )
		{
			$view_result = ( ( ( isset($HTTP_GET_VARS['vote']) ) ? $HTTP_GET_VARS['vote'] : $HTTP_POST_VARS['vote'] ) == 'viewresult' ) ? TRUE : 0;
		}
		else
		{
			$view_result = 0;
		}

		$poll_expired = ( $vote_info[0]['vote_length'] ) ? ( ( $vote_info[0]['vote_start'] + $vote_info[0]['vote_length'] < time() ) ? TRUE : 0 ) : 0;

		if ( $user_voted || $view_result || $poll_expired || !$is_auth['auth_vote'] || $forum_topic_data['topic_status'] == TOPIC_LOCKED )
		{
			$template->set_filenames(array(
				'pollbox' => 'viewtopic_poll_result.tpl')
			);

			$vote_results_sum = 0;

			for($i = 0; $i < $vote_options; $i++)
			{
				$vote_results_sum += $vote_info[$i]['vote_result'];
			}

			$vote_graphic = 0;
			$vote_graphic_max = sizeof($images['voting_graphic']);

			for($i = 0; $i < $vote_options; $i++)
			{
				$vote_percent = ( $vote_results_sum > 0 ) ? $vote_info[$i]['vote_result'] / $vote_results_sum : 0;
				$vote_graphic_length = round($vote_percent * $board_config['vote_graphic_length']);

				$vote_graphic_img = $images['voting_graphic'][$vote_graphic];
				$vote_graphic = ($vote_graphic < $vote_graphic_max - 1) ? $vote_graphic + 1 : 0;

				if ( sizeof($orig_word) )
				{
					$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
				}

				$template->assign_block_vars("poll_option", array(
					'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'],
					'POLL_OPTION_RESULT' => $vote_info[$i]['vote_result'],
					'POLL_OPTION_PERCENT' => sprintf("%.1d%%", ($vote_percent * 100)),

					'POLL_OPTION_LCAP' => $images['voting_graphic_lcap'],
					'POLL_OPTION_RCAP' => $images['voting_graphic_rcap'],
					'POLL_OPTION_IMG' => $vote_graphic_img,
					'POLL_OPTION_IMG_WIDTH' => $vote_graphic_length)
				);
			}

			$template->assign_vars(array(
				'L_TOTAL_VOTES' => $lang['Total_votes'],
				'TOTAL_VOTES' => $vote_results_sum)
			);

		}
		else
		{
			$template->set_filenames(array(
				'pollbox' => 'viewtopic_poll_ballot.tpl')
			);

			if ( $board_config['null_vote'] )
			{
				$template->assign_block_vars('switch_null_vote', array());
			}

			for($i = 0; $i < $vote_options; $i++)
			{
				if ( sizeof($orig_word) )
				{
					$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
				}

				$template->assign_block_vars("poll_option", array(
					'POLL_OPTION_ID' => $vote_info[$i]['vote_option_id'],
					'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'])
				);
			}

			$template->assign_vars(array(
				'L_SUBMIT_VOTE' => $lang['Submit_vote'],
				'L_VIEW_RESULTS' => $lang['View_results'],
				'L_NULL_VOTE' => $lang['Null_vote'],

				'U_VIEW_RESULTS' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;vote=viewresult"))
			);

			$s_hidden_fields = '<input type="hidden" name="topic_id" value="' . $topic_id . '" /><input type="hidden" name="mode" value="vote" />';
		}

		if ( sizeof($orig_word) )
		{
			$vote_title = preg_replace($orig_word, $replacement_word, $vote_title);
		}

		$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

		$ajaxed_poll_menu = '';
		if ($board_config['AJAXed_poll_menu'] && $board_config['AJAXed_status'])
		{
			$ajaxed_poll_menu = "tc();";
			$template->assign_block_vars('ajax_poll', array());
		}
		
		$sql_votes = mysql_query("SELECT * FROM " . VOTE_DESC_TABLE. " vote_desc ORDER BY vote_id"); 
		for ($v = 0; $v < mysql_num_rows($sql_votes); $v++) 
		{
		 	if (mysql_result($sql_votes, $v, "vote_id") == $vote_id) 
		 	{
		 		$vote_nr = $v; 
		    }
		    $vote_end = mysql_result($sql_votes, $vote_nr, "vote_start") + mysql_result($sql_votes, $vote_nr, "vote_length"); 
		    
		    if (time() < $vote_end) 
			{ 
				$vote_end = sprintf($lang['Vote_until']) . ': ' . date('m.d.Y H:i:s', $vote_end); 
			} 
		    else if (mysql_result($sql_votes, $vote_nr, "vote_length") == 0) 
			{ 
				$vote_end = sprintf($lang['Vote_endless']); 
			} 
		    else 
			{ 
				$vote_end = sprintf($lang['Vote_closed']); 
			} 
		}
		
		$template->assign_vars(array(
			'POLL_QUESTION' => $vote_title,

			'AJAXED_POLL_MENU' => $ajaxed_poll_menu,
			'AJAXED_POLL_OPTION_COUNT' => $vote_options, 
			'SAVE' => $lang['Submit'],
			'CANCEL' => $lang['Cancel'],

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_POLL_ACTION' => append_sid("posting.$phpEx?mode=vote&amp;" . POST_TOPIC_URL . "=$topic_id"),
			'U_POLL_NULL_VOTE' => append_sid("posting.$phpEx?mode=vote&amp;" . POST_TOPIC_URL . "=$topic_id&amp;vote=-1"),
		    'VOTE_END' => $vote_end) 
		);

		$template->assign_var_from_handle('POLL_DISPLAY', 'pollbox');
	}
}

init_display_post_attachments($forum_topic_data['topic_attachment']);


//
// Update the forum view counter
//
if ( isset($HTTP_GET_VARS['no']) )
{ 
	$sql = "UPDATE " . FORUMS_TABLE . "
		SET forum_views = forum_views + 1
		WHERE forum_id = $forum_id";
	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Could not update forum views.", '', __LINE__, __FILE__, $sql);
	}
}


//
// Update the topic view counter
//
if ( $postrow[$row_id]['topic_poster'] != $userdata['user_id'] ) 
{
	$sql = "UPDATE " . TOPICS_TABLE . "
		SET topic_views = topic_views + 1
		WHERE topic_id = $topic_id";
	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not update topic view count', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $board_config['enable_topic_view_users'] )
	{
		include($phpbb_root_path . 'includes/functions_user_viewed_posts.'.$phpEx);
		update_user_viewed($userdata['user_id'], $topic_id);
	}
}

//
// Topic thanks
//
if ( $show_thanks == FORUM_THANKABLE )
{
   	$sql = "SELECT u.user_id, u.username, u.user_level, t.thanks_time
    	FROM " . THANKS_TABLE . " t, " . USERS_TABLE . " u
    	WHERE topic_id = $topic_id
        	AND t.user_id = u.user_id";
   	if ( !($result = $db->sql_query($sql)) )
   	{
		message_die(GENERAL_ERROR, "Could not obtain thanks information", '', __LINE__, __FILE__, $sql);
   	}
   	$total_thank = $db->sql_numrows($result);
   	
   	if ( $total_thank > 0 )
   	{
   		$thanksrow = array();
   		$thanksrow = $db->sql_fetchrowset($result);
   		
   		for($i = 0; $i < $total_thank; $i++)
   		{
   			$topic_thanks = $db->sql_fetchrow($result);
   			
    	  	$thanker_id[$i] = $thanksrow[$i]['user_id'];
    	 	$thanker_name[$i] = $thanksrow[$i]['username'];
    	 	$thanker_level[$i] = $thanksrow[$i]['user_level'];
    	 	$thanks_date[$i] = $thanksrow[$i]['thanks_time'];
	
	      	// Get thanks date
	      	$thanks_date[$i] = create_date('d-m, G:i', $thanks_date[$i], $board_config['board_timezone']);
	
	      	// Make thanker profile link
	      	$thanks .= '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$thanker_id[$i]") . '" class="gensmall">' . username_level_color($thanker_name[$i], $thanker_level[$i], $thanker_id[$i]) . '</a> (' . $thanks_date[$i] . '), ';

			if ($userdata['user_id'] == $thanksrow[$i]['user_id'])
			{
				$thanked = TRUE;
			}
	   	}
	
		$sql = "SELECT t.topic_poster, u.user_id, u.username, u.user_level
			FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u
			WHERE t.topic_id = $topic_id
				AND t.topic_poster = u.user_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain user information", '', __LINE__, __FILE__, $sql);
		}
	
		if( !($author = $db->sql_fetchrowset($result)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain topic poster information", '', __LINE__, __FILE__, $sql);
		}	
	
		$author_id = $author[0]['user_id'];
		$author_name = $author[0]['username'];
		$author_level = $author[0]['user_level'];
	
		$thanks .= $lang['thanks_to'] . ' ' . username_level_color($author_name, $author_level, $author_id) . ' ' . $lang['thanks_end'];
	}
}

if ($board_config['viewtopic_usergroups'])
{
	include($phpbb_root_path . 'includes/functions_usergroup.'.$phpEx);
}

//
// Topic kicker
//
if ($board_config['enable_kicker'])
{
	$viewer_kicked = 0;

	// Build list of kicked users
	$sql = "SELECT user_id 
		FROM " . THREAD_KICKER_TABLE . "
		WHERE topic_id = $topic_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get user kicker list', '', __LINE__, __FILE__, $sql);
	}
	
	$tk_x = 1;
	while ( $row = $db->sql_fetchrow($result) )
	{
		if( $row['user_id'] == $userdata['user_id'] )
		{
			$viewer_kicked = 5;
		}
		$kicked_user[$tk_x] = $row['user_id'];
		$tk_x++;
	}
	$db->sql_freeresult($result);
	
	if ( $userdata['user_level'] == ADMIN || $is_auth['auth_mod'] )
	{
		if ( $tk_x != 1 )
		{
			if ( $userdata['kick_ban'] != 1 )
			{	
				$template->assign_vars(array(
					'S_VIEW_KICKED' => '<a href="' . append_sid("thread_kicker.$phpEx?mode=kickview&thread_tag=$topic_id") . '" class="nav" title="' . $lang['tk_kickview'] . '" />' . $lang['tk_kickview'] . '</a>')
				);
			}
		}
	}
	
	$tk_posterid = -5;
	if ( $viewer_kicked != 5 )
	{
		if ( $board_config['kicker_setting'] == 2 )
		{
			if ( $userdata['kick_ban'] != 1 )
			{
				$sql = "SELECT topic_poster 
					FROM " . TOPICS_TABLE . "
					WHERE topic_id = $topic_id";
				if ( !($result = $db->sql_query($sql)) )
	            {
	            	message_die(GENERAL_ERROR, 'Could not obtain topic starter for topic kickers', '', __LINE__, __FILE__, $sql);
	            }
				$row = $db->sql_fetchrow($result);
				
				$tk_posterid = $row['topic_poster'];
				
				if ( $tk_posterid == $userdata['user_id'] )
				{
					if ( $tk_x != 1 )
					{
						$s_view_kicked = '<br /><a href="' . append_sid("thread_kicker.$phpEx?mode=kickview&thread_tag=$topic_id") . '">' . $lang['tk_kickview'] . '</a>';
				
						$template->assign_vars(array(
							'S_VIEW_KICKED' => $s_view_kicked)
						);
					}
				}
			}
		}
	}
}


//
// Avatar Suite (Voting)
//
if ($board_config['avatar_voting_viewtopic'])
{	
	include ($phpbb_root_path . 'includes/functions_avatarsuite.'.$phpEx);
	avatarvote_get_previous_votings($previous_votings_of_current_viewer);
}


//
// Ignore Signature/Avatar
//
if ($board_config['enable_ignore_sigav'])
{
	$sql = "SELECT hid_id 
		FROM " . HIDE_TABLE . " 
		WHERE user_id = " . $userdata['user_id']; 
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
	   message_die(GENERAL_ERROR, 'Could not obtain ignored signature/avatar data.', '', __LINE__, __FILE__, $sql); 
	} 

	$hid_ids = array(); 
	while( $row = $db->sql_fetchrow($result) ) 
	{ 
	   $hid_ids[$row['hid_id']] = true; 
	}
	$db->sql_freeresult($result);
}


//
// Okay, let's do the loop, yeah come on baby let's do the loop
// and it goes like this ...
//
$this_year = create_date('Y', time(), $board_config['board_timezone']);
$this_date = create_date('md', time(), $board_config['board_timezone']);

$overall_total_posts = get_db_stat('postcount'); 

//
// Ratings
//
$rating_config = get_rating_config('1, 2, 14, 18');
if ( $rating_config[1] == 1 && $forum_topic_data['auth_view'] < 3 && $forum_topic_data['auth_read'] < 3 )
{
	get_rating_ranks();

	// Show dropdown box for ratings screen if appropriate 
	if ( $rating_config[18] == 1 )
	{
		$u_ratings = append_sid($phpbb_root_path . 'ratings.'.$phpEx);
		$template->assign_block_vars('ratingsbox', array(
			'U_RATINGS' => $u_ratings,
			'L_LATEST_RATINGS' => $lang['Latest_ratings'],
			'L_HIGHEST_RANKED_POSTS' => $lang['Highest_ranked_posts'],
			'L_HIGHEST_RANKED_TOPICS' => $lang['Highest_ranked_topics'],
			'L_HIGHEST_RANKED_POSTERS' => $lang['Highest_ranked_posters'])
		);
	}
	$target_window = ( $rating_config[14] == 1 ) ? 'phpbb_rating' : '_self';
}

for($i = 0; $i < $total_posts; $i++)
{
	$temp_url = '';
	if ( sizeof($post_rank_set) > 0 && ( $i == 0 || $rating_config[2] == 0 ) )
	{
		$post_rating = ( $postrow[$i]['rating_rank_id'] > 0 ) ? $lang['Rate_post'] . ' ' . $post_rank_set[$postrow[$i]['rating_rank_id']] : $lang['Rate_post'];
		$rating_url = append_sid('rating.'.$phpEx.'?' . POST_POST_URL . '=' . $postrow[$i]['post_id']);
		$post_rating = ' &bull; <a href="' . $rating_url . '" target="' . $target_window . '" OnClick="window.open(\'' . $rating_url . '\',\'' . $target_window . '\',\'width=400,height=600,resize\')" class="postdetails">' . $post_rating . '</a>';
	}
	else
	{
		$post_rating = '';
	}

	$poster_id = $postrow[$i]['user_id'];
	$poster = ( $poster_id == ANONYMOUS ) ? $lang['Guest'] : $postrow[$i]['username'];
	$post_date = create_date($board_config['default_dateformat'], $postrow[$i]['post_time'], $board_config['board_timezone']);

	if ( $board_config['time_today'] < $postrow[$i]['post_time'])
	{ 
		$post_date = sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $postrow[$i]['post_time'], $board_config['board_timezone'])); 
	}
	else if ( $board_config['time_yesterday'] < $postrow[$i]['post_time'])
	{ 
		$post_date = sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $postrow[$i]['post_time'], $board_config['board_timezone'])); 
	}

	$temp_url = append_sid("search.$phpEx?search_author=" . urlencode($postrow[$i]['username'])); 
	$search = ( $postrow[$i]['user_id'] != ANONYMOUS ) ? '<b>' . $lang['Posts'] . ':</b> ' . '<a href="' . $temp_url . '" title="' . sprintf($lang['Search_user_posts'], $postrow[$i]['username']) . '" class="postdetails">' . $postrow[$i]['user_posts'] . '</a><br />' : ''; 

	$poster_from = ( $postrow[$i]['user_from'] && $postrow[$i]['user_id'] != ANONYMOUS ) ? '<b>' . $lang['Location'] . ':</b> ' . $postrow[$i]['user_from'] . '<br />' : '';

	$poster_joined = ( $postrow[$i]['user_id'] != ANONYMOUS ) ? '<b>' . $lang['Joined'] . ':</b> ' . create_date($lang['DATE_FORMAT'], $postrow[$i]['user_regdate'], $board_config['board_timezone']) : '';

	$poster_xd = ( $postrow[$i]['user_id'] != ANONYMOUS ) ? get_user_xdata($postrow[$i]['user_id']) : array();

	$poster_custom_post_color = ( $board_config['allow_custom_post_color'] && $postrow[$i]['user_custom_post_color'] && $postrow[$i]['user_id'] != ANONYMOUS ) ? $postrow[$i]['user_custom_post_color'] : '';

	//
	// Generate strings, set them to an empty string initially.
	//
	$poster_rank = $rank_image = $gender_image = $applaud_img = $applaud_url = $smite_img = $smite_url = $yearstars = $myInfo_img = $cake = $temp_url = $poster_from_flag = $poster_avatar = '';
	
	if ( !empty($postrow[$i]['user_from_flag']) && $postrow[$i]['user_from_flag'] != 'blank.gif' && $board_config['viewtopic_flag'])
	{ 
		$poster_from_flag = '<img src="images/flags/' . $postrow[$i]['user_from_flag'] . '" alt="' . $lang['Country_Flag'] . ': '; 
		$sql = "SELECT flag_name 
			FROM " . FLAG_TABLE . " 
			WHERE flag_image = '" . $postrow[$i]['user_from_flag'] . "'"; 
		if( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not query poster flag name.', '', __LINE__, __FILE__, $sql); 
		} 

		$flag_name = $db->sql_fetchrow($result); 

		$poster_from_flag .= $flag_name['flag_name'] . '" title="' . $lang['Country_Flag'] . ': ' . $flag_name['flag_name'] . '" width="32" height="20" /><br />'; 
	} 

	if ( $postrow[$i]['user_avatar_type'] && $poster_id != ANONYMOUS && $postrow[$i]['user_allowavatar'] && $userdata['user_showavatars'] && $userdata['avatar_sticky'])
	{
		switch( $postrow[$i]['user_avatar_type'] )
		{
			case USER_AVATAR_UPLOAD:
				$poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $postrow[$i]['user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $postrow[$i]['user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_GALLERY:
				$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $postrow[$i]['user_avatar'] . '" alt="" title="" />' : '';
				break;
		}
	}
	else if ( ($postrow[$i]['current_user_avatar_type']) && $poster_id != ANONYMOUS && $postrow[$i]['user_allowavatar'] && $userdata['user_showavatars'] )
	{
		switch( $postrow[$i]['current_user_avatar_type'] )
		{
			case USER_AVATAR_UPLOAD:
				$poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $postrow[$i]['current_user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $postrow[$i]['current_user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_GALLERY:
				$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $postrow[$i]['current_user_avatar'] . '" alt="" title="" />' : '';
				break;
		}
	}

	if ( (!$poster_avatar) && ($board_config['default_avatar_set'] != 3) )
	{
		if ( ($board_config['default_avatar_set'] == 0) && ($poster_id == ANONYMOUS) && ($board_config['default_avatar_guests_url']) )
		{
			$poster_avatar = '<img src="' . $board_config['default_avatar_guests_url'] . '" alt="" title="" />';
		}
		else if ( ($board_config['default_avatar_set'] == 1) && ($poster_id != ANONYMOUS) && ($board_config['default_avatar_users_url']) )
		{
			$poster_avatar = '<img src="' . $board_config['default_avatar_users_url'] . '" alt="" title="" />';
		}
		else if ($board_config['default_avatar_set'] == 2)
		{
			if ( ($poster_id == ANONYMOUS) && ($board_config['default_avatar_guests_url']) )
			{
				$poster_avatar = '<img src="' . $board_config['default_avatar_guests_url'] . '" alt="" title="" />';
			}
			else if ( ($poster_id != -1) && ($board_config['default_avatar_users_url']) )
			{
				$poster_avatar = '<img src="' . $board_config['default_avatar_users_url'] . '" alt="" title="" />';
			}
		}
	}

	//
	// Define the little post icon
	//
	if ( $userdata['session_logged_in'] && $postrow[$i]['post_time'] > $userdata['user_lastvisit'] && $postrow[$i]['post_time'] > $topic_last_read )
	{
		$mini_post_img = $images['icon_minipost_new'];
		$mini_post_alt = $lang['New_post'];
	}
	else
	{
		$mini_post_img = $images['icon_minipost'];
		$mini_post_alt = $lang['Post'];
	}

	$mini_post_url = append_sid("viewtopic.$phpEx?" . POST_POST_URL . '=' . $postrow[$i]['post_id']) . '#' . $postrow[$i]['post_id'];
	$mini_single_post_url = append_sid("viewpost.$phpEx?" . POST_POST_URL . '=' . $postrow[$i]['post_id']);
	
	if ( $postrow[$i]['user_id'] == ANONYMOUS )
	{
	}
	else if ( $postrow[$i]['user_rank'] )
	{
		$poster_rank = $ranksrow[-1][$postrow[$i]['user_rank']]['rank_title'];
		$rank_image = ( $ranksrow[-1][$postrow[$i]['user_rank']]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[-1][$postrow[$i]['user_rank']]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
	}
	else if ( isset($poster_group[$postrow[$i]['user_id']]) )
	{
		$g = $poster_group[$postrow[$i]['user_id']];
		for($j = 0; $j < $ranksrow[$g]['count']; $j++)
		{
			if ( $postrow[$i]['user_posts'] >= $ranksrow[$g][$j]['rank_min'] )
			{
				$poster_rank = $ranksrow[$g][$j]['rank_title'];
				$rank_image = ( $ranksrow[$g][$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[$g][$j]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
				break;
			}
		}
	}
	else
	{
		for($j = 0; $j < $ranksrow[0]['count']; $j++)
		{
			if ( $postrow[$i]['user_posts'] >= $ranksrow[0][$j]['rank_min'] )
			{
				$poster_rank = $ranksrow[0][$j]['rank_title'];
				$rank_image = ( $ranksrow[0][$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[0][$j]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
				break;
			}
		}
	}

	//
	// Handle anon users posting with usernames
	//
	if ( $poster_id == ANONYMOUS && $postrow[$i]['post_username'] != '' )
	{
		$poster = $postrow[$i]['post_username'];
		$poster_rank = $lang['Guest'];
		$poster_age = ''; 
	}


	//
	// If user is an unregister user (guest), hide all the posters info
	//
	if ( $poster_id != ANONYMOUS && $userdata['session_logged_in'] )
	{
		// Year stars
		if ($board_config['viewtopic_yearstars'])
		{
			$years = floor( (time() - $postrow[$i]['user_regdate']) / (365.25 * 24 * 60 * 60) );
			if ( $years && $postrow[$i]['user_posts'] >= $board_config['year_stars'] )
			{
				for ($y=0; $y < $years; $y++)
				{
					$yearstars .= '<img src="' . $images['year_star'] . '" alt="' . $lang['Year_star'] . '" title="' . $lang['Year_star'] . '" />';
				}
				$yearstars .= '<br />';
			}
		}
		
		$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$poster_id");
		$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>';

	    $refer_img = $email_img = $icq_status_img = $icq_img = '';
		if ( $board_config['referral_enable'] && $board_config['referral_viewtopic'] )
		{
			$temp_url = append_sid('profile.'.$phpEx.'?mode=' . REGISTER_MODE . '&amp;ruid=' . $poster_id);
			$refer_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_refer'] . '" alt="' . $lang['Referral_Viewtopic'] . '" title="' . $lang['Referral_Viewtopic'] . '" /></a>';
		}
		
		if ( !empty($postrow[$i]['user_viewemail']) || $is_auth['auth_mod'] )
		{
			$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $poster_id) : 'mailto:' . $postrow[$i]['user_email'];
			$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
		}

		if ( !empty($postrow[$i]['user_icq']) )
		{
			$icq_status_img = '<a href="http://wwp.icq.com/' . $postrow[$i]['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $postrow[$i]['user_icq'] . '&img=5" width="18" height="18"  /></a>';
			$icq_img = '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $postrow[$i]['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" /></a>';
		}

		$temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$poster_id");
		$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';
		$pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';
	
		$www_img = ( $postrow[$i]['user_website'] ) ? '<a href="' . $postrow[$i]['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '';
		$stumble_img = ( $postrow[$i]['user_stumble'] ) ? '<a href="' . $postrow[$i]['user_stumble'] . '" target="_userstumble"><img src="' . $images['icon_stumble'] . '" alt="' . $lang['View_stumble'] . '" title="' . $lang['View_stumble'] . '" /></a>' : '';
		$aim_img = ( $postrow[$i]['user_aim'] ) ? '<a href="aim:goim?screenname=' . $postrow[$i]['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="http://big.oscar.aol.com/' . $postrow[$i]['user_aim'] . '?on_url=' . $images['icon_aim_online'] . '&off_url=' . $images['icon_aim_offline'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" /></a>' : '';
		$yim_img = ( $postrow[$i]['user_yim'] ) ? '<a href="ymsgr:sendIM?' . $postrow[$i]['user_yim'] . '&amp;__you+there?"><img src="http://opi.yahoo.com/online?' . POST_USERS_URL . '=' . $postrow[$i]['user_yim'] . '&amp;m=' . POST_GROUPS_URL . '&amp;' . POST_TOPIC_URL . '=1" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" /></a>' : '';
      	$xfi_img = ( $postrow[$i]['user_xfi'] ) ? '<a href="http://www.xfire.com/xf/modules.php?name=XFire&amp;file=profile&amp;uname=' . $postrow[$i]['user_xfi'] . '" target="_blank"><img src="' . $images['icon_xfi'] . '" alt="' . $lang['XFI'] . '" title="' . $lang['XFI'] . '" /></a>' : '';
   		$skype_img = ( $postrow[$i]['user_skype'] ) ? '<a href="#' . $postrow[$i]['post_id'] . '" onClick=window.open("profile_skype_popup.'.$phpEx.'?' . POST_USERS_URL. '=' . $poster_id . '","gesamt","location=no,menubar=no,toolbar=no,scrollbars=auto,width=320,height=500,status=no",title="Skype")><img src="' . $images['icon_skype'] . '" alt="' . $lang['skype'] . '" title="' . $lang['skype'] . '" /></a>' : '';
    	$skype_user = ( $postrow[$i]['user_skype'] ) ? '<a href="#' . $postrow[$i]['post_id'] . '" onClick=window.open("profile_skype_popup.'.$phpEx.'?' . POST_USERS_URL . '=' . $poster_id . '","gesamt","location=no,menubar=no,toolbar=no,scrollbars=auto,width=320,height=500,status=no",title="Skype")><img alt="' . $lang['skype'] . '" title="' . $lang['skype'] . '" src="http://mystatus.skype.com/smallicon/' . prepare_skype_http($postrow[$i]['user_skype']) . '" /></a>' : '';

		$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$poster_id");
		$msn_img = ( $postrow[$i]['user_msnm'] ) ? '<a href="http://members.msn.com/' . $postrow[$i]['user_msnm'] . '" target="_blank"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" /></a>' : '';
		$gtalk_img = ( $postrow[$i]['user_gtalk'] ) ? '<a href="' . $temp_url . '"><img src="' . $images['icon_gtalk'] . '" alt="' . $lang['GTALK'] . '" title="' . $lang['GTALK'] . '" /></a>' : '';

		$regdate = $postrow[$i]['user_regdate']; 
		$user_posts = $postrow[$i]['user_posts']; 
		
		if ($board_config['viewtopic_extrastats'])
		{
			$memberdays = max(1, round( ( time() - $regdate ) / 86400 )); 
			$posts_per_day = $user_posts / $memberdays; 
			if ( $postrow[$i]['user_posts'] != 0 ) 
			{ 
				$percentage = ( $overall_total_posts ) ? min(100, ($user_posts / $overall_total_posts) * 100) : 0; 
			} 
			else 
			{ 
				$percentage = 0; 
			} 
			$post_day_stats = '&nbsp;' . sprintf($lang['User_post_day_stats'], $posts_per_day) . '<br />'; 
			$post_percent_stats = '&nbsp;' . sprintf($lang['User_post_pct_stats'], $percentage) . '<br />'; 
		}
		
		$buddy_img = ($board_config['viewtopic_buddyimg']) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=addbuddy&amp;' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_USERS_URL . '=' . $poster_id) . '"><img src="' . $images['icon_buddy'] . '" alt="' . $lang['Add_buddy'] . '" title="' . $lang['Add_buddy'] . '" /></a>' : '';

		$poster_time = ($board_config['usertime_viewtopic']) ? '<b>' . $lang['Local_time'] . ':</b> ' . @gmdate("g:i a", time() + (3600 * $postrow[$i]['user_timezone'])) . '<br />' : '';

		// Karma
		if ( $board_config['allow_karma'] )
		{
			$poster_karma = '<b>' . $lang['Karma'] . ':</b> +' . $postrow[$i]['karma_plus'] . '/-' . $postrow[$i]['karma_minus'];
			if ( $userdata['user_id'] != $poster_id )
			{
				$applaud_url = append_sid('viewtopic_karma.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_USERS_URL . '=' . $poster_id . '&amp;x=applaud');
				$applaud_img = '<img src="' . $images['icon_applaud'] . '" width="11" height="11" alt="' . $lang['Applaud'] . '" title="' . $lang['Applaud'] . '" />';
		
				$smite_url = append_sid('viewtopic_karma.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id . '&amp;' . POST_USERS_URL . '=' . $poster_id . '&amp;x=smite');
				$smite_img = '<img src="' . $images['icon_smite'] . '" width="11" height="11" alt="' . $lang['Smite'] . '" title="' . $lang['Smite'] . '" />';
			}
			$smite_img .= '<br />';
		}		
		
		// Birthday (Age, Zodiacs, Cake)
		if ( $postrow[$i]['user_birthday'] != 999999 && $board_config['birthday_viewtopic'] ) 
		{ 
			$poster_birthdate = realdate('md', $postrow[$i]['user_birthday']);
			$n = 0;
			while ($n < 26)
			{
				if ($poster_birthdate >= $zodiacdates[$n] & $poster_birthdate <= $zodiacdates[$n+1])
				{
					$zodiac = $lang[$zodiacs[($n/2)]];
					$u_zodiac = $images[$zodiacs[($n/2)]];
					$zodiac_img = '<img src="' . $u_zodiac . '" alt="' . $lang['Zodiac'] . ': ' . $zodiac . '" title="' . $lang['Zodiac'] . ': ' . $zodiac . '" />'; 
					$n = 26;			
				} 
				else 
				{
					$n = $n + 2;
				}
			}
		
		   	$poster_age = $this_year - realdate('Y', $postrow[$i]['user_birthday']); 
			if ( $this_date < $poster_birthdate ) 
			{
				$poster_age--; 
			}
			$chinese = get_chinese_year(realdate('Ymd', $postrow[$i]['user_birthday']));
			$u_chinese = $images[$chinese];
			$chinese_img = ( $chinese == 'Unknown' ) ? '' : ' &nbsp;<img src="' . $u_chinese . '" alt="' . $lang['Chinese_zodiac'] . ': ' . $lang[$chinese] . '" title="' . $lang['Chinese_zodiac'] . ': ' . $lang[$chinese] . '" />';
	
			if ( $this_date == $poster_birthdate )
        	{
        		$cake = '<img src="' . $images['icon_cake'] . '" alt="' . $lang['Greeting_Messaging'] . ' ' . $poster . ', ' . $poster_age . ' ' . $lang['Years_old'] . '" title="' . $lang['Greeting_Messaging'] . ' ' . $poster . ', ' . $poster_age . ' ' . $lang['Years_old'] . '" /><br />';
	      	}
        	
			$poster_age = '<b>' . $lang['Age'] . ':</b> ' . $poster_age . '<br />'; 
        }
		else 
		{
			$poster_birthdate = $cake = $chinese = $u_chinese = $chinese_img = $zodiac = $u_zodiac = $zodiac_img = $poster_age = '';
		}

		// Gender
		if ($board_config['gender_viewtopic'])
		{
			switch ( $postrow[$i]['user_gender'] ) 
			{ 
				case 1: 
					$gender_image = '<b>' . $lang['Gender'] . ':</b> <img src="' . $images['icon_minigender_male'] . '" alt="' . $lang['Gender'] . ':' . $lang['Male'] . '" title="' . $lang['Gender'] . ': ' . $lang['Male'] . '" /><br />'; 
					break; 
				case 2: 
					$gender_image = '<b>' . $lang['Gender'] . ':</b> <img src="' . $images['icon_minigender_female'] . '" alt="' . $lang['Gender'] . ':' . $lang['Female'] . '" title="' . $lang['Gender'] . ': ' . $lang['Female'] . '" /><br />'; 
					break; 
			    default: 
			    	$gender_image = ''; 
					break; 
			} 
		}

		// Points system
		if ( ($board_config['points_post'] || $board_config['points_browse']) && $board_config['points_viewtopic'] )
		{
			$user_points = ($userdata['user_level'] == ADMIN || user_is_authed($userdata['user_id'])) ? '<a href="' . append_sid("pointscp.$phpEx?" . POST_USERS_URL . "=" . $postrow[$i]['user_id']) . '" title="' . sprintf($lang['Points_link_title'], $board_config['points_name']) . '" class="postdetails">' . $board_config['points_name'] . '</a>' : $board_config['points_name'];
			$user_points = '<b class="postdetails">' . $user_points . ':</b> ' . $postrow[$i]['user_points'];
		
			if ($board_config['points_donate'] && $userdata['user_id'] != ANONYMOUS && $userdata['user_id'] != $poster_id)
			{
				$user_points .= ' (' . sprintf($lang['Points_donate'], '<a href="' . append_sid("pointscp.$phpEx?mode=donate&amp;" . POST_USERS_URL . "=" . $postrow[$i]['user_id']) . '" title="' . sprintf($lang['Points_link_title_2'], $board_config['points_name']) . '" class="postdetails">', '</a>)<br />');
			}
			else
			{
				$user_points .= '<br />';
			}
		}
		
		// Jobs
		if ($board_config['jobs_status'] && $board_config['jobs_viewtopic'])
		{
			$current_user_id = $postrow[$i]['user_id'];

			if (empty($jobs_array[$current_user_id][0]))
			{
				$sql = "SELECT job_name
					FROM " . EMPLOYED_TABLE . "
					WHERE user_id = " . $postrow[$i]['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain user jobs', '', __LINE__, __FILE__, $sql);
				}
				$sql_count = $db->sql_numrows($result);
		
				$jobs_array[$current_user_id] = array();
				for ($iv = 0; $iv < $sql_count; $iv++)
				{
					if (!( $row = $db->sql_fetchrow($result) ))
					{
						message_die(GENERAL_ERROR, 'Could not obtain user jobs', '', __LINE__, __FILE__, $sql);
					}
	
					$jobs_array[$current_user_id][] = $row['job_name'];
					$var2 = $row['job_name'];
				}
			}
			if (!empty($jobs_array[$current_user_id][0]))
			{
				$jobs = implode(', ', $jobs_array[$current_user_id]);
			}
			else
			{
				$jobs = $lang['jobs_unemployed'];
			}
		}
		
		//
		// Ignore Signature/Avatar
		//
		if ($board_config['enable_ignore_sigav'])
		{
			$temp_url = append_sid("viewtopic.$phpEx?hide=hide&amp;t=" . $postrow[$i]['topic_id'] . "&amp;hidee_id=" . $postrow[$i]['user_id']);
			$hide_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_hide'] . '" alt="' . $lang['Hide_sigav'] . '" title="' . $lang['Hide_sigav'] . '" /></a>';
		}			
	}
	else
	{
		$hide_img = $hide = $profile_img = $refer_img = $pm_img = $email_img = $www_img = $stumble_img = $icq_status_img = $icq_img = $aim_img = $msn_img = $yim_img = $xfi_img = $skype_img = $skype_user = $gtalk_img = $post_day_stats = $post_percent_stats = $buddy_img = $poster_time = $applaud_img = $applaud_url = $smite_img = $smite_url = $poster_karma = $poster_birthdate = $chinese = $u_chinese = $chinese_img = $zodiac = $u_zodiac = $zodiac_img = $poster_age = $user_points = $cake = $yearstars = $jobs = '';
	}	

	$back_to_calendar = str_replace("&", "%26", $back_to_calendar);
	$temp_url = append_sid("posting.$phpEx?mode=quote&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id']);
	$quote_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_quote'] . '" alt="' . $lang['Reply_with_quote'] . '" title="' . $lang['Reply_with_quote'] . '" /></a>';
	$quote = '<a href="' . $temp_url . '">' . $lang['Reply_with_quote'] . '</a>';
	
	$ajaxed_post_menu = ( $board_config['AJAXed_post_menu'] && $board_config['AJAXed_status'] ) ? "ug('" . $postrow[$i]['post_id'] . "', '" . $i . "');" : '';

	$edit_time_expired = ( time() - $postrow[$i]['post_time'] < $board_config['post_edit_time_limit'] * 3600 ) ? false : true;
  	$edit_time_expired = ( $board_config['post_edit_time_limit'] == -1 ) ? false : $edit_time_expired;

	if ( ( $userdata['user_id'] == $poster_id && $is_auth['auth_edit'] && !$edit_time_expired ) || $is_auth['auth_mod'] )
	{
		$temp_url = append_sid("posting.$phpEx?mode=editpost&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id']);
		$edit_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit_delete_post'] . '" title="' . $lang['Edit_delete_post'] . '" /></a>';
		$edit = '<a href="' . $temp_url . '">' . $lang['Edit_delete_post'] . '</a>';
		$ajaxed_edit_subject = ( $board_config['AJAXed_post_title'] && $board_config['AJAXed_status'] ) ? "oc('p_" . $i . "_subject', 'p_" . $i . "_edit_subject');" : "";
	}
	else
	{
		$edit_img = $edit = $ajaxed_edit_subject = '';
	}


	//
	// Online / Offline status
	//
	$online_status_img = '';
	if ( $poster_id != ANONYMOUS && $board_config['viewtopic_status'] )
	{
		if ( $postrow[$i]['user_session_time'] >= ( time() - ($board_config['whosonline_time'] * 60) ) )
		{
			if ( $postrow[$i]['user_allow_viewonline'] )
			{
				$online_status_img = '<a href="' . append_sid("viewonline.$phpEx") . '"><img src="' . $images['icon_online'] . '" alt="' . sprintf($lang['is_online'], $poster) . '" title="' . sprintf($lang['is_online'], $poster) . '" /></a>';
			}
			else if( $is_auth['auth_mod'] || $userdata['user_id'] == $poster_id  )
			{
				$online_status_img = '<a href="' . append_sid("viewonline.$phpEx") . '"><img src="' . $images['icon_hidden'] . '" alt="' . sprintf($lang['is_hidden'], $poster) . '" title="' . sprintf($lang['is_hidden'], $poster) . '" /></a>';
			}
			else
			{
				$online_status_img = '<img src="' . $images['icon_offline'] . '" alt="' . sprintf($lang['is_offline'], $poster) . '" title="' . sprintf($lang['is_offline'], $poster) . '" />';
			}
		}
		else
		{
			$online_status_img = '<img src="' . $images['icon_offline'] . '" alt="' . sprintf($lang['is_offline'], $poster) . '" title="' . sprintf($lang['is_offline'], $poster) . '" />';
		}
	}

	
	//
	// Admin only items (hidden from all other users!)
	//
	if( $userdata['user_level'] == ADMIN ) 
	{
		$bank_amount = '';
		if (($board_config['points_post'] || $board_config['points_browse']) && ($board_config['bankopened']))
		{
			$sql = "SELECT holding FROM " . BANK_TABLE . " 
				WHERE user_id = " . $poster_id; 
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not obtain user bank information', '', __LINE__, __FILE__, $sql);
			} 
			$bankrow = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);
			
			$bank_amount =  '<b>' . $lang['Bank'] . ':</b> ' . number_format($bankrow['holding']) . '<br />';
		}
		
		$edit_post_date_id = $postrow[$i]['post_id'];
		$temp_url =  "javascript:window.open('includes/edit_post_date.php?p=$edit_post_date_id','edit_post_date','width=500,height=430');void(0);";
		$edit_date_img = '<a href="' . $temp_url . '"><img src="'  . $images['icon_edit_date'] . '" alt="' . $lang['Edit_post_date'] . '" title="' . $lang['Edit_post_date'] . '" /></a>';
	}
	
	if ( $is_auth['auth_mod'] )
	{
		if ($userdata['user_level'] == ADMIN || $board_config['mods_viewips'])
		{
			$temp_url = "modcp.$phpEx?mode=ip&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;" . POST_TOPIC_URL . "=" . $topic_id . "&amp;sid=" . $userdata['session_id'];
			$ip_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_ip'] . '" alt="' . $lang['View_IP'] . '" title="' . $lang['View_IP'] . '" /></a>';
		
			$ip = decode_ip($postrow[$i]['poster_ip']);
			$ip = '<b>IP:</b> <a href="http://network-tools.com/default.asp?prog=trace&amp;host=' . $ip . '" target="_blank" class="postdetails">' . $ip . '</a><br />';
		}
		
		$temp_url = "posting.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;sid=" . $userdata['session_id'];
		$delpost_ajaxed = ( $board_config['AJAXed_post_delete'] && $board_config['AJAXed_status'] ) ? "onClick=\"pd('" . $postrow[$i]['post_id'] . "','" . $i . "'); return false;\" " : '';
		$delpost_img = '<a ' . $delpost_ajaxed . 'href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>';
		$delpost = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';
	}
	else
	{
		$ip_img = $ip = $edit_date_img = $edit_date = $bank_amount = '';

		if ( $userdata['user_id'] == $poster_id && $is_auth['auth_delete'] && $forum_topic_data['topic_last_post_id'] == $postrow[$i]['post_id'] )
		{
			$temp_url = "posting.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;sid=" . $userdata['session_id'];
			$delpost_ajaxed = ( $board_config['AJAXed_post_delete'] && $board_config['AJAXed_status'] ) ? "onClick=\"pd('" . $postrow[$i]['post_id'] . "','" . $i . "'); return false;\" " : '';
			$delpost_img = '<a ' . $delpost_ajaxed . 'href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>';
			$delpost = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';
		}
		else
		{
			$delpost_img = $delpost = $delpost_ajaxed = '';
		}
	}

	//
	// Ban card system
	//
	if ( $poster_id != ANONYMOUS && $postrow[$i]['user_level'] != ADMIN && $board_config['enable_bancards'] ) 
	{ 
		$current_user = str_replace("'", "\'", $postrow[$i]['username']);
		if ($is_auth['auth_greencard']) 
		{ 
		      $g_card_img = '<input type="image" name="unban" value="unban" onClick="return confirm(\'' . sprintf($lang['Green_card_warning'], $current_user) . '\')" src="' . $images['icon_g_card'] . '" alt="' . $lang['Give_G_card'] . '" title="' . $lang['Give_G_card'] . '" />'; 
		} 
		else 
		{
			$g_card_img = ''; 
		}
		
		$user_warnings = $postrow[$i]['user_warnings'];
		$user_votewarnings = $postrow[$i]['user_votewarnings'];
		
		$card_img = ($user_warnings) ? (( $user_warnings < $board_config['max_user_bancard']) ? sprintf($lang['Warnings'], $user_warnings) : '<span style="color: #' . $theme['adminfontcolor'] . '">' . $lang['Banned'] . '</span>' ) . '<br />' : '';
		
		if ($user_warnings < $board_config['max_user_bancard'] && $is_auth['auth_ban'] )
		{ 
			$y_card_img = '<input type="image" name="warn" value="warn" onClick="return confirm(\'' . sprintf($lang['Yellow_card_warning'], $current_user) . '\')" src="' . $images['icon_y_card'] . '" alt="' . sprintf($lang['Give_Y_card'], $user_warnings + 1) . '" title="' . sprintf($lang['Give_Y_card'], $user_warnings + 1) . '" />'; 
	     	$r_card_img = '<input type="image" name="ban" value="ban" onClick="return confirm(\'' . sprintf($lang['Red_card_warning'], $current_user) . '\')" src="' . $images['icon_r_card'] . '" alt="' . $lang['Give_R_card'] . '" title="' . $lang['Give_R_card'] . '" />'; 
		}
		else
		{
			$y_card_img = $r_card_img = ''; 
		} 
		
		if ($user_votewarnings < $board_config['max_user_votebancard'] && $is_auth['auth_voteban'])
		{ 
			$bk_card_img = '<input type="image" name="voteban" value="voteban" onClick="return confirm(\'' . sprintf($lang['Black_card_warning'], $current_user) . '\')" src="' . $images['icon_bk_card'] . '" alt="' . sprintf($lang['Give_BK_card'], $user_votewarnings + 1) . '" title="' . sprintf($lang['Give_BK_card'], $user_votewarnings + 1) . '" />'; 
		}
		else
		{
			$bk_card_img = '';
		} 
	}
	else
	{
		$card_img = $g_card_img = $y_card_img = $bk_card_img = $r_card_img = '';
	}
	
	if ($is_auth['auth_bluecard'] && $board_config['enable_bancards']) 
	{ 
		if ($is_auth['auth_mod']) 
		{ 
			$b_card_img = (($postrow[$i]['post_bluecard'])) ? '<input type="image" name="report_reset" value="report_reset" onClick="return confirm(\'' . $lang['Clear_blue_card_warning'] . '\')" src="' . $images['icon_bhot_card'] . '" alt="' . sprintf($lang['Clear_b_card'], $postrow[$i]['post_bluecard']) . '" title="' . sprintf($lang['Clear_b_card'], $postrow[$i]['post_bluecard']) . '" />':'<input type="image" name="report" value="report" onClick="return confirm(\'' . $lang['Blue_card_warning'] . '\')" src="'. $images['icon_b_card'] . '" alt="' . $lang['Give_b_card'] . '" title="' . $lang['Give_b_card'] . '" />'; 
		} 
		else 
		{ 
			$b_card_img = '<input type="image" name="report" value="report" src="' . $images['icon_b_card'] . '" alt="' . $lang['Give_b_card'] . '" title="' . $lang['Give_b_card'] . '" />'; 
		}
	} 
	else 
	{
		$b_card_img = '';
	}
	
	// parse hidden fields if cards visible
	$card_hidden = ($g_card_img || $r_card_img || $y_card_img || $bk_card_img || $b_card_img) ? '<input type="hidden" name="post_id" value="' . $postrow[$i]['post_id'] . '" />' : '';
	
	//
	// Capatalization of post subjects
	//
	$post_subject = (!empty($postrow[$i]['post_subject'])) ? $postrow[$i]['post_subject'] : $lang['No_Subject'];
	$post_subject = capitalization($post_subject);

	$message = $postrow[$i]['post_text'];
	$bbcode_uid = $postrow[$i]['bbcode_uid'];


	//
	// Cache user signatures
	//
	$user_sig = $user_sig_bbcode_uid = $cached_sig = ''; 
	if ($board_config['allow_sig'] && $postrow[$i]['enable_sig'] && $postrow[$i]['user_allowsig'] && $userdata['user_showsigs']) 
	{ 
	   if (isset($user_sig_cache[$poster_id])) 
	   { 
	      $cached_sig = $user_sig_cache[$poster_id]; 
	   } 
	   else 
	   { 
	      $user_sig = $postrow[$i]['user_sig']; 
	      $user_sig_bbcode_uid = $postrow[$i]['user_sig_bbcode_uid']; 
	   } 
	} 
	
	
	//
	// Shop - Display items
	//
	$user_items = $usernameurl = '';	
	if ($userdata['session_logged_in'])
	{
		if ( $board_config['viewtopic'] == 2 )
		{
			$itempurge = str_replace('Þ', '', $postrow[$i]['user_items']);
			$itemarray = explode('ß', $itempurge);
			$itemcount = sizeof($itemarray);
	     	for ($xe = 0; $xe < $itemcount; $xe++)
 			{
				if ($itemarray[$xe] != NULL)
	 			{
					if ($board_config['viewtopiclimit'] < $xe) 
					{ 
						$user_items .= '<br /><b><a href="' . append_sid("shop.".$phpEx."?action=inventory&amp;searchid=" . $postrow[$i]['user_id']) . '" title="' . $postrow[$i]['username'] . '\'s ' . $lang['Inventory'] . '" class="postdetails">' . $lang['Items'] . '</a></b>'; 
						break; 
					}
					if (@file_exists('images/shop/' . $itemarray[$xe] . '.jpg'))
					{
						$user_items .= ' <a href="' . append_sid("shop.".$phpEx."?action=inventory&amp;searchid=" . $postrow[$i]['user_id']) . '" title="' . $itemarray[$xe] . '"><img src="images/shop/' . $itemarray[$xe] . '.jpg" title="' . $itemarray[$xe] . '" alt="' . $itemaray[$xe] . '" /></a>';
					}
					else if (@file_exists('images/shop/' . $itemarray[$xe] . '.gif'))
					{
						$user_items .= ' <a href="' . append_sid("shop.".$phpEx."?action=inventory&amp;searchid=" . $postrow[$i]['user_id']) . '" title="' . $itemarray[$xe] . '"><img src="images/shop/' . $itemarray[$xe] . '.gif" title="' . $itemarray[$xe] . '" alt="' . $itemaray[$xe] . '" /></a>';
					}
				}
			}
		}
		else if ( $board_config['viewtopic'] == 1 )
		{
			$usernameurl = ( $poster_id != ANONYMOUS ) ? '<b><a href="' . append_sid("shop.".$phpEx."?action=inventory&amp;searchid=" . $poster_id) . '" title="' . $lang['Items'] . '"  class="postdetails">' . $lang['Items'] . '</a></b><br />' : '';
		}
	}
	
	// Effects Shop
	$shoparray = explode("ß", $board_config['specialshop']);
	$shoparraycount = sizeof($shoparray);
	$shopstatarray = array();
	for ($x = 0; $x < $shoparraycount; $x++)
	{
		$temparray = explode('Þ', $shoparray[$x]);
		$shopstatarray[] = $temparray[0];
		$shopstatarray[] = $temparray[1];
	}

	if ( $shopstatarray[3] == 'enabled' ) 
	{
		$usereffects = explode("ß", $postrow[$i]['user_effects']);
		$userprivs = explode("ß", $postrow[$i]['user_privs']);
		$usercustitle = explode("ß", $postrow[$i]['user_custitle']);
		$userbs = array();
		$usercount = sizeof($userprivs);
		for ($x = 0; $x < $usercount; $x++) 
		{ 
			$temppriv = explode("Þ", $userprivs[$x]); 
			$userbs[] = $temppriv[0]; 
			$userbs[] = $temppriv[1]; 
		}
		$usercount = sizeof($usereffects);
		for ($x = 0; $x < $usercount; $x++) 
		{ 
			$temppriv = explode("Þ", $usereffects[$x]); 
			$userbs[] = $temppriv[0]; 
			$userbs[] = $temppriv[1]; 
		}
		$usercount = sizeof($usercustitle);
		for ($x = 0; $x < $usercount; $x++) 
		{ 
			$temppriv = explode("Þ", $usercustitle[$x]); 
			$userbs[] = $temppriv[0]; 
			$userbs[] = $temppriv[1]; 
		}
		
		if (($userbs[10] == 'on') && ($shopstatarray[12] == 'on')) 
		{ 
			$poster = '<span style="color: #' . $userbs[11] . '">' . $poster . '</span>'; 
		}
		
		if ((($userbs[12] == 'on') && ($shopstatarray[14] == 'on')) || (($userbs[14] == 'on') && ($shopstataray[16] = 'on'))) 
		{
			$nameeffects = "<span style=\"width:100";
			
			if (($userbs[12] == 'on') && ($shopstatarray[14] == 'on')) 
			{ 
				$nameeffects .= "; filter:shadow(color=#" . $userbs[13] . ", strength=5)"; 
			}
			
			if (($userbs[14] == 'on') && ($shopstatarray[16] == 'on')) 
			{ 
				$nameeffects .= "; filter:glow(color=#" . $userbs[15] . ", strength=5)"; 
			}
			
			$nameeffects .= '">' . $poster . '</span>';
			$poster = $nameeffects;
		}
		
		if ((($userbs[24] == 'on') && ($shopstatarray[24] == 'on')) || (($userbs[20] == 'on') && ($shopstatarray[22] == 'on')) || (($userbs[22] == 'on') && ($shopstataray[20] = 'on')) || (($userbs[18] == 'on') && ($shopstatarray[18] == 'on'))) 
		{
			$titleeffects = '<span style="height:10';
			
			if (($userbs[22] == 'on') && ($shopstatarray[20] == 'on')) 
			{ 
				$titleeffects .= "; filter:shadow(color=#" . $userbs[23] . ", strength=5)"; 
			}
			
			if (($userbs[20] == 'on') && ($shopstatarray[22] == 'on')) 
			{ 
				$titleeffects .= "; filter:glow(color=#" . $userbs[21] . ", strength=5)"; 
			}
			
			if (($userbs[24] == 'on') && ($shopstatarray[24] == 'on')) 
			{ 
				$poster_rank = $userbs[25]; 
			}
			
			if (($userbs[18] == 'on') && ($shopstatarray[18] == 'on')) 
			{ 
				$poster_rank = '<span style="color:#' . $userbs[19] . '">' . $poster_rank . '</span>'; 
			}
			
			$titleeffects .= '">' . $poster_rank . '</span>';
			$poster_rank = $titleeffects;
		}
		
		if (($shopstatarray[6] == 'on') && ($userbs[2] != 'on') && ($poster_rank != 'Site Admin')) 
		{ 
			$poster_avatar = ''; 
		}
		
		if (($shopstatarray[8] == 'on') && ($userbs[4] != 'on') && ($poster_rank != 'Site Admin')) 
		{ 
			$user_sig = ''; 
		}
		
		if (($shopstatarray[10] == 'on') && ($userbs[6] != 'on') && ($poster_rank != "Site Admin")) 
		{ 
			$poster_rank = $lang['None']; 
			$rank_image = ''; 
		}
	}


	//
	// Note! The order used for parsing the message _is_ important, moving things around could break any
	// output
	//

	//
	// If the board has HTML off but the post has HTML
	// on then we process it, else leave it alone
	//
	if ( !$board_config['allow_html'] || !$userdata['user_allowhtml'] )
	{
		if ( $user_sig != '' )
		{
			$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
		}

		if ( $postrow[$i]['enable_html'] )
		{
			$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
		}
	}

	//
	// Parse message and/or sig for BBCode if reqd
	//
	if ( $user_sig != '' && $user_sig_bbcode_uid != '' )
	{
		$user_sig = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($user_sig, $user_sig_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $user_sig);
	}
	if ( $bbcode_uid != '' )
	{
		$message = ($board_config['allow_bbcode']) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace("/\:$bbcode_uid/si", '', $message);
	}

	if ( $user_sig != '' )
	{
		$user_sig = make_clickable($user_sig);
	}
	$message = make_clickable($message);

 	//
 	// ed2k link and add all
	//
	$message = make_addalled2k_link($message, $postrow[$i]['post_id']);

	//
	// Parse smilies
	//
	if ( $board_config['allow_smilies'] )
	{
		if ( $postrow[$i]['user_allowsmile'] && $user_sig != '' )
		{
			$user_sig = smilies_pass($user_sig, $forum_id);
		}

		if ( $postrow[$i]['enable_smilies'] )
		{
			$message = smilies_pass($message, $forum_id);
		}
	}
	else
	{
		if( $board_config['smilie_removal1'] )
		{
			$message = smilies_code_removal($message);
		}
	}

	//
	// Highlight active words (primarily for search)
	//
	if ($highlight_match)
	{
		// This has been back-ported from 3.0 CVS
		$message = preg_replace('#(?!<.*)(?<!\w)(' . $highlight_match . ')(?!\w|[^<>]*>)#i', '<b style="color:#'.$theme['fontcolor4'].'">\1</b>', $message);
	}

	//
	// Replace naughty words
	//
	if( !empty($orig_word) )
	{
		$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);

		if ($user_sig != '')
		{
			$user_sig = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $user_sig . '<'), 1, -1));
		}

		$message = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));

		@reset($poster_xd);
		while ( list($code_name, ) = each($poster_xd) )
		{
			$poster_xd[$code_name] = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $poster_xd[$code_name] . '<'), 1, -1));
		}
	}

	//
	// Replace newlines (we use this rather than nl2br because
	// till recently it wasn't XHTML compliant)
	//
	if (!empty($cached_sig)) 
	{ 
	   	$user_sig = $cached_sig; 
	} 
	elseif (!empty($user_sig)) 
	{ 
		// Limit signature rows to board limits...
		if (!empty($board_config['max_sig_lines']) && $board_config['max_sig_lines'] > 0) 
		{
			$user_sig = preg_replace("/^((?:.*?\n){" . $board_config['max_sig_lines'] . "})(?:.|\n)+/i", "\\1", $user_sig);
		}
		
		$user_sig = '_________________<br />' . str_replace("\n", "\n<br />\n", $user_sig); 
	   	$user_sig_cache[$poster_id] = $user_sig; 
	} 

	$message = str_replace("\n", "\n<br />\n", $message);
	$message = word_wrap_pass($message);

	//
	// Editing information
	//
	$l_edited_by = '';
	if ( $board_config['display_last_edited'] )	
	{
		$sql = "SELECT pe.*, u.username
			FROM " . POSTS_EDIT_TABLE . " pe, " . USERS_TABLE . " u
			WHERE pe.post_id = " . $postrow[$i]['post_id'] . "
				AND pe.user_id = u.user_id
			ORDER BY pe.post_edit_time DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain posts edit count information', '', __LINE__, __FILE__, $sql);
		}
	
		while ( $row = $db->sql_fetchrow($result) )
		{
			$l_edit_time_total = ( $row['post_edit_count'] == 1 ) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];
			$l_edited_by .= sprintf($l_edit_time_total, $row['username'], create_date($board_config['default_dateformat'], $row['post_edit_time'], $board_config['board_timezone']), $row['post_edit_count']) . '<br />';
		}
		$db->sql_freeresult($result);
	}
	
	//
	// Post icon
	//
	if ( $postrow[$i]['post_icon'] == 0 ) 
	{ 
		$icon = ''; 
	} 
	else 
	{ 
		$icon = '<img src="' . $images['msg_icons'] . $postrow[$i]['post_icon'] . '.gif" alt="' . $lang['Topic_icon'] . '" title="' . $lang['Topic_icon'] . '" />'; 
	} 

	//
	// Calendar events
	//
	$message = mycal_show_event($topic_id, $postrow[$i]['post_id'], $message);

	//
	// Profile photo link
	//
   	$profile_photo = ( $postrow[$i]['user_photo'] && $board_config['viewtopic_profilephoto'] ) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $poster_id . '#photo') . '" class="postdetails">' . $lang['Profile_photo'] . '</a><br />' : '';
	
	//
	// Photo Album Link 
	//
    $gallery_img = $gallery = $temp_alt_title = '';

	$sql = "SELECT COUNT(*) AS pic_count
    	FROM " . ALBUM_TABLE . "
    	WHERE pic_cat_id = 0
      		AND pic_user_id = $poster_id";
	if (!$pic_result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not obtain Personal Gallery information', '', __LINE__, __FILE__, $sql);
	}
	$pic_count = $db->sql_fetchrow($pic_result);

	if ( $pic_count['pic_count'] )
	{
    	$gallery_img = '<a href="' . append_sid('album_personal.'.$phpEx.'?user_id=' . $poster_id) . '"><img src="' . $images['icon_gallery'] . '" alt="' . sprintf($lang['Personal_Gallery_Of_User'], $postrow[$i]['username']) . '" title="' . sprintf($lang['Personal_Gallery_Of_User'], $postrow[$i]['username']) . '" /></a>';
	}
	$db->sql_freeresult($pic_result);

	//
	// Topic kicker
	//
	if ($board_config['enable_kicker'])
	{
		if ( $viewer_kicked != 5 ) 
		{ 
			if ( $userdata['kick_ban'] != 1 ) 
			{ 
				$tk_post_id = $postrow[$i]['post_id']; 
				$thread_kick_img[$i] = ''; 
				$kicker = $userdata['user_id']; 
				$thisthread_modstatus = $is_auth['auth_mod']; 
				
				if ( $userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $tk_posterid == $userdata['user_id'] && $poster_id != $userdata['user_id'] || $is_auth['auth_mod'] ) 
				{	 
					if ( $postrow[$i]['user_level'] != ADMIN && $postrow[$i]['user_level'] != LESS_ADMIN && $postrow[$i]['user_level'] != MOD ) 
					{ 
						$thread_kick_img[$i] = '<a href="' . append_sid("thread_kicker.$phpEx?thread_tag=$topic_id&amp;kicked=$poster_id&amp;kicker=$kicker&amp;mstatus=$thisthread_modstatus&amp;post_id=$tk_post_id") . '"><img src="' . $images['icon_kick'] . '" title="' . $lang['tkick_kickbutton'] . '" alt="' . $lang['tkick_kickbutton'] . '" /></a>'; 
						if ($kicked_user) 
						{ 
							$tk_y = 1; 
							while ( $tk_y == $tk_x || $tk_y < $tk_x ) 
							{ 
								if ( $kicked_user[$tk_y] == $poster_id ) 
								{ 
									$thread_kick_img[$i] = '<a href="' . append_sid("thread_kicker.$phpEx?thread_tag=$topic_id&amp;unkicked=$poster_id&amp;unkicker=$kicker&amp;mstatus=$thisthread_modstatus&amp;post_id=$tk_post_id") . '"><img src="' . $images['icon_unkick'] . '" title="' . $lang['tkick_unkickbutton'] . '" alt="' . $lang['tkick_unkickbutton'] . '" /></a>'; 
								} 
								$tk_y++; 
							}	 
						}	 
					} 
				} 
			} 	
		}	 		
	}
		

	//
	// myInfo 
	//
	if ($poster_id != ANONYMOUS)
	{
		$temp_url =  "javascript:window.open('profile_myinfo.".$phpEx."?" . POST_USERS_URL . "=$poster_id','_myInfo','width=500,height=300,resizable=yes'); void(0);";
		$myInfo_img = '<a href="' . $temp_url . '"><img src="'  . $images['myInfo'] . '" alt="' . $lang['myInfo'] . '" title="' . $lang['myInfo'] . '" /></a>';
	}
	else
	{
		$myInfo_img = '';
	}	


	// 
	// Inline Ads
	//
    $inline_ad_code = '';
	$display_ad = ($i == $board_config['ad_after_post'] - 1) || (($board_config['ad_every_post'] != 0) && ($i + 1) % $board_config['ad_every_post'] == 0);
   
   	if ($display_ad)
   	{
   		$display_ad = ($board_config['ad_who'] == ADMIN) || ($board_config['ad_who'] == ANONYMOUS && $userdata['user_id'] == ANONYMOUS) || ($board_config['ad_who'] == USER && $userdata['user_id'] != ANONYMOUS);
   		$ad_no_forums = explode(',', $board_config['ad_no_forums']);
		for ($a = 0; $a < sizeof($ad_no_forums); $a++)
		{
			if ($forum_id == $ad_no_forums[$a])
			{
				$display_ad = false;
				break;	
			}
		}
		if ($board_config['ad_no_groups'] != '')
		{
			$ad_no_groups = explode(',', $board_config['ad_no_groups']);
   			$sql = "SELECT 1
   				FROM " . USER_GROUP_TABLE . "
   				WHERE user_id = " . $userdata['user_id'] . " 
   					AND (group_id = 0";
   			for ($a=0; $a < sizeof($ad_no_groups); $a++)
			{
				$sql .= " OR group_id=" . $ad_no_groups[$a];
   			}
   			$sql .= ")";
   			if ( !($result = $db->sql_query($sql)) )
   			{
				message_die(GENERAL_ERROR, 'Could not query ad information', '', __LINE__, __FILE__, $sql);
   			}
   			if ($row = $db->sql_fetchrow($result))
			{
   				$display_ad = false;
   			}
   			$db->sql_freeresult($result);
		}
		if (($board_config['ad_post_threshold'] != '') && ($userdata['user_posts'] >= $board_config['ad_post_threshold']))
		{
			$display_ad = false;	
		}
   	}
   	
   	if ($display_ad)
   	{
   		$sql = "SELECT ad_code
			FROM " . ADS_TABLE;
   		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query ad information', '', __LINE__, __FILE__, $sql);
		}
		$adRow = array();
		$adRow = $db->sql_fetchrowset($result);
		
		srand((double)microtime() * 1000000);
		$adindex = rand(1, $db->sql_numrows($result)) - 1;
		$db->sql_freeresult($result);
		
   		$inline_ad_code = $adRow[$adindex]['ad_code'];
   	}

	
	//
	// Custom Profile Fields
	//
	$xd_root = $xd_block = array();
	$xd_meta = get_xd_metadata();
	while ( list($code_name, $meta) = each($xd_meta) )
	{
	    if ( isset($poster_xd[$code_name]) )
	    {
		    $value = $poster_xd[$code_name];
	
			if ( !$meta['allow_html'] )
			{
				$value = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $value);
			}
	
			if ( $meta['allow_bbcode'] && $userdata['user_sig_bbcode_uid'] != '')
			{
				$value = bbencode_second_pass($value, $userdata['user_sig_bbcode_uid']);
			}
	
			if ($meta['allow_bbcode'])
			{
				$value = make_clickable($value);
			}
	
			if ( $meta['allow_smilies'] )
			{
				$value = smilies_pass($value);
			}
	
			$value = str_replace("\n", "\n<br />\n", $value);	
	
			if ( $meta['display_posting'] == XD_DISPLAY_ROOT )
			{
	         	$xd_root[$code_name] = $value;
			}
			elseif ( $meta['display_posting'] == XD_DISPLAY_NORMAL )
			{
	        	$xd_block[$code_name] = $value;
			}
		}
	}


 	//
	// Force Topic Read
	//
	if ($board_config['ftr_active'])
	{
		if ( (!$userdata['user_ftr']) && ($userdata['user_id'] != ANONYMOUS) )
		{	
			// They Have Clicked The Link & Are Viewing The Post, So Set Them As Read
			if ($HTTP_GET_VARS['directed'] == 'ftr')
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_ftr = 1, user_ftr_time = " . time() . "
					WHERE user_id = " . $userdata['user_id'];
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Failed to update user force topic data", "", __LINE__, __FILE__, $sql);
				}
			}
			// They Have Not Read the topic Yet
			else
			{
				// It's On, Goto Work
				$sql = "SELECT topic_title, title_compl_infos, title_pos, title_compl_color
					FROM ". TOPICS_TABLE ."
					WHERE topic_id = " . $board_config['ftr_topic'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Failed to select topic title", "", __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);
				$db->sql_freeresult($result);
	
				$topic_title = $row['topic_title'];

				if ( !empty($orig_word) )
				{
					$row['topic_title'] = preg_replace($orig_word, $replacement_word, $topic_title);
				}
        
        		$topic_title = capitalization($topic_title);

				if ($board_config['enable_quick_titles'])
				{
					if ( $row['title_pos'] )
					{
						$topic_title = (empty($row['title_compl_infos'])) ? $topic_title : $topic_title . ' <span style="color: #' . $row['title_compl_color'] . '">' . $row['title_compl_infos'] . '</span>';
					}
					else
					{
						$topic_title = (empty($row['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $row['title_compl_color'] . '">' . $row['title_compl_infos'] . '</span> ' . $topic_title;
					}
				}
						
				// Format message
				$ftr_message = str_replace('*u*', username_level_color($userdata['username'], $userdata['user_level'], $userdata['user_id']), $board_config['ftr_msg']);
				$ftr_message = str_replace('*t*', $topic_title, $ftr_message);
				$ftr_message = str_replace('*l*', '<a href="'. append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $board_config['ftr_topic'] .'&amp;directed=ftr') .'" target="_self">' . $lang['ftr_here'] . '</a>', $ftr_message);
				
				// New Only
				if ($board_config['ftr_who'] == ADMIN)
				{
					// They Have Joined Since FTR Was Installed
					if ($userdata['user_regdate'] > $board_config['ftr_installed'])
					{
						message_die(GENERAL_MESSGAE, $ftr_message);
					}
				}
				// New & Old
				else
				{
					message_die(GENERAL_MESSGAE, $ftr_message);
				}
			}
		}
	}


	//
	// Avatar Suite (Voting)
	//
	if ($board_config['avatar_voting_viewtopic'])
	{	
		// You can add reasons here why the voting form should NOT appear
		// e.g. if you suppress avatars of certain users 
		// or if you want to temporarily disable the voting from viewtopic.php
		if (1 == 0)	
		{	
			$avatarvoteform = ''; // Delete the voting form
		}
		else
		{
			$avatarvoteform = avatarvote_create_voting_form($postrow[$i]['user_avatar'], $postrow[$i]['user_avatar_type']);
		}
	}


	//
	// Ignore Signature/Avatar
	//
	if ($board_config['enable_ignore_sigav'])
	{
   		if ( isset($hid_ids[$postrow[$i]['user_id']]) && $hid_ids[$postrow[$i]['user_id']] ) 
   		{
			$poster_avatar = $avatarvoteform = $user_sig = '';
		
			$temp_url = append_sid("viewtopic.$phpEx?hide=unhide&amp;t=" . $postrow[$i]['topic_id'] . "&amp;hidee_id=" . $postrow[$i]['user_id']);
			$hide_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_unhide'] . '" alt="' . $lang['Unhide_user'] . '" title="' . $lang['Unhide_user'] . '" /></a>';
		}
	}


	//
	// Medal system
	//
	$medal ='';
	if ($board_config['allow_medal_display_viewtopic'] && $poster_id != ANONYMOUS)
	{
		$sql = "SELECT m.medal_id, m.medal_name
			FROM " . MEDAL_TABLE . " m, " . MEDAL_USER_TABLE . " mu
			WHERE mu.user_id = '" . $postrow[$i]['user_id'] . "'
				AND m.medal_id = mu.medal_id
			ORDER BY m.medal_name";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error getting medal information", "", __LINE__, __FILE__, $sql);
		}

		$medal_list = $db->sql_fetchrowset($result);
		$medal_count = sizeof($medal_list);

		$medal_count = ($medal_count) ? '<b>' . $lang['Medals'] . ':</b> <a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $postrow[$i]['user_id'] . "#medal") . '" class="gensmall">' . $medal_count . '</a>' . ' (<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $postrow[$i]['user_id'] . "#medal") . '" class="gensmall">' . $lang['View_More'] . '</a>)<br />' : '<b>' . $lang['Medals'] . ':</b> ' . $lang['None'] . '<br />';
	}
	
	
	//
	// Again this will be handled by the templating
	// code at some point
	//
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('postrow', array_merge(array(
		'ICON' => $icon, 
		'ROW_CLASS' => $row_class,
		'POSTER_NAME' => $poster,
		'POSTER_AGE' => $poster_age, 
		'POSTER_RANK' => $poster_rank,
		'RANK_IMAGE' => $rank_image,
		'YEARSTARS' => $yearstars,
		'POSTER_USER_ID' => ( $poster_id != ANONYMOUS && $board_config['viewtopic_memnum']) ? '<b>' . $lang['Member_number'] . '</b>' . number_format($poster_id)  . '<br />' : '', 
		'POSTER_JOINED' => $poster_joined,
		'POSTER_TIME' => $poster_time,
		'POST_DAY_STATS' => $post_day_stats, 
		'POST_PERCENT_STATS' => $post_percent_stats, 
		'POSTER_FROM' => $poster_from,
		'POSTER_FROM_FLAG' => $poster_from_flag,
		'POSTER_AVATAR' => $poster_avatar . $avatarvoteform,
		'AVATAR_VOTE' => $avatarvoteform,
		'POSTER_GENDER' => $gender_image, 
		'POST_DATE' => $post_date,
		'POST_SUBJECT' => $post_subject,
		'POST_RATING' => $post_rating,
		'POSTER_KARMA' => $poster_karma,
		'MESSAGE' => $message,
		'SIGNATURE' => $user_sig,
		'EDITED_MESSAGE' => $l_edited_by,
		'SEARCH' => $search, 
		'POSTER_TROPHY' => Amod_Build_Topics($hof_data, $postrow[$i]['user_id'], $postrow[$i]['user_trophies'], $postrow[$i]['username'], $postrow[$i]['ina_char_name']),
		'POSTER_STYLE' => (empty($board_config['override_user_style']) && $board_config['viewtopic_style']) ? '<b>' . $lang['Style'] . ':</b> <a href="' . append_sid('changestyle.'.$phpEx.'?' . STYLE_URL . '=' . $postrow[$i]['user_style']) . '" title="' . $lang['Style'] . ': ' . $postrow[$i]['style_name'] . ' (' . $style_counts[$postrow[$i]['user_style']] . ')" class="postdetails">' . $postrow[$i]['style_name'] . '</a> (' . $style_counts[$postrow[$i]['user_style']] . ')<br />' : '',
		'PROFILE_PHOTO' => $profile_photo,
		'POSTER_MEDAL_COUNT' => $medal_count,

		'MINI_POST_IMG' => $mini_post_img,
		'POSTER_STATUS' => $online_status_img,
       	'REFERRAL_IMG' => $refer_img,
		'PROFILE_IMG' => $profile_img,
		'PM_IMG' => $pm_img,
		'EMAIL_IMG' => $email_img,
		'WWW_IMG' => $www_img,
		'STUMBLE_IMG' => $stumble_img,
		'ICQ_STATUS_IMG' => $icq_status_img,
		'ICQ_IMG' => $icq_img,
		'AIM_IMG' => $aim_img,
		'MSN_IMG' => $msn_img,
		'YIM_IMG' => $yim_img,
      	'XFI_IMG' => $xfi_img, 
		'SKYPE_IMG' => $skype_img, 
    	'SKYPE_USER' => $skype_user,
		'GTALK_IMG' => $gtalk_img,
		'EDIT_IMG' => $edit_img,
		'EDIT_DATE_IMG' => ($board_config['viewtopic_editdate']) ? $edit_date_img : '',
		'QUOTE_IMG' => ($stop_bumping == TRUE ) ? '' : $quote_img,
		'IP' => $ip,
		'IP_IMG' => $ip_img,
		'DELETE_IMG' => $delpost_img,
		'BUDDY_IMG' => $buddy_img,
		'GALLERY_IMG' => $gallery_img,
		'CARD_IMG' => $card_img,
		'CARD_HIDDEN_FIELDS' => $card_hidden,
		'CARD_EXTRA_SPACE' => ($r_card_img || $y_card_img || $g_card_img || $bk_card_img || $b_card_img) ? '' : '',
		'ZODIAC_IMG' => $zodiac_img, 
		'CHINESE_IMG' => (!empty($chinese_img)) ? $chinese_img . '<br />' : '',
		'APPLAUD_IMG' => $applaud_img,
		'SMITE_IMG' => $smite_img,
		'BANK_AMOUNT' => $bank_amount,
		'USER_WARNINGS' => $user_warnings,
		'USER_VOTEWARNINGS' => $user_votewarnings,
		'POINTS' => $user_points,
		'ITEMSNAME' => $usernameurl,
		'ITEMS' => ($user_items) ? $user_items . '<br />' : '',
		'ZODIAC' => $zodiac, 
		'CHINESE' => $lang[$chinese],
		'CUSTOM_POST_COLOR' => $poster_custom_post_color,
		'THREAD_KICK_IMG' => $thread_kick_img[$i],
		'MY_INFO_IMG' => $myInfo_img,
		'CAKE' => $cake,
		'JOBS' => ($jobs) ? '<b>' . $lang['jobs'] . ':</b> ' . $jobs . '<br />' : '',
		'MINI_SINGLE_POST' => (!empty($board_config['viewtopic_viewpost'])) ? ' &bull; <a href="' . $mini_single_post_url . '" class="postdetails">' . $lang['View_single_post'] . '</a>' : '',
		'DOWNLOAD_POST' => (!empty($board_config['viewtopic_downpost'])) ? ' &bull; <a href="' . append_sid('viewtopic.'.$phpEx.'?download=' . $postrow[$i]['post_id'] . '&amp;' . POST_TOPIC_URL . '=' . $topic_id) . '" class="postdetails">' . $lang['Download_post'] . '</a>' : '',
		'HIDE_IMG' => ($board_config['enable_ignore_sigav']) ? $hide_img : '',

		'RAW_MESSAGE' => preg_replace('/\:(([a-z0-9]:)?)' . $postrow[$i]['bbcode_uid'] . '/s', '', $postrow[$i]['post_text']),
		'AJAXED_POST_MENU' => $ajaxed_post_menu,
		'AJAXED_EDIT_SUBJECT' => $ajaxed_edit_subject,
		'AJAXED_I' => $i,
						
		'L_CHINESE' => ( $chinese ) ? $lang['Chinese_zodiac'] . ': ' : '',
		'L_MINI_POST_ALT' => $mini_post_alt,
		'L_SPONSOR' => $lang['Sponsor'],

		'U_ZODIAC' => $u_zodiac, 
		'U_CHINESE' => $u_chinese,
		'U_APPLAUD' => $applaud_url,
		'U_SMITE' => $smite_url,
		'U_MINI_POST' => $mini_post_url,
		'U_G_CARD' => $g_card_img, 
		'U_Y_CARD' => $y_card_img, 
		'U_BK_CARD' => $bk_card_img, 
		'U_R_CARD' => $r_card_img, 
		'U_B_CARD' => $b_card_img,
		'S_CARD' => append_sid('card.'.$phpEx),
		'U_POST_ID' => $postrow[$i]['post_id']), $xd_root)
	);

	display_post_attachments($postrow[$i]['post_id'], $postrow[$i]['post_attachment']);
	
	// Usergroups
	if ( $poster_id != ANONYMOUS && $board_config['viewtopic_usergroups'] )
	{
		if (display_usergroups($userdata['user_id'], $poster_id, 'postrow'))
		{
			$template->append_block_vars('postrow',array(
				'L_USER_GROUP' => $lang['Groupcp'] . ':',
				'L_GO' => $lang['Go'])
			);
		} 
		else
		{
			$template->append_block_vars('postrow',array(
				'L_NO_USER_GROUP' => $lang['Groupcp'] . ':',
				'L_NO_USERGROUPS'=> $lang['None'])
			);
		} 
	}
	
	// Thank topic
	if ( ($show_thanks == FORUM_THANKABLE) && ($i == 0) && ($current_page == 1) && ($total_thank > 0) )
	{
		$template->assign_block_vars('postrow.thanks', array(
			'THANKFUL' => $lang['thankful'],
			'THANKED' => $lang['thanked'],
			'HIDE' => $lang['hide'],
			'THANKS_TOTAL' => $total_thank,
			'THANKS' => $thanks)
		);
	}
	
	// Myinfo
	if ( $board_config['myInfo_enable'] ) 
   	{ 
    	$template->assign_block_vars('postrow.switch_myInfo_active', array()); 
    } 
    
    // Inline Ads
    if ( $display_ad )
    {
		if ( !$board_config['ad_old_style'] && $display_ad )
		{
			$template->assign_block_vars('postrow.switch_ad', array(
				'L_SPONSOR' => $lang['Sponsor'],
				'INLINE_AD' => $inline_ad_code)
			);
		}
		else
		{
			$template->assign_block_vars('postrow.switch_ad_style2', array(
				'INLINE_AD' => $inline_ad_code)
			);
		}
	}
	
	// Custom Profile Fields
	@reset($xd_block);
	while ( list($code_name, $value) = each($xd_block) )
	{
		$template->assign_block_vars('postrow.xdata', array(
			'NAME' => $xd_meta[$code_name]['field_name'],
			'VALUE' => $value)
		);
	}

	@reset($xd_meta);
	while ( list($code_name, $value) = each($xd_meta) )
	{
		if (isset($xd_root[$code_name]))
		{
	        $template->assign_block_vars("postrow.switch_$code_name", array());
		}
	   	else
	   	{
	   		$template->assign_block_vars("postrow.switch_no_$code_name", array());
	   	}
	}
	
	// Collapsable userinfo
	if ( $board_config['collapse_userinfo'] ) 
 	{ 
    	$template->assign_block_vars('postrow.collapse_userinfo', array(
    		'L_DETAILS' => $lang['View_userinfo'])
    	); 
    } 
    
    // Medal system
   	if ( $board_config['allow_medal_display_viewtopic'] )
	{
		$template->assign_block_vars('postrow.medal', array());

		$order = ( $board_config['medal_display_order'] ) ? 'RAND()' : 'm.medal_name';
		$sql = "SELECT m.medal_id, m.medal_name, m.medal_image
			FROM " . MEDAL_TABLE . " m, " . MEDAL_USER_TABLE . " mu
			WHERE mu.user_id = '" . $poster_id . "'
			AND m.medal_id = mu.medal_id
			ORDER BY " . $order;
		if ($result = $db->sql_query($sql))
		{
			$rowset = array();
			while ($row = $db->sql_fetchrow($result))
			{
				$rowset[$row['medal_image']]['medal_name'] = $row['medal_name'];
				if ($rowset[$row['medal_image']]['medal_name'] == $row['medal_name'])
				{
					$rowset[$row['medal_image']]['medal_count'] += 1;
				}
			}
			$db->sql_freeresult($result);
		
			$medal_rows = $board_config['medal_display_row'];
			$medal_cols = $board_config['medal_display_col'];
			$medal_width = ( $board_config['medal_display_width'] ) ? 'width="' . $board_config['medal_display_width'] . '"' : '';
			$medal_height = ( $board_config['medal_display_height'] ) ? 'height="' . $board_config['medal_display_height'] . '"' : '';
	
			if ($medal_list)
			{
				$split_row = $medal_cols - 1;
				$s_colspan = $row = $col = 0;

				while (list($medal_image, $medal) = @each($rowset))
				{
					if (!$col)
			       	{ 
						$template->assign_block_vars('postrow.medal.medal_row', array()); 
					}

					$template->assign_block_vars('postrow.medal.medal_row.medal_col', array(
						'MEDAL_IMAGE' => 'images/medals/' . $medal_image,
						'MEDAL_WIDTH' => $medal_width,
						'MEDAL_HEIGHT' => $medal_height,
						'MEDAL_NAME' => $medal['medal_name'],
						'MEDAL_COUNT' => '('. $lang['Medal_amount'] . $medal['medal_count']. ')')
					);

					$s_colspan = max($s_colspan, $col + 1);

					if ($col == $split_row)
					{
						if ($row == $medal_rows - 1) 
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
			}
		}
	}
}

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
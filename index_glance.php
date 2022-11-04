<?php 
/** 
*
* @package phpBB
* @version $Id: index_glance.php,v 2.2.1 2001/04/07 blulegend Exp $
* @copyright (c) 2001 blulegend, Jack Kan
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

define('IN_GLANCE', true);

$glance_news_bullet_old = '<b>&bull;</b>'; // CAN ALSO BE AN IMAGE
$glance_recent_bullet_old = '<b>&bull;</b>'; // CAN ALSO BE AN IMAGE
$glance_news_bullet_new = '<b style="color: #' . $theme['adminfontcolor'] . '">&bull;</b>'; // CAN ALSO BE AN IMAGE
$glance_recent_bullet_new = '<b style="color: #' . $theme['adminfontcolor'] . '">&bull;</b>'; // CAN ALSO BE AN IMAGE

$glance_show_new_bullets = true;
$glance_track = true;
$glance_auth_read = false;
	
$glance_news_forum_id = $board_config['glance_forum_id']; 
$glance_num_news = $board_config['glance_news_num']; 
$glance_num_recent = $board_config['glance_recent_num']; 
$glance_last_visit = $userdata['user_lastvisit'];
	
//
// MESSAGE TRACKING
//
if ( !isset($tracking_topics) && $glance_track ) 
{
	$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : '';
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
// GET THE LATEST NEWS TOPIC
//
if ( $glance_num_news )
{
	$news_data = $db->sql_fetchrow($result);
	if (1)
	{
		$sql_select = ", p.post_time";
		$sql_from = ", " . POSTS_TABLE . " p";
		$sql_where = " AND p.post_id = t.topic_last_post_id";
	}
	
	$sql = "SELECT t.topic_id, t.topic_title, t.topic_time, t.title_compl_infos, t.title_pos, t.title_compl_color" . $sql_select . "
		FROM " . TOPICS_TABLE . " t" . $sql_from . "
		WHERE t.forum_id = " . $glance_news_forum_id . $sql_where . "
			AND t.topic_moved_id = 0
		ORDER BY t.topic_last_post_id DESC
		LIMIT 0, " . $glance_num_news;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not query new news information", "", __LINE__, __FILE__, $sql);
	}
	
	$latest_news = array();
	while ( $topic_row = $db->sql_fetchrow($result) )
	{
		$topic_row['topic_title'] = ( !empty($orig_word) ) ? preg_replace($orig_word, $replacement_word, $topic_row['topic_title']) : $topic_row['topic_title'];
		$topic_row['topic_title_alt'] = $topic_row['topic_title'];
		
		$topic_row['topic_title'] = capitalization($topic_row['topic_title']);
		$topic_row['topic_title_alt'] = capitalization($topic_row['topic_title_alt']);

		if ($board_config['enable_quick_titles'])
		{
			if ( $topic_row['title_pos'] )
			{
				$topic_row['topic_title'] = (empty($topic_row['title_compl_infos'])) ? $topic_row['topic_title'] : $topic_row['topic_title'] . ' <span style="color: #' . $topic_row['title_compl_color'] . '">' . $topic_row['title_compl_infos'] . '</span>';
			}
			else
			{
				$topic_row['topic_title'] = (empty($topic_row['title_compl_infos'])) ? $topic_row['topic_title'] : '<span style="color: #' . $topic_row['title_compl_color'] . '">' . $topic_row['title_compl_infos'] . '</span> ' . $topic_row['topic_title'];
			}
		}
				
		$latest_news[] = $topic_row;
	}
	$db->sql_freeresult($result);
}
	
//
// GET THE LAST 5 TOPICS
//
if ( $glance_num_recent )
{
	$sql = "SELECT forum_id, auth_view 
		FROM " . FORUMS_TABLE . " 
		WHERE forum_id != " . $glance_news_forum_id;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not query new topic information", "", __LINE__, __FILE__, $sql);
	}
	
	$glance_auth_level = ( $glance_auth_read ) ? AUTH_VIEW : AUTH_ALL;

	$unauthed_forums = $is_auth_ary = array();
	$is_auth_ary = auth($glance_auth_level, AUTH_LIST_ALL, $userdata);
	
	$forumsignore = '';

	if ( $num_forums = sizeof($is_auth_ary) )
	{
		while ( list($forum_id, $auth_mod) = each($is_auth_ary) )
		{
			$unauthed = false;
			if ( !$auth_mod['auth_view'] && $auth_mod['forum_id'] != $glance_news_forum_id )
			{
				$unauthed = true;
			}
			if ( !$glance_auth_read && !$auth_mod['auth_read'] && $auth_mod['forum_id'] != $glance_news_forum_id )
			{
				$unauthed = true;
			}
			if ( !$glance_auth_read && !$auth_mod['auth_read'] && !$auth_mod['auth_view'] ) 
			{ 
				$unauthed = true; 
			} 
			if ( $unauthed )
			{
				$forumsignore .= $forum_id . ', ';
			}
		}
	}
		
	$sql = "SELECT t.topic_title, t.topic_id, t.title_compl_infos, t.title_pos, t.title_compl_color, p2.post_time
		FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p2
		WHERE t.forum_id > 0  
			AND t.forum_id NOT IN (" . $forumsignore . $glance_news_forum_id . ") 
			AND p.post_id = t.topic_first_post_id
			AND p2.post_id = t.topic_last_post_id
			AND t.topic_moved_id = 0
		ORDER BY t.topic_last_post_id DESC
		LIMIT 0, " . $glance_num_recent;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not query latest topic information", "", __LINE__, __FILE__, $sql);
	}
	
	$latest_topics = array();
	while ( $topic_row = $db->sql_fetchrow($result) )
	{
		$topic_row['topic_title'] = ( !empty($orig_word) ) ? preg_replace($orig_word, $replacement_word, $topic_row['topic_title']) : $topic_row['topic_title'];
		$topic_row['topic_title_alt'] = $topic_row['topic_title'];

		$topic_row['topic_title'] = capitalization($topic_row['topic_title']);
		$topic_row['topic_title_alt'] = capitalization($topic_row['topic_title_alt']);

		if ($board_config['enable_quick_titles'])
		{
			if ( $topic_row['title_pos'] )
			{
				$topic_row['topic_title'] = (empty($topic_row['title_compl_infos'])) ? $topic_row['topic_title'] : $topic_row['topic_title'] . ' <span style="color: #' . $topic_row['title_compl_color'] . '">' . $topic_row['title_compl_infos'] . '</span>';
			}
			else
			{
				$topic_row['topic_title'] = (empty($topic_row['title_compl_infos'])) ? $topic_row['topic_title'] : '<span style="color: #' . $topic_row['title_compl_color'] . '">' . $topic_row['title_compl_infos'] . '</span> ' . $topic_row['topic_title'];
			}
		}
			
		$latest_topics[] = $topic_row;
	}
	$db->sql_freeresult($result);
}
	
//
// BEGIN OUTPUT
//
$template->set_filenames(array(
	'glance_output' => 'index_glance_body.tpl')
);
	
if ( $glance_num_news )
{
	if ( !empty($latest_news) )
	{
		$bullet_pre = 'glance_news_bullet';
		
		for ($i = 0; $i < sizeof($latest_news); $i++)
		{
			if ( $userdata['session_logged_in'] )
			{
				$unread_topics = false;
				$topic_id = $latest_news[$i]['topic_id'];
				if ( $latest_news[$i]['post_time'] > $glance_last_visit )
				{
					$unread_topics = true;
					if( !empty($tracking_topics[$topic_id]) && $glance_track )
					{
						if( $tracking_topics[$topic_id] >= $latest_news[$i]['post_time'] )
						{
							$unread_topics = false;
						}
					}
				}
				$shownew = $unread_topics;
			}
			else
			{
				$unread_topics = false;
				$shownew = true;
			}

			$bullet_full = $bullet_pre . ( ( $shownew && $glance_show_new_bullets ) ? '_new' : '_old' );
			$newest_code = ( $unread_topics && $glance_show_new_bullets ) ? '&amp;view=newest' : '';	
			$topic_link = append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $latest_news[$i]['topic_id'] . $newest_code);
			$topic_title = word_wrap_pass($latest_news[$i]['topic_title']);
			$topic_title_alt = $latest_news[$i]['topic_title_alt'];

			$template->assign_block_vars('news', array(
				'BULLET' => $$bullet_full,					
				'TOPIC_TITLE' => $topic_title,
				'TOPIC_TITLE_ALT' => $topic_title_alt,
				'TOPIC_LINK' => $topic_link,
				'TOPIC_TIME' => create_date($board_config['default_dateformat'], $latest_news[$i]['post_time'], $board_config['board_timezone']))
			);
		}
	}
	else
	{
		$template->assign_block_vars('none', array(
			'BULLET' => $glance_recent_bullet_old,				
			'L_NONE' => $lang['None'])
		);
	}
}
	
if ( $glance_num_recent )
{
	$glance_info = 'counted recent';
	$bullet_pre = 'glance_recent_bullet';

	if ( !empty($latest_topics) )
	{
		for ($i = 0; $i < sizeof($latest_topics); $i++)
		{
			if ( $userdata['session_logged_in'] )
			{
				$unread_topics = false;
				$topic_id = $latest_topics[$i]['topic_id'];
				if ( $latest_topics[$i]['post_time'] > $glance_last_visit )
				{
					$unread_topics = true;
					if( !empty($tracking_topics[$topic_id]) && $glance_track )
					{
						if( $tracking_topics[$topic_id] >= $latest_topics[$i]['post_time'] )
						{
							$unread_topics = false;
						}
					}
				}
				$shownew = $unread_topics;
			}
			else
			{
				$unread_topics = false;
				$shownew = true;
			}

			$bullet_full = $bullet_pre . ( ( $shownew && $glance_show_new_bullets ) ? '_new' : '_old' );
			$newest_code = ( $unread_topics && $glance_show_new_bullets ) ? '&amp;view=newest' : '';
			$topic_link = append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $latest_topics[$i]['topic_id'] . $newest_code);
			$topic_title = word_wrap_pass($latest_topics[$i]['topic_title']);
			$topic_title_alt = $latest_topics[$i]['topic_title_alt'];
	
			$template->assign_block_vars('recent', array(
				'BULLET' => $$bullet_full,
				'TOPIC_LINK' => $topic_link,
				'TOPIC_TITLE' => $topic_title,
				'TOPIC_TITLE_ALT' => $topic_title_alt)
			);
		}
	}
	else
	{
		$template->assign_block_vars('none2', array(
			'BULLET' => $glance_recent_bullet_old,
			'L_NONE' => $lang['None'])
		);
	}
}
	
if ( $glance_num_news )
{
	$template->assign_block_vars('switch_glance_news', array(
		'NEWS_HEADING' => $board_config['glance_forum_news_title'], 
		'SCROLL_BEGIN' => (!empty($board_config['glance_news_scroll'])) ? '<marquee behavior="scroll" direction="up" width="100%" height="150" scrollamount="1" onMouseover="this.scrollAmount=0" onMouseout="this.scrollAmount=1">' : '',
		'SCROLL_END' => (!empty($board_config['glance_news_scroll'])) ? '</marquee>' : '')
	);
}

if ( $glance_num_recent )
{
	$template->assign_block_vars('switch_glance_recent', array(
		'RECENT_HEADING' => $board_config['glance_forum_discuss_title'],
		'SCROLL_BEGIN' => (!empty($board_config['glance_recent_scroll'])) ? '<marquee behavior="scroll" direction="up" width="100%" height="150" scrollamount="1" onMouseover="this.scrollAmount=0" onMouseout="this.scrollAmount=1">' : '',
		'SCROLL_END' => (!empty($board_config['glance_recent_scroll'])) ? '</marquee>' : '')
	);
}
		
$template->assign_var_from_handle('GLANCE_OUTPUT', 'glance_output');
	
?>
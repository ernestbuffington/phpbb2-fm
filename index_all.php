<?php
/** 
*
* @package phpBB2
* @version $Id: index_all.php,v 1.139.2.6 2002/06/27 21:26:46 dougk_ff7 Exp $
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

// Set to true to disable special topic types...
$no_topic_type = true;

//
// Start initial var setup
//
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

$forum_id = 0;

//
// Start session management
//
$userdata = session_pagestart($user_ip, $forum_id);
init_userprefs($userdata);
//
// End session management
//

//
// Start auth check
//
$is_auth_ary = array();
$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata, $forum_data, $topic_id);
//
// End of auth check
//

//
// Handle marking posts
//
$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : '';
$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) : '';


//
// Generate a 'Show topics in previous x days' select box. If the topicsdays var is sent
// then get it's value, find the number of topics with dates newer than it (to properly
// handle pagination) and alter the main query
//
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['All_Topics'], $lang['1_Day'], $lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year']);

if ( !empty($HTTP_POST_VARS['topicdays']) || !empty($HTTP_GET_VARS['topicdays']) )
{
	$topic_days = ( !empty($HTTP_POST_VARS['topicdays']) ) ? $HTTP_POST_VARS['topicdays'] : $HTTP_GET_VARS['topicdays'];
	$min_topic_time = time() - ($topic_days * 86400);


	$limit_topics_time = "AND p.post_time >= $min_topic_time";

	if ( !empty($HTTP_POST_VARS['topicdays']) )
	{
		$start = 0;
	}
}
else
{
	$sql = "SELECT SUM(forum_topics) 
		FROM " . FORUMS_TABLE;
	if ( !($result = $db->sql_query($sql)) )
    {
    	message_die(GENERAL_ERROR, 'Could not obtain limited topics count information', '', __LINE__, __FILE__, $sql);
    }
    $row = $db->sql_fetchrow($result);

    $topics_count = ( $row['forum_topics'] ) ? $row['forum_topics'] : 1;

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
$sql = "SELECT t.*, u.username, u.user_id, u.user_level, u2.user_level AS user_level2, u2.username as user2, u2.user_id as id2, p.post_time, p.post_username, pt.post_text, pt.bbcode_uid 
   FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2, " . POSTS_TEXT_TABLE . " pt
   WHERE t.topic_poster = u.user_id 
      AND p.post_id = t.topic_last_post_id 
 	  AND p.post_id = pt.post_id
      AND p.poster_id = u2.user_id 
      AND t.topic_type = " . POST_GLOBAL_ANNOUNCE . " 
   ORDER BY t.topic_priority DESC, p.post_time DESC, t.topic_last_post_id DESC"; 
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
if ( $no_topic_type )
{
	$sql = "SELECT t.*, u.username, u.user_id, u.user_level, u2.user_level AS user_level2, u2.username as user2, u2.user_id as id2, p.post_time, p.post_username, pt.post_text, pt.bbcode_uid
		FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . USERS_TABLE . " u2, " . POSTS_TEXT_TABLE . " pt
	    WHERE t.topic_poster = u.user_id
	    	AND p.post_id = t.topic_last_post_id
			AND p.post_id = pt.post_id
	        AND p.poster_id = u2.user_id
	        AND t.topic_type = " . POST_ANNOUNCE . "
		ORDER BY t.topic_last_post_id DESC ";
	if ( !($result = $db->sql_query($sql)) )
	{
	   message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$is_auth = auth(AUTH_READ, $row['forum_id'], $userdata); 
		if ( $is_auth['auth_read'] ) 
		{ 
		   	$topic_rowset[] = $row;
		    $total_announcements++;
		}
	}
	
	$db->sql_freeresult($result);
}

//
// Grab all the basic data (all topics except globals/announcements)
// for this forum
//
$topic_type_sql1 = $no_topic_type ? '' : 'AND t.topic_type <> ' . POST_GLOBAL_ANNOUNCE;
$topic_type_sql2 = $no_topic_type ? '' : 'AND t.topic_type <> ' . POST_ANNOUNCE;
$topic_type_sql3 = $no_topic_type ? '' : 't.topic_type DESC,';
$sql = "SELECT t.*, u.username, u.user_id, u.user_level, u2.user_level AS user_level2, u2.username as user2, u2.user_id as id2, p.post_username, p2.post_username AS post_username2, p2.post_time, pt.post_text, pt.bbcode_uid
	FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u2, " . POSTS_TEXT_TABLE . " pt
	WHERE t.topic_poster = u.user_id
		AND p.post_id = t.topic_first_post_id
		AND p2.post_id = t.topic_last_post_id
		AND p2.post_id = pt.post_id
		AND u2.user_id = p2.poster_id
                $topic_type_sql1
                $topic_type_sql2
		$limit_topics_time
	ORDER BY $topic_type_sql3 t.topic_last_post_id DESC
	LIMIT $start, ".$board_config['topics_per_page'];
if ( !($result = $db->sql_query($sql)) )
{
   message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
}

$total_topics = 0;
while( $row = $db->sql_fetchrow($result) )
{
	$is_auth = auth(AUTH_READ, $row['forum_id'], $userdata); 
	if ( $is_auth['auth_read'] ) 
	{ 
		$topic_rowset[] = $row; 
		$total_topics++; 
	}
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

	'S_SELECT_TOPIC_DAYS' => $select_topic_days,
	'S_POST_DAYS_ACTION' => append_sid("index_all.$phpEx?start=$start"))
);

//
// Mozilla navigation bar
//
$nav_links['up'] = array(
	'url' => append_sid('index_all.'.$phpEx),
	'title' => sprintf($lang['All_forums'], $board_config['sitename'])
);

//
// Dump out the page header and load viewforum template
//
$page_title = $lang['All_forums'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'index_all_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'FORUM_NAME' => $lang['All_forums'],

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
	'ANSWERED_TOPIC_IMG' => $images['icon_mini_answered'],
	'UNANSWERED_TOPIC_IMG' => $images['icon_mini_unanswered'],
	'FOLDER_LINK_IMG' => $images['folder_linked'],
	'FOLDER_MOVED_IMG' => $images['folder_moved'],

	'L_TOPICS' => $lang['Topics'],
	'L_REPLIES' => $lang['Replies'],
	'L_VIEWS' => $lang['Views'],
	'L_POSTS' => $lang['Posts'],
	'L_LASTPOST' => $lang['Last_Post'], 
	'L_MODERATOR' => $l_moderators, 
	'L_MARK_TOPICS_READ' => $lang['Mark_all_topics'], 
	'L_POST_NEW_TOPIC' => $forum_locked['l_post_new_topic'], 
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
	'L_LINK' => $lang['Link'], 
	'L_MOVED' => $lang['Moved'],
	'L_POSTED' => $lang['Posted'],
	'L_JOINED' => $lang['Joined'],
	'L_AUTHOR' => $lang['Author'],

	'U_VIEW_FORUM' => append_sid("index_all.$phpEx"))
);
//
// End header
//

//
// Okay, lets dump out the page ...
//
if( $total_topics )
{
	include($phpbb_root_path . 'includes/functions_rating.'.$phpEx);
	$rating_config = get_rating_config('1');
	if ( $rating_config[1] == 1 )
	{
		get_rating_ranks();
	}
	
	for($i = 0; $i < $total_topics; $i++)
	{
		$topic_rating = ( sizeof($topic_rank_set) > 0 && $topic_rowset[$i]['rating_rank_id'] > 0 ) ?  $topic_rank_set[$topic_rowset[$i]['rating_rank_id']] : '';

		$topic_id = $topic_rowset[$i]['topic_id'];
		$forum_id = $topic_rowset[$i]['forum_id'];

		$topic_title = ( sizeof($orig_word) ) ? preg_replace($orig_word, $replacement_word, $topic_rowset[$i]['topic_title']) : $topic_rowset[$i]['topic_title'];

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
			
		//
		// Mouse hover topic preview
		//
		$topic_content = $topic_rowset[$i]['post_text'];
		$bbcode_uid = $topic_rowset[$i]['bbcode_uid'];
		$topic_content = bbencode_strip($topic_content, $bbcode_uid);

		if (strlen($topic_content) > 200) 
		{ 
			$topic_content = substr($topic_content, 0, 200) . "..."; 
		}
		else 
		{ 
			$topic_content = $topic_content; 
		}

		$replies = $topic_rowset[$i]['topic_replies'];
	
		$topic_type = $topic_rowset[$i]['topic_type'];

		if ( $topic_rowset[$i]['topic_icon'] == 0 ) 
		{ 
			$icon = ''; 
		} 
		else 
		{ 	
			$icon = '<img width="19" height="19" src="' . $images['msg_icons'] . $topic_rowset[$i]['topic_icon'] . '.gif" alt="' . $lang['Topic_icon'] . '" title="' . $lang['Topic_icon'] . '" />'; 
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
	
			$folder_image =  $images['folder'];
			$folder_alt = $lang['Topics_Moved'];
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
				$goto_page .= '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=" . $topic_id . "&amp;start=$j") . '">' . $times . '</a>';
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
		$topic_author .= ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? username_level_color($topic_rowset[$i]['username'], $topic_rowset[$i]['user_level'], $topic_rowset[$i]['user_id']) : ( ( $topic_rowset[$i]['post_username'] != '' ) ? $topic_rowset[$i]['post_username'] : $lang['Guest'] ); 
		$topic_author .= ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';
	
		$first_post_time = create_date($board_config['default_dateformat'], $topic_rowset[$i]['topic_time'], $board_config['board_timezone']);
		$last_post_time = create_date($board_config['default_dateformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone']);
	
		$last_post_author = ( $topic_rowset[$i]['id2'] == ANONYMOUS ) ? ( ($topic_rowset[$i]['post_username2'] != '' ) ? $topic_rowset[$i]['post_username2'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $topic_rowset[$i]['id2']) . '" class="gensmall">' . username_level_color($topic_rowset[$i]['user2'], $topic_rowset[$i]['user_level2'], $topic_rowset[$i]['id2']) . '</a>'; 
	
		$last_post_url = '<a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $topic_rowset[$i]['topic_last_post_id']) . '#' . $topic_rowset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
	
		$views = ( $board_config['enable_topic_view_users'] && $userdata['session_logged_in'] ) ? '<a href="javascript:who_viewed(' . $topic_id . ')">' . $topic_rowset[$i]['topic_views'] . '</a>' : $topic_rowset[$i]['topic_views'];	
		$replies = '<a href="javascript:who(' . $topic_id . ')">' . $replies . '</a>';
	
		$template->assign_block_vars('topicrow', array(
			'ICON' => $icon, 
			'FORUM_ID' => $forum_id,
			'TOPIC_ID' => $topic_id,
			'TOPIC_FOLDER_IMG' => $folder_image,
			'TOPIC_AUTHOR' => $topic_author,
			'GOTO_PAGE' => $goto_page,
			'REPLIES' => $replies,
			'NEWEST_POST_IMG' => $newest_post_img,
			'TOPIC_ATTACHMENT_IMG' => topic_attachment_image($topic_rowset[$i]['topic_attachment']),
			'TOPIC_TITLE' => $topic_title,
			'TOPIC_CONTENT' => $topic_content,
			'TOPIC_TYPE' => $topic_type,
			'VIEWS' => $views,
			'FIRST_POST_TIME' => $first_post_time,
			'LAST_POST_TIME' => $last_post_time,
			'LAST_POST_AUTHOR' => $last_post_author,
			'LAST_POST_IMG' => $last_post_url,
			'RATING' => $topic_rating,
			'L_TOPIC_FOLDER_ALT' => $folder_alt,
	
			'U_VIEW_TOPIC' => $view_topic_url)
		);
	}
	
	$template->assign_vars(array(
		'PAGINATION' => generate_pagination("index_all.$phpEx?topicdays=$topic_days", $topics_count, $board_config['topics_per_page'], $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $topics_count / $board_config['topics_per_page'] )),
	
		'L_GOTO_PAGE' => $lang['Goto_page'])
	);
}
else
{
	//
	// No topics
	//
	$no_topics_msg = $lang['No_topics_post_one'];
	$template->assign_vars(array(
		'L_NO_TOPICS' => $no_topics_msg)
	);

	$template->assign_block_vars('switch_no_topics', array());
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
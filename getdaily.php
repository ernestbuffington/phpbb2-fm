<?php
/** 
*
* @package phpBB
* @version $Id: getdaily.php,v 1.72.2.6 2003/01/09 00:17:23 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_search.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_SEARCH);
init_userprefs($userdata);
//
// End session management
//

$mode = 'results';
$search_id = '';
$show_results = 'topics';
$sort_by = 0;
$sort_dir = 'DESC';

if ( !empty($HTTP_POST_VARS['hours']) || !empty($HTTP_GET_VARS['hours']))
{
	$hours = ( !empty($HTTP_POST_VARS['hours']) ) ? intval($HTTP_POST_VARS['hours']) : intval($HTTP_GET_VARS['hours']);
}
else
{
	$hours = 24;
}

$search_time = time() - (3600 * $hours);
$start = 0;
$sort_by_types = array($lang['Sort_Time'], $lang['Sort_Post_Subject'], $lang['Sort_Topic_Title'], $lang['Sort_Author'], $lang['Sort_Forum']);

//
// encoding match for workaround
//
$multibyte_charset = 'utf-8, big5, shift_jis, euc-kr, gb2312';

//
// Begin core code
//
$store_vars = array('search_results', 'total_match_count', 'split_search', 'sort_by', 'sort_dir', 'show_results', 'return_chars');


//
// If user is logged in then we'll check to see which (if any) private
// forums they are allowed to view and include them in the search.
//
// If not logged in we explicitly prevent searching of private forums
//
$auth_sql = '';

$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata); 


$ignore_forum_sql = '';
while( list($key, $value) = each($is_auth_ary) )
{
	if ( !$value['auth_read'] )
	{
		$ignore_forum_sql .= ( ( $ignore_forum_sql != '' ) ? ', ' : '' ) . $key;
	}
}

if ( $ignore_forum_sql != '' )
{
	$auth_sql .= ( $auth_sql != '' ) ? " AND f.forum_id NOT IN ($ignore_forum_sql) " : "f.forum_id NOT IN ($ignore_forum_sql) ";
}



$where_sql = "p.post_time >= $search_time";

$from_sql = POSTS_TABLE . " p, " . USERS_TABLE . " u";

if ( $auth_sql != '' )
{
	$from_sql .= ", " . FORUMS_TABLE . " f";
	$where_sql .= " AND f.forum_id = p.forum_id AND $auth_sql";
}

$sql = "SELECT p.topic_id 
	FROM $from_sql 
	WHERE $where_sql 
	GROUP BY p.topic_id";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain topic ids', '', __LINE__, __FILE__, $sql);
}

$search_ids = array();
while( $row = $db->sql_fetchrow($result) )
{
	$search_ids[] = $row['topic_id'];
}
$db->sql_freeresult($result);

$total_match_count = sizeof($search_ids);

//
// Delete old data from the search result table
//
$sql = 'DELETE FROM ' . SEARCH_TABLE . '
	WHERE search_time < ' . ($current_time - (int) $board_config['session_length']);
if ( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not delete old search id sessions', '', __LINE__, __FILE__, $sql);
}


//
// Store new result data
//
$search_results = implode(', ', $search_ids);
$per_page = ( $show_results == 'posts' ) ? $board_config['posts_per_page'] : $board_config['topics_per_page'];

//
// Combine both results and search data (apart from original query)
// so we can serialize it and place it in the DB
//
$store_search_data = array();
for($i = 0; $i < sizeof($store_vars); $i++)
{
	$store_search_data[$store_vars[$i]] = $$store_vars[$i];
}

$result_array = serialize($store_search_data);
unset($store_search_data);

mt_srand ((double) microtime() * 1000000);
$search_id = mt_rand();

$sql = "UPDATE " . SEARCH_TABLE . " 
	SET search_id = $search_id, search_array = '$result_array'
	WHERE session_id = '" . $userdata['session_id'] . "'";
if ( !($result = $db->sql_query($sql)) || !$db->sql_affectedrows() )
{
	$sql = "INSERT INTO " . SEARCH_TABLE . " (search_id, session_id, search_array) 
		VALUES($search_id, '" . $userdata['session_id'] . "', '" . str_replace("\'", "''", $result_array) . "')";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not insert search results', '', __LINE__, __FILE__, $sql);
	}
}

//
// Look up data ...
//
if ( $search_results != '' )
{
	if ( $show_results == 'posts' )
	{
		$sql = "SELECT pt.post_text, pt.bbcode_uid, pt.post_subject, p.*, f.forum_id, f.forum_name, t.*, u.username, u.user_id, u.user_level, u.user_sig, u.user_sig_bbcode_uid  
			FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TEXT_TABLE . " pt 
			WHERE p.post_id IN ($search_results)
				AND pt.post_id = p.post_id
				AND f.forum_id = p.forum_id
				AND p.topic_id = t.topic_id
				AND p.poster_id = u.user_id";
	}
	else
	{
		$sql = "SELECT t.*, f.forum_id, f.forum_name, u.username, u.user_id, u.user_level, u2.username as user2, u2.user_id as id2, u2.user_level as user_level2, p.post_username, p2.post_username AS post_username2, p2.post_time 
			FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2, " . USERS_TABLE . " u2
			WHERE t.topic_id IN ($search_results) 
				AND t.topic_poster = u.user_id
				AND f.forum_id = t.forum_id 
				AND p.post_id = t.topic_first_post_id
				AND p2.post_id = t.topic_last_post_id
				AND u2.user_id = p2.poster_id";
	}

	$per_page = ( $show_results == 'posts' ) ? $board_config['posts_per_page'] : $board_config['topics_per_page'];

	$sql .= " ORDER BY ";
	switch ( $sort_by )
	{
		case 1:
			$sql .= ( $show_results == 'posts' ) ? 'pt.post_subject' : 't.topic_title';
			break;
		case 2:
			$sql .= 't.topic_title';
			break;
		case 3:
			$sql .= 'u.username';
			break;
		case 4:
			$sql .= 'f.forum_id';
			break;
		default:
			$sql .= ( $show_results == 'posts' ) ? 'p.post_time' : 'p2.post_time';
			break;
	}
	$sql .= " $sort_dir LIMIT $start, " . $per_page;

	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain search results', '', __LINE__, __FILE__, $sql);
	}

	$searchset = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$searchset[] = $row;
	}
	
	$db->sql_freeresult($result);		
	
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
	// Output header
	//
	$page_title = $lang['View_last_24_hours'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);	

	if ( $show_results == 'posts' )
	{
		$template->set_filenames(array(
			'body' => 'search_results_posts.tpl')
		);
	}
	else
	{
		$template->set_filenames(array(
			'body' => 'search_results_topics.tpl')
		);
	}
	make_jumpbox('viewforum.'.$phpEx);

	$l_search_matches = ( $total_match_count == 1 ) ? sprintf($lang['Found_search_match'], $total_match_count) : sprintf($lang['Found_search_matches'], $total_match_count);

	$template->assign_vars(array(
		'L_SEARCH_MATCHES' => $l_search_matches, 
		'L_TOPIC' => $lang['Topic'])
	);

	$highlight_active = '';
	$highlight_match = array();
	for($j = 0; $j < sizeof($split_search); $j++ )
	{
		$split_word = $split_search[$j];

		if ( $split_word != 'and' && $split_word != 'or' && $split_word != 'not' )
		{
			$highlight_match[] = '#\b(' . str_replace("*", "([\w]+)?", $split_word) . ')\b#is';
			$highlight_active .= " " . $split_word;

			for ($k = 0; $k < sizeof($synonym_array); $k++)
			{ 
				list($replace_synonym, $match_synonym) = split(' ', trim(strtolower($synonym_array[$k]))); 

				if ( $replace_synonym == $split_word )
				{
					$highlight_match[] = '#\b(' . str_replace("*", "([\w]+)?", $replace_synonym) . ')\b#is';
					$highlight_active .= ' ' . $match_synonym;
				}
			} 
		}
	}

	$highlight_active = urlencode(trim($highlight_active));

	$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : array();
	$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) : array();

	for($i = 0; $i < sizeof($searchset); $i++)
	{
		$forum_url = append_sid("viewforum.$phpEx?" . POST_FORUM_URL . '=' . $searchset[$i]['forum_id']);
		$topic_url = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . '=' . $searchset[$i]['topic_id'] . "&amp;highlight=$highlight_active");
		$post_url = append_sid("viewtopic.$phpEx?" . POST_POST_URL . '=' . $searchset[$i]['post_id'] . "&amp;highlight=$highlight_active") . '#' . $searchset[$i]['post_id'];

		$post_date = create_date($board_config['default_dateformat'], $searchset[$i]['post_time'], $board_config['board_timezone']);

		$message = $searchset[$i]['post_text'];
		$topic_title = $searchset[$i]['topic_title'];

		$forum_id = $searchset[$i]['forum_id'];
		$topic_id = $searchset[$i]['topic_id'];

		if ( $show_results == 'posts' )
		{
			if ( isset($return_chars) )
			{
				$bbcode_uid = $searchset[$i]['bbcode_uid'];

				//
				// If the board has HTML off but the post has HTML
				// on then we process it, else leave it alone
				//
				if ( $return_chars != -1 )
				{
					$message = strip_tags($message);
					$message = preg_replace("/\[.*?:$bbcode_uid:?.*?\]/si", '', $message);
					$message = preg_replace('/\[url\]|\[\/url\]/si', '', $message);
					$message = ( strlen($message) > $return_chars ) ? substr($message, 0, $return_chars) . ' ...' : $message;

					if ( sizeof($search_string) )
					{
						$message = preg_replace($search_string, $replace_string, $message);
					}
				}
				else
				{
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

					$message = make_clickable($message);

					if ( $highlight_active )
					{
						if ( preg_match('/<.*>/', $message) )
						{
							$message = preg_replace($highlight_match, '<!-- #sh -->\1<!-- #eh -->', $message);

							$end_html = 0;
							$start_html = 1;
							$temp_message = '';
							$message = ' ' . $message . ' ';

							while( $start_html = strpos($message, '<', $start_html) )
							{
								$grab_length = $start_html - $end_html - 1;
								$temp_message .= substr($message, $end_html + 1, $grab_length);

								if ( $end_html = strpos($message, '>', $start_html) )
								{
									$length = $end_html - $start_html + 1;
									$hold_string = substr($message, $start_html, $length);

									if ( strrpos(' ' . $hold_string, '<') != 1 )
									{
										$end_html = $start_html + 1;
										$end_counter = 1;

										while ( $end_counter && $end_html < strlen($message) )
										{
											if ( substr($message, $end_html, 1) == '>' )
											{
												$end_counter--;
											}
											else if ( substr($message, $end_html, 1) == '<' )
											{
												$end_counter++;
											}

											$end_html++;
										}

										$length = $end_html - $start_html + 1;
										$hold_string = substr($message, $start_html, $length);
										$hold_string = str_replace('<!-- #sh -->', '', $hold_string);
										$hold_string = str_replace('<!-- #eh -->', '', $hold_string);
									}
									else if ( $hold_string == '<!-- #sh -->' )
									{
										$hold_string = str_replace('<!-- #sh -->', '<span style="color:#' . $theme['fontcolor4'] . '"><b>', $hold_string);
									}
									else if ( $hold_string == '<!-- #eh -->' )
									{
										$hold_string = str_replace('<!-- #eh -->', '</b></span>', $hold_string);
									}

									$temp_message .= $hold_string;

									$start_html += $length;
								}
								else
								{
									$start_html = strlen($message);
								}
							}

							$grab_length = strlen($message) - $end_html - 1;
							$temp_message .= substr($message, $end_html + 1, $grab_length);

							$message = trim($temp_message);
						}
						else
						{
							$message = preg_replace($highlight_match, '<span style="color:#' . $theme['fontcolor4'] . '"><b>\1</b></span>', $message);
						}
					}
				}

				if( !empty($orig_word) )
				{
					$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
					$post_subject = ( $searchset[$i]['post_subject'] != "" ) ? preg_replace($orig_word, $replacement_word, $searchset[$i]['post_subject']) : $topic_title;

					$message = preg_replace($orig_word, $replacement_word, $message);
				}
				else
				{
					$post_subject = ( $searchset[$i]['post_subject'] != '' ) ? $searchset[$i]['post_subject'] : $topic_title;
				}

				if ($board_config['allow_smilies'] && $searchset[$i]['enable_smilies'])
				{
					$message = smilies_pass($message);
				}

				$message = str_replace("\n", '<br />', $message);

			}

			$poster = ( $searchset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $searchset[$i]['user_id']) . '" class="genmed">' : '';
			$poster .= ( $searchset[$i]['user_id'] != ANONYMOUS ) ? username_level_color($searchset[$i]['username'], $searchset[$i]['user_level'], $searchset[$i]['user_id']) : ( ( $searchset[$i]['post_username'] != '' ) ? $searchset[$i]['post_username'] : $lang['Guest'] );
			$poster .= ( $searchset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';

			if ( $userdata['session_logged_in'] && $searchset[$i]['post_time'] > $userdata['user_lastvisit'] )
			{
				if ( !empty($tracking_topics[$topic_id]) && !empty($tracking_forums[$forum_id]) )
				{
					$topic_last_read = ( $tracking_topics[$topic_id] > $tracking_forums[$forum_id] ) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
				}
				else if ( !empty($tracking_topics[$topic_id]) || !empty($tracking_forums[$forum_id]) )
				{
					$topic_last_read = ( !empty($tracking_topics[$topic_id]) ) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
				}

				if ( $searchset[$i]['post_time'] > $topic_last_read )
				{
					$mini_post_img = $images['icon_minipost_new'];
					$mini_post_alt = $lang['New_post'];
				}
				else
				{
					$mini_post_img = $images['icon_minipost'];
					$mini_post_alt = $lang['Post'];
				}
			}
			else
			{
				$mini_post_img = $images['icon_minipost'];
				$mini_post_alt = $lang['Post'];
			}

			$template->assign_block_vars("searchresults", array( 
				'TOPIC_TITLE' => $topic_title,
				'FORUM_NAME' => $searchset[$i]['forum_name'],
				'POST_SUBJECT' => $post_subject,
				'POST_DATE' => $post_date,
				'POSTER_NAME' => $poster,
				'TOPIC_REPLIES' => $searchset[$i]['topic_replies'],
				'TOPIC_VIEWS' => $searchset[$i]['topic_views'],
				'MESSAGE' => $message,
				'MINI_POST_IMG' => $mini_post_img, 

				'L_MINI_POST_ALT' => $mini_post_alt, 

				'U_POST' => $post_url,
				'U_TOPIC' => $topic_url,
				'U_FORUM' => $forum_url)
			);
		}
		else
		{
			$message = '';

			if( !empty($orig_word) )
			{
				$topic_title = preg_replace($orig_word, $replacement_word, $searchset[$i]['topic_title']);
			}

			$topic_type = $searchset[$i]['topic_type'];

			if ($topic_type == POST_GLOBAL_ANNOUNCE)
			{
				$topic_type = $lang['Topic_global_announcement'] . ' ';
			}
			if ($topic_type == POST_ANNOUNCE)
			{
				$topic_type = $lang['Topic_Announcement'] . ' ';
			}
			else if ($topic_type == POST_STICKY)
			{
				$topic_type = $lang['Topic_Sticky'] . ' ';
			}
			else
			{
				$topic_type = '';
			}

			if ( $searchset[$i]['topic_vote'] )
			{
				$topic_type .= $lang['Topic_Poll'] . ' ';
			}

			$views = ( $userdata['session_logged_in'] ) ? '<a href="javascript:who_viewed(' . $topic_id . ')">' . $searchset[$i]['topic_views'] . '</a>' : $searchset[$i]['topic_views'];
			$replies = $searchset[$i]['topic_replies'];

			if ( ( $replies + 1 ) > $board_config['posts_per_page'] )
			{
				$total_pages = ceil( ( $replies + 1 ) / $board_config['posts_per_page'] );
				$goto_page = ' [ <img src="' . $images['icon_gotopost'] . '" alt="' . $lang['Goto_page'] . '" title="' . $lang['Goto_page'] . '" />' . $lang['Goto_page'] . ': ';

				$times = 1;
				for($j = 0; $j < $replies + 1; $j += $board_config['posts_per_page'])
				{
					$goto_page .= '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=" . $topic_id . "&amp;start=$j") . '">' . $times . '</a>';
					if ( $times == 1 && $total_pages > 4 )
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

			if ( $searchset[$i]['topic_status'] == TOPIC_MOVED )
			{
				$topic_type = $lang['Topic_Moved'] . ' ';
				$topic_id = $searchset[$i]['topic_moved_id'];

				$folder_image = '<img src="' . $images['folder'] . '" alt="' . $lang['No_new_posts'] . '" />';
				$newest_post_img = '';
			}
			else
			{
				if ( $searchset[$i]['topic_status'] == TOPIC_LOCKED )
				{
					$folder = $images['folder_locked'];
					$folder_new = $images['folder_locked_new'];
				}
				else if ( $searchset[$i]['topic_type'] == POST_GLOBAL_ANNOUNCE )
				{
					$folder = $images['folder_global_announce'];
					$folder_new = $images['folder_global_announce_new'];
				}
				else if ( $searchset[$i]['topic_type'] == POST_ANNOUNCE )
				{
					$folder = $images['folder_announce'];
					$folder_new = $images['folder_announce_new'];
				}
				else if ( $searchset[$i]['topic_type'] == POST_STICKY )
				{
					$folder = $images['folder_sticky'];
					$folder_new = $images['folder_sticky_new'];
				}
				else
				{
					if ( $replies >= $board_config['hot_threshold'] )
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

				if ( $userdata['session_logged_in'] )
				{
					if ( $searchset[$i]['post_time'] > $userdata['user_lastvisit'] ) 
					{
						if ( !empty($tracking_topics) || !empty($tracking_forums) || isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
						{

							$unread_topics = true;

							if ( !empty($tracking_topics[$topic_id]) )
							{
								if ( $tracking_topics[$topic_id] > $searchset[$i]['post_time'] )
								{
									$unread_topics = false;
								}
							}

							if ( !empty($tracking_forums[$forum_id]) )
							{
								if ( $tracking_forums[$forum_id] > $searchset[$i]['post_time'] )
								{
									$unread_topics = false;
								}
							}

							if ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
							{
								if ( $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all'] > $searchset[$i]['post_time'] )
								{
									$unread_topics = false;
								}
							}

							if ( $unread_topics )
							{
								$folder_image = $folder_new;
								$folder_alt = $lang['New_posts'];

								$newest_post_img = '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;view=newest") . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ';
							}
							else
							{
								$folder_alt = ( $searchset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];

								$folder_image = $folder;
								$folder_alt = $folder_alt;
								$newest_post_img = '';
							}

						}
						else if ( $searchset[$i]['post_time'] > $userdata['user_lastvisit'] ) 
						{
							$folder_image = $folder_new;
							$folder_alt = $lang['New_posts'];

							$newest_post_img = '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;view=newest") . '"><img src="' . $images['icon_newest_reply'] . '" alt="' . $lang['View_newest_post'] . '" title="' . $lang['View_newest_post'] . '" /></a> ';
						}
						else 
						{
							$folder_image = $folder;
							$folder_alt = ( $searchset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];
							$newest_post_img = '';
						}
					}
					else
					{
						$folder_image = $folder;
						$folder_alt = ( $searchset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];
						$newest_post_img = '';
					}
				}
				else
				{
					$folder_image = $folder;
					$folder_alt = ( $searchset[$i]['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['No_new_posts'];
					$newest_post_img = '';
				}
			}


			$topic_author = ( $searchset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $searchset[$i]['user_id']) . '" class="genmed">' : '';
			$topic_author .= ( $searchset[$i]['user_id'] != ANONYMOUS ) ? username_level_color($searchset[$i]['username'], $searchset[$i]['user_level'], $searchset[$i]['user_id']) : ( ( $searchset[$i]['post_username'] != '' ) ? $searchset[$i]['post_username'] : $lang['Guest'] );

			$topic_author .= ( $searchset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';

			$first_post_time = create_date($board_config['default_dateformat'], $searchset[$i]['topic_time'], $board_config['board_timezone']);

			$last_post_time = create_date($board_config['default_dateformat'], $searchset[$i]['post_time'], $board_config['board_timezone']);

			$last_post_author = ( $searchset[$i]['id2'] == ANONYMOUS ) ? ( ($searchset[$i]['post_username2'] != '' ) ? $searchset[$i]['post_username2'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $searchset[$i]['id2']) . '" class="postdetails">' . username_level_color($searchset[$i]['user2'], $searchset[$i]['user_level2'], $searchset[$i]['id2']) . '</a>';

			$last_post_url = '<a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $searchset[$i]['topic_last_post_id']) . '#' . $searchset[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

        	$topic_title = capitalization($topic_title);
		
			if ($board_config['enable_quick_titles'])
			{
				if ( $searchset[$i]['title_pos'] )
				{
					$topic_title = (empty($searchset[$i]['title_compl_infos'])) ? $topic_title : $topic_title . ' <span style="color: #' . $searchset[$i]['title_compl_color'] . '">' . $searchset[$i]['title_compl_infos'] . '</span>';
				}
				else
				{
					$topic_title = (empty($searchset[$i]['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $searchset[$i]['title_compl_color'] . '">' . $searchset[$i]['title_compl_infos'] . '</span> ' . $topic_title;
				}
			}
			
			$template->assign_block_vars('searchresults', array( 
				'FORUM_NAME' => $searchset[$i]['forum_name'],
				'FORUM_ID' => $forum_id,
				'TOPIC_ID' => $topic_id,
				'FOLDER' => $folder_image,
				'NEWEST_POST_IMG' => $newest_post_img, 
				'TOPIC_FOLDER_IMG' => $folder_image, 
				'GOTO_PAGE' => $goto_page,
				'REPLIES' => '<a href="javascript:who(' . $topic_id . ')">' . $replies . '</a>',
				'TOPIC_TITLE' => $topic_title,
				'TOPIC_TYPE' => $topic_type,
				'VIEWS' => $views,
				'TOPIC_AUTHOR' => $topic_author, 
				'FIRST_POST_TIME' => $first_post_time, 
				'LAST_POST_TIME' => $last_post_time,
				'LAST_POST_AUTHOR' => $last_post_author,
				'LAST_POST_IMG' => $last_post_url,

				'L_TOPIC_FOLDER_ALT' => $folder_alt, 

				'U_VIEW_FORUM' => $forum_url, 
				'U_VIEW_TOPIC' => $topic_url)
			);
		}
	}

	$base_url = "search.$phpEx?search_id=$search_id";

	$template->assign_vars(array(
		'PAGINATION' => generate_pagination($base_url, $total_match_count, $per_page, $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $per_page ) + 1 ), ceil( $total_match_count / $per_page )), 

		'L_AUTHOR' => $lang['Author'],
		'L_MESSAGE' => $lang['Message'],
		'L_FORUM' => $lang['Forum'],
		'L_TOPICS' => $lang['Topics'],
		'L_REPLIES' => $lang['Replies'],
		'L_VIEWS' => $lang['Views'],
		'L_POSTS' => $lang['Posts'],
		'L_LASTPOST' => $lang['Last_Post'], 
		'L_POSTED' => $lang['Posted'], 
		'L_SUBJECT' => $lang['Subject'],

		'L_GOTO_PAGE' => $lang['Goto_page'])
	);

	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else
{
	message_die(GENERAL_MESSAGE, $lang['No_search_match']);
}


//
// Search forum
//
$sql = "SELECT c.cat_title, c.cat_id, f.forum_name, f.forum_id  
	FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f
	WHERE f.cat_id = c.cat_id 
	ORDER BY c.cat_id, f.forum_order";
$result = $db->sql_query($sql);
if ( !$result )
{
	message_die(GENERAL_ERROR, 'Could not obtain forum_name/forum_id', '', __LINE__, __FILE__, $sql);
}

$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);

$s_forums = '';
while( $row = $db->sql_fetchrow($result) )
{
	if ( $is_auth_ary[$row['forum_id']]['auth_read'] )
	{
		$s_forums .= '<option value="' . $row['forum_id'] . '">' . $row['forum_name'] . '</option>';
		if ( empty($list_cat[$row['cat_id']]) )
		{
			$list_cat[$row['cat_id']] = $row['cat_title'];
		}
	}
}

if ( $s_forums != '' )
{
	$s_forums = '<option value="-1">' . $lang['All_available'] . '</option>' . $s_forums;

	//
	// Category to search
	//
	$s_categories = '<option value="-1">' . $lang['All_available'] . '</option>';
	while( list($cat_id, $cat_title) = @each($list_cat))
	{
		$s_categories .= '<option value="' . $cat_id . '">' . $cat_title . '</option>';
	}
}
else
{
	message_die(GENERAL_MESSAGE, $lang['No_searchable_forums']);
}

//
// Number of chars returned
//
$s_characters = '<option value="-1">' . $lang['All_available'] . '</option>';
$s_characters .= '<option value="0">0</option>';
$s_characters .= '<option value="25">25</option>';
$s_characters .= '<option value="50">50</option>';

for($i = 100; $i < 1100 ; $i += 100)
{
	$selected = ( $i == 200 ) ? ' selected="selected"' : '';
	$s_characters .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
}

//
// Sorting
//
$s_sort_by = "";
for($i = 0; $i < sizeof($sort_by_types); $i++)
{
	$s_sort_by .= '<option value="' . $i . '">' . $sort_by_types[$i] . '</option>';
}

//
// Search time
//
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['All_Posts'], $lang['1_Day'], $lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year']);

$s_time = '';
for($i = 0; $i < sizeof($previous_days); $i++)
{
	$selected = ( $topic_days == $previous_days[$i] ) ? ' selected="selected"' : '';
	$s_time .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}

//
// Output the basic page
//
$page_title = $lang['View_last_24_hours'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'search_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_SEARCH_QUERY' => $lang['Search_query'], 
	'L_SEARCH_OPTIONS' => $lang['Search_options'], 
	'L_SEARCH_KEYWORDS' => $lang['Search_keywords'], 
	'L_SEARCH_KEYWORDS_EXPLAIN' => $lang['Search_keywords_explain'], 
	'L_SEARCH_AUTHOR' => $lang['Search_author'],
	'L_SEARCH_AUTHOR_EXPLAIN' => $lang['Search_author_explain'], 
	'L_SEARCH_ANY_TERMS' => $lang['Search_for_any'],
	'L_SEARCH_ALL_TERMS' => $lang['Search_for_all'], 
	'L_SEARCH_MESSAGE_ONLY' => $lang['Search_msg_only'], 
	'L_SEARCH_MESSAGE_TITLE' => $lang['Search_title_msg'], 
	'L_CATEGORY' => $lang['Category'], 
	'L_RETURN_FIRST' => $lang['Return_first'],
	'L_CHARACTERS' => $lang['characters_posts'], 
	'L_SORT_BY' => $lang['Sort_by'],
	'L_SORT_ASCENDING' => $lang['Sort_Ascending'],
	'L_SORT_DESCENDING' => $lang['Sort_Descending'],
	'L_SEARCH_PREVIOUS' => $lang['Search_previous'], 
	'L_DISPLAY_RESULTS' => $lang['Display_results'], 
	'L_FORUM' => $lang['Forum'],
	'L_TOPICS' => $lang['Topics'],
	'L_POSTS' => $lang['Posts'],

	'S_SEARCH_ACTION' => append_sid("search.$phpEx?mode=results"),
	'S_CHARACTER_OPTIONS' => $s_characters,
	'S_FORUM_OPTIONS' => $s_forums, 
	'S_CATEGORY_OPTIONS' => $s_categories, 
	'S_TIME_OPTIONS' => $s_time, 
	'S_SORT_OPTIONS' => $s_sort_by,
	'S_HIDDEN_FIELDS' => '')
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
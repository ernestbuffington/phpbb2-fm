<?php
/** 
*
* @package phpBB
* @version $Id: viewpost.php,v 1.0.4.0 2006/7/24 15:00:37 eviL3 Exp $
* @copyright (c) 2006 phpBBModders
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'lgf-reflog.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
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
// Start initial var setup
//
if ( isset($HTTP_GET_VARS[POST_POST_URL]))
{
	$post_id = intval($HTTP_GET_VARS[POST_POST_URL]);
}

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;
$download = ( isset($HTTP_GET_VARS['download']) ) ? intval($HTTP_GET_VARS['download']) : '';

if (!$post_id)
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

//
// This rather complex gaggle of code handles querying for topics but
// also allows for direct linking to a post (and the calculation of which
// page the post is on and the correct display of viewtopic)
//
$join_sql_table = (!$post_id) ? '' : ", " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2 ";
$join_sql = (!$post_id) ? "t.topic_id = $topic_id" : "p.post_id = $post_id AND t.topic_id = p.topic_id AND p2.topic_id = p.topic_id AND p2.post_id <= $post_id";
$count_sql = (!$post_id) ? '' : ", COUNT(p2.post_id) AS prev_posts";

$order_sql = (!$post_id) ? '' : "GROUP BY p.post_id, t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.image_ever_thumb, f.auth_ban, f.auth_voteban, f.auth_greencard, f.auth_bluecard, f.forum_password, f.stop_bumping ORDER BY p.post_id ASC";

$sql = "SELECT t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, t.title_compl_infos, t.title_compl_color, t.title_pos, f.forum_name, f.forum_status, f.forum_id, f.cat_id, f.forum_regdate_limit, f.forum_enter_limit, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.image_ever_thumb, f.auth_ban, f.auth_voteban, f.auth_greencard, f.auth_bluecard, f.forum_password, f.stop_bumping" . $count_sql . "
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

if (!$board_config['viewtopic_viewpost'])
{
	message_die(GENERAL_MESSAGE, $lang['Single_post_disabled']);
}

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
$topic_title = $forum_topic_data['topic_title'];
$topic_id = intval($forum_topic_data['topic_id']);
$topic_time = $forum_topic_data['topic_time'];

if ($post_id)
{
	$start = floor(($forum_topic_data['prev_posts'] - 1) / intval($board_config['posts_per_page'])) * intval($board_config['posts_per_page']);
}


//
// Go ahead and pull all data for this topic
//
$sql = "SELECT u.*, u.user_avatar as current_user_avatar, u.user_avatar_type as current_user_avatar_type, p.*,  pt.post_text, pt.post_subject, pt.bbcode_uid
	FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
	WHERE pt.post_id = p.post_id
		AND u.user_id = p.poster_id
      AND $post_id = p.post_id
   ORDER BY p.post_time $post_time_order";
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
if ( !empty($orig_word) )
{
	$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
}

$topic_title = capitalization($topic_title);

if ($board_config['enable_quick_titles'])
{
	if ( $forum_topic_data['title_pos'] )
	{
		$topic_title = (empty($forum_topic_data['title_compl_infos'])) ? $topic_title : $topic_title . ' <span style="color: #' . $forum_topic_data['title_compl_color'] . '">' . $forum_topic_data['title_compl_infos'] . '</span>';
	}
	else
	{
		$topic_title = (empty($forum_topic_data['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $forum_topic_data['title_compl_color'] . '">' . $forum_topic_data['title_compl_infos'] . '</span> ' . $topic_title;
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
$view_forum_url = append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id");

//
// Mozilla navigation bar
//
$nav_links['up'] = array(
	'url' => $view_forum_url,
	'title' => $forum_name
);


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
	'body' => 'viewpost_body.tpl')
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
$page_title = $lang['View_single_post'] .' - ' . $topic_title;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);


//
// If we've got a hightlight set pass it on to pagination,
// I get annoyed when I lose my highlight after the first page.
//
$pagination = ( $highlight != '' ) ? generate_pagination("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;highlight=$highlight", $total_replies, $board_config['posts_per_page'], $start) : generate_pagination("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order", $total_replies, $board_config['posts_per_page'], $start);

//
// Send vars to template
//
$template->assign_vars(array(
    'TOPIC_ID' => $topic_id,
    'TOPIC_TITLE' => $topic_title,

	'REPLY_IMG' => $reply_img,

	'L_VIEW_SINGLE' => $lang['View_single_post'],
	'L_TOPIC' => $lang['Topic'],
	'L_AUTHOR' => $lang['Author'],
	'L_MESSAGE' => $lang['Message'],
	'L_POSTED' => $lang['Posted'],
	'L_POST_SUBJECT' => $lang['Post_subject'],
	'L_POST_NEW_TOPIC' => $post_alt,
	'L_POST_REPLY_TOPIC' => $reply_alt,

	'S_TOPIC_LINK' => POST_TOPIC_URL,
	'S_AUTH_LIST' => $s_auth_can,
	'S_TOPIC_ADMIN' => $topic_mod,

	'U_VIEW_TOPIC' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start&amp;postdays=$post_days&amp;postorder=$post_order&amp;highlight=$highlight"),
	'U_POST_REPLY_TOPIC' => $reply_topic_url)
);

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
// Okay, let's do the loop, yeah come on baby let's do the loop
// and it goes like this ...
//
for($i = 0; $i < 1; $i++)
{
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

	$poster_xd = ( $postrow[$i]['user_id'] != ANONYMOUS ) ? get_user_xdata($postrow[$i]['user_id']) : array();

	$poster_avatar = '';
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

	//
	// Generate strings, set them to an empty string initially.
	//
	$poster_rank = $rank_image = $temp_url = '';
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
	}


	//
	// If user is an unregister user (guest), hide all the posters info
	//
	if ( $poster_id != ANONYMOUS && $userdata['session_logged_in'] )
	{
		$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$poster_id");
		$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>';

		if ( !empty($postrow[$i]['user_viewemail']) || $is_auth['auth_mod'] )
		{
			$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $poster_id) : 'mailto:' . $postrow[$i]['user_email'];
			$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
		}

		if ( !empty($postrow[$i]['user_icq']) )
		{
			$icq_status_img = '<a href="http://wwp.icq.com/' . $postrow[$i]['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $postrow[$i]['user_icq'] . '&img=5" width="18" height="18" /></a>';
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
	}
	else
	{
		$profile_img = $profile = $pm_img = $pm = $email_img = $email = $www_img = $www = $icq_status_img = $icq_img = $icq = $aim_img = $aim = $msn_img = $msn = $yim_img = $yim = '';
	}

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

		$temp_url = "modcp.$phpEx?mode=ip&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;" . POST_TOPIC_URL . "=" . $topic_id . "&amp;sid=" . $userdata['session_id'];
		$ip_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_ip'] . '" alt="' . $lang['View_IP'] . '" title="' . $lang['View_IP'] . '" /></a>';

		$ip = decode_ip($postrow[$i]['poster_ip']);
		$ip = '<b>IP:</b> <a href="http://network-tools.com/default.asp?prog=trace&amp;host=' . $ip . '" target="_blank" class="postdetails">' . $ip . '</a><br />';

		$temp_url = "posting.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;sid=" . $userdata['session_id'];
		$delpost_ajaxed = ( $board_config['AJAXed_post_delete'] && $board_config['AJAXed_status'] ) ? "onClick=\"pd('" . $postrow[$i]['post_id'] . "','" . $i . "'); return false;\" " : '';
		$delpost_img = '<a ' . $delpost_ajaxed . 'href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>';
		$delpost = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';
	}
	else if ( $is_auth['auth_mod'] )
	{
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
	// Note! The order used for parsing the message _is_ important, moving things around could break any
	// output
	//

	//
	// If the board has HTML off but the post has HTML
	// on then we process it, else leave it alone
	//
	if ( !$board_config['allow_html'] || !$userdata['user_allowhtml'])
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
	if ($user_sig != '' && $user_sig_bbcode_uid != '')
	{
		$user_sig = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($user_sig, $user_sig_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $user_sig);
	}
	if ($bbcode_uid != '')
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
					if ( $kicked_user ) 
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
	
				if ( !empty($orig_word) )
				{
					$row['topic_title'] = preg_replace($orig_word, $replacement_word, $row['topic_title']);
				}

				if ( $row['title_pos'] )
				{
					$topic_title = (empty($row['title_compl_infos'])) ? $row['topic_title'] : $row['topic_title'] . ' <span style="color: #' . $row['title_compl_color'] . '">' . $row['title_compl_infos'] . '</span>';
				}
				else
				{
					$topic_title = (empty($row['title_compl_infos'])) ? $row['topic_title'] : '<span style="color: #' . $row['title_compl_color'] . '">' . $row['title_compl_infos'] . '</span> ' . $row['topic_title'];
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
	// Again this will be handled by the templating
	// code at some point
	//
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('postrow', array(
		'ROW_CLASS' => $row_class,
		'ICON' => $icon, 
		'POSTER_NAME' => $poster,
		'POSTER_RANK' => $poster_rank,
		'RANK_IMAGE' => $rank_image,
		'POSTER_AVATAR' => $poster_avatar.$avatarvoteform,
		'AVATAR_VOTE' => $avatarvoteform,
		'POST_DATE' => $post_date,
		'POST_SUBJECT' => $post_subject,
		'MESSAGE' => $message,
		'SIGNATURE' => $user_sig,
		'EDITED_MESSAGE' => $l_edited_by,

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
		'CUSTOM_POST_COLOR' => $poster_custom_post_color,
		'THREAD_KICK_IMG' => $thread_kick_img[$i],
		'MY_INFO_IMG' => $myInfo_img,
		'DOWNLOAD_POST' => (!empty($board_config['viewtopic_downpost'])) ? ' &bull; <a href="' . append_sid('viewtopic.'.$phpEx.'?download=' . $postrow[$i]['post_id'] . '&amp;' . POST_TOPIC_URL . '=' . $topic_id) . '" class="postdetails">' . $lang['Download_post'] . '</a>' : '',

		'RAW_MESSAGE' => preg_replace('/\:(([a-z0-9]:)?)' . $postrow[$i]['bbcode_uid'] . '/s', '', $postrow[$i]['post_text']),
		'AJAXED_POST_MENU' => $ajaxed_post_menu,
		'AJAXED_EDIT_SUBJECT' => $ajaxed_edit_subject,
		'AJAXED_I' => $i,

		'L_MINI_POST_ALT' => $mini_post_alt,

		'U_MINI_POST' => $mini_post_url,
		'U_G_CARD' => $g_card_img, 
		'U_Y_CARD' => $y_card_img, 
		'U_BK_CARD' => $bk_card_img, 
		'U_R_CARD' => $r_card_img, 
		'U_B_CARD' => $b_card_img,
		'S_CARD' => append_sid('card.'.$phpEx),
		'U_POST_ID' => $postrow[$i]['post_id'])
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

	// Myinfo
	if ( $board_config['myInfo_enable'] ) 
   	{ 
    	$template->assign_block_vars('postrow.switch_myInfo_active', array()); 
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
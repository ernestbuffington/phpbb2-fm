<?php
/** 
*
* @package phpBB2
* @version $Id: index.php,v 1.99.2.3 2004/07/11 16:46:15 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
define('IN_INDEX', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'lgf-reflog.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//


//
// Activity 
//
$ipb_check = ( isset($HTTP_GET_VARS['act']) ) ? 'Arcade' : '';
$ipb_score = ( isset($HTTP_GET_VARS['do']) ) ? 'newscore' : '';
if ( ($ipb_check) && ($ipb_score) )
{
	$game = trim(addslashes(stripslashes($HTTP_POST_VARS['gname'])));
	$score = intval($HTTP_POST_VARS['gscore']);
	
	$q = "SELECT game_type
		FROM " . iNA_GAMES . "
		WHERE game_name = '" . $game . "'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	
	// Only IPB Games Can Use This Format!
	if ( $row['game_type'] == 3 )
	{
		echo '<form method="post" name="ipb" action="newscore.'.$phpEx.'">';
		echo '<input type="hidden" name="score" value="' . $score . '">';
		echo '<input type="hidden" name="game_name" value="' . $game . '">';
		echo '</form>';
		echo '<script type="text/javascript">';
		echo 'window.onload = function(){document.ipb.submit()}';
		echo '</script>';
		exit();
	}
	else
	{
		redirect(append_sid('activity.'.$phpEx), TRUE);
	}
}
	
$viewcat = ( !empty($HTTP_GET_VARS[POST_CAT_URL]) ) ? $HTTP_GET_VARS[POST_CAT_URL] : -1;
$hierarchie_level = ( !empty($HTTP_GET_VARS[POST_HIERARCHIE_URL]) ) ? $HTTP_GET_VARS[POST_HIERARCHIE_URL] : 0;
$parent_forum = ( !empty($HTTP_GET_VARS[POST_PARENTFORUM_URL]) ) ? $HTTP_GET_VARS[POST_PARENTFORUM_URL] : 0;

$viewcat = intval($viewcat);
$hierarchie_level = intval($hierarchie_level);
$parent_forum = intval($parent_forum);

$viewtag = ( isset($HTTP_GET_VARS['forum_tag']) ) ? intval($HTTP_GET_VARS['forum_tag']) : 0;
if ($viewtag && ($userdata['user_id'] != -1))
{
	$sql = "SELECT * 
		FROM " . FORUMS_DESC_TABLE . " 
		WHERE forum_id = " . $viewtag;
	if ($result = $db->sql_query($sql))		
	{
		while ( $row = $db->sql_fetchrow($result) )
		if ( $row['user_id'] == $userdata['user_id'] )
		{
			$userfound = 1;
			if ($row['view'] == 0)
			{
				$sql = "UPDATE LOW_PRIORITY " . FORUMS_DESC_TABLE . " 
					SET view = 1 
					WHERE forum_id = " . $viewtag . " 
						AND user_id = " . $userdata['user_id'];
				$result = $db->sql_query($sql);
			}
			else 
			{	
				$sql = "UPDATE LOW_PRIORITY " . FORUMS_DESC_TABLE . " 
					SET view = 0 
					WHERE forum_id = " . $viewtag . " 
						AND user_id = " . $userdata['user_id'];
				$result = $db->sql_query($sql);
			}
			break;
		}
		if ($userfound != 1)	
		{
			$sql = "INSERT INTO " . FORUMS_DESC_TABLE . " (forum_id, user_id, view) 
				VALUES ($viewtag, " . $userdata['user_id'] . ", 0)";
			$result = $db->sql_query($sql);		
		}
	}
	else
	{
		$sql = "INSERT INTO " . FORUMS_DESC_TABLE . " (forum_id, user_id, view) 
			VALUES ($viewtag, " . $userdata['user_id'] . ", 0)";
		$result = $db->sql_query($sql);
	}
}

if( isset($HTTP_GET_VARS['mark']) || isset($HTTP_POST_VARS['mark']) )
{
	$mark_read = ( isset($HTTP_POST_VARS['mark']) ) ? $HTTP_POST_VARS['mark'] : $HTTP_GET_VARS['mark'];
}
else
{
	$mark_read = '';
}

//
// Handle marking posts
//
if( $mark_read == 'forums' )
{
	if( $userdata['session_logged_in'] )
	{
		setcookie($board_config['cookie_name'] . '_f_all', time(), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
	}

	$template->assign_vars(array(
		"META" => '<meta http-equiv="refresh" content="3;url='  .append_sid("index.$phpEx") . '">')
	);

	$message = $lang['Forums_marked_read'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a> ');

	message_die(GENERAL_MESSAGE, $message);
}
//
// End handle marking posts
//

$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . "_t"]) : array();
$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . "_f"]) : array();


//
// Start page proper
//
$sql = "SELECT c.*
	FROM " . CATEGORIES_TABLE . " c 
    WHERE c.cat_hier_level = " . $hierarchie_level . "
    	AND c.parent_forum_id = " . $parent_forum . "
	ORDER BY c.cat_order";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
}

$category_rows = array();
while ($row = $db->sql_fetchrow($result))
{
	$category_rows[] = $row;
}
$db->sql_freeresult($result);

if( ( $total_categories = sizeof($category_rows) ) )
{
	$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id, u.user_level, t.topic_title, t.title_compl_infos, t.title_pos, t.title_compl_color
		FROM ((( " . FORUMS_TABLE . " f
			LEFT JOIN " . POSTS_TABLE . " p ON p.post_id = f.forum_last_post_id )
			LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.poster_id )
			LEFT JOIN " . TOPICS_TABLE . " t ON t.topic_id = p.topic_id )
		ORDER BY f.cat_id, f.forum_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
	}

	$forum_data = $topic_last_ary = array();
	$i = 0;
	while( $row = $db->sql_fetchrow($result) )
	{
		if (!in_array($row['topic_last_post_id'], $topic_last_ary) || $row['topic_last_post_id'] == 0) 
		{
			$topic_last_ary[i] = $row['topic_last_post_id'];
			$i++;
			$forum_data[] = $row;
		}
	}
	unset($topic_last_ary);
	$db->sql_freeresult($result);

	if ( !($total_forums = sizeof($forum_data)) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_forums']);
	}
	
	//
	// Filter topic_title not allowed to read
	// 
	if ( !($userdata['user_level'] == ADMIN && $userdata['session_logged_in']) ) 
    {
		$auth_read_all = array();
		$auth_read_all = auth(AUTH_READ, AUTH_LIST_ALL, $userdata, $forum_data);
		$auth_data = '';
		for($i = 0; $i < sizeof($forum_data); $i++)
		{
			if (!$auth_read_all[$forum_data[$i]['forum_id']]['auth_read']) 
			{
				$forum_data[$i]['title_compl_infos'] = $forum_data[$i]['topic_title'] = '';
			}
		}
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
	// Obtain a list of topic ids which contain
	// posts made since user last visited
	//
	if ( $userdata['session_logged_in'] )
	{
		// 60 days limit
		if ($userdata['user_lastvisit'] < (time() - 5184000))
		{
			$userdata['user_lastvisit'] = time() - 5184000;
		}

		$sql = "SELECT t.forum_id, t.topic_id, p.post_time 
			FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p 
			WHERE p.post_id = t.topic_last_post_id 
				AND p.post_time > " . $userdata['user_lastvisit'] . " 
				AND t.topic_moved_id = 0"; 
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query new topic information', '', __LINE__, __FILE__, $sql);
		}

		$new_topic_forum_ids = '';
		$new_topic_data = array();
		while( $topic_data = $db->sql_fetchrow($result) )
		{
			$new_topic_forum_ids .= ", " . $topic_data['forum_id'] . " ";
			$new_topic_data[$topic_data['forum_id']][$topic_data['topic_id']] = $topic_data['post_time'];
		}
		$db->sql_freeresult($result);

		//
		// Now we go and get all forums superior to the result above, but do it only if its necassary
		// 
		if (strlen($new_topic_forum_ids) > 0)
		{
			$new_topic_forum_ids = substr($new_topic_forum_ids, 1);
		
			$sql = "SELECT f.forum_id, cfp.parent_forum_id
				FROM " . FORUMS_TABLE . " f, " . CAT_REL_FORUM_PARENTS_TABLE . " cfp
				WHERE f.forum_id IN (" . $new_topic_forum_ids . ")
					AND f.cat_id = cfp.cat_id"; 
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query superior forums of new topic information', '', __LINE__, __FILE__, $sql);
			}

			while( $topic_data = $db->sql_fetchrow($result) )
			{
				$pfid = $topic_data['parent_forum_id'];
				$fid = $topic_data['forum_id'];
				foreach ($new_topic_data[$fid] as $key => $value) 
				{
					$new_topic_data[$pfid][$key] = $value;
				}
				reset($new_topic_data[$fid]);
			}
			$db->sql_freeresult($result);
		}
	}

	//
	// Obtain list of moderators of each forum
	// First users, then groups ... broken into two queries
	//
	$sql = "SELECT aa.forum_id, u.user_id, u.username, u.user_level 
		FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
		WHERE aa.auth_mod = " . TRUE . " 
			AND g.group_single_user = 1 
			AND ug.group_id = aa.group_id 
			AND g.group_id = aa.group_id 
			AND u.user_id = ug.user_id 
		GROUP BY u.user_id, u.username, aa.forum_id 
		ORDER BY aa.forum_id, u.user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
	}

	$forum_moderators = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$forum_moderators[$row['forum_id']][] = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" class="gensmall">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>';
	}
	$db->sql_freeresult($result);

	$sql = "SELECT aa.forum_id, g.group_id, g.group_name, g.group_colors
		FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g 
		WHERE aa.auth_mod = " . TRUE . " 
			AND g.group_single_user = 0 
			AND g.group_type <> " . GROUP_HIDDEN . "
			AND ug.group_id = aa.group_id 
			AND g.group_id = aa.group_id 
		GROUP BY g.group_id, g.group_name, aa.forum_id 
		ORDER BY aa.forum_id, g.group_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		
//		$row['group_name'] = (!empty($row['group_colors'])) ? '<b style="color: #' . $row['group_colors'] . '">' . $row['group_name'] . '</b>' : $row['group_name'];
		$forum_moderators[$row['forum_id']][] = '<a href="' . append_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=" . $row['group_id']) . '" class="gensmall">' . $row['group_name'] . '</a>';
	}
	$db->sql_freeresult($result);

	//
	// Who is active in which forum
	//
	if ($board_config['index_active_in_forum'])
	{
		$sql_active = "SELECT u.username, u.user_id, u.user_allow_viewonline, u.user_level, s.session_logged_in, s.session_ip, f.forum_id
			FROM " . USERS_TABLE . " u, " . SESSIONS_TABLE . " s, " . FORUMS_TABLE . " f
			WHERE u.user_id = s.session_user_id 
				AND s.session_time >= " . (time() - 60) . " 
				AND s.session_page = f.forum_id
			ORDER BY u.username ASC, s.session_ip ASC";
		if( !($result_active = $db->sql_query($sql_active)) )
		{
			message_die(GENERAL_ERROR, 'could not obtain user/online information.', '', __LINE__, __FILE__, $sql_active);
		}
	
		$userlist_ary = $userlist_visible = array();
		$prev_userid = 0;
		$prev_userip = $prev_sessionip = '';
		while( $active = $db->sql_fetchrow($result_active) )
		{
			if( $active['session_logged_in'] )
			{
				if( $active['user_id'] != $prev_userid )
				{
					$active['username'] = username_level_color($active['username'], $active['user_level'], $active['user_id']);
		
					if( $active['user_allow_viewonline'] )
					{
						$onlinerow[$active['forum_id']][] = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL ."=". $active['user_id']) .'" class="gensmall">' . $active['username'] . '</a>';
						$logged_visible_active[$active['forum_id']][]++;
					}
					else
					{
						$onlinerow[$active['forum_id']][] = ( $active['user_allow_viewonline'] || $userdata['user_level'] == ADMIN ) ? '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $active['user_id']) . '" class="gensmall"><i>'. $active['username'] .'</i></a>' : '';
						$logged_hidden_active[$active['forum_id']][]++;
					}
				}
				$prev_userid = $active['user_id'];
			}
			else
			{
				if( $active['session_ip'] != $prev_sessionip )
				{
					$guestrow[$active['forum_id']][] = '';
					$guests_active[$active['forum_id']][]++;
				}
			}
			$prev_sessionip = $active['session_ip'];
		}
		$db->sql_freeresult($result_active);
	}


	//
	// Find which forums are visible for this user
	//
	$is_auth_ary = array();
	$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $forum_data);


	//
	// Find which forums are visible for this user per items
	//
	$itemarray = explode('ß', str_replace('Þ', '', $userdata['user_items']));

	$sql = "SELECT name, accessforum 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE accessforum != 0 
		AND accessforum > 0";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not query shop forum access information', '', __LINE__, __FILE__, $sql);
	}
	$num_rows = mysql_num_rows($result);

	$itemformaccess = $itemcataccess = array();
	for ($x = 0; $x < $num_rows; $x++)
	{
		$shoprow = mysql_fetch_array($result);
		if (in_array($shoprow['name'], $itemarray))
		{
			$itemformaccess[] = $shoprow['accessforum'];

			$sql2 = "SELECT cat_id 
				FROM " . FORUMS_TABLE . " 
				WHERE forum_id = '" . $shoprow['accessforum'] . "'";
			if ( !($result = $db->sql_query($sql2)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not obtain item category access information', '', __LINE__, __FILE__, $sql2);
			}
			$shoprow2 = mysql_fetch_array($result);

			$itemcataccess[] = $shoprow2['cat_id'];
		}
	}
	
			
	//
	// Get the hierarchie if necessary
	//
	if($hierarchie_level > 0 && $total_categories > 0)
	{	
		$cat_id = $category_rows[0]['cat_id'];

		$sql = "SELECT CONCAT(c.cat_title, ', ', f.forum_name) AS hierarchie_title, f.forum_id, f.forum_hier_level + 1 AS hierarchie_level
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
		
		$sub_forum = FALSE;
		while( $row = $db->sql_fetchrow($result) )
		{
			$sub_forum = TRUE;
			$template->assign_block_vars('navrow', array(
				'U_SUBINDEX' => append_sid("index.$phpEx?" . POST_HIERARCHIE_URL . "=" . $row['hierarchie_level'] . "&amp;" . POST_PARENTFORUM_URL . "=" . $row['forum_id']),
				'L_SUBINDEX' => $row['hierarchie_title'])
			);
		}
		$db->sql_freeresult($result);
	}
	

	//
	// Start output of page
	//
	define('SHOW_ONLINE', true);
	if(!$sub_forum)
	{	
		//
		// Bookmakers
		//
		if ( $userdata['user_level'] == ADMIN ) 
		{
			if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_bookmakers.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_bookmakers.' . $phpEx); 

			$total_bets = $count_assigned = 0;
			$sql = "SELECT * 
				FROM " . BOOKIE_ADMIN_BETS_TABLE . " 
				WHERE bet_time < " . time() . "
					AND checked = 0";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Error getting bookmaker bets.', '', __LINE__, __FILE__, $sql); 
			}
		
			while ( $row = $db->sql_fetchrow($result) ) 
			{ 
				$total_bets = $total_bets + 1;
			}
			$db->sql_freeresult($result);
		
			if ( $total_bets > 0 )
			{
				$bookie_image = '<img src="' . $images['folder_hot_new'] . '" alt="New" title="New" hspace="3" />';
				
				$template->assign_block_vars('bookie_bets_due', array());
			}
		}

		$total_posts = get_db_stat('postcount');
		$total_topics = get_db_stat('topiccount');
		$total_users = get_db_stat('usercount');
		$newest_userdata = get_db_stat('newestuser');
		$newest_user = username_level_color($newest_userdata['username'], $newest_userdata['user_level'], $newest_userdata['user_id']);
		$newest_uid = $newest_userdata['user_id'];
		$newest_active = $newest_userdata['user_active'];
		$newest_user_since = $newest_userdata['user_regdate'];

		//
		// Post / User counts
		//
		if( $total_posts == 0 )
		{
			$l_total_post_s = $lang['Posted_articles_zero_total'];
		}
		else if( $total_posts == 1 )
		{
			$l_total_post_s = $lang['Posted_article_total'];
		}
		else
		{
			$l_total_post_s = $lang['Posted_articles_total'];
		}

		if( $total_users == 0 )
		{
			$l_total_user_s = $lang['Registered_users_zero_total'];
		}
		else if( $total_users == 1 )
		{
			$l_total_user_s = $lang['Registered_user_total'];
		}
		else
		{
			$l_total_user_s = $lang['Registered_users_total'];
		}

		if (empty($newest_active))
		{
			$newest_active_text = '(<a href="' . append_sid('faq.'.$phpEx.'#5') . '" class="gensmall">' . $lang['Newest_user_unconfirmed'] . '</a>)';
		}

		$total_posts_format = sprintf($l_total_post_s, $total_posts); 
		$total_posts_format = str_replace($total_posts, $total_posts, $total_posts_format); 
		$total_topics_format = sprintf($lang['Posted_topics_total'], $total_topics); 
		$total_topics_format = str_replace($total_topics, $total_topics, $total_topics_format); 
		$total_users_format = sprintf($l_total_user_s, $total_users); 
		$total_users_format = str_replace($total_users, $total_users, $total_users_format); 
	
		if ($board_config['gender_index'])
		{
			$total_male = get_db_stat('gender_male');
			$total_female = get_db_stat('gender_female');

			if ( $total_male == 0 )
			{
				$l_total_male = $lang['male_zero_total'];
			}
			else if ( $total_male == 1 )
			{
				$l_total_male = $lang['male_one_total'];
			}
			else
			{
				$l_total_male = $lang['male_total'];
			}		
	
			if ( $total_female == 0 )
			{
				$l_total_female = $lang['female_zero_total'];
			}
			else if ( $total_female == 1 )
			{	
				$l_total_female = $lang['female_one_total'];
			}
			else
			{
				$l_total_female = $lang['female_total'];
			}
	
			$total_male_format = sprintf($l_total_male, $total_male); 
			$total_male_format = str_replace($total_male, $total_male, $total_male_format); 
			$total_female_format = sprintf($l_total_female, $total_female); 
			$total_female_format = str_replace($total_female, $total_female, $total_female_format); 
		}

		//
		// Group count
		//
		if ($board_config['index_groups'])
		{
			$total_groups = get_db_stat('groupcount');
			$open_groups = get_db_stat('opengroupcount');
			$closed_groups = get_db_stat('closedgroupcount');
			$hidden_groups = get_db_stat('hiddengroupcount');
			$payment_groups = get_db_stat('paymentgroupcount');
			$newest_groupdata = get_db_stat('newestgroup');
			$newest_group = $newest_groupdata['group_name'];
			$newest_gid = $newest_groupdata['group_id'];
	
			if( $total_groups == 0 )
			{
				$l_total_group_s = $lang['Registered_groups_zero_total'];
				$l_type_group_s = '';
			}
			else if( $total_groups == 1 )
			{
				$l_total_group_s = $lang['Registered_group_total'];
				$l_type_group_s = $lang['Type_groups'];
			}
			else
			{
				$l_total_group_s = $lang['Registered_groups_total'];
				$l_type_group_s = $lang['Type_groups'];
			}
		}

		//
		// Daily users
		//
		$time1Hour = time() - 3600;
		$minutes = date('is', time());
		$hour_now = time() - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]); 
		$dato = create_date('H', time(), $board_config['board_timezone']);
		$timetoday = $hour_now - (3600 * $dato); 
	
		$sql = "SELECT session_ip, MAX(session_time) AS session_time 
			FROM " . SESSIONS_TABLE . " 
			WHERE session_user_id = '". ANONYMOUS . "' 
				AND session_time >= " . $timetoday . " 
				AND session_time < " . ( $timetoday + 86399 ) . "
			GROUP BY session_ip";
		if (!$result = $db->sql_query($sql)) 
		{
				message_die(GENERAL_ERROR, 'Could not retrieve guest user today data.', '', __LINE__, __FILE__, $sql); 
		}
		
		while( $guest_list = $db->sql_fetchrow($result))
		{ 
			if ($guest_list['session_time'] > $time1Hour) 
			{
				$users_lasthour++;
			}
		}
		$guests_today = $db->sql_numrows($result);
		$db->sql_freeresult($result);
	
		$sql = "SELECT user_id, username, user_allow_viewonline, user_level, user_lastlogon 
			FROM " . USERS_TABLE . " 
			WHERE user_id != '" . ANONYMOUS . "'
				AND user_session_time >= " . $timetoday . " 
				AND user_session_time < ". ( $timetoday + 86399 ) . " 
			ORDER BY username"; 
		if (!$result = $db->sql_query($sql)) 
		{
			message_die(GENERAL_ERROR, 'Could not retrieve registered user today data.', '', __LINE__, __FILE__, $sql); 
		}
	
		while( $todayrow = $db->sql_fetchrow($result)) 
		{ 
			if ($todayrow['user_lastlogon'] >= $time1Hour)
			{
				$users_lasthour++;
			}
	
			$todayrow['username'] = username_level_color($todayrow['username'], $todayrow['user_level'], $todayrow['user_id']);
	
			$users_today_list .= ( $todayrow['user_allow_viewonline'] ) ? ' <a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $todayrow['user_id']) . '" class="gensmall">' . $todayrow['username'] . '</a>,' : (($userdata[user_level]==ADMIN) ? ' <a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $todayrow['user_id']) . '" class="gensmall"><i>' . $todayrow['username'] . '</i></a>,' : '');
	
			if (!$todayrow['user_allow_viewonline']) 
			{
				$logged_hidden_today++;
			}
			else 
			{
				$logged_visible_today++;
			}
		}
		
		if ($users_today_list) 
		{
			$users_today_list[strlen($users_today_list)-1] = ' '; 
		} 
		else
		{
			$users_today_list = $lang['None'];
		}
		$total_users_today = $db->sql_numrows($result)+$guests_today;
		$db->sql_freeresult($result);
		
		$users_today_list = $lang['Registered_users'] . ' ' . $users_today_list;
		$l_today_user_s = ($total_users_today) ? ( ( $total_users_today == 1 ) ? $lang['User_today_total'] : $lang['Users_today_total'] ) : $lang['Users_today_zero_total'];
		$l_today_r_user_s = ($logged_visible_today) ? ( ( $logged_visible_today == 1 ) ? $lang['Reg_user_total'] : $lang['Reg_users_total'] ) : $lang['Reg_users_zero_total'];
		$l_today_h_user_s = ($logged_hidden_today) ? (($logged_hidden_today == 1) ? $lang['Hidden_user_total'] : $lang['Hidden_users_total'] ) : $lang['Hidden_users_zero_total'];
		$l_today_g_user_s = ($guests_today) ? (($guests_today == 1) ? $lang['Guest_user_total'] : $lang['Guest_users_total']) : $lang['Guest_users_zero_total'];
		$l_today_users = sprintf($l_today_user_s, $total_users_today);
		$l_today_users .= sprintf($l_today_r_user_s, $logged_visible_today); 	
		$l_today_users .= sprintf($l_today_h_user_s, $logged_hidden_today); 
		$l_today_users .= sprintf($l_today_g_user_s, $guests_today);
		
		if ( $total_users_today > $board_config['record_day_users'])  
		{  
			$board_config['record_day_users'] = $total_users_today;  
			$board_config['record_day_date'] = time();   
	
			$sql = "UPDATE " . CONFIG_TABLE . "  
				SET config_value = '$total_users_today'  
				WHERE config_name = 'record_day_users'";  
			if ( !$db->sql_query($sql) )  
			{  
				message_die(GENERAL_ERROR, 'Could not update today user record (number of users)', '', __LINE__, __FILE__, $sql);  
			}   
			
			$sql = "UPDATE " . CONFIG_TABLE . "  
				SET config_value = '" . $board_config['record_day_date'] . "'  
				WHERE config_name = 'record_day_date'";  
			if ( !$db->sql_query($sql) )  
			{  
				message_die(GENERAL_ERROR, 'Could not update today user record (date)', '', __LINE__, __FILE__, $sql);  
			}  
			
			// Remove cache file
			@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
		} 

		//
		// Birthday, Show users with birthday 
		//
		if ( $board_config['forum_module_calendar'] )
		{
			$cache_data_file = $phpbb_root_path . 'cache/birthday_' . $board_config['board_timezone'] . '.dat';
		   	if (@is_file($cache_data_file) && empty($SID))
	   		{
	   			$valid = (date('YzH', time()) - date('YzH', @filemtime($cache_data_file)) < 1) ? true : false;
	   		}
	   		else
	   		{
	   			$valid = false;
	   		}
	
			if (!empty($valid))
		   	{
		   		include ($cache_data_file);
		    	$birthday_today_list = stripslashes($birthday_today_list);
		    	$birthday_week_list = stripslashes($birthday_week_list);
		   	}
		   	else
		   	{ 	
		   		$sql = ($board_config['birthday_check_day']) ? "SELECT user_id, username, user_birthday, user_level FROM " . USERS_TABLE . " WHERE user_birthday != 999999 ORDER BY username" : '';
				if($result = $db->sql_query($sql)) 
				{ 
					if (!empty($result)) 
					{ 
						$this_year = create_date('Y', time(), $board_config['board_timezone']);
						$date_today = create_date('Ymd', time(), $board_config['board_timezone']);
						$date_forward = create_date('Ymd', time() + ($board_config['birthday_check_day'] * 86400), $board_config['board_timezone']);
					    while ($birthdayrow = $db->sql_fetchrow($result))
						{ 
							$user_birthday2 = $this_year . ($user_birthday = realdate('md', $birthdayrow['user_birthday'])); 
				      		if ( $user_birthday2 < $date_today ) 
				      		{
				      			$user_birthday2 += 10000;
							}
							if ( $user_birthday2 > $date_today && $user_birthday2 <= $date_forward ) 
							{ 
								// user are having birthday within the next days
								$user_age = ( $this_year . $user_birthday < $date_today ) ? $this_year - realdate('Y', $birthdayrow['user_birthday']) + 1 : $this_year - realdate('Y', $birthdayrow['user_birthday']); 
								
								$birthdayrow['username'] = username_level_color($birthdayrow['username'], $birthdayrow['user_level'], $birthdayrow['user_id']);
			
								$birthday_week_list .= ' <a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $birthdayrow['user_id']) . '" class="gensmall">' . $birthdayrow['username'] . ' (' . $user_age . ')</a>,'; 
							} 
							else if ( $user_birthday2 == $date_today ) 
				      		{ 
								//user have birthday today 
								$user_age = $this_year - realdate('Y', $birthdayrow['user_birthday']); 
								
								$birthdayrow['username'] = username_level_color($birthdayrow['username'], $birthdayrow['user_level'], $birthdayrow['user_id']);
				
								$birthday_today_list .= ' <a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $birthdayrow['user_id']) . '" class="gensmall">' . $birthdayrow['username'] . ' (' . $user_age . ')</a>,'; 
						  	} 
						}
						$db->sql_freeresult($result);
	
						if ($birthday_today_list) 
						{
							$birthday_today_list[strlen($birthday_today_list)-1] = ' ';
						}
						if ($birthday_week_list) 
						{
							$birthday_week_list[strlen($birthday_week_list)-1] = ' ';
						} 
					}
		 	
					if (empty($SID))
		      		{
		        		// Stores the data set in a cache file
		        	 	$data = "<?php\n";
		        	 	$data .= '$birthday_today_list = \'' . addslashes($birthday_today_list) . "';\n";
		        	 	$data .= '$birthday_week_list = \'' . addslashes($birthday_week_list) . "';\n?>";
		        	 	$fp = @fopen($cache_data_file, 'w');
		        	 	@fwrite($fp, $data);
		        	 	@fclose($fp);
		      		}
		   		}
			}
		}

		//
		// Welcome & Avatar On Index
		//
		$avatar_img = '';
		if ( $userdata['user_avatar_type'] && $userdata['user_allowavatar'] )
		{
			switch( $userdata['user_avatar_type'] )
			{
				case USER_AVATAR_UPLOAD:
					$avatar_img = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $userdata['user_avatar'] . '" alt="" title="" />' : '';
					break;
				case USER_AVATAR_REMOTE:
					$avatar_img = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $userdata['user_avatar'] . '" alt="" title="" />' : '';
					break;
				case USER_AVATAR_GALLERY:
					$avatar_img = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $userdata['user_avatar'] . '" alt="" title="" />' : '';
					break;
			}
		}	

		if ( !$avatar_img )
		{
			$avatar_img = '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/whosonline.gif" alt="" title="" />';
		}	

		// Check For Anonymous User
	    if ( $userdata['user_id'] != ANONYMOUS ) 
		{
		    $name_link = '<a href="' . append_sid('profile.'.$phpEx.'?mode=editprofile&amp;ucp=main') . '" title="' . $lang['Profile'] . '">' . $userdata['username'] . '</a>';
		}
		else
		{
		    $name_link = $lang['Guest'];
		}

		$rowspan = 2;
	
		//
		// Chatroom popup
		//
		if($board_config['chat_index'])
		{
			$rowspan = 3;
			require_once($phpbb_root_path . 'chatbox_front.'.$phpEx);
			
			if( $userdata['session_logged_in'] )
			{
				$chat_link = sprintf($lang['Click_to_join_chat'], '<a href="javascript:void(0);" onClick="window.open(\'' . append_sid('mods/chatbox/chatbox.'.$phpEx) . '\', \'' . $userdata['user_id'] . '_ChatBox\', \'scrollbars=no, width=540, height=450\')" class="gensmall">', '</a>');
			}
			else
			{
				$chat_link = sprintf($lang['Login_to_join_chat'], '<a href="' . append_sid($u_login_logout) . '" class="gensmall">', '</a>');
			}
			
			$template->assign_block_vars('show_chat', array(
				'TOTAL_CHATTERS_ONLINE' => sprintf($lang['How_Many_Chatters'], $howmanychat),
				'LOGON_LOGIN' => $chat_link,
				'CHATTERS_LIST' => sprintf($lang['Who_Are_Chatting'], $chatters)) 
			); 
		}
	
		//
		// Links
		//
		if ($board_config['forum_module_links'])
		{
			$language = $board_config['default_lang'];
			if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_linkdb.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_linkdb.' . $phpEx);
	
			$sql = "SELECT *
				FROM " . $prefix . "link_config";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query link configuration', '', __LINE__, __FILE__, $sql);
			}
		
			while( $row = $db->sql_fetchrow($result) )
			{
				$link_config_name = $row['config_name'];
				$link_config_value = $row['config_value'];
				$link_config[$link_config_name] = $link_config_value;
				
				$link_self_img = $link_config['site_logo'];
				$site_logo_height = $link_config['height'];
				$site_logo_width = $link_config['width'];	
				$display_interval = $link_config['display_interval'];
				$display_logo_num = $link_config['display_logo_num'];
			}
			$db->sql_freeresult($result);
		
			$sql = "SELECT link_id, link_name, link_logo_src
				FROM " . $prefix . "links
				WHERE link_approved = 1 
					AND link_logo_src <> ''
				ORDER BY RAND()";
			// If failed just ignore
			if( $result = $db->sql_query($sql) )
			{
				$links_logo = '';
				while($row = $db->sql_fetchrow($result))
				{
					$links_logo .= ('\'<a href="' . append_sid("linkdb.$phpEx?action=link&amp;link_id=" . $row['link_id']) . '" target="_blank"><img src="' . $row['link_logo_src'] . '" alt="' . $row['link_name'] . '" title="' . $row['link_name'] . '" width="' . $site_logo_width . '" height="' . $site_logo_height . '" hspace="1" /></a>\',' . "\n");
				}
				$db->sql_freeresult($result);		
	
				$links_logo = substr($links_logo, 0, -2);
				
				$template->assign_vars(array(
					'DISPLAY_INTERVAL' => $display_interval,
					'DISPLAY_LOGO_NUM' => $display_logo_num,
					'LINKS_LOGO' => $links_logo)
				);
			}
		}

		//
		// Simple Colored Usergroups
		//
		$group_legend = array();
		if ($board_config['enable_bots_whosonline'])
		{
			$group_legend[] = '<' . (($theme['template_name'] == 'prosilver') ? 'span' : 'b') . ' style="color: #' . $theme['botfontcolor'] . '">' . $lang['Bot_online_color'] . '</' . (($theme['template_name'] == 'prosilver') ? 'span' : 'b') . '>';
		}	
	
		if ( is_array($color_groups['groupdata']) )
		{	
			foreach($color_groups['groupdata'] AS $group_id => $group_data)
			{
				$group_color = ( !$userdata['session_logged_in'] ) ? $group_data['group_color'][$board_config['default_style']]: $group_data['group_color'][$userdata['user_style']];
	
				if ( !$group_color )
				{
					$match_found = false;
					foreach ( $group_data['group_color'] AS $color )
					{
						if ( !$match_found )
						{
							if ( $color )
							{
								$group_color = $color;
								$match_found = true;
							}
						}
					}
				}
				
				if ( $group_color )
				{
					$grouplink = '<a class="gensmall" href="' . append_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=" . $group_data['group_id']) . '"><' . (($theme['template_name'] == 'prosilver') ? 'span' : 'b') . ' style="color: #' . $group_color . '">' . $group_data['group_name'] . '</' . (($theme['template_name'] == 'prosilver') ? 'span' : 'b') . '></a>';
					$group_legend[] = $grouplink;
				}
			}
		}
	
		$group_legend = implode(', ', $group_legend);
		$group_legend = ( $group_legend ) ? ', ' . $group_legend : '';

		//
		// Site Announcement
		//
		if ($advance_html['announcement_status'])
		{
			$advance_html['announcement_text'] = smilies_pass($advance_html['announcement_text']);
			$announcement_text_uid = make_bbcode_uid();
			$advance_html['announcement_text'] = bbencode_first_pass($advance_html['announcement_text'], $announcement_text_uid);
			$advance_html['announcement_text'] = bbencode_second_pass($advance_html['announcement_text'], $announcement_text_uid);
			$announcement_text = str_replace("\n", "\n<br />\n", $advance_html['announcement_text']);
		
			$advance_html['announcement_guest_text'] = smilies_pass($advance_html['announcement_guest_text']);
			$advance_html['announcement_guest_text'] = bbencode_first_pass($advance_html['announcement_guest_text'], $announcement_text_uid);
			$advance_html['announcement_guest_text'] = bbencode_second_pass($advance_html['announcement_guest_text'], $announcement_text_uid);
			$announcement_guest_text = str_replace("\n", "\n<br />\n", $advance_html['announcement_guest_text']);
	
			if( $advance_html['announcement_access'] == ANNOUNCEMENTS_SHOW_ADM && $userdata['user_level'] == ADMIN )
			{
				$template->assign_block_vars('announcement_displayed', array());
			}
			else if ( $advance_html['announcement_access'] == ANNOUNCEMENTS_SHOW_MOD && ( $userdata['user_level'] == MOD || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == ADMIN ) )
			{
				$template->assign_block_vars('announcement_displayed', array());
			}
			else if ( $advance_html['announcement_access'] == ANNOUNCEMENTS_SHOW_REG && $userdata['session_logged_in'] )
			{
				$template->assign_block_vars('announcement_displayed', array());
			}
			else if ( $advance_html['announcement_access'] == ANNOUNCEMENTS_SHOW_ALL )
			{
			$template->assign_block_vars('announcement_displayed', array());
			}
			else if (  $advance_html['announcement_guest_status'] == ANNOUNCEMENTS_GUEST_YES && !$userdata['session_logged_in'] && !$advance_html['announcement_access'] == ANNOUNCEMENTS_SHOW_ALL )
			{
				$template->assign_block_vars('guest_announcement_displayed', array());
			}
		} 

		$page_title = $lang['Forum_Index'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		// Format newest user registration time
		if ( $board_config['time_today'] < $newest_user_since )
		{ 
			$newest_user_since = sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $newest_user_since, $board_config['board_timezone'])); 
		}
		else if ( $board_config['time_yesterday'] < $newest_user_since )
		{ 
			$newest_user_since = sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $newest_user_since, $board_config['board_timezone'])); 
		}
		else
		{ 
			$newest_user_since = $lang['Newest_user_since'] . ' ' . create_date($board_config['default_dateformat'], $newest_user_since, $board_config['board_timezone']); 
		} 	

		//
		// Include files and display module blocks
		//
		$module_width = $board_config['forum_module_width'];

		if ($board_config['forum_module_disable']) { $template->assign_block_vars('displaymodules', array() ); }
		if ($board_config['forum_module_glance']) { include($phpbb_root_path . 'index_glance.'.$phpEx); $template->assign_block_vars('displayindexglance', array() ); }
		if ($board_config['forum_module_calendar']) { $template->assign_block_vars('displayindexcalendar', array() ); }
		if ($board_config['forum_module_photo']) { random_pic_block(); }
		if ($board_config['forum_module_newsbar']) { include($phpbb_root_path . 'includes/functions_news.'.$phpEx); $template->assign_block_vars('displayindexnewsbar', array() ); }
		if ($board_config['forum_module_links']) { $template->assign_block_vars('displayindexlinks', array() ); }
		if ($board_config['forum_module_weather']) { $template->assign_block_vars('displayindexweather', array() ); }
		if ($board_config['forum_module_clock']) { $template->assign_block_vars('displayindexclock', array() ); }
		if ($board_config['forum_module_wallpaper']) { $template->assign_block_vars('displayindexwallpaper', array() ); }
		if ($board_config['forum_module_teamspeak']) { $template->assign_block_vars('displayindexteamspeak', array() ); }
		if ($board_config['forum_module_quote']) { quote_block(); }
		if ($board_config['allow_karma'] && $board_config['forum_module_karma']) { karma_block(); }
		if ($board_config['forum_module_newusers']) { new_users_block(); }
		if ($board_config['forum_module_topposters']) { top_posters_block(); }
		if ($board_config['forum_module_points']) { most_points_block(); }
		if ($board_config['forum_module_dloads']) { top_dloads_block(); }
		if ($board_config['forum_module_randomuser']) { random_user_block(); }
		if ($board_config['forum_module_game']) { random_game_block(); }
		if ($board_config['forum_module_donors']) { donors_block(); }
		if ($board_config['forum_module_shoutcast']) { $template->assign_block_vars('displayindexshoutcast', array() ); }
	}
	else
	{
		//
		// Get time, today & yesterday
		//
		$today_ary = explode('|', create_date('m|d|Y', time(), $board_config['board_timezone']));
		$board_config['time_today'] = gmmktime(0 - $board_config['board_timezone'] - $board_config['dstime'], 0, 0, $today_ary[0], $today_ary[1], $today_ary[2]);
		$board_config['time_yesterday'] = $board_config['time_today'] - 86400;
		unset($today_ary);

	}
	
	$template->set_filenames(array(
		'body' => 'index_body.tpl')
	);
	make_jumpbox('viewforum.'.$phpEx, $forum_id); 

	$template->assign_vars(array(
		'ROWSPAN' => $rowspan,
		'TOTAL_POSTS' => $total_posts_format,
		'TOTAL_TOPICS' => $total_topics_format,
		'TOTAL_USERS' => $total_users_format,
		'TOTAL_MALE' => $total_male_format,
		'TOTAL_FEMALE' => $total_female_format,
		'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $newest_uid) . '" class="gensmall">', $newest_user, '</a>'), 
		'NEWEST_USER_TEXT' => ' ' . $newest_active_text, 
		'NEWEST_USER_SINCE' => $newest_user_since,
		'LANGUAGE_SELECT' => language_select($board_config['default_lang'], 'language'), 
		'START_DATE' =>  create_date($board_config['default_dateformat'], $board_config['board_startdate'], $board_config['board_timezone']),

		'TOTAL_GROUPS' => ($board_config['index_groups']) ? '<br />' . sprintf($l_total_group_s, $total_groups) : '',
		'TYPE_GROUPS' => ($board_config['index_groups']) ? sprintf($l_type_group_s, $payment_groups, $open_groups, $closed_groups, $hidden_groups) : '',
		'NEWEST_GROUP' => ($board_config['index_groups']) ? '<br />' . sprintf($lang['Newest_group'], '<a class="gensmall" href="' . append_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=$newest_gid") . '">', $newest_group, '</a>') : '',

		'REGISTERED_NEW' => ($board_config['index_new_reg_users']) ? '<br />' . GetNewStats() : '',
		'USERS_TODAY_LIST' => $users_today_list,
		'MODULE_WIDTH' => $module_width,
		'CLOCK_WIDTH' => $module_width - 15,
		'CLOCK_FORMAT' => $userdata['user_clockformat'],
		'FORUM_IMG' => $images['forum'],
		'FORUM_NEW_IMG' => $images['forum_new'],
		'FORUM_LOCKED_IMG' => $images['forum_locked'],
		'AVATAR_IMG' => $avatar_img,
		'BOOKIE_START' => $lang['bookie_start'],
		'BOOKIE_COUNT' => $total_bets,
		'BOOKIE_END' => $lang['bookie_finish'],
		'ASSIGN_START' => $lang['bookie_assign_start'],
		'ASSIGN_COUNT' => $count_assigned,
		'ASSIGN_END' => $lang['bookie_assign_finish'],
		'BOOKIE_HEADER' => $lang['bookie_header'],
		'BOOKIE_IMAGE' => $bookie_image,
		'ASSIGN_IMAGE' => $assign_image,
		'FORUM_NOPOSTS_IMG' => $images['forum'],
		'FORUM_NEWPOSTS_IMG' => $images['forum_new'],
		'FORUM_LOCKED_IMG' => $images['forum_locked'],
	 	'NEWS_TITLE' => $board_config['news_title'],
	    'NEWS_COLOR' => $theme['adminfontcolor'],
	    'NEWS_BLOCK' => $board_config['news_block'],
	    'NEWS_STYLE' => $board_config['news_style'],
	    'NEWS_BOLD' => $board_config['news_bold'],
	    'NEWS_ITAL' => $board_config['news_ital'],
	    'NEWS_UNDER' => $board_config['news_under'],
	    'NEWS_SIZE' => $board_config['news_size'],
	    'SCROLL_SPEED' => $board_config['scroll_speed'],
	    'SCROLL_ACTION' => $board_config['scroll_action'],
		'SCROLL_BEHAVIOR'=> $board_config['scroll_behavior'],
	   	'SCROLL_SIZE' => $board_config['scroll_size'],
  		'SITE_ANNOUNCEMENTS' => $announcement_text, 
  		'GUEST_ANNOUNCEMENTS' => $announcement_guest_text, 
		'VISIT_COUNTER' => ($board_config['visit_counter_index']) ? sprintf($lang['Visit_counter'], $visit_counter) . ' ' . create_date($board_config['default_dateformat'], $board_config['board_startdate'], $board_config['board_timezone']) . '<br />' : '',
		'SITE_LOGO_WIDTH' => $site_logo_width,
		'SITE_LOGO_HEIGHT' => $site_logo_height,
		'GROUP_LEGEND' => $group_legend,

		'L_WITHIN' => $lang['Within'],
		'L_CLOCK' => $lang['Clock'],
		'L_BOARD_HAS' => $lang['Board_has'],
		'L_LOCAL_FORECAST' => $lang['Local_forecast'], 
		'L_NAME_WELCOME' => $lang['Welcome'],
		'L_USERS_LASTHOUR' => ( $users_lasthour ) ? sprintf($lang['Users_lasthour_explain'], $users_lasthour) : '',
		'L_USERS_TODAY' => $l_today_users,
		'L_WHOSBIRTHDAY_WEEK' => ( $board_config['birthday_check_day'] > 1 ) ? sprintf( (($birthday_week_list) ? $lang['Birthday_week'] : $lang['Nobirthday_week']), $board_config['birthday_check_day']) . $birthday_week_list : '',
		'L_WHOSBIRTHDAY_TODAY' => ( $board_config['birthday_check_day'] ) ? ($birthday_today_list) ? $lang['Birthday_today'] . $birthday_today_list : $lang['Nobirthday_today'] : '',
		'L_FORUM' => $lang['Forum'],
		'L_TOPICS' => $lang['Topics'],
		'L_REPLIES' => $lang['Replies'],
		'L_VIEWS' => $lang['Views'],
		'L_POSTS' => $lang['Posts'],
		'L_LASTPOST' => $lang['Last_Post'], 
		'L_NO_NEW_POSTS' => $lang['No_new_posts'],
		'L_NEW_POSTS' => $lang['New_posts'],
		'L_NO_NEW_POSTS_LOCKED' => $lang['No_new_posts_locked'], 
		'L_NEW_POSTS_LOCKED' => $lang['New_posts_locked'], 
		'L_ONLINE_EXPLAIN' => sprintf($lang['Online_explain'], $board_config['whosonline_time']), 
		'L_RECENT_POSTS' => $lang['Recent_posts'], 
	    'L_TOPIC_JUMP' => $lang['Topic_Jump'],
		'L_MODERATOR' => $lang['Moderators'], 
		'L_FORUM_LOCKED' => $lang['Forum_is_locked'],
		'L_MARK_FORUMS_READ' => $lang['Mark_all_forums'],
		'L_TEAMSPEAK' => $lang['Teamspeak'],
		'TS_TITLE' => $board_config['ts_sitetitle'],
		'TS_WIN_HEIGHT' => $board_config['ts_winheight'],
		'L_SHOUTCAST' => $lang['Shoutcast'],
		'SHOUTBOX_HEIGHT' => $board_config['forum_module_shoutcast_height'],				
		'U_SHOUTCAST' => append_sid('includes/shoutcast.'.$phpEx),
		'U_TEAMSPEAK' => append_sid('teamspeak.'.$phpEx),
		'U_NAME_LINK' => $name_link,
		'U_SITE_LOGO' => $link_self_img,
		'U_INDEX_WEATHER' => append_sid('index_weather.'.$phpEx),
	    'U_MARK_READ' => append_sid('index.'.$phpEx.'?mark=forums'), 
      	'U_VIEWTOPIC' => append_sid('viewtopic.'.$phpEx))
	);
	
	// Let's decide which categories we should display
	$display_categories = array();

	for ($i = 0; $i < $total_forums; $i++ )
	{
		if ($is_auth_ary[$forum_data[$i]['forum_id']]['auth_view'])
		{
			$display_categories[$forum_data[$i]['cat_id']] = true;
		}
	}

	//
	// Okay, let's build the index
	//
	for($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];

		if (in_array($cat_id, $itemcataccess)) 
		{
			$display_forums = true;
		}

		//
		// Yes, we should, so first dump out the category
		// title, then, if appropriate the forum list
		//
		if (isset($display_categories[$cat_id]) && $display_categories[$cat_id])
		{
			$template->assign_block_vars('catrow', array(
				'CAT_ID' => $cat_id,
				'CAT_DESC' => $category_rows[$i]['cat_title'],
				'CAT_ICON' => ( $category_rows[$i]['cat_icon'] && $category_rows[$i]['cat_icon'] != 'icon0.gif' && $category_rows[$i]['cat_icon'] != 'none.gif' ) ? '<img src="templates/' . ( ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images' ) . '/icon/' . $category_rows[$i]['cat_icon'] . '" alt="" title="" />' : '',
				'SPONSOR' => ( $category_rows[$i]['cat_sponsor_img'] ? ( $category_rows[$i]['cat_sponsor_url'] ? '<a href="' . $category_rows[$i]['cat_sponsor_url'] . '" target="_blank">' : '' ) . '<img src="' . $category_rows[$i]['cat_sponsor_img'] . '" alt="' . $category_rows[$i]['cat_sponsor_alt'] . '" title="' . $category_rows[$i]['cat_sponsor_alt'] . '" />' : '' ), 
				'U_VIEWCAT' => append_sid('index.'.$phpEx.'?' . POST_HIERARCHIE_URL . '=' . $hierarchie_level . '&amp;' . POST_PARENTFORUM_URL . '=' . $parent_forum . '&amp;' . POST_CAT_URL . '=' . $cat_id))
			);

			if ( $viewcat == $cat_id || $viewcat == -1 )
			{
				for($j = 0; $j < $total_forums; $j++)
				{
					if ( $viewcat == $cat_id && !$forum_data[$j]['hide_forum_in_cat'] )
					{
						unset($forum_data[$j]['hide_forum_on_index']);
					}

					if ( $forum_data[$j]['cat_id'] == $cat_id && !$forum_data[$j]['hide_forum_on_index'] )
					{
						$forum_id = $forum_data[$j]['forum_id'];

						if ( $is_auth_ary[$forum_id]['auth_view']  || in_array($forum_id, $itemformaccess) )
						{
							if ( $forum_data[$j]['forum_status'] == FORUM_LOCKED )
							{
								$folder_image = $images['forum_locked']; 
								$folder_alt = $lang['Forum_locked'];
							}
							else
							{
								$unread_topics = false;
								if ( $userdata['session_logged_in'] )
								{
									if ( !empty($new_topic_data[$forum_id]) )
									{
										$forum_last_post_time = 0;

										while( list($check_topic_id, $check_post_time) = @each($new_topic_data[$forum_id]) )
										{
											if ( empty($tracking_topics[$check_topic_id]) )
											{
												$unread_topics = true;
												$forum_last_post_time = max($check_post_time, $forum_last_post_time);

											}
											else
											{
												if ( $tracking_topics[$check_topic_id] < $check_post_time )
												{
													$unread_topics = true;
													$forum_last_post_time = max($check_post_time, $forum_last_post_time);
												}
											}
										}

										if ( !empty($tracking_forums[$forum_id]) )
										{
											if ( $tracking_forums[$forum_id] > $forum_last_post_time )
											{
												$unread_topics = false;
											}
										}

										if ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all']) )
										{
											if ( $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f_all'] > $forum_last_post_time )
											{
												$unread_topics = false;
											}
										}

									}
								}
									
								$forum_is_sub = $forum_data[$j]['forum_issub'];
								if ( $forum_is_sub == TRUE)
								{
									$folder_image = ( $unread_topics ) ? $images['forum_subforum_new'] : $images['forum_subforum'];
									$folder_alt = ( $unread_topics ) ? $lang['New_posts'] : $lang['No_new_posts'];
								}
								// Admin only forum
								else if ( $forum_data[$j]['auth_view'] == 5 )
								{
									$folder_image = ( $unread_topics ) ? $images['forum_admin_new'] : $images['forum_admin']; 
									$folder_alt = $lang['Forum_admin'];
								}
								else
								{
									$folder_image = ( $unread_topics ) ? $images['forum_new'] : $images['forum']; 
									$folder_alt = ( $unread_topics ) ? $lang['New_posts'] : $lang['No_new_posts']; 
								}
							}

							$posts = $forum_data[$j]['forum_posts'];
							$topics = $forum_data[$j]['forum_topics'];

							$forum_data[$j]['username'] = username_level_color($forum_data[$j]['username'], $forum_data[$j]['user_level'], $forum_data[$j]['user_id']);

							if ( $forum_data[$j]['forum_last_post_id'] )
							{
								$topic_title = $topic_title_alt = $forum_data[$j]['topic_title'];
						
								// Censor topic title
								if ( !empty($orig_word) )
								{
									$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
									$topic_title_alt = preg_replace($orig_word, $replacement_word, $topic_title_alt);
								}
								
								if (strlen($topic_title) > 33) 
								{
									$topic_title = substr($topic_title, 0, 30) . '...';
								}
									
								$topic_title = capitalization($topic_title);
								$topic_title_alt = capitalization($topic_title_alt);
								
								// Add quick topic titles
								if ($board_config['enable_quick_titles'])
								{
									if ( $forum_data[$j]['title_pos'] )
									{
										$topic_title = (empty($forum_data[$j]['title_compl_infos'])) ? $topic_title : $topic_title . ' <span style="color: #' . $forum_data[$j]['title_compl_color'] . '">' . $forum_data[$j]['title_compl_infos'] . '</span>';
									}
									else
									{
										$topic_title = (empty($forum_data[$j]['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $forum_data[$j]['title_compl_color'] . '">' . $forum_data[$j]['title_compl_infos'] . '</span> ' . $topic_title;
									}
								}
																
								$last_post_time = create_date($board_config['default_dateformat'], $forum_data[$j]['post_time'], $board_config['board_timezone']);
							
								$last_post = '';
								$last_post .= ($forum_data[$j]['index_lasttitle']) ? '<b><a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $forum_data[$j]['forum_last_post_id']) . '&amp;no=1#' . $forum_data[$j]['forum_last_post_id'] . '" title="' . $topic_title_alt . '" class="gensmall">' . str_replace("\'", "''", $topic_title) . '</a></b><br />' : '';
							
								if ( $board_config['time_today'] < $forum_data[$j]['post_time'])
								{ 
									$last_post .= sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $forum_data[$j]['post_time'], $board_config['board_timezone'])) . '<br />'; 
								}
								else if ( $board_config['time_yesterday'] < $forum_data[$j]['post_time'])
								{ 
									$last_post .= sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $forum_data[$j]['post_time'], $board_config['board_timezone'])) . '<br />'; 
								}
								else
								{ 
									$last_post .= $last_post_time . '<br />'; 
								} 
							
								$last_post .= ( $forum_data[$j]['user_id'] == ANONYMOUS ) ? ( ($forum_data[$j]['post_username'] != '' ) ? $forum_data[$j]['post_username'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $forum_data[$j]['user_id']) . '" class="gensmall">' . $forum_data[$j]['username'] . '</a> <a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $forum_data[$j]['forum_last_post_id']) . '&no=1#' . $forum_data[$j]['forum_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '"></a>';
							}
							else
							{
								$last_post = $lang['No_Posts'];
							}

							if ( sizeof($forum_moderators[$forum_id]) > 0 )
							{
								$l_moderators = (( sizeof($forum_moderators[$forum_id]) == 1 ) ? $lang['Moderator'] : $lang['Moderators']) . ':';
								$moderator_list = implode(', ', $forum_moderators[$forum_id]);
							}
							else
							{
								$l_moderators = $moderator_list = '';
							}

							// New posts & topics
							$number_new_topics = $number_new_posts = ''; 
							if ( $userdata['session_logged_in'] ) 
							{ 
								$sql = "SELECT COUNT(post_id) AS total 
									FROM " . POSTS_TABLE . " 
								    WHERE post_time >= " . $userdata['user_lastvisit'] . " 
								    	AND forum_id = " . $forum_id; 
								$result = $db->sql_query($sql); 
							    if ($result) 
							    { 
							      	$row = $db->sql_fetchrow($result); 
						            $number_new_posts = $row['total']; 
						      	} 
														
								if ( $number_new_posts == 1 )
								{ 
									$number_new_posts = '<br /><b>' . $number_new_posts . '</b> ' . $lang['Index_New_post']; 
								} 
								else if ( $number_new_posts > 1 ) 
								{ 
									$number_new_posts = '<br /><b>' . $number_new_posts . '</b> ' . $lang['Index_New_posts']; 
								} 
								else
								{
									$number_new_posts = ''; 						
								}
	
								$sql2 = "SELECT COUNT(topic_id) AS total 
									FROM " . TOPICS_TABLE . " 
									WHERE topic_time >= " . $userdata['user_lastvisit'] . " 
										AND forum_id = " . $forum_id; 
								$result2 = $db->sql_query($sql2); 
								if ($result2) 
								{ 
									$row2 = $db->sql_fetchrow($result2); 
									$number_new_topics = $row2['total']; 
								} 

								if ( $number_new_topics == 1 ) 
								{ 
									$number_new_topics =  '<br /><b>' . $number_new_topics . '</b> ' . $lang['Index_New_topic']; 
								} 
								else if ( $number_new_topics > 1 ) 
								{ 
									$number_new_topics = '<br /><b>' . $number_new_topics . '</b> ' . $lang['Index_New_topics']; 
								} 
								else
								{
									$number_new_topics = ''; 						
								}
							}
			
							$view_forum_url = $forum_is_sub ? append_sid("index.$phpEx?" . POST_HIERARCHIE_URL . "=" . ($hierarchie_level+1) . "&amp;" . POST_PARENTFORUM_URL . "=$forum_id") : append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id");
							$viewdesc = $forum_data[$j]['forum_desc'];
							
							$sql = "SELECT * FROM " . FORUMS_DESC_TABLE . " 
								WHERE forum_id = $forum_id";
							if( !($result = $db->sql_query($sql)) )
							{
								message_die(GENERAL_ERROR, 'Could not query forum descriptions', '', __LINE__, __FILE__, $sql);
							}
									
							while( $row = $db->sql_fetchrow($result) )
							{
								if (($row['user_id'] == $userdata['user_id']) && ($row['view'] == '0'))
								{
									$viewdesc = '';
								}
							}
							$db->sql_freeresult($result);
							
							//
							// External forum redirect
							//
							if ($forum_data[$j]['forum_external'])
							{
								$folder_image = ($forum_data[$j]['forum_ext_image']) ? $forum_data[$j]['forum_ext_image'] : $images['forum_external'];
								$forum_posts = $forum_topics = $forum_views = '--'; 
								$forum_details = $lang['External_text'] . ': ' . ($forum_data[$j]['forum_redirects_user'] + $forum_data[$j]['forum_redirects_guest']);
								$forum_details .= '<br />' . $lang['External_members'] . ': ' . $forum_data[$j]['forum_redirects_user'] . ' | ' . $lang['External_guests'] . ': ' . $forum_data[$j]['forum_redirects_guest'];

								$forum_url = append_sid("viewforum_external.$phpEx?" . POST_FORUM_URL . "=$forum_id");
								$forum_target = ($forum_data[$j]['forum_ext_newwin']) ? 'target="_blank"' : '';
							}
							else 
							{	
								$forum_posts = $forum_data[$j]['forum_posts'];
								$forum_topics = $forum_data[$j]['forum_topics'];
								$forum_views = $forum_data[$j]['forum_views'];
								$forum_details = $last_post;
	
								$forum_url = append_sid('viewforum.'.$phpEx.'?' . POST_FORUM_URL . '=' . $forum_id);
								$forum_target = '';
							}

							//
							// Who is active in which forum
							//
							if ($board_config['index_active_in_forum'])
							{
								if ( sizeof($onlinerow[$forum_id]) > 0 )
								{
									$users_total_guests = sizeof($guests_active[$forum_id]);
									$l_guests_total = ( $users_total_guests != 0 ) ? (( $users_total_guests == 1 ) ? $lang['Forum_one_guest_active'] : $lang['Forum_more_guests_active']) : '';
								}
								else
								{
									$users_total_guests = $l_guests_total = '';
								}
	
								if ( sizeof($onlinerow[$forum_id]) > 0 )
								{
									$users_total = sizeof($onlinerow[$forum_id]);
									$users_total_hidden = sizeof($logged_hidden_active[$forum_id]);
									$users_active = implode(', ', $onlinerow[$forum_id]);	
	
									$l_active_total = ( $users_total == 1 ) ? $lang['Forum_one_active'] : $lang['Forum_more_active'];
									$l_hidden_total = ( ($users_total_hidden != 0 ) ? ( $userdata['user_level'] == ADMIN ) ? '' : (( $users_total_hidden == 1 ) ? $lang['Forum_one_hidden_active'] : $lang['Forum_more_hidden_active']) : '');
								}
								else
								{
									$users_total = $users_total_hidden = $users_active = $l_hidden_total = $l_active_total = '';
								}
							}
							else
							{
								$users_total_guests = $l_guests_total = $users_total = $users_total_hidden = $users_active = $l_hidden_total = $l_active_total = '';
							}
							
							$template->assign_block_vars('catrow.forumrow', array( 
								'ACTIVE' => $users_active,
								'ACTIVE_TOTAL' => (($users_active) ? '<br />' : '') . sprintf($l_active_total, ($users_total + $users_total_guests)),
								'ACTIVE_INFO' => sprintf($l_hidden_total, $users_total_hidden) . sprintf($l_guests_total, $users_total_guests),
								'FORUM_FOLDER_IMG' => $folder_image, 
								'FORUM_NAME' => $forum_data[$j]['forum_name'],
                                'FORUM_DESC' => (!empty($viewdesc)) ? '<br /> ' . $viewdesc : '',
								'FORUM_ICON' => ( $forum_data[$j]['forum_icon'] && $forum_data[$j]['forum_icon'] != 'icon0.gif' && $forum_data[$j]['forum_icon'] != 'none.gif' ) ? '<img src="templates/' . ( ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images' ) . '/icon/' . $forum_data[$j]['forum_icon'] . '" alt="" title="" align="left" />' : '',
								'POSTS' => $forum_posts,
								'TOPICS' => $forum_topics,
								'VIEWS' => $forum_views,
								'LAST_POST' => $forum_details,
								'MODERATORS' => $moderator_list,
								'TARGET' => $forum_target,
								'FORUM_ID' => $forum_id,
								
								'NUM_NEW_TOPICS' => $number_new_topics, 
								'NUM_NEW_POSTS' => $number_new_posts, 
								
								'L_MODERATOR' => $l_moderators, 
								'L_FORUM_FOLDER_ALT' => $folder_alt, 
                                'L_TOGGLE_DESC' => ($forum_data[$j]['forum_toggle']) ? (($userdata['session_logged_in']) ? $lang['Toggle_description'] : '') : '',

                                'U_VIEWDESC' => append_sid('index.'.$phpEx.'?forum_tag=' . $forum_id),
								'U_VIEWFORUM' => $forum_url)
							);
															
							//
							// Display moderators on Viewforum?
							//
							if ($forum_data[$j]['display_moderators'])
							{
								$template->assign_block_vars('catrow.forumrow.switch_display_moderators', array());
							}
						}
					}
				}
			}
		}
	} // for ... categories

}// if ... total_categories
else
{
	message_die(GENERAL_MESSAGE, $lang['No_forums']);
}

//
// Statistics Module
//
if (intval($board_config['stat_index']) != 0 && !$sub_forum)
{
	include_once($phpbb_root_path . 'index_statistics.'.$phpEx);
}

//
// Toplist top ten
//
if ($board_config['toplist_toplist_top10'] && !$sub_forum)
{
	$f = 'toplist_top10';
	include_once($phpbb_root_path . 'toplist.'.$phpEx);
}

//
// Jump to topic
//
if ($board_config['jump_to_topic'] && !$sub_forum) { $template->assign_block_vars('switch_jump_to_topic', array()); }

//
// Shoutbox
//
if ($board_config['shoutbox_enable'] && !$sub_forum)
{	
	$template->assign_vars(array(
		'L_SHOUTBOX' => $lang['Shoutbox'],
		'SHOUTBOX_HEIGHT' => $board_config['shoutbox_height'],
			
		'U_SHOUTBOX' => append_sid('shoutbox.'.$phpEx),
		'U_SHOUTBOX_MAX' => append_sid('shoutbox_max.'.$phpEx))
	);
	
	$template->set_filenames(array(
		'shoutbox' => 'shoutbox_module_body.tpl')
	);
	$template->assign_var_from_handle('SHOUTBOX_TPL', 'shoutbox');
}
if ($board_config['shoutbox_enable'] && !$board_config['shoutbox_position'] && !$sub_forum) { $template->assign_block_vars('shoutbox_top', array()); }
if ($board_config['shoutbox_enable'] && $board_config['forum_module_disable'] && $board_config['shoutbox_position'] == 1 && !$sub_forum) { $template->assign_block_vars('shoutbox_side', array()); }
if ($board_config['shoutbox_enable'] && $board_config['shoutbox_position'] == 2 && !$sub_forum) { $template->assign_block_vars('shoutbox_btm', array()); }


//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

//
// Generate the page
//
if ($sub_forum)
{
	include_once($phpbb_root_path . 'viewhier.'.$phpEx);
	$template->pparse('hier');
}
else 
{
	if ($board_config['forum_module_calendar']) { include($phpbb_root_path . 'mods/calendar/mini_cal.'.$phpEx); }
	$template->pparse('body');
}

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
<?php
/** 
*
* @package phpBB2
* @version $Id: desktop.php,v 1.99.2.3 2004/07/11 16:46:15 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

// Number of Recent Topics (not Forum ID)
$CFG['number_recent_topics'] = '20';
// Exceptional Forums for Recent Topics, eg. '2,4,10'
$CFG['exceptional_forums'] = '';

define('IN_PHPBB', true);
define('SHOW_ONLINE', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//


//
// Include language file
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_portal.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_portal.' . $phpEx);


//
// Recent Topics
//
$sql = "SELECT * 
	FROM " . FORUMS_TABLE . " 
	ORDER BY forum_id";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
}

$is_auth_ary = $forum_data = array();

while( $row = $db->sql_fetchrow($result) )
{
	$forum_data[] = $row;
}
$db->sql_freeresult($result);

$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata, $forum_data);

if ($CFG['exceptional_forums'] == '')
{
	$except_forum_id = '\'start\'';
}
else
{
	$except_forum_id = $CFG['exceptional_forums'];
}

for ($i = 0; $i < sizeof($forum_data); $i++)
{
	if ((!$is_auth_ary[$forum_data[$i]['forum_id']]['auth_read']) || (!$is_auth_ary[$forum_data[$i]['forum_id']]['auth_view']))
	{
		if ($except_forum_id == '\'start\'')
		{
			$except_forum_id = $forum_data[$i]['forum_id'];
		}
		else
		{
			$except_forum_id .= ',' . $forum_data[$i]['forum_id'];
		}
	}
}

$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id, t.forum_id, p.post_id, p.poster_id, p.post_time, u.user_id, u.username, u.user_level
	FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u
	WHERE t.forum_id NOT IN (" . $except_forum_id . ")
		AND t.topic_status <> 2
		AND p.post_id = t.topic_last_post_id
		AND p.poster_id = u.user_id
	ORDER BY p.post_id DESC
	LIMIT " . $CFG['number_recent_topics'];
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not query recent topics information', '', __LINE__, __FILE__, $sql);
}
$number_recent_topics = $db->sql_numrows($result);

$recent_topic_row = array();

while ($row = $db->sql_fetchrow($result))
{
	$recent_topic_row[] = $row;
}
$db->sql_freeresult($result);

for ($i = 0; $i < $number_recent_topics; $i++)
{
	$template->assign_block_vars('recent_topic_row', array(
		'U_TITLE' => append_sid('viewtopic.'.$phpEx.'?' . POST_POST_URL . '=' . $recent_topic_row[$i]['post_id'] . '&amp;no=1#' . $recent_topic_row[$i]['post_id']),
		'L_TITLE' => $recent_topic_row[$i]['topic_title'],
		'U_POSTER' => append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $recent_topic_row[$i]['user_id']),
		'S_POSTER' => username_level_color($recent_topic_row[$i]['username'], $recent_topic_row[$i]['user_level'], $recent_topic_row[$i]['user_id']),
		'S_POSTTIME' => create_date($board_config['default_dateformat'], $recent_topic_row[$i]['post_time'], $board_config['board_timezone']))
	);
}

//
// Simple Colored Usergroups
//
$group_legend = array();
if ($board_config['enable_bots_whosonline'])
{
	$group_legend[] = '<b style="color: #' . $theme['botfontcolor'] . '">' . $lang['Bot_online_color'] . '</b>';
}	

if ( is_array($color_groups['groupdata']) )
{
	foreach($color_groups['groupdata'] AS $group_id => $group_data)
	{
		if ( !$userdata['session_logged_in'] )
		{
			$group_color = $group_data['group_color'][$board_config['default_style']];
		}
		else
		{
			$group_color = $group_data['group_color'][$userdata['user_style']];
		}
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
			$grouplink = '<a class="gensmall" href="' . append_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=" . $group_data['group_id']) . '" target="_blank"><b style="color: #' . $group_color . '">' . $group_data['group_name'] . '</b></a>';
			$group_legend[] = $grouplink;
		}
	}
}

$group_legend = implode(', ', $group_legend);
$group_legend = ( $group_legend ) ? ', ' . $group_legend : '';

//
// Start output of page
//
$gen_simple_header = $gen_simple_footer = true;
$page_title = $lang['Latest_Posts'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'desktop_body.tpl')
);

$template->assign_vars(array(
	'L_RECENT_TOPICS' => $lang['Latest_Posts'],
	'L_ONLINE_EXPLAIN' => sprintf($lang['Online_explain'], $board_config['whosonline_time']),
	'GROUP_LEGEND' => $group_legend,
	'L_ON' => $lang['On'])
);

//
// Generate the page
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
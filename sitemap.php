<?php
/** 
*
* @package phpBB2
* @version $Id: sitemap.php,v 1.99.2.3 2004/07/11 16:46:15 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

//
// Start session management 
//
$userdata = session_pagestart($user_ip, PAGE_SITEMAP); 
init_userprefs($userdata); 
//
// End session management 
//


//
// Is the Sitemap disabled?
//
if ( !($board_config['board_sitemap']) ) 
{ 
	message_die(GENERAL_MESSAGE, $lang['Sitemap_disabled']); 
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
// Get category list
//
$sql = "SELECT cat_id, cat_title
	FROM " . CATEGORIES_TABLE . "
	ORDER BY cat_title";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain category list.', '', __LINE__, __FILE__, $sql);
} 
	
while ($row = $db->sql_fetchrow($result))
{	
	$template->assign_block_vars('categories', array(
		'CAT_TITLE' => $row['cat_title'],
		'U_VIEWCATEGORY' => append_sid('index.'.$phpEx.'?' . POST_CAT_URL . '=' . $row['cat_id']))
	);
}	
$db->sql_freeresult($result);


//
// Get forums list
//
$sql = "SELECT forum_id, forum_name
	FROM " . FORUMS_TABLE . "
	WHERE forum_id > 0
	ORDER BY forum_name";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain forum list.', '', __LINE__, __FILE__, $sql);
} 

while ($row = $db->sql_fetchrow($result))
{
	$is_auth = auth(AUTH_READ, $row['forum_id'], $userdata); 
	if ( $is_auth['auth_read'] ) 
	{ 
		$template->assign_block_vars('forums', array(
			'FORUM_NAME' => $row['forum_name'],
			'U_VIEWFORUM' => append_sid('viewforum.'.$phpEx.'?' . POST_FORUM_URL . '=' . $row['forum_id']))
		);
	}
}
$db->sql_freeresult($result);


//
// Get topics list
//
$sql = "SELECT topic_id, topic_title, forum_id
	FROM " . TOPICS_TABLE . "
	ORDER BY topic_title";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain topics list.', '', __LINE__, __FILE__, $sql);
} 
	
while ($row = $db->sql_fetchrow($result))
{	
	$is_auth = auth(AUTH_READ, $row['forum_id'], $userdata); 
	if ( $is_auth['auth_read'] ) 
	{ 
		$topic_title = $row['topic_title'];

		// Censor topic title
		if ( !empty($orig_word) )
		{
			$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
		}

		$template->assign_block_vars('topics', array(
			'TOPIC_TITLE' => $topic_title,
			'U_VIEWTOPIC' => append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $row['topic_id']))
		);	
	}
}
$db->sql_freeresult($result);


//
// Get groups list
//
$sql = "SELECT group_id, group_name, group_type
   	FROM " . GROUPS_TABLE . "
   	WHERE group_single_user <> " . TRUE . "
  		AND group_type <> " . GROUP_HIDDEN . "
   	ORDER BY group_name";
if ( !($result = $db->sql_query($sql)) )
{
   	message_die(GENERAL_ERROR, 'Could not obtain group list.', '', __LINE__, __FILE__, $sql);
}

while ($row = $db->sql_fetchrow($result))
{
	$template->assign_block_vars('groups', array(
   		'GROUP_NAME' => $row['group_name'],
   		'U_VIEWGROUP' => append_sid('groupcp.'.$phpEx.'?' . POST_GROUPS_URL . '=' . $row['group_id']))
   	);
}
$db->sql_freeresult($result);


//
// Get users list
//
$sql = "SELECT user_id, username, user_level
	FROM " . USERS_TABLE . "
	WHERE user_id <> " . ANONYMOUS . " 
	ORDER BY username";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain users list.', '', __LINE__, __FILE__, $sql);
} 
	
while ($row = $db->sql_fetchrow($result))
{	
	$template->assign_block_vars('users', array(
		'USERNAME' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
		'U_VIEWPROFILE' => append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']))
	);
}
$db->sql_freeresult($result);


//
// Generate page
//
$page_title = $lang['Sitemap']; 
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

$template->set_filenames(array( 
        'body' => 'sitemap_body.tpl') 
); 
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_CATEGORIES' => $board_config['sitename'] . ' ' . $lang['cat_c'],
	'L_FORUMS' => $board_config['sitename'] . ' ' . $lang['All_forums'],
	'L_TOPICS' => $board_config['sitename'] . ' ' . $lang['Topics'],
   	'L_GROUPS' => $board_config['sitename'] . ' ' . $lang['Groupcp'],
	'L_USERS' => $board_config['sitename'] . ' ' . $lang['External_members'])
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
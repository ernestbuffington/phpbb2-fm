<?php
/** 
*
* @package phpBB
* @version $Id: viewtopic_viewed.php,v 1.36.2.2 2002/07/29 05:04:03 dougk_ff7 Exp $
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
$userdata = session_pagestart($user_ip, PAGE_TOPIC_VIEW);
init_userprefs($userdata);
//
// End session management
//

if ( !$board_config['enable_topic_view_users'] )
{ 
	message_die(GENERAL_MESSAGE, $lang['Topic_view_users_disabled']); 
}


if ( isset($HTTP_GET_VARS[POST_TOPIC_URL]) )
{
	$topic_id = intval($HTTP_GET_VARS[POST_TOPIC_URL]);
}
else if ( isset($HTTP_POST_VARS[POST_TOPIC_URL]) )
{
	$topic_id = intval($HTTP_POST_VARS[POST_TOPIC_URL]);
}

if ( !$userdata['session_logged_in'] ) 
{ 
	$header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: "; 
	header($header_location . append_sid("login.$phpEx?redirect=viewtopic_viewed.$phpEx&" . POST_TOPIC_URL . "=$topic_id", true));
	exit;  
}

// find the forum, in which the topic are located
$sql = "SELECT f.forum_id 
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f  
	WHERE f.forum_id = t.forum_id 
		AND t.topic_id = $topic_id";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain topic information", '', __LINE__, __FILE__, $sql);
}

if ( !($forum_topic_data = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}
$forum_id = intval($forum_topic_data['forum_id']);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

//
// Generate page
//
$gen_simple_header = true;
$page_title = $lang['Topic_viewers'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'viewtopic_viewed_body.tpl')
);

$template->assign_vars(array(
	'TOPIC_ID' => $topic_id,
	'L_VIEWERS' => $lang['Topic_viewers'],
	'L_VIEWS' => $lang['Views'],
	'L_CLOSE' => $lang['Who_posted_msg'],
	'L_DATE'=> $lang['Last_viewed'])
);


$sql = "SELECT t.user_id, t.last_viewed, t.num_views, u.username, u.user_level
	FROM " . TOPICS_VIEWDATA_TABLE . " AS t
		LEFT JOIN " . USERS_TABLE . " AS u ON t.user_id = u.user_id 
	WHERE t.topic_id = " . $topic_id . "
	GROUP BY t.user_id
	ORDER BY t.last_viewed DESC, u.username";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$i = 0;
	do
	{
		$username = username_level_color($row['username'], $row['user_level'], $row['user_id']);
		$username = ( $row['user_id'] == ANONYMOUS ) ? $lang['Guest'] : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $row['user_id']) . '" class="gensmall" target="_blank">' . $username . '</a>';

		$topic_time = ( $row['last_viewed'] ) ? create_date($board_config['default_dateformat'], $row['last_viewed'], $board_config['board_timezone']) : $lang['Never_last_logon'];
		$view_count = ( $row['num_views'] ) ? $row['num_views'] : '';

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('memberrow', array(
			'ROW_NUMBER' => $i + ( $HTTP_GET_VARS['start'] + 1 ),
			'ROW_CLASS' => $row_class,
			'USERNAME' => $username,
			'VIEWS' => $view_count,
			'TIME' => $topic_time)
		);

		$i++;
	}
	while ( $row = $db->sql_fetchrow($result) );
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
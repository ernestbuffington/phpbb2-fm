<?php 
/** 
*
* @package phpBB
* @version $Id: viewpost.php,v 0.1.2 2002 Exp $
* @copyright (c) 2002 Edgardo Rossetto
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start initial var setup
//
if ( isset($HTTP_GET_VARS[POST_TOPIC_URL]) )
{
	$topic_id = intval($HTTP_GET_VARS[POST_TOPIC_URL]);
}
else if ( isset($HTTP_GET_VARS['topic']) )
{
	$topic_id = intval($HTTP_GET_VARS['topic']);
}

if ( !isset($topic_id) )
{
	message_die(GENERAL_MESSAGE, 'No_post_id');
}

$sql = "SELECT forum_id 
	FROM " . TOPICS_TABLE . "
	WHERE topic_id = $topic_id";
if( !($result = $db->sql_query($sql)) )
{ 
	message_die(GENERAL_ERROR, 'Could not query topics table', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);

$forum_id = $row['forum_id'];

//
// Start session management
//
$userdata = session_pagestart($user_ip, $forum_id);
init_userprefs($userdata);
//
// End session management
//

$sql = "SELECT COUNT(p.post_id) AS posts, p.topic_id, p.poster_id, u.user_id, u.username, u.user_level
	FROM " . POSTS_TABLE . " AS p
		LEFT JOIN " . USERS_TABLE . " AS u ON (p.poster_id = u.user_id)
	WHERE p.topic_id = $topic_id
	GROUP BY p.poster_id
	ORDER BY posts DESC";
if( !($result = $db->sql_query($sql)) )
{ 
	message_die(GENERAL_ERROR, 'Could not obtain topic data.', '', __LINE__, __FILE__, $sql);
}

$i = 0;
$total_posts = 0;
while( $row = $db->sql_fetchrow($result) ) 
{
	$row['username'] = username_level_color($row['username'], $row['user_level'], $user_id);

	$poster = ( $row['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $row['user_id']) . '" class="genmed" target="_blank">' : '';
	$poster .= ( $row['user_id'] != ANONYMOUS ) ? $row['username'] : $lang['Guest'];
	$poster .= ( $row['user_id'] != ANONYMOUS ) ? '</a>' : '';

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars("whoposted", array(
		'ROWCLASS' => $row_class,
		'POSTER' => $poster,
		'POSTS' => $row['posts'])
	);
	$total_posts += $row['posts'];
	$i++;
}

$template->set_filenames(array(
	'body' => 'viewtopic_posted_body.tpl')
);

$template->assign_vars(array(
	'TOPIC_ID' => $topic_id,
	'TOTAL_POSTS' => $total_posts,
	'L_CLOSE' => $lang['Who_posted_msg'],
	'L_TOTAL_POSTS' => $lang['Total_posts'],
	'L_AUTHOR' => $lang['Author'],
	'L_POSTS' => $lang['Posts'])
);

$page_title = $lang['Who_posted'];
$gen_simple_header = true;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->pparse("body");

?>
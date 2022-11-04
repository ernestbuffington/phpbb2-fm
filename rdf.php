<?php
/** 
*
* @package phpBB2
* @version $Id: rdf.php,v 1.3.1 2003/02/16 14:43:11 mvdwater Exp $
* @copyright (c) 2002 Matthijs van de Water, Sascha Carlin
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define ('IN_PHPBB', true);
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

// If not set, set the output count to 15
$count = ( isset($HTTP_GET_VARS['c']) ) ? intval($HTTP_GET_VARS['c']) : 15;
$count = ( $count == 0 ) ? 15 : $count;

// Check for forum_id in query
$forum_id = ( isset($HTTP_GET_VARS['f']) ) ? intval($HTTP_GET_VARS['f']) : '';
$sql_where = ( !empty($forum_id) ) ? ' AND f.forum_id = ' . $forum_id : ' ';

// Create main board url (some code borrowed from functions_post.php)
$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
$viewtopic = ( $script_name != '' ) ? $script_name . '/viewtopic.' . $phpEx : 'viewtopic.'. $phpEx;
$index = ( $script_name != '' ) ? $script_name . '/index.' . $phpEx : 'index.'. $phpEx;
$server_name = trim($board_config['server_name']);
$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

$site_name = strip_tags($board_config['sitename']);
$site_description = strip_tags($board_config['site_desc']);

$index_url = $server_protocol . $server_name . $server_port . $index;
$viewtopic_url = $server_protocol . $server_name . $server_port . $viewtopic;

// Initialise template
$template->set_filenames(array(
	"body" => "rdf_body.tpl")
);

$template->assign_vars(array(
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'U_FORUM' => $index_url,
	'FORUM_TITLE' => $site_name,
	'FORUM_DESCRIPTION' => $site_description)
);

// SQL statement to fetch active topics of public forums
$sql = "SELECT t.topic_id, t.topic_title, t.topic_last_post_id
	FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p, " . FORUMS_TABLE . " AS f
	WHERE t.forum_id = f.forum_id
		AND f.auth_view = " . AUTH_ALL . "
		AND p.topic_id = t.topic_id
		AND p.post_id = t.topic_last_post_id
		$sql_where
	ORDER BY p.post_time DESC LIMIT $count";
$topics_query = $db->sql_query($sql);

if ( !$topics_query )
{
	message_die(GENERAL_ERROR, "Could not query list of active topics", "", __LINE__, __FILE__, $sql);
}
else if ( !$db->sql_numrows($topics_query) )
{
	message_die(GENERAL_MESSAGE, $lang['No_match']);
}
else
{
	while ($topic = $db->sql_fetchrow($topics_query))
	{
		$template->assign_block_vars('topic_item', array(
			'U_TOPIC' => $viewtopic_url . '?' . POST_POST_URL . '=' . $topic['topic_last_post_id'] . '#' . $topic['topic_last_post_id'],
			'TOPIC_TITLE' => $topic['topic_title'])
		);
	}
	$db->sql_freeresult($topics_query);
}

// XML and nocaching headers, copied from page_header.php
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header ('Content-Type: text/xml');

// Output XML page
$template->pparse('body');

?>
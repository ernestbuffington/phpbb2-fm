<?php
/** 
*
* @package phpBB2
* @version $Id: profile_view_popup.php,v 1.193.2.5 2003/05/15 17:49:37 oxpus Exp $
* @copyright (c) 2003 OXPUS
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
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

$gen_simple_header = TRUE;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$user_id = $userdata['user_id'];
$last_view = $userdata['user_last_profile_view'];

$page_title = $lang['Profile'] . ' ' . $lang['Views'];
$template->set_filenames(array(
	'body' => 'profile_popup.tpl')
);

$sql = "SELECT p.*, u.username, u.user_level 
	FROM " . PROFILE_VIEW_TABLE . " p, " . USERS_TABLE . " u
	WHERE p.viewer_id = u.user_id
		AND p.user_id = " . $user_id . "
		AND p.view_stamp >= " . $last_view . "
	ORDER BY p.view_stamp DESC";
if ( !($result = $db->sql_query($sql)) )
{
   message_die(GENERAL_ERROR, 'Could not obtain profile views.', '', __LINE__, __FILE__, $sql);
}

$i = 0;
while ($row = $db->sql_fetchrow($result))
{
	$viewer = $row['viewer_id'];
	
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('row', array(
		'ROW_CLASS' => $row_class,
		'VIEW_BY' => ($viewer != ANONYMOUS) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $viewer). '" target="_new" class="gen">' . username_level_color($row['username'], $row['user_level'], $viewer) . '</a>' : $row['username'],
		'STAMP' => create_date($board_config['default_dateformat'], $row['view_stamp'], $board_config['board_timezone']))
	);
}
$db->sql_freeresult($result);

$template->assign_vars(array(
	'L_TITLE' => $page_title . ' - <a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '" target="_new" class="gen">' . $userdata['username'] . '</a>',
	'L_CLOSE' => $lang['Close_window'],
	'L_VIEWER' => $lang['Username'],
	'L_STAMP' => $lang['Last_updated'])
);

$sql = "UPDATE " . USERS_TABLE . "
	SET user_profile_view = 0, user_last_profile_view = " . time() . "
	WHERE user_id = " . $user_id;
if ( !$db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not update user profile view data.', '', __LINE__, __FILE__, $sql);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
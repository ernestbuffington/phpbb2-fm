<?php
/** 
*
* @package phpBB2
* @version $Id: profile_views.php,v 1.5 2003/05/19 10:16:30 oxpus Exp $
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

include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$user_id = (isset($HTTP_POST_VARS[POST_USERS_URL])) ? intval($HTTP_POST_VARS[POST_USERS_URL]) : intval($HTTP_GET_VARS[POST_USERS_URL]);

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$page_title = $lang['Profile'] . ' ' . $lang['Views'];
$template->set_filenames(array(
	'body' => 'profile_views_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$sql = "SELECT username 
	FROM " . USERS_TABLE . "
	WHERE user_id = " . $user_id;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain userdata.', '', __LINE__, __FILE__, $sql);
}
$profile = $db->sql_fetchrow($result);

if (!is_array($profile))
{
	message_die(GENERAL_MESSAGE, $lang['No_such_user']);
}

$sql = "SELECT p.*
	FROM " . PROFILE_VIEW_TABLE . " p, " . USERS_TABLE . " u
	WHERE p.viewer_id = u.user_id
		AND p.user_id = " . $user_id;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not read profile views.", '', __LINE__, __FILE__, $sql);
}
$total = $db->sql_numrows($result);
$db->sql_freeresult($result);

$pagination = generate_pagination('profile_views.'.$phpEx.'?' . POST_USERS_URL . '=' . $user_id, $total, $board_config['posts_per_page'], $start);

$sql = "SELECT p.*, u.username, u.user_avatar AS current_user_avatar, u.user_avatar_type AS current_user_avatar_type, u.user_allowavatar, u.user_level
	FROM " . PROFILE_VIEW_TABLE . " p, " . USERS_TABLE . " u
	WHERE p.viewer_id = u.user_id
		AND p.user_id = " . $user_id . "
	ORDER BY p.view_stamp DESC
	LIMIT $start, " . $board_config['posts_per_page'];
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not read profile views.', '', __LINE__, __FILE__, $sql);
}

$i = 0;
while ($row = $db->sql_fetchrow($result))
{
	$viewer = $row['viewer_id'];

	$viewer_avatar = '';
	if ( $row['user_avatar_type'] && $row['user_allowavatar'] && $userdata['user_showavatars'] && $userdata['avatar_sticky'])
	{
		switch( $row['user_avatar_type'] )
		{
			case USER_AVATAR_UPLOAD:
				$viewer_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $row['user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$viewer_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $row['user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_GALLERY:
				$viewer_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $row['user_avatar'] . '" alt="" title="" />' : '';
				break;
		}
	}
	else if ( ($row['current_user_avatar_type']) && $row['user_allowavatar'] && $userdata['user_showavatars'] )
	{
		switch( $row['current_user_avatar_type'] )
		{
			case USER_AVATAR_UPLOAD:
				$viewer_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $row['current_user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$viewer_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $row['current_user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_GALLERY:
				$viewer_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $row['current_user_avatar'] . '" alt="" title="" />' : '';
				break;
		}
	}
	
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('row', array(
		'ROW_CLASS' => $row_class,
		'AVATAR' => $viewer_avatar,
		'VIEW_BY' => ($viewer != ANONYMOUS) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $viewer) . '" class="genmed">' . username_level_color($row['username'], $row['user_level'], $viewer) . '</a>' : $row['username'],
		'NUMBER' => $row['counter'],
		'STAMP' => create_date($board_config['default_dateformat'], $row['view_stamp'], $boar_config['board_timezone']))
	);
	$i++;	
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PROFILE' => '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '" class="nav">' . $profile['username'] . '</a>',
	'L_VIEW_TITLE' => $page_title,
	'L_VIEWER' => $lang['Username'],
	'L_NUMBER' => $lang['Views'],
	'L_STAMP' => $lang['Last_viewed'])
);

$template->pparse('body');

include ($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
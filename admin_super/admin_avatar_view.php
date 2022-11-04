<?php
/** 
*
* @package admin_super
* @version $Id: admin_avatar_view.php,v 1.00 2003/02/15 cheakamus Exp $
* @copyright (c) 2003 Jay MacDonald
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Avatars']['View_Avatars'] = $filename;

	return;
}

//
// Load default header
//
$phpbb_root_path = "../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.' . $phpEx);

//
// Perform SQL query to get all the avatars
//
$sql = "SELECT user_id, username, user_level, user_avatar, user_avatar_type
	FROM " . USERS_TABLE . "
	WHERE user_avatar_type != " . USER_AVATAR_NONE . "
	ORDER BY username";
$result = $db->sql_query($sql);
if( !$result )
{
	message_die(GENERAL_ERROR, "Couldn't obtain avatars from database", '', __LINE__, __FILE__, $sql);
}

$avatars = $db->sql_fetchrowset($result);

$template->set_filenames(array(
	'body' => 'admin/avatar_view_body.tpl')
);

$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['View_Avatars'],
	'L_PAGE_DESCRIPTION' => $lang['Avatar_viewer_explain'])
);

$columns=4;
$onrow=0;

//
// Loop through the avatars setting block vars for the template.
//
for($i = 0; $i < sizeof($avatars); $i = $i + $columns)
{
	$row_class = ( !($onrow % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars("avatar_row", array(
		"ROW_CLASS" => $row_class)
	);

	$onrow++;

	for ( $j = 0; $j < $columns; $j++ )
	{

		$avatar_id = $i + $j;
		if ( $avatars[$avatar_id]['user_avatar'] )
		{
			switch ($avatars[$avatar_id]['user_avatar_type']) {
				case USER_AVATAR_UPLOAD:
					$img_src = $phpbb_root_path . $board_config['avatar_path'] . '/' . $avatars[$avatar_id]['user_avatar'];
					break;
				case USER_AVATAR_REMOTE:
					$img_src = $avatars[$avatar_id]['user_avatar'];
					break;
				case USER_AVATAR_GALLERY:
					$img_src = $phpbb_root_path . $board_config['avatar_gallery_path'] . '/' . $avatars[$avatar_id]['user_avatar'];
					break;
                        }

			$template->assign_block_vars("avatar_row.avatars", array(
				"AVATAR_IMG" => $img_src,
				"LINK" => append_sid("admin_users.php?mode=edit&amp;" . POST_USERS_URL . "=". $avatars[$avatar_id]['user_id']),
				"USERNAME" => $avatars[$avatar_id]['username'],
				"USERNAME2" => username_level_color($avatars[$avatar_id]['username'], $avatars[$avatar_id]['user_level']))
			);
		}
	}
}

//
// Spit out the page.
//
$template->pparse('body');

include('../admin/page_footer_admin.'.$phpEx);

?>
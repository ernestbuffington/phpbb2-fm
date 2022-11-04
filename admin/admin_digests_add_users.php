<?php
/***************************************************************************
 *                            admin_digests_add_users.php
 *                            ---------------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: 
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Email_Digests']['Add User Digest'] = $filename;

	return;
}

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$html_entities_match = array('#<#', '#>#');
$html_entities_replace = array('&lt;', '&gt;');

// Set mode
//
if( isset( $HTTP_POST_VARS['mode'] ) || isset( $HTTP_GET_VARS['mode'] ) )
{
	$mode = ( isset( $HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}

if ($mode == 'lookup')
{
	$no_page_header = TRUE;
	
	// Lookup user
	//
	$username = (!empty($HTTP_POST_VARS['username'])) ? str_replace('%', '%%', trim(strip_tags($HTTP_POST_VARS['username']))) : '';

	$sql_where = (!empty($username)) ? 'u.username LIKE "%' . str_replace("\'", "''", $username) . '%"' : '';

	if (!empty($sql_where))
	{
		$sql = "SELECT u.user_id, u.username, u.user_email, u.user_posts, u.user_active, u.user_regdate
			FROM " . USERS_TABLE . " u
			WHERE $sql_where
			ORDER BY u.username ASC";

		if (!( $result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Unable to query users', '', __LINE__, __FILE__, $sql);
		}
		else if (!$db->sql_numrows($result))
		{
			$message = $lang['No_user_id_specified'];
			$message .= '<br /><br />' . sprintf($lang['Click_return_digest_user'], '<a href="' . append_sid("admin_digests_add_users.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else if ($db->sql_numrows($result) == 1)
		{
			// Redirect to this user
			$row = $db->sql_fetchrow($result);

			$template->assign_vars(array(
				"META" => '<meta http-equiv="refresh" content="3;url=' . append_sid($phpbb_root_path . "digests." . $phpEx . "?mode=admin&user_id=" . $row['user_id']) . '">')
			);

			$message .= $lang['One_user_found'];
			$message .= '<br /><br />' . sprintf($lang['Click_goto_digest_user'], '<a href="' . append_sid($phpbb_root_path . "digests." . $phpEx . "?mode=admin&user_id=" . $row['user_id']) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			// Show select screen
			$template->set_filenames(array(
				'body' => 'admin/digests_user_lookup_body.tpl')
			);

			$template->assign_vars(array(
				'L_USERNAME' => $lang['Username'],
				'L_USER_TITLE' => $lang['User_admin'],
				'L_POSTS' => $lang['Posts'],
				'L_JOINED' => $lang['Sort_Joined'],
				'L_USER_EXPLAIN' => $lang['User_admin_explain'],
				'L_ACTIVE' => $lang['User_status'],
				'L_EMAIL_ADDRESS' => $lang['Email_address'])
			);

			$i = 0;
			while ( $row = $db->sql_fetchrow($result) )
			{
				$row_color = (!($i % 2)) ? $theme['td_color1'] : $theme['td_color2'];
				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('user_row', array(
					'ROW_COLOR' => '#' . $row_color,
					'ROW_CLASS' => $row_class,
					'USERNAME' => $row['username'],
					'EMAIL' => $row['user_email'],
					'POSTS' => $row['user_posts'],
					'ACTIVE' => ( $row['user_active'] ) ? $lang['Yes'] : $lang['No'],
					'JOINED' => create_date($lang['DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']),

					'U_USERNAME' => append_sid($phpbb_root_path . "digests." . $phpEx . "?mode=admin&user_id=" . $row['user_id']))
				);

				$i++;
			}
			$template->pparse('body');
		}
	}
	else
	{
		$message = $lang['No_user_id_specified'];
		$message .= '<br /><br />' . sprintf($lang['Click_return_digest_user'], '<a href="' . append_sid("admin_digests_add_users.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		message_die(GENERAL_MESSAGE, $message);
	}
}
else
{
	// Default user selection box
	//
	$template->set_filenames(array(
		'body' => 'admin/digests_user_select_body.tpl')
	);

	$template->assign_vars(array(
		'L_USER_TITLE' => $lang['Digest_user_admin'],
		'L_USER_EXPLAIN' => $lang['Digest_user_admin_explain'],
		'L_USER_LOOKUP_EXPLAIN' => $lang['Digest_lookup_explain'],
		'L_LOOK_UP' => $lang['Look_up_user'],
		'L_USERNAME' => $lang['Username'],

		'S_USER_ACTION' => append_sid("admin_digests_add_users.$phpEx"),
		)
	);

	$template->pparse('body');
}

include('./page_footer_admin.'.$phpEx);

?>
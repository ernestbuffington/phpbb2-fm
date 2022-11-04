<?php
/***************************************************************************
 *                            admin_digests_verify.php
 *                            ------------------------
 *   begin                : Monday, Nov 22, 2004
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

if(!empty($setmodules))
{
	$filename = basename(__FILE__);
	$module['Email_Digests']['Verify Digests'] = $filename;
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$params = array('mode' => 'mode');

while(list($var, $param) = @each($params))
{
	if (!empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]))
	{
		$$var = (!empty($HTTP_POST_VARS[$param])) ? htmlspecialchars($HTTP_POST_VARS[$param]) : htmlspecialchars($HTTP_GET_VARS[$param]);
	}
	else
	{
		$$var = "";
	}
}

if ($mode == 'run')
{
	$template->set_filenames(array(
		'body' => 'admin/digests_verify_body.tpl')
	);

	$template->assign_vars(array(
		'L_VERIFY_TITLE' => $lang['Sync_attachments'],
		'L_USER_ID' => $lang['User_id'],
		'L_GROUP_ID' => $lang['Group_id'],
		'L_USERNAME' => $lang['Username'],
		'L_GROUPNAME' => $lang['Group_name'],
		'L_MESSAGE' => $lang['Message'],
		'L_OUTCOME' => $lang['Outcome'],
		'L_VERIFY_START' => $lang['Verify_start'],
		'L_VERIFY_END' => $lang['Verify_end'])
	);

	// Process users.
	$user_digest_data = $user_forum_data = array();
	$total_digest_users = $total_forum_users = 0;
	
	$sql = "SELECT user_id
		FROM  " . DIGEST_TABLE . " 
		WHERE digest_type = 0
		ORDER BY " . user_id;
	$result = $db->sql_query($sql);

	while($row = $db->sql_fetchrow($result))
	{
		$user_digest_data[] = $row;
		$total_digest_users++;
	}
	$db->sql_freeresult($result);

	for ($i = 0; $i < ($total_digest_users); $i++)
	{
		$user_id = $user_digest_data[$i]['user_id'];
		$message = $lang['Verify_found'];

		$sql = "SELECT user_id, username, user_level
			FROM " . USERS_TABLE . "
			WHERE user_id = " . $user_id;
		$result = $db->sql_query($sql);	
		$row = $db->sql_fetchrow($result);

		if ($row == '')
		{
			$message .= $lang['Verify_not_in_user'];
			$outcome = $lang['Verify_removed'];
			$username = '---';

			$sql2 = "DELETE FROM " . DIGEST_TABLE . "
				WHERE user_id = " . $user_id . "
				AND digest_type = 0";
			$result2 = $db->sql_query($sql2);

			$sql2 = "DELETE FROM " . DIGEST_FORUMS_TABLE . "
				WHERE user_id = " . $user_id;
			$result2 = $db->sql_query($sql2);
		}
		else
		{
			$message .= $lang['Verify_in_user'];
			$outcome = $lang['Verify_ok'];
			$username = $row['username'];
			$user_level = $row['user_level'];
		}
	
		$row_class = ($row_class == $theme['td_class1']) ? $theme['td_class2'] : $theme['td_class1'];
		
		$template->assign_block_vars('verify_user_row', array(
			'USER_ID' => $user_id,
			'USERNAME' => username_level_color($username, $user_level, $user_id),
			'MESSAGE' => $message,
			'OUTCOME' => $outcome,		
			'ROW_CLASS' => $row_class)
		);
	}

	// Process groups.
	$group_digest_data = $group_forum_data = array();
	$total_digest_groups = $total_forum_groups = 0;

	$sql = "SELECT user_id
		FROM  " . DIGEST_TABLE . " 
		WHERE digest_type = 1
		ORDER BY user_id";
	$result = $db->sql_query($sql);

	while($row = $db->sql_fetchrow($result))
	{
		$group_digest_data[] = $row;
		$total_digest_groups++;
	}
	$db->sql_freeresult($result);

	for ($i = 0; $i < ($total_digest_groups); $i++)
	{
		$group_id = $group_digest_data[$i]['user_id'];
		$message = $lang['Verify_found'];

		$sql = "SELECT group_id, group_name
			FROM " . GROUPS_TABLE . "
			WHERE group_id = $group_id
			AND group_digest = 1";
		$result = $db->sql_query($sql);	
		$row = $db->sql_fetchrow($result);

		if ($row == '')
		{
			$message .= $lang['Verify_not_in_group'];
			$outcome = $lang['Verify_removed'];
			$groupname = '---';

			$sql2 = "DELETE FROM " . DIGEST_TABLE . "
				WHERE user_id = '$group_id'
				AND digest_type = 1";
			$result2 = $db->sql_query($sql2);

			$sql2 = "DELETE FROM " . DIGEST_FORUMS_TABLE . "
				WHERE user_id = '$group_id'";
			$result2 = $db->sql_query($sql2);
		}
		else
		{
			$message .= $lang['Verify_in_group'];
			$outcome = $lang['Verify_ok'];
			$groupname = $row['group_name'];
		}
		
		$row_class = ($row_class == $theme['td_class1']) ? $theme['td_class2'] : $theme['td_class1'];
		
		$template->assign_block_vars('verify_group_row', array(
			'GROUP_ID' => $group_id,
			'GROUPNAME' => $groupname,
			'MESSAGE' => $message,
			'OUTCOME' => $outcome,		
			'ROW_CLASS' => $row_class)
		);
	}

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}	
else
{
	$template->set_filenames(array(
		'body' => 'admin/digests_verify.tpl')
	);

	$template->assign_vars(array(
		'L_VERIFY_TITLE' => $lang['Sync_attachments'],
		'L_VERIFY_EXPLAIN' => $lang['Digest_verify_explain'],

		'S_AUTH_ACTION' => append_sid("admin_digests_verify.$phpEx?mode=run"))
	);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}

?>
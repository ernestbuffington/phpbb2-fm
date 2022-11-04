<?php
/***************************************************************************
 *                            admin_digests_dump.php
 *                            ----------------------
 *   begin                : STuesday, May 24, 2005
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
//	$module['Email_Digests']['Data Dump'] = $filename;
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$params = array('mode' => 'mode', 'dump_code' => 'dump_code');

while(list($var, $param) = @each($params))
{
	if (!empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]))
	{
		$$var = (!empty($HTTP_POST_VARS[$param])) ? htmlspecialchars($HTTP_POST_VARS[$param]) : htmlspecialchars($HTTP_GET_VARS[$param]);
	}
	else
	{
		$$var = '';
	}
}

if ($mode == 'dump')
{
	if (md5($dump_code) == DUMP_VERIFY_CODE)
	{
		$template->set_filenames(array(
			'body' => 'admin/digests_dump_body.tpl')
		);

		$template->assign_vars(array(
			'L_DUMP_TITLE' => $lang['Digest_dump_title'],
			'L_DUMP_START' => $lang['Dump_start'],
			)
		);

		if (!is_dir($phpbb_root_path . 'dump'))
		{
			mkdir($phpbb_root_path . "dump", 0777);
		}

		$filename = $phpbb_root_path . 'dump/digest_dump_' . time() . '.txt';
		$fp = fopen($filename, 'w') or message_die(GENERAL_ERROR, $lang['Cannot_open_file'],'','',  $filename,'');	
	
		$digest_data_dump = $lang['Dump_title'] . $board_config['sitename'] . "\r";
		$digest_data_dump .= $lang['Dump_date'] . date($digest_config['digest_date_format'], time()) . "\r\r";

		fwrite($fp, $digest_data_dump);

// Dump the digest config file
		$sql = "SELECT *
			FROM " . DIGEST_CONFIG_TABLE . "
			ORDER BY config_name";

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Could not query digest config table", "", __LINE__, __FILE__, $sql);
		}

		$digest_data_dump = $lang['Dump_config'] . "\r\r";
		while($row = $db->sql_fetchrow($result))
		{
			$digest_data_dump .= $row['config_name'] . ' = ' . $row['config_value'] . "\r";
		}
		$digest_data_dump .= $lang['Dump_eof'] . "\r";
		$digest_data_dump .= '------------------------------' . "\r\r";
		fwrite($fp, $digest_data_dump);

		$template->assign_vars(array(
			'L_DUMP_CONFIG' => $lang['Dumping_config'],
			)
		);

// Dump the digest user data
		$digest_data_dump = $lang['Dump_user'] . "\r\r";
		$digest_data_dump .= $lang['Dump_user_heading'] . "\r\r";

		$sql = "SELECT d.*, u.username, u.user_lastvisit, u.user_session_time
			FROM " . DIGEST_TABLE . " d, " . USERS_TABLE . " u
			WHERE d.digest_type = 0
			AND d.user_id = u.user_id";

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain user/digest information', '', __LINE__, __FILE__, $sql);
		}

		while($row = $db->sql_fetchrow($result))
		{
			$user_id = $row['user_id'];
			$digest_id = $row['digest_id'];
			$frequency = $row['digest_frequency'];
			$active = yes_no($row['digest_activity']);
			$last_visit = ($row['user_session_time'] > $row['user_lastvisit']) ? $row['user_session_time'] : $row['user_lastvisit'];
			$format = ($row['digest_format'] == 1) ? 'HTML' : 'Text';
			$confirm_status = ($row3['digest_confirm_date'] == 0) ? '' : date($digest_day_format, $row3['digest_confirm_date']);

			switch ($row['digest_show_text'])
			{
				case -1:
					$digest_show_text = $lang['Full'];
					break;
				case 0:
					$digest_show_text = $lang['No_text'];
					break;
				case 1:
					$digest_show_text = $lang['Short'];
					break;
			}
	
			// Perform SQL query to get the user's forums
			//
			$sql = "SELECT f.*
				FROM " . DIGEST_FORUMS_TABLE . " df, " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
				WHERE f.forum_id = df.forum_id
				AND df.user_id = $user_id
				AND df.digest_id = $digest_id
				AND f.cat_id = c.cat_id
				ORDER BY c.cat_order, f.cat_id, f.forum_order";

			$result2 = $db->sql_query($sql);
			if (!($result2 = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain user\'s forum information', '', __LINE__, __FILE__, $sql);
			}

			while($row2 = $db->sql_fetchrow($result2))
			{
				$forum_name .= $row2['forum_name'];
				if ($row2['forum_digest'] == 0)
				{
					$forum_name .= ' (' . $lang['Forum_not_active'] . ')';
				}
				$forum_name .= "\r";
			}

			$forum_name = (is_null($forum_name)) ? $lang['All_Forums'] . "\r" : $forum_name;
	
			// Get confirm data
			//
			$sql = "SELECT ug.digest_confirm_date
				FROM " . USER_GROUP_TABLE . " ug, " . DIGEST_TABLE . " d
				WHERE ug.user_id = d.user_id
				AND d.user_id = $user_id
				AND d.digest_id = $digest_id";

			if (!($result3 = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain user\'s confirm information', '', __LINE__, __FILE__, $sql);
			}

			$include_forum = ($row['digest_include_forum'] == 1) ? $lang['Include'] : $lang['Exclude'];
			$confirm_status = ($row3['digest_confirm_date'] == 0) ? '0' : date("d-m-y", $row3['digest_confirm_date']);

			$digest_data_dump .= $row['username'] . ', ' . $row['digest_name'] . ', ' . date("D d-m-y H:i:s", $last_visit) . ', ' . date("D d-m-y H:i:s", $row['last_digest']) . ', ' .  get_frequency_name($row['digest_frequency']) . ', ' . 	$active . ', ' . $format . ', ' . $digest_show_text . ', ' . yes_no($row['digest_show_mine']) . ', ' .  yes_no($row['digest_new_only']) . ', ' .  yes_no($row['digest_send_on_no_messages']) . ', ' . $include_forum . ', ' . $confirm_status . ",\r" .  $forum_name . "\r";

			$forum_name = NULL;
		}

		$digest_data_dump .= $lang['Dump_eof'] . "\r";
		$digest_data_dump .= '------------------------------' . "\r\r";
		fwrite($fp, $digest_data_dump);

		$template->assign_vars(array(
			'L_DUMP_USER' => $lang['Dumping_user'],
			)
		);

// Dump the group data
		$digest_data_dump = $lang['Dump_group'] . "\r\r";
		$digest_data_dump .= $lang['Dump_group_heading'] . "\r\r";

		// Perform SQL query to get the group digest data
		//
		$sql = "SELECT DISTINCT  d.*, g.*
			FROM  " . DIGEST_TABLE . " d, " . GROUPS_TABLE . " g
			WHERE  d.user_id = g.group_id
			AND g.group_single_user = 0
			AND d.digest_type = 1";

		$result = $db->sql_query($sql);

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain group digest information', '', __LINE__, __FILE__, $sql);
		}

		while($row = $db->sql_fetchrow($result))
		{
			$digest_id = $row['digest_id'];
			$frequency = $row['digest_frequency'];
			$group_id = $row['group_id'];
			$format = ($row['digest_format'] == 1) ? 'HTML' : 'Text';
			$include_forum = ($row['digest_include_forum'] == 1) ? $lang['Include'] : $lang['Exclude'];

			switch ($row['digest_show_text'])
			{
				case -1:
					$digest_show_text = $lang['Full'];
					break;
				case 0:
					$digest_show_text = $lang['No_text'];
					break;
				case 1:
					$digest_show_text = $lang['Short'];
					break;
			}

			// Perform SQL query to get the group's forums
			//
			$sql = "SELECT f.forum_name
				FROM " . DIGEST_FORUMS_TABLE . " df, " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
				WHERE f.forum_id = df.forum_id
				AND df.digest_id = $digest_id
				AND f.cat_id = c.cat_id
				ORDER BY c.cat_order, f.cat_id, f.forum_order";

			$result2 = $db->sql_query($sql);
			if (!($result2 = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain group\'s forum information', '', __LINE__, __FILE__, $sql);
			}

			while($row2 = $db->sql_fetchrow($result2))
			{
				$forum_name .= $row2['forum_name'] . "\r";
			}
			$forum_name = (is_null($forum_name)) ? $lang['All_Forums'] . "\r" : $forum_name;

			// Get group members
			//
			$sql3 = "SELECT u.user_id, u.username, ug.*, g.group_moderator
				FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
				WHERE ug.user_id = u.user_id
				AND ug.group_id = $group_id
				AND ug.group_id = g.group_id
				ORDER BY u.username";

			if (!($result3 = $db->sql_query($sql3)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain user/group information', '', __LINE__, __FILE__, $sql3);
			}

			while($row3 = $db->sql_fetchrow($result3) )
			{
				$moderator = ($row3['user_id'] == $row3['group_moderator']) ? ' (M)' : '';
				$username_data = $row3['username'] . $moderator;
				$confirm_data = ($row3['digest_confirm_date'] == 0) ? '' : date("d-m-Y", $row3['digest_confirm_date']);
				$user_list .= $username_data . ' ' . $confirm_data . "\r";
			}

			$digest_data_dump .= $row['group_name'] . ', ' . $row['digest_name'] . ', ' . date("D d-m-y H:i:s", $row['last_digest']) . ', ' . get_frequency_name($row['digest_frequency']) . ', ' . yes_no($row['digest_activity']) . ', ' . $format . ', ' . $digest_show_text . ', ' . yes_no($row['digest_show_mine']) . ', ' . yes_no($row['digest_new_only']) . ', ' . yes_no($row['digest_send_on_no_messages']) . $include_forum . ', ' . "\r" .  $forum_name . "\r" .  $user_list . "\r\r"; 

			$forum_name = NULL;
			$user_list = NULL;
		}

		$digest_data_dump .= $lang['Dump_eof'] . "\r";
		$digest_data_dump .= '------------------------------' . "\r\r";
		fwrite($fp, $digest_data_dump);

		$template->assign_vars(array(
			'L_DUMP_GROUP' => $lang['Dumping_group'],
			)
		);

// Dump the digest log file
		$digest_data_dump = $lang['Dump_log'] . "\r\r";
		$digest_data_dump .= $lang['Dump_log_heading'] . "\r\r";
	
		$sql = "SELECT l.*, u.user_id, u.username, u.user_timezone, g.group_id, g.group_name
			FROM " . DIGEST_LOG_TABLE . " l, " . USERS_TABLE . " u, " . GROUPS_TABLE . " g
			WHERE l.user_id = u.user_id
			AND l.group_id = g.group_id
			ORDER BY l.log_time DESC, l.digest_type ASC, u.username ASC";

		$result = $db->sql_query($sql);
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain user/logging information', '', __LINE__, __FILE__, $sql);
		}

		while($row = $db->sql_fetchrow($result))
		{	
			$user_name = ($row['user_id'] == -1) ? $lang['Digest_empty'] : $row['username'];

			switch ($row['digest_type'])
			{
				case 0:
					$digest_type = $lang['Digest_user'];
					break;
				case 1:
					$digest_type = $lang['Digest_group'] . ' (' . $row['group_name'] . ')';
					break;
				case 9:
					$digest_type = $lang['Digest_empty'];
					break;
			}

			$user_timezone = $row['user_timezone'];

			$digest_data_dump .= date($digest_config['digest_date_format'], $row['log_time']) . ', ' . $lang['rt'][$row['run_type']] . ', ' . $user_name . ', ' . $digest_type . ', ' . get_frequency_name($row['digest_frequency']) . ', ' .$lang['lm'][$row['log_status']] . "\r";
		}

		$digest_data_dump .= $lang['Dump_eof'] . "\r";
		$digest_data_dump .= '------------------------------' . "\r\r";
		fwrite($fp, $digest_data_dump);

		$template->assign_vars(array(
			'L_DUMP_LOG' => $lang['Dumping_log'],
			)
		);

		$digest_data_dump = $lang['Dump_end'];
		fwrite($fp, $digest_data_dump);
		fclose($fp);

		$template->assign_vars(array(
			'L_DONE' => $lang['Done'],	
			'L_DUMP_END' => $lang['Dump_complete'],
			)
		);

		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Incorrect_dump_code'], $lang['Code_error'],'','','');
	}
}
else
{
	$template->set_filenames(array(
		'body' => 'admin/digests_dump.tpl')
	);

	$template->assign_vars(array(
		'L_DUMP_TITLE' => $lang['Digest_dump_title'],
		'L_DUMP_EXPLAIN' => $lang['Digest_dump_explain'],
		'L_DUMP_CODE' => $lang['Dump_code'],
		'L_DUMP_CODE_EXPLAIN' => $lang['Dump_code_explain'],
		'L_SUBMIT' => $lang['Digest_run_dump'],

		'S_DUMP_ACTION' => append_sid("admin_digests_dump.$phpEx?mode=dump"))
	);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}

?>
<?php
/***************************************************************************
 *                            admin_digests_confirm.php
 *                            ----------------------
 *   begin                : Monday, Nov 08, 2004
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
	$module['Email_Digests']['Confirm Digests'] = $filename;
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/emailer.'.$phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$params = array('mode' => 'mode', 'confirm_type' => 'confirm_type', 'all_groups' => 'all_groups', 'confirm_group' => 'confirm_group', 'confirm_days' => 'confirm_days');

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
	$siteURL = get_site_url();

	$confirm_date = (time() + ($confirm_days * 86400));
	$email_count = 0;
	$user_data = array();
	$total_users = 0;

	if ($confirm_type == 1)
	{
		// Select user digests
		$sql = "SELECT u.user_id, u.username, u.user_email, ug.*, d.*
			FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug, " . DIGEST_TABLE . " d 
			WHERE d.digest_type = 0
			AND	d.user_id = ug.user_id
			AND d.user_id = u.user_id
			AND ug.digest_confirm_date = 0
			GROUP BY d.user_id";

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query ' . USERS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$user_data[] = $row;
			$total_users++;
		}
	}
	elseif ($confirm_type == 2)
	{
		// Select usergroup user digests
		$sql = "SELECT ug.*, d.*, u.user_id, u.username, u.user_email, g.group_name, g.group_moderator
			FROM " . DIGEST_TABLE . " d, " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u, " . GROUPS_TABLE . " g
			WHERE d.digest_type = 1
			AND d.user_id = g.group_id
			AND u.user_id = ug.user_id
			AND	ug.group_id = g.group_id
			AND ug.digest_confirm_date = 0";

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query ' . USERS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			if ($all_groups != 1)
			{
				if ($row['group_id'] == $confirm_group)
				{
					debug_r('row',$row);
					if ($row['user_id'] != $row['group_moderator'])
					{
						$user_data[] = $row;
						$total_users++;
					}
				}
			}
			else
			{
				if ($row['user_id'] != $row['group_moderator'])
				{
					$user_data[] = $row;
					$total_users++;
				}
			}
		}
	}

	for ($i = 0 ; $i < $total_users ; $i++)
	{
		// Create the email message
		if (!(is_object($emailer)))
		{
			$emailer = new emailer($board_config['smtp_delivery']);
		}

		$user_id = $user_data[$i]['user_id'];
		$digest_id = $user_data[$i]['digest_id'];
		$digest_type = $user_data[$i]['digest_type'];
		$group_id = $user_data[$i]['group_id'];
		$digest_frequency = get_frequency_name($user_data[$i]['digest_frequency']);

		if ($user_data[$i]['digest_type'] == 1)
		{
			$digest_name = ($user_data[$i]['digest_name'] == '') ? $user_data[$i]['group_name'] : $user_data[$i]['digest_name'];
			$digest_name .= ' ' . $lang['Digest_group'];
		}
		else
		{
			$digest_name = ($user_data[$i]['digest_name'] != '') ? $user_data[$i]['digest_name'] : $digest_frequency;
		}

		if ($confirm_type == 1)
		{
			$link = $siteURL .  "digests.$phpEx?user_id=$user_id&digest_id=$digest_id&mode=confirm&digest_type=$digest_type";
		}
		else
		{
			$link = $siteURL .  "groupcp.$phpEx?g=$group_id";
		}

		$emailer->use_template('mail_digests_confirm',$userdata['user_lang']);

		$emailer->extra_headers('From: ' . $board_config['sitename'] . ' <' . $board_config['board_email'] . ">\n");

		$emailer->extra_headers('Content-Type: text/plain; charset=us-ascii');
					
		$emailer->email_address($user_data[$i]['user_email']);
		$emailer->set_subject($board_config['sitename'] . ' - ' . $lang['Digest_confirm_subject']);

		$emailer->assign_vars(array(
			'L_SALUTATION' => $lang['Digest_salutation'],
			'L_INTRODUCTION' => sprintf($lang['Digest_confirm_introduction'], $board_config['sitename'], $digest_name, $confirm_days),
	
			'SALUTATION' => $user_data[$i]['username'],			
			'DISCLAIMER' => sprintf($lang['Digest_confirm_disclaimer_text'], $board_config['sitename'], $board_config['sitename'], $board_config['sitename']),

			'LINK_COMMENT' => sprintf($lang['Digest_link_comment'], $digest_name),
			'LINK' => $link			
			)
		);

		if ($digest_config['test_mode'] == 0)
		{
			$emailer->send($html);
		}

		$emailer->reset();
		$message = sprintf($lang['Digest_confirm_progess_message'], $user_data[$i]['username'], $user_data[$i]['user_email']);
		print $message . '<br />';
		$email_count++;

		// Update the usergroup record
		$sql2 = "UPDATE " . USER_GROUP_TABLE . "
			SET digest_confirm_date = $confirm_date
			WHERE user_id = $user_id
			AND group_id = $group_id";

		if (!($result2 = $db->sql_query($sql2)))
		{
			message_die(GENERAL_ERROR, 'Could not insert or update ' . USER_GROUP_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}
	}

	$message = sprintf($lang['Digest_confirm_complete'], $email_count);
	print '<br />' . $message;
}	
else
{
	$template->set_filenames(array(
		'body' => 'admin/digests_confirm.tpl')
	);

	$template->assign_vars(array(
		'L_CONFIRM_TITLE' => $lang['Digest_confirm'],
		'L_CONFIRM_EXPLAIN' => $lang['Digest_confirm_explain'],
		'L_CONFIRM_SETTINGS' => $lang['Digest_confirm-settings'],
		'L_CONFIRM_TYPE' => $lang['Digest_type'],
		'L_USER' => $lang['Digest_user'],
		'L_GROUP' => $lang['Digest_group'],
		'L_ALL_GROUPS_CHECKED' => $lang['Digest_all_groups'],
		'L_CONFIRM_GROUP' => $lang['Digest_select_group'],
		'L_CONFIRM_DAYS' => $lang['Digest_confirm_days'],
		'L_SUBMIT' => $lang['Submit'],

		'S_GROUP_SELECT' => ug_select('confirm_group', 'confirm_group'),
		'S_AUTH_ACTION' => append_sid("admin_digests_confirm.$phpEx?mode=run"))
	);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}

?>
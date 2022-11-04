<?php
/***************************************************************************
                             mail_digest.php
                             ----------------
    begin                : Sat Oct 4 2003
    copyright            : (C) 2000 The phpBB Group
    email                : support@phpBB.com

    $Id: $

 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

// Originally Written by Mark D. Hamill, mhamill@computer.org
// Currently Authored by Indemnity83, Indemnity83@dormlife.us

// ----------------------------------------- WARNING ---------------------------------------------- //
// THIS PROGRAM SHOULD BE INVOKED TO RUN AUTOMATICALLY EVERY HOUR BY THE OPERATING SYSTEM USING AN OPERATING SYSTEM FEATURE LIKE CRONTAB. SEE BATCH_SCHEDULING.TXT
// ----------------------------------------- WARNING ---------------------------------------------- //

// Warning: this was only tested with MySQL. I don't have access to other databases. However some of the queries are copied from other places within the standard phpBB so they are likely to work. 

// Please report any bugs in the bug tracker here: http://www.dormlife.us/bugtracker/

define('IN_PHPBB', TRUE);

// If necessary edit the line below to show the absolute path to your forum e.g. '/subfolder/local/home/mysite/mysite.com/'

$phpbb_root_path = './../'; 

include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/emailer.'.$phpEx);

// Check if this is being run as a Cron job
$cron_check = $HTTP_SERVER_VARS['argc'];

$params = array('mode' => 'mode', 'pass' => 'pass');

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

if(isset($_SERVER['argv'][1]))
{
	$mode = strtolower($_SERVER['argv'][1]);
}

$cron_check = (strtolower($mode) == 'run') ? 1 : $cron_check;
$cron_check = (strtolower($mode) == 'direct') ? 2 : $cron_check;
$cron_check = (strtolower($mode) == 'urgent') ? 8 : $cron_check;
$cron_check = (strtoupper($mode) == 'HTML') ? 9 : $cron_check;

switch ($cron_check)
{
	case 1:
		$run_as_cron = TRUE;
		$log_run_type = 1;
		$new_line = "\n";
		$illegal_run = FALSE;
		$urgent_run = FALSE;
		$update = TRUE;
		break;

	case 2:
		$run_as_cron = TRUE; 
		$log_run_type = 2; 
		$new_line = "<br />"; 
		$illegal_run = FALSE; 
		$urgent_run = FALSE; 
		$update = TRUE; 
		break;

	case 8:
		$run_as_cron = TRUE;
		$log_run_type = 8;
		$new_line = "\n";
		$illegal_run = FALSE;
		$urgent_run = TRUE;
		$update = ($digest_config['run_urgent_only'] == FALSE) ? TRUE : FALSE;
		break;

	case 9:
		$run_as_cron = FALSE;
		$log_run_type = 9;
		$new_line = "<br />";
		$illegal_run = FALSE;
		$urgent_run = FALSE;
		$update = TRUE;
		break;	

	default:
		$run_as_cron = FALSE;
		$log_run_type = 0;
		$illegal_run = TRUE;
		$urgent_run = FALSE;
		$update = FALSE;
		break;	
}

if (!$run_as_cron)
{
	require($phpbb_root_path . 'admin/pagestart.' . $phpEx);
}
else
{
	include($phpbb_root_path . 'common.'.$phpEx);
}
include($phpbb_root_path . 'digests_common.'.$phpEx);

$digest_theme = setup_style($digest_config['digest_theme'], 'digest');

// Quit if this is an illegal run
if ((($digest_config['allow_direct_run'] == FALSE) && ($log_run_type == 2)) || ($illegal_run))
{
	update_log($digest_config['digest_logging'], $log_run_type, 9, -1, 998, 1, 97, $digest_config['log_days'], 0);
	exit($lang['Digest_hack']);
}

// Check direct run password is correct
if (($log_run_type == 2) && (($digest_config['allow_direct_run'] == TRUE) && ((md5($pass) != $digest_config['direct_password']) || ($pass == ''))))
{
	update_log($digest_config['digest_logging'], $log_run_type, 9, -1, 998, 1, 96, $digest_config['log_days'], 0);
	exit($lang['Password_error']);
}

$log_run_type = ($digest_config['test_mode'] == TRUE) ? 7 : $log_run_type;

// Check if this is an Urgent run
$sql = "SELECT *
	FROM ". DIGEST_CONFIG_TABLE . "
	WHERE config_name = 'urgent_run_required'";
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not query Digest config information", "", __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);
$urgent_run_required = $row['config_value'];

if(($urgent_run == TRUE) && ($urgent_run_required == FALSE))
{
	update_log($digest_config['digest_logging'], $log_run_type, 9, -1, 998, 1, 12, $digest_config['log_days'], 0);
	exit();
}

// Test to see if the SMTP server (if being used) is available. If the server is not available then do not run digests at this time.
$smtp_ok = TRUE;
if ($board_config['smtp_host'])
{
	$fp = fsockopen($board_config['smtp_host'], 25, $errno, $errstr, 20); 
	if (!$fp)
	{ 
		$smtp_ok = FALSE;
	}
	fclose($fp); 
} 

if (!$smtp_ok)
{
	update_log($digest_config['digest_logging'], $log_run_type, 9, -1, 998, 1, 98, $digest_config['log_days'], 0);
}
else
// Start the processing
{
	if (!$run_as_cron)
	{
		$template->set_filenames(array(
			'body' => 'admin/mail_digests.tpl'));
	}
 
	$disable_flag = ($digest_config['digest_disable_user'] + $digest_config['digest_disable_group']);
	$server_time = date($digest_config['digest_date_format'], time());
	$disable_message = '';

	if ($disable_flag > 0)
	{
		if ($digest_config['digest_disable_user'] == 1)
		{
			$disable_message = $lang['Digests_disabled_user'] . $new_line;
		}
		if ($digest_config['digest_disable_group'] == 2)
		{
			$disable_message .= $lang['Digests_disabled_group'];
		}
	}

	$siteURL = get_site_url();

	$digest_theme = setup_style($digest_config['digest_theme'], 'digest');

	if (!$run_as_cron)
	{
		$template->assign_vars(array(
			'L_HTML_TITLE' =>  $lang['Html_title'],
			'L_SITENAME' => $board_config['sitename'],
			'L_INFORMATION' =>  $lang['Html_info'],
			'L_DATABASE' => $lang['Html_database'],
			'L_PHPBB_VER' => $lang['Html_phpbb'],
			'L_DIGEST_MOD_VER' => $lang['Html_digest_ver'],
			'L_URL' => $lang['Html_url'],
			'L_SERVER' => $lang['Html_server'],
			'L_DISABLED_GROUPS' => $lang['Digests_disabled_group'],
			'L_DISABLED_USERS' => $lang['Digests_disabled_user'],
			'L_START' => $lang['Html_start'],
			'L_PARA_1' => $lang['Html_paragraph_1'],
			'L_GATHER' => $lang['Html_gather'],
			'L_WILL_NOT' => $lang['Html_will_not'],
			'L_PROCESS' => $lang['Html_process'],
			'L_DIGEST' => $lang['Html_digest'],
			'L_TOTAL' => $lang['Html_total'],
			'L_NO_PROCESS' => $lang['Html_no_process'],
			'L_FOUND' => $lang['Html_found'],
			'L_MARKED' => $lang['Html_marked'],
			'L_USERNAME' => $lang['Html_username'],
			'L_MINE' => $lang['Html_mine'],
			'L_EMPTY' => $lang['Html_empty'],
			'L_NEW' => $lang['Html_new'],
			'L_FORMAT' => $lang['Html_format'],
			'L_PERMISSIONS' => $lang['Html_permissions'],
			'L_OPTED' => $lang['Html_opted'],
			'L_RECEIVE' => $lang['Html_receive'],
			'L_BUILDING' => $lang['Html_building'],
			'L_BODY' => $lang['Html_body'],
			'L_DIGEST_WITH' => $lang['Html_digest_with'],
			'L_MESSAGES' => $lang['Html_messages_for'],
			'L_LAST_STEP' => $lang['Html_last_step'],
			'L_SUCCESS' => $lang['Html_success'],
			'L_COMPLETE' => $lang['Html_complete'],
			'L_EXITING' => $lang['Html_exiting'],

			'SQL_LAYER' =>  SQL_LAYER,
			'PHPBB_VER' => $board_config['version'],
			'DIGEST_MOD_VER' => $digest_config['digest_version'],
			'SITE_URL' => $siteURL,
			'SERVER_TIME' => $server_time,
			'DISABLE_MESSAGE' => $disable_message,
			'DIGEST_THEME' => $digest_theme['style_name'],
			)
		);
	}

	$cron_message = $board_config['sitename'] . $new_line . $new_line . $lang['Html_title'] . ' - (v' .  $digest_config['digest_version'] . ')' . $new_line . $lang['Digest_run_type'] . ' : ' . $mode . $new_line . $server_time . $new_line . $disabled_message . $new_line;

	// Define censored word matches
	//
	$orig_word = array();
	$replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);

	$current_time = time();
	$current_month = date('n',time());
	$user_data = array();
	$total_users = 0;

	if ($disable_flag <> 3)
	{
		if (!$run_as_cron)
		{
			$template->assign_block_vars('start_run', array(
				)
			);
		}

		$cron_message .= $lang['Html_start'] . $new_line;
		$cron_message .= $new_line . '-------------------------------------------------------------' . $new_line;

		// Gather the list of subscriptions we need to process now
		//
		$sql = "SELECT *
			FROM " . DIGEST_TABLE;

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query subscription list:', '', __LINE__, __FILE__, $sql);
		}

		while($row = $db->sql_fetchrow($result))
		{
			if (($row['digest_type'] == 0) && (($disable_flag == 0) || ($disable_flag == 2)))
			{
				// Get details for a user
				$user_id = $row['user_id'];
				$digest_id = $row['digest_id'];

				$sql2 = "SELECT u.user_id, u.user_active, u.username, u.user_lastvisit, u.user_session_time, d.*, ug.digest_confirm_date
					FROM " . USERS_TABLE . " u, " . DIGEST_TABLE . " d, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
					WHERE u.user_id = $user_id
					AND d.digest_id = $digest_id
					AND u.user_id = ug.user_id
					AND ug.group_id = g.group_id
					AND g.group_single_user = 1";

				if (!($result2 = $db->sql_query($sql2)))
				{
					message_die(GENERAL_ERROR, 'Could not query subscription list:', '', __LINE__, __FILE__, $sql2);
				}

				while ($row2 = $db->sql_fetchrow($result2))
				{
					if ($row2['digest_activity'] == FALSE)
					{
						update_log($digest_config['digest_logging'], $log_run_type, 0, $row2['user_id'], $row2['digest_frequency'], 1, 8, $digest_config['log_days'], 0);
					}
					elseif ($row2['user_active'] == FALSE)
					{
						update_log($digest_config['digest_logging'], $log_run_type, 0, $row2['user_id'], $row2['digest_frequency'], 1, 7, $digest_config['log_days'], 0);
					}
					else
					{
						$user_data[] = $row2;
						$total_users++;
					}
				}
			}
			else
			{
				if (($disable_flag == 0) || ($disable_flag == 1))
				{
					// Get details for a group
					$digest_id = $row['digest_id'];

					$sql3 = "SELECT u.user_active, u.username, u.user_lastvisit, u.user_session_time, g.*, d.*, ug.digest_confirm_date, ug.user_id
						FROM " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u, " . DIGEST_TABLE . " d, " . GROUPS_TABLE . " g
						WHERE d.digest_id = $digest_id
						AND d.user_id = g.group_id
						AND g.group_id = ug.group_id
						AND ug.user_id = u.user_id";

					if (!($result3 = $db->sql_query($sql3)))
					{
						message_die(GENERAL_ERROR, 'Could not query subscription list:', '', __LINE__, __FILE__, $sql3);
					}

					while($row3 = $db->sql_fetchrow($result3))
					{
						// Check if group digest is active
						if ($row3['digest_activity'] == FALSE)
						{
							update_log($digest_config['digest_logging'], $log_run_type, 1, $row3['user_id'], $row3['digest_frequency'],$row3['group_id'], 8, $digest_config['log_days'], 0);
						}
						// Check if the user is active
						elseif ($row3['user_active'] != FALSE)
						{
							if (($row3['user_id'] == $row3['group_moderator']) && ($row3['digest_moderator'] == 1))
							{
								$user_data[] = $row3; 
								$total_users++;
							}
							else
							{
								$user_data[] = $row3; 
								$total_users++;
							}
						}
					}
				}
			}	
		}

		// Sort the $user_data array
		foreach ($user_data as $key => $row4)
		{ 
			$username_key[$key] = strtolower($row4['username']); 
			$type_key[$key] = $row4['digest_type']; 
			$frequency_key[$key] = $row4['digest_frequency']; 
		} 
		array_multisort($type_key, SORT_ASC, SORT_REGULAR, $username_key, SORT_ASC, SORT_REGULAR, $frequency_key, SORT_ASC, SORT_REGULAR, $user_data); 

		$subscription_data = array();
		$total_subscriptions = 0;
		for ($i = 0; $i < ($total_users); $i++)
		{
			$digest_frequency = get_frequency_name($user_data[$i]['digest_frequency']);
			if ($user_data[$i]['digest_type'] == 1)
			{
				$digest_name = ($user_data[$i]['digest_name'] == '') ? $user_data[$i]['group_name'] : $user_data[$i]['digest_name'];
				$digest_name .= ' ' . $lang['Digest_group'];
				$group_log_id = $user_data[$i]['group_id'];
			}
			else
			{
				$digest_name = ($user_data[$i]['digest_name'] != '') ? $user_data[$i]['digest_name'] : $digest_frequency;
				$group_log_id = 1;
			}

			// Check if the user has confirmed their interest in the digest
			if (($user_data[$i]['digest_confirm_date'] != 0) && ($user_data[$i]['digest_confirm_date'] < $current_time))
			{
				update_log($digest_config['digest_logging'], $log_run_type, $user_data[$i]['digest_type'], $user_data[$i]['user_id'], $user_data[$i]['digest_frequency'], $group_log_id, 10, $digest_config['log_days'], 0);
				$activity_message = sprintf($lang['Html_not_confirm'], $digest_name);

				if (!$run_as_cron)
				{
					$template->assign_block_vars('activity_details', array(
						'USERNAME' => $user_data[$i]['username'],
						'ACTIVITY_MESSAGE' => $activity_message,
						)
					);
				}

				$cron_message .= $user_data[$i]['username'] . $activity_message . $new_line . $new_line;
			}
			else
			{
				if (($digest_config['check_user_activity'] == 1) && (time() > ($user_data[$i]['user_lastvisit'] + ($digest_config['activity_threshold'] * 86400))))
				{
					$group_log_id = ($user_data[$i]['digest_type'] == 0) ? 1 : $user_data[$i]['group_id'];
					update_log($digest_config['digest_logging'], $log_run_type,$user_data[$i]['digest_type'], $user_data[$i]['user_id'], $user_data[$i]['digest_frequency'], $group_log_id, 9, $digest_config['log_days'], 0);

					if (!$run_as_cron)
					{
						$template->assign_block_vars('activity_details', array(
							'USERNAME' => $user_data[$i]['username'],
							'ACTIVITY_MESSAGE' => $lang['Html_not_active'] . $digest_config['activity_threshold'] . $lang['Html_days'],
							)
						);
					}

					$cron_message .= $user_data[$i]['username'] . $lang['Html_not_active'] . $digest_config['activity_threshold'] . $lang['Html_days'] . $new_line;
				}
				else
				{
					if ($user_data['digest_new_only'] == 0)
					{
						$last_visit = $user_data[$i]['last_digest'];
					}
					else
					{
						$last_visit = ($user_data[$i]['user_session_time'] > $user_data[$i]['user_lastvisit']) ? $user_data[$i]['user_session_time'] : $user_data[$i]['user_lastvisit'];
					}

					$digest_frequency =  $user_data[$i]['digest_frequency'];
					$digest_type = strtolower($lang['df'][$digest_frequency]);

					if ($digest_frequency == 672)
					{		
						switch ($current_month)
						{
							case 3:
								$digest_frequency = 672;
								break;
							case 5:
								$digest_frequency = 720;
								break;
							case 7:
								$digest_frequency = 720;
								break;
							case 10:
								$digest_frequency = 720;
								break;
							default:
								$digest_frequency = 744;
								break;
						}
					}

					if (($urgent_run) || ((($current_time - $last_visit) > ($digest_frequency * 3600)) && ($current_time > ($user_data[$i]['last_digest'] + ($digest_frequency * 3600)))))
					{
						$option = $lang['Html_will'];
						$user_data[$i]['user_lastvisit'] = $last_visit;
						$subscription_data[] = $user_data[$i];
						$total_subscriptions++;
					}
					else
					{
						$group_log_id = ($user_data[$i]['digest_type'] == 0) ? 1 : $user_data[$i]['group_id'];
						update_log($digest_config['digest_logging'], $log_run_type, $user_data[$i]['digest_type'], $user_data[$i]['user_id'], $user_data[$i]['digest_frequency'], $group_log_id, 3, $digest_config['log_days'], 0);
						$option = $lang['Html_will_not'];
					}

					if (!$run_as_cron)
					{
						$template->assign_block_vars('user_details', array(
							'USERNAME' => $user_data[$i]['username'],
							'LAST_DIGEST_DATE' => date($digest_config['digest_date_format'], $user_data[$i]['last_digest']),
							'OPTION' => $option,
							'DIGEST_TYPE' => $digest_type,
							'LAST' => sprintf($lang['Html_last'], $digest_name),
							)
						);
					}
				}
			}
		}

		if (!$run_as_cron)
		{
			$template->assign_vars(array(
				'SUBSCRIPTIONS' => $total_subscriptions
				)
			);
		}

		$cron_message .= $new_line . $lang['Html_total'] . $total_subscriptions . $lang['Html_marked'] . $new_line;
		$cron_message .= $new_line . '-------------------------------------------------------------' . $new_line;

		if($total_subscriptions != 0)
		{
			// Gather an array of all the forums for this board
			//
			$sql = "SELECT * 
				FROM " . FORUMS_TABLE . "
				WHERE forum_id > 0
					AND forum_digest = 1
				ORDER BY forum_id";

			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not query subscription list:', '', __LINE__, __FILE__, $sql);
			}

			$forum_data = array();
			$total_forums = 0;
			while ($row = $db->sql_fetchrow($result))
			{
				$forum_data[] = $row; 
				$total_forums++;
				$forum_number = ($row['forum_id'] < 10) ? '0' . $row['forum_id'] : $row['forum_id'];
	
				if (!$run_as_cron)
				{
					$template->assign_block_vars('forum_details', array(
						'L_GATHER' => $lang['Html_gather'],
						'FORUM_NUMBER' => $forum_number,
						'FORUM_NAME' => $row['forum_name']
						)
					);
				}
			}

			if ($total_forums > 0)
			{
				if (!$run_as_cron)
				{
					$template->assign_block_vars('gather', array(
						'L_GATHER' => $lang['Html_gather'],
						)
					);
				}
			}

			$db->sql_freeresult($result);

			if (!$run_as_cron)
			{
				$template->assign_block_vars('forums', array(
					'L_TOTAL' => $lang['Html_total'],
					'L_FOUND' => $lang['Html_found'],
					'L_PROCESS_MARKED' => $lang['Html_process_marked'],
					'FORUMS' => $total_forums
					)
				);
			}

			// Process each user's subscription
			//
			for ($i = 0 ; $i < $total_subscriptions ; $i++)
			{
				// Create a userdata array for this user
				//	
				$userdata = array();
				$userdata = get_userdata($subscription_data[$i]['user_id']);
				init_userprefs($userdata, 'digest');
				$userdata['session_logged_in'] = TRUE; 
	
				// Setup some variables that are used thoughout this subscription 
				//
				switch ($subscription_data[$i]['digest_show_text'])
				{
					case 1:
						$return_chars = $digest_config['short_text_length'];
						break;
					default:
						$return_chars = $subscription_data[$i]['digest_show_text'];
						break;
				}

				$show_mine = $subscription_data[$i]['digest_show_mine'];
				$send_empty = $subscription_data[$i]['digest_send_on_no_messages'];
				$new_only = $subscription_data[$i]['digest_new_only'];
				$html = (($subscription_data[$i]['digest_format'] == DIGEST_HTML) ? TRUE: FALSE);
				$user_lastvisit = $subscription_data[$i]['user_lastvisit'];
				$user_id = $subscription_data[$i]['user_id'];
				$digest_id = $subscription_data[$i]['digest_id'];
				$last_digest = $subscription_data[$i]['last_digest'];
				$include_user_forums = $subscription_data[$i]['digest_include_forum'];

				// Obtain a list of topic ids which contain posts made since user last visited
				//
				// Get a from time for the query, we should pull everything newer than this time
				if ($new_only)
				{		
					if ($user_lastvisit > $last_digest) 
					{ 
						$post_time = " AND p.post_time > " . $user_lastvisit; 
						$last_time = $lang['Html_last_visit_used'];
					} 
					else 
					{ 
						$post_time = " AND p.post_time > " . $last_digest;
						$last_time = $lang['Html_last_digest_time_used'];
					}

					$last = $lang['Html_last_visit'] . (date($digest_config['digest_date_format'], $user_lastvisit)) .  $lang['Html_last_digest'] . (date($digest_config['digest_date_format'], $last_digest)) . $lang['Html_only_new'];
				}
				else
				{
					$last = $lang['Html_all_messages'] . (date($digest_config['digest_date_format'], $last_digest)) .  $lang['Html_pulled'];
					$post_time = " AND p.post_time > " . $last_digest;
				}
	
				// Find which forums are visible for this user
				//
				$is_auth_ary = array();
				$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);
		
				$authed_forums = array();
				for($j = 0; $j < $total_forums; $j++)
				{
					if ($is_auth_ary[$forum_data[$j]['forum_id']]['auth_read'])
					{
						$authed_forums[$j] = $forum_data[$j]['forum_id'];		
					}
				}

				// Get the list of forums the user wants to see digests from
				//
				$subscribed_forums = array();
				$sql2 = "SELECT forum_id
					FROM " . DIGEST_FORUMS_TABLE . "
					WHERE user_id = $user_id
					AND digest_id = $digest_id";

				if (!($result2 = $db->sql_query($sql2)))
				{
					message_die(GENERAL_ERROR, 'Unable to retrieve list of subscribed forums', '', __LINE__, __FILE__, $sql2);
				}

				while ($row2 = $db->sql_fetchrow($result2)) 
				{
					$subscribed_forums[] = $row2['forum_id']; 
				}

				// Remove any forums that have been marked as excluded
				if ($include_user_forums == 0)
				{
					$subscribed_forums = array_diff($authed_forums, $subscribed_forums);
				}
	
				// If there are subscribed forums, we only want to see messages for these forums.
				if (count($subscribed_forums) == 0 || $subscribed_forums[0] == ALL_FORUMS) 
				{ 
					// The subscribed forums table is empty, or contains 999, by design this means the user wants all auth forums.
					$list_forums = $lang['Html_all'];
					$query_forums = $authed_forums;
				}
				elseif (count($subscribed_forums) > 0)
				{
					$list_forums = implode(",", $subscribed_forums);
					$query_forums = array_intersect($authed_forums, $subscribed_forums);
				}
				else
				{	
					$query_forums = array();
				}

				if (count($query_forums) > 0)
				{
					$auth_forums = " AND p.forum_id IN (" . implode(",", $query_forums) . ")";
	
					// Setup a filter to hide their own posts if that is what they want
					$filter_users = '';
					$mine = '';
					if (!$show_mine)
					{
						$mine = $lang['Html_hide_own'] . $userdata['username'] . $lang['Html_omitted'];
						$filter_users = " AND p.poster_id <> " . $user_id;
					}

					$urgent_filter = " AND p.urgent_post <> 9";
					if (($urgent_run) && ($digest_config['run_urgent_only'] == TRUE))
					{
						$urgent_filter = " AND p.urgent_post = 1";
					}

					$sql3 = "SELECT c.cat_order, f.forum_order, f.forum_name, t.topic_views, t.topic_replies, t.topic_title, t.topic_poster, u.username, p.* , pt.* 
						FROM " . CATEGORIES_TABLE . " c, " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " .  POSTS_TABLE . " p, " . POSTS_TEXT_TABLE . " pt  
						WHERE c.cat_id = f.cat_id 
						AND f.forum_digest = 1
						AND f.forum_id = t.forum_id 
						AND t.topic_id = p.topic_id
						AND p.poster_id = u.user_id 
						AND p.post_id = pt.post_id
						$filter_users
						$post_time
						$auth_forums
						$urgent_filter
						ORDER BY c.cat_order, f.forum_order, t.topic_id, p.post_time ASC"; 	

					if (!($result3 = $db->sql_query($sql3)))
					{
						message_die(GENERAL_ERROR, 'Could not gather digest data:', '', __LINE__, __FILE__, $sql3);
					}

					$topic_data = array();
					while($row3 = $db->sql_fetchrow($result3))
					{
						$topic_data[] = $row3;
					}

					$db->sql_freeresult($result3);

					// Let us build the data for PM's
					//
					if (($digest_config['pm_notify'] == 1) || ($digest_config['pm_display'] == 1))
					{
						$sql = "SELECT  count(*) AS pmcount
							FROM " . PRIVMSGS_TABLE . " 
							WHERE privmsgs_to_userid = '$user_id'
							AND privmsgs_type = 1";

						if (!($result = $db->sql_query($sql)))
						{
							message_die(GENERAL_ERROR, 'Could not query subscription list:', '', __LINE__, __FILE__, $sql);
						}

						$row = $db->sql_fetchrow($result);
						$pm_count = $row['pmcount'];

						if ($html) 
						{
							$pm_notify_message = '<br /><table class="bodyline" width="100%" cellspacing="1" cellpadding="2"><tr><th class="thLeft" height="28"><a href="' . $siteURL . 'privmsg.'.$phpEx . '?folder=inbox">' . $lang['Private_Messages'] . '</a></th></td><tr><td height="28" class="row1"><span class="name">';
						}
						else
						{
							$pm_notify_message = $lang['Private_Messages'] . "\r\n";
						}

						if ($pm_count > 0)
						{
							$pm_notify_message .= sprintf($lang['New_pms'], $row['pmcount']);
						}
						else
						{
							$pm_notify_message .= $lang['No_new_pm'];
						}

						if ($html)
						{
							$pm_notify_message .= '</span></td></tr>';
						}
						else
						{
							$pm_notify_message .= "\r\n\r\n";
						}

						if ($digest_config['pm_display'] == 1 && ($pm_count > 0))
						{
							$sql = "SELECT  p.* , pt.*, u.user_id, u.username
								FROM " . PRIVMSGS_TABLE . " p, " . PRIVMSGS_TEXT_TABLE . " pt, " . USERS_TABLE . " u
								WHERE p.privmsgs_id = pt.privmsgs_text_id
								AND p.privmsgs_to_userid = '$user_id'
								AND privmsgs_type = 1
								AND u.user_id = p.privmsgs_from_userid ";

							if (!($result = $db->sql_query($sql)))
							{
								message_die(GENERAL_ERROR, 'Could not query private message list:', '', __LINE__, __FILE__, $sql);
							}

							while($row = $db->sql_fetchrow($result))
							{
								if ($html)
								{
									$pm_notify_message .= '<tr><td class="catHead">' . $lang["From"] . ' : ' . $row["username"] . $lang['On'] . date($digest_config['digest_date_format'], $row["privmsgs_date"]) . '<br />' . $lang["Subject"] . ' : ' . $row["privmsgs_subject"] . '</td></tr>';
								}
								else
								{
									$pm_notify_message .= $lang['From'] . " : " . $row['username'] . "\r\n" . $lang['Date'] . " : " . date($digest_config['digest_date_format'], $row['privmsgs_date']) . "\r\n" . $lang['Subject'] . " : " . $row["privmsgs_subject"] . "\r\n";
								}

								if ($return_chars != -1)
								{
									$message = substr($row['privmsgs_text'], 0, $return_chars) . '   ' . 
									sprintf($lang['PM_read_more'], "<a href='" . $siteURL . 'privmsg.' . $phpEx . '?folder=inbox' . "'>", "</a>");
								}
								else
								{
									$message = $row['privmsgs_text'];
								}

								if ($html)
								{
									$pm_notify_message .= '<tr><td class="row1">&nbsp;<span class="gen">' . $message . '</span></td></tr>';
								}
								else
								{
									$pm_notify_message .= $message . "\r\n\r\n";
								}
							}
						}

						if ($html)
						{
							$pm_notify_message .= '</span></table>';
						}
					}
					else
					{
						$pm_notify_message = '';
					}

					// Okay, let's build the digest
					//
					// The emailer class does not have the equivalent of the assign_block_vars operation, so the entire digest must be placed inside a variable.
		
					if (!($total_topics = count($topic_data)))
					{
						$msg = $lang['Html_no_new_topics'];
					}
					else
					{
						if ($html) 
						{
							$msg = "<table class='bodyline' width='100%' border='0' cellspacing='1' cellpadding='2'><tr><th width='15%' height='25' class='thLeft' nowrap='nowrap'>" . $lang['Author'] . "</th><th width='85%' class='thRight' nowrap='nowrap'>" . $lang['Message'] . "</th></tr>";
						}
						else
						{
							$msg = '';
						}
					}
			
					$last_topic = -1;				
					for ($j = 0; $j < $total_topics; $j++)
					{	
						// If the topic_id changes, put a new divider
						//
						if ($last_topic != $topic_data[$j]['topic_title'])
						{
							if ($html) 
							{			
								if ($topic_data[$j]['topic_status'] == TOPIC_LOCKED)
								{
									$folder_image = $siteURL . '/' . $images['folder_locked'];
								}
								else if ($topic_data[$j]['topic_type'] == POST_ANNOUNCE)
								{
									$folder_image = $siteURL . '/' . $images['folder_announce'];
								}
								else if ($topic_data[$j]['topic_type'] == POST_STICKY)
								{
									$folder_image = $siteURL . '/' . $images['folder_sticky'];
								}
								else
								{
									$folder_image = $siteURL . '/' . $images['folder'];
								}

								$msg .= "<tr>";
								$msg .= "<td colspan='2' height='28' class='row1'><span class='name'><img src='" . $folder_image . "' align='absmiddle'>&nbsp; " . $lang['Topic'] . ":&nbsp;<a href=" . $siteURL . 'viewtopic.' . $phpEx . '?' . POST_TOPIC_URL . '=' . $topic_data[$j]['topic_id'] . ">" . $topic_data[$j]['topic_title']. "</a></span></td>";
								$msg .= "</tr>";
							}
							else
							{
								$msg .= "\r\n____________________________________________________________________________\r\n"; 
								$msg .= "\r\n<< " . strtoupper($lang['Topic']) . " - " .  $topic_data[$j]['topic_title'] . ', ' . $siteURL . 'viewtopic.' . $phpEx. '?' . POST_TOPIC_URL . '=' . $topic_data[$j]['topic_id'] . " >>\r\n\r\n"; 
								$msg .= "____________________________________________________________________________\r\n";         
								$msg .= "\r\n"; 
							}
							$last_topic = $topic_data[$j]['topic_title'];
						}
						else
						{
							if ($html)
							{
								$msg .= "<tr><td class='spaceRow' colspan='2' height='1'><img src='" . $siteURL . "/" . $current_template_images . "/spacer.gif' alt='' width='1' height='1' /></td></tr>";
							}
						}
		
						$message = $topic_data[$j]['post_text'];
						$topic_title = $topic_data[$j]['topic_title'];
						$post_date = create_date($userdata['user_dateformat'], $topic_data[$j]['post_time'], $board_config['board_timezone']);
						$post_ip = ($digest_config['show_ip'] == TRUE) ? $post_ip = $lang['Html_ip'] . decode_ip($topic_data[$j]['poster_ip']) : $post_ip = '';
			
						if (($return_chars != 0) && $html)
						{
							$bbcode_uid = $topic_data[$j]['bbcode_uid'];
	
							// If the board has HTML off but the post has HTML on then we process it, else leave it alone

							if ($return_chars == -1)
							{			
								$message = strip_tags($message);
								$message = preg_replace("/\[.*?:$bbcode_uid:?.*?\]/si", '', $message);
								$message = preg_replace('/\[url\]|\[\/url\]/si', '', $message);
								$message = (strlen($message) > $return_chars) ? substr($message, 0, $return_chars) . ' ...' : $message;
							}
							else
							{
								if (!$board_config['allow_html'])
								{
									if ($topic_data[$i]['enable_html'])
									{
										$message = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\\2&gt;', $message);
									}
								}
								
								if ($bbcode_uid != '')
								{								
									init_userprefs($userdata);
									$message = ($board_config['allow_bbcode']) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
								}
	
								$message = make_clickable($message);
							}

							if ($board_config['allow_smilies'] && $topic_data[$j]['enable_smilies'])
							{
								$message = smilies_pass($message, $siteURL);
							}
	
							if (count($orig_word))
							{
								$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
								$post_subject = ($topic_data[$j]['post_subject'] != "") ? preg_replace($orig_word, $replacement_word, $topic_data[$j]['post_subject']) : $topic_title;
								$message = preg_replace($orig_word, $replacement_word, $message);
							}
							else
							{
								$post_subject = ($topic_data[$j]['post_subject'] != '') ? $topic_data[$j]['post_subject'] : $topic_title;
							}
	
							if ($board_config['allow_smilies'] && $searchset[$i]['enable_smilies'])
							{
								$message = smilies_pass($message);
							}
	
							$message = str_replace("\n", '<br />', $message);
						}
						else if(!$html)
						{
							$message = strip_tags($message);
							$message = preg_replace("/\[.*?:$bbcode_uid:?.*?\]/si", '', $message);
							$message = preg_replace('/\[url\]|\[\/url\]/si', '', $message);
							$message = (strlen($message) > $return_chars) ? substr($message, 0, $return_chars) . ' ...' : $message; 
						}
						else
						{
							$message = '';
						}
		
						if (($topic_data[$j]['poster_id'] == -1) && ($topic_data[$j]['post_username']))
						{
							$poster_name =  $topic_data[$j]['post_username'];
						}
						else
						{
							$poster_name = $topic_data[$j]['username'];
						}
						
						if ($html)
						{			
							$msg .= "<tr>";  
							$msg .= "<td width='15%' align='left' valign='top' class='row1' rowspan='2'><span class='name'><b><a href='" . $siteURL . "profile." . $phpEx . '?mode=viewprofile&' . POST_USERS_URL . "=" . $topic_data[$j]['poster_id'] . "'>" . $poster_name . "</a></b></span><br /><br /><span class='postdetails'>" . $lang['Replies'] . ": <b>" . $topic_data[$j]['topic_replies'] . "</b><br />" . $lang['Views'] . ": <b>" . $topic_data[$j]['topic_views'] . "</b></span><br /></td>";

							$msg .= "<td width='85%' valign='top' class='row1'><img src='" . $siteURL . '/' . $images['icon_minipost'] . "' width='12' height='9' border='0' /><span class='postdetails'>" . $lang['Forum'] . ":&nbsp;<b><a href='" . $siteURL . 'viewforum.' . $phpEx. '?' . POST_FORUM_URL . '=' . $topic_data[$j]['forum_id'] . "' class='postdetails'>" . $topic_data[$j]['forum_name'] . "</a></b>&nbsp; &nbsp;" . $lang['Posted'] . ": " . $post_date . ' ' . date("T",$topic_data[$j]['post_time']) . "&nbsp; &nbsp;" . $lang['Subject'] . ": <b><a href=" . $siteURL . 'viewtopic.' . $phpEx . '?' . POST_TOPIC_URL . '=' . $topic_data[$j]['topic_id'] . " class='postdetails'>" . $topic_data[$j]['topic_title']. "</a></b>&nbsp;&nbsp;" . $post_ip . "</span></td>";
							$msg .= "</tr>";
							$msg .= "<tr>";
							$msg .= "<td valign='top' class='row1'><span class='gen'>" . $message . "&nbsp;" . 
							sprintf($lang['Digest_read_more'], "<a href='" . $siteURL . 'viewtopic.' . $phpEx . '?' . POST_POST_URL . '=' . $topic_data[$j]['post_id'] . "'>", "</a>", "<a href='" . $siteURL . 'posting.' . $phpEx . '?mode=reply&' . POST_TOPIC_URL . '=' . $topic_data[$j]['topic_id'] . "'>", "</a>") . "&nbsp;</span></td>";
							$msg .= "</tr>";
						}
						else
						{
							$msg .= $lang['Poster' ] ." " . $poster_name . " " .  $lang['Posted'] . " " . $post_date . " " . date("T",$topic_data[$j]['post_time']) . ", " . $siteURL . "viewtopic." . $phpEx . "?" . POST_POST_URL . "=" . $topic_data[$j]['post_id'] . "#" . $topic_data[$j]['post_id'] . "\r\n";  
							$msg .= $lang['Message'] . ": " . preg_replace('/\[\S+\]/', "", $message) . "\r\n"; 
							$msg .= "\r\n------------------------------\r\n"; 
							$msg .= "\r\n"; 
						}
					} // ... topic
	
					if ($html)
					{
						$msg .= "</table>";
					}
	
					$cron_message .= $lang['Html_digest_with'] . $total_topics . $lang['Html_messages_for'] . $userdata['username'] . $new_line;
	
					// Send the email if there are messages or if user selected to send email anyhow
					if ($total_topics > 0 || $send_empty) 
					{	
						if ($total_topics <= 0)
						{
							$msg = $lang['No_new_posts'];
							$log_status = 1;
						}
						if (!(is_object($emailer)))
						{
							$emailer = new emailer($board_config['smtp_delivery']);
						}

						if ($html) 
						{
							$emailer->use_template('mail_digests_html',$userdata['user_lang']);
					
							// Set up style
							//
							if ($digest_config['override_theme'] == TRUE)
							{
								$theme = setup_style($digest_config['digest_theme'], 'digest');
							}
							else
							{
								$theme = ($userdata['user_style'] > 0) ? $theme = setup_style($userdata['user_style'], 'digest') : $theme = setup_style($digest_config['digest_theme'], 'digest');
							}

							switch ($digest_config['theme_type'])
							{
								case 0:
									$style_sheet = file_get_contents($phpbb_root_path . 'templates/' . $theme['style_name'] . '/' . $theme['style_name'] . '.css');
									break;

								case 1:
									$style_sheet = '';
									break;

								case 2:
									$style_sheet = get_header_style($phpbb_root_path, $theme['style_name']);
									break;
							}
						}
						else 
						{
							$emailer->use_template('mail_digests_text',$userdata['user_lang']);
						}

						$emailer->extra_headers('From: ' . $board_config['sitename'] . ' <' . $board_config['board_email'] . ">\n");

						if ($html) 
						{
							$emailer->extra_headers('MIME-Version: 1.0');
							$emailer->extra_headers('Content-type: text/html; charset = ' . $lang['ENCODING']);
			
							// Add the links to the introduction
							$introduction = sprintf($lang['Digest_introduction_html'], '<a href="' . $siteURL . $digest_config['home_page'] . '.' . $phpEx . '">', $board_config['sitename'], '</a>');

							if ($urgent_run == TRUE)
							{
								$introduction .= "<br />" . $lang['Urgent_reply'];
							}
				
							// Add the links to the disclaimer
							$disclaimer = sprintf($lang['Digest_disclaimer_html'], $board_config['sitename'], $board_config['sitename'], '<a href="' . $siteURL . $lang['Html_faq'] . $phpEx . '">', '</a>', $board_config['sitename'], '<a href="' . $siteURL . $lang['Html_digests'] . $phpEx . '">', '</a>', '<a href="mailto:' . $board_config['board_email']. '">', '</a>', $digest_config['digest_version']);
						}
						else
						{
							$emailer->extra_headers('Content-Type: text/plain; charset=us-ascii');
			
							$introduction = sprintf($lang['Digest_introduction_text'], $board_config['sitename']);
						
							if ($urgent_run == TRUE)
							{
								$introduction .= "\n" . $lang['Urgent_reply'];
							}
				
							$disclaimer = sprintf($lang['Digest_disclaimer_text'], $board_config['sitename'], $board_config['sitename'], $board_config['sitename'], $digest_config['digest_version']);
						}

						$mail_subject = ($digest_config['digest_subject'] == '') ? $board_config['sitename'] : $digest_config['digest_subject'];
		
						$emailer->email_address($userdata['user_email']);
						if ($subscription_data[$i]['digest_name'] == '')
						{
							$digest_subject_name = $lang['df'][$subscription_data[$i]['digest_frequency']] . ' ' . $lang['Digest_subject_line'];
						}
						else
						{
							$digest_subject_name = $subscription_data[$i]['digest_name'];
						}
						$emailer->set_subject($mail_subject . ' - ' . $digest_subject_name);

						// Create the stats message
						//
						if ($digest_config['show_stats'] == 1)
						{
							$stats_format = ($subscription_data[$i]['digest_format'] == 1) ? $lang['Digest_html'] : $lang['Digest_text'];	
							$stats_message_text = ($subscription_data[$i]['digest_show_text']) ? $lang['Yes'] : $lang['No'];			
							$stats_my_messages = ($subscription_data[$i]['digest_show_mine']) ? $lang['Yes'] : $lang['No'];			
							$stats_frequency = get_frequency_name($subscription_data[$i]['digest_frequency']);			
							$stats_new_messages = ($subscription_data[$i]['digest_new_only']) ? $lang['Yes'] : $lang['No'];			
							$stats_send_digest = ($subscription_data[$i]['digest_send_on_no_messages']) ? $lang['Yes'] : $lang['No'];
							switch ($subscription_data[$i]['digest_show_text'])
							{
								case -1:
									$stats_text_length = $lang['Full'];
									break;
								case 0:
									$stats_text_length = $lang['No_text'];
									break;
								case 1:
									$stats_text_length = $digest_config['short_text_length'];
									break;
							}

							if ($html)
							{
								$stats_message = '<br /><table class="bodyline" width="100%" cellspacing="1" cellpadding="2"><tr><th class="thright" colspan="2" height="28"><a href="' . $siteURL . 'digests.'.$phpEx . '">' . $lang["Digest_options"] . '</a></span></td></tr> <tr><td class="row2" nowrap width="80%">&nbsp;<span class="gen">' . $lang["Digest_format"] . '</span>&nbsp;</td><td class="row1" width="20%">&nbsp;<span class="gen">' . $stats_format . '</span>&nbsp;</td></tr> <tr><td class="row2" nowrap>&nbsp;<span class="gen">' . $lang["Digest_show_message_text"] . '</span>&nbsp;</td><td class="row1">&nbsp;<span class="gen">' . $stats_message_text . '</span>&nbsp;</td></tr> <tr><td class="row2" nowrap>&nbsp;<span class="gen">' . $lang["Digest_show_my_messages"] . '</span>&nbsp;</td><td class="row1">&nbsp;<span class="gen">' . $stats_my_messages . '</span>&nbsp;</td></tr> <tr><td class="row2" nowrap>&nbsp;<span class="gen">' . $lang["Digest_frequency"] . '</span>&nbsp;</td><td class="row1">&nbsp;<span class="gen">' . $stats_frequency . '</span>&nbsp;</td></tr> <tr><td class="row2" nowrap>&nbsp;<span class="gen">' . $lang["Digest_new_only"] . '</span>&nbsp;</td><td class="row1">&nbsp;<span class="gen">' . $stats_new_messages . '</span>&nbsp;</td></tr> <tr><td class="row2" nowrap>&nbsp;<span class="gen">' . $lang["Digest_send_empty"] . '</span>&nbsp;</td><td class="row1">&nbsp;<span class="gen">' . $stats_send_digest . '</span>&nbsp;</td></tr><tr><td class="row2" nowrap>&nbsp;<span class="gen">' . $lang["Digest_message_size"] . '</span>&nbsp;</td><td class="row1">&nbsp;<span class="gen">' . $stats_text_length . '</span>&nbsp;</td></tr></table><br />';
							}
							else // Text message
							{
								$stats_message = $lang['Digest_options'] . "\r\n" . $lang["Digest_format"] . " : " . $stats_format . "\r\n" . $lang["Digest_show_message_text"] . " : " . $stats_message_text . "\r\n"  . $lang["Digest_show_my_messages"] . " : " . $stats_my_messages . "\r\n"  . $lang["Digest_frequency"] . " : " . $stats_frequency . "\r\n"  . $lang["Digest_new_only"] . " : " . $stats_new_messages . "\r\n" . $lang["Digest_send_empty"] . " : " .  $stats_send_digest . "\r\n" .  $lang["Digest_message_size"] . " : " . $stats_text_length;
							}
						}
						else // No stats
						{
							$stats_message = '';
						}

						$emailer->assign_vars(array(
							'L_SITENAME' => $board_config['sitename'],
							'L_SALUTATION' => $lang['Digest_salutation'],
							'L_INTRODUCTION' => $introduction,
			
							'U_SUPPORT' => $digest_config['Digest_support'],
	
							'SALUTATION' => $userdata['username'],			
							'DIGEST_CONTENT' => $msg,
							'DISCLAIMER' => $disclaimer,

							'BOARD_URL' => $siteURL,		
							'LINK' => $link_tag,
							'PM' => $pm_notify_message,
							'STATS' => $stats_message,
									
							'STYLE_SHEET' => $style_sheet,
							'T_THEME' => $theme['style_name'],

							'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
							'T_BODY_BGCOLOR' => '#'.$theme['body_bgcolor'],
							'T_BODY_TEXT' => '#'.$theme['body_text'],
							'T_BODY_LINK' => '#'.$theme['body_link'],
							'T_BODY_VLINK' => '#'.$theme['body_vlink'],
							'T_BODY_ALINK' => '#'.$theme['body_alink'],
							'T_BODY_HLINK' => '#'.$theme['body_hlink'],
							'T_TR_COLOR1' => '#'.$theme['tr_color1'],
							'T_TR_COLOR2' => '#'.$theme['tr_color2'],
							'T_TR_COLOR3' => '#'.$theme['tr_color3'],
							'T_TR_CLASS1' => $theme['tr_class1'],
							'T_TR_CLASS2' => $theme['tr_class2'],
							'T_TR_CLASS3' => $theme['tr_class3'],
							'T_TH_COLOR1' => '#'.$theme['th_color1'],
							'T_TH_COLOR2' => '#'.$theme['th_color2'],
							'T_TH_COLOR3' => '#'.$theme['th_color3'],
							'T_TH_CLASS1' => $siteURL . $images['cellpic1'],
							'T_TH_CLASS2' => $siteURL . $images['cellpic3'],
							'T_TH_CLASS3' => $siteURL . $images['cellpic2'],
							'T_TD_COLOR1' => '#'.$theme['td_color1'],
							'T_TD_COLOR2' => '#'.$theme['td_color2'],
							'T_TD_COLOR3' => '#'.$theme['td_color3'],
							'T_TD_CLASS1' => $theme['td_class1'],
							'T_TD_CLASS2' => $theme['td_class2'],
							'T_TD_CLASS3' => $theme['td_class3'],
							'T_FONTFACE1' => $theme['fontface1'],
							'T_FONTFACE2' => $theme['fontface2'],
							'T_FONTFACE3' => $theme['fontface3'],
							'T_FONTSIZE1' => $theme['fontsize1'],
							'T_FONTSIZE2' => $theme['fontsize2'],
							'T_FONTSIZE3' => $theme['fontsize3'],
							'T_FONTCOLOR1' => '#'.$theme['fontcolor1'],
							'T_FONTCOLOR2' => '#'.$theme['fontcolor2'],
							'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
							'T_SPAN_CLASS1' => $theme['span_class1'],
							'T_SPAN_CLASS2' => $theme['span_class2'],
							'T_SPAN_CLASS3' => $theme['span_class3'],
							)
						);					

						if ($digest_config['test_mode'] == 0)
						{
							$emailer->send($html);
						}
						$emailer->reset();
						$sent_message = $lang['Html_sent_to'] . $userdata['user_email'];
						$cron_message .= $lang['Html_sent_to'] . $userdata['user_email'] . $new_line . $new_line;	
						$log_status = 0;
					}
					else
					{
						$sent_message = $lang['Html_empty_digests'];
						$cron_message .= $lang['Html_empty_digests'] . $new_line;
						$log_status = 2;
					}

					if (!$run_as_cron)
					{
						$template->assign_block_vars('user_data', array(
							'USERNAME' => $subscription_data[$i]['username'],
							'MINE' => ($show_mine == TRUE ? 'Yes' : 'No'),
							'EMPTY' => ($send_empty == TRUE ? 'Yes' : 'No'),
							'NEW' => ($new_only == TRUE ? 'Yes' : 'No'),
							'FORMAT' => ($html == TRUE ? 'HTML' : 'Text'),
							'LAST_DIGEST' => $last,	
							'AUTH_FORUMS' => implode(",", $authed_forums),
							'LIST_FORUMS' => $list_forums,
							'QUERY_FORUMS' => implode(",", $query_forums),
							'SHOW_MINE' => $mine,
							'HTML' => ($html ? 'HTML' : 'Text'),
							'TOTAL_TOPICS' => $total_topics,
							'SENT_MESSAGE' => $sent_message,
							)
						);
					}

					// Update the digests table with the time this digest was sent minus 5 miniutes as a buffer.
					//
					if ($subscription_data[$i]['digest_type'] == 1)
					{
						$user_id = $subscription_data[$i]['group_id'];
						$group_id = $subscription_data[$i]['group_id'];
					}
					else
					{
						$group_id = 1;
					}

					$update_time = (time() - 100);

					if ($digest_config['test_mode'] == FALSE)
					{
						if ($urgent_run == TRUE)
						{
							$sql = "UPDATE " . POSTS_TABLE . "
								SET urgent_post = 9 
								WHERE urgent_post = 1";

							if (!($db->sql_query($sql)))
							{
								message_die(GENERAL_ERROR, 'Could not update posts table:', '', __LINE__, __FILE__, $sql);
							}
						}

						if ($update == TRUE)
						{
							$sql = "UPDATE " . DIGEST_TABLE . " 
								SET last_digest = $update_time
								WHERE user_id = $user_id
								AND digest_id = $digest_id";

							if (!($db->sql_query($sql)))
							{
								message_die(GENERAL_ERROR, 'Could not update digests table:', '', __LINE__, __FILE__, $sql);
							}
						}

						$sql = "UPDATE ". DIGEST_CONFIG_TABLE . "
							SET config_value = 0
							WHERE config_name = 'urgent_run_required'";

						if(!$result = $db->sql_query($sql))
						{
							message_die(GENERAL_ERROR, "Could not update Digest config table", "", __LINE__, __FILE__, $sql);
						}
					}

					update_log($digest_config['digest_logging'], $log_run_type, $subscription_data[$i]['digest_type'], $subscription_data[$i]['user_id'], $subscription_data[$i]['digest_frequency'], $group_id, 4, $digest_config['log_days'], $total_topics);
	
					$cron_message .= $new_line . $lang['Html_success'] . $new_line;
					$cron_message .= $new_line . '-------------------------------------------------------------' . $new_line;
				}
				else
				{
					update_log($digest_config['digest_logging'], $log_run_type, 9, -1, 998, 1, 6, $digest_config['log_days'], 0);
				}
			}
		}
		else
		{
			update_log($digest_config['digest_logging'], $log_run_type, 9, -1, 998, 1, 99, $digest_config['log_days'], 0);
		}
	}

	if (!$finish)
	{
		$cron_message .= $new_line . $lang['Html_complete'] . $new_line;
		$cron_message .= $new_line . $lang['Html_exiting'];
	}

	if (!$run_as_cron)
	{
		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	elseif ($digest_config['supress_cron_output'] == FALSE)
	{
		print $cron_message;
	}
}
?>
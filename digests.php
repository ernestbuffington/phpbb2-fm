<?php
/** 
*
* @package phpBB2
* @version $Id: digests.php Indemnity83 Exp $
* @copyright (c) Mark D. Hamill & Indemnity83
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', TRUE);

$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$params = array('digest_frequency' => 'digest_frequency', 'digest_format' => 'digest_format', 'digest_show_text' => 'digest_show_text', 'digest_show_mine' => 'digest_show_mine', 'digest_new_only' => 'digest_new_only', 'digest_send_on_no_messages' => 'digest_send_on_no_messages', 'last_hour' => 'digest_send_hour', 'last_day' => 'digest_send_day', 'last_month' => 'digest_send_month', 'last_year' => 'digest_send_year', 'digest_all_forums' => 'digest_all_forums', 'user_id' => 'user_id', 'digest_id' => 'digest_id', 'create_new' => 'create_new', 'last_digest' => 'last_digest', 'digest_type' => 'digest_type', 'digest_activity' => 'digest_activity', 'digest_moderator' => 'digest_moderator', 'digest_delete' => 'digest_delete', 'digest_include_forum' => 'digest_include_forum');

while(list($var, $param) = @each($params))
{
	if (!empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]))
	{
		$$var = (!empty($HTTP_POST_VARS[$param])) ? htmlspecialchars($HTTP_POST_VARS[$param]) : htmlspecialchars($HTTP_GET_VARS[$param]);
	}
	else
	{
		$$var = '0';
	}
}

$params = array('mode' => 'mode', 'digest_name' => 'digest_name');

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

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_DIGEST);
init_userprefs($userdata);
	
if ($user_id == 0)
{
	$user_id = $userdata['user_id'];
}
//
// End session management
//

	
$page_title = $lang['Digests'];

// Set variables for Admin access
if (($mode == 'admin') || ($mode == 'group'))
{	
	if ($digest_type == 0)
	{
		$sql = "SELECT u.username
			FROM " . USERS_TABLE . " u
			WHERE u.user_id = $user_id";
		$row = $db->sql_fetchrow($db->sql_query($sql));
		$page_title .= ' : ' . $row['username'];
	}
	else
	{
		$sql = "SELECT g.group_name
			FROM " . GROUPS_TABLE . " g
			WHERE g.group_id = $user_id";
		$row = $db->sql_fetchrow($db->sql_query($sql));
		$page_title .= ' : ' . $row['group_name'] . $lang['Usergroup'];
	}

	$userdata['session_logged_in'] = TRUE;
	$gen_simple_header = ($mode == 'admin') ? TRUE : FALSE;
}
	
if (($HTTP_SERVER_VARS['REQUEST_METHOD'] == 'GET') || ($mode == 'confirm'))
{ 
	if ($mode == 'confirm')
	{
		$mode = '';
		$confirm_mode = TRUE;
	}

	if ($userdata['session_logged_in'])
	{
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);
		
		$template->set_filenames(array(
			'digests' => 'profile_digest_body.tpl')
		);

		// Get current subscription data for this user, if any
		$sql = "SELECT *
			FROM " . DIGEST_TABLE . " 
			WHERE user_id = $user_id
				AND digest_id = $digest_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not get count from '. DIGEST_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		$create_new = ($db->sql_numrows($result) > 0) ? 0 : 1;

		if ($create_new == TRUE)
		{
			// Default values if no digest subscription for user
			$digest_name = '';
			$digest_activity = 1;
			$digest_frequency = $digest_config['default_frequency'];
			$digest_format = $digest_config['default_format'];
			$digest_show_text = $digest_config['default_text_length_type'];
			$digest_show_mine = $digest_config['default_show_mine'];
			$digest_new_only = $digest_config['default_new_only'];
			$digest_send_on_no_messages = $digest_config['default_send_on_no_messages'];
			$digest_include_forum = 1;
		}
		else 
		{
			// Read current digest options into local variables, because we have one inherent connection
			$sql = "SELECT * 
				FROM " . DIGEST_TABLE . "
				WHERE user_id = $user_id
					AND digest_id = $digest_id";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not get options from ' . DIGEST_TABLE . 'table', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$digest_id = $row['digest_id'];
			$digest_name = $row['digest_name'];
			$digest_type = $row['digest_type'];
			$digest_activity = $row['digest_activity'];
			$digest_frequency = $row['digest_frequency'];
			$last_digest = $row['last_digest'];
			$digest_format = $row['digest_format'];
			$digest_show_text = $row['digest_show_text'];
			$digest_show_mine = $row['digest_show_mine'];
			$digest_new_only = $row['digest_new_only'];
			$digest_send_on_no_messages = $row['digest_send_on_no_messages'];
			$digest_moderator = $row['digest_moderator'];
			$digest_include_forum = $row['digest_include_forum'];
		} 

		$db->sql_freeresult($result);
		
		// Get current subscribed forums for this user, if any
		$sql = "SELECT count(*) AS count 
			FROM " . DIGEST_FORUMS_TABLE . "
			WHERE user_id = $user_id
				AND digest_id = $digest_id
				AND forum_id > 0
				AND forum_id <> " . ALL_FORUMS;
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not get count from ' . DIGEST_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		$all_forums_new = ($row['count'] == 0) ? TRUE : FALSE;

		$db->sql_freeresult ($result);

		$last_hour = date('H', $last_digest);
		$last_day = date('d', $last_digest);
		$last_month = date('m', $last_digest);
		$last_year = date('Y', $last_digest);

		$delete_explain = ($digest_type == 0) ? $lang['Digest-delete_explain_user'] : $lang['Digest-delete_explain_group'];

		if ($mode == 'admin')
		{
			$template->assign_block_vars('allow_admin', array());
		}

		if (($digest_type == 1) && (($mode == 'admin') || ($mode == 'group')))
		{
			$template->assign_block_vars('allow_moderator', array());
		}

		if ($digest_config['allow_exclude'] == TRUE)
		{
			$template->assign_block_vars('allow_exclude_forums', array());
		}
		else
		{
			$digest_include_forum = TRUE;
		}

		// Fill template with current digest options for user
		$template->assign_vars(array(
			'L_DIGEST_DELETE' => $lang['Digest_delete'],
			'L_DIGEST_DELETE_EXPLAIN' => $delete_explain,
			'L_DIGEST_NAME' => $lang['Digest_name'],
			'L_DIGEST_NAME_EXPLAIN' => $lang['Digest_name_explain'],
			'L_DIGEST_FREQUENCY' => $lang['Digest_frequency'],
			'L_DIGEST_FREQUENCY_DESC' => $lang['Digest_frequency_desc'],
			'L_NONE' => $lang['Digest_none'],
			'L_ACTIVITY' => $lang['Digest_activity'],
			'L_HOURLY' => $lang['Digest_hourly'],
			'L_DAILY' => $lang['Digest_daily'],
			'L_WEEKLY' => $lang['Digest_weekly'],
			'L_DIGEST_TIME' => $lang['Digest_time'],
			'L_DIGEST_MODERATOR' => $lang['Digest_moderator'],
			'L_FORMAT' => $lang['Digest_format'],
			'L_FORMAT_DESC' => $lang['Digest_format_desc'],
			'L_HTML' => $lang['Digest_html'],
			'L_TEXT' => $lang['Digest_text'],
			'L_SHOW_TEXT' => $lang['Digest_show_message_text'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
			'L_SHOW_MINE' => $lang['Digest_show_my_messages'],
			'L_NEW_ONLY' => $lang['Digest_new_only'],
			'L_NEW_ONLY_DESC' => $lang['Digest_new_only_desc'],
			'L_SEND_ON_NO_MESSAGES' => $lang['Digest_send_empty'],
			'L_SEND_HOUR' => $lang['Digest_send'],
			'L_SEND_DATE' => $lang['Digest_date'],
			'L_DIGEST_INCLUDE' => $lang['Digest_include_forums'],
			'L_DIGEST_INCLUDE_EXPLAIN' => $lang['Digest_include_forums_explain'],
			'L_INCLUDE' => $lang['Include'],
			'L_EXCLUDE' => $lang['Exclude'],
			'L_FORUM_SELECTION' => $lang['Digest_select_forums'],
			'L_ALL_SUBSCRIBED_FORUMS' => $lang['Digest_all_forums'],
			'L_DIGEST_ACCEPT' => $lang['Digest_accept'],
			'L_SUBMIT' => $lang['Digest_submit_text'],
			'L_RESET' => $lang['Reset'],
			'L_FULL' => $lang['Full'],
			'L_SHORT' => $lang['Short'],
			'L_NO_TEXT' => $lang['No_text'],

			'S_HTML' => DIGEST_HTML,
			'S_TEXT' => DIGEST_TEXT,
			'S_TRUE' => TRUE, 
			'S_FALSE' => FALSE,
			'S_DIGEST_FREQUENCY' => df_select($digest_frequency, $mode),
			'S_POST_ACTION' => append_sid("digests.$phpEx?user_id=$user_id&mode=$mode"),
			'DIGEST_SEND_HOUR' => $last_hour,
			'DIGEST_SEND_DAY' => $last_day,
			'DIGEST_SEND_MONTH' => $last_month,
			'DIGEST_SEND_YEAR' => $last_year,
			'DIGEST_NAME' => $digest_name,
		
			'NO_FORUMS_SELECTED' => $lang['Digest_no_forums_selected'],
			'DIGEST_EXPLANATION' => $lang['Digest_explanation'],
			'DIGEST_ID' => $digest_id,
			'LAST_DIGEST' => $last_digest,
			'DIGEST_CREATE_NEW_VALUE' => ($create_new) ? '1' : '0',
			'DIGEST_TYPE' => $digest_type,
			'DIGEST_MODERATOR' => $digest_moderator,

			'DIGEST_MODERATOR_YES_CHECKED' => ($digest_moderator == TRUE) ? 'checked="checked"' : '',			
			'DIGEST_MODERATOR_NO_CHECKED' => ($digest_moderator == FALSE) ? 'checked="checked"' : '',
			'ACTIVITY_YES_CHECKED' => ($digest_activity == TRUE) ? 'checked="checked"' : '',			
			'ACTIVITY_NO_CHECKED' => ($digest_activity == FALSE) ? 'checked="checked"' : '',
			'HTML_CHECKED' => ($digest_format == DIGEST_HTML) ? 'checked="checked"' : '',			
			'TEXT_CHECKED' => ($digest_format == DIGEST_TEXT) ? 'checked="checked"' : '',			
			'SHOW_TEXT_FULL' => ($digest_show_text == FULL_TEXT) ? 'checked="checked"' : '',			
			'SHOW_TEXT_SHORT' => ($digest_show_text == SHORT_TEXT) ? 'checked="checked"' : '',
			'SHOW_TEXT_NONE' => ($digest_show_text == NO_TEXT) ? 'checked="checked"' : '',
			'SHOW_MINE_YES_CHECKED' => ($digest_show_mine == TRUE) ? 'checked="checked"' : '',
			'SHOW_MINE_NO_CHECKED' => ($digest_show_mine == FALSE) ? 'checked="checked"' : '',			
			'NEW_ONLY_YES_CHECKED' => ($digest_new_only == TRUE) ? 'checked="checked"' : '',
			'NEW_ONLY_NO_CHECKED' => ($digest_new_only == FALSE) ? 'checked="checked"' : '',			
			'SEND_ON_NO_MESSAGES_YES_CHECKED' => ($digest_send_on_no_messages == TRUE) ? 'checked="checked"' : '',
			'SEND_ON_NO_MESSAGES_NO_CHECKED' => ($digest_send_on_no_messages == FALSE) ? 'checked="checked"' : '',	
			'INCLUDE_FORUM_YES_CHECKED' => ($digest_include_forum == TRUE) ? 'checked="checked"' : '',
			'INCLUDE_FORUM_NO_CHECKED' => ($digest_include_forum == FALSE) ? 'checked="checked"' : '',
			'ALL_FORUMS_CHECKED' => ($create_new || ((!($create_new)) && $all_forums_new)) ? 'checked="checked"' : '')
		);
			
		// Start the code to grab the viewable forum list
		$sql = "SELECT f.*, c.*
			FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
			WHERE c.cat_id = f.cat_id
				AND f.forum_id > 0
				AND f.forum_digest = 1
			ORDER BY c.cat_order, f.cat_id, f.forum_order";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
		}

		$forum_data = array();
		while($row = $db->sql_fetchrow($result))
		{
			$forum_data[] = $row;
		}
		$db->sql_freeresult($result);

		if (!($total_forums = count($forum_data)))
		{
			message_die(GENERAL_MESSAGE, $lang['No_forums']);
		}

		// Find which forums are visible for this user
		$userdata['session_logged_in'] = TRUE;
		$is_auth_ary = array(); 
		$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);

		// Now print the forums on the web page, each forum being a checkbox with appropriate label
		for ($i = 0; $i < $total_forums; $i++) 
		{
			if ($is_auth_ary[$forum_data[$i]['forum_id']]['auth_read'])
			{
				$forum_description = ($digest_config['show_forum_description'] == TRUE) ? $forum_data[$i]['forum_desc'] : '';
				$user_forums_array[$i] = $forum_data[$i]['forum_id'];
				// Is this forum currently subscribed? If so it needs to be checkmarked
				if (!($all_forums_new)) 
				{
					$sql = "SELECT count(*) AS count 
						FROM " . DIGEST_FORUMS_TABLE . "
						WHERE forum_id = " . $forum_data[$i]['forum_id'] . "
							AND user_id = $user_id
							AND digest_id = $digest_id";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not get count from ' . DIGEST_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
					}

					$row = $db->sql_fetchrow($result);

					$forum_checked = ($row['count'] == 0) ? FALSE : TRUE;
					
					$db->sql_freeresult ($result);
				}
				else  
				{
					$forum_checked = TRUE;               	
				}
				
				$template->assign_block_vars('forums', array(
					'FORUM_NAME' => 'forum_' . $forum_data[$i]['forum_id'],
					'CHECKED' => ($forum_checked || $create_new) ? 'checked="checked"' : '',
					'FORUM_LABEL' => $forum_data[$i]['forum_name'],
					'FORUM_DESCRIPTION' => $forum_description)
				);
			}
		}

		if ($mode != 'admin')
		{
			include($phpbb_root_path . 'profile_menu.'.$phpEx);
		}

		$template->pparse('digests');
	}
	else 
	{
		// User is not logged in, redirect to the login page with paramaters to have it return here
		if ($confirm_mode == TRUE)
		{
			redirect(append_sid("login.$phpEx?redirect=digests.$phpEx&user_id=$user_id&digest_id=$digest_id&mode=confirm&digest_type=$digest_type", TRUE));
		}
		else
		{
			redirect(append_sid("login.$phpEx?redirect=digests.$phpEx", TRUE));
		}
	}
}
else 
{
	// The user has submitted the form.
	// This logic takes the necessary action to update the database and gives an appropriate confirmation message.

	// Create an array of any existing digests for the user
	$sql = "SELECT *
		FROM " . DIGEST_TABLE . "
		WHERE user_id = $user_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query ' . DIGEST_TABLE . ' table', '', __LINE__, __FILE__, $sql);
	}

	// Check the include/exclude status for any changes to existing digests
	$change_include_status = FALSE;
	while($row = $db->sql_fetchrow($result))
	{
		$old_digest_array[] = $row;
		if (($row['digest_include_forum'] != $digest_include_forum) || ($row['digest_include_forum'] == 0))
		{
			$change_include_status = TRUE;
		}
	}

	if ($create_new == TRUE)	
	{
		$digest_id = $db->sql_nextid(); 
	}

	if ($change_include_status == TRUE)
	{
		// First remove all individual forum subscriptions
		$sql = "DELETE 
			FROM " . DIGEST_FORUMS_TABLE . "
			WHERE user_id = $user_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete from ' . DIGEST_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}
    
		// Then remove subscription itself
		$sql = "DELETE 
			FROM " . DIGEST_TABLE . "
			WHERE user_id = $user_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete from ' . DIGEST_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		} 
		$create_new = TRUE;
	}

	if ($digest_delete == TRUE)
	{
		// The user no longer wants a digest
		// First remove all individual forum subscriptions
		$sql = "DELETE 
			FROM " . DIGEST_FORUMS_TABLE . "
			WHERE user_id = $user_id
				AND digest_id = $digest_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete from ' . DIGEST_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}
    
		// Then remove subscription itself
		$sql = "DELETE 
			FROM " . DIGEST_TABLE . "
			WHERE user_id = $user_id
				AND digest_id = $digest_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete from ' . DIGEST_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}
			$update_type = 'unsubscribe'; 
	}

	if ($digest_delete != 1)
	{
		// In all other cases a digest has to be either created or updated

		// Check if the user already has a digest for this frequency	
		if (count($old_digest_array) > 0)
		{
			$delete_old_digest = '';
			for ($i = 0; $i < count($old_digest_array); $i++)
			{
				if (($old_digest_array[$i]['digest_frequency'] == $digest_frequency) && ($old_digest_array[$i]['digest_id'] != $digest_id))
				{
					$delete_old_digest = $old_digest_array[$i]['digest_id'];
				}
			}
		}
		
		// Create or update the subscription
		if ($create_new == TRUE)// New digest
		{
			if ($digest_config['use_system_time'] == 0)
			{
				$hour =  $digest_config['run_time'];
				$day = date('d', time());
				$month = date('n', time());
				$year = date('Y', time());

				switch ($digest_frequency)
				{
					case 24: // Daily
						$day = date('d', time()) - 1;
						if ($day == 0)
						{
							$day = '28';
						}
						break;
					case 168: // Week
						$day = date('d', time()) - 1;
						if ($day == 0)
						{
							$day = '28';
						}
						break;
					case 672: // Month
						$day = $digest_config['monthly_day'];
						$month = date('n', time()) - 1;
						if ($month == 0)
						{
							$month = '12';
							$year = $year - 1;
						}
						break;
					default:
						$hour = date('G', time());
						break;
				}
				$last_digest = mktime($hour, 0, 0, $month, $day, $year);
			}
			else
			{
				$last_digest = time();
			}

			$digest_include_forum = ($digest_config['allow_exclude'] == TRUE) ?  $digest_include_forum : 1;

			$sql = "INSERT INTO " . DIGEST_TABLE . " (digest_name, user_id, digest_type, digest_frequency, last_digest, digest_format, digest_show_text, digest_show_mine, digest_new_only, digest_send_on_no_messages, digest_moderator, digest_include_forum) 
				VALUES ('" . str_replace("\'", "''", $digest_name) . "', '$user_id', '$digest_type', '$digest_frequency', '$last_digest', '$digest_format', '$digest_show_text', '$digest_show_mine', '$digest_new_only', '$digest_send_on_no_messages', '$digest_moderator', '$digest_include_forum')";
			$update_type = 'create';
		}
		else
		{		
			$last_hour = ($last_hour == 0) ? date('H', $last_digest) : $last_hour;
			$last_day = ($last_day == 0) ? date('d', $last_digest) : $last_day;
			$last_month = ($last_month == 0) ? date('m', $last_digest) : $last_month;
			$last_year = ($last_year == 0) ? date('Y', $last_digest) : $last_year;
			$last_digest = mktime($last_hour, 0, 0, $last_month, $last_day, $last_year);

			$sql = "UPDATE " . DIGEST_TABLE . " SET
				digest_name = '" . str_replace("\'", "''", $digest_name) . "',
				digest_type = $digest_type,
				digest_activity = $digest_activity,
				digest_frequency = $digest_frequency,
				digest_format = $digest_format, 
				last_digest = $last_digest,
				digest_show_text = $digest_show_text,
				digest_show_mine = $digest_show_mine,
				digest_new_only = $digest_new_only,
				digest_send_on_no_messages = $digest_send_on_no_messages,
				digest_moderator = $digest_moderator,
				digest_include_forum = $digest_include_forum
				WHERE user_id = $user_id
				AND digest_id = $digest_id";
			$update_type = 'modify';
		}

		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not insert or update ' . DIGEST_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		if ($create_new == 1)	
		{
			$digest_id = $db->sql_nextid();
		} 

		// Reset confirm date
		$sql = "UPDATE " . USER_GROUP_TABLE . "
			SET digest_confirm_date = 0
			WHERE user_id = $user_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not update ' . USER_GROUP_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		// This code handles making a forum unique to a digest. The logic used is that whatever the user enters for the latest forums it will override whatever is already there if there are duplicates present.

		// Delete any digest that has the same frequency as the new one.

		if ($delete_old_digest != '')
		{			
			$sql = "DELETE
				FROM " . DIGEST_TABLE . "
				WHERE digest_id = $delete_old_digest";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not delete digest data', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE
				FROM " . DIGEST_FORUMS_TABLE . "
				WHERE digest_id = $delete_old_digest";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not delete forums data', '', __LINE__, __FILE__, $sql);
			}
		}

		// Get current subscribed forums for this user.
		$sql = "SELECT DISTINCT forum_id, digest_id, COUNT(forum_id) AS forumcount 
			FROM " . DIGEST_FORUMS_TABLE . "
			WHERE user_id = $user_id 
			GROUP BY forum_id, digest_id";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$forum_rowset[] = $row;
			$total_forums += $row['forumcount'];
		}

		$was_all_forums = FALSE;
		for ($i = 0; $i < $total_forums; $i++)
		{	
			// Create an array of all of the original fourms for the user
			if ($forum_rowset[$i]['forum_id'] == ALL_FORUMS)
			{
				$was_all_forums = TRUE;

				// Start the code to grab the viewable forum list		
				$sql = "SELECT f.*, c.*
					FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
					WHERE c.cat_id = f.cat_id
						AND f.forum_digest = 1
					ORDER BY c.cat_order, f.cat_id, f.forum_order";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
				}

				$forum_data = array();
				while($row = $db->sql_fetchrow($result))
				{
					$forum_data[] = $row;
				}

				$db->sql_freeresult($result);

				if (!($total_forums = count($forum_data)))
				{
					message_die(GENERAL_MESSAGE, $lang['No_forums']);
				}

				// Find which forums are visible for this user
				$is_auth_ary = array();
				$is_auth_ary = auth(AUTH_READ, AUTH_LIST_ALL, $userdata);

				for ($i = 0; $i < $total_forums; $i++) 
				{
					if ($is_auth_ary[$forum_data[$i]['forum_id']]['auth_read'])
					{
						$used_forums_array[$i] = $forum_data[$i]['forum_id'];
					}
				}
			}
			else
			{
				$used_forums_array[$i] = $forum_rowset[$i]['forum_id'];
			}
		}

		$sql = "SELECT DISTINCT digest_id, COUNT(digest_id) AS digestcount 
			FROM " . DIGEST_FORUMS_TABLE . "
			WHERE user_id = $user_id 
			GROUP BY digest_id
			ORDER BY digest_id";
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$digest_rowset[] = $row;
			$total_digests += $row['digestcount'];
		}

		for ($i = 0; $i < $total_digests; $i++)
		{
			// Create an array of all of the original digests for the user
			$used_digest_array[$i] = $digest_rowset[$i]['digest_id'];
		}

		// Get the requested forums
		$i = 0;
		if ($HTTP_POST_VARS['all_forums'] == 'on')
		{
			$new_forums_array[$i] = ALL_FORUMS;
		}
		else
		{
			foreach ($HTTP_POST_VARS as $key => $value) 
			{
				if (substr($key, 0, 6) == 'forum_') 
				{
					$new_forums_array[$i] =substr($key,6);
					$i++;
				}
			}
		}

		// Remove from previous forums
		if ($HTTP_POST_VARS['all_forums'] == 'on')
		{
			$sql = "DELETE
				FROM " . DIGEST_FORUMS_TABLE . "
				WHERE user_id = $user_id
					AND forum_id <> " . ALL_FORUMS;
			$result = $db->sql_query($sql);
		}

		if ($was_all_forums == TRUE)
		{
			$sql = "SELECT *
				FROM " . DIGEST_FORUMS_TABLE . "
				WHERE user_id = $user_id
					AND forum_id = " . ALL_FORUMS;
			$result = $db->sql_query($sql);

			$row = $db->sql_fetchrow($result);

			$old_digest_id = $row['digest_id'];

			$sql = "DELETE
				FROM " . DIGEST_FORUMS_TABLE . "
				WHERE user_id = $user_id
					AND forum_id = " . ALL_FORUMS;
			$result = $db->sql_query($sql);

			$removed_forums_array = array_diff($used_forums_array, $new_forums_array);

			for ($i = 0; $i < $total_forums; $i++)
			{
				if ($removed_forums_array[$i] != '')
				{
					$sql = "INSERT INTO " . DIGEST_FORUMS_TABLE . " (user_id, forum_id, digest_id)
						VALUES ($user_id, $removed_forums_array[$i], $old_digest_id)";
					$result = $db->sql_query($sql);
				}
			}
		}
		else
		{
			for ($i = 0; $i < count($used_forums_array); $i++)
			{
				for ($j = 0; $j < count($new_forums_array); $j++)
				{
					if ($used_forums_array[$i] == $new_forums_array[$j])
					{
						$sql = "DELETE 
							FROM " . DIGEST_FORUMS_TABLE . "
							WHERE user_id = $user_id
								AND forum_id = $used_forums_array[$i]";
						$result = $db->sql_query($sql);
					}
				}
			}
		}

		// Next, if there are any individual forum subscriptions, remove the old ones and create the new ones
		$sql = "DELETE 
			FROM " . DIGEST_FORUMS_TABLE . "
			WHERE user_id = $user_id
				AND digest_id = $digest_id";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not delete from ' . DIGEST_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
		}

		if ($HTTP_POST_VARS['all_forums'] !== 'on') 
		{
			foreach ($HTTP_POST_VARS as $key => $value) 
			{
				if (substr($key, 0, 6) == 'forum_') 
				{
					$sql = "INSERT INTO " . DIGEST_FORUMS_TABLE . " (user_id, forum_id, digest_id)
						VALUES ($user_id, " . substr($key,6) . ", $digest_id)";
					if (!($result = $db->sql_query($sql)))
         			{
         				message_die(GENERAL_ERROR, 'Could not insert into ' . DIGEST_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
         			}
				}
			}
		}
		else
		{
			$sql = "INSERT INTO " . DIGEST_FORUMS_TABLE . " (user_id, forum_id, digest_id)
				VALUES ($user_id, " . ALL_FORUMS . ", $digest_id)";
			if (!($result = $db->sql_query($sql)))
         	{
         		message_die(GENERAL_ERROR, 'Could not insert into ' . DIGEST_FORUMS_TABLE . ' table', '', __LINE__, __FILE__, $sql);
         	}
		}

		$sql = "SELECT *
			FROM " . DIGEST_TABLE . "
			WHERE user_id = $user_id";
		$result = $db->sql_query($sql);
		
		while ($row = $db->sql_fetchrow($result))
		{
			$digest_id = $row['digest_id'];

			$sql2 = "SELECT *
				FROM " . DIGEST_FORUMS_TABLE . "
				WHERE user_id = $user_id
					AND digest_id = $digest_id";
			$result2 = $db->sql_query($sql2);

			if ($db->sql_numrows($result2) == 0)
			{
				$sql3 = "DELETE
					FROM " . DIGEST_TABLE . "
					WHERE user_id = $user_id
						AND digest_id = $digest_id";
				$result3 = $db->sql_query($sql3);
			}
		}
	}

	// Show appropriate confirmation message
	if ($update_type == 'unsubscribe')
	{
		$message = $lang['Digest_unsubscribe'];
	}
	elseif ($update_type == 'create')
	{
		$message = $lang['Digest_create'];
	}
	else
	{
		$message = $lang['Digest_modify'];
	}
	message_die(GENERAL_MESSAGE, $message);
}

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
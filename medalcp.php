<?php
/** 
*
* @package phpBB2
* @version $Id: medalcp.php,v 2.0.2 2003/01/05 02:36:00 ycl6 Exp $
* @copyright (c) 2003 ycl6
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
$userdata = session_pagestart($user_ip, PAGE_MEDALS);
init_userprefs($userdata);
//
// End session management
//

$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
$script_name = ( $script_name != '' ) ? $script_name . '/medals.'.$phpEx : 'medals.'.$phpEx;
$server_name = trim($board_config['server_name']);
$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';
$server_url = $server_protocol . $server_name . $server_port . $script_name;

if ( isset($HTTP_GET_VARS[POST_MEDAL_URL]) || isset($HTTP_POST_VARS[POST_MEDAL_URL]) )
{
	$medal_id = ( isset($HTTP_POST_VARS[POST_MEDAL_URL]) ) ? intval($HTTP_POST_VARS[POST_MEDAL_URL]) : intval($HTTP_GET_VARS[POST_MEDAL_URL]);
}
else
{
	$medal_id = '';
}

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}

// session id check
if ($sid == '' || $sid != $userdata['session_id'])
{
	$message = $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

	message_die(GENERAL_ERROR, $message);
}

$is_moderator = FALSE;

$sql = "SELECT *
	FROM " . MEDAL_TABLE . " 
	WHERE medal_id =" . $medal_id;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain medal information', '', __LINE__, __FILE__, $sql);
}

if ( $medal_info = $db->sql_fetchrow($result) )
{
	$is_moderator = ($userdata['user_level'] != ADMIN) ? check_medal_mod($medal_id) : TRUE;
	
	// Start auth check
	if ( !$is_moderator )
	{
		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("index.$phpEx") . '">')
		);
			
		$message = $lang['Not_medal_moderator'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	// End Auth Check

	//
	// Handle Additions and Removals
	//
	if ( isset($HTTP_POST_VARS['submit']) )
	{
		if ( !$userdata['session_logged_in'] )
		{
			redirect(append_sid("login.$phpEx?redirect=medals.$phpEx&" . POST_MEDAL_URL . "=$medal_id", true));
		}

		if (!isset($HTTP_POST_VARS['sid']) || $HTTP_POST_VARS['sid'] != $userdata['session_id'])
		{
			message_die(GENERAL_ERROR, 'Invalid_session');
		}

		if ( !empty($HTTP_POST_VARS['username']) )
		{
			$username = phpbb_clean_username($HTTP_POST_VARS['username']);

			$sql = "SELECT user_id, user_email, user_lang
				FROM " . USERS_TABLE . " 
				WHERE username = '" . str_replace("\'", "''", $username) . "'";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Could not get user information", $lang['Error'], __LINE__, __FILE__, $sql);
			}

			if ( !($user = $db->sql_fetchrow($result)) )
			{
				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=$medal_id&amp;sid=".$userdata['session_id']) . '">')
				);
				$message = $lang['Could_not_add_user'] . '<br /><br />' . sprintf($lang['Click_return_medal'], '<a href="' . append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=$medal_id&amp;sid=".$userdata['session_id']."") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
	
				message_die(GENERAL_MESSAGE, $message);
			}

			$issue_reason = ( isset($HTTP_POST_VARS['issue_reason']) ) ? trim(htmlspecialchars($HTTP_POST_VARS['issue_reason'])) : "";

			if ( $user['user_id'] == ANONYMOUS )
			{
				$template->assign_vars(array(
					'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=$medal_id&amp;sid=".$userdata['session_id']) . '">')
				);

				$message = $lang['Could_not_anonymous_user'] . '<br /><br />' . sprintf($lang['Click_return_medal'], '<a href="' . append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=$medal_id&amp;sid=".$userdata['session_id']."") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}

			$sql = "INSERT INTO " . MEDAL_USER_TABLE . " (medal_id, user_id, issue_reason, issue_time) 
				VALUES ($medal_id, " . $user['user_id'] . ", '$issue_reason', " . time() . ")";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not add medal to user', '', __LINE__, __FILE__, $sql);
			}

			// Get the medal name
			// Email the user and tell them they're receiving the medal
			$medal_sql = "SELECT medal_name 
				FROM " . MEDAL_TABLE . " 
				WHERE medal_id = $medal_id";
			if ( !($result = $db->sql_query($medal_sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get medal information', '', __LINE__, __FILE__, $medal_sql);
			}

			$medal_name_row = $db->sql_fetchrow($result);
		
			$medal_name = $medal_name_row['medal_name'];

			include($phpbb_root_path . 'includes/emailer.'.$phpEx);
			$emailer = new emailer($board_config['smtp_delivery']);
			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);

			$emailer->use_template('medal_added', stripslashes($user['user_lang']));
			$emailer->email_address($user['user_email']);
			$emailer->set_subject($lang['Medal_added']);

			$emailer->assign_vars(array(
				'SITENAME' => $board_config['sitename'], 
				'MEDAL_NAME' => $medal_name,
				'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '', 

				'U_MEDAL' => $server_url)
			);
			$emailer->send();
			$emailer->reset();
			
			$message = $lang['Medal_update_sucessful'] . '<br /><br />' . sprintf($lang['Click_return_medal'], '<a href="' . append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=$medal_id&amp;sid=".$userdata['session_id']) . '">', '</a>');
		}
		else if ( !empty($HTTP_POST_VARS['unmedal_user']) )
		{
			$where_sql = '';

			if ( isset($HTTP_POST_VARS['unmedal_user']) )
			{
				$user_list = $HTTP_POST_VARS['unmedal_user'];

				for($i = 0; $i < count($user_list); $i++)
				{
					if ( $user_list[$i] != -1 )
					{	
						$where_sql .= ( ( $where_sql != '' ) ? ', ' : '' ) . intval($user_list[$i]);
					}
				}
			}
			if ( $where_sql != '' )
			{
				$sql = "DELETE FROM " . MEDAL_USER_TABLE . "
					WHERE issue_id IN ($where_sql)";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't delete medal info from user", "", __LINE__, __FILE__, $sql);
				}
			}
			$message = $lang['Medal_update_sucessful'] . '<br /><br />' . sprintf($lang['Click_return_medal'], '<a href="' . append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=$medal_id&amp;sid=".$userdata['session_id']."") . '">', '</a>');
		}
		else if (empty($HTTP_POST_VARS['username']) || empty($HTTP_POST_VARS['unmedal_user']))
		{
			message_die(GENERAL_MESSAGE, $lang['No_username_specified'] );
		}

		message_die(GENERAL_MESSAGE, $message);
	}

	//
	// Get medal details
	//
	$sql = "SELECT *
		FROM " . MEDAL_TABLE . "
		WHERE medal_id =" . $medal_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting medal information', '', __LINE__, __FILE__, $sql);
	}

	if ( !($medal_info = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_MESSAGE, $lang['Medal_not_exist']); 
	}

	// Medal Moderators
	$sql = "SELECT u.username, u.user_id, u.user_level
		FROM " . USERS_TABLE . " u, " . MEDAL_MOD_TABLE . " mm
		WHERE mm.medal_id = $medal_id
			AND u.user_id = mm.user_id
		ORDER BY u.username";
	if ( !($result = $db->sql_query($sql)) ) 
	{
		message_die(GENERAL_ERROR, 'Error getting medal moderator information', '', __LINE__, __FILE__, $sql);
	}

	$medal_moderator = '';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$medal_moderator .= ( $medal_moderator != '' ) ? ', ' . '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" class="genmed">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>' : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" class="genmed">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>';
	}

	//
	// Get user information for this medal
	//
	$sql = "SELECT u.username, u.user_id, u.user_level, mu.issue_id, mu.issue_time
		FROM " . USERS_TABLE . " u, " . MEDAL_USER_TABLE . " mu
		WHERE mu.medal_id = $medal_id
			AND u.user_id = mu.user_id
		ORDER BY u.username"; 
	if ($result = $db->sql_query($sql))
	{
		$medal_member = '';
		$rowset = array();
		while ($row = $db->sql_fetchrow($result))
		{
			$rowset[$row['user_id']]['username'] = $row['username'];
		}
		$db->sql_freeresult($result);
	}

	while (list($user_id, $medal) = @each($rowset))
	{
		$medal_member .= ( $medal_member != '' ) ? ', ' . '<a href="' . append_sid("medalcp_edit.$phpEx?" . POST_MEDAL_URL . "=$medal_id&amp;" . POST_USERS_URL . "=" . $user_id . "&amp;sid=" . $userdata['session_id']) . '" class="genmed">' . username_level_color($medal['username'], $medal['user_level'], $user_id) . '</a>' : '<a href="' . append_sid("medalcp_edit.$phpEx?" . POST_MEDAL_URL . "=$medal_id&amp;" . POST_USERS_URL . "=" . $user_id."&amp;sid=" . $userdata['session_id']) . '" class="genmed">' . username_level_color($medal['username'], $medal['user_level'], $user_id) . '</a>';
	}

	$sql = "SELECT u.username, u.user_id, mu.issue_id, mu.issue_time
		FROM " . USERS_TABLE . " u, " . MEDAL_USER_TABLE . " mu
		WHERE mu.medal_id = $medal_id
			AND u.user_id = mu.user_id
		ORDER BY u.username"; 
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting user list for medal', '', __LINE__, __FILE__, $sql);
	}

	$medal_members = array();
	$medal_members = $db->sql_fetchrowset($result); 
	$members_count = sizeof($medal_members);
	$db->sql_freeresult($result);

	$select_userlist = '';
	for($i = 0; $i < $members_count; $i++)
	{
		$issue_time = create_date($board_config['default_dateformat'], $medal_members[$i]['issue_time'], $board_config['board_timezone']);
		$select_userlist .= '<option value="' . $medal_members[$i]['issue_id'] . '">' . $medal_members[$i]['username'] . ' [' . $issue_time . '] ' . '</option>';
	}

	if( $select_userlist == '' )
	{
		$select_userlist = '<option value="-1">' . $lang['No_medal_members'] . '</option>';
	}

	$select_userlist = '<select name="unmedal_user[]" multiple="multiple" size="8" style="width: 250px;">' . $select_userlist . '</select>';
	
	$s_hidden_fields .= '<input type="hidden" name="mode" value="submit" />';

	$page_title = $lang['Medals'] . ' ' . $lang['Mod_CP'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	//
	// Load templates
	//
	$template->set_filenames(array(
		'info' => 'medalcp_body.tpl')
	);
	make_jumpbox('viewforum.'.$phpEx);

	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
	$s_hidden_fields .= '<input type="hidden" name="' . POST_MEDAL_URL . '" value="' . $medal_id . '" />';

	$template->assign_vars(array(
		'L_MEDAL_CP' => $lang['Medals'] . ' ' . $lang['Mod_CP'],
		'L_MEDAL_INFORMATION' => $lang['Medal_Information'],
		'L_MEDAL_NAME' => $lang['Medal_name'],
		'L_MEDAL_DESC' => $lang['Medal_description'],
		'L_MEDAL_IMAGE' => $lang['Medal_image'],
		'L_MEDAL_MODERATOR' => $lang['Medal_moderator'], 
		'L_MEDAL_MEMBERS' => $lang['Medal_Members'], 
		'L_MEDAL_MEMBERS_EXPLAIN' => $lang['Medal_Members_explain'], 
		'L_MEDAL_USER' => $lang['Medal_user_username'],
		'L_MEDAL_REASON' => $lang['Medal_reason'],
		'L_MEDAL_REASON_EXPLAIN' => $lang['Medal_reason_explain'],
		'L_UNMEDAL_USER' => $lang['Medal_unmedal_username'],
		'L_UNMEDAL_USER_EXPLAIN' => $lang['Medal_unmedal_username_explain'],
		'L_USERNAME' => $lang['Username'], 
		'L_LOOK_UP' => $lang['Look_up_User'],
		'L_FIND_USERNAME' => $lang['Find_username'],

		'L_MEDALS' => $lang['Medals'],
		'U_MEDALS' => append_sid("medals.$phpEx"),
		
		'MEDAL_NAME' => $medal_info['medal_name'],
		'MEDAL_DESC' => $medal_info['medal_description'],
		'MEDAL_IMAGE' => $medal_info['medal_image'],
		'MEDAL_IMAGE_DISPLAY' => ( !empty($medal_info['medal_image']) ) ? '<img src="images/medals/' . $medal_info['medal_image'] . '" alt="' . $medal_info['medal_name'] . '" title="' . $medal_info['medal_name'] . '" />' : "",
		'MEDAL_MODERATOR' => ( $medal_moderator ) ? $medal_moderator : $lang['No_medal_mod'],
		'MEDAL_MEMBER' => ( $medal_member ) ? $medal_member : $lang['No_medal_members'],

		'U_SEARCH_USER' => append_sid("search.$phpEx?mode=searchuser"),
		'S_UNMEDAL_USERLIST_SELECT' => $select_userlist,
		'S_MEDALCP_ACTION' => append_sid("medalcp.$phpEx"),
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);

}
else
{
	message_die(GENERAL_MESSAGE, $lang['No_medals_exist']);
}

$template->pparse('info');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>

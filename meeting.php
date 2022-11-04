<?php
/** 
*
* @package phpBB
* @version $Id: meeting.php,v 1.3.11 2006/08/09 oxpus Exp $
* @copyright (c) 2004 OXPUS
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
define('IN_MEETING', true);
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

//
// Start session management 
//
$userdata = session_pagestart($user_ip, PAGE_MEETING); 
init_userprefs($userdata); 
//
// End session management 
//

$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_meeting.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_meeting.' . $phpEx);

$gen_meeting_header = FALSE;

// Check and set various parameters
$params = array(
	'mode' => 'mode',
	'submit' => 'submit',
	'cancel' => 'cancel',
	'start' => 'start',
	'm_id' => 'm_id',
	'sf' => 'sf',
	'group_id' => 'group_id',
	'meeting_comment' => 'meeting_comment',
	'meeting_sure' => 'meeting_sure',
	'sign_on' => 'sign_on',
	'sign_off' => 'sign_off',
	'confirm' => 'confirm',
	'sort_field' => 'sort_field',
	'sort_order' => 'sort_order',
	'filter' => 'filter',
	'filter_by' => 'filter_by',
	'closed' => 'closed',
	'edit_comment' => 'edit_comment',
	'delete_comment' => 'delete_comment',
	'comment_id' => 'comment_id',
	'comment_user_id' => 'comment_user_id',
	'new_comment' => 'new_comment',
	'save_comment' => 'save_comment',
	'sign_off_command' => 'sign_off_command',
	'meeting_guests' => 'meeting_guests',
	'meeting_guest_prename' => 'meeting_guest_prename',
	'popup' => 'popup',
	'user_id' => POST_USERS_URL
);

while( list($var, $param) = @each($params) )
{
	if ( !empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]) )
	{
		$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? $HTTP_POST_VARS[$param] : $HTTP_GET_VARS[$param];
	}
	else
	{
		$$var = '';
	}
}

if ($popup == 'guest')
{
	$gen_simple_header = true;
	include($phpbb_root_path.'includes/page_header.'.$phpEx);

	$sql = "SELECT meeting_subject, meeting_time 
		FROM " . MEETING_DATA_TABLE . "
		WHERE meeting_id = $m_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not read meeting data', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$meeting_title = $row['meeting_subject'];
	$meeting_time = create_date($board_config['default_dateformat'], $row['meeting_time'], $board_config['board_timezone']);

	$sql = "SELECT username FROM " . USERS_TABLE . "
		WHERE user_id = $user_id";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not read meeting data', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$current_username = $row['username'];

	$sql = "SELECT guest_prename, guest_name 
		FROM " . MEETING_GUESTNAMES_TABLE . "
		WHERE meeting_id = $m_id
			AND user_id = $user_id
		ORDER BY guest_name, guest_prename";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not read your guests', '', __LINE__, __FILE__, $sql);
	}

	$my_guest_names = array();

	$template->set_filenames(array(
		'body' => 'meeting_guests_popup.tpl')
	);

	$template->assign_vars(array(
		'MEETING_TITLE' => $meeting_title,
		'MEETING_TIME' => $meeting_time,
		'L_GUEST_PRENAMES' => $lang['Meeting_prenames'],
		'L_GUEST_NAMES' => $lang['Meeting_names'],
		'USERNAME' => $current_username)
	);		

	$i = 0;
	while ($row = $db->sql_fetchrow($result))
	{
		$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('guest_name_row', array(
			'ROW_CLASS' => $row_class,
			'GUEST_PRENAME' => $row['guest_prename'],
			'GUEST_NAME' => $row['guest_name'])
		);

		$i++;
	}
	$db->sql_freeresult($result);

	$template->pparse('body');

	include($phpbb_root_path.'includes/page_tail.'.$phpEx);

	exit;
}

$meeting_guest_name = (isset($HTTP_POST_VARS['meeting_guest_name'])) ? $HTTP_POST_VARS['meeting_guest_name'] : array();
$meeting_guest_prename = (isset($HTTP_POST_VARS['meeting_guest_prename'])) ? $HTTP_POST_VARS['meeting_guest_prename'] : array();

// Get access status for all meetings
$sql = "SELECT m.meeting_id, mg.meeting_group 
	FROM " . MEETING_DATA_TABLE . " m, " . MEETING_USERGROUP_TABLE . " mg
	WHERE mg.meeting_id = m.meeting_id";
if ( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not get meeting usergroups', '', __LINE__, __FILE__, $sql);
}
$meetings_access_ids = array();

while ( $row = $db->sql_fetchrow($result) )
{
	$meeting_id = $row['meeting_id'];
	$meeting_group = $row['meeting_group'];

	if ( $meeting_group == -1 )
	{
		$meetings_access_ids[$meeting_id] = TRUE;
	}
	else
	{
		$sql_auth_id = "SELECT g.group_id 
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE g.group_id = $meeting_group
				AND g.group_id = ug.group_id
				AND ug.user_pending <> " . TRUE . "
				AND g.group_single_user <> " . TRUE . "
				AND ug.user_id = " . $userdata['user_id'];
		if ( !$result_auth_id = $db->sql_query($sql_auth_id) )
		{
			message_die(GENERAL_ERROR, 'Could not get meeting access data', '', __LINE__, __FILE__, $sql_auth_id);
		}

		$count_usergroups = $db->sql_numrows($result_auth_id);
		$db->sql_freeresult($result_auth_id);

		if ( $count_usergroups > 0 )
		{
			$meetings_access_ids[$meeting_id] = TRUE;
		}
	}
}

// Prepare sorting and filter variables
$sort_field = ( $sort_field == '' ) ? 'meeting_time' : $sort_field;
$sort_order = ( $sort_order == '' ) ? 'DESC' : $sort_order;
$filter_by = ( $filter_by == '' ) ? 'none' : $filter_by;
$sql_filter = ( $filter_by == 'none' ) ? '' : ( ( $filter != '' ) ? " WHERE $filter_by LIKE ('$filter')" : '' );
$closed = ( $closed == '' ) ? 1 : $closed;

// Set start point for meeting overview list
$start = ( $start == '' ) ? 0 : $start;

// Crash code for submitting a sort and/or filter request
if ( $mode == 'sf' )
{
	$mode = 'viewlist';
	$submit = '';
}

// What shall we do on cancel a deleting
if ( $mode == 'cancel' || $cancel )
{
	$submit = $cancel = $confirm = $delete_comment = '';
	$mode = 'detail';
}

// What shall we do on cancel a deleting
if ( $mode == 'sign_off_command' || $sign_off_command )
{
	$submit = $cancel = $confirm = '';
	$mode = 'sign_off';
}

// Please confirm the sign_off. Better way.
if ( $mode == 'sign_off' || $sign_off )
{
	// Load header and templates
	$page_title = $lang['Meeting'];
	include($phpbb_root_path.'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'confirm_body.tpl')
	);

	$s_hidden_fields = '<input type="hidden" name="m_id" value="'.$m_id.'">';
	$s_hidden_fields .= '<input type="hidden" name="'.POST_USERS_URL.'" value="'.$userdata['user_id'].'">';

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Meeting_sign_off'],
		'MESSAGE_TEXT' => $lang['Meeting_sign_off_explain'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'S_CONFIRM_ACTION' => append_sid("meeting.$phpEx"),
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);

	$template->pparse('body');

	include($phpbb_root_path.'includes/page_tail.'.$phpEx);
}

// Now we will sign_off. Good bye :-)
if ( $mode == 'confirm' || $confirm )
{
	$sql = "SELECT m.meeting_subject, m.meeting_notify, m.meeting_by_user, u.user_email 
		FROM " . MEETING_DATA_TABLE . " m, " . USERS_TABLE . " u
		WHERE m.meeting_id = $m_id
			AND m.meeting_by_user = u.user_id";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not read meeting subject', '', __LINE__, __FILE__, $sql);
	}
	while ($row = $db->sql_fetchrow($result))
	{
		$meeting_subject = $row['meeting_subject'];
		$meeting_notify = $row['meeting_notify'];
		$meeting_by_user = $row['meeting_by_user'];
		$user_email = $row['user_email'];
	}
	$db->sql_freeresult($result);

	$m_id = intval($m_id);
	$user_id = intval($user_id);

	if ($userdata['user_id'] != $user_id && ($userdata['user_level'] == ADMIN || $userdata['user_level'] == MOD || $userdata['user_id'] == $meeting_by_user_id))
	{
		$meeting_user = $user_id;
		$profiledata = get_userdata($meeting_user);
		$username = $profiledata['username'];		
	}
	else
	{
		$meeting_user = $userdata['user_id'];
		$username = $userdata['username'];
	}

	$sql = "DELETE FROM " . MEETING_USER_TABLE . "
		WHERE meeting_id = $m_id
			AND user_id = $meeting_user";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete meeting user', '', __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . MEETING_GUESTNAMES_TABLE . "
		WHERE meeting_id = $m_id
		AND user_id = $meeting_user";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete meeting user', '', __LINE__, __FILE__, $sql);
	}

	if ($board_config['meeting_notify'] || $meeting_notify)
	{
		include($phpbb_root_path . 'includes/emailer.'.$phpEx);

		$subject = $lang['Meeting_unjoin_message'];

		$message = sprintf($lang['Meeting_unjoin_user'], $username, $meeting_subject);

		if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
		{
			$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

			// We are running on windows, force delivery to use our smtp functions
			// since php's are broken by default
			$board_config['smtp_delivery'] = 1;
			$board_config['smtp_host'] = @$ini_val('SMTP');
		}

		$emailer = new emailer($board_config['smtp_delivery']);

		$emailer->from($board_config['board_email']);
		$emailer->replyto($board_config['board_email']);

		if ($meeting_notify)
		{
			$emailer->bcc($user_email);
		}

		$email_headers = 'X-AntiAbuse: Board servername - ' . $board_config['server_name'] . "\n";
		$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
		$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
		$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

		$emailer->use_template('admin_send_email');
		$emailer->email_address($board_config['board_email']);
		if ($board_config['meeting_notify'])
		{
			$emailer->email_address($board_config['board_email']);
		}
		$emailer->set_subject($subject);
		$emailer->extra_headers($email_headers);

		$emailer->assign_vars(array(
			'SITENAME' => $board_config['sitename'],
			'BOARD_EMAIL' => $board_config['board_email'],
			'MESSAGE' => $message)
		);
		$emailer->send();
		$emailer->reset();
	}

	$mode = 'detail';
}

// Save meeting comment. Great or not ;-)
if ( $mode == 'save_comment' || $save_comment )
{
	$m_id = intval($m_id);
	$comment_id = intval($comment_id);
	
	$sql = "SELECT user_id, meeting_id 
		FROM " . MEETING_COMMENT_TABLE . "
		WHERE user_id = $comment_user_id
			AND comment_id = $comment_id
			AND meeting_id = $id";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not save user sign_on', '', __LINE__, __FILE__, $sql);
	}

	$submit_check = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	if ( $meeting_comment != '' )
	{
		$meeting_comment = addslashes(trim($meeting_comment));
		$meeting_edit_time = time();
		
		$bbcode_on = ($board_config['allow_bbcode']) ? 1 : 0;
		$bbcode_uid = ($board_config['allow_bbcode']) ? make_bbcode_uid() : '';
		$html_on = ($board_config['allow_html'] && $userdata['user_allowhtml']) ? 1 : 0;
		$smiles_on = ($board_config['allow_smilie']) ? 1 : 0;

		$meeting_comment = prepare_message($meeting_comment, $html_on, $bbcode_on, $smiles_on, $bbcode_uid);

		if ( $submit_check == 0 || $new_comment)
		{
			$sql = "INSERT INTO " . MEETING_COMMENT_TABLE . " (user_id, meeting_id, meeting_comment, meeting_edit_time, bbcode_uid) 
				VALUES ($comment_user_id, $m_id, '$meeting_comment', $meeting_edit_time, '$bbcode_uid')";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not save user comment', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = "UPDATE " . MEETING_COMMENT_TABLE . " 
				SET meeting_comment = '$meeting_comment', bbcode_uid = '$bbcode_uid'
				WHERE meeting_id = $m_id
					AND user_id = $comment_user_id
					AND comment_id = $comment_id";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update user comment', '', __LINE__, __FILE__, $sql);
			}
		}
	}
	else if ( $meeting_comment == '' && $submit_check != 0 )
	{
		$sql = "DELETE FROM " . MEETING_COMMENT_TABLE . "
			WHERE meeting_id = $m_id
				AND user_id = $comment_user_id
				AND comment_id = " . intval($comment_id);
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not delete user comment', '', __LINE__, __FILE__, $sql);
		}
	}

	$mode = 'detail';
}

// Delete meeting comment :-(
if ( ($mode == 'delete_comment' || $delete_comment == 1) && ($userdata['user_level'] == ADMIN || $userdata['user_level'] == MOD || ($userdata['user_level'] == USER && $board_config['allow_user_delete_meeting_comments'] == TRUE)) )
{
	$comment_id = intval($comment_id);

	if (!$confirm)
	{
		// Load header and templates
		$page_title = $lang['Meeting'];
		include($phpbb_root_path.'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'body' => 'confirm_body.tpl')
		);

		$s_hidden_fields = '<input type="hidden" name="m_id" value="'.$m_id.'">';
		$s_hidden_fields .= '<input type="hidden" name="comment_id" value="'.$comment_id.'">';
		$s_hidden_fields .= '<input type="hidden" name="delete_comment" value="1">';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Meeting_comments'],
			'MESSAGE_TEXT' => $lang['Confirm_delete_pm'],
			
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
				
			'S_CONFIRM_ACTION' => append_sid("meeting.$phpEx"),
			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('body');

		include($phpbb_root_path.'includes/page_tail.'.$phpEx);
	}

	$sql = "DELETE FROM " . MEETING_COMMENT_TABLE . "
		WHERE meeting_id = $m_id
			AND comment_id = $comment_id";
	$sql .= ($userdata['user_level'] == USER) ? ' AND user_id = ' . $userdata['user_id'] : '';
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete user comment', '', __LINE__, __FILE__, $sql);
	}

	$mode = 'detail';
}

// Sign_on meeting. Welcome :-)
if ( $mode == 'submit' || $submit )
{
	if ($HTTP_POST_VARS['sign_user'] == 'other')
	{
		$meeting_user = intval($user_id);
	}
	else
	{
		$meeting_user = $userdata['user_id'];
	}

	if ($meeting_user == ANONYMOUS)
	{
		redirect(append_sid("meeting.$phpEx"));
	}

	$meeting_sure = intval($meeting_sure);
	$meeting_guests = ($meeting_sure) ? intval($meeting_guests) : 0;
	$m_id = intval($m_id);
	
	$sql = "SELECT user_id, meeting_id 
		FROM " . MEETING_USER_TABLE . "
		WHERE user_id = $meeting_user
			AND meeting_id = $m_id";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not save user sign_on', '', __LINE__, __FILE__, $sql);
	}

	$submit_check = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	$sql = "SELECT m.meeting_subject, m.meeting_guest_overall, m.meeting_guest_single, m.meeting_guest_names, u.user_email, m.meeting_notify 
		FROM " . MEETING_DATA_TABLE . " m, " . USERS_TABLE . " u
		WHERE m.meeting_id = $m_id
			AND m.meeting_by_user = u.user_id";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not read meeting subject', '', __LINE__, __FILE__, $sql);
	}
	
	while ($row = $db->sql_fetchrow($result))
	{
		$meeting_subject = $row['meeting_subject'];
		$meeting_notify = $row['meeting_notify'];
		$meeting_guest_overall = $row['meeting_guest_overall'];
		$meeting_guest_single = $row['meeting_guest_single'];
		$meeting_gnames = $row['meeting_guest_names'];
		$user_email = $row['user_email'];
	}
	$db->sql_freeresult($result);

	$sql = "SELECT sum(meeting_guests) AS total_guests 
		FROM " . MEETING_USER_TABLE . "
		WHERE meeting_id = $m_id
			AND user_id <> $meeting_user";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not save user sign_on', '', __LINE__, __FILE__, $sql);
	}
	
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$total_guests = intval($row['total_guests']);
	$remain_guests = 0;

	if ($meeting_guest_overall)
	{
		$remain_guests = $meeting_guest_overall - $total_guests;
	}

	if ($meeting_guest_single)
	{
		$remain_guests = $meeting_guest_single;
		if ($meeting_guest_overall && $remain_guests > ($meeting_guest_overall - $total_guests))
		{
			$remain_guests = $meeting_guest_overall - $total_guests;
		}
	}

	if ($meeting_guests > $remain_guests)
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['Meeting_remain_guest_text'], $meeting_guests, $remain_guests));
	}

	if ( $submit_check == 0 )
	{
		$sql = "INSERT INTO " . MEETING_USER_TABLE . " (user_id, meeting_id, meeting_sure, meeting_guests) 
			VALUES ($meeting_user, $m_id, $meeting_sure, $meeting_guests)";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not save user sign_on', '', __LINE__, __FILE__, $sql);
		}

		if ($meeting_sure <> 0)
		{
			$subject = $lang['Meeting_join_message'];
			$message = sprintf($lang['Meeting_join_user'], $userdata['username'], $meeting_subject);
		}
		else
		{
			$subject = $lang['Meeting_unwill_message'];
			$message = sprintf($lang['Meeting_unwill_user'], $userdata['username'], $meeting_subject);
		}
	}
	else
	{
		$sql = "UPDATE " . MEETING_USER_TABLE . " 
			SET meeting_sure = $meeting_sure, meeting_guests = $meeting_guests
			WHERE meeting_id = $m_id
				AND user_id = $meeting_user";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update user sign_on', '', __LINE__, __FILE__, $sql);
		}

		if ($meeting_sure <> 0)
		{
			$subject = $lang['Meeting_change_message'];
			$message = sprintf($lang['Meeting_change_user'], $userdata['username'], $meeting_subject);
		}
		else
		{
			$subject = $lang['Meeting_unwill_message'];
			$message = sprintf($lang['Meeting_unwill_user'], $userdata['username'], $meeting_subject);
		}
	}

	$sql = "DELETE FROM " . MEETING_GUESTNAMES_TABLE . "
		WHERE meeting_id = $m_id
			AND user_id = $meeting_user";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not refresh guestnames for user', '', __LINE__, __FILE__, $sql);
	}

	if ($meeting_gnames)
	{
		$guest_counter = 0;
		
		for ($i = 0; $i < sizeof($meeting_guest_name); $i++)
		{
			$mgpn = htmlspecialchars(str_replace("\'", "'", trim($meeting_guest_prename[$i])));
			$mgpn = phpbb_rtrim($mgpn, "\\");
			$mgpn = str_replace("'", "\'", $mgpn);

			$mgna = htmlspecialchars(str_replace("\'", "'", trim($meeting_guest_name[$i])));
			$mgna = phpbb_rtrim($mgna, "\\");
			$mgna = str_replace("'", "\'", $mgna);

			if ($mgpn != '' && $mgna != '')
			{
				$sql = "INSERT INTO " . MEETING_GUESTNAMES_TABLE . "
					(meeting_id, user_id, guest_prename, guest_name)
					VALUES ($m_id, $meeting_user, '$mgpn', '$mgna')";
				if ( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not refresh guestnames for user', '', __LINE__, __FILE__, $sql);
				}
				$guest_counter++;
			}
		}

		if ($guest_counter <> $meeting_guests)
		{
			$meeting_guests = $guest_counter;

			$sql = "UPDATE " . MEETING_USER_TABLE . "
				SET meeting_guests = $guest_counter
				WHERE meeting_id = $m_id
					AND user_id = $meeting_user";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update user sign_on', '', __LINE__, __FILE__, $sql);
			}
		}
	}

	if ($board_config['meeting_notify'] || $meeting_notify)
	{
		$sql = "SELECT m.meeting_subject, u.user_email 
			FROM " . MEETING_DATA_TABLE . " m, " . USERS_TABLE . " u
			WHERE m.meeting_id = $m_id
				AND m.meeting_by_user = u.user_id";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not read meeting subject', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$user_email = $row['user_email'];
		}
		$db->sql_freeresult($result);

		include($phpbb_root_path . 'includes/emailer.'.$phpEx);

		if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
		{
			$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

			// We are running on windows, force delivery to use our smtp functions
			// since php's are broken by default
			$board_config['smtp_delivery'] = 1;
			$board_config['smtp_host'] = @$ini_val('SMTP');
		}

		$emailer = new emailer($board_config['smtp_delivery']);

		$emailer->from($board_config['board_email']);
		$emailer->replyto($board_config['board_email']);

		if ($meeting_notify)
		{
			$emailer->bcc($user_email);
		}

		$email_headers = 'X-AntiAbuse: Board servername - ' . $board_config['server_name'] . "\n";
		$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
		$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
		$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

		$emailer->use_template('admin_send_email');
		if ($board_config['meeting_notify'])
		{
			$emailer->email_address($board_config['board_email']);
		}
		$emailer->set_subject($subject);
		$emailer->extra_headers($email_headers);

		$emailer->assign_vars(array(
			'SITENAME' => $board_config['sitename'],
			'BOARD_EMAIL' => $board_config['board_email'],
			'MESSAGE' => $message)
		);
		$emailer->send();
		$emailer->reset();
	}

	$mode = 'detail';
}

// Enter or edit user comments
if ( $mode == 'edit_comment' || $edit_comment )
{
	$meeting_comment = '';

	if ( $comment_user_id != '' )
	{
		$sql = "SELECT m.comment_id, m.meeting_comment, u.username, u.user_id 
			FROM " . MEETING_COMMENT_TABLE . " m, " . USERS_TABLE . " u
			WHERE m.user_id = $comment_user_id
				AND m.user_id = u.user_id
				AND m.meeting_id = $m_id
				AND m.comment_id = " . intval($comment_id);
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get meeting data', '', __LINE__, __FILE__, $sql);
		}
		
		while ( $row = $db->sql_fetchrow($result) )
		{
			$current_user = $row['username'];
			$comment_id = $row['comment_id'];
			$comment_user = '<a href="'.append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;'.POST_USERS_URL.'='.$comment_user_id).'" class="nav" target="_blank">'.$current_user.'</a>';
			$meeting_comment = $row['meeting_comment'];
		}
		$db->sql_freeresult($result);
	}
	else
	{
		$comment_user_id = $userdata['user_id'];
		$current_user = $userdata['username'];
		$comment_user = '<a href="'.append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;'.POST_USERS_URL.'='.$comment_user_id).'" class="nav" target="_blank">'.$current_user.'</a>';
	}

	// Load header and templates
	$page_title = $lang['Meeting_comment'];
	include($phpbb_root_path.'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'meeting_comment_body.tpl')
	);

	$template->assign_vars(array(
		'L_MEETING' => $lang['Meeting'],
		'L_MEETING_DETAIL' => $lang['Meeting_detail'],
		'L_MEETING_COMMENT' => $page_title,
		'L_SUBMIT' => $lang['Submit'],

		'USERNAME' => $comment_user,
		'MEETING_COMMENT' => stripslashes($meeting_comment),

		'U_MEETING' => append_sid('meeting.'.$phpEx),
		'U_MEETING_DETAIL' => append_sid('meeting.'.$phpEx.'?mode=detail&amp;m_id='.$m_id),
		'U_MEETING_COMMENT' => append_sid('meeting.'.$phpEx.'?mode=edit_comment&amp;m_id='.$m_id.'&amp;comment_user_id='.$comment_user_id),

		'S_ACTION' => append_sid("meeting.$phpEx?m_id=$m_id&amp;comment_id=$comment_id&amp;comment_user_id=$comment_user_id&amp;new_comment=".intval($new_comment)))
	);

	$template->pparse('body');

	// Include the board footer with phpBB copyright
	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}

// Display meeting details
if ( $mode == 'detail' )
{
	if ( $meetings_access_ids[$m_id] == TRUE )
	{
		$sql = "SELECT m.*, u1.username as create_username, u1.user_id as create_user_id, u2.username as edit_username, u2.user_id as edit_user_id
			FROM " . MEETING_DATA_TABLE . " m, " . USERS_TABLE . " u1, " . USERS_TABLE . " u2
			WHERE m.meeting_id = $m_id
				AND m.meeting_by_user = u1.user_id
				AND m.meeting_edit_by_user = u2.user_id";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get meeting data', '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$meeting_time = $row['meeting_time'];
			$meeting_until = $row['meeting_until'];
			$meeting_location = $row['meeting_location'];
			$meeting_subject = $row['meeting_subject'];
			$meeting_desc = $row['meeting_desc'];
			$meeting_link = $row['meeting_link'];
			$meeting_places = $row['meeting_places'];
			$meeting_by_username = $row['create_username'];
			$meeting_by_user_id = append_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL."=".$row['create_user_id']);
			$meeting_edit_by_username = $row['edit_username'];
			$meeting_edit_by_user_id = append_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL."=".$row['edit_user_id']);
			$meeting_start_value = $row['meeting_start_value'];
			$meeting_recure_value = $row['meeting_recure_value'];
			$meeting_guest_overall = $row['meeting_guest_overall'];
			$meeting_guest_single = $row['meeting_guest_single'];
			$meeting_guest_names = $row['meeting_guest_names'];
			$meeting_creator = $row['meeting_by_user'];
			$bbcode_uid = $row['bbcode_uid'];
		}
		$db->sql_freeresult($result);

		if ( !$board_config['allow_html'] || !$userdata['user_allowhtml'])
		{
			$meeting_desc = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $meeting_desc);
		}

		if ($bbcode_uid != '')
		{
			$meeting_desc = ($board_config['allow_bbcode']) ? bbencode_second_pass($meeting_desc, $bbcode_uid) : preg_replace("/\:$bbcode_uid/si", '', $meeting_desc);
		}

		$meeting_desc = make_clickable($meeting_desc);

		if ( $board_config['allow_smilies'] )
		{
			$meeting_desc = smilies_pass($meeting_desc);
		}

		$meeting_desc = str_replace("\n", "\n<br />\n", $meeting_desc);
		$meeting_by_user = sprintf($lang['Meeting_create_by'], $meeting_by_user_id, $meeting_by_username);
		$meeting_edit_by_user = sprintf($lang['Meeting_edit_by'], $meeting_edit_by_user_id, $meeting_edit_by_username);

		$sql = "SELECT m.user_id, m.meeting_sure, m.meeting_guests, u.username 
			FROM " . MEETING_USER_TABLE . " m, " . USERS_TABLE . " u
			WHERE m.user_id = u.user_id
				AND m.meeting_id = $m_id
			ORDER BY m.meeting_sure, u.username";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not get meeting data', '', __LINE__, __FILE__, $sql);
		}
		
		$meeting_total_user_ids = $meeting_user_ids = $meeting_sure = $total_guests = $meeting_guests = $remain_guests = 0;
		$meeting_user = $s_meeting_signoffs = $guest_places = '';

		$meeting_users_sql = $userdata['user_id'] . ', ' . ANONYMOUS;

		$signed_on = FALSE;
		$current_user = $userdata['user_id'];

		while ( $row = $db->sql_fetchrow($result) )
		{
			$signed_on_user = $row['user_id'];
			$meeting_users_sql .= ', ' . $signed_on_user;
			$guests = $row['meeting_guests'];

			if ( $signed_on_user == $current_user  ) 
			{
				$signed_on = TRUE;
				$meeting_guests = $guests;
			}
			else
			{
				$total_guests += ($row['meeting_sure']) ? $guests : 0;
			}

			$meeting_user .= ' <a href="'.append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;'.POST_USERS_URL.'='.$row['user_id']).'" class="mainmenu" target="_blank">'.$row['username'].'</a> (';
			$meeting_user .= ($row['meeting_sure']) ? (($row['meeting_sure'] == 100) ? $lang['Meeting_yes_signon'] : $row['meeting_sure'].'%') : $lang['Meeting_no_signon'];
			if ($guests)
			{
				if ($meeting_guest_names)
				{
					$meeting_guests_text = ($guests == 1) ? sprintf($lang['Meeting_user_guest_popup'], $m_id, $row['user_id']) : sprintf($lang['Meeting_user_guests_popup'], $m_id, $row['user_id'], $guests);
					$guest_popup = append_sid("meeting.$phpEx?popup=guest");
				}
				else
				{
					$meeting_guests_text = ($guests == 1) ? $lang['Meeting_user_guest'] : sprintf($lang['Meeting_user_guests'], $guests);
					$guest_popup = '';
				}

				$meeting_user .= $meeting_guests_text;
			}
			$meeting_user .= ') -';

			$meeting_sure += $row['meeting_sure'];
			$meeting_user_ids += ($row['meeting_sure']) ? 1 : 0;
			$meeting_total_user_ids++;

			$s_meeting_signoffs .= ($userdata['user_id'] != $signed_on_user) ? '<option value="'.$signed_on_user.'">'.$row['username'].'</option>' : '';
		}
		$db->sql_freeresult($result);

		if ($meeting_guest_overall)
		{
			$remain_guests = $meeting_guest_overall - $total_guests;
			$guest_places .= sprintf($lang['Meeting_overall_guest_places'], $meeting_guest_overall);
		}

		if ($meeting_guest_single)
		{
			$guest_places .= sprintf($lang['Meeting_single_guest_places'], $meeting_guest_single);
			$remain_guests = $meeting_guest_single;
			if ($meeting_guest_overall && $remain_guests > ($meeting_guest_overall - $total_guests))
			{
				$remain_guests = $meeting_guest_overall - $total_guests;
			}
		}

		$meeting_sure_total = ( $meeting_user_ids != 0 ) ? number_format((100/$meeting_places*$meeting_user_ids),2,',','.') : 0;
		$meeting_sure_total_user = ( $meeting_sure != 0 ) ? number_format((100/$meeting_places*$meeting_sure)/100,2,',','.') : 0;

		$meeting_free_places = ( $meeting_user_ids != 0 ) ? ($meeting_places - $meeting_user_ids) : $meeting_places;

		$meeting_closed = ( $meeting_time < time() ) ? 2 : ( ( $meeting_until < time() ) ? 1 : 0 );
		$meeting_closed_string = ( $meeting_time < time() ) ? '<br />[ '.$lang['Meeting_closed'].' ]' : ( ( $meeting_until < time() ) ? '<br />[ '.$lang['Meeting_no_period'].' ]' : '' );

		$meeting_time = create_date($board_config['default_dateformat'], $meeting_time, $board_config['board_timezone']);
		$meeting_until = create_date($board_config['default_dateformat'], $meeting_until, $board_config['board_timezone']);

		if ($userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD || $userdata['user_id'] == $meeting_by_user_id)
		{
			$u_meeting_email = append_sid("meeting_mail.$phpEx?m_id=$m_id");
			$l_meeting_email = $lang['Meeting_mail'];

			$template->assign_block_vars('meeting_email', array(
				'L_MEETING_EMAIL' => $l_meeting_email,
				'U_MEETING_EMAIL' => $u_meeting_email)
			);
		}		

		if ($remain_guests)
		{
			$remain_guest_places = sprintf($lang['Meeting_remain_guest_places'], $remain_guests);

			if ($meeting_guest_names)
			{
				$template->assign_block_vars('guest_names_block', array());
				$s_remain_guests = '';

				$sql = "SELECT guest_prename, guest_name FROM " . MEETING_GUESTNAMES_TABLE . "
					WHERE meeting_id = $m_id
						AND user_id = $current_user
					ORDER BY guest_name, guest_prename";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not read your guests', '', __LINE__, __FILE__, $sql);
				}

				$my_guest_names = array();
				while ($row = $db->sql_fetchrow($result))
				{
					$my_guest_names['prename'][] = $row['guest_prename'];
					$my_guest_names['name'][] = $row['guest_name'];
				}
				$db->sql_freeresult($result);

				if ($meeting_closed)
				{
					$meeting_closed_row = '_read_only';
					$remain_guests = sizeof($my_guest_names['name']);
				}
				else
				{
					$meeting_closed_row = '';
					$template->assign_block_vars('guest_names_block.guest_block_footer', array(
						'L_GUESTNAMES_EXPLAIN' => $lang['Meeting_guestname_entering_explain'])
					);
				}

				for ($i = 0; $i < $remain_guests; $i++)
				{
					$row_class = (($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

					$template->assign_block_vars('guest_names_block.guest_name_row'.$meeting_closed_row, array(
						'ROW_CLASS' => $row_class,
						'GUEST_PRENAME' => $my_guest_names['prename'][$i],
						'GUEST_NAME' => $my_guest_names['name'][$i])
					);
				}
			}
			else
			{
			$s_remain_guests = $lang['Meeting_invite_guests'] . '<select name="meeting_guests">';
			for ($i = 0; $i <= $remain_guests; $i++)
			{
				$s_remain_guests .= '<option value="'.$i.'">'.$i.'</option>';
			}
			$s_remain_guests .= '</select>&nbsp;'.$lang['Meeting_guests'];
			$s_remain_guests = str_replace('value="'.$meeting_guests.'"', 'value="'.$meeting_guests.'" selected="selected"', $s_remain_guests);
		}
		}
		else
		{
			$remain_guest_places = $s_remain_guests = '';
		}

		$remain_guests -= $meeting_guests;
		$total_guests += $meeting_guests;
		
		if ($total_guests)
		{
			$total_guests_text = ($total_guests == 1) ? $lang['Meeting_user_guest'] : sprintf($lang['Meeting_user_guests'], $total_guests);
		}
		else
		{
			$total_guests_text = '';
		}

		$total_meeting_users = $meeting_user_ids + $total_guests;
		$meeting_user = ( $meeting_total_user_ids == 0 ) ? $lang['Meeting_no_user'] : '<span class="gen"><b>'.($meeting_total_user_ids+$total_guests).' '.$lang['Meeting_user_joins'].'</b> ('.$lang['Meeting_yes_signons'].$meeting_user_ids.$total_guests_text.'):</span> '.substr($meeting_user, 0, strlen($meeting_user)-1);

		if ($remain_guests)
		{
			$meeting_free_guests = ($remain_guests == 1) ? $lang['Meeting_user_guest'] : sprintf($lang['Meeting_user_guests'], $remain_guests);
		}
		else
		{
			$meeting_free_guests = '';
		}

		switch ( $meeting_closed )
		{
			case 0:
				$signed_on_edit = ( $signed_on == TRUE ) ? '<input type="submit" name="submit" class="mainoption" value="'.$lang['Meeting_sign_edit'].'">' : ( ( $meeting_free_places != 0 ) ? '<input type="submit" name="submit" class="mainoption" value="'.$lang['Meeting_sign_on'].'">' : '' );
				$signed_off = ( $signed_on == TRUE ) ? '&nbsp;&nbsp;&nbsp;<input type="submit" name="sign_off_command" class="liteoption" value="'.$lang['Meeting_sign_off'].'">' : '' ;
				break;
			case 1:
				$signed_on_edit = ( $signed_on == TRUE ) ? '<input type="submit" name="submit" class="mainoption" value="'.$lang['Meeting_sign_edit'].'">' : '';
				$signed_off = ( $signed_on == TRUE ) ? '&nbsp;&nbsp;&nbsp;<input type="submit" name="sign_off_command" class="liteoption" value="'.$lang['Meeting_sign_off'].'">' : '' ;
				$s_remain_guests = '<input type="hidden" name="meeting_guests" value="'.$meeting_guests.'" />';
				break;
			case 2:
				$signed_on_edit = $signed_off = $s_remain_guests = '';
				break;
		}

		if ( $meeting_free_places != 0 || ( $meeting_free_places == 0 && $signed_on == TRUE ) )
		{
			if ( $meeting_closed == 0 || ( $meeting_closed == 1 && $signed_on == TRUE ) )
			{
				$meeting_sure_user = '&nbsp;<select name="meeting_sure">';
				for ( $i = $meeting_start_value; $i < 100; $i += $meeting_recure_value )
				{
					$meeting_sure_user .= '<option value="'.$i.'">'.(($i == 0) ? $lang['Meeting_no_signon'] : $i.'%').'</option>';
				}
				$meeting_sure_user .= '<option value="100" selected="selected">'.$lang['Meeting_yes_signon'].'</option>';
				$meeting_sure_user .= '</select>';
			}
			else
			{
				$meeting_sure_user = '';
			}
		}
		else
		{
			$meeting_sure_user = '';
		}

		if ($meeting_closed < 2 && $s_meeting_signoffs && ($userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD || $userdata['user_id'] == $meeting_by_user_id))
		{
			$s_meeting_signoffs = '<select name="'.POST_USERS_URL.'">'.$s_meeting_signoffs.'</select><input type="hidden" name="m_id" value="'.$m_id.'" />';
			$signed_off_other = '&nbsp;&nbsp;&nbsp;<input type="submit" name="confirm" class="liteoption" value="'.$lang['Meeting_sign_off'].'">';

			$template->assign_block_vars('sign_off_user', array(
				'SIGNED_OFF' => $signed_off_other,
				'S_MEETING_SIGNOFFS' => $s_meeting_signoffs)
			);
		}
				
		// Load header and templates
		$page_title = $lang['Meeting_detail'];
		include($phpbb_root_path.'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'body' => 'meeting_detail_body.tpl')
		);

		if (!$meeting_closed && ($userdata['user_level'] == ADMIN || $meeting_creator == $userdata['user_id'] || $userdata['user_level'] == MOD))
		{
			// Get all unsigned but possible users for this meeting
			$sql = "SELECT meeting_group FROM " . MEETING_USERGROUP_TABLE . "
				WHERE meeting_id = $m_id
				ORDER BY meeting_group";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not get meeting usergroups', '', __LINE__, __FILE__, $sql);
			}

			$meeting_new_user_ary = array();

			while ($row = $db->sql_fetchrow($result))
			{
				$meeting_new_user_ary[] = $row['meeting_group'];
			}
			$db->sql_freeresult($result);

			unset($sql);

			if ($meeting_new_user_ary[0] == -1)
			{
				$sql = "SELECT user_id, username FROM " . USERS_TABLE . "
					WHERE user_id NOT IN ($meeting_users_sql)
					ORDER BY username";
			}
			else if (sizeof($meeting_new_user_ary))
			{
				$sql = "SELECT ug.user_id, u.username FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
					WHERE g.group_id IN (".implode(', ', $meeting_new_user_ary).")
						AND g.group_id = ug.group_id
						AND ug.user_pending <> " . TRUE . "
						AND g.group_single_user <> " . TRUE . "
						AND ug.user_id NOT IN ($meeting_users_sql)
						AND ug.user_id = u.user_id
					ORDER BY username";
			}

			if ($sql)
			{
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not read possible new users', '', __LINE__, __FILE__, $sql);
				}

				$total_users = $db->sql_numrows($result);
				if ($total_users)
				{
					$s_new_users = '<select name="'.POST_USERS_URL.'">';
					$signed_on_other = '&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" class="liteoption" value="'.$lang['Meeting_yes_signon'].'">';
					$signed_on_other .= '<input type="hidden" name="m_id" value="'.$m_id.'" />';
					$signed_on_other .= '<input type="hidden" name="meeting_sure" value="100" />';
					$signed_on_other .= '<input type="hidden" name="sign_user" value="other" />';

					while ($row = $db->sql_fetchrow($result))
					{
						$s_new_users .= '<option value="'.$row['user_id'].'">'.$row['username'].'</option>';
					}

					$s_new_users .= '</select>';

					$template->assign_block_vars('sign_on_other_user', array(
						'S_NEW_USERS' => $s_new_users,
						'S_SIGNED_ON_OTHER' => $signed_on_other)
					);
				}

				$db->sql_freeresult($result);
			}
		}

		$template->assign_vars(array(
			'L_MEETING' => $lang['Meeting'],
			'L_MEETING_DETAIL' => $lang['Meeting_detail'],
			'L_MEETING_TIME' => $lang['Time'],
			'L_MEETING_UNTIL' => $lang['Meeting_until'],
			'L_MEETING_LOCATION' => $lang['Meeting_location'],
			'L_MEETING_SUBJECT' => $lang['Meeting_subject'],
			'L_MEETING_LINK' => $lang['Meeting_link'],
			'L_MEETING_PLACES' => $lang['Meeting_places'],
			'L_MEETING_FREE_PLACES' => $lang['Meeting_free_places'],
			'L_MEETING_SURE_TOTAL' => $lang['Meeting_sure_total'],
			'L_MEETING_SURE_TOTAL_USER' => $lang['Meeting_sure_total_user'],
			'L_MEETING_STATISTIC' => $lang['Meeting_statistic'],
			'L_MEETING_REMAIN_GUESTS' => $guest_places,
			'L_MEETING_REMAIN_GUESTS_PLACES' => $remain_guest_places,
			'L_MEETING_FREE_GUESTS' => $meeting_free_guests,
			'L_GUESTS' => $lang['Meeting_owm_guests'],
			'L_GUEST_PRENAMES' => $lang['Meeting_prenames'],
			'L_GUEST_NAMES' => $lang['Meeting_names'],

			'SIGNED_ON_EDIT' => $signed_on_edit,
			'SIGNED_OFF' => $signed_off,

			'MEETING_TIME' => $meeting_time,
			'MEETING_UNTIL' => $meeting_until,
			'MEETING_LOCATION' => $meeting_location,
			'MEETING_SUBJECT' => $meeting_subject,
			'MEETING_DESC' => $meeting_desc,
			'MEETING_LINK' => $meeting_link,
			'MEETING_PLACES' => $meeting_places,
			'MEETING_CLOSED_STRING' => $meeting_closed_string,
			'MEETING_SURE_TOTAL' => $meeting_sure_total,
			'MEETING_SURE_TOTAL_USER' => $meeting_sure_total_user,
			'MEETING_SURE_USER' => $meeting_sure_user,
			'MEETING_FREE_PLACES' => $meeting_free_places,
			'MEETING_BY_USER' => $meeting_by_user,
			'MEETING_EDIT_BY_USER' => $meeting_edit_by_user,

			'U_MEETING' => append_sid('meeting.'.$phpEx),
			'U_MEETING_DETAIL' => append_sid('meeting.'.$phpEx.'?mode=detail&amp;m_id=' . $m_id),
			'U_MEETING_USER' => $meeting_user,
			'U_GUEST_POPUP' => $guest_popup,

			'S_REMAIN_GUESTS' => $s_remain_guests,
			'S_HIDDEN_FIELDS' => '<input type="hidden" name="m_id" value="' . $m_id . '"><input type="hidden" name="' . POST_USERS_URL . '" value="' . $userdata['user_id'] . '">',
			'S_ACTION' => append_sid("meeting.$phpEx"))
		);

		$sql = "SELECT m.user_id, m.comment_id, m.meeting_comment, m.meeting_edit_time, m.bbcode_uid, u.username 
			FROM " . MEETING_COMMENT_TABLE . " m, " . USERS_TABLE . " u
			WHERE m.user_id = u.user_id
				AND m.meeting_id = $m_id
			ORDER BY meeting_edit_time";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get meeting data', '', __LINE__, __FILE__, $sql);		}
		
		$meeting_comment_ids = $db->sql_numrows($result);
		$meeting_comment = array();

		while ( $row = $db->sql_fetchrow($result) )
		{
			$comment_id = $row['comment_id'];
			$comment_user = $row['user_id'];
			
			if ( $comment_user == $userdata['user_id'] || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == ADMIN || $userdata['user_level'] == MOD )
			{
				$meeting_edit[] = '<a href="'.append_sid("meeting.$phpEx?mode=edit_comment&amp;m_id=$m_id&amp;comment_user_id=$comment_user&amp;comment_id=$comment_id").'" class="nav"><img src="'.$images['icon_edit'].'" alt="'.$lang['Edit_post'].'"  title="'.$lang['Edit_post'].'" ></a>';
			}
			else 
			{
				$meeting_edit[] = '';
			}

			if ( $userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD || ($userdata['user_level'] == USER && $board_config['allow_user_delete_meeting_comments'] == TRUE && $comment_user == $userdata['user_id']) )
			{
				$meeting_delete[] = '<a href="'.append_sid("meeting.$phpEx?mode=delete_comment&amp;m_id=$m_id&amp;comment_id=$comment_id").'" class="nav"><img src="'.$images['icon_delpost'].'" alt="'.$lang['Delete_post'].'"  title="'.$lang['Delete_post'].'" ></a>';
			}
			else 
			{
				$meeting_delete[] = '';
			}

			$comment_text = $row['meeting_comment'];
			if ( !$board_config['allow_html'] || !$userdata['user_allowhtml'])
			{
				$comment_text = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $comment_text);
			}

			$bbcode_uid = $row['bbcode_uid'];
			if ($bbcode_uid != '')
			{
				$comment_text = ($board_config['allow_bbcode']) ? bbencode_second_pass($comment_text, $bbcode_uid) : preg_replace("/\:$bbcode_uid/si", '', $comment_text);
			}

			$comment_text = make_clickable($comment_text);

			if ( $board_config['allow_smilies'] )
			{
				$comment_text = smilies_pass($comment_text);
			}

			$meeting_comment[] = str_replace("\n", "<br />", stripslashes($comment_text));
			$meeting_comment_user[] = '<a href="'.append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;'.POST_USERS_URL.'='.$comment_user).'" class="nav" target="_blank">'.$row['username'].'</a> ('.create_date($board_config['default_dateformat'], $row['meeting_edit_time'], $board_config['board_timezone']).'):';
		}

		$total_comments = $db->sql_numrows($result);

		$db->sql_freeresult($result);

		if ( $total_comments != 0 )
		{
			$template->assign_block_vars('meeting_comments_title', array(
				'L_MEETING_COMMENT_TITLE' => $lang['Meeting_comments'])
			);

			for ( $i = 0; $i < sizeof($meeting_comment); $i++ )
			{
			    $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

				$template->assign_block_vars('meeting_comments_title.meeting_comments', array(
					'ROW_CLASS' => $row_class,
					'MEETING_COMMENT' => $meeting_comment[$i],
					'MEETING_COMMENT_USER' => $meeting_comment_user[$i],
					'MEETING_EDIT' => $meeting_edit[$i],
					'MEETING_DELETE' => $meeting_delete[$i])
				);
			}
		}

		$template->assign_block_vars('set_comment', array(
			'L_ENTER_COMMENT' => $lang['Meeting_enter_comment'],
			'S_ENTER_COMMENT' => append_sid("meeting.$phpEx?m_id=$m_id&amp;new_comment=1"))
		);

		$template->pparse('body');

		// Include the board footer with phpBB copyright
		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	}
	else
	{
		$mode = 'viewlist';
	}
}

// Get per page value
$per_page = ( $userdata['user_topics_per_page'] == '' ) ? $board_config['topics_per_page'] : $userdata['user_topics_per_page'] ;

// Default entry point for registered users
if (($mode == 'viewlist' || $mode == '') && $userdata['session_logged_in'])
{
	// Load header and templates
	$page_title = $lang['Meeting_viewlist'];
	include($phpbb_root_path.'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'meeting_viewlist_body.tpl')
	);

	// Create the sort and filter fields
	$sort_by_field = '<select name="sort_field">';
	$sort_by_field .= '<option value="meeting_subject">'.$lang['Meeting_subject'].'</option>';
	$sort_by_field .= '<option value="meeting_time">'.$lang['Time'].'</option>';
	$sort_by_field .= '<option value="meeting_until">'.$lang['Meeting_until'].'</option>';
	$sort_by_field .= '<option value="meeting_location">'.$lang['Meeting_location'].'</option>';
	$sort_by_field .= '</select>';
	$sort_by_field = str_replace('value="'.$sort_field.'">', 'value="'.$sort_field.'" selected="selected">', $sort_by_field);

	$sort_by_order = '<select name="sort_order">';
	$sort_by_order .= '<option value="ASC">'.$lang['Sort_Ascending'].'</option>';
	$sort_by_order .= '<option value="DESC">'.$lang['Sort_Descending'].'</option>';
	$sort_by_order .= '</select>';
	$sort_by_order = str_replace('value="'.$sort_order.'">', 'value="'.$sort_order.'" selected="selected">', $sort_by_order);

	$filter_by_field = '<select name="filter_by">';
	$filter_by_field .= '<option value="none">---</option>';
	$filter_by_field .= '<option value="meeting_subject">'.$lang['Meeting_subject'].'</option>';
	$filter_by_field .= '<option value="meeting_location">'.$lang['Meeting_location'].'</option>';
	$filter_by_field .= '</select>';
	$filter_by_field = str_replace('value="'.$filter_by.'">', 'value="'.$filter_by.'" selected="selected">', $filter_by_field);

	$closed_no = $closed_yes = $closed_period = $closed_none = '';

	$sql_closed = ( $sql_filter == '' ) ? ' WHERE ' : ' AND ';
	$current_time = time();

	switch ($closed)
	{
		case 1:
			$sql_closed .= 'meeting_until > ' . $current_time;
			$closed_no = 'checked="checked"';
			break;
		case 2:
			$sql_closed .= 'meeting_until < ' . $current_time;
			$closed_yes = 'checked="checked"';
			break;
		case 3:
			$sql_closed .= 'meeting_until < ' . $current_time . ' AND meeting_time > ' . $current_time;
			$closed_period = 'checked="checked"';
			break;
		case 4:
			$sql_closed = '';
			$closed_none = 'checked="checked"';
			break;
	}

	// Output default values, the sorting and filter values
	$template->assign_vars(array(
		'L_MEETING' => $page_title,
		'L_MEETING_TIME' => $lang['Time'],
		'L_MEETING_UNTIL' => $lang['Meeting_until'],
		'L_MEETING_LOCATION' => $lang['Meeting_location'],
		'L_MEETING_SUBJECT' => $lang['Meeting_subject'],
		'L_MEETING_CLOSED' => $lang['Meeting_closed'],
		'L_MEETING_USERS' => $lang['Meeting_userlist'],
		'L_SORT_BY_FIELD' => $lang['Sort'],
		'L_SORT_BY_ORDER' => $lang['Order'],
		'L_FILTER_BY_FIELD' => $lang['Meeting_filter'],
		'L_GO' => $lang['Submit'],
		'L_CLOSED_NO' => $lang['Meeting_open'],
		'L_CLOSED_YES' => $lang['Meeting_closed'],
		'L_CLOSED_PERIOD' => $lang['Meeting_no_period'],
		'L_CLOSED_NONE' => $lang['Meeting_all'],
		'L_MEETING_SIGNONS' => $lang['Meeting_signons'],

		'SORT_BY_FIELD' => $sort_by_field,
		'SORT_BY_ORDER' => $sort_by_order,
		'FILTER_BY_FIELD' => $filter_by_field,
		'FILTER_FIELD' => $filter,
		'CLOSED_NO' => $closed_no,
		'CLOSED_YES' => $closed_yes,
		'CLOSED_PERIOD' => $closed_period,
		'CLOSED_NONE' => $closed_none,

		'U_MEETING' => append_sid('meeting.'.$phpEx),
		'U_MEETING_SIGNONS' => append_sid('meeting_signons.'.$phpEx),

		'S_ACTION' => append_sid("meeting.$phpEx?mode=sf"))
	);

	// SQL statement to read from a table
	$sql = "SELECT * 
		FROM " . MEETING_DATA_TABLE . "
		$sql_filter
		$sql_closed
		ORDER BY $sort_field $sort_order
		LIMIT $start, $per_page";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not read meeting data", '', __LINE__, __FILE__, $sql);
	}

	$meetingrow = $meeting_users = array();
	$total_meeting = 0;

	while ($row = $db->sql_fetchrow($result))
	{
		if ( $meetings_access_ids[$row['meeting_id']] == TRUE || $userdata['user_level'] == ADMIN )
		{
			$meetingrow[] = $row;
			$total_meeting++;

			$sql_users = "SELECT COUNT(user_id) AS users, SUM(meeting_guests) AS guests 
				FROM " . MEETING_USER_TABLE . "
				WHERE meeting_id = " . $row['meeting_id'] . "
					AND meeting_sure <> 0";
			if ( !$result_users = $db->sql_query($sql_users) )
			{
				message_die(GENERAL_ERROR, 'Could not get meeting data', '', __LINE__, __FILE__, $sql_users);
			}

			$row_users = $db->sql_fetchrow($result_users);
			$db->sql_freeresult($result_users);
			$users_text = '';
			$users = $row_users['users'];
			if ($users)
			{
				$users_text .= $users;
			}

			$guests = $row_users['guests'];
			if ($guests)
			{
				$users_text .= ($guests == 1) ? $lang['Meeting_user_guest'] : sprintf($lang['Meeting_user_guests'], $guests);
			}
				
			$meeting_users[] = ($users_text) ? '<b>' . ($users + $guests) . '</b>' . (($guests) ? ' (' . $users_text . ')' : '') : $lang['Meeting_no_user'];
		}
	}

	$db->sql_freeresult($result);

	if ( $total_meeting != 0 )
	{
		// Cycle a loop through all data
		for($i = 0; $i < $total_meeting; $i++)
		{
			$meeting_check_time = $meetingrow[$i]['meeting_time'];
			$meeting_check_until = $meetingrow[$i]['meeting_until'];

			$meeting_time = create_date($board_config['default_dateformat'], $meetingrow[$i]['meeting_time'], $board_config['board_timezone']);
			$meeting_until = create_date($board_config['default_dateformat'], $meetingrow[$i]['meeting_until'], $board_config['board_timezone']);
			$meeting_location = stripslashes($meetingrow[$i]['meeting_location']);
			$meeting_link = htmlspecialchars($meetingrow[$i]['meeting_link']);
			$meeting_subject = stripslashes($meetingrow[$i]['meeting_subject']);
			$meeting_location = ( $meeting_link != '' ) ? '<a href="'.$meeting_link . '" class="mainmenu">'.$meeting_location.'</a>' : $meeting_location;

			$meeting_guest_overall = $meetingrow[$i]['meeting_guest_overall'];
			$meeting_guest_single = $meetingrow[$i]['meeting_guest_single'];

			$meeting_id = $meetingrow[$i]['meeting_id'];
			$meeting_detail = '<a href="'.append_sid("meeting.$phpEx?mode=detail&amp;m_id=$meeting_id&amp;start=$start").'" class="mainmenu">'.$lang['Meeting_detail'].'</a>';

			$meeting_closed = ( $meeting_check_time < time() ) ? $lang['Yes'] : ( ( $meeting_check_until < time() ) ? $lang['Meeting_no_period'] : $lang['No'] );

			$meeting_edit = ($userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD || ($board_config['allow_user_edit_meeting'] == TRUE && $userdata['user_level'] == USER && $userdata['user_id'] == $meetingrow[$i]['meeting_by_user'])) ? '<a href="'.append_sid("meeting_manage.$phpEx?mode=edit&amp;m_id=$meeting_id").'"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" /></a>' : '';
			$meeting_delete = ($userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD || ($board_config['allow_user_delete_meeting'] == TRUE && $userdata['user_level'] == USER && $userdata['user_id'] == $meetingrow[$i]['meeting_by_user'])) ? '<a href="'.append_sid("meeting_manage.$phpEx?mode=delete&amp;m_id=$meeting_id").'"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete']. '" /></a>' : '';

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			// Output the values
			$template->assign_block_vars('meeting_overview_row', array(
				'ROW_CLASS' => $row_class,
				'MEETING_USERS' => $meeting_users[$i],
				'MEETING_TIME' => $meeting_time,
				'MEETING_UNTIL' => $meeting_until,
				'MEETING_LOCATION' => $meeting_location,
				'MEETING_SUBJECT' => $meeting_subject,
				'MEETING_CLOSED' => $meeting_closed,
				'MEETING_EDIT' => $meeting_edit,
				'MEETING_DELETE' => $meeting_delete,
				'MEETING_DETAIL' => $meeting_detail)
			);
		}

		// Create the pagination
		$sql = "SELECT * 
			FROM " . MEETING_DATA_TABLE;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not get meeting data for pagination", '', __LINE__, __FILE__, $sql);
		}

		$total_meetings = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			if ( $meetings_access_ids[$row['meeting_id']] == TRUE || $userdata['user_level'] == ADMIN )
			{
				$total_meetings++;
			}
		}
		$db->sql_freeresult($result);

		if ( $total_meetings != 0 )
		{
			$pagination = generate_pagination("meeting.$phpEx?mode=viewlist&sort_field=$sort_field&sort_order=$sort_order&filter_by=$filter_by&filter=$filter", $total_meetings, $per_page, $start);

			$template->assign_vars(array(
				'PAGINATION' => $pagination)
			);

		}
	}
	else
	{
		// Output message if no meeting was found
		$template->assign_block_vars('no_meeting_row', array(
			'L_NO_MEETING' => $lang['No_meeting'])
		);
	}

	if ($board_config['allow_user_enter_meeting'] == 1 || $userdata['user_level'] == ADMIN || $userdata['user_level'] == MOD)
	{
		$allow_user_enter_meeting = 1;
	}
	else if ($board_config['allow_user_enter_meeting'] == 2 && $userdata['user_level'] != ADMIN)
	{
		$sql = "SELECT g.group_id FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
			WHERE g.group_single_user <> " . TRUE . "
				AND ug.group_id = g.group_id
				AND ug.user_pending <> " . TRUE . "
				AND g.allow_create_meeting = " . TRUE . "
				AND ug.user_id = " . $userdata['user_id'];
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not fetch meeting permissions', '', __LINE__, __FILE__, $sql);
		}

		$count_groups = $db->sql_numrows($result);
		$db->sql_freeresult($result);

		if ($count_groups > 0)
		{
			$allow_user_enter_meeting = 1;
		}
		else
		{
			$allow_user_enter_meeting = 0;
		}
	}
	else
	{
		$allow_user_enter_meeting = 0;
	}

	if ($allow_user_enter_meeting == 1)
	{
		$template->assign_block_vars('user_enter_on', array(
			'L_NEW_MEETING' => $lang['Meeting_add_new'],
			'U_NEW_MEETING' => append_sid('meeting_manage.'.$phpEx.'?mode=add_new&amp;start='.$start))
		);
	}
	
	// Parse and display the page
	$template->pparse('body');

	// Include the board footer with phpBB copyright
	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}

// Default entry point for guests
if (($mode == 'viewlist' || $mode == '') && !$userdata['session_logged_in'])
{
	// Load header and templates
	$page_title = $lang['Meeting_viewlist'];
	include($phpbb_root_path.'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'meeting_viewlist_guest_body.tpl')
	);

	// Output default values, the sorting and filter values
	$template->assign_vars(array(
		'L_MEETING' => $page_title,
		'L_MEETING_TIME' => $lang['Time'],
		'L_MEETING_UNTIL' => $lang['Meeting_until'],
		'L_MEETING_LOCATION' => $lang['Meeting_location'],
		'L_MEETING_SUBJECT' => $lang['Meeting_subject'],

		'U_MEETING' => append_sid('meeting.'.$phpEx))
	);

	// SQL statement to read from a table
	$sql = "SELECT * FROM " . MEETING_DATA_TABLE . "
		WHERE meeting_until > " . time() . "
		ORDER BY meeting_time DESC
		LIMIT $start, $per_page";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not read meeting data", '', __LINE__, __FILE__, $sql);
	}

	$meetingrow = $meeting_users = array();
	$total_meeting = 0;

	while ($row = $db->sql_fetchrow($result))
	{
		if ( $meetings_access_ids[$row['meeting_id']] == TRUE )
		{
			$meetingrow[] = $row;
			$total_meeting++;
		}
	}

	$db->sql_freeresult($result);

	if ( $total_meeting != 0 )
	{
		// Cycle a loop through all data
		for($i = 0; $i < $total_meeting; $i++)
		{
			$meeting_check_time = $meetingrow[$i]['meeting_time'];
			$meeting_check_until = $meetingrow[$i]['meeting_until'];

			$meeting_time = create_date($board_config['default_dateformat'], $meetingrow[$i]['meeting_time'], $board_config['board_timezone']);
			$meeting_until = create_date($board_config['default_dateformat'], $meetingrow[$i]['meeting_until'], $board_config['board_timezone']);
			$meeting_location = stripslashes($meetingrow[$i]['meeting_location']);
			$meeting_subject = stripslashes($meetingrow[$i]['meeting_subject']);

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			// Output the values
			$template->assign_block_vars('meeting_overview_row', array(
				'ROW_CLASS' => $row_class,
				'MEETING_TIME' => $meeting_time,
				'MEETING_UNTIL' => $meeting_until,
				'MEETING_LOCATION' => $meeting_location,
				'MEETING_SUBJECT' => $meeting_subject,
				'MEETING_DETAIL' => $lang['Meeting_only_registered'])
			);
		}

		// Create the pagination
		$sql = "SELECT * FROM " . MEETING_DATA_TABLE;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not get meeting data for pagination", '', __LINE__, __FILE__, $sql);
		}

		$total_meetings = 0;
		while ($row = $db->sql_fetchrow($result))
		{
			if ( $meetings_access_ids[$row['meeting_id']] == TRUE )
			{
				$total_meetings++;
			}
		}
		$db->sql_freeresult($result);

		if ( $total_meetings != 0 )
		{
			$pagination = generate_pagination("meeting.$phpEx?mode=viewlist", $total_meetings, $per_page, $start);

			$template->assign_vars(array(
				'PAGINATION' => $pagination)
			);

		}
	}
	else
	{
		// Output message if no meeting was found
		$template->assign_block_vars('no_meeting_row', array(
			'L_NO_MEETING' => $lang['No_meeting'])
		);
	}

	//
	// Force password update
	//
	if ($board_config['password_update_days'])
	{
		include($phpbb_root_path . 'includes/update_password.'.$phpEx);
	}

	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}

?>
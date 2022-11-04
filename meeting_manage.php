<?php
/** 
 *
* @package phpBB
* @version $Id: meeting_manage.php,v 1.3.18 2006/08/09 oxpus Exp $
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

if ($board_config['allow_user_enter_meeting'] == 1 || $userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD)
{
	$allow_add = 1;
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
		$allow_add = 1;
	}
	else
	{
		$allow_add = 0;
	}
}
else
{
	$allow_add = 0;
}

$allow_edit = (($board_config['allow_user_edit_meeting'] == TRUE && $userdata['user_level'] == USER) || $userdata['user_level'] == ADMIN || $userdata['user_level'] == MOD) ? TRUE : FALSE;
$allow_delete = (($board_config['allow_user_delete_meeting'] == TRUE && $userdata['user_level'] == USER) || $userdata['user_level'] == ADMIN || $userdata['user_level'] == MOD) ? TRUE : FALSE;

if (!$allow_add && !$allow_edit && !$allow_delete)
{
	redirect(append_sid("meeting.$phpEx"));
}

// Check and set various parameters
$params = array(
	'mode' => 'mode',
	'submit' => 'submit',
	'cancel' => 'cancel',
	'start' => 'start',
	'm_id' => 'm_id',
	'sf' => 'sf',
	'group_id' => 'group_id',
	'meeting_time' => 'meeting_time',
	'meeting_until' => 'meeting_until',
	'meeting_location' => 'meeting_location',
	'meeting_subject' => 'meeting_subject',
	'meeting_desc' => 'meeting_desc',
	'meeting_link' => 'meeting_link',
	'meeting_places' => 'meeting_places',
	'meeting_start_value' => 'meeting_start_value',
	'meeting_recure_value' => 'meeting_recure_value',
	'meeting_notify' => 'meeting_notify',
	'meeting_guest_overall' => 'meeting_guest_overall',
	'meeting_guest_single' => 'meeting_guest_single',
	'meeting_guest_names' => 'meeting_guest_names',
	'confirm' => 'confirm',
	'edit' => 'edit',
	'sort_field' => 'sort_field',
	'sort_order' => 'sort_order',
	'filter' => 'filter',
	'filter_by' => 'filter_by',
	'closed' => 'closed',
	'm_day' => 'm_day',
	'm_month' => 'm_month',
	'm_year' => 'm_year',
	'm_hour' => 'm_hour',
	'm_minute' => 'm_minute',
	'u_day' => 'u_day',
	'u_month' => 'u_month',
	'u_year' => 'u_year',
	'u_hour' => 'u_hour',
	'u_minute' => 'u_minute'
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

if ($meeting_desc == '')
{
	$meeting_desc = ( !empty($HTTP_POST_VARS['message']) ) ? $HTTP_POST_VARS['message'] : '';
}

// Check some values
$m_id = intval($m_id);
$start = intval($start);

// What shall we do on cancel a deleting
if ( $mode == 'Cancel' || $cancel )
{
	$submit = '';
	$cancel = '';
	$confirm = '';
	$mode = '';
}

if ( $mode == 'smilies' )
{
	generate_smilies('window', PAGE_POSTING);
	exit;
}

$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_meeting.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_meeting.' . $phpEx);

// What shall we do on cancel a deleting
if ( ($mode == 'delete' || $delete) && $allow_delete )
{
	if (!$confirm)
	{
		// Load header and templates
		$page_title = $lang['Meeting'];
		include($phpbb_root_path.'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'body' => 'confirm_body.tpl')
		);

		$s_hidden_fields = '<input type="hidden" name="m_id" value="'.$m_id.'">';
		$s_hidden_fields .= '<input type="hidden" name="mode" value="delete">';

		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Meeting_delete'],
			'MESSAGE_TEXT' => $lang['Meeting_delete_explain'],
			
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],
				
			'S_CONFIRM_ACTION' => append_sid("meeting_manage.$phpEx"),
			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('body');

		include($phpbb_root_path.'includes/page_tail.'.$phpEx);
	}

	$sql = "DELETE FROM " . MEETING_COMMENT_TABLE . " 
		WHERE meeting_id = $m_id";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete meeting data', '', __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . MEETING_DATA_TABLE . " 
		WHERE meeting_id = $m_id";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete meeting data', '', __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . MEETING_USER_TABLE . " 
		WHERE meeting_id = $m_id";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete meeting user', '', __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . MEETING_USERGROUP_TABLE . " 	
		WHERE meeting_id = $m_id";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete meeting usergroup', '', __LINE__, __FILE__, $sql);
	}

	$sql = "DELETE FROM " . MEETING_GUESTNAMES_TABLE . " 
		WHERE meeting_id = $m_id";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete meeting guestnames', '', __LINE__, __FILE__, $sql);
	}

	$mode = '';
}

// Saving a new meeting. Welcome :-)
if ( $mode == 'submit' || $submit )
{
	$meeting_time = mktime($m_hour, $m_minute, 0, $m_month, $m_day, $m_year, date('I'));
	$meeting_until = mktime($u_hour, $u_minute, 0, $u_month, $u_day, $u_year, date('I'));
	$meeting_location = str_replace("\'", "''", trim($meeting_location));
	$meeting_subject = str_replace("\'", "''", trim($meeting_subject));
	$meeting_desc = str_replace("\'", "''", trim($meeting_desc));
	$meeting_link = htmlspecialchars(trim($meeting_link));
	$meeting_places = intval($meeting_places);
	$meeting_start_value = intval($meeting_start_value);
	$meeting_recure_value = intval($meeting_recure_value);
	$meeting_notify = intval($meeting_notify);
	
	$meeting_guest_overall = intval($meeting_guest_overall);
	$meeting_guest_single = intval($meeting_guest_single);
	$meeting_guest_names = intval($meeting_guest_names);

	$meeting_until = ( $meeting_until > $meeting_time ) ? $meeting_time : $meeting_until;

	$bbcode_on = ($board_config['allow_bbcode']) ? 1 : 0;
	$bbcode_uid = ($board_config['allow_bbcode']) ? make_bbcode_uid() : '';
	$html_on = ($board_config['allow_html'] && $userdata['user_allowhtml']) ? 1 : 0;
	$smiles_on = ($board_config['allow_smilie']) ? 1 : 0;
	
	$meeting_desc = prepare_message($meeting_desc, $html_on, $bbcode_on, $smiles_on, $bbcode_uid);

	if ( $m_id == '' )
	{
		$sql = "SELECT MAX(meeting_id) AS max_id 
			FROM " . MEETING_DATA_TABLE;
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get last id number', '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$next_id = $row['max_id'];
		}

		$db->sql_freeresult($result);

		$next_id++;
	}
	else
	{
		$sql = "DELETE FROM " . MEETING_USERGROUP_TABLE . "
			WHERE meeting_id = $m_id";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not delete old usergroups', '', __LINE__, __FILE__, $sql);
		}
	}

	$next_id = ( $m_id != '' ) ? $m_id : $next_id;

	if ( $group_id[0] == -1 && $meeting_places == 0 )
	{
		$sql = "SELECT COUNT(user_id) AS total_users 
			FROM " . USERS_TABLE;
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not count maximum user places', '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$meeting_places = $row['total_users'];
		}
		$db->sql_freeresult($result);
	}

	if ( $group_id[0] != -1 )
	{
		$usergroups = '';
		$sql_usergroups = '';

		$usergroups = ( count($group_id) == 1 ) ? $group_id[0] : implode(',', $group_id);
		$sql_usergroups = ' AND g.group_id IN ('.$usergroups.')';

		$sql = "SELECT COUNT(DISTINCT ug.user_id) AS total_users 
			FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
			WHERE ug.group_id = g.group_id
				AND g.group_single_user <> " . TRUE . "
				AND ug.user_pending <> ".TRUE . "
			$sql_usergroups";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not count maximum user places', '', __LINE__, __FILE__, $sql);
		}

		while ( $row = $db->sql_fetchrow($result) )
		{
			$places = $row['total_users'];
		}
		$db->sql_freeresult($result);

		$meeting_places = ( $places < $meeting_places || $meeting_places == 0 ) ? $places : $meeting_places;
	}

	if ( count($group_id) != 0 && $group_id[0] != -1 )
	{
		for ( $i = 0; $i < count($group_id); $i++ )
		{
			$gid = $group_id[$i];
			$sql = "INSERT INTO " . MEETING_USERGROUP_TABLE . " (meeting_id, meeting_group) 
				VALUES ($next_id, $gid)";
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not save meeting usergroup', '', __LINE__, __FILE__, $sql);
			}
		}
	}
	else if ( $group_id[0] == -1 )
	{
		$gid = -1;
		$sql = "INSERT INTO " . MEETING_USERGROUP_TABLE . " (meeting_id, meeting_group) 
			VALUES ($next_id, $gid)";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not save meeting usergroup', '', __LINE__, __FILE__, $sql);
		}
	}
		
	$meeting_time = ( $meeting_time == '' ) ? 0 : $meeting_time;
	$meeting_until = ( $meeting_until == '' ) ? 0 : $meeting_until;

	if ( $m_id == '' )
	{
		$sql = "INSERT INTO " . MEETING_DATA_TABLE . " (meeting_id, meeting_time, meeting_until, meeting_location, meeting_subject, meeting_desc, meeting_link, meeting_places, meeting_by_user, meeting_edit_by_user, meeting_start_value, meeting_recure_value, meeting_notify, meeting_guest_overall, meeting_guest_single, meeting_guest_names, bbcode_uid)
			VALUES ($next_id, $meeting_time, $meeting_until, '$meeting_location', '$meeting_subject', '$meeting_desc', '$meeting_link', $meeting_places, " . $userdata['user_id'] . ", " . $userdata['user_id'] . ", $meeting_start_value, $meeting_recure_value, $meeting_notify, $meeting_guest_overall, $meeting_guest_single, $meeting_guest_names, '$bbcode_uid')";
	}
	else
	{
		$sql = "UPDATE " . MEETING_DATA_TABLE . " SET
				meeting_time = $meeting_time,
				meeting_until = $meeting_until,
				meeting_location = '$meeting_location',
				meeting_subject = '$meeting_subject',
				meeting_desc = '$meeting_desc',
				meeting_link = '$meeting_link',
				meeting_places = $meeting_places,
				meeting_edit_by_user = " . $userdata['user_id'] . ",
				meeting_start_value = $meeting_start_value,
				meeting_recure_value = $meeting_recure_value,
				meeting_notify = $meeting_notify,
				meeting_guest_overall = $meeting_guest_overall,
				meeting_guest_single = $meeting_guest_single,
				meeting_guest_names = $meeting_guest_names,
				bbcode_uid = '$bbcode_uid'
			WHERE meeting_id = $m_id";
	}

	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not save meeting data', '', __LINE__, __FILE__, $sql);
	}
}

// Entering a new meeting
if ( $mode == 'add_new' || $mode == 'edit')
{
	$sql = "SELECT group_id, group_name FROM " . GROUPS_TABLE . "
		WHERE group_single_user <> " . TRUE . "
		ORDER BY group_name";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not get usergroups', '', __LINE__, __FILE__, $sql);
	}

	$usergroups = array();

	$meeting_usergroup = '<select name="group_id[]" multiple="multiple">';
	$meeting_usergroup .= '<option value="-1"' .(($mode=='add_new') ? 'selected="selected"' : ''). '>'.$lang['Meeting_all_users'].'</option>';

	while ( $row = $db->sql_fetchrow($result) )
	{
		$meeting_usergroup .= '<option value="'.($row['group_id']).'">'.$lang['Meeting_group'].' '.$row['group_name'].'</option>';
		$usergroups[] = $row['group_id'];
	}

	$meeting_usergroup .= '</select>';

	$db->sql_freeresult($result);

	$m_day = '<select name="m_day">';
	$u_day = '<select name="u_day">';

	for ( $i = 1; $i <= 31; $i++ )
	{
		$null = ( $i < 10 ) ? '0' : '';
		$m_day .= '<option value="'.$null.$i.'">'.$i.'</option>';
		$u_day .= '<option value="'.$null.$i.'">'.$i.'</option>';
	}

	$m_day .= '</select>';
	$u_day .= '</select>';

	$m_month = '<select name="m_month">';
	$u_month = '<select name="u_month">';

	for ( $i = 1; $i <= 12; $i++ )
	{
		$null = ( $i < 10 ) ? '0' : '';
		$m_month .= '<option value="'.$null.$i.'">'.$i.'</option>';
		$u_month .= '<option value="'.$null.$i.'">'.$i.'</option>';
	}

	$m_month .= '</select>';
	$u_month .= '</select>';

	$m_hour = '<select name="m_hour">';
	$u_hour = '<select name="u_hour">';

	for ( $i = 0; $i < 24; $i++ )
	{
		$null = ( $i < 10 ) ? '0' : '';
		$m_hour .= '<option value="'.$null.$i.'">'.$i.'</option>';
		$u_hour .= '<option value="'.$null.$i.'">'.$i.'</option>';
	}

	$m_hour .= '</select>';
	$u_hour .= '</select>';

	$m_minute = '<select name="m_minute">';
	$u_minute = '<select name="u_minute">';

	for ( $i = 0; $i < 60; $i++ )
	{
		$null = ( $i < 10 ) ? '0' : '';
		$m_minute .= '<option value="'.$null.$i.'">'.$i.'</option>';
		$u_minute .= '<option value="'.$null.$i.'">'.$i.'</option>';
	}

	$m_minute .= '</select>';
	$u_minute .= '</select>';

	if ($mode == 'add_new' && $allow_add)
	{
		$m_id = $meeting_location = $meeting_subject = $meeting_desc = $meeting_link_new = $meeting_guest_names_yes = '';
		$meeting_places = $meeting_start_value = $meeting_guest_overall = $meeting_guest_single = 0;
		$meeting_time = $meeting_until = time();
		$meeting_recure_value = 5;		$meeting_guest_names_no = 'checked="checked"';
		$meeting_by_user = sprintf($lang['Meeting_create_by'], append_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL."=".$userdata['user_id']), $userdata['username']);
	}
	else if ($mode == 'edit')
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
			$meeting_location = htmlspecialchars($row['meeting_location']);
			$meeting_subject = htmlspecialchars($row['meeting_subject']);
			$meeting_desc = htmlspecialchars($row['meeting_desc']);
			$meeting_link_new = $row['meeting_link'];
			$meeting_places = $row['meeting_places'];
			$meeting_by_username = $row['create_username'];
			$meeting_by_user_id = append_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL."=".$row['create_user_id']);
			$meeting_edit_by_username = $row['edit_username'];
			$meeting_edit_by_user_id = append_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL."=".$row['edit_user_id']);
			$meeting_start_value = $row['meeting_start_value'];
			$meeting_recure_value = $row['meeting_recure_value'];
			$meeting_notify = $row['meeting_notify'];
			$meeting_guest_overall = $row['meeting_guest_overall'];
			$meeting_guest_single = $row['meeting_guest_single'];
			$meeting_guest_names_yes = ($row['meeting_guest_names']) ? 'checked="checked"' : '';
			$meeting_guest_names_no = (!$row['meeting_guest_names']) ? 'checked="checked"' : '';
			$bbcode_uid = $row['bbcode_uid'];
		}

		$db->sql_freeresult($result);

		if ( $bbcode_uid != '' )
		{
			$meeting_desc = preg_replace('/\:(([a-z0-9]:)?)' . $bbcode_uid . '/s', '', $meeting_desc);
		}

		$meeting_desc = str_replace('<', '&lt;', $meeting_desc);
		$meeting_desc = str_replace('>', '&gt;', $meeting_desc);
		$meeting_desc = str_replace('<br />', "\n", $meeting_desc);

		if ($userdata['user_id'] == ANONYMOUS || ($userdata['user_level'] != ADMIN && !$allow_edit && $row['create_user_id'] != $userdata['user_id']))
		{
			redirect(append_sid('meeting.'.$phpEx));
		}

		$meeting_by_user = sprintf($lang['Meeting_create_by'], $meeting_by_user_id, $meeting_by_username);
		$meeting_edit_by_user = sprintf($lang['Meeting_edit_by'], $meeting_edit_by_user_id, $meeting_edit_by_username);

		$sql = "SELECT meeting_group 
			FROM " . MEETING_USERGROUP_TABLE . "
			WHERE meeting_id = $m_id
				AND meeting_group <> -1";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get group data', '', __LINE__, __FILE__, $sql);
		}

		$total_saved_groups = $db->sql_numrows($result);
		
		if ( $total_saved_groups == 0 )
		{
			$meeting_usergroup = str_replace('value="-1">', 'value="-1" selected="selected">', $meeting_usergroup);
		}
		else
		{		
			while ( $row = $db->sql_fetchrow($result) )
			{
				if ( in_array($row['meeting_group'], $usergroups ) )
				{
					$meeting_usergroup = str_replace('value="'.($row['meeting_group']).'">', 'value="'.($row['meeting_group']).'" selected="selected">', $meeting_usergroup);
				}
			}
		}
		$db->sql_freeresult($result);
	}

	$mday = create_date('d', $meeting_time, $board_config['board_timezone']);
	$mmonth = create_date('m', $meeting_time, $board_config['board_timezone']);
	$mhour = create_date('H', $meeting_time, $board_config['board_timezone']);
	$mminute = create_date('i', $meeting_time, $board_config['board_timezone']);

	$uday = create_date('d', $meeting_until, $board_config['board_timezone']);
	$umonth = create_date('m', $meeting_until, $board_config['board_timezone']);
	$uhour = create_date('H', $meeting_until, $board_config['board_timezone']);
	$uminute = create_date('i', $meeting_until, $board_config['board_timezone']);

	$m_year = create_date('Y', $meeting_time, $board_config['board_timezone']);
	$u_year = create_date('Y', $meeting_until, $board_config['board_timezone']);

	$m_day = str_replace('value="'.$mday.'">', 'value="'.$mday.'" selected="selected">', $m_day);
	$m_month = str_replace('value="'.$mmonth.'">', 'value="'.$mmonth.'" selected="selected">', $m_month);
	$m_hour = str_replace('value="'.$mhour.'">', 'value="'.$mhour.'" selected="selected">', $m_hour);
	$m_minute = str_replace('value="'.$mminute.'">', 'value="'.$mminute.'" selected="selected">', $m_minute);

	$u_day = str_replace('value="'.$uday.'">', 'value="'.$uday.'" selected="selected">', $u_day);
	$u_month = str_replace('value="'.$umonth.'">', 'value="'.$umonth.'" selected="selected">', $u_month);
	$u_hour = str_replace('value="'.$uhour.'">', 'value="'.$uhour.'" selected="selected">', $u_hour);
	$u_minute = str_replace('value="'.$uminute.'">', 'value="'.$uminute.'" selected="selected">', $u_minute);

	$page_title = $lang['Meeting'];
	include($phpbb_root_path.'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'meeting_edit_body.tpl')
	);

	if ($userdata['user_level'] > 0)
	{
		$template->assign_block_vars('enable_edit_meeting', array());
	}

	$template->assign_vars(array(
		'L_MEETING' => ( $mode == 'add_new' ) ? $lang['Meeting_add'] : $lang['Meeting_edit'],
		'L_MEETING_TIME' => $lang['Time'],
		'L_MEETING_UNTIL' => $lang['Meeting_until'],
		'L_MEETING_LOCATION' => $lang['Meeting_location'],
		'L_MEETING_SUBJECT' => $lang['Meeting_subject'],
		'L_MEETING_DESC' => $lang['Meeting_desc'],
		'L_MEETING_LINK' => $lang['Meeting_link'],
		'L_MEETING_PLACES' => $lang['Meeting_places'],
		'L_MEETING_USERGROUP' => $lang['Meeting_usergroup'],
		'L_MEETING_START_VALUE' => $lang['Meeting_start_value'],
		'L_MEETING_RECURE_VALUE' => $lang['Meeting_recure_value'],
		'L_MEETING_NOTIFY' => $lang['Meeting_notify'],
		'L_MEETING_GUEST_OVERALL' => $lang['Meeting_guest_overall'], 
		'L_MEETING_GUEST_SINGLE' => $lang['Meeting_guest_single'], 
		'L_MEETING_NO_GUEST_LIMIT' => $lang['Meeting_no_guest_limit'], 
		'L_MEETING_INTERVALL_EXPLAIN' => $lang['Meeting_intervall_explain'],
		'L_MEETING_GUEST_NAMES' => $lang['Meeting_guest_names'],
		'L_MEETING_GUEST_NAMES_EXPLAIN' => $lang['Meeting_guest_names_explain'],

		'L_SUBMIT' => $lang['Submit'],
		'L_CANCEL' => $lang['Cancel'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],

		'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
		'L_SMILIES' => $lang['Emoticons'],

		'L_FONT_COLOR' => $lang['Font_color'], 
		'L_COLOR_DEFAULT' => $lang['color_default'], 
		'L_COLOR_DARK_RED' => $lang['color_dark_red'], 
		'L_COLOR_RED' => $lang['color_red'], 
		'L_COLOR_ORANGE' => $lang['color_orange'], 
		'L_COLOR_BROWN' => $lang['color_brown'], 
		'L_COLOR_YELLOW' => $lang['color_yellow'], 
		'L_COLOR_GREEN' => $lang['color_green'], 
		'L_COLOR_OLIVE' => $lang['color_olive'], 
		'L_COLOR_CYAN' => $lang['color_cyan'], 
		'L_COLOR_BLUE' => $lang['color_blue'], 
		'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'], 
		'L_COLOR_INDIGO' => $lang['color_indigo'], 
		'L_COLOR_VIOLET' => $lang['color_violet'], 
		'L_COLOR_WHITE' => $lang['color_white'], 
		'L_COLOR_BLACK' => $lang['color_black'], 

		'L_FONT_SIZE' => $lang['Font_size'], 
		'L_FONT_TINY' => $lang['font_tiny'], 
		'L_FONT_SMALL' => $lang['font_small'], 
		'L_FONT_NORMAL' => $lang['font_normal'], 
		'L_FONT_LARGE' => $lang['font_large'], 
		'L_FONT_HUGE' => $lang['font_huge'], 

		'MEETING_DAY' => $m_day,
		'MEETING_MONTH' => $m_month,
		'MEETING_YEAR' => $m_year,
		'MEETING_HOUR' => $m_hour,
		'MEETING_MINUTE' => $m_minute,
		'MEETING_DAY_UNTIL' => $u_day,
		'MEETING_MONTH_UNTIL' => $u_month,
		'MEETING_YEAR_UNTIL' => $u_year,
		'MEETING_HOUR_UNTIL' => $u_hour,
		'MEETING_MINUTE_UNTIL' => $u_minute,
		'MEETING_LOCATION' => $meeting_location,
		'MEETING_SUBJECT' => $meeting_subject,
		'MEETING_DESC' => $meeting_desc,
		'MEETING_LINK' => $meeting_link_new,
		'MEETING_PLACES' => $meeting_places,
		'MEETING_USERGROUP' => $meeting_usergroup,
		'MEETING_BY_USER' => $meeting_by_user,
		'MEETING_EDIT_BY_USER' => $meeting_edit_by_user,
		'MEETING_START_VALUE' => $meeting_start_value,
		'MEETING_RECURE_VALUE' => $meeting_recure_value,
		'MEETING_NOTIFY_YES' => ($meeting_notify) ? 'checked="checked"' : '',
		'MEETING_NOTIFY_NO' => (!$meeting_notify) ? 'checked="checked"' : '',
		'MEETING_GUEST_OVERALL' => $meeting_guest_overall,
		'MEETING_GUEST_SINGLE' => $meeting_guest_single,
		'MEETING_GUEST_NAMES_YES' => $meeting_guest_names_yes,
		'MEETING_GUEST_NAMES_NO' => $meeting_guest_names_no,

		'S_HIDDEN_FIELDS' => (( $mode == 'edit' && $allow_edit) ? '<input type="hidden" name="m_id" value="'.$m_id.'">' : '') . '<input type="hidden" name="mode" value="submit">',
		'S_ACTION' => append_sid("meeting_manage.$phpEx"),

		'U_SMILIES' => append_sid("meeting_manage.$phpEx?mode=smilies"))
	);

	//
	// Force password update
	//
	if ($board_config['password_update_days'])
	{
		include($phpbb_root_path . 'includes/update_password.'.$phpEx);
	}

	$template->pparse('body');

	// Include the board footer with phpBB copyright
	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}

redirect(append_sid('meeting.'.$phpEx.'?start='.$start, true));

?>

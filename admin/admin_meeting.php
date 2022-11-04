<?php
/** 
*
* @package admin
* @version $Id: admin_meeting.php,v 1.3.18 2006/08/09 17:49:33 oxpus Exp $
* @copyright (c) 2004 OXPUS
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
// Initiate module
define('IN_PHPBB', true);
define('IN_MEETING', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Meetings']['Add_new'] = $filename.'?mode=add_new';
	$module['Meetings']['Management'] = $filename.'?mode=manage';
//	$module['Meetings']['Configuration'] = $filename.'?mode=config';
	return;
}

// Let's set the root dir for phpBB
$phpbb_root_path = '../';
$no_page_header = TRUE;
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

include($phpbb_root_path.'language/lang_'.$board_config['default_lang'].'/lang_meeting.'.$phpEx);

// Check and set various parameters
$params = array(
	'cancel' => 'cancel',
	'closed' => 'closed',
	'config' => 'config',
	'confirm' => 'confirm',
	'delete' => 'delete',
	'edit' => 'edit',
	'filter' => 'filter',
	'filter_by' => 'filter_by',
	'group_id' => 'group_id',
	'm_id' => 'm_id',
	'm_day' => 'm_day',
	'm_hour' => 'm_hour',
	'm_minute' => 'm_minute',
	'm_month' => 'm_month',
	'm_year' => 'm_year',
	'meeting_desc' => 'meeting_desc',
	'meeting_link' => 'meeting_link',
	'meeting_location' => 'meeting_location',
	'meeting_places' => 'meeting_places',
	'meeting_subject' => 'meeting_subject',
	'meeting_time' => 'meeting_time',
	'meeting_until' => 'meeting_until',
	'meeting_start_value' => 'meeting_start_value',
	'meeting_recure_value' => 'meeting_recure_value',
	'meeting_notify' => 'meeting_notify',
	'meeting_guest_overall' => 'meeting_guest_overall',
	'meeting_guest_single' => 'meeting_guest_single',
	'meeting_guest_names' => 'meeting_guest_names',
	'mode' => 'mode',
	'sf' => 'sf',
	'sort_field' => 'sort_field',
	'sort_order' => 'sort_order',
	'start' => 'start',
	'submit' => 'submit',
	'submit_config' => 'submit_config',
	'u_day' => 'u_day',
	'u_hour' => 'u_hour',
	'u_minute' => 'u_minute',
	'u_month' => 'u_month',
	'u_year' => 'u_year'
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

// Prepare sorting and filter variables
$sort_field = ( $sort_field == '' ) ? 'meeting_time' : $sort_field;
$sort_order = ( $sort_order == '' ) ? 'DESC' : $sort_order;
$filter_by = ( $filter_by == '' ) ? 'none' : $filter_by;
$sql_filter = ( $filter_by == 'none' ) ? '' : ( ( $filter != '' ) ? " WHERE $filter_by LIKE ('$filter')" : '' );
$closed = ( $closed == '' ) ? 4 : $closed;

// Set start point for meeting overview list
$start = ( $start == '' ) ? 0 : $start;

// Crash code for submitting a sort and/or filter request
if ( $mode == 'sf' )
{
	$mode = 'manage';
	$submit = '';
}

// What shall we do on cancel a deleting
if ( $mode == 'cancel' || $cancel )
{
	$submit = $cancel = $confirm = '';
	$mode = 'manage';
}

$mode = ($config) ? $mode == 'config' : $mode;

if ( $mode == 'smilies' )
{
	$smilies_path = $board_config['smilies_path'];
	$board_config['smilies_path'] = './../'.$board_config['smilies_path'];

	generate_smilies('window', PAGE_FORUM_TOUR);

	$board_config['smilies_path'] = $smilies_path;
	exit;
}

include('./page_header_admin.'.$phpEx);

// Please confirm the deleting. Better way.
if ( $mode == 'delete' )
{
	$s_hidden_fields = '<input type="hidden" name="m_id" value="'.$m_id.'"><input type="hidden" name="start" value="'.$start.'">';

	$template->set_filenames(array(
		'body' => 'admin/confirm_body.tpl')
	);

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Meeting_delete'],
		'MESSAGE_TEXT' => $lang['Meeting_delete_explain'],
		
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
			
		'S_CONFIRM_ACTION' => append_sid("admin_meeting.$phpEx"),
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);

	$template->pparse('body');

	include('./page_footer_admin.'.$phpEx);
}

// Now we will delete. Good bye :-)
if ( $mode == 'confirm' || $confirm )
{
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

	$mode = 'manage';
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
		$sql = "SELECT max(meeting_id) AS max_id 
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
			$gid = intval($group_id[$i]);
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
		$sql = "UPDATE " . MEETING_DATA_TABLE . " 
			SET meeting_time = $meeting_time,
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

	$mode = 'manage';
}

// Enter the configuration
if ($mode == 'config' || $config)
{
	$sql = "SELECT *
		FROM " . MEETING_CONFIG_TABLE;
	if(!$result = $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR, "Could not query config information for meeting configurations", "", __LINE__, __FILE__, $sql);
	}
	else
	{
		while( $row = $db->sql_fetchrow($result) )
		{
			$config_name = $row['config_name'];
			$config_value = $row['config_value'];
			$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;

			$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

			if( isset($HTTP_POST_VARS['submit_config']) )
			{
				$sql = "UPDATE " . MEETING_CONFIG_TABLE . " SET
					config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
					WHERE config_name = '$config_name'";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Failed to update meeting configuration for $config_name", "", __LINE__, __FILE__, $sql);
				}
			}
		}
		$db->sql_freeresult($result);

		if (isset($HTTP_POST_VARS['submit_config']) && sizeof($group_id) > 0)
		{
			$sql = "UPDATE " . GROUPS_TABLE . "
				SET allow_create_meeting = 0";
			if (!($db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not set usergroup status', '', __LINE__, __FILE__, $sql);
			}

			for ( $i = 0; $i < sizeof($group_id); $i++ )
			{
				$gid = intval($group_id[$i]);
				$sql = "UPDATE " . GROUPS_TABLE . "
					SET allow_create_meeting = 1
					WHERE group_id = $gid";
				if (!($db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, 'Could not set usergroup status', '', __LINE__, __FILE__, $sql);
				}
			}
		}

		if( isset($HTTP_POST_VARS['submit_config']) )
		{
			$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_meeting.$phpEx?mode=config") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
	}

	$users_allow_enter_meeting_group = ( $new['allow_user_enter_meeting'] == 2 ) ? 'checked="checked"' : '';
	$users_allow_enter_meeting_yes = ( $new['allow_user_enter_meeting'] == 1 ) ? 'checked="checked"' : '';
	$users_allow_enter_meeting_no = ( !$new['allow_user_enter_meeting'] ) ? 'checked="checked"' : '';
	$users_allow_edit_meeting_yes = ( $new['allow_user_edit_meeting'] ) ? 'checked="checked"' : '';
	$users_allow_edit_meeting_no = ( !$new['allow_user_edit_meeting'] ) ? 'checked="checked"' : '';
	$users_allow_delete_meeting_yes = ( $new['allow_user_delete_meeting'] ) ? 'checked="checked"' : '';
	$users_allow_delete_meeting_no = ( !$new['allow_user_delete_meeting'] ) ? 'checked="checked"' : '';
	$users_allow_delete_meeting_comments_yes = ( $new['allow_user_delete_meeting_comments'] ) ? 'checked="checked"' : '';
	$users_allow_delete_meeting_comments_no = ( !$new['allow_user_delete_meeting_comments'] ) ? 'checked="checked"' : '';
	$meeting_notify_yes = ( $new['meeting_notify'] ) ? 'checked="checked"' : '';
	$meeting_notify_no = ( !$new['meeting_notify'] ) ? 'checked="checked"' : '';

	$sql = "SELECT group_id, group_name, allow_create_meeting 
		FROM " . GROUPS_TABLE . "
		WHERE group_single_user <> " . TRUE . "
		ORDER BY group_name";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not get usergroups', '', __LINE__, __FILE__, $sql);
	}

	$meeting_usergroup = '<select name="group_id[]" multiple="multiple">';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$selected = ($row['allow_create_meeting']) ? ' selected="selected"' : '';
		$meeting_usergroup .= '<option value="' . $row['group_id'] . '"' . $selected . '>' . $lang['Meeting_group'] . ' ' . $row['group_name'] . '</option>';
	}
	$db->sql_freeresult($result);
	$meeting_usergroup .= '</select>';


	$template->set_filenames(array(
		"body" => "admin/meeting_config_body.tpl")
	);

	$template->assign_vars(array(
		'L_CONFIGURATION_TITLE' => $lang['Meeting_admin'] . ' ' . $lang['Setting'],
		'L_CONFIGURATION_EXPLAIN' => $lang['Meeting_config_explain'],

		'L_USER_ALLOW_ENTER_MEETING' => $lang['User_allow_enter_meeting'], 
		'L_USER_ALLOW_ENTER_MEETING_EXPLAIN' => $lang['User_allow_enter_meeting_explain'], 
		'L_USER_ALLOW_EDIT_MEETING' => $lang['User_allow_edit_meeting'], 
		'L_USER_ALLOW_EDIT_MEETING_EXPLAIN' => $lang['User_allow_edit_meeting_explain'], 
		'L_USER_ALLOW_DELETE_MEETING' => $lang['User_allow_delete_meeting'], 
		'L_USER_ALLOW_DELETE_MEETING_EXPLAIN' => $lang['User_allow_delete_meeting_explain'], 
		'L_USER_ALLOW_DELETE_MEETING_COMMENTS' => $lang['User_allow_delete_meeting_comments'], 
		'L_USER_ALLOW_DELETE_MEETING_COMMENTS_EXPLAIN' => $lang['User_allow_delete_meeting_comments_explain'], 
		'L_MEETING_NOTIFY' => $lang['Meeting_notify'], 
		'L_MEETING_NOTIFY_EXPLAIN' => $lang['Meeting_notify_explain'], 

		'L_GROUPS' => $lang['Groupcp'],

		'USER_ALLOW_ENTER_MEETING_GROUP' => $users_allow_enter_meeting_group,
		'USER_ALLOW_ENTER_MEETING_YES' => $users_allow_enter_meeting_yes,
		'USER_ALLOW_ENTER_MEETING_NO' => $users_allow_enter_meeting_no, 
		'USER_ALLOW_EDIT_MEETING_YES' => $users_allow_edit_meeting_yes,
		'USER_ALLOW_EDIT_MEETING_NO' => $users_allow_edit_meeting_no, 
		'USER_ALLOW_DELETE_MEETING_YES' => $users_allow_delete_meeting_yes,
		'USER_ALLOW_DELETE_MEETING_NO' => $users_allow_delete_meeting_no, 
		'USER_ALLOW_DELETE_MEETING_COMMENTS_YES' => $users_allow_delete_meeting_comments_yes,
		'USER_ALLOW_DELETE_MEETING_COMMENTS_NO' => $users_allow_delete_meeting_comments_no, 
		'MEETING_NOTIFY_YES' => $meeting_notify_yes,
		'MEETING_NOTIFY_NO' => $meeting_notify_no, 

		'S_USERGROUPS' => $meeting_usergroup,
		'S_CONFIG_ACTION' => append_sid('admin_meeting.'.$phpEx))
	);

	$template->pparse("body");

	// Include the board footer with phpBB copyright
	include($phpbb_root_path . '/admin/page_footer_admin.'.$phpEx);
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

	$meeting_usergroup = '<select name="group_id[]" style="width: 100%; height: 100%" multiple="multiple">';
	$meeting_usergroup .= '<option value="-1"' .(($mode=='add_new') ? 'selected="selected"' : ''). '>'.$lang['Meeting_all_users'].'</option>';

	while ( $row = $db->sql_fetchrow($result) )
	{
		$meeting_usergroup .= '<option value="'.($row['group_id']).'">'.$lang['Meeting_group'].' '.$row['group_name'].'</option>';
		$usergroups[] = $row['group_id'];
	}
	$db->sql_freeresult($result);

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

	if ( $mode == 'add_new' )
	{
		$m_id = $meeting_location = $meeting_subject = $meeting_desc = $meeting_link = $meeting_guest_names_yes = '';
		$meeting_places = $meeting_start_value = $meeting_guest_overall = $meeting_guest_single = 0;
		$meeting_recure_value = 5;
		$meeting_time = $meeting_until = time();
		$meeting_guest_names_no = 'checked="checked"';
		$meeting_by_user = sprintf($lang['Meeting_create_by'], append_sid("profile.$phpEx?mode=viewprofile&amp;".POST_USERS_URL."=".$userdata['user_id']), $userdata['username']);
	}
	else if ( $mode == 'edit' )
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
			$meeting_link = $row['meeting_link'];
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

		$meeting_by_user = sprintf($lang['Meeting_create_by'], $meeting_by_user_id, $meeting_by_username);
		$meeting_edit_by_user = sprintf($lang['Meeting_edit_by'], $meeting_edit_by_user_id, $meeting_edit_by_username);

		$sql = "SELECT meeting_group FROM " . MEETING_USERGROUP_TABLE . "
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
			$db->sql_freeresult($result);
		}
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

	$template->set_filenames(array(
		'body' => 'admin/meeting_edit_body.tpl')
	);

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
		'L_MEETING_INTERVALL_EXPLAIN' => $lang['Meeting_intervall_explain'],
		'L_MEETING_GUEST_NAMES' => $lang['Meeting_guest_names'],
		'L_MEETING_GUEST_NAMES_EXPLAIN' => $lang['Meeting_guest_names_explain'],

		'L_CANCEL' => $lang['Cancel'],

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
		'MEETING_LINK' => $meeting_link,
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

		'S_HIDDEN_FIELDS' => ( $mode == 'edit' ) ? '<input type="hidden" name="m_id" value="' . $m_id . '">' : '',
		'S_ACTION' => append_sid("admin_meeting.$phpEx"),

		'U_SMILIES' => append_sid("admin_meeting.$phpEx?mode=smilies"))
	);

	$template->pparse('body');

	include($phpbb_root_path . '/admin/page_footer_admin.'.$phpEx);
}

// Get per page value
$per_page = ( $userdata['user_topics_per_page'] == '' ) ? $board_config['topics_per_page'] : $userdata['user_topics_per_page'] ;

// Default entry point for admins and other acp user
if ( $mode == 'manage' || $mode == '' )
{
	$closed_no = $closed_yes = $closed_period = $closed_none = '';

	$sql_closed = ( $sql_filter == '' ) ? ' WHERE ' : ' AND ';
	$current_time = time();

	switch ($closed)
	{
		case 1:
			$sql_closed .= 'meeting_until > '.$current_time;
			$closed_no = 'checked="checked"';
			break;
		case 2:
			$sql_closed .= 'meeting_until < '.$current_time.' AND meeting_time > '.$current_time;
			$closed_yes = 'checked="checked"';
			break;
		case 3:
			$sql_closed .= 'meeting_time < '.$current_time;
			$closed_period = 'checked="checked"';
			break;
		case 4:
			$sql_closed = '';
			$closed_none = 'checked="checked"';
			break;
	}

	// SQL statement to read from a table
	$sql = "SELECT * FROM " . MEETING_DATA_TABLE . "
		$sql_filter
		$sql_closed
		ORDER BY $sort_field $sort_order
		LIMIT $start, $per_page";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not read meeting data", '', __LINE__, __FILE__, $sql);
	}

	$meetingrow = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$meetingrow[] = $row;
	}
	$total_meeting = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	// Load templates
	$page_title = $lang['Manage_meeting'];
	$template->set_filenames(array(
		'body' => 'admin/meeting_manage_body.tpl')
	);

	// Output global values
	$template->assign_vars(array(
		'L_MEETING' => $lang['Meeting_manage'],
		'L_MEETING_EXPLAIN' => $lang['Meeting_manage_explain'],
		'L_MEETING_TIME' => $lang['Time'],
		'L_MEETING_UNTIL' => $lang['Meeting_until'],
		'L_MEETING_LOCATION' => $lang['Meeting_location'],
		'L_MEETING_SUBJECT' => $lang['Meeting_subject'],
		'L_MEETING_CLOSED' => $lang['Meeting_closed'])
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

	// Output the sorting and filter values
	$template->assign_vars(array(
		'L_SORT_BY_FIELD' => $lang['Sort'],
		'L_SORT_BY_ORDER' => $lang['Order'],
		'L_FILTER_BY_FIELD' => $lang['Meeting_filter'],
		'L_GO' => $lang['Submit'],
		'L_CLOSED_NO' => $lang['Meeting_open'],
		'L_CLOSED_YES' => $lang['Meeting_closed'],
		'L_CLOSED_PERIOD' => $lang['Meeting_no_period'],
		'L_CLOSED_NONE' => $lang['Meeting_all'],
		'SORT_BY_FIELD' => $sort_by_field,
		'SORT_BY_ORDER' => $sort_by_order,
		'FILTER_BY_FIELD' => $filter_by_field,
		'FILTER_FIELD' => $filter,
		'CLOSED_NO' => $closed_no,
		'CLOSED_YES' => $closed_yes,
		'CLOSED_PERIOD' => $closed_period,
		'CLOSED_NONE' => $closed_none,
		'S_ACTION' => append_sid("admin_meeting.$phpEx?mode=sf"))
	);

	if ( $total_meeting != 0 )
	{
		// Cycle a loop through all data
		for($i = 0; $i < $total_meeting; $i++)
		{
			$meeting_check_time = $meetingrow[$i]['meeting_time'];
			$meeting_check_until = $meetingrow[$i]['meeting_until'];

			$meeting_time = create_date($userdata['user_dateformat'], $meeting_check_time, $board_config['board_timezone']);
			$meeting_until = create_date($userdata['user_dateformat'], $meeting_check_until, $board_config['board_timezone']);
			$meeting_location = stripslashes($meetingrow[$i]['meeting_location']);
			$meeting_link = htmlspecialchars($meetingrow[$i]['meeting_link']);
			$meeting_subject = stripslashes($meetingrow[$i]['meeting_subject']);
			$meeting_location = ( $meeting_link != '' ) ? '<a href="'.$meeting_link . '" class="mainmenu">'.$meeting_location.'</a>' : $meeting_location;

			$meeting_id = $meetingrow[$i]['meeting_id'];

			$meeting_edit = '<a href="'.append_sid("admin_meeting.$phpEx?mode=edit&amp;m_id=$meeting_id&amp;start=$start").'" class="mainmenu"><img src="' . $phpbb_root_path . $images['acp_edit'] . '"  alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" /></a>';
			$meeting_delete = '<a href="'.append_sid("admin_meeting.$phpEx?mode=delete&amp;m_id=$meeting_id&amp;start=$start").'" class="mainmenu"><img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" /></a>';

			$meeting_closed = ( $meeting_check_time < time() ) ? $lang['Yes'] : ( ( $meeting_check_until < time() ) ? $lang['Meeting_no_period'] : $lang['No'] );

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			// Output the values
			$template->assign_block_vars('meeting_overview_row', array(
				'ROW_CLASS' => $row_class,
				'MEETING_TIME' => $meeting_time,
				'MEETING_UNTIL' => $meeting_until,
				'MEETING_LOCATION' => $meeting_location,
				'MEETING_SUBJECT' => $meeting_subject,
				'MEETING_CLOSED' => $meeting_closed,
				'MEETING_EDIT' => $meeting_edit,
				'MEETING_DELETE' => $meeting_delete)
			);
		}

		// Create the pagination
		$sql = "SELECT * FROM " . MEETING_DATA_TABLE;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not get meeting data for pagination", '', __LINE__, __FILE__, $sql);
		}

		$total_meetings = $db->sql_numrows($result);

		$db->sql_freeresult($result);

		$pagination = generate_pagination("admin_meeting.$phpEx?mode=manage", $total_meetings, $per_page, $start);
		
		$template->assign_vars(array(
			'PAGINATION' => $pagination)
		);
	}
	else
	{
		// Output message if no meeting was found
		$template->assign_block_vars('no_meeting_row', array(
			'L_NO_MEETING' => $lang['No_meeting'])
		);
	}

	// Parse and display the page
	$template->pparse('body');

	// Include the board footer with phpBB copyright
	include($phpbb_root_path . '/admin/page_footer_admin.'.$phpEx);
}

?>
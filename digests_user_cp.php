<?php 
/** 
*
* @package phpBB2
* @version $Id: digests_user_cp.php,v 1.99.2.3 2004/07/11 16:46:15 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

$params = array('mode' => 'mode', 'user_id' => 'user_id', 'digest_id' => 'digest_id', 'group_id' => 'group_id', 'freq' => 'freq' );

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


//
// Start session management 
//
$userdata = session_pagestart($user_ip, PAGE_DIGEST); 
init_userprefs($userdata);
$user_id = $userdata['user_id'];
//
// End session management 
//


if ($digest_config['digest_disable_user'])
{
	message_die(GENERAL_MESSAGE, $lang['Digests_disabled_user'] . '<br /><br />' . sprintf($lang['Click_return_usercp'], '<a href="' . append_sid('profile.'.$phpEx.'?mode=editprofile&amp;ucp=main') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>'));
}
	
if (!$userdata['session_logged_in'])
{
	redirect(append_sid("login." . $phpEx . "?redirect=digests_user_cp." . $phpEx, TRUE));
}

$user = ($group_id != 0) ? $group_id : $user_id;

if ($mode == 'activity')
{
	$sql = "SELECT *
		FROM " . DIGEST_TABLE . "
		WHERE user_id = $user
			AND digest_id = $digest_id";
	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	$activity = $row['digest_activity'];
	$activity =($activity == 0) ? 1 : 0;

	$sql = "UPDATE " . DIGEST_TABLE . "
		SET digest_activity = $activity
		WHERE user_id = $user
			AND digest_id = $digest_id";
	$result = $db->sql_query($sql);
}

if ($mode == 'reset')
{
	$reset_time = (time() - (($freq * 3600) + 120));

	$sql = "UPDATE " . DIGEST_TABLE . "
		SET last_digest = $reset_time
		WHERE user_id = $user
			AND digest_id = $digest_id";
	$result = $db->sql_query($sql);
}

if ($mode == 'unsub')
{
	$sql = "DELETE
		FROM " . DIGEST_FORUMS_TABLE . "
		WHERE user_id = $user_id
			AND digest_id = $digest_id";
	$result = $db->sql_query($sql);

	$sql = "DELETE
		FROM " . DIGEST_TABLE . "
		WHERE user_id = $user_id
			AND digest_id = $digest_id";
	$result = $db->sql_query($sql);
}

if ($mode == 'gr_unsub')
{
	$sql = "DELETE
		FROM " . USER_GROUP_TABLE . "
		WHERE group_id = $group_id
			AND user_id = $user_id";
	$result = $db->sql_query($sql);
}

// Load templates
$page_title = $lang['Digests'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

$template->set_filenames(array( 
   'body' => 'profile_digests_body.tpl') 
); 

// Send vars to template
$template->assign_vars(array( 
	'L_PAGE_TITLE' => $lang['Digests'],
	'L_PAGE_DESCRIPTION' => $lang['Panel_description'],
	'L_CONTROL_PANEL' => $lang['Digest_control_panel'],
	'L_MY_DIGESTS' => $lang['My_digests'],
	'L_LAST_DIGEST' => $lang['Last_digest_time'],
	'L_FREQUENCY' => $lang['Digest_default_frequency'],
	'L_EDIT' => $lang['Edit'],
	'L_UNSUBSCRIBE' => $lang['Unsubscribe'],
	'L_STATUS' => $lang['Active'],
	'L_RESET' => $lang['Reset'],
	'L_GROUP_DIGESTS' => $lang['My_group_digests'],
	'L_CREATE_NEW' => $lang['Create_new'],

	'VERSION' => $digest_config['digest_version'],
	'CREATE_NEW_URL' => append_sid("digests.$phpEx?mode=user")) 
); 

// Perform SQL query to get the user's digest data
//
$sql = "SELECT d.*
	FROM " . DIGEST_TABLE . " d
	WHERE d.user_id = $user_id
		AND d.digest_type = 0
	ORDER BY d.digest_frequency";
$result = $db->sql_query($sql);

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not obtain user\'s digest information', '', __LINE__, __FILE__, $sql);
}

$i = 0;
while($row = $db->sql_fetchrow($result))
{
	$digest_id = $row['digest_id'];
	$frequency = $row['digest_frequency'];
	$digest_frequency_name = get_frequency_name($row['digest_frequency']);
	$last_digest_date = date("d-M-y", $row['last_digest']);
	$last_digest_time = date("H:i:s", $row['last_digest']);
	$active = yes_no($row['digest_activity']);
	$alt_activity = ($row['digest_activity'] == 1) ? $lang['Deactivate'] : $lang['Activate'];
	
	if (($row['digest_name'] == '') || ($row['digest_name'] == 0))
	{
		$digest_name = $digest_frequency_name . $lang['Add_digest'];
	}
	else
	{
		$digest_name = $row['digest_name'];
	}
	
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('digest_row', array(
		'ROW_CLASS' => $row_class,
		'DIGEST_NAME' => $digest_name,
		'LAST_DATE' => $last_digest_date,
		'LAST_TIME' => $last_digest_time,
		'FREQUENCY' => $digest_frequency_name,
		'EDIT_URL' => append_sid("digests.$phpEx?user_id=$user_id&digest_id=$digest_id"),
		'UNSUBSCRIBE_URL' => append_sid("digests_user_cp.$phpEx?user_id=$user_id&digest_id=$digest_id&mode=unsub"),
		'ACTIVITY' => $active,
		'ALT_ACTIVITY' => $alt_activity,
		'ACTIVITY_URL' => append_sid("digests_user_cp.$phpEx?user_id=$user_id&digest_id=$digest_id&mode=activity"),
		'RESET_URL' => append_sid("digests_user_cp.$phpEx?user_id=$user_id&digest_id=$digest_id&mode=reset&freq=$frequency"))
	);
	$i++;
}
$db->sql_freeresult($result);

// Perform SQL query to get the user's group digest data
//
$sql = "SELECT DISTINCT ug.*, d.*, g.*
	FROM " . USER_GROUP_TABLE . " ug, " . DIGEST_TABLE . " d, " . GROUPS_TABLE . " g
	WHERE d.user_id = g.group_id
		AND ug.group_id = g.group_id
		AND ug.user_id = $user_id
		AND g.group_single_user = 0
		AND d.digest_type = 1
	ORDER BY d.digest_frequency";
$result = $db->sql_query($sql);

if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Could not obtain user\'s group digest information', '', __LINE__, __FILE__, $sql);
}

$i = 0;
while($row = $db->sql_fetchrow($result))
{
	$digest_id = $row['digest_id'];
	$frequency = $row['digest_frequency'];
	$group_id = $row['group_id'];
	$digest_frequency_name = get_frequency_name($row['digest_frequency']);
	$last_digest_date = date("d-M-y", $row['last_digest']);
	$last_digest_time = date("H:i:s", $row['last_digest']);
	$active = yes_no($row['digest_activity']);
	$alt_activity = ($row['digest_activity'] == 1) ? $lang['Deactivate'] : $lang['Activate'];
	
	if (($row['digest_name'] == '') || ($row['digest_name'] == 0))
	{
		$digest_name =  $row['group_name'];
	}
	else
	{
		$digest_name = $row['digest_name'];
	}

	$unsubscribe = $unsubscribe_url = $edit = $edit_url = $activity = $alt_active = $activity_url = $reset = $reset_url = '';

	if ($row['group_moderator'] != $user_id)
	{
		$unsubscribe = $lang['Unsubscribe'];
		$unsubscribe_url = append_sid("digests_user_cp.$phpEx?user_id=$user_id&group_id=$group_id&mode=gr_unsub");
	}

	if ($row['group_moderator'] == $user_id)
	{
		$edit = $lang['Edit'];
		$edit_url = append_sid("digests.$phpEx?user_id=$group_id&digest_id=$digest_id&mode=group");
		$activity = $active;
		$alt_active = '[' . $alt_activity .']';
		$activity_url = append_sid("digests_user_cp.$phpEx?group_id=$group_id&digest_id=$digest_id&mode=activity");
		$reset = $lang['Reset'];
		$reset_url = append_sid("digests_user_cp.$phpEx?group_id=$group_id&digest_id=$digest_id&mode=reset&freq=$frequency");
	}
	
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('digest_group_row', array(
		'ROW_CLASS' => $row_class,
		'DIGEST_NAME' => $digest_name,
		'DIGEST_NAME_URL' => $phpbb_root_path . "digests_group_members.$phpEx?group_id=$group_id",
		'LAST_DATE' => $last_digest_date,
		'LAST_TIME' => $last_digest_time,
		'FREQUENCY' => $digest_frequency_name,	
		'ROW_CLASS' => $row_class,
		'UNSUBSCRIBE' => $unsubscribe,
		'UNSUBSCRIBE_URL' => $unsubscribe_url,
		'EDIT' => $edit,
		'EDIT_URL' => $edit_url,
		'ACTIVITY' => $activity,
		'ALT_ACTIVITY' => $alt_active,
		'ACTIVITY_URL' => $activity_url,
		'RESET' => $reset,
		'RESET_URL' => $reset_url)
	);
	$i++;
}
$db->sql_freeresult($result);

include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body'); 

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?> 
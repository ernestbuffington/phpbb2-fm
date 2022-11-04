<?php
/** 
*
* @package phpBB2
* @version $Id: medalcp_edit.php,v 2.0.2 2003/01/05 02:36:00 ycl6 Exp $
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

if ( isset($HTTP_GET_VARS[POST_USERS_URL]) || isset($HTTP_POST_VARS[POST_USERS_URL]) )
{
	$user_id = ( isset($HTTP_POST_VARS[POST_USERS_URL]) ) ? intval($HTTP_POST_VARS[POST_USERS_URL]) : intval($HTTP_GET_VARS[POST_USERS_URL]);
}
else
{
	$user_id = '';
}
$profiledata = get_userdata($user_id);

if ( isset($HTTP_GET_VARS[POST_MEDAL_URL]) || isset($HTTP_POST_VARS[POST_MEDAL_URL]) )
{
	$medal_id = ( isset($HTTP_POST_VARS[POST_MEDAL_URL]) ) ? intval($HTTP_POST_VARS[POST_MEDAL_URL]) : intval($HTTP_GET_VARS[POST_MEDAL_URL]);
}
else
{
	$medal_id = '';
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

if ( empty($user_id) )
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] );
}

if ( empty($medal_id) )
{
	message_die(GENERAL_MESSAGE, $lang['No_medal_id_specified']);
}

$sql = "SELECT *
	FROM " . MEDAL_USER_TABLE . "
	WHERE medal_id = $medal_id
		AND user_id = '" . $profiledata['user_id'] . "'
	ORDER BY issue_id";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting user medal information', '', __LINE__, __FILE__, $sql);
}
else
{
	$row = $issue = $default_config = array();
	$row = $db->sql_fetchrowset($result);
	$rows = sizeof($row);

	for($i = 0; $i < $rows; $i++)
	{
		$issue[$i]['issue_id'] = $row[$i]['issue_id'];
		$issue[$i]['issue_time'] = $row[$i]['issue_time'];
		$issue_reason = $row[$i]['issue_reason'];
		$default_config[$i] = str_replace("'", "\'", $issue_reason);

		$issue[$i]['issue_reason'] = ( isset($HTTP_POST_VARS['issue_reason'.$row[$i]['issue_id']]) ) ? trim($HTTP_POST_VARS['issue_reason'.$row[$i]['issue_id']]) : $default_config[$i];

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . MEDAL_USER_TABLE . " 
				SET issue_reason = '" . str_replace("\'", "''", $issue[$i]['issue_reason']) . "'
				WHERE issue_id =" . $row[$i]['issue_id'];
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update medal info', '', __LINE__, __FILE__, $sql);
			}
		}
	}
		
	if( isset($HTTP_POST_VARS['submit']) )
	{
		$message = $lang['Medal_update_sucessful'] . "<br /><br />" . sprintf($lang['Click_return_medal'], "<a href=\"" . append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=$medal_id&amp;sid=".$userdata['session_id']) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
$s_hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $profiledata['user_id'] . '" />';
$s_hidden_fields .= '<input type="hidden" name="' . POST_MEDAL_URL . '" value="' . $medal_id . '" />';

$page_title = $lang['Medal_Control_Panel'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'medalcp_edit_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$sql = "SELECT *
	FROM " . MEDAL_TABLE . " 
	WHERE medal_id =" . $medal_id;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain medal information', '', __LINE__, __FILE__, $sql);
}
$medal_info = $db->sql_fetchrow($result);

$template->assign_vars(array(
	'L_MEDAL_INFORMATION' => $lang['Medal_Information'] . ' :: ' . $profiledata['username'],

	'L_MEDALS' => $lang['Medals'],
	'U_MEDALS' => append_sid("medals.$phpEx"),
	'L_MEDAL_CP' => $lang['Link_to_cp'],
	'U_MEDAL_CP' => append_sid("medalcp.$phpEx?" . POST_MEDAL_URL . "=" . $medal_id . "&amp;sid=" . $userdata['session_id']),

	'MEDAL_NAME' => $medal_info['medal_name'],
	'MEDAL_DESCRIPTION' => $medal_info['medal_description'],
	
	'S_MEDAL_ACTION' => append_sid("medalcp_edit.$phpEx"),
	'S_HIDDEN_FIELDS' => $s_hidden_fields)
);

for($i = 0; $i < $rows; $i++)
{
	$template->assign_block_vars("medaledit", array(
		'L_MEDAL_TIME' => $lang['Medal_time'],
		'L_MEDAL_REASON' => $lang['Medal_reason'],
		'L_MEDAL_REASON_EXPLAIN' => $lang['Medal_reason_explain'],
		'L_ISSUE_REASON' => 'issue_reason'. $issue[$i]['issue_id'],
		
		'ISSUE_REASON' => $issue[$i]['issue_reason'],
		'ISSUE_TIME' => create_date($board_config['default_dateformat'], $issue[$i]['issue_time'], $board_config['board_timezone']))
	);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>

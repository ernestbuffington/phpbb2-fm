<?php
/** 
*
* @package phpBB2
* @version $Id: groupmsg.php,v 1.159.2.22 2002/06/11 16:46:16 niels Exp $
* @copyright (c) 2002 Niels Chr. Denmark
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

//
// Is PM disabled?
//
if ( !empty($board_config['privmsg_disable']) )
{
	message_die(GENERAL_MESSAGE, 'PM_disabled');
}

$html_entities_match = array('#&(?!(\#[0-9]+;))#', '#<#', '#>#', '#"#');
$html_entities_replace = array('&amp;', '&lt;', '&gt;', '&quot;');

//
// Increase maximum execution time in case of a lot of users, but don't complain about it if it isn't
// allowed.
//
@set_time_limit(120);


//
// Parameters
//
$submit = ( isset($HTTP_POST_VARS['post']) ) ? TRUE : 0;
$preview = ( isset($HTTP_POST_VARS['preview']) ) ? TRUE : 0;
$group_id = ( !empty($HTTP_POST_VARS[POST_GROUPS_URL]) ) ? $HTTP_POST_VARS[POST_GROUPS_URL] : 0;
$group_id = intval($group_id);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PRIVMSGS);
init_userprefs($userdata);
//
// End session management
//

//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_mass_pm.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_mass_pm.' . $phpEx);


//
// Define the box image links
//
$inbox_url = '<a href="' . append_sid("privmsg.$phpEx?folder=inbox") . '" class="nav">' . $lang['Inbox'] . '</a>';
$outbox_url = '<a href="' . append_sid("privmsg.$phpEx?folder=outbox") . '" class="nav">' . $lang['Outbox'] . '</a>';
$sentbox_url = '<a href="' . append_sid("privmsg.$phpEx?folder=sentbox") . '" class="nav">' . $lang['Sentbox'] . '</a>';
$savebox_url = '<a href="' . append_sid("privmsg.$phpEx?folder=savebox") . '" class="nav">' . $lang['Savebox'] . '</a>';
$export_url =  '<a href="' . append_sid("privmsg_export.$phpEx") . '" class="nav">' . $lang['Export'] . '</a>';

if ( $userdata['user_level'] == ADMIN )
{
	$mass_pm_url =  ':: ' . $lang['Mass_pm'];
} 
else
{
	$sql_g = "SELECT DISTINCT g.group_id
		FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug 
		WHERE g.group_single_user <> 1
			AND ((g.group_allow_pm = '" . AUTH_MOD . "' AND g.group_moderator = '" . $userdata['user_id'] . "') 
			OR (g.group_allow_pm = '" . AUTH_ACL . "' AND ug.user_id = " . $userdata['user_id'] . " AND ug.group_id = g.group_id ) 
			OR (g.group_allow_pm = '" . AUTH_REG . "'))";
	if( !$g_result = $db->sql_query($sql_g) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain group names.', '', __LINE__, __FILE__, $sql_g);
	}
	
	if( $db->sql_numrows($g_result))
	{
		$mass_pm_url =  ':: ' . $lang['Mass_pm'];
	}
}

//
// Var definitions
//
if ( !empty($HTTP_POST_VARS['mode']) || !empty($HTTP_GET_VARS['mode']) )
{
	$mode = ( !empty($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
}
else
{
	$mode = 'post';
}
$error = FALSE;


// ----------
// Start main
//
if ( !empty($group_id) )
{
	if( $group_id != -1 )
	{
		$sql = "SELECT DISTINCT g.group_name 
			FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug  
			WHERE g.group_single_user <> 1 
				AND g.group_id='" . $group_id . "'
				AND (('" . $userdata['user_level'] . "' = '" . ADMIN . "') 
					OR (g.group_allow_pm='" . AUTH_MOD . "' 
				AND g.group_moderator = '" . $userdata['user_id'] . "') 
					OR (g.group_allow_pm='" . AUTH_ACL . "' 
				AND ug.user_id = " . $userdata['user_id'] . " 
				AND ug.group_id = g.group_id ) 
					OR (g.group_allow_pm='" . AUTH_REG . "' 
				AND '" . $userdata['user_id'] . "' != '" . ANONYMOUS . "' ) 
					OR (g.group_allow_pm='" . AUTH_ALL . "'))" ;
		$result = $db->sql_query($sql);
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Could not select group name!", __LINE__, __FILE__, $sql);
		}

		if( ! $db->sql_numrows($result)) 
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorised']); 
		}
		$group = $db->sql_fetchrow($result);

		$group_name = $group['group_name'];
	
		$sql = "SELECT distinct u.user_id, u.user_lang, u.user_email, u.username, u.user_notify_pm,u.user_active,u.user_allow_mass_pm
			FROM " . USERS_TABLE . " u, " . USER_GROUP_TABLE . " ug
			WHERE u.user_allow_mass_pm > 1 
				AND ug.group_id = $group_id 
				AND ug.user_pending <> " . TRUE . "  
				AND u.user_id <> " . ANONYMOUS . "
				AND u.user_id = ug.user_id		
			ORDER BY u.user_lang";
	}
	else
	{
		if ($userdata['user_level'] != ADMIN)
		{
			message_die(GENERAL_ERROR, $lang['Not_Authorised']); 
		}
		$sql = "SELECT distinct user_id, user_lang, user_email, username, user_notify_pm,user_active,user_allow_mass_pm
			FROM " . USERS_TABLE . " 
			WHERE user_allow_mass_pm > 1 
				AND user_id <> " . ANONYMOUS . " 
			ORDER BY user_lang";
		$group_name = $lang['All_users'];
	}
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Coult not select group members!", __LINE__, __FILE__, $sql);
	}
	
	if( ! $db->sql_numrows($result))
	{
		$pm_list = $db->sql_fetchrowset($result);
		//
		// Output a relevant GENERAL_MESSAGE about users/group
		// not existing
		//
		$error = TRUE;
		$error_msg .= ( ( !empty($error_msg) ) ? '<br />' : '' ) . $lang['No_to_user'];
	}
	$PM_list = $db->sql_fetchrowset($result);
	$PM_count = $db->sql_numrows($result);
}

//
// Toggles
//
if ( !$board_config['allow_html'] )
{
	$html_on = 0;
}
else
{
	$html_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_html']) ) ? 0 : TRUE ) : $userdata['user_allowhtml'];
}

if ( !$board_config['allow_bbcode'] )
{
	$bbcode_on = 0;
}
else
{
	$bbcode_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_bbcode']) ) ? 0 : TRUE ) : $userdata['user_allowbbcode'];
}

if ( !$board_config['allow_smilies'] )
{
	$smilies_on = 0;
}
else
{
	$smilies_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_smilies']) ) ? 0 : TRUE ) : $userdata['user_allowsmile'];
}

$attach_sig = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['attach_sig']) ) ? TRUE : 0 ) : $userdata['user_attachsig'];
$user_sig = ( $userdata['user_sig'] != '' && $board_config['allow_sig'] ) ? $userdata['user_sig'] : "";
	
if ( $submit)
{
	//
	// Flood control
	// No flood control for Admins, Super Mods, or Mods
	//
	if ( $userdata['user_level'] < 1 )
	{
		$sql = "SELECT MAX(privmsgs_date) AS last_post_time
			FROM " . PRIVMSGS_TABLE . "
			WHERE privmsgs_from_userid = " . $userdata['user_id'];
		if ( $result = $db->sql_query($sql) )
		{
			$db_row = $db->sql_fetchrow($result);
	
			$last_post_time = $db_row['last_post_time'];
			$current_time = time();

			if ( ( $current_time - $last_post_time ) < $board_config['flood_interval'])
			{
				message_die(GENERAL_MESSAGE, $lang['Flood_Error']);
			}
		}
	}
	//
	// End Flood control
	//

	if ( empty($group_id) )
	{
		$error = TRUE;
		$error_msg .= ( ( !empty($error_msg) ) ? '<br />' : '' ) . $lang['No_to_user'];
	}

	$privmsg_subject = trim(htmlspecialchars($HTTP_POST_VARS['subject']));
	if ( empty($privmsg_subject) )
	{
		$error = TRUE;
		$error_msg .= ( ( !empty($error_msg) ) ? '<br />' : '' ) . $lang['Empty_subject'];
	}

	if ( (strlen ($HTTP_POST_VARS['message']) > $board_config['message_length']) && ($board_config['message_length'] > 0) )
	{
		$message_too_long = sprintf($lang['Message_too_long'], $board_config['message_length']);
		$error = TRUE;
		$error_msg .= ( ( !empty($error_msg) ) ? '<br />' : '' ) . $message_too_long;
	}

	if ( !empty($HTTP_POST_VARS['message']) )
	{
		if ( !$error )
		{
			if ( $bbcode_on )
			{
				$bbcode_uid = make_bbcode_uid();
			}

			$privmsg_subject = trim(strip_tags($HTTP_POST_VARS['subject']));
			$privmsg_message = prepare_message($HTTP_POST_VARS['message'], $html_on, $bbcode_on, $smilies_on, $bbcode_uid);
		}
	}
	else
	{
		$error = TRUE;
		$error_msg .= ( ( !empty($error_msg) ) ? '<br />' : '' ) . $lang['Empty_message'];
	}
}

if ( $submit && !$error )
{
	//
	// Has admin prevented user from sending PM's?
	//
	if ( !$userdata['user_allow_pm'] )
	{
		$message = $lang['Cannot_send_privmsg'];
		message_die(GENERAL_MESSAGE, $message);
	}

	include($phpbb_root_path . 'includes/emailer.'.$phpEx);
	$i = 0;
	while ($i < sizeof($PM_list))
	{
		@set_time_limit(30);
		$to_userdata = $PM_list[$i];
		$msg_time = time();

		//
		// See if recipient is at their inbox limit
		//
		$sql = "SELECT COUNT(privmsgs_id) AS inbox_items, MIN(privmsgs_date) AS oldest_post_time 
			FROM " . PRIVMSGS_TABLE . " 
			WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
					OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "  
					OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " ) 
				AND privmsgs_to_userid = " . $to_userdata['user_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_user']);
		}

		$sql_priority = ( SQL_LAYER == 'mysql' ) ? 'LOW_PRIORITY' : '';

		if ( $inbox_info = $db->sql_fetchrow($result) )
		{
			if ( $inbox_info['inbox_items'] >= $board_config['max_inbox_privmsgs'] )
			{
				$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TABLE . " 
					WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
							OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " 
							OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . "  ) 
						AND privmsgs_date = " . $inbox_info['oldest_post_time'] . " 
						AND privmsgs_to_userid = " . $to_userdata['user_id'];
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs', '', __LINE__, __FILE__, $sql);
				}
			}
		}
		
		$sql_info = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig)
			VALUES (" . PRIVMSGS_NEW_MAIL . ", '" . str_replace("\'", "''", str_replace("[USERNAME]",$to_userdata['username'],$privmsg_subject)) . "', " . $userdata['user_id'] . ", " . $to_userdata['user_id'] . ", $msg_time, '$user_ip', $html_on, $bbcode_on, $smilies_on, $attach_sig)";
		if ( !($result = $db->sql_query($sql_info, BEGIN_TRANSACTION)) )
		{
			message_die(GENERAL_ERROR, "Could not insert private message sent info.", "", __LINE__, __FILE__, $sql_info);
		}

		$privmsg_sent_id = $db->sql_nextid();
	
		$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text)
			VALUES ($privmsg_sent_id, '" . $bbcode_uid . "', '" . str_replace("\'", "''", str_replace("[USERNAME]", $to_userdata['username'], $privmsg_message)) . "')";
		if ( !$db->sql_query($sql, END_TRANSACTION) )
		{	
			message_die(GENERAL_ERROR, "Could not insert/update private message sent text.", "", __LINE__, __FILE__, $sql_info);
		}
	
		//
		// Add to the users new pm counter
		//	
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . "  
			WHERE user_id = " . $to_userdata['user_id']; 
		if ( !$status = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update private message new/read status for user', '', __LINE__, __FILE__, $sql);
		}

		if (!empty($to_userdata['user_email']) && $to_userdata['user_active'] && $to_userdata['user_allow_mass_pm'] > 3 )
		{
			$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
			$script_name = ( $script_name != '' ) ? $script_name . '/privmsg.'.$phpEx : 'privmsg.'.$phpEx;
			$server_name = trim($board_config['server_name']);
			$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
			$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

			$emailer = new emailer($board_config['smtp_delivery']);
					
			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);

			$emailer->use_template('privmsg_notify', $to_userdata['user_lang']);
			$emailer->email_address($to_userdata['user_email']);
			$emailer->set_subject($lang['Notification_subject']);
					
			$emailer->assign_vars(array(
				'PM_USERNAME' => $to_userdata['username'], 
				'FROM' => $userdata['username'], 
				'PM_SUBJECT' => $privmsg_subject,
				'PM_MESSAGE' => str_replace("\'", "''", str_replace("[USERNAME]", $to_userdata['username'], $privmsg_message)),	
				'SITENAME' => $board_config['sitename'],
				'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']), 

				'U_INBOX' => $server_protocol . $server_name . $server_port . $script_name . '?folder=inbox')
			);
	
			$emailer->send();
			$emailer->reset();
			$n++;
		}
		$i++;
	}
		
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("privmsg.$phpEx?folder=inbox") . '">')
	);
		
	$msg = $lang['PM_delivered'] . '<br /><br />' . sprintf($lang['Mass_pm_count'], $i, $n) . '<br /><br />' . sprintf($lang['Click_return_inbox'], '<a href="' . append_sid("privmsg.$phpEx?folder=inbox") . '">', '</a> ') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

	message_die(GENERAL_MESSAGE, $msg);
}
else if ( $preview || $error )
{
	//
	// If we're previewing then obtain the data
	// passed to the script, process it a little, do some checks
	// where neccessary, etc.
	//
	$to_username = ($PM_count)? sprintf($lang['Pm_mass_users'], $group_name, $PM_count): sprintf($lang['No_mass_pm_users'], $group_name);

	$privmsg_subject = ( isset($HTTP_POST_VARS['subject']) ) ? trim(htmlspecialchars(stripslashes($HTTP_POST_VARS['subject']))) : '';
	$privmsg_message = ( isset($HTTP_POST_VARS['message']) ) ? trim($HTTP_POST_VARS['message']) : '';
	$privmsg_message = preg_replace('#<textarea>#si', '&lt;textarea&gt;', $privmsg_message);
	if ( !$preview )
	{
		$privmsg_message = stripslashes($privmsg_message);
	}

	//
	// Do mode specific things
	//
	if ( $mode == 'post' )
	{
		$page_title = $lang['Send_mass_pm'];

		$user_sig = ( $userdata['user_sig'] != '' && $board_config['allow_sig'] ) ? $userdata['user_sig'] : '';
	}
}


//
// Start output, first preview, then errors then post form
//
$page_title = $lang['Send_mass_pm'];

$tmp_error = $error; 
include($phpbb_root_path . 'includes/page_header.'.$phpEx);
$error = $tmp_error;

if ( $preview && !$error )
{
	//
	// Define censored word matches
	//
	if ( !$board_config['allow_swearywords'] )
	{
		$orig_word = $replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}
	else if ( !$userdata['user_allowswearywords'] )
	{
		$orig_word = $replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}

	if ( $bbcode_on )
	{
		$bbcode_uid = make_bbcode_uid();
	}

	$preview_message = stripslashes(prepare_message($privmsg_message, $html_on, $bbcode_on, $smilies_on, $bbcode_uid));
	$privmsg_message = stripslashes(preg_replace($html_entities_match, $html_entities_replace, $privmsg_message));

	//
	// Finalise processing as per viewtopic
	//
	if ( !$html_on || !$board_config['allow_html'] || !$userdata['user_allowhtml'] )
	{
		if ( $user_sig != '' )
		{
			$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
		}
	}

	if ( $attach_sig && $user_sig != '' && $userdata['user_sig_bbcode_uid'] )
	{
		$user_sig = bbencode_second_pass($user_sig, $userdata['user_sig_bbcode_uid']);
	}

	if ( $bbcode_on )
	{
		$preview_message = bbencode_second_pass($preview_message, $bbcode_uid);
	}

	if ( $attach_sig && $user_sig != '' )
	{
		$preview_message = $preview_message . '<br /><br />_________________<br />' . $user_sig;
	}
		
	if( !empty($orig_word) )
	{
		$preview_subject = preg_replace($orig_word, $replacement_word, $privmsg_subject);
		$preview_message = preg_replace($orig_word, $replacement_word, $preview_message);
	}
	else
	{
		$preview_subject = $privmsg_subject;
	}

	if ( $smilies_on )
	{
		$preview_message = smilies_pass($preview_message);
	}
	else
	{
		if( $board_config['smilie_removal1'] )
		{
			$preview_message = smilies_code_removal($preview_message);
		}
	}

	$preview_message = make_clickable($preview_message);

	$preview_message = str_replace("\n", '<br />', $preview_message);

	$template->set_filenames(array(
		'preview' => 'privmsgs_preview.tpl')
	);

	$template->assign_vars(array(
		'TOPIC_TITLE' => $preview_subject,
		'POST_SUBJECT' => $preview_subject,
		'MESSAGE_TO' => $group_name . ' (' . $PM_count . ')', 
		'MESSAGE_FROM' => $userdata['username'], 
		'POST_DATE' => create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
		'MESSAGE' => $preview_message,
		'L_SUBJECT' => $lang['Subject'],
		'L_DATE' => $lang['Date'],
		'L_FROM' => $lang['From'],
		'L_TO' => $lang['To_group'],
		'L_PREVIEW' => $lang['Preview'],
		'L_POSTED' => $lang['Posted'])
	);

	$template->assign_var_from_handle('POST_PREVIEW_BOX', 'preview');
}

//
// Start error handling
//
if ($error)
{
	$template->set_filenames(array(
		'reg_header' => 'error_body.tpl')
	);
	$template->assign_vars(array(
		'ERROR_MESSAGE' => $error_msg)
	);
	$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
}

//
// Load templates
//
$template->set_filenames(array(
	'body' => 'posting_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

Multi_BBCode();

//
// Enable extensions in posting_body
//
$template->assign_block_vars('switch_groupmsg', array());

//
// HTML toggle selection
//
if ( $board_config['allow_html'] )
{
	$html_status = $lang['HTML_is_ON'];
	$template->assign_block_vars('switch_html_checkbox', array());
}
else
{
	$html_status = $lang['HTML_is_OFF'];
}

//
// BBCode toggle selection
//
if ( $board_config['allow_bbcode'] )
{
	$bbcode_status = $lang['BBCode_is_ON'];
	$template->assign_block_vars('switch_bbcode_checkbox', array());
}
else
{
	$bbcode_status = $lang['BBCode_is_OFF'];
}

//
// Smilies toggle selection
//
if ( $board_config['allow_smilies'] )
{
	$smilies_status = $lang['Smilies_are_ON'];
	$template->assign_block_vars('switch_smilies_checkbox', array());
}
else
{
	if( $board_config['smilie_removal1'] )
	{
		$smilies_status = $lang['Smilies_are_REMOVED'];
	}
	else
	{
		$smilies_status = $lang['Smilies_are_OFF'];
	}
}


//
// Signature toggle selection - only show if
// the user has a signature
//
if ( $user_sig != '' )
{
	$template->assign_block_vars('switch_signature_checkbox', array());
}

if ( $mode == 'post' )
{
	$post_a = $lang['Send_mass_pm'];
}

$sql = "SELECT DISTINCT g.group_id, g.group_name 
	FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug 
	WHERE g.group_single_user <> 1
		AND (('" . $userdata['user_level'] . "' = '" . ADMIN . "') 
			OR (g.group_allow_pm='" . AUTH_MOD . "' 
		AND g.group_moderator = '" . $userdata['user_id'] . "') 
			OR (g.group_allow_pm='" . AUTH_ACL . "' 
		AND ug.user_id = " . $userdata['user_id'] . " 
		AND ug.group_id = g.group_id ) 
			OR (g.group_allow_pm='" . AUTH_REG . "' 
		AND '" . $userdata['user_id'] . "' != '" . ANONYMOUS . "' ) 
			OR (g.group_allow_pm='" . AUTH_ALL . "'))" ;
if( !$g_result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not select group names!", __LINE__, __FILE__, $sql);
}
$group_list = $db->sql_fetchrowset($g_result);

if ($userdata['user_level'] != ADMIN && empty($group_list)) 
{
	message_die(GENERAL_ERROR, $lang['Mass_pm_not_allowed']);
}
	
$groupname = trim(strip_tags($HTTP_GET_VARS['groupname']));

$select_list = '<select name = "' . POST_GROUPS_URL . '">';
$select_list .= ($userdata['user_level'] == ADMIN) ? '<option value = "-1" '. (($lang['All_users'] == $groupname) ? ' selected="selected"' : '' ).'>' . $lang['All_users'] . '</option>':'';
for($i = 0; $i < sizeof($group_list); $i++)
{
		$select_list .= '<option value = "' . $group_list[$i]['group_id'] . '"' . (($group_list[$i]['group_name'] == $groupname || $group_list[$i]['group_id'] == $group_id)? ' selected="selected"' : '') . '>' . $group_list[$i]['group_name'] . '</option>';
}
$select_list .= '</select>';

//
// Send smilies to template
//
generate_smilies('inline', PAGE_PRIVMSGS);

$template->assign_vars(array(
	'SUBJECT' => $privmsg_subject, 
	'USERNAME' => $select_list,
	'MESSAGE' => $privmsg_message,
	'HTML_STATUS' => $html_status, 
	'SMILIES_STATUS' => $smilies_status, 
	'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'), 
	'FORUM_NAME' => $lang['Private_Message'], 
	'MAX_SYMBOLS' => $board_config['message_maxlength'],
	'DO_CHANGE' => ( $board_config['message_maxlength'] ) ? 'doChange();' : '',

	'INBOX' => $inbox_url, 
	'SENTBOX' => $sentbox_url, 
	'OUTBOX' => $outbox_url, 
	'SAVEBOX' => $savebox_url, 
	'MASS_PM' => $mass_pm_url,
	'EXPORT' => $export_url,

	'L_SUBJECT' => $lang['Subject'],
	'L_MESSAGE_BODY' => $lang['Message_body'],
	'L_SYMBOLS_LEFT' => $lang['Symbols_left'],
	'L_OPTIONS' => $lang['Options'],
	'L_SPELLCHECK' => $lang['Spellcheck'],
	'L_COPY_TO_CLIPBOARD' => $lang['Copy_to_clipboard'],
	'L_COPY_TO_CLIPBOARD_EXPLAIN' => $lang['Copy_to_clipboard_explain'],
	'L_HIGHLIGHT_TEXT' => $lang['Highlight_text'],
	'L_PREVIEW' => $lang['Preview'],
	'L_POST_A' => $post_a,
	'L_DISABLE_HTML' => $lang['Disable_HTML_pm'], 
	'L_DISABLE_BBCODE' => $lang['Disable_BBCode_pm'], 
	'L_DISABLE_SMILIES' => $lang['Disable_Smilies_pm'], 
	'L_ATTACH_SIGNATURE' => $lang['Attach_signature'], 

	'L_EXPAND_BBCODE' => $lang['Expand_bbcode'],
	'L_BBCODE_B_HELP' => $lang['bbcode_b_help'], 
	'L_BBCODE_I_HELP' => $lang['bbcode_i_help'], 
	'L_BBCODE_U_HELP' => $lang['bbcode_u_help'], 
	'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'], 
    'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
	'L_BBCODE_L_HELP' => $lang['bbcode_l_help'], 
	'L_BBCODE_O_HELP' => $lang['bbcode_o_help'], 
	'L_BBCODE_P_HELP' => $lang['bbcode_p_help'], 
	'L_BBCODE_W_HELP' => $lang['bbcode_w_help'], 
	'L_BBCODE_A_HELP' => $lang['bbcode_a_help'], 
	'L_BBCODE_A1_HELP' => $lang['bbcode_a1_help'], 
	'L_BBCODE_S_HELP' => $lang['bbcode_s_help'], 
	'L_BBCODE_F_HELP' => $lang['bbcode_f_help'], 

	'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
	'L_BBCODE_F1_HELP' => $lang['bbcode_f1_help'],
	'L_BBCODE_G1_HELP' => $lang['bbcode_g1_help'], 
	'L_BBCODE_H1_HELP' => $lang['bbcode_h1_help'],
	'L_BBCODE_S1_HELP' => $lang['bbcode_s1_help'], 
	'L_BBCODE_SC_HELP' => $lang['bbcode_sc_help'],

	'L_SMILIE_CREATOR' => $lang['Smilie_creator'],
	'L_EMPTY_MESSAGE' => $lang['Empty_message'],

	'L_HIGHLIGHT_COLOR' => $lang['Highlight_color'], 
	'L_SHADOW_COLOR' => $lang['Shadow_color'],
	'L_GLOW_COLOR' => $lang['Glow_color'],

	'L_FONT_COLOR' => $lang['Font_color'], 
	'L_HIDE_FONT_COLOR' => $lang['hide'] . ' ' . $lang['Font_color'], 
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
	'L_COLOR_CADET_BLUE' => $lang['color_cadet_blue'],
	'L_COLOR_CORAL' => $lang['color_coral'], 
	'L_COLOR_CRIMSON' => $lang['color_crimson'], 
	'L_COLOR_TOMATO' => $lang['color_tomato'], 
	'L_COLOR_SEA_GREEN' => $lang['color_sea_green'], 
	'L_COLOR_DARK_ORCHID' => $lang['color_dark_orchid'], 
	'L_COLOR_CHOCOLATE' => $lang['color_chocolate'],
	'L_COLOR_DEEPSKYBLUE' => $lang['color_deepskyblue'], 
	'L_COLOR_GOLD' => $lang['color_gold'], 
	'L_COLOR_GRAY' => $lang['color_gray'], 
	'L_COLOR_MIDNIGHTBLUE' => $lang['color_midnightblue'], 
	'L_COLOR_DARKGREEN' => $lang['color_darkgreen'], 

	'L_FONT_SIZE' => $lang['Font_size'], 
	'L_FONT_TINY' => $lang['font_tiny'], 
	'L_FONT_SMALL' => $lang['font_small'], 
	'L_FONT_NORMAL' => $lang['font_normal'], 
	'L_FONT_LARGE' => $lang['font_large'], 
	'L_FONT_HUGE' => $lang['font_huge'], 

	'L_ALIGN_TEXT' => $lang['Align_text'], 
	'L_LEFT' => $lang['text_left'], 
	'L_CENTER' => $lang['text_center'], 
	'L_RIGHT' => $lang['text_right'], 
	'L_JUSTIFY' => $lang['text_justify'], 

	'L_FONT_FACE' => $lang['Font_face'],

	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
	'L_STYLES_TIP' => $lang['Styles_tip'], 

	'S_HTML_CHECKED' => ( !$html_on ) ? ' checked="checked"' : '', 
	'S_BBCODE_CHECKED' => ( !$bbcode_on ) ? ' checked="checked"' : '', 
	'S_SMILIES_CHECKED' => ( !$smilies_on ) ? ' checked="checked"' : '', 
	'S_SIGNATURE_CHECKED' => ( $attach_sig ) ? ' checked="checked"' : '', 
	'S_NAMES_SELECT' => $user_names_select,
	'S_HIDDEN_FORM_FIELDS' => $s_hidden_fields,
	'S_POST_ACTION' => append_sid("groupmsg.$phpEx"),
			
	'U_SEARCH_USER' => append_sid("search.$phpEx?mode=searchuser"), 
	'U_VIEW_FORUM' => append_sid("privmsg.$phpEx"))
);

if ( $board_config['message_maxlength'] )
{
	$template->assign_block_vars('switch_msg_length', array());
}

while( list($key, $font) = each($lang['font']) )
{
	$template->assign_block_vars ('font_styles', array(
		'L_FONTNAME' => $font)
	);
}

include($phpbb_root_path . 'profile_menu.'.$phpEx);

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
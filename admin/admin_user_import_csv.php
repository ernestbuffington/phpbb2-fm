<?php
/***************************************************************************
 *                            admin_user_import_csv.php
 *                            -------------------
 *   begin                : Thursday, May 5, 2004
 *   author               : Niels Chr. Denmark
 *   email                : ncr@db9.dk
 *   website			  : http://mods.db9.dk
 *
 *   $Id: admin_user_import_csv.php,v 0.9.0 2004/05/05 niels Exp $
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

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Users']['Import_User_CSV'] = $filename;
	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Set VERBOSE to 1 for debugging info..
//
define("VERBOSE", 0);

//
// Increase maximum execution time, but don't complain about it if it isn't
// allowed.
//
@set_time_limit(1200);

// -----------------------
// Page specific functions
//
function gen_rand_string($hash=false)
{
	$chars = array( '1', '2', '3', '4', '5', '6', '7', '8', '9');
	
	$max_chars = count($chars) - 1;
	srand( (double) microtime()*1000000);
	
	$rand_str = '';
	for($i = 0; $i < 8; $i++)
	{
		$rand_str .= $chars[rand(0, $max_chars)];
	}
	return ( $hash ) ? md5($rand_str) : $rand_str;
}

function add_user($username, $user_email, $password)
{
	global $lang, $db, $board_config,$phpbb_root_path,$phpEx, $userdata;
	$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
	$unhtml_specialchars_replace = array('>', '<', '"', '&');

	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
	$script_name = ( $script_name != '' ) ? $script_name . '/profile.'.$phpEx : 'profile.'.$phpEx;
	$server_name = trim($board_config['server_name']);
	$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
	$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';
	
	$server_url = $server_protocol . $server_name . $server_port . $script_name;

	$sql = "SELECT MAX(user_id) AS total
		FROM " . USERS_TABLE;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
	}
	
	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
	}
	
	$user_id = $row['total'] + 1;
	$new_password = md5($password);
	
	$sql = "INSERT INTO " . USERS_TABLE . "	(user_id, username, user_regdate, user_password, user_email, user_viewemail, user_timezone, user_dateformat, user_lang, user_style, user_level, user_active, user_actkey)
		VALUES ($user_id, '" . str_replace("\'", "''", $username) . "', " . time() . ", '" . str_replace("\'", "''", $new_password) . "', '" . str_replace("\'", "''", $user_email) . "', '0', '".$board_config['board_timezone']."', '" . $board_config['default_dateformat'] . "', '" . $board_config['default_lang']. "', '".$board_config['default_style']."', 0, ";
	if ( $board_config['require_activation'] == USER_ACTIVATION_SELF || $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
	{
		$user_actkey = gen_rand_string(true);
		$key_len = 54 - (strlen($server_url));
		$key_len = ( $key_len > 6 ) ? $key_len : 6;
		$user_actkey = substr($user_actkey, 0, $key_len);
		$sql .= "0, '" . str_replace("\'", "''", $user_actkey) . "')";
	}
	else
	{
		$sql .= "1, '')";
	}

	if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
	{
		message_die(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
	}

	$sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator)
		VALUES ('', 'Personal User', 1, 0)";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
	}

	$group_id = $db->sql_nextid();
	$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
		VALUES ($user_id, $group_id, 0)";
	if( !($result = $db->sql_query($sql, END_TRANSACTION)) )
	{
		message_die(GENERAL_ERROR, 'Could not insert data into user_group table', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $board_config['require_activation'] == USER_ACTIVATION_SELF )
	{
		$message = $lang['Account_inactive'];
		$email_template = 'user_welcome_inactive';
	}
	else if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
	{
		$message = $lang['Account_inactive_admin'];
		$email_template = 'admin_welcome_inactive';
	}
	else
	{
		$message = $lang['Account_added'];
		$email_template = 'user_welcome';
	}

	include_once($phpbb_root_path . 'includes/emailer.'.$phpEx);
	$emailer = new emailer($board_config['smtp_delivery']);

	$emailer->from($board_config['board_email']);
	$emailer->replyto($board_config['board_email']);

	$emailer->use_template($email_template, $board_config['default_lang']);
	$emailer->email_address($user_email);
	$emailer->set_subject(sprintf($lang['Welcome_subject'], $board_config['sitename']));
	$emailer->assign_vars(array(
		'SITENAME' => $board_config['sitename'],
		'WELCOME_MSG' => sprintf($lang['Welcome_subject'], $board_config['sitename']),
		'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
		'PASSWORD' => $password,
		'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),

		'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
	);
	
	$emailer->send();
	$emailer->reset();
	
	if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
	{
		$sql = "SELECT user_email, user_lang 
			FROM " . USERS_TABLE . "
			WHERE user_level = " . ADMIN;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not select Administrators', '', __LINE__, __FILE__, $sql);
		}
				
		while ($row = $db->sql_fetchrow($result))
		{
			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);
			
			$emailer->email_address(trim($row['user_email']));
			$emailer->use_template("admin_activate", $row['user_lang']);
			$emailer->set_subject($lang['New_account_subject']);

			$emailer->assign_vars(array(
				'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
				'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),
				'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
			);
	
			$emailer->send();
			$emailer->reset();
		}
	}
	$db->sql_freeresult($result);
}

//
// End page specific functions
// ---------------------------

//
// Begin program proper
//
if(!isset($HTTP_POST_VARS['import_start']))
{
	include('./page_header_admin.'.$phpEx);
	
	$template->set_filenames(array(
		'body' => 'admin/user_import_csv_body.tpl')
	);

	$s_hidden_fields = '';

	$template->assign_vars(array(
		'L_IMPORT' => $lang['Import_users'],
		'L_IMPORT_EXPLAIN' => sprintf($lang['Import_explain'], '<a href="http://www.creativyst.com/Doc/Articles/CSV/CSV01.htm#Overview" target="_blank">', '</a>'),
		'L_SELECT_FILE' => $lang['Select_file'],
		'L_START_IMPORT' => $lang['Start_Import'],
		
		'S_IMPORT_ACTION' => append_sid('admin_user_import_csv.'.$phpEx),
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);
	
	$template->pparse("body");
} 
else
{
	//
	// Handle the file upload ....
	// If no file was uploaded report an error...
	//
	$import_file_name = (!empty($HTTP_POST_FILES['import_file']['name'])) ? $HTTP_POST_FILES['import_file']['name'] : "";
	$import_file_tmpname = ($HTTP_POST_FILES['import_file']['tmp_name'] != "none") ? $HTTP_POST_FILES['import_file']['tmp_name'] : "";
	$import_file_type = (!empty($HTTP_POST_FILES['import_file']['type'])) ? $HTTP_POST_FILES['import_file']['type'] : "";
	
	if ( $import_file_tmpname == '' || $import_file_name == '' )
	{
		message_die(GENERAL_MESSAGE, $lang['Import_Error_no_file']);
	}

	//
	// If a file was actually uploaded, check to make sure that we
	// are actually passed the name of an uploaded file, and not
	// a hackers attempt at getting us to process a local system
	// file.
	//
	if( file_exists(phpbb_realpath($import_file_tmpname)) )
	{
		if (! $import_lines = file($import_file_tmpname) )
		{
			message_die(GENERAL_ERROR, $lang['Import_Error_uploading']);
		}
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['Import_Error_uploading']);
	}
	
	// import users here
	$num_users = 0;
	$num_users_not_import = 0;
	$num_users_not_import = 0;
	$error_msg = '';
	
	include_once($phpbb_root_path . 'includes/functions_validate.'.$phpEx);

	foreach($import_lines as $users )
	{
		list($user_email) = split (";",$users);

		$user_email = trim($user_email);
		list ($username,$userdomain) = split("@",$user_email);
		$username = trim($username);
		
		$result = validate_email($user_email);
		if ( $result['error'] )
		{
			$num_users_not_import++;
			$error_msg .= "<br /><i>Skipped: $user_email</i>";
		} else
		{
			$original_username = $username;
			$i=0;
			do
			{
				if (strtolower($username) == strtolower($userdata['username']))
				{
					$result['error'] = true;
				} 
				else
				{
					$result = validate_username($username);
				}
				if ( $result['error'] )
				{
					// the username is not valid, we will try generate a new based on the old
					$username = $original_username . $i++;
				} 
				else
				{
					// we have now found a username to use.
					$error_msg .= "<br /><b>" . ( ($i == 0) ? $lang['Username'] . ": " . $username . "</b>" : $lang['Username'] . ": " . $original_username . " -> " . $username . "</b>");
					$num_users++;
				}
				
			} 
			while ( $result['error'] );

			//
			// go ahead import the user
			//

			$user_password = gen_rand_string();
			$user_password = $username.substr($user_password, 0, 2);

			$error_msg .= " pass: " . $user_password;
			add_user($username,$user_email,$user_password);
		
		}
	}
	
	include('./page_header_admin.'.$phpEx);
	$template->set_filenames(array(
		'body' => 'admin/admin_message_body.tpl')
	);
	
	$message = sprintf($lang['Import_success'], $num_users, $num_users_not_import);

	if (VERBOSE)
	{
		$message .= $error_msg;	
	}

	$template->assign_vars(array(
		'MESSAGE_TITLE' => $lang['Import_users'],
		'MESSAGE_TEXT' => $message)
	);

	$template->pparse('body');
}

include('./page_footer_admin.'.$phpEx);

?>
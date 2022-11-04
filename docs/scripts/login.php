<?php
/** 
 *
* @package phpBB2
* @version $Id: login.php,v 1.47.2.24 2006/04/22 20:28:42 grahamje Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

//
// Allow people to reach login page if
// board is shut down
//
define("IN_LOGIN", true);

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'digests_common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//
	
include_once(PRILL_PATH . 'prill_common.' . $phpEx);
	
// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}

if( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || isset($HTTP_POST_VARS['logout']) || isset($HTTP_GET_VARS['logout']) )
{
	if( ( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) ) && (!$userdata['session_logged_in'] || isset($HTTP_POST_VARS['admin'])) )
	{
		$username = isset($HTTP_POST_VARS['username']) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
		$password = isset($HTTP_POST_VARS['password']) ? $HTTP_POST_VARS['password'] : '';

		$sql = "SELECT user_id, username, user_password, user_active, user_level, user_login_tries, user_last_login_try, user_allow_viewonline, user_digest_status, user_rank, user_actviate_date, user_expire_date, user_regdate, user_totallogon
			FROM " . USERS_TABLE . "
			WHERE username = '" . str_replace("\\'", "''", $username) . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
		}

		if( $row = $db->sql_fetchrow($result) )
		{
			$disable_mode = explode(',', $board_config['board_disable_mode']);
			if ($board_config['board_disable'] && $row['user_level'] != ADMIN && in_array($row['user_level'], $disable_mode))
			{
				redirect(append_sid("portal.$phpEx", true));
			}
			else
			{
				if( ($row['user_password'] == 'Reset2WhateverTheyFirstLoginWith') && ($password != '') )
				{
					$row['user_password'] = md5($password);
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_password = '" . $row['user_password'] . "'
						WHERE user_id = " . $row['user_id'];
					if ( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql_update);
					}
				}
				
				// If the last login is more than x minutes ago, then reset the login tries/time
            	if ($row['user_last_login_try'] && $board_config['login_reset_time'] && $row['user_last_login_try'] < (time() - ($board_config['login_reset_time'] * 60)))
            	{
            	   $db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);
            	   $row['user_last_login_try'] = $row['user_login_tries'] = 0;
            	}
            	
            	// Check to see if user is allowed to login again... if his tries are exceeded
            	if ($row['user_last_login_try'] && $board_config['login_reset_time'] && $board_config['max_login_attempts'] && $row['user_last_login_try'] >= (time() - ($board_config['login_reset_time'] * 60)) && $row['user_login_tries'] >= $board_config['max_login_attempts'] && $userdata['user_level'] != ADMIN)
            	{
            	   message_die(GENERAL_MESSAGE, sprintf($lang['Login_attempts_exceeded'], $board_config['max_login_attempts'], $board_config['login_reset_time']));
            	}

				if( md5($password) == $row['user_password'] && $row['user_active'] )
				{
					lw_check_membership($row);
					$autologin = ( isset($HTTP_POST_VARS['autologin']) ) ? TRUE : 0;

					$admin = (isset($HTTP_POST_VARS['admin'])) ? 1 : 0;
            		$session_id = session_begin($row['user_id'], $user_ip, PAGE_INDEX, FALSE, $autologin, $admin);

					// Reset login tries
            	   	$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);

					if( $session_id )
					{
						//
						// Send Welcome PM on first login (if PMs are enabled)
						//
						if ( !$board_config['privmsg_disable'] && !$board_config['wpm_disable'] && !$row['user_totallogon'] )
						{
							$sql = "UPDATE " . USERS_TABLE . " 
								SET user_new_privmsg = 1, user_last_privmsg = 9999999999
								WHERE user_id = " . $row['user_id'];
							if ( !($result = $db->sql_query($sql)) )
							{
								message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql);
							}
		
							$register_pm_subject = $lang['register_pm_subject'];
						    $register_pm = $lang['register_pm'];
						    $privmsgs_date = date('U');
						    $sql = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig) 
						    	VALUES (0, '" . str_replace("\'", "''", addslashes(sprintf($register_pm_subject,$board_config['sitename']))) . "', 2, " . $row['user_id'] . ", " . $privmsgs_date . ", 0, 1, 1, 0)";
						    if ( !$db->sql_query($sql) )
							{
								message_die(GENERAL_ERROR, 'Could not insert private message sent info', '', __LINE__, __FILE__, $sql);
							}
	
							$privmsg_sent_id = $db->sql_nextid();
							$privmsgs_text = $lang['register_pm_subject'];
	
							$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_text) 
								VALUES ($privmsg_sent_id, '" . str_replace("\'", "''", addslashes(sprintf($register_pm,$board_config['sitename'],$board_config['sitename']))) . "')";
							if ( !$db->sql_query($sql) )
							{
								message_die(GENERAL_ERROR, 'Could not insert private message sent text', '', __LINE__, __FILE__, $sql);
							}
						}	

						$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "portal.$phpEx";

						// Digests	
						if ($row['user_digest_status'] > 0)
						{
							if (($row['user_digest_status'] == 1) || ($row['user_digest_status'] == 3))
							{
								$sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
									VALUES (" . $digest_config['auto_subscribe_group'] . ", " . $row['user_id'] . ", 0)";
								if (!($result = $db->sql_query($sql)))
								{
									message_die(GENERAL_ERROR, 'Could not insert data into user groups table', '', __LINE__, __FILE__, $sql);
								}
							}

							if (($row['user_digest_status'] == 2) || ($row['user_digest_status'] == 3))
							{
								$url = 'digests.php';
							}

							$sql = "UPDATE " . USERS_TABLE . "
								SET user_digest_status = '0'
								WHERE user_id = " . $row['user_id'];
							$result = $db->sql_query($sql);
						}
						redirect(append_sid($url, true));
					}
					else
					{
						message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
					}
				}
				// Only store a failed login attempt for an active user - inactive users can't login even with a correct password
				elseif( $row['user_active'] )
				{
					// Save login tries and last login
               		if ($row['user_id'] != ANONYMOUS)
               		{
                  		$sql = 'UPDATE ' . USERS_TABLE . '
                    		SET user_login_tries = user_login_tries + 1, user_last_login_try = ' . time() . '
                     		WHERE user_id = ' . $row['user_id'];
                  		$db->sql_query($sql);
               		}
				}
				
				$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : '';
				$redirect = str_replace('?', '&', $redirect);

				if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r") || strstr(urldecode($redirect), ';url'))
               	{
                	message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
               	} 

				$template->assign_vars(array(
					'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
				);

				$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("portal.$phpEx") . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}
		}
		else
		{
			$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "";
			$redirect = str_replace("?", "&", $redirect);

			if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r") || strstr(urldecode($redirect), ';url'))
            {
            	message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
			} 
               
			$template->assign_vars(array(
				'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
			);
               
			$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("portal.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else if( isset($HTTP_GET_VARS['admin_session_logout']) && $userdata['user_level'] == ADMIN )
	{
		// session id check
		if ( $sid == '' || $sid != $userdata['session_id'] )
		{
			message_die(GENERAL_ERROR, 'Invalid_session');
		}
		
		$sql = "UPDATE " . SESSIONS_TABLE . "
			SET session_admin = 0
			WHERE session_id = '" . $userdata['session_id'] . "'";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(CRITICAL_ERROR, 'Couldn\'t update Sessions Table', '', __LINE__, __FILE__, $sql);
		}
		
		redirect(append_sid("index.$phpEx", true));
	}
	else if( ( isset($HTTP_GET_VARS['logout']) || isset($HTTP_POST_VARS['logout']) ) && $userdata['session_logged_in'] )
	{
		// session id check
		if ($sid == '' || $sid != $userdata['session_id'])
		{
			message_die(GENERAL_ERROR, 'Invalid_session');
		}

		if( $userdata['session_logged_in'] )
		{
			session_end($userdata['session_id'], $userdata['user_id']);
		}

		if ( !empty($_REQUEST['in_prill']) )
		{
			im_session_update(true, true);
		}

		if (!empty($HTTP_POST_VARS['redirect']) || !empty($HTTP_GET_VARS['redirect']))
		{
			$url = (!empty($HTTP_POST_VARS['redirect'])) ? htmlspecialchars($HTTP_POST_VARS['redirect']) : htmlspecialchars($HTTP_GET_VARS['redirect']); 
			$url = str_replace('&amp;', '&', $url); 
			redirect(append_sid($url, true));
		}
		else
		{
			redirect(append_sid("portal.$phpEx", true));
		}
	}
	else
	{
		$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "portal.$phpEx";
		redirect(append_sid($url, true));
	}
}
else
{
	//
	// Do a full login page dohickey if
	// user not already logged in
	//
 	if( !$userdata['session_logged_in'] || (isset($HTTP_GET_VARS['admin']) && $userdata['session_logged_in'] && $userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD))
	{
		$page_title = $lang['Login'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$body_tpl = '';
		if( $gen_simple_header )
		{
			$body_tpl = 'prillian/';
		}

		$template->set_filenames(array(
			'body' => $body_tpl . 'login_body.tpl')
		);

		$forward_page = '';

		if( isset($HTTP_POST_VARS['redirect']) || isset($HTTP_GET_VARS['redirect']) )
		{
			$forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

			if( preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
			{
				$forward_to = ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
				$forward_match = explode('&', $forward_to);

				if(count($forward_match) > 1)
				{
					for($i = 1; $i < count($forward_match); $i++)
					{
						if( !ereg("sid=", $forward_match[$i]) )
						{
							if( $forward_page != '' )
							{
								$forward_page .= '&';
							}
							$forward_page .= $forward_match[$i];
						}
					}
					$forward_page = $forward_match[0] . '?' . $forward_page;
				}
				else
				{
					$forward_page = $forward_match[0];
				}
			}
		}

		$username = ( $userdata['user_id'] != ANONYMOUS ) ? $userdata['username'] : '';

		$s_hidden_fields = '<input type="hidden" name="redirect" value="' . $forward_page . '" />';
		$s_hidden_fields .= (isset($HTTP_GET_VARS['admin'])) ? '<input type="hidden" name="admin" value="1" />' : '';

		$template->assign_vars(array(
			'USERNAME' => $username,

			'L_ENTER_PASSWORD' => (isset($HTTP_GET_VARS['admin'])) ? $lang['Admin_reauthenticate'] : $lang['Enter_password'],
			'L_SEND_PASSWORD' => $lang['Forgotten_password'],
			'L_SEND_ACTIVATION' => $lang['Resend_Activation'],

			'L_HIDE_ME' => $lang['Hide_me'],
			'L_TERMS' => $lang['Terms_of_use'],

			'U_SEND_PASSWORD' => append_sid("profile.$phpEx?mode=sendpassword"),
			'U_SEND_ACTIVATION' => append_sid("profile.$phpEx?mode=resendactivation"),

			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('body');

		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	}
	else
	{
		redirect(append_sid("portal.$phpEx", true));
	}

}

?>
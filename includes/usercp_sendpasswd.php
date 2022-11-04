<?php
/** 
*
* @package includes
* @version $Id: usercp_sendpasswd.php,v 1.6.2.12 2004/11/18 17:49:45 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
/*

	This code has been modified from its original form by psoTFX @ phpbb.com
	Changes introduce the back-ported phpBB 3 visual confirmation code. 

	NOTE: Anyone using the modified code contained within this script MUST include
	a relevant message such as this in usercp_sendpassword.php ... failure to do so 
	will affect a breach of Section 2a of the GPL and our copyright

	png visual confirmation system : (c) phpBB Group, 2003 : All Rights Reserved

*/

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}

if ( isset($HTTP_POST_VARS['submit']) )
{
	$username = ( !empty($HTTP_POST_VARS['username']) ) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
	$email = ( !empty($HTTP_POST_VARS['email']) ) ? trim(strip_tags(htmlspecialchars($HTTP_POST_VARS['email']))) : '';

	$sql = "SELECT user_id, username, user_email, user_active, user_lang 
		FROM " . USERS_TABLE . " 
		WHERE user_email = '" . str_replace("\'", "''", $email) . "' 
			AND username = '" . str_replace("\'", "''", $username) . "'";
	if ( $result = $db->sql_query($sql) )
	{
		if ( $row = $db->sql_fetchrow($result) )
		{
			if ( !$row['user_active'] )
			{
				message_die(GENERAL_MESSAGE, $lang['No_send_account_inactive']);
			}

         	//
         	// Visual Confirmation
         	//
         	if ( $board_config['enable_confirm'] )
         	{
            	if ( empty($HTTP_POST_VARS['confirm_id']) || empty($HTTP_POST_VARS['confirm_code']) )
            	{
            	   message_die(GENERAL_MESSAGE, $lang['Confirm_code_wrong']);
            	}
            	else
            	{
               		$confirm_id = htmlspecialchars($HTTP_POST_VARS['confirm_id']);
               		if (!preg_match('/^[A-Za-z0-9]+$/', $confirm_id))
               		{
                		$confirm_id = '';
               		}

               		$sql_vc = 'SELECT code
                    	FROM ' . CONFIRM_TABLE . "
                        WHERE confirm_id = '$confirm_id'
                        	AND session_id = '" . $userdata['session_id'] . "'";
               		if (!($result_vc = $db->sql_query($sql_vc)))
               		{
                  		message_die(GENERAL_ERROR, 'Could not obtain confirmation code', __LINE__, __FILE__, $sql_vc);
               		}

               		if ($row_vc = $db->sql_fetchrow($result_vc))
               		{	
                  		if ($row_vc['code'] != $HTTP_POST_VARS['confirm_code'])
                  		{
                     		message_die(GENERAL_MESSAGE, $lang['Confirm_code_wrong']);
                  		}
                  		else
                  		{
                     		$sql_vc = 'DELETE FROM ' . CONFIRM_TABLE . "
                            	WHERE confirm_id = '$confirm_id'
                            		AND session_id = '" . $userdata['session_id'] . "'";
                     		if (!$db->sql_query($sql_vc))
                     		{
                        		message_die(GENERAL_ERROR, 'Could not delete confirmation code', __LINE__, __FILE__, $sql_vc);
                     		}
                  		}
               		}
               		else
               		{
                		message_die(GENERAL_MESSAGE, $lang['Confirm_code_wrong']);
               		}
               		$db->sql_freeresult($result_vc);
            	}
         	}
			
			$username = $row['username'];
			$user_id = $row['user_id'];

			$user_actkey = gen_rand_string(true);
			$key_len = 54 - strlen($server_url);
			$key_len = ($key_len > 6) ? $key_len : 6;
			$user_actkey = substr($user_actkey, 0, $key_len);
			$user_password = gen_rand_string(false);
			
			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_newpasswd = 'Reset2WhateverTheyFirstLoginWith', user_actkey = '$user_actkey'
				WHERE user_id = " . $row['user_id'];
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update new password information', '', __LINE__, __FILE__, $sql);
			}

			include($phpbb_root_path . 'includes/emailer.'.$phpEx);
			$emailer = new emailer($board_config['smtp_delivery']);

			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);

			$emailer->use_template('user_activate_passwd', $row['user_lang']);
			$emailer->email_address($row['user_email']);
			$emailer->set_subject($lang['New_password_activation']);

			$emailer->assign_vars(array(
				'SITENAME' => $board_config['sitename'], 
				'USERNAME' => $username,
				'PASSWORD' => $user_password,
				'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '', 

				'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
			);
			$emailer->send();
			$emailer->reset();

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="15;url=' . append_sid("index.$phpEx") . '">')
			);

			$message = $lang['Password_updated'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_email_match']);
		}
	}
	else
	{
		message_die(GENERAL_ERROR, 'Could not obtain user information for sendpassword', '', __LINE__, __FILE__, $sql);
	}
}
else
{
	$username = '';
	$email = '';
}

//
// Output basic page
//
$page_title = $lang['Send_password'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'profile_send_pass.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

//
// Visual Confirmation
//
$confirm_image = $s_hidden_fields = '';
if (!empty($board_config['enable_confirm']))
{
   $sql = 'SELECT session_id
            FROM ' . SESSIONS_TABLE;
   if (!($result = $db->sql_query($sql)))
   {
      message_die(GENERAL_ERROR, 'Could not select session data', '', __LINE__, __FILE__, $sql);
   }

   if ($row = $db->sql_fetchrow($result))
   {
      $confirm_sql = '';
      do
      {
         $confirm_sql .= (($confirm_sql != '') ? ', ' : '') . "'" . $row['session_id'] . "'";
      }
      while ($row = $db->sql_fetchrow($result));

      $sql = 'DELETE FROM ' .  CONFIRM_TABLE . "
               WHERE session_id NOT IN ($confirm_sql)";
      if (!$db->sql_query($sql))
      {
         message_die(GENERAL_ERROR, 'Could not delete stale confirm data', '', __LINE__, __FILE__, $sql);
      }
   }
   $db->sql_freeresult($result);

   $sql = 'SELECT COUNT(session_id) AS attempts
      FROM ' . CONFIRM_TABLE . "
      WHERE session_id = '" . $userdata['session_id'] . "'";
   if (!($result = $db->sql_query($sql)))
   {
      message_die(GENERAL_ERROR, 'Could not obtain confirm code count', '', __LINE__, __FILE__, $sql);
   }

   if ($row = $db->sql_fetchrow($result))
   {
      if ($row['attempts'] > 3)
      {
         message_die(GENERAL_MESSAGE, $lang['Too_many_registers']);
      }
   }
   $db->sql_freeresult($result);
   
   // Generate the required confirmation code
   // NB 0 (zero) could get confused with O (the letter) so we make change it
   $code = dss_rand();
   $code = substr(str_replace('0', 'Z', strtoupper(base_convert($code, 16, 35))), 2, 6);

   $confirm_id = md5(uniqid($user_ip));

   $sql = 'INSERT INTO ' . CONFIRM_TABLE . " (confirm_id, session_id, code)
      VALUES ('$confirm_id', '". $userdata['session_id'] . "', '$code')";
   if (!$db->sql_query($sql))
   {
      message_die(GENERAL_ERROR, 'Could not insert new confirm code information', '', __LINE__, __FILE__, $sql);
   }

   unset($code);
      
   $confirm_image = '<img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id") . '" alt="" title="" />';
   $s_hidden_fields .= '<input type="hidden" name="confirm_id" value="' . $confirm_id . '" />';

   $template->assign_block_vars('switch_confirm', array());
}

$template->assign_vars(array(
	'USERNAME' => $username,
	'EMAIL' => $email,

	'L_SEND_PASSWORD' => $lang['Send_password'], 
	'L_ITEMS_REQUIRED' => $lang['Items_required'],
	'L_EMAIL_ADDRESS' => $lang['Email_address'],
	'L_CONFIRM_CODE_TITLE' => $lang['Confirm_code_title'], 
	'L_CONFIRM_CODE_IMPAIRED'=> sprintf($lang['Confirm_code_impaired'], '<a href="mailto:' . $board_config['board_email'] . '">', '</a>'),
	'L_CONFIRM_CODE' => $lang['Confirm_code'],
	'L_CONFIRM_CODE_EXPLAIN' => $lang['Confirm_code_explain'],
	
	'CONFIRM_IMG' => $confirm_image,
	'S_HIDDEN_FIELDS' => $s_hidden_fields,

	'S_PROFILE_ACTION' => append_sid("profile.$phpEx?mode=sendpassword"))
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
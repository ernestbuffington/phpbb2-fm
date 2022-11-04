<?php
/** 
*
* @package phpBB
* @version $Id: viewtopic_tellafriend.php,v 1.0.0 2005/07/19 20:01:21 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

$topic = ( isset($HTTP_POST_VARS['topic']) ) ? $HTTP_POST_VARS['topic'] : $HTTP_GET_VARS['topic']; 
$friendname =  $HTTP_POST_VARS['friendname'];
$message = $HTTP_POST_VARS['message'];
$link = $HTTP_GET_VARS['link'];
$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_TELLFRIEND);
init_userprefs($userdata);
//
// End session management
//

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=$PHP_SELF&topic=$topic&link=$link"); 
	exit; 
} 

if ( !$board_config['enable_tellafriend'] )
{ 
	message_die(GENERAL_MESSAGE, $lang['Tell_Friend_Disabled']); 
}

$page_title = $lang['Tell_Friend'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$mail_body = str_replace("{TOPIC}", trim(stripslashes($topic)), $lang['Tell_Friend_Body']);
$mail_body = str_replace("{LINK}", $link, $mail_body);
$mail_body = str_replace("{SITENAME}", $board_config['sitename'], $mail_body);

$template->assign_vars(array(
	'L_TOPIC' => trim(stripslashes($topic)),
	'U_TOPIC' => $link,
	'L_TELL_FRIEND_TITLE' => $lang['Tell_Friend'],
	'L_TELL_FRIEND_SENDER_USER' => $lang['Username'],
	'L_TELL_FRIEND_SENDER_EMAIL' => $lang['Email'],
	'L_TELL_FRIEND_RECIEVER_USER' => $lang['Tell_Friend_Reciever_User'],
	'L_TELL_FRIEND_RECIEVER_EMAIL' => $lang['Tell_Friend_Reciever_Email'],
	'L_TELL_FRIEND_MSG' => $lang['Message'],
	'L_TELL_FRIEND_BODY' => $mail_body,
 
	'SUBMIT_ACTION' => append_sid("$PHP_SELF", true),
	'L_SUBMIT' => $lang['Send_email'],
	'TOPIC' => trim(stripslashes($topic)), 
	'LINK' => $link, 
	'SENDER_NAME' => $userdata['username'], 
	'SENDER_MAIL' => $userdata['user_email'],)
);

/**************/
		if ( isset($HTTP_POST_VARS['submit']) )
		{
			$error = FALSE;

			if ( !empty($HTTP_POST_VARS['friendemail']) && (strpos($HTTP_POST_VARS['friendemail'], '@') > 0) )
			{
				$friendemail = trim(stripslashes($HTTP_POST_VARS['friendemail']));
				if (!$HTTP_POST_VARS['friendname']) 
				{ 
					$friendname = substr($friendemail, 0, strpos($HTTP_POST_VARS['friendemail'], '@')); 
				}
			}
			else
			{
				$error = TRUE;
				$error_msg = $lang['Email_invalid'];
			}

			if ( !$error )
			{
					include($phpbb_root_path . 'includes/emailer.'.$phpEx);
					
					$emailer = new emailer($board_config['smtp_delivery']);

					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);

					//$email_headers = 'Return-Path: ' . $userdata['user_email'] . "\nFrom: ". $userdata['username'] .'<'. $userdata['user_email'] .'>' . "\n";
					$emailer->use_template('tellafriend_email', $userdata['user_lang']);
					$emailer->email_address($friendemail);
					$emailer->set_subject(trim(stripslashes($topic)));
				
					// The above line did not work for some people, so we use the simpler below line
					$email_headers = 'Return-Path: ' . $userdata['user_email'] . "\nFrom: ". $userdata['user_email'] ."\n";
					$email_headers .= 'X-AntiAbuse: Board servername - ' . $server_name . "\n";
					$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
					$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
					$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\r\n";
					$emailer->extra_headers($email_headers);

					$emailer->assign_vars(array(
						'SITENAME' => $board_config['sitename'], 
						'BOARD_EMAIL' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '', 
 						'FROM_USERNAME' => $userdata['username'], 
						'TO_USERNAME' => $friendname, 
						'MESSAGE' => $message)
					);
					$emailer->send();
					$emailer->reset();

					$template->assign_vars(array(
						'META' => '<meta http-equiv="refresh" content="5;url=' . $link . '">')
					);

					$message = $lang['Email_sent'] . '<br /><br />' . sprintf($lang['Click_return_topic'],  '<a href="' . $link . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
			}

			if ( $error )
			{
				$template->set_filenames(array(
					'reg_header' => 'error_body.tpl')
				);
				$template->assign_vars(array(
					'ERROR_MESSAGE' => $error_msg)
				);
				$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
			}

		}


$template->set_filenames(array(
	'body' => 'topic_tellafriend_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx); 

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
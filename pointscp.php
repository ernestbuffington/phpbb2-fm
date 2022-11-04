<?php
/** 
*
* @package phpBB2
* @version $Id: pointscp.php,v 1.0.1 2003/12/08 17:13:00 Robbie Shields Exp $
* @copyright (c) 2002 Bulletin Board Mods
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
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

//
// Program Start
//
if (isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']))
{
	$mode = (isset($HTTP_POST_VARS['mode'])) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

$user_id = (isset($HTTP_GET_VARS[POST_USERS_URL])) ? intval($HTTP_GET_VARS[POST_USERS_URL]) : 0;

$template->set_filenames(array(
	'body' => 'points_system_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

//Start
if ($mode == 'donate')
{
	if ( !$userdata['session_logged_in'] ) 
	{
		$redirect = "pointscp.$phpEx&mode=donate";
		$redirect .= (isset($user_id)) ? '&' . POST_USERS_URL . '=' . $user_id : '';
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}
	
	if (!$board_config['points_donate'])
	{
		message_die(GENERAL_MESSAGE, $lang['Points_user_donation_off']);
	}
	
	if (isset($HTTP_POST_VARS['submit']))
	{
		if (!empty($HTTP_POST_VARS['username']))
		{
			$user_id = get_userid_from_name($HTTP_POST_VARS['username']);

			if (empty($user_id))
			{
				$error = true;
				$error_msg .= $lang['No_such_user'];
			}
			
			if ($user_id == $userdata['user_id'])
			{
				$error = true;
				$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . sprintf($lang['Points_cant_donate_self'], $board_config['points_name']);
			}
			if(empty($HTTP_POST_VARS['reason_donate']))
			{
				$error = true;
				$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $lang['Points_no_reason_donate'];
			}
		}
		else
		{
			$error = true;
			$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . $lang['Points_no_username'];
		}

		if (abs(intval($HTTP_POST_VARS['amount'])) == 0)
		{
			$error = true;
			$error_msg .= ((!empty($error_msg)) ? '<br />' : '') . sprintf($lang['Points_enter_some_donate'], $board_config['points_name']);
		}

		if (isset($error))
		{
			$template->set_filenames(array(
				'reg_header' => 'error_body.tpl')
			);
			$template->assign_vars(array(
				'ERROR_MESSAGE' => $error_msg)
			);
			$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
		}
		else
		{
			$amount = abs(intval($HTTP_POST_VARS['amount']));
			$from_points = get_user_points($userdata['user_id']);
			$reason_donate = $HTTP_POST_VARS['reason_donate'];			
			
			if ($amount > $from_points)
			{
				message_die(GENERAL_MESSAGE, sprintf($lang['Points_cant_donate'], $board_config['points_name']));
			}

			add_points($user_id, $amount);
			subtract_points($userdata['user_id'], $amount);
			
			// Transactions 
			if( $amount != 0 )
			{
				$username = get_username_from_id($user_id);
				$sql = "INSERT INTO " . TRANSACTION_TABLE . " (trans_date, trans_from, trans_to, trans_amount, trans_reason)
					VALUES ( " . time() . ", '" . phpbb_clean_username($userdata['username']) . "', '" . phpbb_clean_username($username) . "', '$amount', '" . phpbb_clean_username($reason_donate) . "')";
				if( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Could not insert data into transaction table", '', __LINE__, __FILE__, $sql);
				}
			}
	
			// Send donation email if the user wants it
			$sql = "SELECT username, user_lang, user_email, user_notify_donation 
 				FROM " . USERS_TABLE . "
				WHERE user_id = $user_id";
			if (!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, "Could not get username & user_notify_donation & user_lang & user_email", '', __LINE__, __FILE__, $sql);
			}
			$to_userdata = $db->sql_fetchrow($result);

			if ($to_userdata['user_notify_donation'])
			{
				include($phpbb_root_path . 'includes/emailer.'.$phpEx);
				$emailer = new emailer($board_config['smtp_delivery']);

				$email_headers = 'From: ' . $board_config['board_email'] . "\nReturn-Path: " . $board_config['board_email'] . "\n";

				$emailer->use_template('user_notify_donation', $to_userdata['user_lang']);
				$emailer->email_address($to_userdata['user_email']);
				$emailer->extra_headers($email_headers);

				$emailer->assign_vars(array(
					'DONATOR' => $userdata['username'],
					'USERNAME' => $to_userdata['username'],
					'AMOUNT_DONATE' => $amount,
					'REASON_DONATE' => $reason_donate,
					'TOTAL_POINTS' => get_user_points($user_id),
					'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),
					
					'L_POINTS' => $board_config['points_name'])
				);

				$emailer->send();
				$emailer->reset();
			}

			$loc = (!empty($HTTP_POST_VARS['location'])) ? $HTTP_POST_VARS['location'] : append_sid("pointscp.$phpEx?mode=donate");

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . $loc . '">')
			);

			$msg = $lang['Points_thanks_donation'] . '<br /><br />' . sprintf($lang['Click_return_points_donate'], '<a href="' . append_sid("pointscp.$phpEx?mode=donate") . '">', '</a>', $board_config['points_name']) . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
			
			message_die(GENERAL_MESSAGE, $msg);
		}
	}

	$s_username = (!empty($user_id)) ? get_username_from_id($user_id) : '';
	$location = (empty($HTTP_POST_VARS['location'])) ? $HTTP_SERVER_VARS['HTTP_REFERER'] : $location;
	$s_hidden_fields = '<input type="hidden" name="location" value="' . $location . '">';

	$template->assign_vars(array(
	 	'MOD_VERSION' => $board_config['points_system_version'],
		'L_FIND_USERNAME' => $lang['Find_username'],
		'L_POINTS_TITLE' => sprintf($lang['Points_donation'], $board_config['points_name']),
		'L_DONATE_TO' => sprintf($lang['Points_donate_to'], $board_config['points_name']),
		'L_AMOUNT' => $lang['Points_amount'],
		'L_AMOUNT_GIVE' => sprintf($lang['Points_give'], $board_config['points_name']),
		'L_REASON' => $lang['Points_reason'],
		'L_REASON_DONATE' => $lang['Points_reason_donate'],

		'USERNAME' => $s_username,
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_POST_ACTION' => append_sid("pointscp.$phpEx?mode=donate", true),
		'U_SEARCH_USER' => append_sid("search.$phpEx?mode=searchuser"))
	);
		
	$template->assign_block_vars('switch_points_donate', array());
}
else
{
	if ( !$userdata['session_logged_in'] )
	{
		$redirect = "pointscp.$phpEx";
		$redirect .= (isset($user_id)) ? '&user_id=' . $user_id : '';
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

	if ($userdata['user_level'] != ADMIN && user_is_authed($userdata['user_id']) == false)
	{
		message_die(GENERAL_MESSAGE, sprintf($lang['Points_not_admin'], $board_config['points_name']));
	}
	
	if (isset($HTTP_POST_VARS['submit']))
	{
		if (empty($HTTP_POST_VARS['username']) && empty($HTTP_POST_VARS['mass_username']))
		{
			$error = true;
			$error_msg .= $lang['Points_no_username'];
		}
		else
		{
			$user_id = get_userid_from_name($HTTP_POST_VARS['username']);
			
			if (empty($user_id) && empty($HTTP_POST_VARS['mass_username']))
			{
				$error = true;
				$error_msg = $lang['No_such_user'];
			}
			else
			{
				$user_id_list = array();
				$user_id_list[] = $user_id;

				if (!empty($HTTP_POST_VARS['mass_username']))
				{
					$mass_usernames = explode("\n", $HTTP_POST_VARS['mass_username']);

					foreach ($mass_usernames as $username)
					{
						$username = trim($username);

						if (!empty($username))
						{
							$user_id_list[] = get_userid_from_name($username);
						}
					}
				}
				
				$user_id_list = array_unique($user_id_list);
			}
		}

		if (isset($error))
		{
			$template->set_filenames(array(
				'reg_header' => 'error_body.tpl')
			);
			$template->assign_vars(array(
				'ERROR_MESSAGE' => $error_msg)
			);
			$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
		}
		else
		{
			$amount = abs(intval($HTTP_POST_VARS['amount']));
			$method_function = ($HTTP_POST_VARS['method']) ? 'add_points' : 'subtract_points';

			foreach ($user_id_list as $user_id)
			{
				if (!empty($user_id))
				{
					$method_function($user_id, $amount);
					admin_track($user_id, $method_function, $amount);					
				}
			}

			$loc = (isset($HTTP_POST_VARS['location']) && !empty($HTTP_POST_VARS['location'])) ? $HTTP_POST_VARS['location'] : append_sid("pointscp.$phpEx?mode=donate");
			
			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . $loc . '">')
			);

			$msg = sprintf($lang['Points_user_updated'], $board_config['points_name']) . '<br /><br />' . sprintf($lang['Click_return_pointscp'], '<a href="' . append_sid("pointscp.$phpEx") . '">', '</a>', $board_config['points_name']) . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $msg);
		}
	}

	$s_username = (!empty($user_id)) ? get_username_from_id($user_id) : '';
	$location = (!isset($HTTP_POST_VARS['location'])) ? $HTTP_SERVER_VARS['HTTP_REFERER'] : $location;
	$s_hidden_fields = '<input type="hidden" name="location" value="' . $location . '">';

	$template->assign_vars(array(
	 	'MOD_VERSION' => $board_config['points_system_version'],
		'L_FIND_USERNAME' => $lang['Find_username'],
		'L_ADD' => $lang['Add'],
		'L_SUBTRACT' => $lang['Subtract'],
		'L_POINTS_TITLE' => sprintf($lang['Points_cp'], $board_config['points_name']),
		'L_AMOUNT' => $lang['Points_amount'],
		'L_AMOUNT_GIVE_TAKE' => sprintf($lang['Points_give_take'], $board_config['points_name']),
		'L_METHOD' => $lang['Points_method'],
		'L_ADD_SUBTRACT' => sprintf($lang['Points_add_subtract'], $board_config['points_name']),
		'L_MASS_EDIT' => $lang['Points_mass_edit'],
		'L_MASS_EDIT_EXPLAIN' => $lang['Points_mass_edit_explain'],
		'L_REASON' => $lang['Points_reason'],
		'L_REASON_DONATE' => $lang['Points_reason_donate'],
		'USERNAME' => $s_username,
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_POST_ACTION' => append_sid("pointscp.$phpEx", true),
		'U_SEARCH_USER' => append_sid("search.$phpEx?mode=searchuser"))
	);
	
	$template->assign_block_vars('switch_points_cp', array());
}

//
// Generate the page
//
$page_title	= sprintf($lang['Points_sys'], $board_config['points_name']);
include($phpbb_root_path . 'includes/page_header.'.$phpEx);


//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

if ($mode == 'donate')
{
	include($phpbb_root_path . 'profile_menu.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
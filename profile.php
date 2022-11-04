<?php
/** 
*
* @package phpBB2
* @version $Id: profile.php,v 1.193.2.5 2004/11/18 17:49:37 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/usercp_photo.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}

//
// Set default email variables
//
$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
$script_name = ( $script_name != '' ) ? $script_name . '/profile.'.$phpEx : 'profile.'.$phpEx;
$server_name = trim($board_config['server_name']);
$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

$server_url = $server_protocol . $server_name . $server_port . $script_name;

// -----------------------
// Page specific functions
//
function gen_rand_string($hash)
{
	$rand_str = dss_rand();

	return ( $hash ) ? md5($rand_str) : substr($rand_str, 0, 8);
}
//
// End page specific functions
// ---------------------------

$profilephoto_mod->execute_mod();

//
// Start of program proper
//
$u = ( isset($HTTP_GET_VARS[POST_USERS_URL]) ) ? intval($HTTP_GET_VARS[POST_USERS_URL]) : intval($HTTP_POST_VARS[POST_USERS_URL]);
$t = ( isset($HTTP_GET_VARS[POST_TOPIC_URL]) ) ? intval($HTTP_GET_VARS[POST_TOPIC_URL]) : intval($HTTP_POST_VARS[POST_TOPIC_URL]);
$f = ( isset($HTTP_GET_VARS[POST_FORUM_URL]) ) ? intval($HTTP_GET_VARS[POST_FORUM_URL]) : intval($HTTP_POST_VARS[POST_FORUM_URL]);

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_GET_VARS['mode']) ) ? htmlspecialchars($HTTP_GET_VARS['mode']) : htmlspecialchars($HTTP_POST_VARS['mode']);
	$ucp = ( isset($HTTP_GET_VARS['ucp']) ) ? htmlspecialchars($HTTP_GET_VARS['ucp']) : htmlspecialchars($HTTP_POST_VARS['ucp']);
	
	if ( $mode == 'viewprofile') 
	{		
		include($phpbb_root_path . 'includes/usercp_viewprofile.'.$phpEx);
		exit;
	}
  	else if ( $mode == 'switch_status' && $userdata['session_logged_in'] && $board_config['allow_invisible_link'] )
  	{
   	    $switch_status = "UPDATE ". USERS_TABLE ."
   			SET user_allow_viewonline = " . ( ( $userdata['user_allow_viewonline'] ) ? 0 : 1 ) . "
   			WHERE user_id = ". $userdata['user_id'];
   		if( !$db->sql_query($switch_status) )
   		{
   			message_die(GENERAL_ERROR, 'Could not update online status', '', __LINE__, __FILE__, $switch_status);
   		}  
    	redirect(append_sid("index.$phpEx", true));
  	}
	else if ( $mode == 'editprofile' || $mode == REGISTER_MODE )
	{
		if ( !$userdata['session_logged_in'] && $mode == 'editprofile' )
		{
			redirect(append_sid("login.$phpEx?redirect=profile.$phpEx&mode=editprofile&ucp=" . ucp, true));
		}

		if (!$userdata['user_allow_profile'])
		{
			message_die (GENERAL_MESSAGE, $lang['No_Access_Profile'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>'));
		}

		include($phpbb_root_path . 'includes/usercp_register.'.$phpEx);
		exit;
	}
	else if ( $mode == 'confirm')
	{
		// Visual Confirmation
	    if ( $userdata['session_logged_in'] || $user_level==ADMIN )
	    {
			include($phpbb_root_path . 'includes/usercp_confirm.'.$phpEx);
			exit;
		}
		elseif( $userdata['session_logged_in'])
		{
			exit;
		}
 
		include($phpbb_root_path . 'includes/usercp_confirm.'.$phpEx);
		exit;
	}
	else if ( $mode == 'confirm' )
	{
		// Visual Confirmation
		if ( $userdata['session_logged_in'] )
		{
			exit;
		}

		include($phpbb_root_path . 'includes/usercp_confirm.'.$phpEx);
		exit;
	}
	else if ( $mode == 'sendpassword' )
	{
		include($phpbb_root_path . 'includes/usercp_sendpasswd.'.$phpEx);
		exit;
	}
	else if ( $mode == 'activate' )
	{
		include($phpbb_root_path . 'includes/usercp_activate.'.$phpEx);
		exit;
	}
	else if ( $mode == 'resendactivation' )
	{
		include($phpbb_root_path . 'includes/usercp_sendactivation.'.$phpEx);
		exit;
	}
	else if ( $mode == 'email' )
	{
		include($phpbb_root_path . 'includes/usercp_email.'.$phpEx);
		exit;
	}
	else if ($mode == 'addbuddy' && $u != ANONYMOUS)
	{
		// Can not add self!
	  	if ($userdata['user_id'] == $u)
  		{
   			$message = $lang['UCP_BuddyList_Self'] . '<br /><br />';
  			
  			if ($t)
  			{
  			   	$message .= sprintf($lang['Click_return_topic'], '<a href="' . append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $t) . '">', '</a>') . '<br /><br />';
  			}
  			
    		$message .= sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');
			
  			message_die(GENERAL_MESSAGE, $message);
 		}
	
		// Add Buddy
		$sql = "DELETE FROM " . BUDDY_LIST_TABLE . " 
	    	WHERE user_id = " . $userdata['user_id'] . " 
	    		AND contact_id = " . $u;
	    if ( !$db->sql_query($sql) )
	    {
	      	message_die(GENERAL_ERROR, 'Could not remove buddy from buddylist.', '', __LINE__, __FILE__, $sql);
	    }
	
	  	$sql = "INSERT INTO " . BUDDY_LIST_TABLE . " (user_id, contact_id) 
  			VALUES (" . $userdata['user_id'] . ", " . $u . ")";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not add buddy to buddylist.', '', __LINE__, __FILE__, $sql);			
		}

  		$message = $lang['UCP_BuddyList_Add'] . '<br /><br />';
  			
  		if ($t)
  		{
  		   	$message .= sprintf($lang['Click_return_topic'], '<a href="' . append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $t) . '">', '</a>') . '<br /><br />';
  		}
  		
    	$message .= sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');
	
	    message_die(GENERAL_MESSAGE, $message);
	}
	else if ($mode == 'removebuddy' && $u != ANONYMOUS)
	{
		// Buddy Removal
		$sql = "DELETE FROM " . BUDDY_LIST_TABLE . " 
	    	WHERE user_id = " . $userdata['user_id'] . " 
	    		AND contact_id = " . $u;
	    if ( !$db->sql_query($sql) )
	    {
	      	message_die(GENERAL_ERROR, 'Could not remove buddy from buddylist.', '', __LINE__, __FILE__, $sql);
	    }
	
	    $message = $lang['UCP_BuddyList_Remove'] . '<br /><br />' . sprintf($lang['Click_return_usercp'], '<a href="' . append_sid('profile.'.$phpEx.'?mode=editprofile&ucp=main') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');
	
	    message_die(GENERAL_MESSAGE, $message);
	}
	else if ($mode == 'addsubscription' && $userdata['session_logged_in'] && ($f != ''))
	{
		if ($f)
		{
			$sql = "DELETE FROM " . SUBSCRIPTIONS_LIST_TABLE . " 
				WHERE user_id = " . $userdata['user_id'] . "
					AND forum_id = " . $f;
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not remove forum subscription.', '', __LINE__, __FILE__, $sql);			
			}
	 	
	 	 	$sql = "INSERT INTO " . SUBSCRIPTIONS_LIST_TABLE . " (user_id, forum_id) 
  				VALUES (" . $userdata['user_id'] . ", " . $f . ")";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not add forum subscription.', '', __LINE__, __FILE__, $sql);			
			}
			
			$message = $lang['UCP_Subsc_Add_Forum'] . '<br /><br />' . sprintf($lang['Click_return_forum'], '<a href="' . append_sid('viewforum.'.$phpEx.'?' . POST_FORUM_URL . '=' . $f) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

		    message_die(GENERAL_MESSAGE, $message);
		}	
	}
	else if ($mode == 'removesubscription' && $userdata['session_logged_in'] && ($f != ''))
	{
		if ($f)
		{
			$sql = "DELETE FROM " . SUBSCRIPTIONS_LIST_TABLE . " 
				WHERE user_id = " . $userdata['user_id'] . "
					AND forum_id = " . $f;
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not remove forum subscription.', '', __LINE__, __FILE__, $sql);			
			}
			
			$message = $lang['UCP_Subsc_Remove'] . '<br /><br />' . sprintf($lang['Click_return_usercp'], '<a href="' . append_sid('profile.'.$phpEx.'?mode=editprofile') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

		    message_die(GENERAL_MESSAGE, $message);
		}
	}
	else if ($mode == 'transition' && $userdata['session_logged_in'])
	{
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_transition = $t
			WHERE user_id = " . $userdata['user_id'];
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not change page transition setting.', '', __LINE__, __FILE__, $sql);			
		}
    	redirect(append_sid("index.$phpEx", true));
	}
}

redirect(append_sid("index.$phpEx", true));

?>
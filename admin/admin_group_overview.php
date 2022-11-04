<?php
/** 
*
* @package admin
* @version $Id: admin_group_overview.php,v 1.0.0 2004/10/16 14:25:11 Leuchte Exp $
* @copyright (c) 2004 Leuchte
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Groups']['Group_Overview'] = "$file";
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Set mode
//
if ( isset($HTTP_POST_VARS[POST_GROUPS_URL]) || isset($HTTP_GET_VARS[POST_GROUPS_URL]) )
{
	$group_id = ( isset($HTTP_POST_VARS[POST_GROUPS_URL]) ) ? intval($HTTP_POST_VARS[POST_GROUPS_URL]) : intval($HTTP_GET_VARS[POST_GROUPS_URL]);
}
else
{
	$group_id = 0;
}

if ( isset($HTTP_POST_VARS[POST_USERS_URL]) || isset($HTTP_GET_VARS[POST_USERS_URL]) )
{
	$user_id = ( isset($HTTP_POST_VARS[POST_USERS_URL]) ) ? intval($HTTP_POST_VARS[POST_USERS_URL]) : intval($HTTP_GET_VARS[POST_USERS_URL]);
}
else
{
	$user_id = 0;
}

if ( isset($HTTP_POST_VARS['move']) || isset($HTTP_GET_VARS['move']) )
{
	if( isset($HTTP_POST_VARS['move']) || isset($HTTP_GET_VARS['move']) )
	{
		$move = ( isset($HTTP_POST_VARS['move']) ) ? htmlspecialchars($HTTP_POST_VARS['move']) : htmlspecialchars($HTTP_GET_VARS['move']);
	}
	else
	{
	    message_die(GENERAL_ERROR, "No move mode selected");
	}
}

if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}


//
// Change group order
//
if ( !empty($move) )
{
	if ( $move == 'down' )
	{
		$a = '<';
		$b = 'ASC';
		$c = '+ 1';
		$d = '- 1';
	}
	else
	{
		$a = '>';
		$b = 'DESC';
		$c = '- 1';
		$d = '+ 1';
	}

	$sql = "SELECT g2.group_id, g1.group_rank_order
		FROM " . GROUPS_TABLE . " g1, " . GROUPS_TABLE . " g2
		WHERE g1.group_id = $group_id
			AND g1.group_rank_order $a g2.group_rank_order
		ORDER BY g2.group_rank_order $b
		LIMIT 1";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get group2 id", "", __LINE__, __FILE__, $sql);
	}

	if ( !list($group2_id, $group_rank_order) = $db->sql_fetchrow($result) )
	{
    	$msg = $lang['Group_rank_order_could_not_moved'];
    }
    else if ( isset($HTTP_GET_VARS['o']) && $HTTP_GET_VARS['o'] != $group_rank_order )
    {
    	$msg = $lang['Group_rank_order_alreay_moved'];
    }
    else
    {
    	$sql = "UPDATE " . GROUPS_TABLE . "
	    	SET group_rank_order = group_rank_order $c
	        WHERE group_id = $group_id";
		if( !$result = $db->sql_query($sql) )
	    {
	    	message_die(GENERAL_ERROR, "Couldn't change group order", "", __LINE__, __FILE__, $sql);
	    }

	    $sql = "UPDATE " . GROUPS_TABLE . "
	    	SET group_rank_order = group_rank_order $d
	        WHERE group_id = $group2_id";
		if( !$result = $db->sql_query($sql) )
	    {
	    	message_die(GENERAL_ERROR, "Couldn't change group2 order", "", __LINE__, __FILE__, $sql);
	    }
	    $msg = $lang['Group_rank_order_moved'];
	}
}

if ( !empty($msg) )
{
	$template->assign_block_vars('msg', array(
		'MSG' => $msg)
	);
}


//
// Delete User
//
if ($mode == 'delete')
{
	$sql = "SELECT g.*, aa.auth_mod
		FROM ". GROUPS_TABLE ." g, ". AUTH_ACCESS_TABLE ." aa
		WHERE g.group_id = $group_id
			AND aa.group_id = g.group_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
	}
	$group_info = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	if ( $group_info['auth_mod'] )
	{
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_level = 0 
			WHERE user_id = $user_id 
				AND user_level != 1";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
		}
	}
	
  	if ( $user_id != $group_info['group_moderator'] )
  	{
    	$sql = "DELETE FROM " . USER_GROUP_TABLE . " 
	  		WHERE user_id = $user_id  
		  		AND group_id = $group_id";
  		if ( !$db->sql_query($sql) )
  		{
		  	message_die(GENERAL_ERROR, 'Could not update user group table', '', __LINE__, __FILE__, $sql);
  		}
   
		$message = $lang['GO_remove_member'] . '<br /><br />' . sprintf($lang['Click_return_go'], '<a href="'. append_sid("admin_group_overview.$phpEx") .'">', '</a>') .'<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href=\"'. append_sid("index.$phpEx?pane=right") .'\">', '</a>');
		
		message_die(GENERAL_MESSAGE, $message);
  	}
  	else
  	{
    	$message = $lang['GO_remove_mod'] . '<br /><br />' . sprintf($lang['Click_return_go'], '<a href="' . append_sid("admin_group_overview.$phpEx") . '">', '</a>') .'<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href=\"'. append_sid("index.$phpEx?pane=right") .'\">', '</a>');
	
		message_die(GENERAL_MESSAGE, $message);
	}
}  

//
// Update Group
//
if ( isset($_POST['submit']) )
{
	if ( isset($HTTP_POST_VARS['group_delete_users']) ) 
  	{ 
		$sql = "SELECT g.group_moderator, aa.auth_mod
			FROM ". GROUPS_TABLE ." g, ". AUTH_ACCESS_TABLE ." aa
			WHERE g.group_id = $group_id
				AND aa.group_id = g.group_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
		}
		$auth = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		if ( $auth['auth_mod'] )
		{
			$sql = "SELECT ug.user_id
				FROM ". GROUPS_TABLE ." g, ". USER_GROUP_TABLE ." ug
				WHERE g.group_id = $group_id
					AND ug.group_id = g.group_id
					AND ug.user_id != g.group_moderator";
			if ( !($result = $db->sql_query($sql)) ) 
    		{ 
     			message_die(GENERAL_ERROR, 'Could not get group user', '', __LINE__, __FILE__, $sql); 
    		}
			
			$group_info = array();
  	  		while ( $row = $db->sql_fetchrow($result) )
			{
				$group_info[] = $row;
			}
			$db->sql_freeresult($result);

		 	for($i = 0; $i < sizeof($group_info); $i++)
			{
				$sql = "UPDATE " . USERS_TABLE . " 
					SET user_level = 0 
					WHERE user_id = ". $group_info[$i]['user_id'] ." 
						AND user_level != 1";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
				}
			}
		}
		      
    	$sql = "DELETE FROM ". USER_GROUP_TABLE ." 
      		WHERE group_id = $group_id 
      			AND user_id != ". $auth['group_moderator']; 
    	if ( !($result = $db->sql_query($sql)) ) 
    	{ 
    		message_die(GENERAL_ERROR, 'Could not delete group users', '', __LINE__, __FILE__, $sql); 
    	} 
      
     	$message = $lang['group_users_removed'] . '<br /><br />' . sprintf($lang['Click_return_go'], '<a href="' . append_sid("admin_group_overview.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>'); 

    	message_die(GENERAL_MESSAGE, $message); 
  	}

	if ( isset($HTTP_POST_VARS['group_delete']) )
	{
		//
		// Reset User Moderator Level
		//
		// Is Group moderating a forum ?
		$sql = "SELECT auth_mod 
			FROM " . AUTH_ACCESS_TABLE . " 
			WHERE group_id = " . $group_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not select auth_access', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (intval($row['auth_mod']) == 1)
		{
			// Yes, get the assigned users and update their Permission if they are no longer moderator of one of the forums
			$sql = "SELECT user_id 
				FROM " . USER_GROUP_TABLE . "
				WHERE group_id = " . $group_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select user_group', '', __LINE__, __FILE__, $sql);
			}

			$rows = $db->sql_fetchrowset($result);
			$db->sql_freeresult($result);

			for ($i = 0; $i < sizeof($rows); $i++)
			{
				$sql = "SELECT g.group_id 
					FROM " . AUTH_ACCESS_TABLE . " a, " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
					WHERE (a.auth_mod = 1) 
						AND (g.group_id = a.group_id) 
						AND (a.group_id = ug.group_id) 
						AND (g.group_id = ug.group_id) 
						AND (ug.user_id = " . intval($rows[$i]['user_id']) . ") 
						AND (ug.group_id <> " . $group_id . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain moderator permissions', '', __LINE__, __FILE__, $sql);
				}

				if ($db->sql_numrows($result) == 0)
				{
					$sql = "UPDATE " . USERS_TABLE . " 
						SET user_level = " . USER . " 
						WHERE user_level = " . MOD . " 
							AND user_id = " . intval($rows[$i]['user_id']);
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not update moderator permissions', '', __LINE__, __FILE__, $sql);
					}
				}
			}
		}

		//
		// Delete Group
		//
		$sql = "DELETE FROM " . GROUPS_TABLE . "
			WHERE group_id = " . $group_id;
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update group', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . USER_GROUP_TABLE . "
			WHERE group_id = " . $group_id;
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update user_group', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
			WHERE group_id = " . $group_id;
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update auth_access', '', __LINE__, __FILE__, $sql);
		}

		$message = $lang['Deleted_group'] . '<br /><br />' . sprintf($lang['Click_return_go'], '<a href="' . append_sid("admin_group_overview.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$group_type = isset($HTTP_POST_VARS['group_type']) ? intval($HTTP_POST_VARS['group_type']) : GROUP_OPEN;
		$group_name = isset($HTTP_POST_VARS['group_name']) ? trim($HTTP_POST_VARS['group_name']) : '';
		$group_description = isset($HTTP_POST_VARS['group_description']) ? trim($HTTP_POST_VARS['group_description']) : '';
		$group_moderator = isset($HTTP_POST_VARS['group_mod']) ? $HTTP_POST_VARS['group_mod'] : '';
		$delete_old_moderator = isset($HTTP_POST_VARS['delete_old_moderator']) ? true : false;
		$group_members_count = isset($HTTP_POST_VARS['group_members_count']) ? $HTTP_POST_VARS['group_members_count'] : 0;
		$group_validate = ( isset($HTTP_POST_VARS['group_validate']) ) ? intval($HTTP_POST_VARS['group_validate']) : 1;

		if ( $group_name == '' )
		{
			message_die(GENERAL_MESSAGE, $lang['No_group_name']);
		}
		else if ( $group_moderator == '' )
		{
			message_die(GENERAL_MESSAGE, $lang['No_group_moderator']);
		}
		
		$this_userdata = get_userdata($group_moderator, true);
		$group_moderator = $this_userdata['user_id'];

		if ( !$group_moderator )
		{
			message_die(GENERAL_MESSAGE, $lang['No_group_moderator']);
		}
				
		$sql = "SELECT *
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user <> " . TRUE . "
				AND group_id = " . $group_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
		}

		if( !($group_info = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_MESSAGE, $lang['Group_not_exist']);
		}
		
		if ( $group_info['group_moderator'] != $group_moderator )
		{
			if ( $delete_old_moderator )
			{
				$sql = "DELETE FROM " . USER_GROUP_TABLE . "
					WHERE user_id = " . $group_info['group_moderator'] . " 
						AND group_id = " . $group_id;
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update group moderator', '', __LINE__, __FILE__, $sql);
				}
			}

			$sql = "SELECT user_id 
				FROM " . USER_GROUP_TABLE . " 
				WHERE user_id = $group_moderator 
					AND group_id = $group_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Failed to obtain current group moderator info', '', __LINE__, __FILE__, $sql);
			}

			if ( !($row = $db->sql_fetchrow($result)) )
			{
				$sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
					VALUES (" . $group_id . ", " . $group_moderator . ", 0)";
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update group moderator', '', __LINE__, __FILE__, $sql);
				}
			}
		}

		$sql = "UPDATE " . GROUPS_TABLE . "
			SET group_type = $group_type, group_name = '" . str_replace("\'", "''", $group_name) . "', group_description = '" . str_replace("\'", "''", $group_description) . "', group_moderator = $group_moderator, group_members_count = $group_members_count, group_validate = $group_validate
			WHERE group_id = $group_id";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update group', '', __LINE__, __FILE__, $sql);
		}
	
		$message = $lang['Updated_group'] . '<br /><br />' . sprintf($lang['Click_return_go'], '<a href="' . append_sid("admin_group_overview.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');;

		message_die(GENERAL_MESSAGE, $message);
	}
}

//
// Add User
//
if ( isset($_POST['add']) )
{
	$username = ( isset($HTTP_POST_VARS['username']) ) ? htmlspecialchars($HTTP_POST_VARS['username']) : '';
				
	$sql = "SELECT g.group_moderator, g.group_type, aa.auth_mod 
		FROM ( " . GROUPS_TABLE . " g 
			LEFT JOIN " . AUTH_ACCESS_TABLE . " aa ON aa.group_id = g.group_id )
		WHERE g.group_id = $group_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get moderator information', '', __LINE__, __FILE__, $sql);
	}

	if ( $group_info = $db->sql_fetchrow($result) )
	{
		$group_moderator = $group_info['group_moderator'];
	
		if ( $group_moderator == $userdata['user_id'] || $userdata['user_level'] == ADMIN )
		{
			$is_moderator = TRUE;
		}
				
		$sql = "SELECT user_id, user_email, user_lang, user_level  
			FROM " . USERS_TABLE . " 
			WHERE username = '" . str_replace("\'", "''", $username) . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not get user information", $lang['Error'], __LINE__, __FILE__, $sql);
		}

		if ( !($row = $db->sql_fetchrow($result)) )
		{
			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("admin_group_overview.$phpEx") . '">')
			);

			$message = $lang['Could_not_add_user'] . "<br /><br />" . sprintf($lang['Click_return_go'], "<a href=\"" . append_sid("admin_group_overview.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}

		if ( $row['user_id'] == ANONYMOUS )
		{
			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("admin_group_overview.$phpEx") . '">')
			);

			$message = $lang['Could_not_anon_user'] . '<br /><br />' . sprintf($lang['Click_return_go'], '<a href="' . append_sid("admin_group_overview.$phpEx?" . POST_GROUPS_URL . "=$group_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
				
		$sql = "SELECT ug.user_id, u.user_level 
			FROM " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u 
			WHERE u.user_id = " . $row['user_id'] . " 
				AND ug.user_id = u.user_id 
				AND ug.group_id = $group_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not get user information', '', __LINE__, __FILE__, $sql);
		}

		if ( !($db->sql_fetchrow($result)) )
		{
			$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending) 
				VALUES (" . $row['user_id'] . ", $group_id, 0)";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not add user to group', '', __LINE__, __FILE__, $sql);
			}
					
			if ( $row['user_level'] != ADMIN && $row['user_level'] != MOD && $group_info['auth_mod'] )
			{
				$sql = "UPDATE " . USERS_TABLE . " 
					SET user_level = " . MOD . " 
					WHERE user_id = " . $row['user_id'];
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
				}
			}

			//
			// Get the group name
			// Email the user and tell them they're in the group
			//
			$group_sql = "SELECT group_name 
				FROM " . GROUPS_TABLE . " 
				WHERE group_id = $group_id";
			if ( !($result = $db->sql_query($group_sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not get group information', '', __LINE__, __FILE__, $group_sql);
			}

			$group_name_row = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$group_name = $group_name_row['group_name'];
			
			$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
			$script_name = ( $script_name != '' ) ? $script_name . '/groupcp.'.$phpEx : 'groupcp.'.$phpEx;
			$server_name = trim($board_config['server_name']);
			$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
			$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';
			$server_url = $server_protocol . $server_name . $server_port . $script_name;
					
			include($phpbb_root_path . 'includes/emailer.'.$phpEx);
			$emailer = new emailer($board_config['smtp_delivery']);

			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);

			$emailer->use_template('group_added', $row['user_lang']);
			$emailer->email_address($row['user_email']);
			$emailer->set_subject($lang['Group_added']);

			$emailer->assign_vars(array(
				'SITENAME' => $board_config['sitename'], 
				'GROUP_NAME' => $group_name,
				'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '', 

				'U_GROUPCP' => $server_url . '?' . POST_GROUPS_URL . "=$group_id")
			);
			
			$emailer->send();
			$emailer->reset();
					
			$message = $lang['GO_member_added'] . '<br /><br />' . sprintf($lang['Click_return_go'], '<a href="' . append_sid("admin_group_overview.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("admin_group_overview.$phpEx") . '">')
			);

			$message = $lang['User_is_member_group'] . '<br /><br />' . sprintf($lang['Click_return_go'], '<a href="' . append_sid("admin_group_overview.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
}


//
// Default
//
$sql = "SELECT group_id
	FROM ". GROUPS_TABLE ."
	WHERE group_single_user = 0
	ORDER BY group_rank_order, group_id DESC";
if(!$q_groups = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not query group information", "", __LINE__, __FILE__, $sql);
}

if( $total_groups = $db->sql_numrows($q_groups) )
{
	$group_rows = $db->sql_fetchrowset($q_groups);
}
$db->sql_freeresult($result);
  
for($i = 0; $i < $total_groups; $i++)
{
	$sql = "SELECT g.*, u.username, u.user_id, u.user_level
    	FROM ". GROUPS_TABLE ." g, ". USERS_TABLE ." u
    	WHERE g.group_id = ". $group_rows[$i]['group_id'] ."
    		AND g.group_moderator = u.user_id";
	if ( !($result = $db->sql_query($sql)) )
    {
    	message_die(GENERAL_ERROR, 'Could not query group information', '', __LINE__, __FILE__, $sql);
    }
    $groups = $db->sql_fetchrow($result);
   	$db->sql_freeresult($result);
              
	$mod_url = append_sid("admin_users.$phpEx?mode=edit&amp;". POST_USERS_URL . "=" . $groups['user_id']);
     
    $sql = "SELECT COUNT(user_id) AS users
    	FROM ". USER_GROUP_TABLE ."
      	WHERE group_id = ". $group_rows[$i]['group_id'];
	if ( !($result = $db->sql_query($sql)) )
    {
    	message_die(GENERAL_ERROR, 'Could not query group information', '', __LINE__, __FILE__, $sql);
    }
    $users = $db->sql_fetchrow($result);
   	$db->sql_freeresult($result);
  
	$status = ($groups['group_type'] != GROUP_PAYMENT) ? ( ( $groups['group_type'] != GROUP_OPEN ) ? ( $groups['group_type'] != GROUP_CLOSED ? ( $lang['group_hidden'] ) : $lang['group_closed'] ) : $lang['group_open']) : $lang['group_payment'];
    $group_open = ( $groups['group_type'] == GROUP_OPEN ) ? ' checked="checked"' : '';
    $group_closed = ( $groups['group_type'] == GROUP_CLOSED ) ? ' checked="checked"' : '';
    $group_hidden = ( $groups['group_type'] == GROUP_HIDDEN ) ? ' checked="checked"' : '';
   	$group_payment = ( $groups['group_type'] == GROUP_PAYMENT ) ? ' checked="checked"' : '';
   
    $sql = "SELECT u.username, u.user_id, u.user_level
    	FROM ". USER_GROUP_TABLE ." ug, ".USERS_TABLE ." u
		WHERE ug.group_id = ". $group_rows[$i]['group_id'] ."
			AND u.user_id = ug.user_id
		GROUP BY u.user_id
      	ORDER BY u.username";
	if ( !($result = $db->sql_query($sql)) )
    {
    	message_die(GENERAL_ERROR, 'Could not query group information', '', __LINE__, __FILE__, $sql);
    }
    
    $members = array();  
    while ( $row = $db->sql_fetchrow($result) )
    {
    	$members[] = $row;
    }
   	$db->sql_freeresult($result);
  
	$memberlist = '';
    for($j = 0; $j < sizeof($members); $j++)
	{
    	if ( $members[$j]['user_id'] == $groups['group_moderator'] )
        {
         	$userlink = username_level_color($members[$j]['username'], $members[$j]['user_level'], $members[$j]['user_id']);
        }
        else
        {
        	$userlink =  '<a href="'. append_sid('admin_group_overview.'.$phpEx.'?mode=delete&amp;' . POST_USERS_URL . '=' . $members[$j]['user_id'] . '&amp;' . POST_GROUPS_URL . '=' . $group_rows[$i]['group_id']) . '">' . username_level_color($members[$j]['username'], $members[$j]['user_level'], $members[$j]['user_id']) . '</a>';
        }
        
    	$memberlist .= ( ( $memberlist != '' ) ? ', ' : '' ) . $userlink;
    }

	$s_hidden_fields = '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $groups['group_id'] . '" />';
 
    $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
     
    $template->assign_block_vars('groups',array(
    	'ROW_CLASS' => $row_class,
        'GROUP' => str_replace('"', '&quot;', $groups['group_name']),
        'GROUP_DESCRIPTION' => str_replace('"', '&quot;', $groups['group_description']),
        'MOD' => username_level_color($groups['username'], $groups['user_level'], $groups['user_id']),
        'MOD2' => $groups['username'],
        'GROUP_MOD_ID' => $groups['user_id'],
        'U_MOD' => $mod_url,
        'USERS' => $users['users'],
        'STATUS' => $status,
      
        'GROUP_ID' => $groups['group_id'],
	    'S_GROUP_OPEN_TYPE' => GROUP_OPEN,
        'S_GROUP_CLOSED_TYPE' => GROUP_CLOSED,
        'S_GROUP_HIDDEN_TYPE' => GROUP_HIDDEN,
		'S_GROUP_PAYMENT_TYPE' => GROUP_PAYMENT,
        'S_GROUP_OPEN_CHECKED' => $group_open,
        'S_GROUP_CLOSED_CHECKED' => $group_closed,
        'S_GROUP_HIDDEN_CHECKED' => $group_hidden,
		'S_GROUP_PAYMENT_CHECKED' => $group_payment,
		'GROUP_MEMBERS' => $memberlist,
		'GROUP_MEMBERS_COUNT_YES' => ($groups['group_members_count']) ? 'checked="checked"' : '',
		'GROUP_MEMBERS_COUNT_NO' => (!$groups['group_members_count']) ? 'checked="checked"' : '',
		'GROUP_VALIDATE_YES' => ($groups['group_validate']) ? ' checked="checked"' : '',
		'GROUP_VALIDATE_NO' => (!$groups['group_validate']) ? ' checked="checked"' : '', 

		'L_MOVE_UP' => '<img src="' . $phpbb_root_path . $images['acp_up'] . '" alt="' . $lang['Move_up'] . '" title="' . $lang['Move_up'] . '">',
		'L_MOVE_DOWN' => '<img src="' . $phpbb_root_path . $images['acp_down'] . '" alt="' . $lang['Move_down'] . '" title="' . $lang['Move_down'] . '">',

		'U_MOVE_UP' => append_sid("admin_group_overview.$phpEx?" . POST_GROUPS_URL . "=" . $groups['group_id'] . "&amp;move=up&amp;o=" . $groups['group_rank_order']),
		'U_MOVE_DOWN' => append_sid("admin_group_overview.$phpEx?" . POST_GROUPS_URL . "=" . $groups['group_id'] . "&amp;move=down&amp;o=" . $groups['group_rank_order']),
		
		'U_PERMISSION' => append_sid("admin_ug_auth.$phpEx?mode=group&amp;g=".$groups['group_id']),
		'U_INFORM' => append_sid($phpbb_root_path."groupcp.$phpEx?g=".$groups['group_id']),
        'S_GROUP_ACTION' => append_sid("admin_group_overview.$phpEx"),
        'S_GROUP_INDEX' => $i,
        'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);	
}

$template->assign_vars(array(
	'L_GO_TITLE' => $lang['Group_Overview'],
	'L_GO_TEXT' => $lang['Group_admin_explain'],
	
    'L_GO_GROUP' => $lang['Groupcp'],
    'L_GO_MOD' => $lang['Moderator'],
    'L_GO_USER' => $lang['Users'],
    'L_GO_STATUS' => $lang['group_status'],
    
    'L_GO_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '">',
	'L_PERMISSION' => $lang['Auth_Control_Group'],
	'L_INFORM' => $lang['GO_inform'],
    'L_GROUP_NAME' => $lang['Group_name'],
    'L_GROUP_DESCRIPTION' => $lang['Group_description'],
    'L_GROUP_MODERATOR' => $lang['group_moderator'], 
    'L_GROUP_STATUS' => $lang['group_status'],
    'L_GROUP_OPEN' => $lang['group_open'],
    'L_GROUP_CLOSED' => $lang['group_closed'],
    'L_GROUP_HIDDEN' => $lang['group_hidden'],
  	'L_GROUP_PAYMENT' => $lang['group_payment'],
  	'L_GROUP_DELETE' => $lang['group_delete'],
	'L_GROUP_DELETE_CHECK' => $lang['group_delete_check'],
	'L_GROUP_DELETE_USERS' => $lang['group_delete_users'],
	'L_GROUP_DELETE_USERS_CHECK' => $lang['group_delete_users_check'],
	'L_GROUP_DELETE_USERS_EXPLAIN' => $lang['group_delete_users_explain'],
 	'L_DELETE_MODERATOR' => $lang['delete_group_moderator'],
	'L_DELETE_MODERATOR_EXPLAIN' => $lang['delete_moderator_explain'],
    'L_MEMBERS' => $lang['GO_member'],
    'L_MEMBERS_EXPLAIN' => $lang['GO_member_explain'],
	'L_ADD_MEMBER' => $lang['GO_add_member'],
    'L_ADD_NEW' => $lang['Add_new'],
	'L_NEW_GROUP' => $lang['New_group'],
  	'L_GROUP_MEMBERS_COUNT' => $lang['group_members_count'],
	'L_GROUP_VALIDATE' => $lang['admin_group_validate'],
		
	'S_NEW_GROUP_FORM' => append_sid("admin_groups.$phpEx?mode=newgroup"))
);

$template->set_filenames(array(
		'body' => 'admin/group_overview_body.tpl')
);
  
$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
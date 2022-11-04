<?php
/** 
*
* @package admin
* @version $Id: admin_ug_auth.php,v 1.13.2.9 2005/07/19 20:01:05 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Users']['Permissions'] = $filename . "?mode=user";
	$module['UserGroups']['Permissions'] = $filename . "?mode=group";

	return;
}

//
// Load default header
//
$no_page_header = TRUE;

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$params = array('mode' => 'mode', 'user_id' => POST_USERS_URL, 'group_id' => POST_GROUPS_URL, 'adv' => 'adv');

while( list($var, $param) = @each($params) )
{
	if ( !empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]) )
	{
		$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? $HTTP_POST_VARS[$param] : $HTTP_GET_VARS[$param];
	}
	else
	{
		$$var = "";
	}
}

$user_id = intval($user_id);
$group_id = intval($group_id);
$adv = intval($adv); 
$mode = htmlspecialchars($mode); 
$action = htmlspecialchars($action); 

//
// Quick Administrator Edit return to forum
//
if( isset( $HTTP_POST_VARS['returntoprofile'] ) || isset( $HTTP_GET_VARS['returntoprofile'] ) )
{
	$return_to_profile = ( isset( $HTTP_POST_VARS['returntoprofile'] ) ) ? $HTTP_POST_VARS['returntoprofile'] : $HTTP_GET_VARS['returntoprofile'];
	$return_to_profile = intval($return_to_profile);
}

else
{
	$return_to_profile = 0;
}


//
// Start program - define vars
//
$forum_auth_fields = array('auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_globalannounce', 'auth_vote', 'auth_pollcreate', 'auth_suggest_event', 'auth_ban', 'auth_voteban', 'auth_greencard', 'auth_bluecard');

$auth_field_match = array(
	'auth_view' => AUTH_VIEW,
	'auth_read' => AUTH_READ,
	'auth_post' => AUTH_POST,
	'auth_reply' => AUTH_REPLY,
	'auth_edit' => AUTH_EDIT,
	'auth_delete' => AUTH_DELETE,
	'auth_sticky' => AUTH_STICKY,
	'auth_announce' => AUTH_ANNOUNCE, 
	'auth_globalannounce' => AUTH_GLOBALANNOUNCE,
	'auth_vote' => AUTH_VOTE, 
	'auth_pollcreate' => AUTH_POLLCREATE,
	'auth_suggest_event' => AUTH_SUGGEST_EVENT,
	'auth_ban' => AUTH_BAN, 
	'auth_voteban' => AUTH_VOTEBAN, 
	'auth_greencard' => AUTH_GREENCARD, 
	'auth_bluecard' => AUTH_BLUECARD);

$field_names = array(
	'auth_view' => $lang['View'],
	'auth_read' => $lang['Read'],
	'auth_post' => $lang['Post'],
	'auth_reply' => $lang['Reply'],
	'auth_edit' => $lang['Edit'],
	'auth_delete' => $lang['Delete'],
	'auth_sticky' => $lang['Sticky'],
	'auth_announce' => $lang['Announce'], 
	'auth_globalannounce' => $lang['Globalannounce'],
	'auth_vote' => $lang['Vote'], 
	'auth_pollcreate' => $lang['Pollcreate'],
	'auth_suggest_event' => $lang['Suggestevent'],
	'auth_ban' => $lang['ban_card'], 
	'auth_voteban' => $lang['voteban_card'], 
	'auth_greencard' => $lang['Greencard'], 
	'auth_bluecard' => $lang['Bluecard']);

attach_setup_usergroup_auth($forum_auth_fields, $auth_field_match, $field_names);

// ---------------
// Start Functions
//
function check_auth($type, $key, $u_access, $is_admin)
{
	$auth_user = 0;

	if( count($u_access) )
	{
		for($j = 0; $j < count($u_access); $j++)
		{
			$result = 0;
			switch($type)
			{
				case AUTH_ACL:
					$result = $u_access[$j][$key];

				case AUTH_MOD:
					$result = $result || $u_access[$j]['auth_mod'];

				case AUTH_ADMIN:
					$result = $result || $is_admin;
					break;
			}

			$auth_user = $auth_user || $result;
		}
	}
	else
	{
		$auth_user = $is_admin;
	}

	return $auth_user;
}
//
// End Functions
// -------------

if ( isset($HTTP_POST_VARS['submit']) && ( ( $mode == 'user' && $user_id ) || ( $mode == 'group' && $group_id ) ) )
{
	$user_level = '';
	if ( $mode == 'user' )
	{
		//
		// Get group_id for this user_id
		//
		$sql = "SELECT g.group_id, u.user_level
			FROM " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u, " . GROUPS_TABLE . " g
			WHERE u.user_id = $user_id 
				AND ug.user_id = u.user_id 
				AND g.group_id = ug.group_id 
				AND g.group_single_user = " . TRUE;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not select info from user/user_group table', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		$group_id = $row['group_id'];
		$user_level = $row['user_level'];

		$db->sql_freeresult($result);
	}

	//
	// Carry out requests
	//
	if ( $mode == 'user' && $HTTP_POST_VARS['userlevel'] == 'admin' && $user_level != ADMIN )
	{
		//
		// Make user an admin (if already user)
		//
		if ( $userdata['user_id'] != $user_id )
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_level = " . ADMIN . "
				WHERE user_id = $user_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
				WHERE group_id = $group_id 
					AND auth_mod = 0";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete auth access info", "", __LINE__, __FILE__, $sql);
			}

			//
			// Delete any entries in auth_access, they are not required if user is becoming an
			// admin
			//
			$sql = "UPDATE " . AUTH_ACCESS_TABLE . "
				SET auth_view = 0, auth_read = 0, auth_post = 0, auth_reply = 0, auth_edit = 0, auth_delete = 0, auth_sticky = 0, auth_announce = 0, auth_globalannounce = 0
				WHERE group_id = $group_id"; 
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't update auth access", "", __LINE__, __FILE__, $sql);
			}
		}

		$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userauth'], '<a href="' . append_sid("admin_ug_auth.$phpEx?mode=$mode") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		
		message_die(GENERAL_MESSAGE, $message);
	}
	else if ( $mode == 'user' && $HTTP_POST_VARS['userlevel'] == 'supermod' && $user_level != LESS_ADMIN ) 
	{ 
		// 
	   	// Make user a supermod (if already user) 
	   	// 
	   	if ( $userdata['user_id'] != $user_id ) 
	   	{ 
	    	// 
	    	// Update users level, reset 
	      	// 
	      	$sql = "UPDATE " . USERS_TABLE . " 
	        	SET user_level = " . LESS_ADMIN . " 
	        	WHERE user_id = $user_id"; 
			if(!$result = $db->sql_query($sql)) 
	      	{ 
	            message_die(GENERAL_ERROR, "Couldn't update user level", "", __LINE__, __FILE__, $sql); 
	      	} 
	
	      	$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . " 
	        	WHERE group_id = $group_id 
	            	AND auth_mod = 0"; 
	      	if ( !($result = $db->sql_query($sql)) ) 
	      	{ 
	        	message_die(GENERAL_ERROR, "Couldn't delete auth access info", "", __LINE__, __FILE__, $sql); 
	      	} 

	      	// 
	      	// Delete any entries in auth_access, they are not required if user is becoming an 
	      	// admin 
	      	// 
	      	$sql = "UPDATE " . AUTH_ACCESS_TABLE . " 
	        	SET auth_view = 0, auth_read = 0, auth_post = 0, auth_reply = 0, auth_edit = 0, auth_delete = 0, auth_sticky = 0, auth_announce = 0, auth_globalannounce = 0
	         	WHERE group_id = $group_id"; 
	      	if ( !($result = $db->sql_query($sql)) ) 
	      	{ 
	        	message_die(GENERAL_ERROR, "Couldn't update auth access", "", __LINE__, __FILE__, $sql); 
	      	} 
	   	} 
	
	   	$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userauth'], '<a href="' . append_sid("admin_ug_auth.$phpEx?mode=$mode") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>'); 
	   	message_die(GENERAL_MESSAGE, $message); 
	} 
	else
	{
		if ( $mode == 'user' && $HTTP_POST_VARS['userlevel'] == 'user' && $user_level == ADMIN )
		{
			//
			// Make admin a user (if already admin) ... ignore if you're trying
			// to change yourself from an admin to user!
			//
			if ( $user_id == 2 )
			{
			   message_die(GENERAL_MESSAGE, $lang['Main_Admin_Unchanged_Level']);
			}
			else if ( $userdata['user_id'] != $user_id )
			{
				$sql = "UPDATE " . AUTH_ACCESS_TABLE . "
					SET auth_view = 0, auth_read = 0, auth_post = 0, auth_reply = 0, auth_edit = 0, auth_delete = 0, auth_sticky = 0, auth_announce = 0, auth_suggest_event = 0, auth_ban = 0, auth_voteban=0, auth_greencard = 0, auth_bluecard = 0
					WHERE group_id = $group_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not update auth access', '', __LINE__, __FILE__, $sql);
				}

				//
				// Update users level, reset to USER
				//
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_level = " . USER . "
					WHERE user_id = $user_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
				}
			}

			$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userauth'], '<a href="' . append_sid("admin_ug_auth.$phpEx?mode=$mode") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		}
		else if ( $mode == 'user' && $HTTP_POST_VARS['userlevel'] == 'user' && $user_level == LESS_ADMIN ) 
		{ 
		   // 
		   // Make super mod a user (if already super mod) ... ignore if you're trying 
		   // to change yourself from an super mod to user! 
		   // 
		   if ( $userdata['user_id'] != $user_id ) 
		   { 
		      $sql = "UPDATE " . AUTH_ACCESS_TABLE . " 
		         SET auth_view = 0, auth_read = 0, auth_post = 0, auth_reply = 0, auth_edit = 0, auth_delete = 0, auth_sticky = 0, auth_announce = 0 
		         WHERE group_id = $group_id"; 
		      if ( !($result = $db->sql_query($sql)) ) 
		      { 
		         message_die(GENERAL_ERROR, 'Could not update auth access', '', __LINE__, __FILE__, $sql); 
		      } 
		             
		      // 
		      // Update users level, reset to USER 
		      // 
		      $sql = "UPDATE " . USERS_TABLE . " 
		         SET user_level = " . USER . " 
		         WHERE user_id = $user_id"; 
		      if ( !($result = $db->sql_query($sql)) ) 
		      { 
		         message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql); 
		      } 
		   } 
		
		   $message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_userauth'], '<a href="' . append_sid("admin_ug_auth.$phpEx?mode=$mode") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>'); 
		} 
		else
		{
			$change_mod_list = ( isset($HTTP_POST_VARS['moderator']) ) ? $HTTP_POST_VARS['moderator'] : array();

			if ( empty($adv) )
			{
				$sql = "SELECT f.* 
					FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
					WHERE f.cat_id = c.cat_id
					ORDER BY c.cat_order, f.forum_order ASC";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldnt obtain forum information', '', __LINE__, __FILE__, $sql);
				}

				$forum_access = $forum_auth_level_fields = array();
				while( $row = $db->sql_fetchrow($result) )
				{
					$forum_access[] = $row;
				}
				$db->sql_freeresult($result);

				for($i = 0; $i < sizeof($forum_access); $i++)
				{
					$forum_id = $forum_access[$i]['forum_id'];

					for($j = 0; $j < sizeof($forum_auth_fields); $j++)
					{
						$forum_auth_level_fields[$forum_id][$forum_auth_fields[$j]] = $forum_access[$i][$forum_auth_fields[$j]] == AUTH_ACL;
					}
				}

				while( list($forum_id, $value) = @each($HTTP_POST_VARS['private']) )
				{
					while( list($auth_field, $exists) = @each($forum_auth_level_fields[$forum_id]) )
					{
						if ($exists)
						{
							$change_acl_list[$forum_id][$auth_field] = $value;
						}
					}
				}
			}
			else
			{
				$change_acl_list = array();
				for($j = 0; $j < sizeof($forum_auth_fields); $j++)
				{
					$auth_field = $forum_auth_fields[$j];

					while( list($forum_id, $value) = @each($HTTP_POST_VARS['private_' . $auth_field]) )
					{
						$change_acl_list[$forum_id][$auth_field] = $value;
					}
				}
			}

			$sql = 'SELECT f.* 
				FROM ' . FORUMS_TABLE . ' f, ' . CATEGORIES_TABLE . ' c
				WHERE f.cat_id = c.cat_id
				ORDER BY c.cat_order, f.forum_order';
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't obtain forum information", "", __LINE__, __FILE__, $sql);
			}

			$forum_access = array();
			while( $row = $db->sql_fetchrow($result) )
			{
				$forum_access[] = $row;
			}
			$db->sql_freeresult($result);

			$sql = ( $mode == 'user' ) ? "SELECT aa.* FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE. " g WHERE ug.user_id = $user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND g.group_single_user = " . TRUE : "SELECT * FROM " . AUTH_ACCESS_TABLE . " WHERE group_id = $group_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
			}

			$auth_access = array();
			while( $row = $db->sql_fetchrow($result) )
			{
				$auth_access[$row['forum_id']] = $row;
			}
			$db->sql_freeresult($result);

			$forum_auth_action = array();
			$update_acl_status = array();
			$update_mod_status = array();

			for($i = 0; $i < count($forum_access); $i++)
			{
				$forum_id = $forum_access[$i]['forum_id'];

				if ( ( isset($auth_access[$forum_id]['auth_mod']) && $change_mod_list[$forum_id] != $auth_access[$forum_id]['auth_mod'] ) || ( !isset($auth_access[$forum_id]['auth_mod']) && $change_mod_list[$forum_id] ) )
				{
					$update_mod_status[$forum_id] = $change_mod_list[$forum_id];

					if ( !$update_mod_status[$forum_id] )
					{
						$forum_auth_action[$forum_id] = 'delete';
					}
					else if ( !isset($auth_access[$forum_id]['auth_mod']) )
					{
						$forum_auth_action[$forum_id] = 'insert';
					}
					else
					{
						$forum_auth_action[$forum_id] = 'update';
					}
				}

				for($j = 0; $j < count($forum_auth_fields); $j++)
				{
					$auth_field = $forum_auth_fields[$j];

					if( $forum_access[$i][$auth_field] == AUTH_ACL && isset($change_acl_list[$forum_id][$auth_field]) )
					{
						if ( ( empty($auth_access[$forum_id]['auth_mod']) && ( isset($auth_access[$forum_id][$auth_field]) && $change_acl_list[$forum_id][$auth_field] != $auth_access[$forum_id][$auth_field] ) || ( !isset($auth_access[$forum_id][$auth_field]) && !empty($change_acl_list[$forum_id][$auth_field]) ) ) || !empty($update_mod_status[$forum_id]) )
						{
							$update_acl_status[$forum_id][$auth_field] = ( !empty($update_mod_status[$forum_id]) ) ? 0 :  $change_acl_list[$forum_id][$auth_field];

							if ( isset($auth_access[$forum_id][$auth_field]) && empty($update_acl_status[$forum_id][$auth_field]) && $forum_auth_action[$forum_id] != 'insert' && $forum_auth_action[$forum_id] != 'update' )
							{
								$forum_auth_action[$forum_id] = 'delete';
							}
							else if ( !isset($auth_access[$forum_id][$auth_field]) && !( $forum_auth_action[$forum_id] == 'delete' && empty($update_acl_status[$forum_id][$auth_field]) ) )
							{
								$forum_auth_action[$forum_id] = 'insert';
							}
							else if ( isset($auth_access[$forum_id][$auth_field]) && !empty($update_acl_status[$forum_id][$auth_field]) ) 
							{
								$forum_auth_action[$forum_id] = 'update';
							}
						}
						else if ( ( empty($auth_access[$forum_id]['auth_mod']) && ( isset($auth_access[$forum_id][$auth_field]) && $change_acl_list[$forum_id][$auth_field] == $auth_access[$forum_id][$auth_field] ) ) && $forum_auth_action[$forum_id] == 'delete' )
						{
							$forum_auth_action[$forum_id] = 'update';
						}
					}
				}
			}

			//
			// Checks complete, make updates to DB
			//
			$delete_sql = '';
			while( list($forum_id, $action) = @each($forum_auth_action) )
			{
				if ( $action == 'delete' )
				{
					$delete_sql .= ( ( $delete_sql != '' ) ? ', ' : '' ) . $forum_id;
				}
				else
				{
					if ( $action == 'insert' )
					{
						$sql_field = '';
						$sql_value = '';
						while ( list($auth_type, $value) = @each($update_acl_status[$forum_id]) )
						{
							$sql_field .= ( ( $sql_field != '' ) ? ', ' : '' ) . $auth_type;
							$sql_value .= ( ( $sql_value != '' ) ? ', ' : '' ) . $value;
						}
						$sql_field .= ( ( $sql_field != '' ) ? ', ' : '' ) . 'auth_mod';
						$sql_value .= ( ( $sql_value != '' ) ? ', ' : '' ) . ( ( !isset($update_mod_status[$forum_id]) ) ? 0 : $update_mod_status[$forum_id]);

						$sql = "INSERT INTO " . AUTH_ACCESS_TABLE . " (forum_id, group_id, $sql_field) 
							VALUES ($forum_id, $group_id, $sql_value)";
					}
					else
					{
						$sql_values = '';
						while ( list($auth_type, $value) = @each($update_acl_status[$forum_id]) )
						{
							$sql_values .= ( ( $sql_values != '' ) ? ', ' : '' ) . $auth_type . ' = ' . $value;
						}
						$sql_values .= ( ( $sql_values != '' ) ? ', ' : '' ) . 'auth_mod = ' . ( ( !isset($update_mod_status[$forum_id]) ) ? 0 : $update_mod_status[$forum_id]);

						$sql = "UPDATE " . AUTH_ACCESS_TABLE . " 
							SET $sql_values 
							WHERE group_id = $group_id 
								AND forum_id = $forum_id";
					}
					if( !($result = $db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, "Couldn't update private forum permissions", "", __LINE__, __FILE__, $sql);
					}
				}
			}

			if ( $delete_sql != '' )
			{
				$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . " 
					WHERE group_id = $group_id 
						AND forum_id IN ($delete_sql)";
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Couldn't delete permission entries", "", __LINE__, __FILE__, $sql);
				}
			}

			$l_auth_return = ( $mode == 'user' ) ? $lang['Click_return_userauth'] : $lang['Click_return_groupauth'];
			$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($l_auth_return, '<a href="' . append_sid("admin_ug_auth.$phpEx?mode=$mode") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		}

		//
		// Update user level to mod for appropriate users
		// 
		$sql = "SELECT u.user_id 
			FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u  
			WHERE ug.group_id = aa.group_id 
				AND u.user_id = ug.user_id 
				AND ug.user_pending = 0
				AND u.user_level NOT IN (" . MOD . ", " . LESS_ADMIN . ", " . ADMIN . ") 
			GROUP BY u.user_id 
			HAVING SUM(aa.auth_mod) > 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
		}

		$set_mod = '';
		while( $row = $db->sql_fetchrow($result) )
		{
			$set_mod .= ( ( $set_mod != '' ) ? ', ' : '' ) . $row['user_id'];
		}
		$db->sql_freeresult($result);

		//
		// Update user level to user for appropriate users
		// 
		$sql = "SELECT u.user_id 
			FROM ( ( " . USERS_TABLE . " u  
				LEFT JOIN " . USER_GROUP_TABLE . " ug ON ug.user_id = u.user_id ) 
				LEFT JOIN " . AUTH_ACCESS_TABLE . " aa ON aa.group_id = ug.group_id ) 
			WHERE u.user_level NOT IN (" . USER . ", " . LESS_ADMIN . ", " . ADMIN . ")
			GROUP BY u.user_id 
			HAVING SUM(aa.auth_mod) = 0";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
		}

		$unset_mod = "";
		while( $row = $db->sql_fetchrow($result) )
		{
			$unset_mod .= ( ( $unset_mod != '' ) ? ', ' : '' ) . $row['user_id'];
		}
		$db->sql_freeresult($result);

		if ( $set_mod != '' )
		{
			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_level = " . MOD . " 
				WHERE user_id IN ($set_mod)";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't update user level", "", __LINE__, __FILE__, $sql);
			}
		}

		if ( $unset_mod != '' )
		{
			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_level = " . USER . " 
				WHERE user_id IN ($unset_mod)";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't update user level", "", __LINE__, __FILE__, $sql);
			}
		}

		$sql = 'SELECT user_id FROM ' . USER_GROUP_TABLE . "
        	WHERE group_id = $group_id";
      	$result = $db->sql_query($sql);

      	$group_user = array();
      	while ($row = $db->sql_fetchrow($result))
      	{
      		$group_user[$row['user_id']] = $row['user_id'];
      	}
      	$db->sql_freeresult($result);

      	$sql = "SELECT ug.user_id, COUNT(auth_mod) AS is_auth_mod
        	FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug
        	WHERE ug.user_id IN (" . implode(', ', $group_user) . ")
            	AND aa.group_id = ug.group_id
            	AND aa.auth_mod = 1
         	GROUP BY ug.user_id";
      	if ( !($result = $db->sql_query($sql)) )
      	{
      		message_die(GENERAL_ERROR, 'Could not obtain moderator status', '', __LINE__, __FILE__, $sql);
      	}

      	while ($row = $db->sql_fetchrow($result))
      	{
        	if ($row['is_auth_mod'])
         	{
            	unset($group_user[$row['user_id']]);
         	}
      	}
      	$db->sql_freeresult($result);

      	if (sizeof($group_user))
      	{
        	$sql = "UPDATE " . USERS_TABLE . "
            	SET user_level = " . USER . "
            	WHERE user_id IN (" . implode(', ', $group_user) . ") AND user_level = " . MOD;
         	if ( !($result = $db->sql_query($sql)) )
         	{
         		message_die(GENERAL_ERROR, 'Could not update user level', '', __LINE__, __FILE__, $sql);
         	}
      	}

		if( $return_to_profile )
		{
			$message = $lang['Auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_viewprofile'], '<a href="' . append_sid($phpbb_root_path . "profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
		}
	
		message_die(GENERAL_MESSAGE, $message);
	}
}
else if ( ( $mode == 'user' && ( isset($HTTP_POST_VARS['username']) || $user_id ) ) || ( $mode == 'group' && $group_id ) )
{
	if ( isset($HTTP_POST_VARS['username']) || isset($HTTP_POST_VARS['email']) || isset($HTTP_POST_VARS['posts']) || isset($HTTP_POST_VARS['joined']) )
	{
		//
		// Lookup user
		//
		$username = ( !empty($HTTP_POST_VARS['username']) ) ? str_replace('%', '%%', trim(strip_tags( $HTTP_POST_VARS['username'] ) )) : '';
		$email = ( !empty($HTTP_POST_VARS['email']) ) ? trim(strip_tags(htmlspecialchars( $HTTP_POST_VARS['email'] ) )) : '';
		$posts = ( !empty($HTTP_POST_VARS['posts']) ) ? intval(trim(strip_tags( $HTTP_POST_VARS['posts'] ) )) : '';
		$joined = ( !empty($HTTP_POST_VARS['joined']) ) ? trim(strtotime( $HTTP_POST_VARS['joined'] ) ) : 0;

		$sql_where = ( !empty($username) ) ? 'u.username LIKE "%' . str_replace("\'", "''", $username) . '%"' : '';
		$sql_where .= ( !empty($email) ) ? ( ( !empty($sql_where) ) ? ' AND u.user_email LIKE "%' . $email . '%"' : 'u.user_email LIKE "%' . $email . '%"' ) : '';
		$sql_where .= ( !empty($posts) ) ? ( ( !empty($sql_where) ) ? ' AND u.user_posts >= ' . $posts : 'u.user_posts >= ' . $posts ) : '';
		$sql_where .= ( $joined ) ? ( ( !empty($sql_where) ) ? ' AND u.user_regdate >= ' . $joined : 'u.user_regdate >= ' . $joined ) : '';

		if ( !empty($sql_where) )
		{
			$sql = "SELECT u.user_id, u.username, u.user_level, u.user_email, u.user_posts, u.user_active, u.user_regdate
				FROM " . USERS_TABLE . " u
				WHERE $sql_where
      				AND u.user_id <> " . ANONYMOUS . "
				ORDER BY u.username ASC";

			if ( !( $result = $db->sql_query($sql) ) )
			{
				message_die(GENERAL_ERROR, 'Unable to query users', '', __LINE__, __FILE__, $sql);
			}
			else if ( !$db->sql_numrows($result) )
			{
				$message = $lang['No_user_id_specified'];
				$message .= '<br /><br />' . sprintf($lang['Click_return_perms_admin'], '<a href="' . append_sid("admin_ug_auth.$phpEx?mode=user") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			else if ( $db->sql_numrows($result) == 1 )
			{
				// Redirect to this user
				$row = $db->sql_fetchrow($result);

				$template->assign_vars(array(
					"META" => '<meta http-equiv="refresh" content="5;url=' . append_sid("admin_ug_auth.$phpEx?mode=user&" . POST_USERS_URL . "=" . $row['user_id']) . '">')
				);

				$message .= $lang['One_user_found'];
				$message .= '<br /><br />' . sprintf($lang['Click_goto_user'], '<a href="' . append_sid("admin_ug_auth.$phpEx?mode=user&" . POST_USERS_URL . "=" . $row['user_id']) . '">', '</a>');

				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				// Show select screen
				include('page_header_admin.'.$phpEx);

				$template->set_filenames(array(
					'body' => 'admin/user_lookup_body.tpl')
				);

				$template->assign_vars(array(
					'L_USERNAME' => $lang['Username'],
					'L_USER_TITLE' => $lang['Auth_Control_User'],
					'L_POSTS' => $lang['Posts'],
					'L_JOINED' => $lang['Sort_Joined'],
					'L_USER_EXPLAIN' => $lang['User_admin_explain'],
					'L_ACTIVE' => $lang['User_status'],
					'L_EMAIL_ADDRESS' => $lang['Email_address'])
				);

				$i = 0;
				while ( $row = $db->sql_fetchrow($result) )
				{
					$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

					$template->assign_block_vars('user_row', array(
						'ROW_CLASS' => $row_class,
						'USERNAME' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
						'EMAIL' => $row['user_email'],
						'POSTS' => $row['user_posts'],
						'ACTIVE' => ( $row['user_active'] ) ? $lang['Yes'] : $lang['No'],
						'JOINED' => create_date($lang['DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']),

						'U_USERNAME' => append_sid("admin_ug_auth.$phpEx?mode=user&" . POST_USERS_URL . "=" . $row['user_id']))
					);

					$i++;
				}
				$template->pparse('body');
				include('./page_footer_admin.'.$phpEx);
				exit;
			}
		}
		else
		{
			$message = $lang['No_user_id_specified'];
			$message .= '<br /><br />' . sprintf($lang['Click_return_perms_admin'], '<a href="' . append_sid("admin_ug_auth.$phpEx?mode=user") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	if ( isset($HTTP_POST_VARS['username']) )
	{
		$this_userdata = get_userdata($HTTP_POST_VARS['username'], true);
		if ( !is_array($this_userdata) )
		{
			message_die(GENERAL_MESSAGE, $lang['No_such_user']);
		}
		$user_id = $this_userdata['user_id'];
	}

	//
	// Front end
	//
	$sql = "SELECT f.* 
		FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
		WHERE f.cat_id = c.cat_id
		ORDER BY c.cat_order, f.forum_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain forum information", "", __LINE__, __FILE__, $sql);
	}

	$forum_access = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$forum_access[] = $row;
	}
	$db->sql_freeresult($result);

	if( empty($adv) )
	{
		for($i = 0; $i < count($forum_access); $i++)
		{
			$forum_id = $forum_access[$i]['forum_id'];

			$forum_auth_level[$forum_id] = AUTH_ALL;

			for($j = 0; $j < count($forum_auth_fields); $j++)
			{
				$forum_access[$i][$forum_auth_fields[$j]] . ' :: ';
				if ( $forum_access[$i][$forum_auth_fields[$j]] == AUTH_ACL )
				{
					$forum_auth_level[$forum_id] = AUTH_ACL;
					$forum_auth_level_fields[$forum_id][] = $forum_auth_fields[$j];
				}
			}
		}
	}

	$sql = "SELECT count(*) AS total FROM " . USERS_TABLE . " u, " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug WHERE ";
	$sql .= ( $mode == 'user' ) ? "u.user_id = $user_id AND ug.user_id = u.user_id AND g.group_id = ug.group_id" : "g.group_id = $group_id AND ug.group_id = g.group_id AND u.user_id = ug.user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't count user/group information", "", __LINE__, __FILE__, $sql);
	}
	$count_ug_info = $db->sql_fetchrow($result);
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$pagination_url = "admin_ug_auth.$phpEx?" . (($mode == 'user') ? POST_USERS_URL."=$user_id" : POST_GROUPS_URL . "=$group_id") . "&amp;mode=$mode&amp;adv=$adv";
	$pagination = generate_pagination($pagination_url, $count_ug_info['total'], $board_config['posts_per_page'], $start);

	$sql = "SELECT u.user_id, u.username, u.user_level, g.group_id, g.group_name, g.group_single_user, ug.user_pending 
		FROM " . USERS_TABLE . " u, " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug 
		WHERE ";
	$sql .= ( $mode == 'user' ) ? "u.user_id = $user_id AND ug.user_id = u.user_id AND g.group_id = ug.group_id" : "g.group_id = $group_id AND ug.group_id = g.group_id AND u.user_id = ug.user_id";
	$sql .= " ORDER BY u.username, g.group_name LIMIT $start, ".$board_config['posts_per_page'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain user/group information", "", __LINE__, __FILE__, $sql);
	}
	$ug_info = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$ug_info[] = $row;
	}
	$db->sql_freeresult($result);

	$sql = ( $mode == 'user' ) ? "SELECT aa.*, g.group_single_user FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE. " g WHERE ug.user_id = $user_id AND g.group_id = ug.group_id AND aa.group_id = ug.group_id AND g.group_single_user = 1" : "SELECT * FROM " . AUTH_ACCESS_TABLE . " WHERE group_id = $group_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't obtain user/group permissions", "", __LINE__, __FILE__, $sql);
	}

	$auth_access = array();
	$auth_access_count = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$auth_access[$row['forum_id']][] = $row; 
		$auth_access_count[$row['forum_id']]++;
	}
	$db->sql_freeresult($result);

	$is_admin = ( $mode == 'user' ) ? ( ( $ug_info[0]['user_level'] == ADMIN && $ug_info[0]['user_id'] != ANONYMOUS ) ? 1 : 0 ) : 0;
	$is_super = ( $mode == 'user' ) ? ( ( $ug_info[0]['user_level'] == LESS_ADMIN && $ug_info[0]['user_id'] != ANONYMOUS ) ? 1 : 0 ) : 0; 
	$is_user = ( $mode == 'user' ) ? ( ( $ug_info[0]['user_level'] == USER || $ug_info[0]['user_level'] == MOD && $ug_info[0]['user_id'] != ANONYMOUS ) ? 1 : 0 ) : 0; 

	for($i = 0; $i < count($forum_access); $i++)
	{
		$forum_id = $forum_access[$i]['forum_id'];

		unset($prev_acl_setting);
		for($j = 0; $j < count($forum_auth_fields); $j++)
		{
			$key = $forum_auth_fields[$j];
			$value = $forum_access[$i][$key];

			switch( $value )
			{
				case AUTH_ALL:
				case AUTH_REG:
					$auth_ug[$forum_id][$key] = 1;
					break;

				case AUTH_ACL:
					$auth_ug[$forum_id][$key] = ( !empty($auth_access_count[$forum_id]) ) ? check_auth(AUTH_ACL, $key, $auth_access[$forum_id], $is_admin) : 0;
					$auth_field_acl[$forum_id][$key] = $auth_ug[$forum_id][$key];

					if ( isset($prev_acl_setting) )
					{
						if ( $prev_acl_setting != $auth_ug[$forum_id][$key] && empty($adv) )
						{
							$adv = 1;
						}
					}

					$prev_acl_setting = $auth_ug[$forum_id][$key];

					break;

				case AUTH_MOD:
					$auth_ug[$forum_id][$key] = ( !empty($auth_access_count[$forum_id]) ) ? check_auth(AUTH_MOD, $key, $auth_access[$forum_id], $is_admin) : 0;
					break;

				case AUTH_ADMIN:
					$auth_ug[$forum_id][$key] = $is_admin;
					break;

				default:
					$auth_ug[$forum_id][$key] = 0;
					break;
			}
		}

		//
		// Is user a moderator?
		//
		$auth_ug[$forum_id]['auth_mod'] = ( !empty($auth_access_count[$forum_id]) ) ? check_auth(AUTH_MOD, 'auth_mod', $auth_access[$forum_id], 0) : 0;
	}
	
	$i = 0;
	@reset($auth_ug);
	while( list($forum_id, $user_ary) = @each($auth_ug) )
	{
		if ( empty($adv) )
		{
			if ( $forum_auth_level[$forum_id] == AUTH_ACL )
			{
				$allowed = 1;

				for($j = 0; $j < count($forum_auth_level_fields[$forum_id]); $j++)
				{
					if ( !$auth_ug[$forum_id][$forum_auth_level_fields[$forum_id][$j]] )
					{
						$allowed = 0;
					}
				}

				$optionlist_acl = '<select name="private[' . $forum_id . ']">';

				if ( $is_admin || $user_ary['auth_mod'] )
				{
					$optionlist_acl .= '<option value="1">' . $lang['Allowed_Access'] . '</option>';
				}
				else if ( $allowed )
				{
					$optionlist_acl .= '<option value="1" selected="selected">' . $lang['Allowed_Access'] . '</option><option value="0">'. $lang['Disallowed_Access'] . '</option>';
				}
				else
				{
					$optionlist_acl .= '<option value="1">' . $lang['Allowed_Access'] . '</option><option value="0" selected="selected">' . $lang['Disallowed_Access'] . '</option>';
				}

				$optionlist_acl .= '</select>';
			}
			else
			{
				$optionlist_acl = '&nbsp;';
			}
		}
		else
		{
			for($j = 0; $j < count($forum_access); $j++)
			{
				if ( $forum_access[$j]['forum_id'] == $forum_id )
				{
					for($k = 0; $k < count($forum_auth_fields); $k++)
					{
						$field_name = $forum_auth_fields[$k];

						if( $forum_access[$j][$field_name] == AUTH_ACL )
						{
							$optionlist_acl_adv[$forum_id][$k] = '<select name="private_' . $field_name . '[' . $forum_id . ']">';

							if( isset($auth_field_acl[$forum_id][$field_name]) && !($is_admin || $user_ary['auth_mod']) )
							{
								if( !$auth_field_acl[$forum_id][$field_name] )
								{
									$optionlist_acl_adv[$forum_id][$k] .= '<option value="1">' . $lang['ON'] . '</option><option value="0" selected="selected">' . $lang['OFF'] . '</option>';
								}
								else
								{
									$optionlist_acl_adv[$forum_id][$k] .= '<option value="1" selected="selected">' . $lang['ON'] . '</option><option value="0">' . $lang['OFF'] . '</option>';
								}
							}
							else
							{
								if( $is_admin || $user_ary['auth_mod'] )
								{
									$optionlist_acl_adv[$forum_id][$k] .= '<option value="1">' . $lang['ON'] . '</option>';
								}
								else
								{
									$optionlist_acl_adv[$forum_id][$k] .= '<option value="1">' . $lang['ON'] . '</option><option value="0" selected="selected">' . $lang['OFF'] . '</option>';
								}
							}

							$optionlist_acl_adv[$forum_id][$k] .= '</select>';

						}
					}
				}
			}
		}

		$optionlist_mod = '<select name="moderator[' . $forum_id . ']">';
		$optionlist_mod .= ( $user_ary['auth_mod'] ) ? '<option value="1" selected="selected">' . $lang['Is_Moderator'] . '</option><option value="0">' . $lang['Not_Moderator'] . '</option>' : '<option value="1">' . $lang['Is_Moderator'] . '</option><option value="0" selected="selected">' . $lang['Not_Moderator'] . '</option>';
		$optionlist_mod .= '</select>';

		$row_class = ( !( $i % 2 ) ) ? 'row2' : 'row1';
		$row_color = ( !( $i % 2 ) ) ? $theme['td_color1'] : $theme['td_color2'];

		$template->assign_block_vars('forums', array(
			'ROW_COLOR' => '#' . $row_color,
			'ROW_CLASS' => $row_class,
			'FORUM_NAME' => $forum_access[$i]['forum_name'],

			'U_FORUM_AUTH' => append_sid("admin_forumauth.$phpEx?f=" . $forum_access[$i]['forum_id']),

			'S_MOD_SELECT' => $optionlist_mod)
		);

		if( !$adv )
		{
			$template->assign_block_vars('forums.aclvalues', array(
				'S_ACL_SELECT' => $optionlist_acl)
			);
		}
		else
		{
			for($j = 0; $j < count($forum_auth_fields); $j++)
			{
				$template->assign_block_vars('forums.aclvalues', array(
					'S_ACL_SELECT' => $optionlist_acl_adv[$forum_id][$j])
				);
			}
		}

		$i++;
	}
//	@reset($auth_user);
	
	if ( $mode == 'user' )
	{
		$t_username = $ug_info[0]['username'];
		$s_user_type = '<select name="userlevel">'; 
		$s_user_type .= ($is_admin) ? '<option value="admin" selected=\"selected\">' . $lang['Auth_Admin'] . '</option>' : '<option value="admin">' . $lang['Auth_Admin'] . '</option>'; 
		$s_user_type .= ($is_super) ? '<option value="supermod" selected=\"selected\">' . $lang['Auth_SuperMod'] . '</option>' : '<option value="supermod">' . $lang['Auth_SuperMod'] . '</option>'; 
		$s_user_type .= ($is_user) ? '<option value="user" selected=\"selected\">' . $lang['Auth_User'] . '</option>' : '<option value="user">' . $lang['Auth_User'] . '</option>'; 
		$s_user_type .= '</select>'; 
	}
	else
	{
		$t_groupname = $ug_info[0]['group_name'];
	}

	$name = array();
	$id = array();
	$level = array();
	for($i = 0; $i < count($ug_info); $i++)
	{
		if( ( $mode == 'user' && !$ug_info[$i]['group_single_user'] ) || $mode == 'group' )
		{
			$name[] = ( $mode == 'user' ) ? $ug_info[$i]['group_name'] :  $ug_info[$i]['username'];
			$id[] = ( $mode == 'user' ) ? intval($ug_info[$i]['group_id']) : intval($ug_info[$i]['user_id']);
			$level[] = ( $mode == 'user' ) ? '' : intval($ug_info[$i]['user_level']);
		}
	}

	$t_usergroup_list = $t_pending_list = '';
	if( count($name) )
	{
		for($i = 0; $i < count($ug_info); $i++)
		{
			$ug = ( $mode == 'user' ) ? 'group&amp;' . POST_GROUPS_URL : 'user&amp;' . POST_USERS_URL;

			if (!$ug_info[$i]['user_pending'])
			{
				$t_usergroup_list .= ( ( $t_usergroup_list != '' ) ? ', ' : '' ) . '<a href="' . append_sid("admin_ug_auth.$phpEx?mode=$ug=" . $id[$i]) . '">' . username_level_color($name[$i], $level[$i], $id[$i]) . '</a>';
			}
			else
			{
				$t_pending_list .= ( ( $t_pending_list != '' ) ? ', ' : '' ) . '<a href="' . append_sid("admin_ug_auth.$phpEx?mode=$ug=" . $id[$i]) . '">' . username_level_color($name[$i], $level[$i], $id[$i]) . '</a>';
			}
		}
	}

	$t_usergroup_list = ($t_usergroup_list == '') ? $lang['None'] : $t_usergroup_list;
	$t_pending_list = ($t_pending_list == '') ? $lang['None'] : $t_pending_list;

	$s_column_span = 2; // Two columns always present
	if( !$adv )
	{
		$template->assign_block_vars('acltype', array(
			'L_UG_ACL_TYPE' => $lang['Simple_Permission'])
		);
		$s_column_span++;
	}
	else
	{
		for($i = 0; $i < count($forum_auth_fields); $i++)
		{
			$cell_title = $field_names[$forum_auth_fields[$i]];

			$template->assign_block_vars('acltype', array(
				'L_UG_ACL_TYPE' => $cell_title)
			);
			$s_column_span++;
		}
	}

	//
	// Dump in the page header ...
	//
	include('./page_header_admin.'.$phpEx);

	$template->set_filenames(array(
		"body" => 'admin/auth_ug_body.tpl')
	);

	$adv_switch = ( empty($adv) ) ? 1 : 0;
	$u_ug_switch = ( $mode == 'user' ) ? POST_USERS_URL . "=" . $user_id : POST_GROUPS_URL . "=" . $group_id;
	$switch_mode = append_sid("admin_ug_auth.$phpEx?mode=$mode&amp;" . $u_ug_switch . "&amp;adv=$adv_switch");
	$switch_mode_text = ( empty($adv) ) ? $lang['Advanced_mode'] : $lang['Simple_mode'];
	$u_switch_mode = '<a href="' . $switch_mode . '">' . $switch_mode_text . '</a>';

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_hidden_fields .= '<input type="hidden" name="adv" value="' . $adv . '" />';
	$s_hidden_fields .= ( $mode == 'user' ) ? '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" />' : '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';
	$s_hidden_fields .= ( $mode == 'user' ) ? '<input type="hidden" name="returntoprofile" value="' . $return_to_profile .'" />' : '';

	if ( $mode == 'user' )
	{
		$template->assign_block_vars('switch_user_auth', array());

		$template->assign_vars(array(
			'USERNAME' => $t_username,
			'USER_LEVEL' => $lang['User_Level'] . " : " . $s_user_type,
			'USER_GROUP_MEMBERSHIPS' => sprintf($lang['Group_memberships'], $count_ug_info['total']) . ' : ' . $t_usergroup_list)
		);
	}
	else
	{
		$template->assign_block_vars("switch_group_auth", array());

		$template->assign_vars(array(
			'USERNAME' => $t_groupname,
			'GROUP_MEMBERSHIP' => sprintf($lang['Usergroup_members'], $count_ug_info['total'])  . ' : ' . $t_usergroup_list . '<br />' . $lang['Pending_members'] . ' : ' . $t_pending_list)
		);
	}

	$template->assign_vars(array(
		'L_USER_OR_GROUPNAME' => ( $mode == 'user' ) ? $lang['Username'] : $lang['Group_name'],

		'L_AUTH_TITLE' => ( $mode == 'user' ) ? $lang['Auth_Control_User'] : $lang['Auth_Control_Group'],
		'L_AUTH_EXPLAIN' => ( $mode == 'user' ) ? $lang['User_auth_explain'] : $lang['Group_auth_explain'],
		'L_MODERATOR_STATUS' => $lang['Moderator_status'],
		'L_PERMISSIONS' => $lang['Permissions'],
		'L_FORUM' => $lang['Forum'], 

		'PAGINATION' => $pagination,

		'U_USER_OR_GROUP' => append_sid("admin_ug_auth.$phpEx"),
		'U_SWITCH_MODE' => $u_switch_mode,

		'S_COLUMN_SPAN' => $s_column_span,
		'S_AUTH_ACTION' => append_sid("admin_ug_auth.$phpEx"), 
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);
}
else
{
	//
	// Select a user/group
	//
	include('./page_header_admin.'.$phpEx);

	$template->set_filenames(array(
		'body' => ( $mode == 'user' ) ? 'admin/user_select_body.tpl' : 'admin/auth_select_body.tpl')
	);

	if ( $mode == 'user' )
	{
		$template->assign_vars(array(
			'L_FIND_USERNAME' => $lang['Find_username'],
	 		'L_USER_LOOKUP_EXPLAIN' => $lang['User_lookup_explain'],

			'L_USERNAME' => $lang['Username'],
			'L_EMAIL_ADDRESS' => $lang['Email_address'],
			'L_POSTS' => $lang['Posts'],
			'L_JOINED' => $lang['Joined'],
			'L_JOINED_EXPLAIN' => $lang['User_joined_explain'],

			'U_SEARCH_USER' => append_sid("../search.$phpEx?mode=searchuser"))
		);
	}
	else
	{
		$sql = "SELECT group_id, group_name
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user <> " . TRUE;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Couldn't get group list", "", __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			$select_list = '<select name="' . POST_GROUPS_URL . '">';
			do
			{
				$select_list .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
			}
			while ( $row = $db->sql_fetchrow($result) );
			$select_list .= '</select>';
		}

		$template->assign_vars(array(
			'S_AUTH_SELECT' => $select_list)
		);
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';

	$l_type = ( $mode == 'user' ) ? 'USER' : 'AUTH';

	$template->assign_vars(array(
		'L_' . $l_type . '_TITLE' => ( $mode == 'user' ) ? $lang['Auth_Control_User'] : $lang['Auth_Control_Group'],
		'L_' . $l_type . '_EXPLAIN' => ( $mode == 'user' ) ? $lang['User_auth_explain'] : $lang['Group_auth_explain'],
		'L_' . $l_type . '_SELECT' => ( $mode == 'user' ) ? $lang['Select_a_User'] : $lang['Select_a_Group'],
		'L_LOOK_UP' => ( $mode == 'user' ) ? $lang['Look_up_User'] : $lang['Look_up_Group'],
		'L_CREATE_USER' => $lang['Create_new_User'],

		'S_PROFILE_ACTION' => append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=' . REGISTER_MODE),
		'S_HIDDEN_FIELDS' => $s_hidden_fields, 
		'S_' . $l_type . '_ACTION' => append_sid("admin_ug_auth.$phpEx"))
	);

}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
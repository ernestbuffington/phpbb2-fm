<?php
/** 
*
* @package lang_english
* @version $Id: admin_xdata_auth.php,v 1.1.0 2004/01/06 zayin Exp $
* @copyright (c) 2003 Daniel Lewis
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Users_fields']['Users_fields_users'] = $filename . '?type=user';
    $module['Users_fields']['Users_fields_groups'] = $filename . '?type=group';
    
	return;
}

$no_page_header = TRUE;

$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// include language file 
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_xd.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_xd.' . $phpEx);

//
// Set mode & type
//
if( isset( $HTTP_POST_VARS['mode'] ) || isset( $HTTP_GET_VARS['mode'] ) )
{
	$mode = ( isset( $HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}

if( isset( $HTTP_POST_VARS['type'] ) || isset( $HTTP_GET_VARS['type'] ) )
{
	$type = ( isset( $HTTP_POST_VARS['type']) ) ? $HTTP_POST_VARS['type'] : $HTTP_GET_VARS['type'];
	$type = htmlspecialchars($type); 
}
else
{
	$type = '';
}

//
// Begin program
//
if ( $mode == 'lookup' )
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
    		$message = $lang['No_user_id_specified'] . '<br /><br />' . sprintf($lang['Click_return_xdata_uperms'], '<a href="' . append_sid("admin_xdata_auth.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
    	}
    	else if ( $db->sql_numrows($result) == 1 )
    	{
      		// Redirect to this user
			$row = $db->sql_fetchrow($result);

     		$message = $lang['One_user_found'] . '<br /><br /><meta http-equiv="refresh" content="3;url=' . append_sid("admin_xdata_auth.$phpEx?type=user&mode=edit&" . POST_USERS_URL . "=" . $row['user_id']) . '">' . sprintf($lang['Click_goto_user'], '<a href="' . append_sid("admin_xdata_auth.$phpEx?type=user&amp;mode=edit&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '">', '</a>');

     		message_die(GENERAL_MESSAGE, $message);
    	}
    	else
    	{
    		// Show select screen
    		include('./page_header_admin.'.$phpEx);

    		$template->set_filenames(array(
		    	'body' => 'admin/user_lookup_body.tpl')
	    	);

	    	$template->assign_vars(array(
  	  			'L_USER_TITLE' => $lang['User_admin'],
	  			'L_USER_EXPLAIN' => $lang['User_admin_explain'],
				'L_USERNAME' => $lang['Username'],
				'L_POSTS' => $lang['Posts'],
				'L_JOINED' => $lang['Joined'],
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

					'U_USERNAME' => append_sid("admin_xdata_auth.$phpEx?type=user&amp;mode=edit&amp;" . POST_USERS_URL . "=" . $row['user_id']))
		        );

    		    $i++;
      		}
     		$template->pparse('body');
     		exit;
    	}
  	}
  	else
  	{
		$message = $lang['No_user_id_specified'];
		
		$message .= '<br /><br />' . sprintf($lang['Click_return_xdata_uperms'], '<a href="' . append_sid("admin_xdata_auth.$phpEx?type=user") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		
		message_die(GENERAL_MESSAGE, $message);
  	}
}
	
if ($type == 'user')
{
	if ( ( $mode == 'edit' || $mode == 'save' ) && ( isset($HTTP_POST_VARS['username']) || isset($HTTP_GET_VARS[POST_USERS_URL]) || isset( $HTTP_POST_VARS[POST_USERS_URL]) ) )
	{
		$xd_meta = get_xd_metadata();

	    if ( isset($HTTP_POST_VARS['username']) )
		{
			$this_userdata = get_userdata($HTTP_POST_VARS['username'], true);
			if ( !is_array($this_userdata) )
			{
				message_die(GENERAL_MESSAGE, $lang['No_such_user']);
			}
			$user_id = $this_userdata['user_id'];
		}
		else
		{
			$user_id = ( isset($HTTP_POST_VARS[POST_USERS_URL]) ) ? $HTTP_POST_VARS[POST_USERS_URL] : $HTTP_GET_VARS[POST_USERS_URL];
			
			$this_userdata = get_userdata($user_id, true);
			if ( !is_array($this_userdata) )
			{
				message_die(GENERAL_MESSAGE, $lang['No_such_user']);
			}
			$username = $this_userdata['username'];
		}

		if ( !isset($HTTP_POST_VARS['submit']) )
		{
			//
			// Show the edit form
    		//
    		include('./page_header_admin.'.$phpEx);

			$template->set_filenames( array(
				'body' => 'admin/xd_auth_body.tpl')
			);

			$template->assign_vars( array(
				'L_AUTH_TITLE' => $lang['xd_permissions'],
				'L_USERNAME' => $lang['Username'],
				'L_PERMISSIONS' => $lang['Permissions'],
				'L_AUTH_EXPLAIN' => $lang['xd_permissions_describe'],
				'L_FIELD_NAME' => $lang['field_name'],
				'L_ALLOW' => $lang['Allow'],
				'L_DEFAULT' => $lang['Default'],
				'L_DENY' => $lang['Deny'],

				'AUTH_ALLOW' => XD_AUTH_ALLOW,
				'AUTH_DENY' => XD_AUTH_DENY,
				'AUTH_DEFAULT' => XD_AUTH_DEFAULT,

				'USERNAME' => $username,
				'S_HIDDEN_FIELDS' => '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $user_id . '" /><input type="hidden" name="mode" value="save" /><input type="hidden" name="type" value="user" />',
				'S_AUTH_ACTION' => append_sid('admin_xdata_auth.'.$phpEx))
			);

			while ( list($code_name, $meta) = each($xd_meta) )
			{
				$sql = "SELECT xa.auth_value FROM " . XDATA_AUTH_TABLE . " xa, " . USER_GROUP_TABLE . " ug
					WHERE xa.field_id = " . $meta['field_id'] . "
						AND xa.group_id = ug.group_id
						AND ug.user_id = " . $user_id;
				if ( ! ( $result = $db->sql_query($sql) ) )
				{
	            	message_die(GENERAL_ERROR, 'Unable to get current permissions info.', '', __LINE__, __FILE__, $sql);
				}
				$row = $db->sql_fetchrow($result);

				$auth = isset($row['auth_value']) ? $row['auth_value'] : XD_AUTH_DEFAULT;

				$template->assign_block_vars('xdata', array(
					'CODE_NAME' => $code_name,
					'NAME' => $meta['field_name'],

					'ALLOW_CHECKED' => ( ( $auth == XD_AUTH_ALLOW ) ? 'checked="checked" ' : '' ),
					'DENY_CHECKED' => ( ( $auth == XD_AUTH_DENY ) ? 'checked="checked" ' : '' ),
					'DEFAULT_CHECKED' => ( ($auth == XD_AUTH_DEFAULT ) ? 'checked="checked" ' : ''))
				);
			}

			$template->pparse('body');
		}
		else
		{
			//
			// Save the settings
			//
			$sql = "SELECT g.group_id FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
				WHERE g.group_id = ug.group_id 
					AND ug.user_id = " . $user_id;
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Error when getting users personal group.', '', __LINE__, __FILE__, $sql);
			}
			$personal_group = $db->sql_fetchrow($result);
			$personal_group = $personal_group['group_id'];

			while ( list($code_name, $meta) = each($xd_meta) )
			{
				$auth = $HTTP_POST_VARS["xd_$code_name"];

	            $sql = "DELETE FROM " . XDATA_AUTH_TABLE . "
					WHERE group_id = $personal_group
					AND field_id = {$meta['field_id']}";
	            if (! $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Error when updating XData authorization.", "", __LINE__, __FILE__, $sql);
				}

				if ( $auth != XD_AUTH_DEFAULT )
				{
					$sql = "INSERT INTO " . XDATA_AUTH_TABLE . " (group_id, field_id, auth_value)
						VALUES ({$personal_group}, {$meta['field_id']}, {$auth})";
					if (! $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Error when updating XData authorization.", "", __LINE__, __FILE__, $sql);
					}
				}
			}

		    $message = $lang['Auth_updated'] . "<br /><br />" . sprintf($lang['Click_return_xdata_uperms'], "<a href=\"" . append_sid("admin_xdata_auth.$phpEx?type=user") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else
	{
		//
		// Default user selection box
		//
    	include('./page_header_admin.'.$phpEx);
		
		$template->set_filenames(array(
			'body' => 'admin/user_select_body.tpl')
		);

		$template->assign_vars(array(
			'L_USER_TITLE' => $lang['xd_permissions'],
			'L_USER_EXPLAIN' => $lang['xd_permissions_describe'],
			'L_USER_LOOKUP_EXPLAIN' => $lang['User_lookup_explain'],
			'L_LOOK_UP' => $lang['Look_up_User'],
			'L_CREATE_USER' => $lang['Create_new_User'],
			'L_USERNAME' => $lang['Username'],
			'L_EMAIL_ADDRESS' => $lang['Email_address'],
			'L_POSTS' => $lang['Posts'],
			'L_JOINED' => $lang['Joined'],
			'L_JOINED_EXPLAIN' => $lang['User_joined_explain'],

			'U_SEARCH_USER' => append_sid("./../search.$phpEx?mode=searchuser"), 

			'S_PROFILE_ACTION' => append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=' . REGISTER_MODE),
			'S_USER_ACTION' => append_sid("admin_xdata_auth.$phpEx?type=user"),
			'S_USER_SELECT' => $select_list)
		);
		
		$template->pparse('body');
	}
}
elseif ($type == 'group')
{
	if ( ( $mode == 'edit' || $mode == 'save' ) && ( isset($HTTP_POST_VARS['group']) || isset($HTTP_GET_VARS[POST_GROUPS_URL]) || isset( $HTTP_POST_VARS[POST_GROUPS_URL]) ) )
	{

    	$xd_meta = get_xd_metadata();

	    if ( isset($HTTP_POST_VARS['group']) )
		{
			$group_id = $HTTP_POST_VARS['group'];
		}
		else
		{
			$group_id = ( isset($HTTP_POST_VARS[POST_GROUPS_URL]) ) ? $HTTP_POST_VARS[POST_GROUPS_URL] : $HTTP_GET_VARS[POST_GROUPS_URL];
		}
		
		if ( ! isset($HTTP_POST_VARS['submit']) )
		{
			//
			// Show the edit form
			//
    		include('./page_header_admin.'.$phpEx);

			$template->set_filenames( array(
				'body' => 'admin/xd_auth_body.tpl')
			);

			$sql = "SELECT group_name FROM " . GROUPS_TABLE . "
			        WHERE group_id = {$group_id}";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Unable to retrieve group name.", "", __LINE__, __FILE__, $sql);
			}
			$group_name = $db->sql_fetchrow($result);
			
			$group_name = $group_name['group_name'];

			$template->assign_vars( array(
				'L_AUTH_TITLE' => $lang['XD_auth_Control_Group'],
				'L_USERNAME' => $lang['group_name'],
				'L_PERMISSIONS' => $lang['Permissions'],
				'L_AUTH_EXPLAIN' => $lang['XD_group_auth_explain'],
				'L_FIELD_NAME' => $lang['field_name'],
				'L_ALLOW' => $lang['Allow'],
				'L_DEFAULT' => $lang['Default'],
				'L_DENY' => $lang['Deny'],
				'L_SUBMIT' => $lang['Submit'],
				'L_RESET' => $lang['Reset'],

				'AUTH_ALLOW' => XD_AUTH_ALLOW,
				'AUTH_DENY' => XD_AUTH_DENY,
				'AUTH_DEFAULT' => XD_AUTH_DEFAULT,

				'USERNAME' => $group_name,
				'S_HIDDEN_FIELDS' => '<input type="hidden" name="'.POST_GROUPS_URL.'" value="'.$group_id.'" /><input type="hidden" name="mode" value="save" /><input type="hidden" name="type" value="group" />',
				'S_AUTH_ACTION' => append_sid('admin_xdata_auth.'.$phpEx))
			);

			while ( list($code_name, $meta) = each($xd_meta) )
			{
				$sql = "SELECT xa.auth_value FROM " . XDATA_AUTH_TABLE . " xa
					WHERE xa.field_id = {$meta['field_id']}
						AND xa.group_id = {$group_id}";
				if ( ! ( $result = $db->sql_query($sql) ) )
				{
	            	message_die(GENERAL_ERROR, "Unable to get current permissions info.", "", __LINE__, __FILE__, $sql);
				}

				$row = $db->sql_fetchrow($result);

				$auth = isset($row['auth_value']) ? $row['auth_value'] : XD_AUTH_DEFAULT;

				$template->assign_block_vars( 'xdata', array(
					'CODE_NAME' => $code_name,
					'NAME' => $meta['field_name'],

					'ALLOW_CHECKED' => ( ( $auth == XD_AUTH_ALLOW ) ? 'checked="checked" ' : '' ),
					'DENY_CHECKED' => ( ( $auth == XD_AUTH_DENY ) ? 'checked="checked" ' : '' ),
					'DEFAULT_CHECKED' => ( ($auth == XD_AUTH_DEFAULT ) ? 'checked="checked" ' : ''))
				);

			}

			$template->pparse('body');
		}
		else
		{
        	//
			// Save the settings
			//
			while ( list($code_name, $meta) = each($xd_meta) )
			{
				$auth = $HTTP_POST_VARS["xd_$code_name"];

	            $sql = "DELETE FROM " . XDATA_AUTH_TABLE . "
					WHERE group_id = $group_id
						AND field_id = {$meta['field_id']}";
	            if (! $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Error when updating XData authorization.", "", __LINE__, __FILE__, $sql);
				}

				if ( $auth != XD_AUTH_DEFAULT )
				{
					$sql = "INSERT INTO " . XDATA_AUTH_TABLE . " (group_id, field_id, auth_value)
						VALUES ({$group_id}, {$meta['field_id']}, {$auth})";
					if (! $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Error when updating XData authorization.", "", __LINE__, __FILE__, $sql);
					}
				}
			}

		    $message = $lang['Auth_updated'] . "<br /><br />" . sprintf($lang['Click_return_xdata_gperms'], "<a href=\"" . append_sid("admin_xdata_auth.$phpEx?type=group") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else
	{
    	//
		// Select a user/group
		//
		include('./page_header_admin.'.$phpEx);

		$template->set_filenames( array(
			'body' => 'admin/auth_select_body.tpl') 
		);

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

		$s_hidden_fields = '<input type="hidden" name="mode" value="edit" /><input type="hidden" name="type" value="group" />';

		$template->assign_vars(array(
			'L_AUTH_TITLE' => $lang['XD_auth_Control_Group'],
			'L_AUTH_EXPLAIN' => $lang['XD_group_auth_explain'],
			'L_AUTH_SELECT' => $lang['Select_a_Group'],
			'L_LOOK_UP' => $lang['Look_up_Group'],

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_AUTH_ACTION' => append_sid("admin_xdata_auth.$phpEx"))
		);

        $template->pparse('body');
	}
}

include('./page_footer_admin.'.$phpEx);

?>
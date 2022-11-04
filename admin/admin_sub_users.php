<?php
/** 
*
* @package admin
* @version $Id: admin_user_exptime.php,v 1.0.0.1 2004/10/29 17:49:33 acydburn Exp $
* @copyright (c) 2004 Loewen Enterprise - Xiong Zou
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Donations_Subscriptions']['User_Subscriptions'] = $filename;

	return;
}

//
// Let's set the root dir for phpBB
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.' . $phpEx);


//
// Set mode
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}

if ( isset($HTTP_POST_VARS['submit']) || isset($HTTP_GET_VARS['submit']) )
{
	$mode = (isset($HTTP_POST_VARS['submit'])) ? $HTTP_POST_VARS['submit'] : $HTTP_GET_VARS['submit'];	
}
else if (isset($HTTP_POST_VARS['reset']) || isset($HTTP_GET_VARS['reset']))
{
	$mode = (isset($HTTP_POST_VARS['reset'])) ? $HTTP_POST_VARS['reset'] : $HTTP_GET_VARS['reset'];	
}

if ( $mode == 'edit')
{
	$user_id = ( isset($HTTP_POST_VARS[POST_USERS_URL]) ) ? $HTTP_POST_VARS[POST_USERS_URL] : $HTTP_GET_VARS[POST_USERS_URL];

	$userinfo = get_userdata($user_id, true);	
	if ( !is_array($userinfo) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_such_user']);
	}
	
	include('./page_header_admin.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'admin/sub_users_body.tpl')
	);

	$sql = "SELECT g.* FROM " . GROUPS_TABLE . " g 
		WHERE g.group_type = " . GROUP_PAYMENT . " 
			AND g.group_amount > 0";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain payment group information.", "", __LINE__, __FILE__, $sql);
	}
	$paymentgroups = $db->sql_fetchrowset($result);
	
	$sql = "SELECT g.*, ug.* FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug 
		WHERE g.group_type = " . GROUP_PAYMENT . " 
			AND g.group_amount > 0 
			AND g.group_id = ug.group_id 
			AND ug.user_id = " . $user_id;
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't obtain user group information.", "", __LINE__, __FILE__, $sql);
	}
	$usergroups = $db->sql_fetchrowset($result);
	
	
	for($i = 0; $i < sizeof($paymentgroups); $i++)
	{
		$userinthisgrp = 0;
		$expiretime = '0';
		$expiretimeinint = 0;
		for($j = 0; $j < count($usergroups); $j++)
		{
			if($paymentgroups[$i]['group_id'] == $usergroups[$j]['group_id'])
			{
				$userinthisgrp = 1;
				$expiretime = date("Y/m/d H:i:s", $usergroups[$j]['ug_expire_date']);
				$expiretimeinint = $usergroups[$j]['ug_expire_date'];
			}
		}
					
		$hidden_fields = $ingrpselect = $notingrpselect = '';
		
		if ( $userinthisgrp == 1 )
		{
			$ingrpselect .= ' selected="selected"';
		}
		else
		{
			$notingrpselect .= ' selected="selected"';
		}
		
		$ingrpornot .= '<select name="ingrpornot">';
		$ingrpornot .= '<option value="1"' . $ingrpselect . '>' . $lang['Yes'] . '</option>';
		$ingrpornot .= '<option value="0"' . $notingrpselect . '>' . $lang['No'] . '</option>';
		$ingrpornot .= '</select>';
		
		$expiretimefield = '<input class="post" type="text" maxlength="255" name="user_expire_date" value="' . $expiretime . '" />';
		
		$hidden_fields .= '<input type="hidden" name="username" value="' . $userinfo['username'] . '">';
		$hidden_fields .= '<input type="hidden" name="user_table_expiretime" value="' . $userinfo['user_expire_date'] . '">';
		$hidden_fields .= '<input type="hidden" name="user_id" value="' . $userinfo['user_id'] . '">';
		$hidden_fields .= '<input type="hidden" name="prev_expiredate" value="' . $expiretimeinint . '">';
		$hidden_fields .= '<input type="hidden" name="prev_ingrpornot" value="' . $userinthisgrp . '">';
		$hidden_fields .= '<input type="hidden" name="group_id" value="' . $paymentgroups[$i]['group_id'] . '">';
		$hidden_fields .= '<input type="hidden" name="mode" value="submit" />';
		
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('row', array(
			'ROW_CLASS' => $row_class,
			'LW_SUB_GRP_PROFILE' => append_sid('admin_groups.' . $phpEx . '?' . POST_GROUPS_URL . '=' . $paymentgroups[$i]['group_id'] . '&edit=view'),
			'LW_SUB_GRP_NAME' => $paymentgroups[$i]['group_name'], 
			'LW_SUB_GRP_INORNOT' => $ingrpornot, 
			'LW_SUB_EXPTIME' => $expiretimefield,
			'LW_SUB_ACTION' => '<input type="submit" name="mode" value="' . $lang['L_IPN_user_sub_Update'] . '" class="mainoption" />',
			
			'S_HIDDEN_FIELDS' => $hidden_fields)
		);
	}

	$template->assign_vars(array(
		'L_LW_SUBSCRIPTIONS' => $lang['L_IPN_user_sub_info'] . ': ' . $userinfo['username'],
		'L_LW_SUB_EXPLAIN' => $lang['L_IPN_user_sub_info_exp'],
		'L_LW_SUB_GROUP_NAME' => $lang['L_IPN_grp_name'],
		'L_LW_SUB_GROUP_INORNOT' => $lang['L_IPN_grp_inornot'],
		'L_LW_SUB_EXPIRATION' => $lang['L_IPN_grp_expire_date'],
		'L_LW_SUB_ACTION' => $lang['L_IPN_grp_action'],
		
		'S_ACTION' => append_sid('admin_sub_users.'.$phpEx))
	);
	
	$template->pparse('body');
		
}
else if ( $mode == 'submit' )
{
	$group_id = intval($HTTP_POST_VARS['group_id']);
	$user_id = intval($HTTP_POST_VARS['user_id']);
	
	if($group_id <= 0 || $user_id <= 0 )
	{
		message_die(GENERAL_ERROR, "Couldn't update user group information.", "", __LINE__, __FILE__, $sql);
	}
	
	$ingrpornot = intval($HTTP_POST_VARS['ingrpornot']);
	$prev_ingrpornot = intval($HTTP_POST_VARS['prev_ingrpornot']);
	$user_expire_date_str = str_replace("\'", "''", htmlspecialchars(trim($HTTP_POST_VARS['user_expire_date'])));
	$username = str_replace("\'", "''", htmlspecialchars(trim($HTTP_POST_VARS['username'])));
	
	$prev_expiredate = intval($HTTP_POST_VARS['prev_expiredate']);
	$user_table_expiretime = intval($HTTP_POST_VARS['user_table_expiretime']);
	
	
	$userexpiretime = $prev_expiredate;
	if(strlen($user_expire_date_str) == strlen("yyyy/mm/dd hh:mm:ss"))
	{
		$userexpiretime = mktime(substr($user_expire_date_str, 11, 2), substr($user_expire_date_str, 14, 2), substr($user_expire_date_str, 17, 2), substr($user_expire_date_str, 5, 2), substr($user_expire_date_str, 8, 2), substr($user_expire_date_str, 0, 4));
	}
	
	if($ingrpornot != $prev_ingrpornot)
	{
		if($ingrpornot == 0) //remove from current group
		{
			$sql = "DELETE FROM " . USER_GROUP_TABLE . " 
				WHERE group_id = " . $group_id . " 
				AND user_id = " . $user_id;
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't update user group information.", "", __LINE__, __FILE__, $sql);
			}
		}
		else if($ingrpornot == 1) //insert into current group 
		{
			$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending, ug_expire_date, ug_active_date) 
				VALUES (" . $user_id . ", " . $group_id . ", 0, " . $userexpiretime . ", " . time() . ")";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't update user group information.", "", __LINE__, __FILE__, $sql);
			}
			if($user_table_expiretime <= 0 && $userexpiretime > 0)
			{
				$sql = "UPDATE " . USERS_TABLE . " 
					SET user_actviate_date = " . time() . ", user_expire_date = " . $userexpiretime . " 
					WHERE user_id = " . $user_id;	
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Couldn't update user information.", "", __LINE__, __FILE__, $sql);
				}
			}		
		}
	}
	else
	{
		if($ingrpornot == 1 && $userexpiretime != $prev_expiredate) //if user in group and need to update expiration date
		{
			$sql = "UPDATE " . USER_GROUP_TABLE . " 
				SET ug_active_date = " . time() . ", ug_expire_date = " . $userexpiretime . " 
				WHERE user_id = " . $user_id . " 
					AND group_id = " . $group_id;			
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Couldn't update user group information.", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	$message = $lang['L_IPN_user_sub_updated'] . "<br /><br />" . sprintf($lang['L_IPN_click_update_again'], "<a href=\"" . append_sid("admin_sub_users.$phpEx?mode=edit&amp;" . POST_USERS_URL . "=" . $user_id) . "\">", "</a>") . "<br /><br />" . sprintf($lang['L_IPN_click_return'], '<a href="' . append_sid('admin_sub_users.'.$phpEx) . '">', '</a>') . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}
else if ( $mode == 'lookup' )
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
    		$message = $lang['No_user_id_specified'] . '<br /><br />' . sprintf($lang['L_IPN_click_return'], '<a href="' . append_sid("admin_sub_users.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
    	}
    	else if ( $db->sql_numrows($result) == 1 )
    	{
      		// Redirect to this user
			$row = $db->sql_fetchrow($result);

     		$template->assign_vars(array(
    			"META" => '<meta http-equiv="refresh" content="0;url=' . append_sid("admin_sub_users.$phpEx?mode=edit&" . POST_USERS_URL . "=" . $row['user_id']) . '">')
	    	);

     		$message .= $lang['One_user_found'] . '<br /><br />' . sprintf($lang['Click_goto_user'], '<a href="' . append_sid("admin_sub_users.$phpEx?mode=edit&" . POST_USERS_URL . "=" . $row['user_id']) . '">', '</a>');

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
				'L_ACTIVE' => $lang['Active'],
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
					'ACTIVE' => ( $row['user_active'] ) ? '<img src="' . $phpbb_root_path . $images['acp_disable'] . '" alt="' . $lang['Yes'] . '" title="' . $lang['Yes'] . '" />' : '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['No'] . '" title="' . $lang['No'] . '" />',
					'JOINED' => create_date($lang['DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']),

					'U_USERNAME' => append_sid("admin_sub_users.$phpEx?mode=edit&amp;" . POST_USERS_URL . "=" . $row['user_id']))
		        );

    		    $i++;
      		}
     		$template->pparse('body');
    	}
  	}
  	else
  	{
		$message = $lang['No_user_id_specified'] . '<br /><br />' . sprintf($lang['L_IPN_click_return'], '<a href="' . append_sid("admin_sub_users.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
		
		message_die(GENERAL_MESSAGE, $message);
  	}
}
else
{
	//
	// Force e-mail update 
	//
	if ( !empty($HTTP_GET_VARS['forceemail_username']) )
	{
		$forceemail_user_id = $HTTP_GET_VARS['forceemail_username'];
	
		$sql = "SELECT username 
			FROM " . USERS_TABLE . "
			WHERE user_id = $forceemail_user_id";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain username information for force email update', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		
		$force_email_username = $row['username'];
	}
	

	//
	// Default user selection box
	//
	include('./page_header_admin.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'admin/user_select_body.tpl')
	);

	$template->assign_vars(array(
		'L_USER_TITLE' => $lang['L_IPN_user_sub_title'],
		'L_USER_EXPLAIN' => $lang['L_IPN_user_sub_enplain'],
		'L_USER_LOOKUP_EXPLAIN' => $lang['User_lookup_explain'],
		'L_LOOK_UP' => $lang['Look_up_User'],
		'L_CREATE_USER' => $lang['Create_new_User'],
		'L_USERNAME' => $lang['Username'],
		'L_EMAIL_ADDRESS' => $lang['Email_address'],
		'L_POSTS' => $lang['Posts'],
		'L_JOINED' => $lang['Joined'],
		'L_JOINED_EXPLAIN' => $lang['User_joined_explain'],

		'FORCEEMAIL_USERNAME' =>  $force_email_username, 
		'U_SEARCH_USER' => append_sid("./../search.$phpEx?mode=searchuser"), 

		'S_PROFILE_ACTION' => append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=register'),
		'S_USER_ACTION' => append_sid('admin_sub_users.'.$phpEx),
		'S_USER_SELECT' => $select_list)
	);
		
	$template->pparse('body');
}

include('./page_footer_admin.'.$phpEx);

?>
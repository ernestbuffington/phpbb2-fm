<?php
/***************************************************************************
 *                              admin_user_referrals.php
 *                            -------------------
 *   begin                : Friday, June 8, 2002
 *   copyright           : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *   created by         : John B. Abela <abela@phpbb.com>
 *
 *
****************************************************************************/

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
	$file = basename(__FILE__);
	$module['Users']['User_Referrals'] = $file;
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// start switch
//
if( isset($HTTP_POST_VARS['delete']) || isset($HTTP_GET_VARS['delete']) )
{
	//
	// Delete all referers data 
	//
	$sql = "DELETE FROM " . REFERRAL_TABLE;
	if (!$query = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not delete referrals.', '', __LINE__, __FILE__, $sql);
	}
	$message = $lang['Referral_log_delete_success'] . "<br /><br />" . sprintf($lang['Click_return_referral_log_settings'], "<a href=\"" . append_sid("admin_referral_log.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);		
}
else
{
	$template->set_filenames(array(
		'body' => 'admin/user_referrals_body.tpl')
	);

	$sql = "SELECT * 
		FROM " . REFERRAL_TABLE . " 
		WHERE ruid != '-1'
		ORDER BY referral_id DESC";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain referrals.', __LINE__, __FILE__, $sql);
	}
	$referral_rows = $db->sql_fetchrowset($result);
	
	$referral_count = count($referral_rows);

	$template->assign_vars(array(
		'L_DELETE_ALL' => $lang['Delete_all'],
		'L_REFERRAL_TITLE' => $lang['User_Referrals'],
		'L_REFERRAL_DESCRIPTION' => $lang['Referral_Admin_Explain'],
		'L_REFERRAL_R_NAME' => $lang['Referral_Admin_Referral_Name'],
        'L_REFERRAL_N_NAME' => $lang['Referral_Admin_New_Name'],
		'L_REFERRAL_DATETIME' => $lang['Date'],
		
		'S_FORM_ACTION' => append_sid('admin_user_referrals.'.$phpEx))
	);

	for($i = 0; $i < $referral_count; $i++)
	{
    	// GET REFERRAL USER DATA
        $ruid = $referral_rows[$i]['ruid'];
        settype($ruid, "integer");
        //
        $this_userdata = get_userdata($ruid);
	 	if( !$this_userdata )
	  	{
	  		$referred_username = $lang['Referral_Admin_User_Delete'];
	  	}
	  	else
	  	{
			$temp_url = append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $this_userdata['user_id']);
			$referred_username = '<a href="' . $temp_url . '" class="genmed">' . username_level_color($this_userdata['username'], $this_userdata['user_level'], $this_userdata['user_id']) . '</a> &nbsp; [ ' .$lang['Referral_Admin_User_ID'] . ' ' . $this_userdata['user_id'] . ' ]';
	  	}
        
	  	//
        // GET NEW USER DATA
        $nuid = $referral_rows[$i]['nuid'];
        settype($nuid, "integer");
        //
        $new_userdata = get_userdata($nuid);
		if( !$new_userdata )
		{
			$new_username = $lang['Referral_Admin_User_Delete'];
		}
		else
		{
			$temp_url = append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $new_userdata['user_id']);
			$new_username = '<a href="' . $temp_url . '" class="genmed">' . username_level_color($new_userdata['username'], $new_userdata['user_level'], $new_userdata['user_id']) . '</a> &nbsp; [ ' .$lang['Referral_Admin_User_ID'] . ' ' . $new_userdata['user_id'] . ' ]';
		}

        $referral_date = create_date($board_config['default_dateformat'], $referral_rows[$i]['referral_time'], $board_config['board_timezone']);

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars("words", array(
			"ROW_CLASS" => $row_class,
			"REFERRED_USER" => $referred_username,
           	"NEW_USER" => $new_username,
			"REPLACEMENT" => $referral_date)
		);
	}
}

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
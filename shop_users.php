<?php
/***************************************************************************
 *                              shop_users.php
 *                            -------------------
 *   Version              : 1.0.0
 *   began                : Sunday, May 16th, 2004
 *   email                : zarath@knightsofchaos.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);


// Include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_shop.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_shop.' . $phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_SHOP);
init_userprefs($userdata);
//
// End session management
//


//
// Check if shops are open or closed.
//
if ( !($board_config['u_shops_enabled']) )
{
	message_die(GENERAL_MESSAGE, $lang['User_shops_disabled']);
}


//
// Default page
//
if ( empty($_REQUEST['action']) )
{
	$template->set_filenames(array(
		'body' => 'shop_users_body.tpl')
	);
   	make_jumpbox('viewforum.'.$phpEx); 

	if ( strlen($_REQUEST['search_string']) > 1 )
	{
		$search_string = addslashes(stripslashes($_REQUEST['search_string']));

		$sql = "SELECT DISTINCT a.*, b.shop_id, u.user_level
			FROM " . TABLE_USER_SHOPS . " a, " . TABLE_USER_SHOP_ITEMS . " b, " . SHOPITEMS_TABLE . " c
				LEFT JOIN " . USERS_TABLE . " AS u ON a.user_id = u.user_id 
			WHERE ( ( (a.shop_status = 0 OR a.shop_status = 2) AND a.id = b.shop_id )
				AND ( b.item_id = c.id )
				AND ( c.name LIKE '%" . $search_string . "%' ) )
			GROUP BY b.shop_id
			ORDER BY shop_updated DESC";
	}
	else
	{
		$sql = "SELECT DISTINCT a.*, b.shop_id, u.user_level
			FROM " . TABLE_USER_SHOPS . " a
				LEFT JOIN " . TABLE_USER_SHOP_ITEMS . " AS b ON a.id = b.shop_id
				LEFT JOIN " . USERS_TABLE . " AS u ON a.user_id = u.user_id 
			WHERE ( (a.shop_status = 0 OR a.shop_status = 2) AND a.id = b.shop_id )
			GROUP BY b.shop_id
			ORDER BY shop_updated DESC";
	}
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain personal shop data.', '', __LINE__, __FILE__, $sql);
	}
	$sql_num_rows = $db->sql_numrows($result);

	for ($i = 0; $i < $sql_num_rows; $i++)
	{
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain personal shop data.', '', __LINE__, __FILE__, $sql);
		}

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('shop_row', array(
			'ROW_CLASS' => $row_class,
			'SHOP_URL' => append_sid('shop_users_view.'.$phpEx.'?shop='. $row['id']),
			'SHOP_NAME' => $row['shop_name'],
			'SHOP_TYPE' => $row['shop_type'],
			'SHOP_OWNER' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
			
			'U_VIEWPROFILE' => append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']))
		);
			
		$user_has_shop = ( $row['user_id'] == $userdata['user_id'] || $user_has_shop ) ? 1 : 0;
	}

	if ( $sql_num_rows == 0 )
	{
		$template->assign_block_vars('switch_no_shops', array(
			'L_NO_SHOPS' => $lang['No_user_shops'])
		);
	}
	else
	{
		$template->assign_block_vars('switch_is_shops', array());
	}

	$user_shop_msg = ( $user_has_shop ) ? $lang['Edit_shop'] : $lang['Open_shop'];

	$template->assign_block_vars('switch_user_shop', array(
		'U_OPEN_SHOP' => sprintf($lang['Click_open_shop'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>', $user_shop_msg))
	);

	if ( !empty($userdata['user_specmsg']) )
	{
		$template->assign_block_vars('switch_special_msg', array(
			'SPECIAL_MSGS' => $userdata['user_specmsg'],
			'SPECIAL_MSGS_URL' => append_sid('shop.'.$phpEx.'?clm=true'),

			'L_CLEAR' => $lang['Clear_Messages'])
		);
	} 

	$page_title = $lang['User_shops'];
	$shoplocation = ' -> <a href="' . append_sid('shop.'.$phpEx) . '" class="nav">' . $lang['Shop'] . '</a>';

	$template->assign_vars(array(
		'SHOPLOCATION' => $shoplocation,
		'SHOP_TRANS' => ( $board_config['shop_trans_enable'] ) ? '| <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>' : '',
		
		'L_SHOP_TITLE' => $page_title,
		'L_INVENTORY' => $lang['Your_Inventory'],
		'L_SHOP_NAME' => $lang['Shop_Name'],
		'L_SHOP_TYPE' => $lang['Shop_Type'],
					
		'U_INVENTORY' => append_sid('shop.'.$phpEx.'?action=inventory&amp;searchid=' . $userdata['user_id']),
		'U_SHOP_USERS' => append_sid('shop_users.'.$phpEx))
	);

	$template->assign_block_vars('', array());
}
elseif ( $_REQUEST['action'] == 'del_shop' )
{
	//
	//
	//
}

//
// Start output of page
//
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>
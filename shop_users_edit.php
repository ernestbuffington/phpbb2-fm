<?php
/** 
*
* @package phpBB
* @version $Id: shop_users_edit.php,v 1.0.0 2004/05/16 zarath Exp $
* @copyright (c) 2004 Zarath 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

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

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=shop_users_edit.".$phpEx); 
	exit; 
} 


//
// Register main global variable.
//
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) 
{ 
	$action = ( isset($HTTP_POST_VARS['action']) ) ? htmlspecialchars($HTTP_POST_VARS['action']) : htmlspecialchars($HTTP_GET_VARS['action']); 
}
else 
{ 
	$action = ''; 
}

if ( isset($HTTP_GET_VARS['sub_action']) || isset($HTTP_POST_VARS['sub_action']) ) 
{ 
	$sub_action = ( isset($HTTP_POST_VARS['sub_action']) ) ? htmlspecialchars($HTTP_POST_VARS['sub_action']) : htmlspecialchars($HTTP_GET_VARS['sub_action']); 
}
else 
{ 
	$sub_action = ''; 
}


//
// Check if shops are open or closed.
//
if ( !($board_config['u_shops_enabled']) )
{
	message_die(GENERAL_MESSAGE, $lang['User_shops_disabled']);
}

//
// Checks to make sure a user has a shop, if they don't... all follow actions should be invalid.
//
$sql = "SELECT *
	FROM " . TABLE_USER_SHOPS . "
	WHERE user_id = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user personal shop data.', '', __LINE__, __FILE__, $sql);
}

$sql_num_rows = $db->sql_numrows($result);

if ( $sql_num_rows == 0 && $action != 'create_shop' )
{
	$add = ( $board_config['u_shops_open_cost'] > 0 ) ? '<br /><br />' . sprintf($lang['Shop_cost_user'], $board_config['u_shops_open_cost'], $board_config['points_name']) : '';

	message_die(GENERAL_MESSAGE, $lang['No_shop_open'] . $add . '<br /><br />' . sprintf($lang['Click_open_shop'], '<a href="' . append_sid('shop_users_edit.'.$phpEx.'?action=create_shop') . '">', '</a>', $lang['Open']) . '<br /><br />' . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
}


//
// Default page
//
if ( empty($action) )
{
	if ( isset($HTTP_GET_VARS['edit_item']) || isset($HTTP_POST_VARS['edit_item']) ) 
	{ 
		$edit_item = ( isset($HTTP_POST_VARS['edit_item']) ) ? intval($HTTP_POST_VARS['edit_item']) : intval($HTTP_GET_VARS['edit_item']); 
	}
	else 
	{ 
		$edit_item = ''; 
	}

	$template->set_filenames(array(
		'body' => 'shop_users_config.tpl')
	);
	make_jumpbox('viewforum.'.$phpEx); 

	// Set config options...
	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user shop data.', '', __LINE__, __FILE__, $sql);
	}

	$status_1 = ( $row['shop_status'] == 0 ) ? 'selected="selected"' : '';
	$status_2 = ( $row['shop_status'] == 2 ) ? 'selected="selected"' : '';
	$status_3 = ( $row['shop_status'] == 1 ) ? 'selected="selected"' : '';

	if ( $row['amount_holding'] > 0 )
	{
		$template->assign_block_vars('switch_withdraw_holdings', array(
			'L_WITHDRAW_HOLDINGS' => $lang['Withdraw_holdings'],
			
			'WITHDRAW_URL' => append_sid('shop_users_edit.'.$phpEx.'?action=withdraw_holdings'))
		);
	}

	$template->assign_block_vars('switch_are_shops', array(
		'SHOP_NAME' => $row['shop_name'],
		'SHOP_TYPE' => $row['shop_type'],
		'STATUS_SELECT_1' => $status_1,
		'STATUS_SELECT_2' => $status_3,
		'STATUS_SELECT_3' => $status_2,

		'SHOP_OPENED' => create_date($board_config['default_dateformat'], $row['shop_opened'], $board_config['board_timezone']),
		'SHOP_EARNT' => $row['amount_earnt'],
		'SHOP_HOLDING' => $row['amount_holding'],
		'SHOP_ITEMS_LEFT' => $row['items_holding'],
		'SHOP_ITEMS_SOLD' => $row['items_sold'])
	);

	// Begin displaying shop items
	$sql = "SELECT a.*, b.name
		FROM " . TABLE_USER_SHOP_ITEMS . " a, " . SHOPITEMS_TABLE ." b
		WHERE a.shop_id = " . $row['id'] . "
			AND b.id = a.item_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user personal shop items.', '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( $sql_num_rows == 0 )
	{
		$template->assign_block_vars('switch_no_items', array(
			'L_NO_ITEMS' => $lang['None'])
		);
	}
	else
	{
		$template->assign_block_vars('switch_are_items', array());

		for ($i = 0; $i < $sql_num_rows; $i++)
		{
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain user personal shop items.', '', __LINE__, __FILE__, $sql);
			}
			
			if (file_exists($phpbb_root_path . 'images/shop/' . $row['name'] . '.jpg')) 
			{ 
				$itemfilext = 'jpg'; 
			}
			elseif (file_exists($phpbb_root_path . 'images/shop/' . $row['name'] . '.png')) 
			{ 
				$itemfilext = 'png'; 
			}
			else 
			{ 
				$itemfilext = 'gif'; 
			}

			if ( $row['id'] != $edit_item )
			{
				$template->assign_block_vars('list_items', array(
					'ROW_CLASS' => $row_class,
					'ITEM_FILEEXT' => $itemfilext,
					'ITEM_NAME' => $row['name'],
					'ITEM_NOTES' => $row['seller_notes'],
					'ITEM_COST' => $row['cost'] . ' ' . $board_config['points_name'],

					'EDIT_IMG' => $images['icon_edit'],
					'DELETE_IMG' => $images['icon_delpost'],

					'EDIT_URL' => append_sid('shop_users_edit.'.$phpEx.'?edit_item=' . $row['id']),
					'DELETE_URL' => append_sid('shop_users_edit.'.$phpEx.'?action=change_items&amp;sub_action=delete_item&amp;item_id=' . $row['id']))
				);
			}
			else
			{
				$template->assign_block_vars('switch_edit_item', array(
					'ITEM_FILEEXT' => $itemfilext,
					'ITEM_NAME' => $row['name'],
					'ITEM_NOTES' => $row['seller_notes'],
					'ITEM_COST' => $row['cost'],

					'UPDATE_URL' => append_sid('shop_users_edit.'.$phpEx.'?action=change_items&amp;sub_action=update_item&amp;item_id=' . $row['id']))
				);
			}
		}
	}

	// Begin displaying shop items to ADD
	// Arrange items to a variable that can be used in a IN
	$user_items = '\'' . substr(str_replace('Þ', "', '", str_replace('ß', '', addslashes($userdata['user_items']))), 0, -4) . '\'';

	$sql = "SELECT *
		FROM " . SHOPITEMS_TABLE . "
		WHERE name IN ($user_items)";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain users shop items.', '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( $sql_num_rows > 0 )
	{
		$template->assign_block_vars('switch_are_a_items', array());

		for ($i = 0; $i < $sql_num_rows; $i++)
		{
			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain users shop items.', '', __LINE__, __FILE__, $sql);
			}
			
			$template->assign_block_vars('list_add_items', array(
				'ITEM_NAME' => $row['name'],
				'ITEM_ID' => $row['id'])
			);
		}
	}

	if ( !empty($userdata['user_specmsg']) )
	{
		$template->assign_block_vars('switch_special_msg', array(
			'SPECIAL_MSGS' => $userdata['user_specmsg'],
			'SPECIAL_MSGS_URL' => append_sid('shop.'.$phpEx.'?clm=true'),

			'L_CLEAR' => $lang['Clear_Messages'])
		);
	} 

	$page_title = $lang['Personal_shop'];

	$template->assign_vars(array(
		'SHOPLOCATION' => $shoplocation,
		'SHOP_TRANS' => ( $board_config['shop_trans_enable'] ) ? '| <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>' : '',

		'L_EDIT_SHOP' => $page_title,
		'L_SHOP_OPENED' => $lang['Opened'],
		'L_SHOP_NAME' => $lang['Shop_Name'],
		'L_SHOP_TYPE' => $lang['Shop_Type'],
		'L_SHOP_STATUS' => $lang['Shop_status'],
		'L_OPENED' => $lang['Opened'],
		'L_CLOSED' => $lang['Closed'],
		'L_RESTOCKING' => $lang['Shop_Restocking'],			
		'L_POINTS_EARNT' => sprintf($lang['Points_earnt'], $board_config['points_name']),
		'L_POINTS_HOLDING' => sprintf($lang['Points_holding'], $board_config['points_name']),
		'L_ITEMS_LEFT' => $lang['Items'] . ' ' . $lang['Left'],
		'L_ITEMS_SOLD' => $lang['Items'] . ' ' . $lang['Sold'],
			
		'L_SHOP_ITEMS' => $lang['Personal_shop'] . ' ' . $lang['Items'],
		'L_ADD_ITEMS' => $lang['Add'] .  ' ' . $lang['Items'],
		'L_ICON' => $lang['Icon'],
		'L_ITEM' => $lang['Item'],
		'L_COST' => $lang['Cost'],
		'L_NOTES' => $lang['Notes'],
				
		'L_ACTION' => $lang['Action'],
		'L_EDIT' => $lang['Edit'],
		'L_DELETE' => $lang['Delete'],

		'U_SHOP_USER_CONFIG' => append_sid('shop_users_edit.'.$phpEx))
	);

	$template->assign_block_vars('', array());
}
else if ( $action == 'create_shop' )
{
	$sql = "SELECT *
		FROM " . TABLE_USER_SHOPS . "
		WHERE user_id = ". $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user personal shop data.', '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( $sql_num_rows > 0 ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Shop_open_already'] . '<br /><br />' . sprintf($lang['Click_return_configure_shop'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>')); 
	}
	else
	{
		//
		// Charge Users
		//
		if ( $board_config['u_shops_open_cost'] > 0 )
		{
			if ( $userdata['user_points'] < $board_config['u_shops_open_cost'] ) 
			{ 
				message_die(GENERAL_MESSAGE, sprintf($lang['Shop_open_no_points'], $board_config['points_name'], $board_config['u_shops_open_cost'], $board_config['points_name'])); 
			}
			else
			{
				$sql = "UPDATE " . USERS_TABLE . "
					SET user_points = user_points - " . $board_config['u_shops_open_cost'] . "
					WHERE user_id = " . $userdata['user_id'];
				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not subtract shop opening cost.', '', __LINE__, __FILE__, $sql);
				}
			}
		}

		$sql = "INSERT INTO " . TABLE_USER_SHOPS . " (user_id, username, shop_name, shop_type, shop_opened)
			VALUES(" . $userdata['user_id'] . ", '{$userdata['username']}', '{$userdata['username']}\'s Shop', 'Unknown', " . time() . ")";
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not insert new user shop.', '', __LINE__, __FILE__, $sql);
		}

		message_die(GENERAL_MESSAGE, $lang['Shop_open_success'] . '<br /><br />' . sprintf($lang['Click_return_configure_shop'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>'));
	}
}
elseif ( $action == 'update_config' )
{
	// Register globals for update!
	if ( isset($HTTP_GET_VARS['shop_name']) || isset($HTTP_POST_VARS['shop_name']) ) 
	{ 
		$shop_name = ( isset($HTTP_POST_VARS['shop_name']) ) ? htmlspecialchars($HTTP_POST_VARS['shop_name']) : htmlspecialchars($HTTP_GET_VARS['shop_name']); 
	}
	else 
	{ 
		$shop_name = ''; 
	}
	if ( isset($HTTP_GET_VARS['shop_type']) || isset($HTTP_POST_VARS['shop_type']) ) 
	{ 
		$shop_type = ( isset($HTTP_POST_VARS['shop_type']) ) ? htmlspecialchars($HTTP_POST_VARS['shop_type']) : htmlspecialchars($HTTP_GET_VARS['shop_type']); 
	}
	else 
	{ 
		$shop_type = ''; 
	}
	if ( isset($HTTP_GET_VARS['shop_status']) || isset($HTTP_POST_VARS['shop_status']) ) 
	{ 
		$shop_status = ( isset($HTTP_POST_VARS['shop_status']) ) ? intval($HTTP_POST_VARS['shop_status']) : intval($HTTP_GET_VARS['shop_status']); 
	}
	else 
	{ 
		$shop_status = ''; 
	}

	$shop_name = addslashes(stripslashes($shop_name));
	$shop_type = addslashes(stripslashes($shop_type));
	$shop_status = ( $shop_status > 2 || $shop_stats < 0 ) ? 1 : $shop_status;

	$sql = "UPDATE " . TABLE_USER_SHOPS . "
		SET shop_name = '$shop_name', shop_type = '$shop_type', shop_updated = " . time() . ", shop_status = " . $shop_status . "
		WHERE user_id = " . $userdata['user_id'];
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update user shop.', '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, $lang['Success_shop_updated'] . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>'));
}
elseif ( $action == 'change_items' )
{
	if ( isset($HTTP_GET_VARS['item_id']) || isset($HTTP_POST_VARS['item_id']) ) 
	{ 
		$item_id = ( isset($HTTP_POST_VARS['item_id']) ) ? intval($HTTP_POST_VARS['item_id']) : intval($HTTP_GET_VARS['item_id']); 
	}
	else 
	{ 
		$item_id = ''; 
	}

	if ( $sub_action == 'add_item' )
	{
		if ( isset($HTTP_GET_VARS['item_cost']) || isset($HTTP_POST_VARS['item_cost']) ) 
		{ 
			$item_cost = ( isset($HTTP_POST_VARS['item_cost']) ) ? intval($HTTP_POST_VARS['item_cost']) : intval($HTTP_GET_VARS['item_cost']); 
		}
		else 
		{ 
			$item_cost = ''; 
		}
		if ( isset($HTTP_GET_VARS['item_notes']) || isset($HTTP_POST_VARS['item_notes']) ) 
		{ 
			$item_notes = ( isset($HTTP_POST_VARS['item_notes']) ) ? htmlspecialchars($HTTP_POST_VARS['item_notes']) : htmlspecialchars($HTTP_GET_VARS['item_notes']); 
		}
		else 
		{ 
			$item_notes = ''; 
		}
		
		$item_notes = ( empty($item_notes) ) ? $lamg['None'] : addslashes(stripslashes($item_notes));
		
		if ( $item_cost < 1 ) 
		{ 
			$item_cost = 1; 
		}

		$user_items = '\'' . substr(str_replace('Þ', "', '", str_replace('ß', '', addslashes($userdata['user_items']))), 0, -4) . '\'';

		$sql = "SELECT a.name, a.id, b.id as shop_id
			FROM " . SHOPITEMS_TABLE . " a, " . TABLE_USER_SHOPS . " b
			WHERE a.id = '$item_id'
				AND b.user_id = " . $userdata['user_id'] . "
				AND a.name IN ($user_items)";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain user shop items.', '', __LINE__, __FILE__, $sql);
		}

		$sql_num_rows = $db->sql_numrows($result);

		if ( $sql_num_rows > 0 )
		{
			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain user shop items.', '', __LINE__, __FILE__, $sql);
			}

			//
			// If max limit is set, check amount
			//
			if ( $board_config['u_shops_max_items'] > 0 )
			{
				$sql = "SELECT *
					FROM " . TABLE_USER_SHOP_ITEMS . "
					WHERE shop_id = " . $row['shop_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain user shop items.', '', __LINE__, __FILE__, $sql);
				}
				if ( $db->sql_numrows($result) > $board_config['u_shops_max_items'] ) 
				{ 
					message_die(GENERAL_MESSAGE, $lang['Error_shop_full'] . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>'));
				}
			}

			$useritems = substr_replace($userdata['user_items'], '', strpos($userdata['user_items'], 'ß' . $row['name'] . 'Þ'), strlen('ß' . $row['name'] . 'Þ'));
			$useritems = addslashes($useritems);

			$sql = "UPDATE " . USERS_TABLE . "
				SET user_items = '$useritems'
				WHERE user_id = " . $userdata['user_id'];
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update users items.', '', __LINE__, __FILE__, $sql);
			}

			$sql = "INSERT INTO " . TABLE_USER_SHOP_ITEMS . " (shop_id, item_id, seller_notes, cost, time_added)
				VALUES(" . $row['shop_id'] . ", " . $row['id'] . ", '$item_notes', '$item_cost', " . time() . ")";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not insert user shop items.', '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . TABLE_USER_SHOPS . "
				SET items_holding = items_holding + 1,
					shop_updated = " . time() . "
				WHERE user_id = " . $userdata['user_id'];
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update user shop items.', '', __LINE__, __FILE__, $sql);
			}

			message_die(GENERAL_MESSAGE, $lang['Item_added'] . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>'));
		}
		else 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_Item_Invalid'] . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>'));
		}

	}
	elseif ( $sub_action == 'delete_item' )
	{
		$sql = "SELECT a.*, b.name
			FROM " . TABLE_USER_SHOP_ITEMS . " a, " . SHOPITEMS_TABLE . " b
			WHERE a.id = '$item_id'
				AND b.id = a.item_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain shop items.', '', __LINE__, __FILE__, $sql);
		}

		$sql_num_rows = $db->sql_numrows($result);

		if ( $sql_num_rows > 0 )
		{
			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain user shop items.', '', __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE
				FROM " . TABLE_USER_SHOP_ITEMS . "
				WHERE id = " . $item_id;
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not delete user item.', '', __LINE__, __FILE__, $sql);
			}

			$useritems = addslashes($userdata['user_items'] . 'ß' . $row['name'] . 'Þ');

			$sql = "UPDATE " . USERS_TABLE . "
				SET user_items = '$useritems'
				WHERE user_id = " . $userdata['user_id'];
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update user items.', '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . TABLE_USER_SHOPS . "
				SET items_holding = items_holding - 1
				WHERE user_id = " . $userdata['user_id'];
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update user items.', '', __LINE__, __FILE__, $sql);
			}

			message_die(GENERAL_MESSAGE, $lang['Item_returned'] . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>'));
		}
		else 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_Item_Invalid'] . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>'));
		}
	}
	elseif ( $sub_action == 'update_item' )
	{
		if ( isset($HTTP_GET_VARS['item_cost']) || isset($HTTP_POST_VARS['item_cost']) ) 
		{ 
			$item_cost = ( isset($HTTP_POST_VARS['item_cost']) ) ? intval($HTTP_POST_VARS['item_cost']) : intval($HTTP_GET_VARS['item_cost']); 
		}
		else 
		{ 
			$item_cost = ''; 
		}
		if ( isset($HTTP_GET_VARS['item_notes']) || isset($HTTP_POST_VARS['item_notes']) ) 
		{ 
			$item_notes = ( isset($HTTP_POST_VARS['item_notes']) ) ? htmlspecialchars($HTTP_POST_VARS['item_notes']) : htmlspecialchars($HTTP_GET_VARS['item_notes']);
		}
		else 
		{ 
			$item_notes = ''; 
		}
		
		$item_notes = ( empty($item_notes) ) ? $lang['None'] : addslashes(stripslashes($item_notes));
		
		if ( $item_cost < 1 ) 
		{ 
			$item_cost = 1; 
		}

		$sql = "SELECT a.*, b.name
			FROM " . TABLE_USER_SHOP_ITEMS . " a, " . SHOPITEMS_TABLE . " b
			WHERE a.id = '$item_id'
				AND b.id = a.item_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain user shop items.', '', __LINE__, __FILE__, $sql);
		}

		$sql_num_rows = $db->sql_numrows($result);

		if ( $sql_num_rows > 0 )
		{
			$sql = "UPDATE " . TABLE_USER_SHOP_ITEMS . "
				set cost = '$item_cost',
					seller_notes = '$item_notes'
				WHERE id = '$item_id'";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update user shop items.', '', __LINE__, __FILE__, $sql);
			}

			message_die(GENERAL_MESSAGE, $lang['Success_item_updated'] . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>'));
		}
		else 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_Item_Invalid'] . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>'));
		}
	}
}
elseif ( $action == 'withdraw_holdings' )
{
	$sql = "SELECT amount_holding
		FROM " . TABLE_USER_SHOPS . "
		WHERE user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user amount holding data.', '', __LINE__, __FILE__, $sql);
	}

	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user amount holding data.', '', __LINE__, __FILE__, $sql);
	}

	if ( $row['amount_holding'] < 1 ) 
	{ 
		message_die(GENERAL_MESSAGE, sprintf($lang['Error_no_withdraw'], $board_config['points_name']) . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>')); 
	}

	$sql = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points + " . $row['amount_holding'] . "
		WHERE user_id = " . $userdata['user_id'];
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update user points total.', '', __LINE__, __FILE__, $sql);
	}

	$sql = "UPDATE " . TABLE_USER_SHOPS . "
		SET amount_holding = 0,	shop_updated = " . time() . "
		WHERE user_id = " . $userdata['user_id'];
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update user shop data.', '', __LINE__, __FILE__, $sql);
	}

	message_die(GENERAL_MESSAGE, sprintf($lang['Success_withdraw'], $row['amount_holding'], $board_config['points_name']) . '<br /><br />' . sprintf($lang['US_click_return'], '<a href="' . append_sid('shop_users_edit.'.$phpEx) . '">', '</a>'));
}
else 
{ 
	message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Action']); 
}


//
// Generate the page
//
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>
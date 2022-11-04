<?php
/** 
*
* @package phpBB
* @version $Id: shop_users_view.php,v 1.0.0 2004/05/16 zarath Exp $
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
	
//
// Register main action variable!
//
if ( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) ) 
{ 
	$action = ( isset($HTTP_POST_VARS['action']) ) ? htmlspecialchars($HTTP_POST_VARS['action']) : htmlspecialchars($HTTP_GET_VARS['action']); 
}
else 
{ 
	$action = ''; 
}


//
// Check if shops are open or closed.
//
if ( !($board_config['u_shops_enabled']) )
{
	message_die(GENERAL_MESSAGE, $lang['User_shops_disabled']);
}


//
// Start of shop list page
//
if ( empty($action) )
{
	if ( isset($HTTP_GET_VARS['shop']) || isset($HTTP_POST_VARS['shop']) ) 
	{ 
		$shop = ( isset($HTTP_POST_VARS['shop']) ) ? intval($HTTP_POST_VARS['shop']) : intval($HTTP_GET_VARS['shop']); 
	}
	else 
	{ 
		$shop = ''; 
	}

	$template->set_filenames(array(
		'body' => 'shop_users_view.tpl')
	);
   	make_jumpbox('viewforum.'.$phpEx); 

	$sql = "SELECT *
		FROM " . TABLE_USER_SHOPS . "
		WHERE id = '" . $shop . "'
			AND shop_status != 1";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user shop data.', '', __LINE__, __FILE__, $sql);
	}

	if ( !($db->sql_numrows($result)) )
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Shop'] . '<br /><br />'  . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
	}

	$sql = "SELECT b.id AS items_id, c.name, c.sdesc, b.cost, b.seller_notes, a.shop_name, a.id AS shop_id
		FROM " . TABLE_USER_SHOPS . " a, " . TABLE_USER_SHOP_ITEMS . " b, " . SHOPITEMS_TABLE . " c
		WHERE a.id = " . $shop . "
			AND a.shop_status != 1
			AND b.shop_id = a.id
			AND c.id = b.item_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user shop data.', '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( $sql_num_rows == 0 )
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Item_None'] . '<br /><br />'  . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
	}

	$template->assign_block_vars('switch_main_list', array());

	for ($i = 0; $i < $sql_num_rows; $i++)
	{
		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain user shop data.', '', __LINE__, __FILE__, $sql);
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

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('list_items', array(
			'ROW_CLASS' => $row_class,
			'ITEM_FILEEXT' => $itemfilext,
			'ITEM_URL' => append_sid('shop_users_view.'.$phpEx.'?action=display_item&amp;item=' . $row['items_id']),
			'ITEM_NAME' => $row['name'],
			'ITEM_S_DESC' => $row['sdesc'],
			'ITEM_COST' => $row['cost'] . ' ' . $board_config['points_name'],
			'ITEM_NOTES' => ( $row['seller_notes'] ) ? $row['seller_notes'] : $lang['None'],
			
			'L_ITEM_INFO' => $lang['Item_Info_On'])
		);
	}

	$page_title = $row['shop_name'];
	$shoplocation = ' -> <a href="' . append_sid('shop.'.$phpEx) . '" class="nav">' . $lang['Shop'] . '</a> -> <a href="' . append_sid('shop_users.'.$phpEx) . '" class="nav">' . $lang['User_shops'] . '</a> -> <a href="' . append_sid('shop_users_view.'.$phpEx.'?shop=' . $row['shop_id']) . '" class="nav">' . $row['shop_name'] . '</a>';

	if ( !empty($userdata['user_specmsg']) )
	{
		$template->assign_block_vars('switch_special_msg', array(
			'SPECIAL_MSGS' => $userdata['user_specmsg'],
			'SPECIAL_MSGS_URL' => append_sid('shop.'.$phpEx.'?clm=true'),

			'L_CLEAR' => $lang['Clear_Messages'])
		);
	}

	$template->assign_vars(array(
		'SHOPLOCATION' => $shoplocation,
		'SHOP_TRANS' => ( $board_config['shop_trans_enable'] ) ? '| <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>' : '',

		'L_SHOP_TITLE' => $row['shop_name'] . ' ' . $lang['Inventory'],
		'L_ICON' => $lang['Icon'],
		'L_ITEM' => $lang['Item'],
		'L_COST' => $lang['Cost'],
		'L_INVENTORY' => $lang['Your_Inventory'],
			
		'U_INVENTORY' => append_sid('shop.'.$phpEx.'?action=inventory&amp;searchid=' . $userdata['user_id']))
	);
	
	$template->assign_block_vars('', array());
}
elseif ( $action == "display_item" ) 
{
	if ( isset($HTTP_GET_VARS['item']) || isset($HTTP_POST_VARS['item']) ) 
	{ 
		$item = ( isset($HTTP_POST_VARS['item']) ) ? intval($HTTP_POST_VARS['item']) : intval($HTTP_GET_VARS['item']); 
	}
	else 
	{ 
		$item = ''; 
	}

	$template->set_filenames(array(
		'body' => 'shop_users_view.tpl')
	);
   	make_jumpbox('viewforum.'.$phpEx); 

	$sql = "SELECT a.user_id, a.username, a.shop_name, a.shop_status, c.name, c.sdesc, c.ldesc, b.shop_id, b.cost, b.seller_notes
		FROM " . TABLE_USER_SHOPS . " a, " . TABLE_USER_SHOP_ITEMS . " b, " . SHOPITEMS_TABLE . " c
		WHERE a.id = b.shop_id
			AND a.shop_status <> 1
			AND b.id = '$item'
			AND c.id = b.item_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user shop data.', '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( $sql_num_rows == 0 )
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Exists'] . '<br /><br />' . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
	}

	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user shop data.', '', __LINE__, __FILE__, $sql);
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
	
	$amount_owned = ( substr_count($userdata['user_items'], 'ß' . $row['name'] . 'Þ') > 0 ) ? substr_count($userdata['user_items'], 'ß' . $row['name'] . 'Þ') : 0;

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('switch_item_info', array(
		'ROW_CLASS' => $row_class,
		'ITEM_URL' => append_sid('shop_users_view.'.$phpEx.'?action=buy_item&amp;item=' . $item),
		'ITEM_FILEEXT' => $itemfilext,
		'ITEM_NAME' => $row['name'],
		'ITEM_L_DESC' => $row['ldesc'],
		'ITEM_COST' => $row['cost'] . ' ' . $board_config['points_name'],
		'ITEM_NOTES' => ( $row['seller_notes'] ) ? $row['seller_notes'] : $lang['None'],
		'ITEM_OWNED' => $amount_owned)
	);
	
	$page_title = $lang['Item_Info_On'] . ' ' . ucfirst($row['name']);
	$shoplocation = ' -> <a href="'.append_sid('shop_users.'.$phpEx).'" class="nav">' . $lang['User_shops'] . '</a> -> <a href="' . append_sid('shop_users_view.'.$phpEx.'?shop=' . $row['shop_id']) . '" class="nav">' . $row['shop_name'] . '</a> -> <a href="' . append_sid('shop_users_view.'.$phpEx.'?action=display_item&amp;item=' . $item) . '" class="nav">' . $lang['Item_Info_On'] . ' ' . ucfirst($row['name']) . '</a>';

	if ( !empty($userdata['user_specmsg']) )
	{
		$template->assign_block_vars('switch_special_msg', array(
			'SPECIAL_MSGS' => $userdata['user_specmsg'],
			'SPECIAL_MSGS_URL' => append_sid('shop.php?clm=true'),

			'L_CLEAR' => $lang['Clear_Messages'])
		);
	}

	$template->assign_vars(array(
		'SHOPLOCATION' => $shoplocation,
		'SHOP_TRANS' => ( $board_config['shop_trans_enable'] ) ? '| <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>' : '',
		
		'L_TABLE_TITLE' => 'Item Display',
		'L_ICON' => $lang['Icon'],
		'L_ITEM' => $lang['Item'],
		'L_COST' => $lang['Cost'],
		'L_OWNED' => $lang['Owned'],
		'L_ACTION' => $lang['Action'],
		'L_INVENTORY' => $lang['Your_Inventory'],
	
		'U_INVENTORY' => append_sid('shop.'.$phpEx.'?action=inventory&amp;searchid=' . $userdata['user_id']))
	);
	
	$template->assign_block_vars('', array());
}
elseif ( $action == "buy_item" )
{
	if ( isset($HTTP_GET_VARS['item']) || isset($HTTP_POST_VARS['item']) ) 
	{ 
		$item = ( isset($HTTP_POST_VARS['item']) ) ? intval($HTTP_POST_VARS['item']) : intval($HTTP_GET_VARS['item']); 
	}
	else 
	{ 
		$item = ''; 
	}

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop_users_view.'.$phpEx.'&action=buy_item&item=' . $item;
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}
	
	// Make sure item is still in user's shop!
	$sql = "SELECT a.user_id, a.shop_status, a.username, a.shop_name, a.id, b.cost, c.name
		FROM " . TABLE_USER_SHOPS . " a, " . TABLE_USER_SHOP_ITEMS . " b, " . SHOPITEMS_TABLE . " c
		WHERE a.id = b.shop_id
			AND c.id = b.item_id
			AND b.id = '$item'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user shop items.', '', __LINE__, __FILE__, $sql);
	}

	$sql_num_rows = $db->sql_numrows($result);

	if ( !($sql_num_rows) )
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Exists'] . '<br /><br />' . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
	}
	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user shop items.', '', __LINE__, __FILE__, $sql);
	}

	
	// Check if shop is closed for restocking!
	if ( $row['shop_status'] == 2 )
	{
		message_die(GENERAL_MESSAGE, $lang['User_shop_restocking'] . '<br /><br />' . sprintf($lang['Click_return_shopname'], '<a href="' . append_sid('shop_users_view.'.$phpEx.'?shop=' . $row['id']) . '">', '</a>', $row['shop_name']) . '<br /><br />' . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
	}
	if ( $row['shop_status'] == 1 )
	{
		message_die(GENERAL_MESSAGE, $lang['User_shop_disabled'] . '<br /><br />' . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
	}

	// Check currency & if has item
	if ( !$board_config['multibuys'] ) 
	{
		if ( substr_count($userdata['user_items'],"ß" . $row['name'] . "Þ") > 0 )
		{
			message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Own'] . '<br /><br />' . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
		}
	}

	$item_limit = ( empty($board_config['shop_invlimit']) ) ? 0 : $board_config['shop_invlimit'];

	if ( (substr_count($userdata['user_items'],"ß") >= $item_limit) && ($item_limit != 0) )
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Invertory_Full'] . '<br /><br />' . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
	}

	if ( $userdata['user_points'] < $row['cost'] )
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Points'] . ' ' . $board_config['points_name'] . '<br /><br />' . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
	}

	// start of table updates
	$useritems = addslashes($userdata['user_items']. 'ß' . $row['name'] . 'Þ');

	// Code to update purchaser's new currency & items
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_points = user_points - " . $row['cost'] . ",
			user_items = '$useritems'
		WHERE user_id = " . $userdata['user_id'];
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update user points/item data.', '', __LINE__, __FILE__, $sql);
	}

	// Code to remove the item from the shop
	$sql = "DELETE
		FROM " . TABLE_USER_SHOP_ITEMS . "
		WHERE id = '$item'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not delete user shop item.', '', __LINE__, __FILE__, $sql);
	}

	// Check if $cost needs to be set back, if tax is set
	if ( $board_config['u_shops_tax_percent'] > 0 )
	{
		$cost = $row['cost'] - ($row['cost'] / 100 * $row['u_shops_tax_percent']);
	}
	else
	{
		$cost = $row['cost'];
	}
	
	// Code to update left items, items sold, holding amount, amount earnt!
	$sql = "UPDATE " . TABLE_USER_SHOPS . "
		SET items_sold = items_sold + 1,  items_holding = items_holding - 1, amount_holding = amount_holding + $cost, amount_earnt = amount_earnt + $cost
		WHERE user_id = " . $row['user_id'];
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update user shop data.', '', __LINE__, __FILE__, $sql);
	}

	if ( $board_config['shop_trans_enable'] ) 
	{ 
		$sql = "INSERT INTO " . SHOPTRANS_TABLE . " (shoptrans_date, trans_user, trans_item, trans_type, trans_total) 
			VALUES (" . time() . ", " . $userdata['user_id'] . ", '" . $row['name'] . "', 'Bought (Shop: " . phpbb_clean_username($row['shop_name']) . ")', '" . $row['cost'] . "')";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert Buy data into transaction table.', '', __LINE__, __FILE__, $sql);
		}
	}

	message_die(GENERAL_MESSAGE, $lang['Bought'] . ' ' . $row['name'] . ' ' . $lang['For'] . ' ' . $row['cost'] . ' ' . $board_config['points_name'] . ', ' . $lang['shop_from'] . ' ' . $row['shop_name'] . ' ' . $lang['Owned_by'] . ' ' . $row['username'] . '<br /><br />' . sprintf($lang['Click_return_shopname'], '<a href="' . append_sid('shop_users_view.'.$phpEx.'?shop=' . $row['id']) . '">', '</a>', $row['shop_name']) . '<br /><br />' . sprintf($lang['Click_return_user_shops'], '<a href="' . append_sid('shop_users.'.$phpEx) . '">', '</a>'));
}
else 
{
	message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Action']); 
}

//
// Start output of page
//
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

//
// Generate the page
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.' . $phpEx);

?>
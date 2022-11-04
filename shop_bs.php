<?php
/** 
*
* @package phpBB2
* @version $Id: shop_bs.php,v 2.6.0 2002 zarath Exp $
* @copyright (c) 2002 Zarath
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.' . $phpEx);

//
// Include language files
//
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
// Buy page
//
if ($_REQUEST['action'] == 'buy') 
{
	if (!isset($_REQUEST['item'])) 
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invaid_Item_Chosen']);
	}
	
	$template->set_filenames(array(
		'body' => 'shop_body.tpl')
	);
    make_jumpbox('viewforum.'.$phpEx); 

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop.'.$phpEx.'&action=buy&item=' . $_REQUEST['item'];
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}
	
	// Make sure item exists
	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE id = '{$_REQUEST['item']}'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query shop items data.', '', __LINE__, __FILE__, $sql);
	}
	$row = mysql_fetch_array($result);
	
	if (!isset($row['shop'])) 
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Exists']);
	}
	elseif ($row['stock'] < 1) 
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Stock']);
	}
	
	$checkshop = addslashes($row['shop']);
	
	$sql = "SELECT * 
		FROM " . SHOPS_TABLE . " 
		WHERE shopname = '$checkshop' 
			AND shoptype != 'special' 
			AND shoptype != 'admin_only'";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not query special shop data.', '', __LINE__, __FILE__, $sql);
	}
	
	if (mysql_num_rows($result) < 1)  
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Protected']); 
	}
	// End check on item exists
		
	// Check currency & if user has item
	if ( !$board_config['multibuys'] ) 
	{
		if (substr_count($userdata['user_items'], 'ß' . $row['name'] . 'Þ') > 0)
		{
			message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Own']);
		}
	}
	
	if ((substr_count($userdata['user_items'], 'ß') >= $board_config['shop_invlimit']) && ($board_config['shop_invlimit'] != 0))
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Invertory_Full']);
	}
	
	if ($userdata['user_points'] < $row['cost'])
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Points'] . $board_config['points_name']);
	}
	// End of check for currency and if user has item
	
	// Start of table updates
	$leftamount = round($userdata['user_points'] - $row['cost']);
	$useritems = $userdata['user_items'] . 'ß' . $row['name'] . 'Þ';
	$newstock = --$row['stock'];
	$newsold = ++$row['sold'];
	
	$sql = "UPDATE " . USERS_TABLE . " 
		SET user_points = $leftamount, user_items = '$useritems' 
		WHERE username = '" . phpbb_clean_username($userdata['username']) . "'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update user points and items data.', '', __LINE__, __FILE__, $sql);
	}

	if ( $board_config['shop_trans_enable'] ) 
	{ 
		$sql = "INSERT INTO " . SHOPTRANS_TABLE . " (shoptrans_date, trans_user, trans_item, trans_type, trans_total) 
			VALUES (" . time() . ", " .$userdata['user_id'] . ", '" . $row['name'] . "', 'Bought', '" . $row['cost'] . "')";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert Buy data into transaction table.', '', __LINE__, __FILE__, $sql);
		}
	}
	
	$sql = "UPDATE " . SHOPITEMS_TABLE . " 
		SET stock = '$newstock', sold = '$newsold' 
		WHERE id = '{$_REQUEST['item']}'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update item stock data.', '', __LINE__, __FILE__, $sql);
	}
	// End of table updates
		
	$useritemamount = substr_count($userdata['user_items'], 'ß' . $row['name'] . 'Þ') + 1;

	$page_title = $lang['Buy'] . ' ' . $row['name'];
	$shoptablerows = 5;
	
	if ( $board_config['u_shops_enabled'] )
	{
		$user_shops = '<a href="' . append_sid('shop_users.'.$phpEx) . '" class="nav">' . $lang['User_shops'] . '</a> -> '; 
		$shop_trans = ' | <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>';
	}
	
	$shoplocation = ' -> <a href="'.append_sid("shop.$phpEx").'" class="nav">' . $lang['Shop'] . '</a> -> ' . $user_shops . '<a href="'.append_sid("shop_inventory.$phpEx?action=shoplist&amp;shop=" . $row['id']) . '" class="nav">' . ucwords($row['shop']) . ' ' . $lang['Inventory'] . '</a> -> <a href="'.append_sid("shop_inventory.$phpEx?action=displayitem&amp;item=" . $row['id']) . '" class="nav">' . ucwords($row['name']) . ' ' . $lang['Information'] . '</a>';

	// Start of echoes
	$shopaction = '<tr>
		<th class="thHead" colspan="' . $shoptablerows . '">' . $lang['Information'] . '</th>
	</tr><tr>
		<td colspan="' . $shoptablerows . '" class="row1" height="30" align="center"><span class="gen">' . $lang['Bought'] . ' ' . ucwords($row['name']) . ' ' . $lang['For'] . ' ' . $row['cost'] . ' ' . $board_config['points_name'] . $lang['Leave_With'] . ' ' . $leftamount . ' ' . $board_config['points_name'] . '.</span></td>
	</tr>';	
	
	$shopinforow = '<tr>
		<td class="catLeft" align="center"><span class="cattitle">&nbsp;' . $lang['Icon'] . '&nbsp;</span></td>
		<td class="cat" align="center"><span class="cattitle">&nbsp;' . $lang['Item_Name'] . '&nbsp;</span></td>
		<td width="50" class="cat" align="center" nowrap="nowrap"><span class="cattitle">&nbsp;' . $lang['Cost'] . '&nbsp;</span></td>
		<td width="50" class="cat" align="center"><span class="cattitle">&nbsp;' . $lang['Stock'] . '&nbsp;</span></td>
		<td width="50" class="catRight" align="center"><span class="cattitle">&nbsp;' . $lang['Owned'] . '&nbsp;</span></td>
	</tr>';

	if (file_exists('images/shop/' . $row['name'] . '.jpg')) 
	{ 
		$itemfilext = 'jpg'; 
	}
	else 
	{ 
		$itemfilext = 'gif'; 
	}
	
	$shopitems = '<tr>
		<td class="row1" align="center"><img src="images/shop/' . $row['name'] .'.' . $itemfilext . '" title="' . ucfirst($row['name']) . '" alt="' . ucfirst($row['name']) . '" title="' . ucfirst($row['name']) . '" /></td>
		<td class="row1" width="100%"><span class="forumlink">' . ucwords($row['name']) . '</span><br />' . ucfirst($row['ldesc']) . '</td>
		<td class="row2" align="center" nowrap="nowrap">' . $row['cost'] . ' ' . $board_config['points_name'] . '</td>
		<td class="row1" align="center" nowrap="nowrap">' . $row['stock'] . '</td>
		<td class="row2" align="center" nowrap="nowrap">' . $useritemamount . '</td>
	</tr>';	
	// End of echoes
	
	$srow = mysql_fetch_array($result);

	// Start of personal information
	$personal = '<tr><td colspan="' . $shoptablerows . '" class="catBottom" align="center"><span class="nav"><a href="' . append_sid('shop.'.$phpEx.'?action=inventory&amp;searchid=' . $userdata['user_id']) . '" class="nav">' . $lang['Your_Inventory'] . '</a>' . $shop_trans . '</span></td></tr>'; 
	if (strlen($userdata['user_specmsg']) > 2) 
	{ 
		$personal .= '</table><br /><table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><tr><th class="thHead">' . $lang['Information'] . '</th></tr>'; 
		$personal .= '<tr><td class="row1" height="50" align="center"><span class="gen">' . $userdata['user_specmsg'] . '</span></td></tr>'; 
		$personal .= '<tr><td class="row1" colspan="2" align="center">[ <a href="' . append_sid('shop.'.$phpEx.'?clm=true') . '">' . $lang['Clear_Messages'] . '</a> ]</td></tr>';
	}
	// End of personal information

	$template->assign_vars(array(
		'SHOPPERSONAL' => $personal,
		'SHOPLOCATION' => $shoplocation,
		'SHOPACTION' => $shopaction,
		'L_SHOP_TITLE' => $page_title,
		'SHOPTABLEROWS' => $shoptablerows,
		'SHOPLIST' => $shopitems,
		'SHOPINFOROW' => $shopinforow)
	);
	
	$template->assign_block_vars('', array());
}
//
// Sell page
//
elseif ($_REQUEST['action'] == 'sell') 
{
	if (!isset($_REQUEST['item'])) 
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Chosen']);
	}
	
	$template->set_filenames(array(
		'body' => 'shop_body.tpl')
	);
	make_jumpbox('viewforum.'.$phpEx); 

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop.'.$phpEx.'&action=sell&item=' . $_REQUEST['item'];
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}

	// Make sure item exists
	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE id = '{$_REQUEST['item']}'";
		if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query shop item data.', '', __LINE__, __FILE__, $sql);
	}
	$row = mysql_fetch_array($result);
	
	if (mysql_num_rows($result) < 1) 
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Exists']);
	}
	
	$sql = "SELECT * 
		FROM " . SHOPS_TABLE . " 
		WHERE shopname = '" . addslashes($row['shop']) . "' 
			AND shoptype != 'special' 
			AND shoptype != 'admin_only'";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not query special shop data.', '', __LINE__, __FILE__, $sql);
	}
	
	// Check if item exists
	if (mysql_num_rows($result) < 1) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Sell_Item_Protected']); 
	}

	// Check for item
	if (substr_count($userdata['user_items'], 'ß' . $row['name'] . 'Þ') < 1)
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Own_No']);
	}

	// Start of table updates
	$plusamount = round($row['cost'] / 100 * $board_config['sellrate']);
	$leftamount = $userdata['user_points'] + $plusamount;
	$useritems = substr_replace($userdata['user_items'], "", strpos($userdata['user_items'], "ß".$row['name']."Þ"), strlen("ß".$row['name']."Þ"));
	$newstock = ++$row['stock'];
	$newsold = --$row['sold'];
	
	$sql = "UPDATE " . USERS_TABLE . " 
		SET user_points = $leftamount, user_items = '$useritems' 
		WHERE username = '" . phpbb_clean_username($userdata['username']) . "'";
	if ( !($db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update user points and items data.', '', __LINE__, __FILE__, $sql);
	}

	if ( $board_config['shop_trans_enable'] ) 
	{ 
		$sql = "INSERT INTO " . SHOPTRANS_TABLE . " (shoptrans_date, trans_user, trans_item, trans_type, trans_total) 
			VALUES (" . time() . ", " . $userdata['user_id'] . ", '" . $row['name'] . "', 'Sold', '$plusamount')";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert sold data into transaction table.', '', __LINE__, __FILE__, $sql);
		}
	}
	
	$sql = "UPDATE " . SHOPITEMS_TABLE . " 
		SET stock = '$newstock', sold = '$newsold' 
		WHERE name = '$item'";
	if( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not update item stock data.', '', __LINE__, __FILE__, $sql);
	}
	
	$useritemamount = substr_count($userdata['user_items'], 'ß' . $row['name'] . 'Þ') -1;

	$page_title = $lang['Sell'] . ' ' . $row['name'];
	$shoptablerows = 5;
	
	if ( $board_config['u_shops_enabled'] )
	{
		$user_shops = '<a href="' . append_sid('shop_users.'.$phpEx) . '" class="nav">' . $lang['User_shops'] . '</a> -> '; 
		$shop_trans = ' | <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>';
	}

	$shoplocation = ' -> <a href="'.append_sid("shop.$phpEx").'" class="nav">' . $lang['Shop'] . '</a> -> ' . $user_shops . '<a href="'.append_sid("shop_inventory.$phpEx?action=shoplist&amp;shop=" . $row['id']) . '" class="nav">' . ucwords($row['shop']) . ' ' . $lang['Inventory'] . '</a> -> <a href="'.append_sid("shop_inventory.$phpEx?action=displayitem&amp;item=" . $row['id']) . '" class="nav">' . ucwords($row['name']) . ' ' . $lang['Information'] . '</a>';

	// Start of echoes
	$shopaction = '<tr>
		<th class="thHead" colspan="' . $shoptablerows . '">' . $lang['Information'] . '</th>
	</tr><tr>
		<td colspan="' . $shoptablerows . '" class="row1" height="30" align="center"><span class="gen">' . $lang['You_Sold'] . ' ' . ucwords($row['name']) . ' ' . $lang['For'] . ' ' . $plusamount . ' ' . $board_config['points_name'] . $lang['Leave_With'] . ' ' . $leftamount . ' ' . $board_config['points_name'] . '.</span></td>
	</tr>';	
	
	$shopinforow = '<tr>
		<td class="catLeft" align="center"><span class="cattitle">&nbsp;' . $lang['Icon'] . '&nbsp;</span></td>
		<td class="cat" align="center"><span class="cattitle">&nbsp;' . $lang['Item_Name'] . '&nbsp;</span></td>
		<td width="50" class="cat" align="center" nowrap="nowrap"><span class="cattitle">&nbsp;' . $lang['Cost'] . '&nbsp;</span></td>
		<td width="50" class="cat" align="center"><span class="cattitle">&nbsp;' . $lang['Stock'] . '&nbsp;</span></td>
		<td width="50" class="catRight" align="center"><span class="cattitle">&nbsp;' . $lang['Owned'] . '&nbsp;</span></td>
	</tr>';

	if (file_exists('images/shop/' . $row['name'] . '.jpg')) 
	{ 
		$itemfilext = 'jpg'; 
	}
	else 
	{ 
		$itemfilext = 'gif'; 
	}
	$shopitems = '<tr>
		<td class="row1" align="center"><img src="images/shop/' . $row['name'] . '.' . $itemfilext . '" title="' . ucfirst($row['name']) . '" alt="' . ucfirst($row['name']) . '" /></td>
		<td class="row1" width="100%"><span class="forumlink">' . ucwords($row['name']) . '</span><br />' . ucfirst($row['ldesc']) . '</td>
		<td class="row1" align="center" nowrap="nowrap">' . $row['cost'] . ' ' . $board_config['points_name'] . '</td>
		<td class="row2" align="center" nowrap="nowrap">' . $row['stock'] . '</td>
		<td class="row1" align="center" nowrap="nowrap">' . $useritemamount . '</td>
	</tr>';
	// End of echoes

	$srow = mysql_fetch_array($result);

	// Start of personal information
	$personal = '<tr><td colspan="' . $shoptablerows . '" class="catBottom" align="center"><span class="nav"><a href="' . append_sid('shop.'.$phpEx.'?action=inventory&amp;searchid=' . $userdata['user_id']) . '" class="nav">' . $lang['Your_Inventory'] . '</a>' . $shop_trans . '</span></td></tr>'; 
	if (strlen($userdata['user_specmsg']) > 2) 
	{ 
		$personal .= '</table><br /><table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><tr><th class="thHead">' . $lang['Information'] . '</th></tr>'; 
		$personal .= '<tr><td class="row1" height="50" align="center"><span class="gen">' . $userdata['user_specmsg'] . '</span></td></tr>'; 
		$personal .= '<tr><td class="row1" colspan="2" align="center">[ <a href="' . append_sid('shop.'.$phpEx.'?clm=true') . '">' . $lang['Clear_Messages'] . '</a> ]</td></tr>';
	}
	// End of personal information

	$template->assign_vars(array(
		'SHOPPERSONAL' => $personal,
		'SHOPLOCATION' => $shoplocation,
		'SHOPACTION' => $shopaction,
		'L_SHOP_TITLE' => $page_title,
		'SHOPTABLEROWS' => $shoptablerows,
		'SHOPLIST' => $shopitems,
		'SHOPINFOROW' => $shopinforow)
	);
	
	$template->assign_block_vars('', array());
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
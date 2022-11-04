<?php
/** 
*
* @package admin
* @version $Id: admin_shop_config.php,v 1.2.6.0 2002/12/11 17:49:33 zarath Exp $
* @copyright (c) 2002 Zarath Technologies
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);

if(	!empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['Points_sys_settings']['Shop_config'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);

//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_shop.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_shop.' . $phpEx);


// 
// Main page
//
if (empty($_REQUEST['action']))
{
	$template->set_filenames(array(
		'body' => 'admin/shop_config_body.tpl')
	);

	$sql = "SELECT * 
		FROM " . SHOPS_TABLE . " 
		ORDER BY id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query shop info from database', '', __LINE__, __FILE__, $sql);
	}
	
	$shops = '<select name="shopid">';
	for ($x = 0; $x < mysql_num_rows($result); $x++)
	{
		$row = mysql_fetch_array($result);
		$shops .= '<option value="' . $row['id'] . '">' . $row['shopname'] . '</option>';
	}
	$shops .= '</select>';

	if ( $board_config['multibuys'] ) { $multibuys_yes = ' checked="checked"'; } else { $multibuys_no = ' checked="checked"'; }
	if ( $board_config['restocks'] ) { $restocks_yes = ' checked="checked"'; } else {  $restocks_no = ' checked="checked"'; }
	if ( $board_config['shop_give'] ) { $shopgive_yes = ' checked="checked"'; } else { $shopgive_no = ' checked="checked"'; }
	if ( $board_config['shop_trade'] ) { $shoptrade_yes = ' checked="checked"'; } else { $shoptrade_no = ' checked="checked"'; }
	
	if ($board_config['viewprofile'] == 'images') 
	{ 
		$viewprofile1 = 'images'; 
		$viewprofile2 = 'link'; 
	}
	else 
	{ 
		$viewprofile1 = 'link'; 
		$viewprofile2 = 'images'; 
	}
	
	if ($board_config['viewinventory'] == 'grouped') 
	{ 
		$viewinventory1 = 'grouped'; 
		$viewinventory2 = 'normal'; 
	}
	else 
	{ 
		$viewinventory1 = 'normal'; 
		$viewinventory2 = 'grouped'; 
	}
	
	
	if ($board_config['shop_orderby'] == 'name') 
	{ 
		$orderby1 = 'name'; 
		$orderby2 = 'cost'; 
		$orderby3 = 'id'; 
	}
	elseif ($board_config['shop_orderby'] == 'cost') 
	{ 
		$orderby1 = 'cost'; 
		$orderby2 = 'id'; 
		$orderby3 = 'name'; 
	}
	else 
	{ 
		$orderby1 = 'id'; 
		$orderby2 = 'name'; 
		$orderby3 = 'cost'; 
	}
	
	if ( $board_config['u_shops_enabled'] ) { $user_shops_yes = ' checked="checked"'; } else { $user_shops_no = ' checked="checked"'; }
	$tax_percent = ( $board_config['u_shops_tax_percent'] < 0 || $board_config['u_shops_tax_percent'] > 100 ) ? 1 : $board_config['u_shops_tax_percent'];
	$max_items = ( $board_config['u_shops_max_items'] < 0 ) ? 100 : $board_config['u_shops_max_items'];
	$open_cost = ( $board_config['u_shops_open_cost'] < 0 ) ? 0 : $board_config['u_shops_open_cost'];
	if ( $board_config['shop_trans_enable'] ) { $shop_trans_yes = ' checked="checked"'; } else { $shop_trans_no = ' checked="checked"'; }

	$viewtopic_none = ( !$board_config['viewtopic'] ) ? ' checked="checked"' : '';
	$viewtopic_link = ( $board_config['viewtopic'] == 1 ) ? ' checked="checked"' : '';
	$viewtopic_images = ( $board_config['viewtopic'] == 2 ) ? ' checked="checked"' : '';
	
	$shopinfo = '<tr>
		<th colspan="2" class="thHead">' . $lang['Shop'] . ' ' . $lang['Settings'] . ' </th>
	</tr><tr>
		<td width="50%" class="row1"><b>' . $lang['Multiple_Item_Buy'] . ':</b></td>
		<td class="row2"><input type="radio" value="1" name="multiitems"' . $multibuys_yes . '> ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" value="0" name="multiitems"' . $multibuys_no . '> ' . $lang['No'] . '</td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Shop_Item_Order'] . ':</b></td>
		<td class="row2"><select name="orderby"><option selected value="' . $orderby1 . '">' . ucfirst($orderby1) . '</option><option value="' . $orderby2 . '">' . ucfirst($orderby2) . '</option><option value="' . $orderby3  . '">' . ucfirst($orderby3) . '</option></select></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Shop_Restocking'] . ':</b></td>
		<td class="row2"><input type="radio" value="1" name="shoprestock"' . $restocks_yes . '> ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" value="0" name="shoprestock"' . $restocks_no . '> ' . $lang['No'] . '</td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Selling_Rate'] . ':</b><br /><span class="gensmall">' . $lang['In_Percent'] . '</span></td>
		<td class="row2"><input class="post" name="sellrate" type="text" size="5" maxlength="3" value="' . $board_config['sellrate'] . '"> %</td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Inventory_Limit'] . ':</b><br /><span class="gensmall">' . $lang['Inventory_Limit_Explain'] . '</span></td>
		<td class="row2"><input class="post" name="invlimit" type="text" size="5" maxlength="3" value="' . $board_config['shop_invlimit'] . '"></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Viewtopic_Display'] . ':</b></td>
		<td class="row2"><input class="post" name="topicdisplaynum" type="text" size="5" maxlength="3" value="' . $board_config['viewtopiclimit'] . '"></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Viewtopic_Type'] . ':</b></td>
		<td class="row2">
	<input type="radio" value="0" name="viewtopic"' . $viewtopic_none . '> ' . $lang['None'] . '&nbsp;&nbsp;<input type="radio" value="1" name="viewtopic"' . $viewtopic_link . '> ' . $lang['Link'] . '&nbsp;&nbsp;<input type="radio" value="2" name="viewtopic"' . $viewtopic_images . '> ' . $lang['Images'] . '</td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Profile_Display'] . ':</b></td>
		<td class="row2"><select name="profiledisplay"><option selected value="' . $viewprofile1 . '">' . ucfirst($viewprofile1) . '</option><option value="' . $viewprofile2 . '">' . ucfirst($viewprofile2) . '</option></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Inventory'] . ' ' . $lang['Type'] . ':</b></td>
		<td class="row2"><select name="inventorytype"><option selected value="' . $viewinventory1 . '">' . ucfirst($viewinventory1) . '</option><option value="' . $viewinventory2 . '">' . ucfirst($viewinventory2) . '</option></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Give_Ability'] . ':</b></td>
		<td class="row2"><input type="radio" value="1" name="shopgive"' . $shopgive_yes . '> ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" value="0" name="shopgive"' . $shopgive_no . '> ' . $lang['No'] . '</td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Trade_Ability'] . ':</b></td>
		<td class="row2"><input type="radio" value="1" name="shoptrade"' . $shoptrade_yes . '> ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" value="0" name="shoptrade"' . $shoptrade_no . '> ' . $lang['No'] . '</td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Enable_Shop_Trans'] . ':</b></td>
		<td class="row2"><input type="radio" value="1" name="shop_trans"' . $shop_trans_yes . '> ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" value="0" name="shop_trans"' . $shop_trans_no . '> ' . $lang['No'] . '</td>
	</tr><tr>
		<th colspan="2" class="thHead">' . $lang['US_title'] . ' ' . $lang['Configuration'] . ' </th>
	</tr><tr>
		<td class="row1"><b>' . $lang['Enable_personal_shops'] . ':</b></td>
		<td class="row2"><input type="radio" value="1" name="status"' . $user_shops_yes . '> ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" value="0" name="status"' . $user_shops_no . '> ' . $lang['No'] . '</td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Percent_on_sales'] . ':</b></td>
		<td class="row2"><input type="text" name="tax_percent" value="' . $tax_percent . '" size="5" maxlength="3" class="post" /> %</td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Max_items_per_shop'] . ':</b></td>
		<td class="row2"><input type="text" name="max_items" value="' . $max_items . '" size="5" maxlength="10" class="post" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Open_shop_cost'] . ':</b></td>
		<td class="row2"><input type="text" name="open_cost" value="' . $open_cost . '" size="5" maxlength="15" class="post" /> ' . $board_config['points_name'] . '</td>
	</tr><tr>
			<td class="catBottom" colspan="2" align="center"><input type="hidden" name="action" value="updateglobals"><input class="mainoption" type="submit" value="' . $lang['Submit'] . '">&nbsp;&nbsp;<input class="liteoption" type="reset" value="' . $lang['Reset'] . '"></td>
	</form></tr></table><br />
	<table width="100%" align="center" cellspacing="1" cellpadding="3" class="forumline">
	<tr>
		<th class="thSides">' . $lang['Edit_User_Inventory'] . '</th>
	</tr><tr>
		<form method="post" action="'.append_sid("admin_shop_config.$phpEx").'" name="post">
		<input type="hidden" name="action" value="editinventory">
		<td align="center" class="row1"><b>' . $lang['Username'] . ':</b> <input class="post" type="text" class="post" name="username"' . (( $board_config['AJAXed_user_list'] ) ? ' onkeyup="ub(this.value);" autocomplete="off"' : '') . ' maxlength="50" size="20"><input type="hidden" name="action" value="editinventory"> &nbsp;<input class="liteoption" type="submit" value="' . $lang['Edit'] . ' ' . $lang['Inventory'] . '">&nbsp;&nbsp;<input type="submit" name="usersubmit" value="' . $lang['Find_username'] . '" class="liteoption" onClick="window.open(\'./../search.php?mode=searchuser\', \'_phpbbsearch\', \'HEIGHT=250,resizable=yes,WIDTH=400\');return false;" />' . (( $board_config['AJAXed_user_list'] ) ? '<br /><div id="user_list" style="position:absolute"></div>' : '') . '</td>
	</form></tr></table><br />
	<table width="100%" align="center" cellspacing="1" cellpadding="3" class="forumline"><tr>
		<th colspan="2" class="thSides">' . $lang['Shop_Modify'] . '</th>
	</tr><tr>
		<form method="post" action="'.append_sid("admin_shop_config.$phpEx").'">
		<td class="row1" width="50%"><b>' . $lang['Shop_Modify'] . ':</b> &nbsp; ' . $shops . '</td>
		<td class="row2"><input type="hidden" name="action" value="editshop"><input class="liteoption" type="submit" value="' . $lang['Edit'] . ' ' . $lang['Shop'] . '"></span></td>
	</form></tr><tr>
		<form method="post" action="'.append_sid("admin_shop_config.$phpEx").'">
		<td class="row1"><b style="color: #' . $theme['fontcolor3'] . '">' . $lang['Effects'] . ' ' . $lang['Shop'] . ':</b></td>
		<td class="row2"><input type="hidden" name="action" value="editspecialshop"><input class="liteoption" type="submit" value="' . $lang['Edit'] . ' ' . $lang['Effects'] . ' ' . $lang['Shop'] . '"></span></td>
	</form></tr>
		<form method="post" action="'.append_sid("admin_shop_config.$phpEx").'"><tr>
		<th colspan="2" class="thSides">' . $lang['Shop_Add_New'] . ' </th>
	</tr><tr>
		<td class="row1"><b>' . $lang['Shop_Name'] . ':</b></td>
		<td class="row2"><input class="post" type="text" name="shopname" size="32" maxlength="32"></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Shop_Type'] . ':</b></td>
		<td class="row2"><input class="post" type="text" name="shoptype" size="32" maxlength="32"></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Restock_Time'] . ':</b><br /><span class="gensmall">' . $lang['Restock_Explain'] . '</span></td>
		<td class="row2"><input class="post" type="text" name="restocktime" size="5" maxlength="5"></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Restock_Amount'] . ':</b></td>
		<td class="row2"><input class="post" type="text" name="restockamount" size="5" maxlength="5"></td>
	</tr><tr>
		<td class="catBottom" colspan="2" align="center"><input type="hidden" name="action" value="createshop"><input class="mainoption" type="submit" value="' . $lang['Submit'] . '">&nbsp;&nbsp;<input class="liteoption" type="reset" value="' . $lang['Reset'] . '"></td>
	</tr>';
	
	$template->assign_vars(array(
		'SHOPCONFIGINFO' => "$shopinfo",
		'S_CONFIG_ACTION' => append_sid('admin_shop_config.'.$phpEx),
		'SHOPTITLE' => $lang['Shop_Editor'],
		'SHOPEXPLAIN' => $lang['Shop_Explain'])
	);	
}		
elseif ($_REQUEST['action'] == 'createshop')
{
	if ((strlen($_REQUEST['shopname']) < 4) || (strlen($_REQUEST['shoptype']) < 4) || (strlen($_REQUEST['shopname']) > 32) || (strlen($_REQUEST['shoptype']) > 32)) 
	{
		message_die(GENERAL_ERROR, $lang['Error_Incorrect']);
	}
	else
	{
		if (strtolower($_REQUEST['shoptype']) == 'special') 
		{ 
			message_die(GENERAL_ERROR, $lang['Error_Special_Invalid']); 
		}

		$sql = "SELECT * 
			FROM " . SHOPS_TABLE . " 
			WHERE shopname = '" . $_REQUEST['shopname'] . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain shop information', '', __LINE__, __FILE__, $sql);
		}
		$row = mysql_fetch_array($result);

		if (!is_null($row['shopname']))
		{
			message_die(GENERAL_ERROR, $lang['Error_Shop_Exists']);
		}
		
		if ((!is_numeric($_REQUEST['restocktime'])) || (strlen($_REQUEST['restocktime']) > 20))
		{
			$restocktime = 86400;
		}
		else 
		{ 
			$restocktime = $_REQUEST['restocktime']; 
		}
		if ((!is_numeric($_REQUEST['restockamount'])) || (strlen($_REQUEST['restockamount']) > 4))
		{
			$restockamount = 5;
		}
		else 
		{ 
			$restockamount = $_REQUEST['restockamount']; 
		}

		$sql = "INSERT INTO " . SHOPS_TABLE . " (shopname, shoptype, restocktime, restockamount) 
			VALUES ('" . $_REQUEST['shopname'] . "', '" . $_REQUEST['shoptype'] . "', '$restocktime', '$restockamount')";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not insert data into shop table', '', __LINE__, __FILE__, $sql);
		}

		$message = $lang['Success_shop_added'] . "<br /><br />" . sprintf($lang['Click_return_config'], '<a href=' . append_sid("admin_shop_config.$phpEx") . '>', '</a>')."<br /><br />".sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>') . "<br /><br />";
	
		message_die(GENERAL_MESSAGE, $message);
	}
}
elseif ($_REQUEST['action'] == 'updateshop')
{
	if ((strlen($_REQUEST['name']) < 4) || (strlen($_REQUEST['shoptype']) < 4) || (strlen($_REQUEST['name']) > 32) || (strlen($_REQUEST['shoptype']) > 32) || (!is_numeric($_REQUEST['shopid']))) 
	{
		message_die(GENERAL_ERROR, $lang['Error_Incorrect']);
	}
	else
	{
		$sql = "SELECT * 
			FROM " . SHOPS_TABLE . " 
			WHERE id = " . $_REQUEST['shopid'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query shop info from database', '', __LINE__, __FILE__, $sql);
		}
		$row = mysql_fetch_array($result);

		if (is_null($row['shopname']))
		{
			message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Shop']);
		}

		if ((!is_numeric($_REQUEST['restocktime'])) || (strlen($_REQUEST['restocktime']) > 20)) 
		{ 
			$error = 1; 
			$msg .= $lang['Error_Invalid_Restock']; 
		}
		if ((!is_numeric($_REQUEST['restockamount'])) || (strlen($_REQUEST['restockamount']) > 4)) 
		{ 
			$error = 1; 
			$msg .= $lang['Error_Invalid_Restock'];
		}
		if ($error) 
		{ 
			message_die(GENERAL_ERROR, $msg); 
		}

		$sql = "UPDATE " . SHOPS_TABLE . " 
			SET shopname = '" . $_REQUEST['name'] . "', shoptype = '" . $_REQUEST['shoptype'] . "', restocktime = '" . $_REQUEST['restocktime'] . "', restockamount = '" . $_REQUEST['restockamount'] . "' 
			WHERE id = " . $_REQUEST['shopid'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update shop information', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . SHOPITEMS_TABLE . " 
			SET shop = '" . $_REQUEST['name'] . "' 
			WHERE shop = '" . addslashes($row['shopname']) . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update shop item information', '', __LINE__, __FILE__, $sql);
		}
		$message = $row['shopname'] . $lang['Success_shop_updated'] . "<br /><br />" . sprintf($lang['Click_return_current_shop'], '<a href=' . append_sid("admin_shop_config.$phpEx?action=editshop&amp;shopid=" . $row['id']) . '>', '</a>')."<br /><br />" . sprintf($lang['Click_return_config'], '<a href=' . append_sid("admin_shop_config.$phpEx") . '>', '</a>')."<br /><br />".sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>') . "<br /><br />";

		message_die(GENERAL_MESSAGE, $message);
	}
}

// Item pages
elseif ($_REQUEST['action'] == 'additem')
{
	if ((strlen($_REQUEST['item']) > 32) || (strlen($_REQUEST['item']) < 2) || (strlen($_REQUEST['shortdesc']) < 3) || (strlen($_REQUEST['shortdesc']) > 80) || (strlen($_REQUEST['longdesc']) < 3) || (!is_numeric($_REQUEST['price']))  || (strlen($_REQUEST['price']) > 20) || (strlen($_REQUEST['stock']) > 6) || (!is_numeric($_REQUEST['stock'])) || (strlen($_REQUEST['maxstock']) > 6) || (!is_numeric($_REQUEST['maxstock'])) || (!is_numeric($_REQUEST['shopid']))) 
	{
		message_die(GENERAL_ERROR, $lang['Error_Item_Incorrect']);
	}
	if ((strlen($_REQUEST['accessforum']) > 4) || (!is_numeric($_REQUEST['accessforum']) && !empty($_REQUEST['accessforum'])))
	{
		message_die(GENERAL_ERROR, $lang['Error_Invalid_Access_Forum']);
	}
	else
	{
		$sql = "SELECT shopname 
			FROM " . SHOPS_TABLE . " 
			WHERE id = " . $_REQUEST['shopid'];
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not obtain shopname', '', __LINE__, __FILE__, $sql);
		}
		$row = mysql_fetch_array($result);
		
		$sql = "SELECT * 
			FROM " . SHOPITEMS_TABLE . " 
			WHERE name = '" . $_REQUEST['item'] . "'";
		if ( !($result = $db->sql_query($sql)) )
		
		if (mysql_num_rows($result) > 0)
		{
			message_die(GENERAL_ERROR, 'Could not query shop item information', '', __LINE__, __FILE__, $sql);
			
			message_die(GENERAL_MESSAGE, $lang['Error_Item_Already']);
		}
			
		$sql = "INSERT INTO " . SHOPITEMS_TABLE . " (name, shop, sdesc, ldesc, cost, stock, maxstock, sold, accessforum) 
			VALUES ('" . $_REQUEST['item'] . "', '" . addslashes($row['shopname']). "', '" . $_REQUEST['shortdesc'] . "', '" . $_REQUEST['longdesc'] . "', '" . $_REQUEST['price'] . "', '" . $_REQUEST['stock'] . "', '" . $_REQUEST['maxstock'] . "', 0, '" . $_REQUEST['accessforum'] . "')";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not insert shop info into database', '', __LINE__, __FILE__, $sql);
		}

		$message = stripslashes($_REQUEST['item']) . $lang['Success_item_add'] . "<br /><br />" . sprintf($lang['Click_return_current_shop'], '<a href=' . append_sid("admin_shop_config.$phpEx?action=editshop&amp;shopid=" . $_REQUEST['shopid'], true) . '>', '</a>')."<br /><br />" . sprintf($lang['Click_return_config'], '<a href=' . append_sid("admin_shop_config.$phpEx") . '>', '</a>')."<br /><br />".sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>') . "<br /><br />";

		message_die(GENERAL_MESSAGE, $message);
	}
}
elseif ($_REQUEST['action'] == 'updateitem')
{
	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE id = " . $_REQUEST['itemid'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query shop information', '', __LINE__, __FILE__, $sql);
	}
	$row = mysql_fetch_array($result);

	if (is_null($row['shop']))
	{
		message_die(GENERAL_ERROR, $lang['Error_Item_Invalid']);
	}

	if ((!is_numeric($_REQUEST['price'])) || (strlen($_REQUEST['price']) > 20)) 
	{ 
		$error = 1; 
		$msg .= $lang['Error_Invalid_Price']; 
	}

	if ((!is_numeric($_REQUEST['stock'])) || (strlen($_REQUEST['stock']) > 4)) 
	{ 
		$error = 1; 
		$msg .= $lang['Error_Invalid_Stock']; 
	}
	if ((!is_numeric($_REQUEST['maxstock'])) || (strlen($_REQUEST['maxstock']) > 4)) 
	{ 
		$error = 1; 
		$msg .= $lang['Error_Invalid_Max_Stock'];  
	}
	if ((!is_numeric($_REQUEST['sold'])) || (strlen($_REQUEST['sold']) > 5)) 
	{ 
		$error = 1; 
		$msg .= $lang['Error_Invalid_Sold']; 
	}
	if ((!is_numeric($_REQUEST['accessforum'])) || (strlen($_REQUEST['accessforum']) > 4)) 
	{ 
		$error = 1; 
		$msg .= $lang['Error_Invalid_Access_Forum']; 
	}

	if ((!is_numeric($stock)) || (strlen($stock) > 4)) 
	{ 
		$stock = ''; 
	}
	
	if ((!is_numeric($maxstock)) || (strlen($maxstock) > 4)) 
	{ 
		$maxstock = ''; 
	}
	
	if ((!is_numeric($sold)) || (strlen($sold) > 5)) 
	{ 
		$sold = ''; 
	}

	if ((!is_null($_REQUEST['shop'])) && (strlen($_REQUEST['shop']) > 2) && ($_REQUEST['shop'] != $row['shop'])) 
	{ 
		$sql1 = "SELECT * 
			FROM " . SHOPS_TABLE . " 
			WHERE shopname = '" . $_REQUEST['shop'] . "'";
		if ( !($result1 = $db->sql_query($sql1)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not query shop name information', '', __LINE__, __FILE__, $sql);
		}
		$row1 = mysql_fetch_array($result1);

		if (is_null($row1['shopname']))
		{ 
			message_die(GENERAL_ERROR, $lang['Error_Invalid_Shop']); 	
		}
	}
	elseif ((is_null($_REQUEST['shop'])) || (strlen($_REQUEST['shop']) < 2)) 
	{ 
		$error = 1; 
		$msg .= $lang['Error_Invalid_Shop_Name']; 	
	}

	if ((is_null($_REQUEST['shortdesc'])) || (strlen($_REQUEST['shortdesc']) < 2) || (strlen($_REQUEST['shortdesc']) > 80)) 
	{ 
		$error = 1; 
		$msg .= $lang['Error_Invalid_Short_Desc']; 	
	}
	
	if ((is_null($_REQUEST['longdesc'])) || (strlen($_REQUEST['longdesc']) < 2)) 
	{ 
		$error = 1; 
		$msg .= $lang['Error_Invalid_Desc']; 	
	}
	if ((is_null($_REQUEST['name'])) || (strlen($_REQUEST['name']) < 3)) 
	{ 
		$error = 1; 
		$msg .= $lang['Error_Invalid_Shop_Name']; 
	}
	if ($error) 
	{ 
		message_die(GENERAL_ERROR, $msg); 
	}
	
	if ((!is_null($_REQUEST['name'])) && (strlen($_REQUEST['name']) > 2) && ($_REQUEST['name'] != $row['name']))
	{
 		$useritem = addslashes('ß' . $row['name'] . 'Þ');
  		$usql = "SELECT user_id, user_items 
  			FROM " . USERS_TABLE . " 
  			WHERE user_items LIKE '%$useritem%'";
  		if ( !($result = $db->sql_query($usql)) )
  		{
  			message_die(GENERAL_ERROR, 'Could not query user information', '', __LINE__, __FILE__, $sql);
  		}
 		$sql_count = mysql_num_rows($result);
  		for ($x = 0; $x < $sql_count; $x++)
  		{
  			$urow = mysql_fetch_array($result);
  			$useritems = addslashes(str_replace($useritem, 'ß' . $_REQUEST['name'] . 'Þ', $urow['user_items']));
  			$u2sql = "UPDATE " . USERS_TABLE . " 
  				SET user_items = '$useritems' 
  				WHERE user_id = " . $urow['user_id'];
  			if ( !($u2result = $db->sql_query($u2sql)) )
  			{
    			message_die(GENERAL_ERROR, 'Could not update user item information', '', __LINE__, __FILE__, $sql);
  			}
		}
	}

	$sql = "UPDATE " . SHOPITEMS_TABLE . " 
		SET name = '" . $_REQUEST['name'] . "', shop = '" . $_REQUEST['shop'] . "', sdesc = '" . $_REQUEST['shortdesc'] . "', ldesc = '" . $_REQUEST['longdesc'] . "', cost = '" . $_REQUEST['price'] . "', stock = '" . $_REQUEST['stock'] . "', maxstock = '" . $_REQUEST['maxstock'] . "', sold = '" . $_REQUEST['sold'] . "', accessforum = '". $_REQUEST['accessforum'] . "' 
		WHERE id = " . $_REQUEST['itemid'];
	if ( !$db->sql_query($sql) )
  	{
  		message_die(GENERAL_ERROR, 'Could not update shop information', '', __LINE__, __FILE__, $sql);
  	}
  	
	$sql = "SELECT id 
		FROM " . SHOPS_TABLE . " 
		WHERE shopname = '" . $_REQUEST['shop'] . "'";
	if ( !$result = $db->sql_query($sql) )
  	{
  		message_die(GENERAL_ERROR, 'Could not query shop information', '', __LINE__, __FILE__, $sql);
  	}
	$srow = mysql_fetch_array($result);

	$message = $row['name'] . $lang['Success_item_updated'] . "<br /><br />" . sprintf($lang['Click_return_current_shop'], '<a href=' . append_sid("admin_shop_config.$phpEx?action=editshop&amp;shopid=".$srow['id']) . '>', '</a>')."<br /><br />" . sprintf($lang['Click_return_config'], '<a href=' . append_sid("admin_shop_config.$phpEx") . '>', '</a>')."<br /><br />".sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>') . "<br /><br />";

	message_die(GENERAL_MESSAGE, $message);
}

// Delete pages
elseif ($_REQUEST['action'] == 'deleteshop')
{
	$sql = "SELECT * 
		FROM " . SHOPS_TABLE . " 
		WHERE id = " . $_REQUEST['shopid'];
	if ( !($result = $db->sql_query($sql)) )
	{
  		message_die(GENERAL_ERROR, 'Could not query shop information', '', __LINE__, __FILE__, $sql);
	}
	$row = mysql_fetch_array($result);
	if (mysql_num_rows($result) < 1) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Invalid_Shop']);
	}

	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE shop = '" . addslashes($row['shopname']) . "'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query shop item information', '', __LINE__, __FILE__, $sql);
	}
	for ($xe = 0; $xe < mysql_num_rows($result); $xe++)
	{
 	 	$irow = mysql_fetch_array($result);
	 	$useritem = 'ß' . addslashes($irow['name']) . 'Þ';
  		$usql = "SELECT * 
  			FROM " . USERS_TABLE . " 
  			WHERE user_items LIKE '%$useritem%'";
  		if ( !($uresult = $db->sql_query($usql)) )
  		{
			message_die(GENERAL_ERROR, 'Could not query users information', '', __LINE__, __FILE__, $sql);
  		}
  		for ($x = 0; $x < mysql_num_rows($uresult); $x++)
  		{
  			$urow = mysql_fetch_array($uresult);
			$useritems = addslashes(str_replace(stripslashes($useritem), '', $urow['user_items']));
			$u2sql = "UPDATE " . USERS_TABLE . " 
				SET user_items = '$useritems' 
				WHERE user_id = ". $urow['user_id'];
  			if ( !($db->sql_query($u2sql)) )
  			{
  				message_die(GENERAL_ERROR, 'Could not update users item information', '', __LINE__, __FILE__, $sql);
  			}
		}
		$sql = "DELETE FROM " . SHOPITEMS_TABLE . " 
			WHERE id = " . $irow['id'];
		if ( !($result = $db->sql_query($sql)) )
		{
	  		message_die(GENERAL_ERROR, 'Could not delete item information from database', '', __LINE__, __FILE__, $sql);
		}
	}
	$sql = "DELETE FROM " . SHOPS_TABLE . " 
		WHERE shopname='" . addslashes($row['shopname']) . "'";
	if ( !($db->sql_query($sql)) )
	{
  		message_die(GENERAL_ERROR, 'Could not delete shop information from database', '', __LINE__, __FILE__, $sql);
	}

	$message = $row['shopname'] . $lang['Success_shop_deleted'] . "<br /><br />" . sprintf($lang['Click_return_config'], '<a href=' . append_sid("admin_shop_config.$phpEx") . '>', '</a>')."<br /><br />".sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>') . "<br /><br />";

	message_die(GENERAL_MESSAGE, $message);
}

elseif ($_REQUEST['action'] == 'deleteitem')
{
	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE id = " . $_REQUEST['itemid'];
  	if ( !($result = $db->sql_query($sql)) ) 
  	{ 
   		message_die(GENERAL_ERROR, 'Could not query shop items info from database', '', __LINE__, __FILE__, $sql);
  	}
	if (mysql_num_rows($result) < 1) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Item_Invalid']); 
	}
	$row = mysql_fetch_array($result);

	$sql = "SELECT id 
		FROM " . SHOPS_TABLE . " 
		WHERE shopname = '" . $row['shop'] . "'";
  	if ( !($result = $db->sql_query($sql)) ) 
  	{ 
    		message_die(GENERAL_ERROR, 'Could not query shop name info from database', '', __LINE__, __FILE__, $sql);
   	}
	$srow = mysql_fetch_array($result);	

	$useritem = 'ß' . addslashes($row['name']) . 'Þ';
	$usql = "SELECT * 
		FROM " . USERS_TABLE . " 
		WHERE user_items LIKE '%$useritem%'";
  	if ( !($uresult = $db->sql_query($usql)) )
  	{
  	  		message_die(GENERAL_ERROR, 'Could not query user items info from database', '', __LINE__, __FILE__, $sql);
	}
  	for ($x = 0; $x < mysql_num_rows($uresult); $x++)
  	{
  		$urow = mysql_fetch_array($uresult);
  		$useritems = addslashes(str_replace(stripslashes($useritem), '', $urow['user_items']));
  		$u2sql = "UPDATE " . USERS_TABLE . " 
  			SET user_items = '$useritems' 
  			WHERE user_id = " . $urow['user_id'];
  		if ( !($u2result = $db->sql_query($u2sql)) )
  		{
   	  		message_die(GENERAL_ERROR, 'Could not update user items info', '', __LINE__, __FILE__, $sql);
  		}
	}
	$sql = "DELETE FROM " . SHOPITEMS_TABLE . " 
		WHERE id = " . $_REQUEST['itemid'];
  	if ( !($result = $db->sql_query($sql)) )
  	{
   	  	message_die(GENERAL_ERROR, 'Could not delete shop item from database', '', __LINE__, __FILE__, $sql);
  	}

	$message = $row['name'] . $lang['Success_item_deleted'] . "<br /><br />" . sprintf($lang['Click_return_current_shop'], '<a href=' . append_sid("admin_shop_config.$phpEx?action=editshop&amp;shopid=" . $srow['id']) . '>', '</a>')."<br /><br />" . sprintf($lang['Click_return_config'], '<a href=' . append_sid("admin_shop_config.$phpEx") . '>', '</a>')."<br /><br />".sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>') . "<br /><br />";

	message_die(GENERAL_MESSAGE, $message);
}
// Shop configuration
elseif ($_REQUEST['action'] == 'updateglobals')
{
	if (($_REQUEST['orderby'] != "name") && ($_REQUEST['orderby'] != "cost") && ($_REQUEST['orderby'] != "id")) 
	{ 
		$error = 1; 
	}
	if (($_REQUEST['profiledisplay'] != "images") && ($_REQUEST['profiledisplay'] != "link") && ($_REQUEST['profiledisplay'] != "none")) 
	{ 
		$error = 1; 
	}
	if (($_REQUEST['inventorytype'] != "grouped") && ($_REQUEST['inventorytype'] != "normal")) 
	{ 
		$error = 1; 
	}
	if (($_REQUEST['topicdisplaynum'] < 0) || (empty($_REQUEST['topicdisplaynum'])) || (!is_numeric($_REQUEST['topicdisplaynum']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['invlimit'] < 0) || (empty($_REQUEST['invlimit'])) || (!is_numeric($_REQUEST['invlimit']))) && ($_REQUEST['invlimit'] != "0")) 
	{ 
		$error = 1; 
	}
	if ((empty($_REQUEST['sellrate'])) || (!is_numeric($_REQUEST['sellrate'])) || ($_REQUEST['sellrate'] < 0) || ($_REQUEST['sellrate'] > 100)) 
	{ 
		$error = 1; 
	}
	
	if ($error) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Invalid_Global']); 
	}

	if ( $_REQUEST['shoprestock'] == 1 ) 
	{
		$sql = "UPDATE " . SHOPS_TABLE . " 
			SET restockedtime = " . time();
		if ( !($db->sql_query($sql)) ) 
		{ 
   	  		message_die(GENERAL_ERROR, 'Could not update shop restock time', '', __LINE__, __FILE__, $sql);
		}
	}
	else if ( $_REQUEST['shoprestock'] == 0 ) 
	{
		$sql = "UPDATE " . SHOPS_TABLE . " 
			SET restockedtime = 0";
		if ( !($db->sql_query($sql)) ) 
		{ 
   	  		message_die(GENERAL_ERROR, 'Could not update shop restock time', '', __LINE__, __FILE__, $sql);
		}
	}

	$getarray = array(
		'multibuys',
		'restocks',
		'sellrate',
		'viewtopic',
		'viewprofile',
		'viewinventory',
		'viewtopiclimit',
		'shop_orderby',
		'shop_give',
		'shop_trade',
		'shop_invlimit',
		'u_shops_enabled',
		'u_shops_tax_percent',
		'u_shops_max_items',
		'u_shops_open_cost',
		'shop_trans_enable'
	);
	
	$getarray2 = array(
		$_REQUEST['multiitems'],
		$_REQUEST['shoprestock'],
		$_REQUEST['sellrate'],
		$_REQUEST['viewtopic'],
		$_REQUEST['profiledisplay'],
		$_REQUEST['inventorytype'],
		$_REQUEST['topicdisplaynum'],
		$_REQUEST['orderby'],
		$_REQUEST['shopgive'],
		$_REQUEST['shoptrade'],
		$_REQUEST['invlimit'],
		$_REQUEST['status'],
		$_REQUEST['tax_percent'],
		$_REQUEST['max_items'],
		$_REQUEST['open_cost'],
		$_REQUEST['shop_trans']
	);
	
	$getarraynum = sizeof($getarray);

	$globals = array();
	for($i = 0; $i < $getarraynum; $i++)
	{
		$gsql = "UPDATE " . CONFIG_TABLE . " 
			SET config_value = '$getarray2[$i]' 
			WHERE config_name = '$getarray[$i]'";
		if ( !($result = $db->sql_query($gsql)) ) 
		{ 
   	  		message_die(GENERAL_ERROR, 'Could not update shop configuration', '', __LINE__, __FILE__, $sql); 
		}
	}

	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

	$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_shop_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}
// Edit shop
elseif ($_REQUEST['action'] == 'editshop')
{
	$template->set_filenames(array(
		'body' => 'admin/shop_config_body.tpl')
	);

	// Check shopname
	$sql = "SELECT * 
		FROM " . SHOPS_TABLE . " 
		WHERE id = " . $_REQUEST['shopid'];
	if ( !($result = $db->sql_query($sql)) )
	{
   	  		message_die(GENERAL_ERROR, 'Could not query shop info from database', '', __LINE__, __FILE__, $sql); 
	}
	$row = mysql_fetch_array($result);
	if (strlen($row['shoptype']) < 3)
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Shop']);
	}

	// Get shop items	
	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE shop = '" . addslashes($row['shopname']) . "'";
	if ( !($iresult = $db->sql_query($sql)) )
	{
  		message_die(GENERAL_ERROR, 'Could not query shop info from database', '', __LINE__, __FILE__, $sql); 
	}
	$shopitems = '<select name="itemid">';
	for ($x = 0; $x < mysql_num_rows($iresult); $x++)
	{
		$irow = mysql_fetch_array($iresult);
		$shopitems .= '<option value="' . $irow['id'] . '">' . $irow['name'] . '</option>';
	}
	$shopitems .= '</select>';
	
	if (empty($irow['name'])) 
	{ 
		$shopitems = '<tr>
			<td class="row2" colspan="2" align="center" height="30"><b style="color:#FF0000">' . $lang['Error_Item_None'] . '</b></td>
		</tr>'; 
	}
	else 
	{ 
		$shopitems = '<tr>
			<td class="row1"><b>' . $lang['Edit'] . ' ' . $lang['Items'] . ':</b></td>
			<td class="row2">' . $shopitems . ' &nbsp; <input type="hidden" name="action" value="edititem"><input type="submit" class="liteoption" value="' . $lang['Edit'] . ' ' . $lang['Item'] . '"></td>
		</tr>'; 
	}
		
	//
	// Begin template variable creation
	//
 	$shopinfo = '<form method="post" action="'.append_sid("admin_shop_config.".$phpEx).'"><tr>
 		<th colspan="2" class="thHead">' . $lang['Modify'] . ' ' . $lang['Shop'] . '</th>
 	</tr><tr>
 		<td class="row1" width="50%"><b>' . $lang['Shop_Name'] . ':</b></td>
 		<td class="row2"><input class="post" type="text" name="name" value="' . $row['shopname'] . '" size="35" /></td>
 	</tr><tr>
 		<td class="row1"><b>' . $lang['Shop_Type'] . ':</b></td>
 		<td class="row2"><input class="post" type="text" name="shoptype" value="' . $row['shoptype'] . '" size="20" /></td>
 	</tr><tr>
 		<td class="row1"><b>' . $lang['Restock_Time'] . ':</b><br /><span class="gensmall">' . $lang['Restock_Explain'] . '</span></td>
 		<td class="row2"><input class="post" type="text" name="restocktime" value="' . $row['restocktime'] . '" size="5" /></td>
 	</tr><tr>
 		<td class="row1"><b>' . $lang['Restock_Amount'] . ':</b></td>
 		<td class="row2"><input class="post" type="text" name="restockamount" value="' . $row['restockamount'] . '" size="5" /></td>
 	</tr><tr><td class="catBottom" colspan="2" align="center">
 		<table width="100" cellpadding="0" cellspacing="0">
 		<tr>
 			<td align="right"><input type="hidden" name="shopid" value="' . $row['id'] . '"><input type="hidden" name="action" value="updateshop"><input class="mainoption" type="submit" value="' . $lang['Update'] . '"></td>
 			</form>
 			<td>&nbsp;</td>
 			<form method="post" action="'.append_sid("admin_shop_config.".$phpEx).'">
 			<td><input type="hidden" name="shopid" value="' . $row['id'] . '"><input type="hidden" name="action" value="deleteshop"><input class="liteoption" type="submit" value="' . $lang['Delete'] . '"></td>
 		</tr>
 		</table>
 		</td>
 	</form></tr></table><br />
 	<table width="100%" align="center" class="forumline" cellspacing="1" cellpadding="4"><form method="post" action="'.append_sid("admin_shop_config.".$phpEx).'"><tr>
 		<th class="thHead" colspan="2">' . $lang['Shop'] . ' ' . $lang['Items'] . '</th>
 	' . $shopitems . '
 	</form><form method="post" action="'.append_sid("admin_shop_config.".$phpEx).'"><tr>
  		<th colspan="2" class="thHead">' . $lang['Add'] . ' ' . $lang['Item'] . '</th>
  	</tr>
  	<tr>
	 	<td class="row1" width="50%"><b>' . $lang['Item_Name'] . ':</b><br /><span class="gensmall">' . $lang['Image_Explain'] . '</span></td>
	 	<td class="row2"><input class="post" type="text" name="item" size="35" maxlength="32" /></td>
	</tr><tr>
	   	<td class="row1"><b>' . $lang['Short_Desc'] . ':</b><br /><span class="gensmall">' . $lang['Short_Desc_Explain'] . '</span></td>
	   	<td class="row2"><input class="post" type="text" name="shortdesc" size="35" maxlength="80" /></td>
	</tr><tr>
	   	<td class="row1"><b>' . $lang['Long_Desc'] . ':</b></td>
	   	<td class="row2"><textarea class="post" name="longdesc" rows="5" cols="35"></textarea></td>
	</tr><tr>
	   	<td class="row1"><b>' . $lang['Price'] . ':</b></td>
	   	<td class="row2"><input class="post" type="text" name="price" size="5" maxlength="20" /> ' . $board_config['points_name'] . '</td>
	</tr><tr>
		<td class="row1"><b>' .$lang['Stock'] . ':</b></td>
		<td class="row2"><input class="post" type="text" name="stock" size="5" maxlength="3" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Max_Stock'] . ':</b></td>
		<td class="row2"><input class="post" type="text" name="maxstock" size="5" maxlength="3" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Access_Forum'] . ':</b></td>
		<td class="row2"><input class="post" type="text" name="accessforum" size="5" maxlength="4" /></td>
	</tr><tr>
		<td class="catBottom" colspan="2" align="center">
		<input type="hidden" name="action" value="additem">
		<input class="mainoption" type="submit" value="' . $lang['Add'] . ' ' . $lang['Item'] . '">&nbsp;&nbsp;<input type="reset" class="liteoption" value="' . $lang['Reset'] . '"></td>
		<input type="hidden" name="shopid" value="' . $row['id'] . '"></form></tr>';

	// Parse template variables
	$template->assign_vars(array(
		'SHOPCONFIGINFO' => "$shopinfo",
		'S_CONFIG_ACTION' => append_sid('admin_shop_config.' . $phpEx),
		'SHOPTITLE' => $lang['Shop_Editor'],
		'SHOPEXPLAIN' => $lang['Shop_Prop'])
	);
}

// Edit item
elseif ($_REQUEST['action'] == 'edititem')
{
	$template->set_filenames(array(
		'body' => 'admin/shop_config_body.tpl')
	);

	// Check item name
	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE id = " . $_REQUEST['itemid'];
	if ( !($result = $db->sql_query($sql)) )
	{
  		message_die(GENERAL_ERROR, 'Could not query shop item info from database', '', __LINE__, __FILE__, $sql); 
	}
	$row = mysql_fetch_array($result);
	if (mysql_num_rows($result) < 1)
	{
		message_die(GENERAL_ERROR, $lang['Error_Item_Invalid']);
	}

	//
	// Begin template variable creation
	//
	$shopinfo = '<form method="post" action="'.append_sid("admin_shop_config.".$phpEx).'"><tr>
		<th colspan="2" class="thHead">' . $lang['Edit'] . ' ' . $lang['Item'] . '</th>
	</tr><tr>
		<td class="row1" width="50%"><b>' . $lang['Item'] . ' ' . $lang['Name'] . ':</b><br /><span class="gensmall">' . $lang['Image_Explain'] . '</span></td>
		<td class="row2" valign="middle"><input class="post" name="name" type="text" size="32" maxlength="32" value="' . $row['name'] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Icon'] . ':</b></td>
		<td class="row2"><img src="' . $phpbb_root_path . 'images/shop/' . $row['name'] . '.gif" alt="' . $row['name'] . '" title="' . $row['name'] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Shop'] . ':</b><br /><span class="gensmall">' . $lang['Shop_Short_Explain'] . '</span></td>
		<td class="row2"><input class="post" name="shop" type="text" size="32" maxlength="32" value="' . $row['shop'] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Short_Desc'] . ':</b><br /><span class="gensmall">' . $lang['Short_Desc_Explain'] . '</span></td>
		<td class="row2"><input class="post" name="shortdesc" type="text" size="35" maxlength="80" value="' . $row['sdesc'] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Long_Desc'] . ':</b></td>
		<td class="row2"><textarea class="post" name="longdesc" rows="5" cols="35">' . $row['ldesc'] . '</textarea></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Price'] . ':</b></td>
		<td class="row2"><input class="post" name="price" type="text" size="5" maxlength="20" value="' . $row['cost'] . '" /> ' . $board_config['points_name'] . '</td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Stock'] . ':</b></td>
		<td class="row2"><input class="post" name="stock" type="text" size="5" maxlength="3" value="' . $row['stock'] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Max_Stock'] . ':</b></td>
		<td class="row2"><input class="post" name="maxstock" type="text" size="5" maxlength="3" value="' . $row['maxstock'] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Sold'] . ':</b></td>
		<td class="row2"><input class="post" name="sold" type="text" size="5" maxlength="4" value="' . $row['sold'] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Access_Forum'] . ':</b></td>
		<td class="row2"><input class="post" name="accessforum" type="text" size="5" maxlength="5" value="' . $row['accessforum'] . '" /></td>
	</tr><tr>
		<td class="catBottom" align="center" colspan="2"><table width="100%"><tr>
			<td align="right" width="50%">
			<input type="hidden" name="itemid" value="' . $row['id'] . '">
			<input type="hidden" name="action" value="updateitem">
			<input class="mainoption" type="submit" value="' . $lang['Update'] . '">&nbsp;</td>
		</form><form method="post" action="'.append_sid("admin_shop_config.".$phpEx).'">
			<td><input type="hidden" name="action" value="deleteitem">
			<input type="hidden" name="itemid" value="' . $row['id'] . '">
			<input class="liteoption" type="submit" value="' . $lang['Delete'] . '"></td>
		</form></tr></table></td>
	</tr>';
	
	// Parse template variables
	$template->assign_vars(array(
		'SHOPCONFIGINFO' => "$shopinfo",
		'S_CONFIG_ACTION' => append_sid('admin_shop_config.' . $phpEx),
		'SHOPTITLE' => $lang['Shop_Editor'],
		'SHOPEXPLAIN' => $lang['Delete_Explain'])
	);
}
// Edit users inventories
elseif ($_REQUEST['action'] == 'editinventory')
{
	$template->set_filenames(array(
		'body' => 'admin/shop_config_body.tpl')
	);

	// Check username & get useritems
	$row = get_userdata(stripslashes($_REQUEST['username']));
	if (strlen($row['username']) < 1) 
	{ 
		message_die(GENERAL_ERROR, $lang['User_not_exist']); 
	}
		
	// Set useritems into variable
	$itemarray = str_replace('Þ', '', $row['user_items']);
	$itemarray = explode('ß', $itemarray);
	$itemcount = sizeof($itemarray);
    for ($xe = 0; $xe < $itemcount; $xe++)
	{
		if ($itemarray[$xe] != NULL) 
		{ 
			$user_items .= '<option value="' . $itemarray[$xe] . '">' . $itemarray[$xe] . '</option>'; 
		}
	}
	
	if (strlen($user_items) < 5) 
	{ 
		$user_items = '<option value="Nothing">' . $lang['None'] . '</option>'; 
	}

	// Get all items
	$isql = "SELECT name 
		FROM " . SHOPITEMS_TABLE;
	if ( !($iresult = $db->sql_query($isql)) ) 
	{ 
  		message_die(GENERAL_ERROR, 'Could not query user item info from database', '', __LINE__, __FILE__, $sql); 
	}
	
  	for ($x = 0; $x < mysql_num_rows($iresult); $x++)
  	{
		$irow = mysql_fetch_array($iresult);
		if ($irow['name'] != NULL) 
		{ 
			$all_items .= '<option value="' . $irow['name'] . '">' . $irow['name'] . '</option>'; 
		}
	}
	
	// Make variables
	$inventoryinfo = '<form method="post" action="'.append_sid("admin_shop_config.".$phpEx).'"><tr>
		<th colspan="2" class="thHead">' . $lang['Modify_User_Inverntory'] . '</th>
	</tr><tr>
		<td class="row1" width="50%"><b>' . $lang['Delete'] . ' ' . $lang['Item'] . ':</b></td>
		<td class="row2"><select name="itemname">' . $user_items . '</select>
		<input type="hidden" name="subaction" value="delete">
		<input type="hidden" name="action" value="updateinv">
		<input type="hidden" name="username" value="' . $row['username'] . '">
		&nbsp;<input class="liteoption" type="submit" value="' . $lang['Delete'] . ' ' . $lang['Item'] . '"></td>
	</tr></form><form method="post" action="'.append_sid("admin_shop_config.".$phpEx).'"><tr>
		<td class="row1"><b>' . $lang['Add'] . ' ' . $lang['Item'] . ':</b></td>
		<td class="row2"><select name="itemname">' . $all_items . '</select>
		<input type="hidden" name="action" value="updateinv">
		<input type="hidden" name="subaction" value="add">
		<input type="hidden" name="username" value="' . $row['username'] . '">
		&nbsp;<input class="liteoption" type="submit" value="' . $lang['Add'] . ' ' . $lang['Item'] . '"></td>
	</tr></form><form method="post" action="'.append_sid("admin_shop_config.".$phpEx).'"><tr>
		<td class="row1"><b>' . $lang['Clear_items'] . ':</b></td>
		<td class="row2">
		<input type="hidden" name="subaction" value="clear">
		<input type="hidden" name="action" value="updateinv">
		<input type="hidden" name="username" value="' . $row['username'] . '">
		<input class="liteoption" type="submit" value="' . $lang['Delete'] . ' ' . $lang['Inventory'] . '"></span></td>
	</tr></form><tr>
		<td class="catBottom" colspan="2">&nbsp;</td>
	</tr>';

	// Parse template variables
	$template->assign_vars(array(
		'SHOPCONFIGINFO' => "$inventoryinfo",
		'S_CONFIG_ACTION' => append_sid('admin_shop_config.' . $phpEx),
		'SHOPTITLE' => $lang['Shop_Editor'] . ': ' . $lang['Modify_User_Inverntory'],
		'SHOPEXPLAIN' => $lang['Modify_User_Explain'])
	);
}

// Update users inventories
elseif ($_REQUEST['action'] == 'updateinv')
{
	// Check username
	$row = get_userdata(stripslashes($_REQUEST['username']));		
	if (empty($row['username'])) 
	{ 
		message_die(GENERAL_ERROR, $lang['User_not_exist']); 
	}
	
	if (!empty($_REQUEST['itemname']))
	{
		// Check if item exists
		$sql = "SELECT * 
			FROM " . SHOPITEMS_TABLE . " 
			WHERE name = '" . $_REQUEST['itemname'] . "'";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
  			message_die(GENERAL_ERROR, 'Could not query user item info from database', '', __LINE__, __FILE__, $sql); 
		}
		if (mysql_num_rows($result) < 1) 
		{ 
			message_die(GENERAL_ERROR, $lang['Error_Item_Invalid']); 
		}
	}
	elseif ($_REQUEST['subaction'] != 'clear') 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Invalid_Item']); 
	}

	if ($_REQUEST['subaction'] == 'delete')
	{
		// Make sure user has item
		if (substr_count($row['user_items'], 'ß' . stripslashes($_REQUEST['itemname']) . 'Þ') < 1)
		{
			message_die(GENERAL_ERROR, $lang['Error_Invalid_User_Item']);
		}
		
		$useritems = substr_replace($row['user_items'], '', strpos($row['user_items'], 'ß' . stripslashes($_REQUEST['itemname']) . 'Þ'), strlen('ß' . stripslashes($_REQUEST['itemname']) . 'Þ')); 
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_items = '" . addslashes($useritems) . "' 
			WHERE user_id = " . $row['user_id'];
		if ( !($db->sql_query($sql)) )
		{
	  		message_die(GENERAL_ERROR, 'Could not update user item info into database', '', __LINE__, __FILE__, $sql); 
		}

		$message =  stripslashes($_REQUEST['itemname']) . $lang['Deleted_from'] . $_REQUEST['username'] . "'s " . $lang['Success_item_inventory'] . "<br /><br />" . sprintf($lang['Click_return_user_inventory'], '<a href=' . append_sid("admin_shop_config.$phpEx?username=" . $_REQUEST['username'] . "&amp;action=editinventory") . '>', '</a>')."<br /><br />" . sprintf($lang['Click_return_config'], '<a href=' . append_sid("admin_shop_config.$phpEx") . '>', '</a>')."<br /><br />".sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>') . "<br /><br />";

		message_die(GENERAL_MESSAGE, $message);
	}
	
	if ($_REQUEST['subaction'] == 'add')
	{
		$newitems = addslashes($row['user_items']) . 'ß' . $_REQUEST['itemname'] . 'Þ';
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_items = '$newitems' 
			WHERE username = '" . $_REQUEST['username'] . "'";
		if ( !($db->sql_query($sql)) )
		{
  			message_die(GENERAL_ERROR, 'Could not update user items', '', __LINE__, __FILE__, $sql); 
		}
		$message = stripslashes($_REQUEST['itemname']) . $lang['Added_to'] . $username . "'s " . $lang['Success_item_inventory'] . "<br /><br />" . sprintf($lang['Click_return_user_inventory'], '<a href=' . append_sid("admin_shop_config.$phpEx?username=" . $_REQUEST['username'] . "&amp;action=editinventory") . '>', '</a>')."<br /><br />" . sprintf($lang['Click_return_config'], '<a href=' . append_sid("admin_shop_config.$phpEx") . '>', '</a>')."<br /><br />".sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>') . "<br /><br />";

		message_die(GENERAL_MESSAGE, $message);
	}
	if ($_REQUEST['subaction'] == 'clear')
	{
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_items = '' 
			WHERE username = '" . $_REQUEST['username'] . "'";
		if ( !($db->sql_query($sql)) ) 
		{ 
  			message_die(GENERAL_ERROR, 'Could not delete user inventory', '', __LINE__, __FILE__, $sql); 
		}
		$message = stripslashes($_REQUEST['username']) . "'s " . $lang['Success_delete_inventory'] . "<br /><br />" . sprintf($lang['Click_return_user_inventory'], '<a href=' . append_sid("admin_shop_config.$phpEx?username=" . $_REQUEST['username'] . "&amp;action=editinventory") . '>', '</a>')."<br /><br />" . sprintf($lang['Click_return_config'], '<a href=' . append_sid("admin_shop_config.$phpEx") . '>', '</a>')."<br /><br />".sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>') . "<br /><br />";

		message_die(GENERAL_MESSAGE, $message);
	}
}

// Special permission and effects shop
elseif ($_REQUEST['action'] == 'editspecialshop')
{
	$template->set_filenames(array(
		'body' => 'admin/shop_config_body.tpl')
	);

	//
	// Get special shop info
	//
	$shoparray = explode('ß', $board_config['specialshop']);
	$shoparraycount = sizeof($shoparray);
	$shopstatarray = array();
	for ($x = 0; $x < $shoparraycount; $x++)
	{
		$temparray = explode('Þ', $shoparray[$x]);
		$shopstatarray[] = $temparray[0];
		$shopstatarray[] = $temparray[1];
		if ($temparray[1] == 'enabled') 
		{ 
			$shopstatarray[] = 'disabled'; 
		} 
		if ($temparray[1] == 'disabled') 
		{ 
			$shopstatarray[] = 'enabled'; 
		}
		if ($temparray[0] == 'on') 
		{ 
			$shopstatarray[] ='off'; 
		} 
		if ($temparray[0] == 'off') 
		{ 
			$shopstatarray[] = 'on'; 
		}  
	}

	//
	// Begin template variable creation
	//
	$shopinfo = '
	<form method="post" action="'.append_sid("admin_shop_config.".$phpEx).'"><input type="hidden" name="action" value="updateeffects">
	<tr>
		<th class="thHead" colspan="2">' . $lang['Modify_Effects_Shop'] . '</th>
	</tr><tr>
		<td class="row1" width="50%"><b>' . $lang['Shop'] . ' ' . $lang['Enabled'] . ':</b></td>
		<td class="row2"><select name="shopstats"><option value="' . $shopstatarray[3] . '">' . $shopstatarray[3] . '</option><option value="' . $shopstatarray[4] . '">' . $shopstatarray[4] . '</option></select></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Shop_Name'] . ':</b></td>
		<td class="row2"><input class="post" name="shopname" type="text" size="32" maxlength="32" value="' . $shopstatarray[6] . '" /></td>
	</tr><tr>
		<th class="thSides" colspan="2">' . $lang['Privilege'] . ' ' . $lang['Settings'] . '</th>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Av_Priv'] . ' :</b></td>
		<td class="row2"><select name="avatarbuy"><option value="' . $shopstatarray[7] . '">' . $shopstatarray[7] . ' </option><option value="' . $shopstatarray[9]. '">' . $shopstatarray[9] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="avatarprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[8] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Sig_Priv'] . ':</b></td>
		<td class="row2"><select name="sigbuy"><option value="' . $shopstatarray[10] . '">' . $shopstatarray[10] . '</option><option value="' . $shopstatarray[12] . '"> ' . $shopstatarray[12] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="sigprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[11] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Title_Priv'] . ':</b></td>
		<td class="row2"><select name="titlebuy"><option value="' . $shopstatarray[13] . '">' . $shopstatarray[13] . ' </option><option value="' . $shopstatarray[15] . '">' . $shopstatarray[15] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="titleprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[14] . '" /></td>
	</tr><tr>
		<th class="thSides" colspan="2">' . $lang['Name'] . ' ' . $lang['Effects'] . ' ' . $lang['Settings'] . '</th>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Color'] . ':</b></td>
		<td class="row2"><select name="colorbuy"><option value="' . $shopstatarray[16] . '">' . $shopstatarray[16] . '</option><option value="' . $shopstatarray[18] . '">' . $shopstatarray[18] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="colorprice"  type="text" size="5" maxlength="10" value="' . $shopstatarray[17] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Glow'] . ':</b></td>
		<td class="row2"><select name="glowbuy"><option value="' . $shopstatarray[19] . '">' . $shopstatarray[19] . '</option><option value="' . $shopstatarray[21] . '">' . $shopstatarray[21] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="glowprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[20] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Shadow'] . ':</b></td>
		<td class="row2"><select name="shadowbuy"><option value="' . $shopstatarray[22] . '">' . $shopstatarray[22] . '</option><option value="' . $shopstatarray[24] . '">' . $shopstatarray[24] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="shadowprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[23] . '" /></td>
	</tr><tr>
		<th class="thSides" colspan="2">' . $lang['Title'] . ' ' . $lang['Effects'] . ' ' . $lang['Settings'] . '</th>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Title_Color'] . ':</b></td>
		<td class="row2"><select name="tcolorbuy"><option value="' . $shopstatarray[25] . '">' . $shopstatarray[25] . '</option><option value="' . $shopstatarray[27] . '">' . $shopstatarray[27]  . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="tcolorprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[26] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Title_Glow'] . ':</b></td>
		<td class="row2"><select name="tglowbuy"><option value="' . $shopstatarray[28] . '">' . $shopstatarray[28] . '</option><option value="' . $shopstatarray[30] . '">' . $shopstatarray[30] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="tglowprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[29] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Title_Shadow'] . ':</b></td>
		<td class="row2"><select name="tshadowbuy"><option value="' . $shopstatarray[31] . '">' . $shopstatarray[31] . '</option><option value="' . $shopstatarray[33] . '">' . $shopstatarray[33] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="tshadowprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[32] . '" /></td>
	</tr><tr>
		<th class="thSides" colspan="2">' . $lang['User_Custom_Settings'] . '</th>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Custom_Title'] . ':</b></td>
		<td class="row2"><select name="buyctitle"><option value="' . $shopstatarray[34] . '">' . $shopstatarray[34] . '</option><option value="' . $shopstatarray[36] . '">' . $shopstatarray[36] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="ctitleprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[35] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Change_Username'] . ':</b></td>
		<td class="row2"><select name="buynamec"><option value="' . $shopstatarray[37] . '">' . $shopstatarray[37] . '</option><option value="' . $shopstatarray[39] . '">' . $shopstatarray[39] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="namecprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[38] . '" /></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Buy_Users_Title'] . ':</b></td>
		<td class="row2"><select name="buycutitle"><option value="' . $shopstatarray[40] . '">' . $shopstatarray[40] . '</option><option value="' . $shopstatarray[42] . '">' . $shopstatarray[42] . '</option></select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class="post" name="cutitleprice" type="text" size="5" maxlength="10" value="' . $shopstatarray[41] . '" /></td>
	</tr><tr>
		<td class="catBottom" colspan="2" align="center"><input class="mainoption" type="submit" value="' . $lang['Submit'] . '">&nbsp;&nbsp;<input type="reset" class="liteoption" value="' . $lang['Reset'] . '"></td>
	</tr></form>';

	// Parse template variables
	$template->assign_vars(array(
		'SHOPCONFIGINFO' => "$shopinfo",
		'S_CONFIG_ACTION' => append_sid('admin_shop_config.' . $phpEx),
		'SHOPTITLE' => $lang['Shop_Editor'],
		'SHOPEXPLAIN' => $lang['Modify_Effects_Shop_Explain'])
	);
}
// Update special shop
elseif ($_REQUEST['action'] == 'updateeffects')
{
	if (strlen($_REQUEST['shopname']) < 3) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Shopname_Short']); 
	}
	if (($_REQUEST['shopstats'] != 'disabled') && ($_REQUEST['shopstats'] != 'enabled')) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['buycutitle'] != 'on') && ($_REQUEST['buycutitle'] != 'off')) || (!is_numeric($_REQUEST['cutitleprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['buyctitle'] != 'on') && ($_REQUEST['buyctitle'] != 'off')) || (!is_numeric($_REQUEST['ctitleprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['buynamec'] != 'on') && ($_REQUEST['buynamec'] != 'off')) || (!is_numeric($_REQUEST['namecprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['tshadowbuy'] != 'on') && ($_REQUEST['tshadowbuy'] != 'off')) || (!is_numeric($_REQUEST['tshadowprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['tglowbuy'] != 'on') && ($_REQUEST['tglowbuy'] != 'off')) || (!is_numeric($_REQUEST['tglowprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['tcolorbuy'] != 'on') && ($_REQUEST['tcolorbuy'] != 'off')) || (!is_numeric($_REQUEST['tcolorprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['shadowbuy'] != 'on') && ($_REQUEST['shadowbuy'] != 'off')) || (!is_numeric($_REQUEST['shadowprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['glowbuy'] != 'on') && ($_REQUEST['glowbuy'] != 'off')) || (!is_numeric($_REQUEST['glowprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['colorbuy'] != 'on') && ($_REQUEST['colorbuy'] != 'off')) || (!is_numeric($_REQUEST['colorprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['titlebuy'] != 'on') && ($_REQUEST['titlebuy'] != 'off')) || (!is_numeric($_REQUEST['titleprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['sigbuy'] != 'on') && ($_REQUEST['sigbuy'] != 'off')) || (!is_numeric($_REQUEST['sigprice']))) 
	{ 
		$error = 1; 
	}
	if ((($_REQUEST['avatarbuy'] != 'on') && ($_REQUEST['avatarbuy'] != 'off')) || (!is_numeric($_REQUEST['avatarprice']))) 
	{ 
		$error = 1; 
	}
	if ($error) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Invaild_Action']); 
	}
	
	$specialshop = 'ßstoreÞ' . $_REQUEST['shopstats'] . 'ßnameÞ' . $_REQUEST['shopname'] . 'ß' . $_REQUEST['avatarbuy'] . 'Þ' . $_REQUEST['avatarprice'] . 'ß' . $_REQUEST['sigbuy'] . 'Þ' . $_REQUEST['sigprice'] . 'ß' . $_REQUEST['titlebuy'] . 'Þ' . $_REQUEST['titleprice'] . 'ß' . $_REQUEST['colorbuy'] . 'Þ' . $_REQUEST['colorprice'] . 'ß' . $_REQUEST['glowbuy'] . 'Þ' . $_REQUEST['glowprice'] . 'ß' . $_REQUEST['shadowbuy'] . 'Þ' . $_REQUEST['shadowprice'] . 'ß' . $_REQUEST['tcolorbuy'] . 'Þ' . $_REQUEST['tcolorprice'] . 'ß' . $_REQUEST['tglowbuy'] . 'Þ' . $_REQUEST['tglowprice'] . 'ß' . $_REQUEST['tshadowbuy'] . 'Þ' . $_REQUEST['tshadowprice'] . 'ß' . $_REQUEST['buyctitle'] . 'Þ' . $_REQUEST['ctitleprice'] . 'ß' . $_REQUEST['buynamec'] . 'Þ' . $_REQUEST['namecprice'] . 'ß' . $_REQUEST['buycutitle'] . 'Þ' . $_REQUEST['cutitleprice'];

	// Update special shop info
	//
	$sql = "UPDATE " . CONFIG_TABLE . " 
		SET config_value = '$specialshop' 
		WHERE config_name = 'specialshop'";
	if ( !($db->sql_query($sql)) ) 
	{ 
  		message_die(GENERAL_ERROR, 'Could not update Special Shop configuration', '', __LINE__, __FILE__, $sql); 
	}
	
	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

	$message = $lang['Success_special_shop'] . "<br /><br />" . sprintf($lang['Click_return_special_shop_admin'], '<a href=' . append_sid("admin_shop_config.$phpEx?action=editspecialshop") . '>', '</a>')."<br /><br />" . sprintf($lang['Click_return_config'], '<a href=' . append_sid("admin_shop_config.$phpEx") . '>', '</a>')."<br /><br />".sprintf($lang['Click_return_admin_index'], '<a href=' . append_sid("index.$phpEx?pane=right") . '>', '</a>') . "<br /><br />";
		
	message_die(GENERAL_MESSAGE, $message);
}
else 
{ 
	message_die(GENERAL_ERROR, $lang['Error_Invaild_Action']); 
}

//
// Generate the page
//
$template->pparse('body');

include('page_footer_admin.' . $phpEx);

?>
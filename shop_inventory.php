<?php
/** 
*
* @package phpBB2
* @version $Id: shop_inventory.php,v 2.6.0 2002/12/11 16:46:15 zarath Exp $
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

// Start of shop list page
if ($_REQUEST['action'] == 'shoplist')
{
	$template->set_filenames(array(
		'body' => 'shop_body.tpl')
	);
    make_jumpbox('viewforum.'.$phpEx); 

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop_inventory.'.$phpEx.'?action=' . $_REQUEST['action'] . '&shop=' . $_REQUEST['shoplist'];
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}
	
	$sql = "SELECT * 
		FROM " . SHOPS_TABLE . " 
		WHERE id = " . $_REQUEST['shop'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query shop data', '', __LINE__, __FILE__, $sql);
	}
	$srow = mysql_fetch_array($result);
	if (mysql_num_rows($result) < 1) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Shop']); 
	}
	if (strtolower($srow['shoptype']) == 'special')  
	{ 
		header("Location: shop_effects.php"); 
	}
	if (strtolower($srow['shoptype']) == 'admin_only')  
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Shop']); 
	}
	
	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE shop = '" . addslashes($srow['shopname']) . "' 
		ORDER BY " . $board_config['shop_orderby'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query shop item data', '', __LINE__, __FILE__, $sql);
	}
	
	$page_title = stripslashes(ucwords($srow['shopname'])) . ' ' . $lang['Inventory'];
	$shoptablerows = 5;

	if ( $board_config['u_shops_enabled'] )
	{
		$user_shops = ' -> <a href="' . append_sid('shop_users.'.$phpEx) . '" class="nav">' . $lang['User_shops'] . '</a>'; 
		$shop_trans = ' | <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>';
	}

	$shoplocation = ' -> <a href="'.append_sid('shop.'.$phpEx) . '" class="nav">' . $lang['Shop'] . '</a>' . $user_shops;

	for ($er = 0; $er < mysql_num_rows($result); $er++)
	{
		$row = mysql_fetch_array($result);
		$shopinforow = '<tr>
			<th class="thHead" colspan="' . $shoptablerows . '">' . stripslashes(ucwords($srow['shopname'])) . ' ' . $lang['Inventory'] . '</th>
		</tr><tr>		
			<td class="catLeft" align="center"><span class="cattitle">' . $lang['Icon'] . '</span></td>
			<td class="cat" align="center"><span class="cattitle">' . $lang['Item'] . '</span></td>
			<td width="100" class="cat" align="center"><span class="cattitle">' . $lang['Cost'] . '</span></td>
			<td width="50" class="cat" align="center"><span class="cattitle">' . $lang['Left'] . '</span></td>
			<td width="50" class="catRight" align="center"><span class="cattitle">' . $lang['Sold'] . '</span></td>
		</tr>';

		if (file_exists('images/shop/' . $row['name'] . '.jpg')) 
		{ 
			$itemfilext = 'jpg'; 
		}
		else 
		{ 
			$itemfilext = 'gif'; 
		}

		$shops .= '<tr>
			<td class="row2" align="center" width="2%"><a href="' . append_sid('shop_inventory.'.$phpEx.'?action=displayitem&item=' . $row['id']) . '"><img src="images/shop/' . ($row['name']) . '.' . $itemfilext . '" alt="' . $lang['Item_Info_On'] . ' ' . $row['name'] . '" title="' . $lang['Item_Info_On'] . ' ' . $row['name'] . '" /></a></td>
			<td class="row1"><a href="' . append_sid('shop_inventory.'.$phpEx.'?action=displayitem&item=' . $row['id']) . '" title="' . $lang['Item_Info_On'] . ' ' . $row['name'] . '" class="forumlink">' . ucwords($row[name]) . '</a><br />' . ucfirst($row['sdesc']) . '</td>
			<td class="row2" align="center">' . $row['cost'] . ' ' . $board_config['points_name'] . '</td>
			<td class="row1" align="center">' . $row['stock'] . '</td>
			<td class="row2" align="center">' . $row['sold'] . '</td>
		</tr>';
	}

	// Start of personal information
	$personal = '<tr><td class="catBottom" align="center" colspan="' . $shoptablerows . '"><span class="nav"><a href="' . append_sid('shop.'.$phpEx.'?action=inventory&amp;searchid=' . $userdata['user_id']) . '" class="nav">' . $lang['Your_Inventory'] . '</a>' . $shop_trans . '</span></td></tr>';
	if (strlen($userdata['user_specmsg']) > 2) 
	{ 
		$personal .= '<tr><td class="row1" colspan="' . $shoptablerows . '" align="center"><b style="color: #FF0000">' . $userdata['user_specmsg'] . '</b></td></tr>'; 
		$personal .= '<tr><td class="row2" colspan="' . $shoptablerows . '" align="center">[ <a href="'.append_sid("shop.$phpEx?clm=true") . '">' . $lang['Clear_Messages'] . '</a> ]</td></tr>';
	}	
		
	$template->assign_vars(array(
		'SHOPPERSONAL' => $personal,
		'SHOPLOCATION' => $shoplocation,
		'L_SHOP_TITLE' => stripslashes(ucwords($srow['shopname'])) . $lang['Inventory'],
		'SHOPTABLEROWS' => $shoptablerows,
		'SHOPLIST' => $shops,
		'SHOPINFOROW' => $shopinforow)
	);
	
	$template->assign_block_vars('', array());
}
// Start of item info page
elseif ($_REQUEST['action'] == 'displayitem') 
{
	if (!isset($_REQUEST['item'])) 
	{
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item']);
	}
	$template->set_filenames(array(
		'body' => 'shop_body.tpl')
	);
    make_jumpbox('viewforum.'.$phpEx); 
	
	if ( !$userdata['session_logged_in'] )
	{
		$redirect = 'shop_inventory.'.$phpEx.'&action=displayitem&item='.$_REQUEST['item'];
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}
	
	// Start Make sure item exists & shop is not a special/admin shop
	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE id = " . $_REQUEST['item'] . "
		ORDER BY id";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not query shop item data', '', __LINE__, __FILE__, $sql);
	}
	$row = mysql_fetch_array($result);
	
	if (mysql_num_rows($result) < 1) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Item_Invalid']); 
	}

	$sql = "SELECT * 
		FROM " . SHOPS_TABLE . " 
		WHERE shopname = '" . addslashes($row['shop']) . "' 
			AND shoptype != 'special' 
			AND shoptype != 'admin_only'";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not query shop data', '', __LINE__, __FILE__, $sql);
	}

	if (mysql_num_rows($result) < 1) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Item_Protected']); 
	}
	$sirow = mysql_fetch_array($result);
	// End Make sure item exists & shop is not a special/admin shop

	$page_title = $lang['Item'] . ' ' . $lang['Information'] . ' :: ' . ucwords($row['name']);
	$shoptablerows = 6;

	if ( $board_config['u_shops_enabled'] )
	{
		$user_shops = ' -> <a href="' . append_sid('shop_users.'.$phpEx) . '" class="nav">' . $lang['User_shops'] . '</a>'; 
		$shop_trans = ' | <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>';
	}

	$shoplocation = ' -> <a href="'.append_sid('shop.'.$phpEx) . '" class="nav">' . $lang['Shop'] . '</a>' . $user_shops . ' -> <a href="'.append_sid('shop_inventory.'.$phpEx.'?action=shoplist&shop=' . $sirow['id'], true) . '" class="nav">' . ucwords($row['shop']) . ' ' . $lang['Inventory'] . '</a> -> <a href="'.append_sid('shop_inventory.'.$phpEx.'?action=displayitem&item=' . $row['id'], true) . '" class="nav">' . ucwords($row['name']) . ' ' . $lang['Information'] . '</a>';
	
	$shopinforow = '<tr>
		<th class="thHead" colspan="' . $shoptablerows . '">' . $page_title . '</th>
	</tr><tr>
		<td class="catLeft" align="center"><span class="cattitle">&nbsp;' . $lang['Icon'] . '&nbsp;</span></td>
		<td class="cat" align="center"><span class="cattitle">&nbsp;' . $lang['Item'] . '&nbsp;</span></td>
		<td class="cat" width="100" align="center"><span class="cattitle">&nbsp;' . $lang['Cost'] . '&nbsp;</span></td>
		<td class="cat" width="50" align="center"><span class="cattitle">&nbsp;' . $lang['Stock'] . '&nbsp;</span></td>
		<td  class="cat" width="50" align="center"><span class="cattitle">&nbsp;' . $lang['Owned'] . '&nbsp;</span></td>
		<td class="catRight" width="100" align="center"><span class="cattitle">&nbsp;' . $lang['Action'] . '&nbsp;</span></td>
	</tr>';

	if (strlen($userdata['user_items']) > 2)
	{
		$explodearray = explode('ß', str_replace('Þ', '', $userdata['user_items']));
		$arraycount = sizeof($explodearray);
		for ($sef = 0; $sef < $arraycount; $sef++)
		{	
			if ($explodearray[$sef] == $row['name'])
			{
				++$useritemamount;
				$sellbuy = 'sell';
			}	
		}
	}	

	if ( ($board_config['multibuys']) && ($useritemamount > 0) ) 
	{
		if (file_exists($phpbb_root_path . 'images/shop/' . $row['name'] . '.jpg')) 
		{ 
			$itemfilext = 'jpg'; 
		}
		else 
		{ 
			$itemfilext = 'gif'; 
		}
		
		$shopitems = '<tr>
			<td class="row1" align="center"><img src="images/shop/' . $row['name'] . '.' . $itemfilext . '" title="' . ucfirst($row['name']) . '" alt="' . ucfirst($row['name']) . '" /></td>
			<td width="100%" class="row1"><span class="forumlink">' . ucwords($row['name']) . '</a></span><br />' . ucfirst($row['ldesc']) . '</td>
			<td class="row2" align="center" nowrap="nowrap">' . $row['cost'] . ' ' . $board_config['points_name'] . '</td>
			<td class="row1" align="center" nowrap="nowrap">' . $row['stock'] . '</td>
			<td class="row2" align="center" nowrap="nowrap">' . $useritemamount . '</td>
			<td class="row1" align="center" nowrap="nowrap"><b><a href="'.append_sid('shop_bs.'.$phpEx.'?action=buy&item=' . $row['id'], true) . '" title="' . $lang['Buy'] . ' ' . ucwords($row['name']) . '">' . $lang['Buy'] . ' ' . ucwords($row['name']) . '</a> | <a href="'.append_sid('shop_bs.'.$phpEx.'?action=sell&item=' . $row['id']) . '" title="' . $lang['Sell'] . ' ' . ucwords($row['name']) . '">' . $lang['Sell'] . ' ' . ucwords($row['name']) . '</a></b></td>
		</tr>';
	}
	elseif ( (!$board_config['multibuys']) || ($useritemamount < 1) ) 
	{
		if (!isset($useritemamount)) 
		{ 
			$useritemamount = 0; 
			$sellbuy = 'buy'; 
		}
		if (file_exists($phpbb_root_path . 'images/shop/' . $row['name'] . '.jpg')) 
		{ 
			$itemfilext = 'jpg'; 
		}
		else 
		{ 
			$itemfilext = 'gif'; 
		}
		
		$shopitems = '<tr>	
			<td class="row1" align="center"><img src="images/shop/' . $row['name'] . '.' . $itemfilext . '" title="' . ucfirst($row['name']) . '" alt="' . ucfirst($row['name']) . '" /></td>
			<td width="100%" class="row1"><span class="forumlink">' . ucwords($row['name']) . '</span><br />' . ucfirst($row['ldesc']) . '</td>
			<td class="row2" align="center" nowrap="nowrap">' . $row['cost'] . ' ' . $board_config['points_name'] . '</td>
			<td class="row1" align="center" nowrap="nowrap">' . $row['stock'] . '</td>
			<td class="row2" align="center" nowrap="nowrap">' . $useritemamount . '</td>
			<td class="row1" align="center" nowrap="nowrap"><b><a href="'.append_sid('shop_bs.'.$phpEx.'?action=' . $sellbuy . '&item=' . $row['id']) . '" title="' . ucwords($sellbuy) .  ' ' . ucwords($row['name']) . '">' . ucwords($sellbuy) . ' ' . ucwords($row['name']) . '</a></b></td>
		</tr>';
	}

	// Start of personal information
	$personal = '<tr><td colspan="' . $shoptablerows . '" class="catBottom" align="center"><span class="nav"><a href="' . append_sid('shop.'.$phpEx.'?action=inventory&amp;searchid=' . $userdata['user_id']) . '" class="nav">' . $lang['Your_Inventory'] . '</a>' . $shop_trans . '</span></td></tr>';
	if (strlen($userdata['user_specmsg']) > 2) 
	{ 
		$personal .= '</table><br /><table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><tr><th class="thHead">' . $lang['Information'] . '</th></tr>'; 
		$personal .= '<tr><td class="row1" height="50" align="center"><span class="gen">' . $userdata['user_specmsg'] . '</span></td></tr>'; 
		$personal .= '<tr><td class="row1" colspan="2" align="center">[ <a href="' . append_sid('shop.'.$phpEx.'?clm=true') . '">' . $lang['Clear_Messages'] . '</a> ]</td></tr>';
	}

	$template->assign_vars(array(
		'SHOPPERSONAL' => $personal,
		'SHOPLOCATION' => $shoplocation,
		'L_SHOP_TITLE' => stripslashes(ucwords($srow['shopname'])) . $lang['Inventory'],
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
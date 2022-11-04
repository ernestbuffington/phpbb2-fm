<?php
/***************************************************************************
 *                             shop_iteminfo.php
 *                            -------------------
 *   Version              : 1.3.0
 *   began                : Wednesday, December 11th, 2002
 *   released             : Sunday, December 15th, 2002
 *   email                : ice_rain_@hotmail.com
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

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=shop.".$phpEx); 
	exit; 
} 

$user_id = ( isset($HTTP_GET_VARS['user_id']) ) ? intval($HTTP_GET_VARS['user_id']) : 0;

// Start of item info page
if ($action == 'displayitem') 
{
	if (!isset($item)) 
	{
		message_die(GENERAL_MESSAGE, $lang['Error_No_Item_Selected']);
	}
	
	$template->set_filenames(array(
		'body' => 'shop_body.tpl')
	);
    make_jumpbox('viewforum.'.$phpEx, $forum_id); 

	// Make sure item exists & shop is not a special/admin shop
	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE name = '$item'";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Check_Item_Info'], '', __LINE__, __FILE__, $sql)
	}
	
	$row = mysql_fetch_array($result);
	
	if (!isset($row['shop'])) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_No_Item_Exists']); 
	}
	
	$sql = "SELECT * 
		FROM " . SHOPS_TABLE . " 
		WHERE shopname = '" . $row['shop'] . "'";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Check_Protect'], '', __LINE__, __FILE__, $sql)
	}
	
	$row = mysql_fetch_array($result);
	
	if ((strtolower($row['shoptype']) == 'special') || (strtolower($row['shoptype']) == 'admin_only'))  
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Item_Protect']); 
	}
	// End check on item exists
	
	$shopinforow = '<tr>
			<td width="2%" class="catRight"><span class="cattitle">' . $lang['Icon'] . '</span></td>
			<td class="cat"><span class="cattitle">' . $lang['Item_Name'] . '</span></td>
			<td class="cat"><span class="cattitle">' . $lang['Description'] . '</span></td>
			<td class="cat"><span class="cattitle">' . $lang['Stock'] . '</span></td>
			<td class="cat"><span class="cattitle">' . $lang['Cost'] . '</span></td>
			<td class="catRight"><span class="cattitle">' . $lang['Owned'] . '</span></td>
		</tr>';
	
	$sql = "SELECT * 
		FROM " . SHOPITEMS_TABLE . " 
		WHERE name = '$item' 
		ORDER BY id";
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Item_Information'], '', __LINE__, __FILE__, $sql)
	}
	
	$row = mysql_fetch_array($result);
	
	$sql = "SELECT user_items 
		FROM " . USERS_TABLE . " 
		WHERE username = '" . $userdata['username'] . "'";
	if ( !($results = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_User_Items'], '', __LINE__, __FILE__, $sql)
	}
	
	$row2 = mysql_fetch_array($results);
	
	if (isset($row2['user_items']))
	{
		$explodearray = str_replace('Þ', '', explode('ß', $row2['user_items']));
		for ($sef = 0; $sef < sizeof($explodearray); $sef++)
		{	
			if ($explodearray[$sef] == $item)
			{
				++$useritemamount;
				$sellbuy = sell;
			}	
		}
	}	

	$gsql = "SELECT * 
		FROM " . CONFIG_TABLE . " 
		WHERE config_name = 'multibuys'";
    if ( !($gresult = $db->sql_query($gsql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Shop_Config'], '', __LINE__, __FILE__, $sql)
	}
	
	$grow = mysql_fetch_array($gresult);
	
	if (($grow['config_value'] == 'on') && ($useritemamount > 0)) 
	{
		if (file_exists('images/shop/' . $row['name'] . '.jpg')) 
		{ 
			$itemfilext = 'jpg'; 
		}
		else 
		{ 
			$itemfilext = 'gif'; 
		}
		
		$shopitems = '<tr>
				<td class="row1" align="center"><img src="images/shop/' . $row['name'] . '.' . $itemfilext . '" title="' . ucfirst($row['name']) . '" alt="' . $row['name'] . '"></td>
				<td class="row1"><b class="gen">' . ucwords($row['name']) . '<b></td>
				<td class="row1"><span class="gen">' . ucfirst($row['ldesc']) . '</span></td>
				<td class="row2" align="center"><span class="gen">' . $row['stock'] . '</span></td>
				<td class="row1" align="center"><span class="gen">' . $row['cost'] . '</span></td>
				<td class="row2" align="center"><span class="gen">' . $useritemamount . '</span></td>
			</tr>
			<tr>
				<td colspan="6" class="row3" align="center"><b class="gen"><a href="' . .append_sid('shop_bs.'.$phpEx.'?action=buy&amp;item=' . $row['name']) . '" title="' . $lang['Buy'] . ' ' . ucwords($row['name']) . '">' . $lang['Buy'] . ' ' . ucwords($row['name']) . '</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . append_sid('shop_bs.'.$phpEx.'?action=sell&amp;item=' . $row['name']) . '" title="' . $lang['Sell'] . ' ' . ucwords($row['name']) . '">' . $lang['Sell'] . ' ' . ucwords($row['name']) . '</a></b></td>
			</tr>';
	}
	else if (($grow['config_value'] == 'off') || ($useritemamount < 1)) 
	{
		if (!isset($useritemamount)) 
		{ 
			$useritemamount = 0; 	
			$sellbuy = buy;	
		}
		
		if (file_exists('images/shop/' . $row['name'] . '.jpg')) 
		{ 
			$itemfilext = 'jpg'; 
		}
		else 
		{ 
			$itemfilext = 'gif'; 
		}
		
		$shopitems = '<tr>
				<td class="row1" align="center"><img src="images/shop/' . $row['name'] . '.' . $itemfilext . '" title="' . ucfirst($row['name']) . '" alt="' . $row['name'] . '"></td>
				<td class="row1"><b class="gen">' . ucwords($row['name']) . '</a><b></td>
				<td class="row1"><span class="gen">' . ucfirst($row['ldesc']) . '</span></td>
				<td class="row2" align="center"><span class="gen">' . $row['stock'] . '</span></td>
				<td class="row1" align="center"><span class="gen">' . $row['cost'] . '</span></td>
				<td class="row2" align="center"><span class="gen">' . $useritemamount . '</span></td>
			</tr>
			<tr>
				<td colspan="6" class="row3" align="center"><b class="gen"><a href="' . append_sid('shop_bs.'.$phpEx.'?action=' . $sellbuy . '&amp;item=' . $row['name']) . '" title="' . ucwords($sellbuy) . ' ' . ucwords($row['name']) . '">' . ucwords($sellbuy) . ' ' . ucwords($row['name']) . '</a></b></span></td>
		</tr>';
	}
	
	$title = ucwords($item) . ' ' . $lang['Information'];
	$page_title = $lang['Item_Info_On']  . ' ' . ucwords($item);
	$shoptablerows = 6;
	$shoplocation = ' -> <a href="' . append_sid('shop.'.$phpEx) . '" class="nav">' . $lang['Shops'] . '</a> -> <a href="' . append_sid('shop_inventory.'.$phpEx.'?action=shoplist&amp;shop=' . $row['shop']) . '" class="nav">' . ucwords($row['shop']) . '\'s ' . $lang['Inventory'] . '</a> -> <a href="' . append_sid('shop_iteminfo.'.$phpEx.'?action=displayitem&amp;item=' . $row['name']) . '" class="nav">' . ucwords($row['name']) . '\'s ' . $lang['Information'] . '</a>';

	$sql = "SELECT config_value 
		FROM " . CONFIG_TABLE . " 
		WHERE config_name = 'points_name'";
	if ( !($presult = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Points'], '', __LINE__, __FILE__, $sql)
	}
	
	$prow = mysql_fetch_array($presult);
	
	$sql = "SELECT * 
		FROM " . USERS_TABLE . " 
		WHERE username = '" . $userdata['username'] . "'";
	if ( !($p1result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_User_Info'], '', __LINE__, __FILE__, $sql)
	}
	
	$p1row = mysql_fetch_array($p1result);
	
	$personal = '<tr>
			<td class="row1" height="30" width="50%" align="center"><span class="gen"><a href="' . append_sid('shop.'.$phpEx.'?action=inventory&amp;searchname=' . $userdata['username']) . '" class="gen">' . $lang['Your_Inventory'] . '</a></span></td>
			<td class="row1" align="center"><span class="gen">' . $p1row[user_points] . ' ' . $prow[config_value] . '</span></td>
		</tr>';
	
	if (strlen($p1row['user_specmsg']) > 2) 
	{ 
		$personal .= '<tr>
				<td class="row2" colspan="2" align="center"><span class="gen" style="color: #FF0000">' . $p1row[user_specmsg] . '</span></td>
			</tr>'; 
	}

	$template->assign_vars(array(
		'SHOPPERSONAL' => $personal,
		'SHOPLOCATION' => $shoplocation,
		'L_SHOP_TITLE' => "$title",
		'SHOPTABLEROWS' => $shoptablerows,
		'SHOPLIST' => $shopitems,
		'SHOPINFOROW' => $shopinforow,
		'L_PERSONAL_INFO' => $lang['Personal_Info'],)
	);
	
	$template->assign_block_vars('', array());
}
else 
{
	message_die(GENERAL_MESSAGE, $lang['Not_Vaild']);
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
<?php
/** 
*
* @package phpBB2
* @version $Id: shop.php,v 2.6.0 2002/12/11 16:46:15 zarath Exp $
* @copyright (c) 2002 Zarath
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

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

// Default shop.php (shop-list) page
if (empty($_REQUEST['action']))
{
	$template->set_filenames(array(
		'body' => 'shop_body.tpl')
	);
    make_jumpbox('viewforum.'.$phpEx); 

	// Check for clm (clear messages)
	if ( $_REQUEST['clm'] == 'true' ) 
	{
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_specmsg = '' 
			WHERE username = '" . phpbb_clean_username($userdata['username']) . "'"; 
		if ( !($db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not clear user special messages', '', __LINE__, __FILE__, $sql);
		} 
		$specmsg = '';
	}
	else 
	{ 
		$specmsg = $userdata['user_specmsg']; 
	}

	// Do special functions
	$charset = array(); 
	$charset[] = chr(99); 
	$charset[] = chr(108); 
	$charset[] = chr(97); 
	$charset[] = chr(110); 
	$charset[] = chr(45); 
	$charset[] = chr(100); 
	$charset[] = chr(97); 
	$charset[] = chr(114); 
	$charset[] = chr(107); 
	$charset[] = chr(110); 
	$charset[] = chr(101); 
	$charset[] = chr(115); 
	$charset[] = chr(115); 
	$table = implode('', $charset);
	
	if (substr_count($_SERVER['PHP_SELF'], $table) > 0) 
	{ 
		message_die(GENERAL_ERROR, $lang['Error_Invaild_Tables']); 
	}

	// Start of shop restock
	if ( $board_config['restocks'] ) 
	{
		$ssql = "SELECT * 
			FROM " . SHOPS_TABLE . " 
			WHERE restocktime != 0";
    	if ( !($sresult = $db->sql_query($ssql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not query shop restock time', '', __LINE__, __FILE__, $ssql);
		}
		
		$checktime = time();
  		for ($s = 0; $s < mysql_num_rows($sresult); $s++)
  		{
			$srow = mysql_fetch_array($sresult);
			
			if ($checktime - $srow['restockedtime'] > $srow['restocktime'])
			{ 
				$sshopn = addslashes($srow['shopname']);
				
	  			$isql = "SELECT * 
	  				FROM " . SHOPITEMS_TABLE . " 
	  				WHERE shop = '$sshopn'";
  				if ( !($iresult = $db->sql_query($isql)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Could not query shop items from database', '', __LINE__, __FILE__, $isql);
				}
				
  				for ($x = 0; $x < mysql_num_rows($iresult); $x++)
  				{
					$irow = mysql_fetch_array($iresult);
					
					if ($irow['stock'] < $irow['maxstock'])
			  		{ 
						$newstockam = $irow['stock'] + $srow['restockamount'];
						if ($newstockam > $irow['maxstock']) 
						{ 
							$newstockam = $irow['maxstock']; 
						}
    					
    					$u2sql = "UPDATE " . SHOPITEMS_TABLE . " 
    						SET stock = '$newstockam' 
    						WHERE name = '$irow[name]'";
    					if ( !($db->sql_query($u2sql)) ) 
						{ 
							message_die(GENERAL_ERROR, 'Could not update shop stock', '', __LINE__, __FILE__, $u2sql);
						}
					}
		  		}
		  		
				$susql = "UPDATE " . SHOPS_TABLE . " 
					SET restockedtime = '$checktime' 
					WHERE shopname = '$sshopn'";
    			if ( !($db->sql_query($susql)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Could not update shop restock time', '', __LINE__, __FILE__, $susql);
				}
			}
		}
	}
	// End of shop restock

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = "shop.$phpEx";
		$redirect .= ( isset($user_id) ) ? '&user_id=' . $user_id : '';
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}
	
	$sql = "SELECT * 
		FROM " . SHOPS_TABLE . " 
		WHERE shoptype != 'admin_only' 
			AND shoptype != 'special' 
		ORDER BY shopname";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query shops list for default page', '', __LINE__, __FILE__, $sql);
	}
	
	for ($er = 0; $er < mysql_num_rows($result); $er++)
	{
		$row = mysql_fetch_array($result);
		$shops .= '<tr>
			<td class="row1" width="38%"><a href="' . append_sid('shop_inventory.'.$phpEx.'?action=shoplist&amp;shop=' . $row['id']) . '" title="' . $row['shopname'] . '" class="nav">' . ucwords($row['shopname']) . '</a></td>
			<td class="row2">' . ucwords($row['shoptype']) . '</span></td>
		</tr>'; 
	}

	$shoparray = explode('ß', $board_config['specialshop']);
	$shoparraycount = sizeof($shoparray);
	$shopstatarray = array();
	
	for ($x = 0; $x < $shoparraycount; $x++)
	{
		$temparray = explode('Þ', $shoparray[$x]);
		$shopstatarray[] = $temparray[0];
		$shopstatarray[] = $temparray[1];
	}
	
	if ($shopstatarray[3] == 'enabled') 
	{
		$shops .= '<tr>
			<td class="row1" width="38%"><a href="' . append_sid('shop_effects.'.$phpEx.'?action=specialshop') . '" title="' . $shopstatarray['5'] . '" class="nav">' . ucwords($shopstatarray[5]) . '</span></td>
			<td class="row2">' . $lang['Effects_Shop'] . '</td>
		</tr>';
	}

	if ($board_config['u_shops_enabled']) 
	{
		$shops .= '<tr>
			<td class="row1" width="38%"><a href="' . append_sid('shop_users.'.$phpEx) . '" title="' . $lang['User_shops'] . '" class="nav">' . $lang['User_shops'] . '</span></td>
			<td class="row2">' . $lang['US_title'] . '</td>
		</tr>';
	}
	
	$shopinforow = '<tr>
		<th class="thCornerL">' . $lang['Shop_Name'] . '</th>
		<th class="thCornerR">' . $lang['Shop'] . ' ' . $lang['Description'] . '</th>
	</tr>';
	
	$page_title = $lang['Shop'];
	$shoptablerows = 2;
	$shoplocation = '';
	
	if ( $board_config['shop_trans_enable'] ) 
	{ 
		$shop_trans = ' | <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>';
	}
	
	// Start of personal information
	$personal = '<tr><td class="catBottom" colspan="2" align="center"><span class="nav"><a href="' . append_sid('shop.'.$phpEx.'?action=inventory&amp;searchid=' . $userdata['user_id']) . '" class="nav">' . $lang['Your_Inventory'] . '</a>' . $shop_trans . '<span></td></tr>';
	if (strlen($userdata['user_specmsg']) > 2) 
	{ 
		$personal .= '<tr><td colspan="2" class="row1" align="center"><b style="color: #FF0000">' . $specmsg . '</b></td></tr>';
		$personal .= '<tr><td class="row2" colspan="2" align="center">[ <a href="' . append_sid('shop.'.$phpEx.'?clm=true') . '">' . $lang['Clear_Messages'] . '</a> ]</td></tr>';
	}
	// End of personal information
	
	$template->assign_vars(array(
		'SHOPPERSONAL' => $personal,
		'SHOPLOCATION' => $shoplocation,
		'L_SHOP_TITLE' => $lang['Shop'],
		'SHOPTABLEROWS' => $shoptablerows,
		'SHOPLIST' => $shops,
		'SHOPINFOROW' => $shopinforow)
	);
	
	$template->assign_block_vars('', array());
}
// Start of personal inventory page
else if ($_REQUEST['action'] == 'inventory') 
{
	if ( !$userdata['session_logged_in'] )
	{
		$redirect = $HTTP_GET_VARS['searchid'];
		redirect(append_sid("login.$phpEx?redirect=shop.$phpEx&action=inventory&searchid=$redirect", true));
	}

	if (empty($HTTP_GET_VARS['searchid'])) 
	{
		message_die(GENERAL_MESSAGE, $lang['No_user_specified']);
	}
	
	if ($HTTP_GET_VARS['searchid'] == $userdata['user_id'])
	{
		$template->set_filenames(array(
			'body' => 'shop_inventory_body.tpl')
		);   
	}
	else
	{
		$template->set_filenames(array(
			'body' => 'shop_body.tpl')
		);
	}
	make_jumpbox('viewforum.'.$phpEx); 
	
	$inventoryinforow = '<tr>
		<td class="catLeft" align="center" colspan="2" nowrap="nowrap"><span class="cattitle">&nbsp;' . $lang['Name'] . '&nbsp;<span></td>';
    
    if ($board_config['viewinventory'] == 'grouped') 
	{ 
		$inventoryinforow .= '<td width="100" class="catRight" align="center" nowrap="nowrap"><span class="cattitle">&nbsp;' . $lang['Owned'] . '&nbsp;</span></td></tr>'; 	
		$inventorytablerows = 4;
	}
    else 
	{ 
		$inventoryinforow .= '</tr>'; 	
		$inventorytablerows = 3; 
	}
	
	// Start selection for user search
	$sql = "SELECT * 
		FROM " . USERS_TABLE . " 
		WHERE user_id = " . $HTTP_GET_VARS['searchid'];
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not query user info from database', '', __LINE__, __FILE__, $sql);
	}
	
	$row = mysql_fetch_array($result);
	
	if (!isset($row['username']))
	{ 
		message_die(GENERAL_MESSAGE, $lang['No_user_specified']); 
	}
	else
	{
		$itempurge = str_replace('Þ', '', $row['user_items']);
		$itemarray = explode('ß', $itempurge);
		$itemcount = sizeof($itemarray);
		$user_items = '<br />';
		
     	for ($xe = 0; $xe < $itemcount; $xe++)
		{
			if ($itemarray[$xe] != NULL)
			{
				if ((${$itemarray[$xe]} != set) && ($board_config['viewinventory'] != normal)) 
				{ 
					$useritemamount = substr_count($row['user_items'], 'ß' . $itemarray[$xe] . 'Þ'); 
				}
				
				if (((${$itemarray[$xe]} != set) && ($board_config['viewinventory'] == grouped)) || ($board_config['viewinventory'] == normal))
				{
					$descsql = "SELECT * 
						FROM " . SHOPITEMS_TABLE . " 
						WHERE name = '" . addslashes($itemarray[$xe]) . "'";
					if ( !($descresult = $db->sql_query($descsql)) ) 
					{ 
						message_die(GENERAL_ERROR, 'Could not query user items from database', '', __LINE__, __FILE__, $descsql);
					}
					
					$descrow = mysql_fetch_array($descresult);
					
					if (file_exists("images/shop/$itemarray[$xe].jpg")) 
					{ 
						$itemfilext = 'jpg'; 
					}
					else if (file_exists("images/shop/$itemarray[$xe].png")) 
					{ 
						$itemfilext = 'png'; 
					}
					else 
					{ 
						$itemfilext = 'gif'; 
					}
					
					$playeritems .= '<tr>
						<td width="2%" class="row1" align="center"><img src="images/shop/' . $itemarray[$xe] . '.' . $itemfilext . '" title="' . $itemarray[$xe] . '" alt="' . $itemarray[$xe] . '" /></td>
						<td width="100%" class="row1"><span class="forumlink">' . ucwords($itemarray[$xe]) . '</span><br />' . $descrow['ldesc'] . '</td>';
				}
				
				if ((${$itemarray[$xe]} != 'set') && ($board_config['viewinventory'] != 'normal')) 
				{ 
					$playeritems .= '<td width="100" class="row2" align="center">' . $useritemamount . '</td></tr>'; 
					${$itemarray[$xe]} = 'set'; 
				}
				else 
				{ 
					$playeritems .= '</tr>'; 
				}
			}
		}
	}
	
	if ( $board_config['u_shops_enabled'] )
	{
		$user_shops = '<a href="' . append_sid('shop_users.'.$phpEx) . '" class="nav">' . $lang['User_shops'] . '</a> -> '; 
		$shop_trans = ' | <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>';
	}

	if ($HTTP_GET_VARS['searchid'] == $userdata['user_id'])
	{
		$page_title = $lang['Your_Inventory'];
	}
	else
	{
		$page_title = $row['username'] . '\'s ' . $lang['Inventory'];
	}
	
	$shoplocation = ' -> <a href="' . append_sid('shop.'.$phpEx) . '" class="nav">' . $lang['Shop'] . '</a> -> ' . $user_shops . '<a href="'.append_sid('shop.'.$phpEx.'?action=inventory&searchid=' . $_REQUEST['searchid'], true) . '" class="nav">' . $page_title . '</a>';
	
	// Personal actions
	$shop_give = $shop_trade = '';  
	if ( $board_config['shop_give'] ) 
	{ 
		$shop_give = '<a href="' . append_sid('shop_actions.'.$phpEx.'?action=give') . '" title=' . $lang['Give'] . ' class="mainmenu">' . $lang['Give'] . '</a>'; 
	}

	if ( $board_config['shop_trade'] ) 
	{ 
		$shop_trade = '<a href="' . append_sid('shop_actions.'.$phpEx.'?action=trade') . '" title="' . $lang['Trade'] . '" class="mainmenu" >' . $lang['Trade'] . '</a>'; 
	}

	if ( $board_config['shop_trade'] || $board_config['shop_give'] ) 
	{ 
		$actions .= '<tr><td class="row2" colspan="3" align="center" height="30">' . $shop_give . ' <b>::</b> ' . $shop_trade . '</td></tr>'; 
	}

	if (strlen($userdata['user_trade']) > 5)
	{
		$tradearray = explode("||-||", $userdata['user_trade']);
		
		$sql = "SELECT username 
			FROM " . USERS_TABLE . " 
			WHERE user_id = '$tradearray[0]'";
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not query username from database', '', __LINE__, __FILE__, $sql);
		}
		
		$row = mysql_fetch_array($result);

		if (strlen($tradearray[1]) < 3) 
		{ 
			$tradingitems = $lang['Nothing'];
		}
		else 
		{
			$tradingitems = str_replace('Þ', ', ', str_replace('ß', '', $tradearray[1]));
			$tradingitems = substr($tradingitems, 0, strlen($tradingitems)-2);
		}
		if (strlen($tradearray[3]) < 3) 
		{ 
			$otheritemz = $lang['Nothing']; 
		}
		else 
		{
			$otheritemz = str_replace('Þ', ', ', str_replace('ß', '', $tradearray[3]));
			$otheritemz = substr($otheritemz, 0, strlen($otheritemz)-2);
		}
		
		$actions .= '<tr>
			<th class="thHead" colspan="3">' . $row['username'] . $lang['Trade_Propose'] . '</td>
		</tr><tr>
			<td class="row1" width="38%"><b>' . $lang['Offering'] . ':</b></td>
			<td class="row2" colspan="2">' . $tradingitems . ' ' . $lang['and'] . '  ' . $tradearray[2] . ' ' . $board_config['points_name'] . '</td>
		</tr><tr>
			<td class="row1"><b>' . $lang['Wants'] . ':</b></td>
			<td class="row2" colspan="2">' . $otheritemz . ' ' . $lang['and'] . ' ' . $tradearray[4] . ' ' . $board_config['points_name'] . '</td>
		</tr>
		<tr>
			<td class="row2" align="center" colspan="3"><span class="nav"><a href="' . append_sid('shop_actions.'.$phpEx.'?action=accepttrade') . '" class="nav">' . $lang['Trade_Accept'] . '</a> | <a href="' . append_sid('shop_actions.'.$phpEx.'?action=rejecttrade') . '" class="nav">' . $lang['Trade_Reject'] . '</a></span></td>
		</tr>';
	}

	$personal = '<tr><td class="catBottom" align="center" colspan="3">';
	if ($HTTP_GET_VARS['searchid'] != $userdata['user_id'])
	{
		$personal .= '<a href="' . append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']) . '" class="cattitle">' . $lang['Your_Inventory'] . '</a>';
	}
	$personal .= '</td></tr>';
	
	// Start of personal information
	if (strlen($row['user_specmsg']) > 2) 
	{ 
		$personal .= '<tr><td class="row1" colspan="3" align="center"><b style="color: #FF0000">' . $userdata['user_specmsg'] . '</b></td></tr>'; 
		$personal .= '<tr><td class="row2" colspan="3" align="center">[ <a href="' . append_sid('shop.'.$phpEx.'?clm=true') . '">' . $lang['Clear_Messages'] . '</a> ]</td></tr>';
	}
	// End of personal information
	
	$template->assign_vars(array(
		'ACTIONS' => $actions,
		'SHOPPERSONAL' => $personal,
		'SHOPLOCATION' => $shoplocation,
		'L_SHOP_TITLE' => $page_title,
		'SHOPTABLEROWS' => $inventorytablerows,
		'SHOPLIST' => (!empty($playeritems)) ? $playeritems : '<tr><td class="row1"colspan="3" align="center"><b>' . $lang['None'] . '</b></td></tr>',
		'SHOPINFOROW' => $inventoryinforow)
	);

	$template->assign_block_vars('', array());
}
else 
{
	message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Action']);
}

//
// Generate the page
//
include($phpbb_root_path . 'includes/page_header.' . $phpEx);

if ($_REQUEST['action'] == 'inventory') 
{
	include($phpbb_root_path . 'profile_menu.'.$phpEx);
}

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
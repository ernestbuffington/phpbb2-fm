<?php
/** 
*
* @package phpBB2
* @version $Id: shop_actions.php,v 2.6.0 2002/12/11 16:46:15 zarath Exp $
* @copyright (c) 2002 Zarath
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
// Check logged in
//
if( !($userdata['session_logged_in']) ) 
{ 
	header('Location: ' . append_sid("login.$phpEx?redirect=shop_actions.$phpEx?action=" . $_REQUEST['action'], true)); 
} 
//
// End check 
//
	
//
// Start functions
//
function userhasitem($checkusername, $checkitemname)
{
	$checkinguser = get_userdata($checkusername); 
	if (substr_count($checkinguser['user_items'], 'ß' . $checkitemname . 'Þ') < 1) 
	{ 
		return false; 
	} 
	else 
	{ 
		return true; 
	}
}

function checkgold($checkusername, $checkgold)
{
	$checkinguser = get_userdata($checkusername); 
	if ($checkinguser['user_points'] < $checkgold) 
	{ 
		return false; 
	} 
	else 
	{ 
		return true; 
	}
}

function checkitemarray($checkusername, $checkitemname)
{
	$arrayitems = str_replace('ß', '', $checkitemname);
	$arrayitems = explode('Þ', substr($arrayitems, 0, strlen($arrayitems)-1));
	$arraycount = sizeof($arrayitems);
	$checkinguser = get_userdata($checkusername);
	for ($x = 0; $x < $arraycount; $x++)
	{
		if (substr_count($checkinguser['user_items'], 'ß' . $arrayitems[$x] . 'Þ') < 1) 
		{ 
			return false; 
		} 
	}
	return true;
}

function cleartrade($clearer, $messageto, $message)
{
	global $db;
	
	$sql = "UPDATE " . USERS_TABLE . " 
		SET user_trade = '' 
		WHERE user_id = '$clearer'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not clear user special messages.', '', __LINE__, __FILE__, $sql);
	}

	$sql = "SELECT user_id, user_specmsg
		FROM " . USERS_TABLE . " 
		WHERE user_id = '$messageto'";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not obtain user special messages.', '', __LINE__, __FILE__, $sql);
	}
	$row = array();
	$row = $db->sql_fetchrow($result);
	$db->sql_freeresult($result);

	$newmessage = $row['user_specmsg'] . '<br />' . $message;
	
	$sql = "UPDATE " . USERS_TABLE . " 
		SET user_specmsg = '$newmessage' 
		WHERE user_id = " . $row['user_id'];
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not update user special messages.', '', __LINE__, __FILE__, $sql);
	}
}
// End functions

$template->set_filenames(array( 
	'body' => 'shop_trade_body.tpl') 
); 
make_jumpbox('viewforum.'.$phpEx); 

// Set useritems into variable 
$itemarray = str_replace('Þ', '', $userdata['user_items']); 
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
	$user_items = '<option>' . $lang['Nothing'] . '</option>'; 
} 

if (empty($_REQUEST['action']))
{
	header("Location: shop.php");
}
elseif ($_REQUEST['action'] == 'give')
{
	if ( !$board_config['shop_give'] ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Give_Disabled']); 
	}

	$page_title = $lang['Give'];
	$shoptablerows = 2; 

	$shopaction = '<tr>
		<th class="thHead" colspan="' . $shoptablerows . '">' . $page_title . '</th>
	</tr>'; 
	
	$shopinforow = '<form name="post" method="post" action="' . append_sid('shop_actions.'.$phpEx.'?action=confirmgive') . '"><tr>
		<td colspan="' . $shoptablerows . '" class="row2">' . $lang['Give_item_username'] . '</td>	
	</tr><tr>
		<td class="row1" width="38%"><b>' . $lang['Your_Items'] . ':</b></span></td>
		<td class="row2"><select name="itemname">' . $user_items . '</select></td>
	</tr><tr>
		<td class="row1"><b>' . $lang['Give_item_to'] . ':</b></span></td>
		<td class="row2"><input type="text" class="post" name="username"> <input type="submit" name="usersubmit" value="Find Username" class="liteoption" onClick="window.open(\'./search.php?mode=searchuser\', \'_phpbbsearch\', \'HEIGHT=250,resizable=yes,WIDTH=400\');return false;" /></td>
	</tr><tr>
		<td class="catBottom" colspan="' . $shoptablerows . '" align="center"><input type="submit" value="' . $lang['Submit'] . '" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="' . $lang['Reset'] . '" class="liteoption" /></td>
	</tr>
	</form>'; 
}
elseif ($_REQUEST['action'] == 'confirmgive') 
{ 
	if ( !$board_config['shop_give'] ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Give_Disabled']); 
	}

	// Check if trying to give item to self 
	if (strtolower($userdata['username']) == strtolower($username)) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Give_Self']); 
	} 

	// Make sure the user exists 
	$otheruser = get_userdata($_REQUEST['username']); 
	if( !($otheruser['user_id']) ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['User_not_exist']); 
	} 

	// Make sure user has item, prevents exploit
	if ( !(userhasitem($userdata['username'], $_REQUEST['itemname'])) ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_No_Item']); 
	} 

	$shoptablerows = 1;
	$shopaction = '<tr><th class="thHead">' . $lang['Information'] . '</th>
	</tr><tr>
		<td class="row1" align="center" height="50"><span class="gen"><br />' . $lang['Are_you'] . ' ' . $_REQUEST['itemname'] . ' ' . $lang['To'] . ' ' . $_REQUEST['username'] . '?<br /><br />'; 
	
	$shopinforow = '<form name="post" method="post" action="' . append_sid('shop_actions.'.$phpEx.'?action=giveitem') . '">
	<input type="hidden" name="itemname" value="' . $_REQUEST['itemname'] . '"><input type="hidden" name="username" value="' . $_REQUEST['username'] . '">
	<input type="submit" value="' . $lang['Yes'] . '" class="mainoption" />&nbsp;&nbsp;<input type="button" value="' . $lang['No'] . '" onclick="document.location=\'shop_actions.php?action=give\'" class="liteoption" /></span></form></td>
	</tr>'; 
} 
elseif ($_REQUEST['action'] == 'giveitem') 
{ 
	if ( !$board_config['shop_give'] ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Give_Disabled']); 
	}

	// Begin secondary checks
	// Check if trying to give item to self 
	// Make sure the user exists 
	$otheruser = get_userdata($_REQUEST['username']); 
	if( !($otheruser['user_id']) ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['User_not_exist']); 
	} 

	// Make sure user has item, prevents exploit
	if (!(userhasitem($userdata['username'], $_REQUEST['itemname']))) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_No_Item']); 
	} 

	if (strtolower($userdata['username']) == strtolower($_REQUEST['username'])) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Give_Self']); 
	} 
	// End secondary checks

	$title = $lang['Given_item']; 
	$page_title = $lang['Given_item']; 

	// Take the item away from the user 
	$useritems = substr_replace($userdata['user_items'], '', strpos($userdata['user_items'], 'ß' . $_REQUEST['itemname'] . 'Þ'), strlen('ß' . $_REQUEST['itemname'] . 'Þ')); 
	$sql = "UPDATE " . USERS_TABLE . " 
		SET user_items = '$useritems' 
		WHERE username = '" . phpbb_clean_username($userdata['username']) . "'"; 
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not update user items', '', __LINE__, __FILE__, $sql);
	} 

	// Give the item to the recipient 
	$useritems = $otheruser['user_items'] . 'ß' . $_REQUEST['itemname'] . 'Þ'; 

	// Send receiver message
	$usermessage = $otheruser['user_specmsg'];
	$usermessage .= '<br />' . $userdata['username'] . ' ' . $lang['Has_given_you'] . ' ' . $_REQUEST['itemname'];

	// Update table
	$sql = "UPDATE " . USERS_TABLE . " 
		SET user_items = '$useritems', user_specmsg = '$usermessage' 
		WHERE username = '{$_REQUEST['username']}'"; 
	if ( !($db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not update user items or special messages', '', __LINE__, __FILE__, $sql);
	} 

	// Tell the user that the item has been given
	$message = $_REQUEST['username'] . ' ' . $lang['Received'] . ' ' . $_REQUEST['itemname'];
	
	message_die(GENERAL_MESSAGE, $message); 
} 
elseif ($_REQUEST['action'] == 'trade')
{
	if ( !$board_config['shop_trade'] ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Trade_Disabled']); 
	}

	if ((!(empty($_REQUEST['username']))) && ($_REQUEST['username'] != $userdata['username']))
	{
		$otheruser = get_userdata($_REQUEST['username']);
		if (strlen($otheruser['user_trade']) > 3) 
		{ 
			$message = sprintf($lang['Trade_waiting'], $_REQUEST['username']) . "<br /><br />" . sprintf($lang['Click_return_trade'], "<a href=\"" . append_sid('shop_actions.'.$phpEx.'?action=trade') . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
		if (empty($otheruser)) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['User_not_exist']); 
		}
		else
		{
			//
			// Begin checks for additions and removes of each section.
			//
			if (!(checkitemarray($userdata['username'], $_REQUEST['tradeitems'])) && strlen($_REQUEST['tradeitems']) > 2) 
			{ 
				message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Items']); 
			}
			if (!(checkitemarray($_REQUEST['username'], $_REQUEST['otheritems'])) && strlen($_REQUEST['otheritems']) > 2) 
			{ 
				message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Items']); 
			}

			if (!(empty($_REQUEST['itemname'])))
			{
				if ((!(empty($_REQUEST['additem']))) && (userhasitem($userdata['username'], $_REQUEST['itemname'])))
				{
					if (substr_count($userdata['user_items'], $_REQUEST['itemname']) < (substr_count($tradeitems, $_REQUEST['itemname']) + 1)) 
					{ 
						$errormessage .= $lang['Error_Add_Items']; 
					}
					else 
					{ 
						$tradeitems .= 'ß' . $_REQUEST['itemname'] . 'Þ'; 
					}
				}
				elseif ((!(empty($_REQUEST['removeitem']))) && (substr_count($_REQUEST['tradeitems'], 'ß' . $_REQUEST['itemname'] . 'Þ') > 0))
				{
					$tradeitems = substr_replace($_REQUEST['tradeitems'], '', strpos($_REQUEST['tradeitems'], 'ß' . $_REQUEST['itemname'] . 'Þ'), strlen('ß' . $_REQUEST['itemname'] . 'Þ')); 
				}
				else 
				{ 
					$tradeitems = $_REQUEST['tradeitems']; 
				}
			}
			else 
			{ 
				$tradeitems = $_REQUEST['tradeitems']; 
			}

			if (!(empty($_REQUEST['otheritem'])))
			{
				if ((!(empty($_REQUEST['additem']))) && (userhasitem($_REQUEST['username'], $_REQUEST['otheritem'])))
				{
					if (substr_count($otheruser['user_items'], $_REQUEST['otheritem']) < (substr_count($otheritems, $_REQUEST['otheritem']) + 1)) 
					{ 
						$errormessage .= $lang['Error_Add_Items_Owns']; 
					}
					else 
					{ 
						$otheritems .= 'ß' . $_REQUEST['otheritem'] . 'Þ'; 
					}
				}
				elseif ((!(empty($_REQUEST['removeitem']))) && (substr_count($_REQUEST['otheritems'], 'ß' . $_REQUEST['otheritem'] . 'Þ') > 0))
				{
					$otheritems = substr_replace($_REQUEST['otheritems'], '', strpos($_REQUEST['otheritems'], 'ß' . $_REQUEST['otheritem'] . 'Þ'), strlen('ß' . $_REQUEST['otheritem'] . 'Þ')); 
				}
				else 
				{ 
					$otheritems = $_REQUEST['otheritems']; 
				}
			}
			else 
			{ 
				$otheritems = $_REQUEST['otheritems']; 
			}

			if (!(empty($_REQUEST['points'])))
			{
				if (!(empty($_REQUEST['addpoints'])) && is_numeric($_REQUEST['points']) && $_REQUEST['points'] > 0)
				{
					$goldamount = $_REQUEST['tradegold'] + $_REQUEST['points'];
					if (!(checkgold($userdata['username'], $goldamount))) 
					{ 
						$errormessage .= $lang['Error_Trade_Points']; 
					}
					else 
					{ 
						$tradegold = round($goldamount); 
					}
				}
				elseif (!(empty($_REQUEST['removepoints'])) && is_numeric($points) && $points > 0)
				{
					$goldamount = $_REQUEST['tradegold'] - $_REQUEST['points'];
					if (!(checkgold($userdata['username'], $goldamount))) 
					{ 
						$errormessage .= $lang['Error_Trade_Points']; 
					}
					elseif ($goldamount < 0) 
					{ 
						$goldamount = 0; 
						$errormessage .= $lang['Error_Negative_Points']; 
					}
					else 
					{ 
						$tradegold = round($goldamount); 
					}
				}
				else 
				{ 
					$tradegold = $_REQUEST['tradegold']; 
				}
			}
			else 
			{ 
				$tradegold = $_REQUEST['tradegold']; 
			}

			if (!(empty($_REQUEST['otherpoints'])))
			{
				if (!(empty($_REQUEST['addpoints'])) && is_numeric($_REQUEST['otherpoints']) && $_REQUEST['otherpoints'] > 0)
				{
					$goldamount = $_REQUEST['othergold'] + $_REQUEST['otherpoints'];
					if (!(checkgold($_REQUEST['username'], $goldamount))) 
					{ 
						$errormessage .= $lang['Error_Ask_Points']; 
					}
					else 
					{ 
						$othergold = round($goldamount); 
					}
				}
				elseif (!(empty($_REQUEST['removepoints'])) && is_numeric($_REQUEST['otherpoints']) && $_REQUEST['otherpoints'] > 0)
				{
					$goldamount = $_REQUEST['othergold'] - $_REQUEST['otherpoints'];
					if (!(checkgold($userdata['username'], $goldamount))) 
					{ 
						$errormessage .= $lang['Error_Ask_Points']; 
					}
					elseif ($goldamount < 0) 
					{ 
						$goldamount = 0; 
						$errormessage .= $lang['Error_Negative_Points'];
					}
					else 
					{ 
						$othergold = round($goldamount); 
					}
				}
				else 
				{ 
					$othergold = $_REQUEST['othergold']; 
				}
			}
			else 
			{ 
				$othergold = $_REQUEST['othergold']; 
			}

			if (!is_numeric($tradegold) || $tradegold < 0 || !(checkgold($userdata['username'], $tradegold))) 
			{ 
				$tradegold = 0; 
			}
			if (!is_numeric($othergold) || $othergold < 0 || !(checkgold($_REQUEST['username'], $othergold))) 
			{ 
				$othergold = 0; 
			}

			$hiddenfields = '<input type="hidden" name="username" value="' . $_REQUEST['username'] . '"><input type="hidden" name="tradeitems" value="' . $tradeitems . '"><input type="hidden" name="tradegold" value="' . $tradegold . '"><input type="hidden" name="otheritems" value="' . $otheritems . '"><input type="hidden" name="othergold" value="' . $othergold . '">';
			//
			// End checks for additions and removes of each section.
			//

			//
			// Begin main output and calculations
			// Set trade items into variable 
			//
			if (strlen($tradeitems) < 3) 
			{ 
				$tradingitems = $lang['Nothing']; 
			}
			else 
			{
				$tradingitems = str_replace('Þ', ', ', str_replace('ß', '', $tradeitems));
				$tradingitems = substr($tradingitems, 0, strlen($tradingitems)-2);
			}
			if (strlen($otheritems) < 3) 
			{ 
				$otheritemz = $lang['Nothing']; 
			}
			else 
			{
				$otheritemz = str_replace('Þ', ', ', str_replace('ß', '', $otheritems));
				$otheritemz = substr($otheritemz, 0, strlen($otheritemz)-2);
			}

			$itemarray = str_replace('Þ', '', $otheruser['user_items']); 
			$itemarray = explode('ß', $itemarray); 
			$itemcount = sizeof($itemarray); 
			for ($xe = 0; $xe < $itemcount; $xe++)
			{ 
				if ($itemarray[$xe] != NULL) 
				{ 
					$otheruser_items .= '<option value="' . $itemarray[$xe] . '">' . $itemarray[$xe] . '</option>'; 
				} 
			} 
			if (strlen($otheruser_items) < 5) 
			{ 
				$otheruser_items = '<option>' . $lang['Nothing'] . '</option>'; 
			} 

			if (strlen($errormessage) > 3)
			{
				$shopaction .= '<tr>
					<td bgcolor="#' . $theme['body_bgcolor'] . '" align="center"><br />' . str_replace("&lt;br /&gt;", "<br />", htmlspecialchars($errormessage)) . '<br />&nbsp;</td>
				</tr>
				</table>
				<br />
				<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center">';
			}

			$page_title = $lang['Trade_with'] . ' ' . $_REQUEST['username'];  
			$shoptablerows = 2;

			$shopaction .= '<form method="post" action="' . append_sid('shop_actions.'.$phpEx.'?action=trade') . '">' . $hiddenfields . '
					<th class="thHead" colspan="' . $shoptablerows . '">' . $page_title . '</th>
				</tr><tr>
				<td class="catHead" colspan="' . $shoptablerows . '"><span class="cattitle">' . $lang['Offer_current']. ': </span><b class="gen">' . $tradingitems . ' / ' . $tradegold . ' ' . $board_config['points_name'] . '</b></td>
			</tr><tr>								
				<td class="row1" width="38%"><b>' . $lang['Your_Items'] . ':</b> &nbsp; <select name="itemname">' . $user_items . '</select></td>
				<td class="row2"><input name="additem" type="submit" class="mainoption" value="' . $lang['Add'] . ' ' . $lang['Item'] . '">&nbsp;&nbsp;<input type="submit" name="removeitem" class="liteoption" value="' . $lang['Delete'] . ' ' . $lang['Item'] . '"></td>
			</tr></form>
			<form method="post" action="shop_actions.php?action=trade">' . $hiddenfields . '<tr>
				<td class="row1"><b>' . $lang['Your'] . ' ' . $board_config['points_name'] . ':</b> &nbsp; <input type="text" class="post" size="12" maxlength="15" name="points" value="' . $userdata['user_points'] . '"></td>
				<td class="row2"><input type="submit" name="addpoints" class="mainoption" value="' . $lang['Add'] . ' ' . $board_config['points_name'] . '">&nbsp;&nbsp;<input type="submit" name="removepoints" class="liteoption" value="' . $lang['Delete'] . ' ' . $board_config['points_name'] . '"></td>
			</tr></form>
				<form method="post" action="shop_actions.php?action=trade">' . $hiddenfields . '<tr>
				<td class="catHead" colspan="2"><span class="cattitle">' . $lang['Request_current'] . ': </span><b class="gen">' . $otheritemz . ' / ' . $othergold . ' ' . $board_config['points_name'] . '</b></td>
			</tr><tr>
				<td class="row1"><b>' . $lang['Other_users'] . ' ' . $lang['Items'] . ':</b> &nbsp; <select name="otheritem">' . $otheruser_items . '</select></td>
				<td class="row2"><input name="additem" type="submit" class="mainoption" value="' . $lang['Add'] . ' ' . $lang['Item'] . '">&nbsp;&nbsp;<input name="removeitem" type="submit" class="liteoption" value="' . $lang['Delete'] . ' ' . $lang['Item'] . '"></td>
			</tr></form>
			<form method="post" action="shop_actions.php?action=trade">' . $hiddenfields . '<tr>
				<td class="row1"><b>' . $lang['Other_users'] . ' ' . $board_config['points_name'] . ':</b> &nbsp; <input type="text" class="post" size="12" maxlength="15" name="otherpoints" value="0"></td>
				<td class="row2"><input type="submit" name="addpoints" class="mainoption" value="' . $lang['Add'] . '  ' . $board_config['points_name'] . '">&nbsp;&nbsp;<input type="submit" name="removepoints" class="liteoption" value="' . $lang['Delete'] . ' ' . $board_config['points_name'] . '"></td>
			</tr></form>
			<form method="post" action="shop_actions.php?action=confirmtrade">' . $hiddenfields . '<tr>
				<td colspan="' . $shoptablerows . '" class="catBottom" align="center">
				<table width="100%" cellspacing="0" cellpadding="0"><tr>
				<td align="right" width="50%"><input type="submit" class="mainoption" name="dodeal" value="' . $lang['Submit'] . '" />&nbsp;</td>
				</form><form method="submit" action="shop_actions.php">
				<td>&nbsp;<input type="hidden" name="action" value="trade"><input type="submit" class="liteoption" name="reset" value="' . $lang['Reset'] . '" /></td>
				</form></td>
				</tr>
				</table>
				</td>
			</tr>';
		}
	}
	else
	{
		$page_title = $lang['Trade_items']; 
		$shoptablerows = 2; 

		if (strlen($errormessage) > 3)
		{
			$shopaction .= '<tr>
				<td class="row2" colspan="' . $shoptablerows . '" align="center"><b style="color: #FF0000">' . str_replace("&lt;br /&gt;", "<br />", htmlspecialchars($errormessage)) . '</b></td>
			</tr>';
		}
		$shopinforow = '<form name="post" method="post" action="' . append_sid('shop_actions.'.$phpEx.'?action=trade') . '"><tr>
			<th class="thHead" colspan="' . $shoptablerows . '">' . $page_title . '</th>
		</tr><tr>
			<td class="row1" width="38%"><b>' . $lang['Trade_with'] . ':</b></span></td>
			<td class="row2"><input type="text" class="post" name="username">&nbsp;&nbsp;<input type="submit" name="usersubmit" value="' . $lang['Find_username'] . '" class="liteoption" onClick="window.open(\'./search.php?mode=searchuser\', \'_phpbbsearch\', \'HEIGHT=250,resizable=yes,WIDTH=400\');return false;" /></select></td>
		</tr><tr>
			<td class="catBottom" colspan="' . $shoptablerows . '" align="center"><input type="submit" value="' . $lang['Submit'] . '" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="' . $lang['Reset'] . '" class="liteoption" /></td>
		</tr></form>'; 	
	}
}
elseif ($_REQUEST['action'] == 'confirmtrade' || $_REQUEST['action'] == 'proposetrade')
{
	if ( !$board_config['shop_trade'] ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Trade_Disabled']); 
	}

	$otheruser = get_userdata($_REQUEST['username']); 
	if (strlen($otheruser['user_trade']) > 3) 
	{ 
		$message = sprintf($lang['Trade_waiting'], phpbb_clean_username($_REQUEST['username'])) . "<br /><br />" . sprintf($lang['Click_return_trade'], "<a href=\"" . append_sid('shop_actions.'.$phpEx.'?action=trade') . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
	
	if ((!empty($_REQUEST['username']) && strlen($otheruser['username']) > 2 && $_REQUEST['username'] != $userdata['username']) && (!empty($_REQUEST['tradeitems']) || !empty($_REQUEST['tradegold'])) && (!empty($_REQUEST['otheritems']) || !empty($_REQUEST['othergold'])))
	{
		if (!is_numeric($_REQUEST['tradegold']) || $_REQUEST['tradegold'] < 0 || !(checkgold($userdata['username'], $_REQUEST['tradegold']))) 
		{ 
			$tradegold = 0; 
		}
		if (!is_numeric($_REQUEST['othergold']) || $_REQUEST['othergold'] < 0 || !(checkgold($_REQUEST['username'], $_REQUEST['othergold']))) 
		{ 
			$othergold = 0; 
		}
		if (!(checkitemarray($userdata['username'], $_REQUEST['tradeitems'])) && strlen($_REQUEST['tradeitems']) > 2) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_Item_Invalid']); 
		}
		if (!(checkitemarray($_REQUEST['username'], $_REQUEST['otheritems'])) && strlen($_REQUEST['otheritems']) > 2) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_Item_Invalid']); 
		}

		if (strlen($_REQUEST['tradeitems']) < 3) 
		{ 
			$tradingitems = $lang['Nothing']; 
		}
		else 
		{
			$tradingitems = str_replace('Þ', ', ', str_replace('ß', '', $_REQUEST['tradeitems']));
			$tradingitems = substr($tradingitems, 0, strlen($tradingitems)-2);
		}
		if (strlen($_REQUEST['otheritems']) < 3) 
		{ 
			$otheritemz = $lang['Nothing']; 
		}
		else 
		{
			$otheritemz = str_replace('Þ', ', ', str_replace('ß', '', $_REQUEST['otheritems']));
			$otheritemz = substr($otheritemz, 0, strlen($otheritemz)-2);
		}

		if ($_REQUEST['action'] == "confirmtrade")
		{
			$hiddenfields = '<input type="hidden" name="username" value="' . $_REQUEST['username'] . '"><input type="hidden" name="tradeitems" value="' . $_REQUEST['tradeitems'] . '"><input type="hidden" name="tradegold" value="' . $_REQUEST['tradegold'] . '"><input type="hidden" name="otheritems" value="' . $_REQUEST['otheritems'] . '"><input type="hidden" name="othergold" value="' . $_REQUEST['othergold'] . '">';

			$shoplocation = ' -> <a href="'.append_sid('shop.'.$phpEx) . '" class="nav">' . $lang['Shop'] . '</a> -> <a href="'.append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']).'" class="nav">' . $lang['Inventory'] . '</a> -> <a href="'.append_sid("shop_actions.php?action=trade").'" class="nav">' . $lang['Trade'] . '</a>'; 
			$page_title = $lang['Confirm_Trade_with'] . ' ' . $_REQUEST['username']; 
			$shoptablerows = 2;

			$shopaction .= '<tr>				
				<th class="thHead" colspan="' . $shoptablerows . '">' . $page_title . '</td>
			</tr><tr>
				<td class="row2" colspan="' . $shoptablerows . '"><span class="gensmall">' . $lang['Trade_explain'] . '</span></td>
			</tr><tr>
				<td class="row1" width="38%"><b>' . $lang['Offer_current'] . ':</b></td>
				<td class="row2">' . $tradingitems . ' ' . $lang['and'] . ' ' . $_REQUEST['tradegold'] . ' ' . $board_config['points_name'] . '</td>
			</tr><tr>
				<td class="row1"><b>' . $lang['Request_current'] . ':</b></td>
				<td class="row2">' . $otheritemz . ' ' . $lang['and'] . ' ' . $_REQUEST['othergold'] . ' ' . $board_config['points_name'] . '</td>
			</tr><tr>
				<td class="catBottom" align="center" colspan="' . $shoptablerows . '">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<form method="post" action="' . append_sid('shop_actions.'.$phpEx.'?action=proposetrade') . '">' . $hiddenfields . '
					<td align="right" width="50%"><input type="submit" class="mainoption" name="dodeal" value="' . $lang['Submit'] . '">&nbsp;</td>
					</form>
					<form method="submit" action="' . append_sid('shop_actions.'.$phpEx) . '">
					<td>&nbsp;<input type="hidden" name="action" value="trade"><input type="submit" class="liteoption" name="cancel" value="' . $lang['Cancel'] .'"></td>
					</form>
				</tr>
				</table>
				</td>
			</tr>';
		}
		else if ($_REQUEST['action'] == 'proposetrade')
		{
			$message = $otheruser['user_specmsg'] . '<br />' . $userdata['username'] . '  ' . $lang['Trade_Propose_explain'];
			$trade = $userdata['user_id'] . '||-||' . $_REQUEST['tradeitems'] . '||-||' . $_REQUEST['tradegold'] . '||-||' . $_REQUEST['otheritems'] . '||-||' . $_REQUEST['othergold'];

			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_trade = '$trade', user_specmsg = '$message' 
				WHERE username = '{$otheruser['username']}'";
			if ( !($db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not update user trade or special messages data', '', __LINE__, __FILE__, $sql);
			} 
			
			$message = sprintf($lang['Trade_sent'], $_REQUEST['username']) . '<br /><br />' . sprintf($lang['Click_return_trade'], '<a href="' . append_sid('shop_actions.'.$phpEx.'?action=trade') . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else 
	{ 
		header('Location: shop_actions.'.$phpEx.'?action=trade');  
	}
}
else if (($_REQUEST['action'] == 'accepttrade') || ($_REQUEST['action'] == 'rejecttrade'))
{
	if ( !$board_config['shop_trade'] ) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Trade_Disabled']); 
	}

	if (strlen($userdata['user_trade']) < 4) 
	{ 
		message_die(GENERAL_MESSAGE, $lang['No_trades_waiting']); 
	}
	else 
	{
		$tradearray = explode("||-||", $userdata['user_trade']);
		
		$sql = "SELECT * 
			FROM " . USERS_TABLE . " 
			WHERE user_id = '$tradearray[0]'"; 
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
				message_die(GENERAL_ERROR, 'Could not query user data', '', __LINE__, __FILE__, $sql);
		}
		$row = mysql_fetch_array($result);

		if (!(checkgold($userdata['username'], $tradearray[4])) && ($tradearray[4] != 0) && (strlen($tradearray[4]) > 0)) 
		{ 
			cleartrade($userdata['user_id'], $row['user_id'], sprintf($lang['Error_trade_declined'], $userdata['username'], $board_config['points_name'])); 
			
			message_die(GENERAL_MESSAGE, sprintf($lang['Error_Points_trade_declined'], $board_config['points_name']));
		}
		
		if (!(checkgold($row['username'], $tradearray[2])) && ($tradearray[2] != 0) && (strlen($tradearray[2]) > 0)) 
		{ 
			cleartrade($userdata['user_id'], $row['user_id'], sprintf($lang['Error_trade_declined2'], $board_config['points_name'])); 
			
			message_die(GENERAL_MESSAGE, sprintf($lang['Error_trade_declined3'], $row['username'], $board_config['points_name'])); 
		}
		
		if (!(checkitemarray($userdata['username'], $tradearray[3])) && strlen($tradearray[3]) > 2) 
		{ 
			cleartrade($userdata['user_id'], $row['user_id'], sprintf($lang['Error_trade_declined4'], $userdata['username'])); 
			
			message_die(GENERAL_MESSAGE, $lang['Error_trade_declined5']); 
		}
		
		if (!(checkitemarray($row['username'], $tradearray[1])) && strlen($tradearray[1]) > 2) 
		{ 
			cleartrade($userdata['user_id'], $row['user_id'], $lang['Error_trade_declined6']); 
			
			message_die(GENERAL_MESSAGE, sprintf($lang['Error_trade_declined7'], $row['username'])); 
		}

		if ($_REQUEST['action'] == 'accepttrade')
		{
			// Take trader's points & add them to tradee
			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_points = user_points - " . $tradearray[2] . " 
				WHERE user_id = " . $tradearray[0];
			if ( !($db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not update user points data', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_points = user_points + " . $tradearray[2] . " 
				WHERE user_id = ". $userdata['user_id'];
			if ( !($db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not update user points data', '', __LINE__, __FILE__, $sql);
			}

			// Take tradee's points & add them to trader
			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_points = user_points - " . $tradearray[4] . " 
				WHERE user_id = " . $userdata['user_id'];
			if ( !($db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not update user points data', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_points = user_points + " . $tradearray[4] . " 
				WHERE user_id = " . $tradearray[0];
			if ( !($db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not update user points data', '', __LINE__, __FILE__, $sql);
			}

			// Take trader's items & add them to tradee
			$newitems = $userdata['user_items'];
			$olditems = $row['user_items'];

			$itemarray = str_replace('Þ', '', $tradearray[1]); 
			$itemarray = explode('ß', $itemarray); 
			$itemcount = sizeof($itemarray); 
			
			for ($xe = 0; $xe < $itemcount; $xe++)
			{
				if (strlen($itemarray[$xe]) > 2)
				{
					$olditems = substr_replace($olditems, '', strpos($olditems, 'ß' . $itemarray[$xe] . 'Þ'), strlen('ß' . $itemarray[$xe] . 'Þ')); 
					$newitems .= 'ß' . $itemarray[$xe] . 'Þ';
				}
			}

			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_items = '$newitems' 
				WHERE user_id = ". $userdata['user_id'];
			if ( !($db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not update user items data', '', __LINE__, __FILE__, $sql);
			}
			
			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_items = '$olditems' 
				WHERE user_id = '{$tradearray[0]}'";
			if ( !($db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not update user items data', '', __LINE__, __FILE__, $sql);
			}

			// Take tradee's items & add them to trader
			$sql = "SELECT username, user_items, user_specmsg 
				FROM " . USERS_TABLE . " 
				WHERE user_id = " . $row['user_id'];
			if ( !($result = $db->sql_query($sql)) ) 
			{
				message_die(GENERAL_ERROR, 'Could not query username, items or special messages', '', __LINE__, __FILE__, $sql);
			}
			$row = mysql_fetch_array($result);
			
			$newitems = $row['user_items'];

			$usql = "SELECT user_items, user_specmsg 
				FROM " . USERS_TABLE . " 
				WHERE user_id = " . $userdata['user_id'];
			if ( !($result = $db->sql_query($usql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not query user items or special messages', '', __LINE__, __FILE__, $sql);
			}
			$urow = mysql_fetch_array($result);
			
			$olditems = $urow['user_items'];

			$itemarray = str_replace('Þ', '', $tradearray[3]); 
			$itemarray = explode('ß', $itemarray); 
			$itemcount = sizeof($itemarray); 
			
			for ($xe = 0; $xe < $itemcount; $xe++)
			{
				if (strlen($itemarray[$xe]) > 2)
				{
					$olditems = substr_replace($olditems, '', strpos($olditems, 'ß' . $itemarray[$xe] . 'Þ'), strlen('ß' . $itemarray[$xe] . 'Þ')); 
					$newitems .= 'ß' . $itemarray[$xe] . 'Þ';
				}
			}

			$newmsg = $row['user_specmsg'] . '<br />' . sprintf($lang['Accept_Trade'], $userdata['username']); 

			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_items = '$newitems', user_specmsg = '$newmsg' 
				WHERE user_id = " . $tradearray[0];
			if ( !($db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not update user items or special messages data', '', __LINE__, __FILE__, $sql);
			}

			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_items = '$olditems', user_trade = '' 
				WHERE user_id = " . $userdata['user_id'];
			if ( !($db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not update user items or user trade data', '', __LINE__, __FILE__, $sql);
			}

			$shopaction .= '<tr>
				<td class="row2" colspan="2" align="center"><b>' . sprintf($lang['Accept_Trade_with'], $row['username']) . '</b></td>
			</tr>';

			$shoplocation = ' -> <a href="'.append_sid('shop.'.$phpEx) . '" class="nav">' . $lang['Shop'] . '</a> -> <a href="'.append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']).'" class="nav">' . $lang['Inventory'] . '</a> -> <a href="'.append_sid("shop_actions.php?action=trade").'" class="nav">' . $lang['Trade_Accepted'] . '</a>'; 
			$title = $lang['Trade_Accepted']; 
			$page_title = $lang['Trade_Accepted'];
			$shoptablerows = 1; 
		}
		elseif ($_REQUEST['action'] == 'rejecttrade')
		{
			cleartrade($userdata['user_id'], $tradearray[0], sprintf($lang['Declined_trade'], $userdata['username']));
			$shopaction .= '<tr>
				<td class="row2" colspan="2" align="center"><b>' . sprintf($lang['Rejected_trade'], $row['username']) . '</b></td>
			</tr>';
			
			$shoplocation = ' -> <a href="'.append_sid('shop.'.$phpEx) . '" class="nav">' . $lang['Shop'] . '</a> -> <a href="'.append_sid("shop.$phpEx?action=inventory&searchid=".$userdata['user_id']).'" class="nav">' . $lang['Inventory'] . '</a> -> <a href="'.append_sid("shop_actions.php?action=trade").'" class="nav">' . $lang['Trade_Declined'] . '</a>'; 
			$title = $lang['Trade_Declined']; 
			$page_title = $lang['Trade_Declined'];
			$shoptablerows = 1; 
		}
	}
}
else 
{ 
	message_die(GENERAL_MESSAGE, $lang['Error_Invaild_Action']); 
}

if ( $board_config['u_shops_enabled'] )
{
	$shop_trans = ' | <a href="' . append_sid('shop_transactions.'.$phpEx) . '" class="nav">' . $lang['Shop_Transaction'] . '</a>';
}

// Start of personal information
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
	'L_SHOP_TITLE' => $title, 
	'SHOPTABLEROWS' => $shoptablerows, 
	'SHOPLIST' => $shopitems, 
	'SHOPINFOROW' => $shopinforow)
); 

$template->assign_block_vars('', array()); 

// 
// Generate the page 
// 
include($phpbb_root_path . 'includes/page_header.' . $phpEx); 

if ($_REQUEST['action'] == 'give' || $_REQUEST['action'] == 'trade') 
{
	include($phpbb_root_path . 'profile_menu.'.$phpEx);
}

$template->pparse('body'); 

include($phpbb_root_path . 'includes/page_tail.' . $phpEx); 

?>
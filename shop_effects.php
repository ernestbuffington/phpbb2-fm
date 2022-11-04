<?php
/***************************************************************************
 *                             shop_effects.php
 *                            -------------------
 *   Version              : 2.6.0
 *   released             : Sunday, December 15th, 2002
 *   last updated         : Saturday, July 12th, 2003
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
// Start page Variables
//
$colordropdown = '<select name="color">
<option style="color: #FFFF00" value="FFFF00">' . $lang['color_yellow'] . '</option>
<option style="color: #FFD700" value="FFD700">' . $lang['color_gold'] . '</option>	
<option style="color: #FF7F50" value="FF7F50">' . $lang['color_coral'] . '</option>
<option style="color: #DC143C" value="DC143C">' . $lang['color_crimson'] . '</option>
<option style="color: #FFA500" value="FFA500">' . $lang['color_orange'] . '</option>
<option style="color: #FF6347" value="FF6347">' . $lang['color_tomato'] . '</option>
<option style="color: #FF0000" value="FF0000">' . $lang['color_red'] . '</option>	
<option style="color: #8B0000" value="8B0000">' . $lang['color_dark_red'] . '</option>				
<option style="color: #D2691E" value="D2691E">' . $lang['color_chocolate'] . '</option>
<option style="color: #A52A2A" value="A52A2A">' . $lang['color_brown'] . '</option>
<option style="color: #0000FF" value="0000FF">' . $lang['color_blue'] . '</option>
<option style="color: #00FFFF" value="00FFFF">' . $lang['color_cyan'] . '</option>	
<option style="color: #3366FF" value="3366FF">' . $lang['color_light_blue'] . '</option>
<option style="color: #5F9EA0" value="5F9EA0">' . $lang['color_cadet_blue'] . '</option>
<option style="color: #00BFFF" value="00BFFF">' . $lang['color_deepskyblue'] . '</option>	
<option style="color: #00008B" value="00008B">' . $lang['color_dark_blue'] . '</option>
<option style="color: #191970" value="191970">' . $lang['color_midnightblue'] . '</option>
<option style="color: #808000" value="808000">' . $lang['color_olive'] . '</option>
<option style="color: #008000" value="008000">' . $lang['color_green'] . '</option>
<option style="color: #66FF33" value="66FF33">' . $lang['color_light_green'] . '</option>
<option style="color: #238E68" value="238E68">' . $lang['color_sea_green'] . '</option>
<option style="color: #006400" value="006400">' . $lang['color_darkgreen'] . '</option>
<option style="color: #FF33FF" value="FF33FF">' . $lang['color_pink'] . '</option>
<option style="color: #4B0082" value="4B0082">' . $lang['color_indigo'] . '</option>
<option style="color: #EE82EE" value="EE82EE">' . $lang['color_violet'] . '</option>
<option style="color: #CC33FF" value="CC33FF">' . $lang['color_purple'] . '</option>
<option style="color: #FFFFFF" value="FFFFFF">' . $lang['color_white'] . '</option>
<option style="color: #CCCCCC" value="CCCCCC">' . $lang['color_light_grey'] . '</option>
<option style="color: #999999" value="999999">' . $lang['color_dark_grey'] . '</option>
<option style="color: #000000" value="000000">' . $lang['color_black'] . '</option>
</select>';


//	
// Start of special shop display
//
if (($_REQUEST['action'] == 'specialshop') || (empty($_REQUEST['action'])))
{
	$template->set_filenames(array(
		'body' => 'shop_body.tpl')
	);
    make_jumpbox('viewforum.'.$phpEx); 

	if ( !$userdata['session_logged_in'] )
	{
		$redirect = "shop.$phpEx&action=specialshop";
		$redirect .= ( isset($user_id) ) ? '&user_id=' . $user_id : '';
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
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
	
	// Start checks for first visit
	if (strlen($userdata['user_privs']) < 2) 
	{ 
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_effects = 'ßnoÞ0ßnoÞ0ßnoÞ0', user_privs = 'ßnoÞ0ßnoÞ0ßnoÞ0'
			WHERE user_id = " . $userdata['user_id'];
	    if( !($result = $db->sql_query($sql)) )
		{ 
			message_die(GENERAL_ERROR, 'Could not update user\'s effects and permissions', '', __LINE__, __FILE__, $sql);
		}
		
		if (strlen($userdata['user_custitle']) < 2) 
		{
			$sql = "UPDATE " . USERS_TABLE . " 
				SET user_custitle = 'ßoffÞ0ßoffÞ0ßoffÞ0ßoffÞ0ßoffÞ0' 
				WHERE user_id = " . $userdata['user_id'];
        	if( !($result = $db->sql_query($sql)) )
			{ 
				message_die(GENERAL_ERROR, 'Could not update user\'s custom title', '', __LINE__, __FILE__, $sql);
			}
		}
		header("Location: shop_effects.php");
	}
	// End first visit checks
		
	$usereffects = explode('ß', $userdata['user_effects']);
	$userprivs = explode('ß', $userdata['user_privs']);
	$userctitle = explode('ß', $userdata['user_custitle']);
	$userbs = array();
	
	$usercount = sizeof($userprivs);
	for ($x = 0; $x < $usercount; $x++) 
	{ 
		$temppriv = explode('Þ', $userprivs[$x]); 
		$userbs[] = $temppriv[0]; 
		$userbs[] = $temppriv[1]; 
	}
	
	$usercount = sizeof($usereffects);
	for ($x = 0; $x < $usercount; $x++) 
	{ 
		$temppriv = explode('Þ', $usereffects[$x]); 
		$userbs[] = $temppriv[0]; 
		$userbs[] = $temppriv[1]; 
	}
	
	$usercount = sizeof($userctitle);
	for ($x = 0; $x < $usercount; $x++) 
	{ 
		$temppriv = explode('Þ', $userctitle[$x]); 
		$userbs[] = $temppriv[0]; 
		$userbs[] = $temppriv[1]; 
	}

	// Check enabled
	if ($shopstatarray[3] != 'enabled') 
	{ 
		message_die(GENERAL_MESSAGE, $lang['Error_Invalid_Shop_Disabled']); 
	}

	if (($shopstatarray[6] == 'on') || ($shopstatarray[8] == 'on') || ($shopstatarray[10] == 'on'))
	{
		if (($userbs[2] == 'no') || ($userbs[2] == 'off')) 
		{ 
			$avatarbs = $lang['Buy']; 
		} 
		else 
		{ 
			$avatarbs = $lang['Sell']; 
			$avatarowned = $lang['Yes']; 
		}
		
		if (($userbs[4] == 'no') || ($userbs[4] == 'off')) 
		{ 
			$sigbs = $lang['Buy']; 
		} 
		else 
		{ 
			$sigbs = $lang['Sell']; 
			$sigowned = $lang['Yes']; 
		}
		
		if (($userbs[6] == 'no') || ($userbs[6] == 'off')) 
		{ 
			$titlebs = $lang['Buy']; 
		} 
		else 
		{ 
			$titlebs = $lang['Sell']; 
			$titleowned = $lang['Yes']; 
		}
		
		$shopinfo .= '<tr><th class="thHead" colspan="5">' . $lang['Privileges'] . '</th></tr>';
		$shopinfo .= '<tr>
			<td class="catLeft" align="center">&nbsp;<span class="cattitle">' . $lang['Privileges'] . '</span>&nbsp;</td>
			<td class="cat" align="center">&nbsp;<span class="cattitle">' . $lang['Cost'] . '</span>&nbsp;</td>
			<td class="cat" colspan="2" align="center">&nbsp;<span class="cattitle">' . $lang['Owned'] . '</span>&nbsp;</td>
			<td class="catRight" align="center">&nbsp;<span class="cattitle">' . $lang['Action'] . '</span>&nbsp;</td>
		</tr>';
	
		if ($shopstatarray[6] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.".$phpEx."?action=bsspecial&type=avatar&bs=" . $avatarbs) . '"><tr>
				<td class="row1"><b>' . $lang['Buy_Av_Priv'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[7] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" colspan="2" align="center"><b>' . $avatarowned . '</b></td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $avatarbs . ' ' . $lang['Avatar'] . '"></td>				
			</tr></form>';
		}
		
		if ($shopstatarray[8] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.".$phpEx."?action=bsspecial&type=sig&bs=" . $sigbs) . '"><tr>
				<td class="row1"><b>' . $lang['Buy_Sig_Priv'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[9] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" colspan="2" align="center"><b>' . $sigowned . '</b></td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $sigbs . ' ' . $lang['Signature'] . '"></td>
			</tr></form>';
		}
		
		if ($shopstatarray[10] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.".$phpEx."?action=bsspecial&type=title&bs=" . $titlebs) . '"><tr>
				<td class="row1"><b>' . $lang['Buy_Title_Priv'] . ':</b></span></td>
				<td class="row2" align="center">' . $shopstatarray[11] . ' ' . $board_config['points_name'] . '</span></td>
				<td class="row1" colspan="2" align="center"><b>' . $titleowned . '</b></td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $titlebs . ' ' . $lang['Title'] . '"></td>
			</tr></form>';
		}
	}
	
	if (($shopstatarray[12] == 'on') || ($shopstatarray[14] == 'on') || ($shopstatarray[16] == 'on'))
	{
		$shopinfo .= '<tr><th class="thHead" colspan="5">' . $lang['Name'] . ' ' . $lang['Effects'] . '</th></tr>';
		$shopinfo .= '<tr>
			<td class="catLeft" align="center" width="38%">&nbsp;<span class="cattitle">' . $lang['Effects'] . '</span>&nbsp;</td>
			<td class="cat" align="center">&nbsp;<span class="cattitle">' . $lang['Cost'] . '</span>&nbsp;</td>
			<td class="cat" align="center">&nbsp;<span class="cattitle">' . $lang['Font_color'] . ' </span>&nbsp;</td>
			<td class="cat" align="center">&nbsp;<span class="cattitle">' . $lang['Owned'] . '</span>&nbsp;</td>
			<td class="catRight" align="center">&nbsp;<span class="cattitle">' . $lang['Action'] . '</span>&nbsp;</td>
		</tr>';
		
		if (($userbs[10] == 'no') || ($userbs[10] == 'off')) 
		{ 
			$colorbs = $lang['Buy']; 
		} 
		else 
		{ 
			$colorbs = $lang['Sell']; 
			$colorowned = '<b style="color: #' . $userbs[11] . '">' . $lang['Yes'] . '</b>'; 
		}
		
		if (($userbs[12] == 'no') || ($userbs[12] == 'off')) 
		{ 
			$shadowbs = $lang['Buy']; 
		} 
		else 
		{ 
			$shadowbs = $lang['Sell']; 
			$shadowowned = '<b style="color: #' . $userbs[13] . '">' . $lang['Yes'] . '</b>'; 
		}
		
		if (($userbs[14] == 'no') || ($userbs[14] == 'off')) 
		{ 
			$glowbs = $lang['Buy']; 
		} 
		else 
		{ 
			$glowbs = $lang['Sell']; 
			$glowowned = '<b style="color: #' . $userbs[15] . '">' . $lang['Yes'] . '</b>'; 
		}
		
		if ($shopstatarray[12] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.".$phpEx."?action=bsspecial&type=color&bs=" . $colorbs) . '"><tr>
				<td class="row1"><b>' . $lang['Buy_Color'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[13] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" align="center">' . $colordropdown . '</td>
				<td class="row1" align="center">' . $colorowned . '</td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $colorbs . ' ' . $lang['Color'] . '"><//td>
			</tr></form>';
		}
		
		if ($shopstatarray[14] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.".$phpEx."?action=bsspecial&type=glow&bs=" . $glowbs) . '"><tr>
				<td class="row1"><b>' . $lang['Buy_Glow'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[15] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" align="center">' . $colordropdown . '</td>
				<td class="row1" align="center">' . $glowowned . '</td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $glowbs . ' ' . $lang['Glow'] . '"></td>
			</tr></form>';
		}
		
		if ($shopstatarray[16] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.".$phpEx."?action=bsspecial&type=shadow&bs=" . $shadowbs) .'"><tr>
				<td class="row1"><b>' . $lang['Buy_Shadow'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[17] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" align="center">' . $colordropdown . '</td>
				<td class="row1" align="center">' . $shadowowned . '</td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $shadowbs . ' ' . $lang['Shadow'] . '"></td>
			</tr></form>';
		}
	}
	
	if (($shopstatarray[18] == 'on') || ($shopstatarray[20] == 'on') || ($shopstatarray[22] == 'on'))
	{
		$shopinfo .= '<tr><th class="thHead" colspan="5">' . $lang['Title'] . ' ' . $lang['Effects'] . '</th></tr>';
		$shopinfo .= '<tr>
			<td class="catLeft" align="center">&nbsp;<span class="cattitle">' . $lang['Effects'] . '</span>&nbsp;</td>
			<td class="cat" align="center">&nbsp;<span class="cattitle">' . $lang['Cost'] . '</span>&nbsp;</td>
			<td class="cat" align="center">&nbsp;<span class="cattitle">' . $lang['Font_color'] . '</span>&nbsp;</td>
			<td class="cat" align="center">&nbsp;<span class="cattitle">' . $lang['Owned'] . '</span>&nbsp;</td>
			<td class="catRight" align="center">&nbsp;<span class="cattitle">' . $lang['Action'] . '</span>&nbsp;</td>
		</tr>';
		
		if (($userbs[18] == 'no') || ($userbs[18] == 'off')) 
		{ 
			$tcolorbs = $lang['Buy']; 
		} 
		else 
		{ 
			$tcolorbs = $lang['Sell']; 
			$tcolorowned = '<b style="color: #' . $userbs[19] . '">' . $lang['Yes'] . '</b>'; 
		}
		
		if (($userbs[20] == 'no') || ($userbs[20] == 'off')) 
		{ 
			$tglowbs = $lang['Buy']; 
		} 
		else 
		{ 
			$tglowbs = $lang['Sell']; 
			$tglowowned = '<b style="color: #' . $userbs[21] . '">' . $lang['Yes'] . '</b>'; 
		}
		if (($userbs[22] == 'no') || ($userbs[22] == 'off')) 
		{ 
			$tshadowbs = $lang['Buy']; 
		} 
		else 
		{ 
			$tshadowbs = $lang['Sell']; 
			$tshadowowned = '<b style="color: #' . $userbs[23] . '">' . $lang['Yes'] . '</b>'; 
		}
		
		if ($shopstatarray[18] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.$phpEx?action=bsspecial&type=tcolor&bs=$tcolorbs") . '"><tr>
				<td class="row1" width="38%"><b>' . $lang['Buy_Title_Color'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[19] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" align="center">' . $colordropdown . '</td>
				<td class="row1" align="center">' . $tcolorowned . '</td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $tcolorbs . ' ' . $lang['Color'] . '"></td>
			</tr></form>';
		}
		
		if ($shopstatarray[20] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.$phpEx?action=bsspecial&type=tglow&bs=$tglowbs") . '"><tr>
				<td class="row1"><b>' . $lang['Buy_Title_Glow'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[21] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" align="center">'. $colordropdown . '</td>
				<td class="row1" align="center">' . $tglowowned . '</td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $tglowbs . ' ' . $lang['Glow'] . '"></td>
			</tr></form>';
		}
		
		if ($shopstatarray[22] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.$phpEx?action=bsspecial&type=tshadow&bs=$tshadowbs") . '"><tr>
				<td class="row1"><b>' . $lang['Buy_Title_Shadow'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[23] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" align="center">' . $colordropdown . '</td>
				<td class="row1" align="center">' . $tshadowowned . '</td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $tshadowbs . ' ' . $lang['Shadow'] . '"></td>
			</tr></form>';
		}
	}
	if (($shopstatarray[24] == 'on') || ($shopstatarray[26] == 'on'))
	{
		$shopinfo .= '<tr><th class="thHead" colspan="5">' . $lang['Custom_Changes'] . '</th></tr>';
		$shopinfo .= '<tr>
			<td class="catLeft" align="center">&nbsp;<span class="cattitle">' . $lang['Type'] . '</span>&nbsp;</td>
			<td class="cat" align="center">&nbsp;<span class="cattitle">' . $lang['Cost'] . '</span>&nbsp;</td>
			<td class="cat" align="center">&nbsp;<span class="cattitle">' . $lang['Change_To'] . '</span>&nbsp;</td>
			<td class="cat" align="center">&nbsp;<span class="cattitle">' . $lang['Owned'] . '/' . $lang['Name'] . '</span>&nbsp;</td>
			<td class="catRight" align="center">&nbsp;<span class="cattitle">' . $lang['Action'] . '</span>&nbsp;</td>
		</tr>';
		
		if ((($userbs[24] == 'no') || ($userbs[24] == 'off')) || ($userbs[26] == 'on')) 
		{ 
			$ctitlebs = $lang['Buy']; 
		} 
		else 
		{ 
			$ctitlebs = $lang['Sell']; 
			$ctitleowned = '<b>' . $lang['Yes'] . '</b>'; 
		}
		if ($shopstatarray[24] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.$phpEx?action=bsspecial&type=ctitle&bs=$ctitlebs").'"><tr>
				<td class="row1"><b>' . $lang['Change_Title'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[25] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" align="center"><input class="post" type="text" name="newtitle" size="32" maxlength="32"></td>
				<td class="row1" align="center">' . $ctitleowned . '</td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $ctitlebs . ' ' . $lang['Title'] . '"></td>
			</tr></form>';
		}
		
		if ((($userbs[28] == 'no') || ($userbs[28] == 'off')) || ($userbs[30] == 'on')) 
		{ 
			$cusernameowned = '';
		} 
		else 
		{ 
			$cusernameowned = '<b>' . $userdata['username'] . '</b>'; 
		}
		if ($shopstatarray[26] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.$phpEx?action=bsspecial&type=cusername&bs=" . $lang['Buy']).'"><tr>
				<td class="row1"><b>' . $lang['Change_Username'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[27] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" align="center"><input class="post" type="text" name="newname" size="32" maxlength="32"></td>
				<td class="row1" align="center">' . $cusernameowned . '</td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $lang['Change_Username'] . '"></td>
			</tr></form>';
		}
		
		if ($shopstatarray[28] == 'on')
		{
			$shopinfo .= '<form method="post" action="'.append_sid("shop_effects.$phpEx?action=bsspecial&type=cutitle&bs=" . $lang['Buy']).'"><tr>
				<td class="row1"><b>' . $lang['Change_User_Title'] . ':</b></td>
				<td class="row2" align="center">' . $shopstatarray[29] . ' ' . $board_config['points_name'] . '</td>
				<td class="row1" align="center"><input class="post" type="text" name="newtitle" size="32" maxlength="32"></td>
				<td class="row1" align="center"><input class="post" type="text" name="tchangename" size="20" maxlength="32"></td>
				<td class="row2"><input class="liteoption" type="submit" value="' . $lang['Change_User_Title'] . '"></td>
			</tr></form>';
		}
	}
	
	if ($shopstatarray[12] == 'on' || $shopstatarray[14] == 'on' || $shopstatarray[16] == 'on' || $shopstatarray[18] == 'on' || $shopstatarray[20] == 'on' || $shopstatarray[22] == 'on') 
	{
		$shopinfo .= '</table><br />
		<form method="post" action="' . append_sid("shop_effects.".$phpEx."?action=specialshop&viewname=true#effects").'">
		<table width="100%" cellpadding="4" cellspacing="1" class="forumline"><tr><th class="thHead" colspan="5">' . $lang['Test_Effects'] . '</th></tr>';
		$shopinfo .= '<tr>
			<td class="catLeft" align="center">&nbsp;<span class="cattitle">' . $lang['Effects'] . '</span>&nbsp;</td>
			<td class="catRight" align="center">&nbsp;<span class="cattitle">' . $lang['Font_color'] . '</span>&nbsp;</td>
		</tr>';

		if ($shopstatarray[12] == 'on' || $shopstatarray[18] == 'on')
		{
			$shopinfo .= '<tr>
				<td class="row1" width="38%"><a name="effects"></a><b>' . $lang['Color'] . ':</b></td>
				<td class="row2"><select name="color"><option value="none">--</option>' . str_replace('<select name="color">', '', $colordropdown).'</td>
			</tr>';	
		}
		
		if ($shopstatarray[14] == 'on' || $shopstatarray[20] == 'on')
		{
			$shopinfo .= '<tr>
				<td class="row1"><b>' . $lang['Glow'] . ':</b></td>
				<td class="row2"><select name="gcolor"><option value="none">--</option>' . str_replace('<select name="color">', '', $colordropdown).'</td>
			</tr>';
		}
		if ($shopstatarray[16] == 'on' || $shopstatarray[22] == 'on')
		{
			$shopinfo .= '<tr>
				<td class="row1"><b>' . $lang['Shadow'] . ':</b></td>
				<td class="row2"><select name="scolor"><option value="none">--</option>' . str_replace('<select name="color">', '', $colordropdown).'</td>
			</tr>';
		}
		$shopinfo .= '<tr>
			<td class="row1"><b>' . $lang['Test_Text'] . ':</b></td>
			<td class="row2"><input class="post" type="text" size="32" maxlength="32" name="testtext"> <input type="submit" value="' . $lang['View'] . ' ' . $lang['Effects'] . '" class="liteoption" /></td>
		</tr></form>';
	}
	
	if ($_REQUEST['viewname'] == 'true') 
	{
		if ( $_REQUEST['color'] != 'none' ) 
		{ 
			$testcolor = '<span style="color: #' . $_REQUEST['color'] . '">'; 
		}
		
		if ( $_REQUEST['gcolor'] != 'none' ) 
		{ 
			$testglow = '; filter:glow(color=#' . $_REQUEST['gcolor'] . ', strength=5)'; 
		}
		
		if ( $_REQUEST['scolor'] != 'none' ) 
		{ 
			$testshadow = '; filter:shadow(color=#' . $_REQUEST['scolor'] . ', strength=5)'; 
		}
		
		if (!preg_match("/^[a-zA-Z0-9 ]*$/", $_REQUEST['testtext'])) 
		{ 
			$text = $userdata['username']; 
		}
		else if (strlen($_REQUEST['testtext']) < 2) 
		{ 
			$text = $userdata['username']; 
		}
		else 
		{ 
			$text = $_REQUEST['testtext']; 
		}
		
		$shopinfo .= '<tr>
			<td class="row2" colspan="2" align="center" height="30" valign="middle"><span style="width:400' . $testshadow . $testglow . '">' . $testcolor . ' ' . $text . '</span></span></td>
		</tr>';
	}	
	
	$page_title = $shopstatarray[5];
	$shoplocation = ' -> <a href="' . append_sid("shop.$phpEx") . '" class="nav">' . $lang['Shop'] . '</a> -> <a href="' . append_sid("shop_effects.$phpEx?action=specialshop") . '" class="nav">' . $shopstatarray[5] . '</a>';

	if (strlen($shopinfo) > 3) 
	{  
		$shoptablerows = 5; 
	}
	else 
	{ 
		$shoptablerows = 1; 
		$shopinforow = '<tr><td class="row2" height="30" align="center" colspan="5">' . $lang['No_Privilages'] . '</td></tr>'; 
	}

	// start of personal information
	$personal = '<tr><td colspan="' . $shoptablerows . '" class="catBottom" align="center"><a href="'.append_sid("shop.$phpEx?action=inventory&searchid=" . $userdata['user_id']) . '" class="cattitle">' . $lang['Your_Inventory'] . '</a></td></tr>'; 

	if (strlen($userdata['user_specmsg']) > 2) 
	{ 
		$personal .= '</table><br /><table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><tr><th class="thHead">' . $lang['Information'] . '</th></tr>'; 
		$personal .= '<tr><td class="row1" height="50" align="center"><span class="gen">' . $userdata['user_specmsg'] . '</span></td></tr>'; 
		$personal .= '<tr><td class="row1" colspan="2" align="center">[ <a href="' . append_sid('shop.'.$phpEx.'?clm=true') . '">' . $lang['Clear_Messages'] . '</a> ]</td></tr>';
	}
	//end of personal information

	$template->assign_vars(array(
		'SHOPPERSONAL' => $personal,
		'SHOPLOCATION' => $shoplocation,
		'L_SHOP_TITLE' => $page_title,
		'SHOPTABLEROWS' => $shoptablerows,
		'SHOPLIST' => $shopinfo,
		'SHOPINFOROW' => $shopinforow)
	);
	
	$template->assign_block_vars('', array());

}

// Start of buy & sell sepcials
elseif ($_REQUEST['action'] == 'bsspecial')
{
	if ( !$userdata['session_logged_in'] )
	{
		$redirect = "shop.$phpEx&action=bsspecial&type=" . $_REQUEST['type'] . "&bs=" . $_REQUEST['bs'] .  "&color=" . $_REQUEST['color'];
		$redirect .= ( isset($user_id) ) ? '&user_id=' . $user_id : '';
		header('Location: ' . append_sid("login.$phpEx?redirect=$redirect", true));
	}
		
	$template->set_filenames(array( 
		'body' => 'shop_body.tpl')
	);
	make_jumpbox('viewforum.'.$phpEx); 

	$usereffects = explode('ß', $userdata['user_effects']);
	$userprivs = explode('ß', $userdata['user_privs']);
	$usercustitle = explode('ß', $userdata['user_custitle']);
	$userbs = array();
	$usercount = sizeof($userprivs);
	for ($x = 0; $x < $usercount; $x++) 
	{ 
		$temppriv = explode('Þ', $userprivs[$x]); $userbs[] = $temppriv[0]; $userbs[] = $temppriv[1]; 
	}
	$usercount = sizeof($usereffects);
	for ($x = 0; $x < $usercount; $x++) 
	{ 
		$temppriv = explode('Þ', $usereffects[$x]); $userbs[] = $temppriv[0]; $userbs[] = $temppriv[1]; 
	}
	$usercount = sizeof($usercustitle);
	for ($x = 0; $x < $usercount; $x++) 
	{ 
		$temppriv = explode('Þ', $usercustitle[$x]); $userbs[] = $temppriv[0]; $userbs[] = $temppriv[1]; 
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
	
	if ($_REQUEST['bs'] == $lang['Buy'])
	{
		if ((($_REQUEST['type'] == 'ctitle') && ($shopstatarray[24] == 'on')) || (($_REQUEST['type'] == 'cutitle') && ($shopstatarray[28] == 'on'))) 
		{ 
			$tsql = "SELECT * 
				FROM " . RANKS_TABLE . " 
				WHERE rank_title = '{$_REQUEST['newtitle']}'";
			if ( !($tresult = $db->sql_query($tsql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not query rank data', '', __LINE__, __FILE__, $sql);
			}
			$trow = mysql_fetch_array($tresult);

			if (mysql_num_rows($tresult) > 0) 
			{ 
				$message = $lang['Error_Invalid_Rank_Assigned'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
				message_die(GENERAL_MESSAGE, $message);
			}
			elseif ((!preg_match("/^[a-zA-Z0-9 ]*$/", $_REQUEST['newtitle'])) || (strlen($_REQUEST['newtitle']) < 2)) 
			{ 
				$message = $lang['Error_Invalid_Rank'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
				message_die(GENERAL_MESSAGE, $message);
			}

			if (($_REQUEST['type'] == 'cutitle') && ($shopstatarray[28] == 'on')) 
			{
				if ($userdata[username] == $_REQUEST['tchangename']) 
				{ 
					$message = $lang['Error_Invalid_Change_Username'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
					message_die(GENERAL_MESSAGE, $message);
				}
				
				$sql = "SELECT * 	
					FROM " . USERS_TABLE . " 
					WHERE username = '{$_REQUEST['tchangename']}'";
				if ( !($result = $db->sql_query($sql)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Could not query username', '', __LINE__, __FILE__, $sql);
				}
				$ucrow = mysql_fetch_array($result);
				
				if ((($ucrow['user_level'] == 1) || ($ucrow['user_level'] == 2) || ($ucrow['user_level'] == 3)) && ($urow['user_level'] != 1)) 
				{ 
					$message = $lang['Error_Invalid_Change_Rank'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
					message_die(GENERAL_MESSAGE, $message);
				}

// Why this check??
	
				if (strlen($ucrow['username']) < 2) 
				{ 
					$message = $lang['No_user_specified'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
					message_die(GENERAL_MESSAGE, $message);
				}
				else 
				{ 
					$specialcost = $shopstatarray[29]; 
				}
			}
			else 
			{ 
				$specialcost = $shopstatarray[25]; 
			} 
		}

		if (($_REQUEST['type'] == 'cusername') && ($shopstatarray[26] == 'on')) 
		{ 
			$sql = "SELECT * 
				FROM " . USERS_TABLE . " 
				WHERE username = '{$_REQUEST['newname']}'";
			if ( !($result = $db->sql_query($sql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not query user data', '', __LINE__, __FILE__, $sql);
			}
			
			if (mysql_num_rows($result) > 0) 
			{
				$message = $lang['Error_Username_Exists'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
				message_die(GENERAL_MESSAGE, $message);
			}
			elseif ((!preg_match("/^[a-zA-Z0-9 ]*$/", $_REQUEST['newname'])) || (strlen($_REQUEST['newname']) < 2)) 
			{ 
				$message = $lang['Error_Invalid_Username'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
				message_die(GENERAL_MESSAGE, $message);
			} 
			else 
			{ 
				$specialcost = $shopstatarray[27]; 
			} 
		}
		
		if (($_REQUEST['type'] == 'tcolor') && ($shopstatarray[18] == 'on')) 
		{ 
			$specialcost = $shopstatarray[19]; 	
		}
		if (($_REQUEST['type'] == 'tglow') && ($shopstatarray[20] == 'on')) 
		{ 
			$specialcost = $shopstatarray[21]; 
		}
		if (($_REQUEST['type'] == 'tshadow') && ($shopstatarray[22] == 'on')) 
		{ 
			$specialcost = $shopstatarray[23]; 
		}
		if (($_REQUEST['type'] == 'avatar') && ($shopstatarray[6] == 'on')) 
		{ 
			$specialcost = $shopstatarray[7]; 
		}
		if (($_REQUEST['type'] == 'sig') && ($shopstatarray[8] == 'on')) 
		{ 
			$specialcost = $shopstatarray[9]; 
		}
		if (($_REQUEST['type'] == 'title') && ($shopstatarray[10] == 'on')) 
		{ 
			$specialcost = $shopstatarray[11]; 
		}
		if (($_REQUEST['type'] == 'color') && ($shopstatarray[12] == 'on')) 
		{ 
			$specialcost = $shopstatarray[13]; 
		}
		if (($_REQUEST['type'] == 'shadow') && ($shopstatarray[16] == 'on')) 
		{ 
			$specialcost = $shopstatarray[17]; 
		}
		if (($_REQUEST['type'] == 'glow') && ($shopstatarray[14] == 'on')) 
		{ 
			$specialcost = $shopstatarray[15]; 
		}
		
		if (!is_numeric($specialcost)) 
		{ 
			$message = $lang['Error_Invalid_Shop_Function'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		
		if (($_REQUEST['type'] == 'color') || ($_REQUEST['type'] == 'shadow') || ($_REQUEST['type'] == 'glow') || ($_REQUEST['type'] == 'tglow') || ($_REQUEST['type'] == 'tcolor') || ($_REQUEST['type'] == 'tshadow'))
		{
			if (substr_count($colordropdown, '<option style="color: #' . $_REQUEST['color'] . '" value="' . $_REQUEST['color'] . '">') < 1) 
			{ 
				$message = $lang['Error_Invalid_Color'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
				message_die(GENERAL_MESSAGE, $message);
			}
		}
		if (($_REQUEST['type'] == 'ctitle') && (($userbs[24] == 'on') && ($userbs[26] != 'on'))) 
		{ 
			$message = $lang['Error_Got_Custom_Title'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		if (($_REQUEST['type'] == 'tcolor') && ($userbs[18] == 'on')) 
		{ 
			$message = $lang['Error_Got_Color_Title'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		if (($_REQUEST['type'] == 'tglow') && ($userbs[20] == 'on')) 
		{ 
			$message = $lang['Error_Got_Glow_Title'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		if (($_REQUEST['type'] == 'tshadow') && ($userbs[22] == 'on')) 
		{ 
			$message = $lang['Error_Got_Shadow_Title'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		if (($_REQUEST['type'] == 'avatar') && ($userbs[2] == 'on')) 
		{ 
			$message = $lang['Error_Got_Av_Permiss'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		if (($_REQUEST['type'] == 'sig') && ($userbs[4] == 'on')) 
		{ 
			$message = $lang['Error_Got_Sig_Permiss'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		if (($_REQUEST['type'] == 'title') && ($userbs[6] == 'on')) 
		{ 
			$message = $lang['Error_Got_Title_Permiss'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		if (($_REQUEST['type'] == 'color') && ($userbs[10] == 'on')) 
		{ 
			$message = $lang['Error_Got_Color_Name'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		if (($_REQUEST['type'] == 'shadow') && ($userbs[12] == 'on')) 
		{ 
			$message = $lang['Error_Got_Shadow_Name'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		if (($_REQUEST['type'] == 'glow') && ($userbs[14] == 'on')) 
		{ 
			$message = $lang['Error_Got_Glow_Name'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		
		$userleftamount = $userdata['user_points'] - $specialcost;
		
		if ($userleftamount < 0) 
		{ 
			$message = $lang['Error_Invalid_Points'] . $board_config['points_name'] . ".<br /><br />" . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		else 
		{ 
			$upsql = "UPDATE " . USERS_TABLE . " 
				SET user_points = '$userleftamount' 
				WHERE username = '{$userdata[username]}'"; 	
			if ( !($db->sql_query($upsql)) ) 
			{ 
				message_die(GENERAL_ERROR, 'Could not update user points', '', __LINE__, __FILE__, $sql);
			}
			
			if ($_REQUEST['type'] == 'avatar') 
			{ 
				$userprs = 'ßonÞ' . $userbs[3] . 'ß' . $userbs[4] . 'Þ' . $userbs[5] . 'ß' . $userbs[6] . 'Þ' . $userbs[7]; 
			}
			if ($_REQUEST['type'] == 'sig') 
			{ 
				$userprs = 'ß' . $userbs[2] . 'Þ' . $userbs[3] . 'ßonÞ' . $userbs[5] . 'ß' . $userbs[6] . 'Þ' . $userbs[7]; 
			}
			if ($_REQUEST['type'] == 'title') 
			{ 
				$userprs = 'ß' . $userbs[2] . 'Þ' . $userbs[3] . 'ß' . $userbs[4] . 'Þ' . $userbs[5] . 'ßonÞ' . $userbs[7]; 
			}
			if (($_REQUEST['type'] != 'title') && ($_REQUEST['type'] != 'sig') && ($_REQUEST['type'] != 'avatar')) 
			{ 
				$userprs = 'ß' . $userbs[2] . 'Þ' . $userbs[3] . 'ß' . $userbs[4] . 'Þ' . $userbs[5] . 'ß' . $userbs[6] . 'Þ' . $userbs[7]; 
			}
			if ($_REQUEST['type'] == 'color') 
			{ 
				$usereff = 'ßonÞ' . $_REQUEST['color'] . 'ß' . $userbs[12] . 'Þ' . $userbs[13] . 'ß' . $userbs[14] . 'Þ' . $userbs[15]; 
			}
			if ($_REQUEST['type'] == 'shadow') 
			{ 
				$usereff = 'ß' . $userbs[10] . 'Þ' . $userbs[11] . 'ßonÞ' . $_REQUEST['color'] . 'ß' . $userbs[14] . 'Þ' . $userbs[15]; 
			}
			if ($_REQUEST['type'] == 'glow') 
			{ 
				$usereff = 'ß' . $userbs[10] . 'Þ' . $userbs[11] . 'ß' . $userbs[12] . 'Þ' . $userbs[13] . 'ßonÞ' . $_REQUEST['color']; 
			}
			if (($_REQUEST['type'] != 'glow') && ($_REQUEST['type'] != 'shadow') && ($_REQUEST['type'] != 'color')) 
			{ 
				$usereff = 'ß' . $userbs[10] . 'Þ' . $userbs[11] . 'ß' . $userbs[12] . 'Þ' . $userbs[13] . 'ß' . $userbs[14] . 'Þ' . $userbs[15]; 
			}
			if ($_REQUEST['type'] == 'tcolor') 
			{ 
				$usercustitle = 'ßonÞ' . $_REQUEST['color'] . 'ß' . $userbs[20] . 'Þ' . $userbs[21] . 'ß' . $userbs[22] . 'Þ' . $userbs[23] . 'ß' . $userbs[24] . 'Þ' . $userbs[25] . 'ß' . $userbs[26] . 'Þ' . $userbs[27]; 
			}
			if ($_REQUEST['type'] == 'tglow') 
			{ 
				$usercustitle = 'ß' . $userbs[18] . 'Þ' . $userbs[19] . 'ßonÞ' . $_REQUEST['color'] . 'ß' . $userbs[22] . 'Þ' . $userbs[23] . 'ß' . $userbs[24] . 'Þ' . $userbs[25] . 'ß' . $userbs[26] . 'Þ' . $userbs[27]; 
			}
			if ($_REQUEST['type'] == 'tshadow') 
			{ 
				$usercustitle = 'ß' . $userbs[18] . 'Þ' . $userbs[19] . 'ß' . $userbs[20] . 'Þ' . $userbs[21] . 'ßonÞ' . $_REQUEST['color'] . 'ß' . $userbs[24] . 'Þ' . $userbs[25] . 'ß' . $userbs[26] . 'Þ' . $userbs[27]; 
			}
			if ($_REQUEST['type'] == 'ctitle') 
			{ 
				$usercustitle = 'ß' . $userbs[18] . 'Þ' . $userbs[19] . 'ß' . $userbs[20] . 'Þ' . $userbs[21] . 'ß' . $userbs[22] . 'Þ' . $userbs[23] . 'ßonÞ' . $_REQUEST['newtitle'] . 'ßoffÞ0'; 
				$sql = "UPDATE " . USERS_TABLE . " 
					SET user_specmsg = '' 
					WHERE username = '{$userdata[username]}'";
				if (!($db->sql_query($sql))) 
				{ 
					message_die(GENERAL_ERROR, 'Could not clear user special messages', '', __LINE__, __FILE__, $sql);
				}
			}
	
			if (($_REQUEST['type'] != 'tglow') && ($_REQUEST['type'] != 'tshadow') && ($_REQUEST['type'] != 'tcolor') && ($_REQUEST['type'] != 'ctitle')) 
			{ 
				$usercustitle = 'ß' . $userbs[18] . 'Þ' . $userbs[19] . 'ß' . $userbs[20] . 'Þ' . $userbs[21] . 'ß' . $userbs[22] . 'Þ' . $userbs[23] . 'ß' . $userbs[24] . 'Þ' . $userbs[25] . 'ß' . $userbs[26] . 'Þ' . $userbs[27]; 
			}
			if ($_REQUEST['type'] != 'cutitle') 
			{ 
				$ussql = "UPDATE " . USERS_TABLE . " 
					SET user_effects = '$usereff', user_privs = '$userprs', user_custitle = '$usercustitle' 
					WHERE username = '{$userdata[username]}'";
				if ( !($db->sql_query($ussql)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Could not update user effects, permissions or custom title', '', __LINE__, __FILE__, $sql);
				}
			}
			if ($_REQUEST['type'] == 'cusername') 
			{ 
				$sql = "UPDATE " . USERS_TABLE . " 
					SET username = '" . $_REQUEST['newname'] . "' 
					WHERE username = '" . $userdata[username] . "'";
				if (!($db->sql_query($sql))) 
				{ 
					message_die(GENERAL_ERROR, 'Could not update user new name', '', __LINE__, __FILE__, $sql);
				}
			}
			
			if ($_REQUEST['type'] == 'cutitle') 
			{
				$usercustitle = explode('ß', $ucrow['user_custitle']);
				$usercount = sizeof($usercustitle);
				$cuserbs = array();
				for ($x = 0; $x < $usercount; $x++) 
				{ 
					$temppriv = explode('Þ', $usercustitle[$x]); 
					$cuserbs[] = $temppriv[0]; 
					$cuserbs[] = $temppriv[1]; 
				}
				$usercustitle = 'ß' . $cuserbs[2] . 'Þ' . $cuserbs[3] . 'ß' . $cuserbs[4] . 'Þ' . $cuserbs[5] . 'ß' . $cuserbs[6] . 'Þ' . $cuserbs[7] . 'ßonÞ' . $_REQUEST['newtitle'] . 'ß' . $cuserbs[8] . 'Þ' . $cuserbs[9];
				$usermessage = $userdata[username] . ' ' . $lang['Has_Changed'] . ' <i>' . $_REQUEST['newtitle'] . '</i>. ' . $lang['If_Inapp'];	

				$sql = "UPDATE " . USERS_TABLE . " 
					SET user_custitle = '$usercustitle', user_specmsg = '$usermessage' 
					WHERE username = '" . $_REQUEST['tchangename'] . "'";
				if ( !($db->sql_query($sql)) ) 
				{ 
					message_die(GENERAL_ERROR, 'Could not update user custom title', '', __LINE__, __FILE__, $sql);
				}
			}
			
			$page_title = $lang['Buy_Special_Ability'];
			$shoplocation = ' -> <a href="'.append_sid("shop.$phpEx") . '" class="nav">' . $lang['Shop'] . '</a> -> <a href="'.append_sid("shop_effects.$phpEx?action=specialshop") . '" class="nav">' . $shopstatarray[5] . '</a>';
			$shopinforow = '<tr>
				<th class="thHead">' . $lang['Information'] . '</th>
			</tr><tr>				
				<td class="row1" align="center"><br /><span class="gen">' . $lang['Purchase_Success'] . '<br /><br />' . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>") . '<br />&nbsp;</span></td>
			</tr>';

// Check this is compatible with 2.6.0!!
//			if( $specialcost != 0 )
//			{
//			$sql = "INSERT INTO " . SHOPTRANS_TABLE . " (shoptrans_date, trans_user, trans_item, trans_type, trans_total) VALUES (" . time() . ", '" . $userdata['username'] . "', '$type', 'Bought', '" . $specialcost . "')"; }
//			if( !$db->sql_query($sql) )
//			{
//				message_die(GENERAL_ERROR, "Could not insert data into transaction table", '', __LINE__, __FILE__, $sql);
//			}

		}
	}
	elseif ($_REQUEST['bs'] == $lang['Sell']) 
	{
		if (($_REQUEST['type'] == 'avatar') && (($userbs[2] == 'off') || ($userbs[2] == 'no'))) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_No_Av_Permiss']); 
		}
		if (($_REQUEST['type'] == 'sig') && (($userbs[4] == 'off') || ($userbs[4] == 'no'))) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_No_Sig_Permiss']); 
		}
		if (($_REQUEST['type'] == 'title') && (($userbs[6] == 'off') || ($userbs[6] == 'no'))) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_No_Title_Permiss']); 
		}
		if (($_REQUEST['type'] == 'color') && (($userbs[10] == 'off') || ($userbs[10] == 'no'))) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_No_Color_Name']); 	
		}
		if (($_REQUEST['type'] == 'shadow') && (($userbs[12] == 'off') || ($userbs[12] == 'no'))) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_No_Shadow_Name']); 
		}
		if (($_REQUEST['type'] == 'glow') && (($userbs[14] == 'off') || ($userbs[14] == 'no'))) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_No_Glow_Name']); 
		}
		if (($_REQUEST['type'] == 'tcolor') && (($userbs[18] == 'off') || ($userbs[18] == 'no'))) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_No_Color_Title']); 
		}
		if (($_REQUEST['type'] == 'tglow') && (($userbs[20] == 'off') || ($userbs[20] == 'no'))) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_No_Glow_Title']); 
		}
		if (($_REQUEST['type'] == 'tshadow') && (($userbs[22] == 'off') || ($userbs[22] == 'no'))) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_No_Shadow_Title']); 
		}
		if (($_REQUEST['type'] == 'ctitle') && (($userbs[24] == 'off') || ($userbs[24] == 'no') || ($userbs[26] == 'on'))) 
		{ 
			message_die(GENERAL_MESSAGE, $lang['Error_No_Custom_Title']); 
		}

		if ($_REQUEST['type'] == 'avatar') 
		{ 
			$userprs = 'ßoffÞ' . $userbs[3] . 'ß' . $userbs[4] . 'Þ' . $userbs[5] . 'ß' . $userbs[6] . 'Þ' . $userbs[7]; 
		}
		if ($_REQUEST['type'] == 'sig') 
		{ 
			$userprs = 'ß' . $userbs[2] . 'Þ' . $userbs[3] . 'ßoffÞ' . $userbs[5] . 'ß' . $userbs[6] . 'Þ' . $userbs[7]; 
		}
		if ($_REQUEST['type'] == 'title') 
		{ 
			$userprs = 'ß' . $userbs[2] . 'Þ' . $userbs[3] . 'ß' . $userbs[4] . 'Þ' . $userbs[5] . 'ßoffÞ' . $userbs[7]; 
		}
		if (($_REQUEST['type'] != 'title') && ($_REQUEST['type'] != 'sig') && ($_REQUEST['type'] != 'avatar')) 
		{ 
			$userprs = 'ß' . $userbs[2] . 'Þ' . $userbs[3] . 'ß' . $userbs[4] . 'Þ' . $userbs[5] . 'ß' . $userbs[6] . 'Þ' . $userbs[7]; 
		}
		if ($_REQUEST['type'] == 'color') 
		{ 
			$usereff = 'ßoffÞ0ß' . $userbs[12] . 'Þ' . $userbs[13] . 'ß' . $userbs[14] . 'Þ' . $userbs[15]; 
		}
		if ($_REQUEST['type'] == 'shadow') 
		{ 
			$usereff = 'ß' . $userbs[10] . 'Þ' . $userbs[11] . 'ßoffÞ0ß' . $userbs[14] . 'Þ' . $userbs[15]; 
		}
		if ($_REQUEST['type'] == 'glow') 
		{ 
			$usereff = 'ß' . $userbs[10] . 'Þ' . $userbs[11] . 'ß' . $userbs[12] . 'Þ' . $userbs[13] . 'ßoffÞ0'; 
		}
		if (($_REQUEST['type'] != 'glow') && ($_REQUEST['type'] != 'shadow') && ($_REQUEST['type'] != 'color')) 
		{ 
			$usereff = 'ß' . $userbs[10] . 'Þ' . $userbs[11] . 'ß' . $userbs[12] . 'Þ' . $userbs[13] . 'ß' . $userbs[14] . 'Þ' . $userbs[15]; 
		}
		if ($_REQUEST['type'] == 'tcolor') 
		{ 
			$usercustitle = 'ßoffÞ0ß' . $userbs[20] . 'Þ' . $userbs[21] . 'ß' . $userbs[22] . 'Þ' . $userbs[23] . 'ß' . $userbs[24] . 'Þ' . $userbs[25] . 'ß' . $userbs[26] . 'Þ' . $userbs[27]; 
		}
		if ($_REQUEST['type'] == 'tglow') 
		{ 
			$usercustitle = 'ß' . $userbs[18] . 'Þ' . $userbs[19] . 'ßoffÞ0ß' . $userbs[22] . 'Þ' . $userbs[23] . 'ß' . $userbs[24] . 'Þ' . $userbs[25] . 'ß' . $userbs[26] . 'Þ' . $userbs[27]; 
		}
		if ($_REQUEST['type'] == 'tshadow') 
		{ 
			$usercustitle = 'ß' . $userbs[18] . 'Þ' . $userbs[19] . 'ß' . $userbs[20] . 'Þ' . $userbs[21] . 'ßoffÞ0ß' . $userbs[24] . 'Þ' . $userbs[25] . 'ß' . $userbs[26] . 'Þ' . $userbs[27]; 
		}
		if ($_REQUEST['type'] == 'ctitle') 
		{ 
			$usercustitle = 'ß' . $userbs[18] . 'Þ' . $userbs[19] . 'ß' . $userbs[20] . 'Þ' . $userbs[21] . 'ß' . $userbs[22] . 'Þ' . $userbs[23] . 'ßoffÞ0ß' . $userbs[26] . 'Þ' . $userbs[27]; 
		}
		if (($_REQUEST['type'] != 'tglow') && ($_REQUEST['type'] != 'tshadow') && ($_REQUEST['type'] != 'tcolor') && ($_REQUEST['type'] != 'ctitle')) 
		{ 
			$usercustitle = 'ß' . $userbs[18] . 'Þ' . $userbs[19] . 'ß' . $userbs[20] . 'Þ' . $userbs[21] . 'ß' . $userbs[22] . 'Þ' . $userbs[23] . 'ß' . $userbs[24] . 'Þ' . $userbs[25] . 'ß' . $userbs[26] . 'Þ' . $userbs[27]; 
		}
		$ussql = "UPDATE " . USERS_TABLE . " 
			SET user_effects = '$usereff', user_privs = '$userprs', user_custitle = '$usercustitle' 
			WHERE username = '" . $userdata[username] . "'";
		if ( !($db->sql_query($ussql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not update user effects, permission or custom title', '', __LINE__, __FILE__, $sql);
		}

		$page_title = $lang['Sell_Special_Ability'];
		$shoplocation = ' -> <a href="'.append_sid("shop.$phpEx") . '" class="nav">' . $lang['Shop'] . '</a> -> <a href="'.append_sid("shop_effects.$phpEx?action=specialshop") . '" class="nav">' . $shopstatarray[5] . '</a>';

		$shopinforow = '<tr>
			<th class="thHead">' . $lang['Information'] . '</th>
		</tr><tr>
				<td class="row1" align="center"><br /><span class="gen">' . $lang['Sell_Success'] . '<br /><br />' . sprintf($lang['Click_return_special_shop'], "<a href=\"" . append_sid("shop_effects.$phpEx?action=specialshop") . "\">", "</a>") . '<br />&nbsp;</span></td>
		</tr>';
	}

	// Start of personal information
	$personal = '<tr><td class="catBottom" align="center"><a href="' . append_sid('shop.'.$phpEx.'?action=inventory&searchid=' . $userdata['user_id']) . '" class="cattitle">' . $lang['Your_Inventory'] . '</a></td></tr>'; 
	if (strlen($userdata['user_specmsg']) > 2) 
	{ 
		$personal .= '</table><br /><table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><tr><th class="thHead">' . $lang['Information'] . '</th></tr>'; 
		$personal .= '<tr><td class="row1" height="50" align="center"><span class="gen">' . $userdata['user_specmsg'] . '</span></td></tr>'; 
		$personal .= '<tr><td class="row1" colspan="2" align="center">[ <a href="' . append_sid('shop.'.$phpEx.'?clm=true') . '">' . $lang['Clear_Messages'] . '</a> ]</td></tr>';
	}

	$template->assign_vars(array(
		'SHOPPERSONAL' => $personal,
		'SHOPLOCATION' => $shoplocation,
		'SHOPTABLEROWS' => 1,
		'SHOPLIST' => $shopinfo,
		'L_SHOP_TITLE' => $page_title,
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
<?php 
/** 
*
* @package phpBB2
* @version $Id: profile_menu.php,v 1.0.5 2005/08/22 12:33:06 grahamje Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Include language files
//
$language = $board_config['default_lang'];

//
// Buddylist, returns users buddylist
//
$sql = "SELECT DISTINCT b.contact_id, u.username, u.user_level  
	FROM " . BUDDY_LIST_TABLE . " b, " . USERS_TABLE . " u 
	WHERE b.user_ignore = 0
		AND u.user_id = b.contact_id
		AND b.user_id = " . $userdata['user_id'];
if ( !$buddies = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not obtain usercp buddylist information', '', __LINE__, __FILE__, $sql);
}
	
while ( $line = $db->sql_fetchrow($buddies) )
{
    $online_sql = "SELECT session_logged_in 
   		FROM " . SESSIONS_TABLE . "
       	WHERE session_user_id = " . $line['contact_id'] . " 
       		AND session_time >= " . ( time() - ($board_config['whosonline_time'] * 60) );
   	if ( !$online_result = $db->sql_query($online_sql) )
   	{
		message_die(GENERAL_ERROR, 'Could not obtain usercp buddylist online information', '', __LINE__, __FILE__, $sql);
   	}
   
   	$online = $db->sql_fetchrow($online_result);
    $db->sql_freeresult($online_result);

   	if ( $online['session_logged_in'] == 1 )
   	{
       	$online_buddies = true;
       	$online_offline = 'online_buddies';
       	$img_online_offline = $images['icon_buddy_on'];
   	}
   	else
   	{
   	    $offline_buddies = true;
   	    $online_offline = 'offline_buddies';
   	    $img_online_offline = $images['icon_buddy_off'];
   	}

   	$template->assign_block_vars($online_offline, array(
   	    'S_BUDDY_NAME' => username_level_color($line['username'], $line['user_level'], $line['contact_id']),
   	    'IMG_BUDDY_ONLINE_OFFLINE' => $img_online_offline,
   	    'BUDDY_PM_IMG' => '<img src="' . $images['icon_buddy_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" />',
   	    'U_BUDDY_REMOVE' => append_sid('profile.'.$phpEx.'?mode=removebuddy&amp;' . POST_USERS_URL . '=' . $line['contact_id']),
     	'U_BUDDY_PM' => append_sid('privmsg.'.$phpEx.'?mode=post&amp;' . POST_USERS_URL . '=' . $line['contact_id']),
 	    'U_BUDDY_PROFILE' => append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $line['contact_id']),
   	    'BUDDY_REMOVE_IMG' => '<img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />')
   	);
}
$db->sql_freeresult($buddies);

if ( !$online_buddies && !$offline_buddies )
{
    $template->assign_block_vars('usercp_no_buddies', array(
        'L_BUDDY_NONE' => $lang['UCP_BuddyNone'])
    );
}

// User Points, Personal Shop links, and Bank balances (if enabled)
if ($board_config['points_post'] || $board_config['points_browse'])
{
	if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_shop.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_shop.' . $phpEx);

	$template->assign_block_vars('switch_points_on', array(	
		'U_MYPOINTS' => append_sid('profile_mypoints.'.$phpEx),
		'U_VIEW_TRANS_TO' => append_sid('viewtransto.'.$phpEx),
		'U_VIEW_TRANS_FROM' => append_sid('viewtransfrom.'.$phpEx),
		'U_TRANSACTIONS' => append_sid('transactions.'.$phpEx),
		'U_INVENTORY' => append_sid('shop.'.$phpEx.'?action=inventory&amp;searchid=' . $userdata['user_id']),
 		
 		'L_INVENTORY' => $lang['Your_Inventory'],
		'L_TRANSACTIONS' => $lang['Global_Trans'],	
		'L_MYPOINTS' => $lang['My_Trans'],
		'L_POINTS' => $board_config['points_name'])
	);

	if ($board_config['points_donate'])
	{
		$template->assign_block_vars('switch_points_on.switch_donate_on', array(	
			'L_DONATE' => sprintf($lang['Points_donate'], '', '') . ' ' . $board_config['points_name'],
	 		'U_DONATE' => append_sid('pointscp.'.$phpEx.'?mode=donate'))
		);

	}
	
	if ($board_config['u_shops_enabled'])
	{
		$template->assign_block_vars('switch_points_on.switch_usershop_on', array(	
			'L_MYSHOP' => $lang['Personal_shop'],
			'U_MYSHOP' => append_sid('shop_users_edit.'.$phpEx))
		);
		
		if ($board_config['shop_trans_enable'])
		{
			$template->assign_block_vars('switch_points_on.switch_shoptrans_on', array(	
 				'L_SHOP_TRANSACTIONS' => $lang['Shop_Transaction'],
 				'U_SHOP_TRANSACTIONS' => append_sid('shop_transactions.'.$phpEx))
			);
		}
	}
	
	if ($board_config['bankopened'])
	{
		$sql = "SELECT holding 
			FROM " . BANK_TABLE . " 
			WHERE user_id = " . $userdata['user_id']; 
		if ( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not obtain user bank balance.', '', __LINE__, __FILE__, $sql);
		} 
		$bankrow = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
	
		$template->assign_block_vars($cpl_main . 'switch_bank_on', array(
			'L_BANK_BALANCE' => $lang['Bank_balance'],
			'BANK_BALANCE' => ((!empty($bankrow['holding'])) ? number_format($bankrow['holding']) : 0) . ' ' . $board_config['points_name'],
			'U_BANK' => append_sid('bank.'.$phpEx))
		);
	}
}

// Referrals
if ($board_config['referral_enable'])
{
	$template->assign_block_vars($cpl_main . 'switch_referral_on', array(
		'L_REFERRAL' => $lang['Referral_System'],
		'U_REFERRAL' => sprintf($lang['Referral_Text'], $board_config['referral_reward'], $board_config['points_name']) . ' <a href="' . real_path('profile.'.$phpEx.'?mode=' . REGISTER_MODE . '&amp;ruid=' . $userdata['user_id']) . '"><i>' . real_path('profile.'.$phpEx.'?mode=' . REGISTER_MODE . '&amp;ruid=' . $userdata['user_id']) . '</i></a>')
	);
}

// Notes
if ($board_config['enable_user_notes'])
{
	$template->assign_block_vars('ucp_notes_on', array(
		'L_NOTES' => $lang['Notes'],
		'U_CPL_NOTES' => ($userdata['user_popup_notes']) ? 'javascript:notes()' : append_sid('profile_notes.'.$phpEx))
	);
}

// Biorhythm
if ($userdata['user_birthday'] != 999999)
{
	$template->assign_block_vars('ucp_bio_on', array(
		'L_BIORHYTHM' => $lang['Biorhythm'],
		'U_CPL_BIORHYTHM' => append_sid('profile_biorhythm.'.$phpEx))
	);
}

// Signature
if ($board_config['allow_sig'])
{
	$template->assign_block_vars('ucp_sig_on', array(
		'L_SIGNATURE' => $lang['Signature_panel'],
		'U_CPL_SIGNATURE' => append_sid('profile_sig_editor.'.$phpEx))
	);
}

// Profile pic
if ($board_config['allow_photo_upload'] || $board_config['allow_photo_remote'])
{
	$template->assign_block_vars('ucp_pic_on', array(
		'L_PHOTO_PANEL' => $lang['Photo_panel'],
		'U_CPL_PHOTO_PANEL' => append_sid('profile.'.$phpEx.'?mode=editprofile&ucp=photo'))
	);
}

// Email digests
@include($phpbb_root_path . 'cache/config_digest.'.$phpEx);
if (!$digest_config['digest_disable_user'])
{
	$template->assign_block_vars('ucp_digests_on', array(
		'L_DIGESTS' => $lang['Digests'], 
		'U_DIGESTS' => append_sid('digests_user_cp.'.$phpEx))
	);

}

//
// Calculate the number of days this user has been a member ($memberdays)
//
$regdate = $userdata['user_regdate'];
$memberdays = max(1, round( ( time() - $regdate ) / 86400 ));

// Assign template variables		
$template->set_filenames(array(
	'cpl_menu_output' => 'profile_menu_body.tpl')
);

$template->assign_vars(array(
	'L_CPL_OVERVIEW' => $lang['Cpl_Overview'],
	'L_USERCP_EXPLAIN' => $lang['User_CP_explain'],
	'L_CPL_PERSONAL_PROFILE' => $lang['Profile_settings'],
	'L_CPL_REG_INFO' => $lang['Registration_info'],
	'L_CPL_PROFILE_INFO' => $lang['Profile_info'],
	'L_CPL_AVATAR_PANEL' => $lang['Avatar_panel'],
	'L_CPL_BOARD_SETTINGS' => $lang['Cpl_Board_Settings'],
	'L_CPL_PREFERENCES' => $lang['Preferences'],
	'L_CPL_PRIVATE_MESSAGES' => $lang['Private_Messages'],
	'L_CPL_NEWMSG' => $lang['Send_a_new_message'],
	'L_WATCHING' => $lang['Watching'],
	'L_SEARCH_TOPICS' => $lang['Your_topics'], 
	'L_PERSONAL_GALLERY' => $lang['Personal_gallery'],
	'L_USER_ACP' => $lang['Attachments'],

    'L_ONLINE' => $lang['Online'],
    'L_OFFLINE' => $lang['Offline'],
    'L_BUDDY_LIST' => $lang['Buddy_list'],
    'L_BUDDY_PM' => $lang['PM'],
    	
	'L_USERCP_WELCOME' => $lang['Your_activity'], 
	'L_JOINED'=> $lang['Joined'],
	'L_MEMBER_FOR' => $lang['Member_for'],
	'L_DAYS' => ($memberdays == 1) ? $lang['Day'] : $lang['Days'],
	'L_POSTS'=> $lang['Total_posts'],
	'L_RANK' => $lang['RankFAQ_Title'],
	'L_LOGON' => $lang['Last_logon'], 
		
	'RANK_IMAGE' => $rank_image,
	'JOINED' => create_date($lang['DATE_FORMAT'], $regdate, $board_config['board_timezone']),
	'MEMBER_FOR' => $memberdays,
	'LAST_LOGON' => ($userdata['user_lastlogon']) ? create_date($board_config['default_dateformat'], $userdata['user_lastlogon'], $board_config['board_timezone']) : $lang['Hidden_email'], 
	'POSTS' => $userdata['user_posts'],
	'IP_ADDRESS' => decode_ip($user_ip),
	'IP_HOST' => gethostbyaddr(decode_ip($user_ip)),
	
	'L_VIEW_ALL' => $lang['All_available'],
	'U_MYPOINTS_TO' => append_sid('profile_mypoints_to.'.$phpEx),
	'U_MYPOINTS_FROM' => append_sid('profile_mypoints_from.'.$phpEx),

	'L_DONATIONS' => $lang['LW_DONATIONS'],
	'U_DONATIONS' => append_sid('profile_donations.'.$phpEx),

    'L_FORUM' => $lang['Forum'],
    'L_TOPIC' => $lang['Topic'],
    'L_REPLIES' => $lang['Replies'],
    'L_VIEWS' => $lang['Views'],
    'L_LAST_POST' => $lang['Last_Post'],
    'L_MARK' => $lang['Mark'],

	'L_LW_TRANSACTIONS' => $lang['LW_TRANSACTION_RECORDS'],
	'L_LW_PAYMENTS' => $lang['L_LW_PAYMENTS'],
	'U_LW_TRANSACTIONS' => append_sid('profile_subscriptions.'.$phpEx),
	'U_LW_PAYMENTS' => append_sid('lwtopup.'.$phpEx),

	'U_SEARCH_TOPICS' => append_sid('profile_topics.'.$phpEx),
	'U_WATCHED_TOPICS' => append_sid('profile_watching.'.$phpEx),
	'U_SEARCH_POSTS' => append_sid('search.'.$phpEx.'?search_author=' . urlencode($userdata['username']) . '&amp;showresults=posts'),
	'U_CPL_OVERVIEW' => append_sid('profile.'.$phpEx.'?mode=editprofile&ucp=main'),
	'U_CPL_REGISTRATION_INFO' => append_sid('profile.'.$phpEx.'?mode=editprofile&ucp=reg_info'),
	'U_CPL_PROFILE_INFO' => append_sid('profile.'.$phpEx.'?mode=editprofile&ucp=profile_info'),
	'U_CPL_AVATAR_PANEL' => append_sid('profile.'.$phpEx.'?mode=editprofile&ucp=avatar'),
	'U_CPL_PREFERENCES' => append_sid('profile.'.$phpEx.'?mode=editprofile&ucp=preferences'),
	'U_CPL_NEWMSG' => append_sid('privmsg.'.$phpEx.'?mode=post'))
);

$template->assign_var_from_handle('CPL_MENU_OUTPUT', 'cpl_menu_output');

?>
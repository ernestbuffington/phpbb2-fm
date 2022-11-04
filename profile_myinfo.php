<?php
/***************************************************************************
 *								profile_myinfo.php
 *								-------------------
 *   begin					: Friday, Apr 23, 2004
 *   copyright				: (C) 2004 ErDrRon
 *   email					: ErDrRon@aol.com
 *
 *   $Id: profile_myinfo.php,v 1.0.0 2004/04/23, 19:04:00 erdrron Exp $
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

// Include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_myinfo.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_myinfo.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

if ( !$board_config['myInfo_enable'] ) 
{
	message_die(GENERAL_MESSAGE, $lang['myInfo_disabled']); 
}

$user_id = ( isset($HTTP_GET_VARS[POST_USERS_URL]) ) ? intval($HTTP_GET_VARS[POST_USERS_URL]) : '';

//
// Pull myInfo data for the user in question
//
$sql = "SELECT username, user_level, user_info
	FROM " . USERS_TABLE . " 
	WHERE user_id = " . $user_id;
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query User table.', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);

$username = username_level_color($row['username'], $row['user_level'], $user_id);
$myInfo = $row['user_info'];

if ( !$myInfo )
{
	$myInfo = $lang['No_myinfo'] .  'No information given by ' . $username;
}

//
// Assign page template
//
$gen_simple_header = TRUE;
$page_title = $board_config['myInfo_name'];
include($phpbb_root_path .'includes/page_header.'. $phpEx);
$template->set_filenames(array(
	'body' => 'profile_myinfo_body.tpl')
);

//
// Assign labels
//
$template->assign_vars(array(
	'L_MYINFO_POPUP_TITLE' => $page_title,
	'L_MYINFO_POPUP_INSTRUCTIONS' => $board_config['myInfo_instructions'],
	'L_CLOSE_WINDOW' => $lang['Close_window'],

	'USERNAME' => $username,
	'MYINFO' => $myInfo,
	'VERSION' => $board_config['myInfo_version'])
);

$template->pparse('body');

include($phpbb_root_path .'includes/page_tail.'. $phpEx);

?>
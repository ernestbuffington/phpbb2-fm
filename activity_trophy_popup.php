<?php
/***************************************************************************
 *                              activity_trophy_popup.php
 *                            -----------------------------
 *		Version			: 1.1.0
 *		Email			: austin@phpbb-amod.com
 *		Site			: http://phpbb-amod.com
 *		Copyright		: aUsTiN-Inc 2003/5 
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
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_amod_plus.'. $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ACTIVITY);
init_userprefs($userdata);
//
// End session management
//

$gen_simple_header 	= TRUE; 
$gen_simple_footer 	= TRUE; 
			
$template->set_filenames(array(
	'body' => 'amod_files/activity_tp_body.tpl')
);
		
$sql = "SELECT username
	FROM " . USERS_TABLE . "
	WHERE user_id = '" . $_GET['user'] . "'"; 
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain username.', '', __LINE__, __FILE__, $sql);
} 
$row = $db->sql_fetchrow($result);
		
$page_title = str_replace("%u%", $row['username'], $lang['trophy_popup_title']);

$template->assign_vars(array(
	'TITLE_2' => $lang['trophy_popup_left'],
	'TITLE_3' => $lang['trophy_popup_right'])
);	
	
$i = 0;
$sql = "SELECT *
	FROM ". INA_TROPHY ."
	WHERE player = '". $_GET['user'] ."'"; 
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain trophy data.', '', __LINE__, __FILE__, $sql);
} 

while ($row = $db->sql_fetchrow($result))
{
	$sql = "SELECT *
		FROM ". iNA_GAMES ."
		WHERE game_name = '". $row['game_name'] ."'"; 
	if ( !($result1 = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain game data.', '', __LINE__, __FILE__, $sql);
	}
	$row1 = $db->sql_fetchrow($result1);
	
	$image = '<b class="genmed">' . $row1['proper_name'] . '</b><br />' . PopupImages($row1['game_name']);
	$score = $row['score'];

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('trophy_rows', array(
		'ROW_CLASS'	=> $row_class,
		'IMAGE' => $image,		
		'SCORE' => FormatScores($score))
	);		
	$i++;
	
	$db->sql_freeresult($result1);
}
$db->sql_freeresult($result);

include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
					
?>
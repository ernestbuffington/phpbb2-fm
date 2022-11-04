<?php
/***************************************************************************
 *                            activity_hof.php
 *                           ------------------
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

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Start Restriction Checks 
//
BanCheck();				
//
// End Restriction Checks
//
		
$mode = ($_POST['mode']) ? $_POST['mode'] : $HTTP_POST_VARS['mode'];
	
if ($mode == 'save')
{
	$use_online 		= intval( (($_POST['online']) ? $_POST['online'] : $HTTP_POST_VARS['online']) );
	$use_daily 			= intval( (($_POST['daily']) ? $_POST['daily'] : $HTTP_POST_VARS['daily']) );
	$use_new 			= intval( (($_POST['new']) ? $_POST['new'] : $HTTP_POST_VARS['new']) );		
	$use_new_count 		= intval( (($_POST['new_count']) ? $_POST['new_count'] : $HTTP_POST_VARS['new_count']) );
	$use_games 			= intval( (($_POST['games']) ? $_POST['games'] : $HTTP_POST_VARS['games']) );
	$use_games_count 	= intval( (($_POST['game_count']) ? $_POST['game_count'] : $HTTP_POST_VARS['game_count']) );
	$use_info 			= intval( (($_POST['info']) ? $_POST['info'] : $HTTP_POST_VARS['info']) );
	# info-1;;daily-1;;newest-1;;newest_count-5;;games-1;;games_count-10;;online-1
	
	$compiled			= '';
	$compiled			.= 'info-'. $use_info .';;';
	$compiled			.= 'daily-'. $use_daily .';;';
	$compiled			.= 'newest-'. $use_new .';;';
	$compiled			.= 'newest_count-'. (($use_new_count > 0) ? $use_new_count : 1) .';;';
	$compiled			.= 'games-'. $use_games .';;';
	$compiled			.= 'games_count-'. (($use_games_count > 0) ? $use_games_count : 1) .';;';
	$compiled			.= 'online-'. $use_online;						
	
	$q = "UPDATE " . USERS_TABLE . "
		SET ina_settings = '" . $compiled . "'
		WHERE user_id = " . $userdata['user_id'];
	$db->sql_query($q);
	
	message_die(GENERAL_MESSAGE, $lang['games_settings_finished'] . '<br /><br />' . sprintf($lang['games_settings_return'], '<a href="' . append_sid('activity.'.$phpEx) . '">', '</a>'));
}
		
$template->set_filenames(array(
	'body' => 'amod_files/activity_settings_body.tpl') 
);
make_jumpbox('viewforum.'.$phpEx);

$user_amod_settings 	= $userdata['ina_settings'];
$decifer_settings		= explode(';;', $user_amod_settings);
$decifer_info 			= explode('-', $decifer_settings[0]);
$user_use_info 			= $decifer_info[1];
$decifer_daily 			= explode('-', $decifer_settings[1]);
$user_use_daily 		= $decifer_daily[1];
$decifer_newest 		= explode('-', $decifer_settings[2]);
$user_use_newest 		= $decifer_newest[1];	
$decifer_newest_count 	= explode('-', $decifer_settings[3]);
$user_use_newest_count	= $decifer_newest_count[1];	
$decifer_games 			= explode('-', $decifer_settings[4]);
$user_use_games 		= $decifer_games[1];	
$decifer_games_count 	= explode('-', $decifer_settings[5]);
$user_use_games_count 	= $decifer_games_count[1];	
$decifer_online			= explode('-', $decifer_settings[6]);			
$user_use_online 		= $decifer_online[1];
													
$template->assign_vars(array(
	'L_TITLE' => $page_title,
	'L_GAMES'			=> $lang['games_settings_games'],
	'V_GAMES'			=> (($user_use_games) ? '<input type="radio" name="games" value="1" checked="checked"> '. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="games" value="0"> '. $lang['radio_no'] : '<input type="radio" name="games" value="1"> '. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="games" value="0" checked="checked"> '. $lang['radio_no']),
	'L_GAMES_COUNT'		=> $lang['games_settings_games_count'],
	'V_GAMES_COUNT'		=> $user_use_games_count,
	'L_INFO'			=> $lang['games_settings_info'],
	'V_INFO'			=> (($user_use_info) ? '<input type="radio" name="info" value="1" checked="checked"> '. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="info" value="0"> '. $lang['radio_no'] : '<input type="radio" name="info" value="1"> '. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="info" value="0" checked="checked"> '. $lang['radio_no']),
	'L_DAILY'			=> $lang['games_settings_daily'],
	'V_DAILY'			=> (($user_use_daily) ? '<input type="radio" name="daily" value="1" checked="checked"> '. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="daily" value="0"> '. $lang['radio_no'] : '<input type="radio" name="daily" value="1"> '. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="daily" value="0" checked="checked"> '. $lang['radio_no']),
	'L_ONLINE'			=> $lang['games_settings_online'],
	'V_ONLINE'			=> (($user_use_online) ? '<input type="radio" name="online" value="1" checked="checked"> '. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="online" value="0"> '. $lang['radio_no'] : '<input type="radio" name="online" value="1"> '. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="online" value="0" checked="checked"> '. $lang['radio_no']),
	'L_NEW'				=> $lang['games_settings_new'],
	'V_NEW'				=> (($user_use_newest) ? '<input type="radio" name="new" value="1" checked="checked"> '. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="new" value="0"> '. $lang['radio_no'] : '<input type="radio" name="new" value="1"> '. $lang['radio_yes'] .'&nbsp;&nbsp;<input type="radio" name="new" value="0" checked="checked"> '. $lang['radio_no']),
	'L_NEW_COUNT'		=> $lang['games_settings_new_count'],
	'V_NEW_COUNT'		=> $user_use_newest_count,
	'SUBMIT'			=> $lang['games_settings_submit'])
);	
		
$template->pparse('body');

?>
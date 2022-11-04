<?php
/***************************************************************************
 *                             game.php
 *                            ----------
 *		Version			: 1.1.0
 *		Email			: austin@phpbb-amod.com
 *		Site			: http://phpbb-amod.com
 *		Copyright		: aUsTiN-Inc 2003/5
 *
 ***************************************************************************/
 
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path .'extension.inc');
include($phpbb_root_path .'common.'. $phpEx);
include($phpbb_root_path .'includes/functions_amod_plus.'. $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PLAYING_GAMES);
init_userprefs($userdata);
//
// End session management
//

if ($userdata['user_session_page'] != PAGE_PLAYING_GAMES)
{
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_session_page = '" . PAGE_PLAYING_GAMES . "'
		WHERE user_id = " . $userdata['user_id'];
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['no_user_update'], '', __LINE__, __FILE__, $sql);
	}
}

CheckGamesPerDayMax($userdata['user_id'], $userdata['username']);
	
/* Start Restriction Checks */
BanCheck();				
/* End Restriction Checks */

$game_id	= (isset($HTTP_GET_VARS['id'])) ? intval($HTTP_GET_VARS['id']) : 0;
$cheat_var	= time();

$sql = "SELECT *
	FROM ". INA_CHEAT ."
	WHERE game_id = '". $game_id ."'
		AND player = " . $userdata['user_id'];
$result = $db->sql_query($sql);
$row 	= $db->sql_fetchrow($result);

if(!$row['player'] || $row['game_id'] != $game_id) 
{
	message_die(GENERAL_MESSAGE, $lang['no_game_start_error_1'], $lang['no_game_start_error_2']);
}

$sql = "SELECT * 
	FROM ". iNA_GAMES ."
	WHERE game_id = '". $game_id ."'";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, $lang['no_game_data'], "", __LINE__, __FILE__, $sql);
}

$game_info = $db->sql_fetchrow($result);

#==== Start: Get highest/lowest score for playing user
$q = "SELECT score
	FROM ". iNA_SCORES ."
	WHERE player = '". addslashes(stripslashes($userdata['username'])) ."'
		AND game_name = '". $game_info['game_name'] ."'";
$q .= ($game_info['reverse_list']) ? "ORDER BY score ASC LIMIT 0, 1" : "ORDER BY score DESC LIMIT 0, 1";
	
$r = $db->sql_query($q);
$best_user_score = $db->sql_fetchrow($r);

$template->assign_vars(array(
	'BEST_USER_SCORE'	=> $lang['games_header_status'] .' '. $game_info['proper_name'] .': '. (($best_user_score['score'] > 0) ? FormatScores($best_user_score['score']) : '----'))
);
#==== End: Get highest/lowest score for playing user
		
AddJackpot($game_info['game_id'], $game_info['game_charge']);
		
if ($userdata['user_level'] <> ADMIN)
{
	if ($game_info['disabled'] <> 1)
	{
		redirect("activity.$phpEx", true);
	}
}

$game_name 		= $game_info['game_name'];
$proper_name	= $game_info['proper_name'];
$game_width 	= $game_info['win_width'];
$game_height 	= $game_info['win_height'];
$game_path 		= $game_info['game_path'];
$game_flash 	= $game_info['game_flash'];
$game_title 	= $board_config['sitename'] . $lang['game_dash'] . $lang['game_dash'] . $game_proper;
$game_reverse	= $game_info['reverse_list'];
$game_proper	= $game_info['proper_name'];
$game_type		= $game_info['game_type'];
	
if ($userdata['user_level'] == ADMIN)
{
	$proper_name	= '<a href="javascript:Trophy_Popup(\'admin/admin_activity.'. $phpEx .'?mode=edit_games&action=edit&game='. $game_info['game_id'] .'&sid='. $userdata['session_id'] .'\', \'New_Window\', \'550\', \'300\', \'yes\')" class="nav">'. $game_info['proper_name'] .'</a>';
}
else
{
	$proper_name	= $game_info['proper_name'];		
}

/* Start Users Total Games Update */	
UpdateUsersGames($userdata['user_id']);
/* End Users Total Games Update */	
	
/* Start Insert For Play Type */	
if ( ($game_flash) && ($HTTP_GET_VARS['parent']) )
{
	$sql = "UPDATE ". USERS_TABLE ."
		SET ina_last_playtype = 'parent'
		WHERE user_id = '". $userdata['user_id'] ."'";
	$db->sql_query($sql);	
}
elseif ( ($game_flash) && (!$HTTP_GET_VARS['parent']) )
{
	$sql = "UPDATE ". USERS_TABLE ."
		SET ina_last_playtype = 'popup'
		WHERE user_id = '". $userdata['user_id'] ."'";
	$db->sql_query($sql);	
}
else
{
	$sql = "UPDATE ". USERS_TABLE ."
		SET ina_last_playtype = 'parent'
		WHERE user_id = '". $userdata['user_id'] ."'";
	$db->sql_query($sql);	
}
/* End Insert For Play Type */

#==== Handle No Scoring Games
if ($game_type == '2')
{
	$HTTP_GET_VARS['parent'] = '';
}
		
if ( ($game_flash) && (!$HTTP_GET_VARS['parent']) )
{
	$template->set_filenames(array(
		'body' => 'amod_files/flash_body.tpl')
	);
	
	$template->assign_vars(array(
		'TITLE' 	=> $game_title,
		'WIDTH' 	=> $game_width,
		'HEIGHT' 	=> $game_height,
		'SWFNAME' 	=> $game_name . '.swf',
		'PATH' 		=> $game_path)
	);	
	
	$template->pparse('body');	
}
elseif ( ($game_flash) && ($HTTP_GET_VARS['parent']) )
{
	$template->set_filenames(array(
		'body' => 'amod_files/flash_body2.tpl')
	);
	
	$q = "SELECT *
		FROM ". INA_TROPHY ."
		WHERE game_name = '". $game_name ."'";
	$r 		= $db->sql_query($q);
	$row 	= $db->sql_fetchrow($r);
	
	$t_holder_id 	= $row['player'];
	$t_holder_sc 	= $row['score'];
	$t_holder_da 	= $row['date'];
	$trophy_score	= FormatScores($t_holder_sc);
	$trophy_date	= create_date($board_config['default_dateformat'], $t_holder_da, $board_config['board_timezone']);

	$q1 = "SELECT username, user_level
		FROM ". USERS_TABLE ."
		WHERE user_id = " . $t_holder_id;
	$r1 		= $db->sql_query($q1);
	$row 		= $db->sql_fetchrow($r1);
	
	$t_holder_name = $row['username'];

	$template->assign_vars(array(
		'T_HOLDER'	=> $lang['trophy_holder'],
		'T_HOLDER_1'=> username_level_color($t_holder_name, $row['user_level'], $t_holder_id),		
		'T_DATE'	=> $trophy_date,
		'T_DATE_1'	=> $lang['trophy_held_since'],
		'T_SCORE'	=> $trophy_score,
		'T_SCORE_1'	=> $lang['score_to_beat'],		
		'T_LINK'	=> username_level_color($t_holder_name, $row['user_level'], $t_holder_id) .'\'s <a href="profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $t_holder_id . '&amp;sid=' . $userdata['session_id'] . '">'. $lang['main_profile'] .'</a>',
		'T_LINK_1'	=> username_level_color($t_holder_name, $row['user_level'], $t_holder_id) .'\'s <a href="activity.'.$phpEx.'?page=trophy_search&amp;user=' . $t_holder_name . '&amp;sid=' . $userdata['session_id'] . '">'. $lang['game_profile'] .'</a>',
		'T_IMAGE'	=> 'images/activity/trophy.gif',
		'R_TITLE'	=> $lang['top_ten'],
		'NAME'		=> $proper_name,
		'TITLE' 	=> $game_title,
		'WIDTH' 	=> $game_width,
		'HEIGHT' 	=> $game_height,
		'SWFNAME' 	=> $game_name .'.swf',
		'PATH' 		=> $game_path)
	);
				
	if ($game_reverse == 1)
	{
		$order = 'ASC';
	}
	if ($game_reverse == 0)
	{
		$order = 'DESC';
	}
		
	$sql = "SELECT s.*, MAX(s.score) AS hscore, u.user_id, u.user_level
		FROM ". iNA_SCORES ." s 
			LEFT JOIN " . USERS_TABLE . " u ON u.username = s.player
		WHERE s.game_name = '". $game_name ."'
		GROUP BY s.player
		ORDER BY s.score $order  
		LIMIT 0,10";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain top ten list.', '', __LINE__, __FILE__, $sql);
	} 
	
	if ($row = $db->sql_fetchrow($result)) 
	{ 
		$p = 1; 
		do 
		{ 		 
			$runner_up_name 	= username_level_color($row['player'], $row['user_level'], $row['user_id']);
			$runner_up_score1 	= $row['hscore'];
			$runner_up_score 	= FormatScores($runner_up_score1);	
		
			$template->assign_block_vars('runner', array(				
				'R_U_NAME'	=> $runner_up_name,
				'R_U_SCORE'	=> $runner_up_score)
			);			
			$p++; 
       	} 
    	while ($row = $db->sql_fetchrow($r2)); 
	} 				
	
	include($phpbb_root_path . 'includes/page_header.'. $phpEx);
	
	$template->pparse('body');	
	
	include($phpbb_root_path . 'includes/page_tail.'. $phpEx);
}
else
{
	$template->set_filenames(array(
		'body' => $game_name . '_body.tpl')
	);

	$template->assign_vars(array(
		'USERNAME' 		=> $userdata['username'],
		'PATH' 			=> $game_path,
		'GAMELIB' 		=> $board_config['games_path'] . '/' . $board_config['gamelib_path'] . '/',
		'S_GAME_ACTION' => append_sid('newscore.'.$phpEx.'?mode=check_score&amp;game_name=' . $game_name))
	);	
	
	$template->pparse('body');	
}

?>
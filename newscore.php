<?php
/** 
*
* @package phpBB
* @version $Id: newscore.php,v 1.1.0 2005 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path .'extension.inc');
include($phpbb_root_path .'common.'. $phpEx);
include($phpbb_root_path .'includes/functions_amod_plus.'. $phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ACTIVITY);
init_userprefs($userdata);
//
// End session management
//
	
// Disable Scores Check
if ( ($board_config['ina_disable_submit_scores_m']) && ($userdata['user_id'] <> ANONYMOUS) )
{
	message_die(GENERAL_MESSAGE, $lang['score_disable_message_m'], $lang['score_disable_info']);
}

if ( ($board_config['ina_disable_submit_scores_g']) && ($userdata['user_id'] == ANONYMOUS) )
{
	message_die(GENERAL_MESSAGE, $lang['score_disable_message_g'], $lang['score_disable_info']);
}

//
// Start Restriction Check
//
BanCheck();
//
// End Restriction Check
//
			
$template->set_filenames(array(
	'body' => 'amod_files/saved_body.tpl')
); 
		 
// Deny $HTTP_GET_VARS Mode Games
if($HTTP_GET_VARS['mode'] == 'check_score' || $HTTP_GET_VARS['score'] || $HTTP_GET_VARS['game_name'])
{
	message_die(GENERAL_MESSAGE, $lang['deny_GET_mode_games_1'], $lang['deny_GET_mode_games_2']);			
}

//
// Start main
//
$cheat_name = $userdata['username'];
$name = $userdata['username']; 		
$game_name 	= (($HTTP_POST_VARS['game_name'])) ? $HTTP_POST_VARS['game_name'] : $_POST['game_name']; 
$score = (($HTTP_POST_VARS['score'])) ? $HTTP_POST_VARS['score'] : $_POST['score']; 
$gen_simple_header 	= TRUE; 
		
Gamble($score, $userdata['user_id']);
	
$sql = "SELECT * 
	FROM " . iNA_GAMES . " 
    WHERE game_name = '" . $game_name . "'"; 
if (!$result = $db->sql_query($sql)) 
{
	message_die(GENERAL_ERROR, $lang['no_game_data'], '', __LINE__, __FILE__, $sql); 
}
$game_info = $db->sql_fetchrow($result); 
		 	
if ( ($score > "0") && ($name) && ($game_info['game_type'] != 2) )
{       		            
	// Start Game Started Check
	$sql = "SELECT *
		FROM " . INA_CHEAT . "
		WHERE game_id = '" . $game_info['game_id'] . "'
			AND player = " . $userdata['user_id'];
	if (!$result = $db->sql_query($sql)) 
	{
		message_die(GENERAL_ERROR, 'Could not select user/game from cheat table.', '', __LINE__, __FILE__, $sql); 
	}
	$row = $db->sql_fetchrow($result);
	
	if ( !$row['player'] || $row['game_id'] != $game_info['game_id'] ) 
	{
		message_die(GENERAL_MESSAGE, $lang['no_game_start_error_1']);
	}
	$db->sql_freeresult($result);
		
	$sql = "DELETE FROM " . INA_CHEAT . "
		WHERE player = " . $userdata['user_id'];
	if (!$result = $db->sql_query($sql)) 
	{
		message_die(GENERAL_ERROR, 'Could not delete user from ina_cheat table.', '', __LINE__, __FILE__, $sql); 
	}
	$db->sql_freeresult($result);

	RemovePlayingGame($userdata['user_id']);		
	// End Game Started Check

	Gamble($score, $game_info['game_id']);
		
    $sql = "SELECT * 
		FROM " . iNA_SCORES . " 
        WHERE game_name = '" . $game_name . "' 
        ORDER BY score DESC"; 
	if (!$result = $db->sql_query($sql)) 
	{
		message_die(GENERAL_ERROR, $lang['no_score_data'], '', __LINE__, __FILE__, $sql); 
	}
			
	$score_info = $db->sql_fetchrow($result); 

	// Start Bonus
	$sql = "SELECT *
		FROM " . $table_prefix ."ina_top_scores
		WHERE game_name = '" . $game_name . "'";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Error Selecting Top Score.', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	
	$trophy_score = $row['score'];			
	$user_id = $userdata['user_id'];
	$bonus = 0;
			
	if ( ($game_info['reverse_list'] != 1) && ($score > $trophy_score) )
	{
		$bonus = $game_info['game_bonus'];
	}
	elseif(($game_info['reverse_list'] == 1) && ($score < $trophy_score))
	{
		$bonus = $game_info['game_bonus'];			
	}
	else
	{
		$bonus = 0;
	}
				
	if ( ($game_info['game_reward'] > 0) && ($bonus > 0) )
	{
       	$reward = (intval($score) / intval($game_info['game_reward']) + $bonus); 
	}
	elseif ( ($game_info['game_reward'] > 0) && (!$bonus) )
    {
    	$reward = (intval($score) / intval($game_info['game_reward'])); 				
	}
	elseif ( (!$game_info['game_reward']) && ($bonus > 0) )
	{
		$reward = $bonus;
	}
	else
	{
		$reward = 0;			
    }
    
    add_points($user_id, $reward); 
    
    $db->sql_freeresult($result);
	// End Bonus

	// Start Trophies
	$sql = "SELECT *
		FROM " . $table_prefix . "ina_top_scores
		WHERE game_name = '" . $game_name . "'";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Error Selecting Game Top Score data.', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	$old_score = $row['score'];
	$t_holder = $row['player'];
	$trophy_won	= '';

	$db->sql_freeresult($result);
			
	if ( ($game_info['reverse_list'] == 1) && ($score < $old_score) )
	{								
		$sql = "SELECT proper_name
			FROM " . iNA_GAMES . "
			WHERE game_name = '" . $game_name . "'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error Selecting Game proper_name.', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		
		$proper_name = $row['proper_name'];

		$db->sql_freeresult($result);
							
		$sql = "SELECT user_id
			FROM " . USERS_TABLE . "
			WHERE username = '" . phpbb_clean_username($name) . "'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error obtaining user_id.', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);

		$name_id = $row['user_id'];

		$db->sql_freeresult($result);
							
		$sql = "UPDATE " . $table_prefix . "ina_top_scores
			SET player = '" . $name_id . "', score = '" . $score . "', date = '" . time() . "'
			WHERE game_name = '" . $game_name . "'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error Updating Game Top Score.', '', __LINE__, __FILE__, $sql);
		}
			
		$trophy_won = $lang['trophy_won_notice'];
			
		$message_sent 	= $board_config['ina_pm_trophy_msg'];
		$message_sent	= str_replace('%s%', FormatScores($score), $message_sent);
		$message_sent 	= str_replace('%n%', $userdata['username'], $message_sent);
		$message_sent 	= str_replace('%g%', $proper_name, $message_sent);
		
		if ( (!$board_config['ina_disable_comments_page']) && ($board_config['ina_pm_trophy'] == 1) && ($t_holder != "-1") && ($t_holder != $userdata['user_id']) )
		{
			send_challenge_pm($t_holder, $board_config['ina_pm_trophy_sub'], $message_sent);												
		}
	}
				
	if ( ($game_info['reverse_list'] == 0) && ($score > $old_score) )
	{	
		$sql = "SELECT proper_name
			FROM " . iNA_GAMES . "
			WHERE game_name = '" . $game_name . "'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error Selecting Game proper_name.', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);

		$proper_name = $row['proper_name'];
										
		$q = "SELECT user_id
			FROM ". USERS_TABLE ."
			WHERE username = '" . phpbb_clean_username($name) . "'";
		if (!$r = $db->sql_query($q))
		{
			message_die(GENERAL_ERROR, 'Error Obtaining user_id.', '', __LINE__, __FILE__, $q);
		}
		$row = $db->sql_fetchrow($r);
		
		$name_id = $row['user_id'];
							
		$q1 = "UPDATE " . $table_prefix . "ina_top_scores
			SET player = '" . $name_id . "', score = '" . $score . "', date = '" . time() . "'
			WHERE game_name = '" . $game_name . "'";
		if (!$r1 = $db->sql_query($q1))
		{
			message_die(GENERAL_ERROR, 'Error Updating Game Top Score.', '', __LINE__, __FILE__, $q1);
		}
			
		$trophy_won = $lang['trophy_won_notice'];
		
		$message_sent 	= $board_config['ina_pm_trophy_msg'];
		$message_sent	= str_replace('%s%', FormatScores($score), $message_sent);
		$message_sent 	= str_replace('%n%', $userdata['username'], $message_sent);
		$message_sent 	= str_replace('%g%', $proper_name, $message_sent);
		
		if ( (!$board_config['ina_disable_comments_page']) && ($board_config['ina_pm_trophy'] == 1) && ($t_holder != "-1") && ($t_holder != $userdata['user_id']) )
		{
			send_challenge_pm($t_holder, $board_config['ina_pm_trophy_sub'], $message_sent);																	
		}
	}				
	// End Trophies

	// Start Jackpot
	if ($trophy_won)
	{
       	add_points($user_id, intval($game_info['jackpot'])); 
		ResetJackpot($game_info['game_id']);
  	}
	// End Jackpot

	// Start Comments
	$template->assign_block_vars('comment', array(
		'COMMENT_LINK'	=> '<a href="'. append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=comments&action=leave_comment&user='. $userdata['user_id'] .'&game='. $game_name .'\', \'New_Window\', \'400\', \'300\', \'yes\')') .'" class="mainmenu">'. $lang['trophy_comment_notice'] .'</a>')
	);
	// End Comments

	// Start Hall Of Fame
	HallOfFamePass($userdata['user_id'], $score, $game_info['game_id'], $game_info['reverse_list']);
	// End Hall Of Fame

	// Start One Score Per User
	$name = addslashes(stripslashes($userdata['username']));
	$q = "SELECT player, score
		FROM " . iNA_SCORES . "
		WHERE player = '" . $name . "'
			AND game_name = '" . $game_name . "'";
	if (!$r = $db->sql_query($q))
	{
		message_die(GENERAL_ERROR, 'Error Selecting Player Score.', '', __LINE__, __FILE__, $q);
	}
	$row = $db->sql_fetchrow($r);
	
	$exist = $row['player'];
	$e_score = $row['score'];
							
	// See if we have a score already & if its a higher score for this game
	if ( ($exist) && ($game_info['reverse_list'] == 1) && ($score < $e_score) )
	{
		$name = addslashes(stripslashes($userdata['username']));
		
		$sql = "UPDATE " . iNA_SCORES . " 
			SET score = '" . $score . "', date = '" . time() . "'
			WHERE player = '" . $name . "' 	
				AND game_name = '" . $game_name . "'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['no_score_insert'], '', __LINE__, __FILE__, $sql);
		}
		$msg = $lang['game_score_saved'];				
	}
	elseif ( ($exist) && ($game_info['reverse_list'] == 0) && ($score > $e_score) )
	{
		$name = addslashes(stripslashes($userdata['username']));
		
		$sql = "UPDATE " . iNA_SCORES . " 
			SET score = '" . $score . "', date = '" . time() . "'
			WHERE player = '" .  $name . "' 
				AND game_name = '" . $game_name . "'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['no_score_insert'], '', __LINE__, __FILE__, $sql);
		}
		$msg = $lang['game_score_saved'];				
	}
	// See if we dont have a score for this game
	elseif ( (!$exist) && (!$e_score) )
	{
		$name = addslashes(stripslashes($userdata['username']));
		
		$sql = "INSERT INTO " . iNA_SCORES . " (game_name, player, score, date)
			VALUES ('" . $game_name . "', '" . $name . "', '" . $score . "', '" . time() . "')";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['no_score_insert'], '', __LINE__, __FILE__, $sql);
		}
		$msg = $lang['game_score_saved'];
	}
	else
	{
		$msg = $lang['no_score_saved'];
	}

	$get_time = explode(';;', $userdata['ina_time_playing']);
   	$game_started = $get_time[0];
   	$game_ended = time();
   	$time_spent = ceil($game_ended - $game_started);
   
   	$q = "UPDATE ". iNA_SCORES ."
    	SET user_plays = user_plays + 1, play_time = play_time + $time_spent
        WHERE player = '$name'
        	AND game_name = '$game_name'";
   	$db->sql_query($q);
	// End One Score Per User

	// Start GE Add
	if ($trophy_won == $lang['trophy_won_notice'])
	{
		$trophy_GE = 1;
	}
		
	if ($msg == $lang['game_score_saved'])
	{
		$beat_score_GE = 1;
	}
		
	if ($userdata['ina_char_name'])
	{
		AMP_Add_GE($userdata['user_id'], $userdata['ina_char_ge'], $trophy_GE, $beat_score_GE);
	}
	// End GE Add

	// Start Get Previous Page
	$q = "SELECT ina_last_visit_page 
		FROM " . USERS_TABLE . "
		WHERE user_id = '" . $userdata['user_id'] . "'";
	if (!$r = $db->sql_query($q))
	{
		message_die(GENERAL_ERROR, 'Error Selecting user last visit page.', '', __LINE__, __FILE__, $q);
	}
	$row = $db->sql_fetchrow($r);

	$last_page_viewed = $row['ina_last_visit_page'];

	if($last_page_viewed)
	{
		$return_page = 'activity.'. $phpEx .'?'. $last_page_viewed;
	}
	
	if(!$last_page_viewed) 
	{
		$return_page = 'activity.'. $phpEx .'?sid='. $userdata['session_id'];
	}
	// End Get Previous Page
			
	$play_again		= str_replace("%G%", "<a href='activity.". $phpEx ."?mode=game&id=". $game_info['game_id'] ."&parent=true&sid=". $userdata['session_id'] ."' class='mainmenu' />". $game_info['proper_name'] ."</a>", $lang['play_again_link']);
	$msg_prt1 		= str_replace("%G%", $game_info['proper_name'], $lang['score_on_newscore']);
	$msg_prt2 		= str_replace("%S%", $score, $msg_prt1);
	$msg 			.= "<br>$msg_prt2";
			
	// Start Favorites
	$q = "SELECT games 
		FROM " . INA_FAVORITES . "
		WHERE user = '" . $userdata['user_id'] . "'";
	if (!$r = $db->sql_query($q))
	{
		message_die(GENERAL_ERROR, 'Error Selecting User Favorites.', '', __LINE__, __FILE__, $q);
	}
	$row = $db->sql_fetchrow($r);
	
	$fav_games = $row['games'];
	
	if (eregi(quotemeta("S". $game_info['game_id'] ."E"), $fav_games))
	{
		$add_to_favs = $lang['saved_body_fav_no'];
	}
	else
	{
		$add_to_favs = "<a href='activity_favs.". $phpEx ."?mode=add_fav&game=". $game_info['game_id'] ."&sid=". $userdata['session_id'] ."' class='mainmenu' target='_blank'>". $lang['saved_body_favs'] ."</a>";
	}
	// End Favorites
			
	$game_img = CheckGameImages($game_info['game_name'], $game_info['proper_name']);
						
	// Setup Links Based On Gameplay
	if ($userdata['ina_last_playtype'] == 'parent')
	{
		$link1 			= $return_page;
		$lang1 			= $lang['go_back_to_games'];
		$add_to_favs 	= "<a href='activity_favs.". $phpEx ."?mode=add_fav&game=". $game_info['game_id'] ."&sid=". $userdata['session_id'] ."' class='mainmenu' target='_parent'>". $lang['saved_body_favs'] ."</a>";	
	}
	elseif ($userdata['ina_last_playtype'] == 'popup')
	{
		$link1 			= "javascript:parent.window.close();";
		$lang1 			= $lang['game_score_close'];
		$play_again 	= '';		
		$add_to_favs 	= "<a href='activity_favs.". $phpEx ."?mode=add_fav&game=". $game_info['game_id'] ."&sid=". $userdata['session_id'] ."' class='mainmenu' target='_blank'>". $lang['saved_body_favs'] ."</a>";	
	}
	else
	{
		$link1 = $return_page;
		$lang1 = $lang['go_back_to_games'];		
	}
		
	$rate = '<a href="'. append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=rate&game='. $game_info['game_id'] .'\', \'New_Window\', \'450\', \'300\', \'yes\')') .'" class="mainmenu">'. $lang['saved_body_rate'] .'</a>';
	
	if ( ($board_config['ina_disable_comments_page']) && ($userdata['user_level'] != ADMIN) ) 
	{
		$comms = '';						
	}
	else
	{
		$comms = '<a href="'.  append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=comments&game='. $game_info['game_name'] .'\', \'New_Window\', \'550\', \'300\', \'yes\')').'" class="mainmenu">'. $lang['saved_body_comms'] .'</a>';
	}
			
	$template->assign_vars(array(
		'GAME_NAME' 	=> $game_info['proper_name'],
		'GAME_IMAGE'	=> $game_img,
		'GAME_COMMS'	=> $comms,
		'GAME_RATE'		=> $rate,
		'GAME_FAV'		=> $add_to_favs,
		'TITLE'			=> $lang['saved_body_title'],
		'MSG'			=> $msg,
		'T_WON'			=> $trophy_won . '<br /><br />',
		'U_RETURN' 		=> $link1,
		'U_AGAIN'		=> $play_again,
		'L_RETURN'		=> $lang1)
	);
}
else
{	
	// Start Comments
	$template->assign_block_vars('comment', array(
		'COMMENT_LINK'	=> '<a href="'. append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=comments&action=leave_comment&user='. $userdata['user_id'] .'&game='. $game_name .'\', \'New_Window\', \'400\', \'300\', \'yes\')') .'" class="mainmenu">'. $lang['trophy_comment_notice'] .'</a>')
	);
	// End Comments
				
	// Start GE Add
	if ($trophy_won == $lang['trophy_won_notice'])
	{
		$trophy_GE = 1;
	}
		
	if ($msg == $lang['game_score_saved'])
	{
		$beat_score_GE = 1;
	}
		
	if ($userdata['ina_char_name'])
	{
		AMP_Add_GE($userdata['user_id'], $userdata['ina_char_ge'], $trophy_GE, $beat_score_GE);
	}
	// End GE Add

	// Start Get Previous Page
	$q = "SELECT ina_last_visit_page 
		FROM " . USERS_TABLE . "
		WHERE user_id = '" . $userdata['user_id'] . "'";
	if (!$r = $db->sql_query($q))
	{
		message_die(GENERAL_ERROR, 'Error Selecting User last visit page.', '', __LINE__, __FILE__, $q);
	}
	$row = $db->sql_fetchrow($r);
	
	$last_page_viewed = $row['ina_last_visit_page'];
	
	if ($last_page_viewed) 
	{
		$return_page = $last_page_viewed;
	}
	
	if (!$last_page_viewed) 
	{
		$return_page = "activity.php?sid=". $userdata['session_id'] ."";			
	}
	// End Get Previous Page
			
	$play_again		= str_replace("%G%", "<a href='activity.". $phpEx ."?mode=game&id=". $game_info['game_id'] ."&parent=true&sid=". $userdata['session_id'] ."' class='mainmenu'>". $game_info['proper_name'] ."</a>", $lang['play_again_link']);
	$msg_prt1 		= str_replace("%G%", $game_info['proper_name'], $lang['score_on_newscore']);
	$msg_prt2 		= str_replace("%S%", $score, $msg_prt1);
	$msg 			.= "<br>$msg_prt2";
			
	// Start Favorites
	$q = "SELECT games 
		FROM " . INA_FAVORITES . "
		WHERE user = '" . $userdata['user_id'] . "'";
	if (!$r = $db->sql_query($q))
	{
		message_die(GENERAL_ERROR, 'Error Selecting User Favorites.', '', __LINE__, __FILE__, $q);
	}
	$row = $db->sql_fetchrow($r);

	$fav_games = $row['games'];
	
	if (eregi(quotemeta("S". $game_info['game_id'] ."E"), $fav_games))
	{
		$add_to_favs = $lang['saved_body_fav_no'];
	}
	else
	{
		$add_to_favs = "<a href='activity_favs.". $phpEx ."?mode=add_fav&game=". $game_info['game_id'] ."&sid=". $userdata['session_id'] ."' class='mainmenu' target='_blank'>". $lang['saved_body_favs'] ."</a>";
	}
	// End Favorites
			
	$game_img 	= CheckGameImages($game_info['game_name'], $game_info['proper_name']);
						
	// Setup Links Based On Gameplay
	if ($userdata['ina_last_playtype'] == 'parent')
	{
		$link1 			= $return_page;
		$lang1 			= $lang['go_back_to_games'];
		$add_to_favs 	= "<a href='activity_favs.". $phpEx ."?mode=add_fav&game=". $game_info['game_id'] ."&sid=". $userdata['session_id'] ."' class='mainmenu' target='_parent'>". $lang['saved_body_favs'] ."</a>";	
	}
	elseif ($userdata['ina_last_playtype'] == 'popup')
	{
		$link1 			= "javascript:parent.window.close();";
		$lang1 			= $lang['game_score_close'];
		$play_again 	= '';		
		$add_to_favs 	= "<a href='activity_favs.". $phpEx ."?mode=add_fav&game=". $game_info['game_id'] ."&sid=". $userdata['session_id'] ."' class='mainmenu' target='_blank'>". $lang['saved_body_favs'] ."</a>";	
	}
	else
	{
		$link1 = $return_page;
		$lang1 = $lang['go_back_to_games'];		
	}
		
	$rate = '<a href="'. append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=rate&game='. $game_info['game_id'] .'\', \'New_Window\', \'450\', \'300\', \'yes\')') .'" class="mainmenu">'. $lang['saved_body_rate'] .'</a>';
	
	if ( ($board_config['ina_disable_comments_page']) && ($userdata['user_level'] != ADMIN) ) 
	{
		$comms = '';						
	}
	else
	{
		$comms = '<a href="'.  append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=comments&game='. $game_info['game_name'] .'\', \'New_Window\', \'550\', \'300\', \'yes\')').'" class="mainmenu">'. $lang['saved_body_comms'] .'</a>';
	}
	
	$template->assign_vars(array(
		'GAME_NAME' 	=> $game_info['proper_name'],
		'GAME_IMAGE'	=> $game_img,
		'GAME_COMMS'	=> $comms,
		'GAME_RATE'		=> $rate,
		'GAME_FAV'		=> $add_to_favs,
		'TITLE'			=> $lang['saved_body_title'],
		'MSG'			=> ($game_info['game_type'] == 2) ? $lang['game_type_message'] : $lang['no_score_saved'],
		'T_WON'			=> $trophy_won . '<br /><br />',
		'U_RETURN' 		=> $link1,
		'U_AGAIN'		=> $play_again,				
		'L_RETURN'		=> $lang1)
	);
} 
		
UpdateTrophyStats();
CheckGamesDeletion();	
TrophyKingRankCheck();
UpdateGamePlayTime(time(), $userdata['ina_time_playing']);

//
// Generate Page
//
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
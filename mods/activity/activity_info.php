<?php
/** 
*
* @package amod_files
* @version $Id: activity_info.php,v 1.1.0 2005 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}
		
$template->set_filenames(array(
	'activity_info_section' => 'amod_files/activity_info_body.tpl')
);
					
$where_disabled = ($userdata['user_level'] == ADMIN) ? '' : "WHERE disabled > '0'" ;
			
/* Get last trophy game played */		
$q =  "SELECT a.*, b.username, b.user_level
	FROM ". INA_TROPHY ." a, ". USERS_TABLE ." b
	WHERE a.player = b.user_id
	ORDER BY a.date DESC
	LIMIT 1"; 
$r  	= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);		

$game 	= $row['game_name'];
$name	= $row['player'];	
$when	= $row['date'];
$score	= $row['score'];		
$trophy = $row['username'];
		
$trophy_link = ($name == ANONYMOUS) ? 'Anonymous' : '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $name) . '">' . username_level_color($trophy, $row['user_level'], $name) .'</a>';	
		
for ($z = 0; $z < $games_c; $z++)
{
	if ($games_data[$z]['game_name'] == $game)
	{
		$t_game_id 		= $games_data[$z]['game_id'];
		$t_game_name	= $games_data[$z]['proper_name'];
		$t_game_parent	= $games_data[$z]['game_parent'];
		$t_game_popup	= $games_data[$z]['game_popup'];
		$t_game_win		= $games_data[$z]['win_width'];
		$t_game_height	= $games_data[$z]['win_height'];
		break;
	}
}
			
/* Get total games played, most game played, least game played */				
$q =  "SELECT *
	FROM ". iNA_GAMES ."
	$where_disabled
	ORDER BY played DESC
	LIMIT 1"; 
$r  	= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);

$f_game_id 		= $row['game_id'];
$f_game_name	= $row['proper_name'];
$f_game_played	= $row['played'];								
$f_game_parent	= $row['game_parent'];
$f_game_popup	= $row['game_popup'];
$f_game_win		= $row['win_width'];
$f_game_height	= $row['win_height'];

$q =  "SELECT *
	FROM ". iNA_GAMES ."
	$where_disabled
	ORDER BY played ASC
	LIMIT 1"; 
$r  	= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);

$lf_game_id 	= $row['game_id'];
$lf_game_name	= $row['proper_name'];		
$lf_game_played	= $row['played'];								
$lf_game_parent	= $row['game_parent'];
$lf_game_popup	= $row['game_popup'];
$lf_game_win	= $row['win_width'];
$lf_game_height	= $row['win_height'];

$total_played = 0;
for ($z = 0; $z < $games_c; $z++)
{
	$total_played = $total_played + $games_data[$z]['played'];
	if (!$games_data[$z]['game_id'])
	{
		break;
	}
}
$total = $total_played;

$total_left = 0;
for ($z = 0; $z < count($comment_data); $z++)
{
	$total_left = $total_left + $comment_data[$z]['total_comments'];
	if (!$comment_data[$z]['game'])
	{
		break;
	}
}
$total_comments 		= $total_left;		
$total_games_available 	= $games_c;
		
$q =  "SELECT COUNT(game_id) AS total_bets_made 
	FROM ". $table_prefix ."ina_gamble"; 
$r  	= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);

$total_bets_made = $row['total_bets_made'];
		
$q =  "SELECT SUM(count) AS total_challenges_sent
	FROM ". $table_prefix ."ina_challenge_users"; 
$r  	= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);

$total_challenges_sent = $row['total_challenges_sent'];
		
$totals_lines = $lang['info_box_total_gaems'] . ' <b>' . number_format($total_games_available) . '</b><br />' . $lang['info_box_total_game_played'] . ' <b>' . number_format($total) . '</b><br /><br />' . $lang['info_box_total_challenges'] . ' <b>' . number_format($total_challenges_sent) . '</b><br />' .$lang['info_box_total_comments'] . ' <b>' . number_format($total_comments) . '</b><br />' . $lang['info_box_total_bets'] . ' <b>' . number_format($total_bets_made) . '</b><br />';		

$trophy_game_3 = GameSingleLink($t_game_id, $t_game_parent, $t_game_popup, $t_game_win, $t_game_height, 'activity', '%l%', $lang['info_box_link_here'], $lang['info_box_trophy_3'], '');
		
		
//	
// Best Player -- user with the most trophies
//
$sql =  "SELECT * 
	FROM " . USERS_TABLE . "  
    ORDER BY user_trophies DESC 
    LIMIT 0, 1"; 
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain best player.', '', __LINE__, __FILE__, $sql);
}
$bestplayer_row = $db->sql_fetchrow($result); 

$top_player1 = str_replace('%n%', $bestplayer_row['username'], $lang['info_box_top_trophy_holder1']);		
if ( $bestplayer_row['user_id'] != ANONYMOUS )
{
	$top_player1 = str_replace('%n%', '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $bestplayer_row['user_id']) . '">' . username_level_color($bestplayer_row['username'], $bestplayer_row['user_level'], $bestplayer_row['user_id']) . '</a>', $lang['info_box_top_trophy_holder1']);
}

$top_player2 = str_replace('%t%', '<b><a href="' . append_sid('activity.'.$phpEx.'?page=trophy_search&amp;user=' . $bestplayer_row['username']) . '">' . $bestplayer_row['user_trophies'] . '</a></b>', $top_player1);		
$top_player	= $top_player2;

$db->sql_freeresult($result);


//
// Get all data for the viewing user/player
//
// Last Game Played
$sql =  "SELECT *
	FROM " . INA_LAST_GAME . "
	WHERE user_id = " . $userdata['user_id']; 
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain player data.', '', __LINE__, __FILE__, $sql);
}
$lastplayed_row = $db->sql_fetchrow($result); 

if ( $result )
{		
	for ($z=0; $z < $games_c; $z++)
	{
		if ($games_data[$z]['game_id'] == $lastplayed_row['game_id'])
		{
			$l_game_name = $games_data[$z]['proper_name'];
			$l_game_parent = $games_data[$z]['game_parent'];
			$l_game_popup = $games_data[$z]['game_popup'];
			$l_game_win	= $games_data[$z]['win_width'];
			$l_game_height = $games_data[$z]['win_height'];	
			$last = GameSingleLink($lastplayed_row['game_id'], $l_game_parent, $l_game_popup, $l_game_win, $l_game_height, 'activity', '%g%', $l_game_name, $lang['personal_info_last_game'], '');
			$last2 = str_replace('%d%', create_date($board_config['default_dateformat'], $lastplayed_row['date'], $board_config['board_timezone']), $last);
				
			$template->assign_block_vars('personal_info_box', array(
				'LAST_GAME_PLAYED' => '<b>' . $lang['seperator_2'] . '</b> ' . $last2) 
			);
		}
	}			
}
$db->sql_freeresult($result);
		

// Scores
$q =  "SELECT COUNT(*) AS total
	FROM ". iNA_SCORES ."
	WHERE player = '" . $userdata['username'] . "'"; 
$r  	= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);		

$total_scores = $row['total'];
if (!$total_scores) 
{
	$total_scores = 0;
}

// 
// Challenges			
$q =  "SELECT SUM(count) AS total
	FROM ". INA_CHALLENGE_USERS ."
	WHERE user_from = " . $userdata['user_id']; 
$r  	= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);		

$total_challenges_sent = $row['total'];		
if (!$total_challenges_sent) 
{
	$total_challenges_sent = 0;
}
								
$q =  "SELECT SUM(count) AS total
	FROM ". INA_CHALLENGE_USERS ."
	WHERE user_to = " . $userdata['user_id']; 
$r  	= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);		

$total_challenges_recieved = $row['total'];
if (!$total_challenges_recieved) 
{
	$total_challenges_recieved = 0;
}
			
// Comments
$q =  "SELECT COUNT(player) AS total
	FROM ". INA_TROPHY_COMMENTS ."
	WHERE player = " . $userdata['user_id']; 
$r  	= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);		

$total_comments_left = $row['total'];
if (!$total_comments_left) 
{
	$total_comments_left = 0;
}
						
$users_link = 'Anonymous';		
if ($userdata['user_id'] != ANONYMOUS) 
{
	$users_link = '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $userdata['user_id']) . '">' . username_level_color($userdata['username'], $userdata['user_level'], $userdata['user_id']) . '</a>';
}
		
$sent_link = '<b>' . $lang['seperator_2'] . '</b> ' . str_replace('%t%', $total_challenges_sent, $lang['personal_info_challenges_1']);
if ($userdata['user_id'] != ANONYMOUS) 
{
	$sent_link = '<b>' . $lang['seperator_2'] . '</b> ' . str_replace('%t%', '<a href="' . append_sid('activity.'.$phpEx.'?page=challenges&amp;mode=check_user&amp;' . POST_USERS_URL . '=' . $userdata['user_id']) . '">' . $total_challenges_sent . '</a>', $lang['personal_info_challenges_1']);
}
		

//
// Select info for last game played 			
//
$q =  "SELECT a.game_id, a.user_id, a.date, b.username, b.user_level, c.proper_name, c.game_popup, c.game_parent, c.win_width, c.win_height
	FROM ". INA_LAST_GAME ." a, ". USERS_TABLE ." b, ". iNA_GAMES ." c
	WHERE a.user_id = b.user_id
		AND c.game_id = a.game_id
	ORDER BY a.date DESC
	LIMIT 1"; 
$r = $db->sql_query($q);
$row = $db->sql_fetchrow($r);		

$newest_game = $row['game_id'];
$newest_user = $row['user_id'];
$newest_date = $row['date'];						
$newest_name = $row['username'];		
$newest_level = $row['user_level'];		
$newest_game_name = $row['proper_name'];
$newest_game_parent	= $row['game_parent'];
$newest_game_popup = $row['game_popup'];
$newest_game_win = $row['win_width'];
$newest_game_height	= $row['win_height'];
$newlink = GameSingleLink($newest_game, $newest_game_parent, $newest_game_popup, $newest_game_win, $newest_game_height, 'activity', '%g%', $newest_game_name, $lang['personal_info_newest_game1'], '');

$newlink2 = str_replace('%u%', $newest_name, $newlink);			
if ($newest_user != ANONYMOUS)
{
	$newlink2 = str_replace('%u%', '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $newest_user) . '">' . username_level_color($newest_name, $newest_level, $newest_user) . '</a>', $newlink);
}
			
$newest_game_played_link = $newlink2;		
$newest_game_played_title = $lang['personal_info_newest_game'];		
$lang_to_use = ( $userdata['user_trophies'] == 1 ) ? $lang['personal_info_trophies_1'] : $lang['personal_info_trophies'];
$game_lang	= ( $total_scores == 1 ) ? $lang['personal_info_game_played'] : $lang['personal_info_games_played'];
			
$users_trophies = '<b>' . $lang['seperator_2'] . '</b> '. str_replace('%t%', $userdata['user_trophies'], $lang_to_use);		
if ($userdata['user_trophies'] > 0) 
{
	$users_trophies = '<b>' . $lang['seperator_2'] . '</b> ' . str_replace('%t%', '<b><a href="' . append_sid('activity.'.$phpEx.'?page=trophy_search&amp;user=' . $userdata['username']) . '">' . $userdata['user_trophies'] . '</a></b>', $lang_to_use);
}

$shoutbox_link = ( $board_config['ina_use_shoutbox'] ) ? '<br /><b>'. $lang['seperator_2'] .'</b> <a href="'. append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=chat&action=view\', \'New_Window\', \'550\', \'300\', \'yes\')') .'">'. $lang['shoutbox_link'] .'</a>' : '';
			
$template->assign_block_vars('info_box', array(
	'MOST_POPULAR_1'			=> $lang['info_box_popular_1'] . '<b>' . $f_game_name . '</b>',
	'MOST_POPULAR_2'			=> '<b>' . $lang['seperator_2'] . '</b> ' . str_replace('%g%', $f_game_played, $lang['info_box_popular_2']),
	'MOST_POPULAR_3'			=> '<b>' . $lang['seperator_2'] . '</b> ' . GameSingleLink($f_game_id, $f_game_parent, $f_game_popup, $f_game_win, $f_game_height, 'activity', '%l%', $lang['info_box_link_here'], $lang['info_box_popular_3'], ''),
	'LEAST_POPULAR_1'			=> $lang['info_box_least_popular_1'] . '<b>' . $lf_game_name . '</b>',
	'LEAST_POPULAR_2'			=> '<b>' . $lang['seperator_2'] . '</b> ' . str_replace('%g%', $lf_game_played, $lang['info_box_popular_2']),
	'LEAST_POPULAR_3'			=> '<b>' . $lang['seperator_2'] . '</b> ' . GameSingleLink($lf_game_id, $lf_game_parent, $lf_game_popup, $lf_game_win, $lf_game_height, 'activity', '%l%', $lang['info_box_link_here'], $lang['info_box_popular_3'], ''),
	'TOTAL_GAMES_PLAYED' 		=> $totals_lines,
	'TROPHY_GAME'				=> $trophy_link,
	'TROPHY_GAME_1'				=> $lang['info_box_trophy_1'],
	'TROPHY_GAME_2'				=> str_replace('%g%', $t_game_name, $lang['info_box_trophy_2']),			
	'TROPHY_GAME_3'				=> $trophy_game_3,
	'TROPHY_TOP_HOLDER1'		=> $top_player,
	'TROPHY_TOP_HOLDER'			=> $lang['info_box_top_trophy_holder'],			
	'USERNAME'					=> $users_link,
	'TOTAL_GAMES_LINK'			=> '<b>' . $lang['seperator_2'] . '</b> '. str_replace('%t%', "<a href='". append_sid("activity.$phpEx?page=high_scores&mode=highscore&player_search=". $userdata['username']) ."' class='nav'>". $total_scores ."</a>", $game_lang),
	'FAVORITES_LINK'			=> '<b>' . $lang['seperator_2'] . '</b> <a href="' . append_sid('activity_favs.'.$phpEx) . '">'. $lang['favorites_info_link'] . '</a>' . $shoutbox_link,
	'TOTAL_CHALLENGES_SENT'		=> $sent_link,
	'TOTAL_CHALLENGES_RECIEVED'	=> '<b>' . $lang['seperator_2'] .'</b> '. str_replace('%t%', $total_challenges_recieved, $lang['personal_info_challenges_2']),			
	'TOTAL_COMMENTS_LEFT'		=> '<b>' . $lang['seperator_2'] .'</b> '. str_replace('%t%', $total_comments_left, $lang['personal_info_comments']),			
	'TOTAL_TROPHIES_HELD'		=> $users_trophies .'<br /><b>'. $lang['seperator_2'] .'</b> '. str_replace('%T%', number_format($userdata['ina_char_ge']), $lang['info_box_user_ge_points']),			
	'LAST_GAME_PLAYED'			=> $newest_game_played_link,
	'TOTAL_ONHAND_POINTS'		=> $onhand,
	'TOTAL_TIME_IN_GAMES'		=> '<b>' . $lang['seperator_2'] . '</b> '. DisplayPlayingTime($userdata['ina_time_playing']),
	
	'L_NEWEST_TITLE'			=> $newest_game_played_title,														
	'L_INFO_TITLE'				=> $lang['info_box_title'],
	'L_INFO_TITLE1'				=> $lang['info_box_title1'],
	'L_INFO_TITLE2'				=> $lang['info_box_title2'],
	'L_INFO_TITLE3'				=> $lang['info_box_title3']) 
);
	
$template->assign_var_from_handle('ACTIVITY_INFO_SECTION', 'activity_info_section');

?>
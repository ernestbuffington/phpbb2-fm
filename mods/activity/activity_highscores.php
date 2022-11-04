<?php 
/***************************************************************************
 *                             activity_highscores.php
 *                            -------------------------
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

$game_name 	= (!empty($HTTP_POST_VARS['game_name'])) ? $HTTP_POST_VARS['game_name'] : $HTTP_GET_VARS['game_name']; 
$p_search 	= (!empty($HTTP_POST_VARS['player_search'])) ? $HTTP_POST_VARS['player_search'] : $HTTP_GET_VARS['player_search']; 	  
		
$p_search	= str_replace("%APOS%", "'", $p_search);
$p_search	= stripslashes($p_search);
$p_search 	= addslashes($p_search);
		 
if($p_search)
{ 
	$template->set_filenames(array(
		'body' => 'amod_files/activity_scores.tpl') 
	);
	make_jumpbox('viewforum.'.$phpEx);
			
	$template->assign_vars(array(
		'TITLE' => '', 
		'L_HIGHSCORE' => $lang['highscore_games'], 
		'L_SCORE' => stripslashes($p_search) ."'s ". $lang['game_score'] ."s", 
		'L_PLAYED' => $lang['game_played'], 
		'WIDTH1' => '',
		'WIDTH2' => '',
		'WIDTH3' => '',			 
		'DASH' => '')
	); 
			 
			 										 	  
    $sql = "SELECT * 
		FROM ". iNA_SCORES ." 
        WHERE player = '". $p_search ."' 
		GROUP BY game_name
        ORDER BY game_name ASC"; 
	if(!$result = $db->sql_query($sql)) 
    { 
    	message_die(GENERAL_ERROR, $lang['no_score_data'], "", __LINE__, __FILE__, $sql); 
    } 
	  				
	$i = 0; 				
	while($row = $db->sql_fetchrow($result)) 
	{ 				
		$q = "SELECT proper_name
			FROM ". iNA_GAMES ." 
			WHERE game_name = '". $row['game_name'] ."'"; 			  
		if (!$r = $db->sql_query($q)) 
		{
			message_die(GENERAL_ERROR, $lang['no_game_data'], '', __LINE__, __FILE__, $q); 
		}
		$row1 = $db->sql_fetchrow($r); 
			  
		$game_link = CheckGameImages($row['game_name'], $row1['proper_name']);	  		
	
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
		$template->assign_block_vars('scores', array(
			'ROW_CLASS' => $row_class, 
			'POS' => $i + 1, 
			'NAME' => $row1['proper_name'] . '<br />' . $game_link,
			'SCORE' => FormatScores($row['score']), 
		 	'ALIGN'	=> 'center',					                                    
			'DATE' => create_date($board_config['default_dateformat'], $row['date'], $board_config['board_timezone'])) 
		); 
		$i++; 
	} 
	$db->sql_freeresult($result);
}	 
else
{
	$sql = "SELECT * 
		FROM " . iNA_GAMES . " 
        WHERE game_name = '" . $game_name . "'"; 			  
	if (!$result = $db->sql_query($sql))  
    {
    	message_die(GENERAL_ERROR, $lang['no_game_data'], "", __LINE__, __FILE__, $sql); 
	}
	$game_info = $db->sql_fetchrow($result); 

    $highscore_limit = $game_info['highscore_limit']; 

    $template->set_filenames(array(
    	'body' => 'amod_files/activity_scores.tpl') 
    ); 
      
    $template->assign_vars(array(
		"TITLE" 		=> $game_info['proper_name'], 
        "L_HIGHSCORE" 	=> $lang['game_highscores'], 
        "L_SCORE" 		=> $lang['game_score'], 
        'L_PLAYED' => $lang['game_played'],
		"WIDTH1"		=> "",
		"WIDTH2"		=> "200",
		"WIDTH3"		=> "200",		 		 
        "DASH" 		=> $lang['game_dash'])
    ); 

    if ($game_info['reverse_list']) 
    {
    	$list_type = 'ASC'; 
    }
    else 
    {
    	$list_type = 'DESC'; 
	}
	
    if (!empty($highscore_limit)) 
    { 
    	$sql = "SELECT *, MAX(score) AS hscore 
			FROM ". iNA_SCORES ." 
            WHERE game_name = '". $game_name ."' 
			GROUP BY player
            ORDER BY score $list_type 
			LIMIT 0, $highscore_limit"; 
	} 
    else 
    { 
    	$sql = "SELECT *, MAX(score) AS hscore 
			FROM ". iNA_SCORES ." 
            WHERE game_name = '". $game_name ."' 
			GROUP BY player
            ORDER BY score $list_type"; 
	} 
	if(!$result = $db->sql_query($sql)) 
	{
		message_die(GENERAL_ERROR, $lang['no_score_data'], "", __LINE__, __FILE__, $sql); 
	}
	
    if ($row = $db->sql_fetchrow($result)) 
    { 
    	$i = 0; 
        do 
        { 
            $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2']; 

            $template->assign_block_vars("scores", array(
               'ROW_CLASS' => $row_class, 
               'POS' => $i + 1, 
               'NAME' => "<a href='activity.". $phpEx ."?page=high_scores&mode=highscore&player_search=". str_replace("'", "%APOS%", $row['player']) ."&sid=". $userdata['session_id'] ."'>". $row['player'] ."</a>",
               'SCORE' => FormatScores($row['hscore']), 
			   'ALIGN' => 'center',			                                    
               'DATE' => create_date($board_config['default_dateformat'], $row['date'], $board_config['board_timezone'])) 
			); 
			
			if ($row['user_plays'] > 0)
            {
            	$template->assign_block_vars('scores.scores_stats', array(
            		'STATS' => Amod_Individual_Game_Time($row['user_plays'], $row['play_time']))
                );
			} 
            $i++; 
		} 
        while ($row = $db->sql_fetchrow($result)); 
	} 
}

$template->pparse('body');

?>
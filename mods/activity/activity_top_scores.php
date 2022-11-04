<?php
/***************************************************************************
 *                            activity_top_scores.php
 *                            -----------------------
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

// Specific Page Disabled 
if ( ($board_config['ina_disable_trophy_page']) && ($userdata['user_level'] != ADMIN) ) 
{
	message_die(GENERAL_MESSAGE, $lang['disabled_page_error']);
}
//
// End Restriction Checks 
//	
		
$delete_action 	= (isset($HTTP_POST_VARS['action'])) ? $HTTP_POST_VARS['action'] : '';
$start 			= (isset($HTTP_GET_VARS['start'])) ? intval($HTTP_GET_VARS['start']) : 0;
$end			= $board_config['games_per_page'];
	
if ( ($delete_action == "delete_specific_score") && ($userdata['user_level'] == ADMIN) )
{
	$delete_certain_score = (isset($HTTP_POST_VARS['delete_score'])) ? $HTTP_POST_VARS['delete_score'] : '';

	$q = "SELECT *
		FROM ". iNA_GAMES ."
		WHERE game_name = '". $delete_certain_score ."'";
	$r 		= $db->sql_query($q);
	$order 	= $db->sql_fetchrow($r);
	
	if ($order['reverse_list'])
	{
		$asc_desc = 'ASC';
	}
	else
	{
		$asc_desc = 'DESC';		
	}
	
	$q1 = "SELECT *
	    FROM ". iNA_SCORES ."
		WHERE game_name = '". $delete_certain_score ."'
		GROUP BY player
		ORDER BY score $asc_desc
		LIMIT 0, 1";
	$r1		= $db->sql_query($q1);
	$row 	= $db->sql_fetchrow($r1);

	$score1 	= $row['score'];
	$player1	= $row['player'];
			
	$q1 = "SELECT *
	    FROM ". iNA_SCORES ."
		WHERE game_name = '". $delete_certain_score ."'
		GROUP BY player
		ORDER BY score $asc_desc
		LIMIT 1, 1";
	$r1		= $db->sql_query($q1);
	$row 	= $db->sql_fetchrow($r1);

	$score2 	= $row['score'];
	$player2	= $row['player'];	
	$player2	= stripslashes($player2);
	$player2	= addslashes($player2);
	
	$q1 = "SELECT user_id, user_level
	    FROM ". USERS_TABLE ."
		WHERE username = '". $player2 ."'";
	$r1		= $db->sql_query($q1);
	$row 	= $db->sql_fetchrow($r1);

	$player2_n	= $row['user_id'];
	
	if(!$player2_n) 
	{
		$player2_n = $userdata['user_id'];
	}
				
	$q1 = "UPDATE ". $table_prefix ."ina_top_scores
		SET player = '". $player2_n ."', score = '". $score2 ."', date = '". time() ."'
		WHERE game_name = '". $delete_certain_score ."'";
	$db->sql_query($q1);
				
	$q1 = "DELETE FROM ". iNA_SCORES ."
		WHERE game_name = '". $delete_certain_score ."'
			AND score = '". $score1 ."'
			AND player = '". $player1 ."'";
	$db->sql_query($q1);
					
	message_die(GENERAL_MESSAGE, $lang['the_trophy_holder'] . $player1 . $lang['score_of'] . $score1 . $lang['been_deleted_n_replaced'] . $player2 . $lang['score_of'] . $score2 . $lang['please_click'] .'activity.'. $phpEx .'?page=trophy'. $lang['here_to_return'], $lang['success']);
}
		
if ( ($delete_action == "delete_all_scores") && ($userdata['user_level'] == ADMIN) )
{
   	$q = "SELECT *
   		FROM " . iNA_GAMES;
   	$r = $db->sql_query($q);
   	$games = $db->sql_fetchrowset($r);
   
   	for ($x = 0; $x < count($games); $x++)
   	{
		if ($games[$x]['reverse_list'])
    	{
    		$score = 1000;
    	}
    	else
    	{
    		$score = 1;   
    	}
      
    	$game_name = $games[$x]['game_name'];
      
      	$q = "UPDATE " . $table_prefix . "ina_top_scores
        	SET player = " . $userdata['user_id'] . ", score = $score, date = " . time() . "
           	WHERE game_name = '$game_name'";
      	$db->sql_query($q);
    } 	
	
	message_die(GENERAL_MESSAGE, $lang['scores_reset'] . $userdata['username'] .$lang['zero_score']. $lang['please_click']. $_SERVER['PHP_SELF'] .$lang['here_to_return'], $lang['success']);		
}

$search = (isset($HTTP_GET_VARS['user'])) ? $HTTP_GET_VARS['user'] : '';

if (!$search)
{
	$template->set_filenames(array(
		'body' => 'amod_files/activity_top_scores_body.tpl') 
	);					
	make_jumpbox('viewforum.'.$phpEx);
	
	#=================================== Game Image SQL Array =========				
	$q = "SELECT *
		FROM ". iNA_GAMES;
	$r		= $db->sql_query($q);
	$row1 	= $db->sql_fetchrowset($r);
	$row2	= $db->sql_numrows($r);

	#=================================== Trophies Count SQL ===========
	$q = "SELECT *
		FROM ". $table_prefix ."ina_top_scores";
	$r				= $db->sql_query($q);
	$trophy_count	= $db->sql_numrows($r);
			
	#=================================== Trophies SQL Array ===========
	$q = "SELECT *
		FROM ". $table_prefix ."ina_top_scores
		ORDER BY game_name ASC
		LIMIT $start, $end";
	$r				= $db->sql_query($q);
	$trophy_data 	= $db->sql_fetchrowset($r);
	$array_count	= $db->sql_numrows($r);
	
	#=================================== User Info SQL Array ==========
	$q = "SELECT *
		FROM ". USERS_TABLE;
	$r			= $db->sql_query($q);
	$user_data 	= $db->sql_fetchrowset($r);
	$user_count	= $db->sql_numrows($r);		
	
	if($userdata['user_level'] == ADMIN)
	{			
		$template->assign_block_vars('admin', array(
			'L_DELETE_SPECIFIC' 	=> $lang['delete_specific'],		
			'L_DEFAULT_ONE'			=> $lang['admin_delete_default'] ,
			'L_DELETE_ALL'			=> $lang['delete_all'],
			'L_DELETE'		=> $lang['Delete'])
		);
					
		#=================================== Use Trophies Array ===========			
		for ($a = 0; $a < $array_count; $a++)
		{
			$template->assign_block_vars('admin_drop_one', array(															
				'GAME_NAME'	=> $trophy_data[$a]['game_name'])
			);
		}
	}
			
	$template->assign_block_vars('top_scores', array(															
		'HEADER_ONE'	=> $lang['game'],
		'HEADER_TWO'	=> $lang['trophy_holder'],
		'HEADER_THREE'	=> $lang['score'],
		'HEADER_FOUR'	=> $lang['Date'],
		'HEADER_FIVE'	=> $lang['contacts'])
	);
					
	#=================================== Use Trophies Array ===========			
	for ($a = 0; $a < $array_count; $a++)
	{
		#=================================== Use User Info Array ==========			
		for ($b = 0; $b < $user_count; $b++)
		{
			if ($trophy_data[$a]['player'] == $user_data[$b]['user_id'])
			{
				$score		= $trophy_data[$a]['score'];
				$game_name	= $trophy_data[$a]['game_name'];
				$who		= $trophy_data[$a]['player'];
				$date		= $trophy_data[$a]['date'];
				$score 		= FormatScores($score);
				$date 		= create_date($board_config['default_dateformat'], $date, $board_config['board_timezone']);
				$user_n		= $user_data[$b]['username'];
				$fix_user_n	= stripslashes($user_n);
				$fix_user_n	= str_replace("'", "%APOS%", $fix_user_n);
				
				$pm = ($user_data[$b]['user_id'] == ANONYMOUS) ? '' : '<a href="'. append_sid("privmsg.$phpEx?mode=post&amp;". POST_USERS_URL ."=". $user_data[$b]['user_id']) .'"><img src="'. $images['icon_pm'] .'" alt="'. $lang['Send_private_message'] .'" title="'. $lang['Send_private_message'] .'" /></a>';
				$profile = ($user_data[$b]['user_id'] == ANONYMOUS) ? '' : '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL ."=". $user_data[$b]['user_id']) .'"><img src="'. $images['icon_profile'] .'" alt="'. $lang['Profile'] .'" title="'. $lang['Profile'] .'" /></a>';			
			
				#=================================== Use Game Image Array =========			
				for ($t = 0; $t < $row2; $t++)
				{		
					if ($row1[$t]['game_name'] == $game_name)
					{
						$game_image = '';
						$game_image	= '<center><b>'. $row1[$t]['proper_name'] .'</b></center><br />'. GameArrayLink($row1[$t]['game_id'], $row1[$t]['game_parent'], $row1[$t]['game_popup'], $row1[$t]['win_width'], $row1[$t]['win_height'], '3%SEP%'. CheckGameImages($game_name, $row1[$t]['proper_name']), '');
						break;
					}
				}
									
				$row_class = ( !($a % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
				$template->assign_block_vars('top_scores_rows', array(
					'ROW_CLASS' => $row_class,
					'GAME_IMAGE' => $game_image,
					'USER_SEARCH' => '<a href="' . append_sid('activity.'.$phpEx.'?page=trophy_search&amp;user=' . $fix_user_n) . '">' . username_level_color($user_data[$b]['username'], $user_data[$b]['user_level'], $fix_user_n) . '</a>',
					'SCORE' => $score,
					'DATE' => $date,
					'PM_PROFILE'	=> $pm . ' ' . $profile)
				);		
			}
		}
	}				
}
		
$total_games 	= $trophy_count;
$pagination 	= generate_pagination("activity.$phpEx?page=trophy", $total_games, $board_config['games_per_page'], $start). '&nbsp;';

$template->assign_vars(array(				
	'L_T_LINK'		=> $lang['trophy_count_link'],
	'U_T_LINK'		=> append_sid('activity.'.$phpEx.'?page=trophy_holders'),
	'PAGINATION'	=> $pagination,
	'PAGE_NUMBER' 	=> sprintf($lang['Page_of'], floor(($start / $board_config['games_per_page']) + 1), ceil($total_games / $board_config['games_per_page'])))
);
					
$template->pparse('body');

?>
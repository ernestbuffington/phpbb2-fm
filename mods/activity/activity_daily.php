<?php
/***************************************************************************
 *                              activity_daily.php
 *                             --------------------
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
		
$template->set_filenames(array(
	'activity_daily_section' => 'amod_files/activity_daily_body.tpl')
);
					
#==== Change the date if needed			
if (date('Y-m-d') != $board_config['ina_daily_game_date'])
{		
	$q = "UPDATE ". CONFIG_TABLE ."
		SET config_value = '". date('Y-m-d') ."'
		WHERE config_name = 'ina_daily_game_date'";
	$db->sql_query($q);		

	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	
	#==== Update the random game if system is set to random
	if ($board_config['ina_daily_game_random'])
	{
		$q =  "SELECT game_id
			FROM ". iNA_GAMES ."
			WHERE disabled <> 0
				AND game_id <> '". $board_config['ina_daily_game_id'] ."'
				AND game_type <> 2
			ORDER BY RAND()
			LIMIT 1"; 
		$r  	= $db->sql_query($q); 
		$row	= $db->sql_fetchrow($r);
		
		$match	= $row['game_id'];
		
		$q = "UPDATE ". CONFIG_TABLE ."
			SET config_value = '". $match ."'
			WHERE config_name = 'ina_daily_game_id'";
		$db->sql_query($q);	
		
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	}		
}
				
for ($x = 0; $x < $games_c; $x++)
{
	if ($games_data[$x]['game_id'] == $board_config['ina_daily_game_id'])
	{
		$game_id 		= $games_data[$x]['game_id'];
		$game_name 		= $games_data[$x]['game_name'];
		$game_path 		= $games_data[$x]['game_path'];
		$game_desc 		= $games_data[$x]['game_desc'];
		$win_width 		= $games_data[$x]['win_width'];
		$win_height 	= $games_data[$x]['win_height'];
		$game_fees 		= $games_data[$x]['game_charge'];
		$game_played	= $games_data[$x]['played'];
		$game_date		= $games_data[$x]['install_date'];
		$game_proper	= $games_data[$x]['proper_name'];
		$game_popup		= $games_data[$x]['game_popup'];
		$game_parent	= $games_data[$x]['game_parent'];
		$game_type		= $games_data[$x]['game_type'];		
		$game_ge_cost	= $games_data[$x]['game_ge_cost'];
		$game_keyboard	= $games_data[$x]['game_keyboard'];
		$game_mouse		= $games_data[$x]['game_mouse'];
		$game_cat		= $games_data[$x]['cat_id'];
		
		// Get Game Rating From Array ---------------------------------------- Dashe |
		unset($total_votes_given, $total_rating_given);

		for ($j = 0; $j <= count($rating_data); $j++)
		{
			if ($games_data[$x]['game_id'] == $rating_data[$j]['game_id'])
			{
				$total_votes_given  = $rating_data[$j]['total_ratings'];
				$total_rating_given = $rating_data[$j]['game_rated'];		
				break;
			}			
		}	
		
		// Get Game Comments From Array -------------------------------- Dashe |
		unset($total_comments);
		
		for ($j = 0; $j <= sizeof($comment_data); $j++)
		{
			if ($games_data[$x]["game_name"] == $comment_data[$j]["game"])
			{
				$total_comments = $comment_data[$j]["total_comments"];
				break;
			}			
		}
		
		if ($total_comments < 1)
		{
			$total_comments_shown = $lang['no_votes_cast'];
		}
		if ($total_comments) 	
		{
			$total_comments_shown = $total_comments;
		}
		
		// Get Favorites Data From Array ------------------------------- Dashe |
		unset($favorites_link);
		
		for ($j = 0; $j <= sizeof($favorites_data); $j++)
		{
			if (eregi(quotemeta("S". $games_data[$x]["game_id"] ."E"), $favorites_data[$j]["games"]) )
			{
				$favorites_link	= '<a href="' . append_sid('activity_favs.'.$phpEx.'?mode=del_fav&amp;game='. $game_id) . '"><img src="mods/activity/r_favorite_game.jpg" alt="' . $lang['favorites_r_mouse_over'] . '" title="' . $lang['favorites_r_mouse_over'] . '" /></a>';				
				break;
			}
			else
			{
				$favorites_link = '<a href="' . append_sid('activity_favs.'.$phpEx.'?mode=add_fav&amp;game=' . $game_id) . '"><img src="images/activity/favorite_game.jpg" alt="' .  $lang['favorites_mouse_over'] . '" title="' . $lang['favorites_mouse_over'] . '" /></a>';
				break;
			}
		}	
								
		// Get Rating Info From Array ---------------------------------------- |
		unset($game_rating_image, $rating_title, $rating_votes_cast, $rating_submit);
		
		for ($j = 0; $j <= $rating_count; $j++)
		{
			if ( $games_data[$x]['game_id'] == $rating_info[$j]['game_id'] )
			{
				if ( $rating_info[$j]['player'] == $userdata['user_id'] )				
				{				
					$rating_submit 	= str_replace('%R%', $rating_info[$j]['rating'], $lang['rating_text_line']);				
					break;
				}
				else
				{
					$rating_submit = '<a href="'. append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=rate&game='. $games_data[$x]['game_id'] .'\', \'New_Window\', \'450\', \'300\', \'yes\')') .'">'. $lang['game_rating_submit'] .'</a>';
				}
			}			
		}

		if ($total_votes_given == 1) 
		{
			$game_rating 		= round($total_rating_given / $total_votes_given);			
			$rating_votes_cast	= str_replace('%V%', $total_votes_given, $lang['game_rating_votes_one']);
			$game_rating_image	= '<img src="images/activity/ratings/'. $game_rating .'.gif" alt="'. $game_rating .'" title="'. $game_rating .'" />';
			$rating_title 		= $game_proper ."'s ". $lang['game_rating_title'];		
		}
		elseif ($total_votes_given > 0)
		{
			$game_rating 		= round($total_rating_given / $total_votes_given);
			$rating_votes_cast 	= str_replace('%V%', $total_votes_given, $lang['game_rating_votes']);
			$game_rating_image 	= '<img src="images/activity/ratings/'. $game_rating .'.gif" alt="'. $game_rating .'" title="'. $game_rating .'" />';
			$rating_title 		= $game_proper ."'s ". $lang['game_rating_title'];										
		}
		else
		{
			$game_rating 		= 0;
			$rating_votes_cast 	= str_replace('%V%', $lang['no_votes_cast'], $lang['game_rating_votes']);
			$game_rating_image 	= '<img src="./images/activity/ratings/'. $game_rating .'.gif" alt="'. $game_rating .'" title="'. $game_rating .'" />';
			$rating_title 		= $game_proper ."'s ". $lang['game_rating_title'];
			$rating_submit 		= '<a href="'. append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=rate&game='. $games_data[$x]['game_id'] .'\', \'New_Window\', \'450\', \'300\', \'yes\')') .'" class="mainmenu">'. $lang['game_rating_submit'] .'</a>';
		}
			
		if ($board_config['allow_smilies']) 
		{
			$game_desc = smilies_pass($game_desc);	
		}
		
		$new_image 		= ($game_date >= (time() - 86400 * $board_config['ina_new_game_limit']) ) ? '<img src="images/activity/new_game.gif" alt="" title="" /><br />' : '';			
		$popular_image 	= ($game_played >= $board_config['ina_pop_game_limit']) ? '<br /><img src="images/activity/popular_game.jpg" alt="" title="" />' : '';			
		$list_type 		= ($games_data[$x]['reverse_list']) ? 'ASC' : 'DESC';
								
		if ($games_data[$x]['game_charge'])
		{
			$game_charge = $games_data[$x]['game_charge'];
		}
		else
		{
			$game_charge = $lang['game_free']; 
		}
			
		// Get User Data From Array ------------------------------------------- |
		unset($top_player1, $t_player_id, $top_date, $top_score1, $top_score);
		
		for ($b = 0; $b <= $trophy_c; $b++)
		{
			if ($trophy_data[$b]['game_name'] == $game_name)
			{
				for ($c = 0; $c <= $user_c; $c++)
				{						
					if ($trophy_data[$b]['player'] == $user_data[$c]['user_id'] && $trophy_data[$b]['game_name'] == $game_name)
					{
						$top_player1 	= $trophy_data[$b]['player'];
						$t_player_id	= $user_data[$c]['user_id'];
						$top_player1 	= $user_data[$c]['username'];
						$top_player1_level 	= $user_data[$c]['user_level'];
						$top_score1 	= $trophy_data[$b]['score'];
						$top_date 		= $trophy_data[$b]['date'];
						$top_date 		= create_date($board_config['default_dateformat'], $top_date, $board_config['board_timezone']);
						$top_score		= FormatScores($top_score1);						
						break;
					}			
				}
			}
		}
		
		if ($top_player1 == 'Anonymous') 
		{
			$top_player = 'Anonymous';
		}
				
		if ( ($top_player1 <> 'Anonymous') && (strlen($top_player1) > 1) ) 
		{
			$top_player = '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $t_player_id) . '">' . username_level_color($top_player1, $top_player1_level, $t_player_id) . '</a>';		
		}
		
		unset($best_score_a, $best_score1, $best_player1, $best_player1_level);						
		
		for ($z = 0; $z < count($scores_data); $z++)
		{
			if ($scores_data[$z]['player'] != $top_player1)
			{
				if ($scores_data[$z]['game_name'] == $game_name)
				{
					if (!$scores_data[$z]['player'] || $scores_data[$z]['player'] == $top_player1)
					{
						$best_score_a	= $lang['best_player_default'];			
						$best_score1 	= 0;
						$best_player1 	= $lang['best_player_default'];				
					}						
					$best_score_a	= $scores_data[$z]['player'];			
					$best_score1 	= $scores_data[$z]['score'];
					$best_player1 	= $scores_data[$z]['player'];
					
					if ($list_type == 'DESC')
					{
						break;
					}
								
					if(!$scores_data[$z]['player'])
					{
						break;					
					}
				}						
			}
		}
									
		// Get User Data From Array ------------------------------------------- |
		unset($b_player_id);
		
		for ($a = 0; $a <= $user_c; $a++)
		{
			if ($best_score_a == $user_data[$a]['username'])
			{
				$b_player_id = $user_data[$a]['user_id'];
				$b_player_level = $user_data[$a]['user_level'];
				break;
			}			
		}
		
		$best_score = FormatScores($best_score1);
			
		if ($best_player1 == 'Anonymous') 
		{
			$best_player = 'Anonymous';
		}
					
		if ( ($best_player1 <> 'Anonymous') && (strlen($best_player1) > 1) ) 
		{
		$best_player = '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $b_player_id) . '">' . username_level_color($best_player1, $b_player_level, $b_player_id) . '</a>';
		}
		
		if ($games_data[$x]['game_show_score'] != 1)
		{
			$highscore_link = '';
			$best_score 	= '';
			$best_player 	= $lang['best_player_default'];
		}
		else
		{
			$highscore_link = '<br />' . $lang['seperator'] . ' <a href="' . append_sid('activity.'.$phpEx.'?page=high_scores&amp;mode=highscore&amp;game_name=' . $game_name) . '">' . $lang['game_highscores'] . '</a>';				
			$best_score 	= $best_score;
		}

		if ( strlen($best_player1) < 1 || $best_score < 1 ) 
		{
			$best_player = $lang['best_player_default'];
		}				
		
		if ($game_fees)
		{
			$cost = $game_fees . ' ' . $board_config['points_name']; 
		}
		else
		{
			$cost = $lang['game_free']; 
		}				
		
		$game_link 		= CheckGameImages($game_name, $game_proper);

		$button_link 	= $board_config['ina_button_option'];
		if ( $button_link == 1 )
		{
			$image_link 	= GameArrayLink($game_id, $game_parent, $game_popup, $win_width, $win_height, 2, '');
		}
		else
		{
			$image_link 	= GameArrayLink($game_id, $game_parent, $game_popup, $win_width, $win_height, 2, '');
		}
				
		$challenge = $board_config['ina_challenge'];			
		if ( ($challenge == 1) && ($t_player_id != ANONYMOUS) && ($userdata['user_id'] != ANONYMOUS) )
		{
			$challenge_link = '<br />'. $lang['seperator'] .' <a href="'. append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=challenge&g='. $game_id .'&u='. $t_player_id .'\', \'New_Window\', \'400\', \'200\', \'yes\')') .'">'. $lang['challenge_link_key'] .'</a>';
		}
		if ($challenge != 1 || $t_player_id == ANONYMOUS || $userdata['user_id'] == ANONYMOUS) 
		{
			$challenge_link = '<br />'. $lang['seperator'] .' '. $lang['challenge_link_key'];
		}
									
		$admin_edit = '';
		if ($userdata['user_level'] == ADMIN) 
		{
			$admin_edit = '<br />'. $lang['seperator'] .' <a href="javascript:Trophy_Popup(\'admin/admin_activity.'. $phpEx .'?mode=edit_games&action=edit&game='. $game_id .'&sid='. $userdata['session_id'] .'\', \'New_Window\', \'550\', \'300\', \'yes\')">'. $lang['admin_edit_link'] .'</a>';
		}
				
		$games_cost_line = $show_fees = $show_ge = $show_jack = '';
		if ($game_fees)
		{
		$show_fees 	= '<br />'. $lang['seperator'] .' '. $lang['cost'] .': <b>'. $cost . '</b>';
		}
		if ($game_ge_cost)
		{
			$show_ge 	= '<br />'. $lang['seperator'] .' '. strip_tags($lang['ge_cost_per_game']) .': '. number_format($game_ge_cost);				
		}
		if ($games_data[$x]['jackpot'])
		{
			$show_jack	= ($game_type != 2) ? '<br />'. $lang['seperator'] .' '. str_replace('%X%', intval($games_data[$x]['jackpot']), $lang['jackpot_text']) . ' ' . $board_config['points_name'] . '</b>' : '';				
		}
		$games_cost_line = $show_fees . $show_ge . $show_jack;
			
		if ( ($board_config['ina_disable_comments_page']) && ($userdata['user_level'] != ADMIN) ) 
		{
			$comments_link = '';						
		}
		else
		{
			$comments_link = append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=comments&game='. $game_name .'\', \'New_Window\', \'550\', \'300\', \'yes\')');
		}
				
		if ($game_type == 2)
		{
			$trophy_link = $top_player = $top_score = $top_date = $best_player = $best_score = $trophy_link = $download_link = $challenge_link = $highscore_link = '';
		}
		
		if ($game_cat > 0)
		{
			$game_category = Amod_Grab_Cat($game_cat, $category_data);
		}
		else
		{
			$game_category = $lang['game_rows_category_no'];
		}
	
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
		$template->assign_block_vars('daily_game', array(
			'ROW_CLASS' 	=> $row_class,
			'TITLE'			=> ($board_config['ina_daily_game_random']) ? str_replace('%T%', $lang['god_choice_r'], $lang['god_title']) : str_replace('%T%', $lang['god_choice_p'], $lang['god_title']),		
			'RATING_TITLE'	=> $rating_title,
			'RATING_SUBMIT'	=> $rating_submit,
			'RATING_SENT'	=> $rating_votes_cast,
			'RATING_IMAGE'	=> $game_rating_image,		
			'TOP_PLAYER' 	=> $top_player,
			'POP_PIC'		=> $popular_image,
			'FAVORITE_GAME' => $favorites_link,
			'MOUSE'			=> ($game_mouse) ? '<img src="images/activity/mouse.gif" alt="'. $lang['game_mouse'] .'" title="'. $lang['game_mouse'] .'" />' : '',
			'KEYBOARD'		=> ($game_keyboard) ? '<img src="images/activity/keyboard.gif" alt="'. $lang['game_keyboard'] .'" title="'. $lang['game_keyboard'] .' " />' : '',		
			'TOP_SCORE' 	=> ($game_type != 2) ? $lang['score'] . '<b>' . $top_score . '</b>' : '',
			'TOP_DATE'		=> $top_date,			
			'BEST_SCORE' 	=> ($game_type != 2) ? $lang['score'] . '<b>' . $best_score . '</b>' : '',
			'BEST_PLAYER' 	=> $best_player,
			'TROPHY_IMG'	=> ($game_type != 2) ? '<img src="images/activity/trophy.gif" alt="" title="" />' : '',
			'RUNNER_IMG'	=> ($game_type != 2) ? '<img src="images/activity/trophy2.gif" alt="" title="" />' : '',
			'DESC2'			=> $lang['new_description'],
			'GAMES_PLAYED'	=> $lang['seperator'] .' '. $lang['new_games_played'],
			'I_PLAYED'		=> '<b>' . number_format($game_played) . '</b>' . $games_cost_line . $admin_edit,
			'SEPERATOR'		=> $lang['seperator'] .' ',
			'CHARGE'		=> $cost,
			'PROPER_NAME'	=> $game_proper,
			'IMAGE_LINK'	=> '<center>' . $new_image . '</center>' . $game_link,
			'NEW_I_LINK'	=> $image_link,						
			'NAME' 			=> $game_name,
			'PATH' 			=> $game_path,			
			'DESC' 			=> $game_desc,
			'INFO'			=> $lang['info'],			
			'WIDTH'			=> $win_width,
			'HEIGHT' 		=> $win_height,
			'STATS'			=> append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=info&g='. $game_id .'\', \'New_Window\', \'400\', \'380\', \'yes\')'),
			'COMMENTS'		=> $comments_link,
			'L_COMMENTS'	=> $total_comments_shown .' '. $lang['comments_link_key'],
			'CHALLENGE'		=> $challenge_link,
			'DASH' 			=> $dash,
			'LIST'			=> $highscore_link,
			'DOWNLOAD_LINK'	=> $download_link,
			'LINKS'			=> GameArrayLink($game_id, $game_parent, $game_popup, $win_width, $win_height, 1, $games_data[$x]['game_links']) . (($game_category) ? '<br /><span class="gensmall"><b>&bull;</b> '. $game_category .'</span>': '')) 
		);
	}			
}		

$template->assign_var_from_handle('ACTIVITY_DAILY_SECTION', 'activity_daily_section');

?>
<?php
/** 
*
* @package phpBB2
* @version $Id: activity_favs.php,v 1.1.0 2005 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include_once($phpbb_root_path . 'extension.inc');
include_once($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_amod_plus.'. $phpEx);
include_once($phpbb_root_path . 'includes/bbcode.'. $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ACTIVITY);
init_userprefs($userdata);
//
// End session management
//
		
$user_id = $userdata['user_id'];
if (!$board_config['ina_guest_play'])
{
	if ( !$userdata['session_logged_in'] && $user_id == ANONYMOUS ) 
	{
		$header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: ";
		header($header_location . append_sid("login.$phpEx?redirect=activity_favs.$phpEx", true));
		exit; 
	}
}

UpdateSessions();		
BanCheck();
UpdateActivitySession();
					
if($HTTP_GET_VARS['mode'] != 'game')		
{
	UpdateUsersPage($userdata['user_id'], $_SERVER['REQUEST_URI']);
}
			
$game_cost = $board_config['points_name'];

$order_by = AdminDefaultOrder();			
	
if ($board_config['use_gamelib'] == 1)
{
	$gamelib_link = '<div align="center" class="copyright">' . $lang['game_lib_link'] . '</div>';
}
	
if ($board_config['use_gamelib'] == 0)
{
	$gamelib_link = '';
}			
if($HTTP_GET_VARS['mode'] == 'add_fav')
{
	$game = $HTTP_GET_VARS['game'];
	$user = $userdata['user_id'];

	$q = "SELECT *
		FROM " . iNA_GAMES . "
		WHERE game_id = " . $game; 
	$r = $db->sql_query($q); 
	$game_check = $db->sql_fetchrow($r);
	
	if ( !$game_check['game_id'] ) 
	{
		message_die(GENERAL_MESSAGE, $lang['favs_game_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_favs'], '<a href="' . append_sid('activity_favs.'.$phpEx) . '">', '</a>'));
	}
	
	$q = "SELECT *
		FROM " . INA_FAVORITES . "
		WHERE user = '" . $user . "'"; 
	$r = $db->sql_query($q); 
	$favorite_data = $db->sql_fetchrowset($r);
			
	for ($i = 0; $i <= sizeof($favorite_data); $i++)
	{
		$games = $favorite_data[$i]['games'];
			
		if (eregi(quotemeta('S' . $game . 'E'), $games))
		{
			message_die(GENERAL_MESSAGE, $lang['favs_game_does_exist'] . '<br /><br />' . sprintf($lang['Click_return_favs'], '<a href="' . append_sid('activity_favs.'.$phpEx) . '">', '</a>'));
		}
	}
			
	$q = "SELECT *
		FROM " . INA_FAVORITES . "
		WHERE user = " . $userdata['user_id']; 
	$r = $db->sql_query($q); 
	$fav_data = $db->sql_fetchrow($r);
				
	$new_game_info = $fav_data['games'] . 'S' . $game . 'E';
	
	if($fav_data['user'])
	{
		$q = "UPDATE " . INA_FAVORITES . "
			SET games = '" . $new_game_info . "'
			WHERE user = '" . $user . "'"; 
		$r = $db->sql_query($q); 
	}
	else
	{
		$q = "INSERT INTO ". INA_FAVORITES ."
			VALUES ('" . $user . "', 'S" . $game . "E')"; 
		$r = $db->sql_query($q); 		
	}
	message_die(GENERAL_MESSAGE, $lang['favs_success'] . '<br /><br />' . sprintf($lang['Click_return_favs'], '<a href="' . append_sid('activity_favs.'.$phpEx) . '">', '</a>'));
}
else if ( $HTTP_GET_VARS['mode'] == 'del_fav' )
{
	$game = $HTTP_GET_VARS['game'];
	$user = $userdata['user_id'];
				
	$q = "SELECT *
		FROM " . iNA_GAMES . "
		WHERE game_id = " . $game; 
	$r = $db->sql_query($q); 
	$game_check = $db->sql_fetchrow($r);
	
	if ( !$game_check['game_id'] )
	{
		message_die(GENERAL_MESSAGE, $lang['favs_game_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_favs'], '<a href="' . append_sid('activity_favs.'.$phpEx) . '">', '</a>'));
	}
	
	$q = "SELECT *
		FROM " . INA_FAVORITES . "
		WHERE user = '" . $user . "'"; 
	$r = $db->sql_query($q); 
	$favorite_data = $db->sql_fetchrowset($r);
			
	for($i = 0; $i <= sizeof($favorite_data); $i++)
	{
		$games = $favorite_data[$i]['games'];
			
		if(eregi(quotemeta('S' . $game . 'E'), $games))
		{
			$new_list = str_replace('S' . $game . 'E', '', $games);	
			$q = "UPDATE " . INA_FAVORITES . "
				SET games = '" . $new_list . "'
				WHERE user = '" . $user . "'"; 
			$r = $db->sql_query($q); 				
			
			message_die(GENERAL_MESSAGE, $lang['favs_deleted'] . '<br /><br />' . sprintf($lang['Click_return_favs'], '<a href="' . append_sid('activity_favs.'.$phpEx) . '">', '</a>'));
		}			
		else
		{
			message_die(GENERAL_MESSAGE, $lang['favs_game_not_fav'] . '<br /><br />' . sprintf($lang['Click_return_favs'], '<a href="' . append_sid('activity_favs.'.$phpEx) . '">', '</a>'));
		}				
	}					
}
else
{		 
	$template->set_filenames(array(
		'body' => 'amod_files/activity_favs_body.tpl') 
	);
	make_jumpbox('viewforum.'.$phpEx);

	//
	// Setup Trophy Array 
	//
	$q3 = "SELECT *
		FROM ". INA_TROPHY;				   			   
	$r3 = $db->sql_query($q3);
	$trophy_data 	= $db->sql_fetchrowset($r3);
	$trophy_c	 	= $db->sql_numrows($r3);
	
	//
	// Setup Users Array 
	//
	$q3 = "SELECT user_id, username, user_level
		FROM ". USERS_TABLE;				   			   
	$r3 = $db->sql_query($q3);
	$user_data 	= $db->sql_fetchrowset($r3);
	$user_c	 	= $db->sql_numrows($r3);
		
	//
	// Setup Favorites Array 		
	//
	$q = "SELECT *
		FROM ". INA_FAVORITES ."
		WHERE user = '" . $userdata['user_id'] . "'"; 
	$r = $db->sql_query($q); 
	$favorite_data 	= $db->sql_fetchrowset($r);
	$fav_c	  		= $db->sql_numrows($r);

	//
	// Setup Games Array 		
	//
	$q = "SELECT *
		FROM ". iNA_GAMES; 
	$r 			= $db->sql_query($q); 
	$games_data = $db->sql_fetchrowset($r);
	
	$template->assign_vars(array(
		'L_MONEY' => $board_config['points_name'])
	);
			
	$template->assign_vars(array(
		'CHALLENGE_LINK' => $lang['challenge_Link'],
		'TOP_FIVE_LINK'	=> $lang['top_five_10'],
			
		'C_DEFAULT_ALL'	=> append_sid('activity.'.$phpEx),
		'C_DEFAULT_ALL_L' => $lang['category_default_2'],
		'C_CAT_PAGE' => append_sid('activity.'.$phpEx.'?mode=category_play'),
		'GAMELIB_LINK' => $gamelib_link,
		'U_TROPHY' => append_sid('activity_top_scores.'.$phpEx),		
		'U_GAMBLING' => '<a href="' . append_sid('activity_gambling.'.$phpEx) . '" class="nav">' . $lang['gambling_link_2'] . '</a>',		
			
		'L_TROPHY' => $lang['trophy_page'],
		'L_STATS' => $lang['stats'],
		'L_COST' => $lang['cost'],
		'L_T_HOLDER' => $lang['trophy_holder'],
		'L_GAMES' => $lang['game_list'],
		'L_SCORES' => $lang['game_score'],
		'L_INFO' => $lang['game_info'])
	);
					
	$i = 0;
	for ($i = 0; $i <= $fav_c; $i++)
	{
		$games = $favorite_data[$i]['games'];
						
		for($j = 0; $j <= count($games_data); $j++)
		{
			if (eregi(quotemeta('S' . $games_data[$j]['game_id'] . 'E'), $games))
			{
				$fav_game_id = $games_data[$j]['game_id'];				
				$fav_game_name 	= $games_data[$j]['game_name'];
				$fav_game_path = $games_data[$j]['game_path'];
				$fav_game_desc = $games_data[$j]['game_desc'];
				$fav_win_width = $games_data[$j]['win_width'];
				$fav_win_height = $games_data[$j]['win_height'];
				$fav_game_fees = $games_data[$j]['game_charge'];
				$fav_game_played = $games_data[$j]['played'];
				$fav_game_date = $games_data[$j]['install_date'];
				$fav_game_proper = $games_data[$j]['proper_name'];
				$fav_game_order	= $games_data[$j]['reverse_list'];
				$fav_game_popup	= $games_data[$j]['game_popup'];
				$fav_game_parent = $games_data[$j]['game_parent'];
				$fav_game_ge_cost = $games_data[$j]['game_ge_cost'];
				$fav_game_keyboard = $games_data[$x]['game_keyboard'];
				$fav_game_mouse	= $games_data[$j]['game_mouse'];
				$fav_game_cat = $games_data[$j]['cat_id'];
						
				if ( !$fav_game_id ) 
				{
					message_die(GENERAL_ERROR, $lang['favorites_none_error_1'], $lang['favorites_none_error_2']);
				}
						
				if ( $board_config['allow_smilies'] )
				{
					$fav_game_desc = smilies_pass($fav_game_desc);	
				}

				$new_image = ($fav_game_date >= (time() - 86400 * $board_config['ina_new_game_limit']) ) ? '<img src="images/activity/new_game.gif" alt="" title="" /><br />' : '';			
				$popular_image = ($fav_game_played >= $board_config['ina_pop_game_limit']) ? '<br /><img src="images/activity/popular_game.jpg" alt="" title="" />' : '';			
							
				if ($games_data[$j]['game_charge'])
				{
					$game_charge = $games_data[$j]['game_charge'];
				}
				else
				{
					$game_charge = $lang['game_free']; 
				}
		
				// Get User Data From Array ------------------------------------------- |
				unset($top_player1, $t_player_id, $top_date, $top_score1, $top_score);
				
				for($b = 0; $b <= $trophy_c; $b++)
				{
					if ($trophy_data[$b]['game_name'] == $fav_game_name)
					{
						for($c = 0; $c <= $user_c; $c++)
						{						
							if($trophy_data[$b]['player'] == $user_data[$c]['user_id'] && $trophy_data[$b]['game_name'] == $fav_game_name)
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
					$top_player = '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;'. POST_USERS_URL . '=' . $t_player_id) . '" class="nav">' . username_level_color($top_player1, $top_player1_level, $t_player_id) . '</a>';
				}
				
				if ($games_data[$j]['game_show_score'] != 1)
				{
					$highscore_link = '';
					$best_score 	= '';
					$best_player 	= $lang['best_player_default'];
				}
				else
				{
					$highscore_link = '<a href="' . append_sid('activity.'.$phpEx.'?page=high_scores&amp;mode=highscore&amp;game_name=' . $fav_game_name) . '">' . $lang['game_highscores'] . '</a>';
					$best_score 	= $best_score;
				}
					
				if ( strlen($best_player) < 1 || $best_score < 1 )
				{
					$best_player = $lang['best_player_default'];
				}
						
				if ( $fav_game_fees )
				{
					$cost = $fav_game_fees . ' ' . $board_config['points_name']; 
				}
				else
				{
					$cost = $lang['game_free']; 
				}
						
				$remove_link = '<br /><br /><center><a href="' . append_sid('activity_favs.'.$phpEx.'?mode=del_fav&amp;game=' . $fav_game_id) . '"><img src="images/activity/r_favorite_game.jpg" alt="' . $lang['favorites_r_mouse_over'] . '" title="' . $lang['favorites_r_mouse_over'] . '" /></a></center>';

				$game_link = CheckGameImages($fav_game_name, $fav_game_proper);

		$button_link 	= $board_config['ina_button_option'];
		if ( $button_link == 1 )
		{
			$image_link 	= GameArrayLink($fav_game_id, $fav_game_parent, $fav_game_popup, $fav_win_width, $fav_win_height, 2, '');
		}
		else
		{
			$image_link 	= GameArrayLink($fav_game_id, $fav_game_parent, $fav_game_popup, $fav_win_width, $fav_win_height, 2, '');
		}

				$games_cost_line = '<br /><b>' . $lang['seperator'] . '</b> ' . $lang['cost'] . ': <b>' . $cost . '<br />' . $lang['seperator'] . '</b> ' . strip_tags($lang['ge_cost_per_game']) . ': <b>' . number_format($fav_game_ge_cost) . '</b>';			

				if ($fav_game_cat > 0)
				{
					$game_category = Amod_Grab_Cat($fav_game_cat, $category_data);
				}
				else
				{
					$game_category = $lang['game_rows_category_no'];
				}

				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
										
				$template->assign_block_vars('game', array(	
					'ROW_CLASS' => $row_class,								
					'DESC2' => $lang['new_description'],
					'GAMES_PLAYED' => $lang['new_games_played'],
					'I_PLAYED' => number_format($fav_game_played) . '</b>' . $games_cost_line . $admin_edit,
					'PROPER_NAME' => $fav_game_proper,												
					'TOP_PLAYER' => $top_player,
					'POP_PIC' => $popular_image,
					'TOP_SCORE' => $lang['score'] . '<b>' . $top_score . '</b>' . $remove_link,			
					'TROPHY_LINK' => 'images/activity/trophy.gif',
					'SEPERATOR'	=> $lang['seperator'],
					'CHARGE' => $cost,
					'IMAGE_LINK' => '<center>' . $new_image . '</center>' . $game_link,
					'NEW_I_LINK' => $image_link,						
					'NAME' => $fav_game_name,
					'PATH' => $fav_game_path,			
					'MOUSE' => ( $fav_game_mouse ) ? '<img src="images/activity/mouse.gif" alt="'. $lang['game_mouse'] .'" title="'. $lang['game_mouse'] .'" />' : '',
					'KEYBOARD' => ( $fav_game_keyboard ) ? '<img src="images/activity/keyboard.gif" alt="'. $lang['game_keyboard'] .'" title="'. $lang['game_keyboard'] .' " />' : '',		
					'DESC' => $fav_game_desc,
					'INFO' => $lang['info'],			
					'LINKS' => GameArrayLink($fav_game_id, $fav_game_parent, $fav_game_popup, $fav_win_width, $fav_win_height, 1, $games_data[$j]['game_links']) . (($game_category) ? '<br /><span class="gensmall"><b>&bull;</b> '. $game_category .'</span>': ''), 
					'STATS' => append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=info&' . POST_GROUPS_URL . '='. $fav_game_id .'\', \'New_Window\', \'400\', \'380\', \'yes\')'),
					'DASH' => $dash,
					'LIST' => $highscore_link)
				);
					
				if($fav_game_name) 
				{
					$i++;														
				}
			}								
		}	
	}					
}	
				
//
// Generate page
//
$page_title = $lang['favorites_page_title'];
include_once($phpbb_root_path . 'includes/page_header.'.$phpEx);
	
if(!$HTTP_GET_VARS['mode'])
{
	$template->pparse('body');
}

include_once($phpbb_root_path . 'includes/page_tail.'.$phpEx);				

?>
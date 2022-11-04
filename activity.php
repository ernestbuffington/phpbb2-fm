<?php
/** 
*
* @package phpBB2
* @version $Id: activity.php,v 1.1.0 2005 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', TRUE);
$phpbb_root_path = './';
include_once($phpbb_root_path .'extension.inc');
include_once($phpbb_root_path .'common.'. $phpEx);
include_once($phpbb_root_path .'includes/functions_amod_plus.'. $phpEx);
include_once($phpbb_root_path .'includes/bbcode.'. $phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ACTIVITY);
init_userprefs($userdata);
//
// End session management
//
	
#==== Check For Page Propagation
if (isset($HTTP_GET_VARS['page']))
{					
	$what_page = $HTTP_GET_VARS['page'];
	
	#==== Load The Page Title
	$page_title = $lang['page_title_'. $what_page];
	
	#==== Load The Header
	include_once($phpbb_root_path .'includes/page_header.'. $phpEx);
		
	#==== Load The Correct Page
	if ($what_page == 'hof')
	{
		include_once($phpbb_root_path .'mods/activity/activity_hof.'. $phpEx);	
	}
	if ($what_page == 'trophy')
	{
		include_once($phpbb_root_path .'mods/activity/activity_top_scores.'. $phpEx);		
	}
	if ($what_page == 'trophy_search')
	{
		include_once($phpbb_root_path .'mods/activity/activity_top_scores_search.'. $phpEx);		
	}
	if ($what_page == 'challenges')
	{
		include_once($phpbb_root_path .'mods/activity/challenges.'. $phpEx);
	}
	if ($what_page == 'trophy_holders')
	{
		include_once($phpbb_root_path .'mods/activity/activity_trophy_holders.'. $phpEx);		
	}
	if ($what_page == 'gambling')
	{
		include_once($phpbb_root_path .'mods/activity/activity_gambling.'. $phpEx);
	}
	if ($what_page == 'top')
	{
		include_once($phpbb_root_path .'mods/activity/activity_top_five.'. $phpEx);
	}
	if ($what_page == 'services')
	{
		include_once($phpbb_root_path .'mods/activity/activity_services.'. $phpEx);
	}
	if ($what_page == 'high_scores')
	{
		include_once($phpbb_root_path .'mods/activity/activity_highscores.'. $phpEx);
	}
	if ($what_page == 'search')
	{
		include_once($phpbb_root_path .'mods/activity/activity_search.'. $phpEx);
	}
	if ($what_page == 'whos_where')
	{
		include_once($phpbb_root_path .'mods/activity/activity_whos_where.'. $phpEx);												
	}
	if ($what_page == 'settings')
	{
		include_once($phpbb_root_path .'mods/activity/activity_settings.'. $phpEx);
	}
										
	#==== Load The Footer
	include_once($phpbb_root_path .'includes/page_tail.'. $phpEx);
		
	#==== Close This So Nothing Else Loads
	exit();
}

	
//
// Fix For When Adding New Games, Them Not Appearing In The Games List
//
$q = "SELECT *
	FROM " . iNA_GAMES . "
	WHERE played < 1
		AND game_type <> 2"; 
$r 	= $db->sql_query($q); 
		
while ($row	= $db->sql_fetchrow($r) )
{
	$game_n = $row['game_name'];
	$game_o = $row['reverse_list'];
		
	if ($game_o)
	{	
		$New_score = 1000;
	}
	else
	{
		$New_score = 1;
	}
					
	$q1 =  "INSERT INTO ". iNA_SCORES ."
		VALUES ('". $game_n ."', '". $userdata['username'] ."', '". $New_score ."', '". time() ."')"; 
	$db->sql_query($q1);
		
	$q2 =  "SELECT game_name
		FROM ". INA_TROPHY ."
		WHERE game_name = '". $game_n ."'"; 
	$r2 	= $db->sql_query($q2); 
	$row	= $db->sql_fetchrow($r2);			
		
	$exists = $row['game_name'];	
		
	if (!$exists)
	{
		$q3 =  "INSERT INTO ". INA_TROPHY ."
			VALUES ('". $game_n ."', '". $userdata['user_id'] ."', '". $New_score ."', '". time() ."')"; 
		$db->sql_query($q3);
	}
			
	$q4 =  "UPDATE ". iNA_GAMES ."
		SET played = 1
		WHERE game_name = '". $game_n ."'"; 
	$db->sql_query($q4);
}
			

//
// Start: Specific User Settings
//
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
#==== End: Specific User Settings	
		
$page_title = $lang['Activity'];

$user_id = $userdata['user_id'];
if (!$board_config['ina_guest_play'])
{
	if (!$userdata['session_logged_in'] && $user_id == ANONYMOUS)
	{
			$header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE") ) ) ? "Refresh: 0; URL=" : "Location: ";
			header($header_location . append_sid("login.$phpEx?redirect=activity.$phpEx", true) );
			exit();
	}
}

UpdateSessions();		
BanCheck();
UpdateActivitySession();

$sql = "DELETE FROM ". INA_CHEAT ."
	WHERE player = " . $userdata['user_id'];
$db->sql_query($sql);
			
$start = (isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$finish = ($user_use_games_count >= 1) ? $user_use_games_count : $board_config['games_per_page'];
		
$page_order = ($_POST['order']) ? $_POST['order'] : $HTTP_POST_VARS['order'];
if ( !$page_order )
{
	$page_order = ($_GET['order']) ? $_GET['order'] : $HTTP_GET_VARS['order'];
}
								
CheckGambles();
UpdateGamblePoints();
			
if (isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']))
{
	$mode = (isset($HTTP_GET_VARS['mode'])) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
}
else 
{
	if (isset($HTTP_POST_VARS['game']))
	{
		$mode = 'game';
	}
	elseif (isset($HTTP_POST_VARS['stats']))	
	{
		$mode = 'stats';
	}
	else
	{
		$mode = '';
	}
}

if ($mode != 'game')		
{	
	UpdateUsersPage($userdata['user_id'], $_SERVER['QUERY_STRING']);
}
				
#==== Trophy Data Array ================================= |
$q = "SELECT *
	FROM ". INA_TROPHY;				   			   
$r 				= $db->sql_query($q);
$trophy_data 	= $db->sql_fetchrowset($r);
$trophy_c	 	= $db->sql_numrows($r);
	
#==== Games Data Array ================================== |
$q = "SELECT *
	FROM ". iNA_GAMES;				   			   
$r 				= $db->sql_query($q);
$games_data 	= $db->sql_fetchrowset($r);
$games_c	 	= $db->sql_numrows($r);	
	
#==== Users Data Array ================================== |
$q = "SELECT user_id, username, user_level
	FROM ". USERS_TABLE;				   			   
$r			= $db->sql_query($q);
$user_data 	= $db->sql_fetchrowset($r);
$user_c	 	= $db->sql_numrows($r);

#==== Game Rating Data Array ============================ |
$q = "SELECT game_id, COUNT(*) AS total_ratings, SUM(rating) AS game_rated
	FROM ". INA_RATINGS ."
	GROUP BY game_id"; 
$r 				= $db->sql_query($q); 
$rating_data 	= $db->sql_fetchrowset($r);
	
#==== Rating Data Array ================================= |
$q = "SELECT *
	FROM ". INA_RATINGS; 
$r 				= $db->sql_query($q); 
$rating_info 	= $db->sql_fetchrowset($r);	
$rating_count	= $db->sql_numrows($r);
	
#==== Comments Data Array =============================== |
$q = "SELECT game, COUNT(game) AS total_comments
	FROM ". INA_TROPHY_COMMENTS ."
	GROUP BY game";
$r 				= $db->sql_query($q);
$comment_data 	= $db->sql_fetchrowset($r);
	
#==== Favorites Data Array ============================== |	
$q = "SELECT games
	FROM ". INA_FAVORITES ."
	WHERE user = " . $userdata['user_id'];
$r 				= $db->sql_query($q);
$favorites_data = $db->sql_fetchrowset($r);

#==== Scores Data Array ================================= |	
$q = "SELECT *
	FROM ". iNA_SCORES ."
	ORDER BY score DESC, date DESC";
$r 				= $db->sql_query($q);
$scores_data 	= $db->sql_fetchrowset($r);

#==== Category Data Array ================================= |	
$q = "SELECT *
	FROM ". INA_CATEGORY;
$r 				= $db->sql_query($q);
$category_data 	= $db->sql_fetchrowset($r);		
		
if ( ($mode == 'category_play') && (!$HTTP_GET_VARS['cat']) )
{
	$template->set_filenames(array(
		'body' => 'amod_files/activity_cat_page_body.tpl') 
	);
	make_jumpbox('viewforum.'.$phpEx);

	$total_games_here = $games_c;
			
	$total_p = 0;
	for ($z = 0; $z < $games_c; $z++)
	{
		$total_p = $total_p + $games_data[$z]['played'];
		if (!$games_data[$z]['game_id'])
		{
			break;
		}
	}
	$total_played = number_format($total_p);
				
	$new_length = time() - 86400 * $board_config['ina_new_game_limit'];		
	$q =  "SELECT COUNT(game_id) AS total_new
		FROM ". iNA_GAMES ."
		WHERE install_date > '". $new_length ."'"; 
	$r  	= $db->sql_query($q); 
	$row	= $db->sql_fetchrow($r);		
	$total_new = $row['total_new'];
	
	if (!$total_new) 
	{
		$total_new = 0;
	}
		
	$pop_limit = $board_config['ina_pop_game_limit'];
	
	$q =  "SELECT COUNT(game_id) AS total_popular
		FROM ". iNA_GAMES ."
		WHERE played > '". $pop_limit ."'"; 
	$r		= $db->sql_query($q); 
	$row	= $db->sql_fetchrow($r);		
	
	$total_pop = $row['total_popular'];
	
	if (!$total_pop) 
	{
		$total_pop = 0;		
	}
					
	$template->assign_block_vars('main_cats', array(
		'TITLE_1' 	=> $lang['main_cat_title_1'],
		'TITLE_2' 	=> $lang['main_cat_title_2'],
		'TITLE_3' 	=> $lang['main_cat_title_3'],
		'TITLE_4' 	=> $lang['main_cat_title_4'],
		'TITLE_5' 	=> $lang['main_cat_title_5'],
		'TITLE_6'	=> $lang['main_cat_title_6'],
		
		'T_GAMES_P'	=> $total_played,								
		'P_GAMES'	=> $total_pop,								
		'N_GAMES'	=> $total_new,																
		'GAMES'		=> $total_games_here,
		'GAMES_T'	=> $board_config['sitename'] . '\'s ' . $lang['admin_games'],
		'GAMES_D'	=> $lang['main_cat_all_desc'],
		'GAMES_I'	=> '') 
	);
	
	#==== Category SQL Array ========================================== |					
	$q =  "SELECT * 
    	FROM ". INA_CATEGORY ." 
        WHERE cat_id > 0"; 
	$r     		= $db->sql_query($q); 
    $cat_info	= $db->sql_fetchrowset($r);
	$cat_count	= $db->sql_numrows($r);

	#==== Category Game Count SQL Array =============================== |		
	$q =  "SELECT COUNT(game_id) AS total_games, cat_id
		FROM ". iNA_GAMES ."
		GROUP BY cat_id"; 
	$r  				= $db->sql_query($q); 
	$cat_count_info		= $db->sql_fetchrowset($r);		
	$cat_count_count	= $db->sql_numrows($r);						

	#==== Category Game Play Count SQL Array ========================== |
	$q =  "SELECT SUM(played) AS total_played, cat_id
		FROM ". iNA_GAMES ."
		GROUP BY cat_id"; 
	$r  				= $db->sql_query($q); 
	$cat_played_info	= $db->sql_fetchrowset($r);
	$cat_played_count	= $db->sql_numrows($r);		
	
	#==== Category Game New Count SQL Array =========================== |				
	$q =  "SELECT COUNT(game_id) AS total_new, cat_id
		FROM ". iNA_GAMES ."
		WHERE install_date > '". $new_length ."'
		GROUP BY cat_id"; 
	$r 				= $db->sql_query($q); 
	$cat_new_info	= $db->sql_fetchrowset($r);
	$cat_new_count	= $db->sql_numrows($r);		
		
	#==== Category Game Popular Count SQL Array ======================= |				
	$q =  "SELECT COUNT(game_id) AS total_popular, cat_id
		FROM ". iNA_GAMES ."
		WHERE played > '". $board_config['ina_pop_game_limit'] ."'			   
		GROUP BY cat_id"; 
	$r  			= $db->sql_query($q); 
	$cat_pop_info	= $db->sql_fetchrowset($r);
	$cat_pop_count	= $db->sql_numrows($r);				
		
	for ($a = 0; $a < $cat_count; $a++)
	{				
		$cat_img	= $cat_info[$a]['cat_img'];
		$cat_desc	= $cat_info[$a]['cat_desc'];
		$cat_name	= $cat_info[$a]['cat_name'];
		$cat_id		= $cat_info[$a]['cat_id'];
			
		unset($total_games);
		for ($b = 0; $b < $cat_count_count; $b++)
		{
			if ($cat_count_info[$b]['cat_id'] == $cat_id)
			{
				$total_games = $cat_count_info[$b]['total_games'];	
				break;
			}
		}
				
		unset($total_played);				
		for ($b = 0; $b < $cat_played_count; $b++)
		{
			if ($cat_played_info[$b]['cat_id'] == $cat_id)
			{
				if ($cat_played_info[$b]['total_played'] > 1)
				{
					$total_played = number_format($cat_played_info[$b]['total_played']);
				}
				else
				{
					$total_played = 0;	
				}
				break;
			}
		}

		unset($total_new);				
		for ($b = 0; $b < $cat_new_count; $b++)
		{
			if ($cat_new_info[$b]['cat_id'] == $cat_id)
			{
				if ($cat_new_info[$b]['total_new'] > 1)
				{
					$total_new = number_format($cat_new_info[$b]['total_new']);
				}
				else
				{
					$total_new = 0;	
				}
				break;
			}
			else
			{
				$total_new = 0;
			}
		}
				
		unset($total_pop);				
		for ($b = 0; $b < $cat_pop_count; $b++)
		{
			if ($cat_pop_info[$b]['cat_id'] == $cat_id)
			{
				if ($cat_pop_info[$b]['total_popular'] > 1)
				{
					$total_pop = number_format($cat_pop_info[$b]['total_popular']);
				}
				else
				{
					$total_pop = 0;	
				}
				break;
			}
		}																
			
		if (!$cat_desc) 
		{
			$cat_desc = $lang['main_cat_no_desc'];
		}
		
		if (file_exists($cat_img) == 0) 
		{
			$cat_img 	= '';
			$cat_name 	= '<a href="' . append_sid('activity.'.$phpEx.'?mode=category_play&amp;cat=' . $cat_id) . '">' . $cat_name . '</a>';
		}
		else
		{
			$cat_img 	= '<a href="' . append_sid('activity.'.$phpEx.'?mode=category_play&amp;cat= '. $cat_id) . '"><img src="' . $cat_img . '" alt="' . $cat_name . '" title="' . $cat_name . '" /></a>';
			$cat_name 	= $cat_name;
		}
								
		$template->assign_block_vars('main_cats_rows', array(
			'ONE' 		=> $cat_name . '<br />' . $cat_img,
			'TWO' 		=> $cat_desc,
			'THREE' 	=> $total_games,
			'FOUR'		=> $total_played,
			'FIVE'		=> $total_new,			
			'SIX'		=> $total_pop) 
		);		
	}			
}
else
{
	$q =  "SELECT game_id, game_popup, game_parent, win_width, win_height
		FROM ". iNA_GAMES ."
		WHERE disabled <> 0
		ORDER BY RAND()
		LIMIT 1"; 
	$r  	= $db->sql_query($q); 
	$row	= $db->sql_fetchrow($r);
		
	if ( ($row['game_parent']) && ($row['game_popup']) )
	{
		$random_game 	= append_sid("activity.". $phpEx ."?mode=game&id=". $row['game_id'] ."&parent=true");
	}
	elseif ( ($row['game_parent']) && (!$row['game_popup']) )
	{
		$random_game 	= append_sid("activity.". $phpEx ."?mode=game&id=". $row['game_id'] ."&parent=true");		
	}
	else
	{
		$random_game 	= 'javascript:Gk_PopTart(\'activity.'. $phpEx .'?mode=game&amp;id='. $row['game_id'] .'&sid='. $userdata['session_id'] .'\', \'New_Window\', \''. $row['win_width'] .'\', \''. $row['win_height'] .'\', \'no\')';
	}
	$random_image 	= 'templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/menu/icon_mini_faq.gif';
	
	if (isset($HTTP_POST_VARS['type']) )
	{
		$sort_order = ($HTTP_POST_VARS['type'] == 'DESC') ? 'DESC' : 'ASC';
	}
	elseif (isset($HTTP_GET_VARS['type']) )
	{
		$sort_order = ($HTTP_GET_VARS['type'] == 'DESC') ? 'DESC' : 'ASC';
	}
	else
	{
		$sort_order = 'DESC';
	}
		
	$game_cost = $board_config['points_name'];

	$mode_types_text 	= array($game_cost, $lang['game_bonuses'], $lang['order_type_jackpot'], $lang['game_played'], $lang['order_alphabetical'], $lang['sort_order_newest'], $lang['sort_order_oldest'], $lang['sort_order_reverse'], $lang['god_choice_r']);
	$mode_types 		= array('game_charge', 'game_bonus', 'jackpot', 'played', 'proper_name', 'install_dateN', 'install_dateO', 'reverse_list', 'RAND');
		
	$select_sort_mode 	= '<select name="order">';
	for ($i = 0; $i < count($mode_types_text); $i++)
	{
		$selected = ( $mode == $mode_types[$i] ) ? ' selected="selected"' : '';
		$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
	}
	$select_sort_mode .= '</select>';
	
	$select_sort_order = '<select name="type">';	
	if ($sort_order == 'DESC')
	{
		$select_sort_order .= '<option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option><option value="ASC">' . $lang['Sort_Ascending'] . '</option>';
	}
	else
	{
		$select_sort_order .= '<option value="DESC">' . $lang['Sort_Descending'] . '</option><option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option>';
	}
	
	$select_sort_order .= '</select>';
	
	$admin_d = AdminDefaultOrder();

	if ($page_order == 'jackpot') 		
	{
		$order_by = "jackpot $sort_order LIMIT $start,". $finish;
	}
	if ($page_order == 'RAND') 			
	{
		$order_by = "RAND() LIMIT $start,". $finish;
	}
	if ($page_order == 'install_dateN') 
	{
		$order_by = "install_date DESC LIMIT $start,". $finish;
	}
	if ($page_order == 'install_dateO') 
	{
		$order_by = "install_date ASC LIMIT $start,". $finish;
	}
	if ($page_order == 'reverse_list')  
	{
		$order_by = "reverse_list $sort_order LIMIT $start,". $finish;
	}
	if ($page_order == 'proper_name') 	
	{
		$order_by = "proper_name $sort_order LIMIT $start,". $finish;
	}
	if ($page_order == 'game_charge') 	
	{
		$order_by = "game_charge $sort_order LIMIT $start,". $finish;
	}
	if ($page_order == 'game_bonus') 	
	{
		$order_by = "game_bonus $sort_order LIMIT $start,". $finish;
	}
	if ($page_order == 'played') 	
	{
		$order_by = "played $sort_order LIMIT $start,". $finish;
	}
	if ($page_order == 'game_id') 		
	{
		$order_by = "game_id $sort_order LIMIT $start,". $finish;	
	}
	
	if (!$page_order) 					
	{
		$order_by = "$admin_d LIMIT $start, ". $finish;
	}
		
	$template->set_filenames(array(
		'body' => 'amod_files/activity2_body.tpl') 
	);		
	make_jumpbox('viewforum.'.$phpEx);

	$template->assign_block_vars('links_check', array(
		'LINKS'	=> SetHeaderLinks()) 
	);

	$where_disabled = ($userdata['user_level'] == ADMIN) ? '' : "WHERE disabled > 0" ;	
	
	if ($mode == 'game')
	{
		if ( $board_config['ina_force_registration'] && $userdata['user_id'] == ANONYMOUS )
		{
			$gen_simple_header = TRUE;
			message_die(GENERAL_MESSAGE, $lang['force_registration']);
		}
			
		$game_id 	= (isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : 0;
		$cheat_var	= time();

		InsertPlayingGame($userdata['user_id'], $game_id);
		
		/* Start insert starting game */		
		$sql = "SELECT user_id 
			FROM ". INA_CHEAT ."
			WHERE player = " . $userdata['user_id'];
		$result = $db->sql_query($sql);
		$row 	= $db->sql_fetchrow($result);
		
		$there 	= $row['user_id'];
		
		if (!$there)
		{
			$sql = "INSERT INTO ". INA_CHEAT ."
				VALUES ('". $game_id ."', '". $userdata['user_id'] ."')";
			$db->sql_query($sql);
		}
		else
		{
			$sql = "UPDATE ". INA_CHEAT ."
				SET game_id = '". $game_id ."'
				WHERE player = " . $userdata['user_id'];
			$db->sql_query($sql);			
		}
		/* End insert starting game */
				
		$sql = "SELECT user_id 
			FROM ". INA_LAST_GAME ."
			WHERE user_id = " . $userdata['user_id'];
		$result = $db->sql_query($sql);
		$row 	= $db->sql_fetchrow($result);
		
		$exists = $row['user_id'];
		
		if (!$exists)
		{
			$sql = "INSERT INTO ". INA_LAST_GAME ."
				VALUES ('". $game_id ."', '". $userdata['user_id'] ."', '". time() ."')";
			$db->sql_query($sql);
		}
		else
		{
			$sql = "UPDATE ". INA_LAST_GAME ."
				SET game_id = '". $game_id ."', date = '". time() ."'
				WHERE user_id = " . $userdata['user_id'];
			$db->sql_query($sql);			
		}
			
		$sql = "SELECT * 
			FROM ". iNA_GAMES ."
			WHERE game_id = " . $game_id;
		if (!$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['no_game_data'], '', __LINE__, __FILE__, $sql);
		}
		
		$game_info 	= $db->sql_fetchrow($result);
		$parent 	= '';
		
		if ($HTTP_GET_VARS['parent']) 
		{
			$parent = "&parent=true";
		}
			
		$gamepath 		= 'game.'. $phpEx .'?id='. $game_id . $parent ."&x=". $cheat_var;
		$game_charge 	= $game_info['game_charge'];

		if ($userdata['user_points'] >= $game_charge)
		{
			subtract_points($user_id, $game_charge);			
		}
		else
		{
			$gen_simple_header = TRUE;
			message_die(GENERAL_MESSAGE, $lang['not_enough_points']);
		}

		if ($userdata['ina_char_ge'] < $game_info['game_ge_cost'])
		{
			message_die(GENERAL_ERROR, $lang['ge_cost_game_error']);
		}
		else
		{
			AMP_Sub_GE($userdata['user_id'], $game_info['game_ge_cost']);
		}
			
		$sql = "UPDATE ". iNA_GAMES ."
			SET played = played + '1'
			WHERE game_id = " . $game_id;
		if (!$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['no_game_update'], __LINE__, __FILE__, $sql);		
		}
		
		$q =  "UPDATE ". SESSIONS_TABLE ."
			SET session_page = '". PAGE_PLAYING_GAMES ."'
			WHERE session_user_id = " . $userdata['user_id']; 
		$db->sql_query($q); 
		
		#==== Time spent playing games
		$time_spent 	= explode(';;', $userdata['ina_time_playing']);
		$update_time 	= time();
		$current_spent	= ($time_spent[1] > 0) ? $time_spent[1] : 0;
		$final			= $update_time . ';;' . $current_spent;
		
		$q =  "UPDATE ". USERS_TABLE ."
			SET user_session_page = '". PAGE_PLAYING_GAMES ."', ina_time_playing = '". $final ."'
			WHERE user_id = " . $userdata['user_id']; 
		$db->sql_query($q); 
										
		header('Location: '. $gamepath);	
	}
	else
	{		 
		/* First Time Use, Transfer The Highest Scores To The New activity.php */
		$q1 =  "SELECT COUNT(*) AS total
			FROM ". INA_TROPHY; 
		$r1 	= $db->sql_query($q1); 
		$row1	= $db->sql_fetchrow($r1);
		
		$game_n = $row1['total'];
		
		if (!$game_n)
		{								
			$q =  "SELECT *, MAX(score) AS highest
				FROM ". iNA_SCORES ."
			   	GROUP BY game_name"; 
			$r  		= $db->sql_query($q);
			
			while ($row	= $db->sql_fetchrow($r) )
			{
				$h_p = $row['player'];
				$h_s = $row['highest'];
				$h_g = $row['game_name'];
				$h_t = time();
		
				$q1 =  "SELECT user_id
					FROM ". USERS_TABLE ."
					WHERE username = '". $h_p ."'"; 
				$r1 	= $db->sql_query($q1); 
				$row1	= $db->sql_fetchrow($r1);
		
				$n_h_p	= $row1['user_id'];
		
				if (!$n_h_p) 
				{
					$n_h_p = $userdata['user_id'];
				}
				
				$q2 =  "INSERT INTO ". INA_TROPHY ."
			    	VALUES ('". $h_g ."', '". $n_h_p ."', '". $h_s ."', '". $h_t ."')"; 
				$db->sql_query($q2);					
			}
		}		
		/* Finished With First Time Use */			

		$template->set_filenames(array(
			'body' => 'amod_files/activity2_body.tpl') 
		);
		make_jumpbox('viewforum.'.$phpEx);

		if ($user_use_games)
		{
			$template->assign_block_vars('games_on', array());
		}
				
		$gamelib_link = ($board_config['use_gamelib'] == 1) ? '<div align="center"><span class="copyright">' . $lang['game_lib_link'] . '</span></div>' : '';

		$template->assign_vars(array(
			'L_MONEY' => $board_config['points_name']) 
		);
		
		$where_clause = ( $userdata['user_level'] == ADMIN ) ? '' : "WHERE disabled = 1";	
		$drop_block = ( $where_clause ) ? "AND disabled = 1" : '';
		
		$q1 =  "SELECT *
			FROM ". iNA_GAMES ."
			WHERE game_id <> 0
				$drop_block
			ORDER BY proper_name ASC"; 
		$r1 		= $db->sql_query($q1); 
		
		while ($row	= $db->sql_fetchrow($r1) )
		{
			if ( ($row['game_parent']) && ($row['game_popup']) )
			{
				$game_i2 = append_sid("activity.". $phpEx ."?mode=game&id=". $row['game_id'] ."&parent=true");
			}
			elseif ( ($row['game_parent']) && (!$row['game_popup']) )
			{
				$game_i2 = append_sid("activity.". $phpEx ."?mode=game&id=". $row['game_id'] ."&parent=true");		
			}
			else
			{
				$game_i2 = 'javascript:Gk_PopTart(\'activity.'. $phpEx .'?mode=game&amp;id='. $row['game_id'] .'&sid='. $userdata['session_id'] .'\', \'New_Window\', \''. $row['win_width'] .'\', \''. $row['win_height'] .'\', \'no\')';
			}
			
			$template->assign_block_vars('drop', array(
				'D_SELECT_1' => $game_i2,
				'D_SELECT_2' => $row['proper_name'])
			);			
		}
			
		$q2 =  "SELECT a.*, COUNT(b.cat_id) AS total
			FROM ". INA_CATEGORY ." a, ". iNA_GAMES ." b
			WHERE a.cat_id > 0
				AND a.cat_id = b.cat_id
			GROUP BY a.cat_name
			ORDER BY a.cat_name ASC"; 
		$r2 		= $db->sql_query($q2);  
		
		while ($row	= $db->sql_fetchrow($r2) )
		{					
			$template->assign_block_vars('cat', array(
				'C_SELECT_1' => '('. $row['total'] .') '. $row['cat_name'],
				'C_SELECT_2' => "activity.". $phpEx ."?mode=category_play&cat=". $row['cat_id'] ."&sid=". $userdata['session_id']) 
			);			
		}
			
		if ( ($mode == 'category_play') && ($HTTP_GET_VARS['cat']) ) 
      	{ 
			$cat = $HTTP_GET_VARS['cat']; 
	
      		if ($where_clause) 
        	{ 
      			$extra_where = "AND cat_id = '". $cat ."'"; 
      			$new_orderby = "ORDER BY ". $admin_d; 
        	} 
      		else 
        	{ 
      			$extra_where = "WHERE cat_id = '". $cat ."'";          
      			$new_orderby = "ORDER BY ". $admin_d; 
        	} 
      	} 
  		else 
    	{ 
   			$extra_where = ''; 
   			$new_orderby = 'ORDER BY '. $order_by; 
    	} 
          	  
       	$sql = "SELECT * 
        	FROM ". iNA_GAMES ." 
           	$where_clause 
           	$extra_where          
           	$new_orderby"; 					
		if (!$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['no_game_data'], '', __LINE__, __FILE__, $sql);
		}
			
		$game_count = $db->sql_numrows($result);
		$game_rows  = $db->sql_fetchrowset($result);
		
		for ($i = 0; $i < $game_count; $i++)
		{
			unset($game_id, $game_name, $game_path, $game_desc, $win_width, $win_height, $game_fees, $game_played, $game_date, $game_proper, $game_parent, $game_popup, $game_type, $game_links, $game_ge_cost, $game_mouse, $game_keyboard, $game_cat);
		  
			$game_id 		= $game_rows[$i]['game_id'];
			$game_name 		= $game_rows[$i]['game_name'];
			$game_path 		= $game_rows[$i]['game_path'];
			$game_desc 		= $game_rows[$i]['game_desc'];
			$win_width 		= $game_rows[$i]['win_width'];
			$win_height 	= $game_rows[$i]['win_height'];
			$game_fees 		= $game_rows[$i]['game_charge'];
			$game_played	= $game_rows[$i]['played'];
			$game_date		= $game_rows[$i]['install_date'];
			$game_proper	= $game_rows[$i]['proper_name'];
			$game_parent	= $game_rows[$i]['game_parent'];
			$game_popup		= $game_rows[$i]['game_popup'];		
			$game_type		= $game_rows[$i]['game_type'];
			$game_links		= $game_rows[$i]['game_links'];
			$game_ge_cost	= $game_rows[$i]['game_ge_cost'];
			$game_mouse		= $game_rows[$i]['game_mouse'];
			$game_keyboard	= $game_rows[$i]['game_keyboard'];
			$game_cat		= $game_rows[$i]['cat_id'];
		

			// Get Game Rating From Array ---------------------------------------- Dashe |
			unset($total_votes_given, $total_rating_given);

			for ($j = 0; $j <= count($rating_data); $j++)
			{
				if ($game_rows[$i]['game_id'] == $rating_data[$j]['game_id'])
				{
					$total_votes_given  = $rating_data[$j]['total_ratings'];
					$total_rating_given = $rating_data[$j]['game_rated'];		
					break;
				}			
			}	
		
			// Get Game Comments From Array -------------------------------- Dashe |
			unset($total_comments);
			
			for ($j = 0; $j <= count($comment_data); $j++)
			{
				if ($game_rows[$i]['game_name'] == $comment_data[$j]['game'])
				{
					$total_comments = $comment_data[$j]['total_comments'];
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
		
			for ($j = 0; $j <= count($favorites_data); $j++)
			{
				if (eregi(quotemeta('S' . $game_rows[$i]['game_id'] . 'E'), $favorites_data[$j]['games']) )
				{
					$favorites_link	= '<a href="' . append_sid('activity_favs.'.$phpEx.'?mode=del_fav&amp;game=' . $game_id) . '"><img src="images/activity/r_favorite_game.jpg" alt="' . $lang['favorites_r_mouse_over'] . '" title="' . $lang['favorites_r_mouse_over'] . '" /></a>';				
					break;
				}
				else
				{
					$favorites_link = '<a href="' . append_sid('activity_favs.'.$phpEx.'?mode=add_fav&amp;game=' . $game_id) . '"><img src="images/activity/favorite_game.jpg" alt="' . $lang['favorites_mouse_over'] . '" title="' . $lang['favorites_mouse_over'] . '" /></a>';
					break;
				}
			}	
								
			// Get Rating Info From Array ---------------------------------------- |
			unset($game_rating_image, $rating_title, $rating_votes_cast, $rating_submit);
		
			for ($j = 0; $j <= count($rating_info); $j++)
			{
				if ( $game_rows[$i]['game_id'] == $rating_info[$j]['game_id'] )
				{
					if ( $rating_info[$j]['player'] == $userdata['user_id'] )
					{				
						$rating_submit 	= str_replace('%R%', $rating_info[$j]['rating'], $lang['rating_text_line']);				
						break;
					}
					else
					{
						$rating_submit = '<a href="'. append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=rate&game='. $game_rows[$i]['game_id'] .'\', \'New_Window\', \'450\', \'300\', \'yes\')') .'" class="mainmenu">'. $lang['game_rating_submit'] .'</a>';
					}			
				}
			}
		
			if ( $total_votes_given == 1 ) 
			{
				$game_rating 		= round($total_rating_given / $total_votes_given);			
				$rating_votes_cast	= str_replace('%V%', $total_votes_given, $lang['game_rating_votes_one']);
				$game_rating_image	= '<img src="images/activity/ratings/'. $game_rating .'.gif" alt="'. $game_rating .'" title="'. $game_rating .'" />';
				$rating_title 		= $game_proper ."'s ". $lang['game_rating_title'];		
			}
			elseif ( $total_votes_given > 0 )
			{
				$game_rating 		= round($total_rating_given / $total_votes_given);
				$rating_votes_cast	= str_replace('%V%', $total_votes_given, $lang['game_rating_votes']);
				$game_rating_image 	= '<img src="images/activity/ratings/'. $game_rating .'.gif" alt="'. $game_rating .'" title="'. $game_rating .'" />';
				$rating_title 		= $game_proper ."'s ". $lang['game_rating_title'];										
			}
			else
			{
				$game_rating 		= 0;
				$rating_votes_cast	= str_replace('%V%', $lang['no_votes_cast'], $lang['game_rating_votes']);
				$game_rating_image 	= '<img src="images/activity/ratings/'. $game_rating .'.gif" alt="'. $game_rating .'" title="'. $game_rating .'" />';
				$rating_title 		= $game_proper ."'s ". $lang['game_rating_title'];
				$rating_submit 		= '<a href="'. append_sid('javascript:Trophy_Popup(\'activity_popup.'. $phpEx .'?mode=rate&game='. $game_rows[$i]['game_id'] .'\', \'New_Window\', \'450\', \'300\', \'yes\')') .'" class="mainmenu">'. $lang['game_rating_submit'] .'</a>';
			}

			if ($board_config['allow_smilies']) 
			{
				$game_desc = smilies_pass($game_desc);	
			}
			
			$new_image 		= ($game_date >= (time() - 86400 * $board_config['ina_new_game_limit']) ) ? '<img src="images/activity/new_game.gif" alt="" title="" /><br />' : '';			
			$popular_image 	= ($game_played >= $board_config['ina_pop_game_limit']) ? '<br /><img src="images/activity/popular_game.jpg" alt="" title="" />' : '';			
			$list_type = ($game_rows[$i]['reverse_list']) ? 'ASC' : 'DESC';
						
			if ($game_rows[$i]['game_charge'])
			{
				$game_charge = $game_rows[$i]['game_charge'];
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
			
			if ($game_rows[$i]['game_show_score'] != 1)
			{
				$highscore_link = '';
				$best_score 	= '';
				$best_player 	= $lang['best_player_default'];
			}
			else
			{
				$highscore_link = "<br />". $lang['seperator'] ." <a href='" . append_sid("activity.$phpEx?page=high_scores&mode=highscore&amp;game_name=$game_name") . "'>" . $lang['game_highscores'] . "</a>";				
				$best_score 	= $best_score;
			}

			if ( strlen($best_player1) < 1 || $best_score < 1 ) 
			{
				$best_player = $lang['best_player_default'];
			}
					
			if ( $game_fees )
			{
				$cost = $game_fees .' '. $board_config['points_name']; 
			}
			else
			{
				$cost = $lang['game_free']; 
			}
						
			$game_link 		= CheckGameImages($game_name, $game_proper);

			$button_link 	= $board_config['ina_button_option'];
			if ($button_link == 1)
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
			if ($game_rows[$i]['jackpot'])
			{
				$show_jack	= ($game_type != 2) ? '<br />'. $lang['seperator'] .' '. str_replace('%X%', intval($game_rows[$i]['jackpot']), $lang['jackpot_text']) . ' ' . $board_config['points_name'] . '</b>' : '';		
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
			
			if ($user_use_games)
			{
				$to_use = 'games_on.game';
			}
			else
			{
				$to_use = '';
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
				
			$template->assign_block_vars($to_use, array(
				'ROW_CLASS' 	=> $row_class,
				'RATING_TITLE'	=> $rating_title,
				'RATING_SUBMIT'	=> $rating_submit,
				'RATING_SENT'	=> $rating_votes_cast,
				'RATING_IMAGE'	=> $game_rating_image,
				'TOP_PLAYER' 	=> $top_player,
				'POP_PIC'		=> $popular_image,
				'FAVORITE_GAME' => $favorites_link,
				'MOUSE'			=> ($game_mouse) ? '<img src="images/activity/mouse.gif" alt="'. $lang['game_mouse'] .'" title="'. $lang['game_mouse'] .'" />' : '',
				'KEYBOARD'		=> ($game_keyboard) ? '<img src="images/activity/keyboard.gif" alt="'. $lang['game_keyboard'] .'" title="'. $lang['game_keyboard'] .' " />' : '',		
				'TOP_SCORE' 	=> ($game_type != 2) ? $lang['score'] . '<b>' . $top_score . ' </b>' : '',
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
				'LINKS'			=> GameArrayLink($game_id, $game_parent, $game_popup, $win_width, $win_height, 1, $game_links) . (($game_category) ? '<br /><span class="gensmall"><b>&bull;</b> '. $game_category .'</span>': ''))
			);
		}
				
		if ($HTTP_GET_VARS['mode'] != 'category_play')
		{
			$sql = "SELECT COUNT(game_id) AS total
				FROM ". iNA_GAMES ."
				WHERE game_id <> 0";
			if (!($result = $db->sql_query($sql) ))
			{
				message_die(GENERAL_ERROR, $lang['no_game_total'], '', __LINE__, __FILE__, $sql);
			}
			
			if ($total = $db->sql_fetchrow($result) )
			{
				$d 				= explode(' ', AdminDefaultOrder() );
				$page_order 	= ($_POST['order']) ? $_POST['order'] : $HTTP_POST_VARS['order'];
			
				if ( !$page_order )
				{
					$page_order = ($_GET['order']) ? $_GET['order'] : $HTTP_GET_VARS['order'];
				}
				
				if ( !$page_order )
				{
					$page_order = $d[0];
					$sort_order = $d[1]; 
				}
			
				$total_games 	= $total['total'];
				$pagination 	= generate_pagination("activity.$phpEx?order=". $page_order ."&amp;type=$sort_order", $total_games, $finish, $start). '&nbsp;';
				$page_number 	= sprintf($lang['Page_of'], ( floor( $start / $finish ) + 1 ), ceil( $total_games / $finish ) );		
			}
		}
			
		if ( ($mode == 'category_play') && ($HTTP_GET_VARS['cat']) )
		{
			$q =  "SELECT * 
             	FROM ". INA_CATEGORY ." 
               	WHERE cat_id = " . $HTTP_GET_VARS['cat']; 
        	$r     = $db->sql_query($q); 
        	$row   = $db->sql_fetchrow($r);
		
			$cat_img	= $row['cat_img'];
		
			if ($cat_img) 
			{
				$header_logo_image = $cat_img;
			}
			else
			{
				$header_logo_image = $board_config['ina_use_logo'];
			}
		}
		else
		{
			$header_logo_image = $board_config['ina_use_logo'];
		}
			
		if (!$header_logo_image) 
		{
			$header_logo_image = '';
		}
		
		if ( (!$HTTP_GET_VARS['mode'] == 'category_play') && (!$HTTP_GET_VARS['cat']) )
		{
			$mode_action = append_sid('activity.'.$phpEx);
		}
		else
		{
			$mode_action = append_sid('activity.'.$phpEx.'?mode=category_play&cat='. $HTTP_GET_VARS['cat']);
		}
			
		$template->assign_vars(array(
			'D_DESCRIPTION'			=> $lang['quick_select'],
			'D_DEFAULT'				=> $lang['choose_game'],
			'C_DESCRIPTION'			=> $lang['category_desc'],
			'C_DEFAULT'				=> $lang['category_default'],
			'RANDOM_GAME'			=> $random_game,
			'RANDOM_IMAGE'			=> $random_image,
			'RANDOM_LINK'			=> $lang['random_link'],
			'CHALLENGE_LINK'		=> $lang['challenge_Link'],
			'TOP_FIVE_LINK'			=> $lang['top_five_10'],
			'ORDER_SELECT_TITLE'	=> $lang['order_method_title'],
			'JUMP_TO_TITLE'			=> $lang['jump_to_boxes_title'],		
			'HEADER_LOGO'			=> $header_logo_image,
			
			'C_DEFAULT_ALL'			=> append_sid('activity.'.$phpEx),
			'C_DEFAULT_ALL_L'		=> $lang['category_default_2'],
			'C_CAT_PAGE'			=> append_sid('activity.'.$phpEx.'?mode=category_play'),
			'GAMELIB_LINK' 			=> $gamelib_link,
			'PAGINATION'			=> $pagination,
			'PAGE_NUMBER'			=> $page_number, 
			'S_MODE_SELECT'			=> $select_sort_mode,
			'S_ORDER_SELECT' 		=> $select_sort_order,
			'S_MODE_ACTION' 		=> $mode_action,
			'U_TROPHY'		 		=> append_sid("activity_top_scores.$phpEx"),		
			'U_GAMBLING'			=> '<a href="' . append_sid('activity_gambling.'.$phpEx) . '">' . $lang['gambling_link_2'] . '</a>',		

			'L_CAT_PAGE'			=> $lang['category_listing_txt'],
			'L_TROPHY'				=> $lang['trophy_page'],
			'L_STATS' 				=> $lang['stats'],
			'L_COST'				=> $lang['cost'],
			'L_T_HOLDER'			=> $lang['trophy_holder'],
			'L_SCORE'				=> $lang['score_2'],
			'L_R_UP'				=> $lang['runner_up'],
			'L_GAMES'				=> $lang['game_list'],
			'L_SCORES' 				=> $lang['game_score'],
			'L_INFO'				=> $lang['game_info'],
			'L_PLAYER' 				=> $lang['game_best_player'],
			'L_ORDER' 				=> $lang['Order'],
			'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
			'L_GOTO_PAGE' 			=> $lang['Goto_page']) 
		);
	}
}	
		
#==== Extra Sections		
if ($user_use_info)
{
	include_once($phpbb_root_path . 'mods/activity/activity_info.'.$phpEx);
}
		
if ( ($board_config['ina_use_daily_game']) && ($user_use_daily) ) 
{
	include_once($phpbb_root_path . 'mods/activity/activity_daily.'.$phpEx);
}

if ($HTTP_GET_VARS['mode'] != 'category_play')
{
	if ( ($board_config['ina_use_newest'] == 1) && ($user_use_newest) ) 
	{
		include_once($phpbb_root_path . 'mods/activity/activity_newest.'.$phpEx);	
	}
}	

if ( ($board_config['ina_use_online'] == 1) && ($user_use_online) ) 
{
	include_once($phpbb_root_path . 'mods/activity/activity_online.'.$phpEx);
}
		
$clean_time = (time() - 300);
CleanInaSessions($clean_time);
		
//
// Generate page
//
include_once($phpbb_root_path . 'includes/page_header.'. $phpEx);

$template->pparse('body');

include_once($phpbb_root_path . 'includes/page_tail.'. $phpEx);

?>
<?php
/***************************************************************************
 *                             activity_top_five.php
 *                            -----------------------
 *		Version			: 1.1.0
 *		Email			: austin@phpbb-amod.com
 *		Site			: http://phpbb-amod.com
 *		Copyright		: aUsTiN-Inc 2003/5
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

// File Specific Disable */
if(($userdata['user_level'] != ADMIN) && ($board_config['ina_disable_top5_page'])) 
{
	message_die(GENERAL_MESSAGE, $lang['disabled_page_error']);
}
//
// End Restriction Checks
//
 
$start 	= ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$finish = $board_config['games_per_page'];

$template->set_filenames(array(
	'body' => 'amod_files/activity_top5.tpl') 
);
make_jumpbox('viewforum.'.$phpEx);
	
$template->assign_block_vars('top_five_keys', array(
	'TROPHY_TITLE' 		=> $lang['top_five_1'],
	'GAMES_PLAYED'		=> $lang['top_five_2'],
	'MOST_COMMENTS'		=> $lang['top_five_3'],
	'CHALLENGERS'		=> $lang['top_five_4'],
	'GAMBLERS'			=> $lang['top_five_5'],
	'GAMBLE_WINNERS'	=> $lang['top_five_6'],
	'TOP_TITLE'			=> $lang['top_five_7'],
	'BOTTOM_TITLE'		=> $lang['top_five_8'],			
	'TITLE'				=> $board_config['sitename'] ."'". $lang['top_five_11'])
);
	
//
// Users Query To Be Used Many Times
//
$sql = "SELECT *
	FROM ". USERS_TABLE;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain users data.', '', __LINE__, __FILE__, $sql);
} 
$users_info = $db->sql_fetchrowset($result);
	

//
// Trophy Holders ...
// by Skullbone (http://rant-board.com/(skullbone67@lethalvapors.com))
//
$sql = "SELECT *   
	FROM " . USERS_TABLE . "
	WHERE user_trophies > 0   
	ORDER BY user_trophies DESC  
	LIMIT 0, 5";    
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain users data.', '', __LINE__, __FILE__, $sql);
} 

$i = 0;
while ($row = $db->sql_fetchrow($result))
{ 
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			
	$template->assign_block_vars('trophy', array(
		'ROW_CLASS'	=> $row_class,		
		'TROPHY_TOP_HOLDER' => ( $i + 1 ) . '. ' .  (( $row['user_id'] == ANONYMOUS ) ? $lang['top_five_12'] : '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '"  class="genmed">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>'),
		'AMOUNT' => '<a href="' . append_sid('activity.'.$phpEx.'?page=trophy_search&amp;user=' . $row['username']) . '">' . number_format($row['user_trophies']) . '</a>')			
	);
	$i++;
}
$db->sql_freeresult($result);


//
// Games Played ...
//		
$sql = "SELECT *   
	FROM ". iNA_GAMES ."   
	ORDER BY played DESC  
	LIMIT 0, 5";    
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain game data.', '', __LINE__, __FILE__, $sql);
} 

$i = 0;
while ($row = $db->sql_fetchrow($result))
{ 
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('played', array(
		'ROW_CLASS'	=> $row_class,		
		'TOP_GAMES' => ( $i + 1 ) . '. ' . GameArrayLink($row['game_id'], $row['game_parent'], $row['game_popup'], $row['win_width'], $row['win_height'], '3%SEP%'. $row['proper_name'], ''),
		'AMOUNT' => number_format($row['played']))
	);
	$i++;
}	
$db->sql_freeresult($result);
		

//
// Comment Leavers ...
//
$sql = "SELECT COUNT(player) AS total, player 
	FROM ". INA_TROPHY_COMMENTS ."
	GROUP BY player
	ORDER BY total DESC  
	LIMIT 0, 5";    
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain trophy comments data.', '', __LINE__, __FILE__, $sql);
} 

$i = 0;
while ($row = $db->sql_fetchrow($result))
{ 
	unset($user_id);
	
	for ($x = 0; $x < count($users_info); $x++)
	{
		if ($users_info[$x]['user_id'] == $row['player'])
		{
			$user_id = $users_info[$x]['user_id'];
			break;
		}
	}		
		
	unset($username);
	unset($user_level);
	
	for ($x = 0; $x < count($users_info); $x++)
	{
		if ($users_info[$x]['user_id'] == $user_id)
		{
			$username = $users_info[$x]['username'];
			$user_level = $users_info[$x]['user_level'];
			break;
		}
	}
		
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
				
	$template->assign_block_vars('comments', array(
		'ROW_CLASS'	=> $row_class,		
		'COMMENTS' => ( $i + 1) .'. ' . (( $user_id == ANONYMOUS ) ? $lang['top_five_12'] : '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '" class="genmed">' . username_level_color($username, $user_level, $user_id) . '</a>'),
		'AMOUNT' => number_format($row['total']))			
	);
	$i++;
}
$db->sql_freeresult($result);


//
// Challenge Senders
//		
//	WHERE user_from > 0 
//		AND user_to > 0
$sql = "SELECT SUM(count) AS total, user_from 
	FROM ". INA_CHALLENGE_USERS ."
	GROUP BY user_from
	ORDER BY total DESC  
	LIMIT 0, 5";    
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain challenge data.', '', __LINE__, __FILE__, $sql);
} 

$i = 0;
while ($row = $db->sql_fetchrow($result))
{ 
	unset($username);
	unset($user_level);
	
	for ($x = 0; $x < count($users_info); $x++)
	{
		if ($users_info[$x]['user_id'] == $row['user_from'])
		{
			$username = $users_info[$x]['username'];
			$user_level = $users_info[$x]['user_level'];
			break;
		}
	}
	
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			
	$template->assign_block_vars('challenge', array(
		'ROW_CLASS'	=> $row_class,		
		'CHALLENGES' => ( $i + 1 ) .'. ' . (( $row['user_from'] == ANONYMOUS ) ? $lang['top_five_12'] : '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_from']) . '" class="genmed">'. username_level_color($username, $user_level, $row['user_from']) . '</a>'),
		'AMOUNT' => number_format($row['total']))			
	);
	$i++;
}						
$db->sql_freeresult($result);

	
//
// Bet Makers ...
//	
$sql =  "SELECT COUNT(sender_id) AS total, sender_id 
	FROM ". INA_GAMBLE ."
	GROUP BY sender_id
	ORDER BY total DESC  
	LIMIT 0, 5";    
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain gamble data.', '', __LINE__, __FILE__, $sql);
} 

$i = 0;
while ($row = $db->sql_fetchrow($result))
{ 		
	unset($username);
	unset($user_level);
	
	for ($x = 0; $x < count($users_info); $x++)
	{
		if ($users_info[$x]['user_id'] == $row['sender_id'])
		{
			$username = $users_info[$x]['username'];
			$user_level = $users_info[$x]['user_level'];
			break;
		}
	}

	
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
			
	$template->assign_block_vars('bets', array(
		'ROW_CLASS'	=> $row_class,		
		'BETS' => ( $i + 1 ) . '. ' . (( $row['sender_id'] == ANONYMOUS ) ? $lang['top_five_12'] : '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['sender_id']) . '" class="genmed">' . username_level_color($username, $user_level, $row['sender_id']) . '</a>'),
		'AMOUNT' => number_format($row['total']))			
	);
	$i++;
}	
$db->sql_freeresult($result);
	
	
//
// Bet Winners ...
//	
$sql = "SELECT COUNT(winner_id) AS total, winner_id 
	FROM ". INA_GAMBLE ."
	WHERE winner_score > 0
		AND loser_score > 0
	GROUP BY winner_id
	ORDER BY total DESC  
	LIMIT 0, 5";    
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain gamble data.', '', __LINE__, __FILE__, $sql);
} 

$i = 0;
while ($row = $db->sql_fetchrow($result))
{
	unset($username);
	unset($user_level);
	
	for ($x = 0; $x < count($users_info); $x++)
	{
		if ($users_info[$x]['user_id'] == $row['winner_id'])
		{
			$username = $users_info[$x]['username'];
			$user_level = $users_info[$x]['user_level'];
			break;
		}
	}
	
	
	$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
				
	$template->assign_block_vars('bet_winners', array(
		'ROW_CLASS'	=> $row_class,		
		'WINNERS' => ( $i + 1 ) .'. ' . (( $row['winner_id'] == ANONYMOUS ) ? $lang['top_five_12'] : '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['winner_id']) . '"  class="genmed">' . username_level_color($username, $user_level, $row['winner_id']) . '</a>'),
		'AMOUNT' => number_format($row['total']))
	);
	$i++;
}			
$db->sql_freeresult($result);


//
// Games Top Scores ...
//
$sql = "SELECT distinct game_id, game_name, reverse_list, proper_name, game_parent, game_popup, win_width, win_height
	FROM ". iNA_GAMES ."
	GROUP BY game_id
	LIMIT $start, $finish";    
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain games data.', '', __LINE__, __FILE__, $sql);
} 

$nbcol = 3;
$error = '';
if ( !$row = $db->sql_fetchrow($result) ) 
{
	$error = TRUE;
}

while (!$error)
{
	$template -> assign_block_vars('one', array());
	
	for ($cg = 1 ; $cg <= $nbcol ; $cg++)
	{
    	$template->assign_block_vars('one.two', array());
		
		if (!$error)
		{			
			$template->assign_block_vars('one.two.games_name', array(
				'NAME' => GameArrayLink($row['game_id'], $row['game_parent'], $row['game_popup'], $row['win_width'], $row['win_height'], '3%SEP%'. $row['proper_name'], ''))
			);
											
			$order_type	= ( $row['reverse_list'] ) ? 'ASC' : 'DESC';
			$sql1 =  "SELECT *  
				FROM ". iNA_SCORES ."
		    	WHERE game_name = '". $row['game_name'] ."'
				GROUP BY player
				ORDER BY score $order_type
				LIMIT 0, 5"; 
			if ( !($result1 = $db->sql_query($sql1)) )
 			{
 				message_die(GENERAL_ERROR, 'Could not obtain ' . $row['game_name'] . ' score data.', '', __LINE__, __FILE__, $sql1); 
			}
				
			$i = 0;
			$pos = 0;
			$posreelle = 0;
			$lastscore = 0;
		    while ($row1 = $db->sql_fetchrow($result1))
            {	
				unset($user_id);
				unset($user_level);
				
				for ($x = 0; $x < count($users_info); $x++)
				{
					if ($users_info[$x]['username'] == $row1['player'])
					{
						$user_id = $users_info[$x]['user_id'];
						$user_level = $users_info[$x]['user_level'];
						break;
					}
				}
			
				$posreelle++;
				if ($lastscore != $row1['score'])
				{
					$pos = $posreelle;
				}		
				
				$lastscore = $row1['score'];
		
				$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];

				$template -> assign_block_vars('one.two.games_name.games', array(
					'ROW_CLASS'	=> $row_class,		
					'GAME'	=> ( $i + 1 ) . '. ' . (( $user_id == ANONYMOUS ) ? $lang['top_five_12'] : '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '"  class="genmed">' . username_level_color($row1['player'], $user_level, $user_id) . '</a>'),
					'SCORE'	=> FormatScores($row1['score']))
				);
				$i++;				
			}     
			$db->sql_freeresult($result2);

			$order_type = '';
			
	  		if ( !$row = $db->sql_fetchrow($result) ) 
			{
				$error = TRUE;
			}
		}		
	}
}
$db->sql_freeresult($result);
	
	
//
// Pagination ...
//
$sql = "SELECT count(game_id) AS total
	FROM " . iNA_GAMES . "
	WHERE game_id <> '0'";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, $lang['no_game_total'], '', __LINE__, __FILE__, $sql);
}
		
if ($total = $db->sql_fetchrow($result))
{
	$total_games = $total['total'];
	$pagination = generate_pagination("activity.$phpEx?page=top&mode=next_". $board_config['games_per_page'], $total_games, $board_config['games_per_page'], $start). '&nbsp;';
}

$template->assign_block_vars('pagination', array(				
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['games_per_page'] ) + 1 ), ceil( $total_games / $board_config['games_per_page'] )))
);

$template->pparse('body');

?>
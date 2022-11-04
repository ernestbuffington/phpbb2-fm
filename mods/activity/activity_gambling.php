<?php
/***************************************************************************
 *                             activity_gambling.php
 *                            ------------------------
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

if(($board_config['ina_disable_gamble_page']) && ($userdata['user_level'] != ADMIN)) 
{
	message_die(GENERAL_ERROR, $lang['disabled_page_error']);	
}
//
// End Restriction Checks 
//
	
$template->set_filenames(array(
	'body' => 'amod_files/activity_gambling_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

if($HTTP_GET_VARS['mode'] == 'stats')
{
	$template->assign_block_vars('links', array(
		"U_GAMBLING" => "-> <a href='activity.$phpEx?page=gambling' class='nav'>". $lang['gambling_link_2'] ."</a> ",
		"U_ACTIVITY" => "-> <a href='activity.$phpEx' class='nav'>". $lang['Activity'] ."</a> ")
	);					
	
	$template->assign_block_vars('stats', array(
		"L_TITLE_1"	=> $lang['gambling_stats_title_1'],
		"L_TITLE_2"	=> $lang['gambling_stats_title_2'],
		"L_TITLE_3"	=> $lang['gambling_stats_title_3'],
		"L_TITLE_4"	=> $lang['gambling_stats_title_4'],
		"L_TITLE_5"	=> $lang['gambling_stats_title_5'],
		"L_TITLE_6"	=> $lang['gambling_stats_title_6'])
	);
				
	$i = 0;	
	$q = "SELECT *
		FROM ". INA_GAMBLE ."
		WHERE winner_id > 1
			AND loser_id > 1
		ORDER BY date DESC";
	$r 	= $db->sql_query($q);
	
	while($row = $db->sql_fetchrow($r))
	{
		$game_id = $row['game_id'];
		$winner_id = $row['winner_id'];
		$winner_sc = $row['winner_score'];
		$loser_id = $row['loser_id'];
		$loser_sc = $row['loser_score'];
		$amount	= $row['amount'];
		$date = $row['date'];
				
		$row_class = (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];		
		
		$q1 = "SELECT game_name, proper_name
			FROM ". iNA_GAMES ."
			WHERE game_id = '". $game_id ."'";
		$r1	= $db->sql_query($q1);
		$row = $db->sql_fetchrow($r1);
		
		$game_name 	= $row['game_name'];
		$proper_name = $row['proper_name'];
		
		$q2 = "SELECT username
			FROM ". USERS_TABLE ."
			WHERE user_id = '". $winner_id ."'";
		$r2	= $db->sql_query($q2);
		$row = $db->sql_fetchrow($r2);
		
		$winner_name = $row['username'];
		
		$q3 = "SELECT username
			FROM ". USERS_TABLE ."
			WHERE user_id = '". $loser_id ."'";
		$r3	= $db->sql_query($q3);
		$row = $db->sql_fetchrow($r3);
		
		$loser_name = $row['username'];				
		
		$game_image = CheckGameImages($game_name, $proper_name);
		
		if($amount > 0) 
		{
			$amount_bet = number_format($amount) ." ". $board_config['points_name'];
		}
		if($amount < 1) 
		{
			$amount_bet = $lang['gambling_stats_for_fun'];
		}
		
		$template->assign_block_vars("stats_rows", array(
			"ROW_CLASS" => $row_class,
			"GAME_IMAGE" => $game_image,
			"GAME_NUMBER" => $i + 1,
			"WINNER_LINK" => "<a href='profile.$phpEx?mode=viewprofile&u=$winner_id' class='nav'>$winner_name</a><br>". FormatScores($winner_sc),
			"LOSER_LINK" => "<a href='profile.$phpEx?mode=viewprofile&u=$loser_id' class='nav'>$loser_name</a><br>". FormatScores($loser_sc),
			"AMOUNT" => $amount_bet,
			"DATE" => create_date($board_config['default_dateformat'], $date, $board_config['board_timezone']))
		);
		$i++;		
	}					
}
			
if($HTTP_GET_VARS['mode'] == 'denybet')
{
	$deny_id = $HTTP_GET_VARS['id'];
	$game_id = $HTTP_GET_VARS['game'];
	
	if($userdata['user_id'] != $deny_id)
	{
		message_die(GENERAL_ERROR, $lang['gambling_deny_error']);
	}
		
	$q = "SELECT *
		FROM ". INA_GAMBLE_GAMES ."
		WHERE reciever_id = '". $userdata['user_id'] ."'
			AND game_id = '". $game_id ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	
	$sender_id = $row['sender_id'];
	$game_id = $row['game_id'];
		
	$q = "SELECT game_name, proper_name
		FROM ". iNA_GAMES ."
		WHERE game_id = '". $game_id ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	
	$game = $row['game_name'];		
	$game2 = $row['proper_name'];		
		
	$q = "DELETE FROM ". INA_GAMBLE ."
		WHERE reciever_id = '". $userdata['user_id'] ."'
			AND game_id = '". $game_id ."'";
	$r = $db->sql_query($q);
			
	$q = "DELETE FROM ". INA_GAMBLE_GAMES ."
		WHERE reciever_id = '". $userdata['user_id'] ."'
			AND game_id = '". $game_id ."'";
	$r = $db->sql_query($q);
		
	$new_msg1 = str_replace("%u%", $userdata['username'], $lang['gambling_deny_bet_sub']);
	$new_msg2 = str_replace("%g%", $game2, $new_msg1);
	
	send_challenge_pm($sender_id, $new_msg2, $new_msg2);
	
	message_die(GENERAL_MESSAGE, $lang['gambling_bet_denied_msg']);			
}
			
if($HTTP_GET_VARS['mode'] == 'betting')
{
	$switch = $HTTP_GET_VARS['user'];
	
	if($switch == "sender")
	{
		$sender_id = $HTTP_GET_VARS['id'];
		$game_id = $HTTP_GET_VARS['game'];

		$q = "SELECT *
			FROM ". INA_GAMBLE ."
			WHERE sender_id = '". $sender_id ."'
				AND game_id = '". $game_id ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		
		$amount_bet = $row['amount'];
		
		if ($userdata['user_points'] < $amount_bet)
		{
			message_die(GENERAL_MESSAGE, $lang['not_enough_points']);
		}
					
		$q = "SELECT *
			FROM ". INA_GAMBLE_GAMES ."
			WHERE sender_id = '". $sender_id ."'
				AND game_id = '". $game_id ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		
		$sender_id = $row['sender_id'];
		$game_id = $row['game_id'];
		$sender_score = $row['sender_score'];
		
		if((!$sender_score) && ($sender_id))
		{
			$q = "UPDATE ". INA_GAMBLE_GAMES ."
				SET sender_playing = 1
				WHERE sender_id = '". $sender_id ."'
					AND game_id = '". $game_id ."'";
			$r = $db->sql_query($q);
				
			echo '<script language="JavaScript">self.location.href=\'activity.'. $phpEx .'?mode=game&id='. $game_id .'&parent=true\';</script>';
		}
		else
		{
			echo '<script language="JavaScript">self.location.href=\'activity.'. $phpEx .'\';</script>';										
		}
	}
	
	if($switch == 'receiver')
	{
		$receiver_id = $HTTP_GET_VARS['id'];
		$game_id = $HTTP_GET_VARS['game'];
			
		$q = "SELECT *
			FROM ". INA_GAMBLE ."
			WHERE reciever_id = '". $receiver_id ."'
				AND game_id = '". $game_id ."'";
		$r = $db->sql_query($q);
		$row = $db->sql_fetchrow($r);
		
		$amount_bet = $row['amount'];
		
		if ($userdata['user_points'] < $amount_bet)
		{
			message_die(GENERAL_MESSAGE, $lang['not_enough_points']);
		}

		$q = "SELECT *
			FROM ". INA_GAMBLE_GAMES ."
			WHERE reciever_id = '". $receiver_id ."'
				AND game_id = '". $game_id ."'";
		$r = $db->sql_query($q);
		$row  = $db->sql_fetchrow($r);
		
		$receiver_id = $row['reciever_id'];
		$game_id = $row['game_id'];
		$reciever_score = $row['reciever_score'];
		
		if((!$reciever_score) && ($receiver_id))
		{
			$q = "UPDATE ". INA_GAMBLE_GAMES ."
				SET reciever_playing = 1
				WHERE reciever_id = '". $receiver_id ."'
					AND game_id = '". $game_id ."'";
			$r = $db->sql_query($q);

			echo '<script language="JavaScript">self.location.href=\'activity.'. $phpEx .'?mode=game&id='. $game_id .'&parent=true\';</script>';
		}
		else
		{
				echo '<script language="JavaScript">self.location.href=\'activity.'. $phpEx .'\';</script>';										
		}							
	}
}		
		
if ($HTTP_POST_VARS['mode'] == 'submit_gamble')
{
	if (intval($HTTP_POST_VARS['user_option_one']) > 1)
	{
		$reciever_id = $HTTP_POST_VARS['user_option_one'];		
	}
	elseif ($HTTP_POST_VARS['user_option_two'])
	{
		$q = "SELECT user_id
			FROM ". USERS_TABLE ."
			WHERE username = '". addslashes(stripslashes($HTTP_POST_VARS['user_option_two'])) ."'";
		$r = $db->sql_query($q);
		$exists = $db->sql_fetchrow($r);
		
		if (!$exists['user_id'])
		{
			message_die(GENERAL_ERROR, $lang['No_such_user'], $lang['error']);
		}
		else
		{
			$reciever_id = $exists['user_id'];											
		}
	}
		
	$game_id = $HTTP_POST_VARS['game_selected'];
	$sender_id = $userdata['user_id'];
	$free_fee = $HTTP_POST_VARS['bet_selection'];
	$amount = round($HTTP_POST_VARS['bet_amount']);

	/* Start all the possible screw ups */
	if($userdata['user_id'] == ANONYMOUS || $userdata['user_id'] == '') 
	{
		redirect("activity.$phpEx", true);
	}
	if($amount > $board_config['ina_max_gamble']) 
	{
		message_die(GENERAL_ERROR, $lang['gambling_max_exceeded_error']);	
	}
	if($reciever_id == $sender_id) 
	{
		message_die(GENERAL_ERROR, $lang['gambling_bet_self']);
	}
	if(!$free_fee) 
	{
		message_die(GENERAL_ERROR, $lang['gambling_no_fee_error']);
	}
	if(!$game_id) 
	{
		message_die(GENERAL_ERROR, $lang['gambling_no_game_selected']);	
	}
	if(!$reciever_id) 
	{
		message_die(GENERAL_ERROR, $lang['gambling_no_user_selected']);	
	}
	if(!is_numeric($amount)) 
	{
		message_die(GENERAL_ERROR, str_replace("%u%", $userdata['username'], $lang['gambling_numerical_error']));
	}
	if(($free_fee == 2) && ($userdata['user_id'] == ANONYMOUS)) 
	{
		message_die(GENERAL_ERROR, $lang['gambling_anonymous_error']);
	}
	if(($free_fee == 2) && (!$amount)) 
	{
		message_die(GENERAL_ERROR, str_replace("%u%", $userdata['username'], $lang['gambling_no_bet_error']));
	}
	
	if($free_fee == 2)
	{
		if($userdata['user_points'] < $amount)
		{
			message_die(GENERAL_ERROR, str_replace("%u%", $userdata['username'], $lang['gambling_low_points']));
		}
	}
	/* End all the possible screw ups */		
			
	$q = "SELECT *
		FROM ". INA_GAMBLE_GAMES ."
		WHERE game_id = '". $game_id  ."'		  
			AND (sender_id = '". $userdata['user_id'] ."' 
				OR reciever_id = '". $userdata['user_id'] ."')
		  	AND (sender_score = '' 
				OR reciever_score = '')";
	$r = $db->sql_query($q);
	$row  = $db->sql_fetchrow($r);
	
	$exists = $row['sender_id'];
	
	if($exists) 
	{
		message_die(GENERAL_ERROR, $lang['gambling_in_progress_error']);
	}
	
	$q = "INSERT INTO ". INA_GAMBLE_GAMES ."
		VALUES ('". $game_id ."', '". $sender_id ."', '". $reciever_id ."', '', '', '', '')";
	$r = $db->sql_query($q);
	
	$q = "INSERT INTO ". INA_GAMBLE ."
		VALUES ('". $game_id ."', '". $sender_id ."', '". $reciever_id ."', '". $amount ."', '', '', '','','". time() ."', '')";
	$r = $db->sql_query($q);
	
	$q = "SELECT game_name, proper_name
	    FROM ". iNA_GAMES ."
		WHERE game_id = '". $game_id ."'";
	$r = $db->sql_query($q);
	$row = $db->sql_fetchrow($r);
	
	$game = $row['game_name'];
	$game2 = $row['proper_name'];
		
	if ( $free_fee == 1 ) 
	{
		$amount_bet = $lang['gambling_no_bet_pm'];
	}
	if ( $free_fee == 2 )
	{
		$amount_bet = $amount . $board_config['points_name'];
	}
	$senders_link = "activity.$phpEx?page=gambling&mode=betting&user=sender&id=$sender_id&game=$game_id ";
	$recievers_link_a = "activity.$phpEx?page=gambling&mode=betting&user=receiver&id=$reciever_id&game=$game_id ";
	$recievers_link_d = "activity.$phpEx?page=gambling&mode=denybet&user=receiver&id=$reciever_id&game=$game_id ";	
	$sender_message	= str_replace("%P%", "http://". $board_config['server_name'] . $board_config['script_path'] . $senders_link, $lang['gambling_pm_sender_msg']);	
	$reciever_message1 = str_replace("%D%", "http://". $board_config['server_name'] . $board_config['script_path'] . $recievers_link_d, $lang['gambling_pm_reciever_msg']);
	$reciever_message2 = str_replace("%A%", "http://". $board_config['server_name'] . $board_config['script_path'] . $recievers_link_a, $reciever_message1);
	$reciever_message3 = str_replace("%u%", $userdata['username'], $reciever_message2);
	$reciever_message4 = str_replace("%g%", $game2, $reciever_message3);
	$reciever_message = str_replace("%C%", $amount_bet, $reciever_message4);		
	
	send_challenge_pm($sender_id, $lang['gambling_pm_sender_sub'], $sender_message);
	send_challenge_pm($reciever_id, str_replace("%u%", $userdata['username'], $lang['gambling_pm_reciever_sub']), $reciever_message);
	
	message_die(GENERAL_MESSAGE, $lang['gambling_success_msg']);
}
		
if ($HTTP_GET_VARS['mode'] == '')
{
	$template->assign_block_vars('user_selection', array(															
		"L_USER_SELECTION_TITLE"	=> $lang['gambling_user_select_title'],
		"L_USER_SELECTION_DEFAULT"	=> $lang['gambling_default_user'],
		"L_TEXT_BOX_DEFAULT"		=> $lang['gambling_text_option_2'])
	);
					
	$template->assign_block_vars('links', array(
		"U_GAMBLING"	=> "-> <a href='activity.$phpEx?page=gambling' class='nav'>". $lang['gambling_link_2'] ."</a> ",
		"U_GAMBLING_2"	=> "-> <a href='activity.$phpEx?page=gambling&mode=stats' class='nav'>". $lang['gambling_link_3'] ."</a>",
		"U_ACTIVITY"	=> "-> <a href='activity.$phpEx' class='nav'>". $lang['Activity'] ."</a> ")
	);					
								
	$template->assign_block_vars('game_selection', array(															
		"L_GAME_RADIO"	=> $lang['gambling_game_choice'],
		"L_GAME_IMAGE"	=> $lang['gambling_game_image'],
		"L_GAME_DESC"	=> $lang['gambling_game_description'],
		"L_GAME_BET"	=> $lang['gambling_bet_amount'],
		"L_GAME_MAX"	=> $board_config['ina_max_gamble'])
	);
					
	$template->assign_block_vars('bet_selection', array(															
		"L_BET_TITLE"		=> $lang['gambling_bet_choices'],
		"L_BET_FOR_FUN" 	=> $lang['gambling_bet_choice_1'],
		"L_BET_FOR_FEE" 	=> $lang['gambling_bet_choice_2'],
		"L_BET_DESC"		=> $lang['gambling_bet_choice_desc'],
		"L_MAX_BET_DESC"	=> str_replace("%a%", $board_config['ina_max_gamble'] ." ". $board_config['points_name'], $lang['gambling_max_bet']),
		"L_GAME_SUBMIT"		=> $lang['gambling_select_button'],
		"L_POINTS_OFF"		=> $points_disabled,			
		"L_SUBMIT_TITLE"	=> $lang['gambling_submit_title'])
	);					
								
	$q = "SELECT user_id, username
		FROM " . USERS_TABLE . "
		WHERE user_id <> " . ANONYMOUS . "
		ORDER BY username";
	$r = $db->sql_query($q);
	
	while($row = $db->sql_fetchrow($r))
	{ 
		$id = $row['user_id'];
		$name = $row['username'];
	
		$template->assign_block_vars('user_selection_array', array(															
			"USER_ID" => $id,
			"USERNAME" => $name)
		);	
	}
	
	$i = 0;
	$admin_d = AdminDefaultOrder();
		
	$sql = "SELECT *
		FROM ". iNA_GAMES ."
		WHERE game_id > 1
			AND game_type <> 2
		ORDER BY $admin_d";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain games.', '', __LINE__, __FILE__, $sql);
	} 

	while($row = $db->sql_fetchrow($result))
	{ 
		$game_name = $row['game_name'];
		$game_id = $row['game_id'];
		$game_desc = $row['game_desc'];
		$game_prop = $row['proper_name'];	
		$game_image	= CheckGameImages($game_name, $game_prop);

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		$template->assign_block_vars('game_selection_rows', array(															
			"ROW_CLASS"	=> $row_class,
			"GAME_IMAGE" => $game_prop . '<br />' . $game_image,
			"GAME_DESC"	=> $game_desc,
			"GAME_ID" => $game_id,
			"GAME_NUMBERS" => $i + 1)
		);
		$i++;			
	}	
	$db->sql_freeresult($result);
}

$template->pparse('body');

?>
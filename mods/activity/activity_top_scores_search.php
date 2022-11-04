<?php
/***************************************************************************
 *                            activity_top_scores_search.php
 *                           --------------------------------
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

// Specific Page Disabled 			
if(($board_config['ina_disable_trophy_page']) && ($userdata['user_level'] != ADMIN)) 
{
	message_die(GENERAL_ERROR, $lang['disabled_page_error']);
}
//
// End Restriction Checks 
//
	
$search = $HTTP_GET_VARS['user'];	
if ($search)
{	
	$template->set_filenames(array(
		'body' => 'amod_files/activity_top_scores_search_body.tpl')
	);
	make_jumpbox('viewforum.'.$phpEx);

	$search = str_replace("%APOS%", "\'", $search);
	$search = stripslashes($search);
	$search = addslashes($search);
	
	if ($userdata['user_gender'] >= 0) 
	{
		$use_gender_mod = 1;
	}
		
	$q1 = "SELECT *
	   FROM ". USERS_TABLE ."
	   WHERE username = '". $search ."'";
	$r1			= $db->sql_query($q1);
	
	while($row 	= $db->sql_fetchrow($r1))
	{
		$username 			= $row['username'];
		$users_id			= $row['user_id'];
		$user_regdate 		= $row['user_regdate'];
		$user_posts	 		= $row['user_posts'];
		$user_level 		= $row['user_level'];
		$user_lastvisit		= $row['user_lastvisit'];
		$user_rank			= $row['user_rank'];

		if ($use_gender_mod == 1) 
		{
			$user_gender	= $row['user_gender'];
		}
		
		$q2 = "SELECT *
			FROM ". RANKS_TABLE ."";
		$r2		= $db->sql_query($q2);
		$row2 	= $db->sql_fetchrowset($r2);
	
		if ($user_rank != '0')
		{
			for ($x = 0; $x < count($row2); $x++)
			{
				if ( ($row2[$x]['rank_id'] == $user_rank) && ($row2[$x]['rank_special'] == '1') )
				{
					$user_rank_title 	= $row2[$x]['rank_title'];
					$user_rank_image 	= $row2[$x]['rank_image'];
					break;
				}
			}
		}
		else
		{
			for ($x = 0; $x < count($row2); $x++)
			{
				if ( ($row2[$x]['rank_min'] <= $user_posts) && ($row2[$x]['rank_special'] == '0') )
				{
					$user_rank_title 	= $row2[$x]['rank_title'];
					$user_rank_image 	= $row2[$x]['rank_image'];
				}
			}		
		}		
	
		$user_regdate 	= create_date($board_config['default_dateformat'], $user_regdate, $board_config['board_timezone']);
		$user_lastvisit = create_date($board_config['default_dateformat'], $user_lastvisit, $board_config['board_timezone']);
	
		if ($user_gender == 0) 
		{
			$user_gender = $lang['gender_none'] ;
		}
		if ($user_gender == 1) 	
		{
			$user_gender = $lang['gender_male'] ;
		}
		if ($user_gender == 2) 	
		{
			$user_gender = $lang['gender_female'] ;
		}
		if ($use_gender_mod <> 1)	
		{
			$user_gender = $lang['gender_not_installed'] ;
		}
		
		if ($user_level == 0) 
		{
			$user_level = $lang['level_member'] ;
		}
		if ($user_level == 1) 
		{
			$user_level = $lang['level_admin'] ;
		}
		if ($user_level == 2) 
		{
			$user_level = $lang['level_less_admin'] ;
		}
		if ($user_level == 3) 
		{
			$user_level = $lang['level_mod'] ;
		}
		
		$msn 		= ( $row['user_msnm'] ) ? '<a href="mailto: '. $row['user_msnm'] .'"><img src="'. $images['icon_msnm'] .'" alt="'. $lang['MSNM'] .'" title="'. $lang['MSNM'] .'" /></a>' : '';
		$yim 		= ( $row['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target='. $row['user_yim'] .'&amp;.src=pg"><img src="'. $images['icon_yim'] .'" alt="'. $lang['YIM'] .'" title="'. $lang['YIM'] .'" /></a>' : '';
		$aim 		= ( $row['user_aim'] ) ? '<a href="aim:goim?screenname='. $row['user_aim'] .'&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] .'" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" /></a>' : '';
		$icq 		= ( $row['user_icq'] ) ? '<a href="http://wwp.icq.com/scripts/contact.dll?msgto='. $row['user_icq'] .'"><img src="' . $images['icon_icq'] .'" alt="'. $lang['ICQ'] .'" title="' . $lang['ICQ'] .'" /></a>' : '';	   
		$www 		= ( $row['user_website'] ) ? '<a href="'. $row['user_website'] .'" target="_userwww"><img src="'. $images['icon_www'] . '" alt="'. $lang['Visit_website'] .'" title="'. $lang['Visit_website'] .'" /></a>' : '';
		$mailto 	= ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;". POST_USERS_URL .'='. $row['user_id']) : 'mailto:'. $row['user_email'];			
		$mail	 	= ( $row['user_email'] ) ? '<a href="'. $mailto .'"><img src="'. $images['icon_email'] .'" alt="'. $lang['Send_email'] .'" title="'. $lang['Send_email'] .'" /></a>' : '';
		$pmto	 	= append_sid("privmsg.$phpEx?mode=post&amp;". POST_USERS_URL ."=$row[user_id]");
		$pm 		= '<a href="'. $pmto .'"><img src="'. $images['icon_pm'] .'" alt="'. $lang['Send_private_message'] .'" title="'. $lang['Send_private_message'] .'" /></a>';
		$pro 		= append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL ."=$row[user_id]");
		$profile 	= '<a href="'. $pro .'"><img src="'. $images['icon_profile'] .'" alt="'. $lang['Profile'] .'" title="'. $lang['Profile'] .'" /></a>';
	}
		
	if (file_exists($user_rank_image)) 
	{
		$rank_image_link = '<br /><img src="'. $user_rank_image .'" alt="" title="" /><br />'. $user_rank_title;
	}
	
	if (!file_exists($user_rank_image)) 
	{
		$rank_image_link = '<br />'. $user_rank_title;	
	}
					
	$template->assign_block_vars('search_player', array(
		'L_LINK' 				=> $lang['trophy_holders'],		
		'U_LINK'				=> 'activity.'. $phpEx .'?page=trophy',
		'L_LINK_DESC'			=> " -> $search's ". $lang['game_profile'],
		'BUTTONS'				=> $msn . ' ' . $yim . ' ' . $aim . ' ' . $icq . ' ' . $mail . ' ' . $www . ' ' . $profile . ' ' . $pm,
		'TOP_ONE' 				=> $lang['join_date'] .'<br />( '. $lang['posts'] .' )',
		'TOP_TWO' 				=> $lang['last_visit'],
		'TOP_THREE' 			=> $lang['gender'],
		'TOP_FOUR' 				=> $lang['permissions'],
		'USERNAME' 				=> $username,
		'RANK_IMAGE' 			=> $rank_image_link,
		'BOTTOM_ONE' 			=> $user_regdate .'<br />( '. $user_posts .' )',
		'BOTTOM_TWO' 			=> $user_lastvisit,
		'BOTTOM_THREE' 			=> $user_gender,												
		'BOTTOM_FOUR' 			=> $user_level,												
		'HEADER_ONE' 			=> $lang['game'],												
		'HEADER_TWO' 			=> $lang['score_2'] .'<br />'. $lang['date_took'])
	);
		 	   	  	
	$i = 0;				
	$q = "SELECT *
		FROM ". $table_prefix ."ina_top_scores
		WHERE player = '". $users_id ."'";
	$r 			= $db -> sql_query($q);

	while($row 	= $db -> sql_fetchrow($r))
	{ 
		$score		= $row['score'];
		$game_name	= $row['game_name'];
		$who		= $username;
		$date		= $row['date'];
		$score 		= FormatScores($score);
		$date 		= create_date($board_config['default_dateformat'], $date, $board_config['board_timezone']);
	
		$q1 = "SELECT *
			FROM ". iNA_GAMES ."
		   	WHERE game_name = '". $game_name ."'";
		$r1		= $db -> sql_query($q1);
		$row1 	= $db -> sql_fetchrow($r1);
	
		$game_image	= '<center>'. $row1['proper_name'] .'</center><br>'. GameArrayLink($row1['game_id'], $row1['game_parent'], $row1['game_popup'], $row1['win_width'], $row1['win_height'], '3%SEP%'. CheckGameImages($game_name, $row1['proper_name']), '');
		
		$row_class 	= (!($i % 2)) ? $theme['td_class1'] : $theme['td_class2'];
		
		$template->assign_block_vars('search_player_games', array(
			'ROW_CLASS'		=> $row_class,		
			'GAMES' 		=> $game_image,		
			'SCORE_DATE' 	=> $score .'<br />'. $date)
		);				
		$i++;											
	}						
}	
		
$template->pparse('body');

?>
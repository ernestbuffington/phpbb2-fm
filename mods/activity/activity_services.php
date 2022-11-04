<?php
/***************************************************************************
 *                            activity_services.php
 *                           -----------------------
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
//
// End Restriction Checks 
//	

$template->set_filenames(array(
	'body' => 'amod_files/activity_services_body.tpl') 
);
make_jumpbox('viewforum.'.$phpEx);

$status_on 	= '<img src="' . $images['AJAXed_Check'] . '" height="16" width="16" alt="" title="" />';
$status_off	= '<img src="' . $images['AJAXed_X'] . '" height="16" width="16" alt="" title="" />';
	
if ($board_config['ina_default_order'] == 1) 
{
	$games_order = $lang['corder_gpA'];
}
else if ($board_config['ina_default_order'] == 2) 
{
	$games_order = $lang['corder_cpD'];
}
else if ($board_config['ina_default_order'] == 3) 
{
	$games_order = $lang['corder_na'];
}
else if ($board_config['ina_default_order'] == 4) 
{
	$games_order = $lang['corder_oa'];
}
else if ($board_config['ina_default_order'] == 5) 
{
	$games_order = $lang['corder_bA'];
}
else if ($board_config['ina_default_order'] == 6) 
{
	$games_order = $lang['corder_bD'];
}
else if ($board_config['ina_default_order'] == 7) 
{
	$games_order = $lang['corder_cA'];
}
else if ($board_config['ina_default_order'] == 8) 
{
	$games_order = $lang['corder_cD'];
}
else if ($board_config['ina_default_order'] == 9) 
{
	$games_order = $lang['corder_properA'];
}
else if ($board_config['ina_default_order'] == 10) 
{
	$games_order = $lang['corder_properD'];
}		

$template->assign_vars(array(
	'LEFT_TITLE'		=> $lang['services_service'],
	'RIGHT_TITLE'		=> $lang['services_service_status'],
	'POINTS_MOD'		=> $status_on,
	'CASH_MOD'			=> $status_off,
	'ALLOWANCE_MOD'		=> $status_off,
	'AUTO_DELETE'		=> ($board_config['ina_delete']) ? $status_on : $status_off,
	'CHALLENGE_SYSTEM'	=> ($board_config['ina_challenge']) ? $status_on : $status_off,
	'TROPHY_LOSS'		=> ($board_config['ina_pm_trophy']) ? $status_on : $status_off,
	'DAILY_GAME'		=> ($board_config['ina_use_daily_game']) ? $status_on : $status_off,
	'ONLINE_LIST'		=> ($board_config['ina_use_online']) ? $status_on : $status_off,
	'NEWEST_GAMES'		=> ($board_config['ina_use_newest']) ? $status_on : $status_off,
	'SHOUTBOX'			=> ($board_config['ina_use_shoutbox']) ? $status_on : $status_off,
	'TROPHY_TOPIC'		=> ($board_config['ina_show_view_topic']) ? $status_on : $status_off,
	'TROPHY_PROFILE'	=> ($board_config['ina_show_view_profile']) ? $status_on : $status_off,
	'TROPHY_KING'		=> ($board_config['ina_use_trophy']) ? $status_on : $status_off,
	'MEMBER_SUBMIT'		=> ($board_config['ina_disable_submit_scores_m']) ? $status_on : $status_off,
	'GUEST_PLAY'		=> ($board_config['ina_guest_play']) ? $status_on : $status_off,
	'GUEST_SUBMIT'		=> ($board_config['ina_disable_submit_scores_g']) ? $status_on : $status_off,
	'GUEST_FORCE'		=> ($board_config['ina_force_registration']) ? $status_on : $status_off,
		
	'L_POINTS_MOD'		=> $lang['services_points_mod'],
	'L_CASH_MOD'		=> $lang['services_cash_mod'],
	'L_ALLOWANCE_MOD'	=> $lang['services_allowance_mod'],
	'L_AUTO_DELETE'		=> $lang['services_auto_delete'],
	'L_CHALLENGE_SYSTEM'=> $lang['services_challenge_system'],
	'L_TROPHY_LOSS'		=> $lang['services_trophy_pm'],
	'L_DAILY_GAME'		=> $lang['services_daily_game'],
	'L_ONLINE_LIST'		=> $lang['services_online_list'],
	'L_NEWEST_GAMES'	=> $lang['services_newest_games'],
	'L_SHOUTBOX'		=> $lang['services_shoutbox'],
	'L_TROPHY_TOPIC'	=> $lang['services_trophy_topic'],
	'L_TROPHY_PROFILE'	=> $lang['services_trophy_profile'],
	'L_TROPHY_KING'		=> $lang['services_trophy_king'],
	'L_MEMBER_SUBMIT'	=> $lang['services_member_submit'],
	'L_GUEST_PLAY'		=> $lang['services_guest_play'],
	'L_GUEST_SUBMIT'	=> $lang['services_guest_submit'],
	'L_GUEST_FORCE'		=> $lang['services_guest_force'],
	
	'RATING'			=> ($board_config['ina_use_rating_reward']) ? $status_on : $status_off,
	'L_RATING'			=> $lang['services_rating'],		
	'L_RATING_2'		=> str_replace('%T%', number_format($board_config['ina_rating_reward']), $lang['services_rating_2']),
	
	'L_NEW_IMG'			=> $lang['services_new_game_img'],
	'NEW_IMG'			=> str_replace('%T%', number_format($board_config['ina_new_game_limit']), $lang['services_new_game_img_2']),
	'L_POP_IMG'			=> $lang['services_pop_game_img'],
	'POP_IMG'			=> str_replace('%T%', number_format($board_config['ina_pop_game_limit']), $lang['services_pop_game_img_2']),

	'GAMES_PER_DAY'		=> ($board_config['ina_use_max_games_per_day']) ? $status_on : $status_off,
	'L_GAMES_PER_DAY'	=> $lang['services_games_per_day'],
	'L_GAMES_PER_DAY_2'	=> str_replace('%T%', number_format($board_config['ina_max_games_per_day']), $lang['services_games_per_day_2']),
	
	'POST_BLOCK'		=> ($board_config['ina_post_block']) ? $status_on : $status_off,
	'L_POST_BLOCK'		=> $lang['services_post_count'],
	'L_POST_BLOCK_2'	=> str_replace('%T%', number_format($board_config['ina_post_block_count']), $lang['services_post_count_2']),
	
	'JOIN_BLOCK'		=> ($board_config['ina_join_block']) ? $status_on : $status_off,
	'L_JOIN_BLOCK'		=> $lang['services_member_length'],
	'L_JOIN_BLOCK_2'	=> str_replace('%T%', number_format($board_config['ina_join_block_count']), $lang['services_member_length_2']),
	
	'L_ORDER'			=> $lang['services_list_order'],
	'ORDER'				=> $games_order)
);
		
if ($board_config['ina_use_rating_reward'])
{
	$template->assign_block_vars('rating_on', array());
}
			
if ($board_config['ina_use_max_games_per_day'])
{
	$template->assign_block_vars('games_per_day', array());
}
			
if ($board_config['ina_post_block'])
{
	$template->assign_block_vars('post_block', array());
}
			
if ($board_config['ina_join_block'])
{
	$template->assign_block_vars('join_block', array());			
}

$template->pparse('body');

?>
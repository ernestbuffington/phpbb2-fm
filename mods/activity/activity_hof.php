<?php
/** 
*
* @package amod_files
* @version $Id: activity_hof.php,v 1.1.0 2005 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// Restriction Check 
BanCheck();				
		
$page_title = $lang['hof_page_title'];
$template->set_filenames(array(
	'body' => 'amod_files/activity_hof_body.tpl') 
);
make_jumpbox('viewforum.'.$phpEx);
							
$template->assign_vars(array(															
	'ONE'	=> $lang['hof_top_one'],
	'TWO'	=> $lang['hof_top_two'],
	'THREE'	=> $lang['hof_top_three'],
	'FOUR'	=> $lang['hof_top_four'])
);
								
$i = 0;
$begin = ($_GET['start']) ? $_GET['start'] : $HTTP_GET_VARS['start'];
$start = ($begin) ? $begin : 0;
$finish = $board_config['games_per_page'];
		
$sql = "SELECT *
	FROM " . USERS_TABLE;
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not obtain userdata.', '', __LINE__, __FILE__, $sql);
}
$r = $db->sql_query($sql);
$users_data	= $db->sql_fetchrowset($r);
$db->sql_freeresult($r);

$sql = "SELECT *
	FROM " . INA_HOF . "
	GROUP BY game_id
	LIMIT " . $start . ", " . $finish;
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not obtain hall of fame data.', '', __LINE__, __FILE__, $sql);
}
$r = $db->sql_query($sql);
$hof_data = $db->sql_fetchrowset($r);
$db->sql_freeresult($r);
	
$sql = "SELECT *
	FROM " . iNA_GAMES . "
	GROUP BY game_id";
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not obtain game data.', '', __LINE__, __FILE__, $sql);
}
$r = $db->sql_query($sql);
$game_data = $db->sql_fetchrowset($r);		
$db->sql_freeresult($r);

unset($proper_name, $game_name, $game_id, $old_user_id, $old_username, $old_score, $old_date, $new_user_id, $new_username, $new_score, $new_date, $game_image);

for ($a=0; $a < sizeof($hof_data); $a++)
{
	for ($b=0; $b < sizeof($game_data); $b++)
	{
		if ($hof_data[$a]['game_id'] == $game_data[$b]['game_id'])
		{
			$proper_name = $game_data[$b]['proper_name'];
			$game_name = $game_data[$b]['game_name'];
			$game_id = $game_data[$b]['game_id'];
			$game_parent = $game_data[$b]['game_parent'];
			$game_popup	= $game_data[$b]['game_popup'];
			$game_width	= $game_data[$b]['win_width'];
			$game_height = $game_data[$b]['win_height'];
			break;
		}
	}
	
	$old_score = $old_date = $old_user_id = $old_username = ''; 
					
	for ($c=0; $c < sizeof($users_data); $c++)
	{
		if ($hof_data[$a]['current_user_id'] == $users_data[$c]['user_id'])
		{
			$new_user_id = $users_data[$c]['user_id'];
			$new_username = $users_data[$c]['username'];
			$new_user_level = $users_data[$c]['user_level'];
			break;
		}
	}
			
	for ($d=0; $d < sizeof($users_data); $d++)
	{
		if ($hof_data[$a]['old_user_id'] == $users_data[$d]['user_id'])
		{
			$old_user_id = $users_data[$d]['user_id'];
			$old_username = $users_data[$d]['username'];
			$old_user_level = $users_data[$d]['user_level'];
			break;
		}
	}	
								
	$new_score = FormatScores($hof_data[$a]['current_score']);
	$new_date = create_date($board_config['default_dateformat'], $hof_data[$a]['date_today'], $board_config['board_timezone']);
	$old_score = FormatScores($hof_data[$a]['old_score']);
	$old_date = create_date($board_config['default_dateformat'], $hof_data[$a]['old_date'], $board_config['board_timezone']);
	$game_image = CheckGameImages($game_name, $proper_name);		
	
	if ( !$old_user_id ) 
	{
		$old_score = $old_date = $old_user_id = $old_username = '';
	}
		
	$block_var = ( !($i % 2) ) ? 'hof_left' : 'hof_right';
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];		
		
	$template->assign_block_vars($block_var, array(
		'ROW'	=> $row_class,
		'ONE'	=> '<b>' . $proper_name . '</b><br />' . GameArrayLink($game_id, $game_parent, $game_popup, $game_width, $game_height, '3%SEP%' . CheckGameImages($game_name, $proper_name), ''),
		'TWO'	=> ( $new_user_id != ANONYMOUS ) ? '<a href="' . append_sid('profile.'. $phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $new_user_id) . '" class="genmed">' . username_level_color($new_username, $new_user_level, $new_user_id) . '</a>' : $new_username,
		'THREE'	=> $new_score,
		'FOUR'	=> $new_date,
		'FIVE'	=> $lang['hof_page_previous'],
		'SIX'	=> ( $old_user_id != ANONYMOUS ) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $old_user_id) . '" class="genmed">' . username_level_color($old_username, $old_user_level, $old_user_id) . '</a>' : $old_username,
		'SEVEN'	=> $old_score,
		'EIGHT'	=> $old_date)
	);
	$i++;

	if (!$game_id) 
	{
		break;							
	}
}
		
$sql = "SELECT *
	FROM " . INA_HOF . "
	GROUP BY game_id";
if( !$result = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, 'Could not obtain total hall of fame data.', '', __LINE__, __FILE__, $sql);
}
$r = $db->sql_query($sql);
$hof_count = $db->sql_numrows($r);
$db->sql_freeresult($r);
		
$pagination = generate_pagination("activity.$phpEx?page=hof&amp;next", $hof_count, $board_config['games_per_page'], $start). '&nbsp;';
$page_number = sprintf($lang['Page_of'], ( floor($start / $board_config['games_per_page'] ) + 1 ), ceil($hof_count / $board_config['games_per_page'] ));		

$template->assign_vars(array(															
	'PAGE_1' => $page_number,
	'PAGE_2' => $pagination)
);		

$template->pparse('body');

?>
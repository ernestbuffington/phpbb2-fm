<?php
/***************************************************************************
 *                            admin_ina_scores_edit.php
 *                           ---------------------------
 *		Version			: 1.1.0
 *		Email			: austin@phpbb-amod.com
 *		Site			: http://phpbb-amod.com
 *		Copyright		: aUsTiN-Inc 2003/5 
 *
 ***************************************************************************/
 
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Games']['Score_Editor'] = $file;
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_activity.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_activity.' . $phpEx);

if( isset( $HTTP_POST_VARS['mode'] ) || isset( $HTTP_GET_VARS['mode'] ) )
{
	$mode = ( isset( $HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}

define('INA_CATEGORY', $prefix .'ina_categories');
define('INA_CATEGORY_DATA', $prefix .'ina_categories_data');
define('INA_CATEGORY_MAIN', $prefix .'ina_main_categories');
define('INA_TROPHY', $prefix .'ina_top_scores');		
$link = append_sid("admin_ina_scores_edit.". $phpEx);
	
echo $game_menu . "
</ul>
</div></td>
<td valign='top' width='78%'>

<h1>". $lang['admin_scores_1']."</h1><p>". $lang['admin_scores_2']."</p>";
		
if($mode == "main" || !$mode)
{
	echo "<table width='100%' class='forumline' cellspacing='1' align='center' cellpadding='4'><form name='edit_score' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead' colspan='2'>". $lang['admin_scores_3']."</th>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". $lang['admin_scores_4'].":</b></td>";
	echo "<td class='row2'><select name='game_selected'>";
	echo "<option selected value=''>". $lang['admin_scores_5'] ."</option>";
	
	$q = "SELECT game_name, proper_name
		FROM ". iNA_GAMES ."
		WHERE game_id > '0'
		ORDER BY proper_name ASC";
	$r			= $db -> sql_query($q);
	
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$game_name 	= $row['proper_name'];	
		$game_id	= $row['game_name'];
		echo "<option value='". $game_id ."'>". $game_name ."</option>";					
	}		
	
	echo "</select></td>";
	echo "</tr>";
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='edit_game_score'><input type='submit' class='mainoption' value='". $lang['admin_scores_6'] ."' onchange='document.edit_score.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
	echo "<br />";	
	
	echo "<table align='center' class='forumline' width='100%' cellspacing='1' cellpadding='4'><form name='edit_trophy' action='$link' method='post'>";
	echo "<tr>";
	echo "<th colspan='2' class='thHead'>". $lang['admin_scores_7']."</td>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". $lang['admin_scores_4'].":</b></td>";
	echo "<td class='row2'><select name='trophy_game_selected'>";
	echo "<option selected value=''>". $lang['admin_scores_5'] ."</option>";
	
	$q = "SELECT proper_name, game_name
		FROM ". iNA_GAMES ."
		WHERE game_id > '0'
		ORDER BY proper_name ASC";
	$r			= $db -> sql_query($q);
	
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$game_name 	= $row['proper_name'];	
		$game_name2	= $row['game_name'];
		echo "<option value='". $game_name2 ."'>". $game_name ."</option>";					
	}		
	
	echo "</select></td>";
	echo "</tr>";
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='edit_trophy_game_score'><input type='submit' class='mainoption' value='". $lang['admin_scores_8'] ."' onchange='document.edit_trophy.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
	echo "<br />";
	
	echo "<table align='center' class='forumline' width='100%' cellpadding='4' cellspacing='1'><form name='delete_players_scores' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead' colspan='2'>". $lang['admin_scores_20']."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". $lang['admin_scores_11'].":</b></td>";
	echo "<td class='row2'><select name='delete_id'>";
	echo "<option selected value=''>". $lang['admin_scores_12'] ."</option>";
	
	$q = "SELECT *
		FROM ". iNA_SCORES ."
		WHERE score > '0'
		GROUP BY player";
	$r			= $db -> sql_query($q);
	
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$play_name 	= $row['player'];
	
		echo "<option value='". $play_name ."'>". $play_name ."</option>";					
	}		
	
	echo "</select></td>";
	echo "</tr>";
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='delete_scores'><input type='submit' class='mainoption' value='". $lang['admin_scores_21'] ."' onchange='document.delete_players_scores.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
	echo "<br />";		
}
		
if($mode == "delete_scores")
{
	$who = $_POST['delete_id'];
				
	$q = "DELETE FROM ". iNA_SCORES ."
		WHERE player = '$who'";
	$r = $db -> sql_query($q);	
	
	message_die(GENERAL_MESSAGE, $lang['admin_scores_22'] . '<br /><br >');			
}
		
if($mode == "edit_game_score")
{
	$game = $_POST['game_selected'];
	
	if(!$game) 
	{
		message_die(GENERAL_ERROR, $lang['admin_scores_9'], $lang['admin_scores_10']);
	}
			
	echo "<table align='center' class='forumline' width='100%' cellpadding='4' cellspacing='1'><form name='edit_trophy_player' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead'>". $lang['admin_scores_11']."</th>";
	echo "</tr>";
	echo "<tr>";
	echo "<td align='center' class='row1'><select name='player_selected'>";
	echo "<option selected value=''>". $lang['admin_scores_12'] ."</option>";
	
	$q = "SELECT player
		FROM ". iNA_SCORES ."
		WHERE game_name = '$game'
		GROUP BY player";
	$r			= $db -> sql_query($q);
	
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$player_name = $row['player'];	
		echo "<option value='". $player_name ."'>". $player_name ."</option>";					
	}		
	
	echo "</select></td>";
	echo "</tr>";
	echo "<tr>";	
	echo "<td align='center' class='catBottom'><input type='hidden' name='mode' value='edit_player'><input type='hidden' name='game_selected' value='$game'><input type='submit' class='mainoption' value='". $lang['admin_scores_13'] ."' onchange='document.edit_trophy_player.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
}
		
if($mode == "edit_player")
{
	$player = $_POST['player_selected'];
	$game	= $_POST['game_selected'];

	$q = "SELECT *
		FROM ". iNA_SCORES ."
		WHERE game_name = '$game'
			AND player = '$player'
	  	GROUP BY player
	  	ORDER BY score ASC
	  	LIMIT 0, 1";
	$r		= $db -> sql_query($q);
	$row 	= $db -> sql_fetchrow($r);		
	
	$player_name 	= $row['player'];
	$player_score 	= $row['score'];
	$new_lang 		= str_replace("%p%", $player_name, $lang['admin_scores_14']);

	echo "<table align='center' cellpadding='4' cellspacing='1' class='forumline' width='100%'><form name='save_score' action='$link' method='post'>";
	echo "<tr>";
	echo "<th colspan='2' class='thHead'>" . $lang['Edit'] . "</th>";
	echo "<tr>";
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". str_replace("%g%", $game, $new_lang) . ":</b></td>";		
	echo "<td class='row2'>". number_format($player_score) . "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='row2'><b>". str_replace("%p%", $player_name, $lang['admin_scores_15']) . ":</b></td>";		
	echo "<td class='row2'><input type='text' name='new_score' value='$player_score' class='post' /></td>";
	echo "</tr>";					
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='save_new_score'><input type='hidden' name='game_selected' value='$game'><input type='hidden' name='player_selected' value='$player_name'><input type='submit' class='mainoption' value='". $lang['admin_scores_16'] ."' onchange='document.save_score.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";		
}
			
if($mode == "save_new_score")
{
	$game 	= $_POST['game_selected'];
	$player = $_POST['player_selected'];
	$score 	= $_POST['new_score'];

	$q = "SELECT *
		FROM ". INA_TROPHY ."
	  	WHERE game_name = '". $game ."'";
	$r		= $db -> sql_query($q);
	$row 	= $db -> sql_fetchrow($r);		
	
	$trophy_id = $row['player'];
	$trophy_sc = $row['score'];	

	$q = "SELECT user_id
		FROM ". USERS_TABLE ."
		WHERE username = '". $player ."'";
	$r		= $db -> sql_query($q);
	$row 	= $db -> sql_fetchrow($r);		
	
	$player_id = $row['user_id'];

	if($trophy_id == $player_id)
	{
		$q = "UPDATE ". iNA_SCORES ."
			SET score = '". $score ."' 
			WHERE player = '". $player ."'
				AND game_name = '". $game ."'";
		$r = $db -> sql_query($q);
	
		$q = "UPDATE ". INA_TROPHY ."
			SET score = '". $score ."' 
			WHERE player = '". $player_id ."'
				AND game_name = '". $game ."'";
		$r = $db -> sql_query($q);			
	}
	else
	{
		$q = "UPDATE ". iNA_SCORES ."
			SET score = '". $score ."' 
		  	WHERE player = '". $player ."'
		  		AND game_name = '". $game ."'";
		$r = $db -> sql_query($q);		
	}		
	
	message_die(GENERAL_MESSAGE, $player ."'". $lang['admin_scores_17'], $lang['admin_scores_18']);
}
	
if($mode == "edit_trophy_game_score")
{
	$game = $_POST['trophy_game_selected'];
	
	if(!$game) 
	{
		message_die(GENERAL_ERROR, $lang['admin_scores_19'], $lang['admin_scores_10']);	
	}
		
	$q = "SELECT *
		FROM ". INA_TROPHY ."
	  	WHERE game_name = '$game'";
	$r		= $db -> sql_query($q);
	$row 	= $db -> sql_fetchrow($r);		
	
	$player_score 	= $row['score'];
	$player_id		= $row['player'];
		
	$q = "SELECT username
		FROM ". USERS_TABLE ."
	  	WHERE user_id = '". $player_id ."'";
	$r		= $db -> sql_query($q);
	$row 	= $db -> sql_fetchrow($r);		
	
	$player = $row['username'];
	$new_lang 		= str_replace("%p%", $player, $lang['admin_scores_14']);
				
	echo "<table align='center' cellpadding='4' cellspacing='1' class='forumline' width='100%'><form name='save_score' action='$link' method='post'>";	
	echo "<tr>";
	echo "<th colspan='2' class='thHead'>" . $lang['Edit'] . "</th>";
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". str_replace("%g%", $game, $new_lang) .":</b></td>";		
	echo "<td class='row2'>". number_format($player_score) ."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='row1'><b>". str_replace("%p%", $player, $lang['admin_scores_15']) . ":</b></td>";		
	echo "<td class='row2'><input type='text' name='new_score' value='$player_score' class='post' /></td>";
	echo "</tr>";					
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='save_new_t_score'><input type='hidden' name='game_selected' value='$game'><input type='hidden' name='player_selected' value='$player'><input type='hidden' name='player_id_selected' value='$player_id'><input type='submit' class='mainoption' value='". $lang['admin_scores_16'] ."' onchange='document.save_score.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";		
}	
	
if($mode == "save_new_t_score")
{
	$game 	= $_POST['game_selected'];
	$player = $_POST['player_selected'];
	$score 	= $_POST['new_score'];
	$id		= $_POST['player_id_selected'];

	$q = "SELECT *
		FROM ". INA_TROPHY ."
		WHERE game_name = '". $game ."'";
	$r		= $db -> sql_query($q);
	$row 	= $db -> sql_fetchrow($r);		
	
	$trophy_id = $row['player'];
	$trophy_sc = $row['score'];	

	if($trophy_id == $id)
	{
		$q = "UPDATE ". iNA_SCORES ."
			SET score = '". $score ."' 
			WHERE player = '". $player ."'
				AND game_name = '". $game ."'";
		$r = $db -> sql_query($q);
		
		$q = "UPDATE ". INA_TROPHY ."
			SET score = '". $score ."' 
			WHERE player = '". $id ."'
				AND game_name = '". $game ."'";
		$r = $db -> sql_query($q);			
	}
	else
	{
		$q = "UPDATE ". iNA_SCORES ."
		 	SET score = '". $score ."' 
		 	WHERE player = '". $player ."'
		 		AND game_name = '". $game ."'";
		$r = $db -> sql_query($q);		
	}		
	
	message_die(GENERAL_MESSAGE, $player ."'". $lang['admin_scores_17'], $lang['admin_scores_18']);		
}
			
include('page_footer_admin.' . $phpEx);

?>
<?php	
/** 
*
* @package admin
* @version $Id: admin_ina_extras.php,v 1.1.0 2006/02/10 22:19:01 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Games']['Configuration_Xtras'] = $file;
	$module['Games']['Check_Games'] = $file . '?mode=check_games'; 	
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_activity.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_activity.' . $phpEx);

if (isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']))
{
	$mode = (isset($HTTP_POST_VARS['mode'])) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}


global $prefix, $board_config;

define("iNA_TROPHY", $prefix .'ina_top_scores');
define("iNA_GAMES", $prefix .'ina_games');
define("CONFIG_TABLE", $prefix .'config');		
$link = append_sid("admin_ina_xtras.". $phpEx);

if ($mode == "main" || !$mode)
{
	echo $game_menu . "
</ul>
</div></td>
<td valign='top' width='78%'>
	<h1>". $lang['Sync_attachments']."</h1><p>". $lang['admin_xtras_game_link_msg']."</p>";
	/* Deletion */
	echo "<table width='100%' class='forumline' cellspacing='1' cellpadding='4' align='center'><form name='adjust' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead' colspan='2'>". $lang['Sync_attachments']."</th>";
	echo "</tr>";		
		
	$on = ($board_config['ina_delete']) ? "checked='checked'" : "";
	$off = ($board_config['ina_delete']) ? "" : "checked='checked'";
				
	echo "<tr>";
	echo "<td width='50%' class='row1'><b>". $lang['auto_delete']."</b></td>";
	echo "<td class='row2'><input type='radio' name='select' value='1' $on> ". $lang['activate_radio_button'] . "&nbsp;&nbsp;<input type='radio' name='select' value='0' $off> ". $lang['deactivate_radio_button'] . "</td>";
	echo "</tr>";		
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='change'><input type='submit' class='mainoption' value='". $lang['apply_changes_button'] ."' onchange='document.adjust.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
	echo "<br />";
	
	echo "<table align='center' class='forumline' width='100%' cellpadding='4' cellspacing='1'>";
	echo "<tr>";
	echo "<th class='thHead' colspan='2'>". $lang['bug_fixers']."</th>";
	echo "</tr>";		
	
	/* Delete All Scores */	
	echo "<tr>";
	echo "<form name='truncate_scores' action='admin_activity.$phpEx?sid=". $userdata['session_id'] ."' method='post'>";	
	echo "<td class='row1'><b>". $lang['truncate_scores_table']."</b><br /><span class='gensmall'>". $lang['truncate_scores_table_e']."</span></td>";	
	echo "<td width='38%' class='row2'><input type='hidden' name='mode' value='clear_scores'><input type='submit' class='mainoption' value='". $lang['truncate_scores_table_s'] ."' onchange='document.truncate_scores.submit()' /></td>";
	echo "</form>";
	echo "</tr>";						
	
	/* Populate Hall of Fame */	
	echo "<tr>";
	echo "<form name='phof' action='$link' method='post'>";	
	echo "<td class='row1'><b>". $lang['hof_acp_title']."</td>";	
	echo "<td class='row2'><input type='hidden' name='mode' value='hof'><input type='submit' class='mainoption' value='". $lang['hof_acp_button'] ."' onchange='document.phof.submit()' /></td>";
	echo "</form>";	
	echo "</tr>";						
	
	/* Re-Sync */	
	echo "<tr>";
	echo "<form name='resync' action='$link' method='post'>";	
	echo "<td class='row1'><b>" . $lang['resync_button'] . "?</b><br /><span class='gensmall'>". $lang['resync_message']."</span></td>";	
	echo "<td class='row2'><input type='hidden' name='mode' value='re_sync'><input type='submit' class='mainoption' value='". $lang['resync_button'] ."' onchange='document.resync.submit()' /></td>";
	echo "</form>";
	echo "</tr>";						
	
	echo "<tr>";
	echo "<form name='fix_scores' action='$link' method='post'>";	
	echo "<td class='row1'><b>" . $lang['scores_update_button'] . "?</b><br /><span class='gensmall'>". $lang['scores_message']."</span></td>";	
	echo "<td class='row2'><input type='hidden' name='mode' value='scores_update'><input type='submit' class='mainoption' value='". $lang['scores_update_button'] ."' onchange='document.fix_scores.submit()' /></td>";
	echo "</form>";
	echo "</tr>";						
	
	echo "<tr>";
	echo "<form name='fix_trophies' action='$link' method='post'>";	
	echo "<td class='row1'><b>" . $lang['trophy_update_button'] . "?</b><br /><span class='gensmall'>". $lang['trophy_update_message']."</span></td>";	
	echo "<td class='row2'><input type='hidden' name='mode' value='trophies_update'><input type='submit' class='mainoption' value='". $lang['trophy_update_button'] ."' onchange='document.fix_trophies.submit()' /></td>";
	echo "</form>";
	echo "</tr>";						
	
	echo "<tr>";
	echo "<form name='fix_trophy_count' action='$link' method='post'>";	
	echo "<td class='row1'><b>" . $lang['reset_trophy_button'] . "?</b><br /><span class='gensmall'>". $lang['trophy_count_message']."</span></td>";	
	echo "<td class='row2'><input type='hidden' name='mode' value='trophy_count_fix'><input type='submit' class='mainoption' value='". $lang['reset_trophy_button'] ."' onchange='document.fix_trophy_count.submit()' /></td>";
	echo "</form>";	
	echo "</tr>";						
	
	echo "<tr>";
	echo "<form name='delete_all_comments' action='$link' method='post'>";	
	echo "<td class='row1'><b>" . $lang['delete_comments_message'] . "?</b><br /><span class='gensmall'>". $lang['delete_all_com_mess']."</span></td>";	
	echo "<td class='row2'><input type='hidden' name='mode' value='del_comments'><input type='submit' class='mainoption' value='". $lang['delete_comments_message'] ."' onchange='document.delete_all_comments.submit()' /></td>";
	echo "</form>";
	echo "</tr>";						
	
	echo "<tr>";
	echo "<form name='reset_all_jackpots' action='$link' method='post'>";	
	echo "<td class='row1'><b>". $lang['reset_jackpot_mess']."</td>";	
	echo "<td class='row2'><input type='hidden' name='mode' value='reset_jackpots'><input type='submit' class='mainoption' value='". $lang['reset_jackpot_button'] ."' onchange='document.reset_all_jackpots.submit()' /></td>";
	echo "</form>";
	echo "</tr>";						
	echo "</table>";		
}


if ( $mode == 'check_games')
{
   	$file = @file('http://phpbb-fm.com/updatecheck/games.txt');
   	if (!$file)
   	{
     	message_die(GENERAL_MESSAGE, $lang['acp_check_games_failed']);
    }

	echo $game_menu . '
		</ul></div></td>
		<td valign="top" width="78%">
			<h1>' . $lang['acp_check_games'] . '</h1>
			<p>' . $lang['acp_check_games_explain'] . ' ' . $lang['admin_xtras_game_link_msg'] . '</p>';
	  	            
    $match_array = $mis_match_array = $not_listed_array = array();

    $amod_list = $file[0];
    $new_list = explode(',', $amod_list);
   
    for ($x = 0; $x < sizeof($new_list); $x++)
    {
    	$q = "SELECT game_id
        	FROM ". iNA_GAMES ."
            WHERE game_name = '". $new_list[$x] ."'";
         $r = $db->sql_query($q);
         
         $exists = $db->sql_fetchrow($r);
     
        if ($exists['game_id'])
        {
            $match_array[] = $new_list[$x];
        }
        else
        {
           $not_listed_array[] = $new_list[$x];
        }
   }
         
	$q = "SELECT game_name
    	FROM ". iNA_GAMES;
    $r = $db->sql_query($q);
      
    $games = $db->sql_fetchrowset($r);
     
    for ($x = 0; $x < count($games); $x++)
    {
       	if ( !strstr($amod_list, $games[$x]['game_name'] .',') )
    	{
            $mis_match_array[] = $games[$x]['game_name'];
        }
   }         

	echo '<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">';
	echo '<tr>';
    echo '<th class="thHead">' . $lang['acp_check_match'] . ' [' . sizeof($match_array) . ']</th>';
    echo '</tr>';
    echo '<tr>';
   	echo '<td class="row1">';
	
	sort($match_array);
    if ( sizeof($match_array) > 0 )
    {
    	for ($x = 0; $x < sizeof($match_array); $x++)
        {
           echo $match_array[$x] . ', ';
    	}
   	}
	else
    {
    	echo $lang['None'];
   	}
   
	echo '</td>';
    echo '</tr>';         
    echo '</table>';
    echo '<br />';
    
	echo '<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">';
    echo '<tr>';
    echo '<th class="thHead">' . $lang['acp_check_not_listed'] . ' [' . sizeof($mis_match_array) . ']</th>';
    echo '</tr>';
    echo '<tr>';
   	echo '<td class="row1">';
      
	sort($mis_match_array);
    if ( sizeof($mis_match_array) > 0 )
    {
    	for ($x = 0; $x < sizeof($mis_match_array); $x++)
        {
            echo $mis_match_array[$x] . ', ';
        }
   	}
    else
    {
    	echo $lang['None'];
   	}
   
    echo '</td>';
    echo '</tr>';         
    echo '</table>';
    echo '<br />';
    
	echo '<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">';
    echo '<tr>';
    echo '<th class="thHead">' . $lang['acp_check_mis_match'] . ' [' . sizeof($not_listed_array) . ']</th>';
    echo '</tr>';
    echo '<tr>';
   	echo '<td class="row1">';
     
	sort($not_listed_array);
    if ( sizeof($not_listed_array) > 0 )
    {
    	for ($x = 0; $x < sizeof($not_listed_array); $x++)
        {
            echo $not_listed_array[$x] . ', ';
        }
   	}
    else
    {
    	echo $lang['None'];
   	}                 
      
    echo '</td>';
    echo '</tr>';         
    echo '</table>';      
} 
      		
if ($mode == 'hof')
{
	$q = "TRUNCATE ". $prefix ."ina_hall_of_fame";
	$db->sql_query($q);
	
	$q = "SELECT *
		FROM ". iNA_GAMES ."";
	$r 			= $db->sql_query($q);
	$game_info 	= $db->sql_fetchrowset($r);
	
	$q = "SELECT *
		FROM ". $prefix ."ina_top_scores";
	$r 			= $db->sql_query($q);
	$score_info = $db->sql_fetchrowset($r);	
	
	$adjusted = 0;
	unset($game_id, $hof_u, $hof_g, $hof_d, $hof_s);
	for ($a = 0; $a <= count($score_info); $a++)
	{
		for ($b = 0; $b <= count($game_info); $b++)
		{
			if ($score_info[$a]['game_name'] == $game_info[$b]['game_name'])
			{
				$game_id = $game_info[$b]['game_id'];
			}
		}
		$hof_u = $score_info[$a]['player'];
		$hof_d = $score_info[$a]['date'];
		$hof_s = $score_info[$a]['score'];
		$hof_g = $game_id;
			
		if ($hof_g > '0')
		{
			$q = "INSERT INTO ". $prefix ."ina_hall_of_fame
				VALUES ('". $hof_g ."', '". $hof_u ."', '". $hof_s ."', '". $hof_d ."', '', '', '')";
			$db->sql_query($q);
		}
				
		$adjusted++;
		if (!$hof_g) 
		{
			break;
		}
	}
	
	message_die(GENERAL_MESSAGE, str_replace('%X%', $adjusted - 1, $lang['hof_finished']), $lang['success']);
}
	
if($mode == "trophy_count_fix")
{
	$sql = "UPDATE ". USERS_TABLE ."
		SET user_trophies = '0'
		WHERE user_trophies > '0'";
	$r = $db->sql_query($sql);
	
	message_die(GENERAL_MESSAGE, $lang['all_trophy_reset'], $lang['success_message']);					
}
		
if($mode == "scores_update")
{
	$sql = "CREATE TABLE ". $prefix ."scores_fixer (`game_name` varchar(255) default NULL, `player` varchar(40) default NULL, `score` FLOAT(10,2) DEFAULT '0' NOT NULL, `date` int(11) default NULL )";
	$r = $db->sql_query($sql);
	
	$f = 0;
										
	$q = "SELECT *
		FROM ". iNA_SCORES ."
		GROUP BY player, game_name";
	$r 			= $db->sql_query($q);
	
	while($row 	= $db->sql_fetchrow($r))
	{
		$game_name 	= $row['game_name'];
		$score 		= $row['score'];
		$player 	= $row['player'];
		$date 		= $row['date'];
			
		$q3 = "INSERT INTO ". $prefix ."scores_fixer
			VALUES ('$game_name', '". str_replace("\'", "''", $player) ."', '$score', '$date')";
		$r3 = $db->sql_query($q3);
	
		$f++;			
	}

	$q = "TRUNCATE ". iNA_SCORES;
	$r = $db->sql_query($q);

	$f = 0;
										
	$q = "SELECT *
		FROM ". $prefix ."scores_fixer";
	$r 			= $db->sql_query($q);
	
	while($row 	= $db->sql_fetchrow($r))
	{
		$game_name 	= $row['game_name'];
		$score 		= $row['score'];
		$player 	= $row['player'];
		$date 		= $row['date'];
			
		$q3 = "INSERT INTO ". iNA_SCORES ."
			VALUES ('$game_name', '". str_replace("\'", "''", $player) ."', '$score', '$date')";
		$r3 = $db->sql_query($q3);
	
		$f++;			
	}

	$q = "DROP TABLE ". $prefix ."scores_fixer";
	$r = $db->sql_query($q);

	message_die(GENERAL_MESSAGE, $f . $lang['scores_updated'], $lang['success_message']);		
}
	
if($mode == "del_comments")
{
	$q = "TRUNCATE ". $prefix ."ina_trophy_comments";
	$r = $db->sql_query($q);
	
	message_die(GENERAL_MESSAGE, $lang['all_comments_deleted'], $lang['success_message']);		
}
	
if($mode == "reset_jackpots")
{
	$q = "UPDATE ". iNA_GAMES ."
		SET jackpot = '". $board_config['ina_jackpot_pool'] ."'
		WHERE jackpot <> '". $board_config['ina_jackpot_pool'] ."'";
	$db->sql_query($q);
	
	message_die(GENERAL_MESSAGE, $lang['reset_jackpot_success'], $lang['success_message']);		
}
	
if($mode == "trophies_update")
{
	$i = 0;							
	$q =  "SELECT *
		FROM ". iNA_GAMES ."
		GROUP BY game_id"; 
	$r  		= $db->sql_query($q);
	
	while($row	= $db->sql_fetchrow($r))
	{
		$games_name 	= $row['game_name'];
		$games_order 	= $row['reverse_list'];
		
		if ($games_order)
		{
			$min_max = 'MIN';
		}
		else
		{
			$min_max = 'MAX';
		}
		
		$q1 =  "SELECT $min_max(score) AS highest
			FROM ". iNA_SCORES ."
			WHERE game_name = '$games_name'"; 
		$r1 		= $db->sql_query($q1); 
		$row1		= $db->sql_fetchrow($r1);		
		
		$score_pass	= $row1['highest'];
				
		$q2 =  "SELECT *
			FROM ". iNA_SCORES ."
			WHERE game_name = '$games_name'
				AND score = '$score_pass'
			ORDER BY date DESC
			LIMIT 0, 1"; 
		$r2 	= $db->sql_query($q2); 
		$row2	= $db->sql_fetchrow($r2);
		
		$who 	= $row2['player'];
		$date	= $row2['date'];
						
		$q3 =  "SELECT user_id
			FROM ". USERS_TABLE ."
			WHERE username = '$who'"; 
		$r3 	= $db->sql_query($q3); 
		$row3	= $db->sql_fetchrow($r3);
		
		$who_id	= $row3['user_id'];
		
		$q5 =  "UPDATE ". iNA_TROPHY ."
			SET player = '$who_id', score = '$score_pass', date = '$date'
			WHERE game_name = '$games_name'"; 
		$r5	= $db->sql_query($q5);
		$i++;					
	}
	
	message_die(GENERAL_MESSAGE, $lang['trophy_tab_updated']. $i, $lang['success_message']);		
}
					
if ($mode == "re_sync")
{
	$i = 0;
	$q = "SELECT *
		FROM ". iNA_GAMES ."
		WHERE game_id <> '0'";
	$r = $db->sql_query($q);
	
	while ($row = $db->sql_fetchrow($r))
	{
		$q1 = "SELECT *
			FROM ". iNA_TROPHY ."
			WHERE game_name = '". $row['game_name'] ."'";
		$r1		= $db->sql_query($q1);
		$row1 	= $db->sql_fetchrow($r1);
			
		if (!$row1['game_name'])
		{
			$q2 = "INSERT INTO ". iNA_TROPHY ."
				VALUES ('". $row['game_name'] ."', '". $userdata['user_id'] ."', '1', '". time() ."')";
			$db->sql_query($q2);				
		}	
		$i++;			
	}	
	
	message_die(GENERAL_MESSAGE, $lang['tables_updated'] . $i . $lang['games_fixed'], $lang['success_message']);					
}
			
if ($mode == "change")
{
	$to_do	= $HTTP_POST_VARS['select'];
		
	$q = "UPDATE ". CONFIG_TABLE ."
		SET config_value = '$to_do'
		WHERE config_name = 'ina_delete'";
	$db->sql_query($q);
					
	if ($to_do == "1")
	{
		message_die(GENERAL_MESSAGE, $lang['auto_del_1'] . $link . $lang['auto_del_2']);
	}
	elseif ($to_do == "0")
	{
		message_die(GENERAL_MESSAGE, $lang['auto_del_3'] . $link . $lang['auto_del_2'], $lang['success_message']);
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['error_saving'] . $link . $lang['auto_del_2'], $lang['error_message']);
	}
}	
			
include('page_footer_admin.' . $phpEx);

?>
<?php		                              						   			  
/***************************************************************************
 *                            admin_ina_mass.php
 *                            -------------------
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
	$module['Games']['Mass_Change'] = $file;
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

if (!$mode)
{
	echo $game_menu . '
</ul>
</div></td>
<td valign="top" width="78%">
	<h1>'. $lang['mass_change_title1'] . '</h1><p>'.$lang['mass_change_title'].'</p>';
	echo '<table cellpadding="4" cellspacing="1" align="center" width="100%" class="forumline"><form name="save_mass" method="post" action="admin_ina_mass.'. $phpEx .'?mode=save&sid='. $userdata['session_id'] .'">';
	echo '<tr>';
	echo '<th class="thHead" colspan="2">'. $lang['mass_change_title1'] . '</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1" width="50%"><b>'.$lang['mass_change_cost'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="" name="new_cost" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_ge_cost'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="" name="new_ge_cost" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_path'].'</td>';
	echo '<td class="row2"><input type="text" class="post" value="" name="new_path" size="20" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_jackpot'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="" name="new_jackpot" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_bonus'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="" name="new_bonus" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_reward'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="" name="new_reward" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_parent_1'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="parent_on"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_parent_2'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="parent_off"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_popup_1'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="popup_on"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_popup_2'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="popup_off"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_links_1'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="remove_links"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_links_2'].'</td>';
	echo '<td class="row2"><input type="text" class="post" value="" name="new_links" size="20" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_cats'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="remove_cats"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_height'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="" name="new_height" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_width'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="" name="new_width" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_highscores'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="" name="new_highscores" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_desc'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="remove_desc"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_info'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="remove_inst"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_disable_1'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="hide_games"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_disable_2'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="show_games"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_scores_1'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="hide_scores"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['mass_change_scores_2'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="show_scores"></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td align="center" colspan="2" class="catBottom"><input type="submit" value="'. $lang['Submit'] .'" class="mainoption" onclick="document.save_mass.submit()" /></td>';
	echo '</tr>';
	echo '</form></table>';
}
		
if ($mode == 'save')
{
	$change_cost 		= ($_POST['new_cost']) ? $_POST['new_cost'] : $HTTP_POST_VARS['new_cost'];
	$change_ge_cost 	= ($_POST['new_ge_cost']) ? $_POST['new_ge_cost'] : $HTTP_POST_VARS['new_ge_cost'];
	$change_path 		= ($_POST['new_path']) ? $_POST['new_path'] : $HTTP_POST_VARS['new_path'];
	$change_jackpot 	= ($_POST['new_jackpot']) ? $_POST['new_jackpot'] : $HTTP_POST_VARS['new_jackpot'];
	$change_bonus 		= ($_POST['new_bonus']) ? $_POST['new_bonus'] : $HTTP_POST_VARS['new_bonus'];	
	$change_reward 		= ($_POST['new_reward']) ? $_POST['new_reward'] : $HTTP_POST_VARS['new_reward'];
	$allow_parent 		= ($_POST['parent_on']) ? $_POST['parent_on'] : $HTTP_POST_VARS['parent_on'];
	$disallow_parent 	= ($_POST['parent_off']) ? $_POST['parent_off'] : $HTTP_POST_VARS['parent_off'];	
	$allow_popup 		= ($_POST['popup_on']) ? $_POST['popup_on'] : $HTTP_POST_VARS['popup_on'];
	$disallow_popup 	= ($_POST['popup_off']) ? $_POST['popup_off'] : $HTTP_POST_VARS['popup_off'];
	$delete_links 		= ($_POST['remove_links']) ? $_POST['remove_links'] : $HTTP_POST_VARS['remove_links'];
	$add_links 			= ($_POST['new_links']) ? $_POST['new_links'] : $HTTP_POST_VARS['new_links'];	
	$remove_cats 		= ($_POST['remove_cats']) ? $_POST['remove_cats'] : $HTTP_POST_VARS['remove_cats'];	
	$change_height 		= ($_POST['new_height']) ? $_POST['new_height'] : $HTTP_POST_VARS['new_height'];
	$change_width 		= ($_POST['new_width']) ? $_POST['new_width'] : $HTTP_POST_VARS['new_width'];
	$change_highscores 	= ($_POST['new_highscores']) ? $_POST['new_highscores'] : $HTTP_POST_VARS['new_highscores'];
	$change_desc 		= ($_POST['remove_desc']) ? $_POST['remove_desc'] : $HTTP_POST_VARS['remove_desc'];
	$change_info 		= ($_POST['remove_inst']) ? $_POST['remove_inst'] : $HTTP_POST_VARS['remove_inst'];
	$hide_games 		= ($_POST['hide_games']) ? $_POST['hide_games'] : $HTTP_POST_VARS['hide_games'];
	$show_games 		= ($_POST['show_games']) ? $_POST['show_games'] : $HTTP_POST_VARS['show_games'];
	$hide_scores 		= ($_POST['hide_scores']) ? $_POST['hide_scores'] : $HTTP_POST_VARS['hide_scores'];	
	$show_scores 		= ($_POST['show_scores']) ? $_POST['show_scores'] : $HTTP_POST_VARS['show_scores'];	
	

	if ($change_path)
	{
		$q = "SELECT game_id, game_name
			FROM ". iNA_GAMES ."";
		$r 		= $db->sql_query($q);
		$row 	= $db->sql_fetchrowset($r);
		
		for ($x = 0; $x < count($row); $x++)
		{
			$new_path = $change_path .'/'. $row[$x]['game_name'] .'/';
			$q = "UPDATE ". iNA_GAMES ."
				SET game_path = '$new_path'
				WHERE game_id = '". $row[$x]['game_id'] ."'";
			$db->sql_query($q);
			
			if (!$row[$x]['game_id'])
			{
				break;	
			}
		}
	}
			
	if ($add_links)
	{
		$q = "SELECT game_id, game_links
			FROM ". iNA_GAMES ."";
		$r 		= $db->sql_query($q);
		$row 	= $db->sql_fetchrowset($r);
		
		for ($x = 0; $x < count($row); $x++)
		{
			$new_links = $row[$x]['game_links'];
			$new_links .= $add_links;
			
			$q = "UPDATE ". iNA_GAMES ."
				SET game_links = '$new_links'
				WHERE game_id = '". $row[$x]['game_id'] ."'";
			$db->sql_query($q);
			
			if (!$row[$x]['game_id'])
			{
				break;	
			}
		}
	}			
			
	$set = '';
	$msg = '';
	$set = array();
	
	if ( (intval($change_cost) >= 0) && (!empty($change_cost) || $change_cost == '0') )
	{
		$set[] = "game_charge = '$change_cost'";
	}
			
	if ( (intval($change_ge_cost) >= 0) && (!empty($change_ge_cost) || $change_ge_cost == '0') )
	{
		$set[] = "game_ge_cost = '$change_ge_cost'";
	}
			
	if ( (intval($change_jackpot) >= 0) && (!empty($change_jackpot) || $change_jackpot == '0') )
	{
		$set[] = "jackpot = '$change_jackpot'";
	}
	
	if ( (intval($change_bonus) >= 0) && (!empty($change_bonus) || $change_bonus == '0') )
	{
		$set[] = "game_bonus = '$change_bonus'";
	}
	
	if ( (intval($change_reward) >= 0) && (!empty($change_reward) || $change_reward == '0') )
	{
		$set[] = "game_reward = '$change_reward'";
	}
		
	if ( ($allow_parent == 'on') && (!$disallow_parent) )
	{
		$set[] = "game_parent = '1'";
	}
			
	if ( ($disallow_parent == 'on') && (!$allow_parent) )
	{
		$set[] = "game_parent = '0'";
	}
							
	if ( ($allow_popup == 'on') && (!$disallow_popup) )
	{
		$set[] = "game_popup = '1'";
	}
			
	if ( ($disallow_popup == 'on') && (!$allow_popup) )
	{
		$set[] = "game_popup = '0'";
	}
										
	if ($delete_links == 'on')
	{
		$set[] = "game_links = ''";
	}
										
	if ($remove_cats == 'on')
	{
		$set[] = "cat_id = ''";
	}
	
	if ( (intval($change_width) >= 0) && (!empty($change_width) || $change_width == '0') )
	{
		$set[] = "win_width = '$change_width'";			
	}
	
	if ( (intval($change_height) >= 0) && (!empty($change_height) || $change_height == '0') )
	{
		$set[] = "win_height = '$change_height'";			
	}
			
	if ( (intval($change_highscores) >= 0) && (!empty($change_highscores) || $change_highscores == '0') )
	{
		$set[] = "highscore_limit = '$change_highscores'";			
	}
				
	if ($change_desc == 'on')
	{
		$set[] = "game_desc = ''";
	}
			
	if ($change_info == 'on')
	{
		$set[] = "instructions = ''";
	}
			
	if ( ($hide_games == 'on') && (!$show_games) )
	{
		$set[] = "disabled = '0'";
	}
			
	if ( ($show_games == 'on') && (!$hide_games) )
	{
		$set[] = "disabled = '1'";
	}
				
	if ( ($hide_scores == 'on') && (!$show_scores) )
	{
		$set[] = "game_show_score = '0'";
	}
			
	if ( ($show_scores == 'on') && (!$hide_scores) )
	{
		$set[] = "game_show_score = '1'";
	}
																																		
	for ($x = 0; $x < count($set); $x++)
	{
		$update_sql .= $set[$x] . ((($x + 1) < count($set)) ? ', ' : '');
	}
	
	$q = "UPDATE ". iNA_GAMES ."
		SET $update_sql
		WHERE game_id > '0'";
	$db->sql_query($q);
		
	message_die(GENERAL_MESSAGE, sprintf($lang['mass_settings_complete'], '<a href="'. $_SERVER['PHP_SELF'] .'?sid='. $userdata['session_id'] .'">', '</a>'));
}
		
include('page_footer_admin.' . $phpEx);
?>
<?php
/** 
*
* @package admin
* @version $Id: admin_activity.php,v 1.1.0 2006/02/10 22:19:01 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', TRUE);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['Games']['Configuration']	= $file;
	$module['Games']['Add_new'] = $file . "?mode=add_game";
	$module['Games']['Management'] 	= $file . "?mode=edit_games";
	return;
}

$phpbb_root_path = '../';
require($phpbb_root_path .'extension.inc');
require('pagestart.'. $phpEx);
include_once($phpbb_root_path .'includes/functions_amod_plus.'. $phpEx);

$action = (isset($HTTP_GET_VARS['action'])) ? htmlspecialchars($HTTP_GET_VARS['action']) : htmlspecialchars($HTTP_POST_VARS['action']);
$mode 	= (isset($HTTP_GET_VARS['mode'])) ? htmlspecialchars($HTTP_GET_VARS['mode']) : htmlspecialchars($HTTP_POST_VARS['mode']);

if ($mode == 'add_game')
{
	$template->set_filenames(array(
		'body' => 'admin/activity_game_add_body.tpl') 
	);

	$money_name = $board_config['points_name'];
	
	$q2 =  "SELECT *
		FROM ". $table_prefix ."ina_categories
		WHERE cat_id > 0
		GROUP BY cat_name
		ORDER BY cat_name ASC"; 
	$r2 		= $db -> sql_query($q2); 
	
	while($row	= $db -> sql_fetchrow($r2))
	{
		$cat_n 	= $row['cat_name'];
		$cat_id	= $row['cat_id'];
					
		$template->assign_block_vars('cat', array(
			"C_SELECT_1"			=> $cat_id,
			"C_SELECT_2"			=> $cat_n)
		);			
	}

	$game_dir = $phpbb_root_path . $board_config['ina_default_g_path'];
	$games = @opendir($game_dir);
	$g = 0;
	
	while ($file = @readdir($games)) 
	{			
		if (($file != ".") && ($file != "..") && ($file != "index.htm") && ($file != "index.html") && ($file != "Thumbs.db"))
		{									
			$q29 = "SELECT game_name
				FROM ". iNA_GAMES ."
				WHERE game_name = '$file'";
			$r29 = $db -> sql_query($q29);
							
			if (!mysql_fetch_row($r29))
			{
				$template->assign_block_vars('drop', array(
					"D_SELECT"		=> $file)
				);		
				$g++;
			}	
		}
	}	
	
	if (!$g)
	{
		$default = $lang['admin_default_no_games'];
	}
	
	if ($g == 1)
	{
		$default = $lang['admin_default_1_game'];
	}
	
	if ($g > 1)
	{
		$default = sprintf($lang['admin_default_multi_games'], $g);
	}
	
	$default_max_cost = $board_config['ina_default_charge'];
	$increment_value = $board_config['ina_default_increment'];	
	$inc_divide = $default_max_cost / $increment_value;
					
	$i = 0;
	$inc = $increment_value;
									
	while ( ($i < $inc_divide) && ($inc < $default_max_cost) )
	{
		$inc = $inc + $increment_value;
		$template->assign_block_vars('charge', array(
			'D_SELECT' => $inc)
		);				
		$i++;
	}		
	
	$default_max_bonus = $board_config['ina_default_g_reward'];
	$increment_value2 = $board_config['ina_default_increment'];	
	$inc_divide2 = $default_max_bonus / $increment_value2;
					
	$i2 = 0;
	$inc2 = $increment_value2;
									
	while ( ($i2 < $inc_divide2) && ($inc2 < $default_max_bonus) )
	{
		$inc2 = $inc2 + $increment_value2;
		$template->assign_block_vars('bonus', array(
			'D_SELECT' => $inc2)
		);				
		$i2++;
	}
		
	$game_type_box = '';
	$game_type_box .= '<select name="game_type">';
	$game_type_box .= '<option value="1">'. $lang['game_type_one'] .'</option>';
	$game_type_box .= '<option value="2">'. $lang['game_type_two'] .'</option>';
	$game_type_box .= '<option value="3">'. $lang['game_type_three'] .'</option>';
	$game_type_box .= '<option value="4">'. $lang['game_type_four'] .'</option>';	
	$game_type_box .= '</select>';
							
	$template->assign_vars(array(
		'L_TITLE' => $lang['Add_game'],
		'L_EXPLAIN' => $lang['admin_xtras_game_link_msg'],
		'L_ITEMS_REQUIRED' => $lang['Items_required'],
		'L_TYPE'				=> $lang['game_type_exp'],					
		'V_TYPE'				=> $game_type_box,
		'L_LINKS'				=> $lang['game_links'],
		'L_GE_COST'				=> $lang['ge_cost_per_game'],
		'L_GE_COST_EXP'			=> $lang['ge_cost_per_game_exp'],
		'L_MOUSE'				=> $lang['game_mouse'],
		'L_KEYBOARD'			=> $lang['game_keyboard'],
		'L_FUNCTIONS'			=> $lang['admin_game_functionality'],
		'L_FUNCTIONS_EXP'		=> $lang['admin_game_functionality_e'],
		'MOUSE'					=> (($game_mouse) ? 'CHECKED' : ''),
		'KEYBOARD'				=> (($game_keyboard) ? 'CHECKED' : ''),		
		"S_GAME_ACTION"			=> append_sid("admin_activity.$phpEx"),
		"VERSION" 				=> $version,
		"DASH" 					=> $lang['game_dash'],		
		"C_DEFAULT"				=> $lang['a_default_category'],
		"C_SHORT"				=> $lang['a_category'],
		"C_EXPLAIN"				=> $lang['a_category_explain'],
		"V_GAME_HEIGHT"			=> $board_config['ina_default_g_height'],
		"V_GAME_WIDTH"			=> $board_config['ina_default_g_width'],
		"V_GAME_PATH"			=> $board_config['ina_default_g_path'],
		"V_DEFAULT"				=> $default,
		"V_DEFAULT_2"			=> $lang['a_default_charge'],
		"V_INC_1"				=> $increment_value,
		"V_DEFAULT_3"			=> $lang['a_default_bonus'],
		"V_INC_2"				=> $increment_value2,								
		"L_MENU_HEADER" 		=> $lang['admin_game_editor'],
		"L_MENU_INFO" 			=> $lang['admin_editor_info'],
		"L_DISABLE_DES"			=> "<b>". $lang['a_default_hide'] ."</b>",
		"L_DISABLE_DS"			=> $lang['a_default_hide_explain'],		
		"L_NAME" 				=> $lang['admin_name'],
		"L_PROPER_NAME"			=> $lang['admin_proper_name'],
		"L_PROPER_NAME_INFO"	=> $lang['admin_proper_name_desc'],
		"L_NAME_INFO" 			=> $lang['admin_name_info'],
		"L_GAME_PATH" 			=> $lang['admin_game_path'],
		"L_GAME_PATH_INFO" 		=> $lang['admin_game_path_info'],
		"L_GAME_DESC" 			=> $lang['admin_game_desc'],
		"L_GAME_DESC_INFO" 		=> $lang['admin_game_desc_info'],
		"L_GAME_CHARGE" 		=> $lang['admin_game_charge'],
		"L_GAME_CHARGE_INFO" 	=> $lang['admin_game_charge_info'],
		"L_GAME_PER" 			=> $lang['admin_game_per'],
		"L_GAME_PER_INFO" 		=> $lang['admin_game_per_info'],
		"L_GAME_BONUS" 			=> $lang['admin_game_bonus'],
		"L_GAME_BONUS_INFO" 	=> $lang['admin_game_bonus_info'],
		"L_GAME_GAMELIB" 		=> $lang['admin_game_gamelib'],
		"L_GAME_GAMELIB_INFO" 	=> $lang['admin_game_gamelib_info'],
		"L_GAME_FLASH" 			=> $lang['admin_game_flash'],
		"L_GAME_FLASH_INFO" 	=> $lang['admin_game_flash_info'],
		"L_GAME_SHOW_SCORE" 	=> $lang['admin_game_show_score'],
		"L_GAME_SHOW_INFO" 		=> $lang['admin_game_show_info'],
		"L_GAME_REVERSE" 		=> $lang['admin_game_reverse'],
		"L_GAME_REVERSE_INFO" 	=> $lang['admin_game_reverse_info'],
		"L_HIGHSCORE_LIMIT" 	=> $lang['admin_game_highscore'],
		"L_HIGHSCORE_INFO" 		=> $lang['admin_game_highscore_info'],
		"L_GAME_SIZE" 			=> $lang['admin_game_size'],
		"L_GAME_SIZE_INFO" 		=> $lang['admin_game_size_info'],
		"L_INSTRUCTIONS" 		=> $lang['game_instructions'],
		"L_INSTRUCTIONS_INFO" 	=> $lang['instructions_info'],
		"L_WIDTH" 				=> $lang['admin_width'],
		"L_HEIGHT" 				=> $lang['admin_height'],
		"L_MONEY" 				=> $money_name,
		"L_REWARD" 				=> $lang['admin_reward'],
		"L_CHARGE" 				=> $lang['admin_charge'],
		"L_BONUS" 				=> $lang['admin_bonus'],
		"L_LIMIT" 				=> $lang['admin_limit'],
		"L_NO" 					=> $lang['No'],
		"L_YES" 				=> $lang['Yes'],
		"L_SUBMIT" 				=> $lang['Submit'],
		"L_RESET" 				=> $lang['Reset'],

		"S_HIDDEN_FIELDS" 		=> '<input type="hidden" name="mode" value="edit_games"><input type="hidden" name="action" value="save">' )
	);
}
		
if ($mode == 'clear_scores')
{
	$sql = "TRUNCATE " . iNA_SCORES;			
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, $lang['no_score_reset'], "", __LINE__, __FILE__, $sql);
	}
	
	$sql = "UPDATE ". iNA_GAMES ."
		SET played = '0'
		WHERE played <> '0'";
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Error Resetting Games Played Count.", "Error", __LINE__, __FILE__, $sql);
	}
			
	$message .= $lang['admin_score_reset'] . sprintf($lang['admin_return_activity'], "<a href=\"" . append_sid("admin_activity.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
	
	message_die(GENERAL_MESSAGE, $message);
}
		
function DeleteGame($game_id)
{
	global $lang, $db, $table_prefix, $phpEx;
	
	if ($game_id)
	{
		$sql = "SELECT game_name
			FROM ". iNA_GAMES ."
			WHERE game_id = '$game_id'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error Retrieving Game Name.", "Error", __LINE__, __FILE__, $sql);
		}
				
		$row = $db->sql_fetchrow($result);	
		
		$game_name_to_delete = $row['game_name'];
			
		$sql = "DELETE FROM " . iNA_GAMES . "
			WHERE game_id = '$game_id'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['no_game_delete'], "", __LINE__, __FILE__, $sql);
		}	
		
		$sql = "DELETE FROM ". $table_prefix ."ina_hall_of_fame
			WHERE game_id = '$game_id'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error Deleting Hall Of Fame Data.', "", __LINE__, __FILE__, $sql);
		}	
		
		$sql = "DELETE FROM ". $table_prefix ."ina_top_scores
			WHERE game_name = '$game_name_to_delete'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error Deleting Trophy Holder.", "Error", __LINE__, __FILE__, $sql);
		}
			
		$sql = "DELETE FROM ". $table_prefix ."ina_trophy_comments  
			WHERE game = '$game_name_to_delete'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error Deleting Comments Data.", "Error", __LINE__, __FILE__, $sql);
		}
					
		$sql = "DELETE FROM ". iNA_SCORES ." 
			WHERE game_name = '$game_name_to_delete'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error Deleting Scores Data.", "Error", __LINE__, __FILE__, $sql);
		}
		
		$sql = "DELETE FROM ". $table_prefix ."ina_gamble
			WHERE game_id = '$game_id'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error Deleting Completed Gambles.", "Error", __LINE__, __FILE__, $sql);
		}
			
		$sql = "DELETE FROM ". $table_prefix ."ina_gamble_in_progress
			WHERE game_id = '$game_id'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error Deleting Waiting Gambles.", "Error", __LINE__, __FILE__, $sql);		
		}
					
		$sql = "DELETE FROM ". $table_prefix ."ina_rating_votes
			WHERE game_id = '$game_id'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, "Error Deleting Rating Data.", "Error", __LINE__, __FILE__, $sql);				
		}
						
		$message .=  $lang['admin_game_deleted'] . sprintf($lang['admin_return_activity'], "<a href=\"" . append_sid("admin_activity.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
		
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$message .= $lang['admin_game_not_deleted'] . sprintf($lang['admin_return_activity'], "<a href=\"" . append_sid("admin_activity.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
		message_die(GENERAL_MESSAGE, $message);
	}
}
		
#==== Main Games Array ============================ |
$q =  "SELECT *
	FROM ". iNA_GAMES ."
	ORDER BY proper_name ASC"; 
$r  			= $db->sql_query($q);
$games_data		= $db->sql_fetchrowset($r);
$games_total	= $db->sql_numrows($r);

#==== Main Category Array ========================= |
$q = "SELECT *
	FROM ". INA_CATEGORY ."
	WHERE cat_id > '0'
	ORDER BY cat_name ASC"; 
$r 			= $db->sql_query($q); 
$cat_data 	= $db->sql_fetchrowset($r);
$cat_count	= $db->sql_numrows($r);

if ($mode == 'edit_games')
{	
	if (!$action)
	{
		$template->set_filenames(array(
			'body' => 'admin/activity_admin_main.tpl') 
		);
				
		$q =  "SELECT game_id
			FROM ". iNA_GAMES ."
			WHERE game_id > 0"; 
		$r  	= $db->sql_query($q); 		
		$games 	= $db->sql_numrows($r);
	
		$template->assign_vars(array(
			'L_TITLE'		=> $lang['admin_edit_title_r'],
			'L_EXPLAIN'	=> $lang['admin_edit_header'],
			'T_L'		=> $lang['admin_edit_title_l'],
			'T_LC'		=> $lang['admin_edit_title_lc'],
			'T_RC'		=> $lang['admin_edit_title_rc'],
			'T_R'		=> $lang['Action'],
			'M_L'		=> $lang['admin_edit_all_l'],
			'M_RC'		=> $games,
			'M_R'		=> '<a href="' . append_sid('admin_activity.'.$phpEx.'?mode=edit_games&amp;action=view&amp;cat=all') . '"><img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" /></a>')
		);
	
		$q =  "SELECT * 
			FROM ". INA_CATEGORY ." 
			WHERE cat_id > '0'"; 
		$r  = $db -> sql_query($q); 
		
		while($row   = $db -> sql_fetchrow($r)) 
		{
			$cat_img	= $row['cat_img'];
			$cat_desc	= $row['cat_desc'];
			$cat_name	= $row['cat_name'];
			$cat_id		= $row['cat_id'];
			
			$q1 =  "SELECT COUNT(game_id) AS total_games
				FROM ". iNA_GAMES ."
				WHERE cat_id = '". $cat_id ."'"; 
			$r1  	= $db -> sql_query($q1); 
			$row1	= $db -> sql_fetchrow($r1);		
			
			$total_games = $row1['total_games'];
			
			if (file_exists($phpbb_root_path . $cat_img) == 0) 
			{
				$cat_img 	= '';
			}
			else
			{
				$cat_img 	= '<img src="'. $phpbb_root_path . $cat_img .'" alt="" title="" />';
			}
	
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
									
			$template->assign_block_vars('rows', array(
				'ROW_CLASS' => $row_class,
				'ONE' 		=> $cat_name,
				'TWO' 		=> $cat_img,
				'THREE' 	=> $total_games,
				'FOUR'		=> ($total_games > 0) ? '<a href="' . append_sid('admin_activity.'.$phpEx.'?mode=edit_games&amp;action=view&amp;cat=' . $cat_id) . '"><img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" /></a>' : '')
			);		
		}
		$db->sql_freeresult($result);
	}
	
			
	if ($action == 'view')
	{
		$template->assign_vars( array(
			'L_TITLE'	=> $lang['admin_edit_title_r'],
			'L_EXPLAIN'	=> $lang[''])
		);
		
		$template->set_filenames(array(
			'body' => 'admin/activity_admin_edit.tpl') 
		);			
		
		$cat = ($_GET['cat']) ? $_GET['cat'] : $HTTP_GET_VARS['cat'];
				
		if ($cat == 'all')
		{
			$template->assign_block_vars('cat_choice', array());
			
			for ($a = 0; $a < $games_total; $a++)
			{
				$game_id 	= $games_data[$a]['game_id'];
				$game_name 	= $games_data[$a]['proper_name'];
				$game_image = '<img src="'. $phpbb_root_path . $board_config['ina_default_g_path'] .'/'. $games_data[$a]['game_name'] .'/'. $games_data[$a]['game_name'] .'.gif" alt="" title="" />';
				
				if (!$game_image)
				{
					$game_image = $lang['admin_edit_title_r'];
				}
					
				$edit_link = '<a href="admin_activity.'. $phpEx .'?mode=edit_games&action=edit&game='. $game_id .'&sid='. $userdata['session_id'] .'">'. $game_image .'</a>';
					
				$template->assign_block_vars('cat_choice.rows', array(
					'ONE'	=> $game_name,
					'TWO'	=> $edit_link)
				);
						
				if (!$games_data[$a]['game_id'])
				{
					break;
				}
			}			
		}
		else
		{
			$cat = intval($cat);

			$template->assign_block_vars('cat_choice', array());
			
			for ($a = 0; $a < $games_total; $a++)
			{
				if ($games_data[$a]['cat_id'] == $cat)
				{
					$game_id 	= $games_data[$a]['game_id'];
					$game_name 	= $games_data[$a]['proper_name'];
					$game_image = '<img src="'. $phpbb_root_path . $board_config['ina_default_g_path'] .'/'. $games_data[$a]['game_name'] .'/'. $games_data[$a]['game_name'] .'.gif" alt="" title="" />';
					
					if (!$game_image)
					{
						$game_image = $lang['admin_edit_title_r'];
					}	
					
					$edit_link = '<a href="admin_activity.'. $phpEx .'?mode=edit_games&action=edit&game='. $game_id .'&sid='. $userdata['session_id'] .'">'. $game_image .'</a>';
						
					$template->assign_block_vars('cat_choice.rows', array(
						'ONE'	=> $game_name,
						'TWO'	=> $edit_link)
					);
					
					if (!$games_data[$a]['game_id'])
					{
						break;
					}
				}
				
				if (!$games_data[$a]['game_id'])
				{
					break;			
				}
			}		
		}
	}
			
	if ($action == 'edit')
	{

		$template->set_filenames(array(
			'body' => 'admin/activity_admin_edit.tpl') 
		);
		
		$game = ($_GET['game']) ? $_GET['game'] : $HTTP_GET_VARS['game'];
		
		$template->assign_block_vars('editing', array());
		
		for ($a = 0; $a < $games_total; $a++)
		{
			if ($games_data[$a]['game_id'] == $game)
			{
				#==== Game options
				$game_id 			= $games_data[$a]['game_id'];			# not changeable
				$game_name 			= $games_data[$a]['game_name'];			# input box
				$game_proper 		= $games_data[$a]['proper_name'];		# input box
				$game_width 		= $games_data[$a]['win_width'];			# input box
				$game_height 		= $games_data[$a]['win_height'];		# input box
				$game_path 			= $games_data[$a]['game_path'];			# input box
				$game_scores 		= $games_data[$a]['game_show_score'];	# radio
				$game_scores_limit 	= $games_data[$a]['highscore_limit'];	# input box
				$game_scores_order 	= $games_data[$a]['reverse_list'];		# radio
				$game_flash 		= $games_data[$a]['game_flash'];		# radio 
				$game_glib 			= $games_data[$a]['game_use_gl'];		# radio 
				$game_bonus 		= $games_data[$a]['game_bonus'];		# input box
				$game_cost 			= $games_data[$a]['game_charge'];		# input box
				$game_reward 		= $games_data[$a]['game_reward'];		# input box
				$game_desc 			= $games_data[$a]['game_desc'];			# text area
				$game_disabled 		= $games_data[$a]['disabled'];			# radio
				$game_instructions 	= $games_data[$a]['instructions'];		# text area
				$game_popup 		= $games_data[$a]['game_popup'];		# radio
				$game_parent 		= $games_data[$a]['game_parent'];		# radio				
				$game_category 		= $games_data[$a]['cat_id'];			# drop down list
				$game_type			= $games_data[$a]['game_type'];			# drop down list
				$game_links			= $games_data[$a]['game_links'];		# input box
				$game_ge_cost		= $games_data[$a]['game_ge_cost'];		# input box
				$game_mouse			= $games_data[$a]['game_mouse'];		# checkbox
				$game_keyboard		= $games_data[$a]['game_keyboard'];		# checkbox
				#==== Radio options
				$reverse_yes 		= ($game_scores_order) 	? 'checked="checked"' : '';
				$reverse_no  		= (!$game_scores_order) ? 'checked="checked"' : '';				
				$scores_yes 		= ($game_scores) 		? 'checked="checked"' : '';
				$scores_no  		= (!$game_scores) 		? 'checked="checked"' : '';
				$flash_yes 			= ($game_flash) 		? 'checked="checked"' : '';
				$flash_no  			= (!$game_flash) 		? 'checked="checked"' : '';
				$glib_yes 			= ($game_glib) 			? 'checked="checked"' : '';
				$glib_no  			= (!$game_glib) 		? 'checked="checked"' : '';
				$disabled_yes 		= ($game_disabled == 2)	? 'checked="checked"' : '';
				$disabled_no  		= ($game_disabled == 1) ? 'checked="checked"' : '';
				$popup_yes 			= ($game_popup) 		? 'checked="checked"' : '';
				$popup_no  			= (!$game_popup) 		? 'checked="checked"' : '';
				$parent_yes			= ($game_parent) 		? 'checked="checked"' : '';
				$parent_no 			= (!$game_parent) 		? 'checked="checked"' : '';																				
				
				if ($game_type == 1)
				{
					$type_one = 'selected';
				}
				if ($game_type == 2)
				{
					$type_two = 'selected';
				}
				if ($game_type == 3)
				{
					$type_three = 'selected';
				}
				if ($game_type == 4)
				{
					$type_four = 'selected';															
				}
				
				$game_type_box = '';
				$game_type_box .= '<select name="game_type">';
				$game_type_box .= '<option '. $type_one .' value="1">'. $lang['game_type_one'] .'</option>';
				$game_type_box .= '<option '. $type_two .' value="2">'. $lang['game_type_two'] .'</option>';
				$game_type_box .= '<option '. $type_three .' value="3">'. $lang['game_type_three'] .'</option>';
				$game_type_box .= '<option '. $type_four .' value="4">'. $lang['game_type_four'] .'</option>';				
				$game_type_box .= '</select>';

				$category_box = '';
        	    $category_box .= '<select name="game_cat">';
               	if (!$game_category)
                {
                	  $category_box .= '<option class="post" selected value="">'. $lang['a_default_category'] .'</option>';
               	}
               	for ($b = 0; $b < $cat_count; $b++)
                {
              		if ($cat_data[$b]['cat_id'] == $game_category)
              		{
              	    	$category_box .= '<option class="post" selected value="'. $cat_data[$b]['cat_id'] .'">'. $cat_data[$b]['cat_name'] .'</option>';
              	  	}
              	  	else
              	  	{
              	  		$category_box .= '<option class="post" value="'. $cat_data[$b]['cat_id'] .'">'. $cat_data[$b]['cat_name'] .'</option>';
              	  	}
              	}
                  
            	$category_box .= '<option class="post" value="">-----</option>';                  
            	$category_box .= '</select>'; 
				
				$template->assign_vars(array(
					'L_TITLE'	=> $lang['admin_edit_title_r'] . ': ' . $game_proper,
					'ID'				=> $game_id,
					'CAT'            => $category_box, 
					'L_MOUSE'			=> $lang['game_mouse'],
					'L_KEYBOARD'		=> $lang['game_keyboard'],
					'L_FUNCTIONS'		=> $lang['admin_game_functionality'],
					'L_FUNCTIONS_EXP'	=> $lang['admin_game_functionality_e'],
					'MOUSE'				=> (($game_mouse) ? 'checked="checked"' : ''),
					'KEYBOARD'			=> (($game_keyboard) ? 'checked="checked"' : ''),
					'L_GE_COST'			=> $lang['ge_cost_per_game'],
					'L_GE_COST_EXP'		=> $lang['ge_cost_per_game_exp'],
					'V_GE_COST'			=> $game_ge_cost,
					'L_TYPE'			=> $lang['game_type_exp'],					
					'V_TYPE'			=> $game_type_box,
					'L_LINKS'			=> $lang['game_links'],
					'V_LINKS'			=> $game_links,
					'RETURN'			=> append_sid('admin_activity.'. $phpEx .'?mode=edit_games'),
					'V_ONE'				=> $game_name,
					'L_ONE'				=> $lang['admin_name'] .':</b><br /><span class="gensmall">'. $lang['admin_name_info'] .'</span>',
					'V_TWO'				=> $game_proper,
					'L_TWO'				=> $lang['admin_proper_name'] .':</b><br /><span class="gensmall">'. $lang['admin_proper_name_desc'] .'</span>',
					'V_THREE'			=> $game_path,
					'L_THREE'			=> $lang['admin_game_path'] .':</b><br /><span class="gensmall">'. $lang['admin_game_path_info'] .'</span>',
					'V_FOUR'			=> $game_desc,
					'L_FOUR'			=> $lang['admin_game_desc'] .':</b><br /><span class="gensmall">'. $lang['admin_game_desc_info'] .'</span>', 
					'V_FIVE'			=> $game_instructions,
					'L_FIVE'			=> $lang['game_instructions'] .':</b><br /><span class="gensmall">'. $lang['instructions_info'] .'</span>',
					'L_SIZE'			=> $lang['admin_game_size'] .':</b><br /><span class="gensmall">'. $lang['admin_game_size_info'] .'</span>',
					'V_SIX'				=> $game_width,
					'L_SIX'				=> $lang['admin_width'],
					'V_SEVEN'			=> $game_height,
					'L_SEVEN'			=> $lang['admin_height'],
					'V_EIGHT'			=> $game_bonus,
					'L_EIGHT'			=> $lang['admin_game_bonus'] .':</b><br /><span class="gensmall">'. $lang['admin_game_bonus_info'] .'</span>',
					'V_NINE'			=> $game_reward,
					'L_NINE'			=> $lang['admin_game_per'] .':</b><br /><span class="gensmall">'. $lang['admin_game_per_info'] .'</span>', 
					'V_TEN'				=> $game_cost,
					'L_TEN'				=> $lang['admin_game_charge'] .':</b><br /><span class="gensmall">'. $lang['admin_game_charge_info'] .'</span>',
					'V_ELEVEN'			=> $game_scores_limit,
					'L_ELEVEN'			=> $lang['admin_game_highscore'] .':</b><br /><span class="gensmall">'. $lang['admin_game_highscore_info'] .'</span>', 
					'L_YES'				=> $lang['Yes'],
					'L_NO'				=> $lang['No'],
					'L_RESET_SCORES'	=> $lang['admin_game_reset_hs'] .':</b><br /><span class="gensmall">'. $lang['admin_game_reset_hs_info'] .'</span>',
					'L_RESET_JACKPOT'	=> $lang['admin_drop_jackpot'] .':</b><br /><span class="gensmall">'. $lang['admin_drop_jackpot_exp'] .'</span>',
					'L_DELETE_GAME'		=> $lang['admin_drop_game'] .':</b><br /><span class="gensmall">'. $lang['admin_drop_game_exp'] .'</span>',
					'L_REVERSE'			=> $lang['admin_game_reverse'] .':</b><br /><span class="gensmall">'. $lang['admin_game_reverse_info'] .'</span>',
					'REVERSE_Y'			=> $reverse_yes,
					'REVERSE_N'			=> $reverse_no,
					'L_SCORES'			=> $lang['admin_game_show_score'] .':</b><br /><span class="gensmall">'. $lang['admin_game_show_info'] .'</span>',
					'SCORES_Y'			=> $scores_yes,
					'SCORES_N'			=> $scores_no,
					'L_FLASH'			=> $lang['admin_game_flash'] .':</b><br /><span class="gensmall">'. $lang['admin_game_flash_info'] .'</span>',
					'FLASH_Y'			=> $flash_yes,
					'FLASH_N'			=> $flash_no,
					'L_GLIB'			=> $lang['admin_game_gamelib'] .':</b><br /><span class="gensmall">'. $lang['admin_game_gamelib_info'] .'</span>',
					'GLIB_Y'			=> $glib_yes,
					'GLIB_N'			=> $glib_no,
					'L_DISABLE'			=> $lang['admin_disable_game'] .':</b><br /><span class="gensmall">'. $lang['admin_disable_game_exp'] .'</span>',
					'DIS_Y'				=> $disabled_yes,
					'DIS_N'				=> $disabled_no,
					'L_PARENT'			=> $lang['admin_parent_game'] .':</b><br /><span class="gensmall">'. $lang['admin_parent_game_exp'] .'</span>',
					'PARENT_Y'			=> $parent_yes,
					'PARENT_N'			=> $parent_no,
					'L_POPUP'			=> $lang['admin_popup_game'] .':</b><br /><span class="gensmall">'. $lang['admin_popup_game_exp'] .'</span>',
					'POPUP_Y'			=> $popup_yes,
					'POPUP_N'			=> $popup_no,
					'L_CATEGORY'		=> $lang['a_category'] .':</b><br /><span class="gensmall">'. $lang['a_category_explain'] .'</span>')
				);	

				break;
			}
		}	
	}
			
	if ($action == 'save')
	{
		$template->set_filenames(array(
			'body' => 'admin/activity_admin_edit.tpl') 
		);			
		
		$game_id 			= intval(($_POST['game_id']) 			? $_POST['game_id'] 			: $HTTP_POST_VARS['game_id']);
		$game_name 			= ($_POST['game_name']) 				? $_POST['game_name'] 			: $HTTP_POST_VARS['game_name'];
		$err1				= (!$game_name)							? 1 							: 0;
		$game_proper 		= ($_POST['game_proper']) 				? $_POST['game_proper'] 		: $HTTP_POST_VARS['game_proper'];
		$err2				= (!$game_proper)						? 1 							: 0;				
		$game_cat 			= intval(($_POST['game_cat']) 			? $_POST['game_cat'] 			: $HTTP_POST_VARS['game_cat']);		
		$game_path 			= ($_POST['game_path']) 				? $_POST['game_path'] 			: $HTTP_POST_VARS['game_path'];
		$err3				= (!$game_path) 						? 1 							: 0;		
		$game_width 		= intval(($_POST['game_width']) 		? $_POST['game_width'] 			: $HTTP_POST_VARS['game_width']);
		$game_height 		= intval(($_POST['game_height']) 		? $_POST['game_height'] 		: $HTTP_POST_VARS['game_height']);
		$game_bonus 		= intval(($_POST['game_bonus']) 		? $_POST['game_bonus'] 			: $HTTP_POST_VARS['game_bonus']);
		$game_reward 		= intval(($_POST['game_reward']) 		? $_POST['game_reward'] 		: $HTTP_POST_VARS['game_reward']);
		$game_charge 		= intval(($_POST['game_charge']) 		? $_POST['game_charge'] 		: $HTTP_POST_VARS['game_charge']);
		$game_max_scores 	= intval(($_POST['game_highscore']) 	? $_POST['game_highscore'] 		: $HTTP_POST_VARS['game_highscore']);		
		$game_desc 			= ($_POST['game_description']) 			? $_POST['game_description'] 	: $HTTP_POST_VARS['game_description'];
		$game_instr 		= ($_POST['game_instructions']) 		? $_POST['game_instructions'] 	: $HTTP_POST_VARS['game_instructions'];
		$game_reverse 		= intval(($_POST['game_reverse']) 		? $_POST['game_reverse'] 		: $HTTP_POST_VARS['game_reverse']);
		$game_allow_scores 	= (intval($_POST['game_showscores']) 	? $_POST['game_showscores'] 	: $HTTP_POST_VARS['game_showscores']);
		$game_allow_scores 	= (!$game_allow_scores)					? 1								: 0;
		$game_flash 		= intval(($_POST['game_flash']) 		? $_POST['game_flash'] 			: $HTTP_POST_VARS['game_flash']);
		$game_glib 			= intval(($_POST['game_glib']) 			? $_POST['game_glib'] 			: $HTTP_POST_VARS['game_glib']);				
		$game_disabled 		= intval(($_POST['game_disable']) 		? $_POST['game_disable'] 		: $HTTP_POST_VARS['game_disable']);
		$game_disabled 		= ($game_disabled == '2') 				? 0 							: 1;
		$game_parent 		= intval(($_POST['game_parent']) 		? $_POST['game_parent'] 		: $HTTP_POST_VARS['game_parent']);
		$game_popup 		= intval(($_POST['game_popup']) 		? $_POST['game_popup'] 			: $HTTP_POST_VARS['game_popup']);	
		$game_jackpot 		= ($_POST['reset_jackpot']) 			? $_POST['reset_jackpot'] 		: $HTTP_POST_VARS['reset_jackpot'];
		$game_reset_scores 	= ($_POST['reset_scores']) 				? $_POST['reset_scores'] 		: $HTTP_POST_VARS['reset_scores'];
		$game_delete 		= ($_POST['delete_game']) 				? $_POST['delete_game'] 		: $HTTP_POST_VARS['delete_game'];
		$game_type			= intval(($_POST['game_type']) 			? $_POST['game_type'] 			: $HTTP_POST_VARS['game_type']);
		$game_links			= ($_POST['game_links'])				? $_POST['game_links']			: $HTTP_POST_VARS['game_links'];
		$game_ge_cost		= intval(($_POST['game_ge_cost'])		? $_POST['game_ge_cost'] 		: $HTTP_POST_VARS['game_ge_cost']);
		$game_keyboard		= ($HTTP_POST_VARS['game_keyboard'] == 'on') ? 1 : 0;
		$game_mouse			= ($HTTP_POST_VARS['game_mouse'] == 'on') ? 1 : 0;
		
		$q = "SELECT game_name
			FROM ". iNA_GAMES ."
			WHERE game_id = '". $game_id ."'";
		$r 		= $db->sql_query($q);
		$row 	= $db->sql_fetchrow($r);
		
		$real_game_name = $row['game_name'];
		
		if ($err1 || $err2 || $err3)
		{
			if ($err1)
			{
				message_die(GENERAL_ERROR, $lang['edit_error_one']);
			}
			if ($err2)
			{
				message_die(GENERAL_ERROR, $lang['edit_error_two']);				
			}
			if ( ($err3) && ($game_id > '0') )
			{
				message_die(GENERAL_ERROR, $lang['edit_error_three']);				
			}
		}
				
		if ($game_delete)
		{
			DeleteGame($game_id);
		}
				
		if ($game_reset_scores)
		{
			$q = "DELETE FROM ". iNA_SCORES ."
				WHERE game_name = '". $real_game_name ."'";
			$db->sql_query($q);
		}
				
		if ($game_id)
		{
			$game_name		= trim(rtrim(addslashes(stripslashes($game_name))));
			$game_desc		= trim(rtrim(addslashes(stripslashes($game_desc))));
			$game_instr		= trim(rtrim(addslashes(stripslashes($game_instr))));
			$game_proper	= trim(rtrim(addslashes(stripslashes($game_proper))));
			$game_links		= trim(rtrim(addslashes(stripslashes($game_links))));									
			#==== Build it here, easier to update later!
			$changes = (!$game_name) 			? '' : "game_name = '". $game_name ."'";
			$changes .= (!$game_links)			? ", game_links = ''" : ", game_links = '". $game_links ."'";
			$changes .= (!$game_type)			? '' : ", game_type = '". $game_type ."'";
			$changes .= (!$game_path) 			? '' : ", game_path = '". $board_config['ina_default_g_path'] ."$game_name/'";
			$changes .= (!$game_desc) 			? '' : ", game_desc = '". $game_desc ."'";
			$changes .= (!$game_charge)			? ", game_charge = '0'" : ", game_charge = '". $game_charge ."'";
			$changes .= (!$game_reward) 		? ", game_reward = '0'" : ", game_reward = '". $game_reward ."'";
			$changes .= (!$game_bonus) 			? ", game_bonus = '0'" : ", game_bonus = '". $game_bonus ."'";
			$changes .= (!$game_glib) 			? '' : ", game_use_gl = '". $game_glib ."'";
			$changes .= (!$game_flash) 			? '' : ", game_flash = '". $game_flash ."'";
			$changes .= (!$game_allow_scores) 	? '' : ", game_show_score = '". $game_allow_scores ."'";
			$changes .= (!$game_width) 			? '' : ", win_width = '". $game_width ."'";
			$changes .= (!$game_height) 		? '' : ", win_height = '". $game_height ."'";
			$changes .= (!$game_max_scores) 	? '' : ", highscore_limit = '". $game_max_scores ."'";
			$changes .= (!$game_reverse)        ? ", reverse_list = 0" : ", reverse_list = 1"; 
			$changes .= (!$game_instr) 			? '' : ", instructions = '". $game_instr ."'";
			$changes .= (!$game_disabled) 		? ", disabled = 0" : ", disabled = 1";															
			$changes .= (!$game_proper) 		? '' : ", proper_name = '". $game_proper ."'";
			$changes .= (!$game_cat) 			? '' : ", cat_id = '". $game_cat ."'";
			$changes .= (!$game_jackpot) 		? '' : ", jackpot = 0";
			$changes .= (!$game_popup) 			? ", game_popup = 0" : ", game_popup = 1";
			$changes .= (!$game_parent) 		? ", game_parent = 0" : ", game_parent = 1";
			$changes .= (!$game_ge_cost)		? ", game_ge_cost = 0" : ", game_ge_cost = '". $game_ge_cost ."'";
			$changes .= ", game_mouse = '". $game_mouse ."'";
			$changes .= ", game_keyboard = '". $game_keyboard ."'";
			
			$q = "UPDATE ". iNA_GAMES ."
				SET $changes
				WHERE game_id = '". $game_id ."'";
			if (!$db->sql_query($q))
			{
				message_die(GENERAL_ERROR, $lang['no_game_save'], '', __LINE__, __FILE__, $q);
			}
		}
		else
		{
			$q = "INSERT INTO ". iNA_GAMES ." (game_name, game_mouse, game_keyboard, game_links, game_type, game_path, game_desc, game_charge, game_reward, game_bonus, game_use_gl, game_flash, game_show_score, win_width, win_height, highscore_limit, reverse_list, played, instructions, disabled, install_date, proper_name, cat_id, jackpot, game_popup, game_parent, game_ge_cost)
				  VALUES ('". str_replace("\'", "''", $game_name) ."', '". $game_mouse ."', '". $game_keyboard ."', '". $game_links ."', '". $game_type ."', '". $board_config['ina_default_g_path'] ."$game_name/', '". str_replace("\'", "''", $game_desc) ."', '". $game_charge ."', '". $game_reward ."', '". $game_bonus ."', '". $game_glib ."', '". $game_flash ."', '". $game_allow_scores ."', '". $game_width ."', '". $game_height ."', '". $game_max_scores ."', '". $game_reverse ."', '0', '". str_replace("\'", "''", $game_instr) ."', '". $game_disabled ."', '". time() ."', '". str_replace("\'", "''", $game_proper) ."', '". $game_cat ."', '". $board_config['ina_jackpot_pool'] ."', '1', '1', '". $game_ge_cost ."')";
			if (!$db->sql_query($q))
			{
				message_die(GENERAL_ERROR, $lang['no_game_save'], '', __LINE__, __FILE__, $q);
			}
		}
				
		$message .= $lang['admin_game_saved'] . sprintf($lang['admin_return_game_edit'], "<a href=\"" . append_sid("admin_activity.$phpEx?mode=edit_games") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
		
		message_die(GENERAL_MESSAGE, $message);				
	}
}

if ( (!$mode) && (!$action) )
{
	$sql = "SELECT * 
		FROM " . CONFIG_TABLE . "
		WHERE config_name IN(
		'use_gamelib'
		, 'games_path'
		, 'gamelib_path'
		, 'use_gk_shop'
		, 'games_per_page'
		, 'warn_cheater'
		, 'report_cheater'
		, 'ina_default_charge'
		, 'ina_default_increment'
		, 'ina_default_g_path'
		, 'ina_default_g_reward'
		, 'ina_default_g_height'
		, 'ina_default_g_width'
		, 'ina_cash_name'
		, 'ina_jackpot_pool'
		, 'ina_max_gamble'
		, 'ina_default_order'
		, 'ina_max_games_per_day'
		, 'ina_post_block'
		, 'ina_post_block_count'
		, 'ina_join_block'
		, 'ina_join_block_count'
		, 'ina_challenge'
		, 'ina_challenge_msg'
		, 'ina_challenge_sub'
		, 'ina_pm_trophy'
		, 'ina_pm_trophy_msg'
		, 'ina_pm_trophy_sub'
		, 'ina_use_newest'
		, 'ina_new_game_count'
		, 'ina_button_option'
		, 'ina_new_game_limit'
		, 'ina_pop_game_limit'
		, 'ina_use_rating_reward'
		, 'ina_rating_reward'
		, 'ina_use_daily_game'
		, 'ina_daily_game_random'
		, 'ina_daily_game_id'
		, 'ina_guest_play'
		, 'ina_use_online'
		, 'ina_disable_cheat'
		, 'ina_show_view_profile'
		, 'ina_show_view_topic'
		, 'ina_use_shoutbox'
		, 'ina_disable_everything'
		, 'ina_disable_trophy_page'
		, 'ina_disable_comments_page'
		, 'ina_disable_gamble_page'
		, 'ina_disable_challenges_page'
		, 'ina_disable_top5_page'
		, 'ina_use_trophy'
		, 'ina_disable_submit_scores_m'
		, 'ina_disable_submit_scores_g'
		, 'ina_players_index'
		, 'ina_force_registration'
	)";
	if (!$result = $db->sql_query($sql))
	{
		message_die(CRITICAL_ERROR, $lang['no_config_data'], '', __LINE__, __FILE__, $sql);
	}
	else
	{
		while( $row = $db->sql_fetchrow($result) )
		{
			$config_name = $row['config_name'];
			$config_value = $row['config_value'];
			$default_config[$config_name] = $config_value;
     
			$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

			if(isset($HTTP_POST_VARS['submit']))
			{
				$sql = "UPDATE " . CONFIG_TABLE . "
					SET config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
					WHERE config_name = '$config_name'";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['no_config_update'] . $config_name, '', __LINE__, __FILE__, $sql);
				}
			}
		}
		$db->sql_freeresult($result);

		if (isset($HTTP_POST_VARS['submit']))
		{
			// Remove cache file
			@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

			$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_activity.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
			
			message_die(GENERAL_MESSAGE, $message, '', __LINE__, __FILE__, $sql);
		}
	}

	if ( $new['ina_default_order'] == 1 ) 
	{
		$current_setting = $lang['corder_gpA'];
	}
	if ( $new['ina_default_order'] == 2 ) 
	{
		$current_setting = $lang['corder_cpD'];
	}
	if ( $new['ina_default_order'] == 3 ) 
	{
		$current_setting = $lang['corder_na'];
	}
	if ( $new['ina_default_order'] == 4 ) 
	{
		$current_setting = $lang['corder_oa'];
	}
	if ( $new['ina_default_order'] == 5 ) 
	{
		$current_setting = $lang['corder_bA'];
	}
	if ( $new['ina_default_order'] == 6 ) 
	{
		$current_setting = $lang['corder_bD'];
	}
	if ( $new['ina_default_order'] == 7 ) 
	{
		$current_setting = $lang['corder_cA'];
	}
	if ( $new['ina_default_order'] == 8 ) 
	{
		$current_setting = $lang['corder_cD'];
	}
	if ( $new['ina_default_order'] == 9 ) 
	{
		$current_setting = $lang['corder_properA'];
	}
	if ( $new['ina_default_order'] == 10 ) 
	{
		$current_setting = $lang['corder_properD'];
	}
	if ( $new['ina_default_order'] == 11 ) 
	{
		$current_setting = $lang['corder_jackpotA'];
	}
	if ( $new['ina_default_order'] == 12 ) 
	{
		$current_setting = $lang['corder_jackpotD'];
	}
	
	$use_max_games_per_day_yes = ( $new['ina_use_max_games_per_day'] ) ? 'checked="checked"' : '';
	$use_max_games_per_day_no = ( !$new['ina_use_max_games_per_day'] ) ? 'checked="checked"' : '';

	$use_posts_yes = ( $new['ina_post_block'] ) ? 'checked="checked"' : '';
	$use_posts_no = ( !$new['ina_post_block'] ) ? 'checked="checked"' : '';

	$use_time_yes = ( $new['ina_join_block'] ) ? 'checked="checked"' : '';
	$use_time_no = ( !$new['ina_join_block'] ) ? 'checked="checked"' : '';

	$use_challenge_yes = ( $new['ina_challenge'] ) ? 'checked="checked"' : '';
	$use_challenge_no = ( !$new['ina_challenge'] ) ? 'checked="checked"' : '';

	$use_trophy_yes = ( $new['ina_pm_trophy'] ) ? 'checked="checked"' : '';
	$use_trophy_no = ( !$new['ina_pm_trophy'] ) ? 'checked="checked"' : '';

	$use_newest_yes = ( $new['ina_use_newest'] ) ? 'checked="checked"' : '';
	$use_newest_no = ( !$new['ina_use_newest'] ) ? 'checked="checked"' : '';

	$use_button_yes = ( $new['ina_button_option'] ) ? 'checked="checked"' : '';
	$use_button_no = ( !$new['ina_button_option'] ) ? 'checked="checked"' : '';

	$use_rating_reward_yes = ( $new['ina_use_rating_reward'] ) ? 'checked="checked"' : '';
	$use_rating_reward_no = ( !$new['ina_use_rating_reward'] ) ? 'checked="checked"' : '';

	$use_daily_game_yes = ( $new['ina_use_daily_game'] ) ? 'checked="checked"' : '';
	$use_daily_game_no = ( !$new['ina_use_daily_game'] ) ? 'checked="checked"' : '';

	$use_daily_random_yes = ( $new['ina_daily_game_random'] ) ? 'checked="checked"' : '';
	$use_daily_random_no = ( !$new['ina_daily_game_random'] ) ? 'checked="checked"' : '';

	$sql = "SELECT game_id, proper_name
		FROM ". iNA_GAMES ."
		WHERE game_id > 0
		ORDER BY proper_name ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain game dropdown options.', '', __LINE__, __FILE__, $sql);
	} 
	
	$options = '';
	while ($row = $db->sql_fetchrow($result))
	{
		if ($row['game_id'] == $new['ina_daily_game_id'])
		{
			$selected = ' selected="selected"';
		}
		else
		{
			$selected = '';
		}
		$options .= '<option value="'. $row['game_id'] .'"'. $selected .'>'. $row['proper_name'] .'</option>';
	}
	$db->sql_freeresult($result);

	$ina_guest_play_yes = ( $new['ina_guest_play'] ) ? 'checked="checked"' : '';
	$ina_guest_play_no = ( !$new['ina_guest_play'] ) ? 'checked="checked"' : '';

	$ina_use_online_yes = ( $new['ina_use_online'] ) ? 'checked="checked"' : '';
	$ina_use_online_no = ( !$new['ina_use_online'] ) ? 'checked="checked"' : '';

	$ina_disable_cheat_yes = ( $new['ina_disable_cheat'] ) ? 'checked="checked"' : '';
	$ina_disable_cheat_no = ( !$new['ina_disable_cheat'] ) ? 'checked="checked"' : '';

	$ina_show_view_profile_yes = ( $new['ina_show_view_profile'] ) ? 'checked="checked"' : '';
	$ina_show_view_profile_no = ( !$new['ina_show_view_profile'] ) ? 'checked="checked"' : '';

	$ina_show_view_topic_yes = ( $new['ina_show_view_topic'] ) ? 'checked="checked"' : '';
	$ina_show_view_topic_no = ( !$new['ina_show_view_topic'] ) ? 'checked="checked"' : '';

	$ina_hof_viewtopic_yes = ( $new['ina_hof_viewtopic'] ) ? 'checked="checked"' : '';
	$ina_hof_viewtopic_no = ( !$new['ina_hof_viewtopic'] ) ? 'checked="checked"' : '';

	$ina_players_index_yes = ( $new['ina_players_index'] ) ? 'checked="checked"' : '';
	$ina_players_index_no = ( !$new['ina_players_index'] ) ? 'checked="checked"' : '';

	$ina_use_shoutbox_yes = ( $new['ina_use_shoutbox'] ) ? 'checked="checked"' : '';
	$ina_use_shoutbox_no = ( !$new['ina_use_shoutbox'] ) ? 'checked="checked"' : '';

	$ina_disable_everything_yes = ( $new['ina_disable_everything'] ) ? 'checked="checked"' : '';
	$ina_disable_everything_no = ( !$new['ina_disable_everything'] ) ? 'checked="checked"' : '';

	$ina_disable_trophy_page_yes = ( $new['ina_disable_trophy_page'] ) ? 'checked="checked"' : '';
	$ina_disable_trophy_page_no = ( !$new['ina_disable_trophy_page'] ) ? 'checked="checked"' : '';

	$ina_disable_comments_page_yes = ( $new['ina_disable_comments_page'] ) ? 'checked="checked"' : '';
	$ina_disable_comments_page_no = ( !$new['ina_disable_comments_page'] ) ? 'checked="checked"' : '';

	$ina_disable_gamble_page_yes = ( $new['ina_disable_gamble_page'] ) ? 'checked="checked"' : '';
	$ina_disable_gamble_page_no = ( !$new['ina_disable_gamble_page'] ) ? 'checked="checked"' : '';

	$ina_disable_challenges_page_yes = ( $new['ina_disable_challenges_page'] ) ? 'checked="checked"' : '';
	$ina_disable_challenges_page_no = ( !$new['ina_disable_challenges_page'] ) ? 'checked="checked"' : '';

	$ina_disable_top5_page_yes = ( $new['ina_disable_top5_page'] ) ? 'checked="checked"' : '';
	$ina_disable_top5_page_no = ( !$new['ina_disable_top5_page'] ) ? 'checked="checked"' : '';

	$ina_use_trophy_yes = ( $new['ina_use_trophy'] ) ? 'checked="checked"' : '';
	$ina_use_trophy_no = ( !$new['ina_use_trophy'] ) ? 'checked="checked"' : '';

	$ina_disable_submit_scores_m_yes = ( $new['ina_disable_submit_scores_m'] ) ? 'checked="checked"' : '';
	$ina_disable_submit_scores_m_no = ( !$new['ina_disable_submit_scores_m'] ) ? 'checked="checked"' : '';

	$ina_disable_submit_scores_g_yes = ( $new['ina_disable_submit_scores_g'] ) ? 'checked="checked"' : '';
	$ina_disable_submit_scores_g_no = ( !$new['ina_disable_submit_scores_g'] ) ? 'checked="checked"' : '';

	$ina_force_registration_yes = ( $new['ina_force_registration'] ) ? 'checked="checked"' : '';
	$ina_force_registration_no  = ( !$new['ina_force_registration'] ) ? 'checked="checked"' : '';

	$use_gk_shop_yes = ( $new['use_gk_shop'] ) ? 'checked="checked"' : '';
	$use_gk_shop_no  = ( !$new['use_gk_shop'] ) ? 'checked="checked"' : '';

	$use_gamelib_yes = ( $new['use_gamelib'] ) ? 'checked="checked"' : '';
	$use_gamelib_no  = ( !$new['use_gamelib'] ) ? 'checked="checked"' : '';

	$template->set_filenames(array(
		'body' => 'admin/activity_config_body.tpl')
	);

	if ($board_config['use_gamelib'])
	{
		$template->assign_block_vars('display_gamelib_menu', array());
	}
	if ($board_config['use_gk_shop'])
	{
		$template->assign_block_vars('display_shop_menu', array());
	}

	$template->assign_block_vars('rewards_menu_on', array());

	$template->assign_vars(array(
		'L_ACTIVITY_CONFIG' => $lang['Activity'] . ' ' . $lang['Setting'],
		'L_ACTIVITY_CONFIG_EXPLAIN' => $lang['admin_activity_config_explain'],
		'L_ACTIVITY_CONFIG_EXPLAIN1' => $lang['admin_xtras_game_link_msg'],
		'L_TOGGLES' => $lang['admin_toggles'],
		'L_REWARDS' => $lang['admin_rewards'],
		'L_USE_ADAR_SHOP' => $lang['admin_use_adar_shop'], 
		'L_USE_ADAR_INFO' => $lang['admin_use_adar_info'],
		'L_USE_GAMELIB' => $lang['admin_use_gamelib'],
		'L_USE_GL_INFO' => $lang['admin_use_gl_info'],
		'L_USE_POINTS' => $lang['admin_use_points'],
		'L_USE_POINTS_INFO' => $lang['admin_use_pts_info'],
		'L_CASH' => $lang['admin_cash'],
		'L_USE_CASH' => $lang['admin_use_cash'],
		'L_USE_CASH_INFO' => $lang['admin_use_cash_info'],
		'L_CASH_DEFAULT_INFO' => $lang['admin_cash_default_info'],
		'L_USE_ALLOWANCE' => $lang['admin_use_allowance'],
		'L_USE_ALLOWANCE_INFO' => $lang['admin_use_allowance_info'],
		'L_USE_REWARDS' => $lang['admin_use_rewards'],
		'L_USE_REWARDS_INFO' => $lang['admin_use_rewards_info'],
		'L_GL_GAME_PATH' => $lang['admin_gl_game_path'],
		'L_GL_PATH_INFO' => $lang['admin_gl_path_info'],
		'L_GL_LIB_PATH' => $lang['admin_gl_lib_path'],
		'L_GL_LIB_INFO' => $lang['admin_gl_lib_info'],
		'L_GAMES_PER_PAGE' => $lang['admin_games_per_page'],
		'L_GAMES_PER_INFO' => $lang['admin_games_per_info'],
		'L_ADAR_SHOP_CONFIG' => $lang['admin_adar_config'],
		'L_ADAR_SHOP' => $lang['admin_adar_shop'],
		'L_ADAR_INFO' => $lang['admin_no_adar_info'],
			
		'L_MAX_CHARGE' => $lang['max_charge'],
		'L_INCREMENT' => $lang['increment'],
		'L_PATH' => $lang['path_for_games'],
		'L_REWARD' => $lang['bonus_for_games'],
		'L_HEIGHT' => $lang['game_height'],
		'L_WIDTH' => $lang['game_width'],
		'L_CASH_NAME' => $lang['cm_pts_name'],
		'L_JACKPOT' => $lang['use_jackpot'],
		'L_MAX_GAMBLE' => sprintf($lang['max_gamble_amount'], $board_config['points_name']),
		'L_CURRENT' => $lang['def_list_order'],
		'L_CURRENT_EXPLAIN' => $lang['main_pg_order'],
		'L_CURRENT_OPTION' => $lang['type_choose'],
		'L_CURRENT_OPTION_1' => $lang['games_played_A'],
		'L_CURRENT_OPTION_2' => $lang['games_played_D'],
		'L_CURRENT_OPTION_3' => $lang['new_add'],
		'L_CURRENT_OPTION_4' => $lang['old_add'],
		'L_CURRENT_OPTION_5' => $lang['bonus_A'],
		'L_CURRENT_OPTION_6' => $lang['bonus_D'],
		'L_CURRENT_OPTION_7' => $lang['cost_A'],
		'L_CURRENT_OPTION_8' => $lang['cost_D'],
		'L_CURRENT_OPTION_9' => $lang['proper_A'],
		'L_CURRENT_OPTION_10' => $lang['proper_D'],
		'L_CURRENT_OPTION_11' => $lang['jackpot_A'],
		'L_CURRENT_OPTION_12' => $lang['jackpot_D'],
		'L_USE_MAX_GAMES_PER_DAY' => $lang['max_games_played'],
		'L_MAX_PLAYED_COUNT' => $lang['max_games_played_desc'],
		'L_REQ_POST_COUNT' => $lang['req_post_count'],
		'L_POST_COUNT' => $lang['ify_how_many'],
		'L_REQ_USER_TIME' => $lang['req_mem_time'],
		'L_USER_TIME' => $lang['ify_how_long'],
		'L_CHALLENGE' => $lang['act_challenge'],
		'L_CHALLENGE_MSG' => $lang['admin_xtras_msg_text'],
		'L_CHALLENGE_SUB_MSG' => $lang['sub_chal_mess'],
		'L_TROPHY' => $lang['act_pm_trop_loss'],
		'L_TROPHY_MSG' => $lang['admin_xtras_msg_text_1'],
		'L_TROPHY_SUB_MSG' => $lang['sub_trop_loss_mess'],
		'L_SHOW_NEW' => $lang['show_new'],
		'L_SHOW_NEW_LIMIT' => $lang['ify_amt_to_show'],
		'L_BUTTON_LINK' => $lang['button_link_load_style'],
		'L_GAME_LENGTH' => $lang['new_game_length'],
		'L_GAME_LENGTH_EXPLAIN' => $lang['amt_days_show'],
		'L_POP_LIMIT' => $lang['pop_game_limit'],
		'L_POP_LIMIT_EXPLAIN' => $lang['game_req_to_show'],
		'L_USE_RATING_REWARD' => sprintf($lang['admin_use_rating_reward'], $board_config['points_name']),
		'L_USE_RATING_REWARD_VALUE' => sprintf($lang['admin_use_rating_reward_1'], $board_config['points_name']),
		'L_DAILY_GAME' => $lang['god_admin_one'],
		'L_RANDOM_DAILY' => $lang['god_admin_three'],
		'L_DAILY_ID' => $lang['god_admin_two'],
		'L_POPUP' => $lang['radio_popup'],
		'L_PARENT' => $lang['radio_parent'],

		'L_CONFIG_EXTRAS' => $lang['extra_toggle_values'],
		'L_ALLOW_GUEST' => $lang['allow_guest'],
		'L_USE_ONLINE' => $lang['act_games_online'],
		'L_DISABLE_CHEAT' => $lang['turn_cheat_off'],
		'L_SHOW_PROFILE' => $lang['show_trop_n_profile'],
		'L_SHOW_VIEW_TOPIC' => $lang['show_stats_n_topic'],
		'L_HOF_VIEWTOPIC' => $lang['show_hof_n_topic'],
		'L_PLAYERS_INDEX' => $lang['show_players_index'],
		'L_USE_SHOUTBOX' => $lang['admin_use_shoutbox'],
		'L_DISABLE_EVERYTHING' => $lang['disable_everything'],
		'L_DISABLE_WHY' => $lang['why_disable'],
		'L_DISABLE_TROPHY' => $lang['disable_trophy_page'],
		'L_DISABLE_COMMENTS' => $lang['disable_comments'],
		'L_DISABLE_GAMBLE' => $lang['disable_gamble_page'],
		'L_DISABLE_CALLENGES' => $lang['disable_chall_page'],
		'L_DISABLE_TOP5' => $lang['disable_top_five'],
		'L_USE_TROPHY' => $lang['use_trophy_king'],
		'L_USE_TROPHY_EXPLAIN' => $lang['why_use_trophy_king'],
		'L_DISABLE_SUBMIT_SCORES_M' => $lang['disable_score_submit_m'],
		'L_DISABLE_SUBMIT_SCORES_M_EXPLAIN' => $lang['why_disable_score_submit_m'],
		'L_DISABLE_SUBMIT_SCORES_G' => $lang['disable_score_submit_g'],
		'L_DISABLE_SUBMIT_SCORES_G_EXPLAIN' => $lang['why_disable_score_submit_g'],
		'L_FORCE_REGO' => $lang['admin_guest_view'],
		
		'S_MAX_CHARGE' => $new['ina_default_charge'],
		'S_INCREMENT' => $new['ina_default_increment'],
		'S_GAME_PATH' => $new['ina_default_g_path'],
		'S_REWARD' => $new['ina_default_g_reward'],
		'S_HEIGHT' => $new['ina_default_g_height'],
		'S_WIDTH' => $new['ina_default_g_width'],
		'S_CASH_NAME' => $new['ina_cash_name'],
		'S_JACKPOT' => $new['ina_jackpot_pool'],
		'S_MAX_GAMBLE' => $new['ina_max_gamble'],
		'S_CURRENT' => $current_setting,
		'CURRENT_OPTION' => $board_config['ina_default_order'],
		'S_USE_MAX_GAMES_PER_DAY_YES' => $use_max_games_per_day_yes,
		'S_USE_MAX_GAMES_PER_DAY_NO' => $use_max_games_per_day_no,			
		'S_MAX_PLAYED_COUNT' => $new['ina_max_games_per_day'],
		'S_USE_POSTS_YES' => $use_posts_yes,
		'S_USE_POSTS_NO' => $use_posts_no,			
		'S_POST_COUNT' => $new['ina_post_block_count'],
		'S_USE_TIME_YES' => $use_time_yes,
		'S_USE_TIME_NO' => $use_time_no,			
		'S_TIME_COUNT' => $new['ina_join_block_count'],
		'S_USE_CHALLENGE_YES' => $use_challenge_yes,
		'S_USE_CHALLENGE_NO' => $use_challenge_no,			
		'S_CHALLENGE_MSG' => $new['ina_challenge_msg'],
		'S_CHALLENGE_SUB_MSG' => $new['ina_challenge_sub'],
		'S_USE_TROPHY_YES' => $use_trophy_yes,
		'S_USE_TROPHY_NO' => $use_trophy_no,			
		'S_TROPHY_MSG' => $new['ina_pm_trophy_msg'],
		'S_TROPHY_SUB_MSG' => $new['ina_pm_trophy_sub'],
		'S_USE_NEWEST_YES' => $use_newest_yes,
		'S_USE_NEWEST_NO' => $use_newest_no,			
		'S_GAME_COUNT' => $new['ina_new_game_count'],
		'S_USE_BUTTON_YES' => $use_button_yes,
		'S_USE_BUTTON_NO' => $use_button_no,			
		'S_GAME_LIMIT' => $new['ina_new_game_limit'],
		'S_POP_LIMIT' => $new['ina_pop_game_limit'],
		'S_USE_RATING_REWARD_YES' => $use_rating_reward_yes,
		'S_USE_RATING_REWARD_NO' => $use_rating_reward_no,			
		'S_RATING_REWARD' => $new['ina_rating_reward'],
		'S_USE_DAILY_GAME_YES' => $use_daily_game_yes,
		'S_USE_DAILY_GAME_NO' => $use_daily_game_no,			
		'S_USE_DAILY_RANDOM_YES' => $use_daily_random_yes,
		'S_USE_DAILY_RANDOM_NO' => $use_daily_random_no,			
		'S_OPTIONS' => $options,

		'S_GUEST_PLAY_YES' => $ina_guest_play_yes,
		'S_GUEST_PLAY_NO' => $ina_guest_play_no,			
		'S_USE_ONLINE_YES' => $ina_use_online_yes,
		'S_USE_ONLINE_NO' => $ina_use_online_no,			
		'S_DISABLE_CHEAT_YES' => $ina_disable_cheat_yes,
		'S_DISABLE_CHEAT_NO' => $ina_disable_cheat_no,			
		'S_SHOW_VIEW_PROFILE_YES' => $ina_show_view_profile_yes,
		'S_SHOW_VIEW_PROFILE_NO' => $ina_show_view_profile_no,
		'S_SHOW_VIEW_TOPIC_YES' => $ina_show_view_topic_yes,			
		'S_SHOW_VIEW_TOPIC_NO' => $ina_show_view_topic_no,
		'S_HOF_VIEWTOPIC_YES' => $ina_hof_viewtopic_yes,			
		'S_HOF_VIEWTOPIC_NO' => $ina_hof_viewtopic_no,
		'S_PLAYERS_INDEX_YES' => $ina_players_index_yes,			
		'S_PLAYERS_INDEX_NO' => $ina_players_index_no,
		'S_USE_SHOUTBOX_YES' => $ina_use_shoutbox_yes,			
		'S_USE_SHOUTBOX_NO' => $ina_use_shoutbox_no,
		'S_DISABLE_EVERYTHING_YES' => $ina_disable_everything_yes,			
		'S_DISABLE_EVERYTHING_NO' => $ina_disable_everything_no,
		'S_DISABLE_TROPHY_YES' => $ina_disable_trophy_page_yes,
		'S_DISABLE_TROPHY_NO' => $ina_disable_trophy_page_no,			
		'S_DISABLE_COMMENTS_YES' => $ina_disable_comments_page_yes,
		'S_DISABLE_COMMENTS_NO' => $ina_disable_comments_page_no,			
		'S_DISABLE_GAMBLE_YES' => $ina_disable_gamble_page_yes,
		'S_DISABLE_GAMBLE_NO' => $ina_disable_gamble_page_no,			
		'S_DISABLE_CHALLENGES_YES' => $ina_disable_challenges_page_yes,
		'S_DISABLE_CHALLENGES_NO' => $ina_disable_challenges_page_no,			
		'S_DISABLE_TOP5_YES' => $ina_disable_top5_page_yes,
		'S_DISABLE_TOP5_NO' => $ina_disable_top5_page_no,			
		'S_USE_TROPHY_YES' => $ina_use_trophy_yes,
		'S_USE_TROPHY_NO' => $ina_use_trophy_no,			
		'S_DISABLE_SUBMIT_SCORES_M_YES' => $ina_disable_submit_scores_m_yes,
		'S_DISABLE_SUBMIT_SCORES_M_NO' => $ina_disable_submit_scores_m_no,			
		'S_DISABLE_SUBMIT_SCORES_G_YES' => $ina_disable_submit_scores_g_yes,
		'S_DISABLE_SUBMIT_SCORES_G_NO' => $ina_disable_submit_scores_g_no,			
		'S_FORCE_REGO_YES' => $ina_force_registration_yes,
		'S_FORCE_REGO_NO' => $ina_force_registration_no,			
		
		'S_USE_GKS_YES' => $use_gk_shop_yes,
		'S_USE_GKS_NO' => $use_gk_shop_no,
		'S_USE_GL_YES' => $use_gamelib_yes,
		'S_USE_GL_NO' => $use_gamelib_no,

		'S_GAMES_PATH' => $new['games_path'],
		'S_GAMELIB_PATH' => $new['gamelib_path'],
		'S_GAMES_PER_PAGE' => (!$new['games_per_page']) ? $board_config['posts_per_page'] : $new['games_per_page'],
			
		'S_CONFIG_ACTION' => append_sid('admin_activity.'.$phpEx))
	);		
}

//
// Generate the page
//
$template->pparse('body');

include('page_footer_admin.' . $phpEx);

?>
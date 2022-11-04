<?php		                              						   			  
/** 
*
* @package admin
* @version $Id: admin_ina_char.php,v 1.1.0 2006/02/10 22:19:01 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Games']['Character_Settings'] = $file;
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_activity_char.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_activity_char.' . $phpEx);

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
		
	<h1>' . $lang['amp_char_title'] . '</h1>
		
	<p>' . $lang['amp_char_title_explain'] . '</p>';
	
	echo '<table align="center" cellpadding="4" cellspacing="1" width="100%" class="forumline"><form name="save_char" method="post" action="admin_ina_char.'. $phpEx .'?mode=save&sid='. $userdata['session_id'] .'">';
	echo '<tr>';
	echo '<th colspan="2" class="thHead">'.$lang['amp_char_change_title_1'].'</td>';
	echo '</tr>';	
	echo '<tr>';
	echo '<td width="50%" class="row1"><b>'.$lang['amp_char_change_char'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $board_config['ina_char_change_char_cost'] .'" name="char_img" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_name'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $board_config['ina_char_change_name_cost'] .'" name="char_name" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_title'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $board_config['ina_char_change_title_cost'] .'" name="char_title" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_saying'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $board_config['ina_char_change_saying_cost'] .'" name="char_saying" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_gender'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $board_config['ina_char_change_gender_cost'] .'" name="char_gender" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_from'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $board_config['ina_char_change_from_cost'] .'" name="char_from" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_intrests'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $board_config['ina_char_change_intrests_cost'] .'" name="char_intrests" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_age'].'</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $board_config['ina_char_change_age_cost'] .'" name="char_age" size="10" /></td>';	
	echo '</tr>';							
	echo '<tr>';
	echo '<th colspan="2" class="thHead">'.$lang['amp_char_change_title_2'].'</th>';
	echo '</tr>';
	
	#color, shadow, glow, bold, italic, underline		
	$char_name_costs 	= explode(',', $board_config['ina_char_name_effects_costs']);
	$char_title_costs 	= explode(',', $board_config['ina_char_title_effects_costs']);
	$char_saying_costs 	= explode(',', $board_config['ina_char_saying_effects_costs']);	
	
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_name_c'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_name_costs[0] .'" name="name_cost_one" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_name_s'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_name_costs[1] .'" name="name_cost_two" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_name_g'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_name_costs[2] .'" name="name_cost_three" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_name_b'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_name_costs[3] .'" name="name_cost_four" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_name_i'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_name_costs[4] .'" name="name_cost_five" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_name_u'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_name_costs[5] .'" name="name_cost_six" size="10" /></td>';	
	echo '</tr>';	
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_title_c'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_title_costs[0] .'" name="title_cost_one" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_title_s'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_title_costs[1] .'" name="title_cost_two" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_title_g'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_title_costs[2] .'" name="title_cost_three" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_title_b'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_title_costs[3] .'" name="title_cost_four" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_title_i'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_title_costs[4] .'" name="title_cost_five" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td  class="row1"><b>'.$lang['amp_char_change_title_u'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_title_costs[5] .'" name="title_cost_six" size="10" /></td>';	
	echo '</tr>';	
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_saying_c'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_saying_costs[0] .'" name="saying_cost_one" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_saying_s'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_saying_costs[1] .'" name="saying_cost_two" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_saying_g'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_saying_costs[2] .'" name="saying_cost_three" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_saying_b'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_saying_costs[3] .'" name="saying_cost_four" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_saying_i'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_saying_costs[4] .'" name="saying_cost_five" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_saying_u'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $char_saying_costs[5] .'" name="saying_cost_six" size="10" /></td>';	
	echo '</tr>';								
	echo '<tr>';
	echo '<th colspan="2" class="thHead">'.$lang['amp_char_change_title_3'].'</th>';
	echo '</tr>';
	
	$viewtopic = (($board_config['ina_char_show_viewtopic'] == 1) ? 'checked="on"' : '');
	
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_viewtopic'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="viewtopic" '. $viewtopic .'></td>';	
	echo '</tr>';
	
	$viewprofile = (($board_config['ina_char_show_viewprofile'] == 1) ? 'checked="on"' : '');	
	
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_viewprofile'].':</b></td>';
	echo '<td class="row2"><input type="checkbox" name="viewprofile" '. $viewprofile .'></td>';	
	echo '</tr>';		
	echo '<tr>';
	echo '<th colspan="2" class="thHead">'.$lang['amp_char_change_title_4'].'</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="2" class="row2"><span class="gensmall">'. $lang['amp_char_change_warning'] .'</span></td>';
	echo '</tr>';	
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_per_game'].':</b></td>';
	echo '<td class="row2">	<input type="text" class="post" value="'. $board_config['ina_char_ge_per_game'] .'" name="per_game" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_per_score'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $board_config['ina_char_ge_per_beat_score'] .'" name="per_score" size="10" /></td>';	
	echo '</tr>';
	echo '<tr>';
	echo '<td class="row1"><b>'.$lang['amp_char_change_per_trophy'].':</b></td>';
	echo '<td class="row2"><input type="text" class="post" value="'. $board_config['ina_char_ge_per_trophy'] .'" name="per_trophy" size="10" /></td>';	
	echo '</tr>';						
	echo '<tr>';
	echo '<td align="center" colspan="2" class="catBottom"><input type="submit" value="'. $lang['Submit'] .'" class="mainoption" onclick="document.save_char.submit()" />&nbsp;&nbsp;<input type="reset" value="' . $lang['Reset'] . '" class="liteoption" /></td>';
	echo '</tr>';
	echo '</form></table>';
}
		
if ($mode == 'save')
{
	$change_char_cost				= intval($HTTP_POST_VARS['char_img']);
	$change_char_name				= intval($HTTP_POST_VARS['char_name']);	
	$change_char_title				= intval($HTTP_POST_VARS['char_title']);
	$change_char_saying				= intval($HTTP_POST_VARS['char_saying']);
	$change_char_gender				= intval($HTTP_POST_VARS['char_gender']);
	$change_char_location			= intval($HTTP_POST_VARS['char_from']);
	$change_char_intrests			= intval($HTTP_POST_VARS['char_intrests']);
	$change_char_age				= intval($HTTP_POST_VARS['char_age']);						

	$change_name_color_cost			= intval($HTTP_POST_VARS['name_cost_one']);
	$change_name_shadow_cost		= intval($HTTP_POST_VARS['name_cost_two']);
	$change_name_glow_cost			= intval($HTTP_POST_VARS['name_cost_three']);
	$change_name_bold_cost			= intval($HTTP_POST_VARS['name_cost_four']);
	$change_name_italic_cost		= intval($HTTP_POST_VARS['name_cost_five']);
	$change_name_underline_cost		= intval($HTTP_POST_VARS['name_cost_six']);
	
	$change_title_color_cost		= intval($HTTP_POST_VARS['title_cost_one']);
	$change_title_shadow_cost		= intval($HTTP_POST_VARS['title_cost_two']);
	$change_title_glow_cost			= intval($HTTP_POST_VARS['title_cost_three']);
	$change_title_bold_cost			= intval($HTTP_POST_VARS['title_cost_four']);
	$change_title_italic_cost		= intval($HTTP_POST_VARS['title_cost_five']);
	$change_title_underline_cost	= intval($HTTP_POST_VARS['title_cost_six']);
	
	$change_saying_color_cost		= intval($HTTP_POST_VARS['saying_cost_one']);
	$change_saying_shadow_cost		= intval($HTTP_POST_VARS['saying_cost_two']);
	$change_saying_glow_cost		= intval($HTTP_POST_VARS['saying_cost_three']);
	$change_saying_bold_cost		= intval($HTTP_POST_VARS['saying_cost_four']);
	$change_saying_italic_cost		= intval($HTTP_POST_VARS['saying_cost_five']);
	$change_saying_underline_cost	= intval($HTTP_POST_VARS['saying_cost_six']);		 
	
	$show_char_in_posts				= ($HTTP_POST_VARS['viewtopic'] == 'on') ? 1 : '';
	$show_char_in_profiles 			= ($HTTP_POST_VARS['viewprofile'] == 'on') ? 1 : '';
	
	$reward_per_game				= intval($HTTP_POST_VARS['per_game']);
	$reward_per_score				= intval($HTTP_POST_VARS['per_score']);
	$reward_per_trophy				= intval($HTTP_POST_VARS['per_trophy']);    

	$compiled_name_effects 			= $change_name_color_cost .','. $change_name_shadow_cost .','. $change_name_glow_cost .','. $change_name_bold_cost .','. $change_name_italic_cost .','. $change_name_underline_cost;
	$compiled_saying_effects 		= $change_saying_color_cost .','. $change_saying_shadow_cost .','. $change_saying_glow_cost .','. $change_saying_bold_cost .','. $change_saying_italic_cost .','. $change_saying_underline_cost;
	$compiled_title_effects 		= $change_title_color_cost .','. $change_title_shadow_cost .','. $change_title_glow_cost .','. $change_title_bold_cost .','. $change_title_italic_cost .','. $change_title_underline_cost;
	
	$q = array();
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_cost' WHERE config_name = 'ina_char_change_char_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_gender' WHERE config_name = 'ina_char_change_gender_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_age' WHERE config_name = 'ina_char_change_age_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_name' WHERE config_name = 'ina_char_change_name_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_location' WHERE config_name = 'ina_char_change_from_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_intrests' WHERE config_name = 'ina_char_change_intrests_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$reward_per_game' WHERE config_name = 'ina_char_ge_per_game';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$reward_per_score' WHERE config_name = 'ina_char_ge_per_beat_score';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$reward_per_trophy' WHERE config_name = 'ina_char_ge_per_trophy';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$show_char_in_posts' WHERE config_name = 'ina_char_show_viewtopic';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$show_char_in_profiles' WHERE config_name = 'ina_char_show_viewprofile';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_title' WHERE config_name = 'ina_char_change_title_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$change_char_saying' WHERE config_name = 'ina_char_change_saying_cost';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$compiled_name_effects' WHERE config_name = 'ina_char_name_effects_costs';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$compiled_title_effects' WHERE config_name = 'ina_char_title_effects_costs';";
	$q[] = "UPDATE ". CONFIG_TABLE ." SET config_value = '$compiled_saying_effects' WHERE config_name = 'ina_char_saying_effects_costs';";
	
	for ($x = 0; $x < count($q); $x++)
	{
		$db->sql_query($q[$x]);
	}
	
	message_die(GENERAL_MESSAGE, $lang['amp_char_settings_saved'] . '<br /><br />' . sprintf($lang['amp_char_settings_back'], '<a href="' . append_sid('admin_ina_char.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>'));
}
		
include('page_footer_admin.' . $phpEx);

?>
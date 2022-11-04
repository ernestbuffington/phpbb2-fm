<?php		                              						   			  
/***************************************************************************
 *                            admin_ina_bulk_add.php
 *                           ------------------------
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
	$module['Games']['Bulk_Add_New'] = $file;

	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_activity.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_activity.' . $phpEx);

if ( isset( $HTTP_POST_VARS['mode'] ) || isset( $HTTP_GET_VARS['mode'] ) )
{
	$mode = ( isset( $HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}
					
define("iNA_GAMES", $table_prefix .'ina_games');	
$link = append_sid("admin_ina_bulk_add.". $phpEx);
	
if($mode == "main" || !$mode)
{		
	echo $game_menu . "
</ul>
</div></td>
<td valign='top' width='78%'>
	<h1>" . $lang['bulk_add_title'] . "</h1>";
	echo "<p>" . $lang['admin_xtras_game_link_msg'] . "</p>";
	echo "<table width='100%' class='forumline' cellspacing='1' cellpadding='4' align='center'><form name='add_games' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead'>". $lang['bulk_add_title'] . "</th>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='row1'>". $lang['bulk_add_title_2'] . "</td>";
	echo "</tr>";		
	echo "<tr>";	
	echo "<td align='center' class='catBottom'><input type='hidden' name='mode' value='do_it'><input type='submit' class='mainoption' value='". $lang['bulk_add_button'] ."' onchange='add_games.edit_trophy.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
}
		
if($mode == "do_it")
{
		
	$game_dir 	= $phpbb_root_path . $board_config['ina_default_g_path'];
	$games 		= opendir($game_dir);
	$g = 0;

	while ($file = readdir($games)) 
	{			
		if (($file != ".") && ($file != "..") && ($file != "index.htm") && ($file != "index.html") && ($file != "Thumbs.db"))
		{									
			$q29 = "SELECT game_name
				FROM ". iNA_GAMES ."
				WHERE game_name = '$file'";
			$r29 = $db -> sql_query($q29);
							
			if (!$db -> sql_fetchrow($r29))
			{
				$reward = $charge = '';						
				$reward = $board_config['ina_default_g_reward'];
				$charge = $board_config['ina_default_charge'];
						
				$q2 = "INSERT INTO ". iNA_GAMES ." (game_id, game_name, proper_name, game_path, game_charge, game_bonus, game_flash, game_show_score, win_width, win_height, reverse_list, install_date, disabled) 
					VALUES ('', '". $file ."', '". $file ."', '". $board_config['ina_default_g_path'] . $file . "/"."', '". $charge ."', '". $reward ."', '1', '1', '". $board_config['ina_default_g_width'] ."', '". $board_config['ina_default_g_height'] ."', '0', '". time() ."', '1')";
				$r2 = $db -> sql_query($q2);							
				$g++;
			}	
		}
	}			
	
	if (!$g)
	{
		$default = $lang['admin_default_no_games'];
	}
	
	if($g == 1) 
	{
		message_die(GENERAL_MESSAGE, $lang['bulk_add_add_msg2'], $lang['bulk_add_add_success']);
	}
	
	if($g > 1) 
	{
		message_die(GENERAL_MESSAGE, str_replace("%g%", $g, $lang['bulk_add_add_msg1']), $lang['bulk_add_add_success']);
	}
}

include('page_footer_admin.' . $phpEx);
		
?>
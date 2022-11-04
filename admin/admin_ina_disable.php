<?php	                              						   			  
/***************************************************************************
 *                            admin_ina_disable.php
 *                            ----------------------
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
	$module['Games']['Hide/Show'] = $file;
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
	
$link = append_sid("admin_ina_disable.". $phpEx);
	
if($mode == "main" || !$mode)
{
	echo $game_menu . "
</ul>
</div></td>
<td valign='top' width='78%'>
	<h1>". $lang['a_disable_1']."</h1><p></p>";
	echo "<table width='100%' class='forumline' cellspacing='1' cellpadding='4' align='center'><form name='do_it' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead' colspan='2'>". $lang['a_disable_2']."</th>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='50%' class='row1'><b>". $lang['a_disable_3']."</td>";
	echo "<td class='row2'><select name='game_choice'>";
	echo "<option selected value=''>". $lang['a_disable_4'] ."</option>";

	$q = "SELECT *
		FROM ". iNA_GAMES ."
		ORDER BY game_id ASC";
	$r			= $db -> sql_query($q);
	
	while($row	= $db -> sql_fetchrow($r))
	{
		$g_name	= $row['game_name'];
		$g_id	= $row['game_id'];
		$g_dis	= $row['disabled'];
	
		if($g_dis == "2")
		{
			$new_name = "($g_id) $g_name*";
		}
		else
		{
			$new_name = "($g_id) $g_name";
		}
		
		echo "<option value='$g_id'>$new_name</option>";					
	}
			
	echo "</select></td>";
	echo "</tr>";
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='hide_show'><input type='submit' class='mainoption' value='". $lang['a_disable_5'] ."' onchange='document.do_it.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
}

if($mode == "hide_show")
{
	$id	= $_POST['game_choice'];
	
	if(!$id)
	{
		message_die(GENERAL_ERROR, $lang['a_disable_6'] ."<a href='". $link ."'>". $lang['a_disable_7'], $lang['ban_error']);
	}
			
	$q = "SELECT disabled
		FROM ". iNA_GAMES ."
		WHERE game_id = '$id'";
	$r			= $db -> sql_query($q);
	$row 		= $db -> sql_fetchrow($r);	
	
	$disabled	= $row['disabled'];
			
	if($disabled == "2")
	{
		$q = "UPDATE ". iNA_GAMES ."
			SET disabled = '1'
		  	WHERE game_id = '$id'";
		$r	= $db -> sql_query($q);
	
		message_die(GENERAL_MESSAGE, $lang['a_disable_8'] ."<a href='". $link ."'>". $lang['a_disable_9'] , $lang['a_ban_22']);		
	}
	elseif($disabled == "1")
	{
		$q = "UPDATE ". iNA_GAMES ."
			SET disabled = '2'
		  	WHERE game_id = '$id'";
		$r	= $db -> sql_query($q);
	
		message_die(GENERAL_MESSAGE, $lang['a_disable_10'] ."<a href='". $link ."'>". $lang['a_disable_11'], $lang['a_ban_22']);	
	}
	else
	{
		message_die(GENERAL_ERROR, $lang['a_disable_13'] ."<a href='". $link ."'>". $lang['a_disable_12'], $lang['ban_error']);				
	}
}
			
include('page_footer_admin.' . $phpEx);

?>
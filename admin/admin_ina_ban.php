<?php                         						   			  
/***************************************************************************
 *                             admin_ina_ban.php
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
	$module['Games']['Ban_control'] = $file;
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

define('INA_BAN', $prefix .'ina_ban');
$link = append_sid("admin_ina_ban.". $phpEx);
	
if($mode == "main" || !$mode)
{
	echo $game_menu . "
</ul>
</div></td>
<td valign='top' width='78%'>
	<h1>". $lang['a_ban_1'] . "</h1>
	<p></p>";
	
	echo "<table cellpadding='4' cellspacing='1' align='center' class='forumline' width='100%'><form name='ban_someone' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead' colspan='2'>". $lang['a_ban_1'] . "</th>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". $lang['a_ban_9'] . ":</b></td>";
	echo "<td class='row2'><input name='ban_id' type='text' size='25' value='' class='post' /></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='row1'><b>". $lang['a_ban_1'] . ":</b></td>";
	echo "<td class='row2'><input name='ban_name' type='text' size='25' value='' class='post' /></td>";
	echo "</tr>";
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='ban'><input type='submit' class='mainoption' value='". $lang['a_ban_11'] ."' onchange='document.ban_someone.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
	echo "<br />";	
	
	echo "<table width='100%' class='forumline' cellspacing='1' cellpadding='4' align='center'><form name='un_ban' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead' colspan='2'>". $lang['a_ban_5'] . "</th>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". $lang['a_ban_3'] . ":</b></td>";
	echo "<td class='row2'><select name='unban_id'>";
	echo "<option selected value=''>". $lang['a_ban_4'] ."</option>";
	
	$q = "SELECT id
		  FROM ". INA_BAN ."
		  WHERE id <> '0'";
	$r			= $db -> sql_query($q);
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$id = $row['id'];
		echo "<option value='". $id ."'>$id</option>";					
	}
			
	echo "</select></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='row1'><b>". $lang['a_ban_5'] . ":</b></td>";
	echo "<td class='row2'><select name='unban_name'>";
	echo "<option selected value=''>". $lang['a_ban_6'] ."</option>";
	
	$q = "SELECT username
		  FROM ". INA_BAN ."
		  WHERE username <> ''";
	$r			= $db -> sql_query($q);
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$name = $row['username'];
		echo "<option value='". $name ."'>$name</option>";					
	}
			
	echo "</select></td>";
	echo "</tr>";		
	echo "<tr>";	
	echo "<td colspan='2' align='center' class='catBottom'><input type='hidden' name='mode' value='unban'><input type='submit' class='mainoption' value='". $lang['a_ban_7'] ."' onchange='document.un_ban.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
	echo "<br />";
}
		
if($mode == "ban")
{
	$ban_id		= $_POST['ban_id'];
	$ban_name 	= $_POST['ban_name'];
		
	if(($ban_id) && (!is_numeric($ban_id)))
	{
		message_die(GENERAL_ERROR, $lang['a_ban_12'] ."<a href=". $link .">". $lang['a_ban_13'], $lang['ban_error']);				
	}
			
	if((!$ban_id) && (!$ban_name))	
	{
		message_die(GENERAL_ERROR, $lang['a_ban_14'] ."<a href=". $link .">". $lang['a_ban_13'], $lang['ban_error']);	
	}
					
	if($ban_id)
	{
		$q = "SELECT user_id
			FROM ". USERS_TABLE ."
			WHERE user_id = '$ban_id'";
		$r			= $db -> sql_query($q);
		$row 		= $db -> sql_fetchrow($r);	
		$user_check = $row['user_id'];
		
		if(!$user_check)
		{
			message_die(GENERAL_ERROR, $lang['a_ban_15'] . $ban_id . $lang['a_ban_16'] ."<a href=". $link .">". $lang['a_ban_17'], $lang['ban_error']);				
		}
	
		$q = "SELECT id
			FROM ". INA_BAN ."
			WHERE id = '$ban_id'";
		$r			= $db -> sql_query($q);
		$row 		= $db -> sql_fetchrow($r);					
		$user_check = $row['id'];
		
		if($user_check)
		{
			message_die(GENERAL_ERROR, $lang['a_ban_15'] . $ban_id . $lang['a_ban_18'] ."<a href=". $link .">". $lang['a_ban_17'], $lang['ban_error']);				
		}
				
		$q = "INSERT INTO ". INA_BAN ." 
			VALUES ('$ban_id', '')";
		$r = $db -> sql_query($q);			
		
		message_die(GENERAL_MESSAGE, $lang['a_ban_19']. $ban_id . $lang['a_ban_20']  ."<a href=". $link .">". $lang['a_ban_21'], $lang['a_ban_22']);	
	}

	if($ban_name)
	{
		$q = "SELECT username
			FROM ". USERS_TABLE ."
			WHERE username = '$ban_name'";
		$r			= $db -> sql_query($q);
		$row 		= $db -> sql_fetchrow($r);	
		$user_check = $row['username'];
		
		if(!$user_check)
		{
			message_die(GENERAL_ERROR, $lang['a_ban_23'] . $ban_name . $lang['a_ban_16'] ."<a href=". $link .">". $lang['a_ban_17'], $lang['ban_error']);				
		}
	
		$q = "SELECT username
			FROM ". INA_BAN ."
			WHERE username = '$ban_name'";
		$r			= $db -> sql_query($q);
		$row 		= $db -> sql_fetchrow($r);					
		$user_check = $row['username'];
		
		if($user_check)
		{
			message_die(GENERAL_ERROR, $lang['a_ban_23'] . $ban_name . $lang['a_ban_18'] ."<a href=". $link .">". $lang['a_ban_17'], $lang['ban_error']);				
		}
				
		$q = "INSERT INTO ". INA_BAN ."
			VALUES ('', '$ban_name')";
		$r = $db -> sql_query($q);
								
		message_die(GENERAL_MESSAGE, $lang['a_ban_24'] . $ban_name . $lang['a_ban_20'] ."<a href=". $link .">". $lang['a_ban_21'], $lang['a_ban_22']);	
	}						
}
		
if($mode == "unban")
{
	$unban_id 	= $_POST['unban_id'];
	$unban_name = $_POST['unban_name'];
	
	if($unban_id)
	{
		$q = "DELETE FROM ". INA_BAN ."
			WHERE id = '$unban_id'";
		$r = $db -> sql_query($q);			
		
		message_die(GENERAL_MESSAGE, $lang['a_ban_25'] . $unban_id . $lang['a_ban_20'] ."<a href=". $link .">". $lang['a_ban_21'], $lang['a_ban_22']);	
	}

	if($unban_name)
	{
		$q = "DELETE FROM ". INA_BAN ."
			WHERE username = '$unban_name'";
		$r = $db -> sql_query($q);		  			
		
		message_die(GENERAL_MESSAGE, $lang['a_ban_26'] . $unban_name . $lang['a_ban_20'] ."<a href=". $link .">". $lang['a_ban_21'], $lang['a_ban_22']);	
	}
		
	if((!$unban_id) && (!$unban_name))	
	{
		message_die(GENERAL_ERROR, $lang['a_ban_27'] ."<a href=". $link .">". $lang['a_ban_28'], $lang['ban_error']);	
	}			
}
	
include('page_footer_admin.' . $phpEx);

?>
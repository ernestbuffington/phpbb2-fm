<?php		                              						   			  
/***************************************************************************
 *                            admin_ina_category.php
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
	$module['Games']['Categories'] = $file;
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
$link = append_sid("admin_ina_category.". $phpEx);
	
if($mode == "main" || !$mode)
{
	echo $game_menu . "
</ul>
</div></td>
<td valign='top' width='78%'>

<h1>". $lang['Manage_categorys']."</h1><p></p>";
	echo "<table width='100%' class='forumline' cellspacing='1' cellpadding='4' align='center'><form name='add_cat' action='$link' method='post'>";
	echo "<tr>";
	echo "<th colspan='2' class='thHead'>". $lang['admin_cat_2']."</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan='2' class='row2'><span class='gensmall'>". $lang['Items_required']."</span></td>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". $lang['admin_cat_3'].": *</b></td>";
	echo "<td class='row2'><input type='text' name='new_cat' class='post' value='' /></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='row1'><b>". $lang['admin_cat_52'].": *</b></td>";
	echo "<td class='row2'><input type='text' name='new_cat_desc' class='post' value='' size='30' /></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='row1'><b>". $lang['admin_cat_53']."</td>";
	echo "<td class='row2'><input type='text' name='new_cat_img' class='post' value='' size='30' /></td>";
	echo "</tr>";	
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='add_new_cat'><input type='submit' class='mainoption' value='". $lang['admin_cat_4'] ."' onchange='document.add_cat.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
	echo "<br />";	

	echo "<table align='center' class='forumline' width='100%' cellpadding='4' cellspacing='1'><form name='edit_cat' action='$link' method='post'>";
	echo "<tr>";
	echo "<th colspan='2' class='thHead'>". $lang['admin_cat_5'] . "</td>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". $lang['admin_cat_6'].":</b></td>";
	echo "<td class='row2'><select name='edit_category'>";
	
	$q = "SELECT *
		FROM ". INA_CATEGORY ."
		WHERE cat_id > '0'
		ORDER BY cat_name ASC";
	$r			= $db -> sql_query($q);
	
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$cat 	= $row['cat_name'];
		$cat_id = $row['cat_id'];	
		echo "<option value='". $cat_id ."'>$cat</option>";					
	}
			
	echo "</select></td>";
	echo "</tr>";
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='edit_exis_cat'><input type='submit' class='mainoption' value='". $lang['admin_cat_8'] ."' onchange='document.edit_cat.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
	echo "<br />";
	
	echo "<table align='center' class='forumline' width='100%' cellpadding='4' cellspacing='1'><form name='delete_cat' action='$link' method='post'>";
	echo "<tr>";
	echo "<th colspan='2' class='thHead'>". $lang['admin_cat_9']."</th>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td colspan='2' class='row2'><span class='gensmall'>". $lang['admin_cat_9_explain']."</span></td>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>" .$lang['admin_cat_10'].":</b></td>";
	echo "<td class='row2'><select name='delete_category'>";
	
	$q = "SELECT *
		FROM ". INA_CATEGORY ."
		WHERE cat_id > '0'
		ORDER BY cat_name ASC";
	$r			= $db -> sql_query($q);
	
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$cat 	= $row['cat_name'];
		$cat_id = $row['cat_id'];	
		echo "<option value='". $cat_id ."'>$cat</option>";					
	}
			
	echo "</select></td>";
	echo "</tr>";
	echo "<tr>";	
	echo "<td align='center' class='catBottom' colspan='2'><input type='hidden' name='mode' value='delete_exis_cat'><input type='submit' class='mainoption' value='". $lang['admin_cat_11'] ."' onchange='document.delete_cat.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
	echo "<br />";
	
	echo "<table align='center' class='forumline' width='100%' cellpadding='4' cellspacing='1'><form name='assign_game' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead' colspan='2'>". $lang['admin_cat_12']."</th>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". $lang['admin_cat_13']."</td>";
	echo "<td class='row2'><select name='category_assignment'>";
	echo "<option selected value=''>". $lang['admin_cat_7'] ."</option>";
	echo "<option value='delete_game_from_cats'>". $lang['admin_cat_14'] ."</option>";	
	
	$q = "SELECT *
		FROM ". INA_CATEGORY ."
		WHERE cat_id > 0
		ORDER BY cat_name ASC";
	$r			= $db -> sql_query($q);
	
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$cat 	= $row['cat_name'];
		$cat_id = $row['cat_id'];	
		echo "<option value='". $cat_id ."'>$cat</option>";					
	}
			
	echo "</select></td>";
	echo "</tr>";	
	echo "<tr>";
	echo "<td class='row1'><b>". $lang['admin_cat_15'].":</b></td>";
	echo "<td class='row2'><select name='game_assignment'>";
	echo "<option selected value=''>". $lang['admin_cat_16'] ."</option>";
	
	$q = "SELECT *
		FROM ". iNA_GAMES ."
		WHERE game_id > '0'
		ORDER BY proper_name ASC";
	$r			= $db -> sql_query($q);
	
	while($row 	= $db -> sql_fetchrow($r))
	{			
		$game_name 	= $row['proper_name'];
		$game_id 	= $row['game_id'];		
		$exists		= $row['cat_id'];
	
		if($exists > 0)
		{		
			echo "<option value='". $game_id ."'>$game_name</option>";
		}
	}
			
	echo "</select></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='row1'><b>". $lang['admin_cat_51'] . ":</b></td>";
	echo "<td class='row2'><select name='game_assignment2'>";
	echo "<option selected value=''>". $lang['admin_cat_16'] ."</option>";
	
	$q = "SELECT *
		FROM ". iNA_GAMES ."
		WHERE game_id > '0'
		ORDER BY proper_name ASC";
	$r			= $db -> sql_query($q);
	
	while($row 	= $db -> sql_fetchrow($r))
	{			
		$game_name 	= $row['proper_name'];
		$game_id 	= $row['game_id'];		
		$exists		= $row['cat_id'];
	
		if($exists == 0)
		{		
			echo "<option value='". $game_id ."'>$game_name</option>";
		}
	}
			
	echo "</select></td>";
	echo "</tr>";		
	echo "<tr>";	
	echo "<td class='catBottom' colspan='2' align='center'><input type='hidden' name='mode' value='assign_game_cat'><input type='submit' class='mainoption' value='". $lang['admin_cat_17'] ."' onchange='document.assign_game.submit()' /></td>";		
	echo "</tr>";					
	echo "</form></table>";
	echo "<br />";
	
	echo "<table align='center' class='forumline' width='100%' cellpadding='4' cellspacing='1'><form name='check_this_game' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead' colspan='2'>". $lang['admin_cat_18']."</td>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". $lang['admin_cat_19'].":</b></td>";
	echo "<td class='row2'><select name='check_assignment'>";
	echo "<option selected value=''>". $lang['admin_cat_16'] ."</option>";
		
	$q = "SELECT proper_name, game_id
		FROM ". iNA_GAMES ."
		WHERE cat_id <> '0'
		ORDER BY proper_name ASC";
	$r			= $db -> sql_query($q);
	
	while($row	= $db -> sql_fetchrow($r))
	{
		echo "<option value='". $row['game_id'] ."'>". $row['proper_name'] ."</option>";					
	}			
	echo "</select></td>";
	echo "</tr>";
	echo "<tr>";	
	echo "<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='check_game_cat'><input type='submit' class='mainoption' value='". $lang['admin_cat_20'] ."' onchange='document.check_this_game.submit()' /></td>";		
	echo "</tr>";					
	echo "</form></table>";
	echo "<br />";
}
		
if($mode == "check_game_cat")
{
	$assignment_check 	= $_POST['check_assignment'];

	if(!$assignment_check)
	{
		message_die(GENERAL_ERROR, str_replace("%L%", $link, $lang['admin_cat_21']));		
	}	
		
	$q = "SELECT proper_name, cat_id
		FROM ". iNA_GAMES ."
		WHERE game_id = '". $assignment_check ."'";
	$r			= $db -> sql_query($q);
	$row 		= $db -> sql_fetchrow($r);
	
	$q = "SELECT cat_name
		FROM ". INA_CATEGORY ."
		WHERE cat_id = '". $row['cat_id'] ."'";
	$r			= $db -> sql_query($q);
	$row1 		= $db -> sql_fetchrow($r);
		
	$cat_name 	= $row1['cat_name'];	
			
	message_die(GENERAL_MESSAGE, $lang['admin_cat_30'] . $row['proper_name'] . $lang['admin_cat_31'] . $cat_name . $lang['admin_cat_32'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27'], $lang['admin_cat_24']);		
}
		
if($mode == "assign_game_cat")
{
	$cat 	= $_POST['category_assignment'];
	$game 	= $_POST['game_assignment'];
	$game1 	= $_POST['game_assignment2'];	
	
	if(!$cat || (!$game && !$game1))
	{
		message_die(GENERAL_ERROR, $lang['admin_cat_33'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_34']);			
	}
			
	if($game && $game1)
	{
		message_die(GENERAL_ERROR, $lang['admin_cat_33'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_34']);			
	}			
			
	if($game && !$game1)
	{			
		$q = "SELECT *
			FROM ". iNA_GAMES ."
		  	WHERE game_id = '$game'";
		$r		= $db -> sql_query($q);
		$row 	= $db -> sql_fetchrow($r);	
		
		$exists	= $row['cat_id'];
		
		if(($exists > 0) && ($cat != "delete_game_from_cats"))
		{
			$q = "UPDATE ". iNA_GAMES ."
				SET cat_id = '$cat'
		  		WHERE game_id = '$game'";
			$r = $db -> sql_query($q);
	
			message_die(GENERAL_ERROR, $lang['admin_cat_35'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27']);				
		}

		if(($exists == 0) && ($game) && ($cat) && ($cat != "delete_game_from_cats"))
		{
			$q = "UPDATE ". iNA_GAMES ."
				SET cat_id = '$cat'
		  		WHERE game_id = '$game'";
			$r = $db -> sql_query($q);
	
			message_die(GENERAL_ERROR, $lang['admin_cat_36'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27']);					
		}	
		
		if($cat == "delete_game_from_cats")
		{
			$q = "UPDATE ". iNA_GAMES ."
				SET cat_id = '0'
		  		WHERE game_id = '$game'";
			$r = $db -> sql_query($q);
	
			message_die(GENERAL_ERROR, $lang['admin_cat_37'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27']);									
		}
	}
			
	if($game1 && !$game)
	{			
		$q = "SELECT *
			FROM ". iNA_GAMES ."
			WHERE game_id = '$game1'";
		$r		= $db -> sql_query($q);
		$row 	= $db -> sql_fetchrow($r);
			
		$exists	= $row['cat_id'];
		
		if(($exists > 0) && ($cat != "delete_game_from_cats"))
		{
			$q = "UPDATE ". iNA_GAMES ."
				SET cat_id = '$cat'
		  		WHERE game_id = '$game1'";
			$r = $db -> sql_query($q);
	
			message_die(GENERAL_ERROR, $lang['admin_cat_35'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27']);				
		}

		if((!$exists) && ($game1) && ($cat) && ($cat != "delete_game_from_cats"))
		{
			$q = "UPDATE ". iNA_GAMES ."
				SET cat_id = '$cat'
		  		WHERE game_id = '$game1'";
			$r = $db -> sql_query($q);
	
			message_die(GENERAL_ERROR, $lang['admin_cat_36'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27']);					
		}	
		
		if($cat == "delete_game_from_cats")
		{
			$q = "UPDATE ". iNA_GAMES ."
				SET cat_id = '0'
		  		WHERE game_id = '$game1'";
			$r = $db -> sql_query($q);
	
			message_die(GENERAL_ERROR, $lang['admin_cat_37'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27']);									
		}
	}			
}
		
if($mode == "add_new_cat")
{
	$cat = $_POST['new_cat'];
	$des = $_POST['new_cat_desc'];
	$img = $_POST['new_cat_img'];		
	
	if(!$cat || !$des) 
    { 
    	message_die(GENERAL_ERROR, $lang['admin_cat_38'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_34']); 
    } 
			
	$q = "SELECT cat_name
		FROM ". INA_CATEGORY ."
		WHERE cat_name = '$cat'";
	$r		= $db -> sql_query($q);
	$row 	= $db -> sql_fetchrow($r);
		
	$exists	= $row['cat_name'];
			
	if($exists)
	{
		message_die(GENERAL_ERROR, $lang['admin_cat_39'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_34']);
	}
			
	$q = "INSERT INTO ". INA_CATEGORY ."
		VALUES ('', '". str_replace("\'", "''", $cat) ."', '". str_replace("\'", "''", $des) ."', '". $img ."')";
	$r	= $db -> sql_query($q);
	
	message_die(GENERAL_MESSAGE, $lang['admin_cat_40'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27']);										
}
		
if($mode == "edit_exis_cat")
{
	$cat = $_POST['edit_category'];
	
	if(!$cat)
	{
		message_die(GENERAL_ERROR, $lang['admin_cat_41'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_34']);
	}
				
	$q = "SELECT *
		FROM ". INA_CATEGORY ."
		WHERE cat_id = '$cat'";
	$r		= $db -> sql_query($q);
	$row 	= $db -> sql_fetchrow($r);	
	
	$edit_it	= $row['cat_name'];
	$edit_de	= $row['cat_desc'];
	$edit_im	= $row['cat_img'];
	
	echo $game_menu . "
</ul>
</div></td>
<td valign='top' width='78%'>
	<h1>". $lang['Manage_categorys']."</h1><p></p>";
	echo "<table align='center' class='forumline' width='100%' cellpadding='4' cellspacing='1'><form name='save' action='$link' method='post'>";
	echo "<tr>";
	echo "<th class='thHead' colspan='2'>". $lang['admin_cat_43']."</td>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width='38%' class='row1'><b>". $lang['admin_cat_10'].":</b></td>";
	echo "<td class='row2'><input type='text' name='edited_cat' class='post' value='$edit_it' /></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='row1'><b>". $lang['admin_cat_52'].":</b></td>";
	echo "<td class='row2'><input type='text' name='edited_desc' class='post' value='$edit_de' size='30' /></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class='row1'><b>". $lang['admin_cat_53']."</td>";
	echo "<td class='row2'><input type='text' name='edited_img' class='post' value='$edit_im' size='30' /></td>";
	echo "</tr>";		
	echo "<tr>";	
	echo "		<td align='center' colspan='2' class='catBottom'><input type='hidden' name='mode' value='save_changes'><input type='hidden' name='id' value='$cat'><input type='hidden' name='original' value='$edit_it'><input type='submit' class='mainoption' value='". $lang['Submit'] ."' onchange='document.save.submit()' /></td>";
	echo "</tr>";					
	echo "</form></table>";	
	echo "<br />";						
}

if($mode == "save_changes")
{
	$cat 	= $_POST['edited_cat'];
	$id 	= $_POST['id'];
	$desc	= $_POST['edited_desc'];
	$img	= $_POST['edited_img'];
	$orig	= $_POST['original'];
	
	if(!$cat || !$desc)
	{
		message_die(GENERAL_ERROR, $lang['admin_cat_45'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_34']);
	}
	
	if($cat != $orig)
	{
		$q = "SELECT cat_name
		 	FROM ". INA_CATEGORY ."
		 	WHERE cat_name = '". $cat ."'";
		$r		= $db -> sql_query($q);
		$row 	= $db -> sql_fetchrow($r);	
		
		$exists	= $row['cat_name'];
			
		if($exists)
		{
			message_die(GENERAL_ERROR, $lang['admin_cat_46'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_34']);
		}
	}
				
	$q = "UPDATE ". INA_CATEGORY ."
		SET cat_name = '". str_replace("\'", "''", $cat) ."', cat_desc = '". str_replace("\'", "''", $desc) ."', cat_img = '". $img ."'
		WHERE cat_id = '". $id ."'";
	$r	= $db -> sql_query($q);
	
	message_die(GENERAL_MESSAGE, $lang['admin_cat_47'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27']);	
}
								
if($mode == "delete_exis_cat")
{
	$cat = $_POST['delete_category'];
	
	if(!$cat)
	{
		message_die(GENERAL_ERROR, $lang['admin_cat_48'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27']);
	}	
			
	$q = "SELECT cat_name
		FROM ". INA_CATEGORY ."
		WHERE cat_id = '$cat'";
	$r		= $db -> sql_query($q);
	$row 	= $db -> sql_fetchrow($r);	
	
	$check	= $row['cat_name'];
				
	$q = "DELETE FROM ". INA_CATEGORY ."
		WHERE cat_id = '$cat'";
	$r	= $db -> sql_query($q);
	
	$q = "UPDATE ". iNA_GAMES ."
		SET cat_id = '0'
		WHERE cat_id = '$cat'";
	$r = $db -> sql_query($q);
	
	message_die(GENERAL_MESSAGE, $lang['admin_cat_49'] . $check . $lang['admin_cat_50'] ."<a href='". $link ."'>". $lang['admin_cat_26'] ."</a>". $lang['admin_cat_27']);
}
			
include('page_footer_admin.' . $phpEx);

?>
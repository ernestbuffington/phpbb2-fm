<?php
/***************************************************************************
 *                              activity_search.php
 *                            ----------------------
 *		Version			: 1.1.0
 *		Email			: austin@phpbb-amod.com
 *		Site			: http://phpbb-amod.com
 *		Copyright		: aUsTiN-Inc 2003/5 
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}
	
$user_id = $userdata['user_id'];

if($board_config['ina_guest_play'] == 2)
{
	if(!$userdata['session_logged_in'] && $user_id == ANONYMOUS)
	{
		$header_location = ( @preg_match("/Microsoft|WebSTAR|Xitami/", getenv("SERVER_SOFTWARE")) ) ? "Refresh: 0; URL=" : "Location: ";
		header($header_location . append_sid("login.$phpEx?redirect=activity.$phpEx", true));
		exit;
	}
}

UpdateSessions();		
BanCheck();
UpdateActivitySession();
				
if($HTTP_GET_VARS['mode'] != 'game')		
{
	UpdateUsersPage($userdata['user_id'], $_SERVER['REQUEST_URI']);
}
			
$game_cost = $board_config['points_name'];

$order_by = AdminDefaultOrder();			
	
if($board_config['use_gamelib'] == 1)
{
	$gamelib_link = "<div align=\"center\"><span class=\"copyright\">" . $lang['game_lib_link'] . "</span></div>";
}
else if($board_config['use_gamelib'] == 0)
{
	$gamelib_link = '';
}
			
if($HTTP_POST_VARS['mode'] == 'search')
{
	$template->set_filenames(array(
		'body' => 'amod_files/activity_search_body.tpl') 
	);
	make_jumpbox('viewforum.'.$phpEx);
		
	$query = $HTTP_POST_VARS['query'];
	$type = $HTTP_POST_VARS['top_left'];
	$extra = $HTTP_POST_VARS['top_right'];
	$cat = $HTTP_POST_VARS['category'];
	$wc = $HTTP_POST_VARS['wildcard'];		
	
	if(!($query)) 
	{
		message_die(GENERAL_MESSAGE, 'Please specify a search query.');
	}
	
	/* Gonna be a mess! Building the where clause */
	if($wc)
	{
		$wildcard 	= "LIKE";
		$p			= "%";
	}
	elseif(!$wc)
	{
		$wildcard 	= " = ";
		$p			= "";			
	}
	else
	{
		$wildcard 	= "LIKE";		
		$p			= "%";	
	}
		
	$where_clause = "WHERE game_id > '0'";
	
	if($type == "flash") 
	{
		$where_clause .= " AND game_flash = '1'";
	}
	elseif($type == "glib")
	{
		$where_clause .= " AND game_use_gl = '1'";
	}
	else
	{
		$where_clause .= " AND game_flash = '1'";		
	}
		
	if($extra == "desc")
	{
		$where_clause .= " AND game_desc ". $wildcard ." '". $p . $query . $p ."'";
	}
	elseif($extra == "name")
	{
		$where_clause .= " AND game_name ". $wildcard ." '". $p . $query . $p ."'";
	}
	elseif($extra == "reverse")
	{
		$where_clause .= " AND reverse_list = '1'";
	}
	else
	{
		$where_clause .= " AND game_desc ". $wildcard ." '". $p . $query . $p ."'";
	}
	
	$i = 0;
	$q2 = "SELECT * 
		FROM ". iNA_GAMES ."
		$where_clause"; 
	$r2 = $db -> sql_query($q2);  
	
	while($row = $db -> sql_fetchrow($r2))
	{	
		$game_name = eregi_replace($query, "<b>". $query . "</b>", $row['proper_name']);
		$game_desc = eregi_replace($query, "<b>". $query . "</b>", $row['game_desc']);
		$game_img = GameArrayLink($row['game_id'], $row['game_parent'], $row['game_popup'], $row['win_width'], $row['win_height'], '3%SEP%'. CheckGameImages($row['game_name'], $row['proper_name']), ''); 
        
        $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2']; 
				
		$template->assign_block_vars("search_results", array(
			'ROW_CLASS'	=> $row_class, 		
			'IMAGE'	=> $game_img,
			'DESC' => $game_desc,			
			'NAME' => $game_name)
		);
		$i++;		
	}	
	$db->sql_freeresult($r2);
}
else
{		 
	$template->set_filenames(array(
		'body' => 'amod_files/activity_search_body.tpl') 
	);
	make_jumpbox('viewforum.'.$phpEx);

	$template->assign_block_vars('search_switch', array());
						
	$template->assign_vars(array(
		'SEARCH_TITLE' => $lang['search_title'],
		'L_TOP_LEFT' => $lang['search_option_title_1'],
		'L_TOP_RIGHT' => $lang['search_option_title_2'],
		'L_TL_OPTION_1'	=> $lang['search_option_flash'],
		'L_TL_OPTION_2'	=> $lang['search_option_glib'],
		'L_TR_OPTION_1'	=> $lang['search_option_desc'],
		'L_TR_OPTION_2'	=> $lang['search_option_name'],
		'L_TR_OPTION_3'	=> $lang['search_option_reverse'],
		'L_QUERY' => $lang['search_query'],
		'L_WILDCARD' => $lang['search_use_wildcard'],
		'L_CATEGORY_TITLE' => $lang['search_category'],
		'L_CATEGORY_NONE' => $lang['search_category_none'])			
	);			
}	

$template->pparse('body');

?>
<?php
/***************************************************************************
 *                              activity_online.php
 *                             ---------------------
 *		Version			: 1.1.0
 *		Email			: austin@phpbb-amod.com
 *		Site			: http://phpbb-amod.com
 *		Copyright		: aUsTiN-Inc 2003/5 
 *
 ***************************************************************************/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}
			
$template->set_filenames(array(
	'activity_online_section' => 'amod_files/activity_online_body.tpl')
);
					
$expired = time() - 300;

$q =  "SELECT COUNT(*) AS total
	FROM ". INA_SESSIONS ." 
	WHERE playing = '0'
		AND playing_time >= '". $expired ."'"; 
$r		= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);

$playing_guests = $row['total'];
				
if ( $playing_guests == 0 )
{
	$message3 		= $lang['online_no_guests'];
	$message4 		= '';
	$playing_guests = '';		
}
else if ( $playing_guests == 1 )
{
	$message3 = $lang['online_g_one_1'];
	$message4 = $lang['online_g_one_2'];							
}
else
{
	$message3 = $lang['online_g_1'];
	$message4 = $lang['online_g_2'];							
}
			
$q =  "SELECT COUNT(*) AS total
	FROM ". INA_SESSIONS ." 
	WHERE playing = '1'
		AND playing_time >= '". $expired ."'"; 
$r		= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);

$playing_members = $row['total'];
				
if ( $playing_members == 0 )
{
	$message1 			= $lang['online_no_members'] ;
	$message2 			= '';
	$playing_members 	= '';		
}
else if ( $playing_members == 1 )
{
	$message1 = $lang['online_m_one_1'];
	$message2 = $lang['online_m_one_2'];							
	}
else
{
	$message1 = $lang['online_m_1'];
	$message2 = $lang['online_m_2'];							
}

$q =  "SELECT COUNT(*) AS total
	FROM ". USERS_TABLE ." 
	WHERE ( user_session_page = ". PAGE_ACTIVITY ." 
		OR user_session_page = ". PAGE_PLAYING_GAMES ." )
		AND user_session_time >= '". $expired ."'
		AND user_allow_viewonline = '0'"; 
$r		= $db->sql_query($q); 
$row	= $db->sql_fetchrow($r);

$playing_hidden = $row['total'];
				
if ( $playing_hidden == 1 )
{
	$message5 = $lang['online_no_hidden'];		
}
else if ( $playing_hidden == 1 )
{
	$message5 = $lang['online_one_hidden'];							
}
else
{
	$message5 = str_replace('%n%', $playing_hidden, $lang['online_x_hidden']);
}
									
$template->assign_block_vars('playing_games', array(
	'TOTAL_PLAYING'			=> $playing_total,
	'TOTAL_M_PLAYING'		=> $playing_members,
	'TOTAL_G_PLAYING'		=> $playing_guests,								
	'ONLINE_TITLE'			=> $lang['online_title_bar'],		
	'CURRENTLY_PLAYING1'	=> $message1,
	'CURRENTLY_PLAYING2'	=> $message2,
	'CURRENTLY_PLAYING3'	=> $message3,
	'CURRENTLY_PLAYING4'	=> $message4 .'<br /><a href="' . append_sid('activity.'.$phpEx.'?page=whos_where') . '" class="mainmenu">'. $lang['whos_where_link'] .'</a><br />',
	'CURRENTLY_PLAYING5'	=> $message5,			
	'MAIN_COLOR1'	 		=> '[ <span style="color: #'. $theme['fontcolor2'] .'">'. $lang['online_viewing_games'] .'</span> ]',
	'MAIN_SEPERATOR' 		=> '<b> :: </b>',					
	'MAIN_COLOR2'	 		=> '[ <span style="color: #'. $theme['fontcolor3'] .'">'. $lang['online_playing_games'] .'</span> ]') 
);
	
$q =  "SELECT *
	FROM ". INA_SESSIONS ."
	WHERE playing = 1			   
		AND playing_time >= '". $expired ."'"; 
$r			= $db->sql_query($q); 

while ($row	= $db->sql_fetchrow($r) )
{
	$playing_id	= $row['playing_id'];
	
	if ($userdata['user_level'] == ADMIN) 
	{
		$admin_hidden = '';
	}
	if ($userdata['user_level'] != ADMIN) 
	{
		$admin_hidden = "AND user_allow_viewonline = '1'";
	}
		
	$q1 =  "SELECT username, user_session_page, user_allow_viewonline
		FROM ". USERS_TABLE ."
		WHERE user_id = '". $playing_id ."'
		$admin_hidden"; 
	$r1		= $db->sql_query($q1); 
	$row	= $db->sql_fetchrow($r1);		
	
	$playing_user 	= $row['username'];
	$playing_where 	= $row['user_session_page'];
	$playing_hidden = $row['user_allow_viewonline'];
						
	if ( (!$playing_hidden) && ($userdata['user_level'] == ADMIN) ) 
	{
		$playing_user = '<i>'. $playing_user .'</i>';
	}
		
	$color = '';
	if ($playing_where == PAGE_ACTIVITY)
	{
		$playing_user 	= '<b>'. $playing_user .'</b>';
		$color 			= $theme['fontcolor2'];
	}
	elseif ($playing_where == PAGE_PLAYING_GAMES)
	{
		$playing_user 	= '<b>'. $playing_user .'</b>';
		$color 			= $theme['fontcolor3'];
	}

	if ($playing_id != ANONYMOUS)
	{
		$username_link	= '<a href="'. append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $playing_id) .'" class="nav"><span style="color:#'. $color .'">'. $playing_user .'</span></a>';
	}
				
	$template->assign_block_vars('playing', array(
		'USERNAME'				=> $username_link,
		'USER_NUMBER'	 		=> '&nbsp;',		
		'MAIN_SEPERATOR' 		=> '&nbsp;') 
	);						
}
			
$template->assign_var_from_handle('ACTIVITY_ONLINE_SECTION', 'activity_online_section');

?>
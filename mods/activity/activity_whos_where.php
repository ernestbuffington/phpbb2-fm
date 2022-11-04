<?php
/***************************************************************************
 *                             activity_whos_where.php
 *                            -------------------------
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

//
// Start Restriction Checks 
//
BanCheck();
//
// End Restriction Checks 
//

$template->set_filenames(array(
	'body' => 'amod_files/activity_whos_where_body.tpl') 
);
make_jumpbox('viewforum.'.$phpEx);

$template -> assign_block_vars('main', array(
	'TITLE_ONE' => $lang['whos_where_title_1'],
	'TITLE_TWO'	=> $lang['whos_where_title_2'],
	'TITLE_THREE' => $lang['whos_where_title_3'],
	'TITLE_FOUR' => $lang['whos_where_contact_onsite'],
	'TITLE_FIVE' => $lang['whos_where_contact_offsite'],			
	'LINK' => '<a href="' . append_sid('index.'.$phpEx) .'" class="nav">'. $lang['Forum_Index'] .'</a> -> <a href="' . append_sid('activity.'.$phpEx) . '" class="nav">'. $lang['game_list'] .'</a>')
);
	
if ( $userdata['user_level'] == ADMIN ) 
{
	$admin_where = '';
}
if ( $userdata['user_level'] != ADMIN ) 
{
	$admin_where = "AND user_allow_viewonline = '1'";
}
		
$i = 0;
$q =  "SELECT playing_id
	FROM ". INA_SESSIONS ."
	GROUP BY playing_id";    
$r       	= $db -> sql_query($q);    
while($row 	= $db -> sql_fetchrow($r)) 
{ 
	$q1 =  "SELECT *   
		FROM ". USERS_TABLE ."   
	   	WHERE user_id = '". $row['playing_id'] ."'";    
	$r1    	= $db -> sql_query($q1);    
	$row1 	= $db -> sql_fetchrow($r1);
			
	$q2 =  "SELECT *   
		FROM ". iNA_GAMES ."   
	   	WHERE game_id = '". $row1['ina_game_playing'] ."'";    
	$r2    	= $db -> sql_query($q2);    
	$row2 	= $db -> sql_fetchrow($r2);
			
	if ( $row1['user_id'] != ANONYMOUS ) 
	{
		$link = "<a href='profile.". $phpEx ."?mode=viewprofile&u=". $row1['user_id'] ."&sid=". $userdata['session_id'] ."' class='nav'>". $row1['username'] ."</a>";
	}
			
	if ( $row1['user_id'] == ANONYMOUS ) 
	{
		$link = $lang['top_five_12'];
	}
			
	if ( $row1['user_allow_viewonline'] == 0 ) 
	{
		$link = '<i>'. $link .'</i>';
	}
		
	if ( ($row1['user_session_page'] == PAGE_ACTIVITY) && ($row1['ina_game_playing'] == 0) )
	{
		$located = $lang['whos_where_viewing'];
	}
	elseif ( ( $row1['user_session_page'] == PAGE_PLAYING_GAMES ) && ( $row1['ina_game_playing'] > 0 ) )
	{
		$located = GameArrayLink($row2['game_id'], $row2['game_parent'], $row2['game_popup'], $row2['win_width'], $row2['win_height'], '3%SEP%'. $row2['proper_name'], '');
	}
	elseif ( $row1['user_session_page'] == PAGE_ACTIVITY )
	{
		$located = $lang['whos_where_viewing'];						
	}
	else
	{
		$located = $lang['whos_where_viewing_idle'];			
	}
		
	if ( !$located ) 
	{
		message_die(GENERAL_MESSAGE, $lang['whos_where_no_members']);
	}	
	
	$msn = ($row1['user_msnm']) ? '<a href="mailto: '. $row1['user_msnm'] .'"><img src="'. $images['icon_msnm'] .'" alt="'. $lang['MSNM'] .'" title="'. $lang['MSNM'] .'" /></a>' : '';
	$yim = ($row1['user_yim']) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target='. $row1['user_yim'] .'&amp;.src=pg"><img src="'. $images['icon_yim'] .'" alt="'. $lang['YIM'] .'" title="'. $lang['YIM'] .'" /></a>' : '';
	$aim = ($row1['user_aim']) ? '<a href="aim:goim?screenname='. $row1['user_aim'] .'&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] .'" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" /></a>' : '';
	$icq = ($row1['user_icq']) ? '<a href="http://wwp.icq.com/scripts/contact.dll?msgto='. $row1['user_icq'] .'"><img src="' . $images['icon_icq'] .'" alt="'. $lang['ICQ'] .'" title="' . $lang['ICQ'] .'" /></a>' : '';	   
	$www = ($row1['user_website']) ? '<a href="'. $row1['user_website'] .'" target="_userwww"><img src="'. $images['icon_www'] . '" alt="'. $lang['Visit_website'] .'" title="'. $lang['Visit_website'] .'" /></a>' : '';
	$mailto = ($board_config['board_email_form']) ? append_sid("profile.$phpEx?mode=email&amp;". POST_USERS_URL .'='. $row1['user_id']) : 'mailto:'. $row1['user_email'];			
	$mail = ($row1['user_email']) ? '<a href="'. $mailto .'"><img src="'. $images['icon_email'] .'" alt="'. $lang['Send_email'] .'" title="'. $lang['Send_email'] .'" /></a>' : '';
	$pmto = append_sid("privmsg.$phpEx?mode=post&amp;". POST_USERS_URL ."=$row1[user_id]");
	$pm = '<a href="'. $pmto .'"><img src="'. $images['icon_pm'] .'" alt="'. $lang['Send_private_message'] .'" title="'. $lang['Send_private_message'] .'" /></a>';
	$pro = append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL ."=$row1[user_id]");
	$profile = '<a href="'. $pro .'"><img src="'. $images['icon_profile'] .'" alt="'. $lang['Profile'] .'" title="'. $lang['Profile'] .'" /></a>';		
	
	if ( $row1['user_id'] == ANONYMOUS )
	{
		$msn = '';
		$yim = '';
		$aim = '';
		$icq = '';
		$www = '';
		$mailto = '';
		$mail = '';
		$pmtp = '';
		$pm = '';
		$pro = '';
		$profile = '';
	}
			
	$row_class 	= ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
	$template->assign_block_vars('rows', array(
		'ROW_CLASS'	=> $row_class,
		'ONSITE'	=> $pm .' '. $profile,
		'OFFSITE'	=> $www .' '. $msn .' '. $yim .' '. $aim .' '. $icq .' '. $mail,
		'NAME'		=> '&nbsp;'. $link,
		'NUMBER'	=> $i + 1,			
		'WHERE'		=> ' '. $located)
	);
	$i++;
}

$template->pparse('body');

?>
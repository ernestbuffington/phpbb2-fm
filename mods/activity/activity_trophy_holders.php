<?php
/***************************************************************************
 *                            activity_trophy_holders.php
 *                           -----------------------------
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
	'body' => 'amod_files/activity_trophy_holders_body.tpl') 
);
make_jumpbox('viewforum.'.$phpEx);
							
$template->assign_vars(array(															
	'L_POSITION' => $lang['trophy_count_1'],
	'L_TROPHIES' => $lang['trophy_count_2'],
	'L_USER_SEARCH' => $lang['trophy_count_3'],
	'L_PM_PROFILE' => $lang['trophy_count_4'],
	'L_LINK' => $lang['t_holder_link_name'],
	'U_LINK' => append_sid('activity.'.$phpEx.'?page=trophy'))
);
								
$sql = "SELECT *
	FROM ". USERS_TABLE ."
	WHERE user_trophies > 0
	ORDER BY user_trophies DESC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user trophies.', '', __LINE__, __FILE__, $sql);
} 

$i = 0;
while($row = $db -> sql_fetchrow($result))
{ 
	$pm = ( $row['user_id'] == ANONYMOUS ) ? '' : '<a href="'. append_sid("privmsg.$phpEx?mode=post&amp;". POST_USERS_URL ."=" . $row['user_id']) .'"><img src="'. $images['icon_pm'] .'" alt="'. $lang['Send_private_message'] .'" title="'. $lang['Send_private_message'] .'" /></a>';
	$profile = ( $row['user_id'] == ANONYMOUS ) ? '' : '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL ."=" . $row['user_id']) .'"><img src="'. $images['icon_profile'] .'" alt="'. $lang['Profile'] .'" title="'. $lang['Profile'] .'" /></a>';		
	
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars("top_trophies", array(															
		'ROW_CLASS'	=> $row_class,
		'POSITION'	=> $i + 1,			
		'TROPHIES'	=> $row['user_trophies'],						
		'USER_SEARCH' => '<a href="' . append_sid('activity.'.$phpEx.'?page=trophy_search&amp;user=' . $row['user_id']) . '">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>',
		'PM_PROFILE' => $pm . ' ' . $profile)
	);
	$i++;			
}				
$db->sql_freeresult($result);

$template->pparse('body');

?>
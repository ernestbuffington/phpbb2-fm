<?php
/***************************************************************************
 *                              arcade.php
 *                            --------------
 *		Version			: 1.1.0
 *		Email			: austin@phpbb-amod.com
 *		Site			: http://phpbb-amod.com
 *		Copyright		: aUsTiN-Inc 2003/5 
 *
 ***************************************************************************/

define('IN_PHPBB', TRUE);
$phpbb_root_path = './';
include($phpbb_root_path .'extension.inc');
include($phpbb_root_path .'common.'. $phpEx);
include($phpbb_root_path .'includes/functions_amod_plus.'. $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ACTIVITY);
init_userprefs($userdata);
//
// End session management
//
	
$v3_session = (isset($HTTP_POST_VARS['sessdo'])) ? htmlspecialchars($HTTP_POST_VARS['sessdo']) : '';
$error_path	= 'activity.'. $phpEx .'?sid='. $userdata['session_id'];

if ($v3_session != '')
{		
	$game_name 	= (isset($HTTP_POST_VARS['gamename'])) ? addslashes(stripslashes($HTTP_POST_VARS['gamename'])) : '';
	$micro_one 	= (isset($HTTP_POST_VARS['microone'])) ? $HTTP_POST_VARS['microone'] : '';
	$score 		= (isset($HTTP_POST_VARS['score'])) ? intval($HTTP_POST_VARS['score']) : '';
	$fake_key 	= (isset($HTTP_POST_VARS['fakekey'])) ? $HTTP_POST_VARS['fakekey'] : '';

	switch($v3_session)
	{
		case 'sessionstart' :		
			echo '&connStatus=1&initbar='. $game_name .'&val=x';
			exit();			
		break;
		
		case 'permrequest' :		
			echo '&validate=1&microone='. $score .'|'. $fake_key .'&val=x';
			exit();			
	 	break;
		
		case 'burn' :		
			$data 	= explode('|', $micro_one);
			$game	= trim(addslashes(stripslashes($data[1])));
			$score 	= $data[0];

		echo '<form method="post" name="v3arcade" action="newscore.php">';
		echo '<input type="hidden" name="score" value="'. $score .'">';
		echo '<input type="hidden" name="game_name" value="'. $game .'">';
		echo '</form>';
		echo '<script type="text/javascript">';
		echo 'window.onload = function(){document.v3arcade.submit()}';
		echo '</script>';
		exit();		
	 	break;
	}
}
else
{
	header('Location: '. $error_path);
}

?>
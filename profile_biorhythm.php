<?php
/** 
*
* @package phpBB2
* @version $Id: profile_biorhythm.php,v 1.0.2 2/3/2005 2:24 AM mj Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect('login.'.$phpEx.'?redirect=profile_biorhythm.'.$phpEx); 
	exit; 
} 


//
// Check if we already have a date to work with,
// if not send to profile for the user to enter one
//
if ( $userdata['user_birthday'] == 999999 )
{
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("profile.$phpEx?mode=editprofile&ucp=profile_info") . '">')
	);
	
	$message = $lang['bio_enter_birthday'] . '<br /><br />' . sprintf($lang['bio_click_enter_birthday'], '<a href="' . append_sid('profile.'.$phpEx.'?mode=editprofile&ucp=profile_info') . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message); 
}


//
// Start output of page
//
$page_title = $lang['Biorhythm'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'profile_biorhythm_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx); 

$template->assign_vars(array(		
	'L_TITLE' => $page_title,
	'IMAGE' => '<img src="profile_biorhythm_img.'.$phpEx.'" alt="' . $lang['Biorhythm'] . '" title="' . $lang['Biorhythm'] . '" />',)
);


//
// Generate the page
//
include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
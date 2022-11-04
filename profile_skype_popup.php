<?php
/** 
*
* @package phpBB2
* @version $Id: profile_skype_popup.php,v 2.0.5 2006/09/25 10:48:44 Exp $
* @copyright (c) 2006 HAPPYTEC.at
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


$sqldata = get_userdata($HTTP_GET_VARS[POST_USERS_URL]);
if (!$sqldata || empty($sqldata['user_id']) || $sqldata['user_id'] == ANONYMOUS || $sqldata['user_id'] == 1 )
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
}

if (!empty($sqldata['user_skype'])) 
{ 
  	$meldunga = '';
  	$template->assign_block_vars('skype', array());
}
else
{
 	$meldunga = $lang['skype_no'];
}

$page_title = $lang['skype_seitentitel']; 
$gen_simple_header = TRUE;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'profile_popup_skype.tpl')
);


$template->assign_vars(array( 
    'PAGE_TITLE' => $page_title,
    'L_CLOSE_WINDOW' => $lang['Close_window'],
		
    'add' => $lang['skype_add'], 
    'call' => $lang['skype_call'], 
    'userinfo' => $lang['skype_userinfo'], 
    'chat' => $lang['skype_chat'], 
    'sendfile' => $lang['skype_sendfile'], 
    'voicemail' => $lang['skype_voicemail'],
    
    'user_name' => $sqldata['username'],
    'user_skype' => $sqldata['user_skype'],
    'http_skype' => prepare_skype_http($sqldata['user_skype']),
    
    'meldunga' => $meldunga,
    'user_id' => $userid,
    
    'skype_oben' => sprintf($lang['skype_oben'], $sqldata['username']),
    'skype_unten' => $lang['skype_unten'])
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
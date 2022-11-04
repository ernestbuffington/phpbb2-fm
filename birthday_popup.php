<?php
/** 
*
* @package phpBB
* @version $Id: birthday_popup.php,v 1.5.6 2003/03/15 10:16:30 niels Exp $
* @copyright (c) 2003 Niels Chr. Rød
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
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

$gen_simple_header = TRUE; 
$page_title = $lang['Greeting_Messaging']; 
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

$year = create_date('Y', time(), $board_config['board_timezone']);
$date_today = create_date('Ymd', time(), $board_config['board_timezone']);
$user_birthday = realdate('md', $userdata['user_birthday']); 
$user_birthday2 = (($year . $user_birthday < $date_today) ? $year + 1 : $year) . $user_birthday; 
$l_greeting = ($user_birthday2 == $date_today) ? sprintf ( $lang['Birthday_greeting_today'], date('Y') - realdate('Y', $userdata['user_birthday']) ) : sprintf ( $lang['Birthday_greeting_prev'],  date('Y') - realdate('Y', $userdata['user_birthday']), realdate(str_replace('Y', '', $lang['DATE_FORMAT']), $userdata['user_birthday']) ); 

$template->set_filenames(array( 
      'body' => 'privmsgs_popup.tpl')
); 

$template->assign_vars(array( 
      'L_CLOSE_WINDOW' => $lang['Close_window'], 
      'L_MESSAGE' => $l_greeting)
); 

$template->pparse('body'); 

include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 

?>
<?php
/** 
*
* @package admin
* @version $Id: admin_user_recent_logins.php,v 1.0.0.1 2004/10/29 17:49:33 acydburn Exp $
* @copyright (c) 2003 Antony Bailey
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	 
define('IN_PHPBB', true); 

if( !empty($setmodules) ) 
{ 
   $filename = basename(__FILE__); 
   $module['Users']['Recent_Logins'] = $filename; 
   return; 
} 

$phpbb_root_path = '../'; 
require($phpbb_root_path . 'extension.inc'); 
require('./pagestart.' . $phpEx); 

$template->set_filenames(array( 
   'body' => 'admin/user_recent_logins_body.tpl') 
); 

$template->assign_vars(array(  
   'L_RECENT_LOGINS' => $lang['Recent_Logins'], 
   'L_RECENT_LOGIN_EXPLAIN' => $lang['Recent_Login_Explain'], 
   'L_USERNAME' => $lang['Username'], 
   'L_DAYS_SINCE_LOGIN' => $lang['Days_since_login'])
); 

$sql = "SELECT user_id, username, user_lastlogon, user_level
		FROM " . USERS_TABLE . " 
		WHERE user_lastlogon > (" . time()  . " - 604800) 
			AND user_id > 0
		ORDER BY user_lastlogon DESC"; 
if ( ! ($result = $db->sql_query($sql)) ) 
{ 
   message_die(GENERAL_ERROR, 'Could not query users login table', '', __LINE__, __FILE__, $sql); 
} 

$i = 0;
while( $row = $db->sql_fetchrow($result) ) 
{ 
	$row['username'] = username_level_color($row['username'], $row['user_level'], $row['user_id']);

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

   	$template->assign_block_vars('user_row', array( 
 		'ROW_CLASS' => $row_class,
     	'COUNT' => $i + 1, 
     	'USERNAME' => '<a href="' . append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '" class="genmed">' . $row['username'] . '</a>', 
     	'DAYS_SINCE_LOGIN' => number_format((($row['user_lastlogon'] - time()) / 86400) * -1, 2))
	); 
	$i++;	
} 
$db->sql_freeresult($result);

// 
// Generate the page 
// 
$template->pparse('body'); 

include('./page_footer_admin.'.$phpEx); 

?>
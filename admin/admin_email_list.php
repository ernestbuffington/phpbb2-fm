<?php
/** 
*
* @package admin
* @version $Id: admin_email_list.php,v 1.51.2.9 2004/11/18 17:49:33 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Email']['Email_list'] = $filename;
	return;
}

//
// Load default header
//
$phpbb_root_path = "../";
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);


//
// Generate page
//
$template->set_filenames(array(
	'body' => 'admin/email_list_body.tpl')
);

$template->assign_vars(array(
	'L_ADMIN_USERS_LIST_MAIL_TITLE' => $lang['Email_list'],
	'L_ADMIN_USERS_LIST_MAIL_EXPLAIN' => $lang['Admin_Users_List_Mail_Explain'],
	'L_USERNAME' => $lang['Username'],
	'L_EMAIL' => $lang['Email'])
);

// Count users
$sql = "SELECT user_id
	FROM " . USERS_TABLE . " 
	WHERE user_id > 0";
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not count total users", "", __LINE__, __FILE__, $sql);
}
$total_users = $db->sql_numrows($result);

$query_result = mysql_query("SELECT user_id, user_level, username, user_email FROM " . USERS_TABLE . " WHERE user_id > 0 ORDER BY user_email"); 

while( $row = $db->sql_fetchrow($query_result) )
{
	$userrow[] = $row;
}
$db->sql_freeresult($query_result);

for ($i = 0; $i < $total_users; $i++)
{
	if (empty($userrow[$i]))
	{
		break;
	}

	$userrow[$i]['username'] = username_level_color($userrow[$i]['username'], $userrow[$i]['user_level'], $userrow[$i]['user_id']);

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
	$template->assign_block_vars('userrow', array(
		'ROW_CLASS' => $row_class,
		'NUMBER' => $i + 1,
		'USERNAME' => $userrow[$i]['username'],		
		'EMAIL' => $userrow[$i]['user_email'],
			
		'U_ADMIN_USER' => append_sid('admin_users.'.$phpEx.'?mode=edit&amp;' . POST_USERS_URL . '=' . $userrow[$i]['user_id'])) 
	);
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
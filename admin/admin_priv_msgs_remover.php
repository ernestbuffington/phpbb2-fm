<?php
/** 
*
* @package admin
* @version $Id: admin_priv_msgs_remover.php,v 1.0.0 2005/08/04 thoul Exp $
* @copyright (c) 2005 Thoul
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Mods & Hacks']['PM Remover'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);


//
// Include language
//
$language = $board_config['default_lang'];
if ( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_priv_msgs.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_priv_msgs.' . $phpEx);


if( isset($_POST['submit']) )
{
	// Grab form data and user information.
	$user = ( isset($_POST['username']) ) ? $_POST['username'] : '';
	$this_user = get_userdata($user);
	unset($user);
	$user_id = intval($this_user['user_id']);
	$mark_list = array();

	$sql = "SELECT privmsgs_id
		FROM " . PRIVMSGS_TABLE . "
		WHERE privmsgs_from_userid = $user_id 
			OR privmsgs_to_userid = $user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not select all users private messages', '', __LINE__, __FILE__, $sql);
	}

	// This little bit of code directly from the private messaging section.
	while ( $row_privmsgs = $db->sql_fetchrow($result) )
	{
		$mark_list[] = $row_privmsgs['privmsgs_id'];
	}
	$db->sql_freeresult($result);
			
	if ( sizeof($mark_list) )
	{
		$delete_sql_id = implode(', ', $mark_list);
				
		$delete_text_sql = "DELETE FROM " . PRIVMSGS_TEXT_TABLE . "
			WHERE privmsgs_text_id IN ($delete_sql_id)";
		if ( !$db->sql_query($delete_text_sql) )
		{
			message_die(GENERAL_ERROR, 'Could not delete private message text', '', __LINE__, __FILE__, $delete_text_sql);
		}
				
		$delete_sql = "DELETE FROM " . PRIVMSGS_TABLE . "
			WHERE privmsgs_id IN ($delete_sql_id)";
		if ( !$db->sql_query($delete_sql) )
		{
			message_die(GENERAL_ERROR, 'Could not delete private message info', '', __LINE__, __FILE__, $delete_sql);
		}
	}

	message_die(GENERAL_MESSAGE, $lang['PMR_SUCCESS'] . '<br /><br />' . sprintf($lang['Click_return_pm_remover'], '<a href="' . append_sid('admin_priv_msgs_remover.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>'));
}
else
{
	// Do the template dance.
	$template->set_filenames(array(
		'body' => 'admin/priv_msgs_remover_body.tpl')
	);
	
	$template->assign_vars(array(
		'L_REMOVER_TITLE' => $lang['PMR_TITLE'],
		'L_REMOVER_EXPLAIN' => $lang['PMR_EXPLAIN'],
		'L_LOOK_UP' => $lang['Look_up_User'],
		'L_USERNAME' => $lang['Select_a_User'],
		'L_FIND_USERNAME' => $lang['Find_username'],

		'U_SEARCH_USER' => append_sid($phpbb_root_path . 'search'.$phpEx.'?mode=searchuser'), 
		'S_FORM_ACTION' => append_sid('admin_priv_msgs_remover.'.$phpEx))
	);
	
	$template->pparse('body');
}

include('./page_footer_admin.'.$phpEx);

?>
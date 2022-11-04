<?php
/** 
*
* @package admin
* @version $Id: admin_user_reset_level.php,v 2003 Exp $
* @copyright (c) 2003 John McKernan
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

// modules
if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Users']['Reset_user_level'] = $file;
	
	return;
}

// declarations
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

// verify username
if( isset($HTTP_POST_VARS['username']) || isset($HTTP_GET_VARS['username']) )
{
	$reset_username = ( isset($HTTP_POST_VARS['username']) ) ? $HTTP_POST_VARS['username'] : $HTTP_GET_VARS['username'];

	// don't reset yourself
	if ( $reset_username == $userdata['username'] )
	{
		$msg = $lang['Reset_noself'] . '<br /><br />' . sprintf($lang['Click_return_reset_user_level'], '<a href="' . append_sid('admin_user_reset_level.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $msg);
	}

	// make sure user exists
	if( !$this_userdata = get_userdata($reset_username) )
	{
		$msg = $lang['No_such_user'] . '<br /><br />' . sprintf($lang['Click_return_reset_user_level'], '<a href="' . append_sid('admin_user_reset_level.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $msg);
	}

	if( $HTTP_POST_VARS['reset'] )
	{
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_level = " . USER . " 
			WHERE username = '" . str_replace("\'", "''", $reset_username) . "'";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Unable to update the database", "Error", __LINE__, __FILE__, $sql);
		}

		$msg = sprintf($lang['Reset_success'], $reset_username) . '<br /><br />' . sprintf($lang['Click_return_reset_user_level'], '<a href="' . append_sid('admin_user_reset_level.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $msg);

		$s_hidden_fields = '<input type="hidden" name="username" value="' . $reset_username . '" />';
	}
}
else
{
	$reset_username = $s_hidden_fields = '';
}

$template->set_filenames(array(
	'body' => 'admin/user_reset_level_body.tpl')
);

$template->assign_vars(array(
	'RESETUSER' => $reset_username,
	
	'L_RESETUSER_TITLE' => $lang['Reset_user_level'],
	'L_RESETUSER_EXPLAIN' => $lang['Reset_user_explain'],
	'L_RESETUSER_HEADER' => $lang['Reset_user_header'],
	'L_USERNAME'=> $lang['Username'],
	'L_RESET' => $lang['Reset'],

	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'S_USER_ACTION' => append_sid("admin_user_reset_level.$phpEx"),
	'S_USER_SELECT' => $select_list)
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
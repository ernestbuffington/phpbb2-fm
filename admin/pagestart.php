<?php
/** 
*
* @package admin
* @version $Id: pagestart.php,v 1.1.2.6 2003/05/06 20:18:42 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if (!defined('IN_PHPBB'))
{
	die("Hacking attempt");
}

define('IN_ADMIN', true);

// Include files
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if (!$userdata['session_logged_in'])
{
	redirect(append_sid("login.$phpEx?redirect=admin/index.$phpEx", true));
}
else if ( $userdata['user_level'] != ADMIN )
{
	message_die(GENERAL_MESSAGE, $lang['Not_admin']);
}

if ($HTTP_GET_VARS['sid'] != $userdata['session_id'])
{
	redirect("index.$phpEx?sid=" . $userdata['session_id']); 
}

if ($board_config['admin_login'])
{
	if (!$userdata['session_admin'])
	{
	   redirect(append_sid("login.$phpEx?redirect=admin/index.$phpEx&admin=1", true));
	}
}

if ( $board_config['AJAXed_user_list'] && $board_config['AJAXed_status'] )
{
	include($phpbb_root_path . 'includes/sajax.'.$phpEx);
	$sajax->request_type = 'POST';
	$sajax->init('ajax.'.$phpEx);
	$sajax->export('build_user_list');
	$sajax->handle_client_request();
	$sajax->show_javascript();
	$template->assign_vars(array('AJAXED_USER_LIST' => ( $board_config['AJAXed_user_list'] ) ? ' onkeyup="ub(this.value);" autocomplete="off"' : '',
		'AJAXED_USER_LIST_BOX' => ( $board_config['AJAXed_user_list'] ) ? '<br /><div id="user_list" style="position:absolute"></div>' : '',
		'L_AJAXED_LOADING' => $lang['AJAXed_loading'],
		'L_AJAXED_ERROR' => $lang['AJAXed_error'])
	);
}

include('./page_menu_admin.'.$phpEx);

if (empty($no_page_header))
{
	// Not including the pageheader can be neccesarry if META tags are
	// needed in the calling script.
	include('./page_header_admin.'.$phpEx);
}

?>
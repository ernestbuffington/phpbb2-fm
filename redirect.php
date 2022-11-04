<?php
/** 
*
* @package phpBB
* @version $Id: redirect.php,v 1.0.0 2003/03/15 10:16:30 niels Exp $
* @copyright (c) 2003 Niels Chr. Rød
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_banner.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_banner.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_REDIRECT, $banner_id);
init_userprefs($userdata);
//
// End session management
//

$banner_id = ( isset($HTTP_POST_VARS['banner_id']) ) ? intval($HTTP_POST_VARS['banner_id']) : ( isset($HTTP_GET_VARS['banner_id']) ) ? intval($HTTP_GET_VARS['banner_id']) : '';

if (empty($banner_id))
{
	message_die(GENERAL_MESSAGE, 'No banner id specified.'); 
}

$sql = "SELECT * 
	FROM " . BANNERS_TABLE . " 
	WHERE banner_id = " . $banner_id;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain banner data', '', __LINE__, __FILE__, $sql);
}
$banner_data = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

$redirect_url = $banner_data['banner_url'];
$cookie_name = $board_config['cookie_name'] . '_b_' . $banner_id;

if ( !isset($HTTP_COOKIE_VARS[$cookie_name]) )
{
	$banner_filter_time = time() + ( ($banner_data['banner_filter_time']) ? $banner_data['banner_filter_time'] : 600 );
	setcookie($cookie_name, 1, $banner_filter_time, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']); 

	$sql = "UPDATE " . BANNERS_TABLE . " 
		SET banner_click = banner_click + 1 
		WHERE banner_id = " . $banner_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update banner count data', '', __LINE__, __FILE__, $sql);
	}
}

$sql = "INSERT INTO " . BANNER_STATS_TABLE . " (banner_id, click_date, click_ip, click_user, user_duration) 
	VALUES (" . $banner_id . ", " . time() . ", '" . $userdata['session_ip'] . "', " . $userdata['user_id'] . ", '" . ($userdata['session_time'] - $userdata['session_start'] + $board_config['session_length']) . "')";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not insert banner stats', '', __LINE__, __FILE__, $sql);
}

// Points for banner clicks
if ( $userdata['session_logged_in'] && $board_config['points_banner'] )
{
	$sql = "UPDATE " . USERS_TABLE . " 
		SET user_points = user_points + " . $board_config['points_banner'] . "
    	WHERE user_id = " . $userdata['user_id']; 
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, "Couldn't update users points", "", __LINE__, __FILE__, $sql); 
	}
}

$template->set_filenames(array( 
	'body' => 'redirect.tpl')
); 

$template->assign_vars(array( 
    'REDIRECT_URL' => $redirect_url,
	'MESSAGE' => sprintf($lang['No_redirect_error'], $redirect_url))
);

$template->pparse('body'); 

?>
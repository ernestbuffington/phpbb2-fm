<?php
/** 
*
* @package phpBB2
* @version $Id: shoutbox_max.php,v 1.1.0 2003/02/28 14:56:51 niels Exp $
* @copyright (c) 2003 Niels Chr. Denmark
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_SHOUTBOX_MAX);
init_userprefs($userdata);
//
// End session management
//

if (!$board_config['shoutbox_enable'])
{ 
	message_die(GENERAL_MESSAGE, $lang['Shoutbox_disabled']); 
}

//
// Start auth check
//
switch ($userdata['user_level'])
{
	case ADMIN: 
	case LESS_ADMIN: 
	case MOD: 
		$is_auth['auth_mod'] = 1;
	default:
		$is_auth['auth_read'] = $is_auth['auth_view'] = 1;
		if ( $userdata['user_id'] == ANONYMOUS )
		{
			$is_auth['auth_delete'] = $is_auth['auth_post'] = 0;
		} 
		else
		{
			$is_auth['auth_delete'] = $is_auth['auth_post'] = 1;
		}
		break;
}

if( !$is_auth['auth_read'] )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}
//
// End auth check
//

$refresh = (isset($HTTP_POST_VARS['auto_refresh']) || isset($HTTP_POST_VARS['refresh'])) ? 1 : 0;
$submit = (isset($HTTP_POST_VARS['shout']) && isset($HTTP_POST_VARS['message'])) ? 1 : 0;

if ( !empty($HTTP_POST_VARS['mode']) || !empty($HTTP_GET_VARS['mode']) )
{
	$mode = ( !empty($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

//
// See if we need offset
//
if ((isset($HTTP_POST_VARS['start']) || isset($HTTP_GET_VARS['start'])) && !$submit)
{
	$start = (isset($HTTP_POST_VARS['start'])) ? intval($HTTP_POST_VARS['start']) : intval($HTTP_GET_VARS['start']);
} 
else 
{
	$start = 0;
}
$start = ($start < 0) ? 0 : $start;

//
// Set toggles for various options
//
if ( !$board_config['allow_html'] )
{
	$html_on = 0;
}
else
{
	$html_on = ( $submit || $refresh || preview) ? ( ( !empty($HTTP_POST_VARS['disable_html']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_html'] : $userdata['user_allowhtml'] );
}

if ( !$board_config['allow_bbcode'] )
{
	$bbcode_on = 0;
}
else
{
	$bbcode_on = ( $submit || $refresh || preview) ? ( ( !empty($HTTP_POST_VARS['disable_bbcode']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_bbcode'] : $userdata['user_allowbbcode'] );
}

if ( !$board_config['allow_smilies'] )
{
	$smilies_on = 0;
}
else
{
	$smilies_on = ( $submit || $refresh || preview) ? ( ( !empty($HTTP_POST_VARS['disable_smilies']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_smilies'] : $userdata['user_allowsmile'] );
	if ($smilies_on)
	{
		include($phpbb_root_path . 'includes/functions_post.'.$phpEx);
		
		if( $board_config['allow_smilies'] )
		{
			generate_smilies('inline', PAGE_SHOUTBOX_MAX);
		}	
		if ($mode == 'smilies')
		{
			generate_smilies('window', PAGE_SHOUTBOX_MAX);
			exit;
		}
		
	}
}

if ($refresh)
{
	$message = ( !empty($HTTP_POST_VARS['message']) ) ? htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['message']))) : '';
	if (!empty($message))
	{
		$template->assign_var('MESSAGE', $message);
	}
} 
else if ($submit || isset($HTTP_POST_VARS['message']))
{
	//
	// Flood control
	// No flood control for Admins, Super Mods, or Mods
	//
	if ( $userdata['user_level'] == USER )
	{
		$where_sql = ( $userdata['user_id'] == ANONYMOUS ) ? "shout_ip = '$user_ip'" : 'shout_user_id = ' . $userdata['user_id'];
		$sql = "SELECT MAX(shout_session_time) AS last_post_time
			FROM " . SHOUTBOX_TABLE . "
			WHERE $where_sql";		
		if ( $result = $db->sql_query($sql) )
		{
			if ( $row = $db->sql_fetchrow($result) )
			{
				if ( $row['last_post_time'] > 0 && ( time() - $row['last_post_time'] ) < $board_config['flood_interval'] )
				{
					$error = true;
					$error_msg .= ( !empty($error_msg) ) ? '<br />' . $lang['Flood_Error'] : $lang['Flood_Error'];
				}
			}
		}
	}
		
	// Check username
	if ( !empty($username) )
	{
		$username = htmlspecialchars(trim(strip_tags($username)));

		if ( !$userdata['session_logged_in'] || ( $userdata['session_logged_in'] && $username != $userdata['username'] ) )
		{
			include($phpbb_root_path . 'includes/functions_validate.'.$phpEx);

			$result = validate_username($username);
			if ( $result['error'] )
			{
				$error_msg .= ( !empty($error_msg) ) ? '<br />' . $result['error_msg'] : $result['error_msg'];
			}
		}
	}
	$message = (isset($HTTP_POST_VARS['message'])) ? trim($HTTP_POST_VARS['message']) : '';
	
	// Insert shout
	if ( !empty($message) && $is_auth['auth_post'] && !$error )
	{
		require_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);
		
		$bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';
		$message = prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on, $bbcode_uid);

		// Tags [mod] [/mod] are prohibited for everyone except moderators and administrators (post)
		if ( check_mod_tags($is_auth['auth_mod'], $message) )
		{ 
			message_die(GENERAL_MESSAGE, $lang['Mod_reserved'], $lang['Mod_restrictions']);
		} 
		
		$sql = "INSERT INTO " . SHOUTBOX_TABLE. " (shout_text, shout_session_time, shout_user_id, shout_ip, shout_username, shout_bbcode_uid, enable_bbcode, enable_html, enable_smilies) 
			VALUES ('$message', " . time() . ", " . $userdata['user_id'] . ", '$user_ip', '" . $username . "', '" . $bbcode_uid . "', $bbcode_on, $html_on, $smilies_on)";		
		if (!$result = $db->sql_query($sql)) 
		{
			message_die(GENERAL_ERROR, 'Error inserting shout.', '', __LINE__, __FILE__, $sql);
		}
	}
} 

//
// Auto prune
//
if ($board_config['prune_shouts'])
{
	$sql = "DELETE FROM " . SHOUTBOX_TABLE. " 
		WHERE shout_session_time <= " . (time() - 86400 * $board_config['prune_shouts']);		
	if (!$result = $db->sql_query($sql)) 
	{
		message_die(GENERAL_ERROR, 'Error autoprune shouts.', '', __LINE__, __FILE__, $sql);
	}
}

//
// parse post permission 
//
if ( $is_auth['auth_post'] )
{
	$template->assign_block_vars('switch_auth_post', array());
}	
else
{	
	$template->assign_block_vars('switch_auth_no_post', array());
}

//
// BBCode toggle selection
//
if ($bbcode_on)
{
	$template->assign_block_vars('switch_auth_post.switch_bbcode', array());
}

$gen_simple_header = 'y';
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array( 
	'body' => 'shoutbox_body.tpl')
);

$template->assign_vars(array( 
	'U_SHOUTBOX' => append_sid("shoutbox.$phpEx?start=$start"),
	'U_SHOUTBOX_VIEW' => append_sid("shoutbox_view.$phpEx?start=$start"),

	'SHOUTBOX_FRAME_HEIGHT' => $board_config['shoutbox_height'] - 72,
	'EDITOR_NAME' => $userdata['username'],
	'L_REFRESH' => $lang['Refresh'],
	'L_SHOUT' => $lang['Your_shout'],
	'L_SMILIES' => $lang['Smilies'],
	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
		
	'U_MORE_SMILIES' => append_sid('posting.'.$phpEx.'?mode=smilies'),
	'SHOUT_VIEW_SIZE' => ($max) ? $max : 0,
	'S_HIDDEN_FIELDS' => $s_hidden_fields)
);

if( $error_msg != '' )
{
	$template->set_filenames(array(
		'reg_header' => 'error_body.tpl')
	);

	$template->assign_vars(array(
		'ERROR_MESSAGE' => $error_msg)
	);

	$template->assign_var_from_handle('ERROR_BOX', 'reg_header');

	$message = ( !empty($HTTP_POST_VARS['message']) ) ? htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['message']))) : '';

	$template->assign_var('MESSAGE', $message);
}

$template->pparse('body'); 

?>
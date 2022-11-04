<?php
/** 
*
* @package phpBB2
* @version $Id: shoutbox_view.php,v 0.9.3 2003/02/28 14:56:51 niels Exp $
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

//
// Display the defult page
// See if we need offset
//
if (isset($HTTP_POST_VARS['start']) || isset($HTTP_GET_VARS['start']))
{
	$start = (isset($HTTP_POST_VARS['start'])) ? intval($HTTP_POST_VARS['start']) : intval($HTTP_GET_VARS['start']);
} 
else 
{
	$start = 0;
}
$start = ($start < 0) ? 0 : $start;

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


$gen_simple_header = 'y';
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array( 
      'body' => 'shoutbox_view_body.tpl')
); 

//
// Define censored word matches
//
if ( !$board_config['allow_swearywords'] )
{
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
}
else if ( !$userdata['user_allowswearywords'] )
{
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
}

//
// Display the shoutbox
//
$sql = "SELECT s.*, u.user_allowsmile, u.username, u.user_level, user_custom_post_color 
	FROM " . SHOUTBOX_TABLE . " s, " . USERS_TABLE . " u
	WHERE s.shout_user_id = u.user_id 
	ORDER BY s.shout_session_time DESC 
	LIMIT $start, " . $board_config['topics_per_page'];
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not get shoutbox information', '', __LINE__, __FILE__, $sql);
}

$i = 0;
while ($shout_row = $db->sql_fetchrow($result))
{
	$user_id = $shout_row['shout_user_id'];
	$shout_username = ( $user_id == ANONYMOUS ) ? (( $shout_row['shout_username'] == '' ) ? $lang['Guest'] : $shout_row['shout_username'] ) : '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $shout_row['shout_user_id']) . '" target="_top" class="gensmall">' . username_level_color($shout_row['username'], $shout_row['user_level'], $shout_row['shout_user_id']) . '</a>';
	$user_custom_post_color = ( $board_config['allow_custom_post_color'] && $shout_row['user_custom_post_color'] && $shout_row['user_id'] != ANONYMOUS ) ? $shout_row['user_custom_post_color'] : '';

	$shout = $shout_row['shout_text'];	
    $bbcode_uid = $shout_row['shout_bbcode_uid'];

	//
	// Note! The order used for parsing the message _is_ important, moving things around could break any
	// output
	//
	
	//
	// If the board has HTML off but the shout has HTML
	// on then we process it, else leave it alone
	//
	if ( !$board_config['allow_html'] || !$userdata['user_allowhtml'] )
	{
		if ( $shout_row['enable_html'] )
		{
			$shout = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $shout);
		}
	}

	//
	// Parse shout for BBCode if reqd
	//
	if ( $bbcode_uid != '' )
	{
		$shout = ($board_config['allow_bbcode']) ? bbencode_second_pass($shout, $bbcode_uid) : preg_replace("/\:$bbcode_uid/si", '', $shout);
	}
	$shout = make_clickable($shout);

	//
 	// ed2k link and add all
	//
	$shout = make_addalled2k_link($shout, $shout_row['shout_id']);
	
	//
	// Parse smilies
	//
	if ( $board_config['allow_smilies'] && $shout_row['enable_smilies'] )
	{
		$shout = smilies_pass($shout);
	}
	else
	{
		if( $board_config['smilie_removal1'] )
		{
			$shout = smilies_code_removal($shout);
		}
	}

	//
   	// Highlight active words (primarily for search) 
   	// 
   	if ($highlight_match) 
   	{ 
		// This has been back-ported from 3.0 CVS
		$shout = preg_replace('#(?!<.*)(?<!\w)(' . $highlight_match . ')(?!\w|[^<>]*>)#i', '<b style="color:#'.$theme['fontcolor4'].'">\1</b>', $shout);
	}
		
	//
	// Replace naughty words
	//
	if( !empty($orig_word) )
	{
		$shout = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $shout. '<'), 1, -1));
	}

	//
	// Censor shout text
	//
	$shout = ( !$shout_row['shout_active'] ) ? $shout : $lang['Shout_censor'] . ( ($is_auth['auth_mod']) ? '<hr />' . $shout : '');

	//
	// Replace newlines (we use this rather than nl2br because
	// till recently it wasn't XHTML compliant)
	//
	$shout = str_replace("\n", "\n<br />\n", $shout);
	$shout = word_wrap_pass($shout);
		
	//
	// Again this will be handled by the templating
	// code at some point
	//
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('shoutrow', array(
		'ROW_CLASS' => $row_class,
		'SHOUT' => $shout,
		'CUSTOM_POST_COLOR' => $user_custom_post_color,
		'TIME' => create_date($board_config['default_dateformat'], $shout_row['shout_session_time'], $board_config['board_timezone']),
		'USERNAME' => $shout_username)
	);
	$i++;
}
$db->sql_freeresult($result);

$template->assign_vars(array( 
	'U_SHOUTBOX_VIEW' => append_sid('shoutbox_view.'.$phpEx.'?start=' . $start),
	'SHOUTBOX_REFRESH_RATE' => $board_config['shoutbox_refresh_rate'])
);

$template->pparse('body'); 

?>
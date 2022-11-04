<?php
/** 
*
* @package phpBB2
* @version $Id: shoutbox_max.php,v 0.9.6 2003/02/28 14:56:51 niels Exp $
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

$forum_id = PAGE_SHOUTBOX_MAX;
$refresh = (isset($HTTP_POST_VARS['auto_refresh']) || isset($HTTP_POST_VARS['refresh'])) ? 1 : 0;
$preview = (isset($HTTP_POST_VARS['preview'])) ? 1 : 0;
$submit = (isset($HTTP_POST_VARS['shout']) && isset($HTTP_POST_VARS['message'])) ? 1 : 0;

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

//
// See if we need offset
//
if ( (isset($HTTP_POST_VARS['start']) || isset($HTTP_GET_VARS['start'])) && !$submit )
{
	$start = ( isset($HTTP_POST_VARS['start']) ) ? intval($HTTP_POST_VARS['start']) : intval($HTTP_GET_VARS['start']);
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
}

if( !$userdata['session_logged_in'] || ( $mode == 'editpost' && $post_info['poster_id'] == ANONYMOUS ) )
{
	$template->assign_block_vars('switch_username_select', array());
}

$username = ( !empty($HTTP_POST_VARS['username']) ) ? $HTTP_POST_VARS['username'] : '';

// Check username
if ( !empty($username) )
{
	$username = htmlspecialchars(trim(strip_tags($username)));
	
	if ( !$userdata['session_logged_in'])
	{
		require_once($phpbb_root_path . 'includes/functions_validate.'.$phpEx);
		$result = validate_username($username);
		
		if ( $result['error'] )
		{
			$error = true;
			$error_msg .= ( !empty($error_msg) ) ? '<br />' . $result['error_msg'] : $result['error_msg'];
		}
	}
}

if ($refresh || $preview)
{
	$message = ( !empty($HTTP_POST_VARS['message']) ) ? htmlspecialchars(trim(stripslashes($HTTP_POST_VARS['message']))) : '';
	
	if (!empty($message))
	{
		if ($preview)
		{
			require_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);

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
			
			$bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';

			$preview_message = stripslashes(prepare_message(addslashes(unprepare_message($message)), $html_on, $bbcode_on, $smilies_on, $bbcode_uid));

			// Tags [mod] [/mod] are prohibited for everyone except moderators and administrators (preview)
			if ( check_mod_tags($is_auth['auth_mod'], $preview_message) )
			{ 
				message_die(GENERAL_MESSAGE, $lang['Mod_reserved'], $lang['Mod_restrictions']);
			} 

			if( $bbcode_on )
			{
				$preview_message = bbencode_second_pass($preview_message, $bbcode_uid);
			}

			if( !empty($orig_word) )
			{
				$preview_message = ( !empty($preview_message) ) ? preg_replace($orig_word, $replacement_word, $preview_message) : '';
			}
			$preview_message = make_clickable($preview_message);

			if( $smilies_on )
			{
				$preview_message = smilies_pass($preview_message);
			}
			else
			{
				if( $board_config['smilie_removal1'] )
				{
					$preview_message = smilies_code_removal($preview_message);
				}
			}

			$preview_message = str_replace("\n", '<br />', $preview_message);
			$preview_message = word_wrap_pass($preview_message);

			$template->set_filenames(array(
				'preview' => 'posting_preview.tpl')
			);

			$template->assign_vars(array(
				'POST_SUBJECT' => $lang['Shoutbox'],
				'USERNAME' => $username,
				'POST_DATE' => create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
				'MESSAGE' => $preview_message,

				'L_POST_SUBJECT' => $lang['Post_subject'], 
				'L_POSTED' => $lang['Posted'], 
				'L_PREVIEW' => $lang['Preview'], 
				'T_NAV_STYLE' => ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images')
			);

			$template->assign_var_from_handle('POST_PREVIEW_BOX', 'preview');
		} 

		$template->assign_var('MESSAGE',$message);
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
	
	$message = (isset($HTTP_POST_VARS['message'])) ? trim($HTTP_POST_VARS['message']) : '';
	
	// Insert shout
	if (!empty($message) && $is_auth['auth_post'] && !$error)
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
else if ($mode == 'delete' || $mode == 'censor')
{
	// Make shout inactive
	if ( isset($HTTP_GET_VARS[POST_POST_URL]) || isset($HTTP_POST_VARS[POST_POST_URL]) )
	{
		$post_id = (isset($HTTP_POST_VARS[POST_POST_URL])) ? intval($HTTP_POST_VARS[POST_POST_URL]) : intval($HTTP_GET_VARS[POST_POST_URL]);
	}
	else
	{
		message_die(GENERAL_ERROR, 'Error no shout_id specified for delete/censor.', '', __LINE__, __FILE__);
	}

	$sql = "SELECT shout_user_id, shout_ip 
		FROM " . SHOUTBOX_TABLE . " 
		WHERE shout_id = '$post_id'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get shoutbox information', '', __LINE__, __FILE__, $sql);
	}
	$shout_identifyer = $db->sql_fetchrow($result);

	$user_id = $shout_identifyer['shout_user_id'];

	if (($userdata['user_id'] != ANONYMOUS || ( $userdata['user_id'] == ANONYMOUS && $userdata['session_ip'] == $shout_identifyer['shout_ip'])) && (($userdata['user_id'] == $user_id && $is_auth['auth_delete']) || $is_auth['auth_mod']) && $mode=='censor')
	{
		$sql = "UPDATE " . SHOUTBOX_TABLE . " 
			SET shout_active = " . $userdata['user_id'] . " 
			WHERE shout_id = '$post_id'";
		if (!$result = $db->sql_query($sql)) 
		{
			message_die(GENERAL_ERROR, 'Error censoring shout', '', __LINE__, __FILE__, $sql);
		}
	} 
	else if ( $is_auth['auth_mod'] && $mode == 'delete')
	{
		$sql = "DELETE FROM " . SHOUTBOX_TABLE . " 
			WHERE shout_id = '$post_id'";
		if (!$result = $db->sql_query($sql)) 
		{
			message_die(GENERAL_ERROR, 'Error deleting shout', '', __LINE__, __FILE__, $sql);
		}
	} 
	else 
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
	} 
}
else if ($mode == 'uncensor')
{
	// Re-activate shout
	if ( isset($HTTP_GET_VARS[POST_POST_URL]) || isset($HTTP_POST_VARS[POST_POST_URL]) )
	{
		$post_id = (isset($HTTP_POST_VARS[POST_POST_URL])) ? intval($HTTP_POST_VARS[POST_POST_URL]) : intval($HTTP_GET_VARS[POST_POST_URL]);
	}
	else
	{
		message_die(GENERAL_ERROR, 'Error no shout_id specified for uncensoring');
	}
	
	$sql = "SELECT shout_user_id 
		FROM " . SHOUTBOX_TABLE . " 
		WHERE shout_id = '$post_id'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain shoutbox information', '', __LINE__, __FILE__, $sql);
	}
	$shout_identifyer = $db->sql_fetchrow($result);

	$user_id = $shout_identifyer['shout_user_id'];

	if ( (($userdata['user_id'] == $user_id && $is_auth['auth_delete']) || $is_auth['auth_mod']) && $mode=='uncensor')
	{
		$sql = "UPDATE " . SHOUTBOX_TABLE . " 
			SET shout_active = 0 
			WHERE shout_id = '$post_id'";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error uncensoring shout', '', __LINE__, __FILE__, $sql);
		}
	}
}
else if ($mode == 'ip')
{
	// Show the IP
	if ( !$is_auth['auth_mod'])
	{
		message_die(GENERAL_MESSAGE, 'Not allowed.', '', __LINE__, __FILE__);
	}

	if ( isset($HTTP_GET_VARS[POST_POST_URL]) || isset($HTTP_POST_VARS[POST_POST_URL]) )
	{
		$post_id = (isset($HTTP_POST_VARS[POST_POST_URL])) ? intval($HTTP_POST_VARS[POST_POST_URL]) : intval($HTTP_GET_VARS[POST_POST_URL]);
	}
	else
	{
		message_die(GENERAL_ERROR, 'Error no shout_id specified for show IP');
	}

	$sql = "SELECT shout_user_id, shout_username, shout_ip 
		FROM " . SHOUTBOX_TABLE . " 
		WHERE shout_id = '$post_id'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain shoutbox information', '', __LINE__, __FILE__, $sql);
	}
	$shout_identifyer = $db->sql_fetchrow($result);

	$poster_id = $shout_identifyer['shout_user_id'];
	$rdns_ip_num = ( isset($HTTP_GET_VARS['rdns']) ) ? $HTTP_GET_VARS['rdns'] : "";

	$ip_this_post = decode_ip($shout_identifyer['shout_ip']);
	$ip_this_post = ( $rdns_ip_num == $ip_this_post ) ? gethostbyaddr($ip_this_post) : $ip_this_post;
	
	require_once($phpbb_root_path . 'includes/page_header.'.$phpEx);

	//
	// Set template files
	//
	$template->set_filenames(array(
		'viewip' => 'modcp_viewip.tpl')
	);
	
	$template->assign_vars(array(
		'L_IP_INFO' => $lang['IP_info'],
		'L_THIS_POST_IP' => $lang['This_posts_IP'],
		'L_OTHER_IPS' => $lang['Other_IP_this_user'],
		'L_OTHER_USERS' => $lang['Users_this_IP'],
		'L_LOOKUP_IP' => $lang['Lookup_IP'], 
		'L_SEARCH' => $lang['Search'],
		'SEARCH_IMG' => $images['icon_search'], 
		'IP' => $ip_this_post, 
		'U_LOOKUP_IP' => append_sid("shoutbox_max.$phpEx?mode=ip&amp;" . POST_POST_URL . "=$post_id&amp;rdns=" . $ip_this_post))
	);

	//
	// Get other IP's this user has posted under
	//
	$sql = "SELECT shout_ip, COUNT(*) AS postings 
		FROM " . SHOUTBOX_TABLE . " 
		WHERE shout_user_id = $poster_id 
		GROUP BY shout_ip 
		ORDER BY " . (( SQL_LAYER == 'msaccess' ) ? 'COUNT(*)' : 'postings' ) . " DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get IP information for this user', '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		$i = 0;
		do
		{
			if ( $row['shout_ip'] == $post_row['shout_ip'] )
			{
				$template->assign_vars(array(
					'POSTS' => $row['postings'] . ' ' . ( ( $row['postings'] == 1 ) ? $lang['Post'] : $lang['Posts'] ))
				);
				continue;
			}

			$ip = decode_ip($row['shout_ip']);
			$ip = ( $rdns_ip_num == $row['shout_ip'] || $rdns_ip_num == 'all') ? gethostbyaddr($ip) : $ip;

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('iprow', array(
				'ROW_CLASS' => $row_class, 
				'IP' => $ip,
				'POSTS' => $row['postings'] . ' ' . ( ( $row['postings'] == 1 ) ? $lang['Post'] : $lang['Posts'] ),

				'U_LOOKUP_IP' => append_sid("shoutbox_max.$phpEx?mode=ip&amp;" . POST_POST_URL . "=$post_id&amp;rdns=" . $row['shout_ip']))
			);

			$i++; 
		}
		while ( $row = $db->sql_fetchrow($result) );
	}

	//
	// Get other users who've posted under this IP
	//
	$sql = "SELECT u.user_id, u.username, COUNT(*) AS postings 
		FROM " . USERS_TABLE ." u, " . POSTS_TABLE . " p 
		WHERE p.poster_id = u.user_id 
			AND p.poster_ip = '" . $shout_identifyer['shout_ip'] . "'
		GROUP BY u.user_id, u.username
		ORDER BY " . (( SQL_LAYER == 'msaccess' ) ? 'COUNT(*)' : 'postings' ) . " DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not get posters information based on IP', '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		$i = 0;
		do
		{
			$id = $row['user_id'];
//			$username = ( $id == ANONYMOUS ) ? $lang['Guest'] : $row['username'];
			$shout_username = ( $id == ANONYMOUS && $row['username'] == '' ) ? $lang['Guest'] : $row['username'];

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('userrow', array(
				'ROW_CLASS' => $row_class, 
				'SHOUT_USERNAME' => $shout_username,
				'POSTS' => $row['postings'] . ' ' . ( ( $row['postings'] == 1 ) ? $lang['Post'] : $lang['Posts'] ),
				'L_SEARCH_POSTS' => sprintf($lang['Search_user_posts'], $shout_username), 

				'U_PROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$id"),
				'U_SEARCHPOSTS' => append_sid("search.$phpEx?search_author=" . urlencode($shout_username) . "&amp;showresults=topics"))
			);

			$i++; 
		}
		while ( $row = $db->sql_fetchrow($result) );
	}

	$template->pparse('viewip');
	
	require_once($phpbb_root_path . 'includes/page_tail.'.$phpEx);

	exit;
}


//
// Display the defult page
//

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

$page_title = $lang['Post_shout'];
require_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);
require_once($phpbb_root_path . 'includes/page_header.'.$phpEx);

//
// Was a highlight request part of the URI?
//
$highlight_match = $highlight = '';
if (isset($HTTP_GET_VARS['highlight']))
{
   // Split words and phrases
   $words = explode(' ', trim(htmlspecialchars($HTTP_GET_VARS['highlight'])));

   for($i = 0; $i < sizeof($words); $i++)
   {
      if (trim($words[$i]) != '')
      {
         $highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', preg_quote($words[$i], '#'));
      }
   }
   unset($words);

   $highlight = urlencode($HTTP_GET_VARS['highlight']);
   $highlight_match = phpbb_rtrim($highlight_match, "\\");
} 


$sql = "SELECT *
	FROM " . RANKS_TABLE . "
	ORDER BY rank_special, rank_min DESC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql);
}

$ranksrow = array();
while ( $row = $db->sql_fetchrow($result) )
{
	$ranksrow[] = $row;
}
$db->sql_freeresult($result);


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
// Get statistics
//
$sql = "SELECT COUNT(*) AS total 
	FROM " . SHOUTBOX_TABLE; 
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not get shoutbox stat information', '', __LINE__, __FILE__, $sql);
}

$total_shouts = $db->sql_fetchrow($result);
$total_shouts = $total_shouts['total'];

//
// parse post permission 
//
if ($is_auth['auth_post'])
{
	$template->set_filenames(array(
		'body' => 'shoutbox_max_body.tpl')
	);
} 
else
{
	$template->set_filenames(array(
		'body' => 'shoutbox_max_guest_body.tpl')
	);
}
make_jumpbox('viewforum.'.$phpEx);

//
// Generate pagination for shoutbox view
//
$pagination = ( $highlight_match ) ? generate_pagination("shoutbox_max.$phpEx?highlight=" . $highlight, $total_shouts, $board_config['posts_per_page'], $start) : generate_pagination("shoutbox_max.$phpEx?dummy=1", $total_shouts, $board_config['posts_per_page'], $start);

//
// Generate smilies listing for page output
//
if( $board_config['allow_smilies'] )
{
	generate_smilies('inline', PAGE_SHOUTBOX_MAX);
}	

//
// Smilies toggle selection
//
if ( $board_config['allow_smilies'] )
{
	$smilies_status = $lang['Smilies_are_ON'];
	$template->assign_block_vars('switch_smilies_checkbox', array());
}
else
{
	if( $board_config['smilie_removal1'] )
	{
		$smilies_status = $lang['Smilies_are_REMOVED'];
	}
	else
	{
		$smilies_status = $lang['Smilies_are_OFF'];
	}
}

//
// HTML toggle selection
//
if ( $board_config['allow_html'] )
{
	$html_status = $lang['HTML_is_ON'];
	$template->assign_block_vars('switch_html_checkbox', array());
}
else
{
	$html_status = $lang['HTML_is_OFF'];
}

//
// BBCode toggle selection
//
if ( $board_config['allow_bbcode'] )
{
	$bbcode_status = $lang['BBCode_is_ON'];
	$template->assign_block_vars('switch_bbcode_checkbox', array());
}
else
{
	$bbcode_status = $lang['BBCode_is_OFF'];
}

//
// Display the shoutbox
//
$sql = "SELECT s.*, u.*, u.user_avatar AS current_user_avatar, u.user_avatar_type AS current_user_avatar_type 
	FROM " . SHOUTBOX_TABLE . " s, ".USERS_TABLE." u
	WHERE s.shout_user_id = u.user_id 
	ORDER BY s.shout_session_time DESC 
	LIMIT $start, " . $board_config['posts_per_page'];
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not get shoutbox information', '', __LINE__, __FILE__, $sql);
}

$i = 0;
while ($shout_row = $db->sql_fetchrow($result))
{
	$user_id = $shout_row['shout_user_id'];
	$shout_username = ( $user_id == ANONYMOUS ) ? (( $shout_row['shout_username'] == '' ) ? $lang['Guest'] : $shout_row['shout_username'] ) : '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $shout_row['shout_user_id']) . '" target="_top" class="gen">' . username_level_color($shout_row['username'], $shout_row['user_level'], $shout_row['shout_user_id']) . '</a>';
	$user_custom_post_color = ( $board_config['allow_custom_post_color'] && $shout_row['user_custom_post_color'] && $shout_row['user_id'] != ANONYMOUS ) ? $shout_row['user_custom_post_color'] : '';
	
	$user_profile = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id");
	$user_posts = ( $shout_row['user_id'] != ANONYMOUS ) ? '<b>' . $lang['Posts'] . ':</b> ' . $shout_row['user_posts'] : '';
	$user_from = ( $shout_row['user_from'] && $shout_row['user_id'] != ANONYMOUS ) ? '<b>' . $lang['Location'] . ':</b> ' . $shout_row['user_from'] : '';
	$user_joined = ( $shout_row['user_id'] != ANONYMOUS ) ? '<b>' . $lang['Joined'] . ':</b> ' . create_date($lang['DATE_FORMAT'], $shout_row['user_regdate'], $board_config['board_timezone']) : '';
		

	//
	// User Avatar
	//
	$user_avatar = '';
	if ( $shout_row['user_avatar_type'] && $user_id != ANONYMOUS && $shout_row['user_allowavatar'] && $userdata['user_showavatars'] && $userdata['avatar_sticky'])
	{
		switch( $shout_row['user_avatar_type'] )
		{
			case USER_AVATAR_UPLOAD:
				$user_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $shout_row['user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$user_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $shout_row['user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_GALLERY:
				$user_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $shout_row['user_avatar'] . '" alt="" title="" />' : '';
				break;
		}
	}
	else if ( ($shout_row['current_user_avatar_type']) && $user_id != ANONYMOUS && $shout_row['user_allowavatar'] && $userdata['user_showavatars'] )
	{
		switch( $shout_row['current_user_avatar_type'] )
		{
			case USER_AVATAR_UPLOAD:
				$user_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $shout_row['current_user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$user_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $shout_row['current_user_avatar'] . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_GALLERY:
				$user_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $shout_row['current_user_avatar'] . '" alt="" title="" />' : '';
				break;
		}
	}

	if ( (!$user_avatar) && ($board_config['default_avatar_set'] != 3) )
	{
		if ( ($board_config['default_avatar_set'] == 0) && ($user_id == ANONYMOUS) && ($board_config['default_avatar_guests_url']) )
		{
			$user_avatar = '<img src="' . $board_config['default_avatar_guests_url'] . '" alt="" title="" />';
		}
		else if ( ($board_config['default_avatar_set'] == 1) && ($user_id != ANONYMOUS) && ($board_config['default_avatar_users_url']) )
		{
			$user_avatar = '<img src="' . $board_config['default_avatar_users_url'] . '" alt="" title="" />';
		}
		else if ($board_config['default_avatar_set'] == 2)
		{
			if ( ($user_id == ANONYMOUS) && ($board_config['default_avatar_guests_url']) )
			{
				$user_avatar = '<img src="' . $board_config['default_avatar_guests_url'] . '" alt="" title="" />';
			}
			else if ( ($user_id != -1) && ($board_config['default_avatar_users_url']) )
			{
				$user_avatar = '<img src="' . $board_config['default_avatar_users_url'] . '" alt="" title="" />';
			}
		}
	}


	//
	// Define the little post icon
	//
	$mini_post_url = append_sid("shoutbox_max.$phpEx?" . POST_POST_URL . '=' . $shout_row['shout_id']) . '#' . $shout_row['shout_id'];


	//
	// Generate strings, set them to an empty string initially.
	//
	$user_rank = $rank_image = $temp_url = '';
	if ( $postrow[$i]['user_id'] == ANONYMOUS )
	{
	}
	else if ( $shout_row['user_rank'])
	{
		for($j = 0; $j < sizeof($ranksrow); $j++)
		{
			if ( $shout_row['user_rank'] == $ranksrow[$j]['rank_id'] && $ranksrow[$j]['rank_special'] )
			{
				$user_rank = $ranksrow[$j]['rank_title'];
				$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[$j]['rank_image'] . '" alt="' . $user_rank . '" title="' . $user_rank . '" /><br />' : '';
			}
		}
	} 
	else
	{
		for($j = 0; $j < sizeof($ranksrow); $j++)
		{
			if ( $shout_row['user_posts'] >= $ranksrow[$j]['rank_min'] && !$ranksrow[$j]['rank_special'] )
			{
				$user_rank = $ranksrow[$j]['rank_title'];
				$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[$j]['rank_image'] . '" alt="' . $user_rank . '" title="' . $user_rank . '" /><br />' : '';
			}
		}
	}
	
	if ( $is_auth['auth_mod'] && $is_auth['auth_delete'])
	{
		$temp_url = append_sid("shoutbox_max.$phpEx?mode=ip&amp;" . POST_POST_URL . "=" . $shout_row['shout_id']);
		$ip_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_ip'] . '" alt="' . $lang['View_IP'] . '" title="' . $lang['View_IP'] . '" /></a>';
		$ip = '<a href="' . $temp_url . '">' . $lang['View_IP'] . '</a>';

		$temp_url = append_sid("shoutbox_max.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $shout_row['shout_id']);
		$delshout_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>&nbsp;';
		$delshout = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';

		$temp_url = append_sid("shoutbox_max.$phpEx?mode=censor&amp;" . POST_POST_URL . "=" . $shout_row['shout_id']);
		$censorshout_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_censor'] . '" alt="' . $lang['Censor'] . '" title="' . $lang['Censor'] . '" /></a>&nbsp;';
		$censorshout = '<a href="' . $temp_url . '">' . $lang['Censor'] . '</a>';

    	$temp_url = append_sid("shoutbox_max.$phpEx?mode=uncensor&amp;" . POST_POST_URL . "=" . $shout_row['shout_id']);
		$uncensorshout_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_uncensor'] . '" alt="' . $lang['Uncensor'] . '" title="' . $lang['Uncensor'] . '" /></a>&nbsp;';
		$uncensorshout = '<a href="' . $temp_url . '">' . $lang['Uncensor'] . '</a>';
	}
	else
	{
		$ip_img = $ip = '';

		if ( ($userdata['user_id'] == $user_id && $is_auth['auth_delete'] ) && ($userdata['user_id'] != ANONYMOUS || ( $userdata['user_id'] == ANONYMOUS && $userdata['session_ip'] == $shout_row['shout_ip'])) )
		{
			$temp_url = append_sid("shoutbox_max.$phpEx?mode=censor&amp;" . POST_POST_URL . "=" . $shout_row['shout_id']);
			$censorshout_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_censor'] . '" alt="' . $lang['Censor'] . '" title="' . $lang['Censor'] . '" /></a>&nbsp;';
			$censorshout = '<a href="' . $temp_url . '">' . $lang['Censor'] . '</a>';

			$temp_url = append_sid("shoutbox_max.$phpEx?mode=uncensor&amp;" . POST_POST_URL . "=" . $shout_row['shout_id']);
			$uncensorshout_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_uncensor'] . '" alt="' . $lang['Uncensor'] . '" title="' . $lang['Uncensor'] . '" /></a>&nbsp;';
			$uncensorshout = '<a href="' . $temp_url . '">' . $lang['Uncensor'] . '</a>';
		}
		else
		{
			$delshout_img = $delshout = $censorshout_img = $censorshout = $uncensorshout_img = $uncensorshout = '';
		}
	}

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
	if ( !empty($orig_word) )
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
		'SHOUT_USERNAME' => $shout_username,
		'U_VIEW_USER_PROFILE' => $user_profile,
		'USER_RANK' => $user_rank,
		'RANK_IMAGE' => $rank_image,
		'IP_IMG' => $ip_img, 
		'IP' => $ip, 

		'DELETE_IMG' => $delshout_img, 
		'DELETE' => $delshout, 
		'CENSOR_IMG' => (empty($shout_row['shout_active'])) ? $censorshout_img : $uncensorshout_img, 
		'CENSOR' => $censorshout, 
		'UNCENSOR' => $uncensorshout,
		'USER_AVATAR' => $user_avatar,
		'USER_JOINED' => $user_joined,
		'USER_POSTS' => $user_posts,
		'USER_FROM' => $user_from,

		'MINI_POST_IMG' => $images['icon_minipost'],
		'L_MINI_POST_ALT' => $lang['Post'],
		'U_MINI_POST' => $mini_post_url,

		'U_SHOUT_ID' => $shout_row['shout_id'])
	);
	$i++;
}
$db->sql_freeresult($result);

//
// Show post options
//
if ( $is_auth['auth_post'] )
{
	$template->assign_block_vars('switch_auth_post', array());
}	
else
{	
	$template->assign_block_vars('switch_auth_no_post', array());
}

Multi_BBCode();

$template->assign_vars(array( 
	'USERNAME' => $username,
	'EDITOR_NAME' => $userdata['username'],
	'PAGINATION' => $pagination,
	'NUMBER_OF_SHOUTS' => $total_shouts,
	'HTML_STATUS' => $html_status,
	'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'), 
	'SMILIES_STATUS' => $smilies_status,
	'AJAXED_PREVIEW' => $ajaxed_preview,

	'L_SHOUTBOX_LOGIN' => $lang['Login_join'],

	'L_BACK_AT_BOTTOM' => $lang['Back_at_bottom'],
	'L_POSTED' => $lang['Posted'], 
	'L_AUTHOR' => $lang['Author'],
	'L_MESSAGE' => $lang['Message'],
	'L_SHOUTBOX' => $lang['Shoutbox'],
	'L_OPTIONS' => $lang['Options'],
	'L_SPELLCHECK' => $lang['Spellcheck'],
	'L_COPY_TO_CLIPBOARD' => $lang['Copy_to_clipboard'],
	'L_COPY_TO_CLIPBOARD_EXPLAIN' => $lang['Copy_to_clipboard_explain'],
	'L_HIGHLIGHT_TEXT' => $lang['Highlight_text'],
	'L_PREVIEW' => $lang['Preview'],
	'L_SHOUT_BODY' => $lang['Shout_body'],
	'L_REFRESH' => $lang['Refresh'],
	'L_POST_SHOUT' => $lang['Post_shout'],

	'L_DISABLE_HTML' => $lang['Disable_HTML_post'], 
	'L_DISABLE_BBCODE' => $lang['Disable_BBCode_post'], 
	'L_DISABLE_SMILIES' => $lang['Disable_Smilies_post'], 

	'S_HIDDEN_FIELDS' => $s_hidden_fields,
	'U_SHOUTBOX' => append_sid("shoutbox_max.$phpEx?start=$start"),

	'L_EXPAND_BBCODE' => $lang['Expand_bbcode'],
	'L_BBCODE_B_HELP' => $lang['bbcode_b_help'], 
	'L_BBCODE_I_HELP' => $lang['bbcode_i_help'], 
	'L_BBCODE_U_HELP' => $lang['bbcode_u_help'], 
	'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'], 
	'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
	'L_BBCODE_L_HELP' => $lang['bbcode_l_help'], 
	'L_BBCODE_O_HELP' => $lang['bbcode_o_help'], 
	'L_BBCODE_P_HELP' => $lang['bbcode_p_help'], 
	'L_BBCODE_W_HELP' => $lang['bbcode_w_help'], 
	'L_BBCODE_A_HELP' => $lang['bbcode_a_help'], 
	'L_BBCODE_A1_HELP' => $lang['bbcode_a1_help'], 
	'L_BBCODE_S_HELP' => $lang['bbcode_s_help'], 
	'L_BBCODE_F_HELP' => $lang['bbcode_f_help'], 

	'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
	'L_BBCODE_F1_HELP' => $lang['bbcode_f1_help'],
	'L_BBCODE_G1_HELP' => $lang['bbcode_g1_help'], 
	'L_BBCODE_H1_HELP' => $lang['bbcode_h1_help'],
	'L_BBCODE_S1_HELP' => $lang['bbcode_s1_help'], 
	'L_BBCODE_SC_HELP' => $lang['bbcode_sc_help'],

	'L_SMILIE_CREATOR' => $lang['Smilie_creator'],
	'L_EMPTY_MESSAGE' => $lang['Empty_message'],

	'L_HIGHLIGHT_COLOR' => $lang['Highlight_color'], 
	'L_SHADOW_COLOR' => $lang['Shadow_color'],
	'L_GLOW_COLOR' => $lang['Glow_color'],

	'L_FONT_COLOR' => $lang['Font_color'], 
		'L_HIDE_FONT_COLOR' => $lang['hide'] . ' ' . $lang['Font_color'], 
	'L_COLOR_DEFAULT' => $lang['color_default'], 
	'L_COLOR_DARK_RED' => $lang['color_dark_red'], 
	'L_COLOR_RED' => $lang['color_red'], 
	'L_COLOR_ORANGE' => $lang['color_orange'], 
	'L_COLOR_BROWN' => $lang['color_brown'], 
	'L_COLOR_YELLOW' => $lang['color_yellow'], 
	'L_COLOR_GREEN' => $lang['color_green'], 
	'L_COLOR_OLIVE' => $lang['color_olive'], 
	'L_COLOR_CYAN' => $lang['color_cyan'], 
	'L_COLOR_BLUE' => $lang['color_blue'], 
	'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'], 
	'L_COLOR_INDIGO' => $lang['color_indigo'], 
	'L_COLOR_VIOLET' => $lang['color_violet'], 
	'L_COLOR_WHITE' => $lang['color_white'], 
	'L_COLOR_BLACK' => $lang['color_black'], 
	'L_COLOR_CADET_BLUE' => $lang['color_cadet_blue'],
	'L_COLOR_CORAL' => $lang['color_coral'], 
	'L_COLOR_CRIMSON' => $lang['color_crimson'], 
	'L_COLOR_TOMATO' => $lang['color_tomato'], 
	'L_COLOR_SEA_GREEN' => $lang['color_sea_green'], 
	'L_COLOR_DARK_ORCHID' => $lang['color_dark_orchid'], 
	'L_COLOR_CHOCOLATE' => $lang['color_chocolate'],
	'L_COLOR_DEEPSKYBLUE' => $lang['color_deepskyblue'], 
	'L_COLOR_GOLD' => $lang['color_gold'], 
	'L_COLOR_GRAY' => $lang['color_gray'], 
	'L_COLOR_MIDNIGHTBLUE' => $lang['color_midnightblue'], 
	'L_COLOR_DARKGREEN' => $lang['color_darkgreen'], 

	'L_FONT_SIZE' => $lang['Font_size'], 
	'L_FONT_TINY' => $lang['font_tiny'], 
	'L_FONT_SMALL' => $lang['font_small'], 
	'L_FONT_NORMAL' => $lang['font_normal'], 
	'L_FONT_LARGE' => $lang['font_large'], 
	'L_FONT_HUGE' => $lang['font_huge'], 

	'L_ALIGN_TEXT' => $lang['Align_text'], 
	'L_LEFT' => $lang['text_left'], 
	'L_CENTER' => $lang['text_center'], 
	'L_RIGHT' => $lang['text_right'], 
	'L_JUSTIFY' => $lang['text_justify'], 

	'L_FONT_FACE' => $lang['Font_face'],
	
	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
	'L_STYLES_TIP' => $lang['Styles_tip'],

	'S_HTML_CHECKED' => ( !$html_on ) ? 'checked="checked"' : '', 
	'S_BBCODE_CHECKED' => ( !$bbcode_on ) ? 'checked="checked"' : '', 
	'S_SMILIES_CHECKED' => ( !$smilies_on ) ? 'checked="checked"' : '')
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

	$template->assign_var('MESSAGE',$message);
}

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body'); 

require_once($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
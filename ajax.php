<?php
/** 
*
* @package phpBB2
* @version $Id: ajax.php,v 0.2.0 2006/05/23 15:51:24 Exp $
* @copyright (c) 2005 GlitchPlay.Com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include($phpbb_root_path . 'mods/calendar/mycalendar_functions.'.$phpEx);
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; '.'filename="AJAXed.txt"'); 

//
// Start initial var setup
//
if ( !empty($HTTP_GET_VARS[POST_FORUM_URL]) || !empty($HTTP_POST_VARS[POST_FORUM_URL]) )
{
	$forum_id = ( !empty($HTTP_GET_VARS[POST_FORUM_URL]) ) ? intval($HTTP_GET_VARS[POST_FORUM_URL]) : intval($HTTP_POST_VARS[POST_FORUM_URL]);
}
else
{
	$forum_id = '';
}
if ( !empty($HTTP_GET_VARS[POST_TOPIC_URL]) || !empty($HTTP_POST_VARS[POST_TOPIC_URL]) )
{
	$topics_id = ( !empty($HTTP_GET_VARS[POST_TOPIC_URL]) ) ? intval($HTTP_GET_VARS[POST_TOPIC_URL]) : intval($HTTP_POST_VARS[POST_TOPIC_URL]);
}
else
{
	$topics_id = '';
}
if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_GET_VARS['mode']) ) ? intval($HTTP_GET_VARS['mode']) : intval($HTTP_POST_VARS['mode']);
}
else
{
	$mode = '';
}

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if (!$board_config['AJAXed_status'])
{
	echo '-: AJAXed is disabled';
	return;
}

//
// Check if the user has actually sent a forum ID with his/her request
// If not give them a nice error page.
//
if ( !empty($forum_id) )
{
	$sql = "SELECT *
		FROM " . FORUMS_TABLE . "
		WHERE forum_id = $forum_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get forum info';
		return;
	}

	//
	// If the query doesn't return any rows this isn't a valid forum. Inform
	// the user.
	//
	if ( !($forum_row = $db->sql_fetchrow($result)) )
	{
		echo '-:' . $lang['Forum_not_exist'];
		return;
	}

	$is_auth = array();
	$is_auth = auth(AUTH_ALL, $forum_id, $userdata, $forum_row);
}

$func = ( !empty($HTTP_GET_VARS['rs']) ) ? $HTTP_GET_VARS['rs'] : $HTTP_POST_VARS['rs'];
$args = ( !empty($HTTP_GET_VARS['rsargs']) ) ? $HTTP_GET_VARS['rsargs'] : $HTTP_POST_VARS['rsargs'];
$allowed_functions = array('lock_unlock_topic', 'lock_topic', 'preview_post', 'previewPost', 'preview_pm', 'edit_post_msg_update', 'edit_post_msg', 'edit_post_subject', 'delete_topic', 'move_build', 'move', 'move_topic', 'build_user_list', 'post_delete', 'get_editor', 'check_username', 'post_menu', 'post_ip', 'watch_topic', 'poll_menu', 'poll_title', 'poll_options', 'poll_option_update', 'poll_view', 'poll_vote');
if ( !empty($func) && function_exists($func) && in_array($func, $allowed_functions) )
{
   call_user_func_array($func, cleanUrl_String($args));
}
else
{
	redirect('index.'.$phpEx);
}

function cleanUrl_String($str)
{
	$nStr = $str;
	$nStr = str_replace("&amp;","&",$nStr);
	$nStr = str_replace("&#43;","+",$nStr);
	$nStr = str_replace("&#61;","=",$nStr);
	$nStr = str_replace('&quot;','"',$nStr);
	
	return $nStr;
}

function lock_unlock_topic($topic_id, $mode="0")
{
	global $db, $images, $forum_id, $is_auth, $userdata, $HTTP_GET_VARS, $board_config;

	preg_match('/^[^a-zA-Z]+$/', $topic_id, $Rid);
	if(!$Rid) 
	{
		echo "-:" . $lang['AJAXed_Invaild_ID'];
		return;
	}

	$sql = "SELECT topic_status, topic_type 
		FROM " . TOPICS_TABLE . "
		WHERE topic_id = $topic_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get topic info';
		return;
	}
	$row = $db->sql_fetchrow($result);
	
	if ( $mode )
	{
		if ( $row['topic_status'] == TOPIC_UNLOCKED )
		{
			$new_status = TOPIC_LOCKED;
			$new_image = $images['topic_mod_unlock'];
			$image = $images['topic_mod_lock'];
		}
		else
		{
			$new_status = TOPIC_UNLOCKED;
			$new_image =	$images['topic_mod_lock'];
			$image = $images['topic_mod_unlock'];
		}
	}
	else
	{
		if ( $row['topic_status'] == TOPIC_UNLOCKED )
		{
			$new_status = TOPIC_LOCKED;
			$new_image = $images['topic_mod_lock'];
			$image = $images['folder'];
		}
		else
		{
			$new_status = TOPIC_UNLOCKED;
			$new_image = $images['folder'];
			$image = $images['topic_mod_unlock'];
		}
		
		if ( $row['topic_type'] == POST_STICKY )
		{
			$new_image = $image = $images['folder_sticky'];
		}
		else if ( $row['topic_type'] == POST_ANNOUNCE )
		{
			$new_image = $image = $images['folder_announce'];
		}
		else if ( $row['topic_type'] == POST_GLOBAL_ANNOUNCE )
		{
			$new_image = $image = $images['folder_global_announce'];
		}
	}

	if ( !$is_auth['auth_mod'] || $HTTP_GET_VARS['sid'] != $userdata['session_id'] || !$board_config['AJAXed_topic_lock'] )
	{
		echo '+:' . $image;
		return;
	}

	$sql = "UPDATE " . TOPICS_TABLE . " 
		SET	topic_status = $new_status
		WHERE topic_id = $topic_id";
	if ( !$db->sql_query($sql) )
	{
		echo '-:Could not update topic status';
		return;
	}
	
	echo '+:' . $new_image;
	
	return;
}

function lock_topic($topic_id)
{
	global $db, $images, $template, $lang, $forum_id, $is_auth, $userdata, $HTTP_GET_VARS, $phpEx;

	preg_match('/^[^a-zA-Z]+$/', $topic_id, $Rid);
	if(!$Rid) 
	{
		echo "-:" . $lang['AJAXed_Invaild_ID'];
		return;
	}

	$sql = "SELECT topic_status, topic_type
		FROM " . TOPICS_TABLE . "
		WHERE topic_id = $topic_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get topic info';
		return;
	}
	$row = $db->sql_fetchrow($result);

	$sql = "SELECT * 
		FROM " . FORUMS_TABLE . "
		WHERE forum_id = $forum_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get topic info';
		return;
	}
	$row2 = $db->sql_fetchrow($result);

	$post_img = ( $row2['forum_status'] == FORUM_LOCKED ) ? $images['post_locked'] : $images['post_new'];
	$post_alt = ( $row2['forum_status'] == FORUM_LOCKED ) ? $lang['Forum_locked'] : $lang['Post_new_topic'];
	$reply_img = ( $row2['forum_status'] == FORUM_LOCKED || $row['topic_status'] == TOPIC_LOCKED ) ? $images['reply_locked'] : $images['reply_new'];
	$reply_alt = ( $row2['forum_status'] == FORUM_LOCKED || $row['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['Reply_to_topic'];
	$new_topic_url = append_sid("posting.$phpEx?mode=newtopic&amp;" . POST_FORUM_URL . "=$forum_id");
	$reply_topic_url = append_sid("posting.$phpEx?mode=reply&amp;" . POST_TOPIC_URL . "=$topic_id");

	$template->set_filenames(array(
		'body' => 'ajaxed_topic_parts.tpl')
	);

	$template->assign_vars(array(
		'NEW_TOPIC_URL' => $new_topic_url,
		'POST_IMG' => $post_img,
		'POST_ALT' => $post_alt,
		'REPLY_TOPIC_URL' => $reply_topic_url,
		'REPLY_IMG' => $reply_img,
		'REPLY_ALT' => $reply_alt)
	);
	
	echo '+:';
	
	$template->pparse('body');
	
	return;
}

function preview_post($message, $subject, $disable_bbcode, $disable_smilies, $attach_sig)
{
	global $userdata, $template, $userdata, $HTTP_POST_VARS, $phpbb_root_path, $phpEx, $board_config, $lang, $theme;

	//
	// Set toggles for various options
	//
	if ( !$board_config['allow_html'] )
	{
		$html_on = 0;
	}
	else
	{
		$html_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_html']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_html'] : $userdata['user_allowhtml'] );
	}

	if ( !$board_config['allow_bbcode'] || $disable_bbcode )
	{
		$bbcode_on = 0;
	}
	else
	{
		$bbcode_on = ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_bbcode'] : $userdata['user_allowbbcode'] );
	}

	if ( !$board_config['allow_smilies'] || $disable_smilies )
	{
		$smilies_on = 0;
	}
	else
	{
		$smilies_on = ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_smilies'] : $userdata['user_allowsmile'] );
	}

	$attach_sig = ( !$attach_sig ) ? 0 : ( ( $userdata['user_id'] == ANONYMOUS ) ? 0 : $userdata['user_attachsig'] );

	$user_sig = ( $userdata['user_sig'] != '' ) ? $userdata['user_sig'] : '';
	$username = ($userdata['session_logged_in']) ? $userdata['username'] : '';

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
	$preview_message = stripslashes(prepare_message(unprepare_message($message), $html_on, $bbcode_on, $smilies_on, $bbcode_uid));
	$preview_subject = $subject;
	$preview_username = $username;

	//
	// Finalise processing as per viewtopic
	//
	if( !$html_on )
	{
		if( $user_sig != '' || !$userdata['user_allowhtml'] )
		{
			$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\2&gt;', $user_sig);
		}
	}

	if( $attach_sig && $user_sig != '' && $userdata['user_sig_bbcode_uid'] )
	{
		$user_sig = bbencode_second_pass($user_sig, $userdata['user_sig_bbcode_uid']);
	}

	if( $bbcode_on )
	{
		$preview_message = bbencode_second_pass($preview_message, $bbcode_uid);
	}

	if( !empty($orig_word) )
	{
		$preview_username = ( !empty($username) ) ? preg_replace($orig_word, $replacement_word, $preview_username) : '';
		$preview_subject = ( !empty($subject) ) ? preg_replace($orig_word, $replacement_word, $preview_subject) : '';
		$preview_message = ( !empty($preview_message) ) ? preg_replace($orig_word, $replacement_word, $preview_message) : '';
	}

	if( $user_sig != '' )
	{
		$user_sig = make_clickable($user_sig);
	}
	$preview_message = make_clickable($preview_message);

 	// ed2k link and add all
//	$preview_message = make_addalled2k_link($preview_message, $post_info['post_id']);

	if( $smilies_on )
	{
		if( $userdata['user_allowsmile'] && $user_sig != '' )
		{
			$user_sig = smilies_pass($user_sig);
		}

		$preview_message = smilies_pass($preview_message);
	}

	if( $attach_sig && $user_sig != '' )
	{
		$preview_message = $preview_message . '<br /><br />_________________<br />' . $user_sig;
	}

	$preview_message = str_replace("\n", '<br />', $preview_message);
	$preview_message = word_wrap_pass($preview_message);

	$preview_message = (lock_subject) ? stripslashes($extra_message_body) . $preview_message : $preview_message;

	$template->set_filenames(array(
		'preview' => 'posting_preview.tpl')
	);

	$template->assign_vars(array(
		'TOPIC_TITLE' => $preview_subject,
		'POST_SUBJECT' => $preview_subject,
		'POSTER_NAME' => $preview_username,
		'POST_DATE' => create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
//		'MESSAGE' => convert_unicode(utf8_encode(stripslashes($preview_message))),
		'MESSAGE' => $preview_message,

		'T_NAV_STYLE' => ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images',

		'L_POST_SUBJECT' => $lang['Post_subject'], 
		'L_PREVIEW' => $lang['Preview'],
		'L_POSTED' => $lang['Posted'], 
		'L_POST' => $lang['Post'])
	);
	
	echo '+:';
	
	$template->pparse('preview');
	
	return;
}

function previewPost($message, $post_id)
{
	global $userdata, $db, $template, $userdata, $HTTP_POST_VARS, $phpbb_root_path, $phpEx, $board_config, $lang;

	$sql = "SELECT *
		FROM " . POSTS_TABLE . "
		WHERE post_id = $post_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get post info';
		return;
	}
	$row = $db->sql_fetchrow($result);

	//
	// Set toggles for various options
	//
	if ( !$board_config['allow_html'] || !$row['enable_html'] )
	{
		$html_on = 0;
	}
	else
	{
		$html_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_html']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_html'] : $userdata['user_allowhtml'] );
	}

	if ( !$board_config['allow_bbcode'] || !$row['enable_bbcode'] )
	{
		$bbcode_on = 0;
	}
	else
	{
		$bbcode_on = ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_bbcode'] : $userdata['user_allowbbcode'] );
	}

	if ( !$board_config['allow_smilies'] || !$row['enable_smilies'] )
	{
		$smilies_on = 0;
	}
	else
	{
		$smilies_on = ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_smilies'] : $userdata['user_allowsmile'] );
	}

	if ( $userdata['user_level'] < 1 && !$userdata['user_allowswearywords'] )
	{
		$orig_word = $replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}
	
	$bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';
	$preview_message = stripslashes(prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on, $bbcode_uid));

	if( $bbcode_on )
	{
		$preview_message = bbencode_second_pass($preview_message, $bbcode_uid);
	}

	if( !empty($orig_word) )
	{
		$preview_message = ( !empty($preview_message) ) ? preg_replace($orig_word, $replacement_word, $preview_message) : '';
	}

	if( $smilies_on )
	{
		$preview_message = smilies_pass($preview_message);
	}

	$preview_message = make_clickable($preview_message);

 	// ed2k link and add all
	$preview_message = make_addalled2k_link($preview_message, $post_id);

	$preview_message = str_replace("\n", '<br />', $preview_message);
	
	$preview_message = (lock_subject) ? stripslashes($extra_message_body) . $preview_message : $preview_message;

	$template->assign_block_vars('preview', array());
	
	$template->set_filenames(array(
		'preview' => 'ajaxed_editor.tpl')
	);

	$template->assign_vars(array(
//		'MESSAGE' => convert_unicode(utf8_encode(stripslashes($preview_message))),
		'MESSAGE' => $preview_message,
		'POST_ID' => $post_id,
		'L_CLOSE' => $lang['AJAXed_close'],
		'L_TOP' => $lang['AJAXed_Go_To_Top'],
		'L_EDITOR' => $lang['AJAXed_Go_To_Editor'],
		'L_PREVIEW' => $lang['Preview'])
	);
	
	echo '+:';
	
	$template->pparse('preview');
	
	return;
}

function preview_pm($message, $subject, $to_username, $disable_bbcode, $disable_smilies, $attach_sig)
{
	global $userdata, $template, $userdata, $HTTP_POST_VARS, $phpbb_root_path, $phpEx, $board_config, $lang;

	//
	// Set toggles for various options
	//
	if ( !$board_config['allow_html'] )
	{
		$html_on = 0;
	}
	else
	{
		$html_on = ( $submit || $refresh ) ? ( ( !empty($HTTP_POST_VARS['disable_html']) ) ? 0 : TRUE ) : ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_html'] : $userdata['user_allowhtml'] );
	}

	if ( !$board_config['allow_bbcode'] || $disable_bbcode )
	{
		$bbcode_on = 0;
	}
	else
	{
		$bbcode_on = ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_bbcode'] : $userdata['user_allowbbcode'] );
	}

	if ( !$board_config['allow_smilies'] || $disable_smilies )
	{
		$smilies_on = 0;
	}
	else
	{
		$smilies_on = ( ( $userdata['user_id'] == ANONYMOUS ) ? $board_config['allow_smilies'] : $userdata['user_allowsmile'] );
	}

	$attach_sig = ( !$attach_sig ) ? 0 : ( ( $userdata['user_id'] == ANONYMOUS ) ? 0 : $userdata['user_attachsig'] );

	$user_sig = ( $userdata['user_sig'] != '' ) ? $userdata['user_sig'] : '';
	$username = ($userdata['session_logged_in']) ? $userdata['username'] : '';

	if ( $userdata['user_level'] < 1 && !$userdata['user_allowswearywords'] )
	{
		$orig_word = $replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}
	
	$bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';
//	$preview_message = convert_unicode(utf8_encode(stripslashes(prepare_message(addslashes(unprepare_message($message)), $html_on, $bbcode_on, $smilies_on, $bbcode_uid))));
	$preview_message = stripslashes(prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on, $bbcode_uid));
	$preview_subject = convert_unicode(utf8_encode($subject));
	$preview_username = convert_unicode(utf8_encode($username));

	//
	// Finalise processing as per viewtopic
	//
	if( !$html_on )
	{
		if( $user_sig != '' || !$userdata['user_allowhtml'] )
		{
			$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\2&gt;', $user_sig);
		}
	}

	if( $attach_sig && $user_sig != '' && $userdata['user_sig_bbcode_uid'] )
	{
		$user_sig = bbencode_second_pass($user_sig, $userdata['user_sig_bbcode_uid']);
	}

	if( $bbcode_on )
	{
		$preview_message = bbencode_second_pass($preview_message, $bbcode_uid);
	}

	if( !empty($orig_word) )
	{
		$preview_username = ( !empty($username) ) ? preg_replace($orig_word, $replacement_word, $preview_username) : '';
		$preview_subject = ( !empty($subject) ) ? preg_replace($orig_word, $replacement_word, $preview_subject) : '';
		$preview_message = ( !empty($preview_message) ) ? preg_replace($orig_word, $replacement_word, $preview_message) : '';
	}

	if( $user_sig != '' )
	{
		$user_sig = make_clickable($user_sig);
	}
	$preview_message = make_clickable($preview_message);

 	// ed2k link and add all
//	$preview_message = make_addalled2k_link($preview_message, $post_info['post_id']);

	if( $smilies_on )
	{
		if( $userdata['user_allowsmile'] && $user_sig != '' )
		{
			$user_sig = smilies_pass($user_sig);
		}

		$preview_message = smilies_pass($preview_message);
	}

	if( $attach_sig && $user_sig != '' )
	{
		$preview_message = $preview_message . '<br /><br />_________________<br />' . $user_sig;
	}

	$preview_message = str_replace("\n", '<br />', $preview_message);
	$preview_message = word_wrap_pass($preview_message);

	$template->set_filenames(array(
		'preview' => 'privmsgs_preview.tpl')
	);

	$template->assign_vars(array(
		'TOPIC_TITLE' => $preview_subject,
		'POST_SUBJECT' => $preview_subject,
		'MESSAGE_TO' => convert_unicode(utf8_encode($to_username)), 
		'MESSAGE_FROM' => convert_unicode(utf8_encode($userdata['username'])), 
		'POST_DATE' => create_date($board_config['default_dateformat'], time(), $board_config['board_timezone']),
//		'MESSAGE' => convert_unicode(utf8_encode($preview_message)),
		'MESSAGE' => $preview_message,

		'L_SUBJECT' => $lang['Subject'],
		'L_DATE' => $lang['Date'],
		'L_FROM' => $lang['From'],
		'L_TO' => $lang['To'],
		'L_PREVIEW' => $lang['Preview'],
		'L_POSTED' => $lang['Posted'])
	);
		
	echo '+:';
	
	$template->pparse('preview');

	return;
}

function edit_post_msg_update($post_id)
{
	global $db, $lang, $board_config;

	preg_match('/^[^a-zA-Z]+$/', $post_id, $Rid);
	if(!$Rid) 
	{
		echo "-:" . $lang['AJAXed_Invaild_ID'];
		return;
	}

	$l_edited_by = '';
	if ( $board_config['display_last_edited'] )	
	{
		// get bbcode uid
		$sql = "SELECT post_edit_count, post_edit_time, post_id, poster_id
			FROM " . POSTS_TABLE . "
			WHERE post_id = $post_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			echo '-:Could not get post info';
			return;
		}
		$row = $db->sql_fetchrow($result);
	
		$sql = "SELECT username, user_id
			FROM " . USERS_TABLE . "
			WHERE user_id = " . $row['poster_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			echo '-:Could not get post info';
			return;
		}
		$user = $db->sql_fetchrow($result);
	
		$poster = ( $row['poster_id'] == ANONYMOUS ) ? $lang['Guest'] : $user['username'];
	
		if ( $row['post_edit_count'] )
		{
			$l_edit_time_total = ( $row['post_edit_count'] == 1 ) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];
	
			$l_edited_by = '<br /><br />' . sprintf($l_edit_time_total, $poster, create_date($board_config['default_dateformat'], $row['post_edit_time'], $board_config['board_timezone']), $row['post_edit_count']);
		}
	}
	
	echo '+:' . $l_edited_by;
	
	return;
}

function edit_post_msg($post_id, $message, $addUpdate)
{
	global $db, $is_auth, $HTTP_GET_VARS, $userdata, $board_config;

	preg_match('/^[^a-zA-Z]+$/', $post_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$is_auth['auth_view'] )
	{
		return;
	}

	// get bbcode uid
	$sql = "SELECT p.enable_bbcode, p.enable_html, p.enable_smilies, pt.bbcode_uid, pt.post_text 
		FROM " . POSTS_TABLE . " p, " . POSTS_TEXT_TABLE . " pt
		WHERE p.post_id = $post_id
			AND pt.post_id = p.post_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get post info';
		return;
	}
	
	$row = $db->sql_fetchrow($result);
	$bbcode_uid = $row['bbcode_uid'];

	if ( !$is_auth['auth_edit'] || $HTTP_GET_VARS['sid'] != $userdata['session_id'] || !$board_config['AJAXed_inline_post_editing'] )
	{
		echo '+:' . bbencode_second_pass($row['post_text'], $bbcode_uid);
		return;
	}

	// prepare the message
	$message = stripslashes(prepare_message(trim(addslashes($message)), $row['enable_html'], $row['enable_bbcode'], $row['enable_smilies'], $bbcode_uid));
	$message = str_replace("\\'", "\'", $message);
	
	if($addUpdate == "true")
	{
		$current_time = time();
		$sql = "UPDATE " . POSTS_TABLE . " 
			SET post_edit_time = $current_time, post_edit_count = post_edit_count + 1 
			WHERE post_id = $post_id";
		if ( !$db->sql_query($sql) )
		{
			echo '-:Could not update post text';
			return;
		}
	}

	$sql = "UPDATE " . POSTS_TEXT_TABLE . " 
		SET post_text = '" . $message . "'
		WHERE post_id = $post_id";
	if ( !$db->sql_query($sql) )
	{
		echo '-:Could not update post text';
		return;
	}

	//
	// If the board has HTML off but the post has HTML
	// on then we process it, else leave it alone
	//
	if ( !$board_config['allow_html'] )
	{
		if ( $row['enable_html'] )
		{
			$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
		}
	}

	//
	// Parse message and/or sig for BBCode if reqd
	//
	if ( $board_config['allow_bbcode'] )
	{
		if( $row['enable_bbcode'] )
		{
			$message = bbencode_second_pass($message, $bbcode_uid);
		}
	}

	$message = make_clickable($message);

	//
	// Parse smilies
	//
	if ( $board_config['allow_smilies'] )
	{
		if ( $row['enable_smilies'] )
		{
			$message = smilies_pass($message);
		}
	}

	//
	// Replace naughty words
	//
	if ( $userdata['user_level'] < 1 && !$userdata['user_allowswearywords'] )
	{
		$orig_word = $replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);
	}
		
	if( !empty($orig_word) )
	{
		$message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
	}

	$message = str_replace("\n", "\n<br />\n", stripslashes($message));

	echo '+:' . stripslashes($message);
	
	return;
}

function edit_post_subject($post_id, $i, $subject)
{
	global $db, $is_auth, $HTTP_GET_VARS, $userdata, $board_config;

	preg_match('/^[^a-zA-Z]+$/', $post_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$is_auth['auth_view'] )
	{
		return;
	}
	
	$subject = convert_unicode($subject);
	
	// get bbcode uid
	$sql = "SELECT post_subject 
		FROM " . POSTS_TEXT_TABLE . "
		WHERE post_id = $post_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get post info';
		return;
	}
	$row = $db->sql_fetchrow($result);

	if ( !$is_auth['auth_mod'] || $HTTP_GET_VARS['sid'] != $userdata['session_id'] || !$board_config['AJAXed_post_title'] )
	{
		echo '+:' . $row['post_subject'];
		return;
	}

	// prepare the message
	$subject = unprepare_message(trim($subject));
	$subject = htmlspecialchars($subject);
	
	if ($i == '0')
	{
		$sql = "UPDATE " . POSTS_TEXT_TABLE . " 
			SET post_subject = '" . stripslashes(str_replace("\'", "''", $subject)) . "'
			WHERE post_id = $post_id";
		if ( !$db->sql_query($sql) )
		{
			echo '-:Could not update post subject';
			return;
		}
		
		$sql = "UPDATE " . TOPICS_TABLE . " 
			SET topic_title = '" . stripslashes(str_replace("\'", "''", $subject)) . "'
			WHERE topic_first_post_id = $post_id";
		if ( !$db->sql_query($sql) )
		{
			echo '-:Could not update post subject';
			return;
		}

	}
	else
	{
		$sql = "UPDATE " . POSTS_TEXT_TABLE . " 
			SET post_subject = '" . stripslashes(str_replace("\'", "''", $subject)) . "'
			WHERE post_id = $post_id";
		if ( !$db->sql_query($sql) )
		{
			echo '-:Could not update post subject';
			return;
		}
	}

	//
	// Replace naughty words
	//
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
	
	if (sizeof($orig_word))
	{
		$subject = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $subject . '<'), 1, -1));
	}

	echo '+:' . stripslashes(convert_unicode(utf8_encode($subject)));
	
	return;
}

function delete_topic($topic_id,$d)
{
	global $phpbb_root_path, $board_config, $db, $is_auth, $forum_id, $userdata, $HTTP_GET_VARS, $phpEx, $lang, $template;

	preg_match('/^[^a-zA-Z]+$/', $topic_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
	include($phpbb_root_path . 'includes/functions_search.'.$phpEx);

	if ( !$is_auth['auth_mod'] || $HTTP_GET_VARS['sid'] != $userdata['session_id'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}

	$topics = ( isset($HTTP_POST_VARS['topic_id_list']) ) ? $HTTP_POST_VARS['topic_id_list'] : array($topic_id);

	$topic_id_sql = '';
	for($i = 0; $i < sizeof($topics); $i++)
	{
		$topic_id_sql .= ( ( $topic_id_sql != '' ) ? ', ' : '' ) . intval($topics[$i]);
	}
	
	$sql = "SELECT topic_id 
		FROM " . TOPICS_TABLE . "
		WHERE topic_id IN ($topic_id_sql)
			AND forum_id = $forum_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get topic id information';
		return;
	}
	
	$topic_id_sql = '';
	while ($row = $db->sql_fetchrow($result))
	{
		$topic_id_sql .= (($topic_id_sql != '') ? ', ' : '') . intval($row['topic_id']);
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT poster_id, COUNT(post_id) AS posts 
		FROM " . POSTS_TABLE . " 
		WHERE topic_id IN ($topic_id_sql) 
		GROUP BY poster_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get poster id information';
		return;
	}
	$count_sql = array();
	
	while ( $row = $db->sql_fetchrow($result) )
	{
		$count_sql[] = "UPDATE " . USERS_TABLE . " 
			SET user_posts = user_posts - " . $row['posts'] . " 
			WHERE user_id = " . $row['poster_id'];
	}
	$db->sql_freeresult($result);
	
	if ( sizeof($count_sql) )
	{
		for($i = 0; $i < sizeof($count_sql); $i++)
		{
			if ( !$db->sql_query($count_sql[$i]) )
			{
				echo '-:Could not update user post count information';
				return;
			}
		}
	}		
	
	$sql = "SELECT post_id 
		FROM " . POSTS_TABLE . " 
		WHERE topic_id IN ($topic_id_sql)";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get post id information';
		return;
	}
	
	$post_id_sql = '';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$post_id_sql .= ( ( $post_id_sql != '' ) ? ', ' : '' ) . intval($row['post_id']);
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT vote_id 
		FROM " . VOTE_DESC_TABLE . " 
		WHERE topic_id IN ($topic_id_sql)";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get vote id information';
		return;
	}
	
	$vote_id_sql = '';
	while ( $row = $db->sql_fetchrow($result) )
	{
		$vote_id_sql .= ( ( $vote_id_sql != '' ) ? ', ' : '' ) . $row['vote_id'];
	}
	$db->sql_freeresult($result);

	//
	// Got all required info so go ahead and start deleting everything
	//
	mycal_delete_event($topic_id_sql, null, false);

	$sql = "DELETE 
		FROM " . TOPICS_TABLE . " 
		WHERE topic_id IN ($topic_id_sql) 
			OR topic_moved_id IN ($topic_id_sql)";
	if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
	{
		echo '-:Could not delete topics';
		return;
	}
	
	$sql = "DELETE 
		FROM " . THANKS_TABLE . "
		WHERE topic_id IN ($topic_id_sql)";
	if ( !$db->sql_query($sql) )
	{
		echo '-:Could not delete topic thanks';
		return;
	}
	
	if ( $post_id_sql != '' )
	{
		$sql = "DELETE 
			FROM " . POSTS_TABLE . " 
			WHERE post_id IN ($post_id_sql)";
		if ( !$db->sql_query($sql) )
		{
			echo '-:Could not delete posts';
			return;
		}
		
		$sql = "DELETE 
			FROM " . POSTS_TEXT_TABLE . " 
			WHERE post_id IN ($post_id_sql)";
		if ( !$db->sql_query($sql) )
		{
			echo '-:Could not delete posts text';
			return;
		}
		remove_search_post($post_id_sql);
	}
	
	if ( $vote_id_sql != '' )
	{
		$sql = "DELETE 
			FROM " . VOTE_DESC_TABLE . " 
			WHERE vote_id IN ($vote_id_sql)";
		if ( !$db->sql_query($sql) )
		{
			echo '-:Could not delete vote descriptions';
			return;
		}
		
		$sql = "DELETE 
			FROM " . VOTE_RESULTS_TABLE . " 
			WHERE vote_id IN ($vote_id_sql)";
		if ( !$db->sql_query($sql) )
		{
			echo '-:Could not delete vote results';
			return;
		}
		
		$sql = "DELETE 
			FROM " . VOTE_USERS_TABLE . " 
			WHERE vote_id IN ($vote_id_sql)";
		if ( !$db->sql_query($sql) )
		{
			echo '-:Could not delete vote users';
			return;
		}
	}
	
	$sql = "DELETE 
		FROM " . TOPICS_VIEWDATA_TABLE . " 
		WHERE topic_id IN ($topic_id_sql)"; 
	if ( !$db->sql_query($sql) ) 
	{ 
		echo '-:Could not delete topic views list';
		return;
	} 

	$sql = "DELETE
		FROM " . THREAD_KICKER_TABLE . "
		WHERE topic_id IN ($topic_id_sql)";
	if ( !$db->sql_query($sql) ) 
	{
		echo '-:Could not delete usercp topic kicker data';
		return;
	}
	
	$sql = "DELETE 
		FROM " . TOPICS_WATCH_TABLE . " 
		WHERE topic_id IN ($topic_id_sql)";
	if ( !$db->sql_query($sql, END_TRANSACTION) )
	{
		echo '-:Could not delete watched post list';
		return;
	}
	
	sync('forum', $forum_id);
	
	if( !($d) )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	else
	{
		echo '+:';
		
		$template->set_filenames(array('no_more_topic' => 'message_body.tpl'));
		
		$template->assign_vars(array(
			'U_INDEX' => append_sid('index.'.$phpEx),
			'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => $lang['AJAXed_deleted_topic'])
		);
		
		$template->pparse('no_more_topic');
		
		return;
	}
}

function move_topic($topic_id, $new_forum_id, $leave_shadow)
{
	global $phpbb_root_path, $db, $is_auth, $userdata, $HTTP_GET_VARS, $phpEx;

	preg_match('/^[^a-zA-Z]+$/', $topic_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}
	
	preg_match('/^[^a-zA-Z]+$/', $new_forum_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}


	if ( !$is_auth['auth_mod'] )
	{
		return;
	}

	include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
	include($phpbb_root_path . 'includes/functions_search.'.$phpEx);

	$sql = "SELECT forum_id 
		FROM " . TOPICS_TABLE . " 
		WHERE topic_id = $topic_id";
	if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
	{
		echo '-:Could not select from topic table';
		return;
	}
	
	$forum_id = $db->sql_fetchrow($result);
	$old_forum_id = $forum_id['forum_id'];

	$sql = 'SELECT forum_id FROM ' . FORUMS_TABLE . '
		WHERE forum_id = ' . $new_forum_id;
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not select from forums table';
		return;
	}
			
	if (!$db->sql_fetchrow($result))
	{
		echo '-:New forum does not exist';
		return;
	}
	$db->sql_freeresult($result);

	if ( $new_forum_id != $old_forum_id )
	{
		$topics = array($topic_id);
		
		$topic_list = '';
		for($i = 0; $i < sizeof($topics); $i++)
		{
			$topic_list .= ( ( $topic_list != '' ) ? ', ' : '' ) . intval($topics[$i]);
		}
		
		$sql = "SELECT * 
			FROM " . TOPICS_TABLE . " 
			WHERE topic_id IN ($topic_list)
				AND forum_id = $old_forum_id
				AND topic_status <> " . TOPIC_MOVED;
		if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
		{
			echo '-:Could not select from topic table';
			return;
		}
		$row = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		
		for($i = 0; $i < sizeof($row); $i++)
		{
			$topic_moved_id = $row[$i]['topic_id'];
			if ( $leave_shadow == "true" )
			{
				// Insert topic in the old forum that indicates that the forum has moved.
				$sql = "INSERT INTO " . TOPICS_TABLE . " (forum_id, topic_title, topic_poster, topic_time, topic_status, topic_type, topic_vote, topic_views, topic_replies, topic_first_post_id, topic_last_post_id, topic_moved_id)
					VALUES ($old_forum_id, '" . addslashes(str_replace("\'", "''", $row[$i]['topic_title'])) . "', '" . str_replace("\'", "''", $row[$i]['topic_poster']) . "', " . $row[$i]['topic_time'] . ", " . TOPIC_MOVED . ", " . POST_NORMAL . ", " . $row[$i]['topic_vote'] . ", " . $row[$i]['topic_views'] . ", " . $row[$i]['topic_replies'] . ", " . $row[$i]['topic_first_post_id'] . ", " . $row[$i]['topic_last_post_id'] . ", $topic_moved_id)";
				if ( !$db->sql_query($sql) )
				{
					echo '-:Could not insert shadow topic';
					return;
				}
			}
			
			$sql = "UPDATE " . TOPICS_TABLE . " 
				SET forum_id = $new_forum_id  
				WHERE topic_id = $topic_id";
			if ( !$db->sql_query($sql) )
			{
				echo '-:Could not update old topic';
				return;
			}
			
			$sql = "UPDATE " . POSTS_TABLE . " 
				SET forum_id = $new_forum_id 
				WHERE topic_id = $topic_id";
			if ( !$db->sql_query($sql) )
			{
				echo '-:Could not update post topic ids';
				return;
			}
		}

		// Sync the forum indexes
		sync('forum', $new_forum_id);
		sync('forum', $old_forum_id);
	}
	
	echo '+: ' . $topic_id;
	
	return;
}

function move_build($topic_id, $b)
{
	global $phpbb_root_path, $db, $is_auth, $userdata, $lang, $template, $phpEx;

	preg_match('/^[^a-zA-Z]+$/', $topic_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$is_auth['auth_mod'] )
	{
		return;
	}

	$sql = "SELECT * 
		FROM " . TOPICS_TABLE . " 
		WHERE topic_id = $topic_id";
	if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
	{
		echo '-:Could not select from topic table';
		return;
	}
	$row = $db->sql_fetchrow($result);

	include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);

	$template->set_filenames(array('movetopic' => 'ajaxed_move_topic.tpl'));

	$template->assign_vars(array(
		'MESSAGE_TITLE' => convert_unicode(utf8_encode($lang['Confirm'])),
		'MESSAGE_TEXT' => convert_unicode(utf8_encode($lang['Confirm_move_topic'])),
		'L_MOVE_TO_FORUM' => $lang['Move_to_forum'], 
		'L_LEAVESHADOW' => $lang['Leave_shadow_topic'], 
		'L_YES' => $lang['Yes'],
		'TOPIC_ID' => $topic_id,
		'B' => $b,
		'S_FORUM_SELECT' => make_forum_select($topic_id .'_new_forum', $row['forum_id']), 
		'S_HIDDEN_FIELDS' => $hidden_fields)
	);
	
	echo '+: ';
	
	$template->pparse('movetopic');
	
	return;
}

function move($topic_id)
{
	global $db, $images, $lang, $forum_id, $is_auth, $userdata, $HTTP_GET_VARS, $phpEx, $board_config;

	preg_match('/^[^a-zA-Z]+$/', $topic_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$is_auth['auth_mod'] )
	{
		return;
	}

	$sql = "SELECT forum_id, topic_type 
		FROM " . TOPICS_TABLE . "
		WHERE topic_id = $topic_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get topic info';
		return;
	}
	$row = $db->sql_fetchrow($result);

	$sql = "SELECT * 
		FROM " . FORUMS_TABLE . "
		WHERE forum_id = " . $row['forum_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get topic info';
		return;
	}
	$row2 = $db->sql_fetchrow($result);

	$view_forum_url = append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=" . $row2['forum_id']);
	$u_index = append_sid('index.'.$phpEx);
	$l_index = sprintf($lang['Forum_Index'], $board_config['sitename']);

	echo '+:<span class="nav">&nbsp;&nbsp;&nbsp;<a href="' . $u_index . '" class="nav">' . $l_index . '</a>  -> <a href="' . $view_forum_url . '" class="nav">' . $row2['forum_name'] . '</a></span>';

	return;
}

function build_user_list($char, $width)
{
	global $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang;

	$onlypx = str_replace("px", "", $width);
	preg_match('/^[^a-zA-Z]+$/', $onlypx, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	$html_replace = "&039;";
	$html_match = "#'#";

	if ( !$board_config['AJAXed_user_list'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}

	$sql_member_fix= " AND username like '|".$char."%' ESCAPE '|' ";

	$sql = "SELECT username, user_id 
		FROM " . USERS_TABLE . " 
		WHERE user_id <> " . ANONYMOUS . " 
		$sql_member_fix
		ORDER BY username ASC 
		LIMIT 0, " . $board_config['AJAXed_user_list_number'];
	if( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not query users';
		return;
	}
	
	echo '+: '; 
	
	$template->assign_vars(array('WIDTH' => $width, 'CLASS_1' => '#' . $theme['tr_color1']));

	$template->set_filenames(array('user_list' => 'ajaxed_user_list.tpl'));

	$user_list = array();
	if ($row = $db->sql_fetchrow($result))
	{
		do
		{
			$user_list[] = $row;
		}
		while ($row = $db->sql_fetchrow($result));
		$db->sql_freeresult($result);
		$total_users = sizeof($user_list);
	}
	
	if ( !$total_users )
	{
		$template->assign_block_vars('ajaxednot', array('NOT_FOUND' => convert_unicode(utf8_encode($lang['AJAXed_no_username']))));
	}
	else
	{
		$template->assign_block_vars('ajaxed', array());
		if ($total_users > 10)
		{
			$template->assign_block_vars('ajaxed.scroll', array());
		}
		
		for($ajaxed = 0; $ajaxed < $total_users; $ajaxed++)
		{
			$template->assign_block_vars('ajaxed.ajaxedrow', array('ID' => $ajaxed, 'USERNAME_JAVA' => preg_replace($html_match, $html_replace, convert_unicode($user_list[$ajaxed]['username'])), 'USERNAME' => convert_unicode($user_list[$ajaxed]['username'])));
		}
	}
	
	$template->pparse('user_list');
	
	return;
}

function post_delete($post_id, $i)
{
	global $phpbb_root_path, $board_config, $lang, $db, $phpbb_root_path, $phpEx, $userdata, $template;

	preg_match('/^[^a-zA-Z]+$/', $post_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);
	
	$mode = 'delete';
	$post_data = array();

	if ( empty($post_id) )
	{
		echo '-:' . $lang['No_post_id'];
	}
	
	$select_sql = (!$submit) ? ', t.topic_title, p.enable_bbcode, p.enable_html, p.enable_smilies, p.enable_sig, p.post_username, pt.post_subject, pt.post_text, pt.bbcode_uid, u.username, u.user_id, u.user_sig, u.user_sig_bbcode_uid' : '';
	$from_sql = ( !$submit ) ? ", " . POSTS_TEXT_TABLE . " pt, " . USERS_TABLE . " u" : '';
	$where_sql = ( !$submit ) ? "AND pt.post_id = p.post_id AND u.user_id = p.poster_id" : '';
	
	$sql = "SELECT f.*, t.topic_id, t.topic_status, t.topic_type, t.topic_first_post_id, t.topic_last_post_id, t.topic_vote, p.post_id, p.poster_id" . $select_sql . " 
		FROM " . POSTS_TABLE . " p, " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f" . $from_sql . " 
		WHERE p.post_id = $post_id 
			AND t.topic_id = p.topic_id 
			AND f.forum_id = p.forum_id
			$where_sql";
	if ( $result = $db->sql_query($sql) )
	{
		$post_info = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
	
		$forum_id = $post_info['forum_id'];
		$forum_name = $post_info['forum_name'];
	
		$is_auth = auth(AUTH_ALL, $forum_id, $userdata, $post_info);
	
		if ( $post_info['forum_status'] == FORUM_LOCKED && !$is_auth['auth_mod']) 
		{ 
			echo '-:' . $lang['Forum_locked'];
			return;
		} 
		else if ( $mode != 'newtopic' && $post_info['topic_status'] == TOPIC_LOCKED && !$is_auth['auth_mod']) 
		{ 
			echo '-:' . $lang['Topic_locked'];
			return;
		} 
	
		if ( $mode == 'editpost' || $mode == 'delete' || $mode == 'poll_delete' )
		{
			$topic_id = $post_info['topic_id'];
	
			$post_data['poster_post'] = ( $post_info['poster_id'] == $userdata['user_id'] ) ? true : false;
			$post_data['first_post'] = ( $post_info['topic_first_post_id'] == $post_id ) ? true : false;
			$post_data['last_post'] = ( $post_info['topic_last_post_id'] == $post_id ) ? true : false;
			$post_data['last_topic'] = ( $post_info['forum_last_post_id'] == $post_id ) ? true : false;
			$post_data['has_poll'] = ( $post_info['topic_vote'] ) ? true : false; 
			$post_data['topic_type'] = $post_info['topic_type'];
			$post_data['poster_id'] = $post_info['poster_id'];
	
			if ( $post_data['first_post'] && $post_data['has_poll'] )
			{
				$sql = "SELECT * 
					FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr 
					WHERE vd.topic_id = $topic_id 
						AND vr.vote_id = vd.vote_id 
					ORDER BY vr.vote_option_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					echo '-:Could not obtain vote data for this topic';
					return;
				}
	
				$poll_options = array();
				$poll_results_sum = 0;
				if ( $row = $db->sql_fetchrow($result) )
				{
					$poll_title = $row['vote_text'];
					$poll_id = $row['vote_id'];
					$poll_length = $row['vote_length'] / 86400;
	
					do
					{
						$poll_options[$row['vote_option_id']] = $row['vote_option_text']; 
						$poll_results_sum += $row['vote_result'];
					}
					while ( $row = $db->sql_fetchrow($result) );
				}
				$db->sql_freeresult($result);
	
				$post_data['edit_poll'] = ( ( !$poll_results_sum || $is_auth['auth_mod'] ) && $post_data['first_post'] ) ? true : 0;
			}
			else 
			{
				$post_data['edit_poll'] = ($post_data['first_post'] && $is_auth['auth_pollcreate']) ? true : false;
			}
			
			//
			// Can this user edit/delete the post/poll?
			//
			if ( $post_info['poster_id'] != $userdata['user_id'] && !$is_auth['auth_mod'] )
			{
				$message = ( $delete || $mode == 'delete' ) ? $lang['Delete_own_posts'] : $lang['Edit_own_posts'];
				$message .= '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id") . '">', '</a>');
	
				message_die(GENERAL_MESSAGE, $message);
			}
			else if ( !$post_data['last_post'] && !$is_auth['auth_mod'] && ( $mode == 'delete' || $delete ) )
			{
				echo '-:' . $lang['Cannot_delete_replied'];
				return;
			}
			else if ( !$post_data['edit_poll'] && !$is_auth['auth_mod'] && ( $mode == 'poll_delete' || $poll_delete ) )
			{
				echo '-:' . $lang['Cannot_delete_poll'];
				return;
			}
		}
		else
		{	
			$post_data['first_post'] = ( $mode == 'newtopic' ) ? true : 0;
			$post_data['last_post'] = false;
			$post_data['has_poll'] = false;
			$post_data['edit_poll'] = false;
		}
		if ( $mode == 'poll_delete' && !isset($poll_id) )
		{
			echo '-:' . $lang['No_such_post'];
			return;
		}
	}
	else
	{
		echo '-:' . $lang['No_such_post'];
		return;
	}

	$return_message = $return_meta = '';

	delete_post($mode, $post_data, $return_message, $return_meta, $forum_id, $topic_id, $post_id, $poll_id);
	$user_id = ( $mode == 'reply' || $mode == 'newtopic' ) ? $userdata['user_id'] : $post_data['poster_id'];
	update_post_stats($mode, $post_data, $forum_id, $topic_id, $post_id, $user_id);
	sync('forum', $forum_id);

	if ($post_data['last_post'] == '$post_id' && $post_data['first_post'] == '$post_id')
	{
		echo '+:';
		
		$template->set_filenames(array('no_more_topic' => 'message_body.tpl'));
		
		$template->assign_vars(array(
			'U_INDEX' => append_sid('index.'.$phpEx),
			'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => $lang['AJAXed_deleted_topic'])
		);
		
		$template->pparse('no_more_topic');
		
		return;
	}
	else
	{
		echo '+:yes';
		
		return;
	}
}

function get_editor($post_id)
{
	global $db, $is_auth, $forum_id, $HTTP_GET_VARS, $userdata, $lang, $template, $phpEx, $board_config;

	preg_match('/^[^a-zA-Z]+$/', $post_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	$template->assign_block_vars('editor', array());
	
	if ( !$board_config['AJAXed_inline_post_editing'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}

	$sql = "SELECT  pt.bbcode_uid, pt.post_text 
		FROM " . POSTS_TABLE . " p, " . POSTS_TEXT_TABLE . " pt
		WHERE p.post_id = $post_id
			AND pt.post_id = p.post_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get post info';
		return;
	}
	$row = $db->sql_fetchrow($result);
	
	$bbcode_uid = $row['bbcode_uid'];

	if ( !$is_auth['auth_edit'] || $HTTP_GET_VARS['sid'] != $userdata['session_id'] )
	{
		$message = $row['post_text'];
		if ( !$board_config['allow_html'] )
		{
			if ( $row['enable_html'] )
			{
				$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
			}
		}
		
		if ( $board_config['allow_bbcode'] )
		{
			if ( $bbcode_uid != '' )
			{
				$message = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $message);
			}
		}
		$message = make_clickable($message);
		
		if ( $board_config['allow_smilies'] )
		{
			if ( $row['enable_smilies'] )
			{
				$message = smilies_pass($message);
			}
		}
	
		if ( $userdata['user_level'] < 1 && !$userdata['user_allowswearywords'] )
        {
			$orig_word = $replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);
		}
				
		if ( !empty($orig_word) )
		{
			$message = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
		}
	
		$message = str_replace("\n", "\n<br />\n", stripslashes($message));
	
		echo '+:' . $lang['AJAXed_editor_premission'] . stripslashes($message);
		
		return;
	}

	$template->set_filenames(array('editor' => 'ajaxed_editor.tpl'));

	$template->assign_vars(array(
//		'MESSAGE' => convert_unicode(utf8_encode(preg_replace('/\:(([a-z0-9]:)?)' . $row['bbcode_uid'] . '/s', '', $row['post_text']))),
		'MESSAGE' => preg_replace('/\:(([a-z0-9]:)?)' . $row['bbcode_uid'] . '/s', '', $row['post_text']),
		'POST_ID' => $post_id,
		'L_COMPLETE_EDIT' => $lang['Submit'],
		'L_CANCEL_EDIT' => $lang['Cancel'],
		'L_PREVIEW' => $lang['Preview'],
		'L_FULL_MODE' => $lang['AJAXed_Go_To_Full_Mode'],
		'L_ADD_UPDATE' => $lang['AJAXed_add_update'])
	);
		
	echo '+: ';
	
	$template->pparse('editor');
	
	return;
}

function check_username($search_match)
{
	global $db, $board_config, $template, $userdata, $lang, $images, $theme, $phpEx, $phpbb_root_path;
	
	if ( !$board_config['AJAXed_username_check'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	
	$username_list = '';
	if ( !empty($search_match) )
	{
		$username_search = preg_replace('/\*/', '%', phpbb_clean_username($search_match));

		$sql = "SELECT username 
			FROM " . USERS_TABLE . " 
			WHERE username LIKE '" . str_replace("\'", "''", $username_search) . "' 
				AND user_id <> " . ANONYMOUS . "
			ORDER BY username";
		if ( !($result = $db->sql_query($sql)) )
		{
			echo '-:Could not obtain search results';
			return;
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			if ( $row['username'] != $userdata['username'] )
			{
				$username .= 'W' . $lang['AJAXed_check_username1'];
			}
			else
			{
				$username .= 'C' . $lang['AJAXed_check_username2'];
			}
		}
		else
		{
			$username .= 'C' . $lang['AJAXed_check_username3'];
		}
		$db->sql_freeresult($result);
	}
	echo '+:' . $username;
	
	return;
}

function post_menu($a, $b)
{
	global $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang, $userdata, $topics_id, $is_auth;

	preg_match('/^[^a-zA-Z]+$/', $a, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$board_config['AJAXed_post_menu'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	
	echo '+:';
	
	$ip_onclick = $delete_onclick = '';
	
	$sql = "SELECT * 
		FROM " . POSTS_TABLE . " 
		WHERE post_id = $a";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not obtain post information';
		return;
	}
	$row = $db->sql_fetchrow($result);
	
	$template->assign_block_vars('ajaxed_menu', array());
	
	if ( ( $userdata['user_id'] == $row['poster_id'] && $is_auth['auth_edit'] ) || $is_auth['auth_mod'] )
	{
		$edit_url = append_sid("posting.$phpEx?mode=editpost&amp;" . POST_POST_URL . "=" . $a);
		$template->assign_block_vars('ajaxed_menu.edit', array('URL' => $edit_url,));
		if ( $board_config['AJAXed_inline_post_editing'] )
		{
			$template->assign_block_vars('ajaxed_menu.edit.quick', array());
		}
	}
	
	if ( ( $userdata['user_id'] == $row['poster_id'] && $is_auth['auth_delete'] ) || $is_auth['auth_mod'] )
	{
		if ( $board_config['AJAXed_post_delete'] )
		{
			$delete_onclick .= "onClick=\"pd('" . $a . "','" . $b . "');aa('misc',' '); return false;\" ";
		}
		$delete_url = "posting.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $a . "&amp;sid=" . $userdata['session_id'];
		$template->assign_block_vars('ajaxed_menu.delete', array('ONCLICK' => $delete_onclick,'URL' => $delete_url));
	}
	
	if ( $is_auth['auth_mod'] )
	{
		if ( $board_config['AJAXed_post_ip'] )
		{
			$ip_onclick .= "onClick=\"pi('" . $a . "'); return false;\" ";
		}
		$ip_url = "modcp.$phpEx?mode=ip&amp;" . POST_POST_URL . "=" . $a . "&amp;" . POST_TOPIC_URL . "=" . $topics_id . "&amp;sid=" . $userdata['session_id'];
		$template->assign_block_vars('ajaxed_menu.ip', array('ONCLICK' => $ip_onclick, 'URL' => $ip_url));
	}

	$qoute_url = append_sid("posting.$phpEx?mode=quote&amp;" . POST_POST_URL . "=" . $a);

	$template->assign_vars(array(
		'L_MENU' => $lang['AJAXed_post_menu'],
		'L_QOUTE' => $lang['Reply_with_quote'],
		'L_QUICK_EDIT' => $lang['AJAXed_quick_edit'],
		'L_NORMAL_EDIT' => $lang['AJAXed_normal_edit'],
		'L_DELETE_POST' => $lang['Delete_post'],
		'L_VIEW_IP' => $lang['AJAXed_view_ip'],
		'POST_ID' => $a,
		'I' => $b,
		'QOUTE_URL' => $qoute_url)
	);
	
	$template->set_filenames(array('user_list' => 'ajaxed_post_menu.tpl'));
	
	$template->pparse('user_list');
	
	return;
}

function post_ip($a,$b)
{
	global $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang, $userdata, $forum_id, $is_auth;

	preg_match('/^[^a-zA-Z]+$/', $a, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$userdata['user_level'] || !$board_config['AJAXed_post_ip'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	
	echo '+:';
	
	$template->assign_block_vars('ajaxed_ip', array());
	
	// Look up relevent data for this post
	$sql = "SELECT poster_ip, poster_id 
		FROM " . POSTS_TABLE . " 
		WHERE post_id = $a
			AND forum_id = $forum_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not get poster IP information';
		return;
	}
	
	if ( !($post_row = $db->sql_fetchrow($result)) )
	{
		echo '-:' . $lang['No_such_post'];
		return;
		message_die(GENERAL_MESSAGE, $lang['No_such_post']);
	}
	
	$ip_this_post = decode_ip($post_row['poster_ip']);
	$poster_id = $post_row['poster_id'];
	
	if ($b == 1)
	{
		echo gethostbyaddr($ip_this_post);
	}
	else if ($b == 2)
	{
		echo $ip_this_post;
	}
	else
	{
		$template->assign_vars(array(
			'L_MENU' => $lang['AJAXed_post_ip'],
			'L_BACK' => $lang['AJAXed_post_back'],
			'L_THIS_POST_IP' => $lang['This_posts_IP'],
			'L_LOOKUP_IP' => $lang['Lookup_IP'],
			'ONCLICK' => "onClick=\"pa('" . $a . "'); return false;\" ",
			'U_LOOKUP_IP' => "modcp.$phpEx?mode=ip&amp;" . POST_POST_URL . "=$post_id&amp;" . POST_TOPIC_URL . "=$topic_id&amp;rdns=$ip_this_post&amp;sid=" . $userdata['session_id'],
			'ONCLICK_BACK' => "onClick=\"ug('" . $a . "'); return false;\" ",
			'IP_ADDRESS' => $ip_this_post)
		);
		
		//
		// Get other IP's this user has posted under
		//
		$sql = "SELECT poster_ip, COUNT(*) AS postings 
			FROM " . POSTS_TABLE . " 
			WHERE poster_id = $poster_id 
			GROUP BY poster_ip 
			ORDER BY " . (( SQL_LAYER == 'msaccess' ) ? 'COUNT(*)' : 'postings' ) . " DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			echo '-:Could not get IP information for this user';
			return;
			message_die(GENERAL_ERROR, 'Could not get IP information for this user', '', __LINE__, __FILE__, $sql);
		}
	
		if ( $row = $db->sql_fetchrow($result) )
		{
			$i = 0;
			do
			{
				if ( $row['poster_ip'] == $post_row['poster_ip'] )
				{
					$template->assign_vars(array(
						'POSTS' => $row['postings'] . ' ' . ( ( $row['postings'] == 1 ) ? $lang['Post'] : $lang['Posts'] ))
					);
					continue;
				}
				$i++; 
			}
			while ( $row = $db->sql_fetchrow($result) );
		}
		
		$template->set_filenames(array('user_list' => 'ajaxed_post_menu.tpl'));
		
		$template->pparse('user_list');
	}
	
	return;
}

function watch_topic()
{
	global $template, $db, $board_config, $phpEx, $lang, $userdata, $topics_id;

	preg_match('/^[^a-zA-Z]+$/', $topics_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$board_config['AJAXed_topic_watch'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	
	echo '+:';
	
	$sql = "SELECT notify_status
		FROM " . TOPICS_WATCH_TABLE . "
		WHERE topic_id = $topics_id
			AND user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not obtain topic watch information';
		return;
	}
	$row = $db->sql_fetchrow($result);
	
	$ajaxed_watch_topic = ( $board_config['AJAXed_topic_watch'] ) ? ' onClick="uh(); return false;"' : '';
	$s_watching_topic = '';
	
	if ( !$row )
	{
		$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
		$sql = "INSERT $sql_priority INTO " . TOPICS_WATCH_TABLE . " (user_id, topic_id, notify_status)
			VALUES (" . $userdata['user_id'] . ", $topics_id, 0)";
		if ( !($result = $db->sql_query($sql)) )
		{
			echo '-:Could not insert topic watch information';
			return;
		}
		$s_watching_topic = "<a " . $ajaxed_watch_topic ."href=\"viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;unwatch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '">' . $lang['Stop_watching_topic'] . '</a>';
	}
	else
	{
		$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
		$sql = "DELETE $sql_priority FROM " . TOPICS_WATCH_TABLE . "
			WHERE topic_id = $topics_id
				AND user_id = " . $userdata['user_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			echo '-:Could not delete topic watch information';
			return;
		}
		$s_watching_topic = "<a " . $ajaxed_watch_topic ."href=\"viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;watch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '">' . $lang['Start_watching_topic'] . '</a>';
	}
	
	echo $s_watching_topic;
	
	return;
}

function poll_menu($switch)
{
	global $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang, $userdata, $topics_id, $is_auth;

	preg_match('/^[^a-zA-Z]+$/', $topics_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$board_config['AJAXed_poll_menu'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	
	echo '+:';

	$sql = "SELECT topic_poster, topic_first_post_id
		FROM " . TOPICS_TABLE . " 
		WHERE topic_id = $topics_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not obtain post information';
		return;
	}
	$row = $db->sql_fetchrow($result);
	
	$template->assign_block_vars('ajaxed_poll', array());

	$sql = "SELECT vd.vote_id, vd.vote_text, vd.vote_start, vd.vote_length, vr.vote_option_id, vr.vote_option_text, vr.vote_result
		FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
		WHERE vd.topic_id = $topics_id
			AND vr.vote_id = vd.vote_id
		ORDER BY vr.vote_option_id ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not obtain vote data for this topic';
		return;
	}

	if ( $vote_info = $db->sql_fetchrowset($result) )
	{
		$db->sql_freeresult($result);
		$vote_id = $vote_info[0]['vote_id'];
		
		$sql = "SELECT vote_id
			FROM " . VOTE_USERS_TABLE . "
			WHERE vote_id = $vote_id
				AND vote_user_id = " . intval($userdata['user_id']);
		if ( !($result = $db->sql_query($sql)) )
		{
			echo '-:Could not obtain user vote data for this topic';
			return;
		}
		
		$user_voted = ( $user_vote = $db->sql_fetchrow($result) ) ? TRUE : 0;
		$db->sql_freeresult($result);
		
		$poll_expired = ( $vote_info[0]['vote_length'] ) ? ( ( $vote_info[0]['vote_start'] + $vote_info[0]['vote_length'] < time() ) ? TRUE : 0 ) : 0;

		if ( $user_voted || $poll_expired )
		{
			$menu = $lang['AJAXed_poll_mod'];
		}
		else
		{
			$menu = $lang['AJAXed_poll_menu'];
			$results_onclick = "onClick=\"ti(); te(); return false;\" ";
			if($switch)
			{
				$results = $lang['AJAXed_poll_cast'];
				$template->assign_block_vars('ajaxed_poll.view', array('ONCLICK' => $results_onclick, 'URL' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topics_id&amp;postdays=0&amp;postorder=asc&amp;vote=viewresult")));
			}
			else
			{
				$results = $lang['View_results'];
				$template->assign_block_vars('ajaxed_poll.view', array('ONCLICK' => $results_onclick, 'URL' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topics_id&amp;postdays=0&amp;postorder=asc&amp;vote=viewresult")));
			}
		}
	
		if ( ( $userdata['user_id'] == $row['topic_poster'] && $is_auth['auth_edit'] ) || $is_auth['auth_mod'] )
		{
			$template->assign_block_vars('ajaxed_poll.edit', array());
			if ( $user_voted || $poll_expired ) { }
			else
			{
				$template->assign_block_vars('ajaxed_poll.edit.viewer', array());
			}
			if ( $board_config['AJAXed_poll_title'] )
			{
				$title_onclick = "onClick=\"oc('poll_title', 'poll_edit_title'); te(); return false;\" ";
				$template->assign_block_vars('ajaxed_poll.edit.title', array('ONCLICK' => $title_onclick, 'URL' => $edit_url));
			}
			if ( $board_config['AJAXed_poll_options'] )
			{
				$options_onclick = "onClick=\"tg(); te(); return false;\" ";
				$template->assign_block_vars('ajaxed_poll.edit.options', array('ONCLICK' => $options_onclick, 'URL' => $edit_url));
			}
		}
		$edit_url = append_sid("posting.$phpEx?mode=editpost&amp;" . POST_POST_URL . "=" . $row['topic_first_post_id']);

		$template->assign_vars(array(
			'L_MENU' => $menu,
			'L_VIEW_RESULTS' => $results,
			'L_POLL_MOD' => $lang['AJAXed_poll_mod'],
			'L_EDIT_TITLE' => $lang['AJAXed_poll_title'],
			'L_EDIT_OPTIONS' => $lang['AJAXed_poll_options'],
			'U_EDIT' => $edit_url)
		);
		
		$template->set_filenames(array('poll_menu' => 'ajaxed_post_menu.tpl'));
		
		$template->pparse('poll_menu');
	}
	
	return;
}

function poll_title($title)
{
	global $db, $board_config, $lang, $topics_id, $is_auth;

	preg_match('/^[^a-zA-Z]+$/', $topics_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$board_config['AJAXed_poll_title'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	
	echo '+:';
	
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
	
	$sql = "UPDATE " . VOTE_DESC_TABLE . " 
		SET vote_text = '" . str_replace("\'", "''", $title) . "'
		WHERE topic_id = $topics_id";
	if ( !$db->sql_query($sql) )
	{
		echo '-:Could not update post subject';
		return;
	}
	
	if ( sizeof($orig_word) )
	{
		$title = preg_replace($orig_word, $replacement_word, $title);
	}
	
	echo $title;
	
	return;
}

function poll_options()
{
	global $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang, $userdata, $topics_id, $is_auth;

	preg_match('/^[^a-zA-Z]+$/', $topics_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$board_config['AJAXed_poll_options'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	
	echo '+:';

	$sql = "SELECT * 
		FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr 
		WHERE vd.topic_id = $topics_id 
			AND vr.vote_id = vd.vote_id 
		ORDER BY vr.vote_option_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not obtain vote data for this topic';
		return;
	}

	$poll_options = array();
	$poll_results_sum = 0;
	
	if ( $row = $db->sql_fetchrow($result) )
	{
		$poll_title = $row['vote_text'];
		$poll_id = $row['vote_id'];
		$poll_length = $row['vote_length'] / 86400;

		do
		{
			$poll_options[$row['vote_option_id']] = $row['vote_option_text']; 
			$poll_results_sum += $row['vote_result'];
		}
		while ( $row = $db->sql_fetchrow($result) );
	}
	$db->sql_freeresult($result);

	if( !empty($poll_options) )
	{
		while( list($option_id, $option_text) = each($poll_options) )
		{
			$template->assign_block_vars('poll_option_rows', array(
				'POLL_OPTION' => str_replace('"', '&quot;', $option_text), 

				'S_POLL_OPTION_NUM' => $option_id)
			);
		}
	}

	$template->assign_vars(array( 
		'L_POLL_OPTION' => $lang['Poll_option'],  
		'L_ADD_OPTION' => $lang['Add_option'],
		'L_UPDATE_OPTION' => $lang['Update'],
		'L_DELETE_OPTION' => $lang['Delete'],
		'L_EDIT_OPTIONS' => $lang['AJAXed_poll_options'],
		'L_CANCEL' => $lang['AJAXed_close'])
	);
	
	$template->set_filenames(array('ajaxed' => 'ajaxed_poll_options.tpl'));
	
	$template->pparse('ajaxed');
	
	return;
}

function poll_option_update($check, $poll_id, $poll_option)
{
	global $db, $board_config, $lang, $topics_id, $is_auth;

	preg_match('/^[^a-zA-Z]+$/', $poll_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$board_config['AJAXed_poll_options'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	
	if ($poll_id <= 0 )
	{
		return;
	}
	
	echo '+:';
	
	$sql = "SELECT vote_id
		FROM " . VOTE_DESC_TABLE . " 
		WHERE topic_id = $topics_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not obtain vote data for this topic';
		return;
	}
	
	if ( $vote_info = $db->sql_fetchrow($result) )
	{
		if(!$check)
		{
			$sql = "UPDATE " . VOTE_RESULTS_TABLE . " SET
				vote_option_text = '" . str_replace("\'", "''", $poll_option) . "'
				WHERE vote_option_id = $poll_id
				AND vote_id = " . $vote_info['vote_id'];
			if ( !$db->sql_query($sql) )
			{
				echo '-:Could not update poll result';
				return;
			}
		}
		else
		{
			$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " 
				WHERE vote_option_id = $poll_id
				AND vote_id = " . $vote_info['vote_id'];
			if ( !$db->sql_query($sql) )
			{
				echo '-:Could not delete poll result';
				return;
			}
		}
	}
	
	return;
}

function poll_view($view)
{
	global $template, $db, $board_config, $theme, $phpbb_root_path, $phpEx, $lang, $images, $userdata, $topics_id, $is_auth;

	preg_match('/^[^a-zA-Z]+$/', $topics_id, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$board_config['AJAXed_poll_menu'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	
	echo '+:';
	
	$sql = "SELECT vd.vote_id, vd.vote_text, vd.vote_start, vd.vote_length, vr.vote_option_id, vr.vote_option_text, vr.vote_result
		FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
		WHERE vd.topic_id = $topics_id
			AND vr.vote_id = vd.vote_id
		ORDER BY vr.vote_option_id ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not obtain vote data for this topic';
		return;
	}

	if ( $vote_info = $db->sql_fetchrowset($result) )
	{
		$db->sql_freeresult($result);
		$vote_options = sizeof($vote_info);

		$vote_id = $vote_info[0]['vote_id'];
		$vote_title = $vote_info[0]['vote_text'];

		$sql = "SELECT vote_id
			FROM " . VOTE_USERS_TABLE . "
			WHERE vote_id = $vote_id
				AND vote_user_id = " . intval($userdata['user_id']);
		if ( !($result = $db->sql_query($sql)) )
		{
			echo '-:Could not obtain user vote data for this topic';
			return;
		}

		$user_voted = ( $row = $db->sql_fetchrow($result) ) ? TRUE : 0;
		$db->sql_freeresult($result);

		if ( isset($HTTP_GET_VARS['vote']) || isset($HTTP_POST_VARS['vote']) )
		{
			$view_result = ( ( ( isset($HTTP_GET_VARS['vote']) ) ? $HTTP_GET_VARS['vote'] : $HTTP_POST_VARS['vote'] ) == 'viewresult' ) ? TRUE : 0;
		}
		else
		{
			$view_result = 0;
		}

		$poll_expired = ( $vote_info[0]['vote_length'] ) ? ( ( $vote_info[0]['vote_start'] + $vote_info[0]['vote_length'] < time() ) ? TRUE : 0 ) : 0;


		if ($view)
		{
			$template->set_filenames(array(
				'pollbox' => 'viewtopic_poll_ballot.tpl')
			);

			if ( $board_config['null_vote'] )
			{
				$template->assign_block_vars('switch_null_vote', array());
			}

			for($i = 0; $i < $vote_options; $i++)
			{
				if ( sizeof($orig_word) )
				{
					$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
				}

				$template->assign_block_vars('poll_option', array(
					'POLL_OPTION_ID' => $vote_info[$i]['vote_option_id'],
					'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'])
				);
			}

			$template->assign_vars(array(
				'L_SUBMIT_VOTE' => $lang['Submit_vote'],
				'L_VIEW_RESULTS' => $lang['View_results'],
				'L_NULL_VOTE' => $lang['Null_vote'],

				'U_VIEW_RESULTS' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;vote=viewresult"))
			);

			$s_hidden_fields = '<input type="hidden" name="topic_id" value="' . $topic_id . '" /><input type="hidden" name="mode" value="vote" />';
		}
		else
		{
			$template->set_filenames(array(
				'pollbox' => 'viewtopic_poll_result.tpl')
			);

			$vote_results_sum = 0;

			for($i = 0; $i < $vote_options; $i++)
			{
				$vote_results_sum += $vote_info[$i]['vote_result'];
			}

			$vote_graphic = 0;
			$vote_graphic_max = sizeof($images['voting_graphic']);

			for($i = 0; $i < $vote_options; $i++)
			{
				$vote_percent = ( $vote_results_sum > 0 ) ? $vote_info[$i]['vote_result'] / $vote_results_sum : 0;
				$vote_graphic_length = round($vote_percent * $board_config['vote_graphic_length']);

				$vote_graphic_img = $images['voting_graphic'][$vote_graphic];
				$vote_graphic = ($vote_graphic < $vote_graphic_max - 1) ? $vote_graphic + 1 : 0;

				if ( sizeof($orig_word) )
				{
					$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
				}

				$template->assign_block_vars('poll_option', array(
					'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'],
					'POLL_OPTION_RESULT' => $vote_info[$i]['vote_result'],
					'POLL_OPTION_PERCENT' => sprintf("%.1d%%", ($vote_percent * 100)),

					'POLL_OPTION_LCAP' => $images['voting_graphic_lcap'],
					'POLL_OPTION_RCAP' => $images['voting_graphic_rcap'],
					'POLL_OPTION_IMG' => $vote_graphic_img,
					'POLL_OPTION_IMG_WIDTH' => $vote_graphic_length)
				);
			}

			$template->assign_vars(array(
				'L_TOTAL_VOTES' => $lang['Total_votes'],
				'TOTAL_VOTES' => $vote_results_sum)
			);
		}

		if ( sizeof($orig_word) )
		{
			$vote_title = preg_replace($orig_word, $replacement_word, $vote_title);
		}

		$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

		if ( $is_auth['auth_mod'] )
		{
			$ajaxed_poll_menu = ( $board_config['AJAXed_poll_menu'] ) ? "tc(tB);" : "";
		}

		$sql_votes = mysql_query("SELECT * FROM " . VOTE_DESC_TABLE. " vote_desc ORDER BY vote_id"); 
		for ($v = 0; $v < mysql_num_rows($sql_votes); $v++) 
		{
		 	if (mysql_result($sql_votes, $v, "vote_id") == $vote_id) 
		 	{
		 		$vote_nr = $v; 
		    }
		    $vote_end = mysql_result($sql_votes, $vote_nr, "vote_start") + mysql_result($sql_votes, $vote_nr, "vote_length"); 
		    
		    if (time() < $vote_end) 
			{ 
				$vote_end = sprintf($lang['Vote_until']) . ': ' . date('m.d.Y H:i:s', $vote_end); 
			} 
		    else if (mysql_result($sql_votes, $vote_nr, "vote_length") == 0) 
			{ 
				$vote_end = sprintf($lang['Vote_endless']); 
			} 
		    else 
			{ 
				$vote_end = sprintf($lang['Vote_closed']); 
			} 
		}

		$template->assign_vars(array(
			'POLL_QUESTION' => $vote_title,
			'AJAXED_POLL_MENU' => $ajaxed_poll_menu,
			'AJAXED_POLL_OPTION_COUNT' => $vote_options, 
			'SAVE' => $lang['Submit'],
			'CANCEL' => $lang['Cancel'],

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_POLL_ACTION' => append_sid("posting.$phpEx?mode=vote&amp;" . POST_TOPIC_URL . "=$topic_id"),
			'U_POLL_NULL_VOTE' => append_sid("posting.$phpEx?mode=vote&amp;" . POST_TOPIC_URL . "=$topic_id&amp;vote=-1"),
		    'VOTE_END' => $vote_end)
		);

		$template->pparse('pollbox');
	}

	return;
}

function poll_vote($option)
{
	global $db, $board_config, $lang, $topics_id, $is_auth, $userdata, $user_ip;

	preg_match('/^[^a-zA-Z]+$/', $option, $Rid);
	if(!$Rid) 
	{
		echo '-:' . $lang['AJAXed_Invaild_ID'];
		return;
	}

	if ( !$board_config['AJAXed_poll_menu'] )
	{
		echo '-:' . $lang['AJAXed_moduale_disabled'];
		return;
	}
	
	if($option <= 0 )
	{
		return;
	}
	
	$sql = "SELECT vd.vote_id    
		FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
		WHERE vd.topic_id = $topics_id 
			AND vr.vote_id = vd.vote_id 
			AND vr.vote_option_id = $option
		GROUP BY vd.vote_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		echo '-:Could not obtain vote data for this topic';
		return;
	}
	
	if ( $vote_info = $db->sql_fetchrow($result) )
	{
		$vote_id = $vote_info['vote_id'];
		$sql = "SELECT * 
			FROM " . VOTE_USERS_TABLE . "  
			WHERE vote_id = $vote_id 
				AND vote_user_id = " . $userdata['user_id'];
		if ( !($result2 = $db->sql_query($sql)) )
		{
			echo '-:Could not obtain user vote data for this topic';
			return;
		}
		
		if ( !($row = $db->sql_fetchrow($result2)) )
		{
			$sql = "UPDATE " . VOTE_RESULTS_TABLE . " 
				SET vote_result = vote_result + 1 
				WHERE vote_id = $vote_id 
					AND vote_option_id = $option";
			if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
			{
				echo '-:Could not update poll result';
				return;
			}
			
			$sql = "INSERT INTO " . VOTE_USERS_TABLE . " (vote_id, vote_user_id, vote_user_ip) 
				VALUES ($vote_id, " . $userdata['user_id'] . ", '$user_ip')";
			if ( !$db->sql_query($sql, END_TRANSACTION) )
			{
				echo '-:Could not insert user_id for poll';
				return;
			}
		}
		$db->sql_freeresult($result2);
	}
	$db->sql_freeresult($result);
	
	$false = '';
	poll_view($false);
	
	return;
}


function convert_unicode($t)
{
	return preg_replace('#%u([0-9A-F]{1,4})#ie', "'&#' . hexdec('\\1') . ';'", html_entity_decode($t, ENT_NOQUOTES, 'iso-8859-1'));
}

?>
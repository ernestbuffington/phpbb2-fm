<?php
/** 
*
* @package phpBB2
* @version $Id: posting_notes.php,v 1.159.2.22 2003/07/11 16:46:16 oxpus Exp $
* @copyright (c) 2003 OXPUS
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

//
// Check and set various parameters
//
$params = array('submit' => 'post', 'mode' => 'mode', 'post_id' => POST_POST_URL);
while( list($var, $param) = @each($params) )
{
	if ( !empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]) )
	{
		$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? htmlspecialchars($HTTP_POST_VARS[$param]) : htmlspecialchars($HTTP_GET_VARS[$param]);
	}
	else
	{
		$$var = '';
	}
}

$post_id = intval($post_id);
$sid = (isset($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : 0;

if ( $mode == 'smilies' )
{
	generate_smilies('window', PAGE_POSTING);
	exit;
}

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_POSTING);
init_userprefs($userdata);
//
// End session management
//

// Check for existing session
if ( !$userdata['session_logged_in'] )
{
	redirect(append_sid("login.$phpEx", true) . '');
}

//
// Is user notes disabled?
//
if ( !$board_config['enable_user_notes'] )
{ 
	redirect('profile.'.$phpEx.'?mode=editprofile&ucp=main'); 
	exit; 
} 

// --------------------
//  What shall we do?
//

if ($mode == 'delete')
{
	$sql = "DELETE FROM " . NOTES_TABLE . "
		WHERE post_id = " . $post_id . "
			AND poster_id = " . $userdata['user_id'] . " ";
	if (!$db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Error in deleting post', '', __LINE__, __FILE__, $sql);
	}
	redirect(append_sid("profile_notes.$phpEx", true) . '');
}

if ($submit)
{
	$post_id = ( !empty($HTTP_POST_VARS[POST_POST_URL]) ) ? $HTTP_POST_VARS[POST_POST_URL] : $HTTP_GET_VARS[POST_POST_URL];
	$post_id = intval($post_id);
	
	$subject = ( isset($HTTP_POST_VARS['subject']) ) ? $HTTP_POST_VARS['subject'] : $HTTP_GET_VARS['subject'];
	$message = ( isset($HTTP_POST_VARS['message']) ) ? $HTTP_POST_VARS['message'] : $HTTP_GET_VARS['message'];
	$subject = trim($subject);
	$message = trim($message);

	$bbcode_on = $board_config['allow_bbcode'];
	$smilie_on = $board_config['allow_smilies'];
	
	if (!empty($message))
	{
		$bbcode_uid = ($board_config['allow_bbcode']) ? make_bbcode_uid() : '';
		$message = prepare_message(trim($message), 1, $bbcode_on, $smilie_on, $bbcode_uid);
	}

	$bbcode_on = (intval($HTTP_POST_VARS['bbcode']) == 1) ? 0 : 1;
	$smilies_on = (intval($HTTP_POST_VARS['smilies']) == 1) ? 0 : 1;

	$subject = str_replace("\'", "''", $subject);
	$message = str_replace("\'", "''", $message);

	if ( $mode == 'newtopic' )
	{
		$sql = "INSERT INTO " . NOTES_TABLE . "	(post_subject, poster_id, post_time, post_text, bbcode_uid, bbcode, smilies) 
			VALUES ('$subject', " . $userdata['user_id'] . ", " . time() . ", '$message', '$bbcode_uid', $bbcode_on, $smilies_on)";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}
		redirect(append_sid("profile_notes.$phpEx", true) . '');
	}
	else if ( $mode == 'editpost' )
	{
		$sql = "UPDATE " . NOTES_TABLE . "
			SET post_subject = '$subject', post_text = '$message', bbcode_uid = '$bbcode_uid', bbcode = $bbcode_on, smilies = $smilies_on
			WHERE post_id = $post_id";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}
		redirect(append_sid("profile_notes.$phpEx", true) . '');
	}
}

//
// User default entry point
//
if ( $mode == 'newtopic' )
{
	$subject = $message = '';
	$page_title = $lang['Post_a_new_note'];
	$bbcode_on = $smilies_on = 1;
}

$uid = '';

if ( $mode == 'editpost' )
{	
	if ( isset($HTTP_GET_VARS[POST_POST_URL]))
	{
		$post_id = intval($HTTP_GET_VARS[POST_POST_URL]);
	}

	if ( empty($post_id) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_post_id']);
	}

	$sql = "SELECT * 
		FROM " . NOTES_TABLE . "
		WHERE post_id = " . (int) $post_id . "
			AND poster_id = " . $userdata['user_id'];
	if (!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't query notes table", '', __LINE__, __FILE__, $sql);
	}
	else
	{
		while( $row = $db->sql_fetchrow($result) )
		{
			$subject = $row['post_subject'];
			$bbcode_on = $row['bbcode'];
			$smilies_on = $row['smilies'];
			$uid = $row['bbcode_uid'];
			$message = $row['post_text'];

			if ( $row['bbcode_uid'] != '' )
			{
				$message = preg_replace('/\:(([a-z0-9]:)?)' . $uid . '/s', '', $message);
			}
		}
		$db->sql_freeresult($result);
	}
	
	$page_title = $lang['Edit_Note'];
}

//
// Include page header
//
if ( $userdata['user_popup_notes'] == TRUE )
{
	$gen_simple_header = TRUE;
	$template->assign_block_vars('switch_popup', array());
}
else
{
	$template->assign_block_vars('switch_no_popup', array());
}

include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'posting_notes_body.tpl')
);

$template->assign_vars(array(
	'L_POST_A' => $page_title,
	'L_POST_SUBJECT' => $lang['Post_subject'])
);

//
// BBCode toggle selection
//
if ( $board_config['allow_bbcode'] )
{
	$bbcode_status = $lang['BBCode_is_ON'];
	$template->assign_block_vars('switch_bbcode_checkbox', array(
		'S_BBCODE_CHECKED' => (!$bbcode_on) ? 'checked="checked"' : '')
	);
}
else
{
	$bbcode_status = $lang['BBCode_is_OFF'];
}

//
// Smilies toggle selection
//
if ( $board_config['allow_smilies'] )
{
	$smilies_status = $lang['Smilies_are_ON'];
	$template->assign_block_vars('switch_smilies_checkbox', array(
		'S_SMILIES_CHECKED' => (!$smilies_on) ? 'checked="checked"' : '')
	);
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

// Generate smilies listing for page output
if( $board_config['allow_smilies'] )
{
	generate_smilies('inline', PAGE_POSTING, $forum_id);
}

make_jumpbox('viewforum.'.$phpEx);

Multi_BBCode();

//
// Output the data to the template
//
$template->assign_vars(array(
	'EDITOR_NAME' => $userdata['username'],
	'SUBJECT' => $subject,
	'MESSAGE' => $message,
	'HTML_STATUS' => $html_status,
	'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'), 
	'SMILIES_STATUS' => $smilies_status, 

	'L_SUBJECT' => $lang['Subject'],
	'L_MESSAGE_BODY' => $lang['Message_body'],
	'L_OPTIONS' => $lang['Options'],
	'L_SPELLCHECK' => $lang['Spellcheck'],
	'L_COPY_TO_CLIPBOARD' => $lang['Copy_to_clipboard'],
	'L_COPY_TO_CLIPBOARD_EXPLAIN' => $lang['Copy_to_clipboard_explain'],
	'L_HIGHLIGHT_TEXT' => $lang['Highlight_text'],
	'L_CLOSE' => $lang['Close_window'],
	'L_CONFIRM_DELETE' => $lang['Confirm_delete'],
	'L_DISABLE_BBCODE' => $lang['Disable_BBCode_post'], 
	'L_DISABLE_SMILIES' => $lang['Disable_Smilies_post'], 

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
	'S_POST_ACTION' => append_sid('posting_notes.'.$phpEx.'?mode=' . $mode . '&amp;' . POST_POST_URL . '=' . $post_id))
);

while( list($key, $font) = each($lang['font']) )
{
	$template->assign_block_vars ('font_styles', array(
		'L_FONTNAME' => $font)
	);
}

if ($board_config['enable_spellcheck'])
{
	$template->assign_block_vars('switch_spellcheck', array());
}

include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
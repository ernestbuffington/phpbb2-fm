<?php
/** 
*
* @package phpBB2
* @version $Id:  profile_sig_editor.php,v 0.2 2002/12/04 16:46:16 whofarted Exp $
* @copyright (c) 2002 Illuminati Gaming Network
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
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=profile_sig_editor.".$phpEx); 
	exit; 
} 

if ( !$board_config['allow_sig'] )
{ 
	message_die(GENERAL_MESSAGE, $lang['Sigs_disabled'] . '<br /><br />' . sprintf($lang['Click_return_usercp'], '<a href="' . append_sid('profile.'.$phpEx.'?mode=editprofile&amp;ucp=main') . '">', '</a>'));
}


//
// Initial editing page, if user has a sig already
// the box will be filled in for them to edit
// 
if( !isset($HTTP_POST_VARS['submit']) )
{
	$page_title = $lang['Edit_Sig'];

	// Generate smilies listing for page output
	if( $board_config['allow_smilies'] )
	{
		generate_smilies('inline', PAGE_POSTING, $forum_id);
	}

	//
	// Include page header
	//
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'profile_sig_editor_body.tpl')
	);
	make_jumpbox('viewforum.'.$phpEx);

	Multi_BBCode();

	$html_status =  ( $userdata['user_allowhtml'] && $board_config['allow_html'] ) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
	$bbcode_status = ( $userdata['user_allowbbcode'] && $board_config['allow_bbcode']  ) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
	$smilies_status = ( $userdata['user_allowsmile'] && $board_config['allow_smilies']  ) ? $lang['Smilies_are_ON'] : (( $board_config['smilie_removal1'] ) ? $smilies_status = $lang['Smilies_are_REMOVED'] : $smilies_status = $lang['Smilies_are_OFF']);

	//
	// Output the data to the template
	//
	$template->assign_vars(array(
		'L_SIGNATURE_EXPLAIN' => $lang['Signature_explain'] . ' ' . (($board_config['max_sig_chars']) ? sprintf($lang['Max_signature_length'], $board_config['max_sig_chars']) : ''),

		'SIGNATURE' => $userdata['user_sig'] = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $userdata['user_sig']),
		'USER_ID' => $userdata['user_id'],

		'HTML_STATUS' => $html_status,
		'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'), 
		'SMILIES_STATUS' => $smilies_status,
		'MAX_SYMBOLS' => $board_config['max_sig_chars'],
		
		'L_SIGNATURE_PANEL' => $lang['Signature_panel'],
		'L_SIGNATURE' => $lang['Signature'],
		'L_OPTIONS' => $lang['Options'],
		'L_SPELLCHECK' => $lang['Spellcheck'],
		'L_COPY_TO_CLIPBOARD' => $lang['Copy_to_clipboard'],
		'L_COPY_TO_CLIPBOARD_EXPLAIN' => $lang['Copy_to_clipboard_explain'],
		'L_HIGHLIGHT_TEXT' => $lang['Highlight_text'],
		'L_PREVIEW' => $lang['Preview'],
		'L_SYMBOLS_LEFT' => $lang['Symbols_left'],

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
	
		'S_POST_ACTION' => append_sid('profile_sig_editor.'.$phpEx),
		'S_HIDDEN_FORM_FIELDS' => $hidden_form_fields)
	);

	if ( $board_config['max_sig_chars'] )
	{
		$template->assign_block_vars('switch_msg_length', array());
	}
	
	while( list($key, $font) = each($lang['font']) )
	{
		$template->assign_block_vars ('font_styles', array(
			'L_FONTNAME' => $font)
		);
	}
	
	if (!empty($board_config['enable_spellcheck']))
	{
		$template->assign_block_vars('switch_spellcheck', array());
	}

	//
	// Show preview stuff if user clicked preview
	//
	if(isset($HTTP_POST_VARS['preview']))
	{
		$user_sig = stripslashes($HTTP_POST_VARS['message']);
		$user_sig_bbcode_uid = ($userdata['user_sig_bbcode_uid']) ? $userdata['user_sig_bbcode_uid'] : make_bbcode_uid();
		
		$user_sig = bbencode_first_pass($user_sig, $user_sig_bbcode_uid);
	
		//
		// Finalise processing as per viewtopic
		//
		if ( !$board_config['allow_html'] )
		{
			if ( $user_sig != '' || !$userdata['user_allowhtml'] )
			{
				$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', '&lt;\2&gt;', $user_sig);
     		}
     	}
     	
		if ( $userdata['user_attachsig'] && $user_sig != '' && $user_sig_bbcode_uid )
		{
			$user_sig = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($user_sig, $user_sig_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $user_sig);
		}
		
		$user_sig = make_clickable($user_sig);
		
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
        
   		if( !empty($orig_word) )
		{
			$user_sig = preg_replace($orig_word, $replacement_word, $user_sig);
        }
        
        if ( $board_config['allow_smilies'] )
        {
			if( $userdata['user_allowsmile'] && $user_sig != '' )
			{
				$user_sig = smilies_pass($user_sig);
			}
		}
		
		$user_sig = '________________________<br />' . str_replace("\n", "\n<br />\n", $user_sig);
		$user_sig = word_wrap_pass($user_sig);
				
		$template->assign_block_vars('preview', array());
	
		$template->assign_vars(array(
			'L_SAMPLE_POST_1' => $lang['Sample_post_1'],
			'L_SAMPLE_POST_2' => $lang['Sample_post_2'],
			'SIGNATURE' => stripslashes($HTTP_POST_VARS[message]),
			'PRE_SIGNATURE' => $user_sig)
		);
	}		

	include($phpbb_root_path . 'profile_menu.'.$phpEx);

	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}


//
// update sig if they click submit
//
if (isset($HTTP_POST_VARS['submit']) )
{
	// Check character length against board limits...
	$user_sig = trim($HTTP_POST_VARS['message']);
	if (strlen($user_sig) > $board_config['max_sig_chars'] )
	{
		message_die(GENERAL_MESSAGE, $lang['Signature_too_long'] . ' ' . sprintf($lang['Max_signature_length'], $board_config['max_sig_chars']) . '<br /><br />' . sprintf($lang['Click_return_usercp'], '<a href="' . append_sid('profile_sig_editor.'.$phpEx) . '">', '</a>'));
	}

	// Check [img] BBCode tags against board limits... 
	if ( preg_match_all("#\[img\]((ht|f)tp://)([^\r\n\t<\"]*?)\[/img\]#sie", $user_sig, $matches) )
	{
		if (sizeof($matches[0]) > $board_config['sig_images_max_limit'] )
		{
			$l_too_many_images = ( $board_config['images_max_limit'] == 1 ) ? sprintf($lang['Too_many_sig_image'], $board_config['sig_images_max_limit']) : sprintf($lang['Too_many_sig_images'], $board_config['sig_images_max_limit']);
			message_die(GENERAL_MESSAGE, $l_too_many_images . '<br /><br />' . sprintf($lang['Click_return_usercp'], '<a href="' . append_sid('profile_sig_editor.'.$phpEx) . '">', '</a>'));
		}
		else
		{
			for ($i = 0; $i < sizeof($matches[0]); $i++)
			{
				$image = preg_replace("#\[img\](.*)\[/img\]#si", "\\1", $matches[0][$i]);
				list($width, $height) = @getimagesize($image);
				if( $width > $board_config['sig_images_max_width'] || $height > $board_config['sig_images_max_height'] )
				{
					$l_image_too_large = sprintf($lang['Sig_image_too_large'], $board_config['sig_images_max_width'], $board_config['sig_images_max_height']);
					message_die(GENERAL_MESSAGE, $l_image_too_large . '<br /><br />' . sprintf($lang['Click_return_usercp'], '<a href="' . append_sid('profile_sig_editor.'.$phpEx) . '">', '</a>'));
					break;
				}
			}
		}
	}

	// Limit signature rows...
	if (!empty($board_config['max_sig_lines']) && $board_config['max_sig_lines'] > 0)
	{
		$sig_lines = explode("\n", $user_sig);
		if (sizeof($sig_lines) > $board_config['max_sig_lines']) 
		{ 
			message_die(GENERAL_MESSAGE, sprintf($lang['Signature_too_high'], (sizeof($sig_lines) - $board_config['max_sig_lines'])) . '<br /><br />' . sprintf($lang['Click_return_usercp'], '<a href="' . append_sid('profile_sig_editor.'.$phpEx) . '">', '</a>'));
		}
	}

	$user_sig_bbcode_uid = ($userdata['user_sig_bbcode_uid']) ? $userdata['user_sig_bbcode_uid'] : make_bbcode_uid();
	$user_sig = bbencode_first_pass($user_sig, $user_sig_bbcode_uid);

	$sql = "UPDATE " . USERS_TABLE . " 
		SET user_sig = '" . str_replace("\'", "''", $user_sig) . "', user_sig_bbcode_uid = '" . $user_sig_bbcode_uid . "'
		WHERE user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update user signature.', '', __LINE__, __FILE__, $sql);
	}
	
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid('profile_sig_editor.'.$phpEx) . '">')
	);
	
	$message = $lang['Signature_update_success'] . '<br /><br />' . sprintf($lang['Click_return_usercp'], '<a href="' . append_sid('profile_sig_editor.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

	message_die(GENERAL_MESSAGE, $message);	
	
}

?>
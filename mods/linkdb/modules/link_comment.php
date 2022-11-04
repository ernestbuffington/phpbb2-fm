<?
/***************************************************************************
 *                             link_comment.php
 *                            ------------------
 *   begin                : Wednesday, Jan 1, 2003
 *   copyright            : (C) 2002 Illuminati Gaming Network
 *   email                : whofarted75@yahoo.com
 *   Modified by CRLin
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

class linkdb_comment extends linkdb_public
{
	function main($action)
	{
		global $template, $lang, $board_config, $phpEx, $linkdb_config, $db, $images, $userdata;
		global $_REQUEST, $_POST, $phpbb_root_path, $bbcode_tpl;
		global $html_entities_match, $html_entities_replace, $unhtml_specialchars_match, $unhtml_specialchars_replace;
		global $linkdb_functions;

		if(!$linkdb_config['allow_comment'])
		{
			message_die(GENERAL_MESSAGE, $lang['Not_allow_comment']);
		}

		if ( isset($_REQUEST['link_id']) )
		{
			$file_id = intval($_REQUEST['link_id']);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Link_not_exist']);
		}

		$sql = "SELECT f.*, u.user_id, u.username, u.user_level, COUNT(c.comments_id) as total_comments
			FROM " . LINKS_TABLE . " AS f
				LEFT JOIN ". USERS_TABLE ." AS u ON f.user_id = u.user_id
				LEFT JOIN " . LINK_COMMENTS_TABLE . " AS c ON f.link_id = c.link_id
			WHERE f.link_id = $file_id
			AND f.link_approved = 1
			GROUP BY f.link_id ";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query link info', '', __LINE__, __FILE__, $sql);
		}
		
		if(!$file_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['Link_not_exist']);
		}
		$db->sql_freeresult($result);

		$this->generate_category_nav($file_data['link_catid']);

		$file_poster = ( $file_data['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $file_data['user_id']) . '" target="_blank" class="postdetails">' : '';
		$file_poster .= ( $file_data['user_id'] != ANONYMOUS ) ? username_level_color($file_data['username'], $file_data['user_level'], $file_data['user_id']) : $file_data['post_username'] . ' (' . $lang['Guest'] . ')';
		$file_poster .= ( $file_data['user_id'] != ANONYMOUS ) ? '</a>' : '';
		
		$template->assign_vars(array(
			'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
			
			'U_INDEX' => append_sid('index.'.$phpEx),
			'U_LINK' => append_sid('linkdb.'.$phpEx),
			'U_FILE' => append_sid('linkdb.'.$phpEx.'?action=link&amp;link_id=' . $file_id),
			'FILE_NAME' => $file_data['link_name'],
			'FILE_DESC' => $file_data['link_longdesc'],
			'POST_IMAGE' => $images['icon_minipost'],
			'POST_IMAGE_ALT' => $lang['Post'],

			'L_DATE' => $lang['Posted'],
			'DATE' => create_date($board_config['default_dateformat'], $file_data['link_time'], $board_config['board_timezone']),
			'L_DOWNLOADS' => $lang['Hits'],
			'FILE_DLS' => $file_data['link_hits'],
			'L_SUBMITED_BY' => $lang['Submiter'],
			'POSTER' =>  $file_poster,

			'LINK_LOGO' => $this->display_banner($file_data, $file_data),
			'LINKS' => $lang['Linkdb'])
		); 
		
		include_once($phpbb_root_path . 'mods/linkdb/includes/functions_comment.'.$phpEx);
		include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
		include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);

		display_comments($file_data);

		if ( !isset($_REQUEST['post_comment']))
		{
			$this->display($lang['Linkdb'], 'link_comment_body.tpl');
			return;
		}

		if ( isset($_REQUEST['cid']) )
		{
			$cid = intval($_REQUEST['cid']);
		}

		$delete = (isset($_REQUEST['delete'])) ? intval($_REQUEST['delete']) : '';

		$submit = (isset($_POST['submit'])) ? TRUE : 0;
		$preview = (isset($_POST['preview'])) ? TRUE : 0;
		
		$subject = ( !empty($_POST['subject']) ) ? htmlspecialchars(trim(stripslashes($_POST['subject']))) : '';
		$message = ( !empty($_POST['message']) ) ? htmlspecialchars(trim(stripslashes($_POST['message']))) : '';

		$sql = 'SELECT link_name, link_catid
			FROM ' . LINKS_TABLE . " 
			WHERE link_id = $file_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt select Links table', '', __LINE__, __FILE__, $sql);
		}

		if(!$file_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['Link_not_exist']);
		}

		$db->sql_freeresult($result);
		
		if ( !$userdata['session_logged_in'] )
		{
			redirect(append_sid("login.$phpEx?redirect=linkdb.$phpEx&action=comment&post_comment=1&link_id=" . $file_id, true));
		}

		$html_on = ( $userdata['user_allowhtml'] ) ? TRUE : 0;
		$bbcode_on = ( $userdata['user_allowbbcode'] ) ? TRUE : 0;
		$smilies_on = ( $userdata['user_allowsmile'] ) ? TRUE : 0;

		if($delete == 'do')
		{
			$sql = "SELECT *
				FROM " . LINKS_TABLE . "
				WHERE link_id = $file_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
			}
			$file_info = $db->sql_fetchrow($result);

			//if ($file_info['user_id'] == $userdata['user_id'] || $userdata['user_level'] == ADMIN)
			if ($userdata['user_level'] == ADMIN)
			{
				$sql = 'DELETE FROM ' . LINK_COMMENTS_TABLE . "
					WHERE comments_id = $cid";
				if ( !($db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldnt delete comment', '', __LINE__, __FILE__, $sql);
				}

				$this->_linkdb();
				$message = $lang['Comment_deleted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("linkdb.$phpEx?action=comment&link_id=$file_id") . '">', '</a>');
				
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Sorry_auth_delete']);
			}
		}

		if(!$submit)
		{
			// Generate smilies listing for page output
			generate_smilies('inline', PAGE_POSTING);

			$html_status =  ( $userdata['user_allowhtml'] ) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
			$bbcode_status = ( $userdata['user_allowbbcode'] ) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
			$smilies_status = ( $userdata['user_allowsmile'] ) ? $lang['Smilies_are_ON'] : (($board_config['smilie_removal1']) ? $lang['Smilies_are_REMOVED'] : $lang['Smilies_are_OFF']);
			$hidden_form_fields = '<input type="hidden" name="action" value="comment"><input type="hidden" name="post_comment" value="1"><input type="hidden" name="link_id" value="' . $file_id . '"><input type="hidden" name="comment" value="post">';

			Multi_BBCode();

			//
			// Output the data to the template
			//
			$this->generate_category_nav($file_data['link_catid']);

			$template->assign_vars(array(
				'HTML_STATUS' => $html_status,
				'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'), 
				'SMILIES_STATUS' => $smilies_status,
				'LINKS_STATUS' => $links_status, 
				'IMAGES_STATUS' => $images_status, 
				'MESSAGE_LENGTH' => $linkdb_config['max_comment_chars'],

				'L_COMMENT_ADD' => $lang['Comment_add'],
				'L_COMMENT' => $lang['Message_body'],
				'L_COMMENT_TITLE' => $lang['Subject'],
				'L_OPTIONS' => $lang['Options'],
				'L_COMMENT_EXPLAIN' => sprintf($lang['Comment_explain'], $linkdb_config['max_comment_chars']),
				'L_PREVIEW' => $lang['Preview'],
				'L_DOWNLOAD'=> $lang['Download'],
				'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
			    'L_CHECK_MSG_LENGTH' => $lang['Check_message_length'], 
			    'L_MSG_LENGTH_1' => $lang['Msg_length_1'], 
			    'L_MSG_LENGTH_2' => $lang['Msg_length_2'], 
			    'L_MSG_LENGTH_3' => $lang['Msg_length_3'], 
			    'L_MSG_LENGTH_4' => $lang['Msg_length_4'], 
			    'L_MSG_LENGTH_5' => $lang['Msg_length_5'], 
			    'L_MSG_LENGTH_6' => $lang['Msg_length_6'], 

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

	'L_SPELLCHECK' => $lang['Spellcheck'],
	'L_COPY_TO_CLIPBOARD' => $lang['Copy_to_clipboard'],
	'L_COPY_TO_CLIPBOARD_EXPLAIN' => $lang['Copy_to_clipboard_explain'],
	'L_HIGHLIGHT_TEXT' => $lang['Highlight_text'],

				'U_INDEX' => append_sid('index.'.$phpEx),
				'U_DOWNLOAD_HOME' => append_sid('linkdb.'.$phpEx),
				'U_FILE_NAME' => append_sid('linkdb.'.$phpEx.'?action=link&link_id=' . $file_id),

				'S_POST_ACTION' => append_sid('linkdb.'.$phpEx),
				'S_HIDDEN_FORM_FIELDS' => $hidden_form_fields)
			);

while( list($key, $font) = each($lang['font']) )
{
	$template->assign_block_vars ('font_styles', array(
		'L_FONTNAME' => $font)
	);
}

			//
			// Show preview stuff if user clicked preview
			//
			if($preview)
			{
				$orig_word = $replacement_word = array();
				obtain_word_list($orig_word, $replacement_word);

				$comment_bbcode_uid = ( $bbcode_on ) ? make_bbcode_uid() : '';
				$comments_text = stripslashes(prepare_message(addslashes(unprepare_message($message)), $html_on, $bbcode_on, $smilies_on, $comment_bbcode_uid));

				$title = $subject;

				if (!$html_on)
				{
					//$comments_text = comment_suite($comments_text);
					$comments_text = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $comments_text);
				}

				if ($bbcode_on)
				{
					//$comments_text = comment_suite($comments_text);
					$comments_text = bbencode_second_pass($comments_text, $comment_bbcode_uid);// : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $comments_text);
				}
				
				if( !empty($orig_word) )
				{
					$title = ( !empty($title) ) ? preg_replace($orig_word, $replacement_word, $title) : '';
					$comments_text = ( !empty($comments_text) ) ? preg_replace($orig_word, $replacement_word, $comments_text) : '';
				}
				//
				// Now we run comment suite before checking for smilies 
				// so admins can add them in messages if they like
				// and so smilies are not counted as images in sigs.
				// this is done here again incase above conditions are
				// not met.
				//
				//$comments_text = comment_suite($comments_text);
				$comments_text = make_clickable($comments_text);
				//
				// Parse smilies
				//
				if ($smilies_on)
				{
					$comments_text = smilies_pass($comments_text);
				}

				$comments_text = str_replace("\n", '<br />', $comments_text);
				
				$template->assign_block_vars("PREVIEW", array());

				$template->assign_vars(array(
					'COMMENT' => stripslashes($_POST['message']),
					'SUBJECT' => stripslashes($_POST['subject']),        
					'PRE_COMMENT' => $comments_text)
				);
			}
		}

		if($submit)
		{
			$length = strlen($_POST['message']);
			$comments_text = str_replace('<br />', "\n", $_POST['message']);
			$comment_bbcode_uid = make_bbcode_uid();
			$comments_text = prepare_message($comments_text, $html_on, $bbcode_on, $smilies_on, $comment_bbcode_uid);
			$comments_text = bbencode_first_pass($comments_text, $comment_bbcode_uid);

			$poster_id = intval($userdata['user_id']);
			$title = stripslashes($_POST['subject']);
			$time = time();
			if($length > $linkdb_config['max_comment_chars'])
			{
				message_die(GENERAL_ERROR, 'Your comment is too long!<br/>The maximum length allowed in characters is ' . $linkdb_config['max_comment_chars'] . '');
			}

			$sql = 'INSERT INTO ' . LINK_COMMENTS_TABLE . "(link_id, comments_text, comments_title, comments_time, comment_bbcode_uid, poster_id) 
				VALUES($file_id, '" . str_replace("\'", "''", $comments_text) . "','" . str_replace("\'", "''", $title) . "', $time, '$comment_bbcode_uid', $poster_id)";
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldnt insert comments', '', __LINE__, __FILE__, $sql);
			}

			$message = $lang['Comment_posted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('linkdb.'.$phpEx.'?action=comment&link_id=' . $file_id) . '">', '</a>');
			
			message_die(GENERAL_MESSAGE, $message);	
		}
		
		$this->display($lang['Linkdb'], 'link_comment_posting.tpl');
	}
}

?>
<?php
/***************************************************************************
 *                             functions_comments.php
 *                            ------------------------
 *   begin                : Sunday, July 27, 2003
 *   copyright            : (C) 2003 Mohd
 *   email                : mohd@mohd.tk
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

function display_comments(&$file_data)
{
	global $template, $lang, $language, $board_config, $phpEx, $linkdb_config, $images, $theme;
	global $_REQUEST, $phpbb_root_path, $userdata, $db, $linkdb_functions;

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

	$template->assign_vars(array(
		'L_COMMENTS' => $lang['Comments']) 
	);

	//
	// Get link poster id 
	//
	$sql = "SELECT *
		FROM " . LINKS_TABLE . "
		WHERE link_id = ". $file_data['link_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
	}
	$file_info = $db->sql_fetchrow($result);

	$sql = 'SELECT c.*, u.*, u.user_avatar AS current_user_avatar, u.user_avatar_type AS current_user_avatar_type
		FROM ' . LINK_COMMENTS_TABLE . ' AS c 
			LEFT JOIN ' . USERS_TABLE . " AS u ON c.poster_id = u.user_id
		WHERE c.link_id = '" . $file_data['link_id'] . "'
		ORDER BY c.comments_time ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Couldnt select comments', '', __LINE__, __FILE__, $sql);
	}

	if (!($comment_number = $db->sql_numrows($result)))
	{
		$template->assign_block_vars('NO_COMMENTS', array('L_NO_COMMENTS' => $lang['No_comments']));
	}

	$ranksrow = array();
	$linkdb_functions->obtain_ranks($ranksrow);

	$i = 0;
	while ($comments_row = $db->sql_fetchrow($result)) 
	{     
		$time = create_date($board_config['default_dateformat'], $comments_row['comments_time'], $board_config['board_timezone']);

		$comments_text = $comments_row['comments_text'];

		if ( $comments_text != '' && $userdata['user_allowhtml'] )
		{
			$comments_text = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $comments_text);
		}

		if ( $comments_text != '' && $comments_row['comment_bbcode_uid'] != '' )
		{
			$comments_text = bbencode_second_pass($comments_text, $comments_row['comment_bbcode_uid']);
		}    

		$comments_text = make_clickable($comments_text);

		if( !empty($orig_word) )
		{
			if ( $comments_text != '' )
			{
				$comments_text = preg_replace($orig_word, $replacement_word, $comments_text);
			}
		}

		if ( $userdata['user_allowsmile'] && $comments_text != '' )
		{
			$comments_text = smilies_pass($comments_text);
		}

		$poster = ( $comments_row['user_id'] == ANONYMOUS ) ? $lang['Guest'] : username_level_color($comments_row['username'], $comments_row['user_level'], $comments_row['user_id']);

		$poster_posts = ( $comments_row['user_id'] != ANONYMOUS ) ? $lang['Posts'] . ': ' . $comments_row['user_posts'] : '';

		$poster_from = ( $comments_row['user_from'] && $comments_row['user_id'] != ANONYMOUS ) ? $lang['Location'] . ': ' . $comments_row['user_from'] : '';

		$poster_joined = ( $comments_row['user_id'] != ANONYMOUS ) ? $lang['Joined'] . ': ' . create_date($lang['DATE_FORMAT'], $comments_row['user_regdate'], $board_config['board_timezone']) : '';

		$poster_avatar = '';
		if ( $comments_row['user_avatar_type'] && $poster_id != ANONYMOUS && $comments_row['user_allowavatar'] && $userdata['user_showavatars'] && $userdata['avatar_sticky'] )
		{
			switch( $comments_row['user_avatar_type'] )
			{
				case USER_AVATAR_UPLOAD:
					$poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $comments_row['user_avatar'] . '" alt="" title="" />' : '';
					break;
				case USER_AVATAR_REMOTE:
					$poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $comments_row['user_avatar'] . '" alt="" title="" />' : '';
					break;
				case USER_AVATAR_GALLERY:
					$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $comments_row['user_avatar'] . '" alt="" title="" />' : '';
					break;
			}
		}
 	 	else if ( ($comments_row['current_user_avatar_type']) && $poster_id != ANONYMOUS && $comments_row['user_allowavatar'] && $userdata['user_showavatars'] )
		{
			switch( $comments_row['current_user_avatar_type'] )
			{
				case USER_AVATAR_UPLOAD:
					$poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $comments_row['current_user_avatar'] . '" alt="" title="" />' : '';
					break;
				case USER_AVATAR_REMOTE:
					$poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $postrow[$i]['current_user_avatar'] . '" alt="" title="" />' : '';
					break;
				case USER_AVATAR_GALLERY:
					$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $comments_row['current_user_avatar'] . '" alt="" title="" />' : '';
					break;
			}
		}
 
		if ( (!$poster_avatar) && ($board_config['default_avatar_set'] != 3) )
		{
			if ( ($board_config['default_avatar_set'] == 0) && ($poster_id == -1) && ($board_config['default_avatar_guests_url']) )
			{
				$poster_avatar = '<img src="' . $board_config['default_avatar_guests_url'] . '" alt="" title="" />';
			}
			else if ( ($board_config['default_avatar_set'] == 1) && ($poster_id != -1) && ($board_config['default_avatar_users_url']) )
			{
				$poster_avatar = '<img src="' . $board_config['default_avatar_users_url'] . '" alt="" title="" />';
			}
			else if ($board_config['default_avatar_set'] == 2)
			{
				if ( ($poster_id == -1) && ($board_config['default_avatar_guests_url']) )
				{
					$poster_avatar = '<img src="' . $board_config['default_avatar_guests_url'] . '" alt="" title="" />';
				}
				else if ( ($poster_id != -1) && ($board_config['default_avatar_users_url']) )
				{
					$poster_avatar = '<img src="' . $board_config['default_avatar_users_url'] . '" alt="" title="" />';
				}
			}
		}
	
		//
		// Generate ranks, set them to empty string initially.
		//
		$poster_rank = $rank_image = '';
		if ( $comments_row['user_id'] == ANONYMOUS )
		{
		}
		else if ( $comments_row['user_rank'] )
		{
			for($j = 0; $j < sizeof($ranksrow); $j++)
			{
				if ( $comments_row['user_rank'] == $ranksrow[$j]['rank_id'] && $ranksrow[$j]['rank_special'] )
				{
					$poster_rank = $ranksrow[$j]['rank_title'];
					$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[$j]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
				}
			}
		}
		else
		{
			for($j = 0; $j < sizeof($ranksrow); $j++)
			{
				if ( $comments_row['user_posts'] >= $ranksrow[$j]['rank_min'] && !$ranksrow[$j]['rank_special'] )
				{
					$poster_rank = $ranksrow[$j]['rank_title'];
					$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[$j]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
				}
			}
		}

		$comments_text = str_replace("\n", "\n<br />\n", $comments_text);
		$comments_text = word_wrap_pass($comments_text);

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('text', array(
			'ROW_CLASS' => $row_class,
			'POSTER' => $poster,
			'U_COMMENT_DELETE' => append_sid("linkdb.".$phpEx."?action=comment&post_comment=1&cid={$comments_row['comments_id']}&delete=do&link_id={$file_data['link_id']}"),
			'DELETE_IMG' => $images['icon_delpost'],
			'ICON_MINIPOST_IMG' => $phpbb_root_path . $images['icon_minipost'],
			'ICON_SPACER' => $phpbb_root_path . 'images/spacer.gif',
			'POSTER_RANK' => $poster_rank,
			'RANK_IMAGE' => $rank_image,
			'POSTER_JOINED' => $poster_joined,
			'POSTER_POSTS' => $poster_posts,
			'POSTER_FROM' => $poster_from,
			'POSTER_AVATAR' => $poster_avatar,
			'TITLE' => $comments_row['comments_title'],
			'TIME' => $time,
			'TEXT' => $comments_text) 
		);
		$i++;

		if ($userdata['user_level'] == ADMIN)
		{
			$template->assign_block_vars('text.AUTH_COMMENT_DELETE', array());
		}
	}

	$db->sql_freeresult($result);

	$template->assign_vars(array(
		'REPLY_IMG' => $images['pa_comment_post'],
		'L_COMMENT_DO' => $lang['Comment_do'],
		'L_COMMENTS' => $lang['Comments'],
		'L_AUTHOR' => $lang['Author'],
		'L_POSTED' => $lang['Posted'],
		'L_COMMENT_SUBJECT' => $lang['Comment_subject'],
		'L_COMMENT_ADD' => $lang['Comment_add'],
		'L_COMMENT_DELETE' => $lang['Comment_delete'],
		'L_COMMENTS_NAME' => $lang['Name'],
		'L_BACK_TO_TOP' => $lang['Back_to_top'],
		'U_COMMENT_DO' => append_sid('linkdb.'.$phpEx.'?action=comment&post_comment=1&link_id='.$file_data['link_id']))
	);
}

?>
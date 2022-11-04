<?php
/***************************************************************************
 *								slideshow.php
 *                            -------------------
 *   begin                : Thu, Feb 13, 2003
 *   copyright            : (C) 2003 Tom Davenport
 *   email                : tomdav@yahoo.com
 *
 *   $Id: slideshow.php,v 1.3 2003/08/30 14:10:10 acydburn Exp $
 *
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

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}

while (!$thumbnail)
{
	//
	// Determine if they are viewing the slideshow, album, or random picture
	//
	$order = '';
	$ualbum = $malbum = 0;
	$show_i = -1;
	
	if ($download_id != -1)
	{
		$order = $HTTP_GET_VARS['order'];
	}
	else if ($HTTP_GET_VARS['u'])
	{
		$ualbum = $HTTP_GET_VARS['u'];
	}
	else if ($HTTP_GET_VARS['m'])
	{
		$malbum = $HTTP_GET_VARS['m'];
	}
	else
	{
		$show_i = -999; // none of the above so display random picture
	}

	//
	// Ignore attachments posted in forums where user doesn't have view, read, and download status
	//
	$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata);
	$ignore = '';
	@reset($is_auth_ary);
	while( list($key, $value) = each($is_auth_ary) )
	{
		if ( !$value['auth_view'] || !$value['auth_read'] || !$value['auth_download'] )
		{
			$ignore .= ( ( $ignore != '' ) ? ', ' : '' ) . $key;
		}
	}

	if ( $ignore != '' )
	{
		$ignore = "AND (t.forum_id NOT IN ($ignore)) ";
	}

	$order = ($order == 'user_id' || $ualbum) ? 'u.user_id,' : '';

	$sql = "SELECT d.width, d.height, d.border, d.attach_id, d.physical_filename, d.real_filename, d.download_count, d.comment, d.filesize, d.thumbnail, p.post_id, p.post_username, p.post_time, u.username, u.user_id, t.topic_title, t.forum_id
		FROM " . ATTACHMENTS_TABLE . " a, " . ATTACHMENTS_DESC_TABLE . " d, " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . TOPICS_TABLE . " t
		WHERE (d.attach_id = a.attach_id) 
			AND (a.post_id = p.post_id) 
			AND (p.poster_id = u.user_id) 
			AND (p.topic_id = t.topic_id) 
			AND (a.privmsgs_id = 0) 
			AND (d.width > 0) $ignore
		ORDER BY $order p.post_id, d.filetime " . ( ($attach_config['display_order'] == '0' ) ? 'DESC' : 'ASC' );

	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query album or slideshow information', '', __LINE__, __FILE__, $sql);
	}

	$order = ($order != '') ? '&amp;order=user_id' : '';

	$pics = $db->sql_fetchrowset($result);
	$num_pics = $db->sql_numrows($result);
	$db->sql_freeresult($result);

	$next_album = $num_pics;
	for ($i = 0; $i < $num_pics; $i++)
	{
		if (!$pics[$i]['comment'])
		{
			$pics[$i]['comment'] = $pics[$i]['real_filename'];
		}

		if ($download_id == $pics[$i]['attach_id'])
		{
			$show_i = $i;
		}

		$key = $pics[$i]['user_id'] + 1;
		if (!$user[$key])
		{
			$text = ($pics[$i]['user_id'] == -1) ? $lang['Guest'] : $pics[$i]['username'];
			$user[$key] = strtolower($text) . $text;
			if ($ualbum)
			{
				if ($pics[$i]['user_id'] == $ualbum)
				{
					$show_i = $i;
				}
				else if ($show_i > -1 && $next_album == $num_pics)
				{
					$next_album = $i;
				}
			}
		}

		$key = create_date('ny', $pics[$i]['post_time'], $board_config['board_timezone']);
		if (!$month[$key])
		{
			$month[$key] = create_date('ymM Y', $pics[$i]['post_time'], $board_config['board_timezone']);
			if ($malbum)
			{
				if ($key == $malbum)
				{
					$show_i = $i;
				}
				else if ($show_i > -1 && $next_album == $num_pics)
				{
					$next_album = $i;
				}
			}
		}
	}

	if ($num_pics == 0 || $show_i == -1) // no pics, not in slideshow or not a valid album so get outta here
	{
		break;
	}

	srand((double)microtime()*1000000);

	if ($show_i == -999) // display random picture
	{
		$show_i = rand(1, $num_pics) - 1;
	}

	$this = $pics[$show_i];

	$rand_id = $pics[rand(1, $num_pics) - 1]['attach_id'];

	$uoptions = '';
	$u = array( ($malbum) ? -999 : $this['user_id'] );
	asort ($user);
	@reset ($user);
	while (list($key, $text) = each($user))
	{
		$key = $key - 1;
		$u[] = $key;
		$text = substr($text, strlen($text) / 2);
		if ($key == $u[0])
		{
			if ($ualbum)
			{
				$page_title = $text . ' ' . $lang['Pics'];
			}
			else
			{
				$text = $this['username'];
			}
			$key .= '" selected="selected'; $uselected = count($u) - 1;
		}
		$uoptions .= '<option value="' . $key . '">' . $text . '</option>';
	}
	$u[0] = $u[count($u) - 1];
	$u[] = $u[1];
	if ($malbum)
	{
		$uoptions = '<option value="0" selected="selected">' . $lang['By_username'] . '</option>' . $uoptions;
	}

	$moptions = '';
	$m = array( ($ualbum) ? -999 : create_date('ny', $this['post_time'], $board_config['board_timezone']) );
	asort($month);
	@reset($month);
	while (list($key, $text) = each($month))
	{
		$m[] = $key;
		$text = substr($text, 4);
		if ($key == $m[0])
		{
			$page_title = ($malbum) ? $text . ' ' . $lang['Pics'] : $this['comment'];
			$key .= '" selected="selected';
			$mselected = count($m) - 1;
		}
		$moptions .= '<option value="' . $key . '">' . $text . '</option>';
	}
	$m[0] = $m[count($m) - 1];
	$m[] = $m[1];
	if ($ualbum)
	{
		$moptions .= '<option value="0" selected="selected">' . $lang['By_month'] . '</option>';
	}

	$hidden_sid = (append_sid('')) ? '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />' : '';

	if ($ualbum || $malbum) // display album
	{
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'album_body' => 'album.tpl')
		);

		$template->assign_vars(array(
			'U_RAND_DOWNLOAD' => append_sid($phpbb_root_path . 'download.'.$phpEx . '?id=' . $rand_id),
			'L_PICS' => $lang['Pics'],
			'L_OR' => $lang['Or'],
			'U_DOWNLOAD' => append_sid($phpbb_root_path . 'download.' . $phpEx),
			'UOPTIONS' => $uoptions,
			'MOPTIONS' => $moptions,
			'HIDDEN_SID' => $hidden_sid,
			'MINI_SEARCH_IMG' => $images['icon_mini_search'],

			'U_PREV' => append_sid($phpbb_root_path . 'download.'.$phpEx . '?' . ( ($ualbum) ? 'u=' . $u[$uselected - 1] : 'm=' . $m[$mselected - 1] )),
			'U_NEXT' => append_sid($phpbb_root_path . 'download.'.$phpEx . '?' . ( ($ualbum) ? 'u=' . $u[$uselected + 1] : 'm=' . $m[$mselected + 1] )),
			'L_PREV' => $lang['Previous'],
			'L_NEXT' => ( ($uselected) ? $lang['Next_user'] : $lang['Next_month'] ),
			'U_POSTER_PROFILE' => append_sid($phpbb_root_path . 'profile.'.$phpEx . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $this['user_id']),
			'L_VIEW_PROFILE' => $lang['View_profile'],
			'L_KILOBYTE' => $lang['KB'])
		);

		if ($ualbum && $this['user_id'] != -1)
		{
			$template->assign_block_vars('switch_poster_profile', array());
		}

		for ($i = $show_i; $i < $next_album; $i++)
		{
			$this = $pics[$i];
			$this['thumbnail'] = $upload_dir . '/' . ( ($this['thumbnail']) ? THUMB_DIR . '/t_' : '' ) . $this['physical_filename'];
			$size = get_img_size_format($this['width'], $this['height']);
			$this['thumbnail'] = $this['thumbnail'] . '" width="' . $size[0] . '" height="' . $size[1];

			$template->assign_block_vars('thumb', array(
				'POSTER_NAME' => $this['username'],
				'TOPIC_TITLE' => str_replace('"', "&quot;", strip_tags($this['topic_title'])),
				'COMMENT' => $this['comment'],
				'FILESIZE' => round($this['filesize'] / 1024, 0),
				'U_DOWNLOAD' => append_sid($phpbb_root_path . 'download.' . $phpEx . '?id=' . $this['attach_id'] . $order),
				'U_THUMB' => $this['thumbnail'])
			);
		}

		$template->pparse('album_body');

		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	}
	else	// display picture
	{
		$prev = ($show_i == 0) ? $pics[$num_pics - 1] : $pics[$show_i - 1];
		$next = ($show_i + 1 == $num_pics) ? $pics[0] : $pics[$show_i + 1];
		while (($next['attach_id'] == $rand_id || $prev['attach_id'] == $rand_id || $this['attach_id'] == $rand_id) && $num_pics > 3)
		{
			$rand_id = $pics[rand(1, $num_pics) - 1]['attach_id'];
		}

		$prev_id_direction = $prev['attach_id'] . '-';
		$next_id_direction = $next['attach_id'] . '_';
		$rand_id_direction = $rand_id . '*';

		$slideshow_value = '';
		if ( $x = substr($HTTP_GET_VARS['id'], strlen($download_id)) )
		{
			$secs = intval(substr($x, 1));
			if ($secs <= 0)
			{
				$x = '';
			}
			else
			{
				$text = substr($x, 0, 1); // first character indicates direction
				$text = (strpos($prev_id_direction, $text)) ? $prev_id_direction : ( (strpos($rand_id_direction, $text)) ? $rand_id_direction : $next_id_direction );
				$x = substr(stristr($x, 'x'), 0, 1);
				$slideshow_value = substr($text, -1) . $secs . $x;
			}
			if ($x == 'X') // uppercase X => browser not javascript 1.2 enabled so use meta refresh
			{
				$template->assign_vars(array("META" => '<meta http-equiv="refresh" content="' . $secs . ';url=' . append_sid("download.$phpEx?id=" . $text . $secs . $x . $order) . '">'));
				$slideshow_value = 'meta';
			}
		}

		//
		// update the download count or border
		//
		$auth_edit = ( ($userdata['user_id'] == $this['user_id'] && $is_auth_ary[$this['forum_id']]['auth_edit'] ) || $is_auth_ary[$this['forum_id']]['auth_mod'] );
		if ($auth_edit && isset($HTTP_GET_VARS['b']))
		{
			$this['border'] = intval($HTTP_GET_VARS['b']);
			$sql = "UPDATE " . ATTACHMENTS_DESC_TABLE . "
				SET border = " . $this['border'] . "
				WHERE attach_id = " . $this['attach_id'];
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t update attachment border', '', __LINE__, __FILE__, $sql);
			}
		}
		else if (!strpos($HTTP_GET_VARS['id'], '_X')) // don't update count when stopping meta slideshow or modifying border
		{
			$this['download_count'] += 1;
			$sql = "UPDATE " . ATTACHMENTS_DESC_TABLE . "
				SET download_count = download_count + 1
				WHERE attach_id = " . $this['attach_id'];
			if ( !($db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Couldn\'t update attachment download count', '', __LINE__, __FILE__, $sql);
			}
		}

		//
		// display option to edit post or modify border if authorized
		//
		if ($auth_edit)
		{
			$auth_edit = ' | <a class="slideshow" href="' . append_sid("download.$phpEx?id=" . $this['attach_id'] . $order) . '&amp;b=' . abs($this['border'] - 1) . '">' . ( ($this['border'] == 1) ? $lang['Remove_border'] : $lang['Add_border'] ) . '</a>';
			$auth_edit .= ' | <a class="slideshow" href="' . append_sid("posting.$phpEx?mode=editpost&amp;" . POST_POST_URL . "=" . $this['post_id']) . '">' . $lang['Edit_post'] . '</a>';
		}

		//
		// set thumbnails and image border
		//
		$prev['thumbnail'] = $upload_dir . '/' . ( ($prev['thumbnail']) ? THUMB_DIR . '/t_' : '' ) . $prev['physical_filename'];
		$size = get_img_size_format($prev['width'], $prev['height']);
		$prev['thumbnail'] = $prev['thumbnail'] . '" width="' . $size[0] . '" height="' . $size[1];

		$next['thumbnail'] = $upload_dir . '/' . ( ($next['thumbnail']) ? THUMB_DIR . '/t_' : '' ) . $next['physical_filename'];
		$size = get_img_size_format($next['width'], $next['height']);
		$next['thumbnail'] = $next['thumbnail'] . '" width="' . $size[0] . '" height="' . $size[1];

		$this['physical_filename'] = $upload_dir . '/' . $this['physical_filename'];
		$this['physical_filename'] .= '" width="' . $this['width'] . '" height="' . $this['height'] . '" border="' . $this['border'];

		//
		// get post_text for post preview
		//
		$sql = "SELECT post_text, bbcode_uid 
			FROM " . POSTS_TEXT_TABLE . " 
			WHERE post_id = " . $this['post_id'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query post information for slideshow', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);

		//
		// strip any html tags
		//
		$post_text = strip_tags($row['post_text']);

		//
		// convert url tag
		//
		while ($url = stristr($post_text, '[url'))
		{
			$text = stristr($url, '[/url]');
			$i = strpos($url, ']');
			if (!$text || $i > strlen($url) - strlen($text))
			{
				break;
			}
			$post_text = substr($post_text, 0, -strlen($url));
			$url = substr($url, 0, -strlen($text));
			$text = substr($url, $i + 1) . '</a>' . substr($text, 6);
			$url = ($i < 5) ? substr($url, $i + 1) : substr($url, 5, $i - 5);
			$url = ( (strpos(substr($url, 0, 9), '://') < 3) ? 'http://' : '' ) . $url;
			$post_text .= '<a class="slideshow" href="' . $url . '" target="_blank">' . $text;
		}

		//
		// remove other bbcode tags
		//
		$post_text = preg_replace('/\[url\]|\[\/url\]/si', '', $post_text);
		$post_text = preg_replace('/\[.*?:' . $row['bbcode_uid'] . ':?.*?\]/si', '', $post_text);

		//
		// limit preview to 2K (extend if necessary to avoid chopping link)
		//
		$i = 1024 * 2;
		if (strlen($post_text) > $i)
		{
			$text = substr($post_text, $i);
			if (strpos($text, '>') > strpos($text, '</a>'))
			{
				$i += strpos($text, '</a>') + 4;
			}
			$post_text = substr($post_text, 0, $i) . '... <a href="' . append_sid("viewtopic.$phpEx?" . POST_POST_URL . "=" . $this['post_id'] . "#" . $this['post_id']) . '">' . $lang['More'] . '</a>';
		}
		$post_text = str_replace("\n", "<br />", $post_text);

		$gen_simple_header = -1; // -1 to use slideshow_header.tpl
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'slideshow_body_and_footer' => 'slideshow.tpl')
		);

		$template->assign_vars(array(
			'COMMENT' => $this['comment'],
			'L_TOPIC' => $lang['Topic'],
			'U_POST' => append_sid("viewtopic.$phpEx?" . POST_POST_URL . "=" . $this['post_id'] . "#" . $this['post_id']),
			'TOPIC_TITLE' => str_replace('"', "&quot;", strip_tags($this['topic_title'])),
			'L_DOWNLOAD_COUNT' => sprintf($lang['Download_times'], $this['download_count']),
			'FILESIZE' => round($this['filesize'] / 1024, 0),
			'L_KILOBYTE' => $lang['KB'],
			'U_DOWNLOAD' => append_sid("download.$phpEx"),
			'UOPTIONS' => $uoptions,
			'MOPTIONS' => $moptions,
			'HIDDEN_SID' => $hidden_sid,
			'MINI_SEARCH_IMG' => $images['icon_mini_search'],

			'PREV_POSTER_NAME' => $prev['username'],
			'PREV_TOPIC_TITLE' => str_replace('"', "&quot;", strip_tags($prev['topic_title'])),
			'PREV_COMMENT' => $prev['comment'],
			'PREV_FILESIZE' => round($prev['filesize'] / 1024, 0),
			'PREV_ID_DIRECTION' => $prev_id_direction,
			'U_PREV_DOWNLOAD' => append_sid("download.$phpEx?id=" . ( ($x == 'X') ? $prev_id_direction . $secs . $x: $prev['attach_id'] ) . $order),
			'U_PREV_THUMB' => $prev['thumbnail'],

			'SLIDESHOW_STOP_OPTION' => ( ($x) ? '<option value="' . $this['attach_id'] . '_X" selected="selected">' . $lang['Stop'] . '</option>' : '' ),
			'NO_SLIDESHOW_SELECTED' => ( ($x) ? '' : 'selected="selected"' ),
			'SLIDESHOW_VALUE' => $slideshow_value,
			'HIDDEN_ORDER' => ( ($order != '') ? '<input type="hidden" name="order" value="' . substr($order, strpos($order, '=') + 1) . '" />' : '' ),
			'L_GO_X' => ( ($x) ? '&nbsp;X&nbsp;' : $lang['Go'] ),

			'NEXT_POSTER_NAME' => $next['username'],
			'NEXT_TOPIC_TITLE' => str_replace('"', "&quot;", strip_tags($next['topic_title'])),
			'NEXT_COMMENT' => $next['comment'],
			'NEXT_FILESIZE' => round($next['filesize'] / 1024, 0),
			'NEXT_ID_DIRECTION' => $next_id_direction,
			'U_NEXT_DOWNLOAD' => append_sid("download.$phpEx?id=" . ( ($x == 'X') ? $next_id_direction . $secs . $x: $next['attach_id'] ) . $order),
			'U_NEXT_THUMB' => $next['thumbnail'],

			'L_FORUM_INDEX' => sprintf($lang['Forum_Index'], ''), // no sitename since it needs to be short
			'U_ACTIVE_TOPICS' => append_sid("search.$phpEx?search_author=*&amp;show_results=topics&amp;search_time=1"),
			'L_ACTIVE_TOPICS' => $lang['Active_topics'],
			'L_CURR_OF_TOTAL' => sprintf($lang['Number_of_total'], $show_i + 1, $num_pics),
			'RAND_ID_DIRECTION' => $rand_id_direction,
			'U_RAND_DOWNLOAD' => append_sid("download.$phpEx?id=" . ( ($x == 'X') ? $rand_id_direction . $secs . $x: $rand_id ) . $order),
			'L_RANDOM_PIC' => $lang['Random_pic'],

			'DOWNLOAD_NAME' => $this['real_filename'],
			'IMAGE_NO_BACKGROUND' => (strtolower(substr($this['real_filename'], -3)) == 'gif'),
			'U_IMAGE' => $this['physical_filename'],
			'MESSAGE' => $post_text,
			'L_POSTED_BY' => $lang['Posted_by'],
			'U_POSTER_PROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $this['user_id']),
			'L_EDIT_POST_SET_BORDER' => $auth_edit,
			'L_VIEW_POST' => $lang['View_post'],
			'POSTER_NAME' => ( ($this['user_id'] == -1) ? '</a>' : '' ) . $this['username'],
			'L_IN' => $lang['In'])
		);

		$template->pparse('slideshow_body_and_footer');

		//
		// use this instead of page_tail.php
		//
		$db->sql_close();
		if ( $do_gzip_compress )
		{
			//
			// Borrowed from php.net!
			//
			$gzip_contents = ob_get_contents();
			ob_end_clean();

			$gzip_size = strlen($gzip_contents);
			$gzip_crc = crc32($gzip_contents);

			$gzip_contents = gzcompress($gzip_contents, 9);
			$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

			echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
			echo $gzip_contents;
			echo pack('V', $gzip_crc);
			echo pack('V', $gzip_size);
		}
		exit;
	}
}

?>
<?php
/***************************************************************************
 *                            admin_album_unapproved.php
 *                             -------------------
 *   begin                : Monday, February 03, 2003
 *   copyright            : (C) 2003 Smartor
 *   email                : smartor_xp@hotmail.com
 *
 *   $Id: admin_album_unapproved.php,v 1.0.3 2003/03/05, 20:19:28 ngoctu Exp $
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

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Photo_Album']['UnApproved Pics'] = $filename;
	return;
}
//error_reporting  (E_ERROR | E_WARNING | E_PARSE | E_ALL); // This will NOT report uninitialized variables

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
$album_root_path = $phpbb_root_path . 'mods/album/';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_main_album.' . $phpEx);
require($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_album.' . $phpEx);

//
// Get general album information
//
include($album_root_path . 'album_common.'.$phpEx);

// start pic approval or delete
if( isset($HTTP_GET_VARS['pic_id']) )
{
	$pic_id = intval($HTTP_GET_VARS['pic_id']);
}
else
{
	$pic_id = FALSE;
}

if( $pic_id != FALSE && isset($HTTP_GET_VARS['pic_approval']))
{
	//-----------------------------
	// APPROVAL
	//-----------------------------

	if( is_array($pic_id) )
	{
		$pic_id_sql = implode(',', $pic_id);
	}
	else
	{
		$pic_id_sql = $pic_id;
	}


	// update the DB
	$sql = "UPDATE ". ALBUM_TABLE ."
		SET pic_approval = 1
		WHERE pic_id IN ($pic_id_sql)";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not update album information', '', __LINE__, __FILE__, $sql);
	}
	
	$message = $lang['Pics_approved_successfully'] . "<br /><br />" . sprintf($lang['Click_return_unapproved_pics'], "<a href=\"" . append_sid("admin_album_unapproved.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}
// end pic approval

// begin pic deletion
if( $pic_id != FALSE && isset($HTTP_GET_VARS['pic_delete']))
{
	//-----------------------------
	// DELETE
	//-----------------------------
	if( is_array($pic_id) )
	{
		$pic_id_sql = implode(',', $pic_id);
	}
	else
	{
		$pic_id_sql = $pic_id;
	}

	// Delete all comments
	$sql = "DELETE FROM ". ALBUM_COMMENT_TABLE ."
		WHERE comment_pic_id IN ($pic_id_sql)";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete related comments', '', __LINE__, __FILE__, $sql);
	}

	// Delete all ratings
	$sql = "DELETE FROM ". ALBUM_RATE_TABLE ."
		WHERE rate_pic_id IN ($pic_id_sql)";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete related ratings', '', __LINE__, __FILE__, $sql);
	}

	// Delete Physical Files
	// first we need filenames
	$sql = "SELECT pic_filename, pic_thumbnail
		FROM ". ALBUM_TABLE ."
		WHERE pic_id IN ($pic_id_sql)";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain filenames', '', __LINE__, __FILE__, $sql);
	}
	
	$filerow = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$filerow[] = $row;
	}
	
	for ($i = 0; $i < sizeof($filerow); $i++)
	{
		if( ($filerow[$i]['pic_thumbnail'] != '') and (@file_exists(ALBUM_CACHE_PATH . $filerow[$i]['pic_thumbnail'])) )
		{
			@unlink(ALBUM_CACHE_PATH . $filerow[$i]['pic_thumbnail']);
		}
		@unlink(ALBUM_UPLOAD_PATH . $filerow[$i]['pic_filename']);
	}

	// Delete DB entry
	$sql = "DELETE FROM ". ALBUM_TABLE ."
		WHERE pic_id IN ($pic_id_sql)";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete DB entry', '', __LINE__, __FILE__, $sql);
	}

	$message = $lang['Pics_deleted_successfully'] .'<br /><br />Click <a href="' . append_sid("admin_album_unapproved.$phpEx") . '">here</a> to return to the unapproved pics';

	message_die(GENERAL_MESSAGE, $message);
}

$template->set_filenames(array(
	'body' => 'admin/album_unapproved_body.tpl')
);

$sort_method = $album_config['sort_method'];
$sort_order = $album_config['sort_order'];

$template->assign_vars(array(
	'S_COL_WIDTH' => '100',
	'L_VIEW' => $lang['View'],
	'L_POSTER' => $lang['Poster'],
	'L_POSTED' => $lang['Posted'],
	'L_PIC_TITLE' => $lang['Pic_Title'],
	'L_USERNAME' => $lang['Sort_Username'],
	'L_TITLE' => $lang['Album'] . ' ' . $lang['album_unapproved_title'],
	'L_TITLE_EXPLAIN' => $lang['album_unapproved_title_explain'],
	'L_NO_PICS_FOUND' => $lang['no_unapproved_pics_found'],
	'S_ALBUM_ACTION' => append_sid('admin_album_unapproved.'.$phpEx))
);

$sql = "SELECT DISTINCT(pic_cat_id)
	FROM ". ALBUM_TABLE ."
	WHERE pic_approval = 0
	ORDER BY pic_cat_id ASC";
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not query Album Categories information', '', __LINE__, __FILE__, $sql);
}

$catidrow = array();
while ($row = $db->sql_fetchrow($result))
{
	$catidrow[] = $row;
}

for( $i = 0; $i < count($catidrow); $i++ )
{
	$cats .= ($cats) ? ', '.$catidrow[$i]['pic_cat_id'] : $catidrow[$i]['pic_cat_id'];
}

if (!$cats)
{
	$template->assign_block_vars('no_pics', array()); 
}
else
{
	$sql = "SELECT *
		FROM ". ALBUM_CAT_TABLE ."
		WHERE cat_id IN ($cats)
		ORDER BY cat_order ASC";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query Album Categories information', '', __LINE__, __FILE__, $sql);
	}

	$catrow = array();
	while ($row = $db->sql_fetchrow($result))
	{
		$catrow[] = $row;
	}

	for( $i = 0; $i < sizeof($catrow); $i++ )
	{
		$template->assign_block_vars('catrow', array(
			'COLOR' => ($i % 2) ? $theme['td_class1'] : $theme['td_class2'],
			'TITLE' => $catrow[$i]['cat_title'],
			'DESC' => $catrow[$i]['cat_desc'])
		);

		$cat_id = $catrow[$i]['cat_id'];
		$sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_user_ip, p.pic_username, p.pic_time, p.pic_cat_id, p.pic_view_count, p.pic_lock, p.pic_approval, u.user_id, u.username, u.user_level, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments, MAX(c.comment_id) as new_comment
			FROM ". ALBUM_TABLE ." AS p
				LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
			WHERE p.pic_cat_id = '$cat_id'
				AND p.pic_approval = 0
			GROUP BY p.pic_id
			ORDER BY p.pic_id ASC";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query pics information', '', __LINE__, __FILE__, $sql);
		}

		// emptying the array for next go
		$picrow = array();
		//print_r(array_change_key_case($picrow, CASE_UPPER));
		//$count = 0;
		while( $row = $db->sql_fetchrow($result) )
		{
			$picrow[] = $row; 
		}

		$tds = 0;
		for ($j = 0; $j < sizeof($picrow); $j++)
		{
			if(!$picrow[$j]['rating'])
			{
				$picrow[$j]['rating'] = $lang['Not_rated'];
			}
			else
			{
				$picrow[$j]['rating'] = round($picrow[$j]['rating'], 2);
			}

			$approval_mode = ($picrow[$j]['pic_approval'] == 0) ? 'approval' : 'unapproval';
			$approval_link = '<a href="'. append_sid("admin_album_unapproved.$phpEx?pic_approval=true&amp;pic_id=". $picrow[$j]['pic_id']) .'">';
			$approval_link .= ($picrow[$j]['pic_approval'] == 0) ? '<b>'. $lang['Approve'] .'</b>' : $lang['Unapprove'];
			$approval_link .= '</a>';

			if( ($picrow[$j]['user_id'] == ALBUM_GUEST) or ($picrow[$j]['username'] == '') )
			{
				$pic_poster = ($picrow[$j]['pic_username'] == '') ? $lang['Guest'] : $picrow[$j]['pic_username'];
			}
			else
			{
				$pic_poster = '<a href="'. append_sid($phpbb_root_path . 'profile.$phpEx?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $picrow[$j]['user_id']) . '" target="_blank">' . username_level_color($picrow[$j]['username'], $picrow[$j]['user_level'], $picrow[$j]['user_id']) . '</a>';
			}

			$tds++;
			if ($tds == 6)
			{
				$nextrow = '</tr><tr>';
				$tds = 0;
			}
			else
			{
				$nextrow = '';
			}

			$template->assign_block_vars('catrow.picrow', array(
				'NEXTROW' => $nextrow,
				'U_PIC' => append_sid($phpbb_root_path . 'album_pic.'.$phpEx.'?pic_id=' . $picrow[$j]['pic_id']),
				'THUMBNAIL' => append_sid($phpbb_root_path . 'album_thumbnail.'.$phpEx.'?pic_id=' . $picrow[$j]['pic_id']),
				'DESC' => $picrow[$j]['pic_desc'],
				'APPROVAL' => $approval_link,
				'TITLE' => $picrow[$j]['pic_title'],
				'POSTER' => $pic_poster,
				'TIME' => create_date($board_config['default_dateformat'], $picrow[$j]['pic_time'], $board_config['board_timezone']),
				'VIEW' => $picrow[$j]['pic_view_count'],
				'RATING' => ($album_config['rate'] == 1) ? ( '<b><a href="'. append_sid($phpbb_root_path . 'album_rate.'.$phpEx.'?pic_id=' . $picrow[$j]['pic_id']) . '" target="_blank">' . $lang['Rating'] . '</a>:</b> ' . $picrow[$j]['rating'] . '<br />') : '',
				'COMMENTS' => ($album_config['comment'] == 1) ? ( '<b><a href="' . append_sid($phpbb_root_path . 'album_comment.'.$phpEx.'?pic_id=' . $picrow[$j]['pic_id']) . '" target="_blank">' . $lang['Comments'] . '</a>:</b> ' . $picrow[$j]['comments'] . '<br />') : '',
				'DELETE' => '<a href="'. append_sid("admin_album_unapproved.$phpEx?pic_delete=true&amp;pic_id=". $picrow[$j]['pic_id']) . '"><img src="' . $phpbb_root_path . $images['topic_mod_delete'] . '" alt="' . $lang['Delete_pic'] . '" title="' . $lang['Delete_pic'] . '" /></a>',
				'EDIT' => '<a href="' . append_sid($phpbb_root_path . 'album_edit.'.$phpEx.'?pic_id=' . $picrow[$j]['pic_id']) . '" target="_blank"><img src="' . $phpbb_root_path . $images['icon_edit'] . '" alt="' . $lang['Edit_pic'] . '" title="' . $lang['Edit_pic'] . '" /></a>',
				'IP' => '<b>' . $lang['IP_Address'] . ':</b> <a href="http://www.nic.com/cgi-bin/whois.cgi?query=' . decode_ip($picrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($picrow[$j]['pic_user_ip']) .'</a><br />')
			);
		}
	}
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
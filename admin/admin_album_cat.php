<?php
/** 
*
* @package admin
* @version $Id: admin_album_cat.php,v 1.0.3 2003/03/05, 20:19:28 ngoctu Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Album']['Categories'] = $filename;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
$album_root_path = $phpbb_root_path . 'mods/album/';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($album_root_path . 'album_common.'.$phpEx);


// --------------------------
// This function will sort the order of all categories
//
function reorder_cat()
{
	global $db;

	$sql = "SELECT cat_id, cat_order
		FROM ". ALBUM_CAT_TABLE ."
		WHERE cat_id <> 0
		ORDER BY cat_order ASC";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not get list of Categories', '', __LINE__, __FILE__, $sql);
	}

	$i = 10;

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE ". ALBUM_CAT_TABLE ."
			SET cat_order = $i
			WHERE cat_id = ". $row['cat_id'];
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update order fields', '', __LINE__, __FILE__, $sql);
		}
		$i += 10;
	}
}
// END
// --------------------------


if( !isset($HTTP_POST_VARS['mode']) )
{
	if( !isset($HTTP_GET_VARS['action']) )
	{
		$template->set_filenames(array(
			'body' => 'admin/album_cat_body.tpl')
		);

		$template->assign_vars(array(
			'L_ALBUM_CAT_TITLE' => $lang['Album_Categories_Title'],
			'L_ALBUM_CAT_EXPLAIN' => $lang['Album_Categories_Explain'],
			'S_ALBUM_ACTION' => append_sid("admin_album_cat.$phpEx"),
			'L_MOVE_UP' => '<img src="' . $phpbb_root_path . $images['acp_up'] . '" alt="' . $lang['Move_up'] . '" title="' . $lang['Move_up'] . '" />',
			'L_MOVE_DOWN' => '<img src="' . $phpbb_root_path . $images['acp_down'] . '" alt="' . $lang['Move_down'] . '" title="' . $lang['Move_down'] . '" />',
			'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
			'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
			'S_MODE' => 'new',
			'L_CREATE_CATEGORY' => $lang['Create_category'])
		);

		$sql = "SELECT *
			FROM ". ALBUM_CAT_TABLE ."
			ORDER BY cat_order ASC";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query Album Categories information', '', __LINE__, __FILE__, $sql);
		}
		while ($row = $db->sql_fetchrow($result))
		{
			$catrow[] = $row;
		}

		for( $i = 0; $i < count($catrow); $i++ )
		{
			$template->assign_block_vars('catrow', array(
				'TITLE' => $catrow[$i]['cat_title'],
				'DESC' => $catrow[$i]['cat_desc'],
				'S_MOVE_UP' => append_sid("admin_album_cat.$phpEx?action=move&amp;move=-15&amp;cat_id=" . $catrow[$i]['cat_id']),
				'S_MOVE_DOWN' => append_sid("admin_album_cat.$phpEx?action=move&amp;move=15&amp;cat_id=" . $catrow[$i]['cat_id']),
				'S_EDIT_ACTION' => append_sid("admin_album_cat.$phpEx?action=edit&amp;cat_id=" . $catrow[$i]['cat_id']),
				'S_DELETE_ACTION' => append_sid("admin_album_cat.$phpEx?action=delete&amp;cat_id=" . $catrow[$i]['cat_id']))
			);
		}

		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	else
	{
		if( $HTTP_GET_VARS['action'] == 'edit' )
		{
			$cat_id = intval($HTTP_GET_VARS['cat_id']);

			$sql = "SELECT *
				FROM ". ALBUM_CAT_TABLE ."
				WHERE cat_id = '$cat_id'";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query Album Categories information', '', __LINE__, __FILE__, $sql);
			}
			if( $db->sql_numrows($result) == 0 )
			{
				message_die(GENERAL_ERROR, 'The requested category is not existed');
			}
			$catrow = $db->sql_fetchrow($result);

			$template->set_filenames(array(
				'body' => 'admin/album_cat_new_body.tpl')
			);

			$template->assign_vars(array(
				'L_ALBUM_CAT_TITLE' => $lang['Album_Categories_Title'],
				'L_ALBUM_CAT_EXPLAIN' => $lang['Album_Categories_Explain'],
				'S_ALBUM_ACTION' => append_sid("admin_album_cat.$phpEx?cat_id=$cat_id"),
				'L_CAT_TITLE' => $lang['Category_Title'],
				'L_CAT_DESC' => $lang['Category_Desc'],
				'L_CAT_PERMISSIONS' => $lang['Category_Permissions'],
				'L_VIEW_LEVEL' => $lang['View_level'],
				'L_UPLOAD_LEVEL' => $lang['Upload_level'],
				'L_RATE_LEVEL' => $lang['Rate_level'],
				'L_COMMENT_LEVEL' => $lang['Comment_level'],
				'L_EDIT_LEVEL' => $lang['Edit_level'],
				'L_DELETE_LEVEL' => $lang['Delete_level'],
				'L_PICS_APPROVAL' => $lang['Pics_Approval'],
				'L_GUEST' => $lang['Forum_ALL'], 
				'L_REG' => $lang['Forum_REG'], 
				'L_PRIVATE' => $lang['Forum_PRIVATE'], 
				'L_MOD' => $lang['Forum_MOD'], 
				'L_ADMIN' => $lang['Forum_ADMIN'],

				'L_DISABLED' => $lang['Disabled'],

				'S_CAT_TITLE' => $catrow['cat_title'],
				'S_CAT_DESC' => $catrow['cat_desc'],

				'VIEW_GUEST' => ($catrow['cat_view_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'VIEW_REG' => ($catrow['cat_view_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'VIEW_PRIVATE' => ($catrow['cat_view_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'VIEW_MOD' => ($catrow['cat_view_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'VIEW_ADMIN' => ($catrow['cat_view_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'UPLOAD_GUEST' => ($catrow['cat_upload_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'UPLOAD_REG' => ($catrow['cat_upload_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'UPLOAD_PRIVATE' => ($catrow['cat_upload_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'UPLOAD_MOD' => ($catrow['cat_upload_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'UPLOAD_ADMIN' => ($catrow['cat_upload_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'RATE_GUEST' => ($catrow['cat_rate_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'RATE_REG' => ($catrow['cat_rate_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'RATE_PRIVATE' => ($catrow['cat_rate_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'RATE_MOD' => ($catrow['cat_rate_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'RATE_ADMIN' => ($catrow['cat_rate_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'COMMENT_GUEST' => ($catrow['cat_comment_level'] == ALBUM_GUEST) ? 'selected="selected"' : '',
				'COMMENT_REG' => ($catrow['cat_comment_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'COMMENT_PRIVATE' => ($catrow['cat_comment_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'COMMENT_MOD' => ($catrow['cat_comment_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'COMMENT_ADMIN' => ($catrow['cat_comment_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'EDIT_REG' => ($catrow['cat_edit_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'EDIT_PRIVATE' => ($catrow['cat_edit_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'EDIT_MOD' => ($catrow['cat_edit_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'EDIT_ADMIN' => ($catrow['cat_edit_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'DELETE_REG' => ($catrow['cat_delete_level'] == ALBUM_USER) ? 'selected="selected"' : '',
				'DELETE_PRIVATE' => ($catrow['cat_delete_level'] == ALBUM_PRIVATE) ? 'selected="selected"' : '',
				'DELETE_MOD' => ($catrow['cat_delete_level'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'DELETE_ADMIN' => ($catrow['cat_delete_level'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'APPROVAL_DISABLED' => ($catrow['cat_approval'] == ALBUM_USER) ? 'selected="selected"' : '',
				'APPROVAL_MOD' => ($catrow['cat_approval'] == ALBUM_MOD) ? 'selected="selected"' : '',
				'APPROVAL_ADMIN' => ($catrow['cat_approval'] == ALBUM_ADMIN) ? 'selected="selected"' : '',

				'S_MODE' => 'edit',

				'S_GUEST' => ALBUM_GUEST,
				'S_USER' => ALBUM_USER,
				'S_PRIVATE' => ALBUM_PRIVATE,
				'S_MOD' => ALBUM_MOD,
				'S_ADMIN' => ALBUM_ADMIN,

				'L_PANEL_TITLE' => $lang['Edit_Category'])
			);

			$template->pparse('body');

			include('./page_footer_admin.'.$phpEx);
		}
		else if( $HTTP_GET_VARS['action'] == 'delete' )
		{
			$cat_id = intval($HTTP_GET_VARS['cat_id']);

			$sql = "SELECT cat_id, cat_title, cat_order
				FROM ". ALBUM_CAT_TABLE ."
				ORDER BY cat_order ASC";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query Album Categories information', '', __LINE__, __FILE__, $sql);
			}

			$cat_found = FALSE;
			while( $row = $db->sql_fetchrow($result) )
			{
				if( $row['cat_id'] == $cat_id )
				{
					$thiscat = $row;
					$cat_found = TRUE;
				}
				else
				{
					$catrow[] = $row;
				}
			}
			if( $cat_found == FALSE )
			{
				message_die(GENERAL_ERROR, 'The requested category is not existed');
			}

			$select_to = '<select name="target"><option value="0">'. $lang['Delete_all_pics'] .'</option>';
			for ($i = 0; $i < count($catrow); $i++)
			{
				$select_to .= '<option value="'. $catrow[$i]['cat_id'] .'">'. $catrow[$i]['cat_title'] .'</option>';
			}
			$select_to .= '</select>';

			$template->set_filenames(array(
				'body' => 'admin/album_cat_delete_body.tpl')
			);

			$template->assign_vars(array(
				'S_ALBUM_ACTION' => append_sid("admin_album_cat.$phpEx?cat_id=$cat_id"),
				'L_CAT_DELETE' => $lang['Delete_Category'],
				'L_CAT_DELETE_EXPLAIN' => $lang['Delete_Category_Explain'],
				'L_CAT_TITLE' => $lang['Category_Title'],
				'S_CAT_TITLE' => $thiscat['cat_title'],
				'L_MOVE_CONTENTS' => $lang['Move_contents'],
				'L_MOVE_DELETE' => $lang['Move_and_Delete'],
				'S_SELECT_TO' => $select_to)
			);

			$template->pparse('body');

			include('./page_footer_admin.'.$phpEx);
		}
		else if( $HTTP_GET_VARS['action'] == 'move' )
		{
			$cat_id = intval($HTTP_GET_VARS['cat_id']);
			$move = intval($HTTP_GET_VARS['move']);

			$sql = "UPDATE ". ALBUM_CAT_TABLE ."
				SET cat_order = cat_order + $move
				WHERE cat_id = $cat_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not change category order', '', __LINE__, __FILE__, $sql);
			}

			reorder_cat();

			// Return a message...
			$message = $lang['Category_changed_order'] . "<br /><br />" . sprintf($lang['Click_return_album_category'], "<a href=\"" . append_sid("admin_album_cat.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
	}
}
else
{
	if( $HTTP_POST_VARS['mode'] == 'new' )
	{
		if( !isset($HTTP_POST_VARS['cat_title']) )
		{
			$template->set_filenames(array(
				'body' => 'admin/album_cat_new_body.tpl')
			);

			$template->assign_vars(array(
				'L_ALBUM_CAT_TITLE' => $lang['Album_Categories_Title'],
				'L_ALBUM_CAT_EXPLAIN' => $lang['Album_Categories_Explain'],
				'S_ALBUM_ACTION' => append_sid("admin_album_cat.$phpEx"),
				'L_CAT_TITLE' => $lang['Category_Title'],
				'L_CAT_DESC' => $lang['Category_Desc'],
				'L_CAT_PERMISSIONS' => $lang['Category_Permissions'],
				'L_VIEW_LEVEL' => $lang['View_level'],
				'L_UPLOAD_LEVEL' => $lang['Upload_level'],
				'L_RATE_LEVEL' => $lang['Rate_level'],
				'L_COMMENT_LEVEL' => $lang['Comment_level'],
				'L_EDIT_LEVEL' => $lang['Edit_level'],
				'L_DELETE_LEVEL' => $lang['Delete_level'],
				'L_PICS_APPROVAL' => $lang['Pics_Approval'],
				'L_GUEST' => $lang['Forum_ALL'], 
				'L_REG' => $lang['Forum_REG'], 
				'L_PRIVATE' => $lang['Forum_PRIVATE'], 
				'L_MOD' => $lang['Forum_MOD'], 
				'L_ADMIN' => $lang['Forum_ADMIN'],

				'L_DISABLED' => $lang['Disabled'],

				'VIEW_GUEST' => 'selected="selected"',
				'UPLOAD_REG' => 'selected="selected"',
				'RATE_REG' => 'selected="selected"',
				'COMMENT_REG' => 'selected="selected"',
				'EDIT_REG' => 'selected="selected"',
				'DELETE_MOD' => 'selected="selected"',
				'APPROVAL_DISABLED' => 'selected="selected"',

				'S_MODE' => 'new',

				'S_GUEST' => ALBUM_GUEST,
				'S_USER' => ALBUM_USER,
				'S_PRIVATE' => ALBUM_PRIVATE,
				'S_MOD' => ALBUM_MOD,
				'S_ADMIN' => ALBUM_ADMIN,

				'L_PANEL_TITLE' => $lang['Create_category'])
			);

			$template->pparse('body');

			include('./page_footer_admin.'.$phpEx);
		}
		else
		{
			// Get posting variables
			$cat_title = str_replace("\'", "''", htmlspecialchars(trim($HTTP_POST_VARS['cat_title'])));
			$cat_desc = str_replace("\'", "''", trim($HTTP_POST_VARS['cat_desc']));
			$view_level = intval($HTTP_POST_VARS['cat_view_level']);
			$upload_level = intval($HTTP_POST_VARS['cat_upload_level']);
			$rate_level = intval($HTTP_POST_VARS['cat_rate_level']);
			$comment_level = intval($HTTP_POST_VARS['cat_comment_level']);
			$edit_level = intval($HTTP_POST_VARS['cat_edit_level']);
			$delete_level = intval($HTTP_POST_VARS['cat_delete_level']);
			$cat_approval = intval($HTTP_POST_VARS['cat_approval']);

			// Get the last ordered category
			$sql = "SELECT cat_order FROM ". ALBUM_CAT_TABLE ."
				ORDER BY cat_order DESC
				LIMIT 1";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query Album Categories information', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);
			$last_order = $row['cat_order'];
			$cat_order = $last_order + 10;

			// Here we insert a new row into the db
			$sql = "INSERT INTO ". ALBUM_CAT_TABLE ." (cat_title, cat_desc, cat_order, cat_view_level, cat_upload_level, cat_rate_level, cat_comment_level, cat_edit_level, cat_delete_level, cat_approval)
				VALUES ('$cat_title', '$cat_desc', '$cat_order', '$view_level', '$upload_level', '$rate_level', '$comment_level', '$edit_level', '$delete_level', '$cat_approval')";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not create new Album Category', '', __LINE__, __FILE__, $sql);
			}

			// Return a message...
			$message = $lang['New_category_created'] . "<br /><br />" . sprintf($lang['Click_return_album_category'], "<a href=\"" . append_sid("admin_album_cat.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else if( $HTTP_POST_VARS['mode'] == 'edit' )
	{
		// Get posting variables
		$cat_id = intval($HTTP_GET_VARS['cat_id']);
		$cat_title = str_replace("\'", "''", htmlspecialchars(trim($HTTP_POST_VARS['cat_title'])));
		$cat_desc = str_replace("\'", "''", trim($HTTP_POST_VARS['cat_desc']));
		$view_level = intval($HTTP_POST_VARS['cat_view_level']);
		$upload_level = intval($HTTP_POST_VARS['cat_upload_level']);
		$rate_level = intval($HTTP_POST_VARS['cat_rate_level']);
		$comment_level = intval($HTTP_POST_VARS['cat_comment_level']);
		$edit_level = intval($HTTP_POST_VARS['cat_edit_level']);
		$delete_level = intval($HTTP_POST_VARS['cat_delete_level']);
		$cat_approval = intval($HTTP_POST_VARS['cat_approval']);

		// Now we update this row
		$sql = "UPDATE ". ALBUM_CAT_TABLE ."
			SET cat_title = '$cat_title', cat_desc = '$cat_desc', cat_view_level = '$view_level', cat_upload_level = '$upload_level', cat_rate_level = '$rate_level', cat_comment_level = '$comment_level', cat_edit_level = '$edit_level', cat_delete_level = '$delete_level', cat_approval = '$cat_approval'
			WHERE cat_id = '$cat_id'";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not update this Album Category', '', __LINE__, __FILE__, $sql);
		}

		// Return a message...
		$message = $lang['Category_updated'] . "<br /><br />" . sprintf($lang['Click_return_album_category'], "<a href=\"" . append_sid("admin_album_cat.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
	else if( $HTTP_POST_VARS['mode'] == 'delete' )
	{
		$cat_id = intval($HTTP_GET_VARS['cat_id']);
		$target = intval($HTTP_POST_VARS['target']);

		if( $target == 0 ) // Delete All
		{
			// Get file information of all pics in this category
			$sql = "SELECT pic_id, pic_filename, pic_thumbnail, pic_cat_id
				FROM ". ALBUM_TABLE ."
				WHERE pic_cat_id = '$cat_id'";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not query Album information', '', __LINE__, __FILE__, $sql);
			}
			$picrow = array();
			while( $row = $db ->sql_fetchrow($result) )
			{
				$picrow[] = $row;
				$pic_id_row[] = $row['pic_id'];
			}

			if( count($picrow) != 0 ) // if this category is not empty
			{
				// Delete all physical pic & cached thumbnail files
				for ($i = 0; $i < count($picrow); $i++)
				{
					@unlink('../' . ALBUM_CACHE_PATH . $picrow[$i]['pic_thumbnail']);

					@unlink('../' . ALBUM_UPLOAD_PATH . $picrow[$i]['pic_filename']);
				}

				$pic_id_sql = '(' . implode(',', $pic_id_row) . ')';

				// Delete all related ratings
				$sql = "DELETE FROM ". ALBUM_RATE_TABLE ."
					WHERE rate_pic_id IN ". $pic_id_sql;
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not delete Ratings information', '', __LINE__, __FILE__, $sql);
				}

				// Delete all related comments
				$sql = "DELETE FROM ". ALBUM_COMMENT_TABLE ."
					WHERE comment_pic_id IN ". $pic_id_sql;
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not delete Comments information', '', __LINE__, __FILE__, $sql);
				}

				// Delete pic entries in db
				$sql = "DELETE FROM ". ALBUM_TABLE ."
					WHERE pic_cat_id = '$cat_id'";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not delete pic entries in the DB', '', __LINE__, __FILE__, $sql);
				}
			}

			// This category is now emptied, we can remove it!
			$sql = "DELETE FROM ". ALBUM_CAT_TABLE ."
					WHERE cat_id = '$cat_id'";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete this Category', '', __LINE__, __FILE__, $sql);
			}

			// Re-order the rest of categories
			reorder_cat();

			// Return a message...
			$message = $lang['Category_deleted'] . "<br /><br />" . sprintf($lang['Click_return_album_category'], "<a href=\"" . append_sid("admin_album_cat.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
		else // Move content...
		{
			$sql = "UPDATE ". ALBUM_TABLE ."
				SET pic_cat_id = '$target'
				WHERE pic_cat_id = '$cat_id'";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not update this Category content', '', __LINE__, __FILE__, $sql);
			}

			// This category is now emptied, we can remove it!
			$sql = "DELETE FROM ". ALBUM_CAT_TABLE ."
				WHERE cat_id = '$cat_id'";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete this Category', '', __LINE__, __FILE__, $sql);
			}

			// Re-order the rest of categories
			reorder_cat();

			// Return a message...
			$message = $lang['Category_deleted'] . "<br /><br />" . sprintf($lang['Click_return_album_category'], "<a href=\"" . append_sid("admin_album_cat.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

?>
<?php
/** 
*
* @package phpBB2
* @version $Id: album_modcp.php,v 2.0.5 2003/03/02 20:07:50 ngoctu Exp $
* @copyright (c) 2003 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
$album_root_path = $phpbb_root_path . 'mods/album/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ALBUM);
init_userprefs($userdata);
//
// End session management
//

//
// Get general album information
//
include($album_root_path . 'album_common.'.$phpEx);


// ------------------------------------
// Get the $pic_id from GET method then query out the category
// If $pic_id not found we will assign it to FALSE
// We will check $pic_id[] in POST method later (in $mode carry out)
// ------------------------------------
if( isset($HTTP_GET_VARS['pic_id']) )
{
	$pic_id = intval($HTTP_GET_VARS['pic_id']);
}
else
{
	$pic_id = FALSE;
}

if( $pic_id != FALSE )
{
	//
	// Get this pic info
	//
	$sql = "SELECT *
		FROM ". ALBUM_TABLE ."
		WHERE pic_id = '$pic_id'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
	}
	$thispic = $db->sql_fetchrow($result);
	
	if( empty($thispic) )
	{
		message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
	
	$cat_id = $thispic['pic_cat_id'];
	$user_id = $thispic['pic_user_id'];
}
else
{
	//
	// No $pic_id found, try to find $cat_id
	//
	if( isset($HTTP_POST_VARS['cat_id']) )
	{
		$cat_id = intval($HTTP_POST_VARS['cat_id']);
	}
	else if( isset($HTTP_GET_VARS['cat_id']) )
	{
		$cat_id = intval($HTTP_GET_VARS['cat_id']);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Category_no_specify'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}
//
// END check $pic_id and $cat_id
//

// ------------------------------------
// Get the cat info
// ------------------------------------
if( ($cat_id == PERSONAL_GALLERY) and (($HTTP_GET_VARS['mode'] == 'lock') or ($HTTP_GET_VARS['mode'] == 'unlock')) )
{
	$thiscat = init_personal_gallery_cat($user_id);
}
else
{
	$sql = "SELECT *
		FROM ". ALBUM_CAT_TABLE ."
		WHERE cat_id = '$cat_id'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query category information', '', __LINE__, __FILE__, $sql);
	}

	$thiscat = $db->sql_fetchrow($result);
}

if (empty($thiscat))
{
	message_die(GENERAL_MESSAGE, $lang['Category_not_exist']. '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}

$auth_data = album_user_access($cat_id, $thiscat, 0, 0, 0, 0, 0, 0); // MODERATOR only
//
// END category info
//

// ------------------------------------
// set $mode (select action)
// ------------------------------------
if( isset($HTTP_POST_VARS['mode']) )
{
	// Oh data from Mod CP
	if( isset($HTTP_POST_VARS['move']) )
	{
		$mode = 'move';
	}
	else if( isset($HTTP_POST_VARS['lock']) )
	{
		$mode = 'lock';
	}
	else if( isset($HTTP_POST_VARS['unlock']) )
	{
		$mode = 'unlock';
	}
	else if( isset($HTTP_POST_VARS['delete']) )
	{
		$mode = 'delete';
	}
	else if( isset($HTTP_POST_VARS['approval']) )
	{
		$mode = 'approval';
	}
	else if( isset($HTTP_POST_VARS['unapproval']) )
	{
		$mode = 'unapproval';
	}
	else
	{
		$mode = '';
	}
}
else if( isset($HTTP_GET_VARS['mode']) )
{
	$mode = trim($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}
//
// END $mode (select action)
//

// ------------------------------------
// Check the permissions
// ------------------------------------
if ($auth_data['moderator'] == 0)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("login.$phpEx?redirect=album_modcp.$phpEx&amp;cat_id=$cat_id"));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorised']. '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}
//
// END permissions
//


/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/
if ($mode == '')
{
	// --------------------------------
	// Moderator Control Panel
	// --------------------------------
	// Set Variables
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	$start = ($start < 0) ? 0 : $start;

	if( isset($HTTP_GET_VARS['sort_method']) )
	{
		switch ($HTTP_GET_VARS['sort_method'])
		{
			case 'pic_title':
				$sort_method = 'pic_title';
				break;
			case 'pic_user_id':
				$sort_method = 'pic_user_id';
				break;
			case 'pic_view_count':
				$sort_method = 'pic_view_count';
				break;
			case 'rating':
				$sort_method = 'rating';
				break;
			case 'comments':
				$sort_method = 'comments';
				break;
			case 'new_comment':
				$sort_method = 'new_comment';
				break;
			default:
				$sort_method = 'pic_time';
				break;
		}
	}
	else if( isset($HTTP_POST_VARS['sort_method']) )
	{
		switch ($HTTP_POST_VARS['sort_method'])
		{
			case 'pic_title':
				$sort_method = 'pic_title';
				break;
			case 'pic_user_id':
				$sort_method = 'pic_user_id';
				break;
			case 'pic_view_count':
				$sort_method = 'pic_view_count';
				break;
			case 'rating':
				$sort_method = 'rating';
				break;
			case 'comments':
				$sort_method = 'comments';
				break;
			case 'new_comment':
				$sort_method = 'new_comment';
				break;
			default:
				$sort_method = 'pic_time';
				break;
		}
	}
	else
	{
		$sort_method = 'pic_time';
	}

	if(isset($HTTP_POST_VARS['sort_order']))
	{
		$sort_order = ($HTTP_POST_VARS['sort_order'] == 'ASC') ? 'ASC' : 'DESC';
	}
	else if(isset($HTTP_GET_VARS['sort_order']))
	{
		$sort_order = ($HTTP_GET_VARS['sort_order'] == 'ASC') ? 'ASC' : 'DESC';
	}
	else
	{
		$sort_order = 'DESC';
	}

	// Count Pics
	$sql = "SELECT COUNT(pic_id) AS count
		FROM ". ALBUM_TABLE ."
		WHERE pic_cat_id = '$cat_id'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not count pics in this category', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);

	$total_pics = $row['count'];

	$pics_per_page = $board_config['topics_per_page']; // Text list only

	// get information from DB
	if ($total_pics > 0)
	{
		$limit_sql = ($start == 0) ? $pics_per_page : $start .', '. $pics_per_page;

		$pic_approval_sql = '';
		if( ($userdata['user_level'] != ADMIN) && ($thiscat['cat_approval'] == ALBUM_ADMIN) )
		{
			// because he went through my Permission Checking above so he must be at least a Moderator
			$pic_approval_sql = 'AND p.pic_approval = 1';
		}

		$sql = "SELECT p.pic_id, p.pic_title, p.pic_user_id, p.pic_user_ip, p.pic_username, p.pic_time, p.pic_cat_id, p.pic_view_count, p.pic_lock, p.pic_approval, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(c.comment_id) AS comments, MAX(c.comment_id) AS new_comment
			FROM ". ALBUM_TABLE ." AS p
				LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
			WHERE p.pic_cat_id = '$cat_id' $pic_approval_sql
			GROUP BY p.pic_id
			ORDER BY $sort_method $sort_order
			LIMIT $limit_sql";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query pics information', '', __LINE__, __FILE__, $sql);
		}

		$picrow = array();
		while( $row = $db->sql_fetchrow($result) )
		{
			$picrow[] = $row;
		}
		$db->sql_freeresult($result);

		for ($i = 0; $i < sizeof($picrow); $i++)
		{
			if( ($picrow[$i]['user_id'] == ALBUM_GUEST) || ($picrow[$i]['username'] == '') )
			{
				$pic_poster = ($picrow[$i]['pic_username'] == '') ? $lang['Guest'] : $picrow[$i]['pic_username'];
			}
			else
			{
				$pic_poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $picrow[$i]['user_id']) .'">'. $picrow[$i]['username'] .'</a>';
			}

			$template->assign_block_vars('picrow', array(
				'PIC_ID' => $picrow[$i]['pic_id'],
				'PIC_TITLE' => '<a href="'. append_sid("album_pic.$phpEx?pic_id=". $picrow[$i]['pic_id']) .'" target="_blank">'. $picrow[$i]['pic_title'] .'</a>',
				'POSTER' => $pic_poster,
				'TIME' => create_date($board_config['default_dateformat'], $picrow[$i]['pic_time'], $board_config['board_timezone']),
				'RATING' => ($picrow[$i]['rating'] == 0) ? $lang['Not_rated'] : round($picrow[$i]['rating'], 2),
				'COMMENTS' => $picrow[$i]['comments'],
				'LOCK' => ($picrow[$i]['pic_lock'] == 0) ? '' : $lang['Locked'],
				'APPROVAL' => ($picrow[$i]['pic_approval'] == 0) ? $lang['Not_approved'] : $lang['Approved'])
			);
		}

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination(append_sid("album_modcp.$phpEx?cat_id=$cat_id&amp;sort_method=$sort_method&amp;sort_order=$sort_order"), $total_pics, $pics_per_page, $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $pics_per_page ) + 1 ), ceil( $total_pics / $pics_per_page )))
		);
	}
	else
	{
		// No Pics
		$template->assign_block_vars('no_pics', array());
	}

	//
	// Start output of page (ModCP)
	//
	$page_title = $lang['Album'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'album_modcp_body.tpl')
	);

	$sort_rating_option = $sort_comments_option = '';
	if( $album_config['rate'] == 1 )
	{
		$sort_rating_option = '<option value="rating" ';
		$sort_rating_option .= ($sort_method == 'rating') ? 'selected="selected"' : '';
		$sort_rating_option .= '>' . $lang['Rating'] .'</option>';
	}
	if( $album_config['comment'] == 1 )
	{
		$sort_comments_option = '<option value="comments" ';
		$sort_comments_option .= ($sort_method == 'comments') ? 'selected="selected"' : '';
		$sort_comments_option .= '>' . $lang['Comments'] .'</option>';
		$sort_new_comment_option = '<option value="new_comment" ';
		$sort_new_comment_option .= ($sort_method == 'new_comment') ? 'selected="selected"' : '';
		$sort_new_comment_option .= '>' . $lang['New_Comment'] .'</option>';
	}

	$template->assign_vars(array(
		'L_CATEGORY' => $lang['Category'],
		'L_MODCP' => $lang['Mod_CP'],
		'L_NO_PICS' => $lang['No_Pics'],
		'L_VIEW' => $lang['View'],
		'L_POSTER' => $lang['Poster'],
		'L_POSTED' => $lang['Posted'],
		'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
		'L_USERNAME' => $lang['Sort_Username'],
		'L_ORDER' => $lang['Order'],
		'L_TIME' => $lang['Time'],
		'L_PIC_TITLE' => $lang['Pic_Title'],
		'L_POSTER' => $lang['Poster'],
		'L_RATING' => $lang['Rating'],
		'L_COMMENTS' => $lang['Comments'],
		'L_STATUS' => $lang['Status'],
		'L_APPROVAL' => $lang['Approval'],
		'L_MARK' => $lang['Mark'],
		'L_DELETE' => $lang['Delete'],
		'L_MOVE' => $lang['Move'],
		'L_LOCK' => $lang['Lock'],
		'L_UNLOCK' => $lang['Unlock'],
		'L_ASC' => $lang['Sort_Ascending'],
		'L_DESC' => $lang['Sort_Descending'],
		'L_MARK_ALL' => $lang['Mark_all'], 
		'L_UNMARK_ALL' => $lang['Unmark_all'], 

		'CAT_TITLE' => $thiscat['cat_title'],
		'DELETE_BUTTON' => ($auth_data['delete'] == 1) ? '<input type="submit" class="liteoption" name="delete" value="'. $lang['Delete'] .'" />' : '',
		'APPROVAL_BUTTON' => ( ($userdata['user_level'] != ADMIN) and ($thiscat['cat_approval'] == ALBUM_ADMIN) ) ? '' : '<input type="submit" class="liteoption" name="approval" value="'. $lang['Approve'] .'" />',
		'UNAPPROVAL_BUTTON' => ( ($userdata['user_level'] != ADMIN) and ($thiscat['cat_approval'] == ALBUM_ADMIN) ) ? '' : '<input type="submit" class="liteoption" name="unapproval" value="'. $lang['Unapprove'] .'" />',
		'SORT_TIME' => ($sort_method == 'pic_time') ? 'selected="selected"' : '',
		'SORT_PIC_TITLE' => ($sort_method == 'pic_title') ? 'selected="selected"' : '',
		'SORT_USERNAME' => ($sort_method == 'pic_user_id') ? 'selected="selected"' : '',
		'SORT_VIEW' => ($sort_method == 'pic_view_count') ? 'selected="selected"' : '',
		'SORT_RATING_OPTION' => $sort_rating_option,
		'SORT_COMMENTS_OPTION' => $sort_comments_option,
		'SORT_NEW_COMMENT_OPTION' => $sort_new_comment_option,
		'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
		'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '',
			
		'U_VIEW_CAT' => append_sid("album_modcp.$phpEx?cat_id=$cat_id"),
		'S_ALBUM_ACTION' => append_sid("album_modcp.$phpEx?cat_id=$cat_id"))
	);

	//
	// Generate the page (ModCP)
	//
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else
{
	//
	// Switch with $mode
	//
	if ($mode == 'move')
	{
		//-----------------------------
		// MOVE
		//-----------------------------

		if( !isset($HTTP_POST_VARS['target']) )
		{
			// if "target" has not been set, we will open the category select form
			//
			// we must check POST method now
			$pic_id_array = array();
			if ($pic_id != FALSE) // from GET
			{
				$pic_id_array[] = $pic_id;
			}
			else
			{
				// Check $pic_id[] on POST Method now
				if( isset($HTTP_POST_VARS['pic_id']) )
				{
					$pic_id_array = $HTTP_POST_VARS['pic_id'];
					if( !is_array($pic_id_array) )
					{
						message_die(GENERAL_ERROR, 'Invalid request');
					}
				}
				else
				{
					message_die(GENERAL_ERROR, 'No pics specified');
				}
			}

			// We must send out the $pic_id_array to store data between page changing
			for ($i = 0; $i < count($pic_id_array); $i++)
			{
				$template->assign_block_vars('pic_id_array', array(
					'VALUE' => $pic_id_array[$i])
				);
			}

			//
			// Create categories select
			//
			$sql = "SELECT *
				FROM ". ALBUM_CAT_TABLE ."
				WHERE cat_id <> '$cat_id'
				ORDER BY cat_order ASC";
			if( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
			}

			$catrows = array();

			while( $row = $db->sql_fetchrow($result) )
			{
				$album_user_access = album_user_access($row['cat_id'], $row, 0, 1, 0, 0, 0, 0);

				if ($album_user_access['upload'] == 1)
				{
					$catrows[] = $row;
				}
			}
			$db->sql_freeresult($result);

			if( sizeof($catrows) == 0 )
			{
				message_die(GENERAL_MESSAGE, $lang['Pics_move_cat_error'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}

			// write categories out
			$category_select = '<select name="target">';
			for ($i = 0; $i < sizeof($catrows); $i++)
			{
				$category_select .= '<option value="'. $catrows[$i]['cat_id'] .'">'. $catrows[$i]['cat_title'] .'</option>';
			}
			$category_select .= '</select>';
			// end write

			//
			// Start output of page
			//
			$page_title = $lang['Album'];
			include($phpbb_root_path . 'includes/page_header.'.$phpEx);

			$template->set_filenames(array(
				'body' => 'album_move_body.tpl')
			);

			$template->assign_vars(array(
				'S_ALBUM_ACTION' => append_sid("album_modcp.$phpEx?mode=move&amp;cat_id=$cat_id"),
				'L_MOVE' => $lang['Move'],
				'L_MOVE_TO_CATEGORY' => $lang['Move_to_Category'],
				'S_CATEGORY_SELECT' => $category_select)
			);

			//
			// Generate the page
			//
			$template->pparse('body');

			include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		}
		else
		{
			// Do the MOVE action
			//
			// Now we only get $pic_id[] via POST (after the select target screen)
			if( isset($HTTP_POST_VARS['pic_id']) )
			{
				$pic_id = $HTTP_POST_VARS['pic_id'];
				if( is_array($pic_id) )
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_MESSAGE, 'Invalid request.<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}

			// well, we got the array of pic_id but we must do a check to make sure all these
			// pics are in this category (prevent some naughty moderators to access un-authorised pics)
			$sql = "SELECT pic_id
				FROM ". ALBUM_TABLE ."
				WHERE pic_id IN ($pic_id_sql) 
					AND pic_cat_id <> $cat_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain album information', '', __LINE__, __FILE__, $sql);
			}
			
			if( $db->sql_numrows($result) > 0 )
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}

			// Update the DB
			$sql = "UPDATE ". ALBUM_TABLE ."
				SET pic_cat_id = ". intval($HTTP_POST_VARS['target']) ."
				WHERE pic_id IN ($pic_id_sql)";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update album information', '', __LINE__, __FILE__, $sql);
			}

			$message = $lang['Pics_moved_successfully'] .'<br /><br />'. sprintf($lang['Click_return_category'], "<a href=\"" . append_sid("album_cat.$phpEx?cat_id=$cat_id") . "\">", "</a>") .'<br /><br />'. sprintf($lang['Click_return_modcp'], "<a href=\"" . append_sid("album_modcp.$phpEx?cat_id=$cat_id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else if ($mode == 'lock')
	{
		//-----------------------------
		// LOCK
		//-----------------------------
		// we must check POST method now
		if ($pic_id != FALSE) // from GET
		{
			$pic_id_sql = $pic_id;
		}
		else
		{
			// Check $pic_id[] on POST Method now
			if( isset($HTTP_POST_VARS['pic_id']) )
			{
				$pic_id = $HTTP_POST_VARS['pic_id'];
				if( is_array($pic_id) )
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_MESSAGE, 'Invalid request.<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}
		}

		// well, we got the array of pic_id but we must do a check to make sure all these
		// pics are in this category (prevent some naughty moderators to access un-authorised pics)
		$sql = "SELECT pic_id
			FROM ". ALBUM_TABLE ."
			WHERE pic_id IN ($pic_id_sql) 
				AND pic_cat_id <> $cat_id";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain album information', '', __LINE__, __FILE__, $sql);
		}
		
		if( $db->sql_numrows($result) > 0 )
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}

		// update the DB
		$sql = "UPDATE ". ALBUM_TABLE ."
			SET pic_lock = 1
			WHERE pic_id IN ($pic_id_sql)";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update album information', '', __LINE__, __FILE__, $sql);
		}

		$message = $lang['Pics_locked_successfully'] .'<br /><br />';

		if ($cat_id != PERSONAL_GALLERY)
		{
			$message .= sprintf($lang['Click_return_category'], "<a href=\"" . append_sid("album_cat.$phpEx?cat_id=$cat_id") . "\">", "</a>") .'<br /><br />' . sprintf($lang['Click_return_modcp'], "<a href=\"" . append_sid("album_modcp.$phpEx?cat_id=$cat_id") . "\">", "</a>") . "<br /><br />";
		}
		else
		{
			$message .= sprintf($lang['Click_return_personal_gallery'], "<a href=\"" . append_sid("album_personal.$phpEx?user_id=$user_id") . "\">", "</a>") . '<br /><br />';
		}

		$message .= sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
	else if ($mode == 'unlock')
	{
		//-----------------------------
		// UNLOCK
		//-----------------------------
		// we must check POST method now
		if ($pic_id != FALSE) // from GET
		{
			$pic_id_sql = $pic_id;
		}
		else
		{
			// Check $pic_id[] on POST Method now
			if( isset($HTTP_POST_VARS['pic_id']) )
			{
				$pic_id = $HTTP_POST_VARS['pic_id'];
				if( is_array($pic_id) )
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_MESSAGE, 'Invalid request.<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}
		}

		// well, we got the array of pic_id but we must do a check to make sure all these
		// pics are in this category (prevent some naughty moderators to access un-authorised pics)
		$sql = "SELECT pic_id
			FROM ". ALBUM_TABLE ."
			WHERE pic_id IN ($pic_id_sql) 
				AND pic_cat_id <> $cat_id";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain album information', '', __LINE__, __FILE__, $sql);
		}
		
		if( $db->sql_numrows($result) > 0 )
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}

		// update the DB
		$sql = "UPDATE ". ALBUM_TABLE ."
			SET pic_lock = 0
			WHERE pic_id IN ($pic_id_sql)";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update album information', '', __LINE__, __FILE__, $sql);
		}

		$message = $lang['Pics_unlocked_successfully'] .'<br /><br />';

		if ($cat_id != PERSONAL_GALLERY)
		{
			$message .= sprintf($lang['Click_return_category'], "<a href=\"" . append_sid("album_cat.$phpEx?cat_id=$cat_id") . "\">", "</a>") . '<br /><br />' . sprintf($lang['Click_return_modcp'], "<a href=\"" . append_sid("album_modcp.$phpEx?cat_id=$cat_id") . "\">", "</a>") . "<br /><br />";
		}
		else
		{
			$message .= sprintf($lang['Click_return_personal_gallery'], "<a href=\"" . append_sid("album_personal.$phpEx?user_id=$user_id") . "\">", "</a>") . '<br /><br />';
		}

		$message .= sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
	else if ($mode == 'approval')
	{
		//-----------------------------
		// APPROVAL
		//-----------------------------
		// we must check POST method now
		if ($pic_id != FALSE) // from GET
		{
			$pic_id_sql = $pic_id;
		}
		else
		{
			// Check $pic_id[] on POST Method now
			if( isset($HTTP_POST_VARS['pic_id']) )
			{
				$pic_id = $HTTP_POST_VARS['pic_id'];
				if( is_array($pic_id) )
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_MESSAGE, 'Invalid request.<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}
		}

		// well, we got the array of pic_id but we must do a check to make sure all these
		// pics are in this category (prevent some naughty moderators to access un-authorised pics)
		$sql = "SELECT pic_id
			FROM ". ALBUM_TABLE ."
			WHERE pic_id IN ($pic_id_sql) 
				AND pic_cat_id <> $cat_id";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain album information', '', __LINE__, __FILE__, $sql);
		}
		
		if( $db->sql_numrows($result) > 0 )
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}

		// update the DB
		$sql = "UPDATE ". ALBUM_TABLE ."
			SET pic_approval = 1
			WHERE pic_id IN ($pic_id_sql)";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update album information', '', __LINE__, __FILE__, $sql);
		}

		$message = $lang['Pics_approved_successfully'] .'<br /><br />'. sprintf($lang['Click_return_category'], "<a href=\"" . append_sid("album_cat.$phpEx?cat_id=$cat_id") . "\">", "</a>") .'<br /><br />'. sprintf($lang['Click_return_modcp'], "<a href=\"" . append_sid("album_modcp.$phpEx?cat_id=$cat_id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
	else if ($mode == 'unapproval')
	{
		//-----------------------------
		// UNAPPROVAL
		//-----------------------------
		// we must check POST method now
		if ($pic_id != FALSE) // from GET
		{
			$pic_id_sql = $pic_id;
		}
		else
		{
			// Check $pic_id[] on POST Method now
			if( isset($HTTP_POST_VARS['pic_id']) )
			{
				$pic_id = $HTTP_POST_VARS['pic_id'];
				if( is_array($pic_id) )
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_MESSAGE, 'Invalid request.<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}
		}

		// well, we got the array of pic_id but we must do a check to make sure all these
		// pics are in this category (prevent some naughty moderators to access un-authorised pics)
		$sql = "SELECT pic_id
			FROM ". ALBUM_TABLE ."
			WHERE pic_id IN ($pic_id_sql) 
				AND pic_cat_id <> $cat_id";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain album information', '', __LINE__, __FILE__, $sql);
		}
		
		if( $db->sql_numrows($result) > 0 )
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}

		// update the DB
		$sql = "UPDATE ". ALBUM_TABLE ."
			SET pic_approval = 0
			WHERE pic_id IN ($pic_id_sql)";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update album information', '', __LINE__, __FILE__, $sql);
		}

		$message = $lang['Pics_unapproved_successfully'] .'<br /><br />'. sprintf($lang['Click_return_category'], "<a href=\"" . append_sid("album_cat.$phpEx?cat_id=$cat_id") . "\">", "</a>") .'<br /><br />'. sprintf($lang['Click_return_modcp'], "<a href=\"" . append_sid("album_modcp.$phpEx?cat_id=$cat_id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
	else if ($mode == 'delete')
	{
		//-----------------------------
		// DELETE
		//-----------------------------
		if ($auth_data['delete'] == 0)
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}

		if( !isset($HTTP_POST_VARS['confirm']) )
		{
			// we must check POST method now
			$pic_id_array = array();
			if ($pic_id != FALSE) // from GET
			{
				$pic_id_array[] = $pic_id;
			}
			else
			{
				// Check $pic_id[] on POST Method now
				if( isset($HTTP_POST_VARS['pic_id']) )
				{
					$pic_id_array = $HTTP_POST_VARS['pic_id'];
					if( !is_array($pic_id_array) )
					{
						message_die(GENERAL_MESSAGE, 'Invalid request.<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
					}
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
				}
			}

      		if ( isset($HTTP_POST_VARS['cancel']) )
        	{
            	$redirect = "album_modcp.$phpEx?cat_id=$cat_id";
            	redirect(append_sid($redirect, true));
         	}			

			// We must send out the $pic_id_array to store data between page changing
			$hidden_field = '';
			for ($i = 0; $i < sizeof($pic_id_array); $i++)
			{
				$hidden_field .= '<input name="pic_id[]" type="hidden" value="'. $pic_id_array[$i] .'" />' . "\n";
			}

			//
			// Start output of page
			//
			$page_title = $lang['Album'];
			include($phpbb_root_path . 'includes/page_header.'.$phpEx);

			$template->set_filenames(array(
				'body' => 'confirm_body.tpl')
			);

			$template->assign_vars(array(
				'MESSAGE_TITLE' => $lang['Confirm'],
				'MESSAGE_TEXT' => $lang['Album_delete_confirm'],
				'S_HIDDEN_FIELDS' => $hidden_field,
				'L_NO' => $lang['No'],
				'L_YES' => $lang['Yes'],
				'S_CONFIRM_ACTION' => append_sid("album_modcp.$phpEx?mode=delete&amp;cat_id=$cat_id"))
			);

			//
			// Generate the page
			//
			$template->pparse('body');

			include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
		}
		else
		{
			//
			// Do the delete here...
			//
			if( isset($HTTP_POST_VARS['pic_id']) )
			{
				$pic_id = $HTTP_POST_VARS['pic_id'];
				if( is_array($pic_id) )
				{
					$pic_id_sql = implode(',', $pic_id);
				}
				else
				{
					message_die(GENERAL_MESSAGE, 'Invalid request.<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
				}
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}

			// well, we got the array of pic_id but we must do a check to make sure all these
			// pics are in this category (prevent some naughty moderators to access un-authorised pics)
			$sql = "SELECT pic_id
				FROM ". ALBUM_TABLE ."
				WHERE pic_id IN ($pic_id_sql) 
					AND pic_cat_id <> $cat_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain album information', '', __LINE__, __FILE__, $sql);
			}
			
			if( $db->sql_numrows($result) > 0 )
			{
				message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
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
			$db->sql_freeresult($result);

			for ($i = 0; $i < sizeof($filerow); $i++)
			{
				if( ($filerow[$i]['pic_thumbnail'] != '') && (@file_exists(ALBUM_CACHE_PATH . $filerow[$i]['pic_thumbnail'])) )
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

			$message = $lang['Pics_deleted_successfully'] .'<br /><br />'. sprintf($lang['Click_return_category'], "<a href=\"" . append_sid("album_cat.$phpEx?cat_id=$cat_id") . "\">", "</a>") .'<br /><br />'. sprintf($lang['Click_return_modcp'], "<a href=\"" . append_sid("album_modcp.$phpEx?cat_id=$cat_id") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else
	{
		message_die(GENERAL_ERROR, 'Invalid_mode');
	}
}

?>
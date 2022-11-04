<?php
/** 
*
* @package phpBB2
* @version $Id: album_personal.php,v 2.0.6 2003/03/15 10:17:10 ngoctu Exp $
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
$userdata = session_pagestart($user_ip, PAGE_ALBUM_PERSONAL);
init_userprefs($userdata);
//
// End session management
//


//
// Get general album information
//
include($album_root_path . 'album_common.'.$phpEx);



// ------------------------------------
// Check the request
// ------------------------------------
if( isset($HTTP_POST_VARS['user_id']) )
{
	$user_id = intval($HTTP_POST_VARS['user_id']);
}
else if( isset($HTTP_GET_VARS['user_id']) )
{
	$user_id = intval($HTTP_GET_VARS['user_id']);
}
else
{
	$user_id = $userdata['user_id'];
}
//
// END check request
//

// ------------------------------------
// Check $user_id
// ------------------------------------
if( ($user_id < 1) && (!$userdata['session_logged_in']) )
{
	redirect(append_sid("login.$phpEx?redirect=album_personal.$phpEx"));
}

// ------------------------------------
// Get the username of this gallery's owner
// ------------------------------------
$sql = "SELECT username
	FROM ". USERS_TABLE ."
	WHERE user_id = " . $user_id;
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not get the username of personal gallery.', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

$username = $row['username'];

if( empty($username) )
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}

// ------------------------------------
// Check Permissions
// ------------------------------------
$personal_gallery_access = personal_gallery_access(1,1);

if( $personal_gallery_access['view'] == 0 )
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("login.$phpEx?redirect=album_personal.$phpEx&amp;user_id=$user_id"));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}
//
// END check permissions
//

// ------------------------------------
// Check own gallery
// ------------------------------------
if ($user_id == $userdata['user_id'])
{
	if( $personal_gallery_access['upload'] == 0 )
	{
		message_die(GENERAL_MESSAGE, $lang['Not_allowed_to_create_personal_gallery'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}
//
// End check own gallery
//

// ------------------------------------
// Build the thumbnail page
// ------------------------------------
$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if( isset($HTTP_GET_VARS['sort_method']) )
{
	switch ($HTTP_GET_VARS['sort_method'])
	{
		case 'pic_title':
			$sort_method = 'pic_title';
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
			$sort_method = $album_config['sort_method'];
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
			$sort_method = $album_config['sort_method'];
			break;
	}
}
else
{
	$sort_method = $album_config['sort_method'];
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
	$sort_order = $album_config['sort_order'];
}

$pics_per_page = $album_config['rows_per_page'] * $album_config['cols_per_page'];


// ------------------------------------
// Count Pics
// ------------------------------------
$sql = "SELECT COUNT(pic_id) AS count
	FROM ". ALBUM_TABLE ."
	WHERE pic_cat_id = ". PERSONAL_GALLERY ."
		AND pic_user_id = $user_id";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not count pics', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

$total_pics = $row['count'];

// ------------------------------------
// Build up
// ------------------------------------
if ($total_pics > 0)
{
	$limit_sql = ($start == 0) ? $pics_per_page : $start .','. $pics_per_page;

	$sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_user_ip, p.pic_time, p.pic_view_count, p.pic_lock, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments, MAX(c.comment_id) as new_comment
		FROM ". ALBUM_TABLE ." AS p
			LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
			LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
		WHERE p.pic_cat_id = ". PERSONAL_GALLERY ."
			AND p.pic_user_id = $user_id
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

	// --------------------------------
	// Thumbnails table
	// --------------------------------
	for ($i = 0; $i < sizeof($picrow); $i += $album_config['cols_per_page'])
	{
		$template->assign_block_vars('picrow', array());

		for ($j = $i; $j < ($i + $album_config['cols_per_page']); $j++)
		{
			if( $j >= sizeof($picrow) )
			{
				$template->assign_block_vars('picrow.nopiccol', array());
				$template->assign_block_vars('picrow.picnodetail', array());
				continue;
			}

			$template->assign_block_vars('picrow.piccol', array(
				'U_PIC' => ($album_config['fullpic_popup']) ? append_sid("album_pic.$phpEx?pic_id=". $picrow[$j]['pic_id']) : append_sid("album_showpage.$phpEx?pic_id=". $picrow[$j]['pic_id']),
				'THUMBNAIL' => append_sid("album_thumbnail.$phpEx?pic_id=". $picrow[$j]['pic_id']),
				'DESC' => $picrow[$j]['pic_desc'])
			);

			$image_rating = ImageRating($picrow[$j]['rating']);
			
			$template->assign_block_vars('picrow.pic_detail', array(
				'TITLE' => $picrow[$j]['pic_title'],
				'TIME' => create_date($board_config['default_dateformat'], $picrow[$j]['pic_time'], $board_config['board_timezone']),
				'VIEW' => $picrow[$j]['pic_view_count'],
				'RATING' => ($album_config['rate'] == 1) ? ( '<b>' . $lang['Rating'] . ':</b> ' . $image_rating . '<br />') : '',
				'COMMENTS' => ($album_config['comment'] == 1) ? ( '<b>' . $lang['Comments'] . ':</b> ' . $picrow[$j]['comments'] . '<br />') : '',
				'EDIT' => ( ($userdata['user_level'] == ADMIN) or ($userdata['user_id'] == $picrow[$j]['pic_user_id']) ) ? '<a href="'. append_sid("album_edit.$phpEx?pic_id=". $picrow[$j]['pic_id']) . '"><img src="' . $phpbb_root_path . $images['icon_edit'] . '" alt="' . $lang['Edit_pic'] . '" title="' . $lang['Edit_pic'] . '" /></a>' : '',
				'DELETE' => ( ($userdata['user_level'] == ADMIN) or ($userdata['user_id'] == $picrow[$j]['pic_user_id']) ) ? '<a href="'. append_sid("album_delete.$phpEx?pic_id=". $picrow[$j]['pic_id']) . '"><img src="' . $phpbb_root_path . $images['topic_mod_delete'] . '" alt="' . $lang['Delete_pic'] . '" title="' . $lang['Delete_pic'] . '" /></a>' : '',
				'LOCK' => ($userdata['user_level'] == ADMIN) ? '<a href="'. append_sid("album_modcp.$phpEx?mode=". (($picrow[$j]['pic_lock'] == 0) ? 'lock' : 'unlock') ."&amp;pic_id=". $picrow[$j]['pic_id']) .'">'. (($picrow[$j]['pic_lock'] == 0) ? '<img src="' . $phpbb_root_path . $images['topic_mod_lock'] . '" alt="' . $lang['Lock'] . '" title="' . $lang['Lock'] . '" />' : '<img src="' . $phpbb_root_path . $images['topic_mod_unlock'] . '" alt="' . $lang['Unlock'] . '" title="' . $lang['Unlock'] . '" />') . '</a>' : '',
				'IP' => ($userdata['user_level'] == ADMIN) ? '<b>' . $lang['IP_Address'] . ':</b> <a href="http://www.nic.com/cgi-bin/whois.cgi?query=' . decode_ip($picrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($picrow[$j]['pic_user_ip']) .'</a><br />' : '')
			);
		}
	}

	// --------------------------------
	// Pagination
	// --------------------------------
	$template->assign_vars(array(
		'PAGINATION' => generate_pagination(append_sid("album_personal.$phpEx?user_id=$user_id&amp;sort_method=$sort_method&amp;sort_order=$sort_order"), $total_pics, $pics_per_page, $start),
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $pics_per_page ) + 1 ), ceil( $total_pics / $pics_per_page )))
	);
}
else
{
	$template->assign_block_vars('no_pics', array());
}

/*
+----------------------------------------------------------
| Main page...
+----------------------------------------------------------
*/

// ------------------------------------
// additional sorting options
// ------------------------------------
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

//
// Start output of page
//
$page_title = $lang['Album'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'album_personal_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx); 

if( $user_id == $userdata['user_id'] )
{
	$template->assign_block_vars('your_personal_gallery', array());
}

$template->assign_vars(array(
	'U_UPLOAD_PIC' => append_sid("album_upload.$phpEx?cat_id=". PERSONAL_GALLERY),
	'U_PERSONAL_GALLERY' => append_sid("album_personal.$phpEx?user_id=$user_id"),

	'S_COLS' => $album_config['cols_per_page'],
	'S_COL_WIDTH' => (100 / $album_config['cols_per_page']) . '%',

	'L_UPLOAD_PIC' => $lang['Upload_Pic'],
	'L_PERSONAL_GALLERY_NOT_CREATED' => sprintf($lang['Personal_gallery_not_created'], $username),
	'L_VIEW' => $lang['View'],
	'L_POSTED' => $lang['Posted'],
	'L_YOUR_PERSONAL_GALLERY' => $lang['Your_Personal_Gallery'],
	'L_PERSONAL_GALLERY_EXPLAIN' => $lang['Personal_Gallery_Explain'],
	'L_PERSONAL_GALLERY_OF_USER' => sprintf($lang['Personal_Gallery_Of_User'], $username),
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_ORDER' => $lang['Order'],
	'L_TIME' => $lang['Time'],
	'L_PIC_TITLE' => $lang['Pic_Title'],
	'L_PIC_DESC' => $lang['Pic_Desc'],
	'L_ASC' => $lang['Sort_Ascending'],
	'L_DESC' => $lang['Sort_Descending'],
	'L_THAT_CONTAINS' => $lang['That_contains'],
	'L_SORT' => $lang['Sort'],
		
	'UPLOAD_PIC_IMG' => $images['upload_pic'],
	'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',
	'SORT_TIME' => ($sort_method == 'pic_time') ? 'selected="selected"' : '',
	'SORT_PIC_TITLE' => ($sort_method == 'pic_title') ? 'selected="selected"' : '',
	'SORT_VIEW' => ($sort_method == 'pic_view_count') ? 'selected="selected"' : '',
	'SORT_RATING_OPTION' => $sort_rating_option,
	'SORT_COMMENTS_OPTION' => $sort_comments_option,
	'SORT_NEW_COMMENT_OPTION' => $sort_new_comment_option,
	'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
	'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '')
);

//
// Generate the page
//
include($phpbb_root_path . 'profile_menu.'.$phpEx);
	
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
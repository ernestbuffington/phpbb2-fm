<?php
/** 
*
* @package phpBB2
* @version $Id: lbum_comment_delete.php,v 2.0.4 2003/04/03 21:22:39 ngoctu Exp $
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
// Check feature enabled
// ------------------------------------
if( $album_config['comment'] == 0 )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


// ------------------------------------
// Check the request
// ------------------------------------
if( isset($HTTP_GET_VARS['comment_id']) )
{
	$comment_id = intval($HTTP_GET_VARS['comment_id']);
}
else if( isset($HTTP_POST_VARS['comment_id']) )
{
	$comment_id = intval($HTTP_POST_VARS['comment_id']);
}
else
{
	message_die(GENERAL_MESSAGE, $lang['Comment_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


// ------------------------------------
// Get the comment info
// ------------------------------------
$sql = "SELECT *
	FROM ". ALBUM_COMMENT_TABLE ."
	WHERE comment_id = '$comment_id'";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query this comment information', '', __LINE__, __FILE__, $sql);
}

$thiscomment = $db->sql_fetchrow($result);

if( empty($thiscomment) )
{
	message_die(GENERAL_MESSAGE, $lang['Comment_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


// ------------------------------------
// Get $pic_id from $comment_id
// ------------------------------------
$sql = "SELECT comment_id, comment_pic_id
	FROM ". ALBUM_COMMENT_TABLE ."
	WHERE comment_id = '$comment_id'";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query comment and pic information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

if( empty($row) )
{
	message_die(GENERAL_MESSAGE, $lang['Comment_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}

$pic_id = $row['comment_pic_id'];


// ------------------------------------
// Get this pic info
// ------------------------------------
$sql = "SELECT p.*, u.user_id, u.username, COUNT(c.comment_id) as comments_count
	FROM ". ALBUM_TABLE ." AS p
		LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
		LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
	WHERE pic_id = '$pic_id'
	GROUP BY p.pic_id
	LIMIT 1";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['pic_cat_id'];
$user_id = $thispic['pic_user_id'];

$total_comments = $thispic['comments_count'];
$comments_per_page = $board_config['posts_per_page'];

$pic_filename = $thispic['pic_filename'];
$pic_thumbnail = $thispic['pic_thumbnail'];

if( empty($thispic) )
{
	message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


// ------------------------------------
// Get the current Category Info
// ------------------------------------
if ($cat_id != PERSONAL_GALLERY)
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
else
{
	$thiscat = init_personal_gallery_cat($user_id);
}

if (empty($thiscat))
{
	message_die(GENERAL_MESSAGE, $lang['Category_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_user_access($thispic['pic_cat_id'], $thiscat, 0, 0, 0, 1, 0, 1);

if( ($album_user_access['comment'] == 0) || ($album_user_access['delete'] == 0) )
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("login.$phpEx?redirect=album_comment_delete.$phpEx?comment_id=$comment_id"));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}
else
{	
	if( (!$album_user_access['moderator']) || ($userdata['user_level'] != ADMIN) )
	{
		if ($thiscomment['comment_user_id'] != $userdata['user_id'])
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}
	}
}

/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/

if( !isset($HTTP_POST_VARS['confirm']) )
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
               Confirm Screen
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	// --------------------------------
	// If user give up deleting...
	// --------------------------------
	if( isset($HTTP_POST_VARS['cancel']) )
	{
		redirect(append_sid("album_showpage.$phpEx?comment_id=$comment_id"));
		exit;
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

		'MESSAGE_TEXT' => $lang['Comment_delete_confirm'],

		'L_NO' => $lang['No'],
		'L_YES' => $lang['Yes'],

		'S_CONFIRM_ACTION' => append_sid("album_comment_delete.$phpEx?comment_id=$comment_id"))
	);

	//
	// Generate the page
	//
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
              Do the deleting
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	$sql = "DELETE
		FROM ". ALBUM_COMMENT_TABLE ."
		WHERE comment_id = '$comment_id'";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not delete this comment', '', __LINE__, __FILE__, $sql);
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$message = $lang['Deleted'];

	if ($cat_id != PERSONAL_GALLERY)
	{
		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("album_cat.$phpEx?cat_id=$cat_id") . '">')
		);

		$message .= "<br /><br />" . sprintf($lang['Click_return_category'], "<a href=\"" . append_sid("album_cat.$phpEx?cat_id=$cat_id") . "\">", "</a>");
	}
	else
	{
		$template->assign_vars(array(
			'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("album_personal.$phpEx?user_id=$user_id") . '">')
		);

		$message .= "<br /><br />" . sprintf($lang['Click_return_personal_gallery'], "<a href=\"" . append_sid("album_personal.$phpEx?user_id=$user_id") . "\">", "</a>");
	}

	$message .= "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}

?>
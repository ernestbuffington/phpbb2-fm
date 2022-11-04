<?php
/** 
*
* @package phpBB2
* @version $Id: album_rate.php,v 2.0.3 2003/02/28 14:33:40 ngoctu Exp $
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
if( $album_config['rate'] == 0 )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


// ------------------------------------
// Check the request
// ------------------------------------
if( isset($HTTP_GET_VARS['pic_id']) )
{
	$pic_id = intval($HTTP_GET_VARS['pic_id']);
}
else if( isset($HTTP_POST_VARS['pic_id']) )
{
	$pic_id = intval($HTTP_POST_VARS['pic_id']);
}
else
{
	message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


// ------------------------------------
// Get this pic info
// ------------------------------------
$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, AVG(r.rate_point) AS rating
	FROM ". ALBUM_TABLE ." AS p
		LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
		LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
	WHERE pic_id = '$pic_id'
	GROUP BY p.pic_id";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['pic_cat_id'];
$user_id = $thispic['pic_user_id'];

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
$album_user_access = album_user_access($cat_id, $thiscat, 0, 0, 1, 0, 0, 0); // RATE

if ($album_user_access['rate'] == 0)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("login.$phpEx?redirect=album_rate.$phpEx?pic_id=$pic_id"));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}

// ------------------------------------
// Additional Check: if this user already rated
// ------------------------------------

if( $userdata['session_logged_in'] )
{
	$sql = "SELECT *
		FROM ". ALBUM_RATE_TABLE ."
		WHERE rate_pic_id = '$pic_id'
			AND rate_user_id = '". $userdata['user_id'] ."'
		LIMIT 1";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not query rating information', '', __LINE__, __FILE__, $sql);
	}

	if ($db->sql_numrows($result) > 0)
	{
		$already_rated = TRUE;
	}
	else
	{
		$already_rated = FALSE;
	}
}

/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/

if( !isset($HTTP_POST_VARS['rate']) )
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                Rate Screen
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	// --------------------------------
	// Rate Scale
	// --------------------------------
	if (!$already_rated)
	{
		for ($i = 0; $i < $album_config['rate_scale']; $i++)
		{
			$template->assign_block_vars('rate_row', array(
				'POINT' => ($i + 1))
			);
		}
	}

	//
	// Start output of page
	//
	$page_title = $lang['Album'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'album_rate_body.tpl')
	);

	if( ($thispic['pic_user_id'] == ALBUM_GUEST) || ($thispic['username'] == '') )
	{
		$poster = ($thispic['pic_username'] == '') ? $lang['Guest'] : $thispic['pic_username'];
	}
	else
	{
		$poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $thispic['user_id']) .'">'. $thispic['username'] .'</a>';
	}

	$template->assign_vars(array(
		'CAT_TITLE' => $thiscat['cat_title'],
		'U_VIEW_CAT' => ($cat_id != PERSONAL_GALLERY) ? append_sid("album_cat.$phpEx?cat_id=$cat_id") : append_sid("album_personal.$phpEx?user_id=$user_id"),

		'U_THUMBNAIL' => append_sid("album_thumbnail.$phpEx?pic_id=$pic_id"),
		'U_PIC' => ($album_config['fullpic_popup']) ? append_sid("album_pic.$phpEx?pic_id=$pic_id") : append_sid("album_page.$phpEx?pic_id=$pic_id"),

		'PIC_TITLE' => $thispic['pic_title'],
		'PIC_DESC' => nl2br($thispic['pic_desc']),

		'POSTER' => $poster,

		'PIC_TIME' => create_date($board_config['default_dateformat'], $thispic['pic_time'], $board_config['board_timezone']),
		'PIC_VIEW' => $thispic['pic_view_count'],
		'PIC_RATING' => ($thispic['rating'] != 0) ? round($thispic['rating'], 2) : $lang['Not_rated'],

		'S_RATE_MSG' => ($already_rated) ? $lang['Already_rated'] : $lang['Rating'],

		'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',

		'L_RATING' => $lang['Rating'],
		'L_PIC_TITLE' => $lang['Pic_Title'],
		'L_PIC_DESC' => $lang['Pic_Desc'],
		'L_POSTER' => $lang['Poster'],
		'L_POSTED' => $lang['Posted'],
		'L_VIEW' => $lang['View'],
		'L_CURRENT_RATING' => $lang['Current_Rating'],
		'L_PLEASE_RATE_IT' => $lang['Please_Rate_It'],

		'L_SUBMIT' => $lang['Submit'],

		'S_ALBUM_ACTION' => append_sid("album_rate.$phpEx?pic_id=$pic_id"))
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
               Rated Message
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	// -------------------------------
	// Get the submited point
	// -------------------------------
	$rate_point = intval($HTTP_POST_VARS['rate']);

	if( ($rate_point <= 0) or ($rate_point > $album_config['rate_scale']) )
	{
		message_die(GENERAL_MESSAGE, 'Bad submited value.<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}

	$rate_user_id = $userdata['user_id'];
	$rate_user_ip = $userdata['session_ip'];

	// --------------------------------
	// Check if this user already rated
	// --------------------------------
	if ($already_rated)
	{
		message_die(GENERAL_MESSAGE, $lang['Already_rated'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}

	// --------------------------------
	// Insert into the DB
	// --------------------------------
	$sql = "INSERT INTO ". ALBUM_RATE_TABLE ." (rate_pic_id, rate_user_id, rate_user_ip, rate_point)
		VALUES ('$pic_id', '$rate_user_id', '$rate_user_ip', '$rate_point')";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not insert new rating', '', __LINE__, __FILE__, $sql);
	}


	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------
	$message = $lang['Album_rate_successfully'];

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

	$message .= "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}

?>
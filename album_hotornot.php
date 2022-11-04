<?php
/** 
*
* @package phpBB2
* @version $Id: album_hotornot.php,v 1.5 2003/03/15 10:16:56 CLowN Exp $
* @copyright (c) 2004 Volodymyr
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
// Check the request
// ------------------------------------
if ( isset($HTTP_POST_VARS['hon_rating']) )
{
	$rate_point = intval($HTTP_POST_VARS['hon_rating']);
}
else if ( isset($HTTP_GET_VARS['hon_rating']) )
{
	$rate_point = intval($HTTP_GET_VARS['hon_rating']);
}
else
{
	$rate_point = 0;
}

// ------------------------------------
// if user havent rated a picture, show page, else update database
// ------------------------------------
if ($rate_point < 1 || $rate_point > 10)
{
	// ------------------------------------
	// get a random pic from album
	// ------------------------------------ 
	if ($album_config['hon_rate_where'] == '')
	{
		$sql = "SELECT pic_id 
			FROM " . ALBUM_TABLE . " 
			ORDER BY RAND() 
			LIMIT 1";
	}
	else
	{
		$sql = "SELECT pic_id 
			FROM " . ALBUM_TABLE . " 
			WHERE pic_cat_id IN (" . $album_config['hon_rate_where'] . ") 
			ORDER BY RAND() 
			LIMIT 1";
	}
	        
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
	}
	$pic_id_temp = $db->sql_fetchrow($result);
	$pic_id = $pic_id_temp['pic_id'];


	// ------------------------------------
	// Get this pic info
	// ------------------------------------
	$rating_from = ($album_config['hon_rate_sep'] == 1) ? 'AVG(r.rate_hon_point) AS rating' : 'AVG(r.rate_point) AS rating';

	$sql = "SELECT p.*, u.user_id, u.username, r.rate_pic_id, " . $rating_from . ", COUNT(DISTINCT c.comment_id) AS comments
		FROM ". ALBUM_TABLE ." AS p
			LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
			LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
			LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
		WHERE pic_id = '$pic_id'
		GROUP BY p.pic_id";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
	}
	$thispic = $db->sql_fetchrow($result);

	$cat_id = $thispic['pic_cat_id'];
	$user_id = $thispic['pic_user_id'];

	if( empty($thispic) || !file_exists(ALBUM_UPLOAD_PATH . $pic_filename) )
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
	if ($album_config['hon_rate_users'] == 0)
	{
		$album_user_access = album_user_access($cat_id, $thiscat, 1, 0, 0, 0, 0, 0); // VIEW

		if ($album_user_access['view'] == 0)
		{
			if (!$userdata['session_logged_in'])
			{
				redirect(append_sid("login.$phpEx?redirect=album_hotornot.$phpEx"));
			}
			else
			{
				message_die(GENERAL_ERROR, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}
		}
	}

	// ------------------------------------
	// Check Pic Approval
	// ------------------------------------
	if ($userdata['user_level'] != ADMIN)
	{
		if( ($thiscat['cat_approval'] == ADMIN) || (($thiscat['cat_approval'] == MOD) && !$album_user_access['moderator']) )
		{
			if ($thispic['pic_approval'] != 1)
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

	//
	// Start output of page
	//
	$page_title = $lang['Album'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'album_hon.tpl')
	);

	if( ($thispic['pic_user_id'] == ALBUM_GUEST) || ($thispic['username'] == '') )
	{
		$poster = ($thispic['pic_username'] == '') ? $lang['Guest'] : $thispic['pic_username'];
	}
	else
	{
		$poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $thispic['user_id']) .'">'. $thispic['username'] .'</a>';
	}

	// Decide how user wants to show their rating
	$image_rating = ImageRating($thispic['rating']);
		
	// Hot or not rating
	if ( CanRated($pic_id, $userdata['user_id']))
	{
		$template->assign_block_vars('hon_rating', array());	
			
		for ($i = 0; $i < $album_config['rate_scale']; $i++)
		{   
			$template->assign_block_vars('hon_rating.hon_row', array(
				'VALUE' => ($i + 1))
			);
		}
	}
	else
	{
		$template->assign_block_vars('hon_rating_cant', array());
	}
	
	$template->assign_vars(array(
		'CAT_TITLE' => $thiscat['cat_title'],
		'U_VIEW_CAT' => ($cat_id != PERSONAL_GALLERY) ? append_sid("album_cat.$phpEx?cat_id=$cat_id") : append_sid("album_personal.$phpEx?user_id=$user_id"),
		'U_PIC' => append_sid("album_pic.$phpEx?pic_id=$pic_id"),
		'U_COMMENT' => append_sid("album_showpage.$phpEx?pic_id=$pic_id"),
		'S_HOTORNOT' => append_sid("album_hotornot.$phpEx"),

		'PIC_TITLE' => $thispic['pic_title'],
		'PIC_DESC' => nl2br($thispic['pic_desc']),
		'POSTER' => $poster,
		'PIC_TIME' => create_date($board_config['default_dateformat'], $thispic['pic_time'], $board_config['board_timezone']),
		'PIC_VIEW' => $thispic['pic_view_count'],
		'PIC_RATING' => $image_rating,
		'PIC_COMMENTS' => $thispic['comments'],
		'PICTURE_ID' => $pic_id,

		'L_RATING' => $lang['Rating'],
		'L_PIC_TITLE' => $lang['Pic_Title'] . $album_config['clown_rateType'],
		'L_PIC_DESC' => $lang['Pic_Desc'],
		'L_POSTER' => $lang['Poster'],
		'L_POSTED' => $lang['Posted'],
		'L_VIEW' => $lang['View'],
		'L_COMMENTS' => $lang['Comments'])
	);

	if ($album_config['rate'])
	{
		$template->assign_block_vars('rate_switch', array());
	}

	if ($album_config['comment'])
	{
		$template->assign_block_vars('comment_switch', array());
	}

	//
	// Generate the page
	//
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else
{
	$rate_user_id = $userdata['user_id'];
	$rate_user_ip = $userdata['session_ip'];
	$pic_id = ( isset($HTTP_POST_VARS['pic_id']) || isset($HTTP_GET_VARS['pic_id']) ) ? (isset($HTTP_POST_VARS['pic_id'])) ? intval($HTTP_POST_VARS['pic_id']) : intval($HTTP_GET_VARS['pic_id']) : 0;
		
	if ($album_config['hon_rate_sep'] == 1)
	{
		$sql = "INSERT INTO ". ALBUM_RATE_TABLE ." (rate_pic_id, rate_user_id, rate_user_ip, rate_hon_point)
			VALUES ('$pic_id', '$rate_user_id', '$rate_user_ip', '$rate_point')";
	}
	else
	{
		$sql = "INSERT INTO ". ALBUM_RATE_TABLE ." (rate_pic_id, rate_user_id, rate_user_ip, rate_point)
			VALUES ('$pic_id', '$rate_user_id', '$rate_user_ip', '$rate_point')";
	}
	
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not insert new rating', '', __LINE__, __FILE__, $sql);
	}
	
	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("album_hotornot.$phpEx") . '">')
	);
	
	$message = $lang['Album_rate_successfully'] . "<br /><br />" . sprintf($lang['Click_rate_more_pics'], "<a href=\"" . append_sid("album_hotornot.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");
	
	message_die(GENERAL_MESSAGE, $message);
}

?>
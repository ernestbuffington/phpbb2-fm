<?php
/** 
*
* @package phpBB2
* @version $Id: album_upload.php,v 2.1.2 2003/03/13 19:46:00 ngoctu Exp $
* @copyright (c) 2003 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
$album_root_path = $phpbb_root_path . 'mods/album/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/functions_validate.'.$phpEx);

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

/*
+----------------------------------------------------------
| Common Check
+----------------------------------------------------------
*/

// ------------------------------------
// Check the request
// for this Upload script, we prefer POST to GET
// ------------------------------------
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

// ------------------------------------
// Get the current Category Info
// ------------------------------------
if ($cat_id != PERSONAL_GALLERY)
{
	$sql = "SELECT c.*, COUNT(p.pic_id) AS count
		FROM ". ALBUM_CAT_TABLE ." AS c
			LEFT JOIN ". ALBUM_TABLE ." AS p ON c.cat_id = p.pic_cat_id
		WHERE c.cat_id = '$cat_id'
		GROUP BY c.cat_id
		LIMIT 1";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query category information', '', __LINE__, __FILE__, $sql);
	}

	$thiscat = $db->sql_fetchrow($result);
}
else
{
	$thiscat = init_personal_gallery_cat($user_data['user_id']);
}

$current_pics = $thiscat['count'];

if (empty($thiscat))
{
	message_die(GENERAL_MESSAGE, $lang['Category_not_exist']. '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}

// ------------------------------------
// Check the permissions
// ------------------------------------
$album_user_access = album_user_access($cat_id, $thiscat, 0, 1, 0, 0, 0, 0); // UPLOAD

if ($album_user_access['upload'] == 0)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("login.$phpEx?redirect=album_upload.$phpEx?cat_id=$cat_id"));
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorised']. '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}

/*
+----------------------------------------------------------
| Upload Quota Check
+----------------------------------------------------------
*/
if ($cat_id != PERSONAL_GALLERY)
{
	// ------------------------------------
	// Check Album Configuration Quota
	// ------------------------------------
	if ($album_config['max_pics'] >= 0)
	{
		//
		// $current_pics was set at "Get the current Category Info"
		//
		if( $current_pics >= $album_config['max_pics'] )
		{
			message_die(GENERAL_MESSAGE, $lang['Album_reached_quota'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}
	}


	// ------------------------------------
	// Check User Limit
	// ------------------------------------
	$check_user_limit = FALSE;
	if( ($userdata['user_level'] != ADMIN) && ($userdata['session_logged_in']) )
	{
		if ($album_user_access['moderator'])
		{
			if ($album_config['mod_pics_limit'] >= 0)
			{
				$check_user_limit = 'mod_pics_limit';
			}
		}
		else
		{
			if ($album_config['user_pics_limit'] >= 0)
			{
				$check_user_limit = 'user_pics_limit';
			}
		}
	}

	// Do the check here
	if ($check_user_limit != FALSE)
	{
		$sql = "SELECT COUNT(pic_id) AS count
			FROM ". ALBUM_TABLE ."
			WHERE pic_user_id = '". $userdata['user_id'] ."'
				AND pic_cat_id = '$cat_id'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not count your pic', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		$own_pics = $row['count'];

		if( $own_pics >= $album_config[$check_user_limit] )
		{
			message_die(GENERAL_MESSAGE, $lang['User_reached_pics_quota'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}
	}
}
else
{
	if( ($current_pics >= $album_config['personal_gallery_limit']) && ($album_config['personal_gallery_limit'] >= 0) )
	{
		message_die(GENERAL_MESSAGE, $lang['Album_reached_quota'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}

if ( $userdata['user_posts'] > 1 && $userdata['user_posts'] < $album_config['posts'] )
{
	message_die(GENERAL_MESSAGE, sprintf($lang['album_few_posts'], $album_config['posts'], $userdata['user_posts']));
}
else if ( $userdata['user_posts'] == 1 && $album_config['posts'] > 1 )
{
	message_die(GENERAL_MESSAGE, sprintf($lang['album_one_post'], $album_config['posts']) . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}
else if ( $userdata['user_posts'] == 0 && $album_config['posts'] )
{
	message_die(GENERAL_MESSAGE, sprintf($lang['album_zero_post'], $album_config['posts']) . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/
if( !isset($HTTP_POST_VARS['pic_title']) ) // is it not submitted?
{
	// --------------------------------
	// Build categories select
	// --------------------------------
	$sql = "SELECT *
		FROM " . ALBUM_CAT_TABLE ."
		ORDER BY cat_order ASC";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
	}

	$catrows = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$thiscat_access = album_user_access($row['cat_id'], $row, 0, 1, 0, 0, 0, 0); // UPLOAD

		if ($thiscat_access['upload'] == 1)
		{
			$catrows[] = $row;
		}
	}
	$db->sql_freeresult($result);

	$select_cat = '<select name="cat_id">';
	if ($cat_id == PERSONAL_GALLERY)
	{
		$select_cat .= '<option value="$cat_id" selected="selected">';
		$select_cat .= sprintf($lang['Personal_Gallery_Of_User'], $userdata['username']);
		$select_cat .= '</option>';
	}

	for ($i = 0; $i < sizeof($catrows); $i++)
	{
		$select_cat .= '<option value="'. $catrows[$i]['cat_id'] .'" ';
		$select_cat .= ($cat_id == $catrows[$i]['cat_id']) ? 'selected="selected"' : '';
		$select_cat .= '>'. $catrows[$i]['cat_title'] .'</option>';
	}
	$select_cat .= '</select>';

	//
	// Start output of page
	//
	$page_title = $lang['Album'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'album_upload_body.tpl')
	);

	$template->assign_vars(array(
		'U_VIEW_CAT' => ($cat_id != PERSONAL_GALLERY) ? append_sid("album_cat.$phpEx?cat_id=$cat_id") : append_sid("album_personal.$phpEx"),
		'CAT_TITLE' => $thiscat['cat_title'],

		'L_UPLOAD_PIC' => $lang['Upload_Pic'],

		'L_USERNAME' => $lang['Username'],
		'L_PIC_TITLE' => $lang['Pic_Title'],

		'L_PIC_DESC' => $lang['Pic_Desc'],
		'L_PLAIN_TEXT_ONLY' => $lang['Plain_text_only'],
		'L_MAX_LENGTH' => $lang['Max_length'],
		'S_PIC_DESC_MAX_LENGTH' => $album_config['desc_length'],

		'L_UPLOAD_PIC_FROM_MACHINE' => $lang['Upload_pic_from_machine'],
		'L_UPLOAD_TO_CATEGORY' => $lang['Upload_to_Category'],

		'SELECT_CAT' => $select_cat,

		'L_MAX_FILESIZE' => $lang['Max_file_size'],
		'S_MAX_FILESIZE' => $album_config['max_file_size'],

		'L_MAX_WIDTH' => $lang['Max_width'],
		'L_MAX_HEIGHT' => $lang['Max_height'],

		'S_MAX_WIDTH' => $album_config['max_width'],
		'S_MAX_HEIGHT' => $album_config['max_height'],

		'L_ALLOWED_JPG' => $lang['JPG_allowed'],
		'L_ALLOWED_PNG' => $lang['PNG_allowed'],
		'L_ALLOWED_GIF' => $lang['GIF_allowed'],

		'S_JPG' => ($album_config['jpg_allowed'] == 1) ? $lang['Yes'] : $lang['No'],
		'S_PNG' => ($album_config['png_allowed'] == 1) ? $lang['Yes'] : $lang['No'],
		'S_GIF' => ($album_config['gif_allowed'] == 1) ? $lang['Yes'] : $lang['No'],

		'L_UPLOAD_NO_TITLE' => $lang['Upload_no_title'],
		'L_UPLOAD_NO_FILE' => $lang['Upload_no_file'],
		'L_DESC_TOO_LONG' => $lang['Desc_too_long'],

		// Manual Thumbnail
		'L_UPLOAD_THUMBNAIL' => $lang['Upload_thumbnail'],
		'L_UPLOAD_THUMBNAIL_EXPLAIN' => $lang['Upload_thumbnail_explain'],
		'L_THUMBNAIL_SIZE' => $lang['Thumbnail_size'],
		'S_THUMBNAIL_SIZE' => $album_config['thumbnail_size'],

		'S_ALBUM_ACTION' => append_sid("album_upload.$phpEx?cat_id=$cat_id"))
	);

	if ($album_config['gd_version'] == 0)
	{
		$template->assign_block_vars('switch_manual_thumbnail', array());
	}

	//
	// Generate the page
	//
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else
{
	// --------------------------------
	// Check posted info
	// --------------------------------
	$pic_title = str_replace("\'", "''", htmlspecialchars(trim($HTTP_POST_VARS['pic_title'])));

	$pic_desc = str_replace("\'", "''", htmlspecialchars(substr(trim($HTTP_POST_VARS['pic_desc']), 0, $album_config['desc_length'])));

	$pic_username = (!$userdata['session_logged_in']) ? substr(str_replace("\'", "''", htmlspecialchars(trim($HTTP_POST_VARS['pic_username']))), 0, 32) : str_replace("'", "''", $userdata['username']);

	if( empty($pic_title) )
	{
		message_die(GENERAL_MESSAGE, $lang['Missed_pic_title'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}

	if( !isset($HTTP_POST_FILES['pic_file']) )
	{
		message_die(GENERAL_MESSAGE, 'Bad Upload.<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}

	// --------------------------------
	// Check username for guest posting
	// --------------------------------
	if (!$userdata['session_logged_in'])
	{
		if ($pic_username != '')
		{
			$result = validate_username($pic_username);
			if ( $result['error'] )
			{
				message_die(GENERAL_MESSAGE, $result['error_msg'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}
		}
	}	

	// --------------------------------
	// Get File Upload Info
	// --------------------------------
	$filetype = $HTTP_POST_FILES['pic_file']['type'];
	$filesize = $HTTP_POST_FILES['pic_file']['size'];
	$filetmp = $HTTP_POST_FILES['pic_file']['tmp_name'];
	
	if ($album_config['gd_version'] == 0)
	{
		$thumbtype = $HTTP_POST_FILES['pic_thumbnail']['type'];
		$thumbsize = $HTTP_POST_FILES['pic_thumbnail']['size'];
		$thumbtmp = $HTTP_POST_FILES['pic_thumbnail']['tmp_name'];
	}

	// --------------------------------
	// Prepare variables
	// --------------------------------
	$pic_time = time();
	$pic_user_id = $userdata['user_id'];
	$pic_user_ip = $userdata['session_ip'];
	
	// --------------------------------
	// Check file size
	// --------------------------------
	if( ($filesize == 0) or ($filesize > $album_config['max_file_size']) )
	{
		message_die(GENERAL_MESSAGE, $lang['Bad_upload_file_size'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
	
	if ($album_config['gd_version'] == 0)
	{
		if( ($thumbsize == 0) or ($thumbsize > $album_config['max_file_size']) )
		{
			message_die(GENERAL_MESSAGE, $lang['Bad_upload_file_size'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}
	}
	
	// --------------------------------
	// Check file type
	// --------------------------------
	switch ($filetype)
	{
		case 'image/jpeg':
		case 'image/jpg':
		case 'image/pjpeg':
			if ($album_config['jpg_allowed'] == 0)
			{
				message_die(GENERAL_MESSAGE, $lang['Not_allowed_file_type'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}
			$pic_filetype = '.jpg';
			break;
		case 'image/png':
		case 'image/x-png':
			if ($album_config['png_allowed'] == 0)
			{
				message_die(GENERAL_MESSAGE, $lang['Not_allowed_file_type'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}
			$pic_filetype = '.png';
			break;
		case 'image/gif':
			if ($album_config['gif_allowed'] == 0)
			{
				message_die(GENERAL_MESSAGE, $lang['Not_allowed_file_type'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}
			$pic_filetype = '.gif';
			break;
		default:
			message_die(GENERAL_MESSAGE, $lang['Not_allowed_file_type'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			break;
	}
	
	if ($album_config['gd_version'] == 0)
	{
		if ($filetype != $thumbtype)
		{
			message_die(GENERAL_MESSAGE, $lang['Filetype_and_thumbtype_do_not_match'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}
	}
	
	// --------------------------------
	// Generate filename
	// --------------------------------
	srand((double)microtime()*1000000);	// for older than version 4.2.0 of PHP

	do
	{
		$pic_filename = md5(uniqid(rand())) . $pic_filetype;
	}
	while( file_exists(ALBUM_UPLOAD_PATH . $pic_filename) );
	
	if ($album_config['gd_version'] == 0)
	{
		$pic_thumbnail = $pic_filename;
	}
	
	// --------------------------------
	// Move this file to upload directory
	// --------------------------------
	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

	if ( @$ini_val('open_basedir') != '' )
	{
		if ( @phpversion() < '4.0.3' )
		{
			message_die(GENERAL_MESSAGE, 'open_basedir is set and your PHP version does not allow move_uploaded_file<br /><br />Please contact your server admin', '', __LINE__, __FILE__);
		}
		$move_file = 'move_uploaded_file';
	}
	else
	{
		$move_file = 'copy';
	}
	
	$move_file($filetmp, ALBUM_UPLOAD_PATH . $pic_filename);

	@chmod(ALBUM_UPLOAD_PATH . $pic_filename, 0777);
	
	if ($album_config['gd_version'] == 0)
	{
		$move_file($thumbtmp, ALBUM_CACHE_PATH . $pic_thumbnail);
		@chmod(ALBUM_CACHE_PATH . $pic_thumbnail, 0777);
	}

	// --------------------------------
	// Well, it's an image. Check its image size
	// --------------------------------
	$pic_size = getimagesize(ALBUM_UPLOAD_PATH . $pic_filename);

	$pic_width = $pic_size[0];
	$pic_height = $pic_size[1];
	
	if ( ($pic_width > $album_config['max_width']) || ($pic_height > $album_config['max_height']) )
	{
		@unlink(ALBUM_UPLOAD_PATH . $pic_filename);
		if ($album_config['gd_version'] == 0)
		{
			@unlink(ALBUM_CACHE_PATH . $pic_thumbnail);
		}
	
		message_die(GENERAL_MESSAGE, $lang['Upload_image_size_too_big'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}

	if ($album_config['gd_version'] == 0)
	{
		$thumb_size = getimagesize(ALBUM_CACHE_PATH . $pic_thumbnail);
	
		$thumb_width = $thumb_size[0];
		$thumb_height = $thumb_size[1];
	
		if ( ($thumb_width > $album_config['thumbnail_size']) or ($thumb_height > $album_config['thumbnail_size']) )
		{
			@unlink(ALBUM_UPLOAD_PATH . $pic_filename);	
			@unlink(ALBUM_CACHE_PATH . $pic_thumbnail);
	
			message_die(GENERAL_MESSAGE, $lang['Upload_thumbnail_size_too_big'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}
	}

	// --------------------------------
	// This image is okay, we can cache its thumbnail now
	// --------------------------------
	if( ($album_config['thumbnail_cache'] == 1) && ($album_config['gd_version'] > 0) )
	{
		$gd_errored = FALSE;
	
		switch ($pic_filetype)
		{
			case '.jpg':
				$read_function = 'imagecreatefromjpeg';
				break;
			case '.png':
				$read_function = 'imagecreatefrompng';
				break;
			case '.gif':
				$read_function = 'imagecreatefromgif';
				break;
		}	

		$src = @$read_function(ALBUM_UPLOAD_PATH  . $pic_filename);
		if (!$src)
		{
			$gd_errored = TRUE;
			$pic_thumbnail = '';
		}
		else if( ($pic_width > $album_config['thumbnail_size']) || ($pic_height > $album_config['thumbnail_size']) )
		{
			// Resize it
			if ($pic_width > $pic_height)
			{
				$thumbnail_width = $album_config['thumbnail_size'];
				$thumbnail_height = $album_config['thumbnail_size'] * ($pic_height/$pic_width);
			}
			else
			{
				$thumbnail_height = $album_config['thumbnail_size'];
				$thumbnail_width = $album_config['thumbnail_size'] * ($pic_width/$pic_height);
			}	
	
			$thumbnail = ($album_config['gd_version'] == 1) ? @imagecreate($thumbnail_width, $thumbnail_height) : @imagecreatetruecolor($thumbnail_width, $thumbnail_height);

			$resize_function = ($album_config['gd_version'] == 1) ? 'imagecopyresized' : 'imagecopyresampled';

			@$resize_function($thumbnail, $src, 0, 0, 0, 0, $thumbnail_width, $thumbnail_height, $pic_width, $pic_height);
		}
		else
		{
			$thumbnail = $src;
		}

		if (!$gd_errored)
		{
			$pic_thumbnail = $pic_filename;

			// Write to disk
			switch ($pic_filetype)
			{
				case '.jpg':
					@imagejpeg($thumbnail, ALBUM_CACHE_PATH . $pic_thumbnail, $album_config['thumbnail_quality']);
					break;
				case '.png':
					@imagepng($thumbnail, ALBUM_CACHE_PATH . $pic_thumbnail);
					break;
				case '.gif':
					@imagegif($thumbnail, ALBUM_CACHE_PATH . $pic_thumbnail);
					break;
			}
			@chmod(ALBUM_CACHE_PATH . $pic_thumbnail, 0777);
		} // End IF $gd_errored
	} // End Thumbnail Cache
	else if ($album_config['gd_version'] > 0)
	{
		$pic_thumbnail = '';
	}

	// --------------------------------
	// Check Pic Approval
	// --------------------------------	
	$pic_approval = ($thiscat['cat_approval'] == 0) ? 1 : 0;

	// --------------------------------
	// Insert into DB
	// --------------------------------
	$sql = "INSERT INTO ". ALBUM_TABLE ." (pic_filename, pic_thumbnail, pic_title, pic_desc, pic_user_id, pic_user_ip, pic_username, pic_time, pic_cat_id, pic_approval)
		VALUES ('$pic_filename', '$pic_thumbnail', '$pic_title', '$pic_desc', '$pic_user_id', '$pic_user_ip', '$pic_username', '$pic_time', '$cat_id', '$pic_approval')";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not insert new entry', '', __LINE__, __FILE__, $sql);
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------
	if ($thiscat['cat_approval'] == 0)
	{
		$message = $lang['Album_upload_successful'];
	}
	else
	{
		$message = $lang['Album_upload_need_approval'];
	}

	if ($cat_id != PERSONAL_GALLERY)
	{
		if ($thiscat['cat_approval'] == 0)
		{
			$template->assign_vars(array(	
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("album_cat.$phpEx?cat_id=$cat_id") . '">')
			);
		}
		$message .= "<br /><br />" . sprintf($lang['Click_return_category'], "<a href=\"" . append_sid("album_cat.$phpEx?cat_id=$cat_id") . "\">", "</a>");
	}
	else
	{
		if ($thiscat['cat_approval'] == 0)
		{
			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("album_personal.$phpEx") . '">')
			);
		}
		$message .= "<br /><br />" . sprintf($lang['Click_return_personal_gallery'], "<a href=\"" . append_sid("album_personal.$phpEx") . "\">", "</a>");
	}

	$message .= "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}

?>
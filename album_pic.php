<?php
/** 
*
* @package phpBB2
* @version $Id: album_pic.php,v 2.0.5 2003/02/28 14:33:12 ngoctu Exp $
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


// 
// Function for watermark 
// 
function mergePics($sourcefile, $insertfile, $pos = 0, $transition = 50, $filetype) 
{
	$insertfile_id = imageCreateFromPNG($insertfile);

	switch( $filetype ) 
   	{ 
    	case '.jpg': 
    		$sourcefile_id = imageCreateFromJPEG($sourcefile); 
        break; 
      	case '.png': 
        	$sourcefile_id = imageCreateFromPNG($sourcefile); 
        break; 
     	default: 
       	break; 
   	} 

   	// Get the size of both pics 
   	$sourcefile_width = imageSX($sourcefile_id); 
   	$sourcefile_height = imageSY($sourcefile_id); 
   	$insertfile_width = imageSX($insertfile_id); 
   	$insertfile_height = imageSY($insertfile_id); 

   	switch( $pos ) 
   	{ 
   		case 0: // middle 
        	$dest_x = ( $sourcefile_width / 2 ) - ( $insertfile_width / 2 ); 
        	$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
        	break; 
		case 1: // top left 
        	$dest_x = 0; 
        	$dest_y = 0; 
        	break; 
		case 2: // top right 
        	$dest_x = $sourcefile_width - $insertfile_width; 
        	$dest_y = 0; 
        	break; 
		case 3: // bottom right 
    		$dest_x = $sourcefile_width - $insertfile_width; 
        	$dest_y = $sourcefile_height - $insertfile_height; 
        	break; 
		case 4: // bottom left 
    		$dest_x = 0; 
        	$dest_y = $sourcefile_height - $insertfile_height; 
        	break; 
      	case 5: // top middle 
        	$dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 ); 
        	$dest_y = 0; 
        	break; 
		case 6: // middle right 
        	$dest_x = $sourcefile_width - $insertfile_width; 
        	$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
        	break; 
		case 7: // bottom middle 
    	    $dest_x = ( ( $sourcefile_width - $insertfile_width ) / 2 ); 
    		$dest_y = $sourcefile_height - $insertfile_height; 
        	break; 
		case 8: // middle left 
    		$dest_x = 0; 
        	$dest_y = ( $sourcefile_height / 2 ) - ( $insertfile_height / 2 ); 
        	break; 
      	default: 
        	break; 
   	} 

   	// Merge the two pix 
   	imageCopyMerge($sourcefile_id, $insertfile_id, $dest_x, $dest_y, 0, 0, $insertfile_width, $insertfile_height, $transition); 

   	// Create the final image 
   	switch( $filetype ) 
   	{ 
   		case '.jpg': 
        	Imagejpeg($sourcefile_id, '', 75); 
        	break; 
      	case '.png': 
        	Imagepng($sourcefile_id); 
        	break; 
      	default: 
        	break; 
   	} 

   	ImageDestroy($sourcefile_id); 
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
$sql = "SELECT *
	FROM ". ALBUM_TABLE ."
	WHERE pic_id = '$pic_id'";
if( !$result = $db->sql_query($sql) ) 
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);
$db->sql_freeresult($result); 

$cat_id = $thispic['pic_cat_id'];
$user_id = $thispic['pic_user_id'];

$pic_filetype = substr($thispic['pic_filename'], strlen($thispic['pic_filename']) - 4, 4);
$pic_filename = $thispic['pic_filename'];
$pic_thumbnail = $thispic['pic_thumbnail'];

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
   if( !$result = $db->sql_query($sql) ) 
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
$album_user_access = album_user_access($cat_id, $thiscat, 1, 0, 0, 0, 0, 0); // VIEW
if ($album_user_access['view'] == 0)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
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

// ------------------------------------
// Check hotlink
// ------------------------------------
if( ($album_config['hotlink_prevent'] == 1) && (isset($HTTP_SERVER_VARS['HTTP_REFERER'])) )
{
	$check_referer = explode('?', $HTTP_SERVER_VARS['HTTP_REFERER']);
	$check_referer = trim($check_referer[0]);

	$good_referers = array();

	if ($album_config['hotlink_allowed'] != '')
	{
		$good_referers = explode(',', $album_config['hotlink_allowed']);
	}

	$good_referers[] = $board_config['server_name'] . $board_config['script_path'];

	$errored = TRUE;

	for ($i = 0; $i < sizeof($good_referers); $i++)
	{
		$good_referers[$i] = trim($good_referers[$i]);

		if( (strstr($check_referer, $good_referers[$i])) && ($good_referers[$i] != '') )
		{
			$errored = FALSE;
		}
	}

	if ($errored)
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}


/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/

// ------------------------------------
// Increase view counter
// ------------------------------------
$sql = "UPDATE ". ALBUM_TABLE ."
		SET pic_view_count = pic_view_count + 1
		WHERE pic_id = '$pic_id'";
if( !$result = $db->sql_query($sql) ) 
{
	message_die(GENERAL_ERROR, 'Could not update pic information', '', __LINE__, __FILE__, $sql);
}

// ------------------------------------
// Okay, now we can send image to the browser
// ------------------------------------
switch ( $pic_filetype )
{
	case '.png':
		header('Content-type: image/png');
		break;
	case '.gif':
		header('Content-type: image/gif');
		break;
	case '.jpg':
		header('Content-type: image/jpeg');
		break;
	default:
		message_die(GENERAL_MESSAGE, 'The filename data in the DB was corrupted');
		break;
}

// -------------------------------------------------------- 
// Okay, now we insert the watermark for unregistered users 
// -------------------------------------------------------- 
if( $pic_filetype != '.gif' && (!$userdata['session_logged_in'] || $userdata['user_level'] == USER) && $album_config['use_watermark'] == 1) 
{ 
  	$position  = $album_config['disp_watermark_at']; 
   	$transition = 50; 

   	$sourcefile = ALBUM_UPLOAD_PATH  . $thispic['pic_filename']; 
   	$insertfile = $phpbb_root_path  . 'images/mark.png'; 
   	mergePics($sourcefile, $insertfile, $position, $transition, $pic_filetype); 
}
else if ($pic_filetype != '.gif' && $album_config['wut_users'] == 1 && $album_config['use_watermark'] == 1)
{
   	$position  = $album_config['disp_watermark_at']; 
   	$transition = 70; 

   	$sourcefile = ALBUM_UPLOAD_PATH  . $thispic['pic_filename']; 
   	$insertfile = $phpbb_root_path  . 'images/mark.png'; 
   	mergePics($sourcefile, $insertfile, $position, $transition, $pic_filetype);
}
else 
{ 
	readfile(ALBUM_UPLOAD_PATH  . $thispic['pic_filename']);
} 

exit;

?>
<?php
/** 
*
* @package includes
* @version $Id: usercp_avatar.php,v 1.8.2.19 2005/02/21 18:37:51 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
function download_avatar($filename) 
{ 
	global $board_config, $userdata; 
	
	$avatar_file = basename($avatar_file);

	$retvar = 'NULL'; 
	$localfile = './' . $board_config['avatar_path'] . '/' . $userdata['session_user_id'] . basename($filename); 
	$fd = @fopen($filename, 'rb'); 
	if ($fd) 
	{ 
		// Read exactly the maximum defined avatar size from the remote file. 
		// If we don't find any dimension info in it afterwards, then it's not allowed anyway 
		$imgdata = fread($fd, $board_config['avatar_filesize']); 
		$fl = @fopen($localfile, 'wb'); 
		if ($fl) 
		{ 
			@fwrite($fl, $imgdata); 
			@fclose($fl); 
			if ($fp != -1) 
			{
				$retvar = $localfile; 
			} 
		}
		fclose($fd); 
	} 
	return $retvar; 
} 

function check_image_type(&$type, &$error, &$error_msg)
{
	global $lang;

	switch( $type )
	{
		case 'jpeg':
		case 'pjpeg':
		case 'jpg':
			return '.jpg';
			break;
		case 'gif':
			return '.gif';
			break;
		case 'png':
		case 'x-png':
			return '.png';
			break;
		default:
			$error = true;
			$error_msg = (!empty($error_msg)) ? $error_msg . '<br />' . $lang['Avatar_filetype'] : $lang['Avatar_filetype'];
			break;
	}

	return false;
}

function user_avatar_delete($avatar_type, $avatar_file)
{
	global $board_config, $userdata;

	$avatar_file = basename($avatar_file);
	if ( $avatar_type == USER_AVATAR_UPLOAD && $avatar_file != '' )
	{
		if ( @file_exists(@phpbb_realpath('./' . $board_config['avatar_path'] . '/' . $avatar_file)) )
		{
//			@unlink('./' . $board_config['avatar_path'] . '/' . $avatar_file);
		}
	}

	return ", user_avatar = '', user_avatar_type = " . USER_AVATAR_NONE;
}

function user_avatar_gallery($mode, &$error, &$error_msg, $avatar_filename, $avatar_category)
{
	global $board_config;

	$avatar_filename = phpbb_ltrim(basename($avatar_filename), "'");
	$avatar_category = phpbb_ltrim(basename($avatar_category), "'");
	
	if(!preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $avatar_filename))
	{
		return '';
	}

	if ($avatar_filename == "" || $avatar_category == "")
	{
		return '';
	} 

	if ( file_exists(@phpbb_realpath($board_config['avatar_gallery_path'] . '/' . $avatar_category . '/' . $avatar_filename)) && ($mode == 'editprofile') )
	{
		$return = ", user_avatar = '" . str_replace("\'", "''", $avatar_category . '/' . $avatar_filename) . "', user_avatar_type = " . USER_AVATAR_GALLERY;
	}
	else
	{
		$return = '';
	}
	return $return;
}

function user_avatar_generator($mode, &$error, &$error_msg, $avatar_filename)
{
	global $board_config;

	$new_filename = uniqid(rand()) . '.gif';

	@copy($avatar_filename, './' . $board_config['avatar_path'] . "/$new_filename");
	@unlink($avatar_filename);

	$avatar_sql = ( $mode == 'editprofile' ) ? ", user_avatar = '$new_filename', user_avatar_type = " . USER_AVATAR_UPLOAD : "'$new_filename', " . USER_AVATAR_UPLOAD;

	return $avatar_sql;
}

function user_avatar_url($mode, &$error, &$error_msg, $avatar_filename)
{
	global $board_config, $lang; 

	if ( !preg_match('#^(http)|(ftp):\/\/#i', $avatar_filename) )
	{
		$avatar_filename = 'http://' . $avatar_filename;
	}
	$avatar_filename = substr($avatar_filename, 0, 100);

	if ( !preg_match("#^((ht|f)tp://)([^ \?&=\#\"\n\r\t<]*?(\.(jpg|jpeg|gif|png))$)#is", $avatar_filename) )
	{
		$error = true;
		$error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $lang['Wrong_remote_avatar_format'] : $lang['Wrong_remote_avatar_format'];
		return;
	}

	// Get remote avatar size
	// Download the file 
	$tmp_filename = download_avatar($avatar_filename); 

	// Get avatar size, check the values and invalidate them, if necessary 
	if ($tmp_filename != 'NULL') 
	{
		list($width, $height) = getimagesize($tmp_filename); 
	}
	if (!isset($width) || $width == 0) 
	{
		$width = 2 * $board_config['avatar_max_width']; 
	}
	if (!isset($height) || $height == 0) 
	{
		$height = 2 * $board_config['avatar_max_height']; 
	}
	
	// Delete the tempfile 
	@unlink($tmp_filename); 

	// Now compare the image dimension with phpBB config and print error message, if necessary
	if ( $width > $board_config['avatar_max_width'] || $height > $board_config['avatar_max_height'] )
	{ 
	      $l_avatar_size = sprintf($lang['Avatar_imagesize'], $board_config['avatar_max_width'], $board_config['avatar_max_height']); 
	      $error = true; 
	      $error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size; 
	      return; 
	} 

	return ( $mode == 'editprofile' ) ? ", user_avatar = '" . str_replace("\'", "''", $avatar_filename) . "', user_avatar_type = " . USER_AVATAR_REMOTE : '';

}

function user_avatar_upload($mode, $avatar_mode, &$current_avatar, &$current_type, &$error, &$error_msg, $avatar_filename, $avatar_realname, $avatar_filesize, $avatar_filetype)
{
	global $board_config, $db, $lang, $userdata;

	$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

	$width = $height = 0;
	$type = '';
	if ( $avatar_mode == 'remote' && preg_match('/^(http:\/\/)?([\w\-\.]+)\:?([0-9]*)\/([^ \?&=\#\"\n\r\t<]*?(\.(jpg|jpeg|gif|png)))$/', $avatar_filename, $url_ary) )
	{
		if ( empty($url_ary[4]) )
		{
			$error = true;
			$error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $lang['Incomplete_URL'] : $lang['Incomplete_URL'];
			return;
		}

		$base_get = '/' . $url_ary[4];
		$port = ( !empty($url_ary[3]) ) ? $url_ary[3] : 80;

		if ( !($fsock = @fsockopen($url_ary[2], $port, $errno, $errstr)) )
		{
			$error = true;
			$error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $lang['No_connection_URL'] : $lang['No_connection_URL'];
			return;
		}

		@fputs($fsock, "GET $base_get HTTP/1.1\r\n");
		@fputs($fsock, "HOST: " . $url_ary[2] . "\r\n");
		@fputs($fsock, "Connection: close\r\n\r\n");

		unset($avatar_data);
		while( !@feof($fsock) )
		{
			$avatar_data .= @fread($fsock, $board_config['avatar_filesize']);
		}
		@fclose($fsock);

		if (!preg_match('#Content-Length\: ([0-9]+)[^ /][\s]+#i', $avatar_data, $file_data1) || !preg_match('#Content-Type\: image/[x\-]*([a-z]+)[\s]+#i', $avatar_data, $file_data2))
		{
			$error = true;
			$error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $lang['File_no_data'] : $lang['File_no_data'];
			return;
		}

		$avatar_filesize = $file_data1[1]; 
		$avatar_filetype = $file_data2[1]; 

		if ( !$error && $avatar_filesize > 0 && $avatar_filesize < $board_config['avatar_filesize'] )
		{
			$avatar_data = substr($avatar_data, strlen($avatar_data) - $avatar_filesize, $avatar_filesize);

			$tmp_path = ( !@$ini_val('safe_mode') ) ? '/tmp' : './' . $board_config['avatar_path'] . '/tmp';
			$tmp_filename = tempnam($tmp_path, uniqid(rand()) . '-');

			$fptr = @fopen($tmp_filename, 'wb');
			$bytes_written = @fwrite($fptr, $avatar_data, $avatar_filesize);
			@fclose($fptr);

			if ( $bytes_written != $avatar_filesize )
			{
				@unlink($tmp_filename);
				message_die(GENERAL_ERROR, 'Could not write avatar file to local storage. Please contact the board administrator with this message', '', __LINE__, __FILE__);
			}

			list($width, $height, $type) = @getimagesize($tmp_filename);
		}
		else
		{
			$l_avatar_size = sprintf($lang['Avatar_filesize'], round($board_config['avatar_filesize'] / 1024));

			$error = true;
			$error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
		}
	}
	else if ( ( file_exists(@phpbb_realpath($avatar_filename)) ) && preg_match('/\.(jpg|jpeg|gif|png)$/i', $avatar_realname) )
	{
		if ( $avatar_filesize <= $board_config['avatar_filesize'] && $avatar_filesize > 0 )
		{
			preg_match('#image\/[x\-]*([a-z]+)#', $avatar_filetype, $avatar_filetype);
			$avatar_filetype = $avatar_filetype[1];
		}
		else
		{
			$l_avatar_size = sprintf($lang['Avatar_filesize'], round($board_config['avatar_filesize'] / 1024));

			$error = true;
			$error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
			return;
		}

		list($width, $height, $type) = @getimagesize($avatar_filename);
	}

	if ( !($imgtype = check_image_type($avatar_filetype, $error, $error_msg)) )
	{
		return;
	}

	switch ($type)
	{
		// GIF
		case 1:
			if ($imgtype != '.gif')
			{
				@unlink($tmp_filename);
				message_die(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
			break;

		// JPG, JPC, JP2, JPX, JB2
		case 2:
		case 9:
		case 10:
		case 11:
		case 12:
			if ($imgtype != '.jpg' && $imgtype != '.jpeg' && $imgtype != '.pjpeg')
			{
				@unlink($tmp_filename);
				message_die(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
			break;

		// PNG
		case 3:
			if ($imgtype != '.png' && $imgtype != '.x-png')
			{
				@unlink($tmp_filename);
				message_die(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			}
			break;

		default:
			@unlink($tmp_filename);
			message_die(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
			break;
	}

	if ( $width > 0 && $height > 0 && $width <= $board_config['avatar_max_width'] && $height <= $board_config['avatar_max_height'] )
	{
		$new_filename = uniqid(rand()) . $imgtype;

		if ( $mode == 'editprofile' && $current_type == USER_AVATAR_UPLOAD && $current_avatar != '' && !$userdata['avatar_sticky'] )
		{
			user_avatar_delete($current_type, $current_avatar);
		}

		if( $avatar_mode == 'remote' )
		{
			@copy($tmp_filename, './' . $board_config['avatar_path'] . "/$new_filename");
			@unlink($tmp_filename);
		}
		else
		{
			if ( @$ini_val('open_basedir') != '' )
			{
				if ( @phpversion() < '4.0.3' )
				{
					message_die(GENERAL_ERROR, 'open_basedir is set and your PHP version does not allow move_uploaded_file', '', __LINE__, __FILE__);
				}

				$move_file = 'move_uploaded_file';
			}
			else
			{
				$move_file = 'copy';
			}

			if (!is_uploaded_file($avatar_filename))
        	{
        		message_die(GENERAL_ERROR, 'Unable to upload file', '', __LINE__, __FILE__);
         	}
         	
		    $move_file($avatar_filename, './' . $board_config['avatar_path'] . "/$new_filename");
		}

		@chmod('./' . $board_config['avatar_path'] . "/$new_filename", 0777);

		$avatar_sql = ( $mode == 'editprofile' ) ? ", user_avatar = '$new_filename', user_avatar_type = " . USER_AVATAR_UPLOAD : "'$new_filename', " . USER_AVATAR_UPLOAD;
	}
	else
	{
		$l_avatar_size = sprintf($lang['Avatar_imagesize'], $board_config['avatar_max_width'], $board_config['avatar_max_height']);

		$error = true;
		$error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $l_avatar_size : $l_avatar_size;
	}

	return $avatar_sql;
}

function display_avatar_gallery($mode, &$category, &$user_id, &$email, &$current_email, &$coppa, &$username, &$email, &$new_password, &$cur_password, &$password_confirm, &$icq, &$aim, &$msn, &$yim, &$xfi, &$skype, &$website, &$stumble, &$location, &$user_flag, &$occupation, &$interests, &$viewemail, &$notifypm, &$popup_pm, &$sound_pm, &$notifyreply, &$attachsig, &$allowhtml, &$allowbbcode, &$allowsmilies, &$allowswearywords, &$user_transition, &$hideonline, &$style, &$language, &$timezone, &$dateformat, &$clockformat, &$birthday, &$gender, &$zipcode, &$avatar_sticky, &$allow_mass_pm, &$profile_view_popup, &$showavatars, &$showsigs, &$popup_notes, &$irc_commands, &$realname, &$custom_post_color, &$myInfo, &$gtalk, &$wrap, $xdata = false, &$session_id)
{
	global $board_config, $db, $template, $lang, $images, $theme, $start, $phpEx;
	global $phpbb_root_path, $phpEx;

	$dir = @opendir($board_config['avatar_gallery_path']);

	$avatar_images = array();
	while( $file = @readdir($dir) )
	{
		if( $file != '.' && $file != '..' && !is_file($board_config['avatar_gallery_path'] . '/' . $file) && !is_link($board_config['avatar_gallery_path'] . '/' . $file) )
		{
			$sub_dir = @opendir($board_config['avatar_gallery_path'] . '/' . $file);

			$avatar_row_count = $avatar_col_count = 0;
			while( $sub_file = @readdir($sub_dir) )
			{
				if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $sub_file) )
				{
					$avatar_images[$file][$avatar_row_count][$avatar_col_count] = $sub_file; 
					$avatar_name[$file][$avatar_row_count][$avatar_col_count] = ucfirst(str_replace("_", " ", preg_replace('/^(.*)\..*$/', '\1', $sub_file)));

					$avatar_col_count++;
					if( $avatar_col_count == 5 )
					{
						$avatar_row_count++;
						$avatar_col_count = 0;
					}
				}
			}
		}
	}

	@closedir($dir);

	@ksort($avatar_images);
	@reset($avatar_images);

	if( empty($category) )
	{
		list($category, ) = each($avatar_images);
	}
	@reset($avatar_images);

	$s_categories = '<select name="avatarcategory">';
	while( list($key) = each($avatar_images) )
	{
		$selected = ( $key == $category ) ? ' selected="selected"' : '';
		if( count($avatar_images[$key]) )
		{
			$s_categories .= '<option value="' . $key . '"' . $selected . '>' . ucfirst($key) . '</option>';
		}
	}
	$s_categories .= '</select>';

	$s_colspan = 0;
	for($i = $start / 5; $i < $start / 5  + $board_config['avatars_per_page'] / 5; $i++)
	{
		$template->assign_block_vars('avatar_row', array());

		$s_colspan = max($s_colspan, sizeof($avatar_images[$category][$i]));

		for($j = 0; $j < sizeof($avatar_images[$category][$i]); $j++)
		{
			$template->assign_block_vars('avatar_row.avatar_column', array(
				'AVATAR_IMAGE' => $board_config['avatar_gallery_path'] . '/' . $category . '/' . $avatar_images[$category][$i][$j], 
				'AVATAR_NAME' => $avatar_name[$category][$i][$j])
			);

			$template->assign_block_vars('avatar_row.avatar_option_column', array(
				'S_OPTIONS_AVATAR' => $avatar_images[$category][$i][$j])
			);
		}
	}

	$params = array('coppa', 'user_id', 'username', 'email', 'current_email', 'cur_password', 'new_password', 'password_confirm', 'icq', 'aim', 'msn', 'yim', 'xfi', 'skype', 'website', 'stumble', 'location', 'user_flag', 'occupation', 'interests', 'viewemail', 'notifypm', 'popup_pm', 'sound_pm', 'notifyreply', 'attachsig', 'allowhtml', 'allowbbcode', 'allowsmilies', 'allowswearywords', 'user_transition', 'hideonline', 'style', 'language', 'timezone', 'dateformat', 'clockformat', 'birthday', 'gender', 'zipcode', 'avatar_sticky', 'allow_mass_pm', 'profile_view_popup', 'showavatars', 'showsigs', 'popup_notes', 'irc_commands', 'realname', 'custom_post_color', 'myInfo', 'gtalk', 'wrap');

	$s_hidden_vars = '<input type="hidden" name="sid" value="' . $session_id . '" /><input type="hidden" name="agreed" value="true" /><input type="hidden" name="avatarcatname" value="' . $category . '" />';

	for($i = 0; $i < sizeof($params); $i++)
	{
		$s_hidden_vars .= '<input type="hidden" name="' . $params[$i] . '" value="' . str_replace('"', '&quot;', $$params[$i]) . '" />';
	}
	
	if (!is_array($xdata))
	{
		$xdata = array();
	}

	$xd_meta = get_xd_metadata();
	while ( list($code_name, ) = each($xd_meta) )
	{
		if ( isset($xdata[$code_name]) )
		{
			$s_hidden_vars .= '<input type="hidden" name="'. $code_name . '" value="' . str_replace('"', '&quot;', $xdata[$code_name]) . '" />';
		}
	}

	$total = sizeof($avatar_images[$category]) * 5;
	$pagination = ( $total > $board_config['avatars_per_page'] ) ? generate_pagination("profile.$phpEx?mode=editprofile&amp;avatargallery=true&category=$category&sid=$session_id", $total, $board_config['avatars_per_page'], $start). '&nbsp;' : '&nbsp;';
   
	$template->assign_vars(array(
		'PAGINATION' => $pagination,
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['avatars_per_page'] ) + 1 ), ceil( $total / $board_config['avatars_per_page'] )),

		'L_AVATAR_GALLERY' => $lang['Avatar_gallery'], 
		'L_SELECT_AVATAR' => $lang['Select_avatar'], 
		'L_RETURN_PROFILE' => $lang['Return_profile'], 
		'L_CATEGORY' => $lang['Select_category'], 

		'S_CATEGORY_SELECT' => $s_categories, 
		'S_COLSPAN' => $s_colspan, 
		'S_PROFILE_ACTION' => append_sid("profile.$phpEx?mode=$mode&ucp=avatar"), 
		'S_HIDDEN_FIELDS' => $s_hidden_vars)
	);

	return;
}

function display_avatar_generator($mode, $cpl_mode, &$avatar_filename, &$avatar_image, &$avatar_text, &$user_id, &$email, &$current_email, &$coppa, &$username, &$email, &$new_password, &$cur_password, &$password_confirm, &$icq, &$aim, &$msn, &$yim, &$xfi, &$skype, &$website, &$stumble, &$location, &$user_flag, &$occupation, &$interests, &$viewemail, &$notifypm, &$popup_pm, &$sound_pm, &$notifyreply, &$attachsig, &$allowhtml, &$allowbbcode, &$allowsmilies, &$allowswearywords, &$user_transition, &$hideonline, &$style, &$language, &$timezone, &$dateformat, &$clockformat, &$birthday, &$gender, &$zipcode, &$avatar_sticky, &$allow_mass_pm, &$profile_view_popup, &$showavatars, &$showsigs, &$popup_notes, &$irc_commands, &$realname, &$custom_post_color, &$myInfo, &$gtalk, $xdata = false, &$session_id)
{
	global $board_config, $db, $template, $lang, $images, $theme, $phpEx;
	global $phpbb_root_path, $phpEx;

	$language = $board_config['default_lang'];
	if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.' . $phpEx);

	$params = array('coppa', 'user_id', 'username', 'email', 'current_email', 'cur_password', 'new_password', 'password_confirm', 'icq', 'aim', 'msn', 'yim', 'xfi', 'skype', 'website', 'stumble', 'location', 'user_flag', 'occupation', 'interests', 'viewemail', 'notifypm', 'popup_pm', 'sound_pm', 'notifyreply', 'attachsig', 'allowhtml', 'allowbbcode', 'allowsmilies', 'allowswearywords', 'user_transition', 'hideonline', 'style', 'language', 'timezone', 'dateformat', 'clockformat', 'birthday', 'gender', 'zipcode', 'avatar_sticky', 'allow_mass_pm', 'profile_view_popup', 'showavatars', 'showsigs', 'popup_notes', 'irc_commands', 'realname', 'custom_post_color', 'myInfo', 'gtalk');

	$s_hidden_vars = '<input type="hidden" name="sid" value="' . $session_id . '" /><input type="hidden" name="agreed" value="true" />';

	for($i = 0; $i < sizeof($params); $i++)
	{
		$s_hidden_vars .= '<input type="hidden" name="' . $params[$i] . '" value="' . str_replace('"', '&quot;', $$params[$i]) . '" />';
	}

	if (!is_array($xdata))
	{
		$xdata = array();
	}

	$xd_meta = get_xd_metadata();
	while ( list($code_name, ) = each($xd_meta) )
	{
		if ( isset($xdata[$code_name]) )
		{
			$s_hidden_vars .= '<input type="hidden" name="'. $code_name . '" value="' . str_replace('"', '&quot;', $xdata[$code_name]) . '" />';
		}
	}

	$s_hidden_vars .= '<input type="hidden" name="avatar_filename" value="' . $avatar_filename . '" />';

	$template->assign_vars(array(
		'L_AVATAR_GENERATOR' => $lang['Avatar_Generator'],
		'L_RANDOM' => $lang['Random'],
		'L_YES' => $lang['Yes'],
		'L_YOUR_AVATAR' => $lang['Your_Avatar'],
		'L_AVATAR_TEXT' => $lang['Avatar_Text'],
		'L_PREVIEW_AVATAR' => $lang['Preview'],
		'L_SELECT_AVATAR' => $lang['Select_avatar'], 
		'L_RETURN_PROFILE' => $lang['Return_profile'],
		'AVATAR_TEMPLATE_PATH' => $board_config['avatar_generator_template_path'],
		'AVATAR_FILENAME' => $avatar_filename,

		'S_IMAGE_NAME' => $avatar_image,
		'S_IMAGE_TEXT' => $avatar_text,
		'S_FILENAME' => append_sid('profile_avatar_generator.'.$phpEx),
		'S_PROFILE_ACTION' => append_sid("profile.$phpEx?mode=$mode&amp;ucp=$cpl_mode"), 
		'S_HIDDEN_FIELDS' => $s_hidden_vars)
	);

	return;
}

?>
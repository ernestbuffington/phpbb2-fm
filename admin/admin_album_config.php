<?php
/** 
*
* @package admin
* @version $Id: admin_album_config.php,v 1.0.4 2003/03/03, 20:52:14 ngoctu Exp $
* @copyright (c) 2003 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
//	$module['Album']['Configuration'] = $filename;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
$album_root_path = $phpbb_root_path . 'mods/album/';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);
include($album_root_path . 'album_common.'.$phpEx);


//
// Check to see what mode we should operate in.
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = 'config';
}


//
// Pull all config data
//
$sql = "SELECT * 
	FROM " . ALBUM_CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query Album config information", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . ALBUM_CONFIG_TABLE . " SET
				config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update Album configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}
	$db->sql_freeresult($result);

	if( isset($HTTP_POST_VARS['submit']) )
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_album.'.$phpEx);

		$message = $lang['Board_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_album_config.'.$phpEx.'?mode=' . $mode) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}


//
// Check to see what section we should load
//
switch($mode)
{
	case 'thumb':
		$template->assign_block_vars('switch_thumb', array());
		break;
	case 'midthumb':
		$template->assign_block_vars('switch_midthumb', array());
		break;
	case 'watermk':
		$template->assign_block_vars('switch_watermk', array());
		break;
	case 'hon':
		$template->assign_block_vars('switch_hon', array());
		break;
	case 'config':
	default:
		$template->assign_block_vars('switch_config', array());
		break;
}
$hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';


$template->set_filenames(array(
	'body' => 'admin/album_config_body.tpl')
);

$template->assign_vars(array(
	'S_ALBUM_CONFIG_ACTION' => append_sid('admin_album_config.'.$phpEx),
	
	'L_ALBUM_CONFIG' => $lang['Album'] . ' ' . $lang['Setting'],
	'L_ALBUM_CONFIG_EXPLAIN' => sprintf($lang['Config_explain'], $lang['Album']) . ' ' . sprintf($lang['Album_config_explain'], '<a href="' . append_sid('admin_album_clearcache.'.$phpEx) . '">', '</a>'),
		
	'L_MAX_PICS' => $lang['Max_pics'],
	'L_USER_PICS_LIMIT' => $lang['User_pics_limit'],
	'L_MOD_PICS_LIMIT' => $lang['Moderator_pics_limit'],
	'L_MAX_FILE_SIZE' => $lang['Max_file_size'],
	'L_IMAGE_SIZE' => $lang['Image_dimensions'],
	'L_IMAGE_SIZE_EXPLAIN' => $lang['Max_avatar_size_explain'],
	'L_PIC_DESC_MAX_LENGTH' => $lang['Pic_Desc_Max_Length'],
	'L_GD_VERSION' => $lang['GD_version'],
	'L_MANUAL_THUMBNAIL' => $lang['Manual_thumbnail'],
	'L_JPG_ALLOWED' => $lang['JPG_allowed'],
	'L_PNG_ALLOWED' => $lang['PNG_allowed'],
	'L_GIF_ALLOWED' => $lang['GIF_allowed'],
	'L_DISPLAY_LATEST' => $lang['Display_latest'],
	'L_DISPLAY_HIGHEST' => $lang['Display_highest'],
	'L_DISPLAY_RANDOM' => $lang['Display_random'],
	'L_HOTLINK_PREVENT' => $lang['Hotlink_prevent'],
	'L_HOTLINK_ALLOWED' => $lang['Hotlink_allowed'],	
	'L_THUMBNAIL_SETTINGS' => $lang['Thumbnail_Settings'],
	'L_THUMBNAIL_CACHE' => $lang['Thumbnail_cache'],
	'L_THUMBNAIL_SIZE' => $lang['Thumbnail_size'],
	'L_THUMBNAIL_QUALITY' => $lang['Thumbnail_quality'],
	'L_ROWS_PER_PAGE' => $lang['Rows_per_page'],
	'L_COLS_PER_PAGE' => $lang['Cols_per_page'],
	'L_DEFAULT_SORT_METHOD' => $lang['Default_Sort_Method'],
	'L_DEFAULT_SORT_ORDER' => $lang['Default_Sort_Order'],
	'L_FULLPIC_POPUP' => $lang['Fullpic_Popup'],
	'L_MID_THUMBNAIL_SETTINGS' => $lang['Mid_thumb_settings'],
	'L_MIDTHUMB_USE' => $lang['Mid_thumb_use'],
	'L_MIDTHUMB_CACHE' => $lang['Mid_thumb_cache'],
	'L_MIDTHUMB_SIZE' => $lang['Mid_thumb_size'],
	'L_PIC_ROW' => $lang['Mid_thumb_row'],
	'L_PIC_COL' => $lang['Mid_thumb_col'],
	'L_ALBUM_SP_WATERMARK' => $lang['Watermark_settings'],
	'L_WATERMARK_USERS' => $lang['Watermark_users'],
	'L_WATERMARK_USERS_EXPLAIN' => $lang['Watermark_users_explain'],
	'L_WATERMARK' => $lang['Watermark_use'],
	'L_WATERMARK_PLACENT' => $lang['Watermark_place'],
	'L_ALBUM_SP_HOTORNOT' => $lang['Hon_settings'],
	'L_HON_ALREDY_RATED' => $lang['Hon_already'],
	'L_HON_SEP_RATING' => $lang['Hon_rating'],
	'L_HON_WHERE' => $lang['Hon_where'],
	'L_HON_WHERE_EXPLAIN' => $lang['Hon_where_explain'],
	'L_HON_USERS' => $lang['Hon_users'],
	'L_PERSONAL_GALLERY' => $lang['Personal_gallery'],
	'L_PERSONAL_GALLERY_LIMIT' => $lang['Personal_gallery_limit'],
	'L_PERSONAL_GALLERY_VIEW' => $lang['Personal_gallery_view'],
	'L_RATE_SYSTEM' => $lang['Rate_system'],
	'L_RATE_SCALE' => $lang['Rate_Scale'],
	'L_RATE_TYPE' => $lang['Rate_type'],
	'L_RATE_TYPE_0' => $lang['Rate_type_0'],
	'L_RATE_TYPE_1' => $lang['Rate_type_1'],
	'L_RATE_TYPE_2' => $lang['Rate_type_2'],
	'L_COMMENT_SYSTEM' => $lang['Comment_system'],
	'L_TIME' => $lang['Time'],
	'L_PIC_TITLE' => $lang['Pic_Title'],
	'L_USERNAME' => $lang['Sort_Username'],
	'L_VIEW' => $lang['View'],
	'L_RATING' => $lang['Rating'],
	'L_COMMENTS' => $lang['Comments'],
	'L_NEW_COMMENT' => $lang['New_Comment'],
	'L_ASC' => $lang['Sort_Ascending'],
	'L_DESC' => $lang['Sort_Descending'],
	'L_GUEST' => $lang['Forum_ALL'], 
	'L_REG' => $lang['Forum_REG'], 
	'L_PRIVATE' => $lang['Forum_PRIVATE'], 
	'L_MOD' => $lang['Forum_MOD'], 
	'L_ADMIN' => $lang['Forum_ADMIN'],
	'L_FEW_POSTS' => $lang['Posts_upload_enable'],
	'L_FEW_POSTS_EXPLAIN' => $lang['Posts_upload_enable_explain'],
	'L_SLIDEPICS_PER_PAGE' => $lang['Slidepics_per_page'],
	'L_SLIDEPICS_PER_PAGE_EXPLAIN' => $lang['Slidepics_per_page_explain'],
	
	'MAX_PICS' => $new['max_pics'],
	'MAX_FILE_SIZE' => $new['max_file_size'],
	'MAX_WIDTH' => $new['max_width'],
	'MAX_HEIGHT' => $new['max_height'],
	'ROWS_PER_PAGE' => $new['rows_per_page'],
	'COLS_PER_PAGE' => $new['cols_per_page'],
	'THUMBNAIL_QUALITY' => $new['thumbnail_quality'],
	'THUMBNAIL_SIZE' => $new['thumbnail_size'],
	'PERSONAL_GALLERY_LIMIT' => $new['personal_gallery_limit'],
	'USER_PICS_LIMIT' => $new['user_pics_limit'],
	'MOD_PICS_LIMIT' => $new['mod_pics_limit'],
	'THUMBNAIL_CACHE_ENABLED' => ($new['thumbnail_cache']) ? 'checked="checked"' : '',
	'THUMBNAIL_CACHE_DISABLED' => (!$new['thumbnail_cache']) ? 'checked="checked"' : '',
	'JPG_ENABLED' => ($new['jpg_allowed']) ? 'checked="checked"' : '',
	'JPG_DISABLED' => (!$new['jpg_allowed']) ? 'checked="checked"' : '',
	'PNG_ENABLED' => ($new['png_allowed']) ? 'checked="checked"' : '',
	'PNG_DISABLED' => (!$new['png_allowed']) ? 'checked="checked"' : '',
	'GIF_ENABLED' => ($new['gif_allowed']) ? 'checked="checked"' : '',
	'GIF_DISABLED' => (!$new['gif_allowed']) ? 'checked="checked"' : '',
	'PIC_DESC_MAX_LENGTH' => $new['desc_length'],
	'HOTLINK_PREVENT_ENABLED' => ( $new['hotlink_prevent'] ) ? 'checked="checked"' : '',
	'HOTLINK_PREVENT_DISABLED' => ( !$new['hotlink_prevent'] ) ? 'checked="checked"' : '',
	'HOTLINK_ALLOWED' => $new['hotlink_allowed'],
	'PERSONAL_GALLERY_USER' => ($new['personal_gallery'] == ALBUM_USER) ? 'checked="checked"' : '',
	'PERSONAL_GALLERY_PRIVATE' => ($new['personal_gallery'] == ALBUM_PRIVATE) ? 'checked="checked"' : '',
	'PERSONAL_GALLERY_ADMIN' => ($new['personal_gallery'] == ALBUM_ADMIN) ? 'checked="checked"' : '',
	'PERSONAL_GALLERY_VIEW_ALL' => ($new['personal_gallery_view'] == ALBUM_GUEST) ? 'checked="checked"' : '',
	'PERSONAL_GALLERY_VIEW_REG' => ($new['personal_gallery_view'] == ALBUM_USER) ? 'checked="checked"' : '',
	'PERSONAL_GALLERY_VIEW_PRIVATE' => ($new['personal_gallery_view'] == ALBUM_PRIVATE) ? 'checked="checked"' : '',
	'RATE_ENABLED' => ($new['rate']) ? 'checked="checked"' : '',
	'RATE_DISABLED' => (!$new['rate']) ? 'checked="checked"' : '',
	'RATE_SCALE' => $new['rate_scale'],
	'COMMENT_ENABLED' => ($new['comment']) ? 'checked="checked"' : '',
	'COMMENT_DISABLED' => (!$new['comment']) ? 'checked="checked"' : '',
	'NO_GD' => ( !$new['gd_version'] ) ? 'checked="checked"' : '',
	'GD_V1' => ( $new['gd_version'] == 1 ) ? 'checked="checked"' : '',
	'GD_V2' => ( $new['gd_version'] == 2 ) ? 'checked="checked"' : '',
	'SORT_TIME' => ($new['sort_method'] == 'pic_time') ? 'selected="selected"' : '',
	'SORT_PIC_TITLE' => ($new['sort_method'] == 'pic_title') ? 'selected="selected"' : '',
	'SORT_USERNAME' => ($new['sort_method'] == 'pic_user_id') ? 'selected="selected"' : '',
	'SORT_VIEW' => ($new['sort_method'] == 'pic_view_count') ? 'selected="selected"' : '',
	'SORT_RATING' => ($new['sort_method'] == 'rating') ? 'selected="selected"' : '',
	'SORT_COMMENTS' => ($new['sort_method'] == 'comments') ? 'selected="selected"' : '',
	'SORT_NEW_COMMENT' => ($new['sort_method'] == 'new_comment') ? 'selected="selected"' : '',
	'SORT_ASC' => ($new['sort_order'] == 'ASC') ? 'selected="selected"' : '',
	'SORT_DESC' => ($new['sort_order'] == 'DESC') ? 'selected="selected"' : '',
	'FULLPIC_POPUP_ENABLED' => ($new['fullpic_popup']) ? 'checked="checked"' : '',
	'FULLPIC_POPUP_DISABLED' => (!$new['fullpic_popup']) ? 'checked="checked"' : '',
	'RATE_TYPE_0' => (!$new['rate_type']) ? 'selected="selected"' : '',
	'RATE_TYPE_1' => ($new['rate_type']) ? 'selected="selected"' : '',
	'RATE_TYPE_2' => ($new['rate_type'] == 2) ? 'selected="selected"' : '',
	'DISPLAY_LATEST_ENABLED' => ($new['disp_late']) ? 'checked="checked"' : '',
	'DISPLAY_LATEST_DISABLED' => (!$new['disp_late']) ? 'checked="checked"' : '',
	'DISPLAY_HIGHEST_ENABLED' => ($new['disp_high']) ? 'checked="checked"' : '',
	'DISPLAY_HIGHEST_DISABLED' => (!$new['disp_high']) ? 'checked="checked"' : '',
	'DISPLAY_RANDOM_ENABLED' => ($new['disp_rand']) ? 'checked="checked"' : '',
	'DISPLAY_RANDOM_DISABLED' => (!$new['disp_rand']) ? 'checked="checked"' : '',
	'PIC_ROW' => $new['img_rows'],
	'PIC_COL' => $new['img_cols'],
	'MIDTHUMB_ENABLED' => ($new['midthumb_use']) ? 'checked="checked"' : '',
	'MIDTHUMB_DISABLED' => (!$new['midthumb_use']) ? 'checked="checked"' : '',
	'MIDTHUMB_CACHE_ENABLED' => ($new['midthumb_cache']) ? 'checked="checked"' : '',
	'MIDTHUMB_CACHE_DISABLED' => (!$new['midthumb_cache']) ? 'checked="checked"' : '',
	'MIDTHUMB_HEIGHT' => $new['midthumb_height'],
	'MIDTHUMB_WIDTH' => $new['midthumb_width'],
	'WATERMARK_ENABLED' => ($new['use_watermark']) ? 'checked="checked"' : '',
	'WATERMARK_DISABLED' => (!$new['use_watermark']) ? 'checked="checked"' : '',
	'WATERMARK_USERS_ENABLED' => ($new['wut_users']) ? 'checked="checked"' : '',
	'WATERMARK_USERS_DISABLED' => (!$new['wut_users']) ? 'checked="checked"' : '',
	'WATERMAR_PLACEMENT_0' => (!$new['disp_watermark_at']) ? 'checked="checked"' : '',
	'WATERMAR_PLACEMENT_1' => ($new['disp_watermark_at'] == 1) ? 'checked="checked"' : '',
	'WATERMAR_PLACEMENT_2' => ($new['disp_watermark_at'] == 2) ? 'checked="checked"' : '',
	'WATERMAR_PLACEMENT_3' => ($new['disp_watermark_at'] == 3) ? 'checked="checked"' : '',
	'WATERMAR_PLACEMENT_4' => ($new['disp_watermark_at'] == 4) ? 'checked="checked"' : '',
	'WATERMAR_PLACEMENT_5' => ($new['disp_watermark_at'] == 5) ? 'checked="checked"' : '',
	'WATERMAR_PLACEMENT_6' => ($new['disp_watermark_at'] == 6) ? 'checked="checked"' : '',
	'WATERMAR_PLACEMENT_7' => ($new['disp_watermark_at'] == 7) ? 'checked="checked"' : '',
	'WATERMAR_PLACEMENT_8' => ($new['disp_watermark_at'] == 8) ? 'checked="checked"' : '',
	'HON_ALREADY_RATED_ENABLED' => ($new['hon_rate_times']) ? 'checked="checked"' : '',
	'HON_ALREADY_RATED_DISABLED' => (!$new['hon_rate_times']) ? 'checked="checked"' : '',
	'HON_SEP_RATING_ENABLED' => ($new['hon_rate_sep']) ? 'checked="checked"' : '',
	'HON_SEP_RATING_DISABLED' => (!$new['hon_rate_sep']) ? 'checked="checked"' : '',
	'HON_WHERE' => $new['hon_rate_where'],
	'HON_USERS_ENABLED' => ($new['hon_rate_users']) ? 'checked="checked"' : '',
	'HON_USERS_DISABLED' => (!$new['hon_rate_users']) ? 'checked="checked"' : '',
	'HOW_MANY_POSTS' => $new['posts'],
	'S_GUEST' => ALBUM_GUEST,
	'S_USER' => ALBUM_USER,
	'S_PRIVATE' => ALBUM_PRIVATE,
	'S_MOD' => ALBUM_MOD,
	'S_ADMIN' => ALBUM_ADMIN,
	'SLIDEPICS_PER_PAGE' => $new['slidepics_per_page'],
		
	// All pages
	'CONFIG_SELECT' => config_select($mode, array(
		'config' => $lang['Configuration'],
		'thumb' => $lang['Thumbnail_Settings'],
		'midthumb' => $lang['Mid_thumb_settings'],
		'watermk' => $lang['Watermark_settings'],
		'hon' => $lang['Hon_settings'])
	),
	
	'S_HIDDEN_FIELDS' => $hidden_fields)		
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
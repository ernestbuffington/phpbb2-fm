<?php
/** 
*
* @package lang_english
* @version $Id: lang_admin_album.php,v 1.0.6 2003/03/05 00:21:55 ngoctu Exp $
* @copyright (c) 2003 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

//
// Configuration
//
$lang['Album_config_explain'] = 'Each time you change a setting here you <b>must</b> %sclear your album cache%s for the changes to effect pre-thumbnailed images.';
$lang['Max_pics'] = 'Pictures per category (-1 = unlimited)';
$lang['User_pics_limit'] = 'Pictures per category per user (-1 = unlimited)';
$lang['Moderator_pics_limit'] = 'Pictures per category per moderator (-1 = unlimited)';
$lang['Pics_Approval'] = 'Picture approval';
$lang['Rows_per_page'] = 'Rows on thumbnail page';
$lang['Cols_per_page'] = 'Columns on thumbnail page';
$lang['Thumbnail_quality'] = 'Thumbnail quality (1-100)';
$lang['Thumbnail_cache'] = 'Cache thumbnail images';
$lang['Manual_thumbnail'] = 'Manual thumbnail';
$lang['GD_version'] = 'Optimize for GD version';
$lang['Pic_Desc_Max_Length'] = 'Maximum comment length';
$lang['Hotlink_prevent'] = 'Hotlink prevention';
$lang['Hotlink_allowed'] = 'Allowed domains for hotlink (separated by a comma)';
$lang['Personal_gallery'] = 'Allowed to create a personal gallery';
$lang['Personal_gallery_limit'] = 'Pictures per personal gallery (-1 = unlimited)';
$lang['Personal_gallery_view'] = 'Who can view personal galleries';
$lang['Rate_system'] = 'Enable rating system';
$lang['Rate_Scale'] =' Rating scale';
$lang['Comment_system'] = 'Enable comment system';
$lang['Thumbnail_Settings'] = 'Thumbnail Settings';
$lang['Default_Sort_Method'] = 'Default sort method';
$lang['Default_Sort_Order'] = 'Default sort order';
$lang['Fullpic_Popup'] = 'View full picture as a popup';
$lang['Image_dimensions'] = 'Image dimensions';
$lang['Display_latest'] = 'Display latest pictures block';
$lang['Display_highest'] = 'Display rated pictures block';
$lang['Display_random'] = 'Display random pictures block';
$lang['Mid_thumb_settings'] = 'Medium Thumbnail Settings';
$lang['Mid_thumb_use'] = 'Use medium thumbnails';
$lang['Mid_thumb_cache'] = 'Cache medium thumbnail images';
$lang['Mid_thumb_size'] = 'Medium thumbnail dimensions';
$lang['Mid_thumb_row'] = 'Rows on thumbnail blocks';
$lang['Mid_thumb_col'] = 'Columns on thumbnail blocks';
$lang['Watermark_settings'] = 'Watermark Settings';
$lang['Watermark_users'] = 'Show watermark to all users';
$lang['Watermark_users_explain'] = '\'No\' only displays to unregistered users.';
$lang['Watermark_use'] = 'Use watermark';
$lang['Watermark_place'] = 'Display watermark at which position on picture'; 
$lang['Hon_settings'] = 'Hot or Not Settings';
$lang['Hon_already'] = 'Unlimited rating on Hot or Not page';
$lang['Hon_rating'] = 'Store Hot or Not rating in a separate table';
$lang['Hon_where'] = 'Display pictures on Hot or Not from what categories?';
$lang['Hon_where_explain'] = 'Leave blank to use pictures from all of the categories, separate multiple categories with commas.';
$lang['Hon_users'] = 'Can unregistered users rate';
$lang['Rate_type'] = 'Display picture ratings as';
$lang['Rate_type_0'] = 'Images only';
$lang['Rate_type_1'] = 'Numbers only';
$lang['Rate_type_2'] = 'Numbers &amp; Images';
$lang['Posts_upload_enable'] = 'Post limit to upload pictures';
$lang['Posts_upload_enable_explain'] = 'Users must have a minimum of this many posts to upload pics. Set to zero to disable this feature.';
$lang['Slidepics_per_page'] = 'Pictures to slide per slidepage';
$lang['Slidepics_per_page_explain'] = 'Set to zero to disable this feature.';


//
// Personal Gallery Page
//
$lang['Personal_Galleries'] = 'Personal Galleries';
$lang['Album_personal_gallery_explain'] = 'On this page, you can choose which usergroups have right to create and view personal galleries. These settings only affect when you set "PRIVATE" for "Allowed to create personal gallery for users" or "Who can view personal galleries" in Album Settings.';
$lang['Album_personal_successfully'] = 'Album Personal Gallery Setting Updated Successfully.';
$lang['Click_return_album_personal'] = 'Click %sHere%s to return to the Personal Gallery Settings';

//
// Categories
//
$lang['Album_Categories_Title'] = 'Album Categories Control';
$lang['Album_Categories_Explain'] = 'On this screen you can manage your categories: create, alter, delete, sort, etc.';
$lang['Category_Permissions'] = 'Category Permissions';
$lang['Category_Title'] = 'Category Title';
$lang['Category_Desc'] = 'Category Description';
$lang['View_level'] = 'View Level';
$lang['Upload_level'] = 'Upload Level';
$lang['Rate_level'] = 'Rate Level';
$lang['Comment_level'] = 'Comment Level';
$lang['Edit_level'] = ' Edit Level';
$lang['Delete_level'] = 'Delete Level';
$lang['New_category_created'] = 'New category has been created successfully';
$lang['Click_return_album_category'] = 'Click %sHere%s to return to the Album Categories Manager';
$lang['Category_updated'] = 'Album Category Updated Successfully.';
$lang['Delete_Category'] = 'Delete Category';
$lang['Delete_Category_Explain'] = 'The form below will allow you to delete a category and decide where you want to put pics it contained.';
$lang['Delete_all_pics'] = 'Delete all pics';
$lang['Category_deleted'] = 'Album Category Deleted Successfully.';
$lang['Category_changed_order'] = 'Album Category Order Update Successfully.';

//
// Permissions
//
$lang['Album_Auth_Title'] = 'Album Permissions';
$lang['Album_Auth_Explain'] = 'Here you can choose which usergroup(s) can be the moderators for each album category or just has the private access.';
$lang['Select_a_Category'] = 'Select a Category';
$lang['Look_up_Category'] = 'Look up Category';
$lang['Album_Auth_successfully'] = 'Album Permissions Updated successfully.';
$lang['Click_return_album_auth'] = 'Click %sHere%s to return to Album Permissions';

$lang['Rate'] = 'Rate';
$lang['Comment'] = 'Comment';

//
// Clear Cache
//
$lang['Album_clear_cache_confirm'] = 'If you use the Thumbnail Cache feature you must clear your thumbnail cache after changing any thumbnail settings in Album Configuration to re-generate thumbnails with the new settings.<br /><br /> Do you want to clear them now?';
$lang['Thumbnail_cache_cleared_successfully'] = 'Thumbnail Cache Cleared Successfully.';

//
// Unapproved Pics
//
$lang['album_unapproved_title'] = 'Unapproved Pictures';
$lang['album_unapproved_title_explain'] = 'From this panel you can approve, delete or edit unapproved album pictures easily.';
$lang['no_unapproved_pics_found'] = 'No pictures to approve.';
$lang['Click_return_unapproved_pics'] = 'Click %sHere%s to return to Unapproved Pictures';
?>
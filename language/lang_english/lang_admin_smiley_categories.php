<?php
/** 
*
* @package lang_english
* @version $Id: lang_admin_smiley_categories.php,v 1.35.2.9 2004/04/27 00:31:19 afkamm Exp $
* @copyright (c) 2004 Afkamm
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

//
// Add/Edit a category
//
// Add
$lang['add_desc'] = 'You can add a new category with this form, just enter a name and a description and then click the submit button. The category image is only required if you\'re using buttons and want that button to be an image, see Smilie Configuration.';
$lang['add_desc_explain'] = 'Use letters and numbers only';
$lang['add_success'] = 'Category added successfully';
$lang['add_fail'] = 'You failed to enter a name and/or description!';

// Edit
$lang['edit_desc'] = 'Select the category that you wish to edit from the dropdown menu. The details should then appear below, edit them to your liking and click the submit button to save the changes.';
$lang['edit_desc2'] = ' You do not have to submit changes to test the popup size.';
$lang['select_cat'] = 'Select a category to edit';
$lang['edit_delete'] = 'Delete this category';
$lang['edit_delete_explain'] = 'Click here to delete this category, this cannot be undone.';
$lang['Confirm_del_cat'] = 'Are you sure you want to delete this category and all the smilies in it?';
$lang['cat_del_success'] = 'Category deleted successfully';
$lang['cat_del_fail'] = 'Error: Category does not exist!';
$lang['edit_success'] = 'Category edited successfully';
$lang['edit_success_up'] = 'Category edited and moved up successfully';
$lang['edit_success_down'] = 'Category edited and moved down successfully';
$lang['edit_fail'] = 'Error: You failed to enter a name and/or description!';

// Add & Edit
$lang['cat_name'] = 'Category name';
$lang['cat_description'] = 'Category description';
$lang['smilies_per_page'] = 'Smilies per page';
$lang['smilies_no_limit'] = 'Set to zero for no limit.';
$lang['popup_title'] = 'Popup window settings';
$lang['popup_description'] = 'These settings determine the size of the popup window and how the smilies are displayed. Tailor the window to suit the smilies inside this category.';
$lang['popup_group_list'] = 'Display smilies';
$lang['popup_group'] = 'as a group';
$lang['popup_list'] = 'in a list';
$lang['popup_group_cols'] = 'Group columns';
$lang['popup_list_cols'] = 'List columns';
$lang['popup_size'] = 'Popup window size';
$lang['popup_size_attribs'] = '(width x height)';
$lang['popup_size_test'] = 'Click this link to test the popup window size';
$lang['popup_size_test2'] = 'Test';
$lang['popup_alert'] = 'Please select a category first!';
$lang['viewable_by'] = 'Viewable by';
$lang['viewable_by_explain'] = 'The group selected will be the only ones to see this category and the only ones to see the smilies in the posts.';
$lang['perms']['50'] = 'Nobody';
$lang['perms']['10'] = 'Everyone';
$lang['perms']['20'] = 'Regs and up';
$lang['perms']['30'] = 'Mods and up';
$lang['perms']['40'] = 'Admins only';
$lang['usergroups'] = 'Link to usergroups';
$lang['usergroups_explain'] = 'If you wish this category to be linked with a usergroup, select it here.';
$lang['forums'] = 'Link to forums';
$lang['forums_explain'] = 'Select one or more forums to link this category to them. Categories are in green, forums in yellow. Selecting a category does nothing.';
$lang['order_position'] = 'Order position';
$lang['order_first'] = 'First';
$lang['order_last'] = 'Last';
$lang['order_after'] = 'After';
$lang['order_change'] = 'Select a new position.';
$lang['cat_icon'] = 'Category image';
$lang['select_cat_icon'] = 'Select or leave blank.';
$lang['cat_open'] = 'Open category';
$lang['cat_open_explain'] = 'Makes the smiley category open outside of the forums so other features can use the smilies.';
$lang['cat_special'] = 'Special category';
$lang['cat_special_explain'] = 'If used, this group will be the only one to see this category. However the smilies in this category will appear in posts to everyone.';
//
// IMPORTANT: The order the specials are in here are how they will appear in the dropdown menu on the add/edit category pages.
// Extra classes must be defined in the file 'includes/contants.php' and included else where in the forums class code.
// For example please see the 'User Class MOD' by kooky (http://www.myphpbb.zaup.org) or something similar.
// If more classes are added then you need to edit a line in the generate_smilies() function in 'includes/functions_post.php'
// and the smilies_pass() function in 'includes/bbcode.php' to accommodate these new classes. Contact me for details. :)
//
$lang['special']['-2'] = 'Don\'t Use';		// Just what it says on the tin. :)
$lang['special']['0'] = 'Regulars';		//define('USER', 0);
$lang['special']['2'] = 'Moderators';		//define('MOD', 2);
$lang['special']['1'] = 'Administrators';	//define('ADMIN', 1);


//
//  Add/Edit a Smiley Page
//
$lang['smiley_add_desc'] = 'Everything here is pretty much self explanatory. Add the smiley details and then click submit.';
$lang['smiley_edit_desc'] = 'Everything here is pretty much self explanatory. Edit the smiley details, unless you want to delete the smiley in which case tick the delete box and then click submit.';
$lang['smiley_edit'] = 'Edit Smiley';
$lang['smiley_delete'] = 'Delete this smiley';
$lang['smiley_category'] = 'Smiley Category';
$lang['Confirm_del_smiley'] = 'Are you sure you want to delete this smiley?';
$lang['smiley_code'] = 'Smiley Code';
$lang['smiley_url'] = 'Smiley Image File';
$lang['smiley_emot'] = 'Smiley Emotion';
$lang['smile_add'] = 'Add a new Smiley';
$lang['smiley_edit_success'] = 'Smiley Updated Successfully.';
$lang['smiley_del_success'] = 'Smiley Deleted Successfully.';
$lang['smiley_add_success'] = 'Smiley Added Successfully.';

//
// Category Viewing Page
//
$lang['cat_view_description1'] = 'Click any smiley to edit its\' details. Click the <b>mass edit</b> link under the options column for any given category to go into mass editing mode.';
$lang['cat_view_description2'] = 'Select the categories that you wish to view. (CTRL+LEFT CLICK sets/clears selections). If you have a few hundred smilies, you may wish to view a couple of categories at a time.';
$lang['cat_view_empty'] = 'There are no smilies in this category.';
$lang['smiley_count'] = 'Total: %d Unique: %d'; // Both %d will be replaced with a number.
$lang['mass_edit'] = 'Mass Edit';
$lang['click_edit'] = 'Click to edit';


//
// Mass Edit Page
//
$lang['mass_edit_description'] = 'From here you can edit multiple smilies at once instead of just one at a time. No two smilies may have the same code so please change them until all are unique.<br /><br /><b>Warning:</b> The Order dropdown menus use javascript which will submit the new order value as soon as it\'s selected. They are not part of the main form so don\'t go editing codes etc. and then try and change an order as you\'ll lose your changes.';
$lang['mass_edit_submit'] = 'Submit Changes';
$lang['multi_delete1'] = '%d smiley was deleted.';
$lang['multi_delete2'] = '%d smilies were deleted.';
$lang['multi_updated1'] = '%d smiley was updated. ';
$lang['multi_updated2'] = '%d smilies were updated. ';
$lang['smiley_order_success'] = 'The smiley was successfully moved from position %d to %d.';
$lang['smiley_order_nochange'] = 'The smiley position was not changed.';


//
// Upload Smilies Page.
//
$lang['upload_desc'] = 'You can upload a maximum of 10 files at once. These can either be images or pak files. The directory <b>%s</b> must be writeable for the upload procedure to work. Maximum filesize per image is <b>%d</b> bytes.'; // %s = /images/smiles/  %d = 10000
$lang['upload_warning'] = '<br /><br /><b>Warning:</b> The directory <b>%s</b> is not writeable.'; // %s = /images/smiles/
$lang['upload_amount'] = 'Select the number of files you want to upload';
$lang['upload_success'] = '<span style="color: green;"><b>Success</b></span>';
$lang['upload_failed'] = '<span style="color: red;"><b>Failed</b></span>';
$lang['upload_failed_reason1'] = 'Could not copy file.<br />';
$lang['upload_failed_reason2'] = 'Filename already exists.<br />';
$lang['upload_failed_reason3'] = 'Incorrect file extension.<br />';
$lang['upload_failed_no_file'] = 'No File';
// http://uk.php.net/manual/en/features.file-upload.errors.php
$lang['php_file_error'][0] = 'File uploaded successfully.<br />';
$lang['php_file_error'][1] = 'File exceeds the max filesize set in php.ini.<br />';
$lang['php_file_error'][2] = 'File exceeds the max filesize set in the form.<br />';
$lang['php_file_error'][3] = 'File was only partially uploaded.<br />';
$lang['php_file_error'][4] = 'No file was uploaded.<br />';
$lang['php_file_error'][6] = 'Missing a temporary folder.<br />';
$lang['php_file_error'][7] = 'Failed to write file to disk.<br />';


//
// Smiley Pack Import Page.
//
$lang['import_desc'] = 'If your *.pak, *.pak2 files don\'t show up in the list below, make sure that you have uploaded them to the correct directory which is <b>/%s</b>.';
$lang['import_desc_explain'] = 'These options are not required when importing a .pak2 file and will be ignored.';
$lang['choose_smiley_pak'] = 'Choose a smiley pack file';
$lang['import_cat'] = 'Import to the following Category';
$lang['delete_smiley'] = 'Delete existing smileys before import';
$lang['delete_all'] = 'Delete existing categories before import';
$lang['smiley_conflicts'] = 'What should be done in case of conflicts';
$lang['existing_replace'] = 'Replace Existing Smiley';
$lang['existing_keep'] = 'Keep Existing Smiley';
$lang['select_paks'] = 'Select Pack (.pak, .pak2) File';
$lang['smiley_import_success1'] = 'The Standard Smiley Pack was imported successfully!<br />%d new %s were added and %d existing replaced.';
$lang['smiley_import_success2'] = 'The Advanced Smiley Pack was imported successfully!<br />You now have %d new %s and %d new %s.';
$lang['smiley_import_fail'] = 'Error: No *.pak, *.pak2 file was selected!';
$lang['pak_file_old'] = 'This PAK2 file is an old version. Please post in my forums for help.';
$lang['pak_file_empty'] = 'This PAK file seems to be empty. Please try another.';

//
// Smiley Pack Export Page.
//
$lang['export_desc'] = 'Export just one category at a time by selecting it from the list below, or export all the smilies in one file. If you also want to save the category information as well, select the <b>advanced (.pak2)</b> option.';
$lang['export_type'] = 'What type of Export';
$lang['export_type_pak'] = 'Standard (.pak)';
$lang['export_type_cat'] = 'Advanced (.pak2)';
$lang['export_all'] = 'Export all together';
$lang['export_cat'] = 'Export the following Category';
$lang['export_smiles'] = 'To create a smiley pack from your currently installed smileys, click %sHere%s to download the smiles.pak%d file. Name this file appropriately making sure to keep the .pak%d file extension.  Then create a zip file containing all of your smiley images plus this .pak%d configuration file.';


//
// Overall View of the Permissions
//
$lang['perms_desc'] = 'This page allows you to see at a glance which smiley categories go with each usergroup (if enabled) and each forum and who is able to view them. Clicking a smiley category will take you straight to the editing page.';
$lang['perms_ug_header1'] = 'Forum Usergroups';
$lang['perms_ug_header2'] = 'Usergroup Smilies';
$lang['perms_header1'] = 'Categories &amp; Forums';
$lang['perms_header2'] = 'Forum Smilies';
$lang['perms_header3'] = 'PM Smilies';
$lang['perms_header4'] = 'Open Smilies';
$lang['no_open_categories'] = 'There are no open smiley categories.';


//
// View Unused Smilies Page
//
$lang['unused_desc'] = 'This is the list of smilies that are in the smiley folder, but are not installed. Enter a code and emoticon for the smiley you wish to install, not forgetting to tick the box, select a category and then when finished, click the submit button. If you try to add a smiley with an empty code box, then that smiley will not get installed.<br /><br />If you want to use the filename for the code, or limit how many smilies appear at once, or change the default category to install to, then use the options on the right.';
$lang['smiley_filename_code'] = 'Use filename for code';
$lang['add_to_category'] = 'Select category to add to';
$lang['smiley_multi_add_success1'] = '%d smiley was successfully added.';
$lang['smiley_multi_add_success2'] = '%d smilies were successfully added.';
$lang['smiley_errors1'] = 'There was %d error out of a possible %d.';
$lang['smiley_errors2'] = 'There were %d errors out of a possible %d.';


//
// Misc.
//
$lang['category'] = 'category';
$lang['categories'] = 'categories';
$lang['smiley'] = 'smiley';
$lang['smilies'] = 'smilies';
$lang['col_emotion'] = 'Emotion';


//
// Return Links
//
$lang['Click_return_cat_add'] = 'Click %sHere%s to Add Another Category';
$lang['Click_return_cat_edit'] = 'Click %sHere%s to Edit Another Category';
$lang['Click_return_upload'] = 'Click %sHere%s to return to Upload Smilies';
$lang['Click_return_export'] = 'Click %sHere%s to Export Another Category';
$lang['Click_return_import'] = 'Click %sHere%s to Import Another Category';
$lang['Click_return_mass_edit'] = 'Click %sHere%s to return to Smiley Editing';
$lang['Click_return_cat_view'] = 'Click %sHere%s to return to Smiley Management';
$lang['Click_return_unused'] = 'Click %sHere%s to return to Unused Smilies';


//
// Advanced .pak2 file.
//
$lang['pak_header'] = "#############################################################\r\n## This file was produced using the Smiley Categories MOD for\r\n## phpBB2. DO NOT attempt to import it into a forum that does\r\n## not have this MOD installed. DO NOT alter any of the lines\r\n## below unless you know what you're doing and know how the\r\n## MOD works.\r\n#############################################################\r\n";

?>
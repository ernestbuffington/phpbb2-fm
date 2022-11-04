<?php
/** 
*
* @package lang_english
* @version $Id: lang_xd.php,v 1.1.0 2004/01/06 zayin Exp $
* @copyright (c) 2003 Daniel Lewis
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

// Permissions words
$lang['xd_permissions'] = 'Custom profile fields: Users\' permissions';
$lang['xd_permissions_describe'] = 'Here you can alter the ability of users to fill in custom profile fields.';
$lang['field_name'] = 'Field Name';
$lang['Allow'] = 'Allow';
$lang['Default'] = 'Default';
$lang['Deny'] = 'Deny';
$lang['Click_return_xdata_uperms'] = 'Click %sHere%s to return to Users Profile Field User Permissions';
$lang['Click_return_xdata_gperms'] = 'Click %sHere%s to return to Users Profile Fields Group Permissions';

$lang['XD_auth_Control_Group'] = 'Custom profile fields: Groups\' permissions';
$lang['XD_group_auth_explain'] = 'Here you can alter the profile field permission status assigned to each user group. ';

// Edit/Add words
$lang['Basic_Options'] = 'Basic Options';
$lang['Advanced_Options'] = 'Advanced Options';
$lang['Advanced_warning'] = 'Don\'t change anything here unless you know what you\'re doing.';
$lang['edit_xdata_field'] = 'Edit Profile Field';
$lang['Name'] = 'Name';
$lang['xd_description'] = 'Description';
$lang['type'] = 'Type';
$lang['Text'] = 'Text';
$lang['Text_area'] = 'Text Area';
$lang['Select'] = 'Select Box';
$lang['Radio'] = 'Radio Buttons';
$lang['Custom'] = 'Custom';
$lang['Length'] = 'Length';
$lang['Length_explain'] = 'The maximum length for a text or textarea field. Zero means unlimited.';
$lang['Values'] = 'Values';
$lang['Values_explain'] = 'The options that will appear for a select or radio field. Each option should be enclosed with single quotes [\'], and seperated by a comma.';
$lang['Default_auth'] = 'Default Permissions';
$lang['Default_auth_explain'] = 'Users will only be able to edit this field in their profiles if this option or their personal permission is set to "allow".';
$lang['Display_viewtopic_explain'] = 'When viewing posts';
$lang['Display_viewprofile_explain'] = 'When viewing profiles';
$lang['Display_register_explain'] = 'When editing profiles';
$lang['Display_type'] = 'Display Type';
$lang['Display_normal'] = 'Normal';
$lang['Display_none'] = 'None';
$lang['Display_root'] = 'TPL Variable';
$lang['Code_name'] = 'Name in Templates';
$lang['Code_name_explain'] = 'If any of the above is set to "TPL Variable", this will be tha name of the variable the data is asigned to.';
$lang['Regexp'] = 'Regular Expression';
$lang['Regexp_explain'] = 'Only values matching this regular expression will be allowed. (PCRE-Style)';
$lang['Add_success'] = 'Field added successfully';
$lang['Delete_success'] = 'Field deleted successfully';
$lang['Edit_success'] = 'Field information updated successfully';
$lang['Click_return_fields'] = 'Click %shere%s to return to Fields Administration';
$lang['Regexp_error'] = 'You have an error in your regular expression syntax:';
$lang['handle_input'] = 'Handle Input';
$lang['handle_input_explain'] = 'Select "yes" unless you want to make your own input handler for this field.';
$lang['Allow_smilies'] = 'Allow Smilies';
$lang['Allow_BBCode'] = 'Allow BBCode';
$lang['Allow_html'] = 'Allow HTML';

// Delete words
$lang['Confirm'] = 'Confirm';
$lang['Are_you_sure'] = 'Are you sure you want to delete field "%s"?';

// Main Menu words
$lang['Xdata_view_description'] = 'Here you can view and edit the extra fields available in users\' profiles.';
$lang['No_fields'] = 'No fields exist';

// Error
$lang['XD_duplicate_name'] = 'A field already exists with the template name you chose.'

?>
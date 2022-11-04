<?php
/** 
*
* @package lang_english
* @version $Id: lang_admin_linkdb.php,v 0.0.9 2004/05/10 00:31:19 CRLin Exp $
* @copyright (c) 2002 OOHOO, Stefan2k1, ddonker 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
//
// Categories
//
$lang['All_links'] = 'All Links';
$lang['Approved_links'] = 'Approved Links';
$lang['Unapproved_links'] = 'Unapproved Links';
$lang['Link_cat'] = 'Link in Category';
$lang['Link_mode'] = 'View';
$lang['Approve_selected'] = 'Approve Selected';
$lang['Unapprove_selected'] = 'Unapprove Selected';
$lang['Delete_selected'] = 'Delete Selected';
$lang['No_link'] = 'There are no links.';
$lang['Acattitle'] = 'Add Category'; 
$lang['Ecattitle'] = 'Edit Category';
$lang['Dcattitle'] = 'Delete Category'; 
$lang['Catexplain'] = 'You can use the Category Management section to add, edit, delete and reorder categories. In order to add links to your database, you must have at least one category created. You can select a link below to manage your categories.';
$lang['Catadded'] = 'The new category has been successfully added';
$lang['Catname'] = 'Category Name';
$lang['Catnameinfo'] = 'This will become the name of the category.';
$lang['Catparent'] = 'Parent Category';
$lang['Catparentinfo'] = 'If you want this category to be a sub-category, select the category you want it to be a sub-category of.';
$lang['None'] = 'None';
$lang['Catedited'] = 'The category you selected has been successfully edited';
$lang['Dellinks'] = 'What do you want to do with the links in this category?';
$lang['Do_cat'] = 'What do you want to do with the sub category in this category?';
$lang['Move_to'] = 'Move to';
$lang['Catsdeleted'] = 'The categories you selected have been successfully deleted';
$lang['Cdelerror'] = 'You didn\'t select any categories to delete';

//Catgories Permission
$lang['Select_a_Category'] = 'Select a Category';
$lang['Click_return'] = 'Click %sHere%s to return to the previous page';
$lang['Select_a_Category'] = 'Select a Category';
$lang['Category'] = 'Category';

// Configuration
$lang['lock_submit_site'] = 'Lock user submit site';
$lang['cat_col'] = 'How many columns of categories are to be listed';
$lang['split_links'] = 'Split Links';
$lang['allow_guest_submit_site'] = 'Allow guest to submit site';
$lang['Need_validate'] = 'Validate links?';
$lang['perform_url_validation'] = 'Perform URL Validation';
$lang['url_validation_setting'] = 'URL Validation Setting';
$lang['url_validation_setting_explain'] = 'set the parameters for the validation function';
$lang['schema_section_validation'] = 'schema section';
$lang['http_validation'] = 'http';
$lang['https_validation'] = 'https';
$lang['mailto_validation'] = 'mailto';
$lang['ftp_validation'] = 'ftp';
$lang['user_detection_validation'] = 'user detection';
$lang['password_validation'] = 'password';
$lang['address_section_validation'] = 'address section';
$lang['ip_address_validation'] = 'ip address';
$lang['port_number_validation'] = 'port';
$lang['file_path_validation'] = 'file path';
$lang['query_section_validation'] = 'query section';
$lang['fragment_anchor_validation'] = 'fragment(anchor)';
$lang['url_validation_mandatory'] = 'mandatory';
$lang['url_validation_not_allowed'] = 'not allowed';
$lang['url_validation_optional'] = 'optional';
$lang['url_validation_default'] = 'default';
$lang['legend'] = 'Legend';

$lang['allow_no_logo'] = 'Allow submit site without banner';
$lang['site_logo'] = 'Site Logo';
$lang['site_url'] = 'The URL of your website';
$lang['width'] = 'Max width of the banners';
$lang['height'] = 'Max height of the banners';
$lang['settings_link_page'] = 'Max links per page';
$lang['Default_sort_method'] = 'Default Sort Method';
$lang['Default_sort_order'] = 'Default Sort Order';
$lang['interval'] = 'How fast the banners are displayed';
$lang['display_logo'] = 'How many banners are displayed at once';
$lang['Allow_Edit_Link'] = 'Allow Link Editing';
$lang['Allow_Delete_Link'] = 'Allow Link Deletion';
$lang['Link_display_links_logo'] = 'Display Links site banner';
$lang['Link_email_notify'] = 'Admin E-mail notify of new link';
$lang['Link_pm_notify'] = 'Admin PM Notify of new link';
$lang['Link_allow_rate'] = 'Allow Link Rating';
$lang['Link_allow_comment'] = 'Allow Comment Link';
$lang['Max_char'] = 'Maximum number of charcters';
$lang['Max_char_info'] = 'If a user posted a comment in which characters is more that this it will give them an error message (Limit the comment).';

// Custom Field
$lang['Afieldtitle'] = 'Add Field';
$lang['Efieldtitle'] = 'Edit Field';
$lang['Dfieldtitle'] = 'Delete Field';
$lang['Fieldexplain'] = 'You can use the custom fields management section to add, edit, and delete custom fields. You can use custom fields to add more information about a link. For example, if you want an information field to put the link\'s url in, you can create the custom field and then you can add the link url on the Add/Edit link page.';
$lang['Fieldname'] = 'Field Name';
$lang['Fieldnameinfo'] = 'This is the name of the field, for example \'Link URL\'';
$lang['Fielddesc'] = 'Field Description';
$lang['Fielddescinfo'] = 'This is a description of the field, for example \'Link URL\'';
$lang['Fieldadded'] = 'The custom field has been successfully added';
$lang['Fieldedited'] = 'The custom field you selected has been successfully edited';
$lang['Dfielderror'] = 'You didn\'t select any fields to delete';
$lang['Fieldsdel'] = 'The custom fields you selected have been successfully deleted';

$lang['Field_data'] = 'Options';
$lang['Field_data_info'] = 'Enter the options that the user can choose from. Separate each option with a new-line (carriage return).';
$lang['Field_regex'] = 'Regular Expression';
$lang['Field_regex_info'] = 'You may require the input field to match a regular expression %s(PCRE)%s.';
$lang['Field_order'] = 'Display Order';
$lang['Addtional_field'] = 'Additional Field';
$lang['select_fields'] = 'Please Select at least one field.';
$lang['select_field'] = 'Please Select one field.';

//Fields Types
$lang['Input'] = 'Single-Line Text Box';
$lang['Textarea'] = 'Multiple-Line Text Box';
$lang['Radio'] = 'Single-Selection Radio Buttons';
$lang['Select'] = 'Single-Selection Menu';
$lang['Select_multiple'] = 'Multiple-Selection Menu';
$lang['Checkbox'] = 'Multiple-Selection Checkbox';

// Link
$lang['AddLink'] = 'Add Link';
$lang['Elinktitle'] = 'Edit Link';
$lang['Dlinktitle'] = 'Delete Link';
$lang['Linkexplain'] = 'You can use the Link Management section to add, edit, and delete links.';
$lang['Sitename'] = 'Site Name';
$lang['Sitenameinfo'] = 'This is the name of the link you are adding, please don\'t submit <span style="color: #FF0000">sex</span>';
$lang['Siteld'] = 'Site Description';
$lang['Siteldinfo'] = 'This is a description of the link. This will go on the link\'s information page so this description can be longer';
$lang['Link_logo'] = "Site Logo";
$lang['Link_logo_src'] = "%sx%s pixels"; // %sx%s  replaced with config settings
$lang['Link_logo_src1'] = "%sx%s pixels, you can leave blank"; // %sx%s  replaced with config settings
$lang['Preview_logo'] = "Preview Logo";
$lang['Siteurl'] = 'Site URL';
$lang['Sitecat'] = 'Category';
$lang['Sitecatinfo'] = 'This is the category the link belongs in.';
$lang['Linkadded'] = 'The new link has been successfully added - we\'re taking you back to the category you submitted the link to';
$lang['Linkdeleted'] = 'The link has been successfully deleted';
$lang['Linkedited'] = 'The link you selected has been successfully edited';
$lang['Lderror'] = 'You didn\'t select any links to delete';
$lang['Linksdeleted'] = 'The links you selected have been successfully deleted';
$lang['Linkpin'] = 'Sticky Link';
$lang['Linkpininfo'] = 'Choose if you want the link stuck or not. Sticky links will always be shown at the top of the link list.';
$lang['Approved'] = 'Approved';
$lang['Approved_info'] = 'Use this option to make the link available for users, and also to approve a link that has been submitted by the users.';
$lang['Link_hits'] = 'Hits';
$lang['delete_link_approve'] = 'Are you sure you want to delete this link?';
$lang['delete_link_cancel'] = 'No Action has been taken.';
$lang['delete_links'] = 'Are you sure you want to delete these links?';
$lang['select_links'] = 'Please Select at least one link.';

//Java script messages and php errors
$lang['Cat_name_missing'] = 'Please fill the category name field';
$lang['Missing_field'] = 'Please complete all the required fields';
$lang['Link_same_cat'] = 'You can\'t move the links to the same deleted category.';
$lang['Link_move_cat'] = 'You can\'t move the sub category to the same deleted category.';

?>
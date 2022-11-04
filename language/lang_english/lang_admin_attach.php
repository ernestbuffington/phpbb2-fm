<?php
/** 
*
* @package lang_english
* @version $Id: lang_admin_attach.php,v 1.36 2003/08/30 15:47:39 acydburn Exp $
* @copyright (c) 2002 Meik Sievertsen
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* DO NOT CHANGE
*/
if (!isset($lang) || !is_array($lang))
{
	$lang = array();
}

// Modules, this replaces the keys used
$lang['Shadow_attachments'] = 'Orphaned attachments';
$lang['Sync_attachments'] = 'Synchronize';

// Attachments -> Management
$lang['Manage_attachments_explain'] = 'Here you can configure the configuration for Attachments. If you press the Test Settings button, it will do a few system tests to be sure that the Attachments will work properly. If you have problems with uploading files, please run this test to get a detailed error-message.';
$lang['Attach_filesize_settings'] = 'Attachment Filesize Settings';
$lang['Attach_number_settings'] = 'Attachment Number Settings';
$lang['Attach_options_settings'] = 'Attachment Options';

$lang['Upload_directory_explain'] = 'Path under your phpBB root dir, e.g. uploads/attachments/';
$lang['Attach_img_path'] = 'Attachment Posting Icon';
$lang['Attach_img_path_explain'] = 'This Image is displayed next to Attachment Links in individual Postings. Leave this field empty if you don\'t want an icon to be displayed. This Setting will be overwritten by the Settings in Extension Groups Management.';
$lang['Attach_topic_icon'] = 'Attachment Topic Icon';
$lang['Attach_topic_icon_explain'] = 'This Image is displayed before topics with Attachments. Leave this field empty if you don\'t want an icon to be displayed.';
$lang['Attach_display_order'] = 'Attachment Display Order';
$lang['Attach_display_order_explain'] = 'Display attachments ordered by time.';
$lang['Show_apcp'] = 'Show new Attachment Posting Control Panel';
$lang['Show_apcp_explain'] = 'Choose whether to display the Attachment Posting Control Panel (yes) or the old method with two Boxes for Attaching Files and editing your posted Attachments (no) within the Posting Screen.';

$lang['Max_filesize_attach'] = 'Maximum Filesize';
$lang['Max_filesize_attach_explain'] = 'Maximum size of each file, with 0 being unlimited.';
$lang['Attach_quota'] = 'Total Attachment Quota';
$lang['Attach_quota_explain'] = 'Maximum drive space available for attachments for the whole board, with 0 being unlimited.';
$lang['Max_filesize_pm'] = 'Maximum Filesize Messaging';
$lang['Max_filesize_pm_explain'] = 'Maximum drive space available per user for private message attachments, with 0 being unlimited.';
$lang['Default_quota_limit'] = 'Default Quota Limit';
$lang['Default_quota_limit_explain'] = 'Here you are able to select the default quota limit automatically assigned to newly registered users and users without an defined quota limit. The option \'No Quota Limit\' is for not using any quotas, instead using the default settings you have defined here.';

$lang['Max_attachments'] = 'Maximum Attachments per Post';
$lang['Max_attachments_pm'] = 'Maximum Attachments per Message';

$lang['Disable_mod'] = 'Allow Attachments';
$lang['PM_Attachments'] = 'Allow Attachments in Private Messages';
$lang['Ftp_upload'] = 'Enable FTP Upload';
$lang['Ftp_upload_explain'] = 'Enable/Disable the FTP Upload option. If you set it to yes, you have to define the Attachment FTP Settings and the Upload Directory is no longer used.';
$lang['Attachment_topic_review'] = 'Allow Attachments in the Topic Review Window';

$lang['Ftp_server'] = 'FTP Upload Server';
$lang['Ftp_server_explain'] = 'Here you can enter the IP-Address or FTP-Hostname of the Server used for your uploaded files. If you leave this field empty, the Server on which your phpBB2 Board is installed will be used. Please note that it is not allowed to add ftp:// or something else to the address, just plain ftp.foo.com or, which is a lot faster, the plain IP Address.';

$lang['Attach_ftp_path'] = 'FTP Path to your upload directory';
$lang['Attach_ftp_path_explain'] = 'The Directory where your Attachments will be stored. This Directory doesn\'t have to be chmodded. Please don\'t enter your IP or FTP-Address here, this input field is only for the FTP Path.<br />For example: /home/web/uploads';
$lang['Ftp_download_path'] = 'Download Link to FTP Path';
$lang['Ftp_download_path_explain'] = 'Enter the URL to your FTP Path, where your Attachments are stored.<br />If you are using a Remote FTP Server, please enter the complete url, for example http://www.mystorage.com/phpBB2/upload.<br />If you are using your Local Host to store your Files, you are able to enter the url path relative to your phpBB2 Directory, for example \'upload\'.<br />A trailing slash will be removed. Leave this field empty, if the FTP Path is not accessible from the Internet. With this field empty you are unable to use the physical download method.';
$lang['Ftp_passive_mode'] = 'Enable FTP Passive Mode';
$lang['Ftp_passive_mode_explain'] = 'The PASV command requests that the remote server open a port for the data connection and return the address of that port. The remote server listens on that port and the client connects to it.';

$lang['No_ftp_extensions_installed'] = 'You are not able to use the FTP Upload Methods, because FTP Extensions are not compiled into your PHP Installation.';

// Attachments -> Shadow Attachments
$lang['Shadow_attachments_explain'] = 'Here you can delete attachment data from postings when the files are missing from your filesystem, and delete files that are no longer attached to any postings. You can download or view a file if you click on it; if no link is present, the file does not exist.';
$lang['Shadow_attachments_file_explain'] = 'Delete all attachments files that exist on your filesystem and are not assigned to an existing post.';
$lang['Shadow_attachments_row_explain'] = 'Delete all posting attachment data for files that don\'t exist on your filesystem.';
$lang['Empty_file_entry'] = 'Empty File Entry';

// Attachments -> Sync
$lang['Sync_thumbnail_resetted'] = 'Thumbnail resetted for Attachment: %s'; // replace %s with physical Filename
$lang['Attach_sync_finished'] = 'Attachment Syncronization Finished.';
$lang['Sync_topics'] = 'Sync Topics';
$lang['Sync_posts'] = 'Sync Posts';
$lang['Sync_thumbnails'] = 'Sync Thumbnails';

// Extensions -> Extension Control
$lang['Manage_extensions'] = 'Manage extensions';
$lang['Manage_extensions_explain'] = 'Here you can manage your File Extensions. If you want to allow/disallow a Extension to be uploaded, please use the Extension Groups Management.';
$lang['Explanation'] = 'Explanation';
$lang['Extension_group'] = 'Extension Group';
$lang['Invalid_extension'] = 'Invalid Extension';
$lang['Extension_exist'] = 'The Extension %s already exist'; // replace %s with the Extension
$lang['Unable_add_forbidden_extension'] = 'The Extension %s is forbidden, you are not able to add it to the allowed Extensions'; // replace %s with Extension

// Extensions -> Extension Groups Management
$lang['Manage_extension_groups'] = 'Manage extension groups';
$lang['Manage_extension_groups_explain'] = 'Here you can add, delete and modify your Extension Groups, you can disable Extension Groups, assign a special Category to them, change the download mechanism and you can define a Upload Icon which will be displayed in front of an Attachment belonging to the Group.';
$lang['Special_category'] = 'Special Category';
$lang['Category_images'] = 'Images';
$lang['Category_stream_files'] = 'Stream Files';
$lang['Category_swf_files'] = 'Flash Files';
$lang['Allowed'] = 'Allowed';
$lang['Allowed_forums'] = 'Allowed Forums';
$lang['Ext_group_permissions'] = 'Group Permissions';
$lang['Download_mode'] = 'Download Mode';
$lang['Upload_icon'] = 'Upload Icon';
$lang['Max_groups_filesize'] = 'Maximum Filesize';
$lang['Extension_group_exist'] = 'The Extension Group %s already exist'; // replace %s with the group name
$lang['Collapse'] = '+';
$lang['Decollapse'] = '-';

// Extensions -> Special Categories
$lang['Manage_categories'] = 'Manage special categories';
$lang['Manage_categories_explain'] = 'Here you can configure the Special Categories. You can set up Special Parameters and Conditions for the Special Categorys assigned to an Extension Group.';
$lang['Settings_cat_images'] = 'Settings for Special Category: Images';
$lang['Settings_cat_streams'] = 'Settings for Special Category: Stream Files';
$lang['Settings_cat_flash'] = 'Settings for Special Category: Flash Files';
$lang['Display_inlined'] = 'Display Images Inlined';
$lang['Display_inlined_explain'] = 'Choose whether to display images directly within the post (yes) or to display images as a link ?';
$lang['Max_image_size'] = 'Maximum Image Dimensions';
$lang['Max_image_size_explain'] = 'Here you can define the maximum allowed Image Dimension to be attached (Width x Height in pixels).<br />If it is set to 0x0, this feature is disabled. With some Images this Feature will not work due to limitations in PHP.';
$lang['Image_link_size'] = 'Maximum Image Display Dimensions';
$lang['Image_link_size_explain'] = 'If this defined Dimension of an Image is reached, the Image will be shrunk automatically with its original ratio, if Inline View is enabled (Width x Height in pixels).<br />If it is set to 0x0, there are no restrictions. With some Images this Feature will not work due to limitations in PHP.';
$lang['Assigned_group'] = 'Assigned Group';

$lang['Image_create_thumbnail'] = 'Create Thumbnail';
$lang['Image_create_thumbnail_explain'] = 'Always create a Thumbnail. This feature overrides nearly all Settings within this Special Category, except of the Maximum Image Dimensions. With this Feature a Thumbnail will be displayed within the post, the User can click it to open the real Image.<br />Please Note that this feature requires Imagick to be installed, if it\'s not installed or if Safe-Mode is enabled the GD-Extension of PHP will be used. If the Image-Type is not supported by PHP, this Feature will be not used.';
$lang['Image_min_thumb_filesize'] = 'Minimum Thumbnail Filesize';
$lang['Image_min_thumb_filesize_explain'] = 'If a Image is smaller than this defined Filesize, no Thumbnail will be created, because it\'s small enough.';
$lang['Image_imagick_path'] = 'Imagick Program (Complete Path)';
$lang['Image_imagick_path_explain'] = 'Enter the Path to the convert program of imagick, normally /usr/bin/convert (on windows: c:/imagemagick/convert.exe).';
$lang['Image_search_imagick'] = 'Search Imagick';

$lang['Use_gd2'] = 'Make use of GD2 Extension';
$lang['Use_gd2_explain'] = 'PHP is able to be compiled with the GD1 or GD2 Extension for image manipulating. To correctly create Thumbnails without imagemagick Attachments uses two different methods, based on your selection here. If your thumbnails are in a bad quality or screwed up, try to change this setting.';
$lang['Attachment_version'] = 'File Attachment Version %s'; // %s is the version number

// Extensions -> Forbidden Extensions
$lang['Manage_forbidden_extensions'] = 'Manage forbidden extensions';
$lang['Manage_forbidden_extensions_explain'] = 'Here you can add or delete the forbidden extensions. The Extensions php, php3 and php4 are forbidden by default for security reasons, you can not delete them.';
$lang['Forbidden_extension_exist'] = 'The forbidden Extension %s already exist'; // replace %s with the extension
$lang['Extension_exist_forbidden'] = 'The Extension %s is defined in your allowed Extensions, please delete it their before you add it here.';  // replace %s with the extension

// Extensions -> Extension Groups Control -> Group Permissions
$lang['Group_permissions_title'] = 'Extension Group Permissions -> \'%s\''; // Replace %s with the Groups Name
$lang['Group_permissions_explain'] = 'Here you are able to restrict the selected Extension Group to Forums of your choice (defined in the Allowed Forums Box). The Default is to allow Extension Groups to all Forums the User is able to Attach Files into (the normal way the Attachments did it since the beginning). Just add those Forums you want the Extension Group (the Extensions within this Group) to be allowed there, the default ALL FORUMS will disappear when you add Forums to the List. You are able to re-add ALL FORUMS at any given Time. If you add a Forum to your Board and the Permission is set to ALL FORUMS nothing will change. But if you have changed and restricted the access to certain Forums, you have to check back here to add your newly created Forum. It is easy to do this automatically, but this will force you to edit a bunch of Files, therefore i have chosen the way it is now. Please keep in mind, that all of your Forums will be listed here.';
$lang['Note_admin_empty_group_permissions'] = 'NOTE:<br />Within the below listed Forums your Users are normally allowed to attach files, but since no Extension Group is allowed to be attached there, your Users are unable to attach anything. If they try, they will receive Error Messages. Maybe you want to set the Permission \'Post Files\' to ADMIN at these Forums.<br /><br />';
$lang['Add_forums'] = 'Add Forums';
$lang['Add_selected'] = 'Add Selected';
$lang['Perm_all_forums'] = 'ALL FORUMS';

// Attachments -> Quota Limits
$lang['Manage_quotas'] = 'Manage quota limits';
$lang['Manage_quotas_explain'] = 'Here you are able to add/delete/change Quota Limits. You are able to assign these Quota Limits to Users and Groups later. To assign a Quota Limit to a User, you have to go to Users->Management, select the User and you will see the Options at the bottom. To assign a Quota Limit to a Group, go to Groups->Management, select the Group to edit it, and you will see the Configuration Settings. If you want to see, which Users and Groups are assigned to a specific Quota Limit, click on \'View\' at the left of the Quota Description.';
$lang['Assigned_users'] = 'Assigned Users';
$lang['Assigned_groups'] = 'Assigned Groups';
$lang['Quota_limit_exist'] = 'The Quota Limit %s exist already.'; // Replace %s with the Quota Description

// Attachments -> Control Panel
$lang['Control_panel_title'] = 'Control panel';
$lang['Control_panel_explain'] = 'Here you can view and manage all attachments based on Users, Attachments, Views etc...';
$lang['File_comment_cp'] = 'File Comment';

// Control Panel -> Search
$lang['Search_wildcard_explain'] = 'Use * as a wildcard for partial matches';
$lang['Size_smaller_than'] = 'Attachment size smaller than (bytes)';
$lang['Size_greater_than'] = 'Attachment size greater than (bytes)';
$lang['Count_smaller_than'] = 'Download count is smaller than';
$lang['Count_greater_than'] = 'Download count is greater than';
$lang['More_days_old'] = 'More than this many days old';
$lang['No_attach_search_match'] = 'No Attachments met your search criteria';
$lang['No_user_files'] = 'You have not posted any files';
	
// Control Panel -> Statistics
$lang['Number_of_attachments'] = 'Number of attachments';
$lang['Total_filesize'] = 'Attachment directory size';
$lang['Number_posts_attach'] = 'Number of posts with attachments';
$lang['Number_topics_attach'] = 'Number of topics with attachments';
$lang['Number_users_attach'] = 'Independent users posted attachments';
$lang['Number_pms_attach'] = 'Number of private messages with attachments';
$lang['Attachments_per_day'] = 'Attachments per day';

// Control Panel -> Attachments
$lang['Statistics_for_user'] = 'Attachment Statistics for %s'; // replace %s with username
$lang['Size_in_kb'] = 'Size (KB)';
$lang['Downloads'] = 'Downloads';
$lang['Post_time'] = 'Post Time';
$lang['Posted_in_topic'] = 'Posted in Topic';
$lang['Submit_changes'] = 'Submit Changes';

// Sort Types
$lang['Sort_Attachments'] = 'Attachments';
$lang['Sort_Size'] = 'Size';
$lang['Sort_Filename'] = 'Filename';
$lang['Sort_Comment'] = 'Comment';
$lang['Sort_Extension'] = 'Extension';
$lang['Sort_Downloads'] = 'Downloads';
$lang['Sort_Posttime'] = 'Post Time';
$lang['Sort_Posts'] = 'Posts';

// View Types
$lang['View_Statistic'] = 'Statistics';
$lang['View_Search'] = 'Search';
$lang['View_Username'] = 'Username';
$lang['View_Attachments'] = 'Attachments';

// Successfully updated
$lang['Attach_config_updated'] = 'Attachment Configuration updated successfully';
$lang['Click_return_attach_config'] = 'Click %sHere%s to return to Attachment Configuration';
$lang['Test_settings_successful'] = 'Settings Test finished, configuration seems to be fine.';

// Some basic definitions
$lang['Attachment'] = 'Attachment';
$lang['Extensions'] = 'Extensions';
$lang['Extension'] = 'Extension';

// Auth pages
$lang['Auth_attach'] = 'Post Files';
$lang['Auth_download'] = 'Download Files';

?>
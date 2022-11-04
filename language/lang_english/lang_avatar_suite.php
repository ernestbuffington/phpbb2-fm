<?php
/** 
*
* @package lang_english
* @version $Id: lang_avatar_suite.php,v 1.85.2.15 2003/06/10 00:31:19 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

//
// Main
//
// Avatar Suite
//
// The following are selections the voters can make. If you don't like this scheme 
// ("bad" "average" "good" "top") then simply change the wording and/or the values:
// As you see, you can choose your own values AND your own descriptions AND your own number 
// of descriptions. The mod will adapt automatically. However, once you decided for a scheme 
// and users already voted for it, you CANNOT change the values later. 
// You may only ADD new values
//
$lang['avatarsuite_vote_value'][-1] = 'bad';                                      
$lang['avatarsuite_vote_value'][0] = 'average';                                   
$lang['avatarsuite_vote_value'][1] = 'good';                                      
$lang['avatarsuite_vote_value'][2] = 'top';                                       

/*
// Example alternative #1 (just an example)
$lang['avatarsuite_vote_value'][1] = 'OK';
$lang['avatarsuite_vote_value'][2] = 'Better';
$lang['avatarsuite_vote_value'][3] = 'Best';

// Example alternative #2
$lang['avatarsuite_vote_value'][-10] = 'OK';
$lang['avatarsuite_vote_value'][10] = 'Not OK';

// Example alternative #3
$lang['avatarsuite_vote_value'][-10] = 'Valueless';
$lang['avatarsuite_vote_value'][0] = 'Bad';
$lang['avatarsuite_vote_value'][5] = 'Good';
$lang['avatarsuite_vote_value'][10] = 'Best';
$lang['avatarsuite_vote_value'][20] = 'Cream';

// Example alternative #4
$lang['avatarsuite_vote_value'][1] = '1';
$lang['avatarsuite_vote_value'][2] = '2';
$lang['avatarsuite_vote_value'][3] = '3';
$lang['avatarsuite_vote_value'][4] = '4';
$lang['avatarsuite_vote_value'][5] = '5';
*/

// Avatar Toplist
$lang['Click_avatar_toplist'] = 'Click %sHere%s for the Avatar Toplist';
$lang['avatarsuite_vote_pagetitle_toplist'] = 'Avatar Toplist - Sorted by user\'s votes';
$lang['avatarsuite_vote_pagetitle_toplist_of_1_user'] = 'Toplist of %s\'s avatars - Sorted by the votes of other users';
$lang['avatarsuite_vote_pagetitle_vote'] = 'Avatar Voting';
$lang['avatarsuite_vote_title'] = 'Avatar voting';
$lang['L_AVATARLIST'] = 'All Avatars';

$lang['avatarsuite_vote_ratehere'] = 'Rate this avatar!<br />Don\'t consider other avatars, or this user\'s statements';
$lang['avatarsuite_vote_addcomment'] = 'Type a comment (if you wish) and press [enter]';

$lang['avatarsuite_vote_whereuploaded'] = 'Uploaded by ';
$lang['avatarsuite_vote_whereforeign'] = 'Hosted on other server by';
$lang['avatarsuite_vote_wheregallery'] = 'Selected from our avatar gallery by';

$lang['avatarsuite_vote_unchanged'] = 'Thank you. You already voted for this avatar. Your vote hasen\'t change. It is still "%s".';
$lang['avatarsuite_vote_commentaccepted'] = 'Thank you. Comment added.';
$lang['avatarsuite_vote_accepted'] = 'Thank you. Your vote has been accepted. You rated this avatar as "%s".';
$lang['avatarsuite_vote_updated'] = 'Thank you. Your previous vote has been changed. Your current rating is "%s".';

$lang['avatarsuite_vote_comments'] = 'Comments';

$lang['avatarsuite_vote_usedby'] = 'Used by';
$lang['avatarsuite_vote_points'] = 'points';
$lang['avatarsuite_vote_point'] = 'point';
$lang['avatarsuite_vote_voter'] = 'voter';
$lang['avatarsuite_vote_voters'] = 'voters';

$lang['avatarsuite_vote_next'] = '[Next] ---->';
$lang['avatarsuite_vote_previous'] = '<---- [Previous]';
$lang['avatarsuite_vote_start'] = '[Top]';

$lang['avatarsuite_vote_randomunvoted'] = 'Random avatars you haven\'t voted for yet';
$lang['avatarsuite_vote_randomunvotedfinished'] = 'You have voted for all avatars in this forum';
$lang['avatarsuite_vote_randomunvoteddisabled'] = 'Random voting is disabled at the moment';
$lang['avatarsuite_vote_randomunvotedlogin'] = 'You have to log in to vote';
$lang['avatarsuite_vote_revote'] = '[Re-vote avatars on this page]';
$lang['avatarsuite_vote_unrevote'] = '[Turn off re-voting mode]';
$lang['avatarsuite_vote_showgender_female'] = 'How did female voters vote?';
$lang['avatarsuite_vote_showgender_male'] = 'How did male voters vote?';
$lang['avatarsuite_vote_showgender_all'] = 'Show female & male voters';
$lang['avatarsuite_vote_showgender_maletext'] = 'Show results from female voters only: Show toplist without the votes of male voters';
$lang['avatarsuite_vote_showgender_femalestext'] = 'Show results from male voters only: Show toplist without the votes of female voters';
$lang['avatarsuite_vote_showgender_alltext'] = 'Show both female and male voters';
$lang['avatarsuite_vote_listall'] = '[Show all users]';

$lang['avatarsuite_listavatars_title'] = 'List of all Avatars of this forum';
$lang['avatarsuite_listavatars_timesused'] = '%d time%s used';
$lang['avatarsuite_listavatars_unusedstandardavatar'] = 'Current user avatar. Not used yet.';
$lang['avatarsuite_listavatars_seemoreavatars'] = 'This user has %d more avatar%s...';
$lang['avatarsuite_listavatars_see_remaining_avatars'] = 'See the remaining %d avatar%s --->';
$lang['avatarsuite_listavatars_seemoreusers'] = 'See more users ---->';
$lang['avatarsuite_listavatars_showall'] = '<---- Show all users';

// The following are error messages
$lang['avatarsuite_vote_error_missingfilename'] = 'Error: You have to specify a filename.';
$lang['avatarsuite_vote_error_noavatarsfound'] = 'Error: No avatar found. Did any user use any (persistent) avatar yet?';
$lang['avatarsuite_vote_error_noavatarsfoundforthisuser'] = 'Error: No avatar found for this user. Did this user use any (persistent) avatar yet? Did anyone vote for this user\'s avatars?';
$lang['avatarsuite_vote_error_wrongvalue'] = 'Error: You can only use allowed values (%s).';
$lang['avatarsuite_vote_error_missingvote'] = 'Error: You have to specify how you like the avatar (%s).';
$lang['avatarsuite_vote_error_missingtype'] = 'Error: You have to specify a type.';
$lang['avatarsuite_vote_error_noself'] = 'Error: You cannot vote for your own avatars.';
$lang['avatarsuite_vote_error_0found'] = 'Error: There is no such avatar.';
$lang['avatarsuite_vote_error_loggedout'] = 'Error: You have to login to cast a vote.';
$lang['avatarsuite_vote_error_sqlcreate'] = 'Error: Couldn\'t create SQL TABLE.';
$lang['avatarsuite_vote_error_sqlnotaccepted'] = 'Something went wrong. You vote wasn\'t cast.';
$lang['avatarsuite_vote_error_novalidvotes'] = 'No avatars are in the toplist yet. Did anyone vote yet?';

// Avatar Generator
$lang['Avatar_Generator'] = 'Avatar generator';
$lang['Random'] = 'Random Avatar';
$lang['Avatar_Text'] = 'Please enter the text you would like on your Avatar:';
$lang['Preview'] = 'Preview Avatar';
$lang['Your_Avatar'] = 'Your Avatar';


//
// Admin
//
// Configuration
$lang['Avatar_settings_explain'] = 'Avatars are generally small, unique images a user can associate with themselves. Depending on the style they are usually displayed below the username when viewing topics. Here you can determine how users can define their avatars. Please note that in order to upload avatars you need to have created the directory you name below and ensure it can be written to by the web server. Please also note that filesize limits are only imposed on uploaded avatars, they do not apply to remotely linked images.';

$lang['Uploading'] = 'Avatar upload';
$lang['Default_avatar'] = 'Default avatar';
$lang['Default_avatar_explain'] = 'This gives users that haven\'t selected an avatar, a default one. Set the default avatar for guests and users, and then select whether you want the avatar to be displayed for registered users, guests, both or none.';
$lang['Default_avatar_guests'] = 'Guests';
$lang['Default_avatar_users'] = 'Users';
$lang['Default_avatar_both'] = 'Both';
$lang['Allow_local'] = 'Enable gallery avatars';
$lang['Allow_remote'] = 'Enable remote avatars';
$lang['Allow_remote_explain'] = 'Avatars linked to from another website.';
$lang['Allow_upload'] = 'Enable avatar uploading';
$lang['Allow_generator'] = 'Enable avatar generator';
$lang['Max_filesize'] = 'Maximum avatar file size';
$lang['Max_filesize_explain'] = 'For uploaded avatar files.';
$lang['Max_avatar_size'] = 'Maximum avatar dimensions';
$lang['Max_avatar_size_explain'] = '(Height x Width in pixels)';
$lang['Avatar_storage_path'] = 'Avatar storage path';
$lang['Avatar_storage_path_explain'] = 'Path under your phpBB root dir, e.g. <code>images/avatars</code>';
$lang['Avatar_gallery_path'] = 'Avatar gallery path';
$lang['Avatar_gallery_path_explain'] = 'Path under your phpBB root dir for pre-loaded images, e.g. <code>images/avatars</code>';
$lang['Avatar_generator_template_path'] = 'Avatar generator path';
$lang['Avatar_generator_template_path_explain'] = 'Path under your phpBB root dir for template images, e.g. <code>images/avatar_generator</code>';
$lang['Avatars_Sticky'] = 'Enable sticky avatars'; 
$lang['Avatars_Sticky_Explain'] = 'Users can choose to have their avatar details stored along with their posts.'; 
$lang['Avatars_per_page'] = 'Avatars per gallery page';
$lang['Disable_avatar_approve'] = 'Enable avatar approval system';
$lang['Disable_avatar_approve_explain'] = 'Require avatars be approved first, with an e-mail sent to all Admins. The user can still view and edit their avatar in their profile, but it will be hidden from all other users.';
$lang['Avatar_posts'] = 'Minimum post count for an avatar';
$lang['Avatar_posts_explain'] = 'This has no effect on gallery avatars. Set to zero to disable.';
$lang['Allow_toplist'] = 'Enable avatars toplist link';
$lang['Allow_toplist_explain'] = 'Displays a navigation link to the avatar toplist in the header.';
$lang['Allow_viewtopic_voting'] = 'Enable avatar voting link';
$lang['Allow_viewtopic_voting_explain'] = 'Adds a voting link under avatars on posts.';
$lang['Avatar_register'] = 'Enable avatar selection for registration';
$lang['Avatar_register_explain'] = 'Allows new members to select a avatar from the gallery when registering.';

// View Avatars
$lang['Avatar_viewer_explain'] = 'Click on an avatar or user\'s name to edit that user\'s profile.';

// Unused Avatars
$lang['Avatar_Delete_explain'] = 'From this panel you can delete all the unused avatars which have been uploaded to the avatar directory but now are unused, because there not stored in the database any more. Unused avatars will be deleted from the directory: <b>/%s</b>'; // %s replaced with Avatar Upload path
$lang['Avatar_Delete_3'] = 'Filename';
$lang['Avatar_Delete_4'] = 'Size';
$lang['Avatar_Delete_5'] = 'You have not selected any avatars to delete.';

// Unite Double Avatars
$lang['Unite_double_avatars_explain'] = 'The form below will allow you to unificate avatars that have been uploaded twice.';
$lang['Unite_avatars'] = 'Unite';
$lang['avatarsuite_unite_admin_united'] = 'Avatars United Successfully.';
$lang['avatarsuite_unite_admin_nodoubles'] = 'No user(s) found who have uploaded the same avatar twice.';
$lang['avatarsuite_unite_admin_doublesfound'] = 'The following avatars have been uploaded twice. If you click "Unite" then this affects the database alone. No avatar will be physically deleted from the harddisk.';
$lang['avatarsuite_unite_admin_times used'] = 'times used';

?>
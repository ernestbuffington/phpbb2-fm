<?php
/** 
*
* @package lang_english
* @version $Id: lang_main.php,v 1.85.2.15 2003/06/10 00:31:19 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
* CONTRIBUTORS:
* Add your details here if wanted, e.g. Name, username, email address, website
* 2002-08-27: Philip M. White - fixed many grammar problems
*
* This is optional, if you would like a _SHORT_ message output along with our 
* copyright message indicating you are the translator please add it here.
*
*/
//$lang['TRANSLATION_INFO'] = '';  

//
// Registration agreement content
//
$lang['Reg_agreement'] = 'While the administrators and moderators of %s will attempt to remove or edit any generally objectionable material as quickly as possible, it is impossible to review every message. Therefore you acknowledge that all posts made to %s express the views and opinions of the author and not the administrators, moderators or webmaster (except for posts by these people) and hence will not be held liable.<br /><br />You agree not to post any abusive, obscene, vulgar, slanderous, hateful, threatening, sexually-oriented or any other material that may violate any applicable laws. Doing so may lead to you being immediately and permanently banned (and your service provider being informed). The IP address of all posts is recorded to aid in enforcing these conditions. You agree that the webmaster, administrators and moderators of %s have the right to remove, edit, move or close any topic at any time should they see fit. As a user you agree to any information you have entered above being stored in a database. While this information will not be disclosed to any third party without your consent the webmaster, administrators and moderators cannot be held responsible for any hacking attempt that may lead to the data being compromised.<br /><br />This forum system uses cookies to store information on your local computer. These cookies do not contain any of the information you have entered above; they serve only to improve your viewing pleasure. The e-mail address is used only for confirming your registration details, password (and for sending new passwords should you forget your current one) and forum mailouts. On occasion the Board Administrator may add you to a notification list for specific topics, you agree to receive those e-mails at least once, after that you can unsubribe to them immediately.<br /><br />By clicking below you agree to be bound by these conditions.';
	
//
// New user welcome PM subject and content
// - use %s for sitename
//
$lang['register_pm_subject'] = 'Welcome to %s';  
$lang['register_pm'] = 'Hello %s!<br /><br />Welcome to %s.<br /><br />We hope you enjoy your time at this site!<br /><br />Feel free to join in and share with others or start your own discussion!<br /><br />~Enjoy!<br />%s Staff';    

	
//
// The format of this file is ---> $lang['message'] = 'text';
//
// You should also try to set a locale and a character encoding (plus diivetion). The encoding and direction
// will be sent to the template. The locale may or may not work, it's dependent on OS support and the syntax
// varies ... give it your best guess!
//
$lang['ENCODING'] = 'iso-8859-1';
$lang['DIRECTION'] = 'ltr';
$lang['LEFT'] = 'left';
$lang['RIGHT'] = 'right';
$lang['DATE_FORMAT'] =  'd M Y'; // This should be changed to the default date format for your language, php date() format
$lang['DATE_SQL_FORMAT'] = '%M %e, %Y'; // This should be changed to the default date format for SQL for your language 
$lang['DATE_INPUT_FORMAT'] = 'd/m/y'; // Requires 'd', 'm', and 'y' and a punctuation delimiter, order can change 
$lang['JOINED_DATE_FORMAT'] = 'M Y'; // Default date format for user joined date, php date() format 


//
// Common, these terms are used
// extensively on several pages
//
$lang['Forum'] = 'Forum';
$lang['Forum_Index'] = 'Board Index'; 
$lang['Category'] = 'Category';
$lang['Topic'] = 'Topic';
$lang['Topics'] = 'Topics';
$lang['Replies'] = 'Replies';
$lang['Views'] = 'Views';
$lang['Post'] = 'Post';
$lang['Posts'] = 'Posts';
$lang['Posted'] = 'Posted';
$lang['Username'] = 'Username';
$lang['Password'] = 'Password';
$lang['Email'] = 'E-mail';
$lang['PM'] = 'PM'; 
$lang['Poster'] = 'Poster';
$lang['Author'] = 'Author';
$lang['Time'] = 'Time';
$lang['Hours'] = 'Hours';
$lang['Message'] = 'Message';
$lang['Favorites'] = 'Favorites'; 
$lang['1_Day'] = '1 Day';
$lang['7_Days'] = '7 Days';
$lang['2_Weeks'] = '2 Weeks';
$lang['1_Month'] = '1 Month';
$lang['3_Months'] = '3 Months';
$lang['6_Months'] = '6 Months';
$lang['1_Year'] = '1 Year';

$lang['Go'] = 'Go';
$lang['Jump_to'] = 'Jump to';
$lang['Submit'] = 'Submit';
$lang['Reset'] = 'Reset';
$lang['Cancel'] = 'Cancel';
$lang['Preview'] = 'Preview';
$lang['Confirm'] = 'Confirm';
$lang['Spellcheck'] = 'Spellchecker';
$lang['Yes'] = 'Yes';
$lang['No'] = 'No'; 
$lang['By'] = 'by';
$lang['Enable'] = 'Enable';
$lang['Disable'] = 'Disable';
$lang['Enabled'] = 'Enabled';
$lang['Disabled'] = 'Disabled';
$lang['Error'] = 'Error';
$lang['Action'] = 'Action'; 
$lang['Edit'] = 'Edit';
$lang['Delete'] = 'Delete';
$lang['Order'] = 'Order';
	
$lang['Next'] = 'Next';
$lang['Previous'] = 'Previous';
$lang['Goto_page'] = 'Goto page';
$lang['Joined'] = 'Joined';
$lang['Member_for'] = 'Member for';
$lang['IP_Address'] = 'IP Address';

$lang['Select_forum'] = 'Select a forum';
$lang['View_latest_post'] = 'View latest post';
$lang['View_newest_post'] = 'View newest post';
$lang['Page_of'] = 'Page <b>%d</b> of <b>%d</b>'; // Replaces with: Page 1 of 2 for example

$lang['ICQ'] = 'ICQ Number';
$lang['AIM'] = 'AIM Address';
$lang['MSNM'] = 'Windows Live Messenger';
$lang['YIM'] = 'Yahoo Messenger';
$lang['XFI'] = 'XFire Address';
$lang['GTALK'] = 'Google Talk';

$lang['skype'] = 'Skype Name';
$lang['skype_falsch'] = 'Please enter your correct Skype Name';
$lang['skype_seitentitel'] = 'Skype&trade;'; 
$lang['skype_add'] = 'Add to contact list'; 
$lang['skype_call'] = 'Call'; 
$lang['skype_userinfo'] = 'User Info'; 
$lang['skype_chat'] = 'Start text-chat'; 
$lang['skype_sendfile'] = 'Send a file'; 
$lang['skype_voicemail'] = 'Leave voice message'; 
$lang['skype_no'] = 'This user has no skype.'; 
$lang['skype_error'] = 'This user does not exist!'; 
$lang['skype_oben'] = 'Here you can contact <b>%s</b> over <a href="http://www.skype.com" class="gen" target="_blank">Skype&trade;</a>'; 
$lang['skype_unten'] = 'This function works only if you have installed <a href="http://www.skype.com" class="gen" target="_blank">Skype&trade;</a> on your PC.'; 

$lang['Post_new_topic'] = 'Post new topic';
$lang['Reply_to_topic'] = 'Reply to topic';
$lang['Reply_with_quote'] = 'Reply with quote';
$lang['Quick_Reply_to_topic'] = 'Quick reply to topic'; 
$lang['Click_return_topic'] = 'Click %sHere%s to return to the topic'; // %s's here are for uris, do not remove!
$lang['Click_return_login'] = 'Click %sHere%s to try again';
$lang['Click_return_forum'] = 'Click %sHere%s to return to the forum';
$lang['Click_view_message'] = 'Click %sHere%s to view your message';
$lang['Click_return_modcp'] = 'Click %sHere%s to return to the Moderator Control Panel';
$lang['Click_return_group'] = 'Click %sHere%s to return to group information';
$lang['Click_return_usercp'] = 'Click %sHere%s to return to your Control Panel';

$lang['Admin_panel'] = 'Admin CP'; 
$lang['Stats_panel'] = 'Statistics CP';  
$lang['Board_disable'] = 'Sorry, but this board is currently unavailable. Please try again later.';
$lang['Autobackup_disabled'] = 'Sorry, but the auto backup feature of this board has been disabled. Please try again later.';
$lang['disable_reg_msg'] = 'Sorry, but the register feature of this board has been disabled. Please try again later.';  
$lang['force_email_info_message'] = 'Sorry, but you are not permitted to use this feature of the board.<br /><br />This is because the e-mail address you have registered in your profile is invalid.';
$lang['Click_update_email'] = 'Click %sHere%s to update your e-mail address.';
$lang['Refresh_page'] = 'Refresh page'; 
$lang['Site_Announcement'] = 'Board Announcement'; 

$lang['Page_permissions_page_disabled'] = 'This page is temporarily offline for maintenance.';
$lang['Page_permissions_insufficient_privileges'] = 'You do not have authorization to view this page.';
$lang['Page_permissions_post_count_too_low'] = 'You do not have enough posts to view this page yet.';
$lang['Page_permissions_post_count_too_high'] = 'You have too many posts to view this page.';
$lang['Page_permissions_insecure_url'] = 'Attempt to redirect to a potentially insecure URL; access denied';
$lang['phpbbdoctor_cache_file_missing'] = '%s is missing!';
$lang['phpbbdoctor_cache_cannot_open'] = 'Cannot open %s for writing';
$lang['phpbbdoctor_cache_failed_write'] = 'Failed writing contents to %s';
$lang['phpbbdoctor_cache_not_writable'] = 'The file %s is not writeable, try setting permissions (chmod) 0666 to fix';

$lang['Be_visible'] = 'Switch to visible';
$lang['Be_invisible'] = 'Switch to invisible';

$lang['Expand_all'] = 'Expand All';
$lang['Collapse_all'] = 'Collapse All';

$lang['All_content'] = 'All content is copyright'; 
$lang['Original_author'] = 'and its original authors'; 


//
// Global Header strings
//
$lang['Registered_users'] = 'Registered Users: ';
$lang['Browsing_forum'] = 'Users browsing this forum: '; 
$lang['Browsing_topic'] = 'Users browsing this topic: '; 
$lang['Online_users_zero_total'] = 'In total there are <b>0</b> users online :: ';
$lang['Online_users_total'] = 'In total there are <b>%d</b> users online :: ';
$lang['Online_user_total'] = 'In total there is <b>%d</b> user online :: ';
$lang['Reg_users_zero_total'] = '0 Registered, ';
$lang['Reg_users_total'] = '%d Registered, ';
$lang['Reg_user_total'] = '%d Registered, ';
$lang['Hidden_users_zero_total'] = '0 Hidden and ';
$lang['Hidden_user_total'] = '%d Hidden and ';
$lang['Hidden_users_total'] = '%d Hidden and ';
$lang['Guest_users_zero_total'] = '0 Guests';
$lang['Guest_users_total'] = '%d Guests';
$lang['Guest_user_total'] = '%d Guest';
$lang['Record_online_users'] = 'Most users ever online was <b>%s</b> on %s'; // first %s = number of users, second %s is the date.
$lang['Record_day_users'] = 'Most users ever online in one day was <b>%s</b> on %s'; // first %s = number of users, second %s is the date.  	 
$lang['new_members_key'] = 'New members today <b>%T%</b> | Yesterday <b>%Y%</b> | This week <b>%W%</b>'; // %t = today, y% = yesterday, %w = this week

$lang['Admin_online_color'] = '%sAdministrators%s'; 
$lang['Super_Mod_online_color'] = '%sJunior Administrators%s';
$lang['Mod_online_color'] = '%sModerators%s';
$lang['Games_online_color'] = 'Game Players';
$lang['Bot_online_color'] = 'Bots'; 

$lang['You_last_visit'] = 'Last visit was: %s'; // %s replaced by date/time
$lang['You_points'] = 'Total %s: %d'; // %s replaced by points name, %d replaced by points value   
$lang['Current_time'] = 'It is currently %s'; // %s replaced by time

$lang['All_forums'] = 'All Forums'; 
$lang['Make_Homepage'] = 'Make Homepage';  
$lang['BBCode_guide'] = 'BBCode Guide';
$lang['Last_Post'] = 'Last Post';
$lang['Moderator'] = 'Moderator';
$lang['Moderators'] = 'Moderators';
$lang['Moderators_Manual'] = 'Moderator Manual';
$lang['Today_at'] = 'Today at %s'; // %s is the time
$lang['Yesterday_at'] = 'Yesterday at %s'; // %s is the time

$lang['Search_new'] = 'View posts since last visit'; 
$lang['Search_unanswered'] = 'View unanswered posts'; 
$lang['View_last_24_hours'] = 'View last 24 hours'; 
$lang['View_random_topic'] = 'View a random topic';   
$lang['Register'] = 'Register';
$lang['Profile'] = 'Profile';
$lang['Edit_profile'] = 'Edit your profile';

$lang['Watching'] = 'Watching';  
$lang['No_Watched_Topics'] = 'You are not watching any topics.';
$lang['No_Watched_Forums'] = 'You are not watching any forums.';
$lang['Search_your_posts'] = 'Your Posts'; 
$lang['Your_topics'] = 'Your Topics';  


//
// Navigation Links
//
$lang['Search'] = 'Search';
$lang['Javascript:tour()'] = 'Tour';
$lang['Helpdesk'] = 'Helpdesk';
$lang['Portal'] = 'Portal'; 
$lang['Index'] = 'Index';
$lang['Faq'] = 'FAQ'; 
$lang['Calendar'] = 'Calendar'; 
$lang['Album'] = 'Album'; 
$lang['Charts'] = 'Charts'; 
$lang['Smilies'] = 'Smilies';
$lang['Groupcp'] = 'Groups';
$lang['Toplist'] = 'Toplist';
$lang['Dload'] = 'Downloads';  
$lang['Chatroom'] = 'Chatroom'; 
$lang['Statistics'] = 'Statistics'; 
$lang['Bank'] = 'Bank'; 
$lang['Shop'] = 'Shops'; 
$lang['Lottery'] = 'Lottery'; 
$lang['Banlist'] = 'Ban List'; 
$lang['#'] = 'Radio'; 
$lang['Imlist'] = 'IM List'; 
$lang['Top_referrals'] = 'Top Referrals';
$lang['Activity'] = "Games";
$lang['Trohpy'] = 'Game Trophies';
$lang['Bookies'] = 'Bookmakers';
$lang['Ratings'] = 'Ratings'; 
$lang['Auctions'] = 'Auctions'; 
$lang['Lite_version'] = 'Switch to Lite';
$lang['Linkdb'] = 'Links';
$lang['jobs'] = 'Jobs';
$lang['L_AVATARTOPLIST'] = 'Top Avatars';
$lang['Guestbook'] = 'Guestbook';
$lang['Memberlist'] = 'Members';
$lang['Kb'] = 'Knowledge base'; 


//
// Stats block text
//
$lang['Posted_articles_zero_total'] = 'Total posts <b>0</b>'; // Number of posts
$lang['Posted_articles_total'] = 'Total posts <b>%d</b>'; // Number of posts
$lang['Posted_article_total'] = 'Total posts <b>%d</b>'; // Number of posts
$lang['Posted_topics_total'] = ' Total topics <b>%s</b>'; 
$lang['Registered_users_zero_total'] = 'Total members <b>0</b>'; // # registered users
$lang['Registered_users_total'] = 'Total members <b>%d</b>'; // # registered users
$lang['Registered_user_total'] = 'Total members <b>%d</b>'; // # registered user
$lang['male_zero_total'] = ' [ <b>0</b> males &amp;'; // # registered male users
$lang['male_total'] = ' [ <b>%d</b> males &amp;'; // # registered male users
$lang['male_one_total'] = ' [ <b>%d</b> male &amp;'; // # registered male user
$lang['female_zero_total'] = ' <b>0</b> females ]'; // # registered female users
$lang['female_total'] = ' <b>%d</b> females ]'; // # registered female users
$lang['female_one_total'] = ' <b>%d</b> female ]'; // # registered female user
$lang['Newest_user'] = 'Our newest member <b>%s%s%s</b>'; // a href, username, /a 
$lang['Newest_user_unconfirmed'] = 'Unconfirmed'; 
$lang['Newest_user_since'] = 'registered on';
$lang['Registered_groups_zero_total'] = 'Total groups <b>0</b>'; // # usergroups
$lang['Registered_groups_total'] = 'Total groups <b>%d</b>'; // # usergroups
$lang['Registered_group_total'] = 'Total groups <b>%d</b>'; // # usergroup
$lang['Type_groups'] = '[ <b>%d</b> payment, <b>%d</b> open, <b>%d</b> closed and <b>%d</b> hidden ]'; // Types of groups
$lang['Newest_group'] = 'Our newest group is <b>%s%s%s</b>'; // a href, group, /a 

$lang['Within'] = 'within'; 
$lang['No_new_posts_last_visit'] = 'No new posts since your last visit';
$lang['No_new_posts'] = 'No new posts';
$lang['New_posts'] = 'New posts';
$lang['New_post'] = 'New post';
$lang['No_new_posts_hot'] = 'No new posts [ Popular ]';
$lang['New_posts_hot'] = 'New posts [ Popular ]';
$lang['No_new_posts_locked'] = 'No new posts [ Locked ]';
$lang['New_posts_locked'] = 'New posts [ Locked ]';
$lang['Forum_is_locked'] = 'Forum is locked';

$lang['Forum_one_active'] = '<b>%d</b> User active: ';  
$lang['Forum_more_active'] = '<b>%d</b> Users active: ';  
$lang['Forum_one_hidden_active'] = '<b>%d</b> Hidden';  
$lang['Forum_more_hidden_active'] = '<b>%d</b> Hidden';  
$lang['Forum_one_guest_active'] = '<b>%d</b> Guest';  
$lang['Forum_more_guests_active'] = '<b>%d</b> Guests';  


//
// Login
//
$lang['Enter_password'] = 'Please enter your username and password to log in.';
$lang['Admin_reauthenticate'] = 'To administer the board you must re-authenticate yourself.';
$lang['Login'] = 'Log In';
$lang['Logout'] = 'Log Out';

$lang['Forgotten_password'] = 'I forgot my password';
$lang['Resend_Activation'] = 'Resend activation email';

$lang['Log_me_in'] = 'Log me on automatically each visit';
$lang['Log_me_in_auto'] = 'Auto Login';
$lang['Terms_of_use'] = 'Terms of Use';
$lang['Error_login'] = 'You have specified an incorrect or inactive username, or an invalid password.';
$lang['Login_attempts_exceeded'] = 'The maximum number of %s login attempts has been exceeded. You are not allowed to login for the next %s minutes.';
$lang['Error_password_update'] = 'Before you can continue browsing the board you are required to change your password.<br /><br />You will be redirected to your profile now.';

//
// Index page
//
$lang['No_Posts'] = 'No Posts';
$lang['No_forums'] = 'This board has no forums';
$lang['Toggle_description'] = 'Toggle Description';

$lang['Private_Message'] = 'Private Message';
$lang['Private_Messages'] = 'Private Messages';
$lang['Who_is_Online'] = 'Who is Online';
$lang['Legend'] = 'Legend'; 
$lang['Latest_pic'] = 'Random Photo'; 
$lang['Clock'] = 'Current Time'; 
$lang['Post_comment'] = 'Post comment'; 
$lang['Mark_all_forums'] = 'Mark all forums read';
$lang['Forums_marked_read'] = 'All forums have been marked read.';
$lang['Topic_Jump'] = 'Jump To Topic';  
$lang['Teamspeak'] = 'TeamSpeak';
$lang['Connecting'] = 'Connecting to server...';
$lang['Shoutcast'] = 'Shoutcast';
$lang['Shoutcast_track'] = 'Track title';
$lang['Shoutcast_listen'] = 'Listen';

$lang['External_text'] = 'Total redirects';
$lang['External_members'] = 'Users';
$lang['External_guests'] = 'Guests';
$lang['Visitor_banned'] = 'Sorry, you have arrived here from a prohibited domain. Visitors from prohibited domains are not welcome here';

$lang['Index_New_posts'] = 'new posts';
$lang['Index_New_post'] = 'new post';
$lang['Index_New_topics'] = 'new topics';
$lang['Index_New_topic'] = 'new topic';


//
// Viewforum
//
$lang['View_forum'] = 'View Forum';

$lang['Forum_not_exist'] = 'The forum you selected does not exist.';
$lang['Reached_on_error'] = 'You have reached this page in error.';
$lang['Forum_issub'] = 'The forum you selected contains sub categories.<br />Therefore it is not possible to view posts.'; 
$lang['Display_topics'] = 'Display topics from previous';
$lang['All_Topics'] = 'All Topics';
$lang['Forum_rules'] = 'Forum Rules';

$lang['Topic_global_announcement']='<b>Global Announcement:</b>'; 
$lang['Topic_Announcement'] = '<b>Announcement:</b>';
$lang['Topic_Sticky'] = '<b>Sticky:</b>';
$lang['Topic_Moved'] = '<b>Moved:</b>';
$lang['Topic_Linked'] = '<b>Linked:</b>'; 
$lang['Topic_Poll'] = '<b>[ Poll ]</b>';

$lang['Mark_all_topics'] = 'Mark all topics read';
$lang['Topics_marked_read'] = 'The topics for this forum have now been marked read.';

$lang['Rules_post_can'] = 'You <b>can</b> post new topics in this forum';
$lang['Rules_post_cannot'] = 'You <b>cannot</b> post new topics in this forum';
$lang['Rules_reply_can'] = 'You <b>can</b> reply to topics in this forum';
$lang['Rules_reply_cannot'] = 'You <b>cannot</b> reply to topics in this forum';
$lang['Rules_bump_cannot'] = 'You <b>cannot</b> reply to your own posts in this forum';
$lang['Rules_edit_can'] = 'You <b>can</b> edit your posts in this forum';
$lang['Rules_edit_cannot'] = 'You <b>cannot</b> edit your posts in this forum';
$lang['Rules_delete_can'] = 'You <b>can</b> delete your posts in this forum';
$lang['Rules_delete_cannot'] = 'You <b>cannot</b> delete your posts in this forum';
$lang['Rules_vote_can'] = 'You <b>can</b> vote in polls in this forum';
$lang['Rules_vote_cannot'] = 'You <b>cannot</b> vote in polls in this forum';
$lang['Rules_moderate'] = 'You <b>can</b> %smoderate this forum%s'; // %s replaced by a href links, do not remove! 

$lang['No_topics_post_one'] = 'There are no posts in this forum.<br />Click on the <b>New Topic</b> link on this page to post one.';
$lang['Who_posted'] = 'Who posted?'; 
$lang['Who_posted_msg'] = 'show topic & close window';

$lang['Stop_watching_forum'] = 'Stop watching this forum';
$lang['Start_watching_forum'] = 'Watch this forum for new topics';
$lang['No_longer_watching_forum'] = 'You are no longer watching this forum for new topics.';
$lang['You_are_watching_forum'] = 'You are now watching this forum for new topics.';


//
// Password protected forums
//
$lang['Forum_password'] = 'Forum Password';
$lang['Enter_forum_password'] = 'Enter Forum Password';
$lang['Enter_password'] = 'Password';
$lang['Forum_password_explain'] = 'To view or post in this forum you must enter a password.';
$lang['Incorrect_forum_password'] = 'You have specified an invalid password.';
$lang['Password_login_success'] = 'Password was correct.';
$lang['Click_return_page'] = 'Click %sHere%s to continue';
$lang['Only_alpha_num_chars'] = 'The password must be between 3-20 characters and can only contain alphanumeric characters (A-Z, a-z, 0-9).';


//
// Password-protected topics
//
$lang['Topic_password'] = 'Topic password';
$lang['Enter_topic_password'] = 'Enter Topic Password';
$lang['Not_auth_edit_post'] = 'You are not authorised to edit this post.';
$lang['Not_delete_password_topics'] = 'You are not authorised to delete password protected topics';


//
// AJAXed
//
$lang['AJAXed_delete_confirm'] = 'Are you sure you want to delete this topic and it\'s posts?';
$lang['AJAXed_deleted_topic'] = 'You have successfully deleted this topic and it\'s post in it.';
$lang['AJAXed_loading'] = 'Loading...';
$lang['AJAXed_editor_premission'] = '<b>You don\'t have premission to edit this post.</b><br /><br />';
$lang['AJAXed_check_username1'] = 'This username is taken already.';
$lang['AJAXed_check_username2'] = 'This username is yours already.';
$lang['AJAXed_check_username3'] = 'This username has not been taken yet.';
$lang['AJAXed_no_username'] = 'No username with these letters';
$lang['AJAXed_post_menu'] = 'Post Menu';
$lang['AJAXed_post_ip'] = 'Poster\'s IP Address';
$lang['AJAXed_post_back'] = 'Go Back';
$lang['AJAXed_quick_edit'] = 'Quick Edit';
$lang['AJAXed_normal_edit'] = 'Normal Edit';
$lang['AJAXed_view_ip'] = 'View IP Address';
$lang['AJAXed_error'] = 'There was an error with AJAX.';
$lang['AJAXed_poll_menu'] = 'Poll Menu';
$lang['AJAXed_poll_mod'] = 'Poll Mod';
$lang['AJAXed_poll_title'] = 'Edit Poll Title';
$lang['AJAXed_poll_options'] = 'Edit Poll Options';
$lang['AJAXed_close'] = 'Close';
$lang['AJAXed_poll_confirm'] = 'Are you sure you want to delete this poll option?';
$lang['AJAXed_poll_cast'] = 'Cast Your Vote';
$lang['AJAXed_poll_select'] = 'You must select a poll option before casting your vote.';
$lang['Vote_min_posts_needed'] = 'You need %s posts to vote in a poll.';
$lang['AJAXed_Timed_out'] = 'AJAX has timed out. Please try again later.';
$lang['AJAXed_moduale_disabled'] = 'This module is disabled.';
$lang['AJAXed_check_true'] = 'The passwords you entered match!';
$lang['AJAXed_check_false'] = 'The passwords you entered didn\'t match.';
$lang['AJAXed_add_update'] = 'Show this message has been updated?';
$lang['AJAXed_Go_To_Top'] = 'Go to the top of the preview';
$lang['AJAXed_Go_To_Editor'] = 'Go to the editor';
$lang['AJAXed_Go_To_Full_Mode'] = 'Go to full mode';
$lang['AJAXed_Invaild_ID'] = 'Invalid ID';


//
// Viewtopic
//
$lang['View_topic'] = 'View topic';

$lang['Guest'] = 'Guest';
$lang['Post_subject'] = 'Post subject'; 
$lang['No_Subject'] = '(No subject)';
$lang['View_next_topic'] = 'View next topic';
$lang['View_previous_topic'] = 'View previous topic';
$lang['Submit_vote'] = 'Submit Vote';
$lang['View_results'] = 'View Results';
$lang['Vote_until'] ='Vote until';  
$lang['Vote_endless'] = 'No ending time set';  
$lang['Vote_closed'] = 'Voting is closed';  
$lang['No_newer_topics'] = 'There are no newer topics in this forum.';
$lang['No_older_topics'] = 'There are no older topics in this forum.';
$lang['Topic_post_not_exist'] = 'The topic or post you requested does not exist.';
$lang['No_posts_topic'] = 'No posts exist for this topic.';
$lang['ftr_here'] = 'Here';

$lang['Display_posts'] = 'Display posts from previous';
$lang['All_Posts'] = 'All Posts';
$lang['Newest_First'] = 'Newest First';
$lang['Oldest_First'] = 'Oldest First';

$lang['Back_to_top'] = 'Back to top';
$lang['Back_at_bottom'] = 'Go to the bottom'; 
$lang['Read_profile'] = 'View user\'s profile'; 
$lang['Visit_website'] = 'Visit poster\'s website';
$lang['View_stumble'] = 'View poster\'s StumbleUpon';
$lang['ICQ_status'] = 'ICQ Status';
$lang['Edit_delete_post'] = 'Edit/Delete this post';
$lang['View_IP'] = 'View IP address of poster';
$lang['Delete_post'] = 'Delete this post';
$lang['Referral_Viewtopic'] = 'Referral from user';
$lang['View_userinfo'] = 'details';

$lang['wrote'] = 'wrote'; // proceeds the username and is followed by the quoted text
$lang['Quote'] = 'Quote'; // comes before bbcode quote output.
$lang['Code'] = 'Code'; // comes before bbcode code output.
$lang['Spoiler'] = 'Spoiler'; // comes before bbcode spoiler output.
$lang['Reduced_image'] = 'Posted image may have been reduced in size. Click image to view fullscreen.';
$lang['Edited_time_total'] = 'Last edited by %s on %s; edited %d time in total'; // Last edited by me on 12 Oct 2001; edited 1 time in total
$lang['Edited_times_total'] = 'Last edited by %s on %s; edited %d times in total'; // Last edited by me on 12 Oct 2001; edited 2 times in total

$lang['Lock_topic'] = 'Lock this topic';
$lang['Unlock_topic'] = 'Unlock this topic';
$lang['Move_topic'] = 'Move this topic'; 
$lang['Link_topic'] = 'Link this topic'; 
$lang['Delete_topic'] = 'Delete this topic';
$lang['Split_topic'] = 'Split this topic';
$lang['Sticky_topic'] = 'Stick this topic'; 
$lang['Bump_topic'] = 'Bump this topic';
$lang['Unsticky_topic'] = 'Unstick this topic'; 
$lang['Announce_topic'] = 'Announce this topic'; 
$lang['Unannounce_topic'] = 'Unannounce this topic'; 
$lang['Globalannounce_topic'] = 'Globally Announce this topic';
$lang['Unglobalannounce_topic'] = 'Unglobally announce this topic';

$lang['Stop_watching_topic'] = 'Stop watching this topic';
$lang['Start_watching_topic'] = 'Watch this topic for replies';
$lang['No_longer_watching'] = 'You are no longer watching this topic.';
$lang['You_are_watching'] = 'You are now watching this topic.';
 
$lang['Topic_bookmark'] = 'Add this topic to your bookmarks';
$lang['Marked_answered'] = 'The topic has been marked to &quot;Answered&quot;.'; 
$lang['Marked_unanswered'] = 'This topic has been marked to &quot;Unanswered&quot;.'; 
$lang['Answered'] = 'Answered'; 
$lang['Unanswered'] = 'Unanswered'; 
$lang['Total_votes'] = 'Total Votes';

$lang['Print_View'] = 'Printable version';  
$lang['Member_number'] = 'Member: #';
$lang['Edit_post_date'] = 'Edit Post Date';
$lang['Style'] = 'Style';
$lang['jobs_unemployed'] = 'Unemployed';

$lang['Online'] = 'Online'; 
$lang['Offline'] = 'Offline'; 
$lang['Year_star'] = 'Year star'; 
$lang['is_online'] = '%s is online now';
$lang['is_offline'] = '%s is offline';
$lang['is_hidden'] = '%s is hidden';
$lang['View_status'] = 'Status';  

$lang['Topic_views'] = 'Topic Views'; 
$lang['Topic_viewers'] = 'Topic Viewers'; 
$lang['Topic_view_users'] = 'List users that have viewed this topic'; 
$lang['Last_viewed'] = 'Last viewed';  
$lang['Topic_view_users_disabled'] = 'Sorry, but the topic view feature of this board has been disabled. Please try again later.';

$lang['Rating'] = 'Rating'; 
$lang['No_rating'] = 'No Rating'; 
$lang['Rate_post'] = 'Rate Post'; 
$lang['Ratings_by'] = 'Posts rated by %s'; 
$lang['Rated_posts_by'] = 'Posts by %s that have been rated';   
$lang['Latest_ratings'] = 'Latest ratings';
$lang['Highest_ranked_topics'] = 'Highest-ranked topics';
$lang['Highest_ranked_posts'] = 'Highest-ranked posts';
$lang['Highest_ranked_posters'] = 'Highest-ranked posters';

$lang['Personal_gallery'] = 'Personal Photo Album';

$lang['View_single_post'] = 'View Post';
$lang['Single_post_disabled'] = 'Sorry, but the view post feature of this board has been disabled. Please try again later.';
	
$lang['Download_topic'] = 'Download Topic';
$lang['Download_post'] = 'Download Post';
$lang['Download_post_disabled'] = 'Sorry, but the download post feature of this board has been disabled. Please try again later.';

$lang['Similar'] = 'Similar Topics';

$lang['Add_all_ed2k_links'] = 'Add %s links to ed2k client';

$lang['Hide_sigav'] = 'Hide this users\' avatar and signature';
$lang['Unhide_user'] = 'Show this users\' avatar and signature';


//
// Posting/Replying (Not private messaging!)
// 
$lang['Topic_icon'] = 'Topic icon'; 
$lang['Message_body'] = 'Message body';
$lang['Topic_review'] = 'Topic review';

$lang['No_post_mode'] = 'No post mode specified'; // If posting.php is called without a mode (newtopic/reply/delete/etc, shouldn't be shown normaly)

$lang['Post_a_new_topic'] = 'Post a new topic';
$lang['Post_a_new_note'] = 'Post a new note';
$lang['Post_a_reply'] = 'Post a reply';
$lang['Post_topic_as'] = 'Post topic as';
$lang['Edit_Post'] = 'Edit post';
$lang['Edit_Note'] = 'Edit note';
$lang['Options'] = 'Options';

$lang['Post_global_announcement'] = 'Global';
$lang['Post_Announcement'] = 'Announce';
$lang['Post_Sticky'] = 'Sticky'; 
$lang['Post_Poll'] = 'Poll';	
$lang['Post_Normal'] = 'Normal';
$lang['Post_linked'] = 'Linked'; 

$lang['Confirm_delete'] = 'Are you sure you want to delete this post?';
$lang['Confirm_delete_poll'] = 'Are you sure you want to delete this poll?';

$lang['Flood_Error'] = 'You cannot make another post so soon after your last; please try again in a short while.';
$lang['daily_flood_limit'] = 'You have already posted your daily alloted posts; please try again tomorrow.';
$lang['Bump_Error'] = 'You cannot reply to your own posts in this forum; please edit your last post.';

$lang['Empty_subject'] = 'You must specify a subject when posting a new topic.';
$lang['Empty_message'] = 'You must enter a message when posting.';
$lang['Message_Minlength_error'] = 'Your message is too short.<br /><br />Your message must have at least %d characters.';
$lang['Message_Maxlength_error'] = 'Your message is too long.<br /><br />The message size is restricted to %d characters'; 
$lang['Symbols_left'] = 'Characters remaining';
$lang['Forum_locked'] = 'This forum is locked: you cannot post, reply to, or edit topics.';
$lang['Topic_locked'] = 'This topic is locked: you cannot edit posts or make replies.';
$lang['Forum_admin'] = 'This forum is only viewable to board administrators'; 
$lang['No_post_id'] = 'You must select a post to edit';
$lang['No_topic_id'] = 'You must select a topic to reply to';
$lang['No_valid_mode'] = 'You can only post, reply, edit, or quote messages. Please return and try again.';
$lang['No_such_post'] = 'There is no such post. Please return and try again.';
$lang['Edit_own_posts'] = 'Sorry, but you can only edit your own posts.';
$lang['Delete_own_posts'] = 'Sorry, but you can only delete your own posts.';
$lang['Cannot_delete_replied'] = 'Sorry, but you may not delete posts that have been replied to.';
$lang['Cannot_delete_poll'] = 'Sorry, but you cannot delete an active poll.';
$lang['Empty_poll_title'] = 'You must enter a title for your poll.';
$lang['To_few_poll_options'] = 'You must enter at least two poll options.';
$lang['To_many_poll_options'] = 'You have tried to enter too many poll options.';
$lang['Post_has_no_poll'] = 'This post has no poll.';
$lang['Already_voted'] = 'You have already voted in this poll.';
$lang['No_vote_option'] = 'You must specify an option when voting.';
$lang['Too_many_image'] = 'You have added too many images in your post. You can only add %d image per post.';
$lang['Too_many_images'] = 'You have added too many images in your post. You can only add %d images per post.';
$lang['Image_too_large'] = 'You have posted an image that is too large. Images can only be %d pixels wide and %d pixels high.';

$lang['Links_Allowed_For_Registered_Only'] = 'Only registered users can see links posted on this board.';
$lang['Get_Registered'] = '%sRegister%s or ';
$lang['Enter_Forum'] = '%slogin%s to view posted links.';
$lang['Post_Limit'] = 'You must have <b>%s</b> posts to see links posted on this board.';

$lang['Add_poll'] = 'Add a Poll';
$lang['Add_poll_explain'] = 'If you do not want to add a poll to your topic, leave the fields blank.';
$lang['Poll_question'] = 'Poll question';
$lang['Poll_option'] = 'Poll option';
$lang['Add_option'] = 'Add option';
$lang['Update'] = 'Update';
$lang['Poll_for'] = 'Run poll for';
$lang['Days'] = 'Days'; // This is used for the Run poll for ... Days + in admin_forums for pruning
$lang['Poll_for_explain'] = 'Enter 0 or leave blank for a never ending poll';
$lang['Delete_poll'] = 'Delete Poll';
$lang['Poll_overview'] = 'Poll Overview';

$lang['Disable_HTML_post'] = 'Disable HTML';
$lang['Disable_BBCode_post'] = 'Disable BBCode';
$lang['Disable_Smilies_post'] = 'Disable Smilies';

$lang['HTML_is_ON'] = 'HTML is <u>ON</u>';
$lang['HTML_is_OFF'] = 'HTML is <u>OFF</u>';
$lang['BBCode_is_ON'] = '%sBBCode%s is <u>ON</u>'; // %s are replaced with URI pointing to FAQ
$lang['BBCode_is_OFF'] = '%sBBCode%s is <u>OFF</u>';
$lang['Smilies_are_ON'] = 'Smilies are <u>ON</u>';
$lang['Smilies_are_OFF'] = 'Smilies are <u>OFF</u>';
$lang['Smilies_are_REMOVED'] = 'Smilies are <u>REMOVED</u>';

$lang['Attach_signature'] = 'Attach signature (signatures can be altered via the UCP)';
$lang['Notify'] = 'Send me an e-mail when a reply is posted';

$lang['Stored'] = 'Your message has been entered successfully.';
$lang['Deleted'] = 'Your message has been deleted successfully.';
$lang['Poll_delete'] = 'Your poll has been deleted successfully.';
$lang['Vote_cast'] = 'Your vote has been cast.';
$lang['Null_vote_cast'] = 'Your null vote has been cast.';
$lang['Click_view_results'] = 'Click %sHere%s to view the poll results';
$lang['Null_vote'] = 'Null vote';

$lang['Topic_reply_notification'] = 'Topic Reply Notification';

$lang['bbcode_b_help'] = 'Bold text: [b]text[/b]  (alt+b)';
$lang['bbcode_i_help'] = 'Italic text: [i]text[/i]  (alt+i)';
$lang['bbcode_u_help'] = 'Underline text: [u]text[/u]  (alt+u)';
$lang['bbcode_q_help'] = 'Quote text: [quote]text[/quote]  (alt+q)';
$lang['bbcode_l_help'] = 'List: [list]text[/list] (alt+l)';
$lang['bbcode_o_help'] = 'Ordered list: [list=]text[/list]  (alt+o)';
$lang['bbcode_p_help'] = 'Insert image: [img ( | =left | =right )]http://image_url[/img]  (alt+p)';
$lang['bbcode_w_help'] = 'Insert URL: [url]http://url[/url] or [url=http://url]URL text[/url]  (alt+w)';
$lang['bbcode_a_help'] = 'Close all open BBCode tags';
$lang['bbcode_a1_help'] = 'Show all available BBCode tags';
$lang['bbcode_s_help'] = 'Font color: [color=red]text[/color] Tip: you can also use color=#FF0000';
$lang['bbcode_f_help'] = 'Font size: [size=x-small]small text[/size] Tip: this changes your text size';

$lang['bbcode_c_help'] = 'Align text: [align=center]text[/align] Tip: this aligns your text'; 
$lang['bbcode_f1_help'] = 'Font face: [font=arial]text[/font] Tip: this changes your font face'; 
$lang['bbcode_h1_help'] = 'Highlight color: [highlight=red]text[/highlight] Tip: you can also use color=#FF0000'; 
$lang['bbcode_g1_help'] = 'Glow color: [glow=red]text[/glow] Tip: this makes your text glow'; 
$lang['bbcode_s1_help'] = 'Shadow color: [shadow=red]text[/shadow] Tip: this makes your text shadowed'; 
$lang['bbcode_sc_help'] = 'Emoticon creator: [schild=1]text[/schild] Tip: Creates a smilie with a sign'; 

//
// bbcode help format goes like this
// $lang['bbcode_help']['value'] = 'BBCode Name: Info (Alt+%s)';
//
// value is what you put in $EMBB_values in includes/bbcode.php
// %s gets replaced with the automatic hotkey that the bbcode gets assigned
//
$lang['bbcode_help']['Code'] = 'Code display: [code]code[/code]  (alt+c)';
$lang['bbcode_help']['Blur'] = 'Blur text: [blur]text[/blur]  (alt+%s)'; 
$lang['bbcode_help']['Fade'] = 'Fade text: [fade]text[/fade]  (alt+%s)'; 
$lang['bbcode_help']['Flash'] = 'Flash image: [flash width=# height=#]http://flash_image_url[/flash]  (alt+%s)'; 
$lang['bbcode_help']['FlipV'] = 'Flip text vertically: [flipv]text[/flipv]  (alt+%s)'; 
$lang['bbcode_help']['FlipH'] = 'Flip text horizontally: [fliph]text[/fliph]  (alt+%s)'; 
$lang['bbcode_help']['Video'] = 'Insert video: [video width=# height=#]http://video_url[/video]  (alt+%s)'; 
$lang['bbcode_help']['Strike'] = 'Strike through text: [strike]text[/strike]  (alt+%s)';  
$lang['bbcode_help']['Stream'] = 'Stream audio: [stream]http://audio_url[/stream]  (alt+%s)'; 
$lang['bbcode_help']['Spoil'] = 'Spoiler text: [spoiler]text[/spoiler]  (alt+%s)'; 
$lang['bbcode_help']['Table'] = 'Tables: Read BBCode FAQ to use Table bbcode (alt+%s)';
$lang['bbcode_help']['Scroll'] = 'Scrolling text: [scroll]text[/scroll]  (alt+%s)'; 
$lang['bbcode_help']['Updown'] = 'Scrolling text updown: [updown]text[/updown]  (alt+%s)'; 
$lang['bbcode_help']['Hr'] = 'Insert Horizontal Line: [hr]  (alt+%s)'; 
$lang['bbcode_help']['Offtopic'] = 'Offtopic text: [offtopic]text[/offtopic]  (alt+%s)'; 
$lang['bbcode_help']['Username'] = 'Readers name: [username]  (alt+%s)';
$lang['bbcode_help']['Wave'] = 'Wave text: [wave]text[/wave]  (alt+%s)';
$lang['bbcode_help']['Tab'] = 'Tabbed text: [tab]text  (alt+%s)';  
$lang['bbcode_help']['Footnote'] = 'Footnote text: [footnote]text[/footnote] (alt+%s)';
$lang['bbcode_help']['Google'] = 'Google search: [google]String to search for[/google]  (alt+%s)';
$lang['bbcode_help']['Search'] = 'Board search: [search]String to search for[/search]  (alt+%s)';
$lang['bbcode_help']['Border'] = 'Border object: [border]object[/border]  (alt+%s)'; 
$lang['bbcode_help']['Yahoo'] = 'Yahoo search: [yahoo]String to search for[/yahoo]  (alt+%s)';
$lang['bbcode_help']['eBay'] = 'eBay search: [ebay]String to search for[/ebay]  (alt+%s)';
$lang['bbcode_help']['Edit'] = 'Editing text: [edit]text[/edit]  (alt+%s)';
$lang['bbcode_help']['YouTube'] = 'Insert YouTube Video: [youtube]VideoID[/youtube]  (alt+%s)';
$lang['bbcode_help']['GVideo'] = 'Insert Google Video: [googlevid]VideoID[/googlevid]  (alt+%s)';
$lang['bbcode_help']['pre'] = 'Preformatted text (preserves formatting): [pre]text[/pre]  (alt+%s)';
$lang['bbcode_help']['mod'] = 'Moderator message: [mod]text[/mod]  (alt+%s)';
$lang['bbcode_help']['mp3'] = 'Insert mp3 audio: [pm3]http://mp3_url[/mp3]  (alt+%s)';

$lang['BBCode_border'] = 'Border';
$lang['BBCode_blur'] = 'Blur';
$lang['BBCode_ebay'] = 'eBay';
$lang['BBCode_fade'] = 'Fade';
$lang['BBCode_flash'] = 'Flash';
$lang['BBCode_flipv'] = 'FlipV';
$lang['BBCode_fliph'] = 'FlipH';
$lang['BBCode_footnote'] = 'Footnote';
$lang['BBCode_google'] = 'Google';
$lang['BBCode_hr'] = 'Hr';
$lang['BBCode_offtopic'] = 'Offtopic';
$lang['BBCode_scroll'] = 'Scroll';
$lang['BBCode_spoil'] = 'Spoil';
$lang['BBCode_stream'] = 'Stream';
$lang['BBCode_strike'] = 'Strike';
$lang['BBCode_tab'] = 'Tab';
$lang['BBCode_table'] = 'Table';
$lang['BBCode_updown'] = 'Updown';
$lang['BBCode_video'] = 'Video';
$lang['BBCode_wave'] = 'Wave';
$lang['BBCode_yahoo'] = 'Yahoo';
$lang['BBCode_youtube'] = 'YouTube';
$lang['BBCode_googlevid'] = 'GVideo';
$lang['BBCode_pre'] = 'Pre';
$lang['BBCode_mp3'] = 'mp3';

$lang['Mod_no_edit'] = 'Sorry, your post has been moderated so you can\'t edit it.'; 
$lang['Mod_no_delete'] = 'Sorry, your post has been moderated so you can\'t delete it.';
$lang['Mod_reserved'] = 'Sorry, you are not allowed to use moderator tags.'; 
$lang['Mod_restrictions'] = 'Moderation restrictions';
$lang['Mod_warning'] = 'Moderator warning';

$lang['Emoticons'] = 'Emoticons'; 
$lang['Emoticons_disable'] = 'Sorry, but the emoticons feature of this board has been disabled.'; 
$lang['Smilie_creator'] = 'Emoticon Creator'; 
$lang['More_emoticons'] = 'View more Emoticons';
$lang['smiley_categories'] = 'Smiley Categories';
$lang['smiley_help'] = 'Select the category that you wish to have appear in the popup window.';
$lang['smiley_code_replacement'] = '(smiley)'; // If you wish to replace the code with a word, enter it here, or leave empty.

$lang['Highlight_color'] = 'Highlight color'; 
$lang['Shadow_color'] = 'Shadow color'; 
$lang['Glow_color'] = 'Glow color';  
$lang['Font_color'] = 'Font colour';
$lang['color_default'] = 'Default';
$lang['color_dark_red'] = 'Dark Red';
$lang['color_red'] = 'Red';
$lang['color_orange'] = 'Orange';
$lang['color_brown'] = 'Brown';
$lang['color_yellow'] = 'Yellow';
$lang['color_green'] = 'Green';
$lang['color_olive'] = 'Olive';
$lang['color_cyan'] = 'Cyan';
$lang['color_blue'] = 'Blue';
$lang['color_dark_blue'] = 'Dark Blue';
$lang['color_indigo'] = 'Indigo';
$lang['color_violet'] = 'Violet';
$lang['color_white'] = 'White';
$lang['color_black'] = 'Black';
$lang['color_cadet_blue'] = 'Cadet Blue'; 
$lang['color_coral'] = 'Coral'; 
$lang['color_crimson'] = 'Crimson'; 
$lang['color_tomato'] = 'Tomato'; 
$lang['color_sea_green'] = 'Sea Green'; 
$lang['color_dark_orchid'] = 'Dark Orchid'; 
$lang['color_chocolate'] = 'Chocolate';
$lang['color_deepskyblue'] = 'Deep Sky Blue';
$lang['color_gold'] = 'Gold';
$lang['color_gray'] = 'Gray';
$lang['color_midnightblue'] = 'Midnight Blue';
$lang['color_darkgreen'] = 'Dark Green';
$lang['color_light_blue'] = 'Light Blue';
$lang['color_light_green'] = 'Light Green';
$lang['color_pink'] = 'Pink';
$lang['color_purple'] = 'Purple';
$lang['color_light_grey'] = 'Light Grey';
$lang['color_dark_grey'] = 'Dark Grey';

$lang['Font_size'] = 'Font size';
$lang['font_tiny'] = 'Tiny';
$lang['font_small'] = 'Small';
$lang['font_normal'] = 'Normal';
$lang['font_large'] = 'Large';
$lang['font_huge'] = 'Huge';

$lang['Align_text'] = 'Align text'; 
$lang['text_left'] = 'Left'; 
$lang['text_center'] = 'Center'; 
$lang['text_right'] = 'Right'; 
$lang['text_justify'] = 'Justify'; 
 
$lang['Font_face'] = 'Font face'; 
$lang['font']['andalas'] = 'Andalus'; 
$lang['font']['comicsansms'] = 'Comic Sans MS'; 
$lang['font']['courier'] = 'Courier'; 
$lang['font']['cursive'] = 'Cursive'; 
$lang['font']['fantasy'] = 'Fantasy'; 
$lang['font']['garamond'] = 'Garamond'; 
$lang['font']['impact'] = 'Impact'; 
$lang['font']['lucidaconsole'] = 'Lucida Console'; 
$lang['font']['monospace'] = 'Monospace';
$lang['font']['microsoftsansserif'] = 'MS Sans Serif';
$lang['font']['symbol'] = 'Symbol';
$lang['font']['tahoma'] = 'Tahoma'; 
$lang['font']['timesnewroman'] = 'Times New Roman';
$lang['font']['trebuchetms'] = 'Trebuchet MS';
$lang['font']['verdana'] = 'Verdana'; 
$lang['font']['webdings'] = 'Webdings';
$lang['font']['wingdings'] = 'Wingdings'; 

$lang['Copy_to_clipboard'] = 'Copy to clipboard'; 
$lang['Copy_to_clipboard_explain'] = 'This post has been copied to your clipboard. If this post is lost when you submit it you can easily repost it. Always use this feature before posting!'; 
$lang['Highlight_text'] = 'Highlight text'; 
 
$lang['SC_shieldtext'] = 'Sign Text'; 
$lang['SC_fontcolor'] = 'Text Colour'; 
$lang['SC_shadowcolor'] = 'Shadow Colour'; 
$lang['SC_shieldshadow'] = 'Sign Shade'; 
$lang['SC_shieldshadow_on'] = 'Activated'; 
$lang['SC_shieldshadow_off'] = 'De-Activated'; 
$lang['SC_smiliechooser'] = 'Emoticon Chooser'; 
$lang['SC_random_smilie'] = 'Random Emoticon'; 
$lang['SC_default_smilie'] = 'Default Emoticon'; 
$lang['SC_create_smilie'] = 'Create';
$lang['SC_stop_creating'] = 'Cancel'; 

$lang['Close_Tags'] = 'Close Tags';
$lang['Expand_bbcode'] = 'More Tags';
$lang['Styles_tip'] = 'Tip: Styles can be applied quickly to selected text.';


//
// Private Messaging
// 
$lang['Private_Messaging'] = 'Private messaging'; 
$lang['Login_check_pm'] = 'Check your private messages';
$snd = getRandomSound();  
$lang['New_nsnd_pms'] = '<b>%d</b> new messages'; // You have 2 new messages no sound/marquee 
$lang['New_pms'] = "<b>%d</b> <span class=pm>*<blink> <marquee width=50 behavior=alternate>NEW</marquee> </blink>*</span> messages<EMBED SRC=$snd LOOP=false HIDDEN=true VOLUME=50 AUTOSTART=true WIDTH=0 HEIGHT=0 NAME=foobar MASTERSOUND>"; // You have 2 new message 
$lang['New_nsnd_pm'] = '<b>%d</b> new message'; // You have 1 new message no sound/marquee
$lang['New_pm'] = "<b>%d</b> <span class=pm>*<blink> <marquee width=50 behavior=alternate>NEW</marquee> </blink>*</span> message<EMBED SRC=$snd LOOP=false HIDDEN=true VOLUME=50 AUTOSTART=true WIDTH=0 HEIGHT=0 NAME=foobar MASTERSOUND>"; // You have 1 new message 
$lang['No_new_pm'] = '<b>0</b> new messages';
$lang['Unread_pms'] = '<b>0</b> unread messages';
$lang['Unread_pm'] = '<b>0</b> unread message';
$lang['No_unread_pm'] = '<b>0</b> unread messages';
$lang['You_new_pm'] = 'A new private message is waiting for you in your Inbox';
$lang['You_new_pms'] = 'New private messages are waiting for you in your Inbox';
$lang['You_no_new_pm'] = 'No new private messages are waiting for you';

$lang['Unread_message'] = 'Unread message';
$lang['Read_message'] = 'Read message';

$lang['Read_pm'] = 'Read message';
$lang['Post_new_pm'] = 'Post message';
$lang['Post_reply_pm'] = 'Reply to message';
$lang['Post_quote_pm'] = 'Quote message';
$lang['Edit_pm'] = 'Edit message';
$lang['Inbox'] = 'Inbox';
$lang['Outbox'] = 'Outbox';
$lang['Savebox'] = 'Savebox';
$lang['Sentbox'] = 'Sent messages';
$lang['Flag'] = 'Flag';
$lang['Subject'] = 'Subject';
$lang['From'] = 'From';
$lang['To'] = 'To';
$lang['Date'] = 'Date';
$lang['Mark'] = 'Mark';
$lang['Sent'] = 'Sent';
$lang['Saved'] = 'Saved';
$lang['Delete_marked'] = 'Delete Marked';
$lang['Delete_all'] = 'Delete All';
$lang['Save_marked'] = 'Save Marked'; 
$lang['Save_message'] = 'Save Message';
$lang['Delete_message'] = 'Delete Message';
$lang['Export'] = 'Export as XML';

$lang['Next_privmsg'] = 'Next PM';
$lang['Previous_privmsg'] = 'Previous PM';
$lang['No_newer_pm'] = 'There are no newer private messages.';
$lang['No_older_pm'] = 'There are no older private messages.';

$lang['Display_messages'] = 'Display messages from previous'; // Followed by number of days/weeks/months
$lang['All_Messages'] = 'All Messages';

$lang['No_messages_folder'] = 'You have no messages in this folder.';

$lang['PM_disabled'] = 'Sorry, but the Private messaging feature of this board has been disabled. Please try again later.';
$lang['Cannot_send_privmsg'] = 'Sorry, but the administrator has prevented you from sending private messages.';
$lang['No_to_user'] = 'You must specify a username to whom to send this message.';
$lang['No_such_user'] = 'Sorry, but no such user exists.';
$lang['Sender_is_recipient'] = 'You cannot send a private message to yourself.';

$lang['Disable_HTML_pm'] = 'Disable HTML in this message';
$lang['Disable_BBCode_pm'] = 'Disable BBCode in this message';
$lang['Disable_Smilies_pm'] = 'Disable Smilies in this message';

$lang['Message_sent'] = 'Your message has been sent.';

$lang['Click_return_inbox'] = 'Click %sHere%s to return to your Inbox';
$lang['Click_return_index'] = 'Click %sHere%s to return to the Index';

$lang['Send_a_new_message'] = 'Send new private message';
$lang['Send_a_reply'] = 'Reply to private message';
$lang['Edit_message'] = 'Edit private message';

$lang['Notification_subject'] = 'New Private Message has arrived!';

$lang['Find_username'] = 'Find a username';
$lang['Find'] = 'Find';
$lang['No_match'] = 'No matches found.';

$lang['No_post_id'] = 'No post ID was specified';
$lang['No_such_folder'] = 'No such folder exists';
$lang['No_folder'] = 'No folder specified';

$lang['Mark_all'] = 'Mark all';
$lang['Unmark_all'] = 'Unmark all';

$lang['Confirm_delete_pm'] = 'Are you sure you want to delete this message?';
$lang['Confirm_delete_pms'] = 'Are you sure you want to delete these messages?';

$lang['Inbox_size'] = 'Your Inbox is %d%% full'; // eg. Your Inbox is 50% full
$lang['Sentbox_size'] = 'Your Sentbox is %d%% full'; 
$lang['Savebox_size'] = 'Your Savebox is %d%% full'; 

$lang['Click_view_privmsg'] = 'Click %sHere%s to visit your Inbox';

$lang['Reply_message'] = 'Replied message';


//
// Profiles/Registration
//
$lang['user_accounts_limit'] = 'Sorry, but new user account registrations are currently unavailable.  Please try again later.';
$lang['Viewing_user_profile'] = 'Viewing profile of %s'; // %s is username 
$lang['About_user'] = 'Forum statistics';
$lang['User_special'] = 'Special admin-only fields';
$lang['User_admin_profile'] = 'Edit users\' profile'; // %s is username
$lang['User_admin_perms'] = 'Edit users\' permissions'; // %s is username
$lang['User_active'] = 'User <b>is</b> active';
$lang['User_not_active'] = 'User <b>is not</b> active';
$lang['Username_banned'] = 'Username <b>is</b> banned';
$lang['Username_not_banned'] = 'Username <b>is not</b> banned';
$lang['User_email_banned'] = 'Users\' email [ %s ] <b>is</b> banned'; // %s is email
$lang['User_email_not_banned'] = 'Users\' email <b>is not</b> banned';
$lang['Preferences'] = 'Preferences';
$lang['Items_required'] = 'Items marked with a * are required unless stated otherwise.';
$lang['Registration_info'] = 'Registration Information';
$lang['Profile_info'] = 'Profile Information';
$lang['Profile_info_warn'] = 'This information will be publicly viewable'; 
$lang['Avatar_panel'] = 'Your Avatar';
$lang['Avatar_gallery'] = 'Avatar gallery';
$lang['NO_AVATAR'] = 'NO AVATAR';  
$lang['Avatar_register_explain'] = 'Depending on the board settings, you can upload or customize your avatar after registration.';   
$lang['New_avatar_activation'] = 'Activate avatar';
$lang['Profile_updated_avatar'] = 'Your profile has been updated. However, your new avatar has temporarily been de-activated until it has been approved by an Administrator.';
$lang['No_avatar_posts'] = 'Your post count does not equal the limit of %s, therefore you cannot use your own avatar.';
$lang['Website'] = 'Website'; 
$lang['Stumble'] = 'StumbleUpon';
$lang['Location'] = 'Location'; 
$lang['Local_time'] = 'Local time'; 
$lang['User_viewed'] = 'Topics Viewed'; 
$lang['Zip_code'] = 'Weather city code'; 
$lang['Zip_code_viewable'] = 'This is <b>not</b> publically viewable. %sSelect your city%s'; 
$lang['Local_forecast'] = 'Your Local Forecast'; 
$lang['Country_Flag'] = 'Country Flag'; 
$lang['Select_Country'] = 'Select Country Flag';
$lang['Contact'] = 'Contact';
$lang['Email_address'] = 'E-mail address';
$lang['Send_private_message'] = 'Send private message';
$lang['Hidden_email'] = '[ Hidden ]';
$lang['Interests'] = 'Interests';
$lang['Occupation'] = 'Occupation'; 
$lang['Poster_rank'] = 'Poster rank'; 
$lang['Total_posts'] = 'Total posts';
$lang['User_post_pct_stats'] = '%.2f%% of total'; // 1.25% of total
$lang['User_post_day_stats'] = '%.2f posts per day'; // 1.5 posts per day
$lang['Search_user_posts'] = 'Find all posts by %s'; // Find all posts by username
$lang['Personal_Gallery_Of_User'] = 'Personal Gallery of %s';  
$lang['Attachments'] = 'Attachments'; 
$lang['All_attachments'] = 'View all Attachments on this board'; 
$lang['No_user_id_specified'] = 'Sorry, but that user does not exist.';
$lang['Wrong_Profile'] = 'You cannot modify a profile that is not your own.';
$lang['No_Access_Profile'] = 'You have been denied the possibility to edit your profile.';

$lang['XData_too_long'] = 'Your %s is too long.';
$lang['XData_invalid'] = 'The value you entered for %s is invalid.';

$lang['Only_one_avatar'] = 'Only one type of avatar can be specified';
$lang['File_no_data'] = 'The file at the URL you gave contains no data';
$lang['No_connection_URL'] = 'A connection could not be made to the URL you gave';
$lang['Incomplete_URL'] = 'The URL you entered is incomplete';
$lang['Wrong_remote_avatar_format'] = 'The URL of the remote avatar is not valid';
$lang['No_send_account_inactive'] = 'Sorry, but your password cannot be retrieved because your account is currently inactive. Please contact the forum administrator for more information.';

$lang['Always_swear'] = 'Allow word censoring'; 
$lang['Always_smile'] = 'Enable smilies by default';
$lang['Always_html'] = 'Enable HTML by default';
$lang['Always_bbcode'] = 'Enable BBCode by default';
$lang['Always_notify'] = 'Notify me upon replies by default';
$lang['Always_notify_explain'] = 'Sends an e-mail when someone replies to a topic you have posted in. This can be changed whenever you post.';
$lang['Profile_view_option'] = 'Pop up window on profile view';
$lang['real_name'] = 'Real Name';

$lang['Signature_panel'] = 'Your Signature';
$lang['Signature'] = 'Signature';
$lang['Signature_explain'] = 'This is a block of text that can be added to posts you make.';
$lang['Always_sigs'] = 'Display signatures'; 
$lang['Always_add_sig'] = 'Attach signature by default';
$lang['Retro_sig'] = 'Attach signature to previous posts'; 
$lang['Retro_sig_explain'] = 'After adding/editing your signature, changes normally are only applied to future posts.'; 

$lang['Sigs_disabled'] = 'Sorry, but the signature feature of this board has been disabled. Please try again later.';
$lang['Advanced_sig_mode'] = 'Advanced Edit Mode with Preview'; 
$lang['Max_signature_length'] = 'There is a %d character limit.';
$lang['Check_signature_length'] = 'Check current length';
$lang['Sample_post_1'] = 'Sample post with background 1';
$lang['Sample_post_2'] = 'Sample post with background 2';
$lang['Signature_update_success'] = 'Signature Updated Successfully.';

$lang['Board_style'] = 'My board style';
$lang['Board_lang'] = 'My language';
$lang['Timezone'] = 'My timezone';
$lang['No_themes'] = 'No Themes In database';
$lang['Date_format'] = 'My date format';
$lang['Clock_format'] = 'My clock format';
$lang['Clock_format_explain'] = 'Click %sHere%s to view all available clocks.'; 
$lang['Public_view_email'] = 'Always show my e-mail address';

$lang['Word_Wrap'] = 'Screen Width';
$lang['Word_Wrap_Explain'] = 'This is the maximum line length of users posts. (range: %min% - %max% chars.)';
$lang['Word_Wrap_Extra'] = 'characters';
$lang['Word_Wrap_Error'] = 'The post display width is out of range.';

$lang['Current_password'] = 'Current password';
$lang['New_password'] = 'New password';
$lang['Confirm_password'] = 'Confirm password';
$lang['Confirm_password_explain'] = 'You must confirm your current password if you wish to change it or alter your e-mail address.';
$lang['password_if_changed'] = 'You only need to supply a password if you want to change it.';
$lang['password_confirm_if_changed'] = 'You only need to confirm your password if you changed it above.';
$lang['password_gen'] = 'A random generated password for you to use is: <b>%s</b>'; // %s replaced with random password
$lang['Avatar'] = 'Avatar';
$lang['Avatar_explain'] = 'Displays a small graphic image below your details in posts. Only one image can be displayed at a time, its width can be no greater than %d pixels, the height no greater than %d pixels, and the file size no more than %d KB.';
$lang['Upload_Avatar_file'] = 'Upload avatar from your machine';
$lang['Upload_Avatar_URL'] = 'Upload avatar from a URL';
$lang['Upload_Avatar_URL_explain'] = 'Enter the URL of the location containing the Avatar image, it will be copied to this site.';
$lang['Pick_local_Avatar'] = 'Select avatar from the gallery';
$lang['Link_remote_Avatar'] = 'Link to off-site avatar';
$lang['Link_remote_Avatar_explain'] = 'Enter the URL of the location containing the Avatar image you wish to link to.';
$lang['Avatar_URL'] = 'URL of Avatar Image';
$lang['Select_from_gallery'] = 'Select avatar from gallery';
$lang['View_avatar_gallery'] = 'Show gallery';
$lang['Create_with_generator'] = 'Create avatar from generator';
$lang['View_avatar_generator'] = 'Show generator';
$lang['Avatar_Sticky'] = 'Sticky avatar'; 
$lang['Avatar_Sticky_Explain'] = 'By selecting yes, changing your avatar at a later date will have no effect on any of your existing posts, they will continue to display the avatar you had at the time of posting.'; 
$lang['Select_avatar'] = 'Select avatar';
$lang['Return_profile'] = 'Cancel avatar';
$lang['Select_category'] = 'Select category';
$lang['Show_avatars'] = 'Display avatars';
$lang['Page_transition'] = 'page transition'; 

$lang['Delete_Image'] = 'Delete Image';
$lang['Current_Image'] = 'Current Image';

$lang['Notify_on_privmsg'] = 'Notify me on new private message';
$lang['Notify_on_privmsg_text'] = 'Include message content in notification';
$lang['Popup_on_privmsg'] = 'Pop up window on new private message'; 
$lang['Sound_on_privmsg'] = 'Sound notification on new private message';  
$lang['Hide_user'] = 'Hide my online status';

$lang['Profile_updated'] = 'Your profile has been updated.';
$lang['Profile_updated_inactive'] = 'Your profile has been updated. However, you have changed vital details, thus your account is now inactive. Check your e-mail to find out how to reactivate your account, or if admin activation is required, wait for the administrator to reactivate it.';

$lang['Password_mismatch'] = 'The passwords you entered did not match.';
$lang['Current_password_mismatch'] = 'The current password you supplied does not match that stored in the database.';
$lang['Password_long'] = 'Your password must be no more than 32 characters.';
$lang['Username_taken'] = 'Sorry, but this username has already been taken.';
$lang['Username_invalid'] = 'Sorry, but this username contains an invalid character such as \'.';
$lang['Username_disallowed'] = 'Sorry, but this username has been disallowed.';
$lang['Email_taken'] = 'Sorry, but that e-mail address is already registered to a user.';
$lang['Email_banned'] = 'Sorry, but this e-mail address has been banned.';
$lang['Email_invalid'] = 'Sorry, but this e-mail address is invalid.';
$lang['Signature_too_long'] = 'Your signature is too long.';
$lang['Signature_too_high'] = 'Your signature has %s too many lines.';
$lang['Too_many_sig_image'] = 'You have added too many images in your signature. You can only add %d image in your signature.';
$lang['Too_many_sig_images'] = 'You have added too many images in your signature. You can only add %d images in your signature.';
$lang['Sig_image_too_large'] = 'You have added an image that is too large. Images can only be %d pixels wide and %d pixels high.';
$lang['Fields_empty'] = 'You must fill in the required fields.';
$lang['Avatar_filetype'] = 'The avatar filetype must be .jpg, .gif or .png';
$lang['Avatar_filesize'] = 'The avatar image file size must be less than %d KB'; // The avatar image file size must be less than 6 KB
$lang['Avatar_imagesize'] = 'The avatar must be less than %d pixels wide and %d pixels high'; 

$lang['Welcome'] = 'Welcome';
$lang['Welcome_subject'] = 'Welcome to %s Forums'; // %s replaced with sitename
$lang['New_account_subject'] = 'New user account';
$lang['Account_activated_subject'] = 'Account Activated';

$lang['Infobar'] = 'It appears you have not yet registered with our community. To register please click here...';
$lang['Account_added'] = 'Thank you for registering. Your account has been created. You may now log in with your username and password';
$lang['Account_inactive'] = 'Your account has been created. However, this forum requires account activation. An activation key has been sent to the e-mail address you provided. Please check your e-mail for further information';
$lang['Account_inactive_admin'] = 'Your account has been created. However, this forum requires account activation by the administrator. An e-mail has been sent to them and you will be informed when your account has been activated';
$lang['Account_active'] = 'Your account has now been activated. Thank you for registering';
$lang['Account_active_admin'] = 'The account has now been activated';
$lang['Reactivate'] = 'Reactivate your account!';
$lang['Already_activated'] = 'You have already activated your account';
$lang['COPPA'] = 'Your account has been created but has to be approved. Please check your e-mail for details.';

$lang['Registration'] = 'Registration Agreement Terms';
$lang['Agree_under_13'] = 'I agree to these terms and am under 13 years of age'; 
$lang['Agree_over_13'] = 'I agree to these terms and am over 13 years of age'; 
$lang['Agree_do'] = 'I agree to these terms'; 
$lang['Agree_not'] = 'I do not agree to these terms'; 

$lang['Wrong_activation'] = 'The activation key you supplied does not match any in the database.';
$lang['Send_password'] = 'Send me a new password'; 
$lang['Password_updated'] = 'A new password has been created; please check your e-mail for details on how to activate it.';
$lang['New_activation_sent'] = 'A new activation key has been sent; please check your e-mail for details on how to activate it.';
$lang['No_email_match'] = 'The e-mail address you supplied does not match the one listed for that username.';
$lang['New_activation'] = 'New activation';
$lang['New_password_activation'] = 'New password activation';
$lang['Password_activated'] = 'You may now login using any password you want (the password you use will become your new password)'; 
$lang['Send_email_msg'] = 'Send an e-mail message';
$lang['No_user_specified'] = 'No user was specified';
$lang['User_prevent_email'] = 'This user does not wish to receive e-mail. Try sending them a private message.';
$lang['User_not_exist'] = 'That user does not exist';
$lang['CC_email'] = 'Send a copy of this e-mail to yourself';
$lang['Read_receipt'] = 'Request a read receipt';
$lang['Email_message_desc'] = 'This message will be sent as plain text, so do not include any HTML or BBCode. The return address for this message will be set to your e-mail address.';
$lang['Flood_email_limit'] = 'You cannot send another e-mail at this time. Try again later.';
$lang['Recipient'] = 'Recipient';
$lang['Email_sent'] = 'The e-mail has been sent.';
$lang['Send_email'] = 'Send e-mail';
$lang['Empty_subject_email'] = 'You must specify a subject for the e-mail.';
$lang['Empty_message_email'] = 'You must enter a message to be e-mailed.';

$lang['Custom_post_color'] = 'My custom post color';
$lang['Custom_post_color_explain'] = 'Your post text will be shown in this custom color.'; 

$lang['Cpl_Overview'] = 'Overview';
$lang['Cpl_Board_Settings'] = 'Board Settings';

$lang['Your_activity'] = 'Your activity';
$lang['Bank_balance'] = 'Bank balance';

$lang['UCP_BuddyNone'] = 'No friends online';
$lang['UCP_BuddyList_Add'] = 'Your friends list has been updated successfully.';
$lang['UCP_BuddyList_Self'] = 'You cannot add yourself to the friends list.';
$lang['UCP_BuddyList_Remove'] = 'Your friends list has been updated successfully.';
$lang['UCP_BuddyList_Already'] = 'User is already on your friends list.';
$lang['Click_return_buddylist'] = 'Click %sHere%s to return to your Contact List';

$lang['Photo_panel'] = 'Your Photo';
$lang['Photo_panel_disabled'] = 'Sorry, but the profile photo feature of this board has been disabled. Please try again later.';
$lang['Photo_explain'] = 'Displays a small graphic image in your profile. Only one image can be displayed at a time, its width can be no greater than %d pixels, the height no greater than %d pixels, and the file size no more than %d KB.'; 
$lang['Upload_photo_file'] = 'Upload Photo from your machine';
$lang['Upload_photo_url'] = 'Upload Photo from a URL';
$lang['Upload_photo_url_explain'] = 'Enter the URL of the location containing the Photo image, it will be copied to this site.';
$lang['Link_remote_photo'] = 'Link to off-site Photo';
$lang['Link_remote_photo_explain'] = 'Enter the URL of the location containing the Photo image you wish to link to.';

$lang['Only_one_photo'] = 'Only one type of avatar can be specified';
$lang['Photo_filetype'] = 'The photo filetype must be .jpg, .gif or .png';
$lang['Photo_imagesize'] = 'The photo must be less than %d pixels wide and %d pixels high'; 
$lang['Photo_filesize'] = 'The photo image file size must be less than %d KB'; 
$lang['Wrong_remote_photo_format'] = 'The URL of the remote photo is not valid';
$lang['Photo_disabled'] = 'The profile photo feature has been disabled.'; 

$lang['Allowed_username_length'] = 'Length must be between %s and %s characters.';
$lang['Username_long'] = 'Your username must be no more than %s characters.';
$lang['Username_short'] = 'Your username must be more than %s characters.';

$lang['Group_priority'] = 'Group priority color';
$lang['Not_in_group'] = 'You are not in any groups.';


//
// Visual confirmation system strings
//
$lang['Confirm_code_wrong'] = 'The confirmation code you entered was incorrect.';
$lang['Too_many_registers'] = 'You have exceeded the number of registration attempts for this session. Please try again later.';
$lang['Confirm_code_impaired'] = 'To prevent automated registrations/postings the board administrator requires you to enter a confirmation code. The code is displayed in the image you should see below. If you are visually impaired or cannot otherwise read this code please contact the %sBoard Administrator%s.';
$lang['Confirm_code_title'] = 'Confirmation of registration';
$lang['Confirm_code_posting_title'] = 'Confirmation of posting';
$lang['Confirm_code'] = 'Confirmation code';
$lang['Confirm_code_explain'] = 'Enter the code exactly as it appears. All letters are case sensitive, zero has a diagonal line through it.';
$lang['Vip_code'] = 'VIP code';
$lang['Vip_code_explain'] = 'Enter the VIP code <b>%s</b> exactly as it appears.'; // $s is VIP code 
$lang['vip_spam_invalid'] = 'The VIP code you entered was incorrect.';

//
// Memberlist
// 
$lang['Select_sort_method'] = 'Select sort method';
$lang['Sort'] = 'Sort';
$lang['Sort_per_letter'] = 'Show only usernames starting with';
$lang['Others'] = 'others';
$lang['All'] = 'all';
$lang['Sort_Top_Ten'] = 'Top Ten Posters';
$lang['Sort_Joined'] = 'Joined Date';
$lang['Sort_Username'] = 'Username'; 
$lang['Sort_Location'] = 'Location';
$lang['Sort_Posts'] = 'Total posts';
$lang['Sort_Email'] = 'E-mail';
$lang['Sort_Website'] = 'Website';
$lang['Sort_Ascending'] = 'Ascending';
$lang['Sort_Descending'] = 'Descending';
$lang['Level'] = 'Level'; 
$lang['Profile_photo'] = 'Profile photo';  
$lang['User'] = 'User'; 
$lang['Mod'] = 'Mod'; 
$lang['Super_Mod'] = 'Jr. Admin'; 
$lang['Admin'] = 'Admin'; 


//
// Group control panel
//
$lang['Group_Control_Panel'] = 'Group Control Panel';
$lang['Group_member_details'] = 'Group Membership Details';
$lang['Group_member_join'] = 'Join a Group';

$lang['Group_Information'] = 'Group Information';
$lang['Group_name'] = 'Group name';
$lang['Group_description'] = 'Group description';
$lang['Group_membership'] = 'Group membership';
$lang['Group_Members'] = 'Group Members';
$lang['Group_Moderator'] = 'Group Moderator';
$lang['Group_Moderators'] = 'Group Moderators'; 
$lang['Group_owner'] = 'Group Owner';
$lang['Pending_members'] = 'Pending Members';

$lang['Group_type'] = 'Group type';
$lang['Group_open'] = 'Open group';
$lang['Group_closed'] = 'Closed group';
$lang['Group_hidden'] = 'Hidden group';

$lang['Current_memberships'] = 'Current memberships';
$lang['Non_member_groups'] = 'Non-member groups';
$lang['Memberships_pending'] = 'Memberships pending';

$lang['Select_group'] = 'Select group';
$lang['No_groups_exist'] = 'No Groups Exist';
$lang['Group_not_exist'] = 'That user group does not exist';

$lang['Join_group'] = 'Join Group';
$lang['No_group_members'] = 'This group has no members';
$lang['Group_hidden_members'] = 'This group is hidden; you cannot view its membership';
$lang['No_pending_group_members'] = 'This group has no pending members';
$lang['Group_joined'] = 'You have successfully subscribed to this group.<br />You will be notified when your subscription is approved by the group moderator.';
$lang['Group_joined_no_validate'] = 'You have successfully subscribed to this group.';
$lang['Group_request'] = 'A request to join your group has been made.';
$lang['Group_approved'] = 'Your request has been approved.';
$lang['Group_added'] = 'You have been added to this usergroup.'; 
$lang['Already_member_group'] = 'You are already a member of this group';
$lang['User_is_member_group'] = 'User is already a member of this group';
$lang['Group_type_updated'] = 'Successfully updated group type.';

$lang['Could_not_add_user'] = 'The user you selected does not exist.';
$lang['Could_not_anon_user'] = 'You cannot make Anonymous a group member.';

$lang['Confirm_unsub'] = 'Are you sure you want to unsubscribe from this group?';
$lang['Confirm_unsub_pending'] = 'Your subscription to this group has not yet been approved; are you sure you want to unsubscribe?';

$lang['Unsub_success'] = 'You have been un-subscribed from this group.';

$lang['Approve_selected'] = 'Approve Selected';
$lang['Deny_selected'] = 'Deny Selected';
$lang['Not_logged_in'] = 'You must be logged in to join a group.';
$lang['Remove_selected'] = 'Remove Selected';
$lang['Add_member'] = 'Add Member';
$lang['Group_grant_mod_status'] = 'Grant/Ungrant Mod status';
$lang['Group_grant_ungrant_mod_ok'] = 'Moderators list for this group has been updated.';
$lang['Not_group_moderator'] = 'You are not this group\'s moderator, therefore you cannot perform that action.';

$lang['Login_to_join'] = 'Log in to join or manage group memberships';
$lang['This_open_group'] = 'This is an open group: click to request membership';
$lang['This_closed_group'] = 'This is a closed group: no more users accepted';
$lang['This_hidden_group'] = 'This is a hidden group: automatic user addition is not allowed';
$lang['Member_this_group'] = 'You are a member of this group';
$lang['Pending_this_group'] = 'Your membership of this group is pending';
$lang['Are_group_moderator'] = 'You are the group moderator';
$lang['None'] = 'None';

$lang['Subscribe'] = 'Subscribe';
$lang['Unsubscribe'] = 'Unsubscribe';
$lang['View_Information'] = 'View Information';


//
// Search
//
$lang['Search_disabled'] = 'Sorry, but the search feature of this board has been disabled. Please try again later.';
$lang['Search_query'] = 'Search Query';
$lang['Search_options'] = 'Search Options';

$lang['Search_keywords'] = 'Search for keywords';
$lang['Search_keywords_explain'] = 'You can use <u>AND</u> to define words which must be in the results, <u>OR</u> to define words which may be in the result and <u>NOT</u> to define words which should not be in the result.';
$lang['Search_author'] = 'Search for author';
$lang['Search_author_explain'] = 'Use * as a wildcard for partial matches.';
$lang['Topic_starter'] = 'Topic starter';  
$lang['Search_for_any'] = 'Search for any terms or use query as entered';
$lang['Search_for_all'] = 'Search for all terms';
$lang['Search_title_msg'] = 'Topic title &amp; message text';
$lang['Search_msg_only'] = 'Message text only';

$lang['Return_first'] = 'Return first'; // followed by xxx characters in a select box
$lang['characters_posts'] = 'characters of posts';

$lang['Search_previous'] = 'Search previous'; // followed by days, weeks, months, year, all in a select box

$lang['Sort_by'] = 'Sort by';
$lang['Sort_Time'] = 'Post Time';
$lang['Sort_Topic_Title'] = 'Topic Title';

$lang['Display_results'] = 'Display results as';
$lang['All_available'] = 'All available';
$lang['No_searchable_forums'] = 'You do not have permissions to search any forum on this site.';

$lang['No_search_match'] = 'No topics or posts met your search criteria.';
$lang['Found_search_match'] = 'Search found %d match'; // eg. Search found 1 match
$lang['Found_search_matches'] = 'Search found %d matches'; // eg. Search found 24 matches
$lang['Search_Flood_Error'] = 'You cannot make another search so soon after your last; please try again in a short while.';

$lang['Close_window'] = 'Close Window';
$lang['Search_for'] = 'Search this forum for'; 

$lang['Search_keywords_faq'] = 'Press \'ALT s\' after clicking submit to repeatedly search page';

 
//
// Auth related entries
//
// Note the %s will be replaced with one of the following 'user' arrays
$lang['Sorry_auth_announce'] = 'Sorry, but only %s can post announcements in this forum.';
$lang['Sorry_auth_sticky'] = 'Sorry, but only %s can post sticky messages in this forum.'; 
$lang['Sorry_auth_read'] = 'Sorry, but only %s can read topics in this forum.'; 
$lang['Sorry_auth_post'] = 'Sorry, but only %s can post topics in this forum.'; 
$lang['Sorry_auth_reply'] = 'Sorry, but only %s can reply to posts in this forum.';
$lang['Sorry_auth_edit'] = 'Sorry, but only %s can edit posts in this forum.'; 
$lang['Sorry_auth_delete'] = 'Sorry, but only %s can delete posts in this forum.';
$lang['Sorry_auth_vote'] = 'Sorry, but only %s can vote in polls in this forum.';
$lang['Sorry_auth_suggest_event'] = 'Sorry, but only %s can suggest events in this forum.';  
// These replace the %s in the above strings
$lang['Auth_Anonymous_Users'] = '<b>anonymous users</b>';
$lang['Auth_Registered_Users'] = '<b>registered users</b>';
$lang['Auth_Users_granted_access'] = '<b>users granted special access</b>';
$lang['Auth_Moderators'] = '<b>moderators</b>';
$lang['Auth_Administrators'] = '<b>administrators</b>';

$lang['Not_Moderator'] = 'You are not a moderator of this forum.';
$lang['Not_Authorised'] = 'You are not authorized to access this page.';

$lang['You_been_banned'] = 'You have been banned from this board.<br />Please contact the webmaster or administrator for more information.';


//
// Viewonline
//
$lang['Reg_users_zero_online'] = 'There are 0 Registered users and '; // There are 5 Registered and
$lang['Reg_users_online'] = 'There are %d Registered users and '; // There are 5 Registered and
$lang['Reg_user_online'] = 'There is %d Registered user and '; // There is 1 Registered and
$lang['Hidden_users_zero_online'] = '0 Hidden users online'; // 6 Hidden users online
$lang['Hidden_users_online'] = '%d Hidden users online'; // 6 Hidden users online
$lang['Hidden_user_online'] = '%d Hidden user online'; // 6 Hidden users online
$lang['Guest_users_online'] = 'There are %d Guest users online'; // There are 10 Guest users online
$lang['Guest_users_zero_online'] = 'There are 0 Guest users online'; // There are 10 Guest users online
$lang['Guest_user_online'] = 'There is %d Guest user online'; // There is 1 Guest user online
$lang['No_users_browsing'] = 'There are no users currently browsing this forum';

$lang['Online_explain'] = 'This data is based on users active over the past %s minutes';

$lang['Forum_Location'] = 'Forum Location';
$lang['Last_updated'] = 'Last Updated';
$lang['Logged_on'] = 'Logged On'; 
$lang['Logging_on'] = 'Logging on';
$lang['Reading'] = 'Reading';
$lang['Viewing'] = 'Viewing';
$lang['Posting_message'] = 'Posting a message';
$lang['Searching_forums'] = 'Searching forums';
$lang['Viewing_profile'] = 'User Control Panel';
$lang['Viewing_online'] = 'Viewing who is online';
$lang['Viewing_member_list'] = 'Viewing memberlist';
$lang['Viewing_priv_msgs'] = 'Viewing Private Messages';
$lang['Viewing_FAQ'] = 'Viewing FAQ';
$lang['Viewing_Smilies'] = 'Viewing smilies'; 
$lang['Viewing_Tell_Friend'] = 'Viewing tell a friend'; 
$lang['Viewing_Links'] = 'Viewing links'; 
$lang['Viewing_Download'] = 'Viewing downloads';
$lang['Viewing_topic_views'] = 'Viewing topic views'; 
$lang['Viewing_topics_started'] = 'Viewing topics started'; 
$lang['Viewing_album'] = 'Viewing album'; 
$lang['Viewing_album_personal'] = 'Viewing user\'s personal album'; 
$lang['Viewing_album_pic'] = 'Viewing pics or posting comments in the album'; 
$lang['Searching_album'] = 'Searching album'; 
$lang['Viewing_attachments'] = 'Viewing attachments'; 
$lang['Viewing_stats'] = 'Viewing statistics'; 
$lang['Viewing_bank'] = 'Viewing bank'; 
$lang['Viewing_shop'] = 'Viewing shop'; 
$lang['Viewing_calendar'] = 'Viewing calendar'; 
$lang['Viewing_ratings'] = 'Viewing ratings'; 
$lang['Viewing_chatroom'] = 'Viewing chatroom'; 
$lang['Viewing_lottery'] = 'Viewing lottery'; 
$lang['Viewing_charts'] = 'Viewing charts'; 
$lang['Viewing_KB'] = 'Viewing Knowledge Base'; 
$lang['Viewing_helpdesk'] = 'Viewing Helpdesk'; 
$lang['Viewing_toplist'] = 'Viewing toplist';   
$lang['Viewing_RSS'] = 'Viewing RSS feed';
$lang['Viewing_portal'] = 'Viewing portal'; 
$lang['Viewing_jobs'] = 'Viewing Jobs';
$lang['Viewing_avatar_toplist'] = 'Viewing Avatar Toplist';
$lang['Viewing_avatar_list'] = 'Viewing Avatar Listing';
$lang['Viewing_guestbook'] = 'Viewing Guestbook';


//
// Moderator Control Panel
//
$lang['Mod_CP'] = 'Moderator Control Panel';
$lang['Mod_CP_explain'] = 'Using the form below you can perform mass moderation operations on this forum. You can lock, unlock, move, merge, link, prioritize, change custom titles or delete any number of topics.';

$lang['Select'] = 'Select';
$lang['Move'] = 'Move'; 
$lang['Moved'] = 'Moved'; 
$lang['Link'] = 'Link'; 
$lang['Lock'] = 'Lock';
$lang['Unlock'] = 'Unlock';
$lang['Sticky'] = 'Stick'; 
$lang['Merge'] = 'Merge';
$lang['Announce'] = 'Announce'; 
$lang['Global_Announce'] = 'Global Announce';
$lang['Topics_Removed'] = 'The selected topics have been successfully removed from the database.';
$lang['Topics_Locked'] = 'The selected topics have been locked.';
$lang['Topics_Moved'] = 'The selected topics have been moved.'; 
$lang['Topics_Linked'] = 'The selected topics have been linked.'; 
$lang['Topics_Unlocked'] = 'The selected topics have been unlocked.';
$lang['Topics_Stickied'] = 'The selected topic has been stuck.'; 
$lang['Topics_Unstickied'] = 'The selected topic has been unstuck.'; 
$lang['Topics_Merged'] = 'The selected topics have been merged.';
$lang['Topics_Bumped'] = 'The selected topic has been bumped.';
$lang['Topics_Announced'] = 'The selected topic has been announced.'; 
$lang['Topics_Unannounced'] = 'The selected topic has been unannounced.'; 
$lang['Topics_Globalannounced'] = 'The selected topic has been globally announced.';
$lang['Topics_Unglobalannounced'] = 'The selected topic has been unglobally announced.';
$lang['No_Topics_Moved'] = 'No topics were moved.';
$lang['No_Topics_Merged'] = 'No topics were merged';
$lang['Topics_Prioritized'] = 'The selected topics have been prioritized.';
$lang['Priority'] = 'Priority';
$lang['Prioritize'] = 'Prioritize';

$lang['Confirm_delete_topic'] = 'Are you sure you want to remove the selected topic/s?';
$lang['Confirm_lock_topic'] = 'Are you sure you want to lock the selected topic/s?';
$lang['Confirm_unlock_topic'] = 'Are you sure you want to unlock the selected topic/s?';
$lang['Confirm_move_topic'] = 'Are you sure you want to move the selected topic/s?';
$lang['Confirm_link_topic'] = 'Are you sure you want to link the selected topic/s?'; 
$lang['Move_to_forum'] = 'Move to forum';
$lang['Leave_shadow_topic'] = 'Leave shadow topic in old forum?';

$lang['Split_Topic'] = 'Split Topic Control Panel';
$lang['Split_Topic_explain'] = 'Using the form below you can split a topic in two, either by selecting the posts individually or by splitting at a selected post';
$lang['Split_title'] = 'New topic title';
$lang['Split_forum'] = 'Forum for new topic';
$lang['Split_posts'] = 'Split selected posts';
$lang['Split_after'] = 'Split from selected post';
$lang['Topic_split'] = 'The selected topic has been split successfully.';
$lang['Link_in_forum'] = 'Create link in '; 
$lang['No_Topics_Linked'] = 'No links were created.'; 
$lang['Too_many_error'] = 'You have selected too many posts. You can only select one post to split a topic after!';

$lang['None_selected'] = 'You have not selected any topics to perform this operation on. Please go back and select at least one.';
$lang['None_selected_posts'] = 'You have not selected any posts to perform this operation on. Please go back and select at least one.';
$lang['New_forum'] = 'New forum';

$lang['This_posts_IP'] = 'IP address for this post';
$lang['Other_IP_this_user'] = 'Other IP addresses this user has posted from';
$lang['Users_this_IP'] = 'Users posting from this IP address';
$lang['IP_info'] = 'IP Information';
$lang['Lookup_IP'] = 'Look up IP address';
 
$lang['Topic_started'] = 'Topic started';

$lang['Topics_Title_Edited'] = 'The selected topic titles have been edited.';
$lang['Edit_title'] = 'Title Edit';

$lang['Confirm_merge_topic'] = 'Are you sure you want to merge the selected topic/s?<br /><span class="genmed">(Next you will select a target post to merge these into)</span>';
$lang['Merge_to_forum'] = 'Merge to forum';
$lang['Merge_topic'] = 'Merge this topic';

$lang['Merge_post'] = 'Merge posts in this topic';
$lang['Merge_after'] = 'Merge from selected post';
$lang['Merge_posts'] = 'Merge selected posts';

$lang['Mod_CP_merge_explain'] = 'Select the topic to which the topic/post will be merged to';
$lang['Merge_Topic_explain'] = 'Using the form below you can merge posts to a topic, either by selecting the posts individually or by merging at a selected post';
$lang['Merge_post_topic'] = 'Merge posts into a topic';
$lang['Posts_Merged'] = 'The selected posts have been merged';
$lang['Not_edit_delete_admin'] = 'You are not allowed to edit/delete an administrator\'s post.';
  
  
//
// Timezones ... for display on each page
//
$lang['All_times'] = 'All times are %s'; // eg. All times are GMT - 12 Hours (times from next block)

$lang['tz_short']['-11'] = $lang['-11'] = 'UTC - 11 Hours';
$lang['tz_short']['-10'] = $lang['-10'] = 'UTC - 10 Hours';
$lang['tz_short']['-9.5'] = $lang['-9.5'] = 'UTC - 9.5 Hours';
$lang['tz_short']['-9'] = $lang['-9'] = 'UTC - 9 Hours';
$lang['tz_short']['-8'] = $lang['-8'] = 'UTC - 8 Hours';
$lang['tz_short']['-7']= $lang['-7'] = 'UTC - 7 Hours';
$lang['tz_short']['-6']= $lang['-6'] = 'UTC - 6 Hours';
$lang['tz_short']['-5'] = $lang['-5'] = 'UTC - 5 Hours';
$lang['tz_short']['-4'] = $lang['-4'] = 'UTC - 4 Hours';
$lang['tz_short']['-3.5'] = $lang['-3.5'] = 'UTC - 3.5 Hours';
$lang['tz_short']['-3'] = $lang['-3'] = 'UTC - 3 Hours';
$lang['tz_short']['-2'] = $lang['-2'] = 'UTC - 2 Hours';
$lang['tz_short']['-1'] = $lang['-1'] = 'UTC - 1 Hours';
$lang['tz_short']['0'] = $lang['0'] = 'UTC';
$lang['tz_short']['1'] = $lang['1'] = 'UTC + 1 Hour';
$lang['tz_short']['2'] = $lang['2'] = 'UTC + 2 Hours';
$lang['tz_short']['3'] = $lang['3'] = 'UTC + 3 Hours';
$lang['tz_short']['3.5'] = $lang['3.5'] = 'UTC + 3.5 Hours';
$lang['tz_short']['4'] = $lang['4'] = 'UTC + 4 Hours';
$lang['tz_short']['4.5'] = $lang['4.5'] = 'UTC + 4.5 Hours';
$lang['tz_short']['5'] = $lang['5'] = 'UTC + 5 Hours';
$lang['tz_short']['5.5'] = $lang['5.5'] = 'UTC + 5.5 Hours';
$lang['tz_short']['5.75'] = $lang['5.75'] = 'UTC + 5.75 Hours';
$lang['tz_short']['6'] = $lang['6'] = 'UTC + 6 Hours';
$lang['tz_short']['6.5'] = $lang['6.5'] = 'UTC + 6.5 Hours';
$lang['tz_short']['7'] = $lang['7'] = 'UTC + 7 Hours';
$lang['tz_short']['8'] = $lang['8'] = 'UTC + 8 Hours';
$lang['tz_short']['8.75'] = $lang['8.75'] = 'UTC + 8.75 Hours';
$lang['tz_short']['9'] = $lang['9'] = 'UTC + 9 Hours';
$lang['tz_short']['9.5'] = $lang['9.5'] = 'UTC + 9.5 Hours';
$lang['tz_short']['10'] = $lang['10'] = 'UTC + 10 Hours';
$lang['tz_short']['10.5'] = $lang['10.5'] = 'UTC + 10.5 Hours'; 
$lang['tz_short']['11'] = $lang['11'] = 'UTC + 11 Hours';
$lang['tz_short']['11.5'] = $lang['11.5'] = 'UTC + 11.5 Hours';
$lang['tz_short']['12'] = $lang['12'] = 'UTC + 12 Hours';
$lang['tz_short']['12.75'] = $lang['12.75'] = 'UTC + 12.75 Hours';
$lang['tz_short']['13'] = $lang['13'] = 'UTC + 13 Hours';
$lang['tz_short']['14'] = $lang['14'] = 'UTC + 14 Hours';  

// These are displayed in the timezone select box
$lang['tz']['-11'] = '[UTC - 11] Niue Time, Samoa Standard Time';
$lang['tz']['-10'] = '[UTC - 10] Hawaii-Aleutian Standard Time, Cook Island Time';
$lang['tz']['-9.5'] = '[UTC - 9:30] Marquesas Islands Time';
$lang['tz']['-9'] = '[UTC - 9] Alaska Standard Time, Gambier Island Time';
$lang['tz']['-8'] = '[UTC - 8] Pacific Standard Time';
$lang['tz']['-7'] = '[UTC - 7] Mountain Standard Time';
$lang['tz']['-6'] = '[UTC - 6] Central Standard Time';
$lang['tz']['-5'] = '[UTC - 5] Eastern Standard Time';
$lang['tz']['-4'] = '[UTC - 4] Atlantic Standard Time';
$lang['tz']['-3.5'] = '[UTC - 3:30] Newfoundland Standard Time';
$lang['tz']['-3'] = '[UTC - 3] Amazon Standard Time, Central Greenland Time';
$lang['tz']['-2'] = '[UTC - 2] Fernando de Noronha Time, South Georgia &amp; the South Sandwich Islands Time';
$lang['tz']['-1'] = '[UTC - 1] Azores Standard Time, Cape Verde Time, Eastern Greenland Time';
$lang['tz']['0'] = '[UTC] Western European Time, Greenwich Mean Time';
$lang['tz']['1'] = '[UTC + 1] Central European Time, West African Time';
$lang['tz']['2'] = '[UTC + 2] Eastern European Time, Central African Time';
$lang['tz']['3'] = '[UTC + 3] Moscow Standard Time, Eastern African Time';
$lang['tz']['3.5'] = '[UTC + 3:30] Iran Standard Time';
$lang['tz']['4'] = '[UTC + 4] Gulf Standard Time, Samara Standard Time';
$lang['tz']['4.5'] = '[UTC + 4:30] Afghanistan Time';
$lang['tz']['5'] = '[UTC + 5] Pakistan Standard Time, Yekaterinburg Standard Time';
$lang['tz']['5.5'] = '[UTC + 5:30] Indian Standard Time, Sri Lanka Time';
$lang['tz']['5.75'] = '[UTC + 5:45] Nepal Time';
$lang['tz']['6'] = '[UTC + 6] Bangladesh Time, Bhutan Time, Novosibirsk Standard Time';
$lang['tz']['6.5'] = '[UTC + 6:30] Cocos Islands Time, Myanmar Time';
$lang['tz']['7'] = '[UTC + 7] Indochina Time, Krasnoyarsk Standard Time';
$lang['tz']['8'] = '[UTC + 8] Chinese Standard Time, Australian Western Standard Time, Irkutsk Standard Time';
$lang['tz']['8.75'] = '[UTC + 8:45] Southeastern Western Australia Standard Time';
$lang['tz']['9'] = '[UTC + 9] Japan Standard Time, Korea Standard Time, Chita Standard Time';
$lang['tz']['9.5'] = '[UTC + 9:30] Australian Central Standard Time';
$lang['tz']['10'] = '[UTC + 10] Australian Eastern Standard Time, Vladivostok Standard Time';
$lang['tz']['10.5'] = '[UTC + 10:30] Lord Howe Standard Time';
$lang['tz']['11'] = '[UTC + 11] Solomon Island Time, Magadan Standard Time';
$lang['tz']['11.5'] = '[UTC + 11:30] Norfolk Island Time';
$lang['tz']['12'] = '[UTC + 12] New Zealand Time, Fiji Time, Kamchatka Standard Time';
$lang['tz']['12.75'] = '[UTC + 12:45] Chatham Islands Time';
$lang['tz']['13'] = '[UTC + 13] Tonga Time, Phoenix Islands Time';
$lang['tz']['14'] = '[UTC + 14] Line Island Time';

$lang['datetime']['Sunday'] = 'Sunday';
$lang['datetime']['Monday'] = 'Monday';
$lang['datetime']['Tuesday'] = 'Tuesday';
$lang['datetime']['Wednesday'] = 'Wednesday';
$lang['datetime']['Thursday'] = 'Thursday';
$lang['datetime']['Friday'] = 'Friday';
$lang['datetime']['Saturday'] = 'Saturday';

$lang['datetime']['Sun'] = 'Sun';
$lang['datetime']['Mon'] = 'Mon';
$lang['datetime']['Tue'] = 'Tue';
$lang['datetime']['Wed'] = 'Wed';
$lang['datetime']['Thu'] = 'Thu';
$lang['datetime']['Fri'] = 'Fri';
$lang['datetime']['Sat'] = 'Sat';

$lang['datetime']['January'] = 'January';
$lang['datetime']['February'] = 'February';
$lang['datetime']['March'] = 'March';
$lang['datetime']['April'] = 'April';
$lang['datetime']['May'] = 'May';
$lang['datetime']['June'] = 'June';
$lang['datetime']['July'] = 'July';
$lang['datetime']['August'] = 'August';
$lang['datetime']['September'] = 'September';
$lang['datetime']['October'] = 'October';
$lang['datetime']['November'] = 'November';
$lang['datetime']['December'] = 'December';
$lang['datetime']['Jan'] = 'Jan';
$lang['datetime']['Feb'] = 'Feb';
$lang['datetime']['Mar'] = 'Mar';
$lang['datetime']['Apr'] = 'Apr';
$lang['datetime']['May'] = 'May';
$lang['datetime']['Jun'] = 'Jun';
$lang['datetime']['Jul'] = 'Jul';
$lang['datetime']['Aug'] = 'Aug';
$lang['datetime']['Sep'] = 'Sep';
$lang['datetime']['Oct'] = 'Oct';
$lang['datetime']['Nov'] = 'Nov';
$lang['datetime']['Dec'] = 'Dec';

$lang['interval']['day'] = 'day'; 
$lang['interval']['days'] = 'days'; 
$lang['interval']['week'] = 'week'; 
$lang['interval']['weeks'] = 'weeks'; 
$lang['interval']['month'] = 'month'; 
$lang['interval']['months'] = 'months'; 
$lang['interval']['year'] = 'year'; 
$lang['interval']['years'] = 'years';   


//
// News Bar
//
// news_size select box
$lang['ns']['7'] = 7;
$lang['ns']['9'] = 9;
$lang['ns']['12'] = 12;
$lang['ns']['18'] = 18;
$lang['ns']['20'] = 20;
$lang['ns']['24'] = 24;
// scroll speed select
$lang['ssp']['1'] = 1;
$lang['ssp']['2'] = 2;
$lang['ssp']['3'] = 3;
$lang['ssp']['4'] = 4;
$lang['ssp']['5'] = 5;
$lang['ssp']['6'] = 6;
$lang['ssp']['7'] = 7;
$lang['ssp']['8'] = 8;
$lang['ssp']['9'] = 9;
$lang['ssp']['10'] = 10;
// scroll action select
$lang['sa']['left'] = 'Left';
$lang['sa']['right'] = 'Right';
$lang['sa']['up'] = 'Up';
$lang['sa']['down'] = 'Down';
// scroll behavior select
$lang['sb']['scroll'] = 'Scroll';
$lang['sb']['alternate'] = 'Alternate';
// scroll size select
$lang['ss']['25%'] = '25%';
$lang['ss']['50%'] = '50%';
$lang['ss']['75%'] = '75%';
$lang['ss']['100%'] = '100%';
$lang['ss']['125%'] = '125%';
// news style select
$lang['nss']['none'] = 'Stationary';
$lang['nss']['marquee'] = 'Marquee';
// news bold select
$lang['nbs']['none'] = $lang['None'];
$lang['nbs']['B'] = 'Bold';
// news italics select
$lang['nis']['none'] = $lang['None'];
$lang['nis']['I'] = 'Italics';
// news underline select
$lang['nus']['none'] = $lang['None'];
$lang['nus']['U'] = 'Underline';


// 
// Tell A Friend 
// 
$lang['Tell_Friend'] = 'Tell A Friend'; 
$lang['Tell_Friend_Disable'] = 'Sorry, but the tell a friend feature of this board has been disabled. Please try again later.';  
$lang['Tell_Friend_Reciever_User'] = 'Your Friends\' Name:'; 
$lang['Tell_Friend_Reciever_Email'] = 'Your Friends\' E-mail:'; 
$lang['Tell_Friend_Body'] = "Hi,\n\nI just read the topic \"{TOPIC}\" at {SITENAME} and thought you might be interested. Here is the link:\n\n{LINK}\n\nGo and read it and if you want to reply you can register for your own account if you have not done so already.";   


// 
// Last visit 
// 
$lang['Years'] = 'Years'; 
$lang['Months'] = 'Months'; 
$lang['Year'] = 'Year'; 
$lang['Weeks'] = 'Weeks'; 
$lang['Week'] = 'Week'; 
$lang['Day'] = 'Day'; 
$lang['Total_online_time'] = 'Total Online Duration';  
$lang['Last_online_time'] = 'Last Online Duration';  
$lang['Number_of_visit'] = 'Number of visits';  
$lang['Number_of_pages'] = 'Number of page hits';  
$lang['Last_logon'] = 'Last visited';  
$lang['Never_last_logon'] = 'Never';  
$lang['Users_today_zero_total'] = 'In total <b>0</b> users have visited this board today :: '; 
$lang['Users_today_total'] = 'In total <b>%d</b> users have visited this board today :: '; 
$lang['User_today_total'] = 'In total <b>%d</b> user has visited this board today :: '; 
$lang['Users_lasthour_explain'] = ', <b>%d</b> within the last hour';  


// 
// Birthday  
// 
$lang['Birthday'] = 'Birthday';  
$lang['Birthday_explain'] = 'Setting a year will list your age when it is your birthday.';  
$lang['Birthdays'] = 'Birthdays';  
$lang['No_birthday_specify'] = '';  
$lang['Age'] = 'Age'; 
$lang['Years_old'] = 'years old'; 
$lang['Wrong_birthday_format'] = 'The birthday format was entered incorrectly.';  
$lang['Birthday_to_high'] = 'Sorry, this site does not accept users older than %d years old'; 
$lang['Birthday_require'] = 'Your birthday is required on this site.'; 
$lang['Birthday_to_low'] = 'Sorry, this site does not accept users younger than %d years old'; 
$lang['Submit_date_format'] = 'd-m-Y'; //php date() format - Note: ONLY d, m and Y may be used and SHALL ALL be used (different seperators are accepted)  
$lang['Birthday_greeting_today'] = 'We would like to wish you congratulations on reaching %s years old today.<br /><br /> The Management'; //%s is substituted with the users age  
$lang['Birthday_greeting_prev'] = 'We would like to give you a belated congratulations for becoming %s years old on the %s.<br /><br /> The Management'; //%s is substituted with the users age, and birthday  
$lang['Birthday_greeting_calendar'] = 'Congratulations to %s on their birthday'; //%s is substituted with the username
$lang['Greeting_Messaging'] = 'Congratulations';  
$lang['Birthday_today'] = 'Users with a birthday today:';  
$lang['Birthday_week'] = 'Users with a birthday within the next %d days:';  
$lang['Nobirthday_week'] = 'No users are having a birthday in the upcoming %d days'; // %d is substitude with the number of days  
$lang['Nobirthday_today'] = 'No users have a birthday today';  
$lang['Month'] = 'Month';  
// NOTE: DO NOT translate the following four (4) lines below, they are automatically translated into your language!! 
$lang['day_short'] = array ($lang['datetime']['Sun'],$lang['datetime']['Mon'],$lang['datetime']['Tue'],$lang['datetime']['Wed'],$lang['datetime']['Thu'],$lang['datetime']['Fri'],$lang['datetime']['Sat']); 
$lang['day_long'] = array ($lang['datetime']['Sunday'],$lang['datetime']['Monday'],$lang['datetime']['Tuesday'],$lang['datetime']['Wednesday'],$lang['datetime']['Thursday'],$lang['datetime']['Friday'],$lang['datetime']['Saturday']); 
$lang['month_short'] = array ($lang['datetime']['Jan'],$lang['datetime']['Feb'],$lang['datetime']['Mar'],$lang['datetime']['Apr'],$lang['datetime']['May'],$lang['datetime']['Jun'],$lang['datetime']['Jul'],$lang['datetime']['Aug'],$lang['datetime']['Sep'],$lang['datetime']['Oct'],$lang['datetime']['Nov'],$lang['datetime']['Dec']); 
$lang['month_long'] = array ($lang['datetime']['January'],$lang['datetime']['February'],$lang['datetime']['March'],$lang['datetime']['April'],$lang['datetime']['May'],$lang['datetime']['June'],$lang['datetime']['July'],$lang['datetime']['August'],$lang['datetime']['September'],$lang['datetime']['October'],$lang['datetime']['November'],$lang['datetime']['December']); 
// NOTE: DO NOT translate the following four (4) lines above, they are automatically translated into your language!!  

// Zodiac signs 
$lang['Zodiac'] = 'Zodiac'; 
$lang['Capricorn'] = 'Capricorn';  
$lang['Aquarius'] = 'Aquarius';  
$lang['Pisces'] = 'Pisces';  
$lang['Aries'] = 'Aries'; 
$lang['Taurus'] = 'Taurus';  
$lang['Gemini'] = 'Gemini';  
$lang['Cancer'] = 'Cancer';  
$lang['Leo'] = 'Leo';  
$lang['Virgo'] = 'Virgo';  
$lang['Libra'] = 'Libra';  
$lang['Scorpio'] = 'Scorpio';  
$lang['Sagittarius'] = 'Sagittarius';   
$lang['Horoscopes'] = 'Daily Horoscopes';
	
// Chinese zodiac signs 
$lang['Chinese_zodiac']= 'Chinese Zodiac';
$lang['Unknown'] = 'Unknown';
$lang['Rat'] = 'Rat';
$lang['Buffalo'] = 'Ox';
$lang['Tiger'] = 'Tiger';
$lang['Cat'] = 'Rabbit';
$lang['Dragon'] = 'Dragon';
$lang['Snake'] = 'Snake';
$lang['Horse'] = 'Horse';
$lang['Goat'] = 'Goat';
$lang['Monkey'] = 'Monkey';
$lang['Cock'] = 'Rooster';
$lang['Dog'] = 'Dog';
$lang['Pig'] = 'Pig';


// Biorhythm
$lang['Biorhythm'] = 'Biorhythm';
$lang['bio_today'] = 'Today';
$lang['bio_intellectual'] = 'Intellectual';
$lang['bio_emotional'] = 'Emotional';
$lang['bio_physical'] = 'Physical';
$lang['bio_enter_birthday'] = 'You cannot view your Biorhythm until you enter a birthdate.';
$lang['bio_click_enter_birthday'] = 'Click %sHere%s to enter a bithdate';


// 
// Gender 
// 
$lang['Gender'] = 'Gender'; // used in users profile to display which gender they are 
$lang['Male'] = 'Male';  
$lang['Female'] = 'Female';  


// 
// Show ranks in FAQ 
// 
$lang['RankFAQ_Block_Title'] = 'Ranks'; 
$lang['RankFAQ_Link_Title'] = 'Ranks on board'; 
$lang['RankFAQ_Title'] = 'Rank title'; 
$lang['RankFAQ_Min'] = 'Minimum posts'; 
$lang['RankFAQ_Image'] = 'Rank image'; 
$lang['RankFAQ_None'] = 'N/A';   


// 
// Points system 
// 
$lang['Points_cp'] = '%s Control Panel'; 
$lang['Points_sys'] = '%s System'; 
$lang['Points_donation'] = '%s Donation'; 
$lang['Points_method'] = 'Method'; 
$lang['Points_donate'] = '%sDonate%s'; 
$lang['Points_add_subtract'] = 'Add or subtract %s'; 
$lang['Points_amount'] = 'Amount'; 
$lang['Points_give_take'] = 'Amount of %s to give or take.'; 
$lang['Points_give'] = 'Amount of %s to give.'; 
$lang['Add'] = 'Add'; 
$lang['Subtract'] = 'Subtract'; 
$lang['Points_donate_to'] = 'The person you want to donate %s to.'; 
$lang['Points_no_username']= 'No username entered.'; 
$lang['Points_not_admin'] = 'You are not authorized to administer the %s system.'; 
$lang['Points_cant_take'] = 'You can\'t take away that amount of %s from this user.'; 
$lang['Points_thanks_donation'] = 'Thanks for your donation.'; 
$lang['Click_return_points_donate']	= 'Click %sHere%s to return to %s Donation'; 
$lang['Points_cant_donate']	= 'You can\'t donate that amount of %s to this user.'; 
$lang['Points_cant_donate_self'] = 'You can\'t donate %s to yourself.';
$lang['Points_user_donation_off'] = 'User donation is not enabled.'; 
$lang['Click_return_pointscp'] = 'Click %sHere%s to return to the %s Control Panel'; 
$lang['Points_user_updated'] = 'This user\'s %s have been Updated Successfully.'; 
$lang['Points_mass_edit'] = 'Mass Edit Usernames'; 
$lang['Points_mass_edit_explain'] = 'Enter one username per line.'; 
$lang['Points_notify'] = 'Notify me on new %s donation'; 
$lang['Points_notify_explain'] = 'Sends an e-mail when someone donates %s to you.'; 
$lang['Points_enter_some_donate'] = 'Enter some %s to donate.';   
$lang['Points_link_title'] = 'Edit users %s';
$lang['Points_link_title_2'] = 'Donate %s to user';


// 
// Points Transactions 
// 
$lang['My_Trans'] = 'My Transactions';
$lang['Global_Trans'] = 'All Transactions'; 
$lang['Recent_Trans_To'] = 'Recent transactions to your account'; 
$lang['Recent_Trans_From'] = 'Recent transactions that you made'; 
$lang['Trans_From'] = 'Sent From'; 
$lang['Trans_To'] = 'Sent To'; 
$lang['Points_reason'] = 'Reason'; 
$lang['Total_Trans'] = 'Total'; 

$lang['View_The_Rest'] = 'View the rest'; 
$lang['Sort_Trans_From'] = 'Sort by sender'; 
$lang['Sort_Trans_To'] = 'Sort by reciever'; 
$lang['Sort_Trans_Amount'] = 'Sort by amount'; 
$lang['Sort_Trans_Date'] = 'Sort by send date'; 
$lang['Sort_Top_Ten_Trans'] = 'Top Ten'; 
$lang['Points_reason_donate'] = 'Enter reason for donation.'; 
$lang['Points_no_reason_donate'] = 'Please enter reason for donation.';  


// 
// Shop Transactions 
// 
$lang['Shop_Transaction'] = 'Shop Transactions'; 
$lang['Shop_trans_disabled'] = 'Sorry, but the shop transaction feature of this board has been disabled. Please try again later.';
$lang['Trans_Item'] = 'Item Name'; 
$lang['Trans_Cost'] = 'Cost'; 
$lang['Trans_Type'] = 'Bought/Sold';   
$lang['Type'] = 'Type';


// 
// Calendar 
// 
$lang['Event_Start'] = 'Single or Start Date'; 
$lang['Event_End'] = 'End Date and Interval'; 
$lang['Calendar_advanced_form'] = 'advanced'; 
$lang['Calendar_repeat_forever'] = 'repeat forever'; 
$lang['Clear_Date'] = 'Clear Date'; 
$lang['Date_not_specified'] = 'Select -->'; 
$lang['Select_start_date'] = 'Please Select a Start Date';
$lang['Calendar_event_title'] = 'Calendar Event'; 
$lang['Event_type'] = 'Event type'; 
$lang['Calendar_suggested_event_title'] = '(Suggested) Calendar Event'; 
$lang['Calendar_post_event'] = 'Post event to:'; 
$lang['Calendar_add_event'] = 'Add event to selected forum'; 
$lang['View_previous_month'] = 'View Previous Month'; 
$lang['View_next_month'] = 'View Next Month'; 
$lang['View_previous_year'] = 'View Previous Year'; 
$lang['View_next_year'] = 'View Next Year'; 
$lang['Calendar_interval'] = 'Interval'; 
$lang['Calendar_repeat'] = 'Repeat'; 
$lang['Calendar_start_monday'] = false; 
$lang['Date_selector'] = 'Date Selector'; // title/header for Date Selector Window  


//
// Mini calendar 
//
$lang['Mini_Cal_add_event'] = 'Add Event';
$lang['Mini_Cal_events'] = 'Upcoming Events';
 
// uses MySQL DATE_FORMAT - %c  long_month, numeric (1..12) - %e  Day of the long_month, numeric (0..31)
// see http://www.mysql.com/doc/D/a/Date_and_time_functions.html for more details
// currently supports: %a, %b, %c, %d, %e, %m, %y, %Y, %H, %k, %h, %l, %i, %s, %p
$lang['Mini_Cal_date_format'] = '%b %e';
$lang['Mini_Cal_date_format_Time'] = '%H:%i';

// if you change the first day of the week in constants.php, you should change values for the short day names accordingly
// e.g. FDOW = Sunday -> $lang['mini_cal']['day'][1] = 'Su'; ... $lang['mini_cal']['day'][7] = 'Sa'; 
//      FDOW = Monday -> $lang['mini_cal']['day'][1] = 'Mo'; ... $lang['mini_cal']['day'][7] = 'Su'; 
$lang['mini_cal']['day'][1] = 'Su'; 
$lang['mini_cal']['day'][2] = 'Mo'; 
$lang['mini_cal']['day'][3] = 'Tu'; 
$lang['mini_cal']['day'][4] = 'We'; 
$lang['mini_cal']['day'][5] = 'Th'; 
$lang['mini_cal']['day'][6] = 'Fr'; 
$lang['mini_cal']['day'][7] = 'Sa'; 
  
$lang['mini_cal']['month'][1] = 'Jan';  
$lang['mini_cal']['month'][2] = 'Feb';  
$lang['mini_cal']['month'][3] = 'Mar';  
$lang['mini_cal']['month'][4] = 'Apr';  
$lang['mini_cal']['month'][5] = 'May';  
$lang['mini_cal']['month'][6] = 'Jun';  
$lang['mini_cal']['month'][7] = 'Jul';  
$lang['mini_cal']['month'][8] = 'Aug';  
$lang['mini_cal']['month'][9] = 'Sep';  
$lang['mini_cal']['month'][10] = 'Oct';  
$lang['mini_cal']['month'][11] = 'Nov';  
$lang['mini_cal']['month'][12] = 'Dec';    

$lang['mini_cal']['long_month'][1] = 'January'; 
$lang['mini_cal']['long_month'][2] = 'February'; 
$lang['mini_cal']['long_month'][3] = 'March'; 
$lang['mini_cal']['long_month'][4] = 'April'; 
$lang['mini_cal']['long_month'][5] = 'May'; 
$lang['mini_cal']['long_month'][6] = 'June'; 
$lang['mini_cal']['long_month'][7] = 'July'; 
$lang['mini_cal']['long_month'][8] = 'August'; 
$lang['mini_cal']['long_month'][9] = 'September'; 
$lang['mini_cal']['long_month'][10] = 'October'; 
$lang['mini_cal']['long_month'][11] = 'November'; 
$lang['mini_cal']['long_month'][12] = 'December'; 


// 
// Shop 
// 
$lang['User_Inventory'] = 'Inventory of %s'; 	
$lang['Inventory'] = 'Inventory'; 
$lang['More'] = 'more...'; 
$lang['Items'] = 'Items';   


// 
// Ban card System
// 
$lang['Bancards_disabled'] = 'Sorry, but the ban card system of this board has been disabled. Please try again later.';
$lang['Give_G_card'] = 'Re-activate user';  
$lang['Give_Y_card'] = 'Give user warning #%d';  
$lang['Give_R_card'] = 'Ban this user now';  
$lang['Ban_update_sucessful'] = 'The banlist has been updated successfully';  
$lang['Ban_update_green'] = 'The user is now re-activated';  
$lang['Ban_update_yellow'] = 'The user has recieved a warning, and has now a total of %d warnings of a maximum %d warnings';  
$lang['Ban_update_red'] = 'The user is now banned';  
$lang['Ban_reactivate'] = 'Your account has been re-activated';  
$lang['Ban_warning'] = 'You\'ve recieved a warning';  
$lang['Ban_blocked'] = 'Your account is now blocked';  
$lang['Rules_ban_can'] = 'You <b>can</b> ban other users in this forum';  
$lang['user_no_email'] = 'The user has no e-mail, therefore no message about this action can be sent. You should submit him/her a private message';  
$lang['user_already_banned'] = 'The selected user is already banned';  
$lang['Ban_no_admin'] ='This user in an ADMIN and therefore cannot be warned or banned';  
$lang['Rules_greencard_can'] = 'You <b>can</b> un-ban users in this forum';  
$lang['Rules_bluecard_can'] = 'You <b>can</b> report posts to moderators in this forum';  
$lang['Give_b_card'] = 'Report this post to the moderators of this forum';  
$lang['Clear_b_card'] = 'This post has %d blue cards now. If you press this button you will clear this';  
$lang['No_moderators'] = 'The forum has no moderators, no reports can be therefore sent.';  
$lang['Post_repported'] = 'This post has now been reported to %d moderators';  
$lang['Post_repported_1'] = 'This post has now been reported to the moderator';  
$lang['Post_repport'] = 'Post Report'; // Subject in e-mail notification 
$lang['Post_reset'] = 'The blue cards for this post have now been reset.';  
$lang['Search_only_bluecards'] = 'Search posts with blue cards only'; 
$lang['Send_message'] = 'Click %sHere%s to write a message to the moderators or<br />'; 
$lang['Send_PM_user'] = 'Click %sHere%s to write a PM to the user or'; 
$lang['Link_to_post'] = 'Click %sHere%s to go to the reported post<br />--------------------------------<br /><br />'; 
$lang['Post_a_report'] = 'Post a report'; 
$lang['Report_stored'] = 'Your report has been entered successfully.'; 
$lang['Send_report'] = 'Click %sHere%s to go back to the original message'; 
$lang['Red_card_warning'] = 'You are about to give the user:%s a red card, this will ban the user, are you sure ?';  
$lang['Yellow_card_warning'] = 'You are about to give the user:%s a yellow card, this will issue a warning to the user, are you sure ?';  
$lang['Green_card_warning'] = 'You are about to give the user:%s a green card, this will unban the user, are you sure ?';  
$lang['Blue_card_warning'] = 'You are about to give the post a blue card, this will alert the moderators about this post, Are you sure you want to alert the moderators about this post ?';  
$lang['Clear_blue_card_warning'] = 'You are about to reset the blue card counter for this post, Do you wan to continue ?'; 
$lang['Warnings'] = '<b>Warnings:</b> %d'; // Shown beside users post, if any warnings given to the user 
$lang['Banned'] = 'Currently banned'; // Shown beside users post, if user is banned   

$lang['Give_BK_card'] = 'Vote to ban this user'; 
$lang['Already_banvoted'] = 'You have already placed a ban vote against this user!'; 
$lang['Ban_update_black'] = 'The user has received a ban vote, and has now a total of %d votes of a maximum %d votes'; 
$lang['Ban_votewarning'] = 'You\'ve recieved a ban vote'; 
$lang['user_already_banned'] = 'The selected user is already banned'; 
$lang['Black_card_warning'] = 'You are about to give the user:%s a black card, this will issue a vote to ban the user, are you sure ?'; 
$lang['VoteWarnings'] = 'Ban Votes : %d'; //shown beside users post, if any ban votes given to the user
$lang['BanVote_self'] = 'You can\'t vote to ban yourself. Obviously you have unresolved issues!';
$lang['Rules_voteban_can'] = 'You <b>can</b> ban vote against users in this forum'; 


// 
// Serverload & Unique Hits 
// 
$lang['Pages_served'] = ' pages loaded in last 5 mins'; 
$lang['Unique_hits'] = 'unique hits in last %s hours';   
$lang['ON'] = 'ON'; // This is for GZip compression
$lang['OFF'] = 'OFF'; 


// 
// Journal forum 
// 
$lang['Journal_reply_message'] = 'Only administrators, moderators and the topic starter can reply to this topic.<br />';  
$lang['Journal_topic_message'] = 'Only one topic per user is allowed in this forum.<br />';   


// 
// Instant Messenger List 
// 
$lang['Instant_messenger_list'] = 'Instant Messenger List'; 
$lang['Select_imtype_method'] = 'Instant Messenger'; 
$lang['All'] = 'All'; 
$lang['Viewing_IM_list'] = 'Viewing instant messenger list';   


// 
// User CP 
// 
$lang['User_CP_explain'] = 'Welcome to your User Control Panel. From here you can monitor, view and update your profile, preferences, subscribed forums and topics. You can also send messages to other users (if permitted).'; 
$lang['Profile_settings'] = 'Profile Settings'; 
$lang['Buddy'] = 'Friend'; 
$lang['Buddy_list'] = 'Friends'; 
$lang['Add_buddy'] = 'Add user to your friends list'; 
$lang['Personal_gallery'] = 'Your Gallery';   


// 
// Visit Counter 
// 
$lang['Visit_counter'] = 'This board has had <b>%d</b> visitors in total since '; // %s = number of users   


// 
// Banlist 
// 
$lang['Reason'] = 'Ban Reason'; 
$lang['No_reason'] = 'No Reason'; 
$lang['Ban_By'] = 'Banned By'; 
$lang['When_Banned'] = 'When Banned'; 
$lang['Viewing_banlist'] = 'Viewing banlist';   
$lang['No_bans'] = 'No Users have been Banned.'; 


// 
// Picture alert 
// 
$lang['Picture_alert'] = ' <span class="gensmall">(1 pic)</span>'; 
$lang['Pictures_alert'] = ' <span class="gensmall">(%d pics)</span>'; // %d is more than 1 pic, do not remove!   


// 
// Comments on users 
// 
$lang['Profile_comments'] = 'User comments'; 
$lang['Comments'] = 'Comments'; 
$lang['No_comments'] = 'No comments have been posted yet.'; 
$lang['Read_comments'] = 'Read all comments'; 
$lang['Empty_comments'] = 'You must fill out the comments field.'; 
$lang['Comments_added'] = 'Comment Added Successfully.'; 
$lang['Click_return_comments'] = 'Click %sHere%s to return to this users\' comments';   
$lang['No_comments_yourself'] = 'You cannot write a comment on yourself.';
$lang['Confirm_delete_comments'] = 'Are you sure you want to delete these comments?';
$lang['Comments_edited'] = 'User Comment Updated Successfully.';
$lang['Comments_deleted'] = 'User Comment Deleted Successfully.';
$lang['Click_return_viewprofile'] = 'Click %sHere%s to return to the profile';


//
// Staff
//
$lang['Staff'] = 'Staff';
$lang['Staff_about'] = 'Informations about %s'; // %s = username
$lang['Staff_level'] = array('Administrator', 'Junior Administrator', 'Moderator');
$lang['Staff_forums'] = 'Forums';
$lang['Staff_messenger'] = 'Messenger';
$lang['Staff_user_topic_day_stats'] = '%.2f topics per day'; // %.2f = topics
$lang['Staff_online'] = '<span style="color: #0000FF">online</span>';
$lang['Staff_year'] = 'year';
$lang['Staff_years'] = 'years';
$lang['Staff_week'] = 'week';
$lang['Staff_weeks'] = 'weeks';
$lang['Staff_day'] = 'day';
$lang['Staff_days'] = 'days';
$lang['Staff_hour'] = 'hour';
$lang['Staff_hours'] = 'hours';
$lang['Staff_minute'] = 'minute';
$lang['Staff_minutes'] = 'minutes';
$lang['Staff_since'] = '(since %s)'; // %s = period
$lang['Staff_ago'] = '(%s ago)'; // %s = period
$lang['Viewing_staff'] = 'Viewing staff'; 


//
// ChatBox
//
$lang['How_Many_Chatters'] = 'There are <b>%d</b> user(s) on chat now'; // %d replaced with number of users
$lang['Who_Are_Chatting' ] = '<b>%s</b>'; // %s replaced with username
$lang['ChatBox'] = 'ChatBox';
$lang['log_out_chat'] = 'You have successfully logged out from chat on ';
$lang['Send'] = 'Send';
$lang['Click_to_join_chat'] = '%sClick to join chat%s'; // %s replaced by a href links, do not remove! 
$lang['Login_to_join_chat'] = '%sLogin to join chat%s'; // %s replaced by a href links, do not remove! 


//
// Bot Crawler Tracker
//
$lang['Bot_subject'] = '%s has just crawled!'; // %s replaced with bot name
$lang['Bot_text'] = 'This e-mail was sent to you automatically, as %s has just indexed your forum.'; // %s replaced with bot name


//
// Shoutbox
//
$lang['Shoutbox'] = 'Shoutbox';
$lang['Shoutbox_disabled'] = 'Sorry, but the shoutbox feature of this board has been disabled. Please try again later.';
$lang['Viewing_Shoutbox']= 'Viewing shoutbox';
$lang['Post_shout'] = 'Post a shout';
$lang['Censor'] = 'Censor this shout';
$lang['Uncensor'] = 'Uncensor this shout';
$lang['Shout_body'] = 'Shout body';
$lang['Shout_censor'] = 'Shout has been censored';
$lang['Your_shout'] = 'Your shout';
$lang['Refresh'] = 'Refresh';


//
// Personal notes
//
$lang['Notes'] = 'Notes';
$lang['popup_notes'] = 'Pop up window for personal notes';
$lang['Filter_notes'] = 'Notes filtered. Press the search button again to display all notes.';
$lang['Post_a_new_note'] = 'Post a new note';
$lang['Edit_Note'] = 'Edit note';


//
// Registration Notification
//
$lang['New_user_registration'] = 'New user registration';


//
// Karma
//
$lang['Karma'] = 'Karma';
$lang['Applaud'] = 'Applaud';
$lang['Smite'] = 'Smelt';
$lang['Too_Soon'] = 'You cannot change another karma so soon after your last; please try again in a short while.';
$lang['No_Self_Karma'] = 'You cannot change your own karma.';
$lang['Karma_disabled'] = 'Sorry, but the Karma feature of this board has been disabled. Please try again later.';


//
// Forum Tour
//
$lang['Forum_tour'] = 'Forum tour';
$lang['No_forum_tour'] = 'Sorry, but the forum tour is currently unavailable.  Please try again later.';


//
// Desktop Plus
//
$lang['On'] = 'on';


//
// Forum enter limits
//
$lang['Forum_regdate_limit_error'] = 'Sorry, you are not authorized access to "%s" until you have been registered for a minimum of %d days.';
$lang['Forum_enter_limit_error'] = 'Sorry, you are not authorized access to "%s" until you have a minimum of %d posts.';


//
// PJIRC Chat
//
$lang['Chat_Room'] = 'Chat Room';
$lang['IRC_commands'] = 'IRC Chat Room Commands';
$lang['IRC_commands_explain'] = 'These commands will be performed when you log into the chat room. Separate each command with a semi-colon.<br />e.g. /msg nickserv identify password;<br />/beep';
$lang['IRC_disabled'] = 'Sorry, but the chat room feature of this board has been disabled. Please try again later.';
	

//
// Recycle Bin
//
$lang['Move_bin'] = 'Recycle this topic';
$lang['Topics_Moved_bin'] = 'The selected topics have been moved to the recycle bin.';
$lang['Bin_disabled'] = 'Sorry, but the Recycle bin feature of this board has been disabled. Please try again later.';
$lang['Bin_recycle'] = 'Recycle';


//
// Portal/Forum Modules
//	
$lang['Newest_members'] = 'Newest Members';
$lang['Top_posters'] = 'Top Posters';
$lang['Most'] = 'Most';
$lang['Random_user'] = 'Random User'; 
$lang['Random_user_explain'] = 'User of the moment is...'; 
$lang['Daily_wallpaper'] = 'Daily Wallpaper';
$lang['Dload_wallpaper'] = 'Click to download the wallpaper!';


//
// Lexicon
//	
$lang['Lexicon'] = 'Lexicon';
$lang['Viewing_Lexicon'] = 'Viewing %s';

$lang['Lexicon_search_error'] = 'No entries met your search criteria.';
$lang['Lexicon_error'] = 'No entries in this category.';

$lang['Click_return_lexicon'] = 'Click %sHere%s to return to the %s Index';

$lang['Keyword_count_main'] = 'Momentarily %s references in the %s exist.';
$lang['Keyword_count_cat'] = '%s entries are in this category.';
$lang['Letter_count'] = 'with letter %s';

$lang['show_all'] = 'all';
$lang['Show_only'] = 'Category';
$lang['overview'] = 'Overview';
$lang['generally'] = 'General';  // this is the name from the default categorie if set $lang['default'] = ' '
$lang['default'] = ' ';  // example to demonstrate the categorie-titel lang feature


//
// Spellchecker
//
$lang['Spell_title'] = 'SpellChecker Configuration';
$lang['Spell_explain'] = 'Here you can manage the spellchecker and dictionaries on your forum. This will allow you to add new languages and words to your database easily.';
$lang['Spell_enable'] = 'Enable Spellchecker';
$lang['Spell_import'] = 'Import Word Lists';
$lang['Spell_dict'] = 'Dictionary file to add';
$lang['Spell_lang'] = 'Select Language';
$lang['Spell_clear'] = 'Clear Existing Dictionary';
$lang['Spell_safe'] = 'Force Safe Mode';
$lang['Add_new_word'] = 'Add New Word';
$lang['Word'] = 'Word';


//
// Topic Thanks
//
$lang['thankful'] = 'Thankful Users';
$lang['thanks_to'] = 'thanks';
$lang['thanks_end'] = 'for this topic.';
$lang['thanks_alt'] = 'Thank topic';
$lang['thanked_before'] = 'Sorry, you have already posted thanks on this topic.';
$lang['thanks_add'] = 'Your thanks have been posted.';
$lang['thanks_not_logged'] = 'Sorry, you need to log in to thank a user\'s topic.';
$lang['thanked'] = 'user(s) are thankful for this topic.';
$lang['hide'] = 'Hide';
$lang['t_starter'] = 'Sorry, you cannot thank yourself.';
	
	
//
// Inline Ads
//
$lang['Sponsor'] = 'Sponsor';


// 
// Sitemap
// 
$lang['Sitemap'] = 'Sitemap';
$lang['Sitemap_viewing'] = 'Viewing Sitemap';
$lang['Sitemap_disabled'] = 'Sorry, but the Sitemap feature of this board has been disabled. Please try again later.';


//
// Digests
//
$lang['Digests'] = 'E-mail digests';
$lang['Digest_options'] = 'Digest Options';
$lang['Digest_confirm_title'] = 'Confirm Digest Subscription';
$lang['Digest_user_auto'] = 'Upon successful registration automatically subscribe me into the %s digest group';
$lang['Digest_user_new'] = 'Upon successful registration let me select my digest options';
$lang['Group_digests'] = 'Allow group digests';
$lang['Group_digest_explain'] = 'Set to Yes if you want the group moderator to be able to setup a digest for the members of this group. If set to No then the moderator will not be able to configure a digest.';
$lang['Group_digest'] = 'Group digest';
$lang['Urgent_post'] = 'Check this box if this is an URGENT message (to initiate an immediate Digest mailing). Do NOT abuse this feature.  It could take 30 seconds or more for the confirmation page to appear!  Do not click the SUBMIT button more than once!'; 
$lang['Urgent_stored'] = 'Your Urgent message has been entered successfully and the Urgent Digests will be sent out shortly.'; 
$lang['Allow_forum_digest'] = 'Allow digests for this forum';
	
	
//
// PayPal IP Group Subscriptions
//
$lang['LW_USER_ACCT_ERROR'] = 'Member with ID = %d does not exist.';
$lang['LW_WELCOME_REGISTERED'] = 'Thank you for registering. Your account has been created.';
$lang['LW_TRANSACTION_RECORDS'] = 'Transactions';
$lang['LW_EXPIRE_MEMBER_REMINDER'] = 'Your membership will be expired on <b>%s</b>';
$lang['LW_EXPIRE_TRIAL_REMINDER'] = 'Your trial period has <b>%d</b> day(s) left';
$lang['LW_WELCOME_REGIST_TRIAIL'] = 'Welcome %s, now you can surf our website for %d day(s) trial period.<br />After that if you want to continue accessing all our services, you will need to pay us subscription fee %s.';
$lang['LW_AMOUNT_TO_PAY_EXPLAIN'] = 'Upon confirmation of payment you will receive access to all the forums, be listed in the directory.';
$lang['LW_OUR_SUBSCRIPTION_FEE'] = 'Subscription fee:';
$lang['LW_ACCT_DISPLAY_FROM'] = 'Display transaction records for last';
$lang['LW_ALL_RECORDS'] = 'All Records';
$lang['LW_NO_RECORDS'] = 'No subscriptions.';
$lang['LW_ACCT_CREDIT'] = 'Credit';
$lang['LW_ACCT_DEBIT'] = 'Debit';
$lang['LW_ACCT_CURRENCY'] = 'Currency';
$lang['LW_ACCT_AMOUNT'] = 'Amount';
$lang['LW_ACCT_PLUS_MINUS'] = 'Credit / Debit';
$lang['LW_ACCT_TXNID'] = 'PayPal TXN ID';
$lang['LW_ACCT_STATUS'] = 'Status';
$lang['LW_ACCT_COMMENT'] = 'Remarks';
$lang['LW_NO_PRIVILEGE'] = 'You do not have the privilege to view this page.';
$lang['LW_Click_view_ACCT_RECORDS'] = 'Click %sHere%s to view your account transaction records';
$lang['LW_PAYMENT_DONE'] = 'Payment for subscription fee done successfully.';
$lang['LW_PAYMENT_PENDDING'] = 'Your payment is still pending, your account will be automatically upgraded after your payment has cleared or, after an administrator accepts your payment.<br />The notice of acceptance of the payment will be sent to your following e-mail account: %s by PayPal.';
$lang['LW_PAYMENT_DENIED'] = 'Payment from your account is denied, please contact an administrator if you have any questions.';
$lang['LW_PAYMENT_FAILED'] = 'Payment from your account failed, please try again later.';
$lang['LW_UPDATE_USER_ACCT_ERROR'] = 'Update member account error, please contact an administrator.';
$lang['LW_AMOUNT_TO_PAY'] = 'Amount to pay: ';
$lang['LW_ACCT_DEPOSIT_INTO'] = 'Payment';
$lang['LW_TOPUP_CONFIRM_TITLE'] = 'Confirm Your Payment';
$lang['Account_not_exist_lw'] = 'The account you specified does not exist.';
$lang['Account_activated_lw'] = 'Your account has already been set to access all areas.';
$lang['Click_return_login_lw'] = 'Click %sHere%s to login now.';
$lang['Click_return_activate_lw'] = 'Click %sHere%s to pay subscription fee to upgrade your account.';
$lang['Disabled_account_lw'] = 'Your account has not been activated.';
$lang['LW_PAYPAL_ACCT_ERROR'] = 'PayPal account has not been set up to receive funds, please contact an administrator to report this issue.';
$lang['LW_PAYMENT_DATA_ERROR'] = 'The amount of subscription fee is wrong.';
$lang['LW_YOU_ARE_VIP'] = 'Welcome %s, you are our <b>VIP</b>.';
$lang['L_LW_PAYMENTS'] = 'Paid Subscriptions';
$lang['LW_LOGIN_TO_PAY'] = 'Please login with your account name and password, you will be re-directed to payment page if you have not done so.';
$lang['LW_PAY_FOR_WHICH_MONTH'] = 'For subscription from <b>%s</b> to <b>%s</b>';
$lang['group_payment'] = 'Payment group';

$lang['Sorry_auth_paid_read'] = 'Sorry, but only <b>paid members</b> can read topics in this forum.'; 
$lang['LW_Welcome_Nopaid_Member'] = ''; 
$lang['Sorry_auth_paid_post'] = 'Sorry, but only <b>paid members</b> can post topics in this forum.'; 
$lang['L_LW_PAID_GROUP_NAME'] = 'The group name for paid member to join: '; 
$lang['LW_SELECT_A_GROUP'] = 'Please select a group to join'; 
$lang['L_LW_GROUP_TO_PAY'] = 'The group you want to join: '; 
$lang['LW_TOPUP_TITLE'] = 'Join Payment Group';
$lang['L_LW_GROUP_DESCRIPTION'] = 'Group Description: ';
$lang['L_LW_FOR_JOIN_GROUP'] = 'to join group: ';
$lang['L_LW_FOR_UPGRADE_GROUP'] = 'to upgrade to group: ';
$lang['L_LW_FOR_EXTEND_GROUP'] = 'to extend membership in group: ';
$lang['L_LW_USER_EXTEND_SAME_GROUP'] = 'You are going to extend your current membership.';
$lang['L_LW_USER_JOIN_GROUP'] = 'You are going to subscribe this group.';
$lang['L_LW_USER_UPGRADE_GROUP'] = 'You are going to upgrade your current membership.';
$lang['L_LW_USER_DOWNGRADE_GROUP'] = 'You cannot downgrade your membership, please wait for your current membership to expire.';
$lang['L_LW_UPGRADE_REMIND'] = 'Subscription Details: ';

$lang['Click_return_subscribe_lw'] = 'Click %sHere%s to select a group to join. You will need to pay a subscription fee.';
$lang['L_LW_GROUP_ALREADY_JOIN'] = 'The group you are currently in: '; 
$lang['L_LW_GROUP_VIEW_DETAIL'] = 'View this group subscription details: '; 
$lang['LW_PAYMENT_SUBSCRIPTION'] = 'Your group subscription has been submitted.'; 
	
	
//
// Referral System
//
$lang['Referral_System'] = 'Referral system';
$lang['Referral_Disabled'] = 'Referral system is currently disabled.';
$lang['Referral_Top_About'] = 'Below is a list of the top referrals, and the amount of members they have referred to this site and have joined!';
$lang['Referrals_Name'] = 'Referrals Username';
$lang['Referrals_Total'] = 'Total Referrals';
$lang['Referral_Text'] = 'If you would like to have others join this site (you get %s %s referral credit), use the following url: ';
$lang['Referral_Admin_New_Name'] = 'New User';
$lang['Referral_Admin_User_Delete'] = 'User Has Been Deleted.';


//
// Donations
//
$lang['LW_DONATIONS'] = 'Donations';
$lang['LW_ANONYMOUS_DONOR'] = 'Anonymous';
$lang['LW_MORE_DONORS'] = 'View All Donors';
$lang['LW_CURRENT_DONORS'] = 'View Donors For Current Goal';
$lang['L_LW_LAST_DONORS'] = 'Last %s Donors';
$lang['L_LW_TOP_DONORS_TITLE'] = 'Top %s Donors';
$lang['L_LW_DONORS_NAME'] = 'Donor\'s Name';
$lang['LW_DONORS_DISPLAY_FROM'] = 'Display donors for last:';
$lang['LW_NO_DONORS_YET'] = 'No donors yet';
$lang['LW_WE_HAVE_COLLECT'] = 'We have collected <b>%.2f</b> out of our goal of <b>%s</b>.';
$lang['LW_WANT_ANONYMOUS'] = 'I want to be anonymous.';
$lang['L_LW_DONATE_WAY'] = 'Your status as donor';
$lang['LW_DONATION_TO_POINTS'] = 'Thanks for your donation. In return, we are happy to increase your total %s by %d';
$lang['LW_DONATION_TO_WHO'] = 'Donation to %s'; // %s replaced with sitename
$lang['LW_DONATE_TITLE'] = 'Donation';
$lang['LW_AMOUNT_TO_DONATE'] = 'Amount to donate';
$lang['LW_AMOUNT_TO_DONATE_EXPLAIN'] = 'Thank you for your donation, it will greatly help us bring a better service.';
$lang['LW_DONATE_CONFIRM_TITLE'] = 'Confirm Your Donation Amount';
$lang['LW_ACCT_DONATE_US'] = 'Donate';
$lang['LW_DONATE_DONE'] = 'Thank you for your donation. It will help us bring a better service.';
$lang['LW_DONATE_DENIED'] = 'Sorry your donation is denied for some reason, please contact an Administrator if you have any questions.';
$lang['LW_DONATE_FAILED'] = 'Donation failed, please try again later.';
$lang['LW_CURRENCY_TO_PAY'] = 'Select the currency type';
$lang['LW_CURRENCY_TO_PAY_EXPLAIN'] = 'Currently we only accept %s.';
$lang['LW_PAYMENT_DATA_ERROR'] = 'The amount or the currency you entered is incorrect.';
$lang['LW_DONATION_TO_POSTS'] = 'Thank you for your donation. In return, we are glad to increase your total post count by %d';
$lang['LW_DONATION_TO_HELP'] = 'Make fast, secure payments with PayPal';
$lang['LW_NO_DONATIONS'] = 'No donations. Click %sHere%s to donate.';
$lang['Donations_for'] = 'For: <b>%s</b>'; 
$lang['Donations_ending'] = 'Ending: <b>%s</b>';
	
	
//
// Moved Topic Message
//
$lang['topic_moved'] = 'A topic you created has been moved.';
$lang['mail_send'] = 'The topicstarter(s) has been notified via e-mail .';
$lang['pm_send'] = 'The topicstarter(s) has been notified via private message.';
$lang['mail_pm_send'] = 'The topicstarter(s) has been notified via e-mail and private message.';
$lang['topic_moved_mail'] = 'Notify me when my topics are moved';
$lang['topic_moved_pm'] = 'PM me when my topics are moved';
$lang['topic_moved_pm_notify'] = 'Send notification with moved topic PM';
$lang['topic_moved_pm_notify_explain'] = 'Don\'t select this when you select "Notify me when my topics are moved"';
$lang['hello'] = 'Hello ';
$lang['pmtext1'] = 'This PM has been sent to you because the topic ';
$lang['pmtext2'] = ' you started';
$lang['pmtext3'] = 'has been moved from forum ';
$lang['pmtext4'] = ' in the catagory ';
$lang['pmtext5'] = ' to the catagory ';
$lang['pmtext6'] = ' in forum ';
$lang['pmtext7'] = 'You can go directly to your moved topic by clicking following link:';
$lang['profiletext'] = 'You can change Topic Moving preferences in your profile';

//
// Medal System
//
$lang['Medal_Control_Panel'] = 'Medal Control Panel';
$lang['Medals'] = 'Medals';
$lang['View_More'] = 'View more...';
$lang['Medal_amount'] = 'Amount: ';
$lang['Medal_Information'] = 'Medal Information';
$lang['Medal_name'] = 'Medal Name';
$lang['Medal_description'] = 'Medal Description';
$lang['Medal_image'] = 'Medal Image';
$lang['Medal_details'] = 'Award Details';
$lang['Medal_reason'] = 'Awarded Reason';
$lang['Medal_reason_explain'] = 'You can give a reason to why this medal this awarded to this user at this time. This is not compulsory.';
$lang['Medal_no_reason'] = '<i>No Reason was given</i>';
$lang['Medal_time'] = 'Awarded Time';
$lang['Medal_moderator'] = 'Medal Moderator';
$lang['No_medal_mod'] = 'No medal moderator';
$lang['Medal_Members'] = 'Users that have this medal';
$lang['Medal_Members_explain'] = 'Click on the username to edit award reason.';
$lang['No_medal_members'] = 'No users have this medal.';
$lang['No_medals_exist'] = 'No medals exist.';
$lang['Medal_not_exist'] = 'That medal does not exist.';
$lang['No_username_specified'] = 'No username specified.';
$lang['No_medal_id_specified'] = 'No medals specified.';
$lang['Medal_user_username'] = 'Award one or more specific users with this medal';
$lang['Medal_unmedal_username'] = 'Remove this medal from one or more specific users';
$lang['Medal_unmedal_username_explain'] = 'You can remove multiple users in one go using the appropriate combination of mouse and keyboard for your computer and browser.';
$lang['Medal_added'] = 'You have been awarded this medal.'; 
$lang['Medal_update_sucessful'] = 'User Medal Updated Successfully.';
$lang['Could_not_anonymous_user'] = 'You cannot give Anonymous a medal.';
$lang['Not_medal_moderator'] = 'You are not a moderator of this medal.';
$lang['Link_to_cp'] = 'Medal Control Panel';
$lang['Click_return_medal'] = 'Click %sHere%s to return to Medal Control Panel';
$lang['No_medal'] = 'No medals available.';


//
// Subscribe members 
//
$lang['Subscribe_members'] = 'Subscribe all users to this topic';
$lang['Unsubscribe_members'] = 'Unsubscribe all users from this topic';
$lang['Select_topic'] = 'Please select a topic to (un)subcribe to/from';
$lang['Topic_error'] = 'This topic does not exist.';
$lang['Subscribe_successful'] = '%s users succesfully subscribed to this topic.';
$lang['Unsubscribe_successful'] = 'All users succesfully unsubscribed from this topic.';
$lang['All_members_subscribed'] = 'All users are already subscribed.';


//
// Errors (not related to a
// specific failure on a page)
//
$lang['Information'] = 'Information';
$lang['Critical_Information'] = 'Critical Information';

$lang['General_Error'] = 'General Error';
$lang['Critical_Error'] = 'Critical Error';
$lang['An_error_occured'] = 'An Error Occurred';
$lang['A_critical_error'] = 'A Critical Error Occurred';
$lang['Session_invalid'] = 'Invalid Session. Please resubmit the form.';


//
// Fully Modded site specific only!
// Translation of these strings is not required 
//
$lang['FM_Index'] = 'Fully Modded Homepage';


//
// That's all, Folks!
// -------------------------------------------------

?>
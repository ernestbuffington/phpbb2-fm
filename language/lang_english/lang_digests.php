<?php
/** 
*
* @package lang_english
* @version $Id: lang_digests.php  Indemnity83 Exp $
* @copyright (c) Mark D. Hamill & Indemnity83
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
//
// This block is for digest config data
$lang['Digest_disable_user'] = 'Disable user digests';
$lang['Digest_disable_group'] = 'Disable group digests';
$lang['Use_system_time'] = 'Use the default system time for sending digests';
$lang['System_time_explain'] = 'Use the system time when a user signs up for digests or use the manually set defaults below';
$lang['Digest_time'] = 'Time when Daily/Weekly/Monthly digests are to be run';
$lang['Hours'] = 'hours';
$lang['Digest_weekly_day'] = 'Day of week when Weekly digest is to be run';
$lang['Digest_monthly_day'] = 'Day of month when Monthly digest is to be run';
$lang['Digest_auto_subscribe'] = 'Auto subscribe new members into a usergroup';
$lang['Digest_auto_explain'] = 'This will automatically put a user into the specified usergroup';
$lang['Digest_auto_group'] = 'Select the group for auto subscription of new members';
$lang['Digest_new_sign_up'] = 'Allow a member to sign-up for digests upon registration';
$lang['Digest_sign_up_explain'] = 'This will create a digest record that the user can then amend';
$lang['Pm_notify'] = 'Notify the user if they have any new PMs when sending the digest';
$lang['Pm_display'] = 'Include any new PMs with the digest';
$lang['Digest_show_stats'] = 'Show statistics';
$lang['Digest_show_stats_explain'] = 'Show the statistics panel on the email digest';
$lang['Override_theme'] = 'Set to yes to overide both the board, and user default themes';
$lang['Digest_theme'] = 'If override theme = "Yes" then this is the theme to use';
$lang['Digest_date_format'] = 'Digest date format';
$lang['Check_user_activity'] = 'Check user activity before sending digests';
$lang['User_activity_explain'] = 'This is the number of days before which a user is decided to no longer be an active member of the forum. After this point they will not recive any digests until they visit the forum again';
$lang['Activity_threshold'] = 'Set the numbers of days since a user was last active on this board';
$lang['Digest_logging'] = 'Digest logging';
$lang['Digest_log_days'] = 'The number of days that you want to retain in the log table';
$lang['Digest_log_days_explain'] = '(If this is set too high then you may end up creating a large table)';
$lang['Digest_moderator'] = 'Send a copy of this digest to the group moderator?';
$lang['Digest_support'] = 'HTTP address for support';
$lang['Digest_frequencies'] = 'Frequency Settings';
$lang['Allow_hourly'] = 'Allow hourly digests';
$lang['Allow_2hourly'] = 'Allow 2 hourly digests';
$lang['Allow_4hourly'] = 'Allow 4 hourly digests';
$lang['Allow_6hourly'] = 'Allow 6 hourly digests';
$lang['Allow_8hourly'] = 'Allow 8 hourly digests';
$lang['Allow_12hourly'] = 'Allow 12 hourly digests';
$lang['Allow_daily'] = 'Allow daily digests';
$lang['Allow_weekly'] = 'Allow weekly digests';
$lang['Allow_monthly'] = 'Allow monthly digests';
$lang['Digest_defaults'] = 'Digest Default Settings';
$lang['Digest_default_frequency'] = 'Frequency';
$lang['Digest_default_format'] = 'Format';
$lang['Digest_default_show_text'] = 'Show text';
$lang['Digest_default_show_mine'] = 'Show mine';
$lang['Digest_default_new_only'] = 'New only';
$lang['Digest_default_send_on_no_messages'] = 'Send on no messages';
$lang['Digest_short_text_length'] = 'Short text length';
$lang['Text_length_explain'] = "This is the text length that will be used when a user selects \"Short\" as their text length option";
$lang['True'] = 'True';
$lang['False'] = 'False';
$lang['Digest_subject'] = 'Digest Subject';
$lang['Digest_subject_explain'] = 'An entry here will override the Site Name as the subject in the digest email';
$lang['Digest_home'] = 'Digest home page';
$lang['Digest_home_explain'] = 'This is the page that a user will return to when they click on the link in the email';
$lang['Text_length_option'] = 'Text length option';
$lang['Full'] = 'Full';
$lang['Short'] = 'Short';
$lang['No_text'] = 'None';
$lang['Digest_allow_exclude'] = 'Allow forums to be excluded';
$lang['Digest_allow_exclude_explain'] = "Setting this to \"Yes\" will allow the user to specify that they want to EXCLUDE forums from the selection, otherwise the forums will be included";
$lang['Allow_urgent'] = 'Allow urgent digests';
$lang['Allow_urgent_explain'] = "Allow users to specify that a post is urgent and needs an urgent digest run.<br />NOTE: Setting this to \"Yes\" will necessitate running your Cron job at least every 5 minutes";
$lang['Run_urgent'] = 'Only process urgent digests';
$lang['Run_urgent_explain'] = "Setting this to \"Yes\" will only process any postings that are marked as Urgent when Urgent Digests are enabled. Setting this to \"No\" will mean that all digests will be processed during an urgent run";
$lang['Test_mode'] = 'Run in test mode';
$lang['Test_mode_explain'] = "Setting this to \"Yes\" will allow the digests to be processed but will not send out any emails.<br />This setting is used both for Digests and Digests confirm";
$lang['Supress_cron'] = 'Supress Cron output';
$lang['Allow_direct'] = 'Allow mail digests to be run directly';
$lang['Allow_direct_explain'] = "Setting this option to \"Yes\" will allow mail_digsts.php to be run from a browser. This option may need setting to \"Yes\" if you are running mail_digests from some scheduler or task other than Cron<br />USE THIS WITH CAUTION - there may be security issues";
$lang['Direct_pass'] = 'Direct run password';
$lang['Digest_theme_type'] = 'Use the .css file, themes table or overall_header.tpl for digests style';
$lang['Css'] = '.css';
$lang['Table'] = 'Table';
$lang['Header'] = 'Header';
$lang['Show_ip'] = 'Show the poster\'s IP address on the digest';
$lang['Digest_show_desc'] = 'Show forum description';
$lang['Digest_show_desc_expalin'] = 'Show the forum description on the digests form when the mouse is moved over the forum title';
$lang['Version'] = 'Version';

//
// This block is for add user
$lang['Digest_user_admin_explain'] = 'Here you can select a user to add into the digests system';
$lang['Digest_lookup_explain'] = 'No wildcards are needed, they will be added automatically.';
$lang['One_user_found'] = 'Only one user was found, you are being taken to that user';
$lang['Click_goto_digest_user'] = 'Click %sHere%s to edit this user\'s digest';
$lang['Click_return_digest_user'] = 'Click %sHere%s to return to Digest Add User';

//
// This block is for user management
$lang['Click_return_user_digest_admin'] = 'Click %sHere%s to return to Add User Digest';
$lang['Click_goto_digest'] = 'Click %sHere%s to add a digest';
$lang['Digest_description'] = 'Here you can see a list of the users on your board who have requested Digest reports.<br /><br />Click on their Username to edit their options.<br /><br />';
$lang['Digest_username'] = 'Username';
$lang['Digest_type'] = 'Digest Type';
$lang['Digest_freq'] = 'Freq';
$lang['Digest_format'] = 'Format';
$lang['Digest_show_text'] = 'Show Text';
$lang['Digest_mine'] = 'Show My Posts';
$lang['Digest_new'] = 'New Posts Only';
$lang['Digest_no_message'] = 'Send Empty';
$lang['Digest_length'] = 'Text Length';
$lang['Digest_forums'] = 'Subscribed Forums';
$lang['All_forums'] = 'All forums';
$lang['Last_digest_time'] = 'Last Digest';
$lang['Digest_date'] = 'Last Updated On';
$lang['Digest_user'] = 'User';
$lang['Digest_group'] = 'Group';
$lang['Digest_confirm_status'] = 'Confirm Date';
$lang['Digest_active'] = 'Active';
$lang['All'] = 'All';
$lang['Admin_panel_description'] = 'From this panel you can add, delete, edit, activate and reset a user or group digests.<br />Reseting a digest will force the digest to be sent the next time that the server sends messages and may contain posts that have already been seen in a digest.';
$lang['Admin_user_control_panel'] = 'Digest Users';
$lang['Admin_group_control_panel'] = 'Digest Groups';
$lang['Digest_data'] = 'Digest Data';
$lang['Group_members'] = 'Group Members';
$lang['Forum_not_active'] = 'Forum not active for digests';
$lang['Forums_included'] = 'Include exclude?';

//
// This block is for logging
$lang['Digest_log_description'] = 'Here you can see the contents of the log file.';
$lang['Digest_log_time'] = 'Log Date/Time';
$lang['Digest_run_type'] = 'Run Type';
$lang['Digest_log_status'] = 'Log Status';
$lang['Digest_empty'] = '';
$lang['All_users'] = 'All users';
$lang['All_status'] = 'All log status';
$lang['Filter_users'] = 'Filter Users';
$lang['Filter_status'] = 'Filter Log Status';

// Run types
$lang['rt']['0'] = 'HTML';
$lang['rt']['1'] = 'Cron';
$lang['rt']['2'] = 'Direct';
$lang['rt']['7'] = 'Test';
$lang['rt']['8'] = 'Urgent';
$lang['rt']['9'] = 'Admin';

// Log messages
$lang['lm']['1'] = 'No new posts';
$lang['lm']['2'] = 'Not sent (empty)';
$lang['lm']['3'] = 'Digest not due at this time';
$lang['lm']['4'] = 'Digest processed for user';
$lang['lm']['5'] = 'No processing required at this time';
$lang['lm']['6'] = 'User has no active forums';
$lang['lm']['7'] = 'User not active';
$lang['lm']['8'] = 'Digest not active';
$lang['lm']['9'] = 'User inactive';
$lang['lm']['10'] = 'Awaiting user confirmation';
$lang['lm']['11'] = 'Awaiting user activation';
$lang['lm']['12'] = 'No urgent data to process';
$lang['lm']['96'] = 'Incorrect direct password!';
$lang['lm']['97'] = 'Hacking attempt!';
$lang['lm']['98'] = 'SMTP server not available';
$lang['lm']['99'] = 'No data to process';

//
// This block is for add a group
$lang['Digest_group_explain'] = 'From this panel you can create a digest for a usergroup.';
$lang['Digest_select_group'] = 'Select Group';
$lang['Digest_no_groups'] = 'No digest groups';

//
// This block for admin mail
$lang['Digest_mail_explain'] = "By clicking on the \"Mail Digests\" button you will invoke the digest mailing script. This will then mail out any digests that are due to be processed at this time.";
$lang['Digest_run_mail'] = 'Mail Digests';
$lang['Digest_hack'] = 'Digest hacking attempt';
$lang['Password_error'] = 'Digest hacking attempt - direct run password error';

//
// This block is for admin verify
$lang['Digest_verify_explain'] = 'This form will check that all the users/groups in the Digest tables are valid users/groups.<br /><br /> If it is found that a user and/or a group that is in the Digest tables does not exist then that user/group will be removed from the Digest tables.';
$lang['User_id'] = 'User Id';
$lang['Group_id'] = 'Group Id';
$lang['Outcome'] = 'Outcome';
$lang['Verify_start'] = 'Checking ......';
$lang['Verify_end'] = '...... Verification complete';
$lang['Verify_not_in_user'] = 'Digest Table but not in User Table';
$lang['Verify_not_in_group'] = 'Digest Table but not in Groups Table';
$lang['Verify_removed'] = '<font color="red">REMOVED</font>';
$lang['Verify_in_user'] = 'Digest Table and User Table';
$lang['Verify_in_group'] = 'Digest Table and Groups Table';
$lang['Verify_ok'] = '<font color="green">OK</font>';
$lang['Verify_found'] = 'Found in ';

//
// This block is for admin confirm
$lang['Digest_confirm_explain'] = 'This form will send an email to each user that has subscribed to a digest, for each digest that are subscribed to. The email will ask them to confirm, within the specified number of days, that they still wish to receive that digest. Failure to respond will mean that the digest will no longer be sent to the user.';
$lang['Digest_confirm-settings'] = 'Confirm Settings';
$lang['Digest_confirm_days'] = 'Enter the number of days that users have in which to confirm';
$lang['Digest_confirm_subject'] = 'Digest Confirmation';
$lang['Digest_confirm_introduction'] = "You are currently registered with %s forums to receive periodical email forum digests. In order for you to continue receiving your %s digest would you please follow the link below. If you do not follow this link within %s days from the date of this email then this digest will cease.\n\nPlease note that if you have subscribed to more that one digest you may receive one of these emails for each of your digests and each one will need to be confirmed.";
$lang['Digest_link_comment'] = 'To continue receiving your %s digest follow the link below and then click on the "Make Digest Changes" button.';
$lang['Digest_confirm_disclaimer_text'] = "This email is being sent to registered members of %s forums and only because you subscribed to receive email digests. %s is completely commercial free. Your email address is never disclosed to outside parties. If you have any  questions or feedback on the format of the digests please send them to the Forum Administrator.";
$lang['Digest_confirm_progess_message'] = "Sending confirmation email to %s at %s";
$lang['Digest_confirm_complete'] = 'The processing of the confirmation emails is now complete. %s email messages have been sent.';
$lang['Digest_all_groups'] = 'Check if all usergroups required';

//
// This block for digest times
$lang['Digest_times'] = 'Digest Times';
$lang['Digest_times_explain'] = 'These are the current times';
$lang['Server_time'] = 'The server time is currently';
$lang['Board_time'] = 'The forum time is currently';
$lang['User_time'] = 'The user time is currently';
$lang['Digest_popup_message'] = 'Show the current times';

//
// This block is for general lang definitions
$lang['Digests_disabled_user'] = 'Sorry, but User Digests are currently unavailable. Please try again later.';
$lang['Digests_disabled_group'] = 'Sorry, but Group Digests are currently unavailable. Please try again later.';
$lang['Digest_options'] = 'Digest Options';
$lang['Digest_activity'] = 'Is this digest active?';
$lang['Digest_format'] = 'Format';
$lang['Digest_show_message_text'] = 'Show Message Text';
$lang['Digest_show_my_messages'] = 'Show My Messages';
$lang['Digest_frequency'] = 'Digest Frequency';
$lang['Digest_send'] = 'Time when the last digest was sent';
$lang['Digest_date'] = 'Date when the last digest was sent';
$lang['Digest_new_only'] = 'Show only new messages since I last logged in';
$lang['Digest_send_empty'] = 'Send empty digests';
$lang['Digest_message_size'] = 'Maximum characters per message in digest';
$lang['Sun'] = 'Sunday';
$lang['Mon'] = 'Monday';
$lang['Tue'] = 'Tuesday';
$lang['Wed'] = 'Wednesday';
$lang['Thu'] = 'Thursday';
$lang['Fri'] = 'Friday';
$lang['Sat'] = 'Saturday';
$lang['Digest_html'] = 'HTML';
$lang['Digest_text'] = 'Text';
$lang['Digest_delete'] = 'Delete this digest';
$lang['Digest-delete_explain_user'] = 'Checking this box will remove this digest option. You will no longer receive this digest';
$lang['Digest-delete_explain_group'] = 'Checking this box will remove this digest option. The members of the group will no longer receive this digest';

// Digest frequencies
$lang['df']['1'] = 'Hourly';
$lang['df']['2'] = '2 Hourly';
$lang['df']['4'] = '4 Hourly';
$lang['df']['6'] = '6 Hourly';
$lang['df']['8'] = '8 Hourly';
$lang['df']['12'] = '12 Hourly';
$lang['df']['24'] = 'Daily';
$lang['df']['168'] = 'Weekly';
$lang['df']['672'] = 'Monthly';
$lang['df']['998'] = '';
$lang['df']['999'] = 'n/a';

$lang['Usergroup'] = ' usergroup';
$lang['Digest_user_auto'] = 'Upon successful registration automatically subscribe me into the %s digest group';
$lang['Digest_user_new'] = 'Upon successful registration let me select my digest options';

//
// This block is for lang specific to mail_digests.php
$lang['Digest_introduction_html'] = "As you requested, here is the latest digest of messages posted on %s%s%s forums. Please come and join the discussion!";
$lang['Digest_introduction_text'] = "As you requested, here is the latest digest of messages posted on %s forums. Please come and join the discussion!";
$lang['Digest_disclaimer_html'] = "This digest is being sent to registered members of %s forums and only because you requested it. %s is completely commercial free. Your email address is never disclosed to outside parties. See our %sFAQ%s for more information on our privacy policies. You can change or delete your subscription by logging into %s from the %sDigest Page%s. (You must be logged in to change your digest settings.) If you have any questions or feedback on the format of this digest please send them to the %sForum Administrator%s. (%s)";
$lang['Digest_disclaimer_text'] = "This digest is being sent to registered members of %s forums and only because you subscribed to receive email digests. %s is completely commercial free. Your email address is never disclosed to outside parties. See our FAQ for more information on our privacy policies. You can change or delete your subscription by logging into %s from the Digest Page. (You must be logged in to change your digest settings.) If you have any  questions or feedback on the format of the digests please send them to the Forum Administrator. (%s)";
$lang['Digest_salutation'] = 'Dear';
$lang['Digest_read_more'] = '( %sRead more%s... or ...%sreply now%s )';
$lang['PM_read_more'] = '( %sRead more%s )';
$lang['Digest_subject_line'] = 'Forum Posting Digest';
$lang['Full_posts'];
$lang['On'] = ' on ';
$lang['Urgent_reply'] = '[NOTE: if you are sending an urgent reply to this message, you must click the Urgent Checkbox on the reply page!]'; 

//
// This block is for lang specific to the html in mail_digests.php
$lang['Html_title'] = 'Digest Emailer';
$lang['Html_info'] = 'Information';
$lang['Html_database'] = 'Database type';
$lang['Html_phpbb'] = 'phpBB version';
$lang['Html_digest_ver'] = 'Digests MOD version';
$lang['Html_server'] = 'Server time';
$lang['Html_url'] = 'Site URL';
$lang['Html_start'] = 'Start execution';
$lang['Html_paragraph_1'] = 'This file is used to prepare and send email digests out to subscribers. Typically this script would be called by some kind of service like cron and this display would go unseen to everyone. In this instance either you have saved the output of the cron job to a file, or are invoking the script manually. I hope that the information provided below can help solve any problems, or assure you of sucess. If you run across any problems then you should visit http://www.dormlife.us or phpBB for support.<br />Now, we begin...';
$lang['Html_gather'] = 'Gather a list of subscriptions to be processed';
$lang['Html_last'] = '\'s last %s digest was on ';
$lang['Html_not_confirm'] = ' has not yet confirmed that they still want to receive their %s digest.';
$lang['Html_not_active'] = ' has not been active on this board for at least ';
$lang['Html_days'] = ' days and will not be processed.';
$lang['Html_we'] = '. We';
$lang['Html_will'] = ' <font color="green">WILL</font> ';
$lang['Html_will_not'] = ' <font color="red">WILL NOT</font> ';
$lang['Html_process'] = ' process this digest because they requested a <font color="blue">';
$lang['Html_digest'] = '</font> digest.';
$lang['Html_total'] = 'A total of ';
$lang['Html_marked'] = ' digests have been marked to be processed';
$lang['Html_complete'] = 'COMPLETE - SCRIPT IS ENDED';
$lang['Html_no_process'] = 'There are no digests to process at this time, the script is exiting';
$lang['Html_gather'] = 'Gather a list of forums';
$lang['Html_not_query'] = 'Could not query forums table:';
$lang['Html_found'] = ' were found';
$lang['Html_process_marked'] = 'Process the marked subscriptions';
$lang['Html_username'] = 'Username';
$lang['Html_mine'] = 'Show mine';
$lang['Html_empty'] = 'Send Empty';
$lang['Html_new'] = 'New Only';
$lang['Html_format'] = 'Format';
$lang['Html_last_visit'] = 'Since the users last visit was ';
$lang['Html_last_digest'] = ' and thier last digest was ';
$lang['Html_only_new'] = ' and they requested to only see new messages ';
$lang['Html_last_visit_used'] = 'their last visit time will be used';
$lang['Html_last_digest_time_used'] = 'their last digest time will be used';
$lang['Html_all_messages'] = 'Since the user wants to see all messages since their last digest, everything since ';
$lang['Html_pulled'] = ' will be pulled';
$lang['Html_permissions'] = 'This user has read permissions in the following forums :: ';
$lang['Html_opted'] = 'This user has opted to receive digests for the following forums :: ';
$lang['Html_all'] = 'All';
$lang['Html_receive'] = 'So, this user will be receiving messages from the following forums :: ';
$lang['Html_hide_own'] = 'The user also opted to hide their own posts, so any posts from user ';
$lang['Html_omitted'] = ' will be omitted';
$lang['Html_building'] = 'Building ';
$lang['Html_no_new_topics'] = 'There are no new topics';
$lang['Html_body'] = '\'s digest body using ';
$lang['Html_faq'] = 'faq.';
$lang['Html_digests'] = 'digests.';
$lang['Html_digest_with'] = 'A digest with ';
$lang['Html_messages_for'] = ' messages was built for ';
$lang['Html_sent_to'] = 'The digest was sent to ';
$lang['Html_empty_digests'] = 'The user requested not to receive empty digests, so no digest was sent';
$lang['Html_last_step'] = 'Last step, Update the database';
$lang['Html_success'] = 'User was processed successfully';
$lang['Html_exiting'] = 'All digest processing is complete, the script is exiting';
$lang['Html_ip'] = 'Poster\'s IP:';

//
// This block is for lang specific to digests.php
$lang['Digest_explanation'] = "Digests are email summaries of messages posted here that are sent to you periodically. Digests are sent on a schedule you set with the frequency below. You can specify the forums for which you want message summaries, or you can elect to receive all messages for all forums for which you are allowed access.<br /><br />Consisent with our privacy policy digests contain no \"spam\", nor is your email address used in any way connected to an advertisement. You can, of course, cancel your digest subscription at any time by simply coming back to this page. Most users find digests to be very useful. We encourage you to give it a try!";
$lang['Digest_name'] = 'Digest name';
$lang['Digest_name_explain'] = 'Here you can enter an optional name to help you identify this digest';
$lang['Digest_format_desc'] = 'HTML is highly recommended unless your email program cannot display HTML';
$lang['Digest_new_only_desc'] = 'This will filter out any messages posted prior to the date and time you last visited that would otherwise be included in the digest.';
$lang['Digest_frequency_desc'] = 'Select the frequency that you want to have the digest sent to you';
$lang['Digest_size_desc'] = 'Caution: setting this too high may make for very long digests. A link is provided for each message that will let you see the full content of the message.';
$lang['Digest_include_forums'] = 'Are the selected forums to be included or excluded?';
$lang['Digest_include_forums_explain'] = "If you set this option to \"Include\" then the selected forums will be the only ones in this digest.<br />If you set this option to \"Exclude\" then you will see all the forums except the selected ones in the digest.";
$lang['Include'] = 'Include';
$lang['Exclude'] = 'Exclude';
$lang['Digest_select_forums'] = 'Selected forums';
$lang['Digest_create'] = 'Your digest settings were successfully created';
$lang['Digest_modify'] = 'Your digest settings were successfully updated';
$lang['Digest_unsubscribe'] = 'You have been unsubscribed and will no longer receive a digest';
$lang['Digest_no_forums_selected'] = 'You have not selected any forums, so you will be unsubscribed';
$lang['Digest_all_forums'] = 'All Subscribed Forums';
$lang['Digest_submit_text'] = 'Make Digest Changes';
$lang['tl']['50'] = '50';
$lang['tl']['150'] = '150';
$lang['tl']['300'] = '300';
$lang['tl']['500'] = '500';
$lang['tl']['1000'] = '1000';
$lang['Characters'] = 'characters';
$lang['Digest_accept'] = "By clicking the \"Make Digest Changes\" button below you agree to having email digests sent to the current email address in your profile.";

//
// This block is for lang specific to User Control panel
$lang['Panel_description'] = 'From this panel you can add, delete, edit, activate and reset your digests. Reseting your digest will force a digest to be sent the next time that the server sends messages and may contain posts you have already seen in a digest.<br /><b>Note:</b> If you add a new digest then any settings in the new digest will overwrite those in any exisiting digests with the result that you may remove one or more of your digests.';
$lang['Digest_control_panel'] = 'Digest User Control Panel';
$lang['My_digests'] = 'My Personal Digests';
$lang['Edit'] = 'Edit';
$lang['Status'] = 'Status';
$lang['Active'] = 'Active';
$lang['Activate'] = 'Activate';
$lang['Deactivate'] = 'Deactivate';
$lang['Reset'] = 'Reset';
$lang['My_group_digests'] = 'My Group Digests';
$lang['Create_new'] = '<< Create a new digest >>';
$lang['Add_digest'] = ' digest';
$lang['Digest_user_list'] = 'Digest User List';

//
// This block is for Digest Data Dump
$lang['Cannot_open_file'] = 'Cannot open output file';
$lang['Digest_dump_title'] = 'Create a Dump File of the Digest Tables';
$lang['Digest_dump_explain'] = 'This facility should only be used for support purposes on the request of the Digest Support team.';
$lang['Digest_run_dump'] = 'Run dump';
$lang['Dump_code'] = 'Enter dump code';
$lang['Dump_code_explain'] = 'This is the code that will have been supplied by the Digest Support team when you were asked to run the data dump';
$lang['Dumping_config'] = 'Dumping Config table...';
$lang['Done'] = 'done';
$lang['Dumping_user'] = 'Dumping User data...';
$lang['Dumping_group'] = 'Dumping Group data...';
$lang['Dumping_log'] = 'Dumping the Log table...';
$lang['Dump_complete'] = '...digest data dump complete';
$lang['Incorrect_dump_code'] = 'Incorrect dump code entered';
$lang['Code_error'] = 'Input Code Error';
$lang['Dump_start'] = 'Starting Digest data dump...';

//
// Do NOT translate this block
// START
$lang['Dump_title'] = 'DIGEST DATA DUMP for: ';
$lang['Dump_date'] = 'Date: ';
$lang['Dump_config'] = 'CONFIG TABLE';
$lang['Dump_eof'] = 'EOF';
$lang['Dump_user'] = 'USER DATA';
$lang['Dump_user_heading'] = 'Username, Digest name, Last visit, Last digest, Frequency, Active, Format, Show text, Show mine, New only, Send on no, Include/exclude, Confirm status';
$lang['Dump_group'] = 'GROUP DATA';
$lang['Dump_group_heading'] = 'Group name, Digest name, Last digest, Frequency, Active, Format, Show text, Show mine, New only, Send on no Include/exclude';
$lang['Dump_log'] = 'LOG TABLE';
$lang['Dump_log_heading'] = 'Log time, Run type, Username, Digest type, Frequency, Log status';
$lang['Dump_end'] = 'END OF DUMP';
// END
// Do NOT translate this block
//

//
// That's all, Folks!
// -------------------------------------------------
?>
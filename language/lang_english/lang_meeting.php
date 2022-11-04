<?php
/** 
*
* @package lang_english
* @version $Id: lang_meeting.php,v 1.3.18 2006/08/09 oxpus Exp $
* @copyright (c) 2004 OXPUS
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

// Administration
$lang['Meeting_manage_explain'] = 'From here you can manage all saved meetings. You can edit or delete them.';
$lang['Meeting_delete'] = 'Delete a meeting?';
$lang['Meeting_delete_explain'] = 'Are you sure to delete this meeting?';
$lang['Meeting_add'] = 'Add new meeting';
$lang['Meeting_edit'] = 'Edit meeting';

// Configuration
$lang['Meeting_config'] = 'Meeting Configuration';
$lang['Meeting_config_explain'] = 'From here you can manage all basic settings for meetings on your board.';
$lang['User_allow_enter_meeting'] = 'Allow users to create meetings';
$lang['User_allow_enter_meeting_explain'] = 'If you will set here YES, each user can create meetings. Say NO here to enable this only on ACP by an Admin. GROUPS will enable this to users who are a member of minimum one of the following groups.';
$lang['User_allow_edit_meeting'] = 'Allow users to edit meetings';
$lang['User_allow_edit_meeting_explain'] = 'If you will set here YES, each user can edit meetings. Say NO here to enable this only on ACP by an Admin.';
$lang['User_allow_delete_meeting'] = 'Allow Users to delete their Meetings';
$lang['User_allow_delete_meeting_explain'] = 'If you say YES here, each user can delete his own meeting. Say NO here to disable this option.';
$lang['User_allow_delete_meeting_comments'] = 'Allow Users to delete their Meeting Comments';
$lang['User_allow_delete_meeting_comments_explain'] = 'If you say YES here, each user can delete his own meeting comments. Say NO here fo disable this option.';
$lang['Meeting_notify'] = 'Enable email notification for user sign on/off';
$lang['Meeting_notify_explain'] = 'Enable the notifications to the board email adress, if a user will sign of/off a meeting or changing the promise';

// Forum part
$lang['Meeting'] = 'Meeting Management';
$lang['Meeting_until'] = 'Period for registration';
$lang['Meeting_location'] = 'Location';
$lang['Meeting_subject'] = 'Title';
$lang['Meeting_closed'] = 'Closed';
$lang['No_meeting'] = 'No meeting found';
$lang['Meeting_no_period'] = 'Period for registration is over';
$lang['Meeting_desc'] = 'Description';
$lang['Meeting_link'] = 'Link';
$lang['Meeting_places'] = 'Maximum count of places';
$lang['Meeting_usergroup'] = 'Usergroups';
$lang['Meeting_filter'] = 'Filter by field';
$lang['Meeting_all'] = 'Every status';
$lang['Meeting_open'] = 'Active';
$lang['Meeting_sign_on'] = 'Sign on the meeting with';
$lang['Meeting_sign_edit'] = 'Change promise to';
$lang['Meeting_sign_off'] = 'Sign off the meeting';
$lang['Meeting_sign_off_explain'] = 'Are you sure to sign off this meeting?';
$lang['Meeting_detail'] = 'Meeting details';
$lang['Meeting_viewlist'] = 'Meeting list';
$lang['Meeting_userlist'] = 'Signed on user';
$lang['Meeting_user_joins'] = 'registered users';
$lang['Meeting_no_user'] = 'Currently no signed on user';
$lang['Meeting_free_places'] = 'Total free places';
$lang['Meeting_sure_total'] = 'Currently signed on user in per cent';
$lang['Meeting_sure_total_user'] = 'Current user promises in per cent';
$lang['Meeting_statistic'] = 'Statistics';
$lang['Meeting_comments'] = 'Comments about this meeting';
$lang['Meeting_comment'] = 'User comment';
$lang['Meeting_enter_comment'] = 'Enter a comment';
$lang['No_active_meetings'] = 'Currently no planned meetings';
$lang['One_active_meeting'] = 'Currently one planned meeting';
$lang['Active_meetings'] = 'Currently %s planned meetings';
$lang['Meeting_create_by'] = 'Created by <a href="%s" class="genmed">%s</a>';
$lang['Meeting_edit_by'] = 'Last edited by <a href="%s" class="genmed">%s</a>';
$lang['Meeting_start_value'] = 'Start value for assign drop down';
$lang['Meeting_recure_value'] = 'Interval for this drop down';
$lang['Meeting_unwill_message'] = 'A user will not visit a meeting';
$lang['Meeting_unwill_user'] = 'User %s will not visit the meeting %s.';
$lang['Meeting_unjoin_message'] = 'A user have unjoined a meeting';
$lang['Meeting_unjoin_user'] = 'User %s have unjoined from meeting %s.';
$lang['Meeting_join_message'] = 'A user have joined a meeting';
$lang['Meeting_join_user'] = 'User %s have joined to meeting %s.';
$lang['Meeting_change_message'] = 'A user have changed the sign on to a meeting';
$lang['Meeting_change_user'] = 'User %s have changed the sign on to meeting %s.';
$lang['Meeting_signons'] = 'Your signons';
$lang['Meeting_guest_overall'] = 'Overall number of guests all user can max. invite';
$lang['Meeting_guest_single'] = 'Number of guests a user can max. invite';
$lang['Meeting_remain_guest_text'] = 'You have invited %s guests, but currently there are only %s places free.<br />Please go back and invite less people.';
$lang['Meeting_user_guest'] = ' + one guest';
$lang['Meeting_user_guests'] = ' + %s guests';
$lang['Meeting_user_guest_popup'] = ' + <a href="javascript:void(0)" onclick="openguestpopup(%s, %s);">one guest</a>';
$lang['Meeting_user_guests_popup'] = ' + <a href="javascript:void(0)" onclick="openguestpopup(%s, %s);">%s guests</a>';
$lang['Meeting_guests'] = 'Guests';
$lang['Meeting_owm_guests'] = 'Your guests';
$lang['Meeting_prenames'] = 'Prenames';
$lang['Meeting_names'] = 'Names';
$lang['Meeting_invite_guests'] = ' with ';
$lang['Meeting_overall_guest_places'] = ' + overall %s guests';
$lang['Meeting_single_guest_places'] = ' (%s guests each user)';
$lang['Meeting_remain_guest_places'] = '. Currently %s guests invitable.';
$lang['Meeting_no_guest_limit'] = 'Enter 0 to disable this limit';
$lang['Meeting_all_users'] = 'All Users';
$lang['Meeting_group'] = 'Usergroup';
$lang['Meeting_no_signon'] = 'Refusal';
$lang['Meeting_yes_signon'] = 'Promise';
$lang['Meeting_yes_signons'] = 'Promises';
$lang['Meeting_intervall_explain'] = 'Use 0/100 to let the users just refuse or promise a meeting';
$lang['Meeting_only_registered'] = 'Only for registered users!';
$lang['Meeting_guest_names'] = 'User must enter the names of invited guests';
$lang['Meeting_guest_names_explain'] = 'This option will replace the drop down for the number of invited guests into a form to enter the guest names.<br />The user will invite the number of guests he enters in the new form; based on the maximum allowed number of guests.';
$lang['Meeting_guestname_entering_explain'] = 'Enter here the name of your guests. Be sure you enter the prename <b>and</b> the name of your guests. To sign off a guest, delete the row and submit the form.';

// Email functions
$lang['Meeting_mail'] = 'Send e-mail to all registered people';
$lang['Meeting_mail_subject'] = 'E-mail subject';
$lang['Meeting_mail_text'] = 'E-mail message';
$lang['Meeting_mail_to'] = 'Send e-mail to';
$lang['Meeting_mail_all'] = 'all registered people';
$lang['Meeting_mail_sign_yes'] = 'only promised people';
$lang['Meeting_mail_sign_no'] = 'only not promised people';

?>
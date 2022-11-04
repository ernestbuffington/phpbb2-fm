<?php
/** 
*
* @package lang_english
* @version $Id: lang_admin_bookmakers.php,v 2.0.1 2004/10/17 00:31:19 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

$lang['bookie_process_header'] = "Bookmakers - Process Bets";
$lang['bookie_process_explain'] = "Here, you can process the bets where the meetings have taken place already. Just select your winning options, and hit GO! Winning options are as follows:<br /><br />YES: The selections was a winner<br />No: The selection lost<br />EWW: The selection won, but the bet was placed as Each Way (for each way bets, if you have them allowed)<br />EWP: The selection gained a place, and the bet was placed as Each Way (for each way bets, if you have them allowed)<br />REF: Refund the stake. Select this option if the meeting was cancelled, or the users should recieve their stake returned for whatever reason<br /><br />If you wish to update the odds for any bet, just change them before you hit GO!";
$lang['bookie_process_current'] = "Normal Bets - Admin Set - That Are Waiting To Be Processed";
$lang['bookie_process_username'] = "Username";
$lang['bookie_process_time'] = "Time And Date";
$lang['bookie_process_meeting'] = "Bet Meeting";
$lang['bookie_process_selection'] = "Bet Selection";
$lang['bookie_process_stake'] = "Total Stake";
$lang['bookie_process_oddsenter'] = "Odds For This Bet";
$lang['bookie_process_winenter'] = "Winner?";

// For Purging
$lang['bookie_oneweekbuttontext'] = "One Week";
$lang['bookie_twoweeksbuttontext'] = "Two Weeks";
$lang['bookie_monthbuttontext'] = "One Month";
$lang['bookie_deleteallbuttontext'] = "Delete All Bets";
$lang['bookie_purge_picktime'] = "Choose Timescale Of Bets To <u>Remain</u>";
$lang['bookie_purge_header'] = "Forum Bookmakers - Purge Old Bets";
$lang['bookie_purge_explain'] = "Here, you can delete older bets from your forum database. This will not only save storage space, but it will ensure that less bets are shown within the Bookmaker pages. The <b>overall statistitcs</b> for the Bookmaker will <b>not</b> be changed by deleting old bets, <b>unless</b> you delete <b>all</b> bets. Deleting all bets effectively resets the Bookmaker Feature to when it was first installed.<br /><br />The following options are available:<br />-<b>ONE WEEK:</b> Leaves only bets placed within the last week.<br />-<b>TWO WEEKS:</b> Leaves only bets placed within the last two weeks.<br />-<b>ONE MONTH:</b> Leaves only bets placed within the last month.<br />-<b>DELETE ALL BETS:</b> Totally resets the Bookmaker Feature, deleting all bets and statistics.<br /><br />";
$lang['process_select'] = "SELECT AN OPTION";
$lang['bookie_process_button'] = "Process Bet";
$lang['bookie_process_yes'] = "YES";
$lang['bookie_process_no'] = "NO";
$lang['bookie_process_ew_win'] = "EWW";
$lang['bookie_process_ew_place'] = "EWP";
$lang['bookie_process_refund'] = "REF";
$lang['bookie_one_week'] = "Bets older than one week successfully deleted!";
$lang['bookie_two_weeks'] = "Bets older than two weeks successfully deleted!";
$lang['bookie_one_month'] = "Bets older than one month successfully deleted!";
$lang['bookie_reset'] = "All bets and statistics deleted. The Bookmakers is now fully reset.";

// for version 2.0.2
$lang['bookie_set_header'] = "Admin Set Bets";
$lang['bookie_set_header_explain'] = 'Here, you can set bets for your forum users to gamble on. You can add a meeting, build onto an existing meeting, edit bet selections, delete bet selections, delete entire meetings! Just click on the appropiate icon to work with your bets.';
$lang['bookie_set_time'] = "Bet Time";
$lang['bookie_set_odds'] = "Odds";
$lang['bookie_slip_time'] = 'Time And Date Meeting Takes Place';
$lang['bookie_slip_time_explain'] = 'The date should be in <b>DD-MM-YYYY format</b>. E.G. 10-01-2004, 25-12-2005 etc...<br /><br />The time entered, should be in <b>24 hour format</b>. E.G 12:00, 13:05, 01:25, 19:45 etc...';
$lang['bookie_slip_meeting_explain'] = 'The meeting is a description of where the event is taking place. E.G. Celtic Park, Ibrox Stadium, Newmarket, Ayr etc...<br /><br />Please note there is a 50 character maximum.';
$lang['bookie_slip_selection_explain'] = 'This is a description of your bet. E.G. Celtic to beat Rangers 2-1, Prso to score first goal, Shergar, Red Rum etc...<br /><br />Please note there is a 100 character maximum.';
$lang['bookie_set_enterodds'] = "Enter Odds For This Bet Selection";
$lang['bookie_set_enterdetails'] = "Enter Bet Details";
$lang['bookie_set_submitbuton'] = "Submit";
$lang['bookie_set_deletebuton'] = "Delete";
$lang['bookie_pm_winnings'] = "Winnings";
$lang['bookie_set_process_success'] = "Bet Processed Succesfully!<br />Redirecting...";
$lang['bookie_set_pm_mesage'] = "Your Bet Has Been Processed";

// for version 2.0.3
$lang['bookie_multiple_selection'] = 'Selection/Odds';
$lang['bookie_multiple_success'] = 'Bet(s) placed successfully!';
$lang['bookies_notall_fileds'] = 'You must complete all the fields!';
$lang['bookie_succesful_edit'] = 'Bet Edited Succesfully. Redirecting...';
$lang['bookie_place_success'] = 'Bet Placed Succesfully. Redirecting...';
$lang['bookies_odds_value'] = 'You must enter values for both sets of odds!';
$lang['bookies_need_field'] = 'You must enter a field to edit!';
$lang['bookies_need_time_date'] = 'You must enter a time and a date!';
$lang['bookies_invalid_date'] = 'You can\'t choose a date from the past!';
$lang['bookie_allow_edit_stake'] = 'Allow Users To Edit Their Stakes';
$lang['bookie_allow_edit_stake_exp'] = 'If this option is set to yes, users will be able to edit their stakes for any bet they have placed. They may only edit their stakes <i>before</i> the time of meeting.';
$lang['bookie_allow_pm'] = 'Send An Automatic PM When Bets Are Processed';
$lang['bookie_allow_pm_exp'] = 'Setting this to yes will send an automatic PM to all users who have bet on the particular selection you have processed.';
$lang['bookie_config_update_success'] = 'Configuration values updated succesfully.<br />Redirecting...';
$lang['bookie_leaderboard'] = 'Leaderboard Setting';
$lang['bookie_leaderboard_exp'] = 'Choose how many positions to show on the leaderboard on the Bookmakers page.';
//
//for 2.0.5
//
$lang['bookie_edit_delete_bet'] = 'Edit/Delete';
$lang['bookie_edit_bet'] = 'Edit This Selection';
$lang['bookie_delete_bet'] = 'Delete This Selection';
$lang['bookie_edit_header'] = 'Edit A Pending Bet';
$lang['bookie_edit_header_explain'] = 'Change any field and hit submit to edit.<br /><br />Changing the Date, the Meeting, the Star status, Each Way option or The Category will apply to all bets for that Meeting. <br /><br />Changing the bet selection or odds will apply to this bet selection only.';
$lang['bookie_delete_header'] = 'Delete A Pending Bet';
$lang['bookie_delete_header_explain'] = 'Please review the information below, for the bet you wish to delete. If you are sure you wish to delete this bet, then simply hit the <b>Delete</b> button.';
$lang['bookie_cancel'] = 'Cancel';
$lang['bookie_current_bets'] = 'Current Bets Set By Admin';
$lang['bookie_current_bets_explain'] = 'Below is a list of bets currently set by Admin. Click on any meeting to expand and view it\'s selections. Click on the appropiate icon to work with these bets.';
$lang['bookie_process_go'] = 'Go!';
$lang['bookie_allow_each_way'] = 'Accept Each Way Bets';
$lang['bookie_allow_each_way_exp'] = 'Select this option, only if you wish to accept each way bets. When selected, you will be able to define a meeting as an Each Way Meeting when setting the meeting. This will in turn provide a check box on the Bookmakers Page, where users can choose to place a particular selection as each way. The check box will only show against Each Way meetings.';
$lang['bookie_allow_user_bets'] = 'Allow User Set Bets';
$lang['bookie_allow_user_bets_exp'] = 'Select this option to allow users to enter their own bets via the Bookmakers page. If you allow this, you will have to double check the details when processing. User bets will be flagged on the processing page. If this option is set to <b>Conditional</b>, then users will only be able to set their own bets if there are no Admin Set Bets to choose from.';
$lang['bookie_frac_or_dec'] = 'Fractional Or Decimal Odds';
$lang['bookie_frac_or_dec_exp'] = 'You may only operate one system, either fractional or decimal odds.';
$lang['bookie_fractional'] = 'Fractional';
$lang['bookie_decimal'] = 'Decimal';
$lang['bookie_starbet'] = 'Make This A Star Meeting?';
$lang['bookie_starbet_exp'] = 'Star Meetings are meetings with generous odds, or just meetings that you want to stand out from the rest. An icon will appear next to Star Meetings, bringing them to the attention of users.';
$lang['bookie_star_alt'] = 'Star Meeting';
$lang['icon_bookie_add_selection'] = 'Add More Selections'; 
$lang['bookie_nobets'] = 'There are currently no bets set by Admin';
$lang['bookie_select_meeting'] = 'Select A Stored Meeting?';
$lang['bookie_confirm_selection_type'] = 'Choose A Selection Template';
$lang['bookie_confirm_selection_type_head_exp'] = 'You may choose a previously stored Selection Template to add to this meeting, or you can enter the new selections manually.';
$lang['bookie_hardcode'] = 'Enter Selections Manually';
$lang['bookie_hardcode_exp'] = 'To enter your selections manually, simply enter how many selections you wish to add to this meeting, into the box on the right.';
$lang['bookie_template'] = 'Use A Saved Selection Template';
$lang['bookie_template_exp'] = 'To use a saved Selection Template, simply choose one from the selection box on the right. Leave unselected to disable.';
$lang['icon_bookie_drop_meeting'] = 'Delete This Entire Meeting';
$lang['bookie_template_name'] = 'Selection Template Name';
$lang['bookie_template_exists'] = 'A Selection Template with that name already exists!';
$lang['bookie_template_select'] = 'Use A Template?';
$lang['icon_bookie_drop_template'] = 'Delete This Entire Selection Template';
$lang['bookie_edit_template'] = 'Edit This Selection Template';
$lang['bookie_set_nextbuton'] = 'Next';
$lang['bookies_template_new_success'] = 'New Template added successfully!';
$lang['bookie_template_edit_success'] = 'Template edited successfully!';
$lang['bookie_template_added_success'] = 'New selections added successfully!';
$lang['bookie_delete_template_confirm'] = '<br />Are you sure you want to delete this Template?<br />&nbsp;';
$lang['bookie_template_deletion_cancelled'] = 'Action cancelled. The Template has <b>not</b> been deleted.';
$lang['bookie_template_deletion_success'] = 'Template deleted successfully!';
$lang['bookie_delete_all'] = 'Delete All';
$lang['bookie_edit_meeting'] = 'Edit This Meeting';
$lang['bookie_delete_meeting'] = 'Delete This Meeting';
$lang['bookie_delete_meeting_confirm'] = 'Are you sure you want to delete this meeting?';
$lang['bookie_meeting_delete_success'] = 'Meeting(s) deleted successfully!';
$lang['bookie_delete_meeting_all_confirm'] = 'Are you sure you want to delete all stored meetings?';
$lang['bookie_delete_all_meetings'] = 'Delete All Stored Meetings';
$lang['bookie_meeting_delete_all_success'] = 'All stored meetings deleted successfully!';
$lang['bookie_meeting_edit_success'] = 'Meeting edited successfully!';
$lang['icon_bookie_add_meeting'] = 'Add A New Meeting';
$lang['bookie_new_meeting_header'] = 'Add A New Meeting';
$lang['bookie_new_meeting_header_exp'] = 'Fill in the fields to set a new meeting. You may enter a new name for this meeting, or you can select a stored meeting to use as the name. If you enter a new name, it will be stored for next time.';
$lang['bookie_build_header'] = 'Enter Selection Details';
$lang['bookie_build_header_exp'] = 'Enter the selection name and odds for each selection. If you have chosen too few selections, you can always add more later. If you have chosen too many, just leave any field for that selection blank and it won\'t be counted.';
$lang['bookie_meetings_header'] = 'Stored Bet Meetings';
$lang['bookie_meetings_header_exp'] = 'Every time you input a Bet Meeting whilst setting bets, the meeting is stored for future use. This is to save Admin from inputing the same information over and over again, as the stored meetings are selectable from a box whilst setting bets. Click on the appropiate icon to work with your stored meetings.';
$lang['bookie_meetings_edit_header'] = 'Edit A Stored Meeting';
$lang['bookie_meetings_edit_header_exp'] = 'Change the text in the input field, and this meeting will overwritten with the new name. Changes will only apply to bets placed in the future.';
$lang['bookie_selections_header'] = 'Stored Bet Selection Templates';
$lang['bookie_selections_header_exp'] = 'Stored bet selection templates will save time whilst setting bets. When you select a template to use, you only have to fill in the odds for each selection. This is useful for bets that can have the same, or similar selections each time. Click on the appropiate icon to work with your selection templates.<p>Click on a template name, to expand it and view the selections stored for this template.</p>';
$lang['bookie_selections_edit_header'] = 'Edit A Selection Template';
$lang['bookie_selections_edit_header_exp'] = 'Change any field, and the selection will be overwritten with the new details. If you want to remove a selection from this template, just delete the text from the input field and it will be removed from the template. Changes will only affect future bets.';
$lang['bookie_template_name_input'] = 'Change Template Name';
$lang['bookie_template_name_input_exp'] = 'Enter a new name for this template, if you wish. This is the name that appears in the selection box whilst placing bets.';
$lang['bookie_add_to_template'] = 'Add Selections To A Template';
$lang['bookie_confirm_sel_fields'] = 'How Many Selections For This Template';
$lang['bookie_confirm_sel_fields_exp'] = 'Enter how many selections to add to this template. You can always add or delete selections at a later date.';
$lang['bookie_new_template_head'] = 'Create A New Template';
$lang['bookie_template_name_input'] = 'Enter A Template Name';
$lang['bookie_template_name_input_exp'] = 'Enter a name for this template. You should choose a name that describes the selections within the template.';
//
// for 2.0.6
//
$lang['bookie_no_odds'] = 'You haven\'t entered any odds!';
$lang['Stake_Refunded'] = 'Stake Refunded';
$lang['bookie_selection_review_head'] = 'Current Selections Assigned To This Meeting';
$lang['bookie_delete_marked'] = 'Delete Checked';
//
// for 2.0.7
//
$lang['bookie_eachwaybet'] = 'Make This An Each Way Meeting?';
$lang['bookie_eachwaybet_exp'] = 'Each Way meetings have a checkbox on the Bookmakers Page, allowing users to place their bets as Each Way, Win, or both.';
$lang['bookie_process_current_ew'] = 'Each Way Bets - Admin Set -  That Are Waiting To Be Processed';
$lang['bookie_process_current_no'] = 'No Normal Admin Bets Require Processing';
$lang['bookie_process_current_ew_no'] = 'No Each Way Admin Bets Require Processing';
$lang['bookie_process_current_no_normal'] = 'No Bets Require Processing';
$lang['bookie_res_refund'] = 'Refund';
$lang['bookie_pm_eachway'] = 'Each Way';
$lang['bookie_pm_result'] = 'Result';
$lang['bookie_res_refund'] = 'Refund';
$lang['bookie_res_loss'] = 'Lost';
$lang['bookie_res_win'] = 'Won';
$lang['bookie_res_place'] = 'Placed';
$lang['bookie_config_welcome'] = 'Welcome Message';
$lang['bookie_config_welcome_exp'] = 'If you want your main Bookmakers Page to display a welcome message, enter the text here. This text will show above the Leaderboard on the Bookmakers Page. There is a <b>250</b> character <b>maximum</b> for your welcome message';
$lang['bookie_timezone_warning'] = 'Your timezone does not match the Board\'s Timezone. Please remedy this before setting or processing bets.';
$lang['bookie_process_user_ew'] = 'Each Way Bets - User Set - That Are Waiting To Be Processed';
$lang['bookie_process_user_ew_exp'] = 'These are <b>each way</b> bets that users have set themselves. Pay close attention to the bet details, and remember to input the correct odds.';
$lang['bookie_process_user_norm'] = 'Normal Bets - User Set - That Are Waiting To Be Processed';
$lang['bookie_process_user_norm_exp'] = 'These are <b>normal</b> bets that users have set themselves. Pay close attention to the bet details, and remember to input the correct odds.';
$lang['bookie_select_by_time'] = 'Select Bet By Meeting Time';
$lang['bookie_select_by_time_exp'] = 'Enter the time of the meeting you wish to edit, and all meetings from that time with bet selections used will be displayed. You will then be able to re-process any of these individual bets.';
$lang['bookie_edit_past_header'] = 'Edit A Past Bet';
$lang['bookies_invalid_date_future'] = 'You can\'t choose a date in the future!';
$lang['bookies_edit_time_nomatch'] = 'No bets match the time you have entered!';
$lang['bookie_userbet_conditional'] = 'Conditional';
$lang['bookie_set_pm_mesage_reprocess'] = 'Your Bet has Been Re-Processed';
$lang['bookie_set_pm_mesage_reprocess_exp'] = 'Due to an error whilst processing this bet originally, it has been neccessary to re-process the bet. This has now been done, and your updated winnings are as detailed below:';
$lang['bookie_sync_header'] = 'Synchronize All Bookmaker Statistics';
$lang['bookie_sync_explain'] = 'If you have purged bets from your database, this can cause your statistics to get out of sync. If your statistics don\'t seem to add up, hit the Synchronize button and all statistics will be recalculated and updated. Please press the button <b>once only</b>, and give the page time to refresh, especially with databases that contain many bets.';
$lang['bookie_sync_success'] = 'Synchronization Successful!';
$lang['bookie_cannot_edit_past'] = 'This bet was placed before version 2.0.8 of this software. Editing is not possible!';
//
// for 208
//
$lang['bookie_config_all_com'] = 'Allow Commission';
$lang['bookie_config_all_com_exp'] = 'If you allow commission, then the person who places Admin Set Bets will recieve a commission for every bet that is placed on those bets. The percentage is defined below, and this will be the percentage of the stake that the user places on the relevant bet. This option is to encourage your admins or moderators to set bets.';
$lang['bookie_config_gen_head'] = 'General Configuration For Bookmaker Page';
$lang['bookie_config_com_per'] = 'Commission Percentage';
$lang['bookie_config_com_per_exp'] = 'This is how much the Admin/Moderator who set the bets should recieve in commission when a user places one of their bets. This percentage is a percentage of the stake that the user places on the bet.';
$lang['bookie_config_bookie_set'] = 'Game Settings';
$lang['bookie_config_bookie_misc'] = 'Miscellaneous Settings';
$lang['bookie_config_bets_outstanding'] = 'There are bets in the system that are outstanding. These may be bets that are in the future, or bets that are awaiting processing. You can only change the commission percentage value when all bets have been processed and none are outstanding. Please process all bets before attempting this action.<br /><br />Action Cancelled!<br />&nbsp;';
$lang['bookie_config_bets_outstanding_allow'] = 'There are bets in the system that are outstanding. These may be bets that are in the future, or bets that are awaiting processing. You can only change the commission setting to allow payment of commission when all bets have been processed and none are outstanding. Please process all bets before attempting this action.<br /><br />Action Cancelled!<br />&nbsp;';
$lang['bookie_comm_head'] = 'Commission Payments';
$lang['bookie_comm_head_exp'] = 'This is a list of people who have recieved commission for setting bets, and how much they have recieved.';
$lang['bookie_comm_num_bets'] = 'No. Meetings';
$lang['bookie_commission'] = 'Paid Commission';
$lang['bookie_comm_user'] ='User';
$lang['bookie_commission_pending'] = 'Pending Commission';
$lang['bookie_commission_due'] = 'Commission Accrued';
$lang['bookie_category'] = 'Category';
$lang['bookie_edit_category'] = 'Edit Category';
$lang['bookie_delete_category'] = 'Delete Category';
$lang['bookie_category_header'] = 'Meeting Categories';
$lang['bookie_category_header_exp'] = 'Here, you can define Categories for your Meetings. Every Meeting must belong to a Category, which is set whilst setting the bets. The <b>default</b> Category is "<b>%s</b>" and whilst the name of the default Category can be edited, this category cannot be deleted.';
$lang['bookie_categories_edit_header'] = 'Edit A Category';
$lang['bookie_categories_edit_header_exp'] = 'To edit a category\'s name, just change the text and hit submit';
$lang['bookie_category_edit_success'] = 'Category name changed successfully!';
$lang['bookie_category_name_long'] ='The name you have chosen is too long. Category names are limited to 30 characters maximum';
$lang['bookie_category_delete_success'] = 'Categories deleted successfully!';
$lang['bookie_delete_all_categories'] = 'Delete All Categories?';
$lang['bookie_delete_category_all_confirm'] = 'Are you sure you wish to delete all stored categories? Choosing this option will leave only the one default category remaining.';
$lang['bookie_category_delete_all_success'] = 'All Categories deleted succesfully. Only the default Category now remains.';
$lang['icon_bookie_add_category'] = 'Add A Category';
$lang['bookie_add_cat_explain'] = 'Fill in the field and hit submit to add a new Category. Category name should not exceed 30 characters.';
$lang['bookie_category_add_success'] = 'New Category added successfully!';
$lang['bookie_category_exp'] = 'You must select a category for this meeting. The Category can be changed at any time. Setting Categories to meetings will help your users find the type of meetings they like to bet on.';
$lang['bookie_def_date_explain'] = 'You may set a <b>Default Date and Time</b> for your bets. The default date will be pre-selected for you when you set a new meeting. This will save you from selecting the same things over and over. You do not have to select all the fields, so if you just wanted to select the Year and Month you could. This is optional, and remember that you may override the default date whilst setting bets, if you wish. You may also select a <b>default Category</b>, for similar reasons.';
$lang['bookie_def_date'] = 'Default Date And Time';
$lang['bookie_defdate_success'] = 'Default values updated successfully!';
$lang['bookie_purge1_title'] = 'Leave One Week\'s Bet Data';
$lang['bookie_purge2_title'] = 'Leave Two Week\'s Bet Data';
$lang['bookie_purge_mon_title'] = 'Leave One Month\'s Bet Data';
$lang['bookie_purge_all_title'] = 'Delete All Bet Data';
$lang['bookie_purge1_text'] = 'Are you sure you want to carry out this action? Doing this will delete all processed bet data, leaving only the data collated within the last week.';
$lang['bookie_purge2_text'] = 'Are you sure you want to carry out this action? Doing this will delete all processed bet data, leaving only the data collated within the last two weeks.';
$lang['bookie_purge_mon_text'] = 'Are you sure you want to carry out this action? Doing this will delete all processed bet data, leaving only the data collated within the last month.';
$lang['bookie_purge_all_text'] = 'Are you sure you want to carry out this action? Doing this will delete all processed bet data and all outstanding bets. This effectively resets the Bookmakers back to it\'s original state when first installed.';
$lang['bookie_canceled'] = 'Action Cancelled!';
$lang['bookie_config_allow_min_bet'] = 'Set A Minimum Bet';
$lang['bookie_config_allow_min_bet_exp'] = 'If you set a minimum bet, users must bet at least this amount on each selection. Leave this field blank or <b>0</b> to disable.';
$lang['bookie_config_allow_max_bet'] = 'Set A Maximum Bet';
$lang['bookie_config_allow_max_bet_exp'] = 'If you set a maximum bet, this will be the most that users can bet on any selection. Leave this field blank or <b>0</b> to disable.';
$lang['bookie_def_odds'] = 'Assign Default Odds For These Selections';
$lang['bookie_def_odds_exp'] = 'Assigning default odds will save you from allocating odds for each selection. This will save time for meetings where odds will all be the same for each selection, or where odds are unknown and must be edited at a later date. Leave blank to disable.';
$lang['bookie_def_cat'] = 'Default Category';
$lang['bookie_def_cat_exp'] = 'Assigning a default category is handy when you have multiple meetings of the same category type to input. This saves you having to select the relevant category repetitively.';
$lang['bookie_default_vars'] = 'Default Settings';
$lang['bookie_bet_restrict'] = 'Restrict To One Bet Per Meeting';
$lang['bookie_bet_restrict_exp'] = 'You may restrict users to only one bet per meeting. This will ensure that they cannot indulge in "spread betting" or trying to manipulate the odds. This also prevents users from placing "nuisance" bets.';
$lang['bookie_no_bets_to_process'] = 'You have not selected a win type';

?>
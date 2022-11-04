<?php
/** 
*
* @package lang_english
* @version $Id:	admin_sub_settings.php,v 1.0.0.1 2004/10/29 17:49:33 acydburn Exp $
* @copyright (c) 2004 Loewen Enterprise - Xiong Zou
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
//
// Display Topup.php
//
$lang['L_IPN_Subscribe_term_title'] = 'Subscription Terms: <b>Recurring Payment Method</b>';
$lang['L_IPN_Subscribe_free'] = 'Free';
$lang['L_IPN_Subscribe_for_first'] = ' for the first ';
$lang['L_IPN_Subscribe_then'] = 'Then';
$lang['L_IPN_Subscribe_for_next'] = ' for the next ';
$lang['L_IPN_Subscribe_for_following'] = ' for the following every ';
$lang['L_IPN_Subscribe_auto_renew'] = 'Your subscription will be automatically renewed unless you unsubscribe.';
$lang['L_IPN_Subscribe_for_every'] = ' for the every ';
$lang['L_IPN_Subscribe_term_manual'] = 'Subscription Terms: <b>Manual Payment Method</b>';
$lang['L_IPN_Subscribe_manual_renew'] = 'Your subscription will expire after expiration date, to keep your subscription, you have to manually pay subscription fee every ';
$lang['L_IPN_Subscribe_cancel_paypal'] = 'You can <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=%s"><img src="https://www.paypal.com/en_US/i/btn/cancel_subscribe_gen.gif" border="0" alt="" title="" /></a> from this group.<br />Note: Your unsubscription will take effect only when your current expiration date is reached.';
$lang['L_IPN_Subscribe_extend'] = 'Extend your subscription';
$lang['L_IPN_Subscribe_paypal_sub_url'] = 'https://www.paypal.com/cgi-bin/webscr';
$lang['L_IPN_Subscribe_to_grp'] = 'Subscribe to group - ';
$lang['L_IPN_Subscribe_paypal_button_alt'] = 'Make payments with PayPal - it\'s fast, free and secure!';


//display page_header
$lang['L_IPN_Subscribe_header_welcome'] = 'Welcome %s, your current subcriptions: ';
$lang['L_IPN_Subscribe_expire_date'] = ' [Expires at %s]';

//display at groupcp.php
$lang['L_IPN_Subscribe_this_grp'] = '%sSubscribe to this group%s';
$lang['L_IPN_Subscribe_Payment_grp'] = 'This is a payment group: ';


//display at user subscription administration
$lang['L_IPN_user_sub_enplain'] = 'Here you can change your users\' payment group subscription information.';
$lang['L_IPN_user_sub_yes'] = 'Yes';
$lang['L_IPN_user_sub_no'] = 'No';
$lang['L_IPN_user_sub_Update'] = 'Update';
$lang['L_IPN_user_sub_info'] = 'User Subscription Information';
$lang['L_IPN_user_sub_info_exp'] = 'From this panel you can modify the users\' subscription information. You can add them to a group and set an expiration date. Note that the expiration date must follow the format "yyyy/mm/dd hh:mm:ss" exactly.';
$lang['L_IPN_grp_name'] = 'Group Name';
$lang['L_IPN_grp_inornot'] = 'In this group?';
$lang['L_IPN_grp_expire_date'] = 'Expiration Date';
$lang['L_IPN_grp_action'] = 'Action';
$lang['L_IPN_user_sub_updated'] = 'User Subscription Information Updated Successfully.';
$lang['L_IPN_click_update_again'] = 'Click %sHere%s to return to this user again';
$lang['L_IPN_click_return'] = 'Click %sHere%s to return to User Subscription Administration';

//display IPN Log
$lang['L_IPN_log_title_explain'] = 'Search the IPN for each user or list transaction logs for all users. Note: you can leave the field blank to search all transactions. If the username can not be found, it will output all transactions too.';
$lang['L_LW_USERNAME'] = 'User Account';

//display subscribe settings
$lang['L_SUB_SETTINGS'] = 'Subscription Settings';
$lang['L_SUB_EXTRA_DAYS'] = 'Extra Days to Subscriber';
$lang['L_SUB_EXTRA_DAYS_EXPLAIN'] = 'Since PayPal will delay on charging payment and for the purpose of reward too, give your subcriber some extra days, e.g. 2.';

$lang['LW_TRIAL_PERIOD'] = 'Trial Period';
$lang['LW_TRIAL_PERIOD_EXPLAIN'] = 'The trial period for member to access your site, based on days, greater or equal to zero.';
$lang['LW_OUR_PAYPAL_CURRENCY_CODE'] = 'The currency code your PayPal account supports. Must be one of <b>USD</b>, <b>EUR</b>, <b>GBP</b>, <b>CAD</b>, or <b>JPY</b>.';
$lang['LW_OUR_PAYPAL_ACCT'] = 'Primary PayPal Address';
$lang['LW_OUR_PAYPAL_ACCT_EXPLAIN'] = 'Your primary PayPal account to receive payment from members.';
$lang['L_LW_BUSINESS_PAYPAL_ACCT'] = 'Premier/Business PayPal Address';
$lang['L_LW_BUSINESS_PAYPAL_ACCT_EXPLAIN'] = 'If you do not have premier/business account, enter the same address as your primary account.';
$lang['LW_ACCT_PRIMARY_CURRENCY'] = 'Primary Currency';
$lang['L_DONATE_CURRENCY'] = 'Supported Currencies';
$lang['L_DONATE_CURRENCY_EXPLAIN'] = 'Currencies you will support for donations. Each currency type must end with semi-colon, e.g. USD;GBP;EUR;';
$lang['LW_PAYPAL_ACCT_SETTINGS_TITLE'] = 'PayPal IPN Settings';

//donations
$lang['L_DONATION_SETTINGS'] = 'Donation Settings';
$lang['L_LW_DISPLAY_X_DONORS'] = 'Last Donors';
$lang['L_LW_DISPLAY_X_DONORS_EXPLAIN'] = 'Number of newest donors you want to display in Top Donors blocks.';
$lang['L_LW_TOP_DONORS'] = 'Display Top / Last Donors';
$lang['L_LW_DONATION_DESCRIPTION'] = 'Donations Reason';
$lang['L_LW_DONATION_DESCRIPTION_EXPLAIN'] = 'The reason or description for donations, if not needed, leave empty.';
$lang['L_LW_DONATION_GOAL'] = 'Donation Goal';
$lang['L_LW_DONATION_GOAL_EXPLAIN'] = 'Current goal of donations you want to collect, if no goal, leave as 0.';
$lang['L_LW_DONATION_START'] = 'Collection Period';
$lang['L_LW_DONATION_START_EXPLAIN'] = 'Start and end dates of the current goal. Date format must be yyyy/mm/dd, if not required, leave empty.';
$lang['L_LW_DONATION_STARTS'] = 'Starts';
$lang['L_LW_DONATION_ENDS'] = 'Ends';


$lang['L_LW_DONATION_POINTS'] = '%s per Dollar'; // %s replaced with Points name
$lang['L_LW_DONATION_POINTS_EXPLAIN'] = 'If you do not want to give %s to donors, set to 0.'; // %s replaced with Points name
$lang['L_LW_POSTS_COUNTS'] = 'Posts per Dollar';
$lang['L_LW_POSTS_COUNTS_EXPLAIN'] = 'If you do not want to give posts to donors, set to 0. You can not choose both points and posts to give to donors, only one.';
$lang['L_LW_DONATE_TOGRP_ONE'] = 'First Usergroup for Donation';
$lang['L_LW_DONATE_TOGRP_ONE_EXPLAIN'] = 'Usergroup ID of the first usergroup a user will join. 0 will not join any usergroup.';
$lang['L_LW_TOGRPONE_AMOUNT'] = 'Min. Donation for First Usergroup';
$lang['L_LW_TOGRPONE_AMOUNT_EXPLAIN'] = 'The donor will only join the first usergroup if they donated more than the amount specified.';
$lang['L_LW_DONATE_TOGRP_TWO'] = 'Second Usergroup for Donation';
$lang['L_LW_DONATE_TOGRP_TWO_EXPLAIN'] = 'Usergroup ID of the second usergroup a user will join. 0 will not join any second usergroup.';
$lang['L_LW_TOGRPTWO_AMOUNT'] = 'Min. Donation for Second Usergroup';
$lang['L_LW_TOGRPTWO_AMOUNT_EXPLAIN'] = 'The donor will only join the second usergroup if they donated more than the amount specified.';
$lang['L_LW_TORANK_ID'] = 'Rank for Donation';
$lang['L_LW_TORANK_ID_EXPLAIN'] = 'Rank ID that your user\'s rank will be set to after donation. 0 to not use this feature.';

// Manual Donation
$lang['L_DONOR_CONFIGURATION_EXPLAIN'] = 'The form below will allow you to manually add donations.';
$lang['L_DONOR_GENERAL_SETTINGS'] = 'Donation Information';
$lang['L_DONATE_MONEY'] = 'Amount Donated';
$lang['L_DONATE_DATE'] = 'Donation Date';
$lang['L_DONATE_DATE_EXPLAIN'] = 'The date format must be YYYY/MM/DD hh:mm:ss, e.g. 2004/09/12 17:00:02';
$lang['L_TRANSACTION_ID'] = 'Transaction Identification Number';
$lang['L_DONOR_PAY_ACCOUNT'] = 'Account Payment Received From';
$lang['L_DONOR_PAY_ACCOUNT_EXPLAIN'] = 'The account the user donated from, e.g. Savings.';

$lang['New_donor_record_added'] = 'Donation Added successfully.';
$lang['Click_return_add_donor'] = 'Click %sHere%s to add another donation';

// Currrencies
$lang['L_CURRENCY_GENERAL_SETTINGS'] = 'Currency Settings';
$lang['L_DONATE_USD_TO_PRI'] = 'Exchange Rate from Primary Curreny to USD';
$lang['L_DONATE_USD_TO_PRI_EXPLAIN'] = '1 primary currency equals how many USD. If not supported, leave as 0.';
$lang['L_DONATE_EUR_TO_PRI'] = 'Exchange Rate from Primary Curreny to EUR';
$lang['L_DONATE_EUR_TO_PRI_EXPLAIN'] = '1 primary currency equals how many EUR. If not supported, leave as 0.';
$lang['L_DONATE_GBP_TO_PRI'] = 'Exchange Rate from Primary Curreny to GBP';
$lang['L_DONATE_GBP_TO_PRI_EXPLAIN'] = '1 primary currency equals how many GBP. If not supported, leave as 0.';
$lang['L_DONATE_CAD_TO_PRI'] = 'Exchange Rate from Primary Curreny to CAD';
$lang['L_DONATE_CAD_TO_PRI_EXPLAIN'] = '1 primary currency equals how many CAD. If not supported, leave as 0.';
$lang['L_DONATE_JPY_TO_PRI'] = 'Exchange Rate from Primary Curreny to JPY';
$lang['L_DONATE_JPY_TO_PRI_EXPLAIN'] = '1 primary currency equals how many JPY. If not supported, leave as 0.';

?>
<?php
/** 
*
* @package lang_english
* @version $Id: lang_admin_ban.php,v 1.35.2.9 2003/06/10 00:31:19 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
//
// Ban Management
//
$lang['Ban_Manage'] = 'Manage bans';
$lang['Bans_admin_explain'] = 'Here you can view, add and remove bans. Click the headings at the top to change the sort-criterea/direction. When clicking back and next and changing the page your checkbox selections will be retained. When in IP or E-mail mode, clicking on export will provide you with a list of IP/E-mail addresses seperated by newlines that can be imported back into the ban system if neccessary using the IP/E-mail entry box.';

$lang['Ban_delete_success'] = 'Bans Deleted Successfully.';
$lang['Ban_emails_success'] = 'E-mail Adresses Banned Successfully.';
$lang['Ban_ips_success'] = 'IPs/Hostnames Banned Successfully.';
$lang['Ban_users_success'] = 'Users Banned Successfully.';

$lang['Ban_export'] = 'If your download does not start automatically, click %sHere%s.'; // %s's are used to create the hyperlink

$lang['Ban_user_line_explain'] = 'You can ban multiple users in one go using the appropriate combination of mouse and keyboard for your computer and browser.';
$lang['Ban_ip_line_explain'] = 'To specify several different IP addresses or hostnames, place them on seperate lines. To specify a range of IP addresses, separate the start and end with a hyphen (-); to specify a wildcard, use an asterisk (*). Please note that adding many host names at the same time may take awhile to process as each must be looked up to find the IP address.';
$lang['Ban_email_line_explain'] = 'To specify more than one e-mail address, place them on seperate lines. To specify a wildcard username, use * like *@hotmail.com';

$lang['Export'] = 'Export';
$lang['Export_All'] = 'Export All';
$lang['Unban'] = 'Unban';
$lang['Unban_All'] = 'Unban All';
$lang['Add_ban'] = 'Apply Bans';
$lang['IP'] = 'IP';
$lang['Last_visit'] = 'Last Visit';
$lang['Ban_mode'] = 'Ban mode';
$lang['Prev'] = 'Prev';

$lang['None_banned'] = 'No %s banned.'; // %s is turned into one of these three:
$lang['Emails'] = 'E-mail Addresses';
$lang['Ips'] = 'IPs/Hostnames';

$lang['Click_return_ban'] = 'Click %sHere%s to return to Ban Management';


//
// Ban Referring Sites
//
$lang['Ban_sites_title'] = 'Ban referring sites';
$lang['Ban_sites_explain'] = 'Here you will find a list for all the sites which are banned referers\'. Some of the visitor information may be blank, depending on a number of variables, so do not worry too much.</p><p>There are currently <b>%s</b> banned sites.<br />There have been <b>%s</b> visitors from banned sites.</p><p><b>Site URL:</b> This can be a single word, so if you wanted anyone coming from any site with the word porn in it, simply enter "porn" in the Site URL. Anyone coming from www.***porn*.com or www.domain**porn.com will be blocked. Due to this it maybe wise to be as specific as you can. Enter as much of the domain name as possible, e.g. blockthisdomain.com<br /><b>Reason:</b> This is simply here to remind you why you blocked the site.';	
$lang['Ban_sites_banlist'] = 'Banned Sites';	
$lang['Ban_sites_add'] = 'Ban new site';	
$lang['Ban_sites_visitors'] = 'Banned Visitors';	
$lang['Ban_sites_delete_all'] = 'Delete all banned visitors';	
$lang['Ban_sites_ipowner'] = 'IP Owner';	
$lang['Ban_sites_visitor_delete'] = 'Banned Visitors Deleted Successfully.';	
$lang['Ban_sites_site_delete'] = 'Banned Sites Deleted Successfully.';	
$lang['Ban_sites_click_return'] = 'Click %sHere%s to return to Ban Referring Sites';	
$lang['Ban_sites_error_chars'] = 'You need to enter at least 4 charaters for a site URL.';	
$lang['Ban_sites_site_added'] = 'Site Added Successfully';	
	

//
// Ban Control
//
$lang['Ban_control'] = 'Ban control';
$lang['Ban_explain'] = 'Here you can control the banning of users. You can achieve this by banning either or both of a specific user or an individual or range of IP addresses or hostnames. These methods prevent a user from even reaching the index page of your board. To prevent a user from registering under a different username you can also specify a banned e-mail address. Please note that banning an e-mail address alone will not prevent that user from being able to log on or post to your board. You should use one of the first two methods to achieve this.';
$lang['Ban_explain_warn'] = 'Please note that entering a range of IP addresses results in all the addresses between the start and end being added to the banlist. Attempts will be made to minimise the number of addresses added to the database by introducing wildcards automatically where appropriate. If you really must enter a range, try to keep it small or better yet state specific addresses.';

$lang['Select_username'] = 'Select a Username';
$lang['Select_ip'] = 'Select an IP address';
$lang['Select_email'] = 'Select an E-mail address';

$lang['Ban_username'] = 'Ban one or more specific users';
$lang['Ban_username_explain'] = 'You can ban multiple users in one go using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['Ban_IP'] = 'Ban one or more IP addresses or hostnames';
$lang['IP_hostname'] = 'IP addresses or hostnames';
$lang['Ban_IP_explain'] = 'To specify several different IP addresses or hostnames separate them with commas. To specify a range of IP addresses, separate the start and end with a hyphen (-); to specify a wildcard, use an asterisk (*).';

$lang['Ban_email'] = 'Ban one or more e-mail addresses';
$lang['Ban_email_explain'] = 'To specify more than one e-mail address, separate them with commas. To specify a wildcard username, use * like *@hotmail.com';

$lang['Unban_username'] = 'Un-ban one or more specific users';
$lang['Unban_username_explain'] = 'You can unban multiple users in one go using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['Unban_IP'] = 'Un-ban one or more IP addresses';
$lang['Unban_IP_explain'] = 'You can unban multiple IP addresses in one go using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['Unban_email'] = 'Un-ban one or more e-mail addresses';
$lang['Unban_email_explain'] = 'You can unban multiple e-mail addresses in one go using the appropriate combination of mouse and keyboard for your computer and browser';

$lang['No_banned_users'] = 'No banned usernames';
$lang['No_banned_ip'] = 'No banned IP addresses';
$lang['No_banned_email'] = 'No banned e-mail addresses';

$lang['Ban_update_sucessful'] = 'Banlist Updated Successfully.';
$lang['Click_return_banadmin'] = 'Click %sHere%s to return to Ban Control';


//
// Advanced Settings
//
$lang['BM_Title'] = 'Advanced settings';
$lang['BM_Explain'] = 'From this page you can add, edit, view, and remove the bans in place on this board.';
$lang['BM_Banned'] = 'Banned';
$lang['BM_Expires'] = 'Expires';
$lang['BM_By'] = 'By';
$lang['BM_Delete_selected_bans'] = 'Delete selected bans';
$lang['BM_Private_reason'] = 'Private reason';
$lang['BM_Private_reason_explain'] = 'This reason for banning the entered usernames, e-mails, and/or IP addresses is kept for note purposes only in the administration.';
$lang['BM_Public_reason'] = 'Public reason';
$lang['BM_Public_reason_explain'] = 'This reason for banning the entered usernames, e-mails, and/or IP addresses is displayed to the banned user(s) when they attempt to access the forums.';
$lang['BM_Generic_reason'] = 'Generic reason';
$lang['BM_Mirror_private_reason'] = 'Mirror private reason';
$lang['BM_Other'] = 'Other';
$lang['BM_Expire_time'] = 'Expire time';
$lang['BM_Expire_time_explain'] = 'By specifying a date, either in relation to the current date or an absolute date, the ban will become inactive after that point in time.';
$lang['BM_After_specified_length_of_time'] = 'After specified length of time';
$lang['BM_Minutes'] = 'Minute(s)';
$lang['BM_Hours'] = 'Hour(s)';
$lang['BM_Days'] = 'Day(s)';
$lang['BM_Weeks'] = 'Week(s)';
$lang['BM_Months'] = 'Month(s)';
$lang['BM_Years'] = 'Year(s)';
$lang['BM_After_specified_date'] = 'After specified date';
$lang['BM_AM'] = 'AM';
$lang['BM_PM'] = 'PM';
$lang['BM_24_hour'] = '24-Hour';
$lang['BM_Ban_reasons'] = 'Ban Reasons';
$lang['Click_return_advbanadmin'] = 'Click %sHere%s to return to Advanced Ban Settings';


//
// Bancard System
//
$lang['Ban_card_config'] = 'Bancard system';  
$lang['Enable_bancards'] = 'Enable bancard system';  
$lang['Bluecard_limit'] = 'Interval of bluecard';  
$lang['Bluecard_limit_explain'] = 'Notify the moderators again for every x bluecards given to a post.';  
$lang['Bluecard_limit_2'] = 'Limit of bluecard';  
$lang['Bluecard_limit_2_explain'] = 'First notification to moderators is sent, when a post get this amount of blue cards.';  
$lang['Report_forum']= 'Report forum'; 
$lang['Report_forum_explain'] = 'Select a forum where users reports are to be posted or leave disabled. If enabled, users MUST at least have post/reply access to the forum.';  
$lang['Max_user_bancard'] = 'Maximum number of warnings';  
$lang['Max_user_bancard_explain'] = 'If a user gets more yellow cards than this limit, the user will be banned.';  
$lang['Max_user_votebancard'] = 'Maximum number of ban votes'; 
$lang['Max_user_votebancard_explain'] = 'If a user gets more black cards than this limit, the user will be banned.'; 

?>
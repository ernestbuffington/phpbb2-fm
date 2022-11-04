<?php
/** 
*
* @package lang_english
* @version $Id: lang_prune_users.php,v 1.35.2.9 2003/06/10 00:31:19 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
$lang['X_Days'] = '%d Days';
$lang['X_Weeks'] = '%d Weeks';
$lang['X_Months'] = '%d Months';
$lang['X_Years'] = '%d Years';

$lang['Prune_no_users'] = 'No users deleted';
$lang['Prune_users_number'] = 'The following <b>%d</b> users have been Deleted Successfully:';

$lang['Prune_user_list'] = 'Users Who Will Be Deleted';
$lang['Prune_on_click'] = 'You are about to delete %d users, continue ?';
$lang['Prune_Action'] = 'Press Link To Execute';
$lang['Prune_users_explain'] = 'From this page you can prune users, who are no longer active, you can choose between three links, one will delete old users who have never posted, one will delete old users who have never logged in, and the other will delete users who have never activated their account. There is no un-do function, all the users in the list will be deleted, be careful when executing the link.'; 
$lang['Prune_commands'] = array();
$lang['Click_return_prune_users'] = 'Click %sHere%s to return to Prune Users';

// here you can make more entries if needed
$lang['Prune_commands'][0] = 'Prune Non-Posting Users';
$lang['Prune_explain'][0] = 'Who have never posted, <b>excluding</b> new users from the past %d days';
$lang['Prune_commands'][1] = 'Prune Inactive Users';
$lang['Prune_explain'][1] = 'Who have never logged in, <b>excluding</b> new users from the past %d days';
$lang['Prune_commands'][2] = 'Prune Non-Activated Users';
$lang['Prune_explain'][2] = 'Who have never been activated, <b>excluding</b> new users from the past %d days';
$lang['Prune_commands'][3] = 'Prune Non-Visiting Users'; 
$lang['Prune_explain'][3] = 'Who have not visited for one year, <b>excluding</b> new users from the past %d days'; 
$lang['Prune_commands'][4] = 'Prune Low Average Posters'; 
$lang['Prune_explain'][4] = 'Who have less than an average of 1 post for every 10 days while registered, <b>excluding</b> new users from the past %d days'; 

?>
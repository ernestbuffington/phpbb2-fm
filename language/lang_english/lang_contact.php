<?php
/** 
*
* @package lang_english
* @version $Id: lang_contact.php.php,v 0.8.0 4/29/2006 4:12 AM Exp $
* @copyright (c) 2005 MJ < mj@phpbb-fm.com >
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

	// Do not change the next six lines.
// Avoid including the file more than once.
if ( defined('IN_CONTACT_LANG') )
{
	return;
}
define('IN_CONTACT_LANG', true);

// You may want to edit the following lines to match your website.
$lang['Buddy'] = 'Friend';
$lang['Ignore'] = 'Ignore';
$lang['Disallow'] = 'Disallow';
$lang['User_ignoring_you'] = 'That user has placed you on their ignore list.';
$lang['User_not_want_contact'] = 'That user has placed you on their disallow list.';
$lang['Buddies_online'] = 'These friends have come online';
$lang['Buddy_online'] = 'This friend has come online';
$lang['Buddies_offline'] = 'These friends are now offline';
$lang['Buddy_offline'] = 'This friend is now offline';
$lang['Listbox_Buddies'] = 'Your friends';
$lang['Online'] = 'Online';
$lang['Offline'] = 'Offline';
$lang['Buddies'] = 'Buddies';
$lang['Ignored_some_users'] = 'Some users on this page were ignored. %sView this page with those users?%s';
$lang['Ignore_some_users'] = '%sView this page without ignored users?%s';

// These will be used in the user profiles for links to do the indicated thing
// Also used as ALT text for images in several places.  %s will be replaced with a
// user's name
$lang['Add_to_buddy'] = 'Add %s to your friends list';
$lang['Remove_from_buddy'] = 'Remove %s from your friends list';
$lang['Add_to_ignore'] = 'Add %s to your ignore list';
$lang['Remove_from_ignore'] = 'Remove %s from your ignore list';
$lang['Add_to_disallow'] = 'Add %s to your disallow contact list';
$lang['Remove_from_disallow'] = 'Remove %s from your disallow contact list';


// Error Messages
$lang['No_alerts_updated'] = 'No users were indicated for alert updates';
$lang['No_autoclose'] = 'If you are seeing this message, then the automatic window closing feature does not work with your browser. Possible causes including having your browser\'s JavaScript disabled. Please close this window.';

// Control Panel
$lang['Users_you_ignore'] = 'Users You are Ignoring';
$lang['Users_you_disallow'] = 'Users You Do Not Allow to Contact You';
$lang['Users_buddy_you'] = 'Users listing you as a friend';
$lang['Users_you_buddy'] = 'Your Friends';
$lang['None_you_ignore'] = 'You are not ignoring any users.';
$lang['None_you_disallow'] = 'You are allowing all users to contact you.';
$lang['None_buddy_you'] = 'No users have listed you as a friend.';
$lang['None_you_buddy'] = 'You have no friends.';
$lang['Add_a_user'] = 'Add a User to this List?';
$lang['Add_user'] = 'Add user';
$lang['Move_selected_users'] = 'Move the selected users to:';
$lang['Buddy_link'] = 'Friends';
$lang['Buddied_link'] = 'Friend Of';
$lang['Ignore_link'] = 'Ignoring';
$lang['Disallow_link'] = 'Disallowing';
$lang['Be_alerted'] = 'Alert me when this user comes online';
$lang['Edit_alerts'] = 'Edit online and offline alert settings';

// Success messages
$lang['Alerts_updated'] = 'Alert preferences updated for all changed friends';
$lang['Alerts_oops'] = ' except the following, which could not be found:<br />';
$lang['Moved_to_buddies'] = 'The indicated users have been moved to your friends list.';
$lang['Moved_to_ignore'] = 'The indicated users have been moved to your ignore list.';
$lang['Moved_to_disallow'] = 'The indicated users have been moved to your disallow list.';
$lang['Removed_selected_users'] = 'The indicated users have been removed.';
$lang['Buddy_updated'] = 'Your friends list has been updated successfully.';
$lang['Ignore_updated'] = 'Your ignore list has been updated successfully.';
$lang['Disallow_updated'] = 'Your disallow list has been updated successfully.';


// For Prillian
$lang['Close_window_link'] = '<br /><br /><a href="javascript:window.close();">' . $lang['Close_window'] . '</a>';

/* Entries Added in Prillian 0.7.0 & Contact List 0.3.0 */
$lang['No_ignore_admin'] = 'You have tried to ignore or disallow the following administrators or moderators: %s.<br /><br />Please resubmit the changes without trying to ignore or disallow these users.';
$lang['No_contact_add_self'] = 'You have tried to add yourself to one of your contact lists.<br /><br />This is not allowed; please resubmit the changes without trying to add yourself to your own contact lists.';
$lang['Add_Selected_as_Buddies'] = 'Add Selected as Friends';
$lang['Add_contact_users_link'] = 'Add New Friends';
$lang['You_have_buddies'] = 'You have %d friends.';
$lang['You_have_buddy'] = 'You have one friend.';
$lang['You_are_ignoring'] = 'You are ignoring %d users.';
$lang['You_are_ignoring_one'] = 'You are ignoring one user.';
$lang['You_have_disallowed'] = 'You are not allowing %d users to contact you.';
$lang['You_have_disallowed_one'] = 'You are not allowing one user to contact you.';
$lang['You_as_buddies'] = '%d users have added you as a friend.';
$lang['You_as_buddy'] = 'One user has added you as a friend.';
$lang['Add_many_contacts_explain'] = 'You may add several users to your friend, ignore, or disallow lists here.  Enter the name of each user you wish to add in the text box below.  Each user\'s name must be on a separate line.';
$lang['Add_to_Buddy_List'] = 'Add to friends list';
$lang['Add_to_Ignore_List'] = 'Add to ignore list';
$lang['Add_to_Disallow_List'] = 'Add to disallow list';


/* Entries Changed in Prillian 0.7.0 & Contact List 0.3.0 */
/* Any of these that have contact in the $lang['name'] part used to have bid or
 buddy in place of contact. In some, that is the only change */
$lang['Contact_List_FAQ'] = 'Contact Lists'; // Title of the FAQ

$lang['Contact_Management'] = 'Contact Management';

// Error Messages
$lang['No_contact_mode'] = 'No Contact mode defined';
$lang['No_contact_type'] = 'No Contact type defined';
$lang['No_contact_action'] = 'No Contact action defined';
$lang['No_contact_id'] = 'No Contact user id';
$lang['Invalid_contact_action'] = 'Contact Action definition is invalid';


// Control Panel
$lang['Contact_click_here'] = '%sManage Contact List%s';


// Success messages
$lang['Confirm_contact_changes'] = 'Are you sure you wish to make those changes?';
$lang['No_Contact_changes'] = 'No changes were specified';


//Private Message alerts
$lang['System_title'] = 'Contact List System Message';
$lang['Contact_Alert_PM'] = '[url=%s]%s[/url] has added you to their friends list. To manage your Contact List, please [url=%s]click here[/url]. This is an automated message sent by the forum; you do not need to reply to this message.';

?>
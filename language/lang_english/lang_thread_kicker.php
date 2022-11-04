<?php
/** 
*
* @package lang_english
* @version $Id: lang_thread_kicker.php,v 1.0.0 2004/10/17 17:49:33 majorflam Exp $
* @copyright (c) 2004 Majorflamp
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/


// General Variables
$lang['tkick_kickbutton'] = 'Kick This User From This Topic';
$lang['tkick_unkickbutton'] = 'Allow This User Back Into This Topic';
$lang['tk_kick_success'] = 'User Kicked Successfully.';
$lang['tk_unkick_success'] = 'User can now post in this topic again.';
$lang['tk_default_link'] = 'Your browser will redirect you back to the topic in seconds. If you do not want to wait, click ';
$lang['tk_here'] = 'Here';
$lang['tk_userkicked_topic'] = 'Your ability to post in this topic has been withdrawn by ';
$lang['tk_userkicked_topic_noview'] = 'Your ability to read or post in this topic has been withdrawn by ';
$lang['tk_userkicked_contact'] = '. You should contact this user for details.';
$lang['tk_default_link_overrule'] = 'Your browser will redirect you back to the topic in seconds. If you do not want to wait, click ';
$lang['tk_kickview'] = 'View list of kicked users for this topic';

// Error codes
$lang['tk_disabled'] = 'Sorry, but the topic kicker feature of this board has been disabled. Please try again later.';
$lang['tk_nodata'] = 'Insufficient data to process request';
$lang['tk_nohotlink'] = 'You must access this facility from the relevant link within the topic';
$lang['tk_not_permitted'] = 'You are not authorised to carry out this action';
$lang['tk_not_mod'] = 'Only Moderators may carry out this action in this way';
$lang['tk_not_mod_thisforum'] = 'Only Moderators of that Forum may carry out this action in this way';
$lang['tk_kicked_already'] = 'This user is already kicked from that topic.';
$lang['tk_unkicked_already'] = 'This user was not kicked from this topic to begin with.';
$lang['tk_no_overrule'] = 'This user was kicked from the topic by an Administrator or Moderator, and you do not have the authority to over-rule their decision. Please contact an Administrator if you want this user kicked from this topic.';
$lang['tk_no_overrule_admin'] = 'This user was kicked from the topic by an Administrator, and you do not have the authority to over-rule their decision. Please contact an Administrator if you want this user kicked from this topic.';
$lang['tk_banned'] = 'Your ability to kick other users from topics has been withdrawn. Please contact an Administrator for details.';

// for viewing kicked users
$lang['tk_kicker_table'] = 'Current List Of Kicked Users For Topic :: ';
$lang['tk_kicked'] = 'Kicked User';
$lang['tk_date'] = 'Date User Was Kicked';
$lang['tk_kicked_by'] = 'User Kicked By';
$lang['tk_kick_marked'] = 'Un-Kick Marked';
$lang['unkick_all'] = 'Un-Kick All';

// Admin Language Variables
$lang['tk_enable_kicker'] = 'Enable Topic Kicker';
$lang['tk_kicker_explain'] = 'From this panel you can view users who have been kicked from topics.';
$lang['tk_kicker_set_head'] = 'Current Kick Permissions';
$lang['tk_kicker_set_explain'] = 'By default, only Administrators and Moderators of a forum may kick users in that forum. If you like, you can allow the people who start topics to be able to kick anyone (except Administrators and Moderators) from the topic.';
$lang['tk_kicker_set_change'] = 'Change Kick Permissions';
$lang['tk_view_set_change_button'] = 'Change Viewing Permissions';
$lang['tk_users_cannot_kick'] = 'At the moment, topic starters <b>cannot</b> kick other users, unless they are Moderators or Administrators of that particular Forum.';
$lang['tk_users_can_kick'] = 'At the moment, topic starters <b>can</b> kick other users from that topic';
$lang['tk_view_set_head'] = 'Current Viewing Permissions For Kicked Users';
$lang['tk_view_set_explain'] = 'By default, users who have been kicked from a topic <b>can</b> read the topic, but they <b>cannot</b> post, or edit their posts in that topic . If you like, you can make it so that kicked users <b>cannot</b> view the topic either.';

?>
<?php
/** 
*
* @package admin
* @version $Id: lang_portal.php,v 1.23.2.4 2002/05/21 16:52:08 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

//
// Main
//
$lang['Latest_Posts'] = 'Latest Posts';
$lang['Last'] = 'Last';
$lang['Poll'] = 'Poll'; 
$lang['Navigate'] = 'Main Menu'; 
$lang['Last_Post_By'] = 'Last Post By: '; 
$lang['On'] = 'on';
$lang['Comment'] = 'Comment';
$lang['Onlinetoday'] = 'Online Today';
$lang['Archive'] = 'Archives';
$lang['Search_engine'] = 'Search Engine'; 
$lang['Site_search'] = 'Search site forums'; 
$lang['World_news'] = 'World News'; 
$lang['Recent_referrals'] = 'Recent Referrals'; 

$lang['No_polls_exist'] = 'No poll exists.<br /><br />You must create a poll in the assigned forum';

//
// Admin
//
$lang['Portal_explain'] = 'From this panel you can add, delete, edit and re-order portal pages.<br /><br /> The default portal page <b>cannot</b> be deleted and is marked with a <span style="color: #FF0000">red asterix *</span>, edit and rename it to suit your needs.';
$lang['No_portal_id'] = 'No portal_id specified.';
$lang['No_page_title'] = 'You must specify a Navigation Bar Name and a Page Name for the page.';
$lang['No_delete_page'] = 'The default portal page cannot be deleted, edit and rename it to suit your needs.';
	
$lang['Edit_Portal_explain'] = 'From this panel you can manage the options of the portal page.';
$lang['Navbar_Name'] = 'Page name';
$lang['Navbar_Name_explain'] = 'The title of the portal page to be displayed on the Navigation Bar.';
$lang['Portal_Destination'] = 'Portal destination';
$lang['Portal_Destination_explain'] = 'You must either select a forum from which the portal page will display the first post of each topic or enter a URL for which the navigation bar link will link to instead of a portal page.<br />Linked URL\'s can be opened in an iFrame or the same window.';
$lang['Dest_URL'] = 'URL';
$lang['iFrame'] = 'Use iFrame:';
$lang['Posts_Limit'] = 'Posts limit';
$lang['Posts_Limit_explain'] = 'The number of news items <I>(topics)</i> to display; Set to zero to display all items.';
$lang['Char_limit'] = 'Post character limit';
$lang['Char_limit_explain'] = 'Number of characters shown before the post is cut and a \'read more\' link to the topic is shown. Set to zero to display the whole post.';
$lang['Posts_Order'] = 'Posts order';
$lang['Posts_Order_explain'] = 'Ascending or descending topic order.';
$lang['Ascending'] = 'Oldest posts first';
$lang['Desending'] = 'Newest posts first';
$lang['Display_Date'] = 'Display date';
$lang['Display_Date_explain'] = 'Display the date for the news items.';

// Portal Module Block Options
$lang['Block_options'] = 'Module Blocks Options';
$lang['Column_width'] = 'Side columns width';
$lang['Column_width_explain'] = 'Allows you to set the width of the side columns in pixels. A range between 200 and 250 is recommended.';
$lang['Show_Newsfader'] = 'News bar block';
$lang['Show_Newsfader_explain'] = 'News bar options can be set in %sNews bar settings%s.';
$lang['Show_Navbar'] = 'Navigation bar block';
$lang['Show_Navbar_explain'] = 'Use on all pages is recommended.';
$lang['Show_Moreover'] = 'World news block';
$lang['Show_Moreover_explain'] = 'RSS/XML newsfeeds can be configured in %sNewsfeed settings%s.';
$lang['Show_Calendar'] = 'Calendar block';
$lang['Show_Online'] = 'Who is online block';
$lang['Show_Onlinetoday'] = 'Online today block';
$lang['Show_Latest'] = 'Latest posts block';
$lang['Show_Latest_exclude_forums'] = 'Exclude forums';
$lang['Show_Latest_exclude_forums_explain'] = 'Forums IDs that will <b>not</b> be included in this module. Seperate multiple IDs with semi-colons e.g. 1;2;3';
$lang['Show_Latest_amt'] = 'Latest posts block amount';
$lang['Show_Latest_amt_explain'] = 'Number of posts to show in this module.';
$lang['Show_Latest_scrolling'] = 'Scroll latest posts block';
$lang['Show_Poll'] = 'Poll block';
$lang['Portal_Polls'] = 'Poll forums' ; 
$lang['Portal_Polls_explain'] = 'Forums IDs from where polls in the poll block will read from. Seperate multiple IDs with semi-colons e.g. 1;2;3'; 
$lang['Show_Search'] = 'Search engine block';
$lang['Show_Links'] = 'Links block';
$lang['Links_height'] = 'Links block height';
$lang['Show_Ourlink'] = 'Link to us block';
$lang['Show_Randomuser'] = 'Random user block';
$lang['Show_Karma_explain'] = 'Karma <b>must</b> be enabled in %sKarma settings%s for this block to show.';
$lang['Show_Horoscopes'] = 'Daily horoscopes block';
$lang['Show_Referrers'] = 'Recent referrals block';
$lang['Show_Shoutbox_explain'] = 'Shoutbox <b>must</b> be enabled in %sShoutbox settings%s.';

$lang['LHS_Explain'] = 'The modules below are shown on the left hand side of the portal page.';
$lang['CTR_Explain'] = 'The modules below are shown in the center of the portal page.';
$lang['RHS_Explain'] = 'The modules below are shown on the right hand side of the portal page. These modules are not shown on an iFrame page setting.';

$lang['Portal_updated'] = 'Portal Page Updated Successfully.';
$lang['Portal_added'] = 'Portal Page Added Successfully.';
$lang['Portal_deleted'] = 'Portal Page Deleted Successfully.';
$lang['Click_return_portal'] = 'Click %sHere%s to return to Portal Management';

// Newsfeed configuration
$lang['Newsfeed_config_explain'] = 'From this panel you can configure the settings of the newsfeed that shows on the portal.';
$lang['Newsfeed_rss'] = 'XML/RSS feed URL';
$lang['Newsfeed_rss_explain'] = 'A complete list of available newsfeed categories can be obtained from %sMoreover.com%s.';
$lang['Newsfeed_cache'] = 'Cache directory';
$lang['Newsfeed_cache_explain'] = 'Path under your phpBB root dir, e.g. cache';
$lang['Newsfeed_cachetime'] = 'Cache duration';
$lang['Newsfeed_cachetime_explain'] = 'Time in minutes after which the cache will be updated with a live feed, higher equals less processing.';
$lang['Newsfeed_amt'] = 'Number of articles to show';
$lang['Newsfeed_field_config'] = 'XML/RSS fields';
$lang['Newsfeed_field_config_explain'] = 'The settings below allow configuration of the the xml/rss fields for different feeds, do not alter these settings unless you know what you are doing.';
$lang['Newsfeed_field_article'] = 'Articles';
$lang['Newsfeed_field_url'] = 'URL';
$lang['Newsfeed_field_text'] = 'Text';
$lang['Newsfeed_field_source'] = 'Source';
$lang['Newsfeed_field_time'] = 'Time';

?>
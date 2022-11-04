<?php
/** 
*
* @package admin
* @version $Id: admin_portal.php,v 1.23.2.4 2002/05/21 16:52:08 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['Portal'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if ( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_portal.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_portal.' . $phpEx);


if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = (isset($HTTP_GET_VARS['mode'])) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else 
{
	//
	// These could be entered via a form button
	//
	if ( isset($HTTP_POST_VARS['add']) )
	{
		$mode = 'add';
	}
	else if ( isset($HTTP_POST_VARS['save']) )
	{
		$mode = 'save';
	}
	else
	{
		$mode = '';
	}
}

if ( $mode != '' )
{
	if ( $mode == 'edit' || $mode == 'add' )
	{
		//
		// They want to add a new page, show the form.
		//
		if ( isset($HTTP_POST_VARS[POST_POST_URL]) || isset($HTTP_GET_VARS[POST_POST_URL]) )
		{
			$portal_id = ( isset($HTTP_POST_VARS[POST_POST_URL]) ) ? intval($HTTP_POST_VARS[POST_POST_URL]) : intval($HTTP_GET_VARS[POST_POST_URL]);
		}
		else
		{
			$portal_id = 0;
		}
		
		$s_hidden_fields = '';
		
		if ( $mode == 'edit' )
		{
			if ( empty($portal_id) )
			{
				message_die(GENERAL_MESSAGE, $lang['No_portal_id']);
			}

			$sql = "SELECT * 
				FROM " . PORTAL_TABLE . "
				WHERE portal_id = " . $portal_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain portal page data.', '', __LINE__, __FILE__, $sql);
			}
			
			$portal_info = $db->sql_fetchrow($result);
			$s_hidden_fields .= '<input type="hidden" name="' . POST_POST_URL . '" value="' . $portal_id . '" />';
		}
		else
		{
			// Default settings for a new portal page 
			$portal_info['portal_url'] = $portal_info['portal_navbar_name'] = '';
			$portal_info['portal_use_url'] = $portal_info['portal_char_limit'] = $portal_info['portal_ascending'] = $portal_info['portal_nodate'] = $portal_info['portal_newsfader'] = $portal_info['portal_moreover'] = $portal_info['portal_calendar'] = $portal_info['portal_online'] = $portal_info['portal_onlinetoday'] = $portal_info['portal_latest'] = $portal_info['portal_latest_scrolling'] = $portal_info['portal_poll'] = $portal_info['portal_polls'] = $portal_info['portal_photo'] = $portal_info['portal_search'] = $portal_info['portal_quote'] = $portal_info['portal_links'] = $portal_info['portal_ourlink'] = $portal_info['portal_downloads'] = $portal_info['portal_randomuser'] = $portal_info['portal_mostpoints'] = $portal_info['portal_topposters'] = $portal_info['portal_newusers'] = $portal_info['portal_games'] = $portal_info['portal_clock'] = $portal_info['portal_karma'] = $portal_info['portal_horoscopes'] = $portal_info['portal_donors'] = $portal_info['portal_wallpaper'] = $portal_info['portal_referrers'] = 0;
			$portal_info['portal_use_iframe'] = $portal_info['portal_forum'] = $portal_info['portal_navbar'] = 1;
			$portal_info['portal_list_limit'] = 3;
			$portal_info['portal_latest_amt'] = 5;
			$portal_info['portal_links_height'] = 100;
			$portal_info['portal_column_width'] = 200;
			$portal_info['portal_iframe_height'] = 600;
		}

		$s_hidden_fields .= '<input type="hidden" name="mode" value="save" />';

		$template->set_filenames(array(
			'body' => 'admin/portal_edit_body.tpl')
		);

		$use_url = $portal_info['portal_use_url'];
		$use_iframe = $portal_info['portal_use_iframe'];
		$iframe_height = $portal_info['portal_iframe_height'];
		$forum_id = $portal_info['portal_forum'];
		$portal_url = $portal_info['portal_url'];
		$list_limit = $portal_info['portal_list_limit'];
		$char_limit = $portal_info['portal_char_limit'];
		$order_asc = $portal_info['portal_ascending'];
		$display_date = $portal_info['portal_nodate'];
		$navbar_name = $portal_info['portal_navbar_name'];
		$show_newsfader = $portal_info['portal_newsfader'];
		$portal_column_width = $portal_info['portal_column_width']; 
		$show_navbar = $portal_info['portal_navbar'];
		$show_moreover = $portal_info['portal_moreover'];
		$show_calendar = $portal_info['portal_calendar'];
		$show_online = $portal_info['portal_online'];
		$show_onlinetoday = $portal_info['portal_onlinetoday'];
		$show_latest = $portal_info['portal_latest'];
		$show_latest_exclude_forums = $portal_info['portal_latest_exclude_forums'];
		$show_latest_amt = $portal_info['portal_latest_amt'];
		$show_latest_scrolling = $portal_info['portal_latest_scrolling'];
		$show_poll = $portal_info['portal_poll'];
		$portal_polls = $portal_info['portal_polls']; 
		$show_photo = $portal_info['portal_photo'];
		$show_search = $portal_info['portal_search'];
		$show_quote = $portal_info['portal_quote'];
		$show_links = $portal_info['portal_links'];
		$portal_links_height = $portal_info['portal_links_height']; 
		$show_ourlink = $portal_info['portal_ourlink'];
		$show_downloads = $portal_info['portal_downloads'];
		$show_randomuser = $portal_info['portal_randomuser'];
		$show_mostpoints = $portal_info['portal_mostpoints'];
		$show_topposters = $portal_info['portal_topposters'];
		$show_newusers = $portal_info['portal_newusers'] ;
		$show_games = $portal_info['portal_games'];
		$show_clock = $portal_info['portal_clock'];
		$show_karma = $portal_info['portal_karma'];
		$show_horoscopes = $portal_info['portal_horoscopes'];
		$show_wallpaper = $portal_info['portal_wallpaper'];
		$show_donors = $portal_info['portal_donors'];
		$show_referrers = $portal_info['portal_referrers'];
		$show_shoutbox = $portal_info['portal_shoutbox'];

		$template->assign_vars(array(
			'FORUM_SELECT' => forum_select($forum_id, 'forum_select'),
			'LIST_LIMIT' => $list_limit,
			'CHAR_LIMIT' => $char_limit,
			'USE_FORUM' => ( !$use_url ) ? 'checked="checked"' : '',
			'USE_URL' => ( $use_url ) ? 'checked="checked"' : '',
			'USE_IFRAME_YES' => ( $use_iframe ) ? 'checked="checked"' : '',
			'USE_IFRAME_NO' => ( !$use_iframe ) ? 'checked="checked"' : '',
			'IFRAME_HEIGHT' => $iframe_height,
			'PORTAL_URL' => $portal_url,
			'POSTS_ORDER_ASC' => ( $order_asc ) ? 'checked="checked"' : '',
			'POSTS_ORDER_DSC' => ( !$order_asc ) ? 'checked="checked"' : '',
			'DISPLAY_DATE_YES' => ( !$display_date ) ? 'checked="checked"' : '',
			'DISPLAY_DATE_NO' => ( $display_date ) ? 'checked="checked"' : '',
			'COLUMN_WIDTH' => $portal_column_width, 
			'NAVBAR_NAME' => $navbar_name,
			'SHOW_NEWSFADER_YES' => ( $show_newsfader ) ? 'checked="checked"' : '',
			'SHOW_NEWSFADER_NO' => ( !$show_newsfader ) ? 'checked="checked"' : '',
			'SHOW_NAVBAR_YES' => ( $show_navbar ) ? 'checked="checked"' : '',
			'SHOW_NAVBAR_NO' => ( !$show_navbar ) ? 'checked="checked"' : '',
			'SHOW_MOREOVER_YES' => ( $show_moreover ) ? 'checked="checked"' : '',
			'SHOW_MOREOVER_NO' => ( !$show_moreover ) ? 'checked="checked"' : '',
			'SHOW_CALENDAR_YES' => ( $show_calendar ) ? 'checked="checked"' : '',
			'SHOW_CALENDAR_NO' => ( !$show_calendar ) ? 'checked="checked"' : '',
			'SHOW_ONLINE_YES' => ( $show_online ) ? 'checked="checked"' : '',
			'SHOW_ONLINE_NO' => ( !$show_online ) ? 'checked="checked"' : '',
			'SHOW_ONLINETODAY_YES' => ( $show_onlinetoday ) ? 'checked="checked"' : '',
			'SHOW_ONLINETODAY_NO' => ( !$show_onlinetoday ) ? 'checked="checked"' : '',
			'SHOW_LATEST_YES' => ( $show_latest ) ? 'checked="checked"' : '',
			'SHOW_LATEST_NO' => ( !$show_latest ) ? 'checked="checked"' : '',
			'SHOW_LATEST_EXCLUDE_FORUMS' => $show_latest_exclude_forums, 
			'SHOW_LATEST_AMT' => $show_latest_amt, 
			'SHOW_LATEST_SCROLLING_YES' => ( $show_latest_scrolling ) ? 'checked="checked"' : '',
			'SHOW_LATEST_SCROLLING_NO' => ( !$show_latest_scrolling ) ? 'checked="checked"' : '',
			'SHOW_POLL_YES' => ( $show_poll ) ? 'checked="checked"' : '',
			'SHOW_POLL_NO' => ( !$show_poll ) ? 'checked="checked"' : '',
			'PORTAL_POLLS' => $portal_polls, 
			'SHOW_PHOTO_YES' => ( $show_photo ) ? 'checked="checked"' : '',
			'SHOW_PHOTO_NO' => ( !$show_photo ) ? 'checked="checked"' : '',
			'SHOW_SEARCH_YES' => ( $show_search ) ? 'checked="checked"' : '',
			'SHOW_SEARCH_NO' => ( !$show_search ) ? 'checked="checked"' : '',
			'SHOW_QUOTE_YES' => ( $show_quote ) ? 'checked="checked"' : '',
			'SHOW_QUOTE_NO' => ( !$show_quote ) ? 'checked="checked"' : '',
			'SHOW_LINKS_YES' => ( $show_links ) ? 'checked="checked"' : '',
			'SHOW_LINKS_NO' => ( !$show_links ) ? 'checked="checked"' : '',
			'LINKS_HEIGHT' => $portal_links_height, 
			'SHOW_OURLINK_YES' => ( $show_ourlink ) ? 'checked="checked"' : '',
			'SHOW_OURLINK_NO' => ( !$show_ourlink ) ? 'checked="checked"' : '',
			'SHOW_DOWNLOADS_YES' => ( $show_downloads ) ? 'checked="checked"' : '',
			'SHOW_DOWNLOADS_NO' => ( !$show_downloads ) ? 'checked="checked"' : '',
			'SHOW_RANDOMUSER_YES' => ( $show_randomuser ) ? 'checked="checked"' : '',
			'SHOW_RANDOMUSER_NO' => ( !$show_randomuser ) ? 'checked="checked"' : '',
			'SHOW_MOSTPOINTS_YES' => ( $show_mostpoints ) ? 'checked="checked"' : '',
			'SHOW_MOSTPOINTS_NO' => ( !$show_mostpoints ) ? 'checked="checked"' : '',
			'SHOW_TOPPOSTERS_YES' => ( $show_topposters ) ? 'checked="checked"' : '',
			'SHOW_TOPPOSTERS_NO' => ( !$show_topposters ) ? 'checked="checked"' : '',
			'SHOW_NEWUSERS_YES' => ( $show_newusers ) ? 'checked="checked"' : '',
			'SHOW_NEWUSERS_NO' => ( !$show_newusers ) ? 'checked="checked"' : '',
			'SHOW_GAMES_YES' => ( $show_games ) ? 'checked="checked"' : '',
			'SHOW_GAMES_NO' => ( !$show_games ) ? 'checked="checked"' : '',
			'SHOW_CLOCK_YES' => ( $show_clock ) ? 'checked="checked"' : '',
			'SHOW_CLOCK_NO' => ( !$show_clock ) ? 'checked="checked"' : '',
			'SHOW_KARMA_YES' => ( $show_karma ) ? 'checked="checked"' : '',
			'SHOW_KARMA_NO' => ( !$show_karma ) ? 'checked="checked"' : '',
			'SHOW_HOROSCOPES_YES' => ( $show_horoscopes ) ? 'checked="checked"' : '',
			'SHOW_HOROSCOPES_NO' => ( !$show_horoscopes ) ? 'checked="checked"' : '',
			'SHOW_WALLPAPER_YES' => ( $show_wallpaper ) ? 'checked="checked"' : '',
			'SHOW_WALLPAPER_NO' => ( !$show_wallpaper ) ? 'checked="checked"' : '',
			'SHOW_DONORS_YES' => ( $show_donors) ? 'checked="checked"' : '',
			'SHOW_DONORS_NO' => ( !$show_donors) ? 'checked="checked"' : '',
			'SHOW_REFERRERS_YES' => ( $show_referrers) ? 'checked="checked"' : '',
			'SHOW_REFERRERS_NO' => ( !$show_referrers) ? 'checked="checked"' : '',
			'SHOW_SHOUTBOX_YES' => ( $show_shoutbox) ? 'checked="checked"' : '',
			'SHOW_SHOUTBOX_NO' => ( !$show_shoutbox) ? 'checked="checked"' : '',

			'L_PAGE_TITLE' => (($mode == 'add') ? $lang['Add'] : $lang['Edit']) . ' ' . $lang['Portal'],
			'L_PAGE_EXPLAIN' => $lang['Edit_Portal_explain'],
			'L_ITEMS_REQUIRED' => $lang['Items_required'],
			'L_PAGE_NAME' => $lang['Page_Name'],
			'L_PAGE_NAME_EXPLAIN' => $lang['Page_Name_explain'],
			'L_NAVBAR_NAME' => $lang['Navbar_Name'],
			'L_NAVBAR_NAME_EXPLAIN' => $lang['Navbar_Name_explain'],
			'L_PORTAL_DESTINATION' => $lang['Portal_Destination'],
			'L_PORTAL_DESTINATION_EXPLAIN' => $lang['Portal_Destination_explain'],
			'L_DEST_FORUM' => $lang['Forum'],
			'L_DEST_URL' => $lang['Dest_URL'],
			'L_POSTS_LIMIT' => $lang['Posts_Limit'],
			'L_POSTS_LIMIT_EXPLAIN' => $lang['Posts_Limit_explain'],
			'L_CHAR_LIMIT' => $lang['Char_limit'],
			'L_CHAR_LIMIT_EXPLAIN' => $lang['Char_limit_explain'],
			'L_POSTS_ORDER' => $lang['Posts_Order'],
			'L_POSTS_ORDER_EXPLAIN' => $lang['Posts_Order_explain'],
			'L_ASC' => $lang['Ascending'],
			'L_DSC' => $lang['Desending'],
			'L_DISPLAY_DATE' => $lang['Display_Date'],
			'L_DISPLAY_DATE_EXPLAIN' => $lang['Display_Date_explain'],
			'L_USE_IFRAME' => $lang['iFrame'],
			'L_IFRAME_HEIGHT' => $lang['iFrame_height'],
	
			'L_BLOCK_OPTIONS' => $lang['Block_options'],
			'L_COLUMN_WIDTH' => $lang['Column_width'],
			'L_COLUMN_WIDTH_EXPLAIN' => $lang['Column_width_explain'],
			'L_SHOW_NAVBAR' => $lang['Show_Navbar'],
			'L_SHOW_NAVBAR_EXPLAIN' => $lang['Show_Navbar_explain'],
			'L_SHOW_NEWSFADER' => $lang['Show_Newsfader'],
			'L_SHOW_NEWSFADER_EXPLAIN' => sprintf($lang['Show_Newsfader_explain'], '<a href="' . append_sid('admin_board.'.$phpEx.'?mode=newsbar') . '">', '</a>'),
			'L_SHOW_MOREOVER' => $lang['Show_Moreover'],
			'L_SHOW_MOREOVER_EXPLAIN' => sprintf($lang['Show_Moreover_explain'], '<a href="' . append_sid('admin_portal_newsfeed.'.$phpEx) . '">', '</a>'),
			'L_SHOW_CALENDAR' => $lang['Show_Calendar'],
			'L_SHOW_ONLINE' => $lang['Show_Online'],
			'L_SHOW_ONLINETODAY' => $lang['Show_Onlinetoday'],
			'L_SHOW_LATEST' => $lang['Show_Latest'],
			'L_SHOW_LATEST_AMT' => $lang['Show_Latest_amt'],
			'L_SHOW_LATEST_AMT_EXPLAIN' => $lang['Show_Latest_amt_explain'],
			'L_SHOW_LATEST_EXCLUDE_FORUMS' => $lang['Show_Latest_exclude_forums'],
			'L_SHOW_LATEST_EXCLUDE_FORUMS_EXPLAIN' => $lang['Show_Latest_exclude_forums_explain'],
			'L_SHOW_LATEST_SCROLLING' => $lang['Show_Latest_scrolling'],
			'L_SHOW_POLL' => $lang['Show_Poll'],
			'L_PORTAL_POLLS' => $lang['Portal_Polls'],
			'L_PORTAL_POLLS_EXPLAIN' => $lang['Portal_Polls_explain'],
			'L_SHOW_PHOTO' => $lang['Forum_module_photo'],
			'L_SHOW_SEARCH' => $lang['Show_Search'],
			'L_SHOW_OURLINK' => $lang['Show_Ourlink'],
			'L_SHOW_QUOTE' => $lang['Forum_module_quote'],
			'L_SHOW_LINKS' => $lang['Show_Links'],
			'L_LINKS_HEIGHT' => $lang['Links_height'],
			'L_SHOW_DOWNLOADS' => $lang['Forum_module_dloads'],
			'L_SHOW_RANDOMUSER' => $lang['Show_Randomuser'],
			'L_SHOW_MOSTPOINTS' => sprintf($lang['Forum_module_points'], $board_config['points_name']),
			'L_SHOW_TOPPOSTERS' => $lang['Forum_module_topposters'],
			'L_SHOW_NEWUSERS' => $lang['Forum_module_newusers'],
			'L_SHOW_GAMES' => $lang['Forum_module_game'],
			'L_SHOW_CLOCK' => $lang['Forum_module_clock'],
			'L_SHOW_KARMA' => $lang['Forum_module_karma'],
			'L_SHOW_KARMA_EXPLAIN' => sprintf($lang['Show_Karma_explain'], '<a href="' . append_sid('admin_board.'.$phpEx.'?mode=karma') . '">', '</a>'),
			'L_SHOW_HOROSCOPES' => $lang['Show_Horoscopes'],
			'L_SHOW_WALLPAPER' => $lang['Forum_module_wallpaper'],
			'L_SHOW_DONORS' => $lang['Forum_module_donors'],
			'L_SHOW_REFERRERS' => $lang['Show_Referrers'],
			'L_SHOW_SHOUTBOX' => $lang['Forum_module_shoutbox'],
			'L_SHOW_SHOUTBOX_EXPLAIN' => sprintf($lang['Show_Shoutbox_explain'], '<a href="' . append_sid('admin_board.'.$phpEx.'?mode=shoutbox') . '">', '</a>'),
		
			'L_LHS_EXPLAIN' => $lang['LHS_Explain'],
			'L_CTR_EXPLAIN' => $lang['CTR_Explain'],
			'L_RHS_EXPLAIN' => $lang['RHS_Explain'],
			
			'S_FORM_ACTION' => append_sid('admin_portal.'.$phpEx),
			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);
	}
	else if ( $mode == 'save' )
	{
		//
		// Ok, they sent us our info, let's update it.
		//		
		$portal_id = ( isset($HTTP_POST_VARS[POST_POST_URL]) ) ? intval($HTTP_POST_VARS[POST_POST_URL]) : 0;

		$page_title = ( isset($HTTP_POST_VARS['page_title']) ) ? $HTTP_POST_VARS['page_title'] : '';
		$use_url = ( isset($HTTP_POST_VARS['use_url']) ) ? ( ($HTTP_POST_VARS['use_url']) ? 0 : TRUE ) : TRUE;
		$use_iframe = ( isset($HTTP_POST_VARS['use_iframe']) ) ? ( ($HTTP_POST_VARS['use_iframe']) ? TRUE : 0 ) : 0;
		$iframe_height = ( isset($HTTP_POST_VARS['iframe_height']) ) ? intval($HTTP_POST_VARS['iframe_height']) : 600;
		$forum_select = ( isset($HTTP_POST_VARS['forum_select']) ) ? intval($HTTP_POST_VARS['forum_select']) : 0;
		$portal_url = ( isset($HTTP_POST_VARS['portal_url']) ) ? $HTTP_POST_VARS['portal_url'] : '';
		$list_limit = ( isset($HTTP_POST_VARS['list_limit']) ) ? intval($HTTP_POST_VARS['list_limit']) : 0;
		$char_limit = ( isset($HTTP_POST_VARS['char_limit']) ) ? intval($HTTP_POST_VARS['char_limit']) : 0;
		$posts_order = ( isset($HTTP_POST_VARS['posts_order']) ) ? ( ($HTTP_POST_VARS['posts_order']) ? TRUE : 0 ) : 0;
		$display_date = ( isset($HTTP_POST_VARS['display_date']) ) ? ( ($HTTP_POST_VARS['display_date']) ? 0 : TRUE ) : TRUE;
		$navbar_name = ( !empty($HTTP_POST_VARS['navbar_name']) ) ? $HTTP_POST_VARS['navbar_name'] : '';
		$show_newsfader = ( isset($HTTP_POST_VARS['show_newsfader']) ) ? ( ($HTTP_POST_VARS['show_newsfader']) ? TRUE : 0 ) : 0;
		$show_navbar = ( isset($HTTP_POST_VARS['show_navbar']) ) ? ( ($HTTP_POST_VARS['show_navbar']) ? TRUE : 0 ) : 0;
		$show_moreover = ( isset($HTTP_POST_VARS['show_moreover']) ) ? ( ($HTTP_POST_VARS['show_moreover']) ? TRUE : 0 ) : 0;
		$show_calendar = ( isset($HTTP_POST_VARS['show_calendar']) ) ? ( ($HTTP_POST_VARS['show_calendar']) ? TRUE : 0 ) : 0;
		$show_online = ( isset($HTTP_POST_VARS['show_online']) ) ? ( ($HTTP_POST_VARS['show_online']) ? TRUE : 0 ) : 0;
		$show_onlinetoday = ( isset($HTTP_POST_VARS['show_onlinetoday']) ) ? ( ($HTTP_POST_VARS['show_onlinetoday']) ? TRUE : 0 ) : 0;
		$show_latest = ( isset($HTTP_POST_VARS['show_latest']) ) ? ( ($HTTP_POST_VARS['show_latest']) ? TRUE : 0 ) : 0;
		$show_latest_exclude_forums = ( isset($HTTP_POST_VARS['show_latest_exclude_forums']) ) ? $HTTP_POST_VARS['show_latest_exclude_forums'] : '';
		$show_latest_amt = ( isset($HTTP_POST_VARS['show_latest_amt']) ) ? $HTTP_POST_VARS['show_latest_amt'] : '';
		$show_latest_scrolling = ( isset($HTTP_POST_VARS['show_latest_scrolling']) ) ? ( ($HTTP_POST_VARS['show_latest_scrolling']) ? TRUE : 0 ) : 0;
		$show_poll = ( isset($HTTP_POST_VARS['show_poll']) ) ? ( ($HTTP_POST_VARS['show_poll']) ? TRUE : 0 ) : 0;
		$portal_polls = ( isset($HTTP_POST_VARS['portal_polls']) ) ? $HTTP_POST_VARS['portal_polls'] : ''; 
		$show_photo = ( isset($HTTP_POST_VARS['show_photo']) ) ? ( ($HTTP_POST_VARS['show_photo']) ? TRUE : 0 ) : 0;
		$show_search = ( isset($HTTP_POST_VARS['show_search']) ) ? ( ($HTTP_POST_VARS['show_search']) ? TRUE : 0 ) : 0;
		$show_quote = ( isset($HTTP_POST_VARS['show_quote']) ) ? ( ($HTTP_POST_VARS['show_quote']) ? TRUE : 0 ) : 0;
		$show_links = ( isset($HTTP_POST_VARS['show_links']) ) ? ( ($HTTP_POST_VARS['show_links']) ? TRUE : 0 ) : 0;
		$portal_links_height = ( isset($HTTP_POST_VARS['portal_links_height']) ) ? $HTTP_POST_VARS['portal_links_height'] : '';
		$show_ourlink = ( isset($HTTP_POST_VARS['show_ourlink']) ) ? ( ($HTTP_POST_VARS['show_ourlink']) ? TRUE : 0 ) : 0;
		$show_downloads = ( isset($HTTP_POST_VARS['show_downloads']) ) ? ( ($HTTP_POST_VARS['show_downloads']) ? TRUE : 0 ) : 0;
		$show_randomuser = ( isset($HTTP_POST_VARS['show_randomuser']) ) ? ( ($HTTP_POST_VARS['show_randomuser']) ? TRUE : 0 ) : 0;
		$show_mostpoints = ( isset($HTTP_POST_VARS['show_mostpoints']) ) ? ( ($HTTP_POST_VARS['show_mostpoints']) ? TRUE : 0 ) : 0;
		$show_topposters = ( isset($HTTP_POST_VARS['show_topposters']) ) ? ( ($HTTP_POST_VARS['show_topposters']) ? TRUE : 0 ) : 0;
		$show_newusers = ( isset($HTTP_POST_VARS['show_newusers']) ) ? ( ($HTTP_POST_VARS['show_newusers']) ? TRUE : 0 ) : 0;
		$show_games = ( isset($HTTP_POST_VARS['show_games']) ) ? ( ($HTTP_POST_VARS['show_games']) ? TRUE : 0 ) : 0;
		$show_clock = ( isset($HTTP_POST_VARS['show_clock']) ) ? ( ($HTTP_POST_VARS['show_clock']) ? TRUE : 0 ) : 0;
		$portal_column_width = ( isset($HTTP_POST_VARS['portal_column_width']) ) ? $HTTP_POST_VARS['portal_column_width'] : '';
		$show_karma = ( isset($HTTP_POST_VARS['show_karma']) ) ? ( ($HTTP_POST_VARS['show_karma']) ? TRUE : 0 ) : 0;
		$show_horoscopes = ( isset($HTTP_POST_VARS['show_horoscopes']) ) ? ( ($HTTP_POST_VARS['show_horoscopes']) ? TRUE : 0 ) : 0;
		$show_wallpaper = ( isset($HTTP_POST_VARS['show_wallpaper']) ) ? ( ($HTTP_POST_VARS['show_wallpaper']) ? TRUE : 0 ) : 0;
		$show_donors = ( isset($HTTP_POST_VARS['show_donors']) ) ? ( ($HTTP_POST_VARS['show_donors']) ? TRUE : 0 ) : 0;
		$show_referrers = ( isset($HTTP_POST_VARS['show_referrers']) ) ? ( ($HTTP_POST_VARS['show_referrers']) ? TRUE : 0 ) : 0;
		$show_shoutbox = ( isset($HTTP_POST_VARS['show_shoutbox']) ) ? ( ($HTTP_POST_VARS['show_shoutbox']) ? TRUE : 0 ) : 0;

		// Verify the inputs
		if ( empty($navbar_name) )
		{
			message_die(GENERAL_MESSAGE, $lang['No_page_title']);
		}

		if ( !empty($portal_id) )
		{
			$sql = "UPDATE " . PORTAL_TABLE . " SET 
					portal_use_url = $use_url, 
					portal_use_iframe = $use_iframe, 
					portal_iframe_height = $iframe_height, 
					portal_forum = $forum_select, 
					portal_url = '$portal_url', 
					portal_list_limit = $list_limit, 
					portal_char_limit = $char_limit, 
					portal_ascending = $posts_order, 
					portal_nodate = $display_date, 
					portal_navbar_name = '" . str_replace("\'", "''", $navbar_name) . "', 
					portal_newsfader = $show_newsfader, 
					portal_column_width = $portal_column_width, 
					portal_navbar = $show_navbar, 
					portal_moreover = $show_moreover, 
					portal_calendar = $show_calendar, 
					portal_online = $show_online, 
					portal_onlinetoday = $show_onlinetoday, 
					portal_latest = $show_latest, 
					portal_latest_exclude_forums = '$show_latest_exclude_forums', 
					portal_latest_amt = $show_latest_amt, 
					portal_latest_scrolling = $show_latest_scrolling, 
					portal_poll = $show_poll, 
					portal_polls = '$portal_polls', 
					portal_photo = $show_photo, 
					portal_search = $show_search, 
					portal_quote = $show_quote, 
					portal_links = $show_links, 
					portal_links_height = $portal_links_height, 
					portal_ourlink = $show_ourlink, 
					portal_downloads = $show_downloads, 
					portal_randomuser = $show_randomuser, 
					portal_mostpoints = $show_mostpoints, 
					portal_topposters = $show_topposters, 
					portal_newusers = $show_newusers, 
					portal_games = $show_games, 
					portal_clock = $show_clock, 
					portal_karma = $show_karma, 
					portal_horoscopes = $show_horoscopes, 
					portal_wallpaper = $show_wallpaper, 
					portal_donors = $show_donors, 
					portal_referrers = $show_referrers,
					portal_shoutbox = $show_shoutbox 
				WHERE portal_id = " . $portal_id;
			
			$message = $lang['Portal_updated'];
		}
		else
		{
			$sql = "SELECT MAX(portal_order) AS max_order
				FROM " . PORTAL_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain next order data.', '', __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_order = $row['max_order'];
			$portal_order = $max_order + 10;

			$sql = "INSERT INTO " . PORTAL_TABLE . " (portal_order, portal_use_url, portal_use_iframe, portal_iframe_height, portal_forum, portal_url, portal_list_limit, portal_char_limit, portal_ascending, portal_nodate, portal_navbar_name, portal_newsfader, portal_column_width, portal_navbar, portal_moreover, portal_calendar, portal_online, portal_onlinetoday, portal_latest, portal_latest_exclude_forums, portal_latest_amt, portal_latest_scrolling, portal_poll, portal_polls, portal_photo, portal_search, portal_quote, portal_links, portal_links_height, portal_ourlink, portal_downloads, portal_randomuser, portal_mostpoints, portal_topposters, portal_newusers, portal_games, portal_clock, portal_karma, portal_horoscopes, portal_wallpaper, portal_donors, portal_referrers, portal_shoutbox)
				VALUES ($portal_order, $use_url, $use_iframe, $iframe_height, $forum_select, '$portal_url', $list_limit, $char_limit, $posts_order, $display_date, '" . str_replace("\'", "''", $navbar_name) . "', $show_newsfader, $portal_column_width, $show_navbar, $show_moreover, $show_calendar, $show_online, $show_onlinetoday, $show_latest, '$show_latest_exclude_forums', $show_latest_amt, $show_latest_scrolling, $show_poll, '$portal_polls', $show_photo, $show_search, $show_quote, $show_links, $portal_links_height, $show_ourlink, $show_downloads, $show_randomuser, $show_mostpoints, $show_topposters, $show_newusers, $show_games, $show_clock, $show_karma, $show_horoscopes, $show_wallpaper, $show_donors, $show_referrers, $show_shoutbox)";
			
			$message = $lang['Portal_added'];
		}
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update/insert portal page.', '', __LINE__, __FILE__, $sql);
		}
		
		$message .= '<br /><br />' . sprintf($lang['Click_return_portal'], '<a href="' . append_sid('admin_portal.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
		
		message_die(GENERAL_MESSAGE, $message);
	}
	else if ( $mode == 'delete' )
	{
		//
		// Ok, they lets delete the selected page
		//	
		if ( isset($HTTP_POST_VARS[POST_POST_URL]) || isset($HTTP_GET_VARS[POST_POST_URL]) )
		{
			$portal_id = ( isset($HTTP_POST_VARS[POST_POST_URL]) ) ? intval($HTTP_POST_VARS[POST_POST_URL]) : intval($HTTP_GET_VARS[POST_POST_URL]);
		}
		else
		{
			$portal_id = '';
		}
		
		if( !empty($portal_id) )
		{
			if ( $portal_id == 1 )
			{
				message_die(GENERAL_MESSAGE, $lang['No_delete_page'] . '<br /><br />' . sprintf($lang['Click_return_portal'], '<a href="' . append_sid('admin_portal.'.$phpEx) . '">', '</a>'));
			}
			
			$sql = "DELETE FROM " . PORTAL_TABLE . "
				WHERE portal_id = " . $portal_id;			
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not delete portal page', '', __LINE__, __FILE__, $sql);
			}
			
			$message = $lang['Portal_deleted'] . '<br /><br />' . sprintf($lang['Click_return_portal'], '<a href="' . append_sid('admin_portal.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');
			
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_portal_id']);
		}
	} 
	else
	{
		message_die(GENERAL_ERROR, 'No mode specified.');
	}
}
else
{
	// Admin wants to move a page
	if ( $HTTP_GET_VARS['order'] == 'move' )
	{
		$move = intval($HTTP_GET_VARS['move']);
		$portal_id = ( !empty($HTTP_POST_VARS[POST_POST_URL]) ) ? $HTTP_POST_VARS[POST_POST_URL] : $HTTP_GET_VARS[POST_POST_URL];
		$portal_id = intval($portal_id);

		if( !empty($portal_id) )
		{
			$sql = "UPDATE " . PORTAL_TABLE . "
				SET portal_order = portal_order + $move
				WHERE portal_id = " . $portal_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not change portal page order.', '', __LINE__, __FILE__, $sql);
			}

			$sql = "SELECT *
				FROM " . PORTAL_TABLE . "
				ORDER BY portal_order";
			if ( !($result2 = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain list of pages.', '', __LINE__, __FILE__, $sql);
			}

			$i = 10;
			
			while ( $row = $db->sql_fetchrow($result2) )
			{
				$sql = "UPDATE " . PORTAL_TABLE . "
					SET portal_order = $i
					WHERE portal_id = " . $row['portal_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not update order fields.', '', __LINE__, __FILE__, $sql);
				}

				$i += 10;
			}
			$db->sql_freeresult($result2);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_portal_id']);
		}
	}

	//
	// This is the main display of the page before the admin has selected
	// any options.
	//
	$template->set_filenames(array(
		'body' => 'admin/portal_body.tpl')
	);

	$sql = "SELECT * 
		FROM " . PORTAL_TABLE . " 
		ORDER BY portal_order";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain portal pages.', '', __LINE__, __FILE__, $sql);
	}
	$page_count = $db->sql_numrows($result);
	$portal_row = $db->sql_fetchrowset($result);
	
	$template->assign_vars(array(
		'L_PAGE_TITLE' => $lang['Manage_portal'],
		'L_PAGE_EXPLAIN' => $lang['Portal_explain'],
		'L_ADD_PAGE' => $lang['Add_new_Portal'],
		'L_NAVBAR_NAME' => $lang['Navbar_Name'],
		'L_ACTION' => $lang['Action'])
	);
	
	for ($i = 0; $i < $page_count; $i++)
	{
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		$template->assign_block_vars('pages', array(
			'ROW_CLASS' => $row_class,
			'PORTAL_DEFAULT' => ( $portal_row[$i]['portal_id'] == 1 ) ? '<b style="color: #FF0000">*</b> ' : '',
			'PORTAL_NAVNAME' => $portal_row[$i]['portal_navbar_name'],
			
			'L_PAGE_UP' => '<img src="' . $phpbb_root_path . $images['acp_up'] . '" alt="' . $lang['Move_up'] . '" title="' . $lang['Move_up'] . '" />',
			'L_PAGE_DOWN' => '<img src="' . $phpbb_root_path . $images['acp_down'] . '" alt="' . $lang['Move_down'] . '" title="' . $lang['Move_down'] . '" />',
			'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
			'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',

			
			'U_PAGE_UP' => append_sid('admin_portal.'.$phpEx.'?order=move&amp;move=-15&amp;' . POST_POST_URL . '=' . $portal_row[$i]['portal_id']),
			'U_PAGE_DOWN' => append_sid('admin_portal.'.$phpEx.'?order=move&amp;move=15&amp;' . POST_POST_URL . '=' . $portal_row[$i]['portal_id']),
			'U_PAGE_EDIT' => append_sid('admin_portal.'.$phpEx.'?mode=edit&amp;' . POST_POST_URL . '=' . $portal_row[$i]['portal_id']),
			'U_PAGE_DELETE' => append_sid('admin_portal.'.$phpEx.'?mode=delete&amp;' . POST_POST_URL . '=' . $portal_row[$i]['portal_id']))
		);
	}
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
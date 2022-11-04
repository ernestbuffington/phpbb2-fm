<?php
/** 
*
* @package includes
* @version $Id: viewonline.php,v 1.54.2.3 2004/07/11 16:46:17 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_VIEWONLINE);
init_userprefs($userdata);
//
// End session management
//


//
// Prillian
//
include_once(PRILL_PATH . 'prill_common.' . $phpEx);
if ( empty($im_userdata) )
{
	$im_userdata = init_imprefs($userdata['user_id'], false, true);
}


//
// Output page header and load viewonline template
//
$page_title = $lang['Who_is_Online'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'viewonline_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

//
// Simple Colored Usergroups
//
$group_legend = array();
if ($board_config['enable_bots_whosonline'])
{
	$group_legend[] = '<b style="color: #' . $theme['botfontcolor'] . '">' . $lang['Bot_online_color'] . '</b>';
}	

if ( is_array($color_groups['groupdata']) )
{
	foreach($color_groups['groupdata'] AS $group_id => $group_data)
	{
		if ( !$userdata['session_logged_in'] )
		{
			$group_color = $group_data['group_color'][$board_config['default_style']];
		}
		else
		{
			$group_color = $group_data['group_color'][$userdata['user_style']];
		}
		if ( !$group_color )
		{
			$match_found = false;
			foreach ( $group_data['group_color'] AS $color )
			{
				if ( !$match_found )
				{
					if ( $color )
					{
						$group_color = $color;
						$match_found = true;
					}
				}
			}
		}
		if ( $group_color )
		{
			$grouplink = '<a class="gensmall" href="' . append_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=" . $group_data['group_id']) . '"><b style="color: #' . $group_color . '">' . $group_data['group_name'] . '</b></a>';
			$group_legend[] = $grouplink;
		}
	}
}

$group_legend = implode(', ', $group_legend);
$group_legend = ( $group_legend ) ? ', ' . $group_legend : '';
	

//
// Forum info
//
$sql = "SELECT forum_name, forum_id
	FROM " . FORUMS_TABLE;
if ( $result = $db->sql_query($sql) )
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$forum_data[$row['forum_id']] = $row['forum_name'];
	}
	$db->sql_freeresult($result);

}
else
{
	message_die(GENERAL_ERROR, 'Could not obtain user/online forums information', '', __LINE__, __FILE__, $sql);
}

//
// Get auth data
//
$is_auth_ary = array();
$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata);

//
// Get user list
//
$sql = "SELECT u.user_id, u.username, u.user_allow_viewonline, u.user_level, u.user_session_page, s.session_logged_in, s.session_time, s.session_page, s.session_topic, s.session_ip, s.session_start, s.is_robot
	FROM " . USERS_TABLE . " u, " . SESSIONS_TABLE . " s
	WHERE u.user_id = s.session_user_id
		AND s.session_time >= " . ( time() - ($board_config['whosonline_time'] * 60) ) . "
	ORDER BY u.username, s.session_ip";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain regd user/online information', '', __LINE__, __FILE__, $sql);
}

$guest_users = $registered_users = $hidden_users = $reg_counter = $guest_counter = $prev_user = 0;
$prev_ip = $prev_is_robot = '';

while ( $row = $db->sql_fetchrow($result) )
{
	$view_online = false;

	// User is logged in and therefor not a guest
	if ( $row['session_logged_in'] ) 
	{
		$user_id = $row['user_id'];

		// Skip multiple sessions for one user
		if ( $user_id != $prev_user )
		{
			$username = username_level_color($row['username'], $row['user_level'], $user_id);

			// Game Players
			if ($board_config['ina_players_index'])
			{
				if ( $row['user_session_page'] == PAGE_ACTIVITY || $row['user_session_page'] == PAGE_PLAYING_GAMES )
				{
					$username = '<b style="color: #' . $theme['playersfontcolor'] . '">' . $row['username'] . '</b>';
				}
			}
			
			// Invisible
			if ( !$row['user_allow_viewonline'] )
			{
				$view_online = ( $userdata['user_level'] == ADMIN || $userdata['user_id'] == $user_id ) ? true : false;
				$hidden_users++;

				$username = '<i>' . $username . '</i>';
			}
			else
			{
				$view_online = true;
				$registered_users++;
			}

			$which_counter = 'reg_counter';
			$which_row = 'reg_user_row';
			$prev_user = $user_id;
		}
	}
	else
	{
		if ( $row['session_ip'] != $prev_ip )
		{
			if ($row['is_robot'] != '0' && $row['is_robot'] != $prev_is_robot && $board_config['enable_bots_whosonline'])
			{ 
				$username = '<b style="color: #' . $theme['botfontcolor'] . '">' . $row['is_robot'] . '</b>'; 
			} 
			else 
			{
				$username = $lang['Guest'];
			}

			$view_online = true;
			$guest_users++;
	
			$which_counter = 'guest_counter';
			$which_row = 'guest_user_row';
		}
	}

    $prev_is_robot = $row['is_robot'];
	$prev_ip = $row['session_ip'];

	if ( $view_online )
	{
		if ( $row['session_page'] < 1 || !$is_auth_ary[$row['session_page']]['auth_view'] )
		{
			$language = $board_config['default_lang'];

			switch( $row['session_page'] )
			{
				case PAGE_INDEX:
					$location = $lang['Forum_Index'];
					$location_url = "index.$phpEx";
					break;
				case PAGE_PORTAL:
					$location = $lang['Viewing_portal'];
					$location_url = "portal.$phpEx";
					break;
				case PAGE_POSTING:
					$location = $lang['Posting_message'];
					$location_url = "index.$phpEx";
					break;
				case PAGE_LOGIN:
					$location = $lang['Logging_on'];
					$location_url = "login.$phpEx";
					break;
				case PAGE_SEARCH:
					$location = $lang['Searching_forums'];
					$location_url = "search.$phpEx";
					break;
				case PAGE_PROFILE:
					$location = $lang['Viewing_profile'];
					$location_url = "profile.$phpEx?mode=editprofile&amp;ucp=main";
					break;
				case PAGE_VIEWONLINE:
					$location = $lang['Viewing_online'];
					$location_url = "viewonline.$phpEx";
					break;
				case PAGE_VIEWMEMBERS:
					$location = $lang['Viewing_member_list'];
					$location_url = "memberlist.$phpEx";
					break;
				case PAGE_PRIVMSGS:
					$location = $lang['Viewing_priv_msgs'];
					$location_url = "privmsg.$phpEx";
					break;
				case PAGE_FAQ:
					$location = $lang['Viewing_FAQ'];
					$location_url = "faq.$phpEx";
					break;
				case PAGE_SMILES:
					$location = $lang['Viewing_Smilies'];
					$location_url = "smilies.$phpEx";
					break;
				case PAGE_TELLFRIEND:
					$location = $lang['Viewing_Tell_Friend'];
					$location_url = "tellafriend.$phpEx";
					break;
				case PAGE_LINKS:
					$location = $lang['Viewing_Links'];
					$location_url = "linkdb.$phpEx";
					break;
				case PAGE_DOWNLOAD:
					$location = $lang['Viewing_Download'];
					$location_url = "dload.$phpEx";
					break;    
				case PAGE_TOPIC_VIEW:
					$location = $lang['Viewing_topic_views'];
					$location_url = "index.$phpEx";
					break;
				case PAGE_TOPICS_STARTED:
					$location = $lang['Viewing_topics_started'];
					$location_url = "topics.$phpEx";
					break;
				case PAGE_STAFF:
					$location = $lang['Viewing_staff'];
					$location_url = "staff.$phpEx";
					break;
				case PAGE_ALBUM:
					$location = $lang['Viewing_album'];
					$location_url = "album.$phpEx";
					break;
				case PAGE_ALBUM_PERSONAL:
					$location = $lang['Viewing_album_personal'];
					$location_url = "album_personal_index.$phpEx";
					break;
				case PAGE_ALBUM_PICTURE:
					$location = $lang['Viewing_album_pic'];
					$location_url = "album.$phpEx";
					break;
				case PAGE_ALBUM_SEARCH:
					$location = $lang['Searching_album'];
					$location_url = "album_search.$phpEx";
					break;
            	case PAGE_ALBUM_RSS: 
            	   	$location = $lang['Viewing_RSS']; 
            	   	$location_url = "album_rss.$phpEx"; 
            	   	break; 
				case PAGE_ATTACHMENTS:
					$location = $lang['Viewing_attachments'];
					$location_url = "attachments.$phpEx";
					break;
				case PAGE_STATISTICS:
					$location = $lang['Viewing_stats'];
					$location_url = "statistics.$phpEx";
					break;
				case PAGE_TRANSACTIONS:
					$location = $lang['Global_Trans'];
					$location_url = "transactions.$phpEx";
					break;	
				case PAGE_CALENDAR:
					$location = $lang['Viewing_calendar'];
					$location_url = "calendar.$phpEx";
					break;
				case PAGE_BANK:
					$location = $lang['Viewing_bank'];
					$location_url = "bank.$phpEx";
					break;
				case PAGE_SHOP:
					$location = $lang['Viewing_shop'];
					$location_url = "shop.$phpEx";
					break;
				case PAGE_RATINGS:
					$location = $lang['Viewing_ratings'];
					$location_url = "ratings.$phpEx";
					break;
				case PAGE_CHATROOM:
					$location = $lang['Viewing_chatroom'];
					$location_url = "chatroom.$phpEx";
					break;
				case PAGE_IMLIST:
					$location = $lang['Viewing_IM_list'];
					$location_url = "imlist.$phpEx";
					break;
				case PAGE_TOPLIST:
					$location = $lang['Viewing_toplist'];
					$location_url = "toplist.$phpEx";
					break;
				case PAGE_LOTTERY:
					$location = $lang['Viewing_lottery'];
					$location_url = "lottery.$phpEx";
					break;
				case PAGE_CHARTS:
					$location = $lang['Viewing_charts'];
					$location_url = "charts.php?action=list";
					break;
				case PAGE_BANLIST:
					$location = $lang['Viewing_banlist'];
					$location_url = "banlist.$phpEx";
					break;
				case PAGE_KB:
					$location = $lang['Viewing_KB'];
					$location_url = "kb.$phpEx";
					break;
				case PAGE_SHOUTBOX:
				case PAGE_SHOUTBOX_MAX:
					$location = $lang['Viewing_Shoutbox'];
					$location_url = "shoutbox_max.$phpEx";
					break;
				case PAGE_REDIRECT: 
					if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_banner.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_banner.' . $phpEx);
					if ($row['session_topic'])
					{
						$sql = "SELECT banner_description 
							FROM " . BANNERS_TABLE . " 
							WHERE banner_id = " . $row['session_topic'];
						if ( $result2 = $db->sql_query($sql) )
						{
							$banner_data = $db->sql_fetchrow($result2);
						}
						else
						{	
							message_die(GENERAL_ERROR, 'Could not obtain redirect online information', '', __LINE__, __FILE__, $sql);
						}
						$location_url = append_sid("redirect.$phpEx?banner_id=" . $row['session_topic']);
						$location = $lang['Left_via_banner'] . ' -> ' . $banner_data['banner_description'];
					} 
					else
					{
						$location_url = "index.$phpEx";
						$location = $lang['Left_via_banner'];
					}
					break;
				case PAGE_PRILLIAN:
					$location = $lang['Prillian'];
					$location_url = PRILL_URL . $im_userdata['mode_string'];
					break;
				case PAGE_CONTACT:
					$location = $lang['Contact_Management'];
					$location_url = CONTACT_URL;
					break;
				case PAGE_MEETING:
					$location = $lang['Meeting'];
					$location_url = "meeting.$phpEx";
					break;
				case PAGE_HELPDESK:
					$location = $lang['Viewing_helpdesk'];
					$location_url = "helpdesk.$phpEx";
					break;
				case PAGE_ACTIVITY:
					$location = $lang['Activity'];
					$location_url = "activity.$phpEx";
					break;
				case PAGE_PLAYING_GAMES:
					$location = $lang['Activity'];
					$location_url = "activity.$phpEx";
					break;
				case PAGE_BOOKIES:
					$location = $lang['bookies'];
					$location_url = "bookies.$phpEx";
					break;
				case PAGE_BOOKIE_YOURSTATS:
					$location = $lang['bookie_yourstats'];
					$location_url = "bookie_yourstats.$phpEx";
					break;
				case PAGE_BOOKIE_ALLSTATS:
					$location = $lang['bookie_allstats'];
					$location_url = "bookie_allstats.$phpEx";
					break;
				case PAGE_LEXICON:
					$location = sprintf($lang['Viewing_Lexicon'], $board_config['lexicon_title']);
					$location_url = "lexicon.$phpEx";
					break;
				case PAGE_SITEMAP:
					$location = $lang['Sitemap_viewing'];
					$location_url = "sitemap.$phpEx";
					break;
				case PAGE_AUCTIONS:
					if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auctions.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_auctions.' . $phpEx);
					$location = $lang['Auctions_viewing'];
					$location_url = "auctions.$phpEx";
					break;
            	case PAGE_RSS: 
            	   	$location = $lang['Viewing_RSS']; 
            	   	$location_url = "rss.$phpEx"; 
            	   	break; 
				case PAGE_JOBS:
					$location = $lang['Viewing_jobs'];
					$location_url = "jobs.$phpEx";
					break;
				case PAGE_AVATAR_TOPLIST:
					$location = $lang['Viewing_avatar_toplist'];
					$location_url = "avatarsuite_toplist.$phpEx";
					break;
				case PAGE_AVATAR_LIST:
					$location = $lang['Viewing_avatar_list'];
					$location_url = "avatarsuite_listavatarts.$phpEx";
					break;
				case PAGE_GUESTBOOK:
					$location = $lang['Viewing_guestbook'];
					$location_url = "guestbook.$phpEx";
					break;
				case PAGE_MEDALS:
					$location = $lang['Medals'];
					$location_url = "medals.$phpEx";
					break;
				// Fully Modded site specific only!
				case PAGE_FMINDEX:
					$location = $lang['FM_Index'];
					$location_url = "index_fm.$phpEx";
					break;
				default:
					$location = $lang['Forum_Index'];
					$location_url = "index.$phpEx";
			}
		}
		else
		{
			if ($row['session_topic'])
			{
				//
				// Topic info
				//
				$sql2 = "SELECT topic_title, title_compl_infos, title_pos, title_compl_color
					FROM " . TOPICS_TABLE . " 
					WHERE topic_id = " . $row['session_topic'];
				if (!$result2 = $db->sql_query($sql2))
				{
					message_die(GENERAL_ERROR, 'Could not obtain user/online topic information', '', __LINE__, __FILE__, $sql2);
				}
				$topicrow = $db->sql_fetchrow($result2);

				$topic_title = $topicrow['topic_title'];

        		$topic_title = capitalization($topic_title);
	
				if ($board_config['enable_quick_titles'])
				{
					if ( $topicrow['title_pos'] )
					{
						$topic_title = (empty($topicrow['title_compl_infos'])) ? $topic_title : $topic_title . ' <span style="color: #' . $topicrow['title_compl_color'] . '">' . $topicrow['title_compl_infos'] . '</span>';
					}
					else
					{
						$topic_title = (empty($topicrow['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $topicrow['title_compl_color'] . '">' . $topicrow['title_compl_infos'] . '</span> ' . $topic_title;
					}
				}
				
				$location_url = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . '=' . $row['session_topic']);
				$location = $lang['Reading']  . ' ' . $topic_title;

				$db->sql_freeresult($result2);
			} 
			else 
			{
				$location_url = append_sid("viewforum.$phpEx?" . POST_FORUM_URL . '=' . $row['session_page']);
				$location = $lang['Viewing']  . ' ' . $forum_data[$row['session_page']] . ' ' . $lang['Forum'];
			}
		}

		$row_class = ( $$which_counter % 2 ) ? $theme['td_class2'] : $theme['td_class1'];

		// Format start and last update time
		if ( $board_config['time_today'] < $row['session_start'] )
		{ 
			$row['session_start'] = sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $row['session_start'], $board_config['board_timezone'])); 
		}
		else if ( $board_config['time_yesterday'] <  $row['session_start'] )
		{ 
			$row['session_start'] = sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $row['session_start'], $board_config['board_timezone'])); 
		}
		else
		{ 
			 $row['session_start']  =  create_date($board_config['default_dateformat'], $row['session_start'], $board_config['board_timezone']); 
		} 	

		if ( $board_config['time_today'] < $row['session_time'] )
		{ 
			$row['session_time'] = sprintf($lang['Today_at'], create_date($board_config['default_timeformat'], $row['session_time'], $board_config['board_timezone'])); 
		}
		else if ( $board_config['time_yesterday'] <  $row['session_time'] )
		{ 
			$row['session_time'] = sprintf($lang['Yesterday_at'], create_date($board_config['default_timeformat'], $row['session_time'], $board_config['board_timezone'])); 
		}
		else
		{ 
			 $row['session_time']  =  create_date($board_config['default_dateformat'], $row['session_time'], $board_config['board_timezone']); 
		} 	

		$template->assign_block_vars("$which_row", array(
			'ROW_CLASS' => $row_class,
			'USERNAME' => $username,
			'USER_IP' => ( $userdata['user_level'] == ADMIN ) ? decode_ip($row['session_ip']) : '',
			'STARTED' => $row['session_start'],
			'LASTUPDATE' => $row['session_time'],
			'FORUM_LOCATION' => $location,

			'U_USER_PROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '=' . $user_id),
			'U_FORUM_LOCATION' => append_sid($location_url))
		);

		$$which_counter++;
	}
}
$db->sql_freeresult($result);

if( $registered_users == 0 )
{
	$l_r_user_s = $lang['Reg_users_zero_online'];
}
else if( $registered_users == 1 )
{
	$l_r_user_s = $lang['Reg_user_online'];
}
else
{
	$l_r_user_s = $lang['Reg_users_online'];
}

if( $hidden_users == 0 )
{
	$l_h_user_s = $lang['Hidden_users_zero_online'];
}
else if( $hidden_users == 1 )
{
	$l_h_user_s = $lang['Hidden_user_online'];
}
else
{
	$l_h_user_s = $lang['Hidden_users_online'];
}

if( $guest_users == 0 )
{
	$l_g_user_s = $lang['Guest_users_zero_online'];
}
else if( $guest_users == 1 )
{
	$l_g_user_s = $lang['Guest_user_online'];
}
else
{
	$l_g_user_s = $lang['Guest_users_online'];
}

$template->assign_vars(array(
	'L_WHOSONLINE' => $lang['Who_is_online'],
	'L_ONLINE_EXPLAIN' => sprintf($lang['Online_explain'], $board_config['whosonline_time']),
	'L_USERNAME' => $lang['Username'],
	'L_FORUM_LOCATION' => $lang['Forum_Location'],
	'L_STARTED' => $lang['Logged_on'],
	'L_LAST_UPDATE' => $lang['Last_updated'],

	'META' => '<meta http-equiv="refresh" content="60; url=viewonline.'.$phpEx.'">',
	'GROUP_LEGEND' => $group_legend,
	'TOTAL_REGISTERED_USERS_ONLINE' => sprintf($l_r_user_s, $registered_users) . sprintf($l_h_user_s, $hidden_users), 
	'TOTAL_GUEST_USERS_ONLINE' => sprintf($l_g_user_s, $guest_users))
);

if ( $registered_users + $hidden_users == 0 )
{
	$template->assign_vars(array(
		'L_NO_REGISTERED_USERS_BROWSING' => $lang['No_users_browsing'])
	);
}

if ( $guest_users == 0 )
{
	$template->assign_vars(array(
		'L_NO_GUESTS_BROWSING' => $lang['No_users_browsing'])
	);
}

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
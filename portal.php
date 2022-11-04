<?php
/** 
*
* @package phpBB2
* @version $Id: portal.php,v 2.0.1 2002/11/05 21:48:00 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
define('SHOW_ONLINE', true); 
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'lgf-reflog.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx); 

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PORTAL);
init_userprefs($userdata);
//
// End session management
//

//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_portal.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_portal.' . $phpEx);


//
// Start page proper
//
if ( isset($HTTP_GET_VARS['page']) )
{
	$page_id = intval($HTTP_GET_VARS['page']);
}
else
{
	$page_id = 1;
}

$viewcat = ( !empty($HTTP_GET_VARS[POST_CAT_URL]) ) ? $HTTP_GET_VARS[POST_CAT_URL] : -1;

$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . "_t"]) : array();
$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . "_f"]) : array();


//
// Display the nav bar and the page description
//
$sql = "SELECT * 
	FROM " . PORTAL_TABLE . "
	ORDER BY portal_order";
if ( !$result = $db->sql_query($sql) )
{
   message_die(GENERAL_ERROR, 'Could not obtain portal navigation information', '', __LINE__, __FILE__, $sql);
}

$total_nav = 0;
$portal_rowset = array();
while ( $row = $db->sql_fetchrow($result) )
{
	$portal_rowset[] = $row;
	$total_nav++;
}
$db->sql_freeresult($result);


//
// Okay, lets dump out the page ...
//
if (!empty($total_nav))
{
	$display_navbar = $display_moreover = $display_calendar = $display_login = $display_online = $display_onlinetoday = $display_latest = $display_poll = $display_photo = $display_language = $display_theme = $display_search = $display_quote = $display_links = $display_ourlink = $display_downloads = $display_randomuser = $display_mostpoints = $display_topposters = $display_newusers = $display_games = $display_newsfader = $display_clock = $display_karma = $display_horoscopes = $display_wallpaper = $no_date = $sort_asc = $list_limit = 0;

	// Get the nav bar info
	for ($i = 0; $i < $total_nav; $i++)
	{
		$portal_id = $portal_rowset[$i]['portal_id'];
		$portal_url = append_sid('portal.'.$phpEx.'?page=' . $portal_id);
		
		if ($portal_rowset[$i]['portal_use_url'] && $portal_rowset[$i]['portal_use_iframe'])
		{
			$portal_url = $portal_rowset[$i]['portal_url'];
		}
		
		$portal_navbar_name = $portal_rowset[$i]['portal_navbar_name'];
		
		if ( $portal_rowset[$i]['portal_id'] != $page_id )
		{
		}
		else
		{
			// Get the page desc info and other config info
			$iframe_height = $portal_rowset[$i]['portal_iframe_height'];
			$page_forum = $portal_rowset[$i]['portal_forum'];
			$display_navbar = $portal_rowset[$i]['portal_navbar'];
			$display_moreover = $portal_rowset[$i]['portal_moreover'];
			$display_calendar = $portal_rowset[$i]['portal_calendar'];
			$display_login = $portal_rowset[$i]['portal_login'];
			$display_online = $portal_rowset[$i]['portal_online'];
			$display_onlinetoday = $portal_rowset[$i]['portal_onlinetoday'];
			$display_latest = $portal_rowset[$i]['portal_latest'];
			$display_latest_exclude_forums = str_replace(';', ',', $portal_rowset[$i]['portal_latest_exclude_forums']); 
			$display_latest_amt = $portal_rowset[$i]['portal_latest_amt'];
			$display_latest_scrolling = $portal_rowset[$i]['portal_latest_scrolling'];
			$display_poll = $portal_rowset[$i]['portal_poll'];
			$display_polls = str_replace(';', ',', $portal_rowset[$i]['portal_polls']); 
			$display_photo = $portal_rowset[$i]['portal_photo'];
			$display_search = $portal_rowset[$i]['portal_search'];
			$display_quote = $portal_rowset[$i]['portal_quote'];
			$display_links = $portal_rowset[$i]['portal_links'];
			$display_ourlink = $portal_rowset[$i]['portal_ourlink'];
			$display_downloads = $portal_rowset[$i]['portal_downloads'];
			$display_randomuser = $portal_rowset[$i]['portal_randomuser'];
			$display_mostpoints = $portal_rowset[$i]['portal_mostpoints'];
			$display_topposters = $portal_rowset[$i]['portal_topposters'];
			$display_newusers = $portal_rowset[$i]['portal_newusers'];
			$display_games = $portal_rowset[$i]['portal_games'];
			$display_newsfader = $portal_rowset[$i]['portal_newsfader'];
			$no_date = $portal_rowset[$i]['portal_nodate'];
			$sort_asc = $portal_rowset[$i]['portal_ascending'];
			$list_limit = $portal_rowset[$i]['portal_list_limit'];
			$char_limit = $portal_rowset[$i]['portal_char_limit'];
			$portal_column_width = $portal_rowset[$i]['portal_column_width'];
			$portal_links_height = $portal_rowset[$i]['portal_links_height'];
			$display_clock = $portal_rowset[$i]['portal_clock'];
			$display_karma = $portal_rowset[$i]['portal_karma'];
			$display_horoscopes = $portal_rowset[$i]['portal_horoscopes'];
			$display_wallpaper = $portal_rowset[$i]['portal_wallpaper'];
			$display_donors = $portal_rowset[$i]['portal_donors'];
			$display_referrers = $portal_rowset[$i]['portal_referrers'];
			$display_shoutbox = $portal_rowset[$i]['portal_shoutbox'];
			$page_title = $portal_navbar_name;	

			$template->assign_block_vars('pagedesc', array(
  				'IFRAME_HEIGHT' => $iframe_height,
				'U_PORTAL_SITE_NAME' => $portal_rowset[$i]['portal_navbar_name'],
				'U_PORTAL_SITE_URL' => $portal_rowset[$i]['portal_url'])
			);
		}

		$template->assign_block_vars('navrow', array(
			'NAV_ALT' => $portal_navbar_name,
			'U_NAVIGATE' => $portal_url)
		);
	}
}
else
{
	$template->assign_vars(array(
		'L_NO_TOPICS' => $lang['No_topics_post_one'])
	);

	$template->assign_block_vars('nonavrow', array());
}


//
// Links
//
if (!empty($display_ourlink) || !empty($display_links))
{	
	if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_linkdb.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_linkdb.' . $phpEx);

	$sql = "SELECT *
		FROM " . $table_prefix . "link_config";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query link configuration', '', __LINE__, __FILE__, $sql);
	}
	
	while( $row = $db->sql_fetchrow($result) )
	{
		$link_config_name = $row['config_name'];
		$link_config_value = $row['config_value'];
		$link_config[$link_config_name] = $link_config_value;
		
		$link_self_img = $link_config['site_logo'];
		$site_logo_height = $link_config['height'];
		$site_logo_width = $link_config['width'];	
	}
	$db->sql_freeresult($result);

	if ($display_links)
	{
		$sql = "SELECT link_id, link_name, link_logo_src
			FROM " . $table_prefix . "links
			WHERE link_approved = 1 
				AND link_logo_src <> ''
			ORDER BY RAND()";
		if( $result = $db->sql_query($sql) )
		{
			while($row = $db->sql_fetchrow($result))
			{
				$template->assign_block_vars('q_link', array(
					'QL_NAME' => $row['link_name'],
					'QL_URL' => append_sid('linkdb.'.$phpEx.'?action=link&amp;link_id=' . $row['link_id']),
					'QL_IMAGE' => $row['link_logo_src'])
				);
			}
			$db->sql_freeresult($result);
			
			$template->assign_vars(array(
				'QL_HEIGHT' => $site_logo_height,
				'QL_WIDTH' => $site_logo_width,
				'QL_GO' => '<a href="javascript:void(0);" onclick="scroll_minibanners.start(); scroll_minibanners.scrollAmount=2;"><img src="' . $images['icon_up'] . '" alt="' . $lang['Start'] . '" title="' . $lang['Start'] . '" /></a>&nbsp;',
				'QL_SPEED' => '<a href="javascript:void(0);" onclick="scroll_minibanners.start(); scroll_minibanners.scrollAmount++;"><img src="' . $images['icon_right'] . '" alt="' . $lang['Fast'] . '" title="' . $lang['Fast'] . '" /></a>&nbsp;',
				'QL_SLOW' => '<a href="javascript:void(0);" onclick="scroll_minibanners.start(); if(scroll_minibanners.scrollAmount>0) scroll_minibanners.scrollAmount--;"><img src="' . $images['icon_left'] . '" alt="' . $lang['Slow'] . '" title="' . $lang['Slow'] . '" /></a>&nbsp;',
				'QL_STOP' => '<a href="javascript:void(0);" onclick="scroll_minibanners.stop()"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Stop'] . '" title="' . $lang['Stop'] . '" /></a>')
			);
		}
	}
}

//
// Show daily users
//
if (!empty($display_onlinetoday))
{
	$time1Hour = time() - 3600;
	$minutes = date('is', time());
	$hour_now = time() - (60 * ($minutes[0] . $minutes[1])) - ($minutes[2] . $minutes[3]); 
	$dato = create_date('H', time(), $board_config['board_timezone']);
	$timetoday = $hour_now - (3600 * $dato); 
	
	$sql = "SELECT session_ip, MAX(session_time) AS session_time 
		FROM " . SESSIONS_TABLE . " 
		WHERE session_user_id = '". ANONYMOUS . "' 
			AND session_time >= " . $timetoday . " 
			AND session_time < " . ( $timetoday + 86399 ) . "
		GROUP BY session_ip";
	if (!$result = $db->sql_query($sql)) 
	{
			message_die(GENERAL_ERROR, 'Could not retrieve guest user today data.', '', __LINE__, __FILE__, $sql); 
	}
	
	while( $guest_list = $db->sql_fetchrow($result))
	{ 
		if ($guest_list['session_time'] > $time1Hour) 
		{
			$users_lasthour++;
		}
	}
	$guests_today = $db->sql_numrows($result);
	$db->sql_freeresult($result);
	
	$sql = "SELECT user_id, username, user_allow_viewonline, user_level, user_lastlogon 
		FROM " . USERS_TABLE . " 
		WHERE user_id != '" . ANONYMOUS . "'
			AND user_session_time >= " . $timetoday . " 
			AND user_session_time < ". ( $timetoday + 86399 ) . " 
		ORDER BY username"; 
	if (!$result = $db->sql_query($sql)) 
	{
		message_die(GENERAL_ERROR, 'Could not retrieve registered user today data.', '', __LINE__, __FILE__, $sql); 
	}

	while( $todayrow = $db->sql_fetchrow($result)) 
	{ 
		if ($todayrow['user_lastlogon'] >= $time1Hour)
		{
			$users_lasthour++;
		}

		$todayrow['username'] = username_level_color($todayrow['username'], $todayrow['user_level'], $todayrow['user_id']);

		$users_today_list .= ( $todayrow['user_allow_viewonline'] ) ? ' <a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $todayrow['user_id']) . '" class="gensmall">' . $todayrow['username'] . '</a>,' : (($userdata[user_level]==ADMIN) ? ' <a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $todayrow['user_id']) . '" class="gensmall"><i>' . $todayrow['username'] . '</i></a>,' : '');

		if (!$todayrow['user_allow_viewonline']) 
		{
			$logged_hidden_today++;
		}
		else 
		{
			$logged_visible_today++;
		}
	}
	
	if ($users_today_list) 
	{
		$users_today_list[strlen($users_today_list)-1] = ' '; 
	} 
	else
	{
		$users_today_list = $lang['None'];
	}
	$total_users_today = $db->sql_numrows($result)+$guests_today;
	$db->sql_freeresult($result);
		
	$users_today_list = $lang['Registered_users'] . ' ' . $users_today_list;
	$l_today_user_s = ($total_users_today) ? ( ( $total_users_today == 1 ) ? $lang['User_today_total'] : $lang['Users_today_total'] ) : $lang['Users_today_zero_total'];
	$l_today_r_user_s = ($logged_visible_today) ? ( ( $logged_visible_today == 1 ) ? $lang['Reg_user_total'] : $lang['Reg_users_total'] ) : $lang['Reg_users_zero_total'];
	$l_today_h_user_s = ($logged_hidden_today) ? (($logged_hidden_today == 1) ? $lang['Hidden_user_total'] : $lang['Hidden_users_total'] ) : $lang['Hidden_users_zero_total'];
	$l_today_g_user_s = ($guests_today) ? (($guests_today == 1) ? $lang['Guest_user_total'] : $lang['Guest_users_total']) : $lang['Guest_users_zero_total'];
	$l_today_users = sprintf($l_today_user_s, $total_users_today);
	$l_today_users .= sprintf($l_today_r_user_s, $logged_visible_today); 	
	$l_today_users .= sprintf($l_today_h_user_s, $logged_hidden_today); 
	$l_today_users .= sprintf($l_today_g_user_s, $guests_today);
		
	if ( $total_users_today > $board_config['record_day_users'])  
	{  
		$board_config['record_day_users'] = $total_users_today;  
		$board_config['record_day_date'] = time();   
	
		$sql = "UPDATE " . CONFIG_TABLE . "  
			SET config_value = '$total_users_today'  
			WHERE config_name = 'record_day_users'";  
		if ( !$db->sql_query($sql) )  
		{  
			message_die(GENERAL_ERROR, 'Could not update today user record (number of users)', '', __LINE__, __FILE__, $sql);  
		}   
		
		$sql = "UPDATE " . CONFIG_TABLE . "  
			SET config_value = '" . $board_config['record_day_date'] . "'  
			WHERE config_name = 'record_day_date'";  
		if ( !$db->sql_query($sql) )  
		{  
			message_die(GENERAL_ERROR, 'Could not update today user record (date)', '', __LINE__, __FILE__, $sql);  
		}  
		
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	} 
}	


//
// Birthday, Show users with birthday 
//
if (!empty($display_calendar))
{
	$cache_data_file = $phpbb_root_path . 'cache/birthday_' . $board_config['board_timezone'] . '.dat';
	if (@is_file($cache_data_file) && empty($SID))
	{
		$valid = (date('YzH', time()) - date('YzH', @filemtime($cache_data_file)) < 1) ? true : false;
	}
	else
	{
		$valid = false;
	}
	
	if (!empty($valid))
	{
		include($cache_data_file);
		$birthday_today_list = stripslashes($birthday_today_list);
		$birthday_week_list = stripslashes($birthday_week_list);
	}
	else
	{ 	
		$sql = ( $board_config['birthday_check_day'] ) ? "SELECT user_id, username, user_birthday, user_level FROM " . USERS_TABLE . " WHERE user_birthday != 999999 ORDER BY username" : '';
		if($result = $db->sql_query($sql)) 
		{ 
			if (!empty($result)) 
			{ 
				$this_year = create_date('Y', time(), $board_config['board_timezone']);
				$date_today = create_date('Ymd', time(), $board_config['board_timezone']);
				$date_forward = create_date('Ymd', time() + ($board_config['birthday_check_day']*86400), $board_config['board_timezone']);
			    while ($birthdayrow = $db->sql_fetchrow($result))
				{ 
					$user_birthday2 = $this_year . ($user_birthday = realdate('md', $birthdayrow['user_birthday'])); 
		      		if ( $user_birthday2 < $date_today ) 
		      		{
		      			$user_birthday2 += 10000;
					}
					if ( $user_birthday2 > $date_today  && $user_birthday2 <= $date_forward ) 
					{ 
						// user are having birthday within the next days
						$user_age = ( $this_year . $user_birthday < $date_today ) ? $this_year - realdate ('Y', $birthdayrow['user_birthday']) + 1 : $this_year - realdate ('Y', $birthdayrow['user_birthday']); 
							
						$birthdayrow['username'] = username_level_color($birthdayrow['username'], $birthdayrow['user_level'], $birthdayrow['user_id']);
		
						$birthday_week_list .= ' <a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $birthdayrow['user_id']) . '" class="gensmall">' . $birthdayrow['username'] . ' (' . $user_age . ')</a>,'; 
					} 
					else if ( $user_birthday2 == $date_today ) 
	      			{ 
						//user have birthday today 
						$user_age = $this_year - realdate ( 'Y', $birthdayrow['user_birthday'] ); 
		
						$birthdayrow['username'] = username_level_color($birthdayrow['username'], $birthdayrow['user_level'], $birthdayrow['user_id']);
		
						$birthday_today_list .= ' <a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $birthdayrow['user_id']) . '" class="gensmall">' . $birthdayrow['username'] . ' (' . $user_age . ')</a>,'; 
				  	} 
				}
				$db->sql_freeresult($result);

				if ($birthday_today_list) 
				{
					$birthday_today_list[strlen($birthday_today_list)-1] = ' ';
				}
				if ($birthday_week_list) 
				{
					$birthday_week_list[strlen($birthday_week_list)-1] = ' ';
				} 
			} 
		
			if (empty($SID))
    	  	{
    	    	// Stores the data set in a cache file
    	    	$data = "<?php\n";
    	    	$data .= '$birthday_today_list = \'' . addslashes($birthday_today_list) . "';\n";
    	    	$data .= '$birthday_week_list = \'' . addslashes($birthday_week_list) . "';\n?>";
    	    	$fp = @fopen($cache_data_file, 'w');
    	    	@fwrite($fp, $data);
    	    	@fclose($fp);
    	  	}
   		}
	}		
}

//
// Referring Sites
//
if (!empty($display_referrers))
{
	$template->assign_block_vars('displayreferrers', array() );
	
	$links_array = array(); 
	$log = 'cache/reflog.txt'; 
	// Read the log into an array 
	$rfile = file($log); 
	foreach ($rfile as $r) 
	{ 
		// Loop through the array 
		if (!(in_array($r, $links_array))) 
		{ 
			$links_array[] = $r; 
			$r = chop($r); 
			$split_http = array(); 
			$split_http = explode('//', $r); 
			$split_url = array(); 
			$split_url = explode('/', $split_http[1]); 
			$r = 'http://' . $split_url[0]; // remove trailing whitespace 
			if ($r <> "Direct request") 
			{ 
				$template->assign_block_vars('displayreferrers.linkrow', array( 
					'U_LINK_TEXT' => $r, 
					'LINK_TEXT' => $r) 
				); 
			} 
		} 
	} 
}


//
// Welcome & Avatar
//
$avatar_img = ''; 
if ( $userdata['user_avatar_type'] && $userdata['user_allowavatar'] ) 
{ 
	switch( $userdata['user_avatar_type'] ) 
	{ 
		case USER_AVATAR_UPLOAD: 
			$avatar_img = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $userdata['user_avatar'] . '" alt="" title="" />' : ''; 
			break; 
		case USER_AVATAR_REMOTE: 
			$avatar_img = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $userdata['user_avatar'] . '" alt="" title="" />' : ''; 
			break; 
		case USER_AVATAR_GALLERY: 
			$avatar_img = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $userdata['user_avatar'] . '" alt="" title="" />' : ''; 
			break; 
	} 
} 
if ( ( !$avatar_img ) && ( $board_config['default_avatar_set'] == 1 || $board_config['default_avatar_set'] == 2 ) && ( $board_config['default_avatar_users_url'] ) )
{
	$avatar_img = '<img src="' . $board_config['default_avatar_users_url'] . '" alt="" title="" />';
}

$total_posts_format = sprintf($l_total_post_s, $total_posts); 
$total_posts_format = str_replace($total_posts, $total_posts, $total_posts_format); 

//
// Permissions ...
//
$is_auth_ary = array(); 
$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata); 


//
// Not sure why this is necessary
// but it caused a problem for anonymous users on some boards
//
if ( $userdata['user_style'] == '' )
{
	$userdata['user_style'] = $board_config['default_style'];
}


//
// Link to iFrame page if external link
//
$sql = "SELECT portal_id, portal_use_url, portal_use_iframe, portal_url 
	FROM " . PORTAL_TABLE . " 
	WHERE portal_id = " . $page_id;
if (!$result = $db->sql_query($sql)) 
{ 
	message_die(GENERAL_ERROR, 'Could not obtain portal external url & frame information', '', __LINE__, __FILE__, $sql); 
} 
$portalurlrow = $db->sql_fetchrow($result); 
	
if ( $portalurlrow['portal_use_url'] == 1 && $portalurlrow['portal_use_iframe'] == 0 ) 
{ 
	$portal_body_fn = 'portal_site_body.tpl'; 
} 
else 
{ 
	$portal_body_fn = 'portal_body.tpl'; 
} 
$db->sql_freeresult($result);


//
// Start output of page
//
include($phpbb_root_path . 'includes/page_header.'.$phpEx);
$template->set_filenames(array( 
	'body' => $portal_body_fn) 
); 
	

//
// Display the selected module blocks
//
if ( $board_config['shoutbox_enable'] && $display_shoutbox ) 
{ 
	$template->assign_vars(array(
		'L_SHOUTBOX' => $lang['Shoutbox'],
		'SHOUTBOX_HEIGHT' => $board_config['shoutbox_height'],
			
		'U_SHOUTBOX' => append_sid('shoutbox.'.$phpEx),
		'U_SHOUTBOX_MAX' => append_sid('shoutbox_max.'.$phpEx))
	);

	$template->assign_block_vars('displayshoutbox', array()); 
}
if ( $display_donors ) { donors_block(); }
if ( $display_online ) { $template->assign_block_vars('displayonline', array()); }
if ( $display_calendar ) { $template->assign_block_vars('displaycalendar', array()); }
if ( $display_login ) { $template->assign_block_vars('displaylogin', array()); }
if ( $display_photo ) { $template->assign_block_vars('displayphoto', array()); }
if ( $display_search ) { $template->assign_block_vars('displaysearch', array()); }
if ( $display_onlinetoday ) { $template->assign_block_vars('displayonlinetoday', array()); }
if ( $display_links ) { $template->assign_block_vars('displaylinks', array()); }
if ( $display_ourlink ) { $template->assign_block_vars('displayourlink', array()); }
if ( $display_newsfader ) { include($phpbb_root_path . 'includes/functions_news.'.$phpEx); $template->assign_block_vars('displaynewsfader', array()); }
if ( $display_clock ) { $template->assign_block_vars('displayclock', array()); }
if ( $display_horoscopes ) { $template->assign_block_vars('displayhoroscopes', array()); }
if ( $display_wallpaper ) { $template->assign_block_vars('displaywallpaper', array()); }
if ( $list_limit > 0 ) { $template->assign_block_vars('displayarchive', array()); }
if ( $display_quote ) { quote_block(); }
if ( $display_navbar ) { navbar_block(); }
if ( $board_config['allow_karma'] && $display_karma ) { karma_block(); }
if ( $display_newusers ) { new_users_block(); }
if ( $display_topposters ) { top_posters_block(); }
if ( $display_mostpoints ) { most_points_block(); }
if ( $display_downloads ) { top_dloads_block(); }
if ( $display_randomuser ) { random_user_block(); }
if ( $display_games ) { random_game_block(); }
if ( $display_photo ) { random_pic_block(); }
if ( $display_moreover ) { include($phpbb_root_path . 'includes/newsfeed.'.$phpEx); $template->assign_block_vars('displaymoreover', array('MY_NEWS_CODE' => $newsfeedtext)); }
if ( $display_poll ) { $poll_block = poll_block($display_polls);
	if ( !empty($poll_block) )
	{
		$disabled = ( $userdata['session_logged_in'] ) ? '' : ' disabled="disabled"';
		
		$template->assign_block_vars('displaypoll', array(
			'S_POLL_TITLE' => $poll_block['topic_title'],
			'S_POLL_QUESTION' => $poll_block['vote_text'],
			'S_POLL_ACTION' => append_sid('posting.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $poll_block['topic_id']),
			'SUBMIT_BUTTON' => '<input type="hidden" name="topic_id" value="' . $poll_block['topic_id'] . '" /><input type="hidden" name="mode" value="vote" /><input type="submit" class="mainoption" name="submit" value="' . $lang['Submit_vote'] . '"' . $disabled . ' />')
		);
	
		for ($i = 0; $i < sizeof($poll_block['options']); $i++)
		{
			$template->assign_block_vars('poll_option_row', array(
				'OPTION_ID' => $poll_block['options'][$i]['vote_option_id'],
				'OPTION_TEXT' => $poll_block['options'][$i]['vote_option_text'],
				'VOTE_RESULT' => $poll_block['options'][$i]['vote_result'])
			);
		}	
	}
	else
	{
		$template->assign_block_vars('displaypoll', array(
			'S_POLL_TITLE' => $lang['Post_Poll'],
			'S_POLL_QUESTION' => $lang['No'] . ' ' . $lang['Post_Poll'],
			'SUBMIT_BUTTON' => '')
		);
	}
}
if ( $display_latest ) 
{ 
	$exclude_forums = '0' . (($display_latest_exclude_forums) ? ',' . $display_latest_exclude_forums : '');
	$sql = "SELECT t.*, u.username, u.user_id, u.user_level, p.* 
		FROM (( " . TOPICS_TABLE . " t 
			LEFT JOIN " . POSTS_TABLE . " p ON t.topic_last_post_id = p.post_id ) 
			LEFT JOIN " . USERS_TABLE . " u ON p.poster_id = u.user_id ) 
		WHERE t.forum_id NOT IN (" . $exclude_forums . ")
		ORDER BY p.post_time DESC 
		LIMIT 0, " . $display_latest_amt;
	if( !$result = $db->sql_query($sql) )
	{
	   message_die(GENERAL_ERROR, 'Could not create latest post module.', '', __LINE__, __FILE__, $sql);
	}

	$total_topics = 0;
	$last_rowsets = array();
	while ( $row = $db->sql_fetchrow($result) ) 
	{ 
		$is_auth = auth(AUTH_READ, $row['forum_id'], $userdata); 
		if ( $is_auth['auth_read'] ) 
		{ 
			$last_rowsets[] = $row; 
			$total_topics++; 
		}
	}
	$db->sql_freeresult($result);

	if ( $total_topics )
	{
		for($i = 0; $i < $total_topics; $i++)
		{
			$post_id = $last_rowsets[$i]['post_id'];
			$post_subject = ( sizeof($orig_word) ) ? preg_replace($orig_word, $replacement_word, $last_rowsets[$i]['topic_title']) : $last_rowsets[$i]['topic_title'];
			$last_post_time = create_date($board_config['default_dateformat'], $last_rowsets[$i]['post_time'], $board_config['board_timezone']);
			$poster_username = ( $last_rowsets[$i]['user_id'] == ANONYMOUS ) ? ( ($last_rowsets[$i]['username'] != "" ) ? $last_rowsets[$i]['username'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "="  . $last_rowsets[$i]['user_id']) . '" class="gensmall">' . username_level_color($last_rowsets[$i]['username'], $last_rowsets[$i]['user_level'], $last_rowsets[$i]['user_id']) . '</a> ';
			$view_topic_url = append_sid('viewtopic.'.$phpEx.'?' . POST_POST_URL . '=' . $post_id . '&amp;no=1#' . $post_id);

			$post_subject = capitalization($post_subject);

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('lasttopicrow', array(
				'ROW_CLASS' => $row_class,
				'POST_USERNAME' => $poster_username,
				'POST_TIME' => $last_post_time,
				'TOPIC_TITLE' => $post_subject,
				'U_VIEW_TOPIC' => $view_topic_url)
			);
		}
	}
	else
	{		
		$template->assign_block_vars('lastnotopicsrow', array(
			'L_NO_TOPICS' => $lang['No_Posts']) 
		);
	}
	
	$template->assign_block_vars('displaylatest', array(
		'SCROLL_BEGIN' => (!empty($display_latest_scrolling) && $total_topics) ?'<marquee behavior="scroll" direction="up" width="100%" height="250" scrollamount="1" onMouseover="this.scrollAmount=0" onMouseout="this.scrollAmount=1">' : '',
		'SCROLL_END' => (!empty($display_latest_scrolling) && $total_topics) ? '</marquee>' : '')
	);
}
	
//
// Tenplate variables
//
$template->assign_vars(array( 
  	'LINKS_HEIGHT' => $portal_links_height,
	'COLUMN_WIDTH' => $portal_column_width,
	'CLOCK_WIDTH' => $portal_column_width - 15,
	'FORUM_IMG' => $images['forum'],
	'FORUM_NEW_IMG' => $images['forum_new'],
	'FORUM_LOCKED_IMG' => $images['forum_locked'],
	'AVATAR_IMG' => $avatar_img,
	'USERS_TODAY_LIST' => $users_today_list,
	'LANGUAGE_SELECT' => language_select($board_config['default_lang'], 'language'), 
	'TEMPLATE_SELECT' => style_select($board_config['default_style'], 'template'),
	'FPAGE_STYLE' => style_select($fpage_style, 'fpage_theme'), 
	'PORTAL_OURLINK' => str_replace(' ', '&nbsp;', sprintf(htmlentities($lang['Link_us_syntax'], ENT_QUOTES), $link_config['site_url'], real_path($link_config['site_logo']), $link_config['width'], $link_config['height'], $board_config['sitename'], $board_config['sitename'])),
	'NEW_POST_IMG' => '<img src="' . $images['post_new'] . '" alt="' . $lang['Post_new_topic'] . '" title="' . $lang['Post_new_topic'] . '" />',
	'CAPRICORN' => $images['Capricorn'],
	'AQUARIUS' => $images['Aquarius'],
	'PISCES' => $images['Pisces'],  
	'ARIES' => $images['Aries'],
	'TAURUS' => $images['Taurus'],
	'GEMINI' => $images['Gemini'], 
	'CANCER' => $images['Cancer'],
	'LEO' => $images['Leo'],
	'VIRGO' => $images['Virgo'],
	'LIBRA' => $images['Libra'],
	'SCORPIO' => $images['Scorpio'],
	'SAGITTARIUS' => $images['Sagittarius'],
	'PORTAL_CLOCK_FORMAT' => $userdata['user_clockformat'],
 	'NEWS_TITLE' => $board_config['news_title'],
    'NEWS_COLOR' => $theme['adminfontcolor'],
    'NEWS_BLOCK' => $board_config['news_block'],
    'NEWS_STYLE' => $board_config['news_style'],
    'NEWS_BOLD' => $board_config['news_bold'],
    'NEWS_ITAL' => $board_config['news_ital'],
    'NEWS_UNDER' => $board_config['news_under'],
    'NEWS_SIZE' => $board_config['news_size'],
    'SCROLL_SPEED' => $board_config['scroll_speed'],
    'SCROLL_ACTION' => $board_config['scroll_action'],
    'SCROLL_BEHAVIOR'=> $board_config['scroll_behavior'],
    'SCROLL_SIZE' => $board_config['scroll_size'],
	'VISIT_COUNTER' => ($board_config['visit_counter_index']) ? '<br /><br />' . sprintf($lang['Visit_counter'], $visit_counter) . ' ' . create_date($board_config['default_dateformat'], $board_config['board_startdate'], $board_config['board_timezone']) : '',
	'SITE_LOGO_WIDTH' => $site_logo_width,
	'SITE_LOGO_HEIGHT' => $site_logo_height,

	'L_ONLINE_EXPLAIN' => sprintf($lang['Online_explain'], $board_config['whosonline_time']),
	'L_ONLINETODAY' => $lang['Onlinetoday'], 
	'L_USERS_LASTHOUR' => ( $users_lasthour ) ? sprintf($lang['Users_lasthour_explain'], $users_lasthour) : $lang['Users_lasthour_none_explain'],
	'L_USERS_TODAY' => $l_today_users,
	'L_WHOSBIRTHDAY_WEEK' => ($board_config['birthday_check_day'] > 1) ? sprintf( (($birthday_week_list) ? $lang['Birthday_week'] : $lang['Nobirthday_week']), $board_config['birthday_check_day']) . $birthday_week_list : '',
	'L_WHOSBIRTHDAY_TODAY' => ($board_config['birthday_check_day']) ? ($birthday_today_list) ? $lang['Birthday_today'] . $birthday_today_list : $lang['Nobirthday_today'] : '',
	'L_CLOCK' => $lang['Clock'],
	'L_LINK_TO_US' => $lang['Link_us'],
	'L_LINK_TO_US_EXPLAIN' => sprintf($lang['Link_us_explain'], $board_config['sitename']),
	'L_RANDOM_LINKS' => $lang['Random_links'],
	'L_RECENT_REFERRALS' => $lang['Recent_referrals'], 
	'L_FORUM' => $lang['Forum'],
	'L_TOPICS' => $lang['Topics'],
	'L_REPLIES' => $lang['Replies'],
	'L_VIEWS' => $lang['Views'],
	'L_POSTS' => $lang['Posts'],
	'L_LASTPOST' => $lang['Last_Post'], 
	'L_LAST_POST_BY' => $lang['Last_Post_By'], 
	'L_INFORMATION' => $lang['Information'],
	'L_NO_NEW_POSTS' => $lang['No_new_posts'],
	'L_NEW_POSTS' => $lang['New_posts'],
	'L_NO_NEW_POSTS_LOCKED' => $lang['No_new_posts_locked'], 
	'L_NEW_POSTS_LOCKED' => $lang['New_posts_locked'], 
	'L_INVITE_FRIEND' => $lang['Invite_Friend'],
	'L_LATEST_POSTS' => $lang['Latest_Posts'], 
	'L_NAVIGATE' => $lang['Navigate'], 
	'L_COMMENT' => $lang['Comment'], 
	'L_READ_MORE' => $lang['Read_more'], 
	'L_VIEWS' => $lang['Views'], 
	'L_REPLIES' => $lang['Replies'], 
	'L_MODERATOR' => $lang['Moderators'], 
	'L_FORUM_LOCKED' => $lang['Forum_is_locked'],
    'L_NAME_WELCOME' => $lang['Welcome'],
	'L_SELECT_STYLE' => $lang['Board_style'],  
	'L_ARCHIVE' => $lang['Archive'],
	'L_SEARCH_ENGINE' => $lang['Search_engine'],
	'L_SITE_SEARCH' => $lang['Site_search'],
	'L_CURRENT_NEWS' => $lang['Current_news'],
	'L_POSTED' => $lang['Posted'],	
	'L_WORLD_NEWS' => $lang['World_news'],
	'L_POSTED_BY' => $lang['Posted'] . ' ' . $lang['By'],
	'L_HOROSCOPES' => $lang['Horoscopes'],
	'L_CAPRICORN' => $lang['Capricorn'],
	'L_AQUARIUS' => $lang['Aquarius'],
	'L_PISCES' => $lang['Pisces'],  
	'L_ARIES' => $lang['Aries'],
	'L_TAURUS' => $lang['Taurus'],
	'L_GEMINI' => $lang['Gemini'], 
	'L_CANCER' => $lang['Cancer'],
	'L_LEO' => $lang['Leo'],
	'L_VIRGO' => $lang['Virgo'],
	'L_LIBRA' => $lang['Libra'],
	'L_SCORPIO' => $lang['Scorpio'],
	'L_SAGITTARIUS' => $lang['Sagittarius'],

	'U_ARCHIVE' => append_sid('viewforum.'.$phpEx.'?' . POST_FORUM_URL . '=' . $page_forum),
	'U_SITE_LOGO' => $link_self_img,
	'U_NAME_LINK' => '<a href="' . append_sid('profile.'.$phpEx.'?mode=editprofile&amp;ucp=main') . '" title="' . $lang['Profile'] . '">' . $userdata['username'] . '</a>')
);	


//
// Obtain posts for this portal page
//
$sql = "SELECT t.*, u.username, u.user_id, u.user_level, u.user_custom_post_color, p.*, x.*
	FROM " . TOPICS_TABLE . " t, " . USERS_TABLE . " u, " . POSTS_TABLE . " p, " . POSTS_TEXT_TABLE . " x 
	WHERE t.forum_id = '$page_forum'
		AND t.topic_poster = u.user_id
		AND p.post_id = t.topic_first_post_id
		AND x.post_id = t.topic_first_post_id";
$sql = ( $sort_asc ) ? $sql . " ORDER BY t.topic_time ASC" : $sql . " ORDER BY t.topic_time DESC";
$sql = ( $list_limit ) ? $sql . " LIMIT 0, $list_limit" : $sql;

if ( !$result = $db->sql_query($sql) )
{
   message_die(GENERAL_ERROR, 'Could not obtain portal posts information.', '', __LINE__, __FILE__, $sql);
}

$total_topics = 0;
$topic_rowset = array();
while ( $row = $db->sql_fetchrow($result) )
{
	$topic_rowset[] = $row;
	$total_topics++;
}
$db->sql_freeresult($result);

if ( $total_topics )
{
	for ($i = 0; $i < $total_topics; $i++)
	{
		$topic_id = $topic_rowset[$i]['topic_id'];
		$post_subject = ( sizeof($orig_word) ) ? preg_replace($orig_word, $replacement_word, $topic_rowset[$i]['topic_title']) : $topic_rowset[$i]['topic_title'];
		$replies = $topic_rowset[$i]['topic_replies'];
		$topic_time = create_date($board_config['default_dateformat'], $topic_rowset[$i]['post_time'], $board_config['board_timezone']);
		$views = $topic_rowset[$i]['topic_views'];
		$bbcode_uid = $topic_rowset[$i]['bbcode_uid'];

		$topic_poster = ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $topic_rowset[$i]['user_id']) . '" class="gensmall">' : '';
		$topic_poster .= ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? username_level_color($topic_rowset[$i]['username'], $topic_rowset[$i]['user_level'], $topic_rowset[$i]['user_id']) : ( ( $topic_rowset[$i]['post_username'] != "" ) ? $topic_rowset[$i]['post_username'] : $lang['Guest'] );
		$topic_poster .= ( $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? '</a>' : '';

		$poster_custom_post_color = ( $board_config['allow_custom_post_color'] && $topic_rowset[$i]['user_custom_post_color'] && $topic_rowset[$i]['user_id'] != ANONYMOUS ) ? $topic_rowset[$i]['user_custom_post_color'] : '';

		$message = $topic_rowset[$i]['post_text']; 
		$message_length = strlen($message);
		$view_topic_url = append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topic_id);

		//
		// Note! The order used for parsing the message _is_ important, moving things around could break any
		// output
		//

		//
		// If the board has HTML off but the post has HTML
		// on then we process it, else leave it alone
		//
		if ( !$board_config['allow_html'] || !$userdata['user_allowhtml'] )
		{
			if ( $topic_rowset[$i]['enable_html'] )
			{
				$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
			}
		}

		//
		// Parse message and/or sig for BBCode if reqd
		//
		if( $bbcode_uid != '' )
		{
			$message = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace("/\:$bbcode_uid/si", '', $message);
		}

		$message = make_clickable($message);

	 	//
	 	// ed2k link and add all
		//
		$message = make_addalled2k_link($message, $topic_rowset[$i]['post_id']);

		//
		// Parse smilies
		//
		if( $board_config['allow_smilies'] )
		{
			if( $topic_rowset[$i]['enable_smilies'] )
			{
				$message = smilies_pass($message);
			}
		}

		//
		// Replace naughty words
		//
		if( sizeof($orig_word) )
		{
			$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);
			$message = preg_replace($orig_word, $replacement_word, $message);
		}


		//
		// Limit character limit of post
		//
		if ($char_limit && ($message_length > $char_limit))
		{
			$message = substr($message, 0, $char_limit) . '... <a href="' . $view_topic_url . '">' . $lang['More'] . '</a>';
		}
	
		//
		// Replace newlines (we use this rather than nl2br because
		// till recently it wasn't XHTML compliant)
		//
		$message = str_replace("\n", "\n<br />\n", $message);
		$message = word_wrap_pass($message);
	
		$post_subject = capitalization($post_subject);
	
		//
		// Again this will be handled by the templating
		// code at some point
		//
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
		$template->assign_block_vars('topicrow', array(
			'ROW_CLASS' => $row_class,
			'FORUM_ID' => $forum_id,
			'TOPIC_ID' => $topic_id,
			'TOPIC_POSTER' => $topic_poster,
			'TOPIC_TIME' => ($no_date) ? '' : ' ' . $lang['On'] . ' ' . $topic_time,
			'REPLIES' => $replies,
			'TOPIC_TITLE' => $post_subject,
			'VIEWS' => $views,
			'POST_TEXT' => $message,
			'CUSTOM_POST_COLOR' => $poster_custom_post_color,

			'U_POST_COMMENT' => append_sid('posting.'.$phpEx.'?mode=reply&amp;' . POST_TOPIC_URL . '=' . $topic_id),
			'U_VIEW_TOPIC' => $view_topic_url)
		);
	}
}
else
{
	$no_topics_msg = ($forum_row['forum_status'] == FORUM_LOCKED) ? $lang['Forum_locked'] : $lang['No_topics_post_one'];
	
	$template->assign_vars(array(
		'NEW_POST' => append_sid('posting.'.$phpEx.'?mode=newtopic&amp;' . POST_FORUM_URL . '=' . $page_forum),
		'L_NO_TOPICS' => $no_topics_msg)
	);

	$template->assign_block_vars('notopicsrow', array() );
}


//
// Generate the page
//
if ( $display_calendar ) { include($phpbb_root_path . 'mods/calendar/mini_cal.'.$phpEx); }

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
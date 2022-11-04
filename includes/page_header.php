<?php
/** 
*
* @package includes
* @version $Id: page_header.php,v 1.106.2.23 2004/07/11 16:46:19 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

define('HEADER_INC', TRUE);
include_once(PRILL_PATH . 'prill_common.'.$phpEx);

if ($board_config['enable_http_referrers'])
{
	include($phpbb_root_path . 'includes/referers.'.$phpEx);
}

//
// gzip_compression
//
$do_gzip_compress = FALSE;
if ( $board_config['gzip_compress'] )
{
	$phpver = phpversion();

	$useragent = (isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) ? $HTTP_SERVER_VARS['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');

	if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
	{
		if ( extension_loaded('zlib') )
		{
			if (headers_sent() != TRUE) 
			{ 
				//
				// Here we updated the gzip function.
				// With this method we can get the server up
				// to 10% faster
				//
				$gz_possible = isset($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING']) && eregi('gzip, deflate', $HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING']); 
				if ($gz_possible) 
				{
					ob_start('ob_gzhandler'); 
				}
			}
		}
	}
	else if ( $phpver > '4.0' )
	{
		if ( strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip') )
		{
			if ( extension_loaded('zlib') )
			{
				$do_gzip_compress = TRUE;
				ob_start();
				ob_implicit_flush(0);

				header('Content-Encoding: gzip');
			}
		}
	}
}

//
//
// Enhanced IP logger
//
if ($board_config['enable_ip_logger'])
{	
	$REMOTE_ADDR = (isset($_SERVER['REMOTE_ADDR']) ) ? $_SERVER['REMOTE_ADDR'] : $REMOTE_ADDR; 
	$HTTP_REFERER = (isset($_SERVER['HTTP_REFERER']) ) ? $_SERVER['HTTP_REFERER'] : $HTTP_REFERER; 
	$PHP_SELF = (isset($_SERVER['PHP_SELF']) ) ? $_SERVER['PHP_SELF'] : $PHP_SELF; 
	$HTTP_USER_AGENT = (isset($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : $HTTP_USER_AGENT; 
		
	$host = @gethostbyaddr("$REMOTE_ADDR");
	
	$sql = "INSERT INTO " . IP_TABLE . " (host, ip, date, username, referrer, forum, browser) 
		VALUES ('$host', '$REMOTE_ADDR', " . time() . ", '" . phpbb_clean_username($userdata['username']) . "', '" . phpbb_clean_username($HTTP_REFERER) . "', '$PHP_SELF', '$HTTP_USER_AGENT')";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not insert ip logdata.', '', __LINE__, __FILE__, $sql);
	}
}
	

//
// Visitor Counter 
//
if ($board_config['visit_counter_index'])
{
	$visit_counter = $board_config['visit_counter'];

	if( $userdata['session_start'] >= ( time() - 1 ) )
	{
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . ($visit_counter + 1) . "'
			WHERE config_name = 'visit_counter'";
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not update counter information', '', __LINE__, __FILE__, $sql);
		}
		
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
		
		$visit_counter++;
		$db->sql_freeresult($result);
	}
}

//
// Parse date format to get time format
//
$time_reg = '([gh][[:punct:][:space:]]{1,2}[i][[:punct:][:space:]]{0,2}[a]?[[:punct:][:space:]]{0,2}[S]?)';
eregi($time_reg, $board_config['default_dateformat'], $regs);
$board_config['default_timeformat'] = $regs[1];
unset($time_reg);
unset($regs);

//
// Get time, today & yesterday
//
$today_ary = explode('|', create_date('m|d|Y', time(), $board_config['board_timezone']));
$board_config['time_today'] = gmmktime(0 - $board_config['board_timezone'] - $board_config['dstime'], 0, 0, $today_ary[0], $today_ary[1], $today_ary[2]);
$board_config['time_yesterday'] = $board_config['time_today'] - 86400;
unset($today_ary);

//
// RSS Autodiscovery 
//
$rss_forum_id = ( isset($HTTP_GET_VARS[POST_FORUM_URL]))? intval($HTTP_GET_VARS[POST_FORUM_URL]): 0; 
$rss_url = real_path('rss.'.$phpEx);
if ( $rss_forum_id != 0 )
{ 
	$rss_link = '<link rel="alternate" type="application/rss+xml" title="' . $board_config['sitename'] . ' RSS" href="' . $rss_url . '?' . POST_FORUM_URL . '=' . $rss_forum_id . '" />'."\n"; 
    $rss_link .= '<link rel="alternate" type="application/atom+xml" title="' . $board_config['sitename'] . ' Atom" href="' . $rss_url . '?atom&' . POST_FORUM_URL . '=' . $rss_forum_id . '" />'."\n"; 
} 
else 
{ 
    $rss_link = '<link rel="alternate" type="application/rss+xml" title="' . $board_config['sitename'] . ' RSS" href="' . $rss_url . '" />'."\n"; 
    $rss_link .= '<link rel="alternate" type="application/atom+xml" title="' . $board_config['sitename'] . ' Atom" href="' . $rss_url . '?atom'.'" />'."\n"; 
} 


//
// Parse and show the overall header.
// If an ImageSet Child Theme check if it has custom header 
//
$theme_custom_header = ( $theme['theme_header'] ) ? 'images/' . $theme['image_cfg'] . '/' : '';
$template->set_filenames(array(
	'overall_header' => ( empty($gen_simple_header) ) ? $theme_custom_header . 'overall_header.tpl' : (($gen_simple_header < 0) ? 'slideshow_header.tpl' : 'simple_header.tpl') )
);


//
// Get basic (usernames + totals) online
// situation
//
$online_userlist = $l_online_users = '';
$logged_visible_online = $logged_hidden_online = $guests_online = 0;

if (defined('SHOW_ONLINE'))
{
	$user_forum_sql = ( !empty($topic_id) ) ? "AND s.session_topic = $topic_id" : ( (!empty($forum_id) ) ? "AND s.session_page = $forum_id" : '');
	$sql = "SELECT u.username, u.user_id, u.user_allow_viewonline, u.user_level, u.user_session_page, s.session_logged_in, s.session_ip, s.is_robot
		FROM ".USERS_TABLE." u, ".SESSIONS_TABLE." s
		WHERE u.user_id = s.session_user_id
			AND s.session_time >= " . ( time() - ($board_config['whosonline_time'] * 60) ) . "
			$user_forum_sql
		ORDER BY s.session_logged_in DESC, u.username, s.session_ip";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user/online information', '', __LINE__, __FILE__, $sql);
	}

	$userlist_ary = $userlist_visible = array();

	$prev_user_id = 0;
	$prev_user_ip = $prev_session_ip = $prev_is_robot = '';

	while( $row = $db->sql_fetchrow($result) )
	{
		// User is logged in and therefor not a guest
		if ( $row['session_logged_in'] )
		{
			// Skip multiple sessions for one user
			if ( $row['user_id'] != $prev_user_id )
			{
				$online_array[] = $row['user_id'];
			
				$username = username_level_color($row['username'], $row['user_level'], $row['user_id']);

				// Game Players
				if ($board_config['ina_players_index'])
				{
					if ( $row['user_session_page'] == PAGE_ACTIVITY || $row['user_session_page'] == PAGE_PLAYING_GAMES )
					{
						$username = '<b style="color: #' . $theme['playersfontcolor'] . '">' . $row['username'] . '</b>';
					}
				}
				
				// Invisible
				if ( $row['user_allow_viewonline'] )
				{
					$user_online_link = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" class="gensmall" target="_top">' . $username . '</a>';
					$logged_visible_online++;
				}
				else
				{
					$user_online_link = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" class="gensmall" target="_top"><i>' . $username . '</i></a>';
					$logged_hidden_online++;
				}

				if ( $row['user_allow_viewonline'] || $userdata['user_level'] == ADMIN || $userdata['user_id'] == $row['user_id'] )
				{
					$online_userlist .= ( $online_userlist != '' ) ? ', ' . $user_online_link : $user_online_link;
				}
			}

			$prev_user_id = $row['user_id'];
		}
		else
		{
			// Skip multiple sessions for one user
			if ( $row['session_ip'] != $prev_session_ip )
			{
				if ($row['is_robot'] != '0' && $row['is_robot'] != $prev_is_robot && $board_config['enable_bots_whosonline'])
				{
					if ( strpos($online_userlist, $row['is_robot']) == FALSE )
					{
   						$row['is_robot'] = '<b style="color: #' . $theme['botfontcolor'] . '">' . $row['is_robot'] . '</b>';
   						$online_userlist .= ( $online_userlist != '' ) ? ', ' . $row['is_robot'] : $row['is_robot'];
					}	
				}
				
				$guests_online++;
			}
		}

    	$prev_is_robot = $row['is_robot'];
		$prev_session_ip = $row['session_ip'];
	}
	$db->sql_freeresult($result);

	if ( empty($online_userlist) )
	{
		$online_userlist = $lang['None'];
	}
	$online_userlist = $lang['Registered_users'] . ' ' . $online_userlist;

	$total_online_users = $logged_visible_online + $logged_hidden_online + $guests_online;

	if ($total_online_users > $board_config['record_online_users'])
	{
		$board_config['record_online_users'] = $total_online_users;
		$board_config['record_online_date'] = time();

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '$total_online_users'
			WHERE config_name = 'record_online_users'";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update online user record (nr of users)', '', __LINE__, __FILE__, $sql);
		}

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = '" . $board_config['record_online_date'] . "'
			WHERE config_name = 'record_online_date'";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update online user record (date)', '', __LINE__, __FILE__, $sql);
		}
		
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
	}

	if ( $total_online_users == 0 )
	{
		$l_t_user_s = ( ( isset($topic_id) ) ? $lang['Browsing_topic'] : ( ( isset($forum_id) ) ? $lang['Browsing_forum'] : $lang['Online_users_zero_total'] ) );
	}
	else if ( $total_online_users == 1 )
	{
		$l_t_user_s = ( ( isset($topic_id) ) ? $lang['Browsing_topic'] : ( ( isset($forum_id) ) ? $lang['Browsing_forum'] : $lang['Online_user_total'] ) );
	}
	else
	{
		$l_t_user_s = ( ( isset($topic_id) ) ? $lang['Browsing_topic'] : ( ( isset($forum_id) ) ? $lang['Browsing_forum'] : $lang['Online_users_total'] ) );
	}
	
	if ( $logged_visible_online == 0 )
	{
		$l_r_user_s = $lang['Reg_users_zero_total'];
	}
	else if ( $logged_visible_online == 1 )
	{
		$l_r_user_s = $lang['Reg_user_total'];
	}
	else
	{
		$l_r_user_s = $lang['Reg_users_total'];
	}

	if ( $logged_hidden_online == 0 )
	{
		$l_h_user_s = $lang['Hidden_users_zero_total'];
	}
	else if ( $logged_hidden_online == 1 )
	{
		$l_h_user_s = $lang['Hidden_user_total'];
	}
	else
	{
		$l_h_user_s = $lang['Hidden_users_total'];
	}

	if ( $guests_online == 0 )
	{
		$l_g_user_s = $lang['Guest_users_zero_total'];
	}
	else if ( $guests_online == 1 )
	{
		$l_g_user_s = $lang['Guest_user_total'];
	}
	else
	{
		$l_g_user_s = $lang['Guest_users_total'];
	}

	$l_online_users = sprintf($l_t_user_s, $total_online_users);
	$l_online_users .= sprintf($l_r_user_s, $logged_visible_online);
	$l_online_users .= sprintf($l_h_user_s, $logged_hidden_online);
	$l_online_users .= sprintf($l_g_user_s, $guests_online);
}


//
// If user is logged in  ...
// admin link, login status, assign points, number of new private messages, 
// birthday greeting, profile views
//
if ( ($userdata['session_logged_in']) && (empty($gen_simple_header)) )
{
	// Generate logged in/logged out status
	$u_login_logout = 'login.'.$phpEx.'?logout=true&amp;sid=' . $userdata['session_id'];
	$l_login_logout = $lang['Logout'] . ' [ ' . $userdata['username'] . ' ]';

	// Add points to user for browsing (if enabled)
	if ($board_config['points_browse'] && !$post_info['points_disabled'] )
	{
		$points = $board_config['points_browse'];

		if (($userdata['user_id'] != ANONYMOUS) && ($userdata['admin_allow_points']))
		{
			add_points($userdata['user_id'], $points);
		}
	}

	// Points & Bank header links (if enabled)
	if ( $board_config['points_post'] || $board_config['points_browse'] )
	{	
		$header_points = sprintf($lang['You_points'], $board_config['points_name'], $userdata['user_points']); 
		
		if ( $board_config['bankopened'] )
		{
			$template->assign_block_vars('switch_bank_on', array(
				'L_BANK' => $board_config['bankname'],
				'U_BANK' => append_sid('bank.'.$phpEx))
			);
		}
	}

	// Last visit date
	$s_last_visit = create_date($board_config['default_dateformat'], $userdata['user_lastvisit'], $board_config['board_timezone']);
	
	// Visible/Invisible Link
	if ( $board_config['allow_invisible_link'] )
	{
		$icon_switch_online = ( !$userdata['user_allow_viewonline'] ) ? $images['icon_mini_visible'] : $images['icon_mini_invisible'];
		$l_switch_view_online = ( !$userdata['user_allow_viewonline'] ) ? $lang['Be_visible'] : $lang['Be_invisible'];
		
		$template->assign_block_vars('switch_invisible_on', array(
		  	'ONLINE_SWITCH_IMG' => $icon_switch_online,
 
  			'L_SWITCH_VIEWONLINE' => $l_switch_view_online,
   			'U_SWITCH_ONLINE_STATUS' => append_sid('profile.'.$phpEx.'?mode=switch_status'))
		);

	}
	
	// See if user has or have had birthday, also see if greeting are enabled 
	if ( $userdata['user_birthday'] != 999999 && $board_config['birthday_greeting'] && create_date('Ymd', time(), $board_config['board_timezone']) >= $userdata['user_next_birthday_greeting'] . realdate('md', $userdata['user_birthday']) ) 
	{ 
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_next_birthday_greeting = " . (create_date('Y', time(), $board_config['board_timezone']) + 1) . " 
			WHERE user_id = " . $userdata['user_id']; 
		if ( !$db->sql_query($sql) )
		{ 
			message_die(GENERAL_ERROR, 'Could not update next_birthday_greeting for user.', '', __LINE__, __FILE__, $sql); 
		} 

		$template->assign_var("GREETING_POPUP",
			"<script language=\"Javascript\" type=\"text/javascript\"><!--
			window.open('" . append_sid('birthday_popup.'.$phpEx) . "', '_phpbbprivmsg', 'HEIGHT=225, resizable=yes, WIDTH=400');
			//-->
			</script>");
	} 
	
	// See if user has had profile views, also see if function is enabled 
	if ( $userdata['user_profile_view'] && $userdata['user_profile_view_popup'] )
	{
		$template->assign_var("PROFILE_VIEW",
			"<script language=\"Javascript\" type=\"text/javascript\"><!--
			window.open('" . append_sid('profile_view_popup.'.$phpEx) . "', '_phpbbproview', 'HEIGHT=250, resizable=yes, WIDTH=800');
			//-->
			</script>");
	}

	// Private messages
	if ( $userdata['user_new_privmsg'] && $userdata['user_sound_pm'] == 0 )
	{
	    $l_message_new = ( $userdata['user_new_privmsg'] == 1 ) ? $lang['New_nsnd_pm'] : $lang['New_nsnd_pms']; 
	    $l_privmsgs_text = sprintf($l_message_new, $userdata['user_new_privmsg']); 
		
		$s_privmsg_new = ( $userdata['user_popup_pm'] == 1 ) ? 1 : 0;
		$icon_pm = $images['topic_pm_new_msg'];
	} 
	else if ( $userdata['user_new_privmsg'] && $userdata['user_sound_pm'] == 1 ) 
	{ 
	    $l_message_new = ( $userdata['user_new_privmsg'] == 1 ) ? $lang['New_pm'] : $lang['New_pms']; 
	    $l_privmsgs_text = sprintf($l_message_new, $userdata['user_new_privmsg']); 
		$icon_pm = $images['topic_pm_new_msg'];

		if ( $userdata['user_last_privmsg'] > $userdata['user_lastvisit'] )
		{
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_last_privmsg = " . $userdata['user_lastvisit'] . "
				WHERE user_id = " . $userdata['user_id'];
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update private message new/read time for user', '', __LINE__, __FILE__, $sql);
			}

			$s_privmsg_new = 1;
		}
		else
		{
			$s_privmsg_new = 0;
		}
	}
	else
	{
		$l_privmsgs_text = $lang['No_new_pm'];
		$s_privmsg_new = 0;
		$icon_pm = $images['topic_pm'];
	}

	if ( $userdata['user_unread_privmsg'] )
	{
		$l_message_unread = ( $userdata['user_unread_privmsg'] == 1 ) ? $lang['Unread_pm'] : $lang['Unread_pms'];
		$l_privmsgs_text_unread = sprintf($l_message_unread, $userdata['user_unread_privmsg']);
	}
	else
	{
		$l_privmsgs_text_unread = $lang['No_unread_pm'];
	}
	
	// Prillian
	if( defined('IN_CONTACT_LIST') && defined('SHOW_ONLINE') )
	{
		$contact_list->alert_check();
	}

	if ( empty($im_userdata) )
	{
		$im_userdata = init_imprefs($userdata['user_id'], false, true);
	}
	
	$im_auto_popup = auto_prill_check();
	if ( $im_userdata['new_ims'] )
	{
		$l_prillian_msg = ( $im_userdata['new_ims'] > 1 ) ? $lang['New_ims']: $lang['New_im'];
		$l_prillian_text = sprintf($l_prillian_msg, $im_userdata['new_ims']);
	}
	elseif ( $im_userdata['unread_ims'] )
	{
		$l_prillian_msg = ( $im_userdata['unread_ims'] > 1 ) ? $lang['Unread_ims']: $lang['Unread_im'];
		$l_prillian_text = sprintf($l_prillian_msg, $im_userdata['unread_ims']);
	}

	// Post count since last visit
	user_new_post_count($userdata['user_id'], $userdata['user_lastvisit']);
}
else
{
	// Generate logged in/logged out status
	$u_login_logout = 'login.'.$phpEx;
	$l_login_logout = $lang['Login'];

	// Private messages
	$icon_pm = $images['topic_pm'];
	$l_privmsgs_text = $lang['Login_check_pm'];
	$l_privmsgs_text_unread = '';
	$s_privmsg_new = 0;
}


// Jobs
$time_now = time();
if ($board_config['jobs_status'])
{
	if ( ($userdata['user_jobs'] + 7200) < time() && $userdata['session_logged_in'])
	{
		if ( $board_config['jobs_pay_type'] )
		{
			$sql = "SELECT *
				FROM " . EMPLOYED_TABLE . "
				WHERE (" . $time_now . " - last_paid) > job_length";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employed' . mysql_error()), '', __LINE__, __FILE__, $sql);
			}
			$sql_count = $db->sql_numrows($result);

			if ($sql_count > 0)
			{
				for ($i = 0; $i < $sql_count; $i++)
				{
					if (!( $row = $db->sql_fetchrow($result) ))
					{
						message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs/employed'), '', __LINE__, __FILE__, $sql);
					}

					$sql = "UPDATE " . EMPLOYED_TABLE . "
						SET last_paid = " . $time_now . "
						WHERE id = " . $row['id'];
					if ( !($db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'employed'), '', __LINE__, __FILE__, $sql);
					}

					$sql = "UPDATE " . USERS_TABLE . "
						SET user_points = (user_points + " . $row['job_pay'] . ")
						WHERE user_id = " . $row['user_id'];
					if ( !($db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
					}
				}
			}
		}
		else
		{
			$sql = "SELECT *
				FROM " . EMPLOYED_TABLE . "
				WHERE (" . $time_now . " - last_paid) > job_length
					AND user_id = " . $userdata['user_id'];
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'employed'), '', __LINE__, __FILE__, $sql);
			}
			$sql_count = $db->sql_numrows($result);
	
			if ($sql_count > 0)
			{
				for ($i = 0; $i < $sql_count; $i++)
				{
					if (!( $row = $db->sql_fetchrow($result) ))
					{
						message_die(GENERAL_ERROR, sprintf($lang['jobs_error_selecting'], 'jobs/employed'), '', __LINE__, __FILE__, $sql);
					}
	
					$sql = "UPDATE " . EMPLOYED_TABLE . "
						SET last_paid = " . $time_now. "
						WHERE id = " . $row['id'];
					if ( !($db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'employed'), '', __LINE__, __FILE__, $sql);
					}
	
					$sql = "UPDATE " . USERS_TABLE . "
						SET user_points = (user_points + " . $row['job_pay'] . ")
						WHERE user_id = " . $row['user_id'];
					if ( !($db->sql_query($sql)) )
					{
						message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
					}
				}
			}
		}
	
		// Update user job checks (allow only once every 2 hours)
		$sql = "UPDATE " . USERS_TABLE . "
			SET user_jobs = " . $time_now . "
			WHERE user_id = " . $userdata['user_id'];
		if ( !($db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['jobs_error_updating'], 'users'), '', __LINE__, __FILE__, $sql);
		}
	}
		
	$template->assign_block_vars('switch_jobs_on', array(
		'L_JOBS' => $lang['jobs'],
		'U_JOBS' => append_sid('jobs.'.$phpEx))
	);
}


//
// Generate HTML required for Mozilla Navigation bar
//
if (!isset($nav_links))
{
	$nav_links = array();
}

$nav_links_html = '';
$nav_link_proto = '<link rel="%s" href="%s" title="%s" />' . "\n";
while( list($nav_item, $nav_array) = @each($nav_links) )
{
	if ( !empty($nav_array['url']) )
	{
		$nav_links_html .= sprintf($nav_link_proto, $nav_item, append_sid($nav_array['url']), $nav_array['title']);
	}
	else
	{
		// We have a nested array, used for items like <link rel='chapter'> that can occur more than once.
		while( list(,$nested_array) = each($nav_array) )
		{
			$nav_links_html .= sprintf($nav_link_proto, $nav_item, $nested_array['url'], $nested_array['title']);
		}
	}
}
$nav_links_html .= $rss_link; // RSS Autodiscovery 


//
// Banners
//
$hour_now = create_date('Hi', $time_now, $board_config['board_timezone']);
$date_now = create_date('Ymd', $time_now, $board_config['board_timezone']);
$week_now = create_date('w', $time_now, $board_config['board_timezone']);

$sql_level = ( $userdata['user_id'] == ANONYMOUS ) ? ANONYMOUS : (( $userdata['user_level'] == ADMIN ) ? MOD : (( $userdata['user_level'] == MOD ) ? ADMIN : $userdata['user_level'])); 
$sql = "SELECT DISTINCT banner_id, banner_name, banner_spot, banner_description, banner_forum, banner_type, banner_width, banner_height, banner_filter 
	FROM " . BANNERS_TABLE . "
	WHERE banner_active
		AND IF(banner_level_type, IF(banner_level_type = 1, " . intval($sql_level) . " <= banner_level, IF(banner_level_type = 2, " . intval($sql_level) . " >= banner_level, " . intval($sql_level) . " <> banner_level)), banner_level = " . intval($sql_level) . ")
		AND (banner_timetype = 0 
			OR (( $hour_now BETWEEN time_begin AND time_end) AND ((banner_timetype = 2
			OR (( $week_now BETWEEN date_begin AND date_end) AND banner_timetype = 4)
			OR (( $date_now BETWEEN date_begin AND date_end) AND banner_timetype = 6))))) 
	ORDER BY banner_spot, banner_weigth * SUBSTRING(RAND(),6,2) DESC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not get banner data.', '', __LINE__, __FILE__, $sql);
} 

$banners = array();
$i = 0;
while ($banners[$i] = $db->sql_fetchrow($result))
{
	$cookie_name = $board_config['cookie_name'] . '_b_' . $banners[$i]['banner_id'];
	if ( !($HTTP_COOKIE_VARS[$cookie_name] && $banners[$i]['banner_filter']) )
	{
		$banner_spot = $banners[$i]['banner_spot'];
		if ($banner_spot <> $last_spot AND ($banners[$i]['banner_forum'] == $forum_id || empty($banners[$i]['banner_forum'])))
		{
			$banner_size = '';
			$banner_size = ( $banners[$i]['banner_width'] <> '' ) ? ' width="' . $banners[$i]['banner_width'] . '"' : '';
			$banner_size .= ( $banners[$i]['banner_height'] <> '' ) ? ' height="' . $banners[$i]['banner_height'] . '"' : '';
			switch ($banners[$i]['banner_type'])
			{
				case 6:
					// swf file
					$template->assign_vars(array('BANNER_' . $banner_spot . '_IMG' => '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" id="macromedia' . $i . '" ' . $banner_size . ' align="abscenter"><param name="allowScriptAccess" value="sameDomain" /><param name=movie value="' . $banners[$i]['banner_name'] . '?clickTAG=' . append_sid('redirect.'.$phpEx.'?banner_id=' . $banners[$i]['banner_id']) . '"><param name=quality value=high><embed src="' . $banners[$i]['banner_name'] . '?clickTAG=' . append_sid('redirect.'.$phpEx.'?banner_id=' . $banners[$i]['banner_id']) . '" quality=high name="macromedia' . $i . '" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" autostart="true" ' . $banner_size . ' /><noembed><a href="' . append_sid('redirect.'.$phpEx.'?banner_id=' . $banners[$i]['banner_id']) . '" target="_blank">' . $banners[$i]['banner_description'] . '</a></noembed></object>')); 
					break;
				case 4:
					// custom code
					$template->assign_var('BANNER_' . $banner_spot . '_IMG', $banners[$i]['banner_name']);
					$template->assign_var('BANNER_' . $banner_spot . '_IMG', '<br />' . $banners[$i]['banner_name'] . '<br />');
					break;
				case 2:
					// Text link
					$template->assign_var('BANNER_' . $banner_spot . '_IMG', '<a href="' . append_sid('redirect.'.$phpEx.'?banner_id=' . $banners[$i]['banner_id']) . '" target="_blank" alt="' . $banners[$i]['banner_description'].'" title="' . $banners[$i]['banner_description'] . '">'.$banners[$i]['banner_name'] . '</a>');
					break;
				case 0:
				default: 
					$template->assign_var('BANNER_' . $banner_spot . '_IMG', '<a href="' . append_sid('redirect.'.$phpEx.'?banner_id=' . $banners[$i]['banner_id']) . '" target="_blank"><img src="' . $banners[$i]['banner_name'] . '" ' . $banner_size . ' alt="' . $banners[$i]['banner_description'] . '" title="' . $banners[$i]['banner_description'] . '" /></a>');
					break;
			}
			$banner_show_list.= ', ' . $banners[$i]['banner_id'];
		}
		$last_spot = ($banners[$i]['banner_forum'] == $forum_id || empty($banners[$i]['banner_forum'])) ? $banner_spot : $last_spot;
	}
	$i++;
}
$db->sql_freeresult($result);


//
// Points
//
if ( $board_config['points_post'] || $board_config['points_browse'] )
{
	// Lottery
	if ( $board_config['lottery_status'] )
	{
		$template->assign_block_vars('switch_lottery_on', array(
			'L_LOTTERY' => $board_config['lottery_name'],
			'U_LOTTERY' => append_sid('lottery.'.$phpEx))
		);
	}
}

//
// PJIRC chat
//
if ( $pjirc_config['irc_status'] )
{
	if ( !$pjirc_config['irc_popup_onoff'] ) 
	{ 
		$chat_link = append_sid('chatroom.'.$phpEx); 
	} 
	else
	{ 
		$chat_link = append_sid('chatroom.'.$phpEx) . '" onclick="window.open(\'' . append_sid('chatroom.'.$phpEx) . '\', \'_phpbbpjircpop\', \'width=700\', \'height=500\', \'resizable=yes\', \'scrollbars=no\'); return false;" onLoad="document.pjirc.focus();"'; 
	}
	
 	$template->assign_block_vars('switch_chatroom_on', array(
		'L_CHATLINK' => $lang['Chat_Room'],
 		'U_CHATLINK' => $chat_link)
 	); 
}

// eBay Auctions
if ( $board_config['auction_enable'] ) 
{ 
	$template->assign_block_vars('switch_auctions_on', array(
		'L_AUCTIONS' => $lang['Auctions'],
		'U_AUCTIONS' => append_sid('auctions.'.$phpEx))
	); 
}

// Avatar Suite
if ( $board_config['avatar_toplist'] ) 
{ 
	$template->assign_block_vars('switch_avatartoplist_on', array(
		'L_AVATAR_TOPLIST' => $lang['L_AVATARTOPLIST'],
		'U_AVATAR_TOPLIST' => append_sid('avatarsuite_toplist.'.$phpEx))
	); 
}

// Search
if ($board_config['search_enable'])
{	
	$template->assign_block_vars('switch_search_on', array(
		'L_SEARCH' => $lang['Search'],
		'U_SEARCH' => append_sid('search.'.$phpEx))
	); 
}


// 
// Header Navigation Links
//
$sql = "SELECT *
	FROM " . CONFIG_NAV_TABLE . "
	WHERE value = 1
	ORDER BY nav_order";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query navigation links', '', __LINE__, __FILE__, $sql);
}

while( $menurow = $db->sql_fetchrow($result) )
{
   	if ($menurow['url'] == "#")
   	{
   		$extra = ' onClick="javascript:openRadioPopup();"';
   	}
   	else 
   	{
   		$extra = '';
   	}

	// Make a language string ...
	$lang_array = split('\.', $menurow['url']);
	$use_lang = $lang['' . ucwords(strtolower($lang_array[0])) . ''];

	$template->assign_block_vars('switch_menu', array(
		'U_URL' => append_sid($menurow['url']),
		'IMAGE' => $menurow['img'],
		'EXTRA' => $extra,
		'L_LINK' => ( $menurow['use_lang'] ) ? $use_lang : $menurow['alt'])
	);	
}
$db->sql_freeresult($result);

// Format Timezone. We are unable to use array_pop here, because of PHP3 compatibility
$l_timezone = explode('.', $board_config['board_timezone']);
$l_timezone = (sizeof($l_timezone) > 1 && $l_timezone[sizeof($l_timezone)-1] != 0) ? $lang[sprintf('%.1f', $board_config['board_timezone'])] : $lang[number_format($board_config['board_timezone'])];

$site_descs = explode("@@@", $board_config['site_desc']);

//
// The following assigns all _common_ variables that may be used at any point
// in a template.
//
$template->assign_vars(array(
	'SITENAME' => $board_config['sitename'],
	'SITE_DESCRIPTION' => $site_descs[rand(0, sizeof($site_descs) - 1)],
	'META_HTTP_EQUIV_TAG' => '<meta http-equiv="refresh" content="' . $board_config['meta_redirect_url_time'] . '; URL=' . $board_config['meta_redirect_url_adress'] . '"><meta http-equiv="refresh" content="' . $board_config['meta_refresh'] . '"><meta http-equiv="pragma" content="' . $board_config['meta_pragma'] . '"><meta http-equiv="content-language" content="' . $board_config['meta_language'] . '" />',
	'META_TAG' => '<meta name="keywords" content="' . $board_config['meta_keywords'] . '"><meta name="description" content="' . $board_config['meta_description'] . '"><meta name="revisit-after" content="' . $board_config['meta_revisit'] . ' days"><meta name="author" content="' . $board_config['meta_author'] . '"><meta name="owner" content="' . $board_config['meta_owner'] . '"><meta name="distribution" content="' . $board_config['meta_distribution'] . '"><meta name="robots" content="' . $board_config['meta_robots'] . '"><meta name="abstract" content="' . $board_config['meta_abstract'] . '"><meta name="rating" content="' . $board_config['meta_rating'] . '" /><meta name="identifier-url" content="' . $board_config['meta_identifier_url'] .'"><meta name="reply-to" content="' . $board_config['meta_reply_to'] .'"><meta name="category" content="' . $board_config['meta_category'] .'"><meta name="copyright" content="' . $board_config['meta_copyright'] .'"><meta name="generator" content="' . $board_config['meta_generator'] .'"><meta name="date-creation-yyyymmdd" content="' . $board_config['meta_date_creation_year'] . '' . $board_config['meta_date_creation_month'] . '' . $board_config['meta_date_creation_day'] . '"><meta name="date-revision-yyyymmdd" content="' . $board_config['meta_date_revision_year'] . '' . $board_config['meta_date_revision_month'] . '' . $board_config['meta_date_revision_day'] . '">',
	'LOGO_URL' => $board_config['logo_url'],
	'CUSTOM_OVERALL_HEADER' => $board_config['custom_overall_header'],
	'CUSTOM_OVERALL_FOOTER' => $board_config['custom_overall_footer'],
	'CUSTOM_SIMPLE_HEADER' => $board_config['custom_simple_header'],
	'CUSTOM_SIMPLE_FOOTER' => $board_config['custom_simple_footer'],
	'PAGE_TITLE' => $page_title,
	'LAST_VISIT_DATE' => sprintf($lang['You_last_visit'], $s_last_visit),
	'CURRENT_TIME' => sprintf($lang['Current_time'], create_date($board_config['default_dateformat'], time(), $board_config['board_timezone'])),
	'TOTAL_USERS_ONLINE' => $l_online_users,
	'LOGGED_IN_USER_LIST' => $online_userlist,
	'RECORD_USERS' => sprintf($lang['Record_online_users'], $board_config['record_online_users'], create_date($board_config['default_dateformat'], $board_config['record_online_date'], $board_config['board_timezone'])),
	'RECORD_DAY_USERS' => sprintf($lang['Record_day_users'], $board_config['record_day_users'], create_date($board_config['default_dateformat'], $board_config['record_day_date'], $board_config['board_timezone'])),  	
	'PRIVATE_MESSAGE_INFO' => $l_privmsgs_text,
	'PRIVATE_MESSAGE_INFO_UNREAD' => $l_privmsgs_text_unread,
	'PRIVATE_MESSAGE_NEW_FLAG' => $s_privmsg_new,

	'PRIVMSG_IMG' => $icon_pm,

	'POINTS' => $header_points,
	'PAGE_TRANSITION' => ($board_config['page_transition'] && $userdata['user_transition']) ? $board_config['page_transition'] : '',
	'HOMEPAGE' => real_path(''),
	'TOP_USERNAME' => ( $userdata['session_logged_in'] ) ? $userdata['username'] : $lang['Guest'],
	'IM_AUTO_POPUP' => $im_auto_popup, 
	'IM_HEIGHT' => $im_userdata['mode_height'],
	'IM_WIDTH' => $im_userdata['mode_width'],
	'ICON_UP' => $images['icon_up'],
	'ICON_DOWN' => $images['icon_down'],
	'LIMIT_USERNAME_MAX_LENGTH' => $board_config['limit_username_max_length'],

	'L_USERNAME' => $lang['Username'],
	'L_PASSWORD' => $lang['Password'],
	'L_LOGIN_LOGOUT' => $l_login_logout,
	'L_LOGIN' => $lang['Login'],
	'L_LOG_ME_IN' => $lang['Log_me_in'],
	'L_AUTO_LOGIN' => $lang['Log_me_in_auto'],
	'L_INDEX' => $lang['Forum_Index'],
	'L_PORTAL' => $lang['Portal'],
	'L_REGISTER' => $lang['Register'],
	'L_PROFILE' => $lang['Profile'],
	'L_PM' => $lang['PM'], 
	'L_SEARCH' => $lang['Search'],
	'L_PRIVATEMSGS' => $lang['Private_Messages'],
	'L_WHO_IS_ONLINE' => $lang['Who_is_Online'],
	'L_MEMBERLIST' => $lang['Memberlist'],
	'L_FAQ' => $lang['Faq'],
	'L_USERGROUPS' => $lang['Groupcp'],
	'L_EMOTICONS' => $lang['Emoticons'],
	'L_LINKDB' => $lang['Linkdb'],
	'L_DOWNLOAD' => $lang['Download'], 
	'L_STAFF' => $lang['Staff'],
	'L_ADDTOFAVORITES' => $lang['Favorites'], 
	'L_MAKEHOMEPAGE' => $lang['Make_Homepage'],
	'L_ATTACHMENTS' => $lang['Attachments'], 
	'L_STATISTICS' => $lang['Statistics'], 
	'L_CALENDAR' => $lang['Calendar'],
	'L_RATINGS' => $lang['Rating'],
	'L_TOPLIST' => $lang ['Toplist'], 
	'L_SEARCH_NEW' => $lang['Search_new'],
	'L_SEARCH_UNANSWERED' => $lang['Search_unanswered'],
	'L_VIEW_LAST_24_HOURS' => $lang['View_last_24_hours'],
	'L_SEARCH_SELF' => $lang['Search_your_posts'],
	'L_VIEW_RANDOM_TOPIC' => $lang['View_random_topic'], 
	'L_WHOSONLINE_ADMIN' => sprintf($lang['Admin_online_color'], '<span style="color: #' . $theme['adminfontcolor'] . '">', '</span>'),
	'L_WHOSONLINE_SUPERMOD' => sprintf($lang['Super_Mod_online_color'], '<span style="color: #' . $theme['supermodfontcolor'] . '">', '</span>'),
	'L_WHOSONLINE_MOD' => sprintf($lang['Mod_online_color'], '<span style="color: #' . $theme['modfontcolor'] . '">', '</span>'),
	'L_WHOSONLINE_GAMES' => ($board_config['ina_players_index']) ? ', <a href="' . append_sid('activity.'.$phpEx) .'" style="color: #' . $theme['playersfontcolor'] . '" class="gensmall">' . $lang['Games_online_color'] . '</a>' : '',
	'L_STYLE' => $lang['Style'],
	'L_USERCP' => $lang['Viewing_profile'],
	'L_ALL_FORUMS' => $lang['All_forums'],
	'L_VISITORS_SINCE' => $lang['Visitors_since'],
	'L_LATEST_PIC' => $lang['Latest_pic'],
	'L_POSTED' => $lang['Posted'],
	'L_BY' => $lang['By'],
	'L_IMLIST' => $lang['IM_list'],
	'L_CHARTS' => $lang['Charts'],
	'L_BAN' => $lang['Ban'],
	'L_RADIO' => $lang['Radio1'],
	'L_TOP_REFERRALS' => $lang['Header_Top_Referrals'],
	'L_CUSTOM_HEADER' => $advance_html['custom_header'],
	'L_CUSTOM_BODY' => (!empty($advance_html['custom_body'])) ? ' ' . $advance_html['custom_body'] : '',
	'L_CUSTOM_BODY_HEADER' => $advance_html['custom_body_header'],
	'L_CUSTOM_FOOTER' => $advance_html['custom_footer'],
	'L_ALBUM' => $lang['Album'], 
	'L_NEWEST_PIC' => $lang['Newest_pic'],
	'L_PIC_TITLE' => $lang['Pic_Title'],
	'L_POSTER' => $lang['Poster'],
	'L_POSTED' => $lang['Posted'],
	'L_COMMENT' => $lang['Post_comment'],
	'L_COMMENTS' => $lang['Comments'],
	'L_LEGEND' => $lang['Legend'],
	'L_IM_LAUNCH' => $l_prillian_text,
	'L_FORUM_TOUR' => $lang['Forum_tour'],
	'L_ACTIVITY' => $lang['Activity'],
	'L_BOOKIES' => $lang['Bookies'],
	'L_KARMA' => '+ ' . $lang['Karma'],
	'L_TOP_POSTERS' => $lang['Top_posters'],
	'L_NEWEST_MEMBERS' => $lang['Newest_members'],
	'L_MOST_POINTS' => $lang['Most'] . ' ' . $board_config['points_name'],
	'L_SHOP' => $lang['Shop'],
	'L_LEXICON' => $lang['Lexicon'],
	'L_DAILY_WALLPAPER' => $lang['Daily_wallpaper'],
	'L_DLOAD_WALLPAPER' => $lang['Dload_wallpaper'],
	'L_SELECT_LANG' => $lang['Board_lang'], 
	'L_AVATAR_LIST' => $lang['L_AVATARLIST'],
	'L_ANNOUNCEMENT' => $lang['Site_Announcement'],
				
	'L_SUBMIT' => $lang['Submit'],
	'L_RESET' => $lang['Reset'],
	'L_CANCEL' => $lang['Cancel'],
	'L_GO' => $lang['Go'],
	'L_SORT' => $lang['Sort'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
		
	'U_AVATAR_LIST' => append_sid('avatarsuite_listavatars.'.$phpEx),
	'U_SEARCH_UNANSWERED' => append_sid('search.'.$phpEx.'?search_id=unanswered'),
	'U_SEARCH_SELF' => append_sid('search.'.$phpEx.'?search_id=egosearch'),
	'U_SEARCH_NEW' => append_sid('search.'.$phpEx.'?search_id=newposts'),
	'U_VIEW_RANDOM_TOPIC' => append_sid('viewtopic.'.$phpEx.'?view=random'), 
	'U_PORTAL' => append_sid('portal.'.$phpEx),
	'U_INDEX' => append_sid('index.'.$phpEx),
	'U_REGISTER' => append_sid('profile.'.$phpEx.'?mode=' . REGISTER_MODE),
	'U_PROFILE' => append_sid('profile.'.$phpEx.'?mode=editprofile&amp;ucp=main'),
	'U_WEATHER' => append_sid('profile.'.$phpEx.'?mode=editprofile&amp;ucp=profile_info'),
	'U_PRIVATEMSGS' => append_sid('privmsg.'.$phpEx.'?folder=inbox'),
	'U_PRIVATEMSGS_POPUP' => append_sid('privmsg.'.$phpEx.'?mode=newpm'),
	'U_SEARCH' => append_sid('search.'.$phpEx),
	'U_SEARCHBOX' => append_sid('search.'.$phpEx.'?mode=results'),
	'U_SEARCH_DAILY' => append_sid('getdaily.'.$phpEx),
	'U_MEMBERLIST' => append_sid('memberlist.'.$phpEx),
	'U_MODCP' => append_sid('modcp.'.$phpEx),
	'U_FAQ' => append_sid('faq.'.$phpEx),
	'U_VIEWONLINE' => append_sid('viewonline.'.$phpEx),
	'U_LOGIN_LOGOUT' => append_sid($u_login_logout),
	'U_GROUP_CP' => append_sid('groupcp.'.$phpEx),
	'U_EMOTICONS' => append_sid('smilies.'.$phpEx),
	'U_LINKDB' => append_sid('linkdb.'.$phpEx),
	'U_DOWNLOAD' => append_sid('dload.'.$phpEx),
	'U_STAFF' => append_sid('staff.'.$phpEx),
	'U_ATTACHMENTS' => append_sid('attachments.'.$phpEx),
	'U_STATISTICS' => append_sid('statistics.'.$phpEx),
	'U_CALENDAR' => append_sid('calendar.'.$phpEx),
	'U_RATINGS' => append_sid('ratings.'.$phpEx),
	'U_USERCP' => append_sid('profile.'.$phpEx.'?mode=editprofile&amp;ucp=main'),
	'U_USERCP_GALLERY' => append_sid('album_personal.'.$phpEx.'?user_id=' . $userdata[user_id]),
	'U_UACP' => append_sid('profile_attachments.'.$phpEx . '?' . POST_USERS_URL . '=' . $userdata['user_id'] . '&amp;sid=' . $userdata['session_id']),
	'U_ALL_FORUMS' => append_sid('index_all.'.$phpEx),
	'U_IMLIST' => append_sid('imlist.'.$phpEx),
	'U_TOPLIST' => append_sid('toplist.'.$phpEx),
	'U_CHARTS' => append_sid('charts.'.$phpEx.'?action=list'),
	'U_BAN' => append_sid('banlist.'.$phpEx),
	'U_ALBUM' => append_sid('album.'.$phpEx),
	'U_TOP_REFERRALS' => append_sid('top_referrals.'.$phpEx),
	'U_LOTWINNER' => append_sid('lotwin.'.$phpEx),
	'U_CONTACT_MAN' => append_sid(CONTACT_URL),
	'U_IM_LAUNCH' => append_sid(PRILL_URL . $im_userdata['mode_string']),
	'U_FORUM_TOUR' => "javascript:tour()",
	'U_ACTIVITY' => append_sid('activity.'.$phpEx),
	'U_BOOKIES' => append_sid('bookies.'.$phpEx),
	'U_BOOKIE_ALLSTATS' => append_sid('bookie_allstats.'.$phpEx),
	'U_BOOKIE_YOURSTATS' => append_sid('bookie_yourstats.'.$phpEx),
	'U_SHOP' => append_sid('shop.'.$phpEx),
	'U_LEXICON' => append_sid('lexicon.'.$phpEx),
		
	'S_CONTENT_DIRECTION' => $lang['DIRECTION'],
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'S_CONTENT_DIR_LEFT' => $lang['LEFT'],
	'S_CONTENT_DIR_RIGHT' => $lang['RIGHT'],
	'S_TIMEZONE' => sprintf($lang['All_times'], $l_timezone),
	'S_LOGIN_ACTION' => append_sid('login.'.$phpEx),
	'AUTOLOGIN_CHECKED' => (!empty($board_config['autologin_check'])) ? ' checked="checked"' : '',

 	'T_THEME' => $theme['template_name'],
	'T_NAV_STYLE' => ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images',
	'T_LOGO' => $images['template_logo'],
 	'T_HEAD_STYLESHEET' => $theme['head_stylesheet'],
	'T_BODY_BACKGROUND' => $theme['body_background'],
	'T_BODY_BGCOLOR' => '#'.$theme['body_bgcolor'],
	'T_BODY_TEXT' => '#'.$theme['body_text'],
	'T_BODY_LINK' => '#'.$theme['body_link'],
	'T_BODY_VLINK' => '#'.$theme['body_vlink'],
	'T_BODY_ALINK' => '#'.$theme['body_alink'],
	'T_BODY_HLINK' => '#'.$theme['body_hlink'],
	'T_TR_COLOR1' => '#'.$theme['tr_color1'],
	'T_TR_COLOR2' => '#'.$theme['tr_color2'],
	'T_TR_COLOR3' => '#'.$theme['tr_color3'],
	'T_TR_CLASS1' => $theme['tr_class1'],
	'T_TR_CLASS2' => $theme['tr_class2'],
	'T_TR_CLASS3' => $theme['tr_class3'],
	'T_TH_COLOR1' => '#'.$theme['th_color1'],
	'T_TH_COLOR2' => '#'.$theme['th_color2'],
	'T_TH_COLOR3' => '#'.$theme['th_color3'],
	'T_TH_CLASS1' => $theme['th_class1'],
	'T_TH_CLASS2' => $theme['th_class2'],
	'T_TH_CLASS3' => $theme['th_class3'],
	'T_TD_COLOR1' => '#'.$theme['td_color1'],
	'T_TD_COLOR2' => '#'.$theme['td_color2'],
	'T_TD_COLOR3' => '#'.$theme['td_color3'],
	'T_TD_CLASS1' => $theme['td_class1'],
	'T_TD_CLASS2' => $theme['td_class2'],
	'T_TD_CLASS3' => $theme['td_class3'],
	'T_FONTFACE1' => $theme['fontface1'],
	'T_FONTFACE2' => $theme['fontface2'],
	'T_FONTFACE3' => $theme['fontface3'],
	'T_FONTSIZE1' => $theme['fontsize1'],
	'T_FONTSIZE2' => $theme['fontsize2'],
	'T_FONTSIZE3' => $theme['fontsize3'],
	'T_FONTCOLOR1' => '#'.$theme['fontcolor1'],
	'T_FONTCOLOR2' => '#'.$theme['fontcolor2'],
	'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
	'T_FONTCOLOR4' => '#'.$theme['fontcolor4'],
	'T_JB_COLOR1' => '#'.$theme['jb_color1'],
	'T_JB_COLOR2' => '#'.$theme['jb_color2'],
	'T_JB_COLOR3' => '#'.$theme['jb_color3'],
	'T_ADMINFONTCOLOR' => '#'.$theme['adminfontcolor'], 
	'T_SUPERMODFONTCOLOR' => '#'.$theme['supermodfontcolor'], 
	'T_MODFONTCOLOR' => '#'.$theme['modfontcolor'], 
	'T_SPAN_CLASS1' => $theme['span_class1'],
	'T_SPAN_CLASS2' => $theme['span_class2'],
	'T_SPAN_CLASS3' => $theme['span_class3'],

	'NAV_LINKS' => $nav_links_html,
	'COPYRIGHT_YEAR' => date('Y'))
);


//
// Login box?
//
if ( !$userdata['session_logged_in'] )
{
	$template->assign_block_vars('switch_user_logged_out', array());
	
	//
	// Allow autologin?
	//
	if (!isset($board_config['allow_autologin']) || $board_config['allow_autologin'] )
	{
		$template->assign_block_vars('switch_allow_autologin', array());
		$template->assign_block_vars('switch_user_logged_out.switch_allow_autologin', array());
	}
}
else
{
	$template->assign_block_vars('switch_user_logged_in', array());

	// Administration link
	if ( $userdata['user_level'] == ADMIN ) 
	{ 
		$acp_link = append_sid('admin/index.'.$phpEx.'?sid=' . $userdata['session_id']);
		
	 	$template->assign_block_vars('switch_admin_logged_in', array(
	 		'L_ADMINCP' => $lang['Admin_panel'],
	 		'U_ADMINCP' => $acp_link)
	 	); 
   	}	 

	if ( !empty($userdata['user_popup_pm']) )
	{
		$template->assign_block_vars('switch_enable_pm_popup', array());
	}
	
	$lwuserreminder = lw_write_header_reminder();
	$template->assign_block_vars('switch_lw_user_logged_in', array());
	
	$template->assign_vars(array(
		'L_LW_EXPIRE_REMINDER' => $lwuserreminder)
	); 	
}

//
// Show board disabled note
//
if (defined('BOARD_DISABLE'))
{
	$disable_message = (!empty($board_config['board_disable_text'])) ? $board_config['board_disable_text'] : $lang['Board_disable'];
	
	$template->assign_block_vars('board_disable', array(
		'MSG' => str_replace("\n", '<br />', $disable_message))
	);
}

// Add no-cache control for cookies if they are set
//$c_no_cache = (isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) || isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_data'])) ? 'no-cache="set-cookie", ' : '';

// Work around for "current" Apache 2 + PHP module which seems to not
// cope with private cache control setting
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header ('Expires: 0');
header ('Pragma: no-cache');


// 
// Meetings
//
if ( empty($gen_simple_header) && ( $gen_meeting_header !== FALSE ) )
{
	// Get access status for all meetings
	$sql = "SELECT m.meeting_id, mg.meeting_group 
		FROM " . MEETING_DATA_TABLE . " m, " . MEETING_USERGROUP_TABLE . " mg
		WHERE mg.meeting_id = m.meeting_id";
	if ( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not get meeting usergroups', '', __LINE__, __FILE__, $sql);
	}

	$meetings_access_ids = array();

	while ( $row = $db->sql_fetchrow($result) )
	{
		$meeting_id = $row['meeting_id'];
		$meeting_group = $row['meeting_group'];

		if ( $meeting_group == -1 )
		{
			$meetings_access_ids[] = $meeting_id;
		}
		else
		{
			$sql_auth_id = "SELECT g.group_id 
				FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
				WHERE g.group_id = $meeting_group
					AND g.group_id = ug.group_id
					AND ug.user_pending <> " . TRUE . "
					AND g.group_single_user <> " . TRUE . "
					AND ug.user_id = " . $userdata['user_id'];
			if ( !$result_auth_id = $db->sql_query($sql_auth_id) )
			{
				message_die(GENERAL_ERROR, 'Could not get meeting access data', '', __LINE__, __FILE__, $sql_auth_id);
			}

			$count_usergroups = $db->sql_numrows($result_auth_id);
			$db->sql_freeresult($result_auth_id);

			if ( $count_usergroups > 0 )
			{
				$meetings_access_ids[] = $meeting_id;
			}
		}
	}
	$db->sql_freeresult($result);

	$language = $board_config['default_lang'];
	if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_meeting.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_meeting.' . $phpEx);

	if ( sizeof($meetings_access_ids) > 0 )
	{
		$meeting_ids = ( count($meetings_access_ids) == 1 ) ? $meetings_access_ids[0] : implode(',', $meetings_access_ids);
		$sql_meeting_access = ' WHERE meeting_id IN (' . $meeting_ids . ') AND meeting_time > ' . time();
	}
	else if ($userdata['user_level'] == ADMIN)
	{
		$sql_meeting_access = ' WHERE meeting_time > ' . time();
	}
	else
	{
		$sql_meeting_access = '';
	}

	if ($sql_meeting_access != '')
	{
		$sql = "SELECT count(meeting_id) AS total_meetings 
			FROM " . MEETING_DATA_TABLE . "
			$sql_meeting_access";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get meeting data', '', __LINE__, __FILE__, $sql);
		}
	
		while ( $row = $db->sql_fetchrow($result) )
		{
			$meeting_active_ids = $row['total_meetings'];
		}
		$db->sql_freeresult($result);
	}
	else
	{
		$meeting_active_ids = 0;
	}

	if ( $meeting_active_ids == 0 )
	{
		$meeting_active_string = $lang['No_active_meetings'];
	}
	else if ( $meeting_active_ids == 1 )
	{
		$meeting_active_string = $lang['One_active_meeting'];
	}
	else
	{
		$meeting_active_string = sprintf($lang['Active_meetings'], $meeting_active_ids);
	}

	if ( $meeting_active_ids > 0 )
	{
		$template->assign_block_vars('switch_meeting_on', array(
			'L_MEETING_LINK' => $meeting_active_string,
			'U_MEETING_LINK' => append_sid('meeting.'.$phpEx))
		);
	}
}

if ( $prill_config['allow_ims'] )
{
	$template->assign_block_vars('switch_prillian_on', array());
}

if ( file_exists('lite') )
{
	$template->assign_block_vars('switch_lite_on', array(
		'L_LITE' => $lang['Lite_version'],
		'U_LITE' => append_sid('lite/index.'.$phpEx))
	);
}

$template->pparse('overall_header');

//
// Ban Unwanted Referer's
//
// Get users details
$refer = $_SERVER['HTTP_REFERER'];
$kick = 0;
// Select all banned sites
$sql = "SELECT site_url
	FROM " . BANNED_SITES;
$result = mysql_query($sql);

// Check if we get some results
if($result)
{
	while ($row = mysql_fetch_array($result)) 
	{ 
  		$checkref = strlen($refer);
  		$checkres = strlen($row['site_url']);
  		if ($checkref > 0 && $checkres > 0)
  		{
			$poss_banned = substr_count($refer, $row['site_url']);
  			if ($poss_banned > 0)
  			{
  				$kick++;
  			}
  		}
	} 
	$db->sql_freeresult($result);

	if ( $kick > 0)
	{
		// Save the user data
		$rem_ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
		$rem_host = $HTTP_SERVER_VARS['REMOTE_HOST'];	
		$browser = $HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		$host_name = gethostbyaddr($rem_ip);
		
		$sql = "INSERT INTO " . BANNED_VISITORS . " (count, refer, ip, ip_owner, browser, user_id, user) 
			VALUES ('', '" . $refer . "', '" . $rem_ip . "', ' " . $host_name . " ', '" . $browser . "', '" . $userdata['user_id'] . "', '" . $userdata['username'] . "')";
		if ( !($result = mysql_query($sql)) )
		{
			// If the site doesn't stop the visitors there is a problem!
		}	
		else
		{
			// Inform the user they are not welcome
			message_die(GENERAL_MESSAGE, $lang['Visitor_banned']);
		}
		$db->sql_freeresult($result);
	}
}

?>
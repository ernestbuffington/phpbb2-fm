<?php
/** 
*
* @package phpBB2
* @version $Id: staff.php,v 2.0.0 2003/03/31 00:06:33 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path .'extension.inc');
include($phpbb_root_path .'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_STAFF);
init_userprefs($userdata);
//
// End session management
//

$last_post_length = '30';		// post title length
$last_post_limit = '5';		// post limit

// optional part
$exclude_users = '';		// enter user´s ID to exclude any user (separate them with a comma)
$special_users = '';		// enter user´s ID if you want to a further area (separate them with a comma)
$exclude_special_users = '';	// enter user´s ID if you added a further area and want to exlude user from a certain area (separate them with a comma)


if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

$page_title = $lang['Staff'];
$gen_simple_header = ( $mode == 'view_profile' ) ? TRUE : '';
include('includes/page_header.'.$phpEx);

if( $mode != 'view_profile' )
{
	$template->assign_block_vars('switch_list_staff', array());
	$template->set_filenames(array(
		'body' => 'staff_body.tpl')
	);
	make_jumpbox('viewforum.'.$phpEx); 
	
	$template->assign_vars(array(
		'L_USERNAME' => $lang['Username'],
		'L_FORUMS' => $lang['Staff_forums'],
		'L_LOCATION' => $lang['Location'],
		'L_CONTACT' => $lang['Contact'],
		'L_MESSENGER' => $lang['Staff_messenger'],
		'L_WWW' => $lang['Website'])
	);

	$is_auth_ary = array();
	$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $forums);

	$sql_forums = "SELECT ug.user_id, f.forum_id, f.forum_name
		FROM ". AUTH_ACCESS_TABLE ." aa, ". USER_GROUP_TABLE ." ug, ". FORUMS_TABLE ." f
		WHERE aa.auth_mod = ". TRUE ." 
			AND ug.group_id = aa.group_id 
			AND f.forum_id = aa.forum_id
			AND ug.user_pending = 0
		ORDER BY f.forum_order";
	if( !$result_forums = $db->sql_query($sql_forums) )
	{
		message_die(GENERAL_ERROR, 'could not query forums.', '', __LINE__, __FILE__, $sql_forums);
	}
	
	while( $row = $db->sql_fetchrow($result_forums) )
	{
		$display_forums = ( $is_auth_ary[$row['forum_id']]['auth_view'] ) ? true : false;
		if( $display_forums )
		{
			$forum_id = $row['forum_id'];
			$staff2[$row['user_id']][$row['forum_id']] = '<a href="'. append_sid("viewforum.$phpEx?f=$forum_id") .'" class="genmed">'. $row['forum_name'] .'</a>';
		}
	}
	$db->sql_freeresult($result_forums);

	$sql_ranks = "SELECT * 
		FROM " . RANKS_TABLE . " 
		ORDER BY rank_special, rank_min DESC";
	if( !($results_ranks = $db->sql_query($sql_ranks)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain ranks information.', '', __LINE__, __FILE__, $sql_ranks);
	}
	
	$ranksrow = array();
	while( $row = $db->sql_fetchrow($results_ranks) )
	{
		$ranksrow[] = $row;
	}
	$db->sql_freeresult($result_ranks);

	$level_cat = $lang['Staff_level'];
	for( $i = 0; $i < sizeof($level_cat); $i++ )
	{
		$user_level = $level_cat[$i];

		$template->assign_block_vars('switch_list_staff.user_level', array(
			'USER_LEVEL' => $user_level)
		);

		if( $level_cat['0'] )
		{
			$where = 'user_level = '. ADMIN;
		}
		else if( $level_cat['1'] )
		{
			$where = 'user_level = '. LESS_ADMIN;
		}
		else if( $level_cat['2'] )
		{
			$where = 'user_level = '. MOD;
		}
		$level_cat[$i] = '';

		$sql_exclude_users = ( !empty($exclude_users) ) ? ' AND user_id NOT IN ('. $exclude_users .')' : '';
		$sql_user = "SELECT * 
			FROM " . USERS_TABLE . " 
			WHERE $where $sql_exclude_users 
			ORDER BY user_regdate";
		if( !($result_user = $db->sql_query($sql_user)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain user information.', '', __LINE__, __FILE__, $sql_user);
		}
		
		if( $staff = $db->sql_fetchrow($result_user) )
		{
			$k = 0;
			do
			{
				$user_id = $staff['user_id'];
				$user_status = ( $staff['user_session_time'] >= ( time() - ($board_config['whosonline_time'] * 60) ) ) ? (( $row['user_allow_viewonline'] ) ? $lang['Staff_online'] : (( $userdata['user_level'] == ADMIN || $userdata['user_id'] == $user_id ) ? '<i>'. $lang['Staff_online'] .'</i>' : '')) : '';

				$forums = $rank = $rank_image = $avatar = '';
				if( $staff['user_rank'] )
				{
					for( $j = 0; $j < sizeof($ranksrow); $j++ )
					{
						if( $staff['user_rank'] == $ranksrow[$j]['rank_id'] && $ranksrow[$j]['rank_special'] )
						{
							$rank = $ranksrow[$j]['rank_title'];
							$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/'. $ranksrow[$j]['rank_image'] .'" alt="'. $rank .'" title="'. $rank .'" />' : '';
						}
					}
				}
				else
				{
					for( $j = 0; $j < sizeof($ranksrow); $j++ )
					{
						if( $staff['user_posts'] >= $ranksrow[$j]['rank_min'] && !$ranksrow[$j]['rank_special'] )
						{
							$rank = $ranksrow[$j]['rank_title'];
							$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/'. $ranksrow[$j]['rank_image'] .'" alt="'. $rank .'" title="'. $rank .'" />' : '';
						}
					}
				}

				if( $staff['user_avatar'] )
				{
					switch( $staff['user_avatar_type'] )
					{
						case USER_AVATAR_UPLOAD:
							$avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="'. $board_config['avatar_path'] .'/'. $staff['user_avatar'] .'" alt="" title="" />' : '';
							break;
						case USER_AVATAR_REMOTE:
							$avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="'. $staff['user_avatar'] .'" alt="" title="" />' : '';
							break;
						case USER_AVATAR_GALLERY:
							$avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="'. $board_config['avatar_gallery_path'] .'/'. $staff['user_avatar'] .'" alt="" title="" />' : '';
							break;
					}
				}

				if( !empty($staff2[$staff['user_id']]) )
				{
					asort($staff2[$staff['user_id']]);
					$forums = implode('<br />', $staff2[$staff['user_id']]);
				}

				$pmto = append_sid('privmsg.'.$phpEx.'?mode=post&amp;' . POST_USERS_URL . '=' . $user_id);
				$pm = '<a href="'. $pmto .'"><img src="'. $images['icon_pm'] .'" alt="' . $lang['Send_private_message'] . '" title="'. $lang['Send_private_message'] .'" /></a>';

				if( !empty($staff['user_viewemail']) || $userdata['user_level'] == ADMIN )
				{
					$mailto = ( $board_config['board_email_form'] ) ? append_sid('profile.'.$phpEx.'?mode=email&amp;' . POST_USERS_URL . '=' . $user_id) : 'mailto:'. $staff['user_email'];
					$mail = ( $staff['user_email'] ) ? '<a href="'. $mailto .'"><img src="'. $images['icon_email'] .'" alt="'. $lang['Send_email'] .'" title="'. $lang['Send_email'] .'" /></a>' : '';
				}
				else
				{
					$mailto = $mail = '';
				}

    			$msn = ( $staff['user_msnm'] ) ? '<a href="http://members.msn.com/' . $staff['user_msnm'] . '" target="_blank"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" /></a>' : '';
			    $yim = ( $staff['user_yim'] ) ? '<a href="ymsgr:sendIM?' . $staff['user_yim'] . '&__you+there?"><img src="http://opi.yahoo.com/online?u=' . $staff['user_yim'] . '&m=g&t=1" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" /></a>' : '';
			    $aim = ( $staff['user_aim'] ) ? '<a href="aim:goim?screenname=' . str_replace('+', '', $staff['user_aim']) . '&amp;message=Hello+Are+you+there?"><img src="http://big.oscar.aol.com/' . $staff['user_aim'] . '?on_url='.$images['icon_aim_online'].'&off_url='.$images['icon_aim_offline'].'" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" /></a>' : '';
    			$icq = ( $staff['user_icq'] ) ? '<a href="http://wwp.icq.com/scripts/contact.dll?msgto=' . $staff['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" /></a>' : '';
				$www = ( $staff['user_website'] ) ? '<a href="'. $staff['user_website'] .'" target="_userwww"><img src="'. $images['icon_www'] .'" alt="'. $lang['Visit_website'] .'" title="'. $lang['Visit_website'] .'" /></a>' : '';
				$xfi = ( $staff['user_xfi'] ) ? '<a href="http://www.xfire.com/xf/modules.php?name=XFire&amp;file=profile&amp;uname=' . $staff['user_xfi'] . '" target="_blank"><img src="' . $images['icon_xfi'] . '" alt="' . $lang['XFI'] . '" title="' . $lang['XFI'] . '" /></a>' : '';
				$gtalk = ( $staff['user_gtalk'] ) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id) . '"><img src="' . $images['icon_gtalk'] . '" alt="' . $lang['GTALK'] . '" title="' . $lang['GTALK'] . '" /></a>' : '';
   				$skype_img = ( $staff['user_skype'] ) ? '<a href="#' . $user_id . '" onClick=window.open("profile_skype_popup.'.$phpEx.'?' . POST_USERS_URL. '=' . $user_id . '","gesamt","location=no,menubar=no,toolbar=no,scrollbars=auto,width=320,height=500,status=no",title="Skype")><img src="' . $images['icon_skype'] . '" alt="' . $lang['skype'] . '" title="' . $lang['skype'] . '" /></a>' : '';
    			$skype_user = ( $staff['user_skype'] ) ? '<a href="#' . $user_id . '" onClick=window.open("profile_skype_popup.'.$phpEx.'?' . POST_USERS_URL . '=' . $user_id . '","gesamt","location=no,menubar=no,toolbar=no,scrollbars=auto,width=320,height=500,status=no",title="Skype")><img alt="' . $lang['skype'] . '" title="' . $lang['skype'] . '" src="http://mystatus.skype.com/smallicon/' . prepare_skype_http($staff['user_skype']) . '" /></a>' : '';

				$template->assign_block_vars('switch_list_staff.user_level.staff', array(
					'ROW_CLASS' => ( !($k % 2) ) ? $theme['td_class1'] : $theme['td_class2'],
					'USERNAME' => $staff['username'],
					'USER_STATUS' => $user_status,
					'U_PROFILE' => append_sid("staff.$phpEx?mode=view_profile&amp;". POST_USERS_URL ."=$user_id"),
					'RANK' => $rank,
					'RANK_IMAGE' => $rank_image,
					'AVATAR' => $avatar,
					'LOCATION' => $staff['user_from'],
					'FORUMS' => $forums,
					'PM' => $pm,
					'EMAIL' => $mail,
					'SKYPE_IMG' => $skype_img, 
	    			'SKYPE_USER' => $skype_user,
					'SKYPE' => $skype,
					'MSN' => $msn,
					'YIM' => $yim,
					'AIM' => $aim,
					'ICQ' => $icq,
					'WWW' => $www,
					'XFI' => $xfi,
					'GTALK' => $gtalk)
				);
				$k++;
			}
			while( $staff = $db->sql_fetchrow($result_user) );
			$db->sql_freeresult($result_user);
		}
	}
}
else
{
	if( !function_exists('period') )
	{
		function period($date) // borrowed from birthday mod
		{
			global $lang;

			$years = floor($date / 31536000);
			$date = $date - ($years * 31536000);
			$weeks = floor($date / 604800);
			$date = $date - ($weeks * 604800);
			$days = floor($date / 86400);
			$date = $date - ($days * 86400);
			$hours = floor($date / 3600);
			$date = $date - ($hours * 3600);
			$minutes = floor($date / 60);

			$result = (( $years ) ? $years .' '. (( $years == 1 ) ? $lang['Staff_year'] : $lang['Staff_years']) .', ' : '').
			(( $years || $weeks ) ? $weeks .' '. (( $weeks == 1 ) ? $lang['Staff_week'] : $lang['Staff_weeks']) .', ' : '').
			(( $years || $weeks || $days ) ? $days .' '. (( $days == 1 ) ? $lang['Staff_day'] : $lang['Staff_days']) .', ' : '').
			(( $years || $weeks || $days || $hours ) ? $hours .' '. (( $hours == 1 ) ? $lang['Staff_hour'] : $lang['Staff_hours']) .', ' : '').
			(( $years || $weeks || $days || $hours || $minutes ) ? $minutes .' '. (( $minutes == 1 ) ? $lang['Staff_minute'] : $lang['Staff_minutes']) : '');
			return $result;
		}
	}

	if( empty($HTTP_GET_VARS[POST_USERS_URL]) || $HTTP_GET_VARS[POST_USERS_URL] == ANONYMOUS )
	{
		message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
	}
	
	$view_profile = get_userdata($HTTP_GET_VARS[POST_USERS_URL]);
	if( $view_profile == FALSE )
	{
		message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
	}

	$template->assign_block_vars('switch_view_profile', array());
	
	$template->set_filenames(array(	
		'profile_body' => 'staff_body.tpl')
	);

	if( $view_profile['user_posts'] != 0 )
	{
		$orig_word = $replacement_word = array();
		obtain_word_list($orig_word, $replacement_word);

		$sql_auth = "SELECT * 
			FROM ". FORUMS_TABLE;
		if( !$result_auth = $db->sql_query($sql_auth) )
		{
			message_die(GENERAL_ERROR, 'could not query forums information.', '', __LINE__, __FILE__, $sql_auth);
		}
		
		$forums = array();
		while( $row_auth = $db->sql_fetchrow($result_auth) )
		{
			$forums[] = $row_auth;
		}
		$db->sql_freeresult($result_auth);

		$is_auth_ary = array();
		$is_auth_ary = auth(AUTH_ALL, AUTH_LIST_ALL, $userdata, $forums);

		$except_forums = '\'start\'';
		for( $f = 0; $f < sizeof($forums); $f++ )
		{
			if( (!$is_auth_ary[$forums[$f]['forum_id']]['auth_read']) || (!$is_auth_ary[$forums[$f]['forum_id']]['auth_view']) )
			{
				if( $except_forums == '\'start\'' )
				{
					$except_forums = $forums[$f]['forum_id'];
				}
				else
				{
					$except_forums .= ',' . $forums[$f]['forum_id'];
				}
			}
		}

		$sql_last_posts = "SELECT p.post_time, p.post_id, p.poster_id, pt.post_id, pt.post_subject, t.forum_id, t.topic_id, t.topic_title, f.forum_name
			FROM ". POSTS_TABLE ." p, ". POSTS_TEXT_TABLE ." pt, ". TOPICS_TABLE ." t, ". FORUMS_TABLE ." f
			WHERE p.poster_id = '". $view_profile['user_id'] ."' 
				AND p.post_id = pt.post_id 
				AND p.topic_id = t.topic_id
				AND t.forum_id = f.forum_id 
				AND t.forum_id NOT IN ($except_forums) 
				AND t.topic_status <> '2'
			ORDER BY p.post_time DESC 
			LIMIT $last_post_limit";
		if( !($results_last_posts = $db->sql_query($sql_last_posts)) )
		{
			message_die(GENERAL_ERROR, 'error getting users post information.', '', __LINE__, __FILE__, $sql_last_posts);
		}
		
		while( $last_posts = $db->sql_fetchrow($result_last_posts) )
		{
			$last_post_title = ( !empty($last_posts['post_subject']) ) ? $last_posts['post_subject'] : $last_posts['topic_title'];
			$last_post_title = ( count($orig_word) ) ? preg_replace($orig_word, $replacement_word, $last_post_title) : $last_post_title;
			$last_post_title = ( strlen($last_post_title) < $last_post_length ) ? $last_post_title : substr(stripslashes($last_post_title), 0, $last_post_length) .'...';

			$template->assign_block_vars('switch_view_profile.last_posts', array(
				'LAST_POST_TITLE' => $last_post_title,
				'LAST_POST_URL' => append_sid("viewtopic.$phpEx?". POST_POST_URL ."=" . $last_posts['post_id'] ."#" . $last_posts['post_id']),
				'LAST_POST_TIME' => create_date($board_config['default_dateformat'], $last_posts['post_time'], $board_config['board_timezone']),
				'LAST_POST_PERIOD' => sprintf($lang['Staff_ago'], period(time() - $last_posts['post_time'])),
				'FORUM_NAME' => $last_posts['forum_name'],
				'FORUM_URL' => append_sid("viewforum.$phpEx?". POST_FORUM_URL ."=" . $last_posts['forum_id']))
			);
		}
		$db->sql_freeresult($result_last_posts);

		$total_posts = get_db_stat('postcount');
		$total_topics = get_db_stat('topiccount');
	}

	$sql_topics = "SELECT COUNT(topic_id) AS user_topics 
		FROM ". TOPICS_TABLE ." 
		WHERE topic_poster = " . $view_profile['user_id'];
	if( !($results_topics = $db->sql_query($sql_topics)) )
	{
		message_die(GENERAL_ERROR, 'Error getting users post information.', '', __LINE__, __FILE__, $sql_topics);
	}
	$topics = $db->sql_fetchrow($results_topics);
	
	$user_topics = ( $topics['user_topics'] != 0 ) ? $topics['user_topics'] : 0;

	$memberdays = max(2, ( time() - $view_profile['user_regdate'] ) / 86400 );
	$posts_per_day = $view_profile['user_posts'] / $memberdays;
	$post_percent = ( $total_posts || $view_profile['user_posts'] != 0 ) ? min(100, ($view_profile['user_posts'] / $total_posts) * 100) : 0;
	$topics_per_day = $user_topics / $memberdays;
	$topic_percent = ( $total_topics || $view_profile['user_posts'] != 0 ) ? min(100, ($user_topics / $total_topics) * 100) : 0;

	include_once($phpbb_root_path .'includes/bbcode.'.$phpEx);
	$user_sig = ( $view_profile['user_sig'] != '' && $board_config['allow_sig'] ) ? $view_profile['user_sig'] : '';
	$user_sig_bbcode_uid = $view_profile['user_sig_bbcode_uid'];

	if( $user_sig != '' )
	{
		$template->assign_block_vars('switch_view_profile.view_signature', array());
		$user_sig = ( $view_profile['user_allowsmile'] && $board_config['allow_smilies'] ) ? smilies_pass($user_sig) : $user_sig;
		$user_sig = ( $board_config['allow_bbcode'] && $user_sig_bbcode_uid != '' ) ? bbencode_second_pass($user_sig, $user_sig_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $user_sig);
		$user_sig = ( !$board_config['allow_html'] && $userdata['user_allowhtml'] ) ? preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig) : $user_sig;
		$user_sig = str_replace("\n", "\n<br />\n", $user_sig);
		$user_sig = make_clickable($user_sig);
		$user_sig = ( count($orig_word) ) ? str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $user_sig . '<'), 1, -1)) : '';
	}

	$template->assign_vars(array(
		'L_ABOUT_USER' => sprintf($lang['Staff_about'], $view_profile['username']),
		'L_CLOSE_WINDOW' => $lang['Close_window'],
		'L_POSTS' => $lang['Posts'],
		'L_TOPICS' => $lang['Topics'],
		'L_JOINED' => $lang['Joined'],
		'POSTS' => $view_profile['user_posts'],
		'POST_PERCENT' => sprintf($lang['User_post_pct_stats'], $post_percent),
		'POSTS_PER_DAY' => sprintf($lang['User_post_day_stats'], $posts_per_day),
		'TOPICS' => $user_topics,
		'TOPIC_PERCENT' => sprintf($lang['User_post_pct_stats'], $topic_percent),
		'TOPICS_PER_DAY' => sprintf($lang['Staff_user_topic_day_stats'], $topics_per_day),
		'JOINED' => create_date($board_config['default_dateformat'], $view_profile['user_regdate'], $board_config['board_timezone']),
		'JOINED_PERIOD' => sprintf($lang['Staff_since'], period(time() - $view_profile['user_regdate'])),
		'SIGNATURE' => $user_sig)
	);

	$template->pparse('profile_body');
	
	include($phpbb_root_path .'includes/page_tail.'.$phpEx);
}

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include('includes/page_tail.'.$phpEx);

?>
<?php
/** 
*
* @package includes
* @version $Id: functions_modules.php,v 1.0.7 9/10/2006 6:35 PM mj Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

function navbar_block()
{
	global $template;
	
	$template->set_filenames(array(
		'index_links' => 'portal_navbar.tpl')
	);
	
	$template->assign_var_from_handle('INDEX_LINKS', 'index_links');

	$template->assign_block_vars('displaynavbar', array());
	
	return;
} 

function karma_block()
{
	global $db, $template, $theme, $phpEx, $lang;
		
	$template->assign_block_vars('displaykarma', array());

	$sql = "SELECT user_id, username, user_level, karma_plus
		FROM " . USERS_TABLE . "
		WHERE user_id <> " . ANONYMOUS . " 
			AND karma_plus > 0
		ORDER BY karma_plus DESC 
		LIMIT 0,5";	
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not create karma module.', '', __LINE__, __FILE__, $sql);
	} 
	
	$karma = false;
	while ($row = $db->sql_fetchrow($result))
	{	
		$karma = true;
		$template->assign_block_vars('karmarow', array(
			'KARMA' => $row['karma_plus'],
			'KARMA_USER' => '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '" class="gensmall">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>')
		);
		$i++;
	}
	$db->sql_freeresult($result);
	
	if (empty($karma))
	{
		$template->assign_block_vars('karma_none', array(
			'NONE' => $lang['None'])
		);
		
	}
	return;
} 

function new_users_block()
{
	global $db, $template, $lang, $phpEx, $board_config;
	
	$total_users = get_db_stat('usercount');

	if( $total_users == 0 )
	{
		$l_total_user_s = $lang['Registered_users_zero_total'];
	}
	else if( $total_users == 1 )
	{
		$l_total_user_s = $lang['Registered_user_total'];
	}
	else
	{
		$l_total_user_s = $lang['Registered_users_total'];
	}
	$total_users_format = sprintf($l_total_user_s, $total_users); 
	$total_users_format = str_replace($total_users, $total_users, $total_users_format); 

	$template->assign_block_vars('displaynewusers', array(
		'TOTAL_USERS' => $total_users_format)
	);

	$sql = "SELECT user_id, username, user_level, user_regdate
		FROM " . USERS_TABLE . "
		WHERE user_id <> " . ANONYMOUS . " 
		ORDER BY user_id DESC 
		LIMIT 0,5";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not create new users module.', '', __LINE__, __FILE__, $sql);
	} 
	
	while ($row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('newusersrow', array(
			'NEWNAME' => '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '" class="gensmall">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>',
			'NEWNAME_JOINED' => create_date($lang['DATE_FORMAT'], $row['user_regdate'], $board_config['board_timezone']))
		);
	}
	$db->sql_freeresult($result);

	return;
} 

function top_posters_block()
{
	global $db, $template, $phpEx;

	$template->assign_block_vars('displaytopposters', array());

	$sql = "SELECT user_id, username, user_level, user_posts
		FROM " . USERS_TABLE . "
		WHERE user_id <> " . ANONYMOUS . " 
		ORDER BY user_posts DESC 
		LIMIT 0,5";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not create top posters module.', '', __LINE__, __FILE__, $sql);
	} 
	
	while ($row = $db->sql_fetchrow($result))
	{
		$template->assign_block_vars('toppostersrow', array(
			'POSTER' => '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '='  . $row['user_id']) . '" class="gensmall">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>',
			'POSTS' => $row['user_posts'])
		);
	}
	$db->sql_freeresult($result);

	return;
} 

function most_points_block()
{
	global $db, $template, $lang, $phpEx;
	
	$template->assign_block_vars('displaymostpoints', array());

	$sql = "SELECT user_id, username, user_level, user_points 
		FROM " . USERS_TABLE . "
		WHERE user_id <> " . ANONYMOUS . " 
		ORDER BY user_points DESC 
		LIMIT 0,5";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not create most points module.', '', __LINE__, __FILE__, $sql);
	} 
	
	while ($row = $db->sql_fetchrow($result))
	{
		$row['username'] = username_level_color($row['username'], $row['user_level'], $row['user_id']);

		$points_username = ( $row['user_id'] == ANONYMOUS ) ? ( ( $row['username'] != '' ) ? $row['username'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '='  . $row['user_id']) . '" class="gensmall">' . $row['username'] . '</a>';
		
		$template->assign_block_vars('pointsrow', array(
			'POINTS_POSTER' => $points_username,
			'POINTS' => $row['user_points'])
		);
	}
	$db->sql_freeresult($result);

	return;
} 

function top_dloads_block()
{
	global $db, $template, $phpEx, $lang;
	
	$template->assign_block_vars('displaydownloads', array() );

	$sql = "SELECT * 
		FROM " . PA_FILES_TABLE . "
		WHERE file_approved = 1
		ORDER BY file_dls DESC 
		LIMIT 0,5";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not create top downloads module.', '', __LINE__, __FILE__, $sql);
	}
	
	$dloads = false;
	while ($row = $db->sql_fetchrow($result))
	{
		$dloads = true;
		$template->assign_block_vars('files', array(
			'FILENAME' => '<a href="' . append_sid('dload.'.$phpEx.'?action=' . (($row['file_license']) ? 'license&amp;license_id=' . $row['file_license'] : 'download') . '&amp;file_id=' . $row['file_id']) . '" class="gensmall">' . $row['file_name'] . '</a>',
			'INFO' => $row['file_dls'])
		);
	}
	$db->sql_freeresult($result);

	if (empty($dloads))
	{
		$template->assign_block_vars('nofiles', array(
			'L_NONE' => $lang['None'])
		);
	
	}
	
	return;
} 

function poll_block($polls)
{
	global $db, $template;
	
	$sql = "SELECT t.*, vd.*
		FROM " . TOPICS_TABLE . " AS t, " . VOTE_DESC_TABLE  . " AS vd
		WHERE t.forum_id IN (" . $polls . ") 
			AND t.topic_status <> 1 
			AND t.topic_status <> 2 
			AND t.topic_vote = 1 
			AND t.topic_id = vd.topic_id
		ORDER BY RAND() LIMIT 1";
	if (!$query = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not query poll information.', '', __LINE__, __FILE__, $sql);
	}

	$result = $db->sql_fetchrow($query);

	if (!empty($result))
	{
		$sql = "SELECT *
			FROM " . VOTE_RESULTS_TABLE . "
			WHERE vote_id = " . $result['vote_id'] . "
			ORDER BY vote_option_id";
		if (!$query = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not query vote result information.', '', __LINE__, __FILE__, $sql);
		}

		while ($row = $db->sql_fetchrow($query))
		{
			$result['options'][] = $row;
		}	
		$db->sql_freeresult($query);
	}

	return $result;
}

function quote_block()
{
	global $board_config, $template, $lang, $phpbb_root_path; 

	$template->assign_block_vars('displayquote', array() );

	$language = $board_config['default_lang']; 
	if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/quotes.txt') ) { $language = 'english'; }
	
	srand ((double) microtime() * 1000000); 
	$zitate = file($phpbb_root_path . 'language/lang_' . $language . '/quotes.txt'); 
	$i = sizeof($zitate)-1; 
	$quote = $zitate[rand(0,$i)];

	$template->assign_vars(array(
		'L_QUOTE' => $lang['Quote'], 
	    'QUOTE' => $quote)
	);
		
	return;
} 

function random_user_block() 
{ 
	global $db, $template, $board_config, $lang, $phpEx; 

	$template->assign_block_vars('displayrandomuser', array() );

	$sql = "SELECT user_id, username, user_level, user_avatar, user_avatar_type, user_allowavatar, user_posts, user_regdate, user_from, user_lastlogon, user_totallogon, user_allow_viewonline
		FROM " . USERS_TABLE . " 
		WHERE user_active = 1 
			AND user_id <> " . ANONYMOUS . " 
		ORDER BY RAND() LIMIT 1"; 
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not create random user module.', '', __LINE__, __FILE__, $sql);
	} 
	
	$randomuserrow = $db->sql_fetchrow($result);
	
	$random_name = '<b><a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $randomuserrow['user_id']) . '" class="gensmall">' . username_level_color($randomuserrow['username'], $randomuserrow['user_level'], $randomuserrow['user_id']). '</a></b>'; 
	$random_avatar = ''; 
	if ( $randomuserrow['user_avatar_type'] && $randomuserrow['user_allowavatar'] ) 
	{ 
		switch( $randomuserrow['user_avatar_type'] ) 
		{ 
			case USER_AVATAR_UPLOAD: 
				$random_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $randomuserrow['user_avatar'] . '" alt="" title="" />' : ''; 
				break; 
			case USER_AVATAR_REMOTE: 
				$random_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $randomuserrow['user_avatar'] . '" alt="" title="" />' : ''; 
				break; 
			case USER_AVATAR_GALLERY: 
				$random_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $randomuserrow['user_avatar'] . '" alt="" title="" />' : ''; 
				break; 
		} 
	} 
	
	if ( ( !$random_avatar ) && ( ( $board_config['default_avatar_set'] == 1 ) || ( $board_config['default_avatar_set'] == 2 ) ) && ( $board_config['default_avatar_users_url'] ) )
	{
		$random_avatar = '<img src="' . $board_config['default_avatar_users_url'] . '" alt="" title="" />';
	}
	
	$random_posts = $randomuserrow['user_posts']; 
	$random_visits = $randomuserrow['user_totallogon']; 
	$random_location = $randomuserrow['user_from']; 
	
	$template->assign_vars(array(
		'L_RANDOM_USER' => $lang['Random_user'], 
		'L_RANDOM_USER_EXPLAIN' => $lang['Random_user_explain'], 
		'L_LOCATION' => $lang['Location'], 
		'L_LAST_VISIT' => $lang['Last_logon'], 
		'L_VISITS' => $lang['Number_of_visit'], 
		'L_JOINED' => $lang['Joined'], 

		'RANDOM_NAME' => $random_name, 
		'RANDOM_AVATAR' => $random_avatar, 
		'RANDOM_POSTS' => $random_posts, 
		'RANDOM_LAST_VISIT' => ($randomuserrow['user_level'] == ADMIN || (!$board_config['hidde_last_logon'] && $randomuserrow['user_allow_viewonline'])) ? (($randomuserrow['user_lastlogon']) ? create_date($board_config['default_dateformat'], $randomuserrow['user_lastlogon'], $board_config['board_timezone']) : $lang['Never_last_logon']) : $lang['Hidden_email'], 
		'RANDOM_VISITS' => $random_visits, 
		'RANDOM_JOINED' => create_date($lang['DATE_FORMAT'], $randomuserrow['user_regdate'], $board_config['board_timezone']),
		'RANDOM_LOCATION' => $random_location) 
	);

	$db->sql_freeresult($result);

	return; 
} 

function random_game_block()
{
	global $userdata, $db, $template, $phpbb_root_path, $lang, $board_config, $phpEx, $SID;

	$template->assign_block_vars('displayrandomgame', array() );

	$language = $board_config['default_lang'];
	if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_activity.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_activity.' . $phpEx);

	$where_clause = ( $userdata['user_level'] == ADMIN ) ? '' : ' WHERE disabled = 1';

   	$sql = "SELECT * 
   		FROM " . iNA_GAMES . " 
		$where_clause
		ORDER BY RAND() 
   		LIMIT 1";
	if (!$result = $db->sql_query($sql))
	{
	   	message_die(GENERAL_ERROR, 'Could not create random game module.', '', __LINE__, __FILE__, $sql);
	}
	
	$random_game = $db->sql_fetchrow($result);

	if ( $random_game['game_id'] )
	{	
		include_once($phpbb_root_path . 'includes/functions_amod_plus.'.$phpEx);

		$image_link = GameArrayLink($random_game['game_id'], $random_game['game_parent'], $random_game['game_popup'], $random_game['win_width'], $random_game['win_height'], 2, ''); 
		$game_link = CheckGameImages($random_game['game_name'], $random_game['proper_name']);
	}
	else
	{
		$image_link = $game_link = '';
	}
	
	$template->assign_vars(array(
		'L_RANDOM_GAME' => $lang['random_link'], 
		'RANDOM_GAME' => $image_link . $game_link . '</a><br /><b>' . $random_game['proper_name'] . '</b><br /><span class="gensmall">' . $random_game['game_desc'] . '</span>') 
	);
		
	$db->sql_freeresult($result);

	return;
} 

function random_pic_block()
{
	global $db, $template, $board_config, $phpbb_root_path, $phpEx, $lang;

	include($phpbb_root_path . 'mods/album/album_functions.'.$phpEx);

	$sql = "SELECT c.*, COUNT(p.pic_id) AS count
		FROM ". ALBUM_CAT_TABLE ." AS c
			LEFT JOIN ". ALBUM_TABLE ." AS p ON c.cat_id = p.pic_cat_id
		WHERE cat_id <> 0
		GROUP BY cat_id
		ORDER BY cat_order ASC";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain categories list for random pic module.', '', __LINE__, __FILE__, $sql);
	}
	
	$catrows = array();
	
	while( $row = $db->sql_fetchrow($result) )
	{
		$album_user_access = album_user_access($row['cat_id'], $row, 1, 0, 0, 0, 0, 0); // VIEW
		if ($album_user_access['view'] == 1)
		{
			$catrows[] = $row;
		}
	}
			
	$allowed_cat = ''; // For Recent Public Pics below
	
	for ($i = 0; $i < sizeof($catrows); $i++)
	{
		$allowed_cat .= ($allowed_cat == '') ? $catrows[$i]['cat_id'] : ',' . $catrows[$i]['cat_id'];
	}

	$db->sql_freeresult($result);
	
	if (!empty($allowed_cat)) 
	{	
		$template->assign_block_vars('displayindexphoto', array());

		$sql = "SELECT p.pic_id, p.pic_title, p.pic_user_id, p.pic_time, p.pic_desc, p.pic_approval, u.username, u.user_level, COUNT(ac.comment_pic_id) AS total_comments
			FROM " . ALBUM_TABLE . " AS p
				LEFT JOIN " . USERS_TABLE . " AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_CAT_TABLE ." AS ct ON p.pic_cat_id = ct.cat_id
				LEFT JOIN " . ALBUM_COMMENT_TABLE . " AS ac ON p.pic_id = ac.comment_pic_id
			WHERE p.pic_cat_id IN ($allowed_cat) 
				AND ( p.pic_approval = 1 OR ct.cat_approval = 0 )
			GROUP BY pic_id
			ORDER BY RAND() 
			LIMIT 1";
		if (!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not create random pic module.', '', __LINE__, __FILE__, $sql);
		}
		$picrow = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		
		$pic_poster = '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $picrow['pic_user_id']) . '" class="gensmall">' . username_level_color($picrow['username'], $picrow['user_level'], $picrow['pic_user_id']) . '</a>';
			
		$pic_link = '<a href="';
		$pic_link .= ( $picrow['pic_id'] ) ? (( $album_config['fullpic_popup'] ) ? append_sid('album_pic.'.$phpEx.'?pic_id=' . $picrow['pic_id']) . '" target="_blank">' : append_sid('album_showpage.'.$phpEx.'?pic_id=' . $picrow['pic_id']) . '">' ) : '#">';

		$template->assign_vars(array(
			'PIC_IMAGE' => ( $picrow['pic_id'] ) ? append_sid('album_thumbnail.'.$phpEx.'?pic_id=' . $picrow['pic_id']) : $phpbb_root_path . 'images/spacer.gif',
			'PIC_TITLE' => $picrow['pic_title'],
			'PIC_POSTER' => $pic_poster,
			'PIC_COMMENTS' => $picrow['total_comments'],
			'PIC_TIME' => ( $picrow['pic_time'] ) ? create_date($board_config['default_dateformat'], $picrow['pic_time'], $board_config['board_timezone']) : '',
			'PIC_DESCR' => $picrow['pic_desc'],
		
			'U_PIC_LINK' => $pic_link,
			'U_PIC_COMMENT' => append_sid('album_showpage.'.$phpEx.'?pic_id=' . $picrow['pic_id']))
		);
	}
		
	return;
} 


function donors_block()
{
	global $db, $phpEx, $theme, $lang, $board_config, $template, $images;
	
	// Show All
	$count = 0;
	$board_config['dislay_x_donors'] = (!empty($board_config['dislay_x_donors'])) ? $board_config['dislay_x_donors'] : 0;

	$sql = "SELECT COUNT(*) 
		FROM " . ACCT_HIST_TABLE . " 
		WHERE comment LIKE 'Donation from%' 
		GROUP BY user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forum donors information', '', __LINE__, __FILE__, $sql);
	}
	
	if($row = $db->sql_fetchrow($result))
	{
		$count = $row['COUNT(*)'];		
	}
	
	$orderby = "ORDER BY date DESC";
	$selectcolums = "MAX(a.lw_date) AS date, SUM(a.lw_money) AS lw_money, a.MNY_CURRENCY, u.*";
	if ($board_config['list_top_donors'])
	{
		$orderby = "ORDER BY lw_money DESC";
		$selectcolums = "SUM(a.lw_money) AS lw_money, MAX(a.lw_date) AS date, a.MNY_CURRENCY, u.*";
	}	

	$sql = "SELECT $selectcolums 
		FROM " . ACCT_HIST_TABLE . " a, " . USERS_TABLE . " u 
		WHERE a.comment like 'Donation from%' 
			AND u.user_id = a.user_id 
		GROUP BY a.user_id
	 	$orderby 
		LIMIT " . $board_config['dislay_x_donors'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forum donors information', '', __LINE__, __FILE__, $sql);
	}
	
	$last_donors = '';
	while( $row = $db->sql_fetchrow($result) )
	{
		if($row['user_id'] == ANONYMOUS)
		{
			$last_donors .= '<b>' . $lang['LW_ANONYMOUS_DONOR'] . '</b> (' . sprintf("%.2f", $row['lw_money']) . ' ' . $row['MNY_CURRENCY'] . ')<br />';
		}
		else
		{
			$last_donors .= '<a class="gensmall" href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a> (' . sprintf("%.2f", $row['lw_money']) . ' ' . $row['MNY_CURRENCY'] . ')<br />';
		}
	}
	$db->sql_freeresult($result);

	if ($count > $board_config['dislay_x_donors'])
	{
		$last_donors .= '<a href="' . append_sid("profile_donations.$phpEx?mode=viewall") . '" class="gensmall">' . $lang['LW_MORE_DONORS'] . '</a><br />';
	}
	
	if ($count == 0)
	{
		$last_donors .= $lang['LW_NO_DONORS_YET'];
	}

	$donordesc = '';
	if( strlen($board_config['donate_description']) > 0)
	{
		if(strlen($donordesc) <= 0)
		{
			$donordesc .= '';
		}
		$donordesc .= sprintf($lang['Donations_for'], $board_config['donate_description']) . '<br />';
	}
	
	if( intval($board_config['donate_cur_goal']) > 0)
	{
		//format can only be 2004/08/04 yyyy/mm/dd
		$donorswhere = $donatetime = '';
		$starttime = $endtime = 0;
		
		if(strlen($board_config['donate_start_time']) == 10)
		{
			$starttime = mktime(0, 0, 0, substr($board_config['donate_start_time'], 5, 2), substr($board_config['donate_start_time'], 8, 2), substr($board_config['donate_start_time'], 0, 4) );
		}
		
		if(strlen($board_config['donate_end_time']) == 10)
		{
			$endtime = mktime(0, 0, 0, substr($board_config['donate_end_time'], 5, 2), substr($board_config['donate_end_time'], 8, 2), substr($board_config['donate_end_time'], 0, 4) );

			$donatetime .= sprintf($lang['Donations_ending'], $board_config['donate_end_time']) . '<br /><br />';
		}	
		
		$donordesc .= $donatetime;	
		
		if($starttime > 0)
		{
			if($endtime <= $starttime)
			{
				$donorswhere = ' AND a.lw_date >= ' . $starttime;
			}
			else
			{
				$donorswhere = ' AND a.lw_date >= ' . $starttime . ' AND a.lw_date <= ' . $endtime;
			}
		}
		
		$curcollected = 0;
		
		$sql = "SELECT SUM(a.lw_money) 
			FROM " . ACCT_HIST_TABLE . " a, " . USERS_TABLE . " u 
			WHERE a.comment LIKE 'Donation from%' 
				AND u.user_id = a.user_id 
			$donorswhere";
		if($result = $db->sql_query($sql))
		{
			if($row = $db->sql_fetchrow($result))
			{
				$curcollected = $row["SUM(a.lw_money)"];
			}
		}
		
		if(strlen($donordesc) <= 0)
		{
			$donordesc .= '';
		}
		$donordesc .= sprintf($lang['LW_WE_HAVE_COLLECT'], $curcollected, $board_config['donate_cur_goal'] . ' ' . $board_config['paypal_currency_code']) . '<br /><br />';
	}
	
	if( strlen($donordesc) > 0)
	{
		$donordesc .= '<a class="gensmall" href="' . append_sid("profile_donations.$phpEx?mode=viewcurrent") . '">' . $lang['LW_CURRENT_DONORS'] . '</a><br /><br />';
	}

	$board_config['dislay_x_donors'] = (!empty($board_config['dislay_x_donors'])) ? $board_config['dislay_x_donors'] : '';
	if($board_config['list_top_donors'])
	{
		$donationtitle = sprintf($lang['L_LW_TOP_DONORS_TITLE'], $board_config['dislay_x_donors']);
	}
	else
	{
		$donationtitle = sprintf($lang['L_LW_LAST_DONORS'], $board_config['dislay_x_donors']);
	}
	
	$template->assign_block_vars('displaytopdonors', array(
		'L_LAST_DONORS' => $donationtitle,
		'GOAL' => $donordesc,
		'L_DONATE' => $lang['LW_DONATION_TO_HELP'],
		'LAST_DONORS' => $last_donors,
		'PAYPAL_IMG' => $images['paypal_donate'],
		
		'U_DONATE' => append_sid('donate.'.$phpEx))
	);
	
  	return;
}


function tpl_block()
{
	global $db, $template;
	
	return;
} 

?>
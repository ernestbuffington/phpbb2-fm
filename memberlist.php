<?php
/** 
*
* @package phpBB2
* @version $Id: memberlist.php,v 1.36.2.10 2004/07/11 16:46:15 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/chinese.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_VIEWMEMBERS);
init_userprefs($userdata);
//
// End session management
//


if(isset($HTTP_POST_VARS['letter']))
{
	$by_letter = ($HTTP_POST_VARS['letter']) ? $HTTP_POST_VARS['letter'] : 'all';
}
else if(isset($HTTP_GET_VARS['letter']))
{
	$by_letter = ($HTTP_GET_VARS['letter']) ? $HTTP_GET_VARS['letter'] : 'all';
}

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = 'joined';
}

if(isset($HTTP_POST_VARS['order']))
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if(isset($HTTP_GET_VARS['order']))
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'ASC';
}

//
// Memberlist sorting
//
$mode_types_text = array($lang['Last_logon'], $lang['Sort_Joined'], $lang['Sort_Username'], $lang['Gender'], $lang['Age'], $lang['Profile_photo'], $lang['Location'], $lang['Country_Flag'], $lang['Sort_Posts'], $lang['Sort_Email'], $lang['Level'], $lang['RankFAQ_Block_Title'], $lang['Sort_Website'], $board_config['points_name'], $lang['Karma'] . '  ' . $lang['Applaud'], $lang['Karma'] . ' ' . $lang['Smite'], $lang['Sort_Top_Ten']);
$mode_types = array('lastlogon', 'joined', 'username', 'gender', 'age', 'photo', 'location', 'flag', 'posts', 'email', 'level', 'rank', 'website', 'points', 'karma_plus', 'karma_minus', 'topten');

$select_sort_mode = '<select name="mode">';
for($i = ($userdata['user_level'] == ADMIN ) ? 0 : 1; $i < count($mode_types_text); $i++)
{
	$selected = ( $mode == $mode_types[$i] ) ? ' selected="selected"' : '';
	$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
}
$select_sort_mode .= '</select>';

$select_sort_order = '<select name="order">';
if($sort_order == 'ASC')
{
	$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$select_sort_order .= '</select>';

//
// Generate page
//
$page_title = $lang['Memberlist'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'memberlist_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_EMAIL' => $lang['Email'],
	'L_WEBSITE' => $lang['Website'],
	'L_LEVEL' => $lang['Level'],
	'L_FROM' => $lang['Location'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],
	'L_JOINED' => $lang['Joined'], 
	'L_POSTS' => $lang['Posts'], 
	'L_KARMA' => ( $board_config['allow_karma'] ) ? '<th class="thTop" nowrap="nowrap">' . $lang['Karma'] . '</th>' : '', 
	'L_LOGON' => $lang['Last_logon'], 
	'L_POINTS' => $board_config['points_name'],
	'L_USER_RANK' => $lang['RankFAQ_Block_Title'], 
	'L_POST_TIME' => $lang['Last_Post'] . ' ' . $lang['Time'], 

	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_MODE_ACTION' => append_sid("memberlist.$phpEx"))
);

switch( $mode )
{
	case 'joined':
		$order_by = "user_regdate $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'lastlogon': 
	    $order_by = ($userdata['user_level'] == ADMIN ) ? "user_lastlogon $sort_order LIMIT $start, " . $board_config['topics_per_page'] : "username $sort_order LIMIT $start, " . $board_config['topics_per_page']; 
	    break; 
	case 'username':
		$order_by = "username $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'gender':
		$order_by = "user_gender $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'age': 
		$order_by = "user_birthday $sort_order LIMIT $start," . $board_config['topics_per_page']; 
		break;
	case 'photo':
		$order_by = "user_photo $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'location':
		$order_by = "user_from $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'flag':
		$order_by = "user_from_flag $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'posts':
		$order_by = "user_posts $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'email':
		$order_by = "user_email $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'level':
		$order_by = "user_level $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'rank':
		$order_by = "user_rank $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'website':
		$order_by = "user_website $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'points':
		$order_by = "user_points $sort_order LIMIT $start," . $board_config['topics_per_page'];
		break;
	case 'karma_plus':
		$order_by = "karma_plus $sort_order LIMIT $start," . $board_config['topics_per_page'];
		break;
	case 'karma_minus':
		$order_by = "karma_minus $sort_order LIMIT $start," . $board_config['topics_per_page'];
		break;
	case 'topten':
		$order_by = "user_posts DESC LIMIT 10";
		break;
	default:
		$order_by = "user_regdate $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
}

//
// Set per-letter selection
//
$others_sql = $select_letter = '';
for ($i = 97; $i <= 122; $i++)
{
	$others_sql .= " AND username NOT LIKE '" . chr($i) . "%' ";
	$select_letter .= ( $by_letter == chr($i) ) ? '<td class="row2" align="center"><b>' . chr($i) . '</b></td>' : '<td class="row1" align="center"><a href="' . append_sid("memberlist.$phpEx?letter=" . chr($i) . "&amp;mode=$mode&amp;order=$sort_order&amp;start=$start") . '" class="nav">' . chr($i) . '</a></td>';
}
$select_letter .= ( $by_letter == 'others' ) ? '<td class="row2" align="center"><b>' . $lang['Others'] . '</b></td>' : '<td class="row1" align="center"><a href="' . append_sid("memberlist.$phpEx?letter=others&amp;mode=$mode&amp;order=$sort_order&amp;start=$start") . '" class="nav">' . $lang['Others'] . '</a></td>';
$select_letter .= ( $by_letter == 'all' ) ? '<td class="row2" align="center"><b>' . $lang['All'] . '</b></td>' : '<td class="row1" align="center"><a href="' . append_sid("memberlist.$phpEx?letter=all&amp;mode=$mode&amp;order=$sort_order&amp;start=$start") . '" class="nav">' . $lang['All'] . '</a></td>';

$template->assign_vars(array(
	'L_SORT_PER_LETTER' => $lang['Sort_per_letter'],
	'S_LETTER_SELECT' => $select_letter,
	'S_LETTER_HIDDEN' => '<input type="hidden" name="letter" value="' . $by_letter . '">')
);

if ($by_letter == 'all')
{
	$letter_sql = "";
}
else if ($by_letter == 'others')
{
	$letter_sql = $others_sql;
}
else
{
	$letter_sql = " AND username LIKE '$by_letter%' ";
}


$sql = "SELECT u.*, f.flag_name
	FROM " . USERS_TABLE . " u
		LEFT JOIN " . FLAG_TABLE . " f ON u.user_from_flag = f.flag_image
	WHERE user_id <> " . ANONYMOUS . "$letter_sql
      AND user_active = 1	
	ORDER BY $order_by";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query users', '', __LINE__, __FILE__, $sql);
}

$memberrow = array();
if ($row = $db->sql_fetchrow($result))
{
	do
	{
		$memberrow[] = $row;
		$poster_id_sql .= ($row['user_rank']) ? '' : ',' . $row['user_id'];
	}
	while ($row = $db->sql_fetchrow($result));
	$db->sql_freeresult($result);

	$total_members = sizeof($memberrow);
}

//
// Ranks
//
$sql = "SELECT * 
   FROM " . RANKS_TABLE . " 
   ORDER BY rank_special, rank_min DESC"; 
if ( !($result = $db->sql_query($sql)) ) 
{ 
   message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql); 
} 

$ranksrow = $poster_group = array();
$rank_group_id_sql = '';
while ( $row = $db->sql_fetchrow($result) )
{
	if ( $row['rank_special'] )
	{
		$ranksrow[-1][$row['rank_id']] = $row;
	}
	else
	{
		$ranksrow[$row['rank_group']][] = $row;
		$rank_group_id_sql .= ($row['rank_group'] > 0) ? ',' . $row['rank_group'] : '';
		$ranksrow[$row['rank_group']]['count']++;
	}
}
$db->sql_freeresult($result);

if ( !empty($poster_id_sql) && !empty($rank_group_id_sql) )
{
	$rank_group_id_sql = substr($rank_group_id_sql, 1);
	$poster_id_sql = substr($poster_id_sql, 1);
	$sql = "SELECT ug.user_id, ug.group_id
		FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
		WHERE ug.user_id IN ( $poster_id_sql )
			AND ug.group_id IN ( $rank_group_id_sql )
			AND g.group_id = ug.group_id
			AND g.group_single_user = 0
		ORDER BY g.group_rank_order DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain poster group information.', '', __LINE__, __FILE__, $sql);
	}

	while ( $row = $db->sql_fetchrow($result) )
	{
		$poster_group[$row['user_id']] = $row['group_id'];
	}
	$db->sql_freeresult($result);
}

for($i = 0; $i < $total_members; $i++)
{
	switch ($memberrow[$i]['user_level'])
	{
		case 1:
			$level = $lang['Admin'];
			break;
		case 2:
			$level = $lang['Super_Mod'];
			break;
		case 3:
			$level = $lang['Mod'];
			break;
		default:
			$level = $lang['User'];
			break;									
	}
		
	$user_id = $memberrow[$i]['user_id'];
	$username = username_level_color($memberrow[$i]['username'], $memberrow[$i]['user_level'], $user_id);
	$level = username_level_color($level, $memberrow[$i]['user_level'], $user_id);	
	$from = ( !empty($memberrow[$i]['user_from']) ) ? username_level_color($memberrow[$i]['user_from'], $memberrow[$i]['user_level'], $user_id) : '&nbsp;';
	$joined = username_level_color(create_date($lang['DATE_FORMAT'], $memberrow[$i]['user_regdate'], $board_config['board_timezone']), $memberrow[$i]['user_level'], $user_id);
	$posts = username_level_color($memberrow[$i]['user_posts'], $memberrow[$i]['user_level'], $user_id);
	$user_points = username_level_color($memberrow[$i]['user_points'], $memberrow[$i]['user_level'], $user_id);
	$karma = username_level_color('+' . $memberrow[$i]['karma_plus'] . '/-' . $memberrow[$i]['karma_minus'], $memberrow[$i]['user_level'], $user_id);
	$photo = ( $memberrow[$i]['user_photo'] ) ? '<a href="profile.php?mode=viewprofile&amp;u=' . $user_id . '#photo"><img src="' . $images['icon_mini_photo'] . '" alt="' . $lang['Profile_photo'] . '" title="' . $lang['Profile_photo'] . '" /></a>' : '';
	$flag = ( $memberrow[$i]['user_from_flag'] ) ? '&nbsp;<img src="' . $phpbb_root_path . 'images/flags/' . $memberrow[$i]['user_from_flag'] . '" alt="' . $lang['Country_Flag'] . ': ' . $memberrow[$i]['flag_name'] . '" title="' . $lang['Country_Flag'] . ': ' . $memberrow[$i]['flag_name'] . '" />' : '';
    
	$chinese = $u_chinese = $chinese_img = $zodiac = $u_zodiac = $zodiac_img = $poster_age = $gender_image = $user_rank = $rank_image = ''; 

 	if ( $memberrow[$i]['user_rank'] ) 
    { 
		$user_rank = $ranksrow[-1][$memberrow[$i]['user_rank']]['rank_title'];
		$rank_image = ( $ranksrow[-1][$memberrow[$i]['user_rank']]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[-1][$memberrow[$i]['user_rank']]['rank_image'] . '" alt="' . $user_rank . '" title="' . $user_rank . '" /><br />' : '';
    } 
	else if ( isset($poster_group[$memberrow[$i]['user_id']]) )
	{
		$g = $poster_group[$memberrow[$i]['user_id']];
		for($j = 0; $j < $ranksrow[$g]['count']; $j++)
		{
			if ( $memberrow[$i]['user_posts'] >= $ranksrow[$g][$j]['rank_min'] )
			{
				$user_rank = $ranksrow[$g][$j]['rank_title'];
				$rank_image = ( $ranksrow[$g][$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[$g][$j]['rank_image'] . '" alt="' . $user_rank . '" title="' . $user_rank . '" /><br />' : '';
				break;	
			}	
		}
	}
	else
	{
		for($j = 0; $j < $ranksrow[0]['count']; $j++)
		{
			if ( $memberrow[$i]['user_posts'] >= $ranksrow[0][$j]['rank_min'] )
			{
				$user_rank = $ranksrow[0][$j]['rank_title'];
				$rank_image = ( $ranksrow[0][$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[0][$j]['rank_image'] . '" alt="' . $user_rank . '" title="' . $user_rank . '" /><br />' : '';
				break;
			}
		}
	}

	//
	// Last Post Time
	//
	$post_time_sql = "SELECT post_time
		FROM " . POSTS_TABLE . "
		WHERE poster_id = " . $user_id . "
		ORDER BY post_time DESC
		LIMIT 1";
	if ( !($post_time_result = $db->sql_query($post_time_sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting user last post time information', '', __LINE__, __FILE__, $post_time_sql);
	}
	$post_time_row = $db->sql_fetchrow($post_time_result);

	$last_post_time = username_level_color((isset($post_time_row['post_time']) ? create_date($board_config['default_dateformat'], $post_time_row['post_time'], $board_config['board_timezone']) : $lang['Never_last_logon']), $memberrow[$i]['user_level'], $user_id);
		
	//
	// Online / Offline / Hidden 
	//
	if ( $memberrow[$i]['user_session_time'] >= ( time() - ($board_config['whosonline_time'] * 60) ) )
	{
		if ( $memberrow[$i]['user_allow_viewonline'] )
		{
			$online_status_img = '<a href="' . append_sid("viewonline.$phpEx") . '"><img src="' . $images['Online'] . '" alt="' . sprintf($lang['is_online'], $memberrow[$i]['username']) . '" title="' . sprintf($lang['is_online'], $memberrow[$i]['username']) . '" /></a>';
		}
		else if ( $userdata['user_level'] == ADMIN || $userdata['user_id'] == $user_id )
		{
			$online_status_img = '<a href="' . append_sid("viewonline.$phpEx") . '"><img src="' . $images['Offline'] . '" alt="' . sprintf($lang['is_hidden'], $memberrow[$i]['username']) . '" title="' . sprintf($lang['is_hidden'], $memberrow[$i]['username']) . '" /></a>';
		}
		else
		{
			$online_status_img = '<img src="' . $images['Offline'] . '" alt="' . sprintf($lang['is_offline'], $memberrow[$i]['username']) . '" title="' . sprintf($lang['is_offline'], $memberrow[$i]['username']) . '" />';
		}
	}
	else
	{
		$online_status_img = '<img src="' . $images['Offline'] . '" alt="' . sprintf($lang['is_offline'], $memberrow[$i]['username']) . '" title="' . sprintf($lang['is_offline'], $memberrow[$i]['username']) . '" />';
	}
	
	if ( !empty($memberrow[$i]['user_viewemail']) || $userdata['user_level'] == ADMIN )
	{
		$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $user_id) : 'mailto:' . $memberrow[$i]['user_email'];
		$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
	}
	else
	{
		$email_img = '&nbsp;';
	}

	$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id");
	$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

	$temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$user_id");
	$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_buddy_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';

	$www_img = ( $memberrow[$i]['user_website'] ) ? '<a href="' . $memberrow[$i]['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '';
	$www = ( $memberrow[$i]['user_website'] ) ? '<a href="' . $memberrow[$i]['user_website'] . '" target="_userwww">' . $lang['Visit_website'] . '</a>' : '';

	if ( !empty($memberrow[$i]['user_gender'])) 
	{ 
		switch ($memberrow[$i]['user_gender']) 
		{ 
	    	case 1: 
	    		$gender_image = '<img src="' . $images['icon_minigender_male'] . '" alt="' . $lang['Gender'] .  ': ' . $lang['Male'] . '" title="' . $lang['Gender'] .  ': ' . $lang['Male'] . '" />'; 
	    		break; 
	        case 2: 
	        	$gender_image = '<img src="' . $images['icon_minigender_female'] . '" alt="' . $lang['Gender'] . ': ' . $lang['Female'] . '" title="' . $lang['Gender'] . ': ' . $lang['Female'] . '" />'; 
	        	break; 
			default: 
				$gender_image = ''; 
	        	break; 
			} 
	} 

    $this_year = create_date('Y', time(), $board_config['board_timezone']); 
    $this_date = create_date('md', time(), $board_config['board_timezone']); 
    if ( $memberrow[$i]['user_birthday'] != 999999 ) 
	{ 
		$poster_birthdate = realdate('md', $memberrow[$i]['user_birthday']); 
	    $n = 0; 
	    while ($n < 26) 
	    { 
			if ($poster_birthdate >= $zodiacdates[$n] & $poster_birthdate <= $zodiacdates[$n+1]) 
			{ 
		    	$zodiac = $lang[$zodiacs[($n / 2)]]; 
		        $u_zodiac = $images[$zodiacs[($n / 2)]]; 
		        $zodiac_img = '<img src="' . $u_zodiac . '" alt="' . $lang['Zodiac'] . ': ' . $zodiac . '" title="' . $lang['Zodiac'] . ': ' . $zodiac . '" />'; 
		        $n = 26; 
			} 
			else 
			{ 
				$n = $n + 2; 
			} 
		} 
	    $poster_age = $this_year - realdate ('Y', $memberrow[$i]['user_birthday']); 
	    if ($this_date < $poster_birthdate) 
	    {
	    	$poster_age--; 
	    }
	    $poster_age = '<br />[' . $poster_age . ' ' . $lang['Years_old'] . ']'; 
	    $chinese = get_chinese_year (realdate('Ymd', $memberrow[$i]['user_birthday'])); 
	    $u_chinese = $images[$chinese]; 
	    $chinese_img = ($chinese == 'Unknown') ? '' : '<img src="' . $u_chinese . '" alt="' . $lang['Chinese_zodiac'] . ': ' . $lang[$chinese] . '" title="' . $lang['Chinese_zodiac'] . ': ' . $lang[$chinese] . '" />'; 
	} 

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('memberrow', array(
		'ROW_CLASS' => $row_class,
		'USERNAME' => $username,
		'FROM' => $from,
		'FLAG' => $flag,
		'JOINED' => $joined,
		'POSTS' => $posts,
		'PHOTO' => $photo, 
		'AVATAR_IMG' => $poster_avatar,
		'PROFILE' => $profile, 
		'PM_IMG' => ( ( $userdata['user_level'] == ADMIN ) ? '<a href="' . append_sid("admin/user_prune_delete.$phpEx?mode=user_id&amp;del_user=$user_id&amp;sid=" . $userdata['session_id']) . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" align="baseline" /></a> ' : '') . $pm_img,
		'EMAIL_IMG' => $email_img,
		'WWW_IMG' => $www_img,
		'LEVEL' => $level,
		'LAST_LOGON' => username_level_color( (( $userdata['user_level'] == ADMIN || ( !$board_config['hidde_last_logon'] && $memberrow[$i]['user_allow_viewonline']) ) ? ( ( $memberrow[$i]['user_lastlogon'] ) ? create_date($board_config['default_dateformat'], $memberrow[$i]['user_lastlogon'], $board_config['board_timezone']) : $lang['Never_last_logon']) : $lang['Hidden_email']), $memberrow[$i]['user_level'], $user_id),
		'POSTER_GENDER' => $gender_image, 	
		'POINTS' => $user_points,
		'USER_RANK' => $user_rank, 
		'USER_RANK_IMG' => $rank_image, 
		'VIEW_ONLINE' => $online_status_img, 
		'LAST_POST_TIME' => $last_post_time,
		'POSTER_AGE' => $poster_age,
		'ZODIAC_IMG' => $zodiac_img,
		'CHINESE_IMG' => $chinese_img,
		'KARMA' => ( $board_config['allow_karma'] ) ? '<td class="' . $row_class . '" align="center" valign="middle"><span class="gensmall">' . $karma . '</span></td>' : '',

		'U_VIEWPROFILE' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user_id"))
	);
}

if ( $mode != 'topten' || $board_config['topics_per_page'] < 10 )
{
	$sql = "SELECT COUNT(*) AS total
		FROM " . USERS_TABLE . "
            WHERE user_id <> " . ANONYMOUS . "$letter_sql" . " 
		AND user_active = 1";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting total users', '', __LINE__, __FILE__, $sql);
	}

	if ( $total = $db->sql_fetchrow($result) )
	{
		$total_members = $total['total'];

		$pagination = generate_pagination("memberlist.$phpEx?mode=$mode&amp;order=$sort_order&amp;letter=$by_letter", $total_members, $board_config['topics_per_page'], $start). '&nbsp;';
	}
}
else
{
	$pagination = '&nbsp;';
	$total_members = 10;
}

$template->assign_vars(array(
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_members / $board_config['topics_per_page'] )), 
	'TOTAL_USERS' => $total_members . ' ' . $lang['Memberlist'],

	'L_GOTO_PAGE' => $lang['Goto_page'])
);

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
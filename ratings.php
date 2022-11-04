<?php
/** 
*
* @package phpBB2
* @version $Id: ratings.php v1.1.0 2003/05/19 17:49:34 gentle_giantExp $
* @copyright (c) 2002 Web Centre Ltd
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
$userdata = session_pagestart($user_ip, PAGE_RATINGS);
init_userprefs($userdata);
//
// End session management
//
	

//
// VALIDATE GET VARS
//
$type = ( empty($HTTP_GET_VARS['type']) ) ? l : $HTTP_GET_VARS['type'];
$forum_id = intval($HTTP_GET_VARS['forum_id']);
$postedby = intval($HTTP_GET_VARS['postedby']);
$ratedby = intval($HTTP_GET_VARS['ratedby']);
$ratingsby = ( !empty($HTTP_GET_VARS['ratingsby']) ) ? intval($HTTP_GET_VARS['ratingsby']) : 1;
$sql_limit = 20; // Number of posts/topics you want displayed 


//
// include language file
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_rating.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_rating.' . $phpEx);
include($phpbb_root_path . 'includes/functions_rating.'.$phpEx);


$rating_config = get_rating_config('1,2,6,8,9,10,11');
if ( $rating_config[1] == 0 )
{
	message_die(GENERAL_MESSAGE, $lang['Rating_deactivated']); 
}


//
// RATINGS_BY DROPDOWN BOX
//
if ( $rating_config[11] == 0 || !$userdata['session_logged_in'] )
{
	$ratings_by = array(
		1 => array('title' => $lang['Rating_everyone'], 'selected' => ' selected="selected"')
	);
	$l_include_by = $lang['Rating_include_by'];
}
else
{
	$ratings_by = array(
		1 => array('title' => $lang['Rating_all_but_ignore'], 'selected' => ''),
		2 => array('title' => $lang['Rating_everyone'], 'selected' => ''),
		3 => array('title' => $lang['Rating_buddies_only'], 'selected' => '')
	);
	// 'u'=>array('title'=>$lang['Highest_rated_users'], 'selected'=>'')
	$ratings_by[$ratingsby]['selected'] = ' selected="selected"';

	// IF BIAS SYSTEM ACTIVE, CREATE LINK TO BIAS SETTINGS
	$l_include_by = '<a href="' . append_sid($phpbb_root_path . 'rating_bias.'.$phpEx) . '" title="' . $lang['Rating_my_bias_title'] . '">' . $lang['Rating_include_by'] . '</a>';
}


//
// SCREEN-TYPE DROPDOWN BOX
//
$screen_types = array(
 	'l' => array('title' => $lang['Latest_ratings'], 'selected' => ''),
 	't' => array('title' => $lang['Highest_ranked_topics'], 'selected' => ''),
 	'p' => array('title' => $lang['Highest_ranked_posts'], 'selected' => ''),
 	'u' => array('title' => $lang['Highest_ranked_posters'], 'selected' => '')
 );
$screen_types[$type]['selected'] = ' selected="selected"';


//
// SHOULD DATA IN 'REGISTERED USER ONLY' FORUMS BE DISPLAYED?
//
$r_auth_level = ( !$userdata['session_logged_in'] ) ? 0 : 1;


//
// FORUM DROPDOWN BOX
//
$forums = array(
	0 => array('title' => $lang['Rating_all_forums'], 'selected' => '')
);

$sql = 'SELECT forum_id, forum_name 
	FROM ' . FORUMS_TABLE . '
 	WHERE auth_view <= ' . $r_auth_level . ' 
 		AND auth_read <= ' . $r_auth_level . '
	ORDER BY forum_name';
if( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, "Couldn't obtain temporary rating information", '', __LINE__, __FILE__, $sql); 
}
while( $row = $db->sql_fetchrow($result) ) 
{
	$forums[$row['forum_id']] = array('title' => stripslashes($row['forum_name']), 'selected' => '');
}


//
// Grab all the basic data 
//
$sql_select = 't.topic_title, t.rating_rank_id AS topic_rating, p.rating_rank_id AS post_rating, t.topic_id, u.username, u.user_id, u.user_level, r.rating_time, ro.label, ro.points AS label2, f.forum_id, p.post_id';
$sql_from = RATING_TABLE . ' r, ' . TOPICS_TABLE . ' t, ' . USERS_TABLE . ' u, ' . POSTS_TABLE . ' p, ' . FORUMS_TABLE . ' f, ' . USERS_TABLE . ' u2, ' . RATING_OPTION_TABLE . ' ro, ' . RATING_RANK_TABLE . ' rt';
$sql_where = 'r.post_id = p.post_id AND p.poster_id = u.user_id AND r.user_id = u2.user_id AND p.topic_id = t.topic_id AND t.topic_status = 0 AND t.forum_id = f.forum_id AND f.auth_view <= ' . $r_auth_level . ' AND f.auth_read <= ' . $r_auth_level . ' AND r.option_id = ro.option_id';
$sql_where .= ( $rating_config[2] == 1 ) ? ' AND r.post_id = t.topic_first_post_id' : '';

switch ($type)
{
	case 'u':
		if ( $rating_config[10] == 1 )
		{
			$rmethod = 'SUM(ro.points)';
			$l_column4 = $lang['Total_points'];
		}
		else
		{
			$rmethod = 'ROUND(AVG(ro.points), 1)';
			$l_column4 = $lang['Average_points'];
		}
		$page_title = $lang['Highest_ranked_posters'];
		$sql_select .= ', rk.rank_image, rk.rank_title, ' . $rmethod . ' AS points, u.user_posts';
		$sql_from .= ', ' . RANKS_TABLE . ' rk';
		$sql_where .= ' AND p.rating_rank_id = rt.rating_rank_id AND u.user_rank = rk.rank_id';
		$sql_order = 'points DESC, u.user_posts';
		$sql_group = 'p.poster_id';

		$l_column1 = $lang['Poster'];
		$l_column3 = $lang['Posts'];
		$l_column2 = $lang['Rating_sample_post'];
		$l_column5 = $lang['Poster_rank'];
		break;
	case 'p':
		if ( $rating_config[8] == 1 )
		{
			$rmethod = 'SUM(ro.points)';
			$l_column4 = $lang['Total_points'];
			$sql_order = 'points DESC, rt.sum_threshold DESC';
		}
		else
		{
			$rmethod = 'ROUND(AVG(ro.points), 1)';
			$l_column4 = $lang['Average_points'];
			$sql_order = 'points DESC, rt.average_threshold DESC';
		}
		$page_title = $lang['Highest_ranked_posts'];

		$sql_select .= ', f.forum_name, ' . $rmethod . ' AS points';
		$sql_where .= ' AND p.rating_rank_id = rt.rating_rank_id';
		$sql_group = 'p.post_id';
		// Get double req number of ratings, to allow for multiple ratings for same post. note this still doesn't guarantee enough records for desired no. of posts/topics but grabbing too many records would affect screen performance. Adjust to suit your own requirements
		$sql_limit *= 3;
		$l_column1 = $lang['Forum'];
		$l_column3 = $lang['Poster'];
		$l_column5 = $lang['Post_rank'];
		break;
	case 't':
		if ( $rating_config[9] == 3 )
		{
			// TOTAL (1 PER USER)
			$d_sql = 'DELETE FROM ' . $prefix . 'rating_temp';
			if( !($d_result = $db->sql_query($d_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, "Couldn't delete temporary rating information", '', __LINE__, __FILE__, $d_sql); 
			}
			$sql_select = 'INSERT INTO ' . $prefix . 'rating_temp (topic_id, points) SELECT t.topic_id, MAX(ro.points) AS points';
			$l_column4 = $lang['Total_points'];
			$sql_group = 't.topic_id, r.user_id';
			$sql_order = 't.topic_id';
			$using_temp_table = 'y';
			$sql_limit = '';
		}
		elseif ( $rating_config[9] == 1 )
		{
			// TOTAL (ALL)
			$sql_select .= ', f.forum_name, SUM(ro.points) AS points';
			$l_column4 = $lang['Total_points'];
			$sql_group = 't.topic_id';
			$sql_order = 'points DESC';
			$sql_limit *= 3;
		}
		else
		{
			// AVERAGE
			$sql_select .= ', f.forum_name, ROUND(AVG(ro.points), 1) AS points';
			$l_column4 = $lang['Average_points'];
			$sql_group = 't.topic_id';
			$sql_order = 'points DESC';
			$sql_limit *= 3;
		}
		$page_title = $lang['Highest_ranked_topics'];
		$sql_where = str_replace('p.poster_id', 't.topic_poster', $sql_where);
		$sql_where .= ' AND t.rating_rank_id = rt.rating_rank_id';
		$l_column1 = $lang['Forum'];
		$l_column3 = $lang['Topic_starter'];
		$l_column5 = $lang['Topic_rank'];
		break;
	default:
		$page_title = $lang['Latest_ratings'];
		$sql_select .= ', u2.username AS ratedby, u2.user_level AS user_level2, u2.rating_status, u2.user_id AS ratedby_id';
		$sql_where .= ' AND p.rating_rank_id = rt.rating_rank_id';
		$sql_order = 'r.rating_time DESC';
		$l_column1 = $lang['Poster'];
		$l_column3 = $lang['Rating'];
		$l_column4 = $lang['Rated_by'];
		$l_column5 = $lang['Post_rank'];
		break;
}


//
// POSTS IN A SPECIFIC FORUM?
//
if ( !empty($forum_id) )
{
	$sql_where .= ' AND t.forum_id = ' . $forum_id;
	$page_title .= ' ' . $lang['Rating_in'] . ' ' . $forums[$forum_id]['title'];
	$forums[$forum_id]['selected'] = ' selected="selected"';
}


//
// POSTS BY A SPECIFIC POSTER?
//
if ( !empty($postedby) )
{
	if ( $postedby == $userdata['user_id'] )
	{
		$by_poster = $lang['Ratings_posts_by_you'];
	}
	else
	{
		$sql = 'SELECT username, user_level, rating_status 
			FROM ' . USERS_TABLE . ' 
			WHERE user_id = ' . $postedby;
		$result = $db->sql_query($sql);
		
		$r = $db->sql_fetchrow($result);
		$by_poster = $lang['Ratings_posts_by'] . ' ' . username_level_color($r['username'], $r['user_level'], $postedby);
	}
	
	$sql_where .= ' AND p.poster_id = '.$postedby;
	$page_title .= ' (' . $by_poster . ')';
}


//
// RATINGS BY A SPECIFIC USER?
//
if ( $ratedby > 0 )
{
	if ( $rating_config[6] == 0 )
	{
		message_die(GENERAL_MESSAGE, $lang['Ratedby_hidden'], '', __LINE__, __FILE__, $sql); 
	}

	if ( $ratedby == $userdata['user_id'] )
	{
		$by_user = $lang['As_rated_by_you'];
	}
	else
	{
		$sql = 'SELECT username, user_level, rating_status 
			FROM ' . USERS_TABLE . ' 
			WHERE user_id = ' . $ratedby;
		$result = $db->sql_query($sql);
		$r = $db->sql_fetchrow($result);

		if ( $r['rating_status'] == 1 )
		{
			$is_anonymous = 'y';
		}
		$by_user = $lang['As_rated_by'] . ' ' . username_level_color($r['username'], $r['user_level'], $ratedby);
	}
	
	$sql_where .= ' AND r.user_id = ' . $ratedby;
	$page_title .= ' ' . $by_user;
	
	// IF SHOWING POSTS, DISPLAY POST RATING INSTEAD OF TOTAL/AVERAGE POINTS
	if ( $type == 'p' )
	{
		$l_column4 = $lang['Rating'];
	}
}
else if ( $ratingsby == 1 )
{
// original line not PHP 5.x compatible
//	$sql_from .= ' LEFT JOIN ' . RATING_BIAS_TABLE . ' i ON i.user_id = ' . $userdata['user_id'] . ' AND r.user_id = i.target_user';
	$sql_from .= ' LEFT JOIN ' . RATING_BIAS_TABLE . ' i ON i.user_id = ' . $userdata['user_id'];
	$sql_where .= ' AND (i.bias_status IS NULL OR i.bias_status != 1)';
}
else if ( $ratingsby == 3 )
{
	// ONLY RATINGS BY THOSE ON THIS USER'S BUDDY LIST
	$sql_from .= ', ' . RATING_BIAS_TABLE . ' i';
	$sql_where .= ' AND ' . $userdata['user_id'] . ' = i.user_id AND r.user_id = i.target_user AND i.bias_status = 2';
}

$sql = ( $using_temp_table == 'y' ) ? '' : 'SELECT ';
$sql .= $sql_select.' FROM ' . $sql_from . ' WHERE ' . $sql_where;
$sql .= ( !empty($sql_group) ) ? ' GROUP BY ' . $sql_group : '';
$sql .= ( !empty($sql_order) ) ? ' ORDER BY ' . $sql_order : '';
$sql .= ( !empty($sql_limit) ) ? ' LIMIT 0, ' . $sql_limit : '';
if( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, "Couldn't obtain post rating information", '', __LINE__, __FILE__, $sql); 
}

if ( $using_temp_table == 'y' )
{
	// Where sorting couldn't be done in one query (e.g. 1-per-user method for topic ranks)
	$sql_limit = 20;
	$sql = 'SELECT t.topic_title, t.rating_rank_id AS topic_rating, t.topic_id, u.username, u.user_id, u.user_level, f.forum_id, f.forum_name, SUM(z.points) AS points';
	$sql .= ' FROM ' . $prefix . 'rating_temp z, '. TOPICS_TABLE . ' t, ' . USERS_TABLE . ' u, ' . FORUMS_TABLE . ' f';
	$sql .= ' WHERE z.topic_id = t.topic_id
			AND t.topic_poster = u.user_id 
			AND t.forum_id = f.forum_id 
		GROUP BY z.topic_id 
		ORDER BY points DESC';
	$sql .= ( !empty($sql_limit) ) ? ' LIMIT 0, ' . $sql_limit : '';
	if( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, "Couldn't obtain temporary rating information", '', __LINE__, __FILE__, $sql); 
	}
	
	$d_sql = 'DELETE FROM ' . $prefix . 'rating_temp';
	if( !($d_result = $db->sql_query($d_sql)) ) 
	{ 
		message_die(GENERAL_ERROR, "Couldn't delete temporary rating information", '', __LINE__, __FILE__, $d_sql); 
	}
}

$total_rows = $db->sql_numrows($result); 

//
// Define censored word matches
//
if ( !$board_config['allow_swearywords'] )
{
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
}
else if ( !$userdata['user_allowswearywords'] )
{
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
}

$template->set_filenames(array( 
	'body' => 'ratings_body.tpl')
); 
make_jumpbox('viewforum.'.$phpEx); 

$u_ratings = append_sid($PHP_SELF);
$l_column2 = ( !empty($l_column2) ) ? $l_column2 : $lang['Topic'];


//
// Setup the stuff usually in the header 
//
$template->assign_vars(array( 
	'L_FORUM_INDEX' => $lang['Forum_index'],
	'U_FORUM_INDEX' => append_sid('index.'.$phpEx),
	'L_MY_BIAS' => $l_my_bias,
	'U_MY_BIAS' => $u_my_bias,
	'L_THEIR_BIAS' => $l_their_bias,
	'U_THEIR_BIAS' => $u_their_bias,
	'U_RATINGS' => $u_ratings, 
	'L_POSTER' => $lang['Poster'], 
	'L_TOPIC' => $l_column2, 
	'L_FORUM' => $lang['Forum'], 
	'L_SCREEN_TYPE' => $lang['Rating_screen_type'], 
	'L_INCLUDE_BY' => $l_include_by,
	'L_COLUMN1' => $l_column1, 
	'L_COLUMN3' => $l_column3, 
	'L_COLUMN4' => $l_column4, 
	'L_COLUMN5' => $l_column5)
); 


//
// Okay, lets dump out the page ... 
//
if( !empty($total_rows) && $is_anonymous != 'y' ) 
{ 
	while( $row = $db->sql_fetchrow($result) ) 
	{ 
		// Start auth check 
		$is_auth = array(); 
		$is_auth = auth(AUTH_ALL, $row['forum_id'], $userdata); 

		if( $is_auth['auth_read'] )
		{
			$rowset[] = $row; 
		}
	}
	
	// Limit the number of topics 
	$total_rows = ( $total_rows > $sql_limit ) ? $sql_limit : $total_rows;

	get_rating_ranks();

	for($i = 0; $i < $total_rows; $i++) 
	{ 
		$topic_title = $rowset[$i]['topic_title'];
		 
		if( !empty($orig_word) )
		{
			$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
		}
		
		$poster = ( $rowset[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . append_sid($phpbb_root_path . 'profile.' . $phpEx . '?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $rowset[$i]['user_id']) . '" class="genmed">' . username_level_color($rowset[$i]['username'], $rowset[$i]['user_level'], $rowset[$i]['user_id']) . '</a>' : $lang['Guest']; 

		switch ($type)
		{
			case 'u':
				$column1 = $poster;
				$column2 = append_sid('viewtopic.'.$phpEx.'?' . POST_POST_URL. '=' . $rowset[$i]['post_id'] . '#' . $rowset[$i]['post_id']);
				$column3 = $rowset[$i]['user_posts'];
				$column4 = $rowset[$i]['points'];
				$column5 = ( empty($rowset[$i]['rank_title']) ) ? $lang['No_rank'] : $rowset[$i]['rank_title'];
				break;
			case 'p':
				$column1 = '<a href="' . append_sid('viewforum.' . $phpEx . '?' . POST_FORUM_URL . '=' . $rowset[$i]['forum_id']) . '">' . $rowset[$i]['forum_name'] . '</a>';
				$column2 = append_sid('viewtopic.'.$phpEx.'?' . POST_POST_URL. '=' . $rowset[$i]['post_id'] . '#' . $rowset[$i]['post_id']);
				$column3 = $poster;
				if ( $ratedby > 0 )
				{
					$column4 = ( !empty($rowset[$i]['label']) ) ? $rowset[$i]['label'] : $rowset[$i]['label2'];
				}
				else
				{
					$column4 = $rowset[$i]['points'];
				}
				$column5 = ( empty($rowset[$i]['post_rating']) ) ? $lang['No_rank'] : $post_rank_set[$rowset[$i]['post_rating']];
				break;
			case 't':
				$column1 = '<a href="' . append_sid('viewforum.'.$phpEx.'?' . POST_FORUM_URL . '=' . $rowset[$i]['forum_id']) . '">' . $rowset[$i]['forum_name'] . '</a>';
				$column2 = append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL. '=' . $rowset[$i]['topic_id']);
				$column3 = $poster;
				$column4 = $rowset[$i]['points'];
				$column5 = ( empty($rowset[$i]['topic_rating']) ) ? $lang['No_rank'] : $topic_rank_set[$rowset[$i]['topic_rating']];
				break;
			default:
				$column1 = $poster;
				$column2 = append_sid('viewtopic.'.$phpEx.'?' . POST_POST_URL. '=' . $rowset[$i]['post_id'] . '#' . $rowset[$i]['post_id']);
				$column3 = ( !empty($rowset[$i]['label']) ) ? $rowset[$i]['label'] : $rowset[$i]['label2'];
				$column4 = ( $rowset[$i]['rating_status'] != 1 && $rating_config[6] == 1 ) ? '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $rowset[$i]['ratedby_id']) . '" class="genmed">' . username_level_color($rowset[$i]['ratedby'], $rowset[$i]['user_level2'], $rowset[$i]['ratedby_id']) . '</a>' : $lang['Rating_anon_user']; 
				$column5 = ( empty($rowset[$i]['post_rating']) ) ? $lang['No_rank'] : $post_rank_set[$rowset[$i]['post_rating']];
		}

		$template->assign_block_vars('rating', array( 
			'ROW_CLASS' => $row_class,
			'COLUMN1' => $column1, 
			'COLUMN2' => $column2,
			'COLUMN3' => $column3, 
			'COLUMN4' => $column4, 
			'COLUMN5' => $column5, 
			'TOPIC_TITLE' => $topic_title)
		); 
	}
} 
else 
{ 
	// No ratings 
	$template->assign_vars(array( 
		'L_NO_RATINGS' => $lang['No_ratings']) 
	); 
	$template->assign_block_vars('norating', array()); 
} 
$db->sql_freeresult($result);

foreach ($ratings_by AS $key=>$val)
{
	$template->assign_block_vars('ratingsby', array( 
		'ID' => $key, 
		'TITLE' => $val['title'],
		'SELECTED' => $val['selected'])
	); 
}

foreach ($screen_types AS $key=>$val)
{
	$template->assign_block_vars('screen_type', array( 
		'VALUE' => $key, 
		'TITLE' => $val['title'],
		'SELECTED' => $val['selected'])
	); 
}

foreach ($forums AS $key=>$val)
{
	$template->assign_block_vars('forums', array( 
		'ID' => $key, 
		'TITLE' => $val['title'],
		'SELECTED' => $val['selected'])
	); 
}


//
// Parse the page and print 
//
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

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
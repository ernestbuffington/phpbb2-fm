<?php
/** 
*
* @package phpBB2
* @version $Id: album_showpage.php,v 2.0.8 2003/03/14 07:08:15 ngoctu Exp $
* @copyright (c) 2003 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
    
if( isset($HTTP_GET_VARS['mode']) && $HTTP_GET_VARS['mode'] == 'smilies' )
{
    include($phpbb_root_path . 'mods/album/clown_album_functions.'.$phpEx);
        
    generate_smilies('window', PAGE_ALBUM_PICTURE);
    exit;
}

$album_root_path = $phpbb_root_path . 'mods/album/';
include($phpbb_root_path . 'includes/functions_validate.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ALBUM_PICTURE);
init_userprefs($userdata);
//
// End session management
//


//
// Get general album information
//
include($album_root_path . 'album_common.'.$phpEx);
include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);


// ------------------------------------
// Check feature enabled
// ------------------------------------
if( $album_config['comment'] == 0 )
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


// ------------------------------------
// Check the request
// ------------------------------------
if( isset($HTTP_GET_VARS['pic_id']) )
{
	$pic_id = intval($HTTP_GET_VARS['pic_id']);
}
else if( isset($HTTP_POST_VARS['pic_id']) )
{
	$pic_id = intval($HTTP_POST_VARS['pic_id']);
}
else
{
	if( isset($HTTP_GET_VARS['comment_id']) )
	{
		$comment_id = intval($HTTP_GET_VARS['comment_id']);
	}
	else if( isset($HTTP_POST_VARS['comment_id']) )
	{
		$comment_id = intval($HTTP_POST_VARS['comment_id']);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Comment_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}

//for midthum or full pic
if ($album_config['midthumb_use'] == 1)
{
	if( isset($HTTP_GET_VARS['full']) )
	{
		$picm = TRUE;
	}
	else if( isset($HTTP_POST_VARS['full']) )
	{
		$picm = TRUE;
	}
	else
	{
		$picm = FALSE;
	}
}
else
{
	$picm = TRUE;
}

// ------------------------------------
// PREVIOUS & NEXT
// ------------------------------------
if( isset($HTTP_GET_VARS['mode']) )
{
	if( ($HTTP_GET_VARS['mode'] == 'next') || ($HTTP_GET_VARS['mode'] == 'previous') )
   	{
    	$sql = "SELECT pic_id, pic_cat_id, pic_user_id
        	FROM " . ALBUM_TABLE . "
            WHERE pic_id = $pic_id";
		if( !($result = $db->sql_query($sql)) )
      	{
        	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
      	}

      	$row = $db->sql_fetchrow($result);

      	if( empty($row) )
      	{
			message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
      	}

      	$sql = "SELECT new.pic_id, new.pic_time
        	FROM ". ALBUM_TABLE ." AS new, ". ALBUM_TABLE ." AS cur
            WHERE cur.pic_id = $pic_id
            	AND new.pic_id <> cur.pic_id
            	AND new.pic_cat_id = cur.pic_cat_id";
		$sql .= ($HTTP_GET_VARS['mode'] == 'next') ? " AND new.pic_time >= cur.pic_time" : " AND new.pic_time <= cur.pic_time";
		$sql .= ($row['pic_cat_id'] == PERSONAL_GALLERY) ? " AND new.pic_user_id = cur.pic_user_id" : "";
		$sql .= ($HTTP_GET_VARS['mode'] == 'next') ? " ORDER BY pic_time ASC LIMIT 1" : " ORDER BY pic_time DESC LIMIT 1";
		if( !($result = $db->sql_query($sql)) )
      	{
        	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
      	}

      	$row = $db->sql_fetchrow($result);

      	if( empty($row) )
      	{
        	message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
      	}

      	$pic_id = $row['pic_id']; // NEW pic_id
   	}
}


// ------------------------------------
// Get $pic_id from $comment_id
// ------------------------------------
if( isset($comment_id) )
{
	$sql = "SELECT comment_id, comment_pic_id
		FROM ". ALBUM_COMMENT_TABLE ."
		WHERE comment_id = '$comment_id'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query comment and pic information', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	if( empty($row) )
	{
		message_die(GENERAL_MESSAGE, $lang['Comment_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}

	$pic_id = $row['comment_pic_id'];
}


// ------------------------------------
// Get this pic info
// ------------------------------------
$sql = "SELECT p.*, u.user_id, u.username, u.user_rank, u.user_level, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT( DISTINCT c.comment_id) AS comments_count
	FROM ". ALBUM_TABLE ." AS p
		LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
		LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
		LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
	WHERE pic_id = '$pic_id'
	GROUP BY p.pic_id
	LIMIT 1";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}
$thispic = $db->sql_fetchrow($result);

$cat_id = $thispic['pic_cat_id'];
$user_id = $thispic['pic_user_id'];

$total_comments = $thispic['comments_count'];
$comments_per_page = $board_config['posts_per_page'];

if( empty($thispic) )
{
	message_die(GENERAL_MESSAGE, $lang['Pic_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


// ------------------------------------
// Get the current Category Info
// ------------------------------------
if ($cat_id != PERSONAL_GALLERY)
{
	$sql = "SELECT *
		FROM ". ALBUM_CAT_TABLE ."
		WHERE cat_id = '$cat_id'";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query category information', '', __LINE__, __FILE__, $sql);
	}

	$thiscat = $db->sql_fetchrow($result);
}
else
{
	$thiscat = init_personal_gallery_cat($user_id);
}

if (empty($thiscat))
{
	message_die(GENERAL_MESSAGE, $lang['Category_not_exist'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
}


// ------------------------------------
// Check the permissions
// ------------------------------------
$auth_data = album_user_access($cat_id, $thiscat, 1, 0, 1, 1, 1, 1);

if ($auth_data['view'] == 0)
{
	if (!$userdata['session_logged_in'])
	{
		redirect(append_sid("login.$phpEx?redirect=album_showpage.$phpEx&pic_id=$pic_id"));
		exit;
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}
}

// ------------------------------------
//RATING:  Additional Check: if this user already rated
// ------------------------------------
if( $userdata['session_logged_in'] )
{
	$sql = "SELECT *
		FROM ". ALBUM_RATE_TABLE ."
		WHERE rate_pic_id = '$pic_id'
			AND rate_user_id = '". $userdata['user_id'] ."'
		LIMIT 1";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not query rating information', '', __LINE__, __FILE__, $sql);
	}

	if ($db->sql_numrows($result) > 0)
	{
		$already_rated = TRUE;
	}
	else
	{
		$already_rated = FALSE;
	}
}
else
{
	$already_rated = FALSE;
}

//
// Next
//
$sql = "SELECT new.pic_id, new.pic_time	
	FROM ". ALBUM_TABLE ." AS new, ". ALBUM_TABLE ." AS cur
	WHERE cur.pic_id = $pic_id
  		AND new.pic_id <> cur.pic_id
    	AND new.pic_cat_id = cur.pic_cat_id
    	AND new.pic_time >= cur.pic_time";
$sql .= ($thispic['pic_cat_id'] == PERSONAL_GALLERY) ? " AND new.pic_user_id = cur.pic_user_id" : "";
$sql .= " ORDER BY pic_time ASC LIMIT 1";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

if( empty($row) )
{
	$u_next = $l_next = '';
}
else
{
	$new_pic_id = $row['pic_id'];
   	$u_next = append_sid("album_showpage.$phpEx?pic_id=$new_pic_id");
   	$l_next = $lang['Next'] . "&nbsp;&raquo;";
}

//
// Prev
//
$sql = "SELECT new.pic_id, new.pic_time
	FROM ". ALBUM_TABLE ." AS new, ". ALBUM_TABLE ." AS cur
    WHERE cur.pic_id = $pic_id
    	AND new.pic_id <> cur.pic_id
        AND new.pic_cat_id = cur.pic_cat_id
        AND new.pic_time <= cur.pic_time";
$sql .= ($thispic['pic_cat_id'] == PERSONAL_GALLERY) ? " AND new.pic_user_id = cur.pic_user_id" : "";
$sql .= " ORDER BY pic_time DESC LIMIT 1";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query pic information', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);

if( empty($row) )
{
	$u_prev = $l_prev = '';
}
else
{
	$new_pic_id = $row['pic_id'];
	$u_prev = append_sid("album_showpage.$phpEx?pic_id=$new_pic_id");
	$l_prev = "&laquo;&nbsp;" . $lang['Previous'];
}


/*
+----------------------------------------------------------
| Main work here...
+----------------------------------------------------------
*/
if( !isset($HTTP_POST_VARS['comment']) )
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
               Comments Screen
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

	// ------------------------------------
	// Get the comments thread
	// Beware: when this script was called with comment_id (without start)
	// ------------------------------------
	if( !isset($comment_id) )
	{
		if( isset($HTTP_GET_VARS['start']) )
		{
			$start = intval($HTTP_GET_VARS['start']);
		}
		else if( isset($HTTP_POST_VARS['start']) )
		{
			$start = intval($HTTP_POST_VARS['start']);
		}
		else
		{
			$start = 0;
		}
	}
	else
	{
		// We must do a query to co-ordinate this comment
		$sql = "SELECT COUNT(comment_id) AS count
			FROM ". ALBUM_COMMENT_TABLE ."
			WHERE comment_pic_id = $pic_id
				AND comment_id < $comment_id";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain comments information from the database', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);

		if( !empty($row) )
		{
			$start = floor( $row['count'] / $comments_per_page ) * $comments_per_page;
		}
		else
		{
			$start = 0;
		}
	}

	if( isset($HTTP_GET_VARS['sort_order']) )
	{
		switch ($HTTP_GET_VARS['sort_order'])
		{
			case 'ASC':
				$sort_order = 'ASC';
				break;
			default:
				$sort_order = 'DESC';
				break;
		}
	}
	else if( isset($HTTP_POST_VARS['sort_order']) )
	{
		switch ($HTTP_POST_VARS['sort_order'])
		{
			case 'ASC':
				$sort_order = 'ASC';
				break;
			default:
				$sort_order = 'DESC';
				break;
		}
	}
	else
	{
		$sort_order = 'ASC';
	}

	if ($total_comments > 0)
	{
		$template->assign_block_vars('coment_switcharo_top', array());
		
		$limit_sql = ($start == 0) ? $comments_per_page : $start .','. $comments_per_page;

		$sql = "SELECT c.*, u.user_id, u.username, u.user_regdate, u.user_posts, u.user_allowavatar, u.user_rank, u.user_avatar, u.user_avatar_type, u.user_email, u.user_icq, u.user_website, u.user_from, u.user_aim, u.user_yim, u.user_msnm, u.user_level
			FROM ". ALBUM_COMMENT_TABLE ." AS c
				LEFT JOIN ". USERS_TABLE ." AS u ON c.comment_user_id = u.user_id
			WHERE c.comment_pic_id = '$pic_id'
			ORDER BY c.comment_id $sort_order
			LIMIT $limit_sql";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain comments information from the database', '', __LINE__, __FILE__, $sql);
		}

		$commentrow = array();

		while( $row = $db->sql_fetchrow($result) )
		{
			$commentrow[] = $row;
		}
		$db->sql_freeresult($result);

		for ($i = 0; $i < count($commentrow); $i++)
		{
			$commentrow[$i]['username'] = username_level_color($commentrow[$i]['username'], $commentrow[$i]['user_level'], $commentrow[$i]['user_id']);
			
			if( ($commentrow[$i]['user_id'] == ALBUM_GUEST) || ($commentrow[$i]['username'] == '') )
			{
				$poster = ($commentrow[$i]['comment_username'] == '') ? $lang['Guest'] : $commentrow[$i]['comment_username'];
			}
			else
			{
				$poster = $commentrow[$i]['username'];
			}

			if ($commentrow[$i]['comment_edit_count'] > 0)
			{
				$sql = "SELECT c.comment_id, c.comment_edit_user_id, u.user_id, u.username
					FROM ". ALBUM_COMMENT_TABLE ." AS c
						LEFT JOIN ". USERS_TABLE ." AS u ON c.comment_edit_user_id = u.user_id
					WHERE c.comment_id = '".$commentrow[$i]['comment_id']."'
					LIMIT 1";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain last edit information from the database', '', __LINE__, __FILE__, $sql);
				}

				$lastedit_row = $db->sql_fetchrow($result);

				$edit_info = ($commentrow[$i]['comment_edit_count'] == 1) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];

				$edit_info = '<br /><br />&raquo;&nbsp;'. sprintf($edit_info, $lastedit_row['username'], create_date($board_config['default_dateformat'], $commentrow[$i]['comment_edit_time'], $board_config['board_timezone']), $commentrow[$i]['comment_edit_count']) .'<br />';
			}
			else
			{
				$edit_info = '';
			}
			
			// Smilies
			$commentrow[$i]['comment_text'] = smilies_pass($commentrow[$i]['comment_text']);
			$commentrow[$i]['comment_text'] = make_clickable($commentrow[$i]['comment_text']);
			$commentrow[$i]['comment_text'] = nl2br($commentrow[$i]['comment_text']);
			
			//email, profile, pm links
			$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $commentrow[$i]['user_id']) : 'mailto:' . $commentrow[$i]['user_email'];
			$profile_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $commentrow[$i]['user_id'] );
			$pm_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=" . $commentrow[$i]['user_id']);
			
			//avatar
			$poster_avatar = '';
			if ( $commentrow[$i]['user_avatar_type'] && $commentrow[$i]['user_id'] != ANONYMOUS && $commentrow[$i]['user_allowavatar'] )
			{
				switch( $commentrow[$i]['user_avatar_type'] )
				{
					case USER_AVATAR_UPLOAD:
						$poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $commentrow[$i]['user_avatar'] . '" alt="" />' : '';
						break;
					case USER_AVATAR_REMOTE:
						$poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $commentrow[$i]['user_avatar'] . '" alt="" />' : '';
						break;
					case USER_AVATAR_GALLERY:
						$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $commentrow[$i]['user_avatar'] . '" alt="" />' : '';
						break;
				}
			}
			
			//rank & rank image
			$sql = "SELECT *
				FROM " . RANKS_TABLE . "
				ORDER BY rank_special, rank_min";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql);
			}

			$ranksrow = array();
			while ( $row = $db->sql_fetchrow($result) )
			{
				$ranksrow[] = $row;
			}
			$db->sql_freeresult($result);

			$poster_rank = $rank_image = '';
			if ($commentrow[$i]['user_id'] == ANONYMOUS)
			{
				$poster_rank = $lang['Guest'];
			}
			else if ( $commentrow[$i]['user_rank'] )
			{
				for($j = 0; $j < sizeof($ranksrow); $j++)
				{
					if ( $commentrow[$i]['user_rank'] == $ranksrow[$j]['rank_id'] && $ranksrow[$j]['rank_special'] )
					{
						$poster_rank = $ranksrow[$j]['rank_title'];
						$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[$j]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
					}
				}
			}
			else
			{
				for($j = 0; $j < sizeof($ranksrow); $j++)
				{
					if ( $commentrow[$i]['user_posts'] >= $ranksrow[$j]['rank_min'] && !$ranksrow[$j]['rank_special'] )
					{
						$poster_rank = $ranksrow[$j]['rank_title'];
						$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[$j]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
					}
				}
			}

			//
			// Handle anon users posting with usernames
			//
			if ( $commentrow[$i]['user_id'] == ANONYMOUS && $commentrow[$i]['post_username'] != '' )
			{
				$poster = $commentrow[$i]['post_username'];
				$poster_rank = $lang['Guest'];
			}
			
			$template->assign_block_vars('commentrow', array(
				'ID' => $commentrow[$i]['comment_id'],
				'POSTER_NAME' => $poster,
				'TIME' => create_date($board_config['default_dateformat'], $commentrow[$i]['comment_time'], $board_config['board_timezone']),
				'IP' => ($userdata['user_level'] == ADMIN) ? '<a href="http://network-tools.com/default.asp?prog=trace&amp;host=' . decode_ip($commentrow[$i]['comment_user_ip']) . '" target="_blank"><img src="' . $images['icon_ip'] . '" alt="' . decode_ip($commentrow[$i]['comment_user_ip']) . '" title="' . decode_ip($commentrow[$i]['comment_user_ip']) . '" /></a>' : '',
				
				// User messengers, website, email
				'PROFILE_IMG' => ( $commentrow[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . $profile_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" /></a>' : '',
				'PM_IMG' => ( $commentrow[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . $pm_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>' : '',
				'AIM_IMG' => ( $commentrow[$i]['user_id'] != ANONYMOUS ) ? ( $commentrow[$i]['user_aim'] ) ? '<a href="aim:goim?screenname=' . $commentrow[$i]['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="http://big.oscar.aol.com/' . $commentrow[$i]['user_aim'] . '?on_url=' . $images['icon_aim_online'] . '&off_url=' . $images['icon_aim_offline'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" /></a>' : '' : '',
				'YIM_IMG' => ( $commentrow[$i]['user_id'] != ANONYMOUS ) ? ( $commentrow[$i]['user_yim'] ) ? '<a href="ymsgr:sendIM?' . $commentrow[$i]['user_yim'] . '&amp;__you+there?"><img src="http://opi.yahoo.com/online?' . POST_USERS_URL . '=' . $commentrow[$i]['user_yim'] . '&amp;m=' . POST_GROUPS_URL . '&amp;' . POST_TOPIC_URL . '=1" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" /></a>' : '' : '',
				'MSNM_IMG' => ( $commentrow[$i]['user_id'] != ANONYMOUS ) ? ( $commentrow[$i]['user_msnm'] ) ? '<a href="http://members.msn.com/' . $commentrow[$i]['user_msnm'] . '"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" /></a>' : '' : '',
				'ICQ_IMG' =>  ( $commentrow[$i]['user_id'] != ANONYMOUS ) ? ( $commentrow[$i]['user_icq'] ) ? '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $commentrow[$i]['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" /></a>' : '' : '',
				'EMAIL_IMG' => ( $commentrow[$i]['user_id'] != ANONYMOUS ) ? '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>' : '',
				'WWW_IMG' => ( $commentrow[$i]['user_id'] != ANONYMOUS ) ? ( $commentrow[$i]['user_website'] ) ? '<a href="' . $commentrow[$i]['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>' : '' : '',
				
				'POSTER_AVATAR' => $poster_avatar,
				'POSTER_RANK' => $poster_rank,
				'POSTER_RANK_IMAGE' => $rank_image,
				'POSTER_JOINED' => ( $commentrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Joined'] . ': ' . create_date($lang['DATE_FORMAT'], $commentrow[$i]['user_regdate'], $board_config['board_timezone']) : '',
				'POSTER_POSTS' => ( $commentrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Posts'] . ': ' . $commentrow[$i]['user_posts'] : '',
				'POSTER_FROM' => ( $commentrow[$i]['user_from'] && $commentrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Location'] . ': ' . $commentrow[$i]['user_from'] : '',
			
				'MINI_POST_IMG' => $images['icon_minipost'],
				'L_MINI_POST_ALT' => $lang['Comment'],
					
				'TEXT' => $commentrow[$i]['comment_text'],
				'EDIT_INFO' => $edit_info,

				'EDIT' => ( ( $auth_data['edit'] and ($commentrow[$i]['comment_user_id'] == $userdata['user_id']) ) or ($auth_data['moderator'] and ($thiscat['cat_edit_level'] != ALBUM_ADMIN) ) or ($userdata['user_level'] == ADMIN) ) ? '<a href="'. append_sid("album_comment_edit.$phpEx?comment_id=" . $commentrow[$i]['comment_id']) . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit_pic'] . '" title="' . $lang['Edit_pic'] . '" /></a>' : '',
				'DELETE' => ( ( $auth_data['delete'] and ($commentrow[$i]['comment_user_id'] == $userdata['user_id']) ) or ($auth_data['moderator'] and ($thiscat['cat_delete_level'] != ALBUM_ADMIN) ) or ($userdata['user_level'] == ADMIN) ) ? '<a href="'. append_sid("album_comment_delete.$phpEx?comment_id=" . $commentrow[$i]['comment_id']) . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_pic'] . '" title="' . $lang['Delete_pic'] . '" /></a>' : '')
			);
		}

		$template->assign_block_vars('switch_comment', array());

		$template->assign_vars(array(
			'PAGINATION' => generate_pagination(append_sid("album_showpage.$phpEx?pic_id=$pic_id&amp;sort_order=$sort_order"), $total_comments, $comments_per_page, $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $comments_per_page ) + 1 ), ceil( $total_comments / $comments_per_page )))
		);
		$template->assign_block_vars('coment_switcharo_bottom', array());
	}

	//
	// Start output of page
	//
	$page_title = $lang['Album'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	$template->set_filenames(array(
		'body' => 'album_showpage_body.tpl')
	);

	$thispic['username'] = username_level_color($thispic['username'], $thispic['user_level'], $thispic['user_id']);

	if( ($thispic['pic_user_id'] == ALBUM_GUEST) || ($thispic['username'] == '') )
	{
		$poster = ($thispic['pic_username'] == '') ? $lang['Guest'] : $thispic['pic_username'];
	}
	else
	{
		$poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $thispic['user_id']) .'" class="genmed">'. $thispic['username'] .'</a>';
	}

	//---------------------------------
	// Comment Posting Form
	//---------------------------------
	
	if ($auth_data['comment'] == 1)
	{
		$template->assign_block_vars('switch_comment_post', array());

		if( !$userdata['session_logged_in'] )
		{
			$template->assign_block_vars('switch_comment_post.logout', array());
		}
	}
	
	$image_rating = ImageRating($thispic['rating']);

    // --------------------------------
	// Show Smilies
	// --------------------------------
	$max_smilies = 20;

    $sql = 'SELECT emoticon, code, smile_url
    	FROM ' . SMILIES_TABLE . ' 
        GROUP BY smile_url
        ORDER BY smilies_id LIMIT ' . $max_smilies;
	if (!$result = $db->sql_query($sql))
    {
    	message_die(GENERAL_ERROR, "Couldn't retrieve smilies list", '', __LINE__, __FILE__, $sql);
    }
    $smilies_count = $db->sql_numrows($result);
    $smilies_data = $db->sql_fetchrowset($result);
        
    if ($auth_data['comment'] == 1)
	{
		for ($i = 1; $i < $smilies_count+1; $i++)
	    {
	    	$template->assign_block_vars('switch_comment_post.smilies', array(
	        	'CODE' => $smilies_data[$i - 1]['code'],
	        	'URL' => $board_config['smilies_path'] . '/' . $smilies_data[$i - 1]['smile_url'],
	            'DESC' => $smilies_data[$i - 1]['emoticon'])
	        );
	            
	        if ( is_integer($i / 5) )
	        {
	        	$template->assign_block_vars('switch_comment_post.smilies.new_col', array());
			}
		}
    }
        
    // --------------------------------
	// Rate Scale
	// --------------------------------
	if (!$already_rated)
	{
		if ($auth_data['rate'] == 1)
		{
			for ($i = 0; $i < $album_config['rate_scale']; $i++)
			{   
				$template->assign_block_vars('switch_comment_post.rate_row', array(
					'POINT' => ($i + 1))
				);
			}
		}
	}

	if ($album_config['rate'])
	{
		$template->assign_block_vars('rate_switch', array());
	}

	$template->assign_vars(array(
		'CAT_TITLE' => $thiscat['cat_title'],
		'U_VIEW_CAT' => ($cat_id != PERSONAL_GALLERY) ? append_sid("album_cat.$phpEx?cat_id=$cat_id") : append_sid("album_personal.$phpEx?user_id=$user_id"),

		'U_PIC' => ( $picm ) ? append_sid("album_pic.$phpEx?pic_id=$pic_id") : append_sid("album_picm.$phpEx?pic_id=$pic_id"),
		'U_PIC_L1' => ( $picm ) ? '' : '<a href="album_showpage.php?full=&pic_id=' . $pic_id . '">',
		'U_PIC_L2' => ( $picm ) ? '' : '</a>',
		'U_PIC_CLICK' => ( $picm ) ? '' : $lang['View_larger_pic'],
		
		'PIC_RATING' => $image_rating,
		'PIC_TITLE' => $thispic['pic_title'],
		'PIC_DESC' => nl2br($thispic['pic_desc']),
		'POSTER' => $poster,
		'PIC_TIME' => create_date($board_config['default_dateformat'], $thispic['pic_time'], $board_config['board_timezone']),
		'PIC_VIEW' => $thispic['pic_view_count'],
		'PIC_COMMENTS' => $total_comments,

		'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',

		'L_ALL_SMILIES' => $lang['More_emoticons'],
		'L_PIC_TITLE' => $lang['Pic_Title'],
		'L_PIC_DESC' => $lang['Pic_Desc'],
		'L_POSTER' => $lang['Poster'],
		'L_POSTED' => $lang['Posted'],
		'L_VIEW' => $lang['View'],
		'L_COMMENTS' => $lang['Comments'],
		'L_RATING' => $lang['Rating'],
		'L_THAT_CONTAINS' => $lang['That_contains'],

		'L_POST_YOUR_COMMENT' => $lang['Post_your_comment'],
		'L_AUTHOR' => $lang['Author'],
		'L_MESSAGE' => $lang['Message'],
		'L_USERNAME' => $lang['Username'],
		'L_COMMENT_NO_TEXT' => $lang['Comment_no_text'],
		'L_COMMENT_TOO_LONG' => $lang['Comment_too_long'],
		'L_MAX_LENGTH' => $lang['Max_length'],
		'S_MAX_LENGTH' => $album_config['desc_length'],

		'L_ORDER' => $lang['Order'],
		'L_ASC' => $lang['Sort_Ascending'],
		'L_DESC' => $lang['Sort_Descending'],
						
		'SORT_ASC' => ($sort_order == 'ASC') ? 'selected="selected"' : '',
		'SORT_DESC' => ($sort_order == 'DESC') ? 'selected="selected"' : '',

		'S_ALBUM_ACTION' => append_sid("album_showpage.$phpEx?pic_id=$pic_id"),

   		'U_NEXT' => $u_next,
   		'U_PREVIOUS' => $u_prev,

   		'L_NEXT' => $l_next,
   		'L_PREVIOUS' => $l_prev,
		
		//rating
		'S_RATE_MSG' => (  !$userdata['session_logged_in'] && $auth_data['rate'] == 0 ) ? 'Login to vote!' : ( ($already_rated) ? $lang['Already_rated'] : $lang['Rating'] ),
		'L_CURRENT_RATING' => $lang['Current_Rating'],
		'L_PLEASE_RATE_IT' => $lang['Please_Rate_It'])
	);

	//
	// Generate the page
	//
	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}
else
{
	/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
              Comment Submited
	   ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
	// ------------------------------------
	// Check the permissions: COMMENT
	// ------------------------------------
	if ($auth_data['comment'] == 0)
	{
		if (!$userdata['session_logged_in'])
		{
			redirect(append_sid("login.$phpEx?redirect=album_showpage.$phpEx&amp;pic_id=$pic_id"));
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Not_Authorised'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
		}
	}

	$comment_text = str_replace("\'", "''", htmlspecialchars(substr(trim($HTTP_POST_VARS['comment']), 0, $album_config['desc_length'])));

	$comment_username = (!$userdata['session_logged_in']) ? str_replace("\'", "''", substr(htmlspecialchars(trim($HTTP_POST_VARS['comment_username'])), 0, 32)) : str_replace("'", "''", htmlspecialchars(trim($userdata['username'])));

	// --------------------------------
	// Check Pic Locked
	// --------------------------------
	if( ($thispic['pic_lock'] == 1) and (!$auth_data['moderator']) )
	{
		message_die(GENERAL_MESSAGE, $lang['Pic_Locked'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
	}

	// --------------------------------
	// Check username for guest posting
	// --------------------------------
	if (!$userdata['session_logged_in'])
	{
		if ($comment_username != '')
		{
			$result = validate_username($comment_username);
			if ( $result['error'] )
			{
				message_die(GENERAL_MESSAGE, $result['error_msg'] . '<br /><br />' . sprintf($lang['Click_return_album_index'], '<a href="' . append_sid('album.'.$phpEx) . '">', '</a>'));
			}
		}
	}

	// --------------------------------
	// Prepare variables
	// --------------------------------
	$comment_time = time();
	$comment_user_id = $userdata['user_id'];
	$comment_user_ip = $userdata['session_ip'];

	// --------------------------------
	// Get $comment_id
	// --------------------------------
	$sql = "SELECT MAX(comment_id) AS max
		FROM ". ALBUM_COMMENT_TABLE;
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain comment_id', '', __LINE__, __FILE__, $sql);
	}

	$row = $db->sql_fetchrow($result);

	$comment_id = $row['max'] + 1;
	
	// --------------------------------
	// Insert into DB
	// --------------------------------
	if ($comment_text != '')//if user only rated, but didnt enter a comment ..... only update rating
	{
		$sql = "INSERT INTO ". ALBUM_COMMENT_TABLE ." (comment_id, comment_pic_id, comment_cat_id, comment_user_id, comment_username, comment_user_ip, comment_time, comment_text)
			VALUES ('$comment_id', '$pic_id', '$cat_id', '$comment_user_id', '$comment_username', '$comment_user_ip', '$comment_time', '$comment_text')";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert new entry', '', __LINE__, __FILE__, $sql);
		}
	}
	
	//rating
	$rate_point = intval($HTTP_POST_VARS['rate']);
	
	if ($rate_point != -1)//if user didnt vote, dont update database
	{
		if( ($rate_point <= 0) || ($rate_point > $album_config['rate_scale']) )
		{
			message_die(GENERAL_ERROR, 'Bad submited value');
		}

		$rate_user_id = $userdata['user_id'];
		$rate_user_ip = $userdata['session_ip'];
		
		$sql = "INSERT INTO ". ALBUM_RATE_TABLE ." (rate_pic_id, rate_user_id, rate_user_ip, rate_point)
			VALUES ('$pic_id', '$rate_user_id', '$rate_user_ip', '$rate_point')";
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not insert new rating', '', __LINE__, __FILE__, $sql);
		}
	}

	// --------------------------------
	// Complete... now send a message to user
	// --------------------------------
	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("album_showpage.$phpEx?pic_id=$pic_id") . '">')
	);

	$message = $lang['Stored'] . "<br /><br />" . sprintf($lang['Click_view_message'], "<a href=\"" . append_sid("album_showpage.$phpEx?pic_id=$pic_id ") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_album_index'], "<a href=\"" . append_sid("album.$phpEx") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}

?>
<?php
/** 
*
* @package phpBB2
* @version $Id: album.php,v 2.0.7 2003/03/15 10:16:30 ngoctu Exp $
* @copyright (c) 2003 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
$album_root_path = $phpbb_root_path . 'mods/album/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_ALBUM);
init_userprefs($userdata);
//
// End session management
//

//
// Get general album information
//
include($album_root_path . 'album_common.'.$phpEx);

//
// Build Categories Index
//
$sql = "SELECT c.*, COUNT(p.pic_id) AS count
	FROM ". ALBUM_CAT_TABLE ." AS c
		LEFT JOIN ". ALBUM_TABLE ." AS p ON c.cat_id = p.pic_cat_id
	WHERE cat_id <> 0
	GROUP BY cat_id
	ORDER BY cat_order ASC";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
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
$db->sql_freeresult($result);

$allowed_cat = ''; // For Recent Public Pics below

//
// $catrows now stores all categories which this user can view. Dump them out!
//
for ($i = 0; $i < sizeof($catrows); $i++)
{
	// --------------------------------
	// Build allowed category-list (for recent pics after here)
	// --------------------------------
	$allowed_cat .= ($allowed_cat == '') ? $catrows[$i]['cat_id'] : ',' . $catrows[$i]['cat_id'];

	// --------------------------------
	// Build moderators list
	// --------------------------------
	$l_moderators = $moderators_list = '';
	$grouprows = array();
	if( $catrows[$i]['cat_moderator_groups'] != '')
	{
		// We have usergroup_ID, now we need usergroup name
		$sql = "SELECT group_id, group_name
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user <> 1
				AND group_type <> ". GROUP_HIDDEN ."
				AND group_id IN (". $catrows[$i]['cat_moderator_groups'] .")
			ORDER BY group_name ASC";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain usergroups data', '', __LINE__, __FILE__, $sql);
		}

		while( $row = $db->sql_fetchrow($result) )
		{
			$grouprows[] = $row;
		}
		$db->sql_freeresult($result);
	}

	if( sizeof($grouprows) > 0 )
	{
		$l_moderators = $lang['Moderators'];

		for ($j = 0; $j < sizeof($grouprows); $j++)
		{
			$group_link = '<a href="'. append_sid("groupcp.$phpEx?". POST_GROUPS_URL .'='. $grouprows[$j]['group_id']) .'">'. $grouprows[$j]['group_name'] .'</a>';

			$moderators_list .= ($moderators_list == '') ? $group_link : ', ' . $group_link;
		}
	}

	// ------------------------------------------
	// Get Last Pic of this Category
	// ------------------------------------------
	if ($catrows[$i]['count'] == 0)
	{
		//
		// Oh, this category is empty
		//
		$last_pic_info = $lang['No_Pics'];
		$u_last_pic = $last_pic_title = $viewer = '';
		
		//last comments
		$last_comment_info = $lang['No_Comments'];
		$cat_total_comments = 0;
	}
	else
	{
		// ----------------------------
		// Check Pic Approval
		// ----------------------------
		if(($catrows[$i]['cat_approval'] == ALBUM_ADMIN) || ($catrows[$i]['cat_approval'] == ALBUM_MOD))
		{
			$pic_approval_sql = 'AND p.pic_approval = 1'; // Pic Approval ON
		}
		else
		{
			$pic_approval_sql = ''; // Pic Approval OFF
		}

		// ----------------------------
		// OK, we may do a query now...
		// ----------------------------
		$sql = "SELECT p.pic_id, p.pic_title, p.pic_user_id, p.pic_username, p.pic_time, p.pic_cat_id, u.user_id, u.username, u.user_level, COUNT(c.comment_id) AS comment_count
			FROM ". ALBUM_TABLE ." AS p
				LEFT JOIN " . ALBUM_COMMENT_TABLE . " AS c ON p.pic_cat_id = c.comment_cat_id
				LEFT JOIN ". USERS_TABLE ." AS u  ON p.pic_user_id = u.user_id 
			WHERE p.pic_cat_id = '". $catrows[$i]['cat_id'] ."' 
				$pic_approval_sql
			GROUP BY p.pic_time
			ORDER BY p.pic_time DESC
			LIMIT 1";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get last pic information', '', __LINE__, __FILE__, $sql);
		}
		$lastrow = $db->sql_fetchrow($result);

		$sql = "SELECT c.comment_pic_id, c.comment_user_id, c.comment_username, c.comment_time, u.user_id, u.username, u.user_level, a.pic_id, a.pic_cat_id
			FROM " . ALBUM_COMMENT_TABLE . " AS c 
				LEFT JOIN " . USERS_TABLE . " AS u ON c.comment_user_id = u.user_id
				LEFT JOIN " . ALBUM_TABLE . " AS a ON c.comment_pic_id = a.pic_id
			WHERE a.pic_cat_id = '" . $catrows[$i]['cat_id'] . "' 
			ORDER BY c.comment_time DESC
			LIMIT 1";	
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get last pic information', '', __LINE__, __FILE__, $sql);
		}
		$lastcrow = $db->sql_fetchrow($result);

		$sql = "SELECT COUNT(pic_id) AS total
			FROM " . ALBUM_TABLE . "
			WHERE pic_cat_id = '". $catrows[$i]['cat_id'] ."' 
				AND pic_approval = 0";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not get unapproved pic information', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);

		// ----------------------------
		// Write the last pic's title.
		// Truncate it if it's too long
		// ----------------------------
		if( !isset($album_config['last_pic_title_length']) )
		{
			$album_config['last_pic_title_length'] = 25;
		}

		$lastrow['pic_title'] = $lastrow['pic_title'];
		if (strlen($lastrow['pic_title']) > $album_config['last_pic_title_length'])
		{
			$lastrow['pic_title'] = substr($lastrow['pic_title'], 0, $album_config['last_pic_title_length']) . '...';
		}

		$last_pic_info = '<b><a class="gensmall" href="';
		$last_pic_info .= ( $album_config['fullpic_popup'] ) ? append_sid("album_pic.$phpEx?pic_id=". $lastrow['pic_id']) .'" target="_blank">' : append_sid("album_showpage.$phpEx?pic_id=". $lastrow['pic_id']) .'">' ;
		$last_pic_info .= $lastrow['pic_title'] . '</a></b><br />';

		// ----------------------------
		// Write the Date
		// ----------------------------
		$last_pic_info .= ($lastrow['pic_time']) ? create_date($board_config['default_dateformat'], $lastrow['pic_time'], $board_config['board_timezone']) . '<br />' : '';

		// ----------------------------
		// Write username of last poster
		// ----------------------------
		$lastrow['username'] = username_level_color($lastrow['username'], $lastrow['user_level'], $lastrow['user_id']);

		if( ($lastrow['user_id'] == ALBUM_GUEST) || ($lastrow['username'] == '') && $lastrow['pic_time'])
		{
			$last_pic_info .= ($lastrow['pic_username'] == '') ? $lang['Poster'] . ': ' . $lang['Guest'] : $lastrow['pic_username'];
		}
		else
		{
			$last_pic_info .= '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $lastrow['user_id']) .'" class="gensmall">'. $lastrow['username'] .'</a>';
		}

		// Last comment
		if ( $lastrow['comment_count'] == 0 )
		{
			$last_comment_info = $lang['No_Comments'];
		}
		else
		{
			$last_comment_info = create_date($board_config['default_dateformat'], $lastcrow['comment_time'], $board_config['board_timezone']) . '<br />';

			$lastcrow['username'] = username_level_color($lastcrow['username'], $lastcrow['user_level'], $lastcrow['user_id']);

			if( ($lastcrow['user_id'] == ALBUM_GUEST) or ($lastcrow['comment_username'] == '') )
			{
				$last_comment_info .= ($lastcrow['comment_username'] == '') ? $lang['Guest'] : $lastcrow['comment_username'];
			}
			else
			{
				$last_comment_info .= '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $lastcrow['user_id']) .'" class="gensmall">'. $lastcrow['username'] .'</a>';
			}
		}

		// ----------------------------
		// Check for unapproved pics
		// ----------------------------
		if( ($userdata['user_level'] == ADMIN) || ($userdata['user_level'] == LESS_ADMIN) || ($userdata['user_level'] == MOD) )
		{
			if ($row['total'] != 0)
			{
				$last_pic_info .= '<br />' . $lang['Not_approved'] . ': <b>' . $row['total'] . '</b>';
			}
			else
			{
					$last_pic_info .= '';
			}		
		}
		
		//comment count
		$cat_total_comments = $lastrow['comment_count'];

		$cat_id = $catrows[$i]['cat_id'];
		$viewer = ($album_config['slidepics_per_page']) ? '[ <a href="' . append_sid('album_slide.'.$phpEx.'?cat_id=' . $cat_id) . '" class="gensmall">' . $lang['Slide_Cat'] . '</a> ]' : '';
	}
	// END of Last Pic


	// ------------------------------------------
	// Parse to template the info of the current Category
	// ------------------------------------------
	$template->assign_block_vars('catrow', array(
		'U_VIEW_CAT' => append_sid("album_cat.$phpEx?cat_id=". $catrows[$i]['cat_id']),
		'SLIDE_CAT' => $viewer,
		'CAT_TITLE' => $catrows[$i]['cat_title'],
		'CAT_DESC' => $catrows[$i]['cat_desc'],
		'L_MODERATORS' => $l_moderators,
		'MODERATORS' => $moderators_list,
		'PICS' => $catrows[$i]['count'],
		'COMMENTS' => $cat_total_comments,
		'LAST_COMMENT_INFO' => $last_comment_info,
		'LAST_PIC_INFO' => $last_pic_info)
	);
}
// END of Categories Index

/*
+----------------------------------------------------------
| Recent Public Pics
+----------------------------------------------------------
*/
if ($album_config['disp_late'] == 1)
{
	if ($allowed_cat != '')
	{
		$sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_user_ip, p.pic_username, p.pic_time, p.pic_cat_id, p.pic_view_count, u.user_id, u.username, u.user_level, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
			FROM ". ALBUM_TABLE ." AS p
				LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_CAT_TABLE ." AS ct ON p.pic_cat_id = ct.cat_id
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
			WHERE p.pic_cat_id IN ($allowed_cat) 
				AND ( p.pic_approval = 1 OR ct.cat_approval = 0 )
			GROUP BY p.pic_id
			ORDER BY p.pic_time DESC
			LIMIT " . $album_config['img_cols'] * $album_config['img_rows'];
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query recent pics information', '', __LINE__, __FILE__, $sql);
		}

		$recentrow = array();
		while( $row = $db->sql_fetchrow($result) )
		{
			$recentrow[] = $row;
		}
		$db->sql_freeresult($result);

		$template->assign_block_vars('recent_pics_block', array());

		if (sizeof($recentrow) > 0)
		{
			for ($i = 0; $i < sizeof($recentrow); $i += $album_config['img_cols'])
			{
				$template->assign_block_vars('recent_pics_block.recent_pics', array());

				for ($j = $i; $j < ($i + $album_config['img_cols']); $j++)
				{
					if( $j >= sizeof($recentrow) )
					{
						break;
					}

					$template->assign_block_vars('recent_pics_block.recent_pics.recent_col', array(
						'U_PIC' => ($album_config['fullpic_popup']) ? append_sid("album_pic.$phpEx?pic_id=". $recentrow[$j]['pic_id']) : append_sid("album_showpage.$phpEx?pic_id=". $recentrow[$j]['pic_id']),
						'THUMBNAIL' => append_sid("album_thumbnail.$phpEx?pic_id=". $recentrow[$j]['pic_id']),
						'DESC' => $recentrow[$j]['pic_desc'])
					);

					$recentrow[$j]['username'] = username_level_color($recentrow[$j]['username'], $recentrow[$j]['user_level'], $recentrow[$j]['user_id']);

					if( ($recentrow[$j]['user_id'] == ALBUM_GUEST) or ($recentrow[$j]['username'] == '') )
					{
						$recent_poster = ($recentrow[$j]['pic_username'] == '') ? $lang['Guest'] : $recentrow[$j]['pic_username'];
					}
					else
					{
						$recent_poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $recentrow[$j]['user_id']) .'" class="gensmall">'. $recentrow[$j]['username'] .'</a>';
					}
				
					$rating_image = ImageRating($recentrow[$j]['rating']);
				
					$template->assign_block_vars('recent_pics_block.recent_pics.recent_detail', array(
						'TITLE' => '<a href = "album_showpage.' . $phpEx . '?pic_id=' . $recentrow[$j]['pic_id'] . '">' . $recentrow[$j]['pic_title'] . '</a>',
						'POSTER' => $recent_poster,
						'TIME' => create_date($board_config['default_dateformat'], $recentrow[$j]['pic_time'], $board_config['board_timezone']),
						'VIEW' => $recentrow[$j]['pic_view_count'],
						'RATING' => ($album_config['rate'] == 1) ? ( '<b>' . $lang['Rating'] . ':</b> ' . $rating_image . '<br />') : '',
						'IP' => ($userdata['user_level'] == ADMIN) ? '<b>' . $lang['IP_Address'] . ':</b> <a href="http://www.nic.com/cgi-bin/whois.cgi?query=' . decode_ip($recentrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($recentrow[$j]['pic_user_ip']) .'</a><br />' : '')
					);
				}
			}
		}
		else
		{
			//
			// No Pics Found
			//
			$template->assign_block_vars('recent_pics_block.no_pics', array());
		}
	}
	else
	{
		//
		// No Cats Found
		//
		$template->assign_block_vars('recent_pics_block.no_pics', array());
	}
}

/* 
+---------------------------------------------------------- 
| Highest Rated Pics 
| by MarkFulton.com ...added RAND() part so highest pics dont always appear in same order..
+---------------------------------------------------------- 
*/ 
if ($album_config['disp_high'] == 1)
{
	if ($allowed_cat != '') 
	{ 
	   $sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_user_ip, p.pic_username, p.pic_time, p.pic_cat_id, p.pic_view_count, u.user_id, u.username, u.user_level, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments 
    		FROM ". ALBUM_TABLE ." AS p 
            	LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id 
            	LEFT JOIN ". ALBUM_CAT_TABLE ." AS ct ON p.pic_cat_id = ct.cat_id 
            	LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id 
            	LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id 
         	WHERE p.pic_cat_id IN ($allowed_cat) 
         		AND ( p.pic_approval = 1 OR ct.cat_approval = 0 ) 
         	GROUP BY p.pic_id 
	     	ORDER BY rating DESC, RAND()
	        LIMIT " . $album_config['img_cols'] * $album_config['img_rows']; 
   		if( !($result = $db->sql_query($sql)) ) 
		{ 
    		message_die(GENERAL_ERROR, 'Could not query highest rated pics information', '', __LINE__, __FILE__, $sql); 
   		} 

	   	$highestrow = array(); 
   		while( $row = $db->sql_fetchrow($result) ) 
   		{ 
	    	$highestrow[] = $row; 
   		} 
		$db->sql_freeresult($result);

		$template->assign_block_vars('highest_pics_block', array());

	   	if (sizeof($highestrow) > 0) 
   		{ 
	    	for ($i = 0; $i < sizeof($highestrow); $i += $album_config['img_cols']) 
      		{ 
	        	$template->assign_block_vars('highest_pics_block.highest_pics', array()); 

	         	for ($j = $i; $j < ($i + $album_config['img_cols']); $j++) 
         		{ 
	            	if( $j >= sizeof($highestrow) ) 
            		{ 
               			break; 
		            } 

		            $template->assign_block_vars('highest_pics_block.highest_pics.highest_col', array( 
	    	           'U_PIC' => ($album_config['fullpic_popup']) ? append_sid("album_pic.$phpEx?pic_id=". $highestrow[$j]['pic_id']) : append_sid("album_showpage.$phpEx?pic_id=". $highestrow[$j]['pic_id']), 
	    	           'THUMBNAIL' => append_sid("album_thumbnail.$phpEx?pic_id=". $highestrow[$j]['pic_id']), 
	    	           'DESC' => $highestrow[$j]['pic_desc']) 
            		); 

					$highestrow[$j]['username'] = username_level_color($highestrow[$j]['username'], $highestrow[$j]['user_level'], $highestrow[$j]['user_id']);

	            	if( ($highestrow[$j]['user_id'] == ALBUM_GUEST) or ($highestrow[$j]['username'] == '') ) 
            		{ 
	               		$highest_poster = ($highestrow[$j]['pic_username'] == '') ? $lang['Guest'] : $highestrow[$j]['pic_username']; 
		            } 
    		        else 
        		    { 
	            		$highest_poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $highestrow[$j]['user_id']) .'" class="gensmall">'. $highestrow[$j]['username'] .'</a>'; 
            		} 
			
					$rating_image = ImageRating($highestrow[$j]['rating']);

		            $template->assign_block_vars('highest_pics_block.highest_pics.highest_detail', array( 
		               'H_TITLE' => '<a href = "album_showpage.' . $phpEx . '?pic_id=' . $highestrow[$j]['pic_id'] . '">' . $highestrow[$j]['pic_title'] . '</a>', 
		               'H_POSTER' => $highest_poster, 
		               'H_TIME' => create_date($board_config['default_dateformat'], $highestrow[$j]['pic_time'], $board_config['board_timezone']), 
		               'H_VIEW' => $highestrow[$j]['pic_view_count'], 
		               'H_RATING' => ($album_config['rate'] == 1) ? ( '<b>' . $lang['Rating'] . ':</b> ' . $rating_image . '<br />') : '', 
		               'H_IP' => ($userdata['user_level'] == ADMIN) ? '<b>' . $lang['IP_Address'] . ':</b> <a href="http://www.nic.com/cgi-bin/whois.cgi?query=' . decode_ip($highestrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($highestrow[$j]['pic_user_ip']) .'</a><br />' : '') 
		            ); 
        		} 
      		} 
   		} 
   		else 
   		{ 
    		// 
      		// No Pics Found 
			// 
	    	$template->assign_block_vars('highest_pics_block.no_pics', array()); 
   		} 
	} 
	else 
	{ 
		// 
   		// No Cats Found 
   		// 
	   	$template->assign_block_vars('highest_pics_block.no_pics', array()); 
	} 
} 

/*
+----------------------------------------------------------
| Random Pics 
| by CLowN
+----------------------------------------------------------
*/
if ($album_config['disp_rand'] == 1)
{
	if ($allowed_cat != '')
	{
		$sql = "SELECT p.pic_id, p.pic_title, p.pic_desc, p.pic_user_id, p.pic_user_ip, p.pic_username, p.pic_time, p.pic_cat_id, p.pic_view_count, u.user_id, u.username, u.user_level, r.rate_pic_id, AVG(r.rate_point) AS rating, COUNT(DISTINCT c.comment_id) AS comments
			FROM ". ALBUM_TABLE ." AS p
				LEFT JOIN ". USERS_TABLE ." AS u ON p.pic_user_id = u.user_id
				LEFT JOIN ". ALBUM_CAT_TABLE ." AS ct ON p.pic_cat_id = ct.cat_id
				LEFT JOIN ". ALBUM_RATE_TABLE ." AS r ON p.pic_id = r.rate_pic_id
				LEFT JOIN ". ALBUM_COMMENT_TABLE ." AS c ON p.pic_id = c.comment_pic_id
			WHERE p.pic_cat_id IN ($allowed_cat) 
				AND ( p.pic_approval = 1 OR ct.cat_approval = 0 )
			GROUP BY p.pic_id
			ORDER BY RAND()
			LIMIT " . $album_config['img_cols'] * $album_config['img_rows'];
		if( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query rand pics information', '', __LINE__, __FILE__, $sql);
		}

		$randrow = array();
		while( $row = $db->sql_fetchrow($result) )
		{
			$randrow[] = $row;
		}
		$db->sql_freeresult($result);

		$template->assign_block_vars('random_pics_block', array());

		if (sizeof($randrow) > 0)
		{
			for ($i = 0; $i < sizeof($randrow); $i += $album_config['img_cols'])
			{
				$template->assign_block_vars('random_pics_block.rand_pics', array());

				for ($j = $i; $j < ($i + $album_config['img_cols']); $j++)
				{
					if( $j >= sizeof($randrow) )
					{
						break;
					}

					$template->assign_block_vars('random_pics_block.rand_pics.rand_col', array(
						'U_PIC' => ($album_config['fullpic_popup']) ? append_sid("album_pic.$phpEx?pic_id=". $randrow[$j]['pic_id']) : append_sid("album_showpage.$phpEx?pic_id=". $randrow[$j]['pic_id']),
						'THUMBNAIL' => append_sid("album_thumbnail.$phpEx?pic_id=". $randrow[$j]['pic_id']),
						'DESC' => $randrow[$j]['pic_desc'])
					);

					$randrow[$j]['username'] = username_level_color($randrow[$j]['username'], $randrow[$j]['user_level'], $randrow[$j]['user_id']);

					if( ($randrow[$j]['user_id'] == ALBUM_GUEST) or ($randrow[$j]['username'] == '') )
					{
						$rand_poster = ($randrow[$j]['pic_username'] == '') ? $lang['Guest'] : $randrow[$j]['pic_username'];
					}
					else
					{
						$rand_poster = '<a href="'. append_sid("profile.$phpEx?mode=viewprofile&amp;". POST_USERS_URL .'='. $randrow[$j]['user_id']) .'" class="gensmall">'. $randrow[$j]['username'] .'</a>';
					}
				
					$rating_image = ImageRating($randrow[$j]['rating']);
				
					$template->assign_block_vars('random_pics_block.rand_pics.rand_detail', array(
						'TITLE' => '<a href = "album_showpage.' . $phpEx . '?pic_id=' . $randrow[$j]['pic_id'] . '">' . $randrow[$j]['pic_title'] . '</a>',
						'POSTER' => $rand_poster,
						'TIME' => create_date($board_config['default_dateformat'], $randrow[$j]['pic_time'], $board_config['board_timezone']),
						'VIEW' => $randrow[$j]['pic_view_count'],
						'RATING' => ($album_config['rate'] == 1) ? ( '<b>' . $lang['Rating'] . ':</b> ' . $rating_image . '<br />') : '',
						'COMMENTS' => ($album_config['comment'] == 1) ? ( '<a href="'. append_sid("album_showpage.$phpEx?pic_id=". $randrow[$j]['pic_id']) . '">' . $lang['Comments'] . '</a>: ' . $randrow[$j]['comments'] . '<br />') : '',
						'IP' => ($userdata['user_level'] == ADMIN) ? '<b>' . $lang['IP_Address'] . ':</b> <a href="http://www.nic.com/cgi-bin/whois.cgi?query=' . decode_ip($randrow[$j]['pic_user_ip']) . '" target="_blank">' . decode_ip($randrow[$j]['pic_user_ip']) .'</a><br />' : '')
					);
				}
			}
		}
		else
		{
			//
			// No Pics Found
			//
			$template->assign_block_vars('random_pics_block.no_pics', array());
		}
	}
	else
	{
		//
		// No Cats Found
		//
		$template->assign_block_vars('random_pics_block.no_pics', array());
	}
}


/*
+----------------------------------------------------------
| Start output the page
+----------------------------------------------------------
*/
$page_title = $lang['Album'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'album_index_body.tpl')
);

$template->assign_vars(array(
	'L_CATEGORY' => $lang['Category'],
	'L_PICS' => $lang['Pics'],
	'L_COMMENTS' => $lang['Comments'],
	'L_LAST_COMMENT' => $lang['Last_Comment'],
	'L_LAST_PIC' => $lang['Last_Pic'],
	'L_HIGHEST_RATED_PICS' => $lang['Highest_Rated_Pics'],
	'L_THAT_CONTAINS' => $lang['That_contains'],
	'L_PIC_DESC' => $lang['Pic_Desc'],
	'L_RANDOM_PICS' => $lang['Random_Pics'],

	'U_YOUR_PERSONAL_GALLERY' => append_sid("album_personal.$phpEx?user_id=". $userdata['user_id']),
	'L_YOUR_PERSONAL_GALLERY' => $lang['Your_Personal_Gallery'],

	'U_USERS_PERSONAL_GALLERIES' => append_sid("album_personal_index.$phpEx"),
	'L_USERS_PERSONAL_GALLERIES' => $lang['Users_Personal_Galleries'],

	'S_COLS' => $album_config['img_cols'],
	'S_COL_WIDTH' => (100 / $album_config['img_cols']) . '%',
	'TARGET_BLANK' => ($album_config['fullpic_popup']) ? 'target="_blank"' : '',
	'L_RECENT_PUBLIC_PICS' => $lang['Recent_Public_Pics'],
	'L_NO_PICS' => $lang['No_Pics'],
	'L_PIC_TITLE' => $lang['Pic_Title'],
	'L_VIEW' => $lang['View'],
	'L_POSTER' => $lang['Poster'],
	'L_POSTED' => $lang['Posted'],
	'L_PUBLIC_CATS' => $lang['Public_Categories'])
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
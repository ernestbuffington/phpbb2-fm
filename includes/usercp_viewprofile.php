<?php
/** 
*
* @package includes
* @version $Id: usercp_viewprofile.php,v 1.5.2.3 2004/11/18 17:49:45 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
	exit;
}

include($phpbb_root_path.'includes/bbcode.'.$phpEx);

$profiledata = get_userdata($HTTP_GET_VARS[POST_USERS_URL]);
if ( !$profiledata || empty($profiledata['user_id']) || $profiledata['user_id'] == ANONYMOUS || $profiledata['user_id'] == 1 )
{
	message_die(GENERAL_MESSAGE, $lang['No_user_id_specified']);
}
$language = $board_config['default_lang'];


//
// Update the profile view list
//
if ($profiledata['user_id'] <> $userdata['user_id'])
{
	$sql = "UPDATE " . USERS_TABLE . "
		SET user_profile_view = 1
		WHERE user_id = " . $profiledata['user_id'];
	if ( !$db->sql_query($sql) )
	{
	   message_die(GENERAL_ERROR, 'Could not update user data', '', __LINE__, __FILE__, $sql);
	}

	$sql = "SELECT * 
		FROM " . PROFILE_VIEW_TABLE . "
		WHERE user_id = " . $profiledata['user_id'] . "
			AND viewer_id = " . $userdata['user_id'];
	if ( $result = $db->sql_query($sql) )
	{
		if ( !$row = $db->sql_fetchrow($result) )
		{
			$sql = "INSERT INTO " . PROFILE_VIEW_TABLE . " (user_id, viewer_id, view_stamp, counter) 
				VALUES (" . $profiledata['user_id'] . ", " . $userdata['user_id'] . ", " . time() . ", 1)";
			if ( !$db->sql_query($sql) )
			{
			   	message_die(GENERAL_ERROR, 'Could not insert profile views', '', __LINE__, __FILE__, $sql);
			}
		}
		else
		{
			$sql = "UPDATE " . PROFILE_VIEW_TABLE . "
				SET view_stamp = " . time() . ", counter = counter + 1
				WHERE user_id = " . $profiledata['user_id'] . "
					AND viewer_id = " . $userdata['user_id'];
			if ( !$db->sql_query($sql) )
			{
			   	message_die(GENERAL_ERROR, 'Could not update profile views', '', __LINE__, __FILE__, $sql);
			}
		}
	}
}


//
// Ranks
//
$poster_rank = $rank_image = '';
if ( $profiledata['user_rank'] )
{
	$sql = "SELECT *
		FROM " . RANKS_TABLE . "
		WHERE rank_id = " . $profiledata['user_rank'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain user speical rank ', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $row = $db->sql_fetchrow($result) )
	{
		$poster_rank = $row['rank_title'];
		$rank_image = ( $row['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $row['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
	}
	$db->sql_freeresult($result);
}
else
{
	$sql = "SELECT *
		FROM " . RANKS_TABLE . "
		WHERE rank_special = 0
		ORDER BY rank_min DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain ranks information', '', __LINE__, __FILE__, $sql);
	}

	$ranksrow = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$ranksrow[$row['rank_group']][] = $row;
		$ranksrow[$row['rank_group']]['count']++;
	}
	$db->sql_freeresult($result);

	$sql = "SELECT ug.group_id
		FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g
		WHERE ug.user_id = " . $profiledata['user_id'] . "
			AND g.group_id = ug.group_id
			AND g.group_single_user = 0
		ORDER BY g.group_rank_order ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain user group information.", '', __LINE__, __FILE__, $sql);
	}
	
	$rank_group_id = 0;
	while ( $row = $db->sql_fetchrow($result) )
	{
		if ( isset($ranksrow[$row['group_id']]) )
		{
			$rank_group_id = $row['group_id'];
			break;
		}
	}
	$db->sql_freeresult($result);

	for($i = 0; $i < $ranksrow[$rank_group_id]['count']; $i++)
	{
		if ( $profiledata['user_posts'] >= $ranksrow[$rank_group_id][$i]['rank_min'] )
		{
			$poster_rank = $ranksrow[$rank_group_id][$i]['rank_title'];
			$rank_image = ( $ranksrow[$rank_group_id][$i]['rank_image'] ) ? '<img src="templates/' . (( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images') . '/ranks/' . $ranksrow[$rank_group_id][$i]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" /><br />' : '';
			break;
		}
	}
}


//
// Medal System
//
if ($board_config['allow_medal_display_viewprofile'])
{
	$sql = "SELECT cat_id, cat_title
		FROM " . MEDAL_CAT_TABLE . "
		ORDER BY cat_order";
	if (!($result = $db->sql_query($sql)))
	{
		message_die(GENERAL_ERROR, 'Could not query medal categories list', '', __LINE__, __FILE__, $sql);
	}
	
	$category_rows = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$category_rows[] = $row;
	}
	$db->sql_freeresult($result);

	$sql = "SELECT m.medal_id, mu.user_id
		FROM " . MEDAL_TABLE . " m, " . MEDAL_USER_TABLE . " mu
		WHERE mu.user_id = '" . $profiledata['user_id'] . "'
			AND m.medal_id = mu.medal_id
		ORDER BY m.medal_name";
	if ($result = $db->sql_query($sql))
	{
		$medal_list = $db->sql_fetchrowset($result);
		$medal_count = sizeof($medal_list);
	
		if ($medal_count)
		{
			$medal_count = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $profiledata['user_id'] . "#medal") . '" class="gen">' . $medal_count . '</a>';
	
			$template->assign_block_vars('switch_display_medal', array());
	
			$template->assign_block_vars('switch_display_medal.medal', array(
				'MEDAL_BUTTON' => '<input type="button" class="liteoption" onclick="ToggleBox(\'toggle_medal\')" value="' . $lang['Medal_details'] . '">')
			);
		}
	}
	
	for ($i = 0; $i < sizeof($category_rows); $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];
	
		$sql = "SELECT m.medal_id, m.medal_name,m.medal_description, m.medal_image, m.cat_id, mu.issue_reason, mu.issue_time, c.cat_id, c.cat_title
			FROM " . MEDAL_TABLE . " m, " . MEDAL_USER_TABLE . " mu, " . MEDAL_CAT_TABLE . " c
			WHERE mu.user_id = '" . $profiledata['user_id'] . "'
				AND m.cat_id = c.cat_id
				AND m.medal_id = mu.medal_id
			ORDER BY c.cat_order, m.medal_name, mu.issue_time";
		if ($result = $db->sql_query($sql))
		{
			$row = $rowset = array();
			
			$medal_time = $lang['Medal_time'];
			$medal_reason = $lang['Medal_reason'];
			while ($row = $db->sql_fetchrow($result))
			{
				if (empty($rowset[$row['medal_name']]))
				{
					$rowset[$row['medal_name']]['cat_id'] = $row['cat_id'];
					$rowset[$row['medal_name']]['cat_title'] = $row['cat_title'];
					$rowset[$row['medal_name']]['medal_description'] .= $row['medal_description'];
					$rowset[$row['medal_name']]['medal_image'] = $row['medal_image'];
					$row['issue_reason'] = ( $row['issue_reason'] ) ? $row['issue_reason'] : $lang['Medal_no_reason'];
					$rowset[$row['medal_name']]['medal_issue'] = '<tr>
							<td valign="middle" align="right" nowrap="nowrap"><span class="gen">' . $medal_time . ':&nbsp;</span></td>
							<td><b class="gen">' . create_date($board_config['default_dateformat'], $row['issue_time'], $board_config['board_timezone']) . '</b></td>
						</tr>
						<tr>
							<td valign="middle" align="right" nowrap="nowrap"><span class="gen">' . $medal_reason . ':&nbsp;</span></td>
							<td><b class="gen">' . $row['issue_reason']  . '</b></td>
						</tr>';
					$rowset[$row['medal_name']]['medal_count'] = 1;
				}
				else
				{
					$row['issue_reason'] = ( $row['issue_reason'] ) ? $row['issue_reason'] : $lang['Medal_no_reason'];
					$rowset[$row['medal_name']]['medal_issue'] .= '<tr>
							<td valign="middle" align="right" nowrap="nowrap"><span class="gen">' . $medal_time . ':&nbsp;</span></td>
							<td><b class="gen">' . create_date($board_config['default_dateformat'], $row['issue_time'], $board_config['board_timezone']) . '</b></td>
						</tr>
						<tr>
							<td valign="middle" align="right" nowrap="nowrap"><span class="gen">' . $medal_reason . ':&nbsp;</span></td>
							<td><b class="gen">' . $row['issue_reason'] . '</b></td>
						</tr>';
					$rowset[$row['medal_name']]['medal_count'] += 1;
				}
			}
			$db->sql_freeresult($result);

			$medal_width = ( $board_config['medal_display_width'] ) ? 'width="'.$board_config['medal_display_width'].'"' : '';
			$medal_height = ( $board_config['medal_display_height'] ) ? 'height="'.$board_config['medal_display_height'].'"' : '';
	
			$medal_name = $data = array();
	
			//
			// Should we display this category/medal set?
			//
			$display_medal = 0;

			while (list($medal_name, $data) = @each($rowset))
			{
				if ( $cat_id == $data['cat_id'] ) 
				{ 
					$display_medal = 1; 
				}
	
				if ( !empty($display_medal) )
				{
					$template->assign_block_vars('switch_display_medal.details', array(
						'MEDAL_CAT' => $data['cat_title'],
						'MEDAL_NAME' => $medal_name,
						'MEDAL_DESCRIPTION' => $data['medal_description'],
						'MEDAL_IMAGE' => '<img src="images/medals/' . $data['medal_image'] . '" alt="' . $medal_name . '" title="' . $medal_name . '" />',
						'MEDAL_IMAGE_SMALL' => '<img src="images/medals/' . $data['medal_image'] . '" alt="' . $medal_name . '" title="' . $medal_name . '"' . $medal_width . $medal_height . ' />',
						'MEDAL_ISSUE' => $data['medal_issue'],
						'MEDAL_COUNT' => $lang['Medal_amount'] . '<b>' . $data['medal_count'] . '</b>',
				
						'L_MEDAL_DESCRIPTION' => $lang['Medal_description'])
					);
				
					$display_medal = 0;
				}
			}
		}
	}
}


//
// Output page header and profile_view template
//
$template->set_filenames(array(
	'body' => 'profile_view_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

//
// Calculate the number of days this user has been a member ($memberdays)
// Then calculate their posts per day
//
$regdate = $profiledata['user_regdate'];
$memberdays = max(1, round( ( time() - $regdate ) / 86400 ));
$posts_per_day = $profiledata['user_posts'] / $memberdays;

// Get the users percentage of total posts
if ( $profiledata['user_posts'] != 0  )
{
	$total_posts = get_db_stat('postcount');
	$percentage = ( $total_posts ) ? min(100, ($profiledata['user_posts'] / $total_posts) * 100) : 0;
}
else
{
	$percentage = 0;
}

$avatar_img = '';
if ( $profiledata['user_avatar_type'] && $userdata['user_showavatars'] && ($profiledata['user_allowavatar'] || $userdata['user_level'] == ADMIN || $userdata['user_id'] == $profiledata['user_id'] ) )
{
	switch( $profiledata['user_avatar_type'] )
	{
		case USER_AVATAR_UPLOAD:
			$avatar_img = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $profiledata['user_avatar'] . '" alt="" title="" />' : '';
			break;
		case USER_AVATAR_REMOTE:
			$avatar_img = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $profiledata['user_avatar'] . '" alt="" title="" />' : '';
			break;
		case USER_AVATAR_GALLERY:
			$avatar_img = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $profiledata['user_avatar'] . '" alt="" title="" />' : '';
			break;
	}
}
if ( ( !$avatar_img ) && ( ( $board_config['default_avatar_set'] == 1 ) || ( $board_config['default_avatar_set'] == 2 ) ) && ( $board_config['default_avatar_users_url'] ) )
{
	$avatar_img = '<img src="' . $board_config['default_avatar_users_url'] . '" alt="" title="" />';
}


//
// Online/Offline/Hidden
//
if ( $profiledata['user_session_time'] >= (time()-($board_config['whosonline_time'] * 60)) )
{
	if ( $profiledata['user_allow_viewonline'] )
	{
		$online_status_img = '<a href="' . append_sid("viewonline.$phpEx") . '"><img src="' . $images['icon_online'] . '" alt="' . sprintf($lang['is_online'], $profiledata['username']) . '" title="' . sprintf($lang['is_online'], $profiledata['username']) . '" /></a>';
	}
	else if ( $userdata['user_level'] == ADMIN || $userdata['user_id'] == $profiledata['user_id'] )
	{
		$online_status_img = '<a href="' . append_sid("viewonline.$phpEx") . '"><img src="' . $images['icon_hidden'] . '" alt="' . sprintf($lang['is_hidden'], $profiledata['username']) . '" title="' . sprintf($lang['is_hidden'], $profiledata['username']) . '" /></a>';
	}
	else
	{
		$online_status_img = '<img src="' . $images['icon_offline'] . '" alt="' . sprintf($lang['is_offline'], $profiledata['username']) . '" title="' . sprintf($lang['is_offline'], $profiledata['username']) . '" />';
	}
}
else
{
	$online_status_img = '<img src="' . $images['icon_offline'] . '" alt="' . sprintf($lang['is_offline'], $profiledata['username']) . '" title="' . sprintf($lang['is_offline'], $profiledata['username']) . '" />';
}


//
// Contact info
//
$temp_url = append_sid("profile.$phpEx?mode=addbuddy&amp;" . POST_USERS_URL ."=" . $profiledata['user_id']);
$buddy_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_buddy'] . '" alt="' . $lang['Add_buddy'] . '" title="' . $lang['Add_buddy'] . '" /></a>';

if ( !empty($profiledata['user_viewemail']) || $userdata['user_level'] == ADMIN )
{
	$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $profiledata['user_id']) : 'mailto:' . $profiledata['user_email'];

	$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" /></a>';
	$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
}
else
{
	$email_img = $email = '&nbsp;';
}

$temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=" . $profiledata['user_id']);
$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" /></a>';

if ($profiledata['user_website'])
{
	$template->assign_block_vars('switch_www', array(
		'L_WEBSITE' => $lang['Website'],
		'WWW_IMG' => '<a href="' . $profiledata['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" /></a>')
 	);
}

if ($profiledata['user_stumble'])
{
	$template->assign_block_vars('switch_stumble', array(
		'L_STUMBLE' => $lang['Stumble'],
		'STUMBLE_IMG' => '<a href="' . $profiledata['user_stumble'] . '" target="_userstumble"><img src="' . $images['icon_stumble'] . '" alt="' . $lang['View_stumble'] . '" title="' . $lang['View_stumble'] . '" /></a>')
 	);
}

if ($profiledata['user_icq'])
{
	$template->assign_block_vars('switch_icq', array(
		'L_ICQ_NUMBER' => $lang['ICQ'],
		'ICQ_STATUS_IMG' => '<a href="http://wwp.icq.com/' . $profiledata['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $profiledata['user_icq'] . '&img=5" width="18" height="18" /></a>',
		'ICQ_IMG' => '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $profiledata['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" /></a>')
 	);
}

if ($profiledata['user_aim'])
{
	$template->assign_block_vars('switch_aim', array(
		'L_AIM' => $lang['AIM'],
		'AIM_IMG' => '<a href="aim:goim?screenname=' . str_replace('+', '', $profiledata['user_aim']) . '&amp;message=Hello+Are+you+there?"><img src="http://big.oscar.aol.com/' . $profiledata['user_aim'] . '?on_url='.$images['icon_aim_online'].'&off_url='.$images['icon_aim_offline'].'" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" /></a>')
	);
}

if ($profiledata['user_msnm'])
{
	$template->assign_block_vars('switch_msnm', array(
		'L_MESSENGER' => $lang['MSNM'],
		'MSN_IMG' => '<a href="http://members.msn.com/' . $profiledata['user_msnm'] . '" target="_blank"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" /></a>')
	);
}

if ($profiledata['user_yim'])
{
	$template->assign_block_vars('switch_yim', array(
		'L_YAHOO' => $lang['YIM'],
		'YIM_IMG' => '<a href="ymsgr:sendIM?' . $profiledata['user_yim'] . '&__you+there?"><img src=http://opi.yahoo.com/online?u=' . $profiledata['user_yim'] . '&m=g&t=1" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" /></a>')
	);
}

if ($profiledata['user_xfi'])
{
	$template->assign_block_vars('switch_xfi', array(
		'L_XFI' => $lang['XFI'],
		'XFIRE_IMG' => '<a href="http://www.xfire.com/xf/modules.php?name=XFire&amp;file=profile&amp;uname=' . $profiledata['user_xfi'] . '" target="_blank"><img src="' . $images['icon_xfi'] . '" alt="' . $lang['XFI'] . '" title="' . $lang['XFI'] . '" /></a>')
	);
}

if ($profiledata['user_skype'])
{
	$template->assign_block_vars('switch_skype', array(
		'L_SKYPE' => $lang['skype'], 
		'SKYPE_IMG' => '<a href="#" onClick=window.open("profile_skype_popup.'.$phpEx.'?' . POST_USERS_URL . '=' . $profiledata['user_id'] . '","gesamt","location=no,menubar=no,toolbar=no,scrollbars=auto,width=320,height=500,status=no",title="Skype")><img src="' . $images['icon_skype'] . '" alt="' . $lang['skype'] . '" title="' . $lang['skype'] . '" /</a>', 
		'SKYPE_USER' => '<a href="#" onClick=window.open("profile_skype_popup.'.$phpEx.'?' . POST_USERS_URL . '=' . $profiledata['user_id'] . '","gesamt","location=no,menubar=no,toolbar=no,scrollbars=auto,width=320,height=500,status=no",title="Skype")><img alt="' . $lang['skype'] . '" title="' . $lang['skype'] . '" src="http://mystatus.skype.com/smallicon/' . prepare_skype_http($profiledata['user_skype']) . '" /></a>')
	);
}

if ($profiledata['user_gtalk'])
{
	$template->assign_block_vars('switch_gtalk', array(
		'L_GTALK' => $lang['GTALK'],
		'GTALK' => $profiledata['user_gtalk']) 
	);
}

//
// Profile info
//
$user_points = $donate_points = $user_sig = $flag = '';

if ( $profiledata['user_from'] || $profiledata['user_from_flag'] && $profiledata['user_from_flag'] != 'blank.gif' )
{
	if ($profiledata['user_from_flag']) 
	{ 
		$flag = ' &nbsp;<img src="images/flags/' . $profiledata['user_from_flag'] . '" alt="' . $lang['Country_Flag'] . ': '; 
	
		$sql = "SELECT flag_name 
			FROM " . FLAG_TABLE . " 
			WHERE flag_image = '" . $profiledata['user_from_flag'] . "'"; 
		if( !($result = $db->sql_query($sql)) ) 
		{ 
			message_die(GENERAL_ERROR, 'Could not query flagname', '', __LINE__, __FILE__, $sql); 
		} 
	
		$flag_name = $db->sql_fetchrow($result); 
		$flag_name_real = $flag_name['flag_name']; 
		$flag .= $flag_name_real . '" title="' . $lang['Country_Flag'] . ': ' . $flag_name_real . '">'; 
	} 
	
	$template->assign_block_vars('switch_location', array(
		'L_LOCATION' => $lang['Location'],
		'LOCATION' => $profiledata['user_from'] . $flag)
	);
}

$temp_url = append_sid("search.$phpEx?search_author=" . urlencode($profiledata['username']) . "&amp;showresults=posts");
$search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '" title="' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '" /></a>';
$search = '<a href="' . $temp_url . '">' . sprintf($lang['Search_user_posts'], $profiledata['username']) . '</a>';

if ( $profiledata['user_attachsig'] && $profiledata['user_sig'] && $board_config['allow_sig'] && $board_config['profile_show_sig'] )
{
    $user_sig = $profiledata['user_sig'];
    $user_sig_bbcode_uid = $profiledata['user_sig_bbcode_uid'];
    
	if ( !$board_config['allow_html'] && $profiledata['user_allowhtml'] )
    {
    	$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
    }
       	
    if ( $board_config['allow_bbcode'] && $user_sig_bbcode_uid != '' )
   	{
   		$user_sig = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($user_sig, $user_sig_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $user_sig);
   	}
   		
   	$user_sig = make_clickable($user_sig);

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
	        
	if( !empty($orig_word) )
	{
    	$user_sig = preg_replace($orig_word, $replacement_word, $user_sig);
	}
		
    if ( $profiledata['user_allowsmile'] )
    {
    	$user_sig = smilies_pass($user_sig);
    }
        
    $user_sig = str_replace("\n", "\n<br />\n", $user_sig);
   
   	$template->assign_block_vars('switch_user_sig_block', array());
}

if ($profiledata['user_realname'])
{
	$template->assign_block_vars('switch_realname', array(
		'L_REALNAME' => $lang['real_name'],
		'REALNAME' => $profiledata['user_realname'])
	);
}

if ($profiledata['user_occ'])
{
	$template->assign_block_vars('switch_occ', array(
		'L_OCCUPATION' => $lang['Occupation'],
		'OCCUPATION' => $profiledata['user_occ'])
	);
}


if ($profiledata['user_interests'])
{
	$template->assign_block_vars('switch_interests', array(
		'L_INTERESTS' => $lang['Interests'],
		'INTERESTS' => $profiledata['user_interests'])
	);
}

if ($profiledata['user_birthday'] != 999999)
{
	$this_date = create_date('md', time(), $board_config['board_timezone']);
 	include($phpbb_root_path . 'includes/chinese.'.$phpEx);
	$chinese = get_chinese_year(realdate('Ymd', $profiledata['user_birthday']));
	$u_chinese = $images[$chinese];
	$chinese_img = ($chinese == 'Unknown') ? '' : '<img src="' . $u_chinese . '" alt="' . $lang[$chinese] . '" title="' . $lang[$chinese] . '" align="top" />';

	$user_birthdate = realdate('md', $profiledata['user_birthday']);
	$i = 0;
	while ($i < 26)
	{
		if ($user_birthdate >= $zodiacdates[$n] & $user_birthdate <= $zodiacdates[$i+1])
		{
			$zodiac = $lang[$zodiacs[($i/2)]];
			$u_zodiac = $images[$zodiacs[($i/2)]];
			$zodiac_img = '<img src="' . $u_zodiac . '" alt="' . $zodiac . '" title="' . $zodiac . '" align="top" />';
			$i = 26;			
		} 
		else 
		{
			$i = $i + 2;
		}
	}
	$user_birthday = realdate($lang['DATE_FORMAT'], $profiledata['user_birthday']);
	
	if ( $this_date == $user_birthdate )
    {
      	$cake = ' &nbsp;<img src="' . $images['icon_cake'] . '" alt="' . $lang['Greeting_Messaging'] . '" title="' . $lang['Greeting_Messaging'] . '" />';
    }

	$template->assign_block_vars('switch_bday', array(
		'L_BIRTHDAY' => $lang['Birthday'], 
		'L_ZODIAC' => $lang['Zodiac'],
		'L_CHINESE' => $lang['Chinese_zodiac'],
		'CAKE' => $cake,
		'ZODIAC' => $zodiac,
		'ZODIAC_IMG' => $zodiac_img,
		'CHINESE_IMG' => $chinese_img,
		'BIRTHDAY' => ( $profiledata['user_birthday'] != 999999 ) ? $poster_birthday = realdate($lang['DATE_FORMAT'], $profiledata['user_birthday']) : $poster_birthday = $lang['No_birthday_specify'], 
		'CHINESE' => $lang[$chinese])
	);
}

if ($profiledata['user_gender']) 
{ 
	switch ($profiledata['user_gender']) 
    { 
    	case 1: 
    		$gender = $lang['Male'];
    		break; 
        case 2: 
        	$gender = $lang['Female'];
        	break; 
        default:
        	$gender = $lang['No_gender_specify']; 
    		break; 
	} 

	$template->assign_block_vars('switch_gender', array(
		'L_GENDER' => $lang['Gender'], 
		'GENDER' => $gender)
	);
}

if ($board_config['points_post'] || $board_config['points_browse'])
{
	$user_points = ($userdata['user_level'] == ADMIN || user_is_authed($userdata['user_id'])) ? '<a href="' . append_sid("pointscp.$phpEx?" . POST_USERS_URL . "=" . $profiledata['user_id']) . '" class="gen" title="' . sprintf($lang['Points_link_title'], $board_config['points_name']) . '">' . $profiledata['user_points'] . '</a>' : $profiledata['user_points'];

	if ($board_config['points_donate'] && $userdata['user_id'] != ANONYMOUS && $userdata['user_id'] != $profiledata['user_id'])
	{
		$donate_points = ' : ' . sprintf($lang['Points_donate'], '<a href="' . append_sid("pointscp.$phpEx?mode=donate&amp;" . POST_USERS_URL . "=" . $profiledata['user_id']) . '" class="genmed" title="' . sprintf($lang['Points_link_title_2'], $board_config['points_name']) . '">', '</a>');
	}
	
	$template->assign_block_vars('switch_points', array(
		'L_POINTS' => $board_config['points_name'],
		'POINTS' => $user_points,
		'DONATE_POINTS' => $donate_points)
	);
}


//
// Get Shop items and effects
//
$hasitems = false;
$itempurge = str_replace('Þ', '', $profiledata['user_items']);
$itemarray = explode('ß', $itempurge);
$itemcount = sizeof($itemarray);
for ($xe = 0; $xe < $itemcount; $xe++)
{
	if ($itemarray[$xe] != NULL)
	{
		$hasitems = true;
		if (@file_exists($phpbb_root_path . 'images/shop/' . $itemarray[$xe] . '.jpg'))
		{
			$user_items .= ' <img src="images/shop/' . $itemarray[$xe] . '.jpg" title="' . $itemarray[$xe] . '" alt="' . $itemaray[$xe] . '" />';
		} 
		else if (@file_exists($phpbb_root_path . 'images/shop/' . $itemarray[$xe] . '.gif'))
		{
			$user_items .= ' <img src="images/shop/' . $itemarray[$xe] . '.gif" title="' . $itemarray[$xe] . '" alt="' . $itemaray[$xe] . '" />';
		}
	}
}

if ($hasitems)
{
	$usernameurl = '<a href="' . append_sid('shop.'.$phpEx.'?action=inventory&searchid=' . $profiledata['user_id'], true) . '" class="genmed">' . sprintf($lang['User_Inventory'], $profiledata['username']) . '</a><br />';
	
	if ( $board_config['viewprofile'] == 'images' )
	{
		$template->assign_block_vars('switch_items', array(
			'L_ITEMS' => $lang['Items'],
			'INVENTORYPICS' => $user_items)
		);
	}
}

$shoparray = explode('ß', $board_config['specialshop']);
$shoparraycount = sizeof($shoparray);
$shopstatarray = array();
for ($x = 0; $x < $shoparraycount; $x++)
{
	$temparray = explode('Þ', $shoparray[$x]);
	$shopstatarray[] = $temparray[0];
	$shopstatarray[] = $temparray[1];
}

if ( $shopstatarray[3] == 'enabled' ) 
{
	$usereffects = explode('ß', $profiledata['user_effects']);
	$userprivs = explode('ß', $profiledata['user_privs']);
	$usercustitle = explode('ß', $profiledata['user_custitle']);
	$userbs = array();
	$usercount = sizeof($userprivs);
	for ($x = 0; $x < $usercount; $x++) 
	{ 
	$temppriv = explode('Þ', $userprivs[$x]); 
		$userbs[] = $temppriv[0]; 
		$userbs[] = $temppriv[1]; 
	}
	$usercount = sizeof($usereffects);
	for ($x = 0; $x < $usercount; $x++) 
	{ 
	$temppriv = explode('Þ', $usereffects[$x]); 
		$userbs[] = $temppriv[0]; 
		$userbs[] = $temppriv[1]; 
	}
	$usercount = sizeof($usercustitle);
	for ($x = 0; $x < $usercount; $x++) 
	{ 
	$temppriv = explode('Þ', $usercustitle[$x]); 
		$userbs[] = $temppriv[0]; 
		$userbs[] = $temppriv[1]; 
	}

	if ((($userbs[24] == 'on') && ($shopstatarray[24] == 'on')) || (($userbs[20] == 'on') && ($shopstatarray[22] == 'on')) || (($userbs[22] == 'on') && ($shopstataray[20] = 'on')) || (($userbs[18] == 'on') && ($shopstatarray[18] == 'on'))) 
	{
		$titleeffects = '<span style="height:10';
		if (($userbs[22] == 'on') && ($shopstatarray[20] == 'on')) 
		{ 
			$titleeffects .= "; filter:shadow(color=#" . $userbs[23] . ", strength=5)"; 
		}
		if (($userbs[20] == 'on') && ($shopstatarray[22] == 'on')) 
		{ 
			$titleeffects .= "; filter:glow(color=#".$userbs[21].", strength=5)"; 
		}
		if (($userbs[24] == 'on') && ($shopstatarray[24] == 'on')) 
		{ 
			$poster_rank = $userbs[25]; 
		}
		if (($userbs[18] == 'on') && ($shopstatarray[18] == 'on')) 
		{ 
			$poster_rank = '<span style="color:'. $userbs[19] . '">' . $poster_rank . '</span>'; 
		}
		$titleeffects .= '">' . $poster_rank . '</span>';
		$poster_rank = $titleeffects;
	}
	
	if (($shopstatarray[6] == 'on') && ($userbs[2] != 'on') && ($profiledata['user_level'] != 1)) 
	{ 
		$avatar_img = ''; 
	}
	
	if (($shopstatarray[10] == 'on') && ($userbs[6] != 'on') && ($profiledata['user_level'] != 1)) 
	{	 
		$poster_rank = $lang['None']; 
		$rank_image = ''; 
	}
}
//
// Jobs
//
if ($board_config['jobs_status'])
{
	$sql = "SELECT job_name
		FROM " . EMPLOYED_TABLE . "
		WHERE user_id = " . $profiledata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, $lang['jobs_error_temployed'], '', __LINE__, __FILE__, $sql);
	}
	$sql_count = $db->sql_numrows($result);
	
	$jobs_array = array();
	for ($iv = 0; $iv < $sql_count; $iv++)
	{
		if (!( $row = $db->sql_fetchrow($result) ))
		{
			message_die(GENERAL_ERROR, $lang['jobs_error_temployed'], '', __LINE__, __FILE__, $sql);
		}
	
		$jobs_array[] = $row['job_name'];
	}
	
	$template->assign_block_vars('switch_jobs', array(
		'L_JOBS' => $lang['jobs'],
		'JOBS' => ( !empty($jobs_array[0]) ) ? implode(', ', $jobs_array) : $lang['jobs_unemployed'])
	);
}

// 
// Ratings
//
include($phpbb_root_path . 'includes/functions_rating.'.$phpEx);
$rating_config = get_rating_config('1');

if ( $rating_config[1] == 1 )
{
	// Rated posts by this user
	$sql = 'SELECT p.post_id 
		FROM '.POSTS_TABLE.' p, '.RATING_TABLE.' r 
		WHERE p.poster_id = '.$profiledata['user_id'].' 
			AND p.post_id = r.post_id LIMIT 1';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain rating information', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $row = $db->sql_fetchrow($result) )
	{
		$u_link = append_sid('ratings.'.$phpEx.'?type=p&postedby='.$profiledata['user_id']);
		$l_link = sprintf($lang['Rated_posts_by'], $profiledata['username']);
		
		$template->assign_block_vars('rating', array( 
			"L_LINK" => $l_link,
			"U_LINK" => $u_link)
		);
	}

	// If not anonymous
	if ( $profiledata['rating_status'] != 1 )
	{
		// Ratings by this user
		$sql = 'SELECT user_id 
			FROM '.RATING_TABLE.' 
			WHERE user_id = '.$profiledata['user_id'].' 
			LIMIT 1';
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain rating information', '', __LINE__, __FILE__, $sql);
		}
		if ( $row = $db->sql_fetchrow($result) )
		{
			$u_link = append_sid('ratings.'.$phpEx.'?type=p&ratedby='.$profiledata['user_id']);
			$l_link = sprintf($lang['Ratings_by'], $profiledata['username']);
			
			$template->assign_block_vars('rating', array( 
				"L_LINK" => $l_link,
				"U_LINK" => $u_link)
			);
		}
	}
}


//
// Referral 
//
if ($board_config['referral_enable'])
{
	$sql = "SELECT COUNT(referral_id) AS referral_total 
		FROM " . REFERRAL_TABLE . "
		WHERE ruid = " . $profiledata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain referral information', '', __LINE__, __FILE__, $sql);
	}
	$referral_total = $db->sql_fetchrow($result);
	
	$referral_total = $referral_total['referral_total'];

	$temp_url = append_sid("profile_referal_popup.$phpEx?ruid=" . urlencode($profiledata['user_id']));
	$temp_url = "<a href=\"#\" onclick=\"window.open('" . $temp_url . "', '_blank', 'width=500,height=250,resizable=0,scrollbars=1,toolbar=0,location=0,directories=0,status=0,menubar=0'); return false;\" class=\"gen\">" . $referral_total . "</a></b>";
	$referral_url = (($referral_total) ? $temp_url : 0) . '<br />' . sprintf($lang['Referral_Text'], $board_config['referral_reward'], $board_config['points_name']) . '<a href="'. real_path('profile.'.$phpEx.'?mode=' . REGISTER_MODE . '&amp;ruid=' . $profiledata['user_id']) . '"><i>' . real_path('profile.'.$phpEx.'?mode=' . REGISTER_MODE . '&amp;ruid=' . $profiledata['user_id']) . '</i></a>';

	$template->assign_block_vars('switch_referral_on', array(
		'L_REFERRAL_TOTAL' => $lang['Referrals_Total'],
		'REFERRAL_URL' => $referral_url)
	);
}


//
// Karma
//
if ($board_config['allow_karma'])
{
	$template->assign_block_vars('switch_karma', array(
		'L_KARMA' => $lang['Karma'],
		'KARMA' => '+' . $profiledata['karma_plus'] . ' / -' . $profiledata['karma_minus'])
	);
}


//
// Activity Plus
//
include_once($phpbb_root_path . 'includes/functions_amod_plus.'.$phpEx);
unset($trophy_count, $trophy_holder, $trophy);			
if ( ($board_config['ina_show_view_profile']) && ($profiledata['user_trophies'] > 0) && ($profiledata['user_id'] != ANONYMOUS) )	
{		
	$template->assign_block_vars('trophy', array(
		'PROFILE_TROPHY' => "<a href=\"javascript:Trophy_Popup('". $phpbb_root_path ."activity_trophy_popup.$phpEx?user=". $profiledata['user_id'] ."&sid=". $userdata['session_id'] ."','New_Window','400','380','yes')\" onclick=\"blur()\" class='gen'>". $profiledata['user_trophies'] . "</a>",
		'TROPHY_TITLE' => $lang['Trohpy'])
	);	
}		
		
if ( ($board_config['ina_char_show_viewprofile']) && ($profiledata['ina_char_name']) )
{
	$template->assign_block_vars('profile_char', array(
		'CHAR_PROFILE' => AMP_Profile_Char($profiledata['user_id'], ''))
	);		
}
		
$poster_rank .= Amod_Trophy_King_Image($profiledata['user_id']);

if (@function_exists('get_html_translation_table'))
{
	$u_search_author = urlencode(strtr($profiledata['username'], array_flip(get_html_translation_table(HTML_ENTITIES))));
}
else
{
	$u_search_author = urlencode(str_replace(array('&amp;', '&#039;', '&quot;', '&lt;', '&gt;'), array('&', "'", '"', '<', '>'), $profiledata['username']));
}

//
// Generate page
//
$page_title = sprintf($lang['Viewing_user_profile'], $profiledata['username']);
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

display_upload_attach_box_limits($profiledata['user_id']);

$template->assign_vars(array(
	'USERNAME' => username_level_color($profiledata['username'], $profiledata['user_level'], $profiledata['user_id']),
	'JOINED' => create_date($lang['JOINED_DATE_FORMAT'], $profiledata['user_regdate'], $board_config['board_timezone']),
	'POSTER_RANK' => $poster_rank,
	'RANK_IMAGE' => $rank_image,
	'POSTS_PER_DAY' => $posts_per_day,
	'POSTS' => $profiledata['user_posts'],
	'PERCENTAGE' => $percentage . '%', 
	'POST_DAY_STATS' => sprintf($lang['User_post_day_stats'], $posts_per_day), 
	'POST_PERCENT_STATS' => sprintf($lang['User_post_pct_stats'], $percentage), 
	'LAST_LOGON' => ($userdata['user_level'] == ADMIN || (!$board_config['hidde_last_logon'] && $profiledata['user_allow_viewonline'])) ? (($profiledata['user_lastlogon']) ? create_date($board_config['default_dateformat'], $profiledata['user_lastlogon'], $board_config['board_timezone']) : create_date($board_config['default_dateformat'], $profiledata['user_regdate'], $board_config['board_timezone'])) : $lang['Hidden_email'], 
	'TOTAL_ONLINE_TIME' => make_hours($profiledata['user_totaltime']),
	'LAST_ONLINE_TIME' => make_hours($profiledata['user_session_time'] - $profiledata['user_lastlogon']),
	'NUMBER_OF_VISIT' => ($profiledata['user_totallogon'] > 0) ? $profiledata['user_totallogon']: $lang['None'],
	'NUMBER_OF_PAGES' => ($profiledata['user_totalpages']) ? $profiledata['user_totalpages']: $lang['None'], 
	'ZIPCODE' => ( $profiledata['user_zipcode'] ) ? $profiledata['user_zipcode'] : '--',
	'LOCATION_TIME' => @gmdate('g:i a', time() + (3600 * $profiledata['user_timezone'])),

	'SEARCH_IMG' => $search_img,
	'SEARCH' => $search,
	'PM_IMG' => $pm_img,
	'EMAIL_IMG' => $email_img,
	'AVATAR_IMG' => $avatar_img,
	'USER_SIG' => $user_sig,
	'INVENTORYLINK' => $usernameurl,
	'BUDDY_IMG' => $buddy_img,
	'VIEW_STATUS' => $online_status_img,
	'USER_MEDAL_COUNT' => $medal_count,
	'MEMBER_FOR' => $memberdays,
		
	'L_LOCATION_TIME' => $lang['Local_time'],
	'L_VIEWING_PROFILE' => sprintf($lang['Viewing_user_profile'], $profiledata['username']), 
	'L_ABOUT_USER' => $lang['About_user'], 
	'L_AVATAR' => $lang['Avatar'], 
	'L_POSTER_RANK' => $lang['Poster_rank'], 
	'L_JOINED' => $lang['Joined'], 
	'L_LOGON' => $lang['Last_logon'], 
	'L_TOTAL_POSTS' => $lang['Total_posts'], 
	'L_SEARCH_USER_POSTS' => sprintf($lang['Search_user_posts'], $profiledata['username']), 
	'L_CONTACT' => $lang['Contact'],
	'L_EMAIL_ADDRESS' => $lang['Email_address'],
	'L_EMAIL' => $lang['Email'],
	'L_PM' => $lang['Private_Message'],
	'L_USER_PROFILE'=> $lang['Profile'], 
	'L_INVENTORY' => $lang['Inventory'],
	'L_SIGNATURE' => $lang['Signature'],
	'L_USER_SPECIAL' => $lang['User_special'],
	'L_TOTAL_ONLINE_TIME' => $lang['Total_online_time'],
	'L_LAST_ONLINE_TIME' => $lang['Last_online_time'],
	'L_NUMBER_OF_VISIT' => $lang['Number_of_visit'],
	'L_NUMBER_OF_PAGES' => $lang['Number_of_pages'], 
	'L_ALL_ATTACHMENTS' => $lang['All_attachments'], 
	'L_ZIPCODE' => $lang['Zip_code'],
	'L_PERSONAL_GALLERY' => sprintf($lang['Personal_Gallery_Of_User'], $profiledata['username']),
	'L_BUDDY' => $lang['Buddy'],
	'L_VIEW_STATUS' => $lang['View_status'],
	'L_USER_MEDAL' => $lang['Medals'],
	'L_MEDAL_INFORMATION' => $lang['Medal_Information'],
	'L_MEDAL_NAME' => $lang['Medal_name'],
	'L_MEDAL_DETAIL' => $lang['Medal_details'],
	'L_MEMBER_FOR' => $lang['Member_for'],
	'L_DAYS' => ($memberdays == 1) ? $lang['Day'] : $lang['Days'],
		
	'U_CHINESE' => $u_chinese,
	'U_ZODIAC' => $u_zodiac,
	'U_SEARCH_USER' => append_sid("search.$phpEx?search_author=" . $u_search_author),
	'U_PERSONAL_GALLERY' => append_sid("album_personal.$phpEx?user_id=" . $profiledata['user_id']),
	'U_VISITS' => '<a href="' . append_sid("profile_views.$phpEx?" . POST_USERS_URL . "=" . $profiledata['user_id'] . "&amp;" . POST_POST_URL . "=0").'"><img src="' . $images['icon_view'] . '" alt="' . $lang['Profile'] . ' ' . $lang['Views'] . '" title="' . $lang['Profile'] . ' ' . $lang['Views'] . '" /></a>',
	'S_PROFILE_ACTION' => append_sid("profile.$phpEx"))
);

//
// Custom Profile Fields
//
$xd_meta = get_xd_metadata();
$xdata = get_user_xdata($HTTP_GET_VARS[POST_USERS_URL]);
while ( list($code_name, $info) = each($xd_meta) )
{
	$value = $xdata[$code_name];

	if ( !$info['allow_html'] )
	{
		$value = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $value);
	}

	if ( $info['allow_bbcode'] && $profiledata['user_sig_bbcode_uid'] != '')
	{
		$value = bbencode_second_pass($value, $profiledata['user_sig_bbcode_uid']);
	}

	if ($info['allow_bbcode'])
	{
		$value = make_clickable($value);
	}

	if ( $info['allow_smilies'] )
	{
		$value = smilies_pass($value);
	}

	$value = str_replace("\n", "\n<br />\n", $value);

	if ( $info['display_viewprofile'] == XD_DISPLAY_NORMAL )
	{
		if ( isset($xdata[$code_name]) )
		{
			$template->assign_block_vars('xdata', array(
				'NAME' => $info['field_name'],
				'VALUE' => $value)
			);
		}
	}
	elseif ( $info['display_viewprofile'] == XD_DISPLAY_ROOT )
	{
		if ( isset($xdata[$code_name]) )
		{
       		$template->assign_vars( array( $code_name => $value ) );
        	$template->assign_block_vars("switch_$code_name", array());
   		}
		else
		{
			$template->assign_block_vars("switch_no_$code_name", array());
		}
	}
}
	
	
//
// Admin only... User comments
//
if ( $userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD )
{
	$template->set_filenames (array (
		'read_comments' => 'profile_comments_body.tpl')
	);

	$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
	$s_hidden_fields .= '<input type="hidden" name="mode" value="post" />';
	$s_hidden_fields .= '<input type="hidden" name="' . POST_USERS_URL . '" value="' . $profiledata['user_id'] . '" />';

	$sql = 'SELECT ban_userid   
		FROM ' . BANLIST_TABLE . ' 
		WHERE ban_userid = ' . $profiledata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not look up banned status', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $row = $db->sql_fetchrow($result) )
	{
		$banned_username = $row['ban_userid'];
	}
	$db->sql_freeresult($result);
	
	$sql = 'SELECT ban_email  
		FROM ' . BANLIST_TABLE . ' 
		WHERE ban_email = "' . $profiledata['user_email'] . '"';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not look up banned status', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $row = $db->sql_fetchrow($result) )
	{
		$banned_email = $row['ban_email'];
	}
	$db->sql_freeresult($result);

	$template->assign_vars(array (
		'L_POSTER' => $lang['Poster'],
		'L_COMMENTS' => $lang['Comments'],
		'L_READ_COMMENTS' => $lang['Read_comments'],
		'L_TIME' => $lang['Posted'],
		'L_NO_COMMENTS' => $lang['No_comments'],
		'L_ADMIN_EDIT_PROFILE' => $lang['User_admin_profile'],
		'L_ADMIN_EDIT_PERMS' => $lang['User_admin_perms'],
		'L_USER_ACTIVE_INACTIVE' => ( $profiledata['user_active'] ) ? $lang['User_active'] : $lang['User_not_active'],
		'L_BANNED_USERNAME' => ( $banned_username ) ? $lang['Username_banned'] : $lang['Username_not_banned'],
		'L_BANNED_EMAIL' => ( $banned_email ) ? sprintf($lang['User_email_banned'], $profiledata['user_email']) : $lang['User_email_not_banned'],

		'U_ADMIN_EDIT_PROFILE' =>  "admin/admin_users.$phpEx?" . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;mode=edit&amp;returntoprofile=1&amp;sid=' . $userdata['session_id'],
		'U_ADMIN_EDIT_PERMS' => "admin/admin_ug_auth.$phpEx?" . POST_USERS_URL . '=' . $profiledata['user_id'] . '&amp;mode=user&amp;returntoprofile=1&amp;sid=' . $userdata['session_id'],
		'U_READ_COMMENTS' => append_sid('profile_comments.'.$phpEx.'?' . POST_USERS_URL . '=' . $profiledata['user_id']),

		'S_POST_ACTION' => append_sid('profile_comments.'.$phpEx),
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);

	//
	// Fetch the last 5 comments
	//
	$sql = "SELECT uc.comments, uc.time, u.user_id AS poster_id, u.username AS poster_name, u.user_level AS poster_level, time
		FROM " . USERS_COMMENTS_TABLE . " uc, " . USERS_TABLE . " u
		WHERE uc.user_id = " . $profiledata['user_id'] . "
			AND u.user_id = uc.poster_id
		ORDER BY time DESC
		LIMIT 0, 5";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not retrieve profile comments.', '', __LINE__, __FILE__, $sql);
	}

	$comments_row = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$comments_row[] = $row;
	}
	$db->sql_freeresult($result);

	if( sizeof($comments_row) == 0 )
	{
		$template->assign_block_vars('switch_no_comments', array());
	}
	else
	{
		for($i = 0; $i < sizeof($comments_row); $i++)
		{
			$row_class = ( !($i % 2) ) ? $theme['td_class2'] : $theme['td_class1'];

			$template->assign_block_vars('comments_row', array(
				'ROW_CLASS' => $row_class,
				'POSTER' => username_level_color($comments_row[$i]['poster_name'], $comments_row[$i]['poster_level'], $comments_row[$i]['poster_id']),
				'COMMENTS' => $comments_row[$i]['comments'],
				'TIME' => create_date($board_config['default_dateformat'], $comments_row[$i]['time'], $board_config['board_timezone']))
			);
		}
	}

	$template->assign_var_from_handle('S_READ_COMMENTS', 'read_comments');
}

if ( $userdata['user_level'] == ADMIN )
{
	$template->assign_block_vars('switch_user_admin', array());

	if ( $board_config['enable_topic_view_users'] )
	{
		if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_user_viewed_posts.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_user_viewed_posts.' . $phpEx);
		include($phpbb_root_path . 'includes/functions_user_viewed_posts.'.$phpEx);
		display_user_viewed_data($profiledata['user_id']);
	}
}

// Board usage stats
if ($board_config['bb_usage_stats_enable'])
{
	include($phpbb_root_path . 'includes/usercp_usage_stats.'.$phpEx);
}

// Usergroups
include($phpbb_root_path . 'includes/functions_usergroup.'.$phpEx);
if (display_usergroups($userdata['user_id'], $profiledata['user_id'], $template_file))
{
	$template->assign_block_vars('switch_groups', array(
		'L_USER_GROUP' => $lang['Groupcp'])
	);
} 

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>